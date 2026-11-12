<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 11/11/2026
 * Proyecto Tema 5 - LoginLogoff (Inicio Público)
 */
// session_start();

if (isset($_REQUEST['login'])) {
    header("Location: codigoPHP/login.php");
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
        <link rel="stylesheet" href="../webroot/css/styles.css">
        <link rel="stylesheet" href="webroot/css/estilos.css">
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
        </style>
    </head>
    <body class="pagina-ajustada">
        <!-- Bloque: Cabecera -->
        <header id="site-header" class="cabecera">
            <div class="cabecera__contenedor">
                <a href="./indexLoginLogoffTema5.php"><img class="cabecera__logo" src="webroot/images/logoLoginLogoff.webp" alt="Proyecto Login Logoff Tema 5"></a>
                <h2 class="cabecera__titulo">Inicio Público</h2>
            </div>
        </header>

        <main>
            <div class="container">


                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="submit" name="login" id="login" value="Iniciar Sesión" class="btn btn-ejecutar"/>
                </form>

                <img src="./webroot/images/modelo.png" alt="modelo de datos">
            </div>
        </main>

        <!-- Bloque: Pie -->
        <footer class="pie">
            <div class="pie__redes">
                <a class="pie__red" href="https://github.com/moisesdcsauces/MDCDWESProyectoLoginLogoffTema5" target="_blank" rel="noopener" aria-label="GitHub">
                    <img src="../webroot/images/icons/github.png" alt="">
                </a>
                <a class="pie__red" href="https://www.linkedin.com/in/mois%C3%A9s-alberto-dom%C3%ADnguez-cruz/" target="_blank" rel="noopener" aria-label="LinkedIn">
                    <img src="../webroot/images/icons/linkedin.png" alt="">
                </a>
            </div>
            <p class="pie__texto">© <a class="titulo-enlace" href="../index.html"> Moisés Alberto Domínguez Cruz</a> - IES Los Sauces<span id="year"></span></p>
        </footer>

        <script src="../webroot/js/main.js" defer></script>
    </body>
</html>