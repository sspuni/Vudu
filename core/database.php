<?php

/*
 * Name : database.php
 * Author : Sandeep Singh
 * Description : This facilitates functions which enables creation of database interface object.
 */

class DatabaseManager {
    private static $instance=null;
    
    public static function getDatabase(){
        global $config;
        if(!DatabaseManager::$instance){
            
            if(!isset($config['db_type']) || !$config['db_type']) $config['db_type'] = 'MySQL';
            
            $path = __DIR__ . "/../database/".strtolower($config['db_type']).".php";
            if(file_exists($path)) {
                require_once $path;
                
            }else {
                $path = __DIR__ . "/database/".strtolower($config['db_type']).".php";
                if(file_exists($path)) {
                    require_once $path;
                }
            }
            
            $class = $config['db_type']."Database";
            if(class_exists($class)){
                DatabaseManager::$instance = new $class;
            }
        }
        return DatabaseManager::$instance;
    }
}

?>