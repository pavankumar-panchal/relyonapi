function saralmail(command)
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=saralmail&command=" + encodeURIComponent(command);
	//alert(passdata);
	var queryString = "../ajax/dashboard.php";
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				$("#gridprocessf").html('');
				
					if(command == 'active')
					{
						$('#tabgroupdetail2').show();
						$("#saralmaildisplay").html('Official Email ID Active');
						$('#tabgroupgridf1_1').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'disabled')
					{
						$('#tabgroupdetail2').show();
						$("#saralmaildisplay").html('Official Email ID Disabled');
						$('#tabgroupgridf1_2').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'deleted')
					{
						$('#tabgroupdetail2').show();
						$("#saralmaildisplay").html('Official Email ID Deleted');
						$('#tabgroupgridf1_3').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
		}, 
		error: function(a,b)
		{
			$("#gridprocessf").html(scripterror1());
		}
	});		
}



function flashnews(command)
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=flashnews&command=" + encodeURIComponent(command);
	//alert(passdata);
	var queryString = "../ajax/dashboard.php";
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				$("#gridprocessf").html('');
				
					if(command == 'active')
					{
						$('#tabgroupdetail3').show();
						$("#flashnewsdisplay").html('Enabled Flash News');
						$('#tabgroupgridf2_1').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'disabled')
					{
						$('#tabgroupdetail3').show();
						$("#flashnewsdisplay").html('Disabled Flash News');
						$('#tabgroupgridf2_2').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
		}, 
		error: function(a,b)
		{
			$("#gridprocessf").html(scripterror1());
		}
	});		
}

function jobrequired(command)
{

	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=jobcareer&command=" + encodeURIComponent(command);
	//alert(passdata);
	var queryString = "../ajax/dashboard.php";
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				$("#gridprocessf").html('');
				
					if(command == 'activecareer')
					{
						$('#tabgroupdetail4').show();
						$("#careerdisplay").html('Enabled Job Opening');
						$('#tabgroupgridf5_1').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'disablecareer')
					{
						$('#tabgroupdetail4').show();
						$("#careerdisplay").html('Disabled Job Opening');
						$('#tabgroupgridf6_1').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
		}, 
		error: function(a,b)
		{
			$("#gridprocessf").html(scripterror1());
		}
	});			
}

function activity()
{
	
	//$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=latestactivity";
	//alert(passdata);
	var queryString = "../ajax/dashboard.php";
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('|^|');
				$('#activity_form').html(ajaxresponse[0]);
				//$("#gridprocessf").html('');
		}, 
		error: function(a,b)
		{
			//$("#gridprocessf").html(scripterror1());
		}
	});		
}

function verhotfix(command)
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=verhotfixform&command=" + encodeURIComponent(command);
	//alert(passdata);
	var queryString = "../ajax/dashboard.php";
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				$("#gridprocessf").html('');
				
					if(command == 'version')
					{
						$('#tabgroupdetail1').show();
						$("#updatetype").html('Version Update display in web');
						$('#tabgroupgridf3_3').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'hotfix')
					{
						$('#tabgroupdetail1').show();
						$("#updatetype").html('Hotfix Update display in web');
						$('#tabgroupgridf4_4').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					
		}, 
		error: function(a,b)
		{
			$("#gridprocessf").html(scripterror1());
		}
	});		
}