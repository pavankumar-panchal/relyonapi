<?php
    include('functions/phpfunctions.php');
	$count8 = $count9 = "";
	
	if($_REQUEST['Qa1iio9']==""  || $_REQUEST['AsWrIo'] == "")
	{
	    exit;
	}
	 
	$customerid = decodevalue($_REQUEST['Qa1iio9']);
	$productcode = decodevalue($_REQUEST['AsWrIo']);
	
	$customerid = $_REQUEST['Qa1iio9'];
	$productcode = $_REQUEST['AsWrIo'];
    
		if(strlen($customerid) == 5)
		{
			 $cusid = $customerid;
		}
		elseif(strlen($customerid) > 5)
		{
			 $cusid = substr($customerid,-5);
		}
	//}
	$actualprice ="";
	$updationprice="";
	$checkproductcode="";
	
	$custquery = "select inv_mas_customer.slno ,inv_mas_customer.place,
inv_mas_state.statename,
inv_mas_district.districtname,inv_mas_customer.businessname,inv_mas_customer.customerid,
inv_mas_district.statecode from inv_mas_customer 
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
left join inv_mas_state on inv_mas_state.slno = inv_mas_district.statecode 
WHERE inv_mas_customer.slno = '".$cusid."';";

	$custfetch= runmysqlqueryfetch($custquery);
	
	$custquery2 = "select GROUP_CONCAT(emailid) as emailid,  GROUP_CONCAT(cell) as cell, GROUP_CONCAT(phone) as phone, GROUP_CONCAT(contactperson) as contactperson from inv_contactdetails where customerid = '".$cusid."'";
	$resultfetch2 = runmysqlqueryfetch($custquery2);
	
	$businessname =$custfetch['businessname'];
	$contactperson =$resultfetch2['contactperson'];
	$place = $custfetch['place'];
	$statename = $custfetch['statename'];
	$districtname = $custfetch['districtname'];
	$pincode = $custfetch['pincode'];
	$stdcode = $custfetch['stdcode'];
	$phone = $resultfetch2['phone'];
	$cell = $resultfetch2['cell'];
	$emailid = $resultfetch2['emailid'];
	$customerid = cusidcombine($custfetch['customerid']);
	
	$proquery = "select year from inv_mas_product order by year desc limit 1;";
	$proresult = runmysqlquery($proquery);
	$profetch = mysql_fetch_array($proresult);
	$currentyear = $profetch['year'];
	//$currentyear = "2014-15";
	
	$yearquery = "select distinct(year) from inv_mas_product where year!= '".$currentyear."' 
	order by year desc limit 2;";
	$yearresult = runmysqlquery($yearquery);
	while($yearfetch = mysql_fetch_array($yearresult))
	{
		 $yearcount[] = $yearfetch['year'];
	}
	//$yearcount[0] = "2013-14";
	//$yearcount[1] = "2012-13";
	
	
	$grid .= '<table width="600" border="0" align="center" cellpadding="4" cellspacing="0" class="table-border-grid2" id="adddescriptionrows"><tr bgcolor="#6AB5FF">
	<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Sl No</font></strong></td>
	<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Product Name</font></strong></td>
	<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Price</font></strong></td>

	<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">Status</font></strong></td>
	<td class="td-border-grid2" align ="center"><strong><font color="#FFFFFF">&nbsp;</font></strong></td></tr>';
	
	$query0 = "select distinct subgroup as subgroup from inv_dealercard
    left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
    where inv_dealercard.customerreference = ".$cusid." and 
	inv_mas_product.year in ('".$yearcount[0]."','".$yearcount[1]."') and inv_mas_product.group in ('SPP');";
	$result0 = runmysqlquery($query0);
	$lastcount = mysql_num_rows($result0);
	$i_n = 0;
	while($fetch0=mysql_fetch_array($result0))
	{
			$checksubgroup = $fetch0['subgroup'];
	  
			$query1 = "select productname,productcode,subgroup from inv_mas_product where subgroup = '".$checksubgroup."' 
			and inv_mas_product.year = '".$currentyear."';";
			$fetch1 = runmysqlqueryfetch($query1);
	  
			$query5 = "select productcode,subgroup from inv_mas_product where productcode = '".$productcode."';";
			$fetch5 = runmysqlqueryfetch($query5);
		  
			$query6 = "select dealerid,billingname from inv_dealercard
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode
			left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
			where inv_mas_product.year in ('".$yearcount[0]."','".$yearcount[1]."') 
			and inv_dealercard.customerreference = ".$cusid." and inv_mas_product.subgroup  = '".$fetch5['subgroup']."' 
			order by inv_mas_product.year desc,inv_dealercard.slno desc limit 1";
			$fetch6 = runmysqlqueryfetch($query6);
			$dealerid = $fetch6['dealerid'];
			$currentdealer = $fetch6['billingname'];
				 
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
			   $color = "#D3D3D3";
			else
			   $color = "#C7C7C7";
			   
			$query10 = "select inv_customerproduct.customerreference from inv_customerproduct
			left join inv_dealercard on inv_customerproduct.cardid = inv_dealercard.cardid
			where inv_dealercard.productcode =".$fetch1['productcode']." and inv_customerproduct.customerreference = ".$cusid;
			$result10 = runmysqlquery($query10);
			$count10 = mysql_num_rows($result10);  
			   
			/*query for taking last two year count*/
			$query7 = "select inv_mas_product.year from inv_dealercard
			left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
			where inv_dealercard.customerreference = ".$cusid."
			and inv_mas_product.subgroup = '".$checksubgroup."' and inv_mas_product.year in 
			('".$yearcount[0]."','".$yearcount[1]."') order by inv_mas_product.year desc limit 1";
			$fetch7 = runmysqlqueryfetch($query7);
			$lasttwoyear = $fetch7['year']; 
			
			/*current year updation card count*/
			$query8 = "select count(inv_dealercard.purchasetype)as newpurcount from inv_dealercard
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
			where inv_dealercard.customerreference = ".$cusid."
			and inv_mas_product.subgroup = '".$checksubgroup."' and inv_mas_product.year = '".$currentyear."' 
			and inv_dealercard.purchasetype = 'updation'";
			$fetch8 = runmysqlqueryfetch($query8);
			$count8 = $fetch8['newpurcount'];
			
			/*last year updation card count*/
			$query9 = "select count(inv_dealercard.purchasetype) as oldpurcount from inv_dealercard
			left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
			where inv_dealercard.customerreference = ".$cusid."
			and inv_mas_product.subgroup = '".$checksubgroup."' and inv_mas_product.year = '".$lasttwoyear."'";
			$fetch9 = runmysqlqueryfetch($query9); 
			$count9 = $fetch9['oldpurcount'];
			
			if($count8 == 0)
			{
				$limit = 0;
			}
			else
			{
				$limit = $count8;
			}
			
			if($count8 < $count9)	
			{
				$query2 = "select usagetype from inv_dealercard
				left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
				left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
				where inv_mas_product.year = '".$lasttwoyear."' 
				and inv_dealercard.customerreference = ".$cusid." and inv_mas_product.subgroup  = '".$checksubgroup."' 
				order by inv_dealercard.slno desc limit ".$limit.",1";
				$fetch2 = runmysqlqueryfetch($query2);
				$usagetype = $fetch2['usagetype'];
				
				$query4 = "select inv_relyonsoft_prices.productcode,updationprice,actualprice
				from inv_relyonsoft_prices 
				left join inv_mas_product on inv_mas_product.productcode = inv_relyonsoft_prices.productcode
				where inv_mas_product.year = '".$currentyear."' 
				and subgroup = '".$checksubgroup."' and purchasetype = 'updation' and usagetype ='".$usagetype."'";
				$fetch4 = runmysqlqueryfetch($query4);
				$updationprice = $fetch4['updationprice'];
				$actualprice = $fetch4['actualprice'];
				$checkproductcode = $fetch4['productcode'];
			}
			if($fetch5['subgroup'] == $fetch1['subgroup'])
					$checked = 'checked = checked';
			else
				$checked = "";
			
			if($count8 < $count9)
			{
				if($count10 < $count9)
				{
					$status = 'Available';
					$checkbox = '<input type="checkbox" name="resultcheckbox'.$slno.'" 
					id ="resultcheckbox'.$slno.'" class="resultcheckbox" value="'.$updationprice.'" '.$checked.'/>';
				}
				else
				{
					$status = "Updated";
					$checkbox ="";
					$actualprice = "0";
					$updationprice = "0";
				}
			}
			else
			{
				$status = "Updated";
				$checkbox ="";
				$actualprice = "0";
				$updationprice = "0";
			}
			
			$grid .= ' <tr bgcolor="'.$color.'">
			<td class="td-border-grid2" align="center"><font color="'.$fontcolor.'">'.$slno.'</font></td>
			<td class="td-border-grid2" style="width:210px">'.$fetch1['productname'].'
			<input type="hidden" name="purchasetype'.$slno.'" id="purchasetype'.$slno.'" value="'.$usagetype.'" size="3">
			<input type="hidden" name="productname'.$slno.'" id="productname'.$slno.'" value="'.$fetch1['productname'].'" >
			</td> 
			<td class="td-border-grid2 rupee" align="right">
			<font color="'.$fontcolor.'">'.getprice($actualprice).'</font></td>
			<td class="td-border-grid2 rupee" align="right">
			<font color="'.$fontcolor.'"><strong>'.getprice($updationprice).'</strong></font></td>
			<td class="td-border-grid2"><font color="'.$fontcolor.'"><span>
			<strong>'.$status.'</strong></span></font></td>
			<td class="td-border-grid2" align="center">'.$checkbox.'
			<input type="hidden" name="purchasecheck'.$slno.'" id="purchasecheck'.$slno.'" value="'.$status.'"> </td></tr>';
	  }
	
	//$grid .= ' </table>';
	 $grid .= '<tr bgcolor="#E5E5E5">
	 <td width="79%" class="td-border-grid2" colspan="3"><div align="right">Total Amount :</div>
     </td>
     <td width="23%" class="td-border-grid2" ><strong>
	 <div align="right" id="countvalue"></div></strong></td>
	 <td width="23%" class="td-border-grid2"  colspan="2"><input type="hidden" id="custid" name="custid" value="'.$cusid.'">
	 <input type="hidden" name="dealername" id="dealername" value="'.$dealerid.'">
	 <input type="hidden" name="productcode" id="productcode" value="'.$productcode.'">
	 </td>
	 </tr></table>';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relyon Softech Ltd - Buy Online</title>
