<?php
class RequestController extends AppController
{	
	public function actionIndex()
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		$this->setLayoutVar('pageTitle','Requests');
		$this->setLayoutVar('pageHead','Requests');
	}
}

?>