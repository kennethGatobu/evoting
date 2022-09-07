<?php

if(isset($_POST['reg']) or isset($_POST['search']))
{
	if(isset($_POST['search']))
	{
	include("config/db_con.php");
		
	}
	else{
		include("../config/db_con.php");
		}
   
   if(!empty($_POST['reg']) or $_POST['reg']!='')
   {

   $reg=filter_input(INPUT_POST,'reg',FILTER_SANITIZE_SPECIAL_CHARS);
 
	include("current_election.php");

	$color="1";
	$i=0;
	
	
$query=odbc_exec($con,"SELECT top (10) reg_no,name,COUNT(reg_no) as total_student  
						FROM Students
						where (reg_no like '%$reg%' or name like '%$reg%') and election_period='$election'
						GROUP BY reg_no,name");
	echo '<table class="dm" width="100%" border="0" cellpadding="5">
	<tr><th colspan="4">KCAU  '.$election.' SAKU ELECTION</th></tr>
	<tr><td id="bold_row"></td><td id="bold_row">'."Reg No".
	'</td><td id="bold_row"  align="left">'."Student Name".
	'</td><td></td></tr>';
	while($row=odbc_fetch_array($query))
	{
		 $i++;
		if($color==1)
		{
		echo '<tr style="background-color:#CFF;" celpadding="5";><td>'.$i.". ".
		'</td><td>'.$row['reg_no'].
		'</td><td align="left">'.$row['name'].'</td><td><a href="?reg='.$row['reg_no'].'">'."Show Password".
		'</a></td></tr>';
		$color="2";
		}
		else
		{
		echo '<tr><td >'.$i.". ".'</td><td>'.$row['reg_no'].'</td><td align="left">'.$row['name'].
		'</td><td><a href="?reg='.$row['reg_no'].'">'."Show Password".'</a></td></tr>';
		$color="1";
		}	
 }
echo '</table>';
   }
   else
   {
	   echo '<p style="color:red;"> Please Enter text to search</p>';
	}

	
}
	
?>