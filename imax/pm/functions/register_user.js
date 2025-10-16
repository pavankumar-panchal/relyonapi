var userarray = new Array();
var totalarray = new Array();

function formsubmit(command)
{
	$('#save').removeClass('button_enter1');
	var passData = "";
	var form = $("#leaduploadform" );
	var error = $("#form-error" );
	var msg_box = $("#msg_box");
	if(command == 'save')
	{
		var field = $("#fname");  
		if (!field.val())
		{ error.html( errormessage("Please Enter First Name.")); field.focus(); return false;}
				
		var field = $("#lname");  
		if (!field.val())
		{ error.html(errormessage("Please Enter Last Name.")); field.focus(); return false;}
		
		var field = $("#login");  
		if (!field.val())
		{ error.html(errormessage("Please Enter Username.")); field.focus(); return false;}
		
		var field = $("#email");  
		if (!field.val())
		{ error.html(errormessage("Please Enter Email Address.")); field.focus(); return false;}
				
		var field = $("#password");  
		if (!field.val())
		{ error.html(errormessage("Please Enter Password.")); field.focus(); return false;}
			
		var passData = "switchtype=save&fname=" + encodeURIComponent($("#fname").val()) 
			+ "&lname=" + encodeURIComponent($("#lname").val()) 
			+ "&login=" + encodeURIComponent($("#login").val())
			+ "&password=" + encodeURIComponent($("#password").val())
			+ "&email=" + encodeURIComponent($("#email").val()) 
			+ "&reg_form=" + encodeURIComponent($("#reg_form").is(':checked')) 
			+ "&prd_master=" + encodeURIComponent($("#prd_master").is(':checked')) 
			+ "&ver_update=" + encodeURIComponent($("#ver_update").is(':checked')) 
			+ "&hot_update=" + encodeURIComponent($("#hot_update").is(':checked')) 
			+ "&flash_news=" + encodeURIComponent($("#flash_news").is(':checked')) 
			+ "&main_prod=" + encodeURIComponent($("#main_prod").is(':checked')) 
			+ "&grp_head=" + encodeURIComponent($("#grp_head").is(':checked')) 
			+ "&job_req=" + encodeURIComponent($("#job_req").is(':checked')) 
			+ "&mail_active=" + encodeURIComponent($("#mail_active").is(':checked'))
			+ "&mail_save=" + encodeURIComponent($("#mail_save").is(':checked')) 
			+ "&mail_disable=" + encodeURIComponent($("#mail_disable").is(':checked')) 
			+ "&mail_delete=" + encodeURIComponent($("#mail_delete").is(':checked')) 
			+ "&mail_search=" + encodeURIComponent($("#mail_search").is(':checked')) 
			+ "&reset_password=" + encodeURIComponent($("#reset_password").is(':checked'))
			+ "&mail_forward=" + encodeURIComponent($("#mail_forward").is(':checked'))
			+ "&form_adminid=" + encodeURIComponent($("#form_adminid").val())
			+"&dummy=" + Math.floor(Math.random()*10230000000); 
			
		//alert(passData);
	}
	else if(command == 'delete')
	{
		var confirmation = confirm("Are you sure you want to delete the selected Employee?");
		if(confirmation)
		{
			passData =  "switchtype=delete&form_adminid=" + encodeURIComponent($("#form_adminid").val()) 
			+ "&dummy=" + Math.floor(Math.random()*10000000000);
		}
		else
		return false;
	}
	
	
		queryString = "../ajax/register_user_ajax.php";
		error.html(getprocessingimage());
		
		ajaxcall1 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						error.html(successmessage(response['errormessage']));
						gettotalusercount();
						//loadDataforwarder(1);
						newentry();
						
					}
					else if(response['errorcode'] == '2')
					{
						$('#changepassword').dialog('close');
						error.html(successmessage(response['errormessage']));
						//alert(response['erroralert']);
						refreshuserarray();
						//loadDataforwarder(1);
						//gettotalusercount();
						newentry();
						
					}
					else if(response['errorcode'] == '3')
					{
						//alert(response['erroralert']);
						error.html(errormessage(response['errormessage']));
						//loadData(1);
						//loadDataforwarder(1);
						newentry();
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	

	}


