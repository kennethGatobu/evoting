<?php
session_start();
if(!isset($_SESSION['voter_info']))
{
	header("location:../login.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../images/kca.jpg" />
<title>Already Voted</title>
<link  href="../styles.css"  rel="stylesheet" type="text/css" media="screen" />
<link  href="../print.css"  rel="stylesheet" type="text/css" media="print" />

<script language="javascript"  src="../scripts/myscript.js" type="text/javascript"></script>

<script language='VBScript'>
/*Sub Prints()
       OLECMDID_PRINT = 6
       OLECMDEXECOPT_DONTPROMPTUSER = 2
       OLECMDEXECOPT_PROMPTUSER = 1
       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
End Sub
document.write "<object ID='WB' WIDTH=0 HEIGHT=0 CLASSID='CLSID:8856F961-340A-11D0-A96B-00C04FD705A2'></object>"*/
</script>
<script language="VBScript">
// THIS VB SCRIP REMOVES THE PRINT DIALOG BOX AND PRINTS TO YOUR DEFAULT PRINTER
Sub window_onunload()
On Error Resume Next
Set WB = nothing
On Error Goto 0
End Sub

Sub Print()
OLECMDID_PRINT = 6
OLECMDEXECOPT_DONTPROMPTUSER = 2
OLECMDEXECOPT_PROMPTUSER = 1


On Error Resume Next

If DA Then
call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)

Else
call WB.IOleCommandTarget.Exec(OLECMDID_PRINT ,OLECMDEXECOPT_DONTPROMPTUSER,"","","")

End If

If Err.Number <> 0 Then
If DA Then 
Alert("Nothing Printed :" & err.number & " : " & err.description)
Else
HandleError()
End if
End If
On Error Goto 0
End Sub

If DA Then
wbvers="8856F961-340A-11D0-A96B-00C04FD705A2"
Else
wbvers="EAB22AC3-30C1-11CF-A7EB-0000C05BAE0B"
End If

document.write "<object ID=""WB"" WIDTH=0 HEIGHT=0 CLASSID=""CLSID:"
document.write wbvers & """> </object>"
</script>
</head>

<body onload="javascript:window.Prints();">
<table id="table_content">
<tr><td id="tr_header_text"></td></tr>
<tr id="tr_header" >
<td></td>
</tr>
  <tr id="tr_link" ><td >
	<?php
   // include 'links.php';
    ?>
</td></tr>
<tr><td align="right" id="tr_name">
<?php
if(isset($_SESSION['name']))
{
	echo "User: ".$_SESSION['name'];
}

?>

</td></tr>
<tr>

<td valign="top" align="center" >

<?php

include("../config/db_con.php");
include("../functions/current_election.php");
$reg=$_SESSION['voter_info'];
//--------------------------------------------------------------------------------------
$logged_status="OFFLINE";
$time=date("h:i:s a");
		$update_user=odbc_exec($con,"UPDATE  Students SET logged_status='$logged_status', time_logged_out='$time' where reg_no='$reg' AND election_period='$election'");
//---------------------------------------------------------------------------------------
$i=1;
$color="1";
$query=odbc_exec($con,"SELECT c.reg_no,c.name,p.description,c.image,c.election_period,c.other_names,vote_id
						FROM votes v 
						INNER JOIN candidates c ON c.reg_no=v.reg_no AND c.election_period=v.election_period
						INNER JOIN [position] p ON p.position_code=c.position_code
						WHERE v.student_reg='$reg' AND v.election_period='$election'
						ORDER BY p.priority_rating ASC");
echo '<table class="dm" id="mt" cellspacing="0"  border="1" width=60%">
<tr><th colspan="6"><h1>KCAU SAKU '.$election.' ELECTIONS</h1> </th></tr>
<tr><td   id="bold_row" >Full Name: </td><td  id="bold_row" colspan="5">'.$_SESSION['name'].'</td></tr>
<tr><td   id="bold_row" >Reg No: </td><td  id="bold_row" colspan="5">'.$reg.'</td></tr>

<tr><td  colspan="6"><h2 style="color:red;">You Have already voted for the following</h2></td></tr>
<tr>
<td></td>
<td id="bold_row">Photo</td>
<td id="bold_row">Candidate Name</td>
<td></td>
<td id="bold_row">Position</td>
<td id="bold_row">Vote REF NO</td>

<tr>';
while($row=odbc_fetch_array($query))
{
	
	echo '<tr><td>'.$i++.".".'</td><td width="125">
	<img width="125" height="125" src="../Candidates/'.$election.'/'.$row['image'].'">'.
	'</td><td>'.$row['name'].'</td><td>'.$row['other_names'].
	'</td><td>'.$row['description'].
	'</td><td>'.
	$row['vote_id'].
	'</td></tr>';
}


echo '<tr><td id="lv"><a href="../logout.php">Log Out</a></td> 
<td colspan=5>
<a href="_vote_send_.php" onClick=" window.print(); ">Print
      this page</a>
	  
</td></tr>';
echo '</table>';
//<input type="button" onclick=" printResults()" name="pr" value="print">
?>

</td></tr>
<tr >
 
    <td id="tr_footer"><?php include '../functions/footer.php'; ?></td>
  </tr>
</table>
</body></html>