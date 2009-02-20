<?php

class NewsController extends AppController
{		
	public function actionIndex($page=1)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		
		if (empty($page)) $page = 1;
		
		$posts = NewsPost::get_posts($page, 10);
		
		$this->setVar('posts',$posts['posts']);
		$this->setVar('count',$posts['count']);
		$this->setVar('page',$page);
		$this->setVar('window',10);
		$this->setVar('base_url',WWW_BASE_PATH.'news');
		$this->setLayoutVar('pageTitle','News');
		$this->setLayoutVar('pageHead','News');
	}
	
	public function actionNewest($window = 1)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		
		if (empty($window)) $window = 1;
		
		$posts = NewsPost::get_posts(1, $window);
		
		$this->setVar('posts',$posts['posts']);
		$this->setVar('count',$posts['count']);
	}
	
	public function actionPost()
	{
		$active_user = User::require_admin();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		
		$this->requireJs('jquery-ui.js');
		$this->requireJs('autofill_users.js');
		
		$this->setVar('user', $active_user);	
	}
}
?>