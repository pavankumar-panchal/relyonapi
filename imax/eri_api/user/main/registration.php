<?php
	include('../functions/phpfunctions.php');
	include "../aes/AESEncryption.php";
	
	$offset = 19800;
	
	
	if(isset($_POST["REQTYPE"]))
	{
		$REQTYPE= AES_RLN_DENCRYPT($_POST["REQTYPE"]);
		if ($REQTYPE == "1001")
		{
			$connect = isRegistrationV2Connected("Y-m-d",5.5,time());
			
			if (strlen($connect) == 0)
			{
				$connect = "Connecting...";
			}
			echo $connect;
			exit;
		}
		else
		{
			if ($REQTYPE == "1002" || $REQTYPE == "1003" || $REQTYPE == "1004")
			{
				if(isset($_POST["DWP"]))
				{
					$DWP= AES_RLN_DENCRYPT($_POST["DWP"]);
				}
				else
				{
					echo WriteError("Invalid Password.");
					exit;
				}
			}
			if ($REQTYPE == "1003" || $REQTYPE == "1004")
			{
				if(isset($_POST["UNAME"]))
				{
					$UNAME = AES_RLN_DENCRYPT($_POST["UNAME"]);
				}
				else
				{
					echo WriteError("Invalid user name.");
					exit;
				}
			}
			else
			{
				if(isset($_POST["CUSTID"]))
				{
					$CUSTID= AES_RLN_DENCRYPT($_POST["CUSTID"]);
					$custrefno=substr($CUSTID,15,5);
					$query = "SELECT *,AES_DECRYPT(loginpassword,'imaxpasswordkey') as loginpassword FROM inv_mas_customer WHERE slno = '".$custrefno."'";
					$result = runmysqlquery($query);
					if(mysql_num_rows($result) == 0)
					{
						echo WriteError("Invalid Customer ID.");
						exit;
					}
					else
					{
						$fetch = runmysqlqueryfetch($query);
						$passwd = $fetch['loginpassword'];
						$disablelogin = $fetch['disablelogin'];
						if($disablelogin == 'yes')
						{
							echo WriteError("Login is disabled");
							exit;
						}
					}
				}
				else
				{
					echo WriteError("Invalid Customer ID.");
					exit;
				}
			}
		}
		
		if ($REQTYPE == "1002")
		{			
		   if ($passwd != $DWP)
		   {
			   echo WriteError("Invalid Password.");
			   exit;
		   }
			
			$query1 ="INSERT INTO inv_logs_login (userid,`date`,`time`,`type`,system,device,browser) VALUES('".$custrefno."','".date('Y-m-d')."','".date('h:i:s')."','customer_login','".$_SERVER['REMOTE_ADDR']."','DESKTOP','".$_SERVER['HTTP_USER_AGENT']."')";
			
			$result = runmysqlquery($query1);

			$output=$output .  "<ROOT>";
					$output=$output .  "<LICENSE>";   
					$output=$output .  "<UDCID>". AES_RLN_ENCRYPT($custrefno)."</UDCID>";
					$output=$output .  "</LICENSE>";
					$output=$output .  "</ROOT>";
				echo $output;
		   	exit;
		}
		
		if ($REQTYPE == "1003")
		{			
			$query = "SELECT slno,AES_DECRYPT(loginpassword,'imaxpasswordkey') as loginpassword,disablelogin FROM inv_mas_dealer WHERE dealerusername = '".$UNAME."'";
			$result = runmysqlquery($query);
			if(mysql_num_rows($result) > 0)
			{
				$fetch = runmysqlqueryfetch($query);
				
				$user = $fetch['slno']; 
				$passwd = $fetch['loginpassword'];
				$disablelogin = $fetch['disablelogin'];
				if($disablelogin == 'no')
				{ 
					if ($passwd != $DWP)
					{
						echo WriteError("Invalid Password.");
						exit;
					}
					else
					{										
						$query1 ="INSERT INTO inv_logs_login(userid,`date`,`time`,`type`,system,device,browser) VALUES('".$user."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','dealer_login','".$_SERVER['REMOTE_ADDR']."','DESKTOP','".$_SERVER['HTTP_USER_AGENT']."')";
						$result = runmysqlquery($query1);
						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$user."','".$_SERVER['REMOTE_ADDR']."','112','".date('Y-m-d').' '.date('H:i:s')."')";
						$eventresult = runmysqlquery($eventquery);
						
						$output=$output .  "<ROOT>";
						$output=$output .  "<LICENSE>";   
						$output=$output .  "<UDCID>". AES_RLN_ENCRYPT($user)."</UDCID>";
						$output=$output .  "</LICENSE>";
						$output=$output .  "</ROOT>";
						echo $output;
			   			exit;
					}
				}
				else
				{
					echo WriteError("Login is disabled");
					exit;
				}
			}
			else
			{
				echo WriteError("Invalid user name.");
				exit;
			}
		}
		
		if ($REQTYPE == "1004")
		{			
			$query = "SELECT *,AES_DECRYPT(loginpassword,'imaxpasswordkey') as loginpassword  FROM inv_mas_users WHERE username = '".$UNAME."' and disablelogin = 'no'";
			$result = runmysqlquery($query);
			if(mysql_num_rows($result) > 0)
			{
				$fetch = runmysqlqueryfetch($query);
					
				$user = $fetch['slno'];
				$passwd = $fetch['loginpassword'];
				//check login
				//if login successful
				if ($passwd != $DWP)
				{
					echo WriteError("Invalid Password.");
					exit;
				}
				else
				{					
					$query1 ="INSERT INTO inv_logs_login(userid,`date`,`time`,`type`,system,device,browser) VALUES('".$user."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','user_login','".$_SERVER['REMOTE_ADDR']."','DESKTOP','".$_SERVER['HTTP_USER_AGENT']."')";
					$result = runmysqlquery($query1);
							
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','1','".date('Y-m-d').' '.date('H:i:s')."')";
					$eventresult = runmysqlquery($eventquery);
					
					$output=$output .  "<ROOT>";
					$output=$output .  "<LICENSE>";   
					$output=$output .  "<UDCID>". AES_RLN_ENCRYPT($user)."</UDCID>";
					$output=$output .  "</LICENSE>";
					$output=$output .  "</ROOT>";
					echo $output;
			   		exit;
				}
			}
			else
			{
				echo WriteError("Either invalid user name Or Login is disabled");
				exit;
			}
		}
	}

	if(isset($_POST["HDDID"]))
	{
		$HDDID= AES_RLN_DENCRYPT($_POST["HDDID"]);
	}
	if(isset($_POST["ETHID"]))
	{
		$ETHID= AES_RLN_DENCRYPT($_POST["ETHID"]);
	}
	
	if(isset($_POST["PIN"]))
	{
		$PIN= AES_RLN_DENCRYPT($_POST["PIN"]);
	}
	else
	{
		echo WriteError("Invalid PIN");
		exit;
	}
	
	if(isset($_POST["PID"]))
	{
		$PID= AES_RLN_DENCRYPT($_POST["PID"]);
	}
	else
	{
		echo WriteError("Invalid product category");
		exit;
	}
	
	if(isset($_POST["REGTYPE"]))
	{
		$REGTYPE= AES_RLN_DENCRYPT($_POST["REGTYPE"]);
	}
	
	if(isset($_POST["UDCID"]))
	{
		$UDCID = AES_RLN_DENCRYPT($_POST["UDCID"]);
	}
	else
	{
		echo WriteError("Invalid user");
		exit;
	}
	
	if(isset($_POST["CMPNAME"]))
	{
		$CMPNAME= AES_RLN_DENCRYPT($_POST["CMPNAME"]);
	}
	
	if(isset($_POST["CMPID"]))
	{
		$CMPID= AES_RLN_DENCRYPT($_POST["CMPID"]);
	}
	
	if(isset($_POST["LGNTYPE"]))
	{
		$LGNTYPE= AES_RLN_DENCRYPT($_POST["LGNTYPE"]);
	}
	
	/*$q1 = "select * from inv_customerproduct where (computerid = '35300' )";//HDDID <> '' and ETHID <> '' AND HDDID = 'WD-WMAYUF063559'
	$r1 = runmysqlquery($q1);
		while($f1 = mysql_fetch_array($r1))
		print_r($f1);
					exit;*/
							
	
   
