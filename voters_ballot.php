<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
student();
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
#candidate_list{
	border:solid 1px;
	 /*width:88%; */ 
	 border-radius:5px;
	 font-size:16px;
	  
	 
	 margin:10px;
	 box-shadow:5px 5px #999; 
	 -khtml-box-shadow:5px 5px #999;
	 }
#candidate_image_border{
	 border-style: solid;
    border-color:#CCC;
	}
#candidate_list h2{color:#666;}
#candidate_list tr:hover{ background:#CBCBE4;}
#candidate_radio_button{
	width:25px;
	 height:25px;
	 cursor:pointer;
	background:#CCC;
	
	border-radius: 100%;
	position: relative;
	-webkit-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
	-moz-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
	box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
	 
	 }

input[type=radio]:checked { 
   background-image: none;
    background-color:#090;
}
#submit_button{
	margin-bottom:20px;
	width:250px;
 height:50px;
  background:#F00;
  font-size:16px;
  color:#FFF;
  font-weight:bold;}
  #submit_button:hover{
	  cursor:pointer;
	  }
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
                    
					 <?php
                   
				include 'functions/current_election.php';

				




				$reg_no=htmlspecialchars($_SESSION['voter_info'],ENT_QUOTES);
				$campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);
				$check_voter=odbc_prepare($con,"SELECT count(student_reg) as total_count 
											FROM votes where student_reg=? and election_period=?");
				$param=array($reg_no,$election);
				if(odbc_execute($check_voter,$param))
				{
					$total_found_voter=odbc_fetch_array($check_voter);
					if($total_found_voter['total_count']>=1)
					{
						already_voted($con);
						//header("location:_MESSAGES_/vote_confirmation.php");
					}
					else
					{
						echo '
				<div class="page-header text-center">
				<h3 class="text-info">'."SAKU ".$election.' ELECTIONS ('.$_SESSION['campus_name'].')</h3>'.
				'<h4 style="color:brown;">'."Step 1 of 2 : Select Your Choice and Click the button below to proceed".'</h4></div>';		
						$query=odbc_prepare($con,"SELECT p.position_code,p.[description],s.campus_code
													FROM Students s
													INNER join elections e on e.election_period=s.election_period
													INNER JOIN Courses c on c.course_code=s.course_code
													INNER JOIN faculty_academic_departments f on f.department_code=c.department_code
													INNER JOIN positions_department_relationship r on r.department_code=f.department_code
													INNER JOIN [position] p on p.position_code=r.position_code 
													and p.election_type=e.election_type and p.position_status='active'
													where s.reg_no=? and s.election_period=?
													ORDER BY p.priority_rating ASC");
							$paramenter=array($reg_no,$election);					
							if(odbc_execute($query,$paramenter))
							{
								echo '<form action="_vote_preview_.php" method="post">';
						
								while($position_row=odbc_fetch_array($query))
								{
									$position_code=$position_row['position_code'];
									echo '<div class="col-md-11" id="candidate_list">
									<h3 class="text-info">'.$position_row['description'].'</h3>';
										
								$candidate_query=odbc_prepare($con,"SELECT c.election_period,c.reg_no,
															c.name,c.other_names,c.position_code,c.image 
															FROM candidates c 
															WHERE c.position_code=? AND c.election_period=?
															AND c.campus=?");
									$candidate_parameter=array($position_code,$election,$campus);
									if(odbc_execute($candidate_query,$candidate_parameter))						
									echo '<table class="table table-hover">
									<tr bgcolor="#666" style="font-weight:bold; font-size:15px; color:white;">
											<td>PHOTO</td>
											<td>CANDIDATE NAME</td>
											<td></td>
											<td>SELECT</td>
											</tr>';
										while($ca=odbc_fetch_array($candidate_query))
										{
										echo '<tr >
										<td width=110>'.
										"<img height=100 id='candidate_image_border' width=105 src='Candidates/".$ca['election_period'].'/'.$ca['image']."'>".
										'</td>
										<td title="Click on the round circle to select"  align="left">
										'.$ca['name'].
										'</td>
										<td>'. $ca['other_names'].'</td>
										<td>
						<input id="candidate_radio_button" title="Click here to select this Candidate" type="radio"  name="nm['.$position_row['description'].']" value="'.$ca['reg_no'].'" required>
										</td></tr>'; 
										}	
										echo '</table></div>';
								}
					echo '
					<div class="form-group text-center">
					<input class="btn btn-danger" type="submit" name="preview_vote" value="Click here to Proceed >>">
					</div>
					</form>';
							}
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
		//----------------------------------------------
		
		
	});
</script>
</body>

</html>
