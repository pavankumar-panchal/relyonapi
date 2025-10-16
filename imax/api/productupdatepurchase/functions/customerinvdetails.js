// JavaScript Document
function formsubmit()
{	
	var passData =  "switchtype=customerdetails&lastslno="+ encodeURIComponent($("#lastslno").val()) + 
	"&invoicelist=" + encodeURIComponent($("#invno").val()) 
	 +"&dummy=" + Math.floor(Math.random()*100000000);
	
	$.ajax(
		{
			type : "POST",url:"../ajax/customerinvdetails.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
				   $("#lastslno").val(ajaxresponse[1]);
				   document.getElementById('proceedforpayment').disabled = true;
				   document.getElementById("submitpayform").submit();
				}
			}			
		});
}