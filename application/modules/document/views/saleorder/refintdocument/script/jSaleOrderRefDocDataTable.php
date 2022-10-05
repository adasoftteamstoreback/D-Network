<script>

$('.xDocuemntRefInt').click(function(){
    var tBchCode = $(this).data('bchcode');
    var tDocNo = $(this).data('docno');
    var tDocType = $(this).data('doctype');
    JSxSOCallRefIntDocDetailDataTable(tBchCode,tDocNo,tDocType);
    $('.xDocuemntRefInt').removeClass('active');
    $(this).addClass('active');
})

// Function Check Data Search And Add In Tabel DT Temp
function JSvSORefIntClickPageList(ptPage){
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
function JSxSOCallRefIntDocDetailDataTable(ptBchCode,ptDocNo,tDocType){
    JCNxOpenLoading();
    $.ajax({
        type    : "POST",
        url     : "docSORefIntDocDetailDataTable",
        data    : {
            'ptBchCode'     : ptBchCode,
            'ptDocNo'       : ptDocNo,
            'tDocType'       : tDocType
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