<?php
include('../functions/phpfunctions.php');

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'customerdetails':
	{
		$customerid = $_POST['customerid'];
		$invoiceslno = $_POST['invoiceslno'];
		$customertanno = $_POST['customertanno'];

               $update = "update inv_spp_amc_pinv set deduction = 'NULL', tanno ='' where slno ='".$invoiceslno."';";
	       $result2 = runmysqlquery($update);

	       echo(json_encode('1^Customer Record Saved Successfully^'.$invoiceslno));

        }
	break;
}

?>