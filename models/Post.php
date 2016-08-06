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

    public function __construct($file, $title = '', $author = 'user')
    {
        $this->file = $file;
        $this->author = $author;
        $this->title = $title;
    }

    public static function getPosts($params = array())
    {
        $post = null;
        $list = [];
        $posts = MongoDb::find('posts', array(), array('date' => -1));
        
        if (!empty($posts)) {
            foreach ($posts as $entry) {
                $post = new Post($entry['file'], $entry['title'], $entry['author']);
                isset($entry['_id']) ? $post->setId($entry['_id']) : '';
                isset($entry['date']) ? $post->setDate($entry['date']) : '';
                isset($entry['views']) ? $post->setViews($entry['date']) : $post->setViews(0);
                $list[] = $post;
            }
        }
        
        return $list;
    }
    
    public function save()
    {
        MongoDb::insert('posts', $this);
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
