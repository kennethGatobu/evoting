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

  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">SAKU ELECTIONS CANDIDATES MANAGEMENT</h3>
					 <?php
					
                    if(isset($_GET['ballot']) and isset($_SESSION['campus']))
					{
					$election_period=htmlspecialchars($_REQUEST['ballot'],ENT_QUOTES);
					$campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);
					$query=odbc_prepare($con,"SELECT  c.position_code,p.description ,
										count(c.position_code) as total
										from candidates c
										INNER JOIN [position] p on p.position_code=c.position_code
										where c.election_period=? AND c.campus=?
										GROUP BY c.position_code,p.description,p.priority_rating
										ORDER BY p.priority_rating asc");
                        odbc_execute($query,array($election_period,$campus));
                        $i=1; 
                    echo '
					 <div class="dataTable_wrapper">
					
                  <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
					<tr><th style="font-size:15px;" colspan="8">'.
                    $election_period.' SAKU ELECTIONS ('.$_SESSION['campus_name'].')
                    </th></tr>
					<tr>
					<th></th>
					<th>POSITION</th>
					<th>No. of Candidates Registered</th>
					<th colspan="2">Quick View</th>
					<th colspan="3">Download</th>
					
					</tr>
					</thead>
					<tbody>';
                    while($row=odbc_fetch_array($query))
                    {
					echo '<tr><td>'.$i++.'</td><td>'.
					$row['description'].
					'</td>
                    <td>'.$row['total'].'</td>
                    <td>'.
					'<a href="show_candidate_list.php?position_code='.$row['position_code'].'
						&election_period='.$election_period.'&position='.$row['description'].'">
						Candidates List</a>'.
					'</td>
					<td>'.
					'<a href="preview_results.php?position_code='.$row['position_code'].'
						& election_period='.$election_period.'&position='.$row['description'].'&photo=yes&results=few">
						Preview Results with photos</a>'.
					'</td><td>'.
					'<a href="file_download_ballot.php?position_code='.$row['position_code'].'
						& election_period='.$election_period.'&position='.$row['description'].'">Download Ballot Paper</a>'.
					'</td><td>'.
					'<a href="file_download_position_results.php?position_code='.$row['position_code'].'
						& election_period='.$election_period.'&position='.$row['description'].'& photo=yes">Download Results with photos</a>'.
						'</td><td>'.
					'<a href="file_download_list_voter_turnout_per_position.php?position_code='.$row['position_code'].'
						& election_period='.$election_period.'&position='.$row['description'].'">
						Download List of Voters who voted here</a>'.
					'</td></tr>';
                    }	
                    echo '<tr><td colspan="8">
	
					<a id="link_button" href="'.$_SERVER['HTTP_REFERER'].'" >Go Back</a>
				
					</td></tr>
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
