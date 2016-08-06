<?php
namespace Insta\controllers;

use Insta\system\RenderController;
use Insta\models\Post;

class HomeController
{
    public function run()
    {
        $posts = \Insta\models\Post::getPosts();
        $render = new RenderController();
        $render->setContent($render->renderTemplate('home/post.html', array('posts' => $posts)));
        $render->response();
    }
}
