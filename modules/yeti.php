<?php
	// Start output buffering and the session
	ob_start();
	session_start();
	
	// Include libraries
	require_once(APP_PATH.'modules/yeti/helper.php');
	require_once(APP_PATH.'modules/yeti/Bittorrent2/Decode.php');
	require_once(APP_PATH.'modules/yeti/sphinxapi.php');
	require_once(APP_PATH.'modules/yeti/db.php');
	require_once(APP_PATH.'modules/yeti/torrent.php');
	require_once(APP_PATH.'modules/yeti/category.php');
	require_once(APP_PATH.'modules/yeti/mail.php');
	require_once(APP_PATH.'modules/yeti/user.php');
	require_once(APP_PATH.'modules/yeti/message.php');
	require_once(APP_PATH.'modules/yeti/file.php');
	require_once(APP_PATH.'modules/yeti/chart.php');
	require_once(APP_PATH.'modules/yeti/flash.php');
	require_once(APP_PATH.'modules/yeti/ldap.php');
	require_once(APP_PATH.'modules/yeti/log.php');
	require_once(APP_PATH.'modules/yeti/newspost.php');
	require_once(APP_PATH.'modules/yeti/admin.php');
	require_once(APP_PATH.'modules/yeti/comment.php');
	
?>