function getuserlist1()
{	
	//disableformelemnts();
	var form = document.leaduploadform;
	var selectbox = document.getElementById('userlist');
	var numberofusers = userarray.length;
	document.filterform.detailsearchtext.focus();
	var actuallimit = 500;
	var limitlist = (numberofusers > actuallimit)?actuallimit:numberofusers;
	
	selectbox.options.length = 0;
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = userarray[i].split("^");
		selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
	}
	
}

function refreshuserarray()
{
	var passData = "switchtype=generateuserlist&fname=" + encodeURIComponent($("#fname").val()) 
		+"&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall2 = createajax();
	document.getElementById('userselectionprocess').innerHTML = getprocessingimage();
	queryString = "../ajax/register_user_ajax.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var response = ajaxcall2.responseText.split('^*^');
				userarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					userarray[i] = response[i];
				}
				getuserlist1();
				document.getElementById('userselectionprocess').innerHTML = successsearchmessage('All Users...');
				document.getElementById('totalcount').innerHTML = userarray.length;
			}
			else
				document.getElementById('userselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall2.send(passData);
}

function userdetailstoform(adminid)
{
	if(adminid != '')
	{
		totalarray = '';
		var form = $("#leaduploadform" );
		
		var passData = "switchtype=userdetailstoform&form_adminid=" + encodeURIComponent(adminid) +
		 "&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passData);
		queryString = "../ajax/register_user_ajax.php";
		$("#form-error").html(getprocessingimage())
		ajaxcall6 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					$("#form-error").html('');
					var response = ajaxresponse;
					var responsearray = response.split("^");
					
					$('#genpass').html('Reset Password');
					
						//document.getElementById('form_quota').value = '250';
						document.getElementById('form_adminid').value = responsearray[0];
						//alert(responsearray[0]);
						document.getElementById('fname').value = responsearray[1];
						document.getElementById('lname').value = responsearray[2];
						document.getElementById('login').value = responsearray[3];
						if (document.getElementById('password').value = responsearray[4])
						{
     						 document.getElementById('password').disabled = 'true';
					    }
						else 
						{
							document.getElementById('password').disabled = 'false';
  						}
						document.getElementById('email').value = responsearray[5];
						
						
						// checkbox list
						if(responsearray[6]=='1') 
						{
							document.getElementById('reg_form').checked="checked";
						}
						else
						{
							document.getElementById('reg_form').checked=null;
						}	
						if(responsearray[7]=='1') 
						{
							document.getElementById('prd_master').checked="checked";
						}
						else
						{
							document.getElementById('prd_master').checked=null;
						}	
						if(responsearray[8]=='1') 
						{
							document.getElementById('ver_update').checked="checked";
						}
						else
						{
							document.getElementById('ver_update').checked=null;
						}	
						if(responsearray[9]=='1') 
						{
							document.getElementById('hot_update').checked="checked";
						}
						else
						{
							document.getElementById('hot_update').checked=null;
						}	
						if(responsearray[10]=='1') 
						{
							document.getElementById('flash_news').checked="checked";
						}
						else
						{
							document.getElementById('flash_news').checked=null;
						}	
						
						if(responsearray[11]=='1') 
						{
							document.getElementById('grp_head').checked="checked";
						}
						else
						{
							document.getElementById('grp_head').checked=null;
						}	
						if(responsearray[12]=='1') 
						{
							document.getElementById('job_req').checked="checked";
						}
						else
						{
							document.getElementById('job_req').checked=null;
						}	
						if(responsearray[13]=='1') 
						{
							document.getElementById('main_prod').checked="checked";
						}
						else
						{
							document.getElementById('main_prod').checked=null;
						}	
						if(responsearray[14]=='1') 
						{
							document.getElementById('mail_active').checked="checked";
						}
						else
						{
							document.getElementById('mail_active').checked=null;
						}	
						if(responsearray[15]=='1') 
						{
							document.getElementById('mail_save').checked="checked";
						}
						else
						{
							document.getElementById('mail_save').checked=null;
						}	
						if(responsearray[16]=='1') 
						{
							document.getElementById('mail_disable').checked="checked";
						}
						else
						{
							document.getElementById('mail_disable').checked=null;
						}
						if(responsearray[17]=='1') 
						{
							document.getElementById('mail_delete').checked="checked";
						}
						else
						{
							document.getElementById('mail_delete').checked=null;
						}	
						if(responsearray[18]=='1') 
						{
							document.getElementById('mail_search').checked="checked";
						}
						else
						{
							document.getElementById('mail_search').checked=null;
						}
						if(responsearray[19]=='1') 
						{
							document.getElementById('reset_password').checked="checked";
						}
						else
						{
							document.getElementById('reset_password').checked=null;
						}
						if(responsearray[20]=='1') 
						{
							document.getElementById('mail_forward').checked="checked";
						}
						else
						{
							document.getElementById('mail_forward').checked=null;
						}	
						enablebutton('delete');
						enablebutton('new');
				
				}
			}, 
			error: function(a,b)
			{
				$("#userselectionprocess").html(scripterror());
			}
		});	
	}
}

