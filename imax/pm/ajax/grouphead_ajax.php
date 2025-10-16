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
    
            $query = "SELECT grouphead,email,forwarder,department,id FROM email_grouphead order by grouphead";
    
             if($slnocount == '0')
			  {
				    $grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			  //Write the header Row of the table
				  $grid .= '<tr class="gridheader">
								<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
								<td nowrap="nowrap"  class="tdborderlead">Grouphead Name </td>
								<td nowrap="nowrap"  class="tdborderlead">Grouphead Email </td>
								<td nowrap="nowrap"  class="tdborderlead">Forwarder</td>
								<td nowrap="nowrap"  class="tdborderlead">Department</td>
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
					 
					  $grid .= '<tr bgcolor='.$color.' class="gridrow" onclick="javascript:gridtoform('.$fetch[4].');">';
					  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
					
					//Write the cell data
					for($i = 0; $i < count($fetch)-1; $i++)
					{
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
				<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a>
				<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
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
	
	}
	break;
		
		case "save":
		{
			$form_head = $_POST['form_head'];
			$form_forwarder = $_POST['form_forwarder'];
			$form_depart = $_POST['form_depart'];
			$form_id = $_POST["form_id"];

			if($form_id == "")
			{
				$form_email = $_POST['form_email'].'@relyonsoft.com';

				if($form_head == "")
				{
					$message = "2^Kindly, Enter Grouphead Name. . !!";
				}
				elseif($form_email == "")
				{
					$message = "2^Kindly, Enter Grouphead Email. . !!";
				}
				elseif($form_depart == "")
				{
					$message = "2^Kindly, Enter Department. . !!";
				}
				else
				{				
					//check if the record is already present
					$query1 ="SELECT count(*) as email FROM email_grouphead WHERE email = '".$form_email."'";
					$resultfetch1 = runmysqlqueryfetch($query1);
					$email = $resultfetch1['email'];
					if($email == 0)
					{
						
						$query2 = "INSERT INTO email_grouphead (grouphead, email, forwarder,department) 
						values('".$form_head."', '".$form_email."', '".$form_forwarder."', '".$form_depart."')";
						$result2 = runmysqlquery($query2);
						
						$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
						$datetime = gmdate("Y-m-d H:i:s");
						$activity = "Added Grouphead Details into Grouphead Name: " .$form_head.", Email : ".$form_email.",Forwarder : ".$form_forwarder;
						$message = "1^Grouphead Details Inserted successfully. Thank you. . .!!";
						$eventtype = '11';
						audit_trail($userid, $ipaddr, $datetime, $activity, $eventtype);
					}
					else
					{
						$message = "2^Sorry..!! Already Email ID available . . !!";
					}
				}
			}
			else
			{	
				$form_email = $_POST['form_email'];
							
				if($form_head == "")
				{
					$message = "2^Kindly, Enter Grouphead Name. . !!";
				}
				elseif($form_email == "")
				{
					$message = "2^Kindly, Enter Grouphead Email. . !!";
				}
				elseif($form_depart == "")
				{
					$message = "2^Kindly, Enter Department. . !!";
				}
				else
				{		
					$form_email = $_POST['form_email'];
					
					$query_old ="SELECT * FROM email_grouphead WHERE id=".$form_id;
					$resultfetch1 = runmysqlqueryfetch($query_old);
					$head_old = $resultfetch1['grouphead'];
					$email_old = $resultfetch1['email'];
					$forwader_old = $resultfetch1['forwarder'];
					
					$query = "UPDATE email_grouphead SET grouphead='".$form_head."', email='".$form_email."', 
					forwarder='".$form_forwarder."', department='".$form_depart."'
					WHERE id=" . $form_id;
					$result = runmysqlquery($query);
					$head_new = $form_head;
					$email_new = $form_email;
					$forwader_new = $form_forwarder;
					
					$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
					$datetime = gmdate("Y-m-d H:i:s");
					$activity = "Made Changes into Grouphead Name Old Value: " .$head_old . ", New Value: ".$head_new. " old Email: " .$email_old.", New Value: ".$email_new. " old Forwader: " .$forwader_old.", New Value: ".$forwader_new;
					$eventtype = '12';
					$message = "1^Grouphead Details Changed successfully. Thank you. . .!!";
					
					audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				}
			}
			echo($message);
		}
		break;


	case "delete":
	{	
		if($_POST['id'])
		{
			$form_id = $_POST['id'];
			$form_head = $_POST['form_head'];
			$sql = "delete from email_grouphead where id='".$form_id."'";
			$result4 = runmysqlquery($sql);

			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Delete Grouphead Details From Grouphead Master Value: " .$form_head ." prdid: ". $form_id;
			$message = "Product Deleted From Product Master !";
			$eventtype = '13';
			
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
		}
		echo($message);
	}
	break;
	
	case "gridtoform" :
	{
			
		$form_id = $_POST['form_id'];
		
		$query1  = "select count(*) as count from email_grouphead where id =".$form_id;
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)   
		{
			$query = "SELECT *	from  email_grouphead where id = ".$form_id;
			$fetch = runmysqlqueryfetch($query);
			echo('1^'.$fetch['id'].'^'.$fetch['grouphead'].'^'.$fetch['email'].'^'.$fetch['forwarder'].'^'.$fetch['department']);
		}
		else
		{
			echo('2^'.$form_id.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	
	}
	break;


}
?>