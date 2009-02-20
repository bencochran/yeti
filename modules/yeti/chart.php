<?php
	class Chart {
		
		// Return an array of torrents based on number of files
		public static function most_files($params = array('limit' => 10)) {

			$query = "SELECT xbt_files.*, COUNT(*) AS count FROM xbt_files JOIN xbt_paths ON xbt_files.fid = xbt_paths.torrent_fid WHERE xbt_files.flags = '0' GROUP BY xbt_paths.torrent_fid ORDER BY count DESC LIMIT " . DB::escape($params['limit']);

			if($results = DB::query($query)) {
				$torrents = array();

				while($result = mysql_fetch_array($results)) {
					$torrent = new Torrent($result);
					array_push($torrents, $torrent);
				}

				return $torrents;
			} else {
				return false;
			}
		}
		
		public static function currently_seeding($user = null, $limit = null)
		{
			$query_count = "SELECT DISTINCT xbt_users.* FROM xbt_users JOIN xbt_files_users ON xbt_users.uid = xbt_files_users.uid WHERE xbt_files_users.active = 1";
			$query = "SELECT DISTINCT xbt_users.* FROM xbt_users JOIN xbt_files_users ON xbt_users.uid = xbt_files_users.uid WHERE xbt_files_users.active = 1";
			if (!User::admin_logged_in())
			{
				$query .= " WHERE shown=1";
				if (!empty($user)) $query .= " OR uid=".$user->uid;
			}
			$query .= " ORDER BY uploaded DESC";
			if (!empty($limit))
			{
				$query .= " LIMIT " . DB::escape($limit);
			}
			
			if($results = DB::query($query)) {
				$users = array();

				while($result = mysql_fetch_array($results)) {
					$user = new User($result);
					array_push($users, $user);
				}

				return array('users' => $users);
			} else {
				return false;
			}
		}
		
		// Return an array of users based on the amount uploaded
		public static function top_uploaders($params = array('limit' => 10, 'user'=>null)) {

			$query = "SELECT * FROM xbt_users";
			if (!User::admin_logged_in()) 
			{
				$query .= " WHERE shown=1";
				if (!empty($params['user'])) $query .= " OR uid=".$params['user']->uid;
			}
			$query .= " ORDER BY uploaded DESC LIMIT " . DB::escape($params['limit']);

			if($results = DB::query($query)) {
				$users = array();

				while($result = mysql_fetch_array($results)) {
					$user = new User($result);
					array_push($users, $user);
				}

				return $users;
			} else {
				return false;
			}
		}

		// Return an array of torrents based on number of snatches
		public static function most_snatched($params = array('limit' => 10)) {

			$query = "SELECT * FROM xbt_files ORDER BY completed DESC LIMIT " . DB::escape($params['limit']);

			if($results = DB::query($query)) {
				$torrents = array();

				while($result = mysql_fetch_array($results)) {
					$torrent = new Torrent($result);
					array_push($torrents, $torrent);
				}

				return $torrents;
			} else {
				return false;
			}
		}
	}
?>
