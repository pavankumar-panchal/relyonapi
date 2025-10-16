<?php
session_start();
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
include('../inc/checksession.php');
include('../inc/checkpermission.php');

$submittype = $_POST['submittype'];

switch($submittype)
{
	
	case "save":
	{
			//////////Collect the form data ////////////////////
			$curpass = $_POST['curpass'];
			$newpass = $_POST['newpass'];
			$name = $_POST['name']; 
			$emailid = $_POST['emailid'];

		
			if($name <> "")
			{
					//check if the record is already present
					$query1 ="SELECT password,name FROM saral_admins WHERE name = '".$name."'";
					$resultfetch1 = runmysqlqueryfetch($query1);
					$curpassword = $resultfetch1['password'];
					$curname = $resultfetch1['name'];
					
					if($curpassword == $curpass)
					{
						if($curpassword <> $newpass)
						{
							$query2 = "UPDATE saral_admins SET password = '".$newpass."' WHERE name = '".$name."'";
							$result2 = runmysqlquery($query2);
							
							$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
							$datetime = gmdate("Y-m-d H:i:s");
							$activity = "Password Changed By : " .$name." New Password : ".$newpass;
							$message = "1^Password Changed successfully. .!!";
							$eventtype = '55';
							
							##inserting into Log##
							audit_trail($userid,$ipaddr,$datetime,$activity,$eventtype);
						}
						else
						{
							$message = "2^New Password Cannot be As Previous Password!!";
						}
					}
					else
					{
						$message = "2^Password Didn't Match. .!!";
					}
			}
			else{$message ="2^unable to connect! contact Admin!!";}
			echo($message);
	}
	break;
	
}
?>