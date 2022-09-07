<?php

date_default_timezone_set('Africa/Nairobi');



function top_links(){
echo ' <div class="navbar-header" style="color:white;">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
				<a class="navbar-brand" href="index.php" style="color:white"><strong>KCAU Evoting System </strong></a>
				';
              //   brand_name(); 
          echo ' </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" id="some_color" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                   
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a id="some_color" class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                   
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" id="some_color" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a id="some_color" class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>';
						if(isset($_SESSION['name'])){echo $_SESSION['name']; }
						echo '  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="user_profile.php"><i class="fa fa-user fa-fw"></i> My Profile</a>
                        </li>
                        
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->';	
	
}


top_links();

function admin_links(){
	if(isset($_SESSION['xyz']))
	{
	
	echo '<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li  class="sidebar-search">
						
                            <div class="input-group custom-search-form">
                                <img src="images/kca.png" class="img-responsive" style="width:130px; height:130px">
                            </div>
                            <!-- /input-group -->
                        </li>
	

	
	<li><a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
	
	<li><a href="#"><i class="fa fa-list-alt"></i> Voters Register Management<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
		<li><a href="_upload_voters_for_all_programs.php"><i class="fa fa-upload fa-fw"></i> Upload Voters (Excel)</a></li>
		
		<li><a href="added_students.php"><i class="fa fa-plus  fa-fw"></i>Register New Student</a></li>
		<li><a href="show_elections_register.php"><i class="fa fa-download  fa-fw"></i>Donwload Voters Register</a></li>
		</ul>
	</li>
	<li><a href="create_elections.php"><i class="fa  fa-calendar-o fa-fw"></i> Elections Configuratoins</a></li>
	<li><a href="#"><i class="fa  fa-bar-chart-o"></i> Election Results<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
		<li><a href="show_elections.php"><i class="fa fa-list-ol fa-fw"></i> Election  Results </a></li>
        <li><a href="show_elections.php"><i class="fa fa-cubes fa-fw"></i> Voter Turnout Analysis</a></li>
        <li><a href="turnout_stream.php"><i class="fa fa-desktop fa-fw"></i> Stream Voter Turnout</a></li>
		</ul>
	</li>
	<li><a href="show_candidates_elections.php"><i class="fa fa-users fa-fw"></i> Candidates</a></li>
	<li><a href="positions_setting.php"><i class="fa  fa-folder fa-fw"></i> SAKU Positions</a></li>
	<li><a href="#"><i class="glyphicon glyphicon-wrench"></i> School Setup<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
		<li><a href="faculties_and_departments.php"><i class="fa fa-briefcase fa-fw"></i>Faculties &amp; Departments </a></li>
		<li><a href="courses.php"><i class="fa fa-graduation-cap  fa-fw"></i> Programmes</a></li>
		</ul>
	</li>
	<li><a href="voters_password.php"><i class="fa  fa-lock"></i> Issue Voters Password</a></li>
    <li><a href="#"><i class="fa fa-globe"></i> Network Config<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
        <li><a href="blocked_ips.php"><i class="fa fa-remove"></i> IP Filtering</a></li>
	
		
		</ul>
	</li>
	<li><a href="#"><i class="fa fa-user"></i> Users<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
        <li><a href="user_profile.php"><i class="fa fa-file"></i> My Profile</a></li>
		<li><a href="user_accounts.php"><i class="fa fa-key fa-fw"></i> User Accounts</a></li>
		<li><a href="_user_performance.php"><i class="fa fa-bar-chart-o  fa-fw"></i> User Performance</a></li>
		<li><a href="user_activities_log.php"><i class="fa fa-book  fa-fw"></i> User Account Activities</a></li>
		
		</ul>
	</li>
	</ul>
	</div>
	<!-- /.sidebar-collapse -->
	</div>';
			
	}
	
		
		
		
}
function ieck_chairperson_links(){
	if(isset($_SESSION['xyz']))
	{
	
	echo '<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li  class="sidebar-search">
						
                            <div class="input-group custom-search-form">
                                <img src="images/kca.png" class="img-responsive" style="width:130px; height:130px">
                            </div>
                            <!-- /input-group -->
                        </li>
	

	
	<li><a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
	
	<li><a href="#"><i class="fa fa-list-alt"></i> Voters Register Management<span class="fa arrow"></span></a> 
    <ul class="nav nav-second-level">
		<li><a href="added_students.php"><i class="fa fa-plus  fa-fw"></i>Register New Student</a></li>
		<li><a href="show_elections_register.php"><i class="fa fa-download  fa-fw"></i>Donwload Voters Register</a></li>
		</ul>
	</li>
	
	<li><a href="#"><i class="fa  fa-bar-chart-o"></i> Election Results<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
		<li><a href="show_elections.php"><i class="fa fa-list-ol fa-fw"></i> Election  Results </a></li>
        <li><a href="show_elections.php"><i class="fa fa-cubes fa-fw"></i> Voter Turnout Analysis</a></li>
        <li><a href="turnout_stream.php"><i class="fa fa-desktop fa-fw"></i> Stream Voter Turnout</a></li>
		</ul>
	</li>
	
	<li><a href="voters_password.php"><i class="fa  fa-lock"></i> Issue Voters Password</a></li>
	
	</ul>
	</div>
	<!-- /.sidebar-collapse -->
	</div>';
			
	}
	
		
		
		
}
function  _user_links(){
	if(isset($_SESSION['xyz']))
	{
	
	echo '<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li  class="sidebar-search">
						 
                            <div  class="input-group custom-search-form">
							  <img src="images/kca.png" class="img-responsive" style="width:130px; height:130px">
                            </div>
                            <!-- /input-group -->
                        </li>
	

	
	<li><a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
	
	<li><a href="voters_password.php"><i class="fa  fa-lock"></i> Issue Voters Password</a></li>
	
	</ul>
	</div>
	<!-- /.sidebar-collapse -->
	</div>';
			
	}
	
		
		
		
}


