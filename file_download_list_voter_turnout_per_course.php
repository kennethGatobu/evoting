<?php
ob_start();
session_start();
if(!isset($_SESSION['xyz']))
{
		 header("location:index.php");	
}
require 'Classes/_function_classes.php';

require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
$html='

<html>
<head>

<style>

table{font-size:12px; padding:4px;}
tr{page-break-after: always;}
th{text-align:left; padding:4pt;}
td{padding:5pt;}
.text-right{text-align:right;}
@page {
	margin: 100px 25px;
}
header {
	position: fixed;
	left: 0px;
	right: 0px;
	height: 180px;
	margin-top: -60px;
	margin-bottom:10px;
	/** Extra personal styles **/
	background-color: #182b5c;
	color: white;
	text-align: center;
	line-height: 25px;
}
main{
	//border:1px solid red;
	//margin-top:75px;
	page-break-after: always;
}
body { //border: 1px solid red;
	 margin-top: 3.5cm;
	}
footer {
	position: fixed; 
	bottom: -60px; 
	left: 0px; 
	right: 0px;
	height: 50px; 

	/** Extra personal styles **/
	background-color: #182B5C;
	color: white;
	text-align: center;
	line-height: 35px;
}
#no_border_table{ border:none;}
#bold_row{ font-weight:bold;}
#amount{
	text-align:right;
	font-weight:bold;}
.pagenum:before{content: counter(page);}
</style>
</head>
<body>
<header>';
$html.=new_report_heading($con);
$html.='</header>
<footer>
<span style="float:left" >Page: <span class="pagenum"></span></span>
A Premier Business and Technology University of Choice
</footer>
<main>';

//----------------------------------------------------------------------------------------
	if(isset($_REQUEST['election_period']))
	{
		
	
		$election_period=$_REQUEST['election_period'];
		$course=$_REQUEST['course'];
		$course_code=$_REQUEST['course_code'];		
		 
//fetch semester details-----------------------------------------------------------------
	
$count=odbc_exec($con,"SELECT DISTINCT v.student_reg ,s.name,v.time_voted,v.host_ip_address,
							cast(v.time_voted as TIME) as tt,c.course_description , 
							COUNT(v.student_reg ) as total_position
							from votes v
							INNER JOIN Students s on s.reg_no=v.student_reg and v.election_period=s.election_period
							INNER JOIN  Courses c on c.course_code=s.course_code
							WHERE v.election_period='$election_period' AND s.course_code='$course_code'
							GROUP BY v.student_reg ,s.name,v.time_voted,c.course_description,v.host_ip_address
							ORDER BY tt ASC");
				   
				
				   
				   $html.= '
				   <h3 align="center" style="color:#182A5C">'.$election_period.' ELECTION VOTER TURNOUT ANALYSIS PER COURSE</h3>
				   <h4 align="center"  style="color:#182A5C">'.$course.'</h4>';
				
			
			
			$i=1;
			//------------------------------------------------------------------------------------
		$html.= '<table width="100%" style="font-size:12px;" border="1" cellspacing="0">
				<tr>
				<th>#</th>
				<th>TIME</th>
				<th>ADMISSION NUMBER</th>
				<th>STUDENT NAME</th>
				<th>COMPUTER USED</th>
				<th id="amount">NO. OF POSITION VOTED </th>
				
				</tr>';
				$total_votes=0;
				while($row=odbc_fetch_array($count))
				{
					$total_votes+=1;
					$html.= '<tr>
					  	 <td>'.$i++.'.</td>
						   
						   <td>'.$row['time_voted']. '</td>
						   <td>'.$row['student_reg']. '</td>
						   <td>'.$row['name']. '</td>
						   <td>'.$row['host_ip_address']. '</td>
						   <td id="amount">'.$row['total_position']. '</td>
						</tr>';
				}		
	   
	 
		$html.= '
				<tr>
					
					<th  colspan="6">TOTAL VOTERS : '.number_format($total_votes,0).'</th>
				</tr>
				</table>
		<P>VOTER TOURNOUT ANALYSIS FOR SAKU '.$election_period.' ELECTION </p>
		<p><strong>NAME :</strong>..............................................................
		<strong>SIGN :</strong>................................
		<strong>DATE :</strong>............................
										  <br>(IECK CHAIRPERSON)</p>';
	
	
			

	

//-----------------------------------------------------------------------------

$html.='</main></body> </html>';

//echo $html;
$dompdf=new Dompdf();
$dompdf->load_html($html);
//$dompdf->set_paper('A4','landscape');
$dompdf->set_paper('A4');
$dompdf->getOptions()->setChroot($APP_PATH);

$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('isRemoteEnabled',TRUE);
$dompdf->render();

$dompdf->stream(str_replace('/','_',strtoupper($election_period))." TURNOUT ANALYSIS PER COURSE.pdf",array("Attachment"=>1));

}

?>