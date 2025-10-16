<?
if($p_hotfixupdate <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
?>
<script src="../functions/hotfix.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function()
{
	newentry();
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

  <form id="leaduploadform" name="leaduploadform" method="post" action="">
    <table border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="200" valign="top" class="general_text">Product Name</td>
        <td width="1" valign="top">:</td>
        <td valign="top"><select name="form_product" class="textfield" id="form_product" style="width:265px;" onChange="load_hotfix();prd_url();prdcode();hotfix();document.getElementById('form_productcode').value='';
			document.getElementById('msg_box').innerHTML='';
			document.getElementById('form_url').value='';
			document.getElementById('form_filesize').value='';
			document.getElementById('DPC_date').value='<?php echo date('Y-m-d') ?>';
			document.getElementById('form_hotfix').value='';
            document.getElementById('form_slno').value='';
			document.getElementById('check_web').checked=null;">
            <?php productname();?>
          </select></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Product Code </td>
        <td valign="top">:</td>
        <td valign="top"><input name="form_slno" type="hidden" class="formfields" id="form_slno" />
          <input name="form_productcode" type="text" class="textfield" id="form_productcode" size="34" /></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Patch Version</td>
        <td valign="top">:</td>
        <td valign="top"><select name="form_patch" class="textfield" id="form_patch" style="width:265px;">
            <option name="sel_prd" value=""> Select Product First </option>
          </select></td>
        <!--<input name="form_patch" type="text" class="formfields" id="form_patch" size="40" maxlength="10" /></td>--> 
      </tr>
      <tr>
        <td valign="top" class="general_text">Path URL 
        <div id="apDiv2">(Just Enter File Name After&quot;/&quot;)</div></td>
        <td valign="top">:</td>
        <td valign="top"><textarea rows="4" name="form_url" type="text" class="textfield" id="form_url" style="width:260px"></textarea>
        <!--<input name="form_url" type="text" class="textfield" id="form_url" size="34"  />-->
          &nbsp;&nbsp;
          <input type="button"name="submit" class="go_button" id="submit" value="Check URL" onClick="urlcheck();"/></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">File Size</td>
        <td valign="top">:</td>
        <td valign="top"><input name="form_filesize" type="text" class="textfield" id="form_filesize" size="34" maxlength="200" /></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Release Date</td>
        <td valign="top">:</td>
        <td valign="top"><input  name="DPC_date" type="text" class="textfield" id="DPC_date" value="<?php echo date('Y-m-d') ?>" size="20" autocomplete="off" />
          &nbsp;&nbsp; (YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Hotfix No.</td>
        <td valign="top">:</td>
        <td valign="top"><input name="form_hotfix" type="text" class="textfield" id="form_hotfix"  size="34" /></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Show In Web<div id="apDiv2">(To Activate in WEB)</div></td>
        <td valign="top">:</td>
        <td valign="top"><input name="check_web" type="checkbox" class="number" id="check_web"  /></td>
      </tr>
      <tr>
        <td colspan="3" valign="top"><div align="center">
            <input name="save" type="button" class="go_button" id="save" value="Save" onClick="formsubmit('');" />
            <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="delete" type="button" id="delete" value="Delete" />--> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="new" type="reset" id="new" class="go_button" value="Reset"  onClick="newentry();document.getElementById('txtHint').innerHTML='';document.getElementById('msg_box').innerHTML='';document.getElementById('container').innerHTML=(' Select Product ');document.getElementById('form_slno').value='';" />
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="delete" type="button" id="delete" class="go_button" value="Delete"  onClick="delsubmit();" />
          </div></td>
      </tr>
           
    </table>
  </form>
<table border="0" cellspacing="0" cellpadding="4" width="100%">
    <tr>
        <td colspan="3" valign="top"><div id="msg_box" style="display:none"></div></td>
      </tr>
       <tr>
        <td colspan="3" valign="top"><div id="form-error"> </div></td>
      </tr>
      <tr>
        <td colspan="3" valign="top"><div id="txtHint"> </div></td>
      </tr>
      <tr>
      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">          
          <tr>
            <td height="20px" colspan="3">
                <div id="tabgroupgridc5" style="display:none;" >
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr class="headerline">
                      <td width="50%" ><strong>&nbsp;HotFix Update:<span id="gridprocessf"></span></strong></td>
                      <td align="left"><span id="gridprocessf1"></span></td>
                    </tr>
                    <tr>
                      <td colspan="3" >
                      	<div id="tabgroupc1" style="overflow:auto; height:260px; width:100%" align="center">
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