<?php
include 'db_con.php';
include 'email_config.php';
function user_activity_logs($con,$activity_description)
{	
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
$parameters=array($username,$date,$time,$activity_description,$page,$ipaddress,$computer);
odbc_execute($query,$parameters);
}
function formating_dates($str_date)
{
$fdate1=str_replace("/","",$str_date);
$fday=substr($fdate1,0,2);
$fmonth=substr($fdate1,2,2);
$fyear=substr($fdate1,4,4);
$fdate2=$fyear."-".$fmonth."-".$fday;
return $fdate2;
}
function already_voted($con)
{
	$reg=$_SESSION['voter_info'];
	$election= $_SESSION['election_period'];
	$logged_status="OFFLINE";
	$time=date("h:i:s a");
		$update_user=odbc_prepare($con,"UPDATE  Students 
				SET logged_status=?, time_logged_out=? 
				where reg_no=? AND election_period=?");
		odbc_execute($update_user,array($logged_status,$time,$reg,$election));
//  ---------------------------------------------------------------------------------------
$i=1;
$color="1";
$query=odbc_prepare($con,"SELECT c.reg_no,c.name,p.description,
						c.image,c.election_period,c.other_names,vote_id
						FROM votes v 
						INNER JOIN candidates c ON c.reg_no=v.reg_no AND c.election_period=v.election_period
						INNER JOIN [position] p ON p.position_code=c.position_code
						WHERE v.student_reg=? AND v.election_period=?
						ORDER BY p.priority_rating ASC");
	odbc_execute($query,array($reg,$election));
echo '
<h3 class="page-header text-info">KCAU SAKU '.$election.' ELECTIONS ('.$_SESSION['campus_name'].')</h3> 
<table class="table table-bordered" >
<tr><th  colspan="6"><h2 style="color:red;">You Have already voted for the following</h2></th></tr>
<tr><th >STUDENT NAME: </th><th colspan="5">'.$_SESSION['name'].'</thh></tr>
<tr><th >ADMISSION NO: </th><th colspan="5">'.$reg.'</th></tr>


<tr bgcolor="#666" style="font-weight:bold; font-size:15px; color:white;">
<th></th>
<th>Photo</th>
<th>Candidate Name</th>
<th></th>
<th>Position</th>
<th>Vote REF NO</th>
<tr>';
while($row=odbc_fetch_array($query))
{
	
	echo '<tr><td>'.$i++.".".'</td><td width="125">
	<img width="125" height="125" src="Candidates/'.$election.'/'.$row['image'].'">'.
	'</td><td>'.$row['name'].'</td><td>'.$row['other_names'].
	'</td><td>'.$row['description'].
	'</td><td>'.
	$row['vote_id'].
	'</td></tr>';
}


echo '<tr><td><a href="logout.php" class="btn btn-success">Log Out</a></td> 
<td colspan=5>';

echo '<a href="_MESSAGES_/_vote_send_.php" onClick=" window.print(); ">Print this page</a>';
	  
echo '</td></tr>';
echo '</table>';
}




function show_campus_and_election_popup($page,$con){
	if(isset($_POST['show_report'])){
		
		$campus_code=htmlspecialchars($_POST['campus'],ENT_QUOTES);
		$campuses=odbc_prepare($con,"SELECT c.campus_code,c.campus_description 
								FROM admin_accounts_campus_accessed a 
								INNER JOIN campus c on c.campus_code=a.campus_code
								where a.campus_code=?");
		odbc_execute($campuses,array($campus_code));
		if(odbc_num_rows($campuses)>0){
			$row=odbc_fetch_array($campuses);
			$_SESSION['election_period']=htmlspecialchars($_POST['election_period'],ENT_QUOTES);
			$_SESSION['campus']=$row['campus_code'];
			$_SESSION['campus_name']=$row['campus_description'];
			header('location:'.$page.'');
		}
	}
	
	echo '<div id="campus" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="campus">
	<div class="modal-dialog">
	 <div class="modal-content">
		 <div class="modal-header">
		 <button type="button" class="close" data-dismiss="modal">&times;</button>
		 <h4 class="modal-title"><span class="fa fa-edit" style="color:#FF5"></span> SELECT CAMPUS</h4>
		 </div>
		 <form role="form" method="post" enctype="multipart/form-data">
		 <div class="modal-body" id="load_register">
		 <div class="form-group">
		 <label>Campus:</label>';
		  campus_dropdown($con); 
		echo '</div>';
		$election_qry=odbc_exec($con,"SELECT election_period,election_type 
		FROM elections ORDER BY election_period DESC");
		echo '<div class="form-group">
				<label>Election Period</label>
				<select name="election_period" class="form-control" required>
						<option value="">---Select---</option>';
						while($row=odbc_fetch_array($election_qry))
						{
							echo '<option value="'.$row['election_period'].'">'.
							$row['election_period'].' '.$row['election_type'].'</option>';
						}
				echo '</select></div>';
		
		  
		 echo '</div>
		 <div class="modal-footer">
		 <input type="submit" class="btn btn-primary" name="show_report" value="Show">
		 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		 </div>
		 </form>
	 </div>
	</div>
	</div>';
		echo '<p class="text-right">
		<a style="font-size:16px" href="" data-target="#campus" data-toggle="modal" class="btn btn-primary"><i class="fa fa-filter"></i> Click here to Select Election Period &amp; Campus</a>
		</p>';
	}






function show_campus_popup($page,$con){
if(isset($_POST['show_elections'])){
	
	$campus_code=htmlspecialchars($_POST['campus'],ENT_QUOTES);
	$campuses=odbc_prepare($con,"SELECT c.campus_code,c.campus_description 
							FROM admin_accounts_campus_accessed a 
							INNER JOIN campus c on c.campus_code=a.campus_code
							where a.campus_code=?");
	odbc_execute($campuses,array($campus_code));
	if(odbc_num_rows($campuses)>0){
		$row=odbc_fetch_array($campuses);
		$_SESSION['campus']=$row['campus_code'];
		$_SESSION['campus_name']=$row['campus_description'];
		header('location:'.$page.'');
	}
}

echo '<div id="campus" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="campus">
<div class="modal-dialog">
 <div class="modal-content">
	 <div class="modal-header">
	 <button type="button" class="close" data-dismiss="modal">&times;</button>
	 <h4 class="modal-title"><span class="fa fa-edit" style="color:#FF5"></span> SELECT CAMPUS</h4>
	 </div>
	 <form role="form" method="post" enctype="multipart/form-data">
	 <div class="modal-body" id="load_register">
	 <div class="form-group">
	 <label>SELECT CAMPUS:</label>';
	  campus_dropdown($con); 
	echo '</div>
	  
	  </div>
	 <div class="modal-footer">
	 <input type="submit" name="show_elections" value="Show Elections" class="btn btn-primary"> 
	 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	 </div>
	 </form>
 </div>
</div>
</div>';
	echo '<p class="text-right">
	<a style="font-size:16px" href="" data-target="#campus" data-toggle="modal" class="btn btn-primary"><i class="fa fa-filter"></i> Click here to Select Campus</a>
	</p>';
}



//campus dropdown
function campus_dropdown($con)
{
	$username=htmlspecialchars($_SESSION['logged_user'],ENT_QUOTES);
	$campuses=odbc_prepare($con,"SELECT c.campus_code,c.campus_description 
						FROM admin_accounts_campus_accessed a 
						INNER JOIN campus c on c.campus_code=a.campus_code
						where a.username=?");
	odbc_execute($campuses,array($username));
	echo '<select name="campus" class="form-control">';
	if(odbc_num_rows($campuses)>0){
		while($campus=odbc_fetch_array($campuses)){
			echo '<option value="'.$campus['campus_code'].'">'.
			$campus['campus_description'].'</option>';
		}
	}
	echo '</select>';
} 
function new_report_heading($con)
{

	$html='
	
	<div style="text-align:center">
    <h3 style="color:#E4C461"><img src="images/kca.png" width="120" height="120"  alt="No Logo"><br>
   STUDENTS ASSOCIATION OF KCA UNIVERSITY (SAKU)</h3>
	
	</div>
	<div style="position: fixed;
		top: 25%;
		width: 100%;
		text-align: center;
		opacity: 0.06;
		transform: rotate(-20deg);
		transform-origin: 50% 50%;
		z-index: -1000;" id="watermarks">
		<img src="images/kca.jpg" >
		</div>';
	return $html;
}


?>