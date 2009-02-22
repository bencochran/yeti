<?php
	class User {
		// CLASS FUNCTIONS

		public static $active_user;

		// Is the user logged in?
		public static function logged_in() {	
			return (self::get_active_user());
		}

		public static function require_active_user()
		{
			if ($user = self::get_active_user())
			{
				if (defined('ADMIN_ONLY') && ADMIN_ONLY)
				{
					if ($user->admin)
						return $user;
					else
					{
						buffer_end_clean();
						Flash::set('failure', SITE_NAME.' is currently locked, you must be an admin to login.');
						//throw new Lvc_Exception('Non admin ('.$user->name.') tried to login during lock.');
						//$_SESSION['flash']['referrer'] = $_SERVER['REQUEST_URI'];
						header('Location: /locked');
						die();
					}
				}
				else
					return $user;
			}
			else
			{
				buffer_end_clean();
				Flash::set('failure', 'You must first login.');
				$_SESSION['flash']['referrer'] = $_SERVER['REQUEST_URI'];
				header('Location: /user/login');
				die();
			}
		}
		
		public static function require_admin()
		{
			if ($user = self::get_active_user())
			{
				if ($user->admin)
					return $user;
				else
					throw new Lvc_Exception('Non admin ('.$user->name.') tried to access admin stuff.', 403);
			}
			else
			{
				buffer_end_clean();
				$_SESSION['flash']['referrer'] = $_SERVER['REQUEST_URI'];
				header('Location: /user/login');
				die();
			}
			
		}

		public static function get_active_user()
		{
			if (empty(self::$active_user))
			{
				if(isset($_SESSION['user_uid']))
				{
					self::$active_user = User::get_user_and_update($_SESSION['user_uid']);
				}
				if (empty(self::$active_user))
				{
					return false;
				}
			}
			return self::$active_user;
		}
		

		public static function admin_logged_in()
		{
			$user = self::get_active_user();
			return $user->admin;
		}

		// Returns a user based on id and updates that user's last_seen time
		public static function get_user_and_update($uid)
		{
			$query = "SELECT * FROM xbt_users WHERE uid = ".DB::escape($uid)." LIMIT 1";
			if($results = DB::query($query) and $results = mysql_fetch_array($results)) {
				DB::query("UPDATE xbt_users SET last_seen = UNIX_TIMESTAMP() WHERE uid = ".DB::escape($uid)." LIMIT 1;");
				return new User($results);
			} else {
				return false;
			}
			
		}

		// Return a user based on specified uid or name
		public static function find($params = array('uid' => null, 'name' => null, 'exclude_hidden' => false)) {
			if(isset($params['uid'])) {
				$query = "SELECT * FROM xbt_users WHERE uid = " . DB::escape($params['uid']);
			} elseif(isset($params['name'])) {
				$query = "SELECT * FROM xbt_users WHERE name = '" . DB::escape($params['name']) . "'";
			}
			else return false;
			if ($params['exclude_hidden'])
			{
				$query .= ' AND shown=1';
			}			
			
			if($results = DB::query($query) and $results = mysql_fetch_array($results)) {
				return new User($results);
			} else {
				return false;
			}
		}

		public static function get_autocomplete($start, $include_hidden = false, $active_user = null)
		{
			$query = "SELECT name FROM xbt_users WHERE name LIKE '" . DB::escape($start) . "%'";
			if (!$include_hidden)
			{
				$query .= ' AND (shown=1';
				if (!empty($active_user))
				{
					$query .= " OR name='". $active_user->name ."'";
				}
				$query .= ')';
			}
			$query .= ' ORDER BY name';
			
			if($results = DB::query($query))
			{
				$return = array();
				while ($row = mysql_fetch_array($results))
				{
				    $return[] = $row[0];
				}
				
				return $return;
			}
			else return array();
			
		}
		
		// Count the number of registered+activated users
		public static function count() {
			$query = "SELECT COUNT(*) FROM xbt_users WHERE is_activated = 1";

			$results = DB::query($query);
			$count = mysql_fetch_row($results);
			$count = $count[0];
			return $count;
		}
		
		// Number of active seeding users:
		// select count(distinct uid) from xbt_files_users where active = 1;

		// Log the user in
		public static function login($username, $password) {
			
			// Identify needed data
			$password = md5($password . SALT_PASSWORD);

			// Escape strings
			$username = DB::escape($username);
			$password = DB::escape($password);

			// Is user registered?
			$query = "SELECT * FROM xbt_users WHERE name = '" . $username . "' AND pass = '" . $password . "' AND is_activated = 1";
			
			if($results = DB::query($query) and $results = mysql_fetch_array($results)) {
				$user = new User($results);
				$_SESSION['user_uid'] = $user->uid;
				$worked = setcookie('YetiUser',$user->name, 0,'/',COOKIE_DOMAIN);
				return array('status' => true, 'message' => 'You successfully logged in!');
			} else {
				return array('status' => false, 'message' => 'Login failed! Either the username or password was incorrect, or your account has not yet been activated.');
			}
		}

		// Log the user out
		public static function logout() {
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', -1, '/');
				setcookie('YetiUser', '', -1, '/');
			}
			session_destroy();
		}


		public static function validate_email($email) {
			
			// General regular expression email check
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) { 
				return array('status' => false, 'message' => 'The e-mail address you provided doesn\'t seem to be valid.');
			}
			
			// Domain-limiting regular expression email check.
			// Uncomment this and edit the domain name to only allow 
			// registrations from a certain domain name.
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@carleton.edu",$email)) {
				return array('status' => false, 'message' => 'You don\'t seem to be a Carleton student.');
			}
			
			// Check LDAP.
			// If you want to enable LDAP-validation of users, you'll need to 
			// edit modules/yet/ldap.php to fit your needs.
			$result = LDAP::check_address($email);
			if (!$result['status'])
			{
				$body = 'An invalid email attempted to register. The following message resulted:'."\r\n";
				$body .= $result['message'];
				
				mail_send(CONTACT_EMAIL, $body, 'Invalid Email', CONTACT_EMAIL, EMAIL_FROM_NAME);
				return array('status' => false, 'message' => 'You don\'t seem to be allowed to register.');
			}
			
			return array('status' => true);
			
		}

		// Register the user
		public static function register($email, $username, $password, $password_match) {
			
			$invalid_usernames = array('login', 'logout', 'register', 'view', 'list',
																	'add', 'edit', 'delete', 'create', 'update',
																	'remove', 'activate','autocomplete');

			$email_validate = self::validate_email($email);
			
			if (!$email_validate['status'])
			{
				return $email_validate;
			}

			// Username too short?
			if(strlen($username) < 3) {
				return array('status' => false, 'message' => 'The username you requested is too short. It must be at least 3 characters.');
			}
			
			if(in_array($username,$invalid_usernames)) {
				return array('status' => false, 'message' => 'The username you requested is invalid.');
			}

			if(!eregi("^[_a-z0-9-]+$", $username)) { 
				return array('status' => false, 'message' => 'The username you requested is invalid.');
			}
			

			// Password too short?
			if(strlen($password) < 8) {
				return array('status' => false, 'message' => 'The password you requested is too short. It must be at least 8 characters.');
			}

			// Password doesn't match?
			if($password != $password_match) {
				return array('status' => false, 'message' => 'The passwords you provided did not match.');
			}

			// Identify passwords and codes
			$password = md5($password . SALT_PASSWORD);
			$torrent_pass = md5($email . SALT_TORRENT_PASS);
			$code = md5($email . SALT_CODE);

			// Escape strings
			$username = DB::escape($username);
			$email = DB::escape($email);
			$password = DB::escape($password);
			$torrent_pass = DB::escape($torrent_pass);
			$code = DB::escape($code);

			// Is user already registered?
			$query = "SELECT name FROM xbt_users WHERE name = '" . $username . "'";
			$results = DB::query($query, true);
			if(mysql_num_rows($results) > 0) {
				return array('status' => false, 'message' => 'The username you requested is already in use.');
			}

			// Is e-mail already used?
			$query = "SELECT name FROM xbt_users WHERE email = '" . $email . "'";
			$results = DB::query($query, true);
			if(mysql_num_rows($results) > 0) {
				return array('status' => false, 'message' => 'The e-mail address you specified has already been used to register.');
			}

			// Register user
			$query = "INSERT INTO xbt_users (name, email, pass, ctime, can_leech, wait_time, peers_limit, torrents_limit, torrent_pass, torrent_pass_secret, downloaded, uploaded, code, is_activated) VALUES ('" . $username . "', '" . $email . "', '" . $password . "', UNIX_TIMESTAMP(), 1, 0, 0, 0, '" . $torrent_pass . "', 0, 0, 0, '" . $code . "', 0)";
			if(DB::Query($query, true)) {
				$fromname = EMAIL_FROM_NAME;
				$fromaddress = EMAIL_FROM;
				$to = $email;
				$subject = "Activate your Massive account!";
				$body = "Hi " . $username . "!\r\n\r\nThanks for registering with ".SITE_NAME.". In order to use ".SITE_NAME.", you first need to activate ";
				$body .= "your account by visiting the link below:\r\n\r\n";
				$body .= "Activation Link: http:" . WWW_BASE_PATH . "user/".$username."/activate/" . $code . "\r\n\r\n";
				$body .= "Once you've activated, the following details will get you in:\r\n\r\n";
				$body .= "URL: http:" . WWW_BASE_PATH . "\r\nUsername: " . $username . "\r\nPassword: <encrypted>\r\n\r\n";
				$body .= "Regards,\r\nThe Massive Staff";

				// Attempt to send the registration e-mail
				if(mail_send($to, $body, $subject, $fromaddress, $fromname)) {
					return array('status' => true, 'message' => 'Your account was created! Please check for an activation e-mail at the address you provided.');
				} else {
					return array('status' => false, 'message' => 'Your account was created, but your activation e-mail could not be sent at this time. An administrator will contact you within three days to activate your account.');
				}
			} else {
				return array('status' => false, 'message' => 'Your account could not be created at this time.');
			}
		}

		// Activate user based on activation code
		function activate($name,$code) {

			$name = DB::escape($name);
			$code = DB::escape($code);


			// Is user in the database?
			$query = "SELECT name FROM xbt_users WHERE name = '" . $name . "' AND code = '" . $code . "'";
			$results = DB::query($query,true);
			if(mysql_num_rows($results) == 0) {
				return array('status' => false, 'message' => 'The activation code you used was invalid!');
			} else {
				$query = "UPDATE xbt_users SET is_activated = 1, code = NULL WHERE name = '" . $name . "' AND code = '" . $code . "' AND is_activated = '0'";
				$results = DB::query($query);
				if($results) {
					return array('status' => true, 'message' => 'Your account was activated! Try logging in!');
				} else {
					return array('status' => false, 'message' => 'Your account could not be activated for an unknown reason!');
				}
			}
		}

		// Deactivate a user. Remove their password, make them hidden, and set them is_activated = 0
		function deactivate($name)
		{
			$query = "UPDATE xbt_users SET is_activated = 0, pass = '".md5(microtime().'nopassword')."', code = '".md5(microtime().'noactivate')."', shown = 0 WHERE name = '".$name."'";
			
			$results = DB::query($query,true);
			if($results)
			{
				return array('status' => true, 'message' => 'User deactivated!');
			}
			else
			{
				return array('status' => false, 'message' => 'Could not deactivate '.$name.'!');
			}
			
			
			
			//var_dump($query);
		}
		
		function request_reset($user_or_email)
		{
			$user_or_email = DB::escape($user_or_email);
			$query = "SELECT * FROM xbt_users WHERE (email = '".$user_or_email."' OR name = '".$user_or_email."') AND is_activated = 1";
			
			if($results = DB::query($query) and $results = mysql_fetch_array($results))
			{
				$user = new User($results);
				$reset_code = md5($user->email . SALT_RESET_CODE);
				
				$query = "UPDATE xbt_users SET wants_reset = 1, wants_reset_at=UNIX_TIMESTAMP(), reset_code = '".$reset_code."' WHERE uid = " . $user->uid;

				$result = DB::query($query,true);
				if($result)
				{
					$fromname = EMAIL_FROM_NAME;
					$fromaddress = EMAIL_FROM;
					$to = $user->email;
					$subject = "Reset your Massive password!";
					$body = "Hi " . $user->name . "!\r\n\r\nI understand that you've forgotten your password. No worries, ";
					$body .= "you can simply reset it by visiting the link below within 3 days:\r\n\r\n";
					$body .= "Reset Link: http:" . WWW_BASE_PATH . "user/".$user->name."/reset/" . $reset_code . "\r\n\r\n";
					$body .= "There, simply choose a new password and login.\r\n\r\n";
					$body .= "If you've remembered your password already or you didn't request a reset, no worries, simply ";
					$body .= "disregard this email and your password will remain unchanged.\r\n\r\n";
					$body .= "Regards,\r\nThe Massive Staff";

					// Attempt to send the reset e-mail
					if(mail_send($to, $body, $subject, $fromaddress, $fromname)) {
						return array('status' => true, 'message' => 'An email has been sent to the address on file. Please check it and follow the instructions to reset your password.');
					} else {
						return array('status' => false, 'message' => 'The reset e-mail could not be sent at this time. An administrator will contact you within three days to help you out.');
					}
				}
				else
				{
					return array('status' => false, 'message' => 'Your request could not be completed for an unknown reason! Oops.');
				}
			}
			else 
			{
				return array('status' => false, 'message' => 'Could not find the username or email address you provided.');
			}
		
		}
		
		function check_reset_code($username, $reset_code)
		{
			$username = DB::escape($username);
			$reset_code = DB::escape($reset_code);
			
			$query = "SELECT name FROM xbt_users WHERE name = '" . $username . "' AND reset_code = '" . $reset_code . "' AND wants_reset = 1 AND UNIX_TIMESTAMP() < UNIX_TIMESTAMP(FROM_UNIXTIME(wants_reset_at) + INTERVAL 3 DAY)";
			
			$results = DB::query($query,true);
			if(mysql_num_rows($results) == 0)
			{
				return array('status' => false, 'message' => 'The reset code you used was invalid or expired!');
			}
			else
			{
				return array('status' => true, 'message' => 'That reset code is valid. However no action has been taken yet.');
			}
		}
		
		function process_reset($username, $reset_code, $password, $password_match)
		{
			// Password too short?
			if(strlen($password) < 8) {
				return array('status' => false, 'message' => 'The password you requested is too short. It must be at least 8 characters.');
			}

			// Password doesn't match?
			if($password != $password_match) {
				return array('status' => false, 'message' => 'The passwords you provided did not match.');
			}

			// Identify passwords and codes
			$password = md5($password . SALT_PASSWORD);
			
			
			$username = DB::escape($username);
			$reset_code = DB::escape($reset_code);
			$password = DB::escape($password);

			$query = "UPDATE xbt_users SET pass = '".$password."', wants_reset = 0, wants_reset_at = NULL WHERE name = '" . $username . "' AND is_activated = 1 AND wants_reset = 1 AND reset_code = '" . $reset_code . "' AND UNIX_TIMESTAMP() < UNIX_TIMESTAMP(FROM_UNIXTIME(wants_reset_at) + INTERVAL 3 DAY)";
			
			$result = DB::query($query, true);
			
			if($result)
			{
				return array('status' => true, 'message' => 'Your password has been reset. Please log in using this new password.');
			}
			else
			{
				return array('status' => false, 'message' => 'Your password could no be reset at this time.');
			}

		}
		
		
		// VARIABLES
		private $data = array(
			
			);
		/*
		public $uid = null;
		public $name = '';
		public $ratio = '';
		public $ratio_warn = false;
		public $uploaded = 0;
		public $downloaded = 0;
		public $torrent_pass = '';
		public $url_announce = '';
		public $url_profile = '';
		public $url_ratio = '';
		public $url_inbox = '';
		public $unread_messages = 0;
		public $admin = false;
		*/
		// CONSTRUCTOR
		function __construct($params = array()) {	

			$this->uid = $params['uid'];
			$this->name = $params['name'];
			$this->email = $params['email'];

			// Calculate ratio
			$this->downloaded = $params['downloaded'];
			$this->uploaded = $params['uploaded'];
			if($this->downloaded == 0) {
				$this->ratio = '&infin;';
			} else {
				$this->ratio = round($this->uploaded / $this->downloaded, 2);
				if($this->ratio < 0.20) {
					$this->ratio_warn = true;
				}
				else
					$this->ratio_warn = false;
			}

			$this->torrent_pass = $params['torrent_pass'];
			$this->url_announce = TRACKER_BASE_PATH . $this->torrent_pass . '/announce';
			$this->url_profile = WWW_BASE_PATH . 'user/' . $this->name;
			$this->url_ratio = $this->url_profile . '#ratio';
			$this->url_inbox = WWW_BASE_PATH . 'message/inbox';
			//$this->unread_messages = Message::count_unread_for($this->uid);
			$this->shown = ($params['shown'] == 1);
			$this->admin = ($params['admin'] == 1);
			$this->active = ($_SESSION['user_uid'] == $this->uid);
			$this->ctime = $params['ctime'];
			$this->last_seen = $params['last_seen'];
			
		}
		
		public function __set($key,$value)
		{
			$this->data[$key] = $value;
		}
		
		public function __get($key)
		{
			switch ($key)
			{
				case 'display_name':
					if ($this->shown || $this->active || User::admin_logged_in()) return $this->name;
					else return 'Anonymous';
					break;
				case 'byline':
					if ($this->shown || $this->active || User::admin_logged_in())
						return '<a href="'.WWW_BASE_PATH.'user/'.$this->name.'">'.$this->display_name.'</a>';
					else
						return '<em>'.$this->display_name.'</em>';
					break;
				case 'unread_messages':
					return Message::count_unread_for($this->uid);
					break;
				//case 'profile_url':
				//	if ($this->shown || User::admin_logged_in()) return WWW_BASE_PATH . 'user/' . $this->name;
				//	else return null;
				//	break;
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

		public function torrents_added($params = array('page' => 1, 'window' => 15)) {

			$window = $params['window'];
			$start = ($params['page'] - 1) * $window;

			$query_torrents = "SELECT xbt_files.* FROM xbt_users JOIN xbt_files ON xbt_users.uid = xbt_files.user_uid JOIN xbt_categories ON xbt_categories.cid = xbt_files.category_cid WHERE xbt_users.uid = " . $this->uid . " ORDER BY xbt_files.ctime DESC LIMIT " . DB::escape($start) . ", " . DB::escape($window);
			$query_count = "SELECT COUNT(*) FROM xbt_files WHERE user_uid = " . $this->uid;

			if($results_torrents = DB::query($query_torrents) and $results_count = DB::query($query_count)) {
				$count = mysql_fetch_row($results_count);
				$count = $count[0];

				$torrents = array();
				while($result = mysql_fetch_array($results_torrents)) {
					$torrent = new Torrent($result);
					array_push($torrents, $torrent);
				}

				return array('count' => $count, 'torrents' => $torrents);
			} else {
				return false;
			}
		}

		public function torrents_active($params = array('page' => 1, 'window' => '15')) {

			$window = $params['window'];
			$start = ($params['page'] - 1) * $window;


			$query_torrents = "SELECT xbt_files.* FROM xbt_users JOIN xbt_files_users ON xbt_users.uid = xbt_files_users.uid JOIN xbt_files ON xbt_files_users.fid = xbt_files.fid JOIN xbt_categories ON xbt_categories.cid = xbt_files.category_cid WHERE xbt_files_users.active = 1 AND xbt_users.uid = " . $this->uid . ' ORDER BY xbt_files.title ASC LIMIT ' . DB::escape($start) . ", " . DB::escape($window);
			$query_count = "SELECT COUNT(*) FROM xbt_users JOIN xbt_files_users ON xbt_users.uid = xbt_files_users.uid JOIN xbt_files ON xbt_files_users.fid = xbt_files.fid WHERE xbt_files_users.active = 1 AND xbt_users.uid = " . $this->uid;

			if($results_torrents = DB::query($query_torrents) and $results_count = DB::query($query_count)) {
				$count = mysql_fetch_row($results_count);
				$count = $count[0];

				$torrents = array();
				while($result = mysql_fetch_array($results_torrents)) {
					$torrent = new Torrent($result);
					array_push($torrents, $torrent);
				}

				return array('count' => $count, 'torrents' => $torrents);
			} else {
				return false;
			}
		}
	}
?>
