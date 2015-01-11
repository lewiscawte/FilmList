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