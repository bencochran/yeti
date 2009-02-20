<?php

class TorrentController extends AppController
{	
	public function actionSearch($mode = null)
	{		
		$active_user = User::require_active_user();
	
		switch ($mode)
		{
			case 'live':
				$browse_base = 'browselive';
				$search_base = 'searchlive';
				break;
			default:	
				$browse_base = 'browse';
				$search_base = 'search';
				break;
		}
		
		$category_cid = $this->get['category_cid'];
		$query = $this->get['query'];
		if ((empty($query) && empty($category_cid)) || empty($category_cid))
		{
			$this->redirect('/'.$browse_base);
			die;
		}
		if ($category_cid == -1)
			$category = new Category(array('cid'=>-1,'name'=>'All','slug'=>'all'));
		elseif (!$category = Category::find(array('cid' => $category_cid)))
			throw new Lvc_Exception('Category Not Found: ' . $category_cid);
		$query = str_replace(array('/'),'',$query);
		$query = strtr($query,array('&'=>'%26','#'=>'%23','\\\\'=>'%5C'));
		$query = urlencode($query);
		$this->redirect('/'.$search_base.'/'.$category->slug.'/'.$query);
		die();
	}
	
	public function actionBrowse($category_slug=null,$page=1,$query='',$mode=null)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		$this->setLayoutVar('tab', 'browse');
		
		$query = urldecode($query);
		
		if (is_null($page)) $page = 1;
		
		if (!empty($category_slug) && $category_slug != 'all')
		{
			if ($category = Category::find(array('slug' => $category_slug)))
			{

			}
			else throw new Lvc_Exception('Category Not Found: ' . $category_slug);
		}
		else
		{
			$category = new Category(array('cid'=>-1,'name'=>'All','slug'=>'all'));
		}
		
		$results = Torrent::browse(array('category_cid' => $category->cid, 'window' => 15, 'page' => $page, 'query' => $query,'mode'=>$mode));
		
		//if (empty($results['torrents'])) throw new Lvc_Exception('No torrents returned. Category: ' . $category->slug. '. Page: '. $page);
		
		
		$this->setVar('selected_category',$category);
		$this->setVar('torrents',$results['torrents']);
		$this->setVar('count',$results['count']);
		$this->setVar('query',$query);
		$this->setVar('page',$page);
		
		$this->setLayoutVar('pageHead','Browse Torrents');
		$this->setLayoutVar('pageTitle', 'Browse Torrents');
		if ($category->cid != -1)
		{
			$this->setLayoutVar('pageHead',$this->getLayoutVar('pageHead').': '. $category->name);
			$this->setLayoutVar('pageTitle', 'Browse Torrents - ' . $category->name );
		}	
		if (!empty($query))
		{
			$this->setLayoutVar('pageHead',$this->getLayoutVar('pageHead').': '. $query);
			$this->setLayoutVar('pageTitle', 'Search Torrents');
			if (strlen($query) < 4)
			{
				Flash::set('failure','Your search query must be more than 4 characters long.');
			}
		}
		
		$categories = Category::find_all();
		$this->setVar('categories',$categories);
		$base_url = WWW_BASE_PATH;

		switch ($mode)
		{
			case 'live':
				$browse_base = 'browselive';
				$search_base = 'searchlive';
				break;
			default:	
				$browse_base = 'browse';
				$search_base = 'search';
				break;
		}
		

