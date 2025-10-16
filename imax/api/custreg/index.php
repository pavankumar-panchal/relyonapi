<?php
//error_reporting(E_ALL);

include('functions/phpfunctions.php');
$data = json_decode(file_get_contents("php://input"), true);
//var_dump($data);
//echo $custcard = $data['custCard'];
//echo $data['custCard'];
//echo $data->custCard;
//echo $data; 

$businessname = trim($data['custBusinessName']); 
$custaddress = $data['custAddress'];
$custplace = $data['custPlace']; 
$custstate = $data['custState'];
$district = $data['custDistrict'];
$pincode = $data['custPincode'];
$custname = $data['custName'];
$custphone = $data['custPhone'];
$custcellno = $data['custCellNo'];
$custemailid = $data['custEmailId'];
$custcard = $data['custCard'];
$txnId = $data['txnId'];
$responseTs = date('Y-m-d\TH:i:s.Z\Z', time());
//exit;

$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
$attacheddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');

 $query0 = 'select * from inv_dealercard where cardid = '.$custcard;
 //exit;
 $result0 =  runmysqlquery($query0);
 $count = mysql_num_rows($result0);
 if($count > 0)
 {
    $fetch0 = runmysqlqueryfetch($query0);
    $custref = $fetch0['customerreference'];
    if($custref == "")
    {
        $fetch0 = runmysqlqueryfetch($query0);
        $firstproduct = $fetch0['productcode'];
        $currentdealer = 2593;
        //
        $query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS newcustomerid FROM inv_mas_customer");
        $cusslno = $query['newcustomerid'];

        //Get new customer id
        $query1 = "select statecode,districtcode from inv_mas_district where districtname  = '".$district."';";
        $result1 = runmysqlquery($query1);
        $countname = mysql_num_rows($result1);
        if($countname > 0)
        {
            $fetch1 = runmysqlqueryfetch($query1);
            $statecode = $fetch1['statecode'];
            $districtcode = $fetch1['districtcode'];
            $newcustomerid = $statecode.$districtcode.$currentdealer.$firstproduct.$cusslno;
            $password = generatepwd();
            $custid = cusidcombine($newcustomerid);
            $lastslno = substr($newcustomerid,-5);

            $query2 = "Insert into inv_mas_customer(slno,customerid,businessname,address, place,pincode,district,region,category,type,stdcode,website,remarks,password,passwordchanged,disablelogin,createddate,createdby,corporateorder,fax,activecustomer,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,branch,companyclosed, promotionalsms,promotionalemail,gst_no,sez_enabled,currentdealer,firstdealer,firstproduct,initialpassword,loginpassword) values
            ('".$cusslno."','".$newcustomerid."','".$businessname."','".$custaddress."','".$custplace."','".$pincode."','".$districtcode."',1,'','','".$stdcode."','','','','N','no','".changedateformatwithtime($createddate)."','2','no','','yes','".date('Y-m-d').' '.date('H:i:s')."','','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."',1,'no','no','no','','','".$currentdealer."','".$currentdealer."','".$firstproduct."','".$password."',AES_ENCRYPT('".$password."','imaxpasswordkey'));";
            $result2 = runmysqlquery($query2); 

            $query5 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$cusslno."','general','".$custname."','".$custphone."','".$custcellno."','".$custemailid."');";
            $result5 = runmysqlquery($query5); 


            $query3 = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber,
             inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype from inv_dealercard
            left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
            left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno
            left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where inv_dealercard.cardid = '".$custcard."';";
            $fetch3 = runmysqlqueryfetch($query3);

            if($fetch3['usagetype'] == "singleuser")
                $usatype = "Single User";
            else
                $usatype = "Multi User";

            $description = '1$'.$fetch3['productname'].'$'.$fetch3['purchasetype'].'$'.$usatype.'$'.$fetch3['scratchnumber'].'$'.$fetch3['cardid'].'$0';

            // Updating in dealercard
            $query4 = "Update inv_dealercard set customerreference = '".$cusslno."' ,cuscardattacheddate = '".$attacheddate."' ,cuscardremarks = '".$remarks."' ,cuscardattachedby = '2', usertype = 'user' where cardid = '".$custcard."' ";
            $result4 = runmysqlquery($query4);

            //insert in inv_invoicenumbers_dummy_regv2
            $regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) values('".$currentdealer."','".$cusslno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$custcard."')";
            $eventresult = runmysqlquery($regvquery);

            //insert in log tables
            $remarks = "Emudra API#$custcard";
            $logquery ="INSERT INTO inv_logs_login(userid,`date`,`time`,`type`,system,device,browser) VALUES('".$lastslno."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','Emudra_API','".$_SERVER['REMOTE_ADDR']."','DESKTOP','".$_SERVER['HTTP_USER_AGENT']."')";
            $logresult = runmysqlquery($logquery);
            $eventquery = "Insert into inv_logs_event(userid,system,eventtype,remarks,eventdatetime) values('".$lastslno."','".$_SERVER['REMOTE_ADDR']."','259',
            '".$remarks."','".date('Y-m-d').' '.date('H:i:s')."')";
            $eventresult = runmysqlquery($eventquery);

            // echo $output = WriteError("Customer Created Successfully!",'17');
            // echo "<br>";
            // echo $output = WriteError("requestTxnId = \"$txnId\"",'21');
            //  echo "<br>";
            //  echo $output = WriteError("responseTs = \"$responseTs\"",'22');
            //  echo "<br>";
            // echo $output = WriteError("Cust Name = \"$businessname\"",'18');
            // echo "<br>";
            // echo $output = WriteError("Cust Id = \"$custid\"",'19');

            $registration['status'] = "1";
            $registration['errorMessage'] = "Customer Created Successfully.";
            $registration['errorCode'] = "20";
            $registration['requestTxnId'] = $txnId;
            $registration['responseTs'] = $responseTs;
            $registration['customerName'] = trim($businessname);
            $registration['customerId'] = $custid;
            echo json_encode($registration);   
        }
        else
        {
            $registration['errorMessage'] = "District name is not matching with Imax Data.";
            $registration['errorCode'] = "24";
            $registration['status'] = "0";
        }
        
    }
    else
    {
        $registration['errorMessage'] = "Customer is already attached to given card.";
        $registration['errorCode'] = "21";
        $registration['status'] = "0";
        echo json_encode($registration);
    }
}
else
{
    //echo $output = WriteError("Please enter the correct Card Id.",'20');
    $registration['errorMessage'] = "Please enter the correct Card Id.";
    $registration['errorCode'] = "22";
    $registration['status'] = "0";
    echo json_encode($registration);
}
