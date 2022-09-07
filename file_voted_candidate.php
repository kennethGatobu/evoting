<?php
session_start();
require( 'fpdf/fpdf.php' );
date_default_timezone_set('Africa/Nairobi');
		include'config/db_con.php';

include'functions/current_election.php';

class PDF extends FPDF
{
	

function Header()
{
	
	//$this->SetFont('Arial','B',10);
	//$this->Cell(0,6,'MKU/ACAD/R011',0,0,'R');
	//$this->Ln();
	
    
	$this->SetTextColor(0,80,180);
   	$img= $this->Image('images/kca.jpg',90,$this->GetY(),30,30);
    // Arial bold 15
		
   
    $this->Cell(30,30,$img,0,'C');
	$this->SetFont('Arial','B',12);
	
	$this->Ln();
	$this->Cell(0,7,'STUDENTS ASSOCIATION OF KCA UNIVERSITY (SAKU)',0,0,'C');
	$this->Ln();
	$this->SetFont('Arial','B',11);
//	$this->Cell(0,7,$_GET['election_period'].' ELECTION',0,0,'C');
    
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
function BasicTable()
{
   
		
    // Data
		
		//$this->Ln();
		$this->SetFont('');
		include'config/db_con.php';
		include'functions/current_election.php';
		$this->SetFont('','B','10');
		$this->Cell(0,8);
		$this->Ln();
		
		//$this->Cell(0,6,'BALLOT PAPER : '.$position,0,0,'C');
		//$this->Ln();
		$this->Cell(0,10);
		$this->Ln();
		$this->SetFont('','B','9');

        $this->Cell(10,7,'',1);
        $this->Cell(26,7,'PHOTO',1);
		$this->Cell(45,7,'REG NO',1);
		$this->Cell(70,7,'NAME',1);
		$this->Cell(30,7,'REF NO',1);

		$this->Ln();
		
		$i=1;
		$votes=array();
		$reg=$_SESSION['voter_info'];

		$candidates=odbc_exec($con,"SELECT v.vote_id,c.reg_no,c.name,p.description,c.image,c.election_period,c.other_names
						FROM votes v 
						INNER JOIN candidates c ON c.reg_no=v.reg_no AND c.election_period=v.election_period
						INNER JOIN [position] p ON p.position_code=c.position_code
						WHERE v.student_reg='$reg' AND v.election_period='$election'
						ORDER BY p.priority_rating ASC");
		while($row=odbc_fetch_array($candidates))
		{
		
			//$this->Image('photos/'.$row['image'],6,10)
		
	 		//$this->Cell(5);
			$this->SetFont('','','8');
			$this->Cell(10,26,$i++,1);
            $this->Cell(26,26,$this->Image('Candidates/'.$election.'/'.$row['image'],$this->GetX(),$this->GetY(),26,26),1);
			 $this->Cell(45,26,$row['reg_no'],1);
			 $this->Cell(70,26,$row['name'],1);
			 $this->Cell(30,26,$row['vote_id'],1);

			 $this->Ln();
		}
		
		
  
 		 $this->Ln(20);
 /*---------------------------------------------------------------------------------------------*/

$this->SetFontSize(8);
/*$this->MultiCell(0,5,'Official copy of '.$position.' Ballot Paper  for SAKU '.$election_period.' Election 
Name :...................................................................................................................
Sign :...................................................Date :..........................................................
			                      (IECK CHAIRPERSON)',0,'R');*/
	
 /*--------------------------------------------------------------------------------------------*/ 

}

}

//if(isset($_GET['position_code']))
//{
//$election_period=$_GET['election_period'];
//$position_code=$_GET['position_code'];
//$position=$_GET['position'];
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',7);
$pdf->SetTopMargin(15);
$pdf->SetDisplayMode('real','default');

//$pdf->SetTopMargin(5);
//$pdf->SetTitle('KCAU SAKU ELECTIONS BALLOT PAPER  '.$election_period);
$pdf->SetKeywords('Contact: 0727951765  0704078230 or Email: petnjau@gmail.com  petnjau@yahoo.com');
//$pdf->SetSubject('KCAU SAKU ELECTIONS BALLOT PAPER  '.$election_period);
$pdf->SetAuthor('MAKENZI NZAU PETER');
$pdf->AddPage();
$pdf->BasicTable();

$pdf->Output();
//}
?>