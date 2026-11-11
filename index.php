<?php
// Check if the button with name="my_button" was clicked
//session_start
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
            .titulo-header{
                color: white;
            }
            header{
                text-align: left;
                padding-left: 0; 
            }
            h2{
                text-align: center;
                text-height: bold;
            }
        </style>
    </head>
    <body class="pagina-ajustada">
        <!-- Bloque: Cabecera -->
        <header id="site-header" class="cabecera">
            <div class="cabecera__contenedor">
                <h1 class="titulo-header">TEMA 5: LOGIN/LOGOFF</h1>
            </div>
        </header>

        <main>
            <div class="container">
                
                <h2>Inicio Publico</h2>

                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type="submit" name="login" id="login" value="Iniciar Sesion"/>
                </form>

            </div>
        </main>

        <!-- Bloque: Pie -->
        <footer class="pie">
            <div class="pie__redes">
                <a class="pie__red" href="https://github.com/moisesdcsauces/" target="_blank" rel="noopener" aria-label="GitHub">
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