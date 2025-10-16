<?php
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

//ob_start("ob_gzhandler");
$switchtype = $_POST['switchtype'];
switch($switchtype)
{
	case 'save':
	{
		$form_adminid = $_POST['form_adminid'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$login = $_POST['login'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$reg_form = $_POST['reg_form'] == "true" ? 1 : 0;
		$prd_master = $_POST['prd_master'] == "true" ? 1 : 0;
		$ver_update = $_POST['ver_update'] == "true" ? 1 : 0;
		$hot_update = $_POST['hot_update'] == "true" ? 1 : 0;
		$flash_news = $_POST['flash_news'] == "true" ? 1 : 0;
		$grp_head = $_POST['grp_head'] == "true" ? 1 : 0; 
		$main_prod = $_POST['main_prod'] == "true" ? 1 : 0;
		$job_req = $_POST['job_req'] == "true" ? 1 : 0;
		
		$mail_active = $_POST['mail_active'] == "true" ? 1 : 0;
		$mail_save = $_POST['mail_save'] == "true" ? 1 : 0;
		$mail_disable = $_POST['mail_disable'] == "true" ? 1 : 0;
		$mail_delete = $_POST['mail_delete'] == "true" ? 1 : 0;
		$mail_search = $_POST['mail_search'] == "true" ? 1 : 0;
		$reset_password = $_POST['reset_password'] == "true" ? 1 : 0;
		$mail_forward = $_POST['mail_forward'] == "true" ? 1 : 0;
		
		if($form_adminid == '')
		{
			$query = "select count(*) as usercount  from saral_admins where (email = '".$email."' or name = '".$login."')";
			$resultfetch = runmysqlqueryfetch($query);
			$usercount = $resultfetch['usercount'];
			//VALIDATE FOR EMAILID AND USERNAME
			if($usercount > 0)
			{	
				$responsearray1 = array();
				$responsearray1['errorcode'] = '3';
				$responsearray1['errormessage'] = "User Account Already Available!";
				//echo "3^Employee Email ID Already in use";
			}
			else
			{
							
				$query1 = "INSERT INTO saral_admins(fname,lname,name,password,email,register_user,productmaster,
				versionupdate,hotfixupdate,flashnewsupdate,grouphead,career, main_product,saralmail,
				saralmail_save,saralmail_disable,saralmail_delete,saralmail_search,saralmail_resetpass,saralmail_forward) 
				VALUES('".$fname."','".$lname."','".$login."','".$password."','".$email."','".$reg_form."',
				'".$prd_master."','".$ver_update."','".$hot_update."','".$flash_news."',
				'".$grp_head."','".$job_req."','".$main_prod."','".$mail_active."','".$mail_save."','".$mail_disable."'
				,'".$mail_delete."','".$mail_search."','".$reset_password."' , '".$mail_forward."');";
				$result1 = runmysqlquery($query1);
				
				
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Registration For First Name : " .$fname. ", Last Name : " .$lname. ", 
				Username: " .$login. ", Password: " .$password. ", Email ID :" .$email.", 
				Previlige: Register Access :".$reg_form.", Prd Master :".$prd_master. " ,
				Version Update: " .$ver_update." , HotFix Update: " .$hot_update. ", Flash News: " .$flash_news. " , 
				Grouphead: " .$grp_head." , Career: " .$job_req." , 
				Main_product: " .$main_prod." , Mail_Active: " .$mail_active." ,Mail_Save: " .$mail_save." ,
				Mail_Disable: " .$mail_disable." ,Main_Delete: " .$mail_delete." ,Main_Search: " .$mail_search." ,
				Reset Password: " .$reset_password. " , Mail_forward = ".$mail_forward ;
				
				$eventtype = '15';
				
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);

				$responsearray1 = array();
				$responsearray1['errorcode'] = "1";
				$responsearray1['errormessage'] = "New Registration Created Successfully";
			}
			
		}
		else
		{
			$query2 = "update saral_admins set fname = '".$fname."', lname = '".$lname."', name = '".$login."',                       
			password = '".$password."', email = '".$email."', register_user =  '".$reg_form."',
			productmaster = '".$prd_master."' , versionupdate = '".$ver_update."' , hotfixupdate = '".$hot_update."'
			, flashnewsupdate = '".$flash_news."'  ,	main_product = '".$main_prod."' , grouphead = '".$grp_head."' ,
			career = '".$job_req."' ,saralmail = '".$mail_active."' ,saralmail_save = '".$mail_save."' ,
			saralmail_disable = '".$mail_disable."' ,saralmail_delete = '".$mail_delete."' ,
			saralmail_search = '".$mail_search."' ,saralmail_resetpass = '".$reset_password."' , 
			saralmail_forward = '".$mail_forward."'  where adminid = '".$form_adminid."'";
			$result2 = runmysqlquery($query2);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Register User Updated for ".$form_adminid;
			$eventtype = '16';
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
			
			$responsearray1 = array();
			$responsearray1['errorcode'] = "1";
			$responsearray1['errormessage'] = "User Account Record updated  Successfully";
		}
		echo(json_encode($responsearray1));
		
	}
	break;

	case 'delete':
	{
		$form_adminid = $_POST['form_adminid'];
		$query = "delete from saral_admins  WHERE adminid = '".$form_adminid."'";
		$result2 = runmysqlquery($query);
		$msg = "Employee Email ID deleted Successfully";
		
		$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
		$datetime = gmdate("Y-m-d H:i:s");
		$activity = "Register User Deleted  ".$form_adminid;
		$eventtype = '18';
		audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);

		$responsearray1['errorcode'] = '2';
		$responsearray1['errormessage'] = "User Account Record deleted Successfully!!";
		echo(json_encode($responsearray1));
	}		
	break;
	
	case 'generateuserlist':
	{
		$query = "SELECT adminid,email,fname FROM saral_admins";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 1;
		while($fetch = mysql_fetch_array($result))
		{
			if($count > 1)
			$grid .='^*^';
			$grid .= $fetch['fname'].'^'.$fetch['adminid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'getusercount':
	{
		$responsearray3 = array();
		$query = "SELECT adminid,email,fname FROM saral_admins";
		$result = runmysqlquery($query);
		$count = mysql_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	
	case 'userdetailstoform':
	{
		$form_adminid = $_POST['form_adminid'];

		$query = "SELECT * from saral_admins where adminid = '".$form_adminid."' ";
		$fetch = runmysqlqueryfetch($query);
			
		echo($fetch['adminid'].'^'.$fetch['fname'].'^'.$fetch['lname'].'^'.$fetch['name'].'^'.$fetch['password']
		.'^'.$fetch['email'].'^'.$fetch['register_user'].'^'.$fetch['productmaster'].'^'.$fetch['versionupdate'].'^'.
		$fetch['hotfixupdate'].'^'.$fetch['flashnewsupdate'].'^'.$fetch['grouphead'].'^'.
		$fetch['career'].'^'.$fetch['main_product'].'^'.$fetch['saralmail'].'^'.$fetch['saralmail_save'].'^'.
		$fetch['saralmail_disable'].'^'.$fetch['saralmail_delete'].'^'.$fetch['saralmail_search'].'^'.
		$fetch['saralmail_resetpass'].'^'.$fetch['saralmail_forward']);
	}
	break;

	case 'searchbyuseremail':
	{
		$searchuseremail = $_POST['searchuseremail'];
		$query = "SELECT * from saral_admins where  (fname = '".$searchuseremail."' OR email = '".$searchuseremail."');";
		$fetch = runmysqlqueryfetch($query);
		
		echo($fetch['adminid'].'^'.$fetch['fname'].'^'.$fetch['lname'].'^'.$fetch['name'].'^'.$fetch['password']
		.'^'. $fetch['email'].'^'.$fetch['register_user'].'^'.$fetch['productmaster'].'^'.$fetch['versionupdate'].'^'.
		$fetch['hotfixupdate'].'^'.$fetch['flashnewsupdate'].'^'.$fetch['grouphead'].'^'.$fetch['career'].'^'.
		$fetch['main_product'].'^'.$fetch['saralmail'].'^'.$fetch['saralmail_save'].'^'.
		$fetch['saralmail_disable'].'^'.$fetch['saralmail_delete'].'^'.$fetch['saralmail_search'].'^'.
		$fetch['saralmail_resetpass'].'^'.$fetch['saralmail_forward']);
	}
	break;
}
?>