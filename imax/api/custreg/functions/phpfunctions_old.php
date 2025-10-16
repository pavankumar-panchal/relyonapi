<?php

//Include Database Configuration details

if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

//Connect to host
$newconnection = mysql_connect($dbhost, $dbuser, $dbpwd) ;

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

function getbannerimage($type)
{
	switch($type)
	{
		case 'solutions': return "../images/relyonweb-banner-solutions.gif"; break;
		case 'company': return "../images/relyonweb-banner-company.gif"; break;
		case 'customers': return "../images/relyonweb-banner-customers.gif"; break;
		case 'support': return "../images/relyonweb-banner-support.gif"; break;
		case 'careers': return "../images/relyonweb-banner-careers.gif"; break;
		case 'more': return "../images/relyonweb-banner-10-years.gif"; break;
		case 'contactus': return "../images/relyonweb-banner-contact.gif"; break;
		case 'home': return generaterandomimage(); break;
		default:
			return "../images/relyonweb-banner-10-years.gif"; 
			break;
	}
}

function generaterandomimage()
{
	$inputimages = array("../images/relyonweb-banner-home-online-payments.gif", "../images/relyonweb-banner-home-tds.gif", "../images/relyonweb-banner-10-years.gif");
	$rand_image = array_rand($inputimages, 1);
	return $inputimages[$rand_image];
}

/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $newconnection;
	$dbname = 'relyonso_imaxd';
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
	$dbname = 'relyonso_imaxd';
	
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

	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");

	$cusstatecode = $query['statecode'];

	$newcustomerid = $cusstatecode.$district.$generatedealer.$productcode.$customerid;

	return $newcustomerid;

}	



