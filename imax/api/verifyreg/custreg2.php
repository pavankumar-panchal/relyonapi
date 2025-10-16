<?php
include('functions/phpfunctions.php');


//echo encodevalue(CUSTID);

//phpinfo();
//exit;
$a= encodevalue($_REQUEST["CUSTID"]);
$b= $_REQUEST["PINNO"];

header("location: api.php?CUSTID=".$a."&PINNO=".$b);
?>