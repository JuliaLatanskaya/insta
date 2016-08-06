<?php
namespace Insta\controllers;

use Insta\system\FileUploader;
use Insta\models\Post;
use Insta\libs\BaseException;

class PostController
{
    public function run()
    {
       echo 'run mwthod';
    }
    
    public function createPost()
    {
        $post = null;
        $file = new FileUploader();
        
        if ($filename = $file->uploadedFile()) {
            $title = !empty($_POST['title']) ? (string)$_POST['title'] : '';
            $post = new Post($filename, $title);
            $post->setDate(time());
            $post->save();
        }
        
        echo json_encode(array('error' => $file->getError()));
    }
}
