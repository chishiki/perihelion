
ALTER TABLE `perihelion_Content`
ADD COLUMN `includeOnSitemap` INT NOT NULL AFTER `contentClasses`,
ADD COLUMN `authenticatedUsersOnly` INT NOT NULL AFTER `includeOnSitemap`;

UPDATE `perihelion_Content` SET `includeOnSitemap` = 1 WHERE `contentID` > 0;
