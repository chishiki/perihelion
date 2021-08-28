SET @now = now();
SET @siteID = 1; -- whatever number you use here needs to go in instance.config.php siteID
SET @userID = 1; -- admin
SET @domainKey = 'perihelion'; -- this is typically the domain name on single instance installs or the subdomain when powering multiple instances
SET @domainName = 'perihelion.xyz';
SET @adminEmail = 'support@perihelion.xyz';

SET @siteTitleEnglish = 'Perihelion';
SET @siteKeywordsEnglish = 'Perihelion, Content Management System, CMS, Prototype Framework';
SET @siteTitleJapanese = 'ペリヘリオン';
SET @siteKeywordsJapanese = 'ペリヘリオン, コンテントマネージメント, CMS, プロトタイプフレームワーク';

INSERT INTO `perihelion_Carousel` VALUES
(1,@siteID,'Default Carousel Title','Default Carousel Subtitle','例のタイトル','例のサブタイトル',@userID,@now,'index',0,1,1,1,1,1,1);

INSERT INTO `perihelion_Content` VALUES
(
    1, -- contentID
    @siteID, -- siteID
    @userID, -- creator
    @now, -- created
    @now, -- updated
    0, -- deleted
    '', -- contentURL
    'home', -- entrySeoURL
    0, -- contentCategoryID
    'page', -- contentCategoryType
    0, -- entryPublished
    '1973-12-18', -- entryPublishStartDate
    '2073-12-18', -- entryPublishEndDate
    'Index', -- entryTitleEnglish
    'インデックス', -- entryTitleJapanese
    'Perihelion is a content management system for building dynamic responsive websites and a prototype and systems development framework that enables the rapid development and deployment of highly customizable modular functionality.', -- entryContentEnglish
    'ペリヘリオンは動的なレスポンシブWebサイトを構築するためのコンテントマネージメントシステムであり、高度にカスタマイズ可能なモジュール機能の迅速な開発と展開を可能にするプロトタイプおよびシステム開発フレームワークです。', -- entryContentJapanese
    0, -- entryViews
    'Index', -- contentMetaKeywordsEnglish
    'インデックス', -- contentMetaKeywordsJapanese
    'Index', -- contentMetaDescriptionEnglish
    'インデックス', -- contentMetaDescriptionJapanese
    0, -- contentLock
    '{"id":null,"container":"container-fluid","row":"row","col":"col-12 col-md-8 offset-md-2"}' -- contentClasses
);

INSERT INTO `perihelion_Menu` VALUES
(1,@siteID,'navbar',@userID,@now,1,'navbar');

INSERT INTO `perihelion_MenuItem` VALUES
(1,@siteID,1,0,@userID,@now,'/','Home','ホーム',1,10,1,1,0,''),
(2,@siteID,1,0,@userID,@now,'test-1','Test 1','テスト １',1,20,1,1,0,''),
(3,@siteID,1,0,@userID,@now,'test-2','Test 2','テスト ２',1,30,1,1,0,''),
(4,@siteID,1,3,@userID,@now,'test-2-a','Test 2A','テスト ２Ａ',1,10,1,1,0,''),
(5,@siteID,1,3,@userID,@now,'test-2-b','Test 2B','テスト ２Ｂ',1,20,1,1,0,''),
(6,@siteID,1,0,@userID,@now,'test-3','Test 3','テスト ３',1,40,1,1,0,''),
(7,@siteID,1,0,@userID,@now,'auth-only','Auth Only','認証済みのみ',1,50,1,0,0,''),
(8,@siteID,1,0,@userID,@now,'anon-only','Anon Only','未認証のみ',1,60,0,1,0,'');

INSERT INTO `perihelion_Site` VALUES
(
    @siteID, -- siteID
    @domainKey, -- siteKey
    @domainName, -- siteURL
    @userID, -- siteManagerUserID
    1, -- siteIndexable
    @siteTitleEnglish, -- siteTitleEnglish
    @siteKeywordsEnglish, -- siteKeywordsEnglish
    '', -- siteDescriptionEnglish
    @siteTitleJapanese, -- siteTitleJapanese
    @siteKeywordsJapanese, -- siteKeywordsJapanese
    '', -- siteDescriptionJapanese
    '', -- siteGoogleAnalyticsID
    '', -- siteGoogleAdSenseID
    '', -- siteGoogleApiKey
    0, -- siteUsesGoogleMaps
	0, -- siteUsesLocationPicker
    0, -- defaultLatitude
    0, -- defaultLongitude
    '', -- siteTheme
    1, -- themeID
    'perihelionkk', -- siteTwitter
    'perihelionkk', -- siteFacebook
    'perihelionkk', -- siteLinkedIn
    '', -- sitePinterest
    'perihelion_kk', -- siteInstagram
    '', -- siteSkype
    '', -- siteAutomatedEmailAddress
    '', -- siteAutomatedEmailSenderName
    '', -- siteContactFormToAddress
    1, -- siteNavMenuID
    1, -- siteIndexContentID
    0, -- siteHeaderContentID
    0, -- siteFooterContentID
    'ja', -- siteIso639
    'JPY', -- siteIso4217
    @now, -- siteDeploymentDate
    0, -- sitePagesServed
    1, -- siteLangJapanese
    0, -- siteIsDevInstance
    0.0000, -- taxRate
    '', -- pingdomStatus
    '', -- pingdomReport
    '', -- siteDefaultTimeZone
    0 -- siteUsesDataTables
);

INSERT INTO `perihelion_Theme` VALUES
(1,@siteID,@now,@userID,'perihelion','/*\r\nsite-specific CSS can be placed below\r\ngenerally speaking it will override Bootstrap and Perihelion CSS\r\n*/','#000000','#ffffff');

INSERT INTO `perihelion_User` VALUES (@userID,@now,@now,0,'0000-00-00 00:00:00','admin','Perihelion Admin',@adminEmail,0,0,'$2y$10$88BtCGM4/l2Ny8MY1OrfOewxuOZUtOF5U5//bbCQz6MYSwPd24xqO',@siteID,@now,@now,0,1);

INSERT INTO `perihelion_UserRole` (`siteID`, `userID`, `userRole`, `lastVisit`) VALUES (@siteID, @userID, 'siteManager', @now);
