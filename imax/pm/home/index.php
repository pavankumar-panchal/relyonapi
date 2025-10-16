<?php 
	include('../functions/phpfunctions.php'); 
	include('../inc/sessioncheck.php');
	include('../inc/checkpermission.php');
	$userid = imaxgetcookie('userid');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php $pagetilte = getpagetitle($_GET['a_link']); echo($pagetilte); ?>
</title>
<link media="screen" rel="stylesheet" href="../functions/datepickercontrol.css?dummy=<?php echo (rand());?>"  />
<?php include('../inc/scriptsandstyles.php'); ?>
</head>
<body>
<div style="display:none; visibility:hidden;"><img src="../images/imax-loading-image.gif" border="0"/></div>
	<?php
		$query = "Select fname,lname,name,email from saral_admins where adminid = '".$userid."'";
		$fetch = runmysqlqueryfetch($query);
		$fullname = $fetch['fname'].' '.$fetch['lname']; 
		$username = $fetch['name'];
		$femail = $fetch['email'];
    ?>
<?php
	// Menu Nav . . . file
	include("../inc/navigation.php");
?>
<div id="pageheading">
<?php $pageheader = getpageheader($_GET['a_link']); echo($pageheader); ?>
 </div>
<div id="loggedas" style="text-align:right; border-bottom:1px #999999 dashed;">Logged in as: <?php echo( $fullname); echo(' ['.$username.']')?> </div>
<div id="clearlayer" />
<div id="page_conents_filled">
<?php $pagelink = getpagelink($_GET['a_link']); include($pagelink); ?>
</div>
<!-- End page_conents_filled-->
<div id="clearlayer"></div>
<?php include("../inc/footer.php"); ?>
</body>
</html>