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
	$clas="";
	
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
			$form_product = $_POST['form_product'];
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
                $query = "SELECT prdcode,patchversion,size ,reldate,verfrom,patchurl,showinweb,slno FROM prdupdate WHERE updatetype = 'versionupdate' AND prdcode = '410' order by slno desc ";
            }
            else if($form_product == "Saral Accounts")
                $query = "SELECT prdcode,patchversion,size ,reldate,verfrom,patchurl,showinweb,slno FROM prdupdate WHERE updatetype = 'versionupdate' AND product = '".$form_product."' and prdcode!= '410' order by slno desc ";

            else
                $query = "SELECT prdcode,patchversion,size ,reldate,verfrom,patchurl,showinweb,slno FROM prdupdate WHERE updatetype = 'versionupdate' AND product = '".$form_product."' order by slno desc ";
			
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
									<td nowrap="nowrap"  class="tdborderlead"> Version From</td>
									<td nowrap="nowrap"  class="tdborderlead">URL</td>
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
					$color = "#FFF";
				 }
				 else
				 {
					 $color = "#F4F4F4";
				 }
						
				 if($fetch[6]==1)
				 {
					 $class ="showinweb";
				 }else{ $class = '';}
				
				 $grid .= '<tr bgcolor='.$color.' class="gridrow '.$class.'"  onclick="javascript:gridtoform('.$fetch[7].');">';
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
	}
	break;

	case "save":
	{
		$form_slno = $_POST["form_slno"];
		if($form_slno=="")
		{
		
				$form_product = $_POST['form_product'];
				
				$form_productcode = trim($_POST['form_productcode']);
				
				$form_patch = trim($_POST['form_patch']);
				
				$form_filesize = trim($_POST['form_filesize']);
				
				$DPC_date = $_POST['DPC_date'];
		
				$form_verfrom = $_POST['form_verfrom'];
		
				$form_url = trim($_POST['form_url']);
				
				/*$form_app = $_POST['form_app'];*/
				
				$form_path = $_POST['form_path'];

				$form_size = $_POST['form_size'];
				
				$show_web = $_POST['show_web']=="true" ? 1:0;
					
				$up_prd = $_POST['up_prd']=="true" ? 1:0;
				
                if($form_productcode == 410)
                    $product = "Saral Accounts";
                else
                    $product = $form_product;
				function update_ver($form_slno,$form_patch,$product,$form_productcode,$form_filesize,$DPC_date,$form_url)
				{
					$web='0';
					$qry5= "UPDATE prdupdate SET product ='".$product."', prdcode ='".$form_productcode."', patchversion ='".$form_patch."', size='".$form_filesize."', reldate ='".$DPC_date."', patchurl ='".$form_url."', showinweb ='".$web."'  
		WHERE slno=".$form_slno;
					$result5 = runmysqlquery($qry5);
				}
			
				if($form_size <> "" || $form_size == "undefined")
				{
					
					$qryprd="INSERT INTO saral_update (product, version, date, url, size) 
					values('".$form_product."', '".$form_patch."', '".$DPC_date."', '".$form_path."', '".$form_size."')";
					$result = runmysqlquery($qryprd);	
					$mainmsg =  "Your Main Product Updated successfully. Thank you. . .!!";				
				}
				if($form_productcode == 410)
                    $qry3 ="SELECT * FROM prdupdate WHERE prdcode='410' AND verfrom BETWEEN '".$form_verfrom."' AND '".$form_patch."'";
				else
                    $qry3 ="SELECT * FROM prdupdate WHERE prdcode!='410' and product='".$form_product."' AND verfrom BETWEEN '".$form_verfrom."' AND '".$form_patch."'";
				$result3 = runmysqlquery($qry3);
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
							$lastvariable= $row["patchversion"];
							
							#echo "last variable".$lastvariable."<br/>";
						
							update_ver($form_slno,$form_patch,$product,$form_productcode,$form_filesize,$DPC_date,$form_url);
						
							$qry1="INSERT INTO prdupdate (product, prdcode, patchversion, size, reldate, verfrom, patchurl, updatetype, applicable, showinweb) values('".$product."', '".$form_productcode."', '".$form_patch."', '".$form_filesize."', '".$DPC_date."', '".$lastvariable."', '".$form_url."', 'versionupdate', '".$form_verfrom."', ".$show_web.")";
							$result = runmysqlquery($qry1);
						}// end if
						else
						{
							update_ver($form_slno,$form_patch,$product,$form_productcode,$form_filesize,$DPC_date,$form_url);
						}
						$last++;
					}//endwhile
				}
				else
				{
					$query2 = "INSERT INTO prdupdate (product, prdcode, patchversion, size, reldate, verfrom, patchurl, updatetype, applicable, showinweb) values('".$product."', '".$form_productcode."', '".$form_patch."', '".$form_filesize."', '".$DPC_date."', '".$form_verfrom."', '".$form_url."', 'versionupdate', '".$form_verfrom."', ".$show_web.")";
					$result2 = runmysqlquery($query2);
				}

				###########// Log update into audit #########
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Added NEW Version Product Into Version Update Product: " .$form_product .", Productcode: ".$form_productcode . ", PatchVersion: " .$form_patch. ", File Size: ". $form_filesize . ", , Version From: " .$form_verfrom. ", Patch URL: ". $form_url. ", Activate Web: ".$show_web;
				if($form_size <> '' || $form_size == "undefined")
				{ $mainsetup = "And, <br />".$mainmsg;}else{ $mainsetup = '';}
				$message = "1^Your Version Update Details has been Updated successfully. Thank you. . .!! ". $mainsetup;
				
				$sub = $form_product." Product Version Update Added";
				$eventtype = '21';
				$file_htm = "../mailcontents/verfrom.htm";
				$file_txt = "../mailcontents/verfrom.txt";
				##Taking audit into log##
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				##sending Mail##
				productmail($sub,$file_htm,$file_txt);
		}
		else
		{
				$form_slno = $_POST["form_slno"];
					
				$form_product = $_POST['form_product'];
				
				$form_productcode = $_POST['form_productcode'];
				
				$form_patch = $_POST['form_patch'];
				
				$form_filesize = $_POST['form_filesize'];
				
				$DPC_date = $_POST['DPC_date'];
		
				$form_path = $_POST['form_path'];

				$form_size = $_POST['form_size'];
		
				$form_verfrom = $_POST['form_verfrom'];
		
				$form_url = $_POST['form_url'];
				
				$show_web = $_POST['show_web']=="true" ? 1:0;
				
				$up_prd = $_POST['up_prd']=="true" ? 1:0;
				if($form_productcode == 410)
				    $product = "Saral Accounts";
				else
                    $product = $form_product;
				
				if($form_size <> "" || $form_size == "undefined")
				{
					$qryprd="INSERT INTO saral_update (product, version, date, url, size) 
					values('".$form_product."', '".$form_patch."', '".$DPC_date."', '".$form_path."', '".$form_size."')";
					$result = runmysqlquery($qryprd);	
					$mainmsg =   "Your Main Product Updated successfully. Thank you. . .!!";				
				}

				
				$query_old ="SELECT * FROM prdupdate WHERE slno=".$form_slno;
				$resultfetch1 = runmysqlqueryfetch($query_old);
				
				$prd_old = $resultfetch1['product'];
				$code_old = $resultfetch1['prdcode'];
				$patch_old = $resultfetch1['patchversion'];
				$size_old = $resultfetch1['size'];
				$verfrom_old = $resultfetch1['verfrom'];
				$url_old = $resultfetch1['patchurl'];
				$showeb_old = $resultfetch1['showinweb'];

				$query3 = "UPDATE prdupdate SET product ='".$product."', prdcode ='".$form_productcode."', patchversion ='".$form_patch."', size='".$form_filesize."', reldate ='".$DPC_date."', verfrom ='".$form_verfrom."', patchurl ='".$form_url."', updatetype ='versionupdate', applicable='".$form_verfrom."', showinweb =".$show_web."  
		WHERE slno=".$form_slno;
		
				$result3 = runmysqlquery($query3);
	
				$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
				$datetime = gmdate("Y-m-d H:i:s");
				$activity = "Made Changes of Version Update Old Product Value: " .$prd_old . " : New Product Value: ".$form_product .",old prdcode value:".$code_old ." : New prdcode value:".$form_productcode. ", Old Patchversion Value:".$patch_old. " : New Patchversion Value:".$form_patch. ", Old File size value:".$size_old. " : New File size value:".$form_filesize. ", Old Patch URL Value:" .$url_old." : New Patch URL Value:".$form_url.", Old Version From:" .$verfrom_old." : New Version From:" .$form_verfrom.", Old Activation Website:" .$showeb_old." : New Activation Website:" .$show_web;
				$subject = $form_product." Product Changes";
				if($form_size <> '' || $form_size == "undefined")
				{ $mainsetup = "And, <br />".$mainmsg;}else{ $mainsetup = '';}

				$message = "1^Your Version Changes has been Made successfully. Thank you. . .!! ".$mainsetup;
				
				$sub = $form_product." Product Version Update Changes Made ";
				$eventtype = "22";
				$file_htm = "../mailcontents/verfrom.htm";
				$file_txt = "../mailcontents/verfrom.txt";
				##Taking audit into log##
				audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
				##sending Mail##
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
				
			
			$slno = mysql_escape_String($slno);
			
			$query4 = "DELETE FROM prdupdate WHERE slno='".$slno."'";
			$result4 = runmysqlquery($query4);
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
			$datetime = gmdate("Y-m-d H:i:s");
			$activity = "Deleted Version Product From Version Update record slno: ". $slno;
			$eventtype = "24";
		
			echo "Your Version Details Deleted successfully. Thank you. . .!!";
			audit_trail($userid, $ipaddr, $datetime, $activity,$eventtype);
		}
	}
	break;


	case "load":
	{
		$form_product = $_POST['form_product'];
		#$form_product ='Saral Tax Office';
        if($form_product == "Saral Accounts GSTN")
        {
            $query3 = "select verfrom as patch_verfrom from prdupdate where prdcode='410' and verfrom <>'' union
            select  patchversion as patch_verfrom from prdupdate where prdcode='410' and patchversion <>''ORDER BY patch_verfrom desc";
        }
        else if($form_product == "Saral Accounts")
        {
            $query3 = "select verfrom as patch_verfrom from prdupdate where prdcode!='410' and product='" . $form_product . "' and verfrom <>'' union
            select  patchversion as patch_verfrom from prdupdate where prdcode!='410' and product='" . $form_product . "' and patchversion <>''ORDER BY patch_verfrom desc";
        }
        else {
		$query3 = "select verfrom as patch_verfrom from prdupdate where product='".$form_product."' and verfrom <>'' union
select  patchversion as patch_verfrom from prdupdate where product='".$form_product."' and patchversion <>''ORDER BY patch_verfrom desc";
        }
		$result_data = runmysqlquery($query3);
		if(mysql_num_rows($result_data) > 1)
		{
			$msg = "";
			echo('<option value="" selected="selected"> Select a Version From</option>');
		}
		while($fetch = mysql_fetch_array($result_data))
		{
			#$msg= $fetch['patchversion'];
			echo('<option value="'.$fetch['patch_verfrom'].'">'.$fetch['patch_verfrom'].'</option>');
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
		if($form_product == "Saral Accounts GSTN")
        {
            $qry3 ="SELECT * FROM prdupdate WHERE prdcode='410' order by slno";
        }
		else if($form_product == "Saral Accounts")
            $qry3 ="SELECT * FROM prdupdate WHERE prdcode!='410' and product='".$form_product."' order by slno";
		else
		$qry3 ="SELECT * FROM prdupdate WHERE product='".$form_product."' order by slno";
		$result3 = runmysqlquery($qry3);
		$num = mysql_num_rows($result3);
		$last = 1;
		$ver = 1;
		if($num >= 1)
		{
			while ($row = mysql_fetch_assoc($result3))
			{
					$form_slno=$row['slno'];
					#echo "last Code ".$row['prdcode'] ."<br/>";
				if($last >= $num)
				{
					$lastcode= $row["prdcode"];
					echo 'Product Code Entered, Can Verify!#'.$lastcode;
				}// end if
				$last++;	
					#echo "last Patch Version ".$row['patchversion'] ."<br/>";
				if($ver >= $num)
				{
					$lastpv= $row["patchversion"];
					$patch = $lastpv;
					$msg = "#";
					$msg .= $patch+0.01; 
				}// end if
				$ver++;
			}//endwhile
		}
		else
		{
			$undefined = '0';
			$msg = ' Sorry! No Patch Version or Product Avaiable! #'.$undefined;
			$msg .= '#0';
		}
		echo ($msg);
	}
	break;
	
	case "path":
	{
		$form_path = $_REQUEST['form_path']; //'http://etds-payroll-salary-software-india.com/training/registration-form-12thjuly-blr.docx';
		// We preform a bit of filtering 
		#echo "this the Path " .$form_path;
		$file = strip_tags($form_path); 
		$file = trim ($form_path); 
		
		#echo "file ". strlen($file);
		
		if(strlen($file)==0)
		{
			$msg = "Enter Path URL ";
			$msg .= "#0";
		}
		else
		{
			$file_headers = @get_headers($file);
		
			if($file_headers[0] == 'HTTP/1.1 404 Not Found') 
			{
				$exists = false;
				$msg =  "Sorry, Please Enter Valid 'URL' . . .!!";
				$msg .= "#0";
			}
			else 
			{
				$exists = true;
				$msg = "URL Is Avaiable . . .!!";
				$headers = get_headers($file);
				$content_length = 1;
				foreach ($headers as $h)
				{
					preg_match('/Content-Length: (\d+)/', $h, $m);
					if (isset($m[1]))
					{
						$content_length = (int)$m[1];
						break;
					}
				}
				#echo "Bytes = ".$content_length."<br/>"; 
				$bytes = ( int )$content_length;
				$kb = round( $bytes / ( int )1024);
				$megs = round( $bytes / ( int )1024 / ( int )1024, 4 );  
			
				$msg .=  "#" .$kb;
				/*$msg .=  "#" .$megs;*/
				}
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
			$fetch['patchurl'].'^'.$fetch['size'].'^'.$fetch['reldate'].'^'.$fetch['showinweb'].'^'.$fetch['verfrom']);
		}
		else
		{
			echo('2^'.$form_slno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	
	}
	break;
}
?>