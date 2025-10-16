<?php
ob_start("ob_gzhandler");
include("../functions/phpfunctions.php"); 
//Configuration
if(!empty($_POST))
{
 $TxnID = $_POST['ipgTransactionId'];
 $currency = $_POST['currency'];
 $chargetotal = $_POST['chargetotal'];
 $oid = $_POST['oid'];
 $status = $_POST['status'];
 $fail_rc = $_POST['fail_rc'];
 $approval_code = $_POST['approval_code'];
 $responseMessage = explode(":",$approval_code);
 
 if($status == 'FAILED')
 {
     $ResponseCode = '2';
 }
 elseif($status == 'APPROVED')
 {
      $ResponseCode = '0';
 }
 else
 {
    echo("Error Occurred in transaction");
	exit; 
 }
   $newResponseMessage = $responseMessage[2];
  
  if (is_numeric($newResponseMessage)) {
    $newResponseMessage = 'Transaction Successful';
  }
}
else
{
    echo("Invalid Entry");
	exit;
}


// new code to to restrict multiple entries in db

$test_query = "select responsecode,pgtxnid from transactions where orderid = '".$oid."'";
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

$query = "update transactions set responsecode = '".$ResponseCode."', responsemessage = '".$newResponseMessage."', pgtxnid = '".$TxnID."', authidcode = '".$TxnID."', rrn = '".$TxnID."', cvrespcode = '".$CVRespCode."', fdmsscore = '".$FDMSScore."', fdmsresult = '".$FDMSResult."', cookievalue = '".$Cookie."' where orderid = '".$oid."'";
$result = runicicidbquery($query);

//Select the values from transation table
$query = "select * from transactions where orderid = '".$oid."'";
$result = runicicidbquery($query);
$fetchresult5 = mysql_fetch_array($result);
$recordreferencestring = $fetchresult5['recordreference'];
$amount = $fetchresult5['amount'];

$txnid_nums = $fetchresult5['id'];

if($ResponseCode == 0) //Success
{
	$paymenttype = "creditordebit";
	$paymentmode = "credit/debit";
	$invoicepayremarks = "Payment received through Credit/Debit card.";
	include('generateinvoice.php');
	rslcookiedelete();
}
?>
<!doctype html>

<html>
<head>
<meta charset="utf-8">
<meta  name="description" content="Products page of Relyon - House of eTDS, Payroll, salary and attendance management software" />
<meta content="Form100, VAT Offices,Service Tax Returns,Online Filing,Indian Taxation,Inventory,PF software,Computerized Accounting" name="keywords" />
<meta HTTP-EQUIV="Pragma" content="no-cache">
<meta HTTP-EQUIV="Expires" content="-1" >
<title>Relyon: Buy Online</title>
<style type="text/css">
.document
{
    border: 2px solid #a1a1a1;
    padding: 8px 23px; 
    background: #dddddd;
    border-radius: 13px;
    margin-left:116px;
}
</style>
<link rel="stylesheet" type="text/css" href="../styles/style.css?dummy = <? echo (rand());?>">
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script language="javascript">
function viewonlineinvoice(slno)
{
	$('#onlineslno').val(slno);
		
	var form = $('#submitform');	
	
	//alert($('#onlineslno').val(slno));
	$('#submitform').attr("action", "../ajax/viewinvoicepdf.php") ;
	$('#submitform').attr( 'target', '_blank' );
	$('#submitform').submit();
}

</script>
</head>

<body>
<form method="post" name="submitform" id="submitform">
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><table width="683px" border="0" align="center" cellpadding="0" cellspacing="0" >
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <? 

