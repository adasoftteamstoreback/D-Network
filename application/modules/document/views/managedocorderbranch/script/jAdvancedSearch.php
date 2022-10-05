<script type="text/javascript">
	$(document).ready(function() {

		$('.selectpicker').selectpicker();

		$('#obtSearchDocDateFrom').click(function() {
			event.preventDefault();
			$('#oetSearchDocDateFrom').datepicker('show');
		});

		$('#obtSearchDocDateTo').click(function() {
			event.preventDefault();
			$('#oetSearchDocDateTo').datepicker('show');
		});

		$('#obtSearchDocDateRef').click(function() {
			event.preventDefault();
			$('#oetSearchDocDateRef').datepicker('show');
		});

		$('.xCNDatePicker').datepicker({
			format			: 'yyyy-mm-dd',
			todayHighlight	: true,
			autoclose		: true
		});

		$(".selection-2").select2({
			dropdownParent: $('#dropDownSelect1')
		});

	});

	// Advance search display control
	$('#oahMNGAdvanceSearch').on('click', function() {
		if($('#odvMNGAdvanceSearchContainer').hasClass('hidden')) {
			$('#odvMNGAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
		}else{
			$("#odvMNGAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
		}
	});

	var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtMNGBrowseBchFrom').attr('disabled',true);
		$('#obtMNGBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

	// Option Branch From
	var oPmhBrowseBchFrom = {
		Title   : ['company/branch/branch', 'tBCHTitle'],
		Table   : {
			Master  : 'TCNMBranch',
			PK      : 'FTBchCode'
		},
		Join    : {
			Table   : ['TCNMBranch_L'],
			On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits, ]
		},
		Where   : {
			Condition : [tWhere]
		},
		GrideView : {
			ColumnPathLang   : 'company/branch/branch',
			ColumnKeyLang    : ['tBCHCode', 'tBCHName'],
			ColumnsSize      : ['15%', '75%'],
			WidthModal       : 50,
			DataColumns      : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
			DataColumnsFormat: ['', ''],
			Perpage          : 10,
			OrderBy          : ['TCNMBranch.FTBchCode ASC'],
		},
		CallBack: {
			ReturnType      : 'S',
			Value           : ["oetBchCodeFrom", "TCNMBranch.FTBchCode"],
			Text            : ["oetBchNameFrom", "TCNMBranch_L.FTBchName"],
		},
	}

	// Option Branch To
	var oPmhBrowseBchTo = {
		Title   : ['company/branch/branch', 'tBCHTitle'],
		Table   : {
			Master  : 'TCNMBranch',
			PK      : 'FTBchCode'
		},
		Join    : {
			Table   : ['TCNMBranch_L'],
			On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits, ]
		},
		Where   :{
			Condition : [tWhere]
		},
		GrideView   : {
			ColumnPathLang      : 'company/branch/branch',
			ColumnKeyLang       : ['tBCHCode', 'tBCHName'],
			ColumnsSize         : ['15%', '75%'],
			WidthModal          : 50,
			DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
			DataColumnsFormat   : ['', ''],
			Perpage             : 10,
			OrderBy             : ['TCNMBranch.FTBchCode ASC'],
		},
		CallBack: {
			ReturnType  : 'S',
			Value       : ["oetBchCodeTo", "TCNMBranch.FTBchCode"],
			Text        : ["oetBchNameTo", "TCNMBranch_L.FTBchName"],
		},
	}

	// Event Browse
	$('#obtMNGBrowseBchFrom').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchFrom');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtMNGBrowseBchTo').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchTo');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

	// Option SPL From
	var oPmhBrowseSPLFrom = {
		Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
		Table   : {Master:'TCNMSpl', PK:'FTSplCode'},
		Join    : {
			Table: ['TCNMSpl_L', 'TCNMSplCredit'],
			On: [
				'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
				'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
			]
		},
		Where:{
			Condition : [ " AND TCNMSpl.FTSplStaActive = '1' " ]
		},
		GrideView:{
			ColumnPathLang      : 'supplier/supplier/supplier',
			ColumnKeyLang       : ['tSPLTBCode', 'tSPLTBName'],
			ColumnsSize         : ['15%', '75%'],
			WidthModal          : 50,
			DataColumns         : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid'],
			DataColumnsFormat   : ['',''],
			DisabledColumns     : [2, 3, 4, 5 , 6 , 7],
			Perpage             : 10,
			OrderBy             : ['TCNMSpl.FDCreateOn DESC']
		},
		CallBack:{
			ReturnType          : 'S',
			Value               : ['oetSplCodeFrom',"TCNMSpl.FTSplCode"],
			Text                : ['oetSplNameFrom',"TCNMSpl_L.FTSplName"]
		}
	}

	// Option SPL To
	var oPmhBrowseSPLTo = {
		Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
		Table   : {Master:'TCNMSpl', PK:'FTSplCode'},
		Join    : {
			Table: ['TCNMSpl_L', 'TCNMSplCredit'],
			On: [
				'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
				'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
			]
		},
		Where:{
			Condition : [ " AND TCNMSpl.FTSplStaActive = '1' " ]
		},
		GrideView:{
			ColumnPathLang      : 'supplier/supplier/supplier',
			ColumnKeyLang       : ['tSPLTBCode', 'tSPLTBName'],
			ColumnsSize         : ['15%', '75%'],
			WidthModal          : 50,
			DataColumns         : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid'],
			DataColumnsFormat   : ['',''],
			DisabledColumns     : [2, 3, 4, 5 , 6 , 7],
			Perpage             : 10,
			OrderBy             : ['TCNMSpl.FDCreateOn DESC']
		},
		CallBack:{
			ReturnType          : 'S',
			Value               : ['oetSplCodeTo',"TCNMSpl.FTSplCode"],
			Text                : ['oetSplNameTo',"TCNMSpl_L.FTSplName"]
		}
	}

	// Event Browse
	$('#obtMNGBrowseSplFrom').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseSPLFrom');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtMNGBrowseSplTo').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseSPLTo');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

	//ล้างข้อมูล
	function JSxMNGClearSearchData() {
		var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){

            var nCountBch = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
            if(nCountBch != 1){ //ถ้ามีมากกว่า 1 สาขาต้อง reset 
                $("#oetBchCodeTo").val("");
                $("#oetBchNameTo").val(""); 
                $("#oetBchCodeFrom").val("");
                $("#oetBchNameFrom").val("");
            }

			$("#oetSplCodeTo , #oetSplNameTo").val("");
			$("#oetSplCodeFrom , #oetSplNameFrom").val("");

            $("#oetSearchAll").val("");
            $("#oetSearchDocDateFrom").val("");
            $("#oetSearchDocDateTo").val("");
			$("#oetSearchDocDateRef").val("");
            $(".xCNDatePicker").datepicker("setDate", null);
            $(".selectpicker").val("0").selectpicker("refresh");
            JSvMNGCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
   }
</script>