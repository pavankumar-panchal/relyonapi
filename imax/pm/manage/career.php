<?php
if($p_flashnewsupdate <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
?>
<script src="../functions/career.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--Start Of Editor -->

    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="../scripts/jHtmlArea-0.7.0.js"></script>
    <link rel="Stylesheet" type="text/css" href="../css/jHtmlArea.css" />
 
<!--Start Of PAgination -->
<script type="text/javascript">
$(document).ready(function()
{
	//$('#dialog').dialog({width: 420 });
	$('#form_qualification').htmlarea();
	$('#form_location').htmlarea();
	$('#form_profile').htmlarea();
	$('#form_attributes').htmlarea();
	$('#form_vacancies').htmlarea();
	$('#form_sl').htmlarea();
	$('#form_venue').htmlarea();
	
	$('#qualification iframe').attr('id','qualification');
	$('#location iframe').attr('id','location');
	$('#profile iframe').attr('id','profile');
	$('#attributes iframe').attr('id','attributes');
	$('#vacancies iframe').attr('id','vacancies');
	$('#sl iframe').attr('id','sl');
	$('#venue iframe').attr('id','venue');
		
});
</script>
<!--End Of PAgination -->

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
    <table border="0" cellspacing="0" cellpadding="4" width="100%">
      <tr>
        <td width="200" valign="top" class="general_text">Department</td>
        <td width="1" valign="top">:</td>
        <td valign="top"><select name="form_department" class="textfield" id="form_department"  onChange="career();department();reset_entry();
			document.getElementById('msg_box').innerHTML='';
			            document.getElementById('form_slno').value='';
			document.getElementById('show_web').checked=null;" style="width:265px">
            <?php department();?>
            <option value="others">Others</option>
			</select>
          <input name="form_department1" type="text" class="" id="form_department1" style="display:none;"/></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Job Code</td>
        <td valign="top">:</td>
        <td valign="top">
        <input name="form_jobcode" type="text" class="textfield" id="form_jobcode" size="34" /></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Experience</td>
        <td valign="top">:</td>
        <td valign="top">
        <input name="form_slno" type="hidden" class="formfields" id="form_slno" />
        <input name="form_experience" type="text" class="textfield" id="form_experience" size="34" /></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Commitment</td>
        <td valign="top">:</td>
        <td valign="top"><select name="form_commitment" class="textfield" id="form_commitment" style="width:265px;">
           <option value="">Make a Selection</option>
           <option value="Required to submit originals for 1 year">Required to submit originals for 1 year</option>
           <option value="Required to submit originals for 2 year">Required to submit originals for 2 year</option>
           <option value="Required to submit originals for 1.6 year">Required to submit originals for 1.6 year</option>
			</select></td>
      </tr>
      
      <tr>
        <td valign="top" class="general_text">Education Qualification </td>
        <td valign="top">:</td>
        <td valign="top" id="qualification">
       
	    <textarea id="form_qualification" name="form_qualification" class="textfield" rows="2" style="width:260px;"></textarea>

       <!-- <textarea rows="2" name="form_qualification" title="Education Qualification" type="text" class="textfield" id="form_qualification" style="width:260px" ></textarea>-->
          &nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Location</td>
        <td valign="top">:</td>
        <td valign="top" id="location"><textarea rows="2" title="Location" name="form_location" type="text" class="textfield" id="form_location" style="width:260px"></textarea></td>
      </tr>
      
      <tr>
        <td valign="top" class="general_text">Job  Profile</td>
        <td valign="top">:</td>
        <td valign="top" id="profile"><textarea rows="2" title="Job Profile" name="form_profile" type="text" class="textfield" id="form_profile" style="width:260px"></textarea></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Required Attributes</td>
        <td valign="top">:</td>
        <td valign="top" id="attributes"><textarea rows="2" title="Required Attributes" name="form_attributes" type="text" class="textfield" id="form_attributes" style="width:260px"></textarea></td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Spoken  Languages </td>
        <td valign="top">:</td>
        <td valign="top" id="sl"><textarea rows="2" title="Spoken  Languages" name="form_sl" type="text" class="textfield" id="form_sl" style="width:260px"></textarea></td>
      </tr>
      
      <tr>
        <td valign="top" class="general_text">Venue Details </td>
        <td valign="top">:</td>
        <td valign="top" id="venue"><textarea rows="2" title="Venue Details" name="form_venue" type="text" class="textfield" id="form_venue" style="width:260px"></textarea></td>
      </tr>
      
      <tr>
        <td valign="top" class="general_text">No. of Vacancies</td>
        <td valign="top">:</td>
        <td valign="top" id="vacancies">
        <textarea rows="2" title="No. of Vacancies" name="form_vacancies" type="text" class="textfield" id="form_vacancies" style="width:260px"></textarea>
        <!--<input name="form_vacancies" title="No. of Vacancies" type="text" class="textfield" id="form_vacancies" size="34" />-->
          &nbsp;&nbsp;</td>
      </tr>
      
      <tr>
        <td valign="top" class="general_text">Candidate Age</td>
        <td valign="top">:</td>
        <td valign="top">
        <input name="form_age" title="Age" type="text" class="textfield" id="form_age" size="34" />
          &nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Vehicle</td>
        <td valign="top">:</td>
        <td valign="top"><input name="form_vehicle" type="checkbox" class="textfield" id="form_vehicle" size="34" /> &nbsp; (check) = YES &nbsp; (uncheck) = NO </td>
      </tr>
      <tr>
        <td valign="top" class="general_text">Show In Web<div id="apDiv2">(To Activate in WEB)</div></td>
        <td valign="top">:</td>
        <td valign="top"><input name="show_web" type="checkbox" class="number" id="show_web"  />&nbsp; (check) = YES &nbsp; (uncheck) = NO
        </td>
      </tr>
           
       <tr>
        <td colspan="3" valign="top"><div align="center">
            <input name="save" type="button" class="go_button" id="save" value="Save" onClick="formsubmit('');" />
            <!--        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="delete" type="button" id="delete"  value="Delete" />-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="new" type="button" class="go_button" id="new" value="Reset" onClick="newentry();reset_entry();document.getElementById('msg_box').innerHTML='';document.getElementById('form_slno').value='';" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <input id="delete" class="go_button" type="button" onclick="delsubver();" value="Delete" name="delete">
          </div></td>
      </tr>
      <tr>
        <td colspan="3" valign="top"><div id="msg_box" style="display:none"></div></td>
      </tr>
       <tr>
        <td colspan="3" valign="top"><div id="form-error"></div></td>
      </tr>
     
    </table>
  </form>
<table border="0" cellspacing="0" cellpadding="4" width="100%">
    
      <tr>
      <td colspan="4"><table border="0" cellspacing="0" cellpadding="0">          
          <tr>
            <td height="20px" colspan="4">
                <div id="tabgroupgridc5" style="display:none;" >
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                    
                    <tr class="headerline">
                      <td width="50%" ><strong>&nbsp;Flash News:<span id="gridprocessf"></span></strong></td>
                      <td align="left"><span id="gridprocessf1"></span></td>
                    </tr>
                    <tr>
                      <td colspan="3" >
                      	<div id="tabgroupgridc1" style="overflow:auto; height:20%; width:840px" align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><div id="tabgroupgridf1_1" align="center"></div></td>
                            </tr>
                            <tr>
                              <td><div id="tabgroupgridlinkf1"  align="left" style="height:20px; "></div></td>
                            </tr>
                          </table>
                        </div>
                        
                        <div id="resultgridf1" style="overflow:auto; display:none; height:150px; width:840px;" align="center">&nbsp;</div></td>
                    </tr>
                  </table>
                </div>
              </td>
          </tr>
        </table></td>
    </tr>
  </table>
<?php }?>