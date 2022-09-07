<?php
$query_string="SELECT *FROM elections where status=?";

$res = odbc_prepare($con, $query_string);
if(!$res) die("could not prepare statement ".$query_string);

$ele="open";
$parameters=array($ele);
if(odbc_execute($res, $parameters)) {
   while( $row = odbc_fetch_array($res))
   $election= $row['election_period'];
   
	
} else
 {
    // handle error
}
?>