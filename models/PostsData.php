<?php
namespace Insta\models;

use Insta\libs\MongoDb;

class PostsData
{
    public static function getPosts($params = array(), $sort = array())
    {
        $post = null;
        $list = [];
        
        $db = MongoDb::getInstance();
        $posts = $db->find('posts', $params, $sort);
        
        if (!empty($posts)) {
            foreach ($posts as $entry) {
                $post = new Post($entry['file'], $entry['title'], $entry['author']);
                isset($entry['_id']) ? $post->setId($entry['_id']) : '';
                isset($entry['date']) ? $post->setDate($entry['date']) : '';
                isset($entry['views']) ? $post->setViews($entry['views']) : $post->setViews(0);
                $list[] = $post;
            }
        }
        
        return $list;
    }
    
    public static function getPost($params = array())
    {
        $db = MongoDb::getInstance();
        $entry = $db->findOne('posts', $params);
        if (!empty($entry)) {
            $post = new Post($entry['file'], $entry['title'], $entry['author']);
            isset($entry['_id']) ? $post->setId($entry['_id']) : '';
            isset($entry['date']) ? $post->setDate($entry['date']) : '';
            isset($entry['views']) ? $post->setViews($entry['views']) : $post->setViews(0);
        }
        
        return $post;
    }
    
    public static function getTotalViews()
    {
        $totalViews = 0;
        $db = MongoDb::getInstance();
        $entry = $db->aggregate('posts', array(array('$group' =>
                                                    array(
                                                            '_id' => null,
                                                            'totalViews' => array('$sum' => '$views')
                                                          )
                                                    )
                                                )
                                );
        if (!empty($entry[0]) && isset($entry[0]['totalViews'])) {
            $totalViews = $entry[0]['totalViews'];        
        }
        
        return $totalViews;
    }
    
    public static function getTotalPosts()
    {
        $db = MongoDb::getInstance();
        return $db->count('posts');
    }
}
