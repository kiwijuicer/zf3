SELECT * FROM zf2.admiCREATE TABLE `admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(512) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `last_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `role` enum('user','admin') COLLATE utf8_bin NOT NULL DEFAULT 'user',
  `status` enum('enabled','disabled') COLLATE utf8_bin NOT NULL DEFAULT 'enabled',
  `fb_id` bigint(20) DEFAULT NULL,
  `fb_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `fb_is_active` bit(1) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uq_usernameemailfbid` (`fb_id`,`email`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;COLLATE=utf8_bin;