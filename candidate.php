<?php
session_start();
include("sessions/redirect_3.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include 'functions/page_title.php'; ?>


<link href="styles/styles.css" rel="stylesheet" type="text/css" />

<script src="script/jQuery.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
						   
			$("#search").keyup(function(){
					
	var student=$("#search").val();
	if($.trim(student)=="")
	{
		$("#search-result").empty();
	}
	else
	{
		$("#search-result").html("searching Loading.................................");
		$.post("include/search.php",{reg: student, id:Math.random()}, function(data){
																			  
  $("#search-result").html(data);
  });

	}
	
	});	
			} );
	//---------------------------------------------------------------------------------------------
	$(document).ready(function() {
        $("#student_name").keyup(function(){
			var student_name=$("#student_name").val();
			if($.trim(student_name)=="")
			{
				$("#search-result").empty();
			}
			else
			{
				$("#search-result").html("searching.....loading...................................");
				$.post("include/search2.php",{std_name: student_name, id:Math.random()},function(data){
					$("#search-result").html(data);
					});
			}
			
			
			});
    });
</script>

</head>
<body>
<div class="wrap" style="height:700px; min-height:790px; height:auto;">
<?php
include("include/upper_header.php");
?>
<div class="header" style="float:left;">
</div>
<div class="nav" style="width:100%; float:left; margin-bottom:10px;">
<?php
include("include/links.php");
?>

</div>
<h1 style=" margin:10px; color:#339; font-family: 'MS Serif', 'New York', serif;">
<?php
include("config/db_con.php");
include("include/current_election.php");
echo "Enroll Candidates for ".$election." SAKU Elections";
?>
</h1>
<form name="myform" action="enroll.php" method="post">
<table width="923"><tr><td width="340">
Enter Reg_NO :<input type="text" name="search"  id="search"/><input type="submit" name="find" value="Search" /></td>
<td width="571"> NAME:<input type="text" name="name" id="student_name" size="50" /></td></tr></table>
</form>
<!--dispaying results here-->
<div  id="mt" style="padding-bottom:20px;   width:100%; text-align:center;">
<div class="content" id="search-result" style="overflow:scroll; height:350px; width:70%;">

<?php
//include("include/student_list.php");
?>
</div>
</div>
<!--end of results contents-->
 
</div>
<?php
include("include/footer.php");
?>
</body>
</html>