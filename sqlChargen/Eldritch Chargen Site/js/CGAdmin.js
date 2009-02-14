	var viewport;
	var pnlTraitNav;
	var pnlDefaultError;
	var pnlDefaultValues;
	var pnlCenterHolder;
	var pnlDefaultCenter;
	var pnlPkgBasics;
	var pnlPkgButtons;
	var pnlPkgQualTree;
	var pnlPkgQualTreeBtn;
	var pnlPkgQualLeft;
	var pnlPkgQualList;
	var pnlPkgQualListBtn;
	var pnlPkgQualRight;
	var pnlPkgQual;
	var pnlPkgDrawTree;
	var pnlPkgDrawTreeBtn;
	var pnlPkgDrawLeft;
	var pnlPkgDrawList;
	var pnlPkgDrawListBtn;
	var pnlPkgDrawRight;
	var pnlPkgDraw;
	var pnlPkgQDAccordion;
	var pnlPkgTab;
	var pnlPkgQDTabHolder;
	var pnlPkgGroup;
	var pnlPkgError;
	var pnlPkgCenter;


	var currCenter;
	var simpleTreeCollection = "";
	var modified = false;
	var SubToDel =  new Array();
	var pkgDrawInitialized = false;
	var highSub = 1;
	var errorList = new Array();
	//var CurrName = "Administrators";
	var CurrName = "";


	Ext.BLANK_IMAGE_URL = (function() {
		if (Ext.isIE8 || Ext.isGecko || Ext.isOpera)
		{
			return "data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
		}
		else
		{
			return 'extjs/resources/images/default/s.gif';
		}
	});

	Ext.onReady(function(){
		initializeTips();
		initializeView();
		initializeDisplay();
		initializePkgDisplay();

		if (CurrName == "")
		{
			//startCGAdmin();
		}
	});

	function initializeTips()
	{
		// Init the singleton.  Any tag-based quick tips will start working.
		Ext.QuickTips.init();

		// Apply a set of config properties to the singleton
		Ext.apply(Ext.QuickTips.getQuickTip(), {
			maxWidth: 200,
			minWidth: 100,
			showDelay: 50,
			dismissDelay: 20000,
			trackMouse: true
		});
	}

	function initializeView()
	{
		pnlTraitNav = new Ext.Panel({
			title:'Traits',
			region:'west',
			id:'pnlTraitNav',
			html: '<div id = "treeholder"></div>',
			split:true,
			width: 250,
			minSize: 175,
			maxSize: 400,
			autoScroll:true,
			margins:'35 0 5 5'
		});

		pnlDefaultError = new Ext.Panel({
			title: 'Errors',
			id:'pnlDefaultError',
			region: 'south',
			html:'<div id= "error-pane"></div>',
			height: 100,
			minSize: 75,
			maxSize: 250,
			autoScroll:true,
			split:true,
			collapsible: true,
			titleCollapse: true,
			collapsed: true,
			collapseMode: "mini"
		});

		pnlDefaultValues = new Ext.Panel({
			title:'Values',
			id:'pnlDefaultValues',
			region:'center',
			html:'<div id= "center-pane" style="padding: 10px; min-width: 860px"></div>',
			autoScroll:true
		});

		pnlCenterHolder = new Ext.Panel({
			region:'center',
			id:'pnlCenterHolder',
			margins:'35 5 5 0',
			layout:'fit'
		});

		pnlDefaultCenter = new Ext.Panel({
			region:'center',
			id:'pnlDefaultCenter',
			layout:'border',
			items: [pnlDefaultValues, pnlDefaultError]
		});

		pnlCenterHolder.add(pnlDefaultCenter);

		currCenter = pnlDefaultCenter;

		viewport = new Ext.Viewport({
			layout:'border',
			items:[pnlTraitNav, pnlCenterHolder]
		});
	}

	function initializePkgDisplay()
	{
		pnlPkgBasics = new Ext.Panel({
			title:'Package Info',
			id:'pnlPkgBasics',
			region:'north',
			html:'<div id= "pkg-basics"></div>',
			border: false,
			autoScroll:true,
			height: 175
		});

		pnlPkgButtons = new Ext.Panel({
			id:'pnlPkgButtons',
			region:'south',
			html:'<div id= "pkg-btns"></div>',
			border: false,
			height: 25
		});

		pnlPkgQualTree = new Ext.Panel({
			id:'pnlPkgQualTree',
			region:'center',
			html:'<div id= "pkg-qualtree"></div>',
			border: false,
			autoScroll:true
		});

		pnlPkgQualTreeBtn = new Ext.Panel({
			id:'pnlPkgQualTreeBtn',
			region:'south',
			html:'<div id= "pkg-qualtree-btns"></div>',
			border: false,
			height: 55
		});

		pnlPkgQualLeft = new Ext.Panel({
			region:'west',
			border: false,
			id: 'pnlPkgQualLeft',
			split:true,
			width: 190,
			minSize: 190,
			maxSize: 300,
			layout: 'border',
			items: [pnlPkgQualTreeBtn, pnlPkgQualTree]
		});

		pnlPkgQualList = new Ext.Panel({
			id:'pnlPkgQualList',
			region:'center',
			html:'<div id= "pkg-quallist"></div>',
			border: false
		});

		pnlPkgQualListBtn = new Ext.Panel({
			id:'pnlPkgQualListBtn',
			region:'south',
			html:'<div id= "pkg-quallist-btns"></div>',
			border: false,
			height: 25
		});

		pnlPkgQualRight = new Ext.Panel({
			id:'pnlPkgQualRight',
			region:'center',
			border: false,
			layout: 'border',
			items: [pnlPkgQualList, pnlPkgQualListBtn]
		});

		pnlPkgQual = new Ext.Panel({
			title: 'Qualities',
			layout: 'border',
			border: false,
			items: [pnlPkgQualLeft, pnlPkgQualRight]
		});


		pnlPkgDrawTree = new Ext.Panel({
			id:'pnlPkgDrawTree',
			html:'<div id= "pkg-drawtree"></div>',
			region:'center',
			border: false
		});

		pnlPkgDrawTreeBtn = new Ext.Panel({
			id:'pnlPkgDrawTreeBtn',
			region:'south',
			html:'<div id="pkg-drawtree-btns">testing</div>',
			border: false,
			height: 55
		});

		pnlPkgDrawLeft = new Ext.Panel({
			region:'west',
			border: false,
			id: 'pnlPkgDrawLeft',
			split:true,
			width: 190,
			minSize: 190,
			maxSize: 300,
			layout: 'border',
			items: [pnlPkgDrawTreeBtn, pnlPkgDrawTree]
		});

		pnlPkgDrawList = new Ext.Panel({
			id:'pnlPkgDrawList',
			region:'center',
			html:'<div id= "pkg-drawlist"></div>',
			border: false
		});

		pnlPkgDrawListBtn = new Ext.Panel({
			id:'pnlPkgDrawListBtn',
			region:'south',
			html:'<div id= "pkg-drawlist-btns"></div>',
			border: false,
			height: 25
		});

		pnlPkgDrawRight = new Ext.Panel({
			id:'pnlPkgDrawRight',
			region:'center',
			border: false,
			layout: 'border',
			items: [pnlPkgDrawListBtn, pnlPkgDrawList]
		});

		pnlPkgDraw = new Ext.Panel({
			title: 'Drawbacks',
			layout: 'border',
			border: false,
			listeners: {activate: pkgDrawActivated},
			items: [pnlPkgDrawLeft, pnlPkgDrawRight]
		});

		pnlPkgQDAccordion = new Ext.Panel({
			region:'center',
			collapseFirst: true,
			layout:'accordion',
			titleCollapse: true,
			items: [pnlPkgQual, pnlPkgDraw]
		});

		pnlPkgTab = new Ext.TabPanel({
			activeTab: 0,
			items: [pnlPkgQual, pnlPkgDraw]
		});


		pnlPkgQDTabHolder = new Ext.Panel({
			region:'center',
			layout: 'fit',
			items: [pnlPkgTab]
		});

		pnlPkgGroup = new Ext.Panel({
			id:'pkgMainPanel',
			region:'center',
			layout:'border',
			items: [pnlPkgBasics, pnlPkgQDTabHolder, pnlPkgButtons]
		});

		pnlPkgError = new Ext.Panel({
			title: 'Errors',
			id:'pnlPkgError',
			region: 'south',
			html:'<div id= "pkg-error-pane"></div>',
			height: 100,
			minSize: 75,
			maxSize: 250,
			autoScroll:true,
			split:true,
			collapsible: true,
			titleCollapse: true,
			collapsed: true,
			collapseMode: "mini"
		});

		pnlPkgCenter = new Ext.Panel({
			id:'pkgCenterPanel',
			region:'center',
			layout:'border',
			items: [pnlPkgGroup, pnlPkgError]
		});
	}

	function startCGAdmin()
	{
		// Create a variable to hold our EXT Form Panel.
		// Assign various config options as seen.
		var login = new Ext.FormPanel({
			labelWidth:80,
			url:'php/CGAdminLogin.php',
			frame:true,
			title:'Eldritch Chargen Administrator Panel',
			defaultType:'textfield',
			monitorValid:true,
			// Specific attributes for the text fields for username / password.
			// The "name" attribute defines the name of variables sent to the server.
			items:[{
					fieldLabel:'Username',
					name:'loginUsername',
					id:'loginUsername',
					allowBlank:false,
					listeners: {
						specialkey: function(field, el){
							if (el.getKey() == Ext.EventObject.ENTER)
							{
								Ext.getCmp('loginButton').fireEvent('click');
							}
						}
					}
				},{
					fieldLabel:'Password',
					name:'loginPassword',
					id:'loginPassword',
					inputType:'password',
					allowBlank:false,
					listeners: {
						specialkey: function(field, el){
							if (el.getKey() == Ext.EventObject.ENTER)
							{
								Ext.getCmp('loginButton').fireEvent('click');
							}
						}
					}
				}],

			// All the magic happens after the user clicks the button
			buttons:[{
					id: 'loginButton',
					text:'Login',
					formBind: true,
					// Function that fires when user clicks the button
					listeners: {
						click: function()
						{
							doLoginForm(login, win);
						}
					}
				}]
		});

		// This just creates a window to wrap the login form.
		// The login object is passed to the items collection.
		var win = new Ext.Window({
			id: 'login-win',
			layout:'fit',
			width:300,
			height:150,
			closable: false,
			resizable: false,
			plain: true,
			border: false,
			modal: true,
			items: [login]
		});
		win.show();
		Ext.getCmp('loginUsername').focus('', 10);
	}

	function doLoginForm(login, win)
	{
		login.getForm().submit({
			method:'POST',
			waitTitle:'Connecting',
			waitMsg:'Sending data...',
			success:function()
			{
				win.hide();
			},
			failure:function(form, action)
			{
				if(action.failureType == 'server'){
					obj = Ext.util.JSON.decode(action.response.responseText);
					Ext.Msg.alert('Login Failed!', obj.errors.reason);
				}else{
					Ext.Msg.alert('Warning!', 'Authentication server is unreachable : ' + action.response.responseText);
				}
				login.getForm().reset();
				Ext.getCmp('loginUsername').focus('', 10);
			}
		});
	}

	function initializeDisplay()
	{
		updateTree();
		setTimeout("populateCenterPane()", 1000);
	}

	function updateTree()
	{
		if (simpleTreeCollection != "")
		{
			//simpleTreeCollection.remove();
		}
		$.ajax({
			type: "GET",
			url: "php/populateCGAdminTree.php",
			contentType:'html',
			cache:false,
			success: function(response){
				var treestring = "<ul id='browser' class='simpleTree'><li class='root' id='Eldritch'><span>Eldritch</span><ul>" +
								 response +
								"</ul></li></ul>";
				$('#treeholder').html(treestring);
				createTree();
			}
		});
	}

	function createTree()
	{
		simpleTreeCollection = $('.simpleTree').simpleTree({
			animate: true,
			autoclose: false,
			drag: false,
			afterClick:function(node){ return clickTree(node); }
		});

		if (CurrName != "")
		{
			var currtree = simpleTreeCollection[0];
			var tmpName = CurrName.replace(/ /g, "");

			if (tmpName != "Qualities" && tmpName != "Drawbacks" && tmpName != "Users")
			{
				currtree.nodeToggle($('#' + $('#' + tmpName, currtree).parent().parent().attr("id"), currtree)[0]);
				$('#' + tmpName + ' > span', currtree).trigger('click');
			}
		}
	}

	function clickTree(node)
	{
		var tmpname = node.find('>span').text();
		if (tmpname != CurrName)
		{
			if (modified)
			{
				Ext.Msg.confirm("Discard Changes?", "Would you like to discard changes?",
					function(id)
					{
						if (id == "yes")
						{
							CurrName = tmpname;
							populateCenterPane();
						}
						else
						{
							var currtree = simpleTreeCollection[0];
							var idname = CurrName.replace(/ /g, "");
							$('#' + idname + ' > span', currtree).trigger('click');
						}
					}
				);


			}
			else
			{
				CurrName = tmpname;
				populateCenterPane();
			}
		}
	}

	function populateCenterPane()
	{
		pnlDefaultError.collapse();
		$('#error-pane').html("");

		if (CurrName != "")
		{
			var currtree = simpleTreeCollection[0];
			var tmpName = CurrName.replace(/ /g, "");
			var parentName = $('#' + tmpName, currtree).parent().parent().attr("id");
		}

		var strhtml = "";
		if (CurrName == "" || CurrName == "Qualities" || CurrName == "Drawbacks" || CurrName == "Users" || CurrName == "Packages")
		{
			swapCenter(pnlDefaultCenter);
			strhtml = "<h3 align='center' style='padding-Top:"+ (document.getElementById('pnlCenterHolder').offsetHeight/3) +"px; font-size:24px; width: 700px'>Please select an item to modify or add.</h3>";
			$('#center-pane').html(strhtml);
		}
		else if (CurrName == "Add New Quality" || CurrName == "Add New Drawback")
		{
			swapCenter(pnlDefaultCenter);
			$('#center-pane').html(getQDDefaultHTML());
			var type;
			if (CurrName == "Add New Quality")
			{
				type = "QUAL";
			}
			else
			{
				type = "DRAW";
			}
			$('#subitemHolder').html(getQDSubItemHTML("", type, 1, "BASE", "", ""));
			$('#lblName1').hide();
			$('#txtNameSub1').hide();
			$('#chkSub1').hide();
			$('#btnQDDelSubs').hide();
			$('#btnQDDel').hide();
			$('#btnQDSubmit').val('Add');
		}
		else if (parentName == "Qualities")
		{
			swapCenter(pnlDefaultCenter);
			$('#center-pane').html(getQDDefaultHTML());
			populateQDData("QUAL");
		}
		else if (parentName == "Drawbacks")
		{
			swapCenter(pnlDefaultCenter);
			$('#center-pane').html(getQDDefaultHTML());
			populateQDData("DRAW");
		}
		else if (CurrName == "Administrators")
		{
			swapCenter(pnlDefaultCenter);
			$('#center-pane').html(getAdminDefaultHTML());
			populateAdminData();
		}
		else if (CurrName == "Players")
		{
			swapCenter(pnlDefaultCenter);
		}
		else if(parentName == "Packages")
		{
			swapCenter(pnlPkgCenter);
			setPkgDefaultHTML();
		}
		else
		{
			Ext.Msg.alert("Populating Error", "Error state while populating center pane. CurrName: " + CurrName);
		}
		setModified(false);
	}

	function swapCenter(newCenter)
	{
		var tempCenter = pnlCenterHolder.getComponent(0);
		if (newCenter != tempCenter)
		{
			pnlCenterHolder.remove(pnlCenterHolder.getComponent(0), false);
			tempCenter.hide();
			pnlCenterHolder.add(newCenter);
			pnlCenterHolder.doLayout();
			newCenter.show();
		}
	}

