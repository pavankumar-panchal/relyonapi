<?php
//Include Database Configuration details
if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

// PHP 8 compatibility shims for legacy mysql_* APIs (inline to avoid extra files)
if (!function_exists('mysql_connect')) {
	function mysql_connect($host = null, $user = null, $password = null)
	{
		$link = mysqli_connect($host, $user, $password);
		if (!$link) {
			die('Cannot connect to Mysql server host');
		}
		return $link;
	}
}
if (!function_exists('mysql_select_db')) {
	function mysql_select_db($dbname, $link_identifier = null)
	{
		return mysqli_select_db($link_identifier, $dbname);
	}
}
if (!function_exists('mysql_query')) {
	function mysql_query($query, $link_identifier = null)
	{
		return mysqli_query($link_identifier, $query);
	}
}
if (!function_exists('mysql_fetch_array')) {
	function mysql_fetch_array($result)
	{
		return mysqli_fetch_array($result, MYSQLI_BOTH);
	}
}
if (!function_exists('mysql_num_rows')) {
	function mysql_num_rows($result)
	{
		return mysqli_num_rows($result);
	}
}
if (!function_exists('mysql_error')) {
	function mysql_error($link_identifier = null)
	{
		return mysqli_error($link_identifier);
	}
}
if (!function_exists('mysql_real_escape_string')) {
	function mysql_real_escape_string($unescaped_string, $link_identifier = null)
	{
		if ($link_identifier) {
			return mysqli_real_escape_string($link_identifier, $unescaped_string);
		}
		return addslashes($unescaped_string);
	}
}

//Connect to host
$newconnection = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");


/* -------------------- Get local server time [by adding 5.30 hours] -------------------- */
function datetimelocal($format)
{
	//$diff_timestamp = date('U') + 19800;
	$date = date($format);
	return $date;
}

/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $newconnection;
	
	$dbname = 'relyonso_userlogin2';
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132")
	{
		$dbname = 'userlogin2';
	}
	else if($_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
	{
		$dbname = 'etdspayr_userlogin2';
	}

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
	
	$dbname = 'relyonso_userlogin2';
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132")
	{
		$dbname = 'userlogin2';
	}
	else if($_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
	{
		$dbname = 'etdspayr_userlogin2';
	}

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection) or die("run Query Failed in Runquery function1.".$query); //;
	
	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result) or die("Cannot fetch the query result.".$query);
	
	//Return the result
	return $fetchresult;
}

/* -------------------- To change the date format from DD-MM-YYYY to YYYY-MM-DD or reverse -------------------- */
function changedateformat($date)
{
	if($date <> "0000-00-00")
	{
	if(strpos($date, " "))
	$result = explode(" ",$date);
	else
	$result = preg_split("/[:.\/ -]/", $date);
		$date = $result[2]."-".$result[1]."-".$result[0];
	}
	else
	{
		$date = "";
	}
	return $date;
}

function changetimeformat($time)
{
	if($time <> "00:00:00")
	{
		$result = explode(":", $time);
		$time = $result[0].":".$result[1];
	}
	else
	{
		$time = "";
	}
	return $time;
}

function changedateformatwithtime($date)
{
	if($date <> "0000-00-00 00:00:00")
	{
		if(strpos($date, " "))
		{
			$result = explode(" ", $date);
			if(strpos($result[0], "-"))
				$dateonly = explode("-", $result[0]);
			$timeonly = explode(":", $result[1]);
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
	$usrpassword = '';
	for ($i=0; $i<4; $i++)
	{
		$usrpassword .= $charecterset0[rand(0, strlen($charecterset0) - 1)];
	}
	for ($i=0; $i<4; $i++)
	{
		$usrpassword .= $charecterset1[rand(0, strlen($charecterset1) - 1)];
	}
	return $usrpassword;
}

function checkdateformat($date) //Valid is 2008-11-15
{
	$returnflag = false;
	$result = explode("-",$date);
	if(count($result) == 3 && checkdate($result[1], $result[2], $result[0]))
		$returnflag = true;
	return $returnflag;
}

function datenumeric($date) //convert date to its numeric value so that it can be compared.
{
	$dateArr = explode("-",$date);
	$dateInt = mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]);
	return $dateInt;
}
/* -------------------- To trim the data for the grid, If it is more than 20 charecters [Say: "This problem is due to the problem in server" -> "This problem is due ..." -------------------- */
function gridtrim30($value)
{
	$desiredlength = 30;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function gridtrim1($value)
{
	$desiredlength = 20;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "<br>";
	}
	return $value;
}



