<?php
session_start();
include '_SESSIONS_/_session_admin.php';
error_reporting(0);
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
  <tr valign="top" > <td>
<?php
include 'config/db_con.php';
if(isset($_GET['position_code']))
{
	$position_code=$_GET['position_code'];
	$position=$_GET['position'];
$query=odbc_exec($con,"SELECT p.position_code ,f.faculty_code,f.description 
					   FROM positions_faculty_relationship p
					   INNER JOIN Faculty f ON	f.faculty_code=p.faculty_code
					    WHERE p.position_code='$position_code'");
if($query)
{
	$i=1;
echo '<table border="2" cellspacing="0" cellpadding="10" width="50%" align="center" class="dm">'.
'<tr><td colspan="2" id="bold_row" style="font-size:15px;">POSITIONS:</td>
<td colspan="2" id="bold_row" style="font-size:15px;">'.$position.'</td></tr>'.
'<tr><td id="tr_sub_header" colspan="4">The following Faculties can vote in this position</td></tr>'.
'<tr style="font-weight:bold"><td></td>
<td id="bold_row">Faculty Code</td>
<td  id="bold_row">Faculty</td>
<td id="bold_row" >Status </td>
</tr>';
while($row=odbc_fetch_array($query))
{
	echo '<tr><td>'.$i++.'</td>
	<td>'.$row['faculty_code'].
	'</td><td>'.
	$row['description'].
	'</td><td>
	<a href="?position_code='.$row['faculty_code'].' & position='.$row['description'].'">
	View Details</a>
	</td></tr>';
}	
echo '<tr><td colspan="4"><a href="positions_settings.php">Go Back</a>
</td></tr>'.
'</table>';
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