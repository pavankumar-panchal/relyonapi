<?php
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
include('../inc/checksession.php');
include('../inc/checkpermission.php');

	// Given Variable for Serial number and Color for Table 
	$serial = 1;
	$odd=0;
	$class="";

$submittype = $_POST['submittype'];

switch($submittype)
{
	case "table":
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		if($showtype == 'all')
		{
		  $limit = 2000;
		}
		else
		{
			$limit = 10;
		}
		if($startlimit == '')
		{ 
		  $startlimit = 0;
		  $slnocount = 0;
		}
		else
		{
		  $startlimit = $slnocount;
		  $slnocount = $slnocount;
		}
    
            $query = "SELECT productname,producturl,id FROM saral_products order by productname";
    
             if($slnocount == '0')
			  {
				    $grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			  //Write the header Row of the table
				  $grid .= '<tr class="gridheader">
					<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
					<td nowrap="nowrap"  class="tdborderlead">Product</td>
					<td class="tdborderlead">Product URL</td>
					</tr>
				  <tbody>';
			  }
			  $result = runmysqlquery($query);
			  $fetchresultcount = mysql_num_rows($result);
			  
			  $addlimit = " LIMIT ".$startlimit.",".$limit.";";
			  
			  //$addlimit = "";
			  $query1 = $query.$addlimit;
			  $result1 = runmysqlquery($query1);
			  if($fetchresultcount > 0)
			  {
				  while($fetch = mysql_fetch_row($result1))
				  {
					  $slnocount++;
					 
					  //Begin a row
					 $grid .= $tabletr;
					 $i_n++;
					 $color;
					 if($i_n%2 == 0)
					 {
						$color = "#FFF";
					 }
					 else
					 {
						 $color = "#F4F4F4";
					 }
					 
					  $grid .= '<tr bgcolor='.$color.' class="gridrow" onclick="javascript:gridtoform('.$fetch[2].');">';
					  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
					
					//Write the cell data
					for($i = 0; $i < count($fetch)-1; $i++)
					{
						if($i == 1)
						{
							$grid .= "<td nowrap='nowrap' class='tdborderlead' style='text-align:center'>
							<a href = ".$fetch[$i]."><img src='../images/url_16.png' alt='url' title='Product - Folder URL'></a></td>";
						}
						else
							$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
					}
					  //End the Row
					  $grid .= '</tr>';
				  }
			  }
			  //End of Table
			$grid .= '</tbody></table>';
			
			if($slnocount >= $fetchresultcount)
			{
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr>
				<td bgcolor="#FFFFD2"><div align ="left"><font color="#FF4F4F">No More Records</font>
				</div><div></div></td></tr></table>';
			}
			else
			{
				$linkgrid .= '<table><tr><td >
				<div align ="left">
				<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\''.$command.'\');" style="cursor:pointer" class="resendtext">Show More Records >> </a>
				<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\''.$command.'\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			}
			  
			  $k = 0;
			  while($fetch2 = mysql_fetch_row($result))
			  {
				  
				  for($i = 0; $i < count($fetch2); $i++)
				  {
					if($i == 0)
						if($k == 0)
							$leadidarray .= $fetch2[$i];
						else
							$leadidarray .= '$'.$fetch2[$i];
					  $k++;
				  }
			  }					  
			  echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount.'|^|'.$leadidarray);
	}
	break;
		
	case "save":
	{
		if($_POST["form_prdid"] == "")
		{
				$form_product = $_POST['form_product'];
				$product_url = $_POST['product_url'];
		
						
				//check if the record is already present
				$query1 ="SELECT count(*) as prd FROM saral_products WHERE productname = '".$form_product."'";
				$resultfetch1 = runmysqlqueryfetch($query1);
				$prd = $resultfetch1['prd'];
				if($prd == 0)
				{
					$query2 = "INSERT INTO saral_products (productname, producturl) values('".$form_product."', '".$product_url."')";
					$result2 = runmysqlquery($query2);
					
					$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
					$datetime = gmdate("Y-m-d H:i:s");
					$activity = "Added New Product into Product Master Value: " .$form_product.", Product URL: ".$product_url ;
					$eventtype = '3';
					$message = "1^Product Inserted successfully. Thank you. . .!!";
					audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				}
				else
				{
					$message = "2^Sorry..!! Already Product available . . !!";
				}
		
		}
		else
		{	
				$form_product = $_POST['form_product'];
				$product_url = $_POST['product_url'];
				
				$form_prdid = $_POST["form_prdid"];
				$form_product = $_POST['form_product'];
				$product_url = $_POST['product_url'];
				
				$query_old ="SELECT * FROM saral_products WHERE id=".$form_prdid;
				$resultfetch1 = runmysqlqueryfetch($query_old);
				$prd_old = $resultfetch1['productname'];
				$prdurl_old = $resultfetch1['producturl'];
				
				$query = "UPDATE saral_products SET productname='".$form_product."', producturl='".$product_url."'
				WHERE id=" . $form_prdid;
				$result = runmysqlquery($query);
				$prd_new = $form_product;
				$prdurl_new = $product_url;
				
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Made Changes Product into Product Master Old Value: " .$prd_old . ", New Value: ".$prd_new. " old Product URL: " .$prdurl_old.", New Value: ".$prdurl_new;
				$eventtype = '4';
				$message = "1^Product Updated Into Product Master successfully. Thank you. . .!!";
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
			
		}
		echo($message);
	}
	break;


	case "delete":
	{	
		$prdid = $_POST['prdid'];
		
		if($prdid <> '')
		{
			
			$query = "DELETE FROM saral_products where id=".$prdid;
			$result = runmysqlquery($query);

			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Delete Product From Saral_Product table  prdid: ". $prdid;
			$eventtype = '5';
			$message = "1^Product Deleted From Product Master !";
			audit_trail($userid, $ipaddr, $datetime, $activity, $eventtype);
		}
		echo($message);
	}
	break;
	
	case "gridtoform" :
	{
			
		$form_prdid = $_POST['form_prdid'];
		
		$query1  = "select count(*) as count from saral_products where id =".$form_prdid;
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)   
		{
			$query = "SELECT *	from  saral_products where id = ".$form_prdid;
			$fetch = runmysqlqueryfetch($query);
			echo('1^'.$fetch['id'].'^'.$fetch['productname'].'^'.$fetch['producturl']);
		}
		else
		{
			echo('2^'.$form_slno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	
	}
	break;
}
?>