<?php
namespace Insta\models;

use Insta\libs\MongoDb;

class Post
{
    private $id = null;
    private $file = null;
    
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

    public static function all()
    {
        $post = null;
        $list = [];
        $collection = MongoDb::getInstance()->posts;
        $posts = $collection->find(); //TODO: order by date
        
        foreach ($posts as $entry) {
            $post = new Post($entry['file'], $entry['title'], $entry['author']);
            isset($entry['_id']) ? $post->setId($entry['_id']) : '';
            isset($entry['date']) ? $post->setDate($entry['date']) : '';
            isset($entry['views']) ? $post->setViews($entry['date']) : $post->setViews(0);
            $list[] = $post;
        }
        
        return $list;
    }

    public static function find($id)
    {
        if (!is_string($id)) {
            throw new BaseException('trying to get post with wrong id: '.var_dump($id)); //TODO: check it
        }
        
        $post = null;
        $collection = MongoDb::getInstance()->posts;
        $entry = $collection->find(['_id' => $id]);
        
        if (!empty($entry)) {
            $post = new Post($entry['file'], $entry['title'], $entry['author']);
            $post->setId($entry['_id']);
            isset($entry['date']) ? $post->setDate($entry['date']) : '';
            isset($entry['views']) ? $post->setViews($entry['date']) : $post->setViews(0);
        }
        
        return $post;
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
    
    //public function getDate()
    //{
    //    return $this->date;
    //}
    
    public function setViews($views)
    {
        $this->views = $views;
    }
    
    //public function getViews()
    //{
    //    return $this->views;
    //}
    
    //public function getAuthor()
    //{
    //    return $this->author;
    //}
    
    public function getFile()
    {
        return $this->file;
    }
    
    //public function getTitle()
    //{
    //    return $this->title;
    //}
}

