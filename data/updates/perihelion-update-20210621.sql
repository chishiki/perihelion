
ALTER TABLE `perihelion_Address`
    ADD COLUMN `latitude` DECIMAL(8,6) NOT NULL AFTER `addressDefault`,
    ADD COLUMN `longitude` DECIMAL(9,6) NOT NULL AFTER `latitude`;

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

UPDATE perihelion_Content SET creator = 1, created = @now, updated = @now, deleted = 0 WHERE contentID > 0;

ALTER TABLE `perihelion_Image`
    ADD COLUMN `creator` INT(12) NOT NULL AFTER `siteID`,
    ADD COLUMN `created` DATETIME NOT NULL AFTER `creator`,
    ADD COLUMN `updated` DATETIME NOT NULL AFTER `created`,
    ADD COLUMN `deleted` INT(1) NOT NULL AFTER `updated`;

UPDATE perihelion_Image SET creator = imageSubmittedByUserID, created = imageSubmissionDateTime, updated = imageSubmissionDateTime WHERE imageID > 0;

ALTER TABLE `perihelion_File`
    ADD COLUMN `creator` INT(12) NOT NULL AFTER `siteID`,
    ADD COLUMN `created` DATETIME NOT NULL AFTER `creator`,
    ADD COLUMN `updated` DATETIME NOT NULL AFTER `created`,
    ADD COLUMN `deleted` INT(1) NOT NULL AFTER `updated`;

UPDATE perihelion_File SET creator = fileSubmittedByUserID, created = fileSubmissionDateTime, updated = fileSubmissionDateTime WHERE fileID > 0;

ALTER TABLE `perihelion_Content` CHANGE COLUMN `contentID` `contentID` INT(12) NOT NULL AUTO_INCREMENT;
ALTER TABLE `perihelion_Content` AUTO_INCREMENT = 1001;

ALTER TABLE `perihelion_Content`
    DROP COLUMN `contentDeletedByUserID`,
    DROP COLUMN `contentDeletedDate`,
    DROP COLUMN `contentDeleted`,
    DROP COLUMN `entryDescriptionMeta`,
    DROP COLUMN `entryKeywordMeta`,
    DROP COLUMN `pageID`,
    DROP COLUMN `entrySortOrder`,
    DROP COLUMN `entryLastModified`,
    DROP COLUMN `entrySubmissionDateTime`,
    DROP COLUMN `entrySubmittedByUserID`,
    DROP COLUMN `entrySiteID`,
    CHANGE COLUMN `contentCategoryID` `contentCategoryID` INT(12) NOT NULL AFTER `entrySeoURL`,
    CHANGE COLUMN `entryPublished` `entryPublished` INT(1) NOT NULL AFTER `contentCategoryType`,
    CHANGE COLUMN `contentCategoryKey` `contentCategoryType` VARCHAR(20) NOT NULL;
