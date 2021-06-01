/* @chishiki 2021-02-01 */

ALTER TABLE `perihelion_Site` ADD COLUMN `siteUsesGoogleMaps` INT(1) NOT NULL AFTER `siteGoogleApiKey`;

SET @langTimeStamp := now();
INSERT INTO perihelion_Lang VALUES ('siteUsesGoogleMaps', 'Uses Google Maps', 0, 'Googleマップ使用', 0, @langTimeStamp);
INSERT INTO perihelion_Lang VALUES ('siteGoogleUpdateSuccessful', 'Site Google settings successfully updated.', 0, 'Google設定は更新済みです。', 0, @langTimeStamp);
INSERT INTO perihelion_Lang VALUES ('siteUsesDataTables', 'Site uses DataTables.', 0, 'DataTables使用', 0, @langTimeStamp);

ALTER TABLE `perihelion_Site` ADD COLUMN `siteUsesDataTables` INT(1) NOT NULL AFTER `siteDefaultTimeZone`;

/* @chishiki 2021-02-05 */

ALTER TABLE `perihelion_Site`
    ADD COLUMN `defaultLatitude` DECIMAL(8,6) NOT NULL AFTER `siteUsesGoogleMaps`,
    ADD COLUMN `defaultLongitude` DECIMAL(9,6) NOT NULL AFTER `defaultLatitude`;

ALTER TABLE `perihelion_Site`
    ADD COLUMN `siteUsesLocationPicker` INT(1) NOT NULL AFTER `siteUsesGoogleMaps`;

SET @langTimeStamp := now();
INSERT INTO perihelion_Lang VALUES ('siteUsesLocationPicker', 'Uses LocationPicker', 0, 'LocationPicker使用', 0, @langTimeStamp);

ALTER TABLE `perihelion_Address`
    ADD COLUMN `latitude` DECIMAL(8,6) NOT NULL AFTER `addressDefault`,
    ADD COLUMN `longitude` DECIMAL(9,6) NOT NULL AFTER `latitude`;

/* @chishiki 2021-02-10 */

ALTER TABLE perihelion_AccountRecovery ENGINE = InnoDB;
ALTER TABLE perihelion_Audit ENGINE = InnoDB;
ALTER TABLE perihelion_BlacklistIP ENGINE = InnoDB;
ALTER TABLE perihelion_BlacklistWord ENGINE = InnoDB;
ALTER TABLE perihelion_Carousel ENGINE = InnoDB;
ALTER TABLE perihelion_Contact ENGINE = InnoDB;
ALTER TABLE perihelion_ContactForm ENGINE = InnoDB;
ALTER TABLE perihelion_Content ENGINE = InnoDB;
ALTER TABLE perihelion_Contract ENGINE = InnoDB;
ALTER TABLE perihelion_ContractSignature ENGINE = InnoDB;
ALTER TABLE perihelion_File ENGINE = InnoDB;
ALTER TABLE perihelion_Font ENGINE = InnoDB;
ALTER TABLE perihelion_Image ENGINE = InnoDB;
ALTER TABLE perihelion_Lang ENGINE = InnoDB;
ALTER TABLE perihelion_LanguageException ENGINE = InnoDB;
ALTER TABLE perihelion_Link ENGINE = InnoDB;
ALTER TABLE perihelion_Mail ENGINE = InnoDB;
ALTER TABLE perihelion_Menu ENGINE = InnoDB;
ALTER TABLE perihelion_MenuItem ENGINE = InnoDB;
ALTER TABLE perihelion_NewsletterSubscription ENGINE = InnoDB;
ALTER TABLE perihelion_Note ENGINE = InnoDB;
ALTER TABLE perihelion_SEO ENGINE = InnoDB;
ALTER TABLE perihelion_Script ENGINE = InnoDB;
ALTER TABLE perihelion_Session ENGINE = InnoDB;
ALTER TABLE perihelion_Site ENGINE = InnoDB;
ALTER TABLE perihelion_Theme ENGINE = InnoDB;
ALTER TABLE perihelion_Tile ENGINE = InnoDB;
ALTER TABLE perihelion_User ENGINE = InnoDB;
ALTER TABLE perihelion_UserRole ENGINE = InnoDB;
ALTER TABLE perihelion_Video ENGINE = InnoDB;

/* @chishiki 2021-02-19 */

SET @langTimeStamp := now();
INSERT INTO perihelion_Lang VALUES ('youMustEnableJavaScript', 'You must enable JavaScript to use this site.', 0, 'JavaScriptを有効に設定してからご利用ください。', 0, @langTimeStamp);

/* @chishiki 2021-05-18 */

SET @langTimeStamp := now();
INSERT INTO perihelion_Lang VALUES ('carouselSettings', 'Carousel Settings', 0, 'カルーセル設定', 0, @langTimeStamp);
INSERT INTO perihelion_Lang VALUES ('carouselPanelManager', 'Panel Manager', 0, 'パネル管理', 0, @langTimeStamp);

/* @chishiki 2021-06-01 */

ALTER TABLE `perihelion_Content`
    CHANGE COLUMN `contentID` `contentID` INT(12) NOT NULL ,
    CHANGE COLUMN `siteID` `siteID` INT(12) NOT NULL ,
    CHANGE COLUMN `entryCategoryID` `contentCategoryID` INT(12) NOT NULL ,
    ADD COLUMN `creator` INT(12) NOT NULL AFTER `siteID`,
    ADD COLUMN `created` DATETIME NOT NULL AFTER `creator`,
    ADD COLUMN `updated` DATETIME NOT NULL AFTER `created`,
    ADD COLUMN `deleted` INT(1) NOT NULL AFTER `updated`;

CREATE TABLE `perihelion_ContentCategory` (
    `contentCategoryID` int(12) NOT NULL AUTO_INCREMENT,
    `siteID` int(12) NOT NULL,
    `creator` int(12) NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NOT NULL,
    `deleted` int(1) NOT NULL,
    `contentCategoryParentID` int(12) NOT NULL,
    `contentCategoryURL` varchar(100) NOT NULL,
    `contentCategoryEnglish` varchar(100) NOT NULL,
    `contentCategoryJapanese` varchar(100) NOT NULL,
    PRIMARY KEY (`contentCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
