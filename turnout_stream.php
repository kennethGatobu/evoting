<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
include 'Classes/_function_classes.php';
livestream();
//admin();
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
        style="margin-bottom: 0; background:#192a5a; color:#FFF; border-top:solid 13pt #e3c460;" >
         
		    <?php
   			 include 'Classes/new_links.php';
    
		     ?>
            <!-- /.navbar-static-side -->
        </nav>
  

  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
			 <div class="col-md-12 page-header">
			 
			 <?php 
			 show_campus_and_election_popup('turnout_stream.php',$con);
				if(isset($_POST['show_report']))
				{
					$campus_code=htmlspecialchars($_POST['campus'],ENT_QUOTES);
					$_SESSION['election_period']=htmlspecialchars($_POST['election_period'],ENT_QUOTES);
					$campuses=odbc_prepare($con,"SELECT c.campus_code,c.campus_description 
							FROM admin_accounts_campus_accessed a 
							INNER JOIN campus c on c.campus_code=a.campus_code
							where a.campus_code=?");
				odbc_execute($campuses,array($campus_code));
				if(odbc_num_rows($campuses)>0){
					$row=odbc_fetch_array($campuses);
					$_SESSION['campus']=$row['campus_code'];
					$_SESSION['campus_name']=$row['campus_description'];


					header('location:turnout_stream.php');
				}
				}
				if(isset($_SESSION['election_period']))
				{
					echo '<h3>KCAU '.$_SESSION['campus_name'].' SAKU '. $_SESSION['election_period'].'  ELECTIONS VOTER TURNOUT </h3> ';
			   

			 echo '</div>
			<div class="col-md-6">
					<div class="pane panel-green" style="font-size:40px;">
					<div class="panel-heading text-right">
					Total Voter Turnout <br>
					<span id="turnout">
					   0<br> 0.0%
					</span>
					</div>
					</div>
					<br>';
				
					 if(isset($_SESSION['election_period']) and isset($_SESSION['campus']))
					 {
						
						 
							 $election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
							$campus=$_SESSION['campus'];	
						$registered_voter_qry=odbc_prepare($con,
									 				"SELECT COUNT(DISTINCT reg_no) as total_registered_voters 
													FROM Students 
													where election_period=? and campus_code=?");
							odbc_execute($registered_voter_qry,array($election_period,$campus));
								 $total_voters=odbc_fetch_array($registered_voter_qry);
								   
								 $percentage=0;
								  
							 
								 echo '<div class="pane panel-primary" style="font-size:40px;">
								 <div class="panel-heading text-right">
				 
									 '.number_format($total_voters['total_registered_voters'],0).'<br>
									 Registered Voters</div></div>';
								 }
				  
				echo '</div>
                <div class="col-md-6" id="hourly_turnout">
                   
					

             </div>';
			}
			?>
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
	//------turnout------------------------

	
	var interval = 13000;
	var refresh = function() {
		//$("#turnout").html("<img src='images/loader.gif'>Loading.............");
		var turnout="turnout";
		$.ajax({
			    type:'post',
				url:"Classes/dashboard_ajax.php",
				data:'turnout='+turnout,
				cache: false,
				success: function(data){
					$("#turnout").empty();
					$("#turnout").html(data);

					setTimeout(function() {
                    refresh();
               		 }, interval);

					}

			});


			//$("#hourly_turnout").html("<img src='images/loader.gif'>Loading.............");
		var hourly_turnout="hourly turnout";
		$.ajax({
			    type:'post',
				url:"Classes/dashboard_ajax.php",
				data:'hourly_turnout='+hourly_turnout,
				cache: false,
				success: function(data){
					$("#hourly_turnout").empty();
					$("#hourly_turnout").html(data);

					setTimeout(function() {
                    refresh();
               		 }, interval);
					}

			});

	};
	refresh();
//-------------show hourly_turnout---------------
		
		
	
		
		
		
		
	});
</script>


</body>

</html>
