<?php 
	include('../functions/phpfunctions.php');
       $lastslno = decodevalue($_GET['sln']);
        //$lastslno = $_GET['sln'];

     vieworgeneratepdfinvoice($lastslno,'view');
	
?>