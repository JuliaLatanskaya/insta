<?php
namespace Insta\system;

use Insta\system\BaseException;

class RenderController
{
    /**
     * Template engine class. For more info visit @link http://twig.sensiolabs.org/documentation
     *  
     * @var \Twig_Environment instance
     */
    private $twig;
    
    /**
     * Main content to be rendered in layout.html. Usually contains rendered subtemplate.
     *
     * @var string
     */
    private $content = '';
    
    /**
     * Contains $key => $value pairs to be parsed in layout.html
     * @var array  
     */
    public $params = array();

    public function __construct()
    {
        $templateEngine = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($templateEngine);
        $this->twig->addGlobal('siteBase', FrontController::$config['siteBase']);
    }
    
    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;    
    }
    
    /**
     * @param string $template
     * @param array $params
     * @return string
     */
    public function renderTemplate($template, $params)  
    {
        if (!is_array($params) || !is_string($template)) {
            throw new BaseException("got wrong template name $template or params ". json_encode($params));
        }
        
        return $this->twig->render($template, $params); 
    }
    
    /**
     * @param string $param
     * @param any $value
     */
    public function setParam($param, $value)
    {
        $this->params[$param] = $value;
    }
    
    /**
     * sent rendered page to client
     */
    public function response()
    {
        echo $this->render();
    }
    
    /**
     * renders main template with predefined params
     * @return rendered page
     */
    private function render()
    {
        $this->params = array_merge($this->params, array('content' => $this->content));
        return $this->twig->render('layout.html', $this->params);
    }
}
