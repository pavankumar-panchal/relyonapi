<?php 
	

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
	include "inc/dbconfig.php";
	$link_id=mysqli_connect($dbhost, $dbuser, $dbpwd, $dbname) or die("Cannot connect to Mysql server host");
	mysqli_select_db($link_id,$dbname) or die("Cannot connect to database");
	
	$input = $_POST['CUSTINFO'];
	/*$input=")hJh\hZh[hPhKh)hAh)h8h<h9h:h?h8h8h:h=h:h7h?h;h8h@h>h<h)h3h'h)hUhVhVhMhThVhUh[hOhZh)hAh)h<h<h)h3h'h)h]hLhYhZhPhVhUh)hAh)h@h:h@h7h)h3h'h)hJhVhThWhUhHhThLh)hAh)hYhlhsh€hvhuhfhMhvhyhth8h=h)h3h'h)hUhVhVhMhWhHh`hZhShPhWhZh)hAh)h)h3h'h)h[hHhUhUhVh)hAh)hIhShYhXh8h9h:h;h<hLh)h3h'h)h\hWhKhKhHh[hLh[hPhThLh)hAh)h9h7h9h8h4h8h9h4h:h7h'h8h=hAh9h9hAh<h>h)h3h'h)hYhLhNh`hUh)hAh)h8h)h3h'h)hThVhUh[hOh[h_h[h)hAh)hZhlhwh6h9h7h9h8h)h3h'h)hThVhUh[hOh`hLhHhYh)hAh)h9h;h9h=h8h)h3h'h)hZhLhJhJhVhKhLh)hAh)h8h:h:h@h>h5h<h)h3h'h)hShHhZh[hThVhUh[hOhJhYhLhHh[hLhKh)hAh)hVhjh{h6h9h7h9h8h)h3h'h)h[hVh[hThVhUh[hOhJhUh[h)hAh)h<h=h)h'h3h'h)hHhJh[hPh]hLhLhThWhShVh`hLhLhZh)hAh)h:h9h)h3h'h)hPhUhHhJh[hPh]hLhLhThWhShVh`hLhLhZh)hAh)h>h)h'h3h'h)h[hVh[hMhPhShLhZh)hAh)h<h9h)h'h3h'h)hJhVhThWhLhThHhPhSh)hAh)h)h'h3h'h)hWhMhJhVh\hUh[h)hAh)h?h)h3h'h)hLhZhPhJhVh\hUh[h)hAh)h:h)h'h3h'h)h[hKhZh)hAh)h)h'h3h'h)h[hKhZhLhThWhJhUh[h)hAh)h:h9h)h'h3h'h)h\hZhLhYhZhJhUh[h)hAh)h7h)h'h3h'h)hWhYhVhKh]hLhYhUh\hTh)hAh)h@h:h@h7h)h'h3h'h)h]hLhYhZhPhVhUhUh\hTh)hAh)h8h=h)";*/
	 $decodedval = decodevalue($input);
	$decodedval = "{". $decodedval ."}";
	/*$input = '{"CUSTID":"15238113630841975", "NOOFMONTHS":"55", "VERSION":"9390", "COMPNAME":"Relyon_Form16", "NOOFPAYSLIPS":"", "TANNO":"BLRQ12345E", "UPDDATETIME":"2021-12-27 17:23:33", "REGYN":"1", "MONTHTXT":"Sep/2021", "MONTHYEAR":"24261", "SECCODE":"13397.5", "LASTMONTHCREATED":"Oct/2021", "TOTMONTHCNT":"56" , "ACTIVEEMPLOYEES":"32", "INACTIVEEMPLOYEES":"7" , "TOTFILES":"52" , "COMPEMAIL":"" , "PFCOUNT":"8", "ESICOUNT":"3" , "TDS":"" , "TDSEMPCNT":"32" , "USERSCNT":"0" , "PRODVERNUM":"9390", "VERSIONNUM":"16", "DBVER":"7.8"}';*/
	
	$custinfo = json_decode($decodedval);
	$custid=$custinfo->CUSTID;
	$noofmonths=$custinfo->NOOFMONTHS;
	$version=$custinfo->VERSION;
	$companyname=$custinfo->COMPNAME;
	if($custinfo->NOOFPAYSLIPS>0)
	{
		$noofpayslips=$custinfo->NOOFPAYSLIPS;
	}
	else
	{
		$noofpayslips=0;
	}
    $tanno=$custinfo->TANNO;
	$upddatetime=$custinfo->UPDDATETIME;
	$regyn=$custinfo->REGYN;
	$monthtxt=$custinfo->MONTHTXT;
	$monthyear=$custinfo->MONTHYEAR;
	$seccode=$custinfo->SECCODE;
	$lastmonthcreated=$custinfo->LASTMONTHCREATED;
	$totmonthcnt=$custinfo->TOTMONTHCNT;
	$activeemployees=$custinfo->ACTIVEEMPLOYEES;
	$inactiveemployees=$custinfo->INACTIVEEMPLOYEES;
	$totfiles=$custinfo->TOTFILES;
	$compemail=$custinfo->COMPEMAIL;
	$pfcount=$custinfo->PFCOUNT;
	$esicount=$custinfo->ESICOUNT;
	$tds=$custinfo->TDS;
	$tdsempcnt=$custinfo->TDSEMPCNT;
	$userscnt=$custinfo->USERSCNT;
	$prodvernum=$custinfo->PRODVERNUM;
	$versionnum=$custinfo->VERSIONNUM;
	//$dbver=$custinfo->DBVER;	
	
		/*echo "insert into spp_customerinfo (CUSTID,NOOFMONTHS,SPP_VERSION,COMPNAME,NOOFPAYSLIPS,TANNO,UPDDATETIME,REGYN,MONTHTXT,MONTHYEAR,SECCODE,LASTMONTHCREATED,TOTMONTHCNT,ACTIVEEMPLOYEES,INACTIVEEMPLOYEES,TOTFILES,COMPEMAIL,PFCOUNT,ESICOUNT,TDS,TDSEMPCNT,USERSCNT,PRODVERNUM,VERSIONNUM) VALUE ('".$custid."',".$noofmonths.",".$version.",'".$companyname."', ".$noofpayslips.", '".$tanno."','".$upddatetime."',".$regyn.",'".$monthtxt."','".$monthyear."',".$seccode.",'".$lastmonthcreated."',".$totmonthcnt.",".$activeemployees.",".$inactiveemployees.",".$totfiles.",'".$compemail."',".$pfcount.",'".$esicount."','".$tds."',".$tdsempcnt.",".$userscnt.",".$prodvernum.",".$versionnum.")";*/
	$query_custominfo=mysqli_query($link_id,"insert into spp_customerinfo (CUSTID,NOOFMONTHS,SPP_VERSION,COMPNAME,NOOFPAYSLIPS,TANNO,UPDDATETIME,REGYN,MONTHTXT,MONTHYEAR,SECCODE,LASTMONTHCREATED,TOTMONTHCNT,ACTIVEEMPLOYEES,INACTIVEEMPLOYEES,TOTFILES,COMPEMAIL,PFCOUNT,ESICOUNT,TDS,TDSEMPCNT,USERSCNT,PRODVERNUM,VERSIONNUM) VALUE ('".$custid."',".$noofmonths.",".$version.",'".$companyname."', ".$noofpayslips.", '".$tanno."','".$upddatetime."',".$regyn.",'".$monthtxt."','".$monthyear."',".$seccode.",'".$lastmonthcreated."',".$totmonthcnt.",".$activeemployees.",".$inactiveemployees.",".$totfiles.",'".$compemail."',".$pfcount.",'".$esicount."','".$tds."',".$tdsempcnt.",".$userscnt.",".$prodvernum.",".$versionnum.")");
	
	echo "Saved Successfully";
	

function decodevalue($input)
{
	$input = str_replace('\\\\','\\',$input);
	$input = str_replace("\\'","'",$input);
	$length = strlen($input);
	$output = "";
	for($i = 0; $i < $length; $i++)
	{
		if($i % 2 == 0)
		$output .= chr(ord($input[$i]) - 7);
	}
	$output = str_replace("'","\'",$output);
	return $output;
}

function encodevalue($input)
{
	$length = strlen($input);
	$output1 = "";
	for($i = 0; $i < $length; $i++)
	{
		$output1 .= $input[$i];
		if($i < ($length - 1))
		$output1 .= "a";
	}
	$output = "";
	for($i = 0; $i < strlen($output1); $i++)
	{
		$output .= chr(ord($output1[$i]) + 7);
	}
	return $output;
}
?>
