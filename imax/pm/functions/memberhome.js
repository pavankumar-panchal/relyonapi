function loadData(command)
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
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_1').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'disabled')
					{
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf1_2').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'deleted')
					{
						$('#tabgroupgridc5').show();
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
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf2_1').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'disabled')
					{
						$('#tabgroupgridc5').show();
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

function jobrequired()
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=jobcarrer";
	//alert(passdata);
	var queryString = "../ajax/dashboard.php";
	ajaxobjext58 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				$('#tabgroupgridc5').show();
				$('#tabgroupgridf3_1').html(ajaxresponse[0]);
				$("#gridprocessf").html('');
				
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
						$('#tabgroupgridc5').show();
						$('#tabgroupgridf3_3').html(ajaxresponse[0]);
						$("#gridprocessf").html('');
					}
					else if(command == 'hotfix')
					{
						$('#tabgroupgridc5').show();
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