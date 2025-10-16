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

include_once('../functions/saralmailconfig.php');
#include_once('../inc/sendmail.php');
//ob_start("ob_gzhandler");
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'save':
	{
		if(!isset($_POST['form_reason'])){$_POST['form_reason']= '';}
		if(!isset($_POST['form_cid'])){$_POST['form_cid']= '';}
		$form_emailid = $_POST['form_emailid'];
		$form_email = $_POST['form_email'];
		$form_quota = $_POST['form_quota'];
		$form_password = $_POST['form_password'];
		$form_cid = $_POST['form_cid'];
		$form_createddate = $_POST['DPC_date'];
		$form_employee = $_POST['form_employee'];
		$form_employeeid = $_POST['form_employeeid'];
		$form_department = $_POST['form_department'];
		$form_forwards = $_POST['form_forwards'];
		$form_grouphead = $_POST['form_grouphead'];
		$form_requestedby = $_POST['form_requestedby'];
		$form_deleted = "NO";
		$form_deleteddate = "";
		$form_reason = $_POST['form_reason'];
		$form_remarks = $_POST['form_remarks'];
		$check_disable = $_POST['check_disable'] == "true" ? 1 : 0;
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$date = datetimelocal('d-m-Y');
		$createddate = date('Y-m-d').' '.date('H:i:s');
		
		// creating audit log 
		$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
		$datetime = gmdate("Y-m-d H:i:s");

				
		$query7 = "select grouphead, email, forwarder from email_grouphead  WHERE id = '".$form_grouphead."'";
		$fetchresult7 = runmysqlqueryfetch($query7);
		$form_head = $fetchresult7['grouphead'];
		$form_headmail = $fetchresult7['email'];
		$form_forwarder = $fetchresult7['forwarder'];

		if($form_emailid == "")
		{
			$responsearray1 = array();
			//echo("1^"."employee Record Saved Successfully");
			$responsearray1['errorcode'] = "3";
			$responsearray1['errormessage'] = "Kindly Select an Employee";

		}
		else
		{
				if($check_disable == 1)
				{
					$form_disablepass = rand();
					$disablelogin = cpanelresetpassword($form_email,$form_disablepass); 
					$disabledate = date('Y-m-d').' '.date('H:i:s');
					$disableip = $_SERVER['REMOTE_ADDR'];
					$form_disable = 'disabled';
					
					#########  Mailing Starts -----------------------------------
					$sub = "Official Email ID Disabled";
					$file_htm = "../mailcontents/disablemail.htm";
					$file_txt = "../mailcontents/disablemail.txt";
					if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
					{
						if($form_cid == '4')
						{
							$tosend = 'bhumika.p@relyonsoft.com';
							$ccto = 'ALL'; 	
						}
						elseif($form_cid == '3' || $form_cid == '6')
						{
							$tosend = 'hejalkumari.p@relyonsoft.com';
							$ccto = 'DEALER';
						}
						elseif($form_cid == '5' || $form_cid == '7' || $form_cid == '8' || $form_cid == '9')
						{
							$tosend = 'bhavesh@relyonsoft.com';
							$ccto = 'WEBMASTER';
						}
					}
					else
					{
						if($form_cid == '4')
						{
							$tosend = 'hemavathy.ba@relyonsoft.com,'.$form_headmail;
							$ccto = 'ALL'; 	
						}
						elseif($form_cid == '3' || $form_cid == '6')
						{
							$tosend = 'markcom@relyonsoft.com,'.$form_headmail;
							$ccto = 'DEALER';
						}
						elseif($form_cid == '5' || $form_cid == '7' || $form_cid == '8' || $form_cid == '9')
						{
							$tosend = 'webmaster@relyonsoft.com,'.$form_headmail;
							$ccto = 'WEBMASTER';
						}
						else
						{
							$tosend = 'webmaster@relyonsoft.com,hejalkumari.p@relyonsoft.com';
							$ccto = 'WEBMASTER';
						}
					}					
				}
				else
				{
					$form_disablepass = rand();
					$disablelogin = cpanelresetpassword($form_email,$form_disablepass); 
					$disabledate = date('Y-m-d').' '.date('H:i:s');
					$disableip = $_SERVER['REMOTE_ADDR'];
					$form_disable = 'enabled';
					
					#########  Mailing Starts -----------------------------------
					$sub = "Official Email ID Enabled";
					$file_htm = "../mailcontents/enablemail.htm";
					$file_txt = "../mailcontents/enablemail.txt";
					if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
					{
						if($form_cid == '4')
						{
							$tosend = 'bhumika.p@relyonsoft.com';
							$ccto = 'ALL'; 	
						}
						elseif($form_cid == '3' || $form_cid == '6')
						{
							$tosend = 'hejalkumari.p@relyonsoft.com';
							$ccto = 'DEALER';
						}
						elseif($form_cid == '5' || $form_cid == '7' || $form_cid == '8' || $form_cid == '9')
						{
							$tosend = 'bhavesh@relyonsoft.com';
							$ccto = 'WEBMASTER';
						}
					}
					else
					{
						if($form_cid == '4')
						{
							$tosend = 'hemavathy.ba@relyonsoft.com,'.$form_headmail;
							$ccto = 'ALL'; 	
						}
						elseif($form_cid == '3' || $form_cid == '6')
						{
							$tosend = 'markcom@relyonsoft.com,'.$form_headmail;
							$ccto = 'DEALER';
						}
						elseif($form_cid == '5' || $form_cid == '7' || $form_cid == '8' || $form_cid == '9')
						{
							$tosend = 'webmaster@relyonsoft.com,'.$form_headmail;
							$ccto = 'WEBMASTER';
						}
						else
						{
							$tosend = 'webmaster@relyonsoft.com,hejalkumari.p@relyonsoft.com';
							$ccto = 'WEBMASTER';
						}
					}
					##SENDIND MAIL##
					saral_mail($sub,$file_htm,$file_txt,$tosend,$ccto);
				}
			
				$msg2 = cpanel_forwarder($form_email,$form_forwards);

				$query2 = "UPDATE email_acc_record SET email='".$form_email."', password='".$form_password."', cid='".$form_cid."', 
				createddate='".$form_createddate."', employee='".$form_employee."', employeeid='".$form_employeeid."', 
				department='".$form_department."', forwards='".$form_forwards."', grouphead='".$form_grouphead."', 
				requestedby='".$form_requestedby."', disable='".$check_disable."', disabledate ='".$disabledate."',
				 disableip ='".$disableip."', disablepass = '".$form_disablepass."', deleted='".$form_deleted."',
				 reason='".$form_reason."', remarks='".$form_remarks."',lastmodifyby='".$userid."'
				WHERE emailid='".$form_emailid ."'";
				$result2 = runmysqlquery($query2);
				
				
				// Runing Audit Log
				$activity = "Details are update for ".$form_employee. " and email record no is ".$form_emailid;
				$eventtype = '41';
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				$msg = "Employee Record updated Successfully";
				
				$responsearray1 = array();
				//echo("4^"."Employee Record Saved Successfully");
				$responsearray1['errorcode'] = "1";
				$responsearray1['errormessage'] = "Employee Record updated Successfully";
			}
			//Checking The Forwarder is empty or not 
			if($form_forwards <> '')
			{	
				//inserting the data into table email_forwarder
				$query10 ="SELECT count(*) as emailfrd FROM email_forwarder WHERE emailid = '".$form_emailid."' and forwarder = '".$form_forwards."' and deleted = 'NO'";
				$resultfetch10 = runmysqlqueryfetch($query10);
				$emailfrd = $resultfetch10['emailfrd'];
				
				if($emailfrd == 0)
				{
					//inserting the data into table email_forwarder
					$query4 = runmysqlqueryfetch("SELECT (MAX(id) + 1) AS fid FROM email_forwarder");
					$fid = $query4['fid'];
					
					$query3 = "Insert into email_forwarder(id,emailid,forwarder,systemip,createddate,audituser) values ('".$fid."','".$form_emailid."','".$form_forwards."','".$ipaddress."','".date('Y-m-d')."','".$userid."');";
					$result3 = runmysqlquery($query3);
				}
			}
			
			echo(json_encode($responsearray1));
	}
	break;

	case 'delete':
	{
		$form_emailid = $_POST['form_emailid'];
		$form_remarks = $_POST['form_remarks'];
		$form_deleted = "YES";
		$form_deleteddate = date('Y-m-d').' '.date('H:i:s');
		$form_deletedip = $_SERVER['REMOTE_ADDR'];
		
		$query = "select employee, email, employeeid from email_acc_record  WHERE emailid = '".$form_emailid."'";
		$fetchresult = runmysqlqueryfetch($query);
		$form_email = $fetchresult['email'];
		$form_employee = $fetchresult['employee'];
		$form_employeeid = $fetchresult['employeeid'];
		
		$msg = cpaneldelete($form_email);
		
		$query1 = "UPDATE email_acc_record SET deleted='".$form_deleted."',deleteddate='".$form_deleteddate."',
		deletedip='".$form_deletedip."',disable='0',reason='".$form_reason."', remarks='".$form_remarks."',lastmodifyby='".$userid."'
		 WHERE emailid ='".$form_emailid ."'";
		$result1 = runmysqlquery($query1);
			
		$responsearray1['errorcode'] = '2';
		$responsearray1['errormessage'] = "Employee email Record deleted Successfully!!";
		echo(json_encode($responsearray1));
		
		// creating audit log 
		$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
		$datetime = gmdate("Y-m-d H:i:s");
		$activity = "Employee Email ID deleted ".$form_employee. " and email record no is ".$form_emailid;
		$eventtype = '42';
		audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
		
		$msg = "Employee Email ID deleted Successfully";
		
		#########  Mailing Starts -----------------------------------
		$sub = "Official Email ID Deleted";
		$file_htm = "../mailcontents/deletemail.htm";
		$file_txt = "../mailcontents/deletemail.txt";
		if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "etds-payroll-salary-software-india.com")
		{
			if($form_cid == '4')
			{
				$tosend = 'bhumika.p@relyonsoft.com';
				$ccto = 'ALL'; 	
			}
			elseif($form_cid == '3' || $form_cid == '6')
			{
				$tosend = 'hejalkumari.p@relyonsoft.com';
				$ccto = 'DEALER';
			}
			elseif($form_cid == '5' || $form_cid == '7' || $form_cid == '8' || $form_cid == '9')
			{
				$tosend = 'bhavesh@relyonsoft.com';
				$ccto = 'WEBMASTER';
			}
		}
		else
		{
			if($form_cid == '4')
			{
				$tosend = 'hemavathy.ba@relyonsoft.com,'.$form_headmail;
				$ccto = 'ALL'; 	
			}
			elseif($form_cid == '3' || $form_cid == '6')
			{
				$tosend = 'markcom@relyonsoft.com,'.$form_headmail;
				$ccto = 'DEALER';
			}
			elseif($form_cid == '5' || $form_cid == '7' || $form_cid == '8' || $form_cid == '9')
			{
				$tosend = 'webmaster@relyonsoft.com,'.$form_headmail;
				$ccto = 'WEBMASTER';
			}
			else
			{
				$tosend = 'webmaster@relyonsoft.com,hejalkumari.p@relyonsoft.com';
				$ccto = 'WEBMASTER';
			}
		}					
		##SENDIND MAIL##
		saral_mail($sub,$file_htm,$file_txt,$tosend,$ccto);
	}		
	break;
	
	
	case 'generateemployeelist':
	{
		#$query = "SELECT emailid,employee,employeeid FROM email_acc_record WHERE deleted='NO' ORDER BY employee";
		$query = "SELECT emailid,employee,employeeid,email FROM email_acc_record WHERE disable='1' ORDER BY employee";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 1;
		while($fetch = mysql_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .= $fetch['employee'].' | '.$fetch['email'].'^'.$fetch['emailid'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'getemployeecount':
	{
		$responsearray3 = array();
		$query1 = "SELECT emailid,employee,employeeid FROM email_acc_record WHERE disable='1' ORDER BY employee";
		$result1 = runmysqlquery($query1);
		$count = mysql_num_rows($result1);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	
	case 'employeedetailstoform':
	{
		$form_emailid = $_POST['form_emailid'];

		$query1 = "SELECT count(*) as count from email_acc_record where emailid = '".$form_emailid."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT email_acc_record.emailid, email_acc_record.employeeid, 
			email_acc_record.employee,email_acc_record.email, email_acc_record.department,
			email_acc_record.forwards, email_acc_record.cid as cid, email_acc_record.grouphead as grouphead ,
			email_acc_record.requestedby, email_acc_record.password, email_acc_record.createddate, 
			email_acc_record.remarks, email_acc_record.disable,email_acc_record.deleted,
			email_acc_record.disablepass,email_acc_record.reason 
			FROM email_acc_record 
			LEFT JOIN email_mas_category ON email_mas_category.cid = email_acc_record.cid 
			LEFT JOIN email_grouphead ON email_grouphead.id = email_acc_record.grouphead  
			where email_acc_record.emailid = '".$form_emailid."';";
			$fetch = runmysqlqueryfetch($query);
			
			$query1 ="SELECT * from email_acc_record where emailid = '".$form_emailid."'";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysql_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$contactarray .= '****';
				
				$email = $fetchres['email'];
				$employee = $fetchres['employee'];
				$department = $fetchres['department'];
				$grouphead = $fetchres['grouphead'];
				$requestedby = $fetchres['requestedby'];
				$emailid = $fetchres['emailid'];
				$createddate = $fetchres['createddate'];
				$remarks = $fetchres['remarks'];
				
				$contactarray = $emailid.'#'.$employee.'#'.$department.'#'.$grouphead.'#'.$requestedby.'#'.$email.'#'.$createddate.'#'.$remarks;
				
			}
			
				if($fetch['emailid'] == '')
				{
					$employee = '';
				}
				else
				{
					$employee = $fetch['employee'];
					echo($fetch['emailid'].'^'.$fetch['employee'].'^'.$fetch['employeeid'].'^'.$fetch['department'].'^'.
					$fetch['requestedby'].'^'.$fetch['grouphead'].'^'.$fetch['createddate'].'^'.$fetch['email'].'^'.
					$fetch['cid'].'^'.$fetch['forwards'].'^'.$fetch['remarks'].'^'.$fetch['password'].'^'.
					$fetch['disable'].'^'.$fetch['deleted'].'^'.$fetch['disablepass'].'^'.$fetch['reason'].'^1');
				}
			}
			else
			{
				echo($form_emailid.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'^2');
			}		
	}
	break;

	case "forwardertable":
	{
		$form_emailid = $_POST['form_emailid'];
		
	
		if($form_emailid=="")
		{
			echo "<td class='general_text'> Select Employee </td>";
		}
		else
		{
			$query = "SELECT COUNT(emailid) AS count FROM email_forwarder WHERE emailid = '".$form_emailid."'";
			$fetch = runmysqlqueryfetch($query);
		
			if($fetch['count'] > 0)
			{	
				if(!isset($_POST['startlimit'])){$_POST['startlimit'] = '';}
				if(!isset($_POST['slnocount'])){$_POST['slnocount'] = '';}
				if(!isset($_POST['showtype'])){$_POST['showtype'] = '';}
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
				$query = " SELECT email_forwarder.emailid,email_forwarder.forwarder,email_forwarder.createddate,
				saral_admins.fname as audituser, email_forwarder.deleted,email_forwarder.id
				FROM email_forwarder 
				LEFT JOIN saral_admins on saral_admins.adminid = email_forwarder.audituser 
				WHERE email_forwarder.emailid = '".$form_emailid."' order by id desc";
				
				if($slnocount == '0')
				{
					$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
					//Write the header Row of the table
					$grid .= '<tr class="gridheader">
					<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
					<td nowrap="nowrap"  class="tdborderlead">Email ID  </td>
					<td nowrap="nowrap"  class="tdborderlead">Forwarder </td>
					<td nowrap="nowrap"  class="tdborderlead">Date</td>
					<td nowrap="nowrap"  class="tdborderlead">Created By</td>
					<td nowrap="nowrap"  class="tdborderlead">&nbsp;</td>
					</tr>
					<tbody>';
				}
				$result = runmysqlquery($query);
				$fetchresultcount = mysql_num_rows($result);
				
				$addlimit = " LIMIT ".$startlimit.",".$limit.";";
				
				//$addlimit = "";
				$query1 = $query.$addlimit;
				$result1 = runmysqlquery($query1);
				$i_n = '';
				if($fetchresultcount > 0)
				{
					while($fetch = mysql_fetch_row($result1))
					{
						$slnocount++;
						//Begin a row
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
						
						if($fetch[4]=='YES')
						{
							$class ="showinweb";
							$tddel ="<td nowrap='nowrap' class='tdborderlead'>&nbsp;</td>";
						}
						else 
						{
							if($p_saralmail_forward == '1')
							{
								$tddel ="<td nowrap='nowrap' class='tdborderlead'>
								<a fid=".$fetch[5]."  onclick=\"delete_record('".$fetch[5]."','".$fetch[0]."','".$fetch[1]."');\" style='cursor:pointer; font-size:12px; color:#FF4040;font-family: Verdana, Geneva, sans-serif;font-weight:bold;'>&nbsp;Delete</a></td>"; 
							}
						}
						$grid .= '<tr bgcolor='.$color.' class="'.$class.'">';
						$grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
						
						
						//Write the cell data
						for($i = 0; $i < count($fetch)-1; $i++)
						{
							if($i == 2)
							{
								$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformat($fetch[$i])."</td>";
							}
							else if($i == 4)
							{
								$grid .= $tddel;
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
				$linkgrid = '';
				$leadidarray = '';
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
			else
			{ 
				$grid = '<b style="color:red; font-weight:bold;">NO RECORD FOUND</b>';
				$linkgrid = '';
				$fetchresultcount = '';
				$leadidarray = '';
				echo("2|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount.'|^|'.$leadidarray);
			}			
		}
	}
	break;

	case 'deleteforwarder':
	{
		$form_fid = $_POST['form_fid'];
		$form_emailid = $_POST['form_emailid'];
		$form_forwards = $_POST['form_forwards'];
		$form_deleted = "YES";
		$form_deleteddate = date('Y-m-d');
		$form_deletedip = $_SERVER['REMOTE_ADDR'];
		
		if($form_emailid <> '' || $form_fid <> '')
		{		
			$query = "select employee, email, employeeid from email_acc_record WHERE emailid = '".$form_emailid."'";
			$fetchresult = runmysqlqueryfetch($query);
			
			$form_email = $fetchresult['email'];
			$form_employee = $fetchresult['employee'];
			$form_employeeid = $fetchresult['employeeid'];
			
			
			/*echo "emailid is".$form_email;
			exit();*/
			if($form_email <> '' || $form_forwards <> '')
			{
				$msg = deleteforwarder($form_email,$form_forwards);

				$query1 = "UPDATE email_forwarder SET deleted='".$form_deleted."',deleteddate='".$form_deleteddate."',deleteip='".$form_deletedip."' WHERE id ='".$form_fid ."'";
				$result1 = runmysqlquery($query1);
							
				// creating audit log 
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$eventtype = '40';
						
				$activity = "Employees Forwarder detail deleted ".$form_forwards. " and email record no is ".$form_emailid;
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				
				$successmsg = "Employee's Forwarder detail deleted Successfully!!";
				
				$responsearray1['errorcode'] = '2';
				$responsearray1['erroralert'] = "Employee's Forwarder detail deleted Successfully!!";
				$responsearray1['errormessage'] = "Employee's Forwarder detail deleted Successfully!!";
				echo(json_encode($responsearray1));
			}
			else
			{
				$responsearray1['errorcode'] = '3';
				$responsearray1['erroralert'] = 'Forwader mail id is missing or Email ID is missing!!';
				$responsearray1['errormessage'] = "Forwader mail id is missing or Email ID is missing!!";
				echo(json_encode($responsearray1));
			}

		}
		else
		{
			$responsearray1['errorcode'] = '3';
			$responsearray1['erroralert'] = 'Employee details or Forwarder is missing!!';
			$responsearray1['errormessage'] = "Employee details or Forwarder is missing!!";
			echo(json_encode($responsearray1));
		}
	}
	break;


}
?>