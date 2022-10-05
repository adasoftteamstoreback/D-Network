<script type="text/javascript">
    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtSALAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtSALAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

	    // Option Branch
        var oSALBrowseBranch = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var oOptionReturn       = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table : {Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table : ['TCNMBranch_L'],
                On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode','tBCHName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 10,
                OrderBy             : ['TCNMBranch_L.FTBchName ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
        }
        return oOptionReturn;
    };

    // Event Browse Branch From
    $('#obtSALAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSALBrowseBranchFromOption  = oSALBrowseBranch({
                'tReturnInputCode'  : 'oetSALAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetSALAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oSALBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtSALAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSALBrowseBranchToOption  = oSALBrowseBranch({
                'tReturnInputCode'  : 'oetSALAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetSALAdvSearchBchNameTo'
            });
            JCNxBrowseData('oSALBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $(document).ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        // Doc Date From
        $('#obtSALAdvSearchDocDateForm').unbind().click(function(){
            $('#oetSALAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtSALAdvSearchDocDateTo').unbind().click(function(){
            $('#oetSALAdvSearcDocDateTo').datepicker('show');
        });

    });

    // Advance search Display control
    $('#obtSALAdvanceSearch').unbind().click(function(){
        if($('#odvSALAdvanceSearchContainer').hasClass('hidden')){
            $('#odvSALAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvSALAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    $('#obtSALSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxSALClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxSALClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmSALFromSerchAdv').find('input').val('');
            $('#oetSALSearchAllDocument').val('');
            $('#ocmSALAdvSearchStaDoc').val(0).selectpicker("refresh");
            $('#ocmStaDocAct').val(1).selectpicker("refresh");
            JSvSALCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
        $('#oetSALSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvSALCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        $('#obtSALSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSALCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtRBMAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSALCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================

    // Function: Call Page DataTable
    function JSvSALCallPageDataTable(pnPage) {
        var oAdvanceSearch = JSoSALGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        var nStaSite = $('#oetStaSite').val();
        if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type: "POST",
            url: "docTXOWithdrawDataTable",
            data: {
                nPageCurrent: nPageCurrent,
                oAdvanceSearch: oAdvanceSearch,
                'pnStaSite': nStaSite,
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostSALDataTableDocument').html(aReturnData['tViewDataTable']);
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
    // รวม Values ต่างๆของการค้นหาขั้นสูง
    function JSoSALGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetSALSearchAllDocument").val(),
            tSearchBchCodeFrom: $("#oetSALAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetSALAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetSALAdvSearcDocDateFrom").val(),
            tSearchDocDateTo: $("#oetSALAdvSearcDocDateTo").val(),
            tSearchStaDoc: $("#ocmSALAdvSearchStaDoc").val(),
            tSearchStaDocAct: $("#ocmStaDocAct").val()
        };
        return oAdvanceSearchData;
    }

</script>
