<?php
session_start();
include '_SESSIONS_/_sessions.php';
admin();
include 'config/db_con.php';
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
					  
					   $qry=odbc_exec($con,"SELECT election_period from elections ORDER BY election_period DESC");
					   echo '
					   <form role="form" method="post">
					   <div class="col-md-3">
                     <div class="form-group">
                     <label>Election Period:</label>
					   <select name="election_period" class="form-control" required>';
					   while($r=odbc_fetch_array($qry))
					   {
						   echo '<option value="'.$r['election_period'].'">'.$r['election_period'].'</option>';
					   }
					   echo '</select>
					   </div>
					   <div class="form-group">
					    <input type="submit" name="show_report" value="Show Report" class="btn btn-primary">
					  
               			</div>
						</div>
						 </form>';
					
					
				  
				  
					
					if(isset($_POST['show_report']))
					{
					    
						 $election_period=htmlspecialchars($_POST['election_period'],ENT_QUOTES);
						  
					
                   if($query=odbc_exec($con,"SELECT a.username,a.name,a.user_type,count(s.password_issued_by) as total_password
											 FROM admin a 
											LEFT JOIN Students s on s.password_issued_by=a.username and  s.election_period='$election_period'
											WHERE s.election_period='$election_period'
											GROUP BY a.username,a.name,a.user_type ORDER BY total_password DESC "))
					{
			
					$i=1;
					 echo '
					 <div class="dataTable_wrapper">
					
                   <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					   <thead>
					   <tr>
					   <th colspan="6"><h3>KCAU '.$election_period.' SAKU ELECTIONS </th>
					   </tr>
					<tr>
					<th></th> 
					<th>Username</th>
                    <th>Name</th>
					<th>User Type</th>
					
					<th class="text-right">No. of Voters Cleared</th>
                    <th></th>
					</tr>
					</thead>
					<tbody>';
				$total_voters=array();
				while($row=odbc_fetch_array($query))
				{
					$total_voters[]=$row['total_password'];
					
					echo '<tr>
					<td>'.$i++.'.</td>
					<td>'.$row['username'].'</td>
					<td>'.ucwords(strtolower($row['name'])).'</td>
					<td>'.$row['user_type'].'</td>
					
					<td class="text-right">'.number_format($row['total_password'],0).'</td>
					
					<td>
					
					   <span class="dropdown">
					<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class="dropdown-menu pull-right">
						
						
                        <li><a href=""><i class="fa fa-plus-circle "></i> View List of voters issued password</a></li>
						<li><a href="">
                       <span class="glyphicon glyphicon-download"></span> Download Details</a></li>
					</ul>
					
					</td>
					</tr>';
				}	
				echo '
				<tr>
				   <th></th>
				   <th class="text-right" colspan="3">Total Voters Cleared:</th>
				   <th class="text-right">'.number_format(array_sum($total_voters),0).'</th>
				   <th></th>
				</tr>
				</tbody></table></div>';
				}
				else{
					 echo odbc_error($con);
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
