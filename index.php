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
        style="margin-bottom: 0; background:#182B5C; color:#FFF; border-top:solid 13pt #DFD156;" >
           

          
		   
		   
		    <?php
   			 include 'Classes/new_links.php';
    
		     ?>
            <!-- /.navbar-static-side -->
        </nav>
   
<!---add news modal--->
<div id="generate_user_passwords" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="generate_user_passwords">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-lock"></span> User Password</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" >
         		
               <div class="form-group">
                 <label>Election Period:</label>
                   <?php
					  
					   $qry=odbc_exec($con,"SELECT election_period from elections ORDER BY election_period DESC");
					   echo '<select name="election_period" class="form-control" id="election_period" required>';
					   while($r=odbc_fetch_array($qry))
					   {
						   echo '<option value="'.$r['election_period'].'">'.$r['election_period'].'</option>';
					   }
					   echo '</select>';
					
					?>
               </div>
               <div class="form-group" id="show_password_msg"></div>
                
        
         </div>
        <div class="modal-footer">
       <input type="button" name="save_password" value="Generate Passwords" id="save_password" class="btn btn-primary">
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
                    <h3 class="page-header"><i class="fa fa-dashboard"></i> Dashboard</h3>
					
                     <!---------------row------------>
                     
                     <div class="row">
                     
                      <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-bar-chart-o fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div>Election Results</div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <a href="show_elections.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                     
                     <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-upload fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div>Upload Voters</div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <a href="_upload_voters_for_all_programs.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                     
                     
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-lock fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div>Generate Voters Password</div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#" data-target="#generate_user_passwords" data-toggle="modal">
                                            <div class="panel-footer">
                                                <span class="pull-left">Click here to generate</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                  </div>   
                     
                     
                    <!---- end row--->                    
                    
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
		$("#save_password").click(function(e){
		$("#show_password_msg").empty();
		$("#show_password_msg").html("<img src='images/loader.gif'>Please wait while passwords are been set.............");
		var election_period=$("#election_period").val();
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'set_voter_password_election_period='+election_period,
				success: function(data){
					$("#show_password_msg").html(data);
					}

			});
		
		});


		
	});
</script>
</body>

</html>
