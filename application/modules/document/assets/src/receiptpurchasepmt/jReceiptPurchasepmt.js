
$(document).on("keypress", 'form', function (e) {
    var code    = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();
        return false;
    }
})

$("document").ready(function () {
    localStorage.removeItem("LocalItemData");
    localStorage.removeItem("RPP_LocalItemDataInsertDtTemp");
    JSxCheckPinMenuClose(); 
    JSxRPPNavDefult('showpage_list');

    // Click Menut Title Nav
    $('#oliRPPTitle').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvRPPCallPageList();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Click Back Page Button
    $('#obtRPPCallBackPage').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvRPPCallPageList();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // หน้าจอเพิ่มข้อมูล
    $('#obtRPPCallPageAdd').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvRPPCallPageAdd();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // รองรับการเข้ามาแบบ Noti
    var nStaRPProwseType    = $('#oetRPPJumpBrwType').val();
    switch(nStaRPProwseType){
        case '2':
            var tAgnCode    = $('#oetRPPJumpAgnCode').val();
            var tBchCode    = $('#oetRPPJumpBchCode').val();
            var tDocNo      = $('#oetRPPJumpDocNo').val();
            JSvRPPCallPageEdit(tDocNo);
        break;
        default:
            JSvRPPCallPageList();
    }
});

// Function : Control เมนู
// Creator  : 23/03/2022 Wasin
function JSxRPPNavDefult(ptType) {
    // Check Type Call Nav Default
    switch(ptType){
        case 'showpage_list':
            // เปิดปุ่ม
            $("#oliRPPTitle").show();
            $("#odvRPPBtnGrpInfo").show();
            $("#obtRPPCallPageAdd").show();
            // ซ่อนปุ่ม
            $("#oliRPPTitleAdd").hide();
            $("#oliRPPTitleEdit").hide();
            $("#oliRPPTitleDetail").hide();
            $("#oliRPPTitleAprove").hide();
            $("#odvBtnAddEdit").hide();
            $("#obtRPPCallBackPage").hide();
            $("#obtRPPPrintDoc").hide();
            $("#obtRPPCancelDoc").hide();
            $("#obtRPPApproveDoc").hide();
            $('#obtRPPApproveDocHQ').hide();
            $("#odvRPPBtnGrpSave").hide();
            $('#obtRPPSaveAndApvDoc').hide();
        break;
        case 'showpage_add':
            // เปิดปุ่ม
            $("#oliRPPTitle").show();
            $("#odvRPPBtnGrpSave").show();
            $("#obtRPPCallBackPage").show();
            $("#oliRPPTitleAdd").show();
            // ซ่อนปุ่ม
            $("#oliRPPTitleEdit").hide();
            $("#oliRPPTitleDetail").hide();
            $("#oliRPPTitleAprove").hide();
            $("#odvBtnAddEdit").hide();
            $("#obtRPPPrintDoc").hide();
            $("#obtRPPCancelDoc").hide();
            $("#obtRPPApproveDoc").hide();
            $('#obtRPPApproveDocHQ').hide();
            $("#odvRPPBtnGrpInfo").hide();
            $("#obtRPPSaveAndApvDoc").hide();
        break;
        case 'showpage_edit':
            // เปิดปุ่ม
            $("#oliRPPTitle").show();
            $("#odvRPPBtnGrpSave").show();
            $("#obtRPPApproveDoc").show();
            $("#obtRPPSaveAndApvDoc").show();
            $("#obtRPPCancelDoc").show();
            $("#obtRPPCallBackPage").show();
            $("#oliRPPTitleEdit").show();
            $("#obtRPPPrintDoc").show();
            // ซ่อนปุ่ม
            $("#oliRPPTitleAdd").hide();
            $("#oliRPPTitleDetail").hide();
            $("#oliRPPTitleAprove").hide();
            $("#odvBtnAddEdit").hide();
            $("#odvRPPBtnGrpInfo").hide();
        break;
    }
    // ล้างค่า Local Storage
    localStorage.removeItem('RPP_LocalItemDataDelDtTemp');
    localStorage.removeItem('LocalItemData');
}

// Function : Call Page List
// Creator  : 23/03/2022 Wasin
function JSvRPPCallPageList() {
    $.ajax({
        type    : "POST",
        url     : "docRPPPageList",
        cache   : false,
        timeout : 0,
        success : function(tResult) {
            $("#odvRPPContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSxRPPNavDefult('showpage_list');
            JSvRPPCallPageDataTable(1);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : โหลดข้อมูลตาราง
// Creator  : 23/03/2022 Wasin
function JSvRPPCallPageDataTable(pnPage) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        var oAdvanceSearch = JSoRPPGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type    : "POST",
            url     : "docRPPPageDataTable",
            data    : {
                oAdvanceSearch  : oAdvanceSearch,
                nPageCurrent    : nPageCurrent
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSxRPPNavDefult('showpage_list');
                    $('#ostContentRPP').html(aReturnData['tViewDataTable']);
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Function : ข้อมูลค้นหาขั้นสูง
// Creator  : 23/03/2022 Wasin
function JSoRPPGetAdvanceSearchData() {
    try {
        let oAdvanceSearchData = {
            tSearchAll          : $("#oetSearchAll").val(),
            tSearchBchCodeFrom  : $("#oetBchCodeFrom").val(),
            tSearchBchCodeTo    : $("#oetBchCodeTo").val(),
            tSearchSPLCodeFrom  : $('#oetSplCodeFrom').val(),
            tSearchSPLCodeTo    : $('#oetSplCodeTo').val(),
            tSearchDocDateFrom  : $("#oetSearchDocDateFrom").val(),
            tSearchDocDateTo    : $("#oetSearchDocDateTo").val(),
            tSearchStaDoc       : $("#ocmStaDoc").val(),
        };
        return oAdvanceSearchData;
    } catch (err) {
        console.log("ค้นหาขั้นสูง Error: ", err);
    }
}

// Function : เข้าหน้าแบบ เพิ่ม
// Creator  : 23/03/2022 Wasin
function JSvRPPCallPageAdd(){
    try{
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docRPPPageAdd",
                cache   : false,
                timeout : 0,
                success: function(oResult) {
                    let aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxRPPNavDefult('showpage_add');
                        $('#odvRPPContentPageDocument').html(aReturnData['tViewPageAdd']);
                        JCNxCloseLoading();
                    }else {
                        let tMessageError   = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }catch(err){
        console.log('JSvRPPCallPageAdd Error: ', err);
    }
}

//กด Next Page
function JSvRPPClickPageList(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld    = $('.xWPageRPPPdt .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld    = $('.xWPageRPPPdt .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvRPPCallPageDataTable(nPageCurrent);
}

// Function : เซตข้อความ กรณีลบหลายรายการ
// Creator  : 23/03/2022 Wasin
function JSxTextinModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {} else {
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

        $("#ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
        $("#ohdConfirmIDDelMultiple").val(tTextCode);
    }
}

// Function : เปิดปุ่มให้ลบได้ กรณีลบหลายรายการ
// Creator  : 23/03/2022 Wasin
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