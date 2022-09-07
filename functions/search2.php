<?php
include("../config/db_con.php");
//include("current_sem.php");
$reg=filter_input(INPUT_POST,'std_name',FILTER_SANITIZE_SPECIAL_CHARS);
include("current_election.php");

	//$quer=odbc_exec($con,"SELECT count(*) as counter FROM Students WHERE reg_no LIKE '$reg%' AND semester='$cur_trimester'");
	$quer=odbc_exec($con,"SELECT TOP (10) count(*) as counter FROM Students,current_trimester where current_trimester.status='current' and current_trimester.semester=Students.semester and Students.election_period='$election' and Students.name LIKE '%$reg%'");
	//
	while($r=odbc_fetch_array($quer))
	{
		if($r['counter']<1)
		{
			echo '<p style="color:red; font:20px;">'."The Student with that name is not found!!!".'</p>';
		}
		else
		{
			$query=odbc_exec($con,"SELECT TOP (10) Students.* FROM Students,current_trimester WHERE Current_trimester.status='current' AND current_trimester.semester=Students.semester and Students.election_period='$election' AND Students.name LIKE '%$reg%'");
if($query)

	{
	$color="1";
	$i=0;
	echo '<table width="95%" cellpadding="5"><tr><th></th><th>'."REG NO".'</th><th  align="left">'."STUDENT NAME".'</th><th></th></tr>';
	while($row=odbc_fetch_array($query))
	{
		$i++;
		if($color==1)
		{
		echo '<tr style="background-color:#CFF;" celpadding="5";><td>'.$i.". ".'</td><td>'.$row['reg_no'].'</td><td align="left">'.$row['name'].'</td><td><a href="enroll.php?reg='.$row['reg_no'].'">'."Select this".'</a></td></tr>';
		$color="2";
		}
		else
		{
		echo '<tr><td >'.$i.". ".'</td><td>'.$row['reg_no'].'</td><td align="left">'.$row['name'].'</td><td><a href="enroll.php?reg='.$row['reg_no'].'">'."Select this".'</a></td></tr>';
		$color="1";
		}
	}
	echo '</table>';
}
		}
	}

	
?>