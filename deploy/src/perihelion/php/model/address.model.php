<?php

/*

CREATE TABLE `perihelion_Address` (
  `addressID` INT(12) NOT NULL AUTO_INCREMENT,
  `siteID` INT(12) NOT NULL,
  `creator` INT(12) NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `deleted` INT(1) NOT NULL,
  `streetAddress1` VARCHAR(100) NOT NULL,
  `streetAddress2` VARCHAR(100) NOT NULL,
  `city` VARCHAR(50) NOT NULL,
  `state` VARCHAR(50) NOT NULL,
  `country` VARCHAR(2) NOT NULL, -- iso3166
  `postalCode` VARCHAR(20) NOT NULL,
  `addressObject` VARCHAR(25) NOT NULL,
  `addressObjectID` INT(12) NOT NULL,
  `addressDefault` INT(1) NOT NULL,
  PRIMARY KEY (`addressID`)
);

*/

class Address extends ORM {

	public $addressID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $streetAddress1;
	public $streetAddress2;
	public $city;
	public $state;
	public $country;
	public $postalCode;
	public $addressObject;
	public $addressObjectID;
	public $addressDefault;
	public $latitude;
	public $longitude;

	public function __construct($addressID = null) {

		$this->addressID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = date('Y-m-d H:i:s');
		$this->updated = date('Y-m-d H:i:s');
		$this->deleted = 0;
		$this->streetAddress1 = '';
		$this->streetAddress2 = '';
		$this->city = '';
		$this->state = '';
		$this->country = '';
		$this->postalCode = '';
		$this->addressObject = '';
		$this->addressObjectID = 0;
		$this->addressDefault = 0;
		$this->latitude = 0;
		$this->longitude = 0;

		if ($addressID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Address WHERE addressID = :addressID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':addressID' => $addressID));
			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } }
			}

		}
		
	}

	public function stringAddress($lang = null) {

		if (is_null($lang)) {  $lang = $_SESSION['lang']; }

		$addy = '';

		if ($lang == 'ja') {
			$addy = $this->state . $this->city . $this->streetAddress1 . $this->streetAddress2 . ' 〒' . $this->postalCode;
		} else {
			$addyBits = array();
			if ($this->streetAddress1) { $addyBits[] = $this->streetAddress1 . ($this->streetAddress2?' '.$this->streetAddress2:''); }
			if ($this->city) { $addyBits[] = $this->city; }
			if ($this->state) { $addyBits[] = $this->state; }
			if ($this->country) { $addyBits[] = $this->country . ($this->postalCode?' '.$this->postalCode:''); }
			if (!empty($addyBits)) { $addy = implode(', ', $addyBits); }
		}

		return $addy;

	}
		
}

class Addresses {
	
	private $addresses;
	
	public function __construct($addressObject, $addressObjectID) {

		$query = "SELECT addressID FROM perihelion_Address ";
		$query .= "WHERE siteID = :siteID AND addressObject = :addressObject AND addressObjectID = :addressObjectID AND deleted = 0 ";
		$query .= "ORDER BY created ASC";
	
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':addressObject' => $addressObject, ':addressObjectID' => $addressObjectID));
		
		$this->addresses = array();
		while ($row = $statement->fetch()) { $this->addresses[] = $row['addressID']; }
	
	}
	
	public function list() {
		
		return $this->addresses;
		
	}
	
}

class AddressDefault {
	
	private $address;
	
	public function __construct($addressObject, $addressObjectID, $idOnly = false) {

		$query = "SELECT " . ($idOnly?"addressID":"*") . " FROM perihelion_Address ";
		$query .= "WHERE siteID = :siteID AND addressObject = :addressObject ";
		$query .= "AND addressObjectID = :addressObjectID AND addressDefault = 1 AND deleted = 0 ";
		$query .= "LIMIT 1";
	
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':addressObject' => $addressObject, ':addressObjectID' => $addressObjectID));
		
		if ($idOnly) { $this->address = 0; } else { $this->address = array(); }
		if ($row = $statement->fetch()) { 
			if ($idOnly) { $this->address = $row['addressID']; } else { $this->address = $row; }
		}
	
	}
	
	public function address() {
		
		return $this->address;
		
	}
	
}

class Countries {

