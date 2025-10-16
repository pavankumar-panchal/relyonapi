var employeearray = new Array();
var totalarray = new Array();
var employeearray1 = new Array();
var employeearray2 = new Array();
var employeearray3 = new Array();
var employeearray4 = new Array();

var contactarray = '';

function formsubmit(command)
{
	$('#save').removeClass('button_enter1');
	var passData = "";
	var form = $("#leaduploadform" );
	var error = $("#form-error" );
	var msg_box = $("#msg_box");
	var phonevalues = '';
	var cellvalues = '';
	var emailvalues = '';
	var namevalues = '';
	if(command == 'save')
	{
		var field = $("#form_employee");  
		if (!field.val())
		{ error.html( errormessage("Please Enter Employee Name.")); field.focus(); return false;}
				
		var field = $("#form_email");  
		if (!field.val())
		{ error.html(errormessage("Please Enter Email Address (Only Username).")); field.focus(); return false;}
		
		var field = $("#form_cid");  
		if (!field.val())
		{ error.html(errormessage("Please Select a category.")); field.focus(); return false;}

		if ($("#form_cid").val() == "4")
		{
			var field = $("#form_employeeid");  
			if (!field.val())
			{ error.html(errormessage("Please Enter Employee ID.")); field.focus(); return false;}
		}
		
		var field = $("#form_password");  
		if (!field.val())
		{ error.html(errormessage("Please Enter Password.")); field.focus(); return false;}
		
		var field = $("#DPC_date");  
		if (!field.val())
		{ error.html(errormessage("Please Enter the Create Date.")); field.focus(); return false;}
		
		var field = $("#form_department");  
		if (!field.val())
		{ error.html(errormessage("Please Enter Department.")); field.focus(); return false;}
		
		var field = $("#form_quota");  
		if (!field.val())
		{ error.html(errormessage("Please Enter a Email Quota in MB.")); field.focus(); return false;}
		
		var field = $("#form_grouphead");  
		if (!field.val())
		{ error.html(errormessage("Please Select a Grouphead.")); field.focus(); return false;}
		
		var field = $("#form_requestedby");  
		if (!field.val())
		{error.html(errormessage("Please Enter a Requested By.")); field.focus(); return false;}
		
		var passData = "&switchtype=save&form_employee=" + encodeURIComponent($("#form_employee").val()) 
			+ "&form_employeeid=" + encodeURIComponent($("#form_employeeid").val()) 
			+ "&form_emailid=" + encodeURIComponent($("#form_emailid").val())
			+ "&form_quota=" + encodeURIComponent($("#form_quota").val())
			+ "&form_password=" + encodeURIComponent($("#form_password").val()) 
			+ "&form_cid=" + encodeURIComponent($("#form_cid").val()) 
			+ "&DPC_date=" + encodeURIComponent($("#DPC_date").val()) 
			+ "&form_email=" + encodeURIComponent($("#form_email").val()) 
			+ "&form_department=" + encodeURIComponent($("#form_department").val()) 
			+ "&form_forwards=" + encodeURIComponent($("#form_forwards").val()) 
			+ "&form_grouphead=" + encodeURIComponent($("#form_grouphead").val()) 
			+ "&form_requestedby=" + encodeURIComponent($("#form_requestedby").val()) 
			+ "&form_remarks=" + encodeURIComponent($("#form_remarks").val()) 
			+ "&form_reason=" + encodeURIComponent($("#form_reason").val())
			+ "&check_disable=" + encodeURIComponent($("#check_disable").is(':checked')) 
			+"&dummy=" + Math.floor(Math.random()*10230000000); 
			
		//alert(passData);
	}
	else if(command == 'delete')
	{
		var confirmation = confirm("Are you sure you want to delete the selected Employee?");
		if(confirmation)
		{
			passData =  "switchtype=delete&form_emailid=" + encodeURIComponent($("#form_emailid").val()) 
			+ "&form_remarks=" + encodeURIComponent($("#form_remarks").val())
			+ "&form_reason=" + encodeURIComponent($("#form_reason").val())
			+ "&dummy=" + Math.floor(Math.random()*10000000000);
		}
		else
		return false;
	}
	else if(command == 'resetpwd')
	{
		var field = $("#form_changepass");  
		if (!field.val())
		{alert('Please Enter input into Reset Password.');error.html(errormessage("Please Enter input into Reset Password.")); field.focus(); return false;}
		
		var field = $("#form_passremarks");  
		if (!field.val())
		{alert('Please Enter input into Reset Password Remark. ');error.html(errormessage("Please Enter input into Reset Password Remark.")); field.focus(); return false;}

		var confirmation = confirm("Are you sure you want to Reset Password the Selected Employee?");
		if(confirmation)
		{
			passData =  "switchtype=resetpwd&form_emailid=" + encodeURIComponent($("#form_emailid").val()) 
			+ "&form_employee=" + encodeURIComponent($("#form_employee").val())
			+ "&form_employeeid=" + encodeURIComponent($("#form_employeeid").val())
			+ "&form_changepass=" + encodeURIComponent($("#form_changepass").val())
			+ "&form_passremarks=" + encodeURIComponent($("#form_passremarks").val())
			+ "&dummy=" + Math.floor(Math.random()*10000000000);
		}
		else
		return false;
	}
	else if (command == 'checkmail')
	{
		passData =  "switchtype=checkmailid&form_email=" + encodeURIComponent($("#form_email").val()) 
			+ "&dummy=" + Math.floor(Math.random()*10000000000);
	}	

		queryString = '../ajax/saralmail_ajax.php';
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
						gettotalemployeecount();
						loadDataforwarder();
						newentry();
						
					}
					else if(response['errorcode'] == '2')
					{
						$('#changepassword').dialog('close');
						error.html(successmessage(response['errormessage']));
						//alert(response['erroralert']);
						refreshemployeearray();
						loadDataforwarder();
						newentry();
						
					}
					else if(response['errorcode'] == '3')
					{
						alert(response['erroralert']);
						error.html(errormessage(response['errormessage']));
						loadData();
						loadDataforwarder();
						newentry();
					}
					else if(response['errorcode'] == '4')
					{
						
						error.html(successmessage(response['errormessage']));
						gettotalemployeecount();
						loadDataforwarder();
						newentry();
						
					}
					else if(response['errorcode'] == '5')
					{
						error.html(errormessage(response['errormessage']));
						$('#successimage').html(getunsuccessimage())
					}

					else if(response['errorcode'] == '6')
					{
						error.html(successmessage(response['errormessage']));
						$('#successimage').html(getsuccessimage())
					}
					else if(response['errorcode'] == '7')
					{
						error.html(response['errormessage']);
					}

				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
}

