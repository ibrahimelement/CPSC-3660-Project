SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `player_match_stats`;
DROP TABLE IF EXISTS `matches`;
DROP TABLE IF EXISTS `player_team_memberships`;
DROP TABLE IF EXISTS `players`;
DROP TABLE IF EXISTS `teams`;
DROP TABLE IF EXISTS `seasons`;
DROP TABLE IF EXISTS `cookie_sessions`;
DROP TABLE IF EXISTS `user_phones`;
DROP TABLE IF EXISTS `user_profiles`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `user_id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `display_name` VARCHAR(120) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'user',
  `created_at` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  CONSTRAINT `chk_users_role` CHECK (role IN ('admin', 'user'))
);

CREATE TABLE `user_profiles` (
  `user_id` BIGINT PRIMARY KEY NOT NULL,
  `street` VARCHAR(200),
  `city` VARCHAR(120),
  `province` VARCHAR(80),
  `postal_code` VARCHAR(20),
  `country` VARCHAR(80)
);

CREATE TABLE `user_phones` (
  `phone_id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `phone_number` VARCHAR(40) NOT NULL
);

CREATE TABLE `cookie_sessions` (
  `cookie_id` VARCHAR(80) PRIMARY KEY NOT NULL,
  `user_id` BIGINT NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `seasons` (
  `season_id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `league` VARCHAR(100) NOT NULL,
  `name` VARCHAR(60) NOT NULL
);

CREATE TABLE `teams` (
  `team_id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `home_city` VARCHAR(150) NOT NULL
);

CREATE TABLE `players` (
  `player_id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `position` VARCHAR(20) NOT NULL,
  `jersey_number` INT,
  `level` VARCHAR(30),
  CONSTRAINT `chk_players_position` CHECK (position IN ('C', 'LW', 'RW', 'D', 'G')),
  CONSTRAINT `chk_players_jersey` CHECK (jersey_number IS NULL OR (jersey_number >= 0 AND jersey_number <= 99))
);

CREATE TABLE `player_team_memberships` (
  `membership_id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `player_id` BIGINT NOT NULL,
  `team_id` BIGINT NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE,
  `note` VARCHAR(255)
);

CREATE TABLE `matches` (
  `match_id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `season_id` BIGINT NOT NULL,
  `home_team_id` BIGINT NOT NULL,
  `away_team_id` BIGINT NOT NULL,
  `match_date` DATETIME NOT NULL,
  `venue` VARCHAR(150) NOT NULL,
  `home_score` INT NOT NULL DEFAULT 0,
  `away_score` INT NOT NULL DEFAULT 0
);

CREATE TABLE `player_match_stats` (
  `player_id` BIGINT NOT NULL,
  `match_id` BIGINT NOT NULL,
  `team_id` BIGINT NOT NULL,
  `goals` INT NOT NULL DEFAULT 0,
  `assists` INT NOT NULL DEFAULT 0,
  `pim` INT NOT NULL DEFAULT 0,
  `points` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`player_id`, `match_id`)
);

CREATE UNIQUE INDEX `uq_users_email` ON `users` (`email`);

CREATE UNIQUE INDEX `uq_user_phones` ON `user_phones` (`user_id`, `phone_number`);

CREATE UNIQUE INDEX `uq_seasons` ON `seasons` (`league`, `name`);

CREATE UNIQUE INDEX `uq_teams_slug` ON `teams` (`slug`);

CREATE UNIQUE INDEX `uq_matches` ON `matches` (`season_id`, `match_date`, `home_team_id`, `away_team_id`);

ALTER TABLE `user_profiles` ADD CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_phones` ADD CONSTRAINT `fk_user_phones_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cookie_sessions` ADD CONSTRAINT `fk_cookie_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `player_team_memberships` ADD CONSTRAINT `fk_ptm_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `player_team_memberships` ADD CONSTRAINT `fk_ptm_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `matches` ADD CONSTRAINT `fk_matches_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`season_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `matches` ADD CONSTRAINT `fk_matches_home_team` FOREIGN KEY (`home_team_id`) REFERENCES `teams` (`team_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `matches` ADD CONSTRAINT `fk_matches_away_team` FOREIGN KEY (`away_team_id`) REFERENCES `teams` (`team_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `player_match_stats` ADD CONSTRAINT `fk_pms_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `player_match_stats` ADD CONSTRAINT `fk_pms_match` FOREIGN KEY (`match_id`) REFERENCES `matches` (`match_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `player_match_stats` ADD CONSTRAINT `fk_pms_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE RESTRICT ON UPDATE CASCADE;
