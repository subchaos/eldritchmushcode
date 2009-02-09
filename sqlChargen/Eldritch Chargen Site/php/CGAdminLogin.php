<?
	error_reporting(E_ALL);
	header("Cache-Control: no-cache");

	include 'CGAdminDB.php';
	//need to create this file. it's not part of the SVN. It needs to have $password_eldritchSQL and $AES_ENC_KEY
	include 'CGAdminPasswords.php';
	
	$loginUsername = isset($_POST["loginUsername"]) ? $_POST["loginUsername"] : "";
	$loginPassword = isset($_POST["loginPassword"]) ? $_POST["loginPassword"] : "";
	 
	$eldritchSQL = mysql_pconnect($hostname_eldritchSQL, $username_eldritchSQL, $password_eldritchSQL) 
					or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db($database_eldritchSQL) 
		or trigger_error(mysql_error(),E_USER_ERROR);
		
	$result = mysql_query("SELECT * FROM CGDB_ADMIN WHERE User='".$loginUsername."' AND Pass=AES_ENCRYPT('".$loginPassword."','".$AES_ENC_KEY."')");

	if (mysql_num_rows($result) == 1)
	{
		echo("{success: true}");
	}
	else 
	{
		echo "{success: false, errors: { reason: 'Login failed. Try again.' }}";
	}	
?>