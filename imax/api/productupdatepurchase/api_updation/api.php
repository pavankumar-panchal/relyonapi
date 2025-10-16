<?php

//echo "1";
include('functions/phpfunctions.php');
//include('error_log.php');

if($_REQUEST['Qa1iio9']==""  || $_REQUEST['AsWrIo'] == "")
{
	exit;
}
 

$cusid = $productcode = $customerid = "";

$cusid = decodevalue($_REQUEST['Qa1iio9']);
$productcode = decodevalue($_REQUEST['AsWrIo']);
$productcode = substr($productcode, 0, 3);

if(strlen($cusid) == 5)
{
	$customerid = $cusid;
}
elseif(strlen($cusid) > 5)
{
	$customerid = substr($cusid,-5);
}
//echo "2";
//customer validation
$query="select count(*) as count from inv_mas_customer where slno =". $customerid;
$fetch = runmysqlqueryfetch($query);
$custcheck = $fetch['count'];
if($custcheck == 0)
{
	$output=WriteError("CustomerId is Invalid","1");
	terminate($output);
}

//product validation
$query1= "select count(*) as procount from inv_mas_product where productcode ='".$productcode."'";
$fetch1 =  runmysqlqueryfetch($query1);
$procheck = $fetch1['procount'];

if($procheck == 0)
{
	$output=WriteError("Product is Invalid","2");
	terminate($output);
}

//executive validation
$query2 = "select inv_mas_dealer.relyonexecutive from inv_mas_dealer
left join inv_mas_customer on inv_mas_dealer.`slno` = `inv_mas_customer`.`currentdealer`
where inv_mas_customer.slno=". $customerid;
$executivecount = runmysqlqueryfetch($query2);
if($executivecount["relyonexecutive"]=="no")
{
	$output=WriteError("Executive is not of Relyon","3");
	terminate($output);
}

//check for taxation product
$query3 = "select inv_mas_product.group as productgroup,subgroup from inv_mas_product where productcode = '".$productcode."'";
$fetch3 = runmysqlqueryfetch($query3);
$productgroup = $fetch3['productgroup'];
if($productgroup =="SPP" || $productgroup =="SAC" || $productgroup =="AIR")
{
	$output=WriteError("Updation not available.","4");
	terminate($output);
}

//check for last two year updation  & take subgroup for given productcode
$proquery = "select subgroup from inv_mas_product where productcode = '".$productcode."' 
order by year desc limit 1;";
$profetch = runmysqlqueryfetch($proquery);
$subgroup = $profetch['subgroup'];


//take current year
$currentyearquery = "select year from inv_mas_product order by inv_mas_product.year desc limit 1;";
$currentyearresult = runmysqlquery($currentyearquery);
$currentyearfetch = mysql_fetch_array($currentyearresult);
$currentyear = $currentyearfetch['year'];
//$currentyear = "2014-15";


//take last two of current year
$yearquery = "select distinct(year) from inv_mas_product where year!= '".$currentyear."' 
order by year desc limit 2;";
$yearresult = runmysqlquery($yearquery);
while($yearfetch = mysql_fetch_array($yearresult))
{
	 $yearcount[] = $yearfetch['year'];
}
//$yearcount[0] = "2013-14";
//$yearcount[1] = "2012-13";

//check in which year customer has taken product(take only one year count(first prefernce to previous year of current year and if not present then take two years before of current year.)
$query4 = "select inv_mas_product.year from inv_dealercard
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
where inv_dealercard.customerreference = ".$customerid."
and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year in 
('".$yearcount[0]."','".$yearcount[1]."') order by inv_mas_product.year desc limit 1";
$result4 = runmysqlquery($query4);
$count4 = mysql_num_rows($result4);

if($count4 == 0)
{
	$output=WriteError("You have not been taken card from last two year.Kindly Contact Relyon Executive for 
	further details.","5");
	terminate($output);
}
$fetch4 = runmysqlqueryfetch($query4);


$query6 = "select productcode from inv_mas_product where subgroup = '".$subgroup."' and year = '".$currentyear."'";
$fetch6 = runmysqlqueryfetch($query6);
$newproduct = $fetch6['productcode'];



$query7 = "select inv_customerproduct.customerreference from inv_customerproduct
left join inv_dealercard on inv_customerproduct.cardid = inv_dealercard.cardid
where inv_dealercard.productcode =".$newproduct." and inv_customerproduct.customerreference = ".$customerid ." AND inv_customerproduct.cardid <> 0 AND inv_dealercard.cardid <> 0";
$result7 = runmysqlquery($query7);
$count7 = mysql_num_rows($result7);


//check for multiple cards and whether product purchased or not

/*current year updation card count*/
$query8 = "select count(inv_dealercard.purchasetype)as newpurcount from inv_dealercard
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
where inv_dealercard.customerreference = ".$customerid."
and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$currentyear."' 
and inv_dealercard.purchasetype = 'updation'";
$fetch8 = runmysqlqueryfetch($query8);



/*last year updation card count*/
$query9 = "select count(inv_dealercard.purchasetype) as oldpurcount from inv_dealercard
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
where inv_dealercard.customerreference = ".$customerid."
and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$fetch4['year']."'";
$fetch9 = runmysqlqueryfetch($query9);


if($fetch8['newpurcount'] < $fetch9['oldpurcount'])
{
	if($count7 < $fetch9['oldpurcount'])
	{
		$output=WriteError("Product not yet purchased.","6");
		terminate($output);
	}
}
	
//check for product purchased or not
/*$query5 = "select customerreference,inv_dealercard.productcode from inv_dealercard
left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
where subgroup = '".$subgroup."' and year = '".$currentyear."' and customerreference = ".$customerid;
$result5 = runmysqlquery($query5);
$count5 = mysql_num_rows($result5);
if($count5 == 0)
{
	$output=WriteError("Product not yet purchased.","6");
	terminate($output);
}*/

//check whether product is registered or not
if($count7 < $fetch9['oldpurcount'])
{
	$output=WriteError("Product not yet registered.","7");
	terminate($output);
}

function terminate($output)
{
	echo $output;
	exit;
}


?>
