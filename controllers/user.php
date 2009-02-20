<?php
/**
 * UserController
 *
 * @package default
 * @author Ben Cochran
 */
class UserController extends AppController
{
	/**
	 * actionView
	 *
	 * @param string $name 
	 * @return void
	 * @author Ben Cochran
	 */
	public function actionView($name=null)
	{		
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		$is_active_user = ($name == $active_user->name);
		
		if (is_null($name)) throw new Lvc_Exception('Null username on view action');
		
		// Only include hidden users in the search if we're viewing the active
		// user, or the active user is an admin.
		$exclude_hidden = !($is_active_user || $active_user->admin);
		
		if ($user = User::find(array('name' => $name)))
		{
			//$is_active_user = ($user->name == $active_user->name);
			if (!$user->shown && !($is_active_user || $active_user->admin))
				throw new Lvc_Exception('Warning: User "'.$active_user->name.'" tried to view hidden user "' . $name.'"');
			
			if($is_active_user)
			{
				$this->setLayoutVar('pageHead','Your Profile');
				$this->setLayoutVar('pageTitle','Your Profile');
			}
			else
			{
				$this->setLayoutVar('pageHead',$user->name.'\'s Profile');
				$this->setLayoutVar('pageTitle',$user->name.'\'s Profile');
			}
			
			$active_torrents = $user->torrents_active(array('page' => 1, 'window' => 5));
			$added_torrents = $user->torrents_added(array('page' => 1, 'window' => 5));
			
			$this->setVar('user',$user);
			$this->setVar('active_torrents',$active_torrents['torrents']);
			$this->setVar('total_active',$active_torrents['count']);
			$this->setVar('added_torrents',$added_torrents['torrents']);
			$this->setVar('total_added',$added_torrents['count']);
			$this->setVar('is_active_user',$is_active_user);		
		}
		else throw new Lvc_Exception('User Not Found: ' . $name);
	}
	
	public function actionAutocomplete()
	{
		if (!($active_user = User::get_active_user()))
		{
			buffer_end_clean();
			die;
		}
		
		
		$q = $this->get['q'];
		if (empty($q)) die('[]');
		
		$usernames = User::get_autocomplete($q,$active_user->admin,$active_user);
		
		$this->setVar('usernames',$usernames);
		$this->setLayout(NULL);
	}
	
	public function actionUploads($name=null, $page=1)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		$is_active_user = ($name == $active_user->name);
				
		if (is_null($page)) $page = 1;
		
		if ($user = User::find(array('name' => $name)))
		{
			//$is_active_user = ($user->name == $active_user->name);
			if (!$user->shown && !($is_active_user || $active_user->admin))
				throw new Lvc_Exception('Warning: User "'.$active_user->name.'" tried to view hidden user "' . $name.'"');
			
			$results = $user->torrents_added(array('page' => $page, 'window' => 15));
			
			if (count($results['torrents']) < 1) throw new Lvc_Exception('No torrents returned');
			
			$this->setVar('torrents', $results['torrents']);
			$this->setVar('count',$results['count']);
			$this->setVar('page',$page);
			$this->setVar('user',$user);
			$this->setVar('base_url',WWW_BASE_PATH.'user/'.$user->name.'/uploads');
			
			if($is_active_user)
			{
				$this->setLayoutVar('pageHead','Your Uploaded Torrents ('.$results['count'].')');
				$this->setLayoutVar('pageTitle','Your Uploaded Torrents');
			}
			else
			{
				$this->setLayoutVar('pageHead',$user->name.'\'s Uploaded Torrents ('.$results['count'].')');
				$this->setLayoutVar('pageTitle',$user->name.'\'s Uploaded Torrents');
			}
		}
		else throw new Lvc_Exception('User Not Found: ' . $name);
		
