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
	if(isset($_REQUEST['election_period']) and isset($_REQUEST['position'])
	and isset($_SESSION['campus']))
	{
		
		$position_code=$_REQUEST['position_code'];
		$election_period=$_REQUEST['election_period'];
		$position=$_REQUEST['position'];		
		 $campus=$_SESSION['campus'];
//fetch semester details-----------------------------------------------------------------
	
$candidates=odbc_prepare($con,"SELECT *FROM candidates 
							where position_code=?
									 AND election_period=? and campus=?");
				
	odbc_execute($candidates,array($position_code,$election_period,$campus)	);
				
				   
				   $html.= '
				   <h3 align="center" style="color:#182A5C">'.$election_period.' ELECTION BALLOT ('.$_SESSION['campus_name'].')</h3>
				   <h4 align="center">'.strtoupper($position).'</h4>';
				
			
			
			$i=1;
			//------------------------------------------------------------------------------------
		$html.= '<table width="100%" style="font-size:12px;" border="1" cellspacing="0">
				<tr>
				<th>#</th>
				<th>PHOTO</th>
				<th>ADMISSION NO</th>
				<th>FULLNAME</th>
				<th>ALIAS</th>
				<th>SIGN</th>
				</tr>';
	   
				while($row=odbc_fetch_array($candidates))
				{
					$html.= '<tr>
					  	 <td>'.$i++.'</td>
						   <td><img src="Candidates/'.$election_period.'/'.$row['image'].'" width="80" height="80">
						   </td>
						   <td>'.$row['reg_no'].'</td>
						   <td>'.$row['name'].'</td>
						   <td>'.$row['other_names'].'</td>
						   <td> </td> 
						</tr>';
				}		
	   
	 
		$html.= '</table>
		<P>OFFICIAL COPY OF <strong>'.strtoupper($position).'</strong> E-BALLOT FOR SAKU '.$election_period.' ELECTION </p>
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

$dompdf->stream(str_replace('/','_',strtoupper($position))." SAKU BALLOT PAPER.pdf",array("Attachment"=>1));

}

?>