//--------------------------QD functions--------------------------
	function getQDDefaultHTML()
	{
		return '<form name="qdform">' +
			'    <input type="hidden" name="QDID" id="QDID" value="" />' +
			'    <label id="lblShortName" '+ getQDTooltip('shortname') +'>Short Name: </label><input type="text" name="txtQDShortName" id="txtQDShortName" size="6" maxlength="5" onkeydown="keydownCheck(event)" '+ getQDTooltip('shortname') +'>' +
			'    <label id="lblLongName" style="margin-left:15px" '+ getQDTooltip('longname') +'>Long Name: </label><input type="text" name="txtQDLongName" id="txtQDLongName" size="35" maxlength="34" onkeydown="keydownCheck(event)" '+ getQDTooltip('longname') +'>' +
			'    <input type="checkbox" name="chkReqNote" id="chkReqNote" onclick="setModified(true)" style="margin-left:15px" '+ getQDTooltip('reqnote') +'><label id="lblChkReqNote" style="margin-left:5px" '+ getQDTooltip('reqnote') +'>Requires Note</label></input>' +
			'    <input type="checkbox" name="chkPkgOnly" id="chkPkgOnly" onclick="setModified(true)" style="margin-left:15px" '+ getQDTooltip('pkgonly') +'><label id="lblChkPkgOnly" style="margin-left:5px" '+ getQDTooltip('pkgonly') +'>Package Only</label></input>' +
			'    <br><br>' +
			'	 <div id="subitemHolder">' +
			'    </div>' +
			'    <br>' +
			'	 <div align="left">' +
			'    <input type="button" name="btnQDAddSub" id="btnQDAddSub" value= "Add Sub-item" onclick="addQDSubItem()" '+ getQDTooltip('addsub') +'>' +
			'    <input type="button" name="btnQDDelSubs" id="btnQDDelSubs" value= "Delete Sub-Item(s)" onclick="deleteQDSubItems()" style="margin-left:5px" '+ getQDTooltip('delsub') +'>' +
			'    </div>' +
			'    <br>' +
			'    <label id="lblDesc" '+ getQDTooltip('desc') +'>Description:</label><br>' +
			'    <textarea name="txtareaQDDescription" id="txtareaQDDescription" cols="80" rows="5" onkeydown="keydownCheck(event)" '+ getQDTooltip('desc') +'></textarea>' +
			'    <br><br>' +
			'    <hr> ' +
			'    <div align="left">' +
			'    <input type="button" name="btnQDSubmit" id="btnQDSubmit" value="Submit" onclick="submitQD(false, true)" '+ getQDTooltip('submit') +'>' +
			'    <input type="button" name="btnQDDel" id="btnQDDel" value="Delete" onclick="deleteQD()" style="margin-left:5px" '+ getQDTooltip('delete') +'>' +
			'    <input type="button" name="btnQDReset" id="btnQDReset" value="Reset" onclick="resetQD()" style="margin-left:5px" '+ getQDTooltip('reset') +'>' +
			'    <input type="button" name="btnQDCheckVal" id="btnQDCheckVal" value="Check" onclick="submitQD(true, false)" style="margin-left:5px" '+ getQDTooltip('check') +'>' +
			'    </div>' +
			'</form>';
	}

	function populateQDData(type)
	{
		$.post("php/populateCGAdminData.php",{
					TYPE: type,
					NAME: CurrName
				}, function(xml)
				{
					if (xml.indexOf("Error") == -1)
					{
						var qdid = $("qdid",xml).text();
						var shortname = $("shortname",xml).text();
						var longname = $("longname",xml).text();
						var desc = $("desc",xml).text();
						if ($("reqnote",xml).text() == "1")
						{
							$("#chkReqNote").attr('checked', true);
						}
						if ($("pkgonly",xml).text() == "1")
						{
							$("#chkPkgOnly").attr('checked', true);
						}
						$('#QDID').val(qdid);
						$('#txtQDShortName').val(shortname);
						$('#txtQDLongName').val(longname);
						$('#txtareaQDDescription').val(desc);

						var subitemstext = "";
						var i = 0;
						var subitems = $("subitem",xml);
						var subitemcount = subitems.length;
						if (subitemcount > 1)
						{
							subitemstext = "Sub-Items:<br>\n";
							$('#btnQDDelSubs').show();
						}
						else
						{
							$('#btnQDDelSubs').hide();
						}
						subitems.each(
							function(idx)
							{
								var subitem = subitems.get(idx);
								i++;
								highSub = i;
								var subname = $("subname",subitem).text();
								var subval = $("value",subitem).text();
								var subid = $("subid",subitem).text();

								subitemstext = getQDSubItemHTML(subitemstext, type, highSub, subname, subval, subid);
							}
						);
						$('#subitemHolder').html(subitemstext);
						if (subitemcount == 1)
						{
							$('#lblName1').hide();
							$('#txtNameSub1').val("BASE");
							$('#txtNameSub1').hide();
							$('#chkSub1').hide();
							$('#btnQDDelSubs').hide();
						}

						$('#btnQDDel').show();
						$('#btnQDSubmit').val('Modify');
					}
					else
					{
						Ext.Msg.alert("QD Populate Error", "QD Populate Error: " + xml);
					}
				}
		);
	}

	function getQDSubItemHTML(subitemstext, type, idnum, subname, subval, subid)
	{
		subitemstext = subitemstext + "<div id='sub"+ idnum +"' class='subgroup'>\n" +
						'<input type="hidden" name="subID' + idnum +'" id="subID' + idnum +'" value="'+ subid +'" />' +
						'<input type="checkbox" name="chkSub'+ idnum + '" id="chkSub'+ idnum + '" value="chkSub' + idnum + '" '+ getQDTooltip('subcheck') +'>' +
						'<label id="lblName'+ idnum +'" style = "margin-left:5px" '+ getQDTooltip('subname') +'>Name: </label><input type="text" name="txtNameSub'+ idnum + '" id="txtNameSub'+ idnum + '" size="35" maxlength="34" value ="'+ subname +'" onkeydown="keydownCheck(event)" style="margin-right:15px" '+ getQDTooltip('subname') +'>';
		if (type == "QUAL")
		{
			subitemstext = subitemstext + '<label id="lblVal'+ idnum +'" '+ getQDTooltip('subcost') +'>Cost: </label>';
		}
		else if (type == "DRAW")
		{
			subitemstext = subitemstext + '<label id="lblVal'+ idnum +'" '+ getQDTooltip('subcost') +'>Bonus: </label>';
		}
		subitemstext = subitemstext +
						'<input type="text" name="txtCostSub'+ idnum +'" id="txtCostSub'+ idnum +'" size="16" maxlength="15" value ="'+ subval +'" onkeydown="keydownCheck(event)" '+ getQDTooltip('subcost') +'>';

		subitemstext = subitemstext + "</div>\n";
		return subitemstext;
	}

	function getQDTooltip(item)
	{
		if (item == "shortname")
		{
			return 'ext:qtitle="Short Name" ext:qwidth="200" ext:qtip="A text field for a five character or less abbreviated name for this trait. This name must be unique in the database."';
		}
		else if (item == "longname")
		{
			return 'ext:qtitle="Long Name" ext:qwidth="200" ext:qtip="A text field for the long name for the trait. This name must be unique in the database."';
		}
		else if (item == "reqnote")
		{
			return 'ext:qtitle="Requires Note" ext:qwidth="200" ext:qtip="If checked, this trait requires further description on adding to the sheet (i.e. the type of Contact for a Contacts Quality)"';
		}
		else if (item == "pkgonly")
		{
			return 'ext:qtitle="Package Only" ext:qwidth="200" ext:qtip="If checked, this trait is only available in packages."';
		}
		else if (item == "desc")
		{
			return 'ext:qtitle="Description" ext:qwidth="200" ext:qtip="A text field for entering a description of the trait"';
		}
		else if (item == "submit")
		{
			 return 'ext:qtitle="Add/Modify Button" ext:qwidth="200" ext:qtip="Checks for valid values and then uploads the addition or changes to the database."';
		}
		else if (item == "delete")
		{
			return 'ext:qtitle="Delete Button" ext:qwidth="200" ext:qtip="Permanently removes the currently displayed item from the database."';
		}
		else if (item == "reset")
		{
			return 'ext:qtitle="Reset Button" ext:qwidth="200" ext:qtip="Resets the form to the default state."';
		}
		else if (item == "check")
		{
			return 'ext:qtitle="Check Values" ext:qwidth="200" ext:qtip="Checks that the current values are valid and shows any errors in the error pane."';
		}
		else if (item == "addsub")
		{
			return 'ext:qtitle="Add Sub-item" ext:qwidth="200" ext:qtip="Adds a new blank sub-item."';
		}
		else if (item == "delsub")
		{
			return 'ext:qtitle="Delete Sub-items" ext:qwidth="200" ext:qtip="Deletes the checked sub-items."';
		}
		else if (item == "subcheck")
		{
			return 'ext:qtitle="Sub-item Check" ext:qwidth="200" ext:qtip="Check these to enable the delete sub-items button."';
		}
		else if (item == "subname")
		{
			return  'ext:qtitle="Sub-item Name" ext:qwidth="200" ext:qtip="A text field for a sub-item name. This name must be unique for a given trait in the database."';
		}
		else if (item == "subcost")
		{
			return 'ext:qtitle="Trait Values" ext:qwidth="325" ext:qtip="' +
					'A text field for entering the value of a trait or sub-item in a trait. This value must have numeric values from 1 to 50 and be in one of the following formats:<br><br>' +
					'[single value] - for traits with a fixed value (i.e. 5)<br><br>'+
					'[list of |-separated values] - for a trait with multiple fixed costs (i.e. 2|5|9)<br><br>'+
					'[range as low-val:high-val] - for traits with a wide range (i.e. 1:10)<br><br>' +
					'[value]/level - for traits with fixed value per level and no maximum level (i.e. 2/level)"';
		}
		else
		{
			return '';
		}
	}

	function addQDSubItem()
	{
		if (CurrName != "")
		{
			var currtree = simpleTreeCollection[0];
			var tmpName = CurrName.replace(/ /g, "");
			var parentName = $('#' + tmpName, currtree).parent().parent().attr("id");
		}
		var type = "";
		if (parentName == "Qualities")
		{
			type = "QUAL";
		}
		else if (parentName == "Drawbacks")
		{
			type = "DRAW";
		}
		var numsubs = $('#subitemHolder > div').length;
		var newnum = numsubs + 1;
		highSub++;
		var subitemstext = "";
		if (numsubs > 1)
		{
			subitemstext = getQDSubItemHTML("", type, highSub, "", "", "");
			$('#subitemHolder').append(subitemstext);
		}
		else
		{
			var subname = $('input[id*="txtNameSub"]').val();
			var subval = $('input[id*="txtCostSub"]').val();
			var subID = $('input[id*="subID"]').val();
			subitemstext = getQDSubItemHTML("Sub-Items:<br>\n", type, highSub, subname, subval, subID);
			highSub++;
			subitemstext = getQDSubItemHTML(subitemstext, type, highSub, "", "", "");
			$('#subitemHolder').html(subitemstext);
		}
		$('#btnQDDelSubs').show();
		setModified(true);
	}

	function deleteQDSubItems()
	{
		if (CurrName != "")
		{
			var currtree = simpleTreeCollection[0];
			var tmpName = CurrName.replace(/ /g, "");
			var parentName = $('#' + tmpName, currtree).parent().parent().attr("id");
		}
		var type = "";
		if (parentName == "Qualities")
		{
			type = "QUAL";
		}
		else if (parentName == "Drawbacks")
		{
			type = "DRAW";
		}

		//var checked = $('#subitemHolder > div > input:checked');
		var checked = $('input:checked', $('#subitemHolder')).parent();
		var checkedcount = checked.length;
		if (checkedcount == 0)
		{
			Ext.Msg.alert("Delete Sub-items Error", "You must check at least one sub-item to be deleted.");
		}
		else
		{
			Ext.Msg.confirm("Delete Items?", "Are you sure you would like to delete the checked sub-items?",
				function(id)
				{
					if (id == "yes")
					{
						var numsubs = $('#subitemHolder > div').length;
						var hidden = $(":hidden", checked);

						hidden.each(
							function(idx)
							{
								if (this.value != "")
								{
									SubToDel[SubToDel.length] = this.value;
								}
							}
						);
						checked.remove();

						if (checkedcount == numsubs)
						{
							highSub++;
							var subitemstext = getQDSubItemHTML("", type, highSub, "BASE", "", "");
							$('#subitemHolder').html(subitemstext);
							$('#lblName' + highSub).hide();
							$('#txtNameSub' + highSub).hide();
							$('#chkSub' + highSub).hide();
							$('#btnQDDelSubs').hide();
						}
						else if (checkedcount == (numsubs-1))
						{
							highSub++;
							var subval = $('input[id*="txtCostSub"]').val();
							var subID = $('input[id*="subID"]').val();
							subitemstext = getQDSubItemHTML("", type, highSub, "BASE", subval, subID);
							$('#subitemHolder').html(subitemstext);
							$('#lblName' + highSub).hide();
							$('#txtNameSub' + highSub).hide();
							$('#chkSub' + highSub).hide();
							$('#btnQDDelSubs').hide();
						}
						setModified(true);
					}
				}
			);

		}
	}

	function resetQD()
	{
		Ext.Msg.confirm("Reset?", "Are you sure you would like to discard changes and reset to starting values?",
			function(id)
			{
				if (id == "yes")
				{
					var currtree = simpleTreeCollection[0];
					var idname = CurrName.replace(/ /g, "");
					CurrName = "";
					setModified(false);
					$('#' + idname + ' > span', currtree).trigger('click');
				}
			}
		);
	}

	function deleteQD()
	{
		Ext.Msg.confirm("Delete?", "Are you sure you would like to delete this item permanently from the database?",
			function(id)
			{
				if (id == "yes")
				{
					var idNum = $('#QDID').val();
					if (idNum != "")
					{
						$.post("php/deleteCGAdminData.php",{
								TYPE: 'QD',
								ID: idNum
							}, function(ret) {
								if (ret == "Success")
								{
									var currtree = simpleTreeCollection[0];
									var tmpName = CurrName.replace(/ /g, "");
									CurrName = "";
									initializeDisplay();
								}
								else
								{
									Ext.Msg.alert("Delete QD Error", ret);
								}
							}
						);
					}
					else
					{
						Ext.Msg.alert("Delete QD Error", "No ID was found. So no delete can be done. You may have to edit the database by hand.");
					}
				}
			}
		);
	}

	function submitQD(showSuccess, insertOnSuccess)
	{
		errorList = new Array();

		validateQDShortName(showSuccess, insertOnSuccess);
	}

	function validateQDShortName(showSuccess, insertOnSuccess)
	{
		var tmpID = trimString($('#QDID').val());
		var tmpShortName = $('#txtQDShortName').val();
		tmpShortName = trimString(tmpShortName.toUpperCase()).replace(/ /g, "_");

		if (tmpShortName != "")
		{
			var idval=$('#QDID').val();
			$.post("php/validateCGAdminData.php",{
					TYPE: 'QDSHORT',
					ID: idval,
					VAL: tmpShortName
				}, function(ret) {
					if (ret == "Success")
					{
						$('#txtQDShortName').val(tmpShortName);
					}
					else
					{
						errorList[errorList.length] = "The short name already exists in the database.";
					}
					validateQDLongName(showSuccess, insertOnSuccess);
				}
			);
		}
		else
		{
			errorList[errorList.length] = "The short name cannot be blank.";
			validateQDLongName(showSuccess, insertOnSuccess);
		}
	}

	function validateQDLongName(showSuccess, insertOnSuccess)
	{
		var tmpLongName = capWords(trimString($('#txtQDLongName').val()));
		if (tmpLongName != "")
		{
			var idval=$('#QDID').val();
			$.post("php/validateCGAdminData.php",{
					TYPE: 'QDLONG',
					ID: idval,
					VAL: tmpLongName
				}, function(ret) {
					if (ret == "Success")
					{
						$('#txtQDLongName').val(tmpLongName);
					}
					else
					{
						errorList[errorList.length] = "The long name already exists in the database.";
					}
					validateQDSub();
					handleError();
					afterQDValidate(showSuccess, insertOnSuccess);
				}
			);
		}
		else
		{
			errorList[errorList.length] = "The long name cannot be blank.";
			validateQDSub();
			handleError();
			afterQDValidate(showSuccess, insertOnSuccess);
		}
	}

	function validateQDSub()
	{
		var divID = new Array();
		var subID = new Array();
		var subName = new Array();
		var subCost = new Array();

		var i = 0;
		var subitems = $('#subitemHolder > div');
		subitems.each(
			function(idx)
			{
				divID[i] = subitems[idx].id.replace("sub", "");
				subID[i] = trimString($('#subID' + divID[i]).val());
				subName[i] = capWords(trimString($('#txtNameSub' + divID[i]).val()));
				subCost[i] = $('#txtCostSub' + divID[i]).val().replace(/ /g, "").toLowerCase();
				i++;
			}
		);

		for (i = 0; i < subID.length; i++)
		{
			if (subName[i] != "")
			{
				var goodVal = true;
				for (j = i + 1; j < subID.length; j++)
				{
					if (subName[i] == subName[j])
					{
						goodVal = false;
						errorList[errorList.length] = "The name " + subName[i] + " in sub-item " + (i+1) + " is duplicated. Sub-item names must be unique.";
						break;
					}
				}
				if (goodVal)
				{
					$('#txtNameSub' + divID[i]).val(subName[i]);
				}
			}
			else
			{
				errorList[errorList.length] = "The name in sub-item " + (i + 1) + " cannot be blank.";
			}

			validateQDCost(subCost[i], divID[i], i, divID);
		}
	}

	function validateQDCost(val, idx, i, divid)
	{
		if (val != "")
		{
			var goodVal = false;
			if (val.match("/level$")=="/level")
			{
				if (validateQDCostValue(val.substring(0,val.indexOf("/level")), idx, i, divid))
				{
					goodVal = true;
				}
			}
			else if (val.indexOf(':') != -1)
			{
				var left = val.substring(0,val.indexOf(":"));
				var right = val.substr(val.indexOf(":")+1,val.length);

				if (validateQDCostValue(left, idx, i, divid) && validateQDCostValue(right, idx, i, divid))
				{
					if (right > left)
					{
						goodVal = true;
					}
					else
					{
						validateQDCostError(i, divid);
					}
				}
			}
			else if (val.indexOf("|") != -1)
			{
				var goodVals = true;
				var vals = new Array();
				vals = val.split("|");
				for (i = 0; i < vals.length; i++)
				{
					if (!validateQDCostValue(vals[i], idx, i, divid))
					{
						goodVals = false;
						break;
					}
				}
				if (goodVals)
				{
					goodVal = true;
				}
			}
			else
			{
				if (validateQDCostValue(val, idx, i, divid))
				{
					goodVal = true;
				}
			}

			if (goodVal)
			{
				$('#txtCostSub' + idx).val(val);
			}
		}
		else
		{
			validateQDCostError(i, divid);
		}
	}

	function validateQDCostValue(val, idx, i, divid)
	{
		if (val > 0 && val < 51)
		{
			return true;
		}
		else
		{
			validateQDCostError(i, divid);
			return false;
		}
	}

	function validateQDCostError(i, divid)
	{
		if (divid.length > 1)
		{
			i++;
			errorList[errorList.length] = "The value in sub-item " + i  + " is invalid. See the tooltip for valid formats.";
		}
		else
		{
			errorList[errorList.length] = "The current value is invalid. See the tooltip for valid value formats.";
		}
	}

	function afterQDValidate(showSuccess, insertOnSuccess)
	{
		if (errorList.length == 0)
		{
			if (showSuccess)
			{
				setTimeout("Ext.Msg.alert('Validation','Current values are valid.')", 100);
			}
			if (insertOnSuccess)
			{
				insertQD();
			}
		}
	}

	function insertQD()
	{
		if (CurrName != "")
		{
			var currtree = simpleTreeCollection[0];
			var tmpName = CurrName.replace(/ /g, "");
			var parentName = $('#' + tmpName, currtree).parent().parent().attr("id");
		}
		var type = "";
		if (CurrName == "Add New Quality")
		{
			type = "QUAL";
		}
		else if (CurrName == "Add New Drawback")
		{
			type = "DRAW";
		}
		else if (parentName == "Qualities")
		{
			type = "QUAL";
		}
		else if (parentName == "Drawbacks")
		{
			type = "DRAW";
		}

		var id=$('#QDID').val();
		var shortName=$('#txtQDShortName').val();
		var longName=$('#txtQDLongName').val();
		var desc=$('#txtareaQDDescription').val();

		var reqNote = "";
		if ($("#chkReqNote").attr('checked'))
		{
			reqNote = 1;
		}
		else
		{
			reqNote = 0;
		}
		var pkgOnly = "";
		if ($("#chkPkgOnly").attr('checked'))
		{
			pkgOnly = 1;
		}
		else
		{
			pkgOnly = 0;
		}

		$.post("php/insertCGAdminData.php",{
				TYPE: type,
				ID: id,
				SHORTNAME: shortName,
				LONGNAME: longName,
				REQNOTE: reqNote,
				PKGONLY: pkgOnly,
				DESC: desc
			}, function(ret) {
				if (ret != "Failure")
				{
					id = ret;
					insertQDSubs(id);
				}
				else
				{
					Ext.Msg.alert("Insert QD Error", ret);
				}
			}
		);
	}

	function insertQDSubs(qdID)
	{
		var divID = new Array();
		var subID = new Array();
		var subName = new Array();
		var subCost = new Array();

		var i = 0;
		var subitems = $('#subitemHolder > div');
		subitems.each(
			function(idx)
			{
				divID[i] = subitems[idx].id.replace("sub", "");
				subID[i] = $('#subID' + divID[i]).val();
				subName[i] = $('#txtNameSub' + divID[i]).val();
				subCost[i] = $('#txtCostSub' + divID[i]).val();
				i++;
			}
		);

		$.post("php/insertCGAdminData.php",{
				TYPE: "QDSUB",
				ID: qdID,
				SUBID: subID.join("|"),
				SUBNAME: subName.join("|"),
				SUBCOST: subCost.join("|")
			}, function(ret) {
				if (ret == "Success")
				{
					$.post("php/deleteCGAdminData.php",{
							TYPE: "QDSUB",
							ID: SubToDel.join("|")
						}, function(ret) {
							if (ret == "Success")
							{
								Ext.Msg.alert("Update Successful", "Trait successfully updated in database.");
								SubToDel = new Array();
								CurrName = $('#txtQDLongName').val();
								setModified(false);
								initializeDisplay();
							}
							else
							{
								Ext.Msg.alert("Delete Sub-items Error", ret);
							}
						}
					);
				}
				else
				{
					Ext.Msg.alert("Update Sub-items Error",ret);
				}
			}
		);
	}

