<?php

// Setup-specific routes. The begining /setup/ does
// not need to be included in the regex's.

// Format of regex => parseInfo
$regexRoutes = array(

	// Map nothing to the home page.
	'#^$#' => array(
		'controller' => 'setup',
		'action' => 'site',
		'action_params' => array(
			'page_name' => 'home',
		),
	),

	'#^([^/]+)$#' => array(
		'controller' => 'setup',
		'action' => 1,
	),
	
);

?>