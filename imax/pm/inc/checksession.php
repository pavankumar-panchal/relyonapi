<?php
session_start();
if((imaxgetcookie('sessionkind') <> false) && (imaxgetcookie('userid') <> false) && (imaxgetcookie('checkpermission') <> false))
{
	$cookie_logintype = imaxgetcookie('sessionkind');
	$cookie_username = imaxgetcookie('userid');
	$cookie_permissiontype = imaxgetcookie('checkpermission'); 
}
else
{
	echo('Thinking to redirect');
	exit;
}

switch($cookie_logintype)
{
	case "logoutforthreemin":
		if($_SESSION['verificationid'] == '4563464364365')
		{
			ini_set('session.gc_maxlifetime',180);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',1);
			$sessionCookieExpireTime = 180;
			session_start();
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		}
		else
		{
			echo('Thinking to redirect');
			exit;
		}
		break;

	case "logoutforsixhr":
		if($_SESSION['verificationid'] == '4563464364365')
		{
			ini_set('session.gc_maxlifetime',21600);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',1);
			$sessionCookieExpireTime = 21600;
			session_start();
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		}
		else
		{
			echo('Thinking to redirect');
			exit;
		}
		break;

	case "logoutforever":
	//	session_start();
		break;
	
	default:
		echo('Thinking to redirect');
}

?>