<?php

//read and store all value that are sent from pay.php

//show a screen to user with submit button &  2 radio button to choose 1. Payment Successful status 2. payment failure status

/* POST / get below value to complete.php

ResponseCode, 0 for success 1 for failure
TxnID, number which was sent from pay.php
Message, payment status message
ePGTxnID, a random number
AuthIdCode, some number
RRN,some number
CVRespCode, some number


*/

   $amount=$_GET['amount'];
   $transid=$_GET['transid'];
   $lsno=$_GET['slno'];

include('../functions/phpfunctions.php'); 
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relyon Softech Ltd - Payement Status</title>
<script type='text/javascript' src='js/jquery.min.js'></script>

<link rel="stylesheet" type="text/css" href="css/style.css?dummy=<?php echo (rand());?>">
<script>
$(document).ready(function() {
$('#totalresult').empty().append('<img src="images/relyonweb-rupee-symbol.jpg" height="15" width="16" align="absmiddle">');
});
</script>
<style type="text/css">

#invoicedetailsgrid { 
    display:none;
    position:fixed;  
    _position:absolute; 
    height:170px;  
    width:300px;  
    background:#FFFFFF;  
    left: 500px;
    top: 200px;
    z-index:100;
    margin-left: 15px;  
    border:1px solid #328cb8;
	box-shadow: 0px 0px 30px #666666; 
    font-size:15px;   	
	-moz-border-radius: 15px;
	border-radius: 15px; 
}

a{  
cursor: pointer;
text-decoration:none;  
} 

 
</style>
</head>
<body>

<form action="invcomplete.php" method="POST">
  <input type="radio" name="status" value="0" > Payment Successful status<br>
  <input type="radio" name="status" value="1"> payment failure status<br>
  <input type="hidden" name="amount" value="<?php echo $amount;?>">  
  <input type="hidden" name="slno" value="<?php echo $lsno;?>">
  <input type="hidden" name="transid" value="<?php echo $transid;?>">  
  <input type="hidden" name="ePGTxnID" value="<?php echo (rand());?>">  
  <input type="hidden" name="AuthIdCode" value="<?php echo (rand());?>">  
  <input type="hidden" name="RRN" value="<?php echo (rand());?>">  
  <input type="hidden" name="CVRespCode" value="<?php echo (rand());?>">  
  <input type="submit" value="Submit">
</form>
