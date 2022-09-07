<?php
error_reporting(0);
if( isset($_POST['reg_no']) 
and isset($_POST['username'])
and isset($_POST['_token'])
and isset($_POST['_election_period']))
{
    include 'Classes/_function_classes.php';   
        $reg_no=htmlspecialchars($_POST['reg_no'],ENT_QUOTES);	     
        $username=htmlspecialchars($_POST['username'],ENT_QUOTES);
        $token=htmlspecialchars($_POST['_token'],ENT_QUOTES);
        $election_period=htmlspecialchars($_POST['_election_period'],ENT_QUOTES);
        //validate the api key authorization
        $validate_api_key=odbc_prepare($con,"SELECT * FROM admin 
                        where username=? and _token=? and user_type='admin'");
         odbc_execute($validate_api_key,array($username,$token));
         if(odbc_num_rows($validate_api_key)>0)
         {
              //fetch the current active election
                    $election=odbc_prepare($con,"SELECT [name] FROM Students where reg_no=? and election_period=? ");
                    odbc_execute($election,@array($reg_no,$election_period));
                    if(odbc_num_rows($election)>0)
                    {
                      
                    echo 'Success! You are a registered voter.';
                                   
                    }
                    else
                    {
                        echo 'Sorry, You are not a registered voter at the moment';
                    }
          }
          else
          {
             echo 'Failed,Invalid API Authorization Parameters';
          }

}

?>