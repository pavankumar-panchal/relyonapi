<?php
include('../functions/phpfunctions.php');

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'customerdetails':
	{
		$customerid = $_POST['customerid'];
		$invoiceslno = $_POST['invoiceslno'];

	       echo(json_encode('1^Customer Record Saved Successfully^'.$lastslnos));

        }
	break;
}

?>