/*//Function to generate Online Bill In PDF format

function generatepdfbill($firstbillnumber,$custreference,$onlineinvoiceno,$invoicenoformat)

{

	require_once('../pdfbillgeneration/tcpdf.php');

	

	// create new PDF document

	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	

	// remove default header/footer

	$pdf->setPrintHeader(false);

	$pdf->setPrintFooter(false);

	

	// set header and footer fonts

	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	

	// set default monospaced font

	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	

	//set margins

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	

	//set auto page breaks

	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	

	//set image scale factor

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

	

	//set some language-dependent strings

	$pdf->setLanguageArray($l); 

	

	

	// set font

	$pdf->SetFont('Helvetica', '',10);

	

	// add a page

	$pdf->AddPage();

	

	$query = "select inv_mas_customer.businessname as companyname,inv_mas_customer.contactperson,inv_mas_customer.phone,inv_mas_customer.cell,

inv_mas_customer.emailid,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branchname,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename as statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch

left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$custreference."';";



	$fetchresult = runmysqlqueryfetch($query);

	

	// Fetch contact Details 

	

	$querycontactdetails = "select group_concat(phone) as phone,group_concat(cell) as cell ,group_concat(emailid) as emailid,group_concat(contactperson) as contactperson from inv_contactdetails where customerid = '".$custreference."'";

	$fetchcontactdetails = runmysqlqueryfetch($querycontactdetails);

	$arrayreplace = array(',,,',',,');

	

	$query1 = "SELECT inv_mas_product.productcode as productcode , inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno, inv_mas_scratchcard.scratchnumber as pinno FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product

ON inv_mas_product.productcode = inv_dealercard.productcode  WHERE inv_dealercard.cusbillnumber = '".$firstbillnumber."';";

	$result = runmysqlquery($query1);

	

	$resultcount = mysql_num_rows($result);

	

	



	$query2 = "SELECT inv_billdetail.productamount from inv_billdetail where inv_billdetail.cusbillnumber = '".$firstbillnumber."';";

	$result2 = runmysqlquery($query2);

	while($fetch2 = mysql_fetch_array($result2))

	{

		$amount[] = $fetch2['productamount'];

	}

		

	$query3 = "Select * from inv_bill where inv_bill.slno = '".$firstbillnumber."'";

	$result3 = runmysqlqueryfetch($query3);

	

	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';

	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description of Sale</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';

	$k = 0;

	$descriptioncount = 0;

	$appendzero = '.00';

	$servicetaxdesc = 'Service Tax applicable under Service Tax Act: "Taxable Service" category "zzze" (information technology software).';

	while($fetch = mysql_fetch_array($result))

	{

		$slno++;

		$grid .= '<tr>';

		$grid .= '<td width="10%" style="text-align:centre;">'.$slno.'</td>';

		if($fetch['purchasetype'] == 'new')

			$purchasetype = 'New';

		else

			$purchasetype = 'Updation';

		if($fetch['usagetype'] == 'singleuser')

			$usagetype = 'Single User';

		else

			$usagetype = 'Multi User';

		$grid .= '<td width="76%" style="text-align:left;">'.$fetch['productname'].'<br/>

<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$purchasetype.'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$usagetype.'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number </strong>: '.$fetch['pinno'].' (<strong>Serial</strong> : '.$fetch['cardno'].')</span></td>';

		$grid .= '<td  width="14%" style="text-align:right;" >'.$amount[$k].$appendzero.'</td>';

		$grid .= "</tr>";

		if($descriptioncount > 0)

			$description .= '*';

		$description .= $slno.'$'.$fetch['productname'].'$'.$purchasetype.'$'.$usagetype.'$'.$fetch['pinno'].'$'.$fetch['cardno'].'$'.$amount[$k];

		$k++;

		$descriptioncount++;

	  }

	  if($resultcount < 8)

	 {

		$addline = addlinebreak($resultcount);

		$grid .= $addline;

	 }

	 $amountinwords = convert_number($result3['netamount']);

	 $grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right" valign="top">'.$result3['total'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" > '.$servicetaxdesc.'</span></td>

  <td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td>

  <td  width="14%" style="text-align:right">'.$result3['taxamount'].$appendzero.'</td></tr><tr>

  <td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td>

  <td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>

<td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$result3['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$amountinwords.' only</td></tr>';



	$grid .='</table></td></tr></table>';

		

	$emailid = explode(',',trim(str_replace($arrayreplace,',',$fetchcontactdetails['emailid']),','));

	$emailidplit = $emailid[0];

	$phonenumber = explode(',',trim(str_replace($arrayreplace,',',$fetchcontactdetails['phone']),','));

	$phonenumbersplit = $phonenumber[0];

	$cellnumber = explode(',',trim(str_replace($arrayreplace,',',$fetchcontactdetails['cell']),','));

	$cellnumbersplit = $cellnumber[0];

	$contactperson = explode(',',trim(str_replace($arrayreplace,',',$fetchcontactdetails['contactperson']),','));

	$contactpersonplit = $contactperson[0];

	$stdcode = ($fetchresult['stdcode'] == '')?'':$fetchresult['stdcode'].' - ';

	$address = $fetchresult['address'].', '.$fetchresult['place'].', '.$fetchresult['districtname'].', '.$fetchresult['statename'].', Pin: '.$fetchresult['pincode'];

	$invoiceheading = ($fetchresult['statename'] == 'Karnataka')?'Tax Invoice':'Bill Of Sale';



	$query2 = "select remarks from  pre_online_purchase where onlineinvoiceno = '".$result3['onlineinvoiceno']."';";

	$result2 = runmysqlqueryfetch($query2);

	

	$remarks = ($result2['remarks'] == '')?'None':$result2['remarks'];

	

	$invoicequery = "update inv_invoicenumbers set description = '".$description."', amount = '".$result3['total']."',servicetax = '".$result3['taxamount']."', netamount = '".$result3['netamount']."', customerid = '".cusidcombine($fetchresult['customerid'])."',phone =  '".$phonenumbersplit."',cell = '".$cellnumbersplit."',emailid = '".$emailidplit."',contactperson = '".$contactpersonplit."',stdcode = '".$stdcode."',customertype = '".$fetchresult['customertype']."',customercategory = '".$fetchresult['businesstype']."',region = '".$fetchresult['region']."',branch ='".$fetchresult['branchname']."',pincode = '".$fetchresult['pincode']."',address ='".addslashes($address)."', amountinwords = '".$amountinwords."', remarks = '".$remarks."', servicetaxdesc = '".$servicetaxdesc."', invoiceheading = '".$invoiceheading."' where slno  ='".$onlineinvoiceno."';";

	$invoiceresult = runmysqlquery($invoicequery);

	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");

	



	$array = array();

	$array[] = "##BILLDATE##%^%".date('d/m/Y');

	$array[] = "##BILLNO##%^%".$invoicenoformat;

	$array[] = "##BUSINESSNAME##%^%".$fetchresult['companyname'];

	$array[] = "##CONTACTPERSON##%^%".$contactpersonplit;

	$array[] = "##PHONE##%^%".$phonenumbersplit;

	$array[] = "##CELL##%^%".$cellnumbersplit;

	$array[] = "##EMAILID##%^%".$emailidplit;

	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];

	$array[] = "##ADDRESS##%^%".$address;

	$array[] = "##STDCODE##%^%".$stdcode;

	$array[] = "##CUSTOMERID##%^%".cusidcombine($fetchresult['customerid']);

	$array[] = "##BRANCH##%^%".$fetchresult['branchname'];

	$array[] = "##REGION##%^%".$fetchresult['region'];

	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];

	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];

	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['businesstype'];

	$array[] = "##REMARKS##%^%".$remarks;

	$array[] = "##TABLE##%^%".$grid;

	$array[] = "##GENERATEDBY##%^%".'Webmaster';

	$html = replacemailvariable($msg,$array);

	$pdf->WriteHTML($html,true,0,true);

		

	$localtime = date('His');

	$filebasename = str_replace('/','-',$invoicenoformat).".pdf";

	$addstring ="/";

	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab")

		$addstring = "/relyonsoft.com";

		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/upload/'.$filebasename;

	

	$pdf->Output($filepath ,'F');

	return $filebasename;

	//$pdf->Output('example.pdf' ,'I');	

}*/

