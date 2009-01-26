<?
	$hostname_eldritchSQL = "localhost";
	$database_eldritchSQL = "eldritch_eldritch_eldritch";
	$username_eldritchSQL = "eldritch";
	$password_eldritchSQL = "c0b30121";
	$eldritchSQL = mysql_pconnect($hostname_eldritchSQL, $username_eldritchSQL, $password_eldritchSQL) 
					or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db($database_eldritchSQL) 
		or trigger_error(mysql_error(),E_USER_ERROR);
	$qQUAL = mysql_query("SELECT *  FROM `CGDB_QD` WHERE `Type` = 'QUAL'") 
				or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
	$qDRAW = mysql_query("SELECT *  FROM `CGDB_QD` WHERE `Type` = 'DRAW'")
				or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
	
	
	if (!isset($_POST['qdgroup']))
	{
		$viewingQual = TRUE;
	}
	else
	{
		if ($_POST['qdgroup'] == "rdoQual")
		{
			$viewingQual = TRUE;
		}
		else
		{
			$viewingQual = FALSE;
		}
	} 
	$currQD = !isset($_POST['QDList'])? NULL : $_POST['QDList']; 

    if ($_POST["process"] == 1) 
	{
		
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modify Qualities and Drawbacks</title>

<style>
    .basictext {
        font-family: Arial, Helvetica, sans-serif; 
        font-size: 14px; color:#000066;
    }
    .errortext {
        font-family: Arial, Helvetica, sans-serif; 
        font-size: 14px; color:#C00000; font-weight: bold;
    }
</style>
<body>
<form name="qdform" action="SetQD.php" method="post">
	<input type="radio" onclick="submit()" name="qdgroup" value="rdoQual" <? if ($viewingQual) print 'checked'; ?>> Qualities
	<input type="radio" onclick="submit()" name="qdgroup" value="rdoDraw" <? if (!$viewingQual) print 'checked'; ?>> Drawbacks
        
    <br><br>
    
    <label>
    Current <? if ($viewingQual) print "Qualities"; else print "Drawbacks" ?>: 
    <select name="QDList">
     	<? PopulateQDList($viewingQual, $qQUAL, $qDRAW, $currQD) ?>
    </select>
    </label>

	<br><br>
    
    <input type="hidden" name="process" value="1">
  	<input type="submit" name="btnSubmit" value="Submit">
    
</form>
</body>
</html>

<?
    mysql_close($eldritchSQL);

	function PopulateQDList($viewingQual, $qQUAL, $qDRAW, $currQD)
	{
		if ($viewingQual) 
		{
			$qQD = $qQUAL;
			if (mysql_num_rows($qQUAL) > 0)
			{
				$defaultSelection = "Select a Quality";
			}
			else
			{
				$defaultSelection = "No Qualities Available";
			}
		} 
		else 
		{	
			$qQD = $qDRAW;
			if (mysql_num_rows($qDRAW) > 0)
			{
				$defaultSelection = "Select a Drawback";
			}
			else
			{
				$defaultSelection = "No Drawbacks Available";
			}
		}
		
		if ($currQD == $defaultSelection)
		{
			print '<option value="'.$defaultSelection.'" SELECTED>'.$defaultSelection.'</option>';
		}
		else
		{
			print '<option value="'.$defaultSelection.'">'.$defaultSelection.'</option>';
		}
				
		while($row=mysql_fetch_array($qQD))
		{
			if ($currQD == $row[Long_Name])
			{
				print '<option value="'.$row[Long_Name].'" SELECTED>'.$row[Long_Name]."</option>";
			}
			else
			{
				print '<option value="'.$row[Long_Name].'">'.$row[Long_Name]."</option>";
			}
		}
	}
?>
