function printResults()
{
	
	var content=document.getElementById('mt').innerHTML;
 	var pwin=window.open('','print_content',',width=800,height=900,toolbar=0');

	pwin.document.open();
	pwin.document.write('<html><link href="../style.css" rel="stylesheet" type="text/css">.<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px; text-align:center; background-color:#FFF;" onload="window.print()">'+content+'</body></html>');
	pwin.document.close();
 
	setTimeout(function(){pwin.close();},10000);
	
	return true;
}