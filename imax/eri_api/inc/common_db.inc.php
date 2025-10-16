<?php
	$dbhost="66.228.55.243";
	$hostip="66.228.55.243";
	$dbusername="manju";
	$dbuserpassword="cd4017";
	//$default_dbname="spp_jandj";
	$default_dbname="relyon_imax";
	// Hold the active mysqli link for error reporting
	$DB_LINK = null;
	
	date_default_timezone_set('Asia/Calcutta');

	//$dbhost = "66.228.55.243"; $dbuser = "manju";	$dbpwd = "cd4017";	$dbname = "relyon_imax";


	function db_connect($dbname="")
	{
		global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
		global $MYSQL_ERRNO, $MYSQL_ERROR, $DB_LINK;

		$link_id = @mysqli_connect($dbhost, $dbusername, $dbuserpassword);
		if(!$link_id)
		{
			// Preserve original behavior/message
			$MYSQL_ERRNO = 0;
			$MYSQL_ERROR = 'Connection failed to the host $dbhost.';
			return 0;
		}

		// store the link globally for subsequent error checks
		$DB_LINK = $link_id;

		if(empty($dbname) && !@mysqli_select_db($link_id, $default_dbname))
		{
			// Match original semantics: set both to errno
			$MYSQL_ERRNO = mysqli_errno($link_id);
			$MYSQL_ERROR = mysqli_errno($link_id);
			return 0;
		}
		else if(!empty($dbname) && !@mysqli_select_db($link_id, $dbname))
		{
			// Match original semantics: set both to error string
			$MYSQL_ERRNO = mysqli_error($link_id);
			$MYSQL_ERROR = mysqli_error($link_id);
			return 0;
		}
		else {
			return $link_id;
		}
	}
	
	function sql_error()
	{
		global $MYSQL_ERRNO, $MYSQL_ERROR, $DB_LINK;
		
		if(empty($MYSQL_ERROR))
		{
			if($DB_LINK) {
				$MYSQL_ERRNO = mysqli_errno($DB_LINK);
				$MYSQL_ERROR = mysqli_error($DB_LINK);
			} else {
				$MYSQL_ERRNO = mysqli_connect_errno();
				$MYSQL_ERROR = mysqli_connect_error();
			}
		}
		return "$MYSQL_ERRNO : $MYSQL_ERROR";
	}
?>