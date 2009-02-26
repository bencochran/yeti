<?php

// Format of regex => parseInfo
$regexRoutes = array(
	
	// Map nothing to the home page.
	'#^$#' => array(
		'controller' => 'page',
		'action' => 'view',
		'action_params' => array(
			'page_name' => 'home',
		),
	),
	
	// Allow direct access to all pages via a "/page/page_name" URL.
	'#^page/(.*)$#' => array(
		'controller' => 'page',
		'action' => 'view',
		'action_params' => array(
			'page_name' => 1,
		),
	),
	
	// /help
	'#^help$#' => array(
		'controller' => 'page',
		'action' => 'view',
		'action_params' => array(
			'page_name' => 'help',
		),
	),
	
	// /help/transmission
	'#^help/transmission$#' => array(
		'controller' => 'page',
		'action' => 'view',
		'action_params' => array(
			'page_name' => 'help/transmission',
		),
	),
	
	// /help/utorrent
	'#^help/utorrent$#' => array(
		'controller' => 'page',
		'action' => 'view',
		'action_params' => array(
			'page_name' => 'help/utorrent',
		),
	),
	
	
	// /contact
	'#^contact$#' => array(
		'redirect' => '/help#contact',
	),
	
	'#^locked$#' => array(
		'controller' => 'page',
		'action' => 'publicview',
		'action_params' => array(
			'page_name' => 'locked',
		),
	),
	
	
	// /browse, /browse/slug, /browse/slug/2
	'#^browse(?:/([^/]+)(?:/(\d+))?)?$#' => array(
		'controller' => 'torrent',
		'action' => 'browse',
		'action_params' => array(
			'category_slug' => 1,
			'page' => 2,
		),
	),
		
	
	// /search
	'#^search$#' => array(
		'controller' => 'torrent',
		'action' => 'search',
	),
		
	// /search/slug
	'#^search/([^/]+)/?$#' => array(
		'redirect' => '/browse/$1'
	),
		
	
	// /search/slug/query, /search/slug/query/2
	'#^search/([^/]+)/([^/]+)(?:/(\d+))?$#' => array(
		'controller' => 'torrent',
		'action' => 'browse',
		'action_params' => array(
			'category_slug' => 1,
			'query' => 3,
			'page' => 2,
		),
	),	
	
	// /charts
	'#^chart$#' => array(
		'controller' => 'chart',
		'action' => 'index',
	),
	
	// /torrent
	'#^torrent$#' => array(
		'redirect' => '/browse',
	),
	
	// /torrent/1234
	'#^torrent/([\d]+)$#' => array(
		'controller' => 'torrent',
		'action' => 'details',
		'action_params' => array(
			'fid' => 1,
		),
	),
	
	// /torrent/1234/action
	'#^torrent/(\d+)/([^/]+)$#' => array(
		'controller' => 'torrent',
		'action' => 2,
		'action_params' => array(
			'fid' => 1,
		),
	),
	
	// /torrent/1234/files/page
	'#^torrent/(\d+)/files/(\d+)$#' => array(
		'controller' => 'torrent',
		'action' => 'files',
		'action_params' => array(
			'fid' => 1,
			'page' => 2,
		),
	),
	
	// /torrent/1234/comments/page
	'#^torrent/(\d+)/comments/(\d+)$#' => array(
		'controller' => 'torrent',
		'action' => 'comments',
		'action_params' => array(
			'fid' => 1,
			'page' => 2,
		),
	),
	
	
	'#^user/autocomplete.php$#' => array(
		'controller' => 'user',
		'action' => 'autocomplete',
	),
		
	// /user/login /user/register /user/logout
	'#^user/(login|register|logout|reset)$#' => array(
		'controller' => 'user',
		'action' => 1,
	),
	
	// /user/name
	'#^user/([^/]+)$#' => array(
		'controller' => 'user',
		'action' => 'view',
		'action_params' => array(
			'name' => 1,
		),
	),
	
	// /user/name/activate/code
	'#^user/([^/]+)/activate/([^/]+)$#' => array(
		'controller' => 'user',
		'action' => 'activate',
		'action_params' => array(
			'name' => 1,
			'code' => 2,
		),
	),
	
	// /user/name/reset/code
	'#^user/([^/]+)/reset/([^/]+)$#' => array(
		'controller' => 'user',
		'action' => 'resetpass',
		'action_params' => array(
			'name' => 1,
			'code' => 2,
		),
	),
	
	// /user/name/action, /user/name/action/page
	'#^user/([^/]+)/([^/]+)(?:/(\d+))?$#' => array(
		'controller' => 'user',
		'action' => 2,
		'action_params' => array(
			'name' => 1,
			'page' => 3,
		),
	),
	
	// /message/mid
	'#^message/([\d]+)$#' => array(
		'controller' => 'message',
		'action' => 'view',
		'action_params' => array(
			'fid' => 1,
		),
	),
	
	// /message/compose/username
	'#^message/compose/([^/]+)$#' => array(
		'controller' => 'message',
		'action' => 'send',
		'action_params' => array(
			'user' => 1,
		),
	),
	
	// /news/page
	'#^news/([\d]+)$#' => array(
		'controller' => 'news',
		'action' => 'index',
		'action_params' => array(
			'page' => 1,
		),
	),
	
	// Map controler/action/params
	'#^([^/]+)/([^/]+)/?(.*)$#' => array(
		'controller' => 1,
		'action' => 2,
		'additional_params' => 3,
	),
	
	// Map controllers to a default action (not needed if you use the
	// Lvc_Config static setters for default controller name, action
	// name, and action params.)
	'#^([^/]+)/?$#' => array(
		'controller' => 1,
		'action' => 'index',
	),
	
);

?>