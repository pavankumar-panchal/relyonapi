<?php
ini_set('memory_limit', '-1');

$x = $_GET["rj8kb5ns"];
$y = $_GET["sladjf8s"];

if ($x == "" || $y == "")
{
  exit();
}
else
{

			$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === 
			FALSE ? 'http' : 'https';            // Get protocol HTTP/HTTPS
			$host     = $_SERVER['HTTP_HOST'];   // Get  www.domain.com
			$script   = $_SERVER['SCRIPT_NAME']; // Get folder/file.php
			$params   = $_SERVER['QUERY_STRING'];// Get Parameters occupation=odesk&name=ashik
			
			if($params != "")
			{
			   $currentUrl = $protocol . '://' . $host . $script . '?' . $params; // Adding all
			}			
			
			$split = explode('?', $currentUrl,2);
			
			$newUrl = "http://www.etds-payroll-salary-software-india.com/imax/api/saraltracker/saraltracker.php?".$split[1];
			
			//manjunath Sir Code
			
			
			$file = 'prdfu_log.html';

			$current = file_get_contents($file);
			
			$indicesServer = array('PHP_SELF', 'GATEWAY_INTERFACE', 'REQUEST_METHOD', 'REQUEST_TIME', 'REQUEST_TIME_FLOAT', 'QUERY_STRING', 'DOCUMENT_ROOT', 'HTTP_REFERER', 'HTTP_USER_AGENT', 'REMOTE_ADDR', 'REMOTE_HOST', 'REMOTE_PORT', 'REMOTE_USER', 'REDIRECT_REMOTE_USER') ; 
			
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

			
			//manjunath Sir code ends
			
			header('Location: ' . $newUrl);
}

?>