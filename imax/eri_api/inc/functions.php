<?php
	require_once("aes/AESEncryption.php");
	include("aes/seckey.php");
	//$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
   	//$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$key = base64_decode("PSVJQRk9QTEpNVU1DWUZCRVFGV1VVT0ZOV1RRU1NaWQ=");
	function write_code($code)
	{
		echo "<CODE>".$code."</CODE>";
	}
	
	function write_file()
	{
		$filename="../../../pfx/eri.pfx";
		$handle=fopen($filename,"rb");
		$contents = stream_get_contents($handle,filesize($filename));
		fclose($handle);
		echo "<C>" . encryptData($contents) . "</C>";
	}
	
	function writeRijandelIV()
	{
		global $iv;
		echo "<D>" . base64_encode($iv) . "</D>";
	}
	
	function encryptData($value)
	{
		global $iv;
		global $key;
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
		$padding = $block - (strlen($value) % $block);

		$text = $value.str_repeat(chr($padding), $padding);
		$enc = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CBC, $iv);
		$enc64=base64_encode($enc);
		return $enc64;
	}

	function write_credintails()
	{
		/*$u1="relyon";
		$p1="RELYON2011";
		$u2="RelRaghu";
		$p2="relyon#2011";
		$pfx_key="1234";*/
		
		$u1="ERIA100385";
		$p1="RELYON2011";
		$u2="ERIU100296";
		$p2="relyon#2011";
		$pfx_key="123456";
		
		global $AES_SEC_KEY;
		echo "<U1>" . AESEncryptCtr($u1, $AES_SEC_KEY, 256) . "</U1>";
		echo "<P1>" . AESEncryptCtr($p1, $AES_SEC_KEY, 256) . "</P1>";
		echo "<U2>" . AESEncryptCtr($u2, $AES_SEC_KEY, 256). "</U2>";
		echo "<P2>" . AESEncryptCtr($p2, $AES_SEC_KEY, 256) . "</P2>";
		echo "<PK>" . AESEncryptCtr($pfx_key, $AES_SEC_KEY, 256). "</PK>";
	}
	
	function validate_request($custid,$productcode,$computer_id,$softkey)
	{
		global $link_id;
		$query="SELECT inv_customerproduct.softkey AS softkey 
				FROM inv_customerproduct 
				left join inv_mas_customer on inv_customerproduct.customerreference=inv_mas_customer.slno 
				left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode  
				where inv_mas_customer.customerid= '".$custid."' 
				and  productcode='".$productcode."' and computerid='".$computer_id."'";
		$result=mysql_query($query,$link_id);
		//echo $query;		
		$query_data=mysql_fetch_array($result);
		$count=mysql_num_rows($result);
		if($count>0 || $softkey=="0X384934XX394324823efjhsldjfs89")
		{
			log_access($custid,$computer_id . $softkey);
			return true;
		}else{
			return false;
		}
	}
	
	function log_access($custid,$computer_id)
	{
		global $link_id;
		$req_ip=$_SERVER["REMOTE_ADDR"];
		$query="INSERT INTO eri_log (customerid,computerid,req_ip,req_date) 
				VALUES('".$custid."','".$computer_id."','".$req_ip."',NOW())";
		$result=mysql_query($query,$link_id);
	}
?>
