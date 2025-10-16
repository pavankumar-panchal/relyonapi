<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$response = array();
require "../functions/phpfunctions.php";

if (!empty($_REQUEST["Qa1iio9"]) && !empty($_REQUEST["AsWrIo"]) && !empty($_REQUEST["UasffU"]))
{
    $custId = decodevalue($_REQUEST['Qa1iio9']);
    $prdId = decodevalue($_REQUEST['AsWrIo']);
    $scratchNumber = decodevalue($_REQUEST['UasffU']);
    //$custId=$_REQUEST['Qa1iio9'];
    //$prdId=$_REQUEST['AsWrIo'];
    //$cardId=$_REQUEST['UasffU'];
    $query = "select * from inv_customerproduct where customerreference='" . $custId . "'";
    $stmt = $dbh->prepare($query);
    $resp = $stmt->execute();
    $rows_count = $stmt->rowCount($resp);
    if ($rows_count > 0)
    {
        $query1 = "select * from inv_customerproduct where computerid ='" . $prdId . "'";
        $stmt1 = $dbh->prepare($query1);
        $productId = $stmt1->execute();
        $prdId_count = $stmt1->rowCount($productId);
        if ($prdId_count > 0)
        {
            $arr = str_split($scratchNumber, 4);
            $scratchNumber = $arr[0] . "-" . $arr[1] . "-" . $arr[2];
            $query2 = "select * from inv_mas_scratchcard where scratchnumber ='" . $scratchNumber . "'";
            $stmt2 = $dbh->prepare($query2);
            $scratchId = $stmt2->execute();
            $scratchId_count = $stmt2->rowCount($scratchId);
            if ($scratchId_count > 0)
            {

                $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                $cardId = $result2['cardid'];

                $query3 = "select inv_customerproduct.date as regdate,inv_dealercard.cuscardattacheddate as attachdate from inv_customerproduct 
                left join `inv_dealercard` on `inv_customerproduct`.`cardid` = inv_dealercard.cardid where inv_customerproduct.customerreference='" . $custId . "' and inv_customerproduct.computerid ='" . $prdId . "' and inv_customerproduct.cardid = '" . $cardId . "'";
                $stmt3 = $dbh->prepare($query3);
                $cust_prd_pin = $stmt3->execute();
                $cust_prd_pin_count = $stmt3->rowCount($cust_prd_pin);
                if ($cust_prd_pin_count > 0)
                {
                    $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                    $regDate = $result3['date'];
                    $attachdate = date('Y-m-d',strtotime($result3['attachdate']));
                    $slno = $result3['slno'];

                    $query4 = "select * from inv_surrenderproduct where refslno='" . $slno . "' and REGDATE != 0";
                    $stmt4 = $dbh->prepare($query4);
          $reg_date = $stmt4->execute();
                    $reg_date_count = $stmt4->rowCount($reg_date);
                    if ($reg_date_count > 0)
                    {
                        $result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                        $regDate = $result4['REGDATE'];
                    }
                    $response['code'] = 200;
                    $response['message'] = "Successfull";
                    $response['REGDATE'] = $attachdate;
                    //$response['REGDATE'] = $regDate;
                }
                else
                {

                    $response['code'] = 400;
                    $response['message'] = "Product Code and Pin not matching for Customer Id";
                }
            }
            else
            {
                $response['code'] = 400;
                $response['message'] = "Scratch Number Not found";
            }
        }
        else
        {
            $response['code'] = 400;
            $response['message'] = "Product Code Not found";
        }
    }
    else
    {
        $response['code'] = 400;
        $response['message'] = "Customer Id Not found";
    }
}
else
{
    $response['code'] = 500;
    $response['message'] = "Parameters missing";
}
$res_json = json_encode($response);
$res = encodevalue($res_json);
//echo $res ;
echo decodevalue($res);
?>
