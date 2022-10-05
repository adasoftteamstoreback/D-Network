<script type="text/javascript">
    
    $(document).ready(function() {
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/

        //วิ่งเข้าหน้า List
        JSvCallPageBranchSetspl();
    });

    //ข้อมูลหลัก
    function JSvCallPageBranchSetspl() {
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "branchSettingSPLList",
            cache: false,
            success: function (tResult) {
                $('#odvContentPageBranchSetspl').html(tResult);
                JSvBranchSetsplDataTable(1);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    //ข้อมูลตาราง
    function JSvBranchSetsplDataTable(pnPage){
        var tSearchAll = $('#odvContentPageBranchSetspl #oetSearchAll').val();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == '') {
            nPageCurrent = '1';
        }
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "branchSettingSPLDataTable",
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
                    $('#odvContentPageBranchSetspl #ostBranchsetspl').html(tResult);
                }
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    //หน้าจอเพิ่ม
    function JSvCallPageBranchSetsplAdd(){
        $('#odvModalInsertSetSpl').modal('show');
        $('#odvModalInsertSetSpl .xCNTextModalHeard').text('เพิ่มข้อมูล');
    }

    //หน้าจอแก้ไข
    function JSxBranchSetsplPageEdit(ptBCHCode , ptSPLCode){
        $.ajax({
            type    : "POST",
            url     : "branchSettingSPLPageEdit",
            data    : {
                'tBchCode'      : ptBCHCode, 
                'tSplCode'      : ptSPLCode
            },
            cache: false,
            Timeout: 5000,
            success: function (oResult) {
                $('#odvModalInsertSetSpl').modal('show');
                $('#odvModalInsertSetSpl .xCNTextModalHeard').text('แก้ไขข้อมูล');

                var tResult = JSON.parse(oResult)
                var tSPLCode = tResult.raItems.FTSplCode;
                var tSPLName = tResult.raItems.FTSplName;
                var tSPLUse  = tResult.raItems.FTStaUse;

                $('#oetBrowseSetSPLCodeOld').val(tSPLCode);
                $('#oetBrowseSetSPLCode').val(tSPLCode);
                $('#oetBrowseSetSPLName').val(tSPLName);
                if(tSPLUse == 1){
                    $('#ocbSetSPLUse').prop('checked', true);
                }else{
                    $('#ocbSetSPLUse').prop('checked', false);
                }
            },
            error: function (data) {
                console.log(data);
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
                nPageOld = $('.xWPagespl .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWPagespl .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSvBranchSetsplDataTable(nPageCurrent);
    }

</script>