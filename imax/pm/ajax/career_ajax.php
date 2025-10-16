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
	$clas="";
	$datetime = gmdate("Y-m-d H:i:s");	
	$submittype=$_POST['submittype'];
	#$submit ="table";
	
switch($submittype)
{

	case "table":
	{$form_department = $_POST['form_department'];	
		if($form_department=="")
		{
			echo "<td class='general_text'> Select Department </td>";
		}
		else
		{
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			
			if($showtype == 'all')
			{
			  $limit = 200;
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
			
			#$form_department ='Saral TaxOffice';
			$query = "SELECT jobcode,experience,commitment,createddate,slno FROM saral_job_required WHERE department = '".$form_department."' order by slno desc";
		
			if($slnocount == '0')
			 {
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				  //Write the header Row of the table
					  $grid .= '<tr class="gridheader">
									<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
									<td nowrap="nowrap"  class="tdborderlead">Job Code</td>
									<td nowrap="nowrap"  class="tdborderlead">Experience</td>
									<td nowrap="nowrap"  class="tdborderlead">Commitment</td>
									<td nowrap="nowrap"  class="tdborderlead">Created Date</td>
							  </tr>';
			}
			 $result = runmysqlquery($query);
			$fetchresultcount = mysql_num_rows($result);
			
			$addlimit = " LIMIT ".$startlimit.",".$limit.";";
			
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
					$color = "#edf4ff";
				 }
				 else
				 {
					 $color = "#f7faff";
				 }
						
				 if($fetch['showinweb']==1)
				 {
					$color ="#006699";
				 }
				
				 $grid .= '<tr bgcolor='.$color.' class="gridrow"  onclick="javascript:gridtoform('.$fetch[4].');">';
				 $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
				
				//Write the cell data
				for($i = 0; $i < count($fetch)-1; $i++)
				{
					if($i == 3)
					{
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformatwithtime($fetch[$i])."</td>";
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
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			}
			else
			{
				$linkgrid .= '<table><tr><td >
				<div align ="left" style="padding-left:40px">
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
	}
	break;

	case "save":
	{
		$form_slno = $_POST["form_slno"];
		if($form_slno=="")
		{
		
				$form_department = rtrim($_POST['form_department']);
				
				$form_department1 = rtrim($_POST['form_department1']);
				
				$form_jobcode = rtrim($_POST['form_jobcode']);			
				
				$form_experience = rtrim($_POST['form_experience']);
				
				$form_qualification = rtrim($_POST['form_qualification']);
		
				$form_location = rtrim($_POST['form_location']);
		
				$form_commitment = trim($_POST['form_commitment']);
				
				$form_prof = addslashes($_POST['form_profile']);
				
				$form_profile = trim($form_prof);
				
				$form_attributes = addslashes($_POST['form_attributes']);
				
				$form_sl = addslashes($_POST['form_sl']);

				$form_vehicle = $_POST['form_vehicle']=="true" ? 1:0;
				
				$form_vacancies = addslashes($_POST['form_vacancies']);
				
				$form_age = addslashes($_POST['form_age']);
				
				$form_venue = addslashes($_POST['form_venue']);
				
				$show_web = $_POST['show_web']=="true" ? 1:0;
								
				$slno = $form_department;
				
				if($form_department == "others")
				{
					//inserting the data into table 
					$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM saral_job_required_depatment");
					$slnonumber = $query['slno'];

					$qryprd="INSERT INTO saral_job_required_depatment (slno, department) values('".$slnonumber."','".$form_department1."')";
					$result = runmysqlquery($qryprd);	
					
					$slno = $form_department1;
					#echo "Your Depatment Updated successfully. Thank you. . .!!";	
				}
					
					//inserting the data into table 
					$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM saral_job_required");
					$idnumber = $query['slno'];
				
					$query2 = "INSERT INTO saral_job_required (slno, department, jobcode, experience, qualification, location, commitment, profile, attributes, languages, vacancies, vehicle, age, venue, showinweb, createddate, createdip) values('".$idnumber."','".$slno."', '".$form_jobcode."','".$form_experience."', '".$form_qualification."', '".$form_location."', '".$form_commitment."', '".$form_profile."', '".$form_attributes."', '".$form_sl."', '".$form_vacancies."', '".$form_vehicle."', '".$form_age."', '".$form_venue."', '".$show_web."', '".$datetime."', '".$_SERVER['REMOTE_ADDR']."')";
					$result2 = runmysqlquery($query2);

				###########// Log update into audit #########
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				#$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Added NEW career  Into Job Requirement department: " .$slno .", experience: ".$form_experience . ", qualification: " .$form_qualification. ", location: ". $form_location . ", commitment: " .$form_commitment. ", profile: ". $form_profile. ", attributes: ". $form_attributes. ", languages: ". $form_sl. ", vacancies: ". $form_vacancies. ", vehicle: ". $form_vehicle. ", Age: ". $form_age. ", Venue: ". $form_venue. ", Activate Web: ".$show_web;
				
				$message = "1^Your Job Required Details inserted successfully. Thank you. . .!!";
				$eventtype = '48';
				/*$sub = "";
				$file_htm = "mailcontents/verfrom.htm";
				$file_txt = "mailcontents/verfrom.txt";
				send_mail($sub,$file_htm,$file_txt);*/
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				
		}
			
		else
		{
				$form_slno = $_POST["form_slno"];
					
				$form_department = $_POST['form_department'];
				
				$form_department1 = $_POST['form_department1'];
				
				$form_jobcode = $_POST['form_jobcode'];			
				
				$form_experience = $_POST['form_experience'];
				
				$form_qualification = addslashes($_POST['form_qualification']);
		
				$form_location = addslashes($_POST['form_location']);
		
				$form_commitment = trim($_POST['form_commitment']);
				
				$form_prof = addslashes($_POST['form_profile']);
				
				$form_profile = trim($form_prof);
				
				$form_attributes = addslashes($_POST['form_attributes']);
				
				$form_sl = addslashes($_POST['form_sl']);

				$form_vehicle = $_POST['form_vehicle']=="true" ? 1:0;
				
				$form_vacancies = addslashes($_POST['form_vacancies']);
				
				$form_age = addslashes($_POST['form_age']);
				
				$form_venue = addslashes($_POST['form_venue']);
				
				$show_web = $_POST['show_web']=="true" ? 1:0;
				
				$slno = $form_department;
				
				if($form_department == "others")
				{
					//inserting the data into table 
					$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS slno FROM saral_job_required_depatment");
					$slnonumber = $query['slno'];

					$qryprd="INSERT INTO saral_job_required_depatment (slno, department) values('".$slnonumber."','".$form_department1."')";
					$result = runmysqlquery($qryprd);	
					$slno = $form_department1;
					#echo "Your Depatment Updated successfully. Thank you. . .!!";	
				}
				
				$query_old ="SELECT * FROM saral_job_required WHERE slno=".$form_slno;
				$resultfetch1 = runmysqlqueryfetch($query_old);
				
				$depart_old = $resultfetch1['department'];
				$age_old = $resultfetch1['age'];
				$venue_old = $resultfetch1['venue'];
				$exper_old = $resultfetch1['experience'];
				$jobcode_old = $resultfetch1['jobcode'];
				$comm_old = $resultfetch1['commitment'];
				$attri_old = $resultfetch1['attributes'];
				$vehic_old = $resultfetch1['vehicle'];
				$loca_old = $resultfetch1['location'];
				$quali_old = $resultfetch1['qualification'];
				$prof_old = $resultfetch1['profile'];
				$lang_old = $resultfetch1['languages'];
				$vacan_old = $resultfetch1['vacancies'];
				$showeb_old = $resultfetch1['showinweb'];

				$query3 = "UPDATE saral_job_required SET department ='".$slno."', jobcode = '".$form_jobcode."', experience ='".$form_experience."', commitment ='".$form_commitment."', attributes='".$form_attributes."', createddate ='".$datetime."', vehicle ='".$form_vehicle."', location ='".$form_location."', qualification ='".$form_qualification."', profile='".$form_profile."', languages='".$form_sl."' , vacancies='".$form_vacancies."' , age ='".$form_age."' , venue ='".$form_venue."' ,showinweb =".$show_web."  
		WHERE slno=".$form_slno;
		
				$result3 = runmysqlquery($query3);
	
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Made Changes of Version Update 
				Old department Value: " .$depart_old . " : New department Value: ".$slno .",
				old experience value:".$exper_old ." : New experience value:".$form_experience. ",
				 Old commitment Value:".$comm_old. " : New commitment Value:".$form_commitment. ", 
				  Old Job Code Value:".$jobcode_old. " : New Job Code  Value:".$form_jobcode. ", 
				 Old attributes value:".$attri_old. " : New attributes value:".$form_attributes. ",
				  Old vehicle Value:" .$vehic_old." : New vehicle Value:".$form_vehicle.",
				   Old location :" .$loca_old." : New location :" .$form_location.", 
				   	Old qualification Value: " .$quali_old . " : New qualification Value: ".$form_qualification .",
				old profile value:".$prof_old ." : New profile value:".$form_profile. ",
				 Old languages Value:".$lang_old. " : New languages Value:".$form_sl. ", 
				 Old vacancies value:".$vacan_old. " : New vacancies value:".$form_vacancies. ",
				 Old Age:" .$age_old." : New Age:" .$form_age .",
				 Old Venue:" .$age_old." : New Venue:" .$form_venue .",
				   Old Activation Website:" .$showeb_old." : New Activation Website:" .$show_web;
				
				$message = "1^Your Job Required Changes has been Made successfully. Thank you. . .!!";
				$eventtype = '49';
				/*$subject = $form_product." Product Changes";
				$sub = $form_product." Product Version Update Changes Made ";
				$file_htm = "mailcontents/verfrom.htm";
				$file_txt = "mailcontents/verfrom.txt";
				send_mail($sub,$file_htm,$file_txt);*/
				
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype );
				
		}
		
		echo($message);
	}
	break;
		
				
	case "delete":
	{	
		if($_POST["slno"])
		{
			$slno = $_POST['slno'];
				
			$form_department = $_POST['form_department'];
						
			$slno = mysql_escape_String($slno);
			
			$query4 = "DELETE FROM saral_job_required WHERE slno='".$slno."'";
			
			$result4 = runmysqlquery($query4);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Deleted Job Required From Job Required  department: " .$form_department .", Slno: ". $slno;
			$eventtype = '51';
			
			echo "Your Job Required Details Deleted successfully. Thank you. . .!!";
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
		}
	}
	break;

	case "gridtoform" :
	{
			
		$form_slno = $_POST['form_slno'];
		
		$query1  = "select count(*) as count from saral_job_required where slno =".$form_slno;
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)   
		{
			$query = "SELECT *	from  saral_job_required where slno = ".$form_slno;
			$fetch = runmysqlqueryfetch($query);
		
			echo('1^'.$fetch['department'].'^'.$fetch['jobcode'].'^'.$fetch['experience'].'^'.$fetch['commitment'].'^'.
			$fetch['qualification'].'^'.$fetch['location'].'^'.$fetch['profile'].'^'.$fetch['attributes'].'^'.$fetch['venue'].'^'.$fetch['vacancies'].'^'.$fetch['languages'].'^'.$fetch['vehicle'].'^'.$fetch['showinweb'].'^'.$fetch['createddate'].'^'.$fetch['slno']);
		}
		else
		{
			echo('2^'.$form_slno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	
	}
	break;

}
?>