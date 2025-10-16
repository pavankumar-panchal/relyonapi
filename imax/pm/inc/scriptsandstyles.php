<?
		echo('<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">');
	
	if(file_exists("../images/favicon.ico")) echo('<link rel="shortcut icon" type="image/x-icon" href="../images/favicon.ico" />
');
	elseif(file_exists("../../images/favicon.ico")) echo('<link rel="shortcut icon" type="image/x-icon" href="../../images/favicon.ico" />
');
	elseif(file_exists("../../../images/favicon.ico")) echo('<link rel="shortcut icon" type="image/x-icon" href="../../../images/favicon.ico" />
');
	elseif(file_exists("./images/favicon.ico")) echo('<link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
');

	if(file_exists("../css/styles.css")) echo('<LINK href="../css/styles.css" rel=stylesheet>');
	elseif(file_exists("../../css/styles.css")) echo('<LINK href="../../css/styles.css" rel=stylesheet>');
	elseif(file_exists("../../../css/styles.css")) echo('<LINK href="../../../css/styles.css" rel=stylesheet>');
	elseif(file_exists("./css/styles.css")) echo('<LINK href="./css/styles.css" rel=stylesheet>');

	if(file_exists("../css/loginmodule.css")) echo('<LINK href="../css/loginmodule.css" rel=stylesheet>');
	elseif(file_exists("../../css/loginmodule.css")) echo('<LINK href="../../css/loginmodule.css" rel=stylesheet>');
	elseif(file_exists("../../../css/loginmodule.css")) echo('<LINK href="../../../css/loginmodule.css" rel=stylesheet>');
	elseif(file_exists("./css/loginmodule.css")) echo('<LINK href="./css/loginmodule.css" rel=stylesheet>');
	
	if(file_exists("../css/page.css")) echo('<LINK href="../css/page.css" rel=stylesheet>');
	elseif(file_exists("../../css/page.css")) echo('<LINK href="../../css/page.css" rel=stylesheet>');
	elseif(file_exists("../../../css/page.css")) echo('<LINK href="../../../css/page.css" rel=stylesheet>');
	elseif(file_exists("./css/page.css")) echo('<LINK href="./css/page.css" rel=stylesheet>');
	
		if(file_exists("../functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="../functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="../../functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="../../../functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="./functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	
	if(file_exists("../functions/superfish.js")) echo('<SCRIPT src="../functions/superfish.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/superfish.js")) echo('<SCRIPT src="../../functions/superfish.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/superfish.js")) echo('<SCRIPT src="../../../functions/superfish.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/superfish.js")) echo('<SCRIPT src="./functions/superfish.js" type=text/javascript></SCRIPT>');
	
	
	if(file_exists("../functions/main.js")) echo('<SCRIPT src="../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/main.js")) echo('<SCRIPT src="../../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/main.js")) echo('<SCRIPT src="../../../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/main.js")) echo('<SCRIPT src="./functions/main.js" type=text/javascript></SCRIPT>');


	if(file_exists("../functions/datepickercontrol.js")) echo('<SCRIPT src="../functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/datepickercontrol.js")) echo('<SCRIPT src="../../functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/datepickercontrol.js")) echo('<SCRIPT src="../../../functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/datepickercontrol.js")) echo('<SCRIPT src="./functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	
	
	if(file_exists("../functions/cookies.js")) echo('<SCRIPT src="../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/cookies.js")) echo('<SCRIPT src="../../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/cookies.js")) echo('<SCRIPT src="../../../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/cookies.js")) echo('<SCRIPT src="./functions/cookies.js" type=text/javascript></SCRIPT>');

	if(file_exists("../functions/javascripts.js")) echo('<SCRIPT src="../functions/javascripts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/javascripts.js")) echo('<SCRIPT src="../../functions/javascripts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/javascripts.js")) echo('<SCRIPT src="../../../functions/javascripts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/javascripts.js")) echo('<SCRIPT src="./functions/javascripts.js" type=text/javascript></SCRIPT>');
		

echo('<script language="JavaScript"> if (navigator.platform.toString().toLowerCase().indexOf("linux") != -1) { document.write(\'<link type="text/css" rel="stylesheet" href="../functions/datepickercontrol_lnx.css">\'); } else { document.write(\'<link type="text/css" rel="stylesheet" href="../functions/datepickercontrol.js">\'); } </script>');
?>
