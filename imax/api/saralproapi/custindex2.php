<?php
include('functions/phpfunctions.php');


//echo encodevalue(CUSTID);

//phpinfo();
//exit;
$a= $_REQUEST["CUSTID"];

header("location: api.php?CUSTID=".$a);
?>