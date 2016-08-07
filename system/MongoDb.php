<?php
namespace Insta\system;

use Insta\system\BaseException;

/**
 * Decorator class is used to access Php Mongodb Driver via \MongoDB 
 */
class MongoDb
{
    /**
     * @var \MongoDB\Client class. For more info visit @link http://php.net/mongodb
     */
    private $connection;
    
    /**
     * @var string default database name
     */
    private static $database = 'insta';
    
    /**
     * Keeps Singletone instance of MongoDb class.
     * @var MongoDb
     */
    private static $instance = NULL;

    /**
     * Defined private to support Singletone realisation
     */
    private function __construct() {
        try {
            $this->connection = new \MongoDB\Client("mongodb://localhost:27017");
            $this->connection = $this->connection->{ self::$database }; 
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            throw new BaseException("Connection with database missed: ".$e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Defined private to support Singletone realisation
     */
    private function __clone() {}
    
    /**
     * Defined private to support Singletone realisation
     */
    private function __wakeup() {}
    
    /**
     * @return MongoDb instance
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new MongoDb();
        }
        
        return self::$instance;
    }
    
    /**
     * Insert one entity
     * @param string $collection
     * @param array | object $object
     */
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
    
    /**
     * @param string $collection
     * @param array | object $params
     * @param array | object $sort
     * @return array of nested arrays
     */    
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
    
    /**
     * @param string $collection
     * @param array | object $params
     * @param array | object $update
     * @return array with updated entity
     */
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
    
    /**
    * @param string $collection
    * @param array | object $params
    * @return array with requested entity
    */
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
    
    /**
    * @param string $collection
    * @param array | object $pipeline see @link https://docs.mongodb.com/manual/core/aggregation-pipeline/
    * @return array with requested data
    */
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
    
    /**
    * @param string $collection
    * @param array | object $params
    * @return int counted amount of documents in the collection
    */
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
