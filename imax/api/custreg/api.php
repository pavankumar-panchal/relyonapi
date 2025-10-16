<?php
//error_reporting(E_ALL);
$erroroutput = "";

include('functions/phpfunctions.php');
$data = json_decode(file_get_contents("php://input"), true);
$custBusinessName = $data['custBusinessName'];
$custbusleng = strlen($custBusinessName);
$custAddress = $data['custAddress'];
$custPlace= $data['custPlace'];
$custState = $data['custState'];
$custDistrict = $data['custDistrict'];
$custPincode = $data['custPincode'];
$custName = $data['custName'];
$custPhone = $data['custPhone'];
$custCellNo = $data['custCellNo'];
$custEmailId = $data['custEmailId'];
$custCard = $data['custCard'];

//if($custBusinessName == "" || $custAddress == "" || $custPlace == "" || $custState == "" || $custDistrict == "" || $custPincode == "" || $custCellNo == "" || $custEmailId == "" || $custCard == "")

if($custBusinessName == "")
{
    //echo $output = WriteError('Please provide the Businessname.','1');
    $errmessage = "Please provide the Businessname.";
    $errcode = "2";
    validateMsgCode($errmessage,$errcode);
}
if($custbusleng >= 100)
{
    //echo $output = WriteError('Please provide the Businessname not more than 100 chracters.','19');
    $message = "Please provide the Businessname not more than 100 chracters.";
    $code = "3";
    validateMsgCode($errmessage,$errcode);
    
}
if($custAddress == "")
{
	//echo "hi";
    //echo $output = WriteError('Please provide the Address.','2');
    $errmessage = "Please provide the Address.";
    $errcode = "4";
    validateMsgCode($errmessage,$errcode);
}

if($custPlace == "")
{
    //echo $output = WriteError('Please provide the Place.','3');
    $errmessage = "Please provide the Place.";
    $errcode = "5";
    validateMsgCode($errmessage,$errcode);
}

if($custState == "")
{
    //echo $output = WriteError('Please provide the State.','4');
    $errmessage = "Please provide the State.";
    $errcode = "6";
    validateMsgCode($errmessage,$errcode);
}

if($custState!= "")
{
    $querystate = "select count(*) as statecount from inv_mas_state where statename  = '".trim($custState)."'";
    $fetchstate = runmysqlqueryfetch($querystate);
    $statecount = $fetchstate['statecount'];
    if($statecount == 0)
    {
        //echo $output = WriteError('State name is not matching with Imax Data.','18');
        $errmessage = "State name is not matching with Imax Data.";
        $errcode = "7";
        validateMsgCode($errmessage,$errcode);
    }
}

if($custDistrict == "")
{
    //echo $output = WriteError('Please provide the District.','5');
    $errmessage = "Please provide the District.";
    $errcode = "8";
    validateMsgCode($errmessage,$errcode);
}


if($custDistrict!= "")
{
    $query = "select count(*) as districtcount from inv_mas_district where districtname  = '".trim($custDistrict)."'";
    $fetch = runmysqlqueryfetch($query);
    $district = $fetch['districtcount'];
    if($district == 0)
    {
        //echo $output = WriteError('District name is not matching with Imax Data.','12');
        $errmessage = "District name is not matching with Imax Data.";
        $errcode = "9";
        validateMsgCode($errmessage,$errcode);
    }
}

if($custPincode == "")
{
    //echo $output = WriteError('Please provide the Pincode.','6');
    $errmessage = "Please provide the Pincode.";
    $errcode = "10";
    validateMsgCode($errmessage,$errcode);
}

if($custName == "")
{
    //echo $output = WriteError('Please provide the Customer contact name.','7');
    $errmessage = "Please provide the Customer contact name.";
    $errcode = "11";
    validateMsgCode($errmessage,$errcode);
}

if($custPhone == "")
{

    //echo $output = WriteError('Please provide the Landline No.','8');
    $errmessage = "Please provide the Landline No.";
    $errcode = "12";
    validateMsgCode($errmessage,$errcode);
}
if($custPhone!= "")
{
    $validatephones = validatephones($custPhone);
    if(!$validatephones)
    {
        //echo $output = WriteError('Please provide proper Landline No.','13');
        $errmessage = "Please provide proper Landline No.";
        $errcode = "13";
        validateMsgCode($errmessage,$errcode);
    }
}

if($data['custCellNo'] == "")
{
    //echo $output = WriteError('Please provide the Cell No.','9');
    $errmessage = "Please provide the Cell No.";
    $errcode = "14";
    validateMsgCode($errmessage,$errcode);
}

if($custCellNo!= "")
{
    $validatecellno = cellvalidation($custCellNo);
    if(!$validatecellno)
    {
        //echo $output = WriteError('Please provide proper Cell No.','14');
        $errmessage = "Please provide proper Cell No.";
        $errcode = "15";
        validateMsgCode($errmessage,$errcode);
    }
}

if($custEmailId == "")
{
    //echo $output = WriteError('Please provide the Email Id.','10');
    $errmessage = "Please provide the Email Id.";
    $errcode = "16";
    validateMsgCode($errmessage,$errcode);
}

if($custEmailId!= "")
{
    $emailid = checkemail($custEmailId);
    if(!$emailid)
    {
        //echo $output = WriteError('Please provide proper Email Id.','15');
        $errmessage = "Please provide proper Email Id.";
        $errcode = "17";
        validateMsgCode($errmessage,$errcode);
    }
}

if($custCard == "")
{
    //echo $output = WriteError('Please provide the Cardid.','11');
    $errmessage = "Please provide the Cardid.";
    $errcode = "18";
    validateMsgCode($errmessage,$errcode);
}

if($custCard!= "")
{
    $query1 = "select customerreference from inv_dealercard where cardid = ".trim($custCard);
    $result1 =  runmysqlquery($query1);
    $count = mysql_num_rows($result1);
    if($count > 0)
    {
        $fetch1 = runmysqlqueryfetch($query1);
        $custref = $fetch1['customerreference'];
        if($custref!= "")
        {
            //echo $output = WriteError('Card is already attached to customer.','16');
            $errmessage = "Card is already attached to customer.";
            $errcode = "1";
            validateMsgCode($errmessage,$errcode);
        }
        else
        {
            validateMsgCode($errmessage="",$errcode="");
        }
    }
    else
    {
        $errmessage = "Given Card Id is not correct.";
        $errcode = "23";
        validateMsgCode($errmessage,$errcode);
    }

}
function validateMsgCode($errmessage,$errcode)
{
    $errmessage == "" ? $status = "1" : $status = "0";

    $registration['errorMessage'] = $errmessage;
    $registration['errorCode'] = $errcode;
    $registration['status'] = $status;
    echo json_encode($registration);
    exit;
}

function validatephones($validatephones)
{
    //return preg_match('/^[0-9]{10}+$/', $mobile);
    return preg_match('/^[^9]\d{5,7}(?:(?:([,][\s]|[;][\s]|[,;])[^9]\d{5,7}))*$/i', $validatephones);
}

function cellvalidation($validatecellno)
{
    return preg_match('/^[6|7|8|9]+[0-9]{9,9}$/i', $validatecellno);
}

function checkemail($emailid)
{
    return preg_match('/^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,10}$/i', $emailid);
}



