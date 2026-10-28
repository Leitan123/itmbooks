-- MySQL dump 10.13  Distrib 8.0.44, for macos12.7 (arm64)
--
-- Host: localhost    Database: dbitmpos
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES (1,'B0001','NTB','Current Account','100030016924','Sri Sangaraja Mw',70230.00,0,'2026-06-02 16:06:04',NULL,38),(2,'B0002','HNB','Current Account','221010008628','Pamankade',30000.00,0,'2026-06-02 16:07:29',NULL,36),(3,'B0003','HNB','Saving Account','221020080500','Pamankade',10000.00,0,'2026-06-02 16:07:59',NULL,37),(4,'B0004','Sampath Bank','Saving Account','103714019354','Kotahena',88286.81,0,'2026-06-02 16:12:17',NULL,39);
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `capital_accounts` VALUES (5,1,304866.81,'2026-06-06 11:18:14'),(6,1,304866.81,'2026-06-08 20:34:12');
/*!40000 ALTER TABLE `capital_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chart_of_accounts`
--

DROP TABLE IF EXISTS `chart_of_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `chart_of_accounts` VALUES (1,'1001','Cash Account',1,0.00,920510.00,1,'2026-05-17 09:20:03'),(2,'1002','Bank Account',1,0.00,0.00,1,'2026-05-17 09:20:03'),(3,'1100','Accounts Receivable',1,0.00,-3185290.00,1,'2026-05-17 09:20:03'),(4,'1200','Inventory',1,0.00,225000.00,1,'2026-05-17 09:20:03'),(5,'2001','Accounts Payable',2,0.00,0.00,1,'2026-05-17 09:20:03'),(6,'3001','Capital Account',3,0.00,0.00,1,'2026-05-17 09:20:03'),(7,'4001','Sales Income',4,0.00,0.00,1,'2026-05-17 09:20:03'),(8,'4002','Service Income',4,0.00,0.00,1,'2026-05-17 09:20:03'),(9,'5001','Purchase Expense',5,0.00,8000.00,1,'2026-05-17 09:20:03'),(10,'5002','Salary Expense',5,0.00,690600.00,1,'2026-05-17 09:20:03'),(11,'5003','Electricity Expense',5,0.00,20000.00,1,'2026-05-17 09:20:03'),(12,'5004','Transport Expense',5,0.00,10200.00,1,'2026-05-17 09:20:03'),(13,'5005','Office Expense',5,0.00,0.00,1,'2026-05-17 09:20:03'),(14,'5006','Hosting Cost',5,0.00,53500.00,1,'2026-05-31 21:19:39'),(15,'5007','Domain Cost',5,0.00,40000.00,1,'2026-05-31 21:19:39'),(16,'5008','Email Services',5,0.00,452000.00,1,'2026-05-31 21:19:39'),(17,'5009','Server Cost',5,0.00,12000.00,1,'2026-05-31 21:19:39'),(18,'5010','Software Subscription',5,0.00,0.00,1,'2026-05-31 21:19:39'),(19,'5011','Internet Expense',5,0.00,5000.00,1,'2026-05-31 21:19:39'),(20,'5012','Telephone Expense',5,0.00,15500.00,1,'2026-05-31 21:19:39'),(21,'5013','Rent Expense',5,0.00,0.00,1,'2026-05-31 21:19:39'),(22,'5014','Fuel Expense',5,0.00,50500.00,1,'2026-05-31 21:19:39'),(23,'5015','Vehicle Expense',5,0.00,0.00,1,'2026-05-31 21:19:39'),(24,'5016','Commission Expense',5,0.00,22500.00,1,'2026-05-31 21:19:39'),(25,'5017','Advertising Expense',5,0.00,19000.00,1,'2026-05-31 21:19:39'),(26,'5018','Client Entertainment',5,0.00,0.00,1,'2026-05-31 21:19:39'),(27,'5019','Legal Charges',5,0.00,0.00,1,'2026-05-31 21:19:39'),(28,'5020','Accounting Fee',5,0.00,0.00,1,'2026-05-31 21:19:39'),(29,'5021','Bank Charges',5,0.00,10200.00,1,'2026-05-31 21:19:39'),(30,'5022','Loan Repayment',5,0.00,0.00,1,'2026-05-31 21:19:39'),(31,'5023','Staff Welfare',5,0.00,5000.00,1,'2026-05-31 21:19:39'),(32,'5024','ChatGPT Subscription',5,0.00,0.00,1,'2026-05-31 21:19:39'),(33,'5025','Zoho Subscription',5,0.00,15000.00,1,'2026-05-31 21:19:39'),(34,'5018','EPF Contribution Expense',5,0.00,82200.00,1,'2026-06-04 18:54:45'),(35,'5019','ETF Contribution Expense',5,0.00,14550.00,1,'2026-06-04 18:54:45'),(36,'1101','HNB Current Account',1,0.00,-52200.00,1,'2026-06-05 17:16:01'),(37,'1102','HNB Saving Account',1,0.00,0.00,1,'2026-06-05 17:16:01'),(38,'1103','NTB Current Account',1,0.00,-94550.00,1,'2026-06-05 17:16:01'),(39,'1104','Sampath Bank Saving Account',1,0.00,-95240.00,1,'2026-06-05 17:16:01'),(40,'1300','Investment Account',1,0.00,100000.00,1,'2026-06-05 17:18:52'),(41,'1003','Petty Cash',1,0.00,0.00,1,'2026-06-11 11:17:23'),(42,'1004','Undeposited Funds',1,0.00,0.00,1,'2026-06-11 11:17:23'),(43,'1201','Prepaid Expenses',1,0.00,0.00,1,'2026-06-11 11:17:23'),(44,'1202','Security Deposits',1,0.00,0.00,1,'2026-06-11 11:17:23'),(45,'1301','Office Equipment',1,0.00,0.00,1,'2026-06-11 11:17:23'),(46,'1302','Computer Equipment',1,0.00,0.00,1,'2026-06-11 11:17:23'),(47,'1303','Furniture & Fixtures',1,0.00,0.00,1,'2026-06-11 11:17:23'),(48,'1304','Motor Vehicles',1,0.00,0.00,1,'2026-06-11 11:17:23'),(49,'2002','Accrued Expenses',2,0.00,0.00,1,'2026-06-11 11:17:46'),(50,'2003','VAT Payable',2,0.00,0.00,1,'2026-06-11 11:17:46'),(51,'2004','EPF Payable',2,0.00,-28000.00,1,'2026-06-11 11:17:46'),(52,'2005','ETF Payable',2,0.00,-4200.00,1,'2026-06-11 11:17:46'),(53,'2006','PAYE Payable',2,0.00,0.00,1,'2026-06-11 11:17:46'),(54,'2007','Sampath Bank Loan',2,0.00,0.00,1,'2026-06-11 11:17:46'),(55,'2008','Director Loan Account',2,0.00,-250000.00,1,'2026-06-11 11:17:46'),(56,'3002','Drawings Account',3,0.00,0.00,1,'2026-06-11 11:18:04'),(57,'3003','Retained Earnings',3,0.00,0.00,1,'2026-06-11 11:18:04'),(58,'3004','Current Year Profit/Loss',3,0.00,0.00,1,'2026-06-11 11:18:04'),(59,'4003','Domain Registration Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(60,'4004','Hosting Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(61,'4005','Software Development Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(62,'4006','Maintenance Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(63,'4007','Consultancy Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(64,'4008','Commission Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(65,'4009','Other Income',4,0.00,0.00,1,'2026-06-11 11:18:13'),(66,'5017','Office Rent Expense',5,0.00,31720.00,1,'2026-06-11 11:18:21'),(67,'5018','Bank Charges',5,0.00,0.00,1,'2026-06-11 11:18:21'),(68,'5019','Printing & Stationery Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(69,'5020','Repairs & Maintenance',5,0.00,0.00,1,'2026-06-11 11:18:21'),(70,'5021','Marketing & Advertising Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(72,'5023','ETF Contribution Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(73,'5024','Professional Fees',5,0.00,0.00,1,'2026-06-11 11:18:21'),(74,'5025','Training Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(75,'5026','Entertainment Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(76,'5027','Cleaning Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(77,'5028','Insurance Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(78,'5029','Office Supplies Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(79,'5030','Other Administrative Expense',5,0.00,0.00,1,'2026-06-11 11:18:21'),(81,'2009','Housing Loan',2,0.00,500000.00,1,'2026-06-17 10:42:06'),(82,'1203','Salary Advances / Employee Loans',1,0.00,0.00,1,'2026-06-19 17:36:37'),(85,'3005','Drawings - Jeyakala',3,0.00,160000.00,1,'2026-06-26 14:53:34'),(87,'3006','Drawings - Hariharan',3,0.00,91000.00,1,'2026-06-26 18:01:23'),(88,'5000','Operating Expenses',5,0.00,5500.00,1,'2026-06-30 11:17:00'),(89,'5100','Vehicle Expenses',5,0.00,0.00,1,'2026-06-30 11:17:28'),(90,'5110','Car Lease Expense',5,0.00,60000.00,1,'2026-06-30 11:18:04'),(91,'5120','Car Rental Expense',5,0.00,90000.00,1,'2026-06-30 11:18:23'),(92,'5121','Water Bill',5,0.00,0.00,1,'2026-07-14 03:51:18'),(93,'5122','CSR / Donation',5,0.00,0.00,1,'2026-07-14 11:52:23');
/*!40000 ALTER TABLE `chart_of_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
  PRIMARY KEY (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_settings`
--

LOCK TABLES `company_settings` WRITE;
/*!40000 ALTER TABLE `company_settings` DISABLE KEYS */;
INSERT INTO `company_settings` VALUES (1,'IT MARTX (PVT) LTD','200, 4th Floor, George R De Silva Mawatha, Colombo 13','0113670717','0701449779','info@itmartx.com','www.itmartx.com','','',NULL,NULL,NULL,'2026-06-02 15:59:15',NULL);
/*!40000 ALTER TABLE `company_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_notes`
--

DROP TABLE IF EXISTS `credit_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Suganth International (Pvt) Ltd','1','2023-01-01','112521059.0','','Mr. Aravinthan','777314141','','COD',0.00,0.00,0,'2024-09-10 16:54:47',0,'2026-07-02 04:13:00',0,'','','','Mr. Aravinthan','777314141','','suganth.lk','2024-01-01','2027-01-01','Active','2026-01-01',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(2,'Kuvi International (Pvt) Ltd','2','2023-01-03','112449035.0','','Mr. Vicneshwaran','777515542','','COD',0.00,0.00,0,'2024-09-10 16:56:49',0,'2026-06-12 05:09:50',0,'','','','Mr. Vicneshwaran','777515542','','kuviceylon.com','2024-01-03','2027-01-03','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(3,'Reliance Steel','3','2023-01-04','77.0','','Mr. Anuragavan','77','','COD',0.00,0.00,0,'2024-09-10 17:15:37',0,'2026-06-12 17:11:42',0,'','','','Mr. Anuragavan','77','','reliancesteel.lk',NULL,'2027-01-04','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(4,'Avon Roofing','4','2023-01-07','77.0','','Mr. Anuragavan','77','','COD',0.00,0.00,0,'2024-09-10 17:20:40',0,'2026-07-02 13:34:11',0,'','','','Mr. Anuragavan','77','','avonroofing.lk',NULL,'2027-01-07','Sent renewal invoice',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(5,'Micro Electric International (Pvt) Ltd','5','2023-01-10','77.0','','Mr. Dhammika','77','','COD',0.00,0.00,0,'2024-09-10 17:23:31',0,'2026-07-08 14:35:24',0,'','','','Mr. Dhammika','77','','micro.lk',NULL,'2027-01-10','Paid',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(6,'Neosys Medicare (Pvt) Ltd','6','2023-01-10','77.0','','Mr. Nihal','77','','COD',0.00,0.00,0,'2024-09-10 18:48:37',0,'2026-07-07 14:13:12',0,'','','','Mr. Nihal','77','','neosys.lk - kandy branch',NULL,'2028-01-10','Paid','2026-07-07',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(7,'Mitsui Agencies (Pvt) Ltd','7','2023-01-15','77.0','','Mr. Umesh','77','','COD',0.00,0.00,0,'2024-09-10 18:52:48',0,'2026-06-12 05:27:16',0,'','','','Mr. Umesh','77','','mitsuiagencies.lk','2024-01-15','2027-01-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(8,'CompanyName','CustomerNo','0000-00-00','Phone','Sid','CustomerName','MobileNo','Address','',0.00,0.00,0,'0000-00-00 00:00:00',0,'2026-06-11 17:02:10',1,'Email','Website','TRNNo','ContactPerson','WhatsAppNo','CustomerGroup','DomainName','0000-00-00','0000-00-00','Active',NULL,0,'Notes','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(9,'Al Aharam','9','2023-01-20','766499946.0','','Mr. Vinoth','766499946','','COD',0.00,0.00,0,'2024-09-10 19:06:50',0,'2026-06-12 05:45:07',0,'','','','Mr. Vinoth','766499946','','alahramsys.com','2024-01-20','2027-01-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(10,'Hara Packaging (Pvt) Ltd','10','2023-01-24','77.0','','Mr. Afdhal','77','','COD',0.00,0.00,0,'2024-09-10 19:07:46',0,'2026-06-12 05:47:42',0,'','','','Mr. Afdhal','77','','harapackaging.com','2024-01-24','2027-01-24','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(11,'Waverley (Pvt) Ltd','11','2023-01-25','77.0','','Mr. Gnana Samarathunga','77','','',0.00,0.00,0,'2024-09-10 19:26:37',0,'2024-09-11 23:59:04',0,'','','','Mr. Gnana Samarathunga','77','','waverley.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(12,'Tokyo Trading Company','12','2023-01-27','112438393.0','','Mr. Theva','772578530','203, Messenger Street, Colombo 12, Sri Lanka','COD',0.00,0.00,0,'2024-09-10 23:10:53',0,'2026-06-12 06:52:50',0,'info@tokyotradinglk.com','','','Mr. Theva','772578530','','tokyotradinglk.com',NULL,'2027-01-27','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(13,'Amcom Gruop','13','2023-01-27','77.0','','Mr. Mustaq','77','','COD',0.00,0.00,0,'2024-09-10 23:11:53',0,'2026-06-12 06:45:27',0,'','','','Mr. Mustaq','77','','amcomgroup.com',NULL,'2027-01-27','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(14,'Appical (Pvt) Ltd','14','2023-02-01','77.0','','Mr. Nishan','77','','',0.00,0.00,0,'2024-09-10 23:13:20',0,'2024-09-11 23:22:10',0,'','','','Mr. Nishan','77','','appical.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(15,'Sunchoice (Pvt) Ltd','15','2023-02-01','77.0','','Mr. Azman','77','','COD',0.00,0.00,0,'2024-09-10 23:18:28',0,'2026-06-12 09:01:29',0,'','','','Mr. Azman','77','','sunchoice.lk',NULL,'2027-02-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(16,'Kksons (Pvt) Ltd','16','2023-02-01','77.0','','Mr. Pradapan','77','','COD',0.00,0.00,0,'2024-09-10 23:19:10',0,'2026-06-12 09:01:19',0,'','','','Mr. Pradapan','77','','kksons.com',NULL,'2027-02-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(17,'Dv Construction (Pvt) Ltd','17','2023-02-01','77.0','','Mr. Dumintha','77','','COD',0.00,0.00,0,'2024-09-10 23:20:00',0,'2026-06-12 09:00:24',0,'','','','Mr. Dumintha','77','','dvconstruction.lk',NULL,'2027-02-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(18,'Macmart','18','2023-02-01','77.0','','Mr. Satar','77','','COD',0.00,0.00,0,'2024-09-10 23:43:22',0,'2026-06-12 09:00:11',0,'','','','Mr. Satar','77','','macmart.lk',NULL,'2027-02-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(19,'Capital Recruitment (Pvt) Ltd','19','2023-02-02','77.0','','Mr. Senthil','77','','',0.00,0.00,0,'2024-09-10 23:46:05',0,'2024-09-11 21:05:02',0,'','','','Mr. Senthil','77','','capitalrecruitment.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(20,'El-Emtyaz','20','2023-02-13','77.0','','Mr. Uwaiz','77','','',0.00,0.00,0,'2024-09-10 23:47:00',0,'2024-09-11 23:22:52',0,'','','','Mr. Uwaiz','77','','el-emtyaz.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(21,'Carmel Shipping (Pvt) Ltd','21','2023-02-17','77.0','','Mr. ','77','','',0.00,0.00,0,'2024-09-10 23:48:18',0,'2024-09-12 00:01:18',0,'','','','Mr. ','77','','carmelshipping.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(22,'Marine Algae Farms (Pvt) Ltd','22','2023-02-17','77.0','','Mr. ','77','','',0.00,0.00,0,'2024-09-10 23:49:15',0,'2024-09-12 00:01:25',0,'','','','Mr. ','77','','marinealgaefarms.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(23,'Mammacook (Pvt) Ltd','23','2023-02-17','77.0','','Mr. Gokulnath','77','','COD',0.00,0.00,0,'2024-09-10 23:50:31',0,'2026-06-12 06:53:50',0,'','','','Mr. Gokulnath','77','','mammacook.com',NULL,'2027-02-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(24,'Jeseem Travels (Pvt) Ltd','24','2023-02-20','77.0','','Mr. Haniff','77','','COD',0.00,0.00,0,'2024-09-10 23:51:31',0,'2026-06-12 07:14:52',0,'','','','Mr. Haniff','77','','jeseemtravel.com',NULL,'2027-02-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(25,'Dreams Way (Pvt) Ltd','25','2023-02-20','77.0','','Mr. Milan','77','','',0.00,0.00,0,'2024-09-10 23:52:34',0,'2024-09-12 00:01:56',0,'','','','Mr. Milan','77','','dreamsway.lk ','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(26,'Micro Electric International (Pvt) Ltd','26','2023-03-01','77.0','','Mr. Dhammika','77','','COD',0.00,0.00,0,'2024-09-11 01:26:57',0,'2026-06-15 15:48:45',1,'','','','Mr. Dhammika','77','','microenterprises.lk',NULL,'2027-03-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(27,'Etimos Lanka','27','2023-03-01','77.0','','Mr. Niroshan','77','','',0.00,0.00,0,'2024-09-11 01:27:56',0,'2024-09-12 00:02:08',0,'','','','Mr. Niroshan','77','','etimoslanka.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(28,'Nagurum','28','2023-03-10','77.0','','Mr. Rajkumar','77','','COD',0.00,0.00,0,'2024-09-11 01:29:20',0,'2026-06-12 09:56:13',0,'','','','Mr. Rajkumar','77','','nagurum.lk',NULL,'2027-03-10','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(29,'Sahabdeen','29','2023-03-13','77.0','','Mr. Sahabdeen','77','','COD',0.00,0.00,0,'2024-09-11 01:30:21',0,'2026-06-12 07:15:38',0,'','','','Mr. Sahabdeen','77','','itmacc.com',NULL,'2027-03-13','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(30,'OSA - Vivekananda College','30','2023-03-13','777.0','','Mr. Suresh','77','','COD',0.00,0.00,0,'2024-09-11 01:31:20',0,'2026-06-12 07:15:53',0,'','','','Mr. Suresh','77','','cvcosa.com',NULL,'2027-03-13','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(31,'Tvesha Lanka (Pvt) Ltd','31','2023-03-14','77.0','','Mr. Madhavan Dy','77','','COD',0.00,0.00,0,'2024-09-11 01:32:16',0,'2026-06-12 07:16:24',0,'','','','Mr. Madhavan Dy','77','','tveshalanka.com',NULL,'2027-03-14','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(32,'Remarko Bakery (Pvt) Ltd','32','2023-03-15','77.0','','Mrs. Hirunika','77','','COD',0.00,0.00,0,'2024-09-11 01:33:16',0,'2026-06-12 07:16:41',0,'','','','Mrs. Hirunika','77','','remarko.lk',NULL,'2027-03-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(33,'Sharwin CC','33','2023-03-21','77.0','','Mr. Ganesh','77','','COD',0.00,0.00,0,'2024-09-11 01:34:19',0,'2026-06-12 07:16:53',0,'','','','Mr. Ganesh','77','','sharwincc.qa',NULL,'2027-03-21','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(34,'Business Machines (Pvt) Ltd','34','2023-03-22','77.0','','Mr. Glen','77','','COD',0.00,0.00,0,'2024-09-11 01:35:03',0,'2026-06-12 07:17:12',0,'','','','Mr. Glen','77','','biz-machines.com',NULL,'2027-03-22','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(35,'Nida Foundation (Pvt) Ltd','35','2023-03-23','77.0','','Mr. Ghani','77','','',0.00,0.00,0,'2024-09-11 01:35:53',0,'2024-09-12 02:43:09',0,'','','','Mr. Ghani','77','','nida.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(36,'Ralhum Sports','36','2023-03-26','77.0','','Mr. Mohamed','77','','COD',0.00,0.00,0,'2024-09-11 01:36:45',0,'2026-06-12 07:18:24',0,'','','','Mr. Mohamed','77','','ralhum.com',NULL,'2027-03-26','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(37,'DSL Enterprises','37','2023-03-27','77.0','','Mr. Asitha','77','','',0.00,0.00,0,'2024-09-11 01:37:34',0,'2026-06-02 11:18:37',1,'','','','Mr. Asitha','77','','dsltrading.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(38,'DSL Enterprises (Pvt) Ltd','38','2023-03-27','77.0','','Mr. Asitha','77','','COD',0.00,0.00,0,'2024-09-11 01:38:17',0,'2026-06-12 07:18:37',0,'','','','Mr. Asitha','77','','dsl.lk',NULL,'2027-03-27','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(39,'Lanka Grills','39','2023-03-28','77.0','','Mr. Remon','77','','',0.00,0.00,0,'2024-09-11 01:38:48',0,'2024-09-12 02:44:01',0,'','','','Mr. Remon','77','','lankagrills.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(40,'Brand Story','40','2023-03-30','77.0','','Mr. Rumesh','77','','',0.00,0.00,0,'2024-09-11 01:39:32',0,'2024-09-12 02:44:10',0,'','','','Mr. Rumesh','77','','brandstory.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(41,'Trans Tours (Pvt) Ltd','41','2023-04-01','112693993.0','','Mr.  M.F. Mackeen','777388343','1169, Maradana Road, Borella, Colombo 8. Sri Lanka.','COD',0.00,0.00,0,'2024-09-11 01:42:21',0,'2026-06-12 09:02:06',0,'','','','Mr.  M.F. Mackeen','777388343','','transtours.lk',NULL,'2027-04-01','Active',NULL,0,'Mr. Azeez','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(42,'Medi Trust International (Pvt) Ltd','42','2023-04-01','77.0','','Mr. Ghani','77','','COD',0.00,0.00,0,'2024-09-11 01:43:10',0,'2026-06-12 09:02:21',0,'','','','Mr. Ghani','77','','meditrust.lk',NULL,'2027-04-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(43,'Masoo Lanka','43','2023-04-09','77.0','','Mr. Mathusanga','77','','COD',0.00,0.00,0,'2024-09-11 01:44:09',0,'2026-06-12 09:59:35',0,'','','','Mr. Mathusanga','77','','masoolanka.com',NULL,'2027-04-09','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(44,'Medi Trust International (Pvt) Ltd','44','2023-04-06','77.0','','Mr. Ghani','77','','',0.00,0.00,0,'2024-09-11 01:44:52',0,'2026-05-30 05:53:16',1,'','','','Mr. Ghani','77','','medi-trust.net','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(45,'Texton (Pvt) Ltd','45','2023-04-19','77.0','','Mr. Rajkumar','77','','COD',0.00,0.00,0,'2024-09-11 01:45:33',0,'2026-06-12 07:18:50',0,'','','','Mr. Rajkumar','77','','texton.lk',NULL,'2027-04-19','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(46,'Bernice Shipping (Pvt) Ltd','46','2023-04-20','77.0','','Mr. Anton','77','','',0.00,0.00,0,'2024-09-11 01:46:30',0,'2024-09-12 02:45:28',0,'','','','Mr. Anton','77','','berniceshipping.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(47,'Kln Logistics (Pvt) Ltd','47','2023-04-20','77.0','','Mr. Vicneshwaran','77','','COD',0.00,0.00,0,'2024-09-11 01:47:37',0,'2026-06-12 07:19:01',0,'','','','Mr. Vicneshwaran','77','','klnlogistics.lk',NULL,'2027-04-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(48,'Neosys Medicare (Pvt) Ltd','48','2023-04-27','77.0','','Mr. Nihal','77','','',0.00,0.00,0,'2024-09-11 01:48:36',0,'2026-06-03 14:38:00',1,'','','','Mr. Nihal','77','','neosys.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(49,'Divolca','49','2023-04-29','77.0','','Mr. Dhammika','77','','COD',0.00,0.00,0,'2024-09-11 01:51:09',0,'2026-07-06 11:49:52',0,'','','','Mr. Dhammika','77','','divolca.lk',NULL,'2028-04-29','Paid','2026-07-06',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(50,'Alpha Aliments Montreal','50','2023-05-02','77.0','','Mr. Kishan','77','','',0.00,0.00,0,'2024-09-11 01:52:09',0,'2024-09-12 02:46:56',0,'','','','Mr. Kishan','77','','alphaalimentsmontreal.ca','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(51,'Neosys Medicare (Pvt) Ltd','51','2023-05-04','77.0','','Mr. Nihal','77','','',0.00,0.00,0,'2024-09-11 01:52:56',0,'2026-06-03 14:37:54',1,'','','','Mr. Nihal','77','','neosysmedicare.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(52,'Lloyds Steel (Pvt) Ltd','52','2023-05-05','77.0','','Mr. Dhayabaran','77','','COD',0.00,0.00,0,'2024-09-11 01:53:58',0,'2026-06-12 09:14:38',0,'','','','Mr. Dhayabaran','77','','lloyds-steel.com',NULL,'2027-05-05','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(53,'Joyful Holy Matrimony','53','2023-05-06','77.0','','Rev. Paston Soloman','77','','',0.00,0.00,0,'2024-09-11 01:55:04',0,'2024-09-11 01:55:04',0,'','','','Rev. Paston Soloman','77','','joyfulholymatrimony.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(54,'Feeds (Pvt) Ltd','54','2023-05-07','77.0','','Mr. Pradapan','77','','COD',0.00,0.00,0,'2024-09-11 01:55:42',0,'2026-06-12 09:51:22',0,'','','','Mr. Pradapan','77','','feeds.lk',NULL,'2026-05-07','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(55,'Nekko Trading (Pvt) Ltd','55','2023-05-07','77.0','','Mr. Theva','77','','COD',0.00,0.00,0,'2024-09-11 01:56:32',0,'2026-06-12 09:51:35',0,'','','','Mr. Theva','77','','nekkotrading.com',NULL,'2027-05-07','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(56,'khakiya','56','2023-05-07','77.0','','Mr. Shiyam','77','','COD',0.00,0.00,0,'2024-09-11 01:58:33',0,'2026-06-12 09:51:57',0,'','','','Mr. Shiyam','77','','khakiya.com',NULL,'2027-05-07','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(57,'Reliance Trading (Pvt) Ltd','57','2023-05-15','77.0','','Mr. Anuragavan','77','','COD',0.00,0.00,0,'2024-09-11 01:59:12',0,'2026-06-12 07:19:28',0,'','','','Mr. Anuragavan','77','','reliancetrading.lk',NULL,'2027-05-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(58,'Ananda Bawan','58','2023-12-12','77.0','','Mr. Jaypragash','77','','COD',0.00,0.00,0,'2024-09-11 02:01:00',0,'2026-06-12 10:01:31',0,'','','','Mr. Jaypragash','77','','anandabawan.lk',NULL,'2026-12-12','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(59,'Daniya Travels','59','2023-05-16','77.0','','Mr. Rimash','77','','',0.00,0.00,0,'2024-09-11 02:01:41',0,'2024-09-11 02:01:41',0,'','','','Mr. Rimash','77','','daniyatravels.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(60,'Ghani Cards','60','2023-05-17','77.0','','Mr. Amar','77','','COD',0.00,0.00,0,'2024-09-11 02:13:25',0,'2026-06-12 07:29:11',0,'','','','Mr. Amar','77','','ghanicards.com',NULL,'2027-05-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(61,'Arabian Gulf','61','2023-05-22','77.0','','Mr. Husni','77','','',0.00,0.00,0,'2024-09-11 02:14:25',0,'2024-09-11 02:14:25',0,'','','','Mr. Husni','77','','arabiangulf.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(62,'D.M. Rajasekara & Sons','62','2023-05-24','77.0','','Mr. Tiran Rajasekara','77','','COD',0.00,0.00,0,'2024-09-11 02:16:12',0,'2026-06-20 08:04:35',0,'','','','Mr. Tiran Rajasekara','77','','dmraja.com',NULL,'2027-05-24','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(63,'Divolca India','63','2023-05-25','77.0','','Mr. Dhammika','77','','',0.00,0.00,0,'2024-09-11 02:21:16',0,'2024-09-11 02:21:16',0,'','','','Mr. Dhammika','77','','divolca.in','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(64,'Lead KNP','64','2023-05-28','77.0','','Mr. Kantheepan','77','','COD',0.00,0.00,0,'2024-09-11 02:22:03',0,'2026-06-12 07:29:38',0,'','','','Mr. Kantheepan','77','','leadknp.com',NULL,'2027-05-28','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(65,'Pathway Global','65','2023-06-01','77.0','','Mr. Adam','77','','COD',0.00,0.00,0,'2024-09-11 02:44:04',0,'2026-06-12 09:04:00',0,'','','','Mr. Adam','77','','pathwayglobalrms.com',NULL,'2027-06-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(66,'Glamours','66','2023-06-01','77.0','','Mr. Ramesh','77','','COD',0.00,0.00,0,'2024-09-11 02:44:45',0,'2026-06-12 09:04:12',0,'','','','Mr. Ramesh','77','','glamours.lk',NULL,'2027-06-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(67,'Puttalam Urban Council','67','2023-06-02','77.0','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 02:45:51',0,'2026-06-12 09:11:31',0,'','','','Mr. ','77','','puttalamuc.com',NULL,'2032-06-02','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(68,'Celogen Lanka (Pvt) Ltd','68','2023-06-07','77.0','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 02:47:01',0,'2026-06-12 09:52:32',0,'','','','Mr. ','77','','celogen.lk',NULL,'2027-06-07','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(69,'Cement Marketing (Pvt) Ltd','69','2023-06-30','77.0','','Mr. Ramanadan','77','','COD',0.00,0.00,0,'2024-09-11 02:48:00',0,'2026-06-12 07:29:51',0,'','','','Mr. Ramanadan','77','','cementmarketing.lk',NULL,'2027-06-30','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(70,'Business Machines (Pvt) Ltd','70','2023-07-01','77.0','','Mr. Glen','77','','',0.00,0.00,0,'2024-09-11 02:49:41',0,'2026-06-08 17:02:09',1,'','','','Mr. Glen','77','','businessmachines.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(71,'Mima Trading (Pvt) Ltd','71','2023-07-03','77.0','','Mr. Ibrahim','77','','',0.00,0.00,0,'2024-09-11 02:50:31',0,'2024-09-11 02:50:31',0,'','','','Mr. Ibrahim','77','','mima.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(72,'Suganth Sea Farm (Pvt) Ltd','72','2023-07-17','77.0','','Mr. Aravinthan','77','','COD',0.00,0.00,0,'2024-09-11 02:51:46',0,'2026-06-12 17:14:16',0,'','','','Mr. Aravinthan','77','','suganthseafarm.com',NULL,'2027-07-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(73,'Neosys Medicare (Pvt) Ltd','73','2023-07-18','77.0','','Mr. Nihal','77','','',0.00,0.00,0,'2024-09-11 02:52:25',0,'2026-06-03 14:37:46',1,'','','','Mr. Nihal','77','','neosys.lk - Kurunegala ','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(74,'Betton Lanka (Pvt) Ltd','74','2023-07-25','77.0','','Mr. Ramanadan','77','','COD',0.00,0.00,0,'2024-09-11 02:53:02',0,'2026-06-12 07:30:31',0,'','','','Mr. Ramanadan','77','','bettonlanka.com',NULL,'2027-07-25','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(75,'Jayatha Holdings (Pvt) Ltd','75','2023-07-25','77.0','','Mr. Chamara','77','','COD',0.00,0.00,0,'2024-09-11 02:54:28',0,'2026-06-12 11:17:58',0,'','','','Mr. Chamara','77','','jayatha.lk',NULL,'2027-07-25','Active','2026-06-12',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(76,'Isuru Lanka','76','2023-07-30','77.0','','Mr. Malage','77','','',0.00,0.00,0,'2024-09-11 02:55:25',0,'2024-09-11 02:55:25',0,'','','','Mr. Malage','77','','isurulanka.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(77,'Lighting Tokyo (Pvt) Ltd','77','2023-08-01','77.0','','Mr. Theva','77','','COD',0.00,0.00,0,'2024-09-11 02:56:18',0,'2026-06-12 09:04:42',0,'','','','Mr. Theva','77','','lightingtokyo.com',NULL,'2027-08-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(78,'Jesus Saves','78','2023-08-01','77.0','','Rev. Pastor Walter Rajamani','77','','COD',0.00,0.00,0,'2024-09-11 02:57:28',0,'2026-06-12 09:04:31',0,'','','','Rev. Pastor Walter Rajamani','77','','jesussaves.lk',NULL,'2027-08-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(79,'Unique Top Jobs','79','2023-08-08','77.0','','Mr. Mohan','77','','COD',0.00,0.00,0,'2024-09-11 02:58:35',0,'2026-06-12 09:54:16',0,'','','','Mr. Mohan','77','','uniquetopjobs.com',NULL,'2026-08-08','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(80,'Roshan International (Pvt) Ltd','80','2023-08-08','77.0','','Mr. Roshan','77','','COD',0.00,0.00,0,'2024-09-11 02:59:24',0,'2026-07-06 11:38:26',0,'','','','Mr. Roshan','77','','roshanint.com',NULL,'2027-08-08','Paid','2026-07-06',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(81,'VR Construction (Pvt) Ltd','81','2023-08-12','77.0','','Mr. Prasanna','77','','COD',0.00,0.00,0,'2024-09-11 03:00:39',0,'2026-07-06 09:15:43',0,'','','','Mr. Prasanna','77','','vrconstruction.lk',NULL,'2027-08-12','Paid','2026-07-06',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(82,'Noda Auto','82','2023-08-15','77.0','','Mr. Ruzly','77','','',0.00,0.00,0,'2024-09-11 03:02:22',0,'2024-10-02 20:20:27',1,'','','','Mr. Ruzly','77','','nodaauto.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(83,'Royal Organic Exports (Pvt) Ltd','83','2023-08-15','77.0','','Mr. Dhammika','77','','COD',0.00,0.00,0,'2024-09-11 03:03:19',0,'2026-06-13 06:42:24',0,'','','','Mr. Dhammika','77','Web Development','royalorganicexports.com',NULL,'2024-08-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(84,'Aslams (Pvt) Ltd','84','2023-08-18','77.0','','Mr. Chamara','77','','COD',0.00,0.00,0,'2024-09-11 03:04:18',0,'2026-06-12 07:34:59',0,'','','','Mr. Chamara','77','','aslams.lk',NULL,'2026-08-18','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(85,'Al Ridha Employment (Pvt) Ltd','85','2023-08-18','77.0','','Mr. Sam','77','','COD',0.00,0.00,0,'2024-09-11 03:05:45',0,'2026-06-12 07:35:12',0,'','','','Mr. Sam','77','','alridhaemp.com',NULL,'2026-08-18','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(86,'Suganth Seafood (Pvt) Ltd','86','2023-08-18','77.0','','Mr. Aravinthan','77','','COD',0.00,0.00,0,'2024-09-11 03:06:40',0,'2026-07-14 07:22:04',0,'','','','Mr. Aravinthan','77','','suganthseafood.lk',NULL,'2027-08-18','Paid','2026-07-14',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(87,'Suganth Seafood (Pvt) Ltd','87','2023-08-18','77.0','','Mr. Aravinthan','77','','COD',0.00,0.00,0,'2024-09-11 03:07:20',0,'2026-07-14 07:28:09',0,'','','','Mr. Aravinthan','77','','suganthsf.lk',NULL,'2026-08-18','Paid',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(88,'Stenco ITC','88','2023-08-21','77.0','','Mr. Thiru','77','','COD',0.00,0.00,0,'2024-09-11 03:08:06',0,'2026-07-17 09:16:05',0,'','','','Mr. Thiru','77','','stencoitc.com',NULL,'2028-08-21','Paid','2026-07-17',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(89,'Neosys Medicare (Pvt) Ltd','89','2023-08-24','77.0','','Mr. Nihal','77','','',0.00,0.00,0,'2024-09-11 03:08:55',0,'2026-06-03 14:37:38',1,'','','','Mr. Nihal','77','','neosys.lk - Pettah','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(90,'Macmix','90','2023-09-01','77.0','','Mr. Saran','77','','COD',0.00,0.00,0,'2024-09-11 03:10:01',0,'2026-06-12 09:05:11',0,'','','','Mr. Saran','77','','macmix.lk',NULL,'2026-09-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(91,'Remarko Institute of Culinary Arts','91','2024-09-01','77.0','','Mrs. Hirunika','77','','COD',0.00,0.00,0,'2024-09-11 03:11:50',0,'2026-06-12 10:03:11',0,'','','','Mrs. Hirunika','77','','rica.lk',NULL,'2026-09-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(92,'RS Roofing','92','2023-09-05','77.0','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 03:12:44',0,'2026-06-12 09:15:12',0,'','','','Mr. ','77','','rsroofing.lk',NULL,'2026-09-05','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(93,'Mytools International (Pvt) Ltd','93','2023-09-07','77.0','','Mr. Faiz','77','','COD',0.00,0.00,0,'2024-09-11 03:13:40',0,'2026-06-12 09:52:42',0,'','','','Mr. Faiz','77','','mytoolsint.com',NULL,'2026-09-07','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(94,'Dayan Lanka','94','2023-09-09','77.0','','Mr. Dayananda','77','','COD',0.00,0.00,0,'2024-09-11 03:14:29',0,'2026-06-12 09:54:58',0,'','','','Mr. Dayananda','77','','dayanlanka.lk',NULL,'2026-09-09','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(95,'Wahaa Manpowers','95','2023-09-10','77.0','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 03:15:10',0,'2026-06-12 09:56:23',0,'','','','Mr. ','77','','wahaamanpowers.com',NULL,'2026-09-10','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(96,'Monara Group of Companies','96','2023-09-14','77.0','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 03:16:37',0,'2026-06-12 07:35:39',0,'','','','Mr. ','77','','monaragroup.com',NULL,'2026-09-14','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(97,'Demasu Group','97','2023-09-17','77.0','','Mr. Surendra Madhavan','77','','COD',0.00,0.00,0,'2024-09-11 03:31:45',0,'2026-07-06 11:35:44',0,'','','','Mr. Surendra Madhavan','77','','demasu.com',NULL,'2027-09-17','Paid','2026-07-06',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(98,'Al Mirqab Real Estate','98','2023-09-20','77.0','','Mr.','77','','COD',0.00,0.00,0,'2024-09-11 03:34:20',0,'2026-06-12 07:36:00',0,'','','','Mr.','77','','almirqabrealestate.com',NULL,'2026-09-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(99,'Asha Recruiting ','99','2023-09-27','77.0','','Mr.','77','','',0.00,0.00,0,'2024-09-11 03:36:56',0,'2024-09-11 03:36:56',0,'','','','Mr.','77','','asharecruiting.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(100,'Reha Steel','100','2023-09-27','77.0','','Mr. Thiru','77','','',0.00,0.00,0,'2024-09-11 03:38:00',0,'2024-09-11 03:38:00',0,'','','','Mr. Thiru','77','','rehasteel.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(101,'Relaxs Steel','101','2023-09-30','77.0','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 03:39:10',0,'2026-07-06 09:47:28',0,'','','','Mr. ','77','','relaxssteel.com',NULL,'2027-09-30','Paid','2026-07-06',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(102,'Step Construction (Pvt) Ltd','102','2023-10-01','77.0','','Mr. Chinthaka','77','','COD',0.00,0.00,0,'2024-09-11 13:55:46',0,'2026-06-12 09:05:48',0,'','','','Mr. Chinthaka','77','','stepsconstruction.com',NULL,'2026-10-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(103,'Global HR','103','2023-10-01','77.0','','Mr. Ziyard','77','','COD',0.00,0.00,0,'2024-09-11 14:15:02',0,'2026-06-12 09:05:40',0,'','','','Mr. Ziyard','77','','globalhr.lk',NULL,'2026-10-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(104,'Devan Holdings','104','2023-10-01','77.0','','Mr. Tharaka','777767018','0777767018','COD',0.00,0.00,0,'2024-09-11 14:15:56',0,'2026-06-12 09:05:25',0,'','','','Mr. Tharaka','777767018','','devanholdings.lk',NULL,'2026-10-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(105,'Jay Associates','105','2023-10-04','77.0','','Mr. Yasi','77','','',0.00,0.00,0,'2024-09-11 14:16:40',0,'2024-10-09 03:30:14',0,'','','','Mr. Yasi','77','','jayassociates.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(106,'Al- Falah ','106','2023-10-04','77.0','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:17:55',0,'2026-06-12 09:14:25',0,'','','','Mr. ','77','','al-falah.lk',NULL,'2026-10-04','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(107,'Smart Job Travels','107','2023-10-05','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:18:54',0,'2026-06-12 09:15:22',0,'','','','Mr. ','77','','smartjobtravels.lk',NULL,'2026-10-05','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(108,'Rj Create','108','2023-10-06','','','Mr. Regan','77','','',0.00,0.00,0,'2024-09-11 14:21:38',0,'2024-09-11 14:21:38',0,'','','','Mr. Regan','77','','rjcreate.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(109,'Abrar Enterprises','109','2023-10-07','','','Mr. ','77','','',0.00,0.00,0,'2024-09-11 14:23:18',0,'2024-09-11 14:23:18',0,'','','','Mr. ','77','','abrarent.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(110,'Al Maanaah ','110','2023-10-09','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:24:49',0,'2026-06-12 09:55:09',0,'','','','Mr. ','77','','alamaanaah.com',NULL,'2026-10-09','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(111,'Betton Lanka (Pvt) Ltd','111','2023-10-10','','','Mr. Ramanadan','77','','',0.00,0.00,0,'2024-09-11 14:30:48',0,'2026-06-05 16:50:50',1,'','','','Mr. Ramanadan','77','','bettonlanka.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(112,'Care Packs','112','2023-10-10','','','Mr. Mubeen','77','','',0.00,0.00,0,'2024-09-11 14:32:06',0,'2024-10-06 00:01:21',1,'','','','Mr. Mubeen','77','','carepacks.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(113,'Myown Trade','113','2023-10-11','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:32:57',0,'2026-06-12 10:00:35',0,'','','','Mr. ','77','','myowntrade.lk',NULL,'2026-10-11','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(114,'Eurozone Recruiting','114','2023-10-12','','','Mr. Mutaz','77','','',0.00,0.00,0,'2024-09-11 14:38:27',0,'2024-09-11 14:38:27',0,'','','','Mr. Mutaz','77','','erzsystem.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(115,'Sree Vision','115','2023-10-13','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:41:02',0,'2026-06-12 07:37:59',0,'','','','Mr. ','77','','sreevision.lk',NULL,'2026-10-13','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(116,'All Brothers International','116','2023-10-15','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:41:47',0,'2026-06-12 07:38:12',0,'','','','Mr. ','77','','allbrothers.lk',NULL,'2026-10-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(117,'Hylex Lighting','117','2023-10-15','','','Mr. Aslam','77','','',0.00,0.00,0,'2024-09-11 14:43:00',0,'2024-09-11 14:43:00',0,'','','','Mr. Aslam','77','','hylexlighting.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(118,'Gavlon','118','2023-10-17','','','Mr. Anuragavan','77','','COD',0.00,0.00,0,'2024-09-11 14:44:03',0,'2026-06-12 07:39:22',0,'','','','Mr. Anuragavan','77','','gavlon.net',NULL,'2026-10-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(119,'Medilab Lanka','119','2023-10-17','','','Dr. Cader','779991548','','COD',0.00,0.00,0,'2024-09-11 14:45:09',0,'2026-06-12 07:38:30',0,'','','','Dr. Cader','779991548','','medilablanka.com',NULL,'2026-10-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(120,'Business Machines (Pvt) Ltd','120','2023-10-18','','','Mr. Glen','77','','',0.00,0.00,0,'2024-09-11 14:45:49',0,'2024-09-14 23:25:23',1,'','','','Mr. Glen','77','','biz-machines.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(121,'Hexagon Lanka (Pvt) Ltd','121','2023-10-20','','','Mr. Rooban','77','','COD',0.00,0.00,0,'2024-09-11 14:46:43',0,'2026-06-12 07:39:33',0,'','','','Mr. Rooban','77','','hexagonlanka.com',NULL,'2026-10-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(122,'Global Humanitarian Foundation','122','2023-10-20','','','Mr. Guruge','77','','',0.00,0.00,0,'2024-09-11 14:47:47',0,'2024-09-11 14:47:47',0,'','','','Mr. Guruge','77','','globalhumanitarianfoundation.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(123,'Amja Recruitments','123','2023-10-20','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:48:44',0,'2026-06-12 07:39:55',0,'','','','Mr. ','77','','amjarecruitments.com',NULL,'2026-10-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(124,'Globe Hardware','124','2023-10-24','','','Mr. Mahendiran','77','','COD',0.00,0.00,0,'2024-09-11 14:49:50',0,'2026-06-12 07:41:56',0,'','','','Mr. Mahendiran','77','','globehardware.lk',NULL,'2026-10-24','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(125,'Relax Steels','125','2023-10-24','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:51:02',0,'2026-06-12 07:42:09',0,'','','','Mr. ','77','','relaxsteels.lk',NULL,'2026-10-24','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(126,'Safari Travels','126','2023-10-29','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 14:53:10',0,'2026-06-12 07:42:23',0,'','','','Mr. ','77','','safaritravels.lk',NULL,'2026-10-29','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(127,'Deday Holidays','127','2023-10-29','','','Mrs. Vani','77','','',0.00,0.00,0,'2024-09-11 14:53:56',0,'2024-09-11 14:53:56',0,'','','','Mrs. Vani','77','','dedayholidays.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(128,'Medilab Lanka','128','2023-11-01','','','Dr. Cader','77','','COD',0.00,0.00,0,'2024-09-11 17:22:01',0,'2026-06-12 09:08:47',0,'','','','Dr. Cader','77','','medilablanka.lk - mts',NULL,'2026-11-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(129,'Sunrise Vacanza','129','2023-11-01','','','Mr. Kantheepan','77','','COD',0.00,0.00,0,'2024-09-11 17:24:22',0,'2026-06-12 09:08:35',0,'','','','Mr. Kantheepan','77','','sunrisevacanza.com',NULL,'2026-11-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(130,'Live Life','130','2023-11-01','','','Mr. Nishan','77','','COD',0.00,0.00,0,'2024-09-11 17:25:02',0,'2026-06-12 09:06:04',0,'','','','Mr. Nishan','77','','livelife.lk',NULL,'2027-11-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(131,'AB Travels','131','2023-11-05','','','Mr. ','77','','',0.00,0.00,0,'2024-09-11 17:28:34',0,'2024-09-11 17:28:34',0,'','','','Mr. ','77','','abtravels.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(132,'Dana Manpower','132','2023-10-12','722282548.0','','Mr. Dana','722282548','','COD',0.00,0.00,0,'2024-09-11 17:29:31',0,'2026-06-12 10:01:07',0,'','','','Mr. Dana','722282548','','danamanpower.lk',NULL,'2026-10-12','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(133,'Pettah Essense ','133','2023-11-05','','','Mr. Kokul','77','','COD',0.00,0.00,0,'2024-09-11 17:30:31',0,'2026-06-12 09:13:54',0,'','','','Mr. Kokul','77','','peslk.com',NULL,'2026-11-05','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(134,'Miracle Fashion','134','2023-11-05','','','Mr. Murali','77','','',0.00,0.00,0,'2024-09-11 17:31:27',0,'2024-09-11 17:31:27',0,'','','','Mr. Murali','77','','miraclefashions.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(135,'Wide Infinity','135','2023-10-10','','','Mr. Dumintha','77','','',0.00,0.00,0,'2024-09-11 17:32:24',0,'2024-09-11 17:32:24',0,'','','','Mr. Dumintha','77','','wideinfinity.ae','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(136,'Wide Infinity','136','2023-11-10','','','Mr. Dumintha','77','','',0.00,0.00,0,'2024-09-11 17:33:14',0,'2024-10-27 17:32:22',1,'','','','Mr. Dumintha','77','','wideinfinity.ae','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(137,'One Genuine Travels','137','2023-11-11','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 17:34:30',0,'2026-06-12 10:00:46',0,'','','','Mr. ','77','','onegenuinetravels.lk',NULL,'2026-11-11','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(138,'Nilwala Manpower','138','2023-11-18','','','Mr. ','77','','',0.00,0.00,0,'2024-09-11 17:37:11',0,'2024-09-11 17:37:11',0,'','','','Mr. ','77','','nilwalagoup.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(139,'Nithiyani Manpower','139','2023-11-19','777791706.0','','Mr. Uwaiz','777791706','','COD',0.00,0.00,0,'2024-09-11 17:39:21',0,'2026-06-12 07:42:36',0,'','','','Mr. Uwaiz','777791706','','nithyanienterprises.lk',NULL,'2026-11-19','Active',NULL,0,'+94 77 570 1099 - Son','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(140,'Leaders Travels','140','2023-11-19','','','Mr. ','77','','',0.00,0.00,0,'2024-09-11 17:41:21',0,'2024-09-11 17:41:21',0,'','','','Mr. ','77','','leadertravels.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(141,'Sonak Lanka','141','2023-11-24','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 17:42:53',0,'2026-06-12 07:42:46',0,'','','','Mr. ','77','','sonak.lk',NULL,'2026-11-24','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(142,'Asian Manpower','142','2023-11-24','777322074.0','','Mr. Akram','777322074','','COD',0.00,0.00,0,'2024-09-11 17:43:55',0,'2026-06-12 07:42:56',0,'','','','Mr. Akram','777322074','','asianmanpower.lk',NULL,'2026-11-24','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(143,'Air Express','143','2023-11-25','','','Mr. A.L.M. Iqbal','759999994','','COD',0.00,0.00,0,'2024-09-11 17:44:36',0,'2026-06-12 07:43:06',0,'','','','Mr. A.L.M. Iqbal','759999994','','airexpress.lk',NULL,'2026-11-25','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(144,'Al Eman','144','2024-11-27','','','Mrs. ','77','','',0.00,0.00,0,'2024-09-11 17:45:32',0,'2024-09-11 17:45:32',0,'','','','Mrs. ','77','','aleman.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(145,'Yameena','145','2023-11-28','','','Mrs.','77','','COD',0.00,0.00,0,'2024-09-11 17:46:11',0,'2026-06-12 07:45:58',0,'','','','Mrs.','77','','yameena.lk',NULL,'2026-11-28','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(146,'Lanka Brass','146','2023-12-01','','','Mr. Senthuran','77','','COD',0.00,0.00,0,'2024-09-11 17:47:20',0,'2026-06-12 09:09:00',0,'','','','Mr. Senthuran','77','','lankabrass.com',NULL,'2026-12-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(147,'Stenco ITC','147','2023-12-03','','','Mr. Thiru','77','','COD',0.00,0.00,0,'2024-09-11 18:02:21',0,'2026-06-13 06:44:20',1,'','','','Mr. Thiru','77','','stencoitc.com',NULL,'2026-12-03','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(148,'Abu Imaani','148','2023-12-05','','','Mr.','77','','COD',0.00,0.00,0,'2024-09-11 18:06:18',0,'2026-06-12 09:14:14',0,'','','','Mr.','77','','abuimaani.lk',NULL,'2026-12-05','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(149,'Solid Gold Manpower','149','2023-12-05','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 18:06:53',0,'2026-06-12 09:14:05',0,'','','','Mr. ','77','','solidgold.lk',NULL,'2026-12-05','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(150,'Trans Tours (Pvt) Ltd','150','2023-12-05','','','Mr. Azeez','77','','',0.00,0.00,0,'2024-09-11 18:09:28',0,'2024-10-02 19:24:54',1,'','','','Mr. Azeez','77','','transtours.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(151,'Care Packs','151','2023-12-07','','','Mr. Mubeen','77','','COD',0.00,0.00,0,'2024-09-11 18:10:45',0,'2026-06-12 09:53:00',0,'','','','Mr. Mubeen','77','','carepacks.lk',NULL,'2026-12-07','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(152,'Lloyds Steel (Pvt) Ltd','152','2023-12-12','','','Mr. Dhayabaran','77','','',0.00,0.00,0,'2024-09-11 18:11:35',0,'2024-09-21 01:02:34',1,'','','','Mr. Dhayabaran','77','','lloyds-steel.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(153,'Reliance Trading (Pvt) Ltd','153','2023-12-12','','','Mr. Anuragavan','77','','COD',0.00,0.00,0,'2024-09-11 18:13:24',0,'2026-06-12 10:01:16',0,'','','','Mr. Anuragavan','77','','mysteel.lk',NULL,'2026-12-12','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(154,'RS Samarasinghe','154','2023-08-01','','','Mr. Samarasinghe','77','','COD',0.00,0.00,0,'2024-09-11 18:15:31',0,'2026-06-12 09:04:23',0,'','','','Mr. Samarasinghe','77','','rssamarasinghe.lk',NULL,'2026-08-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(155,'Ceylon Travel Scape','155','2024-12-14','','','Mr.','77','','',0.00,0.00,0,'2024-09-11 18:19:35',0,'2024-09-11 18:19:35',0,'','','','Mr.','77','','ceylontravelscape.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(156,'Vista Recruitment ','156','2023-12-14','','','Mr.','77','','',0.00,0.00,0,'2024-09-11 18:20:48',0,'2024-09-11 18:20:48',0,'','','','Mr.','77','','vistarecruitment.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(157,'Trans World','157','2023-12-16','','','Mr.','77','','COD',0.00,0.00,0,'2024-09-11 18:21:33',0,'2026-06-12 07:44:43',0,'','','','Mr.','77','','transworld.lk',NULL,'2026-12-16','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(158,'Avon Industries','158','2023-12-17','','','Mr. Anuragavan','77','','COD',0.00,0.00,0,'2024-09-11 18:22:15',0,'2026-06-12 07:44:54',0,'','','','Mr. Anuragavan','77','','avonindustries.lk',NULL,'2026-12-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(159,'Sree Narayana Guru Sri Lanka','159','2023-12-20','','','Mr. Surendra Madhavan','77','','COD',0.00,0.00,0,'2024-09-11 18:23:33',0,'2026-06-12 07:45:04',0,'','','','Mr. Surendra Madhavan','77','','sreenarayanagurusrilanka.com',NULL,'2026-12-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(160,'Noda Auto','160','2023-12-21','','','Mr. Ruzly','77','','COD',0.00,0.00,0,'2024-09-11 18:24:29',0,'2026-07-13 02:12:02',0,'','','','Mr. Ruzly','77','','nodaauto.com',NULL,'2026-12-21','Sent renewal invoice',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(161,'Al Nadha','161','2023-12-22','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-11 18:25:10',0,'2026-06-12 07:48:29',0,'','','','Mr. ','77','','alnadha.lk',NULL,'2026-12-22','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(162,'Rupasinghe Steels','162','2023-12-22','','','Mr. Thiru','77','','',0.00,0.00,0,'2024-09-11 18:26:15',0,'2024-09-11 18:26:15',0,'','','','Mr. Thiru','77','','rupasinghesteels.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(163,'Esstee Singapore','163','2023-12-22','','','Mr. ','77','','',0.00,0.00,0,'2024-09-11 18:26:59',0,'2024-09-11 18:26:59',0,'','','','Mr. ','77','','essteesingapore.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(164,'Zumra Travels','164','2024-12-22','','','Mr.','77','','',0.00,0.00,0,'2024-09-11 18:27:48',0,'2024-09-11 18:27:48',0,'','','','Mr.','77','','zumratravels.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(165,'Skyline','165','2023-12-24','','','Mr.','77','','',0.00,0.00,0,'2024-09-11 18:28:52',0,'2024-09-11 18:28:52',0,'','','','Mr.','77','','skylinemms.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(166,'Vivekananda College','166','2023-12-24','','','Mr. Muventhan','77','','',0.00,0.00,0,'2024-09-11 18:33:24',0,'2024-09-11 18:33:24',0,'','','','Mr. Muventhan','77','','vivekanandacollege.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(167,'Hangili','167','2023-12-28','','','Mr. Dev','77','','',0.00,0.00,0,'2024-09-11 18:34:04',0,'2024-09-11 18:34:04',0,'','','','Mr. Dev','77','','hangili.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(168,'A&B Travels','168','2024-01-01','112673022.0','','Mr. Basheer','777367046','43/1, Maligakanda Road, Maradana, Colombo - 10, Sri Lanka.','COD',0.00,0.00,0,'2024-09-11 18:49:09',0,'2026-06-12 01:13:42',0,'m.basheer1712@gmail.com','','','Mr. Basheer','777367046','','aandbtravels.com','2025-01-01','2027-01-01','Active','2026-01-12',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(169,'Ucs Express (Pvt) Ltd','169','2024-01-09','114855722.0','','Mrs. Updani','774544415','160-1/6, Justice Akbar Mawatha, Colombo 02, Sri Lanka.','',0.00,0.00,0,'2024-09-11 21:00:41',0,'2024-09-11 21:00:41',0,'info@ucsexpress.lk','','','Mrs. Updani','774544415','','ucsexpress.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(170,'Al Hudha','170','2024-01-09','','','Mrs.','777','','',0.00,0.00,0,'2024-09-11 21:03:39',0,'2024-09-11 21:03:39',0,'','','','Mrs.','777','','alhudha.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(171,'Monara Group of Companies','171','2024-01-12','','','Mr. ','777','','COD',0.00,0.00,0,'2024-09-11 21:10:18',0,'2026-06-12 10:05:23',0,'','','','Mr. ','777','','monaragroup.lk',NULL,'2027-01-12','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(172,'Keym Exports','172','2024-01-23','','','Mr. Sujan','77','','',0.00,0.00,0,'2024-09-11 22:37:28',0,'2024-09-11 22:37:28',0,'','','','Mr. Sujan','77','','keymexports.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(173,'Maple Ceylonese','173','2024-01-24','','','Mr. Saroonan','77','','',0.00,0.00,0,'2024-09-11 22:40:23',0,'2024-09-11 22:40:23',0,'','','','Mr. Saroonan','77','','mapleceylonese.ca','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(174,'Waverley (Pvt) Ltd','174','2023-02-01','','','Mr. ','77','','',0.00,0.00,0,'2024-09-12 00:00:41',0,'2024-09-12 05:06:30',0,'','','','Mr. ','77','','waverley.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(176,'Srilak Fertilizers (Pvt) Ltd','175','2024-02-08','','','Mr. ','77','','',0.00,0.00,0,'2024-09-14 23:08:04',0,'2024-09-14 23:08:04',0,'','','','Mr. ','77','','srilak.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(177,'New City Manpower','176','2024-02-08','','','Mr. ','77','','',0.00,0.00,0,'2024-09-14 23:09:57',0,'2024-09-15 03:12:16',0,'','','','Mr. ','77','','newcitymms.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(178,'Sunshine Foods (Pvt) Ltd','177','2024-02-12','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-14 23:14:55',0,'2026-06-12 10:05:36',0,'','','','Mr. ','77','','sunshinefoods.lk',NULL,'2027-02-12','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(179,'Sri Gold Way','177','2023-12-12','','','Mr. ','77','','',0.00,0.00,0,'2024-09-14 23:46:39',0,'2024-09-14 23:46:39',0,'','','','Mr. ','77','','srigoldway.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(180,'Arab Wings','178','2024-02-16','','','Mr. ','77','','',0.00,0.00,0,'2024-09-15 00:12:42',0,'2024-09-15 00:12:42',0,'','','','Mr. ','77','','arabwingsmms.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(181,'Sujeshi Trading','179','2024-02-16','','','Mr. ','77','','',0.00,0.00,0,'2024-09-15 00:14:39',0,'2024-09-15 00:14:39',0,'','','','Mr. ','77','','sujeshi.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(182,'Eleganth Holidays (Pvt) Ltd','180','2024-02-18','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-15 00:16:40',0,'2026-06-12 17:11:54',0,'','','','Mr. ','77','','elegantholidays.lk',NULL,'2027-02-18','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(183,'Seylan Manpower','181','2024-02-27','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-15 01:13:44',0,'2026-06-12 07:50:20',0,'','','','Mr. ','77','','seylanmanpower.com',NULL,'2027-02-27','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(184,'Al Amna','182','2024-02-27','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-15 01:29:36',0,'2026-06-12 08:52:47',0,'','','','Mr. ','77','','alamnamms.com ',NULL,'2027-02-27','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(185,'Reliance Trading (Pvt) Ltd','183','2024-02-29','','','Mr. Anuragavan','77','','',0.00,0.00,0,'2024-09-15 01:32:00',0,'2024-09-15 02:59:20',1,'','','','Mr. Anuragavan','77','','reliancetrading.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(186,'Vibeez','183','2024-02-16','','','Mr. ','77','','',0.00,0.00,0,'2024-09-15 03:15:38',0,'2024-09-15 03:15:38',0,'','','','Mr. ','77','','vibeez.ca','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(187,'Sunshine Grand','184','2024-03-06','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-15 19:33:56',0,'2026-06-12 10:03:51',0,'','','','Mr. ','77','','sunshinegrand.lk',NULL,'2027-03-06','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(188,'Sonak Lanka','185','2024-03-08','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-15 19:48:45',0,'2026-06-12 10:04:02',0,'','','','Mr. ','77','','sonak.lk - kurunegala',NULL,'2027-03-08','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(189,'Big Mouth Shop','186','2024-03-11','','','Mr. Selva','77','','',0.00,0.00,0,'2024-09-17 19:13:48',0,'2024-09-17 19:13:48',0,'','','','Mr. Selva','77','','bigmouthshop.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(190,'Golden Wings','187','2024-03-15','','','Mr. ','77','','',0.00,0.00,0,'2024-09-20 04:58:14',0,'2024-09-20 04:58:14',0,'','','','Mr. ','77','','goldenwingsmms.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(191,'Abu Muaadh','188','2024-03-18','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-20 05:02:21',0,'2026-06-12 08:54:02',0,'','','','Mr. ','77','','abumuaadh.com',NULL,'2027-03-18','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(192,'Neosys Medicare (Pvt) Ltd','189','2023-04-27','','','Mr. Nihal','77','','',0.00,0.00,0,'2024-09-20 05:07:41',0,'2026-06-03 14:37:28',1,'','','','Mr. Nihal','77','','neosys.lk - System','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(193,'Seeni 243','190','2024-03-21','','','Mr. Mohan','77','','COD',0.00,0.00,0,'2024-09-20 05:11:28',0,'2026-06-12 08:54:41',0,'','','','Mr. Mohan','77','','seeni243.com',NULL,'2027-03-21','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(194,'Wild Ele 243','191','2024-03-21','','','Mr. Mohan','77','','COD',0.00,0.00,0,'2024-09-20 05:12:23',0,'2026-06-12 08:54:11',0,'','','','Mr. Mohan','77','','wildele243.com',NULL,'2027-03-21','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(195,'Tex Links LLC','192','2024-03-25','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-20 05:16:06',0,'2026-06-12 08:54:51',0,'','','','Mr. ','77','','texlinksllc.com ',NULL,'2027-03-25','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(196,'Lakpriya Enterprises','193','2024-03-29','','','Mrs.','77','','COD',0.00,0.00,0,'2024-09-20 05:18:52',0,'2026-06-12 08:55:12',0,'','','','Mrs.','77','','lakpriyaenterprises.com',NULL,'2026-03-29','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(197,'Smart Travel Agency','194','2024-04-03','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-20 23:49:29',0,'2026-06-12 10:04:20',0,'','','','Mr. ','77','','smarttravelagency.lk',NULL,'2027-04-03','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(198,'Ceylon Coffee Cocoa','195','2024-04-05','','','Mr. Nishan','77','','',0.00,0.00,0,'2024-09-20 23:54:15',0,'2024-09-20 23:54:15',0,'','','','Mr. Nishan','77','','ceyloncoffeecocoa.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(199,'Ceylon Coffee and Cacao','196','2024-04-08','','','Mr. Nishan','77','','',0.00,0.00,0,'2024-09-20 23:55:37',0,'2024-09-20 23:55:37',0,'','','','Mr. Nishan','77','','ceyloncoffeeandcacao.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(200,'Ceylon Coffee and Cocoa','197','2024-04-08','','','Mr. Nishan','77','','',0.00,0.00,0,'2024-09-20 23:57:06',0,'2024-09-20 23:57:06',0,'','','','Mr. Nishan','77','','ceyloncoffeeandcocoa.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(201,'Trust Happy and Share','198','2024-04-08','','','Mr. Nishan','77','','',0.00,0.00,0,'2024-09-20 23:58:15',0,'2024-09-20 23:58:15',0,'','','','Mr. Nishan','77','','trusthappyandshare.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(202,'Induwara Lanka','199','2024-04-08','','','Mr. Dilshan','0741399001','','COD',0.00,0.00,0,'2024-09-21 00:02:47',0,'2026-07-24 07:09:34',0,'','','','Mr. ','0741399001','','induwaracare.com',NULL,'2027-08-14','Paid','2026-07-24',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(203,'ICAST','200','2024-04-09','','','Mr. ','77','','',0.00,0.00,0,'2024-09-21 00:06:10',0,'2024-09-21 00:06:10',0,'','','','Mr. ','77','','icast.edu.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(204,'Sri Ponnambalavaneswarar Kovil','201','2024-04-17','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-21 00:14:54',0,'2026-06-12 08:55:21',0,'','','','Mr. ','77','','sriponnambalavaneswarar.com',NULL,'2027-04-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(205,'Medi Crest','202','2024-04-24','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-21 00:24:36',0,'2026-07-13 04:17:12',0,'','','','Mr. ','77','','medicrest.lk',NULL,'2027-04-24','Sent renewal invoice',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(206,'Eunice Shipping','203','2024-04-30','','','Mr. ','77','','',0.00,0.00,0,'2024-09-21 00:33:18',0,'2024-09-21 00:33:18',0,'','','','Mr. ','77','','euniceshipping.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(207,'Sai Sangeetha','204','2024-05-18','','','Mr. ','77','','',0.00,0.00,0,'2024-09-21 00:54:28',0,'2024-09-21 00:54:28',0,'','','','Mr. ','77','','saisangeetha.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(208,'VK Tyres','205','2024-05-20','','','Mr. ','77','','',0.00,0.00,0,'2024-09-21 00:56:08',0,'2024-09-21 00:56:08',0,'','','','Mr. ','77','','vktyres.lk','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(209,'Unico Manpower','206','2024-05-20','','','Mr. ','77','','COD',0.00,0.00,0,'2024-09-21 00:57:45',0,'2026-06-12 08:57:56',0,'','','','Mr. ','77','','unicomanpower.com',NULL,'2027-05-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(210,'Tripex International','205','2024-06-15','','','Tripex','77','','COD',0.00,0.00,0,'2024-10-02 20:16:07',0,'2026-06-12 08:58:11',0,'','','','Tripex','77','','tripex.lk',NULL,'2026-06-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(211,'Vinmax','205','2024-07-01','','','Mr. ','77','','COD',0.00,0.00,0,'2024-10-03 01:04:36',0,'2026-06-13 06:30:25',0,'','','','Mr. ','77','','vinmax.lk',NULL,'2027-07-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(212,'Viro Manpower','206','2024-07-03','','','Mr. ','77','','',0.00,0.00,0,'2024-10-03 14:44:46',0,'2024-10-03 14:44:46',0,'','','','Mr. ','77','','viromanpower.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(213,'Arabian Recruiting','207','2024-07-19','','','Mr. ','77','','',0.00,0.00,0,'2024-10-03 17:20:35',0,'2024-10-03 17:20:35',0,'','','','Mr. ','77','','arabianrecruiting.com','0000-00-00','0000-00-00','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(214,'Jewel Exchange','208','2024-07-17','','','Mr. ','77','','COD',0.00,0.00,0,'2024-10-03 17:22:54',0,'2026-07-13 07:35:43',0,'','','','Mr. ','0773041287','','jewelexchange.lk',NULL,'2027-07-17','Paid','2026-07-13',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(215,'Suganth International (Pvt) Ltd','8','2023-01-19','77','','Mr. Aravinthan','77','','COD',0.00,0.00,0,'2024-09-10 18:53:25',0,'2026-06-12 05:49:06',0,'','','','Mr. Aravinthan','77','','ssf.lk','2024-01-19','2027-01-19','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(216,'Jal Japan','00208','2026-01-01','',NULL,'Mr. Faznaz Zakeer','+94719316713','','COD',0.00,0.00,NULL,'2026-05-29 15:50:46',NULL,'2026-06-12 10:06:51',0,'','jaljapan.co.jp',NULL,'','+94719316713','','',NULL,'2027-01-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(217,'YS Tools','00209','2026-01-01','',NULL,'Mr. Amar','0765788588','','COD',0.00,0.00,NULL,'2026-05-29 15:55:58',NULL,'2026-06-12 10:06:45',0,'','ystools.lk',NULL,'','','','',NULL,'2027-01-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(218,'Maarji Herbal Products','00210','2026-01-01','',NULL,'Mr. Nimantha','77','','COD',0.00,0.00,NULL,'2026-05-29 16:00:31',NULL,'2026-06-12 10:07:27',0,'','',NULL,'','','','maarjierp.com',NULL,'2027-01-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(219,'TSF Lanka','00211','2026-01-01','',NULL,'Mr. Azeez','0771122440','','COD',0.00,0.00,NULL,'2026-05-30 05:27:38',NULL,'2026-06-12 10:06:59',0,'','',NULL,'','0771122440','','tsflanka.lk',NULL,'2027-01-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(220,'TSR Consultancy','00212','2026-01-30','',NULL,'Mr. Robinson','0777279506','','COD',0.00,0.00,NULL,'2026-05-30 05:50:19',NULL,'2026-06-12 08:58:35',0,'','',NULL,'','0777279506','','',NULL,'2027-01-30','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(221,'Jacob Avenue','00212','2026-01-01','',NULL,'Mr. Shane','0773952316','','COD',0.00,0.00,NULL,'2026-05-30 06:11:13',NULL,'2026-06-12 10:06:01',0,'','',NULL,'','0773952316','','jacobavenue.lk',NULL,'2027-01-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(222,'Sri Lanka Tours Hub','00213','2026-01-03','',NULL,'Mr. Ravindra','0762154925','','COD',0.00,0.00,NULL,'2026-05-30 06:26:08',NULL,'2026-06-12 01:18:59',0,'','',NULL,'','0762154925','','srilankatourshub.lk','2027-01-03','2027-01-03','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(223,'Twinkle Multi Corner','00214','2026-01-01','',NULL,'Mr. Sathurshan','0766755834','','COD',0.00,0.00,NULL,'2026-05-30 06:28:50',NULL,'2026-07-02 08:26:31',0,'','',NULL,'','0766755834','','twinklemulticorner.lk',NULL,'2027-01-01','Paid',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(224,'NK Associates','00215','2026-01-01','',NULL,'Mr. Robinson','0777279506','','COD',0.00,0.00,NULL,'2026-05-30 08:52:20',NULL,'2026-06-12 10:06:34',0,'','',NULL,'','0777279506','','nkassociates.lk',NULL,'2027-01-01','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(225,'Janasri','00207','2023-01-10','',NULL,'Mr. Pradeep','77','','COD',0.00,0.00,NULL,'2026-06-13 02:27:15',NULL,'2026-06-13 02:27:15',0,'','janasri.lk',NULL,'','','Web Development','janasri.lk',NULL,'2027-01-10','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(226,'RedBox EXO','00208','2023-02-06','',NULL,'Mr. Shane','77','','COD',0.00,0.00,NULL,'2026-06-13 02:33:16',NULL,'2026-06-13 02:33:16',0,'','',NULL,'','','Web Development','redboxexo.com',NULL,'2027-02-06','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(227,'trkwindows.ca','00209','2025-02-08','',NULL,'Mrs. Renuka','77','','COD',0.00,0.00,NULL,'2026-06-13 02:35:53',NULL,'2026-06-13 02:35:53',0,'','',NULL,'','','Web Development','trkwindows.ca',NULL,'2027-02-08','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(228,'Naveen Ceramic','00210','2025-03-11','',NULL,'Mr. ','77','','COD',0.00,0.00,NULL,'2026-06-13 04:28:10',NULL,'2026-06-13 04:28:10',0,'','',NULL,'','','Web Development','naveen.lk',NULL,'2026-03-11','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(229,'Trans Guard','00211','2025-03-17','',NULL,'Mr. Rizwan','77','','COD',0.00,0.00,NULL,'2026-06-13 04:37:41',NULL,'2026-06-13 04:37:41',0,'','',NULL,'','','Web Development','transguardsys.com',NULL,'2026-03-17','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(230,'Carolina Academy','00212','2025-03-15','',NULL,'Mr. Shane','77','','COD',0.00,0.00,NULL,'2026-06-13 04:40:53',NULL,'2026-06-13 04:40:53',0,'','',NULL,'','','','carolinaacademy.lk',NULL,'2027-03-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(231,'Regal Gardens','00213','2024-03-22','',NULL,'Mr. Rusly','77','','COD',0.00,0.00,NULL,'2026-06-13 04:49:30',NULL,'2026-06-13 04:49:30',0,'','',NULL,'','','ERP','regalgardens.lk',NULL,'2027-03-22','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(232,'Carson Enterprises','00214','2025-03-22','',NULL,'Mr. ','77','','COD',0.00,0.00,NULL,'2026-06-13 04:51:34',NULL,'2026-06-13 04:51:34',0,'','',NULL,'','','Web Development','carson.lk',NULL,'2027-03-22','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(233,'97 / 2000 Foundation of VC','00215','2025-05-09','',NULL,'Mr. S. Hariharan','77','','COD',0.00,0.00,NULL,'2026-06-13 05:23:39',NULL,'2026-06-13 05:23:39',0,'','',NULL,'','','Web Development','97-2000foundation.com',NULL,'2026-05-09','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(234,'Warp Ledger','00216','2025-05-15','',NULL,'Mr. Sree','77','','COD',0.00,0.00,NULL,'2026-06-13 05:25:24',NULL,'2026-06-13 05:25:24',0,'','',NULL,'','','Web Development','warpledger.com',NULL,'2026-05-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(235,'RS Lanka','00217','2025-05-25','',NULL,'Mr. Kasun Harshana De Silva','77','','COD',0.00,0.00,NULL,'2026-06-13 05:31:16',NULL,'2026-07-16 10:30:59',0,'','',NULL,'','','Web Development','rslanka.lk',NULL,'2027-05-25','Paid',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(236,'New Nithyani','00218','2025-06-16','',NULL,'Mr. ','77','','COD',0.00,0.00,NULL,'2026-06-13 05:35:20',NULL,'2026-06-13 05:35:20',0,'','',NULL,'','','','newnithyani.com',NULL,'2027-06-16','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(237,'Kanlook Khai','00219','2025-06-26','',NULL,'Mr. ','77','','COD',0.00,0.00,NULL,'2026-06-13 06:28:08',NULL,'2026-06-13 06:28:08',0,'','',NULL,'','','','kanlookthai.com',NULL,'2026-06-26','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(238,'Royal Connect','00220','2025-07-09','',NULL,'Mr.','77','','COD',0.00,0.00,NULL,'2026-06-13 06:31:47',NULL,'2026-06-24 05:43:54',0,'','',NULL,'','','Web Development','royalconnect.lk',NULL,'2027-07-09','Active','2026-06-24',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(239,'Max Rich Way','00221','2025-07-23','',NULL,'Mr. ','77','','COD',0.00,0.00,NULL,'2026-06-13 06:34:51',NULL,'2026-06-16 09:31:38',0,'','',NULL,'','','','maxrichway.com',NULL,'2027-07-23','Active','2026-06-16',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(240,'Aal Albait Masjid','00222','2025-07-25','',NULL,'Mr','77','','COD',0.00,0.00,NULL,'2026-06-13 06:38:20',NULL,'2026-07-02 13:55:43',1,'','',NULL,'','','Web Development','aalalbaitmasjid.com',NULL,'2026-07-25','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(241,'Rainbow Agency','00223','2025-07-28','',NULL,'Mr.','77','','COD',0.00,0.00,NULL,'2026-06-13 06:40:02',NULL,'2026-06-13 06:40:02',0,'','',NULL,'','','Software','rainbowagencymms.com',NULL,'2026-07-28','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(242,'Bluminz','00224','2025-08-25','',NULL,'Mr.','77','','COD',0.00,0.00,NULL,'2026-06-13 06:43:51',NULL,'2026-07-01 15:52:01',1,'','',NULL,'','','','bluminz.com',NULL,'2026-08-25','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(243,'Brandlabel','00224','2025-08-21','',NULL,'Mr.','77','','COD',0.00,0.00,NULL,'2026-06-13 06:45:32',NULL,'2026-07-07 14:12:05',0,'','',NULL,'','','Web Development','brandlabel.lk',NULL,'2027-08-21','Paid','2026-07-07',0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(244,'A Al Albait Masjid','00224','2025-07-26','',NULL,'Mr. Yoosuf','0777009679','','COD',0.00,0.00,NULL,'2026-06-26 09:33:22',NULL,'2026-07-02 14:02:08',0,'','aalalbaitmasjid.com',NULL,'','','ERP','aalalbaitmasjid.com',NULL,'2026-07-26','Sent renewal invoice',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(245,'Bluminz','00225','2025-08-20','',NULL,'Mr. Asitha','77','','COD',0.00,0.00,NULL,'2026-07-01 15:51:16',NULL,'2026-07-01 15:51:16',0,'','bluminz.com',NULL,'','','','bluminz.com',NULL,'2026-08-20','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(246,'IT MARTX','00224','2011-09-30','0113670717',NULL,'S. Hariharan','0701449779','','COD',0.00,0.00,NULL,'2026-07-06 07:21:40',NULL,'2026-07-06 07:21:40',0,'info@itmartx.com','itmartx.com',NULL,'','0701449779','','itmartx.com',NULL,'2026-09-30','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(247,'Sahra Pool Care','00225','2025-08-14','',NULL,'Mr. Dilshan','77','','COD',0.00,0.00,NULL,'2026-07-12 19:38:54',NULL,'2026-07-12 17:33:45',0,'','sahrapoolcare.ae',NULL,'','','Web Development','sahrapoolcare.ae',NULL,'2026-08-14','Sent renewal invoice',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(248,'Sporty Club','00226','2026-07-15','',NULL,'Mr. Sakthivel','0773041287','','COD',0.00,0.00,NULL,'2026-07-16 20:35:27',NULL,'2026-07-16 15:05:27',0,'itmartx@gmail.com','sportyclub.org',NULL,'','','Software','sportyclub.org',NULL,'2027-07-15','Active',NULL,0,'','Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0),(249,'Walk-in Customer','00248','2026-07-26','-','62793','Walk-in Customer','-','-','COD',0.00,0.00,NULL,'2026-07-26 05:00:08',NULL,'2026-07-26 05:00:08',0,'-',NULL,NULL,'Cash Customer',NULL,NULL,NULL,NULL,NULL,'Active',NULL,0,NULL,'Business',NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_domains`
--

DROP TABLE IF EXISTS `customer_domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_domains` (
  `DomainID` int NOT NULL AUTO_INCREMENT,
  `CustomerID` int NOT NULL,
  `DomainName` varchar(255) NOT NULL,
  `RegistrationDate` date DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL,
  `AnnualFee` decimal(10,2) DEFAULT '0.00',
  `Status` varchar(20) DEFAULT 'Active',
  PRIMARY KEY (`DomainID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_domains`
--

LOCK TABLES `customer_domains` WRITE;
/*!40000 ALTER TABLE `customer_domains` DISABLE KEYS */;
INSERT INTO `customer_domains` VALUES (3,6,'neosys.lk - Kurunegala branch',NULL,NULL,0.00,'Active'),(6,6,'neosysmedicare.com',NULL,NULL,0.00,'Active'),(7,34,'businessmachines.lk',NULL,NULL,0.00,'Active'),(8,38,'dsltrading.ccom',NULL,NULL,0.00,'Active'),(9,5,'microenterprises.com',NULL,NULL,0.00,'Active'),(11,4,'Avon System',NULL,'2026-09-24',25000.00,'Active'),(12,244,'aalalbaitmasjid.com - Domain + Hosting',NULL,'2026-07-26',42000.00,'Active'),(13,244,'aalalbaitmasjid.com - SSL',NULL,'2026-07-26',15000.00,'Active'),(14,244,'IOS',NULL,'2026-07-26',35000.00,'Active'),(15,202,'induwaracare.com - Emp',NULL,NULL,0.00,'Active'),(16,202,'induwaracare.com - APP',NULL,NULL,0.00,'Active'),(17,205,'medicrest.lk - SSL',NULL,'2026-08-06',0.00,'Active'),(18,88,'Zoho Workplace - Emails',NULL,'2026-07-24',50880.00,'Active');
/*!40000 ALTER TABLE `customer_domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_payments`
--

DROP TABLE IF EXISTS `customer_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_payments`
--

LOCK TABLES `customer_payments` WRITE;
/*!40000 ALTER TABLE `customer_payments` DISABLE KEYS */;
INSERT INTO `customer_payments` VALUES (1,62,1,1,'2026-06-02 00:00:00',100000.00,50000.00,-50000.00,'Cheque',1,'121','2026-06-01','NTB',NULL,'2026-06-02 16:24:35',NULL,'2026-06-04 16:54:35',0,'Pending'),(2,65,1,2,'2026-06-01 00:00:00',21000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00','Sampath Bank',NULL,'2026-06-02 16:46:24',NULL,'2026-06-04 16:54:46',0,'Pending'),(3,38,1,3,'2026-06-02 00:00:00',85680.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00','Sampath Bank',NULL,'2026-06-02 16:52:58',NULL,'2026-06-04 16:54:46',0,'Pending'),(4,32,1,4,'2026-06-03 00:00:00',16000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00','Sampath Bank',NULL,'2026-06-03 12:41:42',NULL,'2026-06-04 16:54:46',1,'Pending'),(5,91,1,5,'2026-06-02 00:00:00',16000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00','Sampath Bank',NULL,'2026-06-03 12:42:08',NULL,'2026-06-04 16:54:46',0,'Pending'),(6,32,1,4,'2026-06-02 00:00:00',16000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00','Sampath Bank',NULL,'2026-06-03 12:42:47',NULL,'2026-06-04 16:54:46',0,'Pending'),(7,6,1,6,'2026-06-03 00:00:00',29400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00','Sampath Bank',NULL,'2026-06-03 22:27:10',NULL,'2026-06-04 16:54:46',0,'Pending'),(8,77,1,7,'2026-06-03 00:00:00',24400.00,0.00,0.00,'Cash',NULL,'','0000-00-00','',NULL,'2026-06-04 05:58:53',NULL,'2026-06-04 05:58:53',0,'Pending'),(9,55,1,8,'2026-06-04 00:00:00',28560.00,0.00,0.00,'Cash',NULL,'','0000-00-00','',NULL,'2026-06-04 06:01:28',NULL,'2026-06-04 06:01:28',0,'Pending'),(10,77,1,9,'2026-06-04 00:00:00',10400.00,0.00,0.00,'Cash',NULL,'','0000-00-00','',NULL,'2026-06-04 06:02:56',NULL,'2026-06-04 06:02:56',0,'Pending'),(11,12,1,10,'2026-06-03 00:00:00',63360.00,0.00,0.00,'Cash',NULL,'','0000-00-00','',NULL,'2026-06-04 06:06:16',NULL,'2026-06-04 06:06:16',0,'Pending'),(12,202,1,11,'2026-06-04 00:00:00',180000.00,180000.00,0.00,'Bank Transfer',1,'','0000-00-00','NTB',1,'2026-06-04 20:19:18',NULL,'2026-07-02 14:02:25',0,'Pending'),(13,17,1,12,'2026-06-05 00:00:00',408000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-05 19:47:06',NULL,'2026-07-02 14:02:25',0,'Pending'),(14,74,1,13,'2026-06-05 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-05 22:22:53',NULL,'2026-07-02 14:02:25',0,'Pending'),(15,72,1,14,'2026-06-05 00:00:00',31200.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-05 22:31:06',NULL,'2026-07-02 14:02:25',0,'Pending'),(16,34,1,15,'2026-06-08 00:00:00',23000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-06-08 22:38:13',NULL,'2026-07-02 14:02:25',0,'Pending'),(17,211,1,16,'2026-06-09 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-09 17:22:14',NULL,'2026-07-02 14:02:25',0,'Pending'),(18,218,1,18,'2026-06-09 00:00:00',45000.00,45000.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-09 17:55:15',NULL,'2026-07-02 14:02:25',0,'Pending'),(19,32,1,17,'2026-06-10 00:00:00',5000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-10 11:38:03',NULL,'2026-07-02 14:02:25',0,'Pending'),(20,75,1,19,'2026-06-12 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-12 16:47:58',NULL,'2026-07-02 14:02:25',0,'Pending'),(21,41,1,20,'2026-06-15 00:00:00',85680.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-06-15 12:56:09',NULL,'2026-07-02 14:02:25',0,'Pending'),(22,5,1,21,'2026-06-15 00:00:00',85680.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-15 21:07:18',NULL,'2026-07-02 14:02:25',0,'Pending'),(23,34,1,22,'2026-06-15 00:00:00',234200.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-15 21:17:10',NULL,'2026-07-02 14:02:25',0,'Pending'),(24,239,1,23,'2026-06-16 00:00:00',21000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-16 15:01:38',NULL,'2026-07-02 14:02:25',0,'Pending'),(25,36,1,25,'2026-06-18 00:00:00',73500.00,73500.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-18 15:25:18',NULL,'2026-07-02 14:02:25',0,'Pending'),(26,238,1,26,'2026-06-24 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-24 11:13:54',NULL,'2026-07-02 14:02:25',0,'Pending'),(27,244,1,27,'2026-06-26 00:00:00',300000.00,350000.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-06-26 15:06:53',NULL,'2026-07-02 14:02:25',0,'Pending'),(28,62,1,1,'2026-06-26 00:00:00',50000.00,0.00,0.00,'Bank Transfer',1,'','0000-00-00',NULL,1,'2026-06-26 16:12:12',NULL,'2026-07-02 14:02:25',0,'Pending'),(29,223,1,28,'2026-07-02 00:00:00',20500.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-02 13:56:31',NULL,'2026-07-02 14:02:25',0,'Pending'),(30,202,1,24,'2026-07-03 00:00:00',145000.00,145000.00,0.00,'Bank Transfer',1,'','0000-00-00',NULL,1,'2026-07-04 11:21:55',NULL,'2026-07-04 11:21:55',0,'Pending'),(31,81,1,39,'2026-07-06 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-06 14:45:43',NULL,'2026-07-06 14:45:43',0,'Pending'),(32,101,1,29,'2026-07-06 00:00:00',28400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-06 15:17:27',NULL,'2026-07-06 15:17:27',0,'Pending'),(33,97,1,30,'2026-07-06 00:00:00',29400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-06 17:05:44',NULL,'2026-07-06 17:05:44',0,'Pending'),(34,80,1,51,'2026-07-06 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-06 17:08:26',NULL,'2026-07-06 17:08:26',0,'Pending'),(35,49,1,52,'2026-07-06 00:00:00',5000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-06 17:19:52',NULL,'2026-07-06 17:19:52',0,'Pending'),(36,88,1,33,'2026-07-06 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-06 18:13:58',NULL,'2026-07-06 18:13:58',0,'Pending'),(37,243,1,32,'2026-07-07 00:00:00',27000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-07 19:42:05',NULL,'2026-07-07 19:42:05',0,'Pending'),(38,6,1,43,'2026-07-07 00:00:00',29400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-07 19:43:12',NULL,'2026-07-07 19:43:12',0,'Pending'),(39,5,1,53,'2026-07-08 00:00:00',29400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-08 20:05:24',NULL,'2026-07-08 14:35:24',0,'Pending'),(40,214,1,58,'2026-07-13 00:00:00',24400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-13 13:05:43',NULL,'2026-07-13 07:35:43',0,'Pending'),(41,86,1,36,'2026-07-14 00:00:00',29400.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-14 12:52:04',NULL,'2026-07-14 07:22:04',0,'Pending'),(42,87,1,35,'2026-07-14 00:00:00',8000.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-14 12:58:09',NULL,'2026-07-14 07:28:09',0,'Pending'),(43,235,1,60,'2026-07-16 00:00:00',18900.00,0.00,0.00,'Bank Transfer',1,'','0000-00-00',NULL,1,'2026-07-16 16:00:59',NULL,'2026-07-16 10:30:59',0,'Pending'),(44,88,1,59,'2026-07-17 00:00:00',50880.00,0.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-17 14:46:05',NULL,'2026-07-17 14:46:05',0,'Pending'),(45,202,1,55,'2026-07-24 00:00:00',81750.00,0.00,0.00,'Bank Transfer',1,'','0000-00-00',NULL,1,'2026-07-24 12:39:34',NULL,'2026-07-24 12:39:34',0,'Pending'),(46,1,1,63,'2026-07-25 00:00:00',100000.00,50000.00,0.00,'Bank Transfer',4,'','0000-00-00',NULL,1,'2026-07-25 11:02:08',NULL,'2026-07-25 11:02:08',0,'Pending'),(47,249,1,64,'2026-07-26 10:30:08',406500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-07-26 10:30:08',NULL,'2026-07-26 10:30:08',0,'Pending'),(48,249,1,65,'2026-07-26 10:38:26',421500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-07-26 10:38:26',NULL,'2026-07-26 10:38:26',0,'Pending'),(49,249,1,66,'2026-07-26 10:42:08',250000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-07-26 10:42:08',NULL,'2026-07-26 10:42:08',0,'Pending'),(50,249,1,67,'2026-07-26 10:42:37',433500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-07-26 10:42:37',NULL,'2026-07-26 10:42:37',0,'Pending'),(51,249,1,68,'2026-07-26 21:09:25',1178500.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-07-26 21:09:25',NULL,'2026-07-26 21:09:25',0,'Pending'),(52,2,1,70,'2026-07-27 00:00:00',200000.00,200000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-07-27 10:23:47',NULL,'2026-07-27 10:23:47',0,'Pending'),(53,3,1,71,'2026-07-27 10:24:05',250000.00,0.00,0.00,'Cash',NULL,'','0000-00-00',NULL,1,'2026-07-27 10:24:05',NULL,'2026-07-27 10:24:05',0,'Pending');
/*!40000 ALTER TABLE `customer_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_subscription_notices`
--

DROP TABLE IF EXISTS `customer_subscription_notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_subscription_notices` (
  `CustomerID` int NOT NULL,
  `InvoiceNo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InvoiceDate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ToDomain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DetailDesc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RenewalPeriod` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `customer_subscription_notices` VALUES (4,'IJH020726-H','September 24, 2026','Avon System','AMC','2026 to 2027',25000.00,'2026-07-02 19:04:11',4),(6,'IJH020726-H','July 1, 2026','www.neosys.lk - pettah branch','Renewal Domain + Hosting','2026 to 2027',29400.00,'2026-07-02 13:03:17',4),(49,'IJH060726-H','July 6, 2026','www.divolca.lk','Renewal Domain','2027 to 2028',5000.00,'2026-07-06 17:19:23',4),(79,'IJH010726-H','July 1, 2026','www.uniquetopjobs.com','Renewal Domain + Hosting','2026 to 2027',24400.00,'2026-07-01 22:14:33',4),(80,'IJH060726-H','July 1, 2026','www.roshanint.com','Renewal Domain + Hosting','2026 to 2027',24400.00,'2026-07-06 17:07:49',4),(84,'IJH020726-H','July 2, 2026','www.aslams.lk','Renewal Domain + Hosting','2026 to 2027',24400.00,'2026-07-02 09:28:16',4),(88,'IJH140726-H','July 14, 2026','Zoho Workplace - Emails','Renewal Email','2026 to 2027',50880.00,'2026-07-14 12:39:08',4),(160,'IJH130726-H','July 13, 2026','www.nodaauto.com','Renewal SSL Certificate','2026 to 2027',11700.00,'2026-07-13 07:42:02',4),(202,'IJH130726-H','July 12, 2026','www.induwaracare.com','Renewal Domain + Hosting + SSL certificate + APP Store (IOS) + Play Store (Android)','2027 to 2028',81750.00,'2026-07-13 07:37:45',1),(205,'IJH130726-H','July 13, 2026','www.medicrest.lk - SSL','Renewal Domain + Hosting','2026 to 2027',10500.00,'2026-07-13 09:47:12',4),(214,'IJH130726-H','June 1, 2026','www.jewelexchange.lk','Renewal Domain + Hosting','2026 to 2027',24400.00,'2026-07-13 13:04:59',4),(216,'TEST-INV-123','2026-07-01','www.testdomain.com','Test description','2026 to 2027',15000.00,'2026-07-01 16:38:02',4),(244,'IJH020726-H','July 2, 2026','www.aalalbaitmasjid.com','Renewal IOS','2026 to 2027',35000.00,'2026-07-02 20:11:13',4),(247,'IJH120726-H','July 12, 2026','www.sahrapoolcare.ae','Renewal Domain + Hosting','2026 to 2027',32500.00,'2026-07-12 23:03:45',1);
/*!40000 ALTER TABLE `customer_subscription_notices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drawings`
--

DROP TABLE IF EXISTS `drawings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drawings`
--

LOCK TABLES `drawings` WRITE;
/*!40000 ALTER TABLE `drawings` DISABLE KEYS */;
INSERT INTO `drawings` VALUES (3,'2026-06-15',4,'Personal Expense','Bank',4,50000.00,'Sasikala','seetu',NULL,1,'2026-06-26 14:53:34'),(4,'2026-06-26',4,'Personal Expense','Bank',4,60000.00,'Sinthu','seetu',NULL,1,'2026-06-26 14:55:12'),(6,'2026-06-26',3,'School Fees','Bank',4,91000.00,'Bishop\'s College','Daughter 3rd Term Fee 2025 ',NULL,1,'2026-06-26 18:01:23'),(7,'2026-07-16',4,'Personal Expense','Bank',4,50000.00,'Sasikala','seetu',NULL,1,'2026-07-16 13:02:10');
/*!40000 ALTER TABLE `drawings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `EmployeeID` int NOT NULL AUTO_INCREMENT,
  `EmployeeNo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `MemberNo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EmployeeName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `NIC` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MobileNo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Designation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BasicSalary` decimal(18,2) DEFAULT '0.00',
  `Allowance` decimal(10,2) DEFAULT '0.00',
  `OTPerHour` decimal(10,2) DEFAULT '0.00',
  `CostPerMinute` decimal(10,2) DEFAULT '0.00',
  `JoinDate` date DEFAULT NULL,
  `Status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
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
INSERT INTO `employee` VALUES (1,'1','1','K. L. Thimothy','200224102928','0774730551','thimo@gmail.com','$2y$10$431VWD3i/YrNXnbfB7qzZOwHLBUili0hHgZBwnAXReRVzfrTMLRT2','','Developer','IT ',35000.00,15000.00,0.00,4.01,'2026-01-01','Active','2026-05-25 06:36:28','2026-07-13 13:17:52',0),(2,'2','2','R. P. Wasana Sandamali','997822757V','0762782807','sanda@gmail.com','$2y$10$qNA1DkXCrugG193Z.rsny.Aqb6Jm5McjA3RJBalB1d.tOtT8rtn.K','','Developer','IT ',35000.00,10000.00,0.00,3.61,NULL,'Active','2026-05-26 11:29:52','2026-07-13 13:37:09',0),(3,'3','','Hariharan','','0773041287','hari@itmartx.com','$2y$10$4gRVwpvoZ2nVKpbOSIFy6uS8UQypEceTDebKx6hIA92Yhhgfsd0LC','','Director & Admin','Admin',30000.00,0.00,0.00,2.40,NULL,'Active','2026-05-29 17:55:06','2026-07-13 13:39:52',0),(4,'4',NULL,'Jeyakala','','0762115572','',NULL,'','Director Accounts & HR','Admin',30000.00,0.00,0.00,2.40,'0000-00-00','Active','2026-05-29 17:55:36','2026-05-29 17:58:59',0),(5,'5',NULL,'Wishwajith','','0766264735','',NULL,'','Developer','IT',30000.00,0.00,0.00,2.40,'0000-00-00','Active','2026-05-29 17:55:59','2026-05-29 17:59:27',0),(6,'6','','Vinuka','','0760034515','vinuka@gmail.com','$2y$10$zy7Evb/IH0e9sBDNcIRBHe9ujw50W8kLaIoT0k5zRSdoZNBjachz.','','Developer','IT',30000.00,0.00,0.00,2.40,NULL,'Active','2026-05-29 17:56:15','2026-07-13 13:40:58',0);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_advances`
--

DROP TABLE IF EXISTS `employee_advances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_advances` (
  `AdvanceID` int NOT NULL AUTO_INCREMENT,
  `EmployeeID` int NOT NULL,
  `AdvanceDate` date NOT NULL,
  `Amount` decimal(18,2) NOT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `PaymentMethod` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `BankID` int DEFAULT NULL,
  `JournalID` int DEFAULT NULL,
  `AmountRecovered` decimal(18,2) DEFAULT '0.00',
  `Status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `IsDeleted` tinyint(1) DEFAULT '0',
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AdvanceID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_advances`
--

LOCK TABLES `employee_advances` WRITE;
/*!40000 ALTER TABLE `employee_advances` DISABLE KEYS */;
INSERT INTO `employee_advances` VALUES (1,2,'2026-06-19',25000.00,'','Bank',4,208,25000.00,'Fully Recovered',0,'2026-06-19 23:11:22');
/*!40000 ALTER TABLE `employee_advances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_leaves`
--

DROP TABLE IF EXISTS `employee_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_leaves` (
  `LeaveID` int NOT NULL AUTO_INCREMENT,
  `EmployeeID` int NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `LeaveType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `CreatedDate` datetime NOT NULL,
  `Remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`LeaveID`),
  KEY `EmployeeID` (`EmployeeID`),
  CONSTRAINT `employee_leaves_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_leaves`
--

LOCK TABLES `employee_leaves` WRITE;
/*!40000 ALTER TABLE `employee_leaves` DISABLE KEYS */;
INSERT INTO `employee_leaves` VALUES (1,1,'2026-07-10','2026-07-12','Annual','Family Trip','Approved','2026-07-08 09:26:10','Okay'),(2,1,'2026-07-24','2026-07-24','Annual','program','Approved','2026-07-13 16:55:27','okay'),(3,1,'2026-07-17','2026-07-17','Casual','test casual leave','Approved','2026-07-13 19:19:51','test'),(4,1,'2026-07-13','2026-07-13','Casual','','Approved','2026-07-14 07:49:05',''),(5,1,'2026-07-13','2026-07-13','Casual','','Approved','2026-07-14 07:50:47',''),(6,1,'2026-07-14','2026-07-14','Sick','','Rejected','2026-07-14 07:52:37',''),(7,1,'2026-07-14','2026-07-14','Casual','','Pending','2026-07-14 07:54:53',NULL),(8,1,'2026-07-29','2026-07-29','Casual','','Approved','2026-07-14 10:09:51',''),(9,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:02:08',NULL),(10,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:03:48',NULL),(11,1,'2026-07-14','2026-07-14','Sick','thimo@gmail.com','Pending','2026-07-14 15:04:30',NULL),(12,1,'2026-07-14','2026-07-14','Sick','thimo@gmail.com','Pending','2026-07-14 15:04:33',NULL),(13,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:05:08',NULL),(14,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:12:14',NULL),(15,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:12:41',NULL),(16,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:12:53',NULL),(17,1,'2026-07-14','2026-07-14','Sick','thimo@gmail.com','Pending','2026-07-14 15:16:05',NULL),(18,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:17:07',NULL),(19,1,'2026-07-14','2026-07-14','Sick','thimo@gmail.com','Pending','2026-07-14 15:20:06',NULL),(20,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:20:08',NULL),(21,1,'2026-07-14','2026-07-14','Casual','thimo@gmail.com','Pending','2026-07-14 15:21:31',NULL),(22,1,'2026-07-14','2026-07-14','Sick','thimo@gmail.com','Pending','2026-07-14 15:23:21',NULL);
/*!40000 ALTER TABLE `employee_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_customers`
--

DROP TABLE IF EXISTS `expense_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_customers` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ExpenseID` int NOT NULL,
  `CustomerId` int NOT NULL,
  `DomainName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_customers`
--

LOCK TABLES `expense_customers` WRITE;
/*!40000 ALTER TABLE `expense_customers` DISABLE KEYS */;
INSERT INTO `expense_customers` VALUES (1,2,15,NULL),(2,32,5,NULL),(3,35,12,NULL),(4,38,32,NULL),(5,39,26,NULL),(6,51,34,NULL),(7,53,38,'dsltrading.ccom'),(9,56,97,'demasu.com'),(10,57,36,'ralhum.com'),(11,58,34,'businessmachines.lk'),(12,27,6,'neosysmedicare.com'),(13,66,41,'transtours.lk'),(14,71,236,'newnithyani.com'),(15,72,97,'demasu.com'),(16,74,5,'micro.lk'),(17,78,41,'transtours.lk'),(18,96,49,'divolca.lk'),(19,97,41,'transtours.lk'),(20,98,246,'itmartx.com'),(21,99,246,'itmartx.com'),(22,110,75,'jayatha.lk'),(23,114,225,'janasri.lk'),(24,117,34,'businessmachines.lk'),(26,118,34,'biz-machines.com'),(27,119,17,'dvconstruction.lk'),(29,121,248,'sportyclub.org');
/*!40000 ALTER TABLE `expense_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'2026-06-01',14,'','ITC',4000.00,'Bank',4,NULL,NULL,'','2026-06-02 20:19:02',0,'',0,NULL,NULL),(2,'2026-06-01',15,'','LK Domain',5000.00,'Bank',4,'','0000-00-00','','2026-06-02 20:19:34',0,'',NULL,NULL,NULL),(3,'2026-06-01',22,'','Vino',10000.00,'Bank',4,NULL,NULL,'Vino cash','2026-06-02 20:20:15',0,'',0,NULL,NULL),(4,'2026-06-01',15,'','ITC',5000.00,'Bank',4,NULL,NULL,'','2026-06-02 20:21:19',0,'',0,NULL,NULL),(5,'2026-06-01',15,'','ITC',5000.00,'Bank',4,NULL,NULL,'','2026-06-02 20:22:52',0,'',0,NULL,NULL),(6,'2026-06-02',10,'','                                    Jeyakala                                ',20000.00,'Bank',4,NULL,NULL,'','2026-06-02 20:26:09',0,'',4,NULL,NULL),(16,'2026-06-02',10,'','                                    Hariharan                                ',10000.00,'Bank',4,NULL,NULL,'','2026-06-03 19:56:01',0,'',3,NULL,NULL),(17,'2026-06-01',10,'','                                    Thinothy                                ',42600.00,'Cheque',1,NULL,NULL,'Month of May','2026-06-03 19:56:54',0,'',1,NULL,NULL),(18,'2026-06-03',19,'','Dialog',5000.00,'Bank',4,NULL,NULL,'','2026-06-03 19:58:47',0,'',0,NULL,NULL),(19,'2026-06-03',20,'0773041287','Dialog',3000.00,'Bank',4,NULL,NULL,'','2026-06-03 20:02:19',0,'',0,NULL,NULL),(20,'2026-06-04',10,'','                                    Hariharan                                ',5000.00,'Bank',4,NULL,NULL,'','2026-06-04 18:29:06',0,'',3,NULL,NULL),(21,'2026-06-04',10,'','                                    Jeyakala                                ',5000.00,'Bank',4,NULL,NULL,'','2026-06-04 18:30:25',0,'',4,NULL,NULL),(22,'2026-06-04',12,'Battaramulla','Vino',1500.00,'Bank',4,NULL,NULL,'','2026-06-04 18:31:13',0,'',0,NULL,NULL),(23,'2026-06-01',34,'Month of April','EPF',12000.00,'Cheque',1,'','2026-06-01','','2026-06-04 18:56:55',0,'',NULL,NULL,NULL),(24,'2026-06-04',34,'','',41400.00,'Cheque',1,NULL,NULL,'','2026-06-04 18:57:40',0,'',0,NULL,NULL),(25,'2026-06-04',35,'from Jan to May','',10350.00,'Cheque',1,NULL,NULL,'','2026-06-04 18:58:58',0,'',0,NULL,NULL),(26,'2026-06-01',29,'NDE Ch','NTB',1200.00,'Bank',1,NULL,NULL,'','2026-06-04 19:08:52',0,'',0,NULL,NULL),(27,'2026-06-04',15,'neosysmedicare.com','Go',5000.00,'Bank',4,'','0000-00-00','','2026-06-04 22:18:49',0,'',NULL,NULL,NULL),(28,'2026-06-06',20,'Fiber','SLT',10000.00,'Bank',4,NULL,NULL,'','2026-06-06 06:27:37',0,'',NULL,NULL,NULL),(29,'2026-06-06',10,'','                                    Jeyakala                                ',10000.00,'Bank',4,NULL,NULL,'','2026-06-06 06:28:51',0,'',4,NULL,NULL),(30,'2026-06-06',12,'DM Raja Visit','Trishaw',1000.00,'Bank',4,NULL,NULL,'','2026-06-06 06:29:30',0,'',NULL,NULL,NULL),(31,'2026-06-06',10,'10K+10K','                                    Hariharan                                ',20000.00,'Bank',4,'','0000-00-00','','2026-06-06 06:29:57',0,'',3,NULL,NULL),(32,'2026-06-07',16,'','Google Workspace',34000.00,'Bank',4,'','0000-00-00','','2026-06-07 11:00:50',0,'',NULL,NULL,NULL),(33,'2026-06-07',14,'itc001','DRP',6500.00,'Bank',4,'','0000-00-00','','2026-06-07 12:12:34',0,'',NULL,NULL,NULL),(34,'2026-06-07',14,'itc019','ITC',4500.00,'Bank',4,'','0000-00-00','','2026-06-07 12:20:03',0,'',NULL,NULL,NULL),(35,'2026-06-08',16,'','Google Workspace',23000.00,'Bank',4,'','0000-00-00','','2026-06-08 09:38:18',0,'',NULL,NULL,NULL),(36,'2026-06-08',22,'','Vino',10000.00,'Bank',4,'','0000-00-00','','2026-06-09 05:35:14',0,'',NULL,NULL,NULL),(37,'2026-06-09',14,'s4','DRP',6000.00,'Bank',4,'','0000-00-00','','2026-06-09 16:55:52',0,'',NULL,NULL,NULL),(38,'2026-06-09',15,'','LK Domain',5000.00,'Bank',4,'','0000-00-00','','2026-06-09 17:45:14',0,'',NULL,NULL,NULL),(39,'2026-06-10',16,'','Google Workspace',57000.00,'Bank',4,'','0000-00-00','','2026-06-10 08:16:50',0,'',NULL,NULL,NULL),(40,'2026-06-10',24,'Acc No: 079-2-001-5-0038794','Maneesha Wanasinghe Wijayalath Pedige Eshan',22500.00,'Bank',4,'','0000-00-00','','2026-06-10 11:31:11',0,'',NULL,NULL,NULL),(41,'2026-06-10',12,'Maarji Courier Charge','PickMe',700.00,'Cash',NULL,'','0000-00-00','','2026-06-10 15:04:18',0,'',NULL,NULL,NULL),(42,'2026-06-10',10,'','                                                Jeyakala                                            ',10000.00,'Bank',4,'','0000-00-00','','2026-06-10 18:06:56',0,'',4,NULL,NULL),(43,'2026-06-11',25,'methodist college - ADS','Methodist college',7000.00,'Bank',4,'','0000-00-00','','2026-06-11 11:52:04',0,'',NULL,NULL,NULL),(44,'2026-06-08',66,'Month of May','Nawakumar',31720.00,'Cash',NULL,'','0000-00-00','','2026-06-11 11:54:53',0,'',NULL,NULL,NULL),(45,'2026-06-08',11,'office 54','Water / Electricity board',20000.00,'Cash',NULL,'','0000-00-00','','2026-06-11 11:56:53',0,'',NULL,NULL,NULL),(46,'2026-06-04',35,'','ETF',10350.00,'Cash',NULL,'','0000-00-00','','2026-06-11 12:17:22',1,'',NULL,NULL,NULL),(47,'2026-06-12',12,'Visit Kalyani Jewellers','Thimothy',2000.00,'Bank',4,'','0000-00-00','','2026-06-12 10:29:28',0,'',NULL,NULL,NULL),(48,'2026-06-12',10,'','                                                Jeyakala                                            ',10000.00,'Bank',4,'','0000-00-00','hari','2026-06-12 22:36:49',0,'',4,NULL,NULL),(49,'2026-06-15',10,'','                                                Jeyakala                                            ',20000.00,'Bank',4,'','0000-00-00','','2026-06-15 12:44:34',0,'',4,NULL,NULL),(50,'2026-06-15',10,'','                                                Hariharan                                            ',10000.00,'Bank',4,'','0000-00-00','','2026-06-15 12:44:59',0,'',3,NULL,NULL),(51,'2026-06-15',33,'','Zoho',15000.00,'Bank',4,'','0000-00-00','','2026-06-15 12:45:37',0,'',NULL,NULL,NULL),(52,'2026-06-15',12,'Visit Induwara','Transport',5000.00,'Bank',4,'','0000-00-00','','2026-06-15 20:48:35',0,'',NULL,NULL,NULL),(53,'2026-06-15',16,'','Google Workspace',70000.00,'Bank',4,'','0000-00-00','','2026-06-15 21:08:03',0,'',NULL,NULL,NULL),(54,'2026-06-16',22,'Petrol','                                  ',10000.00,'Cash',NULL,'','0000-00-00','','2026-06-16 15:02:56',0,'',NULL,NULL,NULL),(55,'2026-06-16',31,'provide lunch','',5000.00,'Cash',NULL,'','0000-00-00','','2026-06-17 10:27:29',0,'',NULL,NULL,NULL),(56,'2026-06-17',16,'','Google Workspace',35000.00,'Bank',4,'','0000-00-00','','2026-06-17 12:25:10',0,'',NULL,NULL,NULL),(57,'2026-06-18',16,'','Google Workspace',52000.00,'Bank',4,'','0000-00-00','','2026-06-18 08:04:06',0,'',NULL,NULL,NULL),(58,'2026-06-19',16,'','Zoho',3000.00,'Bank',4,'','0000-00-00','','2026-06-20 13:30:20',0,'',NULL,NULL,NULL),(59,'2026-06-20',10,'','                                                Jeyakala                                            ',10000.00,'Bank',4,'','0000-00-00','','2026-06-20 13:30:58',0,'',4,NULL,NULL),(60,'2026-06-20',17,'vps001','Go',12000.00,'Bank',4,'','0000-00-00','','2026-06-20 20:26:25',0,'',NULL,NULL,NULL),(61,'2026-06-20',14,'itc004','ITC',3000.00,'Bank',4,'','0000-00-00','','2026-06-20 20:27:00',0,'',NULL,NULL,NULL),(62,'2026-06-20',14,'itc002','DomainPriceRight',4000.00,'Bank',4,'','0000-00-00','','2026-06-20 20:27:45',0,'',NULL,NULL,NULL),(63,'2026-06-21',10,'','                                                Jeyakala                                            ',5000.00,'Bank',4,'','0000-00-00','','2026-06-21 19:40:13',0,'',4,NULL,NULL),(64,'2026-06-21',10,'','                                                Hariharan                                            ',4000.00,'Bank',4,'','0000-00-00','','2026-06-21 19:41:23',0,'',3,NULL,NULL),(65,'2026-06-21',10,'','                                                Hariharan                                            ',20000.00,'Cash',NULL,'','0000-00-00','','2026-06-21 19:41:51',0,'',3,NULL,NULL),(66,'2026-06-22',16,'','Google Workspace',35000.00,'Bank',4,'','0000-00-00','','2026-06-22 08:30:22',0,'',NULL,NULL,NULL),(67,'2026-06-23',10,'','                                                Hariharan                                            ',15000.00,'Bank',4,'','0000-00-00','','2026-06-23 10:59:48',0,'',3,NULL,NULL),(68,'2026-06-24',14,'itc003','ITC',4500.00,'Bank',4,'','0000-00-00','','2026-06-24 11:15:04',0,'',NULL,NULL,NULL),(69,'2026-06-24',22,'','',10000.00,'Bank',4,'','0000-00-00','','2026-06-24 15:43:20',0,'',NULL,NULL,NULL),(70,'2026-06-25',9,'','Antigravity',8000.00,'Bank',4,'','0000-00-00','','2026-06-25 10:46:01',0,'',NULL,NULL,NULL),(71,'2026-06-25',15,'','ITC',5000.00,'Bank',4,'','0000-00-00','','2026-06-25 15:21:29',0,'',NULL,NULL,NULL),(72,'2026-06-26',16,'','Google Workspace',37000.00,'Bank',4,'','0000-00-00','','2026-06-26 14:47:57',0,'',NULL,NULL,NULL),(73,'2026-06-26',29,'credit card','ipay',9000.00,'Bank',4,'','0000-00-00','aalalbaitmasjid.com','2026-06-26 15:08:39',0,'',NULL,NULL,NULL),(74,'2026-06-27',16,'','Google Workspace',24000.00,'Bank',4,'','0000-00-00','','2026-06-27 06:37:26',0,'',NULL,NULL,NULL),(75,'2026-06-27',14,'itc016','ITC',3000.00,'Bank',4,'','0000-00-00','','2026-06-27 07:58:22',0,'',NULL,NULL,NULL),(76,'2026-06-30',22,'','',5000.00,'Bank',4,'','0000-00-00','','2026-06-30 09:50:53',0,'',NULL,NULL,NULL),(77,'2026-06-30',91,'','Niranjan',90000.00,'Bank',4,'','0000-00-00','','2026-06-30 11:18:56',0,'',NULL,NULL,NULL),(78,'2026-06-30',16,'','Google Workspace',12000.00,'Bank',4,'','0000-00-00','','2026-06-30 11:19:33',0,'',NULL,NULL,NULL),(79,'2026-06-30',10,'','                                                Hariharan                                            ',35000.00,'Bank',4,'','0000-00-00','','2026-06-30 11:20:27',0,'',3,NULL,NULL),(80,'2026-06-30',10,'','                                                Jeyakala                                            ',60000.00,'Bank',2,'','0000-00-00','','2026-06-30 14:27:01',0,'',4,NULL,NULL),(81,'2026-06-30',90,'','PLC',60000.00,'Bank',2,'','0000-00-00','','2026-06-30 14:27:37',0,'',NULL,NULL,NULL),(82,'2026-06-30',88,'phone repair - F42','Mobile shop',5500.00,'Bank',4,'','0000-00-00','','2026-06-30 14:32:15',0,'',NULL,NULL,NULL),(83,'2026-06-30',10,'','                                                Hariharan                                            ',4500.00,'Bank',4,'','0000-00-00','','2026-06-30 14:32:43',0,'',3,NULL,NULL),(84,'2026-06-30',34,'Month of May','EPF',12000.00,'Cheque',1,'','2026-06-30','','2026-06-30 21:08:32',0,'',NULL,NULL,NULL),(85,'2026-06-30',14,'itc010','ITC',3000.00,'Bank',4,'','0000-00-00','','2026-06-30 21:23:34',0,'',NULL,NULL,NULL),(86,'2026-07-01',14,'itc0031','ITC',4000.00,'Bank',4,'','0000-00-00','','2026-07-01 22:22:03',0,'',NULL,NULL,NULL),(87,'2026-07-02',14,'ix','Godaddy',6000.00,'Bank',4,'','0000-00-00','','2026-07-02 12:41:52',0,'',NULL,NULL,NULL),(88,'2026-07-02',20,'0113670717','SLT',2000.00,'Bank',4,'','0000-00-00','','2026-07-02 14:27:10',0,'',NULL,NULL,NULL),(89,'2026-06-25',10,'Salary payout for 2026-06','R. P. Wasana Sandamali',45000.00,'Cash',NULL,NULL,NULL,'','2026-06-25 15:10:06',0,'',2,NULL,NULL),(90,'2026-06-30',10,'Salary payout for 2026-06','K. L. Thimothy',50000.00,'Bank',2,NULL,NULL,'','2026-06-30 14:26:15',0,'',1,NULL,NULL),(91,'2026-07-02',10,'Salary payout for 2026-07','Vinuka',30000.00,'Bank',1,NULL,NULL,'','2026-07-02 20:00:47',0,'',6,NULL,NULL),(92,'2026-07-04',20,'0113670717','SLT',500.00,'Bank',4,'','0000-00-00','','2026-07-04 15:27:00',0,'',NULL,NULL,NULL),(93,'2026-07-04',10,'','                                                Hariharan                                            ',5000.00,'Bank',4,'','0000-00-00','foods - APG','2026-07-04 15:28:01',0,'',3,NULL,NULL),(94,'2026-07-05',10,'','                                                Hariharan                                            ',14500.00,'Bank',4,'','0000-00-00','','2026-07-05 21:37:42',0,'',3,NULL,NULL),(95,'2026-07-05',22,'','',5500.00,'Bank',4,'','0000-00-00','','2026-07-05 21:38:08',0,'',NULL,NULL,NULL),(96,'2026-07-06',15,'','LK Domain',5000.00,'Bank',4,'','0000-00-00','','2026-07-06 12:20:30',0,'',NULL,NULL,NULL),(97,'2026-07-06',15,'','LK Domain',5000.00,'Bank',4,'','0000-00-00','','2026-07-06 12:20:58',0,'',NULL,NULL,NULL),(98,'2026-07-06',16,'','Google Workspace',35000.00,'Bank',4,'','0000-00-00','','2026-07-06 12:52:43',0,'',NULL,NULL,NULL),(99,'2026-07-06',16,'','Google Workspace',35000.00,'Cheque',2,'933102','2026-07-06','','2026-07-06 12:54:05',0,'',NULL,NULL,NULL),(100,'2026-07-07',14,'itc001','DomainPriceRight',5000.00,'Bank',4,'','0000-00-00','','2026-07-07 10:04:10',0,'',NULL,NULL,NULL),(101,'2026-07-07',10,'','                                                Hariharan                                            ',10000.00,'Bank',4,'','0000-00-00','','2026-07-07 10:04:46',0,'',3,NULL,NULL),(102,'2026-07-07',25,'Annual Fee','Vivekans FC',12000.00,'Bank',4,'','0000-00-00','','2026-07-07 10:05:16',0,'',NULL,NULL,NULL),(103,'2026-07-08',22,'','',5000.00,'Bank',4,'','0000-00-00','','2026-07-08 20:01:21',0,'',NULL,NULL,NULL),(104,'2026-07-08',10,'','                                                Hariharan                                            ',5000.00,'Bank',4,'','0000-00-00','','2026-07-08 20:02:16',0,'',3,NULL,NULL),(105,'2026-07-09',20,'Fiber','SLT',10000.00,'Bank',4,'','0000-00-00','','2026-07-09 08:03:31',0,'',NULL,NULL,NULL),(106,'2026-07-09',14,'itc019','ITC',4500.00,'Bank',4,'','0000-00-00','','2026-07-09 08:15:43',0,'',NULL,NULL,NULL),(107,'2026-07-10',10,'','                                                Hariharan                                            ',5000.00,'Bank',4,'','0000-00-00','','2026-07-10 07:56:14',0,'',3,NULL,NULL),(108,'2026-07-11',10,'','                                                Hariharan                                            ',5000.00,'Bank',4,'','0000-00-00','','2026-07-11 17:06:51',0,'',3,NULL,NULL),(109,'2026-07-12',10,'','                                                Hariharan                                            ',5000.00,'Bank',4,'','0000-00-00','','2026-07-12 19:35:43',0,'',3,NULL,NULL),(110,'2026-07-13',15,'','LK Domain Registry',5000.00,'Bank',4,'','0000-00-00','','2026-07-13 11:31:08',0,'',NULL,NULL,NULL),(111,'2026-07-14',92,'','Water / Electricity board',22500.00,'Bank',4,'','0000-00-00','','2026-07-14 09:21:59',0,'',NULL,NULL,NULL),(112,'2026-07-14',22,'','',5000.00,'Bank',4,'','0000-00-00','','2026-07-14 14:44:52',0,'',NULL,NULL,NULL),(113,'2026-07-14',31,'','',2400.00,'Bank',4,'','0000-00-00','','2026-07-14 14:45:22',0,'',NULL,NULL,NULL),(114,'2026-07-14',15,'','LK Domain',5000.00,'Bank',4,'','0000-00-00','','2026-07-14 16:44:09',0,'',NULL,NULL,NULL),(115,'2026-07-14',10,'','                                                Jeyakala                                            ',10000.00,'Bank',4,'','0000-00-00','','2026-07-14 16:48:46',0,'',4,NULL,NULL),(116,'2026-07-14',93,'Doteloya Primary Vidyalaya ','O V Rajadurai ',7000.00,'Bank',4,'','0000-00-00','','2026-07-14 17:24:10',0,'',NULL,NULL,NULL),(117,'2026-07-16',16,'','Zoho',3000.00,'Bank',4,'','0000-00-00','','2026-07-16 12:49:17',0,'',NULL,NULL,NULL),(118,'2026-07-16',16,'','Zoho',9000.00,'Bank',4,'','0000-00-00','','2026-07-16 12:56:42',0,'',NULL,NULL,NULL),(119,'2026-07-16',16,'','Zoho',19000.00,'Bank',4,'','0000-00-00','','2026-07-16 12:59:03',0,'',NULL,NULL,NULL),(120,'2026-07-16',14,'s4','DomainPriceRight',5000.00,'Bank',4,'','0000-00-00','','2026-07-16 12:59:53',0,'',NULL,NULL,NULL),(121,'2026-07-15',15,'','Godaddy',5000.00,'Bank',4,'','0000-00-00','','2026-07-16 20:36:35',0,'',NULL,NULL,NULL),(122,'2026-07-17',93,'','Class of 2000',4000.00,'Bank',4,'','0000-00-00','','2026-07-17 10:16:55',0,'',NULL,NULL,NULL),(123,'2026-07-24',10,'Salary payout for 2026-07','R. P. Wasana Sandamali',45000.00,'Cash',NULL,NULL,NULL,'','2026-07-24 11:26:45',0,'',2,NULL,NULL),(124,'2026-07-24',10,'Salary payout for 2026-07','K. L. Thimothy',50000.00,'Cash',NULL,NULL,NULL,'','2026-07-24 14:36:11',0,'',1,NULL,NULL),(125,'2026-07-24',28,'','',15000.00,'Cash',NULL,'','0000-00-00','','2026-07-24 14:38:20',0,'',NULL,NULL,NULL);
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_year`
--

DROP TABLE IF EXISTS `financial_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_year` (
  `YearID` int NOT NULL AUTO_INCREMENT,
  `CompanyID` int DEFAULT NULL,
  `YearName` varchar(20) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`YearID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_year`
--

LOCK TABLES `financial_year` WRITE;
/*!40000 ALTER TABLE `financial_year` DISABLE KEYS */;
INSERT INTO `financial_year` VALUES (1,NULL,'2026','2026-06-01','2026-06-04',1,1);
/*!40000 ALTER TABLE `financial_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grn`
--

DROP TABLE IF EXISTS `grn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grn` (
  `GrnID` int NOT NULL AUTO_INCREMENT,
  `GrnNo` varchar(50) NOT NULL,
  `GrnDate` date NOT NULL,
  `VendorID` int NOT NULL,
  `VendorName` varchar(255) NOT NULL,
  `PurchaseOrderNo` varchar(50) DEFAULT NULL,
  `Remarks` text,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grn_items` (
  `GrnItemID` int NOT NULL AUTO_INCREMENT,
  `GrnID` int NOT NULL,
  `ItemID` int NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Description` text,
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issued_cheques`
--

LOCK TABLES `issued_cheques` WRITE;
/*!40000 ALTER TABLE `issued_cheques` DISABLE KEYS */;
INSERT INTO `issued_cheques` VALUES (2,'283645','2026-06-01',NULL,NULL,'Thimothy',10,1,42600.00,'Issued','Month of May','2026-06-03 19:56:54',17),(3,'283644','2026-05-29',NULL,NULL,'',34,1,12000.00,'Issued','','2026-06-04 18:56:55',23),(4,'41400','2026-06-04',NULL,NULL,'',34,1,41400.00,'Issued','','2026-06-04 18:57:40',24),(5,'283647','2026-06-04',NULL,NULL,'',35,1,10350.00,'Issued','','2026-06-04 18:58:58',25);
/*!40000 ALTER TABLE `issued_cheques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (2,'Web Development','Service','Corporate website develop',0,150000,0,1,'Web Solutions',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'30 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(3,'E-Commerce Website','Service','Online shopping website d',0,250000,0,1,'Web Solutions',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'45 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(4,'Domain Registration','Service','LK and COM domain registr',0,6500,0,2,'Domain Services',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'example.lk','2027-05-24','1 Year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(5,'Hosting Package','Service','Shared web hosting packag',0,15000,0,3,'Hosting Services',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'example.lk','2027-05-24','1 Year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(6,'SSL Certificate','Service','Website SSL security cert',0,12000,0,4,'Security Services',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'example.lk','2027-05-24','1 Year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(7,'Accounting System','','Customized accounting sof',0,350000,0,5,'Software Solutions',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'60 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(8,'Inventory System','','Inventory and stock manag',0,200000,0,5,'Software Solutions',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'45 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(9,'POS System','','Point of sales billing so',0,180000,0,5,'Software Solutions',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'30 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(10,'Mobile App Development','Service','Android and iOS mobile ap',0,500000,0,6,'Mobile Solutions',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'90 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(11,'SEO Optimization','Service','Search engine optimizatio',0,25000,0,7,'Digital Marketing',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'Monthly',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(12,'Social Media Management','Service','Facebook and Instagram pa',0,30000,0,7,'Digital Marketing',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'Monthly',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(13,'Graphic Design','Service','Logo and banner designing',0,10000,0,8,'Design Services',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'7 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(14,'AMC Support','Service','Annual maintenance contra',0,50000,0,9,'Support Services',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'','2027-05-24','1 Year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(15,'Email Hosting','Service','Business email hosting se',0,12000,0,3,'Hosting Services',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'company.lk','2027-05-24','1 Year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(16,'Software Bug Fixing','Service','Software issue fixing and',0,15000,0,9,'Support Services',1,'IT MARTX','2026-05-24 20:18','2026-05-24 20:18',0,'',NULL,'3 Days',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(17,'Renewal Domain and Hosting','Service','',0,24400,0,0,'',0,'','2026-05-29 17:51','2026-05-29 17:51',0,'kuviceyloan.com','2026-01-03','1 year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(18,'ERP System','Service','',0,0,0,0,'',0,'','2026-05-29 21:31','2026-05-29 21:31',0,'','0000-00-00','1 year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(19,'Manpower Management System','Service','',0,120000,0,0,'',0,'','2026-05-30 12:04','2026-05-30 12:04',0,'','0000-00-00','1 year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(20,'Google Workspace','Service','',0,0,0,0,'',0,'','2026-06-02 16:49','2026-06-02 16:49',0,'','0000-00-00','1 year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(21,'Zoho Workplace','Service','',0,0,0,0,'',0,'','2026-06-05 19:44','2026-06-05 19:44',0,'','0000-00-00','1 year',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(22,'Technical','Service','',0,0,0,0,'',0,'','2026-06-09 17:52','2026-06-09 17:52',0,'','0000-00-00','',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(23,'Google Adwords','Service','',0,0,0,0,'',0,'','2026-07-01 15:01','2026-07-01 15:01',0,'','0000-00-00','',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(24,'Setup Fee','Service','',0,0,0,0,'',0,'','2026-07-01 15:03','2026-07-01 15:03',0,'','0000-00-00','',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL),(25,'Monthy Charges','Service','',0,0,0,0,'',0,'','2026-07-01 15:03','2026-07-01 15:03',0,'','0000-00-00','',NULL,NULL,NULL,NULL,NULL,'cm',NULL,'kg','Pcs',NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_card`
--

DROP TABLE IF EXISTS `job_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_card`
--

LOCK TABLES `job_card` WRITE;
/*!40000 ALTER TABLE `job_card` DISABLE KEYS */;
INSERT INTO `job_card` VALUES (1,'JC-20260602162305','2026-06-02',1,62,'',7,NULL,'System','','Medium','Completed',NULL,NULL,'2026-06-01',NULL,'0000-00-00',150000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-02 16:23:28',NULL,'2026-06-02 16:23:43',0),(2,'JC-20260602164511','2026-06-01',2,65,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-01',NULL,'0000-00-00',21000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-02 16:45:41',NULL,'2026-06-02 16:46:00',0),(3,'JC-20260602165039','2026-06-01',3,38,'',20,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-01',NULL,'0000-00-00',85680.00,1,0.00,0.00,0.00,'',NULL,'2026-06-02 16:52:12',NULL,'2026-06-02 16:52:20',0),(4,'JC-20260603123934','2026-06-03',4,32,'',6,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-02',NULL,'0000-00-00',16000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-03 12:39:58',NULL,'2026-06-03 12:40:31',0),(5,'JC-20260603124041','2026-06-02',5,91,'',6,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-02',NULL,'0000-00-00',16000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-03 12:41:03',NULL,'2026-06-03 12:41:12',0),(6,'JC-20260603222546','2026-06-03',6,6,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-03',NULL,'0000-00-00',29400.00,1,0.00,0.00,0.00,'',NULL,'2026-06-03 22:26:15',NULL,'2026-06-03 22:26:25',0),(7,'JC-20260604055729','2026-06-03',7,77,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-03',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-06-04 05:57:56',NULL,'2026-06-04 05:58:18',0),(8,'JC-20260604060035','2026-06-03',8,55,'',20,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-03',NULL,'0000-00-00',28560.00,1,0.00,0.00,0.00,'',NULL,'2026-06-04 06:00:54',NULL,'2026-06-04 06:01:03',0),(9,'JC-20260604060206','2026-06-03',9,77,'',6,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-04',NULL,'0000-00-00',10400.00,1,0.00,0.00,0.00,'',NULL,'2026-06-04 06:02:23',NULL,'2026-06-04 06:02:30',0),(10,'JC-20260604060514','2026-06-03',10,12,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-04',NULL,'0000-00-00',63360.00,1,0.00,0.00,0.00,'',NULL,'2026-06-04 06:05:41',NULL,'2026-06-04 06:05:53',0),(11,'JC-20260604195302','2026-04-27',11,202,'',18,NULL,'System','','Medium','Completed',NULL,NULL,'2026-04-27',NULL,'0000-00-00',360000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-04 19:54:32',NULL,'2026-06-04 19:55:11',0),(12,'JC-20260605194534','2026-06-05',12,17,'',21,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-05',NULL,'0000-00-00',408000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-05 19:45:53',NULL,'2026-06-05 19:46:20',0),(13,'JC-20260605222126','2026-06-05',13,74,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-05',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-06-05 22:21:39',NULL,'2026-06-05 22:21:50',0),(14,'JC-20260605223007','2026-06-05',14,72,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-05',NULL,'0000-00-00',31200.00,1,0.00,0.00,0.00,'',NULL,'2026-06-05 22:30:26',NULL,'2026-06-05 22:30:36',0),(15,'JC-20260608223624','2026-06-08',15,34,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-08',NULL,'0000-00-00',23000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-08 22:36:44',NULL,'2026-06-08 22:36:54',0),(16,'JC-20260609172110','2026-06-09',16,211,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-09',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-06-09 17:21:22',NULL,'2026-06-09 17:21:36',0),(17,'JC-20260609174332','2026-06-09',17,32,'',4,NULL,'Purchase','','Medium','Completed',NULL,NULL,'2026-06-09',NULL,'0000-00-00',5000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-09 17:44:10',NULL,'2026-06-09 17:44:17',0),(18,'JC-20260609175332','2026-06-09',18,218,'',22,NULL,'Finger Print','finger print','Medium','Completed',NULL,NULL,'2026-06-09',NULL,'0000-00-00',90000.00,1,0.00,0.00,0.00,'Eshan Quote 45000',NULL,'2026-06-09 17:54:25',NULL,'2026-06-09 17:54:39',0),(19,'JC-20260612163757','2026-06-12',19,75,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-12',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-06-12 16:38:10',NULL,'2026-06-12 16:38:26',0),(20,'JC-20260615125425','2026-06-15',20,41,'',20,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-15',NULL,'0000-00-00',85680.00,1,0.00,0.00,0.00,'',NULL,'2026-06-15 12:54:54',NULL,'2026-06-15 12:55:05',0),(21,'JC-20260615210629','2026-06-15',21,5,'',20,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-15',NULL,'0000-00-00',85680.00,1,0.00,0.00,0.00,'',NULL,'2026-06-15 21:06:42',NULL,'2026-06-15 21:06:47',0),(22,'JC-20260615211619','2026-06-15',22,34,'',21,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-15',NULL,'0000-00-00',234200.00,1,0.00,0.00,0.00,'',NULL,'2026-06-15 21:16:32',NULL,'2026-06-15 21:16:38',0),(23,'JC-20260616150041','2026-06-16',23,239,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-16',NULL,'0000-00-00',21000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-16 15:01:04',NULL,'2026-06-16 15:01:17',0),(24,'JC-20260616161027','2026-06-16',24,202,'',18,NULL,'ERP','update 16/06/2026','Medium','Completed',NULL,NULL,'2026-06-16',NULL,'0000-00-00',290000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-16 16:12:20',NULL,'2026-06-16 16:12:30',0),(25,'JC-20260618152325','2026-06-18',25,36,'',20,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-18',NULL,'0000-00-00',147000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-18 15:23:45',NULL,'2026-06-18 15:23:54',0),(26,'JC-20260624110644','2026-06-24',26,238,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-06-24',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-06-24 11:07:08',NULL,'2026-06-24 11:13:09',0),(27,'JC-20260626150456','2026-06-26',27,244,'',18,NULL,'ERP','balance 650000','Medium','Completed',NULL,NULL,'2026-06-26',NULL,'0000-00-00',650000.00,1,0.00,0.00,0.00,'',NULL,'2026-06-26 15:05:36',NULL,'2026-06-26 15:05:47',0),(28,'JC-20260701150600','2026-07-01',28,223,'',23,NULL,'Google Adwords','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',20500.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 15:06:47',NULL,'2026-07-01 15:06:55',0),(29,'JC-20260701211129','2026-07-01',29,101,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',28400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:11:48',NULL,'2026-07-01 21:11:56',0),(30,'JC-20260701211348','2026-07-01',30,97,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',29400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:13:56',NULL,'2026-07-01 21:14:01',0),(31,'JC-20260701211443','2026-07-01',31,4,'',14,NULL,'AMC','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',25000.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:14:55',NULL,'2026-07-01 21:14:59',0),(32,'JC-20260701211628','2026-07-01',32,243,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',27000.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:16:39',NULL,'2026-07-01 21:16:45',0),(33,'JC-20260701211919','2026-07-01',33,88,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:19:32',NULL,'2026-07-01 21:19:36',0),(34,'JC-20260701212329','2026-07-01',34,245,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',31300.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:23:34',NULL,'2026-07-01 21:23:38',0),(35,'JC-20260701212442','2026-07-01',35,87,'',4,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',8000.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:24:57',NULL,'2026-07-01 21:25:00',0),(36,'JC-20260701212543','2026-07-01',36,86,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',29400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:25:52',NULL,'2026-07-01 21:25:56',0),(37,'JC-20260701212630','2026-07-01',37,85,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:26:41',NULL,'2026-07-01 21:26:47',0),(38,'JC-20260701212740','2026-07-01',38,84,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:27:52',NULL,'2026-07-01 21:27:56',0),(39,'JC-20260701212849','2026-07-01',39,81,'',17,NULL,'Renewal','','Medium','Completed',NULL,NULL,'2026-07-01',NULL,'0000-00-00',24400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-01 21:29:00',NULL,'2026-07-01 21:29:05',0),(40,'JC-20260704112414','2026-07-04',40,202,'',18,NULL,'ERP','New updates - 26/06/2026','Medium','Completed',NULL,NULL,'2026-07-04',NULL,'0000-00-00',395000.00,1,0.00,0.00,0.00,'',NULL,'2026-07-04 11:24:43',NULL,'2026-07-04 11:24:51',0),(41,'JC-20260704151726','2026-07-04',41,202,'',18,NULL,'ERP','','Medium','Completed',NULL,NULL,'2026-07-04',NULL,'0000-00-00',300000.00,1,0.00,0.00,0.00,'03/07/26 - New Updates',NULL,'2026-07-04 15:18:16',NULL,'2026-07-04 15:18:22',0),(42,'JC-20260708200420','2026-07-08',42,5,'',20,NULL,'Email','senal@micro.lk','Medium','Completed',NULL,NULL,'2026-07-08',NULL,'0000-00-00',29400.00,1,0.00,0.00,0.00,'',NULL,'2026-07-08 20:04:39',NULL,'2026-07-08 14:34:54',0),(43,'JC-20260716155944','2026-07-16',43,235,'',15,NULL,'Email','horeca@rslanka.lk \r\nmathasales1@rslanka.lk\r\nmathasales2@rslanka.lk','Medium','Completed',NULL,NULL,'2026-07-16',NULL,'0000-00-00',18900.00,1,0.00,0.00,0.00,'',NULL,'2026-07-16 16:00:10',NULL,'2026-07-16 10:30:27',0),(44,'JC-20260716161202','2026-05-24',44,202,'',18,NULL,'ERP','Total Amount = 425,000/-\r\npaid = 212.500/-\r\n','Medium','Completed',NULL,NULL,'2026-07-16',NULL,'0000-00-00',212500.00,1,0.00,0.00,0.00,'',NULL,'2026-07-16 16:13:12',NULL,'2026-07-16 10:43:28',0),(45,'JC-20260724124018','2026-07-24',47,218,'',18,NULL,'re','','Medium','Pending',NULL,NULL,'2026-07-24',NULL,'0000-00-00',45000.00,1,0.00,0.00,0.00,'',NULL,'2026-07-24 12:40:43',NULL,'2026-07-24 12:40:43',0),(46,'JC-20260725110114','2026-07-25',49,1,'',2,NULL,'pe','','Medium','Completed',NULL,NULL,'2026-07-25',NULL,'0000-00-00',150000.00,1,0.00,0.00,0.00,'',NULL,'2026-07-25 11:01:31',NULL,'2026-07-25 11:01:43',0);
/*!40000 ALTER TABLE `job_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_card_employees`
--

DROP TABLE IF EXISTS `job_card_employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_card_employees`
--

LOCK TABLES `job_card_employees` WRITE;
/*!40000 ALTER TABLE `job_card_employees` DISABLE KEYS */;
INSERT INTO `job_card_employees` VALUES (1,1,6,'2026-06-01',5.00,0.00,0.00,NULL,NULL,0.00,7200,2.40,17280.00,'Pending','','2026-06-02 16:23:28',0),(2,2,3,'2026-06-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-02 16:45:41',0),(3,3,3,'2026-06-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-02 16:52:12',0),(4,4,3,'2026-06-02',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-03 12:39:58',0),(5,5,3,'2026-06-02',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-03 12:41:03',0),(6,6,3,'2026-06-03',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-03 22:26:15',0),(7,7,3,'2026-06-03',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-04 05:57:56',0),(8,8,3,'2026-06-03',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-04 06:00:54',0),(9,9,3,'2026-06-03',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-04 06:02:23',0),(10,10,3,'2026-06-03',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-04 06:05:41',0),(11,11,6,'2026-06-04',6.00,0.00,0.00,NULL,NULL,0.00,8640,2.40,20736.00,'Pending','','2026-06-04 19:54:32',0),(12,12,3,'2026-06-05',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-05 19:45:53',0),(13,13,3,'2026-06-05',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-05 22:21:39',0),(14,14,3,'2026-06-05',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-05 22:30:26',0),(15,15,3,'2026-06-08',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-08 22:36:44',0),(16,16,3,'2026-06-09',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-09 17:21:22',0),(17,17,3,'2026-06-09',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-09 17:44:10',0),(18,18,1,'2026-06-09',5.00,0.00,0.00,NULL,NULL,0.00,7200,3.61,25992.00,'Pending','','2026-06-09 17:54:25',0),(19,19,3,'2026-06-12',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-12 16:38:10',0),(20,20,3,'2026-06-15',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-15 12:54:54',0),(21,21,3,'2026-06-15',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-15 21:06:42',0),(22,22,3,'2026-06-15',2.00,0.00,0.00,NULL,NULL,0.00,2880,2.40,6912.00,'Pending','','2026-06-15 21:16:32',0),(23,23,3,'2026-06-16',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-16 15:01:04',0),(24,24,6,'2026-06-16',7.00,0.00,0.00,NULL,NULL,0.00,10080,2.40,24192.00,'Pending','','2026-06-16 16:12:20',0),(25,24,1,'2026-06-16',7.00,0.00,0.00,NULL,NULL,0.00,10080,3.61,36388.80,'Pending','','2026-06-16 16:12:20',0),(26,25,3,'2026-06-18',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-18 15:23:45',0),(27,26,3,'2026-06-24',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-06-24 11:07:08',0),(28,28,3,'2026-07-01',5.00,0.00,0.00,NULL,NULL,0.00,7200,2.40,17280.00,'Pending','','2026-07-01 15:06:47',0),(29,29,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:11:48',0),(30,30,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:13:56',0),(31,31,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:14:55',0),(32,32,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:16:39',0),(33,33,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:19:32',0),(34,35,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:24:57',0),(35,36,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:25:52',0),(36,37,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:26:41',0),(37,38,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:27:52',0),(38,39,3,'2026-07-01',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-01 21:29:00',0),(39,40,6,'2026-07-04',10.00,0.00,0.00,NULL,NULL,0.00,14400,2.40,34560.00,'Pending','','2026-07-04 11:24:43',0),(40,40,1,'2026-07-04',10.00,0.00,0.00,NULL,NULL,0.00,14400,4.01,57744.00,'Pending','','2026-07-04 11:24:43',0),(41,41,1,'2026-07-04',10.00,0.00,0.00,NULL,NULL,0.00,14400,4.01,57744.00,'Pending','','2026-07-04 15:18:16',0),(42,41,6,'2026-07-04',10.00,0.00,0.00,NULL,NULL,0.00,14400,2.40,34560.00,'Pending','','2026-07-04 15:18:16',0),(43,42,3,'2026-07-08',1.00,0.00,0.00,NULL,NULL,0.00,1440,2.40,3456.00,'Pending','','2026-07-08 20:04:39',0),(44,43,3,'2026-07-16',2.00,0.00,0.00,NULL,NULL,0.00,2880,2.40,6912.00,'Pending','','2026-07-16 16:00:10',0),(45,44,1,'2026-07-16',10.00,0.00,0.00,NULL,NULL,0.00,14400,4.01,57744.00,'Pending','','2026-07-16 16:13:12',0),(46,44,6,'2026-07-16',30.00,0.00,0.00,NULL,NULL,0.00,43200,2.40,103680.00,'Pending','','2026-07-16 16:13:12',0),(47,45,2,'2026-07-24',1.00,0.00,0.00,NULL,NULL,0.00,1440,3.61,5198.40,'Pending','','2026-07-24 12:40:43',0),(48,46,3,'2026-07-25',12.00,0.00,0.00,NULL,NULL,0.00,17280,2.40,41472.00,'Pending','','2026-07-25 11:01:31',0);
/*!40000 ALTER TABLE `job_card_employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_card_tasks`
--

DROP TABLE IF EXISTS `job_card_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_entries` (
  `JournalID` int NOT NULL AUTO_INCREMENT,
  `JournalDate` date NOT NULL,
  `ReferenceNo` varchar(100) DEFAULT NULL,
  `Description` text,
  `CreatedBy` int DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`JournalID`)
) ENGINE=InnoDB AUTO_INCREMENT=578 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entries`
--

LOCK TABLES `journal_entries` WRITE;
/*!40000 ALTER TABLE `journal_entries` DISABLE KEYS */;
INSERT INTO `journal_entries` VALUES (79,'2026-06-05','','HNB investment',NULL,'2026-06-05 17:21:32'),(155,'2026-06-08','BANK-CASH-1','Transfer from Sampath Bank to Cash',NULL,'2026-06-08 17:10:19'),(163,'2026-06-08','BANK-CASH-2','Transfer from NTB Bank to Cash',NULL,'2026-06-08 20:00:36'),(164,'2026-06-08','BANK-CASH-2','Transfer from NTB Bank to Cash',NULL,'2026-06-08 20:12:16'),(194,'2026-06-17','JN-20260617-197','',1,'2026-06-17 12:23:11'),(197,'2026-06-18','JN-20260618-323','',1,'2026-06-18 07:57:33'),(200,'2026-06-18','JN-20260618-826','',1,'2026-06-18 15:30:22'),(208,'2026-06-19','ADV-20260619-539','Salary Advance - Sandamali',1,'2026-06-19 23:11:22'),(224,'2026-06-25','SAL-202606-2','Salary Slip - Sandamali (2026-06)',1,'2026-06-25 15:10:06'),(229,'2026-06-15','DRW-3','Drawings: seetu',1,'2026-06-26 14:53:34'),(230,'2026-06-26','DRW-4','Drawings: seetu',1,'2026-06-26 14:55:12'),(235,'2026-06-26','DRW-6','Drawings: Daughter 3rd Term Fee 2025 ',1,'2026-06-26 18:01:23'),(237,'2026-06-27','JN-20260627-974','',1,'2026-06-27 06:40:24'),(244,'2026-06-30','SAL-202606-1','Salary Slip - K. L. Thimothy (2026-06)',1,'2026-06-30 14:26:15'),(253,'2026-07-01','JN-20260701-225','',1,'2026-07-01 22:19:46'),(258,'2026-07-02','SAL-202607-6','Salary Slip - Vinuka (2026-07)',1,'2026-07-02 20:00:47'),(260,'2026-07-03','JN-20260704-550','',1,'2026-07-04 15:15:32'),(263,'2026-07-04','JN-20260704-540','',1,'2026-07-04 18:39:26'),(272,'2026-07-06','JN-20260706-766','',1,'2026-07-06 15:35:30'),(273,'2026-07-06','JN-20260706-366','',1,'2026-07-06 15:36:49'),(274,'2026-07-06','JN-20260706-243','',1,'2026-07-06 15:40:57'),(282,'2026-07-07','JN-20260707-614','',1,'2026-07-07 10:07:43'),(410,'2026-06-01','EXP-1','Expense: Expense Entry',1,'2026-06-02 20:19:02'),(411,'2026-06-01','EXP-2','Expense: Expense Entry',1,'2026-06-02 20:19:34'),(412,'2026-06-01','EXP-3','Expense: Expense Entry',1,'2026-06-02 20:20:15'),(413,'2026-06-01','EXP-4','Expense: Expense Entry',1,'2026-06-02 20:21:19'),(414,'2026-06-01','EXP-5','Expense: Expense Entry',1,'2026-06-02 20:22:52'),(415,'2026-06-02','EXP-6','Expense: Expense Entry',4,'2026-06-02 20:26:09'),(416,'2026-06-02','EXP-16','Expense: Expense Entry',3,'2026-06-03 19:56:01'),(417,'2026-06-01','EXP-17','Expense: Expense Entry',1,'2026-06-03 19:56:54'),(418,'2026-06-03','EXP-18','Expense: Expense Entry',1,'2026-06-03 19:58:47'),(419,'2026-06-03','EXP-19','Expense: 0773041287',1,'2026-06-03 20:02:19'),(420,'2026-06-04','EXP-20','Expense: Expense Entry',3,'2026-06-04 18:29:06'),(421,'2026-06-04','EXP-21','Expense: Expense Entry',4,'2026-06-04 18:30:25'),(422,'2026-06-04','EXP-22','Expense: Battaramulla',1,'2026-06-04 18:31:13'),(423,'2026-06-01','EXP-23','Expense: Month of April',1,'2026-06-04 18:56:55'),(424,'2026-06-04','EXP-24','Expense: Expense Entry',1,'2026-06-04 18:57:40'),(425,'2026-06-04','EXP-25','Expense: from Jan to May',1,'2026-06-04 18:58:58'),(426,'2026-06-01','EXP-26','Expense: NDE Ch',1,'2026-06-04 19:08:52'),(427,'2026-06-04','EXP-27','Expense: neosysmedicare.com',1,'2026-06-04 22:18:49'),(428,'2026-06-06','EXP-28','Expense: Fiber',1,'2026-06-06 06:27:37'),(429,'2026-06-06','EXP-29','Expense: Expense Entry',4,'2026-06-06 06:28:51'),(430,'2026-06-06','EXP-30','Expense: DM Raja Visit',1,'2026-06-06 06:29:30'),(431,'2026-06-06','EXP-31','Expense: 10K+10K',3,'2026-06-06 06:29:57'),(432,'2026-06-07','EXP-32','Expense: Expense Entry',1,'2026-06-07 11:00:50'),(433,'2026-06-07','EXP-33','Expense: itc001',1,'2026-06-07 12:12:34'),(434,'2026-06-07','EXP-34','Expense: itc019',1,'2026-06-07 12:20:03'),(435,'2026-06-08','EXP-35','Expense: Expense Entry',1,'2026-06-08 09:38:18'),(436,'2026-06-08','EXP-36','Expense: Expense Entry',1,'2026-06-09 05:35:14'),(437,'2026-06-09','EXP-37','Expense: s4',1,'2026-06-09 16:55:52'),(438,'2026-06-09','EXP-38','Expense: Expense Entry',1,'2026-06-09 17:45:14'),(439,'2026-06-10','EXP-39','Expense: Expense Entry',1,'2026-06-10 08:16:50'),(440,'2026-06-10','EXP-40','Expense: Acc No: 079-2-001-5-0038794',1,'2026-06-10 11:31:11'),(441,'2026-06-10','EXP-41','Expense: Maarji Courier Charge',1,'2026-06-10 15:04:18'),(442,'2026-06-10','EXP-42','Expense: Expense Entry',4,'2026-06-10 18:06:56'),(443,'2026-06-11','EXP-43','Expense: methodist college - ADS',1,'2026-06-11 11:52:04'),(444,'2026-06-08','EXP-44','Expense: Month of May',1,'2026-06-11 11:54:53'),(445,'2026-06-08','EXP-45','Expense: office 54',1,'2026-06-11 11:56:53'),(446,'2026-06-12','EXP-47','Expense: Visit Kalyani Jewellers',1,'2026-06-12 10:29:28'),(447,'2026-06-12','EXP-48','Expense: Expense Entry',4,'2026-06-12 22:36:49'),(448,'2026-06-15','EXP-49','Expense: Expense Entry',4,'2026-06-15 12:44:34'),(449,'2026-06-15','EXP-50','Expense: Expense Entry',3,'2026-06-15 12:44:59'),(450,'2026-06-15','EXP-51','Expense: Expense Entry',1,'2026-06-15 12:45:37'),(451,'2026-06-15','EXP-52','Expense: Visit Induwara',1,'2026-06-15 20:48:35'),(452,'2026-06-15','EXP-53','Expense: Expense Entry',1,'2026-06-15 21:08:03'),(453,'2026-06-16','EXP-54','Expense: Petrol',1,'2026-06-16 15:02:56'),(454,'2026-06-16','EXP-55','Expense: provide lunch',1,'2026-06-17 10:27:29'),(455,'2026-06-17','EXP-56','Expense: Expense Entry',1,'2026-06-17 12:25:10'),(456,'2026-06-18','EXP-57','Expense: Expense Entry',1,'2026-06-18 08:04:06'),(457,'2026-06-19','EXP-58','Expense: Expense Entry',1,'2026-06-20 13:30:20'),(458,'2026-06-20','EXP-59','Expense: Expense Entry',4,'2026-06-20 13:30:58'),(459,'2026-06-20','EXP-60','Expense: vps001',1,'2026-06-20 20:26:25'),(460,'2026-06-20','EXP-61','Expense: itc004',1,'2026-06-20 20:27:00'),(461,'2026-06-20','EXP-62','Expense: itc002',1,'2026-06-20 20:27:45'),(462,'2026-06-21','EXP-63','Expense: Expense Entry',4,'2026-06-21 19:40:13'),(463,'2026-06-21','EXP-64','Expense: Expense Entry',3,'2026-06-21 19:41:23'),(464,'2026-06-21','EXP-65','Expense: Expense Entry',3,'2026-06-21 19:41:51'),(465,'2026-06-22','EXP-66','Expense: Expense Entry',1,'2026-06-22 08:30:22'),(466,'2026-06-23','EXP-67','Expense: Expense Entry',3,'2026-06-23 10:59:48'),(467,'2026-06-24','EXP-68','Expense: itc003',1,'2026-06-24 11:15:04'),(468,'2026-06-24','EXP-69','Expense: Expense Entry',1,'2026-06-24 15:43:20'),(469,'2026-06-25','EXP-70','Expense: Expense Entry',1,'2026-06-25 10:46:01'),(470,'2026-06-25','EXP-71','Expense: Expense Entry',1,'2026-06-25 15:21:29'),(471,'2026-06-26','EXP-72','Expense: Expense Entry',1,'2026-06-26 14:47:57'),(472,'2026-06-26','EXP-73','Expense: credit card',1,'2026-06-26 15:08:39'),(473,'2026-06-27','EXP-74','Expense: Expense Entry',1,'2026-06-27 06:37:26'),(474,'2026-06-27','EXP-75','Expense: itc016',1,'2026-06-27 07:58:22'),(475,'2026-06-30','EXP-76','Expense: Expense Entry',1,'2026-06-30 09:50:53'),(476,'2026-06-30','EXP-77','Expense: Expense Entry',1,'2026-06-30 11:18:56'),(477,'2026-06-30','EXP-78','Expense: Expense Entry',1,'2026-06-30 11:19:33'),(478,'2026-06-30','EXP-79','Expense: Expense Entry',3,'2026-06-30 11:20:27'),(479,'2026-06-30','EXP-80','Expense: Expense Entry',4,'2026-06-30 14:27:01'),(480,'2026-06-30','EXP-81','Expense: Expense Entry',1,'2026-06-30 14:27:37'),(481,'2026-06-30','EXP-82','Expense: phone repair - F42',1,'2026-06-30 14:32:15'),(482,'2026-06-30','EXP-83','Expense: Expense Entry',3,'2026-06-30 14:32:43'),(483,'2026-06-30','EXP-84','Expense: Month of May',1,'2026-06-30 21:08:32'),(484,'2026-06-30','EXP-85','Expense: itc010',1,'2026-06-30 21:23:34'),(485,'2026-07-01','EXP-86','Expense: itc0031',1,'2026-07-01 22:22:03'),(486,'2026-07-02','EXP-87','Expense: ix',1,'2026-07-02 12:41:52'),(487,'2026-07-02','EXP-88','Expense: 0113670717',1,'2026-07-02 14:27:10'),(488,'2026-06-25','EXP-89','Expense: Salary payout for 2026-06',2,'2026-06-25 15:10:06'),(489,'2026-06-30','EXP-90','Expense: Salary payout for 2026-06',1,'2026-06-30 14:26:15'),(490,'2026-07-02','EXP-91','Expense: Salary payout for 2026-07',6,'2026-07-02 20:00:47'),(491,'2026-07-04','EXP-92','Expense: 0113670717',1,'2026-07-04 15:27:00'),(492,'2026-07-04','EXP-93','Expense: Expense Entry',3,'2026-07-04 15:28:01'),(493,'2026-07-05','EXP-94','Expense: Expense Entry',3,'2026-07-05 21:37:42'),(494,'2026-07-05','EXP-95','Expense: Expense Entry',1,'2026-07-05 21:38:08'),(495,'2026-07-06','EXP-96','Expense: Expense Entry',1,'2026-07-06 12:20:30'),(496,'2026-07-06','EXP-97','Expense: Expense Entry',1,'2026-07-06 12:20:58'),(497,'2026-07-06','EXP-98','Expense: Expense Entry',1,'2026-07-06 12:52:43'),(498,'2026-07-06','EXP-99','Expense: Expense Entry',1,'2026-07-06 12:54:05'),(499,'2026-07-07','EXP-100','Expense: itc001',1,'2026-07-07 10:04:10'),(500,'2026-07-07','EXP-101','Expense: Expense Entry',3,'2026-07-07 10:04:46'),(501,'2026-07-07','EXP-102','Expense: Annual Fee',1,'2026-07-07 10:05:16'),(502,'2026-06-02','CP-1','Customer Payment Received',1,'2026-06-02 16:24:35'),(503,'2026-06-01','CP-2','Customer Payment Received',1,'2026-06-02 16:46:24'),(504,'2026-06-02','CP-3','Customer Payment Received',1,'2026-06-02 16:52:58'),(505,'2026-06-02','CP-5','Customer Payment Received',1,'2026-06-03 12:42:08'),(506,'2026-06-02','CP-6','Customer Payment Received',1,'2026-06-03 12:42:47'),(507,'2026-06-03','CP-7','Customer Payment Received',1,'2026-06-03 22:27:10'),(508,'2026-06-03','CP-8','Customer Payment Received',1,'2026-06-04 05:58:53'),(509,'2026-06-04','CP-9','Customer Payment Received',1,'2026-06-04 06:01:28'),(510,'2026-06-04','CP-10','Customer Payment Received',1,'2026-06-04 06:02:56'),(511,'2026-06-03','CP-11','Customer Payment Received',1,'2026-06-04 06:06:16'),(512,'2026-06-04','CP-12','Customer Payment Received',1,'2026-06-04 20:19:18'),(513,'2026-06-05','CP-13','Customer Payment Received',1,'2026-06-05 19:47:06'),(514,'2026-06-05','CP-14','Customer Payment Received',1,'2026-06-05 22:22:53'),(515,'2026-06-05','CP-15','Customer Payment Received',1,'2026-06-05 22:31:06'),(516,'2026-06-08','CP-16','Customer Payment Received',1,'2026-06-08 22:38:13'),(517,'2026-06-09','CP-17','Customer Payment Received',1,'2026-06-09 17:22:14'),(518,'2026-06-09','CP-18','Customer Payment Received',1,'2026-06-09 17:55:15'),(519,'2026-06-10','CP-19','Customer Payment Received',1,'2026-06-10 11:38:03'),(520,'2026-06-12','CP-20','Customer Payment Received',1,'2026-06-12 16:47:58'),(521,'2026-06-15','CP-21','Customer Payment Received',1,'2026-06-15 12:56:09'),(522,'2026-06-15','CP-22','Customer Payment Received',1,'2026-06-15 21:07:18'),(523,'2026-06-15','CP-23','Customer Payment Received',1,'2026-06-15 21:17:10'),(524,'2026-06-16','CP-24','Customer Payment Received',1,'2026-06-16 15:01:38'),(525,'2026-06-18','CP-25','Customer Payment Received',1,'2026-06-18 15:25:18'),(526,'2026-06-24','CP-26','Customer Payment Received',1,'2026-06-24 11:13:54'),(527,'2026-06-26','CP-27','Customer Payment Received',1,'2026-06-26 15:06:53'),(528,'2026-06-26','CP-28','Customer Payment Received',1,'2026-06-26 16:12:12'),(529,'2026-07-02','CP-29','Customer Payment Received',1,'2026-07-02 13:56:31'),(530,'2026-07-03','CP-30','Customer Payment Received',1,'2026-07-04 11:21:55'),(531,'2026-07-06','CP-31','Customer Payment Received',1,'2026-07-06 14:45:43'),(532,'2026-07-06','CP-32','Customer Payment Received',1,'2026-07-06 15:17:27'),(533,'2026-07-06','CP-33','Customer Payment Received',1,'2026-07-06 17:05:44'),(534,'2026-07-06','CP-34','Customer Payment Received',1,'2026-07-06 17:08:26'),(535,'2026-07-06','CP-35','Customer Payment Received',1,'2026-07-06 17:19:52'),(536,'2026-07-06','CP-36','Customer Payment Received',1,'2026-07-06 18:13:58'),(537,'2026-07-07','CP-37','Customer Payment Received',NULL,'2026-07-07 19:42:05'),(538,'2026-07-07','CP-38','Customer Payment Received',NULL,'2026-07-07 19:43:12'),(539,'2026-07-07','JN-20260707-510','',1,'2026-07-07 19:50:07'),(540,'2026-07-08','EXP-103','Expense: Expense Entry',1,'2026-07-08 20:01:21'),(541,'2026-07-08','EXP-104','Expense: Expense Entry',1,'2026-07-08 20:02:16'),(542,'2026-07-08','CP-39','Customer Payment Received',NULL,'2026-07-08 14:35:24'),(543,'2026-07-09','EXP-105','Expense: Fiber',1,'2026-07-09 08:03:31'),(544,'2026-07-09','EXP-106','Expense: itc019',1,'2026-07-09 08:15:43'),(545,'2026-07-10','EXP-107','Expense: Expense Entry',1,'2026-07-10 07:56:14'),(546,'2026-07-11','EXP-108','Expense: Expense Entry',1,'2026-07-11 17:06:51'),(547,'2026-07-12','EXP-109','Expense: Expense Entry',1,'2026-07-12 19:35:43'),(548,'2026-07-13','EXP-110','Expense: Expense Entry',1,'2026-07-13 11:31:08'),(549,'2026-07-13','CP-40','Customer Payment Received',NULL,'2026-07-13 07:35:43'),(550,'2026-07-14','EXP-111','Expense: Expense Entry',1,'2026-07-14 09:21:59'),(551,'2026-07-14','CP-41','Customer Payment Received',NULL,'2026-07-14 07:22:04'),(552,'2026-07-14','CP-42','Customer Payment Received',NULL,'2026-07-14 07:28:09'),(553,'2026-07-14','EXP-112','Expense: Expense Entry',1,'2026-07-14 14:44:52'),(554,'2026-07-14','EXP-113','Expense: Expense Entry',1,'2026-07-14 14:45:22'),(555,'2026-07-14','EXP-114','Expense: Expense Entry',1,'2026-07-14 16:44:09'),(556,'2026-07-14','EXP-115','Expense: Expense Entry',1,'2026-07-14 16:48:46'),(557,'2026-07-14','EXP-116','Expense: Doteloya Primary Vidyalaya ',1,'2026-07-14 17:24:10'),(558,'2026-07-16','EXP-117','Expense: Expense Entry',1,'2026-07-16 12:49:17'),(559,'2026-07-16','EXP-118','Expense: Expense Entry',1,'2026-07-16 12:58:02'),(560,'2026-07-16','EXP-118','Expense: Expense Entry',1,'2026-07-16 12:58:02'),(561,'2026-07-16','EXP-119','Expense: Expense Entry',1,'2026-07-16 12:59:03'),(562,'2026-07-16','EXP-120','Expense: s4',1,'2026-07-16 12:59:53'),(563,'2026-07-16','DRW-7','Drawings: seetu',1,'2026-07-16 13:02:22'),(564,'2026-07-16','CP-43','Customer Payment Received',NULL,'2026-07-16 10:30:59'),(565,'2026-07-15','EXP-121','Expense: Expense Entry',1,'2026-07-16 20:36:45'),(566,'2026-07-15','EXP-121','Expense: Expense Entry',1,'2026-07-16 20:36:45'),(567,'2026-07-17','EXP-122','Expense: Expense Entry',1,'2026-07-17 10:16:55'),(568,'2026-07-17','CP-44','Customer Payment Received',NULL,'2026-07-17 14:46:05'),(569,'2026-07-24','SAL-202607-2','Salary Slip - R. P. Wasana Sandamali (2026-07)',1,'2026-07-24 11:26:45'),(570,'2026-07-24','CP-45','Customer Payment Received',NULL,'2026-07-24 12:39:34'),(571,'2026-07-24','SAL-202607-1','Salary Slip - K. L. Thimothy (2026-07)',1,'2026-07-24 14:36:11'),(572,'2026-07-24','EXP-125','Expense: Expense Entry',1,'2026-07-24 14:38:20'),(573,'2026-07-25','CP-46','Customer Payment Received',NULL,'2026-07-25 11:02:08'),(574,'2026-07-26','PUR-1','Purchase Entry',NULL,'2026-07-26 10:24:01'),(575,'2026-07-26','VP-1','Vendor Payment',NULL,'2026-07-26 10:24:47'),(576,'2026-07-27','CP-52','Customer Payment Received',NULL,'2026-07-27 10:23:47'),(577,'2026-07-27','CP-53','Customer Payment Received (Pay Receipt)',NULL,'2026-07-27 10:24:05');
/*!40000 ALTER TABLE `journal_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entry_details`
--

DROP TABLE IF EXISTS `journal_entry_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=1186 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entry_details`
--

LOCK TABLES `journal_entry_details` WRITE;
/*!40000 ALTER TABLE `journal_entry_details` DISABLE KEYS */;
INSERT INTO `journal_entry_details` VALUES (128,79,40,100000.00,0.00,NULL),(129,79,39,0.00,100000.00,NULL),(282,155,1,300000.00,0.00,NULL),(283,155,39,0.00,300000.00,NULL),(299,164,1,200000.00,0.00,NULL),(300,164,38,0.00,200000.00,NULL),(359,194,39,30000.00,0.00,NULL),(360,194,38,0.00,30000.00,NULL),(365,197,39,100000.00,0.00,NULL),(366,197,1,0.00,100000.00,NULL),(371,200,1,100000.00,0.00,NULL),(372,200,39,0.00,100000.00,NULL),(411,208,82,25000.00,0.00,NULL),(412,208,39,0.00,25000.00,NULL),(443,224,10,45000.00,0.00,NULL),(444,224,34,4200.00,0.00,NULL),(445,224,35,1050.00,0.00,NULL),(446,224,1,0.00,17200.00,NULL),(447,224,51,0.00,7000.00,NULL),(448,224,52,0.00,1050.00,NULL),(449,224,82,0.00,25000.00,NULL),(462,229,85,50000.00,0.00,'Personal Withdrawal - Personal Expense to Sasikala'),(463,229,39,0.00,50000.00,'Paid from Bank'),(464,230,85,60000.00,0.00,'Personal Withdrawal - Personal Expense to Sinthu'),(465,230,39,0.00,60000.00,'Paid from Bank'),(476,235,87,91000.00,0.00,'Personal Withdrawal - School Fees to Bishop\'s College'),(477,235,39,0.00,91000.00,'Paid from Bank'),(480,237,36,200000.00,0.00,NULL),(481,237,1,0.00,200000.00,NULL),(498,244,10,50000.00,0.00,NULL),(499,244,34,4200.00,0.00,NULL),(500,244,35,1050.00,0.00,NULL),(501,244,36,0.00,47200.00,NULL),(502,244,51,0.00,7000.00,NULL),(503,244,52,0.00,1050.00,NULL),(520,253,39,10000.00,0.00,NULL),(521,253,38,0.00,10000.00,NULL),(530,258,10,30000.00,0.00,NULL),(531,258,38,0.00,30000.00,NULL),(534,260,39,150000.00,0.00,NULL),(535,260,38,0.00,150000.00,NULL),(542,263,38,100000.00,0.00,NULL),(543,263,39,0.00,100000.00,NULL),(560,272,39,400000.00,0.00,NULL),(561,272,55,0.00,400000.00,NULL),(562,273,39,100000.00,0.00,NULL),(563,273,38,0.00,100000.00,NULL),(572,274,81,500000.00,0.00,'Housing Loan Principal Repayment'),(573,274,39,0.00,500000.00,'Housing Loan Payment'),(584,282,55,100000.00,0.00,''),(585,282,39,0.00,100000.00,''),(840,410,14,4000.00,0.00,''),(841,410,39,0.00,4000.00,'Payment via Bank'),(842,411,15,5000.00,0.00,''),(843,411,39,0.00,5000.00,'Payment via Bank'),(844,412,22,10000.00,0.00,''),(845,412,39,0.00,10000.00,'Payment via Bank'),(846,413,15,5000.00,0.00,''),(847,413,39,0.00,5000.00,'Payment via Bank'),(848,414,15,5000.00,0.00,''),(849,414,39,0.00,5000.00,'Payment via Bank'),(850,415,10,20000.00,0.00,''),(851,415,39,0.00,20000.00,'Payment via Bank'),(852,416,10,10000.00,0.00,''),(853,416,39,0.00,10000.00,'Payment via Bank'),(854,417,10,42600.00,0.00,''),(855,417,38,0.00,42600.00,'Payment via Cheque'),(856,418,19,5000.00,0.00,''),(857,418,39,0.00,5000.00,'Payment via Bank'),(858,419,20,3000.00,0.00,'0773041287'),(859,419,39,0.00,3000.00,'Payment via Bank'),(860,420,10,5000.00,0.00,''),(861,420,39,0.00,5000.00,'Payment via Bank'),(862,421,10,5000.00,0.00,''),(863,421,39,0.00,5000.00,'Payment via Bank'),(864,422,12,1500.00,0.00,'Battaramulla'),(865,422,39,0.00,1500.00,'Payment via Bank'),(866,423,34,12000.00,0.00,'Month of April'),(867,423,38,0.00,12000.00,'Payment via Cheque'),(868,424,34,41400.00,0.00,''),(869,424,38,0.00,41400.00,'Payment via Cheque'),(870,425,35,10350.00,0.00,'from Jan to May'),(871,425,38,0.00,10350.00,'Payment via Cheque'),(872,426,29,1200.00,0.00,'NDE Ch'),(873,426,38,0.00,1200.00,'Payment via Bank'),(874,427,15,5000.00,0.00,'neosysmedicare.com'),(875,427,39,0.00,5000.00,'Payment via Bank'),(876,428,20,10000.00,0.00,'Fiber'),(877,428,39,0.00,10000.00,'Payment via Bank'),(878,429,10,10000.00,0.00,''),(879,429,39,0.00,10000.00,'Payment via Bank'),(880,430,12,1000.00,0.00,'DM Raja Visit'),(881,430,39,0.00,1000.00,'Payment via Bank'),(882,431,10,20000.00,0.00,'10K+10K'),(883,431,39,0.00,20000.00,'Payment via Bank'),(884,432,16,34000.00,0.00,''),(885,432,39,0.00,34000.00,'Payment via Bank'),(886,433,14,6500.00,0.00,'itc001'),(887,433,39,0.00,6500.00,'Payment via Bank'),(888,434,14,4500.00,0.00,'itc019'),(889,434,39,0.00,4500.00,'Payment via Bank'),(890,435,16,23000.00,0.00,''),(891,435,39,0.00,23000.00,'Payment via Bank'),(892,436,22,10000.00,0.00,''),(893,436,39,0.00,10000.00,'Payment via Bank'),(894,437,14,6000.00,0.00,'s4'),(895,437,39,0.00,6000.00,'Payment via Bank'),(896,438,15,5000.00,0.00,''),(897,438,39,0.00,5000.00,'Payment via Bank'),(898,439,16,57000.00,0.00,''),(899,439,39,0.00,57000.00,'Payment via Bank'),(900,440,24,22500.00,0.00,'Acc No: 079-2-001-5-0038794'),(901,440,39,0.00,22500.00,'Payment via Bank'),(902,441,12,700.00,0.00,'Maarji Courier Charge'),(903,441,1,0.00,700.00,'Payment via Cash'),(904,442,10,10000.00,0.00,''),(905,442,39,0.00,10000.00,'Payment via Bank'),(906,443,25,7000.00,0.00,'methodist college - ADS'),(907,443,39,0.00,7000.00,'Payment via Bank'),(908,444,66,31720.00,0.00,'Month of May'),(909,444,1,0.00,31720.00,'Payment via Cash'),(910,445,11,20000.00,0.00,'office 54'),(911,445,1,0.00,20000.00,'Payment via Cash'),(912,446,12,2000.00,0.00,'Visit Kalyani Jewellers'),(913,446,39,0.00,2000.00,'Payment via Bank'),(914,447,10,10000.00,0.00,''),(915,447,39,0.00,10000.00,'Payment via Bank'),(916,448,10,20000.00,0.00,''),(917,448,39,0.00,20000.00,'Payment via Bank'),(918,449,10,10000.00,0.00,''),(919,449,39,0.00,10000.00,'Payment via Bank'),(920,450,33,15000.00,0.00,''),(921,450,39,0.00,15000.00,'Payment via Bank'),(922,451,12,5000.00,0.00,'Visit Induwara'),(923,451,39,0.00,5000.00,'Payment via Bank'),(924,452,16,70000.00,0.00,''),(925,452,39,0.00,70000.00,'Payment via Bank'),(926,453,22,10000.00,0.00,'Petrol'),(927,453,1,0.00,10000.00,'Payment via Cash'),(928,454,31,5000.00,0.00,'provide lunch'),(929,454,1,0.00,5000.00,'Payment via Cash'),(930,455,16,35000.00,0.00,''),(931,455,39,0.00,35000.00,'Payment via Bank'),(932,456,16,52000.00,0.00,''),(933,456,39,0.00,52000.00,'Payment via Bank'),(934,457,16,3000.00,0.00,''),(935,457,39,0.00,3000.00,'Payment via Bank'),(936,458,10,10000.00,0.00,''),(937,458,39,0.00,10000.00,'Payment via Bank'),(938,459,17,12000.00,0.00,'vps001'),(939,459,39,0.00,12000.00,'Payment via Bank'),(940,460,14,3000.00,0.00,'itc004'),(941,460,39,0.00,3000.00,'Payment via Bank'),(942,461,14,4000.00,0.00,'itc002'),(943,461,39,0.00,4000.00,'Payment via Bank'),(944,462,10,5000.00,0.00,''),(945,462,39,0.00,5000.00,'Payment via Bank'),(946,463,10,4000.00,0.00,''),(947,463,39,0.00,4000.00,'Payment via Bank'),(948,464,10,20000.00,0.00,''),(949,464,1,0.00,20000.00,'Payment via Cash'),(950,465,16,35000.00,0.00,''),(951,465,39,0.00,35000.00,'Payment via Bank'),(952,466,10,15000.00,0.00,''),(953,466,39,0.00,15000.00,'Payment via Bank'),(954,467,14,4500.00,0.00,'itc003'),(955,467,39,0.00,4500.00,'Payment via Bank'),(956,468,22,10000.00,0.00,''),(957,468,39,0.00,10000.00,'Payment via Bank'),(958,469,9,8000.00,0.00,''),(959,469,39,0.00,8000.00,'Payment via Bank'),(960,470,15,5000.00,0.00,''),(961,470,39,0.00,5000.00,'Payment via Bank'),(962,471,16,37000.00,0.00,''),(963,471,39,0.00,37000.00,'Payment via Bank'),(964,472,29,9000.00,0.00,'credit card'),(965,472,39,0.00,9000.00,'Payment via Bank'),(966,473,16,24000.00,0.00,''),(967,473,39,0.00,24000.00,'Payment via Bank'),(968,474,14,3000.00,0.00,'itc016'),(969,474,39,0.00,3000.00,'Payment via Bank'),(970,475,22,5000.00,0.00,''),(971,475,39,0.00,5000.00,'Payment via Bank'),(972,476,91,90000.00,0.00,''),(973,476,39,0.00,90000.00,'Payment via Bank'),(974,477,16,12000.00,0.00,''),(975,477,39,0.00,12000.00,'Payment via Bank'),(976,478,10,35000.00,0.00,''),(977,478,39,0.00,35000.00,'Payment via Bank'),(978,479,10,60000.00,0.00,''),(979,479,36,0.00,60000.00,'Payment via Bank'),(980,480,90,60000.00,0.00,''),(981,480,36,0.00,60000.00,'Payment via Bank'),(982,481,88,5500.00,0.00,'phone repair - F42'),(983,481,39,0.00,5500.00,'Payment via Bank'),(984,482,10,4500.00,0.00,''),(985,482,39,0.00,4500.00,'Payment via Bank'),(986,483,34,12000.00,0.00,'Month of May'),(987,483,38,0.00,12000.00,'Payment via Cheque'),(988,484,14,3000.00,0.00,'itc010'),(989,484,39,0.00,3000.00,'Payment via Bank'),(990,485,14,4000.00,0.00,'itc0031'),(991,485,39,0.00,4000.00,'Payment via Bank'),(992,486,14,6000.00,0.00,'ix'),(993,486,39,0.00,6000.00,'Payment via Bank'),(994,487,20,2000.00,0.00,'0113670717'),(995,487,39,0.00,2000.00,'Payment via Bank'),(996,488,10,45000.00,0.00,'Salary payout for 2026-06'),(997,488,1,0.00,45000.00,'Payment via Cash'),(998,489,10,50000.00,0.00,'Salary payout for 2026-06'),(999,489,36,0.00,50000.00,'Payment via Bank'),(1000,490,10,30000.00,0.00,'Salary payout for 2026-07'),(1001,490,38,0.00,30000.00,'Payment via Bank'),(1002,491,20,500.00,0.00,'0113670717'),(1003,491,39,0.00,500.00,'Payment via Bank'),(1004,492,10,5000.00,0.00,''),(1005,492,39,0.00,5000.00,'Payment via Bank'),(1006,493,10,14500.00,0.00,''),(1007,493,39,0.00,14500.00,'Payment via Bank'),(1008,494,22,5500.00,0.00,''),(1009,494,39,0.00,5500.00,'Payment via Bank'),(1010,495,15,5000.00,0.00,''),(1011,495,39,0.00,5000.00,'Payment via Bank'),(1012,496,15,5000.00,0.00,''),(1013,496,39,0.00,5000.00,'Payment via Bank'),(1014,497,16,35000.00,0.00,''),(1015,497,39,0.00,35000.00,'Payment via Bank'),(1016,498,16,35000.00,0.00,''),(1017,498,36,0.00,35000.00,'Payment via Cheque - Cheque No: 933102'),(1018,499,14,5000.00,0.00,'itc001'),(1019,499,39,0.00,5000.00,'Payment via Bank'),(1020,500,10,10000.00,0.00,''),(1021,500,39,0.00,10000.00,'Payment via Bank'),(1022,501,25,12000.00,0.00,'Annual Fee'),(1023,501,39,0.00,12000.00,'Payment via Bank'),(1024,502,38,100000.00,0.00,'Customer Payment Received'),(1025,502,3,0.00,100000.00,'Payment credited to invoice'),(1026,503,39,21000.00,0.00,'Customer Payment Received'),(1027,503,3,0.00,21000.00,'Payment credited to invoice'),(1028,504,39,85680.00,0.00,'Customer Payment Received'),(1029,504,3,0.00,85680.00,'Payment credited to invoice'),(1030,505,39,16000.00,0.00,'Customer Payment Received'),(1031,505,3,0.00,16000.00,'Payment credited to invoice'),(1032,506,39,16000.00,0.00,'Customer Payment Received'),(1033,506,3,0.00,16000.00,'Payment credited to invoice'),(1034,507,39,29400.00,0.00,'Customer Payment Received'),(1035,507,3,0.00,29400.00,'Payment credited to invoice'),(1036,508,1,24400.00,0.00,'Customer Payment Received'),(1037,508,3,0.00,24400.00,'Payment credited to invoice'),(1038,509,1,28560.00,0.00,'Customer Payment Received'),(1039,509,3,0.00,28560.00,'Payment credited to invoice'),(1040,510,1,10400.00,0.00,'Customer Payment Received'),(1041,510,3,0.00,10400.00,'Payment credited to invoice'),(1042,511,1,63360.00,0.00,'Customer Payment Received'),(1043,511,3,0.00,63360.00,'Payment credited to invoice'),(1044,512,38,180000.00,0.00,'Customer Payment Received'),(1045,512,3,0.00,180000.00,'Payment credited to invoice'),(1046,513,39,408000.00,0.00,'Customer Payment Received'),(1047,513,3,0.00,408000.00,'Payment credited to invoice'),(1048,514,39,24400.00,0.00,'Customer Payment Received'),(1049,514,3,0.00,24400.00,'Payment credited to invoice'),(1050,515,39,31200.00,0.00,'Customer Payment Received'),(1051,515,3,0.00,31200.00,'Payment credited to invoice'),(1052,516,1,23000.00,0.00,'Customer Payment Received'),(1053,516,3,0.00,23000.00,'Payment credited to invoice'),(1054,517,39,24400.00,0.00,'Customer Payment Received'),(1055,517,3,0.00,24400.00,'Payment credited to invoice'),(1056,518,39,45000.00,0.00,'Customer Payment Received'),(1057,518,3,0.00,45000.00,'Payment credited to invoice'),(1058,519,39,5000.00,0.00,'Customer Payment Received'),(1059,519,3,0.00,5000.00,'Payment credited to invoice'),(1060,520,39,24400.00,0.00,'Customer Payment Received'),(1061,520,3,0.00,24400.00,'Payment credited to invoice'),(1062,521,1,85680.00,0.00,'Customer Payment Received'),(1063,521,3,0.00,85680.00,'Payment credited to invoice'),(1064,522,39,85680.00,0.00,'Customer Payment Received'),(1065,522,3,0.00,85680.00,'Payment credited to invoice'),(1066,523,39,234200.00,0.00,'Customer Payment Received'),(1067,523,3,0.00,234200.00,'Payment credited to invoice'),(1068,524,39,21000.00,0.00,'Customer Payment Received'),(1069,524,3,0.00,21000.00,'Payment credited to invoice'),(1070,525,39,73500.00,0.00,'Customer Payment Received'),(1071,525,3,0.00,73500.00,'Payment credited to invoice'),(1072,526,39,24400.00,0.00,'Customer Payment Received'),(1073,526,3,0.00,24400.00,'Payment credited to invoice'),(1074,527,39,300000.00,0.00,'Customer Payment Received'),(1075,527,3,0.00,300000.00,'Payment credited to invoice'),(1076,528,38,50000.00,0.00,'Customer Payment Received'),(1077,528,3,0.00,50000.00,'Payment credited to invoice'),(1078,529,39,20500.00,0.00,'Customer Payment Received'),(1079,529,3,0.00,20500.00,'Payment credited to invoice'),(1080,530,38,145000.00,0.00,'Customer Payment Received'),(1081,530,3,0.00,145000.00,'Payment credited to invoice'),(1082,531,39,24400.00,0.00,'Customer Payment Received'),(1083,531,3,0.00,24400.00,'Payment credited to invoice'),(1084,532,39,28400.00,0.00,'Customer Payment Received'),(1085,532,3,0.00,28400.00,'Payment credited to invoice'),(1086,533,39,29400.00,0.00,'Customer Payment Received'),(1087,533,3,0.00,29400.00,'Payment credited to invoice'),(1088,534,39,24400.00,0.00,'Customer Payment Received'),(1089,534,3,0.00,24400.00,'Payment credited to invoice'),(1090,535,39,5000.00,0.00,'Customer Payment Received'),(1091,535,3,0.00,5000.00,'Payment credited to invoice'),(1092,536,39,24400.00,0.00,'Customer Payment Received'),(1093,536,3,0.00,24400.00,'Payment credited to invoice'),(1094,537,39,27000.00,0.00,NULL),(1095,537,3,0.00,27000.00,NULL),(1096,538,39,29400.00,0.00,NULL),(1097,538,3,0.00,29400.00,NULL),(1098,539,55,50000.00,0.00,''),(1099,539,39,0.00,50000.00,''),(1100,540,22,5000.00,0.00,''),(1101,540,39,0.00,5000.00,'Payment via Bank'),(1102,541,10,5000.00,0.00,''),(1103,541,39,0.00,5000.00,'Payment via Bank'),(1104,542,39,29400.00,0.00,NULL),(1105,542,3,0.00,29400.00,NULL),(1106,543,20,10000.00,0.00,'Fiber'),(1107,543,39,0.00,10000.00,'Payment via Bank'),(1108,544,14,4500.00,0.00,'itc019'),(1109,544,39,0.00,4500.00,'Payment via Bank'),(1110,545,10,5000.00,0.00,''),(1111,545,39,0.00,5000.00,'Payment via Bank'),(1112,546,10,5000.00,0.00,''),(1113,546,39,0.00,5000.00,'Payment via Bank'),(1114,547,10,5000.00,0.00,''),(1115,547,39,0.00,5000.00,'Payment via Bank'),(1116,548,15,5000.00,0.00,''),(1117,548,39,0.00,5000.00,'Payment via Bank'),(1118,549,39,24400.00,0.00,NULL),(1119,549,3,0.00,24400.00,NULL),(1120,550,92,22500.00,0.00,''),(1121,550,39,0.00,22500.00,'Payment via Bank'),(1122,551,39,29400.00,0.00,NULL),(1123,551,3,0.00,29400.00,NULL),(1124,552,39,8000.00,0.00,NULL),(1125,552,3,0.00,8000.00,NULL),(1126,553,22,5000.00,0.00,''),(1127,553,39,0.00,5000.00,'Payment via Bank'),(1128,554,31,2400.00,0.00,''),(1129,554,39,0.00,2400.00,'Payment via Bank'),(1130,555,15,5000.00,0.00,''),(1131,555,39,0.00,5000.00,'Payment via Bank'),(1132,556,10,10000.00,0.00,''),(1133,556,39,0.00,10000.00,'Payment via Bank'),(1134,557,93,7000.00,0.00,'Doteloya Primary Vidyalaya '),(1135,557,39,0.00,7000.00,'Payment via Bank'),(1136,558,16,3000.00,0.00,''),(1137,558,39,0.00,3000.00,'Payment via Bank'),(1140,560,16,9000.00,0.00,''),(1141,560,39,0.00,9000.00,'Payment via Bank'),(1142,561,16,19000.00,0.00,''),(1143,561,39,0.00,19000.00,'Payment via Bank'),(1144,562,14,5000.00,0.00,'s4'),(1145,562,39,0.00,5000.00,'Payment via Bank'),(1148,563,85,50000.00,0.00,'Personal Withdrawal - Personal Expense to Sasikala'),(1149,563,39,0.00,50000.00,'Paid from Bank'),(1150,564,38,18900.00,0.00,NULL),(1151,564,3,0.00,18900.00,NULL),(1154,566,15,5000.00,0.00,''),(1155,566,39,0.00,5000.00,'Payment via Bank'),(1156,567,93,4000.00,0.00,''),(1157,567,39,0.00,4000.00,'Payment via Bank'),(1158,568,39,50880.00,0.00,NULL),(1159,568,3,0.00,50880.00,NULL),(1160,569,10,45000.00,0.00,NULL),(1161,569,34,4200.00,0.00,NULL),(1162,569,35,1050.00,0.00,NULL),(1163,569,1,0.00,42200.00,NULL),(1164,569,51,0.00,7000.00,NULL),(1165,569,52,0.00,1050.00,NULL),(1166,570,38,81750.00,0.00,NULL),(1167,570,3,0.00,81750.00,NULL),(1168,571,10,50000.00,0.00,NULL),(1169,571,34,4200.00,0.00,NULL),(1170,571,35,1050.00,0.00,NULL),(1171,571,1,0.00,47200.00,NULL),(1172,571,51,0.00,7000.00,NULL),(1173,571,52,0.00,1050.00,NULL),(1174,572,28,15000.00,0.00,''),(1175,572,1,0.00,15000.00,'Payment via Cash'),(1176,573,39,100000.00,0.00,NULL),(1177,573,3,0.00,100000.00,NULL),(1178,574,4,225000.00,0.00,NULL),(1179,574,5,0.00,225000.00,NULL),(1180,575,5,225000.00,0.00,NULL),(1181,575,1,0.00,225000.00,NULL),(1182,576,1,200000.00,0.00,NULL),(1183,576,3,0.00,200000.00,NULL),(1184,577,1,250000.00,0.00,NULL),(1185,577,3,0.00,250000.00,NULL);
/*!40000 ALTER TABLE `journal_entry_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entry_details_backup`
--

DROP TABLE IF EXISTS `journal_entry_details_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `journal_entry_details_backup` VALUES (1,1,1,100000.00,0.00,NULL),(2,1,7,0.00,100000.00,NULL),(3,2,1,21000.00,0.00,NULL),(4,2,7,0.00,21000.00,NULL),(5,3,1,85680.00,0.00,NULL),(6,3,7,0.00,85680.00,NULL),(39,20,1,16000.00,0.00,NULL),(40,20,7,0.00,16000.00,NULL),(41,21,1,16000.00,0.00,NULL),(42,21,7,0.00,16000.00,NULL),(43,22,1,16000.00,0.00,NULL),(44,22,7,0.00,16000.00,NULL),(69,35,1,29400.00,0.00,NULL),(70,35,7,0.00,29400.00,NULL),(71,36,1,24400.00,0.00,NULL),(72,36,7,0.00,24400.00,NULL),(73,37,1,28560.00,0.00,NULL),(74,37,7,0.00,28560.00,NULL),(75,38,1,10400.00,0.00,NULL),(76,38,7,0.00,10400.00,NULL),(77,39,1,63360.00,0.00,NULL),(78,39,7,0.00,63360.00,NULL),(79,55,14,4000.00,0.00,NULL),(80,56,15,5000.00,0.00,NULL),(81,57,22,10000.00,0.00,NULL),(82,58,15,5000.00,0.00,NULL),(83,59,15,5000.00,0.00,NULL),(84,60,10,20000.00,0.00,NULL),(85,61,10,10000.00,0.00,NULL),(86,62,10,42600.00,0.00,NULL),(87,63,19,5000.00,0.00,NULL),(88,64,20,3000.00,0.00,NULL),(94,55,2,0.00,4000.00,NULL),(95,56,2,0.00,5000.00,NULL),(96,57,2,0.00,10000.00,NULL),(97,58,2,0.00,5000.00,NULL),(98,59,2,0.00,5000.00,NULL),(99,60,2,0.00,20000.00,NULL),(100,61,2,0.00,10000.00,NULL),(101,62,2,0.00,42600.00,NULL),(102,63,2,0.00,5000.00,NULL),(103,64,2,0.00,3000.00,NULL);
/*!40000 ALTER TABLE `journal_entry_details_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_items` (
  `PurchaseItemID` int NOT NULL AUTO_INCREMENT,
  `PurchaseID` int NOT NULL,
  `ItemID` int NOT NULL,
  `Quantity` decimal(10,2) DEFAULT NULL,
  `QtyType` varchar(50) DEFAULT NULL,
  `PurchasePrice` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalAmount` decimal(10,2) DEFAULT NULL,
  `CategoryID` int DEFAULT NULL,
  `BrandID` int DEFAULT NULL,
  PRIMARY KEY (`PurchaseItemID`),
  KEY `PurchaseID` (`PurchaseID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
INSERT INTO `purchase_items` VALUES (1,1,2,15.00,'Pcs',15000.00,0.00,225000.00,NULL,NULL);
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order`
--

DROP TABLE IF EXISTS `purchase_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order` (
  `PurchaseOrderID` int NOT NULL AUTO_INCREMENT,
  `PurchaseOrderNo` varchar(50) NOT NULL,
  `PurchaseOrderDate` date NOT NULL,
  `VendorID` int NOT NULL,
  `VendorName` varchar(255) NOT NULL,
  `SubTotal` decimal(18,2) DEFAULT '0.00',
  `DiscountAmount` decimal(18,2) DEFAULT '0.00',
  `GrandTotal` decimal(18,2) DEFAULT '0.00',
  `Status` varchar(50) DEFAULT 'Pending',
  `Remarks` text,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `PurchaseOrderItemID` int NOT NULL AUTO_INCREMENT,
  `PurchaseOrderID` int NOT NULL,
  `ItemID` int NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Description` text,
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
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (1,1,1,NULL,'2026-07-26','',0.00,'',225000.00,'2026-07-26 10:24:01','2026-07-26 10:24:01',0,NULL);
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation`
--

DROP TABLE IF EXISTS `quotation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation`
--

LOCK TABLES `quotation` WRITE;
/*!40000 ALTER TABLE `quotation` DISABLE KEYS */;
INSERT INTO `quotation` VALUES (1,'QT-20260602-0001','2026-06-01',62,'',150000.00,0.00,0.00,150000.00,'Approved','',NULL,'2026-06-02 16:22:58',NULL,'2026-06-02 16:23:04',0,NULL),(2,'QT-20260602-0002','2026-06-01',65,'',21000.00,0.00,0.00,21000.00,'Approved','',NULL,'2026-06-02 16:45:08',NULL,'2026-06-02 16:45:10',0,NULL),(3,'QT-20260602-0003','2026-06-01',38,'',85680.00,0.00,0.00,85680.00,'Approved','',NULL,'2026-06-02 16:50:34',NULL,'2026-06-02 16:50:39',0,NULL),(4,'QT-20260603-0004','2026-06-02',32,'',16000.00,0.00,0.00,16000.00,'Approved','',NULL,'2026-06-03 12:38:35',NULL,'2026-06-03 12:39:34',0,NULL),(5,'QT-20260603-0005','2026-06-02',91,'',16000.00,0.00,0.00,16000.00,'Approved','',NULL,'2026-06-03 12:39:16',NULL,'2026-06-03 12:40:41',0,NULL),(6,'QT-20260603-0006','2026-06-03',6,'',29400.00,0.00,0.00,29400.00,'Approved','',NULL,'2026-06-03 22:18:13',NULL,'2026-06-03 22:25:46',0,NULL),(7,'QT-20260604-0007','2026-06-03',77,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-06-04 05:50:53',NULL,'2026-06-04 05:51:05',0,NULL),(8,'QT-20260604-0008','2026-06-03',55,'',28560.00,0.00,0.00,28560.00,'Approved','',NULL,'2026-06-04 06:00:31',NULL,'2026-06-04 06:00:34',0,NULL),(9,'QT-20260604-0009','2026-06-03',77,'',10400.00,0.00,0.00,10400.00,'Approved','',NULL,'2026-06-04 06:02:04',NULL,'2026-06-04 06:02:06',0,NULL),(10,'QT-20260604-0010','2026-06-03',12,'',63360.00,0.00,0.00,63360.00,'Approved','',NULL,'2026-06-04 06:05:11',NULL,'2026-06-04 06:05:13',0,NULL),(11,'QT-20260604-0011','2026-04-27',202,'',360000.00,0.00,0.00,360000.00,'Approved','',NULL,'2026-06-04 19:52:57',NULL,'2026-06-04 19:53:02',0,NULL),(12,'QT-20260605-0012','2026-06-05',17,'',408000.00,0.00,0.00,408000.00,'Approved','',NULL,'2026-06-05 19:45:30',NULL,'2026-06-05 19:45:34',0,NULL),(13,'QT-20260605-0013','2026-06-05',74,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-06-05 22:21:22',NULL,'2026-06-05 22:21:26',0,NULL),(14,'QT-20260605-0014','2026-06-05',72,'',31200.00,0.00,0.00,31200.00,'Approved','',NULL,'2026-06-05 22:30:05',NULL,'2026-06-05 22:30:07',0,NULL),(15,'QT-20260608-0015','2026-06-08',34,'',23000.00,0.00,0.00,23000.00,'Approved','',NULL,'2026-06-08 22:36:21',NULL,'2026-06-08 22:36:24',0,NULL),(16,'QT-20260609-0016','2026-06-09',211,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-06-09 17:21:08',NULL,'2026-06-09 17:21:10',0,NULL),(17,'QT-20260609-0017','2026-06-09',32,'',5000.00,0.00,0.00,5000.00,'Approved','',NULL,'2026-06-09 17:43:30',NULL,'2026-06-09 17:43:32',0,NULL),(18,'QT-20260609-0018','2026-06-09',218,'',90000.00,0.00,0.00,90000.00,'Approved','finger print',NULL,'2026-06-09 17:53:28',NULL,'2026-06-09 17:53:32',0,NULL),(19,'QT-20260612-0019','2026-06-12',75,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-06-12 16:37:54',NULL,'2026-06-12 16:37:57',0,NULL),(20,'QT-20260615-0020','2026-06-15',41,'',85680.00,0.00,0.00,85680.00,'Approved','',NULL,'2026-06-15 12:54:23',NULL,'2026-06-15 12:54:25',0,NULL),(21,'QT-20260615-0021','2026-06-15',5,'',85680.00,0.00,0.00,85680.00,'Approved','',NULL,'2026-06-15 21:06:27',NULL,'2026-06-15 21:06:29',0,NULL),(22,'QT-20260615-0022','2026-06-15',34,'',234200.00,0.00,0.00,234200.00,'Approved','',NULL,'2026-06-15 21:16:17',NULL,'2026-06-15 21:16:19',0,NULL),(23,'QT-20260616-0023','2026-06-16',239,'',21000.00,0.00,0.00,21000.00,'Approved','',NULL,'2026-06-16 15:00:38',NULL,'2026-06-16 15:00:41',0,NULL),(24,'QT-20260616-0024','2026-06-16',202,'',290000.00,0.00,0.00,290000.00,'Approved','update 16/06/2026',NULL,'2026-06-16 16:10:24',NULL,'2026-06-16 16:10:27',0,NULL),(25,'QT-20260618-0025','2026-06-18',36,'',147000.00,0.00,0.00,147000.00,'Approved','',NULL,'2026-06-18 15:23:23',NULL,'2026-06-18 15:23:25',0,NULL),(26,'QT-20260624-0026','2026-06-24',238,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-06-24 11:05:51',NULL,'2026-06-24 11:06:44',0,NULL),(27,'QT-20260626-0027','2025-06-26',244,'',650000.00,0.00,0.00,650000.00,'Approved','balance 650000',NULL,'2026-06-26 15:04:54',NULL,'2026-06-26 15:04:56',0,NULL),(28,'QT-20260701-0028','2026-07-01',223,'',20500.00,0.00,0.00,20500.00,'Approved','',NULL,'2026-07-01 15:05:57',NULL,'2026-07-01 15:06:00',0,NULL),(29,'QT-20260701-0029','2026-07-01',101,'',28400.00,0.00,0.00,28400.00,'Approved','',NULL,'2026-07-01 21:11:27',NULL,'2026-07-01 21:11:29',0,NULL),(30,'QT-20260701-0030','2026-07-01',97,'',29400.00,0.00,0.00,29400.00,'Approved','',NULL,'2026-07-01 21:13:46',NULL,'2026-07-01 21:13:48',0,NULL),(31,'QT-20260701-0031','2026-07-01',4,'',25000.00,0.00,0.00,25000.00,'Approved','',NULL,'2026-07-01 21:14:41',NULL,'2026-07-01 21:14:43',0,NULL),(32,'QT-20260701-0032','2026-07-01',243,'',27000.00,0.00,0.00,27000.00,'Approved','',NULL,'2026-07-01 21:16:26',NULL,'2026-07-01 21:16:27',0,NULL),(33,'QT-20260701-0033','2026-07-01',88,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-07-01 21:19:17',NULL,'2026-07-01 21:19:19',0,NULL),(34,'QT-20260701-0034','2026-07-01',245,'',31300.00,0.00,0.00,31300.00,'Approved','',NULL,'2026-07-01 21:23:27',NULL,'2026-07-01 21:23:29',0,NULL),(35,'QT-20260701-0035','2026-07-01',87,'',8000.00,0.00,0.00,8000.00,'Approved','',NULL,'2026-07-01 21:24:41',NULL,'2026-07-01 21:24:42',0,NULL),(36,'QT-20260701-0036','2026-07-01',86,'',29400.00,0.00,0.00,29400.00,'Approved','',NULL,'2026-07-01 21:25:41',NULL,'2026-07-01 21:25:43',0,NULL),(37,'QT-20260701-0037','2026-07-01',85,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-07-01 21:26:29',NULL,'2026-07-01 21:26:30',0,NULL),(38,'QT-20260701-0038','2026-07-01',84,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-07-01 21:27:39',NULL,'2026-07-01 21:27:40',0,NULL),(39,'QT-20260701-0039','2026-07-01',81,'',24400.00,0.00,0.00,24400.00,'Approved','',NULL,'2026-07-01 21:28:47',NULL,'2026-07-01 21:28:49',0,NULL),(40,'QT-20260704-0040','2026-07-04',202,'',395000.00,0.00,0.00,395000.00,'Approved','New updates - 26/06/2026',NULL,'2026-07-04 11:24:10',NULL,'2026-07-04 11:24:14',0,NULL),(41,'QT-20260704-0041','2026-07-04',202,'',300000.00,0.00,0.00,300000.00,'Approved','',NULL,'2026-07-04 15:17:23',NULL,'2026-07-04 15:17:26',0,NULL),(42,'QT-20260708-0042','2026-07-08',5,'',29400.00,0.00,0.00,29400.00,'Approved','senal@micro.lk',NULL,'2026-07-08 20:03:59',NULL,'2026-07-08 14:34:20',0,NULL),(43,'QT-20260714-0043','2026-07-14',235,'',18900.00,0.00,0.00,18900.00,'Approved','horeca@rslanka.lk \r\nmathasales1@rslanka.lk\r\nmathasales2@rslanka.lk',NULL,'2026-07-14 18:33:22',NULL,'2026-07-16 10:29:44',0,NULL),(44,'QT-20260716-0044','2026-05-24',202,'',212500.00,0.00,0.00,212500.00,'Approved','Total Amount = 425,000/-\r\npaid = 212.500/-\r\n',NULL,'2026-07-16 16:11:57',NULL,'2026-07-16 10:42:02',0,NULL),(45,'QT-20260717-0045','2026-07-09',218,'',650000.00,0.00,0.00,650000.00,'Pending','',NULL,'2026-07-17 11:46:15',NULL,'2026-07-17 06:16:15',0,NULL),(46,'QT-20260717-0046','2026-07-13',218,'',85000.00,0.00,0.00,85000.00,'Pending','',NULL,'2026-07-17 11:47:35',NULL,'2026-07-17 06:17:35',0,NULL),(47,'QT-20260717-0047','2026-07-15',218,'',45000.00,0.00,0.00,45000.00,'Approved','',NULL,'2026-07-17 11:48:14',NULL,'2026-07-24 12:40:18',0,NULL),(48,'QT-20260724-0048','2026-07-24',1,'',350000.00,50000.00,0.00,300000.00,'Approved','',NULL,'2026-07-24 13:27:20',NULL,'2026-07-24 13:28:06',0,NULL),(49,'QT-20260725-0049','2026-07-25',1,'',150000.00,0.00,0.00,150000.00,'Approved','',NULL,'2026-07-25 11:01:08',NULL,'2026-07-25 11:01:13',0,NULL);
/*!40000 ALTER TABLE `quotation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation_items`
--

DROP TABLE IF EXISTS `quotation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation_items`
--

LOCK TABLES `quotation_items` WRITE;
/*!40000 ALTER TABLE `quotation_items` DISABLE KEYS */;
INSERT INTO `quotation_items` VALUES (1,1,7,'',NULL,1.00,150000.00,150000.00,'2026-06-02 16:22:58','2026-06-02 16:22:58'),(2,2,17,'',NULL,1.00,21000.00,21000.00,'2026-06-02 16:45:08','2026-06-02 16:45:08'),(3,3,20,'',NULL,1.00,85680.00,85680.00,'2026-06-02 16:50:34','2026-06-02 16:50:34'),(4,4,6,'',NULL,1.00,16000.00,16000.00,'2026-06-03 12:38:35','2026-06-03 12:38:35'),(5,5,6,'',NULL,1.00,16000.00,16000.00,'2026-06-03 12:39:16','2026-06-03 12:39:16'),(6,6,17,'',NULL,1.00,29400.00,29400.00,'2026-06-03 22:18:13','2026-06-03 22:18:13'),(7,7,17,'',NULL,1.00,24400.00,24400.00,'2026-06-04 05:50:53','2026-06-04 05:50:53'),(8,8,20,'',NULL,1.00,28560.00,28560.00,'2026-06-04 06:00:31','2026-06-04 06:00:31'),(9,9,6,'',NULL,1.00,10400.00,10400.00,'2026-06-04 06:02:04','2026-06-04 06:02:04'),(10,10,17,'',NULL,1.00,24400.00,24400.00,'2026-06-04 06:05:11','2026-06-04 06:05:11'),(11,10,6,'',NULL,1.00,10400.00,10400.00,'2026-06-04 06:05:11','2026-06-04 06:05:11'),(12,10,20,'',NULL,1.00,28560.00,28560.00,'2026-06-04 06:05:11','2026-06-04 06:05:11'),(13,11,18,'',NULL,1.00,360000.00,360000.00,'2026-06-04 19:52:57','2026-06-04 19:52:57'),(14,12,21,'',NULL,1.00,408000.00,408000.00,'2026-06-05 19:45:30','2026-06-05 19:45:30'),(15,13,17,'',NULL,1.00,24400.00,24400.00,'2026-06-05 22:21:22','2026-06-05 22:21:22'),(16,14,17,'',NULL,1.00,31200.00,31200.00,'2026-06-05 22:30:05','2026-06-05 22:30:05'),(17,15,17,'',NULL,1.00,23000.00,23000.00,'2026-06-08 22:36:21','2026-06-08 22:36:21'),(18,16,17,'',NULL,1.00,24400.00,24400.00,'2026-06-09 17:21:08','2026-06-09 17:21:08'),(19,17,4,'',NULL,1.00,5000.00,5000.00,'2026-06-09 17:43:30','2026-06-09 17:43:30'),(20,18,22,'',NULL,1.00,90000.00,90000.00,'2026-06-09 17:53:28','2026-06-09 17:53:28'),(21,19,17,'',NULL,1.00,24400.00,24400.00,'2026-06-12 16:37:54','2026-06-12 16:37:54'),(22,20,20,'',NULL,1.00,85680.00,85680.00,'2026-06-15 12:54:23','2026-06-15 12:54:23'),(23,21,20,'',NULL,1.00,85680.00,85680.00,'2026-06-15 21:06:27','2026-06-15 21:06:27'),(24,22,21,'',NULL,1.00,234200.00,234200.00,'2026-06-15 21:16:17','2026-06-15 21:16:17'),(25,23,17,'',NULL,1.00,21000.00,21000.00,'2026-06-16 15:00:38','2026-06-16 15:00:38'),(26,24,18,'',NULL,1.00,290000.00,290000.00,'2026-06-16 16:10:24','2026-06-16 16:10:24'),(27,25,20,'',NULL,1.00,147000.00,147000.00,'2026-06-18 15:23:23','2026-06-18 15:23:23'),(28,26,17,'',NULL,1.00,24400.00,24400.00,'2026-06-24 11:05:51','2026-06-24 11:05:51'),(29,27,18,'',NULL,1.00,650000.00,650000.00,'2026-06-26 15:04:54','2026-06-26 15:04:54'),(30,28,23,'',NULL,1.00,10500.00,10500.00,'2026-07-01 15:05:57','2026-07-01 15:05:57'),(31,28,24,'',NULL,1.00,10000.00,10000.00,'2026-07-01 15:05:57','2026-07-01 15:05:57'),(32,29,17,'',NULL,1.00,28400.00,28400.00,'2026-07-01 21:11:27','2026-07-01 21:11:27'),(33,30,17,'',NULL,1.00,29400.00,29400.00,'2026-07-01 21:13:46','2026-07-01 21:13:46'),(34,31,14,'',NULL,1.00,25000.00,25000.00,'2026-07-01 21:14:41','2026-07-01 21:14:41'),(35,32,17,'',NULL,1.00,27000.00,27000.00,'2026-07-01 21:16:26','2026-07-01 21:16:26'),(36,33,17,'',NULL,1.00,24400.00,24400.00,'2026-07-01 21:19:17','2026-07-01 21:19:17'),(37,34,17,'',NULL,1.00,31300.00,31300.00,'2026-07-01 21:23:27','2026-07-01 21:23:27'),(38,35,4,'',NULL,1.00,8000.00,8000.00,'2026-07-01 21:24:41','2026-07-01 21:24:41'),(39,36,17,'',NULL,1.00,29400.00,29400.00,'2026-07-01 21:25:41','2026-07-01 21:25:41'),(40,37,17,'',NULL,1.00,24400.00,24400.00,'2026-07-01 21:26:29','2026-07-01 21:26:29'),(41,38,17,'',NULL,1.00,24400.00,24400.00,'2026-07-01 21:27:39','2026-07-01 21:27:39'),(42,39,17,'',NULL,1.00,24400.00,24400.00,'2026-07-01 21:28:47','2026-07-01 21:28:47'),(43,40,18,'',NULL,1.00,395000.00,395000.00,'2026-07-04 11:24:10','2026-07-04 11:24:10'),(44,41,18,'',NULL,1.00,300000.00,300000.00,'2026-07-04 15:17:23','2026-07-04 15:17:23'),(46,42,20,'',NULL,1.00,29400.00,29400.00,'2026-07-08 14:34:15','2026-07-08 14:34:15'),(47,43,15,'',NULL,3.00,6300.00,18900.00,'2026-07-14 13:03:22','2026-07-14 13:03:22'),(48,44,18,'',NULL,1.00,212500.00,212500.00,'2026-07-16 10:41:57','2026-07-16 10:41:57'),(49,45,18,'',NULL,1.00,650000.00,650000.00,'2026-07-17 06:16:15','2026-07-17 06:16:15'),(50,46,18,'',NULL,1.00,85000.00,85000.00,'2026-07-17 06:17:35','2026-07-17 06:17:35'),(51,47,18,'',NULL,1.00,45000.00,45000.00,'2026-07-17 06:18:14','2026-07-17 06:18:14'),(54,48,7,'',NULL,1.00,350000.00,350000.00,'2026-07-24 13:28:06','2026-07-24 13:28:06'),(55,49,2,'',NULL,1.00,150000.00,150000.00,'2026-07-25 11:01:08','2026-07-25 11:01:08');
/*!40000 ALTER TABLE `quotation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `received_cheques`
--

DROP TABLE IF EXISTS `received_cheques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `received_cheques`
--

LOCK TABLES `received_cheques` WRITE;
/*!40000 ALTER TABLE `received_cheques` DISABLE KEYS */;
INSERT INTO `received_cheques` VALUES (1,62,0,'121','2026-06-01','2026-06-01',1,'2026-06-03',100000.00,'Cleared','Customer Payment Cheque','2026-06-02 16:24:35');
/*!40000 ALTER TABLE `received_cheques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reload`
--

DROP TABLE IF EXISTS `reload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salary_slips` (
  `SlipID` int NOT NULL AUTO_INCREMENT,
  `EmployeeID` int NOT NULL,
  `PayrollMonth` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `BasicSalary` decimal(18,2) NOT NULL,
  `Allowance` decimal(18,2) NOT NULL,
  `AllowancesJSON` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `OTHours` decimal(10,2) DEFAULT '0.00',
  `OTRate` decimal(10,2) DEFAULT '0.00',
  `OTAmount` decimal(18,2) DEFAULT '0.00',
  `GrossSalary` decimal(18,2) NOT NULL,
  `EmployeeEPF` decimal(18,2) NOT NULL,
  `AdvanceDeduction` decimal(18,2) DEFAULT '0.00',
  `NetSalary` decimal(18,2) NOT NULL,
  `CompanyEPF` decimal(18,2) NOT NULL,
  `CompanyETF` decimal(18,2) NOT NULL,
  `PaymentMethod` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `BankID` int DEFAULT NULL,
  `PaymentDate` date NOT NULL,
  `JournalID` int DEFAULT NULL,
  `IsDeleted` tinyint(1) DEFAULT '0',
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SlipID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_slips`
--

LOCK TABLES `salary_slips` WRITE;
/*!40000 ALTER TABLE `salary_slips` DISABLE KEYS */;
INSERT INTO `salary_slips` VALUES (1,2,'2026-06',35000.00,10000.00,NULL,0.00,0.00,0.00,45000.00,2800.00,25000.00,17200.00,4200.00,1050.00,'Cash',NULL,'2026-06-25',224,0,'2026-06-25 15:10:06'),(2,1,'2026-06',35000.00,15000.00,NULL,0.00,0.00,0.00,50000.00,2800.00,0.00,47200.00,4200.00,1050.00,'Bank',4,'2026-06-27',239,1,'2026-06-27 21:20:07'),(3,1,'2026-06',35000.00,15000.00,NULL,0.00,0.00,0.00,50000.00,2800.00,0.00,47200.00,4200.00,1050.00,'Bank',2,'2026-06-30',244,0,'2026-06-30 14:26:15'),(4,6,'2026-07',30000.00,0.00,NULL,0.00,0.00,0.00,30000.00,0.00,0.00,30000.00,0.00,0.00,'Bank',1,'2026-07-02',258,0,'2026-07-02 20:00:47'),(5,2,'2026-07',35000.00,10000.00,NULL,0.00,0.00,0.00,45000.00,2800.00,0.00,42200.00,4200.00,1050.00,'Cash',NULL,'2026-07-24',569,0,'2026-07-24 11:26:45'),(6,1,'2026-07',35000.00,15000.00,NULL,0.00,0.00,0.00,50000.00,2800.00,0.00,47200.00,4200.00,1050.00,'Cash',NULL,'2026-07-24',571,0,'2026-07-24 14:36:11');
/*!40000 ALTER TABLE `salary_slips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `SaleItemID` int NOT NULL AUTO_INCREMENT,
  `SaleID` int NOT NULL,
  `ItemID` int DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,7,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(2,2,17,1.00,'Pcs',21000.00,0.00,0.00,0.00,21000.00,NULL,NULL),(3,3,20,1.00,'Pcs',85680.00,0.00,0.00,0.00,85680.00,NULL,NULL),(4,4,6,1.00,'Pcs',16000.00,0.00,0.00,0.00,16000.00,NULL,NULL),(5,5,6,1.00,'Pcs',16000.00,0.00,0.00,0.00,16000.00,NULL,NULL),(6,6,17,1.00,'Pcs',29400.00,0.00,0.00,0.00,29400.00,NULL,NULL),(7,7,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(8,8,20,1.00,'Pcs',28560.00,0.00,0.00,0.00,28560.00,NULL,NULL),(9,9,6,1.00,'Pcs',10400.00,0.00,0.00,0.00,10400.00,NULL,NULL),(10,10,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(11,10,6,1.00,'Pcs',10400.00,0.00,0.00,0.00,10400.00,NULL,NULL),(12,10,20,1.00,'Pcs',28560.00,0.00,0.00,0.00,28560.00,NULL,NULL),(13,11,18,1.00,'Pcs',360000.00,0.00,0.00,0.00,360000.00,NULL,NULL),(14,12,21,1.00,'Pcs',408000.00,0.00,0.00,0.00,408000.00,NULL,NULL),(15,13,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(16,14,17,1.00,'Pcs',31200.00,0.00,0.00,0.00,31200.00,NULL,NULL),(17,15,17,1.00,'Pcs',23000.00,0.00,0.00,0.00,23000.00,NULL,NULL),(18,16,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(19,17,4,1.00,'Pcs',5000.00,0.00,0.00,0.00,5000.00,NULL,NULL),(20,18,22,1.00,'Pcs',90000.00,0.00,0.00,0.00,90000.00,NULL,NULL),(21,19,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(22,20,20,1.00,'Pcs',85680.00,0.00,0.00,0.00,85680.00,NULL,NULL),(23,21,20,1.00,'Pcs',85680.00,0.00,0.00,0.00,85680.00,NULL,NULL),(24,22,21,1.00,'Pcs',234200.00,0.00,0.00,0.00,234200.00,NULL,NULL),(25,23,17,1.00,'Pcs',21000.00,0.00,0.00,0.00,21000.00,NULL,NULL),(26,24,18,1.00,'Pcs',290000.00,0.00,0.00,0.00,290000.00,NULL,NULL),(27,25,20,1.00,'Pcs',147000.00,0.00,0.00,0.00,147000.00,NULL,NULL),(28,26,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(29,27,18,1.00,'Pcs',650000.00,0.00,0.00,0.00,650000.00,NULL,NULL),(30,28,23,1.00,'Pcs',10500.00,0.00,0.00,0.00,10500.00,NULL,NULL),(31,28,24,1.00,'Pcs',10000.00,0.00,0.00,0.00,10000.00,NULL,NULL),(32,29,17,1.00,'Pcs',28400.00,0.00,0.00,0.00,28400.00,NULL,NULL),(33,30,17,1.00,'Pcs',29400.00,0.00,0.00,0.00,29400.00,NULL,NULL),(34,31,14,1.00,'Pcs',25000.00,0.00,0.00,0.00,25000.00,NULL,NULL),(35,32,17,1.00,'Pcs',27000.00,0.00,0.00,0.00,27000.00,NULL,NULL),(36,33,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(37,34,17,1.00,'Pcs',31300.00,0.00,0.00,0.00,31300.00,NULL,NULL),(38,35,4,1.00,'Pcs',8000.00,0.00,0.00,0.00,8000.00,NULL,NULL),(39,36,17,1.00,'Pcs',29400.00,0.00,0.00,0.00,29400.00,NULL,NULL),(40,37,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(41,38,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(42,39,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,NULL,NULL),(44,41,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,0,0),(45,42,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,0,0),(46,43,17,1.00,'Pcs',29400.00,0.00,0.00,0.00,29400.00,0,0),(48,45,17,1.00,'Pcs',25000.00,0.00,0.00,0.00,25000.00,0,0),(49,46,17,1.00,'Pcs',42000.00,0.00,0.00,0.00,42000.00,0,0),(50,47,17,1.00,'Pcs',15000.00,0.00,0.00,0.00,15000.00,0,0),(51,48,17,1.00,'Pcs',35000.00,0.00,0.00,0.00,35000.00,0,0),(52,49,18,1.00,'Pcs',395000.00,0.00,0.00,0.00,395000.00,NULL,NULL),(53,50,18,1.00,'Pcs',300000.00,0.00,0.00,0.00,300000.00,NULL,NULL),(54,51,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,0,0),(55,52,17,1.00,'Pcs',5000.00,0.00,0.00,0.00,5000.00,0,0),(56,53,20,1.00,'Pcs',29400.00,0.00,0.00,0.00,29400.00,NULL,NULL),(57,54,17,1.00,'Pcs',32500.00,0.00,0.00,0.00,32500.00,0,0),(58,55,17,1.00,'Pcs',81750.00,0.00,0.00,0.00,81750.00,0,0),(59,56,17,1.00,'Pcs',11700.00,0.00,0.00,0.00,11700.00,0,0),(60,57,17,1.00,'Pcs',10500.00,0.00,0.00,0.00,10500.00,0,0),(61,58,17,1.00,'Pcs',24400.00,0.00,0.00,0.00,24400.00,0,0),(62,59,17,1.00,'Pcs',50880.00,0.00,0.00,0.00,50880.00,0,0),(63,60,15,3.00,'Pcs',6300.00,0.00,0.00,0.00,18900.00,NULL,NULL),(65,61,18,1.00,'Pcs',212500.00,0.00,0.00,0.00,212500.00,NULL,NULL),(66,62,2,1.00,'Pcs',140000.00,0.00,0.00,0.00,140000.00,NULL,NULL),(67,63,2,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(68,64,2,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(69,64,3,1.00,'Pcs',250000.00,0.00,0.00,0.00,250000.00,NULL,NULL),(70,64,4,1.00,'Pcs',6500.00,0.00,0.00,0.00,6500.00,NULL,NULL),(71,65,2,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(72,65,3,1.00,'Pcs',250000.00,0.00,0.00,0.00,250000.00,NULL,NULL),(73,65,4,1.00,'Pcs',6500.00,0.00,0.00,0.00,6500.00,NULL,NULL),(74,65,5,1.00,'Pcs',15000.00,0.00,0.00,0.00,15000.00,NULL,NULL),(75,66,3,1.00,'Pcs',250000.00,0.00,0.00,0.00,250000.00,NULL,NULL),(76,67,5,1.00,'Pcs',15000.00,0.00,0.00,0.00,15000.00,NULL,NULL),(77,67,6,1.00,'Pcs',12000.00,0.00,0.00,0.00,12000.00,NULL,NULL),(78,67,4,1.00,'Pcs',6500.00,0.00,0.00,0.00,6500.00,NULL,NULL),(79,67,3,1.00,'Pcs',250000.00,0.00,0.00,0.00,250000.00,NULL,NULL),(80,67,2,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(81,68,2,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(82,68,3,1.00,'Pcs',250000.00,0.00,0.00,0.00,250000.00,NULL,NULL),(83,68,4,1.00,'Pcs',6500.00,0.00,0.00,0.00,6500.00,NULL,NULL),(84,68,5,2.00,'Pcs',15000.00,0.00,0.00,0.00,30000.00,NULL,NULL),(85,68,6,1.00,'Pcs',12000.00,0.00,0.00,0.00,12000.00,NULL,NULL),(86,68,7,1.00,'Pcs',350000.00,0.00,0.00,0.00,350000.00,NULL,NULL),(87,68,8,1.00,'Pcs',200000.00,0.00,0.00,0.00,200000.00,NULL,NULL),(88,68,9,1.00,'Pcs',180000.00,0.00,0.00,0.00,180000.00,NULL,NULL),(89,69,2,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(90,70,2,1.00,'Pcs',150000.00,0.00,0.00,0.00,150000.00,NULL,NULL),(91,70,3,1.00,'Boxes',250000.00,0.00,0.00,0.00,250000.00,NULL,NULL),(92,71,3,1.00,'Pcs',250000.00,0.00,0.00,0.00,250000.00,NULL,NULL);
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `SaleID` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `CustomerId` int NOT NULL,
  `LocationId` int DEFAULT NULL,
  `SaleDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `JobCardID` int DEFAULT NULL,
  `RefNo` varchar(100) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,1,62,NULL,'2026-06-01 00:00:00',1,NULL,NULL,0.00,150000.00,'2026-06-02 16:23:43','2026-06-02 16:23:43',0,NULL,0.00,0.00),(2,1,65,NULL,'2026-06-01 00:00:00',2,NULL,NULL,0.00,21000.00,'2026-06-02 16:46:00','2026-06-02 16:46:00',0,NULL,0.00,0.00),(3,1,38,NULL,'2026-06-02 00:00:00',3,NULL,NULL,0.00,85680.00,'2026-06-02 16:52:20','2026-06-02 16:52:20',0,NULL,0.00,0.00),(4,1,32,NULL,'2026-06-02 00:00:00',4,NULL,NULL,0.00,16000.00,'2026-06-03 12:40:31','2026-06-03 12:40:31',0,NULL,0.00,0.00),(5,1,91,NULL,'2026-06-02 00:00:00',5,NULL,NULL,0.00,16000.00,'2026-06-03 12:41:12','2026-06-03 12:41:12',0,NULL,0.00,0.00),(6,1,6,NULL,'2026-06-03 00:00:00',6,NULL,NULL,0.00,29400.00,'2026-06-03 22:26:25','2026-06-03 22:26:25',0,NULL,0.00,0.00),(7,1,77,NULL,'2026-06-03 00:00:00',7,NULL,NULL,0.00,24400.00,'2026-06-04 05:58:18','2026-06-04 05:58:18',0,NULL,0.00,0.00),(8,1,55,NULL,'2026-06-03 00:00:00',8,NULL,NULL,0.00,28560.00,'2026-06-04 06:01:03','2026-06-04 06:01:03',0,NULL,0.00,0.00),(9,1,77,NULL,'2026-06-03 00:00:00',9,NULL,NULL,0.00,10400.00,'2026-06-04 06:02:30','2026-06-04 06:02:30',0,NULL,0.00,0.00),(10,1,12,NULL,'2026-06-03 00:00:00',10,NULL,NULL,0.00,63360.00,'2026-06-04 06:05:53','2026-06-04 06:05:53',0,NULL,0.00,0.00),(11,1,202,NULL,'2026-06-04 00:00:00',11,NULL,NULL,0.00,360000.00,'2026-06-04 19:55:11','2026-06-04 19:55:11',0,NULL,0.00,0.00),(12,1,17,NULL,'2026-06-05 00:00:00',12,NULL,NULL,0.00,408000.00,'2026-06-05 19:46:20','2026-06-05 19:46:20',0,NULL,0.00,0.00),(13,1,74,NULL,'2026-06-05 00:00:00',13,NULL,NULL,0.00,24400.00,'2026-06-05 22:21:50','2026-06-05 22:21:50',0,NULL,0.00,0.00),(14,1,72,NULL,'2026-06-05 00:00:00',14,NULL,NULL,0.00,31200.00,'2026-06-05 22:30:36','2026-06-05 22:30:36',0,NULL,0.00,0.00),(15,1,34,NULL,'2026-06-08 00:00:00',15,NULL,NULL,0.00,23000.00,'2026-06-08 22:36:54','2026-06-08 22:36:54',0,NULL,0.00,0.00),(16,1,211,NULL,'2026-06-09 00:00:00',16,NULL,NULL,0.00,24400.00,'2026-06-09 17:21:36','2026-06-09 17:21:36',0,NULL,0.00,0.00),(17,1,32,NULL,'2026-06-09 00:00:00',17,NULL,NULL,0.00,5000.00,'2026-06-09 17:44:17','2026-06-09 17:44:17',0,NULL,0.00,0.00),(18,1,218,NULL,'2026-06-09 00:00:00',18,NULL,NULL,0.00,90000.00,'2026-06-09 17:54:39','2026-06-09 17:54:39',0,NULL,0.00,0.00),(19,1,75,NULL,'2026-06-12 00:00:00',19,NULL,NULL,0.00,24400.00,'2026-06-12 16:38:26','2026-06-12 16:38:26',0,NULL,0.00,0.00),(20,1,41,NULL,'2026-06-15 00:00:00',20,NULL,NULL,0.00,85680.00,'2026-06-15 12:55:05','2026-06-15 12:55:05',0,NULL,0.00,0.00),(21,1,5,NULL,'2026-06-15 00:00:00',21,NULL,NULL,0.00,85680.00,'2026-06-15 21:06:47','2026-06-15 21:06:47',0,NULL,0.00,0.00),(22,1,34,NULL,'2026-06-15 00:00:00',22,NULL,NULL,0.00,234200.00,'2026-06-15 21:16:38','2026-06-15 21:16:38',0,NULL,0.00,0.00),(23,1,239,NULL,'2026-06-16 00:00:00',23,NULL,NULL,0.00,21000.00,'2026-06-16 15:01:17','2026-06-16 15:01:17',0,NULL,0.00,0.00),(24,1,202,NULL,'2026-06-16 00:00:00',24,NULL,NULL,0.00,290000.00,'2026-06-16 16:12:30','2026-06-16 16:12:30',0,NULL,0.00,0.00),(25,1,36,NULL,'2026-06-18 00:00:00',25,NULL,NULL,0.00,147000.00,'2026-06-18 15:23:54','2026-06-18 15:23:54',0,NULL,0.00,0.00),(26,1,238,NULL,'2026-06-24 00:00:00',26,NULL,NULL,0.00,24400.00,'2026-06-24 11:13:09','2026-06-24 11:13:09',0,NULL,0.00,0.00),(27,1,244,NULL,'2026-06-26 00:00:00',27,NULL,NULL,0.00,650000.00,'2026-06-26 15:05:47','2026-06-26 15:05:47',0,NULL,0.00,0.00),(28,1,223,NULL,'2026-07-01 00:00:00',28,NULL,NULL,0.00,20500.00,'2026-07-01 15:06:55','2026-07-01 15:06:55',0,NULL,0.00,0.00),(29,1,101,NULL,'2026-07-01 00:00:00',29,NULL,NULL,0.00,28400.00,'2026-07-01 21:11:56','2026-07-01 21:11:56',0,NULL,0.00,0.00),(30,1,97,NULL,'2026-07-01 00:00:00',30,NULL,NULL,0.00,29400.00,'2026-07-01 21:14:01','2026-07-01 21:14:01',0,NULL,0.00,0.00),(31,1,4,NULL,'2026-07-01 00:00:00',31,NULL,NULL,0.00,25000.00,'2026-07-01 21:14:59','2026-07-01 21:14:59',0,NULL,0.00,0.00),(32,1,243,NULL,'2026-07-01 00:00:00',32,NULL,NULL,0.00,27000.00,'2026-07-01 21:16:45','2026-07-01 21:16:45',0,NULL,0.00,0.00),(33,1,88,NULL,'2026-07-01 00:00:00',33,NULL,NULL,0.00,24400.00,'2026-07-01 21:19:36','2026-07-01 21:19:36',0,NULL,0.00,0.00),(34,1,245,NULL,'2026-07-01 00:00:00',34,NULL,NULL,0.00,31300.00,'2026-07-01 21:23:38','2026-07-01 21:23:38',0,NULL,0.00,0.00),(35,1,87,NULL,'2026-07-01 00:00:00',35,NULL,NULL,0.00,8000.00,'2026-07-01 21:25:00','2026-07-01 21:25:00',0,NULL,0.00,0.00),(36,1,86,NULL,'2026-07-01 00:00:00',36,NULL,NULL,0.00,29400.00,'2026-07-01 21:25:56','2026-07-01 21:25:56',0,NULL,0.00,0.00),(37,1,85,NULL,'2026-07-01 00:00:00',37,NULL,NULL,0.00,24400.00,'2026-07-01 21:26:47','2026-07-01 21:26:47',0,NULL,0.00,0.00),(38,1,84,NULL,'2026-07-01 00:00:00',38,NULL,NULL,0.00,24400.00,'2026-07-01 21:27:56','2026-07-01 21:27:56',0,NULL,0.00,0.00),(39,1,81,NULL,'2026-07-01 00:00:00',39,NULL,NULL,0.00,24400.00,'2026-07-01 21:29:05','2026-07-01 21:29:05',0,NULL,0.00,0.00),(41,1,79,NULL,'2026-07-01 00:00:00',NULL,'IJH010726-H',NULL,0.00,24400.00,'2026-07-01 22:14:33','2026-07-01 22:14:33',0,NULL,0.00,0.00),(42,1,84,NULL,'2026-07-02 00:00:00',NULL,'IJH020726-H',NULL,0.00,24400.00,'2026-07-02 09:28:16','2026-07-02 09:28:16',0,NULL,0.00,0.00),(43,1,6,NULL,'2026-07-01 00:00:00',NULL,'IJH020726-H',NULL,0.00,29400.00,'2026-07-02 12:57:32','2026-07-02 12:57:32',0,NULL,0.00,0.00),(45,1,4,NULL,'2026-09-24 00:00:00',NULL,'IJH020726-H',NULL,0.00,25000.00,'2026-07-02 19:04:11','2026-07-02 19:04:11',1,NULL,0.00,0.00),(46,1,244,NULL,'2026-07-26 00:00:00',NULL,'IJH020726-H',NULL,0.00,42000.00,'2026-07-02 19:32:08','2026-07-02 19:32:08',0,NULL,0.00,0.00),(47,1,244,NULL,'2026-07-02 00:00:00',NULL,'IJH020726-H',NULL,0.00,15000.00,'2026-07-02 20:09:03','2026-07-02 20:09:03',0,'www.aalalbaitmasjid.com - SSL',0.00,0.00),(48,1,244,NULL,'2026-07-02 00:00:00',NULL,'IJH020726-H',NULL,0.00,35000.00,'2026-07-02 20:11:13','2026-07-02 20:11:13',0,'www.aalalbaitmasjid.com',0.00,0.00),(49,1,202,NULL,'2026-07-04 00:00:00',40,NULL,NULL,0.00,395000.00,'2026-07-04 11:24:51','2026-07-04 11:24:51',0,NULL,0.00,0.00),(50,1,202,NULL,'2026-07-04 00:00:00',41,NULL,NULL,0.00,300000.00,'2026-07-04 15:18:22','2026-07-04 15:18:22',0,NULL,0.00,0.00),(51,1,80,NULL,'2026-07-01 00:00:00',NULL,'IJH060726-H',NULL,0.00,24400.00,'2026-07-06 17:07:49','2026-07-06 17:07:49',0,'www.roshanint.com',0.00,0.00),(52,1,49,NULL,'2026-07-06 00:00:00',NULL,'IJH060726-H',NULL,0.00,5000.00,'2026-07-06 17:19:23','2026-07-06 17:19:23',0,'www.divolca.lk',0.00,0.00),(53,1,5,NULL,'2026-07-08 00:00:00',42,NULL,NULL,0.00,29400.00,'2026-07-08 20:04:54','2026-07-08 20:04:54',0,NULL,0.00,0.00),(54,1,247,NULL,'2026-07-12 00:00:00',NULL,'IJH120726-H',NULL,0.00,32500.00,'2026-07-12 23:03:45','2026-07-12 23:03:45',0,'www.sahrapoolcare.ae',0.00,0.00),(55,1,202,NULL,'2026-07-12 00:00:00',NULL,'IJH130726-H',NULL,0.00,81750.00,'2026-07-13 07:37:45','2026-07-13 07:37:45',0,'www.induwaracare.com',0.00,0.00),(56,1,160,NULL,'2026-07-13 00:00:00',NULL,'IJH130726-H',NULL,0.00,11700.00,'2026-07-13 07:42:02','2026-07-13 07:42:02',0,'www.nodaauto.com',0.00,0.00),(57,1,205,NULL,'2026-07-13 00:00:00',NULL,'IJH130726-H',NULL,0.00,10500.00,'2026-07-13 09:47:12','2026-07-13 09:47:12',0,'www.medicrest.lk - SSL',0.00,0.00),(58,1,214,NULL,'2026-06-01 00:00:00',NULL,'IJH130726-H',NULL,0.00,24400.00,'2026-07-13 13:04:59','2026-07-13 13:04:59',0,'www.jewelexchange.lk',0.00,0.00),(59,1,88,NULL,'2026-07-14 00:00:00',NULL,'IJH140726-H',NULL,0.00,50880.00,'2026-07-14 12:39:08','2026-07-14 12:39:08',0,'Zoho Workplace - Emails',0.00,0.00),(60,1,235,NULL,'2026-07-16 00:00:00',43,NULL,NULL,0.00,18900.00,'2026-07-16 16:00:27','2026-07-16 16:00:27',0,NULL,0.00,0.00),(61,1,202,NULL,'2026-05-24 00:00:00',NULL,NULL,NULL,0.00,212500.00,'2026-07-16 16:13:28','2026-07-16 16:13:41',0,NULL,0.00,0.00),(62,1,1,NULL,'2026-07-24 00:00:00',NULL,NULL,NULL,0.00,140000.00,'2026-07-24 12:38:49','2026-07-24 12:38:49',0,NULL,0.00,0.00),(63,1,1,NULL,'2026-07-25 00:00:00',46,NULL,NULL,0.00,150000.00,'2026-07-25 11:01:43','2026-07-25 11:01:43',0,NULL,0.00,0.00),(64,1,249,NULL,'2026-07-26 10:30:08',NULL,NULL,NULL,0.00,406500.00,'2026-07-26 10:30:08','2026-07-26 10:30:08',0,NULL,0.00,0.00),(65,1,249,NULL,'2026-07-26 10:38:26',NULL,NULL,NULL,0.00,421500.00,'2026-07-26 10:38:26','2026-07-26 10:38:26',0,NULL,421500.00,0.00),(66,1,249,NULL,'2026-07-26 10:42:08',NULL,NULL,NULL,0.00,250000.00,'2026-07-26 10:42:08','2026-07-26 10:42:08',0,NULL,300000.00,50000.00),(67,1,249,NULL,'2026-07-26 10:42:37',NULL,NULL,NULL,0.00,433500.00,'2026-07-26 10:42:37','2026-07-26 10:42:37',0,NULL,500000.00,66500.00),(68,1,249,NULL,'2026-07-26 21:09:25',NULL,NULL,NULL,0.00,1178500.00,'2026-07-26 21:09:25','2026-07-26 21:09:25',0,NULL,1180000.00,1500.00),(69,1,1,NULL,'2026-07-27 00:00:00',NULL,NULL,NULL,0.00,150000.00,'2026-07-27 10:02:27','2026-07-27 10:02:27',0,NULL,0.00,0.00),(70,1,2,NULL,'2026-07-27 00:00:00',NULL,NULL,NULL,0.00,400000.00,'2026-07-27 10:23:16','2026-07-27 10:23:16',0,NULL,0.00,0.00),(71,1,3,NULL,'2026-07-27 00:00:00',NULL,NULL,NULL,0.00,250000.00,'2026-07-27 10:24:05','2026-07-27 10:24:05',0,NULL,0.00,0.00);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_renewals`
--

LOCK TABLES `subscription_renewals` WRITE;
/*!40000 ALTER TABLE `subscription_renewals` DISABLE KEYS */;
INSERT INTO `subscription_renewals` VALUES (1,1,NULL,'2025-01-01','2026-01-19',24400.00,'Bank Transfer','2026-06-12 06:41:10'),(2,1,NULL,'2026-01-01','2026-06-12',24400.00,'Cash','2026-06-12 06:41:32'),(3,168,NULL,'2027-01-01','2026-01-12',24400.00,'Cash','2026-06-12 06:43:42'),(4,1,NULL,'2027-01-01','2026-01-01',24400.00,'Bank Transfer','2026-06-12 06:44:40'),(5,75,'2026-07-25','2027-07-25','2026-06-12',24400.00,'Bank Transfer','2026-06-12 16:47:58'),(6,239,'2026-07-23','2027-07-23','2026-06-16',21000.00,'Bank Transfer','2026-06-16 15:01:38'),(7,238,'2026-07-09','2027-07-09','2026-06-24',24400.00,'Bank Transfer','2026-06-24 11:13:54'),(8,81,'2026-08-12','2027-08-12','2026-07-06',24400.00,'Bank Transfer','2026-07-06 14:45:43'),(9,101,'2026-09-30','2027-09-30','2026-07-06',28400.00,'Bank Transfer','2026-07-06 15:17:28'),(10,97,'2026-09-17','2027-09-17','2026-07-06',29400.00,'Bank Transfer','2026-07-06 17:05:44'),(11,80,'2026-08-08','2027-08-08','2026-07-06',24400.00,'Bank Transfer','2026-07-06 17:08:26'),(12,49,'2027-04-29','2028-04-29','2026-07-06',5000.00,'Bank Transfer','2026-07-06 17:19:52'),(13,88,'2026-08-21','2027-08-21','2026-07-06',24400.00,'Bank Transfer','2026-07-06 18:13:58'),(14,243,'2026-08-21','2027-08-21','2026-07-07',27000.00,'Bank Transfer','2026-07-07 19:42:05'),(15,6,'2027-01-10','2028-01-10','2026-07-07',29400.00,'Bank Transfer','2026-07-07 19:43:12'),(16,214,'2026-07-17','2027-07-17','2026-07-13',24400.00,'Bank Transfer','2026-07-13 13:05:43'),(17,86,'2026-08-18','2027-08-18','2026-07-14',29400.00,'Bank Transfer','2026-07-14 12:52:04'),(18,88,'2027-08-21','2028-08-21','2026-07-17',50880.00,'Bank Transfer','2026-07-17 14:46:05'),(19,202,'2026-08-14','2027-08-14','2026-07-24',81750.00,'Bank Transfer','2026-07-24 12:39:34');
/*!40000 ALTER TABLE `subscription_renewals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_privileges`
--

DROP TABLE IF EXISTS `user_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tab`
--

LOCK TABLES `user_tab` WRITE;
/*!40000 ALTER TABLE `user_tab` DISABLE KEYS */;
INSERT INTO `user_tab` VALUES (1,1,'Admin','admin','f7c3bc1d808e04732adf679965ccc34ca7ae3441',1,'2019-02-15 15:02:39','2025-04-24 10:38:02',0,'2026-07-26 15:25:33',1,'',1),(2,1,'hari@itmartx.com','hari@itmartx.com','5032057075275f988d93cd118882af5c626c79c3',1,'2019-02-15 15:02:39','2025-05-01 07:35:50',0,'2025-05-01 14:35:50',1,'',1),(10,1,'Danu','danu','601f1889667efaebb33b8c12572835da3f027f78',1,'25/07/2026 11:12','',0,'2026-07-25 05:42:00',4,'',1);
/*!40000 ALTER TABLE `user_tab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (1,'000001','2026-07-26','ABC','ABC','','','','','','',NULL,'2026-07-26 10:23:41',NULL,'2026-07-26 10:23:41',0,NULL,NULL,NULL,'English',NULL,NULL,'LKR',NULL,'Due on Receipt',0,NULL,NULL);
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_payments`
--

DROP TABLE IF EXISTS `vendor_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_payments`
--

LOCK TABLES `vendor_payments` WRITE;
/*!40000 ALTER TABLE `vendor_payments` DISABLE KEYS */;
INSERT INTO `vendor_payments` VALUES (1,1,1,1,'2026-07-26',NULL,225000.00,0.00,'Cash',NULL,'','0000-00-00',NULL,'2026-07-26 10:24:47',NULL,NULL,0);
/*!40000 ALTER TABLE `vendor_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouse` (
  `WarehouseID` int NOT NULL AUTO_INCREMENT,
  `WarehouseName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ContactPerson` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
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

-- Dump completed on 2026-07-27 11:45:24
