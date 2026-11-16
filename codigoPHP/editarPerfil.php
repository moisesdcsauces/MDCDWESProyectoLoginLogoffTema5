<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 16/11/2026
 * Proyecto Tema 5 - LoginLogoff (Editar Perfil)
 */
// Iniciamos la sesion
session_start();

// libreria de validacion y configuracion de la base de datos
require_once '../core/231018libreriaValidacion.php';
require_once '../config/configDBPDOClase.php';

// Si el usuario no esta logueado, lo echamos al login
if (!isset($_SESSION['usuarioMDCDWESAppLoginLogoff'])) {
    header("Location: login.php");
    exit;
}

// Si el usuario pulsa cancelar, vuelve al inicio privado sin guardar nada
if (isset($_REQUEST['cancelar'])) {
    header("Location: inicioPrivado.php");
    exit;
}

// Si el usuario pulsa cambiar contraseña, se le lleva a su pagina
if (isset($_REQUEST['cambiarPassword'])) {
    header("Location: cambiarPassword.php");
    exit;
}

// Recuperamos de la sesion el array con todos los datos del usuario
$aUsuario = $_SESSION['usuarioMDCDWESAppLoginLogoff'];

$entradaOK = true;
$aErrores = ['descUsuario' => null, 'passwordEliminar' => null];

// Si el usuario confirma eliminar cuenta, comprobamos su contraseña, lo borramos de la BBDD,
// destruimos la sesion y volvemos al indice publico
if (isset($_REQUEST['eliminarCuenta'])) {
    // La ventana de JavaScript solo recoge la contraseña, la comprobacion de verdad se hace aqui
    $aErrores['passwordEliminar'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['passwordEliminar'], 16, 4, 1);

    if ($aErrores['passwordEliminar'] == null) {
        try {
            $miDB = new PDO(HOST, USER, PASS);

            // Solo se borra si la contraseña coincide, igual que se comprueba en el login
            $borrar = $miDB->prepare(
                    "DELETE FROM T01_Usuario
                 WHERE T01_CodUsuario = :usuario
                 AND T01_Password = SHA2(CONCAT(:usuario, :password), 256)"
            );
            $borrar->execute([
                ':usuario' => $aUsuario['T01_CodUsuario'],
                ':password' => trim($_REQUEST['passwordEliminar'])
            ]);

            // rowCount() dice cuantas filas ha borrado: si es 0, la contraseña no era correcta
            if ($borrar->rowCount() == 0) {
                $aErrores['passwordEliminar'] = "Contraseña incorrecta, la cuenta no se ha eliminado.";
            } else {
                unset($miDB);
                session_unset();
                session_destroy();
                header("Location: ../indexLoginLogoffTema5.php");
                exit;
            }
        } catch (PDOException $miExcepcionPDO) {
            // Si falla el borrado el usuario sigue dentro y se le muestra el error
            $aErrores['passwordEliminar'] = "Error de conexion: " . $miExcepcionPDO->getMessage();
        } finally {
            unset($miDB);
        }
    }
}

