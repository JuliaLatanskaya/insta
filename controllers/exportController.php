<?php
namespace Insta\controllers;

use Insta\models\PostsData;

class exportController {
    public function __construct() {}
    
    public function csv()
    {
        $posts = PostsData::getPosts(array(), array('date' => -1));
        
        if (!empty($posts)) {
            $filename = 'posts_'.date('Ymd_H:i:s').'.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
            header("Pragma: no-cache"); // HTTP 1.0
            header("Expires: 0"); // Proxies
        
            $output = fopen("php://output", "w");
            fputcsv($output, array("Title", "FileName"));
            foreach ($posts as $post) {
                fputcsv($output, array($post->getTitle(), $post->getFile()));
            }
            
            fclose($output);
        }
    }
}
