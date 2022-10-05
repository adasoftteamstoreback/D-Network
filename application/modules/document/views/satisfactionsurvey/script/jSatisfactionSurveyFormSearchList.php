<script type="text/javascript">
    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtSatSvAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtSatSvAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

	    // Option Branch
        var oSatSvBrowseBranch = function(poReturnInput){
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
    $('#obtSatSvAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSatSvBrowseBranchFromOption  = oSatSvBrowseBranch({
                'tReturnInputCode'  : 'oetSatSvAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetSatSvAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oSatSvBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtSatSvAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSatSvBrowseBranchToOption  = oSatSvBrowseBranch({
                'tReturnInputCode'  : 'oetSatSvAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetSatSvAdvSearchBchNameTo'
            });
            JCNxBrowseData('oSatSvBrowseBranchToOption');
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
        $('#obtSatSvAdvSearchDocDateForm').unbind().click(function(){
            $('#oetSatSvAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtSatSvAdvSearchDocDateTo').unbind().click(function(){
            $('#oetSatSvAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtSatSvAdvanceSearch').unbind().click(function(){
        if($('#odvSatSvAdvanceSearchContainer').hasClass('hidden')){
            $('#odvSatSvAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvSatSvAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });
    
    $('#obtSatSvSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxSatSvClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxSatSvClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmSatSvFromSerchAdv').find('input').val('');
            $('#oetSatSvSearchAllDocument').val('');
            $('#ofmSatSvFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvSatSvCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
        $('#oetSatSvSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvSatSvCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtSatSvSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSatSvCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtSatSvAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSatSvCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================

    // Function: Call Page DataTable
    function JSvSatSvCallPageDataTable(pnPage) {
        var oAdvanceSearch = JSoSatSvGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type: "POST",
            url: "docSatisfactionSurveyDataTable",
            data: {
                nPageCurrent: nPageCurrent,
                oAdvanceSearch: oAdvanceSearch
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostSatSvDataTableDocument').html(aReturnData['tViewDataTable']);
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
    function JSoSatSvGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetSatSvSearchAllDocument").val(),
            tSearchBchCodeFrom: $("#oetSatSvAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetSatSvAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetSatSvAdvSearcDocDateFrom").val(),
            tSearchDocDateTo: $("#oetSatSvAdvSearcDocDateTo").val(),
            tSearchStaDoc: $("#ocmSatSvAdvSearchStaDoc").val(),
            tSearchStaDocAct: $("#ocmStaDocAct").val()
        };
        return oAdvanceSearchData;
    }

</script>