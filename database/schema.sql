/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - inventory_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`inventory_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `inventory_db`;

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`product_name`,`category`,`quantity`,`price`,`created_at`) values 
(1,'YK Wireless Ergonomic Mouse','Electronics',45,300.99,'2026-10-18 22:54:27'),
(2,'ZEUS Mechanical RGB Keyboard','Electronics',20,500.50,'2026-10-18 22:54:27'),
(3,'Standing Desk','Furniture',34,1999.00,'2026-10-18 22:54:27'),
(4,'NVDIA 27-inch 4K Monitor','Electronics',30,4999.99,'2026-10-18 22:54:27'),
(5,'POPCORN SPEAKER','Electronics',10,200.00,'2026-10-19 18:01:28');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Employee') DEFAULT 'Employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`full_name`,`email`,`phone`,`password`,`role`,`created_at`) values 
(1,'System Admin','admin@gmail.com','09123456789','$2y$10$YmyxnYUWLrtgyEgy23DcGe1mcdpScVY8vJi1.Ish0y2mlqZwv.ewW','Admin','2026-10-18 22:54:12'),
(2,'Rafhael Butardo','rafhael123@gmail.com','09878787878','$2y$10$i1jSTIANnhpLM16p0bIH1ezNDBnPwP6Z.cmggqXlqxca7SzGdVTt.','Employee','2026-10-19 00:34:27');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
