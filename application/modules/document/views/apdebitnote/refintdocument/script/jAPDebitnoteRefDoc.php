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

    $('#obtAPDBrowseBchRefIntDoc').click(function(){ 
        $('#odvAPDModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oAPDBrowseRefBranchOption  = undefined;
                oAPDBrowseRefBranchOption         = oBranchRefOption({
                    'tReturnInputCode'  : 'oetAPDRefIntBchCode',
                    'tReturnInputName'  : 'oetAPDRefIntBchName',
                    'tNextFuncName'     : 'JSxAPDRefIntNextFunctBrowsBranch',
                    'tAgnCode'          : '',
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oAPDBrowseRefBranchOption');
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


    $('#obtAPDBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetAPDRefIntDocDateFrm').datepicker('show');
    });


    $('#obtAPDBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetAPDRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvAPDModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvAPDModalRefIntDoc').css('overflow','auto');
 
});

$('#odvAPDModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvAPDModalRefIntDoc').css('overflow','auto');
});

function JSxAPDRefIntNextFunctBrowsBranch(ptData){
    JSxCheckAPDnMenuClose();
    $('#odvAPDModalRefIntDoc').modal("show");
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
        var nPageCurrent            = pnNewPage;
        var tAPDRefIntBchCode       = $('#oetAPDRefIntBchCode').val();
        var tAPDRefIntDocNo         = $('#oetAPDRefIntDocNo').val();
        var tAPDRefIntDocDateFrm    = $('#oetAPDRefIntDocDateFrm').val();
        var tAPDRefIntDocDateTo     = $('#oetAPDRefIntDocDateTo').val();
        var tAPDRefIntStaDoc        = $('#oetAPDRefIntStaDoc').val();
        var tAPDDocType             = $('#ohdDocType').val();
        var tAPDSplCode             = $('#oetAPDSplCode').val();

        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docAPDebitnoteCallRefIntDocDataTable",
            data: {
                'tAPDRefIntBchCode'     : tAPDRefIntBchCode,
                'tAPDRefIntDocNo'       : tAPDRefIntDocNo,
                'tAPDRefIntDocDateFrm'  : tAPDRefIntDocDateFrm,
                'tAPDRefIntDocDateTo'   : tAPDRefIntDocDateTo,
                'tAPDRefIntStaDoc'      : tAPDRefIntStaDoc,
                'nAPDRefIntPageCurrent' : nPageCurrent,
                'tAPDDocType'           : tAPDDocType,
                'tAPDSplCode'           : tAPDSplCode
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