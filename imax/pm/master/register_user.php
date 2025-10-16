<?php
if($p_registration <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
?>
<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="../functions/jquery-ui.min.js"></script>
<script src="../functions/register_user.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function()
{
	refreshuserarray(); 
	newentry();
});
</script>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
  <tr>
    <td width="16%" align="top" class="active-leftnav" style="font-family: Verdana, Geneva, sans-serif; font-size:14px;"> User Details</td>
    <td width="43%" align="top"><div align="right" style="padding:2px"> <font color="#FF6B24" style="font-family: Verdana, Geneva, sans-serif; font-size:14px;">Username OR Email-ID</font> &nbsp;&nbsp; : &nbsp;&nbsp;
        <input name="searchuseremail" type="text" class="swifttext" id="searchuseremail" onKeyUp="searchbyuseremailevent(event);" style="width:130px"  maxlength="20"  autocomplete="off"/>
        &nbsp;&nbsp; <img src="../images/search.gif" width="16" height="15" align="absmiddle" onclick="searchbyuseremail(document.getElementById('searchuseremail').value);" style="cursor:pointer" /> </div></td>
  </tr>
  <tr>
    <td valign="top" width="30%"><table width="30%"  border="0" cellspacing="0" cellpadding="1" >
        <tr>
          <td valign="top"><table width="30%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="30%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="73%" height="34" id="userselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="27%" ><div align="right"><a onclick="refreshuserarray();"style="cursor:pointer; padding-right:15px;"><img src="../images/imax-employee-refresh.jpg" alt="Refresh Employee" border="0"                             title="Refresh Employee Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" ><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" size="26" onkeyup="usersearch(event);"  autocomplete="off"/>
                          <span style="display:none">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
                          </span>
                          <div id="detailloaduserlist">
                            <select name="userlist" size="5" class="swiftselect" id="userlist" style="width:200px;height:355px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:7px;"><strong>Total Count:</strong></td>
                <td width="50%" id="totalcount">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="70%" valign="top" style="border-bottom:#1f4f66 0px solid;"><form id="leaduploadform" name="leaduploadform" method="post" action="">
        <table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td height="34">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table>
                <tr>
                  <td width="20%" valign="top" class="general_text">First Name</td>
                  <td width="1" valign="top">:</td>
                  <td valign="top"><input name="fname" type="text" class="textfield" placeholder="First name" size="34" id="fname" />
                    <input name="form_adminid" type="hidden" class="textfield" id="form_adminid"/></td>
                </tr>
                <tr>
                  <td valign="top" class="general_text">Last Name </td>
                  <td valign="top">:</td>
                  <td valign="top"><input name="lname" type="text" class="textfield" placeholder="Last Name" size="34"                              id="lname" /></td>
                </tr>
                <tr>
                  <td valign="top" class="general_text">User Name </td>
                  <td valign="top">:</td>
                  <td valign="top"><input name="login" type="text" placeholder="User Name" class="textfield" size="34"                               id="login" /></td>
                </tr>
                <tr>
                  <td valign="top" class="general_text">E-Mail Address
                    <div id="apDiv2"></div></td>
                  <td valign="top">:</td>
                  <td valign="top"><input name="email" type="text" placeholder="Enter only username" size="34" class="textfield" 														                             id="email"  maxlength="200" />
                    &nbsp;<a id="successimage" style="width:17px;"></a></td>
                </tr>
                <tr>
                  <td valign="top" class="general_text">Password </td>
                  <td valign="top">:</td>
                  <td valign="top"><input name="password" type="text" class="textfield" id="password"                   			                              placeholder="Double click here for random number" onDblClick="randompassword('password');"  size="34" />
                    &nbsp; <a id="genpass" name="genpass" title="Reset Password" onClick="randompassword('password');" style="cursor:pointer; font-size:11px; color:#FF4040;font-family: Verdana, Geneva, sans-serif;"></a></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <table width="79%">
          <tr>
            <td width="100%"  valign="top" class="general_text"><fieldset>
                <legend><strong>Mail Permission</strong></legend>
                <table width="100%">
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="mail_active" id="mail_active" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Active</td>
                    <td width="35" align="right"><input type="checkbox" name="mail_save" id="mail_save" value="1"></td>
                    <td align="left" width="200" valign="top" class="general_text">Save</td>
                  </tr>
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="mail_disable" id="mail_disable" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Disable</td>
                    <td width="35" align="right"><input type="checkbox" name="mail_delete" id="mail_delete" value="1"></td>
                    <td align="left" width="200" valign="top" class="general_text">Delete</td>
                  </tr>
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="mail_forward" id="mail_forward" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Forwarder</td>
                    <td width="35" align="right"><input type="checkbox" name="reset_password" id="reset_password" value="1"></td>
                    <td align="left" width="200" valign="top" class="general_text">Reset Password</td>
                  </tr>
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="mail_search" id="mail_search" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Search</td>
                  </tr>
                </table>
              </fieldset></td>
          </tr>
          <tr>
            <td width="100%"  valign="top" class="general_text"><fieldset>
                <legend><strong>Product Update permission</strong></legend>
                <table width="95%">
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="ver_update" id="ver_update" value="1"></td>
                    <td align="left" width="220" valign="top" class="general_text">Product Version</td>
                    <td width="35" align="right"><input type="checkbox" name="hot_update" id="hot_update" value="1"></td>
                    <td align="left" width="170" valign="top" class="general_text">Hotfix</td>
                    <td width="35" align="right"><input type="checkbox" name="flash_news" id="flash_news" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Flash News</td>
                  </tr>
                </table>
              </fieldset></td>
          </tr>
          <tr>
            <td width="100%"  valign="top" class="general_text"><fieldset>
                <legend><strong>Career Permission</strong></legend>
                <table width="40%">
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="job_req" id="job_req" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Job Requirement</td>
                  </tr>
                </table>
              </fieldset></td>
          </tr>
          <tr>
            <td width="100%"  valign="top" class="general_text"><fieldset>
                <legend><strong>Master Permission</strong></legend>
                <table width="90%">
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="prd_master" id="prd_master" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Product Master</td>
                    <td width="35" align="right"><input type="checkbox" name="main_prod" id="main_prod" value="1"></td>
                    <td align="left" width="200" valign="top" class="general_text">Main Product Update</td>
                  </tr>
                  <tr>
                    <td width="35" align="right"><input type="checkbox" name="grp_head" id="grp_head" value="1"></td>
                    <td align="left" width="150" valign="top" class="general_text">Grouphead</td>
                    <td width="35" align="right"><input type="checkbox" name="reg_form" id="reg_form" value="1"></td>
                    <td align="left" width="200" valign="top" class="general_text">Register Access</td>
                  </tr>
                </table>
              </fieldset></td>
          </tr>
        </table>
        <table width='78%'>
          <tr>
            <td colspan="6" valign="top"><div id="msg_box" style="display:none"></div></td>
          </tr>
          <tr>
            <td colspan="6" valign="top"><div id="form-error"> </div></td>
          </tr>
          <tr>
            <td colspan="3" valign="top"><div align="center">
                <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="new" type="reset" id="new" class="swiftchoicebutton" value="New"  onClick="newentry();document.getElementById('form-error').innerHTML='';document.getElementById('msg_box').innerHTML='';document.getElementById('form_adminid').value='';" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="delete" type="button" class="swiftchoicebutton" id="delete" value="Delete" onClick="formsubmit('delete');" />
              </div></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
<?php }?>
