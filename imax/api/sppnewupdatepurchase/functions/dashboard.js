$(document).ready(function() {


var intRegex = /^\d+$/;
var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
var processing;
var amountpayable = $('#amountpayable').val();
var show_slno = $('#show_slno').val();

$("#customertanerror").html("");

$('#givepaymentoption').html(""); 
$('#givepaymentoptions').html(""); 

if(intRegex.test(amountpayable) || floatRegex.test(amountpayable)) { processing = 'TRUE'; }
else { processing = 'FALSE'; alert('Entered Value is Not a Number'); }

if(processing === 'TRUE' && amountpayable > 30000) {  

$("#givepaymentoption").html('<a onclick="loadpayconfirm(\''+ show_slno +'\');" class="sub_headingfont mybutton">Pay Now - Rs.'+amountpayable+'\.00 /-</a>');

$("#givepaymentoptions").html('<a onclick="loadpayconfirm(\''+ show_slno +'\');" class="sub_headingfont mybutton">Pay Now - Rs.'+amountpayable+'\.00 /-</a>');

var totalamount = $("#totalamountpay").text();
var deductionmade = Math.round(totalamount*(0.10));

var finalpay = Math.round(amountpayable - deductionmade);

$("#payamountcal").html('<div class="amountdata"><span class="mypay">Amount to Pay :</span> Rs.'+amountpayable+'\.00 /-<br><br><span class="mypay">Deduction Made :</span> Rs.'+deductionmade+'\.00 /-<br><br><span class="mypay">You Pay :</span> Rs.'+finalpay+'\.00 /-</div>');
 
}
else { 	

$("#givepaymentoption").html('<a onclick="paynow(\''+ show_slno +'\');" class="sub_headingfont mybutton">Pay Now - Rs.'+amountpayable+'\.00 /-</a>');
$("#givepaymentoptions").html('<a onclick="paynow(\''+ show_slno +'\');" class="sub_headingfont mybutton">Pay Now - Rs.'+amountpayable+'\.00 /-</a>');
} 




});

function loadpayconfirm() {    
var show_slno = $('#show_slno').val();

$("#innerpayconfirm").html('<button type="button" id="payconfirmyes" class="mybutton" onclick="loadformdata(\''+show_slno+'\')">Yes</button><button type="button" id="payconfirmno" class="mybutton" onclick="paynows(\''+show_slno+'\')">No</button>');

$('#payconfirmation').fadeIn("slow");      
}  
function unloadpayconfirm() {   
	var msg = '&nbsp;';
	$('#err').html(msg);   
	$('#payconfirmation').fadeOut("slow");    
} 

function paynows()
{


var passData =  "switchtype=customerdetails&customerid="+encodeURIComponent($("#customerid").val()) + 
	"&invoiceslno=" + encodeURIComponent($("#lslnop").val())+ "&customertanno=" + encodeURIComponent($("#customertan").val()) +"&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData);
	$.ajax({
			type : "POST",url:"ajax/updatetanno.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
                    paynow();
				}
			},
			 error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert(msg);
    }
		});


}


function paynow()
{ 
        var show_slno = $('#show_slno').val();
        $("#customertanerror").html('');
	$('#payconfirmation').fadeOut("slow");
   	loadPopupBox();
	$("body").append('<div class="modalOverlay">'); 
	$('#lslnop').val(show_slno);
	$('#onlineinvoiceno').val(show_slno);
}

function loadPopupBox() {    // To Load the Popupbox
	$('#invoicedetailsgrid').fadeIn("slow");
	/*$('body').css({ // this is just for style
		"opacity": "0.3"  
	});*/         
} 

function unloadPopupBox() {    // TO Unload the Popupbox
	var msg='&nbsp;';
	$('#err').html(msg);
	$('#invoicedetailsgrid').fadeOut("slow");
	/*$('body').css({ // this is just for style        
		"opacity": "1"  
	});*/ 
}    


function loadformdata() {    // To Load the Popupbox

        var show_slno = $('#show_slno').val();
	$('#lslnop').val(show_slno);
	$('#onlineinvoiceno').val(show_slno);

	$('#payconfirmation').fadeOut("slow");
	$('#customertangrid').fadeIn("slow"); 
        $('#toberemoved').css("visibility", "visible"); 

        $("#customertan").val("");
        $("#customertanerror").html('');
        $('#customertanumber').prop("disabled", false);    
} 
function unloadformdata() {    // TO Unload the Popupbox
	var msg = '&nbsp;';
	$('#err').html(msg);
	$('#customertangrid').fadeOut("slow");       
}

// JavaScript Document
function customertanumbers()
{
	var formtosubmit = document.submitexistformcusdata;
	//var tanno = document.getElementById('customertan').value;

        var inputvalue = $("#customertan").val();

        if(inputvalue === "") {

		//alert("Kindly Enter Your TAN No");
                $("#customertanerror").html('Missed Your Tan No');
		$("#customertan").focus();
                return false;
        }
        if(/^[a-zA-Z0-9- ]*$/.test(inputvalue) == false) {

		//alert("Kindly Enter Your TAN No");
                $("#customertanerror").html('Special Characters Not Allowed');
		$("#customertan").focus();
                return false;
        }
       if(inputvalue.length != 10) {
        $("#customertanerror").html("Enter 10 characters as your TAN No");
		$("#customertan").focus();
                return false;
       }

        $('.ajax-loader-cust').css("visibility", "visible");
	document.getElementById('customertanumber').disabled = true;

	var passData =  "switchtype=customerdetails&customerid="+encodeURIComponent($("#customerid").val()) + 
	"&invoiceslno=" + encodeURIComponent($("#lslnop").val())+ "&customertanno=" + encodeURIComponent($("#customertan").val()) +"&dummy=" + Math.floor(Math.random()*100000000);
	//alert(passData);
	$.ajax({
			type : "POST",url:"ajax/updatetan.php",data : passData,cache:false,dataType: "json",
			success: function(response,status)
			{
				ajaxresponse = response.split('^');
				if(ajaxresponse[0]== '1')
				{
				   $("#new_lslnop").val(ajaxresponse[2]);

		                   //formtosubmit.submit();
				   //document.getElementById("submitpayform").submit();

$('.ajax-loader-cust').css("visibility", "hidden");
$('#toberemoved').css("visibility", "hidden");
$("#customertanerror").html("");
$('#toberemovedmsg').css("visibility", "visible");
$("#toberemovedmsg").html('<center>Thank You. Kindly Wait While we Proceed With Payment.</center>');

$('#customertangrid').fadeOut("slow"); 
$('#invoicedetailsgrid').fadeIn("slow"); 

				}
			},
			 error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert(msg);
    }
		});


}