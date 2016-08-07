<?php
namespace Insta\system;

use Insta\system\BaseException;

class FrontController
{
    private $controller;
    public static $config;
    
    private $routeTable = array(
                            'default' =>
                                array ('controller' => 'home'),
                            'add' =>
                                array ('controller' => 'post', 'defaultAction' => 'createPost'),
                            'update' =>
                                array ('controller' => 'post', 'defaultAction' => 'updatePost'),
                            'views' =>
                                array ('controller' => 'post', 'defaultAction' => 'countViews'),
                            'posts' =>
                                array ('controller' => 'post', 'defaultAction' => 'count')
                        );

    public function __construct($config, $routeName, $action = null)
    {
        self::$config = $config;
        
        $route = $this->getRoute(strval($routeName));
        $controller = $route['controller'];
        $actionDefault = isset($route['defaultAction']) ? $route['defaultAction'] : 'run';
        
        $controllerPath = self::$config['basePath'].'/controllers/' . $controller . 'Controller.php';
        if (file_exists($controllerPath)) {
            // require the file that matches the controller name
            require_once($controllerPath);
        } else {
            throw new BaseException("file with controller $controllerPath doesn't exist");
        }
        
        $controllerName = 'Insta\controllers\\' . ucfirst($controller) . 'Controller';
        
        if (!class_exists($controllerName)) {
            throw new BaseException("class $controllerName was not declared");
        } else {
            $this->controller = new $controllerName();
        }
       
        //Run the controller action
        if (!empty($action) && method_exists($this->controller, $action)) {
            $this->controller->{$action}();
        } elseif (method_exists($this->controller, $actionDefault)) {
            $this->controller->{$actionDefault}();
        } else {
            throw new BaseException("default method $actionDefault is not implemented in $controllerName class");
        }
    }
    
    private function getRoute($routeName)
    {
        $routeName = strtolower($routeName);
        return isset($this->routeTable[$routeName]) ? $this->routeTable[$routeName] : $this->routeTable['default'];
    }
}
