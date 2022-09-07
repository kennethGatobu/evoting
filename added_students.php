<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin_ieck_chair();
include 'Classes/_function_classes.php';
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
   
<!---add news modal--->
<div id="add_user" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_user">
   <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-user"></span> Register New Student</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" >
         <div class="row">
           
             <div class="col-md-6">
                <div class="panel panel-info">
                     <div class="panel-heading">Student Details</div>
                     <div class="panel-body">
                        
                        <div class="form-group">
                          <label>Full Name:</label>
                          <input type="text" name="fullname" class="form-control" required>
                       </div>
                       <div class="form-group">
                          <label>Admission NO:</label>
                          <input type="text" name="username" class="form-control" required>
                       </div>
                       <div class="form-group">
                          <label>Gender:</label>
                          <select name="gender" class="form-control" required>
                               <option value="">Select</option>
                               <option value="0">Male</option>
                               <option value="1">Female</option>
                          </select>
                       </div>
                      <div class="form-group">
                          <label>Password:</label>
                          <input type="text" name="password" <?php  echo 'value="'.rand(1000,9999).'"'; ?> class="form-control" required>
                       </div>
                     
                     </div>
                </div>
                </div>
            <div class="col-md-6">
            <div class="panel panel-info">
                     <div class="panel-heading">Student Details</div>
                     <div class="panel-body">
                      <?php
                    include 'config/db_con.php';
					$sql=odbc_exec($con,"SELECT faculty_code,description from Faculty ORDER BY description ASC");
					if($sql)
					{
					echo '
					
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
					        <label>Select Course:</label>
							<select id="faculty_courses" name="courses" class="form-control" required>
							     <option value="" >Select</option>
							</select>
					   </div> ';
					
					}?>
                    <div class="form-group">
                 <label>Election Period:</label>
                   <?php
					  
					   $qry=odbc_exec($con,"SELECT election_period from elections ORDER BY election_period DESC");
					   echo '<select name="election_period" class="form-control" required>';
					   while($r=odbc_fetch_array($qry))
					   {
						   echo '<option value="'.$r['election_period'].'">'.$r['election_period'].'</option>';
					   }
					   echo '</select>';
					
					?>
               </div>
                  
                     
                     
                    
              </div>
            
            </div>
            </div>
         
         </div>
         	
         </div>
        <div class="modal-footer">
       <input type="submit" name="save_user" value="Save" class="btn btn-primary">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!---edit user--->
