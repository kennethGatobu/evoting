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
   
<!---add news modal--->
<div id="new_api_key" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="add_user">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-key"></span>New API Key(Token)</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" >
         		<div class="form-group">
                  <p>Click on the button below to generate your API Key(Token)</p>
				  <p class="alert alert-warning"><strong>Notice:</strong> Copy the generated Key because once you close this window you wont be able to see this key  again. However, you can generate a new key</p>
               </div>
               <div class="form-group">
                 
                  <a href="" class="btn btn-primary" id="generate_key"
				  data-id="<?php echo $_SESSION['logged_user'] ?>">Click here to generate a new API Key (Token)</a>
               </div>
			   <div class="form-group" id="key">
                 
                  
               </div>
               
                  
              
        
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
                    <h3 class="page-header"><i class="fa fa-file"></i> My Profile</h3>
					<?php
					if(isset($_SESSION['logged_user']))
					{
						$username=htmlspecialchars($_SESSION['logged_user'],ENT_QUOTES);
						$qry=odbc_prepare($con,"SELECT * FROM admin where username=?");
						odbc_execute($qry,array($username));
						if (odbc_num_rows($qry)>0) {
							$row=odbc_fetch_array($qry);

							echo '<div class="row" style="font-size:12px;">';
							//-------display personal information--------------
							echo '<div class="col-md-6">
									<div class="panel panel-default">
									   <div class="panel-body">
								 <form class="form-horizontal">
								  <div class="form-group" >
									   <div class="col-md-12" >
									  <img src="images/noface.jpeg" style="height:140px; width:130px;" class="img-responsive">
									  </div>
									  </div>
									  
									 <div class="form-group">
									  <label class="col-md-3">Fullname:</label>
									  <div  class="col-md-8" style="border-bottom:dashed 1px">
									  <strong>'.$row['name'].'</strong></div>
									  </div>
									  <div class="form-group">
									 <label class="col-md-3"> Username: </label>
									 <div  class="col-md-8" style="border-bottom:dashed 1px">'.$row['username'].'</div>
									 </div>
									  <div class="form-group">
									 <label class="col-md-3"> User Role: </label>
									 <div  class="col-md-8" style="border-bottom:dashed 1px">'.$row['user_type'].'</div>
									 </div>
									 
									 <div class="form-group">
									 <label class="col-md-3">Account Status: </label>
									 <div  class="col-md-8" style="border-bottom:dashed 1px">'.$row['account_status'].
									 '</div></div>

									 </div>
									 </div>
									 </div>

									 <div class="col-md-6">
									 <div class="panel panel-default">
									 <div class="panel-heading">
									 <h2 class="panel-title">User Account Actitivites</h2>
									 </div>
										<div class="panel-body">

										</div>
										</div>
										</div>




									 <div class="col-md-12">
									<div class="panel panel-default">
									<div class="panel-heading">
									<h3 class="panel-title">APIs Documentation</h3>
									</div>
									   <div class="panel-body">

									   <h3>Voter Register Data Imports API</h3>
									   <p>-This Provides an interface for populating voters data from an external system.
									   <p class="alert alert-warning"><strong>*Preliquisite configurations:</strong> Active(Open) election period</p>
									   <h4>API Key (Token)</h4>
										<p><a href=""  data-target="#new_api_key" class="btn btn-primary" data-toggle="modal">Click here to Generate your API Key (token)</a></p>
										<h4>Endpoint Structure</h4>
										<p>{protocol}://{host}/{resource}</p>
										<p>Example: http://kcaevoting.net/data.php</p>
										<H4>API Call</h3>
										<p>The endpoint takes the following parameters:</p>
										   <P><strong>Method:</strong></p> POST<P>
										   <p><strong>Post Parameters: </strong>
										    {reg_no,student_name,gender,email,course_code,campus_code,username,_token}  
										   </p>
										   <p><strong>Where:</strong> </p>
										   <table class="table table-bordered table-striped">
												<tr>
												  <th>Parameter</th>
												  <th>Description</th>
												</tr>
												<tr>
												   <td>reg_no</td>
												   <td>-The student admission number</td>
												</tr>
												<tr>
												   <td>student_name</td>
												   <td>-The student name</td>
												</tr>
												<tr>
												   <td>gender</td>
												   <td>-The student gender</td>
												</tr>
												<tr>
												   <td>email</td>
												   <td>-The student email address</td>
												</tr>
												<tr>
												   <td>course_code</td>
												   <td>-The student course code</td>
												</tr>
												<tr>
												   <td>campus_code</td>
												   <td>-The student campus code</td>
												</tr>
												<tr>
												   <td>Username</td>
												   <td>-Is the developers username</td>
												</tr>
												<tr>
												   <td>_token</td>
												   <td>- is the API Key(token) generated above</td>
												</tr>
										   <table>
										  
										<p><strong>C# -RestSharp Code Snippet</strong></p>';
								echo	'<div class="alert alert-warning">
								var client = new RestClient("http://kcaevoting.net/data.php"); <br>
									client.Timeout = -1; <br>
									var request = new RestRequest(Method.POST); <br>
									request.AddHeader("_token", "232121"); <br>
									request.AlwaysMultipartFormData = true; <br>
									request.AddParameter("reg_no", "13/2020"); <br>
									request.AddParameter("student_name", "Peter Makenzi"); <br>
									request.AddParameter("gender", "Male"); <br>
									request.AddParameter("email", "petnjau@gmail.com"); <br>
									request.AddParameter("course_code", "KCABCOM"); <br>
									request.AddParameter("campus_code", "MAIN"); <br>
									request.AddParameter("username", "petnjau"); <br>
									request.AddParameter("_token", "123232"); <br>
									IRestResponse response = client.Execute(request); <br>
									Console.WriteLine(response.Content); </div>'; 
									
									  echo ' 
									  <h3>Run in Postman</h3>
									  <img src="images/postnam.png" class="image-responsive" style="width:100%">
									  <h3>Call back Response Messages</h3>
									  <table class="table table-bordered table-striped">
									   <tr>
									     <th>Message</th>
										 <th>Description</th>  
									   </tr> 
									   <tr>
									      <td>Success! Record has been saved</td>
										  <td>-Success</td>  
									   </tr>
									   <tr>
									      <td>Error! Record cannot be saved</td>
										  <td>-This means thats the record cannot be save either because it has already been saved or the values are invalid</td>  
									   </tr>
									   <tr>
									      <td>Sorry, you cant create new voter register at the moment</td>
										  <td>-The register cannot be created since there is an election period that is active (open).</td>  
									   </tr>
									   <tr>
									      <td>Failed,Invalid API Authorization Parameters</td>
										  <td>-The username or _token parameter values supplied are not correct </td>  
									   </tr>
									 
									  </table>
									 
									  </div>

									 
									   </div>

									   </div>
									 
									 </div>';
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

		//check password matching
		$('#generate_key').on('click',function(e){
			e.preventDefault();
		$("#key").empty();
		$("#key").html("<img src='images/loader.gif'>Generating new API Key(Token).............");
		var user=$(e.relatedTarget).data('id');
			
		$.ajax({
			    type:'post',
				url:"Classes/_ajax_requests_functions.php",
				data:'user='+user,
				success: function(data){
					$("#key").html(data);
					}

			});
		
	});


		
	});
</script>
</body>

</html>
