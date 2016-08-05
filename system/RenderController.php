<?php
namespace Insta\system;

class RenderController
{
    private $twig;
    private $content;

    public function __construct()
    {
        $templateEngine = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($templateEngine);
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
        if (!is_array($params) && is_string($template)) {
            echo 'bad';
            exit;
        }
        return $this->twig->render($template, $params); 
    }
    
    private function render()
    {
        return $this->twig->render('layout.html', array('content' => $this->content));
    }
    
    public function response()
    {
        echo $this->render();
    }
}