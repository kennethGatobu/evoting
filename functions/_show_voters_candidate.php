<?php
include 'current_election.php';
$reg_no=$_SESSION['voter_info'];
$check_voter=odbc_prepare($con,"SELECT count(student_reg) as total_count 
							FROM votes where student_reg=? and election_period=?");
$param=array($reg_no,$election);
if(odbc_execute($check_voter,$param))
{
	$total_found_voter=odbc_fetch_array($check_voter);
	if($total_found_voter['total_count']>=1)
	{
		header("location:_MESSAGES_/vote_confirmation.php");
	}
	else
	{
		$query=odbc_prepare($con,"SELECT p.position_code, p.description 
								FROM positions_department_relationship pf 
								INNER JOIN [position] p ON p.position_code=pf.position_code AND p.position_status='active'
								INNER JOIN faculty_academic_departments f ON f.department_code=pf.department_code
								INNER JOIN Courses c ON c.department_code=f.department_code
								INNER JOIN Students s ON s.course_code=c.course_code
								WHERE s.reg_no=? AND s.election_period=?
								ORDER BY p.priority_rating ASC");
			$paramenter=array($reg_no,$election);					
			if(odbc_execute($query,$paramenter))
			{
				echo '<form action="_vote_preview_.php" method="post">';
		
				while($position_row=odbc_fetch_array($query))
				{
					$position_code=$position_row['position_code'];
					echo '<div id="candidate_list"><h2 align="left">'.$position_row['description'].'</h2>';
						
						$candidate_query=odbc_prepare($con,"SELECT c.election_period,c.reg_no,c.name,
															c.other_names,c.position_code,c.image 
															FROM candidates c 
															WHERE c.position_code=? AND c.election_period=?");
					$candidate_parameter=array($position_code,$election);
					if(odbc_execute($candidate_query,$candidate_parameter))						
				    echo '<table width=100%>
					<tr bgcolor="#666" style="font-weight:bold; font-size:15px; color:white;"><td>'.
							 "Photo".
							 '</td><td>'.
							 "Candidate Name".'</td><td></td><td>'."Select".'</td></tr>';
						while($ca=odbc_fetch_array($candidate_query))
						{
						 echo '<tr ><td width=110>'.
						 "<img height=100 id='candidate_image_border' width=105 src='Candidates/".$ca['election_period'].'/'.$ca['image']."'>".
						 '</td><td title="Click on the round circle to select"  align="left">'.$ca['name'].
						 '</td><td>'. $ca['other_names'].
						  '</td><td>
		 <input id="candidate_radio_button" title="Click here to select this Candidate" type="radio"  name="nm['.$position_row['description'].']" value="'.$ca['reg_no'].'">
						  </td></tr>'; 
						}	
						 echo '</table></div>';
				}
	echo '<input id="submit_button" type="submit" name="preview_vote" value="Click here to Proceed">
	</form>';
			}
	}

}

?>