
ALTER TABLE `perihelion_Person`
DROP COLUMN `personObjectID`,
DROP COLUMN `personObject`;

CREATE TABLE `perihelion_PersonMap` (
  `personMapID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` int NOT NULL,
  `personID` int NOT NULL,
  `personObject` varchar(50) NOT NULL,
  `personObjectID` int NOT NULL,
  PRIMARY KEY (`personMapID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
