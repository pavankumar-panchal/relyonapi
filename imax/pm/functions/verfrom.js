// JavaScript Document verfrom.js

function prdcode()
{
	var passdata = "&submittype=prdcode&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#msg_box");
	var queryString = "../ajax/verfrom_ajax.php";
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
				$("#form-error").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			$("#form-error").html('');
		}
	});
}

function load_version()
{
	var passdata = "&submittype=load&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#form_verfrom");
	var queryString = "../ajax/verfrom_ajax.php";
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

function product_url()
{
	var passdata = "&submittype=prdurl&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var outputselect = $("#form_url");
	var queryString = "../ajax/verfrom_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			$("#form-error").html('');
			outputselect.html(response);
			$("#form_url").val(response);
			$("#form_path").val(response);
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
	//alert($('#form_patch').val());
	//alert($("#form_verfrom").val());
	
	var field = $("#form_product");  
	if (!field.val())
	{ msg_box.html( errormessage("Please Select the Product Name.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	var field = $("#form_productcode");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Product Code.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	var field = $("#form_patch");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Patch Version.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	if(parseFloat($("#form_patch").val()) <= parseFloat($("#form_verfrom").val()))
	{msg_box.html(errormessage("Please Enter Valid Patch Version, It Must Be Greater Than Version From! . .")).fadeIn().delay(5000).fadeOut(); 
	$("#form_patch").focus(); return false; }
	
	var field = $("#form_filesize");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the File Size.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	var field = $("#DPC_date");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Release Date.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	var field = $("#form_verfrom");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a Version From.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	var field = $("#form_url");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter a Patch URL.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	
	if(($("#up_prd").is(':checked')) == true) 
	{
		var field = $('#form_size'); if(!field.val()) {msg_box.html(errormessage("Please Enter Main Setup EXE File and File size.")).fadeIn().delay(5000).fadeOut(); field.focus(); return false;} 
	}else { $('#form_size').val('');}
		
	var passdata = "&submittype=save&form_product=" + encodeURIComponent($("#form_product").val()) + 
					"&form_slno=" + encodeURIComponent($("#form_slno").val()) + 
					"&form_productcode=" + encodeURIComponent($("#form_productcode").val()) +
					"&form_patch=" + encodeURIComponent($("#form_patch").val()) + 
					"&form_filesize=" + encodeURIComponent($("#form_filesize").val()) +
					"&DPC_date=" + encodeURIComponent($("#DPC_date").val())  + 
					"&form_verfrom=" + encodeURIComponent($("#form_verfrom").val()) +
					"&form_url=" + encodeURIComponent($("#form_url").val() ) +
					"&show_web=" + encodeURIComponent($("#show_web").is(':checked')) +
					"&form_path=" + encodeURIComponent($("#form_path").val()) +
					"&form_size=" + encodeURIComponent($('#form_size').val()) +
					"&up_prd=" + encodeURIComponent($("#up_prd").is(':checked'))+
					"&dummy=" + Math.floor(Math.random()*10230000000); 
	
	//alert(passdata);
	
	var queryString = "../ajax/verfrom_ajax.php";
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
					reset_entry()
					version();
					$("#form-error").html('');
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
					version();
					reset_entry()
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


function delsubver()
{
	var confirmation = confirm("Are you sure you want to delete the selected Version Update?");
	if(confirmation)
	{
		var passdata = "&submittype=delete&slno="+ encodeURIComponent($("#form_slno").val());
	}
	else
	return false;

	//alert(passdata);
	var outputselect = $('#msg_box');
	var queryString = "../ajax/verfrom_ajax.php";
	$("#gridprocessf1").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			
				outputselect.html(response).fadeIn().delay(5000).fadeOut();
				version();
				reset_entry();
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

function path()
{
	var passdata = "&submittype=path&form_path=" + encodeURIComponent($("#form_path").val());
	//alert(passdata);
	var outputselect = $("#msg_box");
	var queryString = "../ajax/verfrom_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
		
				var msg_result = (response);
				var myarray = msg_result.split("#"); 
			
				outputselect.html(myarray[0]).fadeIn().delay(5000).fadeOut();
				document.getElementById("form_size").value = myarray[1];
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

function showMainPrd(main,see,element) 
{
	var visi = (see.checked) ? "block" : "none";
	document.getElementById(main).style.display = visi;
	if(see.checked == false)
	{
		$('#'+element).val('');
	}
}

function reset_entry()
{
	var dt= new Date();
	document.getElementById('form_productcode').value='';
	document.getElementById('form_url').value='';
	document.getElementById('form_filesize').value='';
	document.getElementById('form_verfrom').value='';
	document.getElementById('DPC_date').Date= dt.getYear();-dt.getMonth();-dt.getDate();
	document.getElementById('form_patch').value='';
	document.getElementById('form_slno').value='';
	document.getElementById('show_web').checked=null;
}

function newentry()
{
	$("#leaduploadform")[0].reset();
	//$("#msg_box").html('');
    $("#form_url").val();
}

function version()
{
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table&form_product=" + encodeURIComponent($("#form_product").val());
	//alert(passdata);
	var queryString = "../ajax/verfrom_ajax.php",
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
	"&startlimit=" + encodeURIComponent(startlimit)+"&slnocount="+slnocount+"&showtype="+showtype ;
	//alert(passdata);
	var queryString = "../ajax/verfrom_ajax.php",
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
		
		var queryString = "../ajax/verfrom_ajax.php",
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
						$("#form_verfrom").val(ajaxresponse[9]);
						if(ajaxresponse[8] == 1)
						{
							$("#show_web").attr('checked',true);
						}else{$("#show_web").attr('checked',false); }
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
