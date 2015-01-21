INSERT INTO `config` (`config_item`, `config_group`, `config_value`) VALUES
('BaseURL', '', 'http://a2.pro/'),
('ListLimit', '', '10'),
('LoggedOutDash', '', 'true'),
('Sitename', '', 'Project Films'),
('TitleSitename', '', '1');

INSERT INTO `film` (`film_id`, `film_name`, `film_active`, `film_virtlocation`, `film_year`, `film_runtime`, `film_plot`, `film_budget`, `film_budget_currency`, `film_tags`, `film_added`) VALUES
(1, 'The LEGO Movie', 1, '', '2014', 100, '', 60000000, 'USD', 'Animation,Comedy,Adventure', NULL),
(5, 'Taken 2', 1, '/var/run/fi124', '2012', 92, NULL, 45000000, 'USD', 'Action,Thriller', NULL);
