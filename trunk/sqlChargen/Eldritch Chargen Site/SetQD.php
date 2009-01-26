<?
	$hostname_eldritchSQL = "localhost";
	$database_eldritchSQL = "eldritch_eldritch_eldritch";
	$username_eldritchSQL = "eldritch";
	$password_eldritchSQL = "c0b30121";
	$eldritchSQL = mysql_pconnect($hostname_eldritchSQL, $username_eldritchSQL, $password_eldritchSQL) 
					or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db($database_eldritchSQL) 
		or trigger_error(mysql_error(),E_USER_ERROR);
	$qQUAL = mysql_query("SELECT *  FROM CGDB_QD WHERE Type = 'QUAL'") 
				or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
	$qDRAW = mysql_query("SELECT *  FROM CGDB_QD WHERE Type = 'DRAW'")
				or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
	
	
	
	$status = "First Startup";
	if (!isset($_POST['btnReset']) && isset($_POST['status']))
	{
		$status = $_POST['status'];
	}
	
	if ($status == "Second")
	{
		trigger_error("testing status updates", E_USER_ERROR);
	}
	
	if ($status == "First Startup")
	{
	 	$status = "Second";
	}
	
	$viewingQual = TRUE;
	if (!isset($_POST['btnReset']) && isset($_POST['qdrdogroup']))
	{
		if ($_POST['qdrdogroup'] == "rdoQual")
		{
			$viewingQual = TRUE;
		}
		else
		{
			$viewingQual = FALSE;
		}
	} 
	
	$currQD = NULL;
	if (!isset($_POST['btnReset']) && isset($_POST['lstQD']))
	{
		$currQD = $_POST['lstQD'];
	}
	

	$currSubList = "~~";
	$SubItems = NULL;
	$NumSubItems = NULL;
	$QDShortName = NULL;
	$QDLongName = NULL;
	$QDDesc = NULL;
	$QDReqNote = NULL;	
	if (isset($_POST['status']) && !isset( $_POST['btnSubmit']) && !isset( $_POST['btnAddSub']) && !isset( $_POST['btnDeleteSubs']))
	{
		//trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
		if ($currQD != NULL && strpos($currQD, "Add New ") == FALSE)
		{
			$currRow = mysql_query("SELECT * FROM CGDB_QD WHERE Long_Name = '".$currQD."'")
					or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
			
			$RowGood = FALSE;
			if (mysql_num_rows($currRow) == 1)
			{
				$RowGood = TRUE;	
			}
			
			if ($RowGood)
			{
				$QDShortName = $currRow['Short_Name'];
				$QDLongName = $currRow['Long_Name'];
				$QDDesc = $currRow['Desc'];
				$QDReqNote = $currRow['Req_Note'];
			}
			
			$SubItems = mysql_query("SELECT * FROM CGDB_QD_SUBITEM WHERE QD_ID IN (SELECT ID FROM CGDB_QD WHERE Long_Name = '".$currQD."')")
					or trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
			$NumSubItems = mysql_num_rows($SubItems);
			if ($NumSubItems == 0)
			{
				$SubItems = NULL;
				$NumSubItems = NULL;
				
				if (isset($_POST['currSubList']))
				{
					$currSubList = $_POST['currSubList'];
				}
			}
		}
	}
	else
	{
		if (isset($_POST['txtShortName']))
		{
			$QDShortName = $_POST['txtShortName'];
		}

		if (isset($_POST['txtLongName']))
		{
			$QDLongName = $_POST['txtLongName'];
		}

		if (isset($_POST['txtareaDescription']))
		{
			$QDDesc = $_POST['txtareaDescription'];
		}

		if (isset($_POST['chkReqNote']))
		{
			$QDReqNote = $_POST['chkReqNote'];
		}
	}
			
	if (isset($_POST['btnAddSub']))
	{
		if ($SubItems != NULL)
		{
			//figure out later
		}
		else
		{
			$currSubList = $currSubList."|~~";
		}
	}
	
	mysql_close($eldritchSQL);

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
  <input type="radio" onclick="submit()" name="qdrdogroup" value="rdoQual" <? if ($viewingQual) print 'checked'; ?>> 
	Qualities&nbsp;&nbsp;
  <input type="radio" onclick="submit()" name="qdrdogroup" value="rdoDraw" <? if (!$viewingQual) print 'checked'; ?>> Drawbacks
        
    <br><br>
    
    Add/Modify <? if ($viewingQual) print "Quality"; else print "Drawback" ?>: 
  <select name="lstQD" onchange="submit()">
     	<? PopulateQDList($viewingQual, $qQUAL, $qDRAW, $currQD) ?>
  </select>
	<br>

    <hr>
    Short Name: <input name="txtShortName" type="text" size="6" maxlength="5" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Long Name: <input name="txtLongName" type="text" size="35" maxlength="34" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="chkReqNote" type="checkbox" value="chkReqNote" /> 
    Requires Note</input>
    <br>
    
    Description:<br>
    <textarea name="txtareaDescription" cols="80" rows="5"></textarea>
    <br><br>
 
    <? PopulateQDSubList($viewingQual, $SubItems, $NumSubItems, $currSubList) ?>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <? if ($NumSubItems > 1) print '<input type="submit" name="btnDeleteSubs" value= "Delete Selected">&nbsp;&nbsp;' ?>
  	<input type="submit" name="btnAddSub" value= "Add Sub-item">
    
    <hr>    
    <input type="hidden" name="currSubList" value="<? $CurrSubList ?>">
    
    <input type="hidden" name="status" value="<? $status ?>">
    <input type="submit" name="btnReset" value= "Reset">&nbsp;&nbsp;
  	<input type="submit" name="btnSubmit" value= "Submit">
    
