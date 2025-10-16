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
	
	$submittype=$_POST['submittype'];
	#$submit ="table";
switch($submittype)
{

	case "table":
	{
		$fromdate = changedateformat('01-01-1990');
		$todate = changedateformat(date('d-m-Y'));
		$form_product = $_POST['form_product'];
		$command = $_POST['command'];
		
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		if($showtype == 'all')
		  $limit = 2000;
		else
		  $limit = 10;
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
		
		  
		  if($command == 'active')
		  {
				$query = "SELECT adddeddate,title,text,link,validtill,slno 
				FROM saral_flashnews WHERE  product = '".$form_product."' and disable = 'no' order by slno DESC";
		  }
		  else if($command == 'disabled')
		  {
				$query = "SELECT adddeddate,title,text,link,validtill,slno 
				FROM saral_flashnews WHERE  product = '".$form_product."' and disable = 'yes' order by slno DESC";
		  }
			  if($slnocount == '0')
			  {
				  $grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			  //Write the header Row of the table
				  $grid .= '<tr class="gridheader">
					<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
					<td nowrap="nowrap"  class="tdborderlead">Created Date</td>
					<td nowrap="nowrap"  class="tdborderlead">Title</td>
					<td nowrap="nowrap"  class="tdborderlead">Description</td>
					<td nowrap="nowrap"  class="tdborderlead">URL</td>
					<td nowrap="nowrap"  class="tdborderlead">Valid Till</td>
				</tr>
				  <tbody>';
			  }
			  $result = runmysqlquery($query);
			  $fetchresultcount = mysqli_num_rows($result);
			  
			  $addlimit = " LIMIT ".$startlimit.",".$limit.";";
			  
			  //$addlimit = "";
			  $query1 = $query.$addlimit;
			  $result1 = runmysqlquery($query1);
			  if($fetchresultcount > 0)
			  {
				  while($fetch = mysqli_fetch_row($result1))
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
					 
					  $grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="javascript:gridtoform('.$fetch[5].');">';
					  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
					
					//Write the cell data
					for($i = 0; $i < count($fetch)-1; $i++)
					{
						if($i == 0 || $i == 5)
						{
							$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformatwithtime($fetch[$i])."</td>";
						}
						else if($i == 3)
						{
							$grid .= "<td nowrap='nowrap' class='tdborderlead'><a href =".$fetch[$i]."><img src='../images/url_16.png' alt='url' title='Product - Patch URL'></a></td>";
						}
						else
						{
							$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
						}
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
				<td bgcolor="#FFFFD2">
				<div align ="left" style="padding-left:40px">
					<font color="#FF4F4F">No More Records</font><div></div></div></td></tr></table>';
			}
			else
			{
				$linkgrid .= '<table><tr><td >
				<div align ="left">
				<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\''.$command.'\');" style="cursor:pointer" class="resendtext">Show More Records >> </a>
				<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\''.$command.'\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			}
			  
			  $k = 0;
			  while($fetch2 = mysqli_fetch_row($result))
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
		  
		  /*}
		  else
		  {
			  echo("2|^|"."Please Enter Valid Date");
		  }*/
		

	}
	break;
	
	case "save":
	{
		if($_POST["form_flashid"]=="")
		{
			$form_product = $_POST['form_product'];
			
			$form_title = $_POST['form_title'];
			
			$form_link = $_POST['form_link'];
			
			$form_desc = $_POST['form_desc'];
			
			$DPC_date1 = $_POST['DPC_date1'];
			
			$DPC_date = $_POST['DPC_date'];
			
			$form_disable = $_POST['form_disable']=="true" ? 'yes' : 'no';
			
			$systemip = $_SERVER['REMOTE_ADDR'];
			
			//check if the record is already present
									
			$query2 = "INSERT INTO saral_flashnews (product, adddeddate, title, link, text, validtill, disable,createdby,systemip) 
			values('".$form_product."', '".$DPC_date1.' '.date('H:i:s')."','".$form_title."', '".$form_link."', '".$form_desc."', '".$DPC_date."', '".$form_disable."', '".$userid."', '".$systemip."')";
			$result2 = runmysqlquery($query2);

			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Added New Flash News Flash Update Update Product: " .$form_product .", Title: ".$form_title . ", Description: " .$form_desc. ", URL Path: ". $form_link . ", Valid Till: ". $DPC_date . ", Disable: ". $form_disable;
			$message = "1^Your Flash News Added successfully. Thank you. . .!!";
			$eventtype = '31';
			$sub = $form_product." Product Flash News Added";
			$file_htm = "../mailcontents/flash_news.htm";
			$file_txt = "../mailcontents/flash_news.txt";
			
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
			productmail($sub,$file_htm,$file_txt);
		}
		else
		{
			$form_flashid = $_POST["form_flashid"];
			
			$form_product = $_POST['form_product'];
			
			$form_link = $_POST['form_link'];
			
			$form_title = $_POST['form_title'];
			
			$form_desc = $_POST['form_desc'];
			
			$DPC_date1 = $_POST['DPC_date1'];
			
			$DPC_date = $_POST['DPC_date'];
			
			$form_disable = $_POST['form_disable']=="true" ? 'yes' : 'no';
			
			$datetime = gmdate("Y-m-d H:i:s");
			
			$systemip = $_SERVER['REMOTE_ADDR'];
										
			$query_old ="SELECT * FROM saral_flashnews WHERE slno=".$form_flashid;
			$resultfetch1 = runmysqlqueryfetch($query_old);
			$prd_old = $resultfetch1['product'];
			$title_old = $resultfetch1['title'];
			$link_old = $resultfetch1['link'];
			$desc_old = $resultfetch1['text'];
			$valid_old = $resultfetch1['validtill'];
			$disable_old = $resultfetch1['disable'];
			$create_old = $resultfetch1['adddeddate'];
			
						
			$query3 = "UPDATE saral_flashnews SET product ='".$form_product."', adddeddate='".$datetime."', title ='".$form_title."', link ='".$form_link."', text ='".$form_desc."', validtill='".$DPC_date."', disable ='".$form_disable."', lastmodifyby ='".$userid."', systemip ='".$systemip."'
		WHERE slno=".$form_flashid;
			$result3 = runmysqlquery($query3);
	
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Made Changes of Flash Update Old Product Value: " .$prd_old . " : New Product Value: ".$form_product .",old Title value:".$title_old ." : New Title value:".$form_title. ", Old URL Path value:".$link_old. " : New URL Path value:".$form_link.", Old Create Date value:".$create_old. " : New Create Date value:".$DPC_date1. ", Old Description Value:".$desc_old. " : New Description Value:".$form_desc. ", Old Valid Till value:".$valid_old. " : New Valid Till value:".$DPC_date. ", Old Disable Value:" .$disable_old." : New Disable Value:".$form_disable;
			$message = "1^Your Flash News Changes has been Made successfully. Thank you. . .!!";
			$eventtype = '32';
			
			$sub = $form_product." Product Flash News Changes Made";
			$file_htm = "../mailcontents/flash_news.htm";
			$file_txt = "../mailcontents/flash_news.txt";
			
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
			productmail($sub,$file_htm,$file_txt);
		}
		echo($message);
	}
	break;
				
	case "delete":
	{
		if($_POST["flashid"])
		{
			$flashid = $_POST["flashid"];
					
			$flashid = mysqli_escape_String($flashid);

			$query4 = "DELETE FROM saral_flashnews WHERE slno='".$flashid."'";
			
			$result = runmysqlquery($query4);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Deleted Flash News Product From Flash Update record Flashid: ". $flashid;
			$eventtype = '34';
			
			echo "Your Flash News Product Deleted successfully. Thank you. . .!!!";
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
		}
	}
	break;
	
	case "gridtoform" :
	{
			
		$form_flashid = $_POST['form_flashid'];
		
		$query1  = "select count(*) as count from saral_flashnews where slno =".$form_flashid;
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)   
		{
			$query = "SELECT *	from  saral_flashnews where slno = ".$form_flashid;
			$fetch = runmysqlqueryfetch($query);
			
			echo('1^'.$fetch['slno'].'^'.$fetch['product'].'^'.$fetch['adddeddate'].'^'.$fetch['text'].'^'.
			$fetch['title'].'^'.$fetch['link'].'^'.$fetch['validtill'].'^'.$fetch['disable']);
		}
		else
		{
			echo('2^'.$form_flashid.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	
	}
	break;
}
	
			?>