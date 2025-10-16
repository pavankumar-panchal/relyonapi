<?
	//Define the connection parameters - MySQL
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132")
	{
			$dbhost = "localhost"; $dbuser = "root";	$dbpwd = "";	$dbname = "userlogin2";
	}
	else
	{
			$dbhost = "localhost"; $dbuser = "eip";	$dbpwd = "RslWdo@12#";$dbname = "relyonso_userlogin2";
	}

?>
