-- IceTrack Database Schema
-- MySQL 8.0+
-- Usage: mysql -u <user> -p <database_name> < sql/schema.sql

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS player_match_stats;
DROP TABLE IF EXISTS matches;
DROP TABLE IF EXISTS player_team_memberships;
DROP TABLE IF EXISTS players;
DROP TABLE IF EXISTS teams;
DROP TABLE IF EXISTS seasons;
DROP TABLE IF EXISTS cookie_sessions;
DROP TABLE IF EXISTS user_phones;
DROP TABLE IF EXISTS user_profiles;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------------------------
-- users
-- -----------------------------------------------------------------------
CREATE TABLE users (
    user_id       BIGINT       NOT NULL AUTO_INCREMENT,
    email         VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    display_name  VARCHAR(120) NOT NULL,
    role          VARCHAR(20)  NOT NULL DEFAULT 'user',
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    UNIQUE KEY uq_users_email (email),
    CONSTRAINT chk_users_role CHECK (role IN ('admin', 'user'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- user_profiles  (weak entity — 1:1 optional; composite address attribute)
-- -----------------------------------------------------------------------
CREATE TABLE user_profiles (
    user_id     BIGINT       NOT NULL,
    street      VARCHAR(200) NULL,
    city        VARCHAR(120) NULL,
    province    VARCHAR(80)  NULL,
    postal_code VARCHAR(20)  NULL,
    country     VARCHAR(80)  NULL,
    PRIMARY KEY (user_id),
    CONSTRAINT fk_user_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- user_phones  (multi-valued attribute)
-- -----------------------------------------------------------------------
CREATE TABLE user_phones (
    phone_id     BIGINT      NOT NULL AUTO_INCREMENT,
    user_id      BIGINT      NOT NULL,
    phone_number VARCHAR(40) NOT NULL,
    PRIMARY KEY (phone_id),
    UNIQUE KEY uq_user_phones (user_id, phone_number),
    CONSTRAINT fk_user_phones_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- cookie_sessions  (persistent login tokens — deferred from milestone)
-- -----------------------------------------------------------------------
CREATE TABLE cookie_sessions (
    cookie_id  VARCHAR(80) NOT NULL,
    user_id    BIGINT      NOT NULL,
    expires_at DATETIME    NOT NULL,
    created_at DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (cookie_id),
    CONSTRAINT fk_cookie_sessions_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- seasons
-- -----------------------------------------------------------------------
CREATE TABLE seasons (
    season_id BIGINT       NOT NULL AUTO_INCREMENT,
    league    VARCHAR(100) NOT NULL,
    name      VARCHAR(60)  NOT NULL,
    PRIMARY KEY (season_id),
    UNIQUE KEY uq_seasons (league, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- teams
-- -----------------------------------------------------------------------
CREATE TABLE teams (
    team_id   BIGINT       NOT NULL AUTO_INCREMENT,
    name      VARCHAR(150) NOT NULL,
    slug      VARCHAR(180) NOT NULL,
    home_city VARCHAR(150) NOT NULL,
    PRIMARY KEY (team_id),
    UNIQUE KEY uq_teams_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- players
-- -----------------------------------------------------------------------
CREATE TABLE players (
    player_id     BIGINT       NOT NULL AUTO_INCREMENT,
    first_name    VARCHAR(100) NOT NULL,
    last_name     VARCHAR(100) NOT NULL,
    position      VARCHAR(20)  NOT NULL,
    jersey_number INT          NULL,
    level         VARCHAR(30)  NULL,
    PRIMARY KEY (player_id),
    CONSTRAINT chk_players_position
        CHECK (position IN ('C', 'LW', 'RW', 'D', 'G')),
    CONSTRAINT chk_players_jersey
        CHECK (jersey_number IS NULL OR (jersey_number >= 0 AND jersey_number <= 99))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- player_team_memberships  (N:M bridge with date history)
-- -----------------------------------------------------------------------
CREATE TABLE player_team_memberships (
    membership_id BIGINT       NOT NULL AUTO_INCREMENT,
    player_id     BIGINT       NOT NULL,
    team_id       BIGINT       NOT NULL,
    start_date    DATE         NOT NULL,
    end_date      DATE         NULL,
    note          VARCHAR(255) NULL,
    PRIMARY KEY (membership_id),
    CONSTRAINT fk_ptm_player
        FOREIGN KEY (player_id) REFERENCES players(player_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ptm_team
        FOREIGN KEY (team_id) REFERENCES teams(team_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- matches
-- -----------------------------------------------------------------------
CREATE TABLE matches (
    match_id      BIGINT       NOT NULL AUTO_INCREMENT,
    season_id     BIGINT       NOT NULL,
    home_team_id  BIGINT       NOT NULL,
    away_team_id  BIGINT       NOT NULL,
    match_date    DATETIME     NOT NULL,
    venue         VARCHAR(150) NOT NULL,
    home_score    INT          NOT NULL DEFAULT 0,
    away_score    INT          NOT NULL DEFAULT 0,
    PRIMARY KEY (match_id),
    UNIQUE KEY uq_matches (season_id, match_date, home_team_id, away_team_id),
    CONSTRAINT fk_matches_season
        FOREIGN KEY (season_id) REFERENCES seasons(season_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_matches_home_team
        FOREIGN KEY (home_team_id) REFERENCES teams(team_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_matches_away_team
        FOREIGN KEY (away_team_id) REFERENCES teams(team_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
    -- Note: home_team_id <> away_team_id is enforced in application code
    -- (MySQL 8.0 does not allow CHECK constraints on FK-referenced columns)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------
-- player_match_stats
-- points is a generated (computed) column: goals + assists
-- -----------------------------------------------------------------------
CREATE TABLE player_match_stats (
    player_id BIGINT NOT NULL,
    match_id  BIGINT NOT NULL,
    team_id   BIGINT NOT NULL,
    goals     INT    NOT NULL DEFAULT 0,
    assists   INT    NOT NULL DEFAULT 0,
    pim       INT    NOT NULL DEFAULT 0,
    points    INT    GENERATED ALWAYS AS (goals + assists) VIRTUAL,
    PRIMARY KEY (player_id, match_id),
    CONSTRAINT fk_pms_player
        FOREIGN KEY (player_id) REFERENCES players(player_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_pms_match
        FOREIGN KEY (match_id) REFERENCES matches(match_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pms_team
        FOREIGN KEY (team_id) REFERENCES teams(team_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