//--------------------------QD functions--------------------------
	function setPkgDefaultHTML()
	{
		$('#pkg-basics').html(
				'    <input type="hidden" name="PKGID" id="PKGID" value="" />' +
				'    <label id="lblShortName" '+ getPKGTooltip('shortname') +'>Short Name: </label><input type="text" name="txtPKGShortName" id="txtPKGShortName" size="6" maxlength="5" onkeydown="keydownCheck(event)" '+ getPKGTooltip('shortname') +'>' +
				'    <label id="lblLongName" style="margin-left:15px" '+ getPKGTooltip('longname') +'>Long Name: </label><input type="text" name="txtPKGLongName" id="txtPKGLongName" size="35" maxlength="34" onkeydown="keydownCheck(event)" '+ getPKGTooltip('longname') +'>' +
				'    <br>' +
				'    <label id="lblDesc" '+ getPKGTooltip('desc') +'>Description:</label><br>' +
				'    <textarea name="txtareaPKGDescription" id="txtareaPKGDescription" cols="80" rows="5" onkeydown="keydownCheck(event)" '+ getPKGTooltip('desc') +'></textarea>'
		);
		$('#pkg-btns').html(
				'    <div align="left">' +
				'    <input type="button" name="btnPKGSubmit" id="btnPKGSubmit" value="Submit" onclick="submitPKG(false, true)" '+ getPKGTooltip('submit') +'>' +
				'    <input type="button" name="btnPKGDel" id="btnPKGDel" value="Delete" onclick="deletePKG()" style="margin-left:5px" '+ getPKGTooltip('delete') +'>' +
				'    <input type="button" name="btnPKGReset" id="btnPKGReset" value="Reset" onclick="resetPKG()" style="margin-left:5px" '+ getPKGTooltip('reset') +'>' +
				'    <input type="button" name="btnPKGCheckVal" id="btnPKGCheckVal" value="Check" onclick="submitPKG(true, false)" style="margin-left:5px" '+ getPKGTooltip('check') +'>' +
				'    </div>'
		);
		$('#pkg-qualtree').html('');
		$('#pkg-qualtree-btns').html(
				'    <input type="button" name="btnPKGQualInfo" id="btnPKGQualInfo" value="Information" onclick="infoPKGQD()" '+ getPKGTooltip('pkgqualinfo') +'>' +
				'    <input type="button" name="btnPKGAddQual" id="btnPKGAddQual" value="Add Selected" onclick="addPKGQD()" style="margin-left:10px; margin-bottom:5px" '+ getPKGTooltip('addpkgqual') +'>' +
				'    <br>' +
				'    <input type="button" name="btnPKGAddQualChoice" id="btnPKGAddQualChoice" value="Add Choice" onclick="addPKGChoice()" '+ getPKGTooltip('addpkgqualchoice') +'>' +
				'    <input type="button" name="btnPKGAddQualPool" id="btnPKGAddQualPool" value="Add Pool" onclick="addPKGPool()" style="margin-left:5px"'+ getPKGTooltip('addpkgqualpool') +'>'
		);
		$('#pkg-quallist').html('');
		$('#pkg-quallist-btns').html('<input type="button" name="btnPKGQualRemove" id="btnPKGQualRemove" value="Remove" onclick="removePKGQual()" '+ getPKGTooltip('removePKGQual') +'>');

		$('#pkg-error-pane').html('');
	}

	function pkgDrawActivated()
	{
		if (!pkgDrawInitialized)
		{
			$('#pkg-drawtree').html('');
			$('#pkg-drawtree-btns').html(
					'    <input type="button" name="btnPKGDrawInfo" id="btnPKGDrawInfo" value="Information" onclick="infoPKGQD()" '+ getPKGTooltip('pkgdrawinfo') +'>' +
					'    <input type="button" name="btnPKGAddDraw" id="btnPKGAddDraw" value="Add Selected" onclick="addPKGQD()" style="margin-left:10px; margin-bottom:5px" '+ getPKGTooltip('addpkgdraw') +'>' +
					'    <br>' +
					'    <input type="button" name="btnPKGAddDrawChoice" id="btnPKGAddDrawChoice" value="Add Choice" onclick="addPKGChoice()" '+ getPKGTooltip('addpkgdrawchoice') +'>' +
					'    <input type="button" name="btnPKGAddDrawPool" id="btnPKGAddDrawPool" value="Add Pool" onclick="addPKGPool()" style="margin-left:5px"'+ getPKGTooltip('addpkgdrawpool') +'>'
			);

			$('#pkg-drawlist').html('');
			$('#pkg-drawlist-btns').html('<input type="button" name="btnPKGDrawRemove" id="btnPKGDrawRemove" value="Remove" onclick="removePKGDraw()" '+ getPKGTooltip('removePKGDraw') +'>');
			pkgDrawInitialized = true;
		}
	}

	function getPKGTooltip(item)
	{
		return "";
	}

