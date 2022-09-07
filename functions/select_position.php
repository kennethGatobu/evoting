<?php
     $course=$rows['course_code'];
	$position=odbc_exec($con,"SELECT p.position_code,p.description
							from Courses c 
							INNER JOIN positions_department_relationship d on d.department_code=c.department_code
							INNER JOIN [position] p on p.position_code=d.position_code and p.position_status='active'
							where c.course_code='$course' ORDER BY p.priority_rating asc");

echo '<select name="position">';
while($p=odbc_fetch_array($position))
{
	echo '<option value="'.$p['position_code'].'">'.$p['description'].'</option>';
}
echo '</select>';
?>