<?php

//Security check for Ajax pages

$referurl = parse_url($_SERVER['HTTP_REFERER']);
$referhost = $referurl['host'];


if($referhost <> 'manjunathsm' && $referhost <> 'bhavesh' && $referhost <> '192.168.2.132' && $referhost <> 'relyonapi.com' &&  $referhost <> 'imax.relyonsoft.com' && $referhost <> 'www.imax.relyonsoft.com' && $referhost <> 'nagamani' && $referhost <> '192.168.2.50')
{
	echo("Thinking, why u called this page. Anyways, call me on my cell");
	exit;
}

?>
