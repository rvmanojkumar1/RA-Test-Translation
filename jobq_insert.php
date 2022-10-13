<?php
include "database.php";
$request = 0;

if(isset($_POST['request'])){
   $request = $_POST['request'];
}

if($request == 2){
    $prid = $_POST['pr_id'];
    $frid = $_POST['formid'];
    $stmtsel = $conn->prepare("SELECT * FROM custom_form WHERE ide= '".$frid."' ");
    $stmtsel->execute();
    $rows = $stmtsel->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("INSERT INTO jobq_translation (project_id, form_id, converted_language) VALUES ('".$rows['projectid']."', '".$rows['formid']."', '".$rows['language']."') ");
    // $stmt->bindValue(':projectid', $pr_id, PDO::PARAM_STR);

    $stmt->execute();
    
    // echo $prid;
    // echo $frid;
    // $pr_id = $_POST['pr_id']
    $response = "Data Insert Success";
    echo json_encode($response);
    exit;
}
?>