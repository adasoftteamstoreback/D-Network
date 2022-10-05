<script type="text/javascript">

$('.xPurchaseInvoiceRefInt').click(function(){
    var tBchCode = $(this).data('bchcode');
    var tDocNo = $(this).data('docno');
    var tdoctype = $(this).data('doctype');
    JSxCRVCallRefIntDocDetailDataTable(tBchCode,tDocNo,tdoctype);
    $('.xPurchaseInvoiceRefInt').removeClass('active');
    $(this).addClass('active');

})

// Function Check Data Search And Add In Tabel DT Temp
function JSvCRVRefIntClickPageList(ptPage){
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


// ดึงรายละเอียดภายในเอกสารอ้างอิง
function JSxCRVCallRefIntDocDetailDataTable(ptBchCode,ptDocNo,ptdoctype){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docCRVCallRefIntDocDetailDataTable",
        data: {
            'ptBchCode'     : ptBchCode,
            'ptDocNo'       : ptDocNo,
            'ptdoctype'     : ptdoctype
        },
        cache: false,
        Timeout: 0,
        success: function (oResult){
            $('#odvCRVRefIntDocDetail').html(oResult);
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // JSxRefIntDocHDDataTable(pnPage)
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}



</script>