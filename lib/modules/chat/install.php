<?php
	$sql = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
	$sql->query('SET NAMES '.SQL_ENCODE);
	
	$sql->query("
		CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."chat` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`userid` int(11) NOT NULL,
			`msg` text COLLATE utf8_unicode_ci NOT NULL,
			`attachments` text COLLATE utf8_unicode_ci,
			`sendTime` int(11) NOT NULL,
			`deleted` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
	");
	
	$iSuccess = true
?>