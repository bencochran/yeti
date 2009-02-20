<?php

class ErrorController extends AppController
{
	/**
	 * Hash of error code -> error message mappings.
	 *
	 * @var array
	 * @see http://www.faqs.org/rfcs/rfc2616
	 **/
	protected static $errorString = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
	);
	
	public function actionView($errorNum = '404')
	{
		$user = User::require_active_user();
		//if ($user = get_active_user())
		//{
			$this->setLayoutVar('active_user',$user);
			$this->setVar('active_user',$user);
		//}
		//else $this->setLayout('outside');
		
		if (strpos($errorNum, '../') !== false)
		{
			$errorNum = '404';
		}
		
		if (isset(self::$errorString[$errorNum]))
		{
			$errorMsg = self::$errorString[$errorNum];
			header('HTTP/1.1 ' . $errorNum . ' ' . $errorMsg);
		}
		else
		{
			$errorMsg = 'Not Found';
		}
		
		$this->setLayoutVar('pageTitle', $errorMsg);
		$this->setLayoutVar('pageHead', $errorMsg);
		$this->loadView($this->getControllerName() . '/' . $errorNum);
	}
	
}

?>