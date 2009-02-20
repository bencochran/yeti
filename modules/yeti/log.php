<?php

class Log
{
	
	public static function append_log($file,$data)
	{
		$file = LOG_BASE_PATH . $file;
		
		if (!is_array($data))
			$data = array('message' => $data);
		
		if (!isset($data['timestamp']))
		{
			$data['timestamp'] = date('D M d H:i:s Y');
		}
		
		if (!isset($data['backtrace']))
		{
			$trace = debug_backtrace();
			$trace = self::digest_backtrace($trace,3);
			$data['backtrace'] = $trace;
		}
				
		ob_start();
		var_dump($data);
		$data = ob_get_contents();
		ob_end_clean();
		
		file_put_contents($file, $data, FILE_APPEND);
	}
	
	public static function digest_backtrace($trace, $levels = 5)
	{
		$return = array();
		foreach($trace as $k=>$step)
		{
			if ($levels < 0) break; $levels--;
			$output = '';
			$file = $step['file'];
			$line = $step['line'];
			$function = (isset($step['function'])) ? $step['function'] : false;
			$type = (isset($step['type'])) ? $step['type'] : false;
			$class = (isset($step['class'])) ? $step['class'] : false;
			$args = (isset($step['args'])) ? $step['args'] : false;
			
			if ($file) $output['file'] = $file;
			if ($line) $output['file'] .= ' on line ' . $line;
			if ($class == 'Log' && $function == 'append_log')
			{
				$return[] = $output;
				continue;
			}
			if ($class && $type && $function)
				$output['function'] = $class . $type . $function;
			elseif ($class && $function)
				$output['function'] = $class . ' ' . $function;
			elseif ($class)
				$output['function'] = 'Class: ' . $class;
			elseif ($function)
				$output['function'] = $function;
			if ($args) $output['args'] = $args;
			$return[] = $output;
		}
		return $return;
	}
}
?>