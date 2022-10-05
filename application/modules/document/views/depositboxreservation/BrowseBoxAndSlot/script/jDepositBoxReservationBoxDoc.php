<script>
$(document).ready(function(){

    var tseq = $("#ohdSeqNo").val();
    $("#ohdShpCode").val($("#ohdShp"+tseq).val());

    $('.selectpicker').selectpicker('refresh');

    $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
    });

    $('#obtDBRBoxBrowseBch').click(function(){ 
        $('#odvDBRModalBrowseBox').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oDBRBrowseBoxBranchOption  = undefined;
                oDBRBrowseBoxBranchOption         = oBranchBoxOption({
                    'tReturnInputCode'  : 'oetDBRBoxBchCode',
                    'tReturnInputName'  : 'oetDBRBoxBchName',
                    'tNextFuncName'     : 'JSxDBRBoxNextFunctBrowsBranch',
                    'tAgnCode'          : $('#oetDBRAgnCode').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName'],
                });
                JCNxBrowseData('oDBRBrowseBoxBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });

    // ตัวแปร Option Browse Modal สาขา
    var oBranchBoxOption = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tAgnCode            = poDataFnc.tAgnCode;
            
            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tSQLWhere = "";
            if(tUsrLevel != "HQ"){
                tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
            }

            if(tAgnCode!=''){
                tSQLWhere = " AND TCNMBranch.FTAgnCode ='"+tAgnCode+"' ";
            }

            // ตัวแปร ออฟชั่นในการ Return
            var oOptionReturn       = {
                Title: ['authen/user/user', 'tBrowseBCHTitle'],
                Table: {
                    Master  : 'TCNMBranch',
                    PK      : 'FTBchCode'
                },
                Join: {
                    Table   : ['TCNMBranch_L','TCNMWaHouse_L'],
                    On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                             'TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID ='+nLangEdits,]
                },
                Where : {
                    Condition : [tSQLWhere]
                },
                GrideView: {
                    ColumnPathLang      : 'authen/user/user',
                    ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName','TCNMWaHouse_L.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat   : ['', ''],
                    DisabledColumns     : [2,3],
                    WidthModal          : 30,
                    Perpage             : 10,
                    OrderBy             : ['TCNMBranch.FTBchCode'],
                    SourceOrder         : "ASC"
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                    Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
                },
                NextFunc: {
                    FuncName    : tNextFuncName,
                    ArgReturn   : aArgReturn
                },
                RouteAddNew: 'branch',
                BrowseLev: 1
            };
            return oOptionReturn;
        }

    $('#obtDBRBoxBrowsePos').click(function(){ 
        $('#odvDBRModalBrowseBox').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oDBRBrowseBoxPosOption  = undefined;
                oDBRBrowseBoxPosOption         = oPosBoxOption({
                    'tReturnInputCode'  : 'oetDBRBoxPosCode',
                    'tReturnInputName'  : 'oetDBRBoxPosName',
                    'tNextFuncName'     : 'JSxDBRBoxNextFunctBrowsBranch',
                    'tBchCode'          : $('#oetDBRBoxBchCode').val(),
                    'aArgReturn'        : ['FTPosCode','FTPosName'],
                });
                JCNxBrowseData('oDBRBrowseBoxPosOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });

    // ตัวแปร Option Browse Modal สาขา
    var oPosBoxOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tBchCode            = poDataFnc.tBchCode;
        
        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhere = "";
        if(tUsrLevel != "HQ"){
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        if(tBchCode!=''){
            tSQLWhere = " AND TCNMPos.FTBchCode ='"+tBchCode+"' ";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn       = {
            Title: ['authen/user/user', 'ตู้ฝาก'],
            Table: {
                Master  : 'TCNMPos',
                PK      : 'FTPosCode'
            },
            Join: {
                Table   : ['TCNMPos_L'],
                On      : ['TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FTBchCode = TCNMPos.FTBchCode AND TCNMPos_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhere]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['รหัสตู้ฝาก', 'ชื่อตู้ฝาก'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 30,
                Perpage             : 10,
                OrderBy             : ['TCNMPos.FTPosCode'],
                SourceOrder         : "ASC"
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMPos.FTPosCode"],
                Text        : [tInputReturnName, "TCNMPos_L.FTPosName"]
            },
            // DebugSQL: true,
            NextFunc: {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        return oOptionReturn;
    }

    JSxBrowseBoxHDDataTable();
});


$('#odvDBRModalBrowseBox').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvDBRModalBrowseBox').css('overflow','auto');
 
});

$('#odvDBRModalBrowseBox').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvDBRModalBrowseBox').css('overflow','auto');
});

function JSxDBRBoxNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvDBRModalBrowseBox').modal("show");
}

$('#obtBrowseBoxFilter').on('click',function(){
    JSxBrowseBoxHDDataTable();
});

//เรียกตารางตู้ฝาก
function JSxBrowseBoxHDDataTable(pnPage){
    if(pnPage == '' || pnPage == null){
            var pnNewPage = 1;
        }else{
            var pnNewPage = pnPage;
        }
        var nPageCurrent = pnNewPage;
        var tDBRBrowseBoxBchCode    = $('#oetDBRBoxBchCode').val();
        var tDBRBrowseBoxPos        = $('#oetDBRBoxPosCode').val();
        var tDBRBrowseBoxNo         = $('#oetDBRBrowseBoxNo').val();
        var tDBRBrowseType          = $('#ohdBrowseType').val();
        if (tDBRBrowseType == 1) {
            var tDBRStaUse          = '';
            var tDBRPosCode         = '';
            var tDBRShpCode          = '';

        }else{
            var tDBRPosCode         = $('#ohdPosCode').val();
            var tDBRStaUse          = $('#oetDBRStaUse').val();
            var tDBRShpCode          = $('#ohdShpCode').val();
        }
        
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docDBRCallBrowseBoxDataTable",
            data: {
                'tDBRBrowseBoxBchCode'      : tDBRBrowseBoxBchCode,
                'tDBRBrowseBoxPos'          : tDBRBrowseBoxPos,
                'tDBRBrowseBoxNo'           : tDBRBrowseBoxNo,
                'nDBRPageCurrent'           : nPageCurrent,
                'tDBRBrowseType'            : tDBRBrowseType,
                'tDBRPosCode'               : tDBRPosCode,
                'tDBRShpCode'                  : tDBRShpCode,
                'tDBRStaUse'                : tDBRStaUse
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                 $('#odvBrowseBoxHDDataTable').html(oResult);
                 JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JSxBrowseBoxHDDataTable(pnPage)
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

}


</script>