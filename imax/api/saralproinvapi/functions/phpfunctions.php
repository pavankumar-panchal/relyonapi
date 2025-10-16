<?php

//Include Database Configuration details

if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

//Connect to host
$newconnection = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to mysqli server host");
define("API_KEY","8b2c7bd4dd7bdfbb285cf7c497adb38f");


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

/* -------------------- Run a query for ICIC database -------------------- */
function runicicidbquery($query)
{
	global $newconnection;
	$icicidbname = "relyon_icici";

	 //Connect to Database
	 mysql_select_db($icicidbname,$newconnection) or die("Cannot connect to database");
	 set_time_limit(3600);

	 //Run the query
	 $result = mysql_query($query,$newconnection) or die(mysql_error());
	 
	 //Return the result
	 return $result;
}
/* -------------------- Run a query for Relyonsoft database -------------------- */

function runrelyonsoftdbquery($query)
{
	 global $newconnection;
	 $relyonsoftdbname = "relyon_relyonsoft";

	 //Connect to Database
	 
	 mysql_select_db($relyonsoftdbname,$newconnection) or die("Cannot connect to database");
	 set_time_limit(3600);

	 //Run the query
	 $result = mysql_query($query,$newconnection) or die(mysql_error());

	 //Close the database connection
	 mysql_close($connection);

	 //Return the result
	 return $result;
}

/* -------------------- Run a query FETCH for Relyonsoft database -------------------- */
function runrelyonsoftdbqueryfetch($query)
{
	 global $newconnection;
	 $relyonsoftdbname = "relyon_relyonsoft";

	 //Connect to Database
	 mysql_select_db($relyonsoftdbname,$newconnection) or die("Cannot connect to database");
	 set_time_limit(3600);

	 //Run the query
	 $result = mysql_query($query,$newconnection) or die(mysql_error());
	 
	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result,$newconnection) or die ("Cannot fetch the query result.".$query);

	//Return the result
	return $fetchresult;
}


// function to delete cookie and encoded the cookie name and value

function rsldeletecookie($cookiename)
{
	 //Name Suffix for MD5 value
	 $stringsuff = "55";

	//Convert Cookie Name to base64
	$Encodename = encodevalue($cookiename);

	 //Append the encoded cookie name with 55(suffix ) for MD5 value
	 $rescookiename = $Encodename.$stringsuff;

	//Set expiration to negative time, which will delete the cookie
	setcookie($Encodename,"",  time()-3600,"/");
	setcookie($rescookiename,"",  time()-3600,"/");
}



// function to create cookie and encoded the cookie name and value

function rslcreatecookie($cookiename,$cookievalue)

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

	 setcookie($Encodename,$Encodevalue,time()+3600,"/");

	//echo($cookiename.'++'.$cookievalue); exit;

 	 //Convert Appended encode value to MD5

	 $rescookievalue = md5($Encodevalue);

	 //Appended the encoded cookie name with 55(suffix )

	 $rescookiename = $Encodename.$stringsuff;



	 //Create a cookie

	 setcookie($rescookiename,$rescookievalue,time()+3600,"/");

	 return false;

	 	 

}



//Function to get cookie and encode it and validate

function rslgetcookie($cookiename)

