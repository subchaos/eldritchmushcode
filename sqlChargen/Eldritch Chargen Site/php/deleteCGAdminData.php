<?
	error_reporting(E_ALL);
	header("Cache-Control: no-cache");

	include 'CGAdminDB.php';
	//need to create this file. it's not part of the SVN. It needs to have $password_eldritchSQL and $AES_ENC_KEY
	include 'CGAdminPasswords.php';

	if (isset($_POST['ID']))
	{
		if (isset($_POST['TYPE']))
		{
			$eldritchSQL = mysql_pconnect($hostname_eldritchSQL, $username_eldritchSQL, $password_eldritchSQL)
							or trigger_error(mysql_error(),E_USER_ERROR);
			mysql_select_db($database_eldritchSQL)
				or trigger_error(mysql_error(),E_USER_ERROR);

			$currID = $_POST['ID'];
			$type = $_POST['TYPE'];

			if ($type == "QD")
			{
				delQD($currID);
			}
			else if ($type == "QDSUB")
			{
				delQDSub($currID);
			}
			else if ($type == "ADMIN")
			{
				delAdmin($currID);
			}
			else if ($type == "USER")
			{
				delUser($currID);
			}
			mysql_close($eldritchSQL);
		}
	}

	function delQD($currID)
	{
		if ($currID != "")
		{
			$response1 = mysql_query("DELETE FROM CGDB_QD_SUBITEM WHERE QD_ID = ".$currID)
						or trigger_error("Error removing sub-items for ID ".$currID.": " . mysql_error(), E_USER_ERROR);
			$response2 = mysql_query("DELETE FROM CGDB_QD WHERE ID = ".$currID)
						or trigger_error("Error removing QD for ID ".$currID.": " . mysql_error(), E_USER_ERROR);
			echo("Success");
		}
		else
		{
			trigger_error("Empty ID Passed", E_USER_ERROR);
		}
	}

	function delQDSUB($currID)
	{
		$return = "";
		if ($currID != "")
		{
			$currSubIDArr = explode("|", $currID);

			for ($i = 0; $i < count($currSubIDArr); $i++)
			{
				$result = mysql_query("DELETE FROM CGDB_QD_SUBITEM WHERE ID = ".$currSubIDArr[$i])
							or trigger_error("Error removing sub-items for ID ".$currSubIDArr[$i].": " . mysql_error(), E_USER_ERROR);

				if ($result)
				{
					$return = $return + "Success";
				}
				else
				{
					$return = $return + "Failure";
				}
			}

		}
		else
		{
			$return = $return + "Success";
		}

		if (strstr($return,"Failure") == false)
		{
			echo("Success");
		}
		else
		{
			echo("Failure");
		}
	}

	function delAdmin($currID)
	{
		if ($currID != "")
		{
			$result = mysql_query("DELETE FROM CGDB_ADMIN WHERE ID = ".$currID)
						or trigger_error("Error removing admin ID ".$currID.": " . mysql_error(), E_USER_ERROR);
			echo("Success");
		}
		else
		{
			trigger_error("Empty Admin ID Passed", E_USER_ERROR);
		}
	}

	function delUser($currID)
	{
		if ($currID != "")
		{
			$result = mysql_query("DELETE FROM CGDB_USER WHERE ID = ".$currID)
						or trigger_error("Error removing User ID ".$currID.": " . mysql_error(), E_USER_ERROR);
			echo("Success");
			//need to also recurse down and delete any sheets and all the info tied to that user ID
		}
		else
		{
			trigger_error("Empty User ID Passed", E_USER_ERROR);
		}
	}

?>
