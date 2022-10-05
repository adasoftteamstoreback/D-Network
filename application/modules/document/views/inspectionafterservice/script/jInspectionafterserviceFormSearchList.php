<script type="text/javascript">
    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtIASAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtIASAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

	    // Option Branch
        var oIASBrowseBranch = function(poReturnInput){
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
    $('#obtIASAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIASBrowseBranchFromOption  = oIASBrowseBranch({
                'tReturnInputCode'  : 'oetIASAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetIASAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oIASBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtIASAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIASBrowseBranchToOption  = oIASBrowseBranch({
                'tReturnInputCode'  : 'oetIASAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetIASAdvSearchBchNameTo'
            });
            JCNxBrowseData('oIASBrowseBranchToOption');
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
        $('#obtIASAdvSearchDocDateForm').unbind().click(function(){
            $('#oetIASAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtIASAdvSearchDocDateTo').unbind().click(function(){
            $('#oetIASAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtIASAdvanceSearch').unbind().click(function(){
        if($('#odvIASAdvanceSearchContainer').hasClass('hidden')){
            $('#odvIASAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvIASAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });
    
    $('#obtIASSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxIASClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxIASClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmIASFromSerchAdv').find('input').val('');
            $('#oetIASSearchAllDocument').val('');
            $('#ofmIASFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvIASCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
        $('#oetIASSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvIASCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtIASSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvIASCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtIASAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvIASCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================

    // Function: Call Page DataTable
    function JSvIASCallPageDataTable(pnPage) {
        var oAdvanceSearch = JSoIASGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type: "POST",
            url: "docIASDataTable",
            data: {
                nPageCurrent: nPageCurrent,
                oAdvanceSearch: oAdvanceSearch
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostIASDataTableDocument').html(aReturnData['tViewDataTable']);
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
    function JSoIASGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetIASSearchAllDocument").val(),
            tSearchBchCodeFrom: $("#oetIASAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetIASAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetIASAdvSearcDocDateFrom").val(),
            tSearchDocDateTo: $("#oetIASAdvSearcDocDateTo").val(),
            tSearchStaDoc: $("#ocmIASAdvSearchStaDoc").val(),
            tSearchStaDocAct: $("#ocmStaDocAct").val()
        };
        return oAdvanceSearchData;
    }

</script>