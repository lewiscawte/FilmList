CREATE TABLE IF NOT EXISTS `admin_notifications` (
  `notif_id` int(11) NOT NULL AUTO_INCREMENT,
  `notif_type` blob NOT NULL,
  `notif_time` datetime NOT NULL,
  `notif_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `notif_message` int(11) NOT NULL,
  PRIMARY KEY (`notif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `config` (
  `config_item` varchar(255) NOT NULL,
  `config_group` varchar(255) NOT NULL,
  `config_value` blob NOT NULL,
  PRIMARY KEY (`config_item`),
  UNIQUE KEY `config_item` (`config_item`),
  KEY `config_item_2` (`config_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `film` (
  `film_id` int(11) NOT NULL AUTO_INCREMENT,
  `film_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'Text of film name',
  `film_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True/False as to if film is enabled',
  `film_virtlocation` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `film_year` varchar(5) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'Year of film release (major/US)',
  `film_runtime` int(6) NOT NULL COMMENT 'Longest film is 14400 minsi',
  `film_plot` blob COMMENT 'Summary of the film',
  `film_budget` float NOT NULL,
  `film_budget_currency` char(4) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'Currency abbr.',
  `film_tags` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'Genres/tags',
  PRIMARY KEY (`film_id`),
  UNIQUE KEY `film_virtlocation` (`film_virtlocation`),
  UNIQUE KEY `film_name` (`film_name`,`film_year`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
