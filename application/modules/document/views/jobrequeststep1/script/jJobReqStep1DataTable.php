<script type="text/javascript">
    localStorage.removeItem("LocalItemData");
    $(document).ready(function(){
        $('.ocbListItem').unbind().click(function(){
            var nCode   = $(this).parent().parent().parent().data('code');  //code
            var tName   = $(this).parent().parent().parent().data('name');  //code
            $(this).prop('checked', true); 
            var LocalItemData   = localStorage.getItem("LocalItemData");
            var obj = [];
            if(LocalItemData){
                obj = JSON.parse(LocalItemData);
            }else{}
            var aArrayConvert   = [JSON.parse(localStorage.getItem("LocalItemData"))];
            if(aArrayConvert == '' || aArrayConvert == null){
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxJR1TextinModal();
            }else{
                var aReturnRepeat = JStJR1FindObjectByKey(aArrayConvert[0],'nCode',nCode);
                if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                    obj.push({"nCode": nCode, "tName": tName });
                    localStorage.setItem("LocalItemData",JSON.stringify(obj));
                    JSxJR1TextinModal();
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
                    JSxJR1TextinModal();
                }
            }
            JSxShowButtonChoose();
        });
        // Event Click Confrim Delete Multiple
        $('#odvJR1ModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSoJR1DelDocMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });


    // Insert Text In Modal Delete
    function JSxJR1TextinModal(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") { } else {
            var tTextCode   = "";
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
            $("#odvJR1ModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
            $("#odvJR1ModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
        }
    }

    // เช็คค่าใน array [หลายรายการ]
    function JStJR1FindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    // เปิดปุ่มให้ลบได้ กรณีลบหลายรายการ
    function JSxShowButtonChoose() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
            $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
        } else {
            nNumOfArr = aArrayConvert[0].length;
            if (nNumOfArr > 1) {
                $('#odvMngTableList #oliBtnDeleteAll').removeClass('disabled');
            } else {
                $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
            }
        }
    }

    // Click Next Page Controll Pagination
    function JSvJR1ClickPageList(ptPage){
        var nPageCurrent    = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWJR1PageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWJR1PageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JSvJR1CallPageDataTable(nPageCurrent);
    }
    
    // Delete Document Multiple
    function JSoJR1DelDocMultiple(){
        var aDataDelMultiple    = $('#odvJR1ModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
        var aTextsDelMultiple   = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
        var aDataSplit          = aTextsDelMultiple.split(" , ");
        var nDataSplitlength    = aDataSplit.length;
        var aNewIdDelete        = [];
        for ($i = 0; $i < nDataSplitlength; $i++) {
            aNewIdDelete.push(aDataSplit[$i].trim());
        }
        if (nDataSplitlength > 1) {
            JCNxOpenLoading();

            $.ajax({
                type    : "POST",
                url     : "docJR1EventDelete",
                data    : {'tDataDocNo' : aNewIdDelete},
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    setTimeout(function () {
                        $('#odvJR1ModalDelDocMultiple').modal('hide');
                        $('#odvJR1ModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                        $('#odvJR1ModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                        $('.modal-backdrop').remove();
                        localStorage.removeItem('LocalItemData');
                        JSvJR1CallPageDataTable();
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    // Delete Document Single
    function JSoJR1DelDocSingle(ptCurrentPage, ptJR1DocNo, ptJR1AgnCode, ptJR1BchCode, ptJR1DocRefCode) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            if (typeof(ptJR1DocNo) != undefined && ptJR1DocNo != "") {
                var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptJR1DocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvJR1ModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvJR1ModalDelDocSingle').modal('show');
                $('#odvJR1ModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docJR1EventDelete",
                        data: {
                            'tDataDocNo': ptJR1DocNo,
                            'tJR1AgnCode' : ptJR1AgnCode,
                            'tJR1BchCode' : ptJR1BchCode,
                            'tJR1DocRefCode' : ptJR1DocRefCode
                        },
                        cache: false,
                        timeout: 0,
                        success: function(oResult) {
                            var aReturnData = JSON.parse(oResult);
                            if (aReturnData['nStaEvent'] == '1') {
                                $('#odvJR1ModalDelDocSingle').modal('hide');
                                $('#odvJR1ModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                JSvJR1CallPageDataTable(ptCurrentPage);
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

</script>