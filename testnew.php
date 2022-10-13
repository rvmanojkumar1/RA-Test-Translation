<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	 <?php
        include_once('links.php');
        include_once('database.php');
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
                $(dyid).append('<div class="row"><div class="col-md-4"><select class="form-control" id="'+value+number+'" name="multipleinput['+number+'][answer]"><option selected disabled>The Following are the options</option><option value="No" disabled>No</option><option value="Yes" disabled>Yes</option></select></div></div>');
            }
            if(value=="multichoiceoneans"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" name="multipleinput['+number+'][answer]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" onchange="addoptions('+"'"+id+number+"'"+', this.value, '+"'"+value+"'"+', '+"'"+number+"'"+')" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary">Generate</button></div></div><div class="col-md-9"></div></div>');
                // $("#dynamicquestions"+number).append('<div class="row"><div class="col-md-6"><input class="form-control" id="option0'+value+number+'" name="multipleinput['+number+'][option0'+value+']" placeholder="option 1"/></div><div class="col-md-6"><input class="form-control" id="option1'+value+number+'" name="multipleinput['+number+'][option1'+value+']" placeholder="option 2"/></div><br/><br/><br/><div class="col-md-6"><input class="form-control" id="option2'+value+number+'" name="multipleinput['+number+'][option2'+value+']" placeholder="option 3"/></div><div class="col-md-6"><input class="form-control" id="option3'+value+number+'" name="multipleinput['+number+'][option3'+value+']" placeholder="option 4"/></div></div>');
            }
            if(value=="multichoicemultians"){
                
                var dyid='#'+id+number;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" name="multipleinput['+number+'][answer]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" onchange="addoptions('+"'"+id+number+"'"+', this.value, '+"'"+value+"'"+', '+"'"+number+"'"+')" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary">Generate</button></div></div><div class="col-md-9"></div></div>');
            }
        }

        function addoptions(id, count, value, number){
        	debugger;

            var nwdyid='#'+id;
            $(nwdyid).empty();
            for(var i=0; i<count; i++){
                $(nwdyid).append('<div class="row" style="margin-bottom:1%;"><div class="col-md-8"><label><b>Option'+(parseInt(i)+1)+'</b></label></div><div class="col-md-4" style="text-align: center;"><label><b>Case Condition</b></label></div><div class="col-md-8"><input class="form-control" id="option'+i+value+number+'" name="multipleinput['+number+'][answer][option'+i+']" placeholder="Option '+(parseInt(i)+1)+'" required/></div><div class="col-md-4" style="text-align: center;"><div class="icheck-primary d-inline"><input type="checkbox" id="icheckcase'+i+value+number+'" name="multipleinput['+number+'][answer][option'+i+'][case'+i+']" onclick="mapquestion('+"'"+'checkbox'+i+value+number+"'"+', this.id, '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+', '+"'checkbox"+i+value+number+"button'"+')"><label for="icheckcase'+i+value+number+'"></label></div></div></div><div class="col-md-12" id="checkbox'+i+value+number+'" style="padding-left: 30px;"></div><div class="row" id="checkbox'+i+value+number+'button"><div class="col-md-9"></div><div class="col-md-3"><button type="button" id="addItem" class="btn btn-primary form-control" onclick="addcasequestion('+"'"+'checkbox'+i+value+number+"'"+', '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+')">Add New Case Question</button></div></div><hr>');

                var butid='checkbox'+i+value+number+'button';

                butid='#'+butid;
                $(butid).hide();
            }
        }

        function mapquestion(optioncaseid, checkid, caseid, optionid, number, casebuttonid){
        	debugger;

	        var newid='#'+optioncaseid;

	        var buttonid='#'+casebuttonid;

	        if (document.getElementById(checkid).checked)
	        {
	        	$(buttonid).show();

	        	$(newid).append('<div id="surveycase'+d+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-7"><label>Question</label></div><div class="col-md-3"><label>Question Type</label></div><div class="col-md-2" style="text-align:center;"><label>Mandatory</label></div></div><div class="row"><div class="col-md-7"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][questiontype]" class="form-control" onchange="showhideoptionscase('+"'dynamicquestionscase'"+', this.value,'+d+', '+"'"+optionid+"'"+', '+"'"+caseid+"'"+', '+number+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryd'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][mandatory]"><label for="checkboxPrimaryd'+d+'"></label></div></div><label style="padding-left: 10px;">Answers</label><div id="dynamicquestionscase'+d+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
	        	d++;
	        }
	        else {
	            $(newid).empty();
	            $(buttonid).hide();
	        }
        }

        function addcasequestion(optioncaseid, caseid, optionid, number){

        	debugger;

        	var optid='#'+optioncaseid;

        	$(optid).append('<div id="surveycase'+d+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-7"><label>Question</label></div><div class="col-md-3"><label>Question Type</label></div><div class="col-md-2" style="text-align:center;"><label>Mandatory</label></div></div><div class="row"><div class="col-md-7"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][questiontype]" class="form-control" onchange="showhideoptionscase('+"'dynamicquestionscase'"+', this.value,'+d+', '+"'"+number+"'"+', '+"'"+caseid+"'"+', '+"'"+optionid+"'"+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryd'+d+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+d+'][mandatory]"><label for="checkboxPrimaryd'+d+'"></label></div></div><label style="padding-left: 10px;">Answers</label><div id="dynamicquestionscase'+d+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
        	d++;
        }


        function showhideoptionscase(id, value, newnumber, optionid, caseid, number){
            debugger;
            if(value=="text"){
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" id="'+value+number+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+newnumber+'][answers2]" placeholder="Textbox" readonly/></div></div>');
            }
            if(value=="number"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" type="number" id="'+value+number+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+newnumber+'][answers2]" placeholder="Total Size (i.e. Max numbers allowed)" required/></div></div>');
            }
            if(value=="trueorfalse"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><select class="form-control" id="'+value+number+'" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+newnumber+'][answers2]"><option selected disabled>The Following are the options</option><option value="No" disabled>No</option><option value="Yes" disabled>Yes</option></select></div></div>');
            }
            if(value=="multichoiceoneans"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+newnumber+'][answers2]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" onchange="addoptionsnew('+"'"+id+newnumber+"'"+', this.value, '+"'"+value+"'"+', '+"'"+newnumber+"'"+', '+"'"+optionid+"'"+', '+"'"+caseid+"'"+', '+"'"+number+"'"+')" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary">Generate</button></div></div><div class="col-md-9"></div></div>');
              
            }
            if(value=="multichoicemultians"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" name="multipleinput['+number+'][answer]['+optionid+']['+caseid+']['+newnumber+'][answers2]" id="count'+value+number+'" class="form-control" placeholder="Total Number of Options Needed" onchange="addoptionsnew('+"'"+id+newnumber+"'"+', this.value, '+"'"+value+"'"+', '+"'"+newnumber+"'"+', '+"'"+optionid+"'"+', '+"'"+caseid+"'"+', '+"'"+number+"'"+')" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary">Generate</button></div></div><div class="col-md-9"></div></div>');
            }
        }

        function addoptionsnew(id, count, value, number, oldoptionid, oldcaseid, oldnumber){
            var nwdyid='#'+id;
            $(nwdyid).empty();
            for(var i=0; i<count; i++){
                $(nwdyid).append('<div class="row" style="margin-bottom:1%;"><div class="col-md-8"><label><b>Option'+(parseInt(i)+1)+'</b></label></div><div class="col-md-4" style="text-align: center;"><label><b>Case Condition</b></label></div><div class="col-md-8"><input class="form-control" id="option'+i+value+number+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2][option'+i+']" placeholder="Option '+(parseInt(i)+1)+'" required/></div><div class="col-md-4" style="text-align: center;"><div class="icheck-primary d-inline"><input type="checkbox" id="icheckcasenew'+i+value+number+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2][option'+i+'][case'+i+']" onclick="mapquestionnew('+"'"+'checkboxnew'+i+value+number+"'"+', this.id, '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+', '+"'checkboxnew"+i+value+number+"button'"+','+"'"+oldoptionid+"'"+','+"'"+oldcaseid+"'"+','+"'"+oldnumber+"'"+')"><label for="icheckcasenew'+i+value+number+'"></label></div></div></div><div class="col-md-12" id="checkboxnew'+i+value+number+'" style="padding-left: 45px;"></div><div class="row" id="checkboxnew'+i+value+number+'button"><div class="col-md-9"></div><div class="col-md-3"><button type="button" id="addItem" class="btn btn-primary form-control" onclick="addcasequestionnew('+"'"+'checkboxnew'+i+value+number+"'"+', '+"'case"+i+"'"+', '+"'option"+i+"'"+', '+"'"+number+"'"+','+"'"+oldoptionid+"'"+','+"'"+oldcaseid+"'"+','+"'"+oldnumber+"'"+')">Add New Case Question</button></div></div><hr>');

                var butid='checkboxnew'+i+value+number+'button';

                butid='#'+butid;
                $(butid).hide();
            }
        }

        function mapquestionnew(optioncaseid, checkid, caseid, optionid, number, casebuttonid, oldoptionid, oldcaseid, oldnumber){
        	debugger;

	        var newid='#'+optioncaseid;

	        var buttonid='#'+casebuttonid;

	        if (document.getElementById(checkid).checked)
	        {
	        	$(buttonid).show();

	        	$(newid).append('<div id="surveycasenew'+g+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-7"><label>Question</label></div><div class="col-md-3"><label>Question Type</label></div><div class="col-md-2" style="text-align:center;"><label>Mandatory</label></div></div><div class="row"><div class="col-md-7"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+g+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answer]['+optionid+']['+caseid+']['+g+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+g+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2]['+optionid+']['+caseid+']['+g+'][questiontype]" class="form-control" onchange="showhideoptionscasenew('+"'dynamicquestionscasenew'"+', this.value,'+g+','+number+', '+"'"+oldnumber+"'"+', '+"'"+caseid+"'"+', '+"'"+optionid+"'"+', '+"'"+oldcaseid+"'"+', '+"'"+oldoptionid+"'"+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryg'+g+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answers2]['+optionid+']['+caseid+']['+g+'][mandatory]"><label for="checkboxPrimaryg'+g+'"></label></div></div><label style="padding-left: 10px;">Answers</label><div id="dynamicquestionscasenew'+g+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
	        	g++;
	        }
	        else {
	            $(newid).empty();
	            $(buttonid).hide();
	        }
        }

        function addcasequestionnew(optioncaseid, caseid, optionid, number, oldoptionid, oldcaseid, oldnumber){

        	var optid='#'+optioncaseid;

        	$(optid).append('<div id="surveycase'+g+'" class="surveynamecase"><br /><h4>Case Question</h4><hr><div class="row"><div class="col-md-7"><label>Question</label></div><div class="col-md-3"><label>Question Type</label></div><div class="col-md-2" style="text-align:center;"><label>Mandatory</label></div></div><div class="row"><div class="col-md-7"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+g+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answer]['+optionid+']['+caseid+']['+g+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+g+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answer]['+optionid+']['+caseid+']['+g+'][questiontype]" class="form-control" onchange="showhideoptionscasenew('+"'dynamicquestionscasenew'"+', this.value,'+g+','+number+', '+"'"+oldnumber+"'"+', '+"'"+caseid+"'"+', '+"'"+optionid+"'"+', '+"'"+oldcaseid+"'"+', '+"'"+oldoptionid+"'"+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimaryg'+g+'" name="multipleinput['+oldnumber+'][answer]['+oldoptionid+']['+oldcaseid+']['+number+'][answer]['+optionid+']['+caseid+']['+g+'][mandatory]"><label for="checkboxPrimaryg'+g+'"></label></div></div><label style="padding-left: 10px;">Answers</label><div id="dynamicquestionscasenew'+g+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitemcase"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
        	g++;
        }

        function showhideoptionscasenew(id, value, newnumber, oldnumber, oldestnumber, oldcaseid, oldoptionid, oldestcaseid, oldestoptionid){

        	debugger;

            if(value=="text"){
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" id="'+value+newnumber+'" name="multipleinput['+oldestnumber+'][answer]['+oldestoptionid+']['+oldestcaseid+']['+oldnumber+'][answers2]['+oldoptionid+']['+oldcaseid+']['+newnumber+'][answers3]" placeholder="Textbox" readonly/></div></div>');
            }
            if(value=="number"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><input class="form-control" type="number" id="'+value+newnumber+'" name="multipleinput['+oldestnumber+'][answer]['+oldestoptionid+']['+oldestcaseid+']['+oldnumber+'][answers2]['+oldoptionid+']['+oldcaseid+']['+newnumber+'][answers3]" placeholder="Total Size (i.e. Max numbers allowed)" required/></div></div>');
            }
            if(value=="trueorfalse"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-4"><select class="form-control" id="'+value+newnumber+'" name="multipleinput['+oldestnumber+'][answer]['+oldestoptionid+']['+oldestcaseid+']['+oldnumber+'][answers2]['+oldoptionid+']['+oldcaseid+']['+newnumber+'][answers3]"><option selected disabled>The Following are the options</option><option value="No" disabled>No</option><option value="Yes" disabled>Yes</option></select></div></div>');
            }
            if(value=="multichoiceoneans"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" name="multipleinput['+oldestnumber+'][answer]['+oldestoptionid+']['+oldestcaseid+']['+oldnumber+'][answers2]['+oldoptionid+']['+oldcaseid+']['+newnumber+'][answers3]" id="count'+value+newnumber+'" class="form-control" placeholder="Total Number of Options Needed" onchange="addoptionsnewcase('+"'"+id+newnumber+"'"+', this.value, '+"'"+value+"'"+', '+"'"+oldnumber+"'"+', '+"'"+oldoptionid+"'"+', '+"'"+oldcaseid+"'"+', '+"'"+oldestnumber+"'"+', '+"'"+oldestcaseid+"'"+', '+"'"+oldestoptionid+"'"+')" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary">Generate</button></div></div><div class="col-md-9"></div></div>');  
            }
            if(value=="multichoicemultians"){
                
                var dyid='#'+id+newnumber;
                $(dyid).empty();
                $(dyid).append('<div class="row"><div class="col-md-3"><label><b>Number of Options</b></label></div><div class="col-md-9"></div></div><div class="row"><div class="col-md-3"><div class="form-group"><input type="number" name="multipleinput['+oldestnumber+'][answer]['+oldestoptionid+']['+oldestcaseid+']['+oldnumber+'][answers2]['+oldoptionid+']['+oldcaseid+']['+newnumber+'][answers3]" id="count'+value+newnumber+'" class="form-control" placeholder="Total Number of Options Needed" onchange="addoptionsnewcase('+"'"+id+newnumber+"'"+', this.value, '+"'"+value+"'"+', '+"'"+oldnumber+"'"+', '+"'"+oldoptionid+"'"+', '+"'"+oldcaseid+"'"+', '+"'"+oldestnumber+"'"+', '+"'"+oldestcaseid+"'"+', '+"'"+oldestoptionid+"'"+')" required></div></div><div class=col-md-1><div class="form-group"><button type="button" class="btn btn-primary">Generate</button></div></div><div class="col-md-9"></div></div>');
            }

        }

        function addoptionsnewcase(id, count, value, newnumber, oldnumber, oldcaseid, oldoptionid, oldestnumber, oldestcaseid, oldestoptionid){
            var nwdyid='#'+id;
            $(nwdyid).empty();
            $(nwdyid).append('<div class="row" style="margin-bottom:1%;">');
            for(var i=0; i<count; i++){
                $(nwdyid).append('<div class="col-md-6"><label><b>Option'+(parseInt(i)+1)+'</b></label></div><div class="col-md-6"><input class="form-control" id="option'+i+value+newnumber+'" name="multipleinput['+oldestnumber+'][answer]['+oldestoptionid+']['+oldestcaseid+']['+oldnumber+'][answers2]['+oldoptionid+']['+oldcaseid+']['+newnumber+'][answers3][option'+i+']" placeholder="Option '+(parseInt(i)+1)+'" required /></div>');
            }
            $(nwdyid).append('<hr></div>');
        }

        $(document).ready(function() {
        	$("#addItem").click(function () {
        		++s;
        		$("#dynamic").append('<div id="survey'+s+'" class="surveyname"><br /><h4>Next Question</h4><hr><div class="row"><div class="col-md-7"><label>Question</label></div><div class="col-md-3"><label>Question Type</label></div><div class="col-md-2" style="text-align:center;"><label>Mandatory</label></div></div><div class="row"><div class="col-md-7"><div class="form-group"><div class="input-group mb-6"><input type="text" id="question'+s+'" name="multipleinput['+s+'][question]" class="form-control" placeholder="Question" required></div></div></div><div class="col-md-3"><div class="form-group"><div class="input-group mb-6"><select id="questiontype'+s+'" name="multipleinput['+s+'][questiontype]" class="form-control" onchange="showhideoptions('+"'dynamicquestions'"+', this.value,'+s+')" required><option value="" selected disabled>Choose Question Type</option><option value="text">Text</option><option value="number">Number</option><option value="trueorfalse">True or False</option><option value="multichoiceoneans">Mutliple choice with one answer</option><option value="multichoicemultians">Mutliple choice with Multiple answer</option></select></div></div></div><div class="col-md-2" style="text-align:center;"><div class="icheck-primary d-inline"><input type="checkbox" id="checkboxPrimarys'+s+'" name="multipleinput['+s+'][mandatory]"><label for="checkboxPrimarys'+s+'"></label></div></div><label style="padding-left: 10px;">Answers</label><div id="dynamicquestions'+s+'" class="col-md-12"></div></div><div class="col-md-12" id="deletediv" style="text-align:right;padding:10px;"><span class="text-danger" id="removeitem"><i class="far fa-trash-alt"></i> Delete this Question</span></div></div>');
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
                <h1 class="m-0 text-dark">Rapid Assessment</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Rapid Assessment</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">New Rapid Assessment</h5>
                </div>
                <div class="card-body p-3">
                    <form method="post" action="test.php">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Project Name</b></label>
                                    <div class="input-group mb-6">
                                        <select id="projectname" name="projectname" class="form-control" required>
                                        <option value="" selected disabled>Choose Project name</option>
                                            <?php
                                                $sql="SELECT DISTINCT p.project_id,p.project_name from beneficiaries ben inner join dblink('dbname= project_management_db port=5432 host=localhost user=postgres password=pass@123' ,'select id,name from projects') as p(project_id character varying, project_name character varying) on ben.project_id=p.project_id";

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
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-question"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Rapid Assessment Name</b></label>
                                    <div class="input-group mb-6">
                                        <input type="text" name="survey" value="" class="form-control" placeholder="Rapid Assessment Name" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-question"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Description (optional)</b></label>
                                    <div class="input-group mb-6">
                                        <textarea class="form-control" rows="2" placeholder="Enter Description" name="description"></textarea>
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
                                <div class="col-md-7">
                                    <label><b>Question</b></label>
                                </div>
                                <div class="col-md-3">
                                    <label><b>Question Type</b></label>
                                </div>
                                <div class="col-md-2" style="text-align:center;">
                                    <label><b>Mandatory</b></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
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
                                                <option value="" selected disabled>Choose Question Type</option>
                                                <option value="text">Text</option>
                                                <option value="number">Number</option>
                                                <option value="trueorfalse">True or False</option>
                                                <option value="multichoiceoneans">Mutliple choice with one answer</option>
                                                <option value="multichoicemultians">Mutliple choice with Multiple answer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2" style="text-align:center;">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimary0" name="multipleinput[0][mandatory]">
                                        <label for="checkboxPrimary0">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                </div>
                                <label style="padding-left: 10px;">Answers</label>
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
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <a class="btn btn-default form-control" href="index.php" style="margin-right: 20px;">cancel</a>
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