// JavaScript Document
function formsubmit()
{
	var form = document.submitexistform;	
	var mode1=document.submitexistform.paymode[0].checked;
	var mode2=document.submitexistform.paymode[1].checked;
	var mode3=document.submitexistform.paymode[2].checked;
	var res=document.getElementById('lslnop').value;
        var customerid =  $("#customerid").val()
	//alert(res);
	var mode='';
	document.getElementById('custpayment').disabled = true;

	if(mode1==true && mode2==false && mode3==false)
	{
        $('.ajax-loader').css("visibility", "visible");
		//mode="credit";		
		form.action = 'makepayment/pay.php';

	var passData =  "switchtype=customerdetails&customerid="+ encodeURIComponent($("#customerid").val()) + 
	"&invoiceslno=" + encodeURIComponent($("#lslnop").val()) +"&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData);
	$.ajax({
			type : "POST",url:"ajax/customerdetails.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
				   $("#new_lslnop").val(ajaxresponse[2]);
		                   form.submit();
				   //document.getElementById("submitpayform").submit();
				}
			},
			 error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert(msg);
    },
    complete: function() {
        //$('.ajax-loader').css("visibility", "hidden");
    }
		});

	}
	else if(mode1==false && mode2==true && mode3==false)
	{
         $('.ajax-loader').css("visibility", "visible");
		//mode="internet";
		form.action = 'makepayment/paycitrus.php';

	var passData =  "switchtype=customerdetails&customerid="+ encodeURIComponent($("#customerid").val()) + 
	"&invoiceslno=" + encodeURIComponent($("#lslnop").val()) +"&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData);
	$.ajax({
			type : "POST",url:"ajax/customerdetails.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
				   $("#new_lslnop").val(ajaxresponse[2]);
		                   form.submit();
				   //document.getElementById("submitpayform").submit();
				}
			},
	error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert(msg);
    },
    complete: function() {
       //$('.ajax-loader').css("visibility", "hidden");
    }
			
		});
	}
	else if(mode1==false && mode2==false && mode3==true)
	{
         $('.ajax-loader').css("visibility", "visible");
		//mode="internet";
		form.action = 'makepayment/payrazor.php';

	var passData =  "switchtype=customerdetails&customerid="+ encodeURIComponent($("#customerid").val()) + 
	"&invoiceslno=" + encodeURIComponent($("#lslnop").val()) +"&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData);
	$.ajax({
			type : "POST",url:"ajax/customerdetails.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
				   $("#new_lslnop").val(ajaxresponse[2]);
		                   form.submit();
				   //document.getElementById("submitpayform").submit();
				}
			},
	error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert(msg);
    },
    complete: function() {
       //$('.ajax-loader').css("visibility", "hidden");
    }
			
		});
	}
	else
	{
		alert("Select mode of payment");
		document.getElementById("paymode").focus();
	        document.getElementById('custpayment').disabled = false;
	}
}