function gettotalemployeecount()
{
	var form = $('#employeeselectionprocess');
	var passData = "switchtype=getemployeecount&dummy=" 
	+ "&form_emailid=" + encodeURIComponent($("#form_emailid").val())+ Math.floor(Math.random()*10054300000);
	queryString = "../ajax/saralmail_ajax.php";
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
				refreshemployeearray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#employeeselectionprocess").html(scripterror());
		}
	});	
}

function randompassword(elementid) 
{
	var chars = "0123456789";
	var string_length = 10;
	var randomstring = '';
	for (var i=0; i<string_length; i++) 
	{
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	document.getElementById(elementid).value = randomstring;
}

function getemployeelistonsearch()
{	
	var form = $("#leaduploadform" );
	var selectbox = $('#employeelist');
	var numberofemployees = employeesearcharray.length;
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
	var actuallimit = 500;
	var limitlist = (numberofemployees > actuallimit)?actuallimit:numberofemployees;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = employeesearcharray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}

function getemployeelist1()
{	
	//disableformelemnts();
	var form = document.leaduploadform;
	var selectbox = document.getElementById('employeelist');
	var numberofemployees = employeearray.length;
	document.filterform.detailsearchtext.focus();
	var actuallimit = 500;
	var limitlist = (numberofemployees > actuallimit)?actuallimit:numberofemployees;
	
	selectbox.options.length = 0;
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = employeearray[i].split("^");
		selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
	}
	
}

function displayalemployee()
{	
	var form = $("#leaduploadform" );
	flag = true;
	var selectbox = $('#employeelist');
	$('#employeeselectionprocess').html(successsearchmessage('All Employee...'));
	var numberofemployees = employeearray.length;
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
	var actuallimit = 500;
	var limitlist = (numberofemployees > actuallimit)?actuallimit:numberofemployees;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = employeearray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	$('#totalcount').html(employeearray.length);
}

