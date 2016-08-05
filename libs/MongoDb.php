<?php
namespace Insta\libs;

class MongoDb
{
    //private static $user = 'instaAdmin';
    //private static $passw = 'secretpwd';
    private static $database = 'insta';
    private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new \MongoDB\Client("mongodb://localhost:27017");
            //self::$instance = new MongoDB\Client('mongodb://'.self::$user.':'.self::$passw.'@localhost:27017/'.self::$database);
            self::$instance = self::$instance->{ self::$database };
        }
        
        return self::$instance;
    }
    
}
