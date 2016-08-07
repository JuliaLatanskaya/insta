<?php
namespace Insta\system;

use Insta\system\BaseException;

class RenderController
{
    private $twig;
    private $content;
    public $params = array();

    public function __construct()
    {
        $templateEngine = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($templateEngine);
        $this->twig->addGlobal('siteBase', FrontController::$config['siteBase']);
    }
    
    public function setContent($content)
    {
        $this->content = $content;    
    }
    
    /**
     * @param string $template
     * @param array $params
     */
    public function renderTemplate($template, $params)  
    {
        if (!is_array($params) || !is_string($template)) {
            throw new BaseException("got wrong template name $template or params");
        }
        return $this->twig->render($template, $params); 
    }
    
    public function setParam($param, $value)
    {
        $this->params[$param] = $value;
    }
    
    public function response()
    {
        echo $this->render();
    }
    
    private function render()
    {
        $this->params = array_merge($this->params, array('content' => $this->content));
        return $this->twig->render('layout.html', $this->params);
    }
}
