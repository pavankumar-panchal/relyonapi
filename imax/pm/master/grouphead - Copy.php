<?
if($p_grouphead <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
?>
<script src="../functions/grouphead.js?dummy=<? echo (rand());?>" language="javascript"> </script>
<script type="text/javascript">
$(document).ready(function()
{
	newentry();
	grouphead();  // For first time page load default results
});
</script>

  <form id="leaduploadform" name="leaduploadform">
    <table cellpadding="4" cellspacing="0" id="txtdb">
      <tr>
        <td class="general_text"><input type="hidden" name="form_id" id="form_id" class="textfield"/>
          Grouphead Name </td>
        <td class="myinput">:</td>
        <td class="myinput"><input name="form_head" class="textfield" placeholder="Enter grouphead name" type="text" id="form_head" size="34" /></td>
      </tr>
      <tr>
        <td width="200" class="general_text">Grouphead Email</td>
        <td width="1" class="myinput">:</td>
        <td class="myinput"><input  name="form_email" type="text" placeholder="Enter email address" class="textfield" id="form_email" size="34">
      </tr>
      <tr>
        <td width="200" class="general_text">Forwarder Email<br/> <em style="font-size:10px; color:#F00"> (only username)</em></td>
        <td width="1" class="myinput">:</td>
        <td class="myinput"><input  name="form_forwarder" placeholder="Enter only username" type="text" class="textfield" id="form_forwarder" size="34">
      </tr>
      <tr>
        <td width="200" class="general_text">Department</td>
        <td width="1" class="myinput">:</td>
        <td class="myinput"><input  name="form_depart" type="text" placeholder="Enter Department" class="textfield" id="form_depart" size="34">
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input name="save" type="button" class="go_button" id="save" onClick="formsubmit('');" value="Save" />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="new" class="go_button" type="reset" id="new" value="Reset"  onClick="newentry();document.getElementById('msg_box').innerHTML='';document.getElementById('form_id').value='';" />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="delete" type="button" id="delete" class="go_button" value="Delete"  onClick="deletesubmit();" />
         </td>
      </tr>
    </table>
  </form>
 <table border="0" cellspacing="0" cellpadding="4" width="100%">
    <tr>
        <td colspan="3" valign="top"><div id="msg_box" style="display:none"></div></td>
      </tr>
       <tr>
        <td colspan="3" valign="top"><div id="form-error"></div></td>
      </tr>
      <tr>
      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">          
          <tr>
            <td height="20px" colspan="3">
                <div id="tabgroupgridc5" style="display:none;" >
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr class="headerline">
                      <td width="50%" ><strong>&nbsp;Grouphead Master:<span id="gridprocessf"></span></strong></td>
                      <td align="left"><span id="gridprocessf1"></span></td>
                    </tr>
                    <tr>
                      <td colspan="3" >
                      	<div id="tabgroupc1" style="overflow:auto; height:280px; width:100%" align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #c7c9d2; border-top:none;">
                            <tr>
                              <td><div id="tabgroupgridf1_1" align="center"></div></td>
                            </tr>
                            <tr>
                              <td><div id="tabgroupgridlinkf1"  align="left" style="height:20px; "></div></td>
                            </tr>
                          </table>
                        </div>
                        
                        <div id="resultgridf1" style="overflow:auto; display:none; height:150px; width:100%;" align="center">&nbsp;</div></td>
                    </tr>
                  </table>
                </div>
              </td>
          </tr>
        </table></td>
    </tr>
  </table>


<? }?>