<?
if($p_saralmail <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
?>
<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="../functions/jquery-ui.min.js"></script><!-- end of popup-->
<script src="../functions/employee.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function()
{
	newentry();
	refreshemployeearray();
	geturllink();
});


$("#genpass").click(function()
{
	loadData();  // For first time page load default results
});
$("#forwardslist").click(function()
{
	loadDataforwarder();  // For first time page load default results
});

</script>
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

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
  <tr>
    <td width="16%" align="top" class="active-leftnav" style="font-family: Verdana, Geneva, sans-serif; font-size:14px;">Employee Details</td>
    <td width="43%" align="top"><div align="right" style="padding:2px"> <font color="#FF6B24" style="font-family: Verdana, Geneva, sans-serif; font-size:14px;">Employee E-mail ID ?</font>&nbsp;&nbsp; : &nbsp;&nbsp;
        <input name="searchemployeemail" type="text" class="swifttext" id="searchemployeemail" onKeyUp="searchbyemployeemailevent(event);" style="width:130px"  maxlength="20"  autocomplete="off"/>
        &nbsp;&nbsp; <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbyemployeemail(document.getElementById('searchemployeemail').value);" style="cursor:pointer" /> </div></td>
  </tr>
  <tr>
    <td valign="top" width="30%"><table width="30%"  border="0" cellspacing="0" cellpadding="1" >
        <tr>
          <td valign="top"><table width="30%" border="0" cellspacing="0" cellpadding="0">
              <!--<tr>
                <td colspan="2" align="left"class="header-line" style="padding-left:7px" >Employee Selection</td>
              </tr>-->
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="30%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="73%" height="34" id="employeeselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="27%" ><div align="right"><a onclick="refreshemployeearray();" style="cursor:pointer; padding-right:15px;"><img src="../images/imax-employee-refresh.jpg" alt="Refresh Employee" border="0" title="Refresh Employee Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" ><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" size="29" onkeyup="employeesearch(event);"  autocomplete="off"/>
                          <span style="display:none">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
                          </span>
                          <div id="detailloademployeelist">
                            <select name="employeelist" size="5" class="swiftselect" id="employeelist" style="width:200px; height:355px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:7px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcount">&nbsp;</td>
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
            <td width="20%" valign="top" class="general_text">Employee Name</td>
            <td width="1" valign="top">:</td>
            <td valign="top"><input name="form_employee" type="text" placeholder="Employee name" class="textfield" id="form_employee" size="34" onchange="suggestionuser();formsubmit('checkmail');" ></td>
          </tr>
          <tr>
            <td valign="top" class="general_text">Employee ID </td>
            <td valign="top">:</td>
            <td valign="top"><input name="form_emailid" type="hidden" class="formfields" id="form_emailid" />
              <span id="empid"><input name="form_employeeid" placeholder="Enter only digit" type="text" class="textfield" id="form_employeeid" size="34" /></span>
              </td>
          </tr>
          <tr>
            <td valign="top" class="general_text">E-Mail Address
              <div id="apDiv2">(Just Enter username)</div></td>
            <td valign="top">:</td>
            <td valign="top">
            <span id="emailaddr"><input name="form_email" type="text" class="textfield" placeholder="Enter only username"  id="form_email" size="34" maxlength="200" onfocus="formsubmit('checkmail');" /></span>
              &nbsp;<a id="successimage" style="width:17px;"></a></td>
            <!--<input name="form_patch" type="text" class="formfields" id="form_patch" size="40" maxlength="10" /></td>--> 
          </tr>
          <tr>
            <td valign="top" class="general_text">Password </td>
            <td valign="top">:</td>
            <td valign="top">
            <span id="pass"><input name="form_password" type="text" class="textfield" id="form_password" placeholder="Double click here for random number" onDblClick="randompassword('form_password');" size="34" readonly></span>
              &nbsp;&nbsp;
              <? if($p_saralmail_resetpass == '1') {?>
              <a id="genpass" name="genpass" title="Reset Password" onClick="viewdialogbox('#changepassword');" style="cursor:pointer; font-size:12px; color:#FF4040;font-family: Verdana, Geneva, sans-serif;">Reset Password</a>
              <? }?>
              
              <!--           <img src="./images/search.gif" width="16" id="genpass" name="genpass" height="15" title="Reset Password" align="absmiddle" onDblClick="resetpass();" style="cursor:pointer" />
