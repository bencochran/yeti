<?php
	class Message {
		public static function count_unread_for($user_uid)
		{
			
			$query = 'SELECT COUNT(*) FROM xbt_messages WHERE is_read = 0 AND to_user_uid = ' . DB::escape($user_uid);

			if($results = DB::query($query) and $result = mysql_fetch_row($results)) {
				return $result[0];
			} else {
				return false;
			}
		}
		
	
		public static function get_messages_to_user($params = array('user' => null, 'page' => 1, 'window' => 20))
		{
			$window = $params['window'];
			$start = ($params['page'] - 1) * $window;

			$query_count = "SELECT COUNT(*) FROM xbt_messages  WHERE to_user_uid = " . $params['user'];
		
			$query_messages = "SELECT * FROM xbt_messages WHERE to_user_uid = " . $params['user'] . " ORDER BY ctime DESC LIMIT " . DB::escape($start) . ", " . DB::escape($window);

			if($results_messages = DB::query($query_messages) and $results_count = DB::query($query_count))
			{
				$count = mysql_fetch_row($results_count);
				$count = $count[0];

				$messages = array();
				while($result = mysql_fetch_array($results_messages))
				{
					$message = new Message($result);
					array_push($messages, $message);
				}

				return array('count' => $count, 'messages' => $messages);
			}
			else return array('count' => 0, 'messages' => array());
		}
	
		public static function find($params = array('mid'=>null, 'user'=>null))
		{		
			$query = "SELECT * FROM xbt_messages WHERE mid = " . $params['mid'];

			if ($results = DB::query($query) and $results = mysql_fetch_array($results))
			{
				$message = new Message($results);
				if ($message->has_access($params['user'])) return $message;
				else return false;
			}
			else return false;
		}		
		
		public static function send($to_user, $subject, $body, $from_user)
		{
			$subject = strip_tags($subject);
			$body = strip_tags($body, "<br><a><strong><em>");
			
			// Verify title wasn't garbage
			if (empty($title) && empty($body))
				return array('status' => false, 'message' => 'You must enter a subject and a body');
			
			if (empty($to_user))
				return array('status' => false, 'message' => 'You must select a recipient');
				
			if (empty($from_user))
				return array('status' => false, 'message' => 'The message must be from someone');
			
			if ($to_user->uid == $from_user->uid)
				return array('status' => false, 'message' => 'You cannot send yourself a message');
			
			$subject = DB::escape($subject);
			$body = DB::escape($body);
			
			$query = "INSERT INTO xbt_messages (from_user_uid, to_user_uid, subject, body, ctime) VALUES (" . $from_user->uid . ", " . $to_user->uid . ", '" . $subject .  "', '" . $body . "', unix_timestamp())";
			
			if ($results = DB::query($query, true))
			{
				return array('status' => true, 'message' => 'Your message has been sent.');
			}
			else return array('status' => false, 'message' => 'The message could not be sent at this time.');
			
			
			
		}
	
		/*
	
			create table if not exists xbt_messages
			(
				mid int not null auto_increment,
				from_user_uid int not null,
				to_user_uid int not null,
				subject varchar(255) default null,
				body text default null,
				ctime int not null,
				is_read int not null default '0',
				primary key (mid),
				key (to_user_uid),
				key (from_user_uid)
			)
		
		*/
	
		// VARIABLES
		private $data = array();
	
		function __construct($params = array())
		{
			$this->mid = $params['mid'];
			$this->subject = $params['subject'];
			$this->body = $params['body'];
			$this->to_user = User::find(array('uid' => $params['to_user_uid']));
			$this->from_user = User::find(array('uid' => $params['from_user_uid']));
			$this->ctime = $params['ctime'];
			$this->read = ($params['is_read'] == 1);
		}
	
		public function __set($key,$value)
		{
			$this->data[$key] = $value;
		}
	
		public function __get($key)
		{
			switch ($key)
			{
				case 'body':
					return strip_tags($this->data['body']);
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
		
		function has_access($user)
		{
			if (empty($user)) return false;
			return ($user->uid == $this->to_user->uid || $user->uid == $this->from_user->uid || $user->admin);
		}
		
		function mark_read()
		{
			$this->read = true;
			
			$query = "UPDATE xbt_messages SET is_read = 1 WHERE mid = " . $this->mid;
			
			DB::query($query, true);
			
		}
		
	}
	
	
	
?>
