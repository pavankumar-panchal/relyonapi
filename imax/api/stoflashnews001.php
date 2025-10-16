<?php

include('functions/phpfunctions.php');

$rcidata = decodevalue($_GET['rj8kb5ns']);
$accessdate = date("Y-m-d");
$accessip = getenv("REMOTE_ADDR");
$accesstime = date("H:i");

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
	echo('INVALIDREQUEST');exit;
}

//Validate customer ID with iMax database and Respond in case of invalid (also exit)
if($customerid <> '')
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

//Check if any record exists for given combination
$query1 = "select * from inv_logs_webservices where customerid = '".$validatedcustomerid."' and registeredname = '".$registeredname."' and pinnumber = '".$pinnumber."' and computerid = '".$computerid."' and productversion = '".$productversion."' and operatingsystem = '".$operatingsystem."' and processor = '".$processor."' and servicename = 'STO-FLASH-NEWS' limit 1";
$result1 = runquery_logs($query1);
if(mysql_num_rows($result1) > 0)
{
	$fetch1 = mysql_fetch_array($result1);
	$currentlogslno = $fetch1['slno'];
}

if($currentlogslno == '')
{
  //Insert the data to iMax webservices table with proper separations
  $query = "Insert into inv_logs_webservices(customerid,registeredname,pinnumber,computerid,productversion,operatingsystem,processor,`date`,ip,servicename) values('".$validatedcustomerid."','".$registeredname."','".$pinnumber."','".$computerid."','".$productversion."','".$operatingsystem."','".$processor."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','STO-FLASH-NEWS')";
  $result = runquery_logs($query);
}
else
{
	$query = "update inv_logs_webservices set `date` = '".date('Y-m-d').' '.date('H:i:s')."', ip = '".$_SERVER['REMOTE_ADDR']."' where slno = '".$currentlogslno."'";
	$result = runquery_logs($query);
}


/*$query = "insert into `desktopflashnews` (accessdate, accessip, accesstime)values('".$accessdate."', '".$accessip."', '".$accesstime."')";
$result = runmysqlquerystoflash($query);*/


$grid = '';
$query1 = "select * from saral_flashnews where (product='Saral TaxOffice') and (validtill > CURDATE() or validtill is null or validtill = '' or validtill = '0000-00-00' ) and (`disable` = 'no')  order by adddeddate desc;";
#$result1 = runmysqlquerystoflash($query1);
$result1 = runmysqlqueryflashnews($query1);

$i_n = 0;
while($fetch = mysql_fetch_array($result1))
{
	if($i_n%2 == 0)
		$color = "#FF0000";
	else
		$color = "#0066FF";
	$link = $fetch['link'];
	$text = $fetch['text'];

	if($link <> '#')
	{
		$linktext = 'href='.$link;
	}
	else
	{
		$linktext = '';
		$text = 'INFO: '.$text;
	}
	
	$title = $fetch['title'];
	
	//Give Top numbering and border
	$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="20" align="center" bgcolor="#006699" style="border-top:1px solid #006699; color:#FFFFFF">'.($i_n+1).'</td><td style="border-top:1px solid #006699">&nbsp;</td></tr></table>';
	//Give the Flash text with link and title
	$grid .= '<a '.$linktext.' target="_blank"  title="'.$title.'"><font color="'.$color.'">'.$text.'</font></a><br /><br />';
	$i_n++;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flashnews for Desktop STO</title>
<style>
body {
	margin:0;
	padding:0;
	/*background:url(../images/sto-desktop-bck.gif) #FFFFFF no-repeat;*/
	font-family:Tahoma, Arial, Helvetica, sans-serif;
	font-size:70.5%;
	line-height:normal;
	color:#FFFFFF;
}/*#D6E6FE*/
a {
	text-decoration:none;
	color:#000000;
}
a:hover {
	text-decoration:underline;
	color:#FF6600;
}
</style>
<script type="text/javascript" src="stoflashnews001.js"></script> 
</head>
<body >
<div id="flashnews"><div>
<div style="padding:2px" align="justify"><?php echo($grid);?><br />
</div></div></div>
</body>
</html>