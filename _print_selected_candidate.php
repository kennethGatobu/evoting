<?php
session_start();
//error_reporting(0);
date_default_timezone_set ("Africa/Nairobi"); 
include 'config/db_con.php';
include 'functions/current_election.php';
include '_SESSIONS_/_session_voter.php';

/*if(isset($_POST['nm']))
{
   $_SESSION['nm']=array_unique($_POST['nm']);
}
else
{
	$_SESSION['nm']=$_SESSION['nm'];
}*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include 'functions/page_title.php'; ?>
<link  href="styles.css"  rel="stylesheet" type="text/css" media="screen" />
<link  href="print.css"  rel="stylesheet" type="text/css" media="print" />

</head>
<body>

<?php

		
		echo 'VOTER REG NO: '. $_SESSION['voter_info'].'   ';
		echo 'Name: '.$_SESSION['name'];	
	
		 echo '<form action="" name="myform" method="post">
		
		 <table class="dm" align="center" title="Click on the red button below to submit your votes" width="80%">
		 
		 <tr><td></td>
		 <td id="bold_row">Photo</td>
		 <td id="bold_row">Reg NO</td>
		 <td id="bold_row">Candidate Name</td>
		 <td id="bold_row">Alias</td>
		 <td id="bold_row">Position</td>
		 <td id="bold_row">Vote Ref NO</td>
		 
		 </tr>';
		 $i=1;
		 
			$reg=$_SESSION['voter_info'];

			 $selected_candidate=odbc_prepare($con,"SELECT v.vote_id,c.reg_no,c.name,p.description,c.image,
			 				c.election_period,c.other_names
						FROM votes v 
						INNER JOIN candidates c ON c.reg_no=v.reg_no AND c.election_period=v.election_period
						INNER JOIN [position] p ON p.position_code=c.position_code
						WHERE v.student_reg=? AND v.election_period=?
						ORDER BY p.priority_rating ASC");
			$candidate_parameter=array($reg,$election);
			if(odbc_execute($selected_candidate,$candidate_parameter))
			{
				while($candidate_row=odbc_fetch_array($selected_candidate))
				{
				echo '<tr><td>'.$i++.
				'</td><td width="125">
				<img src="Candidates/'.$election.'/'.$candidate_row['image'].'" height="125" width="125">'.
				'</td><td>'.
				$candidate_row['reg_no'].
				'</td><td>'.$candidate_row['name'].
				'</td><td>'.
				$candidate_row['other_names'].
				'</td><td>'.
	
				$candidate_row['description'].
				'</td><td>'.
				$candidate_row['vote_id'].
				'</td></tr>';
				}
			}
	
		 echo '<tr><td colspan="2">
		 <a href="'.$_SERVER['HTTP_REFERER'].'">Go Back</a>
		 </td><td colspan="4">
		<a href="#" onClick=" window.print(); return false">Print this page</a>
		 </td></tr>
		 </table>';


?>

</body></html>