function selectfromlist()
{
	//enableregbuttons();
	var selectbox = document.getElementById('userlist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
	//enableformelemnts();
	userdetailstoform(selectbox.value);	
}


function selectuser(input)
{
	var selectbox = document.getElementById('userlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getuserlist1();
	}
	else
	{
		selectbox.options.length = 0;
		var addedcount = 0;
		for( var i=0; i < userarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = userarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = userarray[i];
				}
			if(pattern.test(userarray[i].toLowerCase()))
			{
				var splits = userarray[i].split("^");
				selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}


function usersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrolluser('up');
	else if(KeyID == 40)
		scrolluser('down');
	else
	{
		var form = document.leaduploadform;
		var input = document.getElementById('detailsearchtext').value;
		selectuser(input);
	}
}


function searchbyuseremailevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchuseremail').val();
		searchbyuseremail(input);
	}
}

function searchbyuseremail(adminid)
{
	if(email != '')
	{
		totalarray = '';
		
		var passData = "switchtype=searchbyuseremail&searchuseremail=" + encodeURIComponent(adminid) + 
		"&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		queryString = "../ajax/register_user_ajax.php";
		$("#form-error").html(getprocessingimage())
		ajaxcall6 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					$("#form-error").html('');
					if(ajaxresponse == '')
					{
						$("#form-error").html(errormessage('Search Details Un - Available'));
					}
					else
					{
						var response = ajaxresponse;
						var responsearray = response.split("^");
					
						//document.getElementById('form_quota').value = '250';
						document.getElementById('form_adminid').value = responsearray[0];
						//alert(responsearray[0]);
						document.getElementById('fname').value = responsearray[1];
						document.getElementById('lname').value = responsearray[2];
						document.getElementById('login').value = responsearray[3];
						document.getElementById('password').value = responsearray[4];
						document.getElementById('email').value = responsearray[5];
						
						if(responsearray[6]=='1') 
						{
							document.getElementById('reg_form').checked="checked";
						}
						else
						{
							document.getElementById('reg_form').checked=null;
						}	
						if(responsearray[7]=='1') 
						{
							document.getElementById('prd_master').checked="checked";
						}
						else
						{
							document.getElementById('prd_master').checked=null;
						}	
						if(responsearray[8]=='1') 
						{
							document.getElementById('ver_update').checked="checked";
						}
						else
						{
							document.getElementById('ver_update').checked=null;
						}	
						if(responsearray[9]=='1') 
						{
							document.getElementById('hot_update').checked="checked";
						}
						else
						{
							document.getElementById('hot_update').checked=null;
						}	
						if(responsearray[10]=='1') 
						{
							document.getElementById('flash_news').checked="checked";
						}
						else
						{
							document.getElementById('flash_news').checked=null;
						}
						if(responsearray[11]=='1') 
						{
							document.getElementById('main_prod').checked="checked";
						}
						else
						{
							document.getElementById('main_prod').checked=null;
						}	
						if(responsearray[12]=='1') 
						{
							document.getElementById('grp_head').checked="checked";
						}
						else
						{
							document.getElementById('grp_head').checked=null;
						}	
						
						if(responsearray[13]=='1') 
						{
							document.getElementById('job_req').checked="checked";
						}
						else
						{
							document.getElementById('job_req').checked=null;
						}
						if(responsearray[14]=='1') 
						{
							document.getElementById('mail_active').checked="checked";
						}
						else
						{
							document.getElementById('mail_active').checked=null;
						}	
						if(responsearray[15]=='1') 
						{
							document.getElementById('mail_save').checked="checked";
						}
						else
						{
							document.getElementById('mail_save').checked=null;
						}	
						if(responsearray[16]=='1') 
						{
							document.getElementById('mail_disable').checked="checked";
						}
						else
						{
							document.getElementById('mail_disable').checked=null;
						}
						if(responsearray[17]=='1') 
						{
							document.getElementById('mail_delete').checked="checked";
						}
						else
						{
							document.getElementById('mail_delete').checked=null;
						}	
						if(responsearray[18]=='1') 
						{
							document.getElementById('mail_search').checked="checked";
						}
						else
						{
							document.getElementById('mail_search').checked=null;
						}
						if(responsearray[19]=='1') 
						{
							document.getElementById('reset_password').checked="checked";
						}
						else
						{
							document.getElementById('reset_password').checked=null;
						}
						if(responsearray[20]=='1') 
						{
							document.getElementById('mail_forward').checked="checked";
						}
						else
						{
							document.getElementById('mail_forward').checked=null;
						}	
						enablebutton('delete');
						enablebutton('new');	
					
					}
				}
			}, 
			error: function(a,b)
			{
				$("#userselectionprocess").html(scripterror());
			}
		});	
	}
}