function newentry()
{
	var form = $("#leaduploadform");
	totalarray = '';
	$("#empid").html('');
	$("#empid").html('<input name="form_employeeid" placeholder="Enter only digit" type="text" class="textfield" id="form_employeeid" size="34" />');
	
	$("#emailaddr").html('');
	$("#emailaddr").html('<input name="form_email" type="text" class="textfield" placeholder="Enter only username"  id="form_email" size="34" maxlength="200" onChange="formsubmit(\'checkmail\');" />');
	
	$("#pass").html('');
	$("#pass").html('<input name="form_password" type="text" class="textfield" id="form_password" placeholder="Double click here for random number" onDblClick="randompassword(\'form_password\');" size="34" readonly>');

	$("#leaduploadform" )[0].reset();
	$("#form_emailid" ).val('');
	$("#form_email" ).val('');
	$("#form_password" ).val('');
	$("#form_employee" ).val('');
	$("#form_employeeid" ).val('');
	$("#form_department" ).val('');
	$("#form_grouphead" ).val('');
	$("#form_requestedby" ).val('');
	$("#form_changepass" ).val('');
	$("#form_passremarks" ).val('');
	$('#successimage').html('');
	$("#form_reason" ).val('');
	$("#DPC_date" ).html('Not Available');
	
	disablebutton('delete');
	disablebutton('new');
	disablecheck();
	disableinput('form_changepass');
	disableinput('form_passremarks');
	enableinput('form_password');
	enableinput('form_email');
	
	//$('#form_cid').attr('onChange','employeeid();');
	//alert($("#form_cid").attr("onchange"));
	$('#form_grouphead').attr('onChange','empdepartment()');
}

function blockelement(elementid)
{
	var element = document.getElementById(elementid);
	if(element.style.display == 'none')
		element.style.display = 'block';
}

//Function to make the display as only hide-------------------------------------------------------------------------
function hideelement(elementid)
{
	var element = document.getElementById(elementid);
	if(element.style.display == 'block')
		element.style.display = 'none';
}

//Function to disable the Input -----------------------------------------------------------------------------
function disableinput(elementid)
{
	document.getElementById(elementid).disabled = true;
	document.getElementById(elementid).style.cursor = '';
}

//Function to enable the check box------------------------------------------------------------------------------
function enableinput(elementid)
{
	document.getElementById(elementid).disabled = false;
}

//Function to disable the check box-----------------------------------------------------------------------------
function disablecheck()
{
	document.getElementById('check_disable').disabled = true;
	document.getElementById('check_disable').style.cursor = '';
}

//Function to enable the check box------------------------------------------------------------------------------
function enablecheck()
{
	document.getElementById('check_disable').disabled = false;
}

//Function to disable the button-----------------------------------------------------------------------------
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

function employeedetailstoform(emailid)
{
	if(emailid != '')
	{
		totalarray = '';
		var form = $("#leaduploadform" );
		
		var passData = "switchtype=employeedetailstoform&form_emailid=" + encodeURIComponent(emailid) +
		 "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
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
					if(responsearray[14]=='1')
					{
						document.getElementById('form_quota').value = '250';
						document.getElementById('form_emailid').value = responsearray[0];//alert(responsearray[0]);
						document.getElementById('form_employee').value = responsearray[1];
						$("#empid").html('');
						$("#empid").html(responsearray[2]+'<input name="form_employeeid" placeholder="Enter only digit" type="hidden" class="textfield" id="form_employeeid" size="34" />');
				
						document.getElementById('form_employeeid').value = responsearray[2];
						document.getElementById('form_department').value = responsearray[3];
						document.getElementById('form_requestedby').value = responsearray[4];
						document.getElementById('form_grouphead').value = responsearray[5];
						document.getElementById('DPC_date').value = responsearray[6];
						
						$("#emailaddr").html('');
						$("#emailaddr").html(responsearray[7]+'<input name="form_email" type="hidden" class="textfield" placeholder="Enter only username"  id="form_email" size="34" maxlength="200"  />');
						document.getElementById('form_email').value = responsearray[7];//alert(responsearray[7])
						document.getElementById('form_cid').value = responsearray[8];
						document.getElementById('form_forwards').value = responsearray[9];//alert(responsearray[9])
						document.getElementById('form_remarks').value = responsearray[10];
						
						$("#pass").html('');
						$("#pass").html(responsearray[11]+'<input name="form_password" type="hidden" class="textfield" id="form_password" placeholder="Double click here for random number"  size="34" readonly>');
						document.getElementById('form_password').value = responsearray[11];
						document.getElementById('form_reason').value = responsearray[13];
						//alert(responsearray[12]);
						if(responsearray[12]=='1') 
						{
							document.getElementById('check_disable').checked="checked";
						}
						else
						{
							document.getElementById('check_disable').checked=null;
						}		
						enablebutton('delete');
						enablebutton('new');
						enablecheck();
						enableinput('form_changepass');
						enableinput('form_passremarks');
						
						//disableinput('form_employeeid');
						//disableinput('form_password');
						//disableinput('form_email');
						
						$('#form_cid').removeAttr("onchange");
						$('#form_grouphead').removeAttr("onchange");
						
						$("#genpass").click(function()
						{
							loadData();
						});
						$("#forwardslist").click(function()
						{
							loadDataforwarder();
						});
					}
					else if(responsearray[13]=='2')
					{
						document.getElementById('form_emailid').value = '';
						newentry();
					}
				}
			}, 
			error: function(a,b)
			{
				$("#employeeselectionprocess").html(scripterror());
			}
		});	
	}
}

