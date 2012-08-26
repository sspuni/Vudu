<?php

/*
 * Name        : vudu.php
 * Author      : Sandeep Singh
 * Email       : s4nd33p@gmail.com
 * Description : This is the heart of the Vudu Web Services Framework.
 * All requests for WS are handled by this file. It controls request/response, 
 * input/output, routing, controllers, etc. It initializes and call them in
 * correct order.
 * 
 *  */

class Vudu {
    public static $response=NULL;
    public static $request=NULL;
    public static $route=NULL;
    public static $database=NULL;
    public static $auth=NULL;
    public static $enabled_filters=0;
    
    public static function run(){
          
        Vudu::init();
        
        Vudu::$route->doAction();

        Vudu::$response->doOutput();
    }
    
    private static function init(){
        global $config;
        Vudu::$enabled_filters = $config['filters_enabled'];
        Vudu::$database = DatabaseManager::getDatabase();
        Vudu::$response = new Response('json');
        Vudu::$request = new Request("get");
        Vudu::$route = new Route();
        Vudu::$auth = new AuthController();
        Vudu::loadDefaults();
    }


    private static function loadDefaults(){
        global $config;
        if(!empty($config['default_classes'])){
            foreach($config['default_classes'] as $c){
                Helper::loadClass($c);
            }
        }
        if(!empty($config['default_helpers'])){
            foreach($config['default_helpers'] as $c){
                Helper::loadHelper($c);
            }
        }
        if(!empty($config['default_models'])){
            foreach($config['default_models'] as $c){
                Helper::loadModel($c);
            }
        }
        if(!empty($config['default_modules'])){
            foreach($config['default_modules'] as $c){
                Helper::loadModule($c);
            }
        }
        
    }
}

?>