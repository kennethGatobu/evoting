<?php
error_reporting(0);
if(isset($_POST['reg_no']) 
and isset($_POST['student_name']) 
and isset($_POST['gender']) 
and isset($_POST['email']) 
and isset($_POST['course_code'])
and isset($_POST['campus_code'])
and isset($_POST['username'])
and isset($_POST['_token']))
{
    include 'Classes/_function_classes.php';
    
       
        $reg_no=htmlspecialchars($_POST['reg_no'],ENT_QUOTES);	
        $fullname=htmlspecialchars($_POST['student_name'],ENT_QUOTES);
        $gender=htmlspecialchars($_POST['gender'],ENT_QUOTES);
        $campus=htmlspecialchars($_POST['campus_code'],ENT_QUOTES);
        $course=htmlspecialchars($_POST['course_code'],ENT_QUOTES);
        $email=htmlspecialchars($_POST['email'],ENT_QUOTES);
        $username=htmlspecialchars($_POST['username'],ENT_QUOTES);
        $token=htmlspecialchars($_POST['_token'],ENT_QUOTES);
        //validate the api key authorization
        $validate_api_key=odbc_prepare($con,"SELECT * FROM admin 
                        where username=? and _token=? and user_type='admin'");
         odbc_execute($validate_api_key,array($username,$token));
         if(odbc_num_rows($validate_api_key)>0)
         {
              //fetch the current active election
        $election=odbc_prepare($con,"SELECT election_period FROM elections where [status]='open'");
        odbc_execute($election);
        if(odbc_num_rows($election)>0)
        {
            $row=odbc_fetch_array($election);

            $election_period= $row['election_period'];

            $insert_user=odbc_prepare($con,"INSERT INTO Students 
                             (reg_no,[name],course_code,election_period,campus_code,gender,email)
                                VALUES(?,?,?,?,?,?,?)");
        $query_parameter=array($reg_no,$fullname,$course,$election_period,$campus,$gender,$email);						 
						if(odbc_execute($insert_user,$query_parameter))
                        {
						echo 'Success! Record has been saved';
						}
					else{
							echo 'Error! Record cannot be saved';
						}
            
        }else{
            echo 'Sorry, you cant create new register at the moment';
        }



         }else{
             echo 'Failed,Invalid API Authorization Parameters';
         }



       
        

}

?>