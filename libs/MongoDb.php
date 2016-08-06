<?php
namespace Insta\libs;

use Insta\libs\BaseException;

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
            try {
                self::$instance = new \MongoDB\Client("mongodb://localhost:27017");
                //self::$instance = new MongoDB\Client('mongodb://'.self::$user.':'.self::$passw.'@localhost:27017/'.self::$database);
                self::$instance = self::$instance->{ self::$database };
            } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
                throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
            }
        }
        
        return self::$instance;
    }
    
    public static function insert($collection, $object)
    {
        if (is_array($object) || is_object($object)) {
            try {
                self::getInstance()->$collection->insertOne($object);
            } catch (\MongoDB\Exception\InvalidArgumentException $e) {
                 throw new BaseException("Wrong Argument was passed to insert(): ".$e->getMessage(), 0, $e);
            } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
                throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
            }
        } else {
            throw new BaseException("Wrong Argument was passed to insert():  $object");
        }
    }
    
    public static function find($collection, $params = array(), $sort = array())
    {
        try {
            if (is_array($params) || is_object($params)) {
                if (!empty($sort) && (is_array($params) || is_object($params))) {
                    $result = self::getInstance()->$collection->find($params, array('sort' => $sort));
                } else {
                    $result = self::getInstance()->$collection->find($params); 
                }               
            }
        } catch (\MongoDB\Exception\InvalidArgumentException $e) {
             throw new BaseException("Wrong Argument was passed to find() $params", 0, $e);
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
        
        return $result;
    }
}
