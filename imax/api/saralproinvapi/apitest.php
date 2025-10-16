<?php
header('Content-Type: application/json'); // Ensure the response is JSON

$invoicedate ='';
$roundoff_value = $addition_amount =  0;
$subcribeddate =$storedToken='';

include('functions/phpfunctions.php');

$storedToken = API_KEY;

// Get headers
$headers = getallheaders();
$authorizationHeader = getBearerToken();
//$authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;

// Get JSON body parameters
$inputJSON = file_get_contents("php://input");
$data = json_decode($inputJSON, TRUE);

$IMAXBILLNO = $_REQUEST['IMAXBILLNO']; 


// if(hash_equals($authorizationHeader, $storedToken))
// //if($authorizationHeader === "Bearer $decryptionKey")
// {
	if(!empty($IMAXBILLNO))
	{
		//$custrefno=substr($CUSTID,-5);
		$query = "select * from inv_invoicenumbers where inv_invoicenumbers.invoiceno = '".$IMAXBILLNO."';";
		$result= runmysqlquery($query);
		$count = mysql_num_rows($result);
		if($count > 0)
		{
			$fetch = runmysqlqueryfetch($query);
			$invoicedate = changedateformat(substr($fetch['createddate'],0,10));
			$offerdescription = explode('$',$fetch['offerdescription']);
			$discount = ($offerdescription) ? '0' : $offerdescription[2];
			$roundoff = 'false';
			$roundoff_value = '';

			$newproductfiles =$newprotypes = $newfiles= $productname =$productsplit=[];
			$nooffiles = 0;
			$productdesvalue = '';
			$productfilessplit = explode(',',$fetch['productfiles']);
			$productquantitysplit = explode(',',$fetch['productquantity']);
			$productssplit = explode('#',$fetch['products']);
			$description = explode('*',$fetch['description']);
			$products = explode('#',$fetch['products']);
			//echo "hi"; exit;
			for($i=0;$i<count($productfilessplit);$i++)
			{
				for($j=0;$j<$productquantitysplit[$i];$j++)
				{
					$query0 = "select producttype,productfiles,productcode from inv_mas_product where productcode = '".$productssplit[$i]."' and `group`='SARAL.PRO TAX'";
					$result0= runmysqlquery($query0);
					$procount = mysql_num_rows($result0);
					if($procount > 0)
					{
						$fetch0 = runmysqlqueryfetch($query0);
						$producttype = $fetch0['producttype'];

						$newproductfiles[] = $productfilessplit[$i];
						$newprotypes[] = $fetch0['producttype'];
						$newfiles[] = $fetch0['productfiles'];
						$productcode[] = $fetch0['productcode'];
					}
					//$productsplit[] = $products[$i];
				}
			}
			//print_r($productcode); print_r($products); echo "\n";
			//$result = array_intersect($products, $productcode); // Keep only values in both arrays

			//print_r($result);exit;

			$totalproductquantity = $taxableitemamt = $totalitemval=0;
			//Total product quantity
			for($i=0;$i<count($productquantitysplit);$i++)
			{
				$totalproductquantity += $productquantitysplit[$i];
			}

			$productcodes = array_intersect($products, $productcode);
			for($i=0;$i<$totalproductquantity;$i++)
			{
				if($newfiles[$i]!="" && $newprotypes[$i]!='Topup')
					$subcribeddate = changedateformat(date('Y-m-d', strtotime('+1 years -1 days', strtotime($invoicedate))));
				
				$descriptionsplit = explode('$',$description[$i]);
				

				if(!empty($fetch['igst']))
				{
					$igstamt = ($descriptionsplit[6] * 18)/100;
					$unititemigstamt = round($igstamt,2);
					$unititemcgstamt = $unititemsgstamt = 0;
				}
				else
				{
					$cgstamt = ($descriptionsplit[6] * 9)/100;
					$unititemcgstamt = round($cgstamt,2);
					$sgstamt = ($descriptionsplit[6] * 9)/100;
					$unititemsgstamt = round($sgstamt,2);
					$unititemigstamt = 0;
				}
				$totalitemvalue = $descriptionsplit[6] + $unititemigstamt + $unititemcgstamt + $unititemsgstamt;

				$totalUsers = '';

				$query0 = "select count(*) as procount from inv_mas_product where productcode = '".$productcodes[$i]."';";
				$fetch0= runmysqlqueryfetch($query0);
				if($fetch0['procount'] > 0)
				{
					echo $productcodes[$i]; 
					
					if($descriptionsplit[1]== 'Users Topup')
					{
						$totalUsers = $productfilessplit[$i];
						$totalClients =0;
					}
					else if($newprotypes[$i] =='Topup')
					{
						$totalUsers = 0;
						$totalClients = $productfilessplit[$i];
					}
					else
					{
						$totalUsers = 2;
						$totalClients = $productfilessplit[$i];
					}
						

					$productitemgrid[] = array( "totalUsers"=> $totalUsers,
					"totalTransactions"=> 0,
					"totalClients"=> $totalClients,
					"subscriptionStartdate"=> $invoicedate , 
					"subscriptionEnddate"=> $subcribeddate,
					"planId"=> $descriptionsplit[1],
					"amtDetails"=>[
						"grossAmt"=> $descriptionsplit[6],
						"taxableAmt"=> $descriptionsplit[6],
						"discount"=>0,
						"igst"=> $unititemigstamt,		//calculate GST based on per product
						"cgst"=> $unititemcgstamt,		//calculate GST based on per product
						"sgst"=> $unititemsgstamt,		//calculate GST based on per product
						"roundOff"=>0,
						"gstRate"=>18,
						"netAmt"=> round($totalitemvalue,2) 	//taxableamt + gst
					],
					);
				}
				
			}

			$query0 = "select inv_mas_dealer.emailid as dealeremailid,cell as dealercell from inv_mas_dealer where inv_mas_dealer.slno = '".$fetch['dealerid']."';";
			$fetch0 = runmysqlqueryfetch($query0);
			$dealeremailid = $fetch0['dealeremailid'];

			$addition_amount = $fetch['amount']+$fetch['igst']+$fetch['cgst']+$fetch['sgst'];
			$roundoff_value = ($fetch['netamount'])- ($addition_amount);
			
			if($roundoff_value != 0 || $roundoff_value != 0.00)
			{
				$roundoff = 'true';
			}
			if($roundoff == 'true')
			{
				$roundoff_value = number_format($roundoff_value,2);
			}

			$postInvData = [
				"financialYear"=> $fetch['year'],
				"customerId"=> $fetch['customerid'],
				"billNo"=>$fetch['invoiceno'],
				"billDate"=>$invoicedate,
				"spuserMailid"=>$dealeremailid,
				"branchId"=>$fetch['branch'],
				"subscriptionStartdate"=>$invoicedate,
				"remarks"=>$fetch['remarks'],
				"grossAmt"=>$fetch['netamount'],
				"discount"=>$discount,
				"taxableAmt"=> $fetch['amount'],
				"cgst"=> $fetch['cgst'],
				"sgst"=> $fetch['sgst'],
				"igst"=> $fetch['igst'],
				"roundOff" => $roundoff_value,
				"gstRate"=> 18,
				"netAmt"=> $fetch['netamount'],
			];
			$postInvData['childDetails'] = $productitemgrid;
			print_r($postIrnData);
			$response = $postInvData; 
		}
		else
			$response['errormsg'] = 'Invoice No does not exists!';
		
	}
	else
 		$response['errormsg'] = 'Data is Inavlid';
// }
// else
// 	$response['errormsg'] = 'Invalid API key';


echo json_encode($response);

?>
