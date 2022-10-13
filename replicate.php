<?php
	include_once 'database.php';

	function translate_text($lg, $tr_text) {
       
      	$apiKey = 'AIzaSyCfFJ2pBRMVyg-sFc818iykH-l0xQuayiM';
      	$text = $tr_text;
      	$url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target='.$lg.'';

      	$handle = curl_init($url);
      	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
      	$response = curl_exec($handle);                 
      	$responseDecoded = json_decode($response, true);
      	curl_close($handle);

      	return $responseDecoded['data']['translations'][0]['translatedText'];      
   	}

   	$formid=$_POST['formid'];

	$lang=$_POST['lang'];

	$formname=$_POST['formname'];

	$projectnew=$_POST['project'];

	$sql="SELECT ide, toolbar_title, user_id, dbtable, description, projectid, isdeleted, project_id, formid, language FROM public.custom_form WHERE isdeleted=false and ide=$formid Order by ide";

	$getoldcustom=$conn->prepare($sql);

	$getoldcustom->execute();

	if ($getoldcustom->rowCount() > 0)
	{
	   	$form = $getoldcustom->fetch(PDO::FETCH_ASSOC);

	   	$table=strtolower(str_replace(" ","_",$formname));

		$table=strtolower(str_replace("-","_",$table));

		$table=strtolower(str_replace(".","_",$table));

		$table=strtolower(str_replace(",","_",$table));

		$table=strtolower(str_replace("?","",$table));

		$table=strtolower(str_replace("(","",$table));

		$table=strtolower(str_replace(")","",$table));

		$table=strtolower(str_replace("<","",$table));

		$table=strtolower(str_replace(">","",$table));

		$table=strtolower(str_replace("&","",$table));

		$table=strtolower(str_replace("/","",$table));

	   	$sql2="INSERT INTO public.custom_form (toolbar_title, dbtable, description, projectid, language) VALUES ('".$form['toolbar_title']."', '".$table."_".$lang."', '".$form['description']."', '".$projectnew."', '".$lang."') returning ide";

	   	$getinsertcustom=$conn->prepare($sql2);

		$getinsertcustom->execute();

		if ($getinsertcustom->rowCount() > 0)
		{
	   		$formnew = $getinsertcustom->fetch(PDO::FETCH_ASSOC);

	   		$id=$formnew['ide'];

	   		$sql3="SELECT id, compulsory, db_column, dep_question_id, dep_select_id, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, updated_at, questionnaire_id, section, category, isdisabled"
	   	}


	}
?>