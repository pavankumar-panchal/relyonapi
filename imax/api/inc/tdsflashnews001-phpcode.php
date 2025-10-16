<?

$rcidata = decodevalue($_GET['rj8kb5ns']);
$deductorinfo = decodevalue($_GET['sladjf8s']);
/*$rcidata = '15238118110113457^Relyon Ltd.^X2LB-2RCM-ZL1K^65109-239846015^5.06^Microsoft Windows XP Professional Service Pack 3^32-bit';
$rcidataencoded = "8h<h9h:h4h?h8h8h?h4h8h8h7h8h4h8h:h;h<h>heh]hphqhhh€heh8h9h:h;h4h8h9h:h;h4h8h9h:h;heh=h;h8h7h7h4h<h>h8h9h9h9h;h9h<heh8h8h5h7h=heh^hphuhkhvh~hzh'h>h'hZhlhyh}hphjhlh'hWhhhjhrh'h8heh:h9";
$deductorinfo = 'Name^TAN^Email1^Email2^Mobile^Type(R/C)^Date^FY^Form^Qtr^ProductString';*/
$accessdate = date("Y-m-d");
$accessip = getenv("REMOTE_ADDR");
$accesstime = date("H:i");
$rcidataencoded = encodevalue($rcidata);
setcookie('rcidataencoded', $rcidataencoded);

//VALIDATE Relyon Cusotmer Information data by its structure
if($rcidata <> '')
{
	$rcidatasplit = explode('^',$rcidata);
	$customerid = $rcidatasplit[0];
	$registeredname = $rcidatasplit[1];
	$pinnumber = $rcidatasplit[2];
	$computerid = $rcidatasplit[3];
	$productversion = $rcidatasplit[4];
	$operatingsystem = $rcidatasplit[5];
	$processor = $rcidatasplit[6];
	$rcidatavalidata = rcidatanotrestricted($rcidata);
}

if(!isset($rcidatavalidata))
{
	//echo('INVALIDREQUEST');exit;
}

//Validate customer ID with iMax database and Respond in case of invalid (also exit)
if($customerid <> '')
{
	$customeridlen = strlen($customerid);
	if($customeridlen == 20)
		$validatedcustomerid = cusidsplit($customerid);
	else
		$validatedcustomerid = $customerid;
		
	$query = "select * from inv_mas_customer where customerid = '".$validatedcustomerid."';";
	$resultcustomermaster  = runmysqlquery($query);
	if(mysql_num_rows($resultcustomermaster) == 0)
	{
		echo("INVALIDCUSTOMERID");
		exit;
	}
}

//Check if any record exists for given combination
$query1 = "select * from inv_logs_webservices where customerid = '".$validatedcustomerid."' and registeredname = '".$registeredname."' and pinnumber = '".$pinnumber."' and computerid = '".$computerid."' and productversion = '".$productversion."' and operatingsystem = '".$operatingsystem."' and processor = '".$processor."' and servicename = 'TDS-FLASH-NEWS' limit 1";
$result1 = runmysqlquery($query1);
if(mysql_num_rows($result1) > 0)
{
	$fetch1 = mysql_fetch_array($result1);
	$currentlogslno = $fetch1['slno'];
}

if($currentlogslno == '')
{
  //Insert the data to iMax webservices table with proper separations
  $query = "Insert into inv_logs_webservices(customerid,registeredname,pinnumber,computerid,productversion,operatingsystem,processor,`date`,ip,servicename) values('".$validatedcustomerid."','".$registeredname."','".$pinnumber."','".$computerid."','".$productversion."','".$operatingsystem."','".$processor."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','TDS-FLASH-NEWS')";
  $result = runmysqlquery($query);
}
else
{
	$query = "update inv_logs_webservices set `date` = '".date('Y-m-d').' '.date('H:i:s')."', ip = '".$_SERVER['REMOTE_ADDR']."' where slno = '".$currentlogslno."'";
	$result = runmysqlquery($query);
}