function selectfromlist()
{
	//enableregbuttons();
	var selectbox = document.getElementById('employeelist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
	//enableformelemnts();
	employeedetailstoform(selectbox.value);	
}
function refreshemployeearray()
{
	var passData = "switchtype=generateemployeelist&form_employee=" + encodeURIComponent($("#form_employee").val()) 
		+"&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall2 = createajax();
	document.getElementById('employeeselectionprocess').innerHTML = getprocessingimage();
	queryString = "../ajax/saralmail_ajax.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var response = ajaxcall2.responseText.split('^*^');
				employeearray = new Array();
				for( var i=0; i<response.length; i++)
				{
					employeearray[i] = response[i];
				}
				getemployeelist1();
				document.getElementById('employeeselectionprocess').innerHTML = successsearchmessage('All Employee...');
				document.getElementById('totalcount').innerHTML = employeearray.length;
			}
			else
				document.getElementById('employeeselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall2.send(passData);
}

function selectaemployee(input)
{
	var selectbox = document.getElementById('employeelist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getemployeelist1();
	}
	else
	{
		selectbox.options.length = 0;
		var addedcount = 0;
		for( var i=0; i < employeearray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = employeearray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = employeearray[i];
				}
			if(pattern.test(employeearray[i].toLowerCase()))
			{
				var splits = employeearray[i].split("^");
				selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
				//selectbox.options[0].selected= true;
				//employeedetailstoform(selectbox.options[0].value); //document.getElementById('delaerrep').disabled = true;
				//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration(); 
			}
		}
	}
}

function employeesearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollemployee('up');
	else if(KeyID == 40)
		scrollemployee('down');
	else
	{
		var form = document.leaduploadform;
		var input = document.getElementById('detailsearchtext').value;
		selectaemployee(input);
	}
}

function scrollemployee(type)
{
	var selectbox = document.getElementById('employeelist');
	var totalcus = selectbox.options.length;
	var selectedcus = selectbox.selectedIndex;
	if(type == 'up' && selectedcus != 0)
		selectbox.selectedIndex = selectedcus - 1;
	else if(type == 'down' && selectedcus != totalcus)
		selectbox.selectedIndex = selectedcus + 1;
	selectfromlist();
}

function searchbyemployeemailevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchemployeemail').val();
		searchbyemployeemail(input);
	}
}

