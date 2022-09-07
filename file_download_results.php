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

if(isset($_REQUEST['election_period']) and isset($_SESSION['campus']))
{
		
	     $campus=$_SESSION['campus'];
		$election_period=$_REQUEST['election_period'];
				
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

//fetch faculty details
$position=odbc_prepare($con,"SELECT  c.position_code,p.description ,
								count(c.position_code) as total 
								from candidates c
								INNER JOIN [position] p on p.position_code=c.position_code
								where election_period=? and c.campus=?
								GROUP BY c.position_code,p.description,p.priority_rating
								ORDER BY p.priority_rating asc");
odbc_execute($position,array($election_period,$campus));
				

$overall_votes=0;
while($pos=odbc_fetch_array($position))
{	
	$position_code=$pos['position_code'];
	$total_position_votes=0;
	$html.= '<h3 align="center" style="color:#182A5C">'.
			$election_period.' ELECTION RESULTS ('.$_SESSION['campus_name'].')<br>
			'.strtoupper($pos['description']).'</h3>'; 
	$html.= '
	<table width="100%" style="font-size:12px;" border="1" cellspacing="0">';
$html.='<tr>
			<th></th>
			<th>PHOTO</th>
		  <th>ADMISSION NUMBER</th>
		  <th>FULLNAME</th>
		  <th id="amount">TOTAL VOTES</th>
		</tr>';


	$votes=odbc_prepare($con,"SELECT c.reg_no,c.name,c.image,
					count(v.reg_no) as total_votes 
					FROM candidates c 
					LEFT JOIN votes v on c.reg_no=v.reg_no AND v.election_period=c.election_period 
					WHERE c.position_code=? AND c.election_period=? and c.campus=?
					GROUP BY c.reg_no,c.name,c.image 
					ORDER BY count(v.reg_no) DESC");
	odbc_execute($votes,array($position_code,$election_period,$campus));	   
				   
			
			
			$i=1;
			//------------------------------------------------------------------------------------
	
			
				while($row=odbc_fetch_array($votes))
				{
				
					$total_position_votes=$total_position_votes+$row['total_votes'];
					$html.= '<tr>
					  	 <td>'.$i++.'.</td>
						   <td><img src="Candidates/'.$election_period.'/'.$row['image'].'" width="80" height="80">
						   </td>
						   <td>'.strtoupper($row['reg_no']). '</td>
						   <td>'.strtoupper($row['name']). '</td>
						   <td id="amount">'.number_format($row['total_votes'],0). '</td>
						  
						</tr>';
				}	
				
			
	 
		$html.= '
				<tr>
					
					<th colspan="4" id="amount">TOTAL VOTES :</th>
					<th id="amount"> '.number_format($total_position_votes,0).'</th>
				</tr>
				</table>';
			
	$html.=	'<P >OFFICIAL RESULTS FOR SAKU '.$election_period.' ELECTION </p>
		<p style="page-break-after: always;">
		<strong>NAME :</strong>..............................................................
		<strong>SIGN :</strong>................................
		<strong>DATE :</strong>............................
										  <br>(IECK CHAIRPERSON)</p>';
	
	
}

	

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

$dompdf->stream("SAKU ELECTION ".str_replace('/','_',strtoupper($election_period))." RESULTS.pdf",array("Attachment"=>1));

}

?>