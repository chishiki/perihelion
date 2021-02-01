/* @chishiki 2021-02-01 */

ALTER TABLE `perihelion_Site` ADD COLUMN `siteUsesGoogleMaps` INT(1) NOT NULL AFTER `siteGoogleApiKey`;

SET @langTimeStamp := now();
INSERT INTO perihelion_Lang VALUES ('siteUsesGoogleMaps', 'Uses Google Maps', 0, 'Googleマップ使用', 0, @langTimeStamp);
INSERT INTO perihelion_Lang VALUES ('siteGoogleUpdateSuccessful', 'Site Google settings successfully updated.', 0, 'Google設定は更新済みです。', 0, @langTimeStamp);
INSERT INTO perihelion_Lang VALUES ('siteUsesDataTables', 'Site uses DataTables.', 0, 'DataTables使用', 0, @langTimeStamp);

ALTER TABLE `perihelion_Site` ADD COLUMN `siteUsesDataTables` INT(1) NOT NULL AFTER `siteDefaultTimeZone`;


