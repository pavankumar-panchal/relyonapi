<?php
//error_reporting(E_ALL);
// echo "hi"; 
// echo $_REQUEST['CARDID'];
// echo $_REQUEST['CUSTID'];
include('functions/phpfunctions.php');

$PINNO = $_REQUEST['PINNO'];
$CUSTID = decodevalue($_REQUEST['CUSTID']);

if($CUSTID!= "" && $PINNO!= "")
{
	$query0  = "select * from inv_mas_scratchcard where scratchnumber = '".trim($PINNO)."'"; 
	$result0 = runmysqlquery($query0);
	$countcard = mysql_num_rows($result0);
	if($countcard > 0)
	{
		$fetch0 = runmysqlqueryfetch($query0);
		$CARDID = $fetch0['cardid'];

		$query = "select count(*) as regcount from inv_customerproduct where cardid  = '".$CARDID."' and  
		customerreference = '".$CUSTID."'";
		$fetch = runmysqlqueryfetch($query);
		$regcount = $fetch['regcount'];
		if($regcount > 0)
		{
			$query1 = "select inv_customerproduct.date as regdate,blocked,cancelled,inv_dealercard.cuscardattacheddate as attachdate,cell from inv_customerproduct 
			left join `inv_mas_scratchcard` on `inv_customerproduct`.`cardid` = `inv_mas_scratchcard`.`cardid`
			left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
			left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
			where inv_customerproduct.`cardid` = '".$CARDID."'";
			$fetch1 = runmysqlqueryfetch($query1);

			$regdate = date('d-m-Y',strtotime($fetch1['regdate']));
			$attachdate = date('d-m-Y',strtotime($fetch1['attachdate']));
			$cellno = $fetch1['cell'];
			if($fetch1['blocked']!= 'no')
			{
				$output = 'Card is Registered and Blocked';
			}
			else if($fetch1['cancelled']!= 'no')
			{
				$output = 'Card is Registered and Cancelled';
			}
			else{	
				$output = 'Card is Registered';
			}
			
		}
		else
		{
		   $query2 = "select *,date as attachdate,cell  from inv_mas_scratchcard 
		   left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		   left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
		   where inv_mas_scratchcard.cardid = '".$CARDID."'";
			$fetch2 = runmysqlqueryfetch($query2);
			$attachdate = date('d-m-Y',strtotime($fetch2['attachdate']));
			$cellno = $fetch2['cell'];
			if($fetch2['blocked']!= 'no')
			{
				 $output = 'Card is Not yet Registered and Blocked';

			}
			else if($fetch2['cancelled']!= 'no')
			{
				$output = 'Card is Not yet Registered and Cancelled';
			}
			else {
				$output = 'Card is Not yet Registered';
			}
		}
 	}
else
{
		 $output = 'PIN NO is not matching with Imax Data.';
	}
    
 }
else
{
    $output = 'Invalid data';
	 }
$registration['Registration Date'] = $regdate;
$registration['Attached Date'] = $attachdate;
$registration['Registration Status'] = $output;
$registration['Cell No'] = $cellno;
echo json_encode($registration);
?>
