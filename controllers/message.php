<?php

class MessageController extends AppController
{		
	public function actionInbox($page=1)
	{
		if (defined('DISABLE_MESSAGING') && DISABLE_MESSAGING)
			throw new Lvc_Exception('Messaging disabled'); 
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);		
		
		if (empty($page)) $page = 1;
		
		$messages = Message::get_messages_to_user(array('user' => $active_user->uid, 'page' => $page, 'window' => 20));

		$this->setVar('messages',$messages['messages']);
		$this->setVar('count',$messages['count']);
		$this->setVar('base_url',WWW_BASE_PATH.'inbox');
		$this->setLayoutVar('pageTitle','Inbox');
		$this->setLayoutVar('pageHead','Inbox ('.$messages['count'].')');
		
	}
	
	public function actionView($mid=null)
	{
		if (defined('DISABLE_MESSAGING') && DISABLE_MESSAGING)
			throw new Lvc_Exception('Messaging disabled');
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setLayoutVar('pageHead','Message');
		$this->setLayoutVar('pageTitle','Message');
		
		
		if (is_null($mid)) throw new Lvc_Exception('Null message id on view action');
		
		if ($message = Message::find(array('mid'=> $mid, 'user' => $active_user)))
		{
			if ($message->to_user->uid = $active_user->uid) $message->mark_read();
			$this->setVar('message',$message);
		}
		else throw new Lvc_Exception('Message Not Found: ' . $mid);
		
	}
	
	public function actionSend($name=null)
	{
		if (defined('DISABLE_MESSAGING') && DISABLE_MESSAGING)
			throw new Lvc_Exception('Messaging disabled',404);
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		
		if (is_null($name)) throw new Lvc_Exception('Null username on send action');
		
		if ($user = User::find(array('name' => $name)))
		{
			if (!empty($this->post['submit']))
			{
				$subject = $this->post['subject'];
				$body = $this->post['body'];
				$result = Message::send($user, $subject, $body, $active_user);
				
				if($result['status'])
				{
					Flash::set('success', $result['message']);
					$this->redirect('/message/inbox');
					die;
				}
				else
				{
					Flash::set('failure', $result['message']);
				}
				
				$this->setVar('subject',$subject);
				$this->setVar('body',$body);
			}
			$this->setVar('to_user',$user);
		}
		else throw new Lvc_Exception('User Not Found: ' . $name);
	}
	
}


?>