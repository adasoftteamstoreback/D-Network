$("document").ready(function() {
    JSxPCKNavDefult('page_list');
    // var tReturnCode = $('#ohdRtCode').val();
    // var tDocNo = $('#ohdDocNo').val()
    JSvPCKCallPageList();
    JSxCheckPinMenuClose();

    $('#obtBtnBack').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {

            //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
            // if(localStorage.tCheckBackStage == 'PageDailyWorkOrder'){
            //     JSxBackStageToDailyJob();
            // }else if(localStorage.tCheckBackStage == 'PageCarDetail'){
            //     JSxBackStageToCarDetail();
            // }else{ //กลับสู่หน้า List
            JSvPCKCallPageList();
            // }

        } else {
            JCNxShowMsgSessionExpired();
        }
    });
})

// ------------------------------------------------ controll layout button -----------------------------------------------------------------
function JSxPCKNavDefult(ptType) { // controll layout button
    if (ptType == "page_list") { // controller title
        $('#oliPCKTitleViewData').hide();
        $('#oliPCKTitleAdd').hide();
        $('#oliPCKTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').hide();
        $('#obtPCKPrintDoc').hide();
        $('#obtPCKCancelDoc').hide();
        $('#obtPCKApproveDoc').hide();
        $('#odvPCKBtnGrpSave').hide();
        $('#obtPCKCallPageAdd').show();
    } else if (ptType == "page_add") { // controller title
        $('#oliPCKTitleViewData').hide();
        $('#oliPCKTitleAdd').show();
        $('#oliPCKTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtPCKPrintDoc').hide();
        $('#obtPCKCancelDoc').hide();
        $('#obtPCKApproveDoc').hide();
        $('#odvPCKBtnGrpSave').show();
        $('#obtPCKCallPageAdd').hide();
        $("#odvPCKApvBy").hide();
    } else if (ptType == "showpage_edit") { // controller title
        $('#oliPCKTitleViewData').hide();
        $('#oliPCKTitleAdd').hide();
        $('#oliPCKTitleEdit').show();
        $("#odvPCKApvBy").hide();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtPCKPrintDoc').show();
        $('#obtPCKCancelDoc').show();
        $('#obtPCKApproveDoc').show();
        $('#odvPCKBtnGrpSave').show();
        $('#obtPCKCallPageAdd').hide();
    }
}
// ------------------------------------------------ end controll layout button ---------------------------------------------------------

// ------------------------------------------------ call page func ----------------------------------------------------------------------
// Function: Call Page List
function JSvPCKCallPageList() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "docPCKPageList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxPCKNavDefult('page_list');
            $("#odvPCKPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSvPCKCallPageDataTable()
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
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
        JSvPCKCallPageAdd();
    }
}

