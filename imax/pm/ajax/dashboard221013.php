<?
session_start();
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

	case "saralmail":
	{
		$command = $_POST['command'];
		
		if($command=='active' || $command == 'disabled')
		{
			$commandpiece = ($command == "active")?(" email_acc_record.deleted ='NO' and email_acc_record.disable = '0'"):("  email_acc_record.deleted ='NO' and email_acc_record.disable = '1'");
			$column =($command == "active")?("createddate"):("disabledate");
			$titlecolumn =($command == "active")?("Created Date"):("Disabled Date");
		}
		else
		{	
			$commandpiece = ($command == "deleted")?(" email_acc_record.deleted ='YES'"):("");
			$column = 'deleteddate';
			$titlecolumn =($command == "deleted")?("Deleted Date"):("");
		}
		
		$query = "select email_acc_record.emailid,email_acc_record.email ,
		email_acc_record.".$column." ,email_acc_record.employee,
		email_acc_record.department,email_grouphead.grouphead as grouphead
		from email_acc_record
		left join email_grouphead on  email_grouphead.id = email_acc_record.grouphead 
		where".$commandpiece." order by email_acc_record.".$column." desc LIMIT 5";
		

		$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead" >';
		//Write the header Row of the table
		$grid .= '<tr class="gridheader">
		<td nowrap="nowrap"  class="tdborderlead">Sl No.</td>
		<td nowrap="nowrap"  class="tdborderlead">Email</td>
		<td nowrap="nowrap"  class="tdborderlead">'.$titlecolumn.'</td>
		<td nowrap="nowrap"  class="tdborderlead">Employee</td>
		<td nowrap="nowrap"  class="tdborderlead">Departmant</td>
		<td nowrap="nowrap"  class="tdborderlead">Grouphead</td>
		</tr>
		<tbody>';
		
		$result = runmysqlquery($query);
		
		
		while($fetch = mysql_fetch_array($result))
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
			$grid .= '<tr bgcolor='.$color.'>';
			$grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
			$grid .= '<td  class="tdborderlead">'.$fetch['email'].'</td>';
			$grid .= '<td nowrap="nowrap" class="tdborderlead">'.changedateformatwithtime($fetch[''.$column.'']).'</td>';
			$grid .= '<td  class="tdborderlead">'.$fetch['employee'].'</td>';
			$grid .= '<td  class="tdborderlead">'.$fetch['department'].'</td>';
			$grid .= '<td nowrap="nowrap" class="tdborderlead">'.$fetch['grouphead'].'</td>';
			$grid .= '</tr>';
			
		}
		//End of Table
		$grid .= '</tbody></table>';
		echo $grid;
	}
	break;
	
	
	case "flashnews":
	{
		$command = $_POST['command'];
		
		$status = ($command == 'active')?'no':'yes';
		
		$query = "select slno,product,adddeddate ,title,link from saral_flashnews 
		where disable = '".$status."' order by slno desc LIMIT 5";
		
		$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
		//Write the header Row of the table
		$grid .= '<tr class="gridheader">
		<td nowrap="nowrap"  class="tdborderlead">Sl No.</td>
		<td nowrap="nowrap"  class="tdborderlead">Product</td>
		<td nowrap="nowrap"  class="tdborderlead">Added Date</td>
		<td nowrap="nowrap"  class="tdborderlead">Title</td>
		<td nowrap="nowrap"  class="tdborderlead">Link</td>
		</tr>
		<tbody>';
		
		$result = runmysqlquery($query);
		while($fetch = mysql_fetch_array($result))
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
			$grid .= '<tr bgcolor='.$color.'>
			<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>
			<td nowrap="nowrap" class="tdborderlead">'.$fetch['product'].'</td>
			<td nowrap="nowrap" class="tdborderlead">'.changedateformatwithtime($fetch['adddeddate']).'</td>
			<td class="tdborderlead">'.$fetch['title'].'</td>
			<td nowrap="nowrap" class="tdborderlead"><a href ="'.$fetch['link'].'">
			<img src="../images/url_16.png" alt="url" title="Product - Patch URL"></a></td>
			</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		echo $grid;
	}
	break;
	
	
	case "jobcareer":
	{
		$command = $_POST['command'];
		$status = ($command == 'activecareer')?'1':'0';

		$query = "select slno,department,experience,qualification,location,createddate from saral_job_required 
		where showinweb = '".$status."' order by slno desc LIMIT 5";


		$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
		//Write the header Row of the table
		$grid .= '<tr class="gridheader">
					<td nowrap="nowrap"  class="tdborderlead">Sl No.</td>
					<td nowrap="nowrap"  class="tdborderlead">Department</td>
					<td nowrap="nowrap"  class="tdborderlead">Experience</td>
					<td nowrap="nowrap"  class="tdborderlead">Qualification</td>
					<td nowrap="nowrap"  class="tdborderlead">Location</td>
					<td nowrap="nowrap"  class="tdborderlead">Created Date</td>
				</tr>
				<tbody>';
		
		$result = runmysqlquery($query);
		
		
		while($fetch = mysql_fetch_array($result))
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
			  $grid .= '<tr bgcolor='.$color.'>
							<td  class="tdborderlead">'.$slnocount.'</td>
							<td  class="tdborderlead">'.$fetch['department'].'</td>
							<td  class="tdborderlead">'.$fetch['experience'].'</td>
							<td  class="tdborderlead">'.$fetch['qualification'].'</td>
							<td  class="tdborderlead">'.$fetch['location'].'</td>
							<td  class="tdborderlead">'.changedateformatwithtime($fetch['createddate']).'</td>
						</tr>';
		
		}
		//End of Table
		$grid .= '</tbody></table>';
		echo $grid;
	}
	break;
	
	case "verhotfixform":
	{	 
		 	$command = $_POST['command'];
			
			$updatetypepiece = ($command == 'version')?'versionupdate':'hotfix';
			$columnpiece = ($command == 'version')?'verfrom':'hotfixno';
			
			$query = "select product,patchversion ,reldate ,".$columnpiece.",patchurl from prdupdate 
			where updatetype = '".$updatetypepiece ."' and  showinweb ='1' order by slno desc LIMIT 5";
			

		  $grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
		  //Write the header Row of the table
		  $grid .= '<tr class="gridheader">
			<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
			<td nowrap="nowrap"  class="tdborderlead">Product Name</td>
			<td nowrap="nowrap"  class="tdborderlead">Product Code</td>
			<td nowrap="nowrap"  class="tdborderlead">Release Date</td>
			<td nowrap="nowrap"  class="tdborderlead">V.F. / H.No..</td>
			<td nowrap="nowrap"  class="tdborderlead">URL</td>
		  </tr>
		  <tbody>';
			 
			  $result = runmysqlquery($query);
			 
			 
				  while($fetch = mysql_fetch_array($result))
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
					
					  if($fetch['verfrom']=="")
					  {
						  $hot = "<strong>H. ".$fetch['hotfixno']."</strong>";
					  }
					  else
					  {
						$hot = "V.F. ". $fetch['verfrom'];
					  }
						  $grid .= '<tr bgcolor='.$color.'>';
						  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
						  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$fetch['product'].'</td>';
						  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$fetch['patchversion'].'</td>';
						  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.changedateformat($fetch['reldate']).'</td>';
						  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$hot.'</td>';
						  $grid .= '<td nowrap="nowrap" class="tdborderlead" align="center">
						  <a href = '.$fetch['patchurl'].'><img src="../images/url_16.png" alt="url" title="Product - Patch URL"></a></td>';
					 	  $grid .= '</tr>';
				  }
			  
			  //End of Table
			$grid .= '</tbody></table>';
			echo $grid;
	}
	break;
	
	case "latestactivity":
	{
		$submittype=$_POST['submittype'];

		$query = "(select adddeddate as adddeddate ,product as product ,disable as disable , 0 as hotfixno ,
		0 as verfrom , 0 as employee , 0 as deleted , 0 as updatetype ,0 as department 
		,0 as showinweb ,0 as disabledate from saral_flashnews where YEAR(adddeddate)= YEAR(CURDATE()) 
		and disable = 'no') 
		union 
		(select adddeddate as adddeddate ,product as product ,disable as disable , 0 as hotfixno ,
		0 as verfrom , 0 as employee , 0 as deleted , 0 as updatetype ,0 as department 
		, 0 as showinweb ,0 as disabledate from saral_flashnews 
		where YEAR(adddeddate)= YEAR(CURDATE()) and disable = 'yes') ";
		
		if($p_versionupdate == '1' || $p_hotfixupdate == '1')
		{
			$query .= "union 
			(select reldate as adddeddate ,product as product ,0 as disable ,hotfixno as hotfixno ,
			verfrom as verfrom , 0 as employee , 0 as deleted ,updatetype as updatetype ,
			0 as department , 0 as showinweb ,0 as disabledate from prdupdate where YEAR(reldate)= YEAR(CURDATE())) ";
		}
		if($p_saralmail_delete == '1' || $p_saralmail == '1')
		{
			$query .= "union 
			(select createddate as adddeddate ,0 as product ,0 as disable ,0 as hotfixno ,
			0 as verfrom , employee as employee , deleted as deleted ,0 as updatetype ,
			0 as department ,0 as showinweb, disabledate as disabledate  from email_acc_record 
			where YEAR(createddate)= YEAR(CURDATE()) and deleted = 'yes') 
			union 
			(select createddate as adddeddate ,0 as product ,0 as disable ,0 as hotfixno ,
			0 as verfrom , employee as employee , deleted as deleted , 0 as updatetype ,
			0 as department , 0 as showinweb, disabledate as disabledate  from email_acc_record 
			where YEAR(createddate)= YEAR(CURDATE()) and deleted = 'no')"; 
		}
		if($p_saralmail_disable == '1')
		{
			$query .= "union 
			(select disabledate as adddeddate ,0 as product ,disable as disable ,0 as hotfixno ,
			0 as verfrom , employee as employee , deleted as deleted , 0 as updatetype ,
			0 as department , 0 as showinweb, disabledate as disabledate  from email_acc_record 
			where YEAR(disabledate)= YEAR(CURDATE()) and disable = '1') 
			union 
			(select disabledate as adddeddate ,0 as product ,disable as disable ,0 as hotfixno ,
			0 as verfrom , employee as employee , deleted as deleted , 0 as updatetype ,
			0 as department , 0 as showinweb , disabledate as disabledate from email_acc_record 
			where YEAR(disabledate)= YEAR(CURDATE()) and disable = '0' and disabledate is not NULL 
			and deleted = 'no')";
		}
		if($p_career == '1')
		{
			$query .= "union 
			(select createddate as adddeddate ,0 as product ,0 as disable ,0 as hotfixno ,
			0 as verfrom , 0 as employee , 0 as deleted , 0 as updatetype ,
			department as department , showinweb as showinweb ,0 as disabledate from saral_job_required 
			where YEAR(createddate)= YEAR(CURDATE()) and showinweb = '1') 
			union 
			(select createddate as adddeddate ,0 as product ,0 as disable ,0 as hotfixno ,
			0 as verfrom , 0 as employee , 0 as deleted , 0 as updatetype ,
			department as department , showinweb as showinweb ,0 as disabledate from saral_job_required 
			where YEAR(createddate)= YEAR(CURDATE()) and showinweb = '0')"; 
		}
		$query .= "order by adddeddate desc limit 10";
		
		$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="10" cellpadding="5" id="gridtablelead" >';
		//Write the header Row of the table
		
		
		$result = runmysqlquery($query);
		
		$slno = 1;
		while($fetch = mysql_fetch_array($result))
		{
			//Begin a row
			$disable = $fetch['disable'];
			$hotfixno = $fetch['hotfixno'];
			$verfrom = $fetch['verfrom'];
			$deleted = $fetch['deleted'];
			$updatetype = $fetch['updatetype'];
			$showinweb = $fetch['showinweb'];
			$disdate = $fetch['disabledate'];
		
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
			
			##Flash News Disable##
			if($disable == 'yes')
			{
			  $grid .=	'<tr bgcolor='.$color.'>
						 
							<td class="tdborderrecent">Flash News Disabled for <b>"'.$fetch['product'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}
			##Flash News Active##
			else if($disable == 'no')
			{
			  $grid .=	'<tr bgcolor='.$color.'>
							
							<td class="tdborderrecent">Flash News Added for <b>"'.$fetch['product'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}
		
			##Hotfix update##
			else if($updatetype == 'hotfix')
			{
				 $grid .=	'<tr bgcolor='.$color.'>
				 			
							<td class="tdborderrecent">Hotfix Added for <b>"'.$fetch['product'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
							</tr>';
			}
			##version update##
			else if($updatetype == 'versionupdate')
			{
			  $grid .= '<tr bgcolor='.$color.'>
			  				
							<td class="tdborderrecent">Version Update Added for <b>"'.$fetch['product'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}
			##Official Email ID Deleted ##
			else if($deleted == 'YES' && $disable == '0' || $deleted == 'YES' && $disable == '1' )
			{
			  $grid .= '<tr bgcolor='.$color.'>
			  				
							<td  class="tdborderrecent">Official Email ID Deleted for <b>"'.$fetch['employee'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}
			##Official Email ID Created ##
			else if($deleted == 'NO' && $disable == '0' && ($disdate == '0000-00-00 00:00:00' || $disdate == '' || $disdate == 'NULL'))
			{
			  $grid .= '<tr bgcolor='.$color.'>
			  				
							<td  class="tdborderrecent">Official Email ID Created for <b>"'.$fetch['employee'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}
			##Official Email ID Enabled ##
			else if($deleted == 'NO' && $disable == '0' && ($disdate <> '0000-00-00 00:00:00' || $disdate <> '' || $disdate <> 'NULL'))
			{
			  $grid .= '<tr bgcolor='.$color.'>
			  				
							<td  class="tdborderrecent">Official Email ID Enabled for <b>"'.$fetch['employee'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}
			##Official Email ID Disabled ##
			else if($deleted == 'NO' && $disable == '1')
			{
			  $grid .= '<tr bgcolor='.$color.'>
			  				 
							<td  class="tdborderrecent">Official Email ID Disabled for <b>"'.$fetch['employee'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}

			
			##Job Openings Available ##
			else if($showinweb == '1')
			{
			  $grid .= '<tr bgcolor='.$color.'>
			  				
							<td  class="tdborderrecent">Job Openings Available for <b>"'.$fetch['department'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}
			##Job Openings  Un-Available  ##
			else if($showinweb == '0')
			{
			  $grid .= '<tr bgcolor='.$color.'>
			  				
							<td  class="tdborderrecent">Job Openings Un-Available for <b>"'.$fetch['department'].'"</b> on '.changedateformatwithtime($fetch['adddeddate']). '</td>
						</tr>';
			}

		
		}
		//End of Table
		$grid .= '</table>';
		echo $grid;
	}
	break;
}

?>