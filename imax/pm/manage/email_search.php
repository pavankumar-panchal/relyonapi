<?php
if($p_saralmail_search <> '1') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	include("../inc/eventloginsert.php");
// Get date for From date field.

$month = date('m'); 
if($month >= '04')
   $date = '01-04-'.date('Y'); 
else 
{
	$year = date('Y') - '1';
	$date = '01-04-'.$year; //echo($date);
}

//Get current date for TO DATE field
$defaulttodate = datetimelocal("d-m-Y");

//Select the list of Grouphead for the drop-down
$query3 = "SELECT id,grouphead FROM email_grouphead ORDER BY grouphead";
$result3 = runmysqlquery($query3);
$groupheadselect = '<option value="" selected="selected">- - - - All - - - -</option>';
while($fetch = mysqli_fetch_array($result3))
{
	$groupheadselect .= '<option value="'.$fetch['id'].'">'.$fetch['grouphead'].'</option>';
}

//select the list of forwarder
$query = "SELECT distinct(forwards) FROM email_acc_record where (forwards != '' and !ISNULL(forwards)) ORDER BY forwards;";
$result = runmysqlquery($query);
$forwarderselect = '';
if(mysqli_num_rows($result) > 1)
{
	$forwarderselect .= '<option value="" selected="selected">- - - - All - - - -</option>';
}
while($fetch = mysqli_fetch_array($result))
{
	$forwarderselect .= '<option value="'.$fetch['forwards'].'">'.$fetch['forwards'].'</option>';
}

