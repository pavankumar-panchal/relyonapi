<?php
	include('./functions/phpfunctions.php');

	session_start();
	$isloggedin = 'false';
	$cookie_logintype = '';
	
	if((imaxgetcookie('sessionkind') <> false) && (imaxgetcookie('userid') <> false) && (imaxgetcookie('checkpermission') <> false))
	{
		
		$cookie_logintype = imaxgetcookie('sessionkind');
		$isloggedin = 'true';
	}
	if($cookie_logintype == 'logoutforthreemin' || $cookie_logintype == 'logoutforsixhr')
	{
		if($_SESSION['verificationid'] == '4563464364365')
			$isloggedin = 'true';
		else
			$isloggedin = 'false';
	}
	
	
	if($isloggedin == 'true')
	{
		header('Location:'.'./home/index.php');
	}
	 
	$date = datetimelocal('d-m-Y');
	$time = datetimelocal('H:i:s');
	
	$defaultusername = '';
	$message = '';
	if(isset($_POST["login"]))
	{
		$username = strtoupper($_POST['username']);
		$password = $_POST['password'];
		$loggintype = $_POST['loggintype'];
		
		if($username == '' or $password == '')
		{
			$message = '<span class="error-message"> Enter the User Name or Password </span>';
			$defaultusername = $username;
		}
		else
		{
			$query = "SELECT * FROM saral_admins WHERE name = '".$username."' and disablelogin = '0'";
			$result = runmysqlquery($query);
			if(mysql_num_rows($result) > 0)
			{
				$fetch = runmysqlqueryfetch($query);
					
				$user = $fetch['name']; 
				$passwd = $fetch['password'];
				
				if($password <> $passwd)
				{
					$message =' : <span class="error-message"> Password does not match with the user </span> : ';
					$defaultusername = $username; 
				}
				else
				{					
					$query = runmysqlqueryfetch("SELECT * FROM saral_admins WHERE name = '".$username."'");
					$userid = $query['adminid'];
					
						$permissions = $fetch['register_user']."|^||".$fetch['productmaster']."|^||".
						$fetch['versionupdate']."|^||".$fetch['hotfixupdate']."|^||".$fetch['flashnewsupdate']."|^||".
						$fetch['saralmail']."|^||".$fetch['saralmail_save']."|^||".$fetch['saralmail_resetpass']."|^||".
						$fetch['saralmail_delete']."|^||".$fetch['saralmail_disable']."|^||".$fetch['saralmail_forward'].
						"|^||".$fetch['saralmail_search']."|^||".$fetch['grouphead']."|^||".$fetch['career']."|^||".
						$fetch['main_product'];
					
					//Check for the Login type.
					if($loggintype == 'logoutforthreemin')
					{
						session_start();
						$_SESSION['verificationid'] = '4563464364365';
					}
					elseif($loggintype == 'logoutforsixhr')
					{
						session_start();
						$_SESSION['verificationid'] = '4563464364365';
					}
					elseif($loggintype == 'logoutforever')
					{
						session_start();
					}
					
					imaxcreatecookie('sessionkind',$loggintype); 
					imaxcreatecookie('userid',$userid);
					imaxcreatecookie('checkpermission', $permissions);
										
					$eventquery = "Insert into saral_audit(userid,ipaddr,datetime,activity_type,eventtype) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','Logged in','1')";
					$eventresult = runmysqlquery($eventquery);
					
					if(isset($_GET['link']) && isurl($_GET['link']) && isvalidhostname())
					{
								header('Location:'.$_GET['link']);
					}
					else
					{
								header('Location:'.'./home/index.php?a_link=home_dashboard');
					}
				}
			}
			else
			{
				$message = '<span class="error-message"> Login not registered </span>';
			}
		}
	}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<meta http-equiv="cache-control" content="no-cache,no-store,must-revalidate">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<title>Product Master Login</title>
<link type="text/css" href="./css/login.css" rel="stylesheet" media="all" />
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
<script language="javascript" src="./functions/cookies.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript">
function checknavigatorproperties()
{
	if((navigator.cookieEnabled == false) || (!navigator.javaEnabled())){ document.getElementById('username').focus(); return false; }
	else
	{
		return true;
		form.submit();
	}
}

</script>
<?php include('./inc/scriptsandstyles.php'); ?>
<script language="javascript" src="./functions/main-enter-shortcut.js?dummy=<?php echo (rand());?>"></script>
<style type="text/css">
#apDiv1 
{
	position:absolute;
	width:389px;
	height:270px;
	z-index:1;
	top: 147px;
}
</style>

