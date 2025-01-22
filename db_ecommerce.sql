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
('asd','asd','asd@gmail.com','$2y$10$hr0ZX5d1kZ4OIwUdPgqmG.gctvq0CuI9is8PHM5tkkxim380Bo8C2','0819829839','Klampis','user'),
('admin','admin','admin@gmail.com','$2y$10$CL0haM2/XA/M.mikXw/l0e86Z9Z/w.EYnCk5NahlNVsWSgS7S9d8e','09191283928','ngagel','master');

/*Table structure for table `brands` */

DROP TABLE IF EXISTS `brands`;

CREATE TABLE `brands` (
  `ID_brands` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `premium` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID_brands`),
  UNIQUE KEY `brands_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1,'Men','https://img01.ztat.net/article/spp-media-p1/1aae619ddfed3881adf4291320b3489c/008ba9180eb842c89e12d309be4b4d6f.jpg?imwidth=762','2024-12-11 10:00:00','2024-12-12 22:12:21',NULL),
(2,'Women','https://www.womanwithin.com/on/demandware.static/-/Sites-masterCatalog_ellos/default/dwe356877a/images/hi-res/2532_24826_mc_1364.jpg','2024-12-11 10:00:00','2024-12-12 22:12:33',NULL),
(3,'Kids','https://fasnina.com/wp-content/uploads/2020/01/dress-anak-perempuan.jpg','2024-12-11 10:00:00','2024-12-12 22:12:48',NULL);

CREATE TABLE brands (
  ID_brands int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) DEFAULT NULL,
  logo text DEFAULT NULL,
  created_at datetime DEFAULT current_timestamp(),
  updated_at datetime DEFAULT NULL ON UPDATE current_timestamp(),
  deleted_at datetime DEFAULT NULL,
  PRIMARY KEY (ID_brands)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* Data untuk tabel brands */

