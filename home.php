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
    <?php
        include_once('links.php');
    ?>
	<script>
	    $(document).ready(function() {
	        $('#table').DataTable();
	    });
        function getdatamodel(formid){
            console.log(formid);
            document.getElementById("formid").value=formid;
        }
        function replicate(){
            var formid=document.getElementById("formid").value;
            var lang=document.getElementById("selectlang").value;
            var formname=document.getElementById("formname").value;
            var project=document.getElementById("selectproject").value;

            console.log(formid);
            console.log(lang);
            var mark = {
                "url": "./replicate.php",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "formid": formid,
                    "lang":lang,
                    "formname":formname,
                    "project":project
                }),
            };

            $.ajax(mark).done(function (response) {

            });
        }
        function confirmdelete(id){
            let text = "Are you sure you want to delete?";
            if (confirm(text) == true) {
                window.location.href="delete.php?deleteid="+id+"";
            } else {
                
            }
        }
	</script>
</head>
<body>
	<div class="container-fluid">

    <!-- Content Header (Page header) -->
    <div class="content-header">

        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Setup Survey Questionnaire</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Survey Questionnaire</a></li>
                    <li class="breadcrumb-item active"><a href="index.php"> Home</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                	<div class="row">
                		<div class="col-md-9"><h5 class="card-title">Listing Survey Questionnaire</h5>
                        </div>
                		<div class="col-md-1" style="float:right;">
                        <a class="btn btn-primary" href="./eng_view.php" style="margin-right: 20px;">Translate</a>
                        </div>
                        <div class="col-md-2" style="float:right;">
                        <a class="btn btn-primary" href="./create.php" style="margin-right: 20px;">Add New Survey</a>
                        </div>
                	</div>
                </div>
                <div class="card-body table-responsive p-3">
                    <table id="table" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Questionnaire Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                                <th>Language</th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                        <tbody>
                        	<?php
                        		$query="SELECT ide, toolbar_title, description, language FROM public.custom_form WHERE isdeleted=false Order by ide";

            					$ra_assess = $conn->prepare($query);

            					$ra_assess->execute();
            					
            					if ($ra_assess->rowCount() > 0)
            					{
            						$i=1;
            						while ($rows = $ra_assess->fetch(PDO::FETCH_ASSOC)){
                        	?>
				                        <tr>
				                        	<td><?php echo $i; ?></td>
				                        	<td><?php echo $rows['toolbar_title']; ?></td>
				                        	<td><?php echo $rows['description']; ?></td>
                                            <td><a title="View" href="./view.php?id=<?php echo $rows['ide']; ?>" class="btn btn-success"><i class="far fa-eye"></i> </a>&nbsp;<a title="Edit" href="./edit.php?id=<?php echo $rows['ide']; ?>" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> </a>&nbsp;<button title="Delete" class="btn btn-danger" onclick="confirmdelete('<?php echo $rows['ide']; ?>')"><i class="fas fa-trash-alt"></i> </button></td>
                                            
                                            <td class="form-group">
                                                <select class="form-control">
                                                    <option <?php if($rows['language']=='en') echo "selected"; ?> disabled>English</option>
                                                    <option <?php if($rows['language']=='kn') echo "selected"; ?> disabled>Kannada</option>
                                                    <option <?php if($rows['language']=='bn') echo "selected"; ?> disabled>Bengali</option>
                                                    <option <?php if($rows['language']=='ma') echo "selected"; ?> disabled>Marathi</option>
                                                </select>
                                            </td>
                                            <!-- <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="getdatamodel('<?php echo $rows['ide']; ?>')" disabled>Replicate</button></td> -->
                                            <!-- <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="getdatamodel('<?php echo $rows['ide']; ?>')">Replicate</button></td> -->
				                        </tr>
				            <?php
				            		$i=$i+1;
				            		}
				            	}
				            ?>
                        </tbody>
                    </table>
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Replicate</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="mediumBody">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><b>Form Id</b></label>
                                                    <div class="input-group mb-12">
                                                        <input type="text" name="formid" id="formid" class="form-control" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><b>Form Name</b></label>
                                                    <div class="input-group mb-12">
                                                        <input type="text" name="formname" id="formname" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><b>Project</b></label>
                                                    <select class="form-control" id="selectproject">
                                                        <option value="" selected disabled>Choose Project name</option>
                                                        <?php
                                                            //Test
                                                            $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=postgres password=pass@123' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id";

                                                            //UAT
                                                            // $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=mguatadmin password=bVqTHv9FEXUxhR3k' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id";

                                                            //Live
                                                            // $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=mgprodadmin password=tmkamhpH945ZWFHx' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id";

                                                            $projects = $conn->prepare($sql);

                                                            $projects->execute();
                                                        
                                                            if ($projects->rowCount() > 0)
                                                            {
                                                                while ($rows = $projects->fetch(PDO::FETCH_ASSOC)){
                                                                ?>
                                                                    <option value="<?php echo $rows['project_id'] ?>"><?php echo $rows['project_name']?> - <?php echo substr($rows['project_id'], -4) ?></option>
                                                                <?php
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><b>Language</b></label>
                                                    <select class="form-control" id="selectlang">
                                                        <option value="en">English</option>
                                                        <option value="kn">Kannada</option>
                                                        <option value="bn">Bengali</option>
                                                        <option value="mr">Marathi</option>
                                                        <option value="hi">Hindi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button class="btn btn-success form-control" onclick="replicate()">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</body>
</html>