<?php
 require_once("java/Java.php");

 $strMerchantId="00001203";
 $astrFileName="c://key//00001203.key";
 $astrClearData;


if($_POST){

	if($_POST['DATA']==null)
	{
	print "null is the value";
	}
		$astrResponseData=$_POST['DATA'];
		$astrClearData=validateEncryptedData($astrResponseData,$astrFileName,$strMerchantId);
		#print $astrClearData;
		parse_str($astrClearData, $output);
		$ResponseCode = $output['RespCode'];
		$Message = $output['Message'];
		$TxnID=$output['TxnID'];
		$ePGTxnID=$output['ePGTxnID'];
		$AuthIdCode=$output['AuthIdCode'];
		$RRN = $output['RRN'];
		$CVRespCode=$output['CVRespCode'];
		$FDMSResult=$output['FDMSResult'];
		$FDMSScroe=$output['FDMSScroe'];
		$Cookie=$output['Cookie'];

		print "Response Code:".java_values($ResponseCode)."<br>";
		print "Response Message".java_values($Message)."<br>";
		print "Transaction ID:".java_values($TxnID)."<br>";
	    print "Epg Transaction ID:".java_values($ePGTxnID)."<br>";
		print "Auth Id Code :".java_values($AuthIdCode)."<br>";
		print "RRN :".java_values($RRN)."<br>";
		print "CVResp Code :".java_values($CVRespCode)."<br>";
		print "FDMS Score:".java_values($FDMSResult)."<br>";
		print "FDMS Result:".java_values($FDMSScroe)."<br>";

		# the cookie has to be written to client browser and the same has to be retrieved
		# and set in session details on further calls to postSSL
		print "Cookie:".java_values($Cookie)."<br>";



 }

 function validateEncryptedData($astrResponseData,$astrFileName,$strMerchantId){
 		$fp = fopen ($astrFileName,"r");
 		$strModulus = fgets($fp,1024);
 		$strModulus=decryptMerchantKey(trim($strModulus),$strMerchantId);
 		$strExponent =fgets($fp,1024);
 		$strExponent =decryptMerchantKey(trim($strExponent),$strMerchantId);

 		$oEncryptionLib =new Java('com.opus.epg.sfa.java.EPGMerchantEncryptionLib');
 		$astrClearData=$oEncryptionLib->decryptDataWithPrivateKeyContents($astrResponseData,$strModulus,$strExponent);
 		return $astrClearData;
 }


 function decryptMerchantKey($strData, $strMerchantId){
 		$strMerchantId=$strMerchantId.$strMerchantId;
 		$strDecryptData=decryptData($strData,$strMerchantId);
 		return $strDecryptData;
 }


 function decryptData($strData,$strKey){
 	   	$oEPGCryptLib= new Java('com.opus.epg.sfa.java.EPGCryptLib');
 		$decryptData=$oEPGCryptLib->Decrypt($strKey,$strData);
 		return $decryptData;
}
?>