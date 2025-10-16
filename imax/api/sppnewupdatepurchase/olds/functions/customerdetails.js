// JavaScript Document
function formsubmit()
{
	var res=document.getElementById('lastslno').value;
        var customerid =  $("#customerid").val()
	
	var passData =  "switchtype=customerdetails&customerid="+ encodeURIComponent($("#customerid").val()) + 
	"&invoiceslno=" + encodeURIComponent($("#lastslno").val()) +"&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData);
	$.ajax(
		{
			type : "POST",url:"ajax/customerdetails.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
				   $("#new_lslnop").val(ajaxresponse[2]);
				   document.getElementById('proceedforpayment').disabled = true;
				   document.getElementById("submitpayform").submit();
				}
			}
			
		});
}