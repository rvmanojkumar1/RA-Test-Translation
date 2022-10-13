<?php
	// print_r(json_encode($data));
	include_once 'database.php';
	
	$data = json_decode(file_get_contents('php://input'), true);
	
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

	$projectid=$data['projectname'];

	$form_name=$data['survey'];

	$table=strtolower(str_replace(" ","_",$form_name));

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

	$description=$data['description'];

	$lang=$data['language'];

	// $lanarray=["bengali"];

	// for($zd=0; $zd<count($lanarray); $zd++){

	// 	$lang=$lanarray[$zd];

		if($lang=='english'){

			$selectsql="SELECT dbtable from public.custom_form WHERE dbtable='".$table."_en'";

			$checkdbtable=$conn->prepare($selectsql);

			$checkdbtable->execute();

			if($checkdbtable->rowCount()>0){
				$year = date("Y");

				$table=$table."_".substr($projectid, -4)."_".date("i_s")."";
			}

			$sql="INSERT INTO public.custom_form (toolbar_title, dbtable, description, projectid, language) VALUES ('".$form_name."', '".$table."_en', '".$description."', '".$projectid."', 'en') returning ide";

			$qid = $conn->prepare($sql);

		    $qid->execute();

		    if ($qid->rowCount() > 0)
		    {
		    	$quesid = $qid->fetch(PDO::FETCH_ASSOC);

		    	// print_r($data['multipleinput']);
		    	// echo "<br/>";
		    	// echo "<br/>";
		    	$quescount=count($data['multipleinput']);

		    	for($i=0;$i<$quescount;$i++){

		    		if(array_key_exists($i,$data['multipleinput'])){
		    			$question=$data['multipleinput'][$i]['question'];
					
						$questiontype=$data['multipleinput'][$i]['questiontype'];

						$section=$data['multipleinput'][$i]['section'];

						$category=$data['multipleinput'][$i]['category'];

						$order=$data['multipleinput'][$i]['order'];
						
						$db=strtolower(str_replace(" ","_",$question));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						$db=strtolower(str_replace("%","",$db));

						if(strlen($question)>28){
							$db=substr($question, 28);
						}
						$db=$db."_".rand(1,10000);

						$db=strtolower(str_replace(" ","_",$db));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						$db=strtolower(str_replace("%","",$db));

						$mandatory='false';

						if(array_key_exists('mandatory', $data['multipleinput'][$i])){
							$mandatory=$data['multipleinput'][$i]['mandatory'];
							if($mandatory=='on'){
								$mandatory='true';
							}
						}

						if($questiontype=='text'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".$question."','Text','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='number'){
							$maxrange=strlen($data['multipleinput'][$i]['answer']);

							$tovalidation=pow(10, $data['multipleinput'][$i]['answer'])-1;

							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','".$maxrange."','1','".$question."','Integer','".$tovalidation."',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							// echo $sql2;
							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='trueorfalse'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".$question."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	$opt=['Yes', 'No'];

						    	for($e=0;$e<count($opt);$e++){
						    		
						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$opt[$e]."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
						    }
						}

						if($questiontype=='multichoiceoneans'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".$question."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// print_r(['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "185";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r((intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo $k;
							    				// echo "<br>";
							    				// echo "<br>";

							    				if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

							    					$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						

						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													$casedb=strtolower(str_replace("/","",$casedb));

													$casedb=strtolower(str_replace("%","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													$casedb=strtolower(str_replace("/","",$casedb));

													$casedb=strtolower(str_replace("%","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".$casequestion."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".$casequestion."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".$casequestion."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".$caseopt[$w]."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".$casequestion."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".$casequestion."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}
													$q++;
							    				}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }
						}

						if($questiontype=='multichoicemultians'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".$question."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			// echo "<br>";
			    			// echo "<br>";
			    			// echo "287 ".$quid->rowCount();
			    			// echo "<br>";
			    			// echo "<br>";
			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "296";
						    	// echo "<br>";
						    	// print_r($data['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		// echo "<br>";
						    		// echo "304";
						    		// echo "<br>";
						    		// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    		// echo "<br>";
						    		// echo "<br>";
						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		// echo "<br>";
							    		// echo "<br>";
							    		// echo "313";
							    		// echo "<br>";
							    		// echo "<br>";
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					// echo "<br>";
						    			// echo "<br>";
						    			// echo "324 ".$sqloption->rowCount();
						    			// echo "<br>";
						    			// echo "<br>";

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "334 ".(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo "341 ".$k;
							    				// echo "<br>";
							    				// echo "<br>";
					    						if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

						    						$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						

						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													$casedb=strtolower(str_replace("/","",$casedb));

													$casedb=strtolower(str_replace("%","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													$casedb=strtolower(str_replace("/","",$casedb));

													$casedb=strtolower(str_replace("%","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".$casequestion."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".$casequestion."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".$casequestion."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".$caseopt[$w]."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".$casequestion."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".$casequestion."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													$q++;
												}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }	
						}
		    		}
		    		else{
		    			$quescount++;
		    		}
		    		
		    	}

		    	$addsql="SELECT db_column FROM newquestionnaire WHERE questionnaire_id='".$quesid['ide']."'";

		    	$adsql=$conn->prepare($addsql);

		    	$adsql->execute();

		    	if($adsql->rowCount()>0){
		    		
		    		$createtable="CREATE TABLE IF NOT EXISTS public.".$table."_en"." ( id bigserial, formid character varying, beneficiary_id character varying, ";
		    		while($row=$adsql->fetch(PDO::FETCH_ASSOC)){
		    			$createtable=$createtable." ".$row['db_column']." character varying,";
		    		}
		    		$createtable=$createtable."CONSTRAINT ".$table."_en_pkey PRIMARY KEY (id) ) TABLESPACE pg_default; ALTER TABLE IF EXISTS public.".$table."_en"." OWNER to postgres;";

		    		$conn->exec($createtable);

		    	}
		    }
		}
		else if($lang=='kannada'){

			$selectsql="SELECT dbtable from public.custom_form WHERE dbtable='".$table."_kn'";

			$checkdbtable=$conn->prepare($selectsql);

			$checkdbtable->execute();

			if($checkdbtable->rowCount()>0){
				$year = date("Y");

				$table=$table."_".substr($projectid, -4)."_".date("i_s")."";
			}

			$sql="INSERT INTO public.custom_form (toolbar_title, dbtable, description, projectid, language) VALUES ('".$form_name."', '".$table."_kn', '".$description."', '".$projectid."', 'kn') returning ide";

			$qid = $conn->prepare($sql);

		    $qid->execute();

		    if ($qid->rowCount() > 0)
		    {
		    	$quesid = $qid->fetch(PDO::FETCH_ASSOC);
				
				$quescount=count($data['multipleinput']);

		    	for($i=0;$i<$quescount;$i++){

		    		if(array_key_exists($i,$data['multipleinput'])){

			    		$question=$data['multipleinput'][$i]['question'];
						
						$questiontype=$data['multipleinput'][$i]['questiontype'];

						$section=$data['multipleinput'][$i]['section'];

						$category=$data['multipleinput'][$i]['category'];

						$order=$data['multipleinput'][$i]['order'];
						
						$db=strtolower(str_replace(" ","_",$question));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						if(strlen($question)>28){
							$db=substr($question, 28);
						}
						$db=$db."_".rand(1,10000);

						$db=strtolower(str_replace(" ","_",$db));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						$mandatory='false';

						if(array_key_exists('mandatory', $data['multipleinput'][$i])){
							$mandatory=$data['multipleinput'][$i]['mandatory'];
							if($mandatory=='on'){
								$mandatory='true';
							}
						}

						if($questiontype=='text'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('kn',$question)."','Text','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='number'){
							$maxrange=strlen($data['multipleinput'][$i]['answer']);

							$tovalidation=pow(10, $data['multipleinput'][$i]['answer'])-1;

							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','".$maxrange."','1','".translate_text('kn',$question)."','Integer','".$tovalidation."',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='trueorfalse'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('kn',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	$opt=['Yes', 'No'];

						    	for($e=0;$e<count($opt);$e++){
						    		
						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$opt[$e])."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
						    }
						}

						if($questiontype=='multichoiceoneans'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('kn',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// print_r(['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "185";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r((intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo $k;
							    				// echo "<br>";
							    				// echo "<br>";

							    				if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

							    					$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						

						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]))
													{
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('kn',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('kn',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('kn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('kn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('kn',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}
													$q++;
							    				}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }
						}

						if($questiontype=='multichoicemultians'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('kn',$question)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			// echo "<br>";
			    			// echo "<br>";
			    			// echo "287 ".$quid->rowCount();
			    			// echo "<br>";
			    			// echo "<br>";
			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "296";
						    	// echo "<br>";
						    	// print_r($data['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		// echo "<br>";
						    		// echo "304";
						    		// echo "<br>";
						    		// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    		// echo "<br>";
						    		// echo "<br>";
						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		// echo "<br>";
							    		// echo "<br>";
							    		// echo "313";
							    		// echo "<br>";
							    		// echo "<br>";
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					// echo "<br>";
						    			// echo "<br>";
						    			// echo "324 ".$sqloption->rowCount();
						    			// echo "<br>";
						    			// echo "<br>";

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "334 ".(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo "341 ".$k;
							    				// echo "<br>";
							    				// echo "<br>";
					    						if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

						    						$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						

						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('kn',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('kn',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('kn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('kn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('kn',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													$q++;
												}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('kn',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }	
						}
					}
		    		else{
		    			$quescount++;
		    		}
		    	}

		    	$addsql="SELECT db_column FROM newquestionnaire WHERE questionnaire_id='".$quesid['ide']."'";

		    	$adsql=$conn->prepare($addsql);

		    	$adsql->execute();

		    	if($adsql->rowCount()>0){
		    		$createtable="CREATE TABLE IF NOT EXISTS public.".$table."_kn"." ( id bigserial, formid character varying, beneficiary_id character varying, ";
		    		while($row=$adsql->fetch(PDO::FETCH_ASSOC)){
		    			$createtable=$createtable." ".$row['db_column']." character varying,";
		    		}
		    		$createtable=$createtable."CONSTRAINT ".$table."_kn"."_pkey PRIMARY KEY (id) ) TABLESPACE pg_default; ALTER TABLE IF EXISTS public.".$table."_kn"." OWNER to postgres;";

		    		
		    		$conn->exec($createtable);

		    	}
		    }
		}
		else if($lang=='bengali'){

			$selectsql="SELECT dbtable from public.custom_form WHERE dbtable='".$table."_bn'";

			$checkdbtable=$conn->prepare($selectsql);

			$checkdbtable->execute();

			if($checkdbtable->rowCount()>0){
				$year = date("Y");

				$table=$table."_".substr($projectid, -4)."_".date("i_s")."";
			}

			$sql="INSERT INTO public.custom_form (toolbar_title, dbtable, description, projectid, language) VALUES ('".$form_name."', '".$table."_bn', '".$description."', '".$projectid."', 'bn') returning ide";

			$qid = $conn->prepare($sql);

		    $qid->execute();

		    if ($qid->rowCount() > 0)
		    {
		    	$quesid = $qid->fetch(PDO::FETCH_ASSOC);
				
				$quescount=count($data['multipleinput']);

		    	for($i=0;$i<$quescount;$i++){

		    		if(array_key_exists($i,$data['multipleinput'])){

			    		$question=$data['multipleinput'][$i]['question'];
						
						$questiontype=$data['multipleinput'][$i]['questiontype'];

						$section=$data['multipleinput'][$i]['section'];

						$category=$data['multipleinput'][$i]['category'];

						$order=$data['multipleinput'][$i]['order'];
						
						$db=strtolower(str_replace(" ","_",$question));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						if(strlen($question)>28){
							$db=substr($question, 28);
						}
						$db=$db."_".rand(1,10000);

						$db=strtolower(str_replace(" ","_",$db));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						$mandatory='false';

						if(array_key_exists('mandatory', $data['multipleinput'][$i])){
							$mandatory=$data['multipleinput'][$i]['mandatory'];
							if($mandatory=='on'){
								$mandatory='true';
							}
						}

						if($questiontype=='text'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('bn',$question)."','Text','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='number'){
							$maxrange=strlen($data['multipleinput'][$i]['answer']);

							$tovalidation=pow(10, $data['multipleinput'][$i]['answer'])-1;

							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','".$maxrange."','1','".translate_text('bn',$question)."','Integer','".$tovalidation."',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='trueorfalse'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('bn',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	$opt=['Yes', 'No'];

						    	for($e=0;$e<count($opt);$e++){
						    		
						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$opt[$e])."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
						    }
						}

						if($questiontype=='multichoiceoneans'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('bn',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// print_r(['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "185";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r((intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo $k;
							    				// echo "<br>";
							    				// echo "<br>";

							    				if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

							    					$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						

						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]))
													{
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('bn',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('bn',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('bn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('bn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('bn',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}
													$q++;
							    				}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }
						}

						if($questiontype=='multichoicemultians'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('bn',$question)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			// echo "<br>";
			    			// echo "<br>";
			    			// echo "287 ".$quid->rowCount();
			    			// echo "<br>";
			    			// echo "<br>";
			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "296";
						    	// echo "<br>";
						    	// print_r($data['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		// echo "<br>";
						    		// echo "304";
						    		// echo "<br>";
						    		// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    		// echo "<br>";
						    		// echo "<br>";
						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		// echo "<br>";
							    		// echo "<br>";
							    		// echo "313";
							    		// echo "<br>";
							    		// echo "<br>";
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".$data['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					// echo "<br>";
						    			// echo "<br>";
						    			// echo "324 ".$sqloption->rowCount();
						    			// echo "<br>";
						    			// echo "<br>";

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "334 ".(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo "341 ".$k;
							    				// echo "<br>";
							    				// echo "<br>";
					    						if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

						    						$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						
						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('bn',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('bn',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('bn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('bn',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('bn',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													$q++;
												}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('bn',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }	
						}
					}
		    		else{
		    			$quescount++;
		    		}
		    	}

		    	$addsql="SELECT db_column FROM newquestionnaire WHERE questionnaire_id='".$quesid['ide']."'";

		    	$adsql=$conn->prepare($addsql);

		    	$adsql->execute();

		    	if($adsql->rowCount()>0){
		    		$createtable="CREATE TABLE IF NOT EXISTS public.".$table."_bn"." ( id bigserial, formid character varying, beneficiary_id character varying, ";
		    		while($row=$adsql->fetch(PDO::FETCH_ASSOC)){
		    			$createtable=$createtable." ".$row['db_column']." character varying,";
		    		}
		    		$createtable=$createtable."CONSTRAINT ".$table."_bn"."_pkey PRIMARY KEY (id) ) TABLESPACE pg_default; ALTER TABLE IF EXISTS public.".$table."_bn"." OWNER to postgres;";

		    		
		    		$conn->exec($createtable);

		    	}
		    }
		}
		else if($lang=='marathi'){

			$selectsql="SELECT dbtable from public.custom_form WHERE dbtable='".$table."_mr'";

			$checkdbtable=$conn->prepare($selectsql);

			$checkdbtable->execute();

			if($checkdbtable->rowCount()>0){
				$year = date("Y");

				$table=$table."_".substr($projectid, -4)."_".date("i_s")."";
			}

			$sql="INSERT INTO public.custom_form (toolbar_title, dbtable, description, projectid, language) VALUES ('".$form_name."', '".$table."_mr', '".$description."', '".$projectid."', 'mr') returning ide";

			$qid = $conn->prepare($sql);

		    $qid->execute();

		    if ($qid->rowCount() > 0)
		    {
		    	$quesid = $qid->fetch(PDO::FETCH_ASSOC);
				
				$quescount=count($data['multipleinput']);

		    	for($i=0;$i<$quescount;$i++){

		    		if(array_key_exists($i,$data['multipleinput'])){

			    		$question=$data['multipleinput'][$i]['question'];
						
						$questiontype=$data['multipleinput'][$i]['questiontype'];

						$section=$data['multipleinput'][$i]['section'];

						$category=$data['multipleinput'][$i]['category'];

						$order=$data['multipleinput'][$i]['order'];
						
						$db=strtolower(str_replace(" ","_",$question));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						if(strlen($question)>28){
							$db=substr($question, 28);
						}
						$db=$db."_".rand(1,10000);

						$db=strtolower(str_replace(" ","_",$db));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						$mandatory='false';

						if(array_key_exists('mandatory', $data['multipleinput'][$i])){
							$mandatory=$data['multipleinput'][$i]['mandatory'];
							if($mandatory=='on'){
								$mandatory='true';
							}
						}

						if($questiontype=='text'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('mr',$question)."','Text','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='number'){
							$maxrange=strlen($data['multipleinput'][$i]['answer']);

							$tovalidation=pow(10, $data['multipleinput'][$i]['answer'])-1;

							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','".$maxrange."','1','".translate_text('mr',$question)."','Integer','".$tovalidation."',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='trueorfalse'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('mr',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	$opt=['Yes', 'No'];

						    	for($e=0;$e<count($opt);$e++){
						    		
						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$opt[$e])."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
						    }
						}

						if($questiontype=='multichoiceoneans'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('mr',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// print_r(['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "185";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r((intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo $k;
							    				// echo "<br>";
							    				// echo "<br>";

							    				if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

							    					$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						

						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]))
													{
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('mr',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('mr',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('mr',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('mr',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('mr',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}
													$q++;
							    				}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }
						}

						if($questiontype=='multichoicemultians'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('mr',$question)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			// echo "<br>";
			    			// echo "<br>";
			    			// echo "287 ".$quid->rowCount();
			    			// echo "<br>";
			    			// echo "<br>";
			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "296";
						    	// echo "<br>";
						    	// print_r($data['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		// echo "<br>";
						    		// echo "304";
						    		// echo "<br>";
						    		// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    		// echo "<br>";
						    		// echo "<br>";
						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		// echo "<br>";
							    		// echo "<br>";
							    		// echo "313";
							    		// echo "<br>";
							    		// echo "<br>";
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					// echo "<br>";
						    			// echo "<br>";
						    			// echo "324 ".$sqloption->rowCount();
						    			// echo "<br>";
						    			// echo "<br>";

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "334 ".(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo "341 ".$k;
							    				// echo "<br>";
							    				// echo "<br>";
					    						if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

						    						$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						
						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('mr',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('mr',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('mr',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('mr',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('mr',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													$q++;
												}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }	
						}
					}
		    		else{
		    			$quescount++;
		    		}
		    	}

		    	$addsql="SELECT db_column FROM newquestionnaire WHERE questionnaire_id='".$quesid['ide']."'";

		    	$adsql=$conn->prepare($addsql);

		    	$adsql->execute();

		    	if($adsql->rowCount()>0){
		    		$createtable="CREATE TABLE IF NOT EXISTS public.".$table."_mr"." ( id bigserial, formid character varying, beneficiary_id character varying, ";
		    		while($row=$adsql->fetch(PDO::FETCH_ASSOC)){
		    			$createtable=$createtable." ".$row['db_column']." character varying,";
		    		}
		    		$createtable=$createtable."CONSTRAINT ".$table."_mr"."_pkey PRIMARY KEY (id) ) TABLESPACE pg_default; ALTER TABLE IF EXISTS public.".$table."_mr"." OWNER to postgres;";

		    		
		    		$conn->exec($createtable);

		    	}
		    }
		}

		else if($lang=='hindi'){

			$selectsql="SELECT dbtable from public.custom_form WHERE dbtable='".$table."_hi'";

			$checkdbtable=$conn->prepare($selectsql);

			$checkdbtable->execute();

			if($checkdbtable->rowCount()>0){
				$year = date("Y");

				$table=$table."_".substr($projectid, -4)."_".date("i_s")."";
			}

			$sql="INSERT INTO public.custom_form (toolbar_title, dbtable, description, projectid, language) VALUES ('".$form_name."', '".$table."_hi', '".$description."', '".$projectid."', 'hi') returning ide";

			$qid = $conn->prepare($sql);

		    $qid->execute();

		    if ($qid->rowCount() > 0)
		    {
		    	$quesid = $qid->fetch(PDO::FETCH_ASSOC);
				
				$quescount=count($data['multipleinput']);

		    	for($i=0;$i<$quescount;$i++){

		    		if(array_key_exists($i,$data['multipleinput'])){

			    		$question=$data['multipleinput'][$i]['question'];
						
						$questiontype=$data['multipleinput'][$i]['questiontype'];

						$section=$data['multipleinput'][$i]['section'];

						$category=$data['multipleinput'][$i]['category'];

						$order=$data['multipleinput'][$i]['order'];
						
						$db=strtolower(str_replace(" ","_",$question));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						if(strlen($question)>28){
							$db=substr($question, 28);
						}
						$db=$db."_".rand(1,10000);

						$db=strtolower(str_replace(" ","_",$db));

						$db=strtolower(str_replace("-","_",$db));

						$db=strtolower(str_replace(".","_",$db));

						$db=strtolower(str_replace(",","_",$db));

						$db=strtolower(str_replace("?","",$db));

						$db=strtolower(str_replace("(","",$db));

						$db=strtolower(str_replace(")","",$db));

						$db=strtolower(str_replace("<","",$db));

						$db=strtolower(str_replace(">","",$db));

						$db=strtolower(str_replace("&","",$db));

						$db=strtolower(str_replace("/","",$db));

						$mandatory='false';

						if(array_key_exists('mandatory', $data['multipleinput'][$i])){
							$mandatory=$data['multipleinput'][$i]['mandatory'];
							if($mandatory=='on'){
								$mandatory='true';
							}
						}

						if($questiontype=='text'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('hi',$question)."','Text','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='number'){
							$maxrange=strlen($data['multipleinput'][$i]['answer']);

							$tovalidation=pow(10, $data['multipleinput'][$i]['answer'])-1;

							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','".$maxrange."','1','".translate_text('hi',$question)."','Integer','".$tovalidation."',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();
						}

						if($questiontype=='trueorfalse'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".translate_text('hi',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	$opt=['Yes', 'No'];

						    	for($e=0;$e<count($opt);$e++){
						    		
						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$opt[$e])."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
						    }
						}

						if($questiontype=='multichoiceoneans'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('hi',$question)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// print_r(['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "185";
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r((intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo $k;
							    				// echo "<br>";
							    				// echo "<br>";

							    				if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

							    					$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						

						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]))
													{
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('hi',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('hi',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('hi',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('hi',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('hi',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}
													$q++;
							    				}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }
						}

						if($questiontype=='multichoicemultians'){
							$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','-1','-1','-1','".translate_text('hi',$question)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."') Returning id";

							$quid = $conn->prepare($sql2);

			    			$quid->execute();

			    			// echo "<br>";
			    			// echo "<br>";
			    			// echo "287 ".$quid->rowCount();
			    			// echo "<br>";
			    			// echo "<br>";
			    			if ($quid->rowCount() > 0)
						    {
						    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

						    	// echo "<br>";
						    	// echo "<br>";
						    	// echo "296";
						    	// echo "<br>";
						    	// print_r($data['multipleinput'][$i]['answer']);
						    	// echo "<br>";
						    	// echo "<br>";
						    	for($j=0;$j<count($data['multipleinput'][$i]['answer']);$j++){

						    		// echo "<br>";
						    		// echo "304";
						    		// echo "<br>";
						    		// print_r($data['multipleinput'][$i]['answer']['option'.$j]);
						    		// echo "<br>";
						    		// echo "<br>";
						    		if(array_key_exists('case'.$j,$data['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		// echo "<br>";
							    		// echo "<br>";
							    		// echo "313";
							    		// echo "<br>";
							    		// echo "<br>";
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."') returning id";

							    		$sqloption = $conn->prepare($sqloptionquery);

				    					$sqloption->execute();

				    					// echo "<br>";
						    			// echo "<br>";
						    			// echo "324 ".$sqloption->rowCount();
						    			// echo "<br>";
						    			// echo "<br>";

				    					if ($sqloption->rowCount() > 0)
						    			{
						    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

						    				// echo "<br>";
						    				// echo "<br>";
						    				// echo "334 ".(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo "341 ".$k;
							    				// echo "<br>";
							    				// echo "<br>";
					    						if(array_key_exists($k,$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

						    						$casequestion=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						
						    						$casequestiontype=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

						    						$casemandatory='false';

						    						
						    						$casedb=strtolower(str_replace(" ","_",$casequestion));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(strlen($casequestion)>28){
														$casedb=substr($casequestion, 20);
													}

													$casedb=$casedb."_".rand(1,10000);

													$casedb=strtolower(str_replace(" ","_",$casedb));

						    						$casedb=strtolower(str_replace("-","_",$casedb));

													$casedb=strtolower(str_replace(".","_",$casedb));

													$casedb=strtolower(str_replace(",","_",$casedb));

													$casedb=strtolower(str_replace("?","",$casedb));

													$casedb=strtolower(str_replace("(","",$casedb));

													$casedb=strtolower(str_replace(")","",$casedb));

													$casedb=strtolower(str_replace("<","",$casedb));

													$casedb=strtolower(str_replace(">","",$casedb));

													$casedb=strtolower(str_replace("&","",$casedb));

													if(array_key_exists('mandatory', $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
														if($casemandatory=='on'){
															$casemandatory='true';
														}
													}

													if($casequestiontype=='text'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('hi',$casequestion)."','Text','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','".$casemaxrange."','1','".translate_text('hi',$casequestion)."','Integer','".$casetovalidation."',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."')";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id,dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','1','255','1','".translate_text('hi',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('mr',$caseopt[$w])."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('hi',$casequestion)."','Single Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	// print_r($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, dep_question_id, dep_select_id, section, category, \"order\") VALUES ('".$casemandatory."', '".$casedb."','-1','-1','-1','".translate_text('hi',$casequestion)."','Multi Select','-1',NOW(), '".$quesid['ide']."', '".$questionid['id']."', '".$qcoption['id']."', '".$casesection."', '".$casecategory."', '".$caseorder."') Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	for($m=0;$m<count($data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m])."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													$q++;
												}
							    				else{
							    					if($numbcheck==$q){
							    						break;
							    					}
							    					else{
							    						$numb++;
							    					}
							    				}
					    					}
				    					}
							    	}
							    	else{

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".translate_text('hi',$data['multipleinput'][$i]['answer']['option'.$j][$j])."', '".$questionid['id']."')";

							    		$qsql3 = $conn->prepare($sql3);

				    					$qsql3->execute();
							    	}
						    		
								}
						    }	
						}
					}
		    		else{
		    			$quescount++;
		    		}
		    	}

		    	$addsql="SELECT db_column FROM newquestionnaire WHERE questionnaire_id='".$quesid['ide']."'";

		    	$adsql=$conn->prepare($addsql);

		    	$adsql->execute();

		    	if($adsql->rowCount()>0){
		    		$createtable="CREATE TABLE IF NOT EXISTS public.".$table."_hi"." ( id bigserial, formid character varying, beneficiary_id character varying, ";
		    		while($row=$adsql->fetch(PDO::FETCH_ASSOC)){
		    			$createtable=$createtable." ".$row['db_column']." character varying,";
		    		}
		    		$createtable=$createtable."CONSTRAINT ".$table."_hi"."_pkey PRIMARY KEY (id) ) TABLESPACE pg_default; ALTER TABLE IF EXISTS public.".$table."_hi"." OWNER to postgres;";

		    		
		    		$conn->exec($createtable);

		    	}
		    }
		}
	// }
	header("location:home.php");
	
?>