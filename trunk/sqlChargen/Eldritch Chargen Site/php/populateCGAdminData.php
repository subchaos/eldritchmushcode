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
							or trigger_error("PHPError: ".mysql_error(),E_USER_ERROR);
			mysql_select_db($database_eldritchSQL)
				or trigger_error("PHPError: ".mysql_error(),E_USER_ERROR);

			$currName = $_POST['NAME'];
			$type = $_POST['TYPE'];

			if ($type == "QUAL" || $type == "DRAW")
			{
				echoQD($currName);
			}
			else if ($type == "PACKAGE")
			{
				echoPackage($currName);
			}
			else if ($type == "QDLIST")
			{
				echoQDList();
			}
			else if ($type == "ADMIN")
			{
				echoAdmin();
			}
			else if ($type == "USER")
			{
				echoUser();
			}
			mysql_close($eldritchSQL);
		}
	}

	function echoQD($currName)
	{
		if ($currName != "")
		{
			$currRow = mysql_query("SELECT * FROM CGDB_QD WHERE Long_Name = '".$currName."'")
					or trigger_error("PHPError: querying QD name " . mysql_error(), E_USER_ERROR);

			if (mysql_num_rows($currRow) == 1)
			{
				$currRow = mysql_fetch_array($currRow);
				$QDID = $currRow['ID'];
				$QDShortName = $currRow['Short_Name'];
				$QDLongName = $currRow['Long_Name'];
				$QDDesc = $currRow['Description'];
				$QDReqNote = $currRow['Req_Note'];
				$QDPkgOnly = $currRow['Package_Only'];

				$SubItems = mysql_query("SELECT * FROM CGDB_QD_SUBITEM WHERE QD_ID = '".$QDID."' ORDER BY ID")
						or trigger_error("PHPError: querying sub-items " . mysql_error(), E_USER_ERROR);
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
					trigger_error("PHPError: No sub-items found for Name: ".$currName, E_USER_ERROR);
				}
			}
			else
			{
				trigger_error("PHPError: Incorrect number of entries for Name: ".$currName, E_USER_ERROR);
			}
		}
		else
		{
			trigger_error("PHPError: Empty Name Passed", E_USER_ERROR);
		}
	}

	function echoPackage($currName)
	{
		if ($currName != "")
		{
			$currRow = mysql_query("SELECT * FROM CGDB_PKG WHERE Long_Name = '".$currName."'")
					or trigger_error("PHPError: Querying PKG name: " . mysql_error(), E_USER_ERROR);

			if (mysql_num_rows($currRow) == 1)
			{
				$currRow = mysql_fetch_array($currRow);
				$PKGID = $currRow['ID'];
				$PKGShortName = $currRow['Short_Name'];
				$PKGLongName = $currRow['Long_Name'];
				$PKGCost = $currRow['Total_Cost'];
				$PKGDesc = $currRow['Description'];

				echo "<?xml version=\"1.0\"?>\n";
				echo "<response>\n";
				echo "\t<pkgid>".$PKGID."</qdid>\n";
				echo "\t<shortname>".$PKGShortName."</shortname>\n";
				echo "\t<longname>".$PKGLongName."</longname>\n";
				echo "\t<pkgcost>".$PKGCost."</pkgcost>\n";
				echo "\t<desc>".$PKGDesc."</desc>\n";

				$QDItems = mysql_query("SELECT * FROM CGDB_PKG_QD WHERE PKG_ID='".$PKGID."' ORDER BY ID")
						or trigger_error("PHPError: Querying pkg qd items: " . mysql_error(), E_USER_ERROR);
				while($row=mysql_fetch_array($QDItems))
				{
					$PKGQDID = $row['ID'];
					$PKGQDRating = $row['Rating'];
					$PKGQDCost = $row['Cost'];
					$PKGQDNote = $row['Note'];
					$QDID = $row['QD_ID'];
					$QDSUBID = $row['QD_SUB_ID'];

					if ($QDID != 0)
					{
						$QDItem = mysql_query("SELECT * FROM CGDB_QD WHERE ID = '".$QDID."'")
						or trigger_error("PHPError: Querying qd for id '".$QDID."': " . mysql_error(), E_USER_ERROR);

						if (mysql_num_rows($QDItem) == 1)
						{
							$qdRow = mysql_fetch_array($QDItem);

							$qdtype = $qdRow['Type'];

							if ($qdtype == "QUAL")
							{
								echo "\t<qualitem>\n";
							}
							else
							{
								echo "\t<drawitem>\n";
							}
							echo "\t\t<pkgqdid>".$PKGQDID."</pkgqdid>\n";
							echo "\t\t<pkgqdrating>".$PKGQDRating."</pkgqdrating>\n";
							echo "\t\t<pkgqdcost>".$PKGQDCost."</pkgqdcost>\n";
							echo "\t\t<pkgqdnote>".$PKGQDNote."</pkgqdnote>\n";
							echo "\t\t<qdid>".$QDID."</qdid>\n";
							echo "\t\t<qdshortname>".$qdRow['Short_Name']."</qdshortname>\n";
							echo "\t\t<qdlongname>".$qdRow['Long_Name']."</qdlongname>\n";
							echo "\t\t<qdreqnote>".$qdRow['Req_Note']."</qdreqnote>\n";

							if ($QDSUBID != 0)
							{
								echo "\t\t<qdsubid>".$QDSUBID."</qdsubid>\n";
								$QDSubItem = mysql_query("SELECT * FROM CGDB_QD_SUBITEM WHERE ID = '".$QDSUBID."'")
								or trigger_error("PHPError: querying qd sub for id '".$QDSUBID."': " . mysql_error(), E_USER_ERROR);

								if (mysql_num_rows($QDSubItem) == 1)
								{
									$qdSubRow = mysql_fetch_array($QDSubItem);
									echo "\t\t<qdsubname>".$qdSubRow['Name']."</qdsubname>\n";
									echo "\t\t<qdsubcost>".$qdSubRow['Cost']."</qdsubcost>\n";
								}
								else
								{
									trigger_error("PHPError: More than one QD SUB matched for id '".$QDSUBID."'.", E_USER_ERROR);
								}
							}
							else
							{
								trigger_error("PHPError: Blank QD SUB ID not allowed.", E_USER_ERROR);
							}
						}
						else
						{
							trigger_error("PHPError: More than one QD matched for id '".$QDID."'.", E_USER_ERROR);
						}

						if ($qdtype == "QUAL")
						{
							echo "\t</qualitem>\n";
						}
						else
						{
							echo "\t</drawitem>\n";
						}
					}
					else
					{
						trigger_error("PHPError: Blank QD ID not allowed.", E_USER_ERROR);
					}
				}

				$PoolItems = mysql_query("SELECT * FROM CGDB_PKG_POOL WHERE PKG_ID='".$PKGID."' ORDER BY ID")
						or trigger_error("PHPError: Querying pkg pool items: " . mysql_error(), E_USER_ERROR);
				while($row=mysql_fetch_array($PoolItems))
				{
					$PKGPoolID = $row['ID'];
					$PKGPoolType  = $row['Type'];
					$PKGPoolTraitTypeID = $row['Trait_Type_ID'];
					$PKGPoolFilter = $row['Filter'];
					$PKGPoolPoints = $row['Total_Points'];
					$PKGPoolCost = $row['Cost'];

					if ($PKGPoolType == "QUAL")
					{
						echo "\t<qualpoolitem>\n";
					}
					else
					{
						echo "\t<drawpoolitem>\n";
					}

					echo "\t\t<poolid>".$PKGPoolID."</poolid>\n";
					echo "\t\t<pooltraittypeid>".$PKGPoolTraitTypeID."</pooltraittypeid>\n";

					$TraitTypeItem = mysql_query("SELECT * FROM CGDB_VALIDTRAITTYPES WHERE ID='".$PKGPoolTraitTypeID."'")
						or trigger_error("PHPError: Querying trait type: " . mysql_error(), E_USER_ERROR);
					if (mysql_num_rows($TraitTypeItem) == 1)
					{
						$traitData = mysql_fetch_array($TraitTypeItem);
						echo "\t\t<pooltraittype>".$traitData["Type"]."</pooltraittype>\n";
						echo "\t\t<pooltraitcost>".$traitData["Cost_Per_Rating"]."</pooltraitcost>\n";
					}
					else
					{
						trigger_error("PHPError: Trait type matched more than one.", E_USER_ERROR);
					}

					echo "\t\t<poolfilter>".$PKGPoolFilter."</poolfilter>\n";
					echo "\t\t<poolpoints>".$PKGPoolPoints."</poolpoints>\n";
					echo "\t\t<poolcost>".$PKGPoolCost."</poolcost>\n";

					if ($PKGPoolType == "QUAL")
					{
						echo "\t</qualpoolitem>\n";
					}
					else
					{
						echo "\t</drawpoolitem>\n";
					}
				}

				$ChoiceItems = mysql_query("SELECT * FROM CGDB_PKG_CHOICE WHERE PKG_ID='".$PKGID."' ORDER BY ID")
						or trigger_error("PHPError: Querying pkg choice items: " . mysql_error(), E_USER_ERROR);
				while($row=mysql_fetch_array($ChoiceItems))
				{
					$PKGChoiceID = $row['ID'];
					$PKGChoiceType =  $row['Type'];
					$PKGChoiceHighCost = $row['High_Cost'];

					if ($PKGChoiceType == "QUAL")
					{
						echo "\t<qualchoiceitem>\n";
					}
					else
					{
						echo "\t<drawchoiceitem>\n";
					}

					echo "\t\t<choiceid>".$PKGChoiceID."</choiceid>\n";
					echo "\t\t<choicehighcost>".$PKGChoiceHighCost."</choicehighcost>\n";

					$OptionItems = mysql_query("SELECT * FROM CGDB_PKG_CHOICE_OPTION WHERE Choice_ID='".$PKGChoiceID."' ORDER BY ID")
							or trigger_error("PHPError: Querying pkg choice items: " . mysql_error(), E_USER_ERROR);
					while($orow=mysql_fetch_array($OptionItems))
					{
						$PKGOptionID = $orow['ID'];
						$PKGOptionComboCost = $orow['Combo_Cost'];

						echo "\t\t<optionitem>\n";
						echo "\t\t\t<optionid>".$PKGOptionID."</optionid>\n";
						echo "\t\t\t<optioncombocost>".$PKGOptionComboCost."</optioncombocost>\n";

						$OptionQDItems = mysql_query("SELECT * FROM CGDB_PKG_CHOICE_OPTION_QD WHERE Option_ID='".$PKGOptionID."' ORDER BY ID")
								or trigger_error("PHPError: querying pkg choice items: " . mysql_error(), E_USER_ERROR);
						while($oqdrow=mysql_fetch_array($OptionQDItems))
						{
							$PKGOptionQDID = $oqdrow['ID'];
							$PKGOptionQDRating = $oqdrow['Rating'];
							$PKGOptionQDCost = $oqdrow['Cost'];
							$PKGOptionQDNote = $oqdrow['Note'];
							$PKGOptionQDQDID = $oqdrow['QD_ID'];
							$PKGOptionQDQDSUBID = $oqdrow['QD_SUB_ID'];

							echo "\t\t\t<optionqditem>\n";
							echo "\t\t\t\t<optionqdid>".$PKGOptionQDID."</optionqdid>\n";
							echo "\t\t\t\t<optionqdrating>".$PKGOptionQDRating."</optionqdrating>\n";
							echo "\t\t\t\t<optionqdcost>".$PKGOptionQDCost."</optionqdcost>\n";
							echo "\t\t\t\t<optionqdnote>".$PKGOptionQDNote."</optionqdnote>\n";

							if ($PKGOptionQDQDID != 0)
							{
								echo "\t\t\t\t<qdid>".$PKGOptionQDQDID."</qdid>\n";

								$QDItem = mysql_query("SELECT * FROM CGDB_QD WHERE ID = '".$PKGOptionQDQDID."'")
								or trigger_error("PHPError: querying qd for id '".$PKGOptionQDQDID."': " . mysql_error(), E_USER_ERROR);

								if (mysql_num_rows($QDItem) == 1)
								{
									$qdRow = mysql_fetch_array($QDItem);
									echo "\t\t\t\t<qdshortname>".$qdRow['Short_Name']."</qdshortname>\n";
									echo "\t\t\t\t<qdlongname>".$qdRow['Long_Name']."</qdlongname>\n";
									echo "\t\t\t\t<qdreqnote>".$qdRow['Req_Note']."</qdreqnote>\n";

									if ($PKGOptionQDQDSUBID != 0)
									{
									  echo "\t\t\t\t<qdsubid>".$PKGOptionQDQDSUBID."</qdsubid>\n";
										$QDSubItem = mysql_query("SELECT * FROM CGDB_QD_SUBITEM WHERE ID = '".$PKGOptionQDQDSUBID."'")
										or trigger_error("PHPError: Querying qd sub for id '".$PKGOptionQDQDSUBID."': " . mysql_error(), E_USER_ERROR);

										if (mysql_num_rows($QDSubItem) == 1)
										{
											$qdSubRow = mysql_fetch_array($QDSubItem);
											echo "\t\t\t\t<qdsubname>".$qdSubRow['Name']."</qdsubname>\n";
											echo "\t\t\t\t<qdsubcost>".$qdSubRow['Cost']."</qdsubcost>\n";
										}
										else
										{
											trigger_error("PHPError: More than one QD SUB matched for id '".$PKGOptionQDQDSUBID."'.", E_USER_ERROR);
										}
									}
									else
									{
										trigger_error("PHPError: Blank QD SUB ID not allowed.", E_USER_ERROR);
									}
								}
								else
								{
									trigger_error("PHPError: More than one QD matched for id '".$PKGOptionQDQDID."'.", E_USER_ERROR);
								}
							}
							else
							{
								trigger_error("PHPError: Blank QD ID not allowed.", E_USER_ERROR);
							}

							echo "\t\t\t</optionqditem>\n";
						}

						echo "\t\t</optionitem>\n";
					}

					if ($PKGChoiceType == "QUAL")
					{
						echo "\t</qualchoiceitem>\n";
					}
					else
					{
						echo "\t</drawchoiceitem>\n";
					}
				}

				echo "</response>";
			}
			else
			{
				trigger_error("PHPError: Incorrect number of entries for name: ".$currName, E_USER_ERROR);
			}
		}
		else
		{
			trigger_error("PHPError: Empty Name Passed", E_USER_ERROR);
		}
	}

	function echoQDList()
	{
		$qQUAL = mysql_query("SELECT *  FROM CGDB_QD WHERE Type = 'QUAL' ORDER BY Long_Name ASC")
				or trigger_error("PHPError: Querying quality " . mysql_error(), E_USER_ERROR);
		$qDRAW = mysql_query("SELECT *  FROM CGDB_QD WHERE Type = 'DRAW' ORDER BY Long_Name ASC")
				or trigger_error("PHPError: Querying drawback " . mysql_error(), E_USER_ERROR);

		echo "<?xml version=\"1.0\"?>\n";
		echo "<response>\n";
		echo "\t<qualities>\n";
		while($qual = mysql_fetch_array($qQUAL))
		{
			$subresult = mysql_query("SELECT * FROM CGDB_QD_SUBITEM WHERE QD_ID='".$qual["ID"]."' ORDER BY ID")
				or trigger_error("PHPError: Querying sub-list". mysql_error(), E_USER_ERROR);
			if (mysql_numrows($subresult) > 0)
			{
				echo "\t\t<qual>\n";
				echo "\t\t\t<id>".$qual["ID"]."</id>\n";
				echo "\t\t\t<short_name>".$qual["Short_Name"]."</short_name>\n";
				echo "\t\t\t<long_name>".$qual["Long_Name"]."</long_name>\n";

				while($subitem = mysql_fetch_array($subresult))
				{
					echo "\t\t\t<subitem>";
					echo "\t\t\t\t<id>".$subitem["ID"]."</id>\n";
					echo "\t\t\t\t<name>".$subitem["Name"]."</name>\n";
					echo "\t\t\t\t<cost>".$subitem["Cost"]."</cost>\n";
					echo "\t\t\t</subitem>\n";
				}

				echo "\t\t</qual>\n";
			}
			else
			{
				trigger_error("PHPError: 0 Sub-items.", E_USER_ERROR);
			}
		}
		echo "\t</qualities>\n";
		echo "\t<drawbacks>\n";
		while($draw = mysql_fetch_array($qDRAW))
		{
			$subresult = mysql_query("SELECT * FROM CGDB_QD_SUBITEM WHERE QD_ID='".$draw["ID"]."' ORDER BY ID")
				or trigger_error("PHPError: Querying sub-list". mysql_error(), E_USER_ERROR);
			if (mysql_numrows($subresult) > 0)
			{
				echo "\t\t<draw>\n";
				echo "\t\t\t<id>".$draw["ID"]."</id>\n";
				echo "\t\t\t<short_name>".$draw["Short_Name"]."</short_name>\n";
				echo "\t\t\t<long_name>".$draw["Long_Name"]."</long_name>\n";

				while($subitem = mysql_fetch_array($subresult))
				{
					echo "\t\t\t<subitem>";
					echo "\t\t\t\t<id>".$subitem["ID"]."</id>\n";
					echo "\t\t\t\t<name>".$subitem["Name"]."</name>\n";
					echo "\t\t\t\t<cost>".$subitem["Cost"]."</cost>\n";
					echo "\t\t\t</subitem>\n";
				}

				echo "\t\t</draw>\n";
			}
			else
			{
				trigger_error("PHPError: 0 Sub-items.", E_USER_ERROR);
			}
		}
		echo "\t</drawbacks>\n";

		echo "</response>";
	}

	function echoAdmin()
	{
		$result = mysql_query("SELECT * FROM CGDB_ADMIN")
				or trigger_error("PHPError: Querying ADMIN " . mysql_error(), E_USER_ERROR);

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

	function echoUser()
	{
		$result = mysql_query("SELECT * FROM CGDB_USER")
				or trigger_error("PHPError: Querying user: " . mysql_error(), E_USER_ERROR);

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
