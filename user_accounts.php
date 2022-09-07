<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin();
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
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-user"></span> New Users</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" >
         		<div class="form-group">
                  <label>Full Name:</label>
                  <input type="text" name="fullname" class="form-control" required>
               </div>
               <div class="form-group">
                  <label>Username:</label>
                  <input type="text" name="username" class="form-control" required>
               </div>
                <div class="form-group">
                  <label>Password:</label>
                  <input type="password" name="password" class="form-control" required>
               </div>
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
               
                <div class="form-group" >
                  <label>User Type:</label>
                    <?php
					  
					   $qry=odbc_exec($con,"SELECT user_type from  user_types");
					   echo '<select name="user_type" class="form-control" required>';
					   while($r=odbc_fetch_array($qry))
					   {
						   echo '<option value="'.$r['user_type'].'">'.$r['user_type'].'</option>';
					   }
					   echo '</select>';
					
					?>
                  
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
                    <h3 class="page-header"><i class="fa fa-users"></i> User Accounts</h3>
					 <?php
					//reset password:
					if(isset($_POST['save_password']))
					{
						
					   $password=trim($_POST['password']);
					   $repassword=trim($_POST['confirm_password']);
					  if(!empty($password) and !empty($repassword) and ($password==$repassword))
					  {
						  $username=htmlspecialchars($_POST['username'],ENT_QUOTES);
						   $password=md5($password);
						   
							   //-----------------------------------------------
								   $update=odbc_prepare($con,"UPDATE admin 
										   				SET password=? where username=? ");
								 
								  if( odbc_execute($update,array($password,$username)))
								  {
									 
								 echo '<div class="alert alert-success"> Successs, your account has been activated <strong><a href="login.php">Click here to login</a></strong> </div>';
								  
								}
								  else{
								   echo '<div class="alert alert-danger">
								   <button type="button" class="close" data-dismiss="alert">&times;</button>
									  Account cannot be activated kindly contact the system administrator for more information </div>';
								  }
								   
								 
								  
							   //------------------------------------------------------
							   
							   
						   //	header('location:new_password.php');
							   
						   }
						   
						   else{
							   
							echo '<div class="alert alert-danger">
							 <button type="button" class="close" data-dismiss="alert">&times;</button>
								Account cannot be activated ensure the typed passwords matches </div>';
						   }
								   
						  
					  }
					  


                   //set campus access
				   if(isset($_POST['save_campus']))
				   {
					   if(isset($_POST['campus']))
					   {
						   $y=0;
						   $x=0;
						   foreach($_POST['campus'] as $campus)
						   {
							   $username=htmlspecialchars($_POST['username'],ENT_QUOTES);
							   $qry=odbc_prepare($con,"INSERT INTO admin_accounts_campus_accessed 		(campus_code,username) VALUES(?,?)");
							   $params=array($campus,$username);
							   if(odbc_execute($qry,$params))
							   {
									$y++;
							   }
							   else{
								   $x++;
							   }
						   }
						   echo '<div class="alert alert-success">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Success!</strong> '.$y.' Record(s) have been saved, while '.$x.' failed to save
						</div>';

					   }else{
						echo '<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Error!</strong> Select atleast one campus
					  </div>';
					   }
				   }
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
				   //----remove campus
					if(isset($_REQUEST['remove_campus']))
					{
							$campus=htmlspecialchars($_REQUEST['remove_campus'],ENT_QUOTES);
							$username=htmlspecialchars($_REQUEST['user'],ENT_QUOTES);
							$qry=odbc_prepare($con,"DELETE FROM admin_accounts_campus_accessed 
													WHERE username=? AND campus_code=?");
							if(odbc_execute($qry,array($username,$campus)))
							{
								header('location:user_accounts.php');
								echo '<div class="alert alert-success">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Success!</strong> Record has been removed
								</div>';
								
								}
							else{
								header('location:user_accounts.php');
									echo '<div class="alert alert-danger">
										  <button type="button" class="close" data-dismiss="alert">&times;</button>
										  <strong>Error!</strong> Record cannot be removed
										</div>';
								}

					}
					//create a new user
					if(isset($_POST['save_user']))
					{
					     $username=htmlspecialchars($_POST['username'],ENT_QUOTES);	
						 $password=md5($_POST['password']);
						 $fullname=htmlspecialchars($_POST['fullname'],ENT_QUOTES);
						 $election_period=htmlspecialchars($_POST['election_period'],ENT_QUOTES);
						  $user_type=htmlspecialchars($_POST['user_type'],ENT_QUOTES);
						  $date_created=date('d/m/Y h:i:s a');
						  $account_status='Active';
						 $strg="INSERT INTO admin (username,name,password,user_type,election_period,account_status,date_created )
												     VALUES('$username','$fullname','$password','$user_type',
													'$election_period','$account_status','$date_created')";
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
					
					
                   $query=odbc_exec($con,"SELECT a.username,a.name,a.user_type,a.election_period,a.account_status,a.date_created 
				   					from admin a ORDER BY a.election_period DESC ");
				if($query)
				{
					$i=1;
					 echo '
					 <div class="dataTable_wrapper">
					<p>
					<a href="" data-target="#add_user" class="btn btn-success" data-toggle="modal">
									<i class="fa fa-plus-circle "></i> Create New User</a><p>
                   <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					   <thead>
					<tr>
					<th></th> 
					<th>Username</th>
                    <th>Name</th>
					<th>User Type</th>
					<th>Election</th>
					<th>Campus Access</th>
					<th>Created on</th>
					<th>Status</th>
                    <th></th>
					</tr>
					</thead>
					<tbody>';
				while($row=odbc_fetch_array($query))
				{
					
					echo '<tr>
					<td>'.$i++.'.</td>
					<td>'.$row['username'].'</td>
					<td>'.ucwords(strtolower($row['name'])).'</td>
					<td>'.$row['user_type'].'</td>
					<td>'.$row['election_period'].'</td>
					<td>';
					$campuses=odbc_prepare($con,"SELECT c.campus_code,c.campus_description 
										FROM admin_accounts_campus_accessed a 
										INNER JOIN campus c on c.campus_code=a.campus_code
										where a.username=?");
					odbc_execute($campuses,array($row['username']));
					if(odbc_num_rows($campuses)>0){
						while($campus=odbc_fetch_array($campuses))
						{
							echo '<p>'.$campus['campus_description'].'
							[<a href="?remove_campus='.$campus['campus_code'].'&user='.$row['username'].'">
							<i class="fa fa-trash" style="color:red"></i></a>]</p>';
						}
					}
					else{echo '<p class="text-danger">User cant access any campus</p>';}					

					echo '</td><td>'.$row['date_created'].'</td>
					<td>'.$row['account_status'].'</td>
					<td>
					
					   <span class="dropdown">
					<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class="dropdown-menu pull-right">
						
						<li><a href="" data-id="'.$row['username'].'_edit" data-target="#edit_user" data-toggle="modal">
						<span class="fa fa-pencil" style="color:brown"></span> Edit </a></li>

						<li><a href="" data-id="'.$row['username'].'_resetPassword" data-target="#edit_user" data-toggle="modal">
						<span class="fa fa-pencil" style="color:brown"></span> Reset Password</a></li>

						<li><a href="" data-id="'.$row['username'].'_setCampus" data-target="#edit_user" data-toggle="modal">
						<span class="fa fa-link" style="color:#FC6"></span> Set Campus Accessed</a></li>
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

		//check password matching
		$('#password,#repassword').on('keyup',function(){
         
		 if($('#password').val()==$('#repassword').val()){
			 $('#msg').html("Password Matches").css('color','green');
		 }
		 else{ $("#msg").html("Password Don't Matches").css('color','red');}
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