</form>
</body>
</html>

<?
 	function PopulateQDList($viewingQual, $qQUAL, $qDRAW, $currQD)
	{
		if ($viewingQual) 
		{
			$qQD = $qQUAL;
			$defaultSelection = "Add New Quality";
		} 
		else 
		{	
			$qQD = $qDRAW;
			$defaultSelection = "Add New Drawback";
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
	
	function PopulateQDSubList($viewingQual, $SubItems, $NumSubItems, $currSubList)
	{
		if ($SubItems != NULL)
		{	
			if ($NumSubItems > 1)
			{
				print "Sub-items:<br>";
			}
		
			$i = 0;
			while($row=mysql_fetch_array($SubItems))
			{
				PrintQDSubItem($viewingQual, $i, $NumSubItems, $row['Name'], $row['Cost'], $row['Trait_Mod']);	
				$i = $i + 1;
			}
		}
		else
		{
			$Items = explode("|", $currSubList);
			$ItemCount = count($Items);
			if ($ItemCount != 0)
			{
				if ($ItemCount > 1)
				{
					print "Sub-items:<br>";
				}
			
				$i = 0;
				while($i < $ItemCount)
				{
					$ItemVals = explode("~",$Items[i]);
						
					PrintQDSubItem($viewingQual, $i, $ItemCount, $ItemVals[0], $ItemVals[1], $ItemVals[2]);
					
					$i = $i + 1;
				}
			}
		}
	}
	
	function PrintQDSubItem($viewingQual, $i, $ItemCount, $Name, $Cost, $Traits)
	{
		if ($ItemCount > 1)
		{
			print '<input name="chkSub'.$i.'" type="checkbox" value="chkSub'.$i.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			print 'Name: <input name="txtNameSub'.$i.'" type="text" size="35" maxlength="34" value ="'.$Name.'"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		if ($viewingQual)
		{
			print 'Cost: <input name="txtCostSub'.$i.'" type="text" size="11" maxlength="10" value ="'.$Cost.'"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		else
		{
			print 'Bonus: <input name="txtCostSub'.$i.'" type="text" size="11" maxlength="10" value ="'.$Cost.'"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		print 'Traits Modified: <input name="txtTraitSub'.$i.'" type="text" size="35" maxlength="254" value ="'.$Traits.'"/>';
		print '<br>';		
	}
?>
