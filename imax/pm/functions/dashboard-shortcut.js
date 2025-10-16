// JavaScript Document
function dasboard_short_keys()
{
		shortcut.add("Alt+Shift+V",function() {
window.location = "./index.php?a_link=version_product";
});
		shortcut.add("Alt+Shift+H",function() {
window.location = "./index.php?a_link=hotfix_product";
});

	shortcut.add("Alt+Shift+S",function() {
window.location = "./index.php?a_link=saralmail";
});

$(document).ready(function() {
  var  isShift = false;var  isAlt = false;
    $(document).keydown(function(e) {
        if(e.which == 16) 
		{
           isShift = true;
		}
		if(e.which == 18)
		{
            isAlt = true;
		}
        if(e.which == 191 && isShift && isAlt) {
            $("").colorbox({ inline:true, href:"#shortcut-grid"});
			isShift = false;
			isAlt = false;
			return false;
        }
    });
});

}