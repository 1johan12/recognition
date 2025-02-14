CREATE TABLE `event_title` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `created_at` date NOT NULL,
  `status` TINYINT DEFAULT 1
);

CREATE TABLE `event_edition` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `edition` int NOT NULL,
  `event_title_id` int NOT NULL,
  `start_date` date,
  `end_date` date,
  `created_at` date,
  `status` TINYINT DEFAULT 1
);

CREATE TABLE `recognition` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(100),
  `fullname` varchar(100),
  `mail` varchar(100) NOT NULL,
  `access_code` varchar(20),
  `participant_type` varchar(50),
  `event_edition_id` int,
  `category_id` int
);

CREATE TABLE `category` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100)
);

ALTER TABLE `event_edition` ADD FOREIGN KEY (`event_title_id`) REFERENCES `event_title` (`id`);

ALTER TABLE `recognition` ADD FOREIGN KEY (`event_edition_id`) REFERENCES `event_edition` (`id`);

ALTER TABLE `recognition` ADD FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
