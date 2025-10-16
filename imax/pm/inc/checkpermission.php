<?php
if(imaxgetcookie('userid') == false) { $url = '../index.php'; header("Location:".$url); }
if(imaxgetcookie('checkpermission') == false) { $url = '../index.php'; header("Location:".$url); }

$userid = imaxgetcookie('userid');
$permissions = imaxgetcookie('checkpermission');

$permissionarray = explode('|^||',$permissions);
$p_registration = $permissionarray[0];
$p_productmaster = $permissionarray[1];
$p_versionupdate = $permissionarray[2];
$p_hotfixupdate = $permissionarray[3];
$p_flashnewsupdate = $permissionarray[4];
$p_saralmail = $permissionarray[5];
$p_saralmail_save = $permissionarray[6]; 
$p_saralmail_resetpass = $permissionarray[7];
$p_saralmail_delete = $permissionarray[8];
$p_saralmail_disable = $permissionarray[9];
$p_saralmail_forward = $permissionarray[10];
$p_saralmail_search = $permissionarray[11];
$p_grouphead = $permissionarray[12];
$p_career = $permissionarray[13];
$p_main_product = $permissionarray[14];
?>