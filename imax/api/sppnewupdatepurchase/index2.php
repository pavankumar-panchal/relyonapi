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

        $query1 = "SELECT  * from `inv_spp_amc_pinv` WHERE slno= '13'";

        $result1 = runmysqlquery($query1);
        $fetch1 = mysql_fetch_array($result1);


$grid="<table border='1px solid black' style='border-collapse: collapse;' width='100%'>";
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
					$grid .= '<tr>';
					$grid .= '<td width="10%" style="text-align:centre;">'.$count.'</td>';
					$grid .= '<td width="66style="text-align:left;">'.$descriptionline[1].'<br/>
			<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;<br/><span style="font-size:+6" ><strong>Product Description</strong> : '.$productdesvalue.' </span></td>';
					$grid .= '<td  width="8%" style="text-align:right;" >'.formatnumber($descriptionline[6]).$appendzero.'</td>';
					$grid .= "</tr>";$incno++;$count++;
				}
			//}
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
					
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$count.'</td>';
				$grid .= '<td width="66%" style="text-align:left;">'.$servicedescriptionline[1].'<br/><span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span></td>';
				$grid .= '<td  width="8%" style="text-align:right;" >'.formatnumber($servicedescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
                                $count++;
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch1['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch1['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="66%" style="text-align:left;">'.strtoupper($offerdescriptionline[0]).': '.$offerdescriptionline[1].'</td>';
				$grid .= '<td  width="8%" style="text-align:right;" >'.formatnumber($offerdescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch1['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="66%" style="text-align:left;">'.$fetch1['offerremarks'].'</td><td width="8%">&nbsp;</td></tr>';
		$descriptionlinecount = 0;
		if($description <> '')
		{
			//Add description "Internet downloaded software"
			$grid .= '<table style="border-collapse: collapse;" border="1px solid black">';
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
			$grid .= addlinebreak($rowcount);

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
			
		if($fetch1['seztaxtype'] == 'yes')
		{
			$sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
			
			if($expirydate >= $invoicedate || $expirydate1 > $invoicedate)
			{
				$servicetax1 = 0;
				$servicetax2 = 0;
				$servicetax3 = 0;
			
				$servicetaxname = '<br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.
				formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate2 > $invoicedate)
			{
				$servicetax1 = 0;
				$totalservicetax = formatnumber($servicetax1).$appendzero;
			}
			else
			{
				$servicetax1 = 0;
				$totalservicetax = formatnumber($servicetax1).$appendzero;
				$servicetaxname1 = 'SB Cess @ 0.5%';
				$servicetax2 = 0;
				$servicetaxname2 = 'KK Cess @ 0.5%';
				$servicetax3 = 0;
				$totalservicetax1 = $servicetax2.$appendzero;
				
				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
				<td  width="8%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';
	if($KK_Cess_date < $invoicedate)
		{
			$kkcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
			<td  width="30%" style="text-align:right"><strong>'.$servicetaxname2.'</strong></td>
			<td  width="8%" style="text-align:right!important;"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';
		}
			}
		}
		else
		{
			if($expirydate >= $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch1['amount'] * 0.1);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 10% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$servicetax3 = roundnearestvalue(($fetch1['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate1 > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch1['amount'] * 0.12);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 12% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$servicetax3 = roundnearestvalue(($fetch1['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate2 > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch1['amount'] * 0.14);
				$servicetaxname = 'Service Tax @ 14%';
				$totalservicetax = formatnumber($servicetax1).$appendzero;
			}
			else
			{
				$servicetax1 = roundnearestvalue($fetch1['amount'] * 0.14);
				$servicetax2 = roundnearestvalue($fetch1['amount'] * 0.005);
				$servicetaxname = 'Service Tax @ 14%';
				$servicetaxname1 = 'SB Cess @ 0.5%';
				$totalservicetax = formatnumber($servicetax1).$appendzero;
				$totalservicetax1 = formatnumber($servicetax2).$appendzero;
				
				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
				<td  width="8%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';

				if($KK_Cess_date < $invoicedate)
				{
	               $KK_Cess_tax = roundnearestvalue($fetch1['amount'] * 0.005);
				   $kkcolumn='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>KK Cess @ 0.5% </strong></td><td width="8%" style="text-align:right;font-size:+5">'.formatnumber($KK_Cess_tax).$appendzero.'</td></tr>';
				}
			}
			
			$sezremarks = '';
			
		}
		$billdatedisplay = changedateformat(substr($fetch1['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit;
		$grid .= '<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch1['servicetaxdesc'].' </span></td>
		<td  width="20%" style="text-align:right"><strong>Net Amount</strong></td>
		<td  width="24%" style="text-align:right">'.formatnumber($fetch1['amount']).$appendzero.'</td></tr>
		<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
		<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="8%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn;



$grid .= '<tr>
<td  width="56%" style="text-align:right"><div align="left"></div></td>
<td  width="30%" style="text-align:right"><strong>Total</strong></td>
<td  width="8%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch1['netamount'] ).$appendzero.'</td> 
</tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.convert_number($fetch1['netamount']).' only</td></tr>';
	//	echo($grid1); exit;
	//	$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="8%" style="text-align:right">'.$fetch1['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch1['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="8%" style="text-align:right">'.$fetch1['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="8%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch1['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch1['amountinwords'].' only</td></tr>';
	        

   $grid.='</td></tr></table></tr><tr><td><center><a href="http://imax.relyonsoft.com/sppnewupdatepurchase/index.php?Qa1iio9='.$paycusid.'&AsWrIo='.$payproductcode.'" target="_blank" class="myButton">Pay Now</a></center></td></tr></table>';

echo $grid;

?>