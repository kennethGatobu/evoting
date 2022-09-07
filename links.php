<?php



$url=basename($_SERVER['PHP_SELF']);
function current_class_if($condition)
{

return $condition? 'class="current"': '';	
}
if(isset($_SESSION['xyz']))
{
echo 
'<a href="index.php"  '. current_class_if($url=='index.php').'>HOME</a>|
<a href="upload_voters.php" '. current_class_if($url=='upload_voters.php').'>UPLOAD VOTERS</a>|
<a href="show_elections.php"  '. current_class_if($url=='show_elections.php').'>ELECTIONS</a>|
<a href="show_candidates_elections.php"  '. current_class_if($url=='show_candidates_elections.php'). '>CANDIDATES</a>|

<a href="positions_setting.php" '. current_class_if($url=='positions_setting.php').'>SCHOOL SET UP</a>|
<a href="logout.php"  '. current_class_if($url=='upload.php').'>LOG OUT</a>|
<br></td></tr>';
//<a href="positions_settings.php" '. current_class_if($url=='positions_settings.php').'>POSITIONS</a>|
}
else{
	echo '<a href="logout.php"  '. current_class_if($url=='upload.php').'>LOG OUT</a>|
<br></td></tr>';
	
	}



//Display the name of the logged in person
echo '<tr><td align="right" id="tr_name">Welcome: '.$_SESSION['name'];


$total_open_elections=0;
include 'config/db_con.php';	
$query=odbc_exec($con,"SELECT COUNT(election_period) as total from elections WHERE status='open'");
while($row_election=odbc_fetch_array($query))
{
	$total_open_elections=$row_election['total'];
}
 
//if(isset($_SESSION['xyz']))
//{
include 'config/db_con.php';	
if(isset($_SESSION['logged_user']))
{
$username=$_SESSION['logged_user'];
}
elseif(isset($_SESSION['voter_info']))
{
	$username=$_SESSION['voter_info'];
}

$date=date('Y-m-d');
$time=date('h:m:s a');
$page=$_SERVER['PHP_SELF'];
$ipaddress=$_SERVER['REMOTE_ADDR'];
$computer=gethostbyaddr($_SERVER['REMOTE_ADDR']);
$query=odbc_prepare($con,"INSERT INTO activity_logs (username,date_accessed,time_accessed,
							task_performed,web_page_accessed,ip_address,computer_name)
							 VALUES(?,?,?,?,?,?,?)");
$parameters=array($username,$date,$time,$page,$page,$ipaddress,$computer);
odbc_execute($query,$parameters);
//}


?>