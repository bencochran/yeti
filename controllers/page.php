<?php

class PageController extends AppController
{
	public function actionView($pageName = 'home')
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		
		if (strpos($pageName, '../') !== false)
		{
			throw new Lvc_Exception('File Not Found: ' . $sourceFile);
		}
		
		switch($pageName)
		{
			case 'home':
				$torrents = Torrent::browse(array('category_cid' => -1, 'window' => 5, 'page' => 1));
				$news = $this->requestAction('newest', array(), 'news');
				//$currently_seeding = User::currently_seeding($active_user);
				$this->setVar('torrents',$torrents['torrents']);
				$this->setVar('latest_news',$news);
				$this->setVar('total_torrents',$torrents['count']);
				$this->setVar('currently_seeding',$currently_seeding['users']);
				$this->setVar('currently_seeding_count',$currently_seeding['count']);
				break;
			case 'help':
				$this->setLayoutVar('pageTitle','Help');
				$this->setLayoutVar('pageHead','Help');
				break;
			case 'help/transmission':
				$this->setLayoutVar('pageTitle','Transmission Upload Guide');
				$this->setLayoutVar('pageHead','Transmission Upload Guide');
				break;
			case 'help/utorrent':
				$this->setLayoutVar('pageTitle','uTorrent Upload Guide');
				$this->setLayoutVar('pageHead','uTorrent Upload Guide');
				break;
			case 'faq':
				$this->setLayoutVar('pageTitle','FAQ');
				$this->setLayoutVar('pageHead','Frequently Asked Questions');
				break;
		}		
		
		$this->loadView('page/' . rtrim($pageName, '/'));
	}
	
	public function actionPublicview($pageName = 'locked')
	{		
		if (strpos($pageName, '../') !== false)
		{
			throw new Lvc_Exception('File Not Found: ' . $sourceFile);
		}
		
		$this->setLayout('outside');
		
		switch($pageName)
		{
			case 'locked':
				$this->setLayoutVar('pageTitle','Locked');
				$this->setLayoutVar('pageHead','Lockdown!');
				break;
		}		
		
		$this->loadView('page/' . rtrim($pageName, '/'));
	}
}

?>