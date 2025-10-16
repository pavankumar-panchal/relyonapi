<!DOCTYPE html>
<html>
<head>
    <title>Verify Registration</title>

</head>
<body>
<form action="custreg2.php" method="post">
<div>
    <div>
    <label for="CUSTID">CUSTID</label>
    <input type="text" name="CUSTID" id="CUSTID">
    </div>

    
    <div>
        <label for="PINNO">PINNO</label>
        <input type="text" name="PINNO" id="PINNO">
    </div>
	
	<div>
        <label for="REGCODE">Unique Code</label>
        <input type="text" name="REGCODE" id="REGCODE">
    </div>
   <div>
       <label for="PRDCODE">Product Code</label>
       <input type="text" name="PRDCODE" id="PRDCODE">
   </div>
   <div>
       <label for="CMPNAME">Computer Name</label>
       <input type="text" name="CMPNAME" id="CMPNAME">
   </div>
   <div>
       <label for="CMPID">Computer ID</label>
       <input type="text" name="CMPID" id="CMPID">
   </div>

    <div>
        <button type="submit" name="submit">Submit</button>
    </div>
</div>
</form>
</body>
</html>