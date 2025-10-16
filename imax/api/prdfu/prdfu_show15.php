<?php
   session_start();
   	
   include('functions/phpfunctions.php');
   
   if(isset($_SESSION['passkey']))
	{	
		if($_SESSION['passkey']!=CONSTANTKEY)
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
			writelogin(1);			
		}
	}
	else
	{  
		writelogin();
	}
	
	if(isset($_POST['submit']))
	{
		if($_POST['DPC_fdt']!= "" && $_POST['DPC_tdt']!= "")
		{
			$convertfromdate = $_POST['DPC_fdt'];
			$fromdt = date("Y-m-d",strtotime($convertfromdate));
			$converttodate = $_POST['DPC_tdt'];
			$todt = date("Y-m-d",strtotime($converttodate));
		}
		$invalidcust = $_POST['invalidcust'];
		$search = $_POST['search'];
		$searchpin = $_POST['searchpin'];
		$searchcompid  = $_POST['searchcompid'];
		$searchip  = $_POST['searchip'];
		$searchcustname = $_POST['searchcustname'];
		$selectpage = $_POST['selectpage'];
		$selecttype = $_POST['selecttype'];
	}
	elseif(isset($_GET['start']))
	{
		$fromdt = base64_decode($_GET['start']);
		$convertfromdate = date("d-m-Y",strtotime($fromdt));
		$todt = base64_decode($_GET['end']);
		$converttodate = date("d-m-Y",strtotime($todt));
		$invalidcust = base64_decode($_GET['select']);
		$search = base64_decode($_GET['sea']);
		$searchpin = base64_decode($_GET['seap']);
		$searchcompid  = base64_decode($_GET['seac']);
		$searchip  = base64_decode($_GET['seai']);
		$searchcustname = base64_decode($_GET['seacn']);
		$selectpage = base64_decode($_GET['selpe']);
		$selecttype = base64_decode($_GET['selte']);
	}
	else
	{
		writelogin();
	}
	
?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Customer</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
<nav>
<a href="login.php">Search</a><font color="#000000">&nbsp;&nbsp;|&nbsp;&nbsp;</font>
<?php
    echo "<a  href='excel.php?select=".base64_encode($invalidcust)."&seacn=".base64_encode($searchcustname)."&start=".base64_encode($fromdt).
	     "&end=".base64_encode($todt). "&sea=".base64_encode($search)."&seap=".base64_encode($searchpin)."&seai=".base64_encode($searchip).
		 "&seac=".base64_encode($searchcompid)."&selte=".base64_encode($selecttype)."'> Export To Excel </a>";	 
?>
<font color="#000000">&nbsp;&nbsp;|&nbsp;&nbsp;</font>
<a  href="login.php?ARGS=2">Logout</a>
</nav><br>
<div class='searcheddata'><strong class="searcheddata1">Filter :- </strong>
<?php 
    if($searchcustname!= "")
	{ 
	   echo "Custname :" ."&nbsp;'".$searchcustname."'&nbsp;&nbsp;"; 
	}
	if($search!= "")
	{ 
	   echo "CustID :" ."&nbsp;'".$search."'&nbsp;&nbsp;"; 
	}
	if($searchpin!= "")
	{ 
	   echo "PIN :" ."&nbsp;'".$searchpin."'&nbsp;&nbsp;"; 
	}
	if($searchcompid!= "")
	{ 
	   echo "CompID :" ."&nbsp;'".$searchcompid."'&nbsp;&nbsp;"; 
	}
	if($searchip!= "")
	{ 
	   echo "IP :" ."&nbsp;'".$searchip."'&nbsp;&nbsp;"; 
	}
	if($fromdt!= "" && $todt!= "")
	{
		echo "Fromdate :" ."&nbsp;'".$convertfromdate."'&nbsp;Todate :" . "&nbsp;'".$converttodate."'&nbsp;&nbsp;";
	}
	if($selecttype!= "")
	{
	    echo "Proctype :" ."&nbsp;'".$selecttype."'&nbsp;&nbsp;"; 
	}
