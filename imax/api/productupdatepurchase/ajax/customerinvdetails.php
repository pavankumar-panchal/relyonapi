<?php
error_reporting(0);
//include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
$customerid = rslgetcookie('customerid');
$dealerid = rslgetcookie('dealerid');

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'customerdetails':
	{
		$lastslno = $_POST['lastslno'];
		$invoicelist = $_POST['invoicelist']; 
                $splitinvoicearray = explode(',',$invoicelist);
		
		if($lastslno == "")
		{
                  $lastslnos = "";
                  for($i = 0; $i< count($splitinvoicearray); $i++)
                  {
		      $query = "SELECT slno FROM inv_invoicenumbers 
                           WHERE invoiceno = '".$splitinvoicearray[$i]."'";

                      $fetch = runmysqlqueryfetch($query);
		      $lastslnos .= $fetch['slno'].",";
                  }
                       $lastslno = trim($lastslnos,",");
			
			echo(json_encode('1^'.$lastslno));
		}
	}
	
}
?>