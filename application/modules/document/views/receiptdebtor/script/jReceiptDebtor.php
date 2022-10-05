<script>
    var nStaBrowseType   = $('#oetRCBStaBrowse').val();
    var tCallBackOption  = $('#oetRCBCallBackOption').val();

    $('document').ready(function() {
        localStorage.removeItem('LocalItemData');
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        JCNxOpenLoading();
        JSxRCBNavDefult();
        JSxRCBPageList();
    });

    //ซ่อนปุ่มต่างๆ
    // Create By: Napat(Jame) 02/07/2021
    function JSxRCBNavDefult() {
        try {
            $('.xCNRCBMaster').show();
            // $('#oliRCBTitleAdd').hide();
            $('#oliRCBTitleEdit').hide();
            $('#odvRCBBtnAddEdit').hide();
            $('#odvRCBBtnInfo').show();
        } catch (oErr) {
            FSvCMNSetMsgWarningDialog(oErr.message);
        }
    }

    // เรียกหน้า List มาแสดง
    // Create By: Napat(Jame) 02/07/2021
    function JSxRCBPageList() {
        try {
            JSxCheckPinMenuClose();
            JCNxOpenLoading();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                $('#oetSearchAll').val('');
                $.ajax({
                    type: "POST",
                    url: "docRCBPageList",
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        $('#odvRCBContent').html(tResult);
                        JSxRCBPageDatatable();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                JCNxShowMsgSessionExpired();
            }
        } catch (oErr) {
            FSvCMNSetMsgWarningDialog(oErr.message);
        }
    }

    // เรียกหน้า DataTable มาแสดง
    // Create By: Napat(Jame) 02/07/2021
    function JSxRCBPageDatatable(pnPage,ptTypeSearch) {
        try {
            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = $('#ohdRCBOldPageList').val();
            }

            var aSearchList = {
                tAgnCode   : $('#oetRCBAgnCode').val(),
                tBchCode   : $('#oetRCBBchCode').val(),
                tStaPrcDoc : $('#ocmRCBStaPrcDoc').val(),
                tStaDocAct : $('#oetRCBStaDocAct').val(),
                tDocType   : $('#oetRCBDocType').val(),
                tDocNo     : $('#oetRCBFilterDocNo').val(),
                tChnCode   : $('#oetRCBChannel').val()
            };

            if( ptTypeSearch != "ADD" ){
                var aOldFilterList = $('#ohdRCBOldFilterList').val();
                if( aOldFilterList != "" ){
                    aSearchList = JSON.parse(aOldFilterList);
                    $('#oetRCBAgnCode').val(aSearchList['tAgnCode']);
                    $('#oetRCBBchCode').val(aSearchList['tBchCode']);
                    $('#oetRCBDocDate').val(aSearchList['dDocDate']);
                    $('#ocmRCBStaPrcDoc').val(aSearchList['tStaPrcDoc']);
                    $('#oetRCBChannel').val(aSearchList['tChnCode']);
                    $('#oetRCBFilterDocNo').val(aSearchList['tDocNo']);
                    $('#oetRCBDocType').val(aSearchList['tDocType']);
                    $('.selectpicker').selectpicker('refresh')
                }
            }else{
                $('#ohdRCBOldFilterList').val(JSON.stringify(aSearchList));
            }
            $('#ohdRCBOldPageList').val(nPageCurrent);

            $.ajax({
                type: "POST",
                url: "docRCBPageDataTable",
                data: {
                    pnPageCurrent : nPageCurrent,
                    paSearchList  : aSearchList
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    JSxRCBNavDefult();
                    JCNxLayoutControll();
                    $('#ostRCBContentDatatable').html(tResult);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } catch (oErr) {
            FSvCMNSetMsgWarningDialog(oErr.message);
        }
    }

    // เปลี่ยนหน้า 1 2 3 ..
    // Create By: Napat(Jame) 02/07/2021
    function JSxRCBEventClickPage(ptPage) {
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var nPageCurrent = "";
            switch (ptPage) {
                case "next": //กดปุ่ม Next
                    $(".xWBtnNext").addClass("disabled");
                    nPageOld = $(".xWPageRCBPdt .active").text(); // Get เลขก่อนหน้า
                    nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent = nPageNew;
                    break;
                case "previous": //กดปุ่ม Previous
                    nPageOld = $(".xWPageRCBPdt .active").text(); // Get เลขก่อนหน้า
                    nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent = nPageNew;
                    break;
                default:
                    nPageCurrent = ptPage;
            }
            JSxRCBPageDatatable(nPageCurrent);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //Page - Edit
    function JSxRCBPageEdit(ptDocNo) {
        JSxCheckPinMenuClose();
        try {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docRCBPageEdit",
                    data: {
                        ptDocNo: ptDocNo
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $(window).scrollTop(0);
                            $('.xCNRCBMaster').show();
                            $('#oliRCBTitleEdit').show();
                            $('#odvBtnRCBInfo').hide();
                            $('#odvRCBBtnAddEdit').show();
                            $('#odvRCBContent').html(aReturnData['tViewPageAdd']);
                            JCNxCloseLoading();

                            JCNxLayoutControll();
                        } else {
                            var tMessageError = aReturnData['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                JCNxShowMsgSessionExpired();
            }
        } catch (oErr) {
            FSvCMNSetMsgWarningDialog(oErr.message);
        }
    }
</script>