?>
</div>
<?php
	if(isset($_GET['div'])) 
	{ 
	   $page  = base64_decode($_GET['div']); 
	}
	else 
	{ 
	  $page=1; 
	}
	$start_from = ($page-1) * $selectpage ; 
	
	if($fromdt!= "" && $todt!= "")		
	{
		$date = " date between '".$fromdt."' AND '".$todt."' and ";
	}
	if($search!= "")
	{
		$searchdata = " and substr(customerid,-5) = '".$search."' ";
	}
	if($searchpin!= "")
	{
		$searchdata = $searchdata . " and pinnumber = '".$searchpin ."' ";
	}
	if($searchcompid!= "")
	{
		$searchdata = $searchdata . " and computerid = '".$searchcompid ."' ";
	}
	if($searchip!= "")
	{
		$searchdata = $searchdata . " and ip = '" .$searchip. "' ";
	}
	if($searchcustname!= "")
	{
		$searchdata = $searchdata . " and registeredname like '%" .$searchcustname."%' ";
	}
	if($selecttype!= "")
	{
		$searchdata = $searchdata . " and  processedtype = '".$selecttype."' ";
	}
	
	$result = '';
	$dbhost_old = "etds-payroll-salary-software-india.com"; $dbuser_old = "admin_imax"; $dbpwd_old = "e1?jGx20"; $dbname_old = "admin_imax";
$newconnection_old = mysql_connect($dbhost_old, $dbuser_old, $dbpwd_old,true) or die("Cannot connect to Mysql server host");
mysql_select_db('admin_imax',$newconnection_old) or die("Cannot connect to database 3= ".$query);

	$custdata = ($invalidcust == 1) ? " customerid like 'i%' ". $searchdata : " customerid not like 'i%' ". $searchdata ;
	//query for fetch record
	$query = "Select * from inv_logs_webservices3 where  " . $date . $custdata . " and processedtype != 'KYC' order by slno desc LIMIT $start_from , $selectpage ";
	//$query = "Select * from inv_logs_webservices3 where  " . $date . $custdata . " order by slno desc LIMIT $start_from , $selectpage ";
	// $result = runmysqlquery_old($query);
	$result = mysql_query($query,$newconnection_old) or die(" run Query Failed in runquery function.".$query); //;
	echo $query;

	
    //query for count the records and divide it in pagination
	$query1 = "Select count(slno) as slno from inv_logs_webservices3 where " . $date . $custdata ."and processedtype != 'KYC'" ;
	$result1 = runmysqlquery_old($query1);
	$row = mysql_fetch_row($result1);
	$total_records = $row[0];
	$total_pages = ceil($total_records /$selectpage);

	echo "<strong>Records Found</strong>&nbsp;&nbsp;" . $total_records;		 
	$data = "&start=".base64_encode($fromdt).
	 "&end=".base64_encode($todt)."&select=".base64_encode($invalidcust).
	 "&sea=".base64_encode($search)."&seap=".base64_encode($searchpin)."&seai=".base64_encode($searchip).
	 "&seac=".base64_encode($searchcompid)."&seacn=".base64_encode($searchcustname)."&selpe=".base64_encode($selectpage)."&selte=".base64_encode($selecttype) ;

    //function for pagination
	writepagenav($total_pages,$data,$page);

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
		writepagenav($total_pages,$data,$page);
		
	}
	else
	{
		echo "No record found";
	}
		
	function writelogin($ARGS="")
	{
		header('Location: login.php?ARGS=' .$ARGS );
	}
	
	function writepagenav($total_pages,$data,$currentpage=1)
	{
		echo "<div class='pagination' align='center'>";
		if($currentpage > 1) 
        {
	       echo "<a href='".$_SERVER['PHP_SELF']."?div=".base64_encode($currentpage-1).$data."'> << Back</a>&nbsp;&nbsp;";
        }
		for ($i=1; $i<=$total_pages; $i++) 
		{
			if($i==$currentpage)
			{
			    echo "<span class='currentpage'>".$i."</span>&nbsp;&nbsp;";
			}
			else
			{
				echo "<a href='".$_SERVER['PHP_SELF']."?div=".base64_encode($i).$data."' >".$i."</a>&nbsp;&nbsp;";
			}
		} 	
		if($currentpage < $total_pages)
        {
	         echo "&nbsp;<a href='".$_SERVER['PHP_SELF']."?div=".base64_encode($currentpage+1).$data."'>Next >></a>";
        }			
		echo "</div>";
	}
	
?>

</body>
</html>