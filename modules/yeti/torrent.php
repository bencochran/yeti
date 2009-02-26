<?php
	class Torrent {
		// CLASS FUNCTIONS

		// Find a torrent
		public static function find($params = array('fid' => null)) {	

			$query = "SELECT * FROM xbt_files JOIN xbt_categories ON xbt_categories.cid = xbt_files.category_cid WHERE fid = " . $params['fid'];

			if($results = DB::query($query) and $results = mysql_fetch_array($results)) {
				return new Torrent($results);
			} else {
				return false;
			}
		}

		// Browse a torrent category
		public static function browse($params = array('category_cid' => -1, 'window' => 15, 'page' => 1, 'query' => null, 'mode' => null, 'sort_col' => 'title', 'sort_order' => 'desc')) {

			switch(!is_null($params['query']) and strlen($params['query']) > 1) {
				// no search query was passed
				case false:
					$window = $params['window'];
					$start = ($params['page'] - 1) * $window;

					$query_torrents = "SELECT * FROM xbt_files JOIN xbt_categories ON xbt_categories.cid = xbt_files.category_cid ";
					$query_count = "SELECT COUNT(*) FROM xbt_files ";
					if($params['category_cid'] != -1) {
						$filter_cid = "WHERE category_cid = " . DB::escape($params['category_cid']);
						$query_torrents .= $filter_cid . " AND flags != 1 ";
						$query_count .= $filter_cid . " AND flags != 1 ";
					} else {
						// Don't load or count delete torrents or erotica torrents when browsing all torrents.
						$query_torrents .= "WHERE flags != 1 AND category_cid != 9 ";
						$query_count .= "WHERE flags != 1 AND category_cid != 9 ";
					}
					if (!empty($params['sort_order']) && !empty($params['sort_col']))
					{
						$query_torrents .= "ORDER BY ".DB::escape($params['sort_col'])." ".DB::escape(strtoupper($params['sort_order']))." LIMIT " . DB::escape($start) . ", " . DB::escape($window);
					}
					else
					{
						$query_torrents .= "ORDER BY ctime DESC LIMIT " . DB::escape($start) . ", " . DB::escape($window);
					}


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
					break;

				// a search query was passed
				case true:
					$sphinx = new SphinxClient();
					$sphinx->SetServer(SEARCHD_HOST, SEARCHD_PORT);
					$sphinx->SetMatchMode(SPH_MATCH_ALL);
					if (!empty($params['sort_order']) && !empty($params['sort_col']))
					{
						switch ($params['sort_order'])
						{
							case 'desc':
								$sphinx->SetSortMode(SPH_SORT_ATTR_DESC, $params['sort_col']);
								break;
							case 'asc':
								$sphinx->SetSortMode(SPH_SORT_ATTR_ASC, $params['sort_col']);
								break;
							default:
								// Just in case, let's fall back on a default
								$sphinx->SetSortMode(SPH_SORT_ATTR_DESC, 'ctime');
								break;
						}
					}
					else
					{
						$sphinx->SetSortMode(SPH_SORT_ATTR_DESC, 'ctime');
					}
					$sphinx->SetLimits(($params['page']- 1) * $params['window'], $params['window']);
					if($params['category_cid'] != -1) {
						$sphinx->SetFilter('category_cid', array($params['category_cid']));
					}

					$results = $sphinx->Query($params['query'], 'torrents');

					$torrents = array();
					if($results['total_found'] > 0) {
						foreach($results['matches'] as $hit => $details) {
							$query = "SELECT * FROM xbt_files JOIN xbt_categories ON xbt_categories.cid = xbt_files.category_cid WHERE fid = " . $hit . " AND flags != 1";
							if($result = mysql_fetch_array(DB::query($query))) {
								$torrent = new Torrent($result);
								array_push($torrents, $torrent);
							}
						}

						return array('count' => $results['total_found'], 'torrents' => $torrents);
					} else {
						return false;
					}
					break;
			}
		}

		// Edit torrent
		public static function edit($fid, $title, $description, $category_id, $user) {

			$torrent = Torrent::find(array('fid' => $fid));
			if(!$torrent) {
				return array('status' => false, 'message' => 'The torrent was not found!');
			}

			if(!$torrent->has_access(array('user' => $user))) {
				return array('status' => false, 'message' => 'You are not authorized to edit the specified torrent!');
			}

			if(strlen($title) < 2) {
				return array('status' => false, 'message' => 'The torrent\'s title is too short!');
			}

			if(!Category::find(array('cid' => $category_id))) {
				return array('status' => false, 'message' => 'The specified category does not exist!');
			}

			$description = sanitize_body_text($description);
			$description = DB::escape($description);

			$query = "UPDATE xbt_files SET title = \"" . DB::escape($title) . "\", description = \"" . DB::escape($description) . "\", category_cid = " . DB::escape($category_id) . " WHERE fid = " . DB::escape($fid);

			if(DB::query($query, true)) {
				return array('status' => true, 'message' => 'The torrent was successfully edited. Congratulations!');
			} else {
				return array('status' => false, 'message' => 'The torrent was not successfully edited.');
			}
		}

		// Delete torrent
		public static function delete($fid, $user) {

			$torrent = Torrent::find(array('fid' => $fid));
			if(!$torrent) {
				return array('status' => false, 'message' => 'The torrent was not found!');
			}

			if(!$torrent->has_access(array('user' => $user))) {
				return array('status' => false, 'message' => 'You are not authorized to delete the specified torrent!');
			}

			$query_torrent = "UPDATE xbt_files SET flags = 1 WHERE fid = " . DB::escape($fid);
			$query_paths = "DELETE FROM xbt_paths WHERE torrent_fid = " . DB::escape($fid);

			if(DB::query($query_torrent, true) and DB::query($query_paths, true)) {
				return array('status' => true, 'message' => 'The torrent was successfully deleted. Congratulations!');
			} else {
				return array('status' => false, 'message' => 'The torrent was not deleted.');
			}
		}

		// Upload POSTed torrent
		public static function upload($title, $description, $category_cid, $file, $user) {

			// Verify title wasn't garbage
			if(strlen($title) < 2) {
				return array('status' => false, 'message' => 'The torrent\'s title is too short!');
			}

			// Verify category was set properly
			if(!Category::find(array('cid' => $category_cid))) {
				return array('status' => false, 'message' => 'The specified category does not exist!');
			}

			// Grab the metainfo for the torrent
			$metainfo = new File_Bittorrent2_Decode;
			try {
				$metainfo->decodeFile($file['tmp_name']);
			} catch(File_Bittorrent2_Exception $e) {
				return array('status' => false, 'message' => 'The .torrent file was either invalid or corrupt!');
			}

			// Ensure metainfo is private
			if(!$metainfo->getPrivate()) {
				return array('status' => false, 'message' => 'Your torrent is not flagged as private! Please recreate it as a private torrent before attempting to add it again.');
			}

			// Ensure tracker was set properly
			if(!$metainfo->getAnnounce() == $user->url_announce) {
				return array('status' => false, 'message' => 'The announce URL is not properly set in the torrent!');
			}

			// Ensure metainfo is unique
			$info_hash = '0x' . $metainfo->getInfoHash();
			$query = 'SELECT * FROM xbt_files WHERE info_hash = ' . $info_hash;
			$results = DB::query($query);
			if(mysql_num_rows($results) > 0) {
				return array('status' => false, 'message' => 'Duplicate torrent detected! Either you or somebody else already added it.');
			}

			// Grab basic metainfo properties
			$path = DB::escape(basename($file['name']));
			$title = DB::escape($title);

			$description = sanitize_body_text($description);
			$description = DB::escape($description);

			$size = 0;
			$user_uid = $user->uid;

			// Get total torrent size
			foreach($metainfo->getFiles() as $f) {
				$size += $f['size'];
			}

			// Insert metainfo into database
			$query = "INSERT INTO xbt_files (info_hash, mtime, ctime, path, title, description, size, user_uid, category_cid) VALUES (" . $info_hash . ", unix_timestamp(), unix_timestamp(), '" . $path . "', '" . $title . "', '" . $description . "', " . $size . ", " . $user_uid . ", " . $category_cid . ")";
			$results = DB::query($query, true);

			// Grab the metainfo_id of the newly inserted metainfo
			$handle = DB::get_handle();
			$metainfo_id = mysql_insert_id($handle);

			foreach($metainfo->getFiles() as $f) {
				$f_path = DB::escape($f['filename']);
				$f_size = DB::escape($f['size']);

				$query = "INSERT INTO xbt_paths (torrent_fid, path, size) VALUES (" . $metainfo_id . ", '" . $f_path . "', " . $f_size . ")";
				$results = DB::query($query,true);
			}

			// Move .torrent to path using fid as filename
			$dest = TORRENT_BASE_PATH . '/' . $metainfo_id . '.torrent';
			move_uploaded_file($file['tmp_name'], $dest);

			return array('status' => true, 'message' => 'The torrent has been successfully uploaded. Congratulations!', 'id' => $metainfo_id);
		}

		// INSTANCE VARIABLES
		private $data = array();
/*		public $fid = null;
		public $path = '';
		public $title = '';
		public $description = '';
		public $user = null;
		public $size = 0;
		public $leechers = 0;
		public $seeders = 0;
		public $completed = 0;
		public $category = null;
		public $ctime = null;
		public $mtime = null;
		public $url_edit = '';
		public $url_delete = '';
		public $file_count = 0;*/

		// OBJECT FUNCTIONS

		// Constructor
		function __construct($params) {

			$this->fid = $params['fid'];
			$this->path = $params['path'];
			$this->title = $params['title'];
			$this->description = stripcslashes($params['description']);
			$this->user = User::find(array('uid' => $params['user_uid']));
			$this->size = $params['size'];
			$this->leechers = $params['leechers'];
			$this->seeders = $params['seeders'];
			$this->completed = $params['completed'];
			$this->category = Category::find(array('cid' => $params['category_cid']));
			$this->ctime = $params['ctime'];
			$this->mtime = $params['mtime'];
			$this->url_edit = WWW_BASE_PATH . '/torrent/'.$this->fid.'/edit';
			$this->url_delete = WWW_BASE_PATH . '/torrent/'.$this->fid.'/delete';
			
			$query_file_count = "SELECT COUNT(*) FROM xbt_paths WHERE torrent_fid = " . $this->fid;
			$results_file_count = DB::query($query_file_count);
			$file_count = mysql_fetch_row($results_file_count);
			$file_count = $file_count[0];
			$this->file_count = $file_count;
			
		}
		
		public function __set($key,$value)
		{
			$this->data[$key] = $value;
		}
	
		public function __get($key)
		{
			switch ($key)
			{
				case 'description_view':
					return $this->data['description'];
				case 'description_edit':
					return br2nl($this->data['description']);
					break;
			}
			if (array_key_exists($key, $this->data))
			{
				return $this->data[$key];
			}
			return null;
		}
		

		function has_access($params = array('user' => null))
		{
			if (empty($params['user'])) return false;
			return ($params['user']->uid == $this->user->uid || $params['user']->admin);
		}

		// Return array of torrent's files
		function files($params = array('page' => null, 'window' => null)) {
			
			$query_count = "SELECT COUNT(*) FROM xbt_paths WHERE torrent_fid = " . $this->fid;

			$query_files = "SELECT * FROM xbt_paths WHERE torrent_fid = " . $this->fid;
			$offset = ($params['page'] - 1) * $params['window'];
			$query_files  .= ' LIMIT ' . $offset . ', ' . $params['window'];

			if($results_files = DB::query($query_files) and $results_count = DB::query($query_count)) {
				$count = mysql_fetch_row($results_count);
				$count = $count[0];

				$files = array();
				while($result = mysql_fetch_array($results_files)) {
					$file = new File($result);
					array_push($files, $file);
				}

				return array('count' => $count, 'files' => $files);
			} else {
				return false;
			}
		}

		function comments($page, $window)
		{
			return Comment::get_comments_for_torrent($this->fid, $page, $window);
		}

		// Return array of torrent's seeders
		function seeders() {

			$query = "SELECT xbt_users.uid FROM xbt_files JOIN xbt_files_users ON xbt_files.fid = xbt_files_users.fid JOIN xbt_users ON xbt_users.uid = xbt_files_users.uid WHERE xbt_files_users.active = 1 AND xbt_files_users.left = 0 AND xbt_files.fid = " . $this->fid;

			if($results = DB::query($query)) {
				$users = array();
				while($result = mysql_fetch_row($results) and $result = $result[0]) {
					$user = User::find(array('uid' => $result));
					array_push($users, $user);
				}

				return array('count' => count($users), 'users' => $users);
			} else {
				return false;
			}
		}

		// Return array of torrent's seeders
		function leechers() {
			
			$query = "SELECT xbt_users.uid FROM xbt_files JOIN xbt_files_users ON xbt_files.fid = xbt_files_users.fid JOIN xbt_users ON xbt_users.uid = xbt_files_users.uid WHERE xbt_files_users.active = 1 AND xbt_files_users.left > 0 AND xbt_files.fid = " . $this->fid;

			if($results = DB::query($query)) {
				$users = array();
				while($result = mysql_fetch_row($results) and $result = $result[0]) {
					$user = User::find(array('uid' => $result));
					array_push($users, $user);
				}

				return array('count' => count($users), 'users' => $users);
			} else {
				return false;
			}
		}

		// Return data payload for torrent unique to active user
		function unique_for($params = array('user' => null)) {

			$original_payload = new File_Bittorrent2_Decode();
			$original_payload->decodeFile(TORRENT_BASE_PATH . '/' . $this->fid . '.torrent');
			$original_payload = $original_payload->decoded;
			$user = $params['user'];
			$original_payload['announce'] = $user->url_announce;
			$unique_payload = new File_Bittorrent2_Encode();
			$unique_payload = $unique_payload->encode($original_payload);

			return $unique_payload;
		}
	}
?>