/*	echo WriteError($REGTYPE."-".$REQTYPE);
		exit;
*/	$custname="";
	$date="";
	
	//$customerid=substr($CUSTID,0,4)."-".substr($CUSTID,4,4)."-".substr($CUSTID,8,4)."-".substr($CUSTID,12,5);
	
	$ppid=substr($PID,0,3);
	$utype=substr($PID,3,2);
	
	
	$usagetype1="";//to check in inv_invoicenumbers
	$usagetype2="";//to check in inv_dealercard
	if($utype=="00")
	{
		$usagetype1="Single User";
		$usagetype2="singleuser";
	}
	else if($utype=="09")
	{
		$usagetype1="Multi User";
		$usagetype2="multiuser";
	}
	
	$cardid=="";
	$valid1="false";
	$valid2="false";
	$pinvalid="false";
	$custvalid="false";
/*	$q1 = "select  scratchnumber FROM inv_mas_scratchcard where cardid='104661'";
	$r1 = runmysqlquery($q1);
	while($f1=mysql_fetch_array($r1)){echo $f1[0];}exit;
*/	
$q1 = "select cardid from inv_mas_scratchcard where scratchnumber='".$PIN."' and attached = 'yes' and cancelled = 'no' and blocked = 'no'";
	$r1 = runmysqlquery($q1);
	$c1 = mysql_num_rows($r1);
	
	if($c1 == 1)
	{
		while($f1=mysql_fetch_array($r1))
		{
			$cardid=$f1['cardid'];
			$pinvalid="true";
		}
	}
	
	if($pinvalid=="true")
	{
		$q2="select * from inv_invoicenumbers where customerid='".$CUSTID."' order by slno";
		$r2 = runmysqlquery($q2);
		$c2 = mysql_num_rows($r2);
		
		if($c2 == 0)
		{
			$q2="select * from inv_invoicenumbers_dummy_regv2 where customerid='".$CUSTID."' order by slno";
			$r2 = runmysqlquery($q2);
			$c2 = mysql_num_rows($r2);
		}
		
		if($c2>0)
		{
			$custvalid="true";
			while($f2=mysql_fetch_array($r2))
			{
				if($valid1=="false")
				{
					$arr = explode("*", $f2['description']);
					foreach ($arr as $value)
					{
						$i=0;
						$subarr=explode("$", $value);
						if($cardid==$subarr[5] && $PIN==$subarr[4] && $usagetype1==$subarr[3])
						{
							$valid1="true";
							$q3="select * from inv_dealercard where cardid='".$cardid."' and productcode=".$ppid." and usagetype='".$usagetype2."'";
							
							if ($LGNTYPE == "2")
							{
								$q3 = $q3 ." AND DEALERID = ".$UDCID;
							}
							
							$r3 = runmysqlquery($q3);
							$c3 = mysql_num_rows($r3);
							if($c3==1)
							{
								$valid2="true";
								break;
							}
						}
					}
				}
			}
		}
		else
		{
			$custvalid="false";
		}
	}  
	
	$output="";
	
	if($valid1=="true" && $valid2=="true")//Regitration status check
	{ 
		$customerproductslno = 0;
		$q4="select slno from inv_customerproduct where cardid='".$cardid."' and customerreference='".$custrefno."' and computerid = '".$PID."'"; 
		$r4 = runmysqlquery($q4);
		while($f4 = mysql_fetch_array($r4)){
		$customerproductslno = $f4['slno'];}
		/*$q41="select * from inv_customerproduct where cardid='".$cardid."' and customerreference='".$custrefno."' and computerid = '".$PID."'";
		echo  $q41;
		exit;
		$r41 = runmysqlquery($q41);
		while($f41 = mysql_fetch_array($r41))
		print_r($f41);
		exit;*/
		if($REQTYPE == "1" || $REQTYPE == "3")
		{
			if($customerproductslno == 0)
			{
				$q5 = "SELECT (MAX(slno) + 1) AS newslno FROM inv_customerproduct";
				$r5 = runmysqlquery($q5);
				$f5 = mysql_fetch_array($r5);
				$customerproductslno = $f5['newslno'];
				$q6 = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module,purchasetype,HDDID,ETHID,REGTYPE,COMPUTERNAME,COMPUTERIP,CREATEDBY,AUTOREGISTRATIONYN,ACTIVELICENSE) VALUES('".$customerproductslno."','".$custrefno."','".$cardid."','".$PID."','','','','',(SELECT dealerid from inv_dealercard where cardid = '".$cardid."'),'2','Web','".gmdate("Y-m-d", time()+$offset)."','".gmdate("H:i:s", time()+$offset)."','','no','','user_module',(SELECT purchasetype from inv_dealercard where cardid = '".$cardid."'),'',''," .$REGTYPE. ",'".$CMPNAME."','".$CMPID."',".$UDCID.",'Y','1');";
				$r6 = runmysqlquery($q6);	
			}
			else if ($customerproductslno > 0)
			{
				if ($REGTYPE == "2")
				{
					$q6 = "select REGTYPE FROM inv_customerproduct Where slno = ". $customerproductslno;
					$r6 = runmysqlquery($q6);	
					while($f6 = mysql_fetch_array($r6))
					{
						$regtypeReturned = $f6['REGTYPE'];
					}
					
					if ($regtypeReturned == "1")
					{
						$output=WriteError("Already registered once with 'Online' facility, so further 'Offline' regsitration not supported");
						echo $output;
						exit;
					}
				}
				$q6 = "select ACTIVELICENSE FROM inv_customerproduct Where slno = ". $customerproductslno;
				$r6 = runmysqlquery($q6);	
				while($f6 = mysql_fetch_array($r6))
				{
					$activeLicense = $f6['ACTIVELICENSE'];
				}
				
				if ($activeLicense == "0")
				{
					$output=WriteError("Product License is de-activated. Please contact vendor",8001);
					echo $output;
					exit;
				}
			}
			
			if($REQTYPE == "1")
			{
				if (strlen($HDDID) > 0)
				{
					$q7 = "UPDATE inv_customerproduct SET HDDID = '" .$HDDID."',COMPUTERNAME = '".$CMPNAME."', COMPUTERIP = '".$CMPID."', CREATEDBY = " .$UDCID. " Where (HDDID = '' OR HDDID is null) And slno = ". $customerproductslno;
					$r7 = runmysqlquery($q7);
				}
				if (strlen($ETHID) > 0)
				{
					$q8 = "UPDATE inv_customerproduct SET ETHID = '" .$ETHID."',COMPUTERNAME = '".$CMPNAME."', COMPUTERIP = '".$CMPID."', CREATEDBY = " .$UDCID. " Where (ETHID = '' OR ETHID is null) And slno = ". $customerproductslno;
					if (strlen($HDDID) > 0)
					{
						$q8 = $q8. " And HDDID = '" .$HDDID."'";
					}
					$r8 = runmysqlquery($q8);
				}
			}
		}
		
		
		//$q10="select * from inv_customerproduct where slno=".$customerproductslno." and (HDDID ='".$HDDID."' OR ETHID = '".$ETHID."')" ;
		
		//$q10="select slno from inv_customerproduct where cardid='".$cardid."' and customerreference='".$custrefno."' and computerid = '".$PID."'";
		//$q10="select * from inv_invoicenumbers where customerid='".$CUSTID."' AND order by slno";
		/*$r10 = runmysqlquery($q10);
		while($f10 = mysql_fetch_array($r10))
		print_r($f10);
		$output=WriteError($customerproductslno."-".$HDDID."-".$ETHID);
		echo $output;
			exit;*/
		//exit;
		$q10="select * from inv_customerproduct where slno=".$customerproductslno." and (HDDID ='".$HDDID."' OR ETHID = '".$ETHID."')" ;
		$r10 = runmysqlquery($q10);
		$c10 = mysql_num_rows($r10);
		if($c10 == 0)
		{
			if($REQTYPE == "1" || $REQTYPE == "3")
			{
				$output=WriteError("This PIN is already in use for some other computer system Or Product is already surrendered/cancelled on the current computer system");
			}
			else if($REQTYPE=="2")
			{
				$output=WriteError("Registration details are invalid so can not surrender");
			}
			echo $output;
			exit;
		}
		else
		{
			$query5 = "select Distinct(businessname) from inv_invoicenumbers where customerid='".$CUSTID."'";
			$result5 = runmysqlquery($query5);	
			$fetch5 = mysql_fetch_array($result5);
			$custname=$fetch5['businessname'];	
				
			if($REQTYPE == "1" || $REQTYPE == "3")
			{
				if($REQTYPE == "1")
				{
					$q9 = "UPDATE inv_customerproduct SET REGTYPE = ".$REGTYPE. " Where REGTYPE <> ".$REGTYPE. " AND REGTYPE <> 2 And slno = ". $customerproductslno;
					$r9 = runmysqlquery($q9);
				}
					
				$query11 = "select HDDID, ETHID, computerid, REGTYPE FROM inv_customerproduct Where slno = ". $customerproductslno;
				$result11 = runmysqlquery($query11);	
				while($fetch11 = mysql_fetch_array($result11))
				{
					$HDDIDReturned = $fetch11['HDDID'];
					$ETHIDReturned = $fetch11['ETHID'];
					$computeridReturned = $fetch11['computerid'];
					$regtypeReturned = $fetch11['REGTYPE'];
				}

				$output=$output .  "<ROOT>";
				$output=$output .  "<LICENSE>";   
				$output=$output .  "<HDDID>". AES_RLN_ENCRYPT($HDDIDReturned)."</HDDID>";
				$output=$output .  "<ETHID>". AES_RLN_ENCRYPT($ETHIDReturned)."</ETHID>";
				$output=$output .  "<CUSTID>". AES_RLN_ENCRYPT($CUSTID)."</CUSTID>";
				$output=$output .  "<CUSTNAME>".AES_RLN_ENCRYPT($custname)."</CUSTNAME>";
				$output=$output .  "<PIN>". AES_RLN_ENCRYPT($PIN)."</PIN>";
				$output=$output .  "<PID>". AES_RLN_ENCRYPT($computeridReturned)."</PID>";
				$output=$output .  "<REGTYPE>". AES_RLN_ENCRYPT($regtypeReturned)."</REGTYPE>";
				$output=$output .  "<DOR>". AES_RLN_ENCRYPT(date("Y-m-d"))."</DOR>";
				$output=$output .  "</LICENSE>";
				$output=$output .  "</ROOT>";
				
			}
			else if($REQTYPE=="2")
			{
				$query11 = "select HDDID, ETHID, computerid, REGTYPE FROM inv_customerproduct Where slno = ". $customerproductslno;
				$result11 = runmysqlquery($query11);
					
				while($fetch11 = mysql_fetch_array($result11))
				{
					$HDDIDReturned = $fetch11['HDDID'];
						$ETHIDReturned = $fetch11['ETHID'];
						$computeridReturned = $fetch11['computerid'];
					$regtypeReturned = $fetch11['REGTYPE'];
				}
				
				if ($regtypeReturned == "1")
				{			
					$q10 = "INSERT INTO inv_surrenderproduct(refslno,HDDID,ETHID,surrendertime,networkip,systemip) VALUES('".$customerproductslno."','".$HDDIDReturned."','".$ETHIDReturned."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['SERVER_ADDR']."');";
				$r10 = runmysqlquery($q10);
				
					$q12 = "UPDATE inv_customerproduct SET HDDID = '',ETHID = '' Where slno = ". $customerproductslno;
					$r12 = runmysqlquery($q12);
					
					$query11 = "select HDDID, ETHID, computerid FROM inv_customerproduct Where slno = ". $customerproductslno;
					$result11 = runmysqlquery($query11);
						
					while($fetch11 = mysql_fetch_array($result11))
					{
						$HDDIDReturned = $fetch11['HDDID'];
						$ETHIDReturned = $fetch11['ETHID'];
						$computeridReturned = $fetch11['computerid'];
					}
					
				
					
					
					
					$output=$output .  "<ROOT>";
					$output=$output .  "<LICENSE>";   
					$output=$output .  "<HDDID>". AES_RLN_ENCRYPT($HDDIDReturned)."</HDDID>";
					$output=$output .  "<ETHID>". AES_RLN_ENCRYPT($ETHIDReturned)."</ETHID>";
					$output=$output .  "<CUSTID>". AES_RLN_ENCRYPT($CUSTID)."</CUSTID>";
					$output=$output .  "<CUSTNAME>".AES_RLN_ENCRYPT($custname)."</CUSTNAME>";
					$output=$output .  "<PIN>". AES_RLN_ENCRYPT($PIN)."</PIN>";
					$output=$output .  "<PID>". AES_RLN_ENCRYPT($computeridReturned)."</PID>";
					$output=$output .  "<REGTYPE>". AES_RLN_ENCRYPT($regtypeReturned)."</REGTYPE>";
					$output=$output .  "<DOR>". AES_RLN_ENCRYPT(date("Y-m-d"))."</DOR>";
					$output=$output .  "</LICENSE>";
					$output=$output .  "</ROOT>";
				}
			}
			else
			{
				 echo WriteError("Surrendering is not supported as the product is registered in 'Offline' mode.");
				 exit;
			}
		}
	}
	else
	{
		if($valid1=="true")
		{
			$output = WriteError("Customer ID, PIN and Product category are not matching");
		}
		else if($valid1=="false")
		{
			if($pinvalid=="false")
			{
				$output=WriteError("Invalid PIN");
			}
			else if($custvalid=="false")
			{
				$output=WriteError("Wrong Customer ID");
			}
			else
			{
				$output=WriteError("Given details are wrong");
			}
		}
	}
	echo $output;
?>