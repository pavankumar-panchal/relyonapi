<?php
error_reporting(0);
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'getcartdetails':
	{
		$productrray = $_POST['productrray'];
		$splitproductarray = explode('****',$productrray);
		$countproductarray = count($splitproductarray);
		
		if($countproductarray > 1)
		{
			for($i=0;$i<$countproductarray;$i++)
			{
				if($splitproductarray[$i]!="undefined")
				   $dataarray[] = explode("#",$splitproductarray[$i]);
			}
		}
		else
		{
			for($i=0;$i<$countproductarray;$i++)
			{
				if($splitproductarray[$i]!="undefined")
				  $dataarray[] = explode("#",$splitproductarray[$i]);
			}
		}
		
		$type = array();
		for($j=0;$j<count($dataarray);$j++)
		{
			//$type = $dataarray[$j][0];
			//$product = $dataarray[$j][1];
			if($j==0)
			{
				$product = $dataarray[$j][0]."$".$dataarray[$j][1];
			}
			else
			{
				$product = $product."$$".$dataarray[$j][0]."$".$dataarray[$j][1];
			}
		}
		//echo $type."$".$product;
		rslcreatecookie('customerid',$_POST['custid']);
		rslcreatecookie('dealerid',$_POST['dealerid']);
		echo(json_encode('1^'.$product));
	}
	break;
}


/*for($i=0;$i<$countproductarray;$i++)
{
	//echo $splitproductarray[$i];
	//echo $i;
	$dataarray = explode("#",$splitproductarray[$i]);
	echo $dataarray[$i];
}*/

?>