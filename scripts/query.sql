CREATE DATABASE Filmly;
USE Filmly;
CREATE TABLE productora
(
    id_productora int PRIMARY KEY,
    productora varchar(100)
);
CREATE TABLE pais
(
    id_pais INT PRIMARY KEY,
    nombre varchar(100)
);
create table pelicula
(
	id_pelicula int PRIMARY KEY,
    nombre varchar(100) NOT null,
    fecha_estreno date not null,
    pais_origen int,
    duracion int,
    sinopsis text,
    imagen varchar(255), -- guardar ruta url de la imagen
    productora int,
    FOREIGN KEY (productora) REFERENCES productora(id_productora),
    FOREIGN KEY (pais_origen) REFERENCES pais(id_pais)
);
CREATE TABLE genero
(
    id_genero int PRIMARY KEY,
    nombre_genero varchar(100) not null
);
CREATE TABLE personal -- se refiere que en la misma tabla donde guardamos a los directores estamos guardando a los actores
(
    id_personal int PRIMARY KEY,
    nombre_personal varchar(100),
    url varchar(255), -- guardar url de alguna paguina como wikipedia donde se muestre su informacion completa
    actor_director boolean -- usamos la misma tabla tanto para actores como para directores, diferenciamos uno del otro con booleanos de ser necesario aunque deberia ser presindible 
);

create table idioma
(
    id_idioma int PRIMARY KEY,
    idioma varchar(100)
);
-- tablas intermedias de las tablas personal, genero e idioma
CREATE table pelicula_personal
(
    id_pelicula_personal int PRIMARY KEY,
    pelicula int,
    personal int,
    FOREIGN KEY(pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (personal) REFERENCES personal(id_personal)
);
CREATE TABLE pelicula_genero
(
    id_pelicula_genero int PRIMARY KEY,
    pelicula int,
    genero int,
    FOREIGN KEY (pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (genero) REFERENCES genero(id_genero)
);CREATE TABLE pelicula_idioma
(
    id_pelicula_idioma int PRIMARY KEY,
    pelicula int,
    idioma int,
    FOREIGN KEY (pelicula) REFERENCES pelicula(id_pelicula),
    FOREIGN KEY (idioma) REFERENCES idioma(id_idioma)
);

-- hasta aqui todo lo relacionado con la pelicula en si

CREATE TABLE usuario
(
    id_usuario INT PRIMARY KEY,
    nombre_usuario varchar(100) not null,
    tel_usuario varchar(10) not null,
    correo varchar(255) not null,
    contrasenia varchar(255) not null,
    fecha_reg date not null
);
create table resenia
(
    id_resenia int PRIMARY key,
    texte_re text not null,
    calificacion int CHECK (calificacion BETWEEN 1 and 10),
    fecha_pub date not null,
    usuario int not null,
    pelicula int not null,
    foreign key(id_usuario) REFERENCES usuario(usuario)
    	on delete CASCADE on UPDATE CASCADE,
    FOREIGN KEY (id_pelicula) REFERENCES pelicula(pelicula)
    	on DELETE CASCADE on UPDATE CASCADE
);