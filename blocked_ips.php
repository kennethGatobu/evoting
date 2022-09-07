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
                    <h3 class="page-header"><i class="fa fa-globe"></i> IP Filtering</h3>
					 <?php
				
					
					
                   $query=odbc_exec($con,"SELECT idno,computer_ip_address,computer_name,ip_status 
				   									FROM voting_computers ");
				if($query)
				{
					$i=1;
					 echo '
					 <div class="dataTable_wrapper">
					<p>
					
                   <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					   <thead>
					<tr>
					<th></th> 
					<th>IP Address</th>
                    <th>Computer Name</th>
                    <th>MAC Address</th>
					<th>Status</th>
                    <th></th>
					</tr>
					</thead>
					<tbody>';
				while($row=odbc_fetch_array($query))
				{
					
					echo '<tr>
					<td>'.$i++.'.</td>
					<td>'.$row['computer_ip_address'].'</td>
					
					<td>'.$row['computer_name'].'</td>
					<td>'.$row['ip_status'].'</td>
                    <td></td>
					<td>
					
					   <span class="dropdown">
					<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class="dropdown-menu pull-right">
						
						

						
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
