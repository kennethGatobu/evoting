<?php
ob_start();
session_start();
require( 'fpdf/fpdf.php' );
date_default_timezone_set('Africa/Nairobi');
error_reporting(0);
class PDF extends FPDF
{

function Header()
{
	$this->SetFont('Arial','B',19);
    
	$this->SetTextColor(0,80,180);
   	$img= $this->Image('images/kca.jpg',90,$this->GetY(),30,30);
    // Arial bold 15
		
   
    $this->Cell(30,30,$img,0,'C');
	$this->SetFont('Arial','B',13);
	
	$this->Ln();
	$this->Cell(0,7,'STUDENTS ASSOCIATION OF KCA UNIVERSITY (SAKU)',0,0,'C');
	$this->Ln();
	$this->SetFont('Arial','B',13);
	$this->SetTextColor(0,0,0);
	$this->Cell(0,7,$_GET['election_period'].' ELECTION VOTER TURNOUT ANALYSIS ('.$_SESSION['campus_name'].')',0,0,'C');
		
	$this->Ln();
    
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
	$this->Cell(0,4,'Printed on '.date('D, F j, Y, g:i a'),0,0,'R');
	$this->Ln();
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C');
}

// Simple table
function BasicTable($election_period,$position_code,$position,$campus)
{
    
		
		$this->SetFont('');
		$this->SetFont('','B','9');
		$this->Cell(0,7,'The following voted for '.$position.' during SAKU elections');
		
		
    // Data
		
	require 'Classes/_function_classes.php';

		
		$this->Cell(0,8);
		$this->Ln();
		
        $this->Cell(15,7,'',1);
        $this->Cell(20,7,'TIME',1);
		$this->Cell(30,7,'REG NO',1);
		$this->Cell(55,7,'NAME',1);
		$this->Cell(25,7,'COMPUTER',1);
		$this->Cell(25,7,'ACTIVED BY',1);
		$this->Cell(17,7,'VOTES',1);
		$this->Ln();
		
		$i=1;
		$votes=array();
        $count=odbc_prepare($con,"SELECT DISTINCT v.student_reg,s.name,v.time_voted,
								v.date_voted,v.host_ip_address,
								cast(v.time_voted as time) as tt, s.password_issued_by,count(v.student_reg) as total_positions_voted
								FROM votes v
								INNER JOIN Students s on s.reg_no=v.student_reg and s.election_period=v.election_period
								INNER JOIN candidates c on c.reg_no=v.reg_no and c.election_period=v.election_period
								WHERE v.election_period=? and c.position_code=? and s.campus_code=?
								GROUP BY student_reg,s.name,v.time_voted,v.date_voted,v.host_ip_address,s.password_issued_by
								ORDER BY tt ASC");
					odbc_execute($count,array($election_period,$position_code,$campus));
		while($row=odbc_fetch_array($count))
		{
		
			//$votes[]=$row['total_votes'];
		
	 		//$this->Cell(5);
			$this->SetFont('','','8');
			
    		$this->Cell(15,6,$i++,1);
            $this->Cell(20,6,$row['time_voted'],1);
			 $this->Cell(30,6,$row['student_reg'],1);
			 $this->Cell(55,6,$row['name'],1);
			 $this->Cell(25,6,$row['host_ip_address'],1);
			 $this->Cell(25,6,$row['password_issued_by'],1);
			 $this->Cell(17,6,$row['total_positions_voted'],1);
			 $this->Ln();
		}
		//$this->Cell(0,6,number_format(array_sum($votes),0),1,0,'R');
		//unset($votes);
		$this->Ln();

 		 $this->Ln(15);
 /*---------------------------------------------------------------------------------------------*/
 //$this->SetY(-50);

$this->MultiCell(0,5,'Voter turnout analysis of KCAU SAKU '.$election_period.' Election 
Name :...................................................................................................................
Sign :...................................................Date :..........................................................
			                      (IECK CHAIRPERSON)');
 
 /*--------------------------------------------------------------------------------------------*/ 

}

}
if(isset($_GET['election_period']) and isset($_SESSION['campus']))
{
$election_period=$_GET['election_period'];
$position_code=$_GET['position_code'];
$position=$_GET['position'];
$campus=$_SESSION['campus'];
$pdf = new PDF();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','',7);

$pdf->SetDisplayMode('real','default');

$pdf->SetTitle('KCAU SAKU ELECTION TURNOUT  '.$election_period);
$pdf->SetKeywords('Contact: 0727951765  0704078230 or Email: petnjau@gmail.com  petnjau@yahoo.com');
$pdf->SetSubject('KCAU SAKU ELECTION TURNOUT ANLYSIS  '.$election_period);
$pdf->SetAuthor('MAKENZI NZAU PETER');
$pdf->AddPage();
$pdf->BasicTable($election_period,$position_code,$position,$campus);

$pdf->Output('SAKU ELECTION '.$election_period.' VOTERS LIST.pdf','D');
}
?>