--></td>
          </tr>
          <tr>
            <td><div id="changepassword" title="Reset Password" style="display:none;">
                <table>
                  <tr>
                    <td valign="top" class="general_text">Reset Password</td>
                    <td valign="top">:</td>
                    <td valign="top"><input name="form_changepass" type="text" class="textfield" id="form_changepass" placeholder="Double click here for random number" onDblClick="randompassword('form_changepass');" size="34"></td>
                  </tr>
                  <tr>
                    <td valign="top" class="general_text">Reset Password Remark</td>
                    <td valign="top">:</td>
                    <td valign="top"><textarea rows="2" name="form_passremarks" type="text" class="swifttextarea" id="form_passremarks" style="width:260px"></textarea>
                      &nbsp;&nbsp; 
                      <!--<img src="../images/search.gif" width="16" id="submit" height="15" title="Submit" align="absmiddle" style="cursor:pointer" onClick="formsubmit('resetpwd');"/>--></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="center" valign="top" class="general_text"><input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('resetpwd');" />
                      
                      <!--<input type="button" class="button" style="cursor:pointer"  title="Submit" onclick="formsubmit('resetpwd');" value="Submit !">--></td>
                  </tr>
                </table>
                <!-- Reset Password Table-->
                <table width="100%">
                  <tr>
                    <td colspan="4"><table width="50%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td height="20px" colspan="4"><div id="tabgroupgridc1" >
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="headerline">
                                  <td width="80%" ><strong>&nbsp;Reset Password:<span id="gridprocessf"></span></strong></td>
                                  <td align="left"><span id="gridprocessf2"></span></td>
                                </tr>
                                <tr>
                                  <td colspan="3" ><div id="tabgroupgridc2" style="overflow:auto; height:140px; width:550px" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridf1_2" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridlinkf2"  align="left" style="height:20px; "></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="resultgridf2" style="overflow:auto; display:none; height:150px; width:340px;" align="center">&nbsp;</div></td>
                                </tr>
                              </table>
                            </div></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                 <!-- Reset Password Table-->
              </div></td>
          </tr>
          <tr>
            <td valign="top" class="general_text">Category</td>
            <td valign="top">:</td>
            <td valign="top"><select name="form_cid" class="textfield" id="form_cid" style="width:265px;">
                <?php category(); ?>
              </select></td>
          </tr>
          <tr>
            <td valign="top" class="general_text">Created  Date</td>
            <td valign="top">:</td>
            <td valign="top"><input  name="DPC_date" type="text" class="textfield" id="DPC_date" value="<?php echo date('Y-m-d') ?>" size="20" autocomplete="off" />
              &nbsp;&nbsp; (YYYY-MM-DD)</td>
          </tr>
          <tr>
            <td width="20%" valign="top" class="general_text">Grouphead </td>
            <td width="1" valign="top">:</td>
            <td valign="top"><select name="form_grouphead" class="textfield" id="form_grouphead" style="width:265px;">
                <?php grouphead(); ?>
              </select>
              
              <!--<input name="form_grouphead" type="text" class="textfield" id="form_grouphead" size="34">--></td>
          </tr>
          <tr>
            <td valign="top" class="general_text">Forwarder
              <div id="apDiv2">(opt)</div></td>
            <td valign="top">:</td>
            <td valign="top"><select name="form_forwards" class="textfield" id="form_forwards" style="width:265px;">
                <?php forwards(); ?>
              </select>
              &nbsp;&nbsp;<a id="forwardslist" name="forwardslist" title="Forwarder List" onClick="viewdialogbox('#forwarderlist');" style="cursor:pointer; font-size:12px; color:#FF4040;font-family: Verdana, Geneva, sans-serif;">Forwarder List</a></td>
          </tr>
          <tr>
            <td width="20%" valign="top" class="general_text">Department</td>
            <td width="1" valign="top">:</td>
            <td valign="top"><input name="form_department" type="text" class="textfield" id="form_department" size="34"></td>
          </tr>
          <tr>
            <td><div id="forwarderlist" title="Forwarder List" style="display:none;">
            <!-- Forwarder table-->
                <table width="100%">
                  <tr>
                  <td colspan="4" valign="middle" >
                  <table width="100%" style="margin-top:5%" align="center" border="0" cellspacing="0" cellpadding="0">          
                  <tr>
                    <td height="20px" colspan="4">
                    
                        <div id="tabgroupgridc5" style="display:none;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                                
                            <tr class="headerline">
                              <td width="80%" ><strong>&nbsp;Forwarder List:<span id="gridprocessf"></span></strong></td>
                              <td align="left"><span id="gridprocessf1"></span></td>
                            </tr>
                            <tr>
                              <td colspan="3" >
                                <div id="tabgroupgridc1" style="overflow:auto; height:140px; width:100%" align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
  
   <!-- Forwarder table-->
              </div></td>
          </tr>
          <tr>
            <td width="20%" valign="top" class="general_text">E-mail Quota (MB) </td>
            <td width="1" valign="top">:</td>
            <td valign="top"><input name="form_quota" type="text" class="textfield" id="form_quota" value="250" size="34" readonly="readonly"></td>
          </tr>
          
          
          
          <tr>
            <td width="20%" valign="top" class="general_text">Requested By </td>
            <td width="1" valign="top">:</td>
            <td valign="top"><input name="form_requestedby" type="text" class="textfield" id="form_requestedby" size="34"></td>
          </tr>
          <tr>
            <td width="20%" valign="top" class="general_text">Reason</td>
            <td width="1" valign="top">:</td>
            <td valign="top"><textarea rows="2" name="form_reason" type="text" class="swifttextarea" id="form_reason" style="width:260px"></textarea></td>
          </tr>
          <tr>
            <td width="20%" valign="top" class="general_text">Remarks</td>
            <td width="1" valign="top">:</td>
            <td valign="top"><textarea rows="3" name="form_remarks" type="text" class="swifttextarea" id="form_remarks" style="width:260px"></textarea></td>
          </tr>
          <tr>
            <td valign="top" class="general_text">Disable Login
              <div id="apDiv2">(To Disable Login)</div></td>
            <td valign="top">:</td>
            <td valign="top"><input name="check_disable" type="checkbox" class="number" id="check_disable" /></td>
          </tr>
          <tr>
            <td colspan="3" valign="top"><div id="msg_box" style="display:none"></div></td>
          </tr>
          <tr>
            <td colspan="3" valign="top"><div id="form-error"> </div></td>
          </tr>
          <tr>
            <td colspan="3" valign="top"><div align="center">
                <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="delete" type="button" id="delete" value="Delete" />--> 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="new" type="reset" id="new" class="swiftchoicebutton" value="New"  onClick="newentry();document.getElementById('form-error').innerHTML='';document.getElementById('msg_box').innerHTML='';document.getElementById('form_emailid').value='';" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="delete" type="button" class="swiftchoicebutton" id="delete" value="Delete" onClick="formsubmit('delete');" />
              </div></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
<? }?>
