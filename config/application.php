<?php
/*********************************************************
 * 
 * Welcome to the Yeti config file.
 * 
 ********************************************************/

/*********************************************************
 * 
 * SITE_NAME is the name of the site, duh. It shows up in
 * various places around your site, like <title>s and in
 * the footer.
 * 
 ********************************************************/
define('SITE_NAME', 'Yeti Site');


/*********************************************************
 * 
 * APP_PATH is the path to the application in the
 * filesystem. The defult setup should figure it out and
 * you should only need to change it if something is
 * weird.
 * 
 ********************************************************/
define('APP_PATH', dirname(dirname(__FILE__)) . '/');


/*********************************************************
 * 
 * WWW_BASE_PATH is the url of your Yeti setup. It
 * should start with // to make it protocol-indepedent.
 * WWW_CSS_PATH, WWW_JS_PATH, & WWW_IMAGE_PATH should
 * work as the defaults, but change them if necessary.
 * 
 ********************************************************/
define('WWW_BASE_PATH', '//yourdomain.com/');
define('WWW_CSS_PATH', WWW_BASE_PATH . 'css/');
define('WWW_JS_PATH', WWW_BASE_PATH . 'js/');
define('WWW_IMAGE_PATH', WWW_BASE_PATH . 'images/');


/*********************************************************
 * 
 * COOKIE_DOMAIN is the domain of the cookies set by
 * Yeti. It's usually safe to use .yourdomain.com
 * 
 ********************************************************/
define('COOKIE_DOMAIN', '.yourdomain.com');


/*********************************************************
 * 
 * LOG_BASE_PATH is the path where Yeti will store
 * various log files. PHP needs write access to this
 * directory. NOTE: Yeti does not handle log file
 * rotation. You will want to set this up yourself or
 * disable logging
 * 
 ********************************************************/
define('LOG_BASE_PATH', '/var/yeti/log/');


/*********************************************************
 * 
 * TRACKER_BASE_PATH is the url and port of xbtt.
 * 
 * This could be moved to the database.
 * 
 ********************************************************/
define('TRACKER_BASE_PATH','http://yourdomain.com:2710/');


/*********************************************************
 * 
 * TORRENT_BASE_PATH is the directory in which Yeti
 * will put torrent files. PHP needs write access to this
 * directory.
 * 
 * This could be moved to the database
 * 
 ********************************************************/
define('TORRENT_BASE_PATH','/var/yeti/torrents/');


/*********************************************************
 * 
 * SALT_TORRENT_PASS, SALT_PASSWORD, SALT_CODE, and
 * SALT_RESET_CODE are various salts used for passwords
 * and such. Set them to something unique, but don't
 * change them or chaos will ensue.
 * 
 ********************************************************/
define('SALT_TORRENT_PASS', 'salttime');
define('SALT_PASSWORD', 'okseriously');
define('SALT_CODE', 'activationtime');
define('SALT_RESET_CODE', 'whatpassword');


/*********************************************************
 * 
 * DB_HOST, DB_NAME, DB_USER, and DB_PASS should be self-
 * explanatory. 
 * 
 ********************************************************/
define('DB_HOST', 'localhost');
define('DB_NAME', 'xbt');
define('DB_USER', 'xbt_user');
define('DB_PASS', '6qf2s79V4pM3y8XZ');


/*********************************************************
 * 
 * Yeti uses sphinx for search optimization.
 * SEARCHD_HOST and SEARCHD_PORT should be set according
 * to your sphinx setup.
 * 
 * This setting should probably be moved to the database
 * 
 ********************************************************/
define('SEARCHD_HOST', 'localhost');
define('SEARCHD_PORT', 3312);

/*********************************************************
 * 
 * EMAIL_FROM is the email address from which registration
 * emails and various other email will be sent.
 * 
 * EMAIL_FROM_NAME is the name from which these emails
 * will be sent.
 * 
 * This setting should be moved to the database
 * 
 ********************************************************/
define('EMAIL_FROM', 'yeti@yourdomain.com');
define('EMAIL_FROM_NAME', 'Yeti');

/*********************************************************
 * 
 * CONTACT_EMAIL is the email address of the primary
 * administrator of your site. Yeti will, under
 * various circumstances, email this address to warn of
 * errors and such.
 * 
 * Currently the only time this happens is when a user
 * which is not present in LDAP attempts to register.
 * 
 * This setting should be moved to the database
 * 
 ********************************************************/
define('CONTACT_EMAIL', 'admin@yourdomain.com');


/*********************************************************
 * 
 * Set DISABLE_MESSAGING to true to, well, disable
 * messaging on the site. This is just temporary until
 * the messaging feature is fully implemented.
 * 
 ********************************************************/
define('DISABLE_MESSAGING', true);



/*********************************************************
 * 
 * STOP EDITING HERE!!!!
 * 
 * Unless you're seriously hacking apart Yeti,
 * everything below this line should remain unchanged.
 * 
 ********************************************************/

// Just some page generation time tracking
define('START_MICROTIME',microtime());

// Include and configure the LighVC framework
include_once(APP_PATH . 'modules/lightvc.php');
Lvc_Config::addControllerPath(APP_PATH . 'controllers/');
Lvc_Config::addControllerViewPath(APP_PATH . 'views/');
Lvc_Config::addLayoutViewPath(APP_PATH . 'views/layouts/');
Lvc_Config::addElementViewPath(APP_PATH . 'views/elements/');
Lvc_Config::setViewClassName('AppView');

//Lvc_Config::setControllerSuffix('_controller.php');
//Lvc_Config::setControllerViewSuffix('.thml');
//Lvc_Config::setLayoutViewSuffix('_layout.thml');
//Lvc_Config::setElementViewSuffix('_element.thml');

// Load the app controller
include(APP_PATH . 'classes/AppController.class.php');
include(APP_PATH . 'classes/AppView.class.php');

// Load the Yeti module.
include_once(APP_PATH . 'modules/yeti.php');

// Load Routes
include(dirname(__FILE__) . '/routes.php');

?>
