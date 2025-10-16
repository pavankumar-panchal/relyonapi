<?php
include("../functions/phpfunctions.php");
require('config.php');

session_start();

require('../razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";
$ResponseCode = 0;
$newResponseMessage ='Transaction Successful';

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
             'razorpay_name'     => $_POST['razorpay_name'],
            'razorpay_amount'   => $_POST['razorpay_amount'],
            'razorpay_order_id' => $_POST['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature'],
            'razorpay_company'     => $_POST['razorpay_company'],
            'razorpay_add'     => $_POST['razorpay_add'],
            'razorpay_email'     => $_POST['razorpay_email'],
            'razorpay_txnid'     => $_POST['razorpay_txnid'],
            'razorpay_place'     => $_POST['razorpay_place']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $ResponseCode = 2;
        $newResponseMessage ='Transaction Failure';
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}
if(!$_POST['razorpay_signature'])
{
   echo "Invalid Page";
   exit;
}
$company = $_POST['razorpay_company'];
$contactperson = $_POST['razorpay_name'];
$address = $_POST['razorpay_add'];
$place = $_POST['razorpay_place'];
$amount = $_POST['razorpay_amount'];
$emailid = $_POST['razorpay_email'];
$TxnID = $_POST['razorpay_txnid'];
$ePGTxnID = $_POST['razorpay_payment_id'];
$AuthIdCode = $_POST['razorpay_signature'];
$orderid = $_POST['razorpay_order_id'];
$result = $api->payment->fetch($ePGTxnID);

    if($result['method']=="card")
    {
        $payment_method=$result['method']."#".$result['card_id'];
    }
    elseif($result['method']=="netbanking")
    {
        $payment_method=$result['method']."#".$result['bank'];
    }
    elseif($result['method']=="wallet")
    {
        $payment_method=$result['method']."#".$result['wallet'];
    }
    elseif($result['method']=="upi")
    {
        $payment_method=$result['method']."#".$result['vpa'];
    }
    else
    {
        $payment_method="#";
    }
    
// new code to to restrict multiple entries in db

$test_query = "select responsecode,pgtxnid from transactions where id = '".$TxnID."'";
$result_test_query = runicicidbquery($test_query);

$fetchresult_test_query = mysql_fetch_array($result_test_query);

$test_responsecode = $fetchresult_test_query['responsecode'];
$test_pgtxnid = $fetchresult_test_query['pgtxnid'];

if($test_responsecode != '' && $test_pgtxnid != '')
{
  echo "The Transaction has been completed and page has been expired";
  exit();
}

// new code to to restrict multiple entries in db ends 

$query = "update transactions set responsecode = '".$ResponseCode."', responsemessage = '".$newResponseMessage."', pgtxnid = '".$TxnID."',
payment_method='".$payment_method."', authidcode = '".$TxnID."', rrn = '".$TxnID."', cvrespcode = '".$CVRespCode."', fdmsscore = '".$FDMSScore."', 
fdmsresult = '".$FDMSResult."', cookievalue = '".$Cookie."' where id = '".$TxnID."'";
$result = runicicidbquery($query);


//Select the values from transation table
$query = "select * from transactions where id = '".$TxnID."'";
$result = runicicidbquery($query);
$fetchresult5 = mysql_fetch_array($result);
$recordreferencestring = $fetchresult5['recordreference'];
$amount = $fetchresult5['amount'];

$txnid_nums = $fetchresult5['id'];

$updateInvoicenum = '';

if($ResponseCode == 0) //Success
{
	$paymenttype = "Netbanking";
	$paymentmode = "Net Banking";
	$invoicepayremarks = "Payment received through Net Banking.";
	include('generateinvoice.php');
	rslcookiedelete();
}


	//update preonline purchase with invoice no
	$query355565 = "update transactions set recordreference = '".$updateInvoicenum."' where id = '".$TxnID."'";
	$result35565 = runmysqlquery($query3555);
	
?>

