CREATE DATABASE db_Oasis23 DEFAULT CHARACTER SET utf8mb4;
USE db_Oasis23;

CREATE TABLE `tbl_camareros` (
  `id_camarero` INT NOT NULL AUTO_INCREMENT,
  `username_camarero` VARCHAR(20) NOT NULL,
  `nombre_camarero` VARCHAR(45) NOT NULL,
  `apellidos_camarero` VARCHAR(60) NOT NULL,
  `pwd_camarero` VARCHAR(80) NOT NULL,
  `imagen_camarero` VARCHAR(120),
  PRIMARY KEY (`id_camarero`)
);

CREATE TABLE `tbl_salas` (
  `id_sala` INT NOT NULL AUTO_INCREMENT,
  `nombre_sala` VARCHAR(15) NOT NULL,
  `tipo_sala` ENUM ("Terraza","Comedor","Sala Privada") NOT NULL,
  PRIMARY KEY (`id_sala`)
);

CREATE TABLE `tbl_mesas` (
  `id_mesa` INT NOT NULL AUTO_INCREMENT,
  `nombre_mesa` VARCHAR(4) NOT NULL,
  `estado_mesa` ENUM ("Libre","Ocupada") NOT NULL,
  `sillas_mesa` ENUM ("2","4","6","8","12") NOT NULL,
  `id_sala_mesa` INT NOT NULL,
  PRIMARY KEY (`id_mesa`)
);

CREATE TABLE `tbl_reservas` (
  `id_reserva` INT NOT NULL AUTO_INCREMENT,
  `hora_inicio_reserva` DATETIME NOT NULL,
  `hora_final_reserva` DATETIME NULL,
  `id_camarero_reserva` INT NOT NULL,
  `id_mesa_reserva` INT NOT NULL,
  PRIMARY KEY (`id_reserva`)
);

-- Añadir la nueva tabla de usuarios
CREATE TABLE `tbl_usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `username_usuario` VARCHAR(20) NOT NULL,
  `nombre_usuario` VARCHAR(45) NOT NULL,
  `apellidos_usuario` VARCHAR(60) NOT NULL,
  `pwd_usuario` VARCHAR(80) NOT NULL,
  `imagen_usuario` VARCHAR(120),
  `tipo_usuario` ENUM ("Administracion", "Mantenimiento", "Camarero") NOT NULL,
  PRIMARY KEY (`id_usuario`)
);

-- Conectar la tabla de usuarios con la tabla de camareros
ALTER TABLE `tbl_usuarios` ADD CONSTRAINT `usuarios_camareros_fk`
FOREIGN KEY (`id_usuario`)
REFERENCES `tbl_camareros` (`id_camarero`);



ALTER TABLE `tbl_mesas` ADD CONSTRAINT `mesas_salas_fk`
FOREIGN KEY (`id_sala_mesa`)
REFERENCES `tbl_salas` (`id_sala`);

ALTER TABLE `tbl_reservas` ADD CONSTRAINT `reservas_camareros_fk`
FOREIGN KEY (`id_camarero_reserva`)
REFERENCES `tbl_camareros` (`id_camarero`);

ALTER TABLE `tbl_reservas` ADD CONSTRAINT `reservas_mesas_fk`
FOREIGN KEY (`id_mesa_reserva`)
REFERENCES `tbl_mesas` (`id_mesa`);



INSERT INTO tbl_camareros (username_camarero, nombre_camarero, apellidos_camarero, pwd_camarero, imagen_camarero) 
VALUES
  ('ivanmoreno', 'Ivan', 'Moreno', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/ivan.jpg'),
  ('adricamarero', 'Adrian', 'Herraez', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/adri.jpg'),
  ('sergioleon', 'Sergio', 'Leon', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/sergio.jpg'),
  ('messicamarero', 'Leo', 'Messi', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/messi.jpg'),
  ('administrador', 'Ivan', 'Moreno', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/ian.jpg');

INSERT INTO tbl_salas (nombre_sala, tipo_sala) VALUES
  ('Terraza1', 'Terraza'),
  ('Terraza2', 'Terraza'),
  ('Terraza3', 'Terraza');

INSERT INTO tbl_salas (nombre_sala, tipo_sala) VALUES
  ('Comedor1', 'Comedor'),
  ('Comedor2', 'Comedor');

INSERT INTO tbl_salas (nombre_sala, tipo_sala) VALUES
  ('SalaPrivada1', 'Sala Privada'),
  ('SalaPrivada2', 'Sala Privada'),
  ('SalaPrivada3', 'Sala Privada'),
  ('SalaPrivada4', 'Sala Privada');

INSERT INTO tbl_mesas (nombre_mesa, estado_mesa, sillas_mesa, id_sala_mesa) VALUES
  ('T1M1', 'Libre', '2', 1),
  ('T1M2', 'Libre', '2', 1),
  ('T1M3', 'Libre', '4', 1),
  ('T1M4', 'Libre', '6', 1),
  ('T2M1', 'Libre', '4', 2),
  ('T2M2', 'Libre', '4', 2),
  ('T2M3', 'Libre', '4', 2),
  ('T3M1', 'Libre', '6', 3),
  ('T3M2', 'Libre', '6', 3),
  ('T3M3', 'Libre', '2', 3),
  ('C1M1', 'Libre', '2', 4),
  ('C1M2', 'Libre', '2', 4),
  ('C1M3', 'Libre', '4', 4),
  ('C2M1', 'Libre', '4', 5),
  ('C2M2', 'Libre', '6', 5),
  ('C2M3', 'Libre', '6', 5),
  ('P1M1', 'Libre', '12', 6),
  ('P2M1', 'Libre', '6', 7),
  ('P3M1', 'Libre', '6', 8),
  ('P4M1', 'Libre', '8', 9);

  INSERT INTO tbl_usuarios (username_usuario, nombre_usuario, apellidos_usuario, pwd_usuario, imagen_usuario, tipo_usuario) 
VALUES
  ('ivanmoreno', 'Ivan', 'Moreno', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/ivan.jpg', 'Camarero'),
  ('adricamarero', 'Adrian', 'Herraez', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/adri.jpg', 'Camarero'),
  ('sergioleon', 'Sergio', 'Leon', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/sergio.jpg', 'Camarero'),
  ('messicamarero', 'Leo', 'Messi', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/messi.jpg', 'Camarero'),
  ('administrador', 'Ivan', 'Moreno', '$2y$10$wigWyJ26umFhiMWROr/DK.NqNltLAI4M2dRT5l4MyPPkoy4YN5rW6', './img/ian.jpg', 'Administracion');
