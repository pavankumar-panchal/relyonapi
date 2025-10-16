<?php
error_reporting(0);
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
$customerid = rslgetcookie('customerid');
$dealerid = rslgetcookie('dealerid');

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'customerdetails':
	{
		$lastslno = $_POST['lastslno'];
		$productlist = $_POST['productlist'];
		$splitproductlist = explode('#',$productlist);
		$usagelist = explode('#',$_POST['usagelist']);
		//$splitvalue = str_replace('#',',',$productlist);
		
		if($lastslno == "")
		{
			$query = "SELECT * FROM inv_mas_customer WHERE slno = '".$customerid."'";
			$fetch = runmysqlqueryfetch($query);
			$user = $fetch['slno'];
                        $currentdealer = $fetch['currentdealer'];
			//Contactperson fetch from contact details table

			$querycontactdetails = "select customerid, GROUP_CONCAT(inv_contactdetails.contactperson) as contactperson,
			GROUP_CONCAT(inv_contactdetails.phone) as phone, GROUP_CONCAT(inv_contactdetails.cell) as 
			cell,GROUP_CONCAT(inv_contactdetails.emailid) as emailid 
			from inv_contactdetails where customerid = '".$customerid."'  group by customerid ";
			$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);

			$contactperson = trim(removedoublecomma($resultcontactdetails['contactperson']),',');
			$phone = trim(removedoublecomma($resultcontactdetails['phone']),',');
			$cell = trim(removedoublecomma($resultcontactdetails['cell']),',');
			$emailid = trim(removedoublecomma($resultcontactdetails['emailid']),',');
			for($k=0;$k < count($splitproductlist);$k++)
		    {
				$query1 = "select inv_relyonsoft_prices.* from inv_relyonsoft_prices 
				left join inv_mas_product on inv_mas_product.productcode = inv_relyonsoft_prices.productcode
				where inv_mas_product.productcode = ".$splitproductlist[$k]." and purchasetype = 'updation' 
				and usagetype ='".$usagelist[$k]."'";
				$fetch1 = runmysqlqueryfetch($query1);
				
				$servicetax = round($fetch1['updationprice'] * 0.14);
				$sbtax = round($fetch1['updationprice'] * 0.005);
//kktax
				$kktax = round($fetch1['updationprice'] * 0.005);
				
				//kktax  ends

				$netamount = $fetch1['updationprice'] + $servicetax + $sbtax + $kktax;

				$totalamount += $netamount;

				$totalpricearray[] = $fetch1['updationprice'];
				$productarrayslno[] = $fetch1['slno'];
				$usagearraylist[] = $fetch1['usagetype'];
				
				$productslno = implode('*',$productarrayslno);
				$productusagetype = implode(',',$usagearraylist);
				$totalproductpricearray = implode('*',$totalpricearray);
			}
			//echo $totalproductpricearray;
			//echo $productslno;
			//$productusagetype = getusagetype($usagelist);
			$productpurchasetype = getpurchasetype($productlist);
			$currentdate = strtotime(date('Y-m-d'));
			
			$query2 = "Insert into pre_online_purchase(custreference,businessname,contactperson,address,
			place,district,pincode,stdcode,phone,cell,fax,emailid,website,type,category,amount,productcode,
			paymentdate,paymenttime,currentdealer,productpurchasetype,remarks,totalproductpricearray,products,usagetype) 
			values('".$user."','".trim($fetch['businessname'])."','".$contactperson."','".addslashes($fetch['address'])."',
			'".$fetch['place']."','".$fetch['district']."','".$fetch['pincode']."','".$fetch['stdcode']."','".$phone."',
			'".$cell."','".$fetch['fax']."','".$emailid."','".$fetch['website']."','".$fetch['type']."','".$fetch['category']."',
			'".$totalamount."','".$productlist."','','','".$currentdealer."','".$productpurchasetype."',
			'".$purchaseremarks."','".$totalproductpricearray."','".$productslno."','".$productusagetype."')";

			$result2 = runmysqlquery($query2);
			$query3 = "select max(slno) As slno from pre_online_purchase";
			$result3 = runmysqlqueryfetch($query3);
			$lastslno = $result3['slno'];
			
			rslcreatecookie('cuslastslno',$lastslno);
			echo(json_encode('1^Customer Record Saved Successfully^'.$lastslno));
		}
	}
	break;
}
function getpurchasetype($productlist)
{
	$splitvalue = str_replace('#',',',$productlist);
	$selectedproduct = explode(',',$splitvalue);
	$productcount = count($selectedproduct);
	for($i=0; $i<$productcount; $i++)
	{
		if($i > 0)
			$productpurchasearray .= ',';
		$productpurchasetype = 'updation';
		$productpurchasearray  .= $productpurchasetype;
	}

	return $productpurchasearray; 
}
?>