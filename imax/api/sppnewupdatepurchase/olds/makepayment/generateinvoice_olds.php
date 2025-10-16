<?php
   include("../functions/phpfunctions.php"); 
	Update the Payment status 
	$query1 = "UPDATE pre_online_purchase SET paymentstatus = 'PAID', paymentdate = '".date('Y-m-d')."', paymenttime = '".date('H:i:s')."' WHERE slno = '".$recordreferencestring."'";
	$result = runmysqlquery($query1);

	//details from pinv table 
	
        $query_pinv = "SELECT * from inv_spp_amc_pinv where txnid = '".$txnid."' ";
	$transaction_pinv = runmysqlqueryfetch($query_pinv);
	$trans_description = $transaction_pinv['description'];
	$trans_amount = $transaction_pinv['amount'];
	$trans_servicetax = $transaction_pinv['servicetax'];
	$trans_netamount = $transaction_pinv['netamount'];
	$trans_amountinwords = $transaction_pinv['amountinwords'];
	$trans_servicedescription = $transaction_pinv['servicedescription'];
	$trans_servicetype = $transaction_pinv['servicetype'];
	$trans_serviceamount = $transaction_pinv['serviceamount'];
	$trans_sbtax = $transaction_pinv['sbtax'];
	$trans_kktax = $transaction_pinv['kktax'];
	$trans_products = $transaction_pinv['products'];
	

var_dump($query_pinv);
echo "--------------------------------------<br>";

	//pinv table ends 

	//Check the dealer name 
	$query2 = "SELECT * from pre_online_purchase  where slno = '".$recordreferencestring."' ";
        
	$transaction = runmysqlqueryfetch($query2);
	$company = $transaction['businessname'];
	$contactperson = $transaction['contactperson'];
	$address = $transaction['address'];
	$place = $transaction['place'];
	$district = $transaction['district'];
	$pincode = $transaction['pincode'];
	$stdcode = $transaction['stdcode'];
	$phone = $transaction['phone'];
	$fax = $transaction['fax'];
	$cell = $transaction['cell'];
	$producttotal = $transaction['amount'];
	$emailid = $transaction['emailid'];
	$website = $transaction['website'];
	$category = $transaction['category'];
	$type = $transaction['type'];
	$currentdealer = $transaction['currentdealer'];
	$product = $transaction['products'];
	$custreference = $transaction['custreference'];
	$customerpurchasetype = $transaction['purchasetype'];
	$amount = $transaction['amount'];
	$split = explode('*',$product);
	$quantity = count($split);
	$productpurchasetype = $transaction['productpurchasetype'];
	$productpurchaseremarks = $transaction['remarks'];
	$productcodearray = $transaction['productcode'];
	$totalproductpricearray = $transaction['totalproductpricearray'];
	$productusagetype = $transaction['usagetype'];
	

	// To calculate the quantity
	$quantitycount = 1;
	for($k= 0;$k<$quantity;$k++)
	{
		$productquantity .= $quantitycount.',';
	}

	//take dealer details
		$result1 = runmysqlqueryfetch("Select inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname,inv_mas_dealer.emailid as emailid ,inv_mas_dealer.region  as regionid,inv_mas_dealer.branch as branchid,inv_mas_region.category as regionname,inv_mas_branch.branchname as branchname,inv_mas_dealer.relyonexecutive as relyonexecutive from inv_mas_dealer left join inv_mas_region on  inv_mas_region.slno = inv_mas_dealer.region 
left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_dealer.branch where inv_mas_dealer.slno = '".$currentdealer."'");

			$dealerid = $result1['slno'];
			$dealername = $result1['businessname'];
			$dealeremailid = $result1['emailid'];
			$branchname = $result1['branchname'];
			$regionname = $result1['regionname'];
			$regionid = $result1['regionid'];
			$branchid = $result1['branchid'];

		//Get the customer details
		$fetchquery = "select * from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode =inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode =inv_mas_district.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  where inv_mas_customer.slno = '".$custreference."';";

		$fetchresult1 = runmysqlqueryfetch($fetchquery);

		$customerid = $fetchresult1['customerid'];
		$categoryname = ($fetchresult1['businesstype'] == '')?'Not Available':$fetchresult1['businesstype'];
		$typename = ($fetchresult1['customertype'] == '')?'Not Available':$fetchresult1['customertype'];
		$districtname = $fetchresult1['districtname'];
		$statename = $fetchresult1['statename'];

	//Get the next record serial number for insertion in invoicenumbers table
	$query1 = "select ifnull(max(slno),0) + 1 as billref from inv_invoicenumbers";

	$resultfetch1 = runmysqlqueryfetch($query1);

	$onlineinvoiceno = $resultfetch1['billref'];

	//$invoicenogenerated = generatebillnumber($regionname);
	$query4 = "select ifnull(max(onlineinvoiceno),".getstartnumber($dealerregion).")+ 1 as invoicenotobeinserted from 
	inv_invoicenumbers where category = '".$regionname."'";

var_dump($query4);
echo "--------------------------------------<br>";

	$resultfetch4 = runmysqlqueryfetch($query4);

	$onlineinvoiceslno = $resultfetch4['invoicenotobeinserted'];
	$invoicenoformat = 'RSL/'.$regionname.'/'.$onlineinvoiceslno;
	$invoicenogeneratedsplit = explode('$',$invoicenogenerated);

	//Insert the inv_invoicenumbers table 
	$resultantquery = "Insert into inv_invoicenumbers(slno,invoiceno,category,onlineinvoiceno) values('".$invoicenoformat."',
	'".$invoicenoformat."','".$regionname."','".$onlineinvoiceslno."')";


