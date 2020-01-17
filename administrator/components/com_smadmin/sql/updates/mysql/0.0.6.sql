DROP TABLE IF EXISTS `#__smadmin`;

CREATE TABLE `#__smadmin` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`greeting` VARCHAR(25) NOT NULL,
	`published` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8;

INSERT INTO `#__smadmin` (`greeting`) VALUES
('Supplier/Member Admin Panel!'),
('Good bye Supplier/Member Admin!');
