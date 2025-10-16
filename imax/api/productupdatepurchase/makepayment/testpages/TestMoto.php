<?php


	include("Sfa/BillToAddress.php");
	include("Sfa/CardInfo.php");
	include("Sfa/Merchant.php");
	include("Sfa/MPIData.php");
	include("Sfa/ShipToAddress.php");
	include("Sfa/PGResponse.php");
	include("Sfa/PostLibPHP.php");
	include("Sfa/PGReserveData.php");

	include("Sfa/Address.php");
	include("Sfa/SessionDetail.php");
	include("Sfa/CustomerDetails.php");
	include("Sfa/MerchanDise.php");
	include("Sfa/AirLineTransaction.php");

	$oMPI 		= 	new 	MPIData();

	$oCI		=	new	CardInfo();

	$oPostLibphp	=	new	PostLibPHP();

	$oMerchant	=	new	Merchant();

	$oBTA		=	new	BillToAddress();

	$oSTA		=	new	ShipToAddress();

	$oPGResp	=	new	PGResponse();

	$oPGReserveData = new PGReserveData();

    # Bharosa Object

	$oSessionDetails   		=  new SessionDetail();
	$oCustomerDetails   	=  new CustomerDetails();
	$oOfficeAddress      	=  new Address();
	$oHomeAddress    		=  new Address();
	$oMerchanDise       	=  new MerchanDise();
	$oAirLineTransaction 	=  new AirLineTransaction();



	$oMerchant->setMerchantDetails("00001203","00001203","00001203","10.10.10.238",rand()."","","","","INR","INV123","req.Preauthorization","100","","Ext1","Ext2","Ext3","Ext4","Ext5");

	$oBTA->setAddressDetails ("CID","Tester","Aline1","Aline2","Aline3","Pune","MH","48927489","IND","tester@soft.com");

	$oSTA->setAddressDetails ("Add1","Add2","Add3","City","State","443543","IND","sad@df.com");

	$oCI->setCardDetails ("MC","5081264401288025","234","2008","12","Tester","CREDI");

	$oMPI->setMPIResponseDetails  ("01","NTBlZjRjMThjMjc1NTUxYzk1MTY=","U","AAAAAAAAAAAAAAAAAAAAAAAAAAA=","84759435","1000","356");


	# To set the value to Bharosa Object

    #Parameter Name for Address Details   AddLine1   AddLine2    ,  AddLine3  , City   , State ,  Zip Country , Email id
	$oHomeAddress->setAddressDetails("Sandeep","UttamCorner","Chinchwad","Pune","state","4385435873","IND","tester@soft.com");
	$oOfficeAddress->setAddressDetails("2Opus","MayFairTowers","Wakdewadi","Pune","state","4385435873","IND","tester@soft.com");

	#Parameter Name for Customer Details First Name,LastName ,Office Address Object,Home Address Object,Mobile No,RegistrationDate, flag for matching bill to address and ship to address
	$oCustomerDetails->setCustomerDetails("Amit","Paliwal", $oOfficeAddress, $oHomeAddress,"09372450137","2007-06-13","Y");

	#Parameter Name for Merchant Dise Details Item Purchased,Quantity,Brand,ModelNumber,Buyers Name,flag value for matching CardName and BuyerName
	$oMerchanDise->setMerchanDiseDetails("Computer","2","Intel","P4","Sandeep Patil","Y");

	#Parameter for Session Details   Remote Address, Cookies Value            Browser Country,Browser Local Language,Browser Local Lang Variant,Browser User Agent
	$oSessionDetails->setSessionDetails($_SERVER["REMOTE_ADDR"],"TestCookie","Browser Country",$_SERVER["HTTP_ACCEPT_LANGUAGE"],"",$_SERVER["HTTP_USER_AGENT"]);

	#Parameter Name for AirLine Transaction Details  Booking Date,FlightDate,Flight Time,Flight Number,Passenger Name,Number Of Tickets,flag for matching card name and customer name,PNR,sector from,sector to
	$oAirLineTransaction->setAirLineTransactionDetails ("2007-10-06", "2007-06-22","13:20","119", "Sandeep", "1",  "Y", "25c","Pune","Mumbai");




    # for passing null for any parameter, just pass null
    # eg to pass null for merchandise
    # eg ->postMOTO($oBTA,$oSTA,$oMerchant,$oMPI,$oCI,$oPGReserveData,$oCustomerDetails,$oSessionDetails,$oAirLineTransaction,null);
    #
 	$oPGResp=$oPostLibphp->postMOTO($oBTA,$oSTA,$oMerchant,$oMPI,$oCI,$oPGReserveData,$oCustomerDetails,$oSessionDetails,$oAirLineTransaction,$oMerchanDise);




	# This will remove all white space
	#$oResp =~ s/\s*//g;

	# $oPGResp->getResponse($oResp);

	 print "Response Code:".java_values($oPGResp->getRespCode())."<br>";

	 print "Response Message".java_values($oPGResp->getRespMessage())."<br>";

	 print "Transaction ID:".java_values($oPGResp->getTxnId())."<br>";

	 print "Epg Transaction ID:".java_values($oPGResp->getEpgTxnId())."<br>";

	 print "Auth Id Code :".java_values($oPGResp->getAuthIdCode())."<br>";

	 print "RRN :".java_values($oPGResp->getRRN())."<br>";

	 print "CVResp Code :".java_values($oPGResp->getCVRespCode())."<br>";

     print "FDMS Score:".java_values($oPGResp->getFDMSScore())."<br>";

	 print "FDMS Result:".java_values($oPGResp->getFDMSResult())."<br>";

     # the cookie has to be written to client browser and the same has to be retrieved
     # and set in session details on further calls to postMoto
	 print "Cookie:".java_values($oPGResp->getCookie())."<br>";


 ?>