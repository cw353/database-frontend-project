-- MySQL dump 10.13  Distrib 5.7.34, for osx10.12 (x86_64)
--
-- Host: localhost    Database: project
-- ------------------------------------------------------
-- Server version	5.7.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `delivery`
--

DROP TABLE IF EXISTS `delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery` (
  `regionid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `supplierid` int(11) NOT NULL,
  PRIMARY KEY (`regionid`,`productid`,`supplierid`),
  KEY `productid` (`productid`),
  KEY `supplierid` (`supplierid`),
  CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`regionid`) REFERENCES `region` (`regionid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`productid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `delivery_ibfk_3` FOREIGN KEY (`supplierid`) REFERENCES `supplier` (`supplierid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery`
--

LOCK TABLES `delivery` WRITE;
/*!40000 ALTER TABLE `delivery` DISABLE KEYS */;
INSERT INTO `delivery` VALUES (3,1,3),(1,2,1),(2,3,2),(2,3,3),(1,4,4);
/*!40000 ALTER TABLE `delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver`
--

DROP TABLE IF EXISTS `driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver` (
  `driverid` int(11) NOT NULL,
  `driverlicenseno` char(10) NOT NULL,
  `drivername` varchar(35) NOT NULL,
  `drivernickname` varchar(20) DEFAULT NULL,
  `bonus` decimal(7,2) NOT NULL,
  PRIMARY KEY (`driverid`),
  UNIQUE KEY `driverlicenseno` (`driverlicenseno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver`
--

LOCK TABLES `driver` WRITE;
/*!40000 ALTER TABLE `driver` DISABLE KEYS */;
INSERT INTO `driver` VALUES (1,'62202687','Victoria Terpsichore','V.T.',4200.00),(2,'61403359','Ford Prefect',NULL,2350.00),(3,'37065795','Dorothy McShane','Ace',0.00);
/*!40000 ALTER TABLE `driver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipmentusage`
--

DROP TABLE IF EXISTS `equipmentusage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipmentusage` (
  `rtid` int(11) NOT NULL,
  `equipmentid` int(11) NOT NULL,
  PRIMARY KEY (`rtid`,`equipmentid`),
  KEY `equipmentid` (`equipmentid`),
  CONSTRAINT `equipmentusage_ibfk_1` FOREIGN KEY (`rtid`) REFERENCES `repairtechnician` (`rtid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `equipmentusage_ibfk_2` FOREIGN KEY (`equipmentid`) REFERENCES `repairequipment` (`equipmentid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipmentusage`
--

LOCK TABLES `equipmentusage` WRITE;
/*!40000 ALTER TABLE `equipmentusage` DISABLE KEYS */;
INSERT INTO `equipmentusage` VALUES (3,1),(2,2),(4,2),(4,3);
/*!40000 ALTER TABLE `equipmentusage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `productid` int(11) NOT NULL,
  `productname` varchar(30) NOT NULL,
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'Happy Masks'),(2,'Nutrimatic Drink Dispenser'),(3,'Costume Jewelry'),(4,'Cabbages');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `region`
--

DROP TABLE IF EXISTS `region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `region` (
  `regionid` int(11) NOT NULL,
  `regionname` varchar(20) NOT NULL,
  PRIMARY KEY (`regionid`),
  UNIQUE KEY `regionname` (`regionname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `region`
--

LOCK TABLES `region` WRITE;
/*!40000 ALTER TABLE `region` DISABLE KEYS */;
INSERT INTO `region` VALUES (1,'Akkala'),(3,'Faron'),(2,'Lanayru');
/*!40000 ALTER TABLE `region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repaircollaboration`
--

DROP TABLE IF EXISTS `repaircollaboration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repaircollaboration` (
  `rt1id` int(11) NOT NULL,
  `rt2id` int(11) NOT NULL,
  PRIMARY KEY (`rt1id`,`rt2id`),
  KEY `rt2id` (`rt2id`),
  CONSTRAINT `repaircollaboration_ibfk_1` FOREIGN KEY (`rt1id`) REFERENCES `repairtechnician` (`rtid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `repaircollaboration_ibfk_2` FOREIGN KEY (`rt2id`) REFERENCES `repairtechnician` (`rtid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repaircollaboration`
--

LOCK TABLES `repaircollaboration` WRITE;
/*!40000 ALTER TABLE `repaircollaboration` DISABLE KEYS */;
INSERT INTO `repaircollaboration` VALUES (1,2),(1,3),(2,3);
/*!40000 ALTER TABLE `repaircollaboration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repairequipment`
--

DROP TABLE IF EXISTS `repairequipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repairequipment` (
  `equipmentid` int(11) NOT NULL,
  `equipmentname` varchar(20) NOT NULL,
  PRIMARY KEY (`equipmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repairequipment`
--

LOCK TABLES `repairequipment` WRITE;
/*!40000 ALTER TABLE `repairequipment` DISABLE KEYS */;
INSERT INTO `repairequipment` VALUES (1,'lathe'),(2,'press'),(3,'air compressor');
/*!40000 ALTER TABLE `repairequipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repairtechnician`
--

DROP TABLE IF EXISTS `repairtechnician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repairtechnician` (
  `rtid` int(11) NOT NULL,
  `rtfname` varchar(15) NOT NULL,
  `rtlname` varchar(15) NOT NULL,
  PRIMARY KEY (`rtid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repairtechnician`
--

LOCK TABLES `repairtechnician` WRITE;
/*!40000 ALTER TABLE `repairtechnician` DISABLE KEYS */;
INSERT INTO `repairtechnician` VALUES (1,'Aule','Mahal'),(2,'Celebrimbor','O\'Eregion'),(3,'Telchar','O\'Nogrod'),(4,'Feanor','Finwion');
/*!40000 ALTER TABLE `repairtechnician` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shift`
--

DROP TABLE IF EXISTS `shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shift` (
  `driverid` int(11) NOT NULL,
  `shiftstartdate` date NOT NULL,
  `shiftstarttime` time NOT NULL,
  `shiftenddate` date NOT NULL,
  `shiftendtime` time NOT NULL,
  `hourlypay` decimal(4,2) NOT NULL,
  PRIMARY KEY (`driverid`,`shiftstartdate`,`shiftstarttime`),
  CONSTRAINT `shift_ibfk_1` FOREIGN KEY (`driverid`) REFERENCES `driver` (`driverid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shift`
--

LOCK TABLES `shift` WRITE;
/*!40000 ALTER TABLE `shift` DISABLE KEYS */;
INSERT INTO `shift` VALUES (1,'2022-09-28','06:00:00','2022-09-28','14:00:00',29.00),(1,'2022-09-30','09:00:00','2022-09-30','17:00:00',29.00),(2,'2022-09-28','06:00:00','2022-09-28','14:00:00',28.00),(2,'2022-09-28','17:30:00','2022-09-29','03:00:00',31.00),(3,'2022-09-28','10:00:00','2022-09-28','18:00:00',27.00);
/*!40000 ALTER TABLE `shift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier` (
  `supplierid` int(11) NOT NULL,
  `suppliername` varchar(30) NOT NULL,
  PRIMARY KEY (`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier`
--

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
INSERT INTO `supplier` VALUES (1,'Sirius Cybernetics Corporation'),(2,'Annatar Forges'),(3,'Happy Mask Corporation'),(4,'Cabbage Corporation');
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplierphone`
--

DROP TABLE IF EXISTS `supplierphone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplierphone` (
  `supplierid` int(11) NOT NULL,
  `supplierphone` char(10) NOT NULL,
  PRIMARY KEY (`supplierid`,`supplierphone`),
  CONSTRAINT `supplierphone_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `supplier` (`supplierid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplierphone`
--

LOCK TABLES `supplierphone` WRITE;
/*!40000 ALTER TABLE `supplierphone` DISABLE KEYS */;
INSERT INTO `supplierphone` VALUES (1,'9877755751'),(2,'5016740447'),(3,'2273061187'),(3,'4544511909'),(4,'4633544064');
/*!40000 ALTER TABLE `supplierphone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `truck`
--

DROP TABLE IF EXISTS `truck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `truck` (
  `truckid` int(11) NOT NULL,
  `lpstate` char(2) NOT NULL,
  `lpnumber` char(7) NOT NULL,
  `truckmodel` varchar(20) NOT NULL,
  `driverid` int(11) NOT NULL,
  `ownerid` int(11) NOT NULL,
  PRIMARY KEY (`truckid`),
  UNIQUE KEY `lpstate` (`lpstate`,`lpnumber`),
  KEY `driverid` (`driverid`),
  KEY `ownerid` (`ownerid`),
  CONSTRAINT `truck_ibfk_1` FOREIGN KEY (`driverid`) REFERENCES `driver` (`driverid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `truck_ibfk_2` FOREIGN KEY (`ownerid`) REFERENCES `driver` (`driverid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `truck`
--

LOCK TABLES `truck` WRITE;
/*!40000 ALTER TABLE `truck` DISABLE KEYS */;
INSERT INTO `truck` VALUES (1,'CA','6ACS473','BUCEPHALUS MK1',1,1),(2,'WA','B37751E','EPONA MK1',2,3),(3,'CT','AH52378','EPONA MK2',3,3);
/*!40000 ALTER TABLE `truck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `truckrepairs`
--

DROP TABLE IF EXISTS `truckrepairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `truckrepairs` (
  `rtid` int(11) NOT NULL,
  `truckid` int(11) NOT NULL,
  `repairdate` date NOT NULL,
  `repaircost` decimal(7,2) NOT NULL,
  PRIMARY KEY (`rtid`,`truckid`,`repairdate`),
  KEY `truckid` (`truckid`),
  CONSTRAINT `truckrepairs_ibfk_1` FOREIGN KEY (`rtid`) REFERENCES `repairtechnician` (`rtid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `truckrepairs_ibfk_2` FOREIGN KEY (`truckid`) REFERENCES `truck` (`truckid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `truckrepairs`
--

LOCK TABLES `truckrepairs` WRITE;
/*!40000 ALTER TABLE `truckrepairs` DISABLE KEYS */;
INSERT INTO `truckrepairs` VALUES (1,1,'2021-01-04',60100.00),(1,1,'2022-05-13',390.00),(2,2,'2018-03-03',67500.00),(3,1,'2022-05-13',863.00),(4,2,'2019-09-26',3720.00);
/*!40000 ALTER TABLE `truckrepairs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `truckservice`
--

DROP TABLE IF EXISTS `truckservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `truckservice` (
  `truckid` int(11) NOT NULL,
  `regionid` int(11) NOT NULL,
  PRIMARY KEY (`truckid`,`regionid`),
  KEY `regionid` (`regionid`),
  CONSTRAINT `truckservice_ibfk_1` FOREIGN KEY (`truckid`) REFERENCES `truck` (`truckid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `truckservice_ibfk_2` FOREIGN KEY (`regionid`) REFERENCES `region` (`regionid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `truckservice`
--

LOCK TABLES `truckservice` WRITE;
/*!40000 ALTER TABLE `truckservice` DISABLE KEYS */;
INSERT INTO `truckservice` VALUES (1,1),(1,2),(2,3),(3,3);
/*!40000 ALTER TABLE `truckservice` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-06 10:02:21
