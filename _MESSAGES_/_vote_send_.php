<?php
session_start();

if(!isset($_SESSION['voter_info']))
{
	header("location:../login.php");
}
session_destroy();
?>
<html>
<head>
<title>Logging out.......</title>
</head>
<body style="background:url(../images/bg.png) repeat-x;">
<div style="height:450px; text-align:center; 
 padding:10px; margin-left:200px;
  width:800px; border:solid 1px;; border-radius:10px;">
<script language="javascript">

var time_left = 5;
var cinterval;

function time_dec(){
  time_left--;
  document.getElementById('countdown').innerHTML = time_left;
  if(time_left == 0){
	 window.location="../logout.php";
	 
    //clearInterval(cinterval);
  }
}

cinterval = setInterval('time_dec()', 1000);

</script>
<img src="../images/vote_count.jpg" width="164" height="105">
<?php
unset($_SESSION['nm']);
echo '<h3>'."You have sucessfully voted...Thank you for voting, your vote has counted".'</h3>';
echo '<a href="../logout.php">'."logging out in ".
'<span style="font-size:18px;" id="countdown">5</span>'." seconds".'</a>';
////sleep(10);
//header("location:../logout.php");

?>


</div>
</div>
</body>
</html>