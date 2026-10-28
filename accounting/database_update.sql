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
-- Table structure for table `account_transactions`
--

DROP TABLE IF EXISTS `account_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_transactions` (
  `TransactionID` int NOT NULL AUTO_INCREMENT,
  `TransactionDate` date DEFAULT NULL,
  `AccountID` int DEFAULT NULL,
  `TransactionType` varchar(50) DEFAULT NULL,
  `ReferenceID` int DEFAULT NULL,
  `Debit` decimal(18,2) DEFAULT '0.00',
  `Credit` decimal(18,2) DEFAULT '0.00',
  `Description` text,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`TransactionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_transactions`
--

LOCK TABLES `account_transactions` WRITE;
/*!40000 ALTER TABLE `account_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_types`
--

DROP TABLE IF EXISTS `account_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_types` (
  `AccountTypeID` int NOT NULL AUTO_INCREMENT,
  `AccountTypeName` varchar(100) NOT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AccountTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_types`
--

LOCK TABLES `account_types` WRITE;
/*!40000 ALTER TABLE `account_types` DISABLE KEYS */;
INSERT INTO `account_types` VALUES (1,'Assets','2026-05-17 09:19:32'),(2,'Liabilities','2026-05-17 09:19:32'),(3,'Equity','2026-05-17 09:19:32'),(4,'Income','2026-05-17 09:19:32'),(5,'Expenses','2026-05-17 09:19:32');
/*!40000 ALTER TABLE `account_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounting_defaults`
--

DROP TABLE IF EXISTS `accounting_defaults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounting_defaults` (
  `DefaultID` int NOT NULL AUTO_INCREMENT,
  `CashAccountID` int DEFAULT NULL,
  `BankAccountID` int DEFAULT NULL,
  `ReceivableAccountID` int DEFAULT NULL,
  `PayableAccountID` int DEFAULT NULL,
  `InventoryAccountID` int DEFAULT NULL,
  `CapitalAccountID` int DEFAULT NULL,
  `SalesAccountID` int DEFAULT NULL,
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`DefaultID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounting_defaults`
--

LOCK TABLES `accounting_defaults` WRITE;
/*!40000 ALTER TABLE `accounting_defaults` DISABLE KEYS */;
/*!40000 ALTER TABLE `accounting_defaults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_accounts`
--

DROP TABLE IF EXISTS `bank_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_accounts` (
  `BankID` int NOT NULL AUTO_INCREMENT,
  `BankName` varchar(150) DEFAULT NULL,
  `AccountName` varchar(150) DEFAULT NULL,
  `AccountNumber` varchar(100) DEFAULT NULL,
  `Branch` varchar(150) DEFAULT NULL,
  `OpeningBalance` decimal(15,2) DEFAULT '0.00',
  `CurrentBalance` decimal(15,2) DEFAULT '0.00',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`BankID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_accounts`
--

LOCK TABLES `bank_accounts` WRITE;
/*!40000 ALTER TABLE `bank_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_opening_balance`
--

DROP TABLE IF EXISTS `bank_opening_balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_opening_balance` (
  `BankOpeningID` int NOT NULL AUTO_INCREMENT,
  `BankID` int DEFAULT NULL,
  `OpeningBalance` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`BankOpeningID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_opening_balance`
--

LOCK TABLES `bank_opening_balance` WRITE;
/*!40000 ALTER TABLE `bank_opening_balance` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_opening_balance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_reconciliation`
--

DROP TABLE IF EXISTS `bank_reconciliation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_reconciliation` (
  `ReconciliationID` int NOT NULL AUTO_INCREMENT,
  `BankID` int NOT NULL,
  `ReconciliationDate` date NOT NULL,
  `BankStatementBalance` decimal(18,2) DEFAULT '0.00',
  `Notes` text,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReconciliationID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_reconciliation`
--

LOCK TABLES `bank_reconciliation` WRITE;
/*!40000 ALTER TABLE `bank_reconciliation` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_reconciliation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_transactions`
--

DROP TABLE IF EXISTS `bank_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_transactions` (
  `TransactionID` int NOT NULL AUTO_INCREMENT,
  `BankID` int DEFAULT NULL,
  `TransactionDate` date DEFAULT NULL,
  `TransactionType` enum('Deposit','Withdrawal') DEFAULT NULL,
  `ReferenceNo` varchar(100) DEFAULT NULL,
  `Description` text,
  `Amount` decimal(15,2) DEFAULT NULL,
  `IsReconciled` tinyint(1) DEFAULT '0',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`TransactionID`),
  KEY `BankID` (`BankID`),
  CONSTRAINT `bank_transactions_ibfk_1` FOREIGN KEY (`BankID`) REFERENCES `bank_accounts` (`BankID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_transactions`
--

LOCK TABLES `bank_transactions` WRITE;
/*!40000 ALTER TABLE `bank_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banks` (
  `BankID` int NOT NULL AUTO_INCREMENT,
  `BankCode` varchar(20) DEFAULT NULL,
  `BankName` varchar(150) NOT NULL,
  `AccountName` varchar(150) DEFAULT NULL,
  `AccountNumber` varchar(100) DEFAULT NULL,
  `Branch` varchar(100) DEFAULT NULL,
  `OpeningBalance` decimal(18,2) DEFAULT '0.00',
  `IsDeleted` tinyint(1) DEFAULT '0',
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `AccountID` int DEFAULT NULL,
  PRIMARY KEY (`BankID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand` (
  `BrandID` int NOT NULL AUTO_INCREMENT,
  `CategoryID` int DEFAULT NULL,
  `BrandName` varchar(255) NOT NULL,
  `BrandDescription` text,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`BrandID`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand`
--

LOCK TABLES `brand` WRITE;
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `capital_accounts`
--

DROP TABLE IF EXISTS `capital_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `capital_accounts` (
  `CapitalID` int NOT NULL AUTO_INCREMENT,
  `CompanyID` int DEFAULT NULL,
  `CapitalAmount` decimal(18,2) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CapitalID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `capital_accounts`
--

LOCK TABLES `capital_accounts` WRITE;
/*!40000 ALTER TABLE `capital_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `capital_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `CategoryID` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(255) NOT NULL,
  `CategoryDescription` text,
  `CreatedAt` datetime DEFAULT NULL,
  `UpdatedAt` datetime DEFAULT NULL,
  `ModifiedBy` int DEFAULT NULL,
  `ModifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`CategoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'bank','','2026-07-30 11:32:08',NULL,NULL,'2026-07-30 06:02:08',0),(2,'Art paper','','2026-07-30 11:40:09',NULL,NULL,'2026-07-30 06:10:09',0),(3,'Art board','','2026-07-30 11:40:19',NULL,NULL,'2026-07-30 06:10:19',0),(4,'Ivory board','','2026-07-30 11:40:51',NULL,NULL,'2026-07-30 06:10:51',0),(5,'Ice shine','','2026-07-30 11:41:06',NULL,NULL,'2026-07-30 06:11:06',0),(6,'cream shine','','2026-07-30 11:41:19',NULL,NULL,'2026-07-30 06:11:19',0),(7,'Sticker','','2026-07-30 11:41:34',NULL,NULL,'2026-07-30 06:11:34',0),(8,'Matte Ivory','','2026-07-30 11:42:04',NULL,NULL,'2026-07-30 06:12:04',0),(9,'Trasing','','2026-07-30 11:42:30',NULL,NULL,'2026-07-30 06:12:30',0),(10,'Print','','2026-07-30 11:42:41',NULL,NULL,'2026-07-30 06:12:41',0),(11,'Photo sheet','','2026-07-30 11:42:54',NULL,NULL,'2026-07-30 06:12:54',0),(12,'Mug','','2026-07-30 11:43:06',NULL,NULL,'2026-07-30 06:13:06',0),(13,'Photo frame','','2026-07-30 11:43:17',NULL,NULL,'2026-07-30 06:13:17',0);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chart_of_accounts`
--

DROP TABLE IF EXISTS `chart_of_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chart_of_accounts` (
  `AccountID` int NOT NULL AUTO_INCREMENT,
  `AccountCode` varchar(20) NOT NULL,
  `AccountName` varchar(255) NOT NULL,
  `AccountTypeID` int NOT NULL,
  `OpeningBalance` decimal(18,2) DEFAULT '0.00',
  `CurrentBalance` decimal(18,2) DEFAULT '0.00',
  `Status` tinyint(1) DEFAULT '1',
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AccountID`),
  KEY `AccountTypeID` (`AccountTypeID`),
  CONSTRAINT `chart_of_accounts_ibfk_1` FOREIGN KEY (`AccountTypeID`) REFERENCES `account_types` (`AccountTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_of_accounts`
--

LOCK TABLES `chart_of_accounts` WRITE;
/*!40000 ALTER TABLE `chart_of_accounts` DISABLE KEYS */;
INSERT INTO `chart_of_accounts` VALUES (1,'1001','Cash Account',1,0.00,4786087.99,1,'2026-05-17 09:20:03'),(2,'1002','Bank Account',1,0.00,-5550.00,1,'2026-05-17 09:20:03'),(3,'1100','Accounts Receivable',1,0.00,-7719822.99,1,'2026-05-17 09:20:03'),(4,'1200','Inventory',1,0.00,2007532.00,1,'2026-05-17 09:20:03'),(5,'2001','Accounts Payable',2,0.00,1123647.00,1,'2026-05-17 09:20:03'),(6,'3001','Capital Account',3,0.00,0.00,1,'2026-05-17 09:20:03'),(7,'4001','Sales Income',4,0.00,0.00,1,'2026-05-17 09:20:03'),(8,'4002','Service Income',4,0.00,0.00,1,'2026-05-17 09:20:03'),(9,'5001','Purchase Expense',5,0.00,8000.00,1,'2026-05-17 09:20:03'),(10,'5002','Salary Expense',5,0.00,690600.00,1,'2026-05-17 09:20:03'),(11,'5003','Electricity Expense',5,0.00,20000.00,1,'2026-05-17 09:20:03'),(12,'5004','Transport Expense',5,0.00,10200.00,1,'2026-05-17 09:20:03'),(13,'5005','Office Expense',5,0.00,0.00,1,'2026-05-17 09:20:03'),(14,'5006','Hosting Cost',5,0.00,53500.00,1,'2026-05-31 21:19:39'),(15,'5007','Domain Cost',5,0.00,40000.00,1,'2026-05-31 21:19:39'),(16,'5008','Email Services',5,0.00,452000.00,1,'2026-05-31 21:19:39'),(17,'5009','Server Cost',5,0.00,12000.00,1,'2026-05-31 21:19:39'),(18,'5010','Software Subscription',5,0.00,0.00,1,'2026-05-31 21:19:39'),(19,'5011','Internet Expense',5,0.00,5000.00,1,'2026-05-31 21:19:39'),(20,'5012','Telephone Expense',5,0.00,15500.00,1,'2026-05-31 21:19:39'),(21,'5013','Rent Expense',5,0.00,0.00,1,'2026-05-31 21:19:39'),(22,'5014','Fuel Expense',5,0.00,50500.00,1,'2026-05-31 21:19:39'),(23,'5015','Vehicle Expense',5,0.00,0.00,1,'2026-05-31 21:19:39'),(24,'5016','Commission Expense',5,0.00,22500.00,1,'2026-05-31 21:19:39'),(25,'5017','Advertising Expense',5,0.00,19000.00,1,'2026-05-31 21:19:39'),(26,'5018','Client Entertainment',5,0.00,0.00,1,'2026-05-31 21:19:39'),(27,'5019','Legal Charges',5,0.00,0.00,1,'2026-05-31 21:19:39'),(28,'5020','Accounting Fee',5,0.00,0.00,1,'2026-05-31 21:19:39'),(29,'5021','Bank Charges',5,0.00,10200.00,1,'2026-05-31 21:19:39'),(30,'5022','Loan Repayment',5,0.00,0.00,1,'2026-05-31 21:19:39'),(31,'5023','Staff Welfare',5,0.00,5000.00,1,'2026-05-31 21:19:39'),(32,'5024','ChatGPT Subscription',5,0.00,0.00,1,'2026-05-31 21:19:39'),(33,'5025','Zoho Subscription',5,0.00,15000.00,1,'2026-05-31 21:19:39'),(34,'5018','EPF Contribution Expense',5,0.00,82200.00,1,'2026-06-04 18:54:45'),(35,'5019','ETF Contribution Expense',5,0.00,14550.00,1,'2026-06-04 18:54:45'),(36,'1101','HNB Current Account',1,0.00,-52200.00,1,'2026-06-05 17:16:01'),(37,'1102','HNB Saving Account',1,0.00,0.00,1,'2026-06-05 17:16:01'),(38,'1103','NTB Current Account',1,0.00,-94550.00,1,'2026-06-05 17:16:01'),(39,'1104','Sampath Bank Saving Account',1,0.00,-95240.00,1,'2026-06-05 17:16:01'),(40,'1300','Investment Account',1,0.00,100000.00,1,'2026-06-05 17:18:52'),(41,'1003','Petty Cash',1,0.00,0.00,1,'2026-06-11 11:17:23'),(42,'1004','Undeposited Funds',1,0.00,0.00,1,'2026-06-11 11:17:23'),(43,'1201','Prepaid Expenses',1,0.00,0.00,1,'2026-06-11 11:17:23'),(44,'1202','Security Deposits',1,0.00,0.00,1,'2026-06-11 11:17:23'),(45,'1301','Office Equipment',1,0.00,0.00,1,'2026-06-11 11:17:23'),(46,'1302','Computer Equipment',1,0.00,0.00,1,'2026-06-11 11:17:23'),(47,'1303','Furniture & Fixtures',1,0.00,0.00,1,'2026-06-11 11:17:23'),(48,'1304','Motor Vehicles',1,0.00,0.00,1,'2026-06-11 11:17:23'),(49,'2002','Accrued Expenses',2,0.00,0.00,1,'2026-06-11 11:17:46'),(50,'2003','VAT Payable',2,0.00,0.00,1,'2026-06-11 11:17:46'),(51,'2004','EPF Payable',2,0.00,-28000.00,1,'2026-06-11 11:17:46'),(52,'2005','ETF Payable',2,0.00,-4200.00,1,'2026-06-11 11:17:46'),(53,'2006','PAYE Payable',2,0.00,0.00,1,'2026-06-11 11:17:46'),(54,'2007','Sampath Bank Loan',2,0.00,0.00,1,'2026-06-11 11:17:46'),(55,'2008','Director Loan Account',2,0.00,-250000.00,1,'2026-06-11 11:17:46'),(56,'3002','Drawings Account',3,0.00,0.00,1,'2026-06-11 11:18:04'),(57,'3003','Retained Earnings',3,0.00,0.00,1,'2026-06-11 11:18:04'),(58,'3004','Current Year Profit/Loss',3,0.00,0.00,1,'2026-06-11 11:18:04'),(59,'4003','Domain Registration Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(60,'4004','Hosting Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(61,'4005','Software Development Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(62,'4006','Maintenance Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(63,'4007','Consultancy Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(64,'4008','Commission Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(65,'4009','Other Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(66,'5017','Office Rent Expense',5,0.00,31720.00,1,'2026-06-11 11:18:21'),(67,'5018','Bank Charges',5,0.00,0.00,1,'2026-06-11 11:18:21'),(68,'5019','Printing & Stationery Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(69,'5020','Repairs & Maintenance',5,0.00,0.00,1,'2026-06-11 11:18:21'),(70,'5021','Marketing & Advertising Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(72,'5023','ETF Contribution Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(73,'5024','Professional Fees',5,0.00,0.00,1,'2026-06-11 11:18:21'),(74,'5025','Training Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(75,'5026','Entertainment Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(76,'5027','Cleaning Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(77,'5028','Insurance Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(78,'5029','Office Supplies Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(79,'5030','Other Administrative Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(81,'2009','Housing Loan',2,0.00,500000.00,1,'2026-06-17 10:42:06'),(82,'1203','Salary Advances / Employee Loans',1,0.00,0.00,1,'2026-06-19 17:36:37'),(85,'3005','Drawings - Jeyakala',3,0.00,160000.00,1,'2026-06-26 14:53:34'),(87,'3006','Drawings - Hariharan',3,0.00,91000.00,1,'2026-06-26 18:01:23'),(88,'5000','Operating Expenses',5,0.00,5500.00,1,'2026-06-30 11:17:00'),(89,'5100','Vehicle Expenses',5,0.00,0.00,1,'2026-06-30 11:17:28'),(90,'5110','Car Lease Expense',5,0.00,60000.00,1,'2026-06-30 11:18:04'),(91,'5120','Car Rental Expense',5,0.00,90000.00,1,'2026-06-30 11:18:23'),(92,'5121','Water Bill',5,0.00,0.00,1,'2026-07-14 03:51:18'),(93,'5122','CSR / Donation',5,0.00,0.00,1,'2026-07-14 11:52:23');
/*!40000 ALTER TABLE `chart_of_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `CompanyID` int NOT NULL AUTO_INCREMENT,
  `CompanyCode` varchar(20) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `Address` text,
  `Telephone` varchar(50) DEFAULT NULL,
  `Mobile` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Website` varchar(100) DEFAULT NULL,
  `RegistrationNo` varchar(100) DEFAULT NULL,
  `TaxNo` varchar(100) DEFAULT NULL,
  `Currency` varchar(20) DEFAULT NULL,
  `Logo` varchar(255) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT '1',
  `CreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CompanyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_settings`
--

DROP TABLE IF EXISTS `company_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_settings` (
  `CompanyID` int NOT NULL AUTO_INCREMENT,
  `CompanyName` varchar(255) DEFAULT NULL,
  `Address` text,
  `Telephone` varchar(50) DEFAULT NULL,
  `Mobile` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Website` varchar(100) DEFAULT NULL,
  `RegistrationNo` varchar(100) DEFAULT NULL,
  `TaxNo` varchar(100) DEFAULT NULL,
  `Logo` varchar(255) DEFAULT NULL,
  `FinancialYearStart` date DEFAULT NULL,
  `Currency` varchar(20) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `ThemeColor` varchar(50) DEFAULT '#2f3d4f',
  PRIMARY KEY (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_settings`
--

LOCK TABLES `company_settings` WRITE;
/*!40000 ALTER TABLE `company_settings` DISABLE KEYS */;
INSERT INTO `company_settings` VALUES (1,'VAGMI CREATIONS','12, 1st Floor, Maliban Street, Colombo 11','0112323423','0777763394','vagmi.creationss@gmail.com','','','','logo_1785218009.png',NULL,NULL,'2026-06-02 15:59:15',NULL,'#ff7c24');
/*!40000 ALTER TABLE `company_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_notes`
--

DROP TABLE IF EXISTS `credit_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_notes` (
  `CreditNoteID` int NOT NULL AUTO_INCREMENT,
  `SaleID` int NOT NULL,
  `CreditNoteNo` varchar(50) DEFAULT NULL,
  `CreditDate` date DEFAULT NULL,
  `CustomerID` int DEFAULT NULL,
  `TotalSaleAmount` decimal(10,2) DEFAULT NULL,
  `CreditAmount` decimal(10,2) DEFAULT NULL,
  `Reason` text,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CreditNoteID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_notes`
--

LOCK TABLES `credit_notes` WRITE;
/*!40000 ALTER TABLE `credit_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `credit_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `CustomerId` int NOT NULL AUTO_INCREMENT,
  `CompanyName` varchar(155) DEFAULT NULL,
  `CustomerNo` varchar(50) NOT NULL,
  `RegDate` date NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Sid` varchar(50) DEFAULT NULL,
  `CustomerName` varchar(100) NOT NULL,
  `MobileNo` varchar(15) NOT NULL,
  `Address` text,
  `CustomerType` enum('COD','Credit') DEFAULT 'COD',
  `CreditLimit` decimal(10,2) DEFAULT '0.00',
  `OpeningBalance` decimal(10,2) DEFAULT '0.00',
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` int DEFAULT NULL,
  `ModifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `Email` varchar(255) DEFAULT NULL,
  `Website` varchar(255) DEFAULT NULL,
  `TRNNo` varchar(255) DEFAULT NULL,
  `ContactPerson` varchar(255) DEFAULT NULL,
  `WhatsAppNo` varchar(50) DEFAULT NULL,
  `CustomerGroup` varchar(100) DEFAULT NULL,
  `DomainName` varchar(255) DEFAULT NULL,
  `HostingExpiry` date DEFAULT NULL,
  `ServiceExpiry` date DEFAULT NULL,
  `SubscriptionStatus` enum('Active','Expired','Suspended','Sent renewal invoice','Paid') DEFAULT 'Active',
  `LastRenewalDate` date DEFAULT NULL,
  `RenewalNotified` tinyint(1) DEFAULT '0',
  `Notes` text,
  `CustomerTypeZoho` enum('Business','Individual') DEFAULT 'Business',
  `Salutation` varchar(50) DEFAULT NULL,
  `FirstName` varchar(155) DEFAULT NULL,
  `LastName` varchar(155) DEFAULT NULL,
  `Language` varchar(100) DEFAULT 'English',
  `TaxRate` varchar(100) DEFAULT NULL,
  `CompanyID` varchar(100) DEFAULT NULL,
  `Currency` varchar(100) DEFAULT 'LKR',
  `AccountsReceivable` varchar(155) DEFAULT NULL,
  `PaymentTerms` varchar(100) DEFAULT 'Due on Receipt',
  `EnablePortal` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`CustomerId`),
  KEY `MobileNo` (`MobileNo`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'New Sriyaa Enterprices','00001','2026-07-30','',NULL,'NEW SRIYAA','0719131374','','Credit',100000.00,0.00,NULL,'2026-07-30 13:59:46',NULL,'2026-08-07 08:20:44',0,'newsriyaa@gmail.com','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','Mr.','Chula','','English','','','LKR','','Due on Receipt',0),(2,'','00002','2026-07-30','',NULL,'Mr.Romex','0756432459','','COD',0.00,0.00,NULL,'2026-07-30 14:05:22',NULL,'2026-07-30 08:36:39',1,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Individual','Ms.','Romex 48','','English','','','LKR','','Due on Receipt',0),(3,'Romex Wedding Cards','00002','2026-07-30','0112336393',NULL,'Romex Wedding Cards','+94756432459','No. 48, Malliban Street, Colombo-11','Credit',0.00,0.00,NULL,'2026-07-30 14:07:18',NULL,'2026-08-06 11:29:33',0,'romax.weddingcards@gmail.com','',NULL,'','+94112336393','','',NULL,NULL,'Paid',NULL,0,'','Business','','S. Vishal','','English','','','LKR','','Due on Receipt',0),(4,'ART & CRAFT','00003','2026-07-30','',NULL,'ART & CRAFT','0752852805','','Credit',50000.00,0.00,NULL,'2026-07-30 14:08:24',NULL,'2026-08-04 09:41:30',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','Mr.','Anver','','English','','','LKR','','Due on Receipt',0),(5,'SVS','00004','2026-07-30','0112337042',NULL,'SVS Wedding Cards','0776223146','','COD',0.00,0.00,NULL,'2026-07-30 14:09:49',NULL,'2026-08-11 10:29:19',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','Ms.','Raj','','English','','','LKR','','Due on Receipt',0),(6,'SQK Printers','00005','2026-07-30','',NULL,'SQK Printers','0777534144','','Credit',0.00,0.00,NULL,'2026-07-30 14:10:50',NULL,'2026-08-04 09:29:47',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','Ms.','SQK','','English','','','LKR','','Due on Receipt',0),(7,'Lassana Cards','00006','2026-07-30','',NULL,'Lassana Cards','0775113746','','COD',0.00,0.00,NULL,'2026-07-30 14:12:39',NULL,'2026-08-04 09:49:03',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','Mr.','Yoga','','English','','','LKR','','Due on Receipt',0),(8,'Mani Printers','00007','2026-07-30','',NULL,'Mani Printer','0720826413','','Credit',25000.00,0.00,NULL,'2026-07-30 14:13:45',NULL,'2026-08-04 07:51:48',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','Mr.','Mani','Mani','English','','','LKR','','Due on Receipt',0),(9,'Manjula','00008','2026-07-30','',NULL,'Mrs. Manjula','0773022240','','Credit',0.00,0.00,NULL,'2026-07-30 14:16:09',NULL,'2026-08-04 09:14:07',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Individual','Mrs.','Manjula Balachandran','','English','','','LKR','','Due on Receipt',0),(10,'MS Printers','00009','2026-07-30','',NULL,'MS Printers','0774309341','','Credit',0.00,0.00,NULL,'2026-07-30 14:19:17',NULL,'2026-08-04 05:02:36',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','Mr.','Manoj','','English','','','LKR','','Due on Receipt',0),(11,'sudath negombo','00010','2026-08-04','',NULL,'Sudath Negombo','0777708350','','Credit',10000.00,0.00,NULL,'2026-08-04 10:34:05',NULL,'2026-08-04 07:52:15',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Sudath (Negombo)','','English','','','LKR','','Due on Receipt',0),(12,'Arun wedding cards','00011','2026-08-04','',NULL,'Arun wedding cards','0723203837','','COD',0.00,0.00,NULL,'2026-08-04 11:17:58',NULL,'2026-08-06 05:48:56',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','arun','','English','','','LKR','','Due on Receipt',0),(13,'MS Printers','00012','2026-08-04','',NULL,'MS Printers','0774309341','','COD',0.00,0.00,NULL,'2026-08-04 11:19:55',NULL,'2026-08-07 11:00:49',1,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Manoj','','English','','','LKR','','Due on Receipt',0),(14,'Burani Papermart','00013','2026-08-04','0112454469',NULL,'Burani Papermart','0773503297','','COD',0.00,0.00,NULL,'2026-08-04 11:26:34',NULL,'2026-08-04 09:34:42',0,'burhani33@gmail.com','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Burani','','English','','','LKR','','Due on Receipt',0),(15,'A2Z ','00014','2026-08-04','',NULL,'A2Z','0777634600','','COD',0.00,0.00,NULL,'2026-08-04 11:29:19',NULL,'2026-08-04 05:59:19',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Pradeep','','English','','','LKR','','Due on Receipt',0),(16,'Sathies Kala Printing','00015','2026-08-04','',NULL,'Sathies Kala Printing','0768974069','','COD',0.00,0.00,NULL,'2026-08-04 11:31:47',NULL,'2026-08-04 07:10:08',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Sathies','','English','','','LKR','','Due on Receipt',0),(17,'Dileetha Stationery','00016','2026-08-04','',NULL,'Dileetha Stationery','0777513867','','COD',0.00,0.00,NULL,'2026-08-04 11:36:33',NULL,'2026-08-13 11:09:38',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Dileetha','','English','','','LKR','','Due on Receipt',0),(18,'Ali bhai','00017','2026-08-04','',NULL,'Ali bhai','0777275153','','COD',0.00,0.00,NULL,'2026-08-04 11:37:17',NULL,'2026-08-04 06:07:17',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Ali bhai','','English','','','LKR','','Due on Receipt',0),(19,'Jenson.lk','00018','2026-08-04','',NULL,'Jenson.lk','0755732505','','Credit',75000.00,0.00,NULL,'2026-08-04 11:38:22',NULL,'2026-08-04 09:13:49',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','Mr.','Jenson','','English','','','LKR','','Due on Receipt',0),(20,'Anoshan traders','00019','2026-08-04','0112459235',NULL,'Anoshan traders',' 077 638 8182','','COD',0.00,0.00,NULL,'2026-08-04 11:42:19',NULL,'2026-08-04 09:47:16',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Anoshan','','English','','','LKR','','Due on Receipt',0),(21,'Aqua wedding','00020','2026-08-04','',NULL,'Aqua wedding','0773205320','','COD',0.00,0.00,NULL,'2026-08-04 11:43:37',NULL,'2026-08-04 06:13:37',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Azam','','English','','','LKR','','Due on Receipt',0),(22,'Sathya offset','00021','2026-08-04','',NULL,'Sathya offset','0771599974','','COD',0.00,0.00,NULL,'2026-08-04 11:44:36',NULL,'2026-08-12 08:21:10',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Sathya','','English','','','LKR','','Due on Receipt',0),(23,'Screen line','00022','2026-08-04','',NULL,'Screen line','0774417047','','COD',0.00,0.00,NULL,'2026-08-04 11:45:20',NULL,'2026-08-04 06:15:20',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Screen line','','English','','','LKR','','Due on Receipt',0),(24,'Smart print','00023','2026-08-04','',NULL,'Smart print','0777123348','','COD',0.00,0.00,NULL,'2026-08-04 11:45:57',NULL,'2026-08-11 06:37:53',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Smart print','','English','','','LKR','','Due on Receipt',0),(25,'Asian Trading Company','00024','2026-08-04','0112543141',NULL,'Asian Trading Company','0773444102','','COD',0.00,0.00,NULL,'2026-08-04 11:47:39',NULL,'2026-08-04 06:17:39',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Asian','','English','','','LKR','','Due on Receipt',0),(26,'AM Com','00025','2026-08-04','112 423 241',NULL,'AM Com','112 423 241','','COD',0.00,0.00,NULL,'2026-08-04 12:54:43',NULL,'2026-08-04 07:24:43',0,'info@amcomgroup.com','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','AM Com','','English','','','LKR','','Due on Receipt',0),(27,'Dharshan Graphics','00026','2026-08-01','',NULL,'Dharshan Graphics','0777863106','','Credit',0.00,0.00,NULL,'2026-08-04 13:17:46',NULL,'2026-08-04 07:47:46',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','Mr.','David','','English','','','LKR','','Due on Receipt',0),(28,'Sakthivel & Co.','00027','2026-07-01','0112347855',NULL,'Sakthivel & Co','0777531069','','Credit',25000.00,0.00,NULL,'2026-08-04 13:20:10',NULL,'2026-08-04 07:50:10',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','Mr.','Gavaskar','Sakthivel','English','','','LKR','','Due on Receipt',0),(29,'Crafting Hawi','00028','2026-08-04','',NULL,'Crafting Hawi','0767869288','','COD',0.00,0.00,NULL,'2026-08-04 13:54:48',NULL,'2026-08-04 08:24:48',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Kalutara','','English','','','LKR','','Due on Receipt',0),(30,'AS Printers','00029','2026-08-04','',NULL,'AS Printers','0775469699','','COD',0.00,0.00,NULL,'2026-08-04 14:33:30',NULL,'2026-08-04 09:03:30',0,'asprinters28@gmail.com','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Susi','','English','','','LKR','','Due on Receipt',0),(31,'','00030','2026-08-05','',NULL,'Walk in customer','0112323423','','COD',0.00,0.00,NULL,'2026-08-05 09:58:42',NULL,'2026-08-05 04:36:16',1,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Individual','','Walk in customer','','English','','','LKR','','Due on Receipt',0),(32,'CASH BILL','00031','2026-08-05','',NULL,'CASH BILL','0112323423','','COD',0.00,0.00,NULL,'2026-08-05 10:05:52',NULL,'2026-08-13 06:37:56',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Walk in customer','','English','','','LKR','','Due on Receipt',0),(33,'ORDER BILL','00031','2026-08-06','',NULL,'ORDER BILL','0112323423','','COD',0.00,0.00,NULL,'2026-08-06 10:12:26',NULL,'2026-08-06 06:53:35',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','ORDER BILL','','English','','','LKR','','Due on Receipt',0),(34,'Nature\'s Remedies (Pvt) Ltd','00032','2026-08-01','077 723 0034',NULL,'Nature\'s Remedies (Pvt) Ltd.','0706230034','4 Ratmalana - Attidiya Rd, Dehiwala-Mount Lavinia, Sri Lanka','COD',0.00,0.00,NULL,'2026-08-06 16:00:46',NULL,'2026-08-06 10:30:46',0,'','',NULL,'','+94706230034','','',NULL,NULL,'Active',NULL,0,'','Business','Ms.','','','English','','','LKR','','Due on Receipt',0),(35,'HEC China Harbour','00033','2026-07-01','',NULL,'HEC China Harbour','0765684410','','COD',0.00,0.00,NULL,'2026-08-06 16:05:54',NULL,'2026-08-07 06:21:33',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','Mr.','Mahesh','','English','','','LKR','','Due on Receipt',0),(36,'Bridal Fabrics (Pvt) Ltd','00034','2026-07-01','',NULL,'Bridal Fabrics (Pvt) Ltd','0765718840','','COD',0.00,0.00,NULL,'2026-08-06 16:08:21',NULL,'2026-08-07 06:19:44',0,'','',NULL,'','+94765718840','','',NULL,NULL,'Paid',NULL,0,'','Business','Mr.','Isham','','English','','','LKR','','Due on Receipt',0),(37,'ZTE Lanka (Pvt) Ltd','00035','2026-08-06','0112307777',NULL,'ZTE Lanka (Pvt) Ltd','+94718644610','','COD',0.00,0.00,NULL,'2026-08-06 16:24:59',NULL,'2026-08-06 10:54:59',0,'','',NULL,'','+94718644610','','',NULL,NULL,'Active',NULL,0,'','Business','Mrs.','Thilanka','','English','','','LKR','','Due on Receipt',0),(38,'Hiru Rice Productions (Pvt) Ltd','00036','2026-08-06','',NULL,'Hiru Rice Productions (Pvt) Ltd','0763117496','','COD',0.00,0.00,NULL,'2026-08-06 16:57:09',NULL,'2026-08-10 11:25:13',0,'','',NULL,'','+94763117496','','',NULL,NULL,'Paid',NULL,0,'','Business','','','','English','','','LKR','','Due on Receipt',0),(39,'VALAI.K CAFE','00037','2026-08-06','',NULL,'VALAI.K CAFE','0774347478','','COD',0.00,0.00,NULL,'2026-08-06 17:14:23',NULL,'2026-08-06 11:44:23',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','VALAI.K CAFE','','English','','','LKR','','Due on Receipt',0),(40,'UD Services','00038','2026-08-06','',NULL,'UD Services','0772777754','47/1C Wewaihala Road, Jamburaliya, Madpatha','COD',0.00,0.00,NULL,'2026-08-06 17:18:43',NULL,'2026-08-06 11:48:43',0,'','',NULL,'','+94772777754','','',NULL,NULL,'Active',NULL,0,'','Business','Mr.','Upal','','English','','','LKR','','Due on Receipt',0),(41,'Kamal Mahendra','00039','2026-08-07','',NULL,'Kamal Mahendra','0112323423','','COD',0.00,0.00,NULL,'2026-08-07 10:50:46',NULL,'2026-08-11 04:51:42',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Kamal Mahendra','','English','','','LKR','','Due on Receipt',0),(42,'MCS Trading','00040','2026-08-07','',NULL,'MCS Trading','0776773399','','COD',0.00,0.00,NULL,'2026-08-07 10:54:24',NULL,'2026-08-07 06:10:21',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','MCS Trading','','English','','','LKR','','Due on Receipt',0),(43,'Puppy CO','00041','2026-07-02','',NULL,'Puppy CO','0777822413','','COD',0.00,0.00,NULL,'2026-08-07 10:57:39',NULL,'2026-08-07 05:27:39',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Saman','','English','','','LKR','','Due on Receipt',0),(44,'Micro Health Care','00042','2026-08-07','',NULL,'Micro Health Care',' 0112 506 636','','COD',0.00,0.00,NULL,'2026-08-07 11:01:59',NULL,'2026-08-10 11:29:36',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Micro Health Care','','English','','','LKR','','Due on Receipt',0),(45,'Vivekananda society','00043','2026-08-07','',NULL,'Vivekananda society',' 0112 422 646','','COD',0.00,0.00,NULL,'2026-08-07 11:04:21',NULL,'2026-08-13 09:09:07',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','Vivekananda society','','English','','','LKR','','Due on Receipt',0),(46,'528','00044','2026-08-07','',NULL,'528','0756565647','','COD',0.00,0.00,NULL,'2026-08-07 11:06:44',NULL,'2026-08-07 06:09:22',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','528','','English','','','LKR','','Due on Receipt',0),(47,'530','00045','2026-08-07','',NULL,'530','0767246620','','COD',0.00,0.00,NULL,'2026-08-07 11:08:37',NULL,'2026-08-07 05:38:37',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','530','','English','','','LKR','','Due on Receipt',0),(48,'Director police head quarters','00046','2026-08-07','',NULL,'Director police head quarters','0112444483','','COD',0.00,0.00,NULL,'2026-08-07 11:15:42',NULL,'2026-08-07 05:45:42',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Director police head quarters','','English','','','LKR','','Due on Receipt',0),(49,'EX PSD','00047','2026-08-07','',NULL,'EX PSD','0112444483','','COD',0.00,0.00,NULL,'2026-08-07 11:17:38',NULL,'2026-08-07 05:47:38',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','EX PSD','','English','','','LKR','','Due on Receipt',0),(50,'Hiru Promotion','00048','2026-08-07','',NULL,'Ranga','0766049366','','COD',0.00,0.00,NULL,'2026-08-07 11:26:22',NULL,'2026-08-07 05:56:22',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Ranga','','English','','','LKR','','Due on Receipt',0),(51,'537','00049','2026-08-07','',NULL,'537','0112323423','','COD',0.00,0.00,NULL,'2026-08-07 11:28:31',NULL,'2026-08-07 06:23:40',0,'','',NULL,'','','','',NULL,NULL,'Paid',NULL,0,'','Business','','537','','English','','','LKR','','Due on Receipt',0),(52,'Indika','00049','2026-08-10','',NULL,'Indika','0741314138','','COD',0.00,0.00,NULL,'2026-08-10 10:56:36',NULL,'2026-08-10 05:26:36',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Indika','','English','','','LKR','','Due on Receipt',0),(53,'WAVE CRAFT','00050','2026-08-11','',NULL,'WAVE CRAFT','0752606370','','COD',0.00,0.00,NULL,'2026-08-11 16:45:23',NULL,'2026-08-11 11:15:23',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','WAVE CRAFT','','English','','','LKR','','Due on Receipt',0),(54,'WEDDING CARD BILL','00051','2026-08-13','',NULL,'WEDDING CARD BILL','0112323423','','COD',0.00,0.00,NULL,'2026-08-13 12:35:54',NULL,'2026-08-13 07:05:54',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','WEDDING CARD BILL','','English','','','LKR','','Due on Receipt',0),(55,'PRINT CASH','00052','2026-08-13','',NULL,'PRINT CASH','0112323423','','COD',0.00,0.00,NULL,'2026-08-13 14:47:25',NULL,'2026-08-13 09:17:25',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','PRINT CASH','','English','','','LKR','','Due on Receipt',0),(56,'Smart Asia GLOBAL (PVT) Ltd','00053','2026-08-13','',NULL,'Smart Asia GLOBAL (PVT) Ltd','077 807 0297','','COD',0.00,0.00,NULL,'2026-08-13 16:04:37',NULL,'2026-08-13 10:34:37',0,'','',NULL,'','','','',NULL,NULL,'Active',NULL,0,'','Business','','Smart Asia GLOBAL (PVT) Ltd','','English','','','LKR','','Due on Receipt',0);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_domains`
--

DROP TABLE IF EXISTS `customer_domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_domains` (
  `DomainID` int NOT NULL AUTO_INCREMENT,
  `CustomerID` int NOT NULL,
  `DomainName` varchar(255) NOT NULL,
  `RegistrationDate` date DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL,
  `AnnualFee` decimal(10,2) DEFAULT '0.00',
  `Status` varchar(20) DEFAULT 'Active',
  PRIMARY KEY (`DomainID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_domains`
--

LOCK TABLES `customer_domains` WRITE;
/*!40000 ALTER TABLE `customer_domains` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_payments`
--

DROP TABLE IF EXISTS `customer_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_payments` (
  `PaymentID` int NOT NULL AUTO_INCREMENT,
  `CustomerId` int NOT NULL,
  `UserId` int DEFAULT NULL,
  `SaleID` int NOT NULL,
  `PaymentDate` datetime NOT NULL,
  `PaidAmount` decimal(10,2) NOT NULL,
  `PaymentDue` decimal(10,2) NOT NULL,
  `CreditBalance` decimal(10,2) DEFAULT '0.00',
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `BankID` int DEFAULT NULL,
  `ChequeNumber` varchar(110) DEFAULT NULL,
  `ChequeDate` date DEFAULT NULL,
  `BankName` varchar(50) DEFAULT NULL,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` int DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `ChequeStatus` varchar(20) DEFAULT 'Pending',
  PRIMARY KEY (`PaymentID`),
  KEY `CustomerId` (`CustomerId`) USING BTREE,
  KEY `fk_cp_bank` (`BankID`),
  CONSTRAINT `fk_cp_bank` FOREIGN KEY (`BankID`) REFERENCES `banks` (`BankID`),
  CONSTRAINT `fk_cp_customer` FOREIGN KEY (`CustomerId`) REFERENCES `customer` (`CustomerId`)
) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_payments`
--

LOCK TABLES `customer_payments` WRITE;
/*!40000 ALTER TABLE `customer_payments` DISABLE KEYS */;
INSERT INTO `customer_payments` VALUES (1,9,11,14,'2026-08-04 00:00:00',9175.00,0.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,11,'2026-08-04 14:44:07',NULL,'2026-08-04 09:14:07',0,'Pending'),(2,9,11,58,'2026-08-04 00:00:00',2960.00,0.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,11,'2026-08-04 14:44:07',NULL,'2026-08-04 09:14:07',0,'Pending'),(3,9,11,77,'2026-08-04 00:00:00',1480.00,0.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,11,'2026-08-04 14:44:07',NULL,'2026-08-04 09:14:07',0,'Pending'),(4,6,11,8,'2026-08-04 00:00:00',18560.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 14:59:47',NULL,'2026-08-04 09:29:47',0,'Pending'),(5,6,11,16,'2026-08-04 00:00:00',11345.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 14:59:47',NULL,'2026-08-04 09:29:47',0,'Pending'),(6,6,11,20,'2026-08-04 00:00:00',5755.00,55.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 14:59:47',NULL,'2026-08-04 09:29:47',0,'Pending'),(7,14,11,37,'2026-08-04 00:00:00',1350.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:04:42',NULL,'2026-08-04 09:34:42',0,'Pending'),(8,14,11,50,'2026-08-04 00:00:00',8730.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:04:42',NULL,'2026-08-04 09:34:42',0,'Pending'),(9,3,11,40,'2026-08-04 00:00:00',7220.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(10,3,11,3,'2026-08-04 00:00:00',9770.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(11,3,11,7,'2026-08-04 00:00:00',9420.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(12,3,11,17,'2026-08-04 00:00:00',8680.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(13,3,11,21,'2026-08-04 00:00:00',25230.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(14,3,11,29,'2026-08-04 00:00:00',2650.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(15,3,11,41,'2026-08-04 00:00:00',12950.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(16,3,11,67,'2026-08-04 00:00:00',10810.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:08:12',NULL,'2026-08-04 09:38:12',0,'Pending'),(17,4,11,4,'2026-08-04 00:00:00',4890.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:11:30',NULL,'2026-08-04 09:41:30',0,'Pending'),(18,4,11,24,'2026-08-04 00:00:00',11730.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:11:30',NULL,'2026-08-04 09:41:30',0,'Pending'),(19,1,11,61,'2026-08-04 00:00:00',10000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:13:05',NULL,'2026-08-04 09:43:05',0,'Pending'),(20,5,11,6,'2026-08-04 00:00:00',11300.00,8390.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:14:15',NULL,'2026-08-04 09:44:15',0,'Pending'),(21,20,11,11,'2026-08-04 00:00:00',2250.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:17:16',NULL,'2026-08-04 09:47:16',0,'Pending'),(22,7,11,45,'2026-08-04 00:00:00',2150.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-04 15:19:03',NULL,'2026-08-04 09:49:03',0,'Pending'),(23,32,11,78,'2026-07-01 10:07:03',1900.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:07:03',NULL,'2026-08-05 04:37:03',0,'Pending'),(24,32,11,79,'2026-07-01 10:09:15',5000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:09:15',NULL,'2026-08-05 04:39:15',0,'Pending'),(25,32,11,80,'2026-07-01 10:10:30',1000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:10:30',NULL,'2026-08-05 04:40:30',0,'Pending'),(26,32,11,81,'2026-07-01 10:11:40',780.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:11:40',NULL,'2026-08-05 04:41:40',0,'Pending'),(27,32,11,82,'2026-07-01 10:12:11',980.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:12:11',NULL,'2026-08-05 04:42:11',0,'Pending'),(28,32,11,83,'2026-07-01 10:12:37',1000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:12:37',NULL,'2026-08-05 04:42:37',0,'Pending'),(29,32,11,84,'2026-07-02 10:13:00',840.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:13:00',NULL,'2026-08-05 04:43:00',0,'Pending'),(30,32,11,85,'2026-07-02 10:13:42',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:13:42',NULL,'2026-08-05 04:43:42',0,'Pending'),(31,32,11,86,'2026-06-02 10:14:10',4800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:14:10',NULL,'2026-08-05 04:44:10',0,'Pending'),(32,32,11,87,'2026-07-02 10:14:49',2500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:14:49',NULL,'2026-08-05 04:44:49',0,'Pending'),(33,32,11,88,'2026-07-02 10:15:45',3550.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:15:45',NULL,'2026-08-05 04:46:19',0,'Pending'),(34,32,11,89,'2026-07-02 10:17:36',500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:17:36',NULL,'2026-08-05 04:47:36',0,'Pending'),(35,32,11,90,'2026-07-02 10:23:25',8910.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:23:25',NULL,'2026-08-05 04:53:25',0,'Pending'),(36,32,11,91,'2026-07-03 10:23:50',4000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:23:50',NULL,'2026-08-05 04:53:50',0,'Pending'),(37,32,11,92,'2026-07-03 10:24:19',10500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:24:19',NULL,'2026-08-05 04:54:19',0,'Pending'),(38,32,11,93,'2026-07-03 10:24:44',5800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:24:44',NULL,'2026-08-05 04:54:44',0,'Pending'),(39,32,11,94,'2026-07-03 10:25:41',1000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:25:41',NULL,'2026-08-05 04:55:41',0,'Pending'),(40,32,11,95,'2026-07-03 10:26:16',320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:26:16',NULL,'2026-08-05 04:56:16',0,'Pending'),(41,32,11,96,'2026-07-04 10:26:48',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:26:48',NULL,'2026-08-05 04:56:48',0,'Pending'),(42,32,11,97,'2026-07-06 10:28:53',600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:28:53',NULL,'2026-08-05 04:58:53',0,'Pending'),(43,32,11,98,'2026-07-04 10:29:43',2535.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:29:43',NULL,'2026-08-05 04:59:43',0,'Pending'),(44,32,11,99,'2026-07-06 10:30:54',900.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:30:54',NULL,'2026-08-05 05:00:54',0,'Pending'),(45,32,11,100,'2026-07-07 10:31:28',200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:31:28',NULL,'2026-08-05 05:01:28',0,'Pending'),(46,32,11,101,'2026-07-06 10:32:30',750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:32:30',NULL,'2026-08-05 05:02:30',0,'Pending'),(47,32,11,102,'2026-07-06 10:33:54',7500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:33:54',NULL,'2026-08-05 05:03:54',0,'Pending'),(48,32,11,103,'2026-07-06 10:33:54',7500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:33:54',NULL,'2026-08-05 05:03:54',0,'Pending'),(49,32,11,104,'2026-07-06 10:34:33',750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:34:33',NULL,'2026-08-05 05:04:33',0,'Pending'),(50,32,11,105,'2026-07-06 10:35:05',3600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:35:05',NULL,'2026-08-05 05:05:05',0,'Pending'),(51,32,11,106,'2026-07-06 10:35:42',800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:35:42',NULL,'2026-08-05 05:05:42',0,'Pending'),(52,32,11,107,'2026-07-07 10:36:04',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:36:04',NULL,'2026-08-05 05:06:04',0,'Pending'),(53,32,11,108,'2026-07-07 10:37:29',1200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:37:29',NULL,'2026-08-05 05:07:29',0,'Pending'),(54,32,11,109,'2026-07-07 10:37:50',1300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:37:50',NULL,'2026-08-05 05:07:50',0,'Pending'),(55,32,11,110,'2026-07-07 10:38:19',3500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:38:19',NULL,'2026-08-05 05:08:19',0,'Pending'),(56,32,11,111,'2026-07-08 10:38:45',400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:38:45',NULL,'2026-08-05 05:08:45',0,'Pending'),(57,32,11,112,'2026-07-08 10:39:22',5880.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:39:22',NULL,'2026-08-05 05:09:22',0,'Pending'),(58,32,11,113,'2026-07-08 10:40:03',600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:40:03',NULL,'2026-08-05 05:10:03',0,'Pending'),(59,32,11,114,'2026-07-08 10:40:24',800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:40:24',NULL,'2026-08-05 05:10:24',0,'Pending'),(60,32,11,115,'2026-07-08 10:40:48',1840.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:40:48',NULL,'2026-08-05 05:10:48',0,'Pending'),(61,32,11,116,'2026-07-09 10:41:41',4200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:41:41',NULL,'2026-08-05 05:11:41',0,'Pending'),(62,32,11,117,'2026-07-09 10:42:15',3500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:42:15',NULL,'2026-08-05 05:12:15',0,'Pending'),(63,32,11,118,'2026-07-10 10:42:50',4020.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:42:50',NULL,'2026-08-05 05:12:50',0,'Pending'),(64,32,11,119,'2026-07-10 10:43:16',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:43:16',NULL,'2026-08-05 05:13:16',0,'Pending'),(65,32,11,120,'2026-07-11 10:43:44',1170.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:43:44',NULL,'2026-08-05 05:13:44',0,'Pending'),(66,32,11,121,'2026-07-13 10:44:08',2170.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:44:08',NULL,'2026-08-05 05:14:08',0,'Pending'),(67,32,11,122,'2026-07-13 10:44:36',5050.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:44:36',NULL,'2026-08-05 05:14:36',0,'Pending'),(68,32,11,123,'2026-07-13 10:45:00',6600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:45:00',NULL,'2026-08-05 05:15:00',0,'Pending'),(69,32,11,124,'2026-07-13 10:45:32',1960.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:45:32',NULL,'2026-08-05 05:15:32',0,'Pending'),(70,32,11,125,'2026-07-02 00:00:00',5000.00,27500.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:47:07',NULL,'2026-08-06 11:44:50',1,'Pending'),(71,32,11,126,'2026-07-14 10:48:14',500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:48:14',NULL,'2026-08-05 05:18:14',0,'Pending'),(72,32,11,127,'2026-07-14 10:48:37',1320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:48:37',NULL,'2026-08-05 05:18:37',0,'Pending'),(73,32,11,128,'2026-07-14 10:49:08',900.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:49:08',NULL,'2026-08-05 05:19:08',0,'Pending'),(74,26,11,129,'2026-07-07 10:49:45',19800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:49:45',NULL,'2026-08-05 05:19:45',0,'Pending'),(75,32,11,130,'2026-07-14 10:50:11',17760.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:50:11',NULL,'2026-08-05 05:20:11',0,'Pending'),(76,32,11,131,'2026-07-14 10:50:53',2520.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:50:53',NULL,'2026-08-05 05:20:53',0,'Pending'),(77,32,11,132,'2026-07-15 10:51:22',760.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:51:22',NULL,'2026-08-05 05:21:22',0,'Pending'),(78,32,11,133,'2026-07-15 10:51:46',7375.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:51:46',NULL,'2026-08-05 05:21:46',0,'Pending'),(79,32,11,134,'2026-06-15 10:52:16',4000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:52:16',NULL,'2026-08-05 05:22:16',0,'Pending'),(80,32,11,135,'2026-06-15 10:52:16',4000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:52:16',NULL,'2026-08-05 05:22:16',0,'Pending'),(81,32,11,136,'2026-06-15 10:52:16',4000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:52:16',NULL,'2026-08-05 05:22:16',0,'Pending'),(82,32,11,137,'2026-07-15 10:52:41',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:52:41',NULL,'2026-08-05 05:22:41',0,'Pending'),(83,32,11,138,'2026-07-15 10:53:16',1040.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:53:16',NULL,'2026-08-05 05:23:16',0,'Pending'),(84,32,11,139,'2026-07-15 10:53:16',1040.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:53:16',NULL,'2026-08-05 05:23:16',0,'Pending'),(85,32,11,140,'2026-07-16 10:53:48',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:53:48',NULL,'2026-08-05 05:23:48',0,'Pending'),(86,32,11,141,'2026-07-16 10:54:19',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:54:19',NULL,'2026-08-05 05:24:19',0,'Pending'),(87,32,11,142,'2026-07-16 10:54:42',320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:54:42',NULL,'2026-08-05 05:24:42',0,'Pending'),(88,32,11,143,'2026-07-17 10:55:13',120.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:55:13',NULL,'2026-08-05 05:25:13',0,'Pending'),(89,32,11,144,'2026-07-17 10:55:39',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:55:39',NULL,'2026-08-05 05:25:39',0,'Pending'),(90,32,11,145,'2026-07-17 10:56:09',1200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:56:09',NULL,'2026-08-05 05:26:09',0,'Pending'),(91,32,11,146,'2026-07-17 10:56:42',500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:56:42',NULL,'2026-08-05 05:26:42',0,'Pending'),(92,32,11,147,'2026-07-17 10:57:06',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:57:06',NULL,'2026-08-05 05:27:06',0,'Pending'),(93,32,11,148,'2026-07-18 10:57:34',400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:57:34',NULL,'2026-08-05 05:27:34',0,'Pending'),(94,32,11,149,'2026-07-18 10:57:57',100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:57:57',NULL,'2026-08-05 05:27:57',0,'Pending'),(95,32,11,150,'2026-07-20 10:58:26',480.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:58:26',NULL,'2026-08-05 05:28:26',0,'Pending'),(96,32,11,151,'2026-07-20 10:58:55',3360.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:58:55',NULL,'2026-08-05 05:28:55',0,'Pending'),(97,32,11,152,'2026-07-20 10:58:55',3360.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:58:55',NULL,'2026-08-05 05:28:55',0,'Pending'),(98,32,11,153,'2026-07-20 10:59:22',1400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 10:59:22',NULL,'2026-08-05 05:29:22',0,'Pending'),(99,32,11,154,'2026-07-20 11:00:02',6400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:00:02',NULL,'2026-08-05 05:30:02',0,'Pending'),(100,32,11,155,'2026-07-20 11:00:49',760.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:00:49',NULL,'2026-08-05 05:30:49',0,'Pending'),(101,32,11,156,'2026-07-21 11:01:12',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:01:12',NULL,'2026-08-05 05:31:12',0,'Pending'),(102,32,11,157,'2026-07-21 11:03:37',24000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:03:37',NULL,'2026-08-05 05:33:37',0,'Pending'),(103,32,11,158,'2026-07-21 11:03:37',24000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:03:37',NULL,'2026-08-05 05:33:37',0,'Pending'),(104,32,11,159,'2026-07-22 11:04:18',12000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:04:18',NULL,'2026-08-05 05:34:18',0,'Pending'),(105,32,11,160,'2026-07-21 11:04:53',2100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:04:53',NULL,'2026-08-05 05:34:53',0,'Pending'),(106,32,11,161,'2026-07-21 11:05:44',2920.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:05:44',NULL,'2026-08-05 05:35:44',0,'Pending'),(107,32,11,162,'2026-07-21 11:06:17',150.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:06:17',NULL,'2026-08-05 05:36:17',0,'Pending'),(108,32,11,163,'2026-07-21 11:06:52',700.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:06:52',NULL,'2026-08-05 05:36:52',0,'Pending'),(109,32,11,164,'2026-07-22 11:07:16',1200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:07:16',NULL,'2026-08-05 05:37:16',0,'Pending'),(110,32,11,165,'2026-07-22 11:07:49',3280.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:07:49',NULL,'2026-08-05 05:37:49',0,'Pending'),(111,32,11,166,'2026-07-23 11:08:29',7200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:08:29',NULL,'2026-08-05 05:38:29',0,'Pending'),(112,32,11,167,'2026-07-24 11:09:11',32000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:09:11',NULL,'2026-08-05 05:39:11',0,'Pending'),(113,32,11,168,'2026-07-24 11:09:35',28000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:09:35',NULL,'2026-08-05 05:39:35',0,'Pending'),(114,32,11,169,'2026-07-24 11:10:04',1100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:10:04',NULL,'2026-08-05 05:40:04',0,'Pending'),(115,32,11,170,'2026-07-24 11:10:34',3870.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:10:34',NULL,'2026-08-05 05:40:34',0,'Pending'),(116,32,11,171,'2026-07-24 11:11:04',9810.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:11:04',NULL,'2026-08-05 05:41:04',0,'Pending'),(117,32,11,172,'2026-07-24 11:11:27',1400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:11:27',NULL,'2026-08-05 05:41:27',0,'Pending'),(118,32,11,173,'2026-07-24 11:11:52',400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:11:52',NULL,'2026-08-05 05:41:52',0,'Pending'),(119,32,11,174,'2026-07-24 11:12:17',2560.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:12:17',NULL,'2026-08-05 05:42:17',0,'Pending'),(120,32,11,175,'2026-07-24 11:13:19',750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:13:19',NULL,'2026-08-05 05:43:19',0,'Pending'),(121,32,11,176,'2026-07-24 11:13:45',11970.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:13:45',NULL,'2026-08-05 05:43:45',0,'Pending'),(122,32,11,177,'2026-07-25 11:14:12',150.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:14:12',NULL,'2026-08-05 05:44:12',0,'Pending'),(123,32,11,178,'2026-07-25 11:14:43',1900.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:14:43',NULL,'2026-08-05 05:44:43',0,'Pending'),(124,32,11,179,'2026-07-25 11:15:13',4020.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:15:13',NULL,'2026-08-05 05:45:13',0,'Pending'),(125,32,11,180,'2026-07-25 11:15:42',3240.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:15:42',NULL,'2026-08-05 05:45:42',0,'Pending'),(126,32,11,181,'2026-07-27 11:16:06',8400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:16:06',NULL,'2026-08-05 05:46:06',0,'Pending'),(127,32,11,182,'2026-07-27 11:17:35',10020.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:17:35',NULL,'2026-08-05 05:47:35',0,'Pending'),(128,32,11,183,'2026-07-27 11:18:22',2300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:18:22',NULL,'2026-08-05 05:48:22',0,'Pending'),(129,32,11,184,'2026-07-28 11:19:02',5000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:19:02',NULL,'2026-08-05 05:49:02',0,'Pending'),(130,32,11,185,'2026-07-27 11:19:39',3600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:19:39',NULL,'2026-08-05 05:49:39',0,'Pending'),(131,11,11,186,'2026-07-28 11:22:59',4500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:22:59',NULL,'2026-08-05 05:52:59',0,'Pending'),(132,32,11,187,'2026-07-28 11:23:29',8750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:23:29',NULL,'2026-08-05 05:53:29',0,'Pending'),(133,32,11,188,'2026-07-28 11:24:04',17500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:24:04',NULL,'2026-08-05 05:54:04',0,'Pending'),(134,7,11,189,'2026-07-30 11:24:39',2160.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:24:39',NULL,'2026-08-05 05:54:39',0,'Pending'),(135,32,11,190,'2026-07-30 11:25:17',320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:25:17',NULL,'2026-08-05 05:55:17',0,'Pending'),(136,32,11,191,'2026-07-30 11:25:42',800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:25:42',NULL,'2026-08-05 05:55:42',0,'Pending'),(137,32,11,192,'2026-07-30 11:26:10',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:26:10',NULL,'2026-08-05 05:56:10',0,'Pending'),(138,32,11,193,'2026-07-30 11:26:45',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:26:45',NULL,'2026-08-05 05:56:45',0,'Pending'),(139,32,11,194,'2026-07-30 11:27:08',1320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:27:08',NULL,'2026-08-05 05:57:08',0,'Pending'),(140,32,11,195,'2026-07-30 11:27:39',28160.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:27:39',NULL,'2026-08-05 05:57:39',0,'Pending'),(141,32,11,196,'2026-07-31 11:28:06',1040.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:28:06',NULL,'2026-08-05 05:58:06',0,'Pending'),(142,32,11,197,'2026-07-31 11:28:32',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:28:32',NULL,'2026-08-05 05:58:32',0,'Pending'),(143,32,11,198,'2026-07-31 11:29:05',500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-05 11:29:05',NULL,'2026-08-05 05:59:05',0,'Pending'),(144,33,11,199,'2026-07-01 10:13:07',2200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 10:13:07',NULL,'2026-08-06 04:43:07',0,'Pending'),(145,33,11,200,'2026-07-01 10:13:36',2380.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 10:13:36',NULL,'2026-08-06 04:43:36',0,'Pending'),(146,33,11,201,'2026-07-01 10:13:37',1000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 10:13:37',NULL,'2026-08-06 05:31:05',0,'Pending'),(147,33,11,203,'2026-07-02 11:02:49',4200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:02:49',NULL,'2026-08-06 05:32:49',0,'Pending'),(148,33,11,204,'2026-07-04 11:03:29',2400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:03:29',NULL,'2026-08-06 05:33:29',0,'Pending'),(149,33,11,205,'2026-07-04 11:03:54',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:03:54',NULL,'2026-08-06 05:33:54',0,'Pending'),(150,12,11,31,'2026-08-05 00:00:00',36885.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:18:56',NULL,'2026-08-06 05:48:56',0,'Pending'),(151,12,11,53,'2026-08-05 00:00:00',4435.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:18:56',NULL,'2026-08-06 05:48:56',0,'Pending'),(152,12,11,74,'2026-08-05 00:00:00',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:18:56',NULL,'2026-08-06 05:48:56',0,'Pending'),(153,33,11,206,'2026-08-06 11:23:12',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:23:12',NULL,'2026-08-06 05:53:12',0,'Pending'),(154,33,11,207,'2026-07-04 11:23:38',2700.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:23:38',NULL,'2026-08-06 05:53:38',0,'Pending'),(155,33,11,208,'2026-07-04 11:24:46',3800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:24:46',NULL,'2026-08-06 05:54:46',0,'Pending'),(156,33,11,209,'2026-07-06 11:25:14',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:25:14',NULL,'2026-08-06 05:55:14',0,'Pending'),(157,33,11,210,'2026-07-07 11:26:51',3500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:26:51',NULL,'2026-08-06 05:56:51',0,'Pending'),(158,33,11,211,'2026-07-07 11:27:15',3050.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:27:15',NULL,'2026-08-06 05:57:15',0,'Pending'),(159,33,11,212,'2026-07-07 11:27:48',900.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:27:48',NULL,'2026-08-06 05:57:48',0,'Pending'),(160,33,11,213,'2026-07-07 11:28:19',12480.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:28:19',NULL,'2026-08-06 05:58:19',0,'Pending'),(161,33,11,214,'2026-07-08 11:33:18',4800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:33:18',NULL,'2026-08-06 06:03:18',0,'Pending'),(162,33,11,215,'2026-07-09 11:33:52',2500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:33:52',NULL,'2026-08-06 06:03:52',0,'Pending'),(163,33,11,216,'2026-07-09 11:34:22',1800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:34:22',NULL,'2026-08-06 06:04:22',0,'Pending'),(164,33,11,217,'2026-07-09 11:34:52',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:34:52',NULL,'2026-08-06 06:04:52',0,'Pending'),(165,33,11,218,'2026-07-10 11:35:15',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:35:15',NULL,'2026-08-06 06:05:15',0,'Pending'),(166,33,11,219,'2026-07-11 11:35:42',4600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:35:42',NULL,'2026-08-06 06:05:42',0,'Pending'),(167,33,11,220,'2026-07-11 11:36:09',14500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:36:09',NULL,'2026-08-06 06:06:09',0,'Pending'),(168,33,11,221,'2026-07-11 11:36:09',14500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:36:09',NULL,'2026-08-06 06:06:09',0,'Pending'),(169,33,11,222,'2026-07-11 11:36:35',13000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:36:35',NULL,'2026-08-06 06:06:35',0,'Pending'),(170,33,11,223,'2026-07-13 11:37:55',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:37:55',NULL,'2026-08-06 06:07:55',0,'Pending'),(171,33,11,224,'2026-07-13 11:38:22',1800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:38:22',NULL,'2026-08-06 06:08:22',0,'Pending'),(172,33,11,225,'2026-07-13 11:38:58',11000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:38:58',NULL,'2026-08-06 06:08:58',0,'Pending'),(173,33,11,226,'2026-07-13 11:39:27',14040.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:39:27',NULL,'2026-08-06 06:09:27',0,'Pending'),(174,33,11,227,'2026-07-13 11:39:28',14040.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:39:28',NULL,'2026-08-06 06:09:28',0,'Pending'),(175,33,11,228,'2026-07-13 11:39:28',14040.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:39:28',NULL,'2026-08-06 06:09:28',0,'Pending'),(176,33,11,229,'2026-07-14 11:39:57',800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:39:57',NULL,'2026-08-06 06:09:57',0,'Pending'),(177,33,11,230,'2026-07-15 11:40:29',6000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:40:29',NULL,'2026-08-06 06:10:29',0,'Pending'),(178,33,11,231,'2026-07-15 11:40:55',10200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:40:55',NULL,'2026-08-06 06:10:55',0,'Pending'),(179,33,11,232,'2026-07-15 11:46:18',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:46:18',NULL,'2026-08-06 06:16:18',0,'Pending'),(180,33,11,233,'2026-07-16 11:46:46',4500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:46:46',NULL,'2026-08-06 06:16:46',0,'Pending'),(181,33,11,234,'2026-07-16 11:47:11',700.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:47:11',NULL,'2026-08-06 06:17:11',0,'Pending'),(182,33,11,235,'2026-07-16 11:47:11',700.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:47:11',NULL,'2026-08-06 06:17:11',0,'Pending'),(183,33,11,236,'2026-07-16 11:47:35',1100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:47:35',NULL,'2026-08-06 06:17:35',0,'Pending'),(184,33,11,237,'2026-07-16 11:48:31',12000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:48:31',NULL,'2026-08-06 06:18:31',0,'Pending'),(185,33,11,238,'2026-07-17 11:49:07',900.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:49:07',NULL,'2026-08-06 06:19:07',0,'Pending'),(186,33,11,239,'2026-07-17 11:55:44',6000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:55:44',NULL,'2026-08-06 06:25:44',0,'Pending'),(187,33,11,240,'2026-07-16 11:56:25',9800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:56:25',NULL,'2026-08-06 06:26:25',0,'Pending'),(188,33,11,241,'2026-07-17 11:57:01',2720.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:57:01',NULL,'2026-08-06 06:27:01',0,'Pending'),(189,33,11,242,'2026-07-18 11:57:39',6750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:57:39',NULL,'2026-08-06 06:27:39',0,'Pending'),(190,33,11,243,'2026-07-18 11:58:11',4000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:58:11',NULL,'2026-08-06 06:28:11',0,'Pending'),(191,33,11,244,'2026-07-20 11:58:40',15000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:58:40',NULL,'2026-08-06 06:28:40',0,'Pending'),(192,33,11,245,'2026-07-20 11:59:13',10500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:59:13',NULL,'2026-08-06 06:29:13',0,'Pending'),(193,33,11,246,'2026-07-20 11:59:41',800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 11:59:41',NULL,'2026-08-06 06:29:41',0,'Pending'),(194,33,11,247,'2026-07-20 12:00:07',3600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:00:07',NULL,'2026-08-06 06:30:07',0,'Pending'),(195,33,11,248,'2026-07-21 12:00:31',6000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:00:31',NULL,'2026-08-06 06:30:31',0,'Pending'),(196,33,11,249,'2026-07-21 12:02:37',29750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:02:37',NULL,'2026-08-06 06:32:37',0,'Pending'),(197,33,11,250,'2026-07-22 12:03:01',6000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:03:01',NULL,'2026-08-06 06:33:01',0,'Pending'),(198,33,11,251,'2026-07-23 12:03:35',7000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:03:35',NULL,'2026-08-06 06:33:35',0,'Pending'),(199,33,11,252,'2026-07-23 12:08:10',3060.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:08:10',NULL,'2026-08-06 06:38:10',0,'Pending'),(200,33,11,253,'2026-07-23 12:08:43',1800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:08:43',NULL,'2026-08-06 06:38:43',0,'Pending'),(201,33,11,254,'2026-07-23 12:09:07',12000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:09:07',NULL,'2026-08-06 06:39:07',0,'Pending'),(202,33,11,255,'2026-07-23 12:09:56',6000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:09:56',NULL,'2026-08-06 06:39:56',0,'Pending'),(203,33,11,256,'2026-07-24 12:10:23',6400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:10:23',NULL,'2026-08-06 06:40:23',0,'Pending'),(204,33,11,257,'2026-07-24 12:10:59',45000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:10:59',NULL,'2026-08-06 06:40:59',0,'Pending'),(205,33,11,259,'2026-07-25 12:12:04',4800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:12:04',NULL,'2026-08-06 06:42:04',0,'Pending'),(206,33,11,260,'2026-07-25 12:12:29',5250.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:12:29',NULL,'2026-08-06 06:42:29',0,'Pending'),(207,33,11,261,'2026-07-27 12:14:46',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:14:46',NULL,'2026-08-06 06:44:46',0,'Pending'),(208,33,11,262,'2026-07-27 12:15:16',3500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:15:16',NULL,'2026-08-06 06:45:16',0,'Pending'),(209,33,11,263,'2026-07-28 12:17:00',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:17:00',NULL,'2026-08-06 06:47:00',0,'Pending'),(210,33,11,264,'2026-07-30 12:17:32',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:17:32',NULL,'2026-08-06 06:47:32',0,'Pending'),(211,33,11,265,'2026-07-30 12:17:57',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:17:57',NULL,'2026-08-06 06:47:57',0,'Pending'),(212,33,11,266,'2026-07-30 12:18:27',6000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:18:27',NULL,'2026-08-06 06:48:27',0,'Pending'),(213,33,11,267,'2026-07-31 12:19:43',7500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:19:43',NULL,'2026-08-06 06:49:43',0,'Pending'),(214,33,11,268,'2026-07-31 12:20:16',16320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:20:16',NULL,'2026-08-06 06:50:16',0,'Pending'),(215,33,11,202,'2026-07-01 00:00:00',3600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 12:23:35',NULL,'2026-08-06 06:53:35',0,'Pending'),(216,39,11,125,'2026-07-02 00:00:00',5000.00,27500.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,11,'2026-08-06 17:17:04',NULL,'2026-08-06 11:47:04',0,'Pending'),(217,14,11,73,'2026-08-06 00:00:00',100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-06 17:21:21',NULL,'2026-08-06 11:51:21',0,'Pending'),(218,45,11,274,'2026-07-06 11:05:11',433228.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:05:11',NULL,'2026-08-07 05:35:11',0,'Pending'),(219,41,11,269,'2026-07-02 00:00:00',10000.00,4000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:32:52',NULL,'2026-08-11 04:36:28',1,'Pending'),(220,38,11,270,'2026-08-07 00:00:00',20000.00,24800.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:34:36',NULL,'2026-08-07 06:04:36',0,'Pending'),(221,43,11,272,'2026-07-02 00:00:00',10000.00,16000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:35:22',NULL,'2026-08-07 06:05:22',0,'Pending'),(222,44,11,273,'2026-06-30 00:00:00',49000.00,49000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:36:26',NULL,'2026-08-07 06:06:26',0,'Pending'),(223,46,11,275,'2026-07-06 00:00:00',16500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:39:22',NULL,'2026-08-07 06:09:22',0,'Pending'),(224,42,11,271,'2026-07-04 00:00:00',104000.00,16000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:40:21',NULL,'2026-08-07 06:10:21',0,'Pending'),(225,42,11,276,'2026-07-04 00:00:00',96000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:40:21',NULL,'2026-08-07 06:10:21',0,'Pending'),(226,47,11,277,'2026-08-03 00:00:00',60000.00,40000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:47:56',NULL,'2026-08-07 06:17:56',0,'Pending'),(227,36,11,278,'2026-08-01 00:00:00',8200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:49:44',NULL,'2026-08-07 06:19:44',0,'Pending'),(228,36,11,281,'2026-08-01 00:00:00',70000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:49:44',NULL,'2026-08-07 06:19:44',0,'Pending'),(229,36,11,286,'2026-08-01 00:00:00',74400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:49:44',NULL,'2026-08-07 06:19:44',0,'Pending'),(230,35,11,280,'2026-07-17 00:00:00',63500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:51:33',NULL,'2026-08-07 06:21:33',0,'Pending'),(231,51,11,284,'2026-07-24 00:00:00',18000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 11:53:40',NULL,'2026-08-07 06:23:40',0,'Pending'),(232,40,11,287,'2026-08-07 00:00:00',190000.00,58000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-07 12:00:40',NULL,'2026-08-07 06:30:40',0,'Pending'),(233,14,11,300,'2026-08-05 11:16:43',5400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 11:16:43',NULL,'2026-08-10 05:46:43',0,'Pending'),(234,6,11,20,'2026-08-10 00:00:00',55.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 11:21:00',NULL,'2026-08-10 05:51:00',0,'Pending'),(235,6,11,30,'2026-08-10 00:00:00',1590.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 11:21:00',NULL,'2026-08-10 05:51:00',0,'Pending'),(236,6,11,44,'2026-08-10 00:00:00',8355.00,6245.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 11:21:00',NULL,'2026-08-10 05:51:00',0,'Pending'),(237,14,11,291,'2026-08-10 00:00:00',2100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 11:25:37',NULL,'2026-08-10 05:55:37',0,'Pending'),(238,38,11,270,'2026-08-10 00:00:00',24800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 16:55:13',NULL,'2026-08-10 11:25:13',0,'Pending'),(239,41,11,269,'2026-08-10 00:00:00',3999.99,0.01,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 16:55:48',NULL,'2026-08-11 04:36:46',1,'Pending'),(240,44,11,273,'2026-08-10 00:00:00',49000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 16:56:34',NULL,'2026-08-10 11:26:34',0,'Pending'),(241,4,11,46,'2026-08-10 00:00:00',12180.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 16:58:46',NULL,'2026-08-10 11:28:46',0,'Pending'),(242,4,11,60,'2026-08-10 00:00:00',10860.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 16:58:46',NULL,'2026-08-10 11:28:46',0,'Pending'),(243,4,11,62,'2026-08-10 00:00:00',6000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 16:58:46',NULL,'2026-08-10 11:28:46',0,'Pending'),(244,4,11,294,'2026-08-10 00:00:00',960.00,630.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-10 16:58:46',NULL,'2026-08-10 11:28:46',0,'Pending'),(245,41,11,269,'2026-08-11 00:00:00',14000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-11 10:21:42',NULL,'2026-08-11 04:51:42',0,'Pending'),(246,24,11,36,'2026-08-11 00:00:00',1840.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-11 12:07:53',NULL,'2026-08-11 06:37:53',0,'Pending'),(247,24,11,52,'2026-08-11 00:00:00',12120.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-11 12:07:53',NULL,'2026-08-11 06:37:53',0,'Pending'),(248,24,11,296,'2026-08-11 00:00:00',780.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-11 12:07:53',NULL,'2026-08-11 06:37:53',0,'Pending'),(249,24,11,301,'2026-07-21 12:08:48',2100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-11 12:08:48',NULL,'2026-08-11 06:38:48',0,'Pending'),(250,45,11,313,'2026-08-11 00:00:00',350000.00,404000.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 14:53:30',NULL,'2026-08-11 09:23:30',0,'Pending'),(251,5,11,6,'2026-08-11 00:00:00',8390.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 15:59:19',NULL,'2026-08-11 10:29:19',0,'Pending'),(252,5,11,15,'2026-08-11 00:00:00',13230.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 15:59:19',NULL,'2026-08-11 10:29:19',0,'Pending'),(253,5,11,22,'2026-08-11 00:00:00',23310.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 15:59:19',NULL,'2026-08-11 10:29:19',0,'Pending'),(254,5,11,43,'2026-08-11 00:00:00',3180.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 15:59:19',NULL,'2026-08-11 10:29:19',0,'Pending'),(255,5,11,69,'2026-08-11 00:00:00',3300.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 15:59:19',NULL,'2026-08-11 10:29:19',0,'Pending'),(256,5,11,295,'2026-08-11 00:00:00',300.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 15:59:19',NULL,'2026-08-11 10:29:19',0,'Pending'),(257,5,11,306,'2026-08-11 00:00:00',11130.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-11 15:59:19',NULL,'2026-08-11 10:29:19',0,'Pending'),(258,1,11,2,'2026-08-12 00:00:00',21835.00,0.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(259,1,11,18,'2026-08-12 00:00:00',22500.00,0.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(260,1,11,19,'2026-08-12 00:00:00',16505.00,0.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(261,1,11,33,'2026-08-12 00:00:00',26990.00,0.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(262,1,11,42,'2026-08-12 00:00:00',31490.00,0.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(263,1,11,63,'2026-08-12 00:00:00',17535.00,0.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(264,1,11,299,'2026-08-12 00:00:00',10565.00,0.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(265,1,11,312,'2026-08-12 00:00:00',2580.00,7420.00,0.00,'Cheque',NULL,'123993','2026-08-20',NULL,11,'2026-08-12 13:42:54',NULL,'2026-08-12 08:12:54',0,'Pending'),(266,22,11,25,'2026-08-12 00:00:00',17720.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-12 13:51:10',NULL,'2026-08-12 08:21:10',0,'Pending'),(267,22,11,26,'2026-08-12 00:00:00',18540.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-12 13:51:10',NULL,'2026-08-12 08:21:10',0,'Pending'),(268,22,11,27,'2026-08-12 00:00:00',11460.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-12 13:51:10',NULL,'2026-08-12 08:21:10',0,'Pending'),(269,7,11,319,'2026-08-12 10:13:45',180.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:13:45',NULL,'2026-08-13 04:43:45',0,'Pending'),(270,7,11,9,'2026-08-13 00:00:00',5945.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:14:46',NULL,'2026-08-13 04:44:46',0,'Pending'),(271,7,11,34,'2026-08-13 00:00:00',5880.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:14:46',NULL,'2026-08-13 04:44:46',0,'Pending'),(272,7,11,323,'2026-08-13 00:00:00',700.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:14:46',NULL,'2026-08-13 04:44:46',0,'Pending'),(273,17,11,324,'2026-08-13 10:16:57',2440.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:16:57',NULL,'2026-08-13 04:47:03',1,'Pending'),(274,17,11,324,'2026-08-13 00:00:00',2440.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(275,17,11,325,'2026-08-13 00:00:00',2800.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(276,17,11,326,'2026-08-13 00:00:00',6680.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(277,17,11,327,'2026-08-13 00:00:00',2000.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(278,17,11,328,'2026-08-13 00:00:00',4020.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(279,17,11,334,'2026-08-13 00:00:00',10680.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(280,17,11,333,'2026-08-13 00:00:00',3000.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(281,17,11,332,'2026-08-13 00:00:00',280.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(282,17,11,331,'2026-08-13 00:00:00',900.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:26',NULL,'2026-08-13 04:59:26',0,'Pending'),(283,17,11,330,'2026-08-13 00:00:00',900.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:27',NULL,'2026-08-13 04:59:27',0,'Pending'),(284,17,11,329,'2026-08-13 00:00:00',31250.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:27',NULL,'2026-08-13 04:59:27',0,'Pending'),(285,17,11,338,'2026-08-13 00:00:00',1200.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:27',NULL,'2026-08-13 04:59:27',0,'Pending'),(286,17,11,337,'2026-08-13 00:00:00',1540.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:27',NULL,'2026-08-13 04:59:27',0,'Pending'),(287,17,11,336,'2026-08-13 00:00:00',200.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:27',NULL,'2026-08-13 04:59:27',0,'Pending'),(288,17,11,335,'2026-08-13 00:00:00',1800.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:27',NULL,'2026-08-13 04:59:27',0,'Pending'),(289,17,11,339,'2026-08-13 00:00:00',800.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 10:29:27',NULL,'2026-08-13 04:59:27',0,'Pending'),(290,32,11,342,'2026-08-01 11:16:31',660.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:16:31',NULL,'2026-08-13 05:46:31',0,'Pending'),(291,32,11,343,'2026-08-01 11:17:42',1295.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:17:42',NULL,'2026-08-13 05:47:42',0,'Pending'),(292,32,11,344,'2026-08-01 11:19:08',5000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:19:08',NULL,'2026-08-13 05:49:08',0,'Pending'),(293,32,11,345,'2026-08-01 11:20:14',5600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:20:14',NULL,'2026-08-13 05:50:14',0,'Pending'),(294,32,11,346,'2026-08-01 11:21:53',600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:21:53',NULL,'2026-08-13 05:51:53',0,'Pending'),(295,32,11,347,'2026-08-01 11:22:55',84445.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:22:55',NULL,'2026-08-13 05:52:55',0,'Pending'),(296,7,11,348,'2026-08-03 11:32:24',2250.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:32:24',NULL,'2026-08-13 06:02:24',0,'Pending'),(297,11,11,349,'2026-08-03 11:33:03',990.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:33:03',NULL,'2026-08-13 06:03:03',0,'Pending'),(298,32,11,350,'2026-08-03 11:34:13',2400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:34:13',NULL,'2026-08-13 06:04:13',0,'Pending'),(299,32,11,351,'2026-08-03 11:34:54',1160.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:34:54',NULL,'2026-08-13 06:04:54',0,'Pending'),(300,32,11,352,'2026-08-03 11:35:50',11310.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:35:50',NULL,'2026-08-13 06:05:50',0,'Pending'),(301,32,11,353,'2026-08-03 11:36:26',1000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:36:26',NULL,'2026-08-13 06:06:26',0,'Pending'),(302,9,11,354,'2026-08-03 11:39:01',1480.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:39:01',NULL,'2026-08-13 06:09:01',0,'Pending'),(303,32,11,355,'2026-08-04 11:39:59',14000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:39:59',NULL,'2026-08-13 06:09:59',0,'Pending'),(304,32,11,356,'2026-08-04 11:40:36',3600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:40:36',NULL,'2026-08-13 06:11:45',0,'Pending'),(305,32,11,357,'2026-08-04 11:43:13',2880.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:43:13',NULL,'2026-08-13 06:13:13',0,'Pending'),(306,32,11,358,'2026-08-05 11:45:16',2120.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:45:16',NULL,'2026-08-13 06:15:16',0,'Pending'),(307,32,11,359,'2026-08-05 11:46:00',2840.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:46:00',NULL,'2026-08-13 06:16:00',0,'Pending'),(308,32,11,360,'2026-08-05 11:46:26',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:46:26',NULL,'2026-08-13 06:16:26',0,'Pending'),(309,32,11,361,'2026-08-05 11:46:50',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:46:50',NULL,'2026-08-13 06:16:50',0,'Pending'),(310,32,11,362,'2026-08-05 11:48:01',4020.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:48:01',NULL,'2026-08-13 06:18:01',0,'Pending'),(311,32,11,363,'2026-08-06 11:48:46',1400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:48:46',NULL,'2026-08-13 06:18:46',0,'Pending'),(312,32,11,364,'2026-08-06 11:49:55',30000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:49:55',NULL,'2026-08-13 06:19:55',0,'Pending'),(313,32,11,365,'2026-08-06 11:50:31',14500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:50:31',NULL,'2026-08-13 06:20:31',0,'Pending'),(314,32,11,366,'2026-08-06 11:51:11',5460.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:51:11',NULL,'2026-08-13 06:21:11',0,'Pending'),(315,32,11,367,'2026-08-06 11:52:41',1100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:52:41',NULL,'2026-08-13 06:22:41',0,'Pending'),(316,32,11,368,'2026-08-07 11:55:33',300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:55:33',NULL,'2026-08-13 06:25:33',0,'Pending'),(317,7,11,369,'2026-08-07 11:56:06',2310.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:56:06',NULL,'2026-08-13 06:26:06',0,'Pending'),(318,32,11,370,'2026-08-07 11:56:47',1600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:56:47',NULL,'2026-08-13 06:26:47',0,'Pending'),(319,32,11,371,'2026-08-07 11:57:15',1200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:57:15',NULL,'2026-08-13 06:27:15',0,'Pending'),(320,32,11,372,'2026-08-07 11:57:44',600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:57:44',NULL,'2026-08-13 06:27:44',0,'Pending'),(321,32,11,373,'2026-08-07 11:58:30',6540.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:58:30',NULL,'2026-08-13 06:28:30',0,'Pending'),(322,32,11,374,'2026-08-07 11:59:17',2760.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:59:17',NULL,'2026-08-13 06:29:17',0,'Pending'),(323,32,11,375,'2026-08-10 11:59:46',2035.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 11:59:46',NULL,'2026-08-13 06:29:46',0,'Pending'),(324,32,11,376,'2026-08-10 12:00:16',4470.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:00:16',NULL,'2026-08-13 06:30:16',0,'Pending'),(325,32,11,377,'2026-08-10 12:00:39',60.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:00:39',NULL,'2026-08-13 06:30:39',0,'Pending'),(326,32,11,378,'2026-08-10 12:01:19',1260.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:01:19',NULL,'2026-08-13 06:31:19',0,'Pending'),(327,32,11,379,'2026-08-10 12:01:46',320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:01:46',NULL,'2026-08-13 06:31:46',0,'Pending'),(328,32,11,380,'2026-08-10 12:02:18',8325.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:02:18',NULL,'2026-08-13 06:32:18',0,'Pending'),(329,32,11,381,'2026-08-11 12:02:47',1960.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:02:47',NULL,'2026-08-13 06:32:47',0,'Pending'),(330,32,11,382,'2026-08-10 12:03:29',14000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:03:29',NULL,'2026-08-13 06:33:29',0,'Pending'),(331,32,11,383,'2026-08-11 12:04:03',3280.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:04:03',NULL,'2026-08-13 06:34:03',0,'Pending'),(332,32,11,384,'2026-08-11 12:04:30',2400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:04:30',NULL,'2026-08-13 06:34:30',0,'Pending'),(333,32,11,385,'2026-08-11 12:05:04',750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:05:04',NULL,'2026-08-13 06:35:04',0,'Pending'),(334,32,11,386,'2026-08-12 12:05:30',3800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:05:30',NULL,'2026-08-13 06:35:30',0,'Pending'),(335,32,11,387,'2026-08-12 12:06:11',750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:06:11',NULL,'2026-08-13 06:36:11',0,'Pending'),(336,32,11,388,'2026-08-12 12:07:23',50420.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:07:23',NULL,'2026-08-13 06:37:35',1,'Pending'),(337,32,11,388,'2026-08-13 00:00:00',50420.00,0.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:07:56',NULL,'2026-08-13 06:37:56',0,'Pending'),(338,33,11,389,'2026-08-01 12:11:01',1200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:11:01',NULL,'2026-08-13 06:41:01',0,'Pending'),(339,33,11,390,'2026-08-03 12:11:48',6400.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:11:48',NULL,'2026-08-13 06:41:48',0,'Pending'),(340,33,11,391,'2026-08-03 12:12:18',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:12:18',NULL,'2026-08-13 06:42:18',0,'Pending'),(341,33,11,392,'2026-08-03 12:13:11',1600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:13:11',NULL,'2026-08-13 06:43:11',0,'Pending'),(342,33,11,393,'2026-08-04 12:13:43',4620.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:13:43',NULL,'2026-08-13 06:43:43',0,'Pending'),(343,33,11,394,'2026-08-04 12:14:16',1100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:14:16',NULL,'2026-08-13 06:44:16',0,'Pending'),(344,33,11,395,'2026-08-04 12:14:53',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:14:53',NULL,'2026-08-13 06:44:53',0,'Pending'),(345,33,11,396,'2026-08-04 12:15:22',6500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:15:22',NULL,'2026-08-13 06:45:22',0,'Pending'),(346,33,11,397,'2026-08-04 12:15:54',15000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:15:54',NULL,'2026-08-13 06:45:54',0,'Pending'),(347,33,11,398,'2026-08-04 12:16:22',2000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:16:22',NULL,'2026-08-13 06:46:22',0,'Pending'),(348,33,11,399,'2026-08-04 12:16:55',4500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:16:55',NULL,'2026-08-13 06:46:55',0,'Pending'),(349,33,11,400,'2026-08-05 12:17:25',5900.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:17:25',NULL,'2026-08-13 06:47:25',0,'Pending'),(350,33,11,401,'2026-08-06 12:18:02',18200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:18:02',NULL,'2026-08-13 06:48:02',0,'Pending'),(351,33,11,402,'2026-08-06 12:19:09',6600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:19:09',NULL,'2026-08-13 06:49:09',0,'Pending'),(352,33,11,403,'2026-08-06 12:20:10',13000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:20:10',NULL,'2026-08-13 06:50:10',0,'Pending'),(353,33,11,404,'2026-08-06 12:20:36',8750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:20:36',NULL,'2026-08-13 06:50:36',0,'Pending'),(354,33,11,405,'2026-08-06 12:21:11',1500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:21:11',NULL,'2026-08-13 06:51:11',0,'Pending'),(355,33,11,406,'2026-08-06 12:21:41',7200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:21:41',NULL,'2026-08-13 06:51:41',0,'Pending'),(356,33,11,407,'2026-08-07 12:22:52',4500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:22:52',NULL,'2026-08-13 06:52:52',0,'Pending'),(357,33,11,408,'2026-08-07 12:23:28',3500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:23:28',NULL,'2026-08-13 06:53:28',0,'Pending'),(358,33,11,409,'2026-08-07 12:24:20',12000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:24:20',NULL,'2026-08-13 06:54:20',0,'Pending'),(359,33,11,410,'2026-08-08 12:24:50',12500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:24:50',NULL,'2026-08-13 06:54:50',0,'Pending'),(360,33,11,411,'2026-08-08 12:25:26',3000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:25:26',NULL,'2026-08-13 06:55:26',0,'Pending'),(361,33,11,412,'2026-08-10 12:25:55',5300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:25:55',NULL,'2026-08-13 06:55:55',0,'Pending'),(362,33,11,413,'2026-08-10 12:27:35',7000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:27:35',NULL,'2026-08-13 06:57:35',0,'Pending'),(363,33,11,414,'2026-08-10 00:00:00',5000.00,1750.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:29:00',NULL,'2026-08-13 06:59:00',0,'Pending'),(364,33,11,415,'2026-08-10 12:29:32',200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:29:32',NULL,'2026-08-13 06:59:32',0,'Pending'),(365,33,11,416,'2026-08-12 00:00:00',2000.00,3600.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:31:46',NULL,'2026-08-13 07:01:46',0,'Pending'),(366,33,11,417,'2026-08-12 00:00:00',20000.00,17520.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:31:46',NULL,'2026-08-13 07:01:46',0,'Pending'),(367,33,11,417,'2026-08-13 00:00:00',17520.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:32:17',NULL,'2026-08-13 07:02:17',0,'Pending'),(368,54,11,418,'2026-07-10 00:00:00',60000.00,108000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:39:49',NULL,'2026-08-13 07:09:49',0,'Pending'),(369,54,11,419,'2026-07-18 12:43:44',25000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:43:44',NULL,'2026-08-14 16:13:58',1,'Pending'),(370,54,11,420,'2026-07-18 12:43:44',25000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:43:44',NULL,'2026-08-13 07:13:44',0,'Pending'),(371,54,11,421,'2026-08-23 12:44:24',13500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:44:24',NULL,'2026-08-13 07:14:24',0,'Pending'),(372,54,11,422,'2026-08-05 12:44:58',14000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 12:44:58',NULL,'2026-08-13 07:14:58',0,'Pending'),(373,33,11,424,'2026-08-13 13:42:32',3200.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 13:42:32',NULL,'2026-08-13 08:12:32',0,'Pending'),(374,32,11,425,'2026-08-13 14:32:02',3870.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:32:02',NULL,'2026-08-13 09:02:02',0,'Pending'),(375,45,11,313,'2026-08-13 00:00:00',404000.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:39:07',NULL,'2026-08-13 09:09:07',0,'Pending'),(376,45,11,423,'2026-08-13 00:00:00',24700.00,0.00,0.00,'Cheque',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:39:07',NULL,'2026-08-13 09:09:07',0,'Pending'),(377,55,11,426,'2026-07-01 14:47:53',8810.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:47:53',NULL,'2026-08-13 09:17:53',0,'Pending'),(378,55,11,427,'2026-07-02 14:49:48',41045.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:49:48',NULL,'2026-08-13 09:19:48',0,'Pending'),(379,55,11,428,'2026-07-03 14:51:32',30610.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:51:32',NULL,'2026-08-13 09:21:32',0,'Pending'),(380,55,11,429,'2026-07-04 14:53:09',61995.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:53:09',NULL,'2026-08-13 09:23:09',0,'Pending'),(381,55,11,430,'2026-07-06 14:54:05',17950.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:54:05',NULL,'2026-08-13 09:24:05',0,'Pending'),(382,55,11,431,'2026-07-07 14:55:30',19920.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:55:30',NULL,'2026-08-13 09:25:30',0,'Pending'),(383,55,11,432,'2026-07-08 14:56:44',16330.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:56:44',NULL,'2026-08-13 09:26:44',0,'Pending'),(384,55,11,433,'2026-07-09 14:58:31',17120.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 14:58:31',NULL,'2026-08-13 09:28:31',0,'Pending'),(385,55,11,434,'2026-07-10 15:00:18',15450.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:00:18',NULL,'2026-08-13 09:30:18',0,'Pending'),(386,55,11,435,'2026-07-11 15:01:09',2060.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:01:09',NULL,'2026-08-13 09:31:09',0,'Pending'),(387,55,11,436,'2026-07-13 15:03:56',8780.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:03:56',NULL,'2026-08-13 09:33:56',0,'Pending'),(388,55,11,437,'2026-07-14 15:10:23',42465.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:10:23',NULL,'2026-08-13 09:40:23',0,'Pending'),(389,55,11,438,'2026-07-15 15:11:17',5470.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:11:17',NULL,'2026-08-13 09:41:17',0,'Pending'),(390,55,11,439,'2026-07-16 15:12:01',10910.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:12:01',NULL,'2026-08-13 09:42:01',0,'Pending'),(391,55,11,440,'2026-07-17 15:12:32',8950.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:12:32',NULL,'2026-08-13 09:42:32',0,'Pending'),(392,55,11,441,'2026-07-18 15:13:21',9850.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:13:21',NULL,'2026-08-13 09:43:21',0,'Pending'),(393,55,11,442,'2026-07-20 15:14:58',14330.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:14:58',NULL,'2026-08-13 09:44:58',0,'Pending'),(394,55,11,443,'2026-07-21 15:15:53',6760.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:15:53',NULL,'2026-08-13 09:45:53',0,'Pending'),(395,55,11,444,'2026-07-22 15:16:39',13010.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:16:39',NULL,'2026-08-13 09:46:39',0,'Pending'),(396,55,11,445,'2026-07-23 15:17:21',18370.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:17:21',NULL,'2026-08-13 09:47:21',0,'Pending'),(397,55,11,446,'2026-07-24 15:18:22',18930.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:18:22',NULL,'2026-08-13 09:48:22',0,'Pending'),(398,55,11,447,'2026-07-25 15:19:14',1370.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:19:14',NULL,'2026-08-13 09:49:14',0,'Pending'),(399,55,11,448,'2026-07-26 15:19:53',8095.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:19:53',NULL,'2026-08-13 09:49:53',0,'Pending'),(400,55,11,449,'2026-07-28 15:20:30',2940.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:20:30',NULL,'2026-08-13 09:50:30',0,'Pending'),(401,55,11,450,'2026-07-30 15:22:51',15740.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:22:51',NULL,'2026-08-13 09:52:51',0,'Pending'),(402,55,11,451,'2026-07-31 15:23:29',17010.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:23:29',NULL,'2026-08-13 09:53:29',0,'Pending'),(403,55,11,452,'2026-08-01 15:24:05',26465.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:24:05',NULL,'2026-08-13 09:54:05',0,'Pending'),(404,55,11,453,'2026-08-03 15:25:00',9650.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:25:00',NULL,'2026-08-13 09:55:00',0,'Pending'),(405,55,11,454,'2026-08-04 15:25:42',2260.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:25:42',NULL,'2026-08-13 09:55:42',0,'Pending'),(406,55,11,455,'2026-08-05 15:26:16',10460.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:26:16',NULL,'2026-08-13 09:56:16',0,'Pending'),(407,55,11,456,'2026-08-06 15:26:50',10280.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:26:50',NULL,'2026-08-13 09:56:50',0,'Pending'),(408,55,11,457,'2026-08-07 15:27:36',20370.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:27:36',NULL,'2026-08-13 09:57:36',0,'Pending'),(409,55,11,458,'2026-08-08 15:28:10',5910.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:28:10',NULL,'2026-08-13 09:58:10',0,'Pending'),(410,55,11,459,'2026-08-10 15:28:36',4300.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:28:36',NULL,'2026-08-13 09:58:36',0,'Pending'),(411,55,11,460,'2026-08-11 15:29:00',13880.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:29:00',NULL,'2026-08-13 09:59:00',0,'Pending'),(412,55,11,461,'2026-08-12 15:29:34',6770.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-13 15:29:34',NULL,'2026-08-13 09:59:34',0,'Pending'),(413,56,11,462,'2026-08-13 00:00:00',40000.00,22880.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,11,'2026-08-13 16:06:07',NULL,'2026-08-13 10:36:07',0,'Pending'),(414,3,11,39,'2026-08-14 00:00:00',11950.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 10:15:34',NULL,'2026-08-14 04:45:34',0,'Pending'),(415,3,11,292,'2026-08-14 00:00:00',9320.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 10:15:34',NULL,'2026-08-14 04:45:34',0,'Pending'),(416,3,11,297,'2026-08-14 00:00:00',975.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 10:15:34',NULL,'2026-08-14 04:45:34',0,'Pending'),(417,3,11,309,'2026-08-14 00:00:00',3745.00,13245.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 10:15:34',NULL,'2026-08-14 04:45:34',0,'Pending'),(418,3,11,310,'2026-08-14 00:00:00',6390.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 10:15:34',NULL,'2026-08-14 04:45:34',0,'Pending'),(419,3,11,316,'2026-08-14 00:00:00',1120.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 10:15:34',NULL,'2026-08-14 04:45:34',0,'Pending'),(420,33,11,416,'2026-08-14 00:00:00',3600.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 11:02:31',NULL,'2026-08-14 05:32:31',0,'Pending'),(421,33,11,463,'2026-08-14 00:00:00',750.00,750.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 11:28:47',NULL,'2026-08-14 05:58:47',0,'Pending'),(422,32,11,465,'2026-08-13 11:36:20',11250.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 11:36:20',NULL,'2026-08-14 06:06:20',0,'Pending'),(423,32,11,466,'2026-08-13 11:39:06',15750.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 11:39:06',NULL,'2026-08-14 06:09:06',0,'Pending'),(424,32,11,467,'2026-08-14 11:40:36',6100.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 11:40:36',NULL,'2026-08-14 06:10:36',0,'Pending'),(425,55,11,470,'2026-08-13 13:47:59',4490.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 13:47:59',NULL,'2026-08-14 08:17:59',0,'Pending'),(426,32,11,468,'2026-08-14 00:00:00',1800.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 13:49:49',NULL,'2026-08-14 08:19:49',0,'Pending'),(427,32,11,473,'2026-08-14 13:57:01',250.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 13:57:01',NULL,'2026-08-14 08:27:01',0,'Pending'),(428,32,11,474,'2026-08-14 14:01:15',1680.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,11,'2026-08-14 14:01:15',NULL,'2026-08-14 08:31:15',0,'Pending');
/*!40000 ALTER TABLE `customer_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_subscription_notices`
--

DROP TABLE IF EXISTS `customer_subscription_notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_subscription_notices` (
  `CustomerID` int NOT NULL,
  `InvoiceNo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InvoiceDate` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ToDomain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DetailDesc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RenewalPeriod` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `UpdatedAt` datetime DEFAULT NULL,
  `BankID` int DEFAULT '4',
  PRIMARY KEY (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_subscription_notices`
--

LOCK TABLES `customer_subscription_notices` WRITE;
/*!40000 ALTER TABLE `customer_subscription_notices` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_subscription_notices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drawings`
--

DROP TABLE IF EXISTS `drawings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drawings` (
  `DrawingID` int NOT NULL AUTO_INCREMENT,
  `DrawingDate` date NOT NULL,
  `EmployeeID` int NOT NULL,
  `WithdrawalType` varchar(100) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `BankID` int DEFAULT NULL,
  `Amount` decimal(18,2) NOT NULL,
  `Payee` varchar(255) NOT NULL,
  `Description` text,
  `Attachment` varchar(255) DEFAULT NULL,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` datetime NOT NULL,
  PRIMARY KEY (`DrawingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drawings`
--

LOCK TABLES `drawings` WRITE;
/*!40000 ALTER TABLE `drawings` DISABLE KEYS */;
/*!40000 ALTER TABLE `drawings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `EmployeeID` int NOT NULL AUTO_INCREMENT,
  `EmployeeNo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MemberNo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EmployeeName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NIC` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MobileNo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Address` text COLLATE utf8mb4_unicode_ci,
  `Designation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Department` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BasicSalary` decimal(18,2) DEFAULT '0.00',
  `Allowance` decimal(10,2) DEFAULT '0.00',
  `OTPerHour` decimal(10,2) DEFAULT '0.00',
  `CostPerMinute` decimal(10,2) DEFAULT '0.00',
  `JoinDate` date DEFAULT NULL,
  `Status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ModifiedDate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`EmployeeID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (1,'06','','U.Prithika','200361210523','0711074569','prithikaudaya@gmail.com','$2y$10$fPssQSxgbRuAvGuIeYpn/.RGt46LbJCoQbgcRdXbBthf47sIADFF2','51/3 Attampola watha, hendala, wattala.','Graphic designer','Designing',0.00,0.00,0.00,0.00,'2026-07-01','Active','2026-07-31 13:20:25','2026-07-31 08:10:46',0),(2,'07','','R.Shobika','200667002753','0764438996','','$2y$10$3rE/qJ8pnH.Uccz3QYCgh.Wq5ZctrvBoINAkdYHuyOfWavRH3KPX.','133/5/i/1/1, matagoda, hendala, wattala.','Accountant','Accounts',0.00,0.00,0.00,0.00,'2026-07-01','Active','2026-07-31 13:22:30','2026-07-31 08:11:22',0),(3,'02','','S.Sidhuharan','990210225v','0754555421','sidhuharan21@gmail.com','$2y$10$8jHrNav7G6/JHiyh1B2eUekt8mRn4BRHY5VXHWDlj4RQvVJb25ADa','162/576A, Madhampitiya rd, colombo-15','Graphic designer','Designing',0.00,0.00,0.00,0.00,'2020-07-01','Active','2026-07-31 13:47:32','2026-07-31 08:17:32',0),(4,'04','','B Ajanthan','970270523V','0741656751','ajanthanajay614@gmail.com','$2y$10$/eEkZ.KRshS90RX7dsO/pOCtR3UfsOBNk.O21HNwP8Yeon71Ib.ja','','Graphic designer','Designing',0.00,0.00,0.00,0.00,'2025-02-26','Active','2026-07-31 15:03:44','2026-08-01 06:37:41',0),(5,'03','','kumar','691441300v','0761583611','','$2y$10$VO6prYUGLm41NDuEjf6ahOpYyyOFSq7hR31XJbtnECpEfav1862Ye','125/3rd lane,kaludhamulla rd, Moratuwa','','',0.00,0.00,0.00,0.00,'2026-07-31','Active','2026-07-31 15:07:39','2026-07-31 09:37:39',0),(6,'05','','M.Abishek','','0764716815','diron7986@gmail.com','$2y$10$bgxzXKxNWRWWexFWg9FJiulf3H8GyLTNIX1iBtrh0Wp9Xc1MraC/e','','','Production',0.00,0.00,0.00,0.00,'2026-04-03','Active','2026-07-31 15:12:09','2026-07-31 09:42:09',0);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_advances`
--

DROP TABLE IF EXISTS `employee_advances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_advances` (
  `AdvanceID` int NOT NULL AUTO_INCREMENT,
  `EmployeeID` int NOT NULL,
  `AdvanceDate` date NOT NULL,
  `Amount` decimal(18,2) NOT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci,
  `PaymentMethod` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BankID` int DEFAULT NULL,
  `JournalID` int DEFAULT NULL,
  `AmountRecovered` decimal(18,2) DEFAULT '0.00',
  `Status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `IsDeleted` tinyint(1) DEFAULT '0',
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AdvanceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_advances`
--

LOCK TABLES `employee_advances` WRITE;
/*!40000 ALTER TABLE `employee_advances` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_advances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_leaves`
--

DROP TABLE IF EXISTS `employee_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_leaves` (
  `LeaveID` int NOT NULL AUTO_INCREMENT,
  `EmployeeID` int NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `LeaveType` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Reason` text COLLATE utf8mb4_unicode_ci,
  `Status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `CreatedDate` datetime NOT NULL,
  `Remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`LeaveID`),
  KEY `EmployeeID` (`EmployeeID`),
  CONSTRAINT `employee_leaves_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_leaves`
--

LOCK TABLES `employee_leaves` WRITE;
/*!40000 ALTER TABLE `employee_leaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_customers`
--

DROP TABLE IF EXISTS `expense_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_customers` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ExpenseID` int NOT NULL,
  `CustomerId` int NOT NULL,
  `DomainName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_customers`
--

LOCK TABLES `expense_customers` WRITE;
/*!40000 ALTER TABLE `expense_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `expense_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `ExpenseID` int NOT NULL AUTO_INCREMENT,
  `ExpenseDate` date DEFAULT NULL,
  `AccountID` int DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Payee` varchar(255) DEFAULT NULL,
  `Amount` decimal(18,2) DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `BankID` int DEFAULT NULL,
  `ChequeNo` varchar(50) DEFAULT NULL,
  `ChequeDate` date DEFAULT NULL,
  `Remarks` text,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `ExpenseNo` varchar(20) NOT NULL,
  `EmployeeID` int DEFAULT NULL,
  `CustomerId` int DEFAULT NULL,
  `DomainName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ExpenseID`),
  KEY `fk_exp_bank` (`BankID`),
  KEY `fk_exp_account` (`AccountID`),
  CONSTRAINT `fk_exp_account` FOREIGN KEY (`AccountID`) REFERENCES `chart_of_accounts` (`AccountID`),
  CONSTRAINT `fk_exp_bank` FOREIGN KEY (`BankID`) REFERENCES `banks` (`BankID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'2026-07-31',10,'Salary payout for 2026-07','R.Shobika',35000.00,'Cash',NULL,NULL,NULL,'','2026-08-03 13:21:31',1,'',2,NULL,NULL),(2,'2026-07-31',10,'Salary payout for 2026-07','R.Shobika',35000.00,'Cash',NULL,NULL,NULL,'','2026-08-03 13:41:10',1,'',2,NULL,NULL),(3,'2026-07-06',68,'A3 Photocopy','',2800.00,'Cash',NULL,'','0000-00-00','','2026-08-12 11:31:36',0,'',NULL,NULL,NULL),(4,'2026-07-18',13,'Cutting','',2800.00,'Cash',NULL,'','0000-00-00','','2026-08-12 11:33:01',0,'',NULL,NULL,NULL),(5,'2026-07-04',13,'Cutting','',7850.00,'Cash',NULL,'','0000-00-00','','2026-08-12 11:33:34',0,'',NULL,NULL,NULL),(6,'2026-07-14',12,'Courier charge','',740.00,'Cash',NULL,'','0000-00-00','','2026-08-12 11:34:44',0,'',NULL,NULL,NULL),(7,'2026-07-16',13,'P.B Cord ','',13650.00,'Cash',NULL,'','0000-00-00','','2026-08-12 11:47:46',0,'',NULL,NULL,NULL),(8,'2026-07-04',68,'Printing','',17000.00,'Cash',NULL,'','0000-00-00','','2026-08-12 11:49:10',0,'',NULL,NULL,NULL),(9,'2026-08-12',68,'PRINTING WORKS','',58050.00,'Cash',NULL,'','0000-00-00','','2026-08-12 13:45:26',0,'',NULL,NULL,NULL),(10,'2026-08-12',68,'Anoshan','',5100.00,'Cash',NULL,'','0000-00-00','','2026-08-12 17:03:01',0,'',NULL,NULL,NULL),(11,'2026-08-13',12,'PICK ME & AUTO','',650.00,'Cash',NULL,'','0000-00-00','','2026-08-13 12:47:06',0,'',NULL,NULL,NULL),(12,'2026-08-13',13,'BROOM STICK ','',515.00,'Cash',NULL,'','0000-00-00','','2026-08-13 12:47:52',0,'',NULL,NULL,NULL),(13,'2026-08-13',13,'BROOM STICK ','',515.00,'Cash',NULL,'','0000-00-00','','2026-08-13 12:47:52',0,'',NULL,NULL,NULL);
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_year`
--

DROP TABLE IF EXISTS `financial_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `financial_year` (
  `YearID` int NOT NULL AUTO_INCREMENT,
  `CompanyID` int DEFAULT NULL,
  `YearName` varchar(20) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`YearID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_year`
--

LOCK TABLES `financial_year` WRITE;
/*!40000 ALTER TABLE `financial_year` DISABLE KEYS */;
/*!40000 ALTER TABLE `financial_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grn`
--

DROP TABLE IF EXISTS `grn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grn` (
  `GrnID` int NOT NULL AUTO_INCREMENT,
  `GrnNo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `GrnDate` date NOT NULL,
  `VendorID` int NOT NULL,
  `VendorName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `PurchaseOrderNo` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Remarks` text COLLATE utf8mb4_general_ci,
  `CreatedDate` datetime DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`GrnID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grn`
--

LOCK TABLES `grn` WRITE;
/*!40000 ALTER TABLE `grn` DISABLE KEYS */;
/*!40000 ALTER TABLE `grn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grn_items`
--

DROP TABLE IF EXISTS `grn_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grn_items` (
  `GrnItemID` int NOT NULL AUTO_INCREMENT,
  `GrnID` int NOT NULL,
  `ItemID` int NOT NULL,
  `ItemName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  `QtyReceived` decimal(18,2) DEFAULT '1.00',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`GrnItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grn_items`
--

LOCK TABLES `grn_items` WRITE;
/*!40000 ALTER TABLE `grn_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `grn_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incomes`
--

DROP TABLE IF EXISTS `incomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incomes` (
  `IncomeID` int NOT NULL AUTO_INCREMENT,
  `IncomeDate` date DEFAULT NULL,
  `AccountID` int DEFAULT NULL,
  `Amount` decimal(18,2) DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `BankID` int DEFAULT NULL,
  `Remarks` text,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IncomeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incomes`
--

LOCK TABLES `incomes` WRITE;
/*!40000 ALTER TABLE `incomes` DISABLE KEYS */;
/*!40000 ALTER TABLE `incomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issued_cheques`
--

DROP TABLE IF EXISTS `issued_cheques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issued_cheques` (
  `IssuedChequeID` int NOT NULL AUTO_INCREMENT,
  `ChequeNo` varchar(50) DEFAULT NULL,
  `ChequeDate` date DEFAULT NULL,
  `ClearedDate` date DEFAULT NULL,
  `VendorID` int DEFAULT NULL,
  `PayeeName` varchar(150) DEFAULT NULL,
  `AccountID` int DEFAULT NULL,
  `BankID` int DEFAULT NULL,
  `Amount` decimal(18,2) DEFAULT '0.00',
  `Status` varchar(20) DEFAULT 'Issued',
  `Remarks` text,
  `CreatedDate` datetime DEFAULT NULL,
  `ExpenseID` int DEFAULT NULL,
  PRIMARY KEY (`IssuedChequeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issued_cheques`
--

LOCK TABLES `issued_cheques` WRITE;
/*!40000 ALTER TABLE `issued_cheques` DISABLE KEYS */;
/*!40000 ALTER TABLE `issued_cheques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `ItemID` int NOT NULL AUTO_INCREMENT,
  `ItemName` varchar(39) DEFAULT NULL,
  `ItemType` enum('Product','Service') DEFAULT 'Product',
  `ItemDescription` varchar(25) DEFAULT NULL,
  `PurchasePrice` int DEFAULT NULL,
  `SellingPrice` int DEFAULT NULL,
  `Stock` int DEFAULT NULL,
  `CategoryID` int DEFAULT NULL,
  `CategoryName` varchar(25) DEFAULT NULL,
  `BrandID` int DEFAULT NULL,
  `BrandName` varchar(12) DEFAULT NULL,
  `CreatedAt` varchar(16) DEFAULT NULL,
  `UpdatedAt` varchar(16) DEFAULT NULL,
  `IsDeleted` int DEFAULT NULL,
  `DomainName` varchar(255) DEFAULT NULL,
  `RenewalDate` date DEFAULT NULL,
  `ServiceDuration` varchar(100) DEFAULT NULL,
  `ItemCode` varchar(100) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Length` decimal(10,2) DEFAULT NULL,
  `Width` decimal(10,2) DEFAULT NULL,
  `Height` decimal(10,2) DEFAULT NULL,
  `DimensionUnit` varchar(10) DEFAULT 'cm',
  `Weight` decimal(10,2) DEFAULT NULL,
  `WeightUnit` varchar(10) DEFAULT 'kg',
  `Unit` varchar(50) DEFAULT 'Pcs',
  `PreferredVendorID` int DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'A4-80 gsm','Product','',0,80,0,1,'bank',0,'','2026-07-30 11:33','2026-07-30 11:33',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(2,'A4- 120 gsm','Product','',0,100,0,1,'bank',0,'','2026-07-30 11:34','2026-07-30 11:34',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(3,'A4- 150 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 11:34','2026-07-30 11:34',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(4,'A4-100 gsm','Product','',0,80,0,1,'bank',0,'','2026-07-30 11:35','2026-07-30 11:35',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(5,'12 x 18 - 80 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 11:36','2026-07-30 11:36',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(6,'12 x 18 - 100 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 11:37','2026-07-30 11:37',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(7,'12 x 18 - 120 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 11:38','2026-07-30 11:38',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(8,'12 x 18 - 150 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 11:38','2026-07-30 11:38',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(9,'A4-100 gsm','Product','',0,80,0,2,'Art paper',0,'','2026-07-30 11:43','2026-07-30 11:43',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(10,'A4- 120 gsm','Product','',0,80,0,2,'Art paper',0,'','2026-07-30 11:44','2026-07-30 11:44',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(11,'A4- 150 gsm','Product','',0,100,0,2,'Art paper',0,'','2026-07-30 11:46','2026-07-30 11:46',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(12,'A4- 230 gsm','Product','',0,120,0,3,'Art board',0,'','2026-07-30 11:48','2026-07-30 11:48',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(13,'A4-250 gsm','Product','',0,130,0,3,'Art board',0,'','2026-07-30 11:48','2026-07-30 11:48',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(14,'A4- 310 gsm','Product','',0,150,0,3,'Art board',0,'','2026-07-30 11:49','2026-07-30 11:49',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(15,'A4-250 gsm','Product','',0,120,0,4,'Ivory board',0,'','2026-07-30 11:49','2026-07-30 11:49',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(16,'A4- 300 gsm','Product','',0,150,0,4,'Ivory board',0,'','2026-07-30 11:50','2026-07-30 11:50',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(17,'A4-250 gsm','Product','',0,150,0,5,'Ice shine',0,'','2026-07-30 11:50','2026-07-30 11:50',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(18,'A4- 300 gsm','Product','',0,170,0,5,'Ice shine',0,'','2026-07-30 11:51','2026-07-30 11:51',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(19,'A4-250 gsm','Product','',0,150,0,6,'cream shine',0,'','2026-07-30 11:51','2026-07-30 11:51',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(20,'A4','Product','',0,100,0,7,'Sticker',0,'','2026-07-30 11:51','2026-07-30 11:51',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(21,'Clear A4','Product','',0,150,0,7,'Sticker',0,'','2026-07-30 11:52','2026-07-30 11:52',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(22,'PVC 9 x 12','Product','',0,150,0,7,'Sticker',0,'','2026-07-30 11:53','2026-07-30 11:53',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(23,'Gold A4','Product','',0,1000,0,7,'Sticker',0,'','2026-07-30 11:54','2026-07-30 11:54',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(24,'Silver A4','Product','',0,1000,0,7,'Sticker',0,'','2026-07-30 11:54','2026-07-30 11:54',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(25,'Paper 9 x 12','Product','',0,120,0,7,'Sticker',0,'','2026-07-30 11:54','2026-07-30 11:54',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(26,'Paper 10 x 12','Product','',0,200,0,7,'Sticker',0,'','2026-07-30 11:57','2026-07-30 11:57',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(27,'Paper 13 x 19','Product','',0,100,0,7,'Sticker',0,'','2026-07-30 11:58','2026-07-30 11:58',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(28,'9 x 12 - PVC ','Product','',0,100,0,7,'Sticker',0,'','2026-07-30 11:59','2026-07-30 11:59',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(29,'12.5 x 13 - 260 gsm','Product','',0,100,0,3,'Art board',0,'','2026-07-30 11:59','2026-07-30 11:59',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(30,'12.5 x 13 - 310 gsm','Product','',0,120,0,3,'Art board',0,'','2026-07-30 12:00','2026-07-30 12:00',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(31,'12.5 x 13 - 250 gsm','Product','',0,100,0,8,'Matte Ivory',0,'','2026-07-30 12:01','2026-07-30 12:01',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(32,'12.5 x 13 - 300 gsm','Product','',0,120,0,8,'Matte Ivory',0,'','2026-07-30 12:01','2026-07-30 12:01',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(33,'12 x 18 - 80 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 12:02','2026-07-30 12:02',1,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(34,'12 x 18 - 100 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 12:02','2026-07-30 12:02',1,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(35,'12 x 18 - 120 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 12:02','2026-07-30 12:02',1,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(36,'12 x 18 - 150 gsm','Product','',0,150,0,1,'bank',0,'','2026-07-30 12:03','2026-07-30 12:03',1,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(37,'12 x 18 - 100 gsm','Product','',0,150,0,2,'Art paper',0,'','2026-07-30 12:03','2026-07-30 12:03',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(38,'12 x 18 - 120 gsm','Product','',0,150,0,2,'Art paper',0,'','2026-07-30 12:03','2026-07-30 12:03',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(39,'12 x 18 - 150 gsm','Product','',0,150,0,2,'Art paper',0,'','2026-07-30 12:04','2026-07-30 12:04',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(40,'12 x 18 - 230 gsm','Product','',0,150,0,3,'Art board',0,'','2026-07-30 12:05','2026-07-30 12:05',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(41,'12 x 18 - 250 gsm','Product','',0,150,0,3,'Art board',0,'','2026-07-30 12:06','2026-07-30 12:06',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(42,'12 x 18 - 310 gsm','Product','',0,150,0,3,'Art board',0,'','2026-07-30 12:06','2026-07-30 12:06',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(43,'12 x 18 - 250 gsm','Product','',150,0,0,8,'Matte Ivory',0,'','2026-07-30 12:06','2026-07-30 12:06',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(44,'12 x 18 - 300 gsm','Product','',0,150,0,8,'Matte Ivory',0,'','2026-07-30 12:07','2026-07-30 12:07',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(45,'12 x 18 - 250 gsm','Product','',0,200,0,5,'Ice shine',0,'','2026-07-30 12:07','2026-07-30 12:07',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(46,'12 x 18 - 300 gsm','Product','',0,200,0,5,'Ice shine',0,'','2026-07-30 12:08','2026-07-30 12:08',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(47,'12 x 18 - 250 gsm','Product','',0,200,0,6,'cream shine',0,'','2026-07-30 12:09','2026-07-30 12:09',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(48,'A4','Product','',0,80,0,9,'Trasing',0,'','2026-07-30 12:09','2026-07-30 12:09',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(49,'A3','Product','',0,150,0,9,'Trasing',0,'','2026-07-30 12:09','2026-07-30 12:09',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(50,'A4','Product','',0,35,0,10,'Print',0,'','2026-07-30 12:10','2026-07-30 12:10',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(51,'A3','Product','',0,70,0,10,'Print',0,'','2026-07-30 12:10','2026-07-30 12:10',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(52,'12.5 x 13 ','Product','',0,40,0,10,'Print',0,'','2026-07-30 12:10','2026-07-30 12:10',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(53,'10.5 x 12.5','Product','',0,35,0,10,'Print',0,'','2026-07-30 12:11','2026-07-30 12:11',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(54,'13 x 19','Product','',0,80,0,10,'Print',0,'','2026-07-30 12:11','2026-07-30 12:11',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(55,'12.5 x 19','Product','',0,80,0,10,'Print',0,'','2026-07-30 12:11','2026-07-30 12:11',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(56,'A4','Product','',0,150,0,11,'Photo sheet',0,'','2026-07-30 12:12','2026-07-30 12:12',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(57,'A3','Product','',0,300,0,11,'Photo sheet',0,'','2026-07-30 12:12','2026-07-30 12:12',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(58,'12 x 18 ','Product','',0,300,0,11,'Photo sheet',0,'','2026-07-30 12:12','2026-07-30 12:12',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(59,'12 x 19 PVC ','Product','',0,500,0,11,'Photo sheet',0,'','2026-07-30 12:12','2026-07-30 12:12',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(60,'PVC - A4','Product','',0,250,0,11,'Photo sheet',0,'','2026-07-30 12:13','2026-07-30 12:13',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(61,'White mug','Product','',0,1300,0,12,'Mug',0,'','2026-07-30 12:13','2026-07-30 12:13',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(62,'Magic mug','Product','',0,1600,0,12,'Mug',0,'','2026-07-30 12:14','2026-07-30 12:14',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(63,'Gold ring mug','Product','',0,1500,0,12,'Mug',0,'','2026-07-30 12:14','2026-07-30 12:14',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(64,'Colour mug','Product','',0,1500,0,12,'Mug',0,'','2026-07-30 12:14','2026-07-30 12:14',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(65,'12 x 18 ','Product','',0,3200,0,13,'Photo frame',0,'','2026-07-30 12:15','2026-07-30 12:15',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(66,'10 x 15','Product','',0,2500,0,13,'Photo frame',0,'','2026-07-30 12:15','2026-07-30 12:15',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(67,'8 x 6','Product','',0,800,0,13,'Photo frame',0,'','2026-07-30 12:15','2026-07-30 12:15',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(68,'8 x 10','Product','',0,1200,0,13,'Photo frame',0,'','2026-07-30 12:16','2026-07-30 12:16',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(69,'10 x 12','Product','',0,1500,0,13,'Photo frame',0,'','2026-07-30 12:16','2026-07-30 12:16',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(70,'A4','Product','',1300,0,0,13,'Photo frame',0,'','2026-07-30 12:16','2026-07-30 12:16',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(71,'A3','Product','',0,2500,0,13,'Photo frame',0,'','2026-07-30 12:16','2026-07-30 12:16',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(72,'6 x 4','Product','',0,650,0,13,'Photo frame',0,'','2026-07-30 12:16','2026-07-30 12:16',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(73,'13 x 19','Product','',0,4000,0,13,'Photo frame',0,'','2026-07-30 12:17','2026-07-30 12:17',0,'','0000-00-00','','',NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(74,'Conqueror Paper 120GSM','Product','Conqueror Paper 120GSM',17,25,0,2,'Art paper',NULL,NULL,'2026-08-22 13:11',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_card`
--

DROP TABLE IF EXISTS `job_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_card` (
  `JobCardID` int NOT NULL AUTO_INCREMENT,
  `JobCardNo` varchar(50) NOT NULL,
  `JobCardDate` date NOT NULL,
  `QuotationID` int DEFAULT NULL,
  `CustomerID` int NOT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `ServiceID` int DEFAULT NULL,
  `ServiceName` varchar(255) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text,
  `Priority` varchar(50) NOT NULL DEFAULT 'Medium',
  `Status` varchar(50) NOT NULL DEFAULT 'Pending',
  `AssignedTo` varchar(100) DEFAULT NULL,
  `EmployeeID` int DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Deadline` date DEFAULT NULL,
  `EstimatedAmount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `EstimatedDays` int DEFAULT '0',
  `ActualHours` decimal(10,2) DEFAULT '0.00',
  `ActualCost` decimal(10,2) DEFAULT '0.00',
  `ProfitAmount` decimal(10,2) DEFAULT '0.00',
  `Remarks` text,
  `CreatedBy` varchar(100) DEFAULT NULL,
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(100) DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`JobCardID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_card`
--

LOCK TABLES `job_card` WRITE;
/*!40000 ALTER TABLE `job_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_card_employees`
--

DROP TABLE IF EXISTS `job_card_employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_card_employees` (
  `JobCardEmployeeID` int NOT NULL AUTO_INCREMENT,
  `JobCardID` int NOT NULL,
  `EmployeeID` int NOT NULL,
  `WorkDate` date DEFAULT NULL,
  `Days` decimal(10,2) DEFAULT '0.00',
  `Hours` decimal(10,2) DEFAULT '0.00',
  `Minutes` decimal(10,2) DEFAULT '0.00',
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  `TotalHours` decimal(10,2) DEFAULT '0.00',
  `TotalMinutes` int DEFAULT '0',
  `CostPerMinute` decimal(10,2) DEFAULT '0.00',
  `TotalCost` decimal(10,2) DEFAULT '0.00',
  `Status` varchar(50) DEFAULT 'Pending',
  `WorkDescription` text,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`JobCardEmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_card_employees`
--

LOCK TABLES `job_card_employees` WRITE;
/*!40000 ALTER TABLE `job_card_employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_card_employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_card_tasks`
--

DROP TABLE IF EXISTS `job_card_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_card_tasks` (
  `TaskID` int NOT NULL AUTO_INCREMENT,
  `JobCardID` int NOT NULL,
  `TaskName` varchar(255) NOT NULL,
  `TaskDescription` text,
  `AssignedTo` varchar(100) DEFAULT NULL,
  `TaskStatus` varchar(50) NOT NULL DEFAULT 'Pending',
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Remarks` text,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`TaskID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_card_tasks`
--

LOCK TABLES `job_card_tasks` WRITE;
/*!40000 ALTER TABLE `job_card_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_card_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entries`
--

DROP TABLE IF EXISTS `journal_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_entries` (
  `JournalID` int NOT NULL AUTO_INCREMENT,
  `JournalDate` date NOT NULL,
  `ReferenceNo` varchar(100) DEFAULT NULL,
  `Description` text,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`JournalID`)
) ENGINE=InnoDB AUTO_INCREMENT=603 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entries`
--

LOCK TABLES `journal_entries` WRITE;
/*!40000 ALTER TABLE `journal_entries` DISABLE KEYS */;
INSERT INTO `journal_entries` VALUES (3,'2026-08-04','CP-1','Customer Payment Received',NULL,'2026-08-04 09:14:07'),(4,'2026-08-04','CP-2','Customer Payment Received',NULL,'2026-08-04 09:14:07'),(5,'2026-08-04','CP-3','Customer Payment Received',NULL,'2026-08-04 09:14:07'),(6,'2026-08-04','CP-4','Customer Payment Received',NULL,'2026-08-04 09:29:47'),(7,'2026-08-04','CP-5','Customer Payment Received',NULL,'2026-08-04 09:29:47'),(8,'2026-08-04','CP-6','Customer Payment Received',NULL,'2026-08-04 09:29:47'),(9,'2026-08-04','CP-7','Customer Payment Received',NULL,'2026-08-04 09:34:42'),(10,'2026-08-04','CP-8','Customer Payment Received',NULL,'2026-08-04 09:34:42'),(11,'2026-08-04','CP-9','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(12,'2026-08-04','CP-10','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(13,'2026-08-04','CP-11','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(14,'2026-08-04','CP-12','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(15,'2026-08-04','CP-13','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(16,'2026-08-04','CP-14','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(17,'2026-08-04','CP-15','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(18,'2026-08-04','CP-16','Customer Payment Received',NULL,'2026-08-04 09:38:12'),(19,'2026-08-04','CP-17','Customer Payment Received',NULL,'2026-08-04 09:41:30'),(20,'2026-08-04','CP-18','Customer Payment Received',NULL,'2026-08-04 09:41:30'),(21,'2026-08-04','CP-19','Customer Payment Received',NULL,'2026-08-04 09:43:05'),(22,'2026-08-04','CP-20','Customer Payment Received',NULL,'2026-08-04 09:44:15'),(23,'2026-08-04','CP-21','Customer Payment Received',NULL,'2026-08-04 09:47:16'),(24,'2026-08-04','CP-22','Customer Payment Received',NULL,'2026-08-04 09:49:03'),(25,'2026-07-01','CP-23','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:37:03'),(26,'2026-07-01','CP-24','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:39:15'),(27,'2026-07-01','CP-25','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:40:30'),(28,'2026-07-01','CP-26','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:41:40'),(29,'2026-07-01','CP-27','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:42:11'),(30,'2026-07-01','CP-28','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:42:37'),(31,'2026-07-02','CP-29','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:43:00'),(32,'2026-07-02','CP-30','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:43:42'),(33,'2026-06-02','CP-31','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:44:10'),(34,'2026-07-02','CP-32','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:44:49'),(35,'2026-07-02','CP-33','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:45:45'),(36,'2026-07-02','CP-34','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:47:36'),(37,'2026-07-02','CP-35','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:53:25'),(38,'2026-07-03','CP-36','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:53:50'),(39,'2026-07-03','CP-37','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:54:19'),(40,'2026-07-03','CP-38','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:54:44'),(41,'2026-07-03','CP-39','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:55:41'),(42,'2026-07-03','CP-40','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:56:16'),(43,'2026-07-04','CP-41','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:56:48'),(44,'2026-07-06','CP-42','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:58:53'),(45,'2026-07-04','CP-43','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 04:59:43'),(46,'2026-07-06','CP-44','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:00:54'),(47,'2026-07-07','CP-45','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:01:28'),(48,'2026-07-06','CP-46','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:02:30'),(49,'2026-07-06','CP-47','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:03:54'),(50,'2026-07-06','CP-48','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:03:54'),(51,'2026-07-06','CP-49','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:04:33'),(52,'2026-07-06','CP-50','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:05:05'),(53,'2026-07-06','CP-51','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:05:42'),(54,'2026-07-07','CP-52','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:06:04'),(55,'2026-07-07','CP-53','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:07:29'),(56,'2026-07-07','CP-54','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:07:50'),(57,'2026-07-07','CP-55','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:08:19'),(58,'2026-07-08','CP-56','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:08:45'),(59,'2026-07-08','CP-57','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:09:22'),(60,'2026-07-08','CP-58','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:10:03'),(61,'2026-07-08','CP-59','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:10:24'),(62,'2026-07-08','CP-60','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:10:48'),(63,'2026-07-09','CP-61','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:11:41'),(64,'2026-07-09','CP-62','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:12:15'),(65,'2026-07-10','CP-63','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:12:50'),(66,'2026-07-10','CP-64','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:13:16'),(67,'2026-07-11','CP-65','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:13:44'),(68,'2026-07-13','CP-66','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:14:08'),(69,'2026-07-13','CP-67','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:14:36'),(70,'2026-07-13','CP-68','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:15:00'),(71,'2026-07-13','CP-69','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:15:32'),(72,'2026-07-02','CP-70','Customer Payment Received',NULL,'2026-08-05 05:17:07'),(73,'2026-07-14','CP-71','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:18:14'),(74,'2026-07-14','CP-72','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:18:37'),(75,'2026-07-14','CP-73','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:19:08'),(76,'2026-07-07','CP-74','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:19:45'),(77,'2026-07-14','CP-75','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:20:11'),(78,'2026-07-14','CP-76','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:20:53'),(79,'2026-07-15','CP-77','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:21:22'),(80,'2026-07-15','CP-78','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:21:46'),(81,'2026-06-15','CP-79','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:22:16'),(82,'2026-06-15','CP-80','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:22:16'),(83,'2026-06-15','CP-81','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:22:16'),(84,'2026-07-15','CP-82','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:22:41'),(85,'2026-07-15','CP-83','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:23:16'),(86,'2026-07-15','CP-84','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:23:16'),(87,'2026-07-16','CP-85','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:23:48'),(88,'2026-07-16','CP-86','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:24:19'),(89,'2026-07-16','CP-87','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:24:42'),(90,'2026-07-17','CP-88','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:25:13'),(91,'2026-07-17','CP-89','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:25:39'),(92,'2026-07-17','CP-90','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:26:09'),(93,'2026-07-17','CP-91','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:26:42'),(94,'2026-07-17','CP-92','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:27:06'),(95,'2026-07-18','CP-93','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:27:34'),(96,'2026-07-18','CP-94','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:27:57'),(97,'2026-07-20','CP-95','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:28:26'),(98,'2026-07-20','CP-96','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:28:55'),(99,'2026-07-20','CP-97','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:28:55'),(100,'2026-07-20','CP-98','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:29:22'),(101,'2026-07-20','CP-99','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:30:02'),(102,'2026-07-20','CP-100','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:30:49'),(103,'2026-07-21','CP-101','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:31:12'),(104,'2026-07-21','CP-102','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:33:37'),(105,'2026-07-21','CP-103','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:33:37'),(106,'2026-07-22','CP-104','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:34:18'),(107,'2026-07-21','CP-105','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:34:53'),(108,'2026-07-21','CP-106','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:35:44'),(109,'2026-07-21','CP-107','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:36:17'),(110,'2026-07-21','CP-108','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:36:52'),(111,'2026-07-22','CP-109','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:37:16'),(112,'2026-07-22','CP-110','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:37:49'),(113,'2026-07-23','CP-111','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:38:29'),(114,'2026-07-24','CP-112','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:39:11'),(115,'2026-07-24','CP-113','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:39:35'),(116,'2026-07-24','CP-114','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:40:04'),(117,'2026-07-24','CP-115','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:40:34'),(118,'2026-07-24','CP-116','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:41:04'),(119,'2026-07-24','CP-117','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:41:27'),(120,'2026-07-24','CP-118','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:41:52'),(121,'2026-07-24','CP-119','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:42:17'),(122,'2026-07-24','CP-120','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:43:19'),(123,'2026-07-24','CP-121','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:43:45'),(124,'2026-07-25','CP-122','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:44:12'),(125,'2026-07-25','CP-123','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:44:43'),(126,'2026-07-25','CP-124','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:45:13'),(127,'2026-07-25','CP-125','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:45:42'),(128,'2026-07-27','CP-126','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:46:06'),(129,'2026-07-27','CP-127','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:47:35'),(130,'2026-07-27','CP-128','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:48:22'),(131,'2026-07-28','CP-129','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:49:02'),(132,'2026-07-27','CP-130','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:49:39'),(133,'2026-07-28','CP-131','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:52:59'),(134,'2026-07-28','CP-132','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:53:29'),(135,'2026-07-28','CP-133','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:54:04'),(136,'2026-07-30','CP-134','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:54:39'),(137,'2026-07-30','CP-135','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:55:17'),(138,'2026-07-30','CP-136','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:55:42'),(139,'2026-07-30','CP-137','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:56:10'),(140,'2026-07-30','CP-138','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:56:45'),(141,'2026-07-30','CP-139','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:57:08'),(142,'2026-07-30','CP-140','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:57:39'),(143,'2026-07-31','CP-141','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:58:06'),(144,'2026-07-31','CP-142','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:58:32'),(145,'2026-07-31','CP-143','Customer Payment Received (Pay Receipt)',NULL,'2026-08-05 05:59:05'),(146,'2026-07-01','CP-144','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 04:43:07'),(147,'2026-07-01','CP-145','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 04:43:36'),(148,'2026-07-01','CP-146','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 04:43:37'),(149,'2026-07-02','CP-147','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:32:49'),(150,'2026-07-04','CP-148','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:33:29'),(151,'2026-07-04','CP-149','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:33:54'),(152,'2026-08-05','CP-150','Customer Payment Received',NULL,'2026-08-06 05:48:56'),(153,'2026-08-05','CP-151','Customer Payment Received',NULL,'2026-08-06 05:48:56'),(154,'2026-08-05','CP-152','Customer Payment Received',NULL,'2026-08-06 05:48:56'),(155,'2026-08-06','CP-153','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:53:12'),(156,'2026-07-04','CP-154','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:53:38'),(157,'2026-07-04','CP-155','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:54:46'),(158,'2026-07-06','CP-156','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:55:14'),(159,'2026-07-07','CP-157','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:56:51'),(160,'2026-07-07','CP-158','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:57:15'),(161,'2026-07-07','CP-159','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:57:48'),(162,'2026-07-07','CP-160','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 05:58:19'),(163,'2026-07-08','CP-161','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:03:18'),(164,'2026-07-09','CP-162','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:03:52'),(165,'2026-07-09','CP-163','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:04:22'),(166,'2026-07-09','CP-164','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:04:52'),(167,'2026-07-10','CP-165','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:05:15'),(168,'2026-07-11','CP-166','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:05:42'),(169,'2026-07-11','CP-167','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:06:09'),(170,'2026-07-11','CP-168','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:06:09'),(171,'2026-07-11','CP-169','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:06:35'),(172,'2026-07-13','CP-170','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:07:55'),(173,'2026-07-13','CP-171','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:08:22'),(174,'2026-07-13','CP-172','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:08:58'),(175,'2026-07-13','CP-173','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:09:27'),(176,'2026-07-13','CP-174','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:09:28'),(177,'2026-07-13','CP-175','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:09:28'),(178,'2026-07-14','CP-176','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:09:57'),(179,'2026-07-15','CP-177','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:10:29'),(180,'2026-07-15','CP-178','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:10:55'),(181,'2026-07-15','CP-179','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:16:18'),(182,'2026-07-16','CP-180','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:16:46'),(183,'2026-07-16','CP-181','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:17:11'),(184,'2026-07-16','CP-182','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:17:11'),(185,'2026-07-16','CP-183','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:17:35'),(186,'2026-07-16','CP-184','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:18:31'),(187,'2026-07-17','CP-185','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:19:07'),(188,'2026-07-17','CP-186','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:25:44'),(189,'2026-07-16','CP-187','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:26:25'),(190,'2026-07-17','CP-188','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:27:01'),(191,'2026-07-18','CP-189','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:27:39'),(192,'2026-07-18','CP-190','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:28:11'),(193,'2026-07-20','CP-191','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:28:40'),(194,'2026-07-20','CP-192','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:29:13'),(195,'2026-07-20','CP-193','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:29:41'),(196,'2026-07-20','CP-194','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:30:07'),(197,'2026-07-21','CP-195','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:30:31'),(198,'2026-07-21','CP-196','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:32:37'),(199,'2026-07-22','CP-197','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:33:01'),(200,'2026-07-23','CP-198','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:33:35'),(201,'2026-07-23','CP-199','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:38:10'),(202,'2026-07-23','CP-200','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:38:43'),(203,'2026-07-23','CP-201','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:39:07'),(204,'2026-07-23','CP-202','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:39:56'),(205,'2026-07-24','CP-203','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:40:23'),(206,'2026-07-24','CP-204','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:40:59'),(207,'2026-07-25','CP-205','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:42:04'),(208,'2026-07-25','CP-206','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:42:29'),(209,'2026-07-27','CP-207','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:44:46'),(210,'2026-07-27','CP-208','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:45:16'),(211,'2026-07-28','CP-209','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:47:00'),(212,'2026-07-30','CP-210','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:47:32'),(213,'2026-07-30','CP-211','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:47:57'),(214,'2026-07-30','CP-212','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:48:27'),(215,'2026-07-31','CP-213','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:49:43'),(216,'2026-07-31','CP-214','Customer Payment Received (Pay Receipt)',NULL,'2026-08-06 06:50:16'),(217,'2026-07-01','CP-215','Customer Payment Received',NULL,'2026-08-06 06:53:35'),(218,'2026-07-02','CP-216','Customer Payment Received',NULL,'2026-08-06 11:47:04'),(219,'2026-08-06','CP-217','Customer Payment Received',NULL,'2026-08-06 11:51:21'),(220,'2026-07-06','CP-218','Customer Payment Received (Pay Receipt)',NULL,'2026-08-07 05:35:11'),(221,'2026-07-02','CP-219','Customer Payment Received',NULL,'2026-08-07 06:02:52'),(222,'2026-08-07','CP-220','Customer Payment Received',NULL,'2026-08-07 06:04:36'),(223,'2026-07-02','CP-221','Customer Payment Received',NULL,'2026-08-07 06:05:22'),(224,'2026-06-30','CP-222','Customer Payment Received',NULL,'2026-08-07 06:06:26'),(225,'2026-07-06','CP-223','Customer Payment Received',NULL,'2026-08-07 06:09:22'),(226,'2026-07-04','CP-224','Customer Payment Received',NULL,'2026-08-07 06:10:21'),(227,'2026-07-04','CP-225','Customer Payment Received',NULL,'2026-08-07 06:10:21'),(228,'2026-08-03','CP-226','Customer Payment Received',NULL,'2026-08-07 06:17:56'),(229,'2026-08-01','CP-227','Customer Payment Received',NULL,'2026-08-07 06:19:44'),(230,'2026-08-01','CP-228','Customer Payment Received',NULL,'2026-08-07 06:19:44'),(231,'2026-08-01','CP-229','Customer Payment Received',NULL,'2026-08-07 06:19:44'),(232,'2026-07-17','CP-230','Customer Payment Received',NULL,'2026-08-07 06:21:33'),(233,'2026-07-24','CP-231','Customer Payment Received',NULL,'2026-08-07 06:23:40'),(234,'2026-08-07','CP-232','Customer Payment Received',NULL,'2026-08-07 06:30:40'),(235,'2026-08-05','CP-233','Customer Payment Received (Pay Receipt)',NULL,'2026-08-10 05:46:43'),(236,'2026-08-10','CP-234','Customer Payment Received',NULL,'2026-08-10 05:51:00'),(237,'2026-08-10','CP-235','Customer Payment Received',NULL,'2026-08-10 05:51:00'),(238,'2026-08-10','CP-236','Customer Payment Received',NULL,'2026-08-10 05:51:00'),(239,'2026-08-10','CP-237','Customer Payment Received',NULL,'2026-08-10 05:55:37'),(240,'2026-08-10','CP-238','Customer Payment Received',NULL,'2026-08-10 11:25:13'),(241,'2026-08-10','CP-239','Customer Payment Received',NULL,'2026-08-10 11:25:48'),(242,'2026-08-10','CP-240','Customer Payment Received',NULL,'2026-08-10 11:26:34'),(243,'2026-08-10','CP-241','Customer Payment Received',NULL,'2026-08-10 11:28:46'),(244,'2026-08-10','CP-242','Customer Payment Received',NULL,'2026-08-10 11:28:46'),(245,'2026-08-10','CP-243','Customer Payment Received',NULL,'2026-08-10 11:28:46'),(246,'2026-08-10','CP-244','Customer Payment Received',NULL,'2026-08-10 11:28:46'),(247,'2026-08-11','CP-245','Customer Payment Received',NULL,'2026-08-11 04:51:42'),(248,'2026-08-11','CP-246','Customer Payment Received',NULL,'2026-08-11 06:37:53'),(249,'2026-08-11','CP-247','Customer Payment Received',NULL,'2026-08-11 06:37:53'),(250,'2026-08-11','CP-248','Customer Payment Received',NULL,'2026-08-11 06:37:53'),(251,'2026-07-21','CP-249','Customer Payment Received (Pay Receipt)',NULL,'2026-08-11 06:38:48'),(252,'2026-08-11','CP-250','Customer Payment Received',NULL,'2026-08-11 09:23:30'),(253,'2026-08-11','CP-251','Customer Payment Received',NULL,'2026-08-11 10:29:19'),(254,'2026-08-11','CP-252','Customer Payment Received',NULL,'2026-08-11 10:29:19'),(255,'2026-08-11','CP-253','Customer Payment Received',NULL,'2026-08-11 10:29:19'),(256,'2026-08-11','CP-254','Customer Payment Received',NULL,'2026-08-11 10:29:19'),(257,'2026-08-11','CP-255','Customer Payment Received',NULL,'2026-08-11 10:29:19'),(258,'2026-08-11','CP-256','Customer Payment Received',NULL,'2026-08-11 10:29:19'),(259,'2026-08-11','CP-257','Customer Payment Received',NULL,'2026-08-11 10:29:19'),(260,'2026-08-18','PUR-1','Purchase Entry',NULL,'2026-08-12 04:48:25'),(261,'2026-08-18','VP-1','Vendor Payment (Pay Cash)',NULL,'2026-08-12 04:48:25'),(262,'2026-08-18','PUR-2','Purchase Entry',NULL,'2026-08-12 04:50:06'),(263,'2026-08-18','VP-2','Vendor Payment (Pay Cash)',NULL,'2026-08-12 04:50:06'),(264,'2026-08-16','PUR-3','Purchase Entry',NULL,'2026-08-12 04:51:11'),(265,'2026-08-16','VP-3','Vendor Payment (Pay Cash)',NULL,'2026-08-12 04:51:11'),(266,'2026-08-08','PUR-4','Purchase Entry',NULL,'2026-08-12 04:51:53'),(267,'2026-08-08','VP-4','Vendor Payment (Pay Cash)',NULL,'2026-08-12 04:51:53'),(268,'2026-07-09','PUR-5','Purchase Entry',NULL,'2026-08-12 04:53:18'),(269,'2026-07-09','VP-5','Vendor Payment (Pay Cash)',NULL,'2026-08-12 04:53:18'),(270,'2026-08-05','PUR-6','Purchase Entry',NULL,'2026-08-12 04:57:05'),(271,'2026-08-05','VP-6','Vendor Payment (Pay Cash)',NULL,'2026-08-12 04:57:05'),(272,'2026-08-07','PUR-7','Purchase Entry',NULL,'2026-08-12 04:59:43'),(273,'2026-08-07','VP-7','Vendor Payment (Pay Cash)',NULL,'2026-08-12 04:59:43'),(274,'2026-08-07','PUR-8','Purchase Entry',NULL,'2026-08-12 05:01:40'),(275,'2026-08-07','VP-8','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:01:40'),(276,'2026-07-22','PUR-9','Purchase Entry',NULL,'2026-08-12 05:04:26'),(277,'2026-07-22','VP-9','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:04:26'),(278,'2026-07-14','PUR-10','Purchase Entry',NULL,'2026-08-12 05:05:42'),(279,'2026-07-14','VP-10','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:05:42'),(280,'2026-07-07','PUR-11','Purchase Entry',NULL,'2026-08-12 05:07:14'),(281,'2026-07-07','VP-11','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:07:14'),(282,'2026-07-02','PUR-12','Purchase Entry',NULL,'2026-08-12 05:08:07'),(283,'2026-07-02','VP-12','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:08:07'),(284,'2026-08-04','PUR-13','Purchase Entry',NULL,'2026-08-12 05:09:45'),(285,'2026-08-04','VP-13','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:09:45'),(286,'2026-07-06','PUR-14','Purchase Entry',NULL,'2026-08-12 05:11:41'),(287,'2026-07-06','VP-14','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:11:41'),(288,'2026-08-07','PUR-15','Purchase Entry',NULL,'2026-08-12 05:12:18'),(289,'2026-08-07','VP-15','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:12:18'),(290,'2026-07-09','PUR-16','Purchase Entry',NULL,'2026-08-12 05:13:18'),(291,'2026-07-09','VP-16','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:13:18'),(292,'2026-07-14','PUR-17','Purchase Entry',NULL,'2026-08-12 05:14:26'),(293,'2026-07-14','VP-17','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:14:26'),(294,'2026-07-23','PUR-18','Purchase Entry',NULL,'2026-08-12 05:16:10'),(295,'2026-07-23','VP-18','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:16:10'),(296,'2026-07-28','PUR-19','Purchase Entry',NULL,'2026-08-12 05:17:00'),(297,'2026-07-28','VP-19','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:17:00'),(298,'2026-08-07','PUR-20','Purchase Entry',NULL,'2026-08-12 05:17:57'),(299,'2026-08-07','VP-20','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:17:57'),(300,'2026-07-01','PUR-21','Purchase Entry',NULL,'2026-08-12 05:19:27'),(301,'2026-07-01','VP-21','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:19:27'),(302,'2026-07-02','PUR-22','Purchase Entry',NULL,'2026-08-12 05:21:04'),(303,'2026-07-02','VP-22','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:21:04'),(304,'2026-07-03','PUR-23','Purchase Entry',NULL,'2026-08-12 05:21:38'),(305,'2026-07-03','VP-23','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:21:38'),(306,'2026-08-12','PUR-24','Purchase Entry',NULL,'2026-08-12 05:24:23'),(307,'2026-08-12','VP-24','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:24:23'),(308,'2026-07-11','PUR-25','Purchase Entry',NULL,'2026-08-12 05:26:28'),(309,'2026-07-11','VP-25','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:26:28'),(310,'2026-07-13','PUR-26','Purchase Entry',NULL,'2026-08-12 05:28:10'),(311,'2026-07-13','VP-26','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:28:10'),(312,'2026-07-11','PUR-27','Purchase Entry',NULL,'2026-08-12 05:28:37'),(313,'2026-07-11','VP-27','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:28:37'),(314,'2026-07-20','PUR-28','Purchase Entry',NULL,'2026-08-12 05:29:19'),(315,'2026-07-20','VP-28','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:29:19'),(316,'2026-07-21','PUR-29','Purchase Entry',NULL,'2026-08-12 05:30:04'),(317,'2026-07-21','VP-29','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:30:04'),(318,'2026-07-22','PUR-30','Purchase Entry',NULL,'2026-08-12 05:37:05'),(319,'2026-07-22','VP-30','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:37:05'),(320,'2026-07-25','PUR-31','Purchase Entry',NULL,'2026-08-12 05:52:53'),(321,'2026-07-25','VP-31','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:52:53'),(322,'2026-07-20','PUR-32','Purchase Entry',NULL,'2026-08-12 05:53:39'),(323,'2026-07-20','VP-32','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:53:39'),(324,'2026-07-21','PUR-33','Purchase Entry',NULL,'2026-08-12 05:54:26'),(325,'2026-07-21','VP-33','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:54:26'),(326,'2026-07-07','PUR-34','Purchase Entry',NULL,'2026-08-12 05:57:54'),(327,'2026-07-07','VP-34','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:57:54'),(328,'2026-08-10','PUR-35','Purchase Entry',NULL,'2026-08-12 05:58:32'),(329,'2026-08-10','VP-35','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:58:32'),(330,'2026-07-11','PUR-36','Purchase Entry',NULL,'2026-08-12 05:59:59'),(331,'2026-07-11','VP-36','Vendor Payment (Pay Cash)',NULL,'2026-08-12 05:59:59'),(332,'2026-07-06','EXP-3','Expense: A3 Photocopy',1,'2026-08-12 11:31:36'),(333,'2026-07-18','EXP-4','Expense: Cutting',1,'2026-08-12 11:33:01'),(334,'2026-07-04','EXP-5','Expense: Cutting',1,'2026-08-12 11:33:48'),(335,'2026-07-04','EXP-5','Expense: Cutting',1,'2026-08-12 11:33:48'),(336,'2026-07-14','EXP-6','Expense: Courier charge',1,'2026-08-12 11:34:44'),(337,'2026-07-16','EXP-7','Expense: P.B Cord ',1,'2026-08-12 11:47:46'),(338,'2026-07-04','EXP-8','Expense: Printing',1,'2026-08-12 11:49:10'),(339,'2026-07-07','PUR-37','Purchase Entry',NULL,'2026-08-12 06:31:38'),(340,'2026-07-07','VP-37','Vendor Payment (Pay Cash)',NULL,'2026-08-12 06:31:38'),(341,'2026-07-24','PUR-38','Purchase Entry',NULL,'2026-08-12 06:32:42'),(342,'2026-07-24','VP-38','Vendor Payment (Pay Cash)',NULL,'2026-08-12 06:32:43'),(343,'2026-08-08','PUR-39','Purchase Entry',NULL,'2026-08-12 06:34:49'),(344,'2026-08-08','VP-39','Vendor Payment (Pay Cash)',NULL,'2026-08-12 06:34:49'),(345,'2026-08-12','CP-258','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(346,'2026-08-12','CP-259','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(347,'2026-08-12','CP-260','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(348,'2026-08-12','CP-261','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(349,'2026-08-12','CP-262','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(350,'2026-08-12','CP-263','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(351,'2026-08-12','CP-264','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(352,'2026-08-12','CP-265','Customer Payment Received',NULL,'2026-08-12 08:12:54'),(353,'2026-08-12','EXP-9','Expense: PRINTING WORKS',1,'2026-08-12 13:45:26'),(354,'2026-08-12','CP-266','Customer Payment Received',NULL,'2026-08-12 08:21:10'),(355,'2026-08-12','CP-267','Customer Payment Received',NULL,'2026-08-12 08:21:10'),(356,'2026-08-12','CP-268','Customer Payment Received',NULL,'2026-08-12 08:21:10'),(357,'2026-08-12','PUR-40','Purchase Entry',NULL,'2026-08-12 09:40:48'),(358,'2026-08-12','VP-40','Vendor Payment (Pay Cash)',NULL,'2026-08-12 09:40:48'),(359,'2026-08-12','EXP-10','Expense: Anoshan',1,'2026-08-12 17:03:01'),(360,'2026-08-12','PUR-41','Purchase Entry',NULL,'2026-08-12 15:30:15'),(361,'2026-08-12','CP-269','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 04:43:45'),(362,'2026-08-13','CP-270','Customer Payment Received',NULL,'2026-08-13 04:44:46'),(363,'2026-08-13','CP-271','Customer Payment Received',NULL,'2026-08-13 04:44:46'),(364,'2026-08-13','CP-272','Customer Payment Received',NULL,'2026-08-13 04:44:46'),(365,'2026-08-13','CP-273','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 04:46:57'),(366,'2026-08-13','CP-274','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(367,'2026-08-13','CP-275','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(368,'2026-08-13','CP-276','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(369,'2026-08-13','CP-277','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(370,'2026-08-13','CP-278','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(371,'2026-08-13','CP-279','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(372,'2026-08-13','CP-280','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(373,'2026-08-13','CP-281','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(374,'2026-08-13','CP-282','Customer Payment Received',NULL,'2026-08-13 04:59:26'),(375,'2026-08-13','CP-283','Customer Payment Received',NULL,'2026-08-13 04:59:27'),(376,'2026-08-13','CP-284','Customer Payment Received',NULL,'2026-08-13 04:59:27'),(377,'2026-08-13','CP-285','Customer Payment Received',NULL,'2026-08-13 04:59:27'),(378,'2026-08-13','CP-286','Customer Payment Received',NULL,'2026-08-13 04:59:27'),(379,'2026-08-13','CP-287','Customer Payment Received',NULL,'2026-08-13 04:59:27'),(380,'2026-08-13','CP-288','Customer Payment Received',NULL,'2026-08-13 04:59:27'),(381,'2026-08-13','CP-289','Customer Payment Received',NULL,'2026-08-13 04:59:27'),(382,'2026-08-11','PUR-42','Purchase Entry',NULL,'2026-08-13 05:07:21'),(383,'2026-08-13','VP-41','Vendor Payment',NULL,'2026-08-13 05:31:53'),(384,'2026-07-02','PUR-43','Purchase Entry',NULL,'2026-08-13 05:36:21'),(385,'2026-08-13','PUR-44','Purchase Entry',NULL,'2026-08-13 05:37:28'),(386,'2026-08-13','PUR-45','Purchase Entry',NULL,'2026-08-13 05:43:10'),(387,'2026-08-13','VP-42','Vendor Payment',NULL,'2026-08-13 05:43:33'),(388,'2026-08-01','CP-290','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 05:46:31'),(389,'2026-08-01','CP-291','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 05:47:42'),(390,'2026-08-01','CP-292','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 05:49:08'),(391,'2026-08-01','CP-293','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 05:50:14'),(392,'2026-08-01','CP-294','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 05:51:53'),(393,'2026-08-01','CP-295','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 05:52:55'),(394,'2026-08-03','CP-296','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:02:24'),(395,'2026-08-03','CP-297','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:03:03'),(396,'2026-08-03','CP-298','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:04:13'),(397,'2026-08-03','CP-299','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:04:54'),(398,'2026-08-03','CP-300','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:05:50'),(399,'2026-08-03','CP-301','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:06:26'),(400,'2026-08-03','CP-302','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:09:01'),(401,'2026-08-04','CP-303','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:09:59'),(402,'2026-08-04','CP-304','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:10:36'),(403,'2026-08-04','CP-305','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:13:13'),(404,'2026-08-05','CP-306','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:15:16'),(405,'2026-08-05','CP-307','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:16:00'),(406,'2026-08-05','CP-308','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:16:26'),(407,'2026-08-05','CP-309','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:16:50'),(408,'2026-08-05','CP-310','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:18:01'),(409,'2026-08-06','CP-311','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:18:46'),(410,'2026-08-06','CP-312','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:19:55'),(411,'2026-08-06','CP-313','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:20:31'),(412,'2026-08-06','CP-314','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:21:11'),(413,'2026-08-06','CP-315','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:22:41'),(414,'2026-08-07','CP-316','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:25:33'),(415,'2026-08-07','CP-317','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:26:06'),(416,'2026-08-07','CP-318','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:26:47'),(417,'2026-08-07','CP-319','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:27:15'),(418,'2026-08-07','CP-320','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:27:44'),(419,'2026-08-07','CP-321','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:28:30'),(420,'2026-08-07','CP-322','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:29:17'),(421,'2026-08-10','CP-323','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:29:46'),(422,'2026-08-10','CP-324','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:30:16'),(423,'2026-08-10','CP-325','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:30:39'),(424,'2026-08-10','CP-326','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:31:19'),(425,'2026-08-10','CP-327','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:31:46'),(426,'2026-08-10','CP-328','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:32:18'),(427,'2026-08-11','CP-329','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:32:47'),(428,'2026-08-10','CP-330','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:33:29'),(429,'2026-08-11','CP-331','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:34:03'),(430,'2026-08-11','CP-332','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:34:30'),(431,'2026-08-11','CP-333','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:35:04'),(432,'2026-08-12','CP-334','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:35:30'),(433,'2026-08-12','CP-335','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:36:11'),(434,'2026-08-12','CP-336','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:37:23'),(435,'2026-08-13','CP-337','Customer Payment Received',NULL,'2026-08-13 06:37:56'),(436,'2026-08-01','CP-338','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:41:01'),(437,'2026-08-03','CP-339','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:41:48'),(438,'2026-08-03','CP-340','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:42:18'),(439,'2026-08-03','CP-341','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:43:11'),(440,'2026-08-04','CP-342','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:43:43'),(441,'2026-08-04','CP-343','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:44:16'),(442,'2026-08-04','CP-344','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:44:53'),(443,'2026-08-04','CP-345','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:45:22'),(444,'2026-08-04','CP-346','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:45:54'),(445,'2026-08-04','CP-347','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:46:22'),(446,'2026-08-04','CP-348','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:46:55'),(447,'2026-08-05','CP-349','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:47:25'),(448,'2026-08-06','CP-350','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:48:02'),(449,'2026-08-06','CP-351','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:49:09'),(450,'2026-08-06','CP-352','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:50:10'),(451,'2026-08-06','CP-353','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:50:36'),(452,'2026-08-06','CP-354','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:51:11'),(453,'2026-08-06','CP-355','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:51:41'),(454,'2026-08-07','CP-356','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:52:52'),(455,'2026-08-07','CP-357','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:53:28'),(456,'2026-08-07','CP-358','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:54:20'),(457,'2026-08-08','CP-359','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:54:50'),(458,'2026-08-08','CP-360','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:55:26'),(459,'2026-08-10','CP-361','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:55:55'),(460,'2026-08-10','CP-362','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:57:35'),(461,'2026-08-10','CP-363','Customer Payment Received',NULL,'2026-08-13 06:59:00'),(462,'2026-08-10','CP-364','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 06:59:32'),(463,'2026-08-12','CP-365','Customer Payment Received',NULL,'2026-08-13 07:01:46'),(464,'2026-08-12','CP-366','Customer Payment Received',NULL,'2026-08-13 07:01:46'),(465,'2026-08-13','CP-367','Customer Payment Received',NULL,'2026-08-13 07:02:17'),(466,'2026-07-10','CP-368','Customer Payment Received',NULL,'2026-08-13 07:09:49'),(467,'2026-07-18','CP-369','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 07:13:44'),(468,'2026-07-18','CP-370','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 07:13:44'),(469,'2026-08-23','CP-371','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 07:14:24'),(470,'2026-08-05','CP-372','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 07:14:58'),(471,'2026-08-13','EXP-11','Expense: PICK ME & AUTO',1,'2026-08-13 12:47:06'),(472,'2026-08-13','EXP-12','Expense: BROOM STICK ',1,'2026-08-13 12:47:52'),(473,'2026-08-13','EXP-13','Expense: BROOM STICK ',1,'2026-08-13 12:47:52'),(474,'2026-08-13','VP-43','Vendor Payment',NULL,'2026-08-13 08:05:00'),(475,'2026-08-13','PUR-46','Purchase Entry',NULL,'2026-08-13 08:06:07'),(476,'2026-08-13','VP-44','Vendor Payment (Pay Cash)',NULL,'2026-08-13 08:06:07'),(477,'2026-08-13','CP-373','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 08:12:32'),(478,'2026-08-13','CP-374','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:02:02'),(479,'2026-08-13','CP-375','Customer Payment Received',NULL,'2026-08-13 09:09:07'),(480,'2026-08-13','CP-376','Customer Payment Received',NULL,'2026-08-13 09:09:07'),(481,'2026-07-01','CP-377','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:17:53'),(482,'2026-07-02','CP-378','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:19:48'),(483,'2026-07-03','CP-379','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:21:32'),(484,'2026-07-04','CP-380','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:23:09'),(485,'2026-07-06','CP-381','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:24:05'),(486,'2026-07-07','CP-382','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:25:30'),(487,'2026-07-08','CP-383','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:26:44'),(488,'2026-07-09','CP-384','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:28:31'),(489,'2026-07-10','CP-385','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:30:18'),(490,'2026-07-11','CP-386','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:31:09'),(491,'2026-07-13','CP-387','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:33:56'),(492,'2026-07-14','CP-388','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:40:23'),(493,'2026-07-15','CP-389','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:41:17'),(494,'2026-07-16','CP-390','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:42:01'),(495,'2026-07-17','CP-391','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:42:32'),(496,'2026-07-18','CP-392','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:43:21'),(497,'2026-07-20','CP-393','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:44:58'),(498,'2026-07-21','CP-394','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:45:53'),(499,'2026-07-22','CP-395','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:46:39'),(500,'2026-07-23','CP-396','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:47:21'),(501,'2026-07-24','CP-397','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:48:22'),(502,'2026-07-25','CP-398','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:49:14'),(503,'2026-07-26','CP-399','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:49:53'),(504,'2026-07-28','CP-400','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:50:30'),(505,'2026-07-30','CP-401','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:52:51'),(506,'2026-07-31','CP-402','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:53:29'),(507,'2026-08-01','CP-403','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:54:05'),(508,'2026-08-03','CP-404','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:55:00'),(509,'2026-08-04','CP-405','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:55:42'),(510,'2026-08-05','CP-406','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:56:16'),(511,'2026-08-06','CP-407','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:56:50'),(512,'2026-08-07','CP-408','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:57:36'),(513,'2026-08-08','CP-409','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:58:10'),(514,'2026-08-10','CP-410','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:58:36'),(515,'2026-08-11','CP-411','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:59:00'),(516,'2026-08-12','CP-412','Customer Payment Received (Pay Receipt)',NULL,'2026-08-13 09:59:34'),(517,'2026-08-13','CP-413','Customer Payment Received',NULL,'2026-08-13 10:36:07'),(518,'2026-08-14','CP-414','Customer Payment Received',NULL,'2026-08-14 04:45:34'),(519,'2026-08-14','CP-415','Customer Payment Received',NULL,'2026-08-14 04:45:34'),(520,'2026-08-14','CP-416','Customer Payment Received',NULL,'2026-08-14 04:45:34'),(521,'2026-08-14','CP-417','Customer Payment Received',NULL,'2026-08-14 04:45:34'),(522,'2026-08-14','CP-418','Customer Payment Received',NULL,'2026-08-14 04:45:34'),(523,'2026-08-14','CP-419','Customer Payment Received',NULL,'2026-08-14 04:45:34'),(524,'2026-08-14','PUR-47','Purchase Entry',NULL,'2026-08-14 05:05:31'),(525,'2026-08-14','VP-45','Vendor Payment (Pay Cash)',NULL,'2026-08-14 05:05:31'),(526,'2026-07-07','PUR-48','Purchase Entry',NULL,'2026-08-14 05:10:53'),(527,'2026-07-09','PUR-49','Purchase Entry',NULL,'2026-08-14 05:14:53'),(528,'2026-07-10','PUR-50','Purchase Entry',NULL,'2026-08-14 05:18:03'),(529,'2026-07-11','PUR-51','Purchase Entry',NULL,'2026-08-14 05:19:55'),(530,'2026-07-17','PUR-52','Purchase Entry',NULL,'2026-08-14 05:21:57'),(531,'2026-07-18','PUR-53','Purchase Entry',NULL,'2026-08-14 05:31:26'),(532,'2026-08-14','CP-420','Customer Payment Received',NULL,'2026-08-14 05:32:31'),(533,'2026-07-20','PUR-54','Purchase Entry',NULL,'2026-08-14 05:33:42'),(534,'2026-07-20','PUR-55','Purchase Entry',NULL,'2026-08-14 05:36:58'),(535,'2026-07-22','PUR-56','Purchase Entry',NULL,'2026-08-14 05:37:55'),(536,'2026-07-23','PUR-57','Purchase Entry',NULL,'2026-08-14 05:39:09'),(537,'2026-07-24','PUR-58','Purchase Entry',NULL,'2026-08-14 05:40:05'),(538,'2026-07-24','PUR-59','Purchase Entry',NULL,'2026-08-14 05:41:04'),(539,'2026-07-24','PUR-60','Purchase Entry',NULL,'2026-08-14 05:42:15'),(540,'2026-07-30','PUR-61','Purchase Entry',NULL,'2026-08-14 05:44:37'),(541,'2026-07-30','PUR-62','Purchase Entry',NULL,'2026-08-14 05:45:50'),(542,'2026-08-01','PUR-63','Purchase Entry',NULL,'2026-08-14 05:46:58'),(543,'2026-08-06','PUR-64','Purchase Entry',NULL,'2026-08-14 05:47:51'),(544,'2026-08-06','PUR-65','Purchase Entry',NULL,'2026-08-14 05:49:01'),(545,'2026-08-06','PUR-66','Purchase Entry',NULL,'2026-08-14 05:49:01'),(546,'2026-08-13','PUR-67','Purchase Entry',NULL,'2026-08-14 05:54:05'),(547,'2026-08-13','VP-46','Vendor Payment (Pay Cash)',NULL,'2026-08-14 05:54:05'),(548,'2026-08-14','CP-421','Customer Payment Received',NULL,'2026-08-14 05:58:47'),(549,'2026-08-13','CP-422','Customer Payment Received (Pay Receipt)',NULL,'2026-08-14 06:06:20'),(550,'2026-08-13','CP-423','Customer Payment Received (Pay Receipt)',NULL,'2026-08-14 06:09:06'),(551,'2026-08-14','CP-424','Customer Payment Received (Pay Receipt)',NULL,'2026-08-14 06:10:36'),(552,'2026-08-14','VP-47','Vendor Payment',NULL,'2026-08-14 06:26:19'),(553,'2026-08-13','PUR-68','Purchase Entry',NULL,'2026-08-14 06:46:24'),(554,'2026-08-13','VP-48','Vendor Payment (Pay Cash)',NULL,'2026-08-14 06:46:24'),(555,'2026-08-14','PUR-69','Purchase Entry',NULL,'2026-08-14 06:48:49'),(556,'2026-08-14','VP-49','Vendor Payment (Pay Cash)',NULL,'2026-08-14 06:48:49'),(557,'2026-07-01','PUR-70','Purchase Entry',NULL,'2026-08-14 06:51:46'),(558,'2026-07-01','PUR-71','Purchase Entry',NULL,'2026-08-14 06:53:32'),(559,'2026-07-02','PUR-72','Purchase Entry',NULL,'2026-08-14 06:54:44'),(560,'2026-07-02','PUR-73','Purchase Entry',NULL,'2026-08-14 06:55:11'),(561,'2026-07-03','PUR-74','Purchase Entry',NULL,'2026-08-14 06:57:49'),(562,'2026-07-07','PUR-75','Purchase Entry',NULL,'2026-08-14 07:04:05'),(563,'2026-07-07','PUR-76','Purchase Entry',NULL,'2026-08-14 07:05:08'),(564,'2026-07-06','PUR-77','Purchase Entry',NULL,'2026-08-14 07:05:58'),(565,'2026-07-05','PUR-78','Purchase Entry',NULL,'2026-08-14 07:07:59'),(566,'2026-07-07','PUR-79','Purchase Entry',NULL,'2026-08-14 07:08:39'),(567,'2026-07-11','PUR-80','Purchase Entry',NULL,'2026-08-14 07:10:31'),(568,'2026-07-13','PUR-81','Purchase Entry',NULL,'2026-08-14 07:16:07'),(569,'2026-08-11','PUR-82','Purchase Entry',NULL,'2026-08-14 07:18:08'),(570,'2026-07-20','PUR-83','Purchase Entry',NULL,'2026-08-14 07:29:57'),(571,'2026-07-20','PUR-84','Purchase Entry',NULL,'2026-08-14 07:30:47'),(572,'2026-07-23','PUR-85','Purchase Entry',NULL,'2026-08-14 07:45:16'),(573,'2026-07-09','PUR-86','Purchase Entry',NULL,'2026-08-14 07:46:25'),(574,'2026-07-14','PUR-87','Purchase Entry',NULL,'2026-08-14 07:47:30'),(575,'2026-07-23','PUR-88','Purchase Entry',NULL,'2026-08-14 07:48:24'),(576,'2026-07-28','PUR-89','Purchase Entry',NULL,'2026-08-14 07:49:03'),(577,'2026-08-07','PUR-90','Purchase Entry',NULL,'2026-08-14 07:49:36'),(578,'2026-08-13','PUR-91','Purchase Entry',NULL,'2026-08-14 07:50:35'),(579,'2026-08-13','PUR-92','Purchase Entry',NULL,'2026-08-14 07:51:24'),(580,'2026-08-14','VP-50','Vendor Payment',NULL,'2026-08-14 07:59:18'),(581,'2026-08-14','VP-51','Vendor Payment',NULL,'2026-08-14 07:59:18'),(582,'2026-08-14','VP-52','Vendor Payment',NULL,'2026-08-14 07:59:18'),(583,'2026-08-14','VP-53','Vendor Payment',NULL,'2026-08-14 07:59:18'),(584,'2026-08-14','VP-54','Vendor Payment',NULL,'2026-08-14 07:59:18'),(585,'2026-08-14','VP-55','Vendor Payment',NULL,'2026-08-14 07:59:18'),(586,'2026-08-14','VP-56','Vendor Payment',NULL,'2026-08-14 07:59:18'),(587,'2026-08-14','VP-57','Vendor Payment',NULL,'2026-08-14 07:59:18'),(588,'2026-08-14','VP-58','Vendor Payment',NULL,'2026-08-14 07:59:18'),(589,'2026-08-14','VP-59','Vendor Payment',NULL,'2026-08-14 07:59:18'),(590,'2026-08-14','VP-60','Vendor Payment',NULL,'2026-08-14 07:59:18'),(591,'2026-08-14','VP-61','Vendor Payment',NULL,'2026-08-14 07:59:18'),(592,'2026-08-14','VP-62','Vendor Payment',NULL,'2026-08-14 07:59:18'),(593,'2026-08-14','VP-63','Vendor Payment',NULL,'2026-08-14 07:59:18'),(594,'2026-08-14','VP-64','Vendor Payment',NULL,'2026-08-14 07:59:18'),(595,'2026-08-14','VP-65','Vendor Payment',NULL,'2026-08-14 07:59:18'),(596,'2026-08-14','VP-66','Vendor Payment',NULL,'2026-08-14 07:59:18'),(597,'2026-08-14','PUR-93','Purchase Entry',NULL,'2026-08-14 08:01:27'),(598,'2026-08-14','VP-67','Vendor Payment (Pay Cash)',NULL,'2026-08-14 08:01:27'),(599,'2026-08-13','CP-425','Customer Payment Received (Pay Receipt)',NULL,'2026-08-14 08:17:59'),(600,'2026-08-14','CP-426','Customer Payment Received',NULL,'2026-08-14 08:19:49'),(601,'2026-08-14','CP-427','Customer Payment Received (Pay Receipt)',NULL,'2026-08-14 08:27:01'),(602,'2026-08-14','CP-428','Customer Payment Received (Pay Receipt)',NULL,'2026-08-14 08:31:15');
/*!40000 ALTER TABLE `journal_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entry_details`
--

DROP TABLE IF EXISTS `journal_entry_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_entry_details` (
  `JournalDetailID` int NOT NULL AUTO_INCREMENT,
  `JournalID` int NOT NULL,
  `AccountID` int NOT NULL,
  `DebitAmount` decimal(18,2) DEFAULT '0.00',
  `CreditAmount` decimal(18,2) DEFAULT '0.00',
  `Description` text,
  PRIMARY KEY (`JournalDetailID`),
  KEY `JournalID` (`JournalID`),
  KEY `AccountID` (`AccountID`),
  CONSTRAINT `fk_jed_account` FOREIGN KEY (`AccountID`) REFERENCES `chart_of_accounts` (`AccountID`),
  CONSTRAINT `fk_jed_journal` FOREIGN KEY (`JournalID`) REFERENCES `journal_entries` (`JournalID`),
  CONSTRAINT `journal_entry_details_ibfk_1` FOREIGN KEY (`JournalID`) REFERENCES `journal_entries` (`JournalID`),
  CONSTRAINT `journal_entry_details_ibfk_2` FOREIGN KEY (`AccountID`) REFERENCES `chart_of_accounts` (`AccountID`)
) ENGINE=InnoDB AUTO_INCREMENT=1213 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entry_details`
--

LOCK TABLES `journal_entry_details` WRITE;
/*!40000 ALTER TABLE `journal_entry_details` DISABLE KEYS */;
INSERT INTO `journal_entry_details` VALUES (13,3,2,9175.00,0.00,NULL),(14,3,3,0.00,9175.00,NULL),(15,4,2,2960.00,0.00,NULL),(16,4,3,0.00,2960.00,NULL),(17,5,2,1480.00,0.00,NULL),(18,5,3,0.00,1480.00,NULL),(19,6,1,18560.00,0.00,NULL),(20,6,3,0.00,18560.00,NULL),(21,7,1,11345.00,0.00,NULL),(22,7,3,0.00,11345.00,NULL),(23,8,1,5755.00,0.00,NULL),(24,8,3,0.00,5755.00,NULL),(25,9,1,1350.00,0.00,NULL),(26,9,3,0.00,1350.00,NULL),(27,10,1,8730.00,0.00,NULL),(28,10,3,0.00,8730.00,NULL),(29,11,1,7220.00,0.00,NULL),(30,11,3,0.00,7220.00,NULL),(31,12,1,9770.00,0.00,NULL),(32,12,3,0.00,9770.00,NULL),(33,13,1,9420.00,0.00,NULL),(34,13,3,0.00,9420.00,NULL),(35,14,1,8680.00,0.00,NULL),(36,14,3,0.00,8680.00,NULL),(37,15,1,25230.00,0.00,NULL),(38,15,3,0.00,25230.00,NULL),(39,16,1,2650.00,0.00,NULL),(40,16,3,0.00,2650.00,NULL),(41,17,1,12950.00,0.00,NULL),(42,17,3,0.00,12950.00,NULL),(43,18,1,10810.00,0.00,NULL),(44,18,3,0.00,10810.00,NULL),(45,19,1,4890.00,0.00,NULL),(46,19,3,0.00,4890.00,NULL),(47,20,1,11730.00,0.00,NULL),(48,20,3,0.00,11730.00,NULL),(49,21,1,10000.00,0.00,NULL),(50,21,3,0.00,10000.00,NULL),(51,22,1,11300.00,0.00,NULL),(52,22,3,0.00,11300.00,NULL),(53,23,1,2250.00,0.00,NULL),(54,23,3,0.00,2250.00,NULL),(55,24,1,2150.00,0.00,NULL),(56,24,3,0.00,2150.00,NULL),(57,25,1,1900.00,0.00,NULL),(58,25,3,0.00,1900.00,NULL),(59,26,1,5000.00,0.00,NULL),(60,26,3,0.00,5000.00,NULL),(61,27,1,1000.00,0.00,NULL),(62,27,3,0.00,1000.00,NULL),(63,28,1,780.00,0.00,NULL),(64,28,3,0.00,780.00,NULL),(65,29,1,980.00,0.00,NULL),(66,29,3,0.00,980.00,NULL),(67,30,1,1000.00,0.00,NULL),(68,30,3,0.00,1000.00,NULL),(69,31,1,840.00,0.00,NULL),(70,31,3,0.00,840.00,NULL),(71,32,1,300.00,0.00,NULL),(72,32,3,0.00,300.00,NULL),(73,33,1,4800.00,0.00,NULL),(74,33,3,0.00,4800.00,NULL),(75,34,1,2500.00,0.00,NULL),(76,34,3,0.00,2500.00,NULL),(77,35,1,500.00,0.00,NULL),(78,35,3,0.00,500.00,NULL),(79,36,1,500.00,0.00,NULL),(80,36,3,0.00,500.00,NULL),(81,37,1,8910.00,0.00,NULL),(82,37,3,0.00,8910.00,NULL),(83,38,1,4000.00,0.00,NULL),(84,38,3,0.00,4000.00,NULL),(85,39,1,10500.00,0.00,NULL),(86,39,3,0.00,10500.00,NULL),(87,40,1,5800.00,0.00,NULL),(88,40,3,0.00,5800.00,NULL),(89,41,1,1000.00,0.00,NULL),(90,41,3,0.00,1000.00,NULL),(91,42,1,320.00,0.00,NULL),(92,42,3,0.00,320.00,NULL),(93,43,1,3000.00,0.00,NULL),(94,43,3,0.00,3000.00,NULL),(95,44,1,600.00,0.00,NULL),(96,44,3,0.00,600.00,NULL),(97,45,1,2535.00,0.00,NULL),(98,45,3,0.00,2535.00,NULL),(99,46,1,900.00,0.00,NULL),(100,46,3,0.00,900.00,NULL),(101,47,1,200.00,0.00,NULL),(102,47,3,0.00,200.00,NULL),(103,48,1,750.00,0.00,NULL),(104,48,3,0.00,750.00,NULL),(105,49,1,7500.00,0.00,NULL),(106,49,3,0.00,7500.00,NULL),(107,50,1,7500.00,0.00,NULL),(108,50,3,0.00,7500.00,NULL),(109,51,1,750.00,0.00,NULL),(110,51,3,0.00,750.00,NULL),(111,52,1,3600.00,0.00,NULL),(112,52,3,0.00,3600.00,NULL),(113,53,1,800.00,0.00,NULL),(114,53,3,0.00,800.00,NULL),(115,54,1,2000.00,0.00,NULL),(116,54,3,0.00,2000.00,NULL),(117,55,1,1200.00,0.00,NULL),(118,55,3,0.00,1200.00,NULL),(119,56,1,1300.00,0.00,NULL),(120,56,3,0.00,1300.00,NULL),(121,57,1,3500.00,0.00,NULL),(122,57,3,0.00,3500.00,NULL),(123,58,1,400.00,0.00,NULL),(124,58,3,0.00,400.00,NULL),(125,59,1,5880.00,0.00,NULL),(126,59,3,0.00,5880.00,NULL),(127,60,1,600.00,0.00,NULL),(128,60,3,0.00,600.00,NULL),(129,61,1,800.00,0.00,NULL),(130,61,3,0.00,800.00,NULL),(131,62,1,1840.00,0.00,NULL),(132,62,3,0.00,1840.00,NULL),(133,63,1,4200.00,0.00,NULL),(134,63,3,0.00,4200.00,NULL),(135,64,1,3500.00,0.00,NULL),(136,64,3,0.00,3500.00,NULL),(137,65,1,4020.00,0.00,NULL),(138,65,3,0.00,4020.00,NULL),(139,66,1,1500.00,0.00,NULL),(140,66,3,0.00,1500.00,NULL),(141,67,1,1170.00,0.00,NULL),(142,67,3,0.00,1170.00,NULL),(143,68,1,2170.00,0.00,NULL),(144,68,3,0.00,2170.00,NULL),(145,69,1,5050.00,0.00,NULL),(146,69,3,0.00,5050.00,NULL),(147,70,1,6600.00,0.00,NULL),(148,70,3,0.00,6600.00,NULL),(149,71,1,1960.00,0.00,NULL),(150,71,3,0.00,1960.00,NULL),(151,72,2,5000.00,0.00,NULL),(152,72,3,0.00,5000.00,NULL),(153,73,1,500.00,0.00,NULL),(154,73,3,0.00,500.00,NULL),(155,74,1,1320.00,0.00,NULL),(156,74,3,0.00,1320.00,NULL),(157,75,1,900.00,0.00,NULL),(158,75,3,0.00,900.00,NULL),(159,76,1,19800.00,0.00,NULL),(160,76,3,0.00,19800.00,NULL),(161,77,1,17760.00,0.00,NULL),(162,77,3,0.00,17760.00,NULL),(163,78,1,2520.00,0.00,NULL),(164,78,3,0.00,2520.00,NULL),(165,79,1,760.00,0.00,NULL),(166,79,3,0.00,760.00,NULL),(167,80,1,7375.00,0.00,NULL),(168,80,3,0.00,7375.00,NULL),(169,81,1,4000.00,0.00,NULL),(170,81,3,0.00,4000.00,NULL),(171,82,1,4000.00,0.00,NULL),(172,82,3,0.00,4000.00,NULL),(173,83,1,4000.00,0.00,NULL),(174,83,3,0.00,4000.00,NULL),(175,84,1,300.00,0.00,NULL),(176,84,3,0.00,300.00,NULL),(177,85,1,1040.00,0.00,NULL),(178,85,3,0.00,1040.00,NULL),(179,86,1,1040.00,0.00,NULL),(180,86,3,0.00,1040.00,NULL),(181,87,1,3000.00,0.00,NULL),(182,87,3,0.00,3000.00,NULL),(183,88,1,300.00,0.00,NULL),(184,88,3,0.00,300.00,NULL),(185,89,1,320.00,0.00,NULL),(186,89,3,0.00,320.00,NULL),(187,90,1,120.00,0.00,NULL),(188,90,3,0.00,120.00,NULL),(189,91,1,1500.00,0.00,NULL),(190,91,3,0.00,1500.00,NULL),(191,92,1,1200.00,0.00,NULL),(192,92,3,0.00,1200.00,NULL),(193,93,1,500.00,0.00,NULL),(194,93,3,0.00,500.00,NULL),(195,94,1,300.00,0.00,NULL),(196,94,3,0.00,300.00,NULL),(197,95,1,400.00,0.00,NULL),(198,95,3,0.00,400.00,NULL),(199,96,1,100.00,0.00,NULL),(200,96,3,0.00,100.00,NULL),(201,97,1,480.00,0.00,NULL),(202,97,3,0.00,480.00,NULL),(203,98,1,3360.00,0.00,NULL),(204,98,3,0.00,3360.00,NULL),(205,99,1,3360.00,0.00,NULL),(206,99,3,0.00,3360.00,NULL),(207,100,1,1400.00,0.00,NULL),(208,100,3,0.00,1400.00,NULL),(209,101,1,6400.00,0.00,NULL),(210,101,3,0.00,6400.00,NULL),(211,102,1,760.00,0.00,NULL),(212,102,3,0.00,760.00,NULL),(213,103,1,3000.00,0.00,NULL),(214,103,3,0.00,3000.00,NULL),(215,104,1,24000.00,0.00,NULL),(216,104,3,0.00,24000.00,NULL),(217,105,1,24000.00,0.00,NULL),(218,105,3,0.00,24000.00,NULL),(219,106,1,12000.00,0.00,NULL),(220,106,3,0.00,12000.00,NULL),(221,107,1,2100.00,0.00,NULL),(222,107,3,0.00,2100.00,NULL),(223,108,1,2920.00,0.00,NULL),(224,108,3,0.00,2920.00,NULL),(225,109,1,150.00,0.00,NULL),(226,109,3,0.00,150.00,NULL),(227,110,1,700.00,0.00,NULL),(228,110,3,0.00,700.00,NULL),(229,111,1,1200.00,0.00,NULL),(230,111,3,0.00,1200.00,NULL),(231,112,1,3280.00,0.00,NULL),(232,112,3,0.00,3280.00,NULL),(233,113,1,7200.00,0.00,NULL),(234,113,3,0.00,7200.00,NULL),(235,114,1,32000.00,0.00,NULL),(236,114,3,0.00,32000.00,NULL),(237,115,1,28000.00,0.00,NULL),(238,115,3,0.00,28000.00,NULL),(239,116,1,1100.00,0.00,NULL),(240,116,3,0.00,1100.00,NULL),(241,117,1,3870.00,0.00,NULL),(242,117,3,0.00,3870.00,NULL),(243,118,1,9810.00,0.00,NULL),(244,118,3,0.00,9810.00,NULL),(245,119,1,1400.00,0.00,NULL),(246,119,3,0.00,1400.00,NULL),(247,120,1,400.00,0.00,NULL),(248,120,3,0.00,400.00,NULL),(249,121,1,2560.00,0.00,NULL),(250,121,3,0.00,2560.00,NULL),(251,122,1,750.00,0.00,NULL),(252,122,3,0.00,750.00,NULL),(253,123,1,11970.00,0.00,NULL),(254,123,3,0.00,11970.00,NULL),(255,124,1,150.00,0.00,NULL),(256,124,3,0.00,150.00,NULL),(257,125,1,1900.00,0.00,NULL),(258,125,3,0.00,1900.00,NULL),(259,126,1,4020.00,0.00,NULL),(260,126,3,0.00,4020.00,NULL),(261,127,1,3240.00,0.00,NULL),(262,127,3,0.00,3240.00,NULL),(263,128,1,8400.00,0.00,NULL),(264,128,3,0.00,8400.00,NULL),(265,129,1,10020.00,0.00,NULL),(266,129,3,0.00,10020.00,NULL),(267,130,1,2300.00,0.00,NULL),(268,130,3,0.00,2300.00,NULL),(269,131,1,5000.00,0.00,NULL),(270,131,3,0.00,5000.00,NULL),(271,132,1,3600.00,0.00,NULL),(272,132,3,0.00,3600.00,NULL),(273,133,1,4500.00,0.00,NULL),(274,133,3,0.00,4500.00,NULL),(275,134,1,8750.00,0.00,NULL),(276,134,3,0.00,8750.00,NULL),(277,135,1,17500.00,0.00,NULL),(278,135,3,0.00,17500.00,NULL),(279,136,1,2160.00,0.00,NULL),(280,136,3,0.00,2160.00,NULL),(281,137,1,320.00,0.00,NULL),(282,137,3,0.00,320.00,NULL),(283,138,1,800.00,0.00,NULL),(284,138,3,0.00,800.00,NULL),(285,139,1,1500.00,0.00,NULL),(286,139,3,0.00,1500.00,NULL),(287,140,1,2000.00,0.00,NULL),(288,140,3,0.00,2000.00,NULL),(289,141,1,1320.00,0.00,NULL),(290,141,3,0.00,1320.00,NULL),(291,142,1,28160.00,0.00,NULL),(292,142,3,0.00,28160.00,NULL),(293,143,1,1040.00,0.00,NULL),(294,143,3,0.00,1040.00,NULL),(295,144,1,1500.00,0.00,NULL),(296,144,3,0.00,1500.00,NULL),(297,145,1,500.00,0.00,NULL),(298,145,3,0.00,500.00,NULL),(299,146,1,2200.00,0.00,NULL),(300,146,3,0.00,2200.00,NULL),(301,147,1,2380.00,0.00,NULL),(302,147,3,0.00,2380.00,NULL),(303,148,1,2380.00,0.00,NULL),(304,148,3,0.00,2380.00,NULL),(305,149,1,4200.00,0.00,NULL),(306,149,3,0.00,4200.00,NULL),(307,150,1,2400.00,0.00,NULL),(308,150,3,0.00,2400.00,NULL),(309,151,1,3000.00,0.00,NULL),(310,151,3,0.00,3000.00,NULL),(311,152,1,36885.00,0.00,NULL),(312,152,3,0.00,36885.00,NULL),(313,153,1,4435.00,0.00,NULL),(314,153,3,0.00,4435.00,NULL),(315,154,1,300.00,0.00,NULL),(316,154,3,0.00,300.00,NULL),(317,155,1,3000.00,0.00,NULL),(318,155,3,0.00,3000.00,NULL),(319,156,1,2700.00,0.00,NULL),(320,156,3,0.00,2700.00,NULL),(321,157,1,3800.00,0.00,NULL),(322,157,3,0.00,3800.00,NULL),(323,158,1,2000.00,0.00,NULL),(324,158,3,0.00,2000.00,NULL),(325,159,1,3500.00,0.00,NULL),(326,159,3,0.00,3500.00,NULL),(327,160,1,3050.00,0.00,NULL),(328,160,3,0.00,3050.00,NULL),(329,161,1,900.00,0.00,NULL),(330,161,3,0.00,900.00,NULL),(331,162,1,12480.00,0.00,NULL),(332,162,3,0.00,12480.00,NULL),(333,163,1,4800.00,0.00,NULL),(334,163,3,0.00,4800.00,NULL),(335,164,1,2500.00,0.00,NULL),(336,164,3,0.00,2500.00,NULL),(337,165,1,1800.00,0.00,NULL),(338,165,3,0.00,1800.00,NULL),(339,166,1,3000.00,0.00,NULL),(340,166,3,0.00,3000.00,NULL),(341,167,1,1500.00,0.00,NULL),(342,167,3,0.00,1500.00,NULL),(343,168,1,4600.00,0.00,NULL),(344,168,3,0.00,4600.00,NULL),(345,169,1,14500.00,0.00,NULL),(346,169,3,0.00,14500.00,NULL),(347,170,1,14500.00,0.00,NULL),(348,170,3,0.00,14500.00,NULL),(349,171,1,13000.00,0.00,NULL),(350,171,3,0.00,13000.00,NULL),(351,172,1,2000.00,0.00,NULL),(352,172,3,0.00,2000.00,NULL),(353,173,1,1800.00,0.00,NULL),(354,173,3,0.00,1800.00,NULL),(355,174,1,11000.00,0.00,NULL),(356,174,3,0.00,11000.00,NULL),(357,175,1,14040.00,0.00,NULL),(358,175,3,0.00,14040.00,NULL),(359,176,1,14040.00,0.00,NULL),(360,176,3,0.00,14040.00,NULL),(361,177,1,14040.00,0.00,NULL),(362,177,3,0.00,14040.00,NULL),(363,178,1,800.00,0.00,NULL),(364,178,3,0.00,800.00,NULL),(365,179,1,6000.00,0.00,NULL),(366,179,3,0.00,6000.00,NULL),(367,180,1,10200.00,0.00,NULL),(368,180,3,0.00,10200.00,NULL),(369,181,1,2000.00,0.00,NULL),(370,181,3,0.00,2000.00,NULL),(371,182,1,4500.00,0.00,NULL),(372,182,3,0.00,4500.00,NULL),(373,183,1,700.00,0.00,NULL),(374,183,3,0.00,700.00,NULL),(375,184,1,700.00,0.00,NULL),(376,184,3,0.00,700.00,NULL),(377,185,1,1100.00,0.00,NULL),(378,185,3,0.00,1100.00,NULL),(379,186,1,12000.00,0.00,NULL),(380,186,3,0.00,12000.00,NULL),(381,187,1,900.00,0.00,NULL),(382,187,3,0.00,900.00,NULL),(383,188,1,6000.00,0.00,NULL),(384,188,3,0.00,6000.00,NULL),(385,189,1,9800.00,0.00,NULL),(386,189,3,0.00,9800.00,NULL),(387,190,1,2720.00,0.00,NULL),(388,190,3,0.00,2720.00,NULL),(389,191,1,6750.00,0.00,NULL),(390,191,3,0.00,6750.00,NULL),(391,192,1,4000.00,0.00,NULL),(392,192,3,0.00,4000.00,NULL),(393,193,1,15000.00,0.00,NULL),(394,193,3,0.00,15000.00,NULL),(395,194,1,10500.00,0.00,NULL),(396,194,3,0.00,10500.00,NULL),(397,195,1,800.00,0.00,NULL),(398,195,3,0.00,800.00,NULL),(399,196,1,3600.00,0.00,NULL),(400,196,3,0.00,3600.00,NULL),(401,197,1,6000.00,0.00,NULL),(402,197,3,0.00,6000.00,NULL),(403,198,1,29750.00,0.00,NULL),(404,198,3,0.00,29750.00,NULL),(405,199,1,6000.00,0.00,NULL),(406,199,3,0.00,6000.00,NULL),(407,200,1,7000.00,0.00,NULL),(408,200,3,0.00,7000.00,NULL),(409,201,1,3060.00,0.00,NULL),(410,201,3,0.00,3060.00,NULL),(411,202,1,1800.00,0.00,NULL),(412,202,3,0.00,1800.00,NULL),(413,203,1,12000.00,0.00,NULL),(414,203,3,0.00,12000.00,NULL),(415,204,1,6000.00,0.00,NULL),(416,204,3,0.00,6000.00,NULL),(417,205,1,6400.00,0.00,NULL),(418,205,3,0.00,6400.00,NULL),(419,206,1,45000.00,0.00,NULL),(420,206,3,0.00,45000.00,NULL),(421,207,1,4800.00,0.00,NULL),(422,207,3,0.00,4800.00,NULL),(423,208,1,5250.00,0.00,NULL),(424,208,3,0.00,5250.00,NULL),(425,209,1,1500.00,0.00,NULL),(426,209,3,0.00,1500.00,NULL),(427,210,1,3500.00,0.00,NULL),(428,210,3,0.00,3500.00,NULL),(429,211,1,2000.00,0.00,NULL),(430,211,3,0.00,2000.00,NULL),(431,212,1,1500.00,0.00,NULL),(432,212,3,0.00,1500.00,NULL),(433,213,1,2000.00,0.00,NULL),(434,213,3,0.00,2000.00,NULL),(435,214,1,6000.00,0.00,NULL),(436,214,3,0.00,6000.00,NULL),(437,215,1,7500.00,0.00,NULL),(438,215,3,0.00,7500.00,NULL),(439,216,1,16320.00,0.00,NULL),(440,216,3,0.00,16320.00,NULL),(441,217,1,3600.00,0.00,NULL),(442,217,3,0.00,3600.00,NULL),(443,218,2,5000.00,0.00,NULL),(444,218,3,0.00,5000.00,NULL),(445,219,1,100.00,0.00,NULL),(446,219,3,0.00,100.00,NULL),(447,220,1,433228.00,0.00,NULL),(448,220,3,0.00,433228.00,NULL),(449,221,1,10000.00,0.00,NULL),(450,221,3,0.00,10000.00,NULL),(451,222,1,20000.00,0.00,NULL),(452,222,3,0.00,20000.00,NULL),(453,223,1,10000.00,0.00,NULL),(454,223,3,0.00,10000.00,NULL),(455,224,1,49000.00,0.00,NULL),(456,224,3,0.00,49000.00,NULL),(457,225,1,16500.00,0.00,NULL),(458,225,3,0.00,16500.00,NULL),(459,226,1,104000.00,0.00,NULL),(460,226,3,0.00,104000.00,NULL),(461,227,1,96000.00,0.00,NULL),(462,227,3,0.00,96000.00,NULL),(463,228,1,60000.00,0.00,NULL),(464,228,3,0.00,60000.00,NULL),(465,229,1,8200.00,0.00,NULL),(466,229,3,0.00,8200.00,NULL),(467,230,1,70000.00,0.00,NULL),(468,230,3,0.00,70000.00,NULL),(469,231,1,74400.00,0.00,NULL),(470,231,3,0.00,74400.00,NULL),(471,232,1,63500.00,0.00,NULL),(472,232,3,0.00,63500.00,NULL),(473,233,1,18000.00,0.00,NULL),(474,233,3,0.00,18000.00,NULL),(475,234,1,190000.00,0.00,NULL),(476,234,3,0.00,190000.00,NULL),(477,235,1,5400.00,0.00,NULL),(478,235,3,0.00,5400.00,NULL),(479,236,1,55.00,0.00,NULL),(480,236,3,0.00,55.00,NULL),(481,237,1,1590.00,0.00,NULL),(482,237,3,0.00,1590.00,NULL),(483,238,1,8355.00,0.00,NULL),(484,238,3,0.00,8355.00,NULL),(485,239,1,2100.00,0.00,NULL),(486,239,3,0.00,2100.00,NULL),(487,240,1,24800.00,0.00,NULL),(488,240,3,0.00,24800.00,NULL),(489,241,1,3999.99,0.00,NULL),(490,241,3,0.00,3999.99,NULL),(491,242,1,49000.00,0.00,NULL),(492,242,3,0.00,49000.00,NULL),(493,243,1,12180.00,0.00,NULL),(494,243,3,0.00,12180.00,NULL),(495,244,1,10860.00,0.00,NULL),(496,244,3,0.00,10860.00,NULL),(497,245,1,6000.00,0.00,NULL),(498,245,3,0.00,6000.00,NULL),(499,246,1,960.00,0.00,NULL),(500,246,3,0.00,960.00,NULL),(501,247,1,14000.00,0.00,NULL),(502,247,3,0.00,14000.00,NULL),(503,248,1,1840.00,0.00,NULL),(504,248,3,0.00,1840.00,NULL),(505,249,1,12120.00,0.00,NULL),(506,249,3,0.00,12120.00,NULL),(507,250,1,780.00,0.00,NULL),(508,250,3,0.00,780.00,NULL),(509,251,1,2100.00,0.00,NULL),(510,251,3,0.00,2100.00,NULL),(511,252,2,350000.00,0.00,NULL),(512,252,3,0.00,350000.00,NULL),(513,253,2,8390.00,0.00,NULL),(514,253,3,0.00,8390.00,NULL),(515,254,2,13230.00,0.00,NULL),(516,254,3,0.00,13230.00,NULL),(517,255,2,23310.00,0.00,NULL),(518,255,3,0.00,23310.00,NULL),(519,256,2,3180.00,0.00,NULL),(520,256,3,0.00,3180.00,NULL),(521,257,2,3300.00,0.00,NULL),(522,257,3,0.00,3300.00,NULL),(523,258,2,300.00,0.00,NULL),(524,258,3,0.00,300.00,NULL),(525,259,2,11130.00,0.00,NULL),(526,259,3,0.00,11130.00,NULL),(527,260,4,1500.00,0.00,NULL),(528,260,5,0.00,1500.00,NULL),(529,261,5,1500.00,0.00,NULL),(530,261,1,0.00,1500.00,NULL),(531,262,4,4500.00,0.00,NULL),(532,262,5,0.00,4500.00,NULL),(533,263,5,4500.00,0.00,NULL),(534,263,1,0.00,4500.00,NULL),(535,264,4,3000.00,0.00,NULL),(536,264,5,0.00,3000.00,NULL),(537,265,5,3000.00,0.00,NULL),(538,265,1,0.00,3000.00,NULL),(539,266,4,5250.00,0.00,NULL),(540,266,5,0.00,5250.00,NULL),(541,267,5,5250.00,0.00,NULL),(542,267,1,0.00,5250.00,NULL),(543,268,4,2000.00,0.00,NULL),(544,268,5,0.00,2000.00,NULL),(545,269,5,2000.00,0.00,NULL),(546,269,1,0.00,2000.00,NULL),(547,270,4,2150.00,0.00,NULL),(548,270,5,0.00,2150.00,NULL),(549,271,5,2150.00,0.00,NULL),(550,271,1,0.00,2150.00,NULL),(551,272,4,775.00,0.00,NULL),(552,272,5,0.00,775.00,NULL),(553,273,5,775.00,0.00,NULL),(554,273,1,0.00,775.00,NULL),(555,274,4,48000.00,0.00,NULL),(556,274,5,0.00,48000.00,NULL),(557,275,5,48000.00,0.00,NULL),(558,275,1,0.00,48000.00,NULL),(559,276,4,50500.00,0.00,NULL),(560,276,5,0.00,50500.00,NULL),(561,277,5,50500.00,0.00,NULL),(562,277,1,0.00,50500.00,NULL),(563,278,4,3260.00,0.00,NULL),(564,278,5,0.00,3260.00,NULL),(565,279,5,3260.00,0.00,NULL),(566,279,1,0.00,3260.00,NULL),(567,280,4,60430.00,0.00,NULL),(568,280,5,0.00,60430.00,NULL),(569,281,5,60430.00,0.00,NULL),(570,281,1,0.00,60430.00,NULL),(571,282,4,20500.00,0.00,NULL),(572,282,5,0.00,20500.00,NULL),(573,283,5,20500.00,0.00,NULL),(574,283,1,0.00,20500.00,NULL),(575,284,4,11200.00,0.00,NULL),(576,284,5,0.00,11200.00,NULL),(577,285,5,11200.00,0.00,NULL),(578,285,1,0.00,11200.00,NULL),(579,286,4,2000.00,0.00,NULL),(580,286,5,0.00,2000.00,NULL),(581,287,5,2000.00,0.00,NULL),(582,287,1,0.00,2000.00,NULL),(583,288,4,850.00,0.00,NULL),(584,288,5,0.00,850.00,NULL),(585,289,5,850.00,0.00,NULL),(586,289,1,0.00,850.00,NULL),(587,290,4,705.00,0.00,NULL),(588,290,5,0.00,705.00,NULL),(589,291,5,705.00,0.00,NULL),(590,291,1,0.00,705.00,NULL),(591,292,4,1770.00,0.00,NULL),(592,292,5,0.00,1770.00,NULL),(593,293,5,1770.00,0.00,NULL),(594,293,1,0.00,1770.00,NULL),(595,294,4,265.00,0.00,NULL),(596,294,5,0.00,265.00,NULL),(597,295,5,265.00,0.00,NULL),(598,295,1,0.00,265.00,NULL),(599,296,4,3995.00,0.00,NULL),(600,296,5,0.00,3995.00,NULL),(601,297,5,3995.00,0.00,NULL),(602,297,1,0.00,3995.00,NULL),(603,298,4,3000.00,0.00,NULL),(604,298,5,0.00,3000.00,NULL),(605,299,5,3000.00,0.00,NULL),(606,299,1,0.00,3000.00,NULL),(607,300,4,20755.00,0.00,NULL),(608,300,5,0.00,20755.00,NULL),(609,301,5,20755.00,0.00,NULL),(610,301,1,0.00,20755.00,NULL),(611,302,4,1475.00,0.00,NULL),(612,302,5,0.00,1475.00,NULL),(613,303,5,1475.00,0.00,NULL),(614,303,1,0.00,1475.00,NULL),(615,304,4,765.00,0.00,NULL),(616,304,5,0.00,765.00,NULL),(617,305,5,765.00,0.00,NULL),(618,305,1,0.00,765.00,NULL),(619,306,4,44610.00,0.00,NULL),(620,306,5,0.00,44610.00,NULL),(621,307,5,44610.00,0.00,NULL),(622,307,1,0.00,44610.00,NULL),(623,308,4,3675.00,0.00,NULL),(624,308,5,0.00,3675.00,NULL),(625,309,5,3675.00,0.00,NULL),(626,309,1,0.00,3675.00,NULL),(627,310,4,10735.00,0.00,NULL),(628,310,5,0.00,10735.00,NULL),(629,311,5,10735.00,0.00,NULL),(630,311,1,0.00,10735.00,NULL),(631,312,4,470.00,0.00,NULL),(632,312,5,0.00,470.00,NULL),(633,313,5,470.00,0.00,NULL),(634,313,1,0.00,470.00,NULL),(635,314,4,12450.00,0.00,NULL),(636,314,5,0.00,12450.00,NULL),(637,315,5,12450.00,0.00,NULL),(638,315,1,0.00,12450.00,NULL),(639,316,4,4700.00,0.00,NULL),(640,316,5,0.00,4700.00,NULL),(641,317,5,4700.00,0.00,NULL),(642,317,1,0.00,4700.00,NULL),(643,318,4,2610.00,0.00,NULL),(644,318,5,0.00,2610.00,NULL),(645,319,5,2610.00,0.00,NULL),(646,319,1,0.00,2610.00,NULL),(647,320,4,5750.00,0.00,NULL),(648,320,5,0.00,5750.00,NULL),(649,321,5,5750.00,0.00,NULL),(650,321,1,0.00,5750.00,NULL),(651,322,4,2100.00,0.00,NULL),(652,322,5,0.00,2100.00,NULL),(653,323,5,2100.00,0.00,NULL),(654,323,1,0.00,2100.00,NULL),(655,324,4,3000.00,0.00,NULL),(656,324,5,0.00,3000.00,NULL),(657,325,5,3000.00,0.00,NULL),(658,325,1,0.00,3000.00,NULL),(659,326,4,44400.00,0.00,NULL),(660,326,5,0.00,44400.00,NULL),(661,327,5,44400.00,0.00,NULL),(662,327,1,0.00,44400.00,NULL),(663,328,4,1100.00,0.00,NULL),(664,328,5,0.00,1100.00,NULL),(665,329,5,1100.00,0.00,NULL),(666,329,1,0.00,1100.00,NULL),(667,330,4,5950.00,0.00,NULL),(668,330,5,0.00,5950.00,NULL),(669,331,5,5950.00,0.00,NULL),(670,331,1,0.00,5950.00,NULL),(671,332,68,2800.00,0.00,'A3 Photocopy'),(672,332,1,0.00,2800.00,'Payment via Cash'),(673,333,13,2800.00,0.00,'Cutting'),(674,333,1,0.00,2800.00,'Payment via Cash'),(677,335,13,7850.00,0.00,'Cutting'),(678,335,1,0.00,7850.00,'Payment via Cash'),(679,336,12,740.00,0.00,'Courier charge'),(680,336,1,0.00,740.00,'Payment via Cash'),(681,337,13,13650.00,0.00,'P.B Cord '),(682,337,1,0.00,13650.00,'Payment via Cash'),(683,338,68,17000.00,0.00,'Printing'),(684,338,1,0.00,17000.00,'Payment via Cash'),(685,339,4,12320.00,0.00,NULL),(686,339,5,0.00,12320.00,NULL),(687,340,5,12320.00,0.00,NULL),(688,340,1,0.00,12320.00,NULL),(689,341,4,13600.00,0.00,NULL),(690,341,5,0.00,13600.00,NULL),(691,342,5,13600.00,0.00,NULL),(692,342,1,0.00,13600.00,NULL),(693,343,4,24450.00,0.00,NULL),(694,343,5,0.00,24450.00,NULL),(695,344,5,24450.00,0.00,NULL),(696,344,1,0.00,24450.00,NULL),(697,345,2,21835.00,0.00,NULL),(698,345,3,0.00,21835.00,NULL),(699,346,2,22500.00,0.00,NULL),(700,346,3,0.00,22500.00,NULL),(701,347,2,16505.00,0.00,NULL),(702,347,3,0.00,16505.00,NULL),(703,348,2,26990.00,0.00,NULL),(704,348,3,0.00,26990.00,NULL),(705,349,2,31490.00,0.00,NULL),(706,349,3,0.00,31490.00,NULL),(707,350,2,17535.00,0.00,NULL),(708,350,3,0.00,17535.00,NULL),(709,351,2,10565.00,0.00,NULL),(710,351,3,0.00,10565.00,NULL),(711,352,2,2580.00,0.00,NULL),(712,352,3,0.00,2580.00,NULL),(713,353,68,58050.00,0.00,'PRINTING WORKS'),(714,353,1,0.00,58050.00,'Payment via Cash'),(715,354,1,17720.00,0.00,NULL),(716,354,3,0.00,17720.00,NULL),(717,355,1,18540.00,0.00,NULL),(718,355,3,0.00,18540.00,NULL),(719,356,1,11460.00,0.00,NULL),(720,356,3,0.00,11460.00,NULL),(721,357,4,34300.00,0.00,NULL),(722,357,5,0.00,34300.00,NULL),(723,358,5,34300.00,0.00,NULL),(724,358,1,0.00,34300.00,NULL),(725,359,68,5100.00,0.00,'Anoshan'),(726,359,1,0.00,5100.00,'Payment via Cash'),(727,360,4,1500.00,0.00,NULL),(728,360,5,0.00,1500.00,NULL),(729,361,1,180.00,0.00,NULL),(730,361,3,0.00,180.00,NULL),(731,362,1,5945.00,0.00,NULL),(732,362,3,0.00,5945.00,NULL),(733,363,1,5880.00,0.00,NULL),(734,363,3,0.00,5880.00,NULL),(735,364,1,700.00,0.00,NULL),(736,364,3,0.00,700.00,NULL),(737,365,1,2440.00,0.00,NULL),(738,365,3,0.00,2440.00,NULL),(739,366,2,2440.00,0.00,NULL),(740,366,3,0.00,2440.00,NULL),(741,367,2,2800.00,0.00,NULL),(742,367,3,0.00,2800.00,NULL),(743,368,2,6680.00,0.00,NULL),(744,368,3,0.00,6680.00,NULL),(745,369,2,2000.00,0.00,NULL),(746,369,3,0.00,2000.00,NULL),(747,370,2,4020.00,0.00,NULL),(748,370,3,0.00,4020.00,NULL),(749,371,2,10680.00,0.00,NULL),(750,371,3,0.00,10680.00,NULL),(751,372,2,3000.00,0.00,NULL),(752,372,3,0.00,3000.00,NULL),(753,373,2,280.00,0.00,NULL),(754,373,3,0.00,280.00,NULL),(755,374,2,900.00,0.00,NULL),(756,374,3,0.00,900.00,NULL),(757,375,2,900.00,0.00,NULL),(758,375,3,0.00,900.00,NULL),(759,376,2,31250.00,0.00,NULL),(760,376,3,0.00,31250.00,NULL),(761,377,2,1200.00,0.00,NULL),(762,377,3,0.00,1200.00,NULL),(763,378,2,1540.00,0.00,NULL),(764,378,3,0.00,1540.00,NULL),(765,379,2,200.00,0.00,NULL),(766,379,3,0.00,200.00,NULL),(767,380,2,1800.00,0.00,NULL),(768,380,3,0.00,1800.00,NULL),(769,381,2,800.00,0.00,NULL),(770,381,3,0.00,800.00,NULL),(771,382,4,5550.00,0.00,NULL),(772,382,5,0.00,5550.00,NULL),(773,383,5,5550.00,0.00,NULL),(774,383,2,0.00,5550.00,NULL),(775,384,4,479636.00,0.00,NULL),(776,384,5,0.00,479636.00,NULL),(777,385,4,412616.00,0.00,NULL),(778,385,5,0.00,412616.00,NULL),(779,386,4,4950.00,0.00,NULL),(780,386,5,0.00,4950.00,NULL),(781,387,5,4000.00,0.00,NULL),(782,387,1,0.00,4000.00,NULL),(783,388,1,660.00,0.00,NULL),(784,388,3,0.00,660.00,NULL),(785,389,1,1295.00,0.00,NULL),(786,389,3,0.00,1295.00,NULL),(787,390,1,5000.00,0.00,NULL),(788,390,3,0.00,5000.00,NULL),(789,391,1,5600.00,0.00,NULL),(790,391,3,0.00,5600.00,NULL),(791,392,1,600.00,0.00,NULL),(792,392,3,0.00,600.00,NULL),(793,393,1,84445.00,0.00,NULL),(794,393,3,0.00,84445.00,NULL),(795,394,1,2250.00,0.00,NULL),(796,394,3,0.00,2250.00,NULL),(797,395,1,990.00,0.00,NULL),(798,395,3,0.00,990.00,NULL),(799,396,1,2400.00,0.00,NULL),(800,396,3,0.00,2400.00,NULL),(801,397,1,1160.00,0.00,NULL),(802,397,3,0.00,1160.00,NULL),(803,398,1,11310.00,0.00,NULL),(804,398,3,0.00,11310.00,NULL),(805,399,1,1000.00,0.00,NULL),(806,399,3,0.00,1000.00,NULL),(807,400,1,1480.00,0.00,NULL),(808,400,3,0.00,1480.00,NULL),(809,401,1,14000.00,0.00,NULL),(810,401,3,0.00,14000.00,NULL),(811,402,1,1800.00,0.00,NULL),(812,402,3,0.00,1800.00,NULL),(813,403,1,2880.00,0.00,NULL),(814,403,3,0.00,2880.00,NULL),(815,404,1,2120.00,0.00,NULL),(816,404,3,0.00,2120.00,NULL),(817,405,1,2840.00,0.00,NULL),(818,405,3,0.00,2840.00,NULL),(819,406,1,300.00,0.00,NULL),(820,406,3,0.00,300.00,NULL),(821,407,1,300.00,0.00,NULL),(822,407,3,0.00,300.00,NULL),(823,408,1,4020.00,0.00,NULL),(824,408,3,0.00,4020.00,NULL),(825,409,1,1400.00,0.00,NULL),(826,409,3,0.00,1400.00,NULL),(827,410,1,30000.00,0.00,NULL),(828,410,3,0.00,30000.00,NULL),(829,411,1,14500.00,0.00,NULL),(830,411,3,0.00,14500.00,NULL),(831,412,1,5460.00,0.00,NULL),(832,412,3,0.00,5460.00,NULL),(833,413,1,1100.00,0.00,NULL),(834,413,3,0.00,1100.00,NULL),(835,414,1,300.00,0.00,NULL),(836,414,3,0.00,300.00,NULL),(837,415,1,2310.00,0.00,NULL),(838,415,3,0.00,2310.00,NULL),(839,416,1,1600.00,0.00,NULL),(840,416,3,0.00,1600.00,NULL),(841,417,1,1200.00,0.00,NULL),(842,417,3,0.00,1200.00,NULL),(843,418,1,600.00,0.00,NULL),(844,418,3,0.00,600.00,NULL),(845,419,1,6540.00,0.00,NULL),(846,419,3,0.00,6540.00,NULL),(847,420,1,2760.00,0.00,NULL),(848,420,3,0.00,2760.00,NULL),(849,421,1,2035.00,0.00,NULL),(850,421,3,0.00,2035.00,NULL),(851,422,1,4470.00,0.00,NULL),(852,422,3,0.00,4470.00,NULL),(853,423,1,60.00,0.00,NULL),(854,423,3,0.00,60.00,NULL),(855,424,1,1260.00,0.00,NULL),(856,424,3,0.00,1260.00,NULL),(857,425,1,320.00,0.00,NULL),(858,425,3,0.00,320.00,NULL),(859,426,1,8325.00,0.00,NULL),(860,426,3,0.00,8325.00,NULL),(861,427,1,1960.00,0.00,NULL),(862,427,3,0.00,1960.00,NULL),(863,428,1,14000.00,0.00,NULL),(864,428,3,0.00,14000.00,NULL),(865,429,1,3280.00,0.00,NULL),(866,429,3,0.00,3280.00,NULL),(867,430,1,2400.00,0.00,NULL),(868,430,3,0.00,2400.00,NULL),(869,431,1,750.00,0.00,NULL),(870,431,3,0.00,750.00,NULL),(871,432,1,3800.00,0.00,NULL),(872,432,3,0.00,3800.00,NULL),(873,433,1,750.00,0.00,NULL),(874,433,3,0.00,750.00,NULL),(875,434,1,50420.00,0.00,NULL),(876,434,3,0.00,50420.00,NULL),(877,435,2,50420.00,0.00,NULL),(878,435,3,0.00,50420.00,NULL),(879,436,1,1200.00,0.00,NULL),(880,436,3,0.00,1200.00,NULL),(881,437,1,6400.00,0.00,NULL),(882,437,3,0.00,6400.00,NULL),(883,438,1,3000.00,0.00,NULL),(884,438,3,0.00,3000.00,NULL),(885,439,1,1600.00,0.00,NULL),(886,439,3,0.00,1600.00,NULL),(887,440,1,4620.00,0.00,NULL),(888,440,3,0.00,4620.00,NULL),(889,441,1,1100.00,0.00,NULL),(890,441,3,0.00,1100.00,NULL),(891,442,1,2000.00,0.00,NULL),(892,442,3,0.00,2000.00,NULL),(893,443,1,6500.00,0.00,NULL),(894,443,3,0.00,6500.00,NULL),(895,444,1,15000.00,0.00,NULL),(896,444,3,0.00,15000.00,NULL),(897,445,1,2000.00,0.00,NULL),(898,445,3,0.00,2000.00,NULL),(899,446,1,4500.00,0.00,NULL),(900,446,3,0.00,4500.00,NULL),(901,447,1,5900.00,0.00,NULL),(902,447,3,0.00,5900.00,NULL),(903,448,1,18200.00,0.00,NULL),(904,448,3,0.00,18200.00,NULL),(905,449,1,6600.00,0.00,NULL),(906,449,3,0.00,6600.00,NULL),(907,450,1,13000.00,0.00,NULL),(908,450,3,0.00,13000.00,NULL),(909,451,1,8750.00,0.00,NULL),(910,451,3,0.00,8750.00,NULL),(911,452,1,1500.00,0.00,NULL),(912,452,3,0.00,1500.00,NULL),(913,453,1,7200.00,0.00,NULL),(914,453,3,0.00,7200.00,NULL),(915,454,1,4500.00,0.00,NULL),(916,454,3,0.00,4500.00,NULL),(917,455,1,3500.00,0.00,NULL),(918,455,3,0.00,3500.00,NULL),(919,456,1,12000.00,0.00,NULL),(920,456,3,0.00,12000.00,NULL),(921,457,1,12500.00,0.00,NULL),(922,457,3,0.00,12500.00,NULL),(923,458,1,3000.00,0.00,NULL),(924,458,3,0.00,3000.00,NULL),(925,459,1,5300.00,0.00,NULL),(926,459,3,0.00,5300.00,NULL),(927,460,1,7000.00,0.00,NULL),(928,460,3,0.00,7000.00,NULL),(929,461,1,5000.00,0.00,NULL),(930,461,3,0.00,5000.00,NULL),(931,462,1,200.00,0.00,NULL),(932,462,3,0.00,200.00,NULL),(933,463,1,2000.00,0.00,NULL),(934,463,3,0.00,2000.00,NULL),(935,464,1,20000.00,0.00,NULL),(936,464,3,0.00,20000.00,NULL),(937,465,1,17520.00,0.00,NULL),(938,465,3,0.00,17520.00,NULL),(939,466,1,60000.00,0.00,NULL),(940,466,3,0.00,60000.00,NULL),(941,467,1,25000.00,0.00,NULL),(942,467,3,0.00,25000.00,NULL),(943,468,1,25000.00,0.00,NULL),(944,468,3,0.00,25000.00,NULL),(945,469,1,13500.00,0.00,NULL),(946,469,3,0.00,13500.00,NULL),(947,470,1,14000.00,0.00,NULL),(948,470,3,0.00,14000.00,NULL),(949,471,12,650.00,0.00,'PICK ME & AUTO'),(950,471,1,0.00,650.00,'Payment via Cash'),(951,472,13,515.00,0.00,'BROOM STICK '),(952,472,1,0.00,515.00,'Payment via Cash'),(953,473,13,515.00,0.00,'BROOM STICK '),(954,473,1,0.00,515.00,'Payment via Cash'),(955,474,5,950.00,0.00,NULL),(956,474,1,0.00,950.00,NULL),(957,475,4,2310.00,0.00,NULL),(958,475,5,0.00,2310.00,NULL),(959,476,5,2310.00,0.00,NULL),(960,476,1,0.00,2310.00,NULL),(961,477,1,3200.00,0.00,NULL),(962,477,3,0.00,3200.00,NULL),(963,478,1,3870.00,0.00,NULL),(964,478,3,0.00,3870.00,NULL),(965,479,2,404000.00,0.00,NULL),(966,479,3,0.00,404000.00,NULL),(967,480,2,24700.00,0.00,NULL),(968,480,3,0.00,24700.00,NULL),(969,481,1,8810.00,0.00,NULL),(970,481,3,0.00,8810.00,NULL),(971,482,1,41045.00,0.00,NULL),(972,482,3,0.00,41045.00,NULL),(973,483,1,30610.00,0.00,NULL),(974,483,3,0.00,30610.00,NULL),(975,484,1,61995.00,0.00,NULL),(976,484,3,0.00,61995.00,NULL),(977,485,1,17950.00,0.00,NULL),(978,485,3,0.00,17950.00,NULL),(979,486,1,19920.00,0.00,NULL),(980,486,3,0.00,19920.00,NULL),(981,487,1,16330.00,0.00,NULL),(982,487,3,0.00,16330.00,NULL),(983,488,1,17120.00,0.00,NULL),(984,488,3,0.00,17120.00,NULL),(985,489,1,15450.00,0.00,NULL),(986,489,3,0.00,15450.00,NULL),(987,490,1,2060.00,0.00,NULL),(988,490,3,0.00,2060.00,NULL),(989,491,1,8780.00,0.00,NULL),(990,491,3,0.00,8780.00,NULL),(991,492,1,42465.00,0.00,NULL),(992,492,3,0.00,42465.00,NULL),(993,493,1,5470.00,0.00,NULL),(994,493,3,0.00,5470.00,NULL),(995,494,1,10910.00,0.00,NULL),(996,494,3,0.00,10910.00,NULL),(997,495,1,8950.00,0.00,NULL),(998,495,3,0.00,8950.00,NULL),(999,496,1,9850.00,0.00,NULL),(1000,496,3,0.00,9850.00,NULL),(1001,497,1,14330.00,0.00,NULL),(1002,497,3,0.00,14330.00,NULL),(1003,498,1,6760.00,0.00,NULL),(1004,498,3,0.00,6760.00,NULL),(1005,499,1,13010.00,0.00,NULL),(1006,499,3,0.00,13010.00,NULL),(1007,500,1,18370.00,0.00,NULL),(1008,500,3,0.00,18370.00,NULL),(1009,501,1,18930.00,0.00,NULL),(1010,501,3,0.00,18930.00,NULL),(1011,502,1,1370.00,0.00,NULL),(1012,502,3,0.00,1370.00,NULL),(1013,503,1,8095.00,0.00,NULL),(1014,503,3,0.00,8095.00,NULL),(1015,504,1,2940.00,0.00,NULL),(1016,504,3,0.00,2940.00,NULL),(1017,505,1,15740.00,0.00,NULL),(1018,505,3,0.00,15740.00,NULL),(1019,506,1,17010.00,0.00,NULL),(1020,506,3,0.00,17010.00,NULL),(1021,507,1,26465.00,0.00,NULL),(1022,507,3,0.00,26465.00,NULL),(1023,508,1,9650.00,0.00,NULL),(1024,508,3,0.00,9650.00,NULL),(1025,509,1,2260.00,0.00,NULL),(1026,509,3,0.00,2260.00,NULL),(1027,510,1,10460.00,0.00,NULL),(1028,510,3,0.00,10460.00,NULL),(1029,511,1,10280.00,0.00,NULL),(1030,511,3,0.00,10280.00,NULL),(1031,512,1,20370.00,0.00,NULL),(1032,512,3,0.00,20370.00,NULL),(1033,513,1,5910.00,0.00,NULL),(1034,513,3,0.00,5910.00,NULL),(1035,514,1,4300.00,0.00,NULL),(1036,514,3,0.00,4300.00,NULL),(1037,515,1,13880.00,0.00,NULL),(1038,515,3,0.00,13880.00,NULL),(1039,516,1,6770.00,0.00,NULL),(1040,516,3,0.00,6770.00,NULL),(1041,517,2,40000.00,0.00,NULL),(1042,517,3,0.00,40000.00,NULL),(1043,518,1,11950.00,0.00,NULL),(1044,518,3,0.00,11950.00,NULL),(1045,519,1,9320.00,0.00,NULL),(1046,519,3,0.00,9320.00,NULL),(1047,520,1,975.00,0.00,NULL),(1048,520,3,0.00,975.00,NULL),(1049,521,1,3745.00,0.00,NULL),(1050,521,3,0.00,3745.00,NULL),(1051,522,1,6390.00,0.00,NULL),(1052,522,3,0.00,6390.00,NULL),(1053,523,1,1120.00,0.00,NULL),(1054,523,3,0.00,1120.00,NULL),(1055,524,4,21440.00,0.00,NULL),(1056,524,5,0.00,21440.00,NULL),(1057,525,5,21440.00,0.00,NULL),(1058,525,1,0.00,21440.00,NULL),(1059,526,4,27838.00,0.00,NULL),(1060,526,5,0.00,27838.00,NULL),(1061,527,4,38800.00,0.00,NULL),(1062,527,5,0.00,38800.00,NULL),(1063,528,4,26910.00,0.00,NULL),(1064,528,5,0.00,26910.00,NULL),(1065,529,4,5640.00,0.00,NULL),(1066,529,5,0.00,5640.00,NULL),(1067,530,4,9200.00,0.00,NULL),(1068,530,5,0.00,9200.00,NULL),(1069,531,4,7682.00,0.00,NULL),(1070,531,5,0.00,7682.00,NULL),(1071,532,1,3600.00,0.00,NULL),(1072,532,3,0.00,3600.00,NULL),(1073,533,4,3700.00,0.00,NULL),(1074,533,5,0.00,3700.00,NULL),(1075,534,4,6600.00,0.00,NULL),(1076,534,5,0.00,6600.00,NULL),(1077,535,4,8200.00,0.00,NULL),(1078,535,5,0.00,8200.00,NULL),(1079,536,4,4250.00,0.00,NULL),(1080,536,5,0.00,4250.00,NULL),(1081,537,4,8200.00,0.00,NULL),(1082,537,5,0.00,8200.00,NULL),(1083,538,4,15320.00,0.00,NULL),(1084,538,5,0.00,15320.00,NULL),(1085,539,4,4700.00,0.00,NULL),(1086,539,5,0.00,4700.00,NULL),(1087,540,4,14510.00,0.00,NULL),(1088,540,5,0.00,14510.00,NULL),(1089,541,4,2200.00,0.00,NULL),(1090,541,5,0.00,2200.00,NULL),(1091,542,4,4700.00,0.00,NULL),(1092,542,5,0.00,4700.00,NULL),(1093,543,4,3240.00,0.00,NULL),(1094,543,5,0.00,3240.00,NULL),(1095,544,4,8200.00,0.00,NULL),(1096,544,5,0.00,8200.00,NULL),(1097,545,4,8200.00,0.00,NULL),(1098,545,5,0.00,8200.00,NULL),(1099,546,4,7800.00,0.00,NULL),(1100,546,5,0.00,7800.00,NULL),(1101,547,5,7800.00,0.00,NULL),(1102,547,1,0.00,7800.00,NULL),(1103,548,1,750.00,0.00,NULL),(1104,548,3,0.00,750.00,NULL),(1105,549,1,11250.00,0.00,NULL),(1106,549,3,0.00,11250.00,NULL),(1107,550,1,15750.00,0.00,NULL),(1108,550,3,0.00,15750.00,NULL),(1109,551,1,6100.00,0.00,NULL),(1110,551,3,0.00,6100.00,NULL),(1111,552,5,1500.00,0.00,NULL),(1112,552,1,0.00,1500.00,NULL),(1113,553,4,7800.00,0.00,NULL),(1114,553,5,0.00,7800.00,NULL),(1115,554,5,7800.00,0.00,NULL),(1116,554,1,0.00,7800.00,NULL),(1117,555,4,13250.00,0.00,NULL),(1118,555,5,0.00,13250.00,NULL),(1119,556,5,13250.00,0.00,NULL),(1120,556,1,0.00,13250.00,NULL),(1121,557,4,5405.00,0.00,NULL),(1122,557,5,0.00,5405.00,NULL),(1123,558,4,15350.00,0.00,NULL),(1124,558,5,0.00,15350.00,NULL),(1125,559,4,1075.00,0.00,NULL),(1126,559,5,0.00,1075.00,NULL),(1127,560,4,300.00,0.00,NULL),(1128,560,5,0.00,300.00,NULL),(1129,561,4,765.00,0.00,NULL),(1130,561,5,0.00,765.00,NULL),(1131,562,4,10945.00,0.00,NULL),(1132,562,5,0.00,10945.00,NULL),(1133,563,4,1410.00,0.00,NULL),(1134,563,5,0.00,1410.00,NULL),(1135,564,4,11985.00,0.00,NULL),(1136,564,5,0.00,11985.00,NULL),(1137,565,4,7520.00,0.00,NULL),(1138,565,5,0.00,7520.00,NULL),(1139,566,4,12750.00,0.00,NULL),(1140,566,5,0.00,12750.00,NULL),(1141,567,4,3675.00,0.00,NULL),(1142,567,5,0.00,3675.00,NULL),(1143,568,4,10735.00,0.00,NULL),(1144,568,5,0.00,10735.00,NULL),(1145,569,4,470.00,0.00,NULL),(1146,569,5,0.00,470.00,NULL),(1147,570,4,12450.00,0.00,NULL),(1148,570,5,0.00,12450.00,NULL),(1149,571,4,4700.00,0.00,NULL),(1150,571,5,0.00,4700.00,NULL),(1151,572,4,25435.00,0.00,NULL),(1152,572,5,0.00,25435.00,NULL),(1153,573,4,705.00,0.00,NULL),(1154,573,5,0.00,705.00,NULL),(1155,574,4,1770.00,0.00,NULL),(1156,574,5,0.00,1770.00,NULL),(1157,575,4,265.00,0.00,NULL),(1158,575,5,0.00,265.00,NULL),(1159,576,4,3995.00,0.00,NULL),(1160,576,5,0.00,3995.00,NULL),(1161,577,4,3000.00,0.00,NULL),(1162,577,5,0.00,3000.00,NULL),(1163,578,4,2310.00,0.00,NULL),(1164,578,5,0.00,2310.00,NULL),(1165,579,4,4950.00,0.00,NULL),(1166,579,5,0.00,4950.00,NULL),(1167,580,5,5405.00,0.00,NULL),(1168,580,1,0.00,5405.00,NULL),(1169,581,5,15350.00,0.00,NULL),(1170,581,1,0.00,15350.00,NULL),(1171,582,5,1075.00,0.00,NULL),(1172,582,1,0.00,1075.00,NULL),(1173,583,5,300.00,0.00,NULL),(1174,583,1,0.00,300.00,NULL),(1175,584,5,765.00,0.00,NULL),(1176,584,1,0.00,765.00,NULL),(1177,585,5,7520.00,0.00,NULL),(1178,585,1,0.00,7520.00,NULL),(1179,586,5,11985.00,0.00,NULL),(1180,586,1,0.00,11985.00,NULL),(1181,587,5,12750.00,0.00,NULL),(1182,587,1,0.00,12750.00,NULL),(1183,588,5,1410.00,0.00,NULL),(1184,588,1,0.00,1410.00,NULL),(1185,589,5,10945.00,0.00,NULL),(1186,589,1,0.00,10945.00,NULL),(1187,590,5,3675.00,0.00,NULL),(1188,590,1,0.00,3675.00,NULL),(1189,591,5,470.00,0.00,NULL),(1190,591,1,0.00,470.00,NULL),(1191,592,5,10735.00,0.00,NULL),(1192,592,1,0.00,10735.00,NULL),(1193,593,5,1500.00,0.00,NULL),(1194,593,1,0.00,1500.00,NULL),(1195,594,5,12450.00,0.00,NULL),(1196,594,1,0.00,12450.00,NULL),(1197,595,5,4700.00,0.00,NULL),(1198,595,1,0.00,4700.00,NULL),(1199,596,5,25435.00,0.00,NULL),(1200,596,1,0.00,25435.00,NULL),(1201,597,4,760.00,0.00,NULL),(1202,597,5,0.00,760.00,NULL),(1203,598,5,760.00,0.00,NULL),(1204,598,1,0.00,760.00,NULL),(1205,599,1,4490.00,0.00,NULL),(1206,599,3,0.00,4490.00,NULL),(1207,600,1,1800.00,0.00,NULL),(1208,600,3,0.00,1800.00,NULL),(1209,601,1,250.00,0.00,NULL),(1210,601,3,0.00,250.00,NULL),(1211,602,1,1680.00,0.00,NULL),(1212,602,3,0.00,1680.00,NULL);
/*!40000 ALTER TABLE `journal_entry_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entry_details_backup`
--

DROP TABLE IF EXISTS `journal_entry_details_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_entry_details_backup` (
  `JournalDetailID` int NOT NULL DEFAULT '0',
  `JournalID` int NOT NULL,
  `AccountID` int NOT NULL,
  `DebitAmount` decimal(18,2) DEFAULT '0.00',
  `CreditAmount` decimal(18,2) DEFAULT '0.00',
  `Description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entry_details_backup`
--

LOCK TABLES `journal_entry_details_backup` WRITE;
/*!40000 ALTER TABLE `journal_entry_details_backup` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_entry_details_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `LocationId` int NOT NULL AUTO_INCREMENT,
  `ShopName` varchar(255) NOT NULL,
  `Address` text NOT NULL,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` int DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`LocationId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `numbering_settings`
--

DROP TABLE IF EXISTS `numbering_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `numbering_settings` (
  `ModuleName` varchar(100) DEFAULT NULL,
  `Prefix` varchar(20) DEFAULT NULL,
  `CurrentNo` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `numbering_settings`
--

LOCK TABLES `numbering_settings` WRITE;
/*!40000 ALTER TABLE `numbering_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `numbering_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opening_balances`
--

DROP TABLE IF EXISTS `opening_balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opening_balances` (
  `OpeningID` int NOT NULL AUTO_INCREMENT,
  `CashBalance` decimal(18,2) DEFAULT '0.00',
  `InventoryBalance` decimal(18,2) DEFAULT '0.00',
  `ReceivableBalance` decimal(18,2) DEFAULT '0.00',
  `PayableBalance` decimal(18,2) DEFAULT '0.00',
  `CapitalBalance` decimal(18,2) DEFAULT '0.00',
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`OpeningID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opening_balances`
--

LOCK TABLES `opening_balances` WRITE;
/*!40000 ALTER TABLE `opening_balances` DISABLE KEYS */;
/*!40000 ALTER TABLE `opening_balances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Users List','users_list'),(2,'Add Users','users_add'),(3,'Edit Users','users_edit'),(4,'Delete Users','users_delete'),(5,'Users View','users_view'),(6,'Roles List','roles_list'),(7,'Add Roles','roles_add'),(8,'Edit Roles','roles_edit'),(9,'Permissions List','permissions_list'),(10,'Add Permissions','permissions_add'),(11,' Edit Permissions','permissions_edit'),(12,'Category List','category_list'),(13,'Add Category ','category_add'),(23,'Edit Category ','category_edit'),(24,'Delete Category','category_delete'),(25,'Brand List','brand_list'),(26,'Add Brand','brand_add'),(27,'Edit Brand','brand_edit'),(28,'Delete Brand','brand_delete'),(29,'Item List','item_list'),(30,'Add Item','item_add'),(31,'Edit Item','item_edit'),(32,'Delete Item','item_delete'),(33,'Customer List','customer_list'),(34,'Add Customer','customer_add'),(35,'Edit Customer','customer_edit'),(36,'Delete Customer','customer_delete'),(37,'Vendor List','vendor_list'),(38,'Add Vendor','vendor_add'),(39,'Edit Vendor','vendor_edit'),(40,'Delete Vendor','vendor_delete'),(41,'Purchases List','purchases_list'),(42,'Add Purchases','purchases_add'),(43,'Edit Purchases','purchases_edit'),(44,'Delete Purchases','purchases_delete'),(45,'Vendor Payment List','vendor_payment_list'),(46,'Add Vendor Payment','vendor_payment_add'),(47,'Edit Vendor Payment','vendor_payment_edit'),(48,'Delete Vendor Payment','vendor_payment_delete'),(49,'Sales List','sales_list'),(50,'Add Sales','sales_add'),(51,'Edit Sales','sales_edit'),(52,'Delete Sales','sales_delete'),(53,'Customer Payment List','customer_payment_list'),(54,'Add Customer Payment','customer_payment_add'),(55,'Edit Customer Payment','customer_payment_edit'),(56,'Delete Customer Payment','customer_payment_delete'),(57,'Stock Report','stock_report'),(58,'Customer Balance Steet Report','customer_balance_sheet_report'),(59,'Vendor Balance Steet Report','vendor_balance_sheet_report');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privileges`
--

DROP TABLE IF EXISTS `privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privileges` (
  `PrivilegesId` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(155) DEFAULT NULL,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` int DEFAULT NULL,
  `ModifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PrivilegesId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privileges`
--

LOCK TABLES `privileges` WRITE;
/*!40000 ALTER TABLE `privileges` DISABLE KEYS */;
/*!40000 ALTER TABLE `privileges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_items` (
  `PurchaseItemID` int NOT NULL AUTO_INCREMENT,
  `PurchaseID` int NOT NULL,
  `ItemID` int DEFAULT NULL,
  `Quantity` decimal(10,2) DEFAULT NULL,
  `QtyType` varchar(50) DEFAULT NULL,
  `PurchasePrice` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalAmount` decimal(10,2) DEFAULT NULL,
  `CategoryID` int DEFAULT NULL,
  `BrandID` int DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`PurchaseItemID`),
  KEY `PurchaseID` (`PurchaseID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
INSERT INTO `purchase_items` VALUES (1,1,NULL,1.00,'Pcs',1500.00,0.00,1500.00,NULL,NULL,'Test description 123'),(2,42,NULL,1.00,'Pcs',5550.00,0.00,5550.00,NULL,NULL,'2mm Mag Foil Block '),(3,43,NULL,46318.00,'Pcs',10.00,0.00,463180.00,NULL,NULL,'COLOUR CLICK'),(4,43,NULL,4114.00,'Pcs',4.00,0.00,16456.00,NULL,NULL,'BLACK CLICK'),(5,44,NULL,36774.00,'Pcs',10.00,0.00,367740.00,NULL,NULL,'COLOUR CLICK'),(6,44,NULL,11219.00,'Pcs',4.00,0.00,44876.00,NULL,NULL,'BLACK CLICK'),(10,47,NULL,6.00,'Pcs',2990.00,0.00,17940.00,NULL,NULL,'Glass Trophy'),(11,47,NULL,100.00,'Pcs',35.00,0.00,3500.00,NULL,NULL,'PIN'),(12,48,NULL,270.00,'Boxes',58.00,0.00,15660.00,NULL,NULL,'250 B/BO'),(13,48,NULL,1.00,'Pcs',12178.00,0.00,12178.00,NULL,NULL,'POLY TACK'),(14,49,NULL,1100.00,'Pcs',35.00,0.00,38500.00,NULL,NULL,'A/PAPER '),(15,49,NULL,1.00,'Pcs',300.00,0.00,300.00,NULL,NULL,'CUTTING'),(16,50,NULL,345.00,'Sheets',78.00,0.00,26910.00,NULL,NULL,'A/BOARD'),(17,51,NULL,68.00,'Pcs',80.00,0.00,5440.00,NULL,NULL,'POLYTACK'),(18,51,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(19,52,NULL,1.00,'Packets',9000.00,0.00,9000.00,NULL,NULL,'A/BOARD'),(20,52,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(21,53,NULL,170.00,'Pcs',26.00,0.00,4420.00,NULL,NULL,'N/C/R'),(22,53,NULL,36.00,'Pcs',17.00,0.00,612.00,NULL,NULL,'B/SH'),(23,53,NULL,90.00,'Pcs',25.00,0.00,2250.00,NULL,NULL,'B/SH'),(24,53,NULL,1.00,'Pcs',400.00,0.00,400.00,NULL,NULL,'CUTTING'),(25,54,NULL,100.00,'Pcs',35.00,0.00,3500.00,NULL,NULL,'A/PAPER '),(26,54,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(27,55,NULL,100.00,'Sheets',27.00,0.00,2700.00,NULL,NULL,'A/PAPER 120'),(28,55,NULL,100.00,'Sheets',35.00,0.00,3500.00,NULL,NULL,'A/PAPER 150'),(29,55,NULL,1.00,'Pcs',400.00,0.00,400.00,NULL,NULL,'CUTTING'),(30,56,NULL,1.00,'Packets',8000.00,0.00,8000.00,NULL,NULL,'POLYTACK'),(31,56,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(32,57,NULL,5.00,'Packets',850.00,0.00,4250.00,NULL,NULL,'A/BOARD'),(33,58,NULL,1.00,'Packets',8000.00,0.00,8000.00,NULL,NULL,'POLYTACK'),(34,58,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(35,59,NULL,1080.00,'Sheets',14.00,0.00,15120.00,NULL,NULL,'B/SH'),(36,59,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(37,60,NULL,50.00,'Sheets',90.00,0.00,4500.00,NULL,NULL,'A/BOARD 310'),(38,60,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(39,61,NULL,270.00,'Sheets',27.00,0.00,7290.00,NULL,NULL,'Mid (P)'),(40,61,NULL,270.00,'Sheets',26.00,0.00,7020.00,NULL,NULL,'Bot (Y)'),(41,61,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(42,62,NULL,25.00,'Pcs',80.00,0.00,2000.00,NULL,NULL,'POLYTACK'),(43,62,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(44,63,NULL,50.00,'Pcs',90.00,0.00,4500.00,NULL,NULL,'A/BOARD 310'),(45,63,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(46,64,NULL,38.00,'Pcs',80.00,0.00,3040.00,NULL,NULL,'A/BOARD 260'),(47,64,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(48,65,NULL,1.00,'Packets',8000.00,0.00,8000.00,NULL,NULL,'POLYTACK'),(49,65,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(50,66,NULL,1.00,'Packets',8000.00,0.00,8000.00,NULL,NULL,'POLYTACK'),(51,66,NULL,1.00,'Pcs',200.00,0.00,200.00,NULL,NULL,'CUTTING'),(52,67,NULL,3.00,'Rolls',2600.00,0.00,7800.00,NULL,NULL,'1220 X 2440 2mm PVC Foam board'),(53,46,NULL,8.00,'Packets',235.00,0.00,1880.00,NULL,NULL,'062'),(54,46,NULL,2.00,'Packets',215.00,0.00,430.00,NULL,NULL,'012'),(55,45,74,300.00,'Sheets',16.50,0.00,4950.00,2,NULL,'Conqueror Paper 120GSM'),(57,69,NULL,10.00,'Rolls',1180.00,0.00,11800.00,NULL,NULL,'XSTAND-A'),(58,69,NULL,1.00,'Rolls',1450.00,0.00,1450.00,NULL,NULL,'TAPE-GELTAPE'),(59,68,NULL,3.00,'Rolls',2600.00,0.00,7800.00,NULL,NULL,'1220 X 2440 2mm PVC Foam board'),(60,70,NULL,23.00,'Pcs',235.00,0.00,5405.00,NULL,NULL,'016'),(61,71,NULL,70.00,'Pcs',215.00,0.00,15050.00,NULL,NULL,'001'),(62,71,NULL,2.00,'Pcs',150.00,0.00,300.00,NULL,NULL,'43'),(63,72,NULL,5.00,'Pcs',215.00,0.00,1075.00,NULL,NULL,'001'),(65,74,NULL,3.00,'Pcs',255.00,0.00,765.00,NULL,NULL,'092'),(66,73,NULL,2.00,'Pcs',150.00,0.00,300.00,NULL,NULL,'010'),(67,75,NULL,16.00,'Pcs',235.00,0.00,3760.00,NULL,NULL,'023'),(68,75,NULL,350.00,'Pcs',16.50,0.00,5775.00,NULL,NULL,'7.5 X 5.5 23'),(69,75,NULL,6.00,'Pcs',235.00,0.00,1410.00,NULL,NULL,'29 B'),(70,76,NULL,6.00,'Pcs',235.00,0.00,1410.00,NULL,NULL,'032'),(71,77,NULL,51.00,'Pcs',235.00,0.00,11985.00,NULL,NULL,'090'),(72,78,NULL,1.00,'Pcs',7520.00,0.00,7520.00,NULL,NULL,'054'),(73,79,NULL,50.00,'Pcs',255.00,0.00,12750.00,NULL,NULL,'092'),(74,80,NULL,1.00,'Pcs',255.00,0.00,255.00,NULL,NULL,'092'),(75,80,NULL,1.00,'Pcs',235.00,0.00,235.00,NULL,NULL,'010'),(76,80,NULL,4.00,'Pcs',150.00,0.00,600.00,NULL,NULL,'010 (P)'),(77,80,NULL,11.00,'Pcs',235.00,0.00,2585.00,NULL,NULL,'012 (I/K)'),(78,81,NULL,16.00,'Pcs',235.00,0.00,3760.00,NULL,NULL,'512'),(79,81,NULL,9.00,'Pcs',275.00,0.00,2475.00,NULL,NULL,'513'),(80,81,NULL,6.00,'Pcs',235.00,0.00,1410.00,NULL,NULL,'012'),(81,81,NULL,6.00,'Pcs',280.00,0.00,1680.00,NULL,NULL,'128'),(82,81,NULL,6.00,'Pcs',235.00,0.00,1410.00,NULL,NULL,'009'),(84,82,NULL,2.00,'Pcs',235.00,0.00,470.00,NULL,NULL,'016'),(85,83,NULL,7.00,'Pcs',235.00,0.00,1645.00,NULL,NULL,'023'),(86,83,NULL,13.00,'Pcs',235.00,0.00,3055.00,NULL,NULL,'69'),(87,83,NULL,300.00,'Pcs',13.00,0.00,3900.00,NULL,NULL,'A4 '),(88,83,NULL,10.00,'Pcs',235.00,0.00,2350.00,NULL,NULL,'92'),(89,83,NULL,60.00,'Pcs',25.00,0.00,1500.00,NULL,NULL,'A4'),(90,84,NULL,20.00,'Pcs',235.00,0.00,4700.00,NULL,NULL,'29 R'),(91,85,NULL,3.00,'Pcs',235.00,0.00,705.00,NULL,NULL,'006'),(92,85,NULL,38.00,'Pcs',235.00,0.00,8930.00,NULL,NULL,'007'),(93,85,NULL,13.00,'Pcs',150.00,0.00,1950.00,NULL,NULL,'102 (P)'),(94,85,NULL,16.00,'Pcs',150.00,0.00,2400.00,NULL,NULL,'012 (P)'),(95,85,NULL,13.00,'Pcs',150.00,0.00,1950.00,NULL,NULL,'007 (P)'),(96,85,NULL,16.00,'Pcs',150.00,0.00,2400.00,NULL,NULL,'023 (P)'),(97,85,NULL,1.00,'Pcs',5220.00,0.00,5220.00,NULL,NULL,'001 (300)'),(98,85,NULL,8.00,'Pcs',235.00,0.00,1880.00,NULL,NULL,'023'),(99,86,NULL,3.00,'Pcs',235.00,0.00,705.00,NULL,NULL,'023'),(100,87,NULL,2.00,'Pcs',235.00,0.00,470.00,NULL,NULL,'074'),(101,87,NULL,52.00,'Pcs',25.00,0.00,1300.00,NULL,NULL,'A4 TRANSPARENT'),(102,88,NULL,1.00,'Pcs',265.00,0.00,265.00,NULL,NULL,''),(103,89,NULL,17.00,'Pcs',235.00,0.00,3995.00,NULL,NULL,'025'),(104,90,NULL,10.00,'Pcs',300.00,0.00,3000.00,NULL,NULL,'W03'),(105,91,NULL,8.00,'Pcs',235.00,0.00,1880.00,NULL,NULL,'062'),(106,91,NULL,2.00,'Pcs',215.00,0.00,430.00,NULL,NULL,'012'),(107,92,74,300.00,'Pcs',16.50,0.00,4950.00,2,NULL,'Conqueror Paper 120GSM'),(108,93,NULL,2.00,'Pcs',380.00,0.00,760.00,NULL,NULL,'');
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order`
--

DROP TABLE IF EXISTS `purchase_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order` (
  `PurchaseOrderID` int NOT NULL AUTO_INCREMENT,
  `PurchaseOrderNo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `PurchaseOrderDate` date NOT NULL,
  `VendorID` int NOT NULL,
  `VendorName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `SubTotal` decimal(18,2) DEFAULT '0.00',
  `DiscountAmount` decimal(18,2) DEFAULT '0.00',
  `GrandTotal` decimal(18,2) DEFAULT '0.00',
  `Status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `Remarks` text COLLATE utf8mb4_general_ci,
  `CreatedDate` datetime DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PurchaseOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order`
--

LOCK TABLES `purchase_order` WRITE;
/*!40000 ALTER TABLE `purchase_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_items` (
  `PurchaseOrderItemID` int NOT NULL AUTO_INCREMENT,
  `PurchaseOrderID` int NOT NULL,
  `ItemID` int NOT NULL,
  `ItemName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  `Qty` decimal(18,2) DEFAULT '1.00',
  `UnitPrice` decimal(18,2) DEFAULT '0.00',
  `TotalAmount` decimal(18,2) DEFAULT '0.00',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PurchaseOrderItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `purchase_return_items`
--

LOCK TABLES `purchase_return_items` WRITE;
/*!40000 ALTER TABLE `purchase_return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_return_items` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `purchase_returns`
--

LOCK TABLES `purchase_returns` WRITE;
/*!40000 ALTER TABLE `purchase_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchases` (
  `PurchaseID` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `VendorID` int NOT NULL,
  `LocationId` int DEFAULT NULL,
  `PurchaseDate` date NOT NULL,
  `PONo` varchar(100) DEFAULT NULL,
  `TotalDiscount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `RefNo` varchar(100) DEFAULT NULL,
  `TotalPurchaseAmount` decimal(10,2) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT NULL,
  `UpdatedAt` datetime DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `Remarks` text,
  PRIMARY KEY (`PurchaseID`),
  KEY `VendorID` (`VendorID`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (1,1,10,NULL,'2026-08-18','',0.00,'',1500.00,'2026-08-12 10:18:25','2026-08-12 21:06:22',0,''),(2,11,9,NULL,'2026-08-18','',0.00,'',4500.00,'2026-08-12 10:20:06','2026-08-12 10:20:06',0,''),(3,11,9,NULL,'2026-08-16','',0.00,'',3000.00,'2026-08-12 10:21:11','2026-08-12 10:21:11',0,''),(4,11,9,NULL,'2026-08-08','',0.00,'',5250.00,'2026-08-12 10:21:53','2026-08-12 10:21:53',0,''),(5,11,16,NULL,'2026-07-09','',0.00,'',2000.00,'2026-08-12 10:23:18','2026-08-12 10:23:18',0,''),(6,11,1,NULL,'2026-08-05','',0.00,'',2150.00,'2026-08-12 10:27:05','2026-08-12 10:27:05',0,''),(7,11,1,NULL,'2026-08-07','',0.00,'',775.00,'2026-08-12 10:29:43','2026-08-12 10:29:43',0,''),(8,11,8,NULL,'2026-08-07','',0.00,'',48000.00,'2026-08-12 10:31:40','2026-08-12 10:31:40',0,''),(9,11,7,NULL,'2026-07-22','',0.00,'',50500.00,'2026-08-12 10:34:26','2026-08-12 10:34:26',0,''),(10,11,1,NULL,'2026-07-14','',0.00,'',3260.00,'2026-08-12 10:35:42','2026-08-12 10:35:42',0,''),(11,11,1,NULL,'2026-07-07','',0.00,'',60430.00,'2026-08-12 10:37:14','2026-08-12 10:37:14',0,''),(12,11,5,NULL,'2026-07-02','',0.00,'',20500.00,'2026-08-12 10:38:07','2026-08-12 10:38:07',0,''),(13,11,2,NULL,'2026-08-04','',0.00,'',11200.00,'2026-08-12 10:39:45','2026-08-12 10:39:45',0,''),(14,11,1,NULL,'2026-07-06','',0.00,'',2000.00,'2026-08-12 10:41:41','2026-08-12 10:41:41',0,''),(15,11,1,NULL,'2026-08-07','',0.00,'',850.00,'2026-08-12 10:42:18','2026-08-12 10:42:18',0,''),(16,11,3,NULL,'2026-07-09','',0.00,'',705.00,'2026-08-12 10:43:18','2026-08-12 10:43:18',1,''),(17,11,3,NULL,'2026-07-14','',0.00,'',1770.00,'2026-08-12 10:44:26','2026-08-12 10:44:26',1,''),(18,11,3,NULL,'2026-07-23','',0.00,'',265.00,'2026-08-12 10:46:10','2026-08-12 10:46:10',1,''),(19,11,3,NULL,'2026-07-28','',0.00,'',3995.00,'2026-08-12 10:47:00','2026-08-12 10:47:00',1,''),(20,11,3,NULL,'2026-08-07','',0.00,'',3000.00,'2026-08-12 10:47:57','2026-08-12 10:47:57',1,''),(21,11,3,NULL,'2026-07-01','',0.00,'',20755.00,'2026-08-12 10:49:27','2026-08-12 10:49:27',1,''),(22,11,3,NULL,'2026-07-02','',0.00,'',1475.00,'2026-08-12 10:51:04','2026-08-12 10:51:04',1,''),(23,11,3,NULL,'2026-07-03','',0.00,'',765.00,'2026-08-12 10:51:38','2026-08-12 10:51:38',1,''),(24,11,3,NULL,'2026-08-12','',0.00,'',44610.00,'2026-08-12 10:54:23','2026-08-12 10:54:23',1,''),(25,11,3,NULL,'2026-07-11','',0.00,'',3675.00,'2026-08-12 10:56:28','2026-08-12 10:56:28',1,''),(26,11,3,NULL,'2026-07-13','',0.00,'',10735.00,'2026-08-12 10:58:10','2026-08-12 10:58:10',1,''),(27,11,3,NULL,'2026-07-11','',0.00,'',470.00,'2026-08-12 10:58:37','2026-08-12 10:58:37',1,''),(28,11,3,NULL,'2026-07-20','',0.00,'',12450.00,'2026-08-12 10:59:19','2026-08-12 10:59:19',1,''),(29,11,3,NULL,'2026-07-21','',0.00,'',4700.00,'2026-08-12 11:00:04','2026-08-12 11:00:04',1,''),(30,11,14,NULL,'2026-07-22','',0.00,'',2610.00,'2026-08-12 11:07:05','2026-08-12 11:07:05',0,''),(31,11,13,NULL,'2026-07-25','',0.00,'',5750.00,'2026-08-12 11:22:53','2026-08-12 11:22:53',0,''),(32,11,11,NULL,'2026-07-20','',0.00,'',2100.00,'2026-08-12 11:23:39','2026-08-12 11:23:39',0,''),(33,11,12,NULL,'2026-07-21','',0.00,'',3000.00,'2026-08-12 11:24:26','2026-08-12 11:24:26',0,''),(34,11,4,NULL,'2026-07-07','',0.00,'',44400.00,'2026-08-12 11:27:54','2026-08-12 11:27:54',0,''),(35,11,4,NULL,'2026-08-10','',0.00,'',1100.00,'2026-08-12 11:28:32','2026-08-12 11:28:32',0,''),(36,11,17,NULL,'2026-07-11','',0.00,'',5950.00,'2026-08-12 11:29:59','2026-08-12 11:29:59',0,''),(37,11,20,NULL,'2026-07-07','',0.00,'',12320.00,'2026-08-12 12:01:38','2026-08-12 12:01:38',0,''),(38,11,16,NULL,'2026-07-24','',0.00,'',13600.00,'2026-08-12 12:02:42','2026-08-12 12:02:42',0,''),(39,11,16,NULL,'2026-08-08','',0.00,'',24450.00,'2026-08-12 12:04:49','2026-08-12 12:04:49',0,''),(40,11,21,NULL,'2026-08-12','',0.00,'',34300.00,'2026-08-12 15:10:48','2026-08-12 15:10:48',0,''),(42,11,22,NULL,'2026-08-11','',0.00,'1483',5550.00,'2026-08-13 10:37:21','2026-08-13 10:37:21',0,''),(43,11,23,NULL,'2026-07-02','',0.00,'PP0067',479636.00,'2026-08-13 11:06:21','2026-08-13 11:06:21',0,''),(44,11,23,NULL,'2026-08-13','',0.00,'PP0088',412616.00,'2026-08-13 11:07:28','2026-08-13 11:07:28',0,''),(45,11,3,NULL,'2026-08-13','',0.00,'2310',4950.00,'2026-08-13 11:13:10','2026-08-14 11:59:32',1,''),(46,11,3,NULL,'2026-08-13','',0.00,'2624',2310.00,'2026-08-13 13:36:07','2026-08-14 11:58:23',1,''),(47,11,19,NULL,'2026-08-14','',0.00,'15406',21440.00,'2026-08-14 10:35:31','2026-08-14 10:35:31',0,''),(48,11,1,NULL,'2026-07-07','',0.00,'',27838.00,'2026-08-14 10:40:53','2026-08-14 10:40:53',0,''),(49,11,1,NULL,'2026-07-09','',0.00,'',38800.00,'2026-08-14 10:44:53','2026-08-14 10:44:53',0,''),(50,11,1,NULL,'2026-07-10','',0.00,'',26910.00,'2026-08-14 10:48:03','2026-08-14 10:48:03',0,''),(51,11,1,NULL,'2026-07-11','',0.00,'',5640.00,'2026-08-14 10:49:55','2026-08-14 10:49:55',0,''),(52,11,1,NULL,'2026-07-17','',0.00,'',9200.00,'2026-08-14 10:51:57','2026-08-14 10:51:57',0,''),(53,11,1,NULL,'2026-07-18','',0.00,'',7682.00,'2026-08-14 11:01:26','2026-08-14 11:01:26',0,''),(54,11,1,NULL,'2026-07-20','',0.00,'',3700.00,'2026-08-14 11:03:42','2026-08-14 11:03:42',0,''),(55,11,1,NULL,'2026-07-20','',0.00,'',6600.00,'2026-08-14 11:06:58','2026-08-14 11:06:58',0,''),(56,11,1,NULL,'2026-07-22','',0.00,'',8200.00,'2026-08-14 11:07:55','2026-08-14 11:07:55',0,''),(57,11,1,NULL,'2026-07-23','',0.00,'',4250.00,'2026-08-14 11:09:09','2026-08-14 11:09:09',0,''),(58,11,1,NULL,'2026-07-24','',0.00,'',8200.00,'2026-08-14 11:10:05','2026-08-14 11:10:05',0,''),(59,11,1,NULL,'2026-07-24','',0.00,'',15320.00,'2026-08-14 11:11:04','2026-08-14 11:11:04',0,''),(60,11,1,NULL,'2026-07-24','',0.00,'',4700.00,'2026-08-14 11:12:15','2026-08-14 11:12:15',0,''),(61,11,1,NULL,'2026-07-30','',0.00,'',14510.00,'2026-08-14 11:14:37','2026-08-14 11:14:37',0,''),(62,11,1,NULL,'2026-07-30','',0.00,'',2200.00,'2026-08-14 11:15:50','2026-08-14 11:15:50',0,''),(63,11,1,NULL,'2026-08-01','',0.00,'',4700.00,'2026-08-14 11:16:58','2026-08-14 11:16:58',0,''),(64,11,1,NULL,'2026-08-06','',0.00,'',3240.00,'2026-08-14 11:17:51','2026-08-14 11:17:51',0,''),(65,11,1,NULL,'2026-08-06','',0.00,'',8200.00,'2026-08-14 11:19:01','2026-08-14 11:19:01',0,''),(66,11,1,NULL,'2026-08-06','',0.00,'',8200.00,'2026-08-14 11:19:01','2026-08-14 11:19:01',1,''),(67,11,8,NULL,'2026-08-13','',0.00,'MMI-3516',7800.00,'2026-08-14 11:24:05','2026-08-14 11:24:05',1,''),(68,11,8,NULL,'2026-08-13','',0.00,'MMI-3516',7800.00,'2026-08-14 12:16:24','2026-08-14 12:19:06',0,''),(69,11,8,NULL,'2026-08-14','',0.00,'MMI-3516',13250.00,'2026-08-14 12:18:49','2026-08-14 12:18:49',0,''),(70,11,3,NULL,'2026-07-01','',0.00,'955',5405.00,'2026-08-14 12:21:46','2026-08-14 12:21:46',0,''),(71,11,3,NULL,'2026-07-01','',0.00,'965',15350.00,'2026-08-14 12:23:32','2026-08-14 12:23:32',0,''),(72,11,3,NULL,'2026-07-02','',0.00,'993',1075.00,'2026-08-14 12:24:44','2026-08-14 12:24:44',0,''),(73,11,3,NULL,'2026-07-02','',0.00,'1110',300.00,'2026-08-14 12:25:11','2026-08-14 12:28:17',0,''),(74,11,3,NULL,'2026-07-03','',0.00,'1148',765.00,'2026-08-14 12:27:49','2026-08-14 12:27:49',0,''),(75,11,3,NULL,'2026-07-07','',0.00,'1307',10945.00,'2026-08-14 12:34:05','2026-08-14 12:34:05',0,''),(76,11,3,NULL,'2026-07-07','',0.00,'1302',1410.00,'2026-08-14 12:35:08','2026-08-14 12:35:08',0,''),(77,11,3,NULL,'2026-07-06','',0.00,'1182',11985.00,'2026-08-14 12:35:58','2026-08-14 12:35:58',0,''),(78,11,3,NULL,'2026-07-05','',0.00,'1153',7520.00,'2026-08-14 12:37:59','2026-08-14 12:37:59',0,''),(79,11,3,NULL,'2026-07-07','',0.00,'1232',12750.00,'2026-08-14 12:38:39','2026-08-14 12:38:39',0,''),(80,11,3,NULL,'2026-07-11','',0.00,'1465',3675.00,'2026-08-14 12:40:31','2026-08-14 12:40:31',0,''),(81,11,3,NULL,'2026-07-13','',0.00,'1492',10735.00,'2026-08-14 12:46:07','2026-08-14 12:46:07',0,''),(82,11,3,NULL,'2026-07-11','',0.00,'1507',470.00,'2026-08-14 12:48:08','2026-08-14 12:48:33',0,''),(83,11,3,NULL,'2026-07-20','',0.00,'1793',12450.00,'2026-08-14 12:59:57','2026-08-14 12:59:57',0,''),(84,11,3,NULL,'2026-07-20','',0.00,'1844',4700.00,'2026-08-14 13:00:47','2026-08-14 13:00:47',0,''),(85,11,3,NULL,'2026-07-23','',0.00,'1926',25435.00,'2026-08-14 13:15:16','2026-08-14 13:15:16',0,''),(86,11,3,NULL,'2026-07-09','',0.00,'1393',705.00,'2026-08-14 13:16:25','2026-08-14 13:16:25',0,''),(87,11,3,NULL,'2026-07-14','',0.00,'1826',1770.00,'2026-08-14 13:17:30','2026-08-14 13:17:30',0,''),(88,11,3,NULL,'2026-07-23','',0.00,'1921',265.00,'2026-08-14 13:18:24','2026-08-14 13:18:24',0,''),(89,11,3,NULL,'2026-07-28','',0.00,'2055',3995.00,'2026-08-14 13:19:03','2026-08-14 13:19:03',0,''),(90,11,3,NULL,'2026-08-07','',0.00,'2225',3000.00,'2026-08-14 13:19:36','2026-08-14 13:19:36',0,''),(91,11,3,NULL,'2026-08-13','',0.00,'2624',2310.00,'2026-08-14 13:20:35','2026-08-14 13:20:35',0,''),(92,11,3,NULL,'2026-08-13','',0.00,'2310',4950.00,'2026-08-14 13:21:24','2026-08-14 13:21:24',0,''),(93,11,3,NULL,'2026-08-14','',0.00,'2372',760.00,'2026-08-14 13:31:27','2026-08-14 13:31:27',0,'');
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation`
--

DROP TABLE IF EXISTS `quotation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotation` (
  `QuotationID` int NOT NULL AUTO_INCREMENT,
  `QuotationNo` varchar(50) NOT NULL,
  `QuotationDate` date NOT NULL,
  `CustomerID` int NOT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `SubTotal` decimal(18,2) NOT NULL DEFAULT '0.00',
  `DiscountAmount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `TaxAmount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `GrandTotal` decimal(18,2) NOT NULL DEFAULT '0.00',
  `Status` varchar(50) NOT NULL DEFAULT 'Pending',
  `Remarks` text,
  `CreatedBy` varchar(100) DEFAULT NULL,
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(100) DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `DomainID` int DEFAULT NULL,
  PRIMARY KEY (`QuotationID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation`
--

LOCK TABLES `quotation` WRITE;
/*!40000 ALTER TABLE `quotation` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation_items`
--

DROP TABLE IF EXISTS `quotation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotation_items` (
  `QuotationItemID` int NOT NULL AUTO_INCREMENT,
  `QuotationID` int NOT NULL,
  `ItemID` int NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Description` text,
  `Qty` decimal(18,2) NOT NULL DEFAULT '1.00',
  `UnitPrice` decimal(18,2) NOT NULL DEFAULT '0.00',
  `TotalAmount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`QuotationItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation_items`
--

LOCK TABLES `quotation_items` WRITE;
/*!40000 ALTER TABLE `quotation_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `received_cheques`
--

DROP TABLE IF EXISTS `received_cheques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `received_cheques` (
  `ReceivedChequeID` int NOT NULL AUTO_INCREMENT,
  `CustomerID` int DEFAULT NULL,
  `BankID` int DEFAULT NULL,
  `ChequeNo` varchar(100) DEFAULT NULL,
  `ChequeDate` date DEFAULT NULL,
  `DepositDate` date DEFAULT NULL,
  `CreditedBankID` int DEFAULT NULL,
  `ClearedDate` date DEFAULT NULL,
  `Amount` decimal(15,2) DEFAULT NULL,
  `Status` enum('Pending','Deposited','Cleared','Bounced') DEFAULT 'Pending',
  `Notes` text,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReceivedChequeID`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `received_cheques`
--

LOCK TABLES `received_cheques` WRITE;
/*!40000 ALTER TABLE `received_cheques` DISABLE KEYS */;
INSERT INTO `received_cheques` VALUES (1,45,0,'','0000-00-00','2026-08-11',NULL,NULL,350000.00,'Pending','Customer Payment Cheque','2026-08-11 09:23:30'),(2,5,0,'','0000-00-00','2026-08-11',NULL,NULL,8390.00,'Pending','Customer Payment Cheque','2026-08-11 10:29:19'),(3,5,0,'','0000-00-00','2026-08-11',NULL,NULL,13230.00,'Pending','Customer Payment Cheque','2026-08-11 10:29:19'),(4,5,0,'','0000-00-00','2026-08-11',NULL,NULL,23310.00,'Pending','Customer Payment Cheque','2026-08-11 10:29:19'),(5,5,0,'','0000-00-00','2026-08-11',NULL,NULL,3180.00,'Pending','Customer Payment Cheque','2026-08-11 10:29:19'),(6,5,0,'','0000-00-00','2026-08-11',NULL,NULL,3300.00,'Pending','Customer Payment Cheque','2026-08-11 10:29:19'),(7,5,0,'','0000-00-00','2026-08-11',NULL,NULL,300.00,'Pending','Customer Payment Cheque','2026-08-11 10:29:19'),(8,5,0,'','0000-00-00','2026-08-11',NULL,NULL,11130.00,'Pending','Customer Payment Cheque','2026-08-11 10:29:19'),(9,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,21835.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(10,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,22500.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(11,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,16505.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(12,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,26990.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(13,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,31490.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(14,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,17535.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(15,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,10565.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(16,1,0,'123993','2026-08-20','2026-08-12',NULL,NULL,2580.00,'Pending','Customer Payment Cheque','2026-08-12 08:12:54'),(17,17,0,'','0000-00-00','2026-08-13',NULL,NULL,2440.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(18,17,0,'','0000-00-00','2026-08-13',NULL,NULL,2800.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(19,17,0,'','0000-00-00','2026-08-13',NULL,NULL,6680.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(20,17,0,'','0000-00-00','2026-08-13',NULL,NULL,2000.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(21,17,0,'','0000-00-00','2026-08-13',NULL,NULL,4020.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(22,17,0,'','0000-00-00','2026-08-13',NULL,NULL,10680.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(23,17,0,'','0000-00-00','2026-08-13',NULL,NULL,3000.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(24,17,0,'','0000-00-00','2026-08-13',NULL,NULL,280.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(25,17,0,'','0000-00-00','2026-08-13',NULL,NULL,900.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:26'),(26,17,0,'','0000-00-00','2026-08-13',NULL,NULL,900.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:27'),(27,17,0,'','0000-00-00','2026-08-13',NULL,NULL,31250.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:27'),(28,17,0,'','0000-00-00','2026-08-13',NULL,NULL,1200.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:27'),(29,17,0,'','0000-00-00','2026-08-13',NULL,NULL,1540.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:27'),(30,17,0,'','0000-00-00','2026-08-13',NULL,NULL,200.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:27'),(31,17,0,'','0000-00-00','2026-08-13',NULL,NULL,1800.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:27'),(32,17,0,'','0000-00-00','2026-08-13',NULL,NULL,800.00,'Pending','Customer Payment Cheque','2026-08-13 04:59:27'),(33,45,0,'','0000-00-00','2026-08-13',NULL,NULL,404000.00,'Pending','Customer Payment Cheque','2026-08-13 09:09:07'),(34,45,0,'','0000-00-00','2026-08-13',NULL,NULL,24700.00,'Pending','Customer Payment Cheque','2026-08-13 09:09:07');
/*!40000 ALTER TABLE `received_cheques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reload`
--

DROP TABLE IF EXISTS `reload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reload` (
  `ReloadId` int NOT NULL AUTO_INCREMENT,
  `Vc` varchar(50) NOT NULL,
  `Provider` varchar(50) NOT NULL,
  `Package` decimal(10,2) DEFAULT NULL,
  `PackageD` varchar(255) DEFAULT NULL,
  `AddOn` decimal(10,2) DEFAULT NULL,
  `AddOnd` varchar(255) DEFAULT NULL,
  `ExtraCharge` decimal(10,2) DEFAULT NULL,
  `ExtraCharged` varchar(255) DEFAULT NULL,
  `ExpiryON` date NOT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `PayStatus` enum('Paid','Unpaid') DEFAULT NULL,
  `Remark` text,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `isExpiryMsg` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`ReloadId`),
  KEY `Vc` (`Vc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reload`
--

LOCK TABLES `reload` WRITE;
/*!40000 ALTER TABLE `reload` DISABLE KEYS */;
/*!40000 ALTER TABLE `reload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` int NOT NULL,
  `permission` text NOT NULL,
  `IsDeleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1,'users_list',0),(2,1,'users_add',0),(3,1,'users_edit',0),(4,1,'users_delete',0),(5,1,'users_view',0),(7,1,'roles_list',0),(8,1,'roles_add',0),(9,1,'roles_edit',0),(10,1,'permissions_list',0),(11,1,'permissions_add',0),(12,1,'permissions_edit',0),(76,1,'category_list',0),(77,1,'category_add',0),(78,1,'category_edit',0),(79,1,'category_delete',0),(80,1,'brand_list',0),(81,1,'brand_add',0),(82,1,'brand_edit',0),(83,1,'brand_delete',0),(84,1,'item_list',0),(85,1,'item_add',0),(86,1,'item_edit',0),(87,1,'item_delete',0),(88,1,'customer_list',0),(89,1,'customer_add',0),(90,1,'customer_edit',0),(91,1,'customer_delete',0),(92,1,'vendor_list',0),(93,1,'vendor_add',0),(94,1,'vendor_edit',0),(95,1,'vendor_delete',0),(96,1,'purchases_list',0),(97,1,'purchases_add',0),(98,1,'purchases_edit',0),(99,1,'purchases_delete',0),(100,1,'vendor_payment_list',0),(101,1,'vendor_payment_add',0),(102,1,'vendor_payment_edit',0),(103,1,'vendor_payment_delete',0),(104,1,'sales_list',0),(105,1,'sales_add',0),(106,1,'sales_edit',0),(107,1,'sales_delete',0),(108,1,'customer_payment_list',0),(109,1,'customer_payment_add',0),(110,1,'customer_payment_edit',0),(111,1,'customer_payment_delete',0),(112,1,'stock_report',0),(113,1,'customer_balance_sheet_report',0),(114,1,'vendor_balance_sheet_report',0),(115,2,'item_list',0),(116,2,'item_add',0),(117,2,'customer_list',0),(118,2,'customer_add',0),(119,2,'vendor_list',0),(120,2,'vendor_add',0),(121,2,'purchases_list',0),(122,2,'purchases_add',0),(123,2,'vendor_payment_add',0),(124,2,'sales_list',0),(125,2,'sales_add',0),(126,2,'customer_balance_sheet_report',0),(127,2,'vendor_balance_sheet_report',0),(128,3,'category_list',0),(129,3,'brand_list',0),(130,3,'item_list',0),(131,3,'customer_list',0),(132,3,'customer_add',0),(133,3,'vendor_list',0),(134,3,'vendor_add',0),(135,3,'purchases_add',0),(136,3,'purchases_edit',0),(137,3,'vendor_payment_list',0),(138,3,'vendor_payment_add',0),(139,3,'sales_add',0),(140,3,'customer_payment_add',0),(141,3,'stock_report',0),(142,3,'customer_balance_sheet_report',0),(143,3,'vendor_balance_sheet_report',0),(144,2,'category_list',0),(145,2,'brand_list',0),(146,4,'sales_add',0),(147,4,'sales_edit',0);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `IsDeleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin',0),(2,'SALES',0),(3,'ACCOUNTS',0),(4,'Sales Role',0);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_slips`
--

DROP TABLE IF EXISTS `salary_slips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_slips` (
  `SlipID` int NOT NULL AUTO_INCREMENT,
  `EmployeeID` int NOT NULL,
  `PayrollMonth` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BasicSalary` decimal(18,2) NOT NULL,
  `Allowance` decimal(18,2) NOT NULL,
  `AllowancesJSON` text COLLATE utf8mb4_unicode_ci,
  `OTHours` decimal(10,2) DEFAULT '0.00',
  `OTRate` decimal(10,2) DEFAULT '0.00',
  `OTAmount` decimal(18,2) DEFAULT '0.00',
  `GrossSalary` decimal(18,2) NOT NULL,
  `EmployeeEPF` decimal(18,2) NOT NULL,
  `AdvanceDeduction` decimal(18,2) DEFAULT '0.00',
  `NetSalary` decimal(18,2) NOT NULL,
  `CompanyEPF` decimal(18,2) NOT NULL,
  `CompanyETF` decimal(18,2) NOT NULL,
  `PaymentMethod` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BankID` int DEFAULT NULL,
  `PaymentDate` date NOT NULL,
  `JournalID` int DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SlipID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_slips`
--

LOCK TABLES `salary_slips` WRITE;
/*!40000 ALTER TABLE `salary_slips` DISABLE KEYS */;
INSERT INTO `salary_slips` VALUES (1,2,'2026-07',35000.00,0.00,NULL,0.00,0.00,0.00,35000.00,2800.00,0.00,32200.00,4200.00,1050.00,'Cash',NULL,'2026-07-31',1,1,'2026-08-03 13:21:31'),(2,2,'2026-07',35000.00,0.00,NULL,0.00,0.00,0.00,35000.00,2800.00,0.00,32200.00,4200.00,1050.00,'Cash',NULL,'2026-07-31',2,1,'2026-08-03 13:41:10');
/*!40000 ALTER TABLE `salary_slips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_items` (
  `SaleItemID` int NOT NULL AUTO_INCREMENT,
  `SaleID` int NOT NULL,
  `ItemID` int DEFAULT NULL,
  `Description` text,
  `Quantity` decimal(10,2) DEFAULT NULL,
  `QtyType` varchar(50) DEFAULT NULL,
  `SalePrice` decimal(10,2) NOT NULL,
  `DiscountPercent` decimal(5,2) DEFAULT '0.00',
  `DiscountAmount` decimal(10,2) DEFAULT '0.00',
  `Discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalAmount` decimal(10,2) NOT NULL,
  `CategoryID` int DEFAULT NULL,
  `BrandID` int DEFAULT NULL,
  PRIMARY KEY (`SaleItemID`),
  KEY `SaleID` (`SaleID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=526 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (3,1,NULL,'print',51.00,'Sheets',30.00,0.00,0.00,0.00,1530.00,NULL,NULL),(4,1,NULL,'print',24.00,'Sheets',30.00,0.00,0.00,0.00,720.00,NULL,NULL),(5,2,NULL,'print',1.00,'Pcs',21835.00,0.00,0.00,0.00,21835.00,NULL,NULL),(8,3,NULL,'print',1.00,'Pcs',9770.00,0.00,0.00,0.00,9770.00,NULL,NULL),(9,4,NULL,'print',1.00,'Pcs',4890.00,0.00,0.00,0.00,4890.00,NULL,NULL),(11,6,NULL,'',1.00,'Pcs',19690.00,0.00,0.00,0.00,19690.00,NULL,NULL),(12,5,NULL,'print',1.00,'Pcs',9940.00,0.00,0.00,0.00,9940.00,NULL,NULL),(13,7,NULL,'print',1.00,'Pcs',9420.00,0.00,0.00,0.00,9420.00,NULL,NULL),(14,8,NULL,'print',1.00,'Pcs',18560.00,0.00,0.00,0.00,18560.00,NULL,NULL),(15,9,NULL,'print',1.00,'Pcs',5945.00,0.00,0.00,0.00,5945.00,NULL,NULL),(16,10,NULL,'print',1.00,'Pcs',3640.00,0.00,0.00,0.00,3640.00,NULL,NULL),(17,11,NULL,'print',1.00,'Pcs',2250.00,0.00,0.00,0.00,2250.00,NULL,NULL),(18,12,NULL,'print',1.00,'Pcs',2250.00,0.00,0.00,0.00,2250.00,NULL,NULL),(19,13,NULL,'print',1.00,'Pcs',4375.00,0.00,0.00,0.00,4375.00,NULL,NULL),(20,14,NULL,'print',1.00,'Pcs',9175.00,0.00,0.00,0.00,9175.00,NULL,NULL),(21,15,NULL,'print',1.00,'Pcs',13230.00,0.00,0.00,0.00,13230.00,NULL,NULL),(23,16,NULL,'print',1.00,'Sheets',11345.00,0.00,0.00,0.00,11345.00,NULL,NULL),(24,17,NULL,'print',1.00,'Pcs',8680.00,0.00,0.00,0.00,8680.00,NULL,NULL),(25,18,NULL,'print',1.00,'Pcs',22500.00,0.00,0.00,0.00,22500.00,NULL,NULL),(26,19,NULL,'print',1.00,'Pcs',16505.00,0.00,0.00,0.00,16505.00,NULL,NULL),(27,20,NULL,'print',1.00,'Pcs',5810.00,0.00,0.00,0.00,5810.00,NULL,NULL),(28,21,NULL,'print',1.00,'Pcs',25230.00,0.00,0.00,0.00,25230.00,NULL,NULL),(29,22,NULL,'print',1.00,'Pcs',23310.00,0.00,0.00,0.00,23310.00,NULL,NULL),(30,23,NULL,'',1.00,'Pcs',17955.00,0.00,0.00,0.00,17955.00,NULL,NULL),(31,24,NULL,'print',1.00,'Pcs',11730.00,0.00,0.00,0.00,11730.00,NULL,NULL),(33,26,NULL,'print',1.00,'Pcs',18540.00,0.00,0.00,0.00,18540.00,NULL,NULL),(34,27,NULL,'print',1.00,'Pcs',11460.00,0.00,0.00,0.00,11460.00,NULL,NULL),(35,28,NULL,'print',1.00,'Pcs',1350.00,0.00,0.00,0.00,1350.00,NULL,NULL),(36,29,NULL,'print',1.00,'Pcs',2650.00,0.00,0.00,0.00,2650.00,NULL,NULL),(37,30,NULL,'print',1.00,'Pcs',1590.00,0.00,0.00,0.00,1590.00,NULL,NULL),(38,31,NULL,'print',1.00,'Pcs',36885.00,0.00,0.00,0.00,36885.00,NULL,NULL),(40,33,NULL,'',1.00,'Pcs',26990.00,0.00,0.00,0.00,26990.00,NULL,NULL),(41,34,NULL,'print',1.00,'Pcs',5880.00,0.00,0.00,0.00,5880.00,NULL,NULL),(42,35,NULL,'print',1.00,'Pcs',6645.00,0.00,0.00,0.00,6645.00,NULL,NULL),(43,36,NULL,'print',1.00,'Pcs',1840.00,0.00,0.00,0.00,1840.00,NULL,NULL),(44,37,NULL,'print',1.00,'Pcs',1350.00,0.00,0.00,0.00,1350.00,NULL,NULL),(45,38,NULL,'print',1.00,'Pcs',5770.00,0.00,0.00,0.00,5770.00,NULL,NULL),(46,39,NULL,'print',1.00,'Pcs',11950.00,0.00,0.00,0.00,11950.00,NULL,NULL),(47,40,NULL,'print',1.00,'Pcs',7220.00,0.00,0.00,0.00,7220.00,NULL,NULL),(48,41,NULL,'print',1.00,'Pcs',12950.00,0.00,0.00,0.00,12950.00,NULL,NULL),(49,42,NULL,'print',1.00,'Pcs',31490.00,0.00,0.00,0.00,31490.00,NULL,NULL),(50,43,NULL,'print',1.00,'Pcs',3180.00,0.00,0.00,0.00,3180.00,NULL,NULL),(51,44,NULL,'print',1.00,'Pcs',14600.00,0.00,0.00,0.00,14600.00,NULL,NULL),(52,45,NULL,'print',1.00,'Pcs',2150.00,0.00,0.00,0.00,2150.00,NULL,NULL),(53,46,NULL,'print',1.00,'Pcs',12180.00,0.00,0.00,0.00,12180.00,NULL,NULL),(54,47,NULL,'print',1.00,'Pcs',17605.00,0.00,0.00,0.00,17605.00,NULL,NULL),(55,48,NULL,'print',1.00,'Pcs',23280.00,0.00,0.00,0.00,23280.00,NULL,NULL),(56,49,NULL,'print',1.00,'Pcs',1560.00,0.00,0.00,0.00,1560.00,NULL,NULL),(57,50,NULL,'print',1.00,'Pcs',8730.00,0.00,0.00,0.00,8730.00,NULL,NULL),(58,51,NULL,'print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(59,52,NULL,'print',1.00,'Pcs',12120.00,0.00,0.00,0.00,12120.00,NULL,NULL),(60,53,NULL,'print',1.00,'Pcs',4435.00,0.00,0.00,0.00,4435.00,NULL,NULL),(61,54,NULL,'print',1.00,'Pcs',7760.00,0.00,0.00,0.00,7760.00,NULL,NULL),(62,55,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(63,56,NULL,'print',1.00,'Pcs',960.00,0.00,0.00,0.00,960.00,NULL,NULL),(64,57,NULL,'print',1.00,'Pcs',520.00,0.00,0.00,0.00,520.00,NULL,NULL),(65,58,NULL,'print',1.00,'Pcs',2960.00,0.00,0.00,0.00,2960.00,NULL,NULL),(66,59,NULL,'print',1.00,'Pcs',4810.00,0.00,0.00,0.00,4810.00,NULL,NULL),(67,60,NULL,'print',1.00,'Pcs',10860.00,0.00,0.00,0.00,10860.00,NULL,NULL),(69,62,NULL,'print',1.00,'Pcs',6000.00,0.00,0.00,0.00,6000.00,NULL,NULL),(70,63,NULL,'print',1.00,'Pcs',17535.00,0.00,0.00,0.00,17535.00,NULL,NULL),(71,64,NULL,'print',1.00,'Pcs',520.00,0.00,0.00,0.00,520.00,NULL,NULL),(72,65,NULL,'print',1.00,'Pcs',520.00,0.00,0.00,0.00,520.00,NULL,NULL),(74,66,NULL,'print',1.00,'Pcs',2560.00,0.00,0.00,0.00,2560.00,NULL,NULL),(75,67,NULL,'print',1.00,'Pcs',10810.00,0.00,0.00,0.00,10810.00,NULL,NULL),(76,68,NULL,'print',1.00,'Pcs',2520.00,0.00,0.00,0.00,2520.00,NULL,NULL),(77,69,NULL,'print',1.00,'Pcs',3300.00,0.00,0.00,0.00,3300.00,NULL,NULL),(78,70,NULL,'print',1.00,'Pcs',8830.00,0.00,0.00,0.00,8830.00,NULL,NULL),(80,72,NULL,'print',1.00,'Pcs',25350.00,0.00,0.00,0.00,25350.00,NULL,NULL),(81,73,NULL,'print',1.00,'Pcs',100.00,0.00,0.00,0.00,100.00,NULL,NULL),(82,74,NULL,'print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(83,75,NULL,'print',1.00,'Pcs',210.00,0.00,0.00,0.00,210.00,NULL,NULL),(84,76,NULL,'print',1.00,'Pcs',7260.00,0.00,0.00,0.00,7260.00,NULL,NULL),(85,77,NULL,'print',1.00,'Pcs',1480.00,0.00,0.00,0.00,1480.00,NULL,NULL),(86,78,NULL,'print',1.00,'Pcs',1900.00,0.00,0.00,0.00,1900.00,NULL,NULL),(87,79,NULL,'print',1.00,'Pcs',5000.00,0.00,0.00,0.00,5000.00,NULL,NULL),(88,80,NULL,'print',1.00,'Pcs',1000.00,0.00,0.00,0.00,1000.00,NULL,NULL),(89,81,NULL,'print',1.00,'Pcs',780.00,0.00,0.00,0.00,780.00,NULL,NULL),(90,82,NULL,'print',1.00,'Pcs',980.00,0.00,0.00,0.00,980.00,NULL,NULL),(91,83,NULL,'print',1.00,'Pcs',1000.00,0.00,0.00,0.00,1000.00,NULL,NULL),(92,84,NULL,'print',1.00,'Pcs',840.00,0.00,0.00,0.00,840.00,NULL,NULL),(93,85,NULL,'print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(94,86,NULL,'print',1.00,'Pcs',4800.00,0.00,0.00,0.00,4800.00,NULL,NULL),(95,87,NULL,'print',1.00,'Pcs',2500.00,0.00,0.00,0.00,2500.00,NULL,NULL),(97,88,NULL,'print',1.00,'Pcs',3550.00,0.00,0.00,0.00,3550.00,NULL,NULL),(98,89,NULL,'print',1.00,'Pcs',500.00,0.00,0.00,0.00,500.00,NULL,NULL),(99,90,NULL,'print',1.00,'Pcs',8910.00,0.00,0.00,0.00,8910.00,NULL,NULL),(100,91,NULL,'print',1.00,'Pcs',4000.00,0.00,0.00,0.00,4000.00,NULL,NULL),(101,92,NULL,'print',1.00,'Pcs',10500.00,0.00,0.00,0.00,10500.00,NULL,NULL),(102,93,NULL,'print',1.00,'Pcs',5800.00,0.00,0.00,0.00,5800.00,NULL,NULL),(103,94,NULL,'print',1.00,'Pcs',1000.00,0.00,0.00,0.00,1000.00,NULL,NULL),(104,95,NULL,'print',1.00,'Pcs',320.00,0.00,0.00,0.00,320.00,NULL,NULL),(105,96,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(106,97,NULL,'print',1.00,'Pcs',600.00,0.00,0.00,0.00,600.00,NULL,NULL),(107,98,NULL,'print',1.00,'Pcs',2535.00,0.00,0.00,0.00,2535.00,NULL,NULL),(108,99,NULL,'print',1.00,'Pcs',900.00,0.00,0.00,0.00,900.00,NULL,NULL),(109,100,NULL,'print',1.00,'Pcs',200.00,0.00,0.00,0.00,200.00,NULL,NULL),(110,101,NULL,'print',1.00,'Pcs',750.00,0.00,0.00,0.00,750.00,NULL,NULL),(111,102,NULL,'print',1.00,'Pcs',7500.00,0.00,0.00,0.00,7500.00,NULL,NULL),(112,103,NULL,'print',1.00,'Pcs',7500.00,0.00,0.00,0.00,7500.00,NULL,NULL),(113,104,NULL,'print',1.00,'Pcs',750.00,0.00,0.00,0.00,750.00,NULL,NULL),(114,105,NULL,'print',1.00,'Pcs',3600.00,0.00,0.00,0.00,3600.00,NULL,NULL),(115,106,NULL,'print',1.00,'Pcs',800.00,0.00,0.00,0.00,800.00,NULL,NULL),(116,107,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(117,108,NULL,'print',1.00,'Pcs',1200.00,0.00,0.00,0.00,1200.00,NULL,NULL),(118,109,NULL,'print',1.00,'Pcs',1300.00,0.00,0.00,0.00,1300.00,NULL,NULL),(119,110,NULL,'print',1.00,'Pcs',3500.00,0.00,0.00,0.00,3500.00,NULL,NULL),(120,111,NULL,'print',1.00,'Pcs',400.00,0.00,0.00,0.00,400.00,NULL,NULL),(121,112,NULL,'print',1.00,'Pcs',5880.00,0.00,0.00,0.00,5880.00,NULL,NULL),(122,113,NULL,'print',1.00,'Pcs',600.00,0.00,0.00,0.00,600.00,NULL,NULL),(123,114,NULL,'print',1.00,'Pcs',800.00,0.00,0.00,0.00,800.00,NULL,NULL),(124,115,NULL,'print',1.00,'Pcs',1840.00,0.00,0.00,0.00,1840.00,NULL,NULL),(125,116,NULL,'print',1.00,'Pcs',4200.00,0.00,0.00,0.00,4200.00,NULL,NULL),(126,117,NULL,'print',1.00,'Pcs',3500.00,0.00,0.00,0.00,3500.00,NULL,NULL),(127,118,NULL,'print',1.00,'Pcs',4020.00,0.00,0.00,0.00,4020.00,NULL,NULL),(128,119,NULL,'print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(129,120,NULL,'print',1.00,'Pcs',1170.00,0.00,0.00,0.00,1170.00,NULL,NULL),(130,121,NULL,'print',1.00,'Pcs',2170.00,0.00,0.00,0.00,2170.00,NULL,NULL),(131,122,NULL,'print',1.00,'Pcs',5050.00,0.00,0.00,0.00,5050.00,NULL,NULL),(132,123,NULL,'print',1.00,'Pcs',6600.00,0.00,0.00,0.00,6600.00,NULL,NULL),(133,124,NULL,'print',1.00,'Pcs',1960.00,0.00,0.00,0.00,1960.00,NULL,NULL),(135,126,NULL,'print',1.00,'Pcs',500.00,0.00,0.00,0.00,500.00,NULL,NULL),(136,127,NULL,'print',1.00,'Pcs',1320.00,0.00,0.00,0.00,1320.00,NULL,NULL),(137,128,NULL,'print',1.00,'Pcs',900.00,0.00,0.00,0.00,900.00,NULL,NULL),(138,129,NULL,'print',1.00,'Pcs',19800.00,0.00,0.00,0.00,19800.00,NULL,NULL),(140,130,NULL,'print',1.00,'Pcs',17760.00,0.00,0.00,0.00,17760.00,NULL,NULL),(141,131,NULL,'print',1.00,'Pcs',2520.00,0.00,0.00,0.00,2520.00,NULL,NULL),(142,132,NULL,'print',1.00,'Pcs',760.00,0.00,0.00,0.00,760.00,NULL,NULL),(143,133,NULL,'print',1.00,'Pcs',7375.00,0.00,0.00,0.00,7375.00,NULL,NULL),(144,134,NULL,'print',1.00,'Pcs',4000.00,0.00,0.00,0.00,4000.00,NULL,NULL),(145,135,NULL,'print',1.00,'Pcs',4000.00,0.00,0.00,0.00,4000.00,NULL,NULL),(146,136,NULL,'print',1.00,'Pcs',4000.00,0.00,0.00,0.00,4000.00,NULL,NULL),(147,137,NULL,'print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(148,138,NULL,'print',1.00,'Pcs',1040.00,0.00,0.00,0.00,1040.00,NULL,NULL),(149,139,NULL,'print',1.00,'Pcs',1040.00,0.00,0.00,0.00,1040.00,NULL,NULL),(150,140,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(151,141,NULL,'print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(152,142,NULL,'print',1.00,'Pcs',320.00,0.00,0.00,0.00,320.00,NULL,NULL),(153,143,NULL,'print',1.00,'Pcs',120.00,0.00,0.00,0.00,120.00,NULL,NULL),(154,144,NULL,'print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(155,145,NULL,'print',1.00,'Pcs',1200.00,0.00,0.00,0.00,1200.00,NULL,NULL),(156,146,NULL,'print',1.00,'Pcs',500.00,0.00,0.00,0.00,500.00,NULL,NULL),(157,147,NULL,'print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(158,148,NULL,'print',1.00,'Pcs',400.00,0.00,0.00,0.00,400.00,NULL,NULL),(159,149,NULL,'print',1.00,'Pcs',100.00,0.00,0.00,0.00,100.00,NULL,NULL),(160,150,NULL,'print',1.00,'Pcs',480.00,0.00,0.00,0.00,480.00,NULL,NULL),(161,151,NULL,'print',1.00,'Pcs',3360.00,0.00,0.00,0.00,3360.00,NULL,NULL),(162,152,NULL,'print',1.00,'Pcs',3360.00,0.00,0.00,0.00,3360.00,NULL,NULL),(163,153,NULL,'print',1.00,'Pcs',1400.00,0.00,0.00,0.00,1400.00,NULL,NULL),(164,154,NULL,'print',1.00,'Pcs',6400.00,0.00,0.00,0.00,6400.00,NULL,NULL),(165,155,NULL,'print',1.00,'Pcs',760.00,0.00,0.00,0.00,760.00,NULL,NULL),(166,156,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(167,157,NULL,'print',1.00,'Pcs',24000.00,0.00,0.00,0.00,24000.00,NULL,NULL),(168,158,NULL,'print',1.00,'Pcs',24000.00,0.00,0.00,0.00,24000.00,NULL,NULL),(169,159,NULL,'print',1.00,'Pcs',12000.00,0.00,0.00,0.00,12000.00,NULL,NULL),(170,160,NULL,'print',1.00,'Pcs',2100.00,0.00,0.00,0.00,2100.00,NULL,NULL),(171,161,NULL,'print',1.00,'Pcs',2920.00,0.00,0.00,0.00,2920.00,NULL,NULL),(172,162,NULL,'print',1.00,'Pcs',150.00,0.00,0.00,0.00,150.00,NULL,NULL),(173,163,NULL,'print',1.00,'Pcs',700.00,0.00,0.00,0.00,700.00,NULL,NULL),(174,164,NULL,'print',1.00,'Pcs',1200.00,0.00,0.00,0.00,1200.00,NULL,NULL),(175,165,NULL,'print',1.00,'Pcs',3280.00,0.00,0.00,0.00,3280.00,NULL,NULL),(176,166,NULL,'print',1.00,'Pcs',7200.00,0.00,0.00,0.00,7200.00,NULL,NULL),(177,167,NULL,'print',1.00,'Pcs',32000.00,0.00,0.00,0.00,32000.00,NULL,NULL),(178,168,NULL,'print',1.00,'Pcs',28000.00,0.00,0.00,0.00,28000.00,NULL,NULL),(179,169,NULL,'print',1.00,'Pcs',1100.00,0.00,0.00,0.00,1100.00,NULL,NULL),(180,170,NULL,'print',1.00,'Pcs',3870.00,0.00,0.00,0.00,3870.00,NULL,NULL),(181,171,NULL,'print',1.00,'Pcs',9810.00,0.00,0.00,0.00,9810.00,NULL,NULL),(182,172,NULL,'print',1.00,'Pcs',1400.00,0.00,0.00,0.00,1400.00,NULL,NULL),(183,173,NULL,'print',1.00,'Pcs',400.00,0.00,0.00,0.00,400.00,NULL,NULL),(184,174,NULL,'print',1.00,'Pcs',2560.00,0.00,0.00,0.00,2560.00,NULL,NULL),(185,175,NULL,'print',1.00,'Pcs',750.00,0.00,0.00,0.00,750.00,NULL,NULL),(186,176,NULL,'print',1.00,'Pcs',11970.00,0.00,0.00,0.00,11970.00,NULL,NULL),(187,177,NULL,'print',1.00,'Pcs',150.00,0.00,0.00,0.00,150.00,NULL,NULL),(188,178,NULL,'print',1.00,'Pcs',1900.00,0.00,0.00,0.00,1900.00,NULL,NULL),(189,179,NULL,'print',1.00,'Pcs',4020.00,0.00,0.00,0.00,4020.00,NULL,NULL),(190,180,NULL,'print',1.00,'Pcs',3240.00,0.00,0.00,0.00,3240.00,NULL,NULL),(191,181,NULL,'print',1.00,'Pcs',8400.00,0.00,0.00,0.00,8400.00,NULL,NULL),(192,182,NULL,'print',1.00,'Pcs',10020.00,0.00,0.00,0.00,10020.00,NULL,NULL),(193,183,NULL,'print',1.00,'Pcs',2300.00,0.00,0.00,0.00,2300.00,NULL,NULL),(194,184,NULL,'print',1.00,'Pcs',5000.00,0.00,0.00,0.00,5000.00,NULL,NULL),(195,185,NULL,'print',1.00,'Pcs',3600.00,0.00,0.00,0.00,3600.00,NULL,NULL),(196,186,NULL,'print',1.00,'Pcs',4500.00,0.00,0.00,0.00,4500.00,NULL,NULL),(197,187,NULL,'print',1.00,'Pcs',8750.00,0.00,0.00,0.00,8750.00,NULL,NULL),(198,188,NULL,'print',1.00,'Pcs',17500.00,0.00,0.00,0.00,17500.00,NULL,NULL),(199,189,NULL,'print',1.00,'Pcs',2160.00,0.00,0.00,0.00,2160.00,NULL,NULL),(200,190,NULL,'print',1.00,'Pcs',320.00,0.00,0.00,0.00,320.00,NULL,NULL),(201,191,NULL,'print',1.00,'Pcs',800.00,0.00,0.00,0.00,800.00,NULL,NULL),(202,192,NULL,'print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(203,193,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(204,194,NULL,'print',1.00,'Pcs',1320.00,0.00,0.00,0.00,1320.00,NULL,NULL),(205,195,NULL,'print',1.00,'Pcs',28160.00,0.00,0.00,0.00,28160.00,NULL,NULL),(206,196,NULL,'print',1.00,'Pcs',1040.00,0.00,0.00,0.00,1040.00,NULL,NULL),(207,197,NULL,'print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(208,198,NULL,'print',1.00,'Pcs',500.00,0.00,0.00,0.00,500.00,NULL,NULL),(209,199,NULL,'print',1.00,'Pcs',2200.00,0.00,0.00,0.00,2200.00,NULL,NULL),(210,200,NULL,'print',1.00,'Pcs',2380.00,0.00,0.00,0.00,2380.00,NULL,NULL),(212,201,NULL,'print',1.00,'Pcs',1000.00,0.00,0.00,0.00,1000.00,NULL,NULL),(213,202,NULL,'print',1.00,'Pcs',3600.00,0.00,0.00,0.00,3600.00,NULL,NULL),(214,203,NULL,'print',1.00,'Pcs',4200.00,0.00,0.00,0.00,4200.00,NULL,NULL),(215,204,NULL,'print',1.00,'Pcs',2400.00,0.00,0.00,0.00,2400.00,NULL,NULL),(216,205,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(218,207,NULL,'print',1.00,'Pcs',2700.00,0.00,0.00,0.00,2700.00,NULL,NULL),(219,206,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(220,208,NULL,'print',1.00,'Pcs',3800.00,0.00,0.00,0.00,3800.00,NULL,NULL),(221,209,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(222,210,NULL,'print',1.00,'Pcs',3500.00,0.00,0.00,0.00,3500.00,NULL,NULL),(223,211,NULL,'print',1.00,'Pcs',3050.00,0.00,0.00,0.00,3050.00,NULL,NULL),(224,212,NULL,'print',1.00,'Pcs',900.00,0.00,0.00,0.00,900.00,NULL,NULL),(225,213,NULL,'print',1.00,'Pcs',12480.00,0.00,0.00,0.00,12480.00,NULL,NULL),(226,214,NULL,'print',1.00,'Pcs',4800.00,0.00,0.00,0.00,4800.00,NULL,NULL),(227,215,NULL,'print',1.00,'Pcs',2500.00,0.00,0.00,0.00,2500.00,NULL,NULL),(228,216,NULL,'print',1.00,'Pcs',1800.00,0.00,0.00,0.00,1800.00,NULL,NULL),(229,217,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(230,218,NULL,'print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(231,219,NULL,'print',1.00,'Pcs',4600.00,0.00,0.00,0.00,4600.00,NULL,NULL),(232,220,NULL,'print',1.00,'Pcs',14500.00,0.00,0.00,0.00,14500.00,NULL,NULL),(233,221,NULL,'print',1.00,'Pcs',14500.00,0.00,0.00,0.00,14500.00,NULL,NULL),(234,222,NULL,'print',1.00,'Pcs',13000.00,0.00,0.00,0.00,13000.00,NULL,NULL),(235,223,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(236,224,NULL,'print',1.00,'Pcs',1800.00,0.00,0.00,0.00,1800.00,NULL,NULL),(237,225,NULL,'print',1.00,'Pcs',11000.00,0.00,0.00,0.00,11000.00,NULL,NULL),(238,226,NULL,'print',1.00,'Pcs',14040.00,0.00,0.00,0.00,14040.00,NULL,NULL),(239,227,NULL,'print',1.00,'Pcs',14040.00,0.00,0.00,0.00,14040.00,NULL,NULL),(240,228,NULL,'print',1.00,'Pcs',14040.00,0.00,0.00,0.00,14040.00,NULL,NULL),(241,229,NULL,'print',1.00,'Pcs',800.00,0.00,0.00,0.00,800.00,NULL,NULL),(242,230,NULL,'print',1.00,'Pcs',6000.00,0.00,0.00,0.00,6000.00,NULL,NULL),(243,231,NULL,'print',1.00,'Pcs',10200.00,0.00,0.00,0.00,10200.00,NULL,NULL),(244,232,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(245,233,NULL,'print',1.00,'Pcs',4500.00,0.00,0.00,0.00,4500.00,NULL,NULL),(246,234,NULL,'print',1.00,'Pcs',700.00,0.00,0.00,0.00,700.00,NULL,NULL),(247,235,NULL,'print',1.00,'Pcs',700.00,0.00,0.00,0.00,700.00,NULL,NULL),(248,236,NULL,'print',1.00,'Pcs',1100.00,0.00,0.00,0.00,1100.00,NULL,NULL),(249,237,NULL,'print',1.00,'Pcs',12000.00,0.00,0.00,0.00,12000.00,NULL,NULL),(250,238,NULL,'print',1.00,'Pcs',900.00,0.00,0.00,0.00,900.00,NULL,NULL),(251,239,NULL,'print',1.00,'Pcs',6000.00,0.00,0.00,0.00,6000.00,NULL,NULL),(252,240,NULL,'print',1.00,'Pcs',9800.00,0.00,0.00,0.00,9800.00,NULL,NULL),(253,241,NULL,'print',1.00,'Pcs',2720.00,0.00,0.00,0.00,2720.00,NULL,NULL),(254,242,NULL,'print',1.00,'Pcs',6750.00,0.00,0.00,0.00,6750.00,NULL,NULL),(255,243,NULL,'print',1.00,'Pcs',4000.00,0.00,0.00,0.00,4000.00,NULL,NULL),(256,244,NULL,'print',1.00,'Pcs',15000.00,0.00,0.00,0.00,15000.00,NULL,NULL),(257,245,NULL,'print',1.00,'Pcs',10500.00,0.00,0.00,0.00,10500.00,NULL,NULL),(258,246,NULL,'print',1.00,'Pcs',800.00,0.00,0.00,0.00,800.00,NULL,NULL),(259,247,NULL,'print',1.00,'Pcs',3600.00,0.00,0.00,0.00,3600.00,NULL,NULL),(260,248,NULL,'print',1.00,'Pcs',6000.00,0.00,0.00,0.00,6000.00,NULL,NULL),(261,249,NULL,'print',1.00,'Pcs',29750.00,0.00,0.00,0.00,29750.00,NULL,NULL),(262,250,NULL,'print',1.00,'Pcs',6000.00,0.00,0.00,0.00,6000.00,NULL,NULL),(263,251,NULL,'print',1.00,'Pcs',7000.00,0.00,0.00,0.00,7000.00,NULL,NULL),(264,252,NULL,'print',1.00,'Pcs',3060.00,0.00,0.00,0.00,3060.00,NULL,NULL),(265,253,NULL,'print',1.00,'Pcs',1800.00,0.00,0.00,0.00,1800.00,NULL,NULL),(266,254,NULL,'print',1.00,'Pcs',12000.00,0.00,0.00,0.00,12000.00,NULL,NULL),(267,255,NULL,'print',1.00,'Pcs',6000.00,0.00,0.00,0.00,6000.00,NULL,NULL),(268,256,NULL,'print',1.00,'Pcs',6400.00,0.00,0.00,0.00,6400.00,NULL,NULL),(269,257,NULL,'print',1.00,'Pcs',45000.00,0.00,0.00,0.00,45000.00,NULL,NULL),(270,258,NULL,'print',1.00,'Pcs',49500.00,0.00,0.00,0.00,49500.00,NULL,NULL),(271,259,NULL,'print',1.00,'Pcs',4800.00,0.00,0.00,0.00,4800.00,NULL,NULL),(272,260,NULL,'print',1.00,'Pcs',5250.00,0.00,0.00,0.00,5250.00,NULL,NULL),(273,261,NULL,'print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(274,262,NULL,'print',1.00,'Pcs',3500.00,0.00,0.00,0.00,3500.00,NULL,NULL),(275,263,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(276,264,NULL,'print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(277,265,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(278,266,NULL,'print',1.00,'Pcs',6000.00,0.00,0.00,0.00,6000.00,NULL,NULL),(279,267,NULL,'print',1.00,'Pcs',7500.00,0.00,0.00,0.00,7500.00,NULL,NULL),(280,268,NULL,'print',1.00,'Pcs',16320.00,0.00,0.00,0.00,16320.00,NULL,NULL),(283,125,NULL,'print',1.00,'Pcs',32500.00,0.00,0.00,0.00,32500.00,NULL,NULL),(285,270,NULL,'print',1.00,'Pcs',44800.00,0.00,0.00,0.00,44800.00,NULL,NULL),(286,271,NULL,'print',1.00,'Pcs',120000.00,0.00,0.00,0.00,120000.00,NULL,NULL),(287,272,NULL,'print',1.00,'Pcs',26000.00,0.00,0.00,0.00,26000.00,NULL,NULL),(288,273,NULL,'print',1.00,'Pcs',98000.00,0.00,0.00,0.00,98000.00,NULL,NULL),(289,274,NULL,'print',1.00,'Pcs',433228.00,0.00,0.00,0.00,433228.00,NULL,NULL),(290,275,NULL,'wedding card',1.00,'Pcs',16500.00,0.00,0.00,0.00,16500.00,NULL,NULL),(291,276,NULL,'print',1.00,'Pcs',96000.00,0.00,0.00,0.00,96000.00,NULL,NULL),(293,277,NULL,'print',1.00,'Pcs',100000.00,0.00,0.00,0.00,100000.00,NULL,NULL),(294,278,NULL,'print',1.00,'Pcs',8200.00,0.00,0.00,0.00,8200.00,NULL,NULL),(295,279,NULL,'BANNER',1.00,'Pcs',7200.00,0.00,0.00,0.00,7200.00,NULL,NULL),(296,280,NULL,'BOOKS',1.00,'Pcs',63500.00,0.00,0.00,0.00,63500.00,NULL,NULL),(297,281,NULL,'File cover',1.00,'Pcs',70000.00,0.00,0.00,0.00,70000.00,NULL,NULL),(298,282,NULL,'BOOKS',1.00,'Pcs',33510.00,0.00,0.00,0.00,33510.00,NULL,NULL),(299,283,NULL,'BANNER',1.00,'Pcs',57600.00,0.00,0.00,0.00,57600.00,NULL,NULL),(300,284,NULL,'File cover',1.00,'Pcs',18000.00,0.00,0.00,0.00,18000.00,NULL,NULL),(301,285,NULL,'BANNER',1.00,'Pcs',4200.00,0.00,0.00,0.00,4200.00,NULL,NULL),(302,286,NULL,'Invoice book',1.00,'Pcs',74400.00,0.00,0.00,0.00,74400.00,NULL,NULL),(303,287,NULL,'trophy',1.00,'Pcs',248000.00,0.00,0.00,0.00,248000.00,NULL,NULL),(304,71,NULL,'print',1.00,'Pcs',160.00,0.00,0.00,0.00,160.00,NULL,NULL),(305,288,NULL,'print',1.00,'Pcs',5770.00,0.00,0.00,0.00,5770.00,NULL,NULL),(307,289,NULL,'print',1.00,'Pcs',5660.00,0.00,0.00,0.00,5660.00,NULL,NULL),(308,290,NULL,'print',1.00,'Pcs',4440.00,0.00,0.00,0.00,4440.00,NULL,NULL),(309,291,NULL,'print',1.00,'Pcs',2100.00,0.00,0.00,0.00,2100.00,NULL,NULL),(311,293,NULL,'print',1.00,'Pcs',4830.00,0.00,0.00,0.00,4830.00,NULL,NULL),(312,294,NULL,'print',1.00,'Pcs',1590.00,0.00,0.00,0.00,1590.00,NULL,NULL),(313,292,NULL,'print',1.00,'Pcs',9320.00,0.00,0.00,0.00,9320.00,NULL,NULL),(314,295,NULL,'print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(316,297,NULL,'print',1.00,'Pcs',975.00,0.00,0.00,0.00,975.00,NULL,NULL),(317,298,NULL,'print',1.00,'Pcs',140.00,0.00,0.00,0.00,140.00,NULL,NULL),(318,299,NULL,'print',1.00,'Pcs',10565.00,0.00,0.00,0.00,10565.00,NULL,NULL),(320,296,NULL,'print',1.00,'Pcs',780.00,0.00,0.00,0.00,780.00,NULL,NULL),(321,269,NULL,'print',1.00,'Pcs',14000.00,0.00,0.00,0.00,14000.00,NULL,NULL),(322,301,NULL,'Sticker',1.00,'Pcs',2100.00,0.00,0.00,0.00,2100.00,NULL,NULL),(323,300,NULL,'print',1.00,'Pcs',5400.00,0.00,0.00,0.00,5400.00,NULL,NULL),(324,302,NULL,'print',1.00,'Pcs',1380.00,0.00,0.00,0.00,1380.00,NULL,NULL),(325,303,NULL,'print',1.00,'Pcs',3640.00,0.00,0.00,0.00,3640.00,NULL,NULL),(326,304,NULL,'print',1.00,'Pcs',11290.00,0.00,0.00,0.00,11290.00,NULL,NULL),(327,305,NULL,'print',1.00,'Pcs',3420.00,0.00,0.00,0.00,3420.00,NULL,NULL),(328,306,NULL,'print',1.00,'Pcs',11130.00,0.00,0.00,0.00,11130.00,NULL,NULL),(329,307,NULL,'print',1.00,'Pcs',380.00,0.00,0.00,0.00,380.00,NULL,NULL),(330,308,NULL,'print',1.00,'Pcs',43890.00,0.00,0.00,0.00,43890.00,NULL,NULL),(331,309,NULL,'print',1.00,'Pcs',16990.00,0.00,0.00,0.00,16990.00,NULL,NULL),(332,310,NULL,'print',1.00,'Pcs',6390.00,0.00,0.00,0.00,6390.00,NULL,NULL),(333,311,NULL,'print',1.00,'Pcs',3280.00,0.00,0.00,0.00,3280.00,NULL,NULL),(334,312,NULL,'print',1.00,'Pcs',10000.00,0.00,0.00,0.00,10000.00,NULL,NULL),(335,61,NULL,'print',1.00,'Pcs',10000.00,0.00,0.00,0.00,10000.00,NULL,NULL),(336,32,NULL,'print',1.00,'Pcs',210.00,0.00,0.00,0.00,210.00,NULL,NULL),(337,313,NULL,'Envelop 8 x 10',10000.00,'Pcs',18.00,0.00,0.00,0.00,180000.00,NULL,NULL),(338,313,NULL,'Envelop 12 x 15',6000.00,'Pcs',40.00,0.00,0.00,0.00,240000.00,NULL,NULL),(339,313,NULL,'Envelop 13.5 x 17.5',3000.00,'Pcs',45.00,0.00,0.00,0.00,135000.00,NULL,NULL),(340,313,NULL,'Envelop 14 x 18',3000.00,'Pcs',45.00,0.00,0.00,0.00,135000.00,NULL,NULL),(341,313,NULL,'Envelop 16 x 18',1000.00,'Pcs',64.00,0.00,0.00,0.00,64000.00,NULL,NULL),(342,314,NULL,'Tag',1.00,'Pcs',433000.00,0.00,0.00,0.00,433000.00,NULL,NULL),(343,25,NULL,'print',1.00,'Pcs',17720.00,0.00,0.00,0.00,17720.00,NULL,NULL),(346,317,NULL,'print',1.00,'Pcs',1800.00,0.00,0.00,0.00,1800.00,NULL,NULL),(348,316,NULL,'print',1.00,'Pcs',1120.00,0.00,0.00,0.00,1120.00,NULL,NULL),(349,315,NULL,'print',1.00,'Pcs',11610.00,0.00,0.00,0.00,11610.00,NULL,NULL),(350,318,NULL,'print',1.00,'Pcs',1605.00,0.00,0.00,0.00,1605.00,NULL,NULL),(353,321,NULL,'print',1.00,'Pcs',10590.00,0.00,0.00,0.00,10590.00,NULL,NULL),(354,320,NULL,'print',1.00,'Pcs',4140.00,0.00,0.00,0.00,4140.00,NULL,NULL),(355,322,NULL,'print',1.00,'Pcs',15920.00,0.00,0.00,0.00,15920.00,NULL,NULL),(356,323,NULL,'print',1.00,'Pcs',700.00,0.00,0.00,0.00,700.00,NULL,NULL),(357,319,NULL,'print',1.00,'Pcs',180.00,0.00,0.00,0.00,180.00,NULL,NULL),(361,325,NULL,'print',1.00,'Pcs',2800.00,0.00,0.00,0.00,2800.00,NULL,NULL),(362,324,NULL,'print',1.00,'Pcs',2440.00,0.00,0.00,0.00,2440.00,NULL,NULL),(363,326,NULL,'print',1.00,'Pcs',6680.00,0.00,0.00,0.00,6680.00,NULL,NULL),(364,327,NULL,'print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(365,328,NULL,'print',1.00,'Pcs',4020.00,0.00,0.00,0.00,4020.00,NULL,NULL),(366,329,NULL,'print',1.00,'Pcs',31250.00,0.00,0.00,0.00,31250.00,NULL,NULL),(367,330,NULL,'print',1.00,'Pcs',900.00,0.00,0.00,0.00,900.00,NULL,NULL),(368,331,NULL,'print',1.00,'Pcs',900.00,0.00,0.00,0.00,900.00,NULL,NULL),(369,332,NULL,'print',1.00,'Pcs',280.00,0.00,0.00,0.00,280.00,NULL,NULL),(370,333,NULL,'print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(371,334,NULL,'print',1.00,'Pcs',10680.00,0.00,0.00,0.00,10680.00,NULL,NULL),(372,335,NULL,'print',1.00,'Pcs',1800.00,0.00,0.00,0.00,1800.00,NULL,NULL),(373,336,NULL,'4 Rings',1.00,'Pcs',200.00,0.00,0.00,0.00,200.00,NULL,NULL),(374,337,NULL,'print',1.00,'Pcs',1540.00,0.00,0.00,0.00,1540.00,NULL,NULL),(375,338,NULL,'print',1.00,'Pcs',1200.00,0.00,0.00,0.00,1200.00,NULL,NULL),(376,339,NULL,'print',1.00,'Pcs',800.00,0.00,0.00,0.00,800.00,NULL,NULL),(377,340,NULL,'print',1.00,'Pcs',15400.00,0.00,0.00,0.00,15400.00,NULL,NULL),(378,341,NULL,'print',1.00,'Pcs',4770.00,0.00,0.00,0.00,4770.00,NULL,NULL),(379,342,NULL,'9 X 12 STICKER PRINT',6.00,'Pcs',110.00,0.00,0.00,0.00,660.00,NULL,NULL),(380,343,NULL,'10.25 X 12.5 SOLID',37.00,'Pcs',35.00,0.00,0.00,0.00,1295.00,NULL,NULL),(381,344,NULL,'Gold Plate Print',1.00,'Pcs',5000.00,0.00,0.00,0.00,5000.00,NULL,NULL),(382,345,NULL,'PRINT',40.00,'Pcs',140.00,0.00,0.00,0.00,5600.00,NULL,NULL),(383,346,NULL,'A3 ART Board',6.00,'Pcs',60.00,0.00,0.00,0.00,360.00,NULL,NULL),(384,346,NULL,'A3 Art Board',8.00,'Pcs',30.00,0.00,0.00,0.00,240.00,NULL,NULL),(385,347,NULL,'print',1.00,'Pcs',84445.00,0.00,0.00,0.00,84445.00,NULL,NULL),(386,348,NULL,'print',75.00,'Pcs',30.00,0.00,0.00,0.00,2250.00,NULL,NULL),(387,349,NULL,'12 x 13 Art Board',22.00,'Pcs',45.00,0.00,0.00,0.00,990.00,NULL,NULL),(388,350,NULL,'print',60.00,'Pcs',40.00,0.00,0.00,0.00,2400.00,NULL,NULL),(389,351,NULL,'print',1.00,'Pcs',1160.00,0.00,0.00,0.00,1160.00,NULL,NULL),(390,352,NULL,'print',1.00,'Pcs',11310.00,0.00,0.00,0.00,11310.00,NULL,NULL),(391,353,NULL,'2 x 2 sticker ',1.00,'Pcs',1000.00,0.00,0.00,0.00,1000.00,NULL,NULL),(392,354,NULL,'Print',1.00,'Pcs',1480.00,0.00,0.00,0.00,1480.00,NULL,NULL),(393,355,NULL,'Print',1.00,'Pcs',14000.00,0.00,0.00,0.00,14000.00,NULL,NULL),(395,356,NULL,'Print',1.00,'Pcs',3600.00,0.00,0.00,0.00,3600.00,NULL,NULL),(396,357,NULL,'8 x 3 Banner',1.00,'Pcs',2880.00,0.00,0.00,0.00,2880.00,NULL,NULL),(397,358,NULL,'Print',1.00,'Pcs',2120.00,0.00,0.00,0.00,2120.00,NULL,NULL),(398,359,NULL,'Print',1.00,'Pcs',2840.00,0.00,0.00,0.00,2840.00,NULL,NULL),(399,360,NULL,'Print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(400,361,NULL,'Print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(401,362,NULL,'Print',1.00,'Pcs',4020.00,0.00,0.00,0.00,4020.00,NULL,NULL),(402,363,NULL,'Print',35.00,'Pcs',40.00,0.00,0.00,0.00,1400.00,NULL,NULL),(403,364,NULL,'Print',50.00,'Pcs',600.00,0.00,0.00,0.00,30000.00,NULL,NULL),(404,365,NULL,'Print',145.00,'Pcs',100.00,0.00,0.00,0.00,14500.00,NULL,NULL),(405,366,NULL,'Print',182.00,'Pcs',30.00,0.00,0.00,0.00,5460.00,NULL,NULL),(406,367,NULL,'Print',2.00,'Pcs',300.00,0.00,0.00,0.00,600.00,NULL,NULL),(407,367,NULL,'Print',1.00,'Pcs',500.00,0.00,0.00,0.00,500.00,NULL,NULL),(408,368,NULL,'Print',1.00,'Pcs',300.00,0.00,0.00,0.00,300.00,NULL,NULL),(409,369,NULL,'Print',77.00,'Pcs',30.00,0.00,0.00,0.00,2310.00,NULL,NULL),(410,370,NULL,'Print',1.00,'Pcs',1600.00,0.00,0.00,0.00,1600.00,NULL,NULL),(411,371,NULL,'Print',8.00,'Pcs',150.00,0.00,0.00,0.00,1200.00,NULL,NULL),(412,372,NULL,'Print',4.00,'Pcs',150.00,0.00,0.00,0.00,600.00,NULL,NULL),(413,373,NULL,'Print',218.00,'Pcs',30.00,0.00,0.00,0.00,6540.00,NULL,NULL),(414,374,NULL,'Print',1.00,'Pcs',2760.00,0.00,0.00,0.00,2760.00,NULL,NULL),(415,375,NULL,'Print',1.00,'Pcs',2035.00,0.00,0.00,0.00,2035.00,NULL,NULL),(416,376,NULL,'Print',1.00,'Pcs',4470.00,0.00,0.00,0.00,4470.00,NULL,NULL),(417,377,NULL,'Print',1.00,'Pcs',60.00,0.00,0.00,0.00,60.00,NULL,NULL),(418,378,NULL,'Print',1.00,'Pcs',1260.00,0.00,0.00,0.00,1260.00,NULL,NULL),(419,379,NULL,'Print',4.00,'Pcs',80.00,0.00,0.00,0.00,320.00,NULL,NULL),(420,380,NULL,'Print',1.00,'Pcs',8325.00,0.00,0.00,0.00,8325.00,NULL,NULL),(422,382,NULL,'Print',1.00,'Pcs',14000.00,0.00,0.00,0.00,14000.00,NULL,NULL),(423,381,NULL,'Print',1.00,'Pcs',1960.00,0.00,0.00,0.00,1960.00,NULL,NULL),(424,383,NULL,'Print',1.00,'Pcs',3280.00,0.00,0.00,0.00,3280.00,NULL,NULL),(425,384,NULL,'Print',1.00,'Pcs',2400.00,0.00,0.00,0.00,2400.00,NULL,NULL),(426,385,NULL,'Print',1.00,'Pcs',750.00,0.00,0.00,0.00,750.00,NULL,NULL),(427,386,NULL,'Print',1.00,'Pcs',3800.00,0.00,0.00,0.00,3800.00,NULL,NULL),(428,387,NULL,'PVC STICKER',1.00,'Pcs',750.00,0.00,0.00,0.00,750.00,NULL,NULL),(430,388,NULL,'Print',1.00,'Pcs',50420.00,0.00,0.00,0.00,50420.00,NULL,NULL),(431,389,NULL,'Print',1.00,'Pcs',1200.00,0.00,0.00,0.00,1200.00,NULL,NULL),(432,390,NULL,'Print',1.00,'Pcs',6400.00,0.00,0.00,0.00,6400.00,NULL,NULL),(433,391,NULL,'Print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(434,392,NULL,'Print',1.00,'Pcs',1600.00,0.00,0.00,0.00,1600.00,NULL,NULL),(435,393,NULL,'Print',1.00,'Pcs',4620.00,0.00,0.00,0.00,4620.00,NULL,NULL),(436,394,NULL,'Print',1.00,'Pcs',1100.00,0.00,0.00,0.00,1100.00,NULL,NULL),(437,395,NULL,'Print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(438,396,NULL,'Print',1.00,'Pcs',6500.00,0.00,0.00,0.00,6500.00,NULL,NULL),(439,397,NULL,'Print',1.00,'Pcs',15000.00,0.00,0.00,0.00,15000.00,NULL,NULL),(440,398,NULL,'Print',1.00,'Pcs',2000.00,0.00,0.00,0.00,2000.00,NULL,NULL),(441,399,NULL,'Print',1.00,'Pcs',4500.00,0.00,0.00,0.00,4500.00,NULL,NULL),(442,400,NULL,'Print',1.00,'Pcs',5900.00,0.00,0.00,0.00,5900.00,NULL,NULL),(443,401,NULL,'Print',1.00,'Pcs',18200.00,0.00,0.00,0.00,18200.00,NULL,NULL),(444,402,NULL,'Print',1.00,'Pcs',6600.00,0.00,0.00,0.00,6600.00,NULL,NULL),(445,403,NULL,'Print',1.00,'Pcs',13000.00,0.00,0.00,0.00,13000.00,NULL,NULL),(446,404,NULL,'Print',1.00,'Pcs',8750.00,0.00,0.00,0.00,8750.00,NULL,NULL),(447,405,NULL,'Print',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(448,406,NULL,'Print',1.00,'Pcs',7200.00,0.00,0.00,0.00,7200.00,NULL,NULL),(449,407,NULL,'Print',1.00,'Pcs',4500.00,0.00,0.00,0.00,4500.00,NULL,NULL),(450,408,NULL,'Print',1.00,'Pcs',3500.00,0.00,0.00,0.00,3500.00,NULL,NULL),(451,409,NULL,'Print',1.00,'Pcs',12000.00,0.00,0.00,0.00,12000.00,NULL,NULL),(452,410,NULL,'Print',1.00,'Pcs',12500.00,0.00,0.00,0.00,12500.00,NULL,NULL),(453,411,NULL,'Print',1.00,'Pcs',3000.00,0.00,0.00,0.00,3000.00,NULL,NULL),(454,412,NULL,'Print',1.00,'Pcs',5300.00,0.00,0.00,0.00,5300.00,NULL,NULL),(455,413,NULL,'Print',1.00,'Pcs',7000.00,0.00,0.00,0.00,7000.00,NULL,NULL),(456,414,NULL,'Print',1.00,'Pcs',6750.00,0.00,0.00,0.00,6750.00,NULL,NULL),(457,415,NULL,'Print',1.00,'Pcs',200.00,0.00,0.00,0.00,200.00,NULL,NULL),(458,416,NULL,'Print',1.00,'Pcs',5600.00,0.00,0.00,0.00,5600.00,NULL,NULL),(459,417,NULL,'Print',1.00,'Pcs',37520.00,0.00,0.00,0.00,37520.00,NULL,NULL),(461,418,NULL,'WEDDING CARD',1.00,'Pcs',168000.00,0.00,0.00,0.00,168000.00,NULL,NULL),(462,419,NULL,'WEDDING CARD',1.00,'Pcs',25000.00,0.00,0.00,0.00,25000.00,NULL,NULL),(463,420,NULL,'WEDDING CARD',1.00,'Pcs',25000.00,0.00,0.00,0.00,25000.00,NULL,NULL),(464,421,NULL,'WEDDING CARD',1.00,'Pcs',13500.00,0.00,0.00,0.00,13500.00,NULL,NULL),(465,422,NULL,'WEDDING CARD',1.00,'Pcs',14000.00,0.00,0.00,0.00,14000.00,NULL,NULL),(466,423,NULL,'Supervisor Certificate',1300.00,'Pcs',19.00,0.00,0.00,0.00,24700.00,NULL,NULL),(467,424,NULL,'V/C Card Print',200.00,'Pcs',16.00,0.00,0.00,0.00,3200.00,NULL,NULL),(468,425,NULL,'Print',1.00,'Pcs',3870.00,0.00,0.00,0.00,3870.00,NULL,NULL),(469,426,NULL,'Print',1.00,'Pcs',8810.00,0.00,0.00,0.00,8810.00,NULL,NULL),(470,427,NULL,'Print',1.00,'Pcs',41045.00,0.00,0.00,0.00,41045.00,NULL,NULL),(471,428,NULL,'Print',1.00,'Pcs',30610.00,0.00,0.00,0.00,30610.00,NULL,NULL),(472,429,NULL,'Print',1.00,'Pcs',61995.00,0.00,0.00,0.00,61995.00,NULL,NULL),(473,430,NULL,'Print',1.00,'Pcs',17950.00,0.00,0.00,0.00,17950.00,NULL,NULL),(474,431,NULL,'Print',1.00,'Pcs',19920.00,0.00,0.00,0.00,19920.00,NULL,NULL),(475,432,NULL,'Print',1.00,'Pcs',16330.00,0.00,0.00,0.00,16330.00,NULL,NULL),(476,433,NULL,'Print',1.00,'Pcs',17120.00,0.00,0.00,0.00,17120.00,NULL,NULL),(478,434,NULL,'Print',1.00,'Pcs',15450.00,0.00,0.00,0.00,15450.00,NULL,NULL),(479,435,NULL,'Print',1.00,'Pcs',2060.00,0.00,0.00,0.00,2060.00,NULL,NULL),(480,436,NULL,'Print',1.00,'Pcs',8780.00,0.00,0.00,0.00,8780.00,NULL,NULL),(481,437,NULL,'Print',1.00,'Pcs',42465.00,0.00,0.00,0.00,42465.00,NULL,NULL),(482,438,NULL,'Print',1.00,'Pcs',5470.00,0.00,0.00,0.00,5470.00,NULL,NULL),(483,439,NULL,'Print',1.00,'Pcs',10910.00,0.00,0.00,0.00,10910.00,NULL,NULL),(484,440,NULL,'Print',1.00,'Pcs',8950.00,0.00,0.00,0.00,8950.00,NULL,NULL),(485,441,NULL,'Print',1.00,'Pcs',9850.00,0.00,0.00,0.00,9850.00,NULL,NULL),(486,442,NULL,'Print',1.00,'Pcs',14330.00,0.00,0.00,0.00,14330.00,NULL,NULL),(487,443,NULL,'Print',1.00,'Pcs',6760.00,0.00,0.00,0.00,6760.00,NULL,NULL),(488,444,NULL,'Print',1.00,'Pcs',13010.00,0.00,0.00,0.00,13010.00,NULL,NULL),(489,445,NULL,'Print',1.00,'Pcs',18370.00,0.00,0.00,0.00,18370.00,NULL,NULL),(490,446,NULL,'Print',1.00,'Pcs',18930.00,0.00,0.00,0.00,18930.00,NULL,NULL),(491,447,NULL,'Print',1.00,'Pcs',1370.00,0.00,0.00,0.00,1370.00,NULL,NULL),(492,448,NULL,'Print',1.00,'Pcs',8095.00,0.00,0.00,0.00,8095.00,NULL,NULL),(493,449,NULL,'Print',1.00,'Pcs',2940.00,0.00,0.00,0.00,2940.00,NULL,NULL),(494,450,NULL,'Print',1.00,'Pcs',15740.00,0.00,0.00,0.00,15740.00,NULL,NULL),(495,451,NULL,'Print',1.00,'Pcs',17010.00,0.00,0.00,0.00,17010.00,NULL,NULL),(496,452,NULL,'Print',1.00,'Pcs',26465.00,0.00,0.00,0.00,26465.00,NULL,NULL),(497,453,NULL,'Print',1.00,'Pcs',9650.00,0.00,0.00,0.00,9650.00,NULL,NULL),(498,454,NULL,'Print',1.00,'Pcs',2260.00,0.00,0.00,0.00,2260.00,NULL,NULL),(499,455,NULL,'Print',1.00,'Pcs',10460.00,0.00,0.00,0.00,10460.00,NULL,NULL),(500,456,NULL,'Print',1.00,'Pcs',10280.00,0.00,0.00,0.00,10280.00,NULL,NULL),(501,457,NULL,'Print',1.00,'Pcs',20370.00,0.00,0.00,0.00,20370.00,NULL,NULL),(502,458,NULL,'Print',1.00,'Pcs',5910.00,0.00,0.00,0.00,5910.00,NULL,NULL),(503,459,NULL,'Print',1.00,'Pcs',4300.00,0.00,0.00,0.00,4300.00,NULL,NULL),(504,460,NULL,'Print',1.00,'Pcs',13880.00,0.00,0.00,0.00,13880.00,NULL,NULL),(505,461,NULL,'Print',1.00,'Pcs',6770.00,0.00,0.00,0.00,6770.00,NULL,NULL),(507,463,NULL,'v/c print',100.00,'Pcs',15.00,0.00,0.00,0.00,1500.00,NULL,NULL),(508,464,NULL,'Sticker',1.00,'Pcs',4160.00,0.00,0.00,0.00,4160.00,NULL,NULL),(509,465,NULL,'Letter Head',250.00,'Sheets',35.00,0.00,0.00,0.00,8750.00,NULL,NULL),(510,465,NULL,'Foiling Block',1.00,'Pcs',2500.00,0.00,0.00,0.00,2500.00,NULL,NULL),(511,466,NULL,'9 X 12 STICKER PRINT',225.00,'Pcs',70.00,0.00,0.00,0.00,15750.00,NULL,NULL),(512,467,NULL,'PRINT',1.00,'Pcs',6100.00,0.00,0.00,0.00,6100.00,NULL,NULL),(513,468,NULL,'MEDAL',15.00,'Pcs',120.00,0.00,0.00,0.00,1800.00,NULL,NULL),(514,469,NULL,'Books RSOF A4',25.00,'Pcs',1400.00,0.00,0.00,0.00,35000.00,NULL,NULL),(515,469,NULL,'Books ROF A4',100.00,'Pcs',1270.00,0.00,0.00,0.00,127000.00,NULL,NULL),(516,470,NULL,'Print',1.00,'Pcs',4490.00,0.00,0.00,0.00,4490.00,NULL,NULL),(517,471,NULL,'Tea Bag',2000.00,'Pcs',4.00,0.00,0.00,0.00,8000.00,NULL,NULL),(518,472,NULL,'A4',185.00,'Pcs',70.00,0.00,0.00,0.00,12950.00,NULL,NULL),(519,472,NULL,'Sticker Halfcut',1.00,'Pcs',1500.00,0.00,0.00,0.00,1500.00,NULL,NULL),(520,472,NULL,'Old balance',1.00,'Pcs',800.00,0.00,0.00,0.00,800.00,NULL,NULL),(523,473,NULL,'Print',1.00,'Pcs',250.00,0.00,0.00,0.00,250.00,NULL,NULL),(524,474,NULL,'STICKER SHEET',21.00,'Pcs',80.00,0.00,0.00,0.00,1680.00,NULL,NULL),(525,462,NULL,'Bags',1048.00,'Pcs',60.00,0.00,0.00,0.00,62880.00,NULL,NULL);
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `SaleID` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `CustomerId` int NOT NULL,
  `LocationId` int DEFAULT NULL,
  `SaleDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `JobCardID` int DEFAULT NULL,
  `RefNo` varchar(100) DEFAULT NULL,
  `Remarks` text,
  `PONo` varchar(100) DEFAULT NULL,
  `TotalDiscount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalSaleAmount` decimal(10,2) NOT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `IsDeleted` tinyint DEFAULT '0',
  `RenewalDomain` varchar(255) DEFAULT NULL,
  `CashReceived` decimal(10,2) DEFAULT '0.00',
  `ChangeGiven` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`SaleID`),
  KEY `CustomerID` (`CustomerId`)
) ENGINE=InnoDB AUTO_INCREMENT=475 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,11,7,NULL,'2026-08-04 00:00:00',NULL,NULL,NULL,NULL,0.00,2250.00,'2026-08-04 10:21:00','2026-08-04 10:21:27',1,NULL,0.00,0.00),(2,11,1,NULL,'2026-07-01 00:00:00',NULL,'3721',NULL,NULL,0.00,21835.00,'2026-08-04 11:56:09','2026-08-04 11:56:09',0,NULL,0.00,0.00),(3,11,3,NULL,'2026-07-01 00:00:00',NULL,'3722',NULL,NULL,0.00,9770.00,'2026-08-04 11:56:49','2026-08-04 11:57:50',0,NULL,0.00,0.00),(4,11,4,NULL,'2026-07-01 00:00:00',NULL,'3723',NULL,NULL,0.00,4890.00,'2026-08-04 11:57:18','2026-08-04 11:58:09',0,NULL,0.00,0.00),(5,11,19,NULL,'2026-07-01 00:00:00',NULL,'3724',NULL,NULL,0.00,9940.00,'2026-08-04 11:58:47','2026-08-04 11:59:35',0,NULL,0.00,0.00),(6,11,5,NULL,'2026-07-01 00:00:00',NULL,'3725',NULL,NULL,0.00,19690.00,'2026-08-04 11:59:18','2026-08-04 11:59:18',0,NULL,0.00,0.00),(7,11,3,NULL,'2026-07-01 00:00:00',NULL,'3726',NULL,NULL,0.00,9420.00,'2026-08-04 12:00:02','2026-08-04 12:00:02',0,NULL,0.00,0.00),(8,11,6,NULL,'2026-07-03 00:00:00',NULL,'3727',NULL,NULL,0.00,18560.00,'2026-08-04 12:02:14','2026-08-04 12:02:14',0,NULL,0.00,0.00),(9,11,7,NULL,'2026-07-03 00:00:00',NULL,'3728',NULL,NULL,0.00,5945.00,'2026-08-04 12:02:45','2026-08-04 12:02:45',0,NULL,0.00,0.00),(10,11,8,NULL,'2026-07-03 00:00:00',NULL,'3729',NULL,NULL,0.00,3640.00,'2026-08-04 12:03:24','2026-08-04 12:03:24',0,NULL,0.00,0.00),(11,11,20,NULL,'2026-07-03 00:00:00',NULL,'3730',NULL,NULL,0.00,2250.00,'2026-08-04 12:03:58','2026-08-04 12:03:58',0,NULL,0.00,0.00),(12,11,20,NULL,'2026-07-03 00:00:00',NULL,'3730',NULL,NULL,0.00,2250.00,'2026-08-04 12:03:58','2026-08-04 12:03:58',1,NULL,0.00,0.00),(13,11,21,NULL,'2026-07-03 00:00:00',NULL,'3732',NULL,NULL,0.00,4375.00,'2026-08-04 12:07:19','2026-08-04 12:07:19',0,NULL,0.00,0.00),(14,11,9,NULL,'2026-07-03 00:00:00',NULL,'3733',NULL,NULL,0.00,9175.00,'2026-08-04 12:08:04','2026-08-04 12:08:04',0,NULL,0.00,0.00),(15,11,5,NULL,'2026-07-07 00:00:00',NULL,'3735',NULL,NULL,0.00,13230.00,'2026-08-04 12:08:40','2026-08-04 12:08:40',0,NULL,0.00,0.00),(16,11,6,NULL,'2026-07-07 00:00:00',NULL,'3737',NULL,NULL,0.00,11345.00,'2026-08-04 12:18:14','2026-08-04 12:18:45',0,NULL,0.00,0.00),(17,11,3,NULL,'2026-07-07 00:00:00',NULL,'3738',NULL,NULL,0.00,8680.00,'2026-08-04 12:24:15','2026-08-04 12:24:15',0,NULL,0.00,0.00),(18,11,1,NULL,'2026-07-07 00:00:00',NULL,'3739',NULL,NULL,0.00,22500.00,'2026-08-04 12:24:47','2026-08-04 12:24:47',0,NULL,0.00,0.00),(19,11,1,NULL,'2026-07-10 00:00:00',NULL,'3740',NULL,NULL,0.00,16505.00,'2026-08-04 12:25:13','2026-08-04 12:25:13',0,NULL,0.00,0.00),(20,11,6,NULL,'2026-07-10 00:00:00',NULL,'3741',NULL,NULL,0.00,5810.00,'2026-08-04 12:27:04','2026-08-04 12:27:04',0,NULL,0.00,0.00),(21,11,3,NULL,'2026-07-10 00:00:00',NULL,NULL,NULL,NULL,0.00,25230.00,'2026-08-04 12:27:39','2026-08-04 12:27:39',0,NULL,0.00,0.00),(22,11,5,NULL,'2026-07-10 00:00:00',NULL,'3743',NULL,NULL,0.00,23310.00,'2026-08-04 12:28:09','2026-08-04 12:28:09',0,NULL,0.00,0.00),(23,11,19,NULL,'2026-07-10 00:00:00',NULL,'3744',NULL,NULL,0.00,17955.00,'2026-08-04 12:28:41','2026-08-04 12:28:41',0,NULL,0.00,0.00),(24,11,4,NULL,'2026-07-10 00:00:00',NULL,'3745',NULL,NULL,0.00,11730.00,'2026-08-04 12:29:44','2026-08-04 12:29:44',0,NULL,0.00,0.00),(25,11,22,NULL,'2026-07-10 00:00:00',NULL,'3746',NULL,NULL,0.00,17720.00,'2026-08-04 12:30:23','2026-08-12 13:50:29',0,NULL,0.00,0.00),(26,11,22,NULL,'2026-07-10 00:00:00',NULL,'3747',NULL,NULL,0.00,18540.00,'2026-08-04 12:31:26','2026-08-04 12:31:26',0,NULL,0.00,0.00),(27,11,22,NULL,'2026-07-10 00:00:00',NULL,'3748',NULL,NULL,0.00,11460.00,'2026-08-04 12:31:54','2026-08-04 12:31:54',0,NULL,0.00,0.00),(28,11,23,NULL,'2026-07-13 00:00:00',NULL,'3749',NULL,NULL,0.00,1350.00,'2026-08-04 12:32:38','2026-08-04 12:32:38',0,NULL,0.00,0.00),(29,11,3,NULL,'2026-07-15 00:00:00',NULL,'3750',NULL,NULL,0.00,2650.00,'2026-08-04 12:33:27','2026-08-04 12:33:27',0,NULL,0.00,0.00),(30,11,6,NULL,'2026-07-15 00:00:00',NULL,'3751',NULL,NULL,0.00,1590.00,'2026-08-04 12:33:57','2026-08-04 12:33:57',0,NULL,0.00,0.00),(31,11,12,NULL,'2026-07-15 00:00:00',NULL,'3752',NULL,NULL,0.00,36885.00,'2026-08-04 12:34:32','2026-08-04 12:34:32',0,NULL,0.00,0.00),(32,11,19,NULL,'2026-07-15 00:00:00',NULL,'3753',NULL,NULL,0.00,210.00,'2026-08-04 12:35:06','2026-08-11 13:28:32',0,NULL,0.00,0.00),(33,11,1,NULL,'2026-07-15 00:00:00',NULL,'3754',NULL,NULL,0.00,26990.00,'2026-08-04 12:35:29','2026-08-04 12:35:29',0,NULL,0.00,0.00),(34,11,7,NULL,'2026-07-15 00:00:00',NULL,'3755',NULL,NULL,0.00,5880.00,'2026-08-04 12:36:45','2026-08-04 12:36:45',0,NULL,0.00,0.00),(35,11,16,NULL,'2026-07-15 00:00:00',NULL,'3756',NULL,NULL,0.00,6645.00,'2026-08-04 12:40:38','2026-08-04 12:40:38',0,NULL,0.00,0.00),(36,11,24,NULL,'2026-07-15 00:00:00',NULL,'3757',NULL,NULL,0.00,1840.00,'2026-08-04 12:41:19','2026-08-04 12:41:19',0,NULL,0.00,0.00),(37,11,14,NULL,'2026-07-15 00:00:00',NULL,'3758',NULL,NULL,0.00,1350.00,'2026-08-04 12:42:44','2026-08-04 12:42:44',0,NULL,0.00,0.00),(38,11,13,NULL,'2026-07-15 00:00:00',NULL,'3759',NULL,NULL,0.00,5770.00,'2026-08-04 12:43:28','2026-08-04 12:43:28',0,NULL,0.00,0.00),(39,11,3,NULL,'2026-07-23 00:00:00',NULL,'3760',NULL,NULL,0.00,11950.00,'2026-08-04 12:43:54','2026-08-04 12:43:54',0,NULL,0.00,0.00),(40,11,3,NULL,'2026-06-23 00:00:00',NULL,'3761',NULL,NULL,0.00,7220.00,'2026-08-04 12:45:18','2026-08-04 12:45:18',0,NULL,0.00,0.00),(41,11,3,NULL,'2026-07-23 00:00:00',NULL,'3762',NULL,NULL,0.00,12950.00,'2026-08-04 12:45:54','2026-08-04 12:45:54',0,NULL,0.00,0.00),(42,11,1,NULL,'2026-07-23 00:00:00',NULL,'3763',NULL,NULL,0.00,31490.00,'2026-08-04 12:46:21','2026-08-04 12:46:21',0,NULL,0.00,0.00),(43,11,5,NULL,'2026-07-23 00:00:00',NULL,'3764',NULL,NULL,0.00,3180.00,'2026-08-04 12:47:01','2026-08-04 12:47:01',0,NULL,0.00,0.00),(44,11,6,NULL,'2026-07-23 00:00:00',NULL,'3765',NULL,NULL,0.00,14600.00,'2026-08-04 12:47:31','2026-08-04 12:47:31',0,NULL,0.00,0.00),(45,11,7,NULL,'2026-07-23 00:00:00',NULL,'3766',NULL,NULL,0.00,2150.00,'2026-08-04 12:48:21','2026-08-04 12:48:21',0,NULL,0.00,0.00),(46,11,4,NULL,'2026-07-23 00:00:00',NULL,'3767',NULL,NULL,0.00,12180.00,'2026-08-04 12:48:47','2026-08-04 12:48:47',0,NULL,0.00,0.00),(47,11,19,NULL,'2026-07-23 00:00:00',NULL,'3768',NULL,NULL,0.00,17605.00,'2026-08-04 12:49:59','2026-08-04 12:49:59',0,NULL,0.00,0.00),(48,11,26,NULL,'2026-07-24 00:00:00',NULL,'3769',NULL,NULL,0.00,23280.00,'2026-08-04 12:56:12','2026-08-04 12:56:12',0,NULL,0.00,0.00),(49,11,26,NULL,'2026-07-24 00:00:00',NULL,'3770',NULL,NULL,0.00,1560.00,'2026-08-04 12:56:58','2026-08-04 12:56:58',0,NULL,0.00,0.00),(50,11,14,NULL,'2026-07-24 00:00:00',NULL,'3772',NULL,NULL,0.00,8730.00,'2026-08-04 13:06:00','2026-08-04 13:06:00',0,NULL,0.00,0.00),(51,11,15,NULL,'2026-07-24 00:00:00',NULL,'3773',NULL,NULL,0.00,300.00,'2026-08-04 13:07:21','2026-08-04 13:07:21',0,NULL,0.00,0.00),(52,11,24,NULL,'2026-07-24 00:00:00',NULL,'3774',NULL,NULL,0.00,12120.00,'2026-08-04 13:07:54','2026-08-04 13:07:54',0,NULL,0.00,0.00),(53,11,12,NULL,'2026-07-24 00:00:00',NULL,'3775',NULL,NULL,0.00,4435.00,'2026-08-04 13:08:40','2026-08-04 13:08:40',0,NULL,0.00,0.00),(54,11,8,NULL,'2026-07-24 00:00:00',NULL,'3776',NULL,NULL,0.00,7760.00,'2026-08-04 13:13:27','2026-08-04 13:13:27',0,NULL,0.00,0.00),(55,11,27,NULL,'2026-07-24 00:00:00',NULL,'3771',NULL,NULL,0.00,3000.00,'2026-08-04 13:25:53','2026-08-04 13:25:53',0,NULL,0.00,0.00),(56,11,28,NULL,'2026-07-24 00:00:00',NULL,'3777',NULL,NULL,0.00,960.00,'2026-08-04 13:27:20','2026-08-04 13:27:20',0,NULL,0.00,0.00),(57,11,25,NULL,'2026-07-24 00:00:00',NULL,'3778',NULL,NULL,0.00,520.00,'2026-08-04 13:30:54','2026-08-04 13:30:54',0,NULL,0.00,0.00),(58,11,9,NULL,'2026-07-28 00:00:00',NULL,'3779',NULL,NULL,0.00,2960.00,'2026-08-04 13:31:28','2026-08-04 13:31:28',0,NULL,0.00,0.00),(59,11,6,NULL,'2026-07-31 00:00:00',NULL,'3780',NULL,NULL,0.00,4810.00,'2026-08-04 13:33:16','2026-08-04 13:33:16',0,NULL,0.00,0.00),(60,11,4,NULL,'2026-07-25 00:00:00',NULL,'3781',NULL,NULL,0.00,10860.00,'2026-08-04 13:33:58','2026-08-04 13:33:58',0,NULL,0.00,0.00),(61,11,1,NULL,'2026-07-30 00:00:00',NULL,'3782',NULL,NULL,0.00,10000.00,'2026-08-04 13:42:21','2026-08-11 13:19:50',0,NULL,0.00,0.00),(62,11,4,NULL,'2026-07-30 00:00:00',NULL,'3783',NULL,NULL,0.00,6000.00,'2026-08-04 13:42:58','2026-08-04 13:42:58',0,NULL,0.00,0.00),(63,11,1,NULL,'2026-07-31 00:00:00',NULL,'3784',NULL,NULL,0.00,17535.00,'2026-08-04 13:43:26','2026-08-04 13:43:26',0,NULL,0.00,0.00),(64,11,19,NULL,'2026-07-31 00:00:00',NULL,'3785',NULL,NULL,0.00,520.00,'2026-08-04 13:44:00','2026-08-04 13:44:00',0,NULL,0.00,0.00),(65,11,19,NULL,'2026-07-31 00:00:00',NULL,'3785',NULL,NULL,0.00,520.00,'2026-08-04 13:44:00','2026-08-04 13:44:00',1,NULL,0.00,0.00),(66,11,25,NULL,'2026-07-31 00:00:00',NULL,'3786',NULL,NULL,0.00,2560.00,'2026-08-04 13:44:35','2026-08-04 13:45:19',0,NULL,0.00,0.00),(67,11,3,NULL,'2026-07-31 00:00:00',NULL,'3787',NULL,NULL,0.00,10810.00,'2026-08-04 13:45:58','2026-08-04 13:45:58',0,NULL,0.00,0.00),(68,11,16,NULL,'2026-07-31 00:00:00',NULL,'3788',NULL,NULL,0.00,2520.00,'2026-08-04 13:46:34','2026-08-04 13:46:34',0,NULL,0.00,0.00),(69,11,5,NULL,'2026-07-31 00:00:00',NULL,'3789',NULL,NULL,0.00,3300.00,'2026-08-04 13:47:06','2026-08-04 13:47:06',0,NULL,0.00,0.00),(70,11,29,NULL,'2026-07-31 00:00:00',NULL,'3790',NULL,NULL,0.00,8830.00,'2026-08-04 13:55:40','2026-08-04 13:55:40',0,NULL,0.00,0.00),(71,11,10,NULL,'2026-07-31 00:00:00',NULL,'3791',NULL,NULL,0.00,160.00,'2026-08-04 13:56:21','2026-08-07 16:29:02',0,NULL,0.00,0.00),(72,11,8,NULL,'2026-07-31 00:00:00',NULL,'3792',NULL,NULL,0.00,25350.00,'2026-08-04 13:57:36','2026-08-04 13:57:36',0,NULL,0.00,0.00),(73,11,14,NULL,'2026-07-31 00:00:00',NULL,'3793',NULL,NULL,0.00,100.00,'2026-08-04 13:58:33','2026-08-04 13:58:33',0,NULL,0.00,0.00),(74,11,12,NULL,'2026-07-31 00:00:00',NULL,'3794',NULL,NULL,0.00,300.00,'2026-08-04 13:59:03','2026-08-04 13:59:03',0,NULL,0.00,0.00),(75,11,23,NULL,'2026-07-31 00:00:00',NULL,'3795',NULL,NULL,0.00,210.00,'2026-08-04 13:59:48','2026-08-04 13:59:48',0,NULL,0.00,0.00),(76,11,30,NULL,'2026-07-31 00:00:00',NULL,'3796',NULL,NULL,0.00,7260.00,'2026-08-04 14:34:22','2026-08-04 14:34:22',0,NULL,0.00,0.00),(77,11,9,NULL,'2026-08-04 00:00:00',NULL,NULL,NULL,NULL,0.00,1480.00,'2026-08-04 14:42:58','2026-08-04 14:42:58',0,NULL,0.00,0.00),(78,11,32,NULL,'2026-07-01 00:00:00',NULL,'3662',NULL,NULL,0.00,1900.00,'2026-08-05 10:07:03','2026-08-05 10:07:03',0,NULL,0.00,0.00),(79,11,32,NULL,'2026-07-01 00:00:00',NULL,'3663',NULL,NULL,0.00,5000.00,'2026-08-05 10:09:15','2026-08-05 10:09:15',0,NULL,0.00,0.00),(80,11,32,NULL,'2026-07-01 00:00:00',NULL,'3664',NULL,NULL,0.00,1000.00,'2026-08-05 10:10:30','2026-08-05 10:10:30',0,NULL,0.00,0.00),(81,11,32,NULL,'2026-07-01 00:00:00',NULL,'3666',NULL,NULL,0.00,780.00,'2026-08-05 10:11:40','2026-08-05 10:11:40',0,NULL,0.00,0.00),(82,11,32,NULL,'2026-07-01 00:00:00',NULL,'3667',NULL,NULL,0.00,980.00,'2026-08-05 10:12:11','2026-08-05 10:12:11',0,NULL,0.00,0.00),(83,11,32,NULL,'2026-07-01 00:00:00',NULL,'3668',NULL,NULL,0.00,1000.00,'2026-08-05 10:12:37','2026-08-05 10:12:37',0,NULL,0.00,0.00),(84,11,32,NULL,'2026-07-02 00:00:00',NULL,'3669',NULL,NULL,0.00,840.00,'2026-08-05 10:13:00','2026-08-05 10:13:00',0,NULL,0.00,0.00),(85,11,32,NULL,'2026-07-02 00:00:00',NULL,'3670',NULL,NULL,0.00,300.00,'2026-08-05 10:13:42','2026-08-05 10:13:42',0,NULL,0.00,0.00),(86,11,32,NULL,'2026-06-02 00:00:00',NULL,'3671',NULL,NULL,0.00,4800.00,'2026-08-05 10:14:10','2026-08-05 10:14:10',0,NULL,0.00,0.00),(87,11,32,NULL,'2026-07-02 00:00:00',NULL,'3672',NULL,NULL,0.00,2500.00,'2026-08-05 10:14:49','2026-08-05 10:14:49',0,NULL,0.00,0.00),(88,11,32,NULL,'2026-07-02 00:00:00',NULL,'3673',NULL,NULL,0.00,3550.00,'2026-08-05 10:15:45','2026-08-05 10:16:19',0,NULL,0.00,0.00),(89,11,32,NULL,'2026-07-02 00:00:00',NULL,'3674',NULL,NULL,0.00,500.00,'2026-08-05 10:17:36','2026-08-05 10:17:36',0,NULL,0.00,0.00),(90,11,32,NULL,'2026-07-02 00:00:00',NULL,'3675',NULL,NULL,0.00,8910.00,'2026-08-05 10:23:25','2026-08-05 10:23:25',0,NULL,0.00,0.00),(91,11,32,NULL,'2026-07-03 00:00:00',NULL,'3676',NULL,NULL,0.00,4000.00,'2026-08-05 10:23:50','2026-08-05 10:23:50',0,NULL,0.00,0.00),(92,11,32,NULL,'2026-07-03 00:00:00',NULL,'3677',NULL,NULL,0.00,10500.00,'2026-08-05 10:24:19','2026-08-05 10:24:19',0,NULL,0.00,0.00),(93,11,32,NULL,'2026-07-03 00:00:00',NULL,'3678',NULL,NULL,0.00,5800.00,'2026-08-05 10:24:44','2026-08-05 10:24:44',0,NULL,0.00,0.00),(94,11,32,NULL,'2026-07-03 00:00:00',NULL,'3679',NULL,NULL,0.00,1000.00,'2026-08-05 10:25:41','2026-08-05 10:25:41',0,NULL,0.00,0.00),(95,11,32,NULL,'2026-07-03 00:00:00',NULL,'3680',NULL,NULL,0.00,320.00,'2026-08-05 10:26:16','2026-08-05 10:26:16',0,NULL,0.00,0.00),(96,11,32,NULL,'2026-07-04 00:00:00',NULL,'3681',NULL,NULL,0.00,3000.00,'2026-08-05 10:26:48','2026-08-05 10:26:48',0,NULL,0.00,0.00),(97,11,32,NULL,'2026-07-06 00:00:00',NULL,'3682',NULL,NULL,0.00,600.00,'2026-08-05 10:28:53','2026-08-05 10:28:53',0,NULL,0.00,0.00),(98,11,32,NULL,'2026-07-04 00:00:00',NULL,'3683',NULL,NULL,0.00,2535.00,'2026-08-05 10:29:43','2026-08-05 10:29:43',0,NULL,0.00,0.00),(99,11,32,NULL,'2026-07-06 00:00:00',NULL,'3685',NULL,NULL,0.00,900.00,'2026-08-05 10:30:54','2026-08-05 10:30:54',0,NULL,0.00,0.00),(100,11,32,NULL,'2026-07-07 00:00:00',NULL,'3686',NULL,NULL,0.00,200.00,'2026-08-05 10:31:28','2026-08-05 10:31:28',0,NULL,0.00,0.00),(101,11,32,NULL,'2026-07-06 00:00:00',NULL,'3687',NULL,NULL,0.00,750.00,'2026-08-05 10:32:30','2026-08-05 10:32:30',0,NULL,0.00,0.00),(102,11,32,NULL,'2026-07-06 00:00:00',NULL,'3688',NULL,NULL,0.00,7500.00,'2026-08-05 10:33:54','2026-08-05 10:33:54',0,NULL,0.00,0.00),(103,11,32,NULL,'2026-07-06 00:00:00',NULL,'3688',NULL,NULL,0.00,7500.00,'2026-08-05 10:33:54','2026-08-05 10:33:54',0,NULL,0.00,0.00),(104,11,32,NULL,'2026-07-06 00:00:00',NULL,'3689',NULL,NULL,0.00,750.00,'2026-08-05 10:34:33','2026-08-05 10:34:33',0,NULL,0.00,0.00),(105,11,32,NULL,'2026-07-06 00:00:00',NULL,'3690',NULL,NULL,0.00,3600.00,'2026-08-05 10:35:05','2026-08-05 10:35:05',0,NULL,0.00,0.00),(106,11,32,NULL,'2026-07-06 00:00:00',NULL,'3691',NULL,NULL,0.00,800.00,'2026-08-05 10:35:42','2026-08-05 10:35:42',0,NULL,0.00,0.00),(107,11,32,NULL,'2026-07-07 00:00:00',NULL,'3692',NULL,NULL,0.00,2000.00,'2026-08-05 10:36:04','2026-08-05 10:36:04',0,NULL,0.00,0.00),(108,11,32,NULL,'2026-07-07 00:00:00',NULL,'3693',NULL,NULL,0.00,1200.00,'2026-08-05 10:37:29','2026-08-05 10:37:29',0,NULL,0.00,0.00),(109,11,32,NULL,'2026-07-07 00:00:00',NULL,'3694',NULL,NULL,0.00,1300.00,'2026-08-05 10:37:50','2026-08-05 10:37:50',0,NULL,0.00,0.00),(110,11,32,NULL,'2026-07-07 00:00:00',NULL,'3695',NULL,NULL,0.00,3500.00,'2026-08-05 10:38:19','2026-08-05 10:38:19',0,NULL,0.00,0.00),(111,11,32,NULL,'2026-07-08 00:00:00',NULL,'3696',NULL,NULL,0.00,400.00,'2026-08-05 10:38:45','2026-08-05 10:38:45',0,NULL,0.00,0.00),(112,11,32,NULL,'2026-07-08 00:00:00',NULL,'3697',NULL,NULL,0.00,5880.00,'2026-08-05 10:39:22','2026-08-05 10:39:22',0,NULL,0.00,0.00),(113,11,32,NULL,'2026-07-08 00:00:00',NULL,'3698',NULL,NULL,0.00,600.00,'2026-08-05 10:40:03','2026-08-05 10:40:03',0,NULL,0.00,0.00),(114,11,32,NULL,'2026-07-08 00:00:00',NULL,'3699',NULL,NULL,0.00,800.00,'2026-08-05 10:40:24','2026-08-05 10:40:24',0,NULL,0.00,0.00),(115,11,32,NULL,'2026-07-08 00:00:00',NULL,'3700',NULL,NULL,0.00,1840.00,'2026-08-05 10:40:48','2026-08-05 10:40:48',0,NULL,0.00,0.00),(116,11,32,NULL,'2026-07-09 00:00:00',NULL,'3801',NULL,NULL,0.00,4200.00,'2026-08-05 10:41:41','2026-08-05 10:41:41',0,NULL,0.00,0.00),(117,11,32,NULL,'2026-07-09 00:00:00',NULL,'3802',NULL,NULL,0.00,3500.00,'2026-08-05 10:42:15','2026-08-05 10:42:15',0,NULL,0.00,0.00),(118,11,32,NULL,'2026-07-10 00:00:00',NULL,'3803',NULL,NULL,0.00,4020.00,'2026-08-05 10:42:50','2026-08-05 10:42:50',0,NULL,0.00,0.00),(119,11,32,NULL,'2026-07-10 00:00:00',NULL,'3804',NULL,NULL,0.00,1500.00,'2026-08-05 10:43:16','2026-08-05 10:43:16',0,NULL,0.00,0.00),(120,11,32,NULL,'2026-07-11 00:00:00',NULL,'3805',NULL,NULL,0.00,1170.00,'2026-08-05 10:43:44','2026-08-05 10:43:44',0,NULL,0.00,0.00),(121,11,32,NULL,'2026-07-13 00:00:00',NULL,'3806',NULL,NULL,0.00,2170.00,'2026-08-05 10:44:08','2026-08-05 10:44:08',0,NULL,0.00,0.00),(122,11,32,NULL,'2026-07-13 00:00:00',NULL,'3807',NULL,NULL,0.00,5050.00,'2026-08-05 10:44:36','2026-08-05 10:44:36',0,NULL,0.00,0.00),(123,11,32,NULL,'2026-07-13 00:00:00',NULL,'3808',NULL,NULL,0.00,6600.00,'2026-08-05 10:45:00','2026-08-05 10:45:00',0,NULL,0.00,0.00),(124,11,32,NULL,'2026-07-13 00:00:00',NULL,'3809',NULL,NULL,0.00,1960.00,'2026-08-05 10:45:32','2026-08-05 10:45:32',0,NULL,0.00,0.00),(125,11,39,NULL,'2026-07-14 00:00:00',NULL,'3810',NULL,NULL,0.00,32500.00,'2026-08-05 10:46:12','2026-08-06 17:14:50',0,NULL,0.00,0.00),(126,11,32,NULL,'2026-07-14 00:00:00',NULL,'3811',NULL,NULL,0.00,500.00,'2026-08-05 10:48:14','2026-08-05 10:48:14',0,NULL,0.00,0.00),(127,11,32,NULL,'2026-07-14 00:00:00',NULL,'3812',NULL,NULL,0.00,1320.00,'2026-08-05 10:48:37','2026-08-05 10:48:37',0,NULL,0.00,0.00),(128,11,32,NULL,'2026-07-14 00:00:00',NULL,'3813',NULL,NULL,0.00,900.00,'2026-08-05 10:49:08','2026-08-05 10:49:08',0,NULL,0.00,0.00),(129,11,26,NULL,'2026-07-07 00:00:00',NULL,'3814',NULL,NULL,0.00,19800.00,'2026-08-05 10:49:45','2026-08-05 10:49:45',0,NULL,0.00,0.00),(130,11,32,NULL,'2026-07-14 00:00:00',NULL,'3815',NULL,NULL,0.00,17760.00,'2026-08-05 10:50:11','2026-08-05 10:50:26',0,NULL,0.00,0.00),(131,11,32,NULL,'2026-07-14 00:00:00',NULL,'3816',NULL,NULL,0.00,2520.00,'2026-08-05 10:50:53','2026-08-05 10:50:53',0,NULL,0.00,0.00),(132,11,32,NULL,'2026-07-15 00:00:00',NULL,'3817',NULL,NULL,0.00,760.00,'2026-08-05 10:51:22','2026-08-05 10:51:22',0,NULL,0.00,0.00),(133,11,32,NULL,'2026-07-15 00:00:00',NULL,'3818',NULL,NULL,0.00,7375.00,'2026-08-05 10:51:46','2026-08-05 10:51:46',0,NULL,0.00,0.00),(134,11,32,NULL,'2026-06-15 00:00:00',NULL,'3819',NULL,NULL,0.00,4000.00,'2026-08-05 10:52:16','2026-08-05 10:52:16',0,NULL,0.00,0.00),(135,11,32,NULL,'2026-06-15 00:00:00',NULL,'3819',NULL,NULL,0.00,4000.00,'2026-08-05 10:52:16','2026-08-05 10:52:16',0,NULL,0.00,0.00),(136,11,32,NULL,'2026-06-15 00:00:00',NULL,'3819',NULL,NULL,0.00,4000.00,'2026-08-05 10:52:16','2026-08-05 10:52:16',0,NULL,0.00,0.00),(137,11,32,NULL,'2026-07-15 00:00:00',NULL,'3820',NULL,NULL,0.00,300.00,'2026-08-05 10:52:41','2026-08-05 10:52:41',0,NULL,0.00,0.00),(138,11,32,NULL,'2026-07-15 00:00:00',NULL,'3821',NULL,NULL,0.00,1040.00,'2026-08-05 10:53:16','2026-08-05 10:53:16',0,NULL,0.00,0.00),(139,11,32,NULL,'2026-07-15 00:00:00',NULL,'3821',NULL,NULL,0.00,1040.00,'2026-08-05 10:53:16','2026-08-05 10:53:16',0,NULL,0.00,0.00),(140,11,32,NULL,'2026-07-16 00:00:00',NULL,'3822',NULL,NULL,0.00,3000.00,'2026-08-05 10:53:48','2026-08-05 10:53:48',0,NULL,0.00,0.00),(141,11,32,NULL,'2026-07-16 00:00:00',NULL,'3823',NULL,NULL,0.00,300.00,'2026-08-05 10:54:19','2026-08-05 10:54:19',0,NULL,0.00,0.00),(142,11,32,NULL,'2026-07-16 00:00:00',NULL,'3824',NULL,NULL,0.00,320.00,'2026-08-05 10:54:42','2026-08-05 10:54:42',0,NULL,0.00,0.00),(143,11,32,NULL,'2026-07-17 00:00:00',NULL,'3825',NULL,NULL,0.00,120.00,'2026-08-05 10:55:13','2026-08-05 10:55:13',0,NULL,0.00,0.00),(144,11,32,NULL,'2026-07-17 00:00:00',NULL,'3826',NULL,NULL,0.00,1500.00,'2026-08-05 10:55:39','2026-08-05 10:55:39',0,NULL,0.00,0.00),(145,11,32,NULL,'2026-07-17 00:00:00',NULL,'3827',NULL,NULL,0.00,1200.00,'2026-08-05 10:56:09','2026-08-05 10:56:09',0,NULL,0.00,0.00),(146,11,32,NULL,'2026-07-17 00:00:00',NULL,'3829',NULL,NULL,0.00,500.00,'2026-08-05 10:56:42','2026-08-05 10:56:42',0,NULL,0.00,0.00),(147,11,32,NULL,'2026-07-17 00:00:00',NULL,'3830',NULL,NULL,0.00,300.00,'2026-08-05 10:57:06','2026-08-05 10:57:06',0,NULL,0.00,0.00),(148,11,32,NULL,'2026-07-18 00:00:00',NULL,'3831',NULL,NULL,0.00,400.00,'2026-08-05 10:57:34','2026-08-05 10:57:34',0,NULL,0.00,0.00),(149,11,32,NULL,'2026-07-18 00:00:00',NULL,'3832',NULL,NULL,0.00,100.00,'2026-08-05 10:57:57','2026-08-05 10:57:57',0,NULL,0.00,0.00),(150,11,32,NULL,'2026-07-20 00:00:00',NULL,'3833',NULL,NULL,0.00,480.00,'2026-08-05 10:58:26','2026-08-05 10:58:26',0,NULL,0.00,0.00),(151,11,32,NULL,'2026-07-20 00:00:00',NULL,'3834',NULL,NULL,0.00,3360.00,'2026-08-05 10:58:55','2026-08-05 10:58:55',0,NULL,0.00,0.00),(152,11,32,NULL,'2026-07-20 00:00:00',NULL,'3834',NULL,NULL,0.00,3360.00,'2026-08-05 10:58:55','2026-08-05 10:58:55',0,NULL,0.00,0.00),(153,11,32,NULL,'2026-07-20 00:00:00',NULL,'3835',NULL,NULL,0.00,1400.00,'2026-08-05 10:59:22','2026-08-05 10:59:22',0,NULL,0.00,0.00),(154,11,32,NULL,'2026-07-20 00:00:00',NULL,'3836',NULL,NULL,0.00,6400.00,'2026-08-05 11:00:02','2026-08-05 11:00:02',0,NULL,0.00,0.00),(155,11,32,NULL,'2026-07-20 00:00:00',NULL,'3837',NULL,NULL,0.00,760.00,'2026-08-05 11:00:49','2026-08-05 11:00:49',0,NULL,0.00,0.00),(156,11,32,NULL,'2026-07-21 00:00:00',NULL,'3838',NULL,NULL,0.00,3000.00,'2026-08-05 11:01:12','2026-08-05 11:01:12',0,NULL,0.00,0.00),(157,11,32,NULL,'2026-07-21 00:00:00',NULL,'3839',NULL,NULL,0.00,24000.00,'2026-08-05 11:03:37','2026-08-05 11:03:37',0,NULL,0.00,0.00),(158,11,32,NULL,'2026-07-21 00:00:00',NULL,'3839',NULL,NULL,0.00,24000.00,'2026-08-05 11:03:37','2026-08-05 11:03:37',0,NULL,0.00,0.00),(159,11,32,NULL,'2026-07-22 00:00:00',NULL,'3840',NULL,NULL,0.00,12000.00,'2026-08-05 11:04:18','2026-08-05 11:04:18',0,NULL,0.00,0.00),(160,11,32,NULL,'2026-07-21 00:00:00',NULL,'3841',NULL,NULL,0.00,2100.00,'2026-08-05 11:04:53','2026-08-05 11:04:53',0,NULL,0.00,0.00),(161,11,32,NULL,'2026-07-21 00:00:00',NULL,'3842',NULL,NULL,0.00,2920.00,'2026-08-05 11:05:44','2026-08-05 11:05:44',0,NULL,0.00,0.00),(162,11,32,NULL,'2026-07-21 00:00:00',NULL,'3843',NULL,NULL,0.00,150.00,'2026-08-05 11:06:17','2026-08-05 11:06:17',0,NULL,0.00,0.00),(163,11,32,NULL,'2026-07-21 00:00:00',NULL,'3844',NULL,NULL,0.00,700.00,'2026-08-05 11:06:52','2026-08-05 11:06:52',0,NULL,0.00,0.00),(164,11,32,NULL,'2026-07-22 00:00:00',NULL,'3845',NULL,NULL,0.00,1200.00,'2026-08-05 11:07:16','2026-08-05 11:07:16',0,NULL,0.00,0.00),(165,11,32,NULL,'2026-07-22 00:00:00',NULL,'3846',NULL,NULL,0.00,3280.00,'2026-08-05 11:07:49','2026-08-05 11:07:49',0,NULL,0.00,0.00),(166,11,32,NULL,'2026-07-23 00:00:00',NULL,'3847',NULL,NULL,0.00,7200.00,'2026-08-05 11:08:29','2026-08-05 11:08:29',0,NULL,0.00,0.00),(167,11,32,NULL,'2026-07-24 00:00:00',NULL,'3849',NULL,NULL,0.00,32000.00,'2026-08-05 11:09:11','2026-08-05 11:09:11',0,NULL,0.00,0.00),(168,11,32,NULL,'2026-07-24 00:00:00',NULL,'3850',NULL,NULL,0.00,28000.00,'2026-08-05 11:09:35','2026-08-05 11:09:35',0,NULL,0.00,0.00),(169,11,32,NULL,'2026-07-24 00:00:00',NULL,'3851',NULL,NULL,0.00,1100.00,'2026-08-05 11:10:04','2026-08-05 11:10:04',0,NULL,0.00,0.00),(170,11,32,NULL,'2026-07-24 00:00:00',NULL,'3852',NULL,NULL,0.00,3870.00,'2026-08-05 11:10:34','2026-08-05 11:10:34',0,NULL,0.00,0.00),(171,11,32,NULL,'2026-07-24 00:00:00',NULL,'3853',NULL,NULL,0.00,9810.00,'2026-08-05 11:11:04','2026-08-05 11:11:04',0,NULL,0.00,0.00),(172,11,32,NULL,'2026-07-24 00:00:00',NULL,'3854',NULL,NULL,0.00,1400.00,'2026-08-05 11:11:27','2026-08-05 11:11:27',0,NULL,0.00,0.00),(173,11,32,NULL,'2026-07-24 00:00:00',NULL,'3855',NULL,NULL,0.00,400.00,'2026-08-05 11:11:52','2026-08-05 11:11:52',0,NULL,0.00,0.00),(174,11,32,NULL,'2026-07-24 00:00:00',NULL,'3856',NULL,NULL,0.00,2560.00,'2026-08-05 11:12:17','2026-08-05 11:12:17',0,NULL,0.00,0.00),(175,11,32,NULL,'2026-07-24 00:00:00',NULL,'3857',NULL,NULL,0.00,750.00,'2026-08-05 11:13:19','2026-08-05 11:13:19',0,NULL,0.00,0.00),(176,11,32,NULL,'2026-07-24 00:00:00',NULL,'3858',NULL,NULL,0.00,11970.00,'2026-08-05 11:13:45','2026-08-05 11:13:45',0,NULL,0.00,0.00),(177,11,32,NULL,'2026-07-25 00:00:00',NULL,'3859',NULL,NULL,0.00,150.00,'2026-08-05 11:14:12','2026-08-05 11:14:12',0,NULL,0.00,0.00),(178,11,32,NULL,'2026-07-25 00:00:00',NULL,'3860',NULL,NULL,0.00,1900.00,'2026-08-05 11:14:43','2026-08-05 11:14:43',0,NULL,0.00,0.00),(179,11,32,NULL,'2026-07-25 00:00:00',NULL,'3861',NULL,NULL,0.00,4020.00,'2026-08-05 11:15:13','2026-08-05 11:15:13',0,NULL,0.00,0.00),(180,11,32,NULL,'2026-07-25 00:00:00',NULL,'3863',NULL,NULL,0.00,3240.00,'2026-08-05 11:15:42','2026-08-05 11:15:42',0,NULL,0.00,0.00),(181,11,32,NULL,'2026-07-27 00:00:00',NULL,'3864',NULL,NULL,0.00,8400.00,'2026-08-05 11:16:06','2026-08-05 11:16:06',0,NULL,0.00,0.00),(182,11,32,NULL,'2026-07-27 00:00:00',NULL,'3865',NULL,NULL,0.00,10020.00,'2026-08-05 11:17:35','2026-08-05 11:17:35',0,NULL,0.00,0.00),(183,11,32,NULL,'2026-07-27 00:00:00',NULL,'3866',NULL,NULL,0.00,2300.00,'2026-08-05 11:18:22','2026-08-05 11:18:22',0,NULL,0.00,0.00),(184,11,32,NULL,'2026-07-28 00:00:00',NULL,'3868',NULL,NULL,0.00,5000.00,'2026-08-05 11:19:02','2026-08-05 11:19:02',0,NULL,0.00,0.00),(185,11,32,NULL,'2026-07-27 00:00:00',NULL,'3869',NULL,NULL,0.00,3600.00,'2026-08-05 11:19:39','2026-08-05 11:19:39',0,NULL,0.00,0.00),(186,11,11,NULL,'2026-07-28 00:00:00',NULL,'3870',NULL,NULL,0.00,4500.00,'2026-08-05 11:22:59','2026-08-05 11:22:59',0,NULL,0.00,0.00),(187,11,32,NULL,'2026-07-28 00:00:00',NULL,'3871',NULL,NULL,0.00,8750.00,'2026-08-05 11:23:29','2026-08-05 11:23:29',0,NULL,0.00,0.00),(188,11,32,NULL,'2026-07-28 00:00:00',NULL,'3872',NULL,NULL,0.00,17500.00,'2026-08-05 11:24:04','2026-08-05 11:24:04',0,NULL,0.00,0.00),(189,11,7,NULL,'2026-07-30 00:00:00',NULL,'3873',NULL,NULL,0.00,2160.00,'2026-08-05 11:24:39','2026-08-05 11:24:39',0,NULL,0.00,0.00),(190,11,32,NULL,'2026-07-30 00:00:00',NULL,'3874',NULL,NULL,0.00,320.00,'2026-08-05 11:25:17','2026-08-05 11:25:17',0,NULL,0.00,0.00),(191,11,32,NULL,'2026-07-30 00:00:00',NULL,'3875',NULL,NULL,0.00,800.00,'2026-08-05 11:25:42','2026-08-05 11:25:42',0,NULL,0.00,0.00),(192,11,32,NULL,'2026-07-30 00:00:00',NULL,'3876',NULL,NULL,0.00,1500.00,'2026-08-05 11:26:10','2026-08-05 11:26:10',0,NULL,0.00,0.00),(193,11,32,NULL,'2026-07-30 00:00:00',NULL,'3877',NULL,NULL,0.00,2000.00,'2026-08-05 11:26:45','2026-08-05 11:26:45',0,NULL,0.00,0.00),(194,11,32,NULL,'2026-07-30 00:00:00',NULL,'3878',NULL,NULL,0.00,1320.00,'2026-08-05 11:27:08','2026-08-05 11:27:08',0,NULL,0.00,0.00),(195,11,32,NULL,'2026-07-30 00:00:00',NULL,'3879',NULL,NULL,0.00,28160.00,'2026-08-05 11:27:39','2026-08-05 11:27:39',0,NULL,0.00,0.00),(196,11,32,NULL,'2026-07-31 00:00:00',NULL,'3880',NULL,NULL,0.00,1040.00,'2026-08-05 11:28:06','2026-08-05 11:28:06',0,NULL,0.00,0.00),(197,11,32,NULL,'2026-07-31 00:00:00',NULL,'3881',NULL,NULL,0.00,1500.00,'2026-08-05 11:28:32','2026-08-05 11:28:32',0,NULL,0.00,0.00),(198,11,32,NULL,'2026-07-31 00:00:00',NULL,'3882',NULL,NULL,0.00,500.00,'2026-08-05 11:29:05','2026-08-05 11:29:05',0,NULL,0.00,0.00),(199,11,33,NULL,'2026-07-01 00:00:00',NULL,'3637',NULL,NULL,0.00,2200.00,'2026-08-06 10:13:07','2026-08-06 10:13:07',0,NULL,0.00,0.00),(200,11,33,NULL,'2026-07-01 00:00:00',NULL,'3638',NULL,NULL,0.00,2380.00,'2026-08-06 10:13:36','2026-08-06 10:13:36',0,NULL,0.00,0.00),(201,11,33,NULL,'2026-07-01 00:00:00',NULL,'3639',NULL,NULL,0.00,1000.00,'2026-08-06 10:13:37','2026-08-06 11:01:05',0,NULL,0.00,0.00),(202,11,33,NULL,'2026-07-01 00:00:00',NULL,'3640',NULL,NULL,0.00,3600.00,'2026-08-06 11:01:35','2026-08-06 11:01:35',0,NULL,0.00,0.00),(203,11,33,NULL,'2026-07-02 00:00:00',NULL,'3642',NULL,NULL,0.00,4200.00,'2026-08-06 11:02:49','2026-08-06 11:02:49',0,NULL,0.00,0.00),(204,11,33,NULL,'2026-07-04 00:00:00',NULL,'3645',NULL,NULL,0.00,2400.00,'2026-08-06 11:03:29','2026-08-06 11:03:29',0,NULL,0.00,0.00),(205,11,33,NULL,'2026-07-04 00:00:00',NULL,'3646',NULL,NULL,0.00,3000.00,'2026-08-06 11:03:54','2026-08-06 11:03:54',0,NULL,0.00,0.00),(206,11,33,NULL,'2026-07-04 00:00:00',NULL,'3647',NULL,NULL,0.00,3000.00,'2026-08-06 11:23:12','2026-08-06 11:24:00',0,NULL,0.00,0.00),(207,11,33,NULL,'2026-07-04 00:00:00',NULL,'3648',NULL,NULL,0.00,2700.00,'2026-08-06 11:23:38','2026-08-06 11:23:38',0,NULL,0.00,0.00),(208,11,33,NULL,'2026-07-04 00:00:00',NULL,'3649',NULL,NULL,0.00,3800.00,'2026-08-06 11:24:46','2026-08-06 11:24:46',0,NULL,0.00,0.00),(209,11,33,NULL,'2026-07-06 00:00:00',NULL,'3650',NULL,NULL,0.00,2000.00,'2026-08-06 11:25:14','2026-08-06 11:25:14',0,NULL,0.00,0.00),(210,11,33,NULL,'2026-07-07 00:00:00',NULL,'3651',NULL,NULL,0.00,3500.00,'2026-08-06 11:26:51','2026-08-06 11:26:51',0,NULL,0.00,0.00),(211,11,33,NULL,'2026-07-07 00:00:00',NULL,'3652',NULL,NULL,0.00,3050.00,'2026-08-06 11:27:15','2026-08-06 11:27:15',0,NULL,0.00,0.00),(212,11,33,NULL,'2026-07-07 00:00:00',NULL,'3653',NULL,NULL,0.00,900.00,'2026-08-06 11:27:48','2026-08-06 11:27:48',0,NULL,0.00,0.00),(213,11,33,NULL,'2026-07-07 00:00:00',NULL,'3655',NULL,NULL,0.00,12480.00,'2026-08-06 11:28:19','2026-08-06 11:28:19',0,NULL,0.00,0.00),(214,11,33,NULL,'2026-07-08 00:00:00',NULL,'3656',NULL,NULL,0.00,4800.00,'2026-08-06 11:33:18','2026-08-06 11:33:18',0,NULL,0.00,0.00),(215,11,33,NULL,'2026-07-09 00:00:00',NULL,'3657',NULL,NULL,0.00,2500.00,'2026-08-06 11:33:52','2026-08-06 11:33:52',0,NULL,0.00,0.00),(216,11,33,NULL,'2026-07-09 00:00:00',NULL,'3658',NULL,NULL,0.00,1800.00,'2026-08-06 11:34:22','2026-08-06 11:34:22',0,NULL,0.00,0.00),(217,11,33,NULL,'2026-07-09 00:00:00',NULL,'3659',NULL,NULL,0.00,3000.00,'2026-08-06 11:34:52','2026-08-06 11:34:52',0,NULL,0.00,0.00),(218,11,33,NULL,'2026-07-10 00:00:00',NULL,'3660',NULL,NULL,0.00,1500.00,'2026-08-06 11:35:15','2026-08-06 11:35:15',0,NULL,0.00,0.00),(219,11,33,NULL,'2026-07-11 00:00:00',NULL,'3661',NULL,NULL,0.00,4600.00,'2026-08-06 11:35:42','2026-08-06 11:35:42',0,NULL,0.00,0.00),(220,11,33,NULL,'2026-07-11 00:00:00',NULL,'3662',NULL,NULL,0.00,14500.00,'2026-08-06 11:36:09','2026-08-06 11:36:09',0,NULL,0.00,0.00),(221,11,33,NULL,'2026-07-11 00:00:00',NULL,'3662',NULL,NULL,0.00,14500.00,'2026-08-06 11:36:09','2026-08-06 11:36:09',0,NULL,0.00,0.00),(222,11,33,NULL,'2026-07-11 00:00:00',NULL,'3663',NULL,NULL,0.00,13000.00,'2026-08-06 11:36:35','2026-08-06 11:36:35',0,NULL,0.00,0.00),(223,11,33,NULL,'2026-07-13 00:00:00',NULL,'3664',NULL,NULL,0.00,2000.00,'2026-08-06 11:37:55','2026-08-06 11:37:55',0,NULL,0.00,0.00),(224,11,33,NULL,'2026-07-13 00:00:00',NULL,'3665',NULL,NULL,0.00,1800.00,'2026-08-06 11:38:22','2026-08-06 11:38:22',0,NULL,0.00,0.00),(225,11,33,NULL,'2026-07-13 00:00:00',NULL,'3666',NULL,NULL,0.00,11000.00,'2026-08-06 11:38:58','2026-08-06 11:38:58',0,NULL,0.00,0.00),(226,11,33,NULL,'2026-07-13 00:00:00',NULL,'3667',NULL,NULL,0.00,14040.00,'2026-08-06 11:39:27','2026-08-06 11:39:27',0,NULL,0.00,0.00),(227,11,33,NULL,'2026-07-13 00:00:00',NULL,'3667',NULL,NULL,0.00,14040.00,'2026-08-06 11:39:28','2026-08-06 11:39:28',0,NULL,0.00,0.00),(228,11,33,NULL,'2026-07-13 00:00:00',NULL,'3667',NULL,NULL,0.00,14040.00,'2026-08-06 11:39:28','2026-08-06 11:39:28',0,NULL,0.00,0.00),(229,11,33,NULL,'2026-07-14 00:00:00',NULL,'3668',NULL,NULL,0.00,800.00,'2026-08-06 11:39:57','2026-08-06 11:39:57',0,NULL,0.00,0.00),(230,11,33,NULL,'2026-07-15 00:00:00',NULL,'3669',NULL,NULL,0.00,6000.00,'2026-08-06 11:40:29','2026-08-06 11:40:29',0,NULL,0.00,0.00),(231,11,33,NULL,'2026-07-15 00:00:00',NULL,'3670',NULL,NULL,0.00,10200.00,'2026-08-06 11:40:55','2026-08-06 11:40:55',0,NULL,0.00,0.00),(232,11,33,NULL,'2026-07-15 00:00:00',NULL,'3671',NULL,NULL,0.00,2000.00,'2026-08-06 11:46:18','2026-08-06 11:46:18',0,NULL,0.00,0.00),(233,11,33,NULL,'2026-07-16 00:00:00',NULL,'3672',NULL,NULL,0.00,4500.00,'2026-08-06 11:46:46','2026-08-06 11:46:46',0,NULL,0.00,0.00),(234,11,33,NULL,'2026-07-16 00:00:00',NULL,'3673',NULL,NULL,0.00,700.00,'2026-08-06 11:47:11','2026-08-06 11:47:11',0,NULL,0.00,0.00),(235,11,33,NULL,'2026-07-16 00:00:00',NULL,'3673',NULL,NULL,0.00,700.00,'2026-08-06 11:47:11','2026-08-06 11:47:11',0,NULL,0.00,0.00),(236,11,33,NULL,'2026-07-16 00:00:00',NULL,'3674',NULL,NULL,0.00,1100.00,'2026-08-06 11:47:35','2026-08-06 11:47:35',0,NULL,0.00,0.00),(237,11,33,NULL,'2026-07-16 00:00:00',NULL,'3675',NULL,NULL,0.00,12000.00,'2026-08-06 11:48:31','2026-08-06 11:48:31',0,NULL,0.00,0.00),(238,11,33,NULL,'2026-07-17 00:00:00',NULL,'3676',NULL,NULL,0.00,900.00,'2026-08-06 11:49:07','2026-08-06 11:49:07',0,NULL,0.00,0.00),(239,11,33,NULL,'2026-07-17 00:00:00',NULL,'3677',NULL,NULL,0.00,6000.00,'2026-08-06 11:55:44','2026-08-06 11:55:44',0,NULL,0.00,0.00),(240,11,33,NULL,'2026-07-16 00:00:00',NULL,'3678',NULL,NULL,0.00,9800.00,'2026-08-06 11:56:25','2026-08-06 11:56:25',0,NULL,0.00,0.00),(241,11,33,NULL,'2026-07-17 00:00:00',NULL,'3679',NULL,NULL,0.00,2720.00,'2026-08-06 11:57:01','2026-08-06 11:57:01',0,NULL,0.00,0.00),(242,11,33,NULL,'2026-07-18 00:00:00',NULL,'3680',NULL,NULL,0.00,6750.00,'2026-08-06 11:57:39','2026-08-06 11:57:39',0,NULL,0.00,0.00),(243,11,33,NULL,'2026-07-18 00:00:00',NULL,'3681',NULL,NULL,0.00,4000.00,'2026-08-06 11:58:11','2026-08-06 11:58:11',0,NULL,0.00,0.00),(244,11,33,NULL,'2026-07-20 00:00:00',NULL,'3682',NULL,NULL,0.00,15000.00,'2026-08-06 11:58:40','2026-08-06 11:58:40',0,NULL,0.00,0.00),(245,11,33,NULL,'2026-07-20 00:00:00',NULL,'3683',NULL,NULL,0.00,10500.00,'2026-08-06 11:59:13','2026-08-06 11:59:13',0,NULL,0.00,0.00),(246,11,33,NULL,'2026-07-20 00:00:00',NULL,'3684',NULL,NULL,0.00,800.00,'2026-08-06 11:59:41','2026-08-06 11:59:41',0,NULL,0.00,0.00),(247,11,33,NULL,'2026-07-20 00:00:00',NULL,'3685',NULL,NULL,0.00,3600.00,'2026-08-06 12:00:07','2026-08-06 12:00:07',0,NULL,0.00,0.00),(248,11,33,NULL,'2026-07-21 00:00:00',NULL,'3686',NULL,NULL,0.00,6000.00,'2026-08-06 12:00:31','2026-08-06 12:00:31',0,NULL,0.00,0.00),(249,11,33,NULL,'2026-07-21 00:00:00',NULL,'3687',NULL,NULL,0.00,29750.00,'2026-08-06 12:02:37','2026-08-06 12:02:37',0,NULL,0.00,0.00),(250,11,33,NULL,'2026-07-22 00:00:00',NULL,'3688',NULL,NULL,0.00,6000.00,'2026-08-06 12:03:01','2026-08-06 12:03:01',0,NULL,0.00,0.00),(251,11,33,NULL,'2026-07-23 00:00:00',NULL,'3689',NULL,NULL,0.00,7000.00,'2026-08-06 12:03:35','2026-08-06 12:03:35',0,NULL,0.00,0.00),(252,11,33,NULL,'2026-07-23 00:00:00',NULL,'3690',NULL,NULL,0.00,3060.00,'2026-08-06 12:08:10','2026-08-06 12:08:10',0,NULL,0.00,0.00),(253,11,33,NULL,'2026-07-23 00:00:00',NULL,'3691',NULL,NULL,0.00,1800.00,'2026-08-06 12:08:43','2026-08-06 12:08:43',0,NULL,0.00,0.00),(254,11,33,NULL,'2026-07-23 00:00:00',NULL,'3692',NULL,NULL,0.00,12000.00,'2026-08-06 12:09:07','2026-08-06 12:09:07',0,NULL,0.00,0.00),(255,11,33,NULL,'2026-07-23 00:00:00',NULL,'3693',NULL,NULL,0.00,6000.00,'2026-08-06 12:09:56','2026-08-06 12:09:56',0,NULL,0.00,0.00),(256,11,33,NULL,'2026-07-24 00:00:00',NULL,'3694',NULL,NULL,0.00,6400.00,'2026-08-06 12:10:23','2026-08-06 12:10:23',0,NULL,0.00,0.00),(257,11,33,NULL,'2026-07-24 00:00:00',NULL,'3695',NULL,NULL,0.00,45000.00,'2026-08-06 12:10:59','2026-08-06 12:10:59',0,NULL,0.00,0.00),(258,11,33,NULL,'2026-07-31 00:00:00',NULL,'3696',NULL,NULL,0.00,49500.00,'2026-08-06 12:11:24','2026-08-06 12:11:24',0,NULL,0.00,0.00),(259,11,33,NULL,'2026-07-25 00:00:00',NULL,'3697',NULL,NULL,0.00,4800.00,'2026-08-06 12:12:04','2026-08-06 12:12:04',0,NULL,0.00,0.00),(260,11,33,NULL,'2026-07-25 00:00:00',NULL,'3698',NULL,NULL,0.00,5250.00,'2026-08-06 12:12:29','2026-08-06 12:12:29',0,NULL,0.00,0.00),(261,11,33,NULL,'2026-07-27 00:00:00',NULL,'3699',NULL,NULL,0.00,1500.00,'2026-08-06 12:14:46','2026-08-06 12:14:46',0,NULL,0.00,0.00),(262,11,33,NULL,'2026-07-27 00:00:00',NULL,'3700',NULL,NULL,0.00,3500.00,'2026-08-06 12:15:16','2026-08-06 12:15:16',0,NULL,0.00,0.00),(263,11,33,NULL,'2026-07-28 00:00:00',NULL,'3701',NULL,NULL,0.00,2000.00,'2026-08-06 12:17:00','2026-08-06 12:17:00',0,NULL,0.00,0.00),(264,11,33,NULL,'2026-07-30 00:00:00',NULL,'3702',NULL,NULL,0.00,1500.00,'2026-08-06 12:17:32','2026-08-06 12:17:32',0,NULL,0.00,0.00),(265,11,33,NULL,'2026-07-30 00:00:00',NULL,'3703',NULL,NULL,0.00,2000.00,'2026-08-06 12:17:57','2026-08-06 12:17:57',0,NULL,0.00,0.00),(266,11,33,NULL,'2026-07-30 00:00:00',NULL,'3704',NULL,NULL,0.00,6000.00,'2026-08-06 12:18:27','2026-08-06 12:18:27',0,NULL,0.00,0.00),(267,11,33,NULL,'2026-07-31 00:00:00',NULL,'3705',NULL,NULL,0.00,7500.00,'2026-08-06 12:19:43','2026-08-06 12:19:43',0,NULL,0.00,0.00),(268,11,33,NULL,'2026-07-31 00:00:00',NULL,'16320',NULL,NULL,0.00,16320.00,'2026-08-06 12:20:16','2026-08-06 12:20:16',0,NULL,0.00,0.00),(269,11,41,NULL,'2026-07-02 00:00:00',NULL,'522',NULL,NULL,0.00,14000.00,'2026-08-07 10:51:30','2026-08-11 10:06:28',0,NULL,0.00,0.00),(270,11,38,NULL,'2026-07-02 00:00:00',NULL,'523',NULL,NULL,0.00,44800.00,'2026-08-07 10:52:08','2026-08-07 10:52:08',0,NULL,0.00,0.00),(271,11,42,NULL,'2026-07-02 00:00:00',NULL,'524',NULL,NULL,0.00,120000.00,'2026-08-07 10:54:55','2026-08-07 10:54:55',0,NULL,0.00,0.00),(272,11,43,NULL,'2026-07-02 00:00:00',NULL,'525',NULL,NULL,0.00,26000.00,'2026-08-07 10:58:10','2026-08-07 10:58:10',0,NULL,0.00,0.00),(273,11,44,NULL,'2026-07-03 00:00:00',NULL,'526',NULL,NULL,0.00,98000.00,'2026-08-07 11:02:40','2026-08-07 11:02:40',0,NULL,0.00,0.00),(274,11,45,NULL,'2026-07-06 00:00:00',NULL,'527',NULL,NULL,0.00,433228.00,'2026-08-07 11:05:11','2026-08-07 11:05:11',0,NULL,0.00,0.00),(275,11,46,NULL,'2026-08-07 00:00:00',NULL,'528',NULL,NULL,0.00,16500.00,'2026-08-07 11:07:15','2026-08-07 11:07:15',0,NULL,0.00,0.00),(276,11,42,NULL,'2026-07-04 00:00:00',NULL,'529',NULL,NULL,0.00,96000.00,'2026-08-07 11:07:56','2026-08-07 11:07:56',0,NULL,0.00,0.00),(277,11,47,NULL,'2026-07-16 00:00:00',NULL,'530',NULL,NULL,0.00,100000.00,'2026-08-07 11:09:32','2026-08-07 11:09:40',0,NULL,0.00,0.00),(278,11,36,NULL,'2026-07-16 00:00:00',NULL,'531',NULL,NULL,0.00,8200.00,'2026-08-07 11:10:19','2026-08-07 11:10:19',0,NULL,0.00,0.00),(279,11,48,NULL,'2026-07-17 00:00:00',NULL,'532',NULL,NULL,0.00,7200.00,'2026-08-07 11:19:29','2026-08-07 11:19:29',0,NULL,0.00,0.00),(280,11,35,NULL,'2026-07-17 00:00:00',NULL,'533',NULL,NULL,0.00,63500.00,'2026-08-07 11:20:07','2026-08-07 11:20:07',0,NULL,0.00,0.00),(281,11,36,NULL,'2026-07-17 00:00:00',NULL,'534',NULL,NULL,0.00,70000.00,'2026-08-07 11:21:21','2026-08-07 11:21:21',0,NULL,0.00,0.00),(282,11,50,NULL,'2026-07-24 00:00:00',NULL,'535',NULL,NULL,0.00,33510.00,'2026-08-07 11:27:15','2026-08-07 11:27:15',0,NULL,0.00,0.00),(283,11,48,NULL,'2026-07-24 00:00:00',NULL,'536',NULL,NULL,0.00,57600.00,'2026-08-07 11:27:52','2026-08-07 11:27:52',0,NULL,0.00,0.00),(284,11,51,NULL,'2026-07-24 00:00:00',NULL,'537',NULL,NULL,0.00,18000.00,'2026-08-07 11:29:00','2026-08-07 11:29:00',0,NULL,0.00,0.00),(285,11,49,NULL,'2026-07-28 00:00:00',NULL,'538',NULL,NULL,0.00,4200.00,'2026-08-07 11:29:47','2026-08-07 11:29:47',0,NULL,0.00,0.00),(286,11,36,NULL,'2026-08-07 00:00:00',NULL,'539',NULL,NULL,0.00,74400.00,'2026-08-07 11:30:31','2026-08-07 11:30:31',0,NULL,0.00,0.00),(287,11,40,NULL,'2026-07-30 00:00:00',NULL,'540',NULL,NULL,0.00,248000.00,'2026-08-07 11:31:50','2026-08-07 11:31:50',0,NULL,0.00,0.00),(288,11,10,NULL,'2026-07-15 00:00:00',NULL,'3759',NULL,NULL,0.00,5770.00,'2026-08-07 16:33:48','2026-08-07 16:33:48',0,NULL,0.00,0.00),(289,11,52,NULL,'2026-08-03 00:00:00',NULL,'3797',NULL,NULL,0.00,5660.00,'2026-08-10 10:57:20','2026-08-10 10:57:45',0,NULL,0.00,0.00),(290,11,6,NULL,'2026-08-03 00:00:00',NULL,'3798',NULL,NULL,0.00,4440.00,'2026-08-10 10:58:42','2026-08-10 10:58:42',0,NULL,0.00,0.00),(291,11,14,NULL,'2026-08-03 00:00:00',NULL,'3799',NULL,NULL,0.00,2100.00,'2026-08-10 10:59:13','2026-08-10 10:59:13',0,NULL,0.00,0.00),(292,11,3,NULL,'2026-08-03 00:00:00',NULL,'3800',NULL,NULL,0.00,9320.00,'2026-08-10 10:59:58','2026-08-10 11:03:03',0,NULL,0.00,0.00),(293,11,19,NULL,'2026-08-03 00:00:00',NULL,'3901',NULL,NULL,0.00,4830.00,'2026-08-10 11:01:17','2026-08-10 11:01:17',0,NULL,0.00,0.00),(294,11,4,NULL,'2026-08-03 00:00:00',NULL,'3902',NULL,NULL,0.00,1590.00,'2026-08-10 11:01:55','2026-08-10 11:01:55',0,NULL,0.00,0.00),(295,11,5,NULL,'2026-08-03 00:00:00',NULL,'3903',NULL,NULL,0.00,300.00,'2026-08-10 11:03:58','2026-08-10 11:03:58',0,NULL,0.00,0.00),(296,11,24,NULL,'2026-08-03 00:00:00',NULL,'3904',NULL,NULL,0.00,780.00,'2026-08-10 11:04:25','2026-08-11 10:04:28',0,NULL,0.00,0.00),(297,11,3,NULL,'2026-08-05 00:00:00',NULL,'3905',NULL,NULL,0.00,975.00,'2026-08-10 11:08:47','2026-08-10 11:08:47',0,NULL,0.00,0.00),(298,11,23,NULL,'2026-08-03 00:00:00',NULL,'3906',NULL,NULL,0.00,140.00,'2026-08-10 11:11:29','2026-08-10 11:11:29',0,NULL,0.00,0.00),(299,11,1,NULL,'2026-08-05 00:00:00',NULL,'3907',NULL,NULL,0.00,10565.00,'2026-08-10 11:12:29','2026-08-10 11:12:29',0,NULL,0.00,0.00),(300,11,14,NULL,'2026-08-05 00:00:00',NULL,'3908',NULL,NULL,0.00,5400.00,'2026-08-10 11:16:43','2026-08-11 13:03:56',0,NULL,0.00,0.00),(301,11,24,NULL,'2026-07-21 00:00:00',NULL,'3841',NULL,NULL,0.00,2100.00,'2026-08-11 12:08:48','2026-08-11 12:08:48',0,NULL,0.00,0.00),(302,11,6,NULL,'2026-08-05 00:00:00',NULL,'3909',NULL,NULL,0.00,1380.00,'2026-08-11 13:04:53','2026-08-11 13:04:53',0,NULL,0.00,0.00),(303,11,8,NULL,'2026-08-05 00:00:00',NULL,'3911',NULL,NULL,0.00,3640.00,'2026-08-11 13:05:46','2026-08-11 13:05:46',0,NULL,0.00,0.00),(304,11,19,NULL,'2026-08-05 00:00:00',NULL,'3912',NULL,NULL,0.00,11290.00,'2026-08-11 13:06:18','2026-08-11 13:06:18',0,NULL,0.00,0.00),(305,11,4,NULL,'2026-08-05 00:00:00',NULL,'3913',NULL,NULL,0.00,3420.00,'2026-08-11 13:06:50','2026-08-11 13:06:50',0,NULL,0.00,0.00),(306,11,5,NULL,'2026-08-05 00:00:00',NULL,'3914',NULL,NULL,0.00,11130.00,'2026-08-11 13:07:20','2026-08-11 13:07:20',0,NULL,0.00,0.00),(307,11,16,NULL,'2026-08-05 00:00:00',NULL,'3915',NULL,NULL,0.00,380.00,'2026-08-11 13:09:43','2026-08-11 13:09:43',0,NULL,0.00,0.00),(308,11,25,NULL,'2026-08-05 00:00:00',NULL,'3916',NULL,NULL,0.00,43890.00,'2026-08-11 13:10:40','2026-08-11 13:10:40',0,NULL,0.00,0.00),(309,11,3,NULL,'2026-08-10 00:00:00',NULL,'3917',NULL,NULL,0.00,16990.00,'2026-08-11 13:13:45','2026-08-11 13:13:45',0,NULL,0.00,0.00),(310,11,3,NULL,'2026-08-10 00:00:00',NULL,'3918',NULL,NULL,0.00,6390.00,'2026-08-11 13:14:10','2026-08-11 13:14:10',0,NULL,0.00,0.00),(311,11,6,NULL,'2026-08-10 00:00:00',NULL,'3919',NULL,NULL,0.00,3280.00,'2026-08-11 13:14:40','2026-08-11 13:14:40',0,NULL,0.00,0.00),(312,11,1,NULL,'2026-08-10 00:00:00',NULL,'3920',NULL,NULL,0.00,10000.00,'2026-08-11 13:15:45','2026-08-11 13:15:45',0,NULL,0.00,0.00),(313,11,45,NULL,'2026-08-11 00:00:00',NULL,'549',NULL,NULL,0.00,754000.00,'2026-08-11 14:44:27','2026-08-11 14:44:27',0,NULL,0.00,0.00),(314,11,53,NULL,'2026-08-11 00:00:00',NULL,'548',NULL,NULL,0.00,433000.00,'2026-08-11 16:46:07','2026-08-11 16:46:07',0,NULL,0.00,0.00),(315,11,4,NULL,'2026-08-12 00:00:00',NULL,'3923',NULL,NULL,0.00,11610.00,'2026-08-12 16:25:55','2026-08-12 16:27:29',0,NULL,0.00,0.00),(316,11,3,NULL,'2026-08-12 00:00:00',NULL,'3924',NULL,NULL,0.00,1120.00,'2026-08-12 16:26:24','2026-08-12 16:27:18',0,NULL,0.00,0.00),(317,11,5,NULL,'2026-08-12 00:00:00',NULL,'3925',NULL,NULL,0.00,1800.00,'2026-08-12 16:27:02','2026-08-12 16:27:02',0,NULL,0.00,0.00),(318,11,1,NULL,'2026-08-12 00:00:00',NULL,'3926',NULL,NULL,0.00,1605.00,'2026-08-12 16:28:43','2026-08-12 16:28:43',0,NULL,0.00,0.00),(319,11,7,NULL,'2026-08-12 00:00:00',NULL,'3927',NULL,NULL,0.00,180.00,'2026-08-12 16:29:19','2026-08-13 10:13:45',0,NULL,0.00,0.00),(320,11,6,NULL,'2026-08-12 00:00:00',NULL,'3928',NULL,NULL,0.00,4140.00,'2026-08-12 16:29:44','2026-08-12 16:30:46',0,NULL,0.00,0.00),(321,11,19,NULL,'2026-08-12 00:00:00',NULL,'3929',NULL,NULL,0.00,10590.00,'2026-08-12 16:30:38','2026-08-12 16:30:38',0,NULL,0.00,0.00),(322,11,18,NULL,'2026-08-12 00:00:00',NULL,'3921',NULL,NULL,0.00,15920.00,'2026-08-12 16:31:44','2026-08-12 16:31:44',0,NULL,0.00,0.00),(323,11,7,NULL,'2026-08-12 00:00:00',NULL,'3922',NULL,NULL,0.00,700.00,'2026-08-12 16:32:10','2026-08-12 16:32:10',0,NULL,0.00,0.00),(324,11,17,NULL,'2026-07-01 00:00:00',NULL,NULL,NULL,NULL,0.00,2440.00,'2026-08-13 10:16:57','2026-08-13 10:17:43',0,NULL,0.00,0.00),(325,11,17,NULL,'2026-07-01 00:00:00',NULL,NULL,NULL,NULL,0.00,2800.00,'2026-08-13 10:17:31','2026-08-13 10:17:31',0,NULL,0.00,0.00),(326,11,17,NULL,'2026-07-01 00:00:00',NULL,NULL,NULL,NULL,0.00,6680.00,'2026-08-13 10:18:08','2026-08-13 10:18:08',0,NULL,0.00,0.00),(327,11,17,NULL,'2026-07-01 00:00:00',NULL,NULL,NULL,NULL,0.00,2000.00,'2026-08-13 10:18:36','2026-08-13 10:18:36',0,NULL,0.00,0.00),(328,11,17,NULL,'2026-07-01 00:00:00',NULL,NULL,NULL,NULL,0.00,4020.00,'2026-08-13 10:19:01','2026-08-13 10:19:01',0,NULL,0.00,0.00),(329,11,17,NULL,'2026-07-17 00:00:00',NULL,NULL,NULL,NULL,0.00,31250.00,'2026-08-13 10:19:27','2026-08-13 10:19:27',0,NULL,0.00,0.00),(330,11,17,NULL,'2026-07-17 00:00:00',NULL,NULL,NULL,NULL,0.00,900.00,'2026-08-13 10:19:58','2026-08-13 10:19:58',0,NULL,0.00,0.00),(331,11,17,NULL,'2026-07-17 00:00:00',NULL,NULL,NULL,NULL,0.00,900.00,'2026-08-13 10:20:18','2026-08-13 10:20:18',0,NULL,0.00,0.00),(332,11,17,NULL,'2026-07-17 00:00:00',NULL,NULL,NULL,NULL,0.00,280.00,'2026-08-13 10:20:41','2026-08-13 10:20:41',0,NULL,0.00,0.00),(333,11,17,NULL,'2026-07-17 00:00:00',NULL,NULL,NULL,NULL,0.00,3000.00,'2026-08-13 10:21:00','2026-08-13 10:21:00',0,NULL,0.00,0.00),(334,11,17,NULL,'2026-07-17 00:00:00',NULL,NULL,NULL,NULL,0.00,10680.00,'2026-08-13 10:21:44','2026-08-13 10:21:44',0,NULL,0.00,0.00),(335,11,17,NULL,'2026-07-27 00:00:00',NULL,NULL,NULL,NULL,0.00,1800.00,'2026-08-13 10:22:07','2026-08-13 10:22:07',0,NULL,0.00,0.00),(336,11,17,NULL,'2026-07-27 00:00:00',NULL,NULL,NULL,NULL,0.00,200.00,'2026-08-13 10:23:23','2026-08-13 10:23:23',0,NULL,0.00,0.00),(337,11,17,NULL,'2026-07-27 00:00:00',NULL,NULL,NULL,NULL,0.00,1540.00,'2026-08-13 10:24:08','2026-08-13 10:24:08',0,NULL,0.00,0.00),(338,11,17,NULL,'2026-07-27 00:00:00',NULL,NULL,NULL,NULL,0.00,1200.00,'2026-08-13 10:24:33','2026-08-13 10:24:33',0,NULL,0.00,0.00),(339,11,17,NULL,'2026-08-03 00:00:00',NULL,NULL,NULL,NULL,0.00,800.00,'2026-08-13 10:25:03','2026-08-13 10:25:03',0,NULL,0.00,0.00),(340,11,17,NULL,'2026-08-03 00:00:00',NULL,NULL,NULL,NULL,0.00,15400.00,'2026-08-13 10:25:28','2026-08-13 10:25:28',0,NULL,0.00,0.00),(341,11,17,NULL,'2026-08-03 00:00:00',NULL,NULL,NULL,NULL,0.00,4770.00,'2026-08-13 10:25:58','2026-08-13 10:25:58',0,NULL,0.00,0.00),(342,11,32,NULL,'2026-08-01 00:00:00',NULL,'3883',NULL,NULL,0.00,660.00,'2026-08-13 11:16:31','2026-08-13 11:16:31',0,NULL,0.00,0.00),(343,11,32,NULL,'2026-08-01 00:00:00',NULL,'3884',NULL,NULL,0.00,1295.00,'2026-08-13 11:17:42','2026-08-13 11:17:42',0,NULL,0.00,0.00),(344,11,32,NULL,'2026-08-01 00:00:00',NULL,'3885',NULL,NULL,0.00,5000.00,'2026-08-13 11:19:08','2026-08-13 11:19:08',0,NULL,0.00,0.00),(345,11,32,NULL,'2026-08-01 00:00:00',NULL,'3886',NULL,NULL,0.00,5600.00,'2026-08-13 11:20:14','2026-08-13 11:20:14',0,NULL,0.00,0.00),(346,11,32,NULL,'2026-08-01 00:00:00',NULL,'3887',NULL,NULL,0.00,600.00,'2026-08-13 11:21:53','2026-08-13 11:21:53',0,NULL,0.00,0.00),(347,11,32,NULL,'2026-08-01 00:00:00',NULL,'3888',NULL,NULL,0.00,84445.00,'2026-08-13 11:22:55','2026-08-13 11:22:55',0,NULL,0.00,0.00),(348,11,7,NULL,'2026-08-03 00:00:00',NULL,'3889',NULL,NULL,0.00,2250.00,'2026-08-13 11:32:24','2026-08-13 11:32:24',0,NULL,0.00,0.00),(349,11,11,NULL,'2026-08-03 00:00:00',NULL,'3890',NULL,NULL,0.00,990.00,'2026-08-13 11:33:03','2026-08-13 11:33:03',0,NULL,0.00,0.00),(350,11,32,NULL,'2026-08-03 00:00:00',NULL,'3891',NULL,NULL,0.00,2400.00,'2026-08-13 11:34:13','2026-08-13 11:34:13',0,NULL,0.00,0.00),(351,11,32,NULL,'2026-08-03 00:00:00',NULL,'3892',NULL,NULL,0.00,1160.00,'2026-08-13 11:34:54','2026-08-13 11:34:54',0,NULL,0.00,0.00),(352,11,32,NULL,'2026-08-03 00:00:00',NULL,'3893',NULL,NULL,0.00,11310.00,'2026-08-13 11:35:50','2026-08-13 11:35:50',0,NULL,0.00,0.00),(353,11,32,NULL,'2026-08-03 00:00:00',NULL,'3894',NULL,NULL,0.00,1000.00,'2026-08-13 11:36:26','2026-08-13 11:36:26',0,NULL,0.00,0.00),(354,11,9,NULL,'2026-08-03 00:00:00',NULL,'3895',NULL,NULL,0.00,1480.00,'2026-08-13 11:39:01','2026-08-13 11:39:01',0,NULL,0.00,0.00),(355,11,32,NULL,'2026-08-04 00:00:00',NULL,'3897',NULL,NULL,0.00,14000.00,'2026-08-13 11:39:59','2026-08-13 11:39:59',0,NULL,0.00,0.00),(356,11,32,NULL,'2026-08-04 00:00:00',NULL,'3898',NULL,NULL,0.00,3600.00,'2026-08-13 11:40:36','2026-08-13 11:41:45',0,NULL,0.00,0.00),(357,11,32,NULL,'2026-08-04 00:00:00',NULL,'4202',NULL,NULL,0.00,2880.00,'2026-08-13 11:43:13','2026-08-13 11:43:13',0,NULL,0.00,0.00),(358,11,32,NULL,'2026-08-05 00:00:00',NULL,'4203',NULL,NULL,0.00,2120.00,'2026-08-13 11:45:16','2026-08-13 11:45:16',0,NULL,0.00,0.00),(359,11,32,NULL,'2026-08-05 00:00:00',NULL,'4204',NULL,NULL,0.00,2840.00,'2026-08-13 11:46:00','2026-08-13 11:46:00',0,NULL,0.00,0.00),(360,11,32,NULL,'2026-08-05 00:00:00',NULL,'4205',NULL,NULL,0.00,300.00,'2026-08-13 11:46:26','2026-08-13 11:46:26',0,NULL,0.00,0.00),(361,11,32,NULL,'2026-08-05 00:00:00',NULL,'4206',NULL,NULL,0.00,300.00,'2026-08-13 11:46:50','2026-08-13 11:46:50',0,NULL,0.00,0.00),(362,11,32,NULL,'2026-08-05 00:00:00',NULL,'4207',NULL,NULL,0.00,4020.00,'2026-08-13 11:48:01','2026-08-13 11:48:01',0,NULL,0.00,0.00),(363,11,32,NULL,'2026-08-06 00:00:00',NULL,'4208',NULL,NULL,0.00,1400.00,'2026-08-13 11:48:46','2026-08-13 11:48:46',0,NULL,0.00,0.00),(364,11,32,NULL,'2026-08-06 00:00:00',NULL,'4209',NULL,NULL,0.00,30000.00,'2026-08-13 11:49:55','2026-08-13 11:49:55',0,NULL,0.00,0.00),(365,11,32,NULL,'2026-08-06 00:00:00',NULL,'4210',NULL,NULL,0.00,14500.00,'2026-08-13 11:50:31','2026-08-13 11:50:31',0,NULL,0.00,0.00),(366,11,32,NULL,'2026-08-06 00:00:00',NULL,'4211',NULL,NULL,0.00,5460.00,'2026-08-13 11:51:11','2026-08-13 11:51:11',0,NULL,0.00,0.00),(367,11,32,NULL,'2026-08-06 00:00:00',NULL,'4212',NULL,NULL,0.00,1100.00,'2026-08-13 11:52:41','2026-08-13 11:52:41',0,NULL,0.00,0.00),(368,11,32,NULL,'2026-08-07 00:00:00',NULL,'4213',NULL,NULL,0.00,300.00,'2026-08-13 11:55:33','2026-08-13 11:55:33',0,NULL,0.00,0.00),(369,11,7,NULL,'2026-08-07 00:00:00',NULL,'4214',NULL,NULL,0.00,2310.00,'2026-08-13 11:56:06','2026-08-13 11:56:06',0,NULL,0.00,0.00),(370,11,32,NULL,'2026-08-07 00:00:00',NULL,'4215',NULL,NULL,0.00,1600.00,'2026-08-13 11:56:47','2026-08-13 11:56:47',0,NULL,0.00,0.00),(371,11,32,NULL,'2026-08-07 00:00:00',NULL,'4216',NULL,NULL,0.00,1200.00,'2026-08-13 11:57:15','2026-08-13 11:57:15',0,NULL,0.00,0.00),(372,11,32,NULL,'2026-08-07 00:00:00',NULL,'4217',NULL,NULL,0.00,600.00,'2026-08-13 11:57:44','2026-08-13 11:57:44',0,NULL,0.00,0.00),(373,11,32,NULL,'2026-08-07 00:00:00',NULL,'4218',NULL,NULL,0.00,6540.00,'2026-08-13 11:58:30','2026-08-13 11:58:30',0,NULL,0.00,0.00),(374,11,32,NULL,'2026-08-07 00:00:00',NULL,'4219',NULL,NULL,0.00,2760.00,'2026-08-13 11:59:17','2026-08-13 11:59:17',0,NULL,0.00,0.00),(375,11,32,NULL,'2026-08-10 00:00:00',NULL,'4220',NULL,NULL,0.00,2035.00,'2026-08-13 11:59:46','2026-08-13 11:59:46',0,NULL,0.00,0.00),(376,11,32,NULL,'2026-08-10 00:00:00',NULL,'4221',NULL,NULL,0.00,4470.00,'2026-08-13 12:00:16','2026-08-13 12:00:16',0,NULL,0.00,0.00),(377,11,32,NULL,'2026-08-10 00:00:00',NULL,'4222',NULL,NULL,0.00,60.00,'2026-08-13 12:00:39','2026-08-13 12:00:39',0,NULL,0.00,0.00),(378,11,32,NULL,'2026-08-10 00:00:00',NULL,'4223',NULL,NULL,0.00,1260.00,'2026-08-13 12:01:19','2026-08-13 12:01:19',0,NULL,0.00,0.00),(379,11,32,NULL,'2026-08-10 00:00:00',NULL,'4224',NULL,NULL,0.00,320.00,'2026-08-13 12:01:46','2026-08-13 12:01:46',0,NULL,0.00,0.00),(380,11,32,NULL,'2026-08-10 00:00:00',NULL,'4225',NULL,NULL,0.00,8325.00,'2026-08-13 12:02:18','2026-08-13 12:02:18',0,NULL,0.00,0.00),(381,11,32,NULL,'2026-08-11 00:00:00',NULL,'4226',NULL,NULL,0.00,1960.00,'2026-08-13 12:02:47','2026-08-13 12:03:39',0,NULL,0.00,0.00),(382,11,32,NULL,'2026-08-10 00:00:00',NULL,'4227',NULL,NULL,0.00,14000.00,'2026-08-13 12:03:29','2026-08-13 12:03:29',0,NULL,0.00,0.00),(383,11,32,NULL,'2026-08-11 00:00:00',NULL,'4228',NULL,NULL,0.00,3280.00,'2026-08-13 12:04:03','2026-08-13 12:04:03',0,NULL,0.00,0.00),(384,11,32,NULL,'2026-08-11 00:00:00',NULL,'4229',NULL,NULL,0.00,2400.00,'2026-08-13 12:04:30','2026-08-13 12:04:30',0,NULL,0.00,0.00),(385,11,32,NULL,'2026-08-11 00:00:00',NULL,'4231',NULL,NULL,0.00,750.00,'2026-08-13 12:05:04','2026-08-13 12:05:04',0,NULL,0.00,0.00),(386,11,32,NULL,'2026-08-12 00:00:00',NULL,'4232',NULL,NULL,0.00,3800.00,'2026-08-13 12:05:30','2026-08-13 12:05:30',0,NULL,0.00,0.00),(387,11,32,NULL,'2026-08-12 00:00:00',NULL,'4233',NULL,NULL,0.00,750.00,'2026-08-13 12:06:11','2026-08-13 12:06:11',0,NULL,0.00,0.00),(388,11,32,NULL,'2026-08-12 00:00:00',NULL,'4234',NULL,NULL,0.00,50420.00,'2026-08-13 12:07:23','2026-08-13 12:07:35',0,NULL,0.00,0.00),(389,11,33,NULL,'2026-08-01 00:00:00',NULL,'3709',NULL,NULL,0.00,1200.00,'2026-08-13 12:11:01','2026-08-13 12:11:01',0,NULL,0.00,0.00),(390,11,33,NULL,'2026-08-03 00:00:00',NULL,'3710',NULL,NULL,0.00,6400.00,'2026-08-13 12:11:48','2026-08-13 12:11:48',0,NULL,0.00,0.00),(391,11,33,NULL,'2026-08-03 00:00:00',NULL,'3711',NULL,NULL,0.00,3000.00,'2026-08-13 12:12:18','2026-08-13 12:12:18',0,NULL,0.00,0.00),(392,11,33,NULL,'2026-08-03 00:00:00',NULL,'3712',NULL,NULL,0.00,1600.00,'2026-08-13 12:13:11','2026-08-13 12:13:11',0,NULL,0.00,0.00),(393,11,33,NULL,'2026-08-04 00:00:00',NULL,'3713',NULL,NULL,0.00,4620.00,'2026-08-13 12:13:43','2026-08-13 12:13:43',0,NULL,0.00,0.00),(394,11,33,NULL,'2026-08-04 00:00:00',NULL,'3714',NULL,NULL,0.00,1100.00,'2026-08-13 12:14:16','2026-08-13 12:14:16',0,NULL,0.00,0.00),(395,11,33,NULL,'2026-08-04 00:00:00',NULL,'3715',NULL,NULL,0.00,2000.00,'2026-08-13 12:14:53','2026-08-13 12:14:53',0,NULL,0.00,0.00),(396,11,33,NULL,'2026-08-04 00:00:00',NULL,'3716',NULL,NULL,0.00,6500.00,'2026-08-13 12:15:22','2026-08-13 12:15:22',0,NULL,0.00,0.00),(397,11,33,NULL,'2026-08-04 00:00:00',NULL,'3717',NULL,NULL,0.00,15000.00,'2026-08-13 12:15:54','2026-08-13 12:15:54',0,NULL,0.00,0.00),(398,11,33,NULL,'2026-08-04 00:00:00',NULL,'3718',NULL,NULL,0.00,2000.00,'2026-08-13 12:16:22','2026-08-13 12:16:22',0,NULL,0.00,0.00),(399,11,33,NULL,'2026-08-04 00:00:00',NULL,'3719',NULL,NULL,0.00,4500.00,'2026-08-13 12:16:55','2026-08-13 12:16:55',0,NULL,0.00,0.00),(400,11,33,NULL,'2026-08-05 00:00:00',NULL,'3720',NULL,NULL,0.00,5900.00,'2026-08-13 12:17:25','2026-08-13 12:17:25',0,NULL,0.00,0.00),(401,11,33,NULL,'2026-08-06 00:00:00',NULL,'3721',NULL,NULL,0.00,18200.00,'2026-08-13 12:18:02','2026-08-13 12:18:02',0,NULL,0.00,0.00),(402,11,33,NULL,'2026-08-06 00:00:00',NULL,'3722',NULL,NULL,0.00,6600.00,'2026-08-13 12:19:09','2026-08-13 12:19:09',0,NULL,0.00,0.00),(403,11,33,NULL,'2026-08-06 00:00:00',NULL,'3723',NULL,NULL,0.00,13000.00,'2026-08-13 12:20:10','2026-08-13 12:20:10',0,NULL,0.00,0.00),(404,11,33,NULL,'2026-08-06 00:00:00',NULL,'3724',NULL,NULL,0.00,8750.00,'2026-08-13 12:20:36','2026-08-13 12:20:36',0,NULL,0.00,0.00),(405,11,33,NULL,'2026-08-06 00:00:00',NULL,'3725',NULL,NULL,0.00,1500.00,'2026-08-13 12:21:11','2026-08-13 12:21:11',0,NULL,0.00,0.00),(406,11,33,NULL,'2026-08-06 00:00:00',NULL,'3726',NULL,NULL,0.00,7200.00,'2026-08-13 12:21:41','2026-08-13 12:21:41',0,NULL,0.00,0.00),(407,11,33,NULL,'2026-08-07 00:00:00',NULL,'3727',NULL,NULL,0.00,4500.00,'2026-08-13 12:22:52','2026-08-13 12:22:52',0,NULL,0.00,0.00),(408,11,33,NULL,'2026-08-07 00:00:00',NULL,'3728',NULL,NULL,0.00,3500.00,'2026-08-13 12:23:28','2026-08-13 12:23:28',0,NULL,0.00,0.00),(409,11,33,NULL,'2026-08-07 00:00:00',NULL,'3730',NULL,NULL,0.00,12000.00,'2026-08-13 12:24:20','2026-08-13 12:24:20',0,NULL,0.00,0.00),(410,11,33,NULL,'2026-08-08 00:00:00',NULL,'3731',NULL,NULL,0.00,12500.00,'2026-08-13 12:24:50','2026-08-13 12:24:50',0,NULL,0.00,0.00),(411,11,33,NULL,'2026-08-08 00:00:00',NULL,'3732',NULL,NULL,0.00,3000.00,'2026-08-13 12:25:26','2026-08-13 12:25:26',0,NULL,0.00,0.00),(412,11,33,NULL,'2026-08-10 00:00:00',NULL,'3733',NULL,NULL,0.00,5300.00,'2026-08-13 12:25:55','2026-08-13 12:25:55',0,NULL,0.00,0.00),(413,11,33,NULL,'2026-08-10 00:00:00',NULL,'3734',NULL,NULL,0.00,7000.00,'2026-08-13 12:27:35','2026-08-13 12:27:35',0,NULL,0.00,0.00),(414,11,33,NULL,'2026-08-10 00:00:00',NULL,'3735',NULL,NULL,0.00,6750.00,'2026-08-13 12:28:28','2026-08-13 12:28:28',0,NULL,0.00,0.00),(415,11,33,NULL,'2026-08-10 00:00:00',NULL,'3736',NULL,NULL,0.00,200.00,'2026-08-13 12:29:32','2026-08-13 12:29:32',0,NULL,0.00,0.00),(416,11,33,NULL,'2026-08-12 00:00:00',NULL,'3737',NULL,NULL,0.00,5600.00,'2026-08-13 12:30:08','2026-08-13 12:30:08',0,NULL,0.00,0.00),(417,11,33,NULL,'2026-08-12 00:00:00',NULL,'3738',NULL,NULL,0.00,37520.00,'2026-08-13 12:30:44','2026-08-13 12:30:44',0,NULL,0.00,0.00),(418,11,54,NULL,'2026-07-10 00:00:00',NULL,'571',NULL,NULL,0.00,168000.00,'2026-08-13 12:39:02','2026-08-13 12:39:17',0,NULL,0.00,0.00),(419,11,54,NULL,'2026-07-18 00:00:00',NULL,'572',NULL,NULL,0.00,25000.00,'2026-08-13 12:43:44','2026-08-13 12:43:44',1,NULL,0.00,0.00),(420,11,54,NULL,'2026-07-18 00:00:00',NULL,'572',NULL,NULL,0.00,25000.00,'2026-08-13 12:43:44','2026-08-13 12:43:44',0,NULL,0.00,0.00),(421,11,54,NULL,'2026-08-23 00:00:00',NULL,'573',NULL,NULL,0.00,13500.00,'2026-08-13 12:44:24','2026-08-13 12:44:24',0,NULL,0.00,0.00),(422,11,54,NULL,'2026-08-05 00:00:00',NULL,'574',NULL,NULL,0.00,14000.00,'2026-08-13 12:44:58','2026-08-13 12:44:58',0,NULL,0.00,0.00),(423,11,45,NULL,'2026-08-13 00:00:00',NULL,'602',NULL,NULL,0.00,24700.00,'2026-08-13 13:18:56','2026-08-13 13:18:56',0,NULL,0.00,0.00),(424,11,33,NULL,'2026-08-13 00:00:00',NULL,'3739',NULL,NULL,0.00,3200.00,'2026-08-13 13:42:32','2026-08-13 13:42:32',0,NULL,0.00,0.00),(425,11,32,NULL,'2026-08-13 00:00:00',NULL,'4236',NULL,NULL,0.00,3870.00,'2026-08-13 14:32:02','2026-08-13 14:32:02',0,NULL,0.00,0.00),(426,11,55,NULL,'2026-07-01 00:00:00',NULL,NULL,NULL,NULL,0.00,8810.00,'2026-08-13 14:47:53','2026-08-13 14:47:53',0,NULL,0.00,0.00),(427,11,55,NULL,'2026-07-02 00:00:00',NULL,NULL,NULL,NULL,0.00,41045.00,'2026-08-13 14:49:48','2026-08-13 14:49:48',0,NULL,0.00,0.00),(428,11,55,NULL,'2026-07-03 00:00:00',NULL,NULL,NULL,NULL,0.00,30610.00,'2026-08-13 14:51:32','2026-08-13 14:51:32',0,NULL,0.00,0.00),(429,11,55,NULL,'2026-07-04 00:00:00',NULL,NULL,NULL,NULL,0.00,61995.00,'2026-08-13 14:53:09','2026-08-13 14:53:09',0,NULL,0.00,0.00),(430,11,55,NULL,'2026-07-06 00:00:00',NULL,NULL,NULL,NULL,0.00,17950.00,'2026-08-13 14:54:05','2026-08-13 14:54:05',0,NULL,0.00,0.00),(431,11,55,NULL,'2026-07-07 00:00:00',NULL,NULL,NULL,NULL,0.00,19920.00,'2026-08-13 14:55:30','2026-08-13 14:55:30',0,NULL,0.00,0.00),(432,11,55,NULL,'2026-07-08 00:00:00',NULL,NULL,NULL,NULL,0.00,16330.00,'2026-08-13 14:56:44','2026-08-13 14:56:44',0,NULL,0.00,0.00),(433,11,55,NULL,'2026-07-09 00:00:00',NULL,NULL,NULL,NULL,0.00,17120.00,'2026-08-13 14:58:31','2026-08-13 14:58:31',0,NULL,0.00,0.00),(434,11,55,NULL,'2026-07-10 00:00:00',NULL,NULL,NULL,NULL,0.00,15450.00,'2026-08-13 15:00:10','2026-08-13 15:00:18',0,NULL,0.00,0.00),(435,11,55,NULL,'2026-07-11 00:00:00',NULL,NULL,NULL,NULL,0.00,2060.00,'2026-08-13 15:01:09','2026-08-13 15:01:09',0,NULL,0.00,0.00),(436,11,55,NULL,'2026-07-13 00:00:00',NULL,NULL,NULL,NULL,0.00,8780.00,'2026-08-13 15:03:56','2026-08-13 15:03:56',0,NULL,0.00,0.00),(437,11,55,NULL,'2026-07-14 00:00:00',NULL,NULL,NULL,NULL,0.00,42465.00,'2026-08-13 15:10:23','2026-08-13 15:10:23',0,NULL,0.00,0.00),(438,11,55,NULL,'2026-07-15 00:00:00',NULL,NULL,NULL,NULL,0.00,5470.00,'2026-08-13 15:11:17','2026-08-13 15:11:17',0,NULL,0.00,0.00),(439,11,55,NULL,'2026-07-16 00:00:00',NULL,NULL,NULL,NULL,0.00,10910.00,'2026-08-13 15:12:01','2026-08-13 15:12:01',0,NULL,0.00,0.00),(440,11,55,NULL,'2026-07-17 00:00:00',NULL,NULL,NULL,NULL,0.00,8950.00,'2026-08-13 15:12:32','2026-08-13 15:12:32',0,NULL,0.00,0.00),(441,11,55,NULL,'2026-07-18 00:00:00',NULL,NULL,NULL,NULL,0.00,9850.00,'2026-08-13 15:13:21','2026-08-13 15:13:21',0,NULL,0.00,0.00),(442,11,55,NULL,'2026-07-20 00:00:00',NULL,NULL,NULL,NULL,0.00,14330.00,'2026-08-13 15:14:58','2026-08-13 15:14:58',0,NULL,0.00,0.00),(443,11,55,NULL,'2026-07-21 00:00:00',NULL,NULL,NULL,NULL,0.00,6760.00,'2026-08-13 15:15:53','2026-08-13 15:15:53',0,NULL,0.00,0.00),(444,11,55,NULL,'2026-07-22 00:00:00',NULL,NULL,NULL,NULL,0.00,13010.00,'2026-08-13 15:16:39','2026-08-13 15:16:39',0,NULL,0.00,0.00),(445,11,55,NULL,'2026-07-23 00:00:00',NULL,NULL,NULL,NULL,0.00,18370.00,'2026-08-13 15:17:21','2026-08-13 15:17:21',0,NULL,0.00,0.00),(446,11,55,NULL,'2026-07-24 00:00:00',NULL,NULL,NULL,NULL,0.00,18930.00,'2026-08-13 15:18:22','2026-08-13 15:18:22',0,NULL,0.00,0.00),(447,11,55,NULL,'2026-07-25 00:00:00',NULL,NULL,NULL,NULL,0.00,1370.00,'2026-08-13 15:19:14','2026-08-13 15:19:14',0,NULL,0.00,0.00),(448,11,55,NULL,'2026-07-26 00:00:00',NULL,NULL,NULL,NULL,0.00,8095.00,'2026-08-13 15:19:53','2026-08-13 15:19:53',0,NULL,0.00,0.00),(449,11,55,NULL,'2026-07-28 00:00:00',NULL,NULL,NULL,NULL,0.00,2940.00,'2026-08-13 15:20:30','2026-08-13 15:20:30',0,NULL,0.00,0.00),(450,11,55,NULL,'2026-07-30 00:00:00',NULL,NULL,NULL,NULL,0.00,15740.00,'2026-08-13 15:22:51','2026-08-13 15:22:51',0,NULL,0.00,0.00),(451,11,55,NULL,'2026-07-31 00:00:00',NULL,NULL,NULL,NULL,0.00,17010.00,'2026-08-13 15:23:29','2026-08-13 15:23:29',0,NULL,0.00,0.00),(452,11,55,NULL,'2026-08-01 00:00:00',NULL,NULL,NULL,NULL,0.00,26465.00,'2026-08-13 15:24:05','2026-08-13 15:24:05',0,NULL,0.00,0.00),(453,11,55,NULL,'2026-08-03 00:00:00',NULL,NULL,NULL,NULL,0.00,9650.00,'2026-08-13 15:25:00','2026-08-13 15:25:00',0,NULL,0.00,0.00),(454,11,55,NULL,'2026-08-04 00:00:00',NULL,NULL,NULL,NULL,0.00,2260.00,'2026-08-13 15:25:42','2026-08-13 15:25:42',0,NULL,0.00,0.00),(455,11,55,NULL,'2026-08-05 00:00:00',NULL,NULL,NULL,NULL,0.00,10460.00,'2026-08-13 15:26:16','2026-08-13 15:26:16',0,NULL,0.00,0.00),(456,11,55,NULL,'2026-08-06 00:00:00',NULL,NULL,NULL,NULL,0.00,10280.00,'2026-08-13 15:26:50','2026-08-13 15:26:50',0,NULL,0.00,0.00),(457,11,55,NULL,'2026-08-07 00:00:00',NULL,NULL,NULL,NULL,0.00,20370.00,'2026-08-13 15:27:36','2026-08-13 15:27:36',0,NULL,0.00,0.00),(458,11,55,NULL,'2026-08-08 00:00:00',NULL,NULL,NULL,NULL,0.00,5910.00,'2026-08-13 15:28:10','2026-08-13 15:28:10',0,NULL,0.00,0.00),(459,11,55,NULL,'2026-08-10 00:00:00',NULL,NULL,NULL,NULL,0.00,4300.00,'2026-08-13 15:28:36','2026-08-13 15:28:36',0,NULL,0.00,0.00),(460,11,55,NULL,'2026-08-11 00:00:00',NULL,NULL,NULL,NULL,0.00,13880.00,'2026-08-13 15:29:00','2026-08-13 15:29:00',0,NULL,0.00,0.00),(461,11,55,NULL,'2026-08-12 00:00:00',NULL,NULL,NULL,NULL,0.00,6770.00,'2026-08-13 15:29:34','2026-08-13 15:29:34',0,NULL,0.00,0.00),(462,11,56,NULL,'2026-08-13 00:00:00',NULL,'603',NULL,NULL,0.00,62880.00,'2026-08-13 16:05:27','2026-08-13 16:05:27',0,NULL,0.00,0.00),(463,11,33,NULL,'2026-08-13 00:00:00',NULL,'3740',NULL,NULL,0.00,1500.00,'2026-08-14 11:27:07','2026-08-14 11:27:07',0,NULL,0.00,0.00),(464,11,33,NULL,'2026-08-13 00:00:00',NULL,'3741',NULL,NULL,0.00,4160.00,'2026-08-14 11:27:48','2026-08-14 11:27:48',0,NULL,0.00,0.00),(465,11,32,NULL,'2026-08-13 00:00:00',NULL,'4237',NULL,NULL,0.00,11250.00,'2026-08-14 11:36:20','2026-08-14 11:36:20',0,NULL,0.00,0.00),(466,11,32,NULL,'2026-08-13 00:00:00',NULL,'4238',NULL,NULL,0.00,15750.00,'2026-08-14 11:39:06','2026-08-14 11:39:06',0,NULL,0.00,0.00),(467,11,32,NULL,'2026-08-14 00:00:00',NULL,'4239',NULL,NULL,0.00,6100.00,'2026-08-14 11:40:36','2026-08-14 11:40:36',0,NULL,0.00,0.00),(468,11,32,NULL,'2026-08-14 00:00:00',NULL,'4240',NULL,NULL,0.00,1800.00,'2026-08-14 11:43:46','2026-08-14 11:43:46',0,NULL,0.00,0.00),(469,11,35,NULL,'2026-08-14 00:00:00',NULL,'604',NULL,NULL,0.00,162000.00,'2026-08-14 13:40:13','2026-08-14 13:40:13',0,NULL,0.00,0.00),(470,11,55,NULL,'2026-08-13 00:00:00',NULL,NULL,NULL,NULL,0.00,4490.00,'2026-08-14 13:47:59','2026-08-14 13:47:59',0,NULL,0.00,0.00),(471,11,32,NULL,'2026-08-14 00:00:00',NULL,'4241',NULL,NULL,0.00,8000.00,'2026-08-14 13:51:08','2026-08-14 13:51:08',0,NULL,0.00,0.00),(472,11,32,NULL,'2026-08-14 00:00:00',NULL,'4242',NULL,NULL,0.00,15250.00,'2026-08-14 13:54:20','2026-08-14 13:54:20',0,NULL,0.00,0.00),(473,11,32,NULL,'2026-08-14 00:00:00',NULL,'4243',NULL,NULL,0.00,250.00,'2026-08-14 13:55:40','2026-08-14 13:57:01',0,NULL,0.00,0.00),(474,11,32,NULL,'2026-08-14 00:00:00',NULL,'4244',NULL,NULL,0.00,1680.00,'2026-08-14 13:56:42','2026-08-14 14:01:15',0,NULL,0.00,0.00);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `sales_return_items`
--

LOCK TABLES `sales_return_items` WRITE;
/*!40000 ALTER TABLE `sales_return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_return_items` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `sales_returns`
--

LOCK TABLES `sales_returns` WRITE;
/*!40000 ALTER TABLE `sales_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `ServiceID` int NOT NULL AUTO_INCREMENT,
  `CustomerId` int DEFAULT NULL,
  `Inquiry` text,
  `JobStatus` enum('pending','done') DEFAULT 'pending',
  `Technician` text,
  `JobDetails` text,
  `ServiceDate` date DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`ServiceID`),
  KEY `CustomerID` (`CustomerId`),
  CONSTRAINT `service_ibfk_1` FOREIGN KEY (`CustomerId`) REFERENCES `customer` (`CustomerId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_renewals`
--

DROP TABLE IF EXISTS `subscription_renewals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_renewals` (
  `RenewalID` int NOT NULL AUTO_INCREMENT,
  `CustomerId` int NOT NULL,
  `OldExpiryDate` date DEFAULT NULL,
  `NewExpiryDate` date NOT NULL,
  `RenewalDate` date NOT NULL,
  `AmountPaid` decimal(10,2) DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`RenewalID`),
  KEY `CustomerId` (`CustomerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_renewals`
--

LOCK TABLES `subscription_renewals` WRITE;
/*!40000 ALTER TABLE `subscription_renewals` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_renewals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_privileges`
--

DROP TABLE IF EXISTS `user_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_privileges` (
  `UserPrivilegeId` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `PrivilegeId` int NOT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`UserPrivilegeId`),
  UNIQUE KEY `UserId_PrivilegeId` (`UserId`,`PrivilegeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_privileges`
--

LOCK TABLES `user_privileges` WRITE;
/*!40000 ALTER TABLE `user_privileges` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_privileges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tab`
--

DROP TABLE IF EXISTS `user_tab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tab` (
  `UserId` int unsigned NOT NULL AUTO_INCREMENT,
  `Type` int NOT NULL DEFAULT '1',
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CreatedBy` int NOT NULL,
  `CreatedDate` varchar(50) NOT NULL DEFAULT '',
  `ModifiedDate` varchar(50) NOT NULL DEFAULT '',
  `IsDeleted` int NOT NULL DEFAULT '0',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` int NOT NULL,
  `reset_token` text NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tab`
--

LOCK TABLES `user_tab` WRITE;
/*!40000 ALTER TABLE `user_tab` DISABLE KEYS */;
INSERT INTO `user_tab` VALUES (1,1,'Admin','admin','f7c3bc1d808e04732adf679965ccc34ca7ae3441',1,'2019-02-15 15:02:39','2025-04-24 10:38:02',0,'2026-07-26 15:26:33',1,'',1),(2,1,'hari@itmartx.com','hari@itmartx.com','5032057075275f988d93cd118882af5c626c79c3',1,'2019-02-15 15:02:39','2025-05-01 07:35:50',0,'2025-05-01 14:35:50',1,'',1),(11,1,'shobika','shobika','f7c3bc1d808e04732adf679965ccc34ca7ae3441',1,'30/07/2026 11:17','',0,'2026-07-30 05:47:43',1,'',1);
/*!40000 ALTER TABLE `user_tab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor` (
  `VendorID` int NOT NULL AUTO_INCREMENT,
  `VendorNo` varchar(255) DEFAULT NULL,
  `VendorDate` date DEFAULT NULL,
  `VendorName` varchar(255) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `Address` text,
  `ContactNo` varchar(20) DEFAULT NULL,
  `MobileNo` varchar(20) DEFAULT NULL,
  `EmailId` varchar(255) DEFAULT NULL,
  `Remark` text,
  `Image` varchar(255) DEFAULT NULL,
  `CreatedBy` varchar(255) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiedBy` varchar(255) DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `Salutation` varchar(50) DEFAULT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `VendorLanguage` varchar(50) DEFAULT 'English',
  `TaxRate` varchar(100) DEFAULT NULL,
  `CompanyID` varchar(100) DEFAULT NULL,
  `Currency` varchar(100) DEFAULT 'LKR',
  `AccountsPayable` varchar(155) DEFAULT NULL,
  `PaymentTerms` varchar(100) DEFAULT 'Due on Receipt',
  `EnablePortal` tinyint(1) DEFAULT '0',
  `Website` varchar(255) DEFAULT NULL,
  `WhatsAppNo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`VendorID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (1,'000001','2026-08-01','Anoshan','Anoshan Traders','','','','','','',NULL,'2026-08-01 20:54:57',NULL,'2026-08-01 20:54:57',0,'','','','English','','','LKR','','Due on Receipt',0,'',''),(2,'000002','2026-08-04','Rakul Card','Rakul Card','','0112340111','0779836122','rakulcards5959@gmail.com','','',NULL,'2026-08-04 15:46:20',NULL,'2026-08-04 15:46:20',0,'','Rakul','','English','','','LKR','','Due on Receipt',0,'',''),(3,'000003','2026-08-04','Asian Trading Company','Asian Trading Company','','0112543141','0773444102','','','',NULL,'2026-08-04 15:48:24',NULL,'2026-08-04 15:48:24',0,'','Asian','','English','','','LKR','','Due on Receipt',0,'',''),(4,'000004','2026-08-11','Central Impex (PVT) LTD','Central Impex (PVT) LTD','','0112436313','0776223167','centralimpex78.ci@gmail.com','','',NULL,'2026-08-11 10:40:40',NULL,'2026-08-11 10:40:40',0,'','Central Impex (PVT) LTD','','English','','','LKR','','Due on Receipt',0,'',''),(5,'000005','2026-08-11','Adeesha Printers','Adeesha Printers','','','0772802832','','','',NULL,'2026-08-11 10:43:53',NULL,'2026-08-11 10:43:53',0,'','Adeesha Printers','','English','','','LKR','','Due on Receipt',0,'',''),(6,'000006','2026-08-11','MERCUREI STATIONERS','MERCUREI STATIONERS','','0112330037','0777635036','mscompany2021@gmail.com','','',NULL,'2026-08-11 10:46:42',NULL,'2026-08-11 10:46:42',0,'','MERCUREI STATIONERS','','English','','','LKR','','Due on Receipt',0,'',''),(7,'000007','2026-08-11','JMJ Enterprises','JMJ Enterprises','','','077 724 9546','','','',NULL,'2026-08-11 10:56:31',NULL,'2026-08-11 10:56:31',0,'','JMJ Enterprises','','English','','','LKR','','Due on Receipt',0,'',''),(8,'000008','2026-08-11','TRADE ADS (PVT) LTD','TRADE ADS (PVT) LTD','','0112169692','0777234343','','','',NULL,'2026-08-11 10:57:41',NULL,'2026-08-11 10:57:41',0,'','TRADE ADS (PVT) LTD','','English','','','LKR','','Due on Receipt',0,'',''),(9,'000009','2026-08-11','NEW HARA ENTERPRISES','NEW HARA ENTERPRISES','','','0779828294','','','',NULL,'2026-08-11 10:58:52',NULL,'2026-08-11 10:58:52',0,'','NEW HARA ENTERPRISES','','English','','','LKR','','Due on Receipt',0,'',''),(10,'000010','2026-08-11','COLOMBO DYES & CHEMICALS','COLOMBO DYES & CHEMICALS','','0112344274','0771081921','','','',NULL,'2026-08-11 11:00:18',NULL,'2026-08-11 11:00:18',0,'','COLOMBO DYES & CHEMICALS','','English','','','LKR','','Due on Receipt',0,'',''),(11,'000011','2026-08-11','Soft Creations','Soft Creations','','','0754448098','','','',NULL,'2026-08-11 11:05:05',NULL,'2026-08-11 11:05:05',0,'','Soft Creations','','English','','','LKR','','Due on Receipt',0,'',''),(12,'000012','2026-08-11','E.A.P & Company','E.A.P & Company','','','0773031578','','','',NULL,'2026-08-11 11:07:46',NULL,'2026-08-11 11:07:46',0,'','E.A.P & Company','','English','','','LKR','','Due on Receipt',0,'',''),(13,'000013','2026-08-11','Sunro Marketing','Sunro Marketing','','','0112472244','','','',NULL,'2026-08-11 11:09:03',NULL,'2026-08-11 11:09:03',0,'','Sunro Marketing','','English','','','LKR','','Due on Receipt',0,'',''),(14,'000014','2026-08-11','THUSHARA PHOTO FRAMING','THUSHARA PHOTO FRAMING','','0112382903','0779808851','','','',NULL,'2026-08-11 11:10:29',NULL,'2026-08-11 11:10:29',0,'','THUSHARA PHOTO FRAMING','','English','','','LKR','','Due on Receipt',0,'',''),(15,'000015','2026-08-11','PROMPT XEPRESS (PVT) LTD','PROMPT XEPRESS (PVT) LTD','','','0114506505','customercare@promptxpress.lk','','',NULL,'2026-08-11 11:13:21',NULL,'2026-08-11 11:13:21',0,'','PROMPT XEPRESS (PVT) LTD','','English','','','LKR','','Due on Receipt',0,'',''),(16,'000016','2026-08-11','Kala Print','Kala Print','','','0768974069','','','',NULL,'2026-08-11 11:19:30',NULL,'2026-08-11 11:19:30',0,'','Kala Print','','English','','','LKR','','Due on Receipt',0,'',''),(17,'000017','2026-08-11','L.P Diecut','L.P Diecut','','0342289844','0775070909','','','',NULL,'2026-08-11 11:20:53',NULL,'2026-08-11 11:20:53',0,'','L.P Diecut','','English','','','LKR','','Due on Receipt',0,'',''),(18,'000018','2026-08-12','Colombo Paper','Colombo Paper','','','0112322308','','','',NULL,'2026-08-12 10:24:12',NULL,'2026-08-12 10:24:12',0,'','Colombo Paper','','English','','','LKR','','Due on Receipt',0,'',''),(19,'000019','2026-08-12','Creative Technology','Creative Technology','','','0112199610','','','',NULL,'2026-08-12 11:35:44',NULL,'2026-08-12 11:35:44',0,'','Creative Technology','','English','','','LKR','','Due on Receipt',0,'',''),(20,'000020','2026-08-12','Kishore','Kishore','','','0112949802','','','',NULL,'2026-08-12 12:00:50',NULL,'2026-08-12 12:00:50',0,'','Kishore','','English','','','LKR','','Due on Receipt',0,'',''),(21,'000021','2026-08-12','ROYALTY LANKA (PVT) LTD','ROYALTY LANKA (PVT) LTD','','','0112396020','','','',NULL,'2026-08-12 15:09:56',NULL,'2026-08-12 15:09:56',0,'','ROYALTY LANKA (PVT) LTD','','English','','','LKR','','Due on Receipt',0,'',''),(22,'000022','2026-08-13','Premadasa Offset Plate Makers Ltd','Premadasa Offset Plate Makers Ltd','','','0112820588','','','',NULL,'2026-08-13 10:35:51',NULL,'2026-08-13 10:35:51',0,'','Premadasa Offset Plate Makers Ltd','','English','','','LKR','','Due on Receipt',0,'',''),(23,'000023','2026-08-13','SESATHA PRINT MATERIALS (PVT) LTD','SESATHA PRINT MATERIALS (PVT) LTD','','','0710410327','sesathaprintmaterials@gmail.com','','',NULL,'2026-08-13 11:04:12',NULL,'2026-08-13 11:04:12',0,'','SESATHA PRINT MATERIALS (PVT) LTD','','English','','','LKR','','Due on Receipt',0,'',''),(24,'000024','2026-08-13','Super Graphics ','Super Graphics ','','','0712302419','','','',NULL,'2026-08-13 16:56:43',NULL,'2026-08-13 16:56:43',0,'','Super Graphics ','','English','','','LKR','','Due on Receipt',0,'','');
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_payments`
--

DROP TABLE IF EXISTS `vendor_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor_payments` (
  `PaymentID` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `VendorID` int NOT NULL,
  `PurchaseID` int NOT NULL,
  `PaymentDate` date NOT NULL,
  `ManualReceiptNo` varchar(50) DEFAULT NULL,
  `PaidAmount` decimal(10,2) NOT NULL,
  `PaymentDue` decimal(10,2) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `BankID` int DEFAULT NULL,
  `ChequeNumber` varchar(110) DEFAULT NULL,
  `ChequeDate` date DEFAULT NULL,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiedBy` int DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PaymentID`),
  KEY `VendorID` (`VendorID`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_payments`
--

LOCK TABLES `vendor_payments` WRITE;
/*!40000 ALTER TABLE `vendor_payments` DISABLE KEYS */;
INSERT INTO `vendor_payments` VALUES (1,11,10,1,'2026-08-18',NULL,1500.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:18:25',NULL,NULL,1),(2,11,9,2,'2026-08-18',NULL,4500.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:20:06',NULL,NULL,0),(3,11,9,3,'2026-08-16',NULL,3000.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:21:11',NULL,NULL,0),(4,11,9,4,'2026-08-08',NULL,5250.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:21:53',NULL,NULL,0),(5,11,16,5,'2026-07-09',NULL,2000.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:23:18',NULL,NULL,0),(6,11,1,6,'2026-08-05',NULL,2150.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:27:05',NULL,NULL,0),(7,11,1,7,'2026-08-07',NULL,775.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:29:43',NULL,NULL,0),(8,11,8,8,'2026-08-07',NULL,48000.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:31:40',NULL,NULL,0),(9,11,7,9,'2026-07-22',NULL,50500.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:34:26',NULL,NULL,0),(10,11,1,10,'2026-07-14',NULL,3260.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:35:42',NULL,NULL,0),(11,11,1,11,'2026-07-07',NULL,60430.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:37:14',NULL,NULL,0),(12,11,5,12,'2026-07-02',NULL,20500.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:38:07',NULL,NULL,0),(13,11,2,13,'2026-08-04',NULL,11200.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:39:45',NULL,NULL,0),(14,11,1,14,'2026-07-06',NULL,2000.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:41:41',NULL,NULL,0),(15,11,1,15,'2026-08-07',NULL,850.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:42:18',NULL,NULL,0),(16,11,3,16,'2026-07-09',NULL,705.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:43:18',NULL,NULL,0),(17,11,3,17,'2026-07-14',NULL,1770.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:44:26',NULL,NULL,0),(18,11,3,18,'2026-07-23',NULL,265.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:46:10',NULL,NULL,0),(19,11,3,19,'2026-07-28',NULL,3995.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:47:00',NULL,NULL,0),(20,11,3,20,'2026-08-07',NULL,3000.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:47:57',NULL,NULL,0),(21,11,3,21,'2026-07-01',NULL,20755.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:49:27',NULL,NULL,0),(22,11,3,22,'2026-07-02',NULL,1475.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:51:04',NULL,NULL,0),(23,11,3,23,'2026-07-03',NULL,765.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:51:38',NULL,NULL,0),(24,11,3,24,'2026-08-12',NULL,44610.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:54:23',NULL,NULL,0),(25,11,3,25,'2026-07-11',NULL,3675.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:56:28',NULL,NULL,0),(26,11,3,26,'2026-07-13',NULL,10735.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:58:10',NULL,NULL,0),(27,11,3,27,'2026-07-11',NULL,470.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:58:37',NULL,NULL,0),(28,11,3,28,'2026-07-20',NULL,12450.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 10:59:19',NULL,NULL,0),(29,11,3,29,'2026-07-21',NULL,4700.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:00:04',NULL,NULL,0),(30,11,14,30,'2026-07-22',NULL,2610.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:07:05',NULL,NULL,0),(31,11,13,31,'2026-07-25',NULL,5750.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:22:53',NULL,NULL,0),(32,11,11,32,'2026-07-20',NULL,2100.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:23:39',NULL,NULL,0),(33,11,12,33,'2026-07-21',NULL,3000.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:24:26',NULL,NULL,0),(34,11,4,34,'2026-07-07',NULL,44400.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:27:54',NULL,NULL,0),(35,11,4,35,'2026-08-10',NULL,1100.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:28:32',NULL,NULL,0),(36,11,17,36,'2026-07-11',NULL,5950.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 11:29:59',NULL,NULL,0),(37,11,20,37,'2026-07-07',NULL,12320.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 12:01:38',NULL,NULL,0),(38,11,16,38,'2026-07-24',NULL,13600.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 12:02:42',NULL,NULL,0),(39,11,16,39,'2026-08-08',NULL,24450.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 12:04:49',NULL,NULL,0),(40,11,21,40,'2026-08-12',NULL,34300.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-12 15:10:48',NULL,NULL,0),(41,11,22,42,'2026-08-13',NULL,5550.00,0.00,'Bank Transfer',NULL,'','0000-00-00',NULL,'2026-08-13 11:01:53',NULL,NULL,0),(42,11,3,45,'2026-08-13',NULL,4000.00,950.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-13 11:13:33',NULL,NULL,1),(43,11,3,45,'2026-08-13',NULL,950.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-13 13:35:00',NULL,NULL,0),(44,11,3,46,'2026-08-13',NULL,2310.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-13 13:36:07',NULL,NULL,1),(45,11,19,47,'2026-08-14',NULL,21440.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-14 10:35:31',NULL,NULL,0),(46,11,8,67,'2026-08-13',NULL,7800.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-14 11:24:05',NULL,NULL,0),(47,11,10,1,'2026-08-14',NULL,1500.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 11:56:19',NULL,NULL,0),(48,11,8,68,'2026-08-13',NULL,7800.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-14 12:16:24',NULL,NULL,0),(49,11,8,69,'2026-08-14',NULL,13250.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-14 12:18:49',NULL,NULL,0),(50,11,3,70,'2026-08-14',NULL,5405.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(51,11,3,71,'2026-08-14',NULL,15350.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(52,11,3,72,'2026-08-14',NULL,1075.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(53,11,3,73,'2026-08-14',NULL,300.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(54,11,3,74,'2026-08-14',NULL,765.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(55,11,3,78,'2026-08-14',NULL,7520.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(56,11,3,77,'2026-08-14',NULL,11985.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(57,11,3,79,'2026-08-14',NULL,12750.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(58,11,3,76,'2026-08-14',NULL,1410.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(59,11,3,75,'2026-08-14',NULL,10945.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(60,11,3,80,'2026-08-14',NULL,3675.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(61,11,3,82,'2026-08-14',NULL,470.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(62,11,3,81,'2026-08-14',NULL,10735.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(63,11,3,87,'2026-08-14',NULL,1500.00,270.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(64,11,3,83,'2026-08-14',NULL,12450.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(65,11,3,84,'2026-08-14',NULL,4700.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(66,11,3,85,'2026-08-14',NULL,25435.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-08-14 13:29:18',NULL,NULL,0),(67,11,3,93,'2026-08-14',NULL,760.00,0.00,'Cash',NULL,NULL,NULL,11,'2026-08-14 13:31:27',NULL,NULL,0);
/*!40000 ALTER TABLE `vendor_payments` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `warehouse`
--

LOCK TABLES `warehouse` WRITE;
/*!40000 ALTER TABLE `warehouse` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouse` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Dumping data for table `warehouse_stock`
--

LOCK TABLES `warehouse_stock` WRITE;
/*!40000 ALTER TABLE `warehouse_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouse_stock` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-22 18:48:16
