<?php
   session_start();
   
   include('../functions/phpfunctions.php');
   
   $message="";
   $passkey="";
   if($_GET['ARGS'] == 1)
   {	  
	   $message="Invalid PASSKEY";	   
   }
   if($_GET['ARGS'] == 2)
   {
	   $_SESSION['passkey']="";
	   session_destroy();
	   $message="Logged out successfully";	   
   }
   
   if(isset($_SESSION['passkey']))
	{	
		if($_SESSION['passkey']==CONSTANTKEY)
		{
			  $passkey=CONSTANTKEY;
		}
	}
   
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<!-- Start Of Calender  -->
<link href="date/datepickercontrol.css" rel="stylesheet" type="text/css" media="screen"  />
<script language="javascript" src="date/datepickercontrol.js" type="text/javascript"></script>
<!-- End Of Calender  -->
<link rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>
setTimeout(function() {
    $('.message').fadeOut('slow');
}, 1500);
</script>
</head>

<body>
<?php
	if($message!="")
	{
		echo'<div class="message">'.$message.'</div>';
	}
?>
<form  method="post" action="prdfu_show15.php">
  <table border="0"  class="maintable" cellpadding="0" cellspacing="10" align="center" >
    <tr>
      <td colspan="3" align="center" class="header">Search Valid & Invalid Customers</td>
    </tr>
    <tr>
      <td align="left"  class="tabdata">Enter PASSKEY </td>
      <td width="3%">:</td>
      <td><input type="password" name="PASSKEY"  id="PASSKEY" autofocus tabindex="1" size="34" class="inputdata" value="<?php echo $passkey;?>"></td>
    </tr>
    <tr>
      <td align="left" class="tabdata">From Date </td>
      <td>:</td>
      <td><input name="DPC_fdt" type="text" class="inputdata"  tabindex="2" placeholder="DD-MM-YYYY" id="DPC_fdt" size="34" autocomplete="off"  /></td>
    </tr>
    <tr>
      <td align="left" class="tabdata">To Date </td>
      <td>:</td>
      <td><input  name="DPC_tdt" type="text" tabindex="3" placeholder="DD-MM-YYYY" id="DPC_tdt" size="34" autocomplete="off" class="inputdata"  /></td>
    </tr>
    <tr>
      <td align="left"  class="tabdata">Customer Name</td>
      <td width="3%">:</td>
      <td><input type="text" name="searchcustname" id="searchcustname" tabindex="4" size="34" placeholder="" class="inputdata" autocomplete="off"></td>
    </tr>
    <tr>
      <td align="left"  class="tabdata">Customer ID </td>
      <td width="3%">:</td>
      <td><input type="text" name="search" id="search" size="34" tabindex="5" maxlength="5" placeholder="Enter only last 5 digit" class="inputdata" autocomplete="off"></td>
    </tr>
    <tr>
      <td align="left"  class="tabdata">Pin Number </td>
      <td width="3%">:</td>
      <td><input type="text" name="searchpin" id="searchpin" size="34" tabindex="6" placeholder="Enter like XXXX-XXXX-XXXX" class="inputdata" autocomplete="off"></td>
    </tr>
    <tr>
      <td align="left" width="26%" class="tabdata">Computer ID</td>
      <td width="3%">:</td>
      <td><input type="text" name="searchcompid" id="searchcompid" tabindex="7" size="34" placeholder="Enter like XXXXX-XXXXXXXXX" class="inputdata" autocomplete="off"></td>
    </tr>
    <tr>
      <td align="left"  class="tabdata">IP Address</td>
      <td width="3%">:</td>
      <td><input type="text" name="searchip" id="searchip" size="34" tabindex="8" placeholder="" class="inputdata" autocomplete="off"></td>
    </tr>
    <tr>
      <td align="left" class="tabdata">Page Selection</td>
      <td width="3%">:</td>
      <td ><select name="selectpage"  style="height:20px; width:223px" class="inputdata" tabindex="9">
          <option value="20" >20</option>
          <option value="50">50</option>
          <option value="100">100</option>
          <option value="500" selected="selected">500</option>
           <option value="1000">1000</option>
        </select></td>
    </tr>
    <tr>
      <td align="left" class="tabdata">Processed type</td>
      <td width="3%">:</td>
      <td ><select name="selecttype" class="inputdata" style="height:20px; width:223px" tabindex="10">
          <option value="" selected="selected">All</option>
          <?php
	     $query = "select distinct processedtype from inv_logs_webservices3 order by processedtype";
		 $result = runmysqlquery_old($query);
		 while($query_data = mysql_fetch_array($result))
		 {
	         echo "<option value='".$query_data['processedtype']."'>".$query_data['processedtype']."</option>";
		 }
		 ?>
        </select></td>
    </tr>
    <tr>
      <td align="left" class="tabdata">Invalid Customer</td>
      <td>:</td>
      <td><input type="checkbox" name="invalidcust" id="invalidcust" tabindex="11"  value="1">
        &nbsp; For <strong> Invalid Customer List </strong> check the box.</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><input name="submit"  id= "submit" tabindex="12" class="submit" value="Search" type="submit"></td>
    </tr>
  </table>
</form>
</body>
</html>