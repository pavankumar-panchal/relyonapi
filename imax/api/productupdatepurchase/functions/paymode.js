// JavaScript Document
function formsubmit()
{
	var form = document.submitexistform;	
	var mode1=document.submitexistform.paymode[0].checked;
	var mode2=document.submitexistform.paymode[1].checked;
	var res=document.getElementById('lslnop').value;
	//alert(lslnop);

	var mode='';
	if(mode1==true && mode2==false)
	{
		//mode="credit";		
		form.action = 'makepayment/pay.php';
		form.submit();
	}
	else if(mode1==false && mode2==true)
	{
		//mode="internet";
		form.action = 'makepayment/paycitrus.php';
		form.submit();
	}
	else
	{
		alert("Select mode of payment");
		document.getElementById("paymode").focus();
	}
}