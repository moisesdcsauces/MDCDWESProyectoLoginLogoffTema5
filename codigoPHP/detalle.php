<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 11/11/2026
 * Proyecto Tema 5 - LoginLogoff (Detalle)
 */

session_start();

// Si el usuario no esta logueado, lo echamos al login
if (!isset($_SESSION['usuarioMDCDWESLoginLogoff'])) {
    header("Location: login.php");
    exit;
}

// peticion de phpinfo antes de cargar el HTML
if (isset($_GET['ver_phpinfo'])) {
    phpinfo();
    exit;
}

// boton de Volver
if (isset($_REQUEST['volver'])) {
    header("Location: inicioPrivado.php");
    exit;
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
            .cabecera__contenedor {
                max-width: none;
                margin: 0;
                justify-content: flex-start;
                padding-left: 20px;
            }

            .titulo-header {
                color: white;
                text-align: left !important;
                margin: 0;
            }

            .container {
                max-width: 950px;
                margin: 30px auto;
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                text-align: left;
            }

            h2 {
                color: #777bb4;
                border-bottom: 2px solid #e9ecef;
                padding-bottom: 10px;
                margin-top: 30px;
                margin-bottom: 20px;
            }

            h3 {
                color: #474a8a;
                margin-top: 25px;
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

            .btn-volver {
                background-color: #474a8a;
            }
            
            .btn-volver:hover {
                background-color: #363970;
            }

            .btn-phpinfo {
                display: inline-block;
                background-color: #17a2b8;
                color: white;
                padding: 10px 15px;
                text-decoration: none;
                font-weight: bold;
                border-radius: 4px;
                margin-bottom: 20px;
            }
            .btn-phpinfo:hover {
                background-color: #138496;
            }

            table.tabla-superglobales {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
                margin-bottom: 30px;
            }
            table.tabla-superglobales th, table.tabla-superglobales td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
                word-break: break-word;
            }
            table.tabla-superglobales th {
                background-color: #777bb4;
                color: white;
                font-weight: bold;
            }
            table.tabla-superglobales tr:nth-child(even) {
                background-color: #f8f9fc;
            }

            pre {
                background-color: #2d2d2d;
                color: #a6e22e;
                padding: 15px;
                border-radius: 5px;
                overflow-x: auto;
                font-size: 13px;
            }

            .vacio {
                color: #999;
                font-style: italic;
                padding: 10px;
                background-color: #f8f9fc;
                border-radius: 4px;
                margin-bottom: 20px;
                border-left: 3px solid #ccc;
            }
        </style>
    </head>
    <body class="pagina-ajustada">
        <!-- Bloque: Cabecera -->
        <header id="site-header" class="cabecera">
            <div class="cabecera__contenedor">
                <h1 class="titulo-header">TEMA 5: LOGIN/LOGOFF (Detalle)</h1>
            </div>
        </header>

        <main>
            <div class="container">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" style="margin-bottom: 20px;">
                    <input type="submit" name="volver" value="⬅ Volver a Inicio Privado" class="btn btn-volver">
                </form>

                <?php
                // PHPINFO()
                echo "<h2>1. Informacion de phpinfo</h2>";
                echo "<p>Mediante la función <code>phpinfo()</code> se obtiene informacion detallada sobre la configuracion actual de PHP en el servidor.</p>";
                echo "<a href='?ver_phpinfo=1' target='_blank' class='btn-phpinfo'>Ejecutar y ver phpinfo() completo en nueva pestaña</a>";

                // RECORRIDO DE TODAS LAS SUPERGLOBALES CON FOREACH
                echo "<h2>2. Recorrido de variables Superglobales</h2>";

                // Array asociativo que agrupa todas las variables superglobales de PHP
                $aSuperglobales = [
                    '\$_SERVER'  => $_SERVER,
                    '\$_REQUEST' => $_REQUEST,
                    '\$_GET'     => $_GET,
                    '\$_POST'    => $_POST,
                    '\$_FILES'   => $_FILES,
                    '\$_COOKIE'  => $_COOKIE,
                    '\$_SESSION' => $_SESSION,
                    '\$_ENV'     => $_ENV
                ];

                // Recorrido del array
                foreach ($aSuperglobales as $nombreSuperglobal => $arraySuperglobal) {
                    echo "<h3>Contenido de $nombreSuperglobal</h3>";

                    if (!empty($arraySuperglobal)) {
                        echo "<table class='tabla-superglobales'>";
                        echo "<tr><th>Clave</th><th>Contenido</th></tr>";

                        foreach ($arraySuperglobal as $nombre => $contenido) {
                            echo "<tr>";
                            echo "<td style='width: 20%;'><strong>" . htmlspecialchars($nombre) . "</strong></td>";
                            
                            if (is_array($contenido)) {
                                echo "<td><pre>" . htmlspecialchars(print_r($contenido, true)) . "</pre></td>";
                            } else {
                                echo "<td>" . htmlspecialchars((string)$contenido) . "</td>";
                            }
                            
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='vacio'>La variable $nombreSuperglobal está vacía en este momento.</div>";
                    }
                }
                ?>
            </div>
        </main>

        <!-- Bloque: Pie -->
        <footer class="pie">
            <div class="pie__redes">
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