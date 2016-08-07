<?php
namespace Insta\controllers;

use Insta\system\RenderController;
use Insta\models\PostsData;

class HomeController
{
    public function run()
    {
        $posts = PostsData::getPosts(array(), array('date' => -1));
        $render = new RenderController();
        $render->setContent($render->renderTemplate('home/post.html', array('posts' => $posts)));
        $render->setParam('totalViews', PostsData::getTotalViews());
        $render->setParam('totalPosts', PostsData::getTotalPosts());
        $render->response();
    }
}
