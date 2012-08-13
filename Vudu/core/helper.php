<?php

class Helper {
    private static $models=null;
    private static $modules=null;
    public static function loadModel($model) {
        if(Helper::$models && isset(Helper::$models[$model])) {
            return Helper::$models[$model];
        }
        $path = __DIR__ . "/../models/".$model.".php";
        if(file_exists($path)){
            require_once $path;
            $class = ucfirst($model) . "Model";
            if(class_exists($class)){
                $obj = new ${$class};
                if(!isset(Helper::$models)) Helper::$models=array();
                Helper::$models[$model] = $obj;
                return Helper::$models[$model];
            }
        }
        return false;
    }
    public static function loadModule($module) {
        if(Helper::$modules && isset(Helper::$modules[$module])) {
            return Helper::$modules[$module];
        }
        $path = __DIR__ . "/../modules/".$module.".php";
        if(file_exists($path)){
            require_once $path;
            $class = ucfirst($module);
            if(class_exists($class)){
                $obj = new ${$class};
                if(!isset(Helper::$modules)) Helper::$modules=array();
                Helper::$modules[$module] = $obj;
                return Helper::$modules[$module];
            }
        }
        return false;
    }
}


?>