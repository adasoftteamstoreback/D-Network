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

    $('#obtPNBrowseBchRefIntDoc').click(function(){ 
        $('#odvPNModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oPNBrowseBranchOption  = undefined;
                oPNBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetPNRefIntBchCode',
                    'tReturnInputName'  : 'oetPNRefIntBchName',
                    'tNextFuncName'     : 'JSxPNRefIntNextFunctBrowsBranch',
                    'tPNAgnCode'        :  '',
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oPNBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });


    $('#obtPNBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetPNRefIntDocDateFrm').datepicker('show');
    });


    $('#obtPNBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetPNRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});

      // ========================================== Brows Option Conditon ===========================================
        // ตัวแปร Option Browse Modal สาขา
        var oBranchOption = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tPNAgnCode          = poDataFnc.tPNAgnCode;
            
            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tSQLWhere = "";
            if(tUsrLevel != "HQ"){
                tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
            }

            if(tPNAgnCode!=''){
                tSQLWhere = " AND TCNMBranch.FTAgnCode ='"+tPNAgnCode+"' ";
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
                    DisabledColumns   : [2,3],
                    WidthModal          : 50,
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

$('#odvPNModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvPNModalRefIntDoc').css('overflow','auto');
 
});

$('#odvPNModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvPNModalRefIntDoc').css('overflow','auto');
});

function JSxPNRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
      $('#odvPNModalRefIntDoc').modal("show");
    
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
        var nPageCurrent = pnNewPage;
        var tPNRefIntBchCode  = $('#oetPNRefIntBchCode').val();
        var tPNRefIntDocNo  = $('#oetPNRefIntDocNo').val();
        var tPNRefIntDocDateFrm  = $('#oetPNRefIntDocDateFrm').val();
        var tPNRefIntDocDateTo  = $('#oetPNRefIntDocDateTo').val();
        var tPNRefIntStaDoc  = $('#oetPNRefIntStaDoc').val();
        var tPNTypeRef         = $('#ocmPNSelectBrowse').val();

        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPNCallRefIntDocDataTable",
            data: {
                'tPNRefIntBchCode'     : tPNRefIntBchCode,
                'tPNRefIntDocNo'       : tPNRefIntDocNo,
                'tPNRefIntDocDateFrm'  : tPNRefIntDocDateFrm,
                'tPNRefIntDocDateTo'   : tPNRefIntDocDateTo,
                'tPNRefIntStaDoc'      : tPNRefIntStaDoc,
                'nPNRefIntPageCurrent' : nPageCurrent,
                'tPNTypeRef'           : tPNTypeRef,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                 $('#odvRefIntDocHDDataTable').html(oResult);
                 JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JSxRefIntDocHDDataTable(pnPage)
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

}


</script>