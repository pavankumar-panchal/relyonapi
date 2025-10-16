<?
if($p_productmaster <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
?>
<script src="../functions/pm.js?dummy=<? echo (rand());?>" language="javascript"> </script>
<script type="text/javascript">
$(document).ready(function()
{
	newentry();	
	productmaster();  // For first time page load default results
	
});
</script>
  <form id="leaduploadform" name="leaduploadform">
    <table cellpadding="4" cellspacing="0" id="txtdb">
      <tr>
        <td class="general_text"><input type="hidden" name="form_prdid" id="form_prdid" class="textfield"/>
          Product Name </td>
        <td class="myinput">:</td>
        <td class="myinput"><input name="form_product" class="textfield" type="text" id="form_product" size="34" /></td>
      </tr>
      <tr>
        <td width="200" class="general_text">Product URL</td>
        <td width="1" class="myinput">:</td>
        <td class="myinput"><textarea rows="2" name="product_url" type="text" class="textfield" id="product_url" style="width:300px"></textarea>
        <!--<input name="product_url" class="textfield" type="text" id="product_url" size="34" />--></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input name="save" type="button" class="go_button" id="save" onClick="formsubmit('');" value="Save" />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="new" class="go_button" type="reset" id="new" value="Reset"  onClick="newentry();document.getElementById('msg_box').innerHTML='';document.getElementById('form_prdid').value='';" />
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
                  <table width="50%" border="0" cellspacing="0" cellpadding="0" >
                    <tr class="headerline">
                      <td width="80%" ><strong>&nbsp;Product Master:<span id="gridprocessf"></span></strong></td>
                      <td align="left"><span id="gridprocessf1"></span></td>
                    </tr>
                    <tr>
                      <td colspan="3" >
                      	<div id="tabgroupc1" style="overflow:auto; height:280px; width:100%" align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
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