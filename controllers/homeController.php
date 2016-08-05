<?php
namespace Insta\controllers;

use Insta\system\RenderController;
use Insta\system\FrontController;
use Insta\models\Post;

class HomeController
{
    public function run()
    {
        $posts = \Insta\models\Post::all();
        $render = new RenderController();
        $r = $render->renderTemplate('home/post.html', array('posts' => $posts));
        //$r = $render->renderTemplate('home/post.html', array('posts' =>
        //                                                   array(0 => array('title'=> 'My cat',
        //                                                         'name' => 'here we are'),
        //                                                         1 => array('title'=> 'My cat',
        //                                                         'name' => 'here we are')
        //                                                         ))
        //                          );
        $render->setContent($r);
        $render->response();
    }
    
    public function index()
    {
        $posts = \Insta\models\Post::all();
        require('views/pages/posts.php');
    }
  
    public function error()
    {
        require_once('views/pages/error.php');
    }
}
