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
    <script type="text/javascript">

        var s=0;
        var d=0;

        var g=0;

        function showhideoptions(id, value, number){
            debugger;

            if(value=="text"){
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" id="'+value+number+'" name="multipleinput['+number+'][answer]" placeholder="Textbox" readonly/></div></div>');
            }
            if(value=="number"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" type="number" id="'+value+number+'" name="multipleinput['+number+'][answer]" placeholder="Total Size (i.e. Max numbers allowed)" required/></div></div>');
            }
            if(value=="trueorfalse"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><select class="form-control" id="'+value+number+'" name="multipleinput['+number+'][answer]"><option selected disabled>The Following are the options</option><option value="Yes" disabled>Yes</option><option value="No" disabled>No</option></select></div></div>');
            }
            if(value=="multichoiceoneans"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" min="0" name="multipleinput['+number+'][answer]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary" onclick="addoptions('+"'"+number+"'"+', '+"'"+id+number+"'"+', '+"'count"+value+number+"'"+', '+"'"+value+"'"+')">Generate</button></div></div><div class="col-md-9"></div></div>');
                // $("#dynamicquestions"+number).append('<div class="row"><div class="col-md-6"><input class="form-control" id="option0'+value+number+'" name="multipleinput['+number+'][option0'+value+']" placeholder="option 1"/></div><div class="col-md-6"><input class="form-control" id="option1'+value+number+'" name="multipleinput['+number+'][option1'+value+']" placeholder="option 2"/></div><br/><br/><br/><div class="col-md-6"><input class="form-control" id="option2'+value+number+'" name="multipleinput['+number+'][option2'+value+']" placeholder="option 3"/></div><div class="col-md-6"><input class="form-control" id="option3'+value+number+'" name="multipleinput['+number+'][option3'+value+']" placeholder="option 4"/></div></div>');
            }
            if(value=="multichoicemultians"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" min="0" name="multipleinput['+number+'][answer]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary" onclick="addoptions('+"'"+number+"'"+', '+"'"+id+number+"'"+', '+"'count"+value+number+"'"+', '+"'"+value+"'"+')">Generate</button></div></div><div class="col-md-9"></div></div>');
            }
        }

        function addoptions(number, id, inputid, value){
            debugger;
            console.log(inputid)
            var count=document.getElementById(inputid).value;
            var nwdyid='#'+id;
            if(count=='0'||count==null){
                alert("Enter number of options");
            }else{
                $(nwdyid).empty();
                
                for(var i=0; i<count; i++){

                    $(nwdyid).append('<div class="row" style="margin-bottom:1%;"><div class="col-md-8"><label><b>Option'+(parseInt(i)+1)+'</b></label></div><div class="col-md-4" style="text-align: center;"><label><b>Case Condition</b></label></div><div class="col-md-8"><input class="form-control" id="option'+i+value+number+'" name="multipleinput['+number+'][answer][option'+i+']['+i+']" placeholder="Option '+(parseInt(i)+1)+'" required/></div><div class="col-md-4" style="text-align: center;"><div class="icheck-primary d-inline"><input type="checkbox" id="icheckcase'+i+value+number+'" name="multipleinput['+number+'][answer][option'+i+'][case'+i+']" onclick="mapquestion('+"'"+'checkbox'+i+value+number+"'"+', this.id, '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+', '+"'checkbox"+i+value+number+"button'"+', '+i+')" style="display: none;"><label for="icheckcase'+i+value+number+'"></label></div></div></div><div class="col-md-12" id="checkbox'+i+value+number+'" style="padding-left: 30px;"></div><div class="row" id="checkbox'+i+value+number+'button" style="display:none;"><div class="col-md-9"></div><div class="col-md-3"><button type="button" id="addItemcase" class="btn btn-primary form-control" onclick="addcasequestion('+"'"+'checkbox'+i+value+number+"'"+', '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+', '+i+')">Add New Case Question</button></div></div><hr>');

                    // var butid='checkbox'+i+value+number+'button';

                    // butid='#'+butid;
                    // $(butid).show();
                }
            }
        }

        function mapquestion(optioncaseid, checkid, caseid, optionid, number, casebuttonid, i){
            debugger;

            var newid='#'+optioncaseid;

            var buttonid='#'+casebuttonid;

            if (document.getElementById(checkid).checked)
            {
                // $(buttonid).show();

                const box = document.getElementById(casebuttonid);

                box.style.removeProperty('display');

                $(newid).append('<div style="padding-left:3%"><div id="surveycase'+d+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-2"><label><b>Section</b></label></div><div class="col-md-4"><label><b>Question</b></label></div><div class="col-md-3"><label><b>Response Type</b></label></div><div class="col-md-2"><label><b>Category</b></label></div><div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div></div><div class="row"><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="section'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][section]" class="form-control" required><option value="" selected disabled>Choose Section</option><option value="personal">Personal</option><option value="family">Family</option><option value="business_interest">Business Interest</option><option value="financial">Financial</option><option value="others">Others</option></select></div></div></div><div class="col-md-4"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][questiontype]" class="form-control" onchange="showhideoptionscase('+"'dynamicquestionscase'"+', this.value,'+d+', '+"'"+optionid+"'"+', '+"'"+caseid+"'"+', '+number+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="category'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][category]" class="form-control" required><option value="" selected disabled>Choose Category</option><option value="health">Health</option><option value="education">Education</option><option value="standard_of_living">Standard of Living</option><option value="skill">Skill</option><option value="interest">Interest</option><option value="income">Income</option><option value="none">None</option></select></div></div></div><div class="col-md-1" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryd'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][mandatory]"><label for="checkboxPrimaryd'+d+'"></label></div></div><div class="col-md-2"><label><b>Chronological order</b></label></div><div class="col-md-10"></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><input type="number" min="0" id="order'+d+'" name="multipleinputt['+number+'][answer]['+optionid+']['+caseid+']['+d+'][order]"  class="form-control" placeholder="Order of Questions (number)" required></div></div></div><input type="hidden" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][dbcolumn]" value=""/><label style="padding-left: 10px;"><b>Answers</b></label><div id="dynamicquestionscase'+d+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div></div>');
                d++;
            }
            else {
                $(newid).empty();
                // $(buttonid).hide();
                const box = document.getElementById(casebuttonid);
                box.style.setProperty('display', 'none');
            }
        }

        function addcasequestion(optioncaseid, caseid, optionid, number, i){

            debugger;

            var optid='#'+optioncaseid;
            if(d<i){
            	d=i+1;
            }
            $(optid).append('<div style="padding-left:3%"><div id="surveycase'+d+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-2"><label><b>Section</b></label></div><div class="col-md-4"><label><b>Question</b></label></div><div class="col-md-3"><label><b>Response Type</b></label></div><div class="col-md-2"><label><b>Category</b></label></div><div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div></div><div class="row"><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="section'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][section]" class="form-control" required><option value="" selected disabled>Choose Section</option><option value="personal">Personal</option><option value="family">Family</option><option value="business_interest">Business Interest</option><option value="financial">Financial</option><option value="others">Others</option></select></div></div></div><div class="col-md-4"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][questiontype]" class="form-control" onchange="showhideoptionscase('+"'dynamicquestionscase'"+', this.value,'+d+', '+"'"+optionid+"'"+', '+"'"+caseid+"'"+', '+"'"+number+"'"+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="category'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][category]" class="form-control" required><option value="" selected disabled>Choose Category</option><option value="health">Health</option><option value="education">Education</option><option value="standard_of_living">Standard of Living</option><option value="skill">Skill</option><option value="interest">Interest</option><option value="income">Income</option><option value="none">None</option></select></div></div></div><div class="col-md-1" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryd'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][mandatory]"><label for="checkboxPrimaryd'+d+'"></label></div></div><div class="col-md-2"><label><b>Chronological order</b></label></div><div class="col-md-10"></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><input type="number" min="0" id="order'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][order]" class="form-control" placeholder="Order of Questions (number)" required></div></div></div><input type="hidden" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][dbcolumn]" value=""/><label style="padding-left: 10px;"><b>Answers</b></label> <div id="dynamicquestionscase'+d+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div></div>'); 
                d++;
        }


        function showhideoptionscase(id, value, number, oldoptionid, oldcaseid, oldnumber){
            debugger;
            if(value=="text"){
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" id="'+value+number+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2]" placeholder="Textbox" readonly/></div></div>');
            }
            if(value=="number"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" type="number" id="'+value+number+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2]" placeholder="Total Size (i.e. Max numbers allowed)" required/></div></div>');
            }
            if(value=="trueorfalse"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><select class="form-control" id="'+value+number+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2]"><option selected disabled>The Following are the options</option><option value="Yes" disabled>Yes</option><option value="No" disabled>No</option></select></div></div>');
            }
            if(value=="multichoiceoneans"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" min="0" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary" onclick="addoptionsnew('+"'"+id+number+"'"+', '+"'count"+value+number+"'"+', '+"'"+value+"'"+', '+"'"+number+"'"+', '+"'"+oldnumber+"'"+', '+"'"+oldcaseid+"'"+', '+"'"+oldoptionid+"'"+')">Generate</button></div></div><div class="col-md-9"></div></div>');
              
            }
            if(value=="multichoicemultians"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" min="0" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary" onclick="addoptionsnew('+"'"+id+number+"'"+', '+"'count"+value+number+"'"+', '+"'"+value+"'"+', '+"'"+number+"'"+', '+"'"+oldnumber+"'"+', '+"'"+oldcaseid+"'"+', '+"'"+oldoptionid+"'"+')">Generate</button></div></div><div class="col-md-9"></div></div>');
            }
        }

        function addoptionsnew(id, inputid, value, number, oldnumber, oldcaseid, oldoptionid){
            var nwdyid='#'+id;
            console.log(id);
            console.log(inputid);
            console.log(value);
            console.log(number);
            console.log(oldoptionid);
            console.log(oldcaseid);
            console.log(oldnumber);
            var count=document.getElementById(inputid).value;
            if(count=='0'||count==null){
                alert("Enter number of options");
            }else{
                $(nwdyid).empty();
                $(nwdyid).append('<div class="row" style="margin-bottom:1%;">');
                for(var i=0; i<count; i++){
                    $(nwdyid).append('<div class="col-md-6"><label><b>Option'+(parseInt(i)+1)+'</b></label></div><div class="col-md-6"><input class="form-control" id="option'+i+value+number+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2][option'+i+']['+i+']" placeholder="Option '+(parseInt(i)+1)+'" required /></div>');

                    // var butid='checkboxnew'+i+value+number+'button';

                    // butid='#'+butid;
                    // $(butid).hide();
                }
                $(nwdyid).append('</div><hr>');
            }
        }

        $(document).ready(function() {
            $("#addItem").click(function () {
            	debugger;
            	var n=document.getElementById("hiddenval").value;
            	if(s<n){
            		s=n;
            	}
                ++s;
                $("#dynamic").append('<div id="survey'+s+'" class="surveyname"><br /><h4>Next Question</h4><hr><div class="row"><div class="col-md-2"><label><b>Section</b></label></div><div class="col-md-4"><label><b>Question</b></label></div><div class="col-md-3"><label><b>Response Type</b></label></div><div class="col-md-2"><label><b>Category</b></label></div><div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div></div><div class="row"><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="section'+s+'" name="multipleinput['+s+'][section]" class="form-control" required><option value="" selected disabled>Choose Section</option><option value="personal">Personal</option><option value="family">Family</option><option value="business_interest">Business Interest</option><option value="financial">Financial</option><option value="others">Others</option></select></div></div></div><div class="col-md-4"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+s+'" name="multipleinput['+s+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+s+'" name="multipleinput['+s+'][questiontype]" class="form-control" onchange="showhideoptions('+"'dynamicquestions'"+', this.value,'+s+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="category'+s+'" name="multipleinput['+s+'][category]" class="form-control" required><option value="" selected disabled>Choose Category</option><option value="health">Health</option><option value="education">Education</option><option value="standard_of_living">Standard of Living</option><option value="skill">Skill</option><option value="interest">Interest</option><option value="income">Income</option><option value="none">None</option></select></div></div></div><div class="col-md-1" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimarys'+s+'" name="multipleinput['+s+'][mandatory]"><label for="checkboxPrimarys'+s+'"></label></div></div><div class="col-md-2"><label><b>Chronological order</b></label></div><div class="col-md-10"></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><input type="number" min="0" id="order'+s+'" name="multipleinput['+s+'][order]" class="form-control" placeholder="Order of Questions (number)" required></div></div></div><input type="hidden" name="multipleinput['+s+'][dbcolumn]" value=""/><label style="padding-left: 10px;"><b>Answers</b></label><div id="dynamicquestions'+s+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitem"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
            });

            $(document).on('click', '#removeitem', function () {
                $(this).closest('.surveyname').remove();
            });

            $(document).on('click', '#disablequestion', function () {
                $(this).closest('.surveyname *').attr('disabled','disabled');
            });


            $(document).on('click', '#removeitemcase', function () {
                $(this).closest('.surveynamecase').remove();
            });

            $(document).on('click', '#disablequestioncase', function () {
                $(this).closest('.surveynamecase').remove();
            });
        });
    </script>
</head>
<body>
    <div class="container-fluid">
    <div class="content-header">

        <div class="row mb-2">
            <div class="col-sm-6">
                <h2 class="m-2 text-dark">Setup Survey Questionaire</h2>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="home.php">Survey Questionaire</a></li>
                    <li class="breadcrumb-item active"><a href="edit.php">Edit</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Survey</h5>
                </div>
                <div class="card-body p-3">
                    <form method="post" action="formupdate.php">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Project Name</b></label>
                                    <div class="input-group mb-6">
                                        <select id="projectname" name="projectname" class="form-control" required>
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
                                                        <option value="<?php echo $rows['project_id']; ?>" <?php if($projectid==$rows['project_id']) echo "selected";  ?>><?php echo $rows['project_name']?> - <?php echo substr($rows['project_id'], -4) ?></option>
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
                                    <label><b>Survey Name</b></label>
                                    <div class="input-group mb-6">
                                        <input type="text" name="survey" value="<?php echo $surveyname; ?>" class="form-control" placeholder="Survey Name" maxlength="30" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Description (optional)</b></label>
                                    <div class="input-group mb-6">
                                        <textarea class="form-control" rows="1" placeholder="Enter Description" name="description"><?php echo $description;  ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="dbtable" value="<?php echo $dbtable; ?>">
                        </div>
                        <br />
                        <hr>
                        <h4>Questions</h4>
                        <br />
                        <div id=dynamic>
                        	<?php
                        		$selectques="SELECT id, compulsory, db_column, dep_question_id, dep_select_id, from_validation, max_range, min_range, question_name, question_type, to_validation, section, category, isdisabled, \"order\" FROM public.newquestionnaire WHERE questionnaire_id=$survey_id and dep_question_id is null order by id";

                        		$selectquestion = $conn->prepare($selectques);

    							$selectquestion->execute();
    							$i=0;
    							$r=0;
    							while($formques = $selectquestion->fetch(PDO::FETCH_ASSOC)){
    								// print_r($formques['question_type']);
    								?>
    									<div class="row">
			                                <div class="col-md-2">
			                                    <label><b>Section</b></label>
			                                </div>
			                                <div class="col-md-4">
			                                    <label><b>Question</b></label>
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
			                                    <div class="form-group">
			                                        <div class="input-group mb-6">
			                                            <select id="section0" name="multipleinput[<?php echo $i; ?>][section]" class="form-control" required>
			                                                <option value="" selected disabled>Choose Section</option>
			                                                <option value="personal" <?php if($formques['section']=='personal') echo "selected"; ?>>Personal</option>
			                                                <option value="family" <?php if($formques['section']=='family') echo "selected"; ?>>Family</option>
			                                                <option value="business_interest" <?php if($formques['section']=='business_interest') echo "selected"; ?>>Business Interest</option>
			                                                <option value="financial" <?php if($formques['section']=='financial') echo "selected"; ?>>Financial</option>
			                                                <option value="others" <?php if($formques['section']=='others') echo "selected"; ?>>Others</option>
			                                            </select>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="col-md-4">
			                                    <div class="form-group">

			                                        <div class="input-group mb-6">
			                                            <input type="text" id="question<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][question]" value="<?php echo $formques['question_name']; ?>" class="form-control" placeholder="Question" required>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="col-md-3">
			                                    <div class="form-group">
			                                        <!-- <label>Email-Id</label> -->
			                                        <div class="input-group mb-6">
			                                            <select id="questiontype<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][questiontype]" class="form-control" onchange="showhideoptions('dynamicquestions', this.value, <?php echo $i; ?>)" required>
			                                                <option value="" selected disabled>Choose Question Type</option>
			                                                <option value="text" <?php if($formques['question_type']=='Text') echo "selected";  ?>>Text</option>
			                                                <option value="number" <?php if($formques['question_type']=='Integer') echo "selected";  ?>>Number</option>
			                                                <option value="trueorfalse" <?php if($formques['question_type']=='trueorfalse') echo "selected";  ?>>True or False</option>
			                                                <option value="multichoiceoneans" <?php if($formques['question_type']=='Single Select') echo "selected";  ?>>Mutliple choice with one answer</option>
			                                                <option value="multichoicemultians" <?php if($formques['question_type']=='Multi Select') echo "selected";  ?>>Mutliple choice with Multiple answer</option>
			                                            </select>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="col-md-2">
			                                    <div class="form-group">
			                                        <div class="input-group mb-6">
			                                            <select id="category0" name="multipleinput[<?php echo $i; ?>][category]" class="form-control" required>
			                                                <option value="" selected disabled>Choose Category</option>
			                                                <option value="health" <?php if($formques['category']=='health') echo "selected"; ?>>Health</option>
			                                                <option value="education" <?php if($formques['category']=='education') echo "selected"; ?>>Education</option>
			                                                <option value="standard_of_living" <?php if($formques['category']=='standard_of_living') echo "selected"; ?>>Standard of Living</option>
			                                                <option value="skill" <?php if($formques['category']=='skill') echo "selected"; ?>>Skill</option>
			                                                <option value="interest" <?php if($formques['category']=='interest') echo "selected"; ?>>Interest</option>
			                                                <option value="income" <?php if($formques['category']=='income') echo "selected"; ?>>Income</option>
			                                                <option value="none" <?php if($formques['category']=='none') echo "selected"; ?>>None</option>
			                                            </select>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="col-md-1" style="text-align:center;">
			                                    <div class="icheck-primary d-inline">
			                                        <input type="checkbox" id="checkboxPrimary<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][mandatory]" <?php if($formques['compulsory']=='true') echo "checked";  ?>>
			                                        <label for="checkboxPrimary<?php echo $i; ?>">
			                                        </label>
			                                    </div>
			                                </div>
                                            <div class="col-md-2">
                                                <label><b>Chronological order</b></label>
                                            </div>
                                            <div class="col-md-10"></div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="input-group mb-6">
                                                        <input type="number" min="0" id="order<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][order]" value="<?php echo $formques['order']; ?>" class="form-control" placeholder="Order of Questions (number)" required>
                                                    </div>
                                                </div>
                                            </div>
			                                <input type="hidden" name="multipleinput[<?php echo $i; ?>][dbcolumn]" value="<?php echo $formques['db_column']; ?>"/>
			                            </div>
			                            
			                            <div class="row">
			                                <div class="col-md-12">
			                                </div>
			                                <label style="padding-left: 10px;"><b>Answers</b></label>
			                                <div id="dynamicquestions<?php echo $i; ?>" class="col-md-12">
			                                	<?php
			                                		if($formques['question_type']=='Text'){
			                                			?>
			                                				<div class="row">
			                                					<div class="col-md-4">
			                                						<input class="form-control" id="text<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer]" value="" placeholder="Textbox" readonly/>
			                                					</div>
			                                				</div>
			                                			<?php
			                                		}
			                                		if($formques['question_type']=='Integer'){
			                                			?>
			                                				<div class="row">
			                                					<div class="col-md-4">
			                                						<input class="form-control" type="number" id="number<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer]" value="<?php echo $formques['to_validation']; ?>" placeholder="Total Size (i.e. Max numbers allowed)" required/>
			                                					</div>
			                                				</div>
			                                			<?php
			                                		}
			                                		if($formques['question_type']=='Single Select'){
			                                			$optionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$formques['id']."'";
			                                			$optquery = $conn->prepare($optionquery);

    													$optquery->execute();

    													if($optquery->rowCount()>0){
    														$m=0;

    														while($optionsresult = $optquery->fetch(PDO::FETCH_ASSOC)){

    															$casequestion="SELECT id, compulsory, db_column, dep_question_id, dep_select_id, from_validation, max_range, min_range, question_name, question_type, to_validation, section, category, isdisabled, \"order\" FROM public.newquestionnaire WHERE questionnaire_id=$survey_id AND dep_question_id='".$formques['id']."' AND dep_select_id='".$optionsresult['id']."'";

						    									$caseques=$conn->prepare($casequestion);

						    									$caseques->execute();

						    									if($caseques->rowCount()>0){
						    										
						    										$ab=0;
						    										while($case=$caseques->fetch(PDO::FETCH_ASSOC)){
						    											if($ab==0){
						    												?>
						    													<div class="row" style="margin-bottom:1%;">
								                                					<div class="col-md-8">
								                                						<label><b>Option <?php echo $m+1; ?></b></label>
								                                					</div>
								                                					<div class="col-md-4" style="text-align: center;">
								                                						<label><b>Case Condition</b></label>
								                                					</div>
								                                					<div class="col-md-8">
								                                						<input class="form-control" id="option<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][<?php echo $m; ?>]" placeholder="Option<?php echo $m; ?>" value="<?php echo $optionsresult['title']; ?>" required/>
								                                					</div>
								                                					<div class="col-md-4" style="text-align: center;">
								                                						<div class="icheck-primary d-inline">
								                                							<input type="checkbox" id="icheckcase<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>]" onclick="mapquestion('checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>', this.id, 'case<?php echo $m; ?>', <?php echo $i; ?>, 'checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>button', <?php echo $m; ?>)" checked />
								                                							<label for="icheckcase<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>"></label>
								                                						</div>
								                                					</div>
								                                				</div>

								                                				<div class="col-md-12" id="checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>" style="padding-left: 30px;">

								                                				
								                                				
						    												<?php
						    											}
						    											?>
						    											
							    											<div style="padding-left:3%">
							    												<div id="surveycase<?php echo $r; ?>" class="surveynamecase"><br />
																				    <h4>Case Question</h4>
																				    <hr>
																				    <div class="row">
																				        <div class="col-md-2"><label><b>Section</b></label></div>
																				        <div class="col-md-4"><label><b>Question</b></label></div>
																				        <div class="col-md-3"><label><b>Response Type</b></label></div>
																				        <div class="col-md-2"><label><b>Category</b></label></div>
																				        <div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div>
																				    </div>
																				</div>
																				<div class="row">
																			        <div class="col-md-2">
																			            <div class="form-group">
																			                <div class="input-group mb-6"><select id="section'+d+'"
																			                        name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][section]"
																			                        class="form-control" required>
																			                        <option value="" selected disabled>Choose Section</option>
																			                        <option value="personal" <?php if($case['section']=='personal') echo "selected"; ?>>Personal</option>
																			                        <option value="family" <?php if($case['section']=='family') echo "selected"; ?>>Family</option>
																			                        <option value="business_interest" <?php if($case['section']=='business_interest') echo "selected"; ?>>Business Interest</option>
																			                        <option value="financial" <?php if($case['section']=='financial') echo "selected"; ?>>Financial</option>
																			                        <option value="others" <?php if($case['section']=='others') echo "selected"; ?>>Others</option>
																			                    </select></div>
																			            </div>
																			        </div>
																			        <div class="col-md-4">
																			            <div class="form-group">
																			                <div class="input-group mb-6">
																			                	<input type="text" id="question'+d+'"
																			                        name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][question]" value="<?php echo $case['question_name']; ?>" class="form-control" placeholder="Question" required>
																			                </div>
																			            </div>
																			        </div>
																			        <div class="col-md-3">
																			            <div class="form-group">
																			                <div class="input-group mb-6">
																			                	<select id="questiontype'+d+'"
																			                        name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][questiontype]"
																			                        class="form-control"
																			                        onchange="showhideoptionscase('dynamicquestionscase', this.value,'<?php echo $r; ?>', 'option<?php echo $i; ?>', 'case<?php echo $m; ?>', '<?php echo $i; ?>')"
																			                        required>
																			                        <option value="" selected disabled>Choose Question Type</option>
																			                        <option value="text" <?php if($case['question_type']=='Text') echo "selected"; ?>>Text</option>
																			                        <option value="number" <?php if($case['question_type']=='Integer') echo "selected"; ?>>Number</option>
																			                        <option value="trueorfalse" <?php if($case['question_type']=='trueorfalse') echo "selected"; ?>>True or False</option>
																			                        <option value="multichoiceoneans" <?php if($case['question_type']=='Single Select') echo "selected"; ?>>Mutliple choice with one answer</option>
																			                        <option value="multichoicemultians" <?php if($case['question_type']=='Multi Select') echo "selected"; ?>>Mutliple choice with Multiple answer</option>
																			                    </select>
																			                </div>
																			            </div>
																			        </div>
																			        <div class="col-md-2">
																			            <div class="form-group">
																			                <div class="input-group mb-6"><select id="category'+d+'"
																			                        name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][category]"
																			                        class="form-control" required>
																			                        <option value="" selected disabled>Choose Category</option>
																			                        <option value="health" <?php if($case['category']=='health') echo "selected"; ?>>Health</option>
																			                        <option value="education" <?php if($case['category']=='education') echo "selected"; ?>>Education</option>
																			                        <option value="standard_of_living" <?php if($case['category']=='standard_of_living') echo "selected"; ?>>Standard of Living</option>
																			                        <option value="skill" <?php if($case['category']=='skill') echo "selected"; ?>>Skill</option>
																			                        <option value="interest" <?php if($case['category']=='interest') echo "selected"; ?>>Interest</option>
																			                        <option value="income" <?php if($case['category']=='income') echo "selected"; ?>>Income</option>
																			                        <option value="none" <?php if($case['category']=='none') echo "selected"; ?>>None</option>
																			                    </select></div>
																			            </div>
																			        </div>
																			        <div class="col-md-1" style="text-align:center;">
																			            <div class="icheck-primary d-inline">
																			            	<input type="checkbox" id="checkboxPrimaryd'+d+'"
																			                    name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][mandatory]" <?php if($case['compulsory']=='true') echo "checked"; ?>>
																			                <label for="checkboxPrimaryd'+d+'"></label>
																			            </div>
																			            
																			        </div>
                                                                                    <div class="col-md-2">
                                                                                        <label><b>Chronological order</b></label>
                                                                                    </div>
                                                                                    <div class="col-md-10"></div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <div class="input-group mb-6">
                                                                                                <input type="number" min="0" id="order<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][order]" class="form-control" value="<?php echo $case['order']; ?>" placeholder="Order of Questions (number)" required>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-10"></div>
																			        <input type="hidden" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][dbcolumn]" value="<?php echo $case['db_column']; ?>"/>
																			        <label style="padding-left: 10px;"><b>Answers</b></label>
																			        <div id="dynamicquestionscase<?php echo $r; ?>" class="col-md-12">
																			        	<?php
																			        		if($case['question_type']=='Text'){
																			        			?>
																			        				<div class="row">
																			        					<div class="col-md-4">
																			        						<input class="form-control" id="'+value+number+'" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][answers2]" placeholder="Textbox" readonly/>
																			        					</div>
																			        				</div>
																			        			<?php
																			        		}
																			        		if($case['question_type']=='Integer'){
																			        			?>
																			        				<div class="row">
																			        					<div class="col-md-4">
																			        						<input class="form-control" type="number" id="'+value+number+'" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][answers2]" placeholder="Total Size (i.e. Max numbers allowed)" value="<?php echo $case['to_validation']; ?>" required/>
																			        					</div>
																			        				</div>
																			        			<?php
																			        		}
																			        		if($case['question_type']=='Single Select'){
																			        			?>
																			        				
																			        				<?php
																			        					$caseoptionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$case['id']."'";

																			        					$caseoptquery = $conn->prepare($caseoptionquery);

							    																		$caseoptquery->execute();
							    																		if($caseoptquery->rowCount()>0){
																			    							$y=0;
																			    							while($caseoptionsresult = $caseoptquery->fetch(PDO::FETCH_ASSOC)){
																			    						?>	
																			    							<div class="row">
																			    								<div class="col-md-6">
																			    									<label><b>Option <?php echo $y+1; ?></b></label>
																			    								</div>
																			    							</div>
																			    							<div class="row" style="margin-bottom:1%;">
																												<div class="col-md-6">
																													<input class="form-control" id="option'+i+value+number+'" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][answers2][option<?php echo $y; ?>][<?php echo $y; ?>]" placeholder="Option <?php echo $y; ?>" value="<?php echo $caseoptionsresult['title']; ?>" required />
																												</div>
																											</div>
																			    						<?php
																			    							$y++;
																			    							}
																			    						}
																			        				?>
																			        				<hr/>
																			        			<?php
																			        		}
																			        		if($case['question_type']=='Multi Select'){

																			        		}
																			        	?>
																			        </div>
																			    </div>
																			</div>
																		
																		
						    											<?php
						    											$r++;
						    											$ab++;
						    										}
						    										?>
						    										<script type="text/javascript">
						    											d='<?php echo $r; ?>';
						    										</script>
						    											</div>
						    											<div class="row" id="checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>button">
																			<div class="col-md-9"></div>
																			<div class="col-md-3">
																			   	<button type="button" id="addItemcase" class="btn btn-primary form-control" onclick="addcasequestion('checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>', 'case<?php echo $m; ?>', 'option<?php echo $m; ?>', '<?php echo $i; ?>', '<?php echo $r; ?>' )">Add New Case Question</button>
																			</div>
																		</div>
																		<br/>
						    										<?php
						    									}
						    									else{
						    										?>
						                                				<div class="row" style="margin-bottom:1%;">
						                                					<div class="col-md-8">
						                                						<label><b>Option <?php echo $m+1; ?></b></label>
						                                					</div>
						                                					<div class="col-md-4" style="text-align: center;">
						                                						<label><b>Case Condition</b></label>
						                                					</div>
						                                					<div class="col-md-8">
						                                						<input class="form-control" id="option<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][<?php echo $m; ?>]" placeholder="Option<?php echo $m; ?>" value="<?php echo $optionsresult['title']; ?>" required/>
						                                					</div>
						                                					<div class="col-md-4" style="text-align: center;">
						                                						<div class="icheck-primary d-inline">
						                                							<input type="checkbox" id="icheckcase<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>]" onclick="mapquestion('checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>', this.id, 'case<?php echo $m; ?>', 'option<?php echo $m; ?>', <?php echo $i; ?>, 'checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>button', <?php echo $m; ?>)">
						                                							<label for="icheckcase<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>"></label>
						                                						</div>
						                                					</div>
						                                				</div>
						                                				<div class="col-md-12" id="checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>" style="padding-left: 30px;">
						                                				</div>
						                                				<div class="row" id="checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>button" style="display: none;">
																				<div class="col-md-9"></div>
																				<div class="col-md-3">
																					<button type="button" id="addItemcase" class="btn btn-primary form-control" onclick="addcasequestion('checkbox<?php echo $m; ?><?php echo "multichoiceoneans"; ?><?php echo $i; ?>', 'case<?php echo $m; ?>', 'option<?php echo $m; ?>', 'number<?php echo $m; ?>' , '<?php echo $m; ?>' )">Add New Case Question</button>
																				</div>
																		</div>
						                                			<?php
						    									}
					                                			
					                                			$m++;
			                                				}
			                                				
			                                			}
			                                		}
			                                		if($formques['question_type']=='Multi Select'){
                                                        $optionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$formques['id']."'";
                                                        $optquery = $conn->prepare($optionquery);

                                                        $optquery->execute();

                                                        if($optquery->rowCount()>0){
                                                            $m=0;

                                                            while($optionsresult = $optquery->fetch(PDO::FETCH_ASSOC)){

                                                                $casequestion="SELECT id, compulsory, db_column, dep_question_id, dep_select_id, from_validation, max_range, min_range, question_name, question_type, to_validation, section, category, isdisabled,\"order\" FROM public.newquestionnaire WHERE questionnaire_id=$survey_id AND dep_question_id='".$formques['id']."' AND dep_select_id='".$optionsresult['id']."'";

                                                                $caseques=$conn->prepare($casequestion);

                                                                $caseques->execute();

                                                                if($caseques->rowCount()>0){
                                                                    
                                                                    $ab=0;
                                                                    while($case=$caseques->fetch(PDO::FETCH_ASSOC)){
                                                                        if($ab==0){
                                                                            ?>
                                                                                <div class="row" style="margin-bottom:1%;">
                                                                                    <div class="col-md-8">
                                                                                        <label><b>Option <?php echo $m+1; ?></b></label>
                                                                                    </div>
                                                                                    <div class="col-md-4" style="text-align: center;">
                                                                                        <label><b>Case Condition</b></label>
                                                                                    </div>
                                                                                    <div class="col-md-8">
                                                                                        <input class="form-control" id="option<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][<?php echo $m; ?>]" placeholder="Option<?php echo $m; ?>" value="<?php echo $optionsresult['title']; ?>" required/>
                                                                                    </div>
                                                                                    <div class="col-md-4" style="text-align: center;">
                                                                                        <div class="icheck-primary d-inline">
                                                                                            <input type="checkbox" id="icheckcase<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>]" onclick="mapquestion('checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>', this.id, 'case<?php echo $m; ?>', <?php echo $i; ?>, 'checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>button', <?php echo $m; ?>)" checked />
                                                                                            <label for="icheckcase<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>"></label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12" id="checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>" style="padding-left: 30px;">

                                                                                
                                                                                
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        
                                                                            <div style="padding-left:3%">
                                                                                <div id="surveycase<?php echo $r; ?>" class="surveynamecase"><br />
                                                                                    <h4>Case Question</h4>
                                                                                    <hr>
                                                                                    <div class="row">
                                                                                        <div class="col-md-2"><label><b>Section</b></label></div>
                                                                                        <div class="col-md-4"><label><b>Question</b></label></div>
                                                                                        <div class="col-md-3"><label><b>Response Type</b></label></div>
                                                                                        <div class="col-md-2"><label><b>Category</b></label></div>
                                                                                        <div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <div class="input-group mb-6"><select id="section'+d+'"
                                                                                                    name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][section]"
                                                                                                    class="form-control" required>
                                                                                                    <option value="" selected disabled>Choose Section</option>
                                                                                                    <option value="personal" <?php if($case['section']=='personal') echo "selected"; ?>>Personal</option>
                                                                                                    <option value="family" <?php if($case['section']=='family') echo "selected"; ?>>Family</option>
                                                                                                    <option value="business_interest" <?php if($case['section']=='business_interest') echo "selected"; ?>>Business Interest</option>
                                                                                                    <option value="financial" <?php if($case['section']=='financial') echo "selected"; ?>>Financial</option>
                                                                                                    <option value="others" <?php if($case['section']=='others') echo "selected"; ?>>Others</option>
                                                                                                </select></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <div class="input-group mb-6">
                                                                                                <input type="text" id="question'+d+'"
                                                                                                    name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][question]" value="<?php echo $case['question_name']; ?>" class="form-control" placeholder="Question" required>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <div class="input-group mb-6">
                                                                                                <select id="questiontype'+d+'"
                                                                                                    name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][questiontype]"
                                                                                                    class="form-control"
                                                                                                    onchange="showhideoptionscase('dynamicquestionscase', this.value,'<?php echo $r; ?>', 'option<?php echo $i; ?>', 'case<?php echo $m; ?>', '<?php echo $i; ?>')"
                                                                                                    required>
                                                                                                    <option value="" selected disabled>Choose Question Type</option>
                                                                                                    <option value="text" <?php if($case['question_type']=='Text') echo "selected"; ?>>Text</option>
                                                                                                    <option value="number" <?php if($case['question_type']=='Integer') echo "selected"; ?>>Number</option>
                                                                                                    <option value="trueorfalse" <?php if($case['question_type']=='trueorfalse') echo "selected"; ?>>True or False</option>
                                                                                                    <option value="multichoicemultians" <?php if($case['question_type']=='Single Select') echo "selected"; ?>>Mutliple choice with one answer</option>
                                                                                                    <option value="multichoicemultians" <?php if($case['question_type']=='Multi Select') echo "selected"; ?>>Mutliple choice with Multiple answer</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <div class="input-group mb-6"><select id="category'+d+'"
                                                                                                    name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][category]"
                                                                                                    class="form-control" required>
                                                                                                    <option value="" selected disabled>Choose Category</option>
                                                                                                    <option value="health" <?php if($case['category']=='health') echo "selected"; ?>>Health</option>
                                                                                                    <option value="education" <?php if($case['category']=='education') echo "selected"; ?>>Education</option>
                                                                                                    <option value="standard_of_living" <?php if($case['category']=='standard_of_living') echo "selected"; ?>>Standard of Living</option>
                                                                                                    <option value="skill" <?php if($case['category']=='skill') echo "selected"; ?>>Skill</option>
                                                                                                    <option value="interest" <?php if($case['category']=='interest') echo "selected"; ?>>Interest</option>
                                                                                                    <option value="income" <?php if($case['category']=='income') echo "selected"; ?>>Income</option>
                                                                                                    <option value="none" <?php if($case['category']=='none') echo "selected"; ?>>None</option>
                                                                                                </select></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1" style="text-align:center;">
                                                                                        <div class="icheck-primary d-inline">
                                                                                            <input type="checkbox" id="checkboxPrimaryd'+d+'"
                                                                                                name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][mandatory]" <?php if($case['compulsory']=='true') echo "checked"; ?>>
                                                                                            <label for="checkboxPrimaryd'+d+'"></label>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <label><b>Chronological order</b></label>
                                                                                    </div>
                                                                                    <div class="col-md-10"></div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <div class="input-group mb-6">
                                                                                                <input type="number" min="0" id="order<?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][order]" class="form-control" value="<?php echo $case['order']; ?>" placeholder="Order of Questions (number)" required>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-10"></div>
                                                                                    <input type="hidden" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][dbcolumn]" value="<?php echo $case['db_column']; ?>"/>
                                                                                    <label style="padding-left: 10px;"><b>Answers</b></label>
                                                                                    <div id="dynamicquestionscase<?php echo $r; ?>" class="col-md-12">
                                                                                        <?php
                                                                                            if($case['question_type']=='Text'){
                                                                                                ?>
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-4">
                                                                                                            <input class="form-control" id="'+value+number+'" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][answers2]" placeholder="Textbox" readonly/>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                <?php
                                                                                            }
                                                                                            if($case['question_type']=='Integer'){
                                                                                                ?>
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-4">
                                                                                                            <input class="form-control" type="number" id="'+value+number+'" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][answers2]" placeholder="Total Size (i.e. Max numbers allowed)" value="<?php echo $case['to_validation']; ?>" required/>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                <?php
                                                                                            }
                                                                                            if($case['question_type']=='Single Select'){
                                                                                                ?>
                                                                                                    
                                                                                                    <?php
                                                                                                        $caseoptionquery="SELECT id, title, question_id FROM public.options WHERE question_id='".$case['id']."'";

                                                                                                        $caseoptquery = $conn->prepare($caseoptionquery);

                                                                                                        $caseoptquery->execute();
                                                                                                        if($caseoptquery->rowCount()>0){
                                                                                                            $y=0;
                                                                                                            while($caseoptionsresult = $caseoptquery->fetch(PDO::FETCH_ASSOC)){
                                                                                                        ?>  
                                                                                                            <div class="row">
                                                                                                                <div class="col-md-6">
                                                                                                                    <label><b>Option <?php echo $y+1; ?></b></label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="row" style="margin-bottom:1%;">
                                                                                                                <div class="col-md-6">
                                                                                                                    <input class="form-control" id="option'+i+value+number+'" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>][<?php echo $r; ?>][answers2][option<?php echo $y; ?>][<?php echo $y; ?>]" placeholder="Option <?php echo $y; ?>" value="<?php echo $caseoptionsresult['title']; ?>" required />
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        <?php
                                                                                                            $y++;
                                                                                                            }
                                                                                                        }
                                                                                                    ?>
                                                                                                    <hr/>
                                                                                                <?php
                                                                                            }
                                                                                            if($case['question_type']=='Multi Select'){

                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        
                                                                        
                                                                        <?php
                                                                        $r++;
                                                                        $ab++;
                                                                    }
                                                                    ?>
                                                                    <script type="text/javascript">
                                                                        d='<?php echo $r; ?>';
                                                                    </script>
                                                                        </div>
                                                                        <div class="row" id="checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>button">
                                                                            <div class="col-md-9"></div>
                                                                            <div class="col-md-3">
                                                                                <button type="button" id="addItemcase" class="btn btn-primary form-control" onclick="addcasequestion('checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>', 'case<?php echo $m; ?>', 'option<?php echo $m; ?>', '<?php echo $i; ?>', '<?php echo $r; ?>' )">Add New Case Question</button>
                                                                            </div>
                                                                        </div>
                                                                        <br/>
                                                                    <?php
                                                                }
                                                                else{
                                                                    ?>
                                                                        <div class="row" style="margin-bottom:1%;">
                                                                            <div class="col-md-8">
                                                                                <label><b>Option <?php echo $m+1; ?></b></label>
                                                                            </div>
                                                                            <div class="col-md-4" style="text-align: center;">
                                                                                <label><b>Case Condition</b></label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <input class="form-control" id="option<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][<?php echo $m; ?>]" placeholder="Option<?php echo $m; ?>" value="<?php echo $optionsresult['title']; ?>" required/>
                                                                            </div>
                                                                            <div class="col-md-4" style="text-align: center;">
                                                                                <div class="icheck-primary d-inline">
                                                                                    <input type="checkbox" id="icheckcase<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>" name="multipleinput[<?php echo $i; ?>][answer][option<?php echo $m; ?>][case<?php echo $m; ?>]" onclick="mapquestion('checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>', this.id, 'case<?php echo $m; ?>', 'option<?php echo $m; ?>', <?php echo $i; ?>, 'checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>button', <?php echo $m; ?>)">
                                                                                    <label for="icheckcase<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>"></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12" id="checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>" style="padding-left: 30px;">
                                                                        </div>
                                                                        <div class="row" id="checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>button" style="display: none;">
                                                                                <div class="col-md-9"></div>
                                                                                <div class="col-md-3">
                                                                                    <button type="button" id="addItemcase" class="btn btn-primary form-control" onclick="addcasequestion('checkbox<?php echo $m; ?><?php echo "multichoicemultians"; ?><?php echo $i; ?>', 'case<?php echo $m; ?>', 'option<?php echo $m; ?>', 'number<?php echo $m; ?>' , '<?php echo $m; ?>' )">Add New Case Question</button>
                                                                                </div>
                                                                        </div>
                                                                    <?php
                                                                }
                                                                
                                                                $m++;
                                                            }
                                                            
                                                        }
			                                		}
			                                	?>
			                                </div>
			                            </div>
			                            <!-- <div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;">
										    <span class="text-danger" id="disablequestion"><i class="far fa-eye-slash"></i> Disable this Question</span>
										</div> -->
			                            <hr>
    								<?php
    								$i++;
    							}

                        	?>
                            
                        </div>
                        <br />
                        <br />
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <input type="button" id="addItem" class="btn btn-primary form-control" value="Add New Question" />
                                <input type="hidden" id="hiddenval" value="<?php echo $i; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <a class="btn btn-default form-control" href="home.php" style="margin-right: 20px;">Cancel</a>
                                </div>
                            </div>
                            <div class="col-md-10"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>