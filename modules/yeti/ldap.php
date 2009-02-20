<?php
/**
 * LDAP class for verifying email addresses
 * 
 * You'll need to heavily edit this to make it work for your needs.
 *
 * @author Ben Cochran
 */
class LDAP
{
	private static $connection;
	
	private static function get_connection()
	{
		if (empty(self::$connection))
		{
			if (!(self::$connection = ldap_connect( 'your.ldap.server.com', 389 )))
				return false;
			ldap_set_option( self::$connection, LDAP_OPT_PROTOCOL_VERSION, 3 );
			if( !ldap_bind( self::$connection ) ) 
				return false;
			
		}
		return self::$connection;
	}
	
	public static function check_address($email)
	{
		$conn = &self::get_connection();
		$dn = "ou=department, dc=example, dc=com";
		$filter = "(mail=$email)";
		$justthese = array("affiliation");

		$sr = ldap_search($conn, $dn, $filter, $justthese);

		$entries = ldap_get_entries($conn, $sr);

		if ($entries['count'] == 1)
		{
        if ($entries[0]['affiliation'][0] == 'employee')
        {
					return array('status'=>true, 'message'=>'You are a valid user.');
				}
				else
				{
					return array('status'=>false, 'message'=> "'" . $email . "' has affiliation '" . $entries[0]['affiliation'][0]."'");		
				}
		}
		
		// Log it?!
		
		return array('status'=>false, 'message'=> "'" . $email . "' is not in LDAP");		
	}
	
	function __destruct()
	{
		if ($conn = &self::get_connection())
		{
			ldap_unbind($conn);
		}
	}
}
?>