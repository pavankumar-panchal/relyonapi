<?
	include("../inc/eventloginsert.php");
?>
<script src="../functions/edit_profile.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script type="text/javascript">
		jQuery(document).ready(function($){

/***** JQUERY MENU SLIDE EFFECT *****/							    
if (jQuery().superfish) {
		jQuery('ul.menu').superfish({
			delay: 230,
			animation: {opacity:'show', height:'show'},
			speed: 'fast',
			autoArrows: false,
			dropShadows: false
		}); 
	
	}	
	
	});  
/***** END JQUERY MENU SLIDE EFFECT *****/	
</script>
<!-- Start Generate Randow Password -->
<script language="javascript" type="text/javascript">
function randomString() 
{
    var chars = "0123456789";
    var string_length = 10;
    var randomstring = '';
    for (var i=0; i<string_length; i++) 
    {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum,rnum+1);
    }
    document.passFrom.newpass.value = randomstring;
    document.passFrom.verifypass.value = randomstring;
}
</script>

<form id="passFrom" name="passFrom">
  <table border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td width="200" align="left" valign="top" class="general_text"> Current Password <em>*</em></td>
      <td width="1">:</td>
      <td><input type="text" class="textfield" name="curpass" maxlength="30" id="curpass" ></td>
      <td><input type="hidden" name="name" id="name" maxlength="30"<?php if($username!="") echo "readonly"; ?> value="<?php echo $username;?>" ></td>
    </tr>
    <tr>
      <td align="left" valign="top" class="general_text"> New Password <em>*</em></td>
      <td>:</td>
      <td><input type="text" class="textfield" onCopy="return false" name="newpass" id="newpass" maxlength="30" ></td>
      <td></td>
    </tr>
    <tr>
      <td align="left" valign="top" class="general_text"> Confirm Password <em>*</em></td>
      <td>:</td>
      <td><input type="text" class="textfield" onpaste="return false" name="verifypass" id="verifypass" maxlength="30" ></td>
      <td>&nbsp;&nbsp;
        <input name="Generate Password" class="go_button" type="button" id="Genpass" value="Generate Password !" onClick="randomString();" ></td>
    </tr>
    <tr>
      <td align="left" valign="top" class="general_text">Email</td>
      <td>:</td>
      <td><input type="hidden" name="emailid" id="emailid" maxlength="30"<?php if($femail!="") echo "readonly"; ?> value="<?php echo $femail;?>" >
        <?php echo $femail;?></td>
      <td></td>
    </tr>
    <tr>
      <td height="35px;" colspan="7" valign="top" align="center"><input type="button" class="go_button" value="Change Password" onClick="formsubmit()"></td>
    </tr>
    <tr>
      <td colspan="2" align="left"></td>
    </tr>
    <tr>
      <td colspan="4" valign="top"><div id="msg_box" style="display:none"></div></td>
    </tr>
    <tr>
      <td colspan="4" valign="top"><div id="form-error"> </div></td>
    </tr>
    <tr>
      <td colspan="4" valign="top"><div id="txtHint"> </div></td>
    </tr>
  </table>
</form>