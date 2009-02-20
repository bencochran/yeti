<?php

	class NewsPost
	{
		
		public static function get_posts($page = 1, $window = 20)
		{
			$start = ($page - 1) * $window;

			$query_count = "SELECT COUNT(*) FROM xbt_news  WHERE is_visible = 1 AND ctime < unix_timestamp()";
		
			$query_posts = "SELECT * FROM xbt_news WHERE is_visible = 1 AND ctime < unix_timestamp() ORDER BY ctime DESC LIMIT " . DB::escape($start) . ", " . DB::escape($window);

			if($results_posts = DB::query($query_posts) and $results_count = DB::query($query_count))
			{
				$count = mysql_fetch_row($results_count);
				$count = $count[0];

				$posts = array();
				while($result = mysql_fetch_array($results_posts))
				{
					$post = new NewsPost($result);
					array_push($posts, $post);
				}

				return array('count' => $count, 'posts' => $posts);
			}
			else return array('count' => 0, 'messages' => array());
		}
		
		
		
		/*
	
			create table if not exists xbt_news
			(
				nid int not null auto_increment,
				user_uid int not null,
				title varchar(255) default null,
				body text default null,
				is_visible int not null default '0',
				ctime int not null,
				primary key (nid)
			)
		
		*/
		
		// VARIABLES
		private $data = array();
	
		function __construct($params = array())
		{
			$this->nid = $params['nid'];
			$this->title = $params['title'];
			$this->body = $params['body'];
			$this->user = User::find(array('uid' => $params['user_uid']));
			$this->ctime = $params['ctime'];
			$this->visible = ($this->ctime < time());
		}
	
		public function __set($key,$value)
		{
			$this->data[$key] = $value;
		}
	
		public function __get($key)
		{
			switch ($key)
			{
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