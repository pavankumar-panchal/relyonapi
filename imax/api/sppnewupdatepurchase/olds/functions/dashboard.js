function paynow(onlineslno)
{        
	  loadPopupBox();
	$("body").append('<div class="modalOverlay">'); 
	$('#lslnop').val(onlineslno);
	$('#onlineinvoiceno').val(onlineslno);
}
function unloadPopupBox() {    // TO Unload the Popupbox
	var msg='&nbsp;';
	$('#err').html(msg);
	$('#invoicedetailsgrid').fadeOut("slow");
	/*$('body').css({ // this is just for style        
		"opacity": "1"  
	});*/ 
}    

function loadPopupBox() {    // To Load the Popupbox
	$('#invoicedetailsgrid').fadeIn("slow");
	/*$('body').css({ // this is just for style
		"opacity": "0.3"  
	});*/         
} 