<?php
class Flash {	
	
	private static $flash = array();
	
	public static function set($status,$message)
	{
		self::$flash['status'] = $status;
		self::$flash['message'] = $message;
	
		$_SESSION['flash']['status'] = $status;
		$_SESSION['flash']['message'] = $message;
	}
	
	public static function get()
	{
		$return = self::$flash;
		if (!empty($_SESSION['flash'])) $return = array_merge($_SESSION['flash'],$return);
		self::clean();
		
		if (!empty($return)) return $return;
		else return false;	
	}
	
	public static function clean()
	{
		self::$flash = array();
		unset($_SESSION['flash']);
	}
	
}

?>