function searchbyemployeemail(email)
{
	if(email != '')
	{
		totalarray = '';
		
		var passData = "switchtype=searchbyemployeemail&searchemployeemail=" + encodeURIComponent(email) + 
		"&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
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
						
						document.getElementById('form_quota').value = '250';
						document.getElementById('form_emailid').value = responsearray[0];
						document.getElementById('form_employee').value = responsearray[1];
						$("#empid").html('');
						$("#empid").html(responsearray[2]);
						document.getElementById('form_employeeid').value = responsearray[2];
						document.getElementById('form_department').value = responsearray[3];
						document.getElementById('form_requestedby').value = responsearray[4];
						document.getElementById('form_grouphead').value = responsearray[5];
						document.getElementById('DPC_date').value = responsearray[6];
						$("#emailaddr").html('');
						$("#emailaddr").html(responsearray[7]);
						document.getElementById('form_email').value = responsearray[7];//alert(responsearray[7])
						document.getElementById('form_cid').value = responsearray[8];
						document.getElementById('form_forwards').value = responsearray[9];//alert(responsearray[9])
						document.getElementById('form_remarks').value = responsearray[10];
						$("#pass").html('');
						$("#pass").html(responsearray[11]);
						document.getElementById('form_password').value = responsearray[11];
						document.getElementById('form_reason').value = responsearray[13];
						//alert(responsearray[12]);
						if(responsearray[12]=='1') 
						{
							document.getElementById('check_disable').checked="checked";
						}
						else
						{
							document.getElementById('check_disable').checked=null;
						}	
						$("#genpass").click(function()
						{	
							loadData();
						});
						$("#forwardslist").click(function()
						{	
							loadDataforwarder();
						});
						enablebutton('delete');
						enablebutton('new');
						enablecheck();
						
						$('#form_cid').removeAttr("onchange");
						$('#form_grouphead').removeAttr("onchange");

						
						//disableinput('form_employeeid');
						//disableinput('form_password');
						//disableinput('form_email');
						
						enableinput('form_changepass');
						enableinput('form_passremarks');
					}
				}
			}, 
			error: function(a,b)
			{
				$("#employeeselectionprocess").html(scripterror());
			}
		});	
	}
}

//Bhavesh Patel
function geturllink()
{
	
	var URLParser = function (url) {
    this.url = url || window.location.href;
    this.urlObject = this.parse(); 
};

URLParser.prototype = {
    constructor : URLParser,
    
    parse : function (url) {
        var tempArr,
            item,
            i,
            returnObj = {};
        this.url = url || this.url;
        tempArr = this.url.split("?");
        returnObj.baseURL = tempArr[0];
        returnObj.params = {};
        if (tempArr.length > 1) {
            returnObj.queryString = tempArr[1];
            tempArr = tempArr[1].split("&");
            for (i = 0; i < tempArr.length; i++) {
                item = tempArr[i].split("=");
                returnObj.params[item[0]] = item[1];    
            }
        } else {
            returnObj.queryString = "";
        }
        
        return returnObj;
    },
    
    toString : function () {
        var strURL = this.urlObject.baseURL + "?",
            paramObj = this.urlObject.params,
            prop;
        for (prop in paramObj) {
            if (paramObj.hasOwnProperty(prop)) {
                strURL += prop + "=" + paramObj[prop] + "&";
            }
         }
        return strURL.substr(0, strURL.length - 1);
    },
    
    removeParams : function (removeArray) {
        var paramObj = this.urlObject.params,
            key,
            i;
        if (removeArray instanceof Array) {
            for (i = 0; i < removeArray.length; i++) {
                key = removeArray[i];
                if (paramObj.hasOwnProperty(key)) {
                    delete paramObj[key];
                }
            }
        }
    },
    
    addParams : function (paramObj) {
        var params = this.urlObject.params,
            key;
        if (typeof paramObj === "object") {
            for (key in paramObj) {
                if (paramObj.hasOwnProperty(key)) {
                    params[key] = paramObj[key];
                }
            }
        }
    }
};


var up = new URLParser();
var urlObj = up.parse(currentPageUrl);

//alert(urlObj.params['emailid']);

	var currentPageUrl = "";
	if (typeof this.href === "undefined") {
		currentPageUrl = document.location.toString().toLowerCase();
	}
	else 
	{
		currentPageUrl = this.href.toString().toLowerCase();
	}
	//alert(currentPageUrl);
	employeedetailstoform(urlObj.params['emailid']);
}

	
function loading_show()
{
	$('#loading').html("<img src='../images/loading.gif'>").fadeIn('fast');
}

function loading_hide()
{
	$('#loading').fadeOut('fast');
}        

//RESET PASSWOrd DIALog BOX
function viewdialogbox(element) 
{ 
		if($("#form_emailid").val()!="")
		{
			$(element).dialog({modal: true, height: 350 , width: 800 });
		}
		else{
			var field = $("#form-error"); 
			field.html( errormessage("Please Select Employee Name.")); field.focus(); return false;
		}
}

function delete_record(form_fid,form_emailid,form_forwards)
{
    var del= confirm("Do you want to Delete?");
    if (del== true){
        delsubmit(form_fid,form_emailid,form_forwards);
    }
}

