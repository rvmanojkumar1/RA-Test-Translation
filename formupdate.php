<?php
	
	// print_r($_POST);

	include_once 'database.php';
	
	$projectid=$_POST['projectname'];

	$form_name=$_POST['survey'];

	$dbtable=$_POST['dbtable'];

	$description=$_POST['description'];

	$sql="UPDATE public.custom_form  SET toolbar_title='".$form_name."', description='".$description."', projectid='".$projectid."' WHERE dbtable='".$dbtable."' Returning ide";

	$qid = $conn->prepare($sql);

    $qid->execute();

    // $t=0;

    if ($qid->rowCount() > 0)
    {
    	$quesid = $qid->fetch(PDO::FETCH_ASSOC);

    	$quescount=count($_POST['multipleinput']);

    	for($i=0;$i<$quescount;$i++){

    		if(array_key_exists($i,$_POST['multipleinput'])){

	    		$question=$_POST['multipleinput'][$i]['question'];
				
				$questiontype=$_POST['multipleinput'][$i]['questiontype'];

				$section=$_POST['multipleinput'][$i]['section'];

				$category=$_POST['multipleinput'][$i]['category'];

				$db=$_POST['multipleinput'][$i]['dbcolumn'];

				$order=$_POST['multipleinput'][$i]['order'];

				$mandatory='false';

				if(array_key_exists('mandatory', $_POST['multipleinput'][$i])){
					$mandatory=$_POST['multipleinput'][$i]['mandatory'];
					if($mandatory=='on'){
						$mandatory='true';
					}
				}

				if(strlen($db)>0){

					if($questiontype=='text'){
						$sql2="UPDATE public.newquestionnaire SET compulsory='".$mandatory."', question_name='".$question."', question_type='Text', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', section='".$section."', category='".$category."', \"order\"='".$order."' WHERE db_column='".$db."'";

						$quid = $conn->prepare($sql2);

		    			$quid->execute();

					}

					if($questiontype=='number'){
						$maxrange=strlen($_POST['multipleinput'][$i]['answer']);

						$tovalidation=pow(10,$_POST['multipleinput'][$i]['answer'])-1;

						$sql2="UPDATE public.newquestionnaire SET compulsory='".$mandatory."', max_range='".$maxrange."', question_name='".$question."', to_validation='".$tovalidation."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', section='".$section."', category='".$category."' WHERE db_column='".$db."'";

						$quid = $conn->prepare($sql2);

		    			$quid->execute();

					}

					if($questiontype=='trueorfalse'){
						$sql2="UPDATE public.newquestionnaire SET compulsory='".$mandatory."', question_name='".$question."', questionnaire_id='".$quesid['ide']."', section='".$section."', category='".$category."', updated_at=NOW(), \"order\"='".$order."' WHERE db_column='".$db."' Returning id";

						$quid = $conn->prepare($sql2);

		    			$quid->execute();

		    			if ($quid->rowCount() > 0)
					    {
					    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

					    	$opt=['Yes', 'No'];

					    	$sqldelete="DELETE FROM public.options WHERE question_id='".$questionid['id']."'";

					    	$sqldel = $conn->prepare($sqldelete);

		    				$sqldel->execute();

					    	for($e=0;$e<count($opt);$e++){
					    		
					    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$opt[$e]."', '".$questionid['id']."')";

					    		$qsql3 = $conn->prepare($sql3);

		    					$qsql3->execute();
					    	}
					    }
					}
					if($questiontype=='multichoiceoneans'){
						$sql2="UPDATE public.newquestionnaire SET compulsory='".$mandatory."', updated_at=NOW(), question_name='".$question."',  questionnaire_id='".$quesid['ide']."', section='".$section."', category='".$category."', \"order\"='".$order."' WHERE db_column='".$db."' Returning id";

						$quid = $conn->prepare($sql2);

		    			$quid->execute();

		    			if ($quid->rowCount() > 0)
					    {
					    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

					    	$sqldelete="DELETE FROM public.options WHERE question_id='".$questionid['id']."'";

					    	$sqldel = $conn->prepare($sqldelete);

		    				$sqldel->execute();

					    	for($j=0;$j<count($_POST['multipleinput'][$i]['answer']);$j++){

					    		if(array_key_exists('case'.$j,$_POST['multipleinput'][$i]['answer']['option'.$j]))
						    	{
						    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."') returning id";

						    		$sqloption = $conn->prepare($sqloptionquery);

			    					$sqloption->execute();

			    					if ($sqloption->rowCount() > 0)
					    			{
					    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

					    				$numb=(intval(key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

					    				$numbcheck=count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

					    				$q=0;
				    					for($k=key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

						    				if(array_key_exists($k,$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

						    					$casequestion=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
					    						// echo $casequestion;
					    						$casequestiontype=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

					    						$casesection=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

					    						$casecategory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

					    						$caseorder=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

					    						$casemandatory='false';

					    						// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])
					    						$casedb=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['dbcolumn'];

					    						if(array_key_exists('mandatory', $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
													$casemandatory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
													if($casemandatory=='on'){
														$casemandatory='true';
													}
												}

					    						if(strlen($casedb)>0){

													if($casequestiontype=='text'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."'";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', max_range='".$casemaxrange."', question_name='".$casequestion."', to_validation='".$casetovalidation."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."'";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."',dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."' Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	$sqldelete="DELETE FROM public.options WHERE question_id='".$casequestionid['id']."'";

													    	$sqldel = $conn->prepare($sqldelete);

										    				$sqldel->execute();

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".$caseopt[$w]."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."' returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$sqldelete="DELETE FROM public.options WHERE question_id='".$casequestionid['id']."'";

													    	$sqldel = $conn->prepare($sqldelete);

										    				$sqldel->execute();
													    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."' returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$sqldelete="DELETE FROM public.options WHERE question_id='".$casequestionid['id']."'";

													    	$sqldel = $conn->prepare($sqldelete);

										    				$sqldel->execute();
													    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}
					    						}
					    						else{
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

													if(array_key_exists('mandatory', $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
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

														$casemaxrange=strlen($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

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

													    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

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

													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
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

						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
							}
					    }
					}
					if($questiontype=='multichoicemultians'){
						$sql2="UPDATE public.newquestionnaire SET compulsory='".$mandatory."', updated_at=NOW(), question_name='".$question."',  questionnaire_id='".$quesid['ide']."', section='".$section."', category='".$category."', \"order\"='".$order."' WHERE db_column='".$db."' Returning id";

						$quid = $conn->prepare($sql2);

		    			$quid->execute();

		    			if ($quid->rowCount() > 0)
					    {
					    	$questionid = $quid->fetch(PDO::FETCH_ASSOC);

					    	$sqldelete="DELETE FROM public.options WHERE question_id='".$questionid['id']."'";

					    	$sqldel = $conn->prepare($sqldelete);

		    				$sqldel->execute();

					    	for($j=0;$j<count($_POST['multipleinput'][$i]['answer']);$j++){

					    		if(array_key_exists('case'.$j,$_POST['multipleinput'][$i]['answer']['option'.$j]))
						    	{
						    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."') returning id";

						    		$sqloption = $conn->prepare($sqloptionquery);

			    					$sqloption->execute();

			    					if ($sqloption->rowCount() > 0)
					    			{
					    				$qcoption = $sqloption->fetch(PDO::FETCH_ASSOC);

					    				$numb=(intval(key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

					    				$numbcheck=count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

					    				$q=0;
				    					for($k=key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

						    				if(array_key_exists($k,$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

						    					$casequestion=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
					    						// echo $casequestion;
					    						$casequestiontype=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

					    						$casesection=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

					    						$casecategory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

					    						$caseorder=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

					    						$casemandatory='false';

					    						// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])
					    						$casedb=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['dbcolumn'];

					    						if(array_key_exists('mandatory', $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
													$casemandatory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
													if($casemandatory=='on'){
														$casemandatory='true';
													}
												}

					    						if(strlen($casedb)>0){

													if($casequestiontype=='text'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."'";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='number'){

														$casemaxrange=strlen($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', max_range='".$casemaxrange."', question_name='".$casequestion."', to_validation='".$casetovalidation."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."'";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

													}

													if($casequestiontype=='trueorfalse'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."',dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."' Returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$caseopt=['Yes', 'No'];

													    	$sqldelete="DELETE FROM public.options WHERE question_id='".$casequestionid['id']."'";

													    	$sqldel = $conn->prepare($sqldelete);

										    				$sqldel->execute();

													    	for($w=0;$w<count($caseopt);$w++){
													    		
													    		$csql3="INSERT INTO public.options (title, question_id) VALUES ('".$caseopt[$w]."', '".$casequestionid['id']."')";

													    		$casesql = $conn->prepare($csql3);

										    					$casesql->execute();
													    	}
													    }
													}

													if($casequestiontype=='multichoiceoneans'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."' returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$sqldelete="DELETE FROM public.options WHERE question_id='".$casequestionid['id']."'";

													    	$sqldel = $conn->prepare($sqldelete);

										    				$sqldel->execute();
													    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}

													if($casequestiontype=='multichoicemultians'){
														$sql2="UPDATE public.newquestionnaire SET compulsory='".$casemandatory."', question_name='".$casequestion."', updated_at=NOW(), questionnaire_id='".$quesid['ide']."', dep_question_id='".$questionid['id']."', dep_select_id='".$qcoption['id']."', section='".$casesection."', category='".$casecategory."', \"order\"='".$caseorder."' WHERE db_column='".$casedb."' returning id";

														$quid = $conn->prepare($sql2);

										    			$quid->execute();

										    			if ($quid->rowCount() > 0)
													    {
													    	$casequestionid = $quid->fetch(PDO::FETCH_ASSOC);

													    	$sqldelete="DELETE FROM public.options WHERE question_id='".$casequestionid['id']."'";

													    	$sqldel = $conn->prepare($sqldelete);

										    				$sqldel->execute();
													    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
													    }
														
													}
					    						}
					    						else{
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

													if(array_key_exists('mandatory', $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
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

														$casemaxrange=strlen($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10, $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

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

													    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

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

													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

													    		$qsql3 = $conn->prepare($sql3);

										    					$qsql3->execute();
															}
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

						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
							}
					    }
					}
				}
				else{

					if($questiontype=='text'){
						$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','255','1','".$question."','Text','-1',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

						$quid = $conn->prepare($sql2);

		    			$quid->execute();

					}

					if($questiontype=='number'){
						$maxrange=strlen($_POST['multipleinput'][$i]['answer']);

						$tovalidation=pow(10,$_POST['multipleinput'][$i]['answer'])-1;

						$sql2="INSERT INTO public.newquestionnaire (compulsory, db_column, from_validation, max_range, min_range, question_name, question_type, to_validation, created_at, questionnaire_id, section, category, \"order\") VALUES ('".$mandatory."', '".$db."','1','".$maxrange."','1','".$question."','Integer','".$tovalidation."',NOW(), '".$quesid['ide']."', '".$section."', '".$category."', '".$order."')";

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
						    	for($j=0;$j<count($_POST['multipleinput'][$i]['answer']);$j++){

						    		if(array_key_exists('case'.$j,$_POST['multipleinput'][$i]['answer']['option'.$j]))
							    	{
							    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."') returning id";

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
						    				// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]);
						    				// echo "<br>";
						    				// echo "<br>";
						    				// print_r((intval(key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))));
						    				// echo "<br>";
						    				// echo "<br>";
						    				$numb=(intval(key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

						    				$numbcheck=count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

						    				$q=0;
					    					for($k=key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

					    						// echo "<br>";
							    				// echo "<br>";
							    				// echo $k;
							    				// echo "<br>";
							    				// echo "<br>";

							    				if(array_key_exists($k,$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

							    					$casequestion=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
						    						// echo $casequestion;
						    						$casequestiontype=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

						    						$casesection=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

						    						$casecategory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

						    						$caseorder=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

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

													if(array_key_exists('mandatory', $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
														$casemandatory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
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

														$casemaxrange=strlen($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

														$casetovalidation=pow(10,$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

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

													    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

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

													    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

													    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

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

							    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."')";

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
					    	// print_r($_POST['multipleinput'][$i]['answer']);
					    	// echo "<br>";
					    	// echo "<br>";
					    	for($j=0;$j<count($_POST['multipleinput'][$i]['answer']);$j++){

					    		// echo "<br>";
					    		// echo "304";
					    		// echo "<br>";
					    		// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]);
					    		// echo "<br>";
					    		// echo "<br>";
					    		if(array_key_exists('case'.$j,$_POST['multipleinput'][$i]['answer']['option'.$j]))
						    	{
						    		// echo "<br>";
						    		// echo "<br>";
						    		// echo "313";
						    		// echo "<br>";
						    		// echo "<br>";
						    		$sqloptionquery="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."') returning id";

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
					    				// echo "334 ".(intval(key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));
					    				// echo "<br>";
					    				// echo "<br>";
					    				$numb=(intval(key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]))+intval(count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])));

					    				$numbcheck=count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);

					    				$q=0;
				    					for($k=key($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j]);$k<$numb;$k++){

				    						// echo "<br>";
						    				// echo "<br>";
						    				// echo "341 ".$k;
						    				// echo "<br>";
						    				// echo "<br>";
				    						if(array_key_exists($k,$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j])){

					    						$casequestion=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['question'];
					    						// echo $casequestion;
					    						
					    						$casequestiontype=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['questiontype'];

					    						$casesection=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['section'];

					    						$casecategory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['category'];

					    						$caseorder=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['order'];

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

												if(array_key_exists('mandatory', $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k])){
													$casemandatory=$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['mandatory'];
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

													$casemaxrange=strlen($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);

													$casetovalidation=pow(10, $_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2'])-1;

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

												    	// print_r($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);
												    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

												    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

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

												    	for($m=0;$m<count($_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']);$m++){

												    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j]['case'.$j][$k]['answers2']['option'.$m][$m]."', '".$casequestionid['id']."')";

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

						    		$sql3="INSERT INTO public.options (title, question_id) VALUES ('".$_POST['multipleinput'][$i]['answer']['option'.$j][$j]."', '".$questionid['id']."')";

						    		$qsql3 = $conn->prepare($sql3);

			    					$qsql3->execute();
						    	}
					    		
							}
					    }	
					}
				}
			}
			else{
				$quescount++;
			}
    	}
    }

	header("location:home.php");
?>