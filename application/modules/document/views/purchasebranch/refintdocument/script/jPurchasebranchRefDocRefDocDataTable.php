<script>

$('.xPurchaseInvoiceRefInt').click(function(){
    var tBchCode = $(this).data('bchcode');
    var tDocNo = $(this).data('docno');
    $('.xPurchaseInvoiceRefInt').removeClass('active');
    $(this).addClass('active');

})

// Function Check Data Search And Add In Tabel DT Temp
function JSvPRBRefIntClickPageList(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld    = $('.xWPRBPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld    = $('.xWPRBPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSxRefIntDocHDDataTable(nPageCurrent);
}


// ดึงรายละเอียดภายในเอกสารอ้างอิง
function JSxPRBCallRefIntDocDetailDataTable(ptBchCode,ptDocNo){

    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docPRBCallRefIntDocDetailDataTable",
        data: {
            'ptBchCode'     : ptBchCode,
            'ptDocNo'       : ptDocNo
        },
        cache: false,
        Timeout: 0,
        success: function (oResult){
            $('#odvPRBRefIntDocDetail').html(oResult);
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // JSxRefIntDocHDDataTable(pnPage)
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}



</script>