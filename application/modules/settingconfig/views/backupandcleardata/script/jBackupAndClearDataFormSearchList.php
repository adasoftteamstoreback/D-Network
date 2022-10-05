<script type="text/javascript">
    var tUsrLevel       = "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
    var tWhere 			= "";
    if(nCountBch == 1){
        $('#obtBACAdvSearchBrowseBchFrom').attr('disabled',true);
        $('#obtBACAdvSearchBrowseBchTo').attr('disabled',true);
    }
    if(tUsrLevel != "HQ"){
        tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
    }else{
        tWhere = "";
    }
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });
        // Advance search Display control
        $('#obtBACAdvanceSearch').unbind().click(function(){
            if($('#odvBACAdvanceSearchContainer').hasClass('hidden')){
                $('#odvBACAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
            }else{
                $("#odvBACAdvanceSearchContainer").slideUp(500,function() {
                    $(this).addClass('hidden');
                });
            }
        });
        // Clear Search Reset Filter
        $('#obtBACSearchReset').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxBACClearAdvSearchData();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        // Option Branch Advance Filter 
        var oBACBrowseBranch = function(poReturnInput){
            var tAgnCode            = $('#oetBACAdvSearchAgnCodeFrom').val();
            var tWhereAgn              = '';
            if (tAgnCode == '' || tAgnCode == null) {
                tWhereAgn = '';
            } else {
                tWhereAgn = " AND TCNMBranch.FTAgnCode = '" + tAgnCode + "'";
            }
            var tInputReturnCode    = poReturnInput.tReturnInputCode;
            var tInputReturnName    = poReturnInput.tReturnInputName;
            var oOptionReturn       = {
                Title   : ['company/branch/branch','tBCHTitle'],
                Table   : {Master:'TCNMBranch',PK:'FTBchCode'},
                Join    : {
                    Table : ['TCNMBranch_L'],
                    On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
                },
                Where :{
                    Condition : [tWhere+tWhereAgn]
                },
                GrideView   : {
                    ColumnPathLang      : 'company/branch/branch',
                    ColumnKeyLang       : ['tBCHCode','tBCHName'],
                    ColumnsSize         : ['15%','75%'],
                    WidthModal          : 50,
                    DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                    DataColumnsFormat   : ['',''],
                    Perpage             : 10,
                    OrderBy             : ['TCNMBranch_L.FTBchName ASC'],
                },
                CallBack    : {
                    ReturnType	: 'S',
                    Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                    Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
                }
            };
            return oOptionReturn;
        };

    //Option Agency
    var oBrowseAgn = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title: ['ticket/agency/agency', 'tAggTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'ticket/agency/agency',
                ColumnKeyLang: ['tAggCode', 'tAggName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMAgency.FDCreateOn DESC'],
            },
            NextFunc:{
                    FuncName:'JSxNextFuncBACAgn'
                },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            },
            RouteAddNew: 'agency',
            BrowseLev: 1,
        }
        return oOptionReturn;
    }

    //Option POS
    var oBACBrowsePOS = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tBACBchForm = $('#oetBACAdvSearchBchCodeFrom').val();
        var tBACBchTo = $('#oetBACAdvSearchBchCodeTo').val();
        var tWherePOS = '';

        if (tBACBchTo == '' && tBACBchForm != '') {
            tBACBchTo = tBACBchForm;
        }

        if (tBACBchForm != '' && tBACBchTo != '') {
            var tWherePOS = 'AND ((TCNMPos.FTBchCode BETWEEN '+tBACBchForm+' AND '+tBACBchTo+') OR (TCNMPos.FTBchCode BETWEEN '+tBACBchTo+' AND '+tBACBchForm+'))';  
        }else{
            tWherePOS = '';
        }

        var oOptionReturn = {
            Title: ['settingconfig/backupandcleardata/backupandcleardata', 'tPOSTitle'],
            Table: {
                Master: 'TCNMPos',
                PK: 'FTPosCode'
            },
            Join: {
                Table: ['TCNMPos_L', 'TCNMBranch_L'],
                On: [   
                        'TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FNLngID = ' + nLangEdits,
                        'TCNMBranch_L.FTBchCode = TCNMPos.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits
                    ]
            },
            Where :{
                Condition : [tWherePOS]
            },
            GrideView: {
                ColumnPathLang: 'settingconfig/backupandcleardata/backupandcleardata',
                ColumnKeyLang: ['tPOSCode', 'tPOSName', 'tBACTableBch'],
                ColumnsSize: ['20%', '40%' , '40%'],
                WidthModal: 50,
                DataColumns: ['TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', '', ''],
                Perpage: 10,
                OrderBy: ['TCNMPos.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMPos.FTPosCode"],
                Text: [tInputReturnName, "TCNMPos_L.FTPosName"],
            },
            debugSQL: true,
            BrowseLev: 1,
        }
        return oOptionReturn;
    }
    
        // Event Browse Branch From
        $('#obtBACAdvSearchBrowseBchFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oBACBrowseBranchFromOption  = oBACBrowseBranch({
                    'tReturnInputCode'  : 'oetBACAdvSearchBchCodeFrom',
                    'tReturnInputName'  : 'oetBACAdvSearchBchNameFrom'
                });
                JCNxBrowseData('oBACBrowseBranchFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse Branch To
        $('#obtBACAdvSearchBrowseBchTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oBACBrowseBranchToOption  = oBACBrowseBranch({
                    'tReturnInputCode'  : 'oetBACAdvSearchBchCodeTo',
                    'tReturnInputName'  : 'oetBACAdvSearchBchNameTo'
                });
                JCNxBrowseData('oBACBrowseBranchToOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        // Event Click Document Date Form
        $('#obtBACAdvSearchDocDateForm').unbind().click(function(){
            $('#oetBACAdvSearcDocDateFrom').datepicker('show');
        });
        // Event Click Document Date To
        $('#obtBACAdvSearchDocDateTo').unbind().click(function(){
            $('#oetBACAdvSearcDocDateTo').datepicker('show');
        });
        // Event Key Up Input Text Search
        $('#oetBACSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvBACCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        //BrowseAgn
        $('#obtBACAdvSearchBrowseAgn').click(function(e) {
            e.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oDOBrowseAgencyOption = oBrowseAgn({
                    'tReturnInputCode': 'oetBACAdvSearchAgnCodeFrom',
                    'tReturnInputName': 'oetBACAdvSearchAgnNameFrom',
                });
                JCNxBrowseData('oDOBrowseAgencyOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse POS From
        $('#obtBACAdvSearchBrowsePosFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oBACBrowsePOSFromOption  = oBACBrowsePOS({
                    'tReturnInputCode'  : 'oetBACAdvSearchPosCodeFrom',
                    'tReturnInputName'  : 'oetBACAdvSearchPosNameFrom'
                });
                JCNxBrowseData('oBACBrowsePOSFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse POS To
        $('#obtBACAdvSearchBrowsePosTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oBACBrowsePOSFromOption  = oBACBrowsePOS({
                    'tReturnInputCode'  : 'oetBACAdvSearchPosCodeTo',
                    'tReturnInputName'  : 'oetBACAdvSearchPosNameTo'
                });
                JCNxBrowseData('oBACBrowsePOSFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Search All Document
        $('#obtBACSerchAllDocument').unbind().click(function(){
            JCNxOpenLoading();
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvBACCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        // Event Click Submit Form Search Advance
        $("#obtBACAdvSearchSubmitForm").unbind().click(function(){
            JCNxOpenLoading();
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvBACCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });
    // Function: ล้างค่า Input ทั้งหมดใน Advance Search
    // Create By: Wasin (Yoshi)
    // Create Date: 21/09/2021
    function JSxBACClearAdvSearchData(){
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmBACFromSerchAdv').find('input').val('');
            $('#oetBACSearchAllDocument').val('');
            $('#ofmBACFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvBACCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
    // Function: รวม Values ต่างๆของการค้นหาขั้นสูง
    // Create By: Wasin (Yoshi)
    // Create Date: 21/09/2021
    function JSoBACGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetBACSearchAllDocument").val(),
            tSearchAgn: $("#oetBACAdvSearchAgnCodeFrom").val(),
            tSearchDocDateFrom: $("#oetSearchDocDateFrom").val(),
            tSearchDocDateTo: $("#oetSearchDocDateTo").val(),
            tSearchPrgType: $("#ocmStaPrgType").val(),
            tSearchPrgGroup: $("#ocmStaPrgGroup").val(),
            tSearchPrgAllowPurge: $("#ocmStaPrgAllowPurge").val(),
            tSearchPrgStaUse: $("#ocmStaPrgStaUse").val(),
            tSearchPosCodeFrom: $("#oetBACAdvSearchPosCodeFrom").val(),
            tSearchPosCodeTo: $("#oetBACAdvSearchPosCodeTo").val()
        };
        return oAdvanceSearchData;
    }

    function JSxNextFuncBACAgn() {
        $('#oetDOFrmBchCode').val('');
        $('#oetDOFrmBchName').val('');
        $('#oetDOFrmWahCode').val('');
        $('#oetDOFrmWahName').val('');
    }
</script>