<script type="text/javascript">
    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtPCKAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtPCKAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

    // Option Branch
    var oPCKBrowseBranch = function(poReturnInput){
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
    $('#obtPCKAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPCKBrowseBranchFromOption  = oPCKBrowseBranch({
                'tReturnInputCode'  : 'oetPCKAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetPCKAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oPCKBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtPCKAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPCKBrowseBranchToOption  = oPCKBrowseBranch({
                'tReturnInputCode'  : 'oetPCKAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetPCKAdvSearchBchNameTo'
            });
            JCNxBrowseData('oPCKBrowseBranchToOption');
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
        $('#obtPCKAdvSearchDocDateForm').unbind().click(function(){
            $('#oetPCKAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtPCKAdvSearchDocDateTo').unbind().click(function(){
            $('#oetPCKAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtPCKAdvanceSearch').unbind().click(function(){
        if($('#odvPCKAdvanceSearchContainer').hasClass('hidden')){
            $('#odvPCKAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvPCKAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });
    
    $('#obtPCKSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxPCKClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxPCKClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmPCKFromSerchAdv').find('input').val('');
            $('#oetPCKSearchAllDocument').val('');
            $('#ofmPCKFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvPCKCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
    $('#oetPCKSearchAllDocument').keyup(function(event){
        var nCodeKey    = event.which;
        if(nCodeKey == 13){
            event.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSvPCKCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    });
    
    $('#obtPCKSerchAllDocument').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvPCKCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $("#obtPCKAdvSearchSubmitForm").unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvPCKCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // =================================================================================================================================================

    // Function: Call Page DataTable
    function JSvPCKCallPageDataTable(pnPage) {
        var oAdvanceSearch = JSoPCKGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type: "POST",
            url: "docPCKDataTable",
            data: {
                nPageCurrent: nPageCurrent,
                oAdvanceSearch: oAdvanceSearch
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostPCKDataTableDocument').html(aReturnData['tViewDataTable']);
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
    function JSoPCKGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetPCKSearchAllDocument").val(),
            tSearchBchCodeFrom: $("#oetPCKAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetPCKAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetPCKAdvSearcDocDateFrom").val(),
            tSearchDocDateTo: $("#oetPCKAdvSearcDocDateTo").val(),
            tSearchStaDoc: $("#ocmPCKAdvSearchStaDoc").val(),
            tSearchStaDocAct: $("#ocmStaDocAct").val()
        };
        return oAdvanceSearchData;
    }

</script>