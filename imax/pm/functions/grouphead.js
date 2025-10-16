// JavaScript Document grouphead.js


function formsubmit()
{
	var error = $('#msg_box');
	
	var field = $("#form_head");  
	if (!field.val())
	{ error.html(errormessage("Please Enter Grouphead Name.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}

	var field = $("#form_email");  
	if (!field.val())
	{ error.html(errormessage("Please Enter Email ID (Username).")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}

	var field = $("#form_depart");  
	if (!field.val())
	{ error.html(errormessage("Please Enter Department.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}

	var passdata = "&submittype=save&form_head=" + encodeURIComponent($("#form_head").val()) 
	+ "&form_email=" + encodeURIComponent($("#form_email").val())
	+ "&form_forwarder=" + encodeURIComponent($("#form_forwarder").val()) 
	+ "&form_depart=" + encodeURIComponent($("#form_depart").val()) 
	+ "&form_id=" + encodeURIComponent($("#form_id").val());
	//alert(passdata);
	
	var queryString = "../ajax/grouphead_ajax.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
				var ajaxresponse = response.split('^');//alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{
					error.html(successmessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
					grouphead();  // For first time page load default results
				}
				else if(ajaxresponse[0] == '2')
				{
					error.html(errormessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
					grouphead();  // For first time page load default results
				}
				//newentry();
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function deletesubmit()
{
	var error = $('#msg_box');

	var field = $("#form_id");  
	if (!field.val())
	{ error.html(errormessage("Please Select a Grouphead.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}

	var confirmation = confirm("Are you sure you want to delete the GroupHead From Groudhead Master?");
	if(confirmation)
	{
		var passdata = "&submittype=delete&id="+ encodeURIComponent($("#form_id").val());
	}
	else
	return false;

	//alert(passdata);
	var queryString = "../ajax/grouphead_ajax.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			
				error.html(response).fadeIn().delay(5000).fadeOut();
				grouphead();  // For first time page load default results
				newentry();
			
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}


function newentry()
{
	$("#leaduploadform")[0].reset();
	$("#form_id").val('');
	
	$("#msg_box").html('');
	$("#msg_box").hide();
	
	//$("#msg_box").html('');
}

function grouphead()
{
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table";
	var queryString = "../ajax/grouphead_ajax.php",
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{	
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				$("#gridprocessf").html('');
				if(ajaxresponse[0] == '1')
				{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_1').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
				}
				else if(ajaxresponse[0] == '2')
				{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_1').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					
				}
				else
				{
					$("#gridprocessf").html(scripterror1());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocessf").html(scripterror1());
		}
	});		
}
	
function getmorerecords(startlimit,slnocount,showtype)
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table&startlimit=" + encodeURIComponent(startlimit)+"&slnocount="+slnocount+"&showtype="+showtype ;
	//alert(passdata);
	var queryString = "../ajax/grouphead_ajax.php",
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{	
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				$("#gridprocessf").html('');
				if(ajaxresponse[0] == '1')
				{
						$('#resultgridf1').html($('#tabgroupgridf1_1').html());
						$('#tabgroupgridf1_1').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
				}
				else if(ajaxresponse[0] == '2')
				{
						$('#resultgridf1').html($('#tabgroupgridf1_1').html());
						$('#tabgroupgridf1_1').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
				}
				else
				{
					$("#gridprocessf").html(scripterror1());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocessf").html(scripterror1());
		}
	});		
}


function gridtoform(slno)
{
	if(slno!= '')
	{
		//$("#productselectionprocess").html('');
		var form = $("#leaduploadform");
			
		var passdata = "submittype=gridtoform&form_id=" + encodeURIComponent(slno) +
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passdata)
		
		$("#gridprocessf1").html(getprocessingimage());
		
		var queryString = "../ajax/grouphead_ajax.php",
			ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
					$("#gridprocessf1").html('');
					var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
					if(ajaxresponse[0] == '1')
					{
						$("#form_id").val(ajaxresponse[1]);
						$("#form_head").val(ajaxresponse[2]);
						$("#form_email").val(ajaxresponse[3]);
						$("#form_forwarder").val(ajaxresponse[4]);
						$("#form_depart").val(ajaxresponse[5]);
					}
					else
					{
						$("#form-error").html(errormessage("No datas found to be displayed."));
					}				
			}, 
			error: function(a,b)
			{
			$("#form-error").html(scripterror());
			}
		});
	}
}
