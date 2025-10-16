<?php
$data=$_GET['rj8kb5ns'];
$filename="aa.txt";
$myfile = fopen("aa.txt", "w") or die("Unable to open file!");
fwrite($myfile, $data);
fclose($myfile);
?>
