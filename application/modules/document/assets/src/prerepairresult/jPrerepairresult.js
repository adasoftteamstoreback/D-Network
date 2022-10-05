$("document").ready(function() {
    JSxCldNavDefult('page_list');
    var tReturnCode = $('#ohdRtCode').val();
    var tDocNo = $('#ohdDocNo').val()
    var tDocNoCheck = $('#oetSATJumpDocNo').val()    
    if(tDocNoCheck != ''){//Come WebView
    }else{
        if($('#oetCheckJumpStatus').val() != '1'){
            JSvPreSvCallPageList();
        }
    }
    JSxCheckPinMenuClose();
    // รองรับการเข้ามาแบบ Noti
    var tAgnCode = $('#oetSATJumpAgnCode').val();
    var tBchCode = $('#oetSATJumpBchCode').val();
    var tDocNo = $('#oetSATJumpDocNo').val();
    if (tDocNo == '' || tDocNo == null) {
    } else {
        JSvPreSvCallPageEdit(tAgnCode, tBchCode, tDocNo);
    }
});

// ------------------------------------------------ controll layout button -----------------------------------------------------------------
function JSxCldNavDefult(ptType) { // controll layout button
    if (ptType == "page_list") { // controller title
        $('#oliPreTitleViewData').hide();
        $('#oliPreTitleAdd').hide();
        $('#oliPreTitleEdit').hide();

        // controll Button
        $('#obtBtnBack').hide();
        $('#obtPreSvPrintDoc').hide();
        $('#obtPreSvCancelDoc').hide();
        $('#obtPreSvApproveDoc').hide();
        $('#odvPreSvBtnGrpSave').hide();
        $('#obtPreSvCallPageAdd').show();
    } else if (ptType == "page_add") { // controller title
        $('#oliPreTitleViewData').hide();
        $('#oliPreTitleAdd').show();
        $('#oliPreTitleEdit').hide();

        //ถ้ามาจากการ JumpWeb
        if($('#oetSATJumpDocNo').val() == '' || $('#oetSATJumpDocNo').val() == null){ //ถ้าเข้ามาแบบปกติ
            $('#obtBtnBack').show();
        }else{ //ถ้ามาจากการ JumpWeb
            $('#obtBtnBack').hide();
        }

        $('#obtPreSvPrintDoc').hide();
        $('#obtPreSvCancelDoc').hide();
        $('#obtPreSvApproveDoc').hide();
        $('#odvPreSvBtnGrpSave').show();
        $('#obtPreSvCallPageAdd').hide();
    } else if (ptType == "showpage_edit") { // controller title
        $('#oliPreTitleViewData').hide();
        $('#oliPreTitleAdd').hide();
        $('#oliPreTitleEdit').show();

        //ถ้ามาจากการ JumpWeb
        if($('#oetSATJumpDocNo').val() == '' || $('#oetSATJumpDocNo').val() == null){ //ถ้าเข้ามาแบบปกติ
            $('#obtBtnBack').show();
        }else{ //ถ้ามาจากการ JumpWeb
            $('#obtBtnBack').hide();
        }

        $('#obtPreSvPrintDoc').show();
        $('#obtPreSvCancelDoc').show();
        $('#obtPreSvApproveDoc').show();
        $('#odvPreSvBtnGrpSave').show();
        $('#obtPreSvCallPageAdd').hide();
    }
}
// ------------------------------------------------ end controll layout button ---------------------------------------------------------

