<?php

Class Nucleus {

    public $database;
    private static $instance;

    private function __construct() {
        
		$dsn = 'mysql:host=' . Config::read('db.host') . ';dbname='    . Config::read('db.basename') . ';connect_timeout=15';
        $user = Config::read('db.user');            
        $password = Config::read('db.password');
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 

		try {
			$this->database = new PDO($dsn, $user, $password, $options);
		} catch (PDOException $Exception) {
			throw new DatabaseException($Exception->getMessage(),(int)$s->getCode());
		}

    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }
	
}

?>