<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin();
include 'Classes/_function_classes.php';
//error_reporting(0);
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

  <!--end modals here -->

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">SAKU ELECTIONS CANDIDATES MANAGEMENT</h3>
                    
					 <?php
                     show_campus_popup('show_candidates_elections.php',$con);
					//----------------------------------------------------------
                    if(isset($_REQUEST['election_period']))
                    {
                        $_SESSION['register_candidate_election_period']=htmlspecialchars($_REQUEST['election_period'],ENT_QUOTES);
                        header('location:enrol_candidates.php');
                    }
					if(isset($_GET['election_periods']))
					{
					   $election_period=htmlspecialchars($_GET['election_periods'],ENT_QUOTES);
					   $election_query=odbc_exec($con,"SELECT election_period FROM elections 							WHERE election_period='$election_period'");
					   $election_row=odbc_fetch_array($election_query);
					   $_SESSION['election_period']=$election_row['election_period']; 
						header('location:show_voters_register.php');
					 }
					
					
					if(isset($_SESSION['campus']))
                    {

                   
                    if($query=odbc_exec($con,"SELECT election_period,status 
                                                from elections 
												ORDER BY election_period DESC"))
                    {
                        $i=1; 
                    echo '
					 <div class="dataTable_wrapper">
					
                  <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
                    <tr><th colspan="6">
                    <h3 class="text-info">'.$_SESSION['campus_name'].'</h3>
                    </th></tr>
					<tr>
					<th></th>
					<th>Election Period</th>
					<th>Status</th>
					<th>Register Candidate</th>
					<th>View Candidates</th>
					<th>Print Ballot Paper</th>
					
					</tr>
					</thead>
					<tbody>';
                    while($row=odbc_fetch_array($query))
                    {
						echo '<tr><td>'.$i++.'</td>
						<td>'.$row['election_period'].'</td>
						<td>'.$row['status'].'</td>
						<td>';
					if($row['status']=='open')
					{
					echo '<a href="?election_period='.$row['election_period'].'&campus='.$_SESSION['campus'].'">Register Candidates</a>';
					}
				//	else{ echo '<a href="">Register Candidates</a>'; }
					echo '</td><td>'.
					'<a href="show_positions.php?ballot='.$row['election_period'].'&campus='.$_SESSION['campus'].'">View Candidates</a>'.
					'</td>
                    <td>'.
					'<a href="show_positions.php?ballot='.$row['election_period'].'&campus='.$_SESSION['campus'].'">Print Ballot Paper</a>'.
					
					'</td>
                    </tr>';
                    }	
                    echo '<tr><td colspan="6">
					<a href="create_elections.php">Create New Election</a></td></tr>
					</tbody></table></div>';
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
	//---------------------------------------------------

		
		
	});
</script>
</body>

</html>
