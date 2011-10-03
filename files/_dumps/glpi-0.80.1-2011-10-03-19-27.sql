#GLPI Dump database on 2011-10-03 19:27

### Dump table glpi_alerts

DROP TABLE IF EXISTS `glpi_alerts`;
CREATE TABLE `glpi_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'see define.php ALERT_* constant',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`itemtype`,`items_id`,`type`),
  KEY `type` (`type`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_authldapreplicates

DROP TABLE IF EXISTS `glpi_authldapreplicates`;
CREATE TABLE `glpi_authldapreplicates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authldaps_id` int(11) NOT NULL DEFAULT '0',
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` int(11) NOT NULL DEFAULT '389',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `authldaps_id` (`authldaps_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_authldaps

DROP TABLE IF EXISTS `glpi_authldaps`;
CREATE TABLE `glpi_authldaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `basedn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rootdn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` int(11) NOT NULL DEFAULT '389',
  `condition` text COLLATE utf8_unicode_ci,
  `login_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'uid',
  `use_tls` tinyint(1) NOT NULL DEFAULT '0',
  `group_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_condition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_search_type` int(11) NOT NULL DEFAULT '0',
  `group_member_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `realname_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `use_dn` tinyint(1) NOT NULL DEFAULT '1',
  `time_offset` int(11) NOT NULL DEFAULT '0' COMMENT 'in seconds',
  `deref_option` int(11) NOT NULL DEFAULT '0',
  `title_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_condition` text COLLATE utf8_unicode_ci,
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `rootdn_passwd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_number_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`),
  KEY `is_default` (`is_default`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_authmails

DROP TABLE IF EXISTS `glpi_authmails`;
CREATE TABLE `glpi_authmails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `connect_string` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_autoupdatesystems

DROP TABLE IF EXISTS `glpi_autoupdatesystems`;
CREATE TABLE `glpi_autoupdatesystems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_bookmarks

DROP TABLE IF EXISTS `glpi_bookmarks`;
CREATE TABLE `glpi_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'see define.php BOOKMARK_* constant',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `is_private` tinyint(1) NOT NULL DEFAULT '1',
  `entities_id` int(11) NOT NULL DEFAULT '-1',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `query` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `itemtype` (`itemtype`),
  KEY `entities_id` (`entities_id`),
  KEY `users_id` (`users_id`),
  KEY `is_private` (`is_private`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_bookmarks_users

DROP TABLE IF EXISTS `glpi_bookmarks_users`;
CREATE TABLE `glpi_bookmarks_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bookmarks_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`users_id`,`itemtype`),
  KEY `bookmarks_id` (`bookmarks_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_budgets

DROP TABLE IF EXISTS `glpi_budgets`;
CREATE TABLE `glpi_budgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `value` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_recursive` (`is_recursive`),
  KEY `entities_id` (`entities_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `begin_date` (`begin_date`),
  KEY `is_template` (`is_template`),
  KEY `date_mod` (`date_mod`),
  KEY `end_date` (`end_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_calendars

DROP TABLE IF EXISTS `glpi_calendars`;
CREATE TABLE `glpi_calendars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `date_mod` datetime DEFAULT NULL,
  `cache_duration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_calendars` VALUES ('1','Default','0','1','Default calendar',NULL,'[0,43200,43200,43200,43200,43200,0]');

### Dump table glpi_calendars_holidays

DROP TABLE IF EXISTS `glpi_calendars_holidays`;
CREATE TABLE `glpi_calendars_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `holidays_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`calendars_id`,`holidays_id`),
  KEY `holidays_id` (`holidays_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_calendarsegments

DROP TABLE IF EXISTS `glpi_calendarsegments`;
CREATE TABLE `glpi_calendarsegments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `day` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'numer of the day based on date(w)',
  `begin` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendars_id` (`calendars_id`),
  KEY `day` (`day`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_calendarsegments` VALUES ('1','1','0','0','1','08:00:00','20:00:00');
INSERT INTO `glpi_calendarsegments` VALUES ('2','1','0','0','2','08:00:00','20:00:00');
INSERT INTO `glpi_calendarsegments` VALUES ('3','1','0','0','3','08:00:00','20:00:00');
INSERT INTO `glpi_calendarsegments` VALUES ('4','1','0','0','4','08:00:00','20:00:00');
INSERT INTO `glpi_calendarsegments` VALUES ('5','1','0','0','5','08:00:00','20:00:00');

### Dump table glpi_cartridgeitems

DROP TABLE IF EXISTS `glpi_cartridgeitems`;
CREATE TABLE `glpi_cartridgeitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `cartridgeitemtypes_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `alarm_threshold` int(11) NOT NULL DEFAULT '10',
  `notepad` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `locations_id` (`locations_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `cartridgeitemtypes_id` (`cartridgeitemtypes_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `alarm_threshold` (`alarm_threshold`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_cartridgeitems_printermodels

DROP TABLE IF EXISTS `glpi_cartridgeitems_printermodels`;
CREATE TABLE `glpi_cartridgeitems_printermodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cartridgeitems_id` int(11) NOT NULL DEFAULT '0',
  `printermodels_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`printermodels_id`,`cartridgeitems_id`),
  KEY `cartridgeitems_id` (`cartridgeitems_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_cartridgeitemtypes

DROP TABLE IF EXISTS `glpi_cartridgeitemtypes`;
CREATE TABLE `glpi_cartridgeitemtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_cartridges

DROP TABLE IF EXISTS `glpi_cartridges`;
CREATE TABLE `glpi_cartridges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `cartridgeitems_id` int(11) NOT NULL DEFAULT '0',
  `printers_id` int(11) NOT NULL DEFAULT '0',
  `date_in` date DEFAULT NULL,
  `date_use` date DEFAULT NULL,
  `date_out` date DEFAULT NULL,
  `pages` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cartridgeitems_id` (`cartridgeitems_id`),
  KEY `printers_id` (`printers_id`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computerdisks

DROP TABLE IF EXISTS `glpi_computerdisks`;
CREATE TABLE `glpi_computerdisks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mountpoint` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesystems_id` int(11) NOT NULL DEFAULT '0',
  `totalsize` int(11) NOT NULL DEFAULT '0',
  `freesize` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `device` (`device`),
  KEY `mountpoint` (`mountpoint`),
  KEY `totalsize` (`totalsize`),
  KEY `freesize` (`freesize`),
  KEY `computers_id` (`computers_id`),
  KEY `filesystems_id` (`filesystems_id`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computermodels

DROP TABLE IF EXISTS `glpi_computermodels`;
CREATE TABLE `glpi_computermodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers

DROP TABLE IF EXISTS `glpi_computers`;
CREATE TABLE `glpi_computers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherserial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `date_mod` datetime DEFAULT NULL,
  `operatingsystems_id` int(11) NOT NULL DEFAULT '0',
  `operatingsystemversions_id` int(11) NOT NULL DEFAULT '0',
  `operatingsystemservicepacks_id` int(11) NOT NULL DEFAULT '0',
  `os_license_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `os_licenseid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `autoupdatesystems_id` int(11) NOT NULL DEFAULT '0',
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `domains_id` int(11) NOT NULL DEFAULT '0',
  `networks_id` int(11) NOT NULL DEFAULT '0',
  `computermodels_id` int(11) NOT NULL DEFAULT '0',
  `computertypes_id` int(11) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `notepad` longtext COLLATE utf8_unicode_ci,
  `is_ocs_import` tinyint(1) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `states_id` int(11) NOT NULL DEFAULT '0',
  `ticket_tco` decimal(20,4) DEFAULT '0.0000',
  `uuid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`),
  KEY `name` (`name`),
  KEY `is_template` (`is_template`),
  KEY `autoupdatesystems_id` (`autoupdatesystems_id`),
  KEY `domains_id` (`domains_id`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`),
  KEY `locations_id` (`locations_id`),
  KEY `computermodels_id` (`computermodels_id`),
  KEY `networks_id` (`networks_id`),
  KEY `operatingsystems_id` (`operatingsystems_id`),
  KEY `operatingsystemservicepacks_id` (`operatingsystemservicepacks_id`),
  KEY `operatingsystemversions_id` (`operatingsystemversions_id`),
  KEY `states_id` (`states_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `computertypes_id` (`computertypes_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `is_ocs_import` (`is_ocs_import`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_computers` VALUES ('1','0','320','2BVNQ15','',NULL,NULL,'0',NULL,'2011-09-19 03:14:19','12','6','0','','','0','0','0','0','0','2','0',NULL,'8','0',NULL,'0','7','0','2','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('2','0','Extensa F270','PUX09030020180EC832700','',NULL,NULL,'0',NULL,'2011-09-19 03:14:35','12','12','0','','','0','0','0','0','0','2','0',NULL,'1','0',NULL,'0','11','0','2','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('3','0','VERITON x270','PUV740C0019-130344C-3000','',NULL,NULL,'0',NULL,'2011-09-19 03:14:40','12','9','0','','','0','0','0','0','0','2','0',NULL,'1','0',NULL,'0','6','0','2','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('4','0','VERITON x270','PUV740C0019-1301569-3000','',NULL,NULL,'0',NULL,'2011-09-19 03:14:44','12','9','0','','','0','0','0','0','0','2','0',NULL,'1','0',NULL,'0','8','0','2','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('5','0','Extensa E270','PUX00030020180ECF12700','',NULL,NULL,'0',NULL,'2011-09-19 03:14:26','12','12','0','','','0','0','0','0','0','2','0',NULL,'1','0',NULL,'0','10','0','2','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('6','0','210L','59G8L1S','',NULL,NULL,'0',NULL,'2011-09-18 12:29:57','13','0','0','','','0','0','0','0','0','2','0',NULL,'8','0',NULL,'0','0','0','1','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('7','0','170L','6W4DE3S','',NULL,NULL,'0',NULL,'2011-09-18 12:30:59','13','26','0','','','0','0','0','0','0','2','0',NULL,'8','0',NULL,'0','0','0','1','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('8','0','210L','98G8L1S','',NULL,NULL,'0',NULL,'2011-09-18 12:32:06','12','6','0','','','0','0','0','0','0','2','0',NULL,'8','0',NULL,'0','0','0','1','0.0000',NULL);
INSERT INTO `glpi_computers` VALUES ('9','0','Test Product','12345','12345',NULL,NULL,'0',NULL,'2011-09-28 10:34:59','0','0','0','','','0','0','0','0','0','1','0',NULL,'12','0',NULL,'0','0','0','1','0.0000',NULL);

### Dump table glpi_computers_devicecases

DROP TABLE IF EXISTS `glpi_computers_devicecases`;
CREATE TABLE `glpi_computers_devicecases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicecases_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicecases_id` (`devicecases_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_devicecontrols

DROP TABLE IF EXISTS `glpi_computers_devicecontrols`;
CREATE TABLE `glpi_computers_devicecontrols` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicecontrols_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicecontrols_id` (`devicecontrols_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_devicedrives

DROP TABLE IF EXISTS `glpi_computers_devicedrives`;
CREATE TABLE `glpi_computers_devicedrives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicedrives_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicedrives_id` (`devicedrives_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_devicegraphiccards

DROP TABLE IF EXISTS `glpi_computers_devicegraphiccards`;
CREATE TABLE `glpi_computers_devicegraphiccards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicegraphiccards_id` int(11) NOT NULL DEFAULT '0',
  `specificity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicegraphiccards_id` (`devicegraphiccards_id`),
  KEY `specificity` (`specificity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_deviceharddrives

DROP TABLE IF EXISTS `glpi_computers_deviceharddrives`;
CREATE TABLE `glpi_computers_deviceharddrives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `deviceharddrives_id` int(11) NOT NULL DEFAULT '0',
  `specificity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `deviceharddrives_id` (`deviceharddrives_id`),
  KEY `specificity` (`specificity`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_computers_deviceharddrives` VALUES ('1','7','1','512');
INSERT INTO `glpi_computers_deviceharddrives` VALUES ('2','9','1','512');

### Dump table glpi_computers_devicememories

DROP TABLE IF EXISTS `glpi_computers_devicememories`;
CREATE TABLE `glpi_computers_devicememories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicememories_id` int(11) NOT NULL DEFAULT '0',
  `specificity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicememories_id` (`devicememories_id`),
  KEY `specificity` (`specificity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_devicemotherboards

DROP TABLE IF EXISTS `glpi_computers_devicemotherboards`;
CREATE TABLE `glpi_computers_devicemotherboards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicemotherboards_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicemotherboards_id` (`devicemotherboards_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_computers_devicemotherboards` VALUES ('1','9','1');

### Dump table glpi_computers_devicenetworkcards

DROP TABLE IF EXISTS `glpi_computers_devicenetworkcards`;
CREATE TABLE `glpi_computers_devicenetworkcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicenetworkcards_id` int(11) NOT NULL DEFAULT '0',
  `specificity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicenetworkcards_id` (`devicenetworkcards_id`),
  KEY `specificity` (`specificity`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_computers_devicenetworkcards` VALUES ('1','9','1','');

### Dump table glpi_computers_devicepcis

DROP TABLE IF EXISTS `glpi_computers_devicepcis`;
CREATE TABLE `glpi_computers_devicepcis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicepcis_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicepcis_id` (`devicepcis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_devicepowersupplies

DROP TABLE IF EXISTS `glpi_computers_devicepowersupplies`;
CREATE TABLE `glpi_computers_devicepowersupplies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicepowersupplies_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicepowersupplies_id` (`devicepowersupplies_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_deviceprocessors

DROP TABLE IF EXISTS `glpi_computers_deviceprocessors`;
CREATE TABLE `glpi_computers_deviceprocessors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `deviceprocessors_id` int(11) NOT NULL DEFAULT '0',
  `specificity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `deviceprocessors_id` (`deviceprocessors_id`),
  KEY `specificity` (`specificity`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_computers_deviceprocessors` VALUES ('1','9','1','1000');

### Dump table glpi_computers_devicesoundcards

DROP TABLE IF EXISTS `glpi_computers_devicesoundcards`;
CREATE TABLE `glpi_computers_devicesoundcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `devicesoundcards_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `devicesoundcards_id` (`devicesoundcards_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_computers_devicesoundcards` VALUES ('1','9','1');

### Dump table glpi_computers_items

DROP TABLE IF EXISTS `glpi_computers_items`;
CREATE TABLE `glpi_computers_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to various table, according to itemtype (ID)',
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`),
  KEY `item` (`itemtype`,`items_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_softwarelicenses

DROP TABLE IF EXISTS `glpi_computers_softwarelicenses`;
CREATE TABLE `glpi_computers_softwarelicenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `softwarelicenses_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`computers_id`,`softwarelicenses_id`),
  KEY `computers_id` (`computers_id`),
  KEY `softwarelicenses_id` (`softwarelicenses_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computers_softwareversions

DROP TABLE IF EXISTS `glpi_computers_softwareversions`;
CREATE TABLE `glpi_computers_softwareversions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `softwareversions_id` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`computers_id`,`softwareversions_id`),
  KEY `softwareversions_id` (`softwareversions_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_computertypes

DROP TABLE IF EXISTS `glpi_computertypes`;
CREATE TABLE `glpi_computertypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_computertypes` VALUES ('1','Laptop','');
INSERT INTO `glpi_computertypes` VALUES ('2','Desktop','');
INSERT INTO `glpi_computertypes` VALUES ('3','Tablet','');
INSERT INTO `glpi_computertypes` VALUES ('4','All-in-One PC','');

### Dump table glpi_computervirtualmachines

DROP TABLE IF EXISTS `glpi_computervirtualmachines`;
CREATE TABLE `glpi_computervirtualmachines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `virtualmachinestates_id` int(11) NOT NULL DEFAULT '0',
  `virtualmachinesystems_id` int(11) NOT NULL DEFAULT '0',
  `virtualmachinetypes_id` int(11) NOT NULL DEFAULT '0',
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `vcpu` int(11) NOT NULL DEFAULT '0',
  `ram` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_configs

DROP TABLE IF EXISTS `glpi_configs`;
CREATE TABLE `glpi_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `show_jobs_at_login` tinyint(1) NOT NULL DEFAULT '0',
  `cut` int(11) NOT NULL DEFAULT '255',
  `list_limit` int(11) NOT NULL DEFAULT '20',
  `list_limit_max` int(11) NOT NULL DEFAULT '50',
  `url_maxlength` int(11) NOT NULL DEFAULT '30',
  `version` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_loglevel` int(11) NOT NULL DEFAULT '5',
  `use_mailing` tinyint(1) NOT NULL DEFAULT '0',
  `admin_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_email_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_reply` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_reply_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mailing_signature` text COLLATE utf8_unicode_ci,
  `use_anonymous_helpdesk` tinyint(1) NOT NULL DEFAULT '0',
  `language` char(10) COLLATE utf8_unicode_ci DEFAULT 'en_GB' COMMENT 'see define.php CFG_GLPI[language] array',
  `priority_1` char(20) COLLATE utf8_unicode_ci DEFAULT '#fff2f2',
  `priority_2` char(20) COLLATE utf8_unicode_ci DEFAULT '#ffe0e0',
  `priority_3` char(20) COLLATE utf8_unicode_ci DEFAULT '#ffcece',
  `priority_4` char(20) COLLATE utf8_unicode_ci DEFAULT '#ffbfbf',
  `priority_5` char(20) COLLATE utf8_unicode_ci DEFAULT '#ffadad',
  `priority_6` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ff5555',
  `date_tax` date NOT NULL DEFAULT '2005-12-31',
  `default_alarm_threshold` int(11) NOT NULL DEFAULT '10',
  `cas_host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cas_port` int(11) NOT NULL DEFAULT '443',
  `cas_uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cas_logout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authldaps_id_extra` int(11) NOT NULL DEFAULT '0' COMMENT 'extra server',
  `existing_auth_server_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `existing_auth_server_field_clean_domain` tinyint(1) NOT NULL DEFAULT '0',
  `planning_begin` time NOT NULL DEFAULT '08:00:00',
  `planning_end` time NOT NULL DEFAULT '20:00:00',
  `utf8_conv` int(11) NOT NULL DEFAULT '0',
  `auto_assign_mode` int(11) NOT NULL DEFAULT '1',
  `use_public_faq` tinyint(1) NOT NULL DEFAULT '0',
  `url_base` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_link_in_mail` tinyint(1) NOT NULL DEFAULT '0',
  `text_login` text COLLATE utf8_unicode_ci,
  `founded_new_version` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropdown_max` int(11) NOT NULL DEFAULT '100',
  `ajax_wildcard` char(1) COLLATE utf8_unicode_ci DEFAULT '*',
  `use_ajax` tinyint(1) NOT NULL DEFAULT '0',
  `ajax_limit_count` int(11) NOT NULL DEFAULT '50',
  `use_ajax_autocompletion` tinyint(1) NOT NULL DEFAULT '1',
  `is_users_auto_add` tinyint(1) NOT NULL DEFAULT '1',
  `date_format` int(11) NOT NULL DEFAULT '0',
  `number_format` int(11) NOT NULL DEFAULT '0',
  `csv_delimiter` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `is_ids_visible` tinyint(1) NOT NULL DEFAULT '0',
  `dropdown_chars_limit` int(11) NOT NULL DEFAULT '50',
  `use_ocs_mode` tinyint(1) NOT NULL DEFAULT '0',
  `smtp_mode` int(11) NOT NULL DEFAULT '0' COMMENT 'see define.php MAIL_* constant',
  `smtp_host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtp_port` int(11) NOT NULL DEFAULT '25',
  `smtp_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proxy_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proxy_port` int(11) NOT NULL DEFAULT '8080',
  `proxy_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `add_followup_on_update_ticket` tinyint(1) NOT NULL DEFAULT '1',
  `default_contract_alert` int(11) NOT NULL DEFAULT '0',
  `default_infocom_alert` int(11) NOT NULL DEFAULT '0',
  `use_licenses_alert` tinyint(1) NOT NULL DEFAULT '0',
  `cartridges_alert_repeat` int(11) NOT NULL DEFAULT '0' COMMENT 'in seconds',
  `consumables_alert_repeat` int(11) NOT NULL DEFAULT '0' COMMENT 'in seconds',
  `keep_tickets_on_delete` tinyint(1) NOT NULL DEFAULT '1',
  `time_step` int(11) DEFAULT '5',
  `decimal_number` int(11) DEFAULT '2',
  `helpdesk_doc_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `central_doc_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `documentcategories_id_forticket` int(11) NOT NULL DEFAULT '0' COMMENT 'default category for documents added with a ticket',
  `monitors_management_restrict` int(11) NOT NULL DEFAULT '2',
  `phones_management_restrict` int(11) NOT NULL DEFAULT '2',
  `peripherals_management_restrict` int(11) NOT NULL DEFAULT '2',
  `printers_management_restrict` int(11) NOT NULL DEFAULT '2',
  `use_log_in_files` tinyint(1) NOT NULL DEFAULT '0',
  `time_offset` int(11) NOT NULL DEFAULT '0' COMMENT 'in seconds',
  `is_contact_autoupdate` tinyint(1) NOT NULL DEFAULT '1',
  `is_user_autoupdate` tinyint(1) NOT NULL DEFAULT '1',
  `is_group_autoupdate` tinyint(1) NOT NULL DEFAULT '1',
  `is_location_autoupdate` tinyint(1) NOT NULL DEFAULT '1',
  `state_autoupdate_mode` int(11) NOT NULL DEFAULT '0',
  `is_contact_autoclean` tinyint(1) NOT NULL DEFAULT '0',
  `is_user_autoclean` tinyint(1) NOT NULL DEFAULT '0',
  `is_group_autoclean` tinyint(1) NOT NULL DEFAULT '0',
  `is_location_autoclean` tinyint(1) NOT NULL DEFAULT '0',
  `state_autoclean_mode` int(11) NOT NULL DEFAULT '0',
  `use_flat_dropdowntree` tinyint(1) NOT NULL DEFAULT '0',
  `use_autoname_by_entity` tinyint(1) NOT NULL DEFAULT '1',
  `is_categorized_soft_expanded` tinyint(1) NOT NULL DEFAULT '1',
  `is_not_categorized_soft_expanded` tinyint(1) NOT NULL DEFAULT '1',
  `softwarecategories_id_ondelete` int(11) NOT NULL DEFAULT '0' COMMENT 'category applyed when a software is deleted',
  `x509_email_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_ticket_title_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_ticket_content_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  `is_ticket_category_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `default_mailcollector_filesize_max` int(11) NOT NULL DEFAULT '2097152',
  `followup_private` tinyint(1) NOT NULL DEFAULT '0',
  `task_private` tinyint(1) NOT NULL DEFAULT '0',
  `default_software_helpdesk_visible` tinyint(1) NOT NULL DEFAULT '1',
  `names_format` int(11) NOT NULL DEFAULT '0' COMMENT 'see *NAME_BEFORE constant in define.php',
  `default_graphtype` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'svg',
  `default_requesttypes_id` int(11) NOT NULL DEFAULT '1',
  `use_noright_users_add` tinyint(1) NOT NULL DEFAULT '1',
  `cron_limit` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Number of tasks execute by external cron',
  `priority_matrix` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'json encoded array for Urgence / Impact to Protority',
  `urgency_mask` int(11) NOT NULL DEFAULT '62',
  `impact_mask` int(11) NOT NULL DEFAULT '62',
  `use_infocoms_alert` tinyint(1) NOT NULL DEFAULT '0',
  `use_contracts_alert` tinyint(1) NOT NULL DEFAULT '0',
  `use_reservations_alert` tinyint(1) NOT NULL DEFAULT '0',
  `autoclose_delay` int(11) NOT NULL DEFAULT '0',
  `notclosed_delay` int(11) NOT NULL DEFAULT '0',
  `user_deleted_ldap` tinyint(1) NOT NULL DEFAULT '0',
  `auto_create_infocoms` tinyint(1) NOT NULL DEFAULT '0',
  `use_slave_for_search` tinyint(1) NOT NULL DEFAULT '0',
  `proxy_passwd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtp_passwd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transfers_id_auto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_configs` VALUES ('1','0','250','15','50','30',' 0.80.1','5','0','admsys@xxxxx.fr',NULL,NULL,NULL,'SIGNATURE','0','en_GB','#fff2f2','#ffe0e0','#ffcece','#ffbfbf','#ffadad','#ff5555','2005-12-31','10','','443','',NULL,'1',NULL,'0','08:00:00','20:00:00','1','0','0','http://localhost/itams','0','','','100','*','0','50','1','1','0','0',';','0','50','0','0',NULL,'25',NULL,NULL,'8080',NULL,'1','0','0','0','0','0','0','5','2',NULL,NULL,'0','2','2','2','2','1','0','1','1','1','1','0','0','0','0','0','0','0','1','1','1','1',NULL,'0','1','0','2097152','0','0','1','0','svg','1','1','1','{\"1\":{\"1\":1,\"2\":1,\"3\":2,\"4\":2,\"5\":2},\"2\":{\"1\":1,\"2\":2,\"3\":2,\"4\":3,\"5\":3},\"3\":{\"1\":2,\"2\":2,\"3\":3,\"4\":4,\"5\":4},\"4\":{\"1\":2,\"2\":3,\"3\":4,\"4\":4,\"5\":5},\"5\":{\"1\":2,\"2\":3,\"3\":4,\"4\":5,\"5\":5}}','62','62','0','0','0','-1','0','0','0','0',NULL,NULL,'0');

### Dump table glpi_consumableitems

DROP TABLE IF EXISTS `glpi_consumableitems`;
CREATE TABLE `glpi_consumableitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `consumableitemtypes_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `alarm_threshold` int(11) NOT NULL DEFAULT '10',
  `notepad` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `locations_id` (`locations_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `consumableitemtypes_id` (`consumableitemtypes_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `alarm_threshold` (`alarm_threshold`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_consumableitemtypes

DROP TABLE IF EXISTS `glpi_consumableitemtypes`;
CREATE TABLE `glpi_consumableitemtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_consumables

DROP TABLE IF EXISTS `glpi_consumables`;
CREATE TABLE `glpi_consumables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `consumableitems_id` int(11) NOT NULL DEFAULT '0',
  `date_in` date DEFAULT NULL,
  `date_out` date DEFAULT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_in` (`date_in`),
  KEY `date_out` (`date_out`),
  KEY `consumableitems_id` (`consumableitems_id`),
  KEY `users_id` (`users_id`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_contacts

DROP TABLE IF EXISTS `glpi_contacts`;
CREATE TABLE `glpi_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacttypes_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `notepad` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `entities_id` (`entities_id`),
  KEY `contacttypes_id` (`contacttypes_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_contacts_suppliers

DROP TABLE IF EXISTS `glpi_contacts_suppliers`;
CREATE TABLE `glpi_contacts_suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suppliers_id` int(11) NOT NULL DEFAULT '0',
  `contacts_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`suppliers_id`,`contacts_id`),
  KEY `contacts_id` (`contacts_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_contacttypes

DROP TABLE IF EXISTS `glpi_contacttypes`;
CREATE TABLE `glpi_contacttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_contacttypes` VALUES ('1','Warranty','');

### Dump table glpi_contracts

DROP TABLE IF EXISTS `glpi_contracts`;
CREATE TABLE `glpi_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `contracttypes_id` int(11) NOT NULL DEFAULT '0',
  `begin_date` date DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT '0',
  `notice` int(11) NOT NULL DEFAULT '0',
  `periodicity` int(11) NOT NULL DEFAULT '0',
  `billing` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `accounting_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `week_begin_hour` time NOT NULL DEFAULT '00:00:00',
  `week_end_hour` time NOT NULL DEFAULT '00:00:00',
  `saturday_begin_hour` time NOT NULL DEFAULT '00:00:00',
  `saturday_end_hour` time NOT NULL DEFAULT '00:00:00',
  `use_saturday` tinyint(1) NOT NULL DEFAULT '0',
  `monday_begin_hour` time NOT NULL DEFAULT '00:00:00',
  `monday_end_hour` time NOT NULL DEFAULT '00:00:00',
  `use_monday` tinyint(1) NOT NULL DEFAULT '0',
  `max_links_allowed` int(11) NOT NULL DEFAULT '0',
  `notepad` longtext COLLATE utf8_unicode_ci,
  `alert` int(11) NOT NULL DEFAULT '0',
  `renewal` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `begin_date` (`begin_date`),
  KEY `name` (`name`),
  KEY `contracttypes_id` (`contracttypes_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `use_monday` (`use_monday`),
  KEY `use_saturday` (`use_saturday`),
  KEY `alert` (`alert`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_contracts_items

DROP TABLE IF EXISTS `glpi_contracts_items`;
CREATE TABLE `glpi_contracts_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contracts_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`contracts_id`,`itemtype`,`items_id`),
  KEY `FK_device` (`items_id`,`itemtype`),
  KEY `item` (`itemtype`,`items_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_contracts_suppliers

DROP TABLE IF EXISTS `glpi_contracts_suppliers`;
CREATE TABLE `glpi_contracts_suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suppliers_id` int(11) NOT NULL DEFAULT '0',
  `contracts_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`suppliers_id`,`contracts_id`),
  KEY `contracts_id` (`contracts_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_contracttypes

DROP TABLE IF EXISTS `glpi_contracttypes`;
CREATE TABLE `glpi_contracttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_contracttypes` VALUES ('1','Annual','');
INSERT INTO `glpi_contracttypes` VALUES ('2','Semi Annual','');
INSERT INTO `glpi_contracttypes` VALUES ('3','Quarterly','');

### Dump table glpi_crontasklogs

DROP TABLE IF EXISTS `glpi_crontasklogs`;
CREATE TABLE `glpi_crontasklogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crontasks_id` int(11) NOT NULL,
  `crontasklogs_id` int(11) NOT NULL COMMENT 'id of ''start'' event',
  `date` datetime NOT NULL,
  `state` int(11) NOT NULL COMMENT '0:start, 1:run, 2:stop',
  `elapsed` float NOT NULL COMMENT 'time elapsed since start',
  `volume` int(11) NOT NULL COMMENT 'for statistics',
  `content` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'message',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `crontasks_id` (`crontasks_id`),
  KEY `crontasklogs_id_state` (`crontasklogs_id`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_crontasklogs` VALUES ('1','6','0','2011-01-18 11:40:43','0','0','0','Mode d\'exÃƒÂ©cution : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('2','6','1','2011-01-18 11:40:43','2','0.00222397','0','Action terminÃƒÂ©e, rien ÃƒÂ  faire');
INSERT INTO `glpi_crontasklogs` VALUES ('3','8','0','2011-03-04 11:35:21','0','0','0','Mode d\'exÃ©cution : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('4','8','3','2011-03-04 11:35:21','2','0.0720789','184','Action terminÃ©e, traitement complet');
INSERT INTO `glpi_crontasklogs` VALUES ('5','9','0','2011-06-28 11:34:37','0','0','0','Mode d\'exécution : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('6','9','5','2011-06-28 11:34:37','2','0.0198219','0','Action terminée, rien à faire');
INSERT INTO `glpi_crontasklogs` VALUES ('7','12','0','2011-09-18 11:30:45','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('8','12','7','2011-09-18 11:30:45','2','0.372943','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('9','13','0','2011-09-18 11:31:14','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('10','13','9','2011-09-18 11:31:14','2','0.098877','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('11','14','0','2011-09-18 11:31:25','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('12','14','11','2011-09-18 11:31:25','2','0.10838','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('13','15','0','2011-09-18 11:31:33','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('14','15','13','2011-09-18 11:31:33','2','0.147525','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('15','16','0','2011-09-18 11:36:36','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('16','16','15','2011-09-18 11:36:36','2','0.108193','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('17','17','0','2011-09-18 11:36:46','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('18','17','17','2011-09-18 11:36:46','2','0.100417','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('19','18','0','2011-09-18 11:38:01','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('20','18','19','2011-09-18 11:38:01','2','0.175159','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('21','19','0','2011-09-18 11:41:47','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('22','19','21','2011-09-18 11:41:47','2','0.117635','0','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('23','5','0','2011-09-18 11:41:50','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('24','5','23','2011-09-18 11:41:50','2','0.107353','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('25','6','0','2011-09-18 11:43:11','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('26','6','25','2011-09-18 11:43:11','2','0.125688','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('27','8','0','2011-09-18 11:43:39','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('28','8','27','2011-09-18 11:43:39','2','2.23361','184','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('29','9','0','2011-09-18 11:46:52','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('30','9','29','2011-09-18 11:46:52','2','0.229301','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('31','17','0','2011-09-18 11:47:00','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('32','17','31','2011-09-18 11:47:00','2','0.153358','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('33','17','0','2011-09-18 11:52:54','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('34','17','33','2011-09-18 11:52:54','2','0.313258','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('35','9','0','2011-09-18 12:00:08','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('36','9','35','2011-09-18 12:00:08','2','0.323131','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('37','17','0','2011-09-18 12:01:19','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('38','17','37','2011-09-18 12:01:19','2','0.098912','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('39','17','0','2011-09-18 12:12:32','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('40','17','39','2011-09-18 12:12:32','2','0.106977','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('41','9','0','2011-09-18 12:18:43','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('42','9','41','2011-09-18 12:18:43','2','0.117066','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('43','17','0','2011-09-18 12:23:58','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('44','17','43','2011-09-18 12:23:58','2','0.0983992','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('45','9','0','2011-09-18 12:29:58','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('46','9','45','2011-09-18 12:29:58','2','0.151047','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('47','17','0','2011-09-18 12:30:53','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('48','17','47','2011-09-18 12:30:53','2','0.504539','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('49','13','0','2011-09-19 03:00:00','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('50','13','49','2011-09-19 03:00:00','2','0.340172','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('51','14','0','2011-09-19 03:00:05','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('52','14','51','2011-09-19 03:00:05','2','0.0320241','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('53','17','0','2011-09-19 03:05:20','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('54','17','53','2011-09-19 03:05:20','2','0.061661','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('55','9','0','2011-09-19 03:08:36','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('56','9','55','2011-09-19 03:08:36','2','0.126079','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('57','15','0','2011-09-19 03:09:06','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('58','15','57','2011-09-19 03:09:06','2','0.0690222','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('59','16','0','2011-09-19 03:09:14','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('60','16','59','2011-09-19 03:09:14','2','0.0307529','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('61','17','0','2011-09-19 03:11:22','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('62','17','61','2011-09-19 03:11:22','2','0.0353589','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('63','17','0','2011-09-19 03:24:08','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('64','17','63','2011-09-19 03:24:08','2','0.0521379','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('65','9','0','2011-09-19 03:36:44','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('66','9','65','2011-09-19 03:36:44','2','0.0431888','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('67','17','0','2011-09-19 03:55:56','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('68','17','67','2011-09-19 03:55:56','2','0.0357692','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('69','9','0','2011-09-19 03:56:42','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('70','9','69','2011-09-19 03:56:42','2','0.0371752','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('71','13','0','2011-09-19 04:00:35','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('72','13','71','2011-09-19 04:00:35','2','0.039094','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('73','14','0','2011-09-19 04:08:35','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('74','14','73','2011-09-19 04:08:35','2','0.0454111','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('75','17','0','2011-09-19 04:08:50','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('76','17','75','2011-09-19 04:08:50','2','0.0948031','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('77','9','0','2011-09-19 04:09:09','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('78','9','77','2011-09-19 04:09:09','2','0.0825338','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('79','17','0','2011-09-19 04:15:21','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('80','17','79','2011-09-19 04:15:21','2','0.0961978','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('81','9','0','2011-09-21 07:15:18','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('82','9','81','2011-09-21 07:15:18','2','0.484368','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('83','17','0','2011-09-22 20:58:21','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('84','17','83','2011-09-22 20:58:21','2','0.040025','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('85','13','0','2011-09-23 09:43:06','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('86','13','85','2011-09-23 09:43:06','2','0.119293','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('87','14','0','2011-09-23 10:06:41','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('88','14','87','2011-09-23 10:06:41','2','0.040345','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('89','12','0','2011-09-23 10:12:06','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('90','12','89','2011-09-23 10:12:06','1','0.0591512','1','Clean 1 session file(s) created since more than 180 seconds
');
INSERT INTO `glpi_crontasklogs` VALUES ('91','12','89','2011-09-23 10:12:06','2','0.07025','1','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('92','18','0','2011-09-23 10:21:51','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('93','18','92','2011-09-23 10:21:51','2','0.0907831','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('94','19','0','2011-09-23 10:26:54','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('95','19','94','2011-09-23 10:26:54','2','0.0718679','0','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('96','5','0','2011-09-23 10:40:55','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('97','5','96','2011-09-23 10:40:55','2','0.042896','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('98','6','0','2011-09-23 10:46:29','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('99','6','98','2011-09-23 10:46:29','2','0.040904','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('100','15','0','2011-09-28 07:30:02','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('101','15','100','2011-09-28 07:30:02','2','0.577865','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('102','16','0','2011-09-28 07:31:57','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('103','16','102','2011-09-28 07:31:57','2','0.052238','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('104','9','0','2011-09-28 07:48:41','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('105','9','104','2011-09-28 07:48:41','2','0.0515571','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('106','17','0','2011-09-28 08:24:12','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('107','17','106','2011-09-28 08:24:12','2','0.0411632','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('108','13','0','2011-09-28 08:30:01','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('109','13','108','2011-09-28 08:30:01','1','0.0854571','8','Clean 8 graph file(s) created since more than 3600 seconds
');
INSERT INTO `glpi_crontasklogs` VALUES ('110','13','108','2011-09-28 08:30:01','2','0.093013','8','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('111','14','0','2011-09-28 09:10:43','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('112','14','111','2011-09-28 09:10:43','2','0.0769689','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('113','12','0','2011-09-28 09:22:06','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('114','12','113','2011-09-28 09:22:06','1','0.0387759','1','Clean 1 session file(s) created since more than 180 seconds
');
INSERT INTO `glpi_crontasklogs` VALUES ('115','12','113','2011-09-28 09:22:06','2','0.046082','1','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('116','18','0','2011-09-28 09:35:13','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('117','18','116','2011-09-28 09:35:13','2','0.137981','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('118','19','0','2011-09-28 09:49:30','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('119','19','118','2011-09-28 09:49:30','2','0.0408161','0','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('120','5','0','2011-09-28 10:06:23','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('121','5','120','2011-09-28 10:06:23','2','0.037272','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('122','6','0','2011-09-28 10:13:24','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('123','6','122','2011-09-28 10:13:24','2','0.0392151','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('124','8','0','2011-09-28 10:18:27','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('125','8','124','2011-09-28 10:18:27','2','1.25767','190','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('126','9','0','2011-09-28 10:25:41','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('127','9','126','2011-09-28 10:25:41','2','0.0475039','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('128','17','0','2011-09-28 10:32:25','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('129','17','128','2011-09-28 10:32:25','2','0.042855','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('130','13','0','2011-09-28 10:46:30','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('131','13','130','2011-09-28 10:46:30','2','0.038569','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('132','14','0','2011-09-28 10:54:03','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('133','14','132','2011-09-28 10:54:03','2','0.0366719','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('134','9','0','2011-09-28 10:59:28','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('135','9','134','2011-09-28 10:59:28','2','0.0494211','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('136','17','0','2011-09-28 11:07:31','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('137','17','136','2011-09-28 11:07:31','2','0.0399799','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('138','9','0','2011-09-28 11:14:17','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('139','9','138','2011-09-28 11:14:17','2','0.043319','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('140','17','0','2011-09-28 11:21:00','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('141','17','140','2011-09-28 11:21:00','2','0.0370569','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('142','9','0','2011-09-28 11:26:38','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('143','9','142','2011-09-28 11:26:38','2','0.0562291','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('144','17','0','2011-09-28 11:39:26','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('145','17','144','2011-09-28 11:39:26','2','0.039247','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('146','9','0','2011-09-28 11:46:20','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('147','9','146','2011-09-28 11:46:20','2','0.044064','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('148','17','0','2011-09-28 11:51:50','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('149','17','148','2011-09-28 11:51:50','2','0.04846','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('150','13','0','2011-09-28 12:16:15','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('151','13','150','2011-09-28 12:16:15','2','0.0394759','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('152','14','0','2011-09-28 12:21:17','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('153','14','152','2011-09-28 12:21:17','2','0.037744','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('154','9','0','2011-09-29 09:42:51','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('155','9','154','2011-09-29 09:42:51','2','0.134906','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('156','17','0','2011-09-29 09:49:55','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('157','17','156','2011-09-29 09:49:55','2','0.0650141','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('158','13','0','2011-09-29 10:12:44','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('159','13','158','2011-09-29 10:12:44','2','0.0427351','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('160','14','0','2011-09-29 10:15:31','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('161','14','160','2011-09-29 10:15:31','2','0.0363851','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('162','15','0','2011-09-29 10:16:54','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('163','15','162','2011-09-29 10:16:54','2','0.050132','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('164','16','0','2011-09-29 10:17:34','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('165','16','164','2011-09-29 10:17:34','2','0.0516751','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('166','12','0','2011-09-29 10:17:45','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('167','12','166','2011-09-29 10:17:45','2','0.0441711','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('168','18','0','2011-09-29 10:18:39','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('169','18','168','2011-09-29 10:18:39','2','0.074841','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('170','19','0','2011-09-29 10:20:15','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('171','19','170','2011-09-29 10:20:15','2','0.0526068','0','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('172','9','0','2011-09-29 10:27:11','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('173','9','172','2011-09-29 10:27:11','2','0.0750499','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('174','17','0','2011-10-01 09:22:19','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('175','17','174','2011-10-01 09:22:19','2','0.747018','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('176','5','0','2011-10-01 09:29:34','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('177','5','176','2011-10-01 09:29:34','2','0.0478282','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('178','6','0','2011-10-01 09:40:01','0','0','0','Run mode/<span style=\'font-size:12px; color:red;\'>crontask/36</span> : GLPI/<span style=\'font-size:12px; color:red;\'>crontask/34</span>');
INSERT INTO `glpi_crontasklogs` VALUES ('179','9','0','2011-10-01 09:48:21','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('180','9','179','2011-10-01 09:48:21','2','0.100787','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('181','13','0','2011-10-01 09:56:28','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('182','13','181','2011-10-01 09:56:28','1','0.062582','8','Clean 8 graph file(s) created since more than 3600 seconds
');
INSERT INTO `glpi_crontasklogs` VALUES ('183','13','181','2011-10-01 09:56:28','2','0.0701299','8','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('184','14','0','2011-10-01 10:03:15','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('185','14','184','2011-10-01 10:03:15','2','0.0401351','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('186','15','0','2011-10-01 10:08:38','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('187','15','186','2011-10-01 10:08:38','2','0.099406','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('188','16','0','2011-10-01 10:24:38','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('189','16','188','2011-10-01 10:24:38','2','0.0442641','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('190','12','0','2011-10-01 10:37:42','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('191','12','190','2011-10-01 10:37:42','1','0.040765','1','Clean 1 session file(s) created since more than 180 seconds
');
INSERT INTO `glpi_crontasklogs` VALUES ('192','12','190','2011-10-01 10:37:42','2','0.0500782','1','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('193','18','0','2011-10-01 10:54:16','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('194','18','193','2011-10-01 10:54:16','2','0.0933909','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('195','19','0','2011-10-01 10:54:21','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('196','19','195','2011-10-01 10:54:21','2','0.072813','0','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('197','17','0','2011-10-01 10:54:51','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('198','17','197','2011-10-01 10:54:51','2','0.0674229','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('199','9','0','2011-10-01 10:56:09','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('200','9','199','2011-10-01 10:56:09','2','0.0798838','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('201','13','0','2011-10-01 10:58:11','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('202','13','201','2011-10-01 10:58:11','2','0.0629768','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('203','17','0','2011-10-03 05:37:14','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('204','17','203','2011-10-03 05:37:14','2','3.50379','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('205','14','0','2011-10-03 06:46:31','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('206','14','205','2011-10-03 06:46:31','2','0.286395','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('207','9','0','2011-10-03 06:53:15','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('208','9','207','2011-10-03 06:53:15','2','0.113753','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('209','13','0','2011-10-03 07:26:41','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('210','13','209','2011-10-03 07:26:41','2','0.249186','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('211','15','0','2011-10-03 10:00:48','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('212','15','211','2011-10-03 10:00:48','2','0.12106','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('213','16','0','2011-10-03 10:18:39','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('214','16','213','2011-10-03 10:18:39','2','0.0700052','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('215','5','0','2011-10-03 10:25:05','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('216','5','215','2011-10-03 10:25:05','2','0.0642538','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('217','6','0','2011-10-03 10:31:29','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('218','6','217','2011-10-03 10:31:29','2','0.062665','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('219','12','0','2011-10-03 11:42:21','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('220','12','219','2011-10-03 11:42:21','2','0.0658669','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('221','18','0','2011-10-03 11:48:11','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('222','18','221','2011-10-03 11:48:11','2','0.0852442','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('223','19','0','2011-10-03 11:51:00','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('224','19','223','2011-10-03 11:51:00','2','0.0975699','0','Action completed, fully processed');
INSERT INTO `glpi_crontasklogs` VALUES ('225','17','0','2011-10-03 11:51:39','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('226','17','225','2011-10-03 11:51:39','2','0.0706048','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('227','9','0','2011-10-03 11:51:53','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('228','9','227','2011-10-03 11:51:53','2','0.0739079','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('229','14','0','2011-10-03 11:53:52','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('230','14','229','2011-10-03 11:53:52','2','0.075345','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('231','13','0','2011-10-03 13:32:06','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('232','13','231','2011-10-03 13:32:06','2','0.0679371','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('233','17','0','2011-10-03 13:52:00','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('234','17','233','2011-10-03 13:52:00','2','0.225216','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('235','9','0','2011-10-03 13:53:05','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('236','9','235','2011-10-03 13:53:05','2','0.252756','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('237','14','0','2011-10-03 16:11:05','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('238','14','237','2011-10-03 16:11:05','2','0.18143','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('239','17','0','2011-10-03 18:16:08','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('240','17','239','2011-10-03 18:16:08','2','0.192506','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('241','9','0','2011-10-03 18:16:21','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('242','9','241','2011-10-03 18:16:21','2','0.416922','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('243','13','0','2011-10-03 18:16:26','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('244','13','243','2011-10-03 18:16:26','2','0.0350811','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('245','14','0','2011-10-03 18:16:56','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('246','14','245','2011-10-03 18:16:56','2','0.033787','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('247','17','0','2011-10-03 18:22:40','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('248','17','247','2011-10-03 18:22:40','2','0.036016','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('249','9','0','2011-10-03 18:33:13','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('250','9','249','2011-10-03 18:33:13','2','0.139808','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('251','17','0','2011-10-03 18:33:24','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('252','17','251','2011-10-03 18:33:24','2','0.046211','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('253','17','0','2011-10-03 18:39:25','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('254','17','253','2011-10-03 18:39:25','2','0.1222','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('255','9','0','2011-10-03 19:15:34','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('256','9','255','2011-10-03 19:15:34','2','0.122413','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('257','17','0','2011-10-03 19:21:17','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('258','17','257','2011-10-03 19:21:17','2','0.243142','0','Action completed, no processing required');
INSERT INTO `glpi_crontasklogs` VALUES ('259','13','0','2011-10-03 19:27:38','0','0','0','Run mode : GLPI');
INSERT INTO `glpi_crontasklogs` VALUES ('260','13','259','2011-10-03 19:27:38','2','0.040014','0','Action completed, no processing required');

### Dump table glpi_crontasks

DROP TABLE IF EXISTS `glpi_crontasks`;
CREATE TABLE `glpi_crontasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'task name',
  `frequency` int(11) NOT NULL COMMENT 'second between launch',
  `param` int(11) DEFAULT NULL COMMENT 'task specify parameter',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '0:disabled, 1:waiting, 2:running',
  `mode` int(11) NOT NULL DEFAULT '1' COMMENT '1:internal, 2:external',
  `allowmode` int(11) NOT NULL DEFAULT '3' COMMENT '1:internal, 2:external, 3:both',
  `hourmin` int(11) NOT NULL DEFAULT '0',
  `hourmax` int(11) NOT NULL DEFAULT '24',
  `logs_lifetime` int(11) NOT NULL DEFAULT '30' COMMENT 'number of days',
  `lastrun` datetime DEFAULT NULL COMMENT 'last run date',
  `lastcode` int(11) DEFAULT NULL COMMENT 'last run return code',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`itemtype`,`name`),
  KEY `mode` (`mode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Task run by internal / external cron.';

INSERT INTO `glpi_crontasks` VALUES ('1','OcsServer','ocsng','300',NULL,'0','1','3','0','24','30',NULL,NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('2','CartridgeItem','cartridge','86400','10','0','1','3','0','24','30',NULL,NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('3','ConsumableItem','consumable','86400','10','0','1','3','0','24','30',NULL,NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('4','SoftwareLicense','software','86400',NULL,'0','1','3','0','24','30',NULL,NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('5','Contract','contract','86400',NULL,'1','1','3','0','24','30','2011-10-03 16:25:06',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('6','InfoCom','infocom','86400',NULL,'1','1','3','0','24','30','2011-10-03 16:31:29',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('7','CronTask','logs','86400','30','0','1','3','0','24','30',NULL,NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('8','CronTask','optimize','604800',NULL,'1','1','3','0','24','30','2011-09-28 16:18:28',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('9','MailCollector','mailgate','600','10','1','1','3','0','24','30','2011-10-04 01:15:35',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('10','DBconnection','checkdbreplicate','300',NULL,'0','1','3','0','24','30',NULL,NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('11','CronTask','checkupdate','604800',NULL,'0','1','3','0','24','30',NULL,NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('12','CronTask','session','86400',NULL,'1','1','3','0','24','30','2011-10-03 17:42:21',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('13','CronTask','graph','3600',NULL,'1','1','3','0','24','30','2011-10-04 01:27:38',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('14','ReservationItem','reservation','3600',NULL,'1','1','3','0','24','30','2011-10-04 00:16:57',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('15','Ticket','closeticket','43200',NULL,'1','1','3','0','24','30','2011-10-03 16:00:48',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('16','Ticket','alertnotclosed','43200',NULL,'1','1','3','0','24','30','2011-10-03 16:18:40',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('17','SlaLevel_Ticket','slaticket','300',NULL,'1','1','3','0','24','30','2011-10-04 01:21:17',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('18','Ticket','createinquest','86400',NULL,'1','1','3','0','24','30','2011-10-03 17:48:11',NULL,NULL);
INSERT INTO `glpi_crontasks` VALUES ('19','Crontask','watcher','86400',NULL,'1','1','3','0','24','30','2011-10-03 17:51:00',NULL,NULL);

### Dump table glpi_devicecases

DROP TABLE IF EXISTS `glpi_devicecases`;
CREATE TABLE `glpi_devicecases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `devicecasetypes_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `devicecasetypes_id` (`devicecasetypes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_devicecasetypes

DROP TABLE IF EXISTS `glpi_devicecasetypes`;
CREATE TABLE `glpi_devicecasetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicecasetypes` VALUES ('1','Full Tower','');
INSERT INTO `glpi_devicecasetypes` VALUES ('2','Mid Tower','');
INSERT INTO `glpi_devicecasetypes` VALUES ('3','Mini Tower','');
INSERT INTO `glpi_devicecasetypes` VALUES ('4','Rackmount','');

### Dump table glpi_devicecontrols

DROP TABLE IF EXISTS `glpi_devicecontrols`;
CREATE TABLE `glpi_devicecontrols` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_raid` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `interfacetypes_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `interfacetypes_id` (`interfacetypes_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicecontrols` VALUES ('1','Multilane 4X RAID5/JBOD PCI-E 8x Controller','1','','26','7',NULL);

### Dump table glpi_devicedrives

DROP TABLE IF EXISTS `glpi_devicedrives`;
CREATE TABLE `glpi_devicedrives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_writer` tinyint(1) NOT NULL DEFAULT '1',
  `speed` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `interfacetypes_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `interfacetypes_id` (`interfacetypes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_devicegraphiccards

DROP TABLE IF EXISTS `glpi_devicegraphiccards`;
CREATE TABLE `glpi_devicegraphiccards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interfacetypes_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `specif_default` int(11) NOT NULL,
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `interfacetypes_id` (`interfacetypes_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicegraphiccards` VALUES ('1','GeFORCE 3200','0','','11','0',NULL);

### Dump table glpi_deviceharddrives

DROP TABLE IF EXISTS `glpi_deviceharddrives`;
CREATE TABLE `glpi_deviceharddrives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rpm` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interfacetypes_id` int(11) NOT NULL DEFAULT '0',
  `cache` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `specif_default` int(11) NOT NULL,
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `interfacetypes_id` (`interfacetypes_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_deviceharddrives` VALUES ('1','WD Passport 512GB','','0','','','10','512',NULL);

### Dump table glpi_devicememories

DROP TABLE IF EXISTS `glpi_devicememories`;
CREATE TABLE `glpi_devicememories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `frequence` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `specif_default` int(11) NOT NULL,
  `devicememorytypes_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `devicememorytypes_id` (`devicememorytypes_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicememories` VALUES ('1','DDR3','1333','','25','2000','2',NULL);

### Dump table glpi_devicememorytypes

DROP TABLE IF EXISTS `glpi_devicememorytypes`;
CREATE TABLE `glpi_devicememorytypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicememorytypes` VALUES ('1','EDO',NULL);
INSERT INTO `glpi_devicememorytypes` VALUES ('2','DDR',NULL);
INSERT INTO `glpi_devicememorytypes` VALUES ('3','SDRAM',NULL);
INSERT INTO `glpi_devicememorytypes` VALUES ('4','SDRAM-2',NULL);

### Dump table glpi_devicemotherboards

DROP TABLE IF EXISTS `glpi_devicemotherboards`;
CREATE TABLE `glpi_devicemotherboards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chipset` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicemotherboards` VALUES ('1','Asus Crosshair V Formula Motherboard','','','6','T3ST1NG');

### Dump table glpi_devicenetworkcards

DROP TABLE IF EXISTS `glpi_devicenetworkcards`;
CREATE TABLE `glpi_devicenetworkcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidth` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `specif_default` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicenetworkcards` VALUES ('1','802.11N 300Mbps Wireless PCI Lan Card','','','0','',NULL);

### Dump table glpi_devicepcis

DROP TABLE IF EXISTS `glpi_devicepcis`;
CREATE TABLE `glpi_devicepcis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_devicepowersupplies

DROP TABLE IF EXISTS `glpi_devicepowersupplies`;
CREATE TABLE `glpi_devicepowersupplies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `power` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_atx` tinyint(1) NOT NULL DEFAULT '1',
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_deviceprocessors

DROP TABLE IF EXISTS `glpi_deviceprocessors`;
CREATE TABLE `glpi_deviceprocessors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `frequence` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `specif_default` int(11) NOT NULL,
  `deployed` int(11) DEFAULT NULL,
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_deviceprocessors` VALUES ('1','Intel Core2Duo','0','','25','1000',NULL,NULL);
INSERT INTO `glpi_deviceprocessors` VALUES ('2','sample','0','','1','0','5','T3ST-1234-T1NG');

### Dump table glpi_devicesoundcards

DROP TABLE IF EXISTS `glpi_devicesoundcards`;
CREATE TABLE `glpi_devicesoundcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `serialnumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designation` (`designation`),
  KEY `manufacturers_id` (`manufacturers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_devicesoundcards` VALUES ('1','Soundblaster X-Fi Titanium','','','6',NULL);

### Dump table glpi_displaypreferences

DROP TABLE IF EXISTS `glpi_displaypreferences`;
CREATE TABLE `glpi_displaypreferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`users_id`,`itemtype`,`num`),
  KEY `rank` (`rank`),
  KEY `num` (`num`),
  KEY `itemtype` (`itemtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_displaypreferences` VALUES ('32','Computer','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('34','Computer','45','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('33','Computer','40','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('31','Computer','5','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('30','Computer','23','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('86','DocumentType','3','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('49','Monitor','31','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('50','Monitor','23','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('51','Monitor','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('52','Monitor','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('44','Printer','31','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('38','NetworkEquipment','31','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('39','NetworkEquipment','23','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('45','Printer','23','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('46','Printer','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('63','Software','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('62','Software','5','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('61','Software','23','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('83','CartridgeItem','4','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('82','CartridgeItem','34','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('57','Peripheral','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('56','Peripheral','23','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('55','Peripheral','31','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('29','Computer','31','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('35','Computer','3','7','0');
INSERT INTO `glpi_displaypreferences` VALUES ('36','Computer','19','8','0');
INSERT INTO `glpi_displaypreferences` VALUES ('37','Computer','17','9','0');
INSERT INTO `glpi_displaypreferences` VALUES ('40','NetworkEquipment','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('41','NetworkEquipment','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('42','NetworkEquipment','11','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('43','NetworkEquipment','19','7','0');
INSERT INTO `glpi_displaypreferences` VALUES ('47','Printer','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('48','Printer','19','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('53','Monitor','19','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('54','Monitor','7','7','0');
INSERT INTO `glpi_displaypreferences` VALUES ('58','Peripheral','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('59','Peripheral','19','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('60','Peripheral','7','7','0');
INSERT INTO `glpi_displaypreferences` VALUES ('64','Contact','3','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('65','Contact','4','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('66','Contact','5','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('67','Contact','6','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('68','Contact','9','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('69','Supplier','9','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('70','Supplier','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('71','Supplier','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('72','Supplier','5','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('73','Supplier','10','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('74','Supplier','6','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('75','Contract','4','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('76','Contract','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('77','Contract','5','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('78','Contract','6','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('79','Contract','7','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('80','Contract','11','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('84','CartridgeItem','23','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('85','CartridgeItem','3','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('88','DocumentType','6','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('89','DocumentType','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('90','DocumentType','5','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('91','Document','3','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('92','Document','4','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('93','Document','7','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('94','Document','5','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('95','Document','16','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('96','User','34','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('98','User','5','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('99','User','6','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('100','User','3','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('101','ConsumableItem','34','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('102','ConsumableItem','4','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('103','ConsumableItem','23','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('104','ConsumableItem','3','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('105','NetworkEquipment','40','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('106','Printer','40','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('107','Monitor','40','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('108','Peripheral','40','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('109','User','8','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('110','Phone','31','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('111','Phone','23','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('112','Phone','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('113','Phone','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('114','Phone','40','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('115','Phone','19','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('116','Phone','7','7','0');
INSERT INTO `glpi_displaypreferences` VALUES ('117','Group','16','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('118','States','31','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('119','ReservationItem','4','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('120','ReservationItem','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('125','Budget','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('122','Software','72','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('123','Software','163','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('124','Budget','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('126','Budget','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('127','Budget','19','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('128','Crontask','8','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('129','Crontask','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('130','Crontask','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('131','Crontask','7','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('132','RequestType','14','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('133','RequestType','15','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('134','NotificationTemplate','4','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('135','NotificationTemplate','16','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('136','Notification','5','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('137','Notification','6','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('138','Notification','2','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('139','Notification','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('140','Notification','80','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('141','Notification','86','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('142','MailCollector','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('143','MailCollector','19','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('144','AuthLDAP','3','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('145','AuthLDAP','19','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('146','AuthMail','3','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('147','AuthMail','19','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('148','OcsServer','3','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('149','OcsServer','19','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('150','Profile','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('151','Profile','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('152','Profile','19','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('153','Transfer','19','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('154','TicketValidation','3','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('155','TicketValidation','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('156','TicketValidation','8','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('157','TicketValidation','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('158','TicketValidation','9','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('159','TicketValidation','7','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('160','NotImportedEmail','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('161','NotImportedEmail','5','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('162','NotImportedEmail','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('163','NotImportedEmail','6','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('164','NotImportedEmail','16','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('165','NotImportedEmail','19','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('166','RuleRightParameter','11','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('167','Ticket','12','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('168','Ticket','19','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('169','Ticket','15','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('170','Ticket','3','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('171','Ticket','4','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('172','Ticket','5','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('173','Ticket','7','7','0');
INSERT INTO `glpi_displaypreferences` VALUES ('174','Calendar','19','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('175','Holiday','11','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('176','Holiday','12','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('177','Holiday','13','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('178','SLA','4','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('179','Ticket','18','8','0');
INSERT INTO `glpi_displaypreferences` VALUES ('180','AuthLdap','30','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('181','AuthMail','6','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('182','OcsServer','6','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('183','FieldUnicity','1','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('184','FieldUnicity','80','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('185','FieldUnicity','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('186','FieldUnicity','3','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('187','FieldUnicity','86','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('188','FieldUnicity','30','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('191','PluginGenericobjectType','10','6','0');
INSERT INTO `glpi_displaypreferences` VALUES ('192','PluginGenericobjectType','9','5','0');
INSERT INTO `glpi_displaypreferences` VALUES ('193','PluginGenericobjectType','8','4','0');
INSERT INTO `glpi_displaypreferences` VALUES ('194','PluginGenericobjectType','7','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('195','PluginGenericobjectType','6','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('196','PluginGenericobjectType','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('197','PluginGenericobjectType','4','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('198','PluginGenericobjectType','11','7','0');
INSERT INTO `glpi_displaypreferences` VALUES ('199','PluginGenericobjectType','12','8','0');
INSERT INTO `glpi_displaypreferences` VALUES ('200','PluginGenericobjectType','14','10','0');
INSERT INTO `glpi_displaypreferences` VALUES ('201','PluginGenericobjectType','15','11','0');
INSERT INTO `glpi_displaypreferences` VALUES ('208','DeviceProcessor','23','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('205','DeviceMotherboard','99','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('209','DeviceProcessor','99','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('210','DeviceMotherboard','98','2','0');
INSERT INTO `glpi_displaypreferences` VALUES ('211','DeviceProcessor','98','3','0');
INSERT INTO `glpi_displaypreferences` VALUES ('212','DeviceNetworkCard','98','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('213','DeviceMemory','98','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('214','DeviceHardDrive','98','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('215','DeviceControl','98','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('216','DeviceGraphicCard','98','1','0');
INSERT INTO `glpi_displaypreferences` VALUES ('217','DeviceSoundCard','98','1','0');

### Dump table glpi_documentcategories

DROP TABLE IF EXISTS `glpi_documentcategories`;
CREATE TABLE `glpi_documentcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_documents

DROP TABLE IF EXISTS `glpi_documents`;
CREATE TABLE `glpi_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'for display and transfert',
  `filepath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'file storage path',
  `documentcategories_id` int(11) NOT NULL DEFAULT '0',
  `mime` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `sha1sum` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`),
  KEY `name` (`name`),
  KEY `entities_id` (`entities_id`),
  KEY `tickets_id` (`tickets_id`),
  KEY `users_id` (`users_id`),
  KEY `documentcategories_id` (`documentcategories_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `sha1sum` (`sha1sum`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_documents` VALUES ('1','0','0','Document Computer - Sample','glpi error.png','PNG/b6/7e6d39bbb0a64de3ee8dc426a3672fbbc221d3.PNG','0','image/png','2011-09-23 10:12:51',NULL,'0',NULL,NULL,'2','0','b67e6d39bbb0a64de3ee8dc426a3672fbbc221d3');

### Dump table glpi_documents_items

DROP TABLE IF EXISTS `glpi_documents_items`;
CREATE TABLE `glpi_documents_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documents_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`documents_id`,`itemtype`,`items_id`),
  KEY `item` (`itemtype`,`items_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_documenttypes

DROP TABLE IF EXISTS `glpi_documenttypes`;
CREATE TABLE `glpi_documenttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ext` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mime` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_uploadable` tinyint(1) NOT NULL DEFAULT '1',
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`ext`),
  KEY `name` (`name`),
  KEY `is_uploadable` (`is_uploadable`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_documenttypes` VALUES ('1','JPEG','jpg','jpg-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('2','PNG','png','png-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('3','GIF','gif','gif-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('4','BMP','bmp','bmp-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('5','Photoshop','psd','psd-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('6','TIFF','tif','tif-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('7','AIFF','aiff','aiff-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('8','Windows Media','asf','asf-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('9','Windows Media','avi','avi-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('44','C source','c','','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('27','RealAudio','rm','rm-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('16','Midi','mid','mid-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('17','QuickTime','mov','mov-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('18','MP3','mp3','mp3-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('19','MPEG','mpg','mpg-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('20','Ogg Vorbis','ogg','ogg-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('24','QuickTime','qt','qt-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('10','BZip','bz2','bz2-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('25','RealAudio','ra','ra-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('26','RealAudio','ram','ram-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('11','Word','doc','doc-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('12','DjVu','djvu','','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('42','MNG','mng','','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('13','PostScript','eps','ps-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('14','GZ','gz','gz-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('37','WAV','wav','wav-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('15','HTML','html','html-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('34','Flash','swf','','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('21','PDF','pdf','pdf-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('22','PowerPoint','ppt','ppt-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('23','PostScript','ps','ps-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('40','Windows Media','wmv','','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('28','RTF','rtf','rtf-dist.png','','1','2004-12-13 19:47:21',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('29','StarOffice','sdd','sdd-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('30','StarOffice','sdw','sdw-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('31','Stuffit','sit','sit-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('43','Adobe Illustrator','ai','ai-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('32','OpenOffice Impress','sxi','sxi-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('33','OpenOffice','sxw','sxw-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('46','DVI','dvi','dvi-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('35','TGZ','tgz','tgz-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('36','texte','txt','txt-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('49','RedHat/Mandrake/SuSE','rpm','rpm-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('38','Excel','xls','xls-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('39','XML','xml','xml-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('41','Zip','zip','zip-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('45','Debian','deb','deb-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('47','C header','h','','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('48','Pascal','pas','','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('50','OpenOffice Calc','sxc','sxc-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('51','LaTeX','tex','tex-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('52','GIMP multi-layer','xcf','xcf-dist.png','','1','2004-12-13 19:47:22',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('53','JPEG','jpeg','jpg-dist.png','','1','2005-03-07 22:23:17',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('54','Oasis Open Office Writer','odt','odt-dist.png','','1','2006-01-21 17:41:13',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('55','Oasis Open Office Calc','ods','ods-dist.png','','1','2006-01-21 17:41:31',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('56','Oasis Open Office Impress','odp','odp-dist.png','','1','2006-01-21 17:42:54',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('57','Oasis Open Office Impress Template','otp','odp-dist.png','','1','2006-01-21 17:43:58',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('58','Oasis Open Office Writer Template','ott','odt-dist.png','','1','2006-01-21 17:44:41',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('59','Oasis Open Office Calc Template','ots','ods-dist.png','','1','2006-01-21 17:45:30',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('60','Oasis Open Office Math','odf','odf-dist.png','','1','2006-01-21 17:48:05',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('61','Oasis Open Office Draw','odg','odg-dist.png','','1','2006-01-21 17:48:31',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('62','Oasis Open Office Draw Template','otg','odg-dist.png','','1','2006-01-21 17:49:46',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('63','Oasis Open Office Base','odb','odb-dist.png','','1','2006-01-21 18:03:34',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('64','Oasis Open Office HTML','oth','oth-dist.png','','1','2006-01-21 18:05:27',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('65','Oasis Open Office Writer Master','odm','odm-dist.png','','1','2006-01-21 18:06:34',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('66','Oasis Open Office Chart','odc','','','1','2006-01-21 18:07:48',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('67','Oasis Open Office Image','odi','','','1','2006-01-21 18:08:18',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('68','Word XML','docx','doc-dist.png',NULL,'1','2011-01-18 11:40:42',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('69','Excel XML','xlsx','xls-dist.png',NULL,'1','2011-01-18 11:40:42',NULL);
INSERT INTO `glpi_documenttypes` VALUES ('70','PowerPoint XML','pptx','ppt-dist.png',NULL,'1','2011-01-18 11:40:42',NULL);

### Dump table glpi_domains

DROP TABLE IF EXISTS `glpi_domains`;
CREATE TABLE `glpi_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_entities

DROP TABLE IF EXISTS `glpi_entities`;
CREATE TABLE `glpi_entities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `completename` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  `level` int(11) NOT NULL DEFAULT '0',
  `sons_cache` longtext COLLATE utf8_unicode_ci,
  `ancestors_cache` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`entities_id`,`name`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_entitydatas

DROP TABLE IF EXISTS `glpi_entitydatas`;
CREATE TABLE `glpi_entitydatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `address` text COLLATE utf8_unicode_ci,
  `postcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_email_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_reply` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_reply_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  `ldap_dn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authldaps_id` int(11) NOT NULL DEFAULT '0',
  `mail_domain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_ldapfilter` text COLLATE utf8_unicode_ci,
  `mailing_signature` text COLLATE utf8_unicode_ci,
  `cartridges_alert_repeat` int(11) NOT NULL DEFAULT '-1',
  `consumables_alert_repeat` int(11) NOT NULL DEFAULT '-1',
  `use_licenses_alert` tinyint(1) NOT NULL DEFAULT '-1',
  `use_contracts_alert` tinyint(1) NOT NULL DEFAULT '-1',
  `use_infocoms_alert` tinyint(1) NOT NULL DEFAULT '-1',
  `use_reservations_alert` int(11) NOT NULL DEFAULT '-1',
  `autoclose_delay` int(11) NOT NULL DEFAULT '-1',
  `notclosed_delay` int(11) NOT NULL DEFAULT '-1',
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `auto_assign_mode` int(11) NOT NULL DEFAULT '-1',
  `tickettype` int(11) NOT NULL DEFAULT '0',
  `max_closedate` datetime DEFAULT NULL,
  `inquest_config` int(11) NOT NULL DEFAULT '0',
  `inquest_rate` int(11) NOT NULL DEFAULT '-1',
  `inquest_delay` int(11) NOT NULL DEFAULT '-1',
  `inquest_URL` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `autofill_warranty_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT '-1',
  `autofill_use_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT '-1',
  `autofill_buy_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT '-1',
  `autofill_delivery_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT '-1',
  `autofill_order_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT '-1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_events

DROP TABLE IF EXISTS `glpi_events`;
CREATE TABLE `glpi_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `level` (`level`),
  KEY `item` (`type`,`items_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_events` VALUES ('1','-1','system','2011-01-18 11:40:45','login','3','glpi connexion de l\'IP: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('2','-1','system','2011-03-04 11:35:25','login','3','glpi connexion de l\'IP: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('3','-1','system','2011-06-28 11:34:39','login','3','glpi connexion de l\'IP: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('4','-1','system','2011-09-18 11:31:21','login','3','glpi IP connection: 192.168.1.148');
INSERT INTO `glpi_events` VALUES ('5','-1','system','2011-09-18 11:31:34','login','3','glpi IP connection: 192.168.1.149');
INSERT INTO `glpi_events` VALUES ('6','-1','system','2011-09-18 11:31:40','login','3','glpi IP connection: 192.168.1.147');
INSERT INTO `glpi_events` VALUES ('7','1','UserTitle','2011-09-18 11:32:48','setup','4','glpi added Secretary.');
INSERT INTO `glpi_events` VALUES ('8','2','UserTitle','2011-09-18 11:32:53','setup','4','glpi added Dean.');
INSERT INTO `glpi_events` VALUES ('9','3','UserTitle','2011-09-18 11:32:58','setup','4','glpi added Vice Dean.');
INSERT INTO `glpi_events` VALUES ('10','4','UserTitle','2011-09-18 11:33:05','setup','4','glpi added Technician.');
INSERT INTO `glpi_events` VALUES ('11','5','UserTitle','2011-09-18 11:33:10','setup','4','glpi added Director.');
INSERT INTO `glpi_events` VALUES ('12','6','UserTitle','2011-09-18 11:34:16','setup','4','glpi added Counselor.');
INSERT INTO `glpi_events` VALUES ('13','7','UserTitle','2011-09-18 11:34:45','setup','4','glpi added Library & AV Support Staff.');
INSERT INTO `glpi_events` VALUES ('14','1','OperatingSystem','2011-09-18 11:35:10','setup','4','glpi added Windows 95.');
INSERT INTO `glpi_events` VALUES ('15','2','OperatingSystem','2011-09-18 11:35:18','setup','4','glpi added Windows 98.');
INSERT INTO `glpi_events` VALUES ('16','8','UserTitle','2011-09-18 11:35:28','setup','4','glpi added President.');
INSERT INTO `glpi_events` VALUES ('17','3','OperatingSystem','2011-09-18 11:35:32','setup','4','glpi added Windows Millennium Edition.');
INSERT INTO `glpi_events` VALUES ('18','9','UserTitle','2011-09-18 11:35:38','setup','4','glpi added Chancellor.');
INSERT INTO `glpi_events` VALUES ('19','10','UserTitle','2011-09-18 11:35:46','setup','4','glpi added Compliance Officer.');
INSERT INTO `glpi_events` VALUES ('20','4','OperatingSystem','2011-09-18 11:35:54','setup','4','glpi added Windows 2000.');
INSERT INTO `glpi_events` VALUES ('21','11','UserTitle','2011-09-18 11:35:59','setup','4','glpi added Information Technology Center Director.');
INSERT INTO `glpi_events` VALUES ('22','5','OperatingSystem','2011-09-18 11:36:02','setup','4','glpi added Windows XP.');
INSERT INTO `glpi_events` VALUES ('23','12','UserTitle','2011-09-18 11:36:08','setup','4','glpi added Executive Secretary.');
INSERT INTO `glpi_events` VALUES ('24','6','OperatingSystem','2011-09-18 11:36:10','setup','4','glpi added Windows Server 2003.');
INSERT INTO `glpi_events` VALUES ('25','13','UserTitle','2011-09-18 11:36:15','setup','4','glpi added Controller.');
INSERT INTO `glpi_events` VALUES ('26','7','OperatingSystem','2011-09-18 11:36:34','setup','4','glpi added Windows Fundamentals for Legacy PCs.');
INSERT INTO `glpi_events` VALUES ('27','8','OperatingSystem','2011-09-18 11:36:42','setup','4','glpi added Windows Vista.');
INSERT INTO `glpi_events` VALUES ('28','14','UserTitle','2011-09-18 11:36:44','setup','4','glpi added Marketing and Communications Officer.');
INSERT INTO `glpi_events` VALUES ('29','9','OperatingSystem','2011-09-18 11:36:50','setup','4','glpi added Windows Home Server.');
INSERT INTO `glpi_events` VALUES ('30','10','OperatingSystem','2011-09-18 11:37:00','setup','4','glpi added Windows Server 2008.');
INSERT INTO `glpi_events` VALUES ('31','15','UserTitle','2011-09-18 11:37:04','setup','4','glpi added  Development, External Relations and Special Projects Officer.');
INSERT INTO `glpi_events` VALUES ('32','11','OperatingSystem','2011-09-18 11:37:07','setup','4','glpi added Windows 7.');
INSERT INTO `glpi_events` VALUES ('33','16','UserTitle','2011-09-18 11:37:18','setup','4','glpi added Associate Vice-Chancellor  for Academics Services.');
INSERT INTO `glpi_events` VALUES ('34','17','UserTitle','2011-09-18 11:37:27','setup','4','glpi added Principal.');
INSERT INTO `glpi_events` VALUES ('35','18','UserTitle','2011-09-18 11:37:56','setup','4','glpi added Associate Vice-Chancellor  for Lasallian Mission.');
INSERT INTO `glpi_events` VALUES ('36','-1','system','2011-09-18 11:37:56','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('37','1','UserCategory','2011-09-18 11:37:59','setup','4','glpi added Controller.');
INSERT INTO `glpi_events` VALUES ('38','19','UserTitle','2011-09-18 11:38:06','setup','4','glpi added  Associate Vice-Chancellor  for Administration.');
INSERT INTO `glpi_events` VALUES ('39','2','UserCategory','2011-09-18 11:38:09','setup','4','glpi added Academic Services.');
INSERT INTO `glpi_events` VALUES ('40','20','UserTitle','2011-09-18 11:38:18','setup','4','glpi added Administrative Assistant.');
INSERT INTO `glpi_events` VALUES ('41','21','UserTitle','2011-09-18 11:38:32','setup','4','glpi added Associate Principal  for Student Activities.');
INSERT INTO `glpi_events` VALUES ('42','22','UserTitle','2011-09-18 11:38:38','setup','4','glpi added  Associate Principal  for Academic.');
INSERT INTO `glpi_events` VALUES ('43','12','OperatingSystem','2011-09-18 11:39:25','setup','4','glpi added Windows.');
INSERT INTO `glpi_events` VALUES ('44','13','OperatingSystem','2011-09-18 11:39:30','setup','4','glpi added Linux.');
INSERT INTO `glpi_events` VALUES ('45','23','UserTitle','2011-09-18 11:39:33','setup','4','glpi added Library.');
INSERT INTO `glpi_events` VALUES ('46','14','OperatingSystem','2011-09-18 11:39:37','setup','4','glpi added MAC.');
INSERT INTO `glpi_events` VALUES ('47','24','UserTitle','2011-09-18 11:39:38','setup','4','glpi added ITEO.');
INSERT INTO `glpi_events` VALUES ('48','25','UserTitle','2011-09-18 11:39:49','setup','4','glpi added STUFAP.');
INSERT INTO `glpi_events` VALUES ('49','26','UserTitle','2011-09-18 11:40:04','setup','4','glpi added LPO.');
INSERT INTO `glpi_events` VALUES ('50','27','UserTitle','2011-09-18 11:40:20','setup','4','glpi added Purchasing.');
INSERT INTO `glpi_events` VALUES ('51','28','UserTitle','2011-09-18 11:40:46','setup','4','glpi added Faculty.');
INSERT INTO `glpi_events` VALUES ('52','1','OperatingSystemVersion','2011-09-18 11:41:34','setup','4','glpi added Windows 2000.');
INSERT INTO `glpi_events` VALUES ('53','29','UserTitle','2011-09-18 11:41:48','setup','4','glpi added Office of the Sports Development.');
INSERT INTO `glpi_events` VALUES ('54','3','UserCategory','2011-09-18 11:41:57','setup','4','glpi added Integrated School Department.');
INSERT INTO `glpi_events` VALUES ('55','4','UserCategory','2011-09-18 11:42:08','setup','4','glpi added Lasallian Mission Office.');
INSERT INTO `glpi_events` VALUES ('56','2','OperatingSystemVersion','2011-09-18 11:42:18','setup','4','glpi added Windows 95.');
INSERT INTO `glpi_events` VALUES ('57','3','OperatingSystemVersion','2011-09-18 11:42:25','setup','4','glpi added Windows 98.');
INSERT INTO `glpi_events` VALUES ('58','29','UserTitle','2011-09-18 11:42:26','setup','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('59','1','State','2011-09-18 11:42:36','setup','4','glpi added Stored.');
INSERT INTO `glpi_events` VALUES ('60','4','OperatingSystemVersion','2011-09-18 11:42:38','setup','4','glpi added Windows Millennium Edition.');
INSERT INTO `glpi_events` VALUES ('61','2','State','2011-09-18 11:42:43','setup','4','glpi added Deployed.');
INSERT INTO `glpi_events` VALUES ('62','30','UserTitle','2011-09-18 11:42:44','setup','4','glpi added Registrar.');
INSERT INTO `glpi_events` VALUES ('63','3','State','2011-09-18 11:42:51','setup','4','glpi added Decomissioned.');
INSERT INTO `glpi_events` VALUES ('64','5','OperatingSystemVersion','2011-09-18 11:42:53','setup','4','glpi added Windows 2000.');
INSERT INTO `glpi_events` VALUES ('65','31','UserTitle','2011-09-18 11:42:54','setup','4','glpi added Admissions.');
INSERT INTO `glpi_events` VALUES ('66','5','UserCategory','2011-09-18 11:42:54','setup','4','glpi added Development, Linkages and Special Projects.');
INSERT INTO `glpi_events` VALUES ('67','6','OperatingSystemVersion','2011-09-18 11:43:03','setup','4','glpi added Windows XP.');
INSERT INTO `glpi_events` VALUES ('68','32','UserTitle','2011-09-18 11:43:03','setup','4','glpi added Office of Student  Affairs / Activities.');
INSERT INTO `glpi_events` VALUES ('69','6','UserCategory','2011-09-18 11:43:09','setup','4','glpi added Campus Grounds Development.');
INSERT INTO `glpi_events` VALUES ('70','7','OperatingSystemVersion','2011-09-18 11:43:13','setup','4','glpi added Windows Server 2003.');
INSERT INTO `glpi_events` VALUES ('71','8','OperatingSystemVersion','2011-09-18 11:43:22','setup','4','glpi added Windows Fundamentals for Legacy PCs.');
INSERT INTO `glpi_events` VALUES ('72','7','UserCategory','2011-09-18 11:43:22','setup','4','glpi added Information Technology Center.');
INSERT INTO `glpi_events` VALUES ('73','9','OperatingSystemVersion','2011-09-18 11:43:30','setup','4','glpi added Windows Vista.');
INSERT INTO `glpi_events` VALUES ('74','10','OperatingSystemVersion','2011-09-18 11:43:37','setup','4','glpi added Windows Home Server.');
INSERT INTO `glpi_events` VALUES ('75','11','OperatingSystemVersion','2011-09-18 11:43:46','setup','4','glpi added Windows Server 2008.');
INSERT INTO `glpi_events` VALUES ('76','12','OperatingSystemVersion','2011-09-18 11:44:03','setup','4','glpi added Windows 7.');
INSERT INTO `glpi_events` VALUES ('77','1','ComputerType','2011-09-18 11:44:23','setup','4','glpi added Laptop.');
INSERT INTO `glpi_events` VALUES ('78','13','OperatingSystemVersion','2011-09-18 11:44:28','setup','4','glpi added Windows Server R2.');
INSERT INTO `glpi_events` VALUES ('79','2','ComputerType','2011-09-18 11:44:28','setup','4','glpi added Desktop.');
INSERT INTO `glpi_events` VALUES ('80','3','ComputerType','2011-09-18 11:44:32','setup','4','glpi added Tablet.');
INSERT INTO `glpi_events` VALUES ('81','4','ComputerType','2011-09-18 11:44:39','setup','4','glpi added All-in-One PC.');
INSERT INTO `glpi_events` VALUES ('82','8','UserCategory','2011-09-18 11:44:40','setup','4','glpi added Pre-School.');
INSERT INTO `glpi_events` VALUES ('83','14','OperatingSystemVersion','2011-09-18 11:44:43','setup','4','glpi added Windows Server 2008 R2.');
INSERT INTO `glpi_events` VALUES ('84','9','UserCategory','2011-09-18 11:44:49','setup','4','glpi added Learning Center 1.');
INSERT INTO `glpi_events` VALUES ('85','15','OperatingSystemVersion','2011-09-18 11:44:54','setup','4','glpi added Windows Home Server 2011.');
INSERT INTO `glpi_events` VALUES ('86','10','UserCategory','2011-09-18 11:44:57','setup','4','glpi added Learning Center 2.');
INSERT INTO `glpi_events` VALUES ('87','1','NetworkEquipmentType','2011-09-18 11:45:05','setup','4','glpi added Modem.');
INSERT INTO `glpi_events` VALUES ('88','2','NetworkEquipmentType','2011-09-18 11:45:09','setup','4','glpi added Router.');
INSERT INTO `glpi_events` VALUES ('89','11','UserCategory','2011-09-18 11:45:15','setup','4','glpi added School of Engineering.');
INSERT INTO `glpi_events` VALUES ('90','1','PrinterType','2011-09-18 11:45:32','setup','4','glpi added Laser.');
INSERT INTO `glpi_events` VALUES ('91','2','PrinterType','2011-09-18 11:45:39','setup','4','glpi added Ink Jet.');
INSERT INTO `glpi_events` VALUES ('92','12','UserCategory','2011-09-18 11:45:45','setup','4','glpi added School of Information and Computing.');
INSERT INTO `glpi_events` VALUES ('93','3','PrinterType','2011-09-18 11:45:53','setup','4','glpi added Dot Matrix.');
INSERT INTO `glpi_events` VALUES ('94','13','UserCategory','2011-09-18 11:46:01','setup','4','glpi added School of Information and Communication Studies.');
INSERT INTO `glpi_events` VALUES ('95','1','MonitorType','2011-09-18 11:46:07','setup','4','glpi added LCD.');
INSERT INTO `glpi_events` VALUES ('96','2','MonitorType','2011-09-18 11:46:10','setup','4','glpi added LED.');
INSERT INTO `glpi_events` VALUES ('97','14','UserCategory','2011-09-18 11:46:19','setup','4','glpi added School of Management and Entrepreneurship.');
INSERT INTO `glpi_events` VALUES ('98','15','UserCategory','2011-09-18 11:46:30','setup','4','glpi added College of Arts and Sciences.');
INSERT INTO `glpi_events` VALUES ('99','16','OperatingSystemVersion','2011-09-18 11:46:42','setup','4','glpi added Mac OS X v10.0 (Cheetah).');
INSERT INTO `glpi_events` VALUES ('100','16','UserCategory','2011-09-18 11:46:42','setup','4','glpi added Office of Student Activities.');
INSERT INTO `glpi_events` VALUES ('101','17','OperatingSystemVersion','2011-09-18 11:46:58','setup','4','glpi added Mac OS X v10.1 (Puma).');
INSERT INTO `glpi_events` VALUES ('102','17','UserCategory','2011-09-18 11:47:08','setup','4','glpi added Admissions Office.');
INSERT INTO `glpi_events` VALUES ('103','18','OperatingSystemVersion','2011-09-18 11:47:19','setup','4','glpi added Mac OS X v10.2 (Jaguar).');
INSERT INTO `glpi_events` VALUES ('104','18','UserCategory','2011-09-18 11:47:28','setup','4','glpi added Libraries.');
INSERT INTO `glpi_events` VALUES ('105','19','OperatingSystemVersion','2011-09-18 11:47:33','setup','4','glpi added Mac OS X v10.3 (Panther).');
INSERT INTO `glpi_events` VALUES ('106','1','DeviceCaseType','2011-09-18 11:47:45','setup','4','glpi added Full Tower.');
INSERT INTO `glpi_events` VALUES ('107','17','UserCategory','2011-09-18 11:47:47','setup','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('108','20','OperatingSystemVersion','2011-09-18 11:47:48','setup','4','glpi added Mac OS X v10.4 (Tiger).');
INSERT INTO `glpi_events` VALUES ('109','2','DeviceCaseType','2011-09-18 11:47:59','setup','4','glpi added Mid Tower.');
INSERT INTO `glpi_events` VALUES ('110','19','UserCategory','2011-09-18 11:48:07','setup','4','glpi added Registrar\\\'s Office.');
INSERT INTO `glpi_events` VALUES ('111','3','DeviceCaseType','2011-09-18 11:48:07','setup','4','glpi added Mini Tower.');
INSERT INTO `glpi_events` VALUES ('112','21','OperatingSystemVersion','2011-09-18 11:48:09','setup','4','glpi added Mac OS X v10.5 (Leopard).');
INSERT INTO `glpi_events` VALUES ('113','20','UserCategory','2011-09-18 11:48:19','setup','4','glpi added Student and Financial Assistance.');
INSERT INTO `glpi_events` VALUES ('114','4','DeviceCaseType','2011-09-18 11:48:20','setup','4','glpi added Rackmount.');
INSERT INTO `glpi_events` VALUES ('115','22','OperatingSystemVersion','2011-09-18 11:48:34','setup','4','glpi added Mac OS X v10.6 (Snow Leopard).');
INSERT INTO `glpi_events` VALUES ('116','1','Manufacturer','2011-09-18 11:48:44','setup','4','glpi added Acer.');
INSERT INTO `glpi_events` VALUES ('117','21','UserCategory','2011-09-18 11:48:48','setup','4','glpi added Physical Facilities Office.');
INSERT INTO `glpi_events` VALUES ('118','23','OperatingSystemVersion','2011-09-18 11:48:50','setup','4','glpi added Mac OS X v10.7 (Lion).');
INSERT INTO `glpi_events` VALUES ('119','2','Manufacturer','2011-09-18 11:48:54','setup','4','glpi added eMachines.');
INSERT INTO `glpi_events` VALUES ('120','22','UserCategory','2011-09-18 11:48:57','setup','4','glpi added Logistics.');
INSERT INTO `glpi_events` VALUES ('121','3','Manufacturer','2011-09-18 11:48:58','setup','4','glpi added Gateway.');
INSERT INTO `glpi_events` VALUES ('122','1','ContractType','2011-09-18 11:49:05','setup','4','glpi added Annually.');
INSERT INTO `glpi_events` VALUES ('123','4','Manufacturer','2011-09-18 11:49:06','setup','4','glpi added Packard Bell.');
INSERT INTO `glpi_events` VALUES ('124','23','UserCategory','2011-09-18 11:49:09','setup','4','glpi added Housekeeping.');
INSERT INTO `glpi_events` VALUES ('125','24','OperatingSystemVersion','2011-09-18 11:49:10','setup','4','glpi added Mac OS X Server.');
INSERT INTO `glpi_events` VALUES ('126','2','ContractType','2011-09-18 11:49:12','setup','4','glpi added Semi Annual.');
INSERT INTO `glpi_events` VALUES ('127','5','Manufacturer','2011-09-18 11:49:15','setup','4','glpi added Apple Inc..');
INSERT INTO `glpi_events` VALUES ('128','6','Manufacturer','2011-09-18 11:49:19','setup','4','glpi added Asus.');
INSERT INTO `glpi_events` VALUES ('129','24','UserCategory','2011-09-18 11:49:20','setup','4','glpi added Safety and Security.');
INSERT INTO `glpi_events` VALUES ('130','3','ContractType','2011-09-18 11:49:21','setup','4','glpi added Quarterly.');
INSERT INTO `glpi_events` VALUES ('131','25','UserCategory','2011-09-18 11:49:26','setup','4','glpi added Purchasing.');
INSERT INTO `glpi_events` VALUES ('132','7','Manufacturer','2011-09-18 11:49:28','setup','4','glpi added Benq.');
INSERT INTO `glpi_events` VALUES ('133','1','ContractType','2011-09-18 11:49:30','setup','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('134','26','UserCategory','2011-09-18 11:49:33','setup','4','glpi added Warehouse.');
INSERT INTO `glpi_events` VALUES ('135','8','Manufacturer','2011-09-18 11:49:36','setup','4','glpi added Dell.');
INSERT INTO `glpi_events` VALUES ('136','27','UserCategory','2011-09-18 11:49:38','setup','4','glpi added Clinic.');
INSERT INTO `glpi_events` VALUES ('137','9','Manufacturer','2011-09-18 11:49:52','setup','4','glpi added Fujitsu.');
INSERT INTO `glpi_events` VALUES ('138','28','UserCategory','2011-09-18 11:49:54','setup','4','glpi added Disbursement.');
INSERT INTO `glpi_events` VALUES ('139','10','Manufacturer','2011-09-18 11:50:01','setup','4','glpi added Gigabyte.');
INSERT INTO `glpi_events` VALUES ('140','29','UserCategory','2011-09-18 11:50:01','setup','4','glpi added Bookkeeping.');
INSERT INTO `glpi_events` VALUES ('141','30','UserCategory','2011-09-18 11:50:07','setup','4','glpi added Cashier.');
INSERT INTO `glpi_events` VALUES ('142','11','Manufacturer','2011-09-18 11:50:10','setup','4','glpi added Hewlett-Packard.');
INSERT INTO `glpi_events` VALUES ('143','12','Manufacturer','2011-09-18 11:50:15','setup','4','glpi added Compaq.');
INSERT INTO `glpi_events` VALUES ('144','13','Manufacturer','2011-09-18 11:50:24','setup','4','glpi added Hitachi.');
INSERT INTO `glpi_events` VALUES ('145','14','Manufacturer','2011-09-18 11:50:29','setup','4','glpi added IBM.');
INSERT INTO `glpi_events` VALUES ('146','31','UserCategory','2011-09-18 11:50:32','setup','4','glpi added Student Accounts.');
INSERT INTO `glpi_events` VALUES ('147','15','Manufacturer','2011-09-18 11:50:37','setup','4','glpi added Lenovo.');
INSERT INTO `glpi_events` VALUES ('148','16','Manufacturer','2011-09-18 11:50:41','setup','4','glpi added LG.');
INSERT INTO `glpi_events` VALUES ('149','32','UserCategory','2011-09-18 11:50:45','setup','4','glpi added Office of Sports Development.');
INSERT INTO `glpi_events` VALUES ('150','17','Manufacturer','2011-09-18 11:50:53','setup','4','glpi added Micro-Star International.');
INSERT INTO `glpi_events` VALUES ('151','33','UserCategory','2011-09-18 11:50:54','setup','4','glpi added Human Resource Department.');
INSERT INTO `glpi_events` VALUES ('152','18','Manufacturer','2011-09-18 11:51:00','setup','4','glpi added NEC Corp.');
INSERT INTO `glpi_events` VALUES ('153','-1','system','2011-09-18 11:51:32','login','3','glpi IP connection: 192.168.1.148');
INSERT INTO `glpi_events` VALUES ('154','19','Manufacturer','2011-09-18 11:52:26','setup','4','glpi added NEO.');
INSERT INTO `glpi_events` VALUES ('155','20','Manufacturer','2011-09-18 11:52:32','setup','4','glpi added Panasonic.');
INSERT INTO `glpi_events` VALUES ('156','21','Manufacturer','2011-09-18 11:52:43','setup','4','glpi added Samsung Electronics.');
INSERT INTO `glpi_events` VALUES ('157','25','OperatingSystemVersion','2011-09-18 11:52:52','setup','4','glpi added Linux Knoppix.');
INSERT INTO `glpi_events` VALUES ('158','22','Manufacturer','2011-09-18 11:52:52','setup','4','glpi added Sony.');
INSERT INTO `glpi_events` VALUES ('159','26','OperatingSystemVersion','2011-09-18 11:53:06','setup','4','glpi added Linux Ubuntu.');
INSERT INTO `glpi_events` VALUES ('160','23','Manufacturer','2011-09-18 11:53:09','setup','4','glpi added Toshiba.');
INSERT INTO `glpi_events` VALUES ('161','24','Manufacturer','2011-09-18 11:53:19','setup','4','glpi added ViewSonic.');
INSERT INTO `glpi_events` VALUES ('162','27','OperatingSystemVersion','2011-09-18 11:53:20','setup','4','glpi added Linux Gentoo.');
INSERT INTO `glpi_events` VALUES ('163','28','OperatingSystemVersion','2011-09-18 11:53:37','setup','4','glpi added Linux Pacman.');
INSERT INTO `glpi_events` VALUES ('164','29','OperatingSystemVersion','2011-09-18 11:53:51','setup','4','glpi added Linux Fedora.');
INSERT INTO `glpi_events` VALUES ('165','30','OperatingSystemVersion','2011-09-18 11:54:02','setup','4','glpi added Red Hat Enterprise Linux.');
INSERT INTO `glpi_events` VALUES ('166','31','OperatingSystemVersion','2011-09-18 11:54:10','setup','4','glpi added Linux Mandriva.');
INSERT INTO `glpi_events` VALUES ('167','32','OperatingSystemVersion','2011-09-18 11:54:25','setup','4','glpi added Linux Slackware.');
INSERT INTO `glpi_events` VALUES ('168','6','users','2011-09-18 11:56:05','setup','4','glpi add the item 10725334.');
INSERT INTO `glpi_events` VALUES ('169','7','users','2011-09-18 11:58:38','setup','4','glpi add the item 10742158.');
INSERT INTO `glpi_events` VALUES ('170','8','users','2011-09-18 11:59:51','setup','4','glpi add the item 10706062.');
INSERT INTO `glpi_events` VALUES ('171','-1','system','2011-09-18 12:00:10','login','3','glpi IP connection: 192.168.1.148');
INSERT INTO `glpi_events` VALUES ('172','9','users','2011-09-18 12:00:45','setup','4','glpi add the item 10721827.');
INSERT INTO `glpi_events` VALUES ('173','0','users','2011-09-18 12:02:00','setup','5','glpi  update the item  10721827.');
INSERT INTO `glpi_events` VALUES ('174','-1','system','2011-09-18 12:05:17','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('175','10','users','2011-09-18 12:12:29','setup','4','glpi add the item 10000000.');
INSERT INTO `glpi_events` VALUES ('176','11','users','2011-09-18 12:13:16','setup','4','glpi add the item 10721827.');
INSERT INTO `glpi_events` VALUES ('177','1','computers','2011-09-18 12:18:42','inventory','4','glpi add the item 320.');
INSERT INTO `glpi_events` VALUES ('178','2','computers','2011-09-18 12:21:58','inventory','4','glpi add the item Extensa F270.');
INSERT INTO `glpi_events` VALUES ('179','1','computers','2011-09-18 12:22:11','inventory','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('180','3','computers','2011-09-18 12:23:56','inventory','4','glpi add the item VERITON x270.');
INSERT INTO `glpi_events` VALUES ('181','4','computers','2011-09-18 12:26:39','inventory','4','glpi add the item VERITON x270.');
INSERT INTO `glpi_events` VALUES ('182','5','computers','2011-09-18 12:28:21','inventory','4','glpi add the item Extensa E270.');
INSERT INTO `glpi_events` VALUES ('183','6','computers','2011-09-18 12:29:57','inventory','4','glpi add the item 210L.');
INSERT INTO `glpi_events` VALUES ('184','7','computers','2011-09-18 12:30:59','inventory','4','glpi add the item 170L.');
INSERT INTO `glpi_events` VALUES ('185','8','computers','2011-09-18 12:32:06','inventory','4','glpi add the item 210L.');
INSERT INTO `glpi_events` VALUES ('186','-1','system','2011-09-19 03:00:03','login','1','connection failed: glpi (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('187','-1','system','2011-09-19 03:00:07','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('188','8','users','2011-09-19 03:07:52','setup','4','glpi add a user to an entity');
INSERT INTO `glpi_events` VALUES ('189','0','entity','2011-09-19 03:07:55','setup','4','glpi user\'s deletion from entity');
INSERT INTO `glpi_events` VALUES ('190','11','users','2011-09-19 03:08:07','setup','4','glpi add a user to an entity');
INSERT INTO `glpi_events` VALUES ('191','0','entity','2011-09-19 03:08:10','setup','4','glpi user\'s deletion from entity');
INSERT INTO `glpi_events` VALUES ('192','7','users','2011-09-19 03:08:27','setup','4','glpi add a user to an entity');
INSERT INTO `glpi_events` VALUES ('193','0','entity','2011-09-19 03:08:30','setup','4','glpi user\'s deletion from entity');
INSERT INTO `glpi_events` VALUES ('194','-1','system','2011-09-19 03:08:44','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('195','-1','system','2011-09-19 03:09:12','login','1','connection failed: 10000000 (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('196','-1','system','2011-09-19 03:09:17','login','3','10000000 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('197','-1','system','2011-09-19 03:09:32','login','3','10725334 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('198','-1','system','2011-09-19 03:09:51','login','3','10706062 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('199','-1','system','2011-09-19 03:11:38','login','3','10725334 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('200','-1','system','2011-09-19 03:11:51','login','3','10706062 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('201','-1','system','2011-09-19 03:12:56','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('202','-1','system','2011-09-19 03:13:22','login','3','10706062 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('203','4','computers','2011-09-19 03:13:38','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('204','3','computers','2011-09-19 03:13:55','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('205','2','computers','2011-09-19 03:14:00','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('206','5','computers','2011-09-19 03:14:06','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('207','1','computers','2011-09-19 03:14:19','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('208','5','computers','2011-09-19 03:14:26','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('209','2','computers','2011-09-19 03:14:35','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('210','3','computers','2011-09-19 03:14:40','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('211','4','computers','2011-09-19 03:14:44','inventory','4','10706062 update the item');
INSERT INTO `glpi_events` VALUES ('212','-1','system','2011-09-19 03:14:53','login','3','10725334 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('213','-1','system','2011-09-19 03:55:59','login','3','10725334 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('214','-1','system','2011-09-19 03:56:53','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('215','-1','system','2011-09-19 03:57:10','login','1','connection failed: 10721827 (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('216','-1','system','2011-09-19 03:57:14','login','3','10721827 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('217','-1','system','2011-09-19 03:58:27','login','3','10706062 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('218','-1','system','2011-09-19 03:58:52','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('219','1','ticket','2011-09-19 04:00:30','tracking','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('220','-1','system','2011-09-19 04:00:42','login','3','10721827 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('221','-1','system','2011-09-19 04:09:05','login','1','connection failed: paul (192.168.13.11)');
INSERT INTO `glpi_events` VALUES ('222','-1','system','2011-09-19 04:09:11','login','3','glpi IP connection: 192.168.13.11');
INSERT INTO `glpi_events` VALUES ('223','-1','system','2011-09-19 04:10:15','login','1','connection failed:  (192.168.13.11)');
INSERT INTO `glpi_events` VALUES ('224','-1','system','2011-09-19 04:13:36','login','1','connection failed: paul (192.168.13.11)');
INSERT INTO `glpi_events` VALUES ('225','-1','system','2011-09-19 04:15:33','login','3','10721827 IP connection: 192.168.13.11');
INSERT INTO `glpi_events` VALUES ('226','-1','system','2011-09-21 07:15:19','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('227','-1','system','2011-09-22 20:58:28','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('228','-1','system','2011-09-23 09:43:20','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('229','9','computers','2011-09-23 10:08:14','inventory','4','glpi add the item Sample.');
INSERT INTO `glpi_events` VALUES ('230','1','documents','2011-09-23 10:12:51','document','4','glpi add link with an item');
INSERT INTO `glpi_events` VALUES ('231','1','documents','2011-09-23 10:12:51','document','4','glpi add the item glpi error.png.');
INSERT INTO `glpi_events` VALUES ('232','-1','system','2011-09-28 07:31:58','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('233','1','DeviceGraphicCard','2011-09-28 08:25:56','inventory','4','glpi add the item GeFORCE 3200.');
INSERT INTO `glpi_events` VALUES ('234','1','DeviceHardDrive','2011-09-28 08:30:46','inventory','4','glpi add the item WD Passport 512GB.');
INSERT INTO `glpi_events` VALUES ('235','1','DeviceDrive','2011-09-28 09:11:04','inventory','4','glpi add the item Drive sample.');
INSERT INTO `glpi_events` VALUES ('236','1','ContactType','2011-09-28 10:20:50','setup','4','glpi added Warranty.');
INSERT INTO `glpi_events` VALUES ('237','1','suppliers','2011-09-28 10:32:46','financial','4','glpi add the item ACER SUPPLIER.');
INSERT INTO `glpi_events` VALUES ('238','9','computers','2011-09-28 10:34:59','inventory','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('239','1','DeviceMotherboard','2011-09-28 10:55:00','inventory','4','glpi add the item Asus Crosshair V Formula Motherboard.');
INSERT INTO `glpi_events` VALUES ('240','25','Manufacturer','2011-09-28 10:55:50','setup','4','glpi added Intel.');
INSERT INTO `glpi_events` VALUES ('241','1','DeviceProcessor','2011-09-28 10:55:55','inventory','4','glpi add the item Intel Core2Duo.');
INSERT INTO `glpi_events` VALUES ('242','1','DeviceNetworkCard','2011-09-28 10:57:27','inventory','4','glpi add the item 802.11N 300Mbps Wireless PCI Lan Card.');
INSERT INTO `glpi_events` VALUES ('243','1','DeviceMemory','2011-09-28 11:00:06','inventory','4','glpi add the item DDR3.');
INSERT INTO `glpi_events` VALUES ('244','26','Manufacturer','2011-09-28 11:02:32','setup','4','glpi added Silicon.');
INSERT INTO `glpi_events` VALUES ('245','1','DeviceControl','2011-09-28 11:02:39','inventory','4','glpi add the item Multilane 4X RAID5/JBOD PCI-E 8x Controller.');
INSERT INTO `glpi_events` VALUES ('246','1','DeviceSoundCard','2011-09-28 11:09:14','inventory','4','glpi add the item Soundblaster X-Fi Titanium.');
INSERT INTO `glpi_events` VALUES ('247','9','computers','2011-09-28 11:21:49','inventory','4','glpi modification of components');
INSERT INTO `glpi_events` VALUES ('248','1','peripherals','2011-09-28 11:39:50','inventory','4','glpi add the item k.');
INSERT INTO `glpi_events` VALUES ('249','0','users','2011-09-28 11:41:44','setup','5','glpi  update the item  glpi.');
INSERT INTO `glpi_events` VALUES ('250','0','users','2011-09-28 12:16:18','setup','5','glpi  update the item  glpi.');
INSERT INTO `glpi_events` VALUES ('251','0','users','2011-09-28 12:21:16','setup','5','glpi  update the item/<span style=\'font-size:12px; color:red;\'>log/21</span>  glpi.');
INSERT INTO `glpi_events` VALUES ('252','-1','system','2011-09-29 09:43:09','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('253','0','users','2011-09-29 09:43:54','setup','5','glpi  update the item  glpi.');
INSERT INTO `glpi_events` VALUES ('254','0','users','2011-09-29 09:44:31','setup','5','glpi  update the item/<span style=\'font-size:12px; color:red;\'>log/21</span>  glpi.');
INSERT INTO `glpi_events` VALUES ('255','0','users','2011-09-29 09:45:45','setup','5','glpi  update the item  glpi.');
INSERT INTO `glpi_events` VALUES ('256','0','users','2011-09-29 09:46:45','setup','5','glpi  update the item  glpi.');
INSERT INTO `glpi_events` VALUES ('257','0','users','2011-09-29 09:47:42','setup','5','glpi  modificación del elemento  glpi.');
INSERT INTO `glpi_events` VALUES ('258','-1','system','2011-09-29 10:15:38','login','3','normal IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('259','-1','system','2011-09-29 10:17:09','login','3','post-only IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('260','-1','system','2011-09-29 10:17:42','login','1','connection failed: 10742158 (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('261','-1','system','2011-09-29 10:17:57','login','3','10742158 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('262','-1','system','2011-09-29 10:18:47','login','3','post-only IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('263','-1','system','2011-09-29 10:20:22','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('264','-1','system','2011-10-01 09:22:45','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('265','0','users','2011-10-01 09:33:59','setup','5','glpi  update the item  glpi.');
INSERT INTO `glpi_events` VALUES ('266','0','users','2011-10-01 09:40:19','setup','5','glpi  update the item/<span style=\'font-size:12px; color:red;\'>log/21</span>  glpi.');
INSERT INTO `glpi_events` VALUES ('267','9','computers','2011-10-01 10:27:37','inventory','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('268','9','computers','2011-10-01 10:27:48','inventory','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('269','9','computers','2011-10-01 10:28:05','inventory','4','glpi modification of components');
INSERT INTO `glpi_events` VALUES ('270','-1','system','2011-10-01 10:55:00','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('271','-1','system','2011-10-03 05:37:22','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('272','-1','system','2011-10-03 06:46:32','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('273','-1','system','2011-10-03 07:26:43','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('274','2','DeviceProcessor','2011-10-03 10:03:38','inventory','4','glpi add the item sample.');
INSERT INTO `glpi_events` VALUES ('275','1','DeviceMotherboard','2011-10-03 10:31:58','setup','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('276','2','DeviceProcessor','2011-10-03 11:42:56','setup','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('277','-1','system','2011-10-03 11:51:09','login','3','normal IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('278','-1','system','2011-10-03 11:51:45','login','3','post-only IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('279','-1','system','2011-10-03 11:52:08','login','3','normal IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('280','-1','system','2011-10-03 11:53:54','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('281','-1','system','2011-10-03 16:11:08','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('282','-1','system','2011-10-03 18:16:12','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('283','-1','system','2011-10-03 18:16:24','login','1','connection failed: 10000000 (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('284','-1','system','2011-10-03 18:16:28','login','1','connection failed: 10000000 (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('285','-1','system','2011-10-03 18:16:31','login','3','10000000 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('286','-1','system','2011-10-03 18:16:59','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('287','6','rules','2011-10-03 18:18:11','setup','4','glpi add the item');
INSERT INTO `glpi_events` VALUES ('288','6','rules','2011-10-03 18:18:40','setup','4','glpi update the item');
INSERT INTO `glpi_events` VALUES ('289','7','rules','2011-10-03 18:18:54','setup','4','glpi add the item');
INSERT INTO `glpi_events` VALUES ('290','2','ticket','2011-10-03 18:19:28','tracking','4','glpi item\'s deletion');
INSERT INTO `glpi_events` VALUES ('291','-1','system','2011-10-03 18:19:33','login','3','10000000 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('292','-1','system','2011-10-03 18:19:59','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('293','10','users','2011-10-03 18:21:50','setup','4','glpi add a user to an entity');
INSERT INTO `glpi_events` VALUES ('294','0','entity','2011-10-03 18:21:54','setup','4','glpi user\'s deletion from entity');
INSERT INTO `glpi_events` VALUES ('295','3','ticket','2011-10-03 18:22:31','tracking','4','glpi item\'s deletion');
INSERT INTO `glpi_events` VALUES ('296','-1','system','2011-10-03 18:22:43','login','3','10000000 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('297','-1','system','2011-10-03 18:22:54','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('298','-1','system','2011-10-03 18:23:21','login','1','connection failed: 10742158 (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('299','-1','system','2011-10-03 18:23:26','login','1','connection failed: 10721827 (127.0.0.1)');
INSERT INTO `glpi_events` VALUES ('300','-1','system','2011-10-03 18:23:30','login','3','10725334 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('301','-1','system','2011-10-03 18:23:39','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('302','-1','system','2011-10-03 18:33:29','login','3','10742158 IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('303','-1','system','2011-10-03 18:33:54','login','3','glpi IP connection: 127.0.0.1');
INSERT INTO `glpi_events` VALUES ('304','-1','system','2011-10-03 18:41:59','login','3','glpi IP connection: 127.0.0.1');

### Dump table glpi_fieldblacklists

DROP TABLE IF EXISTS `glpi_fieldblacklists`;
CREATE TABLE `glpi_fieldblacklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `field` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `itemtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_fieldunicities

DROP TABLE IF EXISTS `glpi_fieldunicities`;
CREATE TABLE `glpi_fieldunicities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `itemtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `entities_id` int(11) NOT NULL DEFAULT '-1',
  `fields` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `action_refuse` tinyint(1) NOT NULL DEFAULT '0',
  `action_notify` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores field unicity criterias';


### Dump table glpi_filesystems

DROP TABLE IF EXISTS `glpi_filesystems`;
CREATE TABLE `glpi_filesystems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_filesystems` VALUES ('1','ext',NULL);
INSERT INTO `glpi_filesystems` VALUES ('2','ext2',NULL);
INSERT INTO `glpi_filesystems` VALUES ('3','ext3',NULL);
INSERT INTO `glpi_filesystems` VALUES ('4','ext4',NULL);
INSERT INTO `glpi_filesystems` VALUES ('5','FAT',NULL);
INSERT INTO `glpi_filesystems` VALUES ('6','FAT32',NULL);
INSERT INTO `glpi_filesystems` VALUES ('7','VFAT',NULL);
INSERT INTO `glpi_filesystems` VALUES ('8','HFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('9','HPFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('10','HTFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('11','JFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('12','JFS2',NULL);
INSERT INTO `glpi_filesystems` VALUES ('13','NFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('14','NTFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('15','ReiserFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('16','SMBFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('17','UDF',NULL);
INSERT INTO `glpi_filesystems` VALUES ('18','UFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('19','XFS',NULL);
INSERT INTO `glpi_filesystems` VALUES ('20','ZFS',NULL);

### Dump table glpi_groups

DROP TABLE IF EXISTS `glpi_groups`;
CREATE TABLE `glpi_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `ldap_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ldap_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ldap_group_dn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `ldap_field` (`ldap_field`),
  KEY `ldap_group_dn` (`ldap_group_dn`),
  KEY `ldap_value` (`ldap_value`),
  KEY `entities_id` (`entities_id`),
  KEY `users_id` (`users_id`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_groups_tickets

DROP TABLE IF EXISTS `glpi_groups_tickets`;
CREATE TABLE `glpi_groups_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`tickets_id`,`type`,`groups_id`),
  KEY `group` (`groups_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_groups_users

DROP TABLE IF EXISTS `glpi_groups_users`;
CREATE TABLE `glpi_groups_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `is_dynamic` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`users_id`,`groups_id`),
  KEY `groups_id` (`groups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_holidays

DROP TABLE IF EXISTS `glpi_holidays`;
CREATE TABLE `glpi_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_perpetual` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `begin_date` (`begin_date`),
  KEY `end_date` (`end_date`),
  KEY `is_perpetual` (`is_perpetual`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_infocoms

DROP TABLE IF EXISTS `glpi_infocoms`;
CREATE TABLE `glpi_infocoms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `buy_date` date DEFAULT NULL,
  `use_date` date DEFAULT NULL,
  `warranty_duration` int(11) NOT NULL DEFAULT '0',
  `warranty_info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `suppliers_id` int(11) NOT NULL DEFAULT '0',
  `order_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `immo_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `warranty_value` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `sink_time` int(11) NOT NULL DEFAULT '0',
  `sink_type` int(11) NOT NULL DEFAULT '0',
  `sink_coeff` float NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `bill` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `budgets_id` int(11) NOT NULL DEFAULT '0',
  `alert` int(11) NOT NULL DEFAULT '0',
  `order_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `inventory_date` date DEFAULT NULL,
  `warranty_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`itemtype`,`items_id`),
  KEY `buy_date` (`buy_date`),
  KEY `alert` (`alert`),
  KEY `budgets_id` (`budgets_id`),
  KEY `suppliers_id` (`suppliers_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_interfacetypes

DROP TABLE IF EXISTS `glpi_interfacetypes`;
CREATE TABLE `glpi_interfacetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_interfacetypes` VALUES ('1','IDE',NULL);
INSERT INTO `glpi_interfacetypes` VALUES ('2','SATA',NULL);
INSERT INTO `glpi_interfacetypes` VALUES ('3','SCSI',NULL);
INSERT INTO `glpi_interfacetypes` VALUES ('4','USB',NULL);
INSERT INTO `glpi_interfacetypes` VALUES ('5','AGP','');
INSERT INTO `glpi_interfacetypes` VALUES ('6','PCI','');
INSERT INTO `glpi_interfacetypes` VALUES ('7','PCIe','');
INSERT INTO `glpi_interfacetypes` VALUES ('8','PCI-X','');

### Dump table glpi_knowbaseitemcategories

DROP TABLE IF EXISTS `glpi_knowbaseitemcategories`;
CREATE TABLE `glpi_knowbaseitemcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `knowbaseitemcategories_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `completename` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`entities_id`,`knowbaseitemcategories_id`,`name`),
  KEY `name` (`name`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_knowbaseitems

DROP TABLE IF EXISTS `glpi_knowbaseitems`;
CREATE TABLE `glpi_knowbaseitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '1',
  `knowbaseitemcategories_id` int(11) NOT NULL DEFAULT '0',
  `question` text COLLATE utf8_unicode_ci,
  `answer` longtext COLLATE utf8_unicode_ci,
  `is_faq` tinyint(1) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  `view` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `knowbaseitemcategories_id` (`knowbaseitemcategories_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_faq` (`is_faq`),
  KEY `date_mod` (`date_mod`),
  FULLTEXT KEY `fulltext` (`question`,`answer`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_links

DROP TABLE IF EXISTS `glpi_links`;
CREATE TABLE `glpi_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_links_itemtypes

DROP TABLE IF EXISTS `glpi_links_itemtypes`;
CREATE TABLE `glpi_links_itemtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `links_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`itemtype`,`links_id`),
  KEY `links_id` (`links_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_locations

DROP TABLE IF EXISTS `glpi_locations`;
CREATE TABLE `glpi_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `completename` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  `level` int(11) NOT NULL DEFAULT '0',
  `ancestors_cache` longtext COLLATE utf8_unicode_ci,
  `sons_cache` longtext COLLATE utf8_unicode_ci,
  `building` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`entities_id`,`locations_id`,`name`),
  KEY `locations_id` (`locations_id`),
  KEY `name` (`name`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_logs

DROP TABLE IF EXISTS `glpi_logs`;
CREATE TABLE `glpi_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype_link` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `linked_action` int(11) NOT NULL DEFAULT '0' COMMENT 'see define.php HISTORY_* constant',
  `user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `id_search_option` int(11) NOT NULL DEFAULT '0' COMMENT 'see search.constant.php for value',
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`),
  KEY `itemtype_link` (`itemtype_link`),
  KEY `item` (`itemtype`,`items_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_logs` VALUES ('1','User','6','Profile_User','17','glpi','2011-09-18 11:56:05','0','','post-only, Root entity, D');
INSERT INTO `glpi_logs` VALUES ('2','User','6','0','20','glpi','2011-09-18 11:56:05','0','','');
INSERT INTO `glpi_logs` VALUES ('3','User','7','Profile_User','17','glpi','2011-09-18 11:58:38','0','','post-only, Root entity, D');
INSERT INTO `glpi_logs` VALUES ('4','User','7','0','20','glpi','2011-09-18 11:58:38','0','','');
INSERT INTO `glpi_logs` VALUES ('5','User','8','Profile_User','17','glpi','2011-09-18 11:59:51','0','','post-only, Root entity, D');
INSERT INTO `glpi_logs` VALUES ('6','User','8','0','20','glpi','2011-09-18 11:59:51','0','','');
INSERT INTO `glpi_logs` VALUES ('7','User','9','Profile_User','17','glpi','2011-09-18 12:00:45','0','','post-only, Root entity, D');
INSERT INTO `glpi_logs` VALUES ('8','User','9','0','20','glpi','2011-09-18 12:00:45','0','','');
INSERT INTO `glpi_logs` VALUES ('9','User','9','','0','glpi','2011-09-18 12:02:00','82','&nbsp;','Information Technology Center');
INSERT INTO `glpi_logs` VALUES ('10','User','9','','0','glpi','2011-09-18 12:02:00','81','&nbsp;','Technician');
INSERT INTO `glpi_logs` VALUES ('11','User','9','0','13','glpi','2011-09-18 12:02:16','0','','');
INSERT INTO `glpi_logs` VALUES ('12','User','10','Profile_User','17','glpi','2011-09-18 12:12:29','0','','post-only, Root entity, D');
INSERT INTO `glpi_logs` VALUES ('13','User','10','0','20','glpi','2011-09-18 12:12:29','0','','');
INSERT INTO `glpi_logs` VALUES ('14','User','11','Profile_User','17','glpi','2011-09-18 12:13:16','0','','post-only, Root entity, D');
INSERT INTO `glpi_logs` VALUES ('15','User','11','0','20','glpi','2011-09-18 12:13:16','0','','');
INSERT INTO `glpi_logs` VALUES ('16','Computer','1','0','20','glpi','2011-09-18 12:18:42','0','','');
INSERT INTO `glpi_logs` VALUES ('17','Computer','2','0','20','glpi','2011-09-18 12:21:58','0','','');
INSERT INTO `glpi_logs` VALUES ('18','Computer','1','','0','glpi','2011-09-18 12:22:11','31','Deployed','Stored');
INSERT INTO `glpi_logs` VALUES ('19','Computer','3','0','20','glpi','2011-09-18 12:23:56','0','','');
INSERT INTO `glpi_logs` VALUES ('20','Computer','4','0','20','glpi','2011-09-18 12:26:39','0','','');
INSERT INTO `glpi_logs` VALUES ('21','Computer','5','0','20','glpi','2011-09-18 12:28:21','0','','');
INSERT INTO `glpi_logs` VALUES ('22','Computer','6','0','20','glpi','2011-09-18 12:29:57','0','','');
INSERT INTO `glpi_logs` VALUES ('23','Computer','7','0','20','glpi','2011-09-18 12:30:59','0','','');
INSERT INTO `glpi_logs` VALUES ('24','Computer','8','0','20','glpi','2011-09-18 12:32:06','0','','');
INSERT INTO `glpi_logs` VALUES ('25','Profile','1','','0','glpi','2011-09-19 03:01:23','80','','0');
INSERT INTO `glpi_logs` VALUES ('26','Profile','1','','0','glpi','2011-09-19 03:01:23','87','[\"Computer\",\"Software\",\"Phone\"]','[\"Computer\",\"Monitor\",\"Phone\",\"Software\"]');
INSERT INTO `glpi_logs` VALUES ('27','Profile','1','','0','glpi','2011-09-19 03:01:23','99','','0');
INSERT INTO `glpi_logs` VALUES ('28','Profile','1','','0','glpi','2011-09-19 03:01:23','98','','0');
INSERT INTO `glpi_logs` VALUES ('29','Profile','5','0','20','glpi','2011-09-19 03:02:34','0','','');
INSERT INTO `glpi_logs` VALUES ('30','Profile','5','','0','glpi','2011-09-19 03:03:02','2','helpdesk','central');
INSERT INTO `glpi_logs` VALUES ('31','Profile','5','','0','glpi','2011-09-19 03:03:31','20','','w');
INSERT INTO `glpi_logs` VALUES ('32','Profile','5','','0','glpi','2011-09-19 03:03:31','21','','w');
INSERT INTO `glpi_logs` VALUES ('33','Profile','5','','0','glpi','2011-09-19 03:03:31','22','','w');
INSERT INTO `glpi_logs` VALUES ('34','Profile','5','','0','glpi','2011-09-19 03:03:31','23','','w');
INSERT INTO `glpi_logs` VALUES ('35','Profile','5','','0','glpi','2011-09-19 03:03:31','24','','w');
INSERT INTO `glpi_logs` VALUES ('36','Profile','5','','0','glpi','2011-09-19 03:03:31','26','','w');
INSERT INTO `glpi_logs` VALUES ('37','Profile','5','','0','glpi','2011-09-19 03:03:31','27','','w');
INSERT INTO `glpi_logs` VALUES ('38','Profile','5','','0','glpi','2011-09-19 03:03:31','28','','w');
INSERT INTO `glpi_logs` VALUES ('39','Profile','5','','0','glpi','2011-09-19 03:03:31','25','','w');
INSERT INTO `glpi_logs` VALUES ('40','Profile','5','','0','glpi','2011-09-19 03:03:31','32','','w');
INSERT INTO `glpi_logs` VALUES ('41','Profile','5','','0','glpi','2011-09-19 03:03:31','36','','0');
INSERT INTO `glpi_logs` VALUES ('42','Profile','5','','0','glpi','2011-09-19 03:04:23','102','','0');
INSERT INTO `glpi_logs` VALUES ('43','Profile','5','','0','glpi','2011-09-19 03:04:23','66','','0');
INSERT INTO `glpi_logs` VALUES ('44','Profile','5','','0','glpi','2011-09-19 03:04:23','67','','0');
INSERT INTO `glpi_logs` VALUES ('45','Profile','5','','0','glpi','2011-09-19 03:04:23','94','','0');
INSERT INTO `glpi_logs` VALUES ('46','Profile','5','','0','glpi','2011-09-19 03:04:23','95','','0');
INSERT INTO `glpi_logs` VALUES ('47','Profile','5','','0','glpi','2011-09-19 03:04:23','68','','1');
INSERT INTO `glpi_logs` VALUES ('48','Profile','5','','0','glpi','2011-09-19 03:04:23','96','','1');
INSERT INTO `glpi_logs` VALUES ('49','Profile','5','','0','glpi','2011-09-19 03:04:23','97','','0');
INSERT INTO `glpi_logs` VALUES ('50','Profile','5','','0','glpi','2011-09-19 03:04:23','80','','0');
INSERT INTO `glpi_logs` VALUES ('51','Profile','5','','0','glpi','2011-09-19 03:04:23','76','','0');
INSERT INTO `glpi_logs` VALUES ('52','Profile','5','','0','glpi','2011-09-19 03:04:23','65','','0');
INSERT INTO `glpi_logs` VALUES ('53','Profile','5','','0','glpi','2011-09-19 03:04:23','81','','0');
INSERT INTO `glpi_logs` VALUES ('54','Profile','5','','0','glpi','2011-09-19 03:04:23','99','','0');
INSERT INTO `glpi_logs` VALUES ('55','Profile','5','','0','glpi','2011-09-19 03:04:23','98','','0');
INSERT INTO `glpi_logs` VALUES ('56','Profile','5','','0','glpi','2011-09-19 03:04:23','69','','0');
INSERT INTO `glpi_logs` VALUES ('57','Profile','5','','0','glpi','2011-09-19 03:04:23','70','','0');
INSERT INTO `glpi_logs` VALUES ('58','Profile','5','','0','glpi','2011-09-19 03:04:23','71','','0');
INSERT INTO `glpi_logs` VALUES ('59','Profile','5','','0','glpi','2011-09-19 03:04:23','89','','0');
INSERT INTO `glpi_logs` VALUES ('60','Profile','5','','0','glpi','2011-09-19 03:04:23','73','','1');
INSERT INTO `glpi_logs` VALUES ('61','Profile','5','','0','glpi','2011-09-19 03:04:23','88','','0');
INSERT INTO `glpi_logs` VALUES ('62','Profile','5','','0','glpi','2011-09-19 03:04:23','72','','1');
INSERT INTO `glpi_logs` VALUES ('63','Profile','5','','0','glpi','2011-09-19 03:04:23','75','','0');
INSERT INTO `glpi_logs` VALUES ('64','Profile','5','','0','glpi','2011-09-19 03:04:23','74','','0');
INSERT INTO `glpi_logs` VALUES ('65','Profile','5','','0','glpi','2011-09-19 03:04:23','85','','1');
INSERT INTO `glpi_logs` VALUES ('66','Profile','5','','0','glpi','2011-09-19 03:04:23','77','','0');
INSERT INTO `glpi_logs` VALUES ('67','Profile','5','','0','glpi','2011-09-19 03:04:23','78','','0');
INSERT INTO `glpi_logs` VALUES ('68','Profile','5','','0','glpi','2011-09-19 03:04:23','79','','0');
INSERT INTO `glpi_logs` VALUES ('69','Profile','5','','0','glpi','2011-09-19 03:04:23','87','','[]');
INSERT INTO `glpi_logs` VALUES ('70','Profile','5','','0','glpi','2011-09-19 03:04:23','100','','[]');
INSERT INTO `glpi_logs` VALUES ('71','Profile','5','','0','glpi','2011-09-19 03:04:56','47','','w');
INSERT INTO `glpi_logs` VALUES ('72','Profile','5','','0','glpi','2011-09-19 03:04:56','44','','w');
INSERT INTO `glpi_logs` VALUES ('73','Profile','5','','0','glpi','2011-09-19 03:04:56','42','','w');
INSERT INTO `glpi_logs` VALUES ('74','Profile','5','','0','glpi','2011-09-19 03:04:56','54','','r');
INSERT INTO `glpi_logs` VALUES ('75','Profile','5','','0','glpi','2011-09-19 03:04:56','106','','w');
INSERT INTO `glpi_logs` VALUES ('76','Profile','6','0','20','glpi','2011-09-19 03:05:19','0','','');
INSERT INTO `glpi_logs` VALUES ('77','Profile','6','','0','glpi','2011-09-19 03:05:53','20','','r');
INSERT INTO `glpi_logs` VALUES ('78','Profile','6','','0','glpi','2011-09-19 03:05:53','21','','r');
INSERT INTO `glpi_logs` VALUES ('79','Profile','6','','0','glpi','2011-09-19 03:05:53','22','','w');
INSERT INTO `glpi_logs` VALUES ('80','Profile','6','','0','glpi','2011-09-19 03:05:53','23','','r');
INSERT INTO `glpi_logs` VALUES ('81','Profile','6','','0','glpi','2011-09-19 03:05:53','24','','r');
INSERT INTO `glpi_logs` VALUES ('82','Profile','6','','0','glpi','2011-09-19 03:05:53','26','','r');
INSERT INTO `glpi_logs` VALUES ('83','Profile','6','','0','glpi','2011-09-19 03:05:53','27','','r');
INSERT INTO `glpi_logs` VALUES ('84','Profile','6','','0','glpi','2011-09-19 03:05:53','28','','r');
INSERT INTO `glpi_logs` VALUES ('85','Profile','6','','0','glpi','2011-09-19 03:05:53','25','','r');
INSERT INTO `glpi_logs` VALUES ('86','Profile','6','','0','glpi','2011-09-19 03:05:53','30','','w');
INSERT INTO `glpi_logs` VALUES ('87','Profile','6','','0','glpi','2011-09-19 03:05:53','32','','w');
INSERT INTO `glpi_logs` VALUES ('88','Profile','6','','0','glpi','2011-09-19 03:05:53','38','','r');
INSERT INTO `glpi_logs` VALUES ('89','Profile','6','','0','glpi','2011-09-19 03:05:53','36','','0');
INSERT INTO `glpi_logs` VALUES ('90','Profile','6','','0','glpi','2011-09-19 03:07:16','102','','0');
INSERT INTO `glpi_logs` VALUES ('91','Profile','6','','0','glpi','2011-09-19 03:07:16','66','','0');
INSERT INTO `glpi_logs` VALUES ('92','Profile','6','','0','glpi','2011-09-19 03:07:16','67','','0');
INSERT INTO `glpi_logs` VALUES ('93','Profile','6','','0','glpi','2011-09-19 03:07:16','94','','0');
INSERT INTO `glpi_logs` VALUES ('94','Profile','6','','0','glpi','2011-09-19 03:07:16','95','','0');
INSERT INTO `glpi_logs` VALUES ('95','Profile','6','','0','glpi','2011-09-19 03:07:16','68','','0');
INSERT INTO `glpi_logs` VALUES ('96','Profile','6','','0','glpi','2011-09-19 03:07:16','96','','0');
INSERT INTO `glpi_logs` VALUES ('97','Profile','6','','0','glpi','2011-09-19 03:07:16','97','','0');
INSERT INTO `glpi_logs` VALUES ('98','Profile','6','','0','glpi','2011-09-19 03:07:16','80','','0');
INSERT INTO `glpi_logs` VALUES ('99','Profile','6','','0','glpi','2011-09-19 03:07:16','76','','0');
INSERT INTO `glpi_logs` VALUES ('100','Profile','6','','0','glpi','2011-09-19 03:07:16','65','','0');
INSERT INTO `glpi_logs` VALUES ('101','Profile','6','','0','glpi','2011-09-19 03:07:16','81','','0');
INSERT INTO `glpi_logs` VALUES ('102','Profile','6','','0','glpi','2011-09-19 03:07:16','99','','1');
INSERT INTO `glpi_logs` VALUES ('103','Profile','6','','0','glpi','2011-09-19 03:07:16','98','','0');
INSERT INTO `glpi_logs` VALUES ('104','Profile','6','','0','glpi','2011-09-19 03:07:16','69','','0');
INSERT INTO `glpi_logs` VALUES ('105','Profile','6','','0','glpi','2011-09-19 03:07:16','70','','0');
INSERT INTO `glpi_logs` VALUES ('106','Profile','6','','0','glpi','2011-09-19 03:07:16','71','','1');
INSERT INTO `glpi_logs` VALUES ('107','Profile','6','','0','glpi','2011-09-19 03:07:16','89','','1');
INSERT INTO `glpi_logs` VALUES ('108','Profile','6','','0','glpi','2011-09-19 03:07:16','73','','1');
INSERT INTO `glpi_logs` VALUES ('109','Profile','6','','0','glpi','2011-09-19 03:07:16','88','','1');
INSERT INTO `glpi_logs` VALUES ('110','Profile','6','','0','glpi','2011-09-19 03:07:16','72','','1');
INSERT INTO `glpi_logs` VALUES ('111','Profile','6','','0','glpi','2011-09-19 03:07:16','75','','0');
INSERT INTO `glpi_logs` VALUES ('112','Profile','6','','0','glpi','2011-09-19 03:07:16','74','','0');
INSERT INTO `glpi_logs` VALUES ('113','Profile','6','','0','glpi','2011-09-19 03:07:16','85','','1');
INSERT INTO `glpi_logs` VALUES ('114','Profile','6','','0','glpi','2011-09-19 03:07:16','77','','0');
INSERT INTO `glpi_logs` VALUES ('115','Profile','6','','0','glpi','2011-09-19 03:07:16','78','','0');
INSERT INTO `glpi_logs` VALUES ('116','Profile','6','','0','glpi','2011-09-19 03:07:16','79','','0');
INSERT INTO `glpi_logs` VALUES ('117','Profile','6','','0','glpi','2011-09-19 03:07:16','56','','w');
INSERT INTO `glpi_logs` VALUES ('118','Profile','6','','0','glpi','2011-09-19 03:07:16','60','','w');
INSERT INTO `glpi_logs` VALUES ('119','Profile','6','','0','glpi','2011-09-19 03:07:16','55','','w');
INSERT INTO `glpi_logs` VALUES ('120','Profile','6','','0','glpi','2011-09-19 03:07:16','47','','w');
INSERT INTO `glpi_logs` VALUES ('121','Profile','6','','0','glpi','2011-09-19 03:07:16','44','','w');
INSERT INTO `glpi_logs` VALUES ('122','Profile','6','','0','glpi','2011-09-19 03:07:16','42','','w');
INSERT INTO `glpi_logs` VALUES ('123','Profile','6','','0','glpi','2011-09-19 03:07:16','54','','r');
INSERT INTO `glpi_logs` VALUES ('124','Profile','6','','0','glpi','2011-09-19 03:07:16','87','','[]');
INSERT INTO `glpi_logs` VALUES ('125','Profile','6','','0','glpi','2011-09-19 03:07:16','100','','[]');
INSERT INTO `glpi_logs` VALUES ('126','Profile','5','','0','glpi','2011-09-19 03:07:29','69','0','1');
INSERT INTO `glpi_logs` VALUES ('127','User','8','Profile_User','17','glpi','2011-09-19 03:07:52','0','','ITC Director, Root entity');
INSERT INTO `glpi_logs` VALUES ('128','User','8','Profile_User','19','glpi','2011-09-19 03:07:55','0','post-only, Root entity, D','');
INSERT INTO `glpi_logs` VALUES ('129','User','11','Profile_User','17','glpi','2011-09-19 03:08:07','0','','Technician, Root entity');
INSERT INTO `glpi_logs` VALUES ('130','User','11','Profile_User','19','glpi','2011-09-19 03:08:10','0','post-only, Root entity, D','');
INSERT INTO `glpi_logs` VALUES ('131','User','7','Profile_User','17','glpi','2011-09-19 03:08:27','0','','Technician, Root entity');
INSERT INTO `glpi_logs` VALUES ('132','User','7','Profile_User','19','glpi','2011-09-19 03:08:30','0','post-only, Root entity, D','');
INSERT INTO `glpi_logs` VALUES ('133','Profile','6','','0','glpi','2011-09-19 03:13:13','20','r','w');
INSERT INTO `glpi_logs` VALUES ('134','Profile','6','','0','glpi','2011-09-19 03:13:13','21','r','w');
INSERT INTO `glpi_logs` VALUES ('135','Profile','6','','0','glpi','2011-09-19 03:13:13','23','r','w');
INSERT INTO `glpi_logs` VALUES ('136','Profile','6','','0','glpi','2011-09-19 03:13:13','24','r','w');
INSERT INTO `glpi_logs` VALUES ('137','Profile','6','','0','glpi','2011-09-19 03:13:13','26','r','w');
INSERT INTO `glpi_logs` VALUES ('138','Profile','6','','0','glpi','2011-09-19 03:13:13','27','r','w');
INSERT INTO `glpi_logs` VALUES ('139','Profile','6','','0','glpi','2011-09-19 03:13:13','28','r','w');
INSERT INTO `glpi_logs` VALUES ('140','Profile','6','','0','glpi','2011-09-19 03:13:13','25','r','w');
INSERT INTO `glpi_logs` VALUES ('141','Computer','4','','0','Avancena Bernardo','2011-09-19 03:13:38','70','&nbsp;','10706062');
INSERT INTO `glpi_logs` VALUES ('142','Computer','3','','0','Avancena Bernardo','2011-09-19 03:13:55','70','&nbsp;','10725334');
INSERT INTO `glpi_logs` VALUES ('143','Computer','2','','0','Avancena Bernardo','2011-09-19 03:14:00','70','&nbsp;','10721827');
INSERT INTO `glpi_logs` VALUES ('144','Computer','5','','0','Avancena Bernardo','2011-09-19 03:14:06','70','&nbsp;','10000000');
INSERT INTO `glpi_logs` VALUES ('145','Computer','1','','0','Avancena Bernardo','2011-09-19 03:14:19','31','Stored','Deployed');
INSERT INTO `glpi_logs` VALUES ('146','Computer','1','','0','Avancena Bernardo','2011-09-19 03:14:19','70','&nbsp;','10742158');
INSERT INTO `glpi_logs` VALUES ('147','Computer','5','','0','Avancena Bernardo','2011-09-19 03:14:26','31','Stored','Deployed');
INSERT INTO `glpi_logs` VALUES ('148','Computer','2','','0','Avancena Bernardo','2011-09-19 03:14:35','31','Stored','Deployed');
INSERT INTO `glpi_logs` VALUES ('149','Computer','3','','0','Avancena Bernardo','2011-09-19 03:14:40','31','Stored','Deployed');
INSERT INTO `glpi_logs` VALUES ('150','Computer','4','','0','Avancena Bernardo','2011-09-19 03:14:44','31','Stored','Deployed');
INSERT INTO `glpi_logs` VALUES ('151','Ticket','1','User','15','Ching Tristan','2011-09-19 03:56:27','0','','Ching Tristan');
INSERT INTO `glpi_logs` VALUES ('152','Ticket','1','0','20','Ching Tristan','2011-09-19 03:56:27','0','','');
INSERT INTO `glpi_logs` VALUES ('153','Ticket','1','','0','glpi','2011-09-19 04:00:30','64','10725334','glpi');
INSERT INTO `glpi_logs` VALUES ('154','Ticket','1','User','15','glpi','2011-09-19 04:00:30','0','','Geronimo Paul');
INSERT INTO `glpi_logs` VALUES ('155','Ticket','1','','0','glpi','2011-09-19 04:00:30','12','New','Processing (assigned)');
INSERT INTO `glpi_logs` VALUES ('156','Computer','9','0','20','glpi','2011-09-23 10:08:14','0','','');
INSERT INTO `glpi_logs` VALUES ('161','Document','1','0','20','glpi','2011-09-23 10:12:51','0','','');
INSERT INTO `glpi_logs` VALUES ('162','Computer','9','','0','glpi','2011-09-23 10:13:46','70','10742158','&nbsp;');
INSERT INTO `glpi_logs` VALUES ('163','Computer','9','','0','glpi','2011-09-23 10:13:46','1','Sample','');
INSERT INTO `glpi_logs` VALUES ('164','Computer','9','','0','glpi','2011-09-23 10:13:46','45','Windows','&nbsp;');
INSERT INTO `glpi_logs` VALUES ('165','Computer','9','','0','glpi','2011-09-23 10:13:46','46','Windows 7','&nbsp;');
INSERT INTO `glpi_logs` VALUES ('167','Computer','9','0','12','glpi','2011-09-23 10:13:46','0','','Item is now uninstalled');
INSERT INTO `glpi_logs` VALUES ('168','Supplier','1','0','20','glpi','2011-09-28 10:32:46','0','','');
INSERT INTO `glpi_logs` VALUES ('169','Computer','9','','0','glpi','2011-09-28 10:34:59','1','','Test Product');
INSERT INTO `glpi_logs` VALUES ('170','Computer','7','DeviceHardDrive','1','glpi','2011-09-28 10:47:38','0','','WD Passport 512GB');
INSERT INTO `glpi_logs` VALUES ('171','Computer','9','DeviceControl','1','glpi','2011-09-28 11:10:46','0','','Multilane 4X RAID5/JBOD PCI-E 8x Controller');
INSERT INTO `glpi_logs` VALUES ('172','Computer','9','DeviceGraphicCard','1','glpi','2011-09-28 11:10:52','0','','GeFORCE 3200');
INSERT INTO `glpi_logs` VALUES ('173','Computer','9','DeviceNetworkCard','1','glpi','2011-09-28 11:11:30','0','','802.11N 300Mbps Wireless PCI Lan Card');
INSERT INTO `glpi_logs` VALUES ('174','Computer','9','DeviceGraphicCard','3','glpi','2011-09-28 11:21:49','0','GeFORCE 3200','');
INSERT INTO `glpi_logs` VALUES ('175','Peripheral','1','0','20','glpi','2011-09-28 11:39:50','0','','');
INSERT INTO `glpi_logs` VALUES ('176','Peripheral','1','0','13','glpi','2011-09-28 11:40:36','0','','');
INSERT INTO `glpi_logs` VALUES ('177','User','2','','0','glpi','2011-09-29 09:46:45','17','','es_ES');
INSERT INTO `glpi_logs` VALUES ('178','User','2','','0','glpi','2011-09-29 09:47:42','17','es_ES','NULL');
INSERT INTO `glpi_logs` VALUES ('180','Computer','9','DeviceHardDrive','1','glpi','2011-10-01 10:26:56','0','','WD Passport 512GB');
INSERT INTO `glpi_logs` VALUES ('181','Computer','9','DeviceProcessor','1','glpi','2011-10-01 10:27:11','0','','Intel Core2Duo');
INSERT INTO `glpi_logs` VALUES ('182','Computer','9','DeviceSoundCard','1','glpi','2011-10-01 10:27:20','0','','Soundblaster X-Fi Titanium');
INSERT INTO `glpi_logs` VALUES ('183','Computer','9','DeviceMotherboard','1','glpi','2011-10-01 10:27:26','0','','Asus Crosshair V Formula Motherboard');
INSERT INTO `glpi_logs` VALUES ('184','Computer','9','DeviceControl','3','glpi','2011-10-01 10:28:05','0','Multilane 4X RAID5/JBOD PCI-E 8x Controller','');
INSERT INTO `glpi_logs` VALUES ('221','Ticket','5','User','15','Ching Tristan','2011-10-03 18:23:35','0','','Ching Tristan');
INSERT INTO `glpi_logs` VALUES ('187','RuleTicket','6','0','20','glpi','2011-10-03 18:18:11','0','','');
INSERT INTO `glpi_logs` VALUES ('188','RuleTicket','6','RuleCriteria','17','glpi','2011-10-03 18:18:18','0','','Urgency is Very High');
INSERT INTO `glpi_logs` VALUES ('189','RuleCriteria','9','0','20','glpi','2011-10-03 18:18:18','0','','');
INSERT INTO `glpi_logs` VALUES ('190','RuleTicket','6','RuleAction','17','glpi','2011-10-03 18:18:25','0','','Priority Assign Very High');
INSERT INTO `glpi_logs` VALUES ('191','RuleAction','6','0','20','glpi','2011-10-03 18:18:25','0','','');
INSERT INTO `glpi_logs` VALUES ('192','RuleTicket','6','RuleCriteria','17','glpi','2011-10-03 18:18:33','0','','Impact is Very High');
INSERT INTO `glpi_logs` VALUES ('193','RuleCriteria','10','0','20','glpi','2011-10-03 18:18:33','0','','');
INSERT INTO `glpi_logs` VALUES ('194','RuleTicket','7','0','20','glpi','2011-10-03 18:18:54','0','','');
INSERT INTO `glpi_logs` VALUES ('195','RuleTicket','7','RuleCriteria','17','glpi','2011-10-03 18:19:02','0','','Priority is Medium');
INSERT INTO `glpi_logs` VALUES ('196','RuleCriteria','11','0','20','glpi','2011-10-03 18:19:02','0','','');
INSERT INTO `glpi_logs` VALUES ('197','RuleTicket','7','RuleAction','17','glpi','2011-10-03 18:19:14','0','','Priority Assign Medium');
INSERT INTO `glpi_logs` VALUES ('198','RuleAction','7','0','20','glpi','2011-10-03 18:19:14','0','','');
INSERT INTO `glpi_logs` VALUES ('199','RuleTicket','7','RuleAction','17','glpi','2011-10-03 18:19:20','0','','Impact Assign Medium');
INSERT INTO `glpi_logs` VALUES ('200','RuleAction','8','0','20','glpi','2011-10-03 18:19:20','0','','');
INSERT INTO `glpi_logs` VALUES ('220','Ticket','4','0','20','Magpantay Lissa','2011-10-03 18:22:50','0','','');
INSERT INTO `glpi_logs` VALUES ('204','Profile','7','0','20','glpi','2011-10-03 18:21:13','0','','');
INSERT INTO `glpi_logs` VALUES ('205','Profile','7','','0','glpi','2011-10-03 18:21:34','102','','1');
INSERT INTO `glpi_logs` VALUES ('206','Profile','7','','0','glpi','2011-10-03 18:21:34','66','','0');
INSERT INTO `glpi_logs` VALUES ('207','Profile','7','','0','glpi','2011-10-03 18:21:34','75','','0');
INSERT INTO `glpi_logs` VALUES ('208','Profile','7','','0','glpi','2011-10-03 18:21:34','88','','0');
INSERT INTO `glpi_logs` VALUES ('209','Profile','7','','0','glpi','2011-10-03 18:21:34','89','','0');
INSERT INTO `glpi_logs` VALUES ('210','Profile','7','','0','glpi','2011-10-03 18:21:34','80','','0');
INSERT INTO `glpi_logs` VALUES ('211','Profile','7','','0','glpi','2011-10-03 18:21:34','86','0','1');
INSERT INTO `glpi_logs` VALUES ('212','Profile','7','','0','glpi','2011-10-03 18:21:34','87','','[\"Computer\",\"Monitor\",\"NetworkEquipment\",\"Peripheral\",\"Phone\",\"Printer\",\"Software\"]');
INSERT INTO `glpi_logs` VALUES ('213','Profile','7','','0','glpi','2011-10-03 18:21:34','99','','0');
INSERT INTO `glpi_logs` VALUES ('214','Profile','7','','0','glpi','2011-10-03 18:21:34','98','','0');
INSERT INTO `glpi_logs` VALUES ('215','Profile','7','','0','glpi','2011-10-03 18:21:34','36','','0');
INSERT INTO `glpi_logs` VALUES ('216','User','10','Profile_User','17','glpi','2011-10-03 18:21:50','0','','Dean, Root entity');
INSERT INTO `glpi_logs` VALUES ('217','User','10','Profile_User','19','glpi','2011-10-03 18:21:54','0','post-only, Root entity, D','');
INSERT INTO `glpi_logs` VALUES ('219','Ticket','4','User','15','Magpantay Lissa','2011-10-03 18:22:50','0','','Magpantay Lissa');
INSERT INTO `glpi_logs` VALUES ('222','Ticket','5','0','20','Ching Tristan','2011-10-03 18:23:35','0','','');

### Dump table glpi_mailcollectors

DROP TABLE IF EXISTS `glpi_mailcollectors`;
CREATE TABLE `glpi_mailcollectors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesize_max` int(11) NOT NULL DEFAULT '2097152',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `passwd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_manufacturers

DROP TABLE IF EXISTS `glpi_manufacturers`;
CREATE TABLE `glpi_manufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_manufacturers` VALUES ('1','Acer','');
INSERT INTO `glpi_manufacturers` VALUES ('2','eMachines','');
INSERT INTO `glpi_manufacturers` VALUES ('3','Gateway','');
INSERT INTO `glpi_manufacturers` VALUES ('4','Packard Bell','');
INSERT INTO `glpi_manufacturers` VALUES ('5','Apple Inc.','');
INSERT INTO `glpi_manufacturers` VALUES ('6','Asus','');
INSERT INTO `glpi_manufacturers` VALUES ('7','Benq','');
INSERT INTO `glpi_manufacturers` VALUES ('8','Dell','');
INSERT INTO `glpi_manufacturers` VALUES ('9','Fujitsu','');
INSERT INTO `glpi_manufacturers` VALUES ('10','Gigabyte','');
INSERT INTO `glpi_manufacturers` VALUES ('11','Hewlett-Packard','');
INSERT INTO `glpi_manufacturers` VALUES ('12','Compaq','');
INSERT INTO `glpi_manufacturers` VALUES ('13','Hitachi','');
INSERT INTO `glpi_manufacturers` VALUES ('14','IBM','');
INSERT INTO `glpi_manufacturers` VALUES ('15','Lenovo','');
INSERT INTO `glpi_manufacturers` VALUES ('16','LG','');
INSERT INTO `glpi_manufacturers` VALUES ('17','Micro-Star International','');
INSERT INTO `glpi_manufacturers` VALUES ('18','NEC Corp','');
INSERT INTO `glpi_manufacturers` VALUES ('19','NEO','');
INSERT INTO `glpi_manufacturers` VALUES ('20','Panasonic','');
INSERT INTO `glpi_manufacturers` VALUES ('21','Samsung Electronics','');
INSERT INTO `glpi_manufacturers` VALUES ('22','Sony','');
INSERT INTO `glpi_manufacturers` VALUES ('23','Toshiba','');
INSERT INTO `glpi_manufacturers` VALUES ('24','ViewSonic','');
INSERT INTO `glpi_manufacturers` VALUES ('25','Intel','');
INSERT INTO `glpi_manufacturers` VALUES ('26','Silicon','');

### Dump table glpi_monitormodels

DROP TABLE IF EXISTS `glpi_monitormodels`;
CREATE TABLE `glpi_monitormodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_monitors

DROP TABLE IF EXISTS `glpi_monitors`;
CREATE TABLE `glpi_monitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherserial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(11) NOT NULL DEFAULT '0',
  `have_micro` tinyint(1) NOT NULL DEFAULT '0',
  `have_speaker` tinyint(1) NOT NULL DEFAULT '0',
  `have_subd` tinyint(1) NOT NULL DEFAULT '0',
  `have_bnc` tinyint(1) NOT NULL DEFAULT '0',
  `have_dvi` tinyint(1) NOT NULL DEFAULT '0',
  `have_pivot` tinyint(1) NOT NULL DEFAULT '0',
  `have_hdmi` tinyint(1) NOT NULL DEFAULT '0',
  `have_displayport` tinyint(1) NOT NULL DEFAULT '0',
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `monitortypes_id` int(11) NOT NULL DEFAULT '0',
  `monitormodels_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `states_id` int(11) NOT NULL DEFAULT '0',
  `ticket_tco` decimal(20,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_template` (`is_template`),
  KEY `is_global` (`is_global`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`),
  KEY `locations_id` (`locations_id`),
  KEY `monitormodels_id` (`monitormodels_id`),
  KEY `states_id` (`states_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `monitortypes_id` (`monitortypes_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_monitortypes

DROP TABLE IF EXISTS `glpi_monitortypes`;
CREATE TABLE `glpi_monitortypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_monitortypes` VALUES ('1','LCD','');
INSERT INTO `glpi_monitortypes` VALUES ('2','LED','');

### Dump table glpi_netpoints

DROP TABLE IF EXISTS `glpi_netpoints`;
CREATE TABLE `glpi_netpoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `complete` (`entities_id`,`locations_id`,`name`),
  KEY `location_name` (`locations_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networkequipmentfirmwares

DROP TABLE IF EXISTS `glpi_networkequipmentfirmwares`;
CREATE TABLE `glpi_networkequipmentfirmwares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networkequipmentmodels

DROP TABLE IF EXISTS `glpi_networkequipmentmodels`;
CREATE TABLE `glpi_networkequipmentmodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networkequipments

DROP TABLE IF EXISTS `glpi_networkequipments`;
CREATE TABLE `glpi_networkequipments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ram` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherserial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `domains_id` int(11) NOT NULL DEFAULT '0',
  `networks_id` int(11) NOT NULL DEFAULT '0',
  `networkequipmenttypes_id` int(11) NOT NULL DEFAULT '0',
  `networkequipmentmodels_id` int(11) NOT NULL DEFAULT '0',
  `networkequipmentfirmwares_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `states_id` int(11) NOT NULL DEFAULT '0',
  `ticket_tco` decimal(20,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_template` (`is_template`),
  KEY `domains_id` (`domains_id`),
  KEY `networkequipmentfirmwares_id` (`networkequipmentfirmwares_id`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`),
  KEY `locations_id` (`locations_id`),
  KEY `networkequipmentmodels_id` (`networkequipmentmodels_id`),
  KEY `networks_id` (`networks_id`),
  KEY `states_id` (`states_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `networkequipmenttypes_id` (`networkequipmenttypes_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networkequipmenttypes

DROP TABLE IF EXISTS `glpi_networkequipmenttypes`;
CREATE TABLE `glpi_networkequipmenttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_networkequipmenttypes` VALUES ('1','Modem','');
INSERT INTO `glpi_networkequipmenttypes` VALUES ('2','Router','');

### Dump table glpi_networkinterfaces

DROP TABLE IF EXISTS `glpi_networkinterfaces`;
CREATE TABLE `glpi_networkinterfaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networkports

DROP TABLE IF EXISTS `glpi_networkports`;
CREATE TABLE `glpi_networkports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `logical_number` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `networkinterfaces_id` int(11) NOT NULL DEFAULT '0',
  `netpoints_id` int(11) NOT NULL DEFAULT '0',
  `netmask` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gateway` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subnet` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `on_device` (`items_id`,`itemtype`),
  KEY `networkinterfaces_id` (`networkinterfaces_id`),
  KEY `netpoints_id` (`netpoints_id`),
  KEY `item` (`itemtype`,`items_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networkports_networkports

DROP TABLE IF EXISTS `glpi_networkports_networkports`;
CREATE TABLE `glpi_networkports_networkports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `networkports_id_1` int(11) NOT NULL DEFAULT '0',
  `networkports_id_2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`networkports_id_1`,`networkports_id_2`),
  KEY `networkports_id_2` (`networkports_id_2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networkports_vlans

DROP TABLE IF EXISTS `glpi_networkports_vlans`;
CREATE TABLE `glpi_networkports_vlans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  `vlans_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`networkports_id`,`vlans_id`),
  KEY `vlans_id` (`vlans_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_networks

DROP TABLE IF EXISTS `glpi_networks`;
CREATE TABLE `glpi_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_notifications

DROP TABLE IF EXISTS `glpi_notifications`;
CREATE TABLE `glpi_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `event` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notificationtemplates_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `itemtype` (`itemtype`),
  KEY `entities_id` (`entities_id`),
  KEY `is_active` (`is_active`),
  KEY `date_mod` (`date_mod`),
  KEY `is_recursive` (`is_recursive`),
  KEY `notificationtemplates_id` (`notificationtemplates_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_notifications` VALUES ('1','Alert Tickets not closed','0','Ticket','alertnotclosed','mail','6','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('2','New Ticket','0','Ticket','new','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('3','Update Ticket','0','Ticket','update','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('4','Close Ticket','0','Ticket','closed','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('5','Add Followup','0','Ticket','add_followup','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('6','Add Task','0','Ticket','add_task','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('7','Update Followup','0','Ticket','update_followup','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('8','Update Task','0','Ticket','update_task','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('9','Delete Followup','0','Ticket','delete_followup','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('10','Delete Task','0','Ticket','delete_task','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('11','Resolve ticket','0','Ticket','solved','mail','4','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('12','Ticket Validation','0','Ticket','validation','mail','7','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('13','New Reservation','0','Reservation','new','mail','2','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('14','Update Reservation','0','Reservation','update','mail','2','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('15','Delete Reservation','0','Reservation','delete','mail','2','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('16','Alert Reservation','0','Reservation','alert','mail','3','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('17','Contract Notice','0','Contract','notice','mail','12','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('18','Contract End','0','Contract','end','mail','12','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('19','MySQL Synchronization','0','DBConnection','desynchronization','mail','1','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('20','Cartridges','0','Cartridge','alert','mail','8','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('21','Consumables','0','Consumable','alert','mail','9','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('22','Infocoms','0','Infocom','alert','mail','10','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('23','Software Licenses','0','SoftwareLicense','alert','mail','11','','1','1','2010-02-16 16:41:39');
INSERT INTO `glpi_notifications` VALUES ('24','Ticket Recall','0','Ticket','recall','mail','4','','1','1','2011-03-04 11:35:13');
INSERT INTO `glpi_notifications` VALUES ('25','Password Forget','0','User','passwordforget','mail','13','','1','1','2011-03-04 11:35:13');
INSERT INTO `glpi_notifications` VALUES ('26','Ticket Satisfaction','0','Ticket','satisfaction','mail','14','','1','1','2011-03-04 11:35:15');
INSERT INTO `glpi_notifications` VALUES ('27','Item not unique','0','FieldUnicity','refuse','mail','15','','1','1','2011-03-04 11:35:16');
INSERT INTO `glpi_notifications` VALUES ('28','Crontask Watcher','0','Crontask','alert','mail','16','','1','1','2011-03-04 11:35:16');

### Dump table glpi_notificationtargets

DROP TABLE IF EXISTS `glpi_notificationtargets`;
CREATE TABLE `glpi_notificationtargets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `notifications_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `items` (`type`,`items_id`),
  KEY `notifications_id` (`notifications_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_notificationtargets` VALUES ('1','3','1','13');
INSERT INTO `glpi_notificationtargets` VALUES ('2','1','1','13');
INSERT INTO `glpi_notificationtargets` VALUES ('3','3','2','2');
INSERT INTO `glpi_notificationtargets` VALUES ('4','1','1','2');
INSERT INTO `glpi_notificationtargets` VALUES ('5','1','1','3');
INSERT INTO `glpi_notificationtargets` VALUES ('6','1','1','5');
INSERT INTO `glpi_notificationtargets` VALUES ('7','1','1','4');
INSERT INTO `glpi_notificationtargets` VALUES ('8','2','1','3');
INSERT INTO `glpi_notificationtargets` VALUES ('9','4','1','3');
INSERT INTO `glpi_notificationtargets` VALUES ('10','3','1','2');
INSERT INTO `glpi_notificationtargets` VALUES ('11','3','1','3');
INSERT INTO `glpi_notificationtargets` VALUES ('12','3','1','5');
INSERT INTO `glpi_notificationtargets` VALUES ('13','3','1','4');
INSERT INTO `glpi_notificationtargets` VALUES ('14','1','1','19');
INSERT INTO `glpi_notificationtargets` VALUES ('15','14','1','12');
INSERT INTO `glpi_notificationtargets` VALUES ('16','3','1','14');
INSERT INTO `glpi_notificationtargets` VALUES ('17','1','1','14');
INSERT INTO `glpi_notificationtargets` VALUES ('18','3','1','15');
INSERT INTO `glpi_notificationtargets` VALUES ('19','1','1','15');
INSERT INTO `glpi_notificationtargets` VALUES ('20','1','1','6');
INSERT INTO `glpi_notificationtargets` VALUES ('21','3','1','6');
INSERT INTO `glpi_notificationtargets` VALUES ('22','1','1','7');
INSERT INTO `glpi_notificationtargets` VALUES ('23','3','1','7');
INSERT INTO `glpi_notificationtargets` VALUES ('24','1','1','8');
INSERT INTO `glpi_notificationtargets` VALUES ('25','3','1','8');
INSERT INTO `glpi_notificationtargets` VALUES ('26','1','1','9');
INSERT INTO `glpi_notificationtargets` VALUES ('27','3','1','9');
INSERT INTO `glpi_notificationtargets` VALUES ('28','1','1','10');
INSERT INTO `glpi_notificationtargets` VALUES ('29','3','1','10');
INSERT INTO `glpi_notificationtargets` VALUES ('30','1','1','11');
INSERT INTO `glpi_notificationtargets` VALUES ('31','3','1','11');
INSERT INTO `glpi_notificationtargets` VALUES ('32','19','1','25');
INSERT INTO `glpi_notificationtargets` VALUES ('33','3','1','26');
INSERT INTO `glpi_notificationtargets` VALUES ('34','21','1','2');
INSERT INTO `glpi_notificationtargets` VALUES ('35','21','1','3');
INSERT INTO `glpi_notificationtargets` VALUES ('36','21','1','5');
INSERT INTO `glpi_notificationtargets` VALUES ('37','21','1','4');
INSERT INTO `glpi_notificationtargets` VALUES ('38','21','1','6');
INSERT INTO `glpi_notificationtargets` VALUES ('39','21','1','7');
INSERT INTO `glpi_notificationtargets` VALUES ('40','21','1','8');
INSERT INTO `glpi_notificationtargets` VALUES ('41','21','1','9');
INSERT INTO `glpi_notificationtargets` VALUES ('42','21','1','10');
INSERT INTO `glpi_notificationtargets` VALUES ('43','21','1','11');
INSERT INTO `glpi_notificationtargets` VALUES ('44','21','1','26');
INSERT INTO `glpi_notificationtargets` VALUES ('45','19','1','27');
INSERT INTO `glpi_notificationtargets` VALUES ('46','1','1','28');

### Dump table glpi_notificationtemplates

DROP TABLE IF EXISTS `glpi_notificationtemplates`;
CREATE TABLE `glpi_notificationtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `css` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `itemtype` (`itemtype`),
  KEY `date_mod` (`date_mod`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_notificationtemplates` VALUES ('1','MySQL Synchronization','DBConnection','2010-02-01 15:51:46','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('2','Reservations','Reservation','2010-02-03 14:03:45','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('3','Alert Reservation','Reservation','2010-02-03 14:03:45','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('4','Tickets','Ticket','2010-02-07 21:39:15','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('5','Tickets (Simple)','Ticket','2010-02-07 21:39:15','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('6','Alert Tickets not closed','Ticket','2010-02-07 21:39:15','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('7','Tickets Validation','Ticket','2010-02-26 21:39:15','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('8','Cartridges','Cartridge','2010-02-16 13:17:24','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('9','Consumables','Consumable','2010-02-16 13:17:38','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('10','Infocoms','Infocom','2010-02-16 13:17:55','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('11','Licenses','SoftwareLicense','2010-02-16 13:18:12','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('12','Contracts','Contract','2010-02-16 13:18:12','',NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('13','Password Forget','User','2011-03-04 11:35:13',NULL,NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('14','Ticket Satisfaction','Ticket','2011-03-04 11:35:15',NULL,NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('15','Item not unique','FieldUnicity','2011-03-04 11:35:16',NULL,NULL);
INSERT INTO `glpi_notificationtemplates` VALUES ('16','Crontask','Crontask','2011-03-04 11:35:16',NULL,NULL);

### Dump table glpi_notificationtemplatetranslations

DROP TABLE IF EXISTS `glpi_notificationtemplatetranslations`;
CREATE TABLE `glpi_notificationtemplatetranslations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notificationtemplates_id` int(11) NOT NULL DEFAULT '0',
  `language` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content_text` text COLLATE utf8_unicode_ci,
  `content_html` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `notificationtemplates_id` (`notificationtemplates_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('1','1','','##lang.dbconnection.title##','##lang.dbconnection.delay## : ##dbconnection.delay##
','&lt;p&gt;##lang.dbconnection.delay## : ##dbconnection.delay##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('2','2','','##reservation.action##','======================================================================
##lang.reservation.user##: ##reservation.user##
##lang.reservation.item.name##: ##reservation.itemtype## - ##reservation.item.name##
##IFreservation.tech## ##lang.reservation.tech## ##reservation.tech## ##ENDIFreservation.tech##
##lang.reservation.begin##: ##reservation.begin##
##lang.reservation.end##: ##reservation.end##
##lang.reservation.comment##: ##reservation.comment##
======================================================================
','&lt;!-- description{ color: inherit; background: #ebebeb;border-style: solid;border-color: #8d8d8d; border-width: 0px 1px 1px 0px; } --&gt;
&lt;p&gt;&lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;##lang.reservation.user##:&lt;/span&gt;##reservation.user##&lt;br /&gt; &lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;##lang.reservation.item.name##:&lt;/span&gt;##reservation.itemtype## - ##reservation.item.name##&lt;br /&gt;##IFreservation.tech## ##lang.reservation.tech## ##reservation.tech####ENDIFreservation.tech##&lt;br /&gt; &lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;##lang.reservation.begin##:&lt;/span&gt; ##reservation.begin##&lt;br /&gt; &lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;##lang.reservation.end##:&lt;/span&gt;##reservation.end##&lt;br /&gt; &lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;##lang.reservation.comment##:&lt;/span&gt; ##reservation.comment##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('3','3','','##reservation.action##  ##reservation.entity##','##lang.reservation.entity## : ##reservation.entity## 

 
##FOREACHreservations## 
##lang.reservation.itemtype## : ##reservation.itemtype##

 ##lang.reservation.item## : ##reservation.item##
 
 ##reservation.url## 

 ##ENDFOREACHreservations##','&lt;p&gt;##lang.reservation.entity## : ##reservation.entity## &lt;br /&gt; &lt;br /&gt;
##FOREACHreservations## &lt;br /&gt;##lang.reservation.itemtype## :  ##reservation.itemtype##&lt;br /&gt;
 ##lang.reservation.item## :  ##reservation.item##&lt;br /&gt; &lt;br /&gt;
 &lt;a href=\"##reservation.url##\"&gt; ##reservation.url##&lt;/a&gt;&lt;br /&gt;
 ##ENDFOREACHreservations##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('4','4','','##ticket.action## ##ticket.title##',' ##IFticket.storestatus=solved##
 ##lang.ticket.url## : ##ticket.urlapprove##
 ##lang.ticket.autoclosewarning##
 ##lang.ticket.solvedate## : ##ticket.solvedate##
 ##lang.ticket.solution.type## : ##ticket.solution.type##
 ##lang.ticket.solution.description## : ##ticket.solution.description## ##ENDIFticket.storestatus##
 ##ELSEticket.storestatus## ##lang.ticket.url## : ##ticket.url## ##ENDELSEticket.storestatus##

 ##lang.ticket.description##

 ##lang.ticket.title## : ##ticket.title##
 ##lang.ticket.authors## : ##IFticket.authors## ##ticket.authors## ##ENDIFticket.authors## ##ELSEticket.authors##--##ENDELSEticket.authors##
 ##lang.ticket.creationdate## : ##ticket.creationdate##
 ##lang.ticket.closedate## : ##ticket.closedate##
 ##lang.ticket.requesttype## : ##ticket.requesttype##
##IFticket.itemtype## ##lang.ticket.item.name## : ##ticket.itemtype## - ##ticket.item.name## ##IFticket.item.model## - ##ticket.item.model## ##ENDIFticket.item.model## ##IFticket.item.serial## - ##ticket.item.serial## ##ENDIFticket.item.serial##  ##IFticket.item.otherserial## -##ticket.item.otherserial## ##ENDIFticket.item.otherserial## ##ENDIFticket.itemtype##
##IFticket.assigntousers## ##lang.ticket.assigntousers## : ##ticket.assigntousers## ##ENDIFticket.assigntousers##
 ##lang.ticket.status## : ##ticket.status##
##IFticket.assigntogroups## ##lang.ticket.assigntogroups## : ##ticket.assigntogroups## ##ENDIFticket.assigntogroups##
 ##lang.ticket.urgency## : ##ticket.urgency##
 ##lang.ticket.impact## : ##ticket.impact##
 ##lang.ticket.priority## : ##ticket.priority##
##IFticket.user.email## ##lang.ticket.user.email## : ##ticket.user.email ##ENDIFticket.user.email##
##IFticket.category## ##lang.ticket.category## : ##ticket.category## ##ENDIFticket.category## ##ELSEticket.category## ##lang.ticket.nocategoryassigned## ##ENDELSEticket.category##
 ##lang.ticket.content## : ##ticket.content##
 ##IFticket.storestatus=closed##

 ##lang.ticket.solvedate## : ##ticket.solvedate##
 ##lang.ticket.solution.type## : ##ticket.solution.type##
 ##lang.ticket.solution.description## : ##ticket.solution.description##
 ##ENDIFticket.storestatus##
 ##lang.ticket.numberoffollowups## : ##ticket.numberoffollowups##

##FOREACHfollowups##

 [##followup.date##] ##lang.followup.isprivate## : ##followup.isprivate##
 ##lang.followup.author## ##followup.author##
 ##lang.followup.description## ##followup.description##
 ##lang.followup.date## ##followup.date##
 ##lang.followup.requesttype## ##followup.requesttype##

##ENDFOREACHfollowups##
 ##lang.ticket.numberoftasks## : ##ticket.numberoftasks##

##FOREACHtasks##

 [##task.date##] ##lang.task.isprivate## : ##task.isprivate##
 ##lang.task.author## ##task.author##
 ##lang.task.description## ##task.description##
 ##lang.task.time## ##task.time##
 ##lang.task.category## ##task.category##

##ENDFOREACHtasks##','<!-- description{ color: inherit; background: #ebebeb; border-style: solid;border-color: #8d8d8d; border-width: 0px 1px 1px 0px; }    -->
<div>##IFticket.storestatus=solved##</div>
<div>##lang.ticket.url## : <a href=\"##ticket.urlapprove##\">##ticket.urlapprove##</a> <strong>&#160;</strong></div>
<div><strong>##lang.ticket.autoclosewarning##</strong></div>
<div><span style=\"color: #888888;\"><strong><span style=\"text-decoration: underline;\">##lang.ticket.solvedate##</span></strong></span> : ##ticket.solvedate##<br /><span style=\"text-decoration: underline; color: #888888;\"><strong>##lang.ticket.solution.type##</strong></span> : ##ticket.solution.type##<br /><span style=\"text-decoration: underline; color: #888888;\"><strong>##lang.ticket.solution.description##</strong></span> : ##ticket.solution.description## ##ENDIFticket.storestatus##</div>
<div>##ELSEticket.storestatus## ##lang.ticket.url## : <a href=\"##ticket.url##\">##ticket.url##</a> ##ENDELSEticket.storestatus##</div>
<p class=\"description b\"><strong>##lang.ticket.description##</strong></p>
<p><span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.title##</span>&#160;:##ticket.title## <br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.authors##</span>&#160;:##IFticket.authors## ##ticket.authors## ##ENDIFticket.authors##    ##ELSEticket.authors##--##ENDELSEticket.authors## <br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.creationdate##</span>&#160;:##ticket.creationdate## <br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.closedate##</span>&#160;:##ticket.closedate## <br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.requesttype##</span>&#160;:##ticket.requesttype##<br /> ##IFticket.itemtype## <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.item.name##</span>&#160;: ##ticket.itemtype## - ##ticket.item.name##    ##IFticket.item.model## - ##ticket.item.model##    ##ENDIFticket.item.model## ##IFticket.item.serial## -##ticket.item.serial## ##ENDIFticket.item.serial##&#160; ##IFticket.item.otherserial## -##ticket.item.otherserial##  ##ENDIFticket.item.otherserial## ##ENDIFticket.itemtype## <br /> ##IFticket.assigntousers## <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.assigntousers##</span>&#160;: ##ticket.assigntousers## ##ENDIFticket.assigntousers##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\">##lang.ticket.status## </span>&#160;: ##ticket.status##<br /> ##IFticket.assigntogroups## <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.assigntogroups##</span>&#160;: ##ticket.assigntogroups## ##ENDIFticket.assigntogroups##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.urgency##</span>&#160;: ##ticket.urgency##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.impact##</span>&#160;: ##ticket.impact##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.priority##</span>&#160;: ##ticket.priority## <br /> ##IFticket.user.email##<span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.user.email##</span>&#160;: ##ticket.user.email ##ENDIFticket.user.email##    <br /> ##IFticket.category##<span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\">##lang.ticket.category## </span>&#160;:##ticket.category## ##ENDIFticket.category## ##ELSEticket.category## ##lang.ticket.nocategoryassigned## ##ENDELSEticket.category##    <br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.ticket.content##</span>&#160;: ##ticket.content##</p>
<br />##IFticket.storestatus=closed##<br /><span style=\"text-decoration: underline;\"><strong><span style=\"color: #888888;\">##lang.ticket.solvedate##</span></strong></span> : ##ticket.solvedate##<br /><span style=\"color: #888888;\"><strong><span style=\"text-decoration: underline;\">##lang.ticket.solution.type##</span></strong></span> : ##ticket.solution.type##<br /><span style=\"text-decoration: underline; color: #888888;\"><strong>##lang.ticket.solution.description##</strong></span> : ##ticket.solution.description##<br />##ENDIFticket.storestatus##</p>
<div class=\"description b\">##lang.ticket.numberoffollowups##&#160;: ##ticket.numberoffollowups##</div>
<p>##FOREACHfollowups##</p>
<div class=\"description b\"><br /> <strong> [##followup.date##] <em>##lang.followup.isprivate## : ##followup.isprivate## </em></strong><br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.followup.author## </span> ##followup.author##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.followup.description## </span> ##followup.description##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.followup.date## </span> ##followup.date##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.followup.requesttype## </span> ##followup.requesttype##</div>
<p>##ENDFOREACHfollowups##</p>
<div class=\"description b\">##lang.ticket.numberoftasks##&#160;: ##ticket.numberoftasks##</div>
<p>##FOREACHtasks##</p>
<div class=\"description b\"><br /> <strong> [##task.date##] <em>##lang.task.isprivate## : ##task.isprivate## </em></strong><br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.task.author##</span> ##task.author##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.task.description##</span> ##task.description##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.task.time##</span> ##task.time##<br /> <span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"> ##lang.task.category##</span> ##task.category##</div>
<p>##ENDFOREACHtasks##</p>');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('5','12','','##contract.action##  ##contract.entity##','##lang.contract.entity## : ##contract.entity##

##FOREACHcontracts##
##lang.contract.name## : ##contract.name##
##lang.contract.number## : ##contract.number##
##lang.contract.time## : ##contract.time##
##IFcontract.type####lang.contract.type## : ##contract.type####ENDIFcontract.type##
##contract.url##
##ENDFOREACHcontracts##','&lt;p&gt;##lang.contract.entity## : ##contract.entity##&lt;br /&gt;
&lt;br /&gt;##FOREACHcontracts##&lt;br /&gt;##lang.contract.name## :
##contract.name##&lt;br /&gt;
##lang.contract.number## : ##contract.number##&lt;br /&gt;
##lang.contract.time## : ##contract.time##&lt;br /&gt;
##IFcontract.type####lang.contract.type## : ##contract.type##
##ENDIFcontract.type##&lt;br /&gt;
&lt;a href=\"##contract.url##\"&gt;
##contract.url##&lt;/a&gt;&lt;br /&gt;
##ENDFOREACHcontracts##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('6','5','','##ticket.action## ##ticket.title##','##lang.ticket.url## : ##ticket.url## 

##lang.ticket.description## 


##lang.ticket.title## &#160;:##ticket.title## 

##lang.ticket.authors## &#160;:##IFticket.authors##
##ticket.authors## ##ENDIFticket.authors##
##ELSEticket.authors##--##ENDELSEticket.authors## &#160; 

##IFticket.category## ##lang.ticket.category## &#160;:##ticket.category##
##ENDIFticket.category## ##ELSEticket.category##
##lang.ticket.nocategoryassigned## ##ENDELSEticket.category##

##lang.ticket.content## &#160;: ##ticket.content##
##IFticket.itemtype##
##lang.ticket.item.name## &#160;: ##ticket.itemtype## - ##ticket.item.name##
##ENDIFticket.itemtype##','&lt;div&gt;##lang.ticket.url## : &lt;a href=\"##ticket.url##\"&gt;
##ticket.url##&lt;/a&gt;&lt;/div&gt;
&lt;div class=\"description b\"&gt;
##lang.ticket.description##&lt;/div&gt;
&lt;p&gt;&lt;span
style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;
##lang.ticket.title##&lt;/span&gt;&#160;:##ticket.title##
&lt;br /&gt; &lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;
##lang.ticket.authors##&lt;/span&gt;
##IFticket.authors## ##ticket.authors##
##ENDIFticket.authors##
##ELSEticket.authors##--##ENDELSEticket.authors##
&lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;&#160
;&lt;/span&gt;&lt;br /&gt; &lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt; &lt;/span&gt;
##IFticket.category##&lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;
##lang.ticket.category## &lt;/span&gt;&#160;:##ticket.category##
##ENDIFticket.category## ##ELSEticket.category##
##lang.ticket.nocategoryassigned## ##ENDELSEticket.category##
&lt;br /&gt; &lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;
##lang.ticket.content##&lt;/span&gt;&#160;:
##ticket.content##&lt;br /&gt;##IFticket.itemtype##
&lt;span style=\"color: #8b8c8f; font-weight: bold; text-decoration: underline;\"&gt;
##lang.ticket.item.name##&lt;/span&gt;&#160;:
##ticket.itemtype## - ##ticket.item.name##
##ENDIFticket.itemtype##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('15','15','','##lang.unicity.action##','##lang.unicity.entity## : ##unicity.entity## 

##lang.unicity.itemtype## : ##unicity.itemtype## 

##lang.unicity.message## : ##unicity.message## 

##lang.unicity.action_user## : ##unicity.action_user## 

##lang.unicity.action_type## : ##unicity.action_type## 

##lang.unicity.date## : ##unicity.date##','&lt;p&gt;##lang.unicity.entity## : ##unicity.entity##&lt;/p&gt;
&lt;p&gt;##lang.unicity.itemtype## : ##unicity.itemtype##&lt;/p&gt;
&lt;p&gt;##lang.unicity.message## : ##unicity.message##&lt;/p&gt;
&lt;p&gt;##lang.unicity.action_user## : ##unicity.action_user##&lt;/p&gt;
&lt;p&gt;##lang.unicity.action_type## : ##unicity.action_type##&lt;/p&gt;
&lt;p&gt;##lang.unicity.date## : ##unicity.date##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('7','7','','##ticket.action## ##ticket.title##','##FOREACHvalidations##

##IFvalidation.storestatus=waiting##
##validation.submission.title##
##lang.validation.commentsubmission## : ##validation.commentsubmission##
##ENDIFvalidation.storestatus##
##ELSEvalidation.storestatus## ##validation.answer.title## ##ENDELSEvalidation.storestatus##

##lang.ticket.url## : ##ticket.urlvalidation##

##IFvalidation.status## ##lang.validation.status## : ##validation.status## ##ENDIFvalidation.status##
##IFvalidation.commentvalidation##
##lang.validation.commentvalidation## : ##validation.commentvalidation##
##ENDIFvalidation.commentvalidation##
##ENDFOREACHvalidations##','&lt;div&gt;##FOREACHvalidations##&lt;/div&gt;
&lt;p&gt;##IFvalidation.storestatus=waiting##&lt;/p&gt;
&lt;div&gt;##validation.submission.title##&lt;/div&gt;
&lt;div&gt;##lang.validation.commentsubmission## : ##validation.commentsubmission##&lt;/div&gt;
&lt;div&gt;##ENDIFvalidation.storestatus##&lt;/div&gt;
&lt;div&gt;##ELSEvalidation.storestatus## ##validation.answer.title## ##ENDELSEvalidation.storestatus##&lt;/div&gt;
&lt;div&gt;&lt;/div&gt;
&lt;div&gt;
&lt;div&gt;##lang.ticket.url## : &lt;a href=\"##ticket.urlvalidation##\"&gt; ##ticket.urlvalidation## &lt;/a&gt;&lt;/div&gt;
&lt;/div&gt;
&lt;p&gt;##IFvalidation.status## ##lang.validation.status## : ##validation.status## ##ENDIFvalidation.status##
&lt;br /&gt; ##IFvalidation.commentvalidation##&lt;br /&gt; ##lang.validation.commentvalidation## :
&#160; ##validation.commentvalidation##&lt;br /&gt; ##ENDIFvalidation.commentvalidation##
&lt;br /&gt;##ENDFOREACHvalidations##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('8','6','','##ticket.action## ##ticket.entity##','##lang.ticket.entity## : ##ticket.entity##
 
##FOREACHtickets##

##lang.ticket.title## : ##ticket.title##
 ##lang.ticket.status## : ##ticket.status##

 ##ticket.url## 
 ##ENDFOREACHtickets##','&lt;table class=\"tab_cadre\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\"&gt;
&lt;tbody&gt;
&lt;tr&gt;
&lt;td style=\"text-align: left;\" width=\"auto\" bgcolor=\"#cccccc\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##lang.ticket.authors##&lt;/span&gt;&lt;/td&gt;
&lt;td style=\"text-align: left;\" width=\"auto\" bgcolor=\"#cccccc\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##lang.ticket.title##&lt;/span&gt;&lt;/td&gt;
&lt;td style=\"text-align: left;\" width=\"auto\" bgcolor=\"#cccccc\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##lang.ticket.priority##&lt;/span&gt;&lt;/td&gt;
&lt;td style=\"text-align: left;\" width=\"auto\" bgcolor=\"#cccccc\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##lang.ticket.status##&lt;/span&gt;&lt;/td&gt;
&lt;td style=\"text-align: left;\" width=\"auto\" bgcolor=\"#cccccc\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##lang.ticket.attribution##&lt;/span&gt;&lt;/td&gt;
&lt;td style=\"text-align: left;\" width=\"auto\" bgcolor=\"#cccccc\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##lang.ticket.creationdate##&lt;/span&gt;&lt;/td&gt;
&lt;td style=\"text-align: left;\" width=\"auto\" bgcolor=\"#cccccc\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##lang.ticket.content##&lt;/span&gt;&lt;/td&gt;
&lt;/tr&gt;
##FOREACHtickets##                   
&lt;tr&gt;
&lt;td width=\"auto\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##ticket.authors##&lt;/span&gt;&lt;/td&gt;
&lt;td width=\"auto\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;&lt;a href=\"##ticket.url##\"&gt;##ticket.title##&lt;/a&gt;&lt;/span&gt;&lt;/td&gt;
&lt;td width=\"auto\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##ticket.priority##&lt;/span&gt;&lt;/td&gt;
&lt;td width=\"auto\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##ticket.status##&lt;/span&gt;&lt;/td&gt;
&lt;td width=\"auto\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##IFticket.assigntousers####ticket.assigntousers##&lt;br /&gt;##ENDIFticket.assigntousers####IFticket.assigntogroups##&lt;br /&gt;##ticket.assigntogroups## ##ENDIFticket.assigntogroups####IFticket.assigntosupplier##&lt;br /&gt;##ticket.assigntosupplier## ##ENDIFticket.assigntosupplier##&lt;/span&gt;&lt;/td&gt;
&lt;td width=\"auto\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##ticket.creationdate##&lt;/span&gt;&lt;/td&gt;
&lt;td width=\"auto\"&gt;&lt;span style=\"font-size: 11px; text-align: left;\"&gt;##ticket.content##&lt;/span&gt;&lt;/td&gt;
&lt;/tr&gt;
##ENDFOREACHtickets##
&lt;/tbody&gt;
&lt;/table&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('9','9','','##consumable.action##  ##consumable.entity##','##lang.consumable.entity## : ##consumable.entity##
 

##FOREACHconsumables##
##lang.consumable.item## : ##consumable.item##
 

##lang.consumable.reference## : ##consumable.reference##

##lang.consumable.remaining## : ##consumable.remaining##

##consumable.url## 

##ENDFOREACHconsumables##','&lt;p&gt;
##lang.consumable.entity## : ##consumable.entity##
&lt;br /&gt; &lt;br /&gt;##FOREACHconsumables##
&lt;br /&gt;##lang.consumable.item## : ##consumable.item##&lt;br /&gt;
&lt;br /&gt;##lang.consumable.reference## : ##consumable.reference##&lt;br /&gt;
##lang.consumable.remaining## : ##consumable.remaining##&lt;br /&gt;
&lt;a href=\"##consumable.url##\"&gt; ##consumable.url##&lt;/a&gt;&lt;br /&gt;
   ##ENDFOREACHconsumables##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('10','8','','##cartridge.action##  ##cartridge.entity##','##lang.cartridge.entity## : ##cartridge.entity##
 

##FOREACHcartridges##
##lang.cartridge.item## : ##cartridge.item##
 

##lang.cartridge.reference## : ##cartridge.reference##

##lang.cartridge.remaining## : ##cartridge.remaining##

##cartridge.url## 
 ##ENDFOREACHcartridges##','&lt;p&gt;##lang.cartridge.entity## : ##cartridge.entity##
&lt;br /&gt; &lt;br /&gt;##FOREACHcartridges##
&lt;br /&gt;##lang.cartridge.item## :
##cartridge.item##&lt;br /&gt; &lt;br /&gt;
##lang.cartridge.reference## :
##cartridge.reference##&lt;br /&gt;
##lang.cartridge.remaining## :
##cartridge.remaining##&lt;br /&gt;
&lt;a href=\"##cartridge.url##\"&gt;
##cartridge.url##&lt;/a&gt;&lt;br /&gt;
##ENDFOREACHcartridges##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('11','10','','##infocom.action##  ##infocom.entity##','##lang.infocom.entity## : ##infocom.entity## 
 

##FOREACHinfocoms## 

##lang.infocom.itemtype## : ##infocom.itemtype##

##lang.infocom.item## : ##infocom.item##
 

##lang.infocom.expirationdate## : ##infocom.expirationdate##

##infocom.url## 
 ##ENDFOREACHinfocoms##','&lt;p&gt;##lang.infocom.entity## : ##infocom.entity##
&lt;br /&gt; &lt;br /&gt;##FOREACHinfocoms##
&lt;br /&gt;##lang.infocom.itemtype## : ##infocom.itemtype##&lt;br /&gt;
##lang.infocom.item## : ##infocom.item##&lt;br /&gt; &lt;br /&gt;
##lang.infocom.expirationdate## : ##infocom.expirationdate##
&lt;br /&gt; &lt;a href=\"##infocom.url##\"&gt;
##infocom.url##&lt;/a&gt;&lt;br /&gt;
##ENDFOREACHinfocoms##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('12','11','','##license.action##  ##license.entity##','##lang.license.entity## : ##license.entity##

##FOREACHlicenses## 

##lang.license.item## : ##license.item##

##lang.license.serial## : ##license.serial##

##lang.license.expirationdate## : ##license.expirationdate##

##license.url## 
 ##ENDFOREACHlicenses##','&lt;p&gt;
##lang.license.entity## : ##license.entity##&lt;br /&gt;
##FOREACHlicenses##
&lt;br /&gt;##lang.license.item## : ##license.item##&lt;br /&gt;
##lang.license.serial## : ##license.serial##&lt;br /&gt;
##lang.license.expirationdate## : ##license.expirationdate##
&lt;br /&gt; &lt;a href=\"##license.url##\"&gt; ##license.url##
&lt;/a&gt;&lt;br /&gt; ##ENDFOREACHlicenses##&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('13','13','','##user.action##','##lang.user.realname## ##lang.user.firstname##

##lang.passwordforget.information##

##lang.passwordforget.link## ##user.passwordforgeturl##','&lt;p&gt;&lt;strong&gt;##lang.user.realname## ##lang.user.firstname##&lt;/strong&gt;&lt;/p&gt;
&lt;p&gt;##lang.passwordforget.information##&lt;/p&gt;
&lt;p&gt;##lang.passwordforget.link## &lt;a title=\"##user.passwordforgeturl##\" href=\"##user.passwordforgeturl##\"&gt;##user.passwordforgeturl##&lt;/a&gt;&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('14','14','','##ticket.action## ##ticket.title##','##lang.ticket.title## : ##ticket.title##

##lang.ticket.closedate## : ##ticket.closedate##

##lang.satisfaction.text## ##ticket.urlsatisfaction##','&lt;p&gt;##lang.ticket.title## : ##ticket.title##&lt;/p&gt;
&lt;p&gt;##lang.ticket.closedate## : ##ticket.closedate##&lt;/p&gt;
&lt;p&gt;##lang.satisfaction.text## &lt;a href=\"##ticket.urlsatisfaction##\"&gt;##ticket.urlsatisfaction##&lt;/a&gt;&lt;/p&gt;');
INSERT INTO `glpi_notificationtemplatetranslations` VALUES ('16','16','','##crontask.action##','##lang.crontask.warning## 

##FOREACHcrontasks## 
 ##crontask.name## : ##crontask.description##
 
##ENDFOREACHcrontasks##','&lt;p&gt;##lang.crontask.warning##&lt;/p&gt;
&lt;p&gt;##FOREACHcrontasks## &lt;br /&gt;&lt;a href=\"##crontask.url##\"&gt;##crontask.name##&lt;/a&gt; : ##crontask.description##&lt;br /&gt; &lt;br /&gt;##ENDFOREACHcrontasks##&lt;/p&gt;');

### Dump table glpi_notimportedemails

DROP TABLE IF EXISTS `glpi_notimportedemails`;
CREATE TABLE `glpi_notimportedemails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `mailcollectors_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `subject` text,
  `messageid` varchar(255) NOT NULL,
  `reason` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `mailcollectors_id` (`mailcollectors_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


### Dump table glpi_numberofcomponents

DROP TABLE IF EXISTS `glpi_numberofcomponents`;
CREATE TABLE `glpi_numberofcomponents` (
  `ComponentId` int(11) NOT NULL,
  `Total` int(11) DEFAULT NULL,
  PRIMARY KEY (`ComponentId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


### Dump table glpi_ocsadmininfoslinks

DROP TABLE IF EXISTS `glpi_ocsadmininfoslinks`;
CREATE TABLE `glpi_ocsadmininfoslinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `glpi_column` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_column` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocsservers_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ocsservers_id` (`ocsservers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ocslinks

DROP TABLE IF EXISTS `glpi_ocslinks`;
CREATE TABLE `glpi_ocslinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `ocsid` int(11) NOT NULL DEFAULT '0',
  `ocs_deviceid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `use_auto_update` tinyint(1) NOT NULL DEFAULT '1',
  `last_update` datetime DEFAULT NULL,
  `last_ocs_update` datetime DEFAULT NULL,
  `computer_update` longtext COLLATE utf8_unicode_ci,
  `import_device` longtext COLLATE utf8_unicode_ci,
  `import_disk` longtext COLLATE utf8_unicode_ci,
  `import_software` longtext COLLATE utf8_unicode_ci,
  `import_monitor` longtext COLLATE utf8_unicode_ci,
  `import_peripheral` longtext COLLATE utf8_unicode_ci,
  `import_printer` longtext COLLATE utf8_unicode_ci,
  `ocsservers_id` int(11) NOT NULL DEFAULT '0',
  `import_ip` longtext COLLATE utf8_unicode_ci,
  `ocs_agent_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `import_vm` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`ocsservers_id`,`ocsid`),
  KEY `last_update` (`last_update`),
  KEY `ocs_deviceid` (`ocs_deviceid`),
  KEY `last_ocs_update` (`ocsservers_id`,`last_ocs_update`),
  KEY `computers_id` (`computers_id`),
  KEY `use_auto_update` (`use_auto_update`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ocsservers

DROP TABLE IF EXISTS `glpi_ocsservers`;
CREATE TABLE `glpi_ocsservers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_db_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_db_passwd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_db_host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_db_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_db_utf8` tinyint(1) NOT NULL DEFAULT '0',
  `checksum` int(11) NOT NULL DEFAULT '0',
  `import_periph` tinyint(1) NOT NULL DEFAULT '0',
  `import_monitor` tinyint(1) NOT NULL DEFAULT '0',
  `import_software` tinyint(1) NOT NULL DEFAULT '0',
  `import_printer` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_name` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_os` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_serial` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_model` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_manufacturer` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_type` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_domain` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_contact` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_comment` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_processor` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_memory` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_hdd` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_iface` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_gfxcard` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_sound` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_drive` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_port` tinyint(1) NOT NULL DEFAULT '0',
  `import_device_modem` tinyint(1) NOT NULL DEFAULT '0',
  `import_registry` tinyint(1) NOT NULL DEFAULT '0',
  `import_os_serial` tinyint(1) NOT NULL DEFAULT '0',
  `import_ip` tinyint(1) NOT NULL DEFAULT '0',
  `import_disk` tinyint(1) NOT NULL DEFAULT '0',
  `import_monitor_comment` tinyint(1) NOT NULL DEFAULT '0',
  `states_id_default` int(11) NOT NULL DEFAULT '0',
  `tag_limit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag_exclude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `use_soft_dict` tinyint(1) NOT NULL DEFAULT '0',
  `cron_sync_number` int(11) DEFAULT '1',
  `deconnection_behavior` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_behavior` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `import_vms` tinyint(1) NOT NULL DEFAULT '0',
  `import_general_uuid` tinyint(1) NOT NULL DEFAULT '0',
  `ocs_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_ocsservers` VALUES ('1','localhost','ocs','ocs','localhost','ocsweb','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','',NULL,'0','1',NULL,'',NULL,NULL,'1','1','0','0',NULL);

### Dump table glpi_operatingsystems

DROP TABLE IF EXISTS `glpi_operatingsystems`;
CREATE TABLE `glpi_operatingsystems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_operatingsystems` VALUES ('12','Windows','');
INSERT INTO `glpi_operatingsystems` VALUES ('13','Linux','');
INSERT INTO `glpi_operatingsystems` VALUES ('14','MAC','');

### Dump table glpi_operatingsystemservicepacks

DROP TABLE IF EXISTS `glpi_operatingsystemservicepacks`;
CREATE TABLE `glpi_operatingsystemservicepacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_operatingsystemversions

DROP TABLE IF EXISTS `glpi_operatingsystemversions`;
CREATE TABLE `glpi_operatingsystemversions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_operatingsystemversions` VALUES ('2','Windows 95','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('3','Windows 98','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('4','Windows Millennium Edition','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('5','Windows 2000','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('6','Windows XP','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('7','Windows Server 2003','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('8','Windows Fundamentals for Legacy PCs','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('9','Windows Vista','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('10','Windows Home Server','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('11','Windows Server 2008','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('12','Windows 7','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('16','Mac OS X v10.0 (Cheetah)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('14','Windows Server 2008 R2','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('15','Windows Home Server 2011','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('17','Mac OS X v10.1 (Puma)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('18','Mac OS X v10.2 (Jaguar)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('19','Mac OS X v10.3 (Panther)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('20','Mac OS X v10.4 (Tiger)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('21','Mac OS X v10.5 (Leopard)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('22','Mac OS X v10.6 (Snow Leopard)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('23','Mac OS X v10.7 (Lion)','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('24','Mac OS X Server','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('25','Linux Knoppix','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('26','Linux Ubuntu','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('27','Linux Gentoo','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('28','Linux Pacman','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('29','Linux Fedora','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('30','Red Hat Enterprise Linux','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('31','Linux Mandriva','');
INSERT INTO `glpi_operatingsystemversions` VALUES ('32','Linux Slackware','');

### Dump table glpi_peripheralmodels

DROP TABLE IF EXISTS `glpi_peripheralmodels`;
CREATE TABLE `glpi_peripheralmodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_peripherals

DROP TABLE IF EXISTS `glpi_peripherals`;
CREATE TABLE `glpi_peripherals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherserial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `peripheraltypes_id` int(11) NOT NULL DEFAULT '0',
  `peripheralmodels_id` int(11) NOT NULL DEFAULT '0',
  `brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `states_id` int(11) NOT NULL DEFAULT '0',
  `ticket_tco` decimal(20,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_template` (`is_template`),
  KEY `is_global` (`is_global`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`),
  KEY `locations_id` (`locations_id`),
  KEY `peripheralmodels_id` (`peripheralmodels_id`),
  KEY `states_id` (`states_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `peripheraltypes_id` (`peripheraltypes_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_peripherals` VALUES ('1','0','k','2011-09-28 11:40:36',NULL,NULL,'0',NULL,'','','0','0','0','','0','0','1','0',NULL,NULL,'0','0','0','0.0000');

### Dump table glpi_peripheraltypes

DROP TABLE IF EXISTS `glpi_peripheraltypes`;
CREATE TABLE `glpi_peripheraltypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_phonemodels

DROP TABLE IF EXISTS `glpi_phonemodels`;
CREATE TABLE `glpi_phonemodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_phonepowersupplies

DROP TABLE IF EXISTS `glpi_phonepowersupplies`;
CREATE TABLE `glpi_phonepowersupplies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_phones

DROP TABLE IF EXISTS `glpi_phones`;
CREATE TABLE `glpi_phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherserial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firmware` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `phonetypes_id` int(11) NOT NULL DEFAULT '0',
  `phonemodels_id` int(11) NOT NULL DEFAULT '0',
  `brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phonepowersupplies_id` int(11) NOT NULL DEFAULT '0',
  `number_line` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `have_headset` tinyint(1) NOT NULL DEFAULT '0',
  `have_hp` tinyint(1) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `states_id` int(11) NOT NULL DEFAULT '0',
  `ticket_tco` decimal(20,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_template` (`is_template`),
  KEY `is_global` (`is_global`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`),
  KEY `locations_id` (`locations_id`),
  KEY `phonemodels_id` (`phonemodels_id`),
  KEY `phonepowersupplies_id` (`phonepowersupplies_id`),
  KEY `states_id` (`states_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `phonetypes_id` (`phonetypes_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_phonetypes

DROP TABLE IF EXISTS `glpi_phonetypes`;
CREATE TABLE `glpi_phonetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_behaviors_configs

DROP TABLE IF EXISTS `glpi_plugin_behaviors_configs`;
CREATE TABLE `glpi_plugin_behaviors_configs` (
  `id` int(11) NOT NULL,
  `use_requester_item_group` tinyint(1) NOT NULL DEFAULT '0',
  `use_requester_user_group` tinyint(1) NOT NULL DEFAULT '0',
  `is_ticketsolutiontype_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_ticketrealtime_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_requester_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_ticketdate_locked` tinyint(1) NOT NULL DEFAULT '0',
  `use_assign_user_group` tinyint(1) NOT NULL DEFAULT '0',
  `sql_user_group_filter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sql_tech_group_filter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tickets_id_format` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remove_from_ocs` tinyint(1) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_behaviors_configs` VALUES ('1','0','0','0','0','0','0','0',NULL,NULL,NULL,'0','2011-10-01 16:38:17',NULL);

### Dump table glpi_plugin_genericobject_links

DROP TABLE IF EXISTS `glpi_plugin_genericobject_links`;
CREATE TABLE `glpi_plugin_genericobject_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `destination_type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Device type links definitions';


### Dump table glpi_plugin_genericobject_profiles

DROP TABLE IF EXISTS `glpi_plugin_genericobject_profiles`;
CREATE TABLE `glpi_plugin_genericobject_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profiles_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `right` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `open_ticket` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`profiles_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_genericobject_types

DROP TABLE IF EXISTS `glpi_plugin_genericobject_types`;
CREATE TABLE `glpi_plugin_genericobject_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `use_unicity` tinyint(1) NOT NULL DEFAULT '0',
  `use_history` tinyint(1) NOT NULL DEFAULT '0',
  `use_infocoms` tinyint(1) NOT NULL DEFAULT '0',
  `use_contracts` tinyint(1) NOT NULL DEFAULT '0',
  `use_documents` tinyint(1) NOT NULL DEFAULT '0',
  `use_tickets` tinyint(1) NOT NULL DEFAULT '0',
  `use_links` tinyint(1) NOT NULL DEFAULT '0',
  `use_loans` tinyint(1) NOT NULL DEFAULT '0',
  `use_network_ports` tinyint(1) NOT NULL DEFAULT '0',
  `use_direct_connections` tinyint(1) NOT NULL DEFAULT '0',
  `use_plugin_datainjection` tinyint(1) NOT NULL DEFAULT '0',
  `use_plugin_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `use_plugin_order` tinyint(1) NOT NULL DEFAULT '0',
  `use_plugin_uninstall` tinyint(1) NOT NULL DEFAULT '0',
  `use_plugin_geninventorynumber` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Object types definition table';


### Dump table glpi_plugin_manageentities_configs

DROP TABLE IF EXISTS `glpi_plugin_manageentities_configs`;
CREATE TABLE `glpi_plugin_manageentities_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `backup` int(11) NOT NULL DEFAULT '0',
  `documentcategories_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_documentcategories (id)',
  `hourbyday` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `documentcategories_id` (`documentcategories_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_manageentities_configs` VALUES ('1','0','0','8');

### Dump table glpi_plugin_manageentities_contacts

DROP TABLE IF EXISTS `glpi_plugin_manageentities_contacts`;
CREATE TABLE `glpi_plugin_manageentities_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contacts_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_contacts (id)',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`contacts_id`,`entities_id`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_manageentities_contractdays

DROP TABLE IF EXISTS `glpi_plugin_manageentities_contractdays`;
CREATE TABLE `glpi_plugin_manageentities_contractdays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `plugin_manageentities_critypes_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_plugin_manageentities_critypes (id)',
  `contracts_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_contracts (id)',
  `nbday` decimal(20,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `contracts_id` (`contracts_id`),
  KEY `plugin_manageentities_critypes_id` (`plugin_manageentities_critypes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_manageentities_contracts

DROP TABLE IF EXISTS `glpi_plugin_manageentities_contracts`;
CREATE TABLE `glpi_plugin_manageentities_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contracts_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_contracts (id)',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`contracts_id`,`entities_id`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_manageentities_cridetails

DROP TABLE IF EXISTS `glpi_plugin_manageentities_cridetails`;
CREATE TABLE `glpi_plugin_manageentities_cridetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `documents_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_documents (id)',
  `plugin_manageentities_critypes_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_plugin_manageentities_critypes (id)',
  `withcontract` int(11) NOT NULL DEFAULT '0',
  `contracts_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_contracts (id)',
  `realtime` decimal(20,2) DEFAULT '0.00',
  `technicians` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_id` (`documents_id`),
  KEY `plugin_manageentities_critypes_id` (`plugin_manageentities_critypes_id`),
  KEY `contracts_id` (`contracts_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_manageentities_criprices

DROP TABLE IF EXISTS `glpi_plugin_manageentities_criprices`;
CREATE TABLE `glpi_plugin_manageentities_criprices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `plugin_manageentities_critypes_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_plugin_manageentities_critypes (id)',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `plugin_manageentities_critypes_id` (`plugin_manageentities_critypes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_manageentities_critechnicians

DROP TABLE IF EXISTS `glpi_plugin_manageentities_critechnicians`;
CREATE TABLE `glpi_plugin_manageentities_critechnicians` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_tickets (id)',
  `users_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_users (id)',
  PRIMARY KEY (`id`),
  KEY `tickets_id` (`tickets_id`),
  KEY `users_id` (`users_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_manageentities_critypes

DROP TABLE IF EXISTS `glpi_plugin_manageentities_critypes`;
CREATE TABLE `glpi_plugin_manageentities_critypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_manageentities_critypes` VALUES ('1','Urgent intervention',NULL);
INSERT INTO `glpi_plugin_manageentities_critypes` VALUES ('2','Scheduled intervention',NULL);
INSERT INTO `glpi_plugin_manageentities_critypes` VALUES ('3','Study and advice',NULL);

### Dump table glpi_plugin_manageentities_preferences

DROP TABLE IF EXISTS `glpi_plugin_manageentities_preferences`;
CREATE TABLE `glpi_plugin_manageentities_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_users (id)',
  `show_on_load` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_manageentities_profiles

DROP TABLE IF EXISTS `glpi_plugin_manageentities_profiles`;
CREATE TABLE `glpi_plugin_manageentities_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profiles_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_profiles (id)',
  `manageentities` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cri_create` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profiles_id` (`profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_manageentities_profiles` VALUES ('1','4','w','w');

### Dump table glpi_plugin_pdf_preferences

DROP TABLE IF EXISTS `glpi_plugin_pdf_preferences`;
CREATE TABLE `glpi_plugin_pdf_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL COMMENT 'RELATION to glpi_users (id)',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'see define.php *_TYPE constant',
  `tabref` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ref of tab to display, or plugname_#, or option name',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_pdf_profiles

DROP TABLE IF EXISTS `glpi_plugin_pdf_profiles`;
CREATE TABLE `glpi_plugin_pdf_profiles` (
  `id` int(11) NOT NULL,
  `profile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `use` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_pdf_profiles` VALUES ('4','super-admin','1');

### Dump table glpi_plugin_uninstall_models

DROP TABLE IF EXISTS `glpi_plugin_uninstall_models`;
CREATE TABLE `glpi_plugin_uninstall_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `transfers_id` int(11) NOT NULL,
  `states_id` int(11) NOT NULL,
  `raz_name` int(1) NOT NULL DEFAULT '1',
  `raz_contact` int(1) NOT NULL DEFAULT '1',
  `raz_ip` int(1) NOT NULL DEFAULT '1',
  `raz_os` int(1) NOT NULL DEFAULT '1',
  `raz_domain` int(1) NOT NULL DEFAULT '1',
  `raz_network` int(1) NOT NULL DEFAULT '1',
  `raz_history` int(1) NOT NULL DEFAULT '1',
  `raz_soft_history` int(1) NOT NULL DEFAULT '1',
  `raz_budget` int(1) NOT NULL DEFAULT '1',
  `raz_user` int(1) NOT NULL DEFAULT '1',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `remove_from_ocs` int(1) NOT NULL DEFAULT '0',
  `delete_ocs_link` int(1) NOT NULL DEFAULT '0',
  `types_id` int(11) NOT NULL DEFAULT '0',
  `replace_name` tinyint(1) NOT NULL DEFAULT '0',
  `replace_serial` tinyint(1) NOT NULL DEFAULT '0',
  `replace_otherserial` tinyint(1) NOT NULL DEFAULT '0',
  `replace_documents` tinyint(1) NOT NULL DEFAULT '0',
  `replace_contracts` tinyint(1) NOT NULL DEFAULT '0',
  `replace_infocoms` tinyint(1) NOT NULL DEFAULT '0',
  `replace_reservations` tinyint(1) NOT NULL DEFAULT '0',
  `replace_users` tinyint(1) NOT NULL DEFAULT '0',
  `replace_groups` tinyint(1) NOT NULL DEFAULT '0',
  `replace_tickets` tinyint(1) NOT NULL DEFAULT '0',
  `replace_netports` tinyint(1) NOT NULL DEFAULT '0',
  `replace_direct_connections` tinyint(1) NOT NULL DEFAULT '0',
  `overwrite` tinyint(1) NOT NULL DEFAULT '0',
  `replace_method` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_uninstall_models` VALUES ('1','0','1','Uninstall','2','1','1','1','1','1','1','1','0','1','1','1','','0','1','1','1','0','0','0','0','0','0','0','0','0','0','0','0','0','2');
INSERT INTO `glpi_plugin_uninstall_models` VALUES ('2','0','1','Replace','2','0','1','1','1','1','1','1','0','1','1','1','','0','0','0','2','1','1','1','1','1','1','1','1','1','1','1','1','1','2');

### Dump table glpi_plugin_uninstall_preferences

DROP TABLE IF EXISTS `glpi_plugin_uninstall_preferences`;
CREATE TABLE `glpi_plugin_uninstall_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `entities_id` int(11) DEFAULT '0',
  `templates_id` int(11) DEFAULT '0',
  `locations_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_plugin_uninstall_profiles

DROP TABLE IF EXISTS `glpi_plugin_uninstall_profiles`;
CREATE TABLE `glpi_plugin_uninstall_profiles` (
  `id` int(11) NOT NULL DEFAULT '0',
  `profile` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `use` varchar(1) COLLATE utf8_unicode_ci DEFAULT '',
  `replace` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_uninstall_profiles` VALUES ('4','super-admin','w','1');

### Dump table glpi_plugins

DROP TABLE IF EXISTS `glpi_plugins`;
CREATE TABLE `glpi_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `directory` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0' COMMENT 'see define.php PLUGIN_* constant',
  `author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`directory`),
  KEY `state` (`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugins` VALUES ('1','uninstall','Item\'s uninstallation','2.0.2','5','Walid Nouh, François Legastelois, Remi Collet','https://forge.indepnet.net/projects/show/uninstall');
INSERT INTO `glpi_plugins` VALUES ('2','pdf','Print to pdf','0.80','5','Dévi Balpe, Remi Collet, Nelly Lasson, Walid Nouh','https://forge.indepnet.net/projects/pdf');
INSERT INTO `glpi_plugins` VALUES ('3','genericobject','Objects management','2.0','5','Alexandre Delaunay & Walid Nouh','https://forge.indepnet.net/projects/show/genericobject');
INSERT INTO `glpi_plugins` VALUES ('4','behaviors','Behaviours','0.80.0','5','Remi Collet','https://forge.indepnet.net/projects/behaviors');
INSERT INTO `glpi_plugins` VALUES ('5','manageentities','Entities portal','1.7.0','5','Xavier Caillaud','https://forge.indepnet.net/projects/show/manageentities');

### Dump table glpi_printermodels

DROP TABLE IF EXISTS `glpi_printermodels`;
CREATE TABLE `glpi_printermodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_printers

DROP TABLE IF EXISTS `glpi_printers`;
CREATE TABLE `glpi_printers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherserial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `have_serial` tinyint(1) NOT NULL DEFAULT '0',
  `have_parallel` tinyint(1) NOT NULL DEFAULT '0',
  `have_usb` tinyint(1) NOT NULL DEFAULT '0',
  `have_wifi` tinyint(1) NOT NULL DEFAULT '0',
  `have_ethernet` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `memory_size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `domains_id` int(11) NOT NULL DEFAULT '0',
  `networks_id` int(11) NOT NULL DEFAULT '0',
  `printertypes_id` int(11) NOT NULL DEFAULT '0',
  `printermodels_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `init_pages_counter` int(11) NOT NULL DEFAULT '0',
  `notepad` longtext COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `states_id` int(11) NOT NULL DEFAULT '0',
  `ticket_tco` decimal(20,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_template` (`is_template`),
  KEY `is_global` (`is_global`),
  KEY `domains_id` (`domains_id`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`),
  KEY `locations_id` (`locations_id`),
  KEY `printermodels_id` (`printermodels_id`),
  KEY `networks_id` (`networks_id`),
  KEY `states_id` (`states_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `printertypes_id` (`printertypes_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_printertypes

DROP TABLE IF EXISTS `glpi_printertypes`;
CREATE TABLE `glpi_printertypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_printertypes` VALUES ('1','Laser','');
INSERT INTO `glpi_printertypes` VALUES ('2','Ink Jet','');
INSERT INTO `glpi_printertypes` VALUES ('3','Dot Matrix','');

### Dump table glpi_profiles

DROP TABLE IF EXISTS `glpi_profiles`;
CREATE TABLE `glpi_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interface` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'helpdesk',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `computer` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monitor` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `software` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `networking` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `printer` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `peripheral` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cartridge` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `consumable` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_enterprise` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `infocom` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `knowbase` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `faq` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reservation_helpdesk` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reservation_central` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reports` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocsng` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_ocsng` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sync_ocsng` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropdown` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_dropdown` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `typedoc` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_rule_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_ocs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_ldap` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_softwarecategories` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `search_config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `search_config_global` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `check_update` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_authtype` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transfer` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reminder_public` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bookmark_public` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `backup` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delete_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `add_followups` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_add_followups` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `global_add_followups` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `global_add_tasks` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_priority` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `own_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `steal_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assign_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_all_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_assign_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_full_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observe_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_followups` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_tasks` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_planning` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_group_planning` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_all_planning` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `statistic` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_update` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `helpdesk_hardware` int(11) NOT NULL DEFAULT '0',
  `helpdesk_item_type` text COLLATE utf8_unicode_ci,
  `helpdesk_status` text COLLATE utf8_unicode_ci COMMENT 'json encoded array of from/dest allowed status change',
  `show_group_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_group_hardware` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_dictionnary_software` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_dictionnary_dropdown` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `budget` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `import_externalauth_users` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notification` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_mailcollector` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `validate_ticket` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_validation` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `calendar` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sla` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_dictionnary_printer` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clean_ocsng` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_own_followups` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delete_followups` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_helpdesk` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interface` (`interface`),
  KEY `is_default` (`is_default`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_profiles` VALUES ('1','post-only','helpdesk','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'r','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'r',NULL,NULL,'1',NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,'1','1','[\"Computer\",\"Monitor\",\"Phone\",\"Software\"]',NULL,'0','0',NULL,NULL,NULL,NULL,NULL,NULL,'2011-09-19 03:01:23',NULL,'0','0',NULL,NULL,NULL,NULL,'0',NULL,NULL);
INSERT INTO `glpi_profiles` VALUES ('2','normal','central','0','r','r','r','r','r','r','r','r','r','r','r','r','r','r','r','r','1','r','r',NULL,'r',NULL,NULL,NULL,NULL,'r','r',NULL,NULL,NULL,NULL,NULL,NULL,'w',NULL,'r',NULL,'r','r','r',NULL,NULL,NULL,NULL,NULL,NULL,'1','1','1','0','0','0','0','0','1','0','0','1','1','0','1','0','0','1','0','0','1','1','1','[\"Computer\",\"Software\",\"Phone\"]',NULL,'0','0',NULL,NULL,'r',NULL,NULL,NULL,NULL,NULL,'1','1',NULL,NULL,NULL,NULL,NULL,'0',NULL);
INSERT INTO `glpi_profiles` VALUES ('3','admin','central','0','w','w','w','w','w','w','w','w','w','w','w','w','w','w','w','w','1','w','r','w','r','w','w','w','w','w','w',NULL,NULL,NULL,NULL,NULL,NULL,'w','w','r','r','w','w','w',NULL,NULL,NULL,NULL,NULL,NULL,'1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','3','[\"Computer\",\"Software\",\"Phone\"]',NULL,'0','0',NULL,NULL,'w','w',NULL,NULL,NULL,NULL,'1','1','w',NULL,NULL,'w','1','1',NULL);
INSERT INTO `glpi_profiles` VALUES ('4','super-admin','central','0','w','w','w','w','w','w','w','w','w','w','w','w','w','w','w','w','1','w','r','w','r','w','w','w','w','w','w','w','r','w','w','w','w','w','w','r','w','w','w','w','w','w','r','w','w','w','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','3','[\"Computer\",\"Software\",\"Phone\"]',NULL,'0','0','w','w','w','w','w','w',NULL,NULL,'1','1','w','w','w','w','1','1','w');
INSERT INTO `glpi_profiles` VALUES ('5','Technician','central','0','w','w','w','w','w','w','w','w','w',NULL,NULL,NULL,'w',NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,'w',NULL,'w',NULL,NULL,'w',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'r',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0','0','0','0','1','1','1','0','0','1','1','0','0','0','0','0','0','0','1','0','0','[]','[]','0','0',NULL,NULL,NULL,NULL,'w',NULL,'2011-09-19 03:07:29','','0','0',NULL,NULL,NULL,NULL,'0','0',NULL);
INSERT INTO `glpi_profiles` VALUES ('6','ITC Director','central','0','w','w','w','w','w','w','w','w','w',NULL,'w',NULL,'w',NULL,NULL,NULL,'0',NULL,'r',NULL,NULL,NULL,'w',NULL,'w',NULL,NULL,'w',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'r','w','w',NULL,NULL,NULL,'w',NULL,NULL,NULL,NULL,'0','0','0','0','0','0','0','0','0','0','1','1','1','0','0','0','0','0','0','0','1','0','0','[]','[]','1','1',NULL,NULL,NULL,NULL,NULL,NULL,'2011-09-19 03:13:13','','0','1',NULL,NULL,NULL,NULL,'0','0','w');
INSERT INTO `glpi_profiles` VALUES ('7','Dean','helpdesk','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,'0','1','[\"Computer\",\"Monitor\",\"NetworkEquipment\",\"Peripheral\",\"Phone\",\"Printer\",\"Software\"]',NULL,'0','0',NULL,NULL,NULL,NULL,NULL,NULL,'2011-10-03 18:21:34','','0','0',NULL,NULL,NULL,NULL,'0',NULL,NULL);

### Dump table glpi_profiles_users

DROP TABLE IF EXISTS `glpi_profiles_users`;
CREATE TABLE `glpi_profiles_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `profiles_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '1',
  `is_dynamic` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `profiles_id` (`profiles_id`),
  KEY `users_id` (`users_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `is_dynamic` (`is_dynamic`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_profiles_users` VALUES ('2','2','4','0','1','0');
INSERT INTO `glpi_profiles_users` VALUES ('3','3','1','0','1','0');
INSERT INTO `glpi_profiles_users` VALUES ('4','4','4','0','1','0');
INSERT INTO `glpi_profiles_users` VALUES ('5','5','2','0','1','0');
INSERT INTO `glpi_profiles_users` VALUES ('6','6','1','0','0','1');
INSERT INTO `glpi_profiles_users` VALUES ('13','11','5','0','0','0');
INSERT INTO `glpi_profiles_users` VALUES ('9','9','1','0','0','1');
INSERT INTO `glpi_profiles_users` VALUES ('14','7','5','0','0','0');
INSERT INTO `glpi_profiles_users` VALUES ('12','8','6','0','0','0');
INSERT INTO `glpi_profiles_users` VALUES ('15','10','7','0','0','0');

### Dump table glpi_registrykeys

DROP TABLE IF EXISTS `glpi_registrykeys`;
CREATE TABLE `glpi_registrykeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `hive` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocs_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_reminders

DROP TABLE IF EXISTS `glpi_reminders`;
CREATE TABLE `glpi_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `is_private` tinyint(1) NOT NULL DEFAULT '1',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `is_planned` tinyint(1) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `is_helpdesk_visible` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `begin` (`begin`),
  KEY `end` (`end`),
  KEY `entities_id` (`entities_id`),
  KEY `users_id` (`users_id`),
  KEY `is_private` (`is_private`),
  KEY `is_recursive` (`is_recursive`),
  KEY `is_planned` (`is_planned`),
  KEY `state` (`state`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_requesttypes

DROP TABLE IF EXISTS `glpi_requesttypes`;
CREATE TABLE `glpi_requesttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_helpdesk_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_mail_default` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_helpdesk_default` (`is_helpdesk_default`),
  KEY `is_mail_default` (`is_mail_default`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_requesttypes` VALUES ('1','Helpdesk','1','0',NULL);
INSERT INTO `glpi_requesttypes` VALUES ('2','E-Mail','0','1',NULL);
INSERT INTO `glpi_requesttypes` VALUES ('3','Phone','0','0',NULL);
INSERT INTO `glpi_requesttypes` VALUES ('4','Direct','0','0',NULL);
INSERT INTO `glpi_requesttypes` VALUES ('5','Written','0','0',NULL);
INSERT INTO `glpi_requesttypes` VALUES ('6','Other','0','0',NULL);

### Dump table glpi_reservationitems

DROP TABLE IF EXISTS `glpi_reservationitems`;
CREATE TABLE `glpi_reservationitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `item` (`itemtype`,`items_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_reservations

DROP TABLE IF EXISTS `glpi_reservations`;
CREATE TABLE `glpi_reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservationitems_id` int(11) NOT NULL DEFAULT '0',
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `begin` (`begin`),
  KEY `end` (`end`),
  KEY `reservationitems_id` (`reservationitems_id`),
  KEY `users_id` (`users_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ruleactions

DROP TABLE IF EXISTS `glpi_ruleactions`;
CREATE TABLE `glpi_ruleactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `action_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'VALUE IN (assign, regex_result, append_regex_result, affectbyip, affectbyfqdn, affectbymac)',
  `field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_ruleactions` VALUES ('1','1','assign','entities_id','0');
INSERT INTO `glpi_ruleactions` VALUES ('2','2','assign','entities_id','0');
INSERT INTO `glpi_ruleactions` VALUES ('3','3','assign','entities_id','0');
INSERT INTO `glpi_ruleactions` VALUES ('4','4','assign','_refuse_email_no_response','1');
INSERT INTO `glpi_ruleactions` VALUES ('5','5','assign','_refuse_email_no_response','1');
INSERT INTO `glpi_ruleactions` VALUES ('6','6','assign','priority','5');
INSERT INTO `glpi_ruleactions` VALUES ('7','7','assign','priority','3');
INSERT INTO `glpi_ruleactions` VALUES ('8','7','assign','impact','3');

### Dump table glpi_rulecachecomputermodels

DROP TABLE IF EXISTS `glpi_rulecachecomputermodels`;
CREATE TABLE `glpi_rulecachecomputermodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachecomputertypes

DROP TABLE IF EXISTS `glpi_rulecachecomputertypes`;
CREATE TABLE `glpi_rulecachecomputertypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachemanufacturers

DROP TABLE IF EXISTS `glpi_rulecachemanufacturers`;
CREATE TABLE `glpi_rulecachemanufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachemonitormodels

DROP TABLE IF EXISTS `glpi_rulecachemonitormodels`;
CREATE TABLE `glpi_rulecachemonitormodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachemonitortypes

DROP TABLE IF EXISTS `glpi_rulecachemonitortypes`;
CREATE TABLE `glpi_rulecachemonitortypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachenetworkequipmentmodels

DROP TABLE IF EXISTS `glpi_rulecachenetworkequipmentmodels`;
CREATE TABLE `glpi_rulecachenetworkequipmentmodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachenetworkequipmenttypes

DROP TABLE IF EXISTS `glpi_rulecachenetworkequipmenttypes`;
CREATE TABLE `glpi_rulecachenetworkequipmenttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheoperatingsystems

DROP TABLE IF EXISTS `glpi_rulecacheoperatingsystems`;
CREATE TABLE `glpi_rulecacheoperatingsystems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheoperatingsystemservicepacks

DROP TABLE IF EXISTS `glpi_rulecacheoperatingsystemservicepacks`;
CREATE TABLE `glpi_rulecacheoperatingsystemservicepacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheoperatingsystemversions

DROP TABLE IF EXISTS `glpi_rulecacheoperatingsystemversions`;
CREATE TABLE `glpi_rulecacheoperatingsystemversions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheperipheralmodels

DROP TABLE IF EXISTS `glpi_rulecacheperipheralmodels`;
CREATE TABLE `glpi_rulecacheperipheralmodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheperipheraltypes

DROP TABLE IF EXISTS `glpi_rulecacheperipheraltypes`;
CREATE TABLE `glpi_rulecacheperipheraltypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachephonemodels

DROP TABLE IF EXISTS `glpi_rulecachephonemodels`;
CREATE TABLE `glpi_rulecachephonemodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachephonetypes

DROP TABLE IF EXISTS `glpi_rulecachephonetypes`;
CREATE TABLE `glpi_rulecachephonetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheprintermodels

DROP TABLE IF EXISTS `glpi_rulecacheprintermodels`;
CREATE TABLE `glpi_rulecacheprintermodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheprinters

DROP TABLE IF EXISTS `glpi_rulecacheprinters`;
CREATE TABLE `glpi_rulecacheprinters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_manufacturer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ignore_ocs_import` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_global` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecacheprintertypes

DROP TABLE IF EXISTS `glpi_rulecacheprintertypes`;
CREATE TABLE `glpi_rulecacheprintertypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecachesoftwares

DROP TABLE IF EXISTS `glpi_rulecachesoftwares`;
CREATE TABLE `glpi_rulecachesoftwares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_manufacturer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ignore_ocs_import` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_helpdesk_visible` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_value` (`old_value`),
  KEY `rules_id` (`rules_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_rulecriterias

DROP TABLE IF EXISTS `glpi_rulecriterias`;
CREATE TABLE `glpi_rulecriterias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rules_id` int(11) NOT NULL DEFAULT '0',
  `criteria` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` int(11) NOT NULL DEFAULT '0' COMMENT 'see define.php PATTERN_* and REGEX_* constant',
  `pattern` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rules_id` (`rules_id`),
  KEY `condition` (`condition`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_rulecriterias` VALUES ('1','1','TAG','0','*');
INSERT INTO `glpi_rulecriterias` VALUES ('2','2','uid','0','*');
INSERT INTO `glpi_rulecriterias` VALUES ('3','2','samaccountname','0','*');
INSERT INTO `glpi_rulecriterias` VALUES ('4','2','MAIL_EMAIL','0','*');
INSERT INTO `glpi_rulecriterias` VALUES ('5','3','subject','6','/.*/');
INSERT INTO `glpi_rulecriterias` VALUES ('6','4','x-auto-response-suppress','6','/\\S+/');
INSERT INTO `glpi_rulecriterias` VALUES ('7','5','auto-submitted','6','/\\S+/');
INSERT INTO `glpi_rulecriterias` VALUES ('8','5','auto-submitted','1','no');
INSERT INTO `glpi_rulecriterias` VALUES ('9','6','urgency','0','5');
INSERT INTO `glpi_rulecriterias` VALUES ('10','6','impact','0','5');
INSERT INTO `glpi_rulecriterias` VALUES ('11','7','priority','0','3');

### Dump table glpi_rulerightparameters

DROP TABLE IF EXISTS `glpi_rulerightparameters`;
CREATE TABLE `glpi_rulerightparameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_rulerightparameters` VALUES ('1','(LDAP)Organization','o','');
INSERT INTO `glpi_rulerightparameters` VALUES ('2','(LDAP)Common Name','cn','');
INSERT INTO `glpi_rulerightparameters` VALUES ('3','(LDAP)Department Number','departmentnumber','');
INSERT INTO `glpi_rulerightparameters` VALUES ('4','(LDAP)Email','mail','');
INSERT INTO `glpi_rulerightparameters` VALUES ('5','Object Class','objectclass','');
INSERT INTO `glpi_rulerightparameters` VALUES ('6','(LDAP)User ID','uid','');
INSERT INTO `glpi_rulerightparameters` VALUES ('7','(LDAP)Telephone Number','phone','');
INSERT INTO `glpi_rulerightparameters` VALUES ('8','(LDAP)Employee Number','employeenumber','');
INSERT INTO `glpi_rulerightparameters` VALUES ('9','(LDAP)Manager','manager','');
INSERT INTO `glpi_rulerightparameters` VALUES ('10','(LDAP)DistinguishedName','dn','');
INSERT INTO `glpi_rulerightparameters` VALUES ('12','(AD)User ID','samaccountname','');
INSERT INTO `glpi_rulerightparameters` VALUES ('13','(LDAP) Title','title','');

### Dump table glpi_rules

DROP TABLE IF EXISTS `glpi_rules`;
CREATE TABLE `glpi_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `sub_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ranking` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `match` char(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'see define.php *_MATCHING constant',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `comment` text COLLATE utf8_unicode_ci,
  `date_mod` datetime DEFAULT NULL,
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_active` (`is_active`),
  KEY `sub_type` (`sub_type`),
  KEY `date_mod` (`date_mod`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_rules` VALUES ('1','0','RuleOcs','1','Root','','AND','1',NULL,NULL,'0');
INSERT INTO `glpi_rules` VALUES ('2','0','RuleRight','1','Root','','OR','1',NULL,NULL,'0');
INSERT INTO `glpi_rules` VALUES ('3','0','RuleMailCollector','3','Root','','OR','1',NULL,NULL,'0');
INSERT INTO `glpi_rules` VALUES ('4','0','RuleMailCollector','1','Auto-Reply X-Auto-Response-Suppress','Exclude Auto-Reply emails using X-Auto-Response-Suppress header','AND','1',NULL,'2011-01-18 11:40:42','1');
INSERT INTO `glpi_rules` VALUES ('5','0','RuleMailCollector','2','Auto-Reply Auto-Submitted','Exclude Auto-Reply emails using Auto-Submitted header','AND','1',NULL,'2011-01-18 11:40:42','1');
INSERT INTO `glpi_rules` VALUES ('6','0','RuleTicket','1','Dean Priority','','AND','1','','2011-10-03 18:18:33','0');
INSERT INTO `glpi_rules` VALUES ('7','0','RuleTicket','2','Faculty Medium Priority','','AND','1','','2011-10-03 18:19:20','0');

### Dump table glpi_slalevelactions

DROP TABLE IF EXISTS `glpi_slalevelactions`;
CREATE TABLE `glpi_slalevelactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slalevels_id` int(11) NOT NULL DEFAULT '0',
  `action_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slalevels_id` (`slalevels_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_slalevels

DROP TABLE IF EXISTS `glpi_slalevels`;
CREATE TABLE `glpi_slalevels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slas_id` int(11) NOT NULL DEFAULT '0',
  `execution_time` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_active` (`is_active`),
  KEY `slas_id` (`slas_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_slalevels_tickets

DROP TABLE IF EXISTS `glpi_slalevels_tickets`;
CREATE TABLE `glpi_slalevels_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `slalevels_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_id` (`tickets_id`),
  KEY `slalevels_id` (`slalevels_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_slas

DROP TABLE IF EXISTS `glpi_slas`;
CREATE TABLE `glpi_slas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `resolution_time` int(11) NOT NULL,
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `calendars_id` (`calendars_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_softwarecategories

DROP TABLE IF EXISTS `glpi_softwarecategories`;
CREATE TABLE `glpi_softwarecategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_softwarecategories` VALUES ('1','FUSION',NULL);

### Dump table glpi_softwarelicenses

DROP TABLE IF EXISTS `glpi_softwarelicenses`;
CREATE TABLE `glpi_softwarelicenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `softwares_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `number` int(11) NOT NULL DEFAULT '0',
  `softwarelicensetypes_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherserial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `softwareversions_id_buy` int(11) NOT NULL DEFAULT '0',
  `softwareversions_id_use` int(11) NOT NULL DEFAULT '0',
  `expire` date DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `serial` (`serial`),
  KEY `otherserial` (`otherserial`),
  KEY `expire` (`expire`),
  KEY `softwareversions_id_buy` (`softwareversions_id_buy`),
  KEY `entities_id` (`entities_id`),
  KEY `softwares_id` (`softwares_id`),
  KEY `softwarelicensetypes_id` (`softwarelicensetypes_id`),
  KEY `softwareversions_id_use` (`softwareversions_id_use`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_softwarelicensetypes

DROP TABLE IF EXISTS `glpi_softwarelicensetypes`;
CREATE TABLE `glpi_softwarelicensetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_softwarelicensetypes` VALUES ('1','OEM','');

### Dump table glpi_softwares

DROP TABLE IF EXISTS `glpi_softwares`;
CREATE TABLE `glpi_softwares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `users_id_tech` int(11) NOT NULL DEFAULT '0',
  `is_update` tinyint(1) NOT NULL DEFAULT '0',
  `softwares_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `ticket_tco` decimal(20,4) DEFAULT '0.0000',
  `is_helpdesk_visible` tinyint(1) NOT NULL DEFAULT '1',
  `softwarecategories_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`),
  KEY `name` (`name`),
  KEY `is_template` (`is_template`),
  KEY `is_update` (`is_update`),
  KEY `softwarecategories_id` (`softwarecategories_id`),
  KEY `entities_id` (`entities_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`),
  KEY `locations_id` (`locations_id`),
  KEY `users_id_tech` (`users_id_tech`),
  KEY `softwares_id` (`softwares_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `is_helpdesk_visible` (`is_helpdesk_visible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_softwareversions

DROP TABLE IF EXISTS `glpi_softwareversions`;
CREATE TABLE `glpi_softwareversions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `softwares_id` int(11) NOT NULL DEFAULT '0',
  `states_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `operatingsystems_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `softwares_id` (`softwares_id`),
  KEY `states_id` (`states_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `operatingsystems_id` (`operatingsystems_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_states

DROP TABLE IF EXISTS `glpi_states`;
CREATE TABLE `glpi_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_states` VALUES ('1','Stored','');
INSERT INTO `glpi_states` VALUES ('2','Deployed','');
INSERT INTO `glpi_states` VALUES ('3','Decomissioned','');

### Dump table glpi_suppliers

DROP TABLE IF EXISTS `glpi_suppliers`;
CREATE TABLE `glpi_suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `suppliertypes_id` int(11) NOT NULL DEFAULT '0',
  `address` text COLLATE utf8_unicode_ci,
  `postcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notepad` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `entities_id` (`entities_id`),
  KEY `suppliertypes_id` (`suppliertypes_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_suppliers` VALUES ('1','0','0','ACER SUPPLIER','0','','','','','','','','','0','','',NULL);

### Dump table glpi_suppliertypes

DROP TABLE IF EXISTS `glpi_suppliertypes`;
CREATE TABLE `glpi_suppliertypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_taskcategories

DROP TABLE IF EXISTS `glpi_taskcategories`;
CREATE TABLE `glpi_taskcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `taskcategories_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `completename` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  `level` int(11) NOT NULL DEFAULT '0',
  `ancestors_cache` longtext COLLATE utf8_unicode_ci,
  `sons_cache` longtext COLLATE utf8_unicode_ci,
  `is_helpdeskvisible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `taskcategories_id` (`taskcategories_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `is_helpdeskvisible` (`is_helpdeskvisible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ticketcategories

DROP TABLE IF EXISTS `glpi_ticketcategories`;
CREATE TABLE `glpi_ticketcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `ticketcategories_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `completename` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  `level` int(11) NOT NULL DEFAULT '0',
  `knowbaseitemcategories_id` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `ancestors_cache` longtext COLLATE utf8_unicode_ci,
  `sons_cache` longtext COLLATE utf8_unicode_ci,
  `is_helpdeskvisible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `ticketcategories_id` (`ticketcategories_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `knowbaseitemcategories_id` (`knowbaseitemcategories_id`),
  KEY `users_id` (`users_id`),
  KEY `groups_id` (`groups_id`),
  KEY `is_helpdeskvisible` (`is_helpdeskvisible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ticketfollowups

DROP TABLE IF EXISTS `glpi_ticketfollowups`;
CREATE TABLE `glpi_ticketfollowups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `content` longtext COLLATE utf8_unicode_ci,
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `requesttypes_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `users_id` (`users_id`),
  KEY `tickets_id` (`tickets_id`),
  KEY `is_private` (`is_private`),
  KEY `requesttypes_id` (`requesttypes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ticketplannings

DROP TABLE IF EXISTS `glpi_ticketplannings`;
CREATE TABLE `glpi_ticketplannings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickettasks_id` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `begin` (`begin`),
  KEY `end` (`end`),
  KEY `users_id` (`users_id`),
  KEY `ticketfollowups_id` (`tickettasks_id`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_tickets

DROP TABLE IF EXISTS `glpi_tickets`;
CREATE TABLE `glpi_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `closedate` datetime DEFAULT NULL,
  `solvedate` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `users_id_lastupdater` int(11) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'new',
  `users_id_recipient` int(11) NOT NULL DEFAULT '0',
  `requesttypes_id` int(11) NOT NULL DEFAULT '0',
  `suppliers_id_assign` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `content` longtext COLLATE utf8_unicode_ci,
  `urgency` int(11) NOT NULL DEFAULT '1',
  `impact` int(11) NOT NULL DEFAULT '1',
  `priority` int(11) NOT NULL DEFAULT '1',
  `ticketcategories_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '1',
  `cost_time` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `cost_fixed` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `cost_material` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `ticketsolutiontypes_id` int(11) NOT NULL DEFAULT '0',
  `solution` text COLLATE utf8_unicode_ci,
  `global_validation` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'none',
  `slas_id` int(11) NOT NULL DEFAULT '0',
  `slalevels_id` int(11) NOT NULL DEFAULT '0',
  `due_date` datetime DEFAULT NULL,
  `begin_waiting_date` datetime DEFAULT NULL,
  `sla_waiting_duration` int(11) NOT NULL DEFAULT '0',
  `ticket_waiting_duration` int(11) NOT NULL DEFAULT '0',
  `close_delay_stat` int(11) NOT NULL DEFAULT '0',
  `solve_delay_stat` int(11) NOT NULL DEFAULT '0',
  `takeintoaccount_delay_stat` int(11) NOT NULL DEFAULT '0',
  `actiontime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `closedate` (`closedate`),
  KEY `status` (`status`),
  KEY `priority` (`priority`),
  KEY `request_type` (`requesttypes_id`),
  KEY `date_mod` (`date_mod`),
  KEY `suppliers_id_assign` (`suppliers_id_assign`),
  KEY `ticketcategories_id` (`ticketcategories_id`),
  KEY `entities_id` (`entities_id`),
  KEY `users_id_recipient` (`users_id_recipient`),
  KEY `item` (`itemtype`,`items_id`),
  KEY `solvedate` (`solvedate`),
  KEY `ticketsolutiontypes_id` (`ticketsolutiontypes_id`),
  KEY `urgency` (`urgency`),
  KEY `impact` (`impact`),
  KEY `global_validation` (`global_validation`),
  KEY `slas_id` (`slas_id`),
  KEY `slalevels_id` (`slalevels_id`),
  KEY `due_date` (`due_date`),
  KEY `users_id_lastupdater` (`users_id_lastupdater`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_tickets` VALUES ('1','0','Blue Screen','2011-09-19 03:56:27',NULL,NULL,'2011-09-19 04:00:30','2','assign','6','1','0','Computer','3','Computer keeps showing blue screen whenever i place a cd','3','3','3','0','1','0.0000','0.0000','0.0000','0',NULL,'none','0','0',NULL,NULL,'0','0','0','0','243','0');
INSERT INTO `glpi_tickets` VALUES ('4','0','test','2011-10-03 18:22:50',NULL,NULL,'2011-10-03 18:22:50','10','new','10','1','0','Computer','5','test','5','3','4','0','1','0.0000','0.0000','0.0000','0',NULL,'none','0','0',NULL,NULL,'0','0','0','0','0','0');
INSERT INTO `glpi_tickets` VALUES ('5','0','test','2011-10-03 18:23:35',NULL,NULL,'2011-10-03 18:23:35','6','new','6','1','0','Computer','3','test','3','3','3','0','1','0.0000','0.0000','0.0000','0',NULL,'none','0','0',NULL,NULL,'0','0','0','0','0','0');

### Dump table glpi_tickets_tickets

DROP TABLE IF EXISTS `glpi_tickets_tickets`;
CREATE TABLE `glpi_tickets_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id_1` int(11) NOT NULL DEFAULT '0',
  `tickets_id_2` int(11) NOT NULL DEFAULT '0',
  `link` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `unicity` (`tickets_id_1`,`tickets_id_2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_tickets_users

DROP TABLE IF EXISTS `glpi_tickets_users`;
CREATE TABLE `glpi_tickets_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '1',
  `use_notification` tinyint(1) NOT NULL DEFAULT '0',
  `alternative_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`tickets_id`,`type`,`users_id`,`alternative_email`),
  KEY `user` (`users_id`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_tickets_users` VALUES ('1','1','6','1','0',NULL);
INSERT INTO `glpi_tickets_users` VALUES ('2','1','11','2','0',NULL);
INSERT INTO `glpi_tickets_users` VALUES ('5','4','10','1','0',NULL);
INSERT INTO `glpi_tickets_users` VALUES ('6','5','6','1','0',NULL);

### Dump table glpi_ticketsatisfactions

DROP TABLE IF EXISTS `glpi_ticketsatisfactions`;
CREATE TABLE `glpi_ticketsatisfactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '1',
  `date_begin` datetime DEFAULT NULL,
  `date_answered` datetime DEFAULT NULL,
  `satisfaction` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tickets_id` (`tickets_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ticketsolutiontemplates

DROP TABLE IF EXISTS `glpi_ticketsolutiontemplates`;
CREATE TABLE `glpi_ticketsolutiontemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `ticketsolutiontypes_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`entities_id`,`name`),
  KEY `name` (`name`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ticketsolutiontypes

DROP TABLE IF EXISTS `glpi_ticketsolutiontypes`;
CREATE TABLE `glpi_ticketsolutiontypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_tickettasks

DROP TABLE IF EXISTS `glpi_tickettasks`;
CREATE TABLE `glpi_tickettasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `taskcategories_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `content` longtext COLLATE utf8_unicode_ci,
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `actiontime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `users_id` (`users_id`),
  KEY `tickets_id` (`tickets_id`),
  KEY `is_private` (`is_private`),
  KEY `taskcategories_id` (`taskcategories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_ticketvalidations

DROP TABLE IF EXISTS `glpi_ticketvalidations`;
CREATE TABLE `glpi_ticketvalidations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  `tickets_id` int(11) NOT NULL DEFAULT '0',
  `users_id_validate` int(11) NOT NULL DEFAULT '0',
  `comment_submission` text COLLATE utf8_unicode_ci,
  `comment_validation` text COLLATE utf8_unicode_ci,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'waiting',
  `submission_date` datetime DEFAULT NULL,
  `validation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `users_id` (`users_id`),
  KEY `users_id_validate` (`users_id_validate`),
  KEY `tickets_id` (`tickets_id`),
  KEY `submission_date` (`submission_date`),
  KEY `validation_date` (`validation_date`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_transfers

DROP TABLE IF EXISTS `glpi_transfers`;
CREATE TABLE `glpi_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keep_ticket` int(11) NOT NULL DEFAULT '0',
  `keep_networklink` int(11) NOT NULL DEFAULT '0',
  `keep_reservation` int(11) NOT NULL DEFAULT '0',
  `keep_history` int(11) NOT NULL DEFAULT '0',
  `keep_device` int(11) NOT NULL DEFAULT '0',
  `keep_infocom` int(11) NOT NULL DEFAULT '0',
  `keep_dc_monitor` int(11) NOT NULL DEFAULT '0',
  `clean_dc_monitor` int(11) NOT NULL DEFAULT '0',
  `keep_dc_phone` int(11) NOT NULL DEFAULT '0',
  `clean_dc_phone` int(11) NOT NULL DEFAULT '0',
  `keep_dc_peripheral` int(11) NOT NULL DEFAULT '0',
  `clean_dc_peripheral` int(11) NOT NULL DEFAULT '0',
  `keep_dc_printer` int(11) NOT NULL DEFAULT '0',
  `clean_dc_printer` int(11) NOT NULL DEFAULT '0',
  `keep_supplier` int(11) NOT NULL DEFAULT '0',
  `clean_supplier` int(11) NOT NULL DEFAULT '0',
  `keep_contact` int(11) NOT NULL DEFAULT '0',
  `clean_contact` int(11) NOT NULL DEFAULT '0',
  `keep_contract` int(11) NOT NULL DEFAULT '0',
  `clean_contract` int(11) NOT NULL DEFAULT '0',
  `keep_software` int(11) NOT NULL DEFAULT '0',
  `clean_software` int(11) NOT NULL DEFAULT '0',
  `keep_document` int(11) NOT NULL DEFAULT '0',
  `clean_document` int(11) NOT NULL DEFAULT '0',
  `keep_cartridgeitem` int(11) NOT NULL DEFAULT '0',
  `clean_cartridgeitem` int(11) NOT NULL DEFAULT '0',
  `keep_cartridge` int(11) NOT NULL DEFAULT '0',
  `keep_consumable` int(11) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `keep_disk` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_transfers` VALUES ('1','complete','2','2','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1',NULL,NULL,'1');
INSERT INTO `glpi_transfers` VALUES ('2','plugin_uninstall','0','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','2011-09-23 10:06:49',NULL,'0');

### Dump table glpi_usercategories

DROP TABLE IF EXISTS `glpi_usercategories`;
CREATE TABLE `glpi_usercategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_usercategories` VALUES ('8','Pre-School','under Integrated School Department');
INSERT INTO `glpi_usercategories` VALUES ('4','Lasallian Mission Office','under the Office of the President');
INSERT INTO `glpi_usercategories` VALUES ('5','Development, Linkages and Special Projects','under the Office of the President');
INSERT INTO `glpi_usercategories` VALUES ('6','Campus Grounds Development','under the Office of the President');
INSERT INTO `glpi_usercategories` VALUES ('7','Information Technology Center','');
INSERT INTO `glpi_usercategories` VALUES ('9','Learning Center 1','under Integrated School Department');
INSERT INTO `glpi_usercategories` VALUES ('10','Learning Center 2','under Integrated School Department');
INSERT INTO `glpi_usercategories` VALUES ('11','School of Engineering','under College Department');
INSERT INTO `glpi_usercategories` VALUES ('12','School of Information and Computing','under College Department');
INSERT INTO `glpi_usercategories` VALUES ('13','School of Information and Communication Studies','under College Department');
INSERT INTO `glpi_usercategories` VALUES ('14','School of Management and Entrepreneurship','under College Department');
INSERT INTO `glpi_usercategories` VALUES ('15','College of Arts and Sciences','under College Department');
INSERT INTO `glpi_usercategories` VALUES ('16','Office of Student Activities','under College Department');
INSERT INTO `glpi_usercategories` VALUES ('17','Admissions Office','under Office of Academic Services');
INSERT INTO `glpi_usercategories` VALUES ('18','Libraries','under Office of Academic Services');
INSERT INTO `glpi_usercategories` VALUES ('19','Registrar\'s Office','under Office of Academic Services');
INSERT INTO `glpi_usercategories` VALUES ('20','Student and Financial Assistance','under Office of Academic Services');
INSERT INTO `glpi_usercategories` VALUES ('21','Physical Facilities Office','under Administrative Services');
INSERT INTO `glpi_usercategories` VALUES ('22','Logistics','under Administrative Services');
INSERT INTO `glpi_usercategories` VALUES ('23','Housekeeping','under Administrative Services');
INSERT INTO `glpi_usercategories` VALUES ('24','Safety and Security','under Administrative Services');
INSERT INTO `glpi_usercategories` VALUES ('25','Purchasing','under Administrative Services');
INSERT INTO `glpi_usercategories` VALUES ('26','Warehouse','under Administrative Services');
INSERT INTO `glpi_usercategories` VALUES ('27','Clinic','under Administrative Services');
INSERT INTO `glpi_usercategories` VALUES ('28','Disbursement','under Office of the Controller');
INSERT INTO `glpi_usercategories` VALUES ('29','Bookkeeping','under Office of the Controller');
INSERT INTO `glpi_usercategories` VALUES ('30','Cashier','under Office of the Controller');
INSERT INTO `glpi_usercategories` VALUES ('31','Student Accounts','under Office of the Controller');
INSERT INTO `glpi_usercategories` VALUES ('32','Office of Sports Development','');
INSERT INTO `glpi_usercategories` VALUES ('33','Human Resource Department','');

### Dump table glpi_users

DROP TABLE IF EXISTS `glpi_users`;
CREATE TABLE `glpi_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `realname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locations_id` int(11) NOT NULL DEFAULT '0',
  `language` char(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'see define.php CFG_GLPI[language] array',
  `use_mode` int(11) NOT NULL DEFAULT '0',
  `list_limit` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `comment` text COLLATE utf8_unicode_ci,
  `auths_id` int(11) NOT NULL DEFAULT '0',
  `authtype` int(11) NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `date_sync` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `profiles_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `usertitles_id` int(11) NOT NULL DEFAULT '0',
  `usercategories_id` int(11) NOT NULL DEFAULT '0',
  `date_format` int(11) DEFAULT NULL,
  `number_format` int(11) DEFAULT NULL,
  `names_format` int(11) DEFAULT NULL,
  `csv_delimiter` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_ids_visible` tinyint(1) DEFAULT NULL,
  `dropdown_chars_limit` int(11) DEFAULT NULL,
  `use_flat_dropdowntree` tinyint(1) DEFAULT NULL,
  `show_jobs_at_login` tinyint(1) DEFAULT NULL,
  `priority_1` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority_2` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority_3` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority_4` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority_5` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority_6` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_categorized_soft_expanded` tinyint(1) DEFAULT NULL,
  `is_not_categorized_soft_expanded` tinyint(1) DEFAULT NULL,
  `followup_private` tinyint(1) DEFAULT NULL,
  `task_private` tinyint(1) DEFAULT NULL,
  `default_requesttypes_id` int(11) DEFAULT NULL,
  `token` char(40) COLLATE utf8_unicode_ci DEFAULT '',
  `tokendate` datetime DEFAULT NULL,
  `user_dn` text COLLATE utf8_unicode_ci,
  `registration_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`name`),
  KEY `firstname` (`firstname`),
  KEY `realname` (`realname`),
  KEY `entities_id` (`entities_id`),
  KEY `profiles_id` (`profiles_id`),
  KEY `locations_id` (`locations_id`),
  KEY `usertitles_id` (`usertitles_id`),
  KEY `usercategories_id` (`usercategories_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `is_active` (`is_active`),
  KEY `date_mod` (`date_mod`),
  KEY `authitem` (`authtype`,`auths_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_users` VALUES ('2','glpi','0915bd0a5c6e56d8f38ca2b390857d4949073f41','','','','','',NULL,'0',NULL,'0','20','1',NULL,'0','1','2011-10-03 18:41:59','2011-10-01 09:40:19',NULL,'0','0','0','0','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('3','post-only','98ab18368759abb4a7fd8ef41083b68843fc285b','','','','','',NULL,'0',NULL,'0','20','1',NULL,'0','1','2011-10-03 11:51:45','2011-09-29 10:17:09',NULL,'0','0','0','0','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('4','tech','d9f9133fb120cd6096870bc2b496805b','','','','','',NULL,'0',NULL,'0','20','1',NULL,'0','0',NULL,NULL,NULL,'0','0','0','0','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('5','normal','9c2a6e4809aeef7b7712ca4db05a681452f4f748','','','','','',NULL,'0',NULL,'0','20','1',NULL,'0','1','2011-10-03 11:52:08','2011-09-29 10:15:38',NULL,'0','0','0','0','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('6','10725334','6d7565972643a64c5fc76c89ce5cacc95a193a7c','tj.ching@gmail.com','','100','+639064208825','Ching','Tristan','0',NULL,'0',NULL,'1',NULL,'0','1','2011-10-03 18:23:30','2011-09-18 11:56:05',NULL,'0','0','0','28','14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('7','10742158','bf04650e868cfbe03013fcde4e007c999bb10374','yap.clifford@gmail.com','','101','+639276009999','Yap','Clifford','0',NULL,'0',NULL,'1',NULL,'0','1','2011-10-03 18:33:29','2011-09-18 11:58:38',NULL,'0','0','0','4','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('8','10706062','c89eda8a82ab6e653eaba68ffcf2116c56fd4c6d','ardowz@gmail.com','','101','+639175372727','Avancena','Bernardo','0',NULL,'0',NULL,'1',NULL,'0','1','2011-09-19 03:58:27','2011-09-18 11:59:51',NULL,'0','0','0','11','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('10','10000000','1c21da7003612cc7981a92687180e8955ccca8c3','lissa.magpantay@delasalle.ph','','102','+639189026703','Magpantay','Lissa','0',NULL,'0',NULL,'1',NULL,'0','1','2011-10-03 18:22:43','2011-09-18 12:12:29',NULL,'0','0','0','2','12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);
INSERT INTO `glpi_users` VALUES ('11','10721827','a027184a55211cd23e3f3094f1fdc728df5e0500','geronimo.paul@gmail.com','','101','+639175137285','Geronimo','Paul','0',NULL,'0',NULL,'1',NULL,'0','1','2011-09-19 04:15:33','2011-09-18 12:13:16',NULL,'0','0','0','4','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL);

### Dump table glpi_usertitles

DROP TABLE IF EXISTS `glpi_usertitles`;
CREATE TABLE `glpi_usertitles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_usertitles` VALUES ('1','Secretary','');
INSERT INTO `glpi_usertitles` VALUES ('2','Dean','');
INSERT INTO `glpi_usertitles` VALUES ('3','Vice Dean','');
INSERT INTO `glpi_usertitles` VALUES ('4','Technician','');
INSERT INTO `glpi_usertitles` VALUES ('5','Director','');
INSERT INTO `glpi_usertitles` VALUES ('6','Counselor','');
INSERT INTO `glpi_usertitles` VALUES ('7','Library & AV Support Staff','');
INSERT INTO `glpi_usertitles` VALUES ('8','President','');
INSERT INTO `glpi_usertitles` VALUES ('9','Chancellor','');
INSERT INTO `glpi_usertitles` VALUES ('10','Compliance Officer','');
INSERT INTO `glpi_usertitles` VALUES ('11','Information Technology Center Director','');
INSERT INTO `glpi_usertitles` VALUES ('12','Executive Secretary','');
INSERT INTO `glpi_usertitles` VALUES ('13','Controller','');
INSERT INTO `glpi_usertitles` VALUES ('14','Marketing and Communications Officer','');
INSERT INTO `glpi_usertitles` VALUES ('15',' Development, External Relations and Special Projects Officer','');
INSERT INTO `glpi_usertitles` VALUES ('16','Associate Vice-Chancellor  for Academics Services','');
INSERT INTO `glpi_usertitles` VALUES ('17','Principal','');
INSERT INTO `glpi_usertitles` VALUES ('18','Associate Vice-Chancellor  for Lasallian Mission','');
INSERT INTO `glpi_usertitles` VALUES ('19',' Associate Vice-Chancellor  for Administration','');
INSERT INTO `glpi_usertitles` VALUES ('20','Administrative Assistant','');
INSERT INTO `glpi_usertitles` VALUES ('21','Associate Principal  for Student Activities','');
INSERT INTO `glpi_usertitles` VALUES ('22',' Associate Principal  for Academic','');
INSERT INTO `glpi_usertitles` VALUES ('23','Library','');
INSERT INTO `glpi_usertitles` VALUES ('24','ITEO','');
INSERT INTO `glpi_usertitles` VALUES ('25','STUFAP','');
INSERT INTO `glpi_usertitles` VALUES ('26','LPO','');
INSERT INTO `glpi_usertitles` VALUES ('27','Purchasing','');
INSERT INTO `glpi_usertitles` VALUES ('28','Faculty','');
INSERT INTO `glpi_usertitles` VALUES ('29','Office of Sports Development','');
INSERT INTO `glpi_usertitles` VALUES ('30','Registrar','');
INSERT INTO `glpi_usertitles` VALUES ('31','Admissions','');
INSERT INTO `glpi_usertitles` VALUES ('32','Office of Student  Affairs / Activities','');

### Dump table glpi_virtualmachinestates

DROP TABLE IF EXISTS `glpi_virtualmachinestates`;
CREATE TABLE `glpi_virtualmachinestates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_virtualmachinesystems

DROP TABLE IF EXISTS `glpi_virtualmachinesystems`;
CREATE TABLE `glpi_virtualmachinesystems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_virtualmachinetypes

DROP TABLE IF EXISTS `glpi_virtualmachinetypes`;
CREATE TABLE `glpi_virtualmachinetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


### Dump table glpi_vlans

DROP TABLE IF EXISTS `glpi_vlans`;
CREATE TABLE `glpi_vlans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

