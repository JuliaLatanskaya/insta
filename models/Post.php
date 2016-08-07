<?php
namespace Insta\models;

use Insta\system\MongoDb;

class Post
{
    /**
     * @var ObjectId. See @https://docs.mongodb.com/manual/reference/method/ObjectId/
     */
    private $id = null;
    
    /**
     * @var string
     */
    public $file = null;
    
    /**
     * @var string
     */
    public $author = null;
    
    /**
     * @var string
     */
    public $title = null;
    
    /**
     * @var string
     */
    public $date = null;
    
    /**
     * @var int
     */
    public $views = 0;
    
    /**
     * @var MongoDb instance
     */
    private $db;

    public function __construct($file, $title = '', $author = 'user')
    {
        $this->file = $file;
        $this->author = $author;
        $this->title = $title;
        $this->db = MongoDb::getInstance();
    }
    
    /**
     * @param array | object
     */
    public function update($update = array())
    {
        $this->db->updateOne('posts', array('file' => $this->getFile()), $update);
    }
    
    public function save()
    {
        $this->db->insert('posts', $this);
    }
    
    /**
     * @param ObjectId
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param string @date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * @param int $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }
    
    public function getViews()
    {
        return $this->views;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function getFile()
    {
        return $this->file;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
}