function newentry()
{
	var form = $("#leaduploadform");
	totalarray = '';
	$("#leaduploadform" )[0].reset();
	$("#fname" ).val('');
	$("#lname" ).val('');
	$("#login" ).val('');
	$("#email" ).val('');
	$("#password" ).val('');
	$('#genpass').html('');
	disablebutton('delete');
	disablebutton('new');
}

function gettotalusercount()
{
	var form = $('#userselectionprocess');
	var passData = "switchtype=getusercount&dummy=" 
	+ "&form_adminid=" + encodeURIComponent($("#form_adminid").val())+ Math.floor(Math.random()*10054300000);
	queryString = "../ajax/register_user_ajax.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$("#totalcount").html(response['count']);
				refreshuserarray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#userselectionprocess").html(scripterror());
		}
	});	
}


function randompassword(elementid) 
{
	var chars = "123@5#789";
	var string_length = 10;
	var randomstring = '';
	for (var i=0; i<string_length; i++) 
	{
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	document.getElementById(elementid).value = randomstring;
}

function disablebutton(element)
{
	document.getElementById(element).disabled = true;
	document.getElementById(element).className = 'swiftchoicebuttondisabled';
	document.getElementById(element).style.cursor = '';
}

//Function to enable the button------------------------------------------------------------------------------
function enablebutton(element)
{
	document.getElementById(element).disabled = false;
	document.getElementById(element).className = 'swiftchoicebutton';
}