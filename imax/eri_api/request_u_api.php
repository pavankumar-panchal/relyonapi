<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

	include("inc/common_db.inc.php");
	$link_id=db_connect();
	//include
	
	include("inc/functions.php");
	header ("content-type: text/xml");
	
	$custid="";	
	$computer_id="";
	$softkey="";
	$productcode="";
	
	if(isset($_POST["CUSTID"]))
	{
		$custid= AES_RLN_DENCRYPT($_POST["CUSTID"]);
	}
	if(isset($_POST["COMPID"]))
	{
		$computer_id= AES_RLN_DENCRYPT($_POST["COMPID"]);
	}
	if(isset($_POST["SOFTKEY"]))
	{
		$softkey= AES_RLN_DENCRYPT($_POST["SOFTKEY"]);
	}
	if(isset($_POST["PCODE"]))
	{
		$productcode= AES_RLN_DENCRYPT($_POST["PCODE"]);
	}
	/*echo "<br>c " . $_REQUEST["COMPID"];
	echo "<br>r " . $computer_id;
	
	echo "<br>c " . $_REQUEST["CUSTID"];
	echo "<br>c " . urldecode($_REQUEST["CUSTID"]);
	echo "<br>r " . $custid;
	
	echo "<br>c " . $_REQUEST["SOFTKEY"];
	echo "<br>r " . $softkey;
	
	echo "<br>c " . $_REQUEST["PCODE"];
	echo "<br>r " . $productcode;*/
	echo "<?xml version='1.0' encoding='iso-8859-1'?>";
	echo "<ROOT>";
	
	if($custid!="" && $computer_id!="" && $productcode!="")
	{
		if(validate_request($custid,$productcode,$computer_id))
		{		
			write_code("000^Success"); //sucess
			write_credintails();
		}
		else
		{
			write_code("002^Not a registred customer"); //failuer not a registred customer
		}
	}
	else
	{		
		write_code("001^Invalid Parameter"); //Invalid Parameter
	}
	
	echo "</ROOT>";

	//	$recognize=$_POST["rec"]
?>