//Check if deductor information is present. If yes, update it
if(isset($deductorinfo) && $deductorinfo <> '')
{
	//$deductorinfo => Name^TAN^Email1^Email2^Mobile
	$deductorsplit = explode('^',$deductorinfo);
	
	if($deductorsplit[5] == "") // Called on Software open
	{
		//Check if any record exists for given combination
		$query = "select slno from tds_flashnews_deductorinfo where customerid = '".$validatedcustomerid."' and deductorname = '".$deductorsplit[0]."' and tannumber = '".$deductorsplit[1]."' and emailid1 = '".$deductorsplit[2]."' and emailid2 = '".$deductorsplit[3]."' and mobilenumber = '".$deductorsplit[4]."' limit 1";
		$result = runmysqlquery($query);
		if(mysql_num_rows($result) > 0)
		{
			$fetch = mysql_fetch_array($result);
			$currentslno = $fetch['slno'];
		}
		
		//If there is no existing record, insert the new
		if($currentslno == '')
		{
			$query = "insert into tds_flashnews_deductorinfo (customerid, deductorname, tannumber, emailid1, emailid2, mobilenumber, createddate, createdip)values('".$validatedcustomerid."', '".$deductorsplit[0]."', '".$deductorsplit[1]."', '".$deductorsplit[2]."', '".$deductorsplit[3]."', '".$deductorsplit[4]."', '".date('Y-m-d').' '.date('H:i:s')."', '".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query);
		}
		//If there is an existing record, update the date and ip
		else
		{
			$query = "update tds_flashnews_deductorinfo set createddate = '".date('Y-m-d').' '.date('H:i:s')."', createdip = '".$_SERVER['REMOTE_ADDR']."' where slno = '".$currentslno."'";
			$result = runmysqlquery($query);
		}
	}
	else // Called on successful eReturn, either regular or correction
	{
			$ereturndate_received = $deductorsplit[6];
			$ereturndate = substr($ereturndate_received,4,4)."-".substr($ereturndate_received,2,2)."-".substr($ereturndate_received,0,2);			
			$query = "insert into tds_flashnews_deductorinfo (customerid, deductorname, tannumber, emailid1, emailid2, mobilenumber, ereturntype, ereturndate, ereturnfy, ereturnform, ereturnquarter, productstring, createddate, createdip)values('".$validatedcustomerid."', '".$deductorsplit[0]."', '".$deductorsplit[1]."', '".$deductorsplit[2]."', '".$deductorsplit[3]."', '".$deductorsplit[4]."', '".$deductorsplit[5]."', '".$ereturndate."', '".$deductorsplit[7]."', '".$deductorsplit[8]."', '".$deductorsplit[9]."', '".$deductorsplit[10]."', '".date('Y-m-d').' '.date('H:i:s')."', '".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query);
	}
}
//check for read/unread messages 

/*---------------Branch wise support details------------------*/
if($customerid == '' || mysql_num_rows($resultcustomermaster) == 0)
{
	$supportemail = "support@relyonsoft.com";
	$supportphone = "080-23002100";
}
else
{
	$fetchcustomer = mysql_fetch_array($resultcustomermaster);
	switch($fetchcustomer['branch'])
	{
		case "1": // BKG-Bangalore
			$supportemail = "support@relyonsoft.com";
			$supportphone = "080-23002100";
			break;
		case "3": // CSD-Ahmedabad
			$supportemail = "tax.ahmedabad@relyonsoft.com";
			$supportphone = "079-64500174";
			break;
		case "4": // CSD-Bangalore
			$supportemail = "support@relyonsoft.com";
			$supportphone = "080-23002100";
			break;
		case "5": // CSD-Delhi
			$supportemail = "tax.delhi@relyonsoft.com";
			$supportphone = "011-64690639";
			break;
		case "6": // CSD-Hyderabad
			$supportemail = "tax.hyderabad@relyonsoft.com";
			$supportphone = "040-64590017";
			break;
		case "7": // CSD-Jaipur
			$supportemail = "tax.jaipur@relyonsoft.com";
			$supportphone = "0141-6452139";
			break;
		case "8": // CSD-Kolkata
			$supportemail = "tax.kolkata@relyonsoft.com";
			$supportphone = "033-64990343";
			break;
		case "9": // CSD-Mumbai
			$supportemail = "tax.mumbai@relyonsoft.com";
			$supportphone = "022-65690010";
			break;
		case "10": // CSD-Pune
			$supportemail = "tax.pune@relyonsoft.com";
			$supportphone = "020-64599023";
			break;
		case "15": // CSD-Chennai
			$supportemail = "tax.chennai@relyonsoft.com";
			$supportphone = "044-64540658";
			break;
		case "16": // BKM-Bangalore
			$supportemail = "support@relyonsoft.com";
			$supportphone = "080-23002100";
			break;
	}
}

/*---------------------------------*/


?>