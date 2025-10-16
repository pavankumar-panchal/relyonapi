<?php
include('../functions/phpfunctions.php');
include('../inc/checksession.php');
include('../inc/checkpermission.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

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
			if($form_product == "Saral Accounts GSTN")
            {
			$query = "SELECT prdcode,patchversion ,size ,reldate,hotfixno,patchurl ,showinweb ,slno
					FROM prdupdate WHERE updatetype = 'hotfix' and  prdcode = '410' order by slno DESC";
            }
			else if ($form_product == "Saral Accounts")
            {
                $query = "SELECT prdcode,patchversion ,size ,reldate,hotfixno,patchurl ,showinweb ,slno
					FROM prdupdate WHERE updatetype = 'hotfix' and  prdcode!= '410' and product = '".$form_product."' order by slno DESC";
            }
			else {
                $query = "SELECT prdcode,patchversion ,size ,reldate,hotfixno,patchurl ,showinweb ,slno
					FROM prdupdate WHERE updatetype = 'hotfix' and  product = '".$form_product."' order by slno DESC";
            }
			  
			 if($slnocount == '0')
				  {
					  $grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				  //Write the header Row of the table
					  $grid .= '<tr class="gridheader">
						<td nowrap="nowrap"  class="tdborderlead">Sl No</td>
						<td nowrap="nowrap"  class="tdborderlead">Product Code</td>
						<td nowrap="nowrap"  class="tdborderlead">Patch Version</td>
						<td nowrap="nowrap"  class="tdborderlead">File Size</td>
						<td nowrap="nowrap"  class="tdborderlead">Release Date</td>
						<td nowrap="nowrap"  class="tdborderlead">HotFix No.</td>
						<td nowrap="nowrap"  class="tdborderlead">URL</td>
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
						 if($fetch[6] == 1)
						 {
							 $class ="showinweb";
							#$color ="#006699";
						 }else{$class =""; }
						  $grid .= '<tr bgcolor='.$color.' class="gridrow '.$class.'" onclick="javascript:gridtoform('.$fetch[7].');">';
						  $grid .= '<td nowrap="nowrap" class="tdborderlead">'.$slnocount.'</td>';
						
						//Write the cell data
						for($i = 0; $i < count($fetch)-2; $i++)
						{
							if($i == 3)
							{
								$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformat($fetch[$i])."</td>";
							}
							else if($i == 5)
							{
								$grid .= "<td nowrap='nowrap' class='tdborderlead'><a href = ".$fetch[$i]."><img src='../images/url_16.png' alt='url' title='Product - Patch URL'></a></td>";
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
					<td bgcolor="#FFFFD2"><div align ="left" style="padding-left:40px"><font color="#FF4F4F">No More Records</font>
					</div><div></div></td></tr></table>';
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

		if($_POST["form_slno"]=="")
		{
			$form_product = $_POST['form_product'];
			
			$form_productcode = $_POST['form_productcode'];
			
			$form_patch = $_POST['form_patch'];
			
			$form_filesize = $_POST['form_filesize'];
			
			$DPC_date = $_POST['DPC_date'];
			
			$form_url = $_POST['form_url'];
			
			$form_hotfix = $_POST['form_hotfix'];
			
			$check_web = $_POST['check_web']=="true" ? 1 : 0;
			
			if($form_productcode == 410)
			    $product = "Saral Accounts";
			else
                	    $product = $form_product;
			function update_ver($form_slno,$form_patch,$product)
			{
				$check_web='0';
				$qry5= "UPDATE prdupdate SET product ='".$product."', patchversion ='".$form_patch."', showinweb ='".$check_web."'  
	WHERE slno=".$form_slno;
				$result5 = runmysqlquery($qry5);
			}
				
			//check if the record is already present
			$query1 ="SELECT count(*) as prdversion FROM prdupdate WHERE patchurl = '".$form_url."'";
			$resultfetch1 = runmysqlqueryfetch($query1);
			$prdversion = $resultfetch1['prdversion'];
			if($prdversion == 0)
			{
			    if($form_productcode == 410)
				    $qry3 ="SELECT * FROM prdupdate WHERE prdcode='410' AND patchversion='".$form_patch."' AND updatetype = 'hotfix'";
			    else
                    $qry3 ="SELECT * FROM prdupdate WHERE prdcode!='410' and product='".$form_product."' AND patchversion='".$form_patch."' AND updatetype = 'hotfix'";
				$result3=runmysqlquery($qry3);
				$num = mysql_num_rows($result3);
				$last = 1;
				if($num >= 1)
				{
					while ($row = mysql_fetch_assoc($result3))
					{
						$form_slno=$row['slno'];
						#echo "last num".$row['patchversion'] ."<br/>";
						
						if($last >= $num)
						{
							$lastvariable = $row["hotfixno"];
							
							#echo "last variable".$lastvariable."<br/>";
							#$fixno = $lastvariable+1; 
							update_ver($form_slno,$form_patch,$product);
						
							$qry1="INSERT INTO prdupdate (product, prdcode, patchversion, size, reldate, patchurl, updatetype, hotfixno, showinweb) values('".$product."', '".$form_productcode."', '".$form_patch."', '".$form_filesize."', '".$DPC_date."', '".$form_url."', 'hotfix', '".$form_hotfix."', ".$check_web.")";
							$result = runmysqlquery($qry1);
						}// end if
						else
						{
							update_ver($form_slno,$form_patch,$product);
						}
						$last++;
					}//endwhile
				}
				else
				{
					$query2 = "INSERT INTO prdupdate (product, prdcode, patchversion, size, reldate, patchurl, updatetype, hotfixno, showinweb) values('".$product."', '".$form_productcode."', '".$form_patch."', '".$form_filesize."', '".$DPC_date."', '".$form_url."', 'hotfix', '".$form_hotfix."', ".$check_web.")";
					$result2 = runmysqlquery($query2);
				}
				/*$query2 = "INSERT INTO prdupdate (product, prdcode, patchversion, size, reldate, patchurl, updatetype, hotfixno, showinweb) values('".$form_product."', '".$form_productcode."', '".$form_patch."', '".$form_filesize."', '".$DPC_date."', '".$form_url."', 'hotfix', '".$form_hotfix."', ".$check_web.")";
				$result2 = queryhb($query2);*/
				
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Added New Hotfix Product Into Hotfix Update Product: " .$form_product .", Productcode: ".$form_productcode . ", PatchVersion: " .$form_patch. ", File Size: ". $form_filesize . ", Patch URL: ". $form_url. ", Hotfix No: " .$form_hotfix. ", Activate Web: ".$check_web;
				$message = "1^Your Hotfix Update has been Updated successfully. Thank you. . .!!";
				$eventtype = "26";
				
				$sub = $form_product." Product Hotfix Added";
				$file_htm = "../mailcontents/hotfix.htm";
				$file_txt = "../mailcontents/hotfix.txt";
				
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				productmail($sub,$file_htm,$file_txt);
			}
			else
			{
				$message = "2^Sorry..!! A Hotfix Update has already been Updated with this Patch URL. . .";
			}
		}
		else
		{
			$form_slno = $_POST["form_slno"];
			
			$form_product = $_POST['form_product'];
			
			$form_productcode = $_POST['form_productcode'];
			
			$form_patch = $_POST['form_patch'];
			
			$form_filesize = $_POST['form_filesize'];
			
			$DPC_date = $_POST['DPC_date'];
			
			$form_url = $_POST['form_url'];
			
			$form_hotfix = $_POST['form_hotfix'];
			
			$check_web = $_POST['check_web']=="true" ? 1:0;
			if($form_productcode == 410)
			$product = "Saral Accounts";
			else
			$product = $form_product;
			
			$query_old ="SELECT * FROM prdupdate WHERE slno=".$form_slno;
			$resultfetch1 = runmysqlqueryfetch($query_old);
			$prd_old = $resultfetch1['product'];
			$code_old = $resultfetch1['prdcode'];
			$patch_old = $resultfetch1['patchversion'];
			$size_old = $resultfetch1['size'];
			$url_old = $resultfetch1['patchurl'];
			$hotfix_old = $resultfetch1['hotfixno'];
			$checkweb_old = $resultfetch1['showinweb'];
			
			$query3 = "UPDATE prdupdate SET product ='".$product."', prdcode ='".$form_productcode."', patchversion ='".$form_patch."', size='".$form_filesize."', reldate ='".$DPC_date."', patchurl ='".$form_url."', updatetype ='hotfix', hotfixno ='".$form_hotfix."', showinweb =".$check_web."
			WHERE slno=".$form_slno;
			$result3 = runmysqlquery($query3);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Made Changes of HotFix Update Old Product Value: " .$prd_old . " : New Product Value: ".$form_product .",old prdcode value:".$code_old ." : New prdcode value:".$form_productcode. ", Old Patchversion Value:".$patch_old. " : New Patchversion Value:".$form_patch. ", Old File size value:".$size_old. " : New File size value:".$form_filesize. ", Old Patch URL Value:" .$url_old." : New Patch URL Value:".$form_url.", Old Hotfix No:" .$hotfix_old." : New Hotfix No:" .$form_hotfix.", Old Activation Website:" .$checkweb_old." : New Activation Website:" .$check_web;
			$message = "1^Your Hotfix Changes has been Made successfully. Thank you. . .!!";
			$eventtype ='27';
			$sub = $form_product." Product Hotfix Changes Made";
			$file_htm = "../mailcontents/hotfix.htm";
			$file_txt = "../mailcontents/hotfix.txt";
			
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
			productmail($sub,$file_htm,$file_txt);
		}
		echo($message);
	}
	break;
				
	case "delete":
	{
		if($_POST["slno"])
		{
			$slno = $_POST['slno'];
			
			$form_product = $_POST['form_product'];
			
			$form_productcode = $_POST['form_productcode'];
			
			$form_patch = $_POST['form_patch'];
			
			$form_filesize = $_POST['form_filesize'];
			
			$form_url = $_POST['form_url'];
			
			$form_hotfix = $_POST['form_hotfix'];
			
			$slno = mysql_escape_String($slno);
			
			$query4 = "DELETE FROM prdupdate WHERE slno='".$slno."'";
			$result4 = runmysqlquery($query4);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Deleted Hotfix Product From Hotfix Update Product: " .$form_product .", Slno: ". $slno. ", Productcode: ".$form_productcode . ", PatchVersion: " .$form_patch. ", File Size: ". $form_filesize . ", Patch URL: ". $form_url. ", Hotfix No: " .$form_hotfix;
			$eventtype = '29';
			echo "Your Hotfix Product Deleted successfully. Thank you. . .!!!";
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
		}
	}
	break;

	case "load":
	{
		$form_product = $_POST['form_product'];
		#$form_product ='Saral Tax Office';

		#$query3 = "Select Distinct patchversion From prdupdate where product = '".$form_product."' order by patchversion";
        if($form_product == "Saral Accounts GSTN")
        {
            $query3 = "select verfrom as patch_verfrom from prdupdate where prdcode='410' and verfrom <>'' union
            select  patchversion as patch_verfrom from prdupdate where prdcode='410' and patchversion <>''ORDER BY patch_verfrom desc";
        }
        else if($form_product == "Saral Accounts")
        {
            $query3 = "select verfrom as patch_verfrom from prdupdate where prdcode!='410' and verfrom <>'' union
            select  patchversion as patch_verfrom from prdupdate where prdcode!='410' and product='".$form_product."' and patchversion <>''ORDER BY patch_verfrom desc";
        }
        else {
		$query3 = "select verfrom as patch_verfrom from prdupdate where product='".$form_product."' and verfrom <>'' union
select  patchversion as patch_verfrom from prdupdate where product='".$form_product."' and patchversion <>''ORDER BY patch_verfrom desc";
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

	case "prdcode":
	{
		$form_product = $_POST['form_product'];
		#$qry3 ="SELECT * FROM prdupdate WHERE product='".$form_product."'";
        if($form_product == "Saral Accounts GSTN")
		    $qry3 ="SELECT * FROM prdupdate WHERE prdcode ='410' order by prdcode desc limit 1";
        else if($form_product == "Saral Accounts")
            $qry3 ="SELECT * FROM prdupdate WHERE prdcode!='410' and product ='".$form_product."' order by prdcode desc limit 1";
        else
		$qry3 ="SELECT * FROM prdupdate WHERE product ='".$form_product."' order by prdcode desc limit 1";
		$result3=runmysqlquery($qry3);
		$num = mysql_num_rows($result3);
		#$last = 1;
		#$ver = 1;
		$msg ="";
		if($num == 1)
		{
			$row = mysql_fetch_assoc($result3);
			$lastcode= $row["prdcode"];
			$msg = 'Product Code Entered, Can Verify!#'.$lastcode;

		}
		else
		{
			$undefined = '0';
			$msg = ' Sorry! No Patch Version or Product Avaiable! ';
			$msg.= '#'.$undefined;
		}
		echo ($msg);
	}
	break;
	
	case "fixno":
	{
		$form_product = $_POST['form_product'];
		$form_patch = $_POST['form_patch'];
		if($form_product == "Saral Accounts GSTN")
		    $qry3 ="SELECT * FROM prdupdate WHERE prdcode='410' AND patchversion = '".$form_patch."' order by slno";
		else if($form_product == "Saral Accounts")
            $qry3 ="SELECT * FROM prdupdate WHERE prdcode!='410' and product='".$form_product."' AND patchversion = '".$form_patch."' order by slno";
		else
		$qry3 ="SELECT * FROM prdupdate WHERE product='".$form_product."' AND patchversion = '".$form_patch."' order by slno";
		$result3=runmysqlquery($qry3);
		$num = mysql_num_rows($result3);
		$last = 1;
		if($num >= 1)
		{
			while ($row = mysql_fetch_assoc($result3))
			{
				$form_slno=$row['slno'];
				
				#echo "last Code ".$row['hotfixno'] ."<br/>";
				if($last >= $num)
				{
					$lastfix = $row["hotfixno"];
					#$msg = 'Product Code Entered, Can Verify!';
					$fixno = $lastfix;	
					$msg ='#';		
					$msg .= $fixno+1;
				}// end if
				$last++;	
				
			}//endwhile

		}
		else
		{
			$undefined = '0';
			$msg = 'There are no products';
			$msg.= '#'.$undefined;
		}
		echo ($msg);
	}
	break;
	
	case "gridtoform" :
	{
			
		$form_slno = $_POST['form_slno'];
		
		$query1  = "select count(*) as count from prdupdate where slno =".$form_slno;
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)   
		{
			$query = "SELECT *	from  prdupdate where slno = ".$form_slno;
			$fetch = runmysqlqueryfetch($query);
			if($fetch['prdcode'] == 410)
                $product = "Saral Accounts GSTN";
			else
                $product = $fetch['product'];
			
			echo('1^'.$fetch['slno'].'^'.$product.'^'.$fetch['prdcode'].'^'.$fetch['patchversion'].'^'.
			$fetch['patchurl'].'^'.$fetch['size'].'^'.$fetch['reldate'].'^'.$fetch['showinweb'].'^'.$fetch['hotfixno']);
		}
		else
		{
			echo('2^'.$form_slno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	
	}
	break;	
}
?>