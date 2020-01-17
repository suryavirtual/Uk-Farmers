CREATE TABLE IF NOT EXISTS `#__mb2portfolio` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`alias` varchar(255) NOT NULL,
`state` tinyint(1) NOT NULL DEFAULT '1',
`skill_1` int(11) NOT NULL,
`skill_2` int(11) NOT NULL,
`skill_3` int(11) NOT NULL,
`skill_4` int(11) NOT NULL,
`skill_5` int(11) NOT NULL,
`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` int(11) NOT NULL,
`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified_by` int(11) NOT NULL,
`access` int(10) NOT NULL,
`ordering` int(11) NOT NULL,
`hits` int(10) NOT NULL,
`images` text NOT NULL,
`intro_text` text NOT NULL,
`full_text` text NOT NULL,
`layout` varchar(255) NOT NULL,
`media_width` varchar(255) NOT NULL,
`title_link` varchar(255) NOT NULL,
`video` text NOT NULL,
`links` text NOT NULL,
`extra_fields` text NOT NULL,
`language` char(7) NOT NULL,
`metadata` text NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;



CREATE TABLE IF NOT EXISTS `#__mb2portfolio_extra_fields` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`alias` varchar(255) NOT NULL,
`access` int(10) NOT NULL DEFAULT '0',
`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` int(11) NOT NULL,
`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified_by` int(11) NOT NULL,
`ordering` int(11) NOT NULL,
`state` tinyint(1) NOT NULL,
`checked_out` int(11) NOT NULL,
`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`language` char(7) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;



CREATE TABLE IF NOT EXISTS `#__mb2portfolio_skills` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255)  NOT NULL ,
`alias` varchar(255)  NOT NULL ,
`image` varchar(255)  NOT NULL ,
`description` text NOT NULL ,
`ordering` int(11)  NOT NULL ,
`state` tinyint(1)  NOT NULL DEFAULT '1',
`access` int(10) NOT NULL,
`checked_out` int(11)  NOT NULL ,
`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` int(11) NOT NULL,
`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified_by` int(11) NOT NULL,
`language` char(7) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;