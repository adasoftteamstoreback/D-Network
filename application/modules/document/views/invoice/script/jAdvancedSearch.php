<script type="text/javascript">
	$(document).ready(function() {

		$('.selectpicker').selectpicker();

		$('#obtIVDocDateFrom').click(function() {
			event.preventDefault();
			$('#oetIVDocDateFrom').datepicker('show');
		});

		$('#obtIVDocDateTo').click(function() {
			event.preventDefault();
			$('#oetIVDocDateTo').datepicker('show');
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
	$('#oahIVAdvanceSearch').on('click', function() {
		if($('#odvIVAdvanceSearchContainer').hasClass('hidden')) {
			$('#odvIVAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
		}else{
			$("#odvIVAdvanceSearchContainer").slideUp(500,function() {
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
		$('#obtIVBrowseBchFrom').attr('disabled',true);
		$('#obtIVBrowseBchTo').attr('disabled',true);
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
			OrderBy             : ['TCNMBranch.FTBchCode DESC'],
		},
		CallBack: {
			ReturnType  : 'S',
			Value       : ["oetBchCodeTo", "TCNMBranch.FTBchCode"],
			Text        : ["oetBchNameTo", "TCNMBranch_L.FTBchName"],
		},
	}

	// Event Browse
	$('#obtIVBrowseBchFrom').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchFrom');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtIVBrowseBchTo').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchTo');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

	//ล้างข้อมูล
	function JSxIVClearSearchData() {
		var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){

            var nCountBch = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
            if(nCountBch != 1){ //ถ้ามีมากกว่า 1 สาขาต้อง reset 
                $("#oetBchCodeTo").val("");
                $("#oetBchNameTo").val(""); 
                $("#oetBchCodeFrom").val("");
                $("#oetBchNameFrom").val("");
            }

            $("#oetSearchAll").val("");
            $("#obtIVDocDateFrom").val("");
            $("#obtIVDocDateTo").val("");
            $(".xCNDatePicker").datepicker("setDate", null);
            $(".selectpicker").val("0").selectpicker("refresh");
            JSvIVCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
   }
</script>