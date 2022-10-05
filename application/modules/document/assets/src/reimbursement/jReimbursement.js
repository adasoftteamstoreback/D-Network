$("document").ready(function() {
    JSxCldNavDefult('page_list');
    var tReturnCode = $('#ohdRtCode').val();
    var tDocNo = $('#ohdDocNo').val()
    JSvSALCallPageList();
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
                JSvSALCallPageList();
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    });
})

// ------------------------------------------------ controll layout button -----------------------------------------------------------------
function JSxCldNavDefult(ptType) { // controll layout button
    if (ptType == "page_list") { // controller title
        $('#oliSALTitleViewData').hide();
        $('#oliSALTitleAdd').hide();
        $('#oliSALTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').hide();
        $('#obtSALPrintDoc').hide();
        $('#obtSALCancelDoc').hide();
        $('#obtSALApproveDoc').hide();
        $('#odvSALBtnGrpSave').hide();
        $('#obtSALCallPageAdd').show();
    } else if (ptType == "page_add") { // controller title
        $('#oliSALTitleViewData').show();
        $('#oliSALTitleAdd').show();
        $('#oliSALTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtSALPrintDoc').hide();
        $('#obtSALCancelDoc').hide();
        $('#obtSALApproveDoc').hide();
        $('#odvSALBtnGrpSave').show();
        $('#obtSALCallPageAdd').hide();
        $("#odvSALApvBy").hide();
    } else if (ptType == "showpage_edit") { // controller title
        $('#oliSALTitleViewData').show();
        $('#oliSALTitleAdd').hide();
        $('#oliSALTitleEdit').show();
        $("#odvSALApvBy").hide();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtSALPrintDoc').show();
        $('#obtSALCancelDoc').show();
        $('#obtSALApproveDoc').show();
        $('#odvSALBtnGrpSave').show();
        $('#obtSALCallPageAdd').hide();
    }
}
// ------------------------------------------------ end controll layout button ---------------------------------------------------------

// ------------------------------------------------ call page func ----------------------------------------------------------------------
// Function: Call Page List
function JSvSALCallPageList() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "docTXOWithdrawPageList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_list');
            $("#odvSALPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSvSALCallPageDataTable()
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
        JSvSALCallPageAdd();
    }
}

//เข้ามาแบบเพิ่ม
function JSvSALCallPageAdd() {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docTXOWithdrawPageAdd",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvSALPageDocument").html(tResult);
            $("#odvSALApvBy").hide();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//เข้ามาแบบเพิ่ม
