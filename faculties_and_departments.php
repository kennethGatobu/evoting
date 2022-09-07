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
   <!--upload news image-->
<div id="new_photo" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="new_photo">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-upload"></span> &nbsp; News Photo</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_text">
         
        </div>
        <div class="modal-footer">
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
<!---add news modal--->
<div id="add_department" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_department">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> New Academic Department</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_faculty_new_department">
        
         </div>
        <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!---preview images--->
  
<div id="preview_news" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="preview_news">
   <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-file"></span> &nbsp;School News</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_preview">
        
        </div>
        <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!---edit news modal--->
<div id="edit_news" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="edit_news">
   <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-edit"></span> &nbsp;School News</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_news">
        
        </div>
        <div class="modal-footer">
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>
  <!--end modals here -->
  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Faculties &amp; Academic Departments</h3>
					 <?php
                  
					if(isset($_POST['save_department']))
					{
					     $faculty_code=htmlspecialchars($_POST['faculty_code'],ENT_QUOTES);	
						 $department_name=htmlspecialchars($_POST['department_name'],ENT_QUOTES);
						 $department_code=mt_rand(10000,99999999).'-'.time();
						 $insert_department=odbc_exec($con,"INSERT INTO faculty_academic_departments
						 								(department_code,department_name,faculty_code )
														VALUES('$department_code','$department_name','$faculty_code')");
						if($insert_department)
						{
						echo '<div class="alert alert-success">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Success!</strong> Record has been saved
						</div>';
						}
					else{
							echo '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert">&times;</button>
								  <strong>Error!</strong> Record cannot be saved
								</div>';
						}
					
					
					
					}
					
					
                   $query=odbc_exec($con,"SELECT faculty_code,description from Faculty ORDER BY description ASC ");
				if($query)
				{
					$i=1;
					 echo '
					<p class="text-right">
					  <a href="" data-target="#add_news" class="btn btn-success" data-toggle="modal">
									<i class="fa fa-plus-circle "></i> Create New Faculty</a><p>
                    <ol>';
				while($row=odbc_fetch_array($query))
				{
					$faculty_code=$row['faculty_code'];
					echo ' <li><h4>'.ucwords(strtolower($row['description'])).'</h4></li>';
					 //----------fetch departments---
					      $select_department=odbc_exec($con,"SELECT f.department_code,f.department_name 
						  									FROM faculty_academic_departments f WHERE f.faculty_code='$faculty_code'");
						   if($select_department)
						   {
							   echo '<ol type="a">';
							   while($rw=odbc_fetch_array($select_department))
							   {
								   echo '<li><a href="">'.$rw['department_name'].'</a></li>';
								   
							   }
							   echo '</ol>';
						   }
					 
					 //--------------end--------------
					echo '
					
					<div class="text-right">
					<span class="dropdown">
					<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown">Actions<span class="caret"></span></a>
							
					<ul class=" dropdown-menu pull-right">
						
						<li><a href=""><span class="glyphicon glyphicon-edit" style="color:#FC6"></span> Edit Faculty</a></li>
                        <li><a href="" data-id="'.$row['faculty_code'].'" data-target="#add_department" data-toggle="modal">
						<i class="fa fa-plus-circle" style="color:green"></i> New Department</a></li>
						<li><a href="">
                       <span class="glyphicon glyphicon-download"></span> Download Details</a></li>
					</ul>
					
					</div>
					';
				}	
				echo '</ol>';
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
//------------upload image---------------
		$("#new_photo").on('show.bs.modal',function(e){
		$("#load_text").empty();
		$("#load_text").html("<img src='images/loader.gif'>Loading.............");
		var image_news_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"classes/_ajax_requests_functions.php",
				data:'image_news_code='+image_news_code,
				success: function(data){
					$("#load_text").html(data);
					}

			});
		
		});
		//-------------Edit news---------------
		$("#edit_news").on('show.bs.modal',function(e){
		$("#load_news").empty();
		$("#load_news").html("<img src='images/loader.gif'>Loading.............");
		var edit_news_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"classes/_ajax_requests_functions.php",
				data:'edit_news_code='+edit_news_code,
				success: function(data){
					$("#load_news").html(data);
					}

			});
		
		});
		//------------preview news---------------
		$("#add_department").on('show.bs.modal',function(e){
		$("#load_faculty_new_department").empty();
		$("#load_faculty_new_department").html("<img src='images/loader.gif'>Loading.............");
		var faculty_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'department_faculty_code='+faculty_code,
				success: function(data){
					$("#load_faculty_new_department").html(data);
					}

			});
		
		});


		
	});
</script>
</body>

</html>
