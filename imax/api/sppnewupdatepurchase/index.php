<?php
    include('functions/phpfunctions.php');
    $count8 = $count9 = "";
	
	if($_REQUEST['Qa1iio9']==""  || $_REQUEST['AsWrIo'] == "")
	{
	    exit;
	}
	 
    	$customerid = decodevalue($_REQUEST['Qa1iio9']);
    	$productcode = decodevalue($_REQUEST['AsWrIo']);
    
    	if(strlen($customerid) == 5) { $cusid = $customerid; }
    	elseif(strlen($customerid) > 5) { $cusid = substr($customerid,-5); }

	    $actualprice =""; $updationprice=""; $checkproductcode="";
     
        $final_amount = 0;
        $query1 = "SELECT * from inv_spp_amc_pinv where slno = '".$productcode."' AND RIGHT(customerid,5) = '".$cusid."'";
        $result1 = runmysqlquery($query1);
        $fetch1 = mysql_fetch_array($result1);

        if(!$fetch1)
        {
          echo "Invalid Data Selection";
          exit();
        }
        
	        $businessname = $fetch1['businessname'];
	        $customerid = $fetch1['customerid'];
            $contactperson = $fetch1['contactperson'];
            $show_slno = $fetch1['slno'];
            $dealerid = $fetch1['dealerid'];
            $dealername = $fetch1['dealername'];
            $emailid = $fetch1['emailid'];
            $phone = $fetch1['phone'];
            $cell = $fetch1['cell'];
            $appendzero = '.00';

        $customerid = str_replace("-","",$customerid);

        $query_customer = "SELECT  * from `inv_mas_customer` WHERE customerid = ".$customerid.";";
        $result_customer = runmysqlquery($query_customer);
        $fetch_customer = mysql_fetch_array($result_customer);

    	 $statename = $fetch_customer['state'];
    	 $place = $fetch_customer['place'];
    	 $districtname = $fetch_customer['district'];
         $currentdealer = $fetch_customer['currentdealer'];


        /* $customerid_new = substr($customerid,-5);

        $query_customer_details = "SELECT  * from `inv_contactdetails` WHERE customerid = ".$customerid_new.";";
        $result_customer_details = runmysqlquery($query_customer_details);
        $fetch_customer_details = mysql_fetch_array($result_customer_details);

	 $emailid = $fetch_customer_details['emailid'];
         $phone = $fetch_customer_details['phone'];
         $cell = $fetch_customer_details['cell']; */


        $query_dealer = "SELECT businessname,emailid,phone,cell from `inv_mas_dealer` WHERE slno = ".$dealerid.";";
        $result_dealer = runmysqlquery($query_dealer);
        $fetch_dealer = mysql_fetch_array($result_dealer);

        $currentdealer_name = $fetch_dealer['businessname'];
        $currentdealer_email = $fetch_dealer['emailid'];
        $currentdealer_phone = $fetch_dealer['phone'];
        $currentdealer_cell = $fetch_dealer['cell'];


