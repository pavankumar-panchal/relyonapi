<?
if(imaxgetcookie('userid') <> '' || imaxgetcookie('userid') <>  false) 
{
	$pagelinksplit = explode('/',$pagelink);
	$pagelinkvalue = substr($pagelinksplit[2],0,-4);
	$userid = imaxgetcookie('userid');
	switch($pagelinkvalue)
	{
		case 'dashboard':  $pagetextvalue = '46'; break;
		case 'pm':  $pagetextvalue = '2'; break;
		case 'main_product':  $pagetextvalue = '6'; break;
		case 'career':  $pagetextvalue = '47'; break;
		case 'version_product':  $pagetextvalue = '20'; break;
		case 'hotfix_product':  $pagetextvalue = '25'; break;
		case 'flashnews':  $pagetextvalue = '30'; break;
		case 'grouphead':  $pagetextvalue = '10'; break;
		case 'saralmail':  $pagetextvalue = '52'; break;
		case 'saralmail_disable':  $pagetextvalue =  '53'; break;
		case 'emailsearch':  $pagetextvalue = '54'; break;
		case 'registeruser':  $pagetextvalue = '14'; break;
		case 'editprofile':  $pagetextvalue = '55'; break;
		
	}
	$eventquery = "Insert into saral_audit(userid,ipaddr,datetime,activity_type,eventtype) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','PAGE-VIEW','".$pagetextvalue."')";
	$eventresult = runmysqlquery($eventquery);
}
	
?>
