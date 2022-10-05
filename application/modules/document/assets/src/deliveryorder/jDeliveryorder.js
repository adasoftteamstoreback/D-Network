var nDOStaDOBrowseType = $("#oetDOStaBrowse").val();
var tDOCallDOBackOption = $("#oetDOCallBackOption").val();
var tDOSesSessionID = $("#ohdSesSessionID").val();
var tDOSesSessionName = $("#ohdSesSessionName").val();

$("document").ready(function() {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    if (typeof(nDOStaDOBrowseType) != 'undefined' && (nDOStaDOBrowseType == 0 || nDOStaDOBrowseType ==2)) { // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliDOTitle').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDOCallPageList();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtDOCallBackPage').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDOCallPageList();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Add Page
        $('#obtDOCallPageAdd').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDOCallPageAddDoc();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Cancel Document
        $('#obtDOCancelDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSnDOCancelDocument(false);
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Appove Document
        $('#obtDOApproveDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tFrmSplName = $('#oetDOFrmSplName').val();
                var tDOFrmWahName = $('#oetDOFrmWahName').val();
                var tCheckIteminTable = $('#otbDODocPdtAdvTableList .xWPdtItem').length;
                var nPOStaValidate = $('.xPOStaValidate0').length;
                if (tCheckIteminTable > 0) {
                    if (nPOStaValidate == 0) {
                        //เช็คค่าว่างตัวแทนขาย
                        if (tFrmSplName == '') {
                            $('#odvDOModalPleseselectSPL').modal('show');
                            //เช็คค่าว่างคลังสินค้า
                        } else if (tDOFrmWahName == '') {
                            $('#odvDOModalWahNoFound').modal('show');
                        } else {
                            JSxDOSetStatusClickSubmit(2);
                            JSxDOSubmitEventByButton('approve');
                        }

                    } else {
                        $('#odvDOModalImpackImportExcel').modal('show')
                    }
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdDOValidatePdt').val());
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // ปุ่ม สร้างใบสั่งขาย
        $('#obtDOGenSO').unbind().click(function() {
            $.ajax({
                type    : "POST",
                url     : "docDOEventGenSO",
                data    : {
                    tDocNo      : $('#oetDODocNo').val(),
                    tBchCode    : $('#ohdDOBchCode').val()
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    console.log(tResult);
                    JSvDOCallPageList();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });

        // กดปุ่มบันทึก
        $('#obtDOSubmitFromDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tFrmSplName = $('#oetDOFrmSplName').val();
                var tDOFrmWahName = $('#oetDOFrmWahName').val();
                var tCheckIteminTable = $('#otbDODocPdtAdvTableList .xWPdtItem').length;
                var nPOStaValidate = $('.xPOStaValidate0').length;
                if (tCheckIteminTable > 0) {
                    if (nPOStaValidate == 0) {
                        //เช็คค่าว่างตัวแทนขาย
                        if (tFrmSplName == '') {
                            $('#odvDOModalPleseselectSPL').modal('show');
                            //เช็คค่าว่างคลังสินค้า
                        } else if (tDOFrmWahName == '') {
                            $('#odvDOModalWahNoFound').modal('show');
                        } else {
                            JSxDOSetStatusClickSubmit(1);
                            $('#obtDOSubmitDocument').click();
                        }

                    } else {
                        $('#odvDOModalImpackImportExcel').modal('show')
                    }
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdDOValidatePdt').val());
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        JSxDONavDefult('showpage_list');

        switch(nDOStaDOBrowseType){
            case '2':
                var tAgnCode = $('#oetDOJumpAgnCode').val();
                var tBchCode = $('#oetDOJumpBchCode').val();
                var tDocNo = $('#oetDOJumpDocNo').val();
                JSvDOCallPageEdit(tDocNo);
            break;
            default:
                JSvDOCallPageList();
        }
    } else {
        JSxDONavDefult('showpage_list');
        JSvDOCallPageAddDoc();
    }
});

// อนุมัติเอกสาร
function JSxDOApproveDocument(pbIsConfirm) {
    try {
        if (pbIsConfirm) {
            $("#odvDOModalAppoveDoc").modal('hide');
            var tAgnCode    = $('#oetDOAgnCode').val();
            var tDocNo      = $('#oetDODocNo').val();
            var tBchCode    = $('#ohdDOBchCode').val();
            var tRefInDocNo = $('#oetDORefDocIntName').val();
            $.ajax({
                type: "POST",
                url: "docDOApproveDocument",
                data: {
                    tDocNo: tDocNo,
                    tBchCode: tBchCode,
                    tAgnCode: tAgnCode,
                    tRefInDocNo: tRefInDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvDOModalAppoveDoc").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSoDOCallSubscribeMQ();
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
            $("#odvDOModalAppoveDoc").modal('show');
        }
    } catch (err) {
        console.log("JSxDOApproveDocument Error: ", err);
    }
}


// Control เมนู
function JSxDONavDefult(ptType) {
    if (ptType == 'showpage_list') { // แสดง
        $("#oliDOTitle").show();
        $("#odvDOBtnGrpInfo").show();
        $("#obtDOCallPageAdd").show();

        // ซ่อน
        $("#oliDOTitleAdd").hide();
        $("#oliDOTitleEdit").hide();
        $("#oliDOTitleDetail").hide();
        $("#oliDOTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtDOCallBackPage").hide();
        $("#obtDOPrintDoc").hide();
        $("#obtDOCancelDoc").hide();
        $("#obtDOApproveDoc").hide();
        $("#obtDOGenSO").hide();
        $("#odvDOBtnGrpSave").hide();

    } else if (ptType == 'showpage_add') { // แสดง
        $("#oliDOTitle").show();
        $("#odvDOBtnGrpSave").show();
        $("#obtDOCallBackPage").show();
        $("#oliDOTitleAdd").show();

        // ซ่อน
        $("#oliDOTitleEdit").hide();
        $("#oliDOTitleDetail").hide();
        $("#oliDOTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtDOPrintDoc").hide();
        $("#obtDOCancelDoc").hide();
        $("#obtDOApproveDoc").hide();
        $("#obtDOGenSO").hide();
        $("#odvDOBtnGrpInfo").hide();
    } else if (ptType == 'showpage_edit') { // แสดง
        $("#oliDOTitle").show();
        $("#odvDOBtnGrpSave").show();
        $("#obtDOApproveDoc").show();
        $("#obtDOGenSO").hide();
        $("#obtDOCancelDoc").show();
        $("#obtDOCallBackPage").show();
        $("#oliDOTitleEdit").show();
        $("#obtDOPrintDoc").show();

        // ซ่อน
        $("#oliDOTitleAdd").hide();
        $("#oliDOTitleDetail").hide();
        $("#oliDOTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#odvDOBtnGrpInfo").hide();
    }

    // ล้างค่า
    localStorage.removeItem('IV_LocalItemDataDelDtTemp');
    localStorage.removeItem('LocalItemData');
}

// Function: Call Page List
function JSvDOCallPageList() {
    $.ajax({
        type: "GET",
        url: "dcmDOFormSearchList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvDOContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSxDONavDefult('showpage_list');
            JSvDOCallPageDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function: Call Page DataTable
function JSvDOCallPageDataTable(pnPage) {
    JCNxOpenLoading();
    var oAdvanceSearch = JSoDOGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docDODataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostDODataTableDocument').html(aReturnData['tDOViewDataTableList']);
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
function JSoDOGetAdvanceSearchData() {
    var oAdvanceSearchData = {
        tSearchAll: $("#oetDOSearchAllDocument").val(),
        tSearchBchCodeFrom: $("#oetDOAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo: $("#oetDOAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom: $("#oetDOAdvSearcDocDateFrom").val(),
        tSearchDocDateTo: $("#oetDOAdvSearcDocDateTo").val(),
        tSearchStaDoc: $("#ocmDOAdvSearchStaDoc").val(),
        tSearchStaDocAct: $("#ocmStaDocAct").val()
    };
    return oAdvanceSearchData;
}

// เข้ามาแบบ insert
function JSvDOCallPageAddDoc() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docDOPageAdd",
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxDONavDefult('showpage_add');
                $('#odvDOContentPageDocument').html(aReturnData['tDOViewPageAdd']);
                $("#ocmDOTypePayment").val("1").selectpicker('refresh');
                $('.xCNPanel_CreditTerm').hide();
                JSvDOLoadPdtDataTableHtml();
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
function JSvDOCallPageEdit(ptDocumentNumber, pnDOPayType, pnDOVatInOrEx, pnDOStaRef) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docDOPageEdit",
            data: {
                'ptDODocNo': ptDocumentNumber
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JCNxCloseLoading();
                var aReturnData = JSON.parse(tResult)
                if (aReturnData['nStaEvent'] == '1') {
                    JSxDONavDefult('showpage_edit');
                    $('#odvDOContentPageDocument').html(aReturnData['tViewPageEdit']);
                    //ประเภทชำระเงิน
                    var nDOPayType = $('#ohdDORteFac').val();
                    if (nDOPayType == 1 || pnDOPayType == 1) {
                        $("#ocmDOTypePayment").val("1").selectpicker('refresh');
                        $('.xCNPanel_CreditTerm').hide();
                    } else {
                        $("#ocmDOTypePayment").val("2").selectpicker('refresh');
                        $('.xCNPanel_CreditTerm').show();
                    }

                    //ประเภทภาษี
                    var nDOVatInOrEx = $('#ohdDOVatInOrEx').val();
                    if (nDOVatInOrEx == 1 || pnDOVatInOrEx == 1) {
                        $("#ocmDOFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');
                    } else {
                        $("#ocmDOFrmSplInfoVatInOrEx").val("2").selectpicker('refresh');
                    }

                    //สถานะอ้างอิง
                    var nDOStaRef = $('#ohdDOStaRef').val();
                    if (nDOStaRef == 0 || pnDOStaRef == 0) {
                        $("#ocmDOFrmInfoOthRef").val("0").selectpicker('refresh');
                    } else if (nDOStaRef == 1 || pnDOStaRef == 1) {
                        $("#ocmDOFrmInfoOthRef").val("1").selectpicker('refresh');
                    } else {
                        $("#ocmDOFrmInfoOthRef").val("2").selectpicker('refresh');
                    }
                    JSvDOLoadPdtDataTableHtml();

                    // เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxDOControlFormWhenCancelOrApprove();
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
function JSxDOControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdDOStaDoc').val();
    var tStatusApv = $('#ohdDOStaApv').val();

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
        $('#obtDOCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtDOApproveDoc').hide();

        // ปุ่มสร้างใบสั่งขาย
        $("#obtDOGenSO").hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxDOControlObjAndBtn();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // เอกสารอนุมัติแล้ว
        // ปุ่มยกเลิก
        $('#obtDOCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtDOApproveDoc').hide();

        // ปุ่มสร้างใบสั่งขาย
        $("#obtDOGenSO").hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxDOControlObjAndBtn();
    }
}

// Function : Call Page Product Table In Add Document
function JSvDOLoadPdtDataTableHtml(pnPage) {
    if ($("#ohdDORoute").val() == "docDOEventAdd") {
        var tDODocNo = "";
    } else {
        var tDODocNo = $("#oetDODocNo").val();
    }
    var tDOStaApv = $("#ohdDOStaApv").val();
    var tDOStaDoc = $("#ohdDOStaDoc").val();
    var tDOVATInOrEx = $("#ocmDOFrmSplInfoVatInOrEx").val();

    // เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
    if ($("#otbDODocPdtAdvTableList .xWPdtItem").length == 0) {
        if (pnPage != undefined) {
            pnPage = pnPage - 1;
        }
    }

    if (pnPage == '' || pnPage == null) {
        var pnNewPage = 1;
    } else {
        var pnNewPage = pnPage;
    }
    var nPageCurrent = pnNewPage;
    var tSearchPdtAdvTable = $('#oetDOFrmFilterPdtHTML').val();

    // if (tDOStaApv == 2) {
    //     $('#obtDODocBrowsePdt').hide();
    //     $('#obtDOPrintDoc').hide();
    //     $('#obtDOCancelDoc').hide();
    //     $('#obtDOApproveDoc').hide();
    //     $('#odvDOBtnGrpSave').hide();
    // }

    $.ajax({
        type    : "POST",
        url     : "docDOPdtAdvanceTableLoadData",
        data    : {
            'tSelectBCH'            : $('#oetDOFrmBchCode').val(),
            'ptSearchPdtAdvTable'   : tSearchPdtAdvTable,
            'ptDODocNo'             : tDODocNo,
            'ptDOStaApv'            : tDOStaApv,
            'ptDOStaDoc'            : tDOStaDoc,
            'ptDOVATInOrEx'         : tDOVATInOrEx,
            'pnDOPageCurrent'       : nPageCurrent
        },
        cache: false,
        Timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['checksession'] == 'expire') {
                JCNxShowMsgSessionExpired();
            } else {
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvDODataPanelDetailPDT #odvDODataPdtTableDTTemp').html(aReturnData['tDOPdtAdvTableHtml']);
                    if ($('#ohdDOStaImport').val() == 1) {
                        $('.xDOImportDT').show();
                    }
                    JCNxCloseLoading();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JSvDOLoadPdtDataTableHtml(pnPage)
        }
    });
}

// Function : Add Product Into Table Document DT Temp
function JCNvDOBrowsePdt() {
    var tDOSplCode = $('#oetDOFrmSplCode').val();

    if (typeof(tDOSplCode) !== undefined && tDOSplCode !== '') {
        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                Qualitysearch: [],
                PriceType: [
                    "Cost", "tCN_Cost", "Company", "1"
                ],
                SelectTier: ["Barcode"],
                ShowCountRecord: 10,
                NextFunc: "FSvDONextFuncB4SelPDT",
                ReturnType: "M",
                'aAlwPdtType' : ['T1','T3','T4','T5','T6','S2','S3','S4'],
                'Where' : [" AND Products.FTPdtStkControl = 1 "]
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvModalDOCPDT").modal({ backdrop: "static", keyboard: false });
                $("#odvModalDOCPDT").modal({ show: true });
                // remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $("#odvModalsectionBodyPDT").html(tResult);
                $("#odvModalDOCPDT #oliBrowsePDTSupply").css('display', 'none');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
        FSvCMNSetMsgWarningDialog(tWarningMessage);
        return;
    }
}

function JSvDODOCFilterPdtInTableTemp() {
    JCNxOpenLoading();
    JSvPOLoadPdtDataTableHtml();
}

// Function Chack Value LocalStorage
function JStDOFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

function JSxDOSetStatusClickSubmit(pnStatus) {
    if (pnStatus == 1) {
        $('#ohdDOApvOrSave').val('');
    }else if (pnStatus == 2) {
        $('#ohdDOApvOrSave').val('approve');
    }
    $("#ohdDOCheckSubmitByButton").val(pnStatus);
}

// Add/Edit Document
function JSxDOAddEditDocument() { 
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxDOValidateFormDocument();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Document Single
function JSoDODelDocSingle(ptCurrentPage, ptDODocNo, tBchCode, ptDORefInCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptDODocNo) != undefined && ptDODocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptDODocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvDOModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvDOModalDelDocSingle').modal('show');
            $('#odvDOModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docDOEventDelete",
                    data: {
                        'tDataDocNo': ptDODocNo,
                        'tBchCode': tBchCode,
                        'tDORefInCode': ptDORefInCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvDOModalDelDocSingle').modal('hide');
                            $('#odvDOModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                                JSvDOCallPageDataTable(ptCurrentPage);
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
function JSoDODelDocMultiple() {
    var aDataDelMultiple = $('#odvDOModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
            var tDORefInCode = $(this).data('refcode');
            localStorage.StaDeleteArray = '1';
            $.ajax({
                type: "POST",
                url: "docDOEventDelete",
                data: {
                    'tDataDocNo': tDataDocNo,
                    'tBchCode': tBchCode,
                    'tDORefInCode': tDORefInCode
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvDOModalDelDocMultiple').modal('hide');
                            $('#odvDOModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvDOModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                            JSvDOCallPageList();
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
function JSxDOShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliDOBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliDOBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliDOBtnDeleteAll").addClass("disabled");
        }
    }
}

// Function: Function Chack Value LocalStorage
function JStDOFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

//ยกเลิกเอกสาร
function JSnDOCancelDocument(pbIsConfirm) {
    var tDODocNo    = $("#oetDODocNo").val();
    var tRefInDocNo = $('#oetDORefDocIntName').val();
    if (pbIsConfirm) {
        $.ajax({
            type    : "POST",
            url     : "docDOCancelDocument",
            data    : {
                'ptDODocNo'     : tDODocNo,
                'ptRefInDocNo'  : tRefInDocNo,
                'ptStaApv'      : $('#ohdDOStaApv').val(),
                'ptBchCode'     : $('#oetDOFrmBchCode').val()
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {

                $("#odvDOPopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);

                if (aReturnData['nStaEvent'] == '1') {
                    JSvDOCallPageEdit(tDODocNo);
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
            url     : "docDOCancelCheckDocref",
            data    : {
                'ptDODocNo'     : tDODocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvDOPopupCancel').modal({ backdrop: 'static', keyboard: false });
                    $("#odvDOPopupCancel").modal("show");
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
function JCNxDOControlObjAndBtn() { // Check สถานะอนุมัติ
    var nDOStaDoc = $("#ohdDOStaDoc").val();
    var nDOStaApv = $("#ohdDOStaApv").val();
    var nDORefPO  = $("#ohdDOPORef").val();
    var nDORefIV  = $("#ohdDOPIRef").val();
    var nDORefSO  = $("#ohdDOSORef").val();

    // Status Cancel
    if (nDOStaDoc == 3) {
        $("#oliDOTitleAdd").hide();
        $('#oliDOTitleEdit').hide();
        $('#oliDOTitleDetail').show();
        $('#oliDOTitleAprove').hide();
        $('#oliDOTitleConimg').hide();
        // Hide And Disabled
        $("#obtDOCallPageAdd").hide();
        $("#obtDOCancelDoc").hide(); 
        $("#obtDOApproveDoc").hide(); 
        $("#obtDOBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetDOFrmSearchPdtHTML").attr("disabled", false);
        $('#odvDOBtnGrpSave').show();
        $("#oliDOEditShipAddress").hide();
        $("#oliDOEditTexAddress").hide();
        $("#oliDOTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWDODisabledOnApv').attr('disabled', true);
        $("#ocbDOFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtDOFrmBrowseShipAdd").attr("disabled", true);
        $("#obtDOFrmBrowseTaxAdd").attr("disabled", true);


    }

    // Status Appove Success
    if (nDOStaDoc == 1 && nDOStaApv == 1) { // Hide/Show Menu Title
        $("#oliDOTitleAdd").hide();
        $('#oliDOTitleEdit').hide();
        $('#oliDOTitleDetail').show();
        $('#oliDOTitleAprove').hide();
        $('#oliDOTitleConimg').hide();
        // Hide And Disabled
        $("#obtDOCallPageAdd").hide();
        $("#obtDOCancelDoc").show(); 
        $("#obtDOApproveDoc").hide(); 
        $("#obtDOBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetDOFrmSearchPdtHTML").attr("disabled", false);
        $('#odvDOBtnGrpSave').show();
        $("#oliDOEditShipAddress").hide();
        $("#oliDOEditTexAddress").hide();
        $("#oliDOTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWDODisabledOnApv').attr('disabled', true);
        $("#ocbDOFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtDOFrmBrowseShipAdd").attr("disabled", true);
        $("#obtDOFrmBrowseTaxAdd").attr("disabled", true);
        if(nDORefIV != ''){
            $("#obtDOCancelDoc").hide(); 
        }

        // ปุ่มสร้างใบสั่งขาย
        if(nDORefSO != ''){
            $("#obtDOGenSO").hide(); 
        }else{
            $("#obtDOGenSO").show(); 
        }
    }
}