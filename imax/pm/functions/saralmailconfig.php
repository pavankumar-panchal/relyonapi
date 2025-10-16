<?php
 	
	//Including xmlapi file
	include 'xmlapi.php';

	// cpanel username
	$loginname = "relyonsoft";

	// cpanel password
	//$loginpasswd = '7z;i$(dKl+8h';
	$loginpasswd = 'S%MOE8aPQlwa$#a';

	// domain associated with the email account
	$email_domain = 'relyonsoft.com'; 

	// should be server IP address or 127.0.0.1 if local server
	$ip = "213.229.120.7"; 

	// cpanel secure authentication port unsecure port# 2082 
	$port ='2083';   
	
	//Cpanel Skin details
	$cpskin = 'x3';

	// instantiate client
	$xmlapi = new xmlapi($ip);

	//Checking the Username/Password of Cpanel 
	$xmlapi->password_auth($loginname, $loginpasswd);

	//Setting the port for xmlapi
	$xmlapi->set_port(2083);

	//$xmlapi->set_debug(1);  // uncomment for debugging


function cpanel_init()
{
	global $loginname;
	global $loginpasswd;
	global $port;
	global $ip;
	global $xmlapi;
	
	$xmlapi = new xmlapi($ip);
	//set port number. cpanel client class allow you to access WHM as well using WHM port.
	$xmlapi->set_port($port); 
	
	// authorization with password. not as secure as hash.
	$xmlapi->password_auth($loginname, $loginpasswd);   
	//output to error file  set to 1 to see error_log.
	$xmlapi->set_debug(1);      
}

function cpanel_createemail($form_email,$form_password,$form_password,$form_quota)
{
	global $email_domain;
	global $loginname;
	global $xmlapi;
	cpanel_init();
	//check password
	$msg="";
	
	// cpanel email addpop function Parameters
	$call = array('domain'=>$email_domain, 'email'=>$form_email, 'password'=>$form_password, 'quota'=>$form_quota);
	
	// making call to cpanel api
	$result = $xmlapi->api2_query($loginname, "Email", "addpop", $call ); 
	
	if ($result->data->result == 1)
	{
		$msg ="001";//"Email Account Created";
	} 
	else 
	{
		$msg= "002";//"E-mail Account already exists";
	}
	return $msg;
}
					
function cpanel_forwarder($form_email,$form_forwards)
{
	global $email_domain;
	global $loginname;
	global $xmlapi;
	$msg="";
	cpanel_init();
	$call_f  = array('domain'=> $email_domain, 'email'=> $form_email, 'fwdopt'=> "fwd", 'fwdemail'=> $form_forwards.'@'.$email_domain);
	$result_forward = $xmlapi->api2_query($loginname, "Email", "addforward", $call_f); //create a forward  

	if ($result_forward->data->result == 1)
	{
		$msg="003"; //"Email Account's Forwarder Created";
	}
	return $msg;
}
function cpanelresetpassword($form_email,$form_disablepass)
{
	global $email_domain;
	global $loginname;
	global $xmlapi;
	$msg="";
	cpanel_init();
	$args = array
	(
		'domain' => $email_domain,
		'email' => $form_email,
		'password' => $form_disablepass
	);  
	$response = $xmlapi->api2_query($loginname, 'Email', 'passwdpop', $args);
	
	if( (int)$response->event->result != 1 || (int)$response->data->result != 1) 
	{
		$msg="005"; //Failed to Change Password for  account
	}
	else
	{
		$msg = "006"; //Email Account Password has been Changed

	}
		return $msg;

}
function cpanelresetpass($form_email,$form_changepass)
{
	global $email_domain;
	global $loginname;
	global $xmlapi;
	$msg="";
	cpanel_init();
	$args = array
	(
		'domain' => $email_domain,
		'email' => $form_email,
		'password' => $form_changepass
	);  
	$response = $xmlapi->api2_query($loginname, 'Email', 'passwdpop', $args);
	
	if( (int)$response->event->result != 1 || (int)$response->data->result != 1) 
	{
		$msg="005"; //Failed to Change Password for  account
	}
	else
	{
		$msg = "006"; //Email Account Password has been Changed

	}
		return $msg;

}

function cpaneldelete($form_email)
{
	global $email_domain;
	global $loginname;
	global $xmlapi;	
	
	//Passing Argru. . . 
	$args = array
	(
		'domain' => $email_domain,
		'email' => $form_email,
	);  
	// making call to cpanel api
	$result = $xmlapi->api2_query($loginname, 'Email', 'delpop', $args); 
	
	if ($result->data->result == 1)
	{
	$msg = "007";//'Email Account Deleted
	}
	else 
	{
	$msg = $result->data->reason;
	}
		return $msg;
}

function deleteforwarder($form_email,$form_forwards)
{
	global $email_domain;
	global $loginname;
	global $loginpasswd;
	global $cpskin;
	
	/*echo "".$email_domain .'^'.$loginname.'^'.$loginpasswd.'^'.$cpskin;
	exit;*/
	$msg = '';
	$delfrdurl = "https://$loginname:$loginpasswd@$email_domain:2082/frontend/$cpskin/mail/dodelfwd.html?email=$form_email@$email_domain&emaildest=$form_forwards@$email_domain";

	// Delete email forwarder
	$f = @fopen($delfrdurl,
	 "r");
	
	$msg = 'Forwarder Deleted';
	if (!$f) 
	{
	  $msg = ('Cannot delete forwarder.');
	}
	
	@fclose($f);
	
	
	
	return $msg;
}

?>