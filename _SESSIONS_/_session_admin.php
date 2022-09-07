<?php
if(!isset($_SESSION['xyz']) || $_SESSION['xyz']!="admin")
{
	//header("location:login.php?msg=please login first to use this service");
	header("location:logout.php");
	//header("location:".$_SERVER['HTTP_REFERER']);
}
?>