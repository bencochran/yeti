<?php
class ChartController extends AppController
{	
	public function actionFiles()
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		$this->setLayoutVar('pageTitle','Charts');
		$this->setLayoutVar('pageHead','Charts &mdash; Torrents With the Most Files');
		
		$most_files = Chart::most_files(array('limit' => 10));
		$this->setVar('most_files', $most_files);
	}
	
	public function actionIndex()
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		$this->setLayoutVar('pageTitle','Charts');
		$this->setLayoutVar('pageHead','Charts');
		
		$most_snatched = Chart::most_snatched(array('limit' => 10));
		$top_uploaders = Chart::top_uploaders(array('limit' => 10,'user' => $active_user));
		$this->setVar('most_snatched', $most_snatched);
		$this->setVar('top_uploaders', $top_uploaders);
	}
}

?>