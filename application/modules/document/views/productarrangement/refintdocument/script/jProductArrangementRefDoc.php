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

    $('#obtPAMBrowseBchRefIntDoc').click(function(){
        $('#odvPAMModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oPAMBrowseRefBranchOption  = undefined;
                oPAMBrowseRefBranchOption         = oBranchRefOption({
                    'tReturnInputCode'  : 'oetPAMRefIntBchCode',
                    'tReturnInputName'  : 'oetPAMRefIntBchName',
                    'tNextFuncName'     : 'JSxPAMRefIntNextFunctBrowsBranch',
                    'tAgnCode'          : $('#oetPAMAgnCode').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName'],
                });
                JCNxBrowseData('oPAMBrowseRefBranchOption');
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
            var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tWhere = "";
            if(tUsrLevel != "HQ"){
                tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
            }else{
                tWhere = "";
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
                Join :{
                    Table : ['TCNMBranch_L'],
                    On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
                },
                Where : {
                    Condition : [tWhere]
                },
                GrideView: {
                    ColumnPathLang      : 'authen/user/user',
                    ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
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


    $('#obtPAMBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetPAMRefIntDocDateFrm').datepicker('show');
    });


    $('#obtPAMBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetPAMRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvPAMModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvPAMModalRefIntDoc').css('overflow','auto');

});

$('#odvPAMModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvPAMModalRefIntDoc').css('overflow','auto');
});

function JSxPAMRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvPAMModalRefIntDoc').modal("show");
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
        var tPAMRefIntBchCode  = $('#oetPAMRefIntBchCode').val();
        var tPAMRefIntDocNo  = $('#oetPAMRefIntDocNo').val();
        var tPAMRefIntDocDateFrm  = $('#oetPAMRefIntDocDateFrm').val();
        var tPAMRefIntDocDateTo  = $('#oetPAMRefIntDocDateTo').val();
        var tPAMRefIntStaDoc  = $('#oetPAMRefIntStaDoc').val();
        var tPAMRefIntIntRefDoc  = $('#oetPAMRefIntRefDoc').val();
        var tTypeRef = $("#ocbPAMRefDoc").val();
        if (nPageCurrent==NaN) {
          nPageCurrent = 1;
        }
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPAMCallRefIntDocDataTable",
            data: {
                'tPAMRefIntBchCode'     : tPAMRefIntBchCode,
                'tPAMRefIntDocNo'       : tPAMRefIntDocNo,
                'tPAMRefIntDocDateFrm'  : tPAMRefIntDocDateFrm,
                'tPAMRefIntDocDateTo'   : tPAMRefIntDocDateTo,
                'tPAMRefIntStaDoc'      : tPAMRefIntStaDoc,
                'nPAMRefIntPageCurrent' : nPageCurrent,
                'tPAMRefIntIntRefDoc' : tPAMRefIntIntRefDoc,
                'tTypeRef' : tTypeRef
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
