<script type="text/javascript">

   //Functionality : เปลี่ยนหน้า pagenation
    //Parameters : Event Click Pagenation
    //Creator : 06/10/2020 Worakorn
    //Return : View
    //Return Type : View
    function JSvBCMStdClickPage(ptPage) {
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld = $('.xWPageTotalByBranch .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWPageTotalByBranch .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        FSxBCMCallStandDataTable(nPageCurrent);
    }


    //Functionality : เปลี่ยนหน้า pagenation
    //Parameters : Event Click Pagenation
    //Creator : 06/10/2020 Worakorn
    //Return : View
    //Return Type : View
    function JSxBCMCallPageStand(ptBatID){

        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "dasBCMCallPageStand",
            data: {'tBatID':ptBatID},
            cache: false,
            timeout: 0,
            success: function (tResult){
                $("#odvBCMSALContentPage").html(tResult);
                $('#obtBCMBtnBackBatPage').show();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

    }

$('#obtBCMBtnStdFilter').click(function(){
    FSxBCMCallStandDataTable();
});


</script>