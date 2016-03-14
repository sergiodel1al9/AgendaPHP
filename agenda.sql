drop database if exists agenda;
create database agenda;

use agenda;


CREATE TABLE registro
(
id_registro INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nombre varchar(255) not null,
apellidos varchar(255) not null
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE numero
(
id_numero INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
numero varchar(255) not null,
id_registro INT NOT NULL,
FOREIGN KEY (id_registro) REFERENCES registro(id_registro) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;