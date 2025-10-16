<?php
//Include Database Configuration details
if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

//Connect to host
$newconnection = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");   //- Cannot connect to Mysql server host - Check Imax Database
$dbh = new PDO("mysql:host=$dbhost;dbname=relyon_imax", $dbuser, $dbpwd);//PDO dbconnection for PHP7
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$newconnection_old = mysql_connect($dbhost_old, $dbuser_old, $dbpwd_old,true) or die("Cannot connect to Mysql server host"); // - Cannot connect to Mysql server host - Check reyonsoft.com Database

//passkey
define("CONSTANTKEY","sudhindra@tds");

/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $newconnection;
	$dbname = 'relyon_imax';

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database".$query);
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in runquery function.".$query); //;

	//Return the result
	return $result;
}

function runmysqlquery_old($query)
{
	global $newconnection_old;
	$dbname = 'relyon_imax';

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("Cannot connect to database2".$query);
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection_old) or die(" run Query Failed in runquery function.".$query); //;

	//Return the result
	return $result;
}

/* -------------------- Run a query to database with fetching from SELECT operation -------------------- */
function runmysqlqueryfetch($query)
{
	global $newconnection;
	$dbname = 'relyon_imax';

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in runquery function.".$query); //;

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result) or die ("Cannot fetch the query result.".$query);

	//Return the result
	return $fetchresult;
}

function runmysqlqueryfetch_old($query)
{
	global $newconnection_old;
	$dbname = 'relyon_imax';

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection_old) or die(" run Query Failed in runquery function.".$query); //;

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result) or die ("Cannot fetch the query result.".$query);

	//Return the result
	return $fetchresult;
}



function runqueryuserlogin2($query)
{

	global $newconnection;
	$dbname = "relyon_lms";

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection) or die("P2");

	//Return the result
	return $result;
}

function runqueryfetchuserlogin2($query)
{
	global $newconnection;
	$dbname = "relyon_lms";

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection) or die("P2");

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result);

	//Return the result
	return $fetchresult;
}

function runqueryuserlogin2_old($query)
{

	global $newconnection_old;
	$dbname = "relyonso_userlogin2";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die("P2");

	//Return the result
	return $result;
}

function runqueryfetchuserlogin2_old($query)
{
	global $newconnection_old;
	$dbname = "relyonso_userlogin2";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die("P2");

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result);

	//Return the result
	return $fetchresult;
}

function runquery_logs($query)
{

	global $newconnection_old;
	$dbname = "relyonso_logs";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die("P2");

	//Return the result
	return $result;
}


function runmysqlquerytdsflash($query)
{

	global $newconnection_old;
	$dbname = "relyon_saraltds";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die("P2");

	//Return the result
	return $result;
}

function runmysqlqueryfetchtdsflash($query)
{
	global $newconnection_old;
	$dbname = "relyon_saraltds";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die("P2");

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result);

	//Return the result
	return $fetchresult;

	//Close the database connection
	//mysql_close($connection);
}



/* -------------------- Run a query to database -------------------- */
function runmysqlquerystoflash($query)
{
	global $newconnection_old;
	$dbname = "relyon_saraltaxoffice";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("Cannot connect to database");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die(mysql_error());

	//Return the result
	return $result;
}

function runmysqlqueryfetchstoflash($query)
{
	global $newconnection_old;
	$dbname = "relyon_saraltaxoffice";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die("P2");

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result);

	//Return the result
	return $fetchresult;

}

/* -------------------- Run a query to database -------------------- */
function runmysqlquerysppflash($query)
{

	global $newconnection_old;
	$dbname = "relyon_saralpaypack";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("Cannot connect to database");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die(mysql_error());

	//Return the result
	return $result;
}

function runmysqlqueryfetchsppflash($query)
{
	global $newconnection_old;
	$dbname = "relyon_saralpaypack";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("P2");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die("P2");

	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result);

	//Return the result
	return $fetchresult;
}

function runmysqlqueryflashnews($query)
{	global $newconnection_old;
	$dbname = "relyonso_userlogin2";

	//Connect to Database
	mysql_select_db($dbname,$newconnection_old) or die("Cannot connect to database");

	//Run the query
	$result = mysql_query($query,$newconnection_old) or die(mysql_error());

	//Return the result
	return $result;
}

function changedateformat($getdate)
{
	$temp = explode("-", $getdate);
	$retdate = $temp[2]."-".$temp[1]."-".$temp[0];
	return $retdate;
}


function changedateformaticai($getdate)
{
	$temp = explode("-", $getdate);
	$retdate = $temp[2]."/".$temp[1]."/".$temp[0];
	return $retdate;
}

