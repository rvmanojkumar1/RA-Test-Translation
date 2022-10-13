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

                    $(nwdyid).append('<div class="row" style="margin-bottom:1%;"><div class="col-md-8"><label><b>Option'+(parseInt(i)+1)+'</b></label></div><div class="col-md-4" style="text-align: center;"><label><b>Case Condition</b></label></div><div class="col-md-8"><input class="form-control" id="option'+i+value+number+'" name="multipleinput['+number+'][answer][option'+i+']['+i+']" placeholder="Option '+(parseInt(i)+1)+'" required/></div><div class="col-md-4" style="text-align: center;"><div class="icheck-primary d-inline"><input type="checkbox" id="icheckcase'+i+value+number+'" name="multipleinput['+number+'][answer][option'+i+'][case'+i+']" onclick="mapquestion('+"'"+'checkbox'+i+value+number+"'"+', this.id, '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+', '+"'checkbox"+i+value+number+"button'"+', '+i+')"><label for="icheckcase'+i+value+number+'"></label></div></div></div><div class="col-md-12" id="checkbox'+i+value+number+'" style="padding-left: 30px;"></div><div class="row" id="checkbox'+i+value+number+'button"><div class="col-md-9"></div><div class="col-md-3"><button type="button" id="addItem" class="btn btn-primary form-control" onclick="addcasequestion('+"'"+'checkbox'+i+value+number+"'"+', '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+', '+i+')">Add New Case Question</button></div></div><hr>');

                    var butid='checkbox'+i+value+number+'button';

                    butid='#'+butid;
                    $(butid).hide();
                }
            }
        }

        function mapquestion(optioncaseid, checkid, caseid, optionid, number, casebuttonid, i){
            debugger;

            var newid='#'+optioncaseid;

            var buttonid='#'+casebuttonid;

            if (document.getElementById(checkid).checked)
            {
                $(buttonid).show();

                $(newid).append('<div id="surveycase'+d+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-2"><label><b>Section</b></label></div><div class="col-md-4"><label><b>Question</b></label></div><div class="col-md-3"><label><b>Response Type</b></label></div><div class="col-md-2"><label><b>Category</b></label></div><div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div></div><div class="row"><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="section'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][section]" class="form-control" required><option value="" selected disabled>Choose Section</option><option value="personal">Personal</option><option value="family">Family</option><option value="business_interest">Business Interest</option><option value="financial">Financial</option><option value="others">Others</option></select></div></div></div><div class="col-md-4"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][questiontype]" class="form-control" onchange="showhideoptionscase('+"'dynamicquestionscase'"+', this.value,'+d+', '+"'"+optionid+"'"+', '+"'"+caseid+"'"+', '+number+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="category'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][category]" class="form-control" required><option value="" selected disabled>Choose Category</option><option value="health">Health</option><option value="education">Education</option><option value="standard_of_living">Standard of Living</option><option value="skill">Skill</option><option value="interest">Interest</option><option value="income">Income</option><option value="none">None</option></select></div></div></div><div class="col-md-1" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryd'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][mandatory]"><label for="checkboxPrimaryd'+d+'"></label></div></div><div class="col-md-2"><label><b>Chronological order</b></label></div><div class="col-md-10"></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><input type="number" min="0" id="order'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][order]" value="" class="form-control" placeholder="Order of Questions (number)" required></div></div></div><div class="col-md-10"></div><label style="padding-left: 10px;"><b>Answers</b></label><div id="dynamicquestionscase'+d+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
                d++;
            }
            else {
                $(newid).empty();
                $(buttonid).hide();
            }
        }

        function addcasequestion(optioncaseid, caseid, optionid, number, i){

            debugger;

            var optid='#'+optioncaseid;

            $(optid).append('<div id="surveycase'+d+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-2"><label><b>Section</b></label></div><div class="col-md-4"><label><b>Question</b></label></div><div class="col-md-3"><label><b>Response Type</b></label></div><div class="col-md-2"><label><b>Category</b></label></div><div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div></div><div class="row"><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="section'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][section]" class="form-control" required><option value="" selected disabled>Choose Section</option><option value="personal">Personal</option><option value="family">Family</option><option value="business_interest">Business Interest</option><option value="financial">Financial</option><option value="others">Others</option></select></div></div></div><div class="col-md-4"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][questiontype]" class="form-control" onchange="showhideoptionscase('+"'dynamicquestionscase'"+', this.value,'+d+', '+"'"+optionid+"'"+', '+"'"+caseid+"'"+', '+"'"+number+"'"+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="category'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][category]" class="form-control" required><option value="" selected disabled>Choose Category</option><option value="health">Health</option><option value="education">Education</option><option value="standard_of_living">Standard of Living</option><option value="skill">Skill</option><option value="interest">Interest</option><option value="income">Income</option><option value="none">None</option></select></div></div></div><div class="col-md-1" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryd'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][mandatory]"><label for="checkboxPrimaryd'+d+'"></label></div></div><div class="col-md-2"><label><b>Chronological order</b></label></div><div class="col-md-10"></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><input type="number" min="0" id="order'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][order]" value="" class="form-control" placeholder="Order of Questions (number)" required></div></div></div><div class="col-md-10"></div><label style="padding-left: 10px;"><b>Answers</b></label> <div id="dynamicquestionscase'+d+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>'); 
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
                ++s;
                $("#dynamic").append('<div id="survey'+s+'" class="surveyname"><br /><h4>Next Question</h4><hr><div class="row"><div class="col-md-2"><label><b>Section</b></label></div><div class="col-md-4"><label><b>Question</b></label></div><div class="col-md-3"><label><b>Response Type</b></label></div><div class="col-md-2"><label><b>Category</b></label></div><div class="col-md-1" style="text-align:center;"><label><b>Mandatory</b></label></div></div><div class="row"><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="section'+s+'" name="multipleinput['+s+'][section]" class="form-control" required><option value="" selected disabled>Choose Section</option><option value="personal">Personal</option><option value="family">Family</option><option value="business_interest">Business Interest</option><option value="financial">Financial</option><option value="others">Others</option></select></div></div></div><div class="col-md-4"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+s+'" name="multipleinput['+s+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+s+'" name="multipleinput['+s+'][questiontype]" class="form-control" onchange="showhideoptions('+"'dynamicquestions'"+', this.value,'+s+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><select id="category'+s+'" name="multipleinput['+s+'][category]" class="form-control" required><option value="" selected disabled>Choose Category</option><option value="health">Health</option><option value="education">Education</option><option value="standard_of_living">Standard of Living</option><option value="skill">Skill</option><option value="interest">Interest</option><option value="income">Income</option><option value="none">None</option></select></div></div></div><div class="col-md-1" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimarys'+s+'" name="multipleinput['+s+'][mandatory]"><label for="checkboxPrimarys'+s+'"></label></div></div><div class="col-md-2"><label><b>Chronological order</b></label></div><div class="col-md-10"></div><div class="col-md-2"><div class="form-group"><div class="input-group mb-6"><input type="number" min="0" id="question'+s+'" name="multipleinput['+s+'][order]" value="" class="form-control" placeholder="Order of Questions (number)" required></div></div></div><div class="col-md-10"></div><label style="padding-left: 10px;"><b>Answers</b></label><div id="dynamicquestions'+s+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitem"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
            });

            $(document).on('click', '#removeitem', function () {
                $(this).closest('.surveyname').remove();
            });


            $(document).on('click', '#removeitemcase', function () {
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
                <h2 class="m-2 text-dark">Setup Survey Questionnaire</h2>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="home.php">Survey Questionnaire</a></li>
                    <li class="breadcrumb-item active"><a href="create.php">Create</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">New Survey</h5>
                </div>
                <div class="card-body p-3">
                    <form method="post" action="formsubmit.php">
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
                                                // $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=tgptestadmin password=y5BoxfPmLmkWju' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id";
                                                
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Survey Name</b></label>
                                    <div class="input-group mb-6">
                                        <input type="text" name="survey" value="" class="form-control" placeholder="Survey Name" maxlength="30" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Description (optional)</b></label>
                                    <div class="input-group mb-6">
                                        <textarea class="form-control" rows="1" placeholder="Enter Description" name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <hr>
                        <h4>Questions</h4>
                        <br />
                        <div id=dynamic>
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
                                            <select id="section0" name="multipleinput[0][section]" class="form-control" required>
                                                <option value="" selected disabled>Choose Section</option>
                                                <option value="personal">Personal</option>
                                                <option value="family">Family</option>
                                                <option value="business_interest">Business Interest</option>
                                                <option value="financial">Financial</option>
                                                <option value="others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group mb-6">
                                            <input type="text" id="question0" name="multipleinput[0][question]" value="" class="form-control" placeholder="Question" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <!-- <label>Email-Id</label> -->
                                        <div class="input-group mb-6">
                                            <select id="questiontype0" name="multipleinput[0][questiontype]" class="form-control" onchange="showhideoptions('dynamicquestions', this.value, 0)" required>
                                                <option value="" selected disabled>Choose Response Type</option>
                                                <option value="text">Text</option>
                                                <option value="number">Number</option>
                                                <option value="trueorfalse">True or False</option>
                                                <option value="multichoiceoneans">Mutliple choice with one answer</option>
                                                <option value="multichoicemultians">Mutliple choice with Multiple answer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="input-group mb-6">
                                            <select id="category0" name="multipleinput[0][category]" class="form-control" required>
                                                <option value="" selected disabled>Choose Category</option>
                                                <option value="health">Health</option>
                                                <option value="education">Education</option>
                                                <option value="standard_of_living">Standard of Living</option>
                                                <option value="skill">Skill</option>
                                                <option value="interest">Interest</option>
                                                <option value="income">Income</option>
                                                <option value="none">None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1" style="text-align:center;">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimary0" name="multipleinput[0][mandatory]">
                                        <label for="checkboxPrimary0">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label><b>Chronological order</b></label>
                                </div>
                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <!-- <label>Email-Id</label> -->
                                        <div class="input-group mb-6">
                                            <input type="number" min="0" id="order0" name="multipleinput[0][order]" value="" class="form-control" placeholder="Order of Questions (number)" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                </div>
                                <label style="padding-left: 10px;"><b>Answers</b></label>
                                <div id="dynamicquestions0" class="col-md-12">

                                </div>
                            </div>
                        </div>
                        <br />
                        <br />
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <input type="button" id="addItem" class="btn btn-primary form-control" value="Add New Question" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label><b>Translate Language</b></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="language" class="form-control" required>
                                        <option value="english" selected>English</option>
                                        <option value="kannada">Kannada</option>
                                        <option value="bengali">Bengali</option>
                                        <option value="marathi">Marathi</option>
										<option value="hindi">Hindi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br/>
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