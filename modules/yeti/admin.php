<?php

class Admin
{
	public static function check_all_users($exclude_ids = array())
	{
		$query = "SELECT * FROM xbt_users WHERE is_activated = 1 AND admin = 0 AND email != '".CONTACT_EMAIL."'";
		if (!empty($excude_ids))
		{
			$query .= " AND uid NOT IN ('".implode("'. '",$exclude_ids)."')";
		}
		
		if($results = DB::query($query))
		{
			$users = array();
			while ($result = mysql_fetch_array($results))
			{
				$user = new User($result);
				array_push($users, $user);
			}
		}
		else return false;
		
		$invalid_users = array();
		foreach ($users as $user)
		{
			if (!LDAP::check_address($user->email))
			{	
				array_push($invalid_users, $user);
			}	
		}
		
		return $invalid_users;
	}
}

?>