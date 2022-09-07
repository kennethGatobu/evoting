<?php
function student(){
	
			  
	if(!isset($_SESSION['voter_info']))
	{
			
		header("location:logout.php");
		
		
	}
	

}
function admin(){
	$user_array=array("admin");
			  
	if(isset($_SESSION['xyz']))
	{
		if(!in_array($_SESSION['xyz'],$user_array))
	    {
			
	      header("location:voters_password.php");
		}
		
	   
		
	}
	 else{
		header("location:logout.php");
		}


}
function livestream(){
	$user_array=array("admin","livestream","IECK Chairperson");
			  
	if(isset($_SESSION['xyz']))
	{
		if(!in_array($_SESSION['xyz'],$user_array))
	    {
			
	      header("location:voters_password.php");
		}
		
	   
		
	}
	 else{
		header("location:logout.php");
		}


}
function admin_ieck_chair()
{
	$user_array=array("admin","IECK Chairperson");
			  
	if(isset($_SESSION['xyz']))
	{
		if(!in_array($_SESSION['xyz'],$user_array))
	    {
			
	      header("location:voters_password.php");
		}
		
	   
		
	}
	 else{
		header("location:logout.php");
		}


} 

function admin_clerk()
{
	$user_array=array("admin","clerk","IECK Chairperson");
			  
	if(isset($_SESSION['xyz']))
	{
		if(!in_array($_SESSION['xyz'],$user_array))
	    {
			
	      header("location:turnout_stream.php");
		}
		
	   
		
	}
	 else{
		header("location:logout.php");
		}
}

?>