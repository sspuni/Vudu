<?php

/*
 * Name   : route.php
 * Author : Sandeep Singh
 * 
 */

class Route {
    private $controller;
    private $method;
    public function __construct() {
        $this->parse();
    }

    private function parse() {
        global $request, $routes;
        $this->controller = 'Controller';
        $action = $request->getVar('action');
        if(!$action || empty($action)){
            $this->method = 'index';
        }else {
            $this->method = $action;
        }
        if(!is_array($routes)) $routes = array();
        if(array_key_exists($action, $routes)){
            if(is_array($routes[$action])){
                $this->controller = $routes[$action][0];
                $this->method = (isset($routes[$action][1])?$routes[$action][1]:'index');
            }else{
                $this->method = (!empty($routes[$action])?$routes[$action]:'index');
            }
        }
    }
    
    public function doAction(){
        global $response;
        if(file_exists(__DIR__ . '/../controllers/'.strtolower($this->controller).'.php')){
            require_once __DIR__ . '/../controllers/'.strtolower($this->controller).'.php';
        }
        $cont = ucfirst($this->controller) . 'Controller';
        if(class_exists($cont)){
            $obj = new ${$cont};
        }else {
            require_once __DIR__.'/controller.php';
            $obj = new Controller();
        }
        
        if(is_callable(array($obj, $this->method))){
            call_user_func(array($obj, $this->method));
        }else {
            $response->setError(404, 'Unknown Action');
        }
    }
}

?>
