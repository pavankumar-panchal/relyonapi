<?php 
require('config.php');
require('../razorpay-php/Razorpay.php');
session_start(); 
include("../functions/phpfunctions.php");

//Receive the serial numer of record
$lastslno = $_POST['new_lslnop'];
$lslnop = $_POST['lslnop'];

//var_dump($_POST);
//exit;*/
//Ensure record numbers are right and recalculate the total of selected records.

$query = "select * from pre_online_purchase where pre_online_purchase.slno = '".$lastslno."' ";
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
$invoicenumber = ""; //Optional

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
$customerid = $userdetails['custreference'];
$amount = $userdetails['amount'];   //Optional
$productname = "Relyon Online Purchase";

$emailids = explode(',',$emailid);

$emailids = $emailids[0];

/*--------------------Query Updation --------------------*/

$tan_chk = "select deduction, tanno, amount from inv_spp_amc_pinv where slno = '".$lslnop."'";
$result_tan_chk = runmysqlqueryfetch($tan_chk);

$deduction_tan_chk  = $result_tan_chk['deduction'];
$tanno_tan_chk  = $result_tan_chk['tanno'];
$amount_tan_chk  = $result_tan_chk['amount'];

if(($deduction_tan_chk == '1') && ($tanno_tan_chk != ''))
{
  $amount = round($amount - ($amount_tan_chk*(.10)));
}


/*----------------------Query Updation ---------------------*/

// Create the Razorpay Order

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//
$manurcpt=$customerid."#".$lastslno;

$orderData = [
    'receipt'         => $manurcpt,
    'amount'          => $amount * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);
//var_dump($razorpayOrder); exit;
$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;
$mainamount=$amount;
$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}



$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => $contactperson,
    "description"       => "Relyon User Purchase",
    "image"             => "http://relyonsoft.com/wp-content/uploads/2015/01/Relyon-Logo-142x50.png",
    "prefill"           => [
    "name"              => $contactperson,
    "email"             => $emailids,
    "contact"           => $phone,
    ],
    "notes"             => [
    "address"           => $address1,
    "merchant_order_id" => $manurcpt, //cusid and onlineinvoiceno
    ],
    
    "theme"             => [
    "color"             => "#E68C22"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);
$responseSuccessURL='http://relyonsoft.com/imax/api/sppnewupdatepurchase/makepayment/completerazor.php';

?>

<html>
	<head>
		<title>Relyonsoftech Payment gateway</title>
	</head>
	<body onLoad="document.frm1.submit()">
	    
	  <?php 
	  
	  
	  $responseSuccessURL='http://relyonsoft.com/imax/api/sppnewupdatepurchase/makepayment/completerazor.php';
	  
	  $query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1,
	  address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference,razorpay) 
	  values('".$date."', '".$time."', '".$userip."', '".$razorpayOrderId."', '".$responseSuccessURL."', '".$invoicenumber."', '".$mainamount."', '".$company."', 
	  '".$contactperson."', '".addslashes($address1)."', '".addslashes($address2)."', '".addslashes($address3)."', '".$city."', '".$state."', '".$pincode."',
	  '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', '".$lastslno."','Y')";
        $result = runicicidbquery($query);


//writing data to table

/*$querytxnid="select max(id) as txnid from transactions";
$resulttxnid = runicicidbquery($querytxnid);
$fetchtxnid=mysql_fetch_array($resulttxnid);
$txnid_nums=$fetchtxnid['txnid'];*/

$querytxnid="select max(id) as txnid from transactions";
$resulttxnid = runicicidbquery($querytxnid);
$fetchtxnid=mysql_fetch_array($resulttxnid);
$txnid_nums=$fetchtxnid['txnid'];

$query_txnid = "update inv_spp_amc_pinv set txnid = '".$txnid_nums."' where slno = '".$lslnop."' ";
$result_txnid = runmysqlquery($query_txnid);

require("../checkout/manual.php");

		?>

</body>
</html>	    