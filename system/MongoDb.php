<?php
namespace Insta\system;

use Insta\system\BaseException;

class MongoDb
{
    //private static $user = 'instaAdmin';
    //private static $passw = 'secretpwd';
    private $connection;
    private static $database = 'insta';
    private static $instance = NULL;

    private function __construct() {
        try {
            $this->connection = new \MongoDB\Client("mongodb://localhost:27017");
            $this->connection = $this->connection->{ self::$database }; 
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
    }

    private function __clone() {}

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new MongoDb();
        }
        
        return self::$instance;
    }
    
    public function insert($collection, $object)
    {
        if (is_array($object) || is_object($object)) {
            try {
                $this->connection->$collection->insertOne($object);
            } catch (\MongoDB\Exception\InvalidArgumentException $e) {
                 throw new BaseException("Wrong Argument was passed to insert(): " . $e->getMessage(), 0, $e);
            } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
                throw new BaseException("Connection with database missed: " .$e->getMessage(), 0, $e);
            }
        } else {
            throw new BaseException("Wrong Argument was passed to insert():  " . json_encode($object));
        }
    }
    
    public function find($collection, $params = array(), $sort = array())
    {
        try {
            if (is_array($params) || is_object($params)) {
                if (!empty($sort) && (is_array($params) || is_object($params))) {
                    $result = $this->connection->$collection->find($params, array('sort' => $sort));
                } else {
                    $result = $this->connection->$collection->find($params); 
                }               
            }
        } catch (\MongoDB\Exception\InvalidArgumentException $e) {
             throw new BaseException("Wrong Argument was passed to find() : " .json_encode($params), 0, $e);
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
        
        return $result;
    }
    
    public function updateOne($collection, $params, $update)
    {
        try {
            if ((is_array($params) || is_object($params)) && (is_array($update) || is_object($update))) {
                $result = $this->connection->$collection->updateOne($params, array('$set' => $update));                             
            }
        } catch (\MongoDB\Exception\InvalidArgumentException $e) {
             throw new BaseException("Wrong Argument was passed to updateOne(): filter: "
                                     . json_encode($params) .
                                     " update: ". json_encode($update) .'\n\r'. $e->getMessage(), 0, $e);
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
        
        return $result;
        
    }
    
    public function findOne($collection, $params = array())
    {
        try {
            if (is_array($params) || is_object($params)) {
                $result = $this->connection->$collection->findOne($params);
            } else {
                $result = null;
            }               
        } catch (\MongoDB\Exception\InvalidArgumentException $e) {
             throw new BaseException("Wrong Argument was passed to find() : ".json_encode($params), 0, $e);
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
        
        return $result;
    }
    
    public function aggregate($collection, $pipeline)
    {
        try {
           $result = $this->connection->$collection->aggregate($pipeline)->toArray();
        } catch (\MongoDB\Exception\InvalidArgumentException $e) {
             throw new BaseException("Wrong Argument was passed to aggregate() : ".json_encode($pipeline).
                                     ". Original message: ". $e->getMessage(), 0, $e);
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
        
        return $result;
    }
    
    public function count($collection, $params = array())
    {
        try {
           $result = $this->connection->$collection->count($params);
        } catch (\MongoDB\Exception\InvalidArgumentException $e) {
             throw new BaseException("Wrong Argument was passed to count() : ".json_encode($params).
                                     ". Original message: ". $e->getMessage(), 0, $e);
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
        
        return $result;
    }
}
