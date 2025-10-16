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

$submittype = $_POST['submittype'];
#echo $submittype;
switch($submittype)
{
	
	case "statusstrip":	
	{
		//Count of TOTAL ACTIVE || DELETED || DISABLED Email ACCOUNTS 	
		$query = "SELECT COUNT(*) AS total, SUM(deleted = 'YES') AS deleted, SUM(disable = '1') AS disabled, SUM(deleted = 'NO') AS Active FROM email_acc_record;";
		$fetch = runmysqlqueryfetch($query);
		
		//Count of Grouphead 
		$query1 = "SELECT COUNT(grouphead) AS grouphead FROM email_grouphead;";
		$fetch1 = runmysqlqueryfetch($query1);
		
		$active = $fetch['Active'] - $fetch['disabled'] ;
		$total = $fetch['total'] + $fetch['deleted'] + $fetch['disabled'] + $fetch['Active'] + $fetch1['grouphead'] ;
		
		echo('1^'.$fetch['total'].'^'.$active.'^'.$fetch['disabled'].'^'.$fetch['deleted'].'^'.$fetch1['grouphead']);
	}
	break;


	case "filter":
	{
		$fromdate = changedateformat('01-04-2000');
		$todate = changedateformat(date('d-m-Y'));
		$forwarderselect = $_POST['forwarderselect'];
		$groupheadselect = $_POST['groupheadselect'];
		$dropactivestatus = $_POST['dropactivestatus']; 
		$dropterminatedstatus = $_POST['dropterminatedstatus']; 
		$dropdisablestatus = $_POST['dropdisablestatus']; 
		$searchtext = $_POST['searchtext'];
		$subselection = $_POST['subselection'];
		$leadsource = $_POST['leadsource'];
		$leadsourcelist = ($leadsource == "")?"":("AND email_acc_record.cid = '".$leadsource."'");

		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		if($showtype == 'all')
		{ $limit = 2000;}
		else
		{ $limit = 10; }
		
		if($startlimit == '')
		{
		  $startlimit = 0;
		  $slnocount = 0;
		}
		else
		{
		  $startlimit = $slnocount ;
		  $slnocount = $slnocount;
		}
		if($searchtext <> '')
		{
			switch($subselection)
			{
				case "empid":
					$searchpiece = ($searchtext == '')?"":("AND email_acc_record.employeeid like '%".$searchtext."%'");
				break;
				
				case "employeename":
					$searchpiece = ($searchtext == '')?"":("AND email_acc_record.employee like '%".$searchtext."%'");
				break;
				
				case "username": 
					$searchpiece = ($searchtext == '')?"":("AND email_acc_record.email like '%".$searchtext."%'");
				break;
				
				case "forwarder":
					$searchpiece = ($searchtext == '')?"":("AND email_acc_record.forwards like '%".$searchtext."%'");
				break;
				
				case "grouphead":
					$searchpiece = ($searchtext == '')?"":("AND email_acc_record.grouphead like '%".$searchtext."%'");
				break;
				
				case "category":
					$searchpiece = ($searchtext == '')?"":("AND email_mas_category.category like '%".$searchtext."%'");
				break;
			}
		}
		else
		{
			$searchpiece = '';
		}
		$groupheadpiece = ($groupheadselect == '')?"":("AND email_grouphead.id = '".$groupheadselect."'");
		$datetimepiece = "substring(email_acc_record.createddate,1,10) between '".$fromdate."' AND '".$todate."'"; 			 
		$fowardslist = ($forwarderselect == "")?"":("AND email_acc_record.forwards = '".$forwarderselect."'");
		$activestatuspiece = ($dropactivestatus == 'true')?("AND email_acc_record.deleted = 'NO' AND email_acc_record.disable = '0'") : '';
		$terminatedstatuspiece = ($dropterminatedstatus == 'true')?("AND email_acc_record.deleted = 'YES'") : '';
		$disablestatuspiece = ($dropdisablestatus == 'true')?("AND email_acc_record.disable = '1'") : '';
	
		if(checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
		{
			$query = "select distinct email_acc_record.employeeid,email_acc_record.employee,
			email_acc_record.email,email_grouphead.grouphead,email_acc_record.forwards,
			email_acc_record.department,email_acc_record.createddate,email_mas_category.category,
			email_acc_record.emailid,email_acc_record.disable ,email_acc_record.deleted,
			email_acc_record.disabledate,email_acc_record.deleteddate
			from email_acc_record  
			left join email_grouphead on email_acc_record.grouphead = email_grouphead.id 
			left join email_mas_category on email_acc_record.cid = email_mas_category.cid 
			where ".$datetimepiece." ".$activestatuspiece." ".$terminatedstatuspiece." ".$disablestatuspiece." ".$groupheadpiece." ".$searchpiece." ".$fowardslist." ".$leadsourcelist." ORDER BY emailid DESC";
			
			
			if($slnocount == '0')
			{
				if($dropactivestatus == 'true')
				{$emaildate = '<td nowrap="nowrap"  class="tdborderlead">Created Date</td>';}
				else if($dropterminatedstatus == 'true')
				{$emaildate = '<td nowrap="nowrap"  class="tdborderlead">Deleted Date</td>';}
				else if($dropdisablestatus == 'true')
				{$emaildate = '<td nowrap="nowrap"  class="tdborderlead">Disable Date</td>';}
				
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				
				//Write the header Row of the table
				$grid .= '<tr class="gridheader">
				<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
				<td nowrap="nowrap"  class="tdborderlead">Employee Name</td>
				<td nowrap="nowrap"  class="tdborderlead">Email ID</td>
				<td nowrap="nowrap"  class="tdborderlead">Grouphead</td>
				<td nowrap="nowrap"  class="tdborderlead">Forwarder</td>
				<td nowrap="nowrap"  class="tdborderlead">Department</td>
				'.$emaildate.'
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
					//class for delete
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

					if($fetch[10] <> 'NO')
					{
						##For Deleted Employee##
						$class = "gridrow disable"; 
						$tabletr = '<tr id="1" bgcolor='.$color.' onclick="javascript:window.location.href=\'./index.php?a_link=saralmail_disable&emailid='.$fetch[8].'\'"  class="'.$class.'">';
					}
					//class for Disable
					else if($fetch[9] == '1')
					{
						##For Disabled Employee##
						$class ="gridrow showinweb"; 
						$tabletr = '<tr id="1" bgcolor='.$color.' onclick="javascript:window.location.href=\'./index.php?a_link=saralmail_disable&emailid='.$fetch[8].'\'"  class="'.$class.'">';
					}
					else
					{ 
						##For Active Employee##
						$class = 'gridrow';
						$tabletr = '<tr id="1" bgcolor='.$color.' onclick="javascript:window.location.href=\'./index.php?a_link=saralmail&emailid='.$fetch[8].'\'"  class="'.$class.'">';
					}
					
					## Assign Class For Category##
					if($fetch[7]=='Relyonite')
					{ $categoryclass = 'relyonite';}
					else if($fetch[7]=='Dealer')
					{ $categoryclass = 'dealer ';}
					else if($fetch[7]=='Management')
					{ $categoryclass = 'management';}
					else if($fetch[7]=='General')
					{ $categoryclass = 'general';}	
					else if($fetch[7]=='Consultant')
					{ $categoryclass = 'consultant';}	
					else if($fetch[7]=='Cilent')
					{ $categoryclass = 'cilent';}	
					else if($fetch[7]=='Specific')
					{ $categoryclass = 'specific';}	
					
					// made empID and Emplyname one 
					if($fetch[0] <> '')
					{$employeedetails = $fetch[1]. " | (". $fetch[0].")";}
					else{ $employeedetails = $fetch[1];}
					
					$grid .= $tabletr;
					
					$grid .= '<td nowrap="nowrap" class="tdborderlead">
					<div style="float:left">'.$slnocount.'</div><div class="'.$categoryclass.' category"></div>
					</td>';
					
					if($dropactivestatus == 'true')
					{$fetchdate = $fetch[6]; }
					else if($dropterminatedstatus == 'true')
					{$fetchdate = substr($fetch[12],0,10);}
					else if($dropdisablestatus == 'true')
					{$fetchdate = substr($fetch[11],0,10);}

					//Write the cell data
					for($i = 1; $i < count($fetch)-6; $i++)
					{
						if($i == 1)
							$grid .= "<td class='tdborderlead'>&nbsp;".$employeedetails."</td>";
						elseif($i == 6)
							$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformat($fetchdate)."</td>";
						else
							$grid .= "<td class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
					}
					//End the Row
					$grid .= '</tr>';
				}
				
			}
			//End of Table
			$grid .= '</tbody></table>';
			
			if($slnocount >= $fetchresultcount)
			{
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px">
				<tr>
					<td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td>
				</tr></table>';
			}
			else
			{
				$linkgrid .= '<table>
				<tr>
				<td >
					<div align ="left" style="padding-right:10px">
						<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'filter\');" style="cursor:pointer" class="resendtext">Show More Records >> </a>
						<a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'filter\');" class="resendtext1" style="cursor:pointer">
						<font color = "#000000">(Show All Records)</font></a></div>
				</td>
				</tr></table>';	
			}
			
			$k = 0;
			while($fetch2 = mysql_fetch_row($result))
			{
				
				for($i = 0; $i < count($fetch2); $i++)
				{
					if($i == 0)
					{
						if($k == 0)
						{
							$leadidarray .= $fetch2[$i];
						}
						else
						{
							$leadidarray .= '$'.$fetch2[$i];
						}
						$k++;
					}
					
				}
			}					  
			echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount.'|^|'.$leadidarray);
			
		}
		else
		{
			echo("2|^|"."Please Enter Valid Date");
		}
	
	}
	break;


}
?>
