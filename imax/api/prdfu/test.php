<?php

//phpinfo();

$dbhost_old = "etds-payroll-salary-software-india.com"; $dbuser_old = "admin_imax"; $dbpwd_old = "e1?jGx20"; $dbname_old = "admin_imax";
$newconnection_old = mysql_connect($dbhost_old, $dbuser_old, $dbpwd_old,true) or die("Cannot connect to Mysql server host");
mysql_select_db('admin_imax',$newconnection_old) or die("Cannot connect to database 3= ".$query);
$query ="Select * from inv_logs_webservices3 where date between '2024-04-01' AND '2024-04-02' and customerid not like 'i%' and processedtype != 'KYC' order by slno desc LIMIT 0 , 10;";
$result = mysql_query($query,$newconnection_old) or die(" run Query Failed in runquery function.".$query); //;
echo mysql_num_rows($result);
if(mysql_num_rows($result) > 0)
	{	
		echo "<table border=1 cellpadding=3 cellspacing=0 width=100% >
		<tr class='tableheading'>
			<td ><strong>Slno</strong></td>
			<td ><strong>Customer ID</strong></td>
			<td ><strong>Customer Details</strong></td>
			<td ><strong>Access Details</strong></td>
			<td ><strong>Service Name</strong></td>
			<td><strong>Processed Details<strong></td>
		</tr>";
		$slno=1 + $start_from ;
		while($query_data=mysql_fetch_array($result))
		{     
			   //this will fetch table data
			   //echo fnWriteTableData($query_data);
		   $date = $query_data['date'];
		   $converteddate = date("d-m-Y H:i:s", strtotime($date));
		   
		   $rowclass = ($slno % 2) ? 'tablerow1' : 'tablerow2';
		   	    
		   echo  "<tr class='".$rowclass."'><td  valign='top' align='right'>" .$slno."</td>
			<td  valign='top'>". substr($query_data['customerid'],-5)."</td>
			<td valign='top' style='width:350px'> <div class='customer'>"
			.$query_data['registeredname']."</div>
			Pin : ".$query_data['pinnumber']."<br>
			Comp ID : ".$query_data['computerid']."
			<div class='version'>Ver : ".$query_data['productversion']."</div></td>
			<td  valign='top' style='width:280px'>Date : ".$converteddate."<br>
			IP : ".$query_data['ip']."<br>
			OS : ".$query_data['operatingsystem']."<br>
			<div class='version'>Processor : ".$query_data['processor']."</div></td>
			<td  valign='top'>".$query_data['servicename']."</td><td  valign='top'>
			Proctype : ".$query_data['processedtype']."<br>
			Procdate : ".$converteddate."<br>
			procstatus : ".$query_data['processedstatus']."</td>
			</tr>";	 
			$slno++;	
		}
		echo '</table>';
		//function for pagination
		
	}

?>