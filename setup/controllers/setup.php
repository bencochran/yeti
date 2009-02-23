<?php

class SetupController extends AppController
{
	
	
	public function actionBegin()
	{
		$this->setLayoutVar('pageTitle', 'Yeti Configuration');
		$this->setLayoutVar('pageHead','Yeti');
	}
	
	public function actionSite()
	{
		$this->setLayoutVar('pageTitle', 'Yeti Configuration - Site');
		$this->setLayoutVar('pageHead','1. Site Info');
		
		if (!empty($this->post['submit']))
		{
			// Do stuff with $this->post, set $result accordingly.
			$result = array('status' => true, 'message' => 'You did it!');
			
			if($result['status'])
			{
				$this->redirect(WWW_BASE_PATH . 'database/');
				die;
			}
			else
			{
				Flash::set('failure', $result['message']);
				
				// Set the correct values back into the form 
				// (ignoring passwords)
				$this->setVar('site_name',$this->post['site_name']);
				$this->setVar('base_path',$this->post['base_path']);
				$this->setVar('cookie_domain',$this->post['cookie_domain']);
			}
		}
		else
		{
			// Try to guess the base_path...
			$this->setVar('base_path',$_SERVER['SERVER_NAME'] . str_replace('setup/index.php', '', $_SERVER['SCRIPT_NAME']));
			// and the cookie domain
			$this->setVar('cookie_domain','.'.$_SERVER['SERVER_NAME']);
		}
	}
	
	
	public function actionDatabase()
	{
		$this->setLayoutVar('pageTitle', 'Yeti Configuration - Database');
		$this->setLayoutVar('pageHead','2. MySQL Database');
		
		if (!empty($this->post['submit']))
		{
			// Do stuff with $this->post, set $result accordingly.
			$result = array('status' => true, 'message' => 'You did it!');
			
			if($result['status'])
			{
				$this->redirect(WWW_BASE_PATH . 'next_step/');
				die;
			}
			else
			{
				Flash::set('failure', $result['message']);
				
				// Set the correct values back into the form 
				// (ignoring passwords)
				$this->setVar('hostname',$this->post['hostname']);
				$this->setVar('port',$this->post['port']);
				$this->setVar('user',$this->post['user']);
				$this->setVar('db_name',$this->post['db_name']);
			}
		}
		else
		{
			// Defaults
			$this->setVar('hostname','localhost');
			$this->setVar('port','3306');
		}
	}		
}

?>