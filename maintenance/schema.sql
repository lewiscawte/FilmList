--
-- Table structure for table `admin_notifications`
--

CREATE TABLE IF NOT EXISTS `admin_notifications` (
  `notif_id` int(11) NOT NULL AUTO_INCREMENT,
  `notif_type` blob NOT NULL,
  `notif_time` datetime NOT NULL,
  `notif_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `notif_message` int(11) NOT NULL,
  PRIMARY KEY (`notif_id`),
  UNIQUE KEY `notif_id` (`notif_id`),
  KEY `notif_user` (`notif_user`),
  KEY `notif_message` (`notif_message`),
  KEY `notif_time` (`notif_time`),
  KEY `notif_id_2` (`notif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `config_item` varchar(255) NOT NULL,
  `config_group` varchar(255) NOT NULL,
  `config_value` varchar(535) NOT NULL,
  PRIMARY KEY (`config_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE IF NOT EXISTS `film` (
  `film_id` int(11) NOT NULL AUTO_INCREMENT,
  `film_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'Text of film name',
  `film_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True/False as to if film is enabled',
  `film_year` varchar(5) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'Year of film release (major/US)',
  `film_added` date DEFAULT NULL,
  PRIMARY KEY (`film_id`),
  UNIQUE KEY `film_name` (`film_name`,`film_year`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `film_details`
--

CREATE TABLE IF NOT EXISTS `film_details` (
  `film_id` int(11) NOT NULL,
  `film_runtime` int(6) NOT NULL,
  `film_plot` blob,
  `film_budget` float NOT NULL,
  `film_budgetcurrency` char(4) NOT NULL DEFAULT 'USD',
  `film_tags` text NOT NULL,
  `film_adaptations` blob,
  PRIMARY KEY (`film_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `film_locations`
--

CREATE TABLE IF NOT EXISTS `film_locations` (
  `film_id` int(11) NOT NULL,
  `film_base` varchar(255) DEFAULT NULL,
  `film_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`film_id`),
  UNIQUE KEY `location` (`film_base`,`film_file`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `film_media`
--

CREATE TABLE IF NOT EXISTS `film_media` (
  `film_id` int(11) NOT NULL,
  `film_poster` varchar(255) NOT NULL DEFAULT 'default:blank_poster.png',
  `film_file` varchar(255) DEFAULT NULL,
  `film_screens` blob,
  UNIQUE KEY `film_id` (`film_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(41) NOT NULL,
  `user_password` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
