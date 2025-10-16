<?php
//error_reporting(0);
include("ipg-util.php"); 
include("../functions/phpfunctions.php"); 
//Receive the serial numer of record
//$lastslno = $_POST['lastslno'];
$lastslno = $_POST['lslnop'];

$balanceamt = $_POST['balanceamt'];

//Ensure record numbers are right and recalculate the total of selected records.

$query = "select * from inv_invoicenumbers where inv_invoicenumbers.slno = '".$lastslno."' ";

$result = runmysqlquery($query);

if(mysql_num_rows($result) == 0)
{
	$errormessage = "Invalid Entry.";
	header("Location:../../index.php?cusid=".rslgetcookie('customerid')."&error=".$errormessage);
	exit;
}
else
{

	$userdetails = mysql_fetch_array($result);
	$product = $userdetails['products'];
	$split = explode('*',$product);
	$quantity = count($split);

}



/*-----------------------------Do not edit this piece of code - Begin-----------------------------*/

$query = "SHOW TABLE STATUS like 'transactions'";
$result = runicicidbquery($query);
$row = mysql_fetch_array($result);
$nextautoincrementid = $row['Auto_increment'];


$merchatid = "00004074";
$date = date('Y-m-d');
$time = date('H:i:s');
$userip = $_SERVER["REMOTE_ADDR"];
$userbrowserlanguage = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
$userbrowseragent = $_SERVER["HTTP_USER_AGENT"];
$relyontransactionid = $nextautoincrementid; 

/*-----------------------------Do not edit this piece of code - End-----------------------------*/




//Main Details


$orderid = ""; //Optional
$invoicenumber = $userdetails['invoiceno'];


//User Details

$company = substr($userdetails['businessname'],0,80); //Optional
$contactperson = substr($userdetails['contactperson'],0,50);
$address1 = substr($userdetails['address'],0,50);
$address2 = ""; //Optional
$address3 = ""; //Optional
$city = substr($userdetails['place'],0,30);
$state = "STATE";
$country = "IND"; //No change
$pincode =  $userdetails['pincode'];
$phone = substr($userdetails['phone'],0,15); //Optional
$emailid = substr($userdetails['emailid'],0,80); //Optional
$customerid = substr($userdetails['customerid'],-5);
$amount = $userdetails['netamount'];   //Optional
$productname = "Relyon Online Purchase";


//$responseurl = "http://relyonsoft.com/imax/api/productupdatepurchase/makepayment/invcomplete.php";

        $responseSuccessURL = "http://relyonsoft.com/imax/api/productupdatepurchase/makepayment/invcomplete.php"; //Need to change as per location of response page
        $responseFailURL = "http://relyonsoft.com/imax/api/productupdatepurchase/makepayment/invcomplete.php";       //Need to change as per location of response page

        $CT = $amount;
		$txntype = 'sale';
		$currency = '356';
		$mode = 'payonly';
		$storename = '3300004074';
		$sharedsecret = 'fjC6?e\rb5S^`';
		$oid = "pg".time();

// Do not edit further, till the end.

//Do not touch this. Inserting the record to Relyon main Credit Card transaction table.

$query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1, address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference)	values('".$date."', '".$time."', '".$userip."', '".$oid."', '".$responseSuccessURL."', '".$invoicenumber."', '".$balanceamt."', '".$company."', '".$contactperson."', '".addslashes($address1)."', '".addslashes($address2)."', '".addslashes($address3)."', '".$city."', '".$state."', '".$pincode."', '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', '".$lastslno."')";

$result = runicicidbquery($query);

?>

<?php

// the ICICI pg code has been removed and we will simulate it locally
// write a code below to redirect payicici.php, and send all the value which were supposed to be sent to PG using GET method

/*
if($result)
{
   header('Location: payinvicici.php?amount='.$amount.'&transid='.$relyontransactionid.'&slno='.$lastslno);
}*/


// ICICI code begins. Do not alter anything Further - Vijay .................................................

/*
include("Sfa/BillToAddress.php");
include("Sfa/CardInfo.php");
include("Sfa/Merchant.php");
include("Sfa/MPIData.php");
include("Sfa/ShipToAddress.php");
include("Sfa/PGResponse.php");
include("Sfa/PostLibPHP.php");
include("Sfa/PGReserveData.php");

$oMPI = new MPIData();
$oCI = new CardInfo();
$oPostLibphp = new PostLibPHP();
$oMerchant = new Merchant();
$oBTA = new BillToAddress();
$oSTA = new ShipToAddress();
$oPGResp = new PGResponse();
$oPGReserveData = new PGReserveData();


$oMerchant->setMerchantDetails($merchatid, $merchatid, $merchatid,$userip,$relyontransactionid,$orderid,$responseurl,"POST","INR",$invoicenumber,"req.Sale",$amount,"","Ext1","true","Ext3","Ext4","New PHP");

$oBTA->setAddressDetails ($customerid, $company, $address1, $address2, $address3, $city, $state, $pincode, $country, $emailid);

$oSTA->setAddressDetails ($address1, $address2, $address3, $city, $state, $pincode, $country, $emailid);

#$oMPI->setMPIRequestDetails("1245","12.45","356","2","2 shirts","12","20011212","12","0","","image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/msword, application/x-shockwave-flash, */*","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");

$oPGResp=$oPostLibphp->postSSL($oBTA,$oSTA,$oMerchant,$oMPI,$oPGReserveData);


if($oPGResp->getRespCode() == '000'){
	$url	=$oPGResp->getRedirectionUrl();
	#$url =~ s/http/https/;
	#print "Location: ".$url."\n\n";
	#header("Location: ".$url);
	redirect($url);
}else{
	print "Error Occured.<br>";
	print "Error Code:".$oPGResp->getRespCode()."<br>";
	print "Error Message:".$oPGResp->getRespMessage()."<br>";
}

# This will remove all white space
#$oResp =~ s/\s*//g;

# $oPGResp->getResponse($oResp);

#print $oPGResp->getRespCode()."<br>";

#print $oPGResp->getRespMessage()."<br>";

#print $oPGResp->getTxnId()."<br>";

#print $oPGResp->getEpgTxnId()."<br>";

function redirect($url) {
	if(headers_sent()){
	?>
		<html><head>
			<script language="javascript" type="text/javascript">
				window.self.location='<?php print($url);?>';
			</script>
		</head></html>
	<?php
		exit;
	} else {
		header("Location: ".$url);
		exit;
	}
}
*/

?>