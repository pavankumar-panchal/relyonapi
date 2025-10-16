// JavaScript Document
function formsubmit()
{
		
	var rowcount0 = $('#adddescriptionrows tr').length
	var rowcount = rowcount0-1;
	//alert(rowcount);
	
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
	+ "&custid=" + encodeURIComponent($("#custid").val()) + "&dealerid=" + encodeURIComponent($("#dealername").val())
	+ "&dummy=" + Math.floor(Math.random()*10054300000); //alert(passData);
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