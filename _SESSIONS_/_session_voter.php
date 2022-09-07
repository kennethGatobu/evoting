<?php
if(!isset($_SESSION['voter_info']))
{
	
	header("location:logout.php");
	
	//header("location:login.php?msg=please login first to use this service");
	
}
/*else
{
include("timer.php");
}*/
?>