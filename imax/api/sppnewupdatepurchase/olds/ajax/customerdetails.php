<?php
include('../functions/phpfunctions.php');

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'customerdetails':
	{
		$customerid = $_POST['customerid'];
		$invoiceslno = $_POST['invoiceslno'];

                $last3chars = substr($customerid, -5);
		
		if($customerid != "")
		{
			$query = "SELECT * FROM inv_mas_customer WHERE customerid = '".$customerid."'";
			$fetch = runmysqlqueryfetch($query);
			$user = $fetch['slno'];
			//Contactperson fetch from contact details table

			$querycontactdetails = "select customerid, GROUP_CONCAT(inv_contactdetails.contactperson) as contactperson,
			GROUP_CONCAT(inv_contactdetails.phone) as phone, GROUP_CONCAT(inv_contactdetails.cell) as 
			cell,GROUP_CONCAT(inv_contactdetails.emailid) as emailid 
			from inv_contactdetails where RIGHT(customerid,5) = '".$last3chars."' group by customerid ";
			$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);

			$contactperson = trim(removedoublecomma($resultcontactdetails['contactperson']),',');
			$phone = trim(removedoublecomma($resultcontactdetails['phone']),',');
			$cell = trim(removedoublecomma($resultcontactdetails['cell']),',');
			$emailid = trim(removedoublecomma($resultcontactdetails['emailid']),',');
				
			$query1 = "select isap.dealerid as dealerid,isap.netamount as netamount, isac.slno as isacslno from inv_spp_amc_pinv isap inner join inv_spp_amc_customers isac on isap.invoiceno = isac.invoiceno where isap.slno = '$invoiceslno'";
			$fetch1 = runmysqlqueryfetch($query1);
				
			$dealerid = $fetch1['dealerid'];
			$totalamount = $fetch1['netamount'];
			$isacslno = $fetch1['isacslno'];

$productslnos = $productusagetype = $productpurchasetype = $totalproductpricearray =  $totalproductcodearray = '';

$select = "select purchasetype,product_code,usage_type,new_amount from inv_spp_amc_customers_purchase where ispac_id ='".$isacslno."';";
$res_select = runmysqlquery($select);


while($res_sel_details = mysql_fetch_array($res_select))
{
$product_code = $res_sel_details['product_code'];
$purchasetype = $res_sel_details['purchasetype'];
$usage_type = $res_sel_details['usage_type'];
$new_amount = $res_sel_details['new_amount'];

			$query_pro = "select slno from inv_relyonsoft_prices where productcode = '$product_code'";
			$fetch_pro = runmysqlqueryfetch($query_pro); 
                        $pro_slno = $fetch_pro['slno'];
				
$productusagetype .= $usage_type.",";
$productpurchasetype .= $purchasetype.",";
$totalproductpricearray .= $new_amount."*";
$productslnos .= $pro_slno."*";
$totalproductcodearray .= $product_code."#";

}

$productusagetype = substr($productusagetype,0,-1);
$productpurchasetype = substr($productpurchasetype,0,-1);
$totalproductpricearray = substr($totalproductpricearray,0,-1);
$productslnos = substr($productslnos,0,-1);
$productlist = substr($totalproductcodearray,0,-1);

			$currentdate = strtotime(date('Y-m-d'));
			
			$query2 = "Insert into pre_online_purchase(custreference,businessname,contactperson,address,
			place,district,pincode,stdcode,phone,cell,fax,emailid,website,type,category,amount,productcode,
			paymentdate,paymenttime,currentdealer,productpurchasetype,remarks,totalproductpricearray,products,usagetype) 
			values('".$user."','".trim($fetch['businessname'])."','".$contactperson."','".addslashes($fetch['address'])."',
			'".$fetch['place']."','".$fetch['district']."','".$fetch['pincode']."','".$fetch['stdcode']."','".$phone."',
			'".$cell."','".$fetch['fax']."','".$emailid."','".$fetch['website']."','".$fetch['type']."','".$fetch['category']."',
			'".$totalamount."','".$productlist."','','','".$dealerid."','".$productpurchasetype."',
			'".$purchaseremarks."','".$totalproductpricearray."','".$productslnos."','".$productusagetype."')";

			$result2 = runmysqlquery($query2);
			$query3 = "select max(slno) As slno from pre_online_purchase";
			$result3 = runmysqlqueryfetch($query3);
			$lastslnos = $result3['slno'];
			
			rslcreatecookie('cuslastslno',$lastslnos);
			echo(json_encode('1^Customer Record Saved Successfully^'.$lastslnos));
		}
	}
	break;
}
?>