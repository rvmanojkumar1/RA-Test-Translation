<?php
  include_once 'database.php';
  
  // include_once 'username.php';
  session_start();

  // $company=$_SESSION["username"];

  if (array_key_exists("username",$_SESSION)=='') {
    echo ("<script>location.href='./index.php'</script>");
  }
  else if($_SESSION["username"]==null||$_SESSION["username"]==''){
    echo ("<script>location.href='./index.php'</script>");
  }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
<?php
	include_once('links.php');

	if(isset($_GET['id'])){

        $survey_id=$_GET['id'];

        $selectsurvey="SELECT toolbar_title, description, dbtable, projectid FROM public.custom_form WHERE ide=$survey_id";

        $selectcustom = $conn->prepare($selectsurvey);

    	$selectcustom->execute();

    	$form = $selectcustom->fetch(PDO::FETCH_ASSOC);

    	$surveyname=$form['toolbar_title'];

    	$description=$form['description'];

    	$dbtable=$form['dbtable'];

    	$projectid=$form['projectid'];
	}
    else{
      	echo "<script>alert('Something went wrong!!');</script>";
       	header("location:home.php");
    }
?>
</head>
<body>
	<div class="container-fluid">
	    <div class="content-header">
	        <div class="row mb-2">
	            <div class="col-sm-6">
	                <h2 class="m-2 text-dark">Survey Questionaire</h2>
	            </div><!-- /.col -->
	            <div class="col-sm-6">
	                <ol class="breadcrumb float-sm-right">
	                    <li class="breadcrumb-item"><a href="home.php">Survey Questionaire</a></li>
	                    <li class="breadcrumb-item active"><a href="view.php?id=<?php echo $survey_id; ?>">View</a></li>
	                </ol>
	            </div>
	        </div>
	    </div>
	    <section class="content">
	        <div class="container-fluid">
	            <div class="card">
	                <div class="card-header">
	                    <h5 class="card-title" style="float:left;">View Survey</h5>
	                    <div class="card-tools" style="float:right;">
					     	<a href="./home.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
					    </div>
	                </div>
	                <div class="card-body p-3">
	                	<div class="row">
	                		<div class="col-md-2"></div>
	                		<div class="col-md-3">
	                			<label><b>Project Name</b></label>
	                		</div>
	                		<div class="col-md-3">
	                			<label><b>Survey Name</b></label>
	                		</div>
	                		<div class="col-md-3">
	                			<label><b>Description (optional)</b></label>
	                		</div>
	                		<div class="col-md-1"></div>
	                	</div>
	                	<?php

	                		//Test
                            $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=postgres password=pass@123' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id and p.project_id='".$projectid."'";

                            //UAT
                            // $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=mguatadmin password=bVqTHv9FEXUxhR3k' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id and p.project_id='".$projectid."'";

                            //Live
                            // $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=mgprodadmin password=tmkamhpH945ZWFHx' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id and p.project_id='".$projectid."'";

                            $projects = $conn->prepare($sql);

                            $projects->execute();

                            if ($projects->rowCount() > 0)
                            {
                                $projectdetails = $projects->fetch(PDO::FETCH_ASSOC);
                            }
                        ?>
	                	<div class="row">
	                		<div class="col-md-2"></div>
	                		<div class="col-md-3">
	                			<label><?php echo $projectdetails['project_name']; ?> - <?php echo substr($projectdetails['project_id'], -4) ?></label>
	                		</div>
	                		<div class="col-md-3">
	                			<label><?php echo $surveyname; ?></label>
	                		</div>
	                		<div class="col-md-3">
	                			<label><?php echo $description;  ?></label>
	                		</div>
	                		<div class="col-md-1"></div>
	                	</div>
	                	<hr>
	                	<div>
	                		<?php
                        		$selectques="SELECT id, compulsory, db_column, dep_question_id, dep_select_id, from_validation, max_range, min_range, question_name, question_type, to_validation, section, category FROM public.newquestionnaire WHERE questionnaire_id=$survey_id and dep_question_id is null order by id";

                        		$selectquestion = $conn->prepare($selectques);

    							$selectquestion->execute();

    							if($selectquestion->rowCount()>0){
    								$i=0;
    								while($formques = $selectquestion->fetch(PDO::FETCH_ASSOC)){
    									?>
	    									<div class="row">
				                                <div class="col-md-2">
				                                    <label><b>Section</b></label>
				                                </div>
				                                <div class="col-md-4">
				                                    <label><b>Question <?php echo $i+1; ?></b></label>
				                                </div>
				                                <div class="col-md-3">
				                                    <label><b>Response Type</b></label>
				                                </div>
				                                <div class="col-md-2">
				                                    <label><b>Category</b></label>
				                                </div>
				                                <div class="col-md-1" style="text-align:center;">
				                                    <label><b>Mandatory</b></label>
				                                </div>
				                            </div>
				                            <div class="row">
				                                <div class="col-md-2">
				                                    <label><?php echo ucwords($formques['section']); ?></label>
				                                </div>
				                                <div class="col-md-4">
				                                    <label><?php echo $formques['question_name']; ?></label>
				                                </div>
				                                <div class="col-md-3">
				                                    <label><?php echo $formques['question_type']; ?></label>
				                                </div>
				                                <div class="col-md-2">
				                                    <label><?php echo ucwords($formques['category']); ?></label>
				                                </div>
				                                <div class="col-md-1" style="text-align:center;">
				                                    <label><?php if($formques['compulsory']=='true') echo "Yes"; else echo "false"; ?></label>
				                                </div>
				                            </div>
				                            <?php 
				                            	if($formques['question_type']=='Text'){
				                            		?>
				                            			<div class="row">
				                            				<div class="col-md-3">
				                            					<label><b>Answer</b></label>
				                            				</div>
				                            			</div>
				                            			<div class="row">
				                            				<div class="col-md-3">
				                            					<label>Textbox</label>
				                            				</div>
				                            			</div>
				                            		<?php
				                            	}
				                            	if($formques['question_type']=='Integer'){
				                            		?>
				                            			<div class="row">
				                            				<div class="col-md-3">
				                            					<label>Answer</label>
				                            				</div>
				                            			</div>
				                            			<div class="row">
				                            				<div class="col-md-3">
				                            					<label>Size (Max no.'s allowed) - <?php echo $formques['to_validation']; ?></label>
				                            				</div>
				                            			</div>
				                            		<?php
				                            	}
				                            	if($formques['question_type']=='Single Select'){
				                            		?>
				                            			<br/>
				                            		<?php
				                            			$optionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$formques['id']."' order by id";

				                            			$optquery = $conn->prepare($optionquery);

    													$optquery->execute();

    													if($optquery->rowCount()>0){
						    								$m=0;
						    								while($optionsresult = $optquery->fetch(PDO::FETCH_ASSOC)){
						    									
						    									?>
							    									<div class="row">
							    										<div class="col-md-1"></div>
							    										<div class="col-md-6">
							    											<label><b>Option <?php echo $m+1; ?></b></label>
							    											<br/>
							    											<label><?php echo $optionsresult['title']; ?></label>
							    										</div>
							    									</div>
						    									<?php

						    									$m++;

						    									$casequestion="SELECT id, compulsory, db_column, dep_question_id, dep_select_id, from_validation, max_range, min_range, question_name, question_type, to_validation, section, category FROM public.newquestionnaire WHERE questionnaire_id=$survey_id AND dep_question_id='".$formques['id']."' AND dep_select_id='".$optionsresult['id']."'";

						    									$caseques=$conn->prepare($casequestion);

						    									$caseques->execute();

						    									if($caseques->rowCount()>0){
						    										?>
						    											<div class="container">
						    										<?php
						    										$r=0;
						    										while($case=$caseques->fetch(PDO::FETCH_ASSOC)){
						    											?>
						    												<div class="row">
												                                <div class="col-md-2">
												                                    <label><b>Section</b></label>
												                                </div>
												                                <div class="col-md-4">
												                                    <label><b>Case Question <?php echo $r+1; ?></b></label>
												                                </div>
												                                <div class="col-md-3">
												                                    <label><b>Response Type</b></label>
												                                </div>
												                                <div class="col-md-2">
												                                    <label><b>Category</b></label>
												                                </div>
												                                <div class="col-md-1" style="text-align:center;">
												                                    <label><b>Mandatory</b></label>
												                                </div>
												                            </div>
												                            <div class="row">
												                                <div class="col-md-2">
												                                    <label><?php echo ucwords($case['section']); ?></label>
												                                </div>
												                                <div class="col-md-4">
												                                    <label><?php echo $case['question_name']; ?></label>
												                                </div>
												                                <div class="col-md-3">
												                                    <label><?php echo $case['question_type']; ?></label>
												                                </div>
												                                <div class="col-md-2">
												                                    <label><?php echo ucwords($case['category']); ?></label>
												                                </div>
												                                <div class="col-md-1" style="text-align:center;">
												                                    <label><?php if($case['compulsory']=='true') echo "Yes"; else echo "No"; ?></label>
												                                </div>
												                            </div>
						    											<?php
								    										if($case['question_type']=='Text'){
												                            	?>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label><b>Answer</b></label>
												                            			</div>
												                            		</div>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label>Textbox</label>
												                            			</div>
												                            		</div>
												                            	<?php
												                            }
												                            if($case['question_type']=='Integer'){
												                            	?>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label><b>Answer</b></label>
												                            			</div>
												                            		</div>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label>Size (Max no.'s allowed) - <?php echo $case['to_validation']; ?></label>
												                            			</div>
												                            		</div>
												                            	<?php
												                            }
												                            if($case['question_type']=='Single Select'){
											                            		?>
											                            			<br/>
											                            		<?php
											                            		$caseoptionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$case['id']."' order by id";

										                            			$caseoptquery = $conn->prepare($caseoptionquery);

						    													$caseoptquery->execute();

						    													if($caseoptquery->rowCount()>0){
												    								$y=0;
												    								while($caseoptionsresult = $caseoptquery->fetch(PDO::FETCH_ASSOC)){
												    									?>
													    									<div class="row">
													    										<div class="col-md-1"></div>
													    										<div class="col-md-6">
													    											<label><b> Case Option <?php echo $y+1; ?></b></label>
													    											<br/>
													    											<label><?php echo $caseoptionsresult['title']; ?></label>
													    										</div>
													    									</div>
												    									<?php
												    									$y++;
												    								}
												    							}
											                            	}

											                            	if($case['question_type']=='Multi Select'){
											                            		?>
											                            			<br/>
											                            		<?php
											                            		$caseoptionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$case['id']."' order by id";

										                            			$caseoptquery = $conn->prepare($caseoptionquery);

						    													$caseoptquery->execute();

						    													if($caseoptquery->rowCount()>0){
												    								$y=0;
												    								while($caseoptionsresult = $caseoptquery->fetch(PDO::FETCH_ASSOC)){
												    									?>
													    									<div class="row">
													    										<div class="col-md-1"></div>
													    										<div class="col-md-6">
													    											<label><b>Case Option <?php echo $y+1; ?></b></label>
													    											<br/>
													    											<label><?php echo $caseoptionsresult['title']; ?></label>
													    										</div>
													    									</div>
												    									<?php
												    									$y++;
												    								}
												    							}
											                            	}

											                            $r++;
						    										}
						    										?>
						    											</div>
						    										<?php
						    									}
						    								}
					    								}
					    							?>
					    								
					    							<?php
				                            	}
				                            	if($formques['question_type']=='Multi Select'){
				                            		?>
				                            			<br/>
				                            		<?php
				                            			$optionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$formques['id']."' order by id";

				                            			$optquery = $conn->prepare($optionquery);

    													$optquery->execute();

    													if($optquery->rowCount()>0){
						    								$m=0;
						    								while($optionsresult = $optquery->fetch(PDO::FETCH_ASSOC)){
						    									
						    									?>
							    									<div class="row">
							    										<div class="col-md-1"></div>
							    										<div class="col-md-6">
							    											<label><b>Option <?php echo $m+1; ?></b></label>
							    											<br/>
							    											<label><?php echo $optionsresult['title']; ?></label>
							    										</div>
							    									</div>
						    									<?php

						    									$m++;

						    									$casequestion="SELECT id, compulsory, db_column, dep_question_id, dep_select_id, from_validation, max_range, min_range, question_name, question_type, to_validation, section, category FROM public.newquestionnaire WHERE questionnaire_id=$survey_id AND dep_question_id='".$formques['id']."' AND dep_select_id='".$optionsresult['id']."'";

						    									$caseques=$conn->prepare($casequestion);

						    									$caseques->execute();

						    									if($caseques->rowCount()>0){
						    										?>
						    											<div class="container">
						    										<?php
						    										$r=0;
						    										while($case=$caseques->fetch(PDO::FETCH_ASSOC)){
						    											?>
						    												<div class="row">
												                                <div class="col-md-2">
												                                    <label><b>Section</b></label>
												                                </div>
												                                <div class="col-md-4">
												                                    <label><b>Case Question <?php echo $r+1; ?></b></label>
												                                </div>
												                                <div class="col-md-3">
												                                    <label><b>Response Type</b></label>
												                                </div>
												                                <div class="col-md-2">
												                                    <label><b>Category</b></label>
												                                </div>
												                                <div class="col-md-1" style="text-align:center;">
												                                    <label><b>Mandatory</b></label>
												                                </div>
												                            </div>
												                            <div class="row">
												                                <div class="col-md-2">
												                                    <label><?php echo ucwords($case['section']); ?></label>
												                                </div>
												                                <div class="col-md-4">
												                                    <label><?php echo $case['question_name']; ?></label>
												                                </div>
												                                <div class="col-md-3">
												                                    <label><?php echo $case['question_type']; ?></label>
												                                </div>
												                                <div class="col-md-2">
												                                    <label><?php echo ucwords($case['category']); ?></label>
												                                </div>
												                                <div class="col-md-1" style="text-align:center;">
												                                    <label><?php if($case['compulsory']=='true') echo "Yes"; else echo "false"; ?></label>
												                                </div>
												                            </div>
						    											<?php
								    										if($case['question_type']=='Text'){
												                            	?>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label>Answer</label>
												                            			</div>
												                            		</div>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label>Textbox</label>
												                            			</div>
												                            		</div>
												                            	<?php
												                            }
												                            if($case['question_type']=='Integer'){
												                            	?>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label>Answer</label>
												                            			</div>
												                            		</div>
												                            		<div class="row">
												                            			<div class="col-md-3">
												                            				<label>Size (Max no.'s allowed) - <?php echo $case['to_validation']; ?></label>
												                            			</div>
												                            		</div>
												                            	<?php
												                            }
												                            if($case['question_type']=='Single Select'){
											                            		?>
											                            			<br/>
											                            		<?php
											                            		$caseoptionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$case['id']."' order by id";

										                            			$caseoptquery = $conn->prepare($caseoptionquery);

						    													$caseoptquery->execute();

						    													if($caseoptquery->rowCount()>0){
												    								$y=0;
												    								while($caseoptionsresult = $caseoptquery->fetch(PDO::FETCH_ASSOC)){
												    									?>
													    									<div class="row">
													    										<div class="col-md-1"></div>
													    										<div class="col-md-6">
													    											<label><b>Case Option <?php echo $y+1; ?></b></label>
													    											<br/>
													    											<label><?php echo $caseoptionsresult['title']; ?></label>
													    										</div>
													    									</div>
												    									<?php
												    									$y++;
												    								}
												    							}
											                            	}

											                            	if($case['question_type']=='Multi Select'){
											                            		?>
											                            			<br/>
											                            		<?php
											                            		$caseoptionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$case['id']."' order by id";

										                            			$caseoptquery = $conn->prepare($caseoptionquery);

						    													$caseoptquery->execute();

						    													if($caseoptquery->rowCount()>0){
												    								$y=0;
												    								while($caseoptionsresult = $caseoptquery->fetch(PDO::FETCH_ASSOC)){
												    									?>
													    									<div class="row">
													    										<div class="col-md-1"></div>
													    										<div class="col-md-6">
													    											<label><b>Case Option <?php echo $m+1; ?></b></label>
													    											<br/>
													    											<label><?php echo $caseoptionsresult['title']; ?></label>
													    										</div>
													    									</div>
												    									<?php
												    									$y++;
												    								}
												    							}
											                            	}
											                        $r++;
						    										}
						    										?>
						    											</div>
						    										<?php
						    									}
						    								}
					    								}
					    							?>
					    								
					    							<?php
				                            	}
				                            ?>
				                            <hr>
				                        <?php
				                        $i++;
	    							}
    							}
	    							
                        	?>
	                	</div>
	                </div>
	            </div>
	        </div>
	    </section>
	</div>
</body>