/*function vieworgeneratepdfinvoice($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	$query1 = "select * from inv_invoicenumbers where slno = '".$slno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$invoicestatus = $resultfetch1['status'];
	if($invoicestatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image

			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'invoicing-cancelled-background.jpg';
			$this->Image($img_file, 0, 80, 820, 648, '', '', '', false, 75, '', false, false, 0);

			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}

		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}

	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// remove default header
		$pdf->setPrintHeader(false);
	}

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set margins

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 

	// remove default footer
	$pdf->setPrintFooter(false);

	// set font
	$pdf->SetFont('Helvetica', '', 10);

	// add a page
	$pdf->AddPage();

	$query = "select * from inv_invoicenumbers where inv_invoicenumbers.slno = '".$slno."';";
	$result = runmysqlquery($query);

	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';

	while($fetch = mysql_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);

		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($fetch['purchasetype'] == 'SMS')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[2].'</td>';
				$grid .= "</tr>";
			}
			else
			{
				if($description <> '')
				{
					$grid .= '<tr>';
					$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
					$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>

			<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#FF3300">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span></td>';

					$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[6].$appendzero.'</td>';
					$grid .= "</tr>";

				}

			}

		}

		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);

		$servicedescriptioncount = count($servicedescriptionsplit);

		if($fetch['servicedescription'] <> '')

		{

			for($i=0; $i<$servicedescriptioncount; $i++)

			{

				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);

				$grid .= '<tr>';

				$grid .= '<td width="10%" style="text-align:centre;">'.$servicedescriptionline[0].'</td>';

				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'</td>';

				$grid .= '<td  width="14%" style="text-align:right;" >'.$servicedescriptionline[2].$appendzero.'</td>';

				$grid .= "</tr>";

			}

		}

		

		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);

		$offerdescriptioncount = count($offerdescriptionsplit);

		if($fetch['offerdescription'] <> '')

		{

			for($i=0; $i<$offerdescriptioncount; $i++)

			{

				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);

				$grid .= '<tr>';

				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';

				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[0]).': '.$offerdescriptionline[1].'</td>';

				$grid .= '<td  width="14%" style="text-align:right;" >'.$offerdescriptionline[2].$appendzero.'</td>';

				$grid .= "</tr>";

			}

		}



		if($fetch['offerremarks'] <> '')

			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';

		if($fetch['description'] == '')

			$offerdescriptioncount = 0;

		else

			$offerdescriptioncount = count($descriptionsplit);

		if($fetch['offerdescription'] == '')

			$descriptioncount = 0;

		else

			$descriptioncount = count($descriptionsplit);

		if($fetch['servicedescription'] == '')

			$servicedescriptioncount = 0;

		else

			$servicedescriptioncount = count($servicedescriptionsplit);

		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount ;

		if($rowcount < 8)

		{

			$grid .= addlinebreak($rowcount);



		}

		

		if($fetch['status'] == 'EDITED')

		{

			$query011 = "select * from inv_mas_users where slno = '".$fetch['editedby']."';";

			$resultfetch011 = runmysqlqueryfetch($query011);

			$changedby = $resultfetch011['fullname'];

			$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch['editeddate']).' <br/>Remarks: '.$fetch['editedremarks'];

		}

		elseif($fetch['status'] == 'CANCELLED')

		{

			$query011 = "select * from inv_mas_users where slno = '".$fetch['cancelledby']."';";

			$resultfetch011 = runmysqlqueryfetch($query011);

			$changedby = $resultfetch011['fullname'];

			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch['cancelleddate']).'  <br/>Remarks: '.$fetch['cancelledremarks'];



		}

		else

			$statusremarks = '';
			
		$invoicedatedisplay = substr($fetch['createddate'],0,10);
		$invoicedate =  strtotime($invoicedatedisplay);
		$expirydate = strtotime('2012-04-01');
		$expirydate1 = strtotime('2015-06-01');
		$expirydate2 = strtotime('2015-11-15');

		if($fetch['seztaxtype'] == 'yes')
		{
			$sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
			
			if($expirydate >= $invoicedate || $expirydate1 > $invoicedate)
			{
				$servicetax1 = 0;
				$servicetax2 = 0;
				$servicetax3 = 0;
			
				$servicetaxname = '<br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.
				formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate2 > $invoicedate)
			{
				$servicetax1 = 0;
				$totalservicetax = formatnumber($servicetax1).$appendzero;
			}
			else
			{
				$servicetax1 = 0;
				$totalservicetax = formatnumber($servicetax1).$appendzero;
				$servicetaxname1 = 'SB Cess @ 0.5%';
				$servicetax2 = 0;
				$totalservicetax1 = $servicetax2.$appendzero;
				
				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
				<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';
			}
		}
		else
		{
			if($expirydate >= $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.1);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 10% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate1 > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.12);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 12% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate2 > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
				$servicetaxname = 'Service Tax @ 14%';
				$totalservicetax = formatnumber($servicetax1).$appendzero;
			}
			else
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
				$servicetax2 = roundnearestvalue($fetch['amount'] * 0.005);
				$servicetaxname = 'Service Tax @ 14%';
				$servicetaxname1 = 'SB Cess @ 0.5%';
				$totalservicetax = formatnumber($servicetax1).$appendzero;
				$totalservicetax1 = formatnumber($servicetax2).$appendzero;
				
				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
				<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';
			}
			
			$sezremarks = '';
			
		}
		$billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit;
		$grid .= '<tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].' </span></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td><td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn.'<tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Total</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch['netamount']).$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';





	$grid .='</table></td></tr></table>';

	$fetchresult = runmysqlqueryfetch($query);

	//to fetch dealer email id 

	$query0 = "select inv_mas_dealer.emailid as dealeremailid,cell as dealercell from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";

	$fetch0 = runmysqlqueryfetch($query0);

	$dealeremailid = $fetch0['dealeremailid'];

	$dealercell = $fetch0['dealercell'];

	if($fetchresult['status'] == 'CANCELLED')

	{

		$color = '#FF3300';

		$invoicestatus = '( '.$fetchresult['status'].' )';

	}

	else if($fetchresult['status'] == 'EDITED')

	{

		$color = '#006600';

		$invoicestatus = '( '.$fetchresult['status'].' )';

	}

	else

	{

		$invoicestatus = '';

	}

	$podatepiece = (($fetchresult['podate'] == "0000-00-00") || ($fetchresult['podate'] == ''))?("Not Avaliable"):(changedateformat($fetchresult['podate']));

	$poreferencepiece = ($fetchresult['poreference'] == "")?("Not Avaliable"):($fetchresult['poreference']);

	

	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");

	$array = array();

	$stdcode = $fetchresult['stdcode'];

	$array[] = "##BILLDATE##%^%".$billdatedisplay;

	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];

	$array[] = "##STATUS##%^%".$invoicestatus;

	$array[] = "##DEALERDETAILS##%^%".'( '.$dealeremailid.',&nbsp;'.$dealercell.')';

	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];

	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];

	$array[] = "##ADDRESS##%^%".$fetchresult['address'];

	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];

	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];

	$array[] = "##PHONE##%^%".$fetchresult['phone'];

	$array[] = "##CELL##%^%".$fetchresult['cell'];

	$array[] = "##STDCODE##%^%".$stdcode;

	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];

	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['customercategory'];

	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];

	$array[] = "##REGION##%^%".$fetchresult['region'];

	$array[] = "##BRANCH##%^%".$fetchresult['branch'];

	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];

	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];

	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];

	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];

	$array[] = "##PODATE##%^%".$podatepiece;

	$array[] = "##POREFERENCE##%^%".$poreferencepiece;

	

	$array[] = "##TABLE##%^%".$grid;*/
	
	
