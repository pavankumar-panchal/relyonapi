// JavaScript Document pm.js

function formsubmit()
{
	var form = $("#leaduploadform");
	var outputselect = $('#msg_box');
	
	var field = $("#form_product");  
	if (!field.val())
	{ outputselect.html(errormessage("Please Enter the Product Name.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}

	var field = $("#product_url");  
	if (!field.val())
	{ outputselect.html(errormessage("Please Enter the Product URL.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}

	var passdata = "&submittype=save&form_product=" + encodeURIComponent($("#form_product").val()) + "&product_url=" + encodeURIComponent($("#product_url").val()) + "&form_prdid=" + encodeURIComponent($("#form_prdid").val());
	//alert(passdata);
	var queryString = "../ajax/pm_ajax.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
			if(ajaxresponse[0] == '1')
			{
				outputselect.html(successmessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
				//griddata();
				productmaster();  // For first time page load default results
				newentry();
				//newentry();
			}
			else if(ajaxresponse[0] == '2')
			{
				outputselect.html(errormessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
				//griddata();
				//newentry();
				reset_entry()
				productmaster();
			}
			else
			{
				outputselect.html(scripterror());
			}
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});
}

function deletesubmit()
{
	var confirmation = confirm("Are you sure you want to delete the Product From Product Master?");
	if(confirmation)
	{
		var passdata = "&submittype=delete&prdid="+ encodeURIComponent($("#form_prdid").val());
	}
	else
	return false;

	//alert(passdata);
	var outputselect = $('#msg_box');
	var queryString = "../ajax/pm_ajax.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
			if(ajaxresponse[0] == '1')
			{
				outputselect.html(successmessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
				//griddata();
				productmaster();  // For first time page load default results
				newentry();
			}
			
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});
}

function gridtoform(slno)
{
	
	if(slno!= '')
	{
		//$("#productselectionprocess").html('');
		var form = $("#leaduploadform");
			
		var passdata = "submittype=gridtoform&form_prdid=" + encodeURIComponent(slno) +
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passdata)
		
		$("#gridprocessf1").html(getprocessingimage());
		
		var queryString = "../ajax/pm_ajax.php",
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
					$("#gridprocessf1").html('');
					var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
					if(ajaxresponse[0] == '1')
					{
						$("#form_prdid").val(ajaxresponse[1]);
						$("#form_product").val(ajaxresponse[2]);
						$("#product_url").val(ajaxresponse[3]);
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

function productmaster()
{
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table";
	var queryString = "../ajax/pm_ajax.php",
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
	var queryString = "../ajax/pm_ajax.php",
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



function newentry()
{
	$("#leaduploadform")[0].reset();
	//$("#msg_box").html('');
}

