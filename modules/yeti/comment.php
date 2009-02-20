<?php
	class Comment {
		public static function count_for($file_fid)
		{
			
			$query = 'SELECT COUNT(*) FROM xbt_comments AND file_fid = ' . DB::escape($file_fid);

			if($results = DB::query($query) and $result = mysql_fetch_row($results)) {
				return $result[0];
			} else {
				return false;
			}
		}
		
	
		public static function get_comments_for_torrent($file_fid, $page, $window)
		{
			$start = ($page - 1) * $window;

			$query_count = "SELECT COUNT(*) FROM xbt_comments  WHERE file_fid = " . DB::escape($file_fid);
		
			$query_comments = "SELECT * FROM xbt_comments WHERE file_fid = " . DB::escape($file_fid) . " ORDER BY ctime DESC LIMIT " . DB::escape($start) . ", " . DB::escape($window);
			
			if($results_comments = DB::query($query_comments) and $results_count = DB::query($query_count))
			{
				$count = mysql_fetch_row($results_count);
				$count = $count[0];

				if ($count > 0)
				{
					$comments = array();
					while($result = mysql_fetch_array($results_comments))
					{
						$comment = new Comment($result);
						array_push($comments, $comment);
					}
				}
				else $comments = array();

				return array('count' => $count, 'comments' => $comments);
			}
			else return array('count' => 0, 'comments' => array());
		}
	
		public static function find($cid)
		{		
			$query = "SELECT * FROM xbt_comments WHERE mid = " .DB::escape($cid);

			if ($results = DB::query($query) and $results = mysql_fetch_array($results))
			{
				return new Comment($results);
			}
			else return false;
		}		
		
		
		public static function post($file_fid, $body, $user_uid)
		{
			//$body = strip_tags($body, "<br><a><strong><em>");
			// Replace new lines with br, and limit the number of sucessive new
			// lines to 2.
			//$body = nl2br(preg_replace('/\n(\s)*\n/',"\n\n",$body));
			
			$body = sanitize_body_text($body);
			
			// Verify title wasn't garbage
			if (empty($body))
				return array('status' => false, 'message' => 'You must enter a comment');
			
			if (empty($file_fid))
				return array('status' => false, 'message' => 'No torrent was given');
				
			if (empty($user_uid))
				return array('status' => false, 'message' => 'The comment must be from someone');
			
			$query = "INSERT INTO xbt_comments (file_fid, user_uid, body, ctime) VALUES (" . DB::escape($file_fid) . ", " . DB::escape($user_uid) . ",  '" . DB::escape($body) . "', unix_timestamp())";
			
			if ($results = DB::query($query, true))
			{
				return array('status' => true, 'message' => 'Your comment has been posted.');
			}
			else return array('status' => false, 'message' => 'The comment could not be posted at this time.');			
		}
	
		/*
	
			create table if not exists xbt_comments
			(
				cid int not null auto_increment,
				file_fid int not null,
				user_uid int not null,
				body text default null,
				ctime int not null,
				primary key (cid),
				key (file_fid),
				key (user_uid)
			)
		
		*/
		
		// VARIABLES
		private $data = array();
	
		function __construct($params = array())
		{
			$this->cid = $params['cid'];
			$this->body = $params['body'];
			$this->torrent = Torrent::find(array('fid' => $params['file_fid']));
			$this->user = User::find(array('uid' => $params['user_uid']));
			$this->ctime = $params['ctime'];
		}
	
		public function __set($key,$value)
		{
			$this->data[$key] = $value;
		}
	
		public function __get($key)
		{
			switch ($key)
			{
				case 'body_view':
					return $this->data['body'];
				case 'body_edit':
					return br2nl($this->data['body']);
					break;
			}
			if (array_key_exists($key, $this->data))
			{
				return $this->data[$key];
			}
			return null;

	    /*$trace = debug_backtrace();
	    trigger_error(
	        'Undefined property via __get(): ' . $name .
	        ' in ' . $trace[0]['file'] .
	        ' on line ' . $trace[0]['line'],
	        E_USER_NOTICE);
	    return null;*/
		}
				
	}
	
	
	
?>