//เข้ามาแบบเพิ่ม
function JSvPCKCallPageAdd() {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docPCKPageAdd",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxPCKNavDefult('page_add');
            $("#odvPCKPageDocument").html(tResult);
            $("#odvPCKApvBy").hide();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//เข้ามาแบบเพิ่ม
function JSvPCKCallPageAdd2(aDataFinal) {
    JSxCheckPinMenuClose();
    $.ajax({
        type: "POST",
        url: "docPCKPageAdd",
        data: {
            'pnType': 2, // เข้าแบบ Jump
            'paDataFinal': aDataFinal
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxPCKNavDefult('page_add');
            $("#odvPCKPageDocument").html(tResult);
            $("#odvPCKApvBy").hide();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//กดปุ่มย้อนกลับ ถ้ามาจาก "ตารางงาน ใบสั่งงานรายวัน" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
function JSxBackStageToDailyJob() {
    $.ajax({
        type: "GET",
        url: 'docDWO/0/0',
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);

            localStorage.tCheckBackStage = '';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//กดปุ่มย้อนกลับ ถ้ามาจาก "ข้อมูลรถ" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
function JSxBackStageToCarDetail() {
    var tCarcode = $("#ohdPCKCarCode").val();
    $.ajax({
        type: "GET",
        url: 'masCARView/0/0',
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);

            localStorage.tCheckBackStage = '';
            JSvCallPageCarEdit(tCarcode)
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เข้าหน้าแบบ แก้ไข
function JSvPCKCallPageEdit(ptAgnCode, ptBchCode, ptDocNo, ptCstCode) {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docPCKPageEdit",
            data: {
                'ptAgnCode': ptAgnCode,
                'ptBchCode': ptBchCode,
                'ptDocNo': ptDocNo,
                'ptCstCode': ptCstCode
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JCNxCloseLoading();
                JSxPCKNavDefult('showpage_edit');
                $('#odvPCKPageDocument').html(tResult);

                //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                JSxPCKControlFormWhenCancelOrApprove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}
// ------------------------------------------------ end call page func-------------------------------------------------------------------
// ------------------------------------------------ button onclick ----------------------------------------------------------------------

$('#obtDOSerchAllDocument').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvPCKCallPageDataTable();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Advance search Display control
$('#obtDOAdvanceSearch').unbind().click(function() {
    if ($('#odvDOAdvanceSearchContainer').hasClass('hidden')) {
        $('#odvDOAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
    } else {
        $("#odvDOAdvanceSearchContainer").slideUp(500, function() {
            $(this).addClass('hidden');
        });
    }
});

$('#oliPCKTitle').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvPCKCallPageList();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Click Button Add Page
$('#obtPCKCallPageAdd').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvPCKCallPageAdd();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Browse Modal เหตุผล
$('#obtPCKBrowseRsn').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        window.oPCKBrowseRsnOption = undefined;
        oPCKBrowseRsnOption = oPCKBrowseRsn({
            'tReturnInputCode': "oetPCKRsnCode",
            'tReturnInputName': "oetPCKRsnName",
        });
        JCNxBrowseData('oPCKBrowseRsnOption');
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// ------------------------------------------------ end call button onclick-----------------------------------------------------------
// ------------------------------------------------ Browse Func ----------------------------------------------------------------------

// Option Modal เหตุผล
// var oPCKBrowseRsn       = function(poDataFnc){
//     var tInputReturnCode    = poDataFnc.tReturnInputCode;
//     var tInputReturnName    = poDataFnc.tReturnInputName;
//     var oOptionReturn       = {
//         Title: ["other/reason/reason","tRSNTitle"],
//         Table: {Master:"TCNMRsn",PK:"FTRsnCode"},
//         Join: {
//             Table: ["TCNMRsn_L"],
//             On: ["TCNMRsn.FTRsnCode = TCNMRsn_L.FTRsnCode AND TCNMRsn_L.FNLngID = '"+nLangEdits+"'"]
//         },
//         Where: {
//             Condition : ["  AND TCNMRsn.FTRsgCode = '008' "]
//         },
//         GrideView: {
//             ColumnPathLang: 'other/reason/reason',
//             ColumnKeyLang: ['tRSNTBCode','tRSNTBName'],
//             ColumnsSize: ['15%','75%'],
//             WidthModal: 50,
//             DataColumns: ['TCNMRsn.FTRsnCode','TCNMRsn_L.FTRsnName'],
//             DataColumnsFormat: ['',''],
//             Perpage: 10,
//             OrderBy: ['TCNMRsn.FDCreateOn DESC'],
//         },
//         CallBack: {
//             ReturnType: 'S',
//             Value: [tInputReturnCode,"TCNMRsn.FTRsnCode"],
//             Text: [tInputReturnName,"TCNMRsn_L.FTRsnName"],
//         },
//         RouteAddNew : 'reason',
//         BrowseLev : nStaASTBrowseType,
//     };
//     return oOptionReturn;
// }

// ------------------------------------------------ End Browse Func -------------------------------------------------------------------

// อนุมัติเอกสาร
function JSxPCKApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                JCNxOpenLoading();
                $("#odvPCKModalAppoveDoc").modal('hide');

                var tAgnCode = $('#oetPCKAgnCode').val();
                var tBchCode = $('#oetPCKBchCode').val();
                var tDocNo = $('#oetPCKDocNo').val();
                var tCstCode = $('#ohdPCKCstCode').val();

                $.ajax({
                    type: "POST",
                    url: "docPCKApproveDocument",
                    data: {
                        'tAgnCode': tAgnCode,
                        'tBchCode': tBchCode,
                        'tDocNo': tDocNo
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        var aReturnData = JSON.parse(tResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $("#odvPCKModalAppoveDoc").modal("hide");
                            JCNxCloseLoading();
                            JSvPCKCallPageEdit(tAgnCode, tBchCode, tDocNo, tCstCode);
                        } else {
                            var tMessageError = aReturnData['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                            JCNxCloseLoading();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                $("#odvPCKModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxPCKApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//end

// ยกเลิกเอกสาร
function JSnPCKCancelDocument(pbIsConfirm) {
    var tAgnCode = $('#oetPCKAgnCode').val();
    var tBchCode = $('#oetPCKBchCode').val();
    var tDocNo = $('#oetPCKDocNo').val();
    var tCstCode = $('#ohdPCKCstCode').val();

    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docPCKCancelDocument",
            data: {
                'tAgnCode': tAgnCode,
                'tBchCode': tBchCode,
                'tDocNo': tDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $("#odvPCKPopupCancel").modal("hide");
                    JCNxCloseLoading();
                    JSvPCKCallPageEdit(tAgnCode, tBchCode, tDocNo, tCstCode);
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        $('#odvPCKPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvPCKPopupCancel").modal("show");
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxPCKControlFormWhenCancelOrApprove() {
    let tStatusDoc          = $('#ohdPCKStaDoc').val();
    let tStatusApv          = $('#ohdPCKStaApv').val();
    let tRefInType          = $('#ohdPCKRefInType').val();
    let tAutStaCancel       = $('#ohdPCKAutStaCancel').val();
    let tDocDateCreate      = $('#ohdPCKDocDateCreate').val();
    let tDocDateNowToday    = $('#ohdPCKDateNowToday').val();
    
    // อนุมัติเเล้วแต่ไม่ได้อ้างอิง จะยกเลิกได้
    if (tStatusDoc == 1 && tStatusApv == 1 && tRefInType != 2){
        // เอกสารอนุมัติแล้วแต่ไม่ได้ถูกอ้างอิง
        $('#obtPCKCancelDoc').show();
        $('#obtPCKPrintDoc').show();
        $('#odvPCKBtnGrpSave').show();
        $('#obtPCKApproveDoc').hide();
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
        $('#ocbPCKStaDocAct').attr('readonly', true);
        $('#otaPCKRmk').attr('readonly', true);
        //check box AND Radio Button
        $("#ocbPCKStaAutoGenCode").prop("disabled", true);
        $("#ocbPCKStaDocAct").prop("disabled", true);
        $(".xWRadioRate").prop("disabled", true);
        $(".xCNPCKAns").prop("disabled", true);

        // เช็คเดือนที่สร้างไม่ เท่ากับ เดือนปัจจุบัน วิ่งไปเช็คสิทธิ์ต่อ
        if(tDocDateCreate != tDocDateNowToday){
            if(tAutStaCancel == '1'){
                // มีสิทธิ์ Cancel เอกสารได้
                $('#obtPCKCancelDoc').show();
            }else{
                $('#obtPCKCancelDoc').hide();
            }
        }else{
            $('#obtPCKCancelDoc').hide();
        }
    }

    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1) || tStatusApv == "") {
        // เอกสารอนุมัติ และ เอกสารยกเลิก
        $('#oliPCKTitleViewData').show();
        $('#oliPCKTitleAdd').hide();
        $('#oliPCKTitleEdit').hide();
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
        $('#ocbPCKStaDocAct').attr('readonly', true);
        $('#otaPCKRmk').attr('readonly', true);

        //check box AND Radio Button
        $("#ocbPCKStaAutoGenCode").prop("disabled", true);
        $("#ocbPCKStaDocAct").prop("disabled", true);
        $(".xWRadioRate").prop("disabled", true);
        $(".xCNPCKAns").prop("disabled", true);
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtPCKPrintDoc').show();
        $('#obtPCKCancelDoc').hide();
        $('#obtPCKApproveDoc').hide();
        $('#odvPCKBtnGrpSave').show();
        $('#obtPCKCallPageAdd').hide();
        $("#odvPCKApvBy").hide();
    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtPCKPrintDoc').show();
        $('#obtPCKCancelDoc').hide();
        $('#obtPCKApproveDoc').hide();
        $('#odvPCKBtnGrpSave').show();
        $('#obtPCKCallPageAdd').hide();
        $("#odvPCKApvBy").show();
    } else {
        JSxPCKNavDefult('showpage_edit');
    }
}