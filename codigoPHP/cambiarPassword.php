<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 16/11/2026
 * Proyecto Tema 5 - LoginLogoff (Cambiar Contraseña)
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

// Si el usuario pulsa cancelar, vuelve a editar perfil sin cambiar nada
if (isset($_REQUEST['cancelar'])) {
    header("Location: editarPerfil.php");
    exit;
}

// Recuperamos de la sesion el array con todos los datos del usuario
$aUsuario = $_SESSION['usuarioMDCDWESAppLoginLogoff'];

$entradaOK = true;
$aErrores = ['passwordActual' => null, 'passwordNueva' => null, 'repetirPassword' => null];

// Procesamiento del formulario cuando se pulsa cambiar contraseña
if (isset($_REQUEST['cambiarPassword'])) {
    // Validacion del formato de los campos mediante la libreria
    $aErrores['passwordActual'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['passwordActual'], 16, 4, 1);
    $aErrores['passwordNueva'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['passwordNueva'], 16, 4, 1);

    // La nueva contraseña tiene que ser distinta de la actual
    if ($aErrores['passwordNueva'] == null && $_REQUEST['passwordNueva'] == $_REQUEST['passwordActual']) {
        $aErrores['passwordNueva'] = "La nueva contraseña tiene que ser distinta de la actual.";
    }

    // Las dos contraseñas nuevas tienen que coincidir
    if ($_REQUEST['passwordNueva'] != $_REQUEST['repetirPassword']) {
        $aErrores['repetirPassword'] = "Las contraseñas no coinciden.";
    }

    // Si el formato de la actual es correcto, la validacion continua comprobandola en la BBDD
    if ($aErrores['passwordActual'] == null) {
        try {
            // Instanciamos el objeto PDO
            $miDB = new PDO(HOST, USER, PASS);

            // Misma comprobacion que en el login: SHA2(CONCAT(usuario, password), 256)
            $consulta = $miDB->prepare(
                    "SELECT T01_CodUsuario
                 FROM T01_Usuario
                 WHERE T01_CodUsuario = :usuario
                 AND T01_Password = SHA2(CONCAT(:usuario, :password), 256)"
            );

            // Pasamos los parametros
            $consulta->execute([
                ':usuario' => $aUsuario['T01_CodUsuario'],
                ':password' => trim($_REQUEST['passwordActual'])
            ]);

            // Si no devuelve ninguna fila, la contraseña actual no es la correcta
            if (!$consulta->fetch(PDO::FETCH_ASSOC)) {
                $aErrores['passwordActual'] = "La contraseña actual no es correcta.";
            }
        } catch (PDOException $miExcepcionPDO) {
            $aErrores['passwordActual'] = "Error de conexion: " . $miExcepcionPDO->getMessage();
        } finally {
            unset($miDB);
        }
    }

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

// Si todo es correcto, guardamos la nueva contraseña y volvemos a editar perfil
if ($entradaOK) {
    try {
        $miDB = new PDO(HOST, USER, PASS);

        // La nueva contraseña se guarda igual que en el registro
        $actualizar = $miDB->prepare(
                "UPDATE T01_Usuario
             SET T01_Password = SHA2(CONCAT(:usuario, :password), 256)
             WHERE T01_CodUsuario = :usuario"
        );
        $actualizar->execute([
            ':usuario' => $aUsuario['T01_CodUsuario'],
            ':password' => trim($_REQUEST['passwordNueva'])
        ]);

        // Cerramos conexion y redirigimos a editar perfil
        unset($miDB);
        header("Location: ./editarPerfil.php");
        exit;
    } catch (PDOException $miExcepcionPDO) {
        // Si falla la actualizacion mostramos el error en el formulario
        $aErrores['passwordNueva'] = "Error de conexion: " . $miExcepcionPDO->getMessage();
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
                <h2 class="cabecera__titulo">Cambiar Contraseña</h2>
            </div>
        </header>

        <main>
            <div class="container">

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <p>
                        <label for="passwordActual">Contraseña actual:</label>
                        <input type="password" name="passwordActual" id="passwordActual">
                        <span class="error"><?php echo $aErrores['passwordActual']; ?></span>
                    </p>
                    <p>
                        <label for="passwordNueva">Nueva contraseña:</label>
                        <input type="password" name="passwordNueva" id="passwordNueva">
                        <span class="error"><?php echo $aErrores['passwordNueva']; ?></span>
                    </p>
                    <p>
                        <label for="repetirPassword">Repetir nueva contraseña:</label>
                        <input type="password" name="repetirPassword" id="repetirPassword">
                        <span class="error"><?php echo $aErrores['repetirPassword']; ?></span>
                    </p>
                    <br>
                    <input type="submit" name="cambiarPassword" value="Cambiar contraseña" class="btn btn-password">
                    <input type="submit" name="cancelar" value="Cancelar" class="btn btn-cancelar">
                </form>

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
    </body>
</html>