	private $english = array(
		'AF' => 'Afghanistan',
		'AX' => 'Åland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'The Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BQ' => 'Bonaire, Sint Eustatius and Saba',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'The British Indian Ocean Territory',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'CV' => 'Cabo Verde',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'KY' => 'Cayman Islands (the)',
		'CF' => 'The Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'The Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros (the)',
		'CD' => 'The Democratic Republic of the Congo',
		'CG' => 'The Congo',
		'CK' => 'The Cook Islands',
		'CR' => 'Costa Rica',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CW' => 'Curaçao',
		'CY' => 'Cyprus',
		'CZ' => 'Czechia',
		'CI' => 'Côte d\'Ivoire',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'The Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'SZ' => 'Eswatini',
		'ET' => 'Ethiopia',
		'FK' => 'The Falkland Islands',
		'FO' => 'The Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'The French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia (the)',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island and McDonald Islands',
		'VA' => 'Holy See (the)',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran (Islamic Republic of)',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'Korea (the Democratic People\'s Republic of)',
		'KR' => 'Korea (the Republic of)',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Lao People\'s Democratic Republic (the)',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands (the)',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia (Federated States of)',
		'MD' => 'Moldova (the Republic of)',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands (the)',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger (the)',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands (the)',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'State of Palestine',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines (the)',
		'PN' => 'Pitcairn',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'MK' => 'Republic of North Macedonia',
		'RO' => 'Romania',
		'RU' => 'Russian Federation (the)',
		'RW' => 'Rwanda',
		'RE' => 'Réunion',
		'BL' => 'Saint Barthélemy',
		'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin (French part)',
		'PM' => 'Saint Pierre and Miquelon',
		'VC' => 'Saint Vincent and the Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome and Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SX' => 'Sint Maarten (Dutch part)',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'SS' => 'South Sudan',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan (the)',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard and Jan Mayen',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syrian Arab Republic',
		'TW' => 'Taiwan (Province of China)',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'The Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'UM' => 'United States Minor Outlying Islands',
		'US' => 'United States of America',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Viet Nam',
		'VG' => 'Virgin Islands (British)',
		'VI' => 'Virgin Islands (US)',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);

	private $japanese = array(
		'AF' => 'アフガニスタン',
		'AX' => 'オーランド諸島',
		'AL' => 'アルバニア',
		'DZ' => 'アルジェリア',
		'AS' => 'アメリカ領サモア',
		'AD' => 'アンドラ',
		'AO' => 'アンゴラ',
		'AI' => 'アンギラ',
		'AQ' => '南極',
		'AG' => 'アンチグアバーブーダ',
		'AR' => 'アルゼンチン',
		'AM' => 'アルメニア',
		'AW' => 'アルバ',
		'AU' => 'オーストラリア',
		'AT' => 'オーストリア',
		'AZ' => 'アゼルバイジャン',
		'BS' => 'バハマ',
		'BH' => 'バーレーン',
		'BD' => 'バングラデシュ',
		'BB' => 'バルバドス',
		'BY' => 'ベラルーシ',
		'BE' => 'ベルギー',
		'BZ' => 'ベリーズ',
		'BJ' => 'ベニン',
		'BM' => 'バミューダ',
		'BT' => 'ブータン',
		'BO' => 'ボリビア',
		'BQ' => 'ボネール、シントユースタティウスおよびサバ',
		'BA' => 'ボスニア・ヘルツェゴビナ',
		'BW' => 'ボツワナ',
		'BV' => 'ブーベ島',
		'BR' => 'ブラジル',
		'IO' => 'イギリス領インド洋地域',
		'BN' => 'ブルネイダルサラーム',
		'BG' => 'ブルガリア',
		'BF' => 'ブルキナファソ',
		'BI' => 'ブルンディ',
		'CV' => 'カーボベルデ',
		'KH' => 'カンボジア',
		'CM' => 'カメルーン',
		'CA' => 'カナダ',
		'KY' => 'ケイマン諸島',
		'CF' => '中央アフリカ共和国',
		'TD' => 'チャド',
		'CL' => 'チリ',
		'CN' => '中国',
		'CX' => 'クリスマス島',
		'CC' => 'ココス（キーリング）諸島',
		'CO' => 'コロンビア',
		'KM' => 'コモロ',
		'CD' => 'コンゴ民主共和国',
		'CG' => 'コンゴ',
		'CK' => 'クック諸島',
		'CR' => 'コスタリカ',
		'HR' => 'クロアチア',
		'CU' => 'キューバ',
		'CW' => 'キュラソー',
		'CY' => 'キプロス',
		'CZ' => 'チェコ',
		'CI' => 'コートジボワール',
		'DK' => 'デンマーク',
		'DJ' => 'ジブチ',
		'DM' => 'ドミニカ国',
		'DO' => 'ドミニカ共和国',
		'EC' => 'エクアドル',
		'EG' => 'エジプト',
		'SV' => 'エルサルバドル',
		'GQ' => '赤道ギニア',
		'ER' => 'エリトリア',
		'EE' => 'エストニア',
		'SZ' => 'エスワティニ',
		'ET' => 'エチオピア',
		'FK' => 'フォークランド諸島',
		'FO' => 'フェロー諸島',
		'FJ' => 'フィジー',
		'FI' => 'フィンランド',
		'FR' => 'フランス',
		'GF' => 'フランス領ギアナ',
		'PF' => 'フランス領ポリネシア',
		'TF' => 'フランス領極南諸島',
		'GA' => 'ガボン',
		'GM' => 'ガンビア',
		'GE' => 'ジョージア',
		'DE' => 'ドイツ',
		'GH' => 'ガーナ',
		'GI' => 'ジブラルタル',
		'GR' => 'ギリシャ',
		'GL' => 'グリーンランド',
		'GD' => 'グレナダ',
		'GP' => 'グアドループ',
		'GU' => 'グアム',
		'GT' => 'グアテマラ',
		'GG' => 'ガーンジー',
		'GN' => 'ギニア',
		'GW' => 'ギニアビサウ',
		'GY' => 'ガイアナ',
		'HT' => 'ハイチ',
		'HM' => 'ハード島とマクドナルド諸島',
		'VA' => 'バチカン市国',
		'HN' => 'ホンジュラス',
		'HK' => '香港',
		'HU' => 'ハンガリー',
		'IS' => 'アイスランド',
		'IN' => 'インド',
		'ID' => 'インドネシア',
		'IR' => 'イラン（イスラム共和国）',
		'IQ' => 'イラク',
		'IE' => 'アイルランド',
		'IM' => 'マン島',
		'IL' => 'イスラエル',
		'IT' => 'イタリア',
		'JM' => 'ジャマイカ',
		'JP' => '日本',
		'JE' => 'ジャージー',
		'JO' => 'ヨルダン',
		'KZ' => 'カザフスタン',
		'KE' => 'ケニア',
		'KI' => 'キリバティ',
		'KP' => '北朝鮮',
		'KR' => '韓国',
		'KW' => 'クウェート',
		'KG' => 'キルギスタン',
		'LA' => 'ラオス人民民主共和国',
		'LV' => 'ラトビア',
		'LB' => 'レバノン',
		'LS' => 'レソト',
		'LR' => 'リベリア',
		'LY' => 'リビア',
		'LI' => 'リヒテンシュタイン',
		'LT' => 'リトアニア',
		'LU' => 'ルクセンブルク',
		'MO' => 'マカオ',
		'MG' => 'マダガスカル',
		'MW' => 'マラウィ',
		'MY' => 'マレーシア',
		'MV' => 'モルディブ',
		'ML' => 'マリ',
		'MT' => 'マルタ',
		'MH' => 'マーシャル諸島',
		'MQ' => 'マルティニーク',
		'MR' => 'モーリタニア',
		'MU' => 'モーリシャス',
		'YT' => 'マヨット',
		'MX' => 'メキシコ',
		'FM' => 'ミクロネシア（連邦）',
		'MD' => 'モルドバ（共和国）',
		'MC' => 'モナコ',
		'MN' => 'モンゴル',
		'ME' => 'モンテネグロ',
		'MS' => 'モントセラト',
		'MA' => 'モロッコ',
		'MZ' => 'モザンビーク',
		'MM' => 'ミャンマー',
		'NA' => 'ナミビア',
		'NR' => 'ナウル',
		'NP' => 'ネパール',
		'NL' => 'オランダ',
		'NC' => 'ニューカレドニア',
		'NZ' => 'ニュージーランド',
		'NI' => 'ニカラグア',
		'NE' => 'ニジェール',
		'NG' => 'ナイジェリア',
		'NU' => 'ニウエ',
		'NF' => 'ノーフォーク島',
		'MP' => '北マリアナ諸島',
		'NO' => 'ノルウェー',
		'OM' => 'オマーン',
		'PK' => 'パキスタン',
		'PW' => 'パラオ',
		'PS' => 'パレスチナ州',
		'PA' => 'パナマ',
		'PG' => 'パプアニューギニア',
		'PY' => 'パラグアイ',
		'PE' => 'ペルー',
		'PH' => 'フィリピン',
		'PN' => 'ピトケアン',
		'PL' => 'ポーランド',
		'PT' => 'ポルトガル',
		'PR' => 'プエルトリコ',
		'QA' => 'カタール',
		'MK' => '北マケドニア共和国',
		'RO' => 'ルーマニア',
		'RU' => 'ロシア連邦',
		'RW' => 'ルワンダ',
		'RE' => 'レユニオン',
		'BL' => 'サンバルテルミー',
		'SH' => 'セントヘレナ、アセンションおよびトリスタンダクーニャ',
		'KN' => 'セントクリストファー・ネイビス',
		'LC' => 'セントルシア',
		'MF' => 'サンマルタン（フランス語部分）',
		'PM' => 'サンピエールとミクロン',
		'VC' => 'セントビンセントおよびグレナディーン諸島',
		'WS' => 'サモア',
		'SM' => 'サンマリノ',
		'ST' => 'サントメ・プリンシペ',
		'SA' => 'サウジアラビア',
		'SN' => 'セネガル',
		'RS' => 'セルビア',
		'SC' => 'セイシェル',
		'SL' => 'シエラレオネ',
		'SG' => 'シンガポール',
		'SX' => 'シントマールテン（オランダパート）',
		'SK' => 'スロバキア',
		'SI' => 'スロベニア',
		'SB' => 'ソロモン諸島',
		'SO' => 'ソマリア',
		'ZA' => '南アフリカ',
		'GS' => 'サウスジョージアおよびサウスサンドイッチ諸島',
		'SS' => '南スーダン',
		'ES' => 'スペイン',
		'LK' => 'スリランカ',
		'SD' => 'スーダン',
		'SR' => 'スリナム',
		'SJ' => 'スバールバル諸島およびヤンマイエン島',
		'SE' => 'スウェーデン',
		'CH' => 'スイス',
		'SY' => 'シリアアラブ共和国',
		'TW' => '台湾（中国）',
		'TJ' => 'タジキスタン',
		'TZ' => 'タンザニア',
		'TH' => 'タイ',
		'TL' => '東ティモール',
		'TG' => 'トーゴ',
		'TK' => 'トケラウ',
		'TO' => 'トンガ',
		'TT' => 'トリニダードトバゴ',
		'TN' => 'チュニジア',
		'TR' => 'トルコ',
		'TM' => 'トルクメニスタン',
		'TC' => 'タークスカイコス諸島',
		'TV' => 'ツバル',
		'UG' => 'ウガンダ',
		'UA' => 'ウクライナ',
		'AE' => 'アラブ首長国連邦',
		'GB' => 'イギリス',
		'UM' => 'アメリカ合衆国小離島',
		'US' => 'アメリカ合衆国',
		'UY' => 'ウルグアイ',
		'UZ' => 'ウズベキスタン',
		'VU' => 'バヌアツ',
		'VE' => 'ベネズエラ',
		'VN' => 'ベトナム',
		'VG' => 'バージン諸島（イギリス）',
		'VI' => 'バージン諸島（米国）',
		'WF' => 'ワリスとフツナ',
		'EH' => '西サハラ',
		'YE' => 'イエメン',
		'ZM' => 'ザンビア',
		'ZW' => 'ジンバブエ'
	);

	public function list($lang = 'en') {
		if ($lang == 'ja') { return $this->japanese; } else { return $this->english; }
	}
	
	public function getCountry($iso3166, $lang = 'en') {
		
		$country = '';
		if ($lang == 'ja' && isset($this->japanese[$iso3166])) { $country = $this->japanese[$iso3166]; }
		elseif (isset($this->english[$iso3166])) { $country = $this->english[$iso3166]; }
		return $country;
		
	}
	
}

?>