// Procesamiento del formulario cuando se pulsa aceptar
if (isset($_REQUEST['aceptar'])) {
    // Validacion del formato del nombre mediante la libreria
    $aErrores['descUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['descUsuario'], 255, 4, 1);

    // Recorremos el array de errores para comprobar si hay alguno
    foreach ($aErrores as $error) {
        if ($error != null) {
            $entradaOK = false;
        }
    }
} else {
    // Si no se ha enviado el formulario, forzamos a que no se procese
    $entradaOK = false;
}

// Si todo es correcto, guardamos el nuevo nombre y volvemos al inicio privado
if ($entradaOK) {
    try {
        $miDB = new PDO(HOST, USER, PASS);

        $actualizar = $miDB->prepare(
                "UPDATE T01_Usuario
             SET T01_DescUsuario = :descUsuario
             WHERE T01_CodUsuario = :usuario"
        );
        $actualizar->execute([
            ':descUsuario' => trim($_REQUEST['descUsuario']),
            ':usuario' => $aUsuario['T01_CodUsuario']
        ]);

        // Actualizamos tambien el array de la sesion para que el inicio privado muestre el nombre nuevo
        $aUsuario['T01_DescUsuario'] = trim($_REQUEST['descUsuario']);
        $_SESSION['usuarioMDCDWESAppLoginLogoff'] = $aUsuario;

        // Cerramos conexion y redirigimos al area privada
        unset($miDB);
        header("Location: ./inicioPrivado.php");
        exit;
    } catch (PDOException $miExcepcionPDO) {
        // Si falla la actualizacion mostramos el error en el formulario
        $aErrores['descUsuario'] = "Error de conexion: " . $miExcepcionPDO->getMessage();
    } finally {
        unset($miDB);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Proyecto Tema 5 PHP - LoginLogoff</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Inter:wght@400;500;600;700;800&family=UnifrakturMaguntia&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../../webroot/css/styles.css">
        <link rel="stylesheet" href="../webroot/css/estilos.css">
        <style>
            .container {
                max-width: 900px;
                margin: 30px auto;
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                text-align: center;
            }

            .btn {
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 4px;
                color: white;
                font-size: 16px;
                font-weight: bold;
                border: none;
                cursor: pointer;
            }

            .btn-editar {
                background-color: #c2410c;
            }

            .btn-editar:hover {
                background-color: #9a3412;
            }

            .btn-password {
                background-color: #474a8a;
            }

            .btn-password:hover {
                background-color: #363970;
            }

            .btn-cancelar {
                background-color: #6c757d;
            }

            .btn-cancelar:hover {
                background-color: #5c636a;
            }

            .btn-eliminar {
                background-color: #dc3545;
            }

            .btn-eliminar:hover {
                background-color: #b02a37;
            }

            input[readonly] {
                background-color: #e9ecef;
                color: #555;
            }

            .zona-peligro {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #e9ecef;
            }

            .dialogo {
                width: 400px;
                margin: auto;
                padding: 25px;
                border: none;
                border-top: 4px solid #dc3545;
                border-radius: 8px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                text-align: center;
            }

            .dialogo::backdrop {
                background-color: rgba(0,0,0,0.5);
            }

            .dialogo__titulo {
                color: #dc3545;
                margin-top: 0;
            }

            .dialogo__texto {
                color: #555;
                font-size: 15px;
            }

            .error {
                color: #dc3545;
                font-size: 13px;
                font-weight: bold;
                height: 18px;
                margin-top: 5px;
                display: block;
            }
        </style>
    </head>
    <body class="pagina-ajustada">
        <!-- Bloque: Cabecera -->
        <header id="site-header" class="cabecera">
            <div class="cabecera__contenedor">
                <a href="../indexLoginLogoffTema5.php"><img class="cabecera__logo" src="../webroot/images/logoLoginLogoff.webp" alt="Proyecto Login Logoff Tema 5"></a>
                <h2 class="cabecera__titulo">Editar Perfil</h2>
            </div>
        </header>

        <main>
            <div class="container">

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <!-- Los datos que no se pueden cambiar se muestran en solo lectura -->
                    <p>
                        <label for="usuario">Usuario:</label>
                        <input type="text" id="usuario" value="<?php echo $aUsuario['T01_CodUsuario']; ?>" readonly>
                    </p>
                    <p>
                        <label for="perfil">Perfil:</label>
                        <input type="text" id="perfil" value="<?php echo $aUsuario['T01_Perfil']; ?>" readonly>
                    </p>
                    <p>
                        <label for="numConexiones">Número de conexiones:</label>
                        <input type="text" id="numConexiones" value="<?php echo $aUsuario['T01_NumConexiones']; ?>" readonly>
                    </p>
                    <p>
                        <label for="descUsuario">Nombre completo:</label>
                        <input type="text" name="descUsuario" id="descUsuario"
                               value="<?php echo $_REQUEST['descUsuario'] ?? $aUsuario['T01_DescUsuario']; ?>">
                        <span class="error"><?php echo $aErrores['descUsuario']; ?></span>
                    </p>
                    <br>
                    <input type="submit" name="aceptar" value="Guardar cambios" class="btn btn-editar">
                    <input type="submit" name="cambiarPassword" value="Cambiar Contraseña" class="btn btn-password">
                    <input type="submit" name="cancelar" value="Cancelar" class="btn btn-cancelar">

                    <!-- Eliminar la cuenta no tiene vuelta atras, por eso va separado y abre la ventana de confirmacion -->
                    <div class="zona-peligro">
                        <input type="button" id="btnEliminar" value="Eliminar Cuenta" class="btn btn-eliminar">
                    </div>
                </form>

                <!-- Ventana que pide la contraseña antes de eliminar la cuenta -->
                <dialog id="dialogoEliminar" class="dialogo">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="formEliminar">
                        <h3 class="dialogo__titulo">Eliminar cuenta</h3>
                        <p class="dialogo__texto">¿Seguro que quieres eliminar tu cuenta? Esta acción no se puede deshacer.<br>Escribe tu contraseña para confirmarlo.</p>
                        <p>
                            <label for="passwordEliminar">Contraseña:</label>
                            <input type="password" name="passwordEliminar" id="passwordEliminar" required>
                            <span class="error" id="errorEliminar"><?php echo $aErrores['passwordEliminar']; ?></span>
                        </p>
                        <input type="submit" name="eliminarCuenta" value="Eliminar definitivamente" class="btn btn-eliminar">
                        <input type="button" id="btnCerrarDialogo" value="Cancelar" class="btn btn-cancelar">
                    </form>
                </dialog>

            </div>
        </main>

        <!-- Bloque: Pie -->
        <footer class="pie">
            <div class="pie__redes">
                <a class="pie__red" href="../indexLoginLogoffTema5.php" title="Ir al índice del Login Logoff" aria-label="Ir al índice del Login Logoff">
                    <img src="../../webroot/images/icons/casa.png" alt="">
                </a>
                <a class="pie__red" href="https://github.com/moisesdcsauces/MDCDWESProyectoLoginLogoffTema5" target="_blank" rel="noopener" aria-label="GitHub">
                    <img src="../../webroot/images/icons/github.png" alt="">
                </a>
                <a class="pie__red" href="https://www.linkedin.com/in/mois%C3%A9s-alberto-dom%C3%ADnguez-cruz/" target="_blank" rel="noopener" aria-label="LinkedIn">
                    <img src="../../webroot/images/icons/linkedin.png" alt="">
                </a>
            </div>
            <p class="pie__texto">© <a class="titulo-enlace" href="../../index.html"> Moisés Alberto Domínguez Cruz</a> - IES Los Sauces<span id="year"></span></p>
        </footer>

        <script src="../../webroot/js/main.js" defer></script>
        <script>
            // Ventana para confirmar la eliminacion de la cuenta
            const dialogoEliminar = document.getElementById('dialogoEliminar');
            const errorEliminar = document.getElementById('errorEliminar');

            // Eliminar Cuenta abre la ventana
            document.getElementById('btnEliminar').addEventListener('click', function () {
                errorEliminar.textContent = '';
                dialogoEliminar.showModal();
            });

            // Cancelar la cierra sin enviar nada
            document.getElementById('btnCerrarDialogo').addEventListener('click', function () {
                dialogoEliminar.close();
            });

            // Si el servidor ha devuelto un error, la ventana se vuelve a abrir mostrandolo
            if (errorEliminar.textContent.trim() !== '') {
                dialogoEliminar.showModal();
            }
        </script>
    </body>
</html>
