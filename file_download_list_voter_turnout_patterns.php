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
	if(isset($_REQUEST['election_period']) and isset($_SESSION['campus']))
	{
		
	
		$election_period=$_REQUEST['election_period'];
		$campus=$_SESSION['campus'];		
		 
//fetch semester details-----------------------------------------------------------------
	
$count=odbc_prepare($con,"SELECT   time_line= DATEPART(HOUR, cast(v.time_voted as TIME)),
                                    COUNT( DISTINCT v.student_reg ) as total_voters
                                    from votes v
                                    INNER JOIN Students s on s.reg_no=v.student_reg 
                                    and s.election_period=v.election_period 
                                    WHERE v.election_period=? and s.campus_code=?
                                    GROUP BY DATEPART(HOUR, (cast(v.time_voted as TIME)))
                                    ORDER BY DATEPART(HOUR, cast(v.time_voted as TIME)) ASC");

                        odbc_execute($count,array($election_period,$campus));
				   
				
				   
				   $html.= '
				   <h3 align="center" style="color:#182A5C">'.$election_period.
				   ' ELECTION VOTER TURNOUT ANALYSIS <br>('.$_SESSION['campus_name'].') </h3>
				   ';
				
			
			
			$i=1;
			//------------------------------------------------------------------------------------
		$html.= '<table width="100%" style="font-size:12px;" border="1" cellspacing="0">
				<tr>
				<th>#</th>
				<th>TIME DURATION</th>
				<th id="amount">NO. OF VOTERS </th>
				
				</tr>';
				$total_votes=0;
				while($row=odbc_fetch_array($count))
				{
					$total_votes=$total_votes+$row['total_voters'];
					$start_time=$row['time_line'].':00:00';
					$end_time=$row['time_line'].':59:59';
					$html.= '<tr>
					  	 <td>'.$i++.'</td>
						   
						   <td>'.
						   date('h:i:s A',strtotime($start_time)).
						   '     to     '
						   .date('h:i:s A',strtotime($end_time)).
						   '</td>
						  
						   <td  id="amount">'.number_format($row['total_voters'],0). '</td> 
						</tr>';
				}		
	   
	 
		$html.= '
				<tr>
					<td></td>
					<td  id="amount">TOTAL VOTES</td>
					<td id="amount">'.number_format($total_votes,0).'</td>
				</tr>
				</table>
		<P>VOTER TOURN OUT ANALYSIS FOR SAKU '.$election_period.' ELECTION </p>
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

$dompdf->stream(str_replace('/','_',strtoupper($election_period))." HOURLY VOTER TURNOUT ANALYSIS.pdf",array("Attachment"=>1));

}

?>