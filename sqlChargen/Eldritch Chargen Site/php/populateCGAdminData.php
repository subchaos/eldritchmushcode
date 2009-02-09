<?
	error_reporting(E_ALL);
	header("Cache-Control: no-cache");

	include 'CGAdminDB.php';
	//need to create this file. it's not part of the SVN. It needs to have $password_eldritchSQL and $AES_ENC_KEY
	include 'CGAdminPasswords.php';

	if (isset($_POST['NAME']))
	{
		if (isset($_POST['TYPE']))
		{
			$eldritchSQL = mysql_pconnect($hostname_eldritchSQL, $username_eldritchSQL, $password_eldritchSQL) 
							or trigger_error(mysql_error(),E_USER_ERROR); 
			mysql_select_db($database_eldritchSQL) 
				or trigger_error(mysql_error(),E_USER_ERROR);
	
			$currName = $_POST['NAME'];
			$type = $_POST['TYPE'];
			
			if ($type == "QUAL" || $type == "DRAW")
			{
				echoQD($currName);
			}
			else if ($type == "ADMIN")
			{
				echoAdmin();
			}
			mysql_close($eldritchSQL);
		}
	}	
	
	function echoQD($currName)
	{
		if ($currName != "")
		{
			$currRow = mysql_query("SELECT * FROM CGDB_QD WHERE Long_Name = '".$currName."'")
					or trigger_error("Error querying QD name: " . mysql_error(), E_USER_ERROR);
			
			if (mysql_num_rows($currRow) == 1)
			{
				$currRow = mysql_fetch_array($currRow);
				$QDID = $currRow['ID'];
				$QDShortName = $currRow['Short_Name'];
				$QDLongName = $currRow['Long_Name'];
				$QDDesc = $currRow['Description'];
				$QDReqNote = $currRow['Req_Note'];
				$QDPkgOnly = $currRow['Package_Only'];
								
				$SubItems = mysql_query("SELECT * FROM CGDB_QD_SUBITEM WHERE QD_ID IN (SELECT ID FROM CGDB_QD WHERE Long_Name = '".$currName."') ORDER BY ID")
						or trigger_error("Error querying sub-items: " . mysql_error(), E_USER_ERROR);
				$NumSubItems = mysql_num_rows($SubItems);
				if ($NumSubItems > 0)
				{
					echo "<?xml version=\"1.0\"?>\n";
					echo "<response>\n";
					echo "\t<qdid>".$QDID."</qdid>\n";
					echo "\t<shortname>".$QDShortName."</shortname>\n";
					echo "\t<longname>".$QDLongName."</longname>\n";
					echo "\t<desc>".$QDDesc."</desc>\n";
					echo "\t<reqnote>".$QDReqNote."</reqnote>\n";
					echo "\t<pkgonly>".$QDPkgOnly."</pkgonly>\n";
	
					while($row=mysql_fetch_array($SubItems))
					{
						echo "\t<subitem>\n";
						echo "\t\t<subname>".$row['Name']."</subname>\n";
						echo "\t\t<value>".$row['Cost']."</value>\n";
						echo "\t\t<subid>".$row['ID']."</subid>\n";
						echo "\t</subitem>\n";
					}

					echo "</response>";
				}
				else
				{
					trigger_error("Error: No sub-items found for Name: ".$currName, E_USER_ERROR);
				}	
			}
			else
			{
				trigger_error("Error: Incorrect number of entries for Name: ".$currName, E_USER_ERROR);
			}
		}
		else
		{
			trigger_error("Error: Empty Name Passed", E_USER_ERROR);
		}
	}
		
	
	function echoAdmin()
	{
		$result = mysql_query("SELECT * FROM CGDB_ADMIN")
				or trigger_error("Error querying ADMIN: " . mysql_error(), E_USER_ERROR);
		
		echo "<?xml version=\"1.0\"?>\n";
		echo "<response>\n";
		while($row = mysql_fetch_array($result))
		{								
			echo("\t<user>");
			echo("\t\t<id>".$row['ID']."</id>\n");
			echo("\t\t<name>".$row['User']."</name>\n");
			echo("\t</user>");
		}
		echo "</response>";
	}

?>