function JSvSALCallPageAdd2(aDataFinal) {
    JSxCheckPinMenuClose();
    $.ajax({
        type: "POST",
        url: "docTXOWithdrawPageAdd",
        data: {
            'pnType'        : 2, // เข้าแบบ Jump
            'paDataFinal'   : aDataFinal
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvSALPageDocument").html(tResult);
            $("#odvSALApvBy").hide();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//กดปุ่มย้อนกลับ ถ้ามาจาก "ตารางงาน ใบสั่งงานรายวัน" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
function JSxBackStageToDailyJob(){
    $.ajax({
        type    : "GET",
        url     : 'docDWO/0/0',
        cache   : false,
        timeout : 5000,
        success : function (tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);

            localStorage.tCheckBackStage = '';
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//กดปุ่มย้อนกลับ ถ้ามาจาก "ข้อมูลรถ" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
function JSxBackStageToCarDetail(){
    var tCarcode = $("#ohdSALCarCode").val();
    $.ajax({
        type    : "GET",
        url     : 'masCARView/0/0',
        cache   : false,
        timeout : 5000,
        success : function (tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);

            localStorage.tCheckBackStage = '';
            JSvCallPageCarEdit(tCarcode)
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เข้าหน้าแบบ แก้ไข
function JSvSALCallPageEdit(ptAgnCode, ptBchCode, ptDocNo, ptCstCode) {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docTXOWithdrawPageEdit",
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
                JSxCldNavDefult('showpage_edit');
                $('#odvSALPageDocument').html(tResult);
                $('#oliSALTitleViewData').show();

                //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                JSxSALControlFormWhenCancelOrApprove();
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
        JSvSALCallPageDataTable();
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

$('#oliSALTitle').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvSALCallPageList();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Click Button Add Page
$('#obtSALCallPageAdd').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvSALCallPageAdd();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Browse Modal เหตุผล
$('#obtSALBrowseRsn').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        window.oSALBrowseRsnOption  = undefined;
        oSALBrowseRsnOption         = oSALBrowseRsn({
            'tReturnInputCode'  : "oetSALRsnCode",
            'tReturnInputName'  : "oetSALRsnName",
        });
        JCNxBrowseData('oSALBrowseRsnOption');
    }else{
        JCNxShowMsgSessionExpired();
    }
});

// ------------------------------------------------ end call button onclick-----------------------------------------------------------
// ------------------------------------------------ Browse Func ----------------------------------------------------------------------

// Option Modal เหตุผล
// var oSALBrowseRsn       = function(poDataFnc){
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
function JSxSALApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                JCNxOpenLoading();
                $("#odvSALModalAppoveDoc").modal('hide');

                var tAgnCode = $('#oetSALAgnCode').val();
                var tBchCode = $('#ohdSALBchCode').val();
                var tDocNo = $('#oetSALDocNo').val();
                var tCstCode = $('#ohdSALCstCode').val();

                $.ajax({
                    type: "POST",
                    url: "docTXOWithdrawApproveDocument",
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
                            $("#odvSALModalAppoveDoc").modal("hide");
                            JCNxCloseLoading();
                            JSvSALCallPageEdit(tAgnCode,tBchCode,tDocNo,tCstCode);
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
                $("#odvSALModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxSALApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//end

// ยกเลิกเอกสาร
function JSnSALCancelDocument(pbIsConfirm) {
    var tAgnCode = $('#oetSALAgnCode').val();
    var tBchCode = $('#ohdSALBchCode').val();
    var tDocNo = $('#oetSALDocNo').val();
    var tCstCode = $('#ohdSALCstCode').val();

    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docTXOWithdrawCancelDocument",
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
                    $("#odvSALPopupCancel").modal("hide");
                    JCNxCloseLoading();
                    JSvSALCallPageEdit(tAgnCode,tBchCode,tDocNo,tCstCode);
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
        $('#odvSALPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvSALPopupCancel").modal("show");
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxSALControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdSALStaDoc').val();
    var tStatusApv = $('#ohdSALStaApv').val();
    $('#oliSALTitleViewData').show();
    // control ฟอร์ม
    if (tStatusDoc == 3 || tStatusDoc == 1 && tStatusApv == 1 || tStatusApv == "") {
        // เอกสารอนุมัติ และ เอกสารยกเลิก
        $('#oliSALTitleAdd').hide();
        $('#oliSALTitleEdit').hide();
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
        $('#ocbSALStaDocAct').attr('readonly', true);
        $('#otaSALRmk').attr('readonly', true);

        //check box AND Radio Button
        $("#ocbSALStaAutoGenCode" ).prop( "disabled", true );
        $("#ocbSALStaDocAct" ).prop( "disabled", true );
        $(".xWRadioRate" ).prop( "disabled", true );
        $(".xCNSALAns" ).prop( "disabled", true );
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtSALPrintDoc').show();
        $('#obtSALCancelDoc').hide();
        $('#obtSALApproveDoc').hide();
        $('#odvSALBtnGrpSave').show();
        $('#obtSALCallPageAdd').hide();
        $("#odvSALApvBy").hide();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtSALPrintDoc').show();
        $('#obtSALCancelDoc').hide();
        $('#obtSALApproveDoc').hide();
        $('#odvSALBtnGrpSave').show();
        $('#obtSALCallPageAdd').hide();
        $("#odvSALApvBy").show();

    }else{
        JSxCldNavDefult('showpage_edit');
    }
}
