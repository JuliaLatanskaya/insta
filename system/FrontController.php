<?php
namespace Insta\system;

use Insta\system\BaseException;

class FrontController
{
    /**
     * @var Controller
     */
    private $controller;
    
    /**
     * @var array
     */
    public static $config;
    
    /**
     * Logger class. For more info visit @link https://github.com/katzgrau/KLogger
     *  
     * @var Katzgrau\KLogger\Logger instance
     */
    public static $logger;
    
    /**
     * Predefined route table. To add new route you have to edit this table.
     *
     * @var array
     */
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
                                array ('controller' => 'post', 'defaultAction' => 'count'),
                            'csv' =>
                                array ('controller' => 'export', 'defaultAction' => 'csv')
                        );

    /**
     * Works as entry point to app. Check $routeName and creates appropiate controller.
     * Then calls requested action $action (if defined) | defaultAction @see FrontController::$routeTable
     *
     * @param array $config contains app settings
     * @param Katzgrau\KLogger\Logger $logger Logger instance @see FrontController::$logger
     * @param string $routeName
     * @param string $action | null 
     */
    public function __construct($config, $logger, $routeName, $action = null)
    {
        self::$config = $config;
        self::$logger = $logger;
        
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
            if (!empty($action)) {
                self::$logger->error("controller $controller was called with action $action
                                     which is not implemented. Default action $actionDefault was called");
            }
        } else {
            throw new BaseException("default method $actionDefault is not implemented in $controllerName class");
        }
    }
    
    /**
     * Check if requested page is defined in route table @see FrontController::$routeTable
     * @param string @routeName
     * @return array which contains mapping rules
     */
    private function getRoute($routeName)
    {
        $routeName = strtolower($routeName);
        return isset($this->routeTable[$routeName]) ? $this->routeTable[$routeName] : $this->routeTable['default'];
    }
}
