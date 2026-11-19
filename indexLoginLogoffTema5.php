<?php
/**
 * @author Moisés Alberto Domínguez Cruz
 * @since 11/11/2026
 * Proyecto Tema 5 - LoginLogoff (Inicio Público)
 */
// session_start();

// Si la cookie no fue elegida, la inicilizamos (codigo de juanmi)
if (!isset($_COOKIE['cookieIdioma'])) {
    // 60 segundos * 60 minutos * 24 horas * 7 dias = una semana en segundos
    setcookie('idioma', 'ES', time() + 60 * 60 * 24 * 7);
}

if (isset($_REQUEST['login'])) {
    header("Location: codigoPHP/login.php");
    exit;
}

// Si el usuario pulsa registrarse, se le lleva al formulario de registro
if (isset($_REQUEST['registro'])) {
    header("Location: codigoPHP/registro.php");
    exit;
}

// Idiomas que se pueden elegir con las banderas
$aIdiomas = ['ES', 'EN', 'PT'];

// Si el usuario pulsa una bandera, guardamos el idioma en la cookie durante una semana.
if (isset($_REQUEST['idioma']) && in_array($_REQUEST['idioma'], $aIdiomas)) {
    // 60 segundos * 60 minutos * 24 horas * 7 dias = una semana en segundos
    setcookie('idioma', $_REQUEST['idioma'], time() + 60 * 60 * 24 * 7);

    // Recargamos la pagina para que $_COOKIE ya tenga el idioma nuevo y se marque su bandera
    header("Location: " . $_SERVER['PHP_SELF']);
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
                background-color: #0d6efd;
            }

            .btn-ejecutar:hover {
                background-color: #0b5ed7;
            }

            .btn-registro {
                background-color: #198754;
            }

            .btn-registro:hover {
                background-color: #157347;
            }

            /* Selector de idioma */
            .idioma {
                margin-left: auto;
                margin-right: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .bandera {
                background: none;
                border: none;
                padding: 0;
                cursor: pointer;
                opacity: 0.6;
                transition: transform 0.2s, opacity 0.2s;
            }

            .bandera img {
                display: block;
                width: 28px;
                height: 28px;
                border-radius: 50%;
            }

            .bandera:hover {
                opacity: 1;
            }

            .bandera--activa {
                opacity: 1;
                transform: scale(1.3);
            }

            .bandera--activa img {
                box-shadow: 0 0 0 2px white;
            }

            @media (max-width: 600px) {
                .idioma {
                    margin-right: 10px;
                    gap: 8px;
                }

                .bandera img {
                    width: 20px;
                    height: 20px;
                }

                .cabecera__titulo {
                    right: 125px;
                }
            }
        </style>
    </head>
    <body class="pagina-ajustada">
        <!-- Bloque: Cabecera -->
        <header id="site-header" class="cabecera">
            <div class="cabecera__contenedor">
                <a href="./indexLoginLogoffTema5.php"><img class="cabecera__logo" src="webroot/images/logoLoginLogoff.webp" alt="Proyecto Login Logoff Tema 5"></a>
                <h2 class="cabecera__titulo">Inicio Público</h2>

                <!-- Selector de idioma: cada bandera es un boton submit con name="idioma" y su value.
                     Al pulsarlo se envia el formulario con ese idioma y se guarda la cookie. -->
                <form class="idioma" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <button type="submit" name="idioma" value="ES" title="Español" class="bandera <?php echo ($_COOKIE['cookieIdioma'] == 'ES') ? 'bandera--activa' : ''; ?>">
                        <img src="webroot/images/ES.png" alt="Español">
                    </button>

                    <button type="submit" name="idioma" value="EN" title="English" class="bandera <?php echo ($_COOKIE['cookieIdioma'] == 'EN') ? 'bandera--activa' : ''; ?>">
                        <img src="webroot/images/GB.png" alt="English">
                    </button>

                    <button type="submit" name="idioma" value="PT" title="Português" class="bandera <?php echo ($_COOKIE['cookieIdioma'] == 'PT') ? 'bandera--activa' : ''; ?>">
                        <img src="webroot/images/PT.png" alt="Portugues">
                    </button>
                </form>
            </div>
        </header>

        <main>
            <div class="container">


                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="submit" name="login" id="login" value="Iniciar Sesión" class="btn btn-ejecutar"/>
                    <input type="submit" name="registro" id="registro" value="Registrarse" class="btn btn-registro"/>
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