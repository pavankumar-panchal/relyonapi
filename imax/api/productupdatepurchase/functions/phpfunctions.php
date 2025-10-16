<?php

//Include Database Configuration details

if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

//Connect to host
$newconnection = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");

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
			<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#000000">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span><br/><span style="font-size:+6" ><strong>Product Description</strong> : '.$productdesvalue.' </span><span style="font-size:+6" > / <strong>SAC</strong> : 998434</span></td>';
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
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'<br/><span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span> / <span style="font-size:+6" ><strong>SAC:</strong> 998434</span></td>';
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
        ,inv_mas_state.state_gst_code as state_gst_code from inv_mas_customer 
        left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
        left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
        where inv_mas_customer.customerid like '%".$search_customer."%'";

        $fetch_customer_details = runmysqlqueryfetch($customer_details);
        
        //$customer_gstin = substr($fetch_customer_details['gst_no'],0,2);
        //$state_details = "select statename,state_gst_code from inv_mas_state where statecode = '".$customer_gstin."'";
        
        //echo $state_details;
        //exit();
        //$fetch_state_details = runmysqlqueryfetch($state_details);
       
       /*---------------------------*/

		//echo $invoicedate ;echo $sb_expirydate;
		//echo $invoicedate; echo $sb_expirydate; 
			
/*-----------------SEZ and NON-SEZ Check---------------------------*/



		if($fetch['seztaxtype'] == 'yes' || $fetch_customer_details['sez_enabled'] == 'yes')
		{
			if($fetch['seztaxtype'] == 'yes')
			{
			    $sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
			}			
			
			if($gst_tax_date <= $invoicedate)
		    {
		        /*$igst_tax_amount = roundnearestvalue($fetch['amount'] * ($igst_tax_rate/100));
            	$gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td>*/
            	
            	//echo $fetch['cgst'];
            	//exit();
            	
            	//if($fetch['igst'] != 0 || $fetch['seztaxtype'] == 'yes')
            	if($fetch['cgst'] == '0' &&  $fetch['sgst'] == '0')
            	{
            	    $gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>IGST Tax @'.$igst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['igst']).'</td></tr>';
            	}
            	else
            	{
            	    $gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>CGST Tax @'.$cgst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['cgst']).'</td></tr><tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>SGST Tax @'.$sgst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['sgst']).'</td></tr>';
            	}
            	
            	
            	//echo $gst_tax_column;
            	//echo "mine";
		        //exit();
		    }
		    else
		    {
		        //echo "Good Here";
		        //exit();
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
            				$servicetaxname2 = 'KK Cess @ 0.5%';
            				$servicetax3 = 0;
            				$totalservicetax1 = $servicetax2.$appendzero;
            				
            				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
            				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
            				<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
            				</td></tr>';
                        	if($KK_Cess_date < $invoicedate)
                        		{
                        			$kkcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
                        			<td  width="30%" style="text-align:right"><strong>'.$servicetaxname2.'</strong></td>
                        			<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
                        				</td></tr>';
                        		}
            			}
		    }
		    
		            $billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
				$grid .= '<tr>
				<td  width="56%" style="text-align:left"><span style="font-size:+6" >Accounting Code For Service</span></td>
				<td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>
				<td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>
				<tr>
				<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
				<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn.$gst_tax_column;
		}
		else
		{
		    if($gst_tax_date <= $invoicedate)
		    {
		        //echo "mine";
		        //echo $gst_tax_date."<br>"; 
		        //echo $invoicedate;
		        //exit();
		        
		        //echo $fetch['cgst'];
            //exit();
		        
		        //if($fetch_customer_details['state_code'] == '29')//if Relyon and Customer are in same State
		        if($fetch['cgst'] != '0' &&  $fetch['sgst'] != '0')
		        {
		           // $cgst_tax_amount = roundnearestvalue($fetch['amount'] * ($cgst_tax_rate/100));
		           // $sgst_tax_amount = roundnearestvalue($fetch['amount'] * ($sgst_tax_rate/100));
		            
                	$gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td>
                	<td  width="30%" style="text-align:right"><strong>CGST Tax @'.$cgst_tax_rate.'% </strong></td>
                	<td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['cgst']).'</td></tr>';
                	
                	$gst_tax_column .='<tr><td  width="56%" style="text-align:right"></td>
                	<td  width="30%" style="text-align:right"><strong>SGST Tax @'.$sgst_tax_rate.'% </strong></td>
                	<td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['sgst']).'</td></tr>';
		        }
		        else
		        {
		            //$igst_tax_amount = roundnearestvalue($fetch['amount'] * ($igst_tax_rate/100));
                    
                    $gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td>
                    <td  width="30%" style="text-align:right"><strong>IGST Tax @'.$igst_tax_rate.'% </strong></td>
                    <td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['igst']).'</td></tr>';
		        }

            	
            	
        $billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit; // To be added Here
		$grid .= '<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6" ></span></td>
		<td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>
		<td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>
		<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
		<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn.$gst_tax_column;
		    
		        
		    }
		    else
		    {
		        //echo "minetrtrt";
		        //echo $gst_tax_date."<br>"; 
		        //echo $invoicedate;
		        //exit();
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
            
            				if($KK_Cess_date < $invoicedate)
            				{
            	               $KK_Cess_tax = roundnearestvalue($fetch['amount'] * 0.005);
            				   $kkcolumn='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>KK Cess @ 0.5% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($KK_Cess_tax).$appendzero.'</td></tr>';
            				}
            			}
            			
            			
            			$billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit; // To be added Here
		$grid .= '<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].' </span></td>
		<td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>
		<td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>
		<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
		<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn.$gst_tax_column;
		    }//else condition ends		
			
			$sezremarks = '';
			
		}




