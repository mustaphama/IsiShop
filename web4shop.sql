-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: web4shop
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `web4shop`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `web4shop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `web4shop`;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'john','f18bd055eec95965ee175fa9badd35ae6dbeb98f');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'boissons'),(2,'biscuits'),(3,'fruits secs');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `add1` varchar(50) NOT NULL,
  `add2` varchar(50) NOT NULL,
  `add3` varchar(50) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `registered` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Sarah','Hamida','ligne add1','ligne add2','Meximieux','01800','0612345678','s.hamida@gmail.com',1),(2,'Jean-Benoît','Delaroche','ligne add1','ligne add2','Lyon','69009','0796321458','jb.delaroche@gmx.fr',1),(14,'Salim','chabchoub','9 place de la liberte','','Antony','92160','0756963212','schab@gmail.com',1);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_addresses`
--

DROP TABLE IF EXISTS `delivery_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `add1` varchar(50) NOT NULL,
  `add2` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_addresses`
--

LOCK TABLES `delivery_addresses` WRITE;
/*!40000 ALTER TABLE `delivery_addresses` DISABLE KEYS */;
INSERT INTO `delivery_addresses` VALUES (46,'Christian','Hamida','15 Rue de la paix','','Saint Etienne','42000','0477213145','chr.hamida@gmail.com'),(47,'Sarah','Hamida','ligne add1','ligne add2','Meximieux','01800','0612345678','s.hamida@gmail.com'),(48,'Jean-Benoît','Delaroche','ligne add1','ligne add2','Lyon','69009','0796321458','jb.delaroche@gmx.fr'),(49,'Louise','Delaroche','12 avenue condorcet','étage 2','Saint Priest','45097','0526117898','louise.delaroche@yahoo.fr'),(50,'Salim','chabchoub','9 place de la liberte','','Antony','92160','0756963212','schab@gmail.com');
/*!40000 ALTER TABLE `delivery_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logins`
--

DROP TABLE IF EXISTS `logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logins`
--

LOCK TABLES `logins` WRITE;
/*!40000 ALTER TABLE `logins` DISABLE KEYS */;
INSERT INTO `logins` VALUES (1,'1','Hamidou','d6ee53abcd3b045befa8af69f445fafc33f1f88b'),(2,'2','Delaroche','56a5d2bd85e6c9956d122f59f79540ee0f75e5ad'),(14,'14','solywara','$2y$10$MLrcnMxiuMimnmxhrEIS/uqJNiYITSuX7AeMcSKg.TF9lBDi5ZE.q');
/*!40000 ALTER TABLE `logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderitems`
--

DROP TABLE IF EXISTS `orderitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=289 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderitems`
--

LOCK TABLES `orderitems` WRITE;
/*!40000 ALTER TABLE `orderitems` DISABLE KEYS */;
INSERT INTO `orderitems` VALUES (236,63,17,2),(237,63,19,1),(240,64,18,1),(245,66,23,1);
/*!40000 ALTER TABLE `orderitems` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER update_order_total
AFTER INSERT ON orderitems
FOR EACH ROW
BEGIN
    DECLARE order_total FLOAT;

    
    SELECT SUM(oi.quantity * p.price) INTO order_total
    FROM orderitems oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = NEW.order_id;

    
    UPDATE orders
    SET total = order_total
    WHERE id = NEW.order_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER update_order_total_after_delete
AFTER DELETE ON orderitems
FOR EACH ROW
BEGIN
    DECLARE order_total FLOAT;

    
    SELECT SUM(oi.quantity * p.price) INTO order_total
    FROM orderitems oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = OLD.order_id;

    
    UPDATE orders
    SET total = order_total
    WHERE id = OLD.order_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `registered` int(11) NOT NULL,
  `delivery_add_id` int(11) DEFAULT NULL,
  `payment_type` varchar(6) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `session` varchar(100) NOT NULL,
  `total` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (63,1,1,47,'cheque','2020-01-23',10,'da8bcdf51121d96c71673295b825a010',58.1),(64,1,1,47,'paypal','2020-01-23',3,'da8bcdf51121d96c71673295b825a010',45),(65,2,1,49,'cheque','2020-01-23',10,'da8bcdf51121d96c71673295b825a010',96),(66,2,1,49,'cheque','2020-01-23',10,'da8bcdf51121d96c71673295b825a010',15.3),(67,14,1,50,NULL,NULL,0,'kkosmtq0vjt12scuadamlsnvae',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` tinyint(4) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(30) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `quantity` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (4,2,'Assortiment de biscuits secs','Boîte de 20 biscuits composée de galettes, cookies, crêpes dentelles et sablés','assortimentBiscuitsSecs.jpg',12.90,0),(5,2,'Biscuits de Noël','De doux biscuits de Noël à la cannelle, au chocolat, et sablés pour assurer de belles et uniques fêtes de Noël','biscuitNoel.png',10.50,3),(6,2,'Biscuits aux raisins ','De délicieux biscuits aux raisins secs pour éveiller les sens de toute la famille, des plus petits aux plus grands !','biscuitRaisin.jpeg',6.90,2),(7,3,'Pruneaux secs bio ','Sachet de 500g. De délicieux pruneaux issus d agricultures biologiques et responsables ','pruneauxSecs.jpg',7.90,6),(8,3,'Sachet d\'abricots secs ','Sachet d\'un kilogramme. Produit recommandé par de nombreux nutritionnistes. Authentique goût d\'abricot','abricotsSecs.jpg',15.50,17),(9,3,'Plateau de fruits secs ','Plateau de 1kg composé d\'abricots secs, de noix de cajous, pruneaux secs, bananes sèches, copeaux de noix de coco...','plateauFruitsSecs.jpg',32.00,6),(10,3,'Mélange de fruits secs','Composés de différents sachets de 250g : des marrons, des cacahouètes, des amandes grillés et des noisettes.','melangeMarrons.jpg',25.00,7),(11,3,'Mélange de noisettes','Sachet de 500g composé de noisettes, noix et amandes grillées. Une fois goûtés, on ne peut plus s\'en passer','melangeNoisettes.png',8.30,4),(12,3,'Sachet d\'amandes grillées','Sachet de 500g, grillées lentement au four pour plus de plaisir en bouche lors de la dégustation !','amandes.jpg',9.90,10),(13,1,'Jus de citron','Bouteille d\'un litre de jus de citron frais issus d\'agricultures responsables et biologiques','jusCitron.jpg',5.20,3),(14,1,'Jus de pommes','Pommes issues d\'agricultures biologiques.\r\nBouteille d\'un litre dans une bouteille en verre ','jusPomme.jpg',3.20,8),(15,1,'Jus de pamplemousse','Bouteille d\'un litre et demi sans sucre et sans colorant ajoutés. 100% naturel au bon goût de pamplemousse','jusPamplemousse.jpg',7.30,7),(16,1,'Jus d\'orange','Oranges provenant d\'agricultures locales et biologiques.\r\nCette bouteille d\'un litre permet de se réveiller en douceur le matin.','jusOrange.jpg',4.60,19),(17,1,'Sachet de café en grain','sachet d\'un kilogramme de café en grain, pour garder l\'authentique goût du café pour bien commencer la journée','cafeGrain.jpg',15.00,10),(18,1,'Capsules de café','Paquet de 50 capsules. Adaptable pour toute sortes de machines à café avec capsules','cafeCapsule.jpg',45.00,11),(19,1,'Dosettes de café','Paquet de 30 dosettes de café. Longue date de conservation. Emballage recyclable respectant l\'environnement','dosetteCafe.png',28.10,20),(20,1,'Sachets de thé à la canelle','15 sachets à l\'authentique gout de thé à la cannelle. Nouveauté chez Web4Shop ! ','theCannelle.jpg',10.50,9),(21,1,'Infusion à la verveine','Recommandé pour profiter de nuits calmes.\r\nVendus par paquet de 15 sachets.','infusionVerveine.jpg',8.90,4),(22,1,'Thé vert','20 sachets de thé vert. Les adeptes en raffolent et on comprend bien pourquoi ! ','theVert.jpg',13.90,13),(23,1,'Infusion au citron','Paquet de 20 sachets d\'infusion au citron pour partager un moment unique avec les plus petits ou les plus grands','infusionCitron.jpg',15.30,15),(24,2,'Macarons tout chocolat','Macarons uniques au chocolat. Vendus par 10 macarons dans une très belle boîte ','macaronChocolat.jpg',20.50,18),(25,2,'Boules de neige','Boules aromatisées à la noix de coco.\r\nPlateau de 200g. Idée cadeau qui va plaire aux adeptes de la noix de coco !','bouleDeNeigeCoco.jpg',10.80,8),(26,2,'Cookies au pépites de chocolat','Cookies croquants préparés avec de l\'avoine et des pépites de chocolat fondantes.\r\nPaquet de 15 cookies','cookiesChocolat.jpg',12.30,10),(27,2,'Biscuits étoile à la cannelle','Biscuits secs pour noël à l\'authentique goût de la cannelle. L\'éveil des sens avec ces saveurs est assuré !','biscuitsCannelle.jpg',13.50,14),(28,2,'Biscuits en forme de tortue','Paquet de 20 petits biscuits en forme de tortue. Goût chocolat vanille. Leur très jolie forme va séduire tout le monde !','biscuitsTortue.jpg',25.30,20);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id_product` int(2) NOT NULL,
  `name_user` varchar(50) NOT NULL,
  `photo_user` varchar(50) NOT NULL,
  `stars` int(1) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  KEY `review/product` (`id_product`),
  CONSTRAINT `review/product` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (28,'Gerard','homme.jpg',5,'Trop top','Trop beau et trop bon '),(13,'Michelle','femme.png',3,'super','cool'),(4,'Charlène','femme.png',5,'Excellent !','Ils sont trop bons, je recommande vraiment ces biscuits secs, je ne peux plus m\'en passer ! '),(24,'Helène','femme.png',4,'Vraiment excellent ','Je recommande vivement ces macarons, ils ont un gout authentiques et en plus ils ne sont pas très chers '),(26,'Marc','homme.jpg',4,'Très bon rapport qualité prix','Ils sont vraiment excellents. Je ne sais pas cuisiner alors je les achète et on dirait vraiment des cookies faits maison !'),(26,'Sylvie','femme.png',3,'Je recommande !','Vraiment bons et très craquants, j\'en rachèterai '),(16,'Mélanie','femme.png',5,'Tellement bon ! ','Ce jus est incroyablement bon c\'est un plaisir de déjeuner le matin avec un jus d\'orange si frais '),(23,'Lilian','homme.jpg',3,'Je suis fan','Moi qui suis fan d\'infusion, c\'est vraiment de la qualité ! peut être un peu cher mais vraiment le prix a payer pour bénéficier de si bonnes infusions'),(15,'Elise','femme.png',5,'Tellement bon !!','Je recommande vivement, j\'achète toujours ce bon jus et il fait l\'unanimité à la maison'),(25,'Jean','homme.jpg',4,'Bon goût de noix de coco','Vraiment bon, pour les fêtes de Noël, chaque années elles sont très appréciées'),(11,'Christophe','homme.jpg',4,'Trop bon et livraison rapide','Ces fruits secs sont vraiment à croquer, et ils sont très vite virés à la maison '),(12,'Christine','femme.png',3,'Trop bon ! ','Les amandes sont vraiment bonnes, le paquet ne fait pas longtemps à la maison ! je recommande '),(7,'Marie','femme.png',4,'Une qualité inégalable','Les pruneaux sont vraiment excellents je recommande'),(21,'Léa','femme.png',4,'Des très bonnes infusions','Un goût intensément bon et une livraison rapide'),(6,'Liliane','femme.png',3,'De très bons biscuits ','Vraiment trop bon !! '),(5,'Christine','femme.png',4,'Original','Ces biscuits sont excellents; testés récemment! un délice!'),(4,'Florian','homme.jpg',5,'J\'adore','Ces biscuits secs sont très bons. ils sont variés et très parfumés; je recommande ce produit.'),(6,'Victor','homme.jpg',4,'une tuerie!','les biscuits sont vraiment très bons; très garnis; à tester sans modération!'),(28,'Sophie','femme.png',5,'original!','si vous voulez opter pour des biscuits Originaux, vous ne serez pas déçus! et en plus, ils sont bons!'),(27,'Bernard','homme.jpg',5,'un délice','des biscuits très parfumés qui rappellent les biscuits de mon enfance avec ce bon parfum de cannelle! Je vous le recommande...'),(25,'Huguette','femme.png',3,'bon','Des biscuits assez parfumés, tendre et sucrés juste ce qu\'il faut. bon produit'),(18,'Gilbert','homme.jpg',5,'très bien','des capsules de très bonne qualité; très bon rapport qualité prix; je recommande ce produit'),(26,'Juliette','femme.png',5,'comme à la maison','excellents biscuits; aussi bons que ceux que l\'on fait soi même!'),(19,'Corinne','femme.png',5,'très bon produit','des dosettes de très bonne qualité que je recommande'),(23,'Lila','femme.png',5,'bon produit','Une infusion excellente pour la digestion. Un gout acidulé et un très bon rapport qualité prix. Je recommande ce produit'),(21,'Julien','homme.jpg',4,'Parfumée','Une infusion très parfumée avec un gout authentique! très bon produit!'),(16,'Claudine','femme.png',5,'Bon produit','ce jus d\'orange est très parfumé. Peu c!alorique, et sans additif; naturel et sa pulpe est agréable. je vous le conseille'),(13,'Yvette','femme.png',5,'excellent','produit de qualité aussi bien en cuisine que pour désaltérer; je vous invite à l\'essayer!'),(15,'Sylvie','femme.png',4,'désaltérant','très bon produit; sans additif donc très naturel; à essayer sans hésiter!'),(14,'AHMED','homme.jpg',5,'excellent!','Un très bon produit; ce jus a un délicieux gout acidulé; parfait pour un gouter ou pour bien démarrer la journée!'),(24,'Claudette','femme.png',4,'savoureux','des macarons au top; un produit qui est de très bonne qualité; tendre et fondant dans la bouche; à consommer sans modération\r\n'),(10,'Hortense','femme.png',4,'à conseiller','un mélange idéal pour le petit déjeuner ou avant l\'effort; les amandes et les noisettes sont excellentes; je recommande'),(11,'LOUIS','homme.jpg',4,'exquis!','des fruits secs de bonne qualité; je recommande ce produit sans hésiter'),(9,'Catherine','femme.png',5,'idée cadeau','ce plateau très garni et excellent est une très bonne idée cadeau; à savourer sans modération ; je vous invite à essayer!'),(7,'Jules','homme.jpg',5,'avant l\'effort','des pruneaux d\'un gros calibre; idéal avant une activité sportive ou simplement en cas de petite faim! je recommande!'),(8,'Virginie','femme.png',5,'miam','Parfait pour commencer la journée: ces abricots sont excellents; le prix est raisonnable. Je recommande ce produit'),(12,'Severine','femme.png',3,'très bon produit','je recommande ces amandes grillées; elles sont grillées à point et complètent agréablement une recette; à essayer!'),(17,'sabrina','femme.png',5,'à essayer','un café doux et savoureux dans un emballage de qualité. le prix est raisonnable; je recommande!'),(20,'Dominique','homme.jpg',3,'bon','un produit de qualité; ce thé est parfumé et on apprécie le gout délicat de la cannelle; je vous le recommande.'),(22,'Sylvain','homme.jpg',5,'délicieux','une boisson très parfumée; idéale pour bien démarrer la journée; à essayer les yeux fermés.');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-13  1:18:48
