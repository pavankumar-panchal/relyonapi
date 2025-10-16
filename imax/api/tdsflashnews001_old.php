<?php

include('functions/phpfunctions.php');
include('inc/tdsflashnews001-phpcode.php');

$grid = '';
$query1 = "select * from saral_flashnews where (product='Saral TDSCorporate' or product= 'Saral TDSInstitutional' or product='Saral TDSProfessional') and (validtill > CURDATE() or validtill is null or validtill = '' or validtill = '0000-00-00') and (`disable` = 'no')  order by adddeddate desc;";
#$result1 = runmysqlquerytdsflash($query1);
$result1 = runmysqlqueryflashnews($query1);
$i_n = 0;
while($fetch = mysql_fetch_array($result1))
{
	if($i_n%2 == 0)
		$color = "#FF0000";
	else
		$color = "#0066FF";
	$link = $fetch['link'];
	$text = $fetch['text'];

	if($link <> '#')
	{
		$linktext = 'href='.$link;
	}
	else
	{
		$linktext = '';
		$text = 'INFO: '.$text;
	}
	
	$title = $fetch['title'];
	
	//Give Top numbering and border
	$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="20" align="center" bgcolor="#009933" style="border-top:1px solid #009933; color:#FFFFFF">'.($i_n+1).'</td><td style="border-top:1px solid #009933">&nbsp;</td></tr></table>';
	//Give the Flash text with link and title
	$grid .= '<a '.$linktext.' target="_blank"  title="'.$title.'"><font color="'.$color.'">'.$text.'</font></a><br /><br />';
	$i_n++;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Relyon TDS Flash News</title>
<link rel="stylesheet" type="text/css" href="style/tdsflashnews001.css"/>
<link type="text/css" href="style/scroll.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="functions/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="functions/jquery.rating.js"></script>
<script type="text/javascript" src="tdsflashnews001-v2.js"></script>
<script type="text/javascript" src="functions/jquery.mousewheel.js"></script>
<script type="text/javascript">
  $(function() {
	  $('#click').raty({
		  click: function(score, evt) {
		  }
	  });
  });
  $(document).ready(function(){
    $(document).bind("contextmenu",function(e){
        return false;
    });
});
</script>
</head>
<body style="background-color:#FFF">
<div id="mainholder"  style="width:185px; height:800px; overflow:hidden; background:#FFFFFF; padding:2px; position:relative">
  <div id="flashnewsholder">
    <div id="flashnews" style="width:185px; height:800px; background:#FFFFFF">
      <div>
        <div style="padding:2px;" align="justify">
          <div style="background-color:#009933; color:#ffffff; font-weight:bold; margin-bottom:1px; padding:2px; text-align:center">---- Flash News ----</div>
          <? echo($grid);?><br />
        </div>
      </div>
    </div>
  </div>
  <div id="productsholder" style="display:none">
    <center>
      <div style="background-color:#069; color:#ffffff; font-weight:bold; margin-bottom:5px; padding:2px; text-align:center">Other Relyon Products</div>
      <a href="http://imax.relyonsoft.com/api/redirect.php?url=http://www.saralpaypack.com&source=SaralTDSDesktopFlashNews" target="_blank"><img src="images/tds-flash-spp-badge.jpg" alt="SaralPayPack.com | Payroll Software" width="179" height="54" border="0" align="middle" title="SaralPayPack.com | Payroll Software" /></a><br />
      <a href="http://imax.relyonsoft.com/api/redirect.php?url=http://www.saraltaxoffice.com&source=SaralTDSDesktopFlashNews" target="_blank"><img src="images/tds-flash-sto-badge.jpg" alt="SaralTaxOffice.com | Tax Software" width="179" height="56" border="0" align="middle" title="SaralTaxOffice.com | Tax Software" /></a><br />
      <a href="http://imax.relyonsoft.com/api/redirect.php?url=http://www.saralaccounts.com&source=SaralTDSDesktopFlashNews" target="_blank"><img src="images/tds-flash-sai-badge.jpg" alt="SaralAccounts.com | Accounting Software" width="179" height="54" border="0" align="middle" title="SaralAccounts.com | Accounting Software" /></a><br />
      <br />
    </center>
    <div align="justify" >For more information / demo, please write to sales@relyonsoft.com or call at 080-23002144. </div>
    <br />
    <div align="right">You can also <a href="http://www.relyonsoft.com/buy/index.php" target="_blank"><img src="images/tds-flash-buynow-button.jpg" alt="Buy Online" width="85" height="22" border="0" align="absmiddle" /></a></div>
    <br />
    <div align="left">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td width="36"><a href="http://www.facebook.com/RelyonFB" target="_blank" ><img src="images/tds-flash-facebook-icon.jpg" alt="Facebook Page" width="32" height="32" border="0" align="absmiddle" /></a></td>
          <td align="justify">LIKE our Facebook Page and get continuous information.</td>
        </tr>
      </table>
    </div>
  </div>
  <div id="messagesholder" style="display:none; text-align:justify;" >
    <div style="background-color:#6b7fad; color:#ffffff; font-weight:bold; margin-bottom:5px; padding:2px; text-align:center;" >Alerts from Relyon
      <input type="hidden" id="messageidhidden" value=""  style="margin:0px; padding:0px"/>
    </div>
    <div class="scroll-pane" id="messagescrollgrid">
      <div id="messagegriddisplay" align="center" style="display:block"></div>
    </div>
    <div id="messagedetailsdisplay" align="center" style="display:none"></div>
  </div>
  <div id="rateitholder" style="display:none; text-align:justify">
    <div style="background-color:#930; color:#ffffff; font-weight:bold; margin-bottom:5px; padding:2px; text-align:center">Rate the features / services
      <input type="hidden" id="featureidhidden" value="" />
    </div>
    <div class="scroll-pane" id="rateitscrollgrid">
      <div id="rateitgrid" align="center;" style="display:block ; text-align:center;;" ></div>
    </div>
    <div class="scroll-pane" id="rateitscrollgridcontent">
      <div id="rateitgriddetails" style=" display:none; " >
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td colspan="2"><strong>
              <div id="feature"></div>
              </strong></td>
          </tr>
          <tr>
            <td colspan="2"><div id="descriptionless" align="justify" style="display:block;" ></div>
              <div id="descriptionmore" align="justify" style="display:none;"></div></td>
          </tr>
          <tr>
            <td colspan="2" height="25px"><div id="errormessage"></div></td>
          </tr>
          <tr>
            <td><div align="left"><strong>Rating: </strong></div></td>
            <td><div id="click" align="right"></div></td>
          </tr>
          <tr>
            <td colspan="2"><div align="left"><strong>Name:</strong>
                <input name="rateitfieldname" type="text" class="formfields" maxlength="100" autocomplete="no"  id="rateitfieldname"/>
              </div></td>
          </tr>
          <tr>
            <td colspan="2"><div align="left"><strong>Feedback:</strong></div></td>
          </tr>
          <tr>
            <td colspan="2"><textarea name="rateitfeedback" class="formfieldstextarea"  id="rateitfeedback"  style="resize:none; height:100px" ></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><div align="left"><img src="images/finishrating.gif" width="87" height="21" onclick="finishrating();" style="cursor:pointer" /> &nbsp;&nbsp;<img src="images/cancel.gif" width="46" height="21"  onclick="getrateitgrid()" style="cursor:pointer" /></div></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div id="supportholder" style="display:none; text-align:justify">
    <div style="background-color:#666; color:#ffffff; font-weight:bold; margin-bottom:5px; padding:2px; text-align:center">Contact Relyon Support </div>
    <div id="submitquerydiv" style="display:block"> Write your query and send it to Relyon.<br/>
      <form id="submitform" name="submitform" action="" method="post" onsubmit="return false;" style="margin:0px; padding:0px">
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td valign="top" width="35">Name:</td>
            <td valign="top"><input name="customername" type="text" class="formfieldssupport" maxlength="25" autocomplete="no" id="customername"/></td>
          </tr>
          <tr>
            <td valign="top">Mobile:</td>
            <td valign="top"><input name="customerphone" type="text" class="formfieldssupport" maxlength="10" autocomplete="no" id="customerphone"/></td>
          </tr>
          <tr>
            <td valign="top">Email:</td>
            <td valign="top"><input name="customeremail" type="text" class="formfieldssupport" maxlength="100" autocomplete="no" id="customeremail"/></td>
          </tr>
          <tr>
            <td valign="top">Place:</td>
            <td valign="top"><input name="customerplace" type="text" class="formfieldssupport" maxlength="100" autocomplete="no" id="customerplace"/></td>
          </tr>
          <tr>
            <td valign="top">Query:</td>
            <td valign="top"><textarea name="customerquery" class="formfieldstextareasupport" style="height:100px; max-height:100px; min-height:100px;resize:none;" id="customerquery"></textarea></td>
          </tr>
          <tr>
            <td valign="top" colspan="2" style="height:30px;"><div id="supporterrormeg"></div></td>
          </tr>
          <tr>
            <td valign="top" colspan="2"><div align="center"><img src="images/submit.gif" width="87" height="21" onclick="submitsupportquery();" style="cursor:pointer" /> &nbsp;<img src="images/cancel.gif" width="46" height="21"  onclick="resetsubmitform();"  style="cursor:pointer"/></div></td>
          </tr>
        </table>
      </form>
    </div>
    <div id="queryresultdiv" style="display:none;margin-top:30px;" align="center"></div>
  </div>
</div>
<div id="bottombar" style="position: absolute; top: 815px; height: 30px; width: 189px; display: block; background-color: #ffffff; border-top: 2px solid #F30; left: 7px;">
  <div id="buttons" align="center"> <img onclick="changetab('flashnewsholder');" src="images/tds-flash-home-icon.jpg" title="Home | TDS Flash News" alt="Home | TDS Flash News" width="25" height="25" align="middle" class="bottombaricons" /> <img onclick="changetab('rateitholder');getrateitgrid();" src="images/tds-flash-rateit-icon.jpg" title="Rate Features and Services" alt="Rate Features and Services" width="24" height="25" align="middle" class="bottombaricons" /><span id="alertdiv"> <img onclick="changetab('messagesholder');customeralertsdetails();" src="images/tds-flash-mailread-icon.jpg" title="Alerts / Messages" alt="Alerts / Messages" width="25" height="25" align="middle" class="bottombaricons" /> </span> <img onclick="changetab('productsholder');" src="images/tds-flash-otherproducts-icon.jpg" title="Relyon Products" alt="Relyon Products" width="30" height="25" align="middle" class="bottombaricons" /><img onclick="changetab('supportholder'); $('#submitquerydiv').show();$('#customername').focus();resetsubmitform();" src="images/tds-flash-support-button.jpg" title="Request for Support" alt="Request for Support" width="45" height="25" align="middle" class="bottombaricons" /> </div>
</div>
</body>
</html>