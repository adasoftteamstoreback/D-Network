<script>

$('.xPRSRefInt').click(function(){
    var tBchCode = $(this).data('bchcode');
    var tDocNo = $(this).data('docno');
    JSxPRSCallRefIntDocDetailDataTable(tBchCode,tDocNo);
    $('.xPRSRefInt').removeClass('active');
    $(this).addClass('active');

})

// Function Check Data Search And Add In Tabel DT Temp
function JSvPRSRefIntClickPageList(ptPage){
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
function JSxPRSCallRefIntDocDetailDataTable(ptBchCode,ptDocNo){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docPRSCallRefIntDocDetailDataTable",
        data: {
            'ptBchCode'     : ptBchCode,
            'ptDocNo'       : ptDocNo
        },
        cache: false,
        Timeout: 0,
        success: function (oResult){
            $('#odvPRSRefIntDocDetail').html(oResult);
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // JSxRefIntDocHDDataTable(pnPage)
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}



</script>