function  students_links(){
	if(isset($_SESSION['xyz']))
	{
	
	echo '<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li  class="sidebar-search">
						 
                            <div  class="input-group custom-search-form">
							  <img src="images/kca.png" class="img-responsive" style="width:130px; height:130px">
                            </div>
                            <!-- /input-group -->
                        </li>
	

	
	<li>
    <a href="voters_ballot.php"><i class="fa fa-dashboard fa-fw"></i> View My Ballot</a></li>
	<li>
    <a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Log Out</a></li>
	
	</ul>
	</div>
	<!-- /.sidebar-collapse -->
	</div>';
			
	}		
}
function  livestream_links(){
	if(isset($_SESSION['xyz']))
	{
	
	echo '<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li  class="sidebar-search">
						 
                            <div  class="input-group custom-search-form">
							  <img src="images/kca.png" class="img-responsive" style="width:130px; height:130px">
                            </div>
                            <!-- /input-group -->
                        </li>
	

	
                        <li><a href="#"><i class="fa  fa-bar-chart-o"></i> Election Results<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                       
                        <li><a href="turnout_stream.php"><i class="fa fa-desktop fa-fw"></i> Stream Voter Turnout</a></li>
                        </ul>
                    </li>
	
	</ul>
	</div>
	<!-- /.sidebar-collapse -->
	</div>';
			
	}
	
		
		
		
}

if(isset($_SESSION['xyz']))
  {
	  
              $user_array=array("Super Admin","admin","Lower Admin");
              
			  if(in_array($_SESSION['xyz'],$user_array))
			  {
				 admin_links();
              }
              elseif($_SESSION['xyz']=='IECK Chairperson')
              {
                ieck_chairperson_links();
              }
              elseif($_SESSION['xyz']=='livestream')
              {
                livestream_links();

              }
              elseif($_SESSION['xyz']=='Student')
              {
                students_links();

              }
			  else{
				   _user_links();
				  }
  }
  else
  {
	  header('location:logout.php');
}




?>