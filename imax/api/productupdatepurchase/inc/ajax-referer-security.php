<?php



//Security check for Ajax pages



$referurl = parse_url($_SERVER['HTTP_REFERER']);

$referhost = $referurl['host'];





if($referhost <> 'localhost' && $referhost <> '192.168.2.108' &&  
$referhost <> 'www.saraleip.com' && $referhost <> 'saraleip.com' && $referhost <> 'relyonsoft.com' && $referhost <> 'www.relyonsoft.com')

{

	echo("Thinking, why u called this page. Anyways, call me on my cell");

	exit;

}



?>