var nCRVStaCRVBrowseType = $("#oetCRVStaBrowse").val();
var tCRVCallCRVBackOption = $("#oetCRVCallBackOption").val();
var tCRVSesSessionID = $("#ohdSesSessionID").val();
var tCRVSesSessionName = $("#ohdSesSessionName").val();
var tJumpDocNo = $("#oetCRVJumpDocNo").val();
var tJumpBchNo = $("#oetCRVJumpBchCode").val();

$("document").ready(function() {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    if (typeof(nCRVStaCRVBrowseType) != 'undefined' && (nCRVStaCRVBrowseType == 0 || nCRVStaCRVBrowseType ==2)) { // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliCRVTitle').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvCRVCallPageList();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtCRVCallBackPage').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                if($('#oetCheckJumpStatus').val() != '1'){
                    JSvCRVCallPageList();
                }else{
                    $.ajax({
                        type    : "GET",
                        url     : "dcmSOData/0/0",
                        cache   : false,
                        timeout : 5000,
                        success : function (tResult) {
                            $(window).scrollTop(0);
                            $('.odvMainContent').html(tResult);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Add Page
        $('#obtCRVCallPageAdd').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvCRVCallPageAddDoc();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Cancel Document
        $('#obtCRVCancelDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSnCRVCancelDocument(false);
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Appove Document
        // $('#obtCRVApproveDoc').unbind().click(function() {
        //     var nStaSession = JCNxFuncChkSessionExpired();
        //     if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        //         var tFrmSplName = $('#oetCRVFrmSplName').val();
        //         var tCRVFrmWahName = $('#oetCRVFrmWahName').val();
        //         var tCheckIteminTable = $('#otbCRVDocPdtAdvTableList .xWPdtItem').length;
        //         var nPOStaValidate = $('.xPOStaValidate0').length;
        //         if (tCheckIteminTable > 0) {
        //             if (nPOStaValidate == 0) {
        //                 //เช็คค่าว่างตัวแทนขาย
        //                 if (tFrmSplName == '') {
        //                     $('#odvCRVModalPleseselectSPL').modal('show');
        //                     //เช็คค่าว่างคลังสินค้า
        //                 } else if (tCRVFrmWahName == '') {
        //                     $('#odvCRVModalWahNoFound').modal('show');
        //                 } else {
        //                     JSxCRVSetStatusClickSubmit(2);
        //                     JSxCRVSubmitEventByButton('approve');
        //                 }

        //             } else {
        //                 $('#odvCRVModalImpackImportExcel').modal('show')
        //             }
        //         } else {
        //             FSvCMNSetMsgWarningDialog($('#ohdCRVValidatePdt').val());
        //         }
        //     } else {
        //         JCNxShowMsgSessionExpired();
        //     }
        // });

        // ปุ่ม สร้างใบสั่งขาย
        $('#obtCRVGenSO').unbind().click(function() {
            $.ajax({
                type    : "POST",
                url     : "docCRVEventGenSO",
                data    : {
                    tDocNo      : $('#oetCRVDocNo').val(),
                    tBchCode    : $('#ohdCRVBchCode').val()
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    console.log(tResult);
                    JSvCRVCallPageList();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });

        // กดปุ่มบันทึก
        $('#obtCRVSubmitFromDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tFrmSplName = $('#oetCRVFrmSplName').val();
                var tCRVFrmWahName = $('#oetCRVFrmWahName').val();
                var tCheckIteminTable = $('#otbCRVDocPdtAdvTableList .xWPdtItem').length;
                var nPOStaValidate = $('.xPOStaValidate0').length;
                if (tCheckIteminTable > 0) {
                    if (nPOStaValidate == 0) {
                        //เช็คค่าว่างตัวแทนขาย
                        if (tFrmSplName == '') {
                            $('#odvCRVModalPleseselectSPL').modal('show');
                            //เช็คค่าว่างคลังสินค้า
                        } else if (tCRVFrmWahName == '') {
                            $('#odvCRVModalWahNoFound').modal('show');
                        } else {
                            JSxCRVSetStatusClickSubmit(1);
                            $('#obtCRVSubmitDocument').click();
                        }

                    } else {
                        $('#odvCRVModalImpackImportExcel').modal('show')
                    }
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdCRVValidatePdt').val());
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        JSxCRVNavDefult('showpage_list');

        switch(nCRVStaCRVBrowseType){
            case '2':
                var tAgnCode = $('#oetCRVJumpAgnCode').val();
                var tBchCode = $('#oetCRVJumpBchCode').val();
                var tDocNo = $('#oetCRVJumpDocNo').val();
                JSvCRVCallPageEdit(tDocNo);
            break;
            default:
                if($('#oetCheckJumpStatus').val() != '1'){
                    JSvCRVCallPageList();
                }else{
                    JSvCRVCallPageEdit(tJumpBchNo,tJumpDocNo)
                }
        }
    } else {
        JSxCRVNavDefult('showpage_list');
        JSvCRVCallPageAddDoc();
    }
});

// อนุมัติเอกสาร
function JSxCRVApproveDocument(pbIsConfirm) {
    try {
        if (pbIsConfirm) {
            $("#odvCRVModalAppoveDoc").modal('hide');
            var tAgnCode    = $('#oetCRVAgnCode').val();
            var tDocNo      = $('#oetCRVDocNo').val();
            var tBchCode    = $('#ohdCRVBchCode').val();
            var tRefInDocNo = $('#oetCRVRefDocIntName').val();
            $.ajax({
                type: "POST",
                url: "docCRVApproveDocument",
                data: {
                    tDocNo: tDocNo,
                    tBchCode: tBchCode,
                    tAgnCode: tAgnCode,
                    tRefInDocNo: tRefInDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvCRVModalAppoveDoc").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSoCRVCallSubscribeMQ();
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
            $("#odvCRVModalAppoveDoc").modal('show');
        }
    } catch (err) {
        console.log("JSxCRVApproveDocument Error: ", err);
    }
}


// Control เมนู
function JSxCRVNavDefult(ptType) {
    if (ptType == 'showpage_list') { // แสดง
        $("#oliCRVTitle").show();
        $("#odvCRVBtnGrpInfo").show();
        $("#obtCRVCallPageAdd").show();

        // ซ่อน
        $("#oliCRVTitleAdd").hide();
        $("#oliCRVTitleEdit").hide();
        $("#oliCRVTitleDetail").hide();
        $("#oliCRVTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtCRVCallBackPage").hide();
        $("#obtCRVPrintDoc").hide();
        $("#obtCRVCancelDoc").hide();
        $("#obtCRVApproveDoc").hide();
        $("#obtCRVGenSO").hide();
        $("#odvCRVBtnGrpSave").hide();

    } else if (ptType == 'showpage_add') { // แสดง
        $("#oliCRVTitle").show();
        $("#odvCRVBtnGrpSave").show();
        $("#obtCRVCallBackPage").show();
        $("#oliCRVTitleAdd").show();

        // ซ่อน
        $("#oliCRVTitleEdit").hide();
        $("#oliCRVTitleDetail").hide();
        $("#oliCRVTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtCRVPrintDoc").hide();
        $("#obtCRVCancelDoc").hide();
        $("#obtCRVApproveDoc").hide();
        $("#obtCRVGenSO").hide();
        $("#odvCRVBtnGrpInfo").hide();
    } else if (ptType == 'showpage_edit') { // แสดง
        $("#oliCRVTitle").show();
        $("#odvCRVBtnGrpSave").show();
        $("#obtCRVApproveDoc").show();
        $("#obtCRVGenSO").hide();
        $("#obtCRVCancelDoc").show();
        $("#obtCRVCallBackPage").show();
        $("#oliCRVTitleEdit").show();
        $("#obtCRVPrintDoc").show();

        // ซ่อน
        $("#oliCRVTitleAdd").hide();
        $("#oliCRVTitleDetail").hide();
        $("#oliCRVTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#odvCRVBtnGrpInfo").hide();
    }

    // ล้างค่า
    localStorage.removeItem('IV_LocalItemDataDelDtTemp');
    localStorage.removeItem('LocalItemData');
}

// Function: Call Page List
function JSvCRVCallPageList() {
    $.ajax({
        type: "GET",
        url: "dcmCRVFormSearchList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvCRVContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSxCRVNavDefult('showpage_list');
            JSvCRVCallPageDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function: Call Page DataTable
function JSvCRVCallPageDataTable(pnPage) {
    JCNxOpenLoading();
    var oAdvanceSearch = JSoCRVGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docCRVDataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostCRVDataTableDocument').html(aReturnData['tCRVViewDataTableList']);
            } else {
                var tMessageError = aReturnData['tStaMessg'];
                FSvCMNSetMsgErrorDialog(tMessageError);
            }
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// รวม Values ต่างๆของการค้นหาขั้นสูง
function JSoCRVGetAdvanceSearchData() {
    var oAdvanceSearchData = {
        tSearchAll: $("#oetCRVSearchAllDocument").val(),
        tSearchBchCodeFrom: $("#oetCRVAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo: $("#oetCRVAdvSearchBchCodeTo").val(),
        tSearchShpCode: $("#oetCRVAdvSearchShpCode").val(),
        tSearchPosCode: $("#oetCRVAdvSearchPosCode").val(),
        tSearchPshCode: $("#ocmCRVAdvSearchPshCode").val(),
        tSearchDocDateFrom: $("#oetCRVAdvSearcDocDateFrom").val(),
        tSearchDocDateTo: $("#oetCRVAdvSearcDocDateTo").val(),
        tSearchStaDoc: $("#ocmCRVAdvSearchStaDoc").val(),
        tSearchStaDocAct: $("#ocmStaDocAct").val(),
        tSearcDocIn: $("#ohdCRVInCendition").val(),
    };
    return oAdvanceSearchData;
}

// เข้ามาแบบ insert
function JSvCRVCallPageAddDoc() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docCRVPageAdd",
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxCRVNavDefult('showpage_add');
                $('#odvCRVContentPageDocument').html(aReturnData['tCRVViewPageAdd']);
                $("#ocmCRVTypePayment").val("1").selectpicker('refresh');
                $('.xCNPanel_CreditTerm').hide();
                JSvCRVLoadPdtDataTableHtml();
                JCNxLayoutControll();
            } else {
                var tMessageError = aReturnData['tStaMessg'];
                FSvCMNSetMsgErrorDialog(tMessageError);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เข้าหน้าแบบ แก้ไข
function JSvCRVCallPageEdit(ptBchCode,ptDocNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docCRVPageEdit",
            data: {
                'ptCRVBchCode'  : ptBchCode,
                'ptCRVDocNo'    : ptDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult)
                if (aReturnData['nStaEvent'] == '1') {
                    JSxCRVNavDefult('showpage_edit');
                    $('#odvCRVContentPageDocument').html(aReturnData['tViewPageEdit']);
                    // JCNxCloseLoading();
                    // //ประเภทชำระเงิน
                    // var nCRVPayType = $('#ohdCRVRteFac').val();
                    // if (nCRVPayType == 1 || pnCRVPayType == 1) {
                    //     $("#ocmCRVTypePayment").val("1").selectpicker('refresh');
                    //     $('.xCNPanel_CreditTerm').hide();
                    // } else {
                    //     $("#ocmCRVTypePayment").val("2").selectpicker('refresh');
                    //     $('.xCNPanel_CreditTerm').show();
                    // }

                    // //ประเภทภาษี
                    // var nCRVVatInOrEx = $('#ohdCRVVatInOrEx').val();
                    // if (nCRVVatInOrEx == 1 || pnCRVVatInOrEx == 1) {
                    //     $("#ocmCRVFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');
                    // } else {
                    //     $("#ocmCRVFrmSplInfoVatInOrEx").val("2").selectpicker('refresh');
                    // }

                    // //สถานะอ้างอิง
                    // var nCRVStaRef = $('#ohdCRVStaRef').val();
                    // if (nCRVStaRef == 0 || pnCRVStaRef == 0) {
                    //     $("#ocmCRVFrmInfoOthRef").val("0").selectpicker('refresh');
                    // } else if (nCRVStaRef == 1 || pnCRVStaRef == 1) {
                    //     $("#ocmCRVFrmInfoOthRef").val("1").selectpicker('refresh');
                    // } else {
                    //     $("#ocmCRVFrmInfoOthRef").val("2").selectpicker('refresh');
                    // }
                    JSvCRVLoadPdtDataTableHtml();

                    // เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    // JSxCRVControlFormWhenCancelOrApprove();
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
        JCNxShowMsgSessionExpired();
    }
}

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxCRVControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdCRVStaDoc').val();
    var tStatusApv = $('#ohdCRVStaApv').val();

    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) {
        // เอกสารยกเลิก
        // ปุ่มเลือก
        $('.xCNBtnBrowseAddOn').addClass('disabled');
        $('.xCNBtnBrowseAddOn').attr('disabled', true);

        // ปุ่มเวลา
        $('.xCNBtnDateTime').addClass('disabled');
        $('.xCNBtnDateTime').attr('disabled', true);

        // เพิ่มข้อมูลสินค้า
        $('.xCNHideWhenCancelOrApprove').hide();
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // เอกสารยกเลิก
        // ปุ่มยกเลิก
        $('#obtCRVCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtCRVApproveDoc').hide();

        // ปุ่มสร้างใบสั่งขาย
        $("#obtCRVGenSO").hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxCRVControlObjAndBtn();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // เอกสารอนุมัติแล้ว
        // ปุ่มยกเลิก
        $('#obtCRVCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtCRVApproveDoc').hide();

        // ปุ่มสร้างใบสั่งขาย
        $("#obtCRVGenSO").hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxCRVControlObjAndBtn();
    }
}

// Function : Call Page Product Table In Add Document
function JSvCRVLoadPdtDataTableHtml(pnPage) {
    // if ($("#ohdCRVRoute").val() == "docCRVEventAdd") {
    //     var tCRVDocNo = "";
    // } else {
        var tCRVDocNo = $("#oetCRVDocNo").val();
    // }
    // var tCRVStaApv = $("#ohdCRVStaApv").val();
    // var tCRVStaDoc = $("#ohdCRVStaDoc").val();
    // var tCRVVATInOrEx = $("#ocmCRVFrmSplInfoVatInOrEx").val();

    // เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
    // if ($("#otbCRVDocPdtAdvTableList .xWPdtItem").length == 0) {
    //     if (pnPage != undefined) {
    //         pnPage = pnPage - 1;
    //     }
    // }

    // if (pnPage == '' || pnPage == null) {
    //     var pnNewPage = 1;
    // } else {
    //     var pnNewPage = pnPage;
    // }
    // var nPageCurrent = pnNewPage;
    var tSearchPdtAdvTable = $('#oetSearchPdtHTML').val();

    // if (tCRVStaApv == 2) {
    //     $('#obtCRVDocBrowsePdt').hide();
    //     $('#obtCRVPrintDoc').hide();
    //     $('#obtCRVCancelDoc').hide();
    //     $('#obtCRVApproveDoc').hide();
    //     $('#odvCRVBtnGrpSave').hide();
    // }

    $.ajax({
        type    : "POST",
        url     : "docCRVPdtAdvanceTableLoadData",
        data    : {
            'ptSearchPdtAdvTable'    : tSearchPdtAdvTable,
            'ptCRVBchCode'           : $('#ohdCRVBchCode').val(),
            'ptCRVDocNo'             : tCRVDocNo
        },
        cache: false,
        Timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['checksession'] == 'expire') {
                JCNxShowMsgSessionExpired();
            } else {
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvCRVDataPanelDetailPDT #odvCRVDataPdtTableDTTemp').html(aReturnData['tCRVPdtAdvTableHtml']);
                    // if ($('#ohdCRVStaImport').val() == 1) {
                    //     $('.xCRVImportDT').show();
                    // }
                    JCNxCloseLoading();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSvCRVCRVCFilterPdtInTableTemp() {
    JCNxOpenLoading();
    JSvPOLoadPdtDataTableHtml();
}

// Function Chack Value LocalStorage
function JStCRVFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

function JSxCRVSetStatusClickSubmit(pnStatus) {
    if (pnStatus == 1) {
        $('#ohdCRVApvOrSave').val('');
    }else if (pnStatus == 2) {
        $('#ohdCRVApvOrSave').val('approve');
    }
    $("#ohdCRVCheckSubmitByButton").val(pnStatus);
}

// Add/Edit Document
function JSxCRVAddEditDocument() { 
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxCRVValidateFormDocument();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Document Single
function JSoCRVDelDocSingle(ptCurrentPage, ptCRVDocNo, tBchCode, ptCRVRefInCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptCRVDocNo) != undefined && ptCRVDocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptCRVDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvCRVModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvCRVModalDelDocSingle').modal('show');
            $('#odvCRVModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docCRVEventDelete",
                    data: {
                        'tDataDocNo': ptCRVDocNo,
                        'tBchCode': tBchCode,
                        'tCRVRefInCode': ptCRVRefInCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvCRVModalDelDocSingle').modal('hide');
                            $('#odvCRVModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                                JSvCRVCallPageDataTable(ptCurrentPage);
                            }, 500);
                        } else {
                            JCNxCloseLoading();
                            FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        } else {
            FSvCMNSetMsgErrorDialog('Error Not Found Document Number !!');
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Doc Mutiple
function JSoCRVDelDocMultiple() {
    var aDataDelMultiple = $('#odvCRVModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
    var aTextsDelMultiple = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
    var aDataSplit = aTextsDelMultiple.split(" , ");
    var nDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];
    for ($i = 0; $i < nDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }
    if (nDataSplitlength > 1) {

        JCNxOpenLoading();
        $('.ocbListItem:checked').each(function() {
            var tDataDocNo = $(this).val();
            var tBchCode = $(this).data('bchcode');
            var tCRVRefInCode = $(this).data('refcode');
            localStorage.StaDeleteArray = '1';
            $.ajax({
                type: "POST",
                url: "docCRVEventDelete",
                data: {
                    'tDataDocNo': tDataDocNo,
                    'tBchCode': tBchCode,
                    'tCRVRefInCode': tCRVRefInCode
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvCRVModalDelDocMultiple').modal('hide');
                            $('#odvCRVModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvCRVModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                            JSvCRVCallPageList();
                        }, 1000);
                    } else {
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        });


    }
}

// Function: Function Chack And Show Button Delete All
function JSxCRVShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliCRVBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliCRVBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliCRVBtnDeleteAll").addClass("disabled");
        }
    }
}

// Function: Function Chack Value LocalStorage
function JStCRVFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

//ยกเลิกเอกสาร
function JSnCRVCancelDocument(pbIsConfirm) {
    var tCRVDocNo    = $("#oetCRVDocNo").val();
    var tRefInDocNo = $('#oetCRVRefDocIntName').val();
    if (pbIsConfirm) {
        $.ajax({
            type    : "POST",
            url     : "docCRVCancelDocument",
            data    : {
                'ptCRVDocNo'     : tCRVDocNo,
                'ptRefInDocNo'  : tRefInDocNo,
                'ptStaApv'      : $('#ohdCRVStaApv').val(),
                'ptBchCode'     : $('#oetCRVFrmBchCode').val()
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {

                $("#odvCRVPopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);

                if (aReturnData['nStaEvent'] == '1') {
                    JSvCRVCallPageEdit(tCRVDocNo);
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
        $.ajax({
            type    : "POST",
            url     : "docCRVCancelCheckDocref",
            data    : {
                'ptCRVDocNo'     : tCRVDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvCRVPopupCancel').modal({ backdrop: 'static', keyboard: false });
                    $("#odvCRVPopupCancel").modal("show");
                } else if(aReturnData['nStaEvent'] == '2'){
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                } 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function: Function Control Object Button
function JCNxCRVControlObjAndBtn() { // Check สถานะอนุมัติ
    var nCRVStaDoc = $("#ohdCRVStaDoc").val();
    var nCRVStaApv = $("#ohdCRVStaApv").val();
    var nCRVRefPO  = $("#ohdCRVPORef").val();
    var nCRVRefIV  = $("#ohdCRVPIRef").val();
    var nCRVRefSO  = $("#ohdCRVSORef").val();

    // Status Cancel
    if (nCRVStaDoc == 3) {
        $("#oliCRVTitleAdd").hide();
        $('#oliCRVTitleEdit').hide();
        $('#oliCRVTitleDetail').show();
        $('#oliCRVTitleAprove').hide();
        $('#oliCRVTitleConimg').hide();
        // Hide And Disabled
        $("#obtCRVCallPageAdd").hide();
        $("#obtCRVCancelDoc").hide(); 
        $("#obtCRVApproveDoc").hide(); 
        $("#obtCRVBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetCRVFrmSearchPdtHTML").attr("disabled", false);
        $('#odvCRVBtnGrpSave').show();
        $("#oliCRVEditShipAddress").hide();
        $("#oliCRVEditTexAddress").hide();
        $("#oliCRVTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWCRVDisabledOnApv').attr('disabled', true);
        $("#ocbCRVFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtCRVFrmBrowseShipAdd").attr("disabled", true);
        $("#obtCRVFrmBrowseTaxAdd").attr("disabled", true);


    }

    // Status Appove Success
    if (nCRVStaDoc == 1 && nCRVStaApv == 1) { // Hide/Show Menu Title
        $("#oliCRVTitleAdd").hide();
        $('#oliCRVTitleEdit').hide();
        $('#oliCRVTitleDetail').show();
        $('#oliCRVTitleAprove').hide();
        $('#oliCRVTitleConimg').hide();
        // Hide And Disabled
        $("#obtCRVCallPageAdd").hide();
        $("#obtCRVCancelDoc").show(); 
        $("#obtCRVApproveDoc").hide(); 
        $("#obtCRVBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetCRVFrmSearchPdtHTML").attr("disabled", false);
        $('#odvCRVBtnGrpSave').show();
        $("#oliCRVEditShipAddress").hide();
        $("#oliCRVEditTexAddress").hide();
        $("#oliCRVTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWCRVDisabledOnApv').attr('disabled', true);
        $("#ocbCRVFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtCRVFrmBrowseShipAdd").attr("disabled", true);
        $("#obtCRVFrmBrowseTaxAdd").attr("disabled", true);
        if(nCRVRefIV != ''){
            $("#obtCRVCancelDoc").hide(); 
        }

        // ปุ่มสร้างใบสั่งขาย
        if(nCRVRefSO != ''){
            $("#obtCRVGenSO").hide(); 
        }else{
            $("#obtCRVGenSO").show(); 
        }
    }
}


function JSvCRVPageBeforeADD(ptBchCode,ptDocno ){
    var nStaSession = JCNxFuncChkSessionExpired();
    $("#oetCRVDocFill").val(ptDocno);
    $("#oetCRVBchFill").val(ptBchCode);
    if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        var tUsrCode = $("#ohdCRVUsrCodeChk").val();
        $.ajax({
            type: "POST",
            url: "dcmSODataCheckByPass",
            data: {
                tUsrCode  : tUsrCode,
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1'){  
                    $('.modal-backdrop').remove();
                    var tBypass = aReturnData['aItems']['tByPass'];

                    $("#oetCRVDFillUser").val('');
                    $("#oetCRVDFillUser").text('');
                    $("#oetCRVFillPass").val('');
                    $("#oetCRVFillPass").text('');
                    if(tBypass == '1'){
                        JSvCRVCallPageEdit(ptBchCode,ptDocno)
                    }else{
                        $('#odvCRVChkLoginModal').modal({backdrop:'static',keyboard:false});
                        $("#odvCRVChkLoginModal").modal("show");
                    }
                    
                }else{
                    FSvCMNSetMsgErrorDialog('ไม่พบเลขที่ใบฝากของ');  
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}


function JSxSOFillUserDocument(pbIsConfirm){
    var tUserName = $("#oetCRVDFillUser").val();
    var tUserPass = JCNtAES128EncryptData($("#oetCRVFillPass").val(), tKey, tIV);
    if(pbIsConfirm){
        $.ajax({
        type: "POST",
        url: "dcmSOCheckUserLogin",
        data: {
            tUserName  : tUserName,
            tUserPass  : tUserPass,
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if(aReturnData['nStaEvent'] == '1'){  
                var tBchCode = $("#oetCRVBchFill").val();
                var tDocno   = $("#oetCRVDocFill").val();
                $('.modal-backdrop').remove();
                JSvCRVCallPageEdit(tBchCode,tDocno);
            }else{
                FSvCMNSetMsgErrorDialog('ผู้ใช้ไม่มีสิทธิ์รับของ');  
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
    }
    $("#oetCRVDFillUser").val('');
    $("#oetCRVDFillUser").text('');
    $("#oetCRVFillPass").val('');
    $("#oetCRVFillPass").text('');
}