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

    $('#obtIVBrowseBchRefIntDoc').click(function(){ 
        $('#odvIVModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            window.oIVBrowseBranchOption  = undefined;
            oIVBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'oetIVRefIntBchCode',
                'tReturnInputName'  : 'oetIVRefIntBchName',
                'tNextFuncName'     : 'JSxIVRefIntNextFunctBrowsBranch',
                'tIVAgnCode'        : $('#oetIVAgnCodeFrm').val(),
                'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
            });
            JCNxBrowseData('oIVBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtIVBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetIVRefIntDocDateFrm').datepicker('show');
    });

    $('#obtIVBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetIVRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvIVModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvIVModalRefIntDoc').css('overflow','auto');
 
});

$('#odvIVModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvIVModalRefIntDoc').css('overflow','auto');
});

function JSxIVRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
    $('#odvIVModalRefIntDoc').modal("show");
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
    var nPageCurrent        = pnNewPage;
    var tIVRefIntBchCode    = $('#oetIVRefIntBchCode').val();
    var tIVRefIntDocNo      = $('#oetIVRefIntDocNo').val();
    var tIVRefIntDocDateFrm = $('#oetIVRefIntDocDateFrm').val();
    var tIVRefIntDocDateTo  = $('#oetIVRefIntDocDateTo').val();
    var tIVRefIntStaDoc     = $('#oetIVRefIntStaDoc').val();
    var tIVSPLCode          = $('#ohdIVSPLCode').val();
    var tIVSPLStaLocal      = $('#ohdIVSPLStaLocal').val();

    if(tIVSPLStaLocal == 1){
        $('#odvIVModalRefIntDoc .xCNTextModalHeard').text('อ้างอิงเอกสารใบรับของ');
    }else{
        if($('#ocbIVRefDoc').val() == 2){
            $('#odvIVModalRefIntDoc .xCNTextModalHeard').text('อ้างอิงเอกสารใบสั่งซื้อ'); 
        }else{
            $('#odvIVModalRefIntDoc .xCNTextModalHeard').text('อ้างอิงเอกสารใบขาย'); 
        }
    }

    JCNxOpenLoading();

    $.ajax({
        type    : "POST",
        url     : "docInvoiceRefIntDocDataTable",
        data    : {
            'tIVSPLCode'           : tIVSPLCode,
            'tIVRefIntBchCode'     : tIVRefIntBchCode,
            'tIVRefIntDocNo'       : tIVRefIntDocNo,
            'tIVRefIntDocDateFrm'  : tIVRefIntDocDateFrm,
            'tIVRefIntDocDateTo'   : tIVRefIntDocDateTo,
            'tIVRefIntStaDoc'      : tIVRefIntStaDoc,
            'nIVRefIntPageCurrent' : nPageCurrent,
            'tIVSPLStaLocal'       : tIVSPLStaLocal , //1: สถานะผู้จำหน่าย local , 2: สถานะผู้จำหน่าย online,
            'tIVTypeRefDoc'        : $('#ocbIVRefDoc').val()
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