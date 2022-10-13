<?php
 
  include_once 'database_pm_db.php';
//   include_once 'database.php';
  
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
	$selectsurvey="SELECT * FROM public.projects";

        	$selectcustom = $conn->prepare($selectsurvey);

    		$selectcustom->execute();

    		$form = $selectcustom->fetch(PDO::FETCH_ASSOC);

    		// $surveyname=$form['toolbar_title'];

    		// $description=$form['description'];

    		// $dbtable=$form['dbtable'];

    		// $projectid=$form['projectid'];
			$projectid=$form['id'];
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
	                    <!-- <li class="breadcrumb-item active"><a href="view.php?id=">View</a></li> -->
	                </ol>
	            </div>
	        </div>
		</div>
	    <section class="content">
	        <div class="container-fluid">
	            <div class="card">
	                <div class="card-header">
	                    <h5 class="card-title" style="float:left;">Select List</h5>
	                    <div class="card-tools" style="float:right;">
					     	<a href="./home.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
					    </div>
	                </div>
				</div>
	                <div class="card-body p-3" style="border-style:solid; border-width: thin; border-color: lightgray;">
			<div class="row">
			<div class="col-md-4">
			<div class="form-group">
			<label><b>Project Names</b></label>
			<div class="input-group mb-6">
			<select id="projectname" name="projectname" class="form-control" required>
                <option value="0">Choose Project</option>
	
                    <?php
					
						//Test
						$projectlist = "SELECT DISTINCT id, name 
										FROM   projects tb1 
										INNER   JOIN 
										(SELECT projectid, toolbar_title
										FROM   dblink('dbname=beneficiary_db port=5432 host=localhost user=postgres password=pass@123','SELECT projectid, toolbar_title FROM custom_form')
										AS tb2(projectid character varying, toolbar_title character varying))
										AS tb2 ON tb2.projectid = tb1.id";

						$selectproject = $conn->prepare($projectlist);
						$selectproject->execute();
						// $form = $selectsurvey->fetchAll(PDO::FETCH_ASSOC);
                                            
                        if ($selectproject->rowCount() > 0)
                        {
                        while ($rows = $selectproject->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <option value="<?php echo $rows['id']; ?>"> <?php echo $rows['name']?> - <?php echo substr($rows['id'], -4) ?></option>
													
                        <?php
                        	}
                        }
                        ?>
            </select>
			</div>
			</div>
		
			</div>
			
			<div class="col-md-4">
			
			<div class="form-group">
			<label><b>Survey Names</b></label>
			<div class="input-group mb-6" id="questionsdropdown">
			<select id="surveyname" name="surveyname" class="form-control">
			<option value="0">Choose Survey</option>
			
				              
            </select>
			</div>
			</div>
			
			</div>
			
			<div class="col-md-4" style="padding-top: 31px;">
			<div class="input-group mb-6" id="translate_en" style="width: 183px;">
                        <a class="btn btn-primary" id="engbutton" href="" style="margin-right: 20px;">Translate to English</a>

						</div>
                        </div>
				</div>
				</div>
			</div>
	    </section>
	</div>
	<script type="text/javascript" src='jquery-3.4.1.min.js'></script>
						<script type="text/javascript">

							$(document).ready(function(){
								$('#projectname').change(function(){
								var pr_id = $(this).val();
								// alert( "you selected=" + pr_id );

								$('#surveyname').find('option').not(':first').remove();
								// AJAX request
									$.ajax({
									url: 'eng_view_ajaxcall.php',
									type: 'post',
									data: {request: 1, pr_id: pr_id},
									dataType: 'json',
									success: function(response){

										var len = response.length;
										for( var i = 0; i<len; i++){
										var id = response[i]['id'];
										var name = response[i]['name'];
										var formid = response[i]['formid'];
										// console.log(formid);
										$("#surveyname").append("<option value='"+formid+"'>"+name+"</option>");

										}
									}
									});

								});
							});

								$('#engbutton').click(function(){
									debugger;
								var pr_id = $('#projectname').val();
								var formid = $('#surveyname').val();
									// alert( "projectid=" + pr_id + "  formid=" + formid);

									$.ajax({
									url: 'jobq_insert.php',
									type: 'post',
									data: {request: 2, pr_id: pr_id, formid: formid},
									dataType: 'json',
									success: function(response){
										// return data;
										alert(response);
									}

									});				
							    });
							
						

						</script>
</body>
</html>