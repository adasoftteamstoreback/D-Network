<script type="text/javascript">
	$(document).ready(function() {

		$('.selectpicker').selectpicker();

		$('#obtXphDocDateFrom').click(function() {
			event.preventDefault();
			$('#oetXphDocDateFrom').datepicker('show');
		});

		$('#obtXphDocDateTo').click(function() {
			event.preventDefault();
			$('#oetXphDocDateTo').datepicker('show');
		});

		$('.xCNDatePicker').datepicker({
			format: 'yyyy-mm-dd',
			todayHighlight: true,
			autoclose: true,
		});

		$(".selection-2").select2({
			dropdownParent: $('#dropDownSelect1')
		});

		$('#obtSearchDocDateFrom').click(function() {
			$('#oetSearchDocDateFrom').datepicker('show')
		});

		$('#obtSearchDocDateTo').click(function() {
			$('#oetSearchDocDateTo').datepicker('show')
		});

	});

	// Advance search display control
	$('#oahPCKAdvanceSearch').on('click', function() {
		if ($('#odvPCKAdvanceSearchContainer').hasClass('hidden')) {
			$('#odvPCKAdvanceSearchContainer').removeClass('hidden fadeIn').addClass('fadeIn');
		} else {
			$('#odvPCKAdvanceSearchContainer').addClass('hidden fadeIn');
		}
	});


	var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch = "<?php echo $this->session->userdata("nSesUsrBchCount"); ?>";
	var tWhere = "";

	if (nCountBch == 1) {
		$('#obtPCKBrowseBchFrom').attr('disabled', true);
		$('#obtPCKBrowseBchTo').attr('disabled', true);
	}
	if (tUsrLevel != "HQ") {
		tWhere = " AND TCNMBranch.FTBchCode IN (" + tBchCodeMulti + ") ";
	} else {
		tWhere = "  AND TCNMBranch.FTBchType != 4 ";
	}

	// Option Branch From
	var oPmhBrowseBchFrom = {
		Title: ['company/branch/branch', 'tBCHTitle'],
		Table: {
			Master: 'TCNMBranch',
			PK: 'FTBchCode'
		},
		Join: {
			Table: ['TCNMBranch_L'],
			On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits, ]
		},
		Where: {
			Condition: [tWhere]
		},
		GrideView: {
			ColumnPathLang: 'company/branch/branch',
			ColumnKeyLang: ['tBCHCode', 'tBCHName'],
			ColumnsSize: ['15%', '75%'],
			WidthModal: 50,
			DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
			DataColumnsFormat: ['', ''],
			Perpage: 10,
			OrderBy: ['TCNMBranch.FTBchCode DESC'],
		},
		NextFunc: {
			FuncName: 'JSxPCKCallbackBchFromList',
			ArgReturn: ['FTBchCode', 'FTBchName']
		},
		CallBack: {
			ReturnType: 'S',
			Value: ["oetBchCodeFrom", "TCNMBranch.FTBchCode"],
			Text: ["oetBchNameFrom", "TCNMBranch_L.FTBchName"],
		},
	}



	// Option Branch To
	var oPmhBrowseBchTo = {

		Title: ['company/branch/branch', 'tBCHTitle'],
		Table: {
			Master: 'TCNMBranch',
			PK: 'FTBchCode'
		},
		Join: {
			Table: ['TCNMBranch_L'],
			On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits, ]
		},
		Where: {
			Condition: [tWhere]
		},
		GrideView: {
			ColumnPathLang: 'company/branch/branch',
			ColumnKeyLang: ['tBCHCode', 'tBCHName'],
			ColumnsSize: ['15%', '75%'],
			WidthModal: 50,
			DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
			DataColumnsFormat: ['', ''],
			Perpage: 10,
			OrderBy: ['TCNMBranch.FTBchCode DESC'],
		},
		CallBack: {
			ReturnType: 'S',
			Value: ["oetBchCodeTo", "TCNMBranch.FTBchCode"],
			Text: ["oetBchNameTo", "TCNMBranch_L.FTBchName"],
		},
	}
	// Option Branch To

	// Event Browse
	$('#obtPCKBrowseBchFrom').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchFrom');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtPCKBrowseBchTo').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchTo');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});


	function JSxPCKCallbackBchFromList(params) {
		var aParam = JSON.parse(params);
		$('#oetBchCodeTo').val(aParam[0]);
		$('#oetBchNameTo').val(aParam[1]);
	}
</script>