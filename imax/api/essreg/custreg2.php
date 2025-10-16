<?php
include('functions/phpfunctions.php');


//echo encodevalue(CUSTID);

//phpinfo();
//exit;
//$a= encodevalue($_REQUEST["CUSTID"]);
$a= $_REQUEST["CUSTID"];
$b= $_REQUEST["PINNO"];
$c= $_REQUEST["REGCODE"];
$d= $_REQUEST["PRDCODE"];
$e= $_REQUEST["CMPNAME"];
$f= $_REQUEST["CMPID"];

header("location: verifyreg.php?CUSTID=".$a."&PINNO=".$b."&REGCODE=".$c."&PRDCODE=".$d."&CMPNAME=".$e."&CMPID=".$f);
?>