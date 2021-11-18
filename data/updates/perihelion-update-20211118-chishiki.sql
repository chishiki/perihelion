
ALTER TABLE `perihelion_Note`
DROP COLUMN `displayToOwner`,
DROP COLUMN `noteSubmissionDateTime`,
DROP COLUMN `noteSubmittedByUserID`,
ADD COLUMN `creator` INT NOT NULL AFTER `siteID`,
ADD COLUMN `created` DATETIME NOT NULL AFTER `creator`,
ADD COLUMN `updated` DATETIME NOT NULL AFTER `created`,
ADD COLUMN `deleted` INT NOT NULL AFTER `updated`;
