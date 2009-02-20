<?php
	class Category {
		public static function find_all() {

			$query = "SELECT * FROM xbt_categories ORDER BY cid ASC";

			if($results = DB::query($query)) {
				$categories = array();
				while($result = mysql_fetch_array($results)) {
					$category = new Category($result);
					array_push($categories, $category);
				}
				return $categories;
			} else {
				return false;
			}
		}

		public static function find($params = array('cid' => null, 'name' => null, 'slug' => null)) {

			if($params['cid']) {
				$query = "SELECT * FROM xbt_categories WHERE cid = " . DB::escape($params['cid']);
			} elseif($params['name']) {
				$query = "SELECT * FROM xbt_categories WHERE name = '" . DB::escape($params['name'])."'";
			} elseif($params['slug']) {
				$query = "SELECT * FROM xbt_categories WHERE slug = '" . DB::escape($params['slug'])."'";
			} else {
				return false;
			}

			if($results = DB::query($query) and $result = mysql_fetch_array($results)) {
				return new Category($result);
			} else {
				return false;
			}
		}

		public $cid = null;
		public $name = null;
		public $slug = null;

		function __construct($params) {
			$this->cid = $params['cid'];
			$this->name = $params['name'];
			$this->slug = $params['slug'];
		}
	}
?>
