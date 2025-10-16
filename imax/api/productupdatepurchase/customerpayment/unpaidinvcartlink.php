<?php
include('../functions/phpfunctions.php');
$cid=encodevalue($_GET['idc']);
$inv=encodevalue($_GET['inonv']);
$link="viewinvcart.php?idc=".$cid."&inonv=".$inv;

header('Location:'.$link);
?>