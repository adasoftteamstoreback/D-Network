$("document").ready(function() {
    JSxCldNavDefult('page_list');
    var tDocNo = $('#ohdDocNo').val()
    JSvBACUCallPageAdd();
    JSxCheckPinMenuClose();

    // รองรับการเข้ามาแบบ Noti
    var tAgnCode    = $('#oetSATJumpAgnCode').val();
    var tBchCode    = $('#oetSATJumpBchCode').val();
    var tDocNo      = $('#oetSATJumpDocNo').val();
    if(tDocNo == '' || tDocNo == null){
       
    }else{
        JSvSatSvCallPageEdit(tAgnCode,tBchCode,tDocNo);
    }

})

function JSxBackStageToPreviouspage(){
    var tDocNo   =  localStorage.tDocno;
    var tAgnCode =  localStorage.tAgnCode;
    var tBchCode =  localStorage.tBchCode;
    var tCstCode =  localStorage.tCstCode;

    if (localStorage.tCheckBackStage == 'PageBookingCalendar') {
        var tRoute = 'docBookingCalendar/0/0';
    }else if (localStorage.tCheckBackStage == 'Job2') {
        var tRoute = 'docJOB/0/0';
    }

    $.ajax({
        type    : "GET",
        url     : tRoute,
        cache   : false,
        timeout : 5000,
        success : function (tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);

            if (localStorage.tCheckBackStage == 'PageBookingCalendar') {

                $('.oulTabBooking a[href="#odvBookingByCusTab"]').click();

            }else if (localStorage.tCheckBackStage == 'Job2') {

                JSvJOBCallPageEdit(tAgnCode,tBchCode,tDocNo,tCstCode)
                localStorage.tDocno = '';
                localStorage.tBchCode = '';
                localStorage.tAgnCode = '';
                localStorage.tCstCode = '';

            }

            localStorage.tCheckBackStage = '';
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSxCldNavDefult(ptType) { // controll layout button
    if (ptType == "page_list") { // controller title
        $('#oliBACUTitleViewData').hide();
        $('#oliSatTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').hide();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').hide();
        $('#obtSatSvCallPageAdd').show();
    } else if (ptType == "page_add") { // controller title
        $('#oliBACUTitleViewData').hide();
        $('#oliSatTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();
    } else if (ptType == "showpage_edit") { // controller title
        $('#oliBACUTitleViewData').hide();
        $('#oliSatTitleEdit').show();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtSatSvCancelDoc').show();
        $('#obtSatSvApproveDoc').show();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();
    }

    //ล้างค่า
    localStorage.removeItem('BACU_LocalItemDataDelDtTemp');
}

function JSxGotoPreviousPage() {
    var tRoute = $('#ohdRoute').val();
    if (tRoute != 0) {
        $.ajax({
            type: "GET",
            url: tRoute,
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JSvBACUCallPageAdd();
    }
}

//เข้ามาหน้าการสำรองและการล้างข้อมูล Lst
function JSvBACUCallPageAdd() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docBackupCleanupAddpage",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvBACUPageDocument").html(tResult);
            $("#odvSatApvBy").hide(); 
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

$('#oliBACUTitle').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvBACUCallPageAdd();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Click Button Add Page
$('#obtSatSvCallPageAdd').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvBACUCallPageAdd();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Browse Modal เหตุผล
$('#obtSatSvBrowseRsn').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        window.oSatSvBrowseRsnOption  = undefined;
        oSatSvBrowseRsnOption         = oSatSvBrowseRsn({
            'tReturnInputCode'  : "oetSatSvRsnCode",
            'tReturnInputName'  : "oetSatSvRsnName",
        });
        JCNxBrowseData('oSatSvBrowseRsnOption');
    }else{
        JCNxShowMsgSessionExpired();
    }
});

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxSATControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdSatStaDoc').val();
    var tStatusApv = $('#ohdSatStaApv').val();

    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) {
        // เอกสารอนุมัติ และ เอกสารยกเลิก
        $('#oliBACUTitleViewData').show();
        $('#oliSatTitleEdit').hide();
        // ปุ่มเลือก
        $('.xCNBtnBrowseAddOn').addClass('disabled');
        $('.xCNBtnBrowseAddOn').attr('disabled', true);

        // ปุ่มเวลา
        $('.xCNBtnDateTime').addClass('disabled');
        $('.xCNBtnDateTime').attr('disabled', true);

        // เพิ่มข้อมูลสินค้า
        $('.xCNHideWhenCancelOrApprove').hide();

        //ควบคุม Form
        $('.xControlForm').attr('readonly', true);
        $('#ocbSatStaDocAct').attr('readonly', true);
        $('#otaSatRmk').attr('readonly', true);

        //check box AND Radio Button
        $("#ocbSatStaAutoGenCode" ).prop( "disabled", true );
        $("#ocbSatStaDocAct" ).prop( "disabled", true );
        $(".xWRadioRate" ).prop( "disabled", true );
        $(".xCNSatAns" ).prop( "disabled", true );
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();

    }else{
        JSxCldNavDefult('showpage_edit');
    }
}

