DROP TABLE IF EXISTS fos_user;
CREATE TABLE fos_user (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  username_canonical varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  email varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  email_canonical varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  enabled tinyint(1) NOT NULL,
  salt varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  password varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  last_login datetime DEFAULT NULL,
  locked tinyint(1) NOT NULL,
  expired tinyint(1) NOT NULL,
  expires_at datetime DEFAULT NULL,
  confirmation_token varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  password_requested_at datetime DEFAULT NULL,
  roles longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  credentials_expired tinyint(1) NOT NULL,
  credentials_expire_at datetime DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY UNIQ_957A647992FC23A8 (username_canonical),
  UNIQUE KEY UNIQ_957A6479A0D96FBF (email_canonical)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO fos_user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at) VALUES
(31,	'admin',	'admin',	'admin@exprating.lo',	'admin@exprating.lo',	1,	'juemmtjsqfkcosscok0s80ooo4sgkc0',	'$2y$13$.rCsnAxI6MV/cZy9CG65VeBczK3rhOiYPfi4YYy22QWU6BTpq1/ly',	NULL,	0,	0,	NULL,	NULL,	NULL,	'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}',	0,	NULL);

DROP TABLE IF EXISTS migration_versions;
CREATE TABLE migration_versions (
  version varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO migration_versions (version) VALUES
('20160201095333'),
('20160201105925'),
('20160201110211');

DROP TABLE IF EXISTS product;
CREATE TABLE product (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  slug varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  preview_image varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  min_price decimal(10,2) DEFAULT NULL,
  created_at datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY UNIQ_D34A04AD989D9B62 (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO product (id, title, slug, preview_image, min_price, created_at) VALUES
(161,	'title 1',	'product_1',	'product_1.jpg',	333.00,	'2016-02-01 18:10:21'),
(162,	'title 2',	'product_2',	'product_2.jpg',	302.00,	'2016-02-01 18:10:21'),
(163,	'title 3',	'product_3',	'product_3.jpg',	769.00,	'2016-02-01 18:10:21'),
(164,	'title 4',	'product_4',	'product_4.jpg',	226.00,	'2016-02-01 18:10:21'),
(165,	'title 5',	'product_5',	'product_5.jpg',	755.00,	'2016-02-01 18:10:21'),
(166,	'title 6',	'product_6',	'product_6.jpg',	593.00,	'2016-02-01 18:10:21'),
(167,	'title 7',	'product_7',	'product_7.jpg',	31.00,	'2016-02-01 18:10:21'),
(168,	'title 8',	'product_8',	'product_8.jpg',	933.00,	'2016-02-01 18:10:21'),
(169,	'title 9',	'product_9',	'product_9.jpg',	987.00,	'2016-02-01 18:10:21'),
(170,	'title 10',	'product_10',	'product_10.jpg',	414.00,	'2016-02-01 18:10:21');

-- 2016-02-02 08:28:31
