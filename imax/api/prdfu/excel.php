<?php
    
   session_start();
   include('functions/phpfunctions.php');
   $dbhost_old = "etds-payroll-salary-software-india.com"; $dbuser_old = "admin_imax"; $dbpwd_old = "e1?jGx20"; $dbname_old = "admin_imax";
	$newconnection_old = mysql_connect($dbhost_old, $dbuser_old, $dbpwd_old,true) or die("Cannot connect to Mysql server host");
	mysql_select_db('admin_imax',$newconnection_old) or die("Cannot connect to database 3= ".$query);
   
   if(isset($_SESSION['passkey']))
	{	
		if($_SESSION['passkey']!= CONSTANTKEY)
		{
			  writelogin();
		}
	}
	elseif(isset($_POST['PASSKEY']))
	{
		if($_POST['PASSKEY'] == CONSTANTKEY)
		{
			$_SESSION['passkey'] = $_POST['PASSKEY'];			
		}
		else
		{
			writelogin();
		}
	}
	else
	{  
		writelogin();
	}
   
   header( "Content-Type: application/vnd.ms-excel" );
   header( "Content-disposition: attachment; filename=searchdata.xls" );
   	
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Customer</title>
<style type="text/css">
.tableheading {
	text-align: center;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 14px;
	letter-spacing: 1px;
	font-style: normal;
	font-weight: bold;
	background-color:#666666;
	color: #FFF;
}

.tablerow1 {
    background-color:#CCCCCC;
	font-size: 16px;
	color: #000;
	font-style: normal;
	font-family: Verdana, Geneva, sans-serif;
}
.tablerow2 {
	background-color: #EEEEEE;
	font-size: 14px;
	color: #000;
	font-style: normal;
	font-family: Verdana, Geneva, sans-serif;
}
</style>
</head>

<body>

<?php	

  	$fromdt = base64_decode($_GET['start']);
	$todt = base64_decode($_GET['end']);
	$invalidcust = base64_decode($_GET['select']);
	$search = base64_decode($_GET['sea']);
	$searchpin = base64_decode($_GET['seap']);
	$searchcompid  = base64_decode($_GET['seac']);
	$searchip  = base64_decode($_GET['seai']);
	$searchcustname = base64_decode($_GET['seacn']);
	$selecttype = base64_decode($_GET['selte']);
		
	if($fromdt!= "" && $todt!= "")		
	{
		$date = " date between '".$fromdt."' AND '".$todt."' and ";
	}
	if($search!= "")
	{
		$searchdata = " and substr(customerid,-5) = '" .$search."' ";
	}
	if($searchpin!= "")
	{
		$searchdata = $searchdata . " and pinnumber = '".$searchpin ."' ";
	}
	if($searchcompid!= "")
	{
		$searchdata = $searchdata ." and computerid = '".$searchcompid ."' ";
	}
	if($searchip!= "")
	{
		$searchdata = $searchdata ." and ip = '" .$searchip."' ";
	}
    if($searchcustname!= "")
	{
		$searchdata = $searchdata . " and registeredname like '%" .$searchcustname."%' ";
	}
	if($selecttype!= "")
	{
		$searchdata = $searchdata . " and processedtype = '".$selecttype."' ";
	}
	
	$custdata = ($invalidcust == 1) ? " customerid like 'i%' ". $searchdata : " customerid not like 'i%' " . $searchdata ;
		
	$query = "Select * from inv_logs_webservices3 where " . $date . $custdata . " order by slno desc " ;
	//$result = runmysqlquery_old($query);
	$result = mysql_query($query,$newconnection_old) or die(" run Query Failed in runquery function.".$query); //;
		
	echo "<table border=1 cellpadding=5 cellspacing=0 style='width:200%;'>
	<tr class='tableheading'>
	 <td >Slno</td>
	<td >Customer Id</td>
	<td >Registered Name</td>
	<td >Pin Number</td>
	<td >Computer Id</td>
	<td >Date</td>
	<td >IP</td>
	<td >Product Version</td>
	<td >Operating System</td>
	<td >Processor</td>
	<td >Service Name</td>
	<td >Processed Type</td>
	<td >Processed Date</td>
	<td >Processed Status</td>
	</tr>";
	
	$slno = 1;					
	while($query_data=mysql_fetch_array($result))
	{     
		   //this will fetch table data
		   echo fnWriteTableData($query_data,$slno);  
		   $slno++;	
	}
	
	echo '</table>';
		
	function fnWriteTableData($query_data,$slno)
	{
		$rowclass = ($slno % 2) ? 'tablerow1' : 'tablerow2';
		
		$msg = "<tr class='".$rowclass."'>
		        <td valign='top' align='right'>" .$slno."</td>
		        <td valign='top' >".substr($query_data['customerid'],-5)."</td>
				<td valign='top'>".$query_data['registeredname']."</td>
				<td valign='top'>".$query_data['pinnumber']."</td>
		        <td valign='top'>".$query_data['computerid']."</td>
				<td valign='top'>".$query_data['date']."</td>
		        <td valign='top'>".$query_data['ip']."</td>
				<td valign='top'>".$query_data['productversion']."</td>
				<td valign='top'>".$query_data['operatingsystem']."</td>
				<td valign='top'>".$query_data['processor']."</td>
				<td valign='top'>".$query_data['servicename']."</td>
				<td valign='top'>".$query_data['processedtype']."</td>
				<td valign='top'>".$query_data['processeddate']."</td>
				<td valign='top'>".$query_data['processedstatus']."</td>
				</tr>";
					
		return $msg;		
	}
	function writelogin()
	{
		header('Location: login.php');
	}
		
?>

</body>
</html>