<html>
    <head>
       <meta charset="utf-8">
        <meta name="description" content="Products page of Relyon - House of eTDS, Payroll, salary and attendance management software">
        <meta content="Form100, VAT Offices,Service Tax Returns,Online Filing,Indian Taxation,Inventory,PF software,Computerized Accounting" name="keywords">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="-1">
        <title>Relyon: Buy Online</title>
        <link rel="stylesheet" type="text/css" href="css/finalstyle.css?dummy = 1855711782">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
       <script language="javascript">
           $(document).ready(function(){
                var start = 100;
                var mid = 145;
                var end = 250;
                var width = 22;
                var leftX = start;
                var leftY = start;
                var rightX = mid + 2;
                var rightY = mid - 3;
                var animationSpeed = 20;
                
                var ctx = document.getElementsByTagName('canvas')[0].getContext('2d');
                ctx.lineWidth = width;
                ctx.strokeStyle = '#16a516';
                
                for (i = start; i < mid; i++) {
                    var drawLeft = window.setTimeout(function () {
                        ctx.beginPath();
                        ctx.moveTo(start, start);
                        ctx.lineTo(leftX, leftY);
                        ctx.lineCap = 'round';
                        ctx.stroke();
                        leftX++;
                        leftY++;
                    }, 1 + (i * animationSpeed) / 3);
                }
                
                for (i = mid; i < end; i++) {
                    var drawRight = window.setTimeout(function () {
                        ctx.beginPath();
                        ctx.moveTo(leftX + 2, leftY - 3);
                        ctx.lineTo(rightX, rightY);
                        ctx.stroke();
                        rightX++;
                        rightY--;
                    }, 1 + (i * animationSpeed) / 3);
                }
            });
                           
            function viewonlineinvoice(slno)
            {
            	$('#onlineslno').val(slno);
            	var form = $('#submitform');	
            	//alert($('#onlineslno').val(slno));
            	$('#submitform').attr("action", "http://imax.relyonsoft.net/user/ajax/viewinvoicepdf.php") ;
            	$('#submitform').attr( 'target', '_blank' );
            	$('#submitform').submit();
            }
            
            window.onload = function () 
            {
               document.onkeydown = function (e) {
               return (e.which || e.keyCode) != 116;
               };
            }

</script>
</head>

<body onkeydown="return (event.keyCode != 116)">
    <form method="post" name="submitform" id="submitform">
    <?php if($ResponseCode == 0) { ?>
        <div class="container">
            
                  <div class="row marketing">
                  
                    <div class="col-lg-12 mainbox">
                        <div id="logo" style="float:right;"><img src="../images/relyon-logo.jpg" style="width: 135px;height: 54px;"></div>
                      <div id="scircle"><canvas height="400"></canvas></div>
                      
                        
                        <div>
                        <center>  
                        <h4>Success - your Transaction is Successful!</h4>
                        <h5>Transaction Id: <?php echo($TxnID); ?></h5>
                        <hr />  
                        </div>
                        </center>
                        <div>
                            <p>
                            You have successfully paid <i class="fa fa-inr" aria-hidden="true"></i><b><?php echo($amount.'.00')?></b> . An email also have been sent to <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<b><?php echo wordwrap(($emailid),35,"<br>\n",TRUE)?></b> with the confirmation.
                            </p>
                            <p>The details of the software Purchased Transaction is as below:</p>
                        </div>
                        <table class="table" width="100%" cellspacing="0" cellpadding="3" border="0" style="word-break: break-all;">
                                <tbody>
                                    <tr class="success">
                                        <td><strong>Transaction Status:</strong></td>
                                        <td><?php echo $Message; ?></td>
                                    </tr>  
                                     <tr class="success">
                                        <td><strong>Razorpay Transaction reference Number:</strong></td>
                                        <td><?php echo($ePGTxnID); ?></td>
                                    </tr>  
                                     <tr class="success">
                                        <td><strong>Authorization ID</strong></td>
                                        <td><?php echo($AuthIdCode) ?></td>
                                    </tr>  
                                </tbody>
                        </table>
                        <input type="hidden" name="onlineslno" id="onlineslno" />
                        <button class="btn btn-success printbtn" style="margin-left: -20px;"  onclick="viewonlineinvoice('<?php echo $updateInvoicenum; ?>')"><i class="fa fa-eye"></i> View Invoice</button>
                        <button class="btn btn-success printbtn" id="print" name="print" value="Print" onclick="window.print()" ><i class="fa fa-print"></i> Print</button>
                    </div>
                        
                  </div>
                      <div class="well inf">
                          <ol>
                              <li>
                                  Click on "Print" to save the PIN details for product registration
                              </li>
                              <li>
                                  Click on "View Invoice to see the invoice copy.
                              </li>
                              <li>
                                  Mail with invoice will be sent to the mail id given during purchase.
                              </li>
                              <li>
                                  To register the software,goto "Registration" menu in software, select the usage type (Single User or Multi User), enter the PIN details &amp; click on "Register".
                              </li>
                          </ol>
                         
                     </div>
                 </div> 
    <?php }else{?>
         <div class="container">
            
                  <div class="row marketing">
                  
                    <div class="col-lg-12 mainbox">
                        <div id="logo" style="float:right;"><img src="../images/relyon-logo.jpg" style="width: 135px;height: 54px;"></div>
                      <div id="fcircle"></div>
                      
                        
                        <div>
                        <center>  
                        <h4>Failure - Your Transaction has Failed!</h4>
                        <h5>Transaction Id: <?php echo($TxnID); ?></h5>
                        <hr />  
                        </div>
                        </center>
                        <div>
                            <p>
                            The transaction was NOT successful due to rejection by Gateway / Card issuing Authority. Please try again
                            </p>
                            <!--<p>The details of the software Purchased Transaction is as below:</p>-->
                        </div>
                        <a href="http://www.relyonsoft.com" class="btn btn-danger printbtn"><i class="fa fa-undo"></i> Home</a>
                     
                    </div>
            
                  </div>
            
                </div> 
 
    <?php }?>
</form>
</body>
</html>