//commented 	
	
function vieworgeneratepdfinvoice($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	$query1 = "select * from inv_invoicenumbers where slno = '".$slno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$invoicestatus = $resultfetch1['status'];
	$invoicenewformate= changedateformat(substr($resultfetch1['createddate'],0,10));
	$newyeardate = "31-03-2014";
	if($invoicestatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image
			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'invoicing-cancelled-background.jpg';
			$this->Image($img_file, 0, 80, 820, 648, '', '', '', false, 75, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}
	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
	}

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	// remove default footer
	$pdf->setPrintFooter(false);
	
	// set font
	$pdf->SetFont('Helvetica', '', 10);
	
	// add a page
	//$pdf->AddPage();
	

//Added 01.07.2017

	// set certificate file
    $certificate = 'file:///etc/digitalsign/relyon.crt';

    // set additional information
    $info = array(
        'Name' => 'Relyon Softech Ltd.',
        'Location' => 'Bangalore',
        'Reason' => 'Digitally Signed Invoice',
        'ContactInfo' => 'http://www.relyonsoft.com',
        );
//Ends        
	
	// set font
	$pdf->SetFont('Helvetica', '', 10);
	
	// add a page
	$pdf->AddPage();
	
//Added on 01.07.2017

     // set document signature
    $pdf->setSignature($certificate, $certificate, '123', '', 2, $info);
    
    
    
    // create content for signature (image and/or text)
    //$pdf->Image('../pdfbillgeneration/images/tcpdf_signature.png',5, 5, 15, 15, 'PNG');
   // $pdf->Image('../pdfbillgeneration/images/relyon-logo.png',130, 248, 65, 30, 'PNG');
    
    // define active area for signature appearance
    $pdf->setSignatureAppearance(130, 248, 65, 30);

//Ends
	
	$final_amount = 0;
	$query = "select * from inv_invoicenumbers where inv_invoicenumbers.slno = '".$slno."';";
	$result = runmysqlquery($query);
	$fetchresult = runmysqlqueryfetch($query);
	
	$appendzero = '.00';
	if(strtotime($invoicenewformate) <= strtotime($newyeardate))
	{
		$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
		$grid .='<tr><td ><table width="100%" border="1" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid"><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	}
	else
	{
		$grid .='<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" >';
		$grid .='<tr><td ><table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="border:1px solid"><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	}
        $countslno=1;
	while($fetch = mysql_fetch_array($result))
	{
		$description = $fetch['description'];
		$productbriefdescription = $fetch['productbriefdescription'];
		$productbriefdescriptionsplit = explode('#',$productbriefdescription);
		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$productdesvalue = '';
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($productbriefdescription <> '')
				$productdesvalue = $productbriefdescriptionsplit[$i];
			else
				$productdesvalue = 'Not Available';
			/*if($fetch['purchasetype'] == 'SMS')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$countslno.'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[2].'</td>';
				$grid .= "</tr>";
                                $countslno++;

			}
			else
			{*/
                             
				if($description <> '')
				{
					$grid .= '<tr>';
					$grid .= '<td width="10%" style="text-align:centre;">'.$countslno.'</td>';
					$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
			<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#000000">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span><br/><span style="font-size:+6" ><strong>Product Description</strong> : '.$productdesvalue.' </span><span style="font-size:+6" > / <strong>SAC</strong> : 997331</span></td>';
					$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($descriptionline[6]).$appendzero.'</td>';
					$grid .= "</tr>";
					
					$final_amount = $final_amount + $descriptionline[6];
                                        $incno++;
                                        $countslno++;
				}
			//}
		}
		$itembriefdescription = $fetch['itembriefdescription'];
		$itembriefdescriptionsplit = explode('#',$itembriefdescription);
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
                              

				$itemdesvalue = '';
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				if($itembriefdescription <> '')
					$itemdesvalue = $itembriefdescriptionsplit[$i];
				else
					$itemdesvalue = 'Not Available';
					
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$countslno.'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'<br/><span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span> / <span style="font-size:+6" ><strong>SAC:</strong> 997331</span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($servicedescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
				$final_amount = $final_amount + $servicedescriptionline[2];
                                $countslno++;
                            
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
		    $grid .= '<tr><td width="10%" style="text-align:centre;">&nbsp;</td><td width="76%" style="text-align:left;">Gross Amount</td><td  width="14%" style="text-align:right;" >'.formatnumber($final_amount).$appendzero.'</td></tr>';
		    
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				
				if($offerdescriptionline[0] == 'percentage' || $offerdescriptionline[0] == 'amount')
				{
				    $grid .= '<td width="76%" style="text-align:left;">'.$offerdescriptionline[1].'</td>';
				}
				else
				{
				    $grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[0]).': '.$offerdescriptionline[1].'</td>';
				}
				
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($offerdescriptionline[2]).'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';
		$descriptionlinecount = 0;
		if($description <> '')
		{
			//Add description "Internet downloaded software"
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:center;"><font color="#666666">INTERNET DOWNLOADED SOFTWARE</font></td><td width="14%">&nbsp;</td></tr>';
			$descriptionlinecount = 1;
		}
		if($fetch['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount + $descriptionlinecount;
		if($rowcount < 6)
		{
			$grid .= addlinebreak($rowcount);

		}
		
		if($fetch['status'] == 'EDITED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['editedby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch['editeddate']).' <br/>Remarks: '.$fetch['editedremarks'];
		}
		elseif($fetch['status'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['cancelledby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch['cancelleddate']).'  <br/>Remarks: '.$fetch['cancelledremarks'];

		}
		else
			$statusremarks = '';
			//echo($statusremarks); exit;
			
		$invoicedatedisplay = substr($fetch['createddate'],0,10);
		$invoicedate =  strtotime($invoicedatedisplay);
		$expirydate = strtotime('2012-04-01');
		$expirydate1 = strtotime('2015-06-01');
		$expirydate2 = strtotime('2015-11-15');
		$KK_Cess_date = strtotime('2016-05-31');
		
		//$gst_date = '2017-06-08'; // used to get date from gst_rates
		$gst_date = date('Y-m-d');
		$gst_tax_date = strtotime('2017-07-01');
		
		
		//gst rate fetching
		
		$gst_tax_query= "select igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '$gst_date' AND to_date >= '$gst_date'";
		$gst_tax_result = runmysqlqueryfetch($gst_tax_query);
		$igst_tax_rate = $gst_tax_result['igst_rate'];
		$cgst_tax_rate = $gst_tax_result['cgst_rate'];
		$sgst_tax_rate = $gst_tax_result['sgst_rate'];
		
		//gst rate fetching ends
		/*----------------------------*/
       
        $search_customer =  str_replace("-","",$fetch['customerid']);
        $customer_details = "select inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,
        inv_mas_district.statecode as state_code,inv_mas_state.statename as statename
        ,inv_mas_state.state_gst_code