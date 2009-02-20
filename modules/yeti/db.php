<?php
class DB
{
	public static $handle;
	
	public static $query_count = 0;
	
	public static $cache = array();

	public static function get_handle()
	{
		if (empty(self::$handle))
		{
			self::$handle = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			if (self::$handle)
			{
				mysql_select_db(DB_NAME,self::$handle);
				mysql_query('SET NAMES utf8', self::$handle);
			}
			else
			{
				$error = mysql_error();
				throw new Lvc_Exception('Could not connect to the database. Error: '. $error);
			}
		}
		return self::$handle;
	}
	
	public static function escape($string)
	{
		$handle = self::get_handle();
		return mysql_real_escape_string($string,$handle);
	}
	
	/**
	 * Performs a given mySQL query and caches the result.
	 */
	public static function query($query,$update_cache = false)
	{
		$handle = self::get_handle();
		$key = md5($query);
		$update_cache = ($update_cache || (isset(self::$cache[$key]) && (self::$cache[$key]['query'] != $query)));
		// caching has been diabled until I can make it work correctly
		$update_cache = true;
		
		if ($update_cache || !isset(self::$cache[$key]))
		{
			//echo $query;
			if ($result =  mysql_query($query,$handle))
			{
				self::$cache[$key]['query'] = $query;
				self::$cache[$key]['result'] = $result;
			}
			else
			{	
				$error = array('query' => $query, 'error' => mysql_error($handle));
				Log::append_log('mysql_error.log',$error);
			}
			//Debug::log($query,'query');
			
			self::$query_count++;
		}
		return self::$cache[$key]['result'];
	}
	
	public static function query_count($query,$update_cache = false)
	{
		$result = self::query($query,$update_cache);
		return mysql_num_rows($result);
	}
	
	public static function query_to_assoc($query,$update_cache = false)
	{
		$result = self::query($query,$update_cache);
		$return = array();
		while ($row = mysql_fetch_assoc($result))
		{
		    $return[] = $row;
		}
		
		return $return;
	}
	
	public static function dump_cache()
	{
		var_dump(self::$cache);
	}
	
}

$handle = DB::get_handle();
?>