<?php
session_start();
include '_function_classes.php';
 //----------------------------------------------------------
 if(isset($_POST['turnout'])){

    if(isset($_SESSION['election_period']) and isset($_SESSION['campus']))
{

		
			$election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
                        $campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);
		$total_votes_qry=odbc_prepare($con,"SELECT COUNT( DISTINCT v.student_reg ) as total_voters
                                                from votes v
                                                inner join Students s on s.reg_no=v.student_reg and
                                                s.election_period=v.election_period and s.campus_code=?
                                                WHERE v.election_period=? ");
                odbc_execute($total_votes_qry,array($campus,$election_period));
				
					$votes_row=odbc_fetch_array($total_votes_qry);
	$registered_voter_qry=odbc_prepare($con,"SELECT COUNT(DISTINCT reg_no) as total_registered_voters 
                                        FROM Students 
                                        where election_period=? and campus_code=?");
                        odbc_execute($registered_voter_qry,array($election_period,$campus));      
		$total_voters=odbc_fetch_array($registered_voter_qry);
				  $percentage=0;
				  if($votes_row['total_voters']>0)
				  {
					  $percentage=$votes_row['total_voters']/$total_voters['total_registered_voters'] *100;
				  }
					echo number_format($votes_row['total_voters'],0).'<br>
					
					'.number_format($percentage,1).'%';

			
				
				}
 }


 //------------------------------------------------
 if(isset($_POST['hourly_turnout']))
 {

        
        if(isset($_SESSION['election_period']) and isset($_SESSION['campus']))
        {
        $election_period=htmlspecialchars($_SESSION['election_period'],ENT_QUOTES);
        $campus=htmlspecialchars($_SESSION['campus'],ENT_QUOTES);        

        $i=1;
        $votes=array();
        $query=odbc_prepare($con,"SELECT   time_line= DATEPART(HOUR, cast(v.time_voted as TIME)),
                COUNT( DISTINCT v.student_reg ) as total_voters
                from votes v
                inner join Students s on s.reg_no=v.student_reg and s.election_period=v.election_period
                and s.campus_code=?
                WHERE v.election_period=? 
                GROUP BY DATEPART(HOUR, (cast(v.time_voted as TIME)))
                ORDER BY DATEPART(HOUR, cast(v.time_voted as TIME)) ASC");
         odbc_execute($query,array($campus,$election_period));
        echo '<table class="table table-condensed table-bordered table-striped" style="font-size:13px">'.

        '<tr>
        <th colspan="3" id="bold_row" style="background-color:black; font-size:18px; color:white">
        Hourly voter turnout analysis</th></tr>'.
        '<tr ><td>#</td><th>Time Duration</th>
        <th style="text-align:right;">No. of voters</th>
        </tr>';

        while($row=odbc_fetch_array($query))
        {
        $start_time=$row['time_line'].':00:00';
        $end_time=$row['time_line'].':59:59';

        echo '<tr><td>'.$i++.'.</td>
        <td>'.date('h:i:s A',strtotime($start_time)).' &nbsp;&nbsp;&nbsp; to &nbsp;&nbsp;&nbsp;    '
        .date('h:i:s A',strtotime($end_time)).

        '</td><td style="text-align:right;">'.number_format($row['total_voters'],0).'</td></tr>';

        $votes[]=$row['total_voters'];
        }

        echo '<tr style="background-color:black; color:white">
        <th colspan="2" class="text-right"><strong>Total:</strong></th>
        <th  id="bold_row" style="text-align:right;">
        <strong>'.number_format(array_sum($votes),0).'</strong></th></tr>
        <tr>
        <td colspan="2">
        </td><td>
        <a id="link_button" href="file_download_list_voter_turnout_patterns.php?election_period='.$election_period.'">
        Download Results</a>
        </td>
        </tr></table>';
        }
}

?>