$grid="<table cellpadding='10' border='1px solid black' style='border-collapse: collapse;' width='650'><tr><td class='tabsclass'>Sl No.</td><td  class='tabsclass'>Particulars</td><td class='tabsclass'>Amount</td></tr>";
       $product='';
        $description = $fetch1['description'];
		$productbriefdescription = $fetch1['productbriefdescription'];
		$productbriefdescriptionsplit = explode('#',$productbriefdescription);
		$descriptionsplit = explode('*',$description);
		$count=1;
        for($i=0;$i<count($descriptionsplit);$i++)
		{
			$productdesvalue = '';
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($productbriefdescription <> '')
				$productdesvalue = $productbriefdescriptionsplit[$i];
			else
				$productdesvalue = 'Not Available';
			
				if($description <> '')
				{       $product=$descriptionline[1];
				
                    if($descriptionline[6] > 0)
                    {
                    	$grid .= '<tr>';
					    $grid .= '<td width="70" style="text-align:centre;">'.$countt.'</td>';
					    $grid .= '<td width="" style="text-align:left;">'.$descriptionline[1].'<br/>
			            <span style="font-size:+7" ><strong>Purchase Type</strong> : '.$updationtype.'&nbsp;/&nbsp;<strong>Usage Type</strong> 
			            :'.$descriptionline[3].'&nbsp;&nbsp;</span><br/><span style="font-size:+6" ><strong>Product Description</strong> 
			            : '.$productdesvalue.' </span><span style="font-size:+6" > / <strong>SAC</strong> : 9983</span></td>';
					    $grid .= '<td  width="100" style="text-align:right;" >'.formatnumber($descriptionline[6]).$appendzero.'</td>';
					    $grid .= "</tr>";
					
					    $final_amount = $final_amount + $descriptionline[6];
                        $incno++;$count++;
                    }

				}
		}
		
		$itembriefdescription = $fetch1['itembriefdescription'];
		$itembriefdescriptionsplit = explode('#',$itembriefdescription);
		$servicedescriptionsplit = explode('*',$fetch1['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch1['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$itemdesvalue = '';
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				if($itembriefdescription <> '')
					$itemdesvalue = $itembriefdescriptionsplit[$i];
				else
					$itemdesvalue = 'Not Available';
					
                    if($servicedescriptionline[2] > 0)
                    {
                    	$grid .= '<tr>';
    				    $grid .= '<td width="70" style="text-align:centre;">'.$countt.'</td>';
				        $grid .= '<td width="" style="text-align:left;">'.$servicedescriptionline[1].'<br/>
				        <span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span>
				        <span style="font-size:+6" > / <strong>SAC</strong> : 9983</span></td>';
				        $grid .= '<td  width="100" style="text-align:right;" >'.formatnumber($servicedescriptionline[2]).$appendzero.'</td>';
				        $grid .= "</tr>";
				
				        $final_amount = $final_amount + $servicedescriptionline[2];
                        $count++;
                    }
			}
		}
		
		$grid .= '<tr>
    <td width="70" style="text-align:centre;">&nbsp;</td>
    <td width="" style="text-align:left;">Invoice Total</td>
    <td  width="100" style="text-align:right;" >'.formatnumber($final_amount).$appendzero.'</td></tr>';
		
		$offerdescriptionsplit = explode('*',$fetch1['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch1['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="70" style="text-align:centre;">&nbsp;</td>';
				
				if($offerdescriptionline[0] == 'percentage' || $offerdescriptionline[0] == 'amount')
				{
				    $grid .= '<td width="" style="text-align:left;">'.$offerdescriptionline[1].'</td>';
				}
				else
				{
				    $grid .= '<td width="" style="text-align:left;">'.strtoupper($offerdescriptionline[0]).': '.$offerdescriptionline[1].'</td>';
				}
				
				$grid .= '<td  width="100" style="text-align:right;" >'.formatnumber($offerdescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch1['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="66%" style="text-align:left;">'.$fetch1['offerremarks'].'</td><td >&nbsp;</td></tr>';
		$descriptionlinecount = 0;
		if($description <> '')
		{
			//Add description "Internet downloaded software"
			$grid .= '<table cellpadding="5" width="650" style="border-collapse: collapse;" border="1px solid black">';
			$descriptionlinecount = 1;
		}
		if($fetch1['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch1['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch1['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount + $descriptionlinecount;
		if($rowcount < 6)
		{
			//$grid .= addlinebreak(1);

		}
		
		if($fetch1['status'] == 'EDITED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch1['editedby']."';";
			$resultfetch1011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch1011['fullname'];
			$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch1['editeddate']).' <br/>Remarks: '.$fetch1['editedremarks'];
		}
		elseif($fetch1['status'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch1['cancelledby']."';";
			$resultfetch1011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch1011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch1['cancelleddate']).'  <br/>Remarks: '.$fetch1['cancelledremarks'];

		}
		else
			$statusremarks = '';
			//echo($statusremarks); exit;
			
		$invoicedatedisplay = substr($fetch1['createddate'],0,10);
		$invoicedate =  strtotime($invoicedatedisplay);
		$expirydate = strtotime('2012-04-01');
		$expirydate1 = strtotime('2015-06-01');
		$expirydate2 = strtotime('2015-11-15');
		$KK_Cess_date = strtotime('2016-05-31');

		//echo $invoicedate ;echo $sb_expirydate;
		//echo $invoicedate; echo $sb_expirydate; 
		

/*----------------------Data Calculation-------------------------------*/

	
/*if($fetch1['seztaxtype'] == 'yes')
		{			
			$sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';	
		}
		else
		{
			$sezremarks = '';
		}
		
				$servicetax1 = roundnearestvalue($fetch1['amount'] * 0.14);
				$servicetax2 = roundnearestvalue($fetch1['amount'] * 0.005);
				$servicetaxname = 'Service Tax @ 14%';
				$servicetaxname1 = 'SB Cess @ 0.5%';
				$totalservicetax = formatnumber($servicetax1).$appendzero;
				$totalservicetax1 = formatnumber($servicetax2).$appendzero;
				
				$sbcolumn = '<tr><td  style="text-align:left">&nbsp;</td>
				<td style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
				<td style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';
				
				$KK_Cess_tax = roundnearestvalue($fetch1['amount'] * 0.005);
				$kkcolumn='<tr><td style="text-align:right"></td><td style="text-align:right">
				<strong>KK Cess @ 0.5% </strong></td>
				<td style="text-align:right;font-size:+5">'.formatnumber($KK_Cess_tax).$appendzero.'</td></tr>';




		$billdatedisplay = changedateformat(substr($fetch1['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit;

$grid .= '<tr><td width="80">&nbsp;</td><td></td><td width="110"></td></tr>';
		$grid .= '<tr class="tabsclass">
		<td style="text-align:right"><span style="font-size:+6" >'.$fetch1['servicetaxdesc'].' </span></td>
		<td style="text-align:right"><strong>Net Amount</strong></td>
		<td style="text-align:right">'.formatnumber($fetch1['amount']).$appendzero.'</td></tr>
		<tr>
		<td style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
		<td style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td  style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn;



$grid .= '<tr class="tabsclass">
<td style="text-align:right"><div align="left"></div></td>
<td style="text-align:right"><strong>Total</strong></td>
<td style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch1['netamount'] ).$appendzero.'</td> 
</tr><tr><td colspan="3" style="text-align:left"><strong>Rupees In Words</strong>: '.convert_number($fetch1['netamount']).' only</td></tr>';
	        

   $grid.='</td></tr></table></tr><tr><td></td></tr></table>';*/
   
   
   
   
$grid.='<tr><td width="80" style="text-align:right"><div align="left"></div></td>
<td style="text-align:right"><strong>Total Amount</strong></td>
<td width="110" style="text-align:right">'.$fetch1['amount'].$appendzero.'</td></tr>';

                if(($fetch1['cgst'] == '0' &&  $fetch1['sgst'] == '0') || ($fetch1['cgst'] == '' &&  $fetch1['sgst'] == ''))
            	{
            	    $grid .='<tr>
            	    <td width="80" style="text-align:right"></td>
            	    <td width="" style="text-align:right"><strong>IGST Tax @ 18% </strong></td>
            	    <td width="110" style="text-align:right;font-size:+9">'.formatnumber($fetch1['igst']).'</td></tr>';
            	}
            	else
            	{
            	    $grid .='<tr>
            	    <td width="80" style="text-align:right"></td>
            	    <td width="" style="text-align:right"><strong>CGST Tax @ 9% </strong></td>
            	    <td width="110" style="text-align:right;font-size:+9">'.formatnumber($fetch1['cgst']).'</td>
            	    </tr>
            	    <tr>
            	    <td width="80" style="text-align:right"></td>
            	    <td width="" style="text-align:right"><strong>SGST Tax @ 9% </strong></td>
            	    <td width="110" style="text-align:right;font-size:+9">'.formatnumber($fetch1['sgst']).'</td></tr>';
            	}

/*$grid.='<tr><td  width="10%" style="text-align:right"><div align="left"></div></td><td style="text-align:right"><strong>Service Tax @ 14%	</strong></td><td style="text-align:right">'.$fetch1['servicetax'].$appendzero.'</td></tr>';    
$grid.='<tr><td  width="10%" style="text-align:right"><div align="left"></div></td><td style="text-align:right"><strong>SB Cess @ 0.5%	</strong></td><td style="text-align:right">'.$fetch1['sbtax'].$appendzero.'</td></tr>'; 
$grid.='<tr><td  width="10%" style="text-align:right"><div align="left"></div></td><td style="text-align:right"><strong>KK Cess @ 0.5%	</strong></td><td style="text-align:right">'.$fetch1['kktax'].$appendzero.'</td></tr>';*/

/*-----------------Round Off ----------------------*/
  $roundoff = 'false';
  $roundoff_value = '';
  $addition_amount = $fetch1['amount'] + $fetch1['igst']+ $fetch1['cgst'] + $fetch1['sgst'];
  
  $netamount = $fetch1['netamount'];
  
 $roundoff_value = $netamount- $addition_amount;

if($roundoff_value != 0 || $roundoff_value != 0.00)
{
  $roundoff = 'true';
}

if($roundoff == 'true')
{
	$roundoff_value = number_format($roundoff_value,2);
    $grid .= '<tr>
    <td  width="80" style="text-align:right"><div align="left"></div></td>
    <td  width="" style="text-align:right"><strong>Round Off</strong></td>
    <td  width="110" style="text-align:right">&nbsp;&nbsp;'.$roundoff_value.'</td> 
    </tr>';
}
/*-----------------Round Off Ends ----------------------*/


$grid .= '<tr>
<td  width="80" style="text-align:right"><div align="left"></div></td>
<td  width="" style="text-align:right"><strong>Total</strong></td>
<td  width="110" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" alt="Relynsoft" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch1['netamount'] ).$appendzero.'</td> 
</tr>
<tr><td colspan="3" style="text-align:left"><strong>Rupees in words</strong>: '.convert_number($fetch1['netamount']).' only</td></tr>';  
$grid.='</table>'; 


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relyon Softech Ltd - Buy Online</title>
<script type='text/javascript' src='js/jquery.min.js'></script>
<script type="text/javascript" src="functions/paymode.js?dummy= <? echo (rand());?>"></script>
<script type="text/javascript" src="functions/dashboard.js?dummy= <? echo (rand());?>"></script>
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
<script type="text/javascript">
   $(document).ready( function() {
          $('#popupBoxClose').click( function() {  
			$(".modalOverlay").remove();          
            unloadPopupBox();
        });

          $('#closetangrid').click( function() {  
			$(".modalOverlay").remove();          
            unloadformdata();
        });

          $('#closetpayconfirm').click( function() {  
			$(".modalOverlay").remove();          
            unloadpayconfirm();
        });
   });

</script>
<style type="text/css">
#invoicedetailsgrid, #customertangrid,#payconfirmation {
	display: none;
	position: fixed;
        height: auto;
        min-height: 200px;
        width: 400px;
	background: #FFFFFF;
        left: 445px;
        top: 150px;
	z-index: 100;
	margin-left: 15px;
	border: 1px solid #328cb8;
	box-shadow: 0px 0px 30px #666666;
	font-size: 15px;
	-moz-border-radius: 15px;
	border-radius: 15px;
}
#toberemovedmsg { display:none; }
a {
	cursor: pointer;
	text-decoration: none;
}
.mypay {
 font-weight: bold;
 color: #000;
 text-decoration: underline;
}
.tabsclass
{
    font-weight: bold;
    text-align: center;
    padding: 9px;
    background: #c0c0c0;
}
.mybutton {
    padding: 5px;
    padding-left: 15px;
    padding-right: 15px;
    position: relative;
    top: 4px;
    border-radius: 5px;
    font-size: 15px;
    background-color: #01acf1;
    border-color: #01acf1;
    color: #fff !important;
    border-style: solid;
}
.mybutton:hover {
    padding: 5px;
    padding-left: 15px;
    padding-right: 15px;
    position: relative;
    top: 4px;
    border-radius: 5px;
    font-size: 15px;
    background-color: #dd6f00;
    border-color: #dd6f00;
    color: #fff !important;
    border-style: solid;
    cursor: pointer;
}
#popupBoxClose, #closetangrid,#closetpayconfirm { font-size: 14px; line-height: 15px; right: 5px; top: 5px; position: absolute;
    color: #FFF; font-weight: 500; }
.ajax-loader, .ajax-loader-cust { visibility: hidden; background-color: rgba(255,255,255,0.7); position: fixed;  z-index: 99999 !important;
  width: 100%;  height:100%; }
#innerpayconfirm button { margin:2%; }
.ajax-loader img, .ajax-loader-cust img { position: fixed; top:50%; left:50%; }
.ajax-loader p { position: fixed; top:63%; left:25%; }
.ajax-loader-cust p { position: fixed; top:63%; left:46%; }

#customertanerror {text-align:center; color: red;}
</style>
</head>
<body>


<div class="ajax-loader">
  <img src="images/loading.gif" class="img-responsive" />
  <p style="color: red;font-weight:bolder;text-align:center;">"You will be redirected to your merchant's website in a few seconds.<br>
Please DO NOT refresh the page, close the browser or go back. This may result in your transaction failing".</p>
</div>

<div class="ajax-loader-cust">
  <img src="images/loading.gif" class="img-responsive" />
  <p style="color: red;font-weight:bolder;text-align:center;">"Please Wait"</p>
</div>

<form method="post" action="" method="post" name="submitexistform" id="submitexistform">
  <table width="900px" border="0" align="center" cellpadding="5" cellspacing="0" >
    <tr>
      <td  colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td ><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td width="700" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="content-top">&nbsp;</td>
                </tr>
                   <tr>
                      <td colspan="2" class="content-mid"><? include('inc/header.php') ?></td>
                  </tr>
                   <tr>
                      <td colspan="2" class="content-mid"><br><br><br></td>
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
      <td width="60%" valign="top" style="background:url(images/imax-cust-dashboard-profile-stirp.jpg) no-repeat top left; background-size: 330px 192px">
      <table width="300px" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td height="172px"><table width="270" border="0" cellpadding="4" cellspacing="0" class="dashboardprofilebox">
           <tr>
                                <td ><? echo(gridtrim40($businessname));?></td>
                              </tr>

                              <tr>
                                <td height="20px">Customer ID : <? echo cusidcombine($customerid);?></td>
                              </tr>

                              <tr>
                                <td><? echo(gridtrim40($contactperson));?></td>
                              </tr>
                              <tr>
                                <td><? echo(gridtrim40($place));?>, <? echo(gridtrim40($districtname));?>, <? echo(gridtrim40($statename));?></td>
                              </tr>
                              
                              <tr>
                                <td height="20px">Email : <? echo(gridtrim40($emailid));?></td>
                              </tr>
                              <tr>
                                <td>Phone : <? echo(gridtrim40($phone));?>, <? echo(gridtrim40($cell));?></td>
                              </tr>
                              
          </table></td></tr>
          </table>
          </td>
                          <td width="60%" valign="top" style="background:url(images/imax-cust-dashboard-profile-stirp.jpg) no-repeat top left; background-size: 290px 192px" >
      <table width="300px" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td height="172px" >
          <table width="270" border="0" cellpadding="4" cellspacing="0" class="dashboardprofilebox">           
                             
                               <tr>
                                <td height="20px">Relyon Executive : <? echo(gridtrim40($dealername));?></td>
                              </tr>
                              <tr>
                                <td>Email : <? echo(gridtrim40($currentdealer_email)); ?></td>
                              </tr>
                              <tr>
                                <td>Phone : <? echo(gridtrim40($currentdealer_phone)); echo " / "; echo(gridtrim40($currentdealer_cell)); ?></td>
                              </tr>
                              
                              
          </table></td></tr>
          
          
          </table>
          </td>
          
          </tr></table>
          </td></tr>
                      <tr>
                        <td><? if($_GET['error'] <> '') { ?>
                          <div class="errorbox"> <? echo('Invalid Entry.Please select the Product again.'); } ?></div></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                     <!--  <tr>
                        <td style="font-size:12px; padding-left:91px"><font color="#FF0000">* Additional 15% Service Tax applicable</font></td>
                      </tr> -->
                      <tr>
                        <td><? echo($grid);?></td>
                      </tr>
                      <tr>
                        <td>

                    	<div id="invoicedetailsgrid">
                            		<div style="background-color:#328cb8; height:25px; -moz-border-top-left-radius: 15px;border-top-left-radius: 15px;-moz-border-top-right-radius: 15px;border-top-right-radius: 15px;">

                              <font style="font-size:14px; line-height:15px;left:5px;top:5px; position:absolute;  color:#FFF; font-weight:500; ">Payment mode</font>
 			<a id="popupBoxClose">Close</a></div>

                                  	<table align="center"  width="100%" border="0" cellspacing="10px" cellpadding="0">
                                    <tr><td>
                                     <label> <input type="radio" id="radio1" name="paymode" value="credit" />&nbsp;Pay via Credit / Debit Card</label><br />
                                     </td></tr>
                                     <tr><td>
                                      <label><input type="radio" id="radio2" name="paymode" value="internet" />&nbsp;Pay via Net Banking</label><br /></td></tr>
                                     <tr><td>
                                      <label><input type="radio" id="radio3" name="paymode" value="internet" />&nbsp;Pay Using Razorpay payment Gateway</label><br /><br />
                                      (Net Banking/Credit card/Mobile wallets/UPI)</td></tr>
                                     <tr><td>&nbsp;
                                      <input type="hidden" name="customerid" id="customerid" value="<?php echo $customerid; ?>" >
                                      <input type="hidden" name="lslnop" id="lslnop" value="">
                                      <input type="hidden" name="new_lslnop" id="new_lslnop" value="">
                                      <input type="hidden" name="amountpayable" id="amountpayable" value="<?php echo $fetch1['netamount']; ?>">
                                      <input type="hidden" name="show_slno" id="show_slno" value="<?php echo $show_slno; ?>">
                                      <div id="err" style="color:red;"></div></td></tr><tr><td align="center">
                                      <input name="custpayment" type="button" id="custpayment" value="Proceed for Payment" onclick ="formsubmit()"/></td></tr>
                                    </table>
                              </div>

                      </td>
                      </tr>
                    </table>
            </td>
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

<td colspan="2" height='100'>
<div id="givepaymentoption" style="text-align:center;"></div>
</td>

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

<div id="payconfirmation" style="text-align: center;">


                  <div style="background-color:#328cb8; height:25px; -moz-border-top-left-radius: 15px;border-top-left-radius: 15px;-moz-border-top-right-radius: 15px;border-top-right-radius: 15px;">

                              <font style="font-size:14px; line-height:15px;left:5px;top:5px; position:absolute;  color:#FFF; font-weight:500; "></font>
 			<a id="closetpayconfirm">Close</a></div>
<br><br>
   Do You Want To Deduct TDS & Pay ?
<br><br>
   <div id="payamountcal"></div>
<br>
<div id="innerpayconfirm">
</div>

</div>



<div id="customertangrid">

<form method="post" action="" method="post" name="submitexistformcusdata" id="submitexistformcusdata">

                  <div style="background-color:#328cb8; height:25px; -moz-border-top-left-radius: 15px;border-top-left-radius: 15px;-moz-border-top-right-radius: 15px;border-top-right-radius: 15px;">

                              <font style="font-size:14px; line-height:15px;left:5px;top:5px; position:absolute;  color:#FFF; font-weight:500; "></font>
 			<a id="closetangrid">Close</a></div>
<br>
<center>Please Enter TAN No.</center>
<br>
<div id="customertanerror"></div>
<div id="toberemoved">

<center>
<input type="text" name="customertan" id="customertan" placeholder="Enter Your TAN No" maxlength="10">
<input type="hidden" name="customerid" id="customerid" value="<?php echo $customerid; ?>" >
<input type="hidden" name="show_slno" id="show_slno" value="<?php echo $show_slno; ?>">
<br><br>
<input name="customertanumber" class="mybutton" type="button"  id="customertanumber" value="Proceed" onclick ="customertanumbers()"/>
</center>
</div>
</form>
<div id="toberemovedmsg"></div>

 </div>
<div id="totalamountpay" style="visibility:hidden"><?php echo $fetch1['amount']; ?></div>
</body>
</html>