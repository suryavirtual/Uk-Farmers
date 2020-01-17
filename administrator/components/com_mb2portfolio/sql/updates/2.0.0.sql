ALTER TABLE `#__mb2portfolio` ADD `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__mb2portfolio` ADD `modified_by` int(11) NOT NULL;
ALTER TABLE `#__mb2portfolio` ADD `links` text NOT NULL;
ALTER TABLE `#__mb2portfolio` ADD `extra_fields` text NOT NULL;
ALTER TABLE `#__mb2portfolio` ADD `access` int(10) NOT NULL;
ALTER TABLE `#__mb2portfolio` DROP `slider`;
ALTER TABLE `#__mb2portfolio_skills` ADD `access` int(10) NOT NULL;
ALTER TABLE `#__mb2portfolio_skills` ADD `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__mb2portfolio_skills` ADD `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__mb2portfolio_skills` ADD `modified_by` int(11) NOT NULL;


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