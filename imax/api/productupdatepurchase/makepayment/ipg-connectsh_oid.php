<?php 
include("ipg-util.php"); 
include("../functions/phpfunctions.php"); 


//Receive the serial numer of record
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


?>
<html>
	<head>
		<title>Relyonsoftech Payment gateway</title>
	</head>
	<body onload="document.frm1.submit()">

		<?php

        $responseSuccessURL = "http://relyonsoft.com/imax/api/productupdatepurchase/makepayment/response.php"; //Need to change as per location of response page
        $responseFailURL = "http://relyonsoft.com/imax/api/productupdatepurchase/makepayment/response.php";       //Need to change as per location of response page

		$CT = $balanceamt;
		$txntype = 'sale';
		$currency = '356';
		$mode = 'payonly';
		$storename = '3300004074';
		$sharedsecret = 'fjC6?e\rb5S^`';
		$oid = "pg".time();
		
		//Do not touch this. Inserting the record to Relyon main Credit Card transaction table.

$query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1, address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference) values('".$date."', '".$time."', '".$userip."', '".$oid."', '".$responseSuccessURL."', '".$invoicenumber."', '".$balanceamt."', '".$company."', '".$contactperson."', '".addslashes($address1)."', '".addslashes($address2)."', '".addslashes($address3)."', '".$city."', '".$state."', '".$pincode."', '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', '".$lastslno."')";
$result = runicicidbquery($query);

		?>

			<form method="post" name="frm1" action="https://www4.ipg-online.com/connect/gateway/processing">
			<input type="hidden" name="timezone" value="IST" />
			<input type="hidden" name="authenticateTransaction" value="true" />
			<input size="50" type="hidden" name="txntype" value="<?php echo $txntype ?>"  />
			<input size="50" type="hidden" name="txndatetime" value="<?php echo getDateTime(); ?>"  />
			<input size="50" type="hidden" name="hash" value="<?php echo createHash($CT,"356",$storename,$sharedsecret); ?>"  />
			<input size="50" type="hidden" name="currency" value="<?php echo $currency ?>"  />
			<input size="50" type="hidden" name="mode" value="<?php echo $mode ?>"  />
			<input size="50" type="hidden" name="storename" value="<?php echo $storename ?>"  />
			<input size="50" type="hidden" name="chargetotal" value="<?php echo $CT ?>"  />
			<input size="50" type="hidden" name="sharedsecret" value="<?php echo $sharedsecret ?>"  />
			<input size="50" type="hidden" name="oid" value="<?php echo $oid; ?>"  />
			<input type="hidden" name="responseSuccessURL" value="<?php echo $responseSuccessURL ?>"  />
			<input type="hidden" name="responseFailURL" value="<?php echo $responseFailURL ?>"  />
			<input type="hidden" name="hash_algorithm" value="SHA1"/>
			<input type="hidden" name="SubmitButton" value="Submit"/>

</form>
</body>
</html>