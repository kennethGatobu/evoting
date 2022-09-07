<?php
ob_start();
session_start();
require( 'fpdf/fpdf.php' );
error_reporting(0);
//require_once 'vendor/autoload.php';
date_default_timezone_set('Africa/Nairobi');

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
	
    
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
$this->SetY(-20);
	
 $this->SetFontSize(8);

    // Arial italic 8
    $this->SetFont('Arial','I',8);
	$this->Cell(0,4,'Printed on '.date('D, F j, Y, g:i a'),0,0,'R');
	$this->Ln();
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C');
}

// Simple table
function BasicTable($election_period,$position_code,$position,$photo,$campus)
{
    
		$this->SetFont('Arial','B',13);
		$this->Cell(0,7,$election_period.' ELECTION RESULTS ('.$_SESSION['campus_name'].')',0,0,'C');
		$this->Ln();
	 	/*-----------table--------------------------------*/
		//$this->Cell(5);
		
    // Data
		$this->SetFont('');
		require 'Classes/_function_classes.php';	
		$this->SetFont('','B','9');
		$this->Cell(0,8);
		$this->Ln();
		$this->Cell(0,6,'POSITION: '.strtoupper($position),0,0,'C');
		$this->Ln();
	
		
        $this->Cell(10,7,'',1);
		if($photo=='yes')
		{
		$this->Cell(30,7,'PHOTO',1);
		}
        $this->Cell(46,7,'REG NO',1);
		if($photo=='no')
		{
		$this->Cell(102,7,'FULL NAME',1);
		}
		else{
		$this->Cell(72,7,'FULL NAME',1);

		}
		$this->Cell(32,7,'TOTAL VOTES',1,0,'R');
		$this->Ln();
		
		$i=1;
		$votes=array();
$new=odbc_prepare($con,"SELECT c.reg_no,c.name,c.image,
						count(v.reg_no) as total_votes 
						FROM candidates c 
						LEFT JOIN votes v on c.reg_no=v.reg_no AND v.election_period=c.election_period
						WHERE c.position_code=? AND c.election_period=? and c.campus=?  
						GROUP BY c.reg_no,c.name,c.image 
						ORDER BY count(v.reg_no) DESC");
		odbc_execute($new,array($position_code,$election_period,$campus));
		while($row=odbc_fetch_array($new))
		{
		
			$votes[]=$row['total_votes'];
		
	 		//$this->Cell(5);
			 $this->SetFont('','','8');
			if($photo=='yes')
			{
    		 $this->Cell(10,29,$i++,1);
			 $this->Cell(30,29,$this->Image('Candidates/'.$election_period.'/'.$row['image'],$this->GetX(),$this->GetY(),30,29),1);
			 $this->Cell(46,29,$row['reg_no'],1);
			 $this->Cell(72,29,$row['name'],1);
			 $this->Cell(32,29,number_format($row['total_votes'],0),1,0,'R');
			 $this->Ln();
			}
			else
			{
			$this->Cell(10,6,$i++,1);
			// $this->Cell(30,29,$this->Image('photos/'.$row['image'],$this->GetX(),$this->GetY(),30,29),1);
			 $this->Cell(46,6,$row['reg_no'],1);
			 $this->Cell(102,6,$row['name'],1);
			 $this->Cell(32,6,number_format($row['total_votes'],0),1,0,'R');
			 $this->Ln();
			}
		}
		$this->Cell(0,6,number_format(array_sum($votes),0),1,0,'R');
		unset($votes);
		$this->Ln();
  	
 		 $this->Ln(15);
 /*---------------------------------------------------------------------------------------------*/
 //$this->SetY(-50);

$this->MultiCell(0,5,'Official results of KCAU SAKU '.$election_period.' Election 
Name :...................................................................................................................
Sign :...................................................Date :..........................................................
			                      (IECK CHAIRPERSON)');
 
 
 /*--------------------------------------------------------------------------------------------*/ 

}



}
if(isset($_GET['election_period']) and isset($_SESSION['campus']))
{
$election_period=htmlspecialchars($_GET['election_period'],ENT_QUOTES);
$position_code=htmlspecialchars($_GET['position_code'],ENT_QUOTES);
$position=htmlspecialchars($_GET['position'],ENT_QUOTES);
$campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);
$photo=$_GET['photo'];
$pdf = new PDF();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','',7);
//$pdf->SetFont('times','','7');
$pdf->SetDisplayMode('real','default');


$pdf->AddPage();
$pdf->SetSubject('KCAU SAKU ELECTION RESULTS '.$election_period);
$pdf->SetAuthor('PETER N. MAKENZI');

$pdf->BasicTable($election_period,$position_code,$position,$photo,$campus);

$pdf->Output('KCAU SAKU ELECTION RESULTS '.$election_period.'.pdf','D');
}
?>