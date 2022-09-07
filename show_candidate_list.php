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
<!--upload news image-->
<!------------------------------------Logo uploader--------------------------------------------------->
<div id="photo_uploader" class="modal fade" aria-hidden="true" aria-labelledby="photo_uploader" tabindex="-1" role="dialog">
  <div class="modal-dialog">
  	<div class="modal-content">
       <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal">&times;</button>
       <h3 class="modal-title"><span style="color:#0F0" class="glyphicon glyphicon-upload"></span>Upload Candidate Photo</h3>
       </div>
       <form role="form" method="post" enctype="multipart/form-data">
       <div id="upload_contents" class="modal-body">
       
       </div>
      
       <div class="modal-footer">
       				
       		       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

       </div>
       </form>
    </div>
  </div>
</div>

  <!--end modals here -->
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" 
        style="margin-bottom: 0; background:#2F637A; color:#FFF; border-top:solid 13pt khaki;" >
           

          
		   
		   
		    <?php
   			 include 'Classes/new_links.php';
    
		     ?>
            <!-- /.navbar-static-side -->
        </nav>
   



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">List of Candidates</h3>
					 
					
<?php




//----------upload photo--------------------
if(isset($_POST['upload']))
{
	include 'Classes/image_functions.php';
  $election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
  $old_photo=htmlspecialchars($_POST['old_photo'],ENT_QUOTES);
  $reg_no=htmlspecialchars($_POST['reg_no'],ENT_QUOTES);
  $image=$_FILES['photo']['name'];
  $extension=strtolower(pathinfo($image,PATHINFO_EXTENSION));
  $extension_list=array("jpg","gif","png","jpeg");
  if(in_array($extension,$extension_list))
  {
	   $path='./Candidates/'.$election_period;
	   $image_name='new_'.time().'_'.rand(10000,99999).''.rand(1000,9999);
	  if (!is_dir($path)){ mkdir($path, 0777, true) or 
		die('<p align="center" id="error_msg">Failed to create folders...</p>');}
		$new_name=$image_name.'.'.$extension;
		$fullpath=$path.'/'.$new_name;
		$dst='Candidates/'.$election_period.'/';
		move_uploaded_file($_FILES['photo']['tmp_name'],$fullpath);
	   thumbnail( $new_name, $dst, $dst, 480, 400 );
	   $str="UPDATE candidates set image='$new_name' WHERE reg_no='$reg_no' and election_period='$election_period'";
	  
	   if($query=odbc_exec($con,$str))
	   {
		   unlink($path.'/'.$old_photo);
		   echo '<div class="alert alert-success">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
				   <strong>Success!</strong> Photo has  been uploaded</div>';
		   
	   }else{echo '<div class="alert alert-danger">
	   <button type="button" class="close" data-dismiss="alert">&times;</button>
	   <strong>Error!</strong> Logo has not been uploaded</div>';}
	   
  }
  else{echo '<div class="alert alert-danger"><strong>Warning!</strong> Unknown File format</div>';}

}




//---------------------------------------------------

if(isset($_GET['election_period']) and isset($_SESSION['campus']))
{
$_SESSION['election_period']=htmlspecialchars($_GET['election_period'],ENT_QUOTES);
$election_period=htmlspecialchars($_GET['election_period'],ENT_QUOTES);
$position_code=htmlspecialchars($_GET['position_code'],ENT_QUOTES);
$position=htmlspecialchars($_GET['position'],ENT_QUOTES);
$campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);
		$query=odbc_prepare($con,"SELECT *FROM candidates 
                   where  position_code=?
									 AND election_period=? and campus=?");
		odbc_execute($query,array($position_code,$election_period,$campus));
if(odbc_num_rows($query))
{
	$i=1;
echo '<table class="table table-bordered table-hover table-striped">'.
'<tr><th colspan="5">'.$election_period.' SAKU ELECTIONS ('.$_SESSION['campus_name'].')</th></tr>'.
'<tr>
<th colspan="5">POSITION: '. $position . '</th></tr>

<tr>
<th>&nbsp;</th>
<th>Photo</th>
<th>Reg NO.</th>
<th>Candidate Name</th>
<th>#</th></tr>';
while($row=odbc_fetch_array($query))
{
	$img="Candidates/".$election_period.'/'.$row['image'];
echo '<tr><td>'.
		$i++.
	'</td><td>
	<img src="'.$img.'" height="100" width="100" >'.
	'</td><td>'.
	$row['reg_no'].
	'</td><td>'.
	$row['name'].
	'</td>
	<td>
	<a href="" data-toggle="modal" data-target="#photo_uploader" data-id="'.$row['reg_no'].'">
	<span style="color:green" class="glyphicon glyphicon-upload"></span> Change Photo</a></td>
	</tr>';
}	
echo '<tr><td colspan="4">
	
	<a id="link_button" href="show_positions.php?ballot='.$election_period.'" >Go Back</a>
	</td><td>
	<a  id="link_button" href="file_download_ballot.php?position_code='.$position_code.'
		 & election_period='.$election_period.'& position='.$position.'">Download Ballot Paper</a>
	</td></tr></table>';
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
		
//---------------------------------------------------
//---------------------upload_photo------------------------------------------
	
	$("#photo_uploader").on('show.bs.modal',function(e){
	  $("#upload_contents").empty();
	  $("#upload_contents").html("<img src='images/loader.gif'>Loading.............");
	  var upload_photo_candidate_code=$(e.relatedTarget).data('id');
	  $.ajax({
		     type:'post',
			 url:"Classes/_ajax_requests_functions.php",
			 data:'upload_photo_candidate_code='+upload_photo_candidate_code,
			 success: function(data){
				 $("#upload_contents").html(data);
				 }
		  });	
	
	
	});
		
		
	});
</script>
</body>

</html>
