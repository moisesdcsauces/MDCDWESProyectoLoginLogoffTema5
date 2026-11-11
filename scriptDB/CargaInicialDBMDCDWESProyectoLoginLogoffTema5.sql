USE DBMDCDWESProyectoLoginLogoffTema5;

-- Insercion de departamentos de prueba
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento) VALUES
('INF', 'Departamento de Informática', NOW(), 1500.50, NULL),
('CON', 'Departamento de Contabilidad', NOW(), 2500.00, NULL),
('VEN', 'Departamento de Ventas', NOW(), 3500.75, NULL);

-- Insercion de usuarios de prueba
INSERT INTO T01_Usuario (T01_CodUsuario, T01_Password, T01_DescUsuario, T01_FechaHoraUltimaConexion, T01_NumConexiones, T01_Perfil) VALUES
('admin', SHA2(CONCAT('admin', 'adminpaso'), 256), 'Administrador del sistema', NOW(), 1, 'administrador'),
('moises', SHA2(CONCAT('moises', 'moisespaso'), 256), 'Moisés Alberto Domínguez Cruz', NOW(), 1, 'usuario'),
('juanmi', SHA2(CONCAT('juanmi', 'juanmipaso'), 256), 'Juan Miguel Dominguez', NOW(), 1, 'usuario'),
('juan', SHA2(CONCAT('juan', 'juanpaso'), 256), 'Juan Rivera', NOW(), 1, 'usuario'),
('yolanda', SHA2(CONCAT('yolanda', 'yolandapaso'), 256), 'Yolanda', NOW(), 1, 'usuario'),
('alvaro', SHA2(CONCAT('alvaro', 'alvaropaso'), 256), 'Alvaro', NOW(), 1, 'usuario'),
('santiago', SHA2(CONCAT('santiago', 'santiagopaso'), 256), 'Santiago', NOW(), 1, 'usuario'),
('heraclio', SHA2(CONCAT('heraclio', 'heracliopaso'), 256), 'Heraclio Borbujo', NOW(), 1, 'usuario'),
('amor', SHA2(CONCAT('amor', 'amorpaso'), 256), 'Amor Rodriguez', NOW(), 1, 'usuario'),
('jano', SHA2(CONCAT('alejandro', 'alejandropaso'), 256), 'Alejandro', NOW(), 1, 'usuario'),
('manuel', SHA2(CONCAT('manuel', 'manuelpaso'), 256), 'Manuel Cuesta', NOW(), 1, 'usuario'),
('antonio', SHA2(CONCAT('antonio', 'antoniopaso'), 256), 'Antonio Jañez', NOW(), 1, 'usuario'),
('alberto', SHA2(CONCAT('alberto', 'albertopaso'), 256), 'Alberto', NOW(), 1, 'usuario'),
('ainoa', SHA2(CONCAT('ainoa', 'ainoapaso'), 256), 'Ainoa Santos', NOW(), 1, 'usuario');