if($ResponseCode == 0) //Success
{ 
        ?>
        <tr>
          <td colspan="2"><table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" style="border:solid 2px #272727">
                    <tr>
                      <td >&nbsp;</td>
                    </tr>
                    <tr>
                      <td ><table width="80%" border="0" cellspacing="0" cellpadding="1"  >
                          <tr>
                            <td colspan="2" class="subheading-font">Payment Status</td>
                          </tr>
                          <tr>
                            <td height="1px" colspan="2"></td>
                          </tr>
                          <tr>
                            <td height="3px" colspan="2" class="blueline" ></td>
                          </tr>
                          <tr>
                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="paymentbg">
                                <tr>
                                  <td width="61%" class="subfonts" style="padding-left:5px" >Transaction Successful</td>
                                  <td width="39%" class="subfonts"><div align="right"><img src="../images/relyon-logo.jpg" width="106" height="37" border="0" style="border:solid 2px #9aaed2"/></div></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="2"><table width="90%" border="0" cellspacing="0" cellpadding="5" align="center">
                                <tr>
                                  <td width="45%" valign="top" class="displayfont"><strong>Payment from :</strong><br />
                                    <? echo($company)?><br />
                                    <? echo('('.$contactperson.')')?><br />
                                    <? echo($address)?><br />
                                    <? echo($place)?> : <? echo($pincode)?></td>
                                  <td width="45%"  valign="top" class="displayfont"><strong>Payment To :</strong><br />
                                    Relyon Softech Ltd<br />
                                    No. 73, Shreelekha Complex, <br />
                                    WOC Road,Bangalore :560 086<br />
                                    Phone: 1860-425-5570 <br />
                                    Email: support@relyonsoft.com</td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="fontstyle" ><p align="left">You have successfully paid <img src="../images/relyon-rupee-small.jpg" width="8" height="10"  />&nbsp;<font color="#000000"><? 

	$sel_receipt_customer_pay = "select * from inv_invoicenumbers where invoiceno = '".$invoicenoformat."';";
	$resultfetch_customer_pay = runmysqlqueryfetch($sel_receipt_customer_pay);

	$deduction_customer_pay = $resultfetch_customer_pay['deduction'];
	$tanno_customer_pay = $resultfetch_customer_pay['tanno'];
	$amount_customer_pay = $resultfetch_customer_pay['amount'];
	$netamount_customer_pay = $resultfetch_customer_pay['netamount'];



if(($deduction_customer_pay == '1') && ($tanno_customer_pay != ''))
{
  $amount = round($netamount_customer_pay - ($amount_customer_pay*(.10)));
}

echo($amount.'.00')

?></font>. An email also have been sent to <font color="#FF0000"><? echo wordwrap(($emailid),35,"<br>\n",TRUE)?></font> with the confirmation.<br /></p>
                              
                              <p align="left">The details of the software Purchased Transaction is as below:</p></td>
                          </tr>
                          <tr>
                            <td colspan="2" height="1px"></td>
                          </tr>
                          <tr>
                            <td colspan="2"><table width="600px" border="0" cellspacing="0" cellpadding="5" bgcolor="#eeeeee" align="center">
                                <tr>
                                  <td><table width="400px" border="0" cellspacing="0" cellpadding="3" align="center" style="border:solid 1px #D4D4D4">
                                      <tr>
                                        <td class="displayfont"><p align="center"><strong>Transaction Status:</strong> <? echo($Message); ?><br />
                                            <strong>Relyon Transaction ID:</strong> <? echo($TxnID); ?><br />
                                            <strong>ICICI Transaction reference Number:</strong> <? echo($TxnID); ?><br />
                                            <strong>Authorization ID: </strong> <? echo($TxnID) ?> <br />
                                          </p></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td><? echo($grid)?></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" style="border-top:solid 2px #8e8e8e" height="10px"></td>
                          </tr>
                          <tr>
                            <td width="65%">&nbsp;</td>
                            <td width="35%"><div align="center">
                            <input type="hidden" name="onlineslno" id="onlineslno" />
                                <input type="button" id="print" name="print" value="Print" onclick="window.print()"/>
                                <?php 
								echo '<input type="button" id="print" name="print" value="View Invoice"  
								onclick="viewonlineinvoice(\''.$onlineinvoiceno.'\')" />';
								/*echo '<a onclick="viewonlineinvoice(\''.$onlineinvoiceno.'\')" class="resendtext" 
                                style = "cursor:pointer"> View Invoice >></a>';*/ ?>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <? }else{?>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 2px #272727">
              <tr>
                <td >&nbsp;</td>
              </tr>
              <tr>
                <td ><table width="100%" border="0" cellspacing="0" cellpadding="3"  >
                    <tr>
                      <td colspan="2" class="subheading-font">Payment Status</td>
                    </tr>
                    <tr>
                      <td height="0px" colspan="2"></td>
                    </tr>
                    <tr>
                      <td height="3px" colspan="2" class="blueline" ></td>
                    </tr>
                    <tr>
                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="paymentbg">
                          <tr>
                            <td width="61%" class="subfonts" style="padding-left:1px" >Transaction Failure</td>
                            <td width="39%" class="subfonts"><div align="right"><img src="../images/relyon-logo.jpg" width="106" height="37" style="border:solid 2px #9aaed2"/></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="fontstyle" ><p align="left">The transaction was NOT successful due to rejection by Gateway / Card issuing Authority. Please try again.</p> </td>
                    </tr>
                    <tr>
                      <td colspan="2"><table width="600px" border="0" cellspacing="0" cellpadding="5" bgcolor="#eeeeee" align="center">
                          <tr>
                            <td height="10px"></td>
                          </tr>
                          <tr>
                            <td><table width="400px" border="0" cellspacing="0" cellpadding="3" align="center" style="border:solid 1px #D4D4D4" >
                                <tr>
                                  <td class="displayfont"><p align="center"><strong>Transaction Status:</strong><? echo($Message); ?><br />
                                      <strong>Relyon Transaction ID:</strong> <? echo($TxnID); ?><br />
                                      <strong>ICICI Transaction reference Number:</strong> <? echo($TxnID); ?><br />
                                    </p></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td height="10px"></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" style="border-top:solid 2px #8e8e8e" height="10px"></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <? }?>
        <tr>
          <td></td>
        </tr>
      </table></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>
  <? 
if($ResponseCode == 0) //Success
{ 
  ?>
  <tr>
      <td><table width="618px" border="0"  cellpadding="0" cellspacing="0" class="document">
          
          
            <tr>
                 <td><div align="left"><strong style="font-size:14px">1. Click on "Print" to save the PIN details for product registration.<br />
                  2. Click on "View Invoice to see the invoice copy.<br />
                  3. Mail with invoice will be sent to the mail id given during purchase.<br />
                     </strong></div></td>
                </tr>
              </table></td>
          </tr>
          
    <? }?>
</table>
</form>
</body>
</html>