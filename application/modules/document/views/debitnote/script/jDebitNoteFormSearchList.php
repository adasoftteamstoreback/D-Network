<script type="text/javascript">
    $(document).ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        // Doc Date From
        $('#obtDBNAdvSearchDocDateForm').unbind().click(function(){
            $('#oetDBNAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtDBNAdvSearchDocDateTo').unbind().click(function(){
            $('#oetDBNAdvSearcDocDateTo').datepicker('show');
        });

    });


    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";
    // Check Brach Disabled
    if(nCountBch == 1){
        $('#obtDBNAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtDBNAdvSearchBrowseBchTo').attr('disabled',true);
    }
    // Check User Lave HQ OR Other
    if(tUsrLevel != "HQ"){
        tWhere  = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
    }else{
		tWhere  = "";
	}

    // Option Branch
    var oDBNBrowseBranch        = function(poReturnInput){
        let tInputReturnCode    = poReturnInput.tReturnInputCode;
        let tInputReturnName    = poReturnInput.tReturnInputName;
        let oOptionReturn       = {
            Title   : ['company/branch/branch','tBCHTitle'],
            Table   : {Master:'TCNMBranch',PK:'FTBchCode'},
            Join    : {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
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
        };
        return oOptionReturn;
    }

    // Event Browse Branch From
    $('#obtDBNAdvSearchBrowseBchFrom').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDBNBrowseBranchFromOption   = oDBNBrowseBranch({
                'tReturnInputCode'  : 'oetDBNAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetDBNAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oDBNBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtDBNAdvSearchBrowseBchTo').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDBNBrowseBranchToOption  = oDBNBrowseBranch({
                'tReturnInputCode'  : 'oetDBNAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetDBNAdvSearchBchNameTo'
            });
            JCNxBrowseData('oDBNBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Advance search Display control
    $('#obtDBNAdvanceSearch').unbind().click(function(){
        if($('#odvDBNAdvanceSearchContainer').hasClass('hidden')){
            $('#odvDBNAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvDBNAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Click Reset Advance Search
    $('#obtDBNSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxDBNClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxDBNClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmDBNFromSerchAdv').find('input').val('');
            $('#oetDBNSearchAllDocument').val('');
            $('#ofmDBNFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvDBNCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
    $('#oetDBNSearchAllDocument').keyup(function(event){
        var nCodeKey    = event.which;
        if(nCodeKey == 13){
            event.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSvDBNCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    });
    $('#obtDBNSerchAllDocument').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvDBNCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    $("#obtDBNAdvSearchSubmitForm").unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvDBNCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    // =================================================================================================================================
    
    // Function: Call Page DataTable
    function JSvDBNCallPageDataTable(pnPage) {
        var oAdvanceSearch  = JSoDBNGetAdvanceSearchData();
        var nPageCurrent    = pnPage;
        if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type    : "POST",
            url     : "docDBNDataTable",
            data    : {
                nPageCurrent: nPageCurrent,
                oAdvanceSearch: oAdvanceSearch
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostDBNDataTableDocument').html(aReturnData['tViewDataTable']);
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
    function JSoDBNGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll          : $("#oetDBNSearchAllDocument").val(),
            tSearchBchCodeFrom  : $("#oetDBNAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo    : $("#oetDBNAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom  : $("#oetDBNAdvSearcDocDateFrom").val(),
            tSearchDocDateTo    : $("#oetDBNAdvSearcDocDateTo").val(),
            tSearchStaDoc       : $("#ocmDBNAdvSearchStaDoc").val(),
            tSearchStaDocAct    : $("#ocmDBNAdvSearchStaAct").val()
        };
        return oAdvanceSearchData;
    }

</script>