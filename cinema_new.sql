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

CREATE OR REPLACE PROCEDURE get_upcoming_movies(IN num_movies INT)
BEGIN
  SELECT *
  FROM movie
  WHERE movie.release_date > CURDATE()
  ORDER BY movie.release_date
  LIMIT num_movies;
END$$

CREATE OR REPLACE PROCEDURE get_showings_for_date(IN date DATE)
BEGIN
  SELECT *
  FROM showing
  WHERE showing.date = date
  ORDER BY time ASC;
END$$

CREATE OR REPLACE PROCEDURE get_user_tickets_data(IN user_id INT)
BEGIN
  SELECT 
  showing.date showing_date, 
  showing.time showing_time, 
  room.number room_number,
  language.name language,
  movie.title title,
  ticket.seat_number seat_number
  FROM ticket 
  JOIN showing ON showing.id = ticket.showing_id 
  JOIN movie ON movie.id = showing.movie_id 
  JOIN language ON language.id = showing.language_id
  JOIN room ON room.id = showing.room_id
  WHERE ticket.user_id = user_id
  ORDER BY showing.date DESC, showing.time DESC;
END$$

CREATE OR REPLACE PROCEDURE give_role_to_user(IN user_id INT, IN role ENUM('EMPLOYEE', 'CUSTOMER'))
BEGIN
  UPDATE user
  SET user.role = role
  WHERE user.id = user_id;
END$$


CREATE OR REPLACE FUNCTION is_login_unique(login VARCHAR(20))
RETURNS BOOLEAN
BEGIN
  DECLARE count INT;
  SELECT COUNT(*)
  INTO count
  FROM user
  WHERE user.login = login;
  RETURN count = 0;
END$$

CREATE OR REPLACE FUNCTION is_seat_available(showing_id INT, seat_number INT)
RETURNS BOOLEAN
BEGIN
  DECLARE count INT;
  SELECT COUNT(*)
  INTO count
  FROM ticket
  WHERE ticket.showing_id = showing_id AND ticket.seat_number = seat_number;
  RETURN count = 0;
END$$

CREATE TRIGGER delete_showing_tickets
BEFORE DELETE ON showing
FOR EACH ROW
BEGIN
  DELETE FROM ticket
  WHERE ticket.showing_id = OLD.id;
END$$

CREATE TRIGGER delete_movie_showings
BEFORE DELETE ON movie
FOR EACH ROW
BEGIN
  DELETE FROM showing
  WHERE showing.movie_id = OLD.id;
END$$

CREATE TRIGGER check_room_availability_insert
BEFORE INSERT ON showing
FOR EACH ROW
BEGIN
  IF EXISTS (
    SELECT *
    FROM showing
    JOIN movie ON movie.id = showing.movie_id
    WHERE showing.room_id = NEW.room_id
    AND showing.date = NEW.date
    AND showing.time <= NEW.time AND TIME(DATE_ADD(showing.time, INTERVAL movie.length MINUTE)) >= NEW.time
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Room is already booked for this time';
  END IF;
END$$

CREATE TRIGGER check_room_availability_update
BEFORE UPDATE ON showing
FOR EACH ROW
BEGIN
  IF EXISTS (
    SELECT *
    FROM showing
    JOIN movie ON movie.id = showing.movie_id
    WHERE showing.room_id = NEW.room_id
    AND showing.date = NEW.date
    AND showing.time <= NEW.time AND TIME(DATE_ADD(showing.time, INTERVAL movie.length MINUTE)) >= NEW.time
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Room is already booked for this time';
  END IF;
END$$


DELIMITER ;


