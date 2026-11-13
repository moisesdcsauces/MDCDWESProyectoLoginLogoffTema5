<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 11/11/2026
 * Proyecto Tema 5 - LoginLogoff (Inicio Privado)
 */
// Iniciamos la sesion
session_start();

// Si el usuario no esta logueado, lo echamos al login
if (!isset($_SESSION['usuarioMDCDWESAppLoginLogoff'])) {
    header("Location: login.php");
    exit;
}

// Si el usuario pulsa detalle, se le lleva a la pagina de detalle
if (isset($_REQUEST['detalle'])) {
    header("Location: detalle.php");
    exit;
}

// Si el usuario pulsa cerrar sesion, cerramos la sesion y volvemos al indice publico
if (isset($_REQUEST['cerrarSesion'])) {
    session_unset();
    session_destroy();
    header("Location: ../indexLoginLogoffTema5.php");
    exit;
}

// Recuperamos de la sesion los datos que guardamos en el login
$descUsuario = $_SESSION['descUsuario'];
$numConexiones = $_SESSION['numConexiones'];

// Formateamos la fecha de la ultima conexion anterior (viene de la BBDD como "Y-m-d H:i:s")
$fechaUltimaConexion = null;
if (!empty($_SESSION['fechaHoraUltimaConexionAnterior'])) {
    $oFechaUltimaConexion = new DateTime($_SESSION['fechaHoraUltimaConexionAnterior']);
    $fechaUltimaConexion = $oFechaUltimaConexion->format('d/m/Y H:i:s');
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

            .bienvenida {
                font-size: 18px;
                color: #333;
                margin-bottom: 10px;
            }

            .datos-conexion {
                font-size: 15px;
                color: #555;
                margin-bottom: 30px;
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

            .btn-detalle {
                background-color: #474a8a;
            }

            .btn-detalle:hover {
                background-color: #363970;
            }

            .btn-cerrar {
                background-color: #dc3545;
            }

            .btn-cerrar:hover {
                background-color: #b02a37;
            }
        </style>
    </head>
    <body class="pagina-ajustada">
        <!-- Bloque: Cabecera -->
        <header id="site-header" class="cabecera">
            <div class="cabecera__contenedor">
                <a href="../indexLoginLogoffTema5.php"><img class="cabecera__logo" src="../webroot/images/logoLoginLogoff.webp" alt="Proyecto Login Logoff Tema 5"></a>
                <h2 class="cabecera__titulo">Inicio Privado</h2>
            </div>
        </header>

        <main>
            <div class="container">


                <p class="bienvenida">Bienvenido/a <strong><?php echo $descUsuario; ?></strong></p>

                <?php
                // Si es la primera vez que se conecta no hay conexion anterior que mostrar
                if ($numConexiones == 1) {
                    echo "<p class='datos-conexion'>Esta es la primera vez que te conectas.</p>";
                } else {
                    echo "<p class='datos-conexion'>Esta es la <strong>" . $numConexiones . "</strong> vez que te conectas.<br>";
                    echo "Tu ultima conexion anterior fue el <strong>" . $fechaUltimaConexion . "</strong>.</p>";
                }
                ?>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="submit" name="detalle" value="Detalle" class="btn btn-detalle">
                    <input type="submit" name="cerrarSesion" value="Cerrar Sesión" class="btn btn-cerrar">
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
