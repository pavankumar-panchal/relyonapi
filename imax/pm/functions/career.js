// JavaScript Document verfrom.js

function department()
{
	var dept = $("#form_department").val();
	if(dept == 'others')
	{
		$("#form_department1").css('display','block');
	}
	else
	{	
		$("#form_department1").val('');
		$("#form_department1").css('display','none');
	}
}


function formsubmit()
{
	var form = $("#leaduploadform");
	var msg_box = $("#msg_box");
	var error = $("#form-error");
	
	var field = $('#form_department');
	if(!field.val()) { error.html(errormessage("Select the Department ")); field.focus(); return false; }
	
	var field = $('#form_commitment');
	if(!field.val()) { error.html(errormessage("Select the Commitment ")); field.focus(); return false; }
	
	var passdata = "&submittype=save&form_department=" + encodeURIComponent($("#form_department").val()) + 
	"&form_slno=" + encodeURIComponent($("#form_slno").val()) + 
	"&form_department1=" + encodeURIComponent($("#form_department1").val()) + 
	"&form_experience=" + encodeURIComponent($("#form_experience").val()) + 
	"&form_qualification=" + encodeURIComponent($("#form_qualification").val())  + 
	"&form_location=" + encodeURIComponent($("#form_location").val()) + 
	"&form_age=" + encodeURIComponent($("#form_age").val()) +
	"&form_venue=" + encodeURIComponent($("#form_venue").val()) + 
	"&form_commitment=" + encodeURIComponent($("#form_commitment").val() ) + 
	"&form_profile=" + encodeURIComponent($("#form_profile").val()) + 
	"&form_attributes=" + encodeURIComponent($("#form_attributes").val() ) + 
	"&form_sl=" + encodeURIComponent($("#form_sl").val()) + 
	"&form_vehicle=" + encodeURIComponent($("#form_vehicle").is(':checked')) + 
	"&form_vacancies=" + encodeURIComponent($("#form_vacancies").val() ) + 
	"&form_jobcode=" + encodeURIComponent($("#form_jobcode").val() ) + 
	"&show_web=" + encodeURIComponent($("#show_web").is(':checked')) + 
	"&dummy=" + Math.floor(Math.random()*10230000000); 
	
	//alert(passdata);
	
	var queryString = "../ajax/career_ajax.php";
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
					//newentry();
					reset_entry();
					career();
					
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1])).fadeIn().delay(5000).fadeOut();
					//griddata();
					career();
					reset_entry();
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

function version_update(form_slno,location,qualification,profile,languages,vacancies,experience,commitment,department,attributes,vehicle,jobcode,age,venue,showinweb)
{
	//alert(form_slno+prdname+prdcode+prdpatch+size+date+ver+url+check);

	document.getElementById('form_slno').value = form_slno;
	document.getElementById('form_location').value = location;
	document.getElementById('form_qualification').value = qualification;
	document.getElementById('form_profile').value = profile;
	document.getElementById('form_sl').value = languages;
	document.getElementById('form_vacancies').value = vacancies;
	document.getElementById('form_experience').value = experience;
	document.getElementById('form_commitment').value = commitment;
	document.getElementById('form_department').value = department;
	document.getElementById('form_attributes').value = attributes;
	document.getElementById('form_jobcode').value = jobcode;
	document.getElementById('form_age').value = age;
	document.getElementById('form_venue').value = venue;
	
	fill_iframe_with_value('#venue iframe',venue);
	fill_iframe_with_value('#location iframe',location);
	fill_iframe_with_value('#qualification iframe',qualification);
	fill_iframe_with_value('#profile iframe',profile);
	fill_iframe_with_value('#sl iframe',languages);
	fill_iframe_with_value('#vacancies iframe',vacancies);
	fill_iframe_with_value('#attributes iframe',attributes);
	
	
	
	
	if(vehicle=='1')
	{
		document.getElementById('form_vehicle').checked="checked";
	}
	else
	{
		document.getElementById('form_vehicle').checked=null;
	}	
	
	if(showinweb=='1')
	{
		document.getElementById('show_web').checked="checked";
	}
	else
	{
		document.getElementById('show_web').checked=null;
	}
}

function fill_iframe_with_value(iframe, value)
{
	var frame1 = $(iframe);
	f1 = frame1[0].contentWindow.document;
	var b1=$('body', f1);
	b1.html(value);	
}

function delsubver()
{	var confirmation = confirm("Are you sure you want to delete?");
	if(confirmation)
	{
		var passdata = "&submittype=delete&slno="+ encodeURIComponent($("#form_slno").val()) +
						"&form_department1="+ encodeURIComponent($("#form_department1").val());
	}
	else
	return false;

	//alert(passdata);
	var outputselect = $('#msg_box');
	var queryString = "../ajax/career_ajax.php";
	$("#gridprocessf1").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			
				outputselect.html(response).fadeIn().delay(5000).fadeOut();
				carrer()
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

function delete_record(my_prdid,form_department)
{
    var del= confirm("Do you want to Delete?");
    if (del== true)
	{
        delsubver(my_prdid,form_department);
    }
}


function showMainPrd(main, see) 
{
	var visi = (see.checked) ? "block" : "none";
	document.getElementById(main).style.display = visi;
}

function reset_entry()
{
	$('#form-error').html('');
	document.getElementById('form_slno').value = "";
	document.getElementById('form_experience').value ="";
	document.getElementById('form_qualification').value = "";
	document.getElementById('form_location').value = "";
	document.getElementById('form_commitment').value = "";
	document.getElementById('form_profile').value ="";
	document.getElementById('form_attributes').value = "";
	document.getElementById('form_sl').value = "";
	document.getElementById('form_vehicle').value = "";
	document.getElementById('form_vacancies').value = "";
	document.getElementById('form_age').value = "";
	document.getElementById('form_venue').value = "";
	fill_iframe_with_value('#location iframe','');
	fill_iframe_with_value('#qualification iframe','');
	fill_iframe_with_value('#profile iframe','');
	fill_iframe_with_value('#sl iframe','');
	fill_iframe_with_value('#vacancies iframe','');
	fill_iframe_with_value('#attributes iframe','');
	fill_iframe_with_value('#venue iframe','');
}

function newentry()
{
	$("#leaduploadform")[0].reset();
	reset_entry();
//$("#msg_box").html('');
}

function career()
{
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table&form_department=" + encodeURIComponent($("#form_department").val()) +
					"&form_department1=" + encodeURIComponent($("#form_department1").val());
	//alert(passdata);
	var queryString = "../ajax/career_ajax.php",
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
	
function getmorerecords(startlimit,slnocount,showtype,command)
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=table&form_product=" + encodeURIComponent($("#form_product").val()) + 
	"&startlimit=" + encodeURIComponent(startlimit)+"&slnocount="+slnocount+"&showtype="+showtype ;
	//alert(passdata);
	var queryString = "../ajax/career_ajax.php",
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
	
		//$("#productselectionprocess").html('');
		var form = $("#leaduploadform");
			
		var passdata = "submittype=gridtoform&form_slno=" + encodeURIComponent(slno) +
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passdata);
		
		$("#form-error").html(getprocessingimage());
		
		var queryString = "../ajax/career_ajax.php",
			ajaxobjext38 = $.ajax(
		{
			type: "POST",
			url: queryString, 
			data: passdata, 
			cache: false,
			success: function(response,status)
			{
				$("#form-error").html('');
				var ajaxresponse = response.split('^');
				if(ajaxresponse[0] == '1')
				{
					$("#form_department1").val(ajaxresponse[1]);
					$("#form_jobcode").val(ajaxresponse[2]);
					$("#form_experience").val(ajaxresponse[3]);
					$("#form_commitment").val(ajaxresponse[4]);
					$("#form_qualification").val(ajaxresponse[5]);
					$("#form_location").val(ajaxresponse[6]);
					$("#form_profile").val(ajaxresponse[7]);
					$("#form_attributes").val(ajaxresponse[8]);
					$("#form_venue").val(ajaxresponse[9]);
					$("#form_vacancies").val(ajaxresponse[10]);
					$("#form_sl").val(ajaxresponse[11]);
					$("#form_date").val(ajaxresponse[14]);
					$("#form_slno").val(ajaxresponse[15]);
					
					fill_iframe_with_value('#qualification iframe',ajaxresponse[5]);
					fill_iframe_with_value('#location iframe',ajaxresponse[6]);
					fill_iframe_with_value('#profile iframe',ajaxresponse[7]);
					fill_iframe_with_value('#attributes iframe',ajaxresponse[8]);
					fill_iframe_with_value('#venue iframe',ajaxresponse[9]);
					fill_iframe_with_value('#vacancies iframe',ajaxresponse[10]);
					fill_iframe_with_value('#sl iframe',ajaxresponse[11]);

					if(ajaxresponse[12] == 1)
					{
						$("#form_vehicle").attr('checked',true);
					}
					else
					{
						$("#form_vehicle").attr('checked',false); 
					}
					if(ajaxresponse[13] == 1)
					{
						$("#show_web").attr('checked',true);
					}
					else
					{
						$("#show_web").attr('checked',false); 
					}
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
