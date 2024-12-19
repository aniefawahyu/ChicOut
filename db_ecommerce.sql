/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.28-MariaDB : Database - db_ecommerce
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_ecommerce` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_ecommerce`;

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `username` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` varchar(6) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `accounts` */

insert  into `accounts`(`username`,`display_name`,`email`,`password`,`tel`,`address`,`role`) values 
('calvin','calvin','calvin@gmail.com','$2y$10$hr0ZX5d1kZ4OIwUdPgqmG.gctvq0CuI9is8PHM5tkkxim380Bo8C2','0819829839','Klampis','user'),
('calvins','calvin','calvinsabcdefg@gmail.com','$2y$10$HUkLeKVlW7IJdlBr.2AQHO/5qapqLLMbFp00I4ubarkyeLVEFKXWW','0819829839','Klampis','user'),
('coba','master','master@gmail.com','$2y$10$x1t7VG881pSyHf9m/kY0t.zT8P3sAaNEY/oXFS47wdo1dE2jn27fi','0987898778971','ngagel','user'),
('Flo','Florencia','florencia@gmail.com','$2y$10$dgsh2Tp2sz2WMfnl8iYQi.EQ7CytgJgqqzWorYfGv.la7mQ43yA4u','0881037767536','Surabaya','user'),
('joy','Joy Gemilang','joy@gmail.com','$2y$10$CL0haM2/XA/M.mikXw/l0e86Z9Z/w.EYnCk5NahlNVsWSgS7S9d8e','09191283928','ngagel','master'),
('ryu','Ryu','ryuganteng123@bolobolo.com','$2y$10$gi/ZXimG7GVtggYLn4pSd.kgAVIaIiT/JBE/go9aWT9SJKpH5GyhO','123','123','user');

/*Table structure for table `cart` */

DROP TABLE IF EXISTS `cart`;

CREATE TABLE `cart` (
  `ID_cart` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `ID_items` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_cart`),
  KEY `username` (`username`),
  KEY `ID_items` (`ID_items`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`username`) REFERENCES `accounts` (`username`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ID_items`) REFERENCES `items` (`ID_items`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cart` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `ID_categories` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `img` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_categories`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `categories` */

insert  into `categories`(`ID_categories`,`name`,`img`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Men','men.jpg','2024-12-11 10:00:00','2024-12-12 22:12:21',NULL),
(2,'Women','women.jpg','2024-12-11 10:00:00','2024-12-12 22:12:33',NULL),
(3,'Kids','kids.jpg','2024-12-11 10:00:00','2024-12-12 22:12:48',NULL);

/*Table structure for table `dtrans` */

DROP TABLE IF EXISTS `dtrans`;

CREATE TABLE `dtrans` (
  `ID_dtrans` int(11) NOT NULL AUTO_INCREMENT,
  `ID_htrans` int(11) NOT NULL,
  `ID_items` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  PRIMARY KEY (`ID_dtrans`),
  KEY `ID_htrans` (`ID_htrans`),
  KEY `ID_items` (`ID_items`),
  CONSTRAINT `dtrans_ibfk_1` FOREIGN KEY (`ID_htrans`) REFERENCES `htrans` (`ID_htrans`),
  CONSTRAINT `dtrans_ibfk_2` FOREIGN KEY (`ID_items`) REFERENCES `items` (`ID_items`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `dtrans` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `htrans` */

DROP TABLE IF EXISTS `htrans`;

CREATE TABLE `htrans` (
  `ID_htrans` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `ID_payments` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp(),
  `total` int(11) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`ID_htrans`),
  KEY `username` (`username`),
  KEY `ID_payments` (`ID_payments`),
  CONSTRAINT `htrans_ibfk_1` FOREIGN KEY (`username`) REFERENCES `accounts` (`username`),
  CONSTRAINT `htrans_ibfk_2` FOREIGN KEY (`ID_payments`) REFERENCES `payments` (`ID_payments`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `htrans` */

insert  into `htrans`(`ID_htrans`,`username`,`ID_payments`,`purchase_date`,`total`,`address`) values 
(1,'ryu',2,'2024-01-05 14:48:17',629740,'123'),
(2,'ryu',1,'2024-01-05 15:35:20',12999,'123'),
(3,'ryu',2,'2024-01-05 17:03:25',335221,'123'),
(4,'calvin',3,'2024-01-09 15:58:30',435936,'Klampis'),
(5,'calvin',2,'2024-01-10 16:14:21',204995,'Klampis'),
(6,'calvin',3,'2024-01-10 16:53:36',132331,'Klampis'),
(7,'calvin',3,'2024-01-10 17:53:44',12999,'Klampis'),
(8,'calvin',1,'2024-01-10 17:54:59',537481,'Klampis'),
(9,'ryu',1,'2024-01-10 22:51:14',583552,'123'),
(10,'calvin',2,'2024-01-10 22:53:48',40999,'Klampis'),
(11,'calvins',2,'2024-01-10 23:46:44',40999,'Klampis'),
(12,'calvin',1,'2024-01-11 09:32:04',714714,'Klampis'),
(13,'calvin',4,'2024-01-11 12:58:01',391318,'Klampis'),
(14,'calvin',4,'2024-01-11 12:58:02',0,'Klampis'),
(15,'calvin',1,'2024-01-11 17:17:38',286158,'Klampis'),
(16,'Flo',2,'2024-11-26 15:22:20',321776,'Surabaya'),
(17,'Flo',1,'2024-11-26 15:24:14',329662,'Surabaya');

/*Table structure for table `items` */

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `ID_items` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `img` text DEFAULT NULL,
  `description` text NOT NULL,
  `stock` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `ID_categories` int(11) NOT NULL,
  `id_sub_category` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_items`),
  KEY `ID_categories` (`ID_categories`),
  KEY `id_sub_category` (`id_sub_category`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`ID_categories`) REFERENCES `categories` (`ID_categories`),
  CONSTRAINT `items_ibfk_2` FOREIGN KEY (`id_sub_category`) REFERENCES `sub_categories` (`id_sub_category`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `items` */

insert  into `items`(`ID_items`,`name`,`img`,`description`,`stock`,`price`,`discount`,`ID_categories`,`id_sub_category`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Basic White T-Shirt','basic_white_tshirt.jpg','Comfortable cotton T-shirt in white.',50,100000,10,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(2,'Graphic Print Tee','graphic_print_tee.jpg','Trendy T-shirt with graphic design.',40,120000,5,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(3,'Black Round Neck Tee','black_round_neck_tee.jpg','Classic black round-neck T-shirt.',60,90000,0,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(4,'V-Neck Blue Tee','v_neck_blue_tee.jpg','Stylish V-neck T-shirt in blue.',30,110000,15,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(5,'Oversized T-Shirt','oversized_tshirt.jpg','Relaxed-fit oversized T-shirt.',25,130000,10,1,1,'2024-12-11 10:00:00','2024-12-17 21:42:16',NULL),
(6,'Formal Blue Shirt','formal_blue_shirt.jpg','Elegant blue shirt for office or formal events.',30,150000,15,1,2,'2024-12-11 10:00:00','2024-12-17 21:41:54',NULL),
(7,'Casual Plaid Shirt','casual_plaid_shirt.jpg','Stylish plaid shirt for casual outings.',20,150000,10,1,2,'2024-12-11 10:00:00','2024-12-17 21:42:03',NULL),
(8,'Slim Fit White Shirt','slim_fit_white_shirt.jpg','Modern slim fit white shirt.',25,180000,0,1,2,'2024-12-11 10:00:00','2024-12-17 21:39:54',NULL),
(9,'Linen Shirt','linen_shirt.jpg','Breathable linen shirt, perfect for summer.',15,220000,5,1,2,'2024-12-11 10:00:00','2024-12-12 22:11:35',NULL),
(10,'Denim Shirt','denim_shirt.jpg','Casual denim shirt with button closure.',18,210000,10,1,2,'2024-12-11 10:00:00','2024-12-12 22:11:35',NULL),
(11,'Casual Denim Shorts','denim_shorts.jpg','Stylish denim shorts, perfect for summer.',40,150000,5,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:07',NULL),
(12,'Cargo Shorts','cargo_shorts.jpg','Durable cargo shorts with multiple pockets.',30,170000,10,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:08',NULL),
(13,'Sports Shorts','sports_shorts.jpg','Lightweight shorts for sports and fitness.',50,120000,0,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:08',NULL),
(14,'Chino Shorts','chino_shorts.jpg','Versatile chino shorts for casual wear.',35,160000,15,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:12',NULL),
(15,'Linen Shorts','linen_shorts.jpg','Breathable linen shorts for warm weather.',25,140000,5,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:13',NULL),
(16,'Chino Trousers','chino_trousers.jpg','Comfortable and versatile chino trousers.',25,200000,0,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:42',NULL),
(17,'Slim Fit Trousers','slim_fit_trousers.jpg','Modern slim-fit trousers.',20,250000,10,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:43',NULL),
(18,'Formal Grey Trousers','formal_grey_trousers.jpg','Elegant grey trousers for formal wear.',15,300000,20,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:43',NULL),
(19,'Wide Leg Trousers','wide_leg_trousers.jpg','Relaxed wide-leg trousers.',30,220000,0,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:44',NULL),
(20,'Drawstring Trousers','drawstring_trousers.jpg','Comfortable trousers with drawstring.',35,180000,5,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:45',NULL),
(21,'Collared bomber jacket.','bomber_jacket.jpg','Regular Fit Collared bomber jacket.\r\n',15,220000,20,1,5,'2024-12-11 10:00:00','2024-12-17 21:39:02',NULL),
(22,'Denim Jacket','denim_jacket.jpg','Classic blue denim jacket.',20,250000,15,1,5,'2024-12-11 10:00:00','2024-12-17 21:27:27',NULL),
(23,'Hooded Jacket','hooded_jacket.jpg','Casual hooded jacket for everyday wear.',25,200000,10,1,5,'2024-12-11 10:00:00','2024-12-17 21:27:22',NULL),
(24,'Loose Fit Artwork Hoodie','hoodie.jpg','Stylish loose fit hoodie. ',10,250000,25,1,5,'2024-12-11 10:00:00','2024-12-17 21:36:57',NULL),
(25,'Bomber Jacket','bomber_jacket.jpg','Trendy bomber jacket.',18,250000,10,1,5,'2024-12-11 10:00:00','2024-12-17 21:41:19',NULL),
(26,'Floral Print Top','floral_print_top.jpg','Beautiful floral top perfect for summer.',50,150000,10,2,6,'2024-12-12 10:00:00','2024-12-13 08:42:59',NULL),
(27,'Casual Blouse','casual_blouse.jpg','Casual blouse with button-up front.',40,120000,5,2,6,'2024-12-12 10:00:00','2024-12-13 08:42:59',NULL),
(28,'Long Sleeve Top','long_sleeve_top.jpg','Comfortable long sleeve top for casual wear.',45,160000,10,2,6,'2024-12-12 10:00:00','2024-12-13 08:43:00',NULL),
(29,'Printed Square Neck Short Sleeve','printed_square_neck_short_sleeve.jpg','Stylish printed short sleeve with square neck.',60,140000,5,2,6,'2024-12-12 10:00:00','2024-12-13 08:43:05',NULL),
(30,'Crop Tee','crop_tee.jpg','Trendy crop tee for casual outings.',70,120000,15,2,6,'2024-12-12 10:00:00','2024-12-13 08:43:06',NULL),
(31,'Maxi Dress','maxi_dress.jpg','Elegant maxi dress for evening events.',30,250000,15,2,7,'2024-12-12 10:00:00','2024-12-13 08:44:02',NULL),
(32,'Summer Dress','summer_dress.jpg','Lightweight summer dress in floral pattern.',60,180000,20,2,7,'2024-12-12 10:00:00','2024-12-13 08:44:03',NULL),
(36,'Wide Leg Trousers','wide_leg_trousers.jpg','Comfortable wide-leg trousers for casual wear.',40,220000,10,2,4,'2024-12-12 10:00:00','2024-12-13 08:50:38',NULL),
(37,'Slim Fit Trousers','slim_fit_trousers.jpg','Slim fit trousers perfect for office wear.',50,210000,5,2,4,'2024-12-12 10:00:00','2024-12-13 08:50:39',NULL),
(41,'A-Line Skirt','a_line_skirt.jpg','Classic A-line skirt perfect for any occasion.',70,130000,0,2,8,'2024-12-12 10:00:00','2024-12-13 08:51:11',NULL),
(42,'Knee-length Denim Skirt','','Denim skirt for casual looks.',30,190000,10,2,8,'2024-12-12 10:00:00','2024-12-13 08:51:12',NULL),
(46,'Leather Jacket','leather_jacket.jpg','Stylish leather jacket for casual outings.',20,350000,5,2,5,'2024-12-12 10:00:00','2024-12-13 08:51:32',NULL),
(47,'Denim Jacket','','Classic blue denim jacket.',35,250000,10,2,5,'2024-12-12 10:00:00','2024-12-17 21:40:28',NULL),
(55,'Floral Dress','floral_dress_girls.jpg','Pretty floral dress for girls.',60,140000,5,3,7,'2024-12-11 10:00:00','2024-12-13 08:51:55',NULL),
(56,'Denim Skirt','denim_skirt_girls.jpg','Casual denim skirt for girls.',50,130000,10,3,8,'2024-12-11 10:00:00','2024-12-13 08:51:58',NULL),
(57,'Graphic Tee','graphic_tee_girls.jpg','Colorful graphic T-shirt for girls.',70,100000,0,3,6,'2024-12-11 10:00:00','2024-12-13 08:52:13',NULL),
(58,'Printed sweatshirt','graphic_tee_girls.jpg','Comfortbale sweatshirt for girls.',50,100000,0,3,6,'2024-12-11 10:00:00','2024-12-13 08:53:50',NULL),
(59,'Slim Fit Jeans','graphic_tee_girls.jpg','A comfortable jeans for girls.',40,180000,0,3,4,'2024-12-11 10:00:00','2024-12-13 08:52:24',NULL),
(60,'Hooded Sweatshirt','hooded_sweatshirt_boys.jpg','Comfortable hooded sweatshirt for boys.',50,170000,10,3,2,'2024-12-11 10:00:00','2024-12-13 08:53:12',NULL),
(61,'Cargo Shorts','cargo_shorts_boys.jpg','Durable cargo shorts for boys.',80,120000,5,3,3,'2024-12-11 10:00:00','2024-12-13 08:53:10',NULL),
(62,'Striped Polo Shirt','striped_polo_shirt_boys.jpg','Smart striped polo shirt for boys.',60,150000,5,3,2,'2024-12-11 10:00:00','2024-12-17 21:41:30',NULL);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_reset_tokens_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2019_12_14_000001_create_personal_access_tokens_table',1),
(5,'2024_12_11_081009_create_sessions_table',1);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `ID_payments` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `img` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_payments`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `payments` */

insert  into `payments`(`ID_payments`,`name`,`img`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'GoPay','https://imgop.itemku.com/?url=https%3A%2F%2Ffiles.itemku.com%2Flogo%2Fpayment%2Fgopay.png&w=48&q=75','2024-01-09 22:51:39',NULL,NULL),
(2,'OVO','https://imgop.itemku.com/?url=https%3A%2F%2Ffiles.itemku.com%2Flogo%2Fpayment%2Fovo.png&w=48&q=75','2024-01-09 22:51:39',NULL,NULL),
(3,'BCA Virtual Account','https://imgop.itemku.com/?url=https%3A%2F%2Ffiles.itemku.com%2Flogo%2Fpayment%2Fbca.png&w=48&q=75','2024-01-09 22:51:39',NULL,NULL),
(4,'Mandiri Virtual Account','https://imgop.itemku.com/?url=https%3A%2F%2Ffiles.itemku.com%2Flogo%2Fpayment%2Fmandiri.png&w=48&q=75','2024-01-09 22:51:39',NULL,NULL);

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `reviews` */

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `ID_reviews` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `ID_items` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_reviews`),
  KEY `username` (`username`),
  KEY `ID_items` (`ID_items`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`username`) REFERENCES `accounts` (`username`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`ID_items`) REFERENCES `items` (`ID_items`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `reviews` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('LK0w2RTFelftxlQtirZh9ST33xCvVLcvQ7O9Y0QJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnZxWXB1VTdrUHl6NlhXdHc5U2J5MkgyTFBEWXlrM2R0aHBoN3czUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9DaGljT3V0L1NpZ25Jbi1TaWduVXAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1734403197),
('NrvtDRZSw5mlaTpJM0jqSih3sRG4UeTbNkhLHJLa',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicjFzNG54SktZeTVUSTBsVVB6dmhKME0wbER2cldqcERFVDcydktZeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1734053752),
('q0sBjJC827jbUS1PCINi4RVsIYBPgeCt1hDnprbP',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoib0ZOVnhvZnlDUHR5YVZhN0hIM1dxOFNVMDZHSU40dXdKeXgwRk1jUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9DaGljT3V0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1734580799);

/*Table structure for table `sub_categories` */

DROP TABLE IF EXISTS `sub_categories`;

CREATE TABLE `sub_categories` (
  `id_sub_category` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `id_category` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sub_category`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `sub_categories` */

insert  into `sub_categories`(`id_sub_category`,`name`,`img`,`id_category`,`created_at`,`updated_at`) values 
(1,'T-Shirts','',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(2,'Shirts','',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(3,'Shorts','men_shorts.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(4,'Trousers','men_trousers.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(5,'Jacket','men_jackets_coats.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(6,'Tops','women_tops.jpg',2,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(7,'Dresses','women_dresses.jpg',2,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(8,'Skirts','women_skirts.jpg',2,'2024-12-11 10:00:00','2024-12-11 10:00:00');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`role`,`remember_token`,`created_at`,`updated_at`) values 
(1,'flo','flo@gmail.com',NULL,'flo123','admin',NULL,'2024-12-06 13:23:56','2024-12-06 13:23:56');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
