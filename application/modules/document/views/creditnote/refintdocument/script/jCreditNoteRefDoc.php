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

    $('#obtPiBrowseBchRefIntDoc').click(function(){ 
        $('#odvPiModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oPiBrowseRefBranchOption  = undefined;
                oPiBrowseRefBranchOption         = oBranchRefOption({
                    'tReturnInputCode'  : 'oetPiRefIntBchCode',
                    'tReturnInputName'  : 'oetPiRefIntBchName',
                    'tNextFuncName'     : 'JSxPiRefIntNextFunctBrowsBranch',
                    'tAgnCode'          : '',
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oPiBrowseRefBranchOption');
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


    $('#obtPiBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetPiRefIntDocDateFrm').datepicker('show');
    });


    $('#obtPiBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetPiRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvPiModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvPiModalRefIntDoc').css('overflow','auto');
 
});

$('#odvPiModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvPiModalRefIntDoc').css('overflow','auto');
});

function JSxPiRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvPiModalRefIntDoc').modal("show");
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
        var tPiRefIntBchCode  = $('#oetPiRefIntBchCode').val();
        var tPiRefIntDocNo  = $('#oetPiRefIntDocNo').val();
        var tPiRefIntDocDateFrm  = $('#oetPiRefIntDocDateFrm').val();
        var tPiRefIntDocDateTo  = $('#oetPiRefIntDocDateTo').val();
        var tPiRefIntStaDoc  = $('#oetPiRefIntStaDoc').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "creditNoteCallRefIntDocDataTable",
            data: {
                'tPiRefIntBchCode'     : tPiRefIntBchCode,
                'tPiRefIntDocNo'       : tPiRefIntDocNo,
                'tPiRefIntDocDateFrm'  : tPiRefIntDocDateFrm,
                'tPiRefIntDocDateTo'   : tPiRefIntDocDateTo,
                'tPiRefIntStaDoc'      : tPiRefIntStaDoc,
                'nPiRefIntPageCurrent' : nPageCurrent,
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