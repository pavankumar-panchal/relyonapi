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
			
                        /*------------------------------------check for taxes ---------------------------------*/
					    
					    
					                //$search_customer =  str_replace("-","",$fetch['customerid']);
					                
                                    $customer_details = "select inv_mas_customer.sez_enabled as sez_enabled,
                                    inv_mas_district.statecode as state_code,inv_mas_state.statename as statename,inv_mas_state.statecode as statecode
                                    ,inv_mas_state.state_gst_code as state_gst_code, inv_mas_customer.gst_no as customer_gstno from inv_mas_customer 
                                    left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
                                    left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
                                    where inv_mas_customer.slno = '".$user."'";
                            		
                                    $fetch_customer_details = runmysqlqueryfetch($customer_details);
                                    $customer_gstno = $fetch_customer_details['customer_gstno'];
                                    
                                    if($customer_gstno != "") {
                                        $customer_gst_code = substr($customer_gstno, 0, 2);
                                    }
                                    else {
                                        $customer_gst_code = $fetch_customer_details['state_gst_code'];
                                    }
					                
					                $customer_sez_enabled = $fetch_customer_details['sez_enabled'];
					    
					    /*-----------------------------------./Ends-------------------------------------------*/			

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
				
				//$servicetax = round($fetch1['updationprice'] * 0.14);
				//$sbtax = round($fetch1['updationprice'] * 0.005);
				
                //kktax
				    //$kktax = round($fetch1['updationprice'] * 0.005);
				//kktax ends

				$netamount = $fetch1['updationprice']; //+ $servicetax + $sbtax + $kktax;

				$totalamount += $netamount;

				$totalpricearray[] = $fetch1['updationprice'];
				
				$productarrayslno[] = $fetch1['slno'];
				$usagearraylist[] = $fetch1['usagetype'];
				
				$productslno = implode('*',$productarrayslno);
				$productusagetype = implode(',',$usagearraylist);
				$totalproductpricearray = implode('*',$totalpricearray);
			}
			
			
					            $pre_tax_total = $totalamount;
			/*-----------------------------------------Total Taxes Calculation ------------------------------------------*/
			
			        /*--------------------------------------GST Tax Rates ----------------------------------------*/

                       $gst_tax_date = strtotime('2017-07-01');
                       $invoicecreated_date = date('Y-m-d');
                       $querygst = "SELECT igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '".$invoicecreated_date."' AND to_date >= '".$invoicecreated_date."'";
                       $fetchrate = runmysqlqueryfetch($querygst);
                       
                       $igst_tax_rate = $fetchrate['igst_rate'];
                       $cgst_tax_rate = $fetchrate['cgst_rate'];
                       $sgst_tax_rate = $fetchrate['sgst_rate'];
                       $gst_type = '';
                    
                    /*---------------------./Ends------------------------------------------*/
			
			                            if($customer_sez_enabled == 'yes')
                                        {
										    $cgst_tax_amount = $sgst_tax_amount = $igst_tax_amount = '0.00';
										    $gst_type = 'SEZ';
                                        }
                                        else
                                        {
                                            if($customer_gst_code == '29')
                                            {
                                                $cgst_tax_amount = $totalamount * $cgst_tax_rate/100;
                                                $sgst_tax_amount = $totalamount * $sgst_tax_rate/100;
                                                
                                                $cgst_tax_amount = sprintf('%0.2f', $cgst_tax_amount);
                                                $sgst_tax_amount = sprintf('%0.2f', $sgst_tax_amount);
                                                $igst_tax_amount = '0.00';
                                                
                                                $totalamount = $totalamount + $cgst_tax_amount + $sgst_tax_amount + $igst_tax_amount;
    										    //$totalamount = $netamount;
    										    //$totalpricearray[] = $fetchresult[$i];
    										    $gst_type = 'CSGST';
                                            }
                                            else
                                            {
                                                $cgst_tax_amount = $sgst_tax_amount = '0.00';
                                                $igst_tax_amount = $totalamount * $igst_tax_rate/100;
                                                $igst_tax_amount = sprintf('%0.2f', $igst_tax_amount);
                                                
                                                $gst_type = 'IGST';
                                                
                                                $totalamount = $totalamount+ $cgst_tax_amount + $sgst_tax_amount + $igst_tax_amount;
                                            }
                                        }
                                        
			                        $withoutround = round($totalamount,2);
			                        $totalamount = round($totalamount);
			/*--------------------------------./*Ends----------------------------------------------*/
			
			//echo $totalproductpricearray;
			//echo $productslno;
			//$productusagetype = getusagetype($usagelist);
			$productpurchasetype = getpurchasetype($productlist);
			$currentdate = strtotime(date('Y-m-d'));
			
			$query2 = "Insert into pre_online_purchase(custreference,businessname,contactperson,address,
			place,district,pincode,stdcode,phone,cell,fax,emailid,website,type,category,amount,productcode,
			paymentdate,paymenttime,currentdealer,productpurchasetype,remarks,totalproductpricearray,products,usagetype
			,igst,cgst,sgst,gst_type,withoutround,pre_tax_total) 
			values('".$user."','".trim($fetch['businessname'])."','".$contactperson."','".addslashes($fetch['address'])."',
			'".$fetch['place']."','".$fetch['district']."','".$fetch['pincode']."','".$fetch['stdcode']."','".$phone."',
			'".$cell."','".$fetch['fax']."','".$emailid."','".$fetch['website']."','".$fetch['type']."','".$fetch['category']."',
			'".$totalamount."','".$productlist."','','','".$currentdealer."','".$productpurchasetype."',
			'".$purchaseremarks."','".$totalproductpricearray."','".$productslno."','".$productusagetype."',
			'".$igst_tax_amount."','".$cgst_tax_amount."','".$sgst_tax_amount."','".$gst_type."','".$withoutround."','".$pre_tax_total."')";

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