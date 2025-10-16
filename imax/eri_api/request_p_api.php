<?php
	include("inc/common_db.inc.php");
	$link_id=db_connect();
	include("inc/functions.php");
	
	header ("content-type: text/xml; charset:ISO-8859-1;");
	
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
	
	echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
	echo "<ROOT>";
	if($custid!="" && $computer_id!="" && $productcode!="")
	{
	
		if(validate_request($custid,$productcode,$computer_id))
		{		
			write_code("000^Success"); //sucess
			write_file();
			writeRijandelIV();
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
?>