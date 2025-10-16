<?php
include('../functions/phpfunctions.php');


$grid .= '<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="table-border-grid2" id="adddescriptionrows"><tr bgcolor="#6AB5FF">
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Sl No</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Invoice No</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Invoice Date</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Net Amount</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Paid</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Balance</font></strong></td>
</tr>';
		$invoicearray = decodevalue($_GET['inonv']);
                $cuid = decodevalue($_GET['idc']);
		$splitinvoicearray = explode(',',$invoicearray);
		$countinvoicearray = count($splitinvoicearray);
               

		if($countinvoicearray > 0)
		{       
                        $slno = 1;
                        $totalbalance =0;                 
                          
			for($i=0;$i<$countinvoicearray;$i++)
			{
                            $query= "SELECT imr.invoiceamount as invamt, invn.netamount as netamount,DATE_FORMAT(invn.createddate,'%d-%m-%Y') as date, invn.businessname as businessname, invn.slno as slno, imr.receiptamount as recamt from inv_invoicenumbers invn left join inv_mas_receipt imr on invn.slno = imr.invoiceno WHERE invn.invoiceno ='".$splitinvoicearray[$i]."' AND RIGHT(invn.customerid, 5) = '".$cuid."'";

                  

                           $result = runmysqlquery($query);
                          if(mysql_num_rows($result) > 0)
                          {
                           $fetch = mysql_fetch_array($result);
                           $netamount = $fetch['netamount'];
                           $date = $fetch['date'];
                           $invslno = $fetch['slno'];
                           $encodedslno = encodevalue($invslno);
                           //$netamount = $fetch['invamt'];
                           $paidamt  = $fetch['recamt'];
                           if($paidamt == 0 OR $paidamt == ""){ 
                                $paidamt = 0;
                           }
                           $balance =  $netamount - $paidamt;
                           $businessname = $fetch['businessname'];

                           
				$grid .= ' <tr bgcolor="'.$color.'">
			                     <td class="td-border-grid2" >'.$slno.'</td>
			                     <td class="td-border-grid2"  align="center"><span id="showinvoice" style="color:blue;cursor:pointer" onclick="viewonlineinvoice(\''.$encodedslno.'\');">'.$splitinvoicearray[$i].'</span></td>
                                             <td class="td-border-grid2" align="right">'.$date.'</td>
			                     <td class="td-border-grid2" align="right">Rs.'.getprice($netamount).'</td>
                                             <td class="td-border-grid2" align="right">Rs.'.getprice($paidamt).'</td>
                                             <td class="td-border-grid2" align="right">Rs.'.getprice($balance).'</td> 
			                   </tr>';
                                $slno++;
                                $totalbalance = $totalbalance + $balance;
                            //$queryamount .= "'$splitinvoicearray[$i]',"; 
		         }
                         else {
                           $wronginvoice = 1;
                         }
                      }
			
		}
		         

                
               /* for($i=0;$i<$countinvoicearray;$i++)
               {
                   $splitinvoicearray[$i]="'".$splitinvoicearray[$i]."'";
               } 
               $implodeinvoice = implode(",",$splitinvoicearray);
               $queryamount="SELECT SUM(netamount) as total_amount from inv_invoicenumbers where invoiceno in ($implodeinvoice)";

	       $resultamount =  runmysqlquery($queryamount);
               $fetchamount = mysql_fetch_assoc($resultamount); 
	       $totalamount = $fetchamount['total_amount']; */
			
	$grid .= ' </table>';
	$grid .='<table width="650" border="0" cellpadding="4" cellspacing="0">
	<tr><td>&nbsp;</td></tr>
	<tr><td width = "60%" align="left" ></td>
	<td  width = "10%">&nbsp;</td>
	<td  width = "45%" class="paymenttotfont"><div align="right">Total: <span  id="totalresult" >
	</span>'.$totalbalance.'.00</div></td></tr>
	<tr><td colspan="3"><input type="hidden" name="lastslno" id="lastslno" /><input type="hidden" name="invno" id="invno" value="'.$invoicearray.'" /><input type="hidden" name="balanceamt" id="balanceamt" value="'.$totalbalance.'" />
	</td></tr></table>';


	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relyon Softech Ltd - Buy Online</title>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script type="text/javascript" src="../functions/customerinvdetails.js?dummy= <?php echo (rand());?>"></script>
<script>
$(document).ready(function() {
$('#totalresult').empty().append('<img src="../images/relyon-rupee-small.jpg" height="15" width="16" align="absmiddle">');
});
function viewonlineinvoice(slno)
{
	window.open("http://relyonsoft.com/imax/api/productupdatepurchase/ajax/viewinvinvoicepdf.php?sln="+slno, '_blank');
	
}
</script>
<style type="text/css">
#invoicedetailsgrid {
	display: none;
	position: fixed;
	_position: absolute;
	height: 170px;
	width: 300px;
	background: #FFFFFF;
	left: 500px;
	top: 200px;
	z-index: 100;
	margin-left: 15px;
	border: 1px solid #328cb8;
	box-shadow: 0px 0px 30px #666666;
	font-size: 15px;
	-moz-border-radius: 15px;
	border-radius: 15px;
}
a {
	cursor: pointer;
	text-decoration: none;
}

</style>
</head>
<body>
<form action="payinvmode.php" method="post" name="submitpayform" id="submitpayform">
<table width="900px" border="0" align="center" cellpadding="0" cellspacing="0" >
<tr>
  <td  colspan="2">&nbsp;</td>
</tr>
<tr>
  <td  colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td width="700" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="content-top">&nbsp;</td>
            </tr>
            <tr>
              <td class="content-mid">
              <table width="75%" border="0" cellspacing="0" cellpadding="0" align="center">
                  <tr>
                    <td><img src="../images/relyon-logo.jpg" alt="Customer Payment" width="196" height="75" border="0"></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="heading-font"><?php echo ($businessname);?></td>
                  </tr>
                  <tr>
                    <td height="4px" colspan="2" class="blueline"></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  
                  <tr>
                    <td><?php echo ($grid);?></td>
                  </tr>
                  <?php 
if($balance > 0)
{

                  ?>
                   <tr>
                        <td align="center"><input name="proceedforpayment" type="button"  id="proceedforpayment" 
                        value="Proceed for Payment" style="height:30px; width:144px; cursor:pointer" onclick="formsubmit();"></td>
                </tr>
<?php
}
 elseif($wronginvoice == 1)
{
?>
<tr>
                    <td style="font-size:13px;"><font color="#FF2200">* Invalid Customerid or Invoiceno. Come Through Proper Link</font></td>
                  </tr>
<?php
}
elseif($balance <= 0)
{
?>
                    <td style="font-size:13px;"><font color="#FF2200">* Invoice Has been Paid.</font></td>
                  </tr>
<?php
}
?>
              <tr>
                   <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                  <tr>
                    <td style="font-size:12px;"><font color="#FF0000">* Please do not refresh the page or you might loose your data.</font></td>
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
              <td style="text-align:center">© Relyon Softech Limited | <span style="text-decoration:none"><a href="http://www.relyonsoft.com" class="Link" target="_blank"> www.relyonsoft.com</a></span></td>
            </tr>
                </table></td>
            </tr>
            <tr>
              <td></td>
            </tr>
            <tr>
              <td class="content-btm">&nbsp;</td>
            </tr>
            
            </table></td></tr>
          </table></td>
      </tr>
     
      
    </table></form>
</body>
</html>
