<script type="text/javascript">

    $('.xDLVRefInt').click(function(){
        var tBchCode    = $(this).data('bchcode');
        var tDocNo      = $(this).data('docno');
        JSxDLVCallRefIntDocDetailDataTable(tBchCode,tDocNo);
        $('.xDLVRefInt').removeClass('active');
        $(this).addClass('active');
    })

    // กดหน้า
    function JSvDLVRefIntClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWDLVREFPageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWDLVREFPageDataTable .active').text(); // Get เลขก่อนหน้า
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
    function JSxDLVCallRefIntDocDetailDataTable(ptBchCode,ptDocNo){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docDLVCallRefIntDocDetailDataTable",
            data: {
                'ptBchCode'     : ptBchCode,
                'ptDocNo'       : ptDocNo,
                'ptRefDoc'      : $("#ocbDLVRefDoc").val()
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                $('#odvDLVRefIntDocDetail').html(oResult);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

</script>
