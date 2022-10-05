$("document").ready(function() {
    JSxCldNavDefult('page_list');
    var tReturnCode = $('#ohdRtCode').val();
    var tDocNo = $('#ohdDocNo').val()
    var tDocNoCheck = $('#oetCheckJumpStatus').val()  
    if(tDocNoCheck != ''){//Come WebView
    }else{
        if($('#oetCheckJumpStatus').val() != '1'){
            JSvJOBCallPageList();
        }
    }
    // JSvJOBCallPageList();
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
                JSvJOBCallPageList();
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    });
})

// ------------------------------------------------ controll layout button -----------------------------------------------------------------
function JSxCldNavDefult(ptType) { // controll layout button
    if (ptType == "page_list") { // controller title
        $('#oliJOBTitleViewData').hide();
        $('#oliJOBTitleAdd').hide();
        $('#oliJOBTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').hide();
        $('#obtJOBPrintDoc').hide();
        $('#obtJOBCancelDoc').hide();
        $('#obtJOBApproveDoc').hide();
        $('#odvJOBBtnGrpSave').hide();
        $('#obtJOBCallPageAdd').show();
        $('#obtJOBGoToIAS').hide();
        $('#obtJOBGoToCHK').hide();
    } else if (ptType == "page_add") { // controller title
        $('#oliJOBTitleViewData').hide();
        $('#oliJOBTitleAdd').show();
        $('#oliJOBTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtJOBPrintDoc').hide();
        $('#obtJOBCancelDoc').hide();
        $('#obtJOBApproveDoc').hide();
        $('#odvJOBBtnGrpSave').show();
        $('#obtJOBCallPageAdd').hide();
        $('#obtJOBGoToIAS').show();
        $('#obtJOBGoToCHK').show();
        $("#odvJOBApvBy").hide();
    } else if (ptType == "showpage_edit") { // controller title
        $('#oliJOBTitleViewData').hide();
        $('#oliJOBTitleAdd').hide();
        $('#oliJOBTitleEdit').show();
        $("#odvJOBApvBy").hide(); 

        // controll Button
        $('#obtBtnBack').show();
        $('#obtJOBPrintDoc').show();
        $('#obtJOBCancelDoc').show();
        $('#obtJOBApproveDoc').show();
        $('#odvJOBBtnGrpSave').show();
        $('#obtJOBCallPageAdd').hide();
        $('#obtJOBGoToIAS').show();
        $('#obtJOBGoToCHK').show();
    }
}

// Function: Call Page List
function JSvJOBCallPageList() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "docJOBPageList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_list');
            $("#odvJOBPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSvJOBCallPageDataTable()
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
        JSvJOBCallPageAdd();
    }
}

