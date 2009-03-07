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
			$currType = $_POST['TYPE'];

			if ($currType == "QUAL" || $currType == "DRAW")
			{
				if (isset($_POST['SHORTNAME']) && isset($_POST['LONGNAME']) && isset($_POST['REQNOTE']) && isset($_POST['PKGONLY']) && isset($_POST['DESC']))
				{
					$currShortName = $_POST['SHORTNAME'];
					$currLongName = $_POST['LONGNAME'];
					$currReqNote = $_POST['REQNOTE'];
					$currPkgOnly = $_POST['PKGONLY'];
					$currDesc = $_POST['DESC'];
					updateQD($currID, $currType, $currShortName, $currLongName, $currReqNote, $currPkgOnly, $currDesc);
				}
			}
			else if ($currType == "QDSUB")
			{
				if (isset($_POST['SUBID']) && isset($_POST['SUBNAME']) && isset($_POST['SUBCOST']))
				{
					$currSubID = $_POST['SUBID'];
					$currSubName = $_POST['SUBNAME'];
					$currSubCost = $_POST['SUBCOST'];
					updateQDSub($currID, $currSubID, $currSubName, $currSubCost);
				}
			}
			else if ($currType == "ADMIN")
			{
				if (isset($_POST['USER']) && isset($_POST['PASS']))
				{
					$currUser = $_POST['USER'];
					$currPass = $_POST['PASS'];
					updateAdmin($currID, $currUser, $currPass, $AES_ENC_KEY);
				}
			}
			else if ($currType == "USER")
			{
				if (isset($_POST['USER']) && isset($_POST['PASS']))
				{
					$currUser = $_POST['USER'];
					$currPass = $_POST['PASS'];
					updateUser($currID, $currUser, $currPass, $AES_ENC_KEY);
				}
			}
			mysql_close($eldritchSQL);
		}
	}

	function updateQD($currID, $currType, $currShortName, $currLongName, $currReqNote, $currPkgOnly, $currDesc)
	{
		if ($currID == "")
		{
			$result = mysql_query("INSERT INTO CGDB_QD (ID, Short_Name, Long_Name, Type, Req_Note, Package_Only, Description) VALUES (NULL , '".$currShortName."', '".$currLongName."', '".$currType."', '".$currReqNote."', '".$currPkgOnly."', '".$currDesc."')")
						or trigger_error("Error inserting new QD: " . mysql_error(), E_USER_ERROR);
			if ($result)
			{
				echo(mysql_insert_id());
			}
			else
			{
				echo("Failure");
			}
		}
		else
		{
			$result = mysql_query("UPDATE CGDB_QD SET Short_Name = '".$currShortName."', Long_Name = '".$currLongName."', Type = '".$currType."', Req_Note = '".$currReqNote."', Package_Only = '".$currPkgOnly."', Description = '".$currDesc."' WHERE CGDB_QD.ID =".$currID)
						or trigger_error("Error updating QD for ID ".$currID.": " . mysql_error(), E_USER_ERROR);
			if ($result)
			{
				echo($currID);
			}
			else
			{
				echo("Failure");
			}
		}
	}

	function updateQDSub($currID, $currSubID, $currSubName, $currSubCost)
	{
		$currSubIDArr = explode("|", $currSubID);
		$currSubNameArr = explode("|", $currSubName);
		$currSubCostArr = explode("|", $currSubCost);

		$return = "";

		if (count($currSubIDArr) == count ($currSubNameArr) && count($currSubCostArr) == count ($currSubCostArr))
		{
			for ($i = 0; $i < count($currSubIDArr); $i++)
			{
				if ($currSubIDArr[$i] == "")
				{
					$result = mysql_query("INSERT INTO CGDB_QD_SUBITEM (ID, QD_ID, Name, Cost) VALUES (NULL , '".$currID."', '".$currSubNameArr[$i]."', '".$currSubCostArr[$i]."')")
								or trigger_error("Error inserting new QDSUB: " . mysql_error(), E_USER_ERROR);
					if ($result)
					{
						$return = $return + "|" + "Success";
					}
					else
					{
						$return = $return + "|" + "Failure";
					}
				}
				else
				{
					$result = mysql_query("UPDATE CGDB_QD_SUBITEM SET QD_ID = '".$currID."', Name = '".$currSubNameArr[$i]."', Cost = '".$currSubCostArr[$i]."' WHERE ID =".$currSubIDArr[$i])
								or trigger_error("Error updating QDSUB for ID ".$currSubIDArr[$i].": " . mysql_error(), E_USER_ERROR);
					if ($result)
					{
						$return = $return + "|" + "Success";
					}
					else
					{
						$return = $return + "|" + "Failure";
					}
				}
			}
		}
		else
		{
			$return = $return + "|" + "Failure: Array size mismatch";
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


	function updateAdmin($currID, $currUser, $currPass, $AES_ENC_KEY)
	{
		if ($currID == "")
		{
			$result = mysql_query("INSERT INTO CGDB_ADMIN VALUES (NULL, '".$currUser."', AES_ENCRYPT('".$currPass."','".$AES_ENC_KEY."') )")
						or trigger_error("Error inserting new admin: " . mysql_error(), E_USER_ERROR);
			if ($result)
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
			$result = mysql_query("UPDATE CGDB_ADMIN SET User = '".$currUser."', Pass = AES_ENCRYPT('".$currPass."','".$AES_ENC_KEY."') WHERE ID =".$currID)
						or trigger_error("Error updating admin for ID ".$currID.": " . mysql_error(), E_USER_ERROR);
			if ($result)
			{
				echo("Success");
			}
			else
			{
				echo("Failure");
			}
		}
	}

	function updateUser($currID, $currUser, $currPass, $AES_ENC_KEY)
	{
		if ($currID == "")
		{
			$result = mysql_query("INSERT INTO CGDB_USER VALUES (NULL, '".$currUser."', AES_ENCRYPT('".$currPass."','".$AES_ENC_KEY."') )")
						or trigger_error("Error inserting new user: " . mysql_error(), E_USER_ERROR);
			if ($result)
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
			$result = mysql_query("UPDATE CGDB_USER SET User = '".$currUser."', Pass = AES_ENCRYPT('".$currPass."','".$AES_ENC_KEY."') WHERE ID =".$currID)
						or trigger_error("Error updating user for ID ".$currID.": " . mysql_error(), E_USER_ERROR);
			if ($result)
			{
				echo("Success");
			}
			else
			{
				echo("Failure");
			}
		}
	}

?>