function changedateformaticainew($getdate)
{
	$temp = explode("/", $getdate);
	$retdate = $temp[2]."-".$temp[1]."-".$temp[0];
	return $retdate;
}

function floatconvert($value)
{
	$value = $value + 0.01 - 0.01;
	return $value;
}
function cusidsplit($customerid)
{
	$strlen = strlen($customerid);
	if($strlen <> '17')
	{
		if(strpos($customerid, " "))
		$result = split(" ",$customerid);
		else
		$result = split("[:./-]",$customerid);
		$customerid = $result[0].$result[1].$result[2].$result[3];
	}
	/*else
	{
		$customerid = "";
	}*/
		return $customerid;
}

function validatecustomerid($customerid)
{
	//return true;
	if((strlen($customerid) == 17) || $customerid == "Bank")
	{
		return true;
	}
	else if((strlen($customerid) == 20))
	{
		if (!ereg("^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{5}$", $customerid))
		{
			return false;
		}
		else
			return true;
	}
	else
		return false;
}


function validatecomputerid($computerid)
{
	//35300
	if(!ereg("^[0-9]{5}$", $computerid))
	{
		if(!ereg("^[0-9]{3}0[0|9]-[0-9]{9}$", $computerid))
			return false;
		else
			return true;
	}
	else
	{
		return true;
	}
}

function validatemobileno($mobileno)
{
	if(!ereg("^[987][0-9]{9}$", $mobileno))
		return false;
	else
		return true;
}

function validatedob($dob)
{
	//if (!ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $dob))
	if (!ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $dob))
		return false;
	else
		return true;
}

function validatemembershipno($membershipno)
{
	if (!ereg ("^[0-9]{6}$", $membershipno))
		return false;
	else
		return true;
}

function rcidatarestricted($rcidata)
{
	$rcidatasplit = explode('^',$rcidata);
	$rcidatacount = count($rcidatasplit);
	if($rcidatacount == 7)
	{
		$customerid = $rcidatasplit[0];
		$registeredname = $rcidatasplit[1];
		$pinnumber = $rcidatasplit[2];
		$computerid = $rcidatasplit[3];
		$productversion = $rcidatasplit[4];
		$operatingsystem = $rcidatasplit[5];
		$processor = $rcidatasplit[6];
		if((validatecustomerid($customerid) == true) && ($registeredname <> '') && ($pinnumber <> '') && (validatecomputerid($computerid) == true) && ($productversion <> '') && ($operatingsystem <> '') && ($processor <> ''))
		{
			$rcidatavalidata = true;
			return $rcidatavalidata;
		}
	}
}

function rcidatanotrestricted($rcidata)
{
	$rcidatasplit = explode('^',$rcidata);
	$rcidatacount = count($rcidatasplit);
	if($rcidatacount == 7)
	{
		$customerid = $rcidatasplit[0];
		$registeredname = $rcidatasplit[1];
		$pinnumber = $rcidatasplit[2];
		$computerid = $rcidatasplit[3];
		$productversion = $rcidatasplit[4];
		$operatingsystem = $rcidatasplit[5];
		$processor = $rcidatasplit[6];
		if((validatecustomerid($customerid) == true || $customerid == '')  && (validatecomputerid($computerid) == true || $computerid == '') && ($productversion <> '') && ($operatingsystem <> '') && ($processor <> ''))
		{
			$rcidatavalidata = true;
			return $rcidatavalidata;
		}
	}
}

function validateicaivalues($dob,$mobileno,$email,$computerid,$membershipno)
{
	if((validatedob($dob) == true) && (validatemobileno($mobileno) == true) && (checkemailaddress($email) == true) && (validatecomputerid($computerid) == true) && (validatemembershipno($membershipno) == true))
	{
		return 'true';
	}
	else
		return 'false';
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


function gridtrimtext($value)
{
	$desiredlength = 60;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}
function checkemailaddress($email)
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++)
	{
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
		{
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
	{
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2)
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++)
		{
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i]))
			{
				return false;
			}
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

function validatedata($membershipno,$dob)
{
	$validate = 'notfound';
	if($validate == 'notfound')
	{
		$datavalue = getdata($membershipno,$dob,'W');
		$datavaluesplit = explode('^',$datavalue);
		$data = $datavaluesplit[0];
		$validate = ($data == 'true')?"found":"notfound";
	}
	if($validate == 'notfound')
	{
		$datavalue = getdata($membershipno,$dob,'S');
		$datavaluesplit = explode('^',$datavalue);
		$data = $datavaluesplit[0];
		$validate = ($data == 'true')?"found":"notfound";
	}
	if($validate == 'notfound')
	{
		$datavalue = getdata($membershipno,$dob,'E');
		$datavaluesplit = explode('^',$datavalue);
		$data = $datavaluesplit[0];
		$validate = ($data == 'true')?"found":"notfound";
	}
	if($validate == 'notfound')
	{
		$datavalue = getdata($membershipno,$dob,'C');
		$datavaluesplit = explode('^',$datavalue);
		$data = $datavaluesplit[0];
		$validate = ($data == 'true')?"found":"notfound";
	}
	if($validate == 'notfound')
	{
		$datavalue = getdata($membershipno,$dob,'N');
		$datavaluesplit = explode('^',$datavalue);
		$data = $datavaluesplit[0];
		$validate = ($data == 'true')?"found":"notfound";
	}
		return  $datavalue;
}


