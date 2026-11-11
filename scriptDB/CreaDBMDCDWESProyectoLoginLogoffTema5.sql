-- Creacion de la base de datos
CREATE DATABASE IF NOT EXISTS DBMDCDWESProyectoLoginLogoffTema5;

-- Uso de la base de datos
USE DBMDCDWESProyectoLoginLogoffTema5;

-- Creacion de la tabla T02_Departamento
CREATE TABLE IF NOT EXISTS T02_Departamento (
    T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaCreacionDepartamento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    T02_VolumenDeNegocio FLOAT(10,2) NOT NULL,
    T02_FechaBajaDepartamento DATETIME DEFAULT NULL
) ENGINE=InnoDB;

-- Creacion de la tabla T01_Usuario
CREATE TABLE IF NOT EXISTS T01_Usuario (
    T01_CodUsuario VARCHAR(8) PRIMARY KEY,
    T01_Password VARCHAR(255) NOT NULL,
    T01_DescUsuario VARCHAR(255) NOT NULL,
    T01_FechaHoraUltimaConexion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    T01_NumConexiones INT NOT NULL DEFAULT 1,
    T01_Perfil ENUM('usuario', 'administrador') DEFAULT 'usuario',
    T01_ImagenUsuario MEDIUMBLOB DEFAULT NULL
) ENGINE=InnoDB;

-- Creacion del usuario para la aplicación
CREATE USER IF NOT EXISTS 'userMDCDWESProyectoLoginLogoffTema5'@'%' IDENTIFIED BY 'paso';

-- Asignacion de permisos al usuario sobre la base de datos
GRANT ALL PRIVILEGES ON DBMDCDWESProyectoLoginLogoffTema5.* TO 'userMDCDWESProyectoLoginLogoffTema5'@'%';

-- Fuerza al motor a leer los cambios recientes y aplicarlos al instante sin necesidad de reiniciar el servidor.
FLUSH PRIVILEGES;
