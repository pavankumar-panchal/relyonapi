<?php
include('functions/phpfunctions.php');

if(rslgetcookie('customerid') != '' && rslgetcookie('dealerid') != '')
{
$grid .= '<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="table-border-grid2" id="adddescriptionrows"><tr bgcolor="#6AB5FF">
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Sl No</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Product Name</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Usage</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Purchase</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Amount</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Service Tax</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Net Amount</font></strong></td>
</tr>';

  $select_query = "select isac.slno as isacslno, isac.customerid as customerid from inv_spp_amc_customers isac inner join inv_spp_amc_pinv isap on isac.invoiceno = isap.invoiceno  where isap.slno = ".$_GET['productslno'];
		$res_select = runmysqlqueryfetch($select_query);
		$isacslno = $res_select['isacslno'];
		$customerid = $res_select['customerid'];

		$select_purchase = "select * from inv_spp_amc_customers_purchase where ispac_id ='".$isacslno."'";
		$res_purchase = runmysqlquery($select_purchase);
		
		$select_service = "select * from inv_spp_amc_customers_service where ispac_id ='".$isacslno."'";
		$res_service = runmysqlquery($select_service);
		$servtax = 0.15;
                $totalamount = '';

		$counter = 1;
  if(mysql_num_rows($res_purchase) == 1)
		{	
			$color;
			if($counter%2 == 0)
			   $color = "#C7C7C7";
			else
			   $color = "#D3D3D3";
			   
                        $res_purchase_details = mysql_fetch_array($res_purchase);
			$purchasetype = $res_purchase_details['purchasetype'];
			$product_code = $res_purchase_details['product_code'];
			$usage_type = $res_purchase_details['usage_type'];
			$new_amount = $res_purchase_details['new_amount'];

		$select_product = "select productname,year from inv_mas_product where productcode ='".$product_code."'";
		$res_product = runmysqlqueryfetch($select_product);
		$productname = $res_product['productname'];
		$year = $res_product['year'];
		$pname = $productname;

			
			$product_code = $productcode;	
			$usage = $usagelist = $usage_type;	
			
                        $res_service_details = mysql_fetch_array($res_service);
			$new_service_amount = $res_service_details['new_service_amount'];
			$servicetype = $res_service_details['servicetype'];
			
			
$amount = $new_service_amount + $new_amount;
$servicetax = round($amount*$servtax);
$rowtotal = $amount + $servicetax;
$totalamount = $rowtotal;

			$grid .= ' <tr bgcolor="'.$color.'">
			<td class="td-border-grid2"  align="center">'.$counter.'</td>
			<td class="td-border-grid2"  align="center">'.$pname.'</td>
			<td class="td-border-grid2" align="center">'.$usage.'</td>
			<td class="td-border-grid2" align="center">'.$purchasetype.'</td>
			<td class="td-border-grid2" align="right">'.getprice($amount).'</td>
			<td class="td-border-grid2" align="right">'.getprice($servicetax).'</td>
			<td class="td-border-grid2" align="right">'.getprice($rowtotal).'</td>
			</tr>';
			$counter++;
		}
		else
		{


		}

	$grid .= ' </table>';
	$grid .='<table width="600" border="0" cellpadding="4" cellspacing="0">
	<tr><td>&nbsp;</td></tr>
	<tr><td width = "60%" align="left" ></td>
	<td  width = "10%">&nbsp;</td>
	<td  width = "45%" class="paymenttotfont"><div align="right">Total: <span  id="totalresult" >
	</span>'.$totalamount.'.00(INR)</div></td></tr>
  <tr><td colspan="3"><input type="hidden" name="lastslno" id="lastslno" value="'.$_GET['productslno'].'"><input type="hidden" name="customerid" id="customerid" value="'.$customerid.'"><input type="hidden" name="new_lslnop" id="new_lslnop" value="">
	<input type="hidden" name="productlist" id="productlist" value="'.$product_code.'"><input type="hidden" name="usagelist" id="usagelist" value="'.$usagelist.'"></td></tr></table>';

	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relyon Softech Ltd - Buy Online</title>
<script type='text/javascript' src='js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/style.css?dummy=<?php echo (rand());?>">
<script type="text/javascript" src="functions/customerdetails.js?dummy= <?php echo (rand());?>"></script>
<script>
$(document).ready(function() {
$('#totalresult').empty().append('<img src="images/relyon-rupee-small.jpg" height="15" width="16" align="absmiddle">');
});
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
<form action="paymode.php" method="post" name="submitpayform" id="submitpayform">
<table width="900px" border="0" align="center" cellpadding="0" cellspacing="0" >
<tr>
  <td  colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2"><?php include('inc/header.php') ?></td>
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
                    <td colspan="2" class="heading-font">View your Cart</td>
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
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="font-size:12px;"><font color="#FF0000">* Additional 15% Service Tax applicable</font></td>
                  </tr>
                  <tr>
                    <td><?php echo($grid);?></td>
                  </tr>
                  
                   <tr>
                        <td align="center"><input name="proceedforpayment" type="button"  id="proceedforpayment" 
                        value="Proceed for Payment" style="height:30px; width:144px; cursor:pointer" onclick="formsubmit();"></td>
                </tr>
                <tr>
                        <td>&nbsp;</td>
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
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
       <tr>
      <td>&nbsp;</td>
      </tr>
      </tr>
      <tr>
      <td>&nbsp;</td>
      </tr>
      </tr>
      <tr>
      <td>&nbsp;</td>
      </tr>
      <tr>
  <td><?php include('inc/footer.php') ?></td>
      </tr>
    </table></form>
</body>
</html>
<?php }
 else
 {
		rslcookiedelete();
		echo "Session has been expired";
 } 
?>