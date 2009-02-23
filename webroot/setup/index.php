<?php

// Load installer config
include_once('../../setup/config.php');

try {
	
	// Process the HTTP request using only the routers we need for this application.
	$fc = new Lvc_FrontController();
	$fc->addRouter(new Lvc_RegexRewriteRouter($regexRoutes));
	$fc->processRequest(new Lvc_HttpRequest());
	
} catch (Lvc_Exception $e) {
	
	// Log the error message
	error_log($e->getCode() . ': ' . $e->getMessage());
			
	$code = ($e->getCode() == 0) ? '404' : $e->getCode();

	// Get a request for the 404 error page.
	$request = new Lvc_Request();
	$request->setControllerName('error');
	$request->setActionName('view');
	$request->setActionParams(array('error' => $code));

	// Get a new front controller without any routers, and have it process our handmade request.
	$fc = new Lvc_FrontController();
	$fc->processRequest($request);
	
} catch (ErrorException $e) {
	
	// Some other error, output "technical difficulties" message to user?
	error_log($e->getMessage());
	
} catch (Exception $e) {

	// Some other error, output "technical difficulties" message to user?
	error_log($e->getMessage());

}

?>