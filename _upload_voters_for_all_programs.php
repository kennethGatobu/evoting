<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

   <?php include 'functions/page_title.php'; ?>


     <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
#some_color{color:#FFFBF0;}
/*
ul.navbar-top-links li a, ul.navbar-top-links li a:visited {
    color:#FFF !important;
}

ul.navbar-top-links li a:hover, ul.navbar-top-links li a:active {
    color:#2A0000 !important;
}

ul.navbar-top-links li.active a {
    color:#2A0000 !important;
}
*/
</style>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" 
        style="margin-bottom: 0; background:#2F637A; color:#FFF; border-top:solid 13pt khaki;" >
           

          
		   
		   
		    <?php
   			 include 'Classes/new_links.php';
    
		     ?>
            <!-- /.navbar-static-side -->
        </nav>
 
<div id="upload_voters" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="upload_voters">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="fa fa-upload"></span> Upload Voters List</h4>
        </div>
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="modal-body" id="load_text">
        <div class="form-group">
         <label> Select File:</label>
       	 <input type="file" name="spreadsheet" class="form-control" />
        </div>
        
        </div>
        <div class="modal-footer">
        <input type="submit" name="submit" value="Upload" class="btn btn-primary" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
   </div>
  </div>

  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Upload Voters List</h3>
	<p class="text-right">
      <a href="" class="btn btn-success" data-toggle="modal" data-target="#upload_voters">
      <i class="fa fa-upload"></i> Click here to upload Voters List</a>
    </p>
    <p class="alert alert-warning">
        <strong>*Important Instruction on file Uploading:</strong>
        <br>1) Only upload an Excel  (.xlsx) file format <br>
        2) The file Must have the following column layout
    </p>
    <table class="table table-bordered table-bordered table-striped table-hover table-condensed" 
 title="Click Save Register to submit" style="font-size:13px;">
 <thead>
<tr>
 <th>Reg No</th>
 <th>Student Name</th>
 <th>Gender</th>
 <th>Course Code</th>
 <th>Campus</th>
 <th>Email</th>
 <th>Election Period</th>
 </tr>
</thead>
<tbody>
<tr>
    <td></td>
    <td>The first record to be on the second row</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
</tbody>
</table>
 <?php
include 'config/db_con.php';

//----save the voters--------------------

if(isset($_POST['save']))
{
  
 $x=0;
 $y=0;
 $failed_students="";
  foreach($_SESSION['wb'] as $val )	
  {
		try{
		  $reg_no=$val['reg_no'];
		  $full_name=$val['full_name'];
		  $gender=$val['gender'];
		  $course=$val['course'];
          $campus=$val['campus'];
          $email=$val['email'];
		  $election_period=$val['election_period'];
	     $query_text=odbc_prepare($con,"INSERT INTO Students 
                        (reg_no,name,course_code,election_period,campus_code,gender,email)
		                        VALUES(?,?,?,?,?,?,?)");
		$query_parameter=array($reg_no,$full_name,$course,$election_period,$campus,$gender,$email);	
		
		if($query=odbc_execute($query_text,$query_parameter))
		{
			$x++;
		}
		else{
			 $y++;
			 $failed_students.=$reg_no.' ,';
			
			}	  
		}
		catch(Exception $e)
		{
			echo 'error';
		}
		/**/

}
 echo '<div class="alert alert-success">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Success!</strong><strong> '.$x.' </strong>student(s) have been saved, 
			  while<strong> '.$y.' {'.$failed_students.'}</strong> have failed to save</div>';
	 
	 
	 
	 
}


//---------------------------------------------------


$worksheet_record=array();
if(isset($_POST['submit']))
{
  
  function getExtension($str)
  {
	  $i=strrpos($str,".");
	  if(!$i){return "";}
	  $l=strlen($str)-$i;
	  $ext=substr($str,$i+1,$l);
	  return $ext;
  }
  
  $filename=stripslashes($_FILES['spreadsheet']['name']);
		  //get the extension of the image in lower case
  $extension=getExtension($filename);
  $extension=strtolower($extension);
  if ($extension !="xlsx")
  {
	  echo '<br><br><p style="color:red; font-size:13px;">Error Unknown file format, please select (.xlsx) file</p>';
  }
  else
  { 

require 'vendor/autoload.php';
		
$inputFileName = $_FILES['spreadsheet']['tmp_name'];
$_SESSION['workbook']= $inputFileName;

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
		$reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load($inputFileName);
foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
  $worksheetTitle     = $worksheet->getTitle();
  $highestRow         = $worksheet->getHighestRow(); // e.g. 10
  $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
  //$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
  $nrColumns = ord($highestColumn) - 64;
  echo "<br>The worksheet ".$worksheetTitle." has ";
  echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
  echo ' and ' . $highestRow . ' row.<br>';
  $i=1;
  
 echo '
 <form method="post">
 <table class="table table-bordered table-bordered table-striped table-hover table-condensed" 
 title="Click Save Register to submit" style="font-size:13px;">'.

 '<tr>
 <th></th>
 <th>Reg No</th>
 <th>Student Name</th>
 <th>Gender</th>
 <th>Program</th>
 <th>Campus</th>
 <th>Email</th>
 <th>Election Period</th>
 
 </tr>';
 
  for ($row = 2; $row <= $highestRow; ++ $row) {
 
  
	$reg=$worksheet->getCell('A'.$row)->getValue();
	$full_name=$worksheet->getCell('B'.$row)->getValue();
	$gender=$worksheet->getCell('C'.$row)->getValue();
	$course=$worksheet->getCell('D'.$row)->getValue();
    $campus=$worksheet->getCell('E'.$row)->getValue();
    $email=$worksheet->getCell('F'.$row)->getValue();
	$election_period=$worksheet->getCell('G'.$row)->getValue();


	$worksheet_record[]=array("reg_no"=>$reg,"full_name"=>$full_name,"gender"=>$gender,"course"=>$course,'campus'=>$campus,'email'=>$email, "election_period"=>$election_period);
	   
	echo '<tr>
	<td>'.$i++.'.</td>
	<td>'.$reg.'</td>
	<td>'.ucwords(strtolower($full_name)).'</td>
	<td>'.ucwords(strtolower($gender)).'</td>
	<td>'.$course.'</td>
    <td>'.$campus.'</td>
    <td>'.$email.'</td>
	<td>'.$election_period.'</td>
	
	</tr>';
		  
  }
  $_SESSION['wb']=$worksheet_record;
  echo '<tr><td class="text-right" colspan="8">'.
  '<input type="submit" name="save" value="Save Register" class="btn btn-primary" /></td></tr>'.
  '</table>
  </form>';

}

}
}


?>
                                        
                    
             </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="bower_components/bootstrap/tinymce/tinymce.min.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
		
//---------------------------------------------------


		
	});
</script>
</body>

</html>
