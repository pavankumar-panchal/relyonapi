<?php
// error_reporting(E_ALL);
 // echo "hi";  
 // exit;
// echo $_REQUEST['CARDID'];
// echo $_REQUEST['CUSTID'];
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
$CMPNAME = $_REQUEST['CMPNAME']; 
$CMPID = $_REQUEST['CMPID']; 

//$custrefno=substr($CUSTID,-5);

$cardid=="";

if($PIN!= "" && $CUSTID!="" && $REGCODE!="" && $PRDCODE!= "" && $CMPNAME!="" && $CMPID!="")
{
	$query = "select cardid from inv_mas_scratchcard where scratchnumber='".$PIN."' and attached = 'yes' and cancelled = 'no' and blocked = 'no'";
	$result = runmysqlquery($query);
	$count = mysql_num_rows($result);

	if($count == 1)
	{
		while($fetch=mysql_fetch_array($result))
		{
			$UDCID = $UNAME =$PID = "";
			$cardid=$fetch['cardid'];

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
					$customerproductslno = 0;
					$query2="select * from inv_customerproduct where cardid='".$cardid."' and customerreference='".$custrefno."'"; 
					$result2 = runmysqlquery($query2);
					while($fetch2 = mysql_fetch_array($result2))
					{
						$customerproductslno = $fetch2['slno'];
						$REGDATE = $fetch2['date'].' '.$fetch2['time'];
						$computernameReturned=$fetch2['COMPUTERNAME'];
						$computeripReturned=$fetch2['COMPUTERIP'];
						$surrenderstatus=$fetch2['surrenderstatus'];
						$regcode=$fetch2['regcode'];
					}

					## REQTYPE "1" : registration, 2: Surrender, 3: Checking Registeration ## 
					## Regtype 1: Online, 2: Offline, 3:HardwareLock ##
					if($customerproductslno > 0)
					{
						if($surrenderstatus!= 'yes')
						{
							if($regcode == $REGCODE)
							{
								$q10 = "INSERT INTO inv_surrenderproduct(refslno,REGDATE,surrendertime,networkip,systemip,REGTYPE,COMPUTERNAME,COMPUTERIP) VALUES('".$customerproductslno."','".$REGDATE."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['SERVER_ADDR']."','1','".$computernameReturned."','". $computeripReturned."');";
								$r10 = runmysqlquery($q10);

								$q12 = "UPDATE inv_customerproduct SET surrenderstatus='yes' Where slno = ". $customerproductslno;
								$r12 = runmysqlquery($q12);

								updateCardStatus($cardid,'no');

								$successmsg = 'Surrendered Successfully!';
								$status = 1;
							}
							else
							{
								$error = 'Registration Code is not matching!';
								$status = 2;
							}
							
						}
						else
						{
							$error = 'Given pin is already surrendered!';
							$status = 2;
						}
						
					}
					else
					{
						$error = 'No details found to surrender!';
						$status = 2;
					}
				}
				else
				{
					$error = 'Given details are Wrong!';
					$status = 2;
				}
			}
			else
			{
				$error = 'Customer Id is not matching with Imax Data!';
				$status = 2;
			}
		}
	}
	else
	{
		$error = "Invalid Pin!";
		$status = 2;
	}
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
	else if($CMPNAME == "")
	{
		$error = "Please provide computer name!";
	}
	else if($CMPID == "")
	{
		$error = "Please provide computer id!";
	}
	$status = 2;
}

	
if($status == 1)
	$registration['successmsg'] = $successmsg;
else
	$registration['errorsmsg'] = $error;
echo json_encode($registration);
?>