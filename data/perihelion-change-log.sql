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


