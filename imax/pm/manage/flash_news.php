<?php
if($p_flashnewsupdate <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
?>
<script src="../functions/flash_news.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function()
{
	
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
        <td width="200" valign="top" class="general_text">Product Name </td>
        <td width="1" valign="top">:</td>
        <td valign="top"><select name="form_product" class="textfield" id="form_product" style="width:265px;" onChange="flashnews('active');document.getElementById('form_title').value='';
			document.getElementById('DPC_date').value='<?php echo '0000-00-00'; ?>';
			document.getElementById('form_desc').value='';
            document.getElementById('form_flashid').value='';
			document.getElementById('form_disable').checked=null">
            
            <?php productname();?>
          </select></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Title Of Product </td>
        <td valign="top">:</td>
        <td valign="top"><span class="general_text">
          <input name="form_flashid" type="hidden" class="formfields" id="form_flashid" />
          <input name="form_title" type="text" class="textfield" id="form_title" size="34" />
          </span></td>
      </tr>
      
      <tr>
        <td valign="top" class="general_text">URL Path </td>
        <td valign="top">:</td>
        <td valign="top"><textarea rows="2" name="form_link" type="text" class="textfield" id="form_link" style="width:260px"></textarea></td>
        <!--<input name="form_patch" type="text" class="formfields" id="form_patch" size="40" maxlength="10" /></td>--> 
      </tr>
      
      <tr>
        <td valign="top" class="general_text">Create Date</td>
        <td valign="top">:</td>
        <td valign="top"><input  name="DPC_date1" type="text" class="textfield" id="DPC_date1" value="<?php echo date('Y-m-d') ?>" size="20" autocomplete="off" />
          &nbsp;(YYYY-MM-DD)&nbsp; </td>
      </tr>
      
      <tr>
        <td valign="top" class="general_text">Description Of Product</td>
        <td valign="top">:</td>
        <td valign="top"><textarea rows="2" name="form_desc" type="text" class="textfield" id="form_desc" style="width:260px"></textarea></td>
        <!--<input name="form_patch" type="text" class="formfields" id="form_patch" size="40" maxlength="10" /></td>--> 
      </tr>
      <tr>
        <td valign="top" class="general_text">Valid Till</td>
        <td valign="top">:</td>
        <td valign="top"><input  name="DPC_date" type="text" class="textfield" id="DPC_date" value="<?php echo '0000-00-00'; ?>" size="20" autocomplete="off" />
          &nbsp;(YYYY-MM-DD)&nbsp; </td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Disable</td>
        <td valign="top">:</td>
        <td valign="top"><input name="form_disable" type="checkbox" class="textfield" id="form_disable"  /></td>
      </tr>
      <tr>
        <td colspan="3" valign="top"><div align="center" style="padding-left:180px;">
            <input name="save" class="go_button" type="button" id="save" value="Save" onClick="formsubmit('');" />
            <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="delete" type="button" id="delete" value="Delete" />--> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="new" type="reset" id="new" class="go_button" value="Reset"  onClick="newentry();document.getElementById('txtHint').innerHTML='';document.getElementById('msg_box').innerHTML='';document.getElementById('container').innerHTML=(' Select Product ');document.getElementById('form_flashid').value='';" />
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
                     <tr style="border-left:none; border-right:none;">
                    	<td style="padding:0; border:none;" width="26%">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                          <td width="84px" align="center" id="tabgrouph1" onclick="gridtab2('1','tabgroup','active');" style="cursor:pointer" class="grid-active-tabclass">Active</td>
                          <td width="2">&nbsp;</td>
                          <td width="84px" align="center" id="tabgrouph2" onclick="gridtab2('2','tabgroup','disabled');" style="cursor:pointer" class="grid-tabclass">Disabled</td>
                          <td width="2">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                       
                    </table></td>
                  </tr>
                    
                    <tr class="headerline">
                      <td width="50%" ><strong>&nbsp;Flash News:<span id="gridprocessf"></span></strong></td>
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
                        <div id="tabgroupc2" style="overflow:auto;  height:260px;  display:none; width:100%" align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #c7c9d2; border-top:none;">
                            <tr>
                              <td><div id="tabgroupgridf1_2" align="center"></div></td>
                            </tr>
                            <tr>
                              <td><div id="tabgroupgridlinkf2"  align="left" style="height:20px; "></div></td>
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
<?php }?>