var_dump($resultantquery);
echo "--------------------------------------<br>";

	$resultvalue3 = runmysqlquery($resultantquery);

	//update preonline purchase with invoice no
	$query3555 = "update pre_online_purchase set onlineinvoiceno = '".$onlineinvoiceno."' 
	where slno = '".$recordreferencestring."'";

	$result355 = runmysqlquery($query3555);

	//file to test by manju
	$myFile = "001100";
	$fh = fopen($myFile, 'a');

	//Generate the Bill
	
	$trans_description_split = explode('*',$trans_description);
       	$trans_product_split = explode('#',$trans_product);

	$maxloop = count($trans_description_split);

	for($counter=0;$counter<$maxloop;$counter++)
	{
                $products_allow = $trans_product_split[$counter]; 

		$total= '';
		$trans_description_split_vars = explode('$',$trans_description_split[$counter]);
		$prdprice = $trans_description_split_vars[6];
		$productusagetype = strtolower($trans_description_split_vars[3]);
		$productpurchasetype = strtolower($trans_description_split_vars[2]);
		
		$total += $prdprice;
		$currentdate = strtotime(date('Y-m-d'));

		$taxamt = round($prdprice *.15);
		$sevicetax += round($prdprice *.14);		
		$sbtax += round($prdprice *.005);
		$kktax += round($prdprice *.005);
		$netamount += $prdprice + $taxamt;
		
		$my_string= "\n" . $onlineinvoiceno . ", NetAmount=" . $netamount .", Product Price=" . $prdprice . ", Tax Amount=".$taxamt;
		fwrite($fh,$my_string);

		//Execute the PIN number Purchase

		$query17 = "SELECT attachPIN() as cardid;";
		$result75 = runmysqlqueryfetch($query17);

		//Attach that PIN Number to that dealer/customer

		$query744 = "INSERT INTO inv_dealercard(dealerid,cardid,productcode,date,usagetype,purchasetype,userid,customerreference,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,cusbillnumber,scheme,invoiceid,cuscardattacheddate,cuscardattachedby,usertype,addlicence) values('".$dealerid."','".$result75['cardid']."','".$products_allow."','".date('Y-m-d').' '.date('H:i:s')."','".$productusagetype."','".$productpurchasetype."','2','".$custreference."','".$productusagetype."','".$productpurchasetype."','".$products_allow."','".$dealerid."','','1','".$onlineinvoiceno."','".date('Y-m-d').' '.date('H:i:s')."','2','web','')";
		
$result177 = runmysqlquery($query744);	

	}

	fclose($fh);

	//Check whether purcased mode is relyon representative or self

	//Description of product	
	$carddetailsquery = "select * from inv_dealercard left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid  where invoiceid = '".$onlineinvoiceno."';";
	$carddetailsresult = runmysqlquery($carddetailsquery);

	$slno = 0;
	$k;
	$descriptioncount = 0;
	$k=0;
	while($carddetailsfetch = mysql_fetch_array($carddetailsresult))
	{
		$slno++;
		if($carddetailsfetch['purchasetype'] == 'new')
			$purchasetype = 'New';
		else
			$purchasetype = 'Updation';

		if($carddetailsfetch['addlicence'] == 'yes')
		{
			$usagetype = 'Additional License';
		}

		else
		{
			if($carddetailsfetch['usagetype'] == 'singleuser' || $carddetailsfetch['usagetype'] == 'additionallicense')
				$usagetype = 'Single User';
			else
				$usagetype = 'Multi User';
		}

		if($descriptioncount > 0)

			$description .= '*';

		$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.$purchasetype.'$'.$usagetype.'$'.$carddetailsfetch['scratchnumber'].'$'.$carddetailsfetch['cardid'].'$'.$productamountsplit[$k];
		$k++;
		$descriptioncount++;
	}

	$invemailid = explode(',', trim($emailid,','));
	$emailidplit = $invemailid[0];
	$invphonenumber = explode(',', trim($phone,','));
	$phonenumbersplit = $invphonenumber[0];
	$invcellnumber = explode(',', trim($cell,','));
	$cellnumbersplit = $invcellnumber[0];
	$invcontactperson = explode(',', trim($contactperson,','));
	$contactpersonplit = $invcontactperson[0];
	$invstdcode = ($stdcode == '')?'':$stdcode.' - ';
	$invaddress = $address.', '.$place.', '.$districtname.', '.$statename.', Pin: '.$pincode;
	$invoiceheading = ($statename == 'Karnataka')?'Tax Invoice':'Bill Of Sale';
	$amountinwords = convert_number($netamount);

	$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';

	//Update complete invoice details to invoicenumbers table 
	$query666 = "UPDATE inv_invoicenumbers  SET customerid = '".cusidcombine($customerid)."', businessname = '".trim($company)."',contactperson = '".$contactpersonplit."', address = '".$invaddress."', place = '".$place."',pincode = '".$pincode."' ,emailid = '".$emailidplit."' , description = '".$trans_description."' ,dealername = '".$dealername."', createddate = '".date('Y-m-d').' '.date('H:i:s')."',createdby = 'Webmaster', amount = '".$trans_amount."' , servicetax  = '".$trans_sevicetax."', sbtax  = '".$trans_sbtax."',kktax  = '".$trans_kktax."', netamount = '".$trans_netamount."' , phone  = '".$phonenumbersplit."' , cell = '".$cellnumbersplit."', customertype ='".$typename."' ,customercategory = '".$categoryname."' ,region = '".$regionname."' ,purchasetype = 'Product' ,dealerid = '".$dealerid."' ,products = '".$productcodearray."' ,productquantity =    '".trim($productquantity,',')."' ,pricingtype = 'default' ,createdbyid = '2' ,totalproductpricearray = '".$totalproductpricearray."',actualproductpricearray = '".$totalproductpricearray."' ,module = 'user_module' ,servicetype = '' ,serviceamount = '' ,paymenttypeselected = 'paymentmadenow',paymentmode = '".$paymentmode."' ,stdcode = '".$invstdcode."' ,branch = '".$branchname."' ,amountinwords = '".$amountinwords."' ,remarks = '".$invoicepayremarks."' ,servicetaxdesc = '".$servicetaxdesc."' ,invoiceheading = '".$invoiceheading."',servicedescription = '',offerdescription = '' ,offerremarks = '' ,invoiceremarks = 'None' ,duedate = '',status = 'ACTIVE',regionid = '".$regionid."',branchid = '".$branchid."' where slno = '".$onlineinvoiceno."';";

