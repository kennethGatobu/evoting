<?php


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Africa/Nairobi');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');



if(isset($_GET['course_code']))
{
$course_code=htmlspecialchars($_GET['course_code'],ENT_QUOTES);
$election_period=htmlspecialchars($_GET['election_period'],ENT_QUOTES);
$course=htmlspecialchars($_GET['course'],ENT_QUOTES);
/** Include PHPExcel */
require_once '/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties

$objPHPExcel->getProperties()->setCreator("PETER MAKENZI")
							 ->setLastModifiedBy("PETER MAKENZI")
							 ->setTitle('KCAU SAKU '.$election_period.' VOTERS REGISTER')
							 ->setSubject($election_period)
							 ->setDescription("Contact: 0727951765,0704078230 Email:petnjau@gmail.com ")
							 ->setKeywords("KCAU SAKU DOCUMENT")
							 ->setCategory("KCAU SAKU DOCUMENT");


// Add some data
$objPHPExcel->SetActiveSheetIndex(0)
			->setCellValue('A1','Course:')
			->setCellValue('B1',$course)
			->setCellValue('A2','Course Code:')
			->setCellValue('B2',$course_code)
			->setCellValue('A3','Election Period:')
			->setCellValue('B3',$election_period)
			->setCellValue('A6','S/NO')
			->setCellValue('B6','Reg NO')
			->setCellValue('C6','Student Name');
			


			
			
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
//$objPHPExcel->getActiveSheet()->getColumnDimension('A:C')->setAutoSize(true);
//$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('B1:B6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1:C6')->getFont()->setBold(true);

// Rename worksheet

$objPHPExcel->getActiveSheet()->setTitle('STUDENTS');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$course_code.' .xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

}
?>