//--------------------------Admin functions--------------------------
	function getAdminDefaultHTML()
	{
		return '<form name="adminform">' +
			'    <input type="hidden" name="ADMINID" id="ADMINID" value="" />' +
			'	 <div id="adminHolder">' +
			'    </div>' +
			'    <br>' +
			'	 <div id="adminUserText">' +
			'    <label id="lblAdminUser" '+ getAdminTooltip('user') +'>User Name: </label><input type="text" name="txtAdminUser" id="txtAdminUser" size="35" maxlength="34" style="margin:5px;margin-left:27px" '+ getAdminTooltip('user') +'>' +
			'    <br>' +
			'    </div>' +
			'    <label id="lblAdminPass" '+ getAdminTooltip('pass') +'>New Password: </label><input type="password" name="txtAdminPass" id="txtAdminPass" size="35" maxlength="34" style="margin:5px;margin-left:5px" '+ getAdminTooltip('pass') +'>' +
			'    <hr> ' +
			'    <div align="left">' +
			'    <input type="button" name="btnAdminSubmit" id="btnAdminSubmit" value="Submit" onclick="submitAdmin()" '+ getAdminTooltip('submit') +'>' +
			'    <input type="button" name="btnAdminDel" id="btnAdminDel" value="Delete" onclick="deleteAdmin()" style="margin-left:5px" '+ getAdminTooltip('delete') +'>' +
			'    </div>' +
			'</form>';
	}

	function populateAdminData()
	{
		$.post("php/populateCGAdminData.php",{
					TYPE: "ADMIN",
					NAME: ""
				}, function(xml) {

				var userstext = '<label id="lblAdminUsers" '+ getAdminTooltip("list") + '>Administrative Users:</label>' +
							'<select name="lstAdminUsers" id ="lstAdminUsers" onchange=changeAdminList() style="margin-left:5px"'+ getAdminTooltip("list") +'>\n' +
							'\t<option value="Add New User" id="" selected=true>Add New User</option>';

				var users = $("user",xml);
				var usercount = users.length;
				users.each(
					function(idx)
					{
						var user = users.get(idx);
						var userid = $("id",user).text();
						var username = $("name",user).text();
						userstext = userstext + '\t<option value="'+ username + '" id="'+ userid +'">'+ username +'</option>\n';
					}
				);

				userstext = userstext + '</select>';

				$('#adminHolder').html(userstext);

				$('#btnAdminSubmit').val("Add New Admin");
				$('#adminUserText').show();
				$('#btnAdminDel').hide();
			}
		);
	}

	function getAdminTooltip(item)
	{
		if (item == "user")
		{
			return 'ext:qtitle="User Name" ext:qwidth="200" ext:qtip="A text field for entering a user name when adding a new administrator."';
		}
		else if (item == "pass")
		{
			return 'ext:qtitle="Password" ext:qwidth="200" ext:qtip="A text field for adding/modifying an administrator password."';
		}
		else if (item == "submit")
		{
			return 'ext:qtitle="Add Administrator or Change Password" ext:qwidth="200" ext:qtip="A button that will either try to add a new admin or else change the password of an existing one."';
		}
		else if (item == "delete")
		{
			return 'ext:qtitle="Delete" ext:qwidth="200" ext:qtip="A button to remove an existing administrator from the database."';
		}
		else if (item == "list")
		{
			return 'ext:qtitle="Administrator List" ext:qwidth="200" ext:qtip="A listbox for selecting existing administrator or adding a new one."';
		}
	}

	function submitAdmin()
	{
		errorList = new Array();

		var idNum = $('#ADMINID').val();
		var user = "";
		var pass = "";

		if (idNum != "")
		{
			user = $('#lstAdminUsers').val();
			pass = $('#txtAdminPass').val();
			if (pass == "")
			{
				errorList[errorList.length] = "Blank passwords are not allowed.";
			}
		}
		else
		{
			user = $('#txtAdminUser').val();
			pass = $('#txtAdminPass').val();

			if (user != "")
			{
				$('#lstAdminUsers > option').each(
					function(idx)
					{
						if (user == this.value)
						{
							errorList[errorList.length] = "Duplicate user names are not allowed.";
						}
					}
				);
			}
			else
			{
				errorList[errorList.length] = "Blank user names are not allowed.";
			}
			if (pass != "")
			{
			}
			else
			{
				errorList[errorList.length] = "Blank passwords are not allowed.";
			}
		}

		handleError();

		if (errorList.length == 0)
		{
			$.post("php/insertCGAdminData.php",{
					TYPE: "ADMIN",
					ID: idNum,
					USER: user,
					PASS: pass
				}, function(ret) {
					if (ret == "Success")
					{
						Ext.Msg.alert("Update Successful", "Trait successfully updated in database.");
						initializeDisplay();
					}
					else
					{
						Ext.Msg.alert("Insert ADMIN Error", ret);
					}
				}
			);
		}
	}

	function deleteAdmin()
	{
		Ext.Msg.confirm("Delete?", "Are you sure you would like to delete this item permanently from the database?",
			function(id)
			{
				if (id == "yes")
				{
					var idNum = $('#ADMINID').val();
					if (idNum != "")
					{
						$.post("php/deleteCGAdminData.php",{
								TYPE: 'ADMIN',
								ID: idNum
							}, function(ret) {
								if (ret == "Success")
								{
									initializeDisplay();
								}
								else
								{
									Ext.Msg.alert("Delete ADMIN Error", ret);
								}
							}
						);
					}
					else
					{
						Ext.Msg.alert("Delete ADMIN Error", "No ID was found. So no delete can be done. You may have to edit the database by hand.");
					}
				}
			}
		);
	}

	function changeAdminList()
	{
		var val = $('#lstAdminUsers').val();
		var id = $('option[value="'+ val +'"]')[0].id;
		$('#ADMINID').val(id);
		if (val == "Add New User")
		{
			$('#btnAdminSubmit').val("Add New Admin");
			$('#adminUserText').show();
			$('#btnAdminDel').hide();
		}
		else
		{
			$('#btnAdminSubmit').val("Change Password");
			$('#adminUserText').hide();
			$('#btnAdminDel').show();
		}
	}

