<?php

include "database.php";

$request = 0;

if(isset($_POST['request'])){
   $request = $_POST['request'];
}

// Fetch survey list by project id
if($request == 1){
   $pr_id = $_POST['pr_id'];

   $stmt = $conn->prepare("SELECT * FROM custom_form WHERE projectid=:projectid");
   $stmt->bindValue(':projectid', $pr_id, PDO::PARAM_STR);

   $stmt->execute();
   $surveyList = $stmt->fetchAll();

   $response = array();
   foreach($surveyList as $survey){
      $response[] = array(
        "id" => $survey['projectid'],
        "name" => $survey['toolbar_title'],
        "formid" => $survey['formid']
      );
   }

   echo json_encode($response);
   exit;
}
