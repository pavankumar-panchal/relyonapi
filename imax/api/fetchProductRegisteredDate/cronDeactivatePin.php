<?php
  $servername = "66.228.55.243";
  $username = "manju";
  $password = "cd4017";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=relyon_imax", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "select S.slno,S.refslno,S.REGDATE,P.slno,P.customerreference,P.cardid,P.computerid from inv_surrenderproduct S left join inv_customerproduct P on S.refslno = P.slno where S.REGDATE != 0 and S.REGDATE < DATE_SUB(NOW(),INTERVAL 1 YEAR) group by S.refslno order by S.slno desc";
    $stmt = $conn->prepare($query);
    $resFetch = $stmt->execute();
    if($resFetch){
      $rowCounts = $stmt->rowCount();
      if ($rowCounts > 0){
        $result = $stmt->fetchAll();
        foreach ($$result as $row) {
          $cardId = $row['cardid'];
          $query1= "Update inv_mas_scratchcard set blocked= 'yes' , remarks = '1 year completed - updated by cron job' where cardid = '".$cardId."' ";
          $stmt = $conn->prepare($query1);
          // $resUpdate = $stmt->execute();
        }
      }
    }

  }
  catch(PDOException $e)
    {
    echo $e->getMessage();
    }


?>
