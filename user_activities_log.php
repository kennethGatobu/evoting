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
	<link href="bootstrap/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
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
 
 
  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-users"></i>User Activities Log</h3>
					 <!---------------row------------>
					 <form class="form-horizontal" role="form" method="post">
  <div class="form-group">
    <label class="col-md-2">From Date:</label>
    <div class="col-md-3">
      <input type="text" name="fdate" value="
<?php if(isset($_POST['load'])){ echo htmlspecialchars($_POST['fdate'],ENT_QUOTES); }?>"
   class="datepicker form-control" data-provide="datepicker" placeholder="dd/mm/yyyy" required autocomplete="off"/>
    </div>
    <label class="col-md-2">To Date:</label>
    <div class="col-md-3">
      <input type="text" name="tdate"  value="
<?php if(isset($_POST['load'])){ echo htmlspecialchars($_POST['tdate'],ENT_QUOTES); }?>"
      class="datepicker form-control" data-provide="datepicker" placeholder="dd/mm/yyyy" required autocomplete="off"/>
    </div>
    <div class="col-md-2">
      <input type="submit" name="load"  value="Load Report" class="btn btn-success btn-outline"/>
    </div>
  </div>
  </form>
					 <?php
					 if(!isset($_POST['load']))
					 {
						 $fdate=date('Y-m-d');
						 $tdate=date('Y-m-d');
					 }
					 else{
							 $fd=trim($_POST['fdate']);
							 $td=trim($_POST['tdate']);
							 if(!empty($fd) and !empty($td))
							 {
								 $fdate=formating_dates($fd);
								 $tdate=formating_dates($td);
							 }
							 else{
								 echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								 <strong> Warning!</strong> Please select dates range
								 </div>';
								 $fdate=date('Y-m-d');
								$tdate=date('Y-m-d');
								 
								 }
								 
						 }
						 if(!empty($fdate))
						 {
							   
							$qry=odbc_prepare($con,"SELECT a.log_id,a.username,d.name,
							a.date_accessed,a.time_accessed,
							a.task_performed,
							a.web_page_accessed,a.ip_address,a.computer_name
							FROM activity_logs a
							INNER JOIN admin d on d.username=a.username
							where cast(a.date_accessed as date) BETWEEN ? and ? 
							
							UNION
							SELECT a.log_id,a.username,d.name,
							a.date_accessed,a.time_accessed,
							a.task_performed,
							a.web_page_accessed,a.ip_address,a.computer_name
							FROM activity_logs a
							INNER JOIN Students d on d.reg_no=a.username
							where cast(a.date_accessed as date) BETWEEN ? and ? 
							ORDER BY a.log_id DESC");
									
							   odbc_execute($qry,array($fdate,$tdate,$fdate,$tdate));
							  
							 echo '<br><br>
								  <div class="dataTable_wrapper">
								 <p class="text-right"><a href="excel_user_activity_logs_download.php?fdate='.$fdate.' & tdate='.$tdate.'">
								 <span class="glyphicon glyphicon-download"></span>Download Report(Excel)</a></p>
								 <h4 class="text-info">Users activities for the dates between <strong>'.date('D, d-M-Y',strtotime($fdate)).' </strong>to 
								 <strong>'.date('D, d-M-Y',strtotime($tdate)).'</strong></h4>
								  <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size:11px;">
								<thead>
								 <tr>
								 <th></th>
								 <th>Activity Date</th>
								 <th>Time</th>
								 <th>Username</th>
								 <th>Name</th>
								 <th>Usertype</th>
								
								 <th>Activity</th>
								 <th>Page Accessed</th>
								 <th>Ip Address</th>
								 <th>Computer</th>
								 
								 
								 </tr>
								 </thead>
								 <tbody>';
							 if(odbc_num_rows($qry)>0)
							 {
								 
								 $i=1;
								 while($row=odbc_fetch_array($qry))
								 {
								   echo '<tr>'.
								   '<td>'.$i++.'.</td>'.
								   
									'<td style="width:100px;">'.date('D, d-M-Y',strtotime($row['date_accessed'])).'</td>'.
									'<td>'.$row['time_accessed'].'</td>'.
								   '<td>
								   <a href="#" data-id="'.$row['username'].'" data-target="#show_user_details" data-toggle="modal">'.$row['username'].'</a></td>'.
								   '<td><a href="#" data-id="'.$row['username'].'" data-target="#show_user_details" data-toggle="modal">
								   '.ucwords(strtolower($row['name'])).'</a></td>'.
								   '<td></td>'.
								   
								   '<td>'.$row['task_performed'].'</td>'.
								   '<td>'.$row['web_page_accessed'].'</td>'.
								   '<td>'.$row['ip_address'].'</td>'.
									'<td>'.$row['computer_name'].'</td>
									</tr>';
								 
								 }
								 
								 
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
	<script src="bootstrap/js/bootstrap-datepicker.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
		$('.datepicker').datepicker({
			 format:'dd/mm/yyyy',
			// startDate: '-3d',
			//startDate: 'd',
			 autoclose:true,
			 orientation:"bottom"
		
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
