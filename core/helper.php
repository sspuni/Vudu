<?php

/*
 * Name : helper.php
 * Author : Sandeep Singh
 * Description : Helper class which facilitates dynamic loading of modules, models, controllers, classes, etc.
 * 
 */

class Helper {
    private static $models=null;
    private static $modules=null;
    private static $controllers=null;
    private static $classes=null;
    private static $helpers=null;
    
    public static function loadHelper($helper) {
        if(Helper::$helpers && isset(Helper::$helpers[$helper])) {
            return Helper::$helpers[$helper];
        }
        $path = __DIR__ . "/../helpers/".strtolower($helper).".php";
        if(file_exists($path)){
            require_once $path;
            $class = $helper;//ucfirst($helper);
            if(class_exists($class)){
                $obj = new $class;
                if(!isset(Helper::$helpers)) Helper::$helpers=array();
                Helper::$helpers[$helper] = $obj;
                return Helper::$helpers[$helper];
            }
        }
        return false;
    }
    public static function loadModel($model) {
        if(Helper::$models && isset(Helper::$models[$model])) {
            return Helper::$models[$model];
        }
        $path = __DIR__ . "/../models/".strtolower($model).".php";
        if(file_exists($path)){
            require_once $path;
            $class = ucfirst($model) . "Model";
            if(class_exists($class)){
                $obj = new $class;
                if(!isset(Helper::$models)) Helper::$models=array();
                Helper::$models[$model] = $obj;
                return Helper::$models[$model];
            }
        }
        return false;
    }
    
    public static function loadController($controller) {
        if(Helper::$controllers && isset(Helper::$controllers[$controller])) {
            return Helper::$controllers[$controller];
        }
        $path = __DIR__ . "/../controllers/".strtolower($controller).".php";
        if(file_exists($path)){
            require_once $path;
            $class = ucfirst($controller) . "Controller";
            if(class_exists($class)){
                $obj = new $class;
                if(!isset(Helper::$controllers)) Helper::$controllers=array();
                Helper::$controllers[$controller] = $obj;
                return Helper::$controllers[$controller];
            }
        }
        return false;
    }
    
    public static function loadModule($module) {
        if(Helper::$modules && isset(Helper::$modules[$module])) {
            return Helper::$modules[$module];
        }
        $path = __DIR__ . "/../modules/".strtolower($module).".php";
        if(file_exists($path)){
            require_once $path;
            $class = $module;//ucfirst($module);
            if(class_exists($class)){
                $obj = new $class;
                if(!isset(Helper::$modules)) Helper::$modules=array();
                Helper::$modules[$module] = $obj;
                return Helper::$modules[$module];
            }
        }
        return false;
    }
    
    public static function loadClass($c) {
        if(Helper::$classes && isset(Helper::$classes[$c])) {
            return Helper::$classes[$c];
        }
        $path = __DIR__ . "/../lib/".strtolower($c).".php";
        if(file_exists($path)){
            require_once $path;
            $class = $c;//ucfirst($c);
            if(class_exists($class)){
                $obj = new $class;
                if(!isset(Helper::$classes)) Helper::$classes=array();
                Helper::$classes[$c] = $obj;
                return Helper::$classes[$c];
            }
        }
        return false;
    }
}


?>