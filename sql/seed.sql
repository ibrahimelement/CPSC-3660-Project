-- Admin: admin@icetrack.local / Admin1234!
-- User: user@icetrack.local / User1234
INSERT INTO users (email, password_hash, display_name, role) VALUES
(
    'admin@icetrack.local',
    '$2b$12$qkGO/m.1UG2bHEIU7GYbpuUD8jd1Oeu0AW.zMsV64G6VCSMWbUrFK',
    'IceTrack Admin',
    'admin'
),
(
    'user@icetrack.local',
    '$2b$12$jpw1Kb9WwXXWcIVVDdqXfuzRxW8AFBl/XAkoHWEohoPSERh8Fy1f.',
    'Demo User',
    'user'
);

INSERT INTO seasons (league, name) VALUES
('QMJHL', '2024-2025'),
('NHL',   '2024-2025');

INSERT INTO teams (name, slug, home_city) VALUES
('Laval Rockets',          'laval-rockets',          'Laval'),
('Quebec Remparts',        'quebec-remparts',         'Québec City'),
('Chicoutimi Saguenéens',  'chicoutimi-sagueneens',   'Chicoutimi'),
('Sherbrooke Phoenix',     'sherbrooke-phoenix',      'Sherbrooke');

INSERT INTO players (first_name, last_name, position, jersey_number, level) VALUES
('Jean',    'Tremblay',  'C',   19, 'Junior'),
('Marc',    'Gagnon',    'D',    4, 'Junior'),
('Sophie',  'Leblanc',   'G',   30, 'Junior'),
('Pierre',  'Bouchard',  'LW',  11, 'Junior'),
('Luc',     'Fortin',    'RW',  23, 'Junior'),
('André',   'Côté',      'D',    7, 'Amateur'),
('Marie',   'Bergeron',  'C',   15, 'Junior'),
('Charles', 'Lavoie',    'RW',  88, 'Junior');

INSERT INTO player_team_memberships (player_id, team_id, start_date) VALUES
(1, 1, '2024-09-01'),
(2, 1, '2024-09-01'),
(3, 1, '2024-09-01'),
(4, 2, '2024-09-01'),
(5, 2, '2024-09-01'),
(6, 3, '2024-09-01'),
(7, 3, '2024-09-01'),
(8, 4, '2024-09-01');

INSERT INTO matches
    (season_id, home_team_id, away_team_id, match_date, venue, home_score, away_score)
VALUES
    (1, 1, 2, '2025-01-15 19:00:00', 'Place Bell', 4, 2);

INSERT INTO player_match_stats (player_id, match_id, team_id, goals, assists, pim, points) VALUES
(1, 1, 1, 2, 1, 0, 3),
(2, 1, 1, 0, 2, 2, 2),
(4, 1, 2, 1, 0, 4, 1),
(5, 1, 2, 1, 1, 0, 2);
