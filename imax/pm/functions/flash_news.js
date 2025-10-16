// JavaScript Document flash_news.js
function flashnews(command)
{
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table&form_product=" + encodeURIComponent($("#form_product").val()) + 
	"&command=" + encodeURIComponent(command);
	var queryString = "../ajax/flash_news_ajax.php";
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
					if(command == 'active')
					{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_1').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
					else if(command == 'disabled')
					{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_2').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
					
				}
				else if(ajaxresponse[0] == '2')
				{
					if(command == 'active')
					{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_1').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
					else if(command == 'disabled')
					{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_2').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
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
	
function getmorerecords(startlimit,slnocount,showtype,command)
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table&form_product=" + encodeURIComponent($("#form_product").val()) + 
	"&startlimit=" + encodeURIComponent(startlimit)+"&slnocount="+slnocount+"&showtype="+showtype +
	"&command=" + encodeURIComponent(command);
	//alert(passdata);
	var queryString = "../ajax/flash_news_ajax.php";
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
					if(command == 'active')
					{
						$('#resultgridf1').html($('#tabgroupgridf1_1').html());
						$('#tabgroupgridf1_1').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
					else if(command == 'disabled')
					{
						$('#resultgridf1').html($('#tabgroupgridf1_2').html());
						$('#tabgroupgridf1_2').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
				}
				else if(ajaxresponse[0] == '2')
				{
					if(command == 'active')
					{
						$('#resultgridf1').html($('#tabgroupgridf1_1').html());
						$('#tabgroupgridf1_1').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
					else if(command == 'disabled')
					{
						$('#resultgridf1').html($('#tabgroupgridf1_2').html());
						$('#tabgroupgridf1_2').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					}
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

function loading_show()
{
	$('#loading').html("<img src='../images/loading.gif'>").fadeIn('fast');
}

function loading_hide()
{
	$('#loading').fadeOut('fast');
}        

function formsubmit()
{
	var form = $("#leaduploadform");
	var msg_box = $("#msg_box");

	var field = $("#form_product");  
	if (!field.val())
	{ msg_box.html("Please Select the Product Name.").fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_title");  
	if (!field.val())
	{ msg_box.html("Please Enter the Title Of Product.").fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#form_link");  
	if (!field.val())
	{ msg_box.html("Please Enter URL Path.").fadeIn().delay(5000).fadeOut(); field.focus();return false;}

	var field = $("#form_desc");  
	if (!field.val())
	{ msg_box.html("Please Enter the Description Of Product.").fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	var field = $("#DPC_date1");  
	if (!field.val())
	{ msg_box.html("Please Select the Create Date.").fadeIn().delay(5000).fadeOut(); field.focus(); return false;}
	
	var passdata = "&submittype=save&form_product=" + encodeURIComponent($("#form_product").val()) + "&form_flashid=" + encodeURIComponent($("#form_flashid").val()) + "&form_title=" + encodeURIComponent($("#form_title").val())+ "&form_link=" + encodeURIComponent($("#form_link").val()) + "&form_desc=" + encodeURIComponent($("#form_desc").val()) + "&DPC_date1=" + encodeURIComponent($("#DPC_date1").val()) + "&DPC_date=" + encodeURIComponent($("#DPC_date").val()) + "&form_disable=" + encodeURIComponent($("#form_disable").is(':checked')) +"&dummy=" + Math.floor(Math.random()*10230000000); 
	
	//alert(passdata);
	
	var queryString = "../ajax/flash_news_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(ajaxresponse[1]).fadeIn().delay(5000).fadeOut();
					//griddata();
					gridtab2('1','tabgroup','active');
					reset_entry();
					$("#form-error").html('');
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(ajaxresponse[1]).fadeIn().delay(5000).fadeOut();;
					//griddata();
					reset_entry();
					gridtab2('1','tabgroup','active');
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

function delete_record(my_prdid,form_product,form_title,form_desc,DPC_date,form_disable)
{
    var del= confirm("Do you want to Delete Flash?");
    if (del== true){
        delsubmit(my_prdid,form_product,form_title,form_desc,DPC_date,form_disable);
    }
}
function delsubmit()
{
	var confirmation = confirm("Are you sure you want to delete the selected Flash News?");
	if(confirmation)
	{
		var passdata = "&submittype=delete&flashid="+ encodeURIComponent($("#form_flashid").val());	
	}
	else
	return false;
	
	//alert(passdata);
	var outputselect = $('#msg_box');
	var queryString = "../ajax/flash_news_ajax.php";
	$("#gridprocessf1").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			
				outputselect.html(response).fadeIn().delay(5000).fadeOut();
				gridtab2('1','tabgroup','active');
				newentry();
				reset_entry();
			$("#gridprocessf1").html('');
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
			$("#form-error").html('');
		}
	});
}

function flash_update(flashid,prdname,form_title,url,dat,description,valid,disable)
{
	//alert (flashid + prdname + form_title + description + valid + disable);
	//alert(description);
	document.getElementById('form_flashid').value=flashid;
	document.getElementById('form_product').value=prdname;
	document.getElementById('DPC_date1').value=dat;
	document.getElementById('form_link').value=url;
	document.getElementById('form_title').value=form_title;
	document.getElementById('form_desc').value=description;
	document.getElementById('DPC_date').value=valid;
	if(disable=='yes')
	{
		document.getElementById('form_disable').checked="checked";
	}
	else
	{
		document.getElementById('form_disable').checked=null;
	}
	
}


function newentry()
{
	$("#leaduploadform")[0].reset();
//$("#msg_box").html('');
}
function reset_entry()
{
	var dt= new Date();
	document.getElementById('form_flashid').value="";
	document.getElementById('DPC_date1').Date= dt.getYear();-dt.getMonth();-dt.getDate();
	document.getElementById('form_link').value="";
	document.getElementById('form_title').value="";
	document.getElementById('form_desc').value="";
	document.getElementById('DPC_date').Date= dt.getYear(0000);-dt.getMonth(00);-dt.getDate(00);
	document.getElementById('form_disable').checked=null;
	
}

function gridtoform(slno)
{
	if(slno!= '')
	{
		//$("#productselectionprocess").html('');
		var form = $("#leaduploadform");
			
		var passdata = "submittype=gridtoform&form_flashid=" + encodeURIComponent(slno) +
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passdata)
		
		$("#gridprocessf1").html(getprocessingimage());
		
		var queryString = "../ajax/flash_news_ajax.php";
			ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
					$("#gridprocessf1").html('');
					var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
					if(ajaxresponse[0] == '1')
					{
						$("#form_flashid").val(ajaxresponse[1]);
						$("#form_product").val(ajaxresponse[2]);
						$("#DPC_date1").val(ajaxresponse[3]);
						$("#form_desc").val(ajaxresponse[4]);
						$("#form_title").val(ajaxresponse[5]);
						$("#form_link").val(ajaxresponse[6]);
						$("#DPC_date").val(ajaxresponse[7]);
						if(ajaxresponse[8] == 'yes')
						{
							$('input:checkbox[name=form_disable]').attr('checked',true);
						}else{ $('input:checkbox[name=form_disable]').attr('checked',false); }
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