//--------------------------Helper functions--------------------------
	function getElementHeight(Elem)
	{
		if (ns4) {
			var elem = getObjNN4(document, Elem);
			return elem.clip.height;
		} else {
			if(document.getElementById) {
				var elem = document.getElementById(Elem);
			} else if (document.all){
				var elem = document.all[Elem];
			}
			var xPos;
			if (op5) {
				xPos = elem.style.pixelHeight;
			} else {
				xPos = elem.offsetHeight;
			}
			return xPos;
		}
	}

	function getElementWidth(Elem)
	{
		if (ns4) {
			var elem = getObjNN4(document, Elem);
			return elem.clip.width;
		} else {
			if(document.getElementById) {
				var elem = document.getElementById(Elem);
			} else if (document.all){
				var elem = document.all[Elem];
			}
			var xPos;
			if (op5) {
				xPos = elem.style.pixelWidth;
			} else {
				xPos = elem.offsetWidth;
			}
			return xPos;
		}
	}

	function trimString(val)
	{
		return val.replace(/^\s+|\s+$/g,"");
	}

	function capWords(input)
	{
		var tmpStr, tmpChar, preString, postString, strlen;
		tmpStr = input.toLowerCase();
		var stringLen = tmpStr.length;
		if (stringLen > 0)
		{
		  for (i = 0; i < stringLen; i++)
		  {
			if (i == 0)
			{
			  tmpChar = tmpStr.substring(0,1).toUpperCase();
			  postString = tmpStr.substring(1,stringLen);
			  tmpStr = tmpChar + postString;
			}
			else
			{
			  tmpChar = tmpStr.substring(i,i+1);
			  if (tmpChar == " " && i < (stringLen-1))
			  {
			  tmpChar = tmpStr.substring(i+1,i+2).toUpperCase();
			  preString = tmpStr.substring(0,i+1);
			  postString = tmpStr.substring(i+2,stringLen);
			  tmpStr = preString + tmpChar + postString;
			  }
			}
		  }
		}
		return tmpStr;
	}

	function keydownCheck(e)
	{
		var keynum;
		if(window.event) // IE
		{
			keynum = e.keyCode;
		}
		else if(e.which) // Netscape/Firefox/Opera
		{
			keynum = e.which;
		}
		var keychar = String.fromCharCode(keynum);
		var wordcheck = /\w/;
		var isword = wordcheck.test(keychar);
		var keynums = [8,46,107,109,188,190,191,219,220,221];
		//modify on alphanum, space, backspace, delete, \, ], [, =, -, <period>, <comma>, and /
		if (isword || keychar == " " || keynums.indexOf(keynum) != -1)
		{
			setModified(true);
		}
	}

	function setModified(value)
	{
		modified = value;

		if (value)
		{
			$('#btnQDSubmit')[0].disabled = false;
			$('#btnQDReset')[0].disabled = false;
		}
		else
		{
			$('#btnQDSubmit')[0].disabled = true;
			$('#btnQDReset')[0].disabled = true;
		}
	}

	function handleError()
	{
		if (errorList.length == 0)
		{
			$('#error-pane').html("");
			pnlDefaultError.collapse();
		}
		else
		{
			var errorstring = "";
			for (i = 0; i < errorList.length; i++)
			{
				errorstring = errorstring + '<p style="color:red">Error: ' + errorList[i] + '</p>\n';
			}
			pnlDefaultError.expand();
			$('#error-pane').html(errorstring);
		}
	}