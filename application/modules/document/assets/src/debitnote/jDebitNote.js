$("document").ready(function() {
    JSxDBNNavDefult('page_list');
    var tReturnCode = $('#ohdRtCode').val();
    var tDocNo      = $('#ohdDocNo').val()
    JSvDBNCallPageList();
    JSxCheckPinMenuClose();

    $('#obtBtnBack').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {

            //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
            if(localStorage.tCheckBackStage == 'PageDailyWorkOrder'){
                JSxBackStageToDailyJob();
            }else if(localStorage.tCheckBackStage == 'PageCarDetail'){
                JSxBackStageToCarDetail();
            }else{ //กลับสู่หน้า List
                JSvDBNCallPageList();
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    });
});

// ------------------------------------------------ controll layout button -----------------------------------------------------------------
function JSxDBNNavDefult(ptType) { 
    // controll layout button
    if (ptType == "page_list") { 
        // controller title
        $('#oliDBNTitleViewData').hide();
        $('#oliDBNTitleAdd').hide();
        $('#oliDBNTitleEdit').hide();
        // controll Button
        $('#obtBtnBack').hide();
        $('#obtDBNPrintDoc').hide();
        $('#obtDBNCancelDoc').hide();
        $('#obtDBNApproveDoc').hide();
        $('#odvDBNBtnGrpSave').hide();
        $('#obtDBNCallPageAdd').show();
    } else if (ptType == "page_add") { 
        // controller title
        $('#oliDBNTitleViewData').hide();
        $('#oliDBNTitleAdd').show();
        $('#oliDBNTitleEdit').hide();
        // controll Button
        $('#obtBtnBack').show();
        $('#obtDBNPrintDoc').hide();
        $('#obtDBNCancelDoc').hide();
        $('#obtDBNApproveDoc').hide();
        $('#odvDBNBtnGrpSave').show();
        $('#obtDBNCallPageAdd').hide();
        $("#odvDBNApvBy").hide();
    } else if (ptType == "showpage_edit") { 
        // controller title
        $('#oliDBNTitleViewData').hide();
        $('#oliDBNTitleAdd').hide();
        $('#oliDBNTitleEdit').show();
        $("#odvDBNApvBy").hide();
        // controll Button
        $('#obtBtnBack').show();
        $('#obtDBNPrintDoc').show();
        $('#obtDBNCancelDoc').show();
        $('#obtDBNApproveDoc').show();
        $('#odvDBNBtnGrpSave').show();
        $('#obtDBNCallPageAdd').hide();
    }
}
// ------------------------------------------------ end controll layout button ---------------------------------------------------------

// ------------------------------------------------ call page func ----------------------------------------------------------------------
// Function: Call Page List
function JSvDBNCallPageList() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "docDBNPageList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxDBNNavDefult('page_list');
            $("#odvDBNPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSvDBNCallPageDataTable()
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เข้าหน้าแบบ แก้ไข
function JSvDBNCallPageEdit(ptAgnCode, ptBchCode, ptDocNo, ptCstCode) {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docDBNPageEdit",
            data: {
                'ptAgnCode' : ptAgnCode,
                'ptBchCode' : ptBchCode,
                'ptDocNo'   : ptDocNo,
                'ptCstCode' : ptCstCode
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JCNxCloseLoading();
                JSxDBNNavDefult('showpage_edit');
                $('#odvDBNPageDocument').html(tResult);
                //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                JSxDBNControlFormWhenCancelOrApprove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxDBNControlFormWhenCancelOrApprove() {
    var tStatusDoc  = $('#ohdDBNStaDoc').val();
    var tStatusApv  = $('#ohdDBNStaApv').val();
    // control ฟอร์ม
}