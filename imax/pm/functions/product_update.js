// JavaScript Document product_update_ajax.php

function load_mainprd()
{
	var passdata = "&submittype=load&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#form_patch");
	var queryString = "../ajax/product_update_ajax.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
		
			outputselect.html(response);
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});
}

function prd_url()
{
	var passdata = "&submittype=prdurl&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#form_url");
	var queryString = "../ajax/product_update_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			$("#form-error").html('');
			$("#form_url").val(response);
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
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
	var field = $("#form_patch");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Patch Version.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_size");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the File Size.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#DPC_date");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Release Date.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_url");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter a Patch URL.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	
	var passdata = "&submittype=save&form_product=" + encodeURIComponent($("#form_product").val()) + "&form_pid=" + encodeURIComponent($("#form_pid").val()) + "&form_patch=" + encodeURIComponent($("#form_patch").val()) + "&form_size=" + encodeURIComponent($("#form_size").val()) + "&DPC_date=" + encodeURIComponent($("#DPC_date").val()) + "&form_url=" + encodeURIComponent($("#form_url").val()) +"&dummy=" + Math.floor(Math.random()*10230000000); /*+ "&show_web=" + encodeURIComponent($("#show_web").is(':checked')) */
	
	//alert(passdata);
	
	var queryString = "../ajax/product_update_ajax.php";
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
					mainproduct();
					reset_entry();
					//newentry();
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
					//griddata();
					//newentry();
					reset_entry();
					mainproduct();
				}
				else
				{
					msg_box.html(scripterror());
				}
			
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror());
		}
	});		
}


function delsubmit()
{
	var confirmation = confirm("Are you sure you want to delete the Main Product record?");
	if(confirmation)
	{
		var passdata = "&submittype=delete&pid="+ encodeURIComponent($("#form_pid").val());
	}
	else
	return false;

	//alert(passdata);
	var outputselect = $('#msg_box');
	var queryString = "../ajax/product_update_ajax.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
				outputselect.html(successmessage(response)).fadeIn().delay(5000).fadeOut();
				mainproduct();
				reset_entry();
				//newentry();
			
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			reset_entry();
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
				$("#form-error").html('');	
				var msg_result = (response);
				var myarray = msg_result.split("#"); 
			
				outputselect.html(successmessage(myarray[0])).fadeIn().delay(5000).fadeOut();
				document.getElementById("form_size").value = myarray[1];
			
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});
}
	
function reset_entry()
{
	var dt= new Date();
	document.getElementById('form_url').value='';
	document.getElementById('form_size').value='';
	document.getElementById('DPC_date').Date= dt.getYear();-dt.getMonth();-dt.getDate();
	document.getElementById('form_patch').value='';
	document.getElementById('form_pid').value='';
	document.getElementById('show_web').checked=null;
}

function newentry()
{
	$("#leaduploadform")[0].reset();
	$('#form_pid').val('');
//$("#msg_box").html('');
}

function mainproduct()
{
	var passdata = "&submittype=table&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var queryString = "../ajax/product_update_ajax.php",
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
				}
				else if(ajaxresponse[0] == '2')
				{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_1').html(ajaxresponse[1]);
					
				}
				
			}
		}, 
		
	});		
}


function gridtoform(slno)
{
	if(slno!= '')
	{
		//$("#productselectionprocess").html('');
		var form = $("#leaduploadform");
			
		var passdata = "submittype=gridtoform&form_pid=" + encodeURIComponent(slno) +
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passdata);
		
		$("#gridprocessf1").html(getprocessingimage());
	var queryString = "../ajax/product_update_ajax.php",
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
					$("#gridprocessf1").html('');
					var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
					if(ajaxresponse[0] == '1')
					{
						//alert(ajaxresponse[3]);
						$("#form_pid").val(ajaxresponse[1]);
						$("#form_product").val(ajaxresponse[2]);
						$("#form_patch").val(ajaxresponse[3]);
						$("#form_size").val(ajaxresponse[4]);
						$("#form_url").val(ajaxresponse[5]);
						$("#DPC_date").val(ajaxresponse[6]);
						
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
