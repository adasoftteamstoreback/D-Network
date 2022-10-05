<script type="text/javascript">
$(document).ready(function(){

    $('.selectpicker').selectpicker('refresh');

    $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
    });

    $('#obtDPSBrowseBchRefIntDoc').click(function(){ 
        $('#odvDPSModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            window.oDPSBrowseBranchOption  = undefined;
            oDPSBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'oetDPSRefIntBchCode',
                'tReturnInputName'  : 'oetDPSRefIntBchName',
                'tNextFuncName'     : 'JSxDPSRefIntNextFunctBrowsBranch',
                'tDPSAgnCode'        : $('#oetDPSAgnCodeFrm').val(),
                'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
            });
            JCNxBrowseData('oDPSBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });


    $('#obtDPSBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetDPSRefIntDocDateFrm').datepicker('show');
    });


    $('#obtDPSBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetDPSRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvDPSModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvDPSModalRefIntDoc').css('overflow','auto');
});

$('#odvDPSModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvDPSModalRefIntDoc').css('overflow','auto');
});

function JSxDPSRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvDPSModalRefIntDoc').modal("show");
    
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
    var tDPSRefIntBchCode  = $('#oetDPSRefIntBchCode').val();
    var tDPSRefIntDocNo  = $('#oetDPSRefIntDocNo').val();
    var tDPSRefIntDocDateFrm  = $('#oetDPSRefIntDocDateFrm').val();
    var tDPSRefIntDocDateTo  = $('#oetDPSRefIntDocDateTo').val();
    var tDPSRefIntStaDoc  = $('#oetDPSRefIntStaDoc').val();
    var tDPSTypeRef         = $('#ocmDPSSelectBrowse').val();
    // JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "dcmDPSCallRefIntDocDataTable",
        data: {
            'tDPSRefIntBchCode'     : tDPSRefIntBchCode,
            'tDPSRefIntDocNo'       : tDPSRefIntDocNo,
            'tDPSRefIntDocDateFrm'  : tDPSRefIntDocDateFrm,
            'tDPSRefIntDocDateTo'   : tDPSRefIntDocDateTo,
            'tDPSRefIntStaDoc'      : tDPSRefIntStaDoc,
            'nDPSRefIntPageCurrent' : nPageCurrent,
            'tDPSTypeRef'           : tDPSTypeRef,
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