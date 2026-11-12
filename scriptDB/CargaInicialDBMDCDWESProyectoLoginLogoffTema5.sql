USE DBMDCDWESProyectoLoginLogoffTema5;

-- Insercion de departamentos de prueba
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento) VALUES
('INF', 'Departamento de Informática', NOW(), 1500.50, NULL),
('CON', 'Departamento de Contabilidad', NOW(), 2500.00, NULL),
('VEN', 'Departamento de Ventas', NOW(), 3500.75, NULL);

-- Insercion de usuarios de prueba
INSERT INTO T01_Usuario (T01_CodUsuario, T01_Password, T01_DescUsuario, T01_FechaHoraUltimaConexion, T01_NumConexiones, T01_Perfil) VALUES
('admin', SHA2(CONCAT('admin', 'paso'), 256), 'Administrador del sistema', NOW(), 0, 'administrador'),
('moises', SHA2(CONCAT('moises', 'paso'), 256), 'Moisés Alberto Domínguez Cruz', NOW(), 0, 'usuario'),
('juanmi', SHA2(CONCAT('juanmi', 'paso'), 256), 'Juan Miguel Dominguez', NOW(), 0, 'usuario'),
('juan', SHA2(CONCAT('juan', 'paso'), 256), 'Juan Rivera', NOW(), 0, 'usuario'),
('yolanda', SHA2(CONCAT('yolanda', 'paso'), 256), 'Yolanda', NOW(), 0, 'usuario'),
('alvaro', SHA2(CONCAT('alvaro', 'paso'), 256), 'Alvaro', NOW(), 0, 'usuario'),
('santiago', SHA2(CONCAT('santiago', 'paso'), 256), 'Santiago', NOW(), 0, 'usuario'),
('heraclio', SHA2(CONCAT('heraclio', 'paso'), 256), 'Heraclio Borbujo', NOW(), 0, 'usuario'),
('amor', SHA2(CONCAT('amor', 'paso'), 256), 'Amor Rodriguez', NOW(), 0, 'usuario'),
('jano', SHA2(CONCAT('alejandro', 'paso'), 256), 'Alejandro', NOW(), 0, 'usuario'),
('manuel', SHA2(CONCAT('manuel', 'paso'), 256), 'Manuel Cuesta', NOW(), 0, 'usuario'),
('antonio', SHA2(CONCAT('antonio', 'paso'), 256), 'Antonio Jañez', NOW(), 0, 'usuario'),
('alberto', SHA2(CONCAT('alberto', 'paso'), 256), 'Alberto', NOW(), 0, 'usuario'),
('ainoa', SHA2(CONCAT('ainoa', 'paso'), 256), 'Ainoa Santos', NOW(), 0, 'usuario');
