<?php

//Include Database Configuration details

if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

//Connect to host
$newconnection = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql host") ;





/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $newconnection;
	$dbname = 'relyon_imax';
//$dbname = 'relyon_imax';

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in Runquery function1.".$query); //;
	//Return the result
	return $result;
}

/* -------------------- Run a query to database with fetching from SELECT operation -------------------- */
function runmysqlqueryfetch($query)
{
	global $newconnection;
	//$dbname = 'imaxtest';
	$dbname = 'relyon_imax';
	
	//Connect to Database

	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);

	//Run the query
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in Runquery function1.".$query); //;

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result) or die("Cannot fetch the query result.".$query);

	//Return the result
	return $fetchresult;
}

/* -------------------- Run a query to database for CA - references -------------------- */

function runmysqlqueryreferences($query)
{

	global $newconnection;
	$dbname = 'userlogin2';

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	
	//Run the query
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in runquery function".$query); //;
	

	//Return the result
	return $result;
}



/* -------------------- Run a query to database with fetching from SELECT operation for CA - references  -------------------- */
function runmysqlqueryfetchreferences($query)
{
	global $newconnection;
	$dbname = 'userlogin2';

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");

	//Run the query
	set_time_limit(3600);
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in runquery function.".$query);
	
	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result) or die ("Cannot fetch the query result.".$query);
	
	//Return the result
	return $fetchresult;
}


/* -------------------- Get local server time [by adding 5.30 hours] -------------------- */
function datetimelocal($format)
{
    //$diff_timestamp = date('U') + 19800;
    $date = date($format);
    return $date;
}

function changedateformatwithtime($date)
{
	if($date <> "0000-00-00 00:00:00")
	{
		if(strpos($date, " "))
		{
			$result = split(" ",$date);
			if(strpos($result[0], "-"))
				$dateonly = split("-",$result[0]);
			$timeonly =split(":",$result[1]);
			$timeonlyhm = $timeonly[0].':'.$timeonly[1];
			$date = $dateonly[2]."-".$dateonly[1]."-".$dateonly[0]." ".'('.$timeonlyhm.')';
		}
			
	}
	else
	{
		$date = "";
	}
	return $date;
}



function generatepwd()

{

	$charecterset0 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

	$charecterset1 = "1234567890";

	for ($i=0; $i<4; $i++)

	{

		$usrpassword .= $charecterset0[rand(0, strlen($charecterset0))];

	}

	for ($i=0; $i<4; $i++)

	{

		$usrpassword .= $charecterset1[rand(0, strlen($charecterset1))];

	}

	return $usrpassword;

}

function WriteError($errorDescrption, $errodCode = 0)
{
    $erroroutput=$erroroutput .  "<ROOT>";
    $erroroutput=$erroroutput .  "<ERROR>";
    $erroroutput=$erroroutput .  "<CODE>" .$errodCode."</CODE>";
    $erroroutput=$erroroutput .  "<DESC>" .$errorDescrption."</DESC>";
    $erroroutput=$erroroutput .  "</ERROR>";
    $erroroutput=$erroroutput .  "</ROOT>";
    return $erroroutput;
}



function generatecustomerid($customerid,$productcode,$generatedealer,$district)

{

	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");

	$cusstatecode = $query['statecode'];

	$newcustomerid = $cusstatecode.$district.$generatedealer.$productcode.$customerid;

	return $newcustomerid;

}

function cusidcombine($customerid)
{
	$result1 = substr($customerid,0,4);
	$result2 = substr($customerid,4,4);
	$result3 = substr($customerid,8,4);
	$result4 = substr($customerid,12,5);
	$result = $result1.'-'.$result2.'-'.$result3.'-'.$result4;
	return $result;
}
