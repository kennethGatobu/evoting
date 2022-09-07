<?php
ob_start();
session_start();
include('../Classes/db_con.php');
if(isset($_POST['reg']) or isset($_POST['search']))
{
	
   
   if(!empty($_POST['reg']) or $_POST['reg']!='')
   {

   $reg=htmlspecialchars($_POST['reg'],ENT_QUOTES);
 
	include("current_election.php");
	$campus=$_SESSION['campus'];
	$color="1";
	$i=0;
	

	$query=odbc_prepare($con,"SELECT top (10) s.reg_no,s.name,
						s.campus_code  ,c.campus_description
						FROM Students s
						left JOIN campus c on c.campus_code=s.campus_code
						where (s.reg_no like '%'+?+'%' or s.name like '%'+?+'%') and s.election_period=? and s.campus_code=?
						ORDER BY name ASC");
	odbc_execute($query,array($reg,$reg,$election,$campus));
	echo '<table class="table table-bordered table-striped">
	<tr><th colspan="4">KCAU  '.$election.' SAKU ELECTION</th></tr>
	<tr class="text-info"><th></th>
	<th>REGISTRATION NUMBER</th>
	<th>STUDENT NAME</th>
	<th>CAMPUS</th>

	<th></th>
	</tr>';
	while($row=odbc_fetch_array($query))
	{
		 $i++;
		
		echo '<tr>
		<td>'.$i.". ".
		'</td>
		<td>'.$row['reg_no'].'</td>
		<td align="left">'.
		ucwords(strtolower($row['name'])).'</td>
		<td>'.ucwords(strtolower($row['campus_description'])).'</td>
		<td>

		<a href  data-id="'.$row['reg_no'].'" data-target="#enroll_candidate" data-toggle="modal">Select this</a>
		</td>
		</tr>';
		
		
 }
echo '</table>';
   }
   else
   {
	   echo '<p style="color:red;"> Please Enter text to search</p>';
	}

	
}
	
?>