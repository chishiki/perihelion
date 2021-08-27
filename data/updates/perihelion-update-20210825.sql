/* @siramsay 2021-08-25 */
SET @now := now();
REPLACE INTO `perihelion_Lang` VALUES
('select', 'Select', 0, '選択する', 0, @now),
('pleaseEnterValidCSS', 'Please Enter Valid CSS', 0, '有効なCSSを入力してください', 0, @now),
('validCSSOnly', 'valid CSS only', 0, '有効なCSSのみ', 0, @now),
('pleaseEnterThemeName', 'Please Enter a Theme Name', 0, 'テーマ名を入力してください', 0, @now);
