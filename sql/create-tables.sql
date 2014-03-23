CREATE USER 'tsoha'@'localhost' IDENTIFIED BY  '***';
GRANT USAGE ON * . * TO  'tsoha'@'localhost' IDENTIFIED BY  '***';
CREATE DATABASE IF NOT EXISTS  `tsoha` ;
GRANT ALL PRIVILEGES ON  `tsoha` . * TO  'tsoha'@'localhost';

ALTER DATABASE  `tsoha` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `tsoha`.`photo` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`file_id` VARCHAR(32) NOT NULL,
`file_name` TEXT NOT NULL,
`user_id` INT NOT NULL,
`description` TEXT NOT NULL,
`short_url_id` VARCHAR(16) NOT NULL,
`rating_sum` INT NOT NULL,
`rating_count` INT NOT NULL,
`visibility` INT NOT NULL,
UNIQUE (`file_id`, `short_url_id`))
ENGINE = InnoDB;

CREATE TABLE `tsoha`.`users` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`username` VARCHAR(64) NOT NULL,
`email` VARCHAR(256) NOT NULL,
`password` VARCHAR(32) NOT NULL,
`password_salt` VARCHAR(16) NOT NULL,
`last_login` TIMESTAMP NOT NULL,
UNIQUE (`username`, `email`))
ENGINE = InnoDB;

CREATE TABLE `tsoha`.`comment` (
`photo_id` INT NOT NULL,
`comment` TEXT NOT NULL,
`comment_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`user_id` INT NOT NULL)
ENGINE = InnoDB;

CREATE TABLE  `tsoha`.`photo_tag` (
`photo_id` INT NOT NULL ,
`tag_id` INT NOT NULL
) ENGINE = INNODB;


CREATE TABLE  `tsoha`.`tag` (
`id` INT NOT NULL AUTO_INCREMENT ,
`tag_name` VARCHAR(64) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = INNODB;

ALTER TABLE `comment` ADD INDEX (`photo_id`);
ALTER TABLE `comment` ADD INDEX (`user_id`);

ALTER TABLE `comment` ADD FOREIGN KEY (`photo_id`) REFERENCES  `tsoha`.`photo` (`id`);
ALTER TABLE `comment` ADD FOREIGN KEY (`user_id`) REFERENCES  `tsoha`.`users` (`id`);

ALTER TABLE `photo` ADD INDEX (`user_id`);

ALTER TABLE `photo` ADD FOREIGN KEY (`user_id`) REFERENCES  `tsoha`.`users` (`id`);

ALTER TABLE `photo_tag` ADD INDEX (`tag_id`);
ALTER TABLE `photo_tag` ADD INDEX (`photo_id`);

ALTER TABLE `photo_tag` ADD FOREIGN KEY (`photo_id`) REFERENCES  `tsoha`.`photo` (`id`);
ALTER TABLE `photo_tag` ADD FOREIGN KEY (`tag_id`) REFERENCES  `tsoha`.`tag` (`id`);

