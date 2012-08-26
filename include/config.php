<?php

/*
 * Name   : config.php
 * Author : Sandeep Singh
 */


$config['DATABASE'] = array(
    'db_host' => 'localhost',
    'db_name' => 'vudu_test',
    'db_user' => 'root',
    'db_pass' => 'iknowit',
    'db_type' => 'MySQL'
);

$config['default_classes'] = array();
$config['default_helpers'] = array();
$config['default_models'] = array();
$config['default_modules'] = array();


define('NO_FILTER', 0);
define('BEFORE_FILTER', 1);
define('AFTER_FILTER', 2);
define('BOTH_FILTER', 3);

$config['filters_enabled'] = BOTH_FILTER;


?>