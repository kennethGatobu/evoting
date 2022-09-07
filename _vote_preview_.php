<?php
ob_start();
session_start();
error_reporting(0);
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
	 font-size:12px;
	  
	 
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
		
				if(isset($_POST['submit_vote']))
				{
								$user=$_SESSION['voter_info'];
								$date= date('d/m/Y');
								$ip=$_SERVER['REMOTE_ADDR'];
								$time=date("h:i:s a");
								 foreach($_SESSION['nm'] as $vote_candidate)
								{
									$confirm_candidate=odbc_prepare($con,"SELECT reg_no,position_code 
																	from candidates 
																		where reg_no=? and election_period=?");
									$confirm_parameters=array($vote_candidate,$election);
									if(odbc_execute($confirm_candidate,$confirm_parameters))
									{
										$rows=odbc_fetch_array($confirm_candidate);
										$position_code=$rows['position_code'];
										$candidate_regno=$rows['reg_no'];
										$insert_vote=odbc_prepare($con,"INSERT INTO votes
													 (reg_no,student_reg,election_period,position_code,host_ip_address,date_voted,time_voted)
													  VALUES(?,?,?,?,?,?,?)");
										$insert_arrays=array($candidate_regno,$user,$election,$position_code,$ip,$date,$time);
									$query=odbc_execute($insert_vote,$insert_arrays);
										
									}									
								}
								
								if (!$query) 
									{
										$logged_status="OFFLINE";
									$time=date("h:i:s a");
								  $update_user=odbc_prepare($con,"UPDATE  Students 
								  					SET logged_status=?,time_logged_out=?
														where reg_no=? AND election_period=?");
									odbc_execute($update_user,array($logged_status,$time,$user,$election));
									
										header("location:_MESSAGES_/_vote_send_.php");
									//	echo $con->error;
										//header("location:_MESSAGES_/vote_confirmation.php");
										//header("location:file_voted_candidate.php");
									}
									else
									{
									$logged_status="OFFLINE";
									$time=date("h:i:s a");
								  $update_user=odbc_prepare($con,"UPDATE  Students 
								  					SET logged_status=?,time_logged_out=?
														where reg_no=? AND election_period=?");
									odbc_execute($update_user,array($logged_status,$time,$user,$election));
									$activity_description='Successfully voted for the selected candidates';
                            		user_activity_logs($con,$activity_description);
												   header("location:_MESSAGES_/_vote_send_.php");	
												// header("location:file_voted_candidate.php");
												//header("location:_MESSAGES_/_print_votes.php");		
									}
								
				}



if(isset($_POST['nm']))
{
   $_SESSION['nm']=array_unique($_POST['nm']);
}
else
{
	$_SESSION['nm']=$_SESSION['nm'];
}

if(isset($_SESSION['nm']))
{
	
	 $no_selected_candidates=count($_SESSION['nm']);
	
	 if($no_selected_candidates<1)
	 {
		 
		 echo '<h2 style="color:red;">'."No Preview and select atleast one candidate".'</h2>';
			echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Go Back</a>';
	 }
	 else
	   { 
	   	echo 	'
		   <div class="page-header text-center">
				<h3 class="text-info">'."SAKU ".$election.' ELECTIONS
				('.$_SESSION['campus_name'].')</h3>'.
				'<h4 style="color:brown;">Step 2 of 2 : You have Selected the Following ; Please Click Submit Vote</h4>
				</div>';
		

		 echo '<form action="" name="myform" method="post">
		 <table class="table table-bordered" title="Click on the red button below to submit your votes" >
		 <tr bgcolor="#666" style="font-weight:bold; font-size:15px; color:white;">

		
		 <td></td>
		 <th>PHOTO</th>
		 <th>REG NO </th>
		 <th>CANDIDATE NAME</th>
		 <th>ALIAS</th>
		 <th>POSITION</th>
		 </tr>';
		 $i=1;
		 foreach($_SESSION['nm'] as $val)
		 {
			
			 $selected_candidate=odbc_prepare($con,"SELECT c.reg_no,c.name,
			 								c.other_names,c.image,p.description 
													FROM candidates c
													INNER JOIN [position] p on p.position_code=c.position_code
													where c.reg_no=? and c.election_period=?");
			$candidate_parameter=array($val,$election);
			if(odbc_execute($selected_candidate,$candidate_parameter))
			{
				while($candidate_row=odbc_fetch_array($selected_candidate))
				{
				echo '<tr>
				<td>'.$i++.'.</td>
				<td width="125">
				<img src="Candidates/'.$election.'/'.$candidate_row['image'].'" height="125" width="125">'.
				'</td>
				<td>'.$candidate_row['reg_no'].'</td>
				<td>'.$candidate_row['name'].'</td>
				<td>'.$candidate_row['other_names'].'</td>
				<td>'.$candidate_row['description'].'</td>
				</tr>';
				}
			}
		 }
		 echo '<tr>
		 <td colspan="2">
		 <a href="voters_ballot.php">Go Back</a>
		 </td><td colspan="4">
		 <input id="submit_button" type="submit" name="submit_vote" value="Click here to Submit Vote">
		 </td></tr>
		 </table>
		 </form>';
		
	   }
}else {
	header('location:voters_ballot.php');
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
