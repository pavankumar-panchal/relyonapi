<?php
//error_reporting(E_ALL);
// echo "hi"; 
include('functions/phpfunctions.php');

$msc=microtime(true);
$offset = 19800;

date_default_timezone_set("Asia/Kolkata");
$ISTDATE = date('Y-m-d');
$ISTTIME = date('H:i:s');

$PIN = $_REQUEST['PINNO']; //scratchnumber
$CUSTID = $_REQUEST['CUSTID']; //15 digits customer id
$REGCODE = $_REQUEST['REGCODE']; //unique code to identify registration
$PRDCODE = $_REQUEST['PRDCODE']; 
//$CMPNAME = $_REQUEST['CMPNAME']; 
//$CMPID = $_REQUEST['CMPID']; 
$status= "";
//$custrefno=substr($CUSTID,-5);

$cardid=="";

if($PIN!= "" && $CUSTID!="" && $REGCODE!="" && $PRDCODE!= "")
{
	$query0  = "select * from inv_mas_scratchcard where scratchnumber = '".trim($PIN)."'"; 
	$result0 = runmysqlquery($query0);
	$countcard = mysql_num_rows($result0);
	if($countcard > 0)
	{
		$fetch0 = runmysqlqueryfetch($query0);
		$cardid = $fetch0['cardid'];

		$custquery = "select slno from inv_mas_customer where customerid = '".$CUSTID."'";
		$custresult= runmysqlquery($custquery);
		$custcount = mysql_num_rows($custresult);
		if($custcount > 0)
		{
			$custfetch = runmysqlqueryfetch($custquery);
			$custrefno = $custfetch['slno'];

			$query1="select * from inv_dealercard where cardid='".$cardid."' and productcode=".$PRDCODE." and customerreference='".$custrefno."'" ;
			$result1 = runmysqlquery($query1);
			$count1 = mysql_num_rows($result1);
			if($count1 == 1)
			{
				$query = "select count(*) as regcount from inv_customerproduct where cardid  = '".$cardid."' and  customerreference = '".$custrefno."'";
				$fetch = runmysqlqueryfetch($query);
				$regcount = $fetch['regcount'];
				if($regcount > 0)
				{
					$regcodequery = "select count(*) as regcount from inv_customerproduct where cardid  = '".$cardid."' and  customerreference = '".$custrefno."' and regcode = '".$REGCODE."'";
					$regcodefetch = runmysqlqueryfetch($regcodequery);
					$regcodecount = $regcodefetch['regcount'];

					if($regcodecount == 1)
					{
						$query1 = "select inv_customerproduct.date as regdate,blocked,cancelled,inv_dealercard.cuscardattacheddate as attachdate,cell,surrenderstatus from inv_customerproduct 
						left join `inv_mas_scratchcard` on `inv_customerproduct`.`cardid` = `inv_mas_scratchcard`.`cardid`
						left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
						left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
						where inv_customerproduct.`cardid` = '".$cardid."'";
						$fetch1 = runmysqlqueryfetch($query1);

						$regdate = date('d-m-Y',strtotime($fetch1['regdate']));
						$attachdate = date('d-m-Y',strtotime($fetch1['attachdate']));
						$cellno = $fetch1['cell'];
						$surrenderstatus = $fetch1['surrenderstatus'];

						if($surrenderstatus == 'yes')
						{
							if($fetch1['blocked']!= 'no')
							{
								$output = 'Card is Surrendered and Blocked';
							}
							else if($fetch1['cancelled']!= 'no')
							{
								$output = 'Card is Surrendered and Cancelled';
							}
							else{	
								$output = 'Card is Surrendered';
							}
						}
						else
						{
							if($fetch1['blocked']!= 'no')
							{
								$output = 'Card is Registered and Blocked';
							}
							else if($fetch1['cancelled']!= 'no')
							{
								$output = 'Card is Registered and Cancelled';
							}
							else{	
								$output = 'Card is Registered';
							}

						}
						
					}
					else {
						$output = 'Registration code is not matching';
					}
				}
				else
				{
				  $query2 = "select *,date as attachdate,cell  from inv_mas_scratchcard 
				   left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
				   left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
				   where inv_mas_scratchcard.cardid = '".$cardid."'";
					$fetch2 = runmysqlqueryfetch($query2);
					$attachdate = date('d-m-Y',strtotime($fetch2['attachdate']));
					$cellno = $fetch2['cell'];
					if($fetch2['blocked']!= 'no')
					{
						 $output = 'Card is Not yet Registered and Blocked';

					}
					else if($fetch2['cancelled']!= 'no')
					{
						$output = 'Card is Not yet Registered and Cancelled';
					}
					else {
						$output = 'Card is Not yet Registered';
					}
				}
			}
			else
			{
				$output = 'Given details are Wrong!';
			}
		}
		else
		{
			$output = 'Customer Id is not matching with Imax Data!';
		}

 	}
	else
		$output = 'PIN NO is not matching with Imax Data.';
}
else
{
	if($PIN == "") 
	{
		$error = "Please provide pin number!";
	}
	else if($CUSTID == "")
	{
		$error = "Please provide Customer Id!";
	}
	else if($REGCODE == "")
	{
		$error = "Please provide Registration Code!";
	}
	else if($PRDCODE == "")
	{
		$error = "Please provide product Code!";
	}
	$status = 1;
}

if($status == 1)
	$registration['errorsmsg'] = $error;
else
	$registration['Registration Status'] = $output;

$registration['Registration Date'] = $regdate;
$registration['Attached Date'] = $attachdate;
//$registration['Cell No'] = $cellno;
echo json_encode($registration);
?>