// ------------------------------------------------ call page func ----------------------------------------------------------------------
// Function: Call Page List
function JSvPreSvCallPageList() {
    localStorage.tCheckBackStage = '';
    JCNxOpenLoading();
    //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอตารางนัดหมาย" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
    if (localStorage.tCheckBackStage == 'PageBookingCalendar') {
        $.ajax({
            type: "GET",
            url: 'docBookingCalendar/0/0',
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);

                //เก็บเอาไว้ว่า มาจากหน้าจอตารางนัดหมาย
                localStorage.tCheckBackStage = '';

                JCNxCloseLoading();

                $('.oulTabBooking a[href="#odvBookingByCusTab"]').click();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else { //กลับสู่หน้า List
        $.ajax({
            type: "GET",
            url: "docPreRepairResultPageList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JSxCldNavDefult('page_list');
                $("#odvPreSvPageDocument").html(tResult);
                JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
                JSvPreSvCallPageDataTable()
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
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
        JSvPreSvCallPageAdd();
    }
}

//เข้ามาแบบเพิ่ม (แบบปกติไม่มีค่า)
function JSvPreSvCallPageAdd() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docPreRepairResultPageAdd",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvPreSvPageDocument").html(tResult);
            $("#odvPreApvBy").hide();
            JCNxCloseLoading();
            JSvPreCallPageDataTableAnwser('1');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//เข้ามาแบบเพิ่ม (แบบส่งค่ามาแล้ว)
function JSvPreSvCallPageAdd2(paDataDoc, paDataCar , poDataCustomer) {
    $.ajax({
        type    : "POST",
        url     : "docPreRepairResultPageAdd",
        data    : {
            'pnType'        : 1, // เข้าแบบ Jump
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvPreSvPageDocument").html(tResult);
            $("#odvPreApvBy").hide();

            //ช้อมูลลูกค้า
            if(poDataCustomer == '' || poDataCustomer == null){

            }else{
                var aResultcst      =  [poDataCustomer[0].tCstCode,'','',poDataCustomer[1].tTelephone,poDataCustomer[2].tEmail];
                var aItemCustomer   =  JSON.stringify(aResultcst);
                JSxWhenSeletedCustomer(aItemCustomer);
            }

            //ข้อมูลรถ
            var aDataCar =  JSON.stringify(paDataCar);
            JSxWhenSeletedCstCar(aDataCar, paDataDoc);

            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSvPreCallPageDataTableAnwser(ptType) {
    var tRefDoc = $("#oetPreDocRefCode").val();
    var tDocNo = $("#oetPreDocNo").val();
    var nCondition = $("#ohdPreChkStage").val();
    var nStadoc = $("#ohdPreStaDoc").val();
    // JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docPreRepairResultGetAnwser",
        data: {
            'tRefDoc': tRefDoc,
            'tDocNo': tDocNo,
            'nCondition': nCondition,
            'nStadoc': nStadoc,
            'Type' : ptType
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvPrePageDatatable").html(tResult);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เข้าหน้าแบบ แก้ไข
function JSvPreSvCallPageEdit(ptAgnCode, ptBchCode, ptDocNo) {
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docPreRepairResultPageEdit",
            data: {
                'ptAgnCode': ptAgnCode,
                'ptBchCode': ptBchCode,
                'ptDocNo': ptDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '500') {
                    //เอาค่าต่างๆ ที่ได้มาใส่ใน from ให้พร้อมบันทึกและอนุมัติ
                    JSxRenderValueInForm(ptBchCode,ptDocNo);
                } else {
                    if($('#oetSATJumpDocNo').val() == '' || $('#oetSATJumpDocNo').val() == null){
                    }else{  //มาจากการ WebView
                        // $('.xCNavRow').hide();
                    }
                    JCNxCloseLoading();
                    JSxCldNavDefult('showpage_edit');
                    $('#odvPreSvPageDocument').html(aReturnData['tViewDataTableList']);
                    $("#odvPreApvBy").hide();
                    //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxPreControlFormWhenCancelOrApprove();
                    JSvPreCallPageDataTableAnwser('2');
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
        JSvPreSvCallPageDataTable();
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

$('#oliPreTitle').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvPreSvCallPageList();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Click Button Add Page
$('#obtPreSvCallPageAdd').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvPreSvCallPageAdd();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Browse Modal เหตุผล
$('#obtPreSvBrowseRsn').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        window.oPreSvBrowseRsnOption = undefined;
        oPreSvBrowseRsnOption = oPreSvBrowseRsn({
            'tReturnInputCode': "oetPreSvRsnCode",
            'tReturnInputName': "oetPreSvRsnName",
        });
        JCNxBrowseData('oPreSvBrowseRsnOption');
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// อนุมัติเอกสาร
function JSxPreApproveDocument(pbIsConfirm) {
    try {
        if (pbIsConfirm) {
            JCNxOpenLoading();
            $("#odvPreModalAppoveDoc").modal('hide');
            var tAgnCode = $('#ohdPreSvOldAgnCode').val();
            var tBchCode = $('#ohdPreSvOldBchCode').val();
            var tDocNo = $('#oetPreDocNo').val();
            $.ajax({
                type: "POST",
                url: "docPreRepairResultApproveDocument",
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
                        $("#odvPreModalAppoveDoc").modal("hide");
                        JCNxCloseLoading();
                        JSvPreSvCallPageEdit(tAgnCode, tBchCode, tDocNo);
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
            $("#odvPreModalAppoveDoc").modal('show');
        }
    } catch (err) {
        console.log("JSxPreApproveDocument Error: ", err);
    }
}

//end

// ยกเลิกเอกสาร
function JSnPreCancelDocument(pbIsConfirm) {
    var tAgnCode = $('#ohdPreSvOldAgnCode').val();
    var tBchCode = $('#ohdPreSvOldBchCode').val();
    var tDocNo = $('#oetPreDocNo').val();
    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docPreRepairResultCancelDocument",
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
                    $("#odvPrePopupCancel").modal("hide");
                    JCNxCloseLoading();
                    JSvPreSvCallPageEdit(tAgnCode, tBchCode, tDocNo);
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
        $('#odvPrePopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvPrePopupCancel").modal("show");
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxPreControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdPreStaDoc').val();
    var tStatusApv = $('#ohdPreStaApv').val();
    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) {
        // เอกสารอนุมัติ และ เอกสารยกเลิก
        $('#oliPreTitleViewData').show();
        $('#oliPreTitleAdd').hide();
        $('#oliPreTitleEdit').hide();
        
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
        $('#ocbPreStaDocAct').attr('readonly', true);
        $('#otaPreRmk').attr('readonly', true);

        //check box AND Radio Button
        $("#ocbPreStaAutoGenCode").prop("disabled", true);
        $("#ocbPreStaDocAct").prop("disabled", true);
        $(".xWRadioRate").prop("disabled", true);
        $(".xCNPreAns").prop("disabled", true);
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtPreSvPrintDoc').show();
        $('#obtPreSvCancelDoc').hide();
        $('#obtPreSvApproveDoc').hide();
        $('#odvPreSvBtnGrpSave').show();
        $('#obtPreSvCallPageAdd').hide();

        //ปุ่มน้ำมัน
        $('.xCNClickMile').off();
    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // ควบคุมปุ่ม
        $('#obtBtnBack').show();
        $('#obtPreSvPrintDoc').show();
        $('#obtPreSvCancelDoc').hide();
        $('#obtPreSvApproveDoc').hide();
        $('#odvPreSvBtnGrpSave').show();
        $('#obtPreSvCallPageAdd').hide();

        //ปุ่มน้ำมัน
        $('.xCNClickMile').off();
    } else {
        JSxCldNavDefult('showpage_edit');
    }
}

$('#obtBtnBack').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
        if(localStorage.tCheckBackStage == 'PageJobOrder'){
            JSxBackStageToPreviouspage();
        }else{ //กลับสู่หน้า List
            JSvPreSvCallPageList();
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
});

function JSxBackStageToPreviouspage(){
    var tAgnNo = $('#ohdPreSvOldAgnCode').val();
    var tBchNo = $('#ohdPreSvOldBchCode').val();
    var tDocNo = $('#ohdPreDocRefCode').val();
    var tCstNo = $('#oetPreFrmCstCode').val();
    if (localStorage.tCheckBackStage == 'PageJobOrder') {
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
            if (localStorage.tCheckBackStage == 'PageJobOrder') {
                JSvJOBCallPageEdit(tAgnNo,tBchNo,tDocNo,tCstNo)
                localStorage.tCheckBackStage = '';
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//เอาค่าต่างๆ ที่ได้มาใส่ใน from ให้พร้อมบันทึกและอนุมัติ
function JSxRenderValueInForm(ptBchCode,ptDocNo){
    $.ajax ({
        type    : "POST",
        url     : "docIASRefJobOrder",
        data    : {
            'tBchCode'  : ptBchCode,
            'tDocNo'    : ptDocNo
        },
        cache   : false,
        timeout : 0,
        success : function(oResult) {
            var oResultValue = JSON.parse(oResult);
            if(oResultValue.nStatus == 1){ //เข้าหน้าเพิ่ม
                //ส่งค่ากลับไป
                aDataDoc = [
                    {
                        "ptDocNo"      : ptDocNo,
                        "pdDocRefDate" : oResultValue.aResultdata.FDXshDocDate,
                        "ptCarMile"    : oResultValue.aResultdata.FCXshCarMileage,
                        "ptBchCode"    : oResultValue.aResultdata.FTBchCode,
                        "ptBchName"    : oResultValue.aResultdata.FTBchName,
                        "ptAgnCode"    : '',
                        "ptAgnName"    : ''
                    }
                ];
                aDataCar = [oResultValue.aResultdata.FTCarCode, oResultValue.aResultdata.FTCarRegNo, ''];
                //เข้าแบบหน้าเพิ่มพร้อมข้อมูล
                JSvPreSvCallPageAdd2(aDataDoc, aDataCar , '');
                //ช่องสาขา
                setTimeout(function() {
                    $('#oetPreFrmBchName').val(oResultValue.aResultdata.FTBchName);
                    $('#oetPreFrmBchCode').val(oResultValue.aResultdata.FTBchCode);
                }, 2000);
            }else{ //ไม่พบข้อมูล
                var ptPathPrj = window.location.pathname;
                var tImageNotFound   = ".."+ptPathPrj+"application/modules/common/assets/images/DataNotFound.png";
                var tImage = "<div style='background: linear-gradient(179deg, rgba(255,255,255,1) 0%, rgb(253 253 253) 52%, rgb(231 242 251) 100%); height: 100%; width: 100%; display: block; position: fixed;'>";
                    tImage += "<div>";
                    tImage += "<img src="+tImageNotFound+" style='width: 16%; margin: 12% auto; display: block;'>";
                    tImage += " </div>";
                    tImage += "<div style='margin: 0px auto; display: block;'><p style='display: block; position: absolute; top: 48%; text-align: center; width: 100%; font-size: 47px !important;'>ไม่พบเอกสารใบบันทึกผลตรวจเช็คสภาพรถ</p></div>";
                    tImage += "</div>";
                $('body').html(tImage);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}