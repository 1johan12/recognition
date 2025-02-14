CREATE TABLE `event_title` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  `created_at` DATETIME,
  `status` TINYINT DEFAULT 1
);

CREATE TABLE `event_edition` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `edition` INT NOT NULL,
  `event_title_id` INT NOT NULL,
  `start_date` DATETIME,
  `end_date` DATETIME,
  `created_at` DATETIME,
  `status` TINYINT DEFAULT 1,
  CONSTRAINT fk_event_title FOREIGN KEY (`event_title_id`) 
  REFERENCES `event_title` (`id`) 
  ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE `category` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100),
  `status` TINYINT DEFAULT 1
);

CREATE TABLE `recognition` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `fullname` VARCHAR(100),
  `mail` VARCHAR(100) NOT NULL,
  `access_code` VARCHAR(20),
  `created_at` DATETIME,
  `participant_type` VARCHAR(50),
  `event_edition_id` INT,
  `category_id` INT,
  CONSTRAINT fk_recognition_event_edition FOREIGN KEY (`event_edition_id`) 
  REFERENCES `event_edition` (`id`) 
  ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_recognition_category FOREIGN KEY (`category_id`) 
  REFERENCES `category` (`id`) 
  ON DELETE RESTRICT ON UPDATE CASCADE
);