function getpagelink($linkvalue)
{
	switch($linkvalue)
	{
		case 'home_dashboard':return '../home/dashboard.php';
		case 'pm': return '../master/pm.php';
		case 'main_product': return '../master/product_update.php';
		case 'career': return '../manage/career.php';
		case 'version_product': return '../manage/verfrom.php';
		case 'hotfix_product': return '../manage/hot_fix.php';
		case 'flashnews': return '../manage/flash_news.php';
		case 'unauthorised': return '../usermanagement/unauthorised.php';
		case 'grouphead': return '../master/grouphead.php';
		case 'saralmail': return '../manage/saralmail.php';
		case 'saralmail_disable': return '../manage/saralmail_disable.php';
		case 'emailsearch': return '../manage/email_search.php';
		case 'registeruser': return '../master/register_user.php';
		case 'editprofile': return '../manage/edit_profile.php';
        
		default: return '../home/dashboard.php';
	}
}

function getpagetitle($linkvalue)
{
	switch($linkvalue)
	{
		case 'home_dashboard':return 'Product mAster : Dashboard';
		case 'pm': return 'Product mAster : Product Master';
		case 'main_product': return 'Product mAster : Main Product Update ';
		case 'career': return 'Product mAster : Job Requirement';
		case 'version_product': return 'Product mAster : Product Version';
		case 'hotfix_product': return 'Product mAster : HotFix Version';
		case 'flashnews': return 'Product mAster : Flash News';
		case 'grouphead': return 'Product mAster : Grouphead Master';
		case 'saralmail': return 'Product mAster : Employee Official Email ID';
		case 'saralmail_disable': return 'Product mAster : Disabled Employee Details';
		case 'emailsearch': return 'Product mAster : Email ID Search';
		case 'registeruser': return 'Product mAster : Register User';
		case 'editprofile': return 'Product mAster : Edit Profile';

		default: return 'Product mAster : Dashboard';
	}
}

function getpageheader($linkvalue)
{
	switch($linkvalue)
	{
		case 'home_dashboard':return 'Product mAster : Dashboard';
		case 'pm': return 'Product mAster : Product Master';
		case 'main_product': return 'Product mAster : Main Product Update ';
		case 'career': return 'Product mAster : Job Requirement';
		case 'version_product': return 'Product mAster : Product Version';
		case 'hotfix_product': return 'Product mAster : HotFix Version';
		case 'flashnews': return 'Product mAster : Flash News';
		case 'grouphead': return 'Product mAster : Grouphead Master';
		case 'saralmail': return 'Product mAster : Employee Official Email ID';
		case 'saralmail_disable': return 'Product mAster : Disabled Employee Details';
		case 'emailsearch': return 'Product mAster : Email ID Search';
		case 'registeruser': return 'Product mAster : Register User';
		case 'editprofile': return 'Product mAster : Edit Profile';
        
		default: return 'Product mAster : Dashboard';
	}
}

