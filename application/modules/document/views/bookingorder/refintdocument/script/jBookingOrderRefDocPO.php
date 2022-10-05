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

    $('#obtTWXBrowseBchRefIntDoc').click(function(){ 
        $('#odvTWXModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oTWXBrowseBranchOption  = undefined;
                oTWXBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetTWXRefIntBchCode',
                    'tReturnInputName'  : 'oetTWXRefIntBchName',
                    'tNextFuncName'     : 'JSxTWXRefIntNextFunctBrowsBranch',
                    'tTWXAgnCode'        : $('#oetTWXAgnCodeFrm').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oTWXBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });


    $('#obtTWXBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetTWXRefIntDocDateFrm').datepicker('show');
    });


    $('#obtTWXBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetTWXRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvTWXModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvTWXModalRefIntDoc').css('overflow','auto');
 
});

$('#odvTWXModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvTWXModalRefIntDoc').css('overflow','auto');
});

function JSxTWXRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
      $('#odvTWXModalRefIntDoc').modal("show");
    
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
        var tTWXRefIntBchCode  = $('#oetTWXRefIntBchCode').val();
        var tTWXRefIntDocNo  = $('#oetTWXRefIntDocNo').val();
        var tTWXRefIntDocDateFrm  = $('#oetTWXRefIntDocDateFrm').val();
        var tTWXRefIntDocDateTo  = $('#oetTWXRefIntDocDateTo').val();
        var tTWXRefIntStaDoc  = $('#oetTWXRefIntStaDoc').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docBKOCallRefIntDocDataTablePO",
            data: {
                'tTWXRefIntBchCode'     : tTWXRefIntBchCode,
                'tTWXRefIntDocNo'       : tTWXRefIntDocNo,
                'tTWXRefIntDocDateFrm'  : tTWXRefIntDocDateFrm,
                'tTWXRefIntDocDateTo'   : tTWXRefIntDocDateTo,
                'tTWXRefIntStaDoc'      : tTWXRefIntStaDoc,
                'nTWXRefIntPageCurrent' : nPageCurrent,
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