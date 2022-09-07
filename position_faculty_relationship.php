<?php
session_start();
include '_SESSIONS_/_session_admin.php';
error_reporting(0);
include 'Classes/_function_classes.php';
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
  <tr valign="top" > <td style="text-align:center">
<?php

if(isset($_POST['submit']))
{
	foreach($_POST['faculty'] as $faculty_code)
	{
		$position_code=$_GET['position_code'];
		$query=odbc_exec($con,"INSERT INTO positions_faculty_relationship(faculty_code,position_code)
		 VALUES('$faculty_code','$position_code')");
	}
	if($query)
	{
		echo '<br><br><p style="font-size:15px; color:blue">Records sucessfuly saved</p>';
	}
	else
	{
		echo '<br><br><p style="font-size:15px; color:red">Records NOT saved,either it already exist or an error occured</p>';
	}
}
if(isset($_GET['position']))
{
$query=odbc_exec($con,"SELECT faculty_code,description from Faculty");
if($query)
{
	$i=1;
	echo '<form action="" method="post">
	<table border="2" cellspacing="0" cellpadding="10" width="50%" align="center" class="dm">'.
		'<tr><th colspan="3" style="font-size:15px;">POSITIONS: '.$_GET['position'].'</th></tr>'.
		'<tr><td colspan="3"><em>Select which faculties can vote in position</em></td></tr>'.
		'<tr style="font-weight:bold"><td></td>
		<td id="bold_row">Faculty</td>
		<td id="bold_row" >Select </td></tr>';
while($row=odbc_fetch_array($query))
{
	echo '<tr><td>'.$i++.'</td>
	<td>'.$row['description'].
	'</td><td>'.

	'<input type="checkbox" name="faculty[]" value="'.$row['faculty_code'].'">'.
	'</td></tr>';
}	
echo '<tr>
<td><a href="positions_settings.php">Go Back</a></td>
<td colspan="2" style="text-align:center;">
<input type="submit" name="submit" value="Save">
</td></tr>'.
'</table></form>';
}
}

?></td>
  </tr>
  <tr >
 
    <td id="tr_footer"><?php include 'functions/footer.php'; ?></td>
  </tr>
</table>
</body>
</html>