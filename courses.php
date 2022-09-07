<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

   <?php include 'functions/page_title.php'; ?>


     <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
#some_color{color:#FFFBF0;}
/*
ul.navbar-top-links li a, ul.navbar-top-links li a:visited {
    color:#FFF !important;
}

ul.navbar-top-links li a:hover, ul.navbar-top-links li a:active {
    color:#2A0000 !important;
}

ul.navbar-top-links li.active a {
    color:#2A0000 !important;
}
*/
</style>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" 
        style="margin-bottom: 0; background:#2F637A; color:#FFF; border-top:solid 13pt khaki;" >
           

          
		   
		   
		    <?php
   			 include 'Classes/new_links.php';
    
		     ?>
            <!-- /.navbar-static-side -->
        </nav>
   <!--upload news image-->
<div id="new_photo" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="new_photo">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-upload"></span> &nbsp; News Photo</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_text">
         
        </div>
        <div class="modal-footer">
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
<!---add news modal--->
<div id="add_news" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_news">
   <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> &nbsp;School News</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body">
        <div class="form-group">
			  <label>Title:</label>
			  
			  <input type="text" name="news_title"  class="form-control" required >
			 
			  </div>
         <div class="form-group">
			  <label>News Type:</label>
			  
			  <select name="news_type" class="form-control">
                 <option value="School News">School News</option>
                 <option value="School Resources">School Resource</option>
                 <option value="Co-curricular Activity">Co-curricular Activity</option>
                 <option value="Academics">Academics</option>
                 <option value="About School">About School</option>
                 <option value="School Admission">School Admissions</option>
                 <option value="Principals Desk">Principals Desk</option>
              </select>
			 
			  </div>
        <div class="form-group">
			  <label>Details:</label>
			  <textarea name="news_details" id="mytextarea" class="form-control" rows="10"></textarea>
			  </div>
        </div>
        <div class="modal-footer">
        <input type="submit" name="save_news" value="Save" class="btn btn-success">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!---preview images--->
  
<div id="preview_news" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="preview_news">
   <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> &nbsp;School News</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_preview">
        
        </div>
        <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!---edit news modal--->
