<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin();
include 'config/db_con.php';
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
  
<!---Election Modal--->
<div id="add_election_period" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_position">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> NEW SAKU ELECTION PERIOD</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body">
        <div class="form-group">
			  <label>Election Period:</label>
			  
			  <input type="text" name="election_title" 
			  <?php
			  $next_year= date('Y')+1; echo 'value="'. date('Y').'-'.$next_year.'"'; ?> class="form-control" required >
			 
			  </div>
        <div class="form-group">
          <label>Election Date:</label>
          <input type="date" name="election_date" class="form-control" required>
        </div>
		<div class="form-group">
          <label>Election Type:</label>
         <?php
			$sql=odbc_prepare($con,"SELECT * FROM election_type");
			odbc_execute($sql);
			echo '<select name="election_type" class="form-control" required>';
				if(odbc_num_rows($sql)>0){
					while($row=odbc_fetch_array($sql))
					{
						echo '<option value="'.$row['electionType'].'">'.$row['electionType'].'</option>';
					}
				}

			echo '</select>'
		 ?>
        </div>
       
			 
		</div>
       
        <div class="modal-footer">
         <input type="submit" name="save_election" value="Save" class="btn btn-primary">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>

  <!---Edit Election Modal--->
<div id="edit_election" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="edit_election">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> NEW SAKU ELECTION PERIOD</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_edit_election">
        
       
			 
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
                    <h3 class="page-header">KCAU SAKU ELECTIONS</h3>
					 <?php
                   
					//----------------------------------------------------------
					if(isset($_POST['save_election']) or isset($_POST['save_edit_election']))
					{
						$election_title=htmlspecialchars($_POST['election_title'],ENT_QUOTES);
						$election_type=htmlspecialchars($_POST['election_type'],ENT_QUOTES);
						$election_date=htmlspecialchars($_POST['election_date'],ENT_QUOTES);

						if(isset($_POST['save_election']))
						{
							$status='open';
						$str="INSERT INTO elections (election_period,election_date,status,election_type) 
							VALUES(?,?,?,?)";
						$qry=odbc_prepare($con,$str);
						$params=array($election_title,$election_date,$status,$election_type);	
						}
						elseif( isset($_POST['save_edit_election']))
						{
							$election_period=htmlspecialchars($_POST['election_period'],ENT_QUOTES);
							$str="UPDATE elections SET election_period=?,election_date=?,election_type=?
							    WHERE  election_period=?";
						$qry=odbc_prepare($con,$str);
						$params=array($election_title,$election_date,$election_type,$election_period);

						}	

					 if(odbc_execute($qry,$params))
						{
						 echo '<div class="alert alert-success">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Success!</strong> Election has been saved 
								</div>';
						}
						 else{
						  echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> Election cannot updated
								</div>';
						   
						  }
					
					}
					//--------------------------------------------------------
					if(isset($_REQUEST['election_period']) and isset($_REQUEST['election_status']))
					{
					    $election_period=htmlspecialchars($_REQUEST['election_period'],ENT_QUOTES);
						$election_status=htmlspecialchars($_REQUEST['election_status'],ENT_QUOTES);
						$str="UPDATE elections SET status='$election_status' WHERE election_period='$election_period'";
						if($qry=odbc_exec($con,$str))
						{
							header('location:create_elections.php');
						 echo '<div class="alert alert-success">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Success!</strong> Record has been saved 
								</div>';
						}
						 else{
						  echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> Cannot be saved
								</div>';
						   
						  }
					
					}
					
					
					
                    if($query=odbc_exec($con,"SELECT e.election_period,e.status,e.election_date ,
											count(DISTINCT s.reg_no) as total_registered_voters,
											count(DISTINCT v.student_reg) as total_voted,e.election_type
											from elections  e
											LEFT JOIN Students s on s.election_period=e.election_period
											LEFT JOIN votes v on v.student_reg=s.reg_no and v.election_period=s.election_period
											GROUP BY e.election_period,e.election_date,e.status,e.election_type
											ORDER BY e.election_period DESC"))
                    {
                        $i=1; 
                    echo '
					 <div class="dataTable_wrapper">
					<p>
					  <a href="" data-target="#add_election_period" class="btn btn-success" data-toggle="modal">
									<i class="fa fa-plus-circle "></i> Create New Election Period</a><p>
                  <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
					<tr>
					<th></th>
					<th>Election Period</th>
					<th>Type</th>
					<th>Election Date</th>
					<th class="text-right">Registered Voters</th>
					<th class="text-right">Voter Turnout</th>
					<th class="text-right"> (%) Turnout</th>
                 	 <th>Status </th>
                    <th></th>
					</tr>
					</thead>
					<tbody>';
                    while($row=odbc_fetch_array($query))
                    {
						 $per_turnout=0;
						if($row['total_registered_voters']>0)
						{
							$per_turnout=$row['total_voted']/$row['total_registered_voters']*100;
						}
				
					echo '<tr>
					<td>'.$i++.'.</td>
					<td>'.$row['election_period'].'</td>
					<td>'.$row['election_type'].'</td>
					<td>'.date('D, d-M-Y',strtotime($row['election_date'])).'</td>
					<td class="text-right">'.number_format($row['total_registered_voters'],0).'</td>
					<td class="text-right">'.number_format($row['total_voted'],0).'</td>
					<td class="text-right">'.number_format($per_turnout,2).'%</td>
					<td>'.$row['status']. '</td>
					<td><span class="dropdown">
			<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class="dropdown-menu pull-right">';
						if($row['status']=='closed')
						{
						echo '<li><a href="?election_period='.$row['election_period'].' & election_status=open" >
						<span class="glyphicon glyphicon-ok-circle"></span> Open Election</a></li>';
						}
						else{
							echo '<li><a href="?election_period='.$row['election_period'].' & election_status=closed">
						<span class="glyphicon glyphicon-ban-circle" ></span> Close Election</a></li>';
							}
						echo '<li>
						<a href="" data-id="'.$row['election_period'].'" data-target="#edit_election" data-toggle="modal" >
						<span class="glyphicon glyphicon-edit" style="color:#FC6"></span> Edit</a></li>
                        <li><a href="" data-id="'.$row['election_period'].'" data-target="#add_department" data-toggle="modal">
                        <span class="fa fa-bar-chart-o" style="color:green"></span> View Election Results</a></li>
						
					</ul>
                       
                        
                        </td></tr>';
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
	//---------------------------------------------------

		$("#kca_faculty").change(function(e){
		$("#kca_faculty_department").empty();
		$("#kca_faculty_department").html("<img src='images/loader.gif'>Loading departments.............");
		var faculty_code=$("#kca_faculty").val();
		
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'add_position_department_faculty_code='+faculty_code,
				success: function(data){
					$("#kca_faculty_department").html(data);
					}

			});
		
		});
		

		
	//-------------Edit Election---------------
		$("#edit_election").on('show.bs.modal',function(e){
		$("#load_edit_election").empty();
		$("#load_edit_election").html("<img src='images/loader.gif'>Loading.............");
		var edit_election_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'edit_election_code='+edit_election_code,
				success: function(data){
					$("#load_edit_election").html(data);
					}

			});
		
		});
		

		
		
	});
</script>
</body>

</html>
