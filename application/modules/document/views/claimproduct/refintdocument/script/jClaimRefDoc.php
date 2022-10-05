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

    $('#obtCLMBrowseBchRefIntDoc').click(function(){ 
        $('#odvCLMModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            window.oCLMBrowseBranchOption  = undefined;
            oCLMBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'oetCLMRefIntBchCode',
                'tReturnInputName'  : 'oetCLMRefIntBchName',
                'tNextFuncName'     : 'JSxCLMRefIntNextFunctBrowsBranch',
                'tCLMAgnCode'       : '<?=$this->session->userdata("tSesUsrAgnCode")?>',
                'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
            });
            JCNxBrowseData('oCLMBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtCLMBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetCLMRefIntDocDateFrm').datepicker('show');
    });

    $('#obtCLMBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetCLMRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvCLMModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvCLMModalRefIntDoc').css('overflow','auto');
 
});

$('#odvCLMModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvCLMModalRefIntDoc').css('overflow','auto');
});

function JSxCLMRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvCLMModalRefIntDoc').modal("show");
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
    var nPageCurrent         = pnNewPage;
    var tCLMRefIntBchCode    = $('#oetCLMRefIntBchCode').val();
    var tCLMRefIntDocNo      = $('#oetCLMRefIntDocNo').val();
    var tCLMRefIntDocDateFrm = $('#oetCLMRefIntDocDateFrm').val();
    var tCLMRefIntDocDateTo  = $('#oetCLMRefIntDocDateTo').val();
    var tCLMRefIntStaDoc     = $('#oetCLMRefIntStaDoc').val();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docClaimRefIntDocDataTable",
        data: {
            'tCLMRefIntBchCode'     : tCLMRefIntBchCode,
            'tCLMRefIntDocNo'       : tCLMRefIntDocNo,
            'tCLMRefIntDocDateFrm'  : tCLMRefIntDocDateFrm,
            'tCLMRefIntDocDateTo'   : tCLMRefIntDocDateTo,
            'tCLMRefIntStaDoc'      : tCLMRefIntStaDoc,
            'nCLMRefIntPageCurrent' : nPageCurrent,
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