function gridtrim($value)
{
	$desiredlength = 30;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function gridtrim10($value)
{
	$desiredlength = 10;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function gridtrimalert($value)
{
	$desiredlength = 30;
	$stripedvalue = strip_tags($value);
	$length = strlen($stripedvalue);
	if($length >= $desiredlength)
	{
		$value = substr($stripedvalue, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function deleterecordcheck($fieldvalue,$fieldname,$tablename)
{
	$flag = false;
	$query = "SELECT COUNT(*) AS count FROM ".$tablename." WHERE ".$fieldname." = '".$fieldvalue."'";
	$fetch = runmysqlqueryfetch($query);
	if($fetch['count'] == 0)
		$flag = true;
	else
		$flag = false;
	return $flag;
}

function compare2date()
{
	$exp_date = "2006-01-16"; $todays_date = date("Y-m-d"); $today = strtotime($todays_date); $expiration_date = strtotime($exp_date); if ($expiration_date > $today) { $valid = "yes"; } else { $valid = "no"; } 
}

/* -------------------- Upload ZIP file through PHP -------------------- */
function fileupload($filename,$filetempname)
{
//check that we have a file
  //Check if the file is JPEG image and it's size is less than 350Kb
  
  //retrieve the date.
  $date = datetimelocal('YmdHis-');
  $filebasename = $date.basename($filename);
  $ext = substr($filebasename, strrpos($filebasename, '.') + 1);
  if ($ext == "zip") 
  {
      $newname = $_SERVER['DOCUMENT_ROOT'].'/sssm/upload/'.$filebasename;
	  $downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/sssm/upload/'.$filebasename;
      if (!file_exists($newname)) 
	  {
        if ((move_uploaded_file($filetempname,$newname))) 
		{
           $result = "1^".$downloadlink; //Upload successfull
        } 
		else 
		{
           $result ="^". 4; //Problem dusring upload
        }
      } 
	  else 
	  {
         $result ="^". 3; //File already exists by same name
      }
  } 
  else 
  {
     $result = "^". 2; //Extension doesn't match
  }
  return $result;
}

/* ---------------------------- Upload Any through PHP  -------------------------------------- */
function uploadfile()
{
	$destination_path = getcwd().DIRECTORY_SEPARATOR;
	$result = 0;
	$target_path = $destination_path . basename( $_FILES['myfile']['name']);
	if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) 
	{
		$result = 1;
	}
	sleep(1);
}


/* -------------------- Download any file through PHP header -------------------- */
function downloadfile($filelink)
{
	$filename = basename($filelink);
	header('Content-type: application/octet-stream');
	header("Content-Disposition:attachment; filename=".$filename);
	readfile($filelink);
}

function checkemailaddress($email)
{
	// Basic RFC-like validation using filter_var as ereg_* is removed
	if (strlen($email) < 3 || strlen($email) > 320) return false;
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
	// Domain should have at least one dot or be an IP in brackets
	[$local, $domain] = explode('@', $email, 2);
	if (preg_match('/^\[[0-9\.]+\]$/', $domain)) {
		return true;
	}
	if (substr_count($domain, '.') < 1) return false;
	$labels = explode('.', $domain);
	foreach ($labels as $label) {
		if (!preg_match('/^(?:[A-Za-z0-9](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?)$/', $label)) {
			return false;
		}
	}
	return true;
}


function replacemailvariable($content,$array)
{
	$arraylength = count($array);
	for($i = 0; $i < $arraylength; $i++)
	{
		$splitvalue = explode('%^%',$array[$i]);
		$oldvalue = $splitvalue[0];
		$newvalue = $splitvalue[1];
		$content = str_replace($oldvalue,$newvalue,$content);
	}
	return $content;
}


// function to delete cookie and encoded the cookie name and value
function imaxdeletecookie($cookiename)
{
	 //Name Suffix for MD5 value
	 $stringsuff = "55";

	//Convert Cookie Name to base64
	$Encodename = encodevalue($cookiename);
	 //Append the encoded cookie name with 55(suffix ) for MD5 value
	 $rescookiename = $Encodename.$stringsuff;
	 
	//Set expiration to negative time, which will delete the cookie
	setcookie($Encodename ,"",time()-3600);
	setcookie($rescookiename, "",time()-3600);
	setcookie(session_name(), "",time()-3600);
}


// function to create cookie and encoded the cookie name and value
function imaxcreatecookie($cookiename,$cookievalue)
{

	 //Define prefix and suffix 
	 $prefixstring="AxtIv23";
	 $suffixstring="StPxZ46";
	 $stringsuff = "55";
	 
	 //Append Value with the Prefix and Suffix
	 $Appendvalue = $prefixstring . $cookievalue . $suffixstring;
	 
	 // Convert the Appended Value to base64
	 $Encodevalue = encodevalue( $Appendvalue);
	 
	 //Convert Cookie Name to base64
	 $Encodename = encodevalue($cookiename);

	 //Create a cookie with the encoded name and value
	 setcookie($Encodename,$Encodevalue, time()+2592000);
	
 	 //Convert Appended encode value to MD5
	 $rescookievalue = md5($Encodevalue);

	 //Appended the encoded cookie name with 55(suffix )
	 $rescookiename = $Encodename.$stringsuff;

	 //Create a cookie
	 setcookie($rescookiename,$rescookievalue, time()+2592000);
		 return false;

}

//Function to get cookie and encode it and validate
function imaxgetcookie($cookiename)
{

	$suff = "55";
	// Convert the Cookie Name to base64
	$Encodestr = encodevalue($cookiename);

	//Read cookie name
	$stringret = $_COOKIE[$Encodestr];
	$stringret = stripslashes($stringret);

	//Convert the read cookie name to md5 encode technique
	$Encodestring = md5($stringret);
	
	//Appended the encoded cookie name to 55(suffix)
	$resultstr = $Encodestr.$suff;
	$cookiemd5 = $_COOKIE[$resultstr];
	
	//Compare the encoded value wit the fetched cookie, if the condition is true decode the cookie value
	if($Encodestring == $cookiemd5)
	{
		$decodevalue = decodevalue($stringret);
		//Remove the Prefix/Suffix Characters
		$string1 = substr($decodevalue,7);
		$resultstring = substr($string1,0,-7);
		return $resultstring;
	}

	elseif(isset($Encodestring) == '')
	{
		return false;
	}
	else 
	{
		return false;
	}
	
}

//Function to logout (clear cookies)
function imaxuserlogout()
{
	
	session_start(); 
	session_unset();
	session_destroy(); 
	imaxdeletecookie('userid');
	imaxdeletecookie('checkpermission');
	imaxdeletecookie('sessionkind');
	imaxdeletecookie('verificationid');
}

function imaxuserlogoutredirect()
{
	
	imaxuserlogout();
	//$url = "../index.php";
	$url = "../index.php?link=".fullurl();
	header("Location:".$url);
	exit;	
}

function fullurl()
{
	
	$s = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 's' : '';
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function isvalidhostname()
{
	
	if($_SERVER['HTTP_HOST'] == 'bhavesh' || $_SERVER['HTTP_HOST'] == '192.168.2.132' || $_SERVER['HTTP_HOST'] == 'imax.relyonsoft.com')
		return true;
	else
		return false;
}

function isurl($url)
{
	
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

//Function to delete the file 
function fileDelete($filepath,$filename) 
{
	
	$success = FALSE;
	if (file_exists($filepath.$filename)&&$filename!=""&&$filename!="n/a") {
		unlink ($filepath.$filename);
		$success = TRUE;
	}
	return $success;	
}

function finalsplit($name)
{
	
	$array[]= explode(',', $name);
	for($j=0;$j<count($array);$j++)
	{
		$splitarray = $array[$j][0];
	}
	return $splitarray;
}

function firstletterupper($result_contact)
{
	
	$count = 0;
	$contact = explode(',', $result_contact);
	$array = array_map('trim', $contact);
	for($j=0;$j<count($array);$j++)
	{
		$res = "";
		$var1 = "";
		$res = strtolower(substr($array[$j],1));
		$var1 = strtoupper(substr($array[$j],0,1));
		$char1[] = $var1.$res;
	}
	$result1 = '';
	for($i=0;$i<count($char1);$i++)
	{
		if($count > 0)
		{
			$result1 .= ',';
		}
			$result1 .=  $char1[$i];
			$count++;
	}
	return $result1 ;
}

function roundnearestvalue($amount)
{
	
	$firstamount = round($amount,1);
	$amount1 = round($firstamount);
	return $amount1;
}

function decodevalue($input)
{
	
	$input = str_replace('\\\\','\\',$input);
	$input = str_replace("\\'","'",$input);
	$length = strlen($input);
	$output = "";
	for($i = 0; $i < $length; $i++)
	{
		if($i % 2 == 0)
			$output .= chr(ord($input[$i]) - 7);
	}
	$output = str_replace("'","\'",$output);
	return $output;
}

function encodevalue($input)
{
	
	$length = strlen($input);
	$output1 = "";
	for($i = 0; $i < $length; $i++)
	{
		$output1 .= $input[$i];
		if($i < ($length - 1))
			$output1 .= "a";
	}
	$output = "";
	for($i = 0; $i < strlen($output1); $i++)
	{
		$output .= chr(ord($output1[$i]) + 7);
	}
	return $output;
}

function generateemployeeid($employeeid)
{
	
	// Use the provided argument as region/category to avoid undefined variable notice
	$dealerregion = $employeeid;
	$query4 = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where category = '".$dealerregion."'";
	$resultfetch4 = runmysqlqueryfetch($query4);
	$onlineinvoiceno = $resultfetch4['invoicenotobeinserted'];
	$invoicenoformat = 'RSL/'.$dealerregion.'/'.$onlineinvoiceno;
	return $invoicenoformat;
}

function addlinebreak($linecount)
{
	
	switch($linecount)
	{
		case '1':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '2':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '3':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '4':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '5':
		{
			$linebreak = '<tr><td width="10%"><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		
	}
	return $linebreak;
}
function addlinebreak_bulkprint($linecount)
{
	
	switch($linecount)
	{
		case '1':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '2':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '3':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '4':
		{
			$linebreak = '<tr><td width="10%"><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		
		
	}
	return $linebreak;
}

function appendcomma($value)
{
	
	if($value != '')
	{
		$append = ',';
	}
	else
	{
		$append = '';
	}
	return $append;
}


function removedoublecomma($string)
{
	
	$finalstring = $string;
	$commas =explode(',',$string);
	$countcomma = count($commas);
	for($i=0;$i<$countcomma;$i++)
	{
		$outputstring = str_replace(',,',',',$finalstring);
		$finalstring =  $outputstring;
	}
	return $outputstring;
}


function remove_duplicates($str) 
{
	//in an array called $results
  preg_match_all("([\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7})",$str,$results);
	//sort the results alphabetically
  sort($results[0]);
	// remove duplicate results
	$unique = array_values(array_unique($results[0]));
	//process the array and return the remaining email addresses
	$str = "";
	foreach ($unique as $value)
  {
     $str .= $value.",";
  }
  return trim($str,',');
}

// Function to display amount in Indian Format (Eg:123456 : 1,23,456)
function formatnumber($number)
{
	if(is_numeric($number))
	{
		$numbersign = "";
		$numberdecimals = "";
		
		//Retain the number sign, if present
		if(substr($number, 0, 1 ) == "-" || substr($number, 0, 1 ) == "+")
		{
			$numbersign = substr($number, 0, 1 );
			$number = substr($number, 1);
		}
		
		//Retain the decimal places, if present
		if(strpos($number, '.'))
		{
			$position = strpos($number, '.'); //echo($position.'<br/>');
			$numberdecimals = substr($number, $position); //echo($numberdecimals.'<br/>');
			$number = substr($number, 0, ($position)); //echo($number.'<br/>');
		}
		
		//Apply commas
		if(strlen($number) < 4)
		{
			$output =  $number;
		}
		else
		{
			$lastthreedigits = substr($number, -3);
			$remainingdigits = substr($number, 0, -3);
			$tempstring = "";
			for($i=strlen($remainingdigits),$j=1; $i>0; $i--,$j++)
			{
				if($j % 2 <> 0)
					$tempstring = ','.$tempstring;
				$tempstring = $remainingdigits[$i-1].$tempstring;
			}
			$output = $tempstring.$lastthreedigits;
		}
		$finaloutput = $numbersign.$output.$numberdecimals;
		return $finaloutput;	
	}
	else
	{
		$finaloutput = 0;
		return $finaloutput;
	}
}

//Function to convert the number to words
function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
    	throw new Exception("Number is out of range");
    } 
	 
	$cn = floor($number / 10000000); /* Crores */
	$number -= $cn * 10000000;   
	
	$ln = floor($number / 100000);  /* Lakhs */
	$number -= $ln * 100000;
	
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
	
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
	
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;             /* Ones */ 

    $res = ""; 


	if($cn)
	{
		 $res .= convert_number($cn) . " Crore"; 
	}
	
	if($ln)
	{
		$res .= (empty($res) ? "" : " ") . 
            convert_number($ln) . " Lakh";
	}
    if ($kn) 
    { 
		
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 


function matcharray($array1,$array2)
{
	$found = false;
	for($i = 0; $i < count($array1); $i++)
	{
		if(in_array($array1[$i],$array2))
		{
			$found = true;
			break;
		}
	}
	return $found;
}

function audit_trail($userid, $ipaddr, $datetime, $activity, $eventtype)
{	
	$query = "INSERT INTO saral_audit (userid, ipaddr, datetime, activity_type,eventtype) 
	values('".$userid."', '".$ipaddr."', '".$datetime."', '".$activity."','".$eventtype."')";
	$result = runmysqlquery($query);
}
function productname()
{
	$query = "select productname from saral_products order by productname";
	$result = runmysqlquery($query);
	if(mysql_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysql_fetch_array($result))
	{
		echo('<option value="'.$fetch['productname'].'">'.$fetch['productname'].'</option>');
	}
}

function category()
{
	$query = "SELECT cid, category FROM email_mas_category ORDER BY category desc "; 
	$result = runmysqlquery($query);
	
	if(mysql_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysql_fetch_array($result))
	{
		echo('<option value="'.$fetch['cid'].'">'.$fetch['category'].'</option>');
	}
}

function forwards()
{
	/*$query = "SELECT email,forwards FROM email_acc_record WHERE deleted='NO' ORDER BY email"; 
	$result = queryhb($query);                  
	if(mysql_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysql_fetch_array($result))
	{
		echo('<option value="'.$fetch['email'].'">'.$fetch['email'].'</option>');
	}*/
	
	$query = "SELECT grouphead,forwarder FROM email_grouphead ORDER BY grouphead"; 
	$result = runmysqlquery($query);                  
	if(mysql_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysql_fetch_array($result))
	{
		if($fetch['forwarder'] <> '')
		{
			echo('<option value="'.$fetch['forwarder'].'">'.$fetch['grouphead']. ' | ('.$fetch['forwarder'].')</option>');
		}
	}

	
}

function grouphead()
{
	$query = "SELECT * FROM email_grouphead ORDER BY grouphead"; 
	$result = runmysqlquery($query);                  
	if(mysql_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysql_fetch_array($result))
	{
		echo('<option value="'.$fetch['id'].'">'.$fetch['grouphead'].'</option>');
	}
}

function department()
{
	$query = "SELECT * FROM saral_job_required_depatment ORDER BY department"; 
	$result = runmysqlquery($query);                  
	if(mysql_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysql_fetch_array($result))
	{
		echo('<option value="'.$fetch['department'].'">'.$fetch['department'].'</option>');
	}
}

##Mail Setup for version ##
function productmail($sub,$file_htm,$file_txt)
{
	//global $myemail;
	global $form_product;
	global $form_productcode;
	global $form_patch;
	global $form_filesize;
	global $DPC_date;
	global $form_verfrom;
	global $form_url;
	global $show_web;
	global $check_web;
	global $form_hotfix;
	global $form_title;
	global $form_desc;
	global $form_disable;
	global $form_link;
	global $userid;
	global $femail;
	
	if($DPC_date <> '0000-00-00')
	{
		$validtill = date("d M Y",strtotime($DPC_date));
	}
	else
	{
		$validtill = $DPC_date ;
	}

	$query = "select email,fname from saral_admins where adminid=".$userid;
	$fetchresult = runmysqlqueryfetch($query);
	$useremailid = $fetchresult['email'];
	$userfname = $fetchresult['fname'];
	
	
#########  Mailing Starts -----------------------------------
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
	{
		$mymail = 'hejalkumari.p@relyonsoft.com';
	}
	else
	{
		$mymail = $useremailid;
	}
	#$mymail = 'webmaster@relyonsoft.com,bhavesh.d@relyonsoft.com';
	$emailarray = explode(',',$mymail);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$mymails[$emailarray[$i]] = $emailarray[$i];
		}
	}
	$mail = 'imax@relyon.co.in';
	$fromname = 'Relyon Softech Ltd - Webmaster';
	$fromemail = $mail;
	$msg = file_get_contents($file_htm);
	$textmsg = file_get_contents($file_txt);
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$array = array();
	$array[] = "##PRODUCT##%^%".$form_product;
	$array[] = "##CODE##%^%".$form_productcode;
	$array[] = "##PATCH##%^%".$form_patch;
	$array[] = "##SIZE##%^%".$form_filesize;
	$array[] = "##DATE##%^%".$validtill;
	$array[] = "##VERFROM##%^%".$form_verfrom;
	$array[] = "##URL##%^%".$form_url;
	$array[] = "##SHOWINWEB##%^%".$show_web;
	$array[] = "##CHECKINWEB##%^%".$check_web;
	$array[] = "##HOTFIX##%^%".$form_hotfix;
	$array[] = "##TITLE##%^%".$form_title;
	$array[] = "##DESC##%^%".$form_desc;
	$array[] = "##DISABLE##%^%".$form_disable;
	$array[] = "##LINK##%^%".$form_link;
	$array[] = "##USER##%^%".$userid;
	
	$textarray = array();
	$textarray[] = "##PRODUCT##%^%".$form_product;
	$textarray[] = "##CODE##%^%".$form_productcode;
	$textarray[] = "##PATCH##%^%".$form_patch;
	$textarray[] = "##SIZE##%^%".$form_filesize;
	$textarray[] = "##DATE##%^%".$validtill;
	$textarray[] = "##VERFROM##%^%".$form_verfrom;
	$textarray[] = "##URL##%^%".$form_url;
	$textarray[] = "##SHOWINWEB##%^%".$show_web;
	$textarray[] = "##CHECKINWEB##%^%".$check_web;
	$textarray[] = "##HOTFIX##%^%".$form_hotfix;
	$textarray[] = "##TITLE##%^%".$form_title;
	$textarray[] = "##DESC##%^%".$form_desc;
	$textarray[] = "##DISABLE##%^%".$form_disable;
	$textarray[] = "##LINK##%^%".$form_link;
	$textarray[] = "##USER##%^%".$userid;
	
	$toarray = $mymails;
	
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
	{
		$bccmymails['bhumika'] = 'bhumika.p@relyonsoft.com';
	}
	else
	{
		$bccmymails['bigmail'] ='bigmail@relyonsoft.com';
		$bccmymails['Bhavesh'] ='bhavesh@relyonsoft.com';
		$bccmymails['Webmaster'] ='webmaster@relyonsoft.com';
		$bccmymails['IMAX-SUPPORT'] ='imax.support@relyonsoft.com';
	}
	$bccarray = $bccmymails;
	
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$textarray);
	$subject = $sub;
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html, null,$bccarray, null);
}

##Saral Mail send##

function saral_mail($sub,$file_htm,$file_txt,$tosend,$ccto)
{
	//global $myemail;
	global	$form_emailid ;
	global	$form_email;
	global	$form_quota;
	global	$form_password ;
	global	$form_cid ;
	global	$form_createddate;
	global	$form_employee ;
	global	$form_employeeid ;
	global	$form_department;
	global	$form_forwards;
	global	$form_head;
	global	$form_grouphead ;
	global	$form_requestedby ;
	global	$form_deleted;
	global	$form_deleteddate;
	global	$form_remarks ;
	global	$form_reason ;
	global	$form_passremarks;
	global	$check_disable;
	global	$form_disable;
	global  $disabledate;
	global  $email_domain;
	global  $form_disablepass;
	global  $form_changepass;
	global  $form_headmail;
	global  $userid;
	global  $femail;
	
	$query = "select email,fname from saral_admins where adminid=".$userid;
	$fetchresult = runmysqlqueryfetch($query);
	$useremailid = $fetchresult['email'];
	$userfname = $fetchresult['fname'];
	
	
	
#########  Mailing Starts -----------------------------------
	## Mail Will be received from ajax files. . as Remote server validated##
	$mymail = $tosend;
	
	$emailarray = explode(',',$mymail);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$mymails[$emailarray[$i]] = $emailarray[$i];
		}
	}
	$mail = 'imax@relyon.co.in';
	$fromname = 'Webmaster';
	$fromemail = $mail;
	$msg = file_get_contents($file_htm);
	$textmsg = file_get_contents($file_txt);
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$array = array();
	$array[] = "##EMAIL##%^%".$form_email;
	$array[] = "##QUOTA##%^%".$form_quota;
	$array[] = "##PASS##%^%".$form_password;
	$array[] = "##CID##%^%".$form_cid;
	$array[] = "##DATE##%^%".date("d M Y",strtotime($form_createddate));
	$array[] = "##NAME##%^%".$form_employee;
	$array[] = "##EMPID##%^%".$form_employeeid;
	$array[] = "##DEPART##%^%".$form_department;
	$array[] = "##FORWARD##%^%".$form_forwards;
	$array[] = "##HEAD##%^%".$form_head;
	$array[] = "##GROUPHEAD##%^%".$form_grouphead;
	$array[] = "##REQUEST##%^%".$form_requestedby;
	$array[] = "##DELETED##%^%".$form_deleted;
	$array[] = "##DDATE##%^%".date("d M Y",strtotime($form_deleteddate));
	$array[] = "##REMARKS##%^%".$form_remarks;
	$array[] = "##REASON##%^%".$form_reason;
	$array[] = "##PASSREMARKS##%^%".$form_passremarks;
	$array[] = "##CHECKBOX##%^%".$check_disable;
	$array[] = "##DISABLE##%^%".$form_disable;
	$array[] = "##DOMAIN##%^%".$email_domain;
	$array[] = "##DISABLEDATE##%^%".date("d M Y",strtotime($disabledate));
	$array[] = "##DISABLEPASS##%^%".$form_disablepass;
	$array[] = "##RESETPASSWORD##%^%".$form_changepass;
	$array[] = "##GROUPHEADMAIL##%^%".$form_headmail;
	$array[] = "##USER##%^%".$userid;
				
	$textarray = array();
	$textarray[] = "##EMAIL##%^%".$form_email;
	$textarray[] = "##QUOTA##%^%".$form_quota;
	$textarray[] = "##PASS##%^%".$form_password;
	$textarray[] = "##CID##%^%".$form_cid;
	$textarray[] = "##DATE##%^%".date("d M Y",strtotime($form_createddate));
	$textarray[] = "##NAME##%^%".$form_employee;
	$textarray[] = "##EMPID##%^%".$form_employeeid;
	$textarray[] = "##DEPART##%^%".$form_department;
	$textarray[] = "##FORWARD##%^%".$form_forwards;
	$textarray[] = "##GROUPHEAD##%^%".$form_grouphead;
	$textarray[] = "##HEAD##%^%".$form_head;
	$textarray[] = "##REQUEST##%^%".$form_requestedby;
	$textarray[] = "##DELETED##%^%".$form_deleted;
	$textarray[] = "##DDATE##%^%".date("d M Y",strtotime($form_deleteddate));
	$textarray[] = "##REMARKS##%^%".$form_remarks;
	$textarray[] = "##REASON##%^%".$form_reason;
	$textarray[] = "##PASSREMARKS##%^%".$form_passremarks;
	$textarray[] = "##CHECKBOX##%^%".$check_disable;
	$textarray[] = "##DISABLE##%^%".$form_disable;
	$textarray[] = "##DOMAIN##%^%".$email_domain;
	$textarray[] = "##DISABLEDATE##%^%".date("d M Y",strtotime($disabledate));
	$textarray[] = "##DISABLEPASS##%^%".$form_disablepass;
	$textarray[] = "##RESETPASSWORD##%^%".$form_changepass;
	$textarray[] = "##GROUPHEADMAIL##%^%".$form_headmail;
	$textarray[] = "##USER##%^%".$userid;
	

	$toarray = $mymails;
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
	{
		## New Email ID Creation##
		if($ccto == 'ALL')
		{
			$ccmymails['Webmaster'] ='hejalkumari.p@relyonsoft.com';
		}
		##DISABLE or ENABLE##
		elseif($ccto == 'HR')
		{
			$ccmymails['HR'] ='bhumika.p@relyonsoft.com';
		}
		##RESET PASSWORD##
		elseif($ccto == 'PASS')
		{
			$ccmymails['SysadminDO'] = 'hejalkumari.p@relyonsoft.com';
		}
		##DEALER / CLIENT EMAIL##
		elseif($ccto == 'DEALER')
		{
			$ccmymails['Webmaster'] ='bhumika.p@relyonsoft.com';
		}
	}
	else
	{	
		## New Email ID Creation##
		if($ccto == 'ALL')
		{
			$ccmymails['Webmaster'] = 'webmaster@relyonsoft.com';
			$ccmymails['HR'] = 'hr@relyonsoft.com';
			$ccmymails['Pavansysadmin'] = 'sysadmin.co@relyonsoft.com';
			$ccmymails['Pradeepsysadmin'] = 'sysadmin.do@relyonsoft.com';
		}
		##DISABLE or ENABLE##
		elseif($ccto == 'HR')
		{
			$ccmymails['Webmaster'] ='webmaster@relyonsoft.com';
			$ccmymails['HR'] ='hr@relyonsoft.com';
		}
		##RESET PASSWORD##
		elseif($ccto == 'PASS')
		{
			$ccmymails['Webmaster'] ='webmaster@relyonsoft.com';
			$ccmymails['Pavansysadmin'] = 'sysadmin.co@relyonsoft.com';
			$ccmymails['Pradeepsysadmin'] = 'sysadmin.do@relyonsoft.com';
		}
		##DEALER / CLIENT EMAIL##
		elseif($ccto == 'DEALER')
		{
			$ccmymails['Webmaster'] ='webmaster@relyonsoft.com';
		}
		else
		{
			$ccmymails['Webmaster'] ='webmaster@relyonsoft.com';
		}
		
	}
	$ccarray = $ccmymails;
	
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
	{
		$bccmymails['Bhavesh'] ='bhavesh@relyonsoft.com';
	}
	else
	{	
		$bccmymails['bigmail'] ='bigmail@relyonsoft.com';
		$bccmymails[$userfname] = $useremailid;
		$bccmymails['IMAX-SUPPORT'] ='imax.support@relyonsoft.com';
		$bccmymails['Bhavesh'] ='bhavesh@relyonsoft.com';
	}
	$bccarray = $bccmymails;
	
	
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$textarray);
	$subject = $sub;
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html, $ccarray,$bccarray, null);
}

?>