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

    $('#obtPOBrowseBchRefIntDoc').click(function(){ 
        $('#odvPOModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oPOBrowseBranchOption  = undefined;
                oPOBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetPORefIntBchCode',
                    'tReturnInputName'  : 'oetPORefIntBchName',
                    'tNextFuncName'     : 'JSxPORefIntNextFunctBrowsBranch',
                    'tPOAgnCode'        : $('#oetPOAgnCodeFrm').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oPOBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });


    $('#obtPOBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetPORefIntDocDateFrm').datepicker('show');
    });


    $('#obtPOBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetPORefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvPOModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvPOModalRefIntDoc').css('overflow','auto');
 
});

$('#odvPOModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvPOModalRefIntDoc').css('overflow','auto');
});

function JSxPORefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
      $('#odvPOModalRefIntDoc').modal("show");
    
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
        var tPORefIntBchCode  = $('#oetPORefIntBchCode').val();
        var tPORefIntDocNo  = $('#oetPORefIntDocNo').val();
        var tPORefIntDocDateFrm  = $('#oetPORefIntDocDateFrm').val();
        var tPORefIntDocDateTo  = $('#oetPORefIntDocDateTo').val();
        var tPORefIntStaDoc  = $('#oetPORefIntStaDoc').val();
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPOCallRefIntDocDataTable",
            data: {
                'tPORefIntBchCode'     : tPORefIntBchCode,
                'tPORefIntDocNo'       : tPORefIntDocNo,
                'tPORefIntDocDateFrm'  : tPORefIntDocDateFrm,
                'tPORefIntDocDateTo'   : tPORefIntDocDateTo,
                'tPORefIntStaDoc'      : tPORefIntStaDoc,
                'nPORefIntPageCurrent' : nPageCurrent,
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