function getdata($membershipno,$dob,$regioncode)
{
  //Continue with further process
  $fp = false;
  $value = '0';
  $req = "&VTI-GROUP=".$value."&T1=".$membershipno."&T2=".$dob."&T3=".$regioncode."";
  $posturl = "http://220.225.242.179/memcard.asp";

  $ch = curl_init($posturl);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_HEADER,0);  // DO NOT RETURN HTTP HEADERS
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $Rec_Data = curl_exec($ch);
  curl_close($ch);
  $res = $Rec_Data;
  if($res)
  {
	 # if(stristr($res,'No records returned.') || stristr($res,'The page cannot be found'))
	 if(stristr($res,'Enrolment Dt'))
	  {
		  $message = "true";
	  }
	  else
	  {
		  $message = "false";
	  }
  }
  else
	  $message = "false";

  return $message.'^'.$res;

}

function generatecustomerid($customerid,$productcode,$delaerrep)
{
	$query = "SELECT * FROM inv_mas_customer where slno = '".$customerid."'";
	$fetch = runmysqlqueryfetch($query);
	$district = $fetch['district'];
	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");
	$cusstatecode = $query['statecode'];
	$newcustomerid = $cusstatecode.$district.$delaerrep.$productcode.$customerid;
	return $newcustomerid;
}

