<script>

$('.xDocuemntRefInt').click(function(){
    var tBchCode = $(this).data('bchcode');
    var tDocNo = $(this).data('docno');
    JSxIVCallRefIntDocDetailDataTable(tBchCode,tDocNo);
    $('.xDocuemntRefInt').removeClass('active');
    $(this).addClass('active');
})

// Function Check Data Search And Add In Tabel DT Temp
function JSvIVRefIntClickPageList(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld    = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld    = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSxRefIntDocHDDataTable(nPageCurrent);
}

// Function Check Data Search And Add In Tabel DT Temp
function JSxIVCallRefIntDocDetailDataTable(ptBchCode,ptDocNo){
    JCNxOpenLoading();
    $.ajax({
        type    : "POST",
        url     : "docInvoiceRefIntDocDetailDataTable",
        data    : {
            'ptBchCode'         : ptBchCode,
            'ptDocNo'           : ptDocNo,
            'ptSPLStaLocal'     : $('#ohdIVSPLStaLocal').val(),
            'tIVTypeRefDoc'     : $('#ocbIVRefDoc').val()
        },
        cache: false,
        Timeout: 0,
        success: function (oResult){
            $('#odvRefIntDocDetail').html(oResult);
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}
</script>