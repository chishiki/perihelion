<?php

Class Nucleus {

    public $database;
    private static $instance;
    private static $exception;

    private function __construct() {

    	self::$exception = null;

		$dsn = 'mysql:host=' . Config::read('db.host') . ';dbname='    . Config::read('db.basename') . ';connect_timeout=15';
        $user = Config::read('db.user');            
        $password = Config::read('db.password');
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

		try {
			$this->database = new PDO($dsn, $user, $password, $options);
			$this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $Exception) {
			self::$exception = $Exception->getMessage();
			print_r(self::$exception);
		}

    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    public static function getException() {
        return self::$exception;
    }
	
}

?>