//เข้ามาแบบเพิ่ม
function JSvJOBCallPageAdd() {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docJOBPageAdd",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvJOBPageDocument").html(tResult);
            $("#odvJOBApvBy").hide(); 
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//เข้ามาแบบเพิ่ม
function JSvJOBCallPageAdd2(aDataFinal) {
    JSxCheckPinMenuClose();
    $.ajax({
        type: "POST",
        url: "docJOBPageAdd",
        data: {
            'pnType'        : 2, // เข้าแบบ Jump
            'paDataFinal'   : aDataFinal
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvJOBPageDocument").html(tResult);
            $("#odvJOBApvBy").hide(); 
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
    var tCarcode = $("#ohdJOBCarCode").val();
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
function JSvJOBCallPageEdit(ptAgnCode, ptBchCode, ptDocNo, ptCstCode) {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docJOBPageEdit",
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
                $('#odvJOBPageDocument').html(tResult);
                
                //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                JSxJOBControlFormWhenCancelOrApprove();
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
        JSvJOBCallPageDataTable();
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

$('#oliJOBTitle').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvJOBCallPageList();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Click Button Add Page
$('#obtJOBCallPageAdd').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvJOBCallPageAdd();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Browse Modal เหตุผล
$('#obtJOBBrowseRsn').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        window.oJOBBrowseRsnOption  = undefined;
        oJOBBrowseRsnOption         = oJOBBrowseRsn({
            'tReturnInputCode'  : "oetJOBRsnCode",
            'tReturnInputName'  : "oetJOBRsnName",
        });
        JCNxBrowseData('oJOBBrowseRsnOption');
    }else{
        JCNxShowMsgSessionExpired();
    }
});

// ------------------------------------------------ end call button onclick-----------------------------------------------------------
// ------------------------------------------------ Browse Func ----------------------------------------------------------------------

// Option Modal เหตุผล
// var oJOBBrowseRsn       = function(poDataFnc){
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
function JSxJOBApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                JCNxOpenLoading();
                $("#odvJOBModalAppoveDoc").modal('hide');

                var tAgnCode = $('#oetJOBAgnCode').val();
                var tBchCode = $('#ohdJOBBchCode').val();
                var tDocNo = $('#oetJOBDocNo').val();
                var tCstCode = $('#ohdJOBCstCode').val();

                $.ajax({
                    type: "POST",
                    url: "docJOBApproveDocument",
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
                            $("#odvJOBModalAppoveDoc").modal("hide");
                            JCNxCloseLoading();
                            JSvJOBCallPageEdit(tAgnCode,tBchCode,tDocNo,tCstCode);
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
                $("#odvJOBModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxJOBApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// ยกเลิกเอกสาร
function JSnJOBCancelDocument(pbIsConfirm) {
    var tAgnCode    = $('#oetJOBAgnCode').val();
    var tBchCode    = $('#ohdJOBBchCode').val();
    var tDocNo      = $('#oetJOBDocNo').val();
    var tCstCode    = $('#ohdJOBCstCode').val();

    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docJOBCancelDocument",
            data: {
                'tAgnCode'  : tAgnCode,
                'tBchCode'  : tBchCode,
                'tDocNo'    : tDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $("#odvJOBPopupCancel").modal("hide");

                    JSvJOBCallPageEdit(tAgnCode,tBchCode,tDocNo,tCstCode);
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        $('#odvJOBPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvJOBPopupCancel").modal("show");
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxJOBControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdJOBStaDoc').val();
    var tStatusApv = $('#ohdJOBStaApv').val();

    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1) || tStatusApv == "") {
        // เอกสารอนุมัติ และ เอกสารยกเลิก
        $('#oliJOBTitleViewData').show();
        $('#oliJOBTitleAdd').hide();
        $('#oliJOBTitleEdit').hide();
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
        $('#ocbJOBStaDocAct').attr('readonly', true);
        $('#otaJOBRmk').attr('readonly', true);

        //check box AND Radio Button
        $("#ocbJOBStaAutoGenCode" ).prop( "disabled", true );
        $("#ocbJOBStaDocAct" ).prop( "disabled", true );
        $(".xWRadioRate" ).prop( "disabled", true );
        $(".xCNJOBAns" ).prop( "disabled", true );
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtJOBPrintDoc').show();
        $('#obtJOBCancelDoc').hide();
        $('#obtJOBApproveDoc').hide();
        $('#odvJOBBtnGrpSave').show();
        $('#obtJOBCallPageAdd').hide();
        $("#odvJOBApvBy").hide();

        $('#obtJOBGoToIAS').hide();
        $('#obtJOBGoToCHK').hide();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtJOBPrintDoc').show();

        //ปุ่มยกเลิก
        if($('#ohdJOBUseInRef').val() == 1){ // ถูกอ้างอิงเเล้วยกเลิกไม่ได้
            $('#obtJOBCancelDoc').hide();
        }else{ //ยังไม่ถูกอ้างอิงยกเลิกได้
            $('#obtJOBCancelDoc').show();
        }

        $('#obtJOBApproveDoc').hide();
        $('#odvJOBBtnGrpSave').show();
        $('#obtJOBCallPageAdd').hide();
        $("#odvJOBApvBy").show();

    }else{
        JSxCldNavDefult('showpage_edit');
    }
}

