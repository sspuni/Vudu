<?php

/**
 * Starting point!
 * 
 * This is the starting point of this web services framework. Its the only file which needs to be exposed to web by placing it inside some web-accessible directory.
 * 
 * @filesource index.php
 */

/**
 * Include the one and only main file, required for the whole configuration and running of the framework. 
 */

include 'include/vudu.php';


/**
 * Start the magic! 
 */

Vudu::run();

?>