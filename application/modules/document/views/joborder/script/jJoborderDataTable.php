<script type="text/javascript">
    localStorage.removeItem("LocalItemData");
    $(document).ready(function(){
        $('.ocbListItem').unbind().click(function(){
            var nCode = $(this).parent().parent().parent().data('code');  //code
            var tName = $(this).parent().parent().parent().data('name');  //code
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
                JSxJOBTextinModal();
            }else{
                var aReturnRepeat = JStJOBFindObjectByKey(aArrayConvert[0],'nCode',nCode);
                if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                    obj.push({"nCode": nCode, "tName": tName });
                    localStorage.setItem("LocalItemData",JSON.stringify(obj));
                    JSxJOBTextinModal();
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
                    JSxJOBTextinModal();
                }
            }
            JSxShowButtonChoose();
        });

        $('#odvJOBModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSoJOBDelDocMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });

//Function: Insert Text In Modal Delete
function JSxJOBTextinModal() {
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
        $("#odvJOBModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
        $("#odvJOBModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
    }
}

    

//เปิดปุ่มให้ลบได้ กรณีลบหลายรายการ
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

//ลบคอลัมน์ในฐานข้อมูล เช็คค่าใน array [หลายรายการ]
function JStJOBFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// Function : Function Check Data Search And Add In Tabel DT Temp
function JSvJOBClickPageList(ptPage){
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
    //JCNxOpenLoading();
    JSvJOBCallPageDataTable(nPageCurrent);
}

//ลบเอกสาร หลายตัว
function JSoJOBDelDocMultiple(){
    var aDataDelMultiple    = $('#odvJOBModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
    var aTextsDelMultiple   = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
    var aDataSplit          = aTextsDelMultiple.split(" , ");
    var nDataSplitlength    = aDataSplit.length;
    var aNewIdDelete        = [];
    for ($i = 0; $i < nDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }
    if (nDataSplitlength > 1) {
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docJOBEventDelete",
            data    : {'tDataDocNo' : aNewIdDelete},
            cache   : false,
            timeout : 0,
            success : function (oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    setTimeout(function () {
                        $('#odvJOBModalDelDocMultiple').modal('hide');
                        $('#odvJOBModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                        $('#odvJOBModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                        $('.modal-backdrop').remove();
                        localStorage.removeItem('LocalItemData');
                        JSvJOBCallPageDataTable();
                        }, 1000);
                } else {
                    JCNxCloseLoading();
                    FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

//ลบเอกสารเดี่ยว
function JSoJOBDelDocSingle(ptCurrentPage, ptJOBDocNo, ptJOBAgnCode, ptJOBBchCode, ptJOBDocRefCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptJOBDocNo) != undefined && ptJOBDocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptJOBDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvJOBModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvJOBModalDelDocSingle').modal('show');
            $('#odvJOBModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docJOBEventDelete",
                    data: {
                        'tDataDocNo': ptJOBDocNo,
                        'tJOBAgnCode' : ptJOBAgnCode,
                        'tJOBBchCode' : ptJOBBchCode,
                        'tJOBDocRefCode' : ptJOBDocRefCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvJOBModalDelDocSingle').modal('hide');
                            $('#odvJOBModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                            JSvJOBCallPageDataTable(ptCurrentPage);
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