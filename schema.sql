DROP DATABASE IF EXISTS `todo_php`;

CREATE DATABASE `todo_php`;

USE `todo_php`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified_at` timestamp NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`)
);

CREATE TABLE `todo_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item` text NOT NULL,
  `done` BOOLEAN NOT NULL DEFAULT 0,
  `created_by` int NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`created_by`) REFERENCES users(`id`)
);