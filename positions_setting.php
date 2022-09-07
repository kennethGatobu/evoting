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
  
<!---add position modal--->
<div id="add_position" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_position">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> NEW SAKU POSITION</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body">
        <div class="form-group">
			  <label>Position:</label>
			  
			  <input type="text" name="position_title"  class="form-control" required >
			 
			  </div>
			  
			   <?php
                   
					$sql=odbc_exec($con,"SELECT faculty_code,description from Faculty ORDER BY description ASC");
					if($sql)
					{
					echo '<div class="form-group">
					<label>Select Faculty:</label>
					<select id="faculty" name="faculty" class="form-control" required>
					 <option value="" >Select</option>';
					while($row=odbc_fetch_array($sql))
					{
						echo '<option value="'.$row['faculty_code'].'">'.ucwords(strtolower($row['description'])).'</option>';
					}
					
					 echo '</select>
					 </div>
					   <div class="form-group">
					        <label>Select Department that can vote here:</label>
						</div>
					   <div class="form-group" id="faculty_department">
					   
					   
					   </div>
					   ';
					
					}
				
				?>
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
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!---add position modal--->
<div id="add_department" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_department">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> Set Department to vote for this position</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" >
        <div class="form-group" id="load_add_department"></div>
                     <?php
					$sql=odbc_exec($con,"SELECT faculty_code,description from Faculty ORDER BY description ASC");
					if($sql)
					{
					echo '<div class="form-group">
					<label>Select Faculty:</label>
					<select id="kca_faculty" name="faculty" class="form-control" required>
					 <option value="" >Select</option>';
					while($row=odbc_fetch_array($sql))
					{
						echo '<option value="'.$row['faculty_code'].'">'.ucwords(strtolower($row['description'])).'</option>';
					}
					
					 echo '</select>
					 </div>
					   <div class="form-group">
					        <label>Select Department that can vote here:</label>
						</div>
					   <div class="form-group" id="kca_faculty_department">
					   
					   
					   </div>
					   ';
					
					}
					 
					 ?>
                     
                     
			 
		 </div>
       
        <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!---remove department modal--->
<div id="remove_department" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="remove_department">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> Remove Department from this position</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" >
        <div class="form-group" id="load_remove_department"></div>
                    
			 
		 </div>
       
        <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
   <!---edit department modal--->
