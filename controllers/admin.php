<?php
/**
 * AdminController
 *
 * @package default
 * @author Ben Cochran
 */
class AdminController extends AppController
{
	/**
	 * actionIndex
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		$active_user = User::require_admin();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		
		$this->setLayoutVar('pageHead', 'Admin Panel');
		$this->setLayoutVar('pageTitle', 'Admin');
		
	}
	
	/**
	 * actionCheckldap
	 *
	 * @return void
	 */
	public function actionCheckldap()
	{
		$active_user = User::require_admin();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		
		if (!empty($this->post['submit']))
		{
			if (!empty($this->post['deactivate']))
			{
				foreach ($this->post['deactivate'] as $name)
				{
					User::deactivate($name);
				}
			}
		}
				
		$bad_users = Admin::check_all_users();
		$this->setVar('bad_users', $bad_users);
		
	}
}

?>