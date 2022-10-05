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

    $('#obtCRVBrowseBchRefIntDoc').click(function(){ 
        $('#odvCRVModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oCRVBrowseRefBranchOption  = undefined;
                oCRVBrowseRefBranchOption         = oBranchRefOption({
                    'tReturnInputCode'  : 'oetCRVRefIntBchCode',
                    'tReturnInputName'  : 'oetCRVRefIntBchName',
                    'tNextFuncName'     : 'JSxCRVRefIntNextFunctBrowsBranch',
                    'tAgnCode'          : $('#oetCRVAgnCode').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oCRVBrowseRefBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });

    // ตัวแปร Option Browse Modal สาขา
    var oBranchRefOption = function(poDataFnc){
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


    $('#obtCRVBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetCRVRefIntDocDateFrm').datepicker('show');
    });


    $('#obtCRVBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetCRVRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvCRVModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvCRVModalRefIntDoc').css('overflow','auto');
 
});

$('#odvCRVModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvCRVModalRefIntDoc').css('overflow','auto');
});

function JSxCRVRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvCRVModalRefIntDoc').modal("show");
}

$('#obtRefIntDocFilter').on('click',function(){
    JSxRefIntDocHDDataTable();
});

//เรียกตารางเลขที่เอกสารอ้างอิง
function JSxRefIntDocHDDataTable(pnPage){
    if(pnPage == '' || pnPage == null){
            var pnNewPage = 1;
        }else{
            var pnNewPage = pnPage;
        }
        var nPageCurrent = pnNewPage;
        var tCRVRefIntBchCode  = $('#oetCRVRefIntBchCode').val();
        var tCRVRefIntDocNo  = $('#oetCRVRefIntDocNo').val();
        var tCRVRefIntDocDateFrm  = $('#oetCRVRefIntDocDateFrm').val();
        var tCRVRefIntDocDateTo  = $('#oetCRVRefIntDocDateTo').val();
        var tCRVRefIntStaDoc  = $('#oetCRVRefIntStaDoc').val();
        var tCRVDocType  = $('#ohdDocType').val();
        var tCRVSplCode  = $('#oetCRVFrmSplCode').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docCRVCallRefIntDocDataTable",
            data: {
                'tCRVRefIntBchCode'     : tCRVRefIntBchCode,
                'tCRVRefIntDocNo'       : tCRVRefIntDocNo,
                'tCRVRefIntDocDateFrm'  : tCRVRefIntDocDateFrm,
                'tCRVRefIntDocDateTo'   : tCRVRefIntDocDateTo,
                'tCRVRefIntStaDoc'      : tCRVRefIntStaDoc,
                'nCRVRefIntPageCurrent' : nPageCurrent,
                'tCRVDocType'           : tCRVDocType,
                'tCRVSplCode'           : tCRVSplCode
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