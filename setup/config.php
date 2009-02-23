<?php

/*********************************************************
 * 
 * This is the setup-specific config file. We basically
 * create a sub-app that is seperate from Yeti to handle
 * the initial Yeti setup.
 * 
 ********************************************************/

define('APP_PATH', dirname(dirname(__FILE__)) . '/');
define('WWW_BASE_PATH', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('WWW_CSS_PATH', WWW_BASE_PATH . 'css/');
define('WWW_JS_PATH', WWW_BASE_PATH . 'js/');
define('WWW_IMAGE_PATH', WWW_BASE_PATH . 'images/');
include_once(APP_PATH . 'modules/lightvc.php');
Lvc_Config::addControllerPath(APP_PATH . 'setup/controllers/');
Lvc_Config::addControllerViewPath(APP_PATH . 'setup/views/');
Lvc_Config::addLayoutViewPath(APP_PATH . 'setup/views/layouts/');
Lvc_Config::addElementViewPath(APP_PATH . 'setup/views/elements/');
Lvc_Config::setViewClassName('AppView');

// We'll use the same AppController and AppView as the main app.
include(APP_PATH . 'classes/AppController.class.php');
include(APP_PATH . 'classes/AppView.class.php');

// Load some of the Yeti code. We'll use some of the helpers classes
// such as time/text formatting and the Flash class.
include_once(APP_PATH . 'modules/yeti/flash.php');
include_once(APP_PATH . 'modules/yeti/helper.php');

// Load Routes from /setup/
include(dirname(__FILE__) . '/routes.php');


?>