<div id="add_courses" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_courses">
   <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-edit"></span> Link Courses to Department</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_courses">
        
        </div>
        <div class="modal-footer">
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!--end modals here -->
  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">COURSES</h3>
					 <?php
                    include 'config/db_con.php';
					$sql=odbc_exec($con,"SELECT faculty_code,description from Faculty ORDER BY description ASC");
					if($sql)
					{
					echo '
					<div class="row">
					 <div class="col-md-5">
					<form method="post">
					<div class="form-group">
					<label>Select Faculty:</label>
					<select id="faculty" name="faculty" class="form-control" required>
					 <option value="" >Select</option>';
					while($row=odbc_fetch_array($sql))
					{
						echo '<option value="'.$row['faculty_code'].'">'.ucwords(strtolower($row['description'])).'</option>';
					}
					
					 echo '</select>
					 </div>
					   <div class="form-group">
					        <label>Select Department:</label>
							<select id="faculty_department" name="department" class="form-control" required>
							     <option value="" >Select</option>
							</select>
					   </div>
					   <div class="form-group">
					      <input type="submit" name="show_course" value="Show Courses" class="btn btn-primary">
					   </div>
					 </form>
					 </div>
					 </div>';
					
					}
					
					//-----------------------------------------------------------------
					if(isset($_POST['save_course']))
					{
					   if(!empty($_POST['courses']))
					   {
						    $department_code=htmlspecialchars($_SESSION['department_code'],ENT_QUOTES);
							
						   foreach($_POST['courses'] as $course)
						   {
							   $course=htmlspecialchars($course,ENT_QUOTES);
							   $update_cours=odbc_exec($con,"UPDATE Courses SET department_code='$department_code' WHERE course_code='$course'");
						   }
					   }
					   else{
						   echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> Select atleast one course
								</div>';
						   
						   }
					
					
					}
					//-------------------------------------------------------------------------
					
					if(isset($_POST['show_course']))
					{
						$_SESSION['department_code']=htmlspecialchars($_POST['department'],ENT_QUOTES);
						$_SESSSION['department_faculty_code']=htmlspecialchars($_POST['faculty'],ENT_QUOTES);
						header('location:courses.php');
					}
					
					//-------------show courses in a faculty---------------------------------------
					if(isset($_SESSION['department_code']))
					{
						  $department_code=htmlspecialchars($_SESSION['department_code'],ENT_QUOTES);
						  
						  //-----------show department details-------------------------
						  $d_qry=odbc_exec($con,"SELECT d.department_code,d.department_name,f.description,f.faculty_code
												 from faculty_academic_departments d 
												INNER JOIN Faculty f on f.faculty_code=d.faculty_code WHERE d.department_code='$department_code'");
						   $d_row=odbc_fetch_array($d_qry);
						   
				
				 $qry=odbc_exec($con,"SELECT c.course_code,c.course_description FROM Courses c WHERE c.department_code='$department_code'");
				   if($qry)
                    {
                        $i=1; 
                    echo '
					
                    <table class="table table-bordered table-striped table-hover">
					<thead>
					<tr>
					<th>Faculty:</th>
					<th colspan="3">'.$d_row['description'].'</th>
					</tr>
					<tr>
					   <th>Department:</th>
					   <th colspan="3">'.$d_row['department_name'].'</th>
					</tr>
					<tr>
					<th colspan="4" class="text-right">
					 <a href="" data-target="#add_courses" data-id="'.$d_row['faculty_code'].'" class="btn btn-success" data-toggle="modal">
									<i class="fa fa-plus-circle "></i> Create New Course</a>
					</th>
					</tr>
					<tr>
					<th>#</th>
                   <th>Course Code</th>
                    <th>Course</th>
                   
                    <th></th>
					</tr>
					</thead>
					<tbody>';
                    while($row=odbc_fetch_array($qry))
                    {
                        echo '<tr><td>'.$i++.'.</td>
						
						<td>'.$row['course_code'].'</td>
						<td><a href="">'. ucwords(strtolower($row['course_description'])).'</a></td>
						<td>
						<span class="dropdown">
					<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class="dropdown-menu pull-right">
						
						<li><a href=""><span class="glyphicon glyphicon-edit" style="color:#FC6"></span> Edit</a></li>
                        <li><a href="position_faculty_relationship.php?position_code='.$row['course_code'].'">
                        <span class="fa fa-chain" style="color:green"></span> Set who can vote</a></li>
						<li><a href="view_positions_details.php?position_code='.$row['course_code'].' ">
                       <span class="glyphicon glyphicon-fullscreen"></span> View Details</a></li>
					</ul>
                       
                        
                        </td></tr>';
                    }	
                    echo '</tbody></table>';
                    }
					}
                    
                    ?>
                                        
                    
             </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="bower_components/bootstrap/tinymce/tinymce.min.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
		
//---------------------------
		$("#faculty").change(function(e){
		$("#faculty_department").empty();
		
		var faculty_code=$("#faculty").val();
		
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'select_department_faculty_code='+faculty_code,
				success: function(data){
					$("#faculty_department").html(data);
					}

			});
		
		});
		//-------------aDD COURSES---------------
		$("#add_courses").on('show.bs.modal',function(e){
		$("#load_course").empty();
		$("#load_courses").html("<img src='images/loader.gif'>Loading.............");
		var load_courses_faculty_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'load_courses_faculty_code='+load_courses_faculty_code,
				success: function(data){
					$("#load_courses").html(data);
					}

			});
		
		});
		//------------preview news---------------
		$("#preview_news").on('show.bs.modal',function(e){
		$("#load_preview").empty();
		$("#load_preview").html("<img src='images/loader.gif'>Loading.............");
		var preview_news_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"classes/_ajax_requests_functions.php",
				data:'preview_news_code='+preview_news_code,
				success: function(data){
					$("#load_preview").html(data);
					}

			});
		
		});

		
		
	});
</script>
</body>

</html>
