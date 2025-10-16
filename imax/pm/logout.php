<?
	include('./functions/phpfunctions.php'); 
	if(imaxgetcookie('userid') <> '' || imaxgetcookie('userid') <> false )
	{
		$userid = imaxgetcookie('userid');
		$eventquery = "Insert into saral_audit(userid,ipaddr,datetime,activity_type,eventtype) 
		values('".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','LOGGED-OUT','45')";
		$eventresult = runmysqlquery($eventquery);
	}
	imaxuserlogout();
	header('Location:./index.php');
?>