<?php
   
	//Update the Payment status 
	  // $query1 = "UPDATE pre_online_purchase SET paymentstatus = 'PAID', paymentdate = '".date('Y-m-d')."', paymenttime = '".date('H:i:s')."' WHERE slno = '".$recordreferencestring."'";
         
	//$result = runmysqlquery($query1);

	//Check the dealer name 
	  $query2 = "SELECT * from inv_invoicenumbers  where slno = '".$recordreferencestring."' ";
          $result = runmysqlquery($query2);
          $transaction = mysql_fetch_assoc($result);
	   
         $onlineinvn = $transaction['onlineinvoiceno'];

         /* 
         $query_recpamt = "SELECT SUM(receiptamount) as paidamount FROM inv_mas_receipt WHERE invoiceno='".$onlineinvn."'";
	 $result_recptamt = runmysqlquery($query_recpamt);	
      
          $fetch_recptamt = mysql_fetch_assoc($result_recptamt); 
          $paidamount = $fetch_recptamt['paidamount']; 

           if($paidamount == 0 OR $paidamount == "" OR $paidamount == NULL )
           {
              $leftamount = $amount;              
           }
           else {
              $leftamount = $amount-$paidamount;
           }
      */
  

	//Get the next record serial number for insertion in invoicenumbers table
	 $query1 = "select slno as billref, netamount, customerid, businessname, contactperson, address, place, pincode,emailid from inv_invoicenumbers where slno = '".$recordreferencestring."' ";

	$resultfetch1 = runmysqlqueryfetch($query1);

        $onlineinvoiceno = $resultfetch1['billref'];
        $producttotal = $resultfetch1['netamount'];
        $custreference = substr($resultfetch1['customerid'], -5);
        $company = $resultfetch1['businessname'];
        $contactperson = $resultfetch1['contactperson'];
        $address = $resultfetch1['address'];
        $place = $resultfetch1['place'];
        $pincode = $resultfetch1['pincode'];
        $emailid = $resultfetch1['emailid'];
        //$netamount = $resultfetch1['netamount'];

	//Get the next record serial number for insertion in receipts table
	$query45 ="select max(slno) + 1 as receiptslno from inv_mas_receipt";
	$resultfetch45 = runmysqlqueryfetch($query45);
	$receiptslno = $resultfetch45['receiptslno'];

	//Insert Receipt Details
	 $query55 = "INSERT INTO inv_mas_receipt(slno,invoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) values('".$receiptslno."','".$onlineinvoiceno."','".$producttotal."','".$amount."','".$paymenttype."','','','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".$custreference."','".date('Y-m-d')."','".date('H:i:s')."','Online','no');";

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
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
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