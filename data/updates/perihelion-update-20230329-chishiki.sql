ALTER TABLE `perihelion_Person`
ADD COLUMN `personAcceptsTelephoneCalls` INT NOT NULL AFTER `personFax`,
ADD COLUMN `languagePreference` VARCHAR(2) NULL AFTER `personActive`;