<?php
namespace Insta\controllers;

use Insta\system\RenderController;
use Insta\models\Post;

class HomeController
{
    public function run()
    {
        $posts = Post::getPosts(array(), array('date' => -1));
        $render = new RenderController();
        $render->setContent($render->renderTemplate('home/post.html', array('posts' => $posts)));
        $render->response();
    }
}
