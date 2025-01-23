-- Active: 1729100053215@@127.0.0.1@3306@cinema
USE cinema;

CREATE TABLE user (
  id integer PRIMARY KEY AUTO_INCREMENT,
  login varchar(20) UNIQUE NOT NULL,
  password char(255) NOT NULL,
  firstname varchar(30) NOT NULL,
  lastname varchar(30) NOT NULL,
  email varchar(60) UNIQUE NOT NULL,
  creation_date date NOT NULL,
  role ENUM('EMPLOYEE', 'CUSTOMER') NOT NULL
);

CREATE TABLE movie (
  id integer PRIMARY KEY AUTO_INCREMENT,
  title varchar(100) NOT NULL,
  description varchar(1000),
  length integer,
  category_id integer NOT NULL,
  release_date date,
  poster_name varchar(100) NOT NULL,
  FOREIGN KEY (category_id) REFERENCES category (id)
);

CREATE TABLE category (
  id integer PRIMARY KEY AUTO_INCREMENT,
  name varchar(40) UNIQUE NOT NULL
);

CREATE TABLE room (
  id integer PRIMARY KEY AUTO_INCREMENT,
  number integer UNIQUE NOT NULL,
  num_seats integer NOT NULL
);

CREATE TABLE showing (
  id integer PRIMARY KEY AUTO_INCREMENT,
  movie_id integer NOT NULL,
  room_id integer NOT NULL,
  language_id integer NOT NULL,
  date date NOT NULL,
  time time NOT NULL,
  FOREIGN KEY (movie_id) REFERENCES movie (id),
  FOREIGN KEY (room_id) REFERENCES room (id),
  FOREIGN KEY (language_id) REFERENCES language (id)
);

CREATE TABLE ticket (
  id integer PRIMARY KEY AUTO_INCREMENT,
  showing_id integer NOT NULL,
  user_id integer NOT NULL,
  seat_number integer NOT NULL,
  FOREIGN KEY (showing_id) REFERENCES showing (id),
  FOREIGN KEY (user_id) REFERENCES user (id)
);

CREATE TABLE language (
  id integer PRIMARY KEY AUTO_INCREMENT,
  name varchar(20) UNIQUE NOT NULL
);

DELIMITER $$

CREATE OR REPLACE PROCEDURE get_upcoming_movies()
BEGIN
    SELECT *
    FROM movie
    WHERE movie.release_date > CURDATE()
    ORDER BY movie.release_date
    LIMIT 3;
END$$

DELIMITER ;


