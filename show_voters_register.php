<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin_ieck_chair();
//error_reporting(0);
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
                    <h3 class="page-header">KCAU SAKU ELECTIONS
						<?php if(isset($_SESSION['election_period'])){
							echo $_SESSION['election_period'];
						}
						?>
				 REGISTER</h3>
					 <?php
                   

					//---fetch the faculty  drop down
					$faculty=odbc_exec($con,"SELECT faculty_code,description from Faculty");

					echo '
					<div class="row">
							<div class="col-md-6">
					<form  method="post">';
					echo '<div class="form-group">
						<label> SELECT FACULTY:</label>
						
						<select name="faculty" class="form-control" required>
					    <option>Select</option>';
					while($faculty_row=odbc_fetch_array($faculty))
					{
					
					echo '<option value="'.$faculty_row['faculty_code'].'">'.$faculty_row['description'].'</option>';
					}
					echo '</select></div>
					<div class="form-group">     
					<input type="submit" name="filter" value="Load Courses" class="btn btn-primary" /> 
					
						</div></form>
						</div></div>';
					//set faculty session
					if(isset($_POST['filter']))
					{
						$_SESSION['faculty_code']=htmlspecialchars($_POST['faculty'],ENT_QUOTES);
						header('location:show_voters_register.php');
					}
					//----------------------------------------------------------
if(isset($_SESSION['election_period']))
{
					  
						if(isset($_SESSION['faculty_code']))
						{

						$faculty_code=htmlspecialchars($_SESSION['faculty_code'],ENT_QUOTES);
						$election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
						$query_string="SELECT  c.course_code,c.course_description ,
									count(s.course_code) as registered from  Courses c
									LEFT JOIN Students s on c.course_code=s.course_code and s.election_period ='$election_period'
									WHERE c.faculty_code='$faculty_code'
									GROUP BY c.course_code,c.course_description ORDER BY registered  desc ";
	
}
else
{
$election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
			$query_string="SELECT  c.course_code,c.course_description ,
								count(s.course_code) as registered from  Courses c
								LEFT JOIN Students s on c.course_code=s.course_code and s.election_period ='$election_period'
								GROUP BY c.course_code,c.course_description ORDER BY registered desc ";
}
					
			
         if($query=odbc_exec($con,$query_string))
         {
         $i=1; 
                    echo '
					 <div class="dataTable_wrapper">
					
                  <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
					<tr style="font-size:15px;">
					<th colspan="7">KCAU SAKU ELECTION '.$election_period.'</th>
					</tr>
					<tr>
					<th></th>
					<th>COURSE CODE</th>
					<th >COURSE TITLE</th>
					<th>NO. OF STUDENTS</th>
					<th colspan="3">DOWNLOAD</th>
					</tr>
					</thead>
					<tbody>';
                    while($row=odbc_fetch_array($query))
                    {
						echo '<tr><td>'.$i++.'</td>
						<td>'.$row['course_code'].'</td>
					<td>'.strtoupper($row['course_description']).'</td>
					<td>'.$row['registered'].'</td>
					<td>'.
					'<a href="file_download_voter_register_template.php?course_code='.$row['course_code'].
					'&course='.$row['course_description'].'& election_period='.$election_period.' ">
					<i class="fa fa-download"></i> Register Template</a>'.
					'</td>
					<td>'.
					'<a href="file_download_voters_register.php?course_code='.$row['course_code'].' & election_period='.$election_period.'
					&course='.$row['course_description'].'">
					<i class="fa fa-download"></i> Voters Register</a>'.
					'</td>
					<td>'.
					'<a href="file_download_list_voter_turnout_per_course.php?course_code='.$row['course_code'].' 
					& election_period='.$election_period.'&course='.$row['course_description'].'"> 
					<i class="fa fa-download"></i> Get 	List of voters who voted</a>'.
					'</td></tr>';
                    }	
                    echo '</tbody></table></div>';
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
	
		
		
	});
</script>
</body>

</html>
