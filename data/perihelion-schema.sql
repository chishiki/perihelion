-- MySQL dump 10.13  Distrib 5.7.23, for osx10.14 (x86_64)
--
-- Host: mysql.perihelion.xyz    Database: perihelionDB
-- ------------------------------------------------------
-- Server version	5.6.39-log

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
-- Table structure for table `perihelion_AccountRecovery`
--

DROP TABLE IF EXISTS `perihelion_AccountRecovery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_AccountRecovery` (
  `accountRecoveryID` int(8) NOT NULL AUTO_INCREMENT,
  `accountRecoveryEmail` varchar(255) NOT NULL,
  `accountRecoveryUserID` int(12) NOT NULL,
  `accountRecoveryRequestDateTime` datetime NOT NULL,
  `accountRecoveryRequestedFromIP` varchar(50) NOT NULL,
  `accountRecoveryMash` varchar(40) NOT NULL,
  `accountRecoveryVisited` int(1) NOT NULL,
  PRIMARY KEY (`accountRecoveryID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Audit`
--

DROP TABLE IF EXISTS `perihelion_Audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Audit` (
  `auditID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `auditDateTime` datetime NOT NULL,
  `auditUserID` int(12) NOT NULL,
  `auditIP` varchar(45) NOT NULL,
  `auditAction` varchar(255) NOT NULL,
  `auditObject` varchar(30) NOT NULL,
  `auditObjectID` int(12) NOT NULL,
  `auditProperty` varchar(255) NOT NULL,
  `auditValue` varchar(255) NOT NULL,
  `auditResult` varchar(255) NOT NULL,
  `auditNote` text NOT NULL,
  PRIMARY KEY (`auditID`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_BlacklistDomain`
--

DROP TABLE IF EXISTS `perihelion_BlacklistDomain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_BlacklistDomain` (
  `domain` varchar(100) CHARACTER SET utf8 NOT NULL,
  `siteID` int(8) DEFAULT NULL,
  `blockedByUserID` int(12) NOT NULL,
  `dateTimeBlocked` datetime NOT NULL,
  `dateTimeOfBlockExpiration` datetime NOT NULL,
  `attemptsSinceBlocked` int(12) NOT NULL,
  PRIMARY KEY (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_BlacklistIP`
--

DROP TABLE IF EXISTS `perihelion_BlacklistIP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_BlacklistIP` (
  `ip` varchar(50) CHARACTER SET utf8 NOT NULL,
  `siteID` int(8) DEFAULT NULL,
  `blockedByUserID` int(12) NOT NULL,
  `dateTimeBlocked` datetime NOT NULL,
  `dateTimeOfBlockExpiration` datetime NOT NULL,
  `attemptsSinceBlocked` int(12) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_BlacklistWord`
--

DROP TABLE IF EXISTS `perihelion_BlacklistWord`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_BlacklistWord` (
  `word` varchar(100) CHARACTER SET utf8 NOT NULL,
  `siteID` int(8) DEFAULT NULL,
  `blockedByUserID` int(12) NOT NULL,
  `dateTimeBlocked` datetime NOT NULL,
  `timesBlocked` int(12) NOT NULL,
  PRIMARY KEY (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Carousel`
--

DROP TABLE IF EXISTS `perihelion_Carousel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Carousel` (
  `carouselID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `carouselTitleEnglish` varchar(255) NOT NULL,
  `carouselSubtitleEnglish` varchar(255) NOT NULL,
  `carouselTitleJapanese` varchar(255) NOT NULL,
  `carouselSubtitleJapanese` varchar(255) NOT NULL,
  `carouselCreatedByUserID` int(12) NOT NULL,
  `carouselCreationDateTime` datetime NOT NULL,
  `carouselObject` varchar(255) NOT NULL,
  `carouselObjectID` int(8) NOT NULL,
  `carouselPublished` int(1) NOT NULL,
  `carouselDisplayXs` int(1) NOT NULL,
  `carouselDisplaySm` int(1) NOT NULL,
  `carouselDisplayMd` int(1) NOT NULL,
  `carouselDisplayLg` int(1) NOT NULL,
  `carouselDisplayCaption` int(1) NOT NULL,
  PRIMARY KEY (`carouselID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_CarouselPanel`
--

DROP TABLE IF EXISTS `perihelion_CarouselPanel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_CarouselPanel` (
  `carouselPanelID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(12) NOT NULL,
  `carouselID` int(12) NOT NULL,
  `imageID` int(12) NOT NULL,
  `submittedByUserID` int(12) NOT NULL,
  `submissionDateTime` datetime NOT NULL,
  `carouselPanelAltEnglish` varchar(255) NOT NULL,
  `carouselPanelTitleEnglish` varchar(255) NOT NULL,
  `carouselPanelSubtitleEnglish` varchar(255) NOT NULL,
  `carouselPanelAltJapanese` varchar(255) NOT NULL,
  `carouselPanelTitleJapanese` varchar(255) NOT NULL,
  `carouselPanelSubtitleJapanese` varchar(255) NOT NULL,
  `carouselPanelPublished` int(1) NOT NULL,
  `carouselPanelDisplayOrder` int(4) NOT NULL,
  `carouselPanelUrlEnglish` varchar(255) NOT NULL,
  `carouselPanelUrlJapanese` varchar(255) NOT NULL,
  PRIMARY KEY (`carouselPanelID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Contact`
--

DROP TABLE IF EXISTS `perihelion_Contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Contact` (
  `contactID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `contactUserID` int(12) NOT NULL,
  `contactDateTime` datetime NOT NULL,
  `contactIP` varchar(40) NOT NULL,
  `contactName` varchar(255) NOT NULL,
  `contactEmail` varchar(255) NOT NULL,
  `contactContent` text NOT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_ContactForm`
--

DROP TABLE IF EXISTS `perihelion_ContactForm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_ContactForm` (
  `contactFormID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(12) NOT NULL,
  `companyNameEnglish` varchar(255) NOT NULL,
  `companyAddressEnglish` varchar(255) NOT NULL,
  `companyTelephoneEnglish` varchar(50) NOT NULL,
  `companyFaxEnglish` varchar(50) NOT NULL,
  `companyNameJapanese` varchar(255) NOT NULL,
  `companyAddressJapanese` varchar(255) NOT NULL,
  `companyTelephoneJapanese` varchar(50) NOT NULL,
  `companyFaxJapanese` varchar(50) NOT NULL,
  `displayLocationMap` tinyint(1) NOT NULL DEFAULT '0',
  `locationMapZoom` int(2) NOT NULL DEFAULT '0',
  `locationMapLatitude` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `locationMapLongitude` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `displayOfficeHours` tinyint(1) NOT NULL DEFAULT '0',
  `officeHoursEnglish` varchar(255) NOT NULL,
  `officeHoursJapanese` varchar(255) NOT NULL,
  `promptForPhoneNumber` tinyint(1) NOT NULL DEFAULT '0',
  `promptForPreferredCorrespondence` tinyint(1) NOT NULL DEFAULT '0',
  `promptForContactReason` tinyint(1) NOT NULL DEFAULT '0',
  `promptForAreaOfInterest` tinyint(1) NOT NULL DEFAULT '0',
  `promptForBudget` tinyint(1) NOT NULL DEFAULT '0',
  `budgetCurrencyPrefix` varchar(20) NOT NULL,
  PRIMARY KEY (`contactFormID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Content`
--

DROP TABLE IF EXISTS `perihelion_Content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Content` (
  `contentID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `entrySiteID` int(8) NOT NULL,
  `contentURL` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entrySeoURL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentCategoryKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entryCategoryID` int(8) NOT NULL,
  `entrySubmittedByUserID` int(12) NOT NULL,
  `entrySubmissionDateTime` datetime NOT NULL,
  `entryPublishStartDate` date NOT NULL,
  `entryPublishEndDate` date NOT NULL,
  `entryLastModified` datetime NOT NULL,
  `entryTitleEnglish` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entryTitleJapanese` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entryContentEnglish` text COLLATE utf8_unicode_ci NOT NULL,
  `entryContentJapanese` text COLLATE utf8_unicode_ci NOT NULL,
  `entrySortOrder` int(8) NOT NULL,
  `pageID` int(8) NOT NULL,
  `entryPublished` int(1) NOT NULL,
  `entryViews` int(12) NOT NULL,
  `entryKeywordMeta` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entryDescriptionMeta` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentMetaKeywordsEnglish` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentMetaKeywordsJapanese` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentMetaDescriptionEnglish` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentMetaDescriptionJapanese` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentDeleted` int(1) NOT NULL,
  `contentDeletedDate` datetime NOT NULL,
  `contentDeletedByUserID` int(12) NOT NULL,
  `contentLock` int(1) NOT NULL,
  PRIMARY KEY (`contentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Contract`
--

DROP TABLE IF EXISTS `perihelion_Contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Contract` (
  `contractID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `contractCreatedByUserID` int(12) NOT NULL,
  `contractCreationDateTime` datetime NOT NULL,
  `contractTitleEnglish` varchar(255) NOT NULL,
  `contractTitleJapanese` varchar(255) NOT NULL,
  `contractTitleJapaneseReading` varchar(255) NOT NULL,
  `contractContentEnglish` text NOT NULL,
  `contractContentJapanese` text NOT NULL,
  PRIMARY KEY (`contractID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_ContractSignature`
--

DROP TABLE IF EXISTS `perihelion_ContractSignature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_ContractSignature` (
  `contractID` int(8) NOT NULL,
  `objectClass` varchar(50) NOT NULL,
  `objectID` int(8) NOT NULL,
  `signatoryUserID` int(12) NOT NULL,
  `signatureDateTime` datetime NOT NULL,
  `signatureIPAddress` varchar(39) NOT NULL,
  `signatureNameSigned` varchar(255) NOT NULL,
  PRIMARY KEY (`contractID`,`signatoryUserID`,`objectID`,`objectClass`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Currency`
--

DROP TABLE IF EXISTS `perihelion_Currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Currency` (
  `iso4217` varchar(3) DEFAULT NULL,
  `currencyEnglish` int(11) DEFAULT NULL,
  `currencyJapanese` int(11) DEFAULT NULL,
  `currencyDecimalPlaces` int(11) DEFAULT NULL,
  `currencySeparator` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_File`
--

DROP TABLE IF EXISTS `perihelion_File`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_File` (
  `fileID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `fileSubmittedByUserID` int(12) NOT NULL,
  `fileSubmissionDateTime` datetime NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `s3url` varchar(100) NOT NULL,
  `fileName` varchar(50) NOT NULL,
  `fileOriginalName` varchar(50) NOT NULL,
  `fileType` varchar(30) NOT NULL,
  `fileSize` int(11) NOT NULL,
  `fileObject` varchar(20) NOT NULL,
  `fileObjectID` int(8) NOT NULL,
  `fileTitleEnglish` varchar(255) NOT NULL,
  `fileTitleJapanese` varchar(255) NOT NULL,
  `fileNotes` text NOT NULL,
  PRIMARY KEY (`fileID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Font`
--

DROP TABLE IF EXISTS `perihelion_Font`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Font` (
  `fontKey` varchar(20) NOT NULL,
  `fontCss` varchar(255) NOT NULL,
  PRIMARY KEY (`fontKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Image`
--

DROP TABLE IF EXISTS `perihelion_Image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Image` (
  `imageID` int(12) NOT NULL AUTO_INCREMENT,
  `imageDisplayOrder` int(4) NOT NULL,
  `siteID` int(8) NOT NULL,
  `imageSubmittedByUserID` int(12) NOT NULL,
  `imageSubmissionDateTime` datetime NOT NULL,
  `imagePath` varchar(255) NOT NULL,
  `s3url` varchar(100) NOT NULL,
  `imageObject` varchar(20) NOT NULL,
  `imageObjectID` int(8) NOT NULL,
  `imageDisplayClassification` varchar(20) NOT NULL,
  `imageOriginalName` varchar(50) NOT NULL,
  `imageType` varchar(30) NOT NULL,
  `imageSize` int(11) NOT NULL,
  `imageDimensionX` int(5) NOT NULL,
  `imageDimensionY` int(5) NOT NULL,
  `imageDisplayInGallery` int(1) NOT NULL,
  PRIMARY KEY (`imageID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Lang`
--

DROP TABLE IF EXISTS `perihelion_Lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Lang` (
  `langKey` varchar(255) NOT NULL,
  `enLang` varchar(255) NOT NULL,
  `enCount` int(12) NOT NULL,
  `jaLang` varchar(255) NOT NULL,
  `jaCount` int(12) NOT NULL,
  `langTimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`langKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_LanguageException`
--

DROP TABLE IF EXISTS `perihelion_LanguageException`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_LanguageException` (
  `langKey` varchar(255) NOT NULL,
  `siteID` int(12) NOT NULL,
  `enLangException` varchar(255) NOT NULL,
  `jaLangException` varchar(255) NOT NULL,
  PRIMARY KEY (`langKey`,`siteID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Link`
--

DROP TABLE IF EXISTS `perihelion_Link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Link` (
  `linkID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(12) NOT NULL,
  `linkCreatedByUserID` int(12) NOT NULL,
  `linkCreationDateTime` datetime NOT NULL,
  `linkUrlEnglish` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `linkUrlJapanese` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `linkAnchorTextEnglish` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `linkAnchorTextJapanese` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `linkObject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `linkObjectID` int(12) NOT NULL,
  `linkPublished` int(1) NOT NULL,
  `linkDisplayOrder` int(8) NOT NULL,
  `linkClickCount` int(12) NOT NULL,
  `linkShorty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `linkPortalPrimary` int(1) NOT NULL,
  PRIMARY KEY (`linkID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Mail`
--

DROP TABLE IF EXISTS `perihelion_Mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Mail` (
  `mailID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `mailSentByUserID` int(12) NOT NULL,
  `mailSentDateTime` datetime NOT NULL,
  `mailToAddress` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mailFromAddress` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mailSubject` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mailMessage` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`mailID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Menu`
--

DROP TABLE IF EXISTS `perihelion_Menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Menu` (
  `menuID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) DEFAULT NULL,
  `menuName` varchar(255) DEFAULT NULL,
  `menuAddedByUserID` int(12) DEFAULT NULL,
  `menuAdditionDateTime` datetime DEFAULT NULL,
  `menuPublished` int(1) DEFAULT NULL,
  `menuLayoutLocation` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`menuID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_MenuItem`
--

DROP TABLE IF EXISTS `perihelion_MenuItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_MenuItem` (
  `menuItemID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) DEFAULT NULL,
  `menuID` int(8) DEFAULT NULL,
  `menuItemParentID` int(8) DEFAULT NULL,
  `menuItemAddedByUserID` int(12) DEFAULT NULL,
  `menuItemAdditionDateTime` datetime DEFAULT NULL,
  `menuItemURL` varchar(255) DEFAULT NULL,
  `menuItemAnchorTextEnglish` varchar(255) DEFAULT NULL,
  `menuItemAnchorTextJapanese` varchar(255) NOT NULL,
  `menuItemPublished` int(1) DEFAULT NULL,
  `menuItemOrder` int(4) DEFAULT NULL,
  `menuItemDisplayAuth` int(1) DEFAULT NULL,
  `menuItemDisplayAnon` int(1) DEFAULT NULL,
  PRIMARY KEY (`menuItemID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_NewsletterSubscription`
--

DROP TABLE IF EXISTS `perihelion_NewsletterSubscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_NewsletterSubscription` (
  `siteID` int(12) NOT NULL,
  `newsletterID` int(12) NOT NULL,
  `subscriberEmail` varchar(255) NOT NULL,
  `subscribedDateTime` datetime NOT NULL,
  `subscribedFromIP` varchar(40) NOT NULL,
  `subscriberName` varchar(255) NOT NULL,
  `subscriberVerified` int(1) NOT NULL,
  PRIMARY KEY (`siteID`,`newsletterID`,`subscriberEmail`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Note`
--

DROP TABLE IF EXISTS `perihelion_Note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Note` (
  `noteID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `noteSubmittedByUserID` int(12) NOT NULL,
  `noteSubmissionDateTime` datetime NOT NULL,
  `noteObject` varchar(20) NOT NULL,
  `noteObjectID` int(8) NOT NULL,
  `noteContent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `displayToOwner` tinyint(1) NOT NULL,
  PRIMARY KEY (`noteID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_SEO`
--

DROP TABLE IF EXISTS `perihelion_SEO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_SEO` (
  `seoID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `seoURL` varchar(100) NOT NULL,
  `systemURL` varchar(255) NOT NULL,
  `seoSetByUserID` int(12) NOT NULL,
  `seoSetDateTime` datetime NOT NULL,
  `seoUrlArray0` varchar(100) NOT NULL,
  `seoUrlArray1` varchar(100) NOT NULL,
  `seoUrlArray2` varchar(100) NOT NULL,
  `seoUrlArray3` varchar(100) NOT NULL,
  `seoUrlArray4` varchar(100) NOT NULL,
  `seoUrlArray5` varchar(100) NOT NULL,
  `seoTitleEnglish` varchar(255) NOT NULL,
  `seoDescriptionEnglish` varchar(255) NOT NULL,
  `seoKeywordsEnglish` varchar(255) NOT NULL,
  `seoTitleJapanese` varchar(255) NOT NULL,
  `seoDescriptionJapanese` varchar(255) NOT NULL,
  `seoKeywordsJapanese` varchar(255) NOT NULL,
  `seoRobotsTxtIndex` int(1) NOT NULL,
  `seoRobotsTxtFollow` int(1) NOT NULL,
  `seoCanonicalUrl` varchar(255) NOT NULL,
  PRIMARY KEY (`seoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Script`
--

DROP TABLE IF EXISTS `perihelion_Script`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Script` (
  `scriptID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `scriptCreationDateTime` datetime NOT NULL,
  `scriptCreatorUserID` int(12) NOT NULL,
  `scriptName` varchar(30) NOT NULL,
  `scriptCode` mediumtext NOT NULL,
  `scriptPosition` varchar(6) NOT NULL,
  `scriptOrder` int(3) NOT NULL,
  `scriptEnabled` int(1) NOT NULL,
  PRIMARY KEY (`scriptID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Session`
--

DROP TABLE IF EXISTS `perihelion_Session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Session` (
  `sessionID` varchar(32) CHARACTER SET utf8 NOT NULL,
  `userID` int(12) NOT NULL,
  `sessionDateTimeSet` datetime NOT NULL,
  `sessionDateTimeExpire` datetime NOT NULL,
  `sessionIP` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sessionUserAgent` varchar(255) CHARACTER SET utf8 NOT NULL,
  `sessionData` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sessionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Site`
--

DROP TABLE IF EXISTS `perihelion_Site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Site` (
  `siteID` int(8) NOT NULL AUTO_INCREMENT,
  `siteKey` varchar(20) NOT NULL,
  `siteURL` varchar(50) NOT NULL,
  `siteManagerUserID` int(12) NOT NULL,
  `siteIndexable` int(1) NOT NULL,
  `siteTitleEnglish` varchar(255) NOT NULL,
  `siteKeywordsEnglish` varchar(255) NOT NULL,
  `siteDescriptionEnglish` varchar(255) NOT NULL,
  `siteTitleJapanese` varchar(255) NOT NULL,
  `siteKeywordsJapanese` varchar(255) NOT NULL,
  `siteDescriptionJapanese` varchar(255) NOT NULL,
  `siteGoogleAnalyticsID` varchar(20) NOT NULL,
  `siteGoogleAdSenseID` varchar(20) NOT NULL,
  `siteGoogleApiKey` varchar(40) NOT NULL,
  `siteTheme` varchar(30) NOT NULL,
  `themeID` int(12) NOT NULL,
  `siteTwitter` varchar(20) NOT NULL,
  `siteFacebook` varchar(255) NOT NULL,
  `siteLinkedIn` varchar(255) NOT NULL,
  `sitePinterest` varchar(255) NOT NULL,
  `siteInstagram` varchar(255) NOT NULL,
  `siteSkype` varchar(255) NOT NULL,
  `siteAutomatedEmailAddress` varchar(255) NOT NULL,
  `siteAutomatedEmailSenderName` varchar(30) NOT NULL,
  `siteContactFormToAddress` varchar(255) NOT NULL,
  `siteNavMenuID` int(8) NOT NULL,
  `siteIndexContentID` int(8) NOT NULL,
  `siteHeaderContentID` int(8) NOT NULL,
  `siteFooterContentID` int(8) NOT NULL,
  `siteIso639` varchar(5) NOT NULL,
  `siteIso4217` varchar(3) NOT NULL,
  `siteDeploymentDate` datetime NOT NULL,
  `sitePagesServed` int(12) NOT NULL,
  `siteLangJapanese` tinyint(1) NOT NULL,
  `siteIsDevInstance` tinyint(1) NOT NULL,
  `taxRate` decimal(6,4) NOT NULL,
  PRIMARY KEY (`siteID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Theme`
--

DROP TABLE IF EXISTS `perihelion_Theme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Theme` (
  `themeID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `themeCreationDateTime` datetime NOT NULL,
  `themeCreatorUserID` int(12) NOT NULL,
  `themeName` varchar(30) NOT NULL,
  `themeCss` mediumtext NOT NULL,
  `body_color` varchar(7) NOT NULL,
  `body_backgroundcolor` varchar(7) NOT NULL,
  PRIMARY KEY (`themeID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Tile`
--

DROP TABLE IF EXISTS `perihelion_Tile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Tile` (
  `tileID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `tileKey` varchar(20) NOT NULL,
  `tileKeyID` int(8) NOT NULL,
  `tilePublished` int(1) NOT NULL,
  `tileContainer` varchar(20) NOT NULL,
  `tileRow` int(6) NOT NULL,
  `tileColXS` int(2) NOT NULL,
  `tileColSM` int(2) NOT NULL,
  `tileColMD` int(2) NOT NULL,
  `tileColLG` int(2) NOT NULL,
  `tileColumnWidth` int(2) NOT NULL,
  `tileProminence` int(4) NOT NULL,
  `tileClass` varchar(100) NOT NULL,
  `tileAuthDisplay` varchar(10) NOT NULL,
  PRIMARY KEY (`tileID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_User`
--

DROP TABLE IF EXISTS `perihelion_User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_User` (
  `userID` int(12) NOT NULL AUTO_INCREMENT,
  `createdDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastUpdateDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deletionDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `username` varchar(100) NOT NULL DEFAULT '',
  `userDisplayName` varchar(100) NOT NULL DEFAULT '',
  `userEmail` varchar(100) NOT NULL DEFAULT '',
  `userEmailVerified` tinyint(1) NOT NULL DEFAULT '0',
  `userAcceptsEmail` tinyint(1) NOT NULL DEFAULT '0',
  `userPassword` varchar(255) NOT NULL DEFAULT '',
  `userRegistrationSiteID` int(12) NOT NULL,
  `userRegistrationDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userLastVisitDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userBlackList` tinyint(1) NOT NULL DEFAULT '0',
  `userActive` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_UserRole`
--

DROP TABLE IF EXISTS `perihelion_UserRole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_UserRole` (
  `siteID` int(11) NOT NULL,
  `userID` int(12) NOT NULL,
  `userRole` varchar(20) NOT NULL,
  `lastVisit` datetime NOT NULL,
  PRIMARY KEY (`siteID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perihelion_Video`
--

DROP TABLE IF EXISTS `perihelion_Video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perihelion_Video` (
  `videoID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `videoSubmittedByUserID` int(12) NOT NULL,
  `videoSubmissionDateTime` datetime NOT NULL,
  `videoURL` varchar(255) NOT NULL,
  `videoObject` varchar(20) NOT NULL,
  `videoObjectID` int(8) NOT NULL,
  `videoDisplay` int(1) NOT NULL,
  `videoDisplayOrder` int(4) NOT NULL,
  `videoAutoplay` int(1) NOT NULL,
  `videoControls` int(1) NOT NULL,
  `videoShowinfo` int(1) NOT NULL,
  `videoModestbranding` int(1) NOT NULL,
  `videoLoop` int(1) NOT NULL,
  PRIMARY KEY (`videoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-09  8:21:03
