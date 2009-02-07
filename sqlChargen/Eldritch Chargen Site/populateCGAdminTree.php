<?
	error_reporting(E_ALL);
	header("Cache-Control: no-cache");

	include 'CGAdminDB.php';

	$eldritchSQL = mysql_pconnect($hostname_eldritchSQL, $username_eldritchSQL, $password_eldritchSQL) 
					or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db($database_eldritchSQL) 
		or trigger_error(mysql_error(),E_USER_ERROR);
	$qQUAL = mysql_query("SELECT *  FROM CGDB_QD WHERE Type = 'QUAL' ORDER BY Long_Name ASC") 
				or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
	$qDRAW = mysql_query("SELECT *  FROM CGDB_QD WHERE Type = 'DRAW' ORDER BY Long_Name ASC")
				or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
		
	mysql_close($eldritchSQL);		
?>	
	
<li id='Qualities'><span class='text'>Qualities</span>
	<ul>
		<?
		print "\t\t<li id='AddNewQuality'><span class='text'>Add New Quality</span></li>\n";
        while($row=mysql_fetch_array($qQUAL))
        {
            print "\t\t<li id='".str_replace(" ", "", $row['Long_Name'])."'><span class='text'>".$row['Long_Name']."</span></li>\n";
        }
        ?>
	</ul>
</li>
<li id='Drawbacks'><span class='text'>Drawbacks</span>
	<ul>
		<?
		print "\t\t<li id='AddNewDrawback'><span class='text'>Add New Drawback</span></li>\n";
        while($row=mysql_fetch_array($qDRAW))
        {
            print "\t\t<li id='".str_replace(" ", "", $row['Long_Name'])."'><span class='text'>".$row['Long_Name']."</span></li>\n";
        }
        ?>
        
	</ul>
</li>
