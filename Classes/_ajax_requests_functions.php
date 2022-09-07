<?php
error_reporting(0);
session_start();
include '_function_classes.php';


if(isset($_POST['user']))
{

	$username=$_SESSION['logged_user'];
	 $token = sha1(mt_rand(23, mt_getrandmax()) );

	 $qry=odbc_prepare($con,"UPDATE admin set _token=? where username=? and user_type='admin'");

	 if(odbc_execute($qry,array($token,$username)))
	 {
		echo '
		<label>Copy this API Key (Token)</label>
		<input type="text" value="'.$token.'" class="form-control">';
	 }
	 else{
		 echo '<p class="alert alert-danger">Sorry you dont have permission to generate api key</p>';
	 }
	
}

//search for students
if(isset($_POST['search_student']) )
{
	
   
   if(!empty($_POST['search_student']))
   {

   $reg=htmlspecialchars($_POST['search_student'],ENT_QUOTES);
 
	$election=$_SESSION['register_candidate_election_period'];
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
	<tr><th colspan="5">KCAU  '.$election.' SAKU ELECTION</th></tr>
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

if(isset($_POST['edit_election_code']))
{
	$election=htmlspecialchars($_POST['edit_election_code'],ENT_QUOTES);
	$qry=odbc_prepare($con,"SELECT * FROM elections where election_period=?");
	odbc_execute($qry,array($election));
	if(odbc_num_rows($qry)>0)
	{
		$row=odbc_fetch_array($qry);

	echo '<div class="form-group">
		<label>Election Period:</label>
		
		<input type="text" name="election_title" value="'.$row['election_period'].'" class="form-control" required >
		<input type="hidden" name="election_period"  value="'.$row['election_period'].'">
	   
		</div>
  <div class="form-group">
	<label>Election Date:</label>
	<input type="date" name="election_date" value="'.$row['election_date'].'" class="form-control" required>
  </div>
  <div class="form-group">
	<label>Election Type:</label>';
 
	  $sql=odbc_prepare($con,"SELECT * FROM election_type");
	  odbc_execute($sql);
	  echo '<select name="election_type" class="form-control" required>';
		  if(odbc_num_rows($sql)>0){
			  while($r=odbc_fetch_array($sql))
			  {
				  echo '<option value="'.$r['electionType'].'"';
				  if($r['electionType']==$row['election_type'])
				  {
					  echo 'selected';
				  }
				  echo '>'.$r['electionType'].'</option>';
			  }
		  }

	  echo '</select>
  </div>
  <div class="from-group">
  <input type="submit" name="save_edit_election" value="Save" class="btn btn-primary"> 
  </div>';

	}
	else{echo '<p class="text-danger">No information to display</p>';}
}


if(isset($_POST['register_candidate_code']))
{
	$election=htmlspecialchars($_SESSION['register_candidate_election_period'],ENT_QUOTES);
	$reg=htmlspecialchars($_POST['register_candidate_code'],ENT_QUOTES);

	$sql=odbc_prepare($con,"SELECT s.reg_no, s.name,s.course_code,
						c.course_description,s.election_period,e.election_type
						FROM Students s
						INNER JOIN Courses c on c.course_code=s.course_code
						INNER JOIN elections e on e.election_period=s.election_period 
						WHERE s.election_period=? AND s.reg_no=?");
		odbc_execute($sql,array($election,$reg));
	
		if(odbc_num_rows($sql)>0)
		{

		
	    $rows=odbc_fetch_array($sql);
	
			echo '
			
			<div class="form-group"><label>REG NO :</label> '.$rows['reg_no'].
			'<input type="hidden"  name="regno" value="'.$rows['reg_no'].'">
			</div>
			<div class="form-group">
			<label>STUDENT NAME :</label> '.$rows['name'].
			'<input type="hidden"   name="names" value="'.$rows['name'].'">
			</div>
			<div class="form-group">
					<label>NICK NAME :</label>
					<input type="text" name="nick_name" class="form-control">
					</div>
			<div class="form-group">
			<label>COURSE :</label> '.$rows['course_description'].
			'<input type="hidden" name="course" value="'.$rows['course_code'].'">
			</div>
			<div class="form-group">
			<label>ELECTION PERIOD :</label> '.$rows['election_period'].
			'<input type="hidden" name="election_period" value='.$rows['election_period'].' />
			</div>
				<div class="form-group"><label>POSITION :</label>';
				$course=$rows['course_code'];
				$position=odbc_prepare($con,"SELECT p.position_code,p.description
							from Courses c 
							INNER JOIN positions_department_relationship d on d.department_code=c.department_code
							INNER JOIN [position] p on p.position_code=d.position_code and p.position_status='active' and p.election_type=?
							where c.course_code=? ORDER BY p.priority_rating asc");
						$param=array($rows['election_type'],$course);
						odbc_execute($position,$param);
						echo '<select name="position" class="form-control" required>';
						while($p=odbc_fetch_array($position))
						{
							echo '<option value="'.$p['position_code'].'">'.$p['description'].'</option>';
						}
						echo '</select></div>
					
				  <div class="form-group"><label> PHOTO :</label>
					<input type="file" name="image" class="form-control"></div>

				 <div class="form-group">
				 <input type="submit" name="submit" value="submit" class="btn btn-primary" />
				 </div>';
			}
}
//--------upload aspirant photos-------------------
if(isset($_POST['upload_photo_candidate_code']))
{
     $reg_no=htmlspecialchars($_POST['upload_photo_candidate_code'],ENT_QUOTES);
	 $election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
	 $str="SELECT c.reg_no,c.name,p.position_code,p.description,c.image
			from candidates c 
			INNER JOIN [position]  p on p.position_code=c.position_code
			WHERE c.reg_no='$reg_no' and c.election_period='$election_period'";
	if($qry=odbc_exec($con,$str))
	{
	    $row=odbc_fetch_array($qry);
		$img="Candidates/".$election_period.'/'.$row['image'];
		echo '
		
		<div class="form-group">
		   <h3 class="page-header">
		   <img src="'.$img.'" height="100" width="100" ><br><br>
		   '.$row['name'].' ('.$row['reg_no'].')<br><br>
		   
		   Position: '.$row['description'].'</h3>
		   <input type="hidden" name="reg_no" value="'.$row['reg_no'].'">
		   <input type="hidden" name="old_photo" value="'.$row['image'].'">
		</div>
		
		<div class="form-group">
		  <label>Select Photo:</label>
		  <input type="file" name="photo" class="form-control" required>
		</div>
		<div class="form-group">
		<input type="submit" name="upload" value="Save" class="btn btn-primary" />
		</div>';
	
	}

}


//------------------------------------------------
if(isset($_POST['remove_department_posistion_code']))
{
     $department_code=$_POST['remove_department_posistion_code'];
	 
	$qry=odbc_prepare($con,"SELECT r.record_id,r.department_code,d.department_name,p.description
						 from  positions_department_relationship r
						INNER JOIN faculty_academic_departments d on d.department_code=r.department_code 
						INNER JOIN [position] p on p.position_code=r.position_code
						WHERE r.record_id=?");
				odbc_execute($qry,array($department_code));
		$r=odbc_fetch_array($qry);
		echo '<div class="form-group">
			  <p>Remove <strong>'.$r['department_name'].'</strong>, from <strong>'.$r['description'].'</strong>?</p>
			<input type="hidden" name="record_id" value="'.$r['record_id'].'">
			</div>
			<div class="form-group">
			<input type="submit" name="delete" value="Remove Department" class="btn btn-danger">
			</div>';


}

//---------------position department-------------------
if(isset($_POST['add_department_posistion_code']) or isset($_POST['edit_posistion_code']))
{
	if(isset($_POST['add_department_posistion_code']))
	{
     $p_code=htmlspecialchars($_POST['add_department_posistion_code'],ENT_QUOTES);
	}
	elseif($_POST['edit_posistion_code'])
	{
		$p_code=htmlspecialchars($_POST['edit_posistion_code'],ENT_QUOTES);
	}
	 
	 
	$qry=odbc_prepare($con,"SELECT p.position_code,p.description,
						p.position_status,p.election_type 
						from [position] p 
						where p.position_code=?");
		odbc_execute($qry,array($p_code));				
	$r=odbc_fetch_array($qry);
	
	if(isset($_POST['add_department_posistion_code']))
	{
      echo '<h4 class="page-header">'.$r['description'].'</h4>
			  <input type="hidden" name="position_title" value="'.$r['position_code'].'"  class="form-control" required > ';
	}
	elseif($_POST['edit_posistion_code'])
	{
		


        echo '<div class="form-group">
		<input type="text" name="position_tittle" value="'.$r['description'].'" class="form-control" required>
		<input type="hidden" name="position_code" value="'.$r['position_code'].'"  class="form-control" required > 
		</div>
		<div class="form-group">
		<label>Status:</label>
		<select name="position_status" class="form-control">
		     <option value="active"'; if($r['position_status']=='active'){echo 'selected';} echo '>Active</option>
			 <option value="Locked" '; if($r['position_status']=='Locked'){echo 'selected';} echo '>Lock</option>
		</select>
		</div>
		<div class="form-group">
		<label>Election Type:</label>';
	 
		  $sql=odbc_prepare($con,"SELECT * FROM election_type");
		  odbc_execute($sql);
		  echo '<select name="election_type" class="form-control" required>';
			  if(odbc_num_rows($sql)>0){
				  while($results=odbc_fetch_array($sql))
				  {
					  echo '<option value="'.$results['electionType'].'"';
					  if($results['electionType']==$r['election_type'])
					  {
						  echo 'selected';
					  }
					  echo '>'.$results['electionType'].'</option>';
				  }
			  }
	
		  echo '</select>
	  </div>
		<div class="form-group">
		<input type="submit" name="edit_position" value="Save" class="btn btn-primary">
		</div>
		';
	}
	 
        
            
				
				

}
//-------------select faculty courses--------------------------------------
if(isset($_POST['select_courses_faculty_code']))
{
    $faculty_code=htmlspecialchars($_POST['select_courses_faculty_code'],ENT_QUOTES);
	$qry=odbc_exec($con,"SELECT c.course_code,c.course_description from Courses c 
							WHERE c.faculty_code='$faculty_code' 
							order by c.course_description asc ");
	while($row=odbc_fetch_array($qry))
	{
	  echo '<option value="'.$row['course_code'].'">'.ucwords(strtolower($row['course_description'])).' ( '.$row['course_code'].')</option>';
	
	}



}

//---------------student passowrd----------------------------------------
if(isset($_POST['student_reg_no']))
{
	include("../functions/current_election.php");
   $reg_no=htmlspecialchars($_POST['student_reg_no'],ENT_QUOTES);
   $string="SELECT s.reg_no,s.name,s.election_period,s.password,
   			s.course_code,c.course_description 
			from Students s 
			INNER JOIN Courses c on c.course_code=s.course_code 
			WHERE s.reg_no=? and  s.election_period=?";
		$qry=odbc_prepare($con,$string);
		odbc_execute($qry,array($reg_no,$election));
		if(odbc_num_rows($qry)>0)
		{
			$username=$_SESSION['logged_user'];
			$date_issued=date('d/m/Y h:i:s a');
			//update the student password issuance details
			$update_user=odbc_prepare($con,"UPDATE Students 
										SET date_pwd_issued =?, password_issued_by =?
											where reg_no=? and election_period=?");
				odbc_execute($update_user,array($date_issued,$username,$reg_no,$election));
			//fetch student details	
			$row=odbc_fetch_array($qry);
			
			echo '<div class="form-group">
			         <h3>'.$row['name'].' </h3>
					</div>
					<div class="form-group">	
						<p><strong>COURSE: </strong> '.
						$row['course_description'].' ('.$row['course_code'].')</p>
						<br>
						</div>
						<div class="form-group">
						<p style="color:red; text-decoration:underline; text-weight:bold">
						Please write down the following voter login information:</p>
						</div>
						<div class="form-group">
						<div class="badge huge">Username: '.$row['reg_no'].' </div>
						<br><br>
						 <div class="badge huge">Password: '.$row['password'].'</div>
						 </div>';
		
		
		}


}

//-----------------------------------------
if(isset($_POST['reg']) or isset($_POST['search']))
{
	
   
   if(!empty($_POST['reg']) or $_POST['reg']!='')
   {

   $reg=htmlspecialchars($_POST['reg'],ENT_QUOTES);
 
	include("../functions/current_election.php");

	$color="1";
	$i=0;
	//--fetch campus accessed
	$campus_accessed=array();
	$username=$_SESSION['logged_user'];
	
//echo $campus_accessed;
	
	
$query=odbc_prepare($con,"SELECT top (7) s.reg_no,s.name,
					COUNT(reg_no) as total_student ,c.campus_description 
						FROM Students s
						inner JOIN campus c on c.campus_code=s.campus_code
						where (s.reg_no like '%'+?+'%' or 
						s.name like '%'+?+'%') and s.election_period=?
						and s.campus_code in (SELECT campus_code from admin_accounts_campus_accessed where username=?)
						GROUP BY s.reg_no,s.name,c.campus_description");
	odbc_execute($query,array($reg,$reg,$election,$username));
	echo '
	<h3 class="page-header">Search results for <strong>'.$reg.'</strong></h3>
	<table class="table table-bordered table-striped">
	<tr>
	<th colspan="4">KCAU  '.$election.' SAKU ELECTION</th></tr>
	<tr>
	<th></th>
	<th>Reg No</th>
	<th>Student Name</th>
	<th>Campus</th>
	<th></th>
	</tr>';
	while($row=odbc_fetch_array($query))
	{
		 $i++;
		if($row['total_student']>=1)
		{
		echo '<tr>
		<td>'.$i.'.</td>
		<td>
		<a href="#"  data-id="'.$row['reg_no'].'" data-target="#show_password_modal" data-toggle="modal">'.$row['reg_no'].'</a>
		</td>
		<td align="left">
		<a href="#"  data-id="'.$row['reg_no'].'" data-target="#show_password_modal" data-toggle="modal">'.$row['name'].'</a>
		</td>
		<td>'.$row['campus_description'].'</td>
		<td>
		<a href="#" class="btn btn-success" data-id="'.$row['reg_no'].'" data-target="#show_password_modal" 
		data-toggle="modal">Click here to Show Password</a></td>
		</tr>';
		}
		else{
			 echo '<tr><td colspan="4"> <span class="text-danger">No results</span></td></tr>';
			}
		
			
 }
echo '</table>';
   }
   else
   {
	   echo '<p style="color:red;"> Please Enter text to search</p>';
	}

	
}

//-----------------------------------------------------------------
 if(isset($_POST['set_voter_password_election_period']))
 {
	$election_period=htmlspecialchars($_POST['set_voter_password_election_period'],ENT_QUOTES); 
	$str="SELECT reg_no FROM students where election_period='$election_period'";
	if($qry=odbc_exec($con,$str))
	{   $x=0;
	    $y=0;
		while($row=odbc_fetch_array($qry))
		{
			$password=rand(1001, 9999);
			$reg_no=$row['reg_no'];
			if($update_password=odbc_exec($con,"UPDATE students SET password='$password' 
											WHERE reg_no='$reg_no' AND election_period='$election_period'"))
				{
					$y++;
					
				}
				else{
					  $x++;
					  
					}
		}
		echo '<div class="alert alert-success"><strong>Success </strong>'.number_format($y,0).' passwords have been set ; 
		<span class="text-danger"><strong> While the number of failed is </strong>'.number_format($x,0).'</span></div>';
	}
 
 }
//-----------------------------------------------------------
if(isset($_POST['edit_username']))
{
       if(isset($_POST['edit_username']))
	   {
		   $data=explode('_',$_POST['edit_username']);
		$username=$data[0];
		$type=$data[1];
	   }
	  $query=odbc_prepare($con,"SELECT a.username,a.name,a.user_type,
	  							a.election_period,a.account_status,a.date_created 
				   					from admin a where username=? ");
			odbc_execute($query,array($username));
		$row=odbc_fetch_array($query);
		if($type=='resetPassword')
		{
			echo '
			<div class="form-group">
			<h3 class="text-info">'.$row['name'].'</h3>
			<input type="hidden" name="username" value="'.$username.'">
			</div>
			<div class="form-group">
			   <label>New Password:</label>
			   <input type="password" class="form-control" placeholder="*******" name="password" id="password" required  autofocus>
			   </div>
			   <div class="form-group">
				<label>Confirm Password: </label>
				   <input type="password" class="form-control" placeholder="*******" name="confirm_password" id="repassword" required  autofocus>
					<p id="msg"></p>
				   </div>
			  
			   <div class="form-group">
			   <input type="submit" name="save_password" value="Save Password" class=" form-control btn btn-primary"><br><br>
			 
			  </div>';
		}
		elseif($type=='setCampus')
		{
			echo '<div class="form-group">
			   <h3 class="text-info">'.$row['name'].'</h3>
			   <input type="hidden" name="username" value="'.$row['username'].'">
			</div>
			<div class="form-group">
			<label>Select Campus:</label>';
			$campus=odbc_exec($con,"SELECT * FROM campus");
			if(odbc_num_rows($campus)>0)
			{
				while($c=odbc_fetch_array($campus))
				{
					echo '<p><input type="checkbox" name="campus[]" value="'.$c['campus_code'].'" > 
					'.$c['campus_description'].'</p>';
				}
			}
			echo '</div>
			<div class="form-group">
			<input type="submit" name="save_campus" value="Save" class="btn btn-primary">
			</div>';
		}
		elseif($type=='edit')
		{
		echo '
		<div class="form-group" >
                  <label>Full Name:</label>
                  <input type="text" name="fullname" value="'.$row['name'].'" class="form-control" required>
               </div>
               <div class="form-group">
                  <label>Username:</label>
				  <input type="hidden" name="old_username" value="'.$row['username'].'">
                  <input type="text" name="username" value="'.$row['username'].'" class="form-control" required>
               </div>
               
               <div class="form-group">
                 <label>Election Period:</label>';
                  
					  
					   $qry=odbc_exec($con,"SELECT election_period from elections ORDER BY election_period DESC");
					   echo '<select name="election_period" class="form-control" required>';
					   while($r=odbc_fetch_array($qry))
					   {
						   echo '<option value="'.$r['election_period'].'"';
						   if($r['election_period']==$row['election_period']){echo 'selected';}
						   
						   echo '>'.$r['election_period'].'</option>';
					   }
					   echo '</select>';
					
					
               echo '</div>
               
                <div class="form-group" >
                  <label>User Type:</label>';
                   
					  
					   $qry=odbc_exec($con,"SELECT user_type from  user_types");
					   echo '<select name="user_type" class="form-control" required>';
					   while($r=odbc_fetch_array($qry))
					   {
						   echo '<option value="'.$r['user_type'].'"';
						   if($r['user_type']==$row['user_type']){echo 'selected';}
						   
						   echo '>'.$r['user_type'].'</option>';
					   }
					   echo '</select>';
					
					
                  
              echo  '</div>
			  <div class="form-group">
			  <label>Account Status:</label>
			  <select class="form-control" name="account_status">
			      <option value="Active"'; if($row['account_status']=='Active'){echo 'selected';} echo'>Active</option>
				  <option value="Locked"'; if($row['account_status']=='Locked'){echo 'selected';} echo'>Locked</option>
			  </select>
			  </div>
			  <div class="form-group">
			   <input type="submit" name="save_edit_user" value="Save" class="btn btn-primary">
			  </div>';
					}
 

}

//------------------------------------------------------------------------
if(isset($_POST['load_courses_faculty_code']) )
{
	if(isset($_POST['load_courses_faculty_code'])){
     $faculty_code=htmlspecialchars($_POST['load_courses_faculty_code'],ENT_QUOTES);
	}
	
	 $department_code=htmlspecialchars($_SESSION['department_code'],ENT_QUOTES);
	 
	 $d_qry=odbc_exec($con,"SELECT d.department_code,d.department_name,f.description,f.faculty_code
							 from faculty_academic_departments d 
							INNER JOIN Faculty f on f.faculty_code=d.faculty_code WHERE d.department_code='$department_code'");
	 $d_row=odbc_fetch_array($d_qry);
	 echo '<h4 class="page-header">'.$d_row['description'].'</h4>
	 <p class="text-info"><strong>Select courses to register in the '.$d_row['department_name'].'</strong></p>';
	 
	 
	 
	 
	 
	 $sql=odbc_exec($con,"SELECT c.course_code,c.course_description FROM Courses c 
	 						WHERE c.faculty_code='$faculty_code' ORDER BY c.course_description asc");
	 if($sql)
	 {
		 while($row=odbc_fetch_array($sql))
		 {
			 echo '<div class="form-group">
			 
			 <input type="checkbox" name="courses[]" value="'.$row['course_code'].'">'
			 .ucwords(strtolower($row['course_description'])).'- ('.$row['course_code'].')
			 </div>';
		 }
		 echo '
			 <div class="form-group">
			   <input type="submit" name="save_course" value="Save Courses" class="btn btn-primary">
			 </div>
			 ';
	 }
}


//-----------------------------------------------
if(isset($_POST['select_department_faculty_code'])  or isset($_POST['table_department_faculty_code']) 
or isset($_POST['add_position_department_faculty_code']))
{
	if(isset($_POST['select_department_faculty_code']) ){
    $faculty_code=htmlspecialchars($_POST['select_department_faculty_code'],ENT_QUOTES);
	}
	elseif(isset($_POST['table_department_faculty_code']))
	{
		 $faculty_code=htmlspecialchars($_POST['table_department_faculty_code'],ENT_QUOTES);
	}
	elseif(isset($_POST['add_position_department_faculty_code']))
	{
		 $faculty_code=htmlspecialchars($_POST['add_position_department_faculty_code'],ENT_QUOTES);
	}
	$qry=odbc_exec($con,"SELECT a.department_code,a.department_name from faculty_academic_departments a WHERE a.faculty_code='$faculty_code'");
	if($qry)
	{
		if(isset($_POST['select_department_faculty_code']) ){
			  while($row=odbc_fetch_array($qry))
			  {
				  
				  echo '<option value="'.$row['department_code'].'">'.$row['department_name'].'</option>';
				  
			  }
	  }
		  elseif(isset($_POST['table_department_faculty_code'])){
			  echo '<ol>';
			  while($row=odbc_fetch_array($qry))
			  {
				  
				  echo '<li>
				  <input type="checkbox" name="department[]" value="'.$row['department_code'].'"> '.$row['department_name'].'
				  </li>';
				  
			  }
			  echo '</ol>
			 
			 
			   <input type="submit" name="save_position" value="Save" class="btn btn-primary">';
			  
			}
			 elseif(isset($_POST['add_position_department_faculty_code'])){
			  echo '<ol>';
			  while($row=odbc_fetch_array($qry))
			  {
				  
				  echo '<li>
				  <input type="checkbox" name="department[]" value="'.$row['department_code'].'"> '.$row['department_name'].'
				  </li>';
				  
			  }
			  echo '</ol>
			 
			 
			   <input type="submit" name="save_position_departments" value="Save" class="btn btn-primary">';
			  
			}
	}

}


//---------------------------------------------

if(isset($_POST['department_faculty_code']))
{

    $faculty_code=htmlspecialchars($_POST['department_faculty_code'],ENT_QUOTES);
	 $query=odbc_exec($con,"SELECT faculty_code,description from Faculty WHERE faculty_code='$faculty_code'  ");
	if($query)
	{
		$row=odbc_fetch_array($query);
		
		echo '<h3 class="page-header">'.$row['description'].'</h3>
		     <div class="form-group">
			  <label>Academic Department:</label>
			  <input type="hidden" name="faculty_code" value="'.$row['faculty_code'].'">
			  <input type="text" name="department_name"  class="form-control" required >
			  </div>
			  <div class="form-group">
			   <input type="submit" name="save_department" value="Save" class="btn btn-success">
			  </div>';
		
		
	}


}

?>