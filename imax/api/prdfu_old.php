<?php
$x = $_GET["rj8kb5ns"];
$y = $_GET["sladjf8s"];

$url = "http://imax.etds-payroll-salary-software-india.com/api/saraltracker/saraltracker.php?rj8kb5ns=". $x ."&sladjf8s=". $y;

$file = 'prdfu_log.html';

$current = file_get_contents($file);

$indicesServer = array('PHP_SELF', 
'GATEWAY_INTERFACE', 
'REQUEST_METHOD', 
'REQUEST_TIME', 
'REQUEST_TIME_FLOAT', 
'QUERY_STRING', 
'DOCUMENT_ROOT',  
'HTTP_REFERER', 
'HTTP_USER_AGENT', 
'REMOTE_ADDR', 
'REMOTE_HOST', 
'REMOTE_PORT', 
'REMOTE_USER', 
'REDIRECT_REMOTE_USER') ; 

$current .='<table cellpadding="2">' ; 
foreach ($indicesServer as $arg) { 
    if (isset($_SERVER[$arg])) { 
        $current .='<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ; 
    } 
    else { 
        $current .='<tr><td>'.$arg.'</td><td>-</td></tr>' ; 
    } 
} 

$current .= '<tr><td>'. date("F j, Y, g:i a").'</td><td>' . $url .'</td></tr>';
$current .='</table>';




$current .= "<br><br>";
file_put_contents($file, $current);

if ($x == "" || $y== "")
{
exit();
}

header('Location: ' . $url);


?>