		if (empty($query))
		{
			$base_url .= $browse_base.'/';
			$base_url .= $category->slug;
		}
		else
		{
			$base_url .= $search_base.'/';
			$base_url .= $category->slug.'/';
			
			$url_query = str_replace(array('/'),'',$query);
			$url_query = strtr($url_query,array('&'=>'%26','#'=>'%23','\\\\'=>'%5C'));
			$url_query = urlencode($url_query);
			$base_url .= $url_query;
		}
		$this->setVar('base_url',$base_url);
		$this->setVar('search_base',$search_base);
		$this->setVar('browse_base',$browse_base);
	}
	
	//public function actionSearch($query='',$category_id=-1,$page=0)
	//{
	//	$active_user = User::require_active_user();
	//	$this->setLayoutVar('active_user', $active_user);
	//	$this->setVar('active_user', $active_user);
	//	$this->setLayoutVar('pageHead','Search Torrents');
	//	
	//}
	
	public function actionDetails($fid=null)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);

		if (is_null($fid)) throw new Lvc_Exception('Null fid on details action');
		if ($torrent = Torrent::find(array('fid' => $fid)))
		{
			$files = $torrent->files(array('page' => 1, 'window' => 5));
			$comments = $torrent->comments(1,5);
			$seeders = $torrent->seeders();
			$leechers = $torrent->leechers();
			
			$this->setVar('torrent',$torrent);
			$this->setVar('files',$files['files']);
			$this->setVar('file_count',$files['count']);
			$this->setVar('file_window',5);
			$this->setVar('comments',$comments['comments']);
			$this->setVar('comment_count',$comments['count']);
			$this->setVar('comment_window',5);
			$this->setVar('seeders',$seeders['users']);
			$this->setVar('leechers',$leechers['users']);
			$this->setLayoutVar('pageHead','Details &mdash; '. $torrent->title);
			$this->setLayoutVar('pageTitle','Details - '. $torrent->title);
			
		}
		else throw new Lvc_Exception('Torrent Not Found: ' . $fid);
	}
	
	public function actionFiles($fid=null,$page = 1)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);

		if (is_null($page)) $page = 1;

		if (is_null($fid)) throw new Lvc_Exception('Null fid on files action');
		if ($torrent = Torrent::find(array('fid' => $fid)))
		{
			$files = $torrent->files(array('page' => $page, 'window' => 25));
			
			if (count($files['files']) < 1) throw new Lvc_Exception('No files returned');
			
			$this->setVar('torrent',$torrent);
			$this->setVar('files',$files['files']);
			$this->setVar('count',$files['count']);
			$this->setVar('page',$page);
			$this->setVar('base_url',WWW_BASE_PATH.'torrent/'.$torrent->fid.'/files');
			$this->setLayoutVar('pageHead','Files &mdash; '. $torrent->title);
			$this->setLayoutVar('pageTitle','Files - '. $torrent->title);
			
		}
		else throw new Lvc_Exception('Torrent Not Found: ' . $fid);		
		
	}
	
	// Post a comment
	public function actionComment($fid=null)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);
		
		if (is_null($fid)) throw new Lvc_Exception('Null fid on comment post action');
		if ($torrent = Torrent::find(array('fid' => $fid)))
		{
			if (!empty($this->post['submit']))
			{
				$this->setVar('body',$this->post['body']);
				$result = Comment::post($fid, $this->post['body'], $active_user->uid);
				if($result['status'])
				{
					Flash::set('success', $result['message']);
					$this->redirect('/torrent/'.$fid.'/comments');						
					die;
				}
				else
				{
					$this->setVar('fail_message',$result['message']);
				}
			}
									
			$this->setVar('torrent',$torrent);
			$this->setLayoutVar('pageHead','Post a Comment &mdash; '. $torrent->title);
			$this->setLayoutVar('pageTitle','Post a Comment - '. $torrent->title);
			
		}
		else throw new Lvc_Exception('Torrent Not Found: ' . $fid);		
		
	}
	
	public function actionComments($fid=null,$page=1)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);

		if (is_null($page)) $page = 1;

		if (is_null($fid)) throw new Lvc_Exception('Null fid on comments action');
		if ($torrent = Torrent::find(array('fid' => $fid)))
		{
			$comments = $torrent->comments($page,5);
						
			$this->setVar('torrent',$torrent);
			$this->setVar('comments',$comments['comments']);
			$this->setVar('count',$comments['count']);
			$this->setVar('page',$page);
			$this->setVar('window',5);
			$this->setVar('base_url',WWW_BASE_PATH.'torrent/'.$torrent->fid.'/comments');
			$this->setLayoutVar('pageHead','Comments &mdash; '. $torrent->title);
			$this->setLayoutVar('pageTitle','Comments - '. $torrent->title);
			
		}
		else throw new Lvc_Exception('Torrent Not Found: ' . $fid);		
		
	}
	
	
	public function actionDownload($fid=null)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);

		if (is_null($fid)) throw new Lvc_Exception('Null fid on download action');
		
		if ($torrent = Torrent::find(array('fid' => $fid)))
		{
			$this->setVar('torrent',$torrent);
			$this->setLayout(null);
		}
		else throw new Lvc_Exception('Torrent Not Found: ' . $fid);		
	}
	
	public function actionUpload()
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user', $active_user);	
		$this->setLayoutVar('pageHead', 'Upload a Torrent');
		$this->setLayoutVar('pageTitle', 'Upload a Torrent');
		
		$categories = Category::find_all();
		$this->setVar('categories',$categories);
		$this->setVar('title','');
		$this->setVar('description','');
		$this->setVar('category_cid','');
		
		if (!empty($this->post['submit']))
		{
			if(!($this->post['torrent']['error'] > 0))
			{
				$result = Torrent::upload($this->post['title'], $this->post['description'], $this->post['category_cid'], $this->post['torrent'], $active_user);
				if($result['status'])
				{
					Flash::set('success', $result['message']);
					$this->redirect('/torrent/'.$result['id']);
					die;
				}
				else
				{
					Flash::set('failure', $result['message']);
				}
			}
			else
			{
				Flash::set('failure', 'No .torrent file was specified!');
			}
			$this->setVar('title',$this->post['title']);
			$this->setVar('description',$this->post['description']);
			$this->setVar('category_cid',$this->post['category_cid']);
		}
	}
	
	public function actionDelete($fid=null)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user',$active_user);
		
		if (is_null($fid)) throw new Lvc_Exception('Null fid on edit action');
		
		if ($torrent = Torrent::find(array('fid' => $fid)))
		{
			if ($torrent->has_access(array('user' => $active_user)))
			{				
				if (!empty($this->post['submit']))
				{
					if ($this->post['submit'] == 'delete')
					{
						$result = Torrent::delete($fid, $active_user);
						if($result['status'])
						{
							Flash::set('success', $result['message']);
							$this->redirect('/browse');
							die;
						}
						else
						{
							$this->setVar('fail_message',$result['message']);
						}
					}
					else
					{
						Flash::set('success','That was a close call! But it\'s safe!');
						$this->redirect('/torrent/'.$fid);
					}
				}
				$this->setVar('torrent',$torrent);
				$this->setLayoutVar('pageHead','Confirm Delete &mdash; '. $torrent->title);
				$this->setLayoutVar('pageTitle', 'Confirm Delete - '.$torrent->title);
			}
			else throw new Lvc_Exception('Unauthorized user: '. $active_user->name . ' tried to delete torrent: '.$torrent->fid);
		}
		else throw new Lvc_Exception('Torrent Not Found: ' . $fid);			
		
	}
	
	public function actionEdit($fid=null)
	{
		$active_user = User::require_active_user();
		$this->setLayoutVar('active_user', $active_user);
		$this->setVar('active_user',$active_user);
		
		//Edit &mdash; <?php echo $torrent->title;

		if (is_null($fid)) throw new Lvc_Exception('Null fid on edit action');
		
		if ($torrent = Torrent::find(array('fid' => $fid)))
		{
			if ($torrent->has_access(array('user' => $active_user)))
			{				
				if (!empty($this->post['submit']))
				{
					$result = Torrent::edit($fid, $this->post['title'], $this->post['description'], $this->post['category_cid'], $active_user);
					if($result['status'])
					{
						Flash::set('success', $result['message']);
						$this->redirect('/torrent/'.$fid);						
						die;
					}
					else
					{
						$this->setVar('fail_message',$result['message']);
					}
				}
				
				$categories = Category::find_all();
				
				$this->setVar('categories', $categories);
				$this->setVar('torrent',$torrent);
				$this->setLayoutVar('pageHead','Edit &mdash; '. $torrent->title);
				$this->setLayoutVar('pageTitle', 'Edit - '.$torrent->title);
			}
			else throw new Lvc_Exception('Unauthorized user: '. $active_user->name . ' tried to edit torrent: '.$torrent->fid);
		}
		else throw new Lvc_Exception('Torrent Not Found: ' . $fid);		
		
	}
}


?>