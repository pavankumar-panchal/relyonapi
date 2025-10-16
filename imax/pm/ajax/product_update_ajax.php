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
		$form_product = $_POST['form_product'];
		#$form_product ='Saral TaxOffice';
		
		if($form_product=="")
		{
			echo "<td class='general_text'> Select Product </td>";
		}
		else
		{
			$query = "SELECT version,date,size,url,product,pid FROM saral_update WHERE product = '".$form_product."'
					order by pid desc LIMIT 1";
			
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader">
						<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
						<td nowrap="nowrap"  class="tdborderlead">Patch Version </td>
						<td nowrap="nowrap"  class="tdborderlead">Release Date</td>
						<td nowrap="nowrap"  class="tdborderlead">File Size</td>
						<td nowrap="nowrap"  class="tdborderlead">Patch URL</td>
					</tr>
					<tbody>';
			  
			  $result = runmysqlquery($query);
			  while($fetch = mysql_fetch_row($result))
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
				 
				  $grid .= '<tr bgcolor='.$color.' class="gridrow" onclick="javascript:gridtoform('.$fetch[5].');">';
				  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
				
				//Write the cell data
				for($i = 0; $i < count($fetch)-2; $i++)
				{	
					if($i == 3)
					{
						$grid .= "<td nowrap='nowrap' class='tdborderlead' style='text-align:center'>
						<a href = ".$fetch[$i]."><img src='../images/url_16.png' alt='url' title='Product - Patch URL'></a></td>";
					}
					else if($i == 1)
					{
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformat($fetch[$i])."</td>";
					}
					else
					{
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
					}
				}
				  //End the Row
				  $grid .= '</tr>';
			  }
			  //End of Table
			$grid .= '</tbody></table>';
			echo("1|^|".$grid);
		}
	}
	break;
	
	case "save":
	{
		if($_POST["form_pid"]=="")
		{
			$form_product = $_POST['form_product'];
			
			$form_patch = $_POST['form_patch'];
			
			$form_size = $_POST['form_size'];
			
			$DPC_date = $_POST['DPC_date'];
			
			$form_url = $_POST['form_url'];
			
			$show_web = $_POST['show_web']=="true" ? 1 : 0;
			
			//check if the record is already present
			$query1 ="SELECT count(*) as prdversion FROM saral_update WHERE version = '".$form_patch."' and product = '".$form_product."'";
			$resultfetch1 = runmysqlqueryfetch($query1);
			$prdversion = $resultfetch1['prdversion'];
			if($prdversion == 0)
			{
				$qry1="INSERT INTO saral_update (product, version, size, date, url) values('".$form_product."', '".$form_patch."', '".$form_size."', '".$DPC_date."', '".$form_url."')";
				$result = runmysqlquery($qry1);
	
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Added New Main Product Into Product Setup Product: " .$form_product .",  PatchVersion: " .$form_patch. ", File Size: ". $form_size . ", Patch URL: ". $form_url;
				$eventtype = '7';
				$message = "1^Your Main Product Update has been Updated successfully. Thank you. . .!!";
				
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				#send_mail($sub,$file_htm,$file_txt);
			}
			else
			{
				$message = "2^Sorry..!! A Main Product Update has already been Updated with this Patch Version. . .";
			}
		}
		else
		{
			$form_pid = $_POST["form_pid"];
			
			$form_product = $_POST['form_product'];
			
			$form_patch = $_POST['form_patch'];
			
			$form_size = $_POST['form_size'];
			
			$DPC_date = $_POST['DPC_date'];
			
			$form_url = $_POST['form_url'];
			
			$show_web = $_POST['show_web']=="true" ? 1 : 0;
			
			$query_old ="SELECT * FROM saral_update WHERE pid=".$form_pid;
			$resultfetch1 = runmysqlqueryfetch($query_old);
			$prd_old = $resultfetch1['product'];
			$patch_old = $resultfetch1['version'];
			$size_old = $resultfetch1['size'];
			$url_old = $resultfetch1['url'];
			#$show_web_old = $resultfetch1['showinweb'];
			
			$query3 = "UPDATE saral_update SET product ='".$form_product."', version ='".$form_patch."', size='".$form_size."', date ='".$DPC_date."', url ='".$form_url."'
			WHERE pid=".$form_pid;
			$result3 = runmysqlquery($query3);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Made Changes of Main Product Update Old Product Value: " .$prd_old . " : New Product Value: ".$form_product .", Old Patchversion Value:".$patch_old. " : New Patchversion Value:".$form_patch. ", Old File size value:".$size_old. " : New File size value:".$form_size. ", Old Patch URL Value:" .$url_old." : New Patch URL Value:".$form_url;
			$eventtype = '8';
			$message = "1^Your Main Product Update Changes has been Made successfully. Thank you. . .!!";
			
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
			#send_mail($sub,$file_htm,$file_txt);
		}
		echo($message);
	}
	break;
				
	case "delete":
	{
		if($_POST["pid"])
		{
			$pid = $_POST['pid'];
			
			$form_product = $_POST['form_product'];
			
			$form_patch = $_POST['form_patch'];
			
			$form_size = $_POST['form_size'];
			
			$form_url = $_POST['form_url'];
			
			$pid = mysql_escape_String($pid);
			
			$query4 = "DELETE FROM saral_update WHERE pid='".$pid."'";
			$result4 = runmysqlquery($query4);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Deleted Main Product setup Product: " .$form_product .", Slno: ". $pid. ", PatchVersion: " .$form_patch. ", File Size: ". $form_size . ", Patch URL: ". $form_url;
			$eventtype = '9';
			
			echo "Your Main Product Deleted successfully. Thank you. . .!!!";
			
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
		}
	}
	break;

	case "load":
	{
		$form_product = $_POST['form_product'];
		#$form_product ='Saral Tax Office';

		#$query3 = "Select Distinct patchversion From prdupdate where product = '".$form_product."' order by patchversion";
		//$query3 = "select verfrom as patch_verfrom from prdupdate where product='".$form_product."' and verfrom <>'' union
        //select  patchversion as patch_verfrom from prdupdate where product='".$form_product."' and patchversion <>''ORDER BY patch_verfrom desc";
        if($form_product == "Saral Accounts GSTN")
        {
            $query3 = "select verfrom as patch_verfrom from prdupdate where prdcode='410' and verfrom <>'' union
            select  patchversion as patch_verfrom from prdupdate where prdcode='410' and patchversion <>''ORDER BY patch_verfrom desc";
        }
        else if($form_product == "Saral Accounts")
        {
            $query3 = "select verfrom as patch_verfrom from prdupdate where prdcode!='410' and product='".$form_product."' and verfrom <>'' union
            select  patchversion as patch_verfrom from prdupdate where prdcode!='410' and product='".$form_product."' and patchversion <>''ORDER BY patch_verfrom desc";
        }
        else {
            $query3 = "select verfrom as patch_verfrom from prdupdate where product='" . $form_product . "' and verfrom <>'' union
            select  patchversion as patch_verfrom from prdupdate where product='" . $form_product . "' and patchversion <>''ORDER BY patch_verfrom desc";
        }
		$result_data = runmysqlquery($query3);
		$lastrow = mysql_num_rows($result_data);
		if($lastrow > 1)
		{
			echo('<option value=""> Select a Version From </option>');
		}
		$msg = "";
		$j=1;
		while($fetch = mysql_fetch_array($result_data))
		{
		
			$msg= $fetch['patch_verfrom'];
			if($j == 1)
			{
				echo('<option value="'.$msg.'" selected="selected">'.$msg.'</option>');
			}
			else
			{
				echo('<option value="'.$msg.'">'.$msg.'</option>');
			}
			$j++;
		}
	}
	break;
	
	case "prdurl":
	{	
		$form_product = $_POST['form_product'];
		$query5 = "select producturl from saral_products where productname='".$form_product."'";
		$result5 = runmysqlquery($query5);
		
		$fetch = mysql_fetch_array($result5);
		
		$msg = $fetch['producturl'];
		
		echo($msg);
	}
	break;
	
	case "gridtoform" :
	{
			
		$form_pid = $_POST['form_pid'];
		
		$query1  = "select count(*) as count from saral_update where pid =".$form_pid;
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)   
		{
			$query = "SELECT *	from  saral_update where pid = ".$form_pid;
			$fetch = runmysqlqueryfetch($query);
			echo('1^'.$fetch['pid'].'^'.$fetch['product'].'^'.$fetch['version'].'^'.$fetch['size'].'^'.$fetch['url'].'^'.$fetch['date']);
		}
		else
		{
			echo('2^'.$form_pid.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	
	}
	break;



}
		?>