CREATE DATABASE IF NOT EXISTS catalogo_series;
USE catalogo_series;

CREATE TABLE IF NOT EXISTS series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    genero VARCHAR(255),
    ano_lancamento INT,
    temporadas INT
);