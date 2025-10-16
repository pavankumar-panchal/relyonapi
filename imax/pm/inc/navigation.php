<?php
$userid = imaxgetcookie('userid');
?>

<div id="menu-holder">
  <div>
    <ul class="menu">
      <li><a href="./index.php?a_link=home_dashboard"> Home </a></li>
    <?php  if($p_productmaster==1 || $p_main_product==1 || $p_grouphead==1){
        ?>
            <li><a href="#"> Master </a>
  <?php }?>
      <!-- Sub Menu -->
        <ul>
    <?php if($p_productmaster==1){ ?><li> <a href="./index.php?a_link=pm"> Product Master </a></li> <?php }?>
  <?php if($p_main_product==1){ ?><li><a href="./index.php?a_link=main_product"> Main Product </a></li><?php }?>
  <?php if ($p_grouphead==1){?> <li><a href="./index.php?a_link=grouphead"> Add Grouphead </a></li><?php }?>
    <?php if ($p_registration==1){ ?><li><a href="./index.php?a_link=registeruser"> Register User </a></li><?php }?>

         </ul>
     </li>

     
    <?php  if($p_versionupdate==1 ||$p_hotfixupdate==1 || $p_flashnewsupdate==1 || $p_career==1){
        ?> <li><a href="#"> Manage Product </a> 
  <?php }?>
          <!-- Sub Menu -->
            <ul>
            <?php if ($p_versionupdate==1){ ?><li><a href="./index.php?a_link=version_product"> Add Product Version </a></li><?php }?>
            <?php if ($p_hotfixupdate==1){ ?> <li><a href="./index.php?a_link=hotfix_product"> Add Hotfix Version </a></li> <?php }?>
            <?php if ($p_flashnewsupdate==1){ ?><li><a href="./index.php?a_link=flashnews"> Add Flash News </a></li><?php }?>
            <?php if ($p_career==1){ ?><li><a href="./index.php?a_link=career"> Job Required </a></li><?php }?>
            </ul>
        </li>
        
    <?php  if($p_saralmail==1 || $p_saralmail_disable==1 || $p_saralmail_search==1){
  ?><li><a href="#"> Manage Mail</a><?php }?>
     
      <!-- Sub Menu -->
        <ul>
           <?php if ($p_saralmail==1){ ?> <li><a href="./index.php?a_link=saralmail"> Create Official Email </a></li><?php }?>
      <?php if ($p_saralmail_disable==1){ ?><li><a href="./index.php?a_link=saralmail_disable"> Disabled Email </a></li><?php }?>
      <?php if ($p_saralmail_disable==1){ ?><li><a href="./index.php?a_link=emailsearch"> Mail ID Search </a></li><?php }?>
        </ul>
      </li>
      
      <li><a href="#"> More + </a>
            <!-- Sub Menu -->
        <ul>
          <!--<li><a href="export_list_product.php">Export Excel</a></li>-->
          <li><a href="./index.php?a_link=editprofile">Change Password</a>
        </ul>
      </li>
      <li class="current"><a href="../logout.php">Logout</a></li>
    </ul>
  </div>
 
</div>
<!--end menu-holder-->
