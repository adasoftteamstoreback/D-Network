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
                JSxSALTextinModal();
            }else{
                var aReturnRepeat = JStSALFindObjectByKey(aArrayConvert[0],'nCode',nCode);
                if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                    obj.push({"nCode": nCode, "tName": tName });
                    localStorage.setItem("LocalItemData",JSON.stringify(obj));
                    JSxSALTextinModal();
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
                    JSxSALTextinModal();
                }
            }
            JSxShowButtonChoose();
        });

        $('#odvSALModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSoSALDelDocMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });

//Function: Insert Text In Modal Delete
function JSxSALTextinModal() {
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
        $("#odvSALModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
        $("#odvSALModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
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
function JStSALFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// Function : Function Check Data Search And Add In Tabel DT Temp
function JSvSALClickPageList(ptPage){
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
    JSvSALCallPageDataTable(nPageCurrent);
}

//ลบเอกสาร หลายตัว
function JSoSALDelDocMultiple(){
    var aDataDelMultiple    = $('#odvSALModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
            url     : "docSALEventDelete",
            data    : {'tDataDocNo' : aNewIdDelete},
            cache   : false,
            timeout : 0,
            success : function (oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    setTimeout(function () {
                        $('#odvSALModalDelDocMultiple').modal('hide');
                        $('#odvSALModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                        $('#odvSALModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                        $('.modal-backdrop').remove();
                        localStorage.removeItem('LocalItemData');
                        JSvSALCallPageDataTable();
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
function JSoSALDelDocSingle(ptCurrentPage, ptSALDocNo, ptSALAgnCode, ptSALBchCode, ptSALDocRefCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptSALDocNo) != undefined && ptSALDocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptSALDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvSALModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvSALModalDelDocSingle').modal('show');
            $('#odvSALModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docSALEventDelete",
                    data: {
                        'tDataDocNo': ptSALDocNo,
                        'tSALAgnCode' : ptSALAgnCode,
                        'tSALBchCode' : ptSALBchCode,
                        'tSALDocRefCode' : ptSALDocRefCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvSALModalDelDocSingle').modal('hide');
                            $('#odvSALModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                            JSvSALCallPageDataTable(ptCurrentPage);
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