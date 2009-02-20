<?php
	class File {
		public $path = null;
		public $size = null;

		function __construct($params) {
			$this->path = $params['path'];
			$this->size = $params['size'];
		}
	}
?>
