<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin();
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
<!--modals-->
 <!---edit user--->
 <div id="enroll_candidate" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="enroll_candidate">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-edit" style="color:#FF5"></span> REGISTER CANDIDATE</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_register">
         		
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
                    <h3 class="page-header">REGISTER CANDIDATES FOR 
                        <?php  
                        if(isset($_SESSION['register_candidate_election_period']))
                        {
                        echo $_SESSION['register_candidate_election_period']; 
                        }
                        else{
                            header('location:show_candidates_elections.php');
                        }
                       echo ' SAKU ELECTION ('.$_SESSION['campus_name'].')';
                        ?> </h3>
<?php

 date_default_timezone_set ("Africa/Nairobi"); 

/*------INSERT INTO DATABASE-----------*/

if(isset($_POST['submit']))
{    $election=$_SESSION['register_candidate_election_period'];
        $structure = './Candidates/'.$election;
		$image=$_FILES['image']['name'];

		if (!is_dir($structure)){ mkdir($structure, 0777, true) or die('Failed to create folders...');}
		
		/*-----------UPLOAD IMAGE-----------------------*/
		
		$extension=pathinfo($image,PATHINFO_EXTENSION);
		//echo $extension;
		if(strtolower($extension)!="jpg" and strtolower($extension)!="jpeg" )
		{
			echo 'Error, unkown file format ';
		}
		else{
           
			$new_image_name=time().'.'.$extension;
			$reg_no=$_POST['regno'];
			$name=$_POST['names'];
			$position_code=$_POST['position'];
			$nick_name=$_POST['nick_name'];
			$campus=$_SESSION['campus'];
			copy($_FILES['image']['tmp_name'],$structure.'/'.$new_image_name);
			$query=odbc_prepare($con,"INSERT INTO candidates 
                            (reg_no,name,position_code,election_period,image,other_names,campus)
							 VALUES(?,?,?,?,?,?,?)");
			$parameters=array($reg_no,$name,$position_code,$election,$new_image_name,$nick_name,$campus);
			if(odbc_execute($query,$parameters))
			{
                echo '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Success!</strong> Candidate has been registered
              </div>';
              }
          else{
                  echo '<div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Error!</strong> Record cannot be saved
                      </div>';
              }
			
			}
		
}?>
                     
            <?php  
            if(isset($_SESSION['campus']) and isset($_SESSION['register_candidate_election_period']))  
            {

           
            echo '<form action="" method="post">
                <div class="form-group">
                <i class="fa fa-search"></i> SEARCH STUDENT: 
                <input type="text" id="txtsearch" name="reg" />
          
            </div>
            
            <div class="form-group" id="search-result">';
            
           
           
           echo '</div>
            </form> ';   
          }
          else{
              header('location:show_candidates_elections.php');
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
        $("#txtsearch").keyup(function(){
					
                    var student=$("#txtsearch").val();
                    if($.trim(student)=="")
                    {
                        $("#search-result").empty();
                    }
                    else
                    {
                        $("#search-result").html('<img src="images/loader.gif">searching.................................');
                        $.post("Classes/_ajax_requests_functions.php",{search_student: student, id:Math.random()}, function(data){
                                                                                              
                  $("#search-result").html(data);
                  });
                
                    }
                    
                    });	

                    //------------Register---------------
		$("#enroll_candidate").on('show.bs.modal',function(e){
		$("#load_register").empty();
		$("#load_register").html("<img src='images/loader.gif'>Loading.............");
		var register_candidate_code=$(e.relatedTarget).data('id');
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'register_candidate_code='+register_candidate_code,
				success: function(data){
					$("#load_register").html(data);
					}

			});
		
		});

		
		
	});
</script>
</body>

</html>
