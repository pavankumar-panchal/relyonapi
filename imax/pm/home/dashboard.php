<?
include("../inc/eventloginsert.php");
?>
<script src="../functions/dashboard.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function()
{
	saralmail('active'); 
	flashnews('active'); 
	verhotfix('version');
	jobrequired('activecareer');
	activity();
	 
});

</script>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="70%"><h2>Summary</h2></td>
    <td width="30%"><h2>Recent Activity</h2></td>
  </tr>
  <tr>
    <td valign="top" width="70%" style="border-right:dashed #999999 1px;">
    <table width="100%">
        <!-- Version / hotfix-->
        <? if($p_versionupdate == '1' || $p_hotfixupdate == '1') {?>
        <tr>
          <td style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #c7c9d2; border-top:none;">
              <tr style="cursor:pointer" onClick="showhide('version','toggleimg');">
                <td class="header-line" style="padding:0">&nbsp;&nbsp;Recent details of Version/Hotfix</td>
                <td align="right" class="header-line" style="padding:0 5px 5px 0"><div align="right"> <img src="../images/minus.jpg" border="0" id="toggleimg" name="toggleimg"  align="absmiddle" /></div></td>
              </tr>
              <tr>
                <td colspan="2" valign="top"><div id="version">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td colspan="2" style="padding:2px"><div id="tabgroupdetail1">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                              <tr style="border-left:none; border-right:none;">
                                <td style="padding:0; border:none;" width="26%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                     <? if($p_versionupdate == '1'){ ?>
                                      <td width="84px" align="center" id="tabgrouph3" onclick="gridtab2('3','tabgroup','version');" style="cursor:pointer" class="grid-active-tabclass">Version</td>
                                      <? }else{ ?> <td width="84px" align="center">&nbsp;</td><? }?>
                                      <td width="2">&nbsp;</td>
                                      <? if($p_hotfixupdate == '1'){ ?>
                                      <td width="84px" align="center" id="tabgrouph4" onclick="gridtab2('4','tabgroup','hotfix');" style="cursor:pointer" class="grid-tabclass">Hotfix</td>
                                    	<? }else{ ?> <td width="84px" align="center">&nbsp;</td><? }?>
                                      <td width="2">&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr class="headerlinedisplay">
                                <td id="updatetype">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="3" ><div id="tabgroupc3" style="overflow:auto; width:100%;border:0px solid #c7c9d2; border-top:none;" align="center" >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="tabgroupgridf3_3" align="center"></div></td>
                                      </tr>
                                    </table>
                                  </div>
                                  <div id="tabgroupc4" style="overflow:auto;display:none; width:100%;border:0px solid #c7c9d2; border-top:none;" align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="tabgroupgridf4_4" align="center"></div></td>
                                      </tr>
                                    </table>
                                  </div></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <? }?>
        <!-- Version / hotfix--> 
       
        <?  if($p_saralmail == '1' || $p_saralmail_delete == '1' || $p_saralmail_disable == '1' ) {?>
        <!-- Saral Mail-->
        <tr>
          <td style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #c7c9d2; border-top:none;">
              <tr style="cursor:pointer" onClick="showhide('saralmail','toggleimg2');">
                <td class="header-line" style="padding:0">&nbsp;&nbsp;Recent details of Saral Mail</td>
                <td align="right" class="header-line" style="padding:0 5px 5px 0"><div align="right"> <img src="../images/minus.jpg" border="0" id="toggleimg2" name="toggleimg2"  align="absmiddle" /></div></td>
              </tr>
              <tr>
                <td colspan="2" valign="top"><div id="saralmail">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td colspan="2" style="padding:2px"><div id="tabgroupdetail2">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr style="border-left:none; border-right:none;">
                                <td style="padding:0; border:none;" width="26%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                     <? if($p_saralmail == '1'){ ?>
                                      <td width="84px" align="center" id="tabgroupgridh1" onclick="gridtab3('1','tabgroupgrid','active');" style="cursor:pointer" class="grid-active-tabclass">Active</td>
                                      <? }else{ ?> <td width="84px" align="center">&nbsp;</td><? }?>
                                      <td width="2">&nbsp;</td>
                                      <? if($p_saralmail_disable == '1'){ ?>
                                      <td width="84px" align="center" id="tabgroupgridh2" onclick="gridtab3('2','tabgroupgrid','disabled');" style="cursor:pointer" class="grid-tabclass">Disabled</td>
                                      <? }else{ ?> <td width="84px" align="center">&nbsp;</td><? }?>
                                      <td width="2">&nbsp;</td>
                                       <? if($p_saralmail_delete == '1'){ ?>
                                      <td width="84px" align="center" id="tabgroupgridh3" onclick="gridtab3('3','tabgroupgrid','deleted');" style="cursor:pointer" class="grid-tabclass">Deleted</td>
                                      <? }else{ ?> <td width="84px" align="center">&nbsp;</td><? }?>
                                      <td width="2">&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr class="headerlinedisplay">
                                <td id="saralmaildisplay">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="3" ><div id="tabgroupgridc1" style="overflow:auto;width:100%;border:0px solid #c7c9d2; border-top:none;" align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="tabgroupgridf1_1" align="center"></div></td>
                                      </tr>
                                    </table>
                                  </div>
                                  <div id="tabgroupgridc2" style="overflow:auto;display:none;width:100%;border:0px solid #c7c9d2; border-top:none;" align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="tabgroupgridf1_2" align="center"></div></td>
                                      </tr>
                                    </table>
                                  </div>
                                  <div id="tabgroupgridc3" style="overflow:auto;display:none;width:100%;border:0px solid #c7c9d2; border-top:none;" align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="tabgroupgridf1_3" align="center"></div></td>
                                      </tr>
                                    </table>
                                  </div></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
          <? }?>
        <!-- Saral Mail--> 
        
        <!-- Flash News-->
                <?  if($p_flashnewsupdate == '1') {?>

        <tr>
          <td style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #c7c9d2; border-top:none;">
              <tr style="cursor:pointer" onClick="showhide('flashnew','toggleimg3');">
                <td class="header-line" style="padding:0">&nbsp;&nbsp;Recent details of Flash News</td>
                <td align="right" class="header-line" style="padding:0 5px 5px 0"><div align="right">
                <img src="../images/plus.jpg" border="0" id="toggleimg3" name="toggleimg3"  align="absmiddle" /> </div></td>
              </tr>
              <tr>
                <td colspan="2" valign="top"><div id="flashnew" style="visibility:collapse;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td colspan="2" style="padding:2px"><div id="tabgroupdetail3">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr style="border-left:none; border-right:none;">
                                <td style="padding:0; border:none;" width="26%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td width="84px" align="center" id="tabgrouph1" onclick="gridtab2('1','tabgroup','active');" style="cursor:pointer" class="grid-active-tabclass">Enabled</td>
                                      <td width="2">&nbsp;</td>
                                      <td width="84px" align="center" id="tabgrouph2" onclick="gridtab2('2','tabgroup','disabled');" style="cursor:pointer" class="grid-tabclass">Disabled</td>
                                      <td width="2">&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr class="headerlinedisplay">
                                <td id="flashnewsdisplay">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="3" ><div id="tabgroupc1" style="overflow:auto;width:100%;border:0px solid #c7c9d2; border-top:none;" align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="tabgroupgridf2_1" align="center"></div></td>
                                      </tr>
                                    </table>
                                  </div>
                                  <div id="tabgroupc2" style="overflow:auto;display:none;width:100%;border:0px solid #c7c9d2; border-top:none;" align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="tabgroupgridf2_2" align="center"></div></td>
                                      </tr>
                                    </table>
                                  </div></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
         <? }?>
        <!-- Flash News--> 
        
        <!-- Job Required--> 
          <?  if($p_career == '1') {?>

        <tr>
  <td style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #c7c9d2; border-top:none;">
      <tr style="cursor:pointer" onClick="showhide('careerjob','toggleimg5');">
        <td class="header-line" style="padding:0">&nbsp;&nbsp;Recent details of Job Career</td>
        <td align="right" class="header-line" style="padding:0 5px 5px 0"><div align="right"> <img src="../images/plus.jpg" border="0" id="toggleimg5" name="toggleimg5"  align="absmiddle" /></div></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><div id="careerjob" style="visibility:collapse;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr valign="top">
                <td colspan="2" style="padding:2px"><div id="tabgroupdetail4">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                      <tr style="border-left:none; border-right:none;">
                        <td style="padding:0; border:none;" width="26%">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="84px" align="center" id="tabgrouph5" onclick="gridtab2('5','tabgroup','activecareer');" style="cursor:pointer" class="grid-active-tabclass">Active</td>
                              <td width="2">&nbsp;</td>
                              <td width="84px" align="center" id="tabgrouph6" onclick="gridtab2('6','tabgroup','disablecareer');" style="cursor:pointer" class="grid-tabclass">Disable</td>
                              <td width="2">&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr class="headerlinedisplay">
                        <td id="careerdisplay">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3" ><div id="tabgroupc5" style="overflow:auto; width:100%;border:0px solid #c7c9d2; border-top:none;" align="center" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div id="tabgroupgridf5_1" align="center"></div></td>
                              </tr>
                            </table>
                          </div>
                          <div id="tabgroupc6" style="overflow:auto;display:none; width:100%;border:0px solid #c7c9d2; border-top:none;" align="center">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div id="tabgroupgridf6_1" align="center"></div></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
            </table>
          </div></td>
      </tr>
    </table></td>
</tr>

		   <? }?>
        <!-- Job Required-->
        
      </table></td>
      
    <td valign="top" width="30%"><table width="100%">
        <tr >
          <td><div id="activity_form"></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