/*-----------------Round Off ----------------------*/
  $roundoff = 'false';
  $roundoff_value = '';
  $addition_amount = $fetch['amount']+$fetch['igst']+$fetch['cgst']+$fetch['sgst']+$fetch['kktax']+$fetch['sbtax']+$fetch['servicetax'];
  
 $roundoff_value = ($fetch['netamount'])- ($addition_amount);

if($roundoff_value != 0 || $roundoff_value != 0.00)
{
  $roundoff = 'true';
}

/*----Round Off Done ---------------------------*/


/*------------------Check Ends --------------------------*/

if($roundoff == 'true')
{
	$roundoff_value = number_format($roundoff_value,2);
$grid .= '<tr>
<td  width="56%" style="text-align:right"><div align="left"></div></td>
<td  width="30%" style="text-align:right"><strong>Round Off</strong></td>
<td  width="14%" style="text-align:right">&nbsp;&nbsp;'.$roundoff_value.'</td> 
</tr>';
}

$grid .= '<tr>
<td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td>
<td  width="30%" style="text-align:right"><strong>Total</strong></td>
<td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" alt="Relynsoft" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch['netamount'] ).$appendzero.'</td> 
</tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.convert_number($fetch['netamount']).' only</td></tr>';

	//echo($grid); exit;
	//	$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" alt="Relynsoft" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	  }

	$grid .='</table></td></tr></table>';
	
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
	
	$podatepiece = (($fetchresult['podate'] == "0000-00-00") || ($fetchresult['podate'] == ''))?("Not Available"):(changedateformat($fetchresult['podate']));
	$poreferencepiece = ($fetchresult['poreference'] == "")?("Not Available"):($fetchresult['poreference']);
	if(strtotime($invoicenewformate) <= strtotime($newyeardate))
	{
	  $msg = file_get_contents("../pdfbillgeneration/bill-format-old.php");
	}
	else
	{
		$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	}
	if($gst_tax_date <= $invoicedate)
	{
	    $msg = file_get_contents("../pdfbillgeneration/bill-format-gst.php");
	}
	
	
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".$billdatedisplay;
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##STATUS##%^%".$invoicestatus;
	$array[] = "##color##%^%".$color;
	$array[] = "##DEALERDETAILS##%^%".'Email: '.$dealeremailid.' | Cell: '.$dealercell;
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];
	$array[] = "##ADDRESS##%^%".stripslashes ( stripslashes ( $fetchresult['address']));
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
	
	$array[] = "##INVOICEDT##%^%".$resultfetch1['createddate'];
	if($fetch_customer_details['gst_no'] != '')
	{
        $array[] = "##CUSTOMERGSTIN##%^%".$fetch_customer_details['gst_no'];
	}
	else
	{
	    $novalus = 'Not Registered Under GST';
	    $array[] = "##CUSTOMERGSTIN##%^%".$novalus;
	}
    $array[] = "##POP##%^%".$fetch_customer_details['statename'];
    $array[] = "##CODE##%^%".$fetch_customer_details['state_gst_code'];
    
	
	$array[] = "##TABLE##%^%".$grid;	
        
    if(($resultfetch1['deduction'] == '1') && ($resultfetch1['tanno'] != ''))
    {
        $array[] = "##NOTE##%^%".$note;
        $array[] = "##CONTENT##%^%".$content;
    }
    else 
    {
        $note = "";$content = ""; $array[] = "##NOTE##%^%".$note;$array[] = "##CONTENT##%^%".$content;
    }
    
    
	$html = replacemailvariable($msg,$array);

	$pdf->WriteHTML($html,true,0,true);

		

	$localtime = date('His');

	$filename = str_replace('/','-',$fetchresult['invoiceno']);

	$filebasename = $filename.".pdf";

	$addstring ="/imax/api/productupdatepurchase";

	if($_SERVER['HTTP_HOST'] == "localhost")

	{

		$addstring = "/updationpay";

	}

	$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/upload/'.$filebasename;
	
   
	if($type == 'view')
	{
        ob_end_clean();
		$pdf->Output('example.pdf' ,'I');	
	}

	else

	{

		$pdf->Output($filepath ,'F');

		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$fetchresult['customerid'].'^'.$dealeremailid.'^'.$invoicestatus.'^'.$fetchresult['status'].'^'.$fetchresult['contactperson'].'^'.$fetchresult['netamount'];

	}

	$pdf->writeHTML($html, true, false, true, false, '');


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





