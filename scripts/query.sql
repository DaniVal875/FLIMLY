CREATE DATABASE Filmly;
USE Filmly;

-- (Si necesitas empezar de cero, puedes borrar las tablas antes con DROP TABLE pelicula, usuario, etc.)

CREATE TABLE productora (
    id_productora INT PRIMARY KEY AUTO_INCREMENT,
    productora VARCHAR(100)
);

CREATE TABLE pais (
    id_pais INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100)
);

CREATE TABLE pelicula (
    id_pelicula INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    fecha_estreno DATE NOT NULL,
    pais_origen INT,
    duracion INT,
    sinopsis TEXT,
    imagen VARCHAR(255),
    productora INT,
    FOREIGN KEY (productora) REFERENCES productora(id_productora),
    FOREIGN KEY (pais_origen) REFERENCES pais(id_pais)
);

CREATE TABLE genero (
    id_genero INT PRIMARY KEY AUTO_INCREMENT,
    nombre_genero VARCHAR(100) NOT NULL
);

CREATE TABLE personal (
    id_personal INT PRIMARY KEY AUTO_INCREMENT,
    nombre_personal VARCHAR(100),
    url VARCHAR(255),
    actor_director BOOLEAN
);

CREATE TABLE idioma (
    id_idioma INT PRIMARY KEY AUTO_INCREMENT,
    idioma VARCHAR(100)
);

CREATE TABLE pelicula_personal (
    id_pelicula_personal INT PRIMARY KEY AUTO_INCREMENT,
    pelicula INT,
    personal INT,
    FOREIGN KEY (pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (personal) REFERENCES personal(id_personal)
);

CREATE TABLE pelicula_genero (
    id_pelicula_genero INT PRIMARY KEY AUTO_INCREMENT,
    pelicula INT,
    genero INT,
    FOREIGN KEY (pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (genero) REFERENCES genero(id_genero)
);

CREATE TABLE pelicula_idioma (
    id_pelicula_idioma INT PRIMARY KEY AUTO_INCREMENT,
    pelicula INT,
    idioma INT,
    FOREIGN KEY (pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (idioma) REFERENCES idioma(id_idioma)
);


CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    contrasenia VARCHAR(255) NOT NULL,
    seguidores INT NOT NULL DEFAULT 0, 
    seguidos INT NOT NULL DEFAULT 0, 
    fecha_reg DATE NOT NULL
);


CREATE TABLE resenia (
    id_resenia INT PRIMARY KEY AUTO_INCREMENT,
    texto_resenia TEXT NOT NULL,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 10),
    fecha_pub DATE NOT NULL,
    usuario INT NOT NULL,
    pelicula INT NOT NULL,
    FOREIGN KEY (usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (pelicula) REFERENCES pelicula(id_pelicula)
        ON DELETE CASCADE ON UPDATE CASCADE
);