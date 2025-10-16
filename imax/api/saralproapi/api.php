<?php
//error_reporting(E_ALL);
// echo "hi"; 
// echo $_REQUEST['CARDID'];
// echo $_REQUEST['CUSTID'];
include('functions/phpfunctions.php');

$CUSTID = $_REQUEST['CUSTID']; 


if($CUSTID!= "")
{
	//$custrefno=substr($CUSTID,-5);
	$query = "select inv_mas_customer.slno as custslno,businessname,address,place,pincode,gst_no,statename from inv_mas_customer 
	left join inv_mas_district on inv_mas_customer.district = inv_mas_district.slno
	left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode
	where inv_mas_customer.customerid = '".$CUSTID."'";
	$result= runmysqlquery($query);
	$count = mysql_num_rows($result);
	if($count > 0)
	{
		$fetch  = runmysqlqueryfetch($query);
		$query1 ="SELECT cell,emailid from inv_contactdetails where customerid = '".$fetch['custslno']."' limit 1; ";
		$resultfetch = runmysqlqueryfetch($query1);

		$custname = $fetch['businessname'];
		$address = $fetch['address'];
		$place = $fetch['place'];
		$pincode = $fetch['pincode'];
		$emailid = $resultfetch['emailid'];
		$cellno = $resultfetch['cell'];
		$gst_no = $fetch['gst_no'];
		if(!empty($gst_no))
		{
			if(is_numeric($gst_no))
			{
				$querygst = "select gst_no from customer_gstin_logs where gstin_id =".$gst_no;
				$resultgst = runmysqlquery($querygst);
				$countgst = mysql_num_rows($resultgst);
				if($countgst > 0)
				{
					$fetchgst = runmysqlqueryfetch($querygst);
					$gst_no = $fetchgst['gst_no'];
				}
				else
					$gst_no = 'Not Registered Under GST';
			}
			else
				$gst_no = $fetch['gst_no'];
		}
		else
			$gst_no = 'Not Registered Under GST';
		
		$state = $fetch['statename'];
		$status = 1;
	}
	else
	$output = 'Customer Id is not matching with Imax Data';
    
 }
else
{
    $output = 'Please Provide Customer Id';
	 }

if($status ==1)
{
	$registration['Customer Name'] = $custname;
	$registration['Address'] = $address;
	$registration['Place'] = $place;
	$registration['Pincode'] = $pincode;
	$registration['Email Id'] = $emailid;
	$registration['Cell No'] = $cellno;
	$registration['GSTIN'] = $gst_no;
	$registration['State'] = $state;
}	 
else
	$registration['Status'] = $output;

echo json_encode($registration);
?>
