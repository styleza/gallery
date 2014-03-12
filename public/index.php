<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
set_include_path(get_include_path() .
        PATH_SEPARATOR . realpath('../library/') .
        PATH_SEPARATOR . realpath('../application/'));

define('APPLICATION_PATH', realpath('../application/'));

require_once 'application.php';

$configArray = require '../application/config/application.php';

// register app config
resources::init($configArray);

$myApp = application::getApplicationInstance('photoApp');
echo $myApp->run($_REQUEST);