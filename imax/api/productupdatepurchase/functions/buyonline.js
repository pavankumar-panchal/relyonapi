// JavaScript Document
function formsubmit()
{
	var rowcount0 = $('#adddescriptionrows tr').length
	var rowcount = rowcount0-1;
	//alert(rowcount);
	
	//added on 4th Jan 2018
	var error = $('#form-error');
	var state_gst_code = $("#state_gst_code").val();
	
	var field = $('#gst_no');
	
	if(field.val() != '') { 
	    if(field.val() != 'Not Registered Under GST') { 
        	var gstinformat = new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$');
        	if (!gstinformat.test(field.val())) { error.html('State GST Code Not in Format.'); field.focus(); error.css({"color": "red"}); return false; } 
        	if(field.val()) { if(!validategstinregex(field.val(),state_gst_code)) { error.html('State GST Code Not Matching.'); field.focus(); error.css({"color": "red"}); 
        	return false; } }
	    }
	}
	if(field.val() == '' || field.val() == 'Not Registered Under GST') { 
      field.val('Not Registered Under GST');
	}
	
	//ends on 4th Jan 2018
	
	if(!$('.resultcheckbox').is(':checked'))
	{
		alert("Please check at least one checkbox.");
		  return false;
	}
	
	for(i=0,j=1; i<rowcount,j<=(rowcount); i++,j++)
	{
		if($('#resultcheckbox'+j).is(':checked'))
		{
			if(j==1)
		    var productrray = $("#purchasetype"+j).val() + '#' + $("#productname"+j).val() ;
		    else
		    productrray = productrray + '****' + $("#purchasetype"+j).val() + '#' + $("#productname"+j).val();
		}
	}
	//alert(productrray);
	var passData = "switchtype=getcartdetails&productrray=" + encodeURIComponent(productrray)
	+ "&custid=" + encodeURIComponent($("#custid").val()) + "&dealerid=" + encodeURIComponent($("#dealername").val())+ "&gst_no=" + encodeURIComponent($("#gst_no").val())
	+ "&dummy=" + Math.floor(Math.random()*10054300000); //alert(passData);return false;
	
	/* var passData = "switchtype=getcartdetails&productrray=" + encodeURIComponent(productrray)
	+ "&custid=" + encodeURIComponent($("#custid").val()) + "&dealerid=" + encodeURIComponent($("#dealername").val())
	+ "&dummy=" + Math.floor(Math.random()*10054300000); */
	$.ajax(
		{
			type : "POST",url:"ajax/buyonline.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
					/*var confirmation = confirm("Click 'OK' to proceed to Checkout. Click 'Cancel' to continue shopping");
					if(confirmation)
					{*/
						//alert(ajaxresponse[1]);
						window.location = './viewcart.php?productdata='+encodeURIComponent(ajaxresponse[1]).replace(/%20/g,'+');
						/*alert("done");
						$("#showproduct").hide();
						$("#showproductdetails").show();
						$("#showproductdetails").html(response[1]);*/
					//}
				}
				
			}
			
		});
	
}

function validategstinregex(value,stategstcode) {
 var valueEntered = value;
 var stategstcode = stategstcode;
 var valueEntered_new = valueEntered.substring(0, 2);
 
  if(valueEntered_new != stategstcode) {
    return false;
  }
  else
  {
      return true;
  }
}