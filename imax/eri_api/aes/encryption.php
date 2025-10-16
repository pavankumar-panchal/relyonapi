<?php
	//gets the encryption functions
	require_once(dirname(__FILE__) . "/AESEncryption.php");
	
	include("seckey.php");
	$data="manjunath";
	$sendData = AESEncryptCtr($data, $AES_SEC_KEY, 256);
	echo "<br>Encrypted : " . $sendData;
	
	
	$data = AESDecryptCtr($sendData , $AES_SEC_KEY, 256);
	
	echo "<br>Decrypt : " . $data;
	
	
?>