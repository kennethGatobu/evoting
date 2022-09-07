<?php
ob_start();
session_start();
error_reporting(0);
include 'Classes/_function_classes.php';
require_once 'vendor/autoload.php';
use Gregwar\Captcha\PhraseBuilder;
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

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="Classes/new_styles.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="bower_components/css/bootstrap-datepicker3.min.css" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style="background-color: #9C5E40 !important;">

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
           
                <div style=" margin-top: 8%  !important;" class="login-panel panel panel-primary">
                    <div class="panel-heading" id="login_panel">
                        <h3 class="panel-title text-center"><strong>
                              SAKU E-VOTING
                           
                            <BR>
                            LOGIN
                        </strong></h3>
                    </div>
                    <div class="panel-body">
                     <div class="text-center">
                         <img src="images/kca.png" width="100" height="100">
                     </div>
                     <br>
 <?php
date_default_timezone_set ("Africa/Nairobi"); 
/*------
this block checks whether the user trying to login is an admin/ returning officer
---------------*/

if(isset($_POST['login']))
{
   

	if(empty($_POST['username'])  and empty($_POST['password']) and !empty($_POST['captcha']))
	{
        echo '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                Please enter all details</div>';
	}
	else
	{
            $captcha=$_POST['captcha'];
            if(strtoupper($_SESSION['captcha']) == strtoupper($captcha))
            {


	        $username=htmlspecialchars($_POST['username'],ENT_QUOTES);
	         $password=$_POST['password'];
	
            $query=odbc_prepare($con,"SELECT  [name], user_type
                                FROM  admin 
                                WHERE username = ? and [password]=? AND account_status='Active' ");

            odbc_execute($query,array($username,md5($password)));
	                
	         
			if(odbc_num_rows($query)>0)
			{
                $row=odbc_fetch_array($query);


                $_SESSION['xyz']=$row['user_type'];
                $_SESSION['name']=$row['name'];
                $_SESSION['logged_user']=$username;
                $activity_description='Logged into the system';
                user_activity_logs($con,$activity_description);
                header("location:index.php");	
               //echo odbc_num_rows($query);
				
			}
	                /*  students login */
	        else
	          {
		         
                $query=odbc_prepare($con,"SELECT s.reg_no,s.name,s.course_code,
                                    s.election_period,s.campus_code,c.campus_description
                                        FROM Students s
                                    INNER JOIN elections e on e.election_period=s.election_period 
                                    and e.status='open'
                                    INNER JOIN campus c on c.campus_code=s.campus_code
                                    WHERE s.reg_no=? and s.password=?");
			   odbc_execute($query,array($username,$password));
			
			if(odbc_num_rows($query)>0)
			{
                            $row=odbc_fetch_array($query);
					            $_SESSION['voter_info']=$row['reg_no'];
                                $_SESSION['xyz']="Student";
                                $_SESSION['campus']=$row['campus_code'];
                                $_SESSION['campus_name']=$row['campus_description'];
                                $_SESSION['election_period']=$row['election_period'];
                                $election=htmlspecialchars($row['election_period'],ENT_QUOTES);
								//pick the time the session starts after a sucessfull login
								$_SESSION['start_time']=time();
								$_SESSION['name']=$row['name'];
								$_SESSION['course']=$row['course_code'];
								
								//header("location:index.php");
								$ip_address=$_SERVER['REMOTE_ADDR'];
								$logged_status="ONLINE NOW";
								$time=date("h:i:s a");
								//$host_name=gethostname();
								//get the client machine name
								$host_name=gethostbyaddr($_SERVER['REMOTE_ADDR']);
								
		/*--------UPDATE LOGIN STATUS ------------------------------*/
				        $update_user=odbc_prepare($con,"UPDATE  Students 
                                            SET logged_status=?,time_logged=?,
				 		                      machine_ip_address=?, computer_name=?  
								              where reg_no=?  AND election_period=?");
                            $params=array($logged_status,$time,$ip_address,$host_name,$username,$election);
						if (odbc_execute($update_user,$params))
						{
                            $activity_description='Logged into the system';
                            user_activity_logs($con,$activity_description);
                            header("location:voters_ballot.php");
						}
									
		//----------------------
			 }
			   
		else   {
            echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
               Wrong Username/Password </div>';
				  
			  }	
				
		 }
		
            }
            else{
                echo '<div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
             Sorry, you have entered invalid Captcha code , Please enter the new code to try again</div>'; 
           }	
		}
	}
						
	?>		
                        <form role="form" method="post" action="">
                          
                                <div class="form-group">
                                    <input class="form-control" placeholder="Registration Number" name="username" required   autocomplete="off" value="<?php if(isset($_POST['username'])){echo $_POST['username'];}  ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" required value="<?php if(isset($_POST['password'])){echo $_POST['password'];}  ?>" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  
                                    <p><img src="captcha.php" class="captcha-image" >
                                        
                                      
                                    </p>
                                </div>
                                <div class="form-group">
                                <input class="form-control" placeholder="Type the above Captcha Code here.." name="captcha" required autocomplete="off">   
                                </div>
                                <div class="form-group">
                                <p>
                                    Can't read the code? Click  here
                                   <i style="font-size:25px;" class="text-info fa fa-undo refresh-captcha"></i> to refresh</p>
                                </div>
                               
                                <div class="form-group">
                                <input  type="submit" name="login" value="Login" class=" form-control btn btn-primary ">
                                or
                             <p>  <a href="send_password.php">Click here to receive your Password</a></p>
                               </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
	<script src="bower_components/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
 $(document).ready(function(e) {
	 //--request terms/academic period---------
	 var refreshButton = document.querySelector(".refresh-captcha");
        refreshButton.onclick = function() {
        document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
        }
	 $('.datepicker').datepicker({
			 format:'dd/mm/yyyy',
			// startDate: '-3d',
			//startDate: 'd',
			 autoclose:true,
			 orientation:"bottom"
		
	  });
	  //-----------------------------
 });
 </script>

</body>

</html>