function validateFormat($computerid)
{
	if(preg_match("/^\d{5}-\d{9}$/", $computerid))
	return true;
}
function specialtrimicai($input)
{
	$output = str_replace('&nbsp;','',$input);
	$output = str_replace('&amp;','',$output);
	$output = str_replace("\r","",$output);
	$output = str_replace("\n","",$output);
	$output = str_replace("\t","",$output);
	$output = str_replace("  ","",$output);
	$output = str_replace(",,",",",$output);
	return trim($output,', ');

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

function cusidcombine($customerid)
{
	$result1 = substr($customerid,0,4);
	$result2 = substr($customerid,4,4);
	$result3 = substr($customerid,8,4);
	$result4 = substr($customerid,12,5);
	$result = $result1.'-'.$result2.'-'.$result3.'-'.$result4;
	return $result;
}

function sendfreeupdationcardemail($customerreference,$cardid)
{
	$query5 = "select inv_mas_customer.slno,inv_mas_customer.businessname,inv_mas_customer.customerid, inv_mas_customer.place, inv_mas_product.productname,inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_customer on inv_mas_customer.slno = inv_dealercard.customerreference
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode
left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
where inv_dealercard.customerreference = '".$customerreference."' and inv_dealercard.cardid = '".$cardid."';";
	$result = runmysqlqueryfetch($query5);

	// Fetch Contact Details

	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$result['slno']."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysql_fetch_array($resultfetch))
	{
		if(checkemailaddress($fetchres['emailid']))
		{
			if($fetchres['contactperson'] != '')
				$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
			else
				$emailids[$fetchres['emailid']] = $fetchres['emailid'];
		}
		$contactperson = $fetchres['contactperson'];
		$emailid = $fetchres['emailid'];
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$phoneres .= $phone;
		$phoneres .= appendcomma($phone);
		$cellres .= $cell;
		$cellres .= appendcomma($cell);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($fetchres['emailid']);
	}
	$date = datetimelocal('d-m-Y');
	$businessname = $result['businessname'];
	$contactperson = trim($contactvalues,',');
	$place = $result['place'];
	$customerid = $result['customerid'];
	$productname = $result['productname'];
	$pinno = $result['pinno'];
	$dealername = $result['dealername'];
	$emailid = trim($emailidres,',');
	//Dummy line to override To email ID
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
		$emailid = 'rashmi.hk@relyonsoft.com';
	else
		$emailid = $emailid;

	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("mailcontents/manualcuscardattach.htm");
	$textmsg = file_get_contents("mailcontents/manualcuscardattach.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##CARDID##%^%".$cardid;
	$array[] = "##DEALERNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	$filearray = array(
		array('images/relyon-logo.jpg','inline','8888888888'),
	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
	{
		$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';
	}
	else
	{
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "You have been issued with a PIN Number for ICAI-Payroll registration.";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);

	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs('2',$customerreference,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);

}

function sendregistrationemail($customerproductslno,$userid)
{
	$query = "Select
	inv_mas_customer.businessname as businessname,
	inv_mas_customer.place as place,
	inv_mas_customer.customerid as customerid,inv_mas_customer.slno as slno,
	inv_customerproduct.computerid as computerid,
	inv_customerproduct.softkey as softkey,inv_customerproduct.dealerid as dealerid,
	inv_mas_scratchcard.scratchnumber as pinno,
	inv_mas_product.productname as productname from inv_customerproduct Left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
	where inv_customerproduct.slno = '".$customerproductslno."'";
	$result = runmysqlqueryfetch($query);

	// fetch Contact Details
	$querycontactdetails = "select  emailid,contactperson from inv_contactdetails where customerid = '".$result['slno']."'";
	$resultcontactdetails = runmysqlquery($querycontactdetails);
	// contact Details
	$contactvalues = '';
	$phoneres = '';
	$cellres = '';
	$emailidres = '';

	while($fetchcontactdetails = mysql_fetch_array($resultcontactdetails))
	{
		$contactperson = $fetchcontactdetails['contactperson'];
		$emailid = $fetchcontactdetails['emailid'];

		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
	}
	$contactperson = trim($contactvalues,',');
	$businessname = $result['businessname'];
	$place = $result['place'];
	$customerid = $result['customerid'];
	$customerslno = $result['slno'];
	$productname = $result['productname'];
	$pinno = $result['pinno'];
	$computerid = $result['computerid'];
	$softkey = $result['softkey'];
	$dealerid = $result['dealerid'];

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
		$emailid = 'rashmi.hk@relyonsoft.com';
	else
		$emailid = trim($emailidres,',');

	$query = "Select emailid from inv_mas_dealer where slno = '".$dealerid."'";
	$fetch = runmysqlqueryfetch($query);
	$bcceallemailid = $fetch['emailid'];
	//$bcceallemailid = 'meghana.b@relyonsoft.com';
	 //BCC to dealer
	$bccemailarray = explode(',',$bcceallemailid);
	$bccemailcount = count($bccemailarray);
		//Dummy line to override To email ID
	//$emailid = 'meghana.b@relyonsoft.com';
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	for($i = 0; $i < $bccemailcount; $i++)
	{
		if(checkemailaddress($bccemailarray[$i]))
		{
				$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
				if($i == 0 && $bccemailarray[$i] <> '')
					$bccids = $bccemailarray[$i];
				else if($bccemailarray[$i] <> '')
					$bccids .= ','.$bccemailarray[$i];
		}
	}
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("mailcontents/customerregistration.htm");
	$textmsg = file_get_contents("mailcontents/customerregistration.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$businessname;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##COMPUTERID##%^%".$computerid;
	$array[] = "##SOFTKEY##%^%".$softkey;
	$array[] = "##EMAILID##%^%".$emailid;

	$filearray = array(
		array('images/registration-icon.gif','inline','1234567890'),
		array('images/relyon-logo.jpg','inline','8888888888'),

	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
	{
		$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';	}
	else
	{
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Registration availed for ICAI-Payroll";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);
	//Insert the mail forwarded details to the logs table
	$bccmailid = $bccids.','.'bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);

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

/* -------------------- Get local server time [by adding 5.30 hours] -------------------- */
function datetimelocal($format)
{
	//$diff_timestamp = date('U') + 19800;
	$date = date($format);
	return $date;
}

function inserttologs($userid,$id,$fromname,$emailfrom,$emailto,$ccmailids,$bccemailids,$subject)
{
	$module = 'web_module';
	$sentthroughip = $_SERVER['REMOTE_ADDR'];
	$query = "insert into inv_logs_mails(userid,id,fromname,emailfrom,emailto,ccmailids,bccmailids,subject,date,fromip,module) values('".$userid."','".$id."','".$fromname."','".$emailfrom."','".$emailto."','".$ccmailids."','".$bccemailids."','".$subject."','".date('Y-m-d').' '.date('H:i:s')."','".$sentthroughip."','".$module."');";
	$result = runmysqlquery($query);
}

function validatecellicai($cell)
{
	if(substr($cell,0,1) == '0')
		$cellvalue = substr($cell,1,10);
	else if(substr($cell,0,3) == '+91')
		$cellvalue = substr($cell,3,10);
	else if(substr($cell,0,3) == '+91-')
		$cellvalue = substr($cell,4,10);
	else
		$cellvalue = $cell;
	$result = validatemobileno($cellvalue);
	return $result.'^'.$cellvalue;
}


?>
