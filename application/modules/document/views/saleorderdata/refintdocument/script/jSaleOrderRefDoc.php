<script>
$(document).ready(function(){

    $('.selectpicker').selectpicker('refresh');

    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });

    $('#obtSOBrowseBchRefIntDoc').click(function(){ 
        $('#odvSOModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            window.oSOBrowseBranchOption  = undefined;
            oSOBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'oetSORefIntBchCode',
                'tReturnInputName'  : 'oetSORefIntBchName',
                'tNextFuncName'     : 'JSxSORefIntNextFunctBrowsBranch',
                'tSOAgnCode'        : '',
                'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
            });
            JCNxBrowseData('oSOBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

        // ตัวแปร Option Browse Modal สาขา
        var oBranchOption = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tAgnCode            = poDataFnc.tSOAgnCode;
            
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

    $('#obtSOBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetSORefIntDocDateFrm').datepicker('show');
    });

    $('#obtSOBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetSORefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvSOModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvSOModalRefIntDoc').css('overflow','auto');
 
});

$('#odvSOModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvSOModalRefIntDoc').css('overflow','auto');
});

function JSxSORefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvSOModalRefIntDoc').modal("show");
}

$('#obtRefIntDocFilter').on('click',function(){
    JSxRefIntDocHDDataTable();
});

function JSxRefIntDocHDDataTable(pnPage){
    if(pnPage == '' || pnPage == null){
        var pnNewPage = 1;
    }else{
        var pnNewPage = pnPage;
    }
    var nPageCurrent        = pnNewPage;
    var tSORefIntBchCode    = $('#oetSORefIntBchCode').val();
    var tSORefIntDocNo      = $('#oetSORefIntDocNo').val();
    var tSORefIntDocDateFrm = $('#oetSORefIntDocDateFrm').val();
    var tSORefIntDocDateTo  = $('#oetSORefIntDocDateTo').val();
    var tSORefIntStaDoc     = $('#oetSORefIntStaDoc').val();
    var tSORefIntDocType    = $('#oetSORefIntDocType').val();
    
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docSORefIntDocDataTable",
        data: {
            'tSORefIntBchCode'     : tSORefIntBchCode,
            'tSORefIntDocNo'       : tSORefIntDocNo,
            'tSORefIntDocDateFrm'  : tSORefIntDocDateFrm,
            'tSORefIntDocDateTo'   : tSORefIntDocDateTo,
            'tSORefIntStaDoc'      : tSORefIntStaDoc,
            'tSORefIntDocType'     : tSORefIntDocType,
            'nSORefIntPageCurrent' : nPageCurrent,
        },
        cache: false,
        Timeout: 0,
        success: function (oResult){
            $('#odvRefIntDocHDDataTable').html(oResult);
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

</script>