{

	$suff = "55";



	// Convert the Cookie Name to base64

	$Encodestr = encodevalue($cookiename);



	//Read cookie name

	$stringret = $_COOKIE[$Encodestr];



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

function rslcookiedelete()
{
	session_start(); 
	session_unset();
	session_destroy(); 
	rsldeletecookie('selectedproducts');
	rsldeletecookie('relyonid');
	rsldeletecookie('purchasetype');
	rsldeletecookie('cuslastslno');
	rsldeletecookie('customerid');
	rsldeletecookie('dealerid');
}

//To set products for Buy online, by storing multiple products in same cookie name.	

function buyproduct($slno)

{

	if(rslgetcookie('selectedproducts') <> '')

	{

		$arraylist  = array();

		$arraylist = rslgetcookie('selectedproducts');

		$listvalue = explode('#',$arraylist );

		if(in_array($slno, $listvalue, true))

		{

			return false;

		}

		else

		{

			$value = rslcreatecookie('selectedproducts',rslgetcookie('selectedproducts').'#'.$slno);

			

			return true;

		}

		

	}

	else

	{

		rslcreatecookie('selectedproducts',$slno);

		return true;

	}

}

/* -------------------- Get local server time [by adding 5.30 hours] -------------------- */
function datetimelocal($format)
{
    //$diff_timestamp = date('U') + 19800;
    $date = date($format);
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

	$query = runmysqliqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");

	$cusstatecode = $query['statecode'];

	$newcustomerid = $cusstatecode.$district.$generatedealer.$productcode.$customerid;

	return $newcustomerid;

}	



//Function to convert the Number to words 

function convert_number($number) 

{ 

	if (($number < 0) || ($number > 999999999)) 

	{ 

		throw new Exception("Number is out of range");

	} 



    $Gn = floor($number / 1000000);  /* Millions (giga) */ 

    $number -= $Gn * 1000000; 

    $kn = floor($number / 1000);     /* Thousands (kilo) */ 

    $number -= $kn * 1000; 

    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 

    $number -= $Hn * 100; 

    $Dn = floor($number / 10);       /* Tens (deca) */ 

    $n = $number % 10;               /* Ones */ 

    $res = ""; 

    if ($Gn) 

    { 

        $res .= convert_number($Gn) . " Million"; 

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



function cusidcombine($customerid)

{

	$result1 = substr($customerid,0,4);

	$result2 = substr($customerid,4,4);

	$result3 = substr($customerid,8,4);

	$result4 = substr($customerid,12,5);

	$result = $result1.'-'.$result2.'-'.$result3.'-'.$result4;

	return $result;

}

function getprice($pricevalue)
{
	$productpricevalue = $pricevalue.".00";
	return $productpricevalue;
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


function firstletterupper($result_contact)

{

	$count = 0;

	$contact = split(' ',$result_contact);

	$array = array_map('trim', $contact);

	for($j=0;$j<count($array);$j++)

	{

		$res = "";

		$var1 = "";

		$res = strtolower(substr($array[$j],1));

		$var1 = strtoupper(substr($array[$j],0,1));

		$char1[] = $var1.$res;

	}

	for($i=0;$i<count($char1);$i++)

	{

		if($count > 0)

		{

			$result1 .= ' ';

		}

			$result1 .=  $char1[$i];

			$count++;

	}

	return $result1 ;

}



function gridtrim($value)

{

	$desiredlength = 15;

	$length = strlen($value);

	if($length >= $desiredlength)

	{

		$value = substr($value, 0, $desiredlength);

		$value .= "...";

	}

	return $value;

}

function gridtrim40($value)
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



/* -------------------- To change the date format from DD-MM-YYYY to YYYY-MM-DD or reverse -------------------- */

function changedateformat($date)

{

	if($date <> "0000-00-00" && $date <> "00-00-0000" && $date <> "")

	{

		$result = explode("-",$date);

		$date = $result[2]."-".$result[1]."-".$result[0];

	}

	else

	{

		$date = "";

	}

	return $date;

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


function roundnearestvalue($value)

{

	return round($value);

	}

// this function is used for more secure way for compares two strings (e.g., the provided API key and the stored API key) and prevents timing attacks
function hash_equals($known_string, $user_string) 
{
    if (strlen($known_string) !== strlen($user_string)) {
        return false;
    }
    $res = 0;
    for ($i = 0; $i < strlen($known_string); $i++) {
        $res |= (ord($known_string[$i]) ^ ord($user_string[$i]));
    }
    return $res === 0;
}

function getBearerToken() {
    // Check if the Authorization header is present
    $headers = apache_request_headers();
    
    if (isset($headers['Authorization'])) {
        $authorizationHeader = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $authorizationHeader = $headers['authorization'];
    } else {
        return null;
    }

    // Extract the token from the header
    if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
        return $matches[1]; // Return the Bearer token
    }

    return null;
}
?>