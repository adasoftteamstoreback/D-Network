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
	$('#oahCLMAdvanceSearch').on('click', function() {
		if($('#odvCLMAdvanceSearchContainer').hasClass('hidden')) {
			$('#odvCLMAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
		}else{
			$("#odvCLMAdvanceSearchContainer").slideUp(500,function() {
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
		$('#obtCLMBrowseBchFrom').attr('disabled',true);
		$('#obtCLMBrowseBchTo').attr('disabled',true);
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
	$('#obtCLMBrowseBchFrom').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseBchFrom');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtCLMBrowseBchTo').unbind().click(function() {
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
	$('#obtCLMBrowseSplFrom').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseSPLFrom');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtCLMBrowseSplTo').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseSPLTo');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

	// Option CST From
	var oPmhBrowseCustomer = {
		Title   : ['customer/customer/customer', 'tCSTTitle'],
		Table   : {Master:'TCNMCst', PK:'FTCstCode'},
		Join    : {
			Table: ['TCNMCst_L'],
			On: [
				'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLangEdits
			]
		},
		Where:{
			Condition           : ["AND TCNMCst.FTCstStaActive = '1' "]
		},
		GrideView:{
			ColumnPathLang      : 'customer/customer/customer',
			ColumnKeyLang       : ['tCSTCode', 'tCSTName','tCSTTel','tCSTEmail'],
			ColumnsSize         : ['15%', '40%','20%','20%'],
			WidthModal          : 50,
			DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstTel','TCNMCst.FTCstEmail'],
			DataColumnsFormat   : ['','','',''],
			Perpage             : 10,
			OrderBy             : ['TCNMCst_L.FTCstCode ASC']
		},
		CallBack:{
			ReturnType  : 'S',
			Value       : ['oetCstCode',"TCNMCst.FTCstCode"],
			Text        : ['oetCstName',"TCNMCst_L.FTCstName"]
		}
	}
	$('#obtCLMBrowseCst').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oPmhBrowseCustomer');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

	var oCarCst  = function(poDataFnc){
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tParamsCstCode      = poDataFnc.tParamsCstCode;
        let oOptionReturn       = {
            Title   : ['document/jobrequest1/jobrequest1', 'tJR1CarCst'],
            Table   : {Master:'TSVMCar', PK:'FTCarCode'},
            Where   : {
                Condition : [tParamsCstCode]
            },
			Join    : {
                Table   : ['TCNMCst_L'],
                On      : ["TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits ]
            },
            GrideView:{
                ColumnPathLang      : 'document/jobrequest1/jobrequest1',
                ColumnKeyLang       : ['tJR1CarCstCode', 'tJR1CarCstName', 'tJR1OwnerCstName'],
                ColumnsSize         : ['15%', '15%', '60%'],
                WidthModal          : 50,
                DataColumns         : ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat   : ['','',''],
                Perpage             : 10,
                OrderBy             : ['TSVMCar.FTCarCode ASC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TSVMCar.FTCarOwner"],
                Text    : [tInputReturnName,"TSVMCar.FTCarRegNo"]
            }
        };
        return oOptionReturn;
    };

	$('#obtCLMBrowseCar').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

			if($('#oetCstCode').val() == ''){
				var tParamsCstCode = "";
			}else{
				var tParamsCstCode = "AND TSVMCar.FTCarOwner = '" + $('#oetCstCode').val() + "'";
			}

            window.oCarCstOption = undefined;
            oCarCstOption        = oCarCst({
                'tReturnInputCode'  : 'oetCarCode',
                'tReturnInputName'  : 'oetCarName',
                'tParamsCstCode'    : tParamsCstCode
            });
            JCNxBrowseData('oCarCstOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

	//ล้างข้อมูล
	function JSxCLMClearSearchData() {
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
            $("#obtCLMDocDateFrom").val("");
            $("#obtCLMDocDateTo").val("");

			$("#oetSplNameFrom , #oetSplCodeFrom").val("");
			$("#oetSplNameTo , #oetSplCodeTo").val("");
			$("#oetCstCode , #oetCstName").val("");
			$("#oetCarCode , #oetCarName").val("");

            $(".xCNDatePicker").datepicker("setDate", null);
            $(".selectpicker").val("0").selectpicker("refresh");
            JSvCLMCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
</script>