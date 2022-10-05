<script type="text/javascript">
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
        JSxDOTextinModal();
    }else{
        var aReturnRepeat = JStDOFindObjectByKey(aArrayConvert[0],'nCode',nCode);
        if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
            obj.push({"nCode": nCode, "tName": tName });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxDOTextinModal();
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
            JSxDOTextinModal();
        }
    }
    JSxDOShowButtonChoose();
});

$('#odvDOModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        JSoDODelDocMultiple();
    }else{
        JCNxShowMsgSessionExpired();
    }
});

//Function: Insert Text In Modal Delete
function JSxDOTextinModal() {
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
        $("#odvDOModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
        $("#odvDOModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
    }
}

// Function : Function Check Data Search And Add In Tabel DT Temp
function JSvDOClickPageList(ptPage){
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
    JSvDOCallPageDataTable(nPageCurrent);
}
</script>