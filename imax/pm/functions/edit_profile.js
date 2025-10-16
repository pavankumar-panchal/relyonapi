function formsubmit()
{
	var form = $("#passFrom");
	var msg_box = $("#msg_box");
	var error = $("#form-error");
	
	var field = $('#curpass');
	if(!field.val()) { error.html(errormessage("Please Enter Current Password! ")); field.focus(); return false; }
	
	var field = $('#newpass');
	if(!field.val()) { error.html(errormessage("Please Enter New Password! ")); field.focus(); return false; }
	
	var field = $('#verifypass');
	if(!field.val()) { error.html(errormessage("Please Confirm the Password! ")); field.focus(); return false; }
	
	if($('#newpass').val() != $('#verifypass').val())
	{ error.html(errormessage("Please Confirm the Password! ")); field.focus(); return false; }
	
	var passdata = "submittype=save&curpass=" + encodeURIComponent($("#curpass").val()) + 
	"&name=" + encodeURIComponent($("#name").val()) + 
	"&newpass=" + encodeURIComponent($("#newpass").val()) + 
	"&verifypass=" + encodeURIComponent($("#verifypass").val()) + 
	"&emailid=" + encodeURIComponent($("#emailid").val()) + 
	"&dummy=" + Math.floor(Math.random()*10230000000); 
	
	//alert(passdata);
	
	var queryString = "../ajax/edit_profile_ajax.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
				//alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{
					error.html(successmessage(ajaxresponse[1]));
					newentry();
				}
				else if(ajaxresponse[0] == '2')
				{
					error.html(errormessage(ajaxresponse[1]));
				}
				else
				{
					error.html(scripterror());
				}
			
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}

function newentry()
{
	$('#curpass').val('');
	$('#newpass').val('');
	$('#verifypass').val('');
}