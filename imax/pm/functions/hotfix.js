// JavaScript Document Hotfix.php

function prdcode()
{
	var passdata = "&submittype=prdcode&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#msg_box");
	var queryString = "../ajax/hot_fix_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
				var msg_result = (response);
				var myarray = msg_result.split("#"); 
			
				/*outputselect.html(myarray[0]).fadeIn().delay(5000).fadeOut();*/
				document.getElementById("form_productcode").value = myarray[1];
				document.getElementById("form_patch").value = myarray[2];
				fixno();
				$("#form-error").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			$("#form-error").html('');
		}
	});
}

function fixno()
{
	var passdata = "&submittype=fixno&form_product=" + encodeURIComponent($("#form_product").val()) + "&form_patch=" + encodeURIComponent($("#form_patch").val());
	//alert(passdata);
	var outputselect = $("#msg_box");
	var queryString = "../ajax/hot_fix_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
				var msg_result = (response);
				var myarray = msg_result.split("#"); 
			
				/*outputselect.html(myarray[0]);*/
				document.getElementById("form_hotfix").value = myarray[1];
				$("#form-error").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			$("#form-error").html('');
		}
	});
}

function load_hotfix()
{
	var passdata = "&submittype=load&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#form_patch");
	var queryString = "../ajax/hot_fix_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
		
			outputselect.html(response);
			$("#form-error").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			$("#form-error").html('');
		}
	});
}

function prd_url()
{
	var passdata = "&submittype=prdurl&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#form_url");
	var queryString = "../ajax/hot_fix_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
		
			outputselect.html(response);
			document.getElementById("form_url").value = response;
			$("#form-error").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			$("#form-error").html('');
		}
	});
}


function formsubmit()
{
	var form = $("#leaduploadform");
	var msg_box = $("#msg_box");

	var field = $("#form_product");  
	if (!field.val())
	{ msg_box.html( errormessage("Please Select the Product Name.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_productcode");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Product Code.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_patch");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Patch Version.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_filesize");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the File Size.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#DPC_date");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Release Date.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_url");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter a Patch URL.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_hotfix");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter a HotFix NO.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	
	var passdata = "&submittype=save&form_product=" + encodeURIComponent($("#form_product").val()) + "&form_slno=" + encodeURIComponent($("#form_slno").val()) + "&form_productcode=" + encodeURIComponent($("#form_productcode").val()) + "&form_patch=" + encodeURIComponent($("#form_patch").val()) + "&form_filesize=" + encodeURIComponent($("#form_filesize").val()) + "&DPC_date=" + encodeURIComponent($("#DPC_date").val()) + "&form_url=" + encodeURIComponent($("#form_url").val()) + "&form_hotfix=" + encodeURIComponent($("#form_hotfix").val()) + "&check_web=" + encodeURIComponent($("#check_web").is(':checked')) +"&dummy=" + Math.floor(Math.random()*10230000000); 
	
	//alert(passdata);
	
	var queryString = "../ajax/hot_fix_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
					//griddata();
					hotfix();
					reset_entry();
					//newentry();
					$("#form-error").html('');
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
					//griddata();
					//newentry();
					reset_entry()
					hotfix();
					$("#form-error").html('');
				}
				else
				{
					msg_box.html(scripterror());
					$("#form-error").html('');
				}
			
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror());
			$("#form-error").html('');
		}
	});		
}

function delsubmit()
{
	var confirmation = confirm("Are you sure you want to delete the selected HotFix Update?");
	if(confirmation)
	{
		var passdata = "&submittype=delete&slno="+ encodeURIComponent($("#form_slno").val());
	}
	else
	return false;

	//alert(passdata);
	var outputselect = $('#msg_box');
	var queryString = "../ajax/hot_fix_ajax.php";
	$("#gridprocessf1").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			
				outputselect.html(response).fadeIn().delay(5000).fadeOut();
				hotfix();
				reset_entry();
				//newentry();
			$("#gridprocessf1").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			reset_entry();
			$("#form-error").html('');
		}
	});
}


function urlcheck()
{
	var passdata = "&submit=url&form_url=" + encodeURIComponent($("#form_url").val());
	//alert(passdata);
	var outputselect = $("#msg_box");
	var queryString = "../inc/checkurl.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
				loading_show();
				var msg_result = (response);
				var myarray = msg_result.split("#"); 
			
				outputselect.html(myarray[0]).fadeIn().delay(5000).fadeOut();
				document.getElementById("form_filesize").value = myarray[1];
			$("#form-error").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			$("#form-error").html('');
		}
	});
}

function loading_show()
{
	$('#loading').html("<img src='../images/loading.gif'>").fadeIn('fast');
}
	
function loading_hide()
{
	$('#loading').fadeOut('fast');
}                
	
function reset_entry()
{
	var dt= new Date();
	document.getElementById('form_productcode').value='';
	document.getElementById('form_url').value='';
	document.getElementById('form_filesize').value='';
	document.getElementById('DPC_date').Date= dt.getYear();-dt.getMonth();-dt.getDate();
	document.getElementById('form_hotfix').value='';
	document.getElementById('form_patch').value='';
	document.getElementById('form_slno').value='';
	document.getElementById('check_web').checked=null;
}

function newentry()
{
	$("#leaduploadform")[0].reset();
	$("#form_url").val('');
	$("#form_patch").val('');
	
//$("#msg_box").html('');
}

function hotfix()
{
	
	
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table&form_product=" + encodeURIComponent($("#form_product").val());
	var queryString = "../ajax/hot_fix_ajax.php",
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
	var passdata = "&submittype=table&form_product=" + encodeURIComponent($("#form_product").val()) + 
	"&startlimit=" + encodeURIComponent(startlimit)+"&slnocount="+slnocount+"&showtype="+showtype;
	//alert(passdata);
	var queryString = "../ajax/hot_fix_ajax.php",
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
			
		var passdata = "submittype=gridtoform&form_slno=" + encodeURIComponent(slno) +
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passdata)
		
		$("#gridprocessf1").html(getprocessingimage());
		
		var queryString = "../ajax/hot_fix_ajax.php",
			ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
					$("#gridprocessf1").html('');
					var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
					if(ajaxresponse[0] == '1')
					{
						$("#form_slno").val(ajaxresponse[1]);
						$("#form_product").val(ajaxresponse[2]);
						$("#form_productcode").val(ajaxresponse[3]);
						$("#form_patch").val(ajaxresponse[4]);
						$("#form_url").val(ajaxresponse[5]);
						$("#form_filesize").val(ajaxresponse[6]);
						$("#DPC_date").val(ajaxresponse[7]);
						$("#form_hotfix").val(ajaxresponse[9]);
						if(ajaxresponse[8] == 1)
						{
							$("#check_web").attr('checked',true);
						}else{$("#check_web").attr('checked',false); }
						//$("#form_disable").val(response['disable']);
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