// For category List
$query6 = "select cid,category from email_mas_category order by category";
$result6 = runmysqlquery($query6);
$categoryselect .= '<option value="" selected="selected">- - - All - - - </option>';
while($fetch6 = mysqli_fetch_array($result6))
{
	$categoryselect .= '<option value="'.$fetch6['cid'].'">'.$fetch6['category'].'</option>';
}
?>
<script src="../functions/email_search.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function()
{
	updatestatusstrip();
});

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

  <!--Main Table -->
  <table border="0" cellspacing="0" cellpadding="4" width="100%">
    <!--Start Of Count MAil Status -->
    <tr>
      <td><table border="0" cellspacing="0" cellpadding="4" width="100%" style="color:#006699;border:solid 1px #999999"">
       <tr>
                <td style="font-size:20px;"><strong>Summary</strong></td>
                </tr>
          <tr>
            <td  height="10px"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <!--style="background:#FFFFCE" -->
                <tr>
                  <td width="25%" rowspan="2"><font color="#006699" style="font-size:20px">Total
                  <span  onclick="updatestatusstrip();" class="statusstripclass">&nbsp;&nbsp;&nbsp;&nbsp;
                  <img src="../images/imax-employee-refresh.jpg" alt="Refresh Total" border="0" title="Refresh Total Count" />
                  </span>:&nbsp;<span id = "emailtotal" style="color:#006699" ></span></font></td>
                  <td width="100%" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="12%" height="20"><div align="right" class="statusstriptextclass"><strong> Active :</strong></div></td>
                        <td width="5%"><div align="right"><span id="activemail"></span></div></td>
                        <td width="14%"><div align="right" class="statusstriptextclass"><strong>Disable :</strong></div></td>
                        <td width="6%"><div align="right"><span id="disabledmail"></span></div></td>
                        <td width="12%"><div align="right" class="statusstriptextclass"><strong>Deleted :</strong></div></td>
                        <td width="6%"><div align="right"><span id="deletedmail"></span></div></td>
                        <td width="14%"><div align="right" class="statusstriptextclass"><strong>Grouphead :</strong></div></td>
                        <td width="5%"><div align="right"><span id="groupheadcount"></span></div></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <!--End Of Count Mail Status -->
    <tr>
      <td>&nbsp;</td>
    </tr>
    <!--Start Of Filter Option -->
    
    <tr>
      <td colspan="4" style="color:#006699;border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td bgcolor="#DEE1FA"><strong>Filter: </strong>[<a style="cursor:pointer" onclick="newtog();">Show/Hide</a>]</td>
          </tr>
          <tr>
            <td><!--<form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" method="post" action="filteredtoexcel.php">-->
              
              <form id="filterform" name="filterform">
                <div id="divform">
                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <!--style="background-color:#ffffcc" -->
                    <tr>
                      <td>&nbsp;<strong>Search</strong>:
                        <input name="searchcriteria" type="text" class="formfields" id="searchcriteria"  size="30" maxlength="30" />
                        <font style="font-size:10px;" color="#666666">(Leave Empty for all)</font>
                        <input type="hidden" name="srchhiddenfield" id="srchhiddenfield" /></td>
                    </tr>
                    <tr>
                      <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border-bottom:1px solid #CCCCCC">
                          <tr>
                            <td><strong>Search For:</strong>
                              <label>
                                <input name="databasefield" type="radio"  value="empid" />
                                Employee ID</label>
                              <label>
                                <input name="databasefield" type="radio"  value="employeename" checked="checked"/>
                                Employee Name</label>
                              <label>
                                <input type="radio" name="databasefield" value="username" />
                                Username </label>
                              <label>
                                <input type="radio" name="databasefield" value="forwarder" />
                                Forwarder </label>
                              <label>
                                <input type="radio" name="databasefield" value="grouphead" />
                                Grouphead </label>
                              &nbsp;
                              <label>
                                <input type="radio" name="databasefield" value="category" />
                                Category</label>
                              <input type="hidden" name="subselhiddenfield" id="subselhiddenfield" />
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border-bottom:1px solid #CCCCCC;color:#006699;">
                          <!-- <tr>
                            <td width="11%">From Date : </td>
                            <td width="37%"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="20" maxlength="10" value="<?php /* echo($date); */ ?>"  style="width:50%" />
                              <input type="hidden" name="hiddenfromdate" id="hiddenfromdate" /></td>
                            <td width="13%">To Date : </td>
                            <td width="39%"><input name="todate" type="text" class="formfields" id="DPC_todate" size="20" maxlength="10" value="<?php /* echo($defaulttodate); */ ?>" style="width:50%" />
                              <input type="hidden" name="hiddentodate" id="hiddentodate" /></td>
                          </tr>-->
                          <tr>
                            <td width="13%">Group Head : </td>
                            <td width="37%"><select name="groupheadselect" class="formfields" id="groupheadselect" style="width:50%">
                                <?php echo($groupheadselect);?>
                              </select>
                              <input type="hidden" name="hiddengroupheadselect" id="hiddengroupheadselect" /></td>
                            <td width="13%">Forwarder: </td>
                            <td width="39%"><select name="forwarderselect" class="formfields" id="forwarderselect" style="width:50%">
                                <?php echo($forwarderselect);?>
                              </select>
                              <input type="hidden" name="hiddenforwarderselect" id="hiddenforwarderselect" /></td>
                          </tr>
                          <tr>
                            <td colspan="2">
                            <input name="dropactivestatus" type="checkbox" id="dropactivestatus" value="true" checked="checked" />
                              <label for="dropactivestatus">Consider Active </label>
                            <br />
                            <br />
                            <input name="dropterminatedstatus" type="checkbox" id="dropterminatedstatus" value="true" />
                              <label for="dropterminatedstatus">Consider Deleted </label>
                              <br />
                              <br />
                              <input name="dropdisablestatus" type="checkbox" id="dropdisablestatus" value="true" />
                              <label for="dropdisablestatus">Consider Disable </label>    
                              </td>
                            <td>Category:</td>
                            <td><select name="form_source" class="formfields" id="form_source" style="width:50%">
                                <?php echo($categoryselect);?>
                              </select>
                              <input type="hidden" name="hiddensource" id="hiddensource" /></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="center">
                       <input name="view" type="button" class="swiftchoicebutton" id="view" value="Show" onClick="filtering('view');" />
                          <!--<input name="view" type="button" class="formbutton" id="view" value="Show" onclick="filtering('view');" />-->
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                          <!-- <input name="excel" type="button" class="formbutton" id="excel" value="To Excel" onclick="filtering('excel');" />--> 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          
                          <input name="excel" type="button" class="swiftchoicebutton" id="resetform" value="Reset" onClick="filtering('resetform');" />
                          
                        <!--  <input name="excel" type="button" class="formbutton" id="resetform" value="Reset" onclick="filtering('resetform');" />-->
                        </div></td>
                    </tr>
                  </table>
                </div>
              </form></td>
          </tr>
        </table></td>
    </tr>
    
    <!--End Of Filter Option --> 
    <!--Start Of Grid -->
  </table>
  <table border="0" cellspacing="0" cellpadding="4" width="100%">
    <tr>
      <td colspan="4"><table border="0" width='100%' cellspacing="0" cellpadding="0">          
          <tr>
            <td height="20px" colspan="4"><form id="toexcelform" name="toexcelform" action="" method="post">
                <div id="tabgroupleadgridc5" style="display:block;" >
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                    <tr class="headerline">
                      <td width="100%" ><strong>&nbsp;List of Employee:<span id="gridprocessf"></span></strong></td>
                      <td align="left"><span id="gridprocessf1"></span></td>
                    </tr>
                    <tr>
                      <td colspan="3" ><div id="tabgroupgridf1" style="overflow:auto; height:260px; width:100%" align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><div id="tabgroupgridf1_1" align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="gridheader" height="20px">
                                      <td class="tdborderlead">&nbsp;Sl No</td>
                                      <td class="tdborderlead">&nbsp;Employee Name</td>
                                      <td class="tdborderlead">&nbsp;Email ID</td>
                                      <td class="tdborderlead">&nbsp;Grouphead</td>
                                      <td class="tdborderlead">&nbsp;Forwader</td>
                                      <td class="tdborderlead">&nbsp;Department</td>
                                      <td class="tdborderlead">&nbsp;Created Date</td>
                                    </tr>
                                  </table>
                                </div></td>
                            </tr>
                            <tr>
                              <td><div id="getmorelinkf1"  align="left" style="height:20px; "> </div></td>
                            </tr>
                          </table>
                        </div>
                        <div id="resultgridf1" style="overflow:auto; display:none; height:150px; width:840px;" align="center">&nbsp;</div></td>
                    </tr>
                    
                  </table>
                </div>
              </form></td>
          </tr>
        </table></td>
    </tr>
  </table>
  
  <!--End of Grid -->
  
  <table border="0" cellspacing="0" cellpadding="4" width="100%">
  <tr style="color:#006699">
  <td colspan="6"><strong>Category</strong></td></tr>
    <tr>
        <td class="cilent category"></td><td>Cilent</td>
        <td class="dealer category"></td><td>Dealer</td>
        <td class="general category"></td><td>General</td>
        <td class="specific category"></td><td>Specific</td>
        <td class="relyonite category"></td><td>Relyonite</td>
        <td class="consultant category"></td><td>Consultant</td>
        <td class="management category"></td><td>Management</td>
    </tr>
    <!--Msg Box -->
    <tr>
      <td colspan="3" valign="top"><div id="msg_box" style="display:none"></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top"><div id="form-error"> </div></td>
    </tr>
    <!--Msg Box -->
  </table>
  <!--End Main Table --> 

<?php } ?>