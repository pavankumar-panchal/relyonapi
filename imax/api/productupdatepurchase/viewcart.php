<?php
include('functions/phpfunctions.php');

if(rslgetcookie('customerid')!= '' && rslgetcookie('dealerid') != '')
{
$grid .= '<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="table-border-grid2" id="adddescriptionrows"><tr bgcolor="#6AB5FF">
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Sl No</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Product Name</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Usage</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Purchase</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Amount</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">GST</font></strong></td>
<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Net Amount</font></strong></td>
</tr>';
		$productrray = $_GET['productdata'];
		$splitproductarray = explode('$$',$productrray);
		$countproductarray = count($splitproductarray);
		
		if($countproductarray > 1)
		{
			for($i=0;$i<$countproductarray;$i++)
			{
				$dataarray[] = explode("$",$splitproductarray[$i]);
			}
		}
		else
		{
			for($i=0;$i<$countproductarray;$i++)
			{
				$dataarray[] = explode("$",$splitproductarray[$i]);
			}
		}
		
		
		for($j=0;$j<count($dataarray);$j++)
		{
			$i_n = 0;
			$i_n++;
			$color;
			if($i_n%2 == 0)
			   $color = "#C7C7C7";
			else
			   $color = "#D3D3D3";
			
			$query = "select inv_relyonsoft_prices.productcode,purchasetype,updationprice,usagetype from inv_relyonsoft_prices 
			left join inv_mas_product on inv_mas_product.productcode = inv_relyonsoft_prices.productcode
			where inv_mas_product.productname = '".$dataarray[$j][1]."' and purchasetype = 'updation' 
			and usagetype ='".$dataarray[$j][0]."'";
			$fetch = runmysqlqueryfetch($query);
			$productcode = $fetch['productcode'];
			$purchasetype = $fetch['purchasetype'];
			$updationprice = $fetch['updationprice'];
			$usagetype = $fetch['usagetype'];
			
			//$servicetax1 = round($updationprice * 0.14);
			//$sbtax = round($updationprice * 0.005);
			//$kktax = round($updationprice * 0.005);
			//$servicetax = $servicetax1 + $sbtax + $kktax;
			$servicetax = round($updationprice * 0.18);
					
			$netamount = $updationprice + $servicetax ;

			$totalamount += $netamount;
			
			$slno++;
			
			if($j==0)
			$productlist = $productcode;
			else
			$productlist = $productlist."#".$productcode;
			
			if($j==0)
			$usagelist = $usagetype;
			else
			$usagelist = $usagelist."#".$usagetype;
			
			if($usagetype == "multiuser")
			{
				$usage = "Multi User";
			}
			else
			{
				$usage = "Single User";
			}
			
			if($purchasetype == "updation")
			{
				$purchase = "Updation";
			}
			
			$grid .= ' <tr bgcolor="'.$color.'">
			<td class="td-border-grid2"  align="center">'.$slno.'</td>
			<td class="td-border-grid2"  align="center">'.$dataarray[$j][1].'</td>
			<td class="td-border-grid2" align="center">'.$usage.'</td>
			<td class="td-border-grid2" align="center">'.$purchase.'</td>
			<td class="td-border-grid2" align="right">'.getprice($updationprice).'</td>
			<td class="td-border-grid2" align="right">'.getprice($servicetax).'</td>
			<td class="td-border-grid2" align="right">'.getprice($netamount).'</td>
			</tr>';
		}
	$grid .= ' </table>';
	$grid .='<table width="600" border="0" cellpadding="4" cellspacing="0">
	<tr><td>&nbsp;</td></tr>
	<tr><td width = "60%" align="left" ></td>
	<td  width = "10%">&nbsp;</td>
	<td  width = "45%" class="paymenttotfont"><div align="right">Total: <span  id="totalresult" >
	</span>'.$totalamount.'.00(INR)</div></td></tr>
	<tr><td colspan="3"><input type="hidden" name="lastslno" id="lastslno" />
	<input type="hidden" name="productlist" id="productlist" value="'.$productlist.'"><input type="hidden" name="usagelist" id="usagelist" value="'.$usagelist.'"></td></tr></table>';

	
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
                    <td style="font-size:12px;"><font color="#FF0000">* Additional 18% GST applicable</font></td>
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