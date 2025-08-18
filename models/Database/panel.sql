CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `fullname` varchar(255) NOT NULL,
 `mail` varchar(255) NOT NULL,
 `userToken` varchar(255) NOT NULL,
 `password` varchar(255) NOT NULL,
 `isLogged` bit(1) NOT NULL DEFAULT b'0',
 `adminIp` varchar(255) NOT NULL,
 `isConfirmedDevice` bit(1) NOT NULL DEFAULT b'0',
 `role` enum('admin','personel') NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci