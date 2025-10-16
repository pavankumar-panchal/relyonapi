//Total Count of Email ACCOUNTS status
function updatestatusstrip()
{
	$('#leadstotal').html(processing());
	var queryString = "../ajax/email_search_ajax.php";
	var passdata = "&submittype=statusstrip&dummy=" + Math.floor(Math.random()*10230000000); //alert(passdata)
	ajaxobjext74 = $.ajax(
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
				var ajaxresponse = response.split('^');// alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{
					$('#emailtotal').html(ajaxresponse[1]);
					$('#activemail').html(ajaxresponse[2]);
					$('#disabledmail').html(ajaxresponse[3]);
					$('#deletedmail').html(ajaxresponse[4]);
					$('#groupheadcount').html(ajaxresponse[5]);
				}
				else
				{
					$('#emailtotal').html('');
					$('#form-error').html(scripterror());
				}	
			}
		}, 
		error: function(a,b)
		{
			$('#emailtotal').html('');
			$('#form-error').html(scripterror());
		}
	});		
}

//newtog for Show and hide filter option
function newtog()
{
	$('#divform').toggle();
}
//filter to function
function leadgridtab5(activetab,groupname,activetype)
{
	var totaltabs = 5;
	var activetabclass = "grid-active-leadtabclass";
	var tabheadclass = "grid-leadtabclass";
	for(var i=1 ; i <= totaltabs ; i++)
	{
		var tabhead = groupname + 'h' + i; 
		var tabcontent = groupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).attr('class',activetabclass);
			$('#'+tabcontent).show();
			if(activetype == 'default')
			{
				griddata('');
			}
			else if(activetype == 'todayfollowup')
			{
				followupforday('');
			}
			else if(activetype == 'nofollowup')
			{
				nofollowup('');
			}	
			else if(activetype == 'notviewed')
			{
				notviewed('');
			}
			
		}
		else 
		{
			$('#'+tabhead).attr('class',tabheadclass) ;
			$('#'+tabcontent).hide();
		}
	}	
}

//filtering data into search result tab
function filtering(command)
{
	var form = $("#filterform");
	
	var msg_box = $("#msg_box");
	
	var textfield = $("#searchcriteria").val();
	
	var subselection = $("input[name='databasefield']:checked").val();
	
	
	var selectedvalue = $('#groupheadselect'); 
	
	$('#hiddengroupheadselect').val($('#groupheadselect').val());
	
		
	if($("#dropterminatedstatus:checked").val() == 'true')
	{
		var dropterminatedstatus = 'true';
	}
	else
	{
		var dropterminatedstatus = 'false';
	}
	
	if($("#dropactivestatus:checked").val() == 'true')
	{
		var dropactivestatus = 'true';
	}
	else
	{
		var dropactivestatus = 'false';
	}
	
	if($("#dropdisablestatus:checked").val() == 'true')
	{
		var dropdisablestatus = 'true';
	}
	else
	{
		var dropdisablestatus = 'false';
	}
	
	if(command == 'excel')
	{
		 $("#filterform").submit();
	}
	else if(command == 'resetform')
	{
		form[0].reset();
		//filterfollowupdates();
	}
	else
	{
		$('#gridprocessf').html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'initial\')" class="abort">(STOP)</span>');
		/*$('#hiddenfromdate').val($('#DPC_fromdate').val());
		$('#hiddentodate').val($('#DPC_todate').val());*/
		$('#hiddengroupheadselect').val($('#groupheadselect').val());
		$('#hiddenforwarderselect').val($('#forwarderselect').val());
		$('#srchhiddenfield').val(textfield);      //alert(textfield);
		$('#subselhiddenfield').val(subselection); //alert(subselection);
		$('#hiddensource').val($("#form_source").val()); //alert(form.form_source.value);
		
		leadgridtab5('5','tabgroupleadgrid','searchresult');
		
		var passdata = "submittype=filter&forwarderselect=" + encodeURIComponent($("#forwarderselect").val()) + 
		"&groupheadselect=" + encodeURIComponent($("#groupheadselect").val()) + 
		"&dropactivestatus=" + encodeURIComponent(dropactivestatus) +
		"&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus)+
		"&dropdisablestatus=" + encodeURIComponent(dropdisablestatus)+
		"&searchtext="+ encodeURIComponent($("#srchhiddenfield").val())+
		"&subselection="+encodeURIComponent($("#subselhiddenfield").val())+
		"&leadsource="+encodeURIComponent($("#hiddensource").val())+
		"&dummy=" + Math.floor(Math.random()*10230000000) ; 
		//alert(passdata);
		//&fromdate=" + encodeURIComponent($("#DPC_fromdate").val()) + "&todate=" + encodeURIComponent($("#DPC_todate").val()) + 
		var queryString = "../ajax/email_search_ajax.php";
		ajaxobjext56 = $.ajax(
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
					var response2 = response.split("|^|"); //alert(response)
					if(response2[0] == '1')
					{
						$("#tabgroupgridf1_1").html(response2[1]);
						$("#getmorelinkf1").html(response2[2]);
						$("#gridprocessf").html(' <font color="#FFFFFF">=> Filter Applied (' + response2[3] +' Records)</font>');
						msg_box.html('');
					}
					else if(response2[0] == '2')
					{
						msg_box.html(errormessage(response2[1]));
						$("#gridprocessf").html('');
					}
					else if(response2[0] == '3')
					{
						$("#tabgroupgridf1_1").html(errormessage(response2[1]));
						$("#gridprocessrf").html('');
					}
					else
					{
						$("#gridprocessrf").html(scripterror1()); 
					}
				}
			}, 
			error: function(a,b)
			{
				$("#gridprocessrf").html(scripterror1()); 
			}
		});	
	}
}

