$("document").ready(function() {
    JSxCldNavDefult('page_list');
    var tReturnCode = $('#ohdRtCode').val();
    var tDocNo = $('#ohdDocNo').val()
    JSvSatSvCallPageList();
    JSxCheckPinMenuClose();

    $('#obtBtnBack').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {

            //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
            if(localStorage.tCheckBackStage == 'Job2' || localStorage.tCheckBackStage == 'PageBookingCalendar'){
                JSxBackStageToPreviouspage();
            }else{ //กลับสู่หน้า List
                JSvSatSvCallPageList();
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    });


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
        $('#oliSatTitleViewData').hide();
        $('#oliSatTitleAdd').hide();
        $('#oliSatTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').hide();
        $('#obtSatSvPrintDoc').hide();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').hide();
        $('#obtSatSvCallPageAdd').show();
    } else if (ptType == "page_add") { // controller title
        $('#oliSatTitleViewData').hide();
        $('#oliSatTitleAdd').show();
        $('#oliSatTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtSatSvPrintDoc').hide();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();
    } else if (ptType == "showpage_edit") { // controller title
        $('#oliSatTitleViewData').hide();
        $('#oliSatTitleAdd').hide();
        $('#oliSatTitleEdit').show();

        // controll Button
        $('#obtBtnBack').show();
        $('#obtSatSvPrintDoc').show();
        $('#obtSatSvCancelDoc').show();
        $('#obtSatSvApproveDoc').show();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();
    }
}

// Function: Call Page List
function JSvSatSvCallPageList() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "docSatisfactionSurveyPageList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_list');
            $("#odvSatSvPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSvSatSvCallPageDataTable()
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
        JSvSatSvCallPageAdd();
    }
}

//เข้ามาแบบเพิ่ม
function JSvSatSvCallPageAdd() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docSatisfactionSurveyPageAdd",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvSatSvPageDocument").html(tResult);
            $("#odvSatApvBy").hide(); 
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//เข้ามาแบบเพิ่ม
function JSvSatSvCallPageAdd2(aDataFinal) {
    $.ajax({
        type: "POST",
        url: "docSatisfactionSurveyPageAdd",
        data: {
            'pnType'        : 2, // เข้าแบบ Jump
            'paDataFinal'   : aDataFinal
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvSatSvPageDocument").html(tResult);
            $("#odvSatApvBy").hide(); 
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เข้าหน้าแบบ แก้ไข
function JSvSatSvCallPageEdit(ptAgnCode, ptBchCode, ptDocNo) {
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docSatisfactionSurveyPageEdit",
            data: {
                'ptAgnCode' : ptAgnCode,
                'ptBchCode' : ptBchCode,
                'ptDocNo'   : ptDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '500') {
                    var ptPathPrj = window.location.pathname;
                    var tImageNotFound   = ".."+ptPathPrj+"application/modules/common/assets/images/DataNotFound.png";
                    var tImage = "<div style='background: linear-gradient(179deg, rgba(255,255,255,1) 0%, rgb(253 253 253) 52%, rgb(231 242 251) 100%); height: 100%; width: 100%; display: block; position: fixed;'>";
                        tImage += "<div>";
                        tImage += "<img src="+tImageNotFound+" style='width: 16%; margin: 12% auto; display: block;'>";
                        tImage += " </div>";
                        tImage += "<div style='margin: 0px auto; display: block;'><p style='display: block; position: absolute; top: 48%; text-align: center; width: 100%; font-size: 47px !important;'>ไม่พบเอกสารใบประเมินความพึงพอใจของลูกค้า</p></div>";
                        tImage += "</div>";
                    $('body').html(tImage);
                }else{

                    if($('#oetSATJumpDocNo').val() == '' || $('#oetSATJumpDocNo').val() == null){
                        
                    }else{  //มาจากการ WebView
                        $('.xCNavRow').hide();
                    }
                    
                    JCNxCloseLoading();
                    JSxCldNavDefult('showpage_edit');
                    $('#odvSatSvPageDocument').html(aReturnData['tViewDataTableList']);
                    $("#odvSatApvBy").hide(); 
                    
                    //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxSATControlFormWhenCancelOrApprove();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

$('#obtDOSerchAllDocument').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvSatSvCallPageDataTable();
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

$('#oliSatTitle').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvSatSvCallPageList();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Click Button Add Page
$('#obtSatSvCallPageAdd').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvSatSvCallPageAdd();
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

// ------------------------------------------------ end call button onclick-----------------------------------------------------------
// ------------------------------------------------ Browse Func ----------------------------------------------------------------------

// Option Modal เหตุผล
// var oSatSvBrowseRsn       = function(poDataFnc){
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

// อนุมัติเอกสาร
function JSxSatApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                JCNxOpenLoading();
                $("#odvSatModalAppoveDoc").modal('hide');

                var tAgnCode = $('#oetSatAgnCode').val();
                var tBchCode = $('#ohdSatBchCode').val();
                var tDocNo = $('#oetSatDocNo').val();
                JSxSatSubmitEventByButton('docSatisfactionSurveyEventEdit','approve');

                // $.ajax({
                //     type: "POST",
                //     url: "docSatisfactionSurveyApproveDocument",
                //     data: {
                //         'tAgnCode': tAgnCode,
                //         'tBchCode': tBchCode,
                //         'tDocNo': tDocNo
                //     },
                //     cache: false,
                //     timeout: 0,
                //     success: function(tResult) {
                //         var aReturnData = JSON.parse(tResult);
                //         if (aReturnData['nStaEvent'] == '1') {
                //             $("#odvSatModalAppoveDoc").modal("hide");
                //             JCNxCloseLoading();
                //             JSvSatSvCallPageEdit(tAgnCode,tBchCode,tDocNo);
                //         } else {
                //             var tMessageError = aReturnData['tStaMessg'];
                //             FSvCMNSetMsgErrorDialog(tMessageError);
                //             JCNxCloseLoading();
                //         }
                //     },
                //     error: function(jqXHR, textStatus, errorThrown) {
                //         JCNxResponseError(jqXHR, textStatus, errorThrown);
                //     }
                // });
            } else {
                $("#odvSatModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxSatApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//end

// ยกเลิกเอกสาร
function JSnSATCancelDocument(pbIsConfirm) {
    var tAgnCode = $('#oetSatAgnCode').val();
    var tBchCode = $('#ohdSatBchCode').val();
    var tDocNo = $('#oetSatDocNo').val();

    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docSatisfactionSurveyCancelDocument",
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
                    $("#odvSATPopupCancel").modal("hide");
                    JCNxCloseLoading();
                    JSvSatSvCallPageEdit(tAgnCode,tBchCode,tDocNo);
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
        $('#odvSATPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvSATPopupCancel").modal("show");
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxSATControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdSatStaDoc').val();
    var tStatusApv = $('#ohdSatStaApv').val();

    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) {
        // เอกสารอนุมัติ และ เอกสารยกเลิก
        $('#oliSatTitleViewData').show();
        $('#oliSatTitleAdd').hide();
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
        $('#obtSatSvPrintDoc').show();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtSatSvPrintDoc').show();
        $('#obtSatSvCancelDoc').hide();
        $('#obtSatSvApproveDoc').hide();
        $('#odvSatSvBtnGrpSave').show();
        $('#obtSatSvCallPageAdd').hide();

    }else{
        JSxCldNavDefult('showpage_edit');
    }
}

