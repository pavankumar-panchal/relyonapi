<?php



include("Sfa/Merchant.php");
include("Sfa/PGResponse.php");
include("Sfa/PostLibPHP.php");


 $oPostLibphp	=	new	PostLibPHP();

 $oMerchant	=	new	Merchant();

 $oPGResp	=	new	PGResponse();



$oMerchant->setMerchantRelatedTxnDetails("00001148","00001148","00001148","1182780838062","200706252937635","000000694033","695033","","","INR","req.Authorization","50","","Ext1","Ext2","Ext3","Ext4","Ext5");


$oPgResp=$oPostLibphp->postRelatedTxn($oMerchant);





 print "Response Code:".$oPgResp->getRespCode()."<br>";
 print "Response Message".$oPgResp->getRespMessage()."<br>";
 print "Transaction ID".$oPgResp->getTxnId()."<br>";
 print "Epg Transaction ID".$oPgResp->getEpgTxnId()."<br>";
 print "Auth Id Code :".$oPgResp->getAuthIdCode()."<br>";
 print "RRN :".$oPgResp->getRRN()."<br>";





 ?>