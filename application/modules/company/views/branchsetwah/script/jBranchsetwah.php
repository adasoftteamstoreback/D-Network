<script type="text/javascript">
    
    $(document).ready(function() {
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        JSxControlNavDefult('pageadd');

        //วิ่งเข้าหน้า List
        JSvCallPageBranchSetWah();
    });

    function JSxControlNavDefult(ptType) {
        if(ptType == 'pageadd'){
            $('#oliBranchSetWahAdd').hide();
            $('#oliBranchSetWahEdit').hide();
            $('#odvBtnBranchSetWahEditInfo').hide();
            $('#odvBtnBranchSetWahInfo').show();
            $('.obtChoose').hide();
        }else{
            $('#oliBranchSetWahAdd').show();
            $('#oliBranchSetWahEdit').hide();
            $('#odvBtnBranchSetWahEditInfo').show();
            $('#odvBtnBranchSetWahInfo').hide();
            $('.obtChoose').show();
        }
    }

    //ข้อมูลหลัก
    function JSvCallPageBranchSetWah() {
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "branchSettingWahouseList",
            cache: false,
            success: function (tResult) {
                $('#odvContentPageBranchSetWah').html(tResult);
                JSvBranchSetWahDataTable(1);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    //ข้อมูลตาราง
    function JSvBranchSetWahDataTable(pnPage){
        var tSearchAll = $('#odvContentPageBranchSetWah #oetSearchAll').val();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == '') {
            nPageCurrent = '1';
        }
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "branchSettingWahouseDataTable",
            data    : {
                'tSearchAll'    : tSearchAll,
                'nPageCurrent'  : nPageCurrent,
                'tBchCode'      : '<?=$aItem['tBchCode']?>' , 
                'tAgnCode'      : '<?=$aItem['tAgnCode']?>'
            },
            cache: false,
            Timeout: 5000,
            success: function (tResult) {
                if (tResult != "") {
                    $('#odvContentPageBranchSetWah #ostBranchsetWah').html(tResult);
                }
                JSxControlNavDefult('pageadd');
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    //หน้าจอเพิ่ม
    function JSvCallPageBranchSetWahAdd(){
        $.ajax({
            type    : "POST",
            url     : "branchSettingWahousePageAdd",
            data    : { 'tBchCode' : '<?=$aItem['tBchCode']?>' , 'tAgnCode' : '<?=$aItem['tAgnCode']?>'},
            cache   : false,
            success: function (tResult) {
                $('#odvContentPageBranchSetWah').html(tResult);
                JSxControlNavDefult('pageedit');
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //หน้าจอเเก้ไข
    function JSxBranchSetWahPageEdit(pnSeq,ptWah){
        $.ajax({
            type    : "POST",
            url     : "branchSettingWahousePageEdit",
            data    : { 
                'tBchCode' : '<?=$aItem['tBchCode']?>' , 
                'tAgnCode' : '<?=$aItem['tAgnCode']?>' ,
                'nSeq'    : pnSeq,
                'tWah'    : ptWah
            },
            cache   : false,
            success: function (tResult) {
                $('#odvContentPageBranchSetWah').html(tResult);
                JSxControlNavDefult('pageedit');
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กด page
    function JSvClickPage(ptPage) {
        var nPageCurrent = '';
        var nPageNew;
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld = $('.xWPageWah .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWPageWah .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSvBranchSetWahDataTable(nPageCurrent);
    }

</script>