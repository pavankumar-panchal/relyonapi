// JavaScript Document
function formsubmit()
{
	//var form = document.submitexistform;
	var rowcount0 = $('#adddescriptionrows tr').length
	var rowcount = rowcount0-1;
	//alert($("#productlist").val());
	
	var passData =  "switchtype=customerdetails&lastslno="+ encodeURIComponent($("#lastslno").val()) + 
	"&productlist=" + encodeURIComponent($("#productlist").val()) + 
	"&usagelist=" + encodeURIComponent($("#usagelist").val()) 
	 +"&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData);
	$.ajax(
		{
			type : "POST",url:"ajax/customerdetails.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
				   $("#lastslno").val(ajaxresponse[2]);
				   document.getElementById('proceedforpayment').disabled = true;
				   document.getElementById("submitpayform").submit();
				}
			}
			
		});
}