<div id="edit_user" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="edit_user">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-edit" style="color:#FF5"></span> Edit Users</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_edit_user">
         		
         </div>
        <div class="modal-footer">
      
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
 
  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-users"></i> List of Students added to the register</h3>
					 <?php
                   
				   //-----edit user
				   if(isset($_POST['save_edit_user']))
					{
						$old_username=htmlspecialchars($_POST['old_username'],ENT_QUOTES);
					     $username=htmlspecialchars($_POST['username'],ENT_QUOTES);	
						
						 $fullname=htmlspecialchars($_POST['fullname'],ENT_QUOTES);
						 $election_period=htmlspecialchars($_POST['election_period'],ENT_QUOTES);
						  $user_type=htmlspecialchars($_POST['user_type'],ENT_QUOTES);
						 
						  $account_status=htmlspecialchars($_POST['account_status'],ENT_QUOTES);
						 $strg="UPDATE admin SET username='$username',name='$fullname',user_type='$user_type',
						 				election_period='$election_period',account_status='$account_status' WHERE username='$old_username'";
						if($insert_user=odbc_exec($con,$strg)){
						echo '<div class="alert alert-success">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Success!</strong> Record has been saved
						</div>';
						}
					else{
							echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> Record cannot be saved
								</div>';
						}
					
					
					
					}
				   //----save new user
					
					if(isset($_POST['save_user']))
					{
					     $username=htmlspecialchars($_POST['username'],ENT_QUOTES);	
						 $password=$_POST['password'];
						 $fullname=htmlspecialchars($_POST['fullname'],ENT_QUOTES);
						 $election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
						 $campus=$_SESSION['campus'];
						  $course_code=htmlspecialchars($_POST['courses'],ENT_QUOTES);
						  $date_created=date('d/m/Y h:i:s a');
						  $registration_mode='AD';
						  $account_status='0';
						  $gender=htmlspecialchars($_POST['gender'],ENT_QUOTES);
						  $password_issued_by=htmlspecialchars($_SESSION['logged_user'],ENT_QUOTES);
						 $strg="INSERT INTO Students (reg_no,name,password,course_code,election_period,
						 							registration_mode,gender,password_issued_by,date_pwd_issued,campus_code )
												     VALUES(?,?,?,?,?,?,?,?,?,?)";
							$insert_user=odbc_prepare($con,$strg);

							$param=array($username,$fullname,$password,$course_code,
							$election_period,$registration_mode,$gender,$password_issued_by,
							$date_created,$campus);						 
						if(odbc_execute($insert_user,$param)){
						echo '<div class="alert alert-success">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Success!</strong> Record has been saved
						</div>';
						}
					else{
							echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> Record cannot be saved
								</div>';
						}
					
					
					
					}
					show_campus_and_election_popup('added_students.php',$con);


					if(isset($_SESSION['election_period']) and isset($_SESSION['campus']))
					{
						$election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
						$campus=$_SESSION['campus'];
                   $query=odbc_prepare($con,"SELECT s.reg_no,s.name,s.course_code,s.registration_mode,
				   						s.election_period 
				   						from Students s 
											WHERE s.election_period=? and campus_code=?
											and s.registration_mode='AD' ");
						odbc_execute($query,array($election_period,$campus));
				
					$i=1;
					 echo '
					 <h3 class="text-info">'.$_SESSION['campus_name'].' ('.$_SESSION['election_period'].')</h3>';
					 
					
					echo '<div class="dataTable_wrapper">
					<p class="text-right">
					<a href="" data-target="#add_user" class="btn btn-success" data-toggle="modal">
						<i class="fa fa-plus-circle "></i> Click here to Register New Student</a><p>
                   <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					   <thead>
					<tr>
					<th></th> 
					<th>Reg No</th>
                    <th>Name</th>
					<th>Course</th>
					<th>Election Period</th>
					<th>Registration Mode</th>
					
                    <th></th>
					</tr>
					</thead>
					<tbody>';
				
				while($row=odbc_fetch_array($query))
				{
					
					echo '<tr>
					<td>'.$i++.'.</td>
					<td>'.$row['reg_no'].'</td>
					<td>'.ucwords(strtolower($row['name'])).'</td>
					<td>'.$row['course_code'].'</td>
					<td>'.$row['election_period'].'</td>
					<td>'.$row['registration_mode'].'</td>
					
					<td>
					
					   <span class="dropdown">
					<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class="dropdown-menu pull-right">
						
						<li><a href="" data-id="'.$row['reg_no'].'" data-target="#edit_user" data-toggle="modal">
						<span class="glyphicon glyphicon-edit" style="color:#FC6"></span> Edit</a></li>
                        <li><a href=""><i class="fa fa-plus-circle "></i> View Details</a></li>
						<li><a href="">
                       <span class="glyphicon glyphicon-download"></span> Download Details</a></li>
					</ul>
					
					</td>
					</tr>';
				}	
				echo '</tbody></table></div>';
				
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
		//----------------------------------------------
		//---------------------------
		$("#faculty").change(function(e){
		$("#faculty_courses").empty();
		
		var faculty_code=$("#faculty").val();
		
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'select_courses_faculty_code='+faculty_code,
				success: function(data){
					$("#faculty_courses").html(data);
					}

			});
		
		});

		//------------edit user---------------
		$("#edit_user").on('show.bs.modal',function(e){
		$("#load_edit_user").empty();
		$("#load_edit_user").html("<img src='images/loader.gif'>Loading.............");
		var edit_username=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'edit_username='+edit_username,
				success: function(data){
					$("#load_edit_user").html(data);
					}

			});
		
		});


		
	});
</script>
</body>

</html>
