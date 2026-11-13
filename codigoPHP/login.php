<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 11/11/2026
 * Proyecto Tema 5 - LoginLogoff (Login)
 */
// Iniciamos la sesion
session_start();

// libreria de validacion y configuracion de la base de datos
require_once '../core/231018libreriaValidacion.php';
require_once '../config/configDBPDOClase.php';

// Si el usuario pulsa cancelar, se le redirige al indice publico
if (isset($_REQUEST['cancelar'])) {
    header("Location: ../indexLoginLogoffTema5.php");
    exit;
}

$entradaOK = true;
$aErrores = ['usuario' => null, 'password' => null];

// Procesamiento del formulario cuando se pulsa iniciar sesion
if (isset($_REQUEST['login'])) {
    // Validacion de los campos mediante la libreria
    $aErrores['usuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['usuario'], 8, 4, 1);
    $aErrores['password'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['password'], 16, 4, 1);

    // Recorremos el array de errores para comprobar si hay alguno
    foreach ($aErrores as $error) {
        if ($error != null) {
            $entradaOK = false;
        }
    }

    // Si la validacion es correcta, comprobamos las credenciales en la BBDD
    if ($entradaOK) {
        try {
            // Instanciamos el objeto PDO
            $miDB = new PDO(HOST, USER, PASS);

// Usamos la logica de encriptacion SHA2(CONCAT(usuario, password), 256)
            $consulta = $miDB->prepare(
                    "SELECT * FROM T01_Usuario 
                 WHERE T01_CodUsuario = :usuario 
                 AND T01_Password = SHA2(CONCAT(:usuario, :password), 256)"
            );

            // Pasamos los parametros
            $consulta->execute([
                ':usuario' => trim($_REQUEST['usuario']),
                ':password' => trim($_REQUEST['password'])
            ]);

            // Recuperamos el objeto usuario
            $oUsuario = $consulta->fetchObject();

            // Si el usuario no existe o la contraseña es incorrecta
            if (!$oUsuario) {
                $aErrores['password'] = "Usuario o contraseña incorrectos.";
                $entradaOK = false;
            } else {
                $entradaOK = true;
                // Actualizamos el numero de conexiones y la fecha de ultima conexion
                $actualizar = $miDB->prepare(
                        "UPDATE T01_Usuario 
                     SET T01_NumConexiones = T01_NumConexiones + 1, 
                         T01_FechaHoraUltimaConexion = NOW() 
                     WHERE T01_CodUsuario = :usuarioUpdate"
                );
                $actualizar->execute([':usuarioUpdate' => $oUsuario->T01_CodUsuario]);

                // Guardamos en la sesion los datos requeridos para la vista privada
                $_SESSION['usuarioMDCDWESAppLoginLogoff'] = $oUsuario->T01_CodUsuario;
                $_SESSION['descUsuario'] = $oUsuario->T01_DescUsuario;
                // Sumamos 1 al numero de conexiones para reflejar el acceso actual
                $_SESSION['numConexiones'] = $oUsuario->T01_NumConexiones + 1;
                $_SESSION['fechaHoraUltimaConexionAnterior'] = $oUsuario->T01_FechaHoraUltimaConexion;

                // Cerramos conexion y redirigimos al area privada
                unset($miDB);
                header("Location: ./inicioPrivado.php");
                exit;
            }
        } catch (PDOException $miExcepcionPDO) {
            $aErrores['password'] = "Error de conexion: " . $miExcepcionPDO->getMessage();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }
    }
} else {
    // Si no se ha enviado el formulario, forzamos a que no se procese
    $entradaOK = false;
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

            h2 {
                color: #777bb4;
                text-align: center;
                font-weight: bold;
                margin-bottom: 20px;
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

            .btn-ejecutar {
                background-color: #28a745;
            }

            .btn-ejecutar:hover {
                opacity: 0.9;
            }

            .form-group {
                margin-bottom: 20px;
                text-align: left;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .form-group label {
                margin-bottom: 5px;
                font-weight: bold;
                color: #474a8a;
            }

            .form-group input[type="text"],
            .form-group input[type="password"] {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                width: 250px;
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
                <h2 class="cabecera__titulo">Inicio de Sesión</h2>
            </div>
        </header>

        <main>
            <div class="container">


                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <p>
                        <label for="usuario">Usuario:</label>
                        <input type="text" name="usuario" id="usuario"
                               value="<?php echo $_REQUEST['usuario'] ?? ''; ?>">
                        <span class="error"><?php echo $aErrores['usuario']; ?></span>
                    </p>
                    <p>
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password">
                        <span class="error"><?php echo $aErrores['password']; ?></span>
                    </p>
                    <br>
                    <input type="submit" name="login" value="Iniciar Sesión" class="btn btn-ejecutar">
                    <input type="submit" name="cancelar" value="Cancelar" class="btn" style="background-color: #dc3545;">
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