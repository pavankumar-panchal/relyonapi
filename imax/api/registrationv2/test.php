<?php

include('inc/common_db.inc.php');
//elseif(file_exists("../inc/common_db.inc.php"))
	//include('../inc/common_db.inc.php');
//else
	//include('./inc/common_db.inc.php');

//Connect to host
$newconnection = mysql_connect($dbhost, $dbusername, $dbuserpassword) or die("Cannot connect to Mysql server host");

$custrefno=44118;

function runmysqlquery($query)
{
	global $newconnection;
	$dbname = 'relyon_imax';

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in Runquery function1.".$query . " newconnection = " .$newconnection . "error=" . mysql_error($newconnection)); //;
	
	//Return the result
	return $result;
}

$query5 = "select Distinct(businessname) from inv_mas_customer where slno='".$custrefno."'";
			$result5 = runmysqlquery($query5);	
			$fetch5 = mysql_fetch_array($result5);
			$custname=$fetch5['businessname'];	
		
		echo $custname;
		
		exit;	
			

$componentList1="5VVAK138;#;5PS020E3";
$componentList2="5VVAK138;#;5PS020E3";
echo matchHWComponents($componentList1, $componentList2);

function matchHWComponents($componentList1, $componentList2)
{
	$flag_cmp=0;
    if(strcasecmp($componentList1, $componentList2) == 0)
	{
		return true;
	}
	else
	{
    
	   $compareCompList2 = $maxLimit = $intI = '';
	    $compareCompList2 = explode(";#;", $componentList2);
	    $maxLimit = count($compareCompList2);
		
		echo "<br>Max Limit " . $maxLimit;
    
	    for($intI = 0; $intI < $maxLimit; $intI++)
		{
	        $pos = strpos($componentList1, $compareCompList2[$intI]);			
			echo "<br>First Loop : ". $intI . ", componentList1=" . $componentList1 . ", compareCompList2=".$compareCompList2[$intI];
			if($pos !== false)
			{
	            $flag_cmp=1;
	        }
			
			echo "<br>Flag : ". $flag_cmp;
	    }
    
	    $compareCompList1;
		$compareCompList1 = explode(";#;", $componentList1);
	    $maxLimit = count($compareCompList1);
    
    
	    for ($intI = 0; $intI < $maxLimit; $intI++)
		{
	        $pos = strpos($componentList2, $compareCompList1[$intI]);
			
			echo "<br>Second Loop : ". $intI . ", componentList2=" . $componentList2 . ", compareCompList1=".$compareCompList1[$intI];
			if ($pos !== false)
			{
	            	$flag_cmp=1;
	        }
			echo "<br>Flag : ". $flag_cmp;
	    }
	    if($flag_cmp==1)
	    return true;
	    else
	    return false;
   	 }
}
?>