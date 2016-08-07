<?php
namespace Insta\models;

use Insta\system\MongoDb;

class PostsData
{
    /**
     * @param array | object $params
     * @param array | object $sort
     * @return array of Post entities
     */
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
    
    /**
     * @param array | object $params
     * @return Post entity
     */
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
    
    /**
     * @return int sum of 'views' in posts collection values 
     */
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
    
    /**
     * @return int sum of all documents in posts collection 
     */
    public static function getTotalPosts()
    {
        $db = MongoDb::getInstance();
        return $db->count('posts');
    }
}