function sendwelcomeemail($customerslno)

{

$query = "select inv_mas_customer.customerid AS customerid, inv_mas_customer.businessname AS businessname, inv_mas_customer.place AS place, inv_mas_customer.address AS address, inv_mas_customer.pincode AS pincode, inv_mas_customer.stdcode AS stdcode, inv_mas_customer.initialpassword AS password, inv_mas_customertype.customertype AS type, inv_mas_customercategory.businesstype AS category, inv_mas_district.districtname AS districtname, inv_mas_state.statename AS statename from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_customercategory on inv_mas_customer.category = inv_mas_customercategory.slno left join inv_mas_customertype on inv_mas_customer.type = inv_mas_customertype.slno where inv_mas_customer.slno = '".$customerslno."'";

	$result = runmysqlqueryfetch($query);

	

	$querycontactdetails = "select customerid,GROUP_CONCAT(inv_contactdetails.contactperson) as contactperson,GROUP_CONCAT(inv_contactdetails.phone) as phone, GROUP_CONCAT(inv_contactdetails.cell) as cell,GROUP_CONCAT(inv_contactdetails.emailid) as emailid from inv_contactdetails where customerid = '".$customerslno."'  group by customerid ";

	$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);

	

	$contactvalues = trim(removedoublecomma($resultcontactdetails['contactperson']),',');

	$phoneres = trim(removedoublecomma($resultcontactdetails['phone']),',');

	$cellres = trim(removedoublecomma($resultcontactdetails['cell']),',');

	$emailid = trim(removedoublecomma($resultcontactdetails['emailid']),',');

	

	$customerid = $result['customerid'];

	$businessname = $result['businessname'];

	$contactperson = $contactvalues;

	$place = $result['place'];

	$address = $result['address'];

	$pincode = $result['pincode'];

	$stdcode = $result['stdcode'];

	$phone = $phoneres;

	$cell = $cellres;

	$password = $result['password'];

	$type = $result['type'];

	$category = $result['category'];

	$districtname = $result['districtname'];

	$statename = $result['statename'];

	$emailid = $emailid;

	

	//Dummy line to override To email ID

	//$emailid = 'meghana.b@relyonsoft.com';



	//Split multiple email IDs by Comma

	$emailarray = explode(',',$emailid);

	$emailcount = count($emailarray);

	

	//Convert email IDs to an array. First email ID will eb assigned with Contact person name. Others will be assigned with email IDs itself as Name.

	for($i = 0; $i < $emailcount; $i++)

	{

		if(checkemailaddress($emailarray[$i]))

		{

				$emailids[$emailarray[$i]] = $emailarray[$i];

		}

	}

	$fromname = "Relyon";

	$fromemail = "imax@relyon.co.in";

	require_once("../inc/RSLMAIL_MAIL.php");

	$msg = file_get_contents("../mailcontents/newcustomer.htm");

	$textmsg = file_get_contents("../mailcontents/newcustomer.txt");

	$pincode = ($pincode == '')?'Not Available':$pincode;

	$stdcode = ($stdcode == '')?'Not Available':$stdcode;

	$address = ($address == '')?'Not Available':$address;

	$type = ($type == '')?'Not Available':$type;

	$category = ($category == '')?'Not Available':$category;



	//Create an array of replace parameters

	$array = array();

	$date = date('d-m-Y');

	$array[] = "##DATE##%^%".$date;

	$array[] = "##NAME##%^%".$contactperson;

	$array[] = "##COMPANY##%^%".$businessname;

	$array[] = "##PLACE##%^%".$place;

	$array[] = "##ADDRESS##%^%".$address;

	$array[] = "##DISTRICT##%^%".$districtname;

	$array[] = "##STATE##%^%".$statename;

	$array[] = "##CUSID##%^%".cusidcombine($customerid);

	$array[] = "##PINCODE##%^%".$pincode;

	$array[] = "##STDCODE##%^%".$stdcode;

	$array[] = "##TYPE##%^%".$type;

	$array[] = "##PHONE##%^%".$phone;

	$array[] = "##CELL##%^%".$cell;

	$array[] = "##PASSWORD##%^%".$password;

	$array[] = "##EMAIL##%^%".$emailid;

	$array[] = "##CATEGORY##%^%".$category;

	//Relyon Logo for email Content, as Inline [Not attachment]

	$filearray = array(

		array('images/icon_registration_lg.gif','inline','9999999999'),

		array('images/customer_icon.gif','inline','7777777777'),

		array('images/relyon-logo.jpg','inline','8888888888'),

		array('images/re-registration-icon.gif','inline','66666666666'),

		array('images/contact-info.gif','inline','33333333333'),

		array('images/customer-service.gif','inline','22222222222')

	

	);

	$toarray = $emailids;

	$bccemailids['Relyonimax'] ='relyonimax@gmail.com';

	$bccemailids['support'] ='support@relyonsoft.com';

	$bccemailids['bigmail'] ='bigmail@relyonsoft.com';

	$bccemailids['info'] ='info@relyonsoft.com';

	

	$bccarray = $bccemailids;

	$msg = replacemailvariable($msg,$array);

	$textmsg = replacemailvariable($textmsg,$array);

	$subject = "New customer details registered with Customer ID '".cusidcombine($customerid)."'";

	$html = $msg;

	$text = $textmsg;

	//rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);

	

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



