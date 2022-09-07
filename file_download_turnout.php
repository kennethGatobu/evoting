<?php

ob_start();
session_start();
error_reporting(0);
if(!isset($_SESSION['xyz']))
{
		 header("location:index.php");	
}
require 'Classes/_function_classes.php';

require_once 'vendor/autoload.php';
use Dompdf\Dompdf;

if(isset($_REQUEST['turn_out']) and isset($_SESSION['campus']))
{
		
	
		$election_period=$_REQUEST['turn_out'];
		$campus=$_SESSION['campus'];
				
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

		$html.= '<h3 align="center" style="color:#182A5C">'.$election_period.' ELECTION VOTER TURNOUT ANALYSIS
		<br>('.$_SESSION['campus_name'].')</h3>'; 
//fetch faculty details
$position=odbc_exec($con,"SELECT faculty_code,description 
							FROM faculty where faculty_code !='general'");

				
$html.= '<table width="100%" style="font-size:12px;" border="1" cellspacing="0">';
$overall_votes=0;
while($faculty=odbc_fetch_array($position))
{	
	$faculty_code=$faculty['faculty_code'];
	$total_faculty_votes=0;
$html.='<tr>
		<th colspan="4">'.$faculty['description'].'</th>

		</tr>
		<tr>
			<th></th>
		  <th>COURSE CODE</th>
		  <th>COURSE TITLE</th>
		  <th id="amount">TOTAL VOTES</th>
		</tr>';


$count=odbc_prepare($con,"SELECT  s.course_code ,c.course_description,s.election_period,
						count( DISTINCT v.student_reg) as total_votes 
						from votes v
						INNER JOIN Students s on s.reg_no=v.student_reg and s.election_period=v.election_period and s.campus_code=?
						INNER JOIN Courses c on c.course_code=s.course_code
						WHERE v.election_period=? AND c.faculty_code=?
						GROUP BY s.course_code,s.election_period,c.course_description
						ORDER BY total_votes DESC");
				
				   
			odbc_execute($count,array($campus,$election_period,$faculty_code));	   
			
			
			$i=1;
			//------------------------------------------------------------------------------------
	
			
				while($row=odbc_fetch_array($count))
				{
					$overall_votes=$overall_votes+$row['total_votes'];
					$total_faculty_votes=$total_faculty_votes+$row['total_votes'];
					$html.= '<tr>
					  	 <td>'.$i++.'.</td>
						   
						   <td>'.strtoupper($row['course_code']). '</td>
						   <td>'.strtoupper($row['course_description']). '</td>
						   <td id="amount">'.number_format($row['total_votes'],0). '</td>
						  
						</tr>';
				}	
				$html.='<tr>
					
				<th colspan="3" id="amount">TOTAL FACULTY VOTES :</th>
				<th id="amount"> '.number_format($total_faculty_votes,0).'</th>
			</tr>';
			}
	 
		$html.= '
				<tr>
					
					<th colspan="3" id="amount">TOTAL VOTERS :</th>
					<th id="amount"> '.number_format($overall_votes,0).'</th>
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

$dompdf->stream("SAKU ELECTION  TURNOUT ANALYSIS ".str_replace('/','_',strtoupper($election_period)).".pdf",array("Attachment"=>1));

}

?>