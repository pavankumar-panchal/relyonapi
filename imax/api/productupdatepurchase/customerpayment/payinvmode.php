<?php
error_reporting(0);
include('functions/phpfunctions.php'); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relyon Softech Ltd - Buy Online</title>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type="text/javascript" src="../functions/payinvmode.js?dummy= <? echo (rand());?>"></script>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<? echo (rand());?>">
<script>
$(document).ready(function() {
$('#totalresult').empty().append('<img src="../images/relyonweb-rupee-symbol.jpg" height="15" width="16" align="absmiddle">');
});
</script>
<style type="text/css">

#invoicedetailsgrid { 
    display:none;
    position:fixed;  
    _position:absolute; 
    height:170px;  
    width:300px;  
    background:#FFFFFF;  
    left: 500px;
    top: 200px;
    z-index:100;
    margin-left: 15px;  
    border:1px solid #328cb8;
	box-shadow: 0px 0px 30px #666666; 
    font-size:15px;   	
	-moz-border-radius: 15px;
	border-radius: 15px; 
}

a{  
cursor: pointer;
text-decoration:none;  
} 

}
</style>
</head>
<body>
<form action="" method="post" name="submitexistform" id="00">
<table width="900px" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td  colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td width="700" valign="top">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td class="content-top">&nbsp;</td>
              </tr>
              <tr>
                <td class="content-mid">
                <table width="75%" border="0" cellspacing="0" cellpadding="0" align="center">
                  <tr>
                    <td><img src="../images/relyon-logo.jpg" alt="Customer Payment" width="196" height="75" border="0"></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="heading-font">Choose your mode of payment</td>
                  </tr>
                  <tr>
                    <td height="4px" colspan="2" class="blueline"></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><label> <input type="radio" id="paymode" name="paymode" value="credit" /> Pay through Credit Card</label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                  <!--<tr>
                    <td><div align="center"><strong style="font-size:14px">View your Cart</strong></div></td>
                  </tr> --><tr>
                  
                  <!-- <tr>
                    <td ><label><input type="radio" id="paymode" name="paymode" value="internet" />&nbsp;Pay through Net Banking</label><br /></td></tr><tr><td>&nbsp;<input type="hidden" name="lslnop" id="lslnop" value="<? echo $_POST['lastslno']; ?>"><input type="hidden" name="balanceamt" id="balanceamt" value="<? echo $_POST['balanceamt']; ?>"></td>
                  </tr> -->
                    <td></td>
                  </tr><tr>
                    <td><input name="custpayment" type="button"  id="custpayment" value="Proceed for Payment" onclick ="formsubmit()" class = "swiftchoicepaymentbutton"/></td>
                  </tr>
                  <tr>
                  <td>&nbsp;</td></tr>
                  <tr>
                  <td>&nbsp;</td></tr>
                  <tr>
                  <td><strong>Note : After clicking 'Proceed for Payment' Button don't do actions like  'refresh' or 'go Back' or 'go forward' or 'clicking links in the page'. In such cases the trasactions may end or may be lead to double payments.</strong></td>
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="text-align:center">© Relyon Softech Limited | <span style="text-decoration:none"><a href="http://www.relyonsoft.com" class="Link" target="_blank"> www.relyonsoft.com</a></span></td>
                 </tr>
                </table></td>
              </tr>
              <tr>
                <td></td>
              </tr>
                           <tr>
              <td class="content-btm">&nbsp;</td>
            </tr>
            </table></td></tr>
          </table></td>
      </tr>
      
    </table></form>
</body>
</html>
