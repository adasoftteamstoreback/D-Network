$("document").ready(function() {
    JSxCldNavDefult('page_list');
    var tDocNo = $('#ohdDocNo').val()
    if($('#oetIASCheckJump').val() != '1'){
        JSvIASCallPageList();
    }
    JSxCheckPinMenuClose();

    // รองรับการเข้ามาแบบ Noti
    var tAgnCode    = $('#oetIASJumpAgnCode').val();
    var tBchCode    = $('#oetIASJumpBchCode').val();
    var tDocNo      = $('#oetIASJumpDocNo').val();
    if(tDocNo == '' || tDocNo == null){
       
    }else{
        JSvIASCallPageEdit(tAgnCode,tBchCode,tDocNo);
    }

    $('#obtIASBtnBack').unbind().click(function() {

        //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอใบสั่งงาน" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
        if(localStorage.tCheckBackStage == 'PageJobOrder'){
            JSxBackStageToJob2();
        }else{ //กลับสู่หน้า List
            JSvIASCallPageList();
        }
    });
})

function JSxBackStageToJob2(){
    var tAgnNo = $('#oetIASAgnCode').val();
    var tBchNo = $('#ohdIASBchCode').val();
    var tDocNo = $('#oetIASDocRefCode').val();
    var tCstNo = $('#ohdIASCstCode').val();

    $.ajax({
        type    : "GET",
        url     : 'docJOB/0/0',
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

// ------------------------------------------------ controll layout button -----------------------------------------------------------------
function JSxCldNavDefult(ptType) { // controll layout button
    if (ptType == "page_list") { // controller title
        $('#oliIASTitleViewData').hide();
        $('#oliIASTitleAdd').hide();
        $('#oliIASTitleEdit').hide();

        // controll Button
        $('#obtIASBtnBack').hide();
        $('#obtIASPrintDoc').hide();
        $('#obtIASCancelDoc').hide();
        $('#obtIASApproveDoc').hide();
        $('#odvIASBtnGrpSave').hide();
        $('#obtIASCallPageAdd').show();
    } else if (ptType == "page_add") { // controller title
        $('#oliIASTitleViewData').hide();
        $('#oliIASTitleAdd').show();
        $('#oliIASTitleEdit').hide();

        //ถ้ามาจากการ JumpWeb
        if($('#oetIASJumpDocNo').val() == '' || $('#oetIASJumpDocNo').val() == null){ //ถ้าเข้ามาแบบปกติ
            $('#obtIASBtnBack').show();
        }else{ //ถ้ามาจากการ JumpWeb
            $('#obtIASBtnBack').hide();
        }
        
        $('#obtIASPrintDoc').hide();
        $('#obtIASCancelDoc').hide();
        $('#obtIASApproveDoc').hide();
        $('#odvIASBtnGrpSave').show();
        $('#obtIASCallPageAdd').hide();
        $("#odvIASApvBy").hide();
    } else if (ptType == "showpage_edit") { // controller title
        $('#oliIASTitleViewData').hide();
        $('#oliIASTitleAdd').hide();
        $('#oliIASTitleEdit').show();
        $("#odvIASApvBy").hide(); 

        //ถ้ามาจากการ JumpWeb
        if($('#oetIASJumpDocNo').val() == '' || $('#oetIASJumpDocNo').val() == null){ //ถ้าเข้ามาแบบปกติ
            $('#obtIASBtnBack').show();
        }else{ //ถ้ามาจากการ JumpWeb
            $('#obtIASBtnBack').hide();
        }

        $('#obtIASPrintDoc').show();
        $('#obtIASCancelDoc').show();
        $('#obtIASApproveDoc').show();
        $('#odvIASBtnGrpSave').show();
        $('#obtIASCallPageAdd').hide();
    }
}

// Function: Call Page List
function JSvIASCallPageList() {
    JCNxOpenLoading();
    localStorage.tCheckBackStage = '';
    $.ajax({
        type: "GET",
        url: "docIASPageList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_list');
            $("#odvIASPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSvIASCallPageDataTable()
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
        JSvIASCallPageAdd();
    }
}

//เข้ามาแบบเพิ่ม (แบบปกติไม่มีค่า)
function JSvIASCallPageAdd() {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docIASPageAdd",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvIASPageDocument").html(tResult);
            $("#odvIASApvBy").hide(); 
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//เข้ามาแบบเพิ่ม (แบบส่งค่ามาแล้ว)
function JSvIASCallPageAdd2(paDataDoc, paDataCar) {
    JSxCheckPinMenuClose();
    $.ajax({
        type    : "POST",
        url     : "docIASPageAdd",
        data    : {
            'pnType'        : 1, // เข้าแบบ Jump
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxCldNavDefult('page_add');
            $("#odvIASPageDocument").html(tResult);
            $("#odvIASApvBy").hide(); 

            var aDataCar =  JSON.stringify(paDataCar);
            JSxWhenSeletedCstCarIns(aDataCar, paDataDoc);

            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เข้าหน้าแบบ แก้ไข
function JSvIASCallPageEdit(ptAgnCode, ptBchCode, ptDocNo) {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docIASPageEdit",
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
                    // var ptPathPrj = window.location.pathname;
                    // var tImageNotFound   = ".."+ptPathPrj+"application/modules/common/assets/images/DataNotFound.png";
                    // var tImage = "<div style='background: linear-gradient(179deg, rgba(255,255,255,1) 0%, rgb(253 253 253) 52%, rgb(231 242 251) 100%); height: 100%; width: 100%; display: block; position: fixed;'>";
                    //     tImage += "<div>";
                    //     tImage += "<img src="+tImageNotFound+" style='width: 16%; margin: 12% auto; display: block;'>";
                    //     tImage += " </div>";
                    //     tImage += "<div style='margin: 0px auto; display: block;'><p style='display: block; position: absolute; top: 48%; text-align: center; width: 100%; font-size: 47px !important;'>ไม่พบเอกสารใบตรวจสอบสภาพหลังซ่อม</p></div>";
                    //     tImage += "</div>";
                    // $('body').html(tImage);

                    //เอาค่าต่างๆ ที่ได้มาใส่ใน from ให้พร้อมบันทึกและอนุมัติ
                    JSxRenderValueInForm(ptBchCode,ptDocNo);
                    
                }else{

                    if($('#oetIASJumpDocNo').val() == '' || $('#oetIASJumpDocNo').val() == null){
                        
                    }else{  //มาจากการ WebView
                        //$('.xCNavRow').hide();
                    }

                    JCNxCloseLoading();
                    JSxCldNavDefult('showpage_edit');
                    $('#odvIASPageDocument').html(aReturnData['tViewDataTableList']);
                    
                    //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxIASControlFormWhenCancelOrApprove();
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

//เอาค่าต่างๆ ที่ได้มาใส่ใน from ให้พร้อมบันทึกและอนุมัติ
function JSxRenderValueInForm(ptBchCode,ptDocNo){

    $.ajax({
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
                JSvIASCallPageAdd2(aDataDoc, aDataCar);
            }else{ //ไม่พบข้อมูล
                var ptPathPrj = window.location.pathname;
                var tImageNotFound   = ".."+ptPathPrj+"application/modules/common/assets/images/DataNotFound.png";
                var tImage = "<div style='background: linear-gradient(179deg, rgba(255,255,255,1) 0%, rgb(253 253 253) 52%, rgb(231 242 251) 100%); height: 100%; width: 100%; display: block; position: fixed;'>";
                    tImage += "<div>";
                    tImage += "<img src="+tImageNotFound+" style='width: 16%; margin: 12% auto; display: block;'>";
                    tImage += " </div>";
                    tImage += "<div style='margin: 0px auto; display: block;'><p style='display: block; position: absolute; top: 48%; text-align: center; width: 100%; font-size: 47px !important;'>ไม่พบเอกสารใบตรวจสอบสภาพหลังซ่อม</p></div>";
                    tImage += "</div>";
                $('body').html(tImage);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

$('#obtDOSerchAllDocument').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvIASCallPageDataTable();
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

$('#oliIASTitle').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSvIASCallPageList();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Click Button Add Page
$('#obtIASCallPageAdd').unbind().click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvIASCallPageAdd();
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Event Browse Modal เหตุผล
$('#obtIASBrowseRsn').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        window.oIASBrowseRsnOption  = undefined;
        oIASBrowseRsnOption         = oIASBrowseRsn({
            'tReturnInputCode'  : "oetIASRsnCode",
            'tReturnInputName'  : "oetIASRsnName",
        });
        JCNxBrowseData('oIASBrowseRsnOption');
    }else{
        JCNxShowMsgSessionExpired();
    }
});

// อนุมัติเอกสาร
function JSxIASApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                // JCNxOpenLoading();
                $("#odvIASModalAppoveDoc").modal('hide');
                var tAgnCode = $('#oetIASAgnCode').val();
                var tBchCode = $('#ohdIASBchCode').val();
                var tDocNo = $('#oetIASDocNo').val();
                JSxIASSubmitEventByButton('docIASEventEdit','approve');

                // $.ajax({
                //     type: "POST",
                //     url: "docIASApproveDocument",
                //     data: {
                //         'tAgnCode'  : tAgnCode,
                //         'tBchCode'  : tBchCode,
                //         'tDocNo'    : tDocNo ,
                //         'tDocJOB2'  : $('#oetIASDocRefCode').val()
                //     },
                //     cache: false,
                //     timeout: 0,
                //     success: function(tResult) {
                //         var aReturnData = JSON.parse(tResult);
                //         if (aReturnData['nStaEvent'] == '1') {
                //             $("#odvIASModalAppoveDoc").modal("hide");
                //             JCNxCloseLoading();
                //             JSvIASCallPageEdit(tAgnCode,tBchCode,tDocNo);
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
                $("#odvIASModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxIASApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// ยกเลิกเอกสาร
function JSnIASCancelDocument(pbIsConfirm) {
    var tAgnCode = $('#oetIASAgnCode').val();
    var tBchCode = $('#ohdIASBchCode').val();
    var tDocNo = $('#oetIASDocNo').val();

    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docIASCancelDocument",
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
                    $("#odvIASPopupCancel").modal("hide");
                    JCNxCloseLoading();
                    JSvIASCallPageEdit(tAgnCode,tBchCode,tDocNo);
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
        $('#odvIASPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvIASPopupCancel").modal("show");
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxIASControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdIASStaDoc').val();
    var tStatusApv = $('#ohdIASStaApv').val();

    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) {
        // เอกสารอนุมัติ และ เอกสารยกเลิก
        $('#oliIASTitleViewData').show();
        $('#oliIASTitleAdd').hide();
        $('#oliIASTitleEdit').hide();
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
        $('#ocbIASStaDocAct').attr('readonly', true);
        $('#otaIASRmk').attr('readonly', true);

        //check box AND Radio Button
        $("#ocbIASStaAutoGenCode" ).prop( "disabled", true );
        $("#ocbIASStaDocAct" ).prop( "disabled", true );
        $(".xWRadioRate" ).prop( "disabled", true );
        $(".xCNIASAns" ).prop( "disabled", true );
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // ควบคุมปุ่ม
        $('#obtIASBtnBack').show();
        $('#obtIASPrintDoc').show();
        $('#obtIASCancelDoc').hide();
        $('#obtIASApproveDoc').hide();
        $('#odvIASBtnGrpSave').show();
        $('#obtIASCallPageAdd').hide();
        $("#odvIASApvBy").hide();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // ควบคุมปุ่ม
        $('#obtIASBtnBack').show();
        $('#obtIASPrintDoc').show();
        $('#obtIASCancelDoc').hide();
        $('#obtIASApproveDoc').hide();
        $('#odvIASBtnGrpSave').show();
        $('#obtIASCallPageAdd').hide();
        $("#odvIASApvBy").show();

    }else{
        JSxCldNavDefult('showpage_edit');
    }
}