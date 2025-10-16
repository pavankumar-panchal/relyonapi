<?php
//echo "hi";
include('functions/phpfunctions.php');
$rcidata = decodevalue($_GET['rj8kb5ns']);
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

if($customerid <> '' && $customerid <> 'Bank')
{
	$customeridlen = strlen($customerid);
	if($customeridlen == 20)
		$validatedcustomerid = cusidsplit($customerid);
	else
		$validatedcustomerid = $customerid;
		
	/*$query = "select * from inv_mas_customer where customerid = '".$validatedcustomerid."';";
	$result  = runmysqlquery($query);
	if(mysql_num_rows($result) == 0)
	{
		echo("INVALIDCUSTOMERID");
		exit;
	}*/
}
else
{
	$validatedcustomerid = $customerid;
}


//Check if any record exists for given combination
$query1 = "select * from inv_logs_webservices where customerid = '".$validatedcustomerid."' and registeredname = '".$registeredname."' and pinnumber = '".$pinnumber."' and computerid = '".$computerid."' and productversion = '".$productversion."' and operatingsystem = '".$operatingsystem."' and processor = '".$processor."' and servicename = 'PRODUCT-UPDATE' limit 1";
$result1 = runquery_logs($query1);
if(mysql_num_rows($result1) > 0)
{
	$fetch1 = mysql_fetch_array($result1);
	$currentlogslno = $fetch1['slno'];
}

if($currentlogslno == '')
{
  //Insert the data to iMax webservices table with proper separations
  $query = "Insert into inv_logs_webservices(customerid,registeredname,pinnumber,computerid,productversion,operatingsystem,processor,`date`,ip,servicename) values('".$validatedcustomerid."','".$registeredname."','".$pinnumber."','".$computerid."','".$productversion."','".$operatingsystem."','".$processor."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','PRODUCT-UPDATE')";
  $result = runquery_logs($query);
}
else
{
	$query = "update inv_logs_webservices set `date` = '".date('Y-m-d').' '.date('H:i:s')."', ip = '".$_SERVER['REMOTE_ADDR']."' where slno = '".$currentlogslno."'";
	$result = runquery_logs($query);
}

echo('<?xml version="1.0" encoding="utf-8" ?>');
echo("\n");
echo('<Updates>');
echo("\n");
$query = "SELECT distinct prdcode, product FROM prdupdate";
$result = runqueryuserlogin2_old($query);
while($fetch = mysql_fetch_array($result))
{
	//$fetch['prdcode'] = '653';
	echo("\t");
	echo('<Product NAME="'.$fetch['product'].'" CODE="'.$fetch['prdcode'].'">');
	echo("\n");

	$query2 = "SELECT * FROM prdupdate where prdcode = '".$fetch['prdcode']."' and updatetype = 'versionupdate'";
	$result2 = runqueryuserlogin2_old($query2);
	while($fetch2 = mysql_fetch_array($result2))
	{
		$slno = $fetch2['slno'];
		$product = $fetch2['product'];
		$prdcode = $fetch2['prdcode'];
		$patchversion = $fetch2['patchversion'];
		$size = $fetch2['size'];
		$reldate = $fetch2['reldate'];
		$verfrom = $fetch2['verfrom'];
		$patchurl = $fetch2['patchurl'];
		
		echo("\t\t");
		echo('<PRDVersion VERSION="'.$verfrom.'">');
		echo("\n\t\t\t");
		echo('<UPDATE SIZE="'.$size.'" DATE="'.changedateformat($reldate).'" VERSION="'.$patchversion.'">'.$patchurl.'</UPDATE>');
		echo("\n\t\t");
		echo('</PRDVersion>');
		echo("\n");
	}
	$query12 = "SELECT max(patchversion) as maxversion FROM prdupdate where prdcode = '".$fetch['prdcode']."';";
	$fetch12 = runqueryfetchuserlogin2_old($query12);
	
	$query11 = "SELECT * FROM prdupdate where prdcode = '".$fetch['prdcode']."' and updatetype = 'hotfix' and patchversion ='".$fetch12['maxversion']."' ";
	$result11 = runqueryuserlogin2_old($query11);
	if(mysql_num_rows($result11) > 0)
	{
		
	  //$fetch12 = mysql_fetch_array($result12);
	  $query13 = "SELECT max(hotfixno) as hotfix FROM prdupdate where patchversion = '".$fetch12['maxversion']."' and prdcode = '".$fetch['prdcode']."' ;";
	  $result13 = runqueryuserlogin2_old($query13);
	  if(mysql_num_rows($result13) > 0)
	  {
		$fetch13 = mysql_fetch_array($result13);
		$query1 = "SELECT patchurl as latestpatchurl, reldate as hotfixreldate, size as hotfixsize FROM prdupdate where hotfixno = '".$fetch13['hotfix']."' and  patchversion = '".$fetch12['maxversion']."' and prdcode = '".$fetch['prdcode']."';";
		$result1 = runqueryuserlogin2_old($query1);
		if(mysql_num_rows($result1) > 0)
		{
			$fetch1 = mysql_fetch_array($result1);
			$maxversion = $fetch12['maxversion'];
			$hotfix = $fetch13['hotfix'];
			$latestpatchurl = $fetch1['latestpatchurl'];
			$hotfixreldate = $fetch1['hotfixreldate'];
			$hotfixsize = $fetch1['hotfixsize'];
			
			echo("\t\t");
			echo('<PRDVersion VERSION="'.$maxversion.'">');
			echo("\n\t\t\t");
			echo('<UPDATE SIZE="'.$hotfixsize.'" DATE="'.changedateformat($hotfixreldate).'" VERSION="'.$maxversion.'" HOTFIX="'.$hotfix.'">'.$latestpatchurl.'</UPDATE>');
			echo("\n\t\t");
			echo('</PRDVersion>');
			echo("\n");
			
		}
	  }
	}
	echo("</Product>");
	echo("\n");
}
echo("</Updates>");
echo("\n");
?>