var_dump($query666);
echo "--------------------------------------<br>";

	$result66 = runmysqlquery($query666);

	//Get the next record serial number for insertion in receipts table
	$query45 ="select max(slno) + 1 as receiptslno from inv_mas_receipt";
	$resultfetch45 = runmysqlqueryfetch($query45);
	$receiptslno = $resultfetch45['receiptslno'];

	//Insert Receipt Details
	$query55 = "INSERT INTO inv_mas_receipt(slno,invoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) values('".$receiptslno."','".$onlineinvoiceno."','".$netamount."','".$netamount."','".$paymenttype."','','','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".$custreference."','".date('Y-m-d')."','".date('H:i:s')."','Online','no');";
	$result55 = runmysqlquery($query55);

	//Send receipt email
	sendreceipt($receiptslno,'resend');

	//Online bill Generation in PDF
	$pdftype = 'send';
	$invoicedetails = vieworgeneratepdfinvoice($onlineinvoiceno,$pdftype);
	$invoicedetailssplit = explode('^',$invoicedetails);
	$filebasename = $invoicedetailssplit[0];

	//Select the main data through record-reference
	$grid = '<table width="588px" cellpadding="3" cellspacing="0" border="1" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" >';

	$grid .= '<tr bgcolor ="#FF8080"><td nowrap = "nowrap" ><strong>Product Name</strong></td><td nowrap = "nowrap" ><strong>Usage Type</strong></td><td nowrap = "nowrap" ><strong>Purchase Type</strong></td><td nowrap = "nowrap" ><strong>PIN Serial Number</strong></td><td nowrap = "nowrap" ><strong>PIN Number</strong></td></tr>';

	$query0 = "SELECT inv_mas_product.productcode as productcode ,inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno,inv_mas_scratchcard.scratchnumber as pinno  FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode WHERE inv_dealercard.invoiceid='".$onlineinvoiceno."'";

	$result5 = runmysqlquery($query0);
	while($result4 = mysql_fetch_array($result5))
	{
		
		if($result4['purchasetype'] == "updation")
		{
			$purchase = "Updation";
		}
		else
		{
			$purchase = "New";
		}
		
		if($result4['usagetype'] == 'singleuser' || $result4['usagetype'] == 'additionallicense')
				$usage = 'Single User';
			else
				$usage = 'Multi User';
		
		
		$grid .= "<tr>";
		$grid .= "<td nowrap = 'nowrap'>".$result4['productname']."</td>";
		$grid .= "<td nowrap = 'nowrap'>".$usage."</td>";
		$grid .= "<td nowrap = 'nowrap'>".$purchase."</td>";
		$grid .= "<td nowrap = 'nowrap'>".$result4['cardno']."</td>";
		$grid .= "<td nowrap = 'nowrap'>".$result4['pinno']."</td>";
		$grid .= "</tr>";
	}
	$grid .= "</table>";	

	#########  Mailing Starts -----------------------------------

		//$emailid = 'rashmi.hk@relyonsoft.com';

		$emailid = $emailid;
		$emailarray = explode(',',$emailid);
		$emailcount = count($emailarray);

		for($i = 0; $i < $emailcount; $i++)
		{
			if(checkemailaddress($emailarray[$i]))
			{
				$emailids[$emailarray[$i]] = $emailarray[$i];
			}
		}

		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/paymentinfo1.htm");
		$textmsg = file_get_contents("../mailcontents/paymentinfo1.txt");

		$date = date('d-m-Y');

		$time = date('H:i:s');

		$array = array();

		$array[] = "##DATE##%^%".$date;
		$array[] = "##TIME##%^%".$time;
		$array[] = "##COMPANYNAME##%^%".$company;
		$array[] = "##CONTACTPERSON##%^%".$contactperson;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##ADDRESS##%^%".$address;
		$array[] = "##PINCODE##%^%".$pincode;
		$array[] = "##STDCODE##%^%".$stdcode;
		$array[] = "##PHONE##%^%".$phone;
		$array[] = "##CELL##%^%".$cell;
		$array[] = "##EMAIL##%^%".$emailid;
		$array[] = "##TOTALAMOUNT##%^%".$netamount;
		$array[] = "##TABLE##%^%".$grid;
		$array[] = "##INVOICENOFORMAT##%^%".$invoicenoformat;
		$filearray = array(

		array('../images/relyon-logo.jpg','inline','1234567890'),array('../images/relyon-rupee-small.jpg','inline','1234567892'),
		array('../upload/'.$filebasename.'','attachment','1234567891')

		);


		//Mail to customer
		$toarray = $emailids;
		//CC to the dealer
		//$dealeremailid = 'bhumika.p@relyonsoft.com';
		$ccemailarray = explode(',',$dealeremailid);
		$ccemailcount = count($ccemailarray);

		for($i = 0; $i < $ccemailcount; $i++)
		{
			if(checkemailaddress($ccemailarray[$i]))
			{
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			}
		}

		$ccarray = $ccemailids;

		//Copy of email to Accounts / Vijay Kumar / Bigmails 

		$bccarray = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts' => 'bills@relyonsoft.com', 'Webmaster' => 'webmaster@relyonsoft.com', 'Relyonimax'=> 'relyonimax@gmail.com'); 

		//$bccarray = array('archana' => 'archana.ab@relyonsoft.com');

		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$attachedfilename = explode('.',$filebasename);
		$subject = "Relyon Online Product Purchase | Invoice: ".$attachedfilename[0];
		$html = $msg;
		$text = $textmsg;
                $toarray_mail = array('samar.s@relyonsoft.com');
		rslmail($fromname, $fromemail, $toarray_mail, $subject, $text, $html,$ccarray,$bccarray,$filearray);
		fileDelete('../upload/',$filebasename);
		//rslcookiedelete();
		
function getstartnumber($region)
{
	switch($region)
	{
		case 'BKG': $startnumber = '1'; break;
		case 'BKM': $startnumber = '1';break;
		case 'CSD': $startnumber = '11101';break;
		default: $startnumber = '1';break;
	}
	return ($startnumber-1);
}
		
?>