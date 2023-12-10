-- bluevend.coin_storing definition

CREATE TABLE `coin_storing` (
  `owner` enum('user','machine') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('1','5','10','20','50','100','500','1000') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`owner`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- bluevend.products definition

CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO bluevend.coin_storing (owner,`type`,amount,createdAt,updatedAt) VALUES
	 ('user','1',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('user','5',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('user','10',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('user','20',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('user','50',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('user','100',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('user','500',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('user','1000',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('machine','1',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('machine','5',1,'2023-12-10 10:31:14','2023-12-10 10:31:14');
INSERT INTO bluevend.coin_storing (owner,`type`,amount,createdAt,updatedAt) VALUES
	 ('machine','10',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('machine','20',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('machine','50',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('machine','100',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('machine','500',1,'2023-12-10 10:31:14','2023-12-10 10:31:14'),
	 ('machine','1000',1,'2023-12-10 10:31:14','2023-12-10 10:31:14');


INSERT INTO bluevend.products (name,price,amount,createdAt,updatedAt) VALUES
	 ('Oishi Chakulza',15,6,'2023-12-10 10:14:54','2023-12-10 10:14:54'),
	 ('Pepsi can',12,10,'2023-12-10 10:14:54','2023-12-10 10:14:54'),
	 ('Snickers chocolate 35g.',22,20,'2023-12-10 10:14:54','2023-12-10 10:14:54'),
	 ('Lays',40,6,'2023-12-10 10:14:54','2023-12-10 10:14:54');