</head>
<body onload="document.submitform.username.focus(); SetCookie('logincookiejs','logincookiejs'); if(!GetCookie('logincookiejs')) document.getElementById('form-error').innerHTML = '<span class=\'error-message\'>Enable cookies for this site </span>';">
<div id="page_conents_filled">
  <p><em><a href="http://www.relyonsoft.com" target="_blank"><img class="logo" src="images/relyonsoft.png" alt="relyon"></a></em></p>
  <div id="clearlayer" />
</div>
<div id="signinn_heading"> Relyon Product Master Login Page</div>
<div id="clearlayer" />
<br/>
<div id="apDiv1"><a href="http://www.saralaccounts.com" target="_blank" ><img src="images/relyonweb-tabcontent-saibox.gif" alt="Saral Accounts" title="Relyon - Saral Accounts" /></a>&nbsp;&nbsp;&nbsp; <a href="http://www.saraltaxoffice.com" target="_blank"> <img src="images/relyonweb-tabcontent-sto.gif" alt="Saral Tax Office" title="Relyon - Saral Tax Office" /></a> &nbsp;&nbsp;&nbsp; <a href="http://www.saraltds.com" target="_blank"><img src="images/relyonweb-tds-box.gif" alt="Saral TDS" title="Relyon - Saral TDS" /></a> &nbsp;&nbsp;&nbsp; <a href="http://www.saralpaypack.com" target="_blank"><img src="images/relyonweb-tabcontent-sppbox.gif" alt="Saral PayPack" title="Relyon - Saral PayPack" /></a>&nbsp;&nbsp;&nbsp; <a href="http://www.saralvat.com" target="_blank"><img src="images/relyonweb-vat-box.gif" alt="Saral VAT" title="Relyon - Saral VAT" /></a>&nbsp;&nbsp;&nbsp; <a href="http://www.relyonsoft.com/home/buy/index.php" target="_blank"><img src="images/saral-xbrl.gif" alt="Saral XBRL" title="Relyon - Saral XBRL" /></a><br/>
  <br/>
  &nbsp;&nbsp; <a href="http://www.relyonsoft.com/home/buy/index.php" target="_blank"><img src="images/saral-esign-logo.gif" alt="Saral Sign" title="Relyon - Saral Sign" /></a></div>
<div class="sign-inn">
  <div class="signin-box">
    <h2> Sign In Here <strong></strong></h2>
    <form id="submitform" name="submitform" method="post" action="">
      <div class="email-div">
        <label for="Email"><strong class="email-label">Username</strong></label>
	<input name="username" type="text" class="swifttext type_enter" id="username" size="30" maxlength="40" value="<?php echo($defaultusername);?>" />
      </div>
      <!--email-div-->
      
      <div class="passwd-div">
        <label for="Passwd"><strong class="passwd-label">Password</strong></label>
        <input name="password" type="password" class="swifttext type_enter" id="password" size="30" maxlength="20" />
         </div>
      <!--passwd-div-->
      <input type="hidden" name="loggintype" id="logoutforever"  value="logoutforever"/>
      <!--end logout forever --> 
      <!--  <input name="login" type="submit"  id="login" value="Login"  onclick="checknavigatorproperties()" />-->
      <input type="submit" class="g-button g-button-submit" name="login" id="login" value="Sign in" onclick="checknavigatorproperties()" >
      &nbsp;&nbsp;&nbsp;
      <input name="clear" type="reset" class="g-button g-button-submit" id="clear" value="Clear" onClick="document.getElementById('form-error').innerHTML = '';document.getElementById('username').focus()" />
    </form>
    <br />
    <div align="center" id="form-error" style="height:18px">
      <noscript>
      <div class="error-message"> Enable cookies/javscript/both in your browser,  then </div>
      </noscript>
	<?php if($message <> '') echo($message); ?>
    </div>
  </div>
  <!--signin-box --> 
</div>
<!--sign-in-->
</div>
<!-- End page_conents_filled-->
<div id="clearlayer"></div>
<div id="footer">
  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="foottext">
    <tr>
      <td><div align="right" id="copyright">&copy; <?php print (date("Y")); ?> Relyon Softech Ltd. All rights reserved Powered by <a target="_blank" href="http://www.relyonsoft.com">Relyon Softech Limited </a></div></td>
    </tr>
  </table>
</div>
</body>
</html>
