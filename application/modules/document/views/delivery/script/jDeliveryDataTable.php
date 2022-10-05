<script type="text/javascript">

    $(document).ready(function(){
        $('.ocbListItem').unbind().click(function(){
            var nCode = $(this).parent().parent().parent().data('code');  
            var tName = $(this).parent().parent().parent().data('name');  
            $(this).prop('checked', true);
            var LocalItemData = localStorage.getItem("LocalItemData");
            var obj = [];
            if(LocalItemData){
                obj = JSON.parse(LocalItemData);
            }else{ }
            var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
            if(aArrayConvert == '' || aArrayConvert == null){
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxDLVTextinModal();
            }else{
                var aReturnRepeat = JStDLVFindObjectByKey(aArrayConvert[0],'nCode',nCode);
                if(aReturnRepeat == 'None' ){         
                    obj.push({"nCode": nCode, "tName": tName });
                    localStorage.setItem("LocalItemData",JSON.stringify(obj));
                    JSxDLVTextinModal();
                }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                    localStorage.removeItem("LocalItemData");
                    $(this).prop('checked', false);
                    var nLength = aArrayConvert[0].length;
                    for($i=0; $i<nLength; $i++){
                        if(aArrayConvert[0][$i].nCode == nCode){
                            delete aArrayConvert[0][$i];
                        }
                    }
                    var aNewarraydata = [];
                    for($i=0; $i<nLength; $i++){
                        if(aArrayConvert[0][$i] != undefined){
                            aNewarraydata.push(aArrayConvert[0][$i]);
                        }
                    }
                    localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                    JSxDLVTextinModal();
                }
            }
            JSxDLVShowButtonChoose();
        });

        $('#odvDLVModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSoDLVDelDocMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });

    // ลบช้อมูล
    function JSoDLVDelDocSingle(ptCurrentPage, ptDLVDocNo, tBchCode){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            if (typeof(ptDLVDocNo) != undefined && ptDLVDocNo != "") {
                var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptDLVDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvDLVModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvDLVModalDelDocSingle').modal('show');
                $('#odvDLVModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url : "docDLVEventDelete",
                        data: {
                            'tDataDocNo'        : ptDLVDocNo,
                            'tBchCode'          : tBchCode
                        },
                        cache: false,
                        timeout: 0,
                        success: function(oResult) {
                            var aReturnData = JSON.parse(oResult);
                            if (aReturnData['nStaEvent'] == '1') {
                                $('#odvDLVModalDelDocSingle').modal('hide');
                                $('#odvDLVModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                    JSvDLVCallPageDataTable(ptCurrentPage);
                                }, 500);
                            } else {
                                JCNxCloseLoading();
                                FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            } else {
                FSvCMNSetMsgErrorDialog('Error Not Found Document Number !!');
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //Function: Insert Text In Modal Delete
    function JSxDLVTextinModal() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") { } else {
            var tTextCode = "";
            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tTextCode += aArrayConvert[0][$i].nCode;
                tTextCode += " , ";
            }

            //Disabled ปุ่ม Delete
            if (aArrayConvert[0].length > 1) {
                $(".xCNIconDel").addClass("xCNDisabled");
            } else {
                $(".xCNIconDel").removeClass("xCNDisabled");
            }
            $("#odvDLVModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
            $("#odvDLVModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
        }
    }

    // Function : Function Check Data Search And Add In Tabel DT Temp
    function JSvDLVClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWDLVPageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWDLVPageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSvDLVCallPageDataTable(nPageCurrent);
    }

    // Function: Function Chack And Show Button Delete All
    function JSxDLVShowButtonChoose() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
            $("#oliDLVBtnDeleteAll").addClass("disabled");
        } else {
            nNumOfArr = aArrayConvert[0].length;
            if (nNumOfArr > 1) {
                $("#oliDLVBtnDeleteAll").removeClass("disabled");
            } else {
                $("#oliDLVBtnDeleteAll").addClass("disabled");
            }
        }
    }

    // Function: Event Single Delete Doc Mutiple
    function JSoDLVDelDocMultiple() {
        var aDataDelMultiple = $('#odvDLVModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
        var aTextsDelMultiple = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
        var aDataSplit = aTextsDelMultiple.split(" , ");
        var nDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        for ($i = 0; $i < nDataSplitlength; $i++) {
            aNewIdDelete.push(aDataSplit[$i]);
        }
        if (nDataSplitlength > 1) {

            JCNxOpenLoading();
            $('.ocbListItem:checked').each(function() {
                var tDataDocNo = $(this).val();
                var tBchCode = $(this).data('bchcode');
                var tDLVRefInCode = $(this).data('refcode');
                localStorage.StaDeleteArray = '1';
                $.ajax({
                    type    : "POST",
                    url     : "docDLVEventDelete",
                    data    : {
                        'tDataDocNo': tDataDocNo,
                        'tBchCode': tBchCode,
                        'tDLVRefInCode': tDLVRefInCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            setTimeout(function() {
                                $('#odvDLVModalDelDocMultiple').modal('hide');
                                $('#odvDLVModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                                $('#odvDLVModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                                $('.modal-backdrop').remove();
                                localStorage.removeItem('LocalItemData');
                                    JSvDLVCallPageList();
                                }, 1000);
                        } else {
                            JCNxCloseLoading();
                            FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });

            });


        }
    }


</script>