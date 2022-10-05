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

    $('#obtPRSBrowseBchRefIntDoc').click(function(){ 
        $('#odvPRSModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oPRSBrowseBranchOption  = undefined;
                oPRSBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetPRSRefIntBchCode',
                    'tReturnInputName'  : 'oetPRSRefIntBchName',
                    'tNextFuncName'     : 'JSxPRSRefIntNextFunctBrowsBranch',
                    'tPOAgnCode'        : $('#oetPRSAgnCode').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oPRSBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });


    $('#obtPRSBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetPRSRefIntDocDateFrm').datepicker('show');
    });


    $('#obtPRSBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetPRSRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvPRSModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvPRSModalRefIntDoc').css('overflow','auto');
 
});

$('#odvPRSModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvPRSModalRefIntDoc').css('overflow','auto');
});

function JSxPRSRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
      $('#odvPRSModalRefIntDoc').modal("show");
    
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
        var tPRSRefIntBchCode  = $('#oetPRSRefIntBchCode').val();
        var tPRSRefIntDocNo  = $('#oetPRSRefIntDocNo').val();
        var tPRSRefIntDocDateFrm  = $('#oetPRSRefIntDocDateFrm').val();
        var tPRSRefIntDocDateTo  = $('#oetPRSRefIntDocDateTo').val();
        var tPRSRefIntStaDoc  = $('#oetPRSRefIntStaDoc').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPRSCallRefIntDocDataTable",
            data: {
                'tPRSRefIntBchCode'     : tPRSRefIntBchCode,
                'tPRSRefIntDocNo'       : tPRSRefIntDocNo,
                'tPRSRefIntDocDateFrm'  : tPRSRefIntDocDateFrm,
                'tPRSRefIntDocDateTo'   : tPRSRefIntDocDateTo,
                'tPRSRefIntStaDoc'      : tPRSRefIntStaDoc,
                'nPRSRefIntPageCurrent' : nPageCurrent,
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