//abort the loading into searchtab
function abortajaxprocess(type)
{
	if(type == 'initial')
	{
		ajaxobjext56.abort();
		$("#gridprocessf").html('');
	}
	else if(type == 'showmore')
	{
		ajaxobjext58.abort();
		$("#gridprocessf").html('');
	}
}


function getmorerecords(startlimit,slnocount,showtype,type)
{
	
	var form = $('#filterform') ;	
	if($("#dropterminatedstatus:checked").val() == 'true')
	{
		var dropterminatedstatus = 'true';
	}
	else
	{
		var dropterminatedstatus = 'false';
	}
	
	if($("#dropactivestatus:checked").val() == 'true')
	{
		var dropactivestatus = 'true';
	}
	else
	{
		var dropactivestatus = 'false';
	}
	
	if($("#dropdisablestatus:checked").val() == 'true')
	{
		var dropdisablestatus = 'true';
	}
	else
	{
		var dropdisablestatus = 'false';
	}
	if(type == 'filter')
	{
		$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&startlimit="+startlimit +
		"&slnocount="+slnocount+"&showtype="+showtype+
		"&forwarderselect=" + encodeURIComponent($("#forwarderselect").val()) +
		"&groupheadselect=" + encodeURIComponent($("#groupheadselect").val()) + 
		"&dropactivestatus=" + encodeURIComponent(dropactivestatus) +
		"&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus)+
		"&dropdisablestatus=" + encodeURIComponent(dropdisablestatus)+
		"&searchtext="+ encodeURIComponent($("#srchhiddenfield").val())+
		"&subselection="+encodeURIComponent($("#subselhiddenfield").val())+
		"&leadsource="+encodeURIComponent($("#hiddensource").val())+
		"&dummy=" + Math.floor(Math.random()*10230000000) ; 
		
		var queryString = "../ajax/email_search_ajax.php";//alert(passdata);
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
						$('#getmorelinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
						
					}
					else if(ajaxresponse[0] == '2')
					{
						$('#resultgridf1').html( $('#tabgroupgridf1_1').html());
						$('#tabgroupgridf1_1').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
						$('#getmorelinkf1').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF"> => Filter Applied (' + ajaxresponse[3] +' Records)</font>');
						
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
}