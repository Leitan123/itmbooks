-- MySQL dump 10.13  Distrib 5.7.44, for osx11.0 (x86_64)
--
-- Host: 127.0.0.1    Database: dbvagmi
-- ------------------------------------------------------
-- Server version	8.0.44

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
-- Table structure for table `purchase_returns`
--

DROP TABLE IF EXISTS `purchase_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_returns` (
  `PurchaseReturnID` int NOT NULL AUTO_INCREMENT,
  `VendorID` int NOT NULL,
  `ReturnDate` date NOT NULL,
  `TotalReturnAmount` decimal(10,2) NOT NULL,
  `ReferenceNo` varchar(100) DEFAULT NULL,
  `Notes` text,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PurchaseReturnID`),
  KEY `VendorID` (`VendorID`),
  CONSTRAINT `fk_pr_vendor` FOREIGN KEY (`VendorID`) REFERENCES `vendor` (`VendorID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_return_items`
--

DROP TABLE IF EXISTS `purchase_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_return_items` (
  `PurchaseReturnItemID` int NOT NULL AUTO_INCREMENT,
  `PurchaseReturnID` int NOT NULL,
  `ItemID` int NOT NULL,
  `Quantity` decimal(10,2) NOT NULL,
  `QtyType` varchar(50) NOT NULL,
  `RefundPrice` decimal(10,2) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `IsDamaged` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PurchaseReturnItemID`),
  KEY `PurchaseReturnID` (`PurchaseReturnID`),
  KEY `ItemID` (`ItemID`),
  CONSTRAINT `fk_pri_item` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`),
  CONSTRAINT `fk_pri_return` FOREIGN KEY (`PurchaseReturnID`) REFERENCES `purchase_returns` (`PurchaseReturnID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_returns`
--

DROP TABLE IF EXISTS `sales_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_returns` (
  `SalesReturnID` int NOT NULL AUTO_INCREMENT,
  `CustomerId` int NOT NULL,
  `ReturnDate` date NOT NULL,
  `TotalReturnAmount` decimal(10,2) NOT NULL,
  `ReferenceNo` varchar(100) DEFAULT NULL,
  `Notes` text,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SalesReturnID`),
  KEY `CustomerId` (`CustomerId`),
  CONSTRAINT `fk_sr_customer` FOREIGN KEY (`CustomerId`) REFERENCES `customer` (`CustomerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_return_items`
--

DROP TABLE IF EXISTS `sales_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_return_items` (
  `SalesReturnItemID` int NOT NULL AUTO_INCREMENT,
  `SalesReturnID` int NOT NULL,
  `ItemID` int NOT NULL,
  `Quantity` decimal(10,2) NOT NULL,
  `QtyType` varchar(50) NOT NULL,
  `RefundPrice` decimal(10,2) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `IsDamaged` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`SalesReturnItemID`),
  KEY `SalesReturnID` (`SalesReturnID`),
  KEY `ItemID` (`ItemID`),
  CONSTRAINT `fk_sri_item` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`),
  CONSTRAINT `fk_sri_return` FOREIGN KEY (`SalesReturnID`) REFERENCES `sales_returns` (`SalesReturnID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouse` (
  `WarehouseID` int NOT NULL AUTO_INCREMENT,
  `WarehouseName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ContactPerson` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`WarehouseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `warehouse_stock`
--

DROP TABLE IF EXISTS `warehouse_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouse_stock` (
  `WarehouseStockID` int NOT NULL AUTO_INCREMENT,
  `WarehouseID` int NOT NULL,
  `ItemID` int NOT NULL,
  `Quantity` int DEFAULT '0',
  `LastUpdated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`WarehouseStockID`),
  UNIQUE KEY `idx_warehouse_item` (`WarehouseID`,`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-22 19:00:34
