# Tema 5: Aplicación LoginLogoff

| CFGS DESARROLLO DE APLICACIONES WEB |
|:-----------:|
| DESARROLLO WEB EN ENTORNO SERVIDOR |

Aplicación de control de acceso con sesiones y cookies en PHP: inicio de sesión contra la base de datos, registro de usuarios, área privada, edición del perfil, cambio de contraseña y eliminación de la cuenta.

## Páginas

| Página | Descripción |
|--------|-------------|
| [Inicio público](indexLoginLogoffTema5.php) | Punto de entrada. Elección del idioma con una cookie |
| [Login](codigoPHP/login.php) | Inicio de sesión validando el usuario en la base de datos |
| [Registro](codigoPHP/registro.php) | Alta de un usuario nuevo |
| [Inicio privado](codigoPHP/inicioPrivado.php) | Bienvenida, número de conexiones y última conexión |
| [Detalle](codigoPHP/detalle.php) | Variables superglobales y phpinfo() |
| [Editar perfil](codigoPHP/editarPerfil.php) | Cambio del nombre y eliminación de la cuenta |
| [Cambiar contraseña](codigoPHP/cambiarPassword.php) | Cambio de la contraseña comprobando la actual |

## Estructura

- `indexLoginLogoffTema5.php`: inicio público.
- `codigoPHP/`: páginas de la aplicación.
- `config/`: configuración de la conexión a la base de datos.
- `core/`: librería de validación de formularios.
- `scriptDB/`: scripts para crear, cargar y borrar la base de datos.
- `webroot/`: hojas de estilo e imágenes.
- `error/`: páginas de error 404 y 403 del proyecto.
- `.htaccess`: índice del proyecto y páginas de error.

## Tecnologías

PHP 8 (PDO, sesiones y cookies), MariaDB, HTML5 y CSS3, servidor Apache.

## Ramas

- `master`: version entregada.
- `developerMDC`: rama de trabajo.

> **Moisés Alberto Domínguez Cruz**
> Curso: 2026/2027
> 2º Curso CFGS Desarrollo de Aplicaciones Web — IES Los Sauces
