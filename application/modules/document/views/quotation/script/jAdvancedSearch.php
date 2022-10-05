<script type="text/javascript">
	$(document).ready(function() {

		$('.selectpicker').selectpicker();

		$('#obtTQDocDateFrom').click(function() {
			event.preventDefault();
			$('#oetTQDocDateFrom').datepicker('show');
		});

		$('#obtTQDocDateTo').click(function() {
			event.preventDefault();
			$('#oetTQDocDateTo').datepicker('show');
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
	$('#oahTQAdvanceSearch').on('click', function() {
		if($('#odvTQAdvanceSearchContainer').hasClass('hidden')) {
			$('#odvTQAdvanceSearchContainer').removeClass('hidden fadeIn').addClass('fadeIn');
		}else{
            $('#odvTQAdvanceSearchContainer').addClass('hidden fadeIn');
		}
	});

	var tUsrLevel 	  	= "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?php echo $this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?php echo $this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtTQBrowseBchFrom').attr('disabled',true);
		$('#obtTQBrowseBchTo').attr('disabled',true);
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
	$('#obtTQBrowseBchFrom').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchFrom');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtTQBrowseBchTo').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchTo');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

	//ล้างข้อมูล
	function JSxTQClearSearchData() {
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
            $("#obtTQDocDateFrom").val("");
            $("#obtTQDocDateTo").val("");
            $(".xCNDatePicker").datepicker("setDate", null);
            $(".selectpicker").val("0").selectpicker("refresh");
            JSvQTCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
   }
</script>