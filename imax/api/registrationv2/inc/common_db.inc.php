<?php
	$dbhost="66.228.55.243";
	$hostip="66.228.55.243";
	$dbusername="manju";
	$dbuserpassword="cd4017";
	//$default_dbname="spp_jandj";
	$default_dbname="relyon_imax";
	//$dbhost = "66.228.55.243"; $dbuser = "manju";	$dbpwd = "cd4017";	$dbname = "relyon_imax";


	date_default_timezone_set('Asia/Calcutta');

	
	function db_connect($dbname="")
	{
		global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
		global $MYSQL_ERRNO, $MYSQL_ERROR;
		
		$link_id=mysql_connect($dbhost,$dbusername,$dbuserpassword);
		if(!$link_id)
		{
			$MYSQL_ERRNO=0;
			$MYSQL_ERROR='Connection failed to the host $dbhost.';
			return 0;
		}
		else if(empty($dbname) && !mysql_select_db($default_dbname))
		{
			$MYSQL_ERRNO=mysql_errno();
			$MYSQL_ERROR=mysql_errno();
			return 0;			
		}
		else if(!empty($dbname) && !mysql_select_db($dbname))
		{
			$MYSQL_ERRNO=mysql_error();
			$MYSQL_ERROR=mysql_error();
			return 0;
		}		
		else return $link_id;
	}
	
	function sql_error()
	{
		global $MYSQL_ERRNO, $MYSQL_ERROR;
		
		if(empty($MYSQL_ERROR))
		{
			$MYSQL_ERRNO=mysql_errno();
			$MYSQL_ERROR=mysql_error();
		}
		return "$MYSQL_ERRNO : $MYSQL_ERROR";
	}
?>