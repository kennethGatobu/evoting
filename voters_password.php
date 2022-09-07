<?php
session_start();
include '_SESSIONS_/_sessions.php';
admin_clerk();
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
   

  <!---show password--->
<div id="show_password_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="show_password_modal">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-lock" style="color:#FF5"></span> Voters Password</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_password_details">
         		
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
            <div class="row">
                      
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-users"></i> User Password</h3>
                     <div class="col-lg-12">
                       <p style="color:red;"><sup>*</sup>Hello <strong><?php echo $_SESSION['name'] ?></strong>, 
                      Please note that all the actions that you undertake on this system are recorded.</p> 
                       </div>
                                       
                      </div>
                    <form method="post">
                    <div class="form-group">
					 <div class="col-xs-4">
                     <label><i class="fa fa-search"></i>Search Student by Admission NO or Name:</label>
                      <input type="text" id="txtsearch" name="reg" class="form-control" placeholder="Type here....">
                      
                     </div>
                     
                     </div>
                       </form>
                       <br>
                       
             </div>
             <div class="row">
             <div class="col-lg-12" id="search-result">
              
             </div>
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
		//-----------------------------------------------
		$("#txtsearch").keyup(function(){
					
			var student=$("#txtsearch").val();
			if($.trim(student)=="")
			{
				$("#search-result").empty();
			}
			else
			{
				$("#search-result").html('<img src="images/loader.gif">Searching.................................');
				$.post("Classes/_ajax_requests_functions.php",{reg: student, id:Math.random()}, function(data){
																					  
		  $("#search-result").html(data);
		  });
		
			}
			
	});

		//------------edit user---------------
		$("#show_password_modal").on('show.bs.modal',function(e){
		$("#load_password_details").empty();
		$("#load_password_details").html("<img src='images/loader.gif'>Loading.............");
		var reg_no=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'student_reg_no='+reg_no,
				success: function(data){
					$("#load_password_details").html(data);
					}

			});
		
		});


		
	});
</script>
</body>

</html>
