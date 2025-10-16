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
            $currentdealer = $fetch['currentdealer'];
			//Contactperson fetch from contact details table
			
			
                        /*------------------------------------check for taxes ---------------------------------*/
					    
					    
					                $search_customer =  str_replace("-","",$fetch['customerid']);
					                
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
			from inv_contactdetails where RIGHT(customerid,5) = '".$last3chars."' group by customerid ";
			$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);

			$contactperson = trim(removedoublecomma($resultcontactdetails['contactperson']),',');
			$phone = trim(removedoublecomma($resultcontactdetails['phone']),',');
			$cell = trim(removedoublecomma($resultcontactdetails['cell']),',');
			$emailid = trim(removedoublecomma($resultcontactdetails['emailid']),',');
				
			$query1 = "select isap.dealerid as dealerid,isap.netamount as netamount, isap.igst as igst, 
			isap.cgst as cgst, isap.sgst as sgst, isap.amount as amount, isac.slno as isacslno 
			from inv_spp_amc_pinv isap inner join inv_spp_amc_customers isac on isap.invoiceno = isac.invoiceno 
			where isap.slno = '$invoiceslno'";
			$fetch1 = runmysqlqueryfetch($query1);
				
			$dealerid = $fetch1['dealerid'];
			$totalamount = $fetch1['netamount'];
			$isacslno = $fetch1['isacslno'];
			
			$amount_paid = $fetch1['amount'];
			$igst_tax = $fetch1['igst'];
			$cgst_tax = $fetch1['cgst'];
			$sgst_tax = $fetch1['sgst'];
			

$productslnos = $productusagetype = $productpurchasetype = $totalproductpricearray =  $totalproductcodearray = '';

$select = "select purchasetype,product_code,usage_type,new_amount from inv_spp_amc_customers_purchase where ispac_id ='".$isacslno."';";
$res_select = runmysqlquery($select);

                        while($res_sel_details = mysql_fetch_array($res_select))
                        {
                                    $product_code = $res_sel_details['product_code'];
                                    $purchasetype = $res_sel_details['purchasetype'];
                                    $usage_type = $res_sel_details['usage_type'];
                                    $new_amount = $res_sel_details['new_amount'];
                                    
                                    
                                    if($product_code == '893')
                                    {
                                       $product_code = 899;
                                    }
                                    if($product_code == '894')
                                    {
                                       $product_code = 900;
                                    }
                                    if($product_code == '895')
                                    {
                                       $product_code = 902;
                                    }
                                    if($product_code == '896')
                                    {
                                       $product_code = 903;
                                    }
                                    if($product_code == '897')
                                    {
                                       $product_code = 904;
                                    }
                                    if($product_code == '898')
                                    {
                                       $product_code = 905;
                                    }
                        
                        			$query_pro = "select slno from inv_relyonsoft_prices where productcode = '$product_code'";
                        			$fetch_pro = runmysqlqueryfetch($query_pro); 
                                    $pro_slno = $fetch_pro['slno'];
                        
                        				
                                    $productusagetype .= $usage_type.",";
                                    $productpurchasetype .= strtolower($purchasetype).",";
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
			

					            $pre_tax_total = $amount_paid; //equal to net amount
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
                                                
                                                //$totalamount = $totalamount + $cgst_tax_amount + $sgst_tax_amount + $igst_tax_amount;
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
                                                
                                                //$totalamount = $totalamount+ $cgst_tax_amount + $sgst_tax_amount + $igst_tax_amount;
                                            }
                                        }
                                        
			                        $withoutround = round($totalamount,2);
			                        $totalamount = round($totalamount);
			/*--------------------------------./*Ends----------------------------------------------*/			
			
/*			$query2 = "Insert into pre_online_purchase(custreference,businessname,contactperson,address,
			place,district,pincode,stdcode,phone,cell,fax,emailid,website,type,category,amount,productcode,
			paymentdate,paymenttime,currentdealer,productpurchasetype,remarks,totalproductpricearray,products,usagetype
			,igst,cgst,sgst,gst_type,withoutround,pre_tax_total) 
			values('".$user."','".trim($fetch['businessname'])."','".$contactperson."','".addslashes($fetch['address'])."',
			'".$fetch['place']."','".$fetch['district']."','".$fetch['pincode']."','".$fetch['stdcode']."','".$phone."',
			'".$cell."','".$fetch['fax']."','".$emailid."','".$fetch['website']."','".$fetch['type']."','".$fetch['category']."',
			'".$totalamount."','".$productlist."','','','".$currentdealer."','".$productpurchasetype."',
			'".$purchaseremarks."','".$totalproductpricearray."','".$productslnos."','".$productusagetype."',
			'".$igst_tax_amount."','".$cgst_tax_amount."','".$sgst_tax_amount."','".$gst_type."','".$withoutround."','".$pre_tax_total."')";*/
			
			
			$query2 = "Insert into pre_online_purchase(custreference,businessname,contactperson,address,
			place,district,pincode,stdcode,phone,cell,fax,emailid,website,type,category,amount,productcode,
			paymentdate,paymenttime,currentdealer,productpurchasetype,remarks,totalproductpricearray,products,usagetype
			,igst,cgst,sgst,gst_type,withoutround,pre_tax_total) 
			values('".$user."','".trim($fetch['businessname'])."','".$contactperson."','".addslashes($fetch['address'])."',
			'".$fetch['place']."','".$fetch['district']."','".$fetch['pincode']."','".$fetch['stdcode']."','".$phone."',
			'".$cell."','".$fetch['fax']."','".$emailid."','".$fetch['website']."','".$fetch['type']."','".$fetch['category']."',
			'".$totalamount."','".$productlist."','','','".$currentdealer."','".$productpurchasetype."',
			'".$purchaseremarks."','".$totalproductpricearray."','".$productslnos."','".$productusagetype."',
			'".$igst_tax."','".$cgst_tax."','".$sgst_tax."','".$gst_type."','".$withoutround."','".$pre_tax_total."')";


            //echo $query2;exit();
            
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