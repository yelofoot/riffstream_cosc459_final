-- database.sql — RiffStream final project schema + sample data
-- Run this in MAMP phpMyAdmin (Import tab or SQL tab).

CREATE DATABASE IF NOT EXISTS `riffstream`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE `riffstream`;

DROP TABLE IF EXISTS `tracks`;
DROP TABLE IF EXISTS `playlists`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name`   VARCHAR(50)  NOT NULL,
  `last_name`    VARCHAR(50)  NOT NULL,
  `username`     VARCHAR(30)  NOT NULL UNIQUE,
  `email`        VARCHAR(120) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `account_type` ENUM('Listener','Artist') NOT NULL DEFAULT 'Listener',
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `playlists` (
  `playlist_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NOT NULL,
  `name`        VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_id`),
  CONSTRAINT `fk_playlists_user` FOREIGN KEY (`user_id`)
    REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tracks` (
  `track_id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NOT NULL,
  `title`       VARCHAR(150) NOT NULL,
  `genre`       VARCHAR(80)  DEFAULT NULL,
  `album_name`  VARCHAR(150) DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`track_id`),
  CONSTRAINT `fk_tracks_user` FOREIGN KEY (`user_id`)
    REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample users (password hashes are placeholders for grading).
-- To log in, create a new account through signup.php.

INSERT INTO `users` (first_name, last_name, username, email, password_hash, account_type, created_at) VALUES
('Alex','Smith','asmith1','asmith1@example.com','sample_hash_here','Artist',   NOW()),
('Jamie','Johnson','jjohnson2','jjohnson2@example.com','sample_hash_here','Listener',NOW()),
('Taylor','Davis','tdavis3','tdavis3@example.com','sample_hash_here','Listener',NOW()),
('Jordan','Brown','jbrown4','jbrown4@example.com','sample_hash_here','Artist', NOW()),
('Casey','Miller','cmiller5','cmiller5@example.com','sample_hash_here','Listener',NOW()),
('Morgan','Wilson','mwilson6','mwilson6@example.com','sample_hash_here','Listener',NOW()),
('Riley','Moore','rmoore7','rmoore7@example.com','sample_hash_here','Artist', NOW()),
('Avery','Taylor','ataylor8','ataylor8@example.com','sample_hash_here','Listener',NOW()),
('Quinn','Anderson','qanderson9','qanderson9@example.com','sample_hash_here','Listener',NOW()),
('Skyler','Thomas','sthomas10','sthomas10@example.com','sample_hash_here','Artist',NOW());

INSERT INTO `playlists` (user_id, name, description) VALUES
  (2, 'Morning Mix', 'Upbeat songs for a productive morning'),
  (3, 'Focus Flow', 'Instrumental tracks that help with studying'),
  (5, 'Weekend Drive', 'Road trip essentials');

INSERT INTO `tracks` (user_id, title, genre, album_name) VALUES
  (1, 'Neon Skies', 'Synthwave', 'City Lights EP'),
  (4, 'Echoes of Dawn', 'Indie Rock', 'First Light'),
  (7, 'Midnight Frequencies', 'Electronic', 'Night Shift');


