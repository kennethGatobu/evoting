<?php
ob_start();
session_start();
include 'Classes/_function_classes.php';
date_default_timezone_set ("Africa/Nairobi"); 

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
           
                <div class="login-panel panel panel-primary">
                    <div class="panel-heading" id="login_panel">
                        <h3 class="panel-title text-center"><strong>
                              KCA UNIVERSITY SAKU E-VOTING <br><BR>
                              Password Request
                            <br></strong></h3>
                    </div>
                    <div class="panel-body">
                    
                        <?php 
                            if(isset($_POST['send_code']))
                            {
                                if(!empty($_POST['username']) and !empty($_POST['captcha']))
                                {
                                    $captcha=$_POST['captcha'];
                                    $username=htmlspecialchars($_POST['username'],ENT_QUOTES);
                                    if(strtoupper($_SESSION['captcha']) == strtoupper($captcha))
                                    {
                                            $qry=odbc_prepare($con,"SELECT s.reg_no,s.name,s.email,s.email,s.election_period
                                                                    FROM Students s 
                                                                INNER JOIN elections e on e.election_period=s.election_period
                                                                where s.reg_no=? and e.[status]='open'");
                                            odbc_execute($qry,array($username));
                                            if(odbc_num_rows($qry)>0)
                                            {
                                                $password=mt_rand(100000,999999);
                                            
                                            $row=odbc_fetch_array($qry);
                                            //send password via email
                                            $email=$row['email'];
                                            $user=$row['name'];
                                            $election_period=$row['election_period'];
                                            $msg='<p><br>The following is Your  Password , use it to login to KCA SAKU E-voting System </p>
                                                   
                                                    <h1 style="color:blue">Password:'.$password.'</h1>
                                                <p> Kindly note that this token expires after next 10 minutes.</p>
                                            
                                                    <br>
                                                    Thank you.
                                                    <br>
                                                    <strong>
                                                    SAKU, KCA University
                                                </strong><br>
                                                <br>';
                                            send_otp_mail($email,$msg,$user);
                                                //--update the password
                                                $update=odbc_prepare($con,"UPDATE Students SET [password]=? 
                                                                    where reg_no=? and election_period=?"); 
                                                    if(odbc_execute($update,array($password,$username,$election_period)))
                                                    {
                                                        echo '<div class="alert alert-success">
                                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                       Dear '.$user.', kindly check your email ('.$email.') we have send your login details. Thank you.  </div>';
                                                    }  

                                            }
                                            else{
                                                echo '<div class="alert alert-danger">
                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                               Sorry, We can not share any credentials at the moment , contact ICT Team</div>';
                                            }
                                            


                                            // save password on the student database; 
                                    }else{
                                         echo '<div class="alert alert-danger">
                                       <button type="button" class="close" data-dismiss="alert">&times;</button>
                                      Sorry, you have entered invalid Captcha code , Please enter the new code to try again</div>'; 
                                    }

                                }
                                else{
                                    echo '<div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                   Please enter all details</div>';
                                }
                            } 
                        ?>
                        <form role="form" method="post" action="">
                          
                                <div class="form-group">
                                    <label>Registration Number:</label>
                                    <input class="form-control" placeholder="Registration Number" name="username" required   autocomplete="off" value="<?php if(isset($_POST['username'])){echo $_POST['username'];}  ?>">
                                   
                                </div>
                                <div class="form-group">
                                    <label>Please Enter the Captcha Text</label> &nbsp;
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
                                <input  type="submit" name="send_code" value="Send Password to My Email" class=" form-control btn btn-primary ">
                               <p> or</p>
                                <p><a href="login.php">Click here to Login</p>
                                
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
