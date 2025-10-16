<?php 
	include('../functions/phpfunctions.php');
	
	$lastslno  = $_POST['onlineslno'];
	
	if($lastslno == '')
	{
		$url = 'http://relyonsoft.com'; 
		header("location:".$url);
	}
	else
	{
		vieworgeneratepdfinvoice($lastslno,'view');
	}
?>