function delsubmit(form_fid,form_emailid,form_forwards)
{
	var passData = "switchtype=deleteforwarder&form_fid=" + form_fid + 
	"&form_emailid=" + form_emailid +
	 "&form_forwards=" + form_forwards; 
	
	//alert(passdata);
	var error = $("#form-error" );
		queryString = '../ajax/saralmail_ajax.php';
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
					if(response['errorcode'] == '2')
					{
						alert(response['erroralert']);
						error.html(successmessage(response['errormessage']));
						loadDataforwarder();
						//newentry();
					}

					else if(response['errorcode'] == '3')
					{
						alert(response['erroralert']);
						error.html(successmessage(response['errormessage']));
						loadDataforwarder();
						//newentry();
						
					}
				}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function employeeid()
{
	var cid = $("#form_cid").val();
	var emailid = $("#form_emailid").val();
	
	if(cid == '4')
	{
		var passdata = "&switchtype=employeeid&cid=" + encodeURIComponent(cid) +
		 "&emailid=" + encodeURIComponent(emailid)
		 "&dummy=" + Math.floor(Math.random()*10000000000);;
		//alert(passdata);
		
		var error = $("#form-error" );
		var queryString = "../ajax/saralmail_ajax.php";
		$("#form-error").html(getprocessingimage());
		ajaxobjext38 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
					var response = (response);
					var ajaxresponse = response.split("^"); 
					
					if(ajaxresponse[0] == 1)
					{
						$("#form-error").html('');
						$('#form_employeeid').val(ajaxresponse[1]);
					}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});
	}
	else
	{
		$('#form_employeeid').val('');
	}
}

function empdepartment()
{
	var passdata = "&switchtype=empdepartment&grouphead=" + encodeURIComponent($('#form_grouphead').val()) +
	 "&dummy=" + Math.floor(Math.random()*10000000000);;
	//alert(passdata);
	
	var error = $("#form-error" );
	var queryString = "../ajax/saralmail_ajax.php";
	$("#form-error").html(getprocessingimage());
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
				var response = (response);
				var ajaxresponse = response.split("^"); 
				
				if(ajaxresponse[0] == 1)
				{
					$("#form-error").html('');
					$('#form_department').val(ajaxresponse[1]);
				}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function suggestionuser()
{
	var employee = $("#form_employee").val();
		//document.write(str.substr(4,4));
	var	namesplit = employee.replace(" ",".");
	/*var namesplit =	employee.split(" ")
	var split0 = namesplit[0]
	var split1 = namesplit[1].substring(-1);
	var username = split0.toLowerCase()+'.'+split1.toLowerCase()*/
	var username = namesplit.toLowerCase();
	$('#form_email').val(username);
}



function loadData()
{
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	//$('#tabgroupgridf1_2').html('');
	var passdata = "&switchtype=resetable&form_emailid=" + encodeURIComponent($('#form_emailid').val());
	var queryString = "../ajax/saralmail_ajax.php",
	
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
						$('#tabgroupgridc1').show();
						$('#tabgroupgridf1_2').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
						//alert(ajaxresponse[3]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
				}
				else if(ajaxresponse[0] == '2')
				{
						$('#tabgroupgridc1').show();
						$('#tabgroupgridf1_2').html(ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
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
	
function getrecords(startlimit,slnocount,showtype)
{
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&switchtype=table&startlimit=" + encodeURIComponent(startlimit)+"&slnocount="+slnocount+"&showtype="+showtype ;
	//alert(passdata);
	var queryString = "../ajax/saralmail_ajax.php",
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
						$('#resultgridf2').html($('#tabgroupgridf1_2').html());
						$('#tabgroupgridf1_2').html($('#resultgridf2').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
						$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
				}
				else if(ajaxresponse[0] == '2')
				{
						$('#resultgridf2').html($('#tabgroupgridf1_2').html());
						$('#tabgroupgridf1_2').html($('#resultgridf2').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
						$('#tabgroupgridlinkf2').html(ajaxresponse[2]);
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

function loadDataforwarder()
{
	startlimit = '';
	$("#gridprocessf").html(processing()+'  ' + '<span onclick = "abortajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var passdata = "&switchtype=forwardertable&form_emailid=" + encodeURIComponent($('#form_emailid').val());
	var queryString = "../ajax/saralmail_ajax.php",
	
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
	var passdata = "&switchtype=table&startlimit=" + encodeURIComponent(startlimit)+"&slnocount="+slnocount+"&showtype="+showtype ;
	//alert(passdata);
	var queryString = "../ajax/saralmail_ajax.php",
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
