CREATE DATABASE IF NOT EXISTS test_Dima_Forostenko;

-- Используем созданную базу данных
USE test_dima_forostenko

-- Создание таблицы movie
CREATE TABLE IF NOT EXISTS movie (
    movieId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    releaseDate DATE,
    image VARCHAR(255)
);