<div id="edit_position" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="edit_position">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> EDIT POSITION</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" >
        <div class="form-group" id="load_edit_position"></div>
                    
			 
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
                    <h3 class="page-header">KCAU SAKU POSITIONS</h3>
					 <?php
                   
					//----------------------------------------------------------
					if(isset($_POST['edit_position']))
					{
						$position_code=htmlspecialchars($_POST['position_code'],ENT_QUOTES);
						$position_title=htmlspecialchars($_POST['position_tittle'],ENT_QUOTES);
						$position_status=htmlspecialchars($_POST['position_status'],ENT_QUOTES);
						$election_type=htmlspecialchars($_POST['election_type'],ENT_QUOTES);
					$str="UPDATE [position] SET description=?,position_status=?,election_type=? 
								where position_code=?";
					$params=array($position_title,$position_status,$election_type,$position_code);
					$qry=odbc_prepare($con,$str);
					 if(odbc_execute($qry,$params))
						{
						 echo '<div class="alert alert-success">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Success!</strong> position has been saved 
								</div>';
						}
						 else{
						  echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> position cannot updated
								</div>';
						   
						  }
					
					}
					//--------------------------------------------------------
					if(isset($_POST['delete']))
					{
					    $record_id=htmlspecialchars($_POST['record_id'],ENT_QUOTES);
						$str="DELETE FROM positions_department_relationship WHERE record_id='$record_id'";
						if($qry=odbc_exec($con,$str))
						{
						 echo '<div class="alert alert-success">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Success!</strong> Department has been removed 
								</div>';
						}
						 else{
						  echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> department cannot be removed
								</div>';
						   
						  }
					
					}
					//------------------------------------------------------------------------------------------------
					if(isset($_POST['save_position']) or isset($_POST['save_position_departments']))
					{
						if(isset($_POST['save_position']))
						 {
						 $position_code='SAKU-'.mt_rand(1,99999).'-'.time();
						 }
						 else{
							  $position_code=htmlspecialchars($_POST['position_title'],ENT_QUOTES);
							 }
					  $position_title=htmlspecialchars($_POST['position_title'],ENT_QUOTES);
					  $faculty_code=htmlspecialchars($_POST['faculty'],ENT_QUOTES);
					  $position_status='active';
					  $rating='1';
					  
					  if(!empty($_POST['department']))
					  {
						 //----------------------------------------------------------------------------------------
						 if(isset($_POST['save_position']))
						 {
						 $position_code='SAKU-'.mt_rand(1,99999).'-'.time();
						 $election_type=htmlspecialchars($_POST['election_type'],ENT_QUOTES);
						 $p_qry="INSERT INTO position
						 			(position_code,description,faculty_code,priority_rating,position_status,election_type)
						 		VALUES(?,?,?,?,?,?)";
						$params=array($position_code,$position_title,$faculty_code,
									$rating,$position_status,$election_type);
						$qry=odbc_prepare($con,$p_qry);
						 if(odbc_execute($qry,$params));
						 
						 }
						 
						 //---------------insert the position -department relatioship---------------------------
						  $i=0;
						  $y=0;
						  foreach($_POST['department'] as $department_code)
						  {
						      $record_id=mt_rand(1000,99999).'-'.time();
							  $qry="INSERT INTO positions_department_relationship (position_code,department_code,record_id)
							     	   VALUES('$position_code','$department_code','$record_id')";
							 if($sql=odbc_exec($con,$qry))
							 {
								 $i++;
							 }
							 else{
								 $y++;
								 }
						  
						  }
						  //--------------------------------------------------------------------------------------
								  echo '<div class="alert alert-success">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Success!</strong> Records saved ' .$i.'<span style="color:red"> Failed '.$y.'</span>
								</div>';
						  
						 
						   
						  
						  
						  
						  ///-----------------------------------------------
						  
					  }
					  else{
						  echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> Select atleast one department
								</div>';
						   
						  }
					
					}
					
					
					//------------------------------------------------------------------------------------------------------
                    $query=odbc_exec($con,"SELECT p.position_code,p.description,p.position_status,count(fp.position_code) as total_faculty,p.election_type
											FROM [position] p
											LEFT JOIN positions_department_relationship fp on fp.position_code=p.position_code
											GROUP BY p.position_code,p.description,p.position_status,p.election_type
											ORDER BY p.position_status  ASC");
                    if($query)
                    {
                        $i=1; 
                    echo '
					 <div class="dataTable_wrapper">
					<p>
					  <a href="" data-target="#add_position" class="btn btn-success" data-toggle="modal">
									<i class="fa fa-plus-circle "></i> Create New Position</a><p>
                  <table style="font:12px Tahoma, Arial, sans-serif" class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
					<tr>
					<th></th>
					<th>Position</th>
					<th>Type</th>
					<th>Departments that can vote here</th>
                 	 <th>Status </th>
                    <th></th>
					</tr>
					</thead>
					<tbody>';
                    while($row=odbc_fetch_array($query))
                    {
				$position_code=$row['position_code'];
                        echo '<tr>
						<td>'.$i++.'.</td>
						<td>
						<a href="">'. ucwords(strtolower($row['description'])).'</a>
						</td>
						<td>'.$row['election_type'].'</td>
						<td>';
						//------------------------fetch departments------------------------------
						$d_qry=odbc_exec($con,"SELECT pd.record_id,d.department_code,d.department_name 
											from positions_department_relationship pd 
											INNER JOIN  faculty_academic_departments d on d.department_code=pd.department_code
											WHERE pd.position_code='$position_code'");
						echo '<ol type="a">';
						while($d_row=odbc_fetch_array($d_qry))
						{
						  echo '<li>
						  <a href="">' .$d_row['department_name'].'</a>
						   <a href="" data-id="'.$d_row['record_id'].'" data-target="#remove_department" data-toggle="modal">
						   [<i style="color:red" class="glyphicon glyphicon-remove-sign"></i>]</a>
						   </li>';
						
						}
						echo '</ol>';
						
						//-------------------------------------------------------------------------
						echo '</td>'.
						'<td>'. $row['position_status'].'</td><td>
						<span class="dropdown">
					<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class="dropdown-menu pull-right">
						
						<li><a href="" data-id="'.$row['position_code'].'" data-target="#edit_position" data-toggle="modal" >
						<span class="glyphicon glyphicon-edit" style="color:#FC6"></span> Edit</a></li>
                        <li><a href="" data-id="'.$row['position_code'].'" data-target="#add_department" data-toggle="modal">
                        <span class="fa fa-chain" style="color:green"></span> Add Department to vote here</a></li>
						<li><a href="view_positions_details.php?position_code='.$row['position_code'].' & position='.$row['description'].'">
                       <span class="glyphicon glyphicon-fullscreen"></span> View Details</a></li>
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
		
//---------------------------------------------------

		$("#faculty").change(function(e){
		$("#faculty_department").empty();
		$("#faculty_department").html("<img src='images/loader.gif'>Loading departments.............");
		var faculty_code=$("#faculty").val();
		
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'table_department_faculty_code='+faculty_code,
				success: function(data){
					$("#faculty_department").html(data);
					}

			});
		
		});
		//-------------add position department---------------
		$("#add_department").on('show.bs.modal',function(e){
		$("#load_add_department").empty();
		$("#load_add_department").html("<img src='images/loader.gif'>Loading.............");
		var add_department_posistion_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'add_department_posistion_code='+add_department_posistion_code,
				success: function(data){
					$("#load_add_department").html(data);
					}

			});
		
		});
	//-------------add position---------------
		$("#edit_position").on('show.bs.modal',function(e){
		$("#load_edit_position").empty();
		$("#load_edit_position").html("<img src='images/loader.gif'>Loading.............");
		var edit_posistion_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'edit_posistion_code='+edit_posistion_code,
				success: function(data){
					$("#load_edit_position").html(data);
					}

			});
		
		});
		
//-------------add position department---------------
		$("#remove_department").on('show.bs.modal',function(e){
		$("#load_remove_department").empty();
		$("#load_remove_department").html("<img src='images/loader.gif'>Loading.............");
		var add_department_posistion_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'remove_department_posistion_code='+add_department_posistion_code,
				success: function(data){
					$("#load_remove_department").html(data);
					}

			});
		
		});
		
		
		
	});
</script>
</body>

</html>
