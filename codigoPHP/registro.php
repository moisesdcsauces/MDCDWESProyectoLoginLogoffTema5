<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 16/11/2026
 * Proyecto Tema 5 - LoginLogoff (Registro)
 */
// Iniciamos la sesion
session_start();

// libreria de validacion y configuracion de la base de datos
require_once '../core/231018libreriaValidacion.php';
require_once '../config/configDBPDOClase.php';

// Si el usuario ya ha iniciado la sesion, lo redireccionamos al accesso privado
if (isset($_SESSION['usuarioMDCDWESAppLoginLogoff'])) {
    header("Location: inicioPrivado.php");
    exit;
}

// Si el usuario pulsa cancelar, se le devuelve al login
if (isset($_REQUEST['cancelar'])) {
    header("Location: login.php");
    exit;
}

$entradaOK = true;
$aErrores = ['usuario' => null, 'descUsuario' => null, 'password' => null, 'repetirPassword' => null];
$aUsuario = null;

// Procesamiento del formulario cuando se pulsa registrarse
if (isset($_REQUEST['registrarse'])) {
    // Validacion del formato de los campos mediante la libreria
    $aErrores['usuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['usuario'], 8, 4, 1);
    $aErrores['descUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['descUsuario'], 255, 4, 1);
    $aErrores['password'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['password'], 16, 4, 1);

    // Validar espacios en el usuario
    if (preg_match('/\s/', $_REQUEST['usuario'] ?? '')) {
        $aErrores['usuario'] = "El usuario no puede contener espacios en blanco.";
    }

    // Validar espacios en el password
    if (preg_match('/\s/', $_REQUEST['password'] ?? '')) {
        $aErrores['password'] = "El password no puede contener espacios en blanco.";
    }

    // Las dos contraseñas tienen que coincidir
    if ($_REQUEST['password'] != $_REQUEST['repetirPassword']) {
        $aErrores['repetirPassword'] = "Las contraseñas no coinciden.";
    }

    // Si el formato del usuario es correcto, la validacion continua comprobando que no exista ya en la BBDD
    if ($aErrores['usuario'] == null) {
        try {
            // Instanciamos el objeto PDO
            $miDB = new PDO(HOST, USER, PASS);

            $consulta = $miDB->prepare(
                    "SELECT T01_CodUsuario
                 FROM T01_Usuario
                 WHERE T01_CodUsuario = :usuario"
            );

            // Pasamos los parametros
            $consulta->execute([':usuario' => $_REQUEST['usuario']]);

            // Si devuelve una fila, el codigo de usuario ya esta cogido
            if ($consulta->fetch(PDO::FETCH_ASSOC)) {
                $aErrores['usuario'] = "Ese usuario ya existe, elige otro.";
            }
        } catch (PDOException $miExcepcionPDO) {
            $aErrores['usuario'] = "Error de conexion: " . $miExcepcionPDO->getMessage();
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

// Si todo es correcto, damos de alta al usuario, creamos la sesion y entramos
if ($entradaOK) {
    try {
        $miDB = new PDO(HOST, USER, PASS);

        // La contraseña se guarda con SHA2(CONCAT(usuario, password), 256), igual que en el login
        // Como el registro cuenta como primera conexion, NumConexiones empieza en 1
        $insertar = $miDB->prepare(
                "INSERT INTO T01_Usuario (T01_CodUsuario, T01_Password, T01_DescUsuario, T01_FechaHoraUltimaConexion, T01_NumConexiones, T01_Perfil)
             VALUES (:usuario, SHA2(CONCAT(:usuario, :password), 256), :descUsuario, NOW(), 1, 'usuario')"
        );
        $insertar->execute([
            ':usuario' => $_REQUEST['usuario'],
            ':password' => $_REQUEST['password'],
            ':descUsuario' => $_REQUEST['descUsuario']
        ]);

        // Leemos el usuario recien creado de la BBDD, con las mismas columnas que el login,
        // para guardar en la sesion los datos tal cual estan en la tabla.
        // T01_FechaHoraUltimaConexionAnterior queda a NULL porque todavia no hay conexion anterior
        $consulta = $miDB->prepare(
                "SELECT T01_CodUsuario, T01_DescUsuario, T01_FechaHoraUltimaConexion, T01_FechaHoraUltimaConexionAnterior, T01_NumConexiones, T01_Perfil
             FROM T01_Usuario
             WHERE T01_CodUsuario = :usuario"
        );
        $consulta->execute([':usuario' => $_REQUEST['usuario']]);
        $aUsuario = $consulta->fetch(PDO::FETCH_ASSOC);

        // Guardamos en la sesion el usuario entero en un unico array
        $_SESSION['usuarioMDCDWESAppLoginLogoff'] = $aUsuario;

        // Cerramos conexion y redirigimos al area privada
        unset($miDB);
        header("Location: ./inicioPrivado.php");
        exit;
    } catch (PDOException $miExcepcionPDO) {
        // Si falla el alta no dejamos entrar y mostramos el error en el formulario
        $aErrores['usuario'] = "Error de conexion: " . $miExcepcionPDO->getMessage();
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

            .btn-registro {
                background-color: #198754;
            }

            .btn-registro:hover {
                background-color: #157347;
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
                <h2 class="cabecera__titulo">Registro</h2>
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
                        <label for="descUsuario">Nombre completo:</label>
                        <input type="text" name="descUsuario" id="descUsuario"
                               value="<?php echo $_REQUEST['descUsuario'] ?? ''; ?>">
                        <span class="error"><?php echo $aErrores['descUsuario']; ?></span>
                    </p>
                    <p>
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password">
                        <span class="error"><?php echo $aErrores['password']; ?></span>
                    </p>
                    <p>
                        <label for="repetirPassword">Repetir contraseña:</label>
                        <input type="password" name="repetirPassword" id="repetirPassword">
                        <span class="error"><?php echo $aErrores['repetirPassword']; ?></span>
                    </p>
                    <br>
                    <input type="submit" name="registrarse" value="Registrarse" class="btn btn-registro">
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