INSERT INTO brands (ID_brands, name, logo, created_at, updated_at, deleted_at) VALUES
(1, 'H&M', 'https://cleanclothes.org/image-repository/livingwage-living-wage-images-h-m-logo/@@images/image.jpeg', '2024-12-11 10:00:00', '2024-12-12 22:12:21', NULL),
(2, 'Zara', 'https://logos-world.net/wp-content/uploads/2020/05/Zara-Logo-700x394.png', '2024-12-11 10:00:00', '2024-12-12 22:12:33', NULL),
(3, 'Uniqlo', 'https://logos-world.net/wp-content/uploads/2023/01/Uniqlo-Logo-500x281.png', '2024-12-11 10:00:00', '2024-12-12 22:12:48', NULL);

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
  `status` varchar(255) DEFAULT `unpaid`,
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
  `collaboration_id` int DEFAULT NULL,
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
(1,'Basic White T-Shirt','https://dynamic.zacdn.com/wrzl4hhcupEAWUtQqC-NGVPJ1_s=/filters:quality(70):format(webp)/https://static-id.zacdn.com/p/kale-clothing-9872-5761543-1.jpg','Comfortable cotton T-shirt in white.',50,100000,10,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(2,'Graphic Print Tee','https://fiver.media/images/mu/2018/01/07/graphic-print-mens-t-shirt-white-multi-74063-4.jpg','Trendy T-shirt with graphic design.',40,120000,5,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(3,'Black Round Neck Tee','https://5.imimg.com/data5/ECOM/Default/2023/2/AO/ZU/KG/45978459/1602660269112-1-28-psb1-originnm80prcnt-500x500.jpg','Classic black round-neck T-shirt.',60,90000,0,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(4,'V-Neck Blue Tee','https://cdna.lystit.com/photos/31da-2014/12/02/polo-ralph-lauren-blue-jersey-v-neck-t-shirt-product-1-25378042-0-510631334-normal.jpeg','Stylish V-neck T-shirt in blue.',30,110000,15,1,1,'2024-12-11 10:00:00','2024-12-12 11:36:27',NULL),
(5,'Oversized T-Shirt','https://dynamic.zacdn.com/KIBGiQxLDuUXm967fzB8kjvpEbs=/filters:quality(70):format(webp)/https://static-id.zacdn.com/p/house-of-cuff-5713-6228373-6.jpg','Relaxed-fit oversized T-shirt.',25,130000,10,1,1,'2024-12-11 10:00:00','2024-12-17 21:42:16',NULL),
(6,'Formal Blue Shirt','https://5.imimg.com/data5/ANDROID/Default/2023/7/324570016/TT/TO/TM/11278324/product-jpeg-1000x1000.jpg','Elegant blue shirt for office or formal events.',30,150000,15,1,2,'2024-12-11 10:00:00','2024-12-17 21:41:54',NULL),
(7,'Casual Plaid Shirt','https://i.pinimg.com/originals/17/89/70/178970b49259f4836e6e70ca09725758.jpg','Stylish plaid shirt for casual outings.',20,150000,10,1,2,'2024-12-11 10:00:00','2024-12-17 21:42:03',NULL),
(8,'Slim Fit White Shirt','https://www.crewclothing.co.uk/images/products/large/MKB029_WHITE.jpg','Modern slim fit white shirt.',25,180000,0,1,2,'2024-12-11 10:00:00','2024-12-17 21:39:54',NULL),
(9,'Linen Shirt','https://dynamic.zacdn.com/M1Lmuo6kZnnEUS5qi_6RbZ5M-8U=/fit-in/346x500/filters:quality(90):fill(ffffff)/https://static-id.zacdn.com/p/hassenda-2645-0693104-1.jpg','Breathable linen shirt, perfect for summer.',15,220000,5,1,2,'2024-12-11 10:00:00','2024-12-12 22:11:35',NULL),
(10,'Denim Shirt','https://s2.bukalapak.com/img/265836668/m-1000-1000/Jaket_Pria_Jeans_Denim_Kece_SPIB436.jpg','Casual denim shirt with button closure.',18,210000,10,1,2,'2024-12-11 10:00:00','2024-12-12 22:11:35',NULL),
(11,'Casual Denim Shorts','https://i5.walmartimages.com/asr/942c0e62-9b6a-4387-9276-51a756f4f905.c3fea3198fff558cd9ebb04e9ccf8d6f.jpeg','Stylish denim shorts, perfect for summer.',40,150000,5,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:07',NULL),
(12,'Cargo Shorts','https://cdna.lystit.com/photos/0f2e-2016/02/20/lucky-brand-brunt-olive-mens-cargo-shorts-green-product-0-311005430-normal.jpeg','Durable cargo shorts with multiple pockets.',30,170000,10,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:08',NULL),
(13,'Sports Shorts','https://www.tennisnuts.com/images/product/full/887517-686-A.jpg','Lightweight shorts for sports and fitness.',50,120000,0,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:08',NULL),
(14,'Chino Shorts','https://s1.bukalapak.com/img/1759920062/w-1000/Celana_Chino_Pendek_Pria_Premium__Chinos_Chino_Pendek___Fash.png','Versatile chino shorts for casual wear.',35,160000,15,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:12',NULL),
(15,'Linen Shorts','https://down-my.img.susercontent.com/file/id-11134201-23020-5kgjn2io4unv7c','Breathable linen shorts for warm weather.',25,140000,5,1,3,'2024-12-11 10:00:00','2024-12-13 08:42:13',NULL),
(16,'Chino Trousers','https://i5.walmartimages.com/asr/adda0cef-7c61-4456-b295-06567c95d50f_1.e3bf9638820ac63999dce722eb5d55c6.jpeg','Comfortable and versatile chino trousers.',25,200000,0,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:42',NULL),
(17,'Slim Fit Trousers','https://dynamic.zacdn.com/YYxMxViAZ5c7BunUDTNnWDKems0=/filters:quality(70):format(webp)/https://static-id.zacdn.com/p/emoline-9680-3557983-1.jpg','Modern slim-fit trousers.',20,250000,10,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:43',NULL),
(18,'Formal Grey Trousers','https://dynamic.zacdn.com/z6xpC1bWZmybQ8HiDbUtW2W-30E=/filters:quality(70):format(webp)/https://static-id.zacdn.com/p/dzargo-6453-8472234-1.jpg','Elegant grey trousers for formal wear.',15,300000,20,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:43',NULL),
(19,'Wide Leg Trousers','https://images.opumo.com/wordpress/wp-content/uploads/2023/02/opumo-2-4.jpg','Relaxed wide-leg trousers.',30,220000,0,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:44',NULL),
(20,'Drawstring Trousers','https://apisap.fabindia.com/medias/20054555-01.JPG?context=bWFzdGVyfGltYWdlc3w2NzY0OTN8aW1hZ2UvanBlZ3xoZjkvaDc3LzkxOTEwMzAwNjMxMzQvMjAwNTQ1NTVfMDEuSlBHfGE5YjViNmUyMzQ1ZTRlYTQ2ODg0NTI0NjY5YzhkMTQ5YWIwOGJhNWU4YWMyYjUzZmM3YmVmN2UwMmUyOTE4ZjM','Comfortable trousers with drawstring.',35,180000,5,1,4,'2024-12-11 10:00:00','2024-12-13 08:42:45',NULL),
(21,'Collared bomber jacket.','https://i.pinimg.com/originals/0c/f3/a1/0cf3a1583e51d2df856a633d0d2c2e0e.jpg','Regular Fit Collared bomber jacket.\r\n',15,220000,20,1,5,'2024-12-11 10:00:00','2024-12-17 21:39:02',NULL),
(22,'Denim Jacket','https://4.bp.blogspot.com/-mI9Ii8w7410/W_oW_dZgBqI/AAAAAAAACBE/_Qhkeo-GjLo1IkcZSaJc6PA4t9b6u0ziQCLcBGAs/s1600/inficlo_inficlo-jacket-denim-pria--211-_full04.jpg','Classic blue denim jacket.',20,250000,15,1,5,'2024-12-11 10:00:00','2024-12-17 21:27:27',NULL),
(23,'Hooded Jacket','https://i5.walmartimages.com/asr/d9c9c8ac-2831-41af-8cba-bab7254ac28c_1.f4ea4d5abda2c1488239d5edc0b0210c.jpeg ','Casual hooded jacket for everyday wear.',25,200000,10,1,5,'2024-12-11 10:00:00','2024-12-17 21:27:22',NULL),
(24,'Loose Fit Artwork Hoodie','https://hotelier.id/wp-content/uploads/2023/02/abu.png','Stylish loose fit hoodie. ',10,250000,25,1,5,'2024-12-11 10:00:00','2024-12-17 21:36:57',NULL),
(25,'Bomber Jacket','https://www.xtremejackets.com/wp-content/uploads/2018/02/a-2-bomber-jacket-a.jpg','Trendy bomber jacket.',18,250000,10,1,5,'2024-12-11 10:00:00','2024-12-17 21:41:19',NULL),
(26,'Floral Print Top','https://dynamic.zacdn.com/rcfovmnH9ilavBWgMPYp8iuRd7Y=/filters:quality(70):format(webp)/https://static-id.zacdn.com/p/fame-1217-3707573-1.jpg','Beautiful floral top perfect for summer.',50,150000,10,2,6,'2024-12-12 10:00:00','2024-12-13 08:42:59',NULL),
(27,'Casual Blouse','https://i.pinimg.com/originals/b8/de/c2/b8dec22e48066fb28d26f9c6b2e0066e.jpg','Casual blouse with button-up front.',40,120000,5,2,6,'2024-12-12 10:00:00','2024-12-13 08:42:59',NULL),
(28,'Long Sleeve Top','https://i5.walmartimages.com/asr/563fbb98-9756-4851-8138-e17237110b1d_1.118096af693fb6e29664789d492002e6.jpeg','Comfortable long sleeve top for casual wear.',45,160000,10,2,6,'2024-12-12 10:00:00','2024-12-13 08:43:00',NULL),
(29,'Printed Square Neck Short Sleeve','https://cdn-img.prettylittlething.com/e/0/3/8/e038d88ccdfb87d1354c79bfbf3410b866a8caf5_cml4391_1.jpg','Stylish printed short sleeve with square neck.',60,140000,5,2,6,'2024-12-12 10:00:00','2024-12-13 08:43:05',NULL),
(30,'Crop Tee','https://down-id.img.susercontent.com/file/id-11134207-7r98z-lxxx749m1zfw46','Trendy crop tee for casual outings.',70,120000,15,2,6,'2024-12-12 10:00:00','2024-12-13 08:43:06',NULL),
(31,'Maxi Dress','https://laz-img-sg.alicdn.com/p/488f30f36199a8a286a85f69141aa5a5.jpg','Elegant maxi dress for evening events.',30,250000,15,2,7,'2024-12-12 10:00:00','2024-12-13 08:44:02',NULL),
(32,'Summer Dress','https://i5.walmartimages.com/asr/d414b249-8527-4f77-886a-ba0c34f9562d_1.d79fbbda485fa8e753b82b5299fc54e2.jpeg','Lightweight summer dress in floral pattern.',60,180000,20,2,7,'2024-12-12 10:00:00','2024-12-13 08:44:03',NULL),
(36,'Wide Leg Trousers','https://i5.walmartimages.com/asr/a837facf-5a84-4075-b5d2-08ec55247391.a2e62e105c2f4a21704ed04d59b42d6e.jpeg','Comfortable wide-leg trousers for casual wear.',40,220000,10,2,4,'2024-12-12 10:00:00','2024-12-13 08:50:38',NULL),
(37,'Slim Fit Trousers','https://i5.walmartimages.com/asr/4931432f-9375-48fe-b623-110e808c4d3c_1.40bdcbeb74b4c381e7e662b8d16f1629.jpeg','Slim fit trousers perfect for office wear.',50,210000,5,2,4,'2024-12-12 10:00:00','2024-12-13 08:50:39',NULL),
(41,'A-Line Skirt','https://i5.walmartimages.com/asr/b661e520-8ef0-4ff7-ba5e-bcf2c9e15cdf.62065d5b5b045da7b74a3c75e5da5d8e.jpeg','Classic A-line skirt perfect for any occasion.',70,130000,0,2,8,'2024-12-12 10:00:00','2024-12-13 08:51:11',NULL),
(42,'Knee-length Denim Skirt','https://i.pinimg.com/736x/ec/c3/e3/ecc3e33b5cbe09e23abb4ed4413def40.jpg','Denim skirt for casual looks.',30,190000,10,2,8,'2024-12-12 10:00:00','2024-12-13 08:51:12',NULL),
(46,'Leather Jacket','https://images-na.ssl-images-amazon.com/images/I/713azxKQ1RL.jpg','Stylish leather jacket for casual outings.',20,350000,5,2,5,'2024-12-12 10:00:00','2024-12-13 08:51:32',NULL),
(47,'Denim Jacket','https://i.pinimg.com/originals/95/2d/0e/952d0e48c452ccc99eab2831ea1814dd.jpg','Classic blue denim jacket.',35,250000,10,2,5,'2024-12-12 10:00:00','2024-12-17 21:40:28',NULL),
(55,'Floral Dress','https://fasnina.com/wp-content/uploads/2020/01/dress-anak-perempuan.jpg','Pretty floral dress for girls.',60,140000,5,3,7,'2024-12-11 10:00:00','2024-12-13 08:51:55',NULL),
(56,'Denim Skirt','https://down-id.img.susercontent.com/file/sg-11134201-7rbk0-lkt0o3zw0e6984','Casual denim skirt for girls.',50,130000,10,3,8,'2024-12-11 10:00:00','2024-12-13 08:51:58',NULL),
(57,'Graphic Tee','https://i5.walmartimages.com/asr/ee6aa4c2-8670-40b0-905c-dd03585fe5d5_1.9fce0bac9228c750a73349f27660750e.jpeg','Colorful graphic T-shirt for girls.',70,100000,0,3,6,'2024-12-11 10:00:00','2024-12-13 08:52:13',NULL),
(58,'Printed sweatshirt','https://dfcdn.defacto.com.tr/7/Y5095A6_22AU_AR1_01_02.jpg','Comfortbale sweatshirt for girls.',50,100000,0,3,6,'2024-12-11 10:00:00','2024-12-13 08:53:50',NULL),
(59,'Slim Fit Jeans','https://ecs7.tokopedia.net/img/cache/700/product-1/2018/12/13/4388653/4388653_53828fbd-4ffa-42a9-b6d5-f9a647490aba.jpg','A comfortable jeans for girls.',40,180000,0,3,4,'2024-12-11 10:00:00','2024-12-13 08:52:24',NULL),
(60,'Hooded Sweatshirt','https://i.pinimg.com/originals/d3/ec/c4/d3ecc4b10e93909221b396f6b73f810e.jpg','Comfortable hooded sweatshirt for boys.',50,170000,10,3,2,'2024-12-11 10:00:00','2024-12-13 08:53:12',NULL),
(61,'Cargo Shorts','https://down-id.img.susercontent.com/file/id-11134207-7r991-lm32yzrey0mj5c','Durable cargo shorts for boys.',80,120000,5,3,3,'2024-12-11 10:00:00','2024-12-13 08:53:10',NULL),
(62,'Striped Polo Shirt','https://i.pinimg.com/originals/83/ca/40/83ca4074c86082de1e303e50145bcefc.jpg','Smart striped polo shirt for boys.',60,150000,5,3,2,'2024-12-11 10:00:00','2024-12-17 21:41:30',NULL);

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
(1,'T-Shirts','https://dynamic.zacdn.com/wrzl4hhcupEAWUtQqC-NGVPJ1_s=/filters:quality(70):format(webp)/https://static-id.zacdn.com/p/kale-clothing-9872-5761543-1.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(2,'Shirts','https://i.pinimg.com/originals/17/89/70/178970b49259f4836e6e70ca09725758.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(3,'Shorts','https://www.tennisnuts.com/images/product/full/887517-686-A.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(4,'Trousers','https://images.opumo.com/wordpress/wp-content/uploads/2023/02/opumo-2-4.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(5,'Jacket','https://i.pinimg.com/originals/0c/f3/a1/0cf3a1583e51d2df856a633d0d2c2e0e.jpg',1,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(6,'Tops','https://down-id.img.susercontent.com/file/id-11134207-7r98z-lxxx749m1zfw46',2,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(7,'Dresses','https://laz-img-sg.alicdn.com/p/488f30f36199a8a286a85f69141aa5a5.jpg',2,'2024-12-11 10:00:00','2024-12-11 10:00:00'),
(8,'Skirts','https://i.pinimg.com/736x/ec/c3/e3/ecc3e33b5cbe09e23abb4ed4413def40.jpg',2,'2024-12-11 10:00:00','2024-12-11 10:00:00');

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
