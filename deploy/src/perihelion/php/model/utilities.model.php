<?php

final class DateUtilities {
	
	public static function dateTimeIsValid($datetime) {
		$dt = DateTime::createFromFormat("Y-m-d", $datetime);
		return $dt !== false && !array_sum($dt->getLastErrors());
	}

	function timezones() {
		
		static $regions = array(
			DateTimeZone::AFRICA,
			DateTimeZone::AMERICA,
			DateTimeZone::ANTARCTICA,
			DateTimeZone::ASIA,
			DateTimeZone::ATLANTIC,
			DateTimeZone::AUSTRALIA,
			DateTimeZone::EUROPE,
			DateTimeZone::INDIAN,
			DateTimeZone::PACIFIC,
		);

		$timezones = array();
		foreach($regions as $region) { $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region)); }

		$timezone_offsets = array();
		foreach( $timezones as $timezone ) {
			$tz = new DateTimeZone($timezone);
			$timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
		}

		ksort($timezone_offsets); // sort timezone by timezone name

		$timezone_list = array();
		foreach($timezone_offsets as $timezone => $offset) {
			$offset_prefix = $offset < 0 ? '-' : '+';
			$offset_formatted = gmdate('H:i',abs($offset));
			$pretty_offset = "UTC${offset_prefix}${offset_formatted}";
			$t = new DateTimeZone($timezone);
			$c = new DateTime(null, $t);
			$current_time = $c->format('g:i A');
			$timezone_list[$timezone] = "(${pretty_offset}) $timezone - $current_time";
		}

		return $timezone_list;
		
	}

	public static function localize($datetime, $format = null) : string {

		$dt = new DateTime($datetime);
		if (!is_null($format)) { return $dt->format($format); }
		if ($_SESSION['lang'] == 'ja') { return $dt->format('Y年n月j日'); }
		return $datetime;

	}

}

final class ModuleUtilities {

	public static function moduleToClassName($moduleName, $className) {

		$moduleNameArray = explode('-', $moduleName);
		$moduleNameArrayMap = array_map('ucfirst', $moduleNameArray);
		$moduleCamelCaseName = implode('', $moduleNameArrayMap);
		return ucfirst($moduleCamelCaseName) . $className;

	}

}

final class Utilities {

	public static function generateKey() {
		return md5(uniqid(rand(),true));
	}

	public static function generateNatto () {
		$natto = '';
		for ($i = 0; $i < 3; $i++) { $natto .= chr(rand(35, 126)); }
		return $natto;
	}

	public static function generateUniqueKey () {
		$uniqueKey = '';
		for ($i = 0; $i < 10; $i++) { $uniqueKey .= chr(rand(97, 122)); }
		return $uniqueKey;
	}
	
	public static function generateMash() {
		$natto = self::generateNatto();
		$accountRecoveryMash = md5(time() . $natto);
		return $accountRecoveryMash;
	}
	
	public static function isValidMd5($md5) {
		return preg_match('/^[a-f0-9]{32}$/', $md5);
	}

	public static function truncate($string, $limit, $break = '.', $pad = '...') {
		$truncatedString = $string;
		if(strlen($string) > $limit) {
			if(false !== ($breakpoint = strpos($string, $break, $limit))) {
				if($breakpoint < strlen($string) - 1){
					$truncatedString = substr($string, 0, $breakpoint) . $pad;
				}
			}
		}
		return $truncatedString;
	}
	
	public static function agileTruncate($string, $length, $stopanywhere = false) {
		
		//truncates a string to a certain char length, stopping on a word if not specified otherwise.
		
		if (strlen($string) > $length) {
			
			//limit hit!
			$string = substr($string,0,($length -3));
			
			if ($stopanywhere) {
				
				//stop anywhere
				$string .= '...';
				
			} else {
				
				//stop on a word.
				$string = substr($string,0,strrpos($string,' ')).'...';
				
			}
		}
		
		return $string;
		
	}
	
	public static function remove_urls($string) {
		$pattern = '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si';
		$replace = '';
		return preg_replace($pattern, $replace, $string);
	}
	
	public static function remove_linebreaks($string) {
		
		$newString = preg_replace( '/\r|\n/', ' ', $string);
		return $newString;
	}

	public static function feedificate($string) {
		$string = strip_tags($string);
		$string = self::remove_urls($string);
		$string = self::remove_linebreaks($string);
		$string = self::truncate($string, 100, $break = ' ');
		$string = htmlspecialchars($string);
		return $string;
	}

	public static function googlify($string) {
		$url = ereg_replace("[-]+", "-", ereg_replace("[^a-z0-9-]", "", strtolower( str_replace(" ", "-", $string) ) ) );
		return $url;
	}
	
	public static function isValidEmail($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) { return true; } else { return false; }
	}
	
	public static function isLettersNumbersHyphensOnly($string) {
		if (preg_match('/^[a-zA-Z0-9_-]+$/', $string)) { return true; } else { return false; }
	}
	
	function ordinal($number) {
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13))
			return $number. 'th';
		else
			return $number. $ends[$number % 10];
	}

	public static function subval_sort($a,$subkey,$order='asc') {
		foreach($a as $k=>$v) { $b[$k] = strtolower($v[$subkey]); }
		if ($order=='desc') { arsort($b); } else { asort($b); }
		foreach($b as $key=>$val) { $c[$key] = $a[$key]; }
		return $c;
	}
	
	public static function objectToArray ($object) {
		if(!is_object($object) && !is_array($object)) { return $object; }
		return array_map(array('Utilities','objectToArray'), (array) $object);
	}

	public static function squareMetersToTsubo($m) {
		return $m/3.305785;
	}
	
	public static function nearestFourth($decimal, $round = 'down') {
		
		$x = $decimal * 4;
		$y = floor($x);
		if ($round == 'up') { $y = ceil($x); }
		return $x/4;
		
	}
	
	public static function isValidLimitClause($limit) {
		
		$isValid = false;
		if (preg_match('/^[\d]+(, [\d]+)?$/', $limit)) { $isValid = true; }
		return $isValid;

	}

}

final class StringUtilities {

	public static function camelToUnderscore($input) {
		$pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
		preg_match_all($pattern, $input, $matches);
		$ret = $matches[0];
		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
		}
		return implode('_', $ret);
	}

	public static function camelToHyphen($input) {
		$pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
		preg_match_all($pattern, $input, $matches);
		$ret = $matches[0];
		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
		}
		return implode('-', $ret);
	}

	public static function hyphensToCamel($string, $capitalizeFirstCharacter = true) {
		$str = str_replace('-', '', ucwords($string, '-'));
		if (!$capitalizeFirstCharacter) { $str = lcfirst($str); }
		return $str;
	}

}

final class UUID {

	public static function getUUID() {

		$data = random_bytes(16);
		assert(strlen($data) == 16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

	}

}

?>