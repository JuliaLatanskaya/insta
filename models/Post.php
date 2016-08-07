<?php
namespace Insta\models;

use Insta\libs\MongoDb;

class Post
{
    private $id = null;
    public $file = null;
    public $author = null;
    public $title = null;
    public $date = null;
    public $views = 0;
    private $db;

    public function __construct($file, $title = '', $author = 'user')
    {
        $this->file = $file;
        $this->author = $author;
        $this->title = $title;
        $this->db = MongoDb::getInstance();
    }
    
    public function update($update = array())
    {
        $this->db->updateOne('posts', array('file' => $this->getFile()), $update);
    }
    
    public function save()
    {
        $this->db->insert('posts', $this);
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
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