		$this->loadView($this->controllerName . '/torrents');	
	}
	
	public function actionActive($name=null, $page=1)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		$is_active_user = ($name == $active_user->name);
		
		if (is_null($page)) $page = 1;
		
		if ($user = User::find(array('name' => $name)))
		{
			//$is_active_user = ($user->name == $active_user->name);
			if (!$user->shown && !($is_active_user || $active_user->admin))
				throw new Lvc_Exception('Warning: User "'.$active_user->name.'" tried to view hidden user "' . $name.'"');
			
			$results = $user->torrents_active(array('page' => $page, 'window' => 15));

			if (count($results['torrents']) < 1) throw new Lvc_Exception('No torrents returned');

			$this->setVar('torrents', $results['torrents']);
			$this->setVar('count',$results['count']);
			$this->setVar('page',$page);
			$this->setVar('user',$user);
			$this->setVar('base_url',WWW_BASE_PATH.'user/'.$user->name.'/active');

			if($is_active_user)
			{
				$this->setLayoutVar('pageHead','Your Active Torrents ('.$results['count'].')');
				$this->setLayoutVar('pageTitle','Your Active Torrents');
			}
			else
			{
				$this->setLayoutVar('pageHead',$user->name.'\'s Active Torrents ('.$results['count'].')');
				$this->setLayoutVar('pageTitle',$user->name.'\'s Active Torrents');
			}
			

		}
		else throw new Lvc_Exception('User Not Found: ' . $name);
		
		$this->loadView($this->controllerName . '/torrents');
	}
		
	public function actionRegister()
	{
		if (User::logged_in())
		{
			$this->redirect('/');
			die;
		}
		$this->setLayout('outside');
		$this->setLayoutVar('pageHead','Register for an Account');
		$this->setLayoutVar('pageTitle','Register');
		
		$this->setVar('email','');
		$this->setVar('username','');
		
		if (!empty($this->post['submit']))
		{
			$result = User::register($this->post['email'], $this->post['username'], $this->post['password'], $this->post['password_match']);
			if($result['status'])
			{
				Flash::set('success', $result['message']);
				$this->redirect('/');
				die;
			}
			else
			{
				Flash::set('failure', $result['message']);
				$this->setVar('email',$this->post['email']);
				$this->setVar('username',$this->post['username']);
			}
		}
	}
	
	public function actionReset()
	{
		if (User::logged_in())
		{
			$this->redirect('/');
			die;
		}
		
		$this->setLayout('outside');
		$this->setLayoutVar('pageHead','Reset Your Password');
		$this->setLayoutVar('pageTitle','Reset Password');
		
		if (!empty($this->post['submit']))
		{
			$result = User::request_reset($this->post['username']);
			if($result['status'])
			{
				Flash::set('success', $result['message']);
				$this->redirect('/');
				die;
			}
			else
			{
				Flash::set('failure', $result['message']);
				$this->setVar('username',$this->post['username']);
			}
		}
		
	}
	
	public function actionResetpass($name = null, $code = null)
	{
		$result = User::check_reset_code($name,$code);
		if ($result['status'])
		{
			$this->setLayout('outside');
			$this->setLayoutVar('pageHead','Reset Your Password');
			$this->setLayoutVar('pageTitle','Reset Password');
			$this->setVar('username',$name);
			$this->setVar('reset_code',$code);
			
			if (!empty($this->post['submit']))
			{
				$result = User::process_reset($name,$code,$this->post['password'], $this->post['password_match']);
				if($result['status'])
				{
					Flash::set('success', $result['message']);
					$this->redirect('/');
					die;
				}
				else
				{
					Flash::set('failure', $result['message']);
				}
			}
			
			
		}
		else
		{
			Flash::set('failure',$result['message']);
			$this->redirect('/user/reset');
			die;
		}
		
	}
	
	public function actionActivate($name = null, $code = null)
	{
		$result = User::activate($name,$code);
		if($result['status'])
		{
			Flash::set('success', $result['message']);
		}
		else
		{
			Flash::set('failure', $result['message']);
		}
		$this->redirect('/');
		die();		
	}
	
	public function actionLogin()
	{
		if (User::logged_in())
		{
			$this->redirect('/');
			die;
		}
		$this->setLayout('outside');
		$this->setLayoutVar('pageHead','Login to Your Account');
		$this->setLayoutVar('pageTitle','Login');
		
		$referrer = (isset($_SESSION['flash']) && isset($_SESSION['flash']['referrer'])) ? $_SESSION['flash']['referrer'] : $_SERVER['HTTP_REFERER'];
				
		$this->setVar('username','');
		$this->setVar('referrer',$referrer);
		
		if (!empty($this->post['submit']))
		{
			
			$result = User::login($this->post['username'], $this->post['password']);
			if ($result['status'])
			{
				if (!empty($this->post['referrer']))
					$this->redirect($this->post['referrer']);
				else
					$this->redirect('/');
				die;
			}
			else
			{
				Flash::set('failure', $result['message']);
				$this->setVar('username',$this->post['user']);
			}
		}
	}
	
	public function actionLogout()
	{
		User::logout();
		$this->redirect('/');
		die();
	}
}


?>