<script type='text/javascript' src='js/jquery.min.js'></script>
<script type="text/javascript" src="functions/buyonline.js?dummy= <? echo (rand());?>"></script>
<link rel="stylesheet" type="text/css" href="css/style.css?dummy=<? echo (rand());?>">
<script type="text/javascript">
 $(document).ready(function() {
	 
/*var rowcount0 = $('#adddescriptionrows tr').length
var rowcount = rowcount0-1;
for(i=0,j=1; i<rowcount,j<=(rowcount); i++,j++)
{
	if($('#purchasecheck'+j).val()=="Updated")
	{
		$('#resultcheckbox'+j).attr("disabled", true);
		//alert("Updates are not available.");
	}
}*/
		
$("input[type=checkbox]").change(function(){
  recalculate();
});

function recalculate()
{
    var sum = 0;
    $("input[type=checkbox]:checked").each(function() {
       sum  += parseInt($(this).val());
			
    });
    $('#countvalue').html(sum+'.00')
}

	
var checkvalue = 0;
$("input[type=checkbox]:checked").each(function() {
	
	checkvalue  += parseInt($(this).val());
	//alert(checkvalue);
	});
	$('#countvalue').html(checkvalue+'.00');
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
}
</style>
</head>
<body>
<form method="post">
  <table width="900px" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td  colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><? include('inc/header.php') ?></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td ><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td width="700" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="content-top">&nbsp;</td>
                </tr>
                <tr>
                  <td class="content-mid"><table width="75%" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tr>
                        <td class="heading-font">Welcome <? echo($businessname);?>,</td>
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
  <td  valign="middle">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="60%" valign="top" style="background:url(images/imax-cust-dashboard-profile-stirp.jpg) no-repeat top left; background-size: 330px 132px">
      <table width="300px" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td height="132px"><table width="270" border="0" cellpadding="4" cellspacing="0" class="dashboardprofilebox">
           <tr>
                                <td ><? echo(gridtrim40($businessname));?></td>
                              </tr>
                              <tr>
                                <td><? echo(gridtrim40($contactperson));?></td>
                              </tr>
                              <tr>
                                <td><? echo(gridtrim40($place));?>, <? echo(gridtrim40($districtname));?>, <? echo(gridtrim40($statename));?></td>
                              </tr>
                              
                              <tr>
                                <td height="20px">Email:<? echo(gridtrim40($emailid));?></td>
                              </tr>
                              
          </table></td></tr>
          </table>
          </td>
                          <td width="60%" valign="top" style="background:url(images/imax-cust-dashboard-profile-stirp.jpg) no-repeat top left; background-size: 290px 132px" >
      <table width="300px" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td height="132px" >
          <table width="270" border="0" cellpadding="4" cellspacing="0" class="dashboardprofilebox">
           
                              <tr>
                                <td height="20px">Customer ID : <? echo(gridtrim40($customerid));?></td>
                              </tr>
                              
                               <tr>
                                <td height="20px">Dealer : <? echo(gridtrim40($currentdealer));?></td>
                              </tr>
                              <tr>
                                <td>Phone: <? echo(gridtrim40($phone));?>, <? echo(gridtrim40($cell));?></td>
                              </tr>
                              
                              
          </table></td></tr>
          
          
          </table>
          </td>
          
          </tr></table>
          </td></tr>
          <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><? if($_GET['error'] <> '') { ?>
                          <div class="errorbox"> <? echo('Invalid Entry.Please select the Product again.'); } ?></div></td>
                      </tr>
                      <tr>
                        <td><div align="center"><strong style="font-size:14px">Product Renewals</strong></div></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td style="font-size:12px; padding-left:91px"><font color="#FF0000">* Additional 15% Service Tax applicable</font></td>
                      </tr>
                      <tr>
                        <td><? echo($grid);?></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><input name="submit" style="height:30px; width:144px; cursor:pointer" type="button"  id="submit" value="Proceed" 
				onclick="formsubmit();" /></td>
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
              </table></td>
          </tr>
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
      <td><? include('inc/footer.php') ?></td>
    </tr>
  </table>
</form>

</body>
</html>