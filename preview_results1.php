<?php
ob_start();
session_start();
include '_SESSIONS_/_sessions.php';
admin_ieck_chair();
include 'Classes/_function_classes.php';
error_reporting(0);
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
* {box-sizing: border-box;}
body {font-family: Verdana, sans-serif;}
.mySlides {display: none;}
img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active {
  background-color: #717171;
}

/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}

@-webkit-keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .text {font-size: 11px}
}
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

  <!--end modals here -->



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">ELECTION RESULTS</h3>
                     <!-- Slideshow container -->
           <div class="slideshow-container">
					 <?php
					
if(isset($_GET['results']) and isset($_SESSION['campus']) )
{

  $dot=0;
	 $election_period=htmlspecialchars(trim($_GET['election_period']),ENT_QUOTES);
     $campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);
   
	         

			$position_query=odbc_prepare($con,"SELECT  c.position_code,p.description ,
											count(c.position_code) as total
											FROM candidates c
											INNER JOIN [position] p on p.position_code=c.position_code
											WHERE c.election_period=? AND c.campus=?
											GROUP BY c.position_code,p.description,p.priority_rating
											ORDER BY p.priority_rating asc");

	if(odbc_execute($position_query,array($election_period,$campus)))
	 {
		while($position_row=odbc_fetch_array($position_query))
		{
      $dot.='<span class="dot"></span>';
		$position_code=htmlspecialchars($position_row['position_code'],ENT_QUOTES);

		
		$i=1;
		$votes=array();
	$query=odbc_prepare($con,"SELECT c.reg_no,c.name,c.image,count(v.reg_no) as total_votes 
						FROM candidates c 
						LEFT JOIN votes v on c.reg_no=v.reg_no AND v.election_period=c.election_period 
						WHERE c.position_code=? AND c.election_period=? and c.campus=?
						GROUP BY c.reg_no,c.name,c.image 
						ORDER BY count(v.reg_no) DESC");
		odbc_execute($query,array($position_code,$election_period,$campus));				
						
		echo '
    <div class="mySlides fade">
    
    <table class="table table-bordered" width="60%">'.
		'<tr><th align="center"  colspan="5"> KCAU SAKU '.$election_period.' ELECTION RESULTS ('.$_SESSION['campus_name'].') </th></tr>
		<tr>
		<td colspan="5" id="bold_row">POSITION:  '.strtoupper($position_row['description']).'</td></tr>'.
		'<tr >
		<td>#</td>
		<td></td>
		<td id="bold_row">Reg No</td>
		<td id="bold_row">Candidate Name</td><td id="bold_row" style="text-align:right;">Total Votes</td></tr>';
		while($row=odbc_fetch_array($query))
		{
			echo '<tr>
			<td>'.$i++.'</td>
			<td width="115">
			<img src="Candidates/'.$election_period.'/'.$row['image'].'" height="115" width="115">
			<td>'.$row['reg_no'].
			'</td><td>'.$row['name'].
			'</td><td style="text-align:right;">'.number_format($row['total_votes'],0).'</td></tr>';
		
			$votes[]=$row['total_votes'];
		}
		
	echo '<tr>
  <td colspan="5" id="bold_row" style="text-align:right;">'.number_format(array_sum($votes),0).'</td></tr>
		<tr><td colspan="3">
		<a id="link_button" href="show_elections.php" >Go Back</a>
		</td><td></td><td>
		<a id="link_button" href="file_download_position_results.php?position_code='.$position_code.'& election_period='.
		$election_period.'&position='.$position_row['description'].'& photo=yes">Download Results</a></td></tr>
    </table>
    </div>';	
		}
   }
}


?>   
<div style="text-align:center">
<span class="dot"></span>
<span class="dot"></span>
<span class="dot"></span>
<span class="dot"></span>
<span class="dot"></span>
  <?php //echo $dot ;?>
</div>
</div>     
                    
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
var slideIndex = 0;
showSlides();

function showSlides() {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides, 5000); // Change image every 2 seconds
}
</script>
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
