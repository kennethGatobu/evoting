<?php
require( 'fpdf/fpdf.php' );


date_default_timezone_set('Africa/Nairobi');
class PDF extends FPDF
{

function Header()
{

    
	$this->SetTextColor(0,80,180);
   	$img= $this->Image('images/kca.jpg',90,$this->GetY(),30,30);
    // Arial bold 15
		
   
    $this->Cell(30,30,$img,0,'C');
	$this->SetFont('Arial','B',13);
	
	$this->Ln();
	$this->Cell(0,7,'STUDENTS ASSOCIATION OF KCA UNIVERSITY (SAKU)',0,0,'C');
	$this->Ln();
  
	$this->Cell(0,7,$_GET['election_period'].' ELECTION VOTER REGISTER',0,0,'C');
	$this->Ln();
	  $this->SetFont('Arial','B',11);
    $this->SetTextColor(250,0,0);
		$this->Cell(0,7,'COURSE: '.$_GET['course'],0,0,'C');
		$this->Ln();
		$this->Cell(0,3);
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
function BasicTable($course_code,$election_period)
{
    
		
		
		
	 	/*-----------table--------------------------------*/
		//$this->Cell(5);
		$this->SetFont('','B','8');
        $this->Cell(20,7,'#',1);
        $this->Cell(46,7,'REG NO',1);
		$this->Cell(92,7,'FULL NAME',1);
		$this->Cell(32,7,'SIGN',1);
		$this->Ln();
    // Data
			

	$this->SetFont('');
	include'config/db_con.php';
//	$con=odbc_connect('evoting','','');
	$result=odbc_exec ($con,"Select reg_no,name from Students where course_code='$course_code' and election_period='$election_period' order by reg_no asc" );
		$i=1;
		 while ($row =odbc_fetch_array( $result))
 		{
	 		//$this->Cell(5);
    		$this->Cell(20,6,$i++,1);
            $this->Cell(46,6,$row['reg_no'],1);
			 $this->Cell(92,6,$row['name'],1);
			 $this->Cell(32,6,'',1);
			 $this->Ln();
  		}
 		 $this->Ln(15);
 /*---------------------------------------------------------------------------------------------*/
 //$this->SetY(-50);

$this->MultiCell(0,5,'Voter register of KCAU SAKU '.$election_period.' Election 
Name :...................................................................................................................
Sign :...................................................Date :..........................................................
			                      (IECK CHAIRPERSON)');
 
 /*--------------------------------------------------------------------------------------------*/ 
 
 
 /*--------------------------------------------------------------------------------------------*/ 

}

}
if(isset($_GET['course_code']))
{
$course_code=$_GET['course_code'];
$election_period=$_GET['election_period'];
$pdf = new PDF();
$pdf->AliasNbPages();
// Column headings
//$header = array('Regno', 'name');
// Data loading
$pdf->SetFont('Arial','',7);
//$pdf->SetFont('times','','7');
$pdf->SetDisplayMode('real','default');

//$pdf->AddFont('Comic','','comic.php');$pdf->SetTitle('KCAU SAKU ELECTION TURNOUT  '.$election_period);
$pdf->SetKeywords('Contact: 0727951765  0704078230 or Email: petnjau@gmail.com  petnjau@yahoo.com');
$pdf->SetSubject('KCAU SAKU ELECTION REGISTER  '.$election_period);
$pdf->SetAuthor('MAKENZI NZAU PETER');
$pdf->AddPage();
$pdf->BasicTable($course_code,$election_period);

$pdf->Output($course_code.'.pdf','D');
}
?>