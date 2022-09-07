<?php
session_start();
date_default_timezone_set ("Africa/Nairobi"); 
include 'Classes/_function_classes.php';
include("functions/current_election.php");
//.....................................................
if (isset($_SESSION['user']))
{
$user=$_SESSION['user'];
$time=date("h:i:s a");
$logged_status="OFFLINE";
$update_user=odbc_exec($con,"UPDATE Students SET logged_status='$logged_status', time_logged_out='$time'  where reg_no='$user' AND election_period='$election'");
}



//------------------------------------------------------
session_destroy();
header("location:login.php");
?>