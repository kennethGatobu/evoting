<?php
session_start();
//error_reporting(0);
date_default_timezone_set ("Africa/Nairobi"); 
include 'config/db_con.php';
include 'functions/current_election.php';
include '_SESSIONS_/_session_voter.php';

if(isset($_POST['nm']))
{
   $_SESSION['nm']=array_unique($_POST['nm']);
}
else
{
	$_SESSION['nm']=$_SESSION['nm'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include 'functions/page_title.php'; ?>
<link  href="styles.css"  rel="stylesheet" type="text/css" />
</head>
<body>
<table id="table_content">
<tr><td id="tr_header_text"></td></tr>
<tr id="tr_header" >
<td></td>
</tr>
  <tr id="tr_link" ><td >
	<?php
    include 'links.php';
    ?>
</td></tr>
<tr>
<td   valign="top" align="center" >

<?php
if(isset($_POST['submit_vote']))
{
				$user=$_SESSION['voter_info'];
				$date= date('d/m/Y');
				$ip=$_SERVER['REMOTE_ADDR'];
				$time=date("h:i:s a");
				 foreach($_SESSION['nm'] as $vote_candidate)
				{
					$confirm_candidate=odbc_prepare($con,"SELECT reg_no,position_code from candidates 
														where reg_no=? and election_period=?");
					$confirm_parameters=array($vote_candidate,$election);
					if(odbc_execute($confirm_candidate,$confirm_parameters))
					{
						$rows=odbc_fetch_array($confirm_candidate);
						$position_code=$rows['position_code'];
						$candidate_regno=$rows['reg_no'];
						$insert_vote=odbc_prepare($con,"INSERT INTO votes
				                     (reg_no,student_reg,election_period,position_code,host_ip_address,date_voted,time_voted)
						              VALUES(?,?,?,?,?,?,?)");
						$insert_arrays=array($candidate_regno,$user,$election,$position_code,$ip,$date,$time);
					$query=odbc_execute($insert_vote,$insert_arrays);
						
					}									
				}
				
				if (!$query) 
					{
						echo $con->error;
						//header("location:_MESSAGES_/vote_confirmation.php");
						//header("location:file_voted_candidate.php");
					}
					else
					{
								$logged_status="OFFLINE";
								$time=date("h:i:s a");
		          $update_user=odbc_exec($con,"UPDATE  Students SET logged_status='$logged_status',
		                                 time_logged_out='$time'
		                                where reg_no='$user' AND election_period='$election'");
			                       header("location:_MESSAGES_/_vote_send_.php");	
								// header("location:file_voted_candidate.php");
								//header("location:_MESSAGES_/_print_votes.php");		
					}
				
}
?>


<?php

if(isset($_POST['preview_vote']))
{
	 $no_selected_candidates=count($_SESSION['nm']);
	
	 if($no_selected_candidates<1)
	 {
		 
		 echo '<h2 style="color:red;">'."No Preview and select atleast one candidate".'</h2>';
			echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Go Back</a>';
	 }
	 else
	   { 
	   	echo 	'<h1 style="color:black;">'."SAKU ".$election.' ELECTIONS</h1>'.
		'<h2 style="color:brown;">'."Step 2 of 2 : You have Selected the Following ; Please Click Submit Vote".'</h2>';

		 echo '<form action="" name="myform" method="post">
		 <table class="dm" border="1" cellspacing="0" title="Click on the red button below to submit your votes" width="60%">
		 <tr><td></td>
		 <td id="bold_row">Photo</td>
		 <td id="bold_row">Reg NO</td>
		 <td id="bold_row">Candidate Name</td>
		 <td id="bold_row">Alias</td>
		 <td id="bold_row">Position</td></tr>';
		 $i=1;
		 foreach($_SESSION['nm'] as $val)
		 {
			
			 $selected_candidate=odbc_prepare($con,"SELECT c.reg_no,c.name,c.other_names,c.image,p.description 
													FROM candidates c
													INNER JOIN [position] p on p.position_code=c.position_code
													where c.reg_no=? and c.election_period=?");
			$candidate_parameter=array($val,$election);
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
				'</td></tr>';
				}
			}
		 }
		 echo '<tr><td colspan="2">
		 <a href="'.$_SERVER['HTTP_REFERER'].'">Go Back</a>
		 </td><td colspan="4">
		 <input id="submit_button" type="submit" name="submit_vote" value="Click here to Submit Vote">
		 </td></tr>
		 </table></form>';
		
	   }
}


?>
</td></tr>
<tr >
 
    <td id="tr_footer"><?php include 'functions/footer.php'; ?></td>
  </tr>
</table>
</body></html>