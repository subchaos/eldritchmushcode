<?
	error_reporting(E_ALL);
	header("Cache-Control: no-cache");

	include 'CGAdminDB.php';
	
	if (isset($_POST['VAL']))
	{
		if (isset($_POST['ID']))
		{
			if (isset($_POST['TYPE']))
			{
				$eldritchSQL = mysql_pconnect($hostname_eldritchSQL, $username_eldritchSQL, $password_eldritchSQL) 
								or trigger_error(mysql_error(),E_USER_ERROR); 
				mysql_select_db($database_eldritchSQL) 
					or trigger_error(mysql_error(),E_USER_ERROR);
		
				$currVal = $_POST['VAL'];
				$currID = $_POST['ID'];
				$type = $_POST['TYPE'];
				
				if ($type == "QDSHORT")
				{
					validateQDName($currVal, $currID, "Short_Name");
				}
				else if ($type == "QDLONG")
				{
					validateQDName($currVal, $currID, "Long_Name");
				}
				mysql_close($eldritchSQL);
			}
		}
	}	
	
	function validateQDName($currVal, $currID, $currType)
	{
		if ($currVal != "")
		{
			$result = mysql_query("SELECT * FROM CGDB_QD WHERE ".$currType." = '".$currVal."'")
						or trigger_error("Error selecting for the ".$currType." ".$currVal.": " . mysql_error(), E_USER_ERROR);
			if ($currID == "")
			{
				if (mysql_num_rows($result) == 0)
				{
					echo("Success");
				}
				else
				{
					echo("Failure");
				}			
			}
			else
			{
				if (mysql_num_rows($result) == 1)
				{
					$row = mysql_fetch_array($result);
					if ($row['ID'] == $currID)
					{
						echo("Success");
					}
					else
					{
						echo("Failure");
					}
				}
				else if (mysql_num_rows($result) == 0)
				{
					echo("Success");
				}
				else
				{
					echo("Failure");
				}
			}
			
		}
		else
		{
			trigger_error("Empty Val Passed", E_USER_ERROR);
		}
	}
		
	

?>
