<?php
namespace Insta\controllers;

use Insta\system\FileUploader;
use Insta\models\Post;

class PostController
{    
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
    
    public function updatePost()
    {
        $post = null;
        $file = !empty($_POST['file']) ? (string)$_POST['file'] : '';
        if (!empty($file)) {
            $post = Post::getPost(array('file' => $file));
            if (!empty($post)) {
                $post->setViews($post->getViews() + 1);
                $post->update(array('views' => $post->getViews()));
            }
        }
        
        echo json_encode(array('post' => $post));
        
    }
}
