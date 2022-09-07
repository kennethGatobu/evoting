<?php
//error_reporting(0);

try
{
		
		
		$con=odbc_connect("Driver={ODBC Driver 17 for SQL Server}; Server=localhost;
					
					 		database=E_voting",'sa','Michelle@3');
		
	
		
		if(!$con)
		{
			echo "Error failed to connect";
			echo odbc_error($con);
		}
}
catch(Exception $e)
{
	echo "message :". $e ->getMessage();
	
}

$APP_PATH='/var/www/html/evoting';



?>