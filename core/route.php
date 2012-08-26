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
        global $routes;
        $this->controller = 'Controller';
        $action = Vudu::$request->getVar('action');
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
        
        $obj = Helper::loadController($this->controller);
        
        if(!$obj) $obj = new Controller();
        
        if(is_callable(array($obj, $this->method))){
            if(Vudu::$enabled_filters&BEFORE_FILTER && !empty($obj->filters['before']) && (empty($obj->filters['before_exceptions']) || !in_array($this->method, $obj->filters['before_exceptions']))){
                foreach($obj->filters['before'] as $filter){
                    $m = Helper::loadModule($filter);
                    if($m){
                        if(!$m->run()) return;
                    }
                }
            }
            call_user_func(array($obj, $this->method));
            if(Vudu::$enabled_filters&AFTER_FILTER && !empty($obj->filters['after']) && (empty($obj->filters['after_exceptions']) || !in_array($this->method, $obj->filters['after_exceptions']))){
                foreach($obj->filters['after'] as $filter){
                    $m = Helper::loadModule($filter);
                    if($m){
                        if(!$m->run()) return;
                    }
                }
            }
        }else {
            Vudu::$response->setError(404, 'Unknown Action');
        }
    }
}

?>