function addlinebreak($linecount)

{

	switch($linecount)

	{

		case '1':

		{

			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';

		}

		break;

		case '2':

		{

			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';

		}

		break;

		case '3':

		{

			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';

		}

		break;

		case '4':

		{

			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';

		}

		break;

		case '5':

		{

			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';

		}

		break;

		case '6':

		{

			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';

		}

		break;

		case '7':

		{

			$linebreak = '<tr><td width="10%"><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';

		}

		break;

	}

	return $linebreak;

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



function sendreceipt($receiptno,$type)

{

	//$type = 'resend';

	$receiptdetails = viewreceipt($receiptno,'resend');

	$receiptdetailssplit = explode('^',$receiptdetails);

	$filebasename = $receiptdetailssplit[0];

	$businessname = $receiptdetailssplit[1];

	$invoiceno = $receiptdetailssplit[2];

	$emailid =  $receiptdetailssplit[3];

	$receiptamount =  $receiptdetailssplit[4];

	$dealeremailid =  $receiptdetailssplit[5];

	

	$contactperson = $receiptdetailssplit[6];

	$place = $receiptdetailssplit[7];

	$slno = substr($receiptdetailssplit[8],15,20);	

	if($type == 'cancelled')

	{

		$status = $receiptdetailssplit[9];

		$cancelledreason = $receiptdetailssplit[10];

	}

	

	if($filebasename <> '')

	{

		//Dummy line to override To email ID

		

		if(($_SERVER['HTTP_HOST'] == "bhumika"))

			$emailid = 'bhumika.p@relyonsoft.com';

		else

			$emailid = $emailid;



		$fromname = "Relyon";

		$fromemail = "imax@relyon.co.in";

		require_once("../inc/RSLMAIL_MAIL.php");

		if($type == 'resend')

		{

			$msg = file_get_contents("../mailcontents/receipt.htm");

			$textmsg = file_get_contents("../mailcontents/receipt.txt");

		}

		elseif($type == 'cancelled')

		{

			$msg = file_get_contents("../mailcontents/cancelledreceipt.htm");

			$textmsg = file_get_contents("../mailcontents/cancelledreceipt.txt");

		}

	

		//Create an array of replace parameters

		$array = array();

		$date = date('d-m-Y');

		$array[] = "##DATE##%^%".$date;

		$array[] = "##COMPANYNAME##%^%".$businessname;

		$array[] = "##INVOICENO##%^%".$invoiceno;

		$array[] = "##PLACE##%^%".$place;

		$array[] = "##AMOUNT##%^%".$receiptamount;

		$array[] = "##CONTACTPERSON##%^%".$contactperson;

		$array[] = "##EMAILID##%^%".$emailid;

		if($type == 'cancelled')

		{

			$array[] = "##REASON##%^%".$cancelledreason;

			$array[] = "##RECEIPTNO##%^%".$receiptno;

		}

		#########  Mailing Starts -----------------------------------

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

		

		//CC to Sales person

		if($_SERVER['HTTP_HOST'] == "bhumika")

			$dealeremailid = 'bhumika.p@relyonsoft.com';

		else

			$dealeremailid = $dealeremailid;

		$ccemailarray = explode(',',$dealeremailid);

		$ccemailcount = count($ccemailarray);

		for($i = 0; $i < $ccemailcount; $i++)

		{

			if(checkemailaddress($ccemailarray[$i]))

			{

				if($i == 0)

					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];

				else

					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];

			}

		}

		

		//Relyon Logo for email Content, as Inline [Not attachment]

		if($type == 'resend')

		{

			$filearray = array(

				array('../images/relyon-logo.jpg','inline','1234567890'),

				array('../upload/'.$filebasename,'attachment','1234567891'),array('../images/relyon-rupee-small.jpg','inline','1234567892')

			);

		}

		elseif($type == 'cancelled')

		{

			$filearray = array(

				array('../images/relyon-logo.jpg','inline','1234567890'),

				array('../upload/'.$filebasename,'attachment','1234567891'));

							

		}

		$toarray = $emailids;



		//CC to sales person

		$ccarray = $ccemailids;

		

		if(($_SERVER['HTTP_HOST'] == "bhumika"))

		{

			$bccemailids['bhumika'] ='bhumika.p@relyonsoft.com';

			//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';

		}

		else

		{

			$bccemailids = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts'=> 'accounts@relyonsoft.com', 'Webmaster' => 'webmaster@relyonsoft.com', 'Relyonimax'=> 'relyonimax@gmail.com');

		}

		$bccarray = $bccemailids;

		$msg = replacemailvariable($msg,$array);

		$textmsg = replacemailvariable($textmsg,$array);



		$subject = "Payment made by ".$businessname. " for invoice ".$invoiceno ;

		$html = $msg;

		$text = $textmsg;

		$replyto = $ccemailids[$ccemailarray[0]];

		//rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);

			//Insert the mail forwarded details to the logs table

		$bccmailid = 'bills@relyonsoft.com,bigmail@relyonsoft.com,webmaster@relyonsoft.com'; 

	}

}



function viewreceipt($receiptno,$type)

{

	ini_set('memory_limit', '2048M');

	require_once('../pdfbillgeneration/tcpdf.php');

	$query1 = "select * from inv_mas_receipt where slno = '".$receiptno."';";

	$resultfetch1 = runmysqlqueryfetch($query1);

	$receiptstatus = $resultfetch1['status'];

	if($receiptstatus == 'CANCELLED')

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

			$img_file = K_PATH_IMAGES.'receipt-cancelled-background.gif';

			$this->Image($img_file, 0, 70, 820, 419, '', '', '', false, 75, '', false, false, 0);

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

	

	$query1 = "select * from inv_mas_receipt where slno = '".$receiptno."'";

	$result1 = runmysqlqueryfetch($query1);

	



	$query = "select * from inv_invoicenumbers where inv_invoicenumbers.slno = '".$result1['invoiceno']."';";
	$fetchresult = runmysqlqueryfetch($query);

	

	$description = $fetch['description'];

	$descriptionsplit = explode('*',$description);

	$product = $descriptionsplit[1];

	

	if($result1['paymentmode'] == 'chequeordd' )

		$remarks = '<span> Cheque/DD No: '.$result1['chequeno'].' dated '.changedateformat($result1['chequedate']).', drawn on '.$result1['drawnon'].', for amount <img src="../images/relyon-rupee-small.jpg" alt="" width="8" height="8" border="0" align="absmiddle" /> '.$result1['receiptamount'].'. Cheques received are subject to realization.</span>';

	else if($result1['receiptremarks'] <> '')

	{

		$remarks = $result1['receiptremarks'];

	}

	else if($result1['receiptremarks'] == '')

	{

		$remarks = 'NONE';

	}

	

	//status of receipt

	 if($result1['status'] == 'CANCELLED')

	{

		$query011 = "select * from inv_mas_users where slno = '".$result1['cancelledby']."';";

		$resultfetch011 = runmysqlqueryfetch($query011);

		$changedby = $resultfetch011['fullname'];

		$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($result1['cancelleddate']).' <br/>'.$result1['cancelledremarks'];

	}

	elseif($result1['status'] == 'ACTIVE')

		$statusremarks = 'Not Avaliable.';

	// Fetch Dealer emailid 

	

	$query0 = "select inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";

	$fetch0 = runmysqlqueryfetch($query0);

	$dealeremailid = $fetch0['dealeremailid'];

		

	$msg = file_get_contents("../pdfbillgeneration/receipt-format.php");

	$array = array();

	$stdcode = $fetchresult['stdcode'];

	$array[] = "##RECEIPTDATE##%^%".changedateformatwithtime($result1['createddate']);

	$array[] = "##SLNO##%^%".$result1['slno'];

	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];

	$array[] = "##ADDRESS##%^%".$fetchresult['address'];

	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];

	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];

	$array[] = "##RECEIPTREMARKS##%^%".$remarks;

	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];

	$array[] = "##AMOUNT##%^%".formatnumber($result1['receiptamount']);

	$array[] = "##AMOUNTINWORDS##%^%".convert_number($result1['receiptamount']);

	$array[] = "##INVOICENO##%^%".$fetchresult['invoiceno'];

	$array[] = "##MODE##%^%".getpaymentmode($result1['paymentmode']);

	$array[] = "##REMARKS##%^%".$remarks;

	$html = replacemailvariable($msg,$array);

	

	$pdf->WriteHTML($html,true,0,true);

		

	$localtime = date('His');

	$filename = 'Receipt-'.$result1['slno'];

	$filebasename = $filename.".pdf";

	$addstring ="/imax/api/productupdatepurchase";

	if($_SERVER['HTTP_HOST'] == "localhost")

		$addstring = "/updationpay";

		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/upload/'.$filebasename;

	

	if($type == 'view')

		$pdf->Output('example.pdf' ,'I');	

	else

	{

		$pdf->Output($filepath ,'F');

		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$result1['receiptamount'].'^'.$dealeremailid.'^'.$fetchresult['contactperson'].'^'.$fetchresult['place'].'^'.$fetchresult['customerid'].'^'.$result1['status'].'^'.$statusremarks;

	}

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

function getpaymentmode($mode)

{

	switch($mode)

	{

		case 'cash': $modereturned = 'Cash'; break;

		case 'onlinetransfer': $modereturned = 'Online Transfer'; break;

		case 'chequeordd': $modereturned = 'Cheque / DD'; break;

		case 'creditordebit': $modereturned = 'Credit / Debit Card'; break;

		default: $modereturned = 'Cheque / DD';

	}

	return $modereturned;

}

function roundnearestvalue($value)

{

	return round($value);

	}
?>