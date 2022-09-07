<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin_ieck_chair();
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
                    <h3 class="page-header">KCAU SAKU ELECTIONS TURNOUT ANALYSIS</h3>
					 <?php
                   
					//----------------------------------------------------------
					if(isset($_GET['election_period']) and isset($_SESSION['campus']))
					{
					   $election_period=htmlspecialchars(trim($_GET['election_period']),ENT_QUOTES);
                       $campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);
				$query=odbc_prepare($con,"SELECT   time_line= DATEPART(HOUR, cast(v.time_voted as TIME)),
                                    COUNT( DISTINCT v.student_reg ) as total_voters
                                    from votes v
                                    INNER JOIN Students s on s.reg_no=v.student_reg 
                                    and s.election_period=v.election_period 
                                    WHERE v.election_period=? and s.campus_code=?
                                    GROUP BY DATEPART(HOUR, (cast(v.time_voted as TIME)))
                                    ORDER BY DATEPART(HOUR, cast(v.time_voted as TIME)) ASC");

                        odbc_execute($query,array($election_period,$campus));
                        $i=1; 
                    echo '
					 <div class="dataTable_wrapper">
					
                  <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
                    <tr><th colspan="3"> KCAU SAKU ELECTION '.$election_period. ' ('.$_SESSION['campus_name'].')</th></tr>
                    <tr><th colspan="3" id="bold_row">Hourly voter turnout analysis</th></tr>
					<tr>
					<th>#</th>
					<th>Time Duration</th>
					
					<th class="text-right">No. of Votes</th>
					
					</tr>
					</thead>
					<tbody>';
                    $votes=0;
                    while($row=odbc_fetch_array($query))
                    {
                        $votes+=$row['total_voters'];
						$start_time=$row['time_line'].':00:00';
			            $end_time=$row['time_line'].':59:59';
			
                    echo '<tr><td>'.$i++.'.</td>
                    <td>'.date('h:i:s A',strtotime($start_time)).' &nbsp;&nbsp;&nbsp; to &nbsp;&nbsp;&nbsp;    '
                    .date('h:i:s A',strtotime($end_time)).
                    
                    '</td><td style="text-align:right;">'.number_format($row['total_voters'],0).'</td></tr>';
                     }	
                            echo '<tr>
                            <td colspan="3" id="bold_row" style="text-align:right;">'.number_format($votes,0).'</td>
                            </tr>
                                <tr>
                                <td colspan="2">
                                <a id="link_button" href="show_elections.php" >Go Back</a>
                                </td><td>
                                <a id="link_button" href="file_download_list_voter_turnout_patterns.php?election_period='.$election_period.'">
                                Download Results</a>
                                </td>
                                </tr>
                            </tbody></table></div>';
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
	
		
		
	});
</script>
</body>

</html>
