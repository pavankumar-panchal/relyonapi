<?php

/*
 Purpose: Get the Pro-forma invoice of SPP Product
 Created Date : 30/12/2016
 Updated Date : 31/12/2016 12:15pm
 Created by : S.Rudra Pratap
*/

//error_reporting(-1);
include 'inc/dbconfig.php';
include 'functions/phpfunctions.php';

 
 $cusid = decodevalue($_REQUEST['Qa1iio9']);
 $procode = decodevalue($_REQUEST['AsWrIo']);
 $productcode = substr($procode, 0, 3);
 $cardno = decodevalue($_REQUEST['OrqaiZ']);
 $scratchnumber = scratchcombine($cardno);

if($customerid =="" && $productcode =="" && $cardno =="" )
{
   $output=WriteError("Missing arguments","5");
   terminate($output);
}

if(strlen($cusid) == 5)
{
	$customerid = $cusid;
}
elseif(strlen($cusid) > 5)
{
	$customerid = substr($cusid,-5);
}

$validate_details = "SELECT slno FROM inv_mas_customer WHERE slno = '".$customerid."'";
$result_vald = runmysqlquery($validate_details);
$fetch_val = mysql_num_rows($result_vald);
if($fetch_val ==0 OR is_nan($customerid))
{
   $output=WriteError("CustomerId is Invalid","1");
   terminate($output);
} 

$validate_prddetails = "SELECT slno FROM inv_mas_product WHERE productcode = '".$productcode."'";
$result_prdvald = runmysqlquery($validate_prddetails);
$fetch_prdval = mysql_num_rows($result_prdvald);
if($fetch_prdval ==0 OR is_nan($productcode))
{
   $output=WriteError("Product is Invalid","2");
   terminate($output);
  
}

$validate_crddetails = "SELECT slno,cardid FROM inv_mas_scratchcard WHERE scratchnumber = '".$scratchnumber."'";
$result_crdvald = runmysqlquery($validate_crddetails);
$fetch_crdval = mysql_num_rows($result_crdvald);
$fetch_crdid = mysql_fetch_assoc($result_crdvald);
$cardid = $fetch_crdid['cardid'];
if($fetch_crdval ==0)
{
   $output=WriteError("Cardid is Invalid","3");
   terminate($output);
  
}

  $query ="SELECT 
             isap.slno as slno,
             isac.d_publishpinv as chkpublish
         from 
             inv_dealercard idc
         inner join
             inv_invoicenumbers invn on idc.invoiceid=invn.slno 
         inner join
             inv_spp_amc_pinv isap on invn.invoiceno = isap.invoiceno
         inner join
             inv_spp_amc_customers isac on isap.invoiceno = isac.invoiceno
         WHERE 
              idc.productcode = '".$productcode."' AND idc.cardid = '".$cardid."' AND idc.invoiceid !='' AND idc.customerreference like '%$customerid'";

  $resultquery = runmysqlquery($query);
  $fetchresultquery = mysql_fetch_array($resultquery);

  $pinvslno = $fetchresultquery['slno'];
  $publishpinv = $fetchresultquery['chkpublish'];

  if($pinvslno !="" && $publishpinv == 1)
  {
     // header('Location: http://imax.relyonsoft.com/api/sppnewupdatepurchase/index.php?Qa1iio9='.$customerid.'&AsWrIo='.$pinvslno);
    $output=WriteError($pinvslno,"6");
    terminate($output);
  }
  else
  {
    $output=WriteError("Pro-forma Invoice not available","4");
    terminate($output);
  }

function terminate($output)
{
  echo $output;
  exit;
}
 
?>