var tBKOCallBackOption  = $("#oetBKOCallBackOption").val();
var tTWXSesSessionID    = $("#ohdSesSessionID").val();
var tTWXSesSessionName  = $("#ohdSesSessionName").val();

$("document").ready(function() {
    localStorage.removeItem("LocalItemData");
    JSxTWXNavDefult('showpage_list');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/

    $('#oliTWXTitle').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvTWXCallPageList();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtTWXCallBackPage').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvTWXCallPageList();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Button Add Page
    $('#obtTWXCallPageAdd').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvTWXCallPageAddDoc();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Cancel Document
    $('#obtTWXCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnTWXCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Appove Document
    $('#obtTWXApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            var tCheckIteminTable = $('#otbTWXDocPdtAdvTableList .xWPdtItem').length;
            if (tCheckIteminTable > 0) {
                JSxTWXSetStatusClickSubmit(2);
                JSxTWXApproveDocument(false);
            } else {
                FSvCMNSetMsgWarningDialog($('#ohdTWXValidatePdt').val());
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Submit From Document
    $('#obtTWXSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            var tFrmSplName = $('#oetTWXFrmCstName').val();
            var tTWXFrmWahName = $('#oetTWXWahFrmName').val();
            var tTWXBookWahName = $('#oetTWXWahBookName').val();
            var tCheckIteminTable = $('#otbTWXDocPdtAdvTableList .xWPdtItem').length;
            var nPOStaValidate = $('.xPOStaValidate0').length;
            if (tCheckIteminTable > 0) {
                if (nPOStaValidate == 0) {
                    //เช็คค่าว่างตัวแทนขาย
                    if (tFrmSplName == '') {
                        $('#odvTWXModalPleseselectCST').modal('show');
                        //เช็คค่าว่างคลังสินค้า
                    } else if (tTWXFrmWahName == '' || tTWXBookWahName == '') {
                        $('#odvTWXModalWahNoFound').modal('show');
                    } else {
                        JSxTWXSetStatusClickSubmit(1);
                        $('#obtTWXSubmitDocument').click();
                    }
                } else {
                    $('#odvTWXModalImpackImportExcel').modal('show')
                }
            } else {
                FSvCMNSetMsgWarningDialog($('#ohdTWXValidatePdt').val());
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //รองรับการเข้ามาแบบ Noti
    var nBKOStaBrowseType = $("#oetBKOStaBrowse").val();
    switch(nBKOStaBrowseType){
        case '2':
            var tAgnCode    = $('#oetBKOJumpAgnCode').val();
            var tBchCode    = $('#oetBKOJumpBchCode').val();
            var tDocNo      = $('#oetBKOJumpDocNo').val();
            JSvTWXCallPageEdit(tDocNo);
        break;
        default:
            JSvTWXCallPageList();
    }

});

// อนุมัติเอกสาร
function JSxTWXApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                $("#odvTWXModalAppoveDoc").modal('hide');

                var tDocNo = $('#oetTWXDocNo').val();
                var tBchCode = $('#ohdTWXBchCode').val();
                var tRefInDocNo = $('#oetTWXRefDocIntName').val();

                var tFrmSplName = $('#oetTWXFrmCstName').val();
                var tTWXFrmWahName = $('#oetTWXWahFrmName').val();
                var tTWXBookWahName = $('#oetTWXWahBookName').val();
                var tCheckIteminTable = $('#otbTWXDocPdtAdvTableList .xWPdtItem').length;
                var nPOStaValidate = $('.xPOStaValidate0').length;
                if (tCheckIteminTable > 0) {
                    if (nPOStaValidate == 0) {
                        //เช็คค่าว่างตัวแทนขาย
                        if (tFrmSplName == '') {
                            $('#odvTWXModalPleseselectCST').modal('show');
                            //เช็คค่าว่างคลังสินค้า
                        } else if (tTWXFrmWahName == '' || tTWXBookWahName == '') {
                            $('#odvTWXModalWahNoFound').modal('show');
                        } else {
                            JSxTWXSubmitEventByButton('approve');
                        }
                    } else {
                        $('#odvTWXModalImpackImportExcel').modal('show')
                    }
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdTWXValidatePdt').val());
                }

                // $.ajax({
                //     type: "POST",
                //     url: "docBKOApproveDocument",
                //     data: {
                //         tDocNo: tDocNo,
                //         tBchCode: tBchCode,
                //         tRefInDocNo: tRefInDocNo
                //     },
                //     cache: false,
                //     timeout: 0,
                //     success: function(tResult) {
                //         $("#odvTWXModalAppoveDoc").modal("hide");
                //         $('.modal-backdrop').remove();
                //         var aReturnData = JSON.parse(tResult);
                //         if (aReturnData['nStaEvent'] == '1') {
                //             JSvTWXCallPageEdit(tDocNo);
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
                $("#odvTWXModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxTWXApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Rabbit MQ
function JSoTWXCallSubscribeMQ() {
    // Document variable
    var tLangCode = $("#ohdTWXLangEdit").val();
    var tUsrBchCode = $("#ohdTWXBchCode").val();
    var tUsrApv = $("#ohdSesSessionName").val();
    var tDocNo = $("#oetTWXDocNo").val();
    var tPrefix = "RESDO";
    var tStaApv = $("#ohdTWXStaApv").val();
    var tStaDelMQ = 1;
    var tQName = tPrefix + "_" + tDocNo + "_" + tUsrApv;

    // MQ Message Config
    var poDocConfig = {
        tLangCode: tLangCode,
        tUsrBchCode: tUsrBchCode,
        tUsrApv: tUsrApv,
        tDocNo: tDocNo,
        tPrefix: tPrefix,
        tStaDelMQ: tStaDelMQ,
        tStaApv: tStaApv,
        tQName: tQName
    };

    // RabbitMQ STOMP Config
    var poMqConfig = {
        host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
        username: oSTOMMQConfig.user,
        password: oSTOMMQConfig.password,
        vHost: oSTOMMQConfig.vhost
    };

    // Update Status For Delete Qname Parameter
    var poUpdateStaDelQnameParams = {
        ptDocTableName: "TCNTPdtReqSplHD",
        ptDocFieldDocNo: "FTXphDocNo",
        ptDocFieldStaApv: "FTXphStaPrcStk",
        ptDocFieldStaDelMQ: "FTXphStaDelMQ",
        ptDocStaDelMQ: tStaDelMQ,
        ptDocNo: tDocNo
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit: "JSvTWXCallPageEdit",
        tCallPageList: "JSvTWXCallPageList"
    };

    // Check Show Progress %
    FSxCMNRabbitMQMessage(poDocConfig, poMqConfig, poUpdateStaDelQnameParams, poCallback);
}

// Control เมนู
function JSxTWXNavDefult(ptType) {
    if (ptType == 'showpage_list') { // แสดง
        $("#oliTWXTitle").show();
        $("#odvTWXBtnGrpInfo").show();
        $("#obtTWXCallPageAdd").show();

        // ซ่อน
        $("#oliTWXTitleAdd").hide();
        $("#oliTWXTitleEdit").hide();
        $("#oliTWXTitleDetail").hide();
        $("#oliTWXTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtTWXCallBackPage").hide();
        $("#obtTWXPrintDoc").hide();
        $("#obtTWXCancelDoc").hide();
        $("#obtTWXApproveDoc").hide();
        $("#odvTWXBtnGrpSave").hide();

    } else if (ptType == 'showpage_add') { // แสดง
        $("#oliTWXTitle").show();
        $("#odvTWXBtnGrpSave").show();
        $("#obtTWXCallBackPage").show();
        $("#oliTWXTitleAdd").show();

        // ซ่อน
        $("#oliTWXTitleEdit").hide();
        $("#oliTWXTitleDetail").hide();
        $("#oliTWXTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtTWXPrintDoc").hide();
        $("#obtTWXCancelDoc").hide();
        $("#obtTWXApproveDoc").hide();
        $("#odvTWXBtnGrpInfo").hide();
    } else if (ptType == 'showpage_edit') { // แสดง
        $("#oliTWXTitle").show();
        $("#odvTWXBtnGrpSave").show();
        $("#obtTWXApproveDoc").show();
        $("#obtTWXCancelDoc").show();
        $("#obtTWXCallBackPage").show();
        $("#oliTWXTitleEdit").show();
        $("#obtTWXPrintDoc").show();

        // ซ่อน
        $("#oliTWXTitleAdd").hide();
        $("#oliTWXTitleDetail").hide();
        $("#oliTWXTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#odvTWXBtnGrpInfo").hide();
    }

    // ล้างค่า
    localStorage.removeItem('IV_LocalItemDataDelDtTemp');
    localStorage.removeItem('LocalItemData');
}

// Function: Call Page List
function JSvTWXCallPageList() {
    $.ajax({
        type: "GET",
        url: "dcmBKOFormSearchList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvTWXContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSxTWXNavDefult('showpage_list');
            JSvTWXCallPageDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function: Call Page DataTable
function JSvTWXCallPageDataTable(pnPage) {
    JCNxOpenLoading();
    var oAdvanceSearch = JSoTWXGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docBKODataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostTWXDataTableDocument').html(aReturnData['tTWXViewDataTableList']);
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
function JSoTWXGetAdvanceSearchData() {
    var oAdvanceSearchData = {
        tSearchAll: $("#oetTWXSearchAllDocument").val(),
        tSearchBchCodeFrom: $("#oetTWXAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo: $("#oetTWXAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom: $("#oetTWXAdvSearcDocDateFrom").val(),
        tSearchDocDateTo: $("#oetTWXAdvSearcDocDateTo").val(),
        tSearchStaDoc: $("#ocmTWXAdvSearchStaDoc").val(),
        tSearchStaDocAct: $("#ocmStaDocAct").val()
    };
    return oAdvanceSearchData;
}

// เข้ามาแบบ insert
function JSvTWXCallPageAddDoc() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docBKOPageAdd",
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxTWXNavDefult('showpage_add');
                $('#odvTWXContentPageDocument').html(aReturnData['tTWXViewPageAdd']);
                JSvTWXLoadPdtDataTableHtml();
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
function JSvTWXCallPageEdit(ptDocumentNumber) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docBKOPageEdit",
            data    : {
                'ptTWXDocNo': ptDocumentNumber
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSxTWXNavDefult('showpage_edit');

                    $('#odvTWXContentPageDocument').html(aReturnData['tViewPageEdit']);
                    JSvTWXLoadPdtDataTableHtml();
                    JCNxCloseLoading();

                    // เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxTWXControlFormWhenCancelOrApprove();
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
function JSxTWXControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdTWXStaDoc').val();
    var tStatusApv = $('#ohdTWXStaApv').val();

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
        $('#obtTWXCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtTWXApproveDoc').hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').hide();

        JCNxTWXControlObjAndBtn();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // เอกสารอนุมัติแล้ว
        // ปุ่มยกเลิก
        $('#obtTWXCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtTWXApproveDoc').hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxTWXControlObjAndBtn();
    }
}

// Function : Call Page Product Table In Add Document
function JSvTWXLoadPdtDataTableHtml(pnPage) {
    if ($("#ohdTWXRoute").val() == "docBKOEventAdd") {
        var tTWXDocNo = "";
    } else {
        var tTWXDocNo = $("#oetTWXDocNo").val();
    }
    var tTWXStaApv = $("#ohdTWXStaApv").val();
    var tTWXStaDoc = $("#ohdTWXStaDoc").val();
    var tTWXVATInOrEx = $("#ocmTWXFrmSplInfoVatInOrEx").val();

    // เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
    if ($("#otbTWXDocPdtAdvTableList .xWPdtItem").length == 0) {
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
    var tSearchPdtAdvTable = $('#oetTWXFrmFilterPdtHTML').val();

    if (tTWXStaApv == 2) {
        $('#obtTWXDocBrowsePdt').hide();
        $('#obtTWXPrintDoc').hide();
        $('#obtTWXCancelDoc').hide();
        $('#obtTWXApproveDoc').hide();
        $('#odvTWXBtnGrpSave').hide();
    }

    $.ajax({
        type: "POST",
        url: "docBKOPdtAdvanceTableLoadData",
        data: {
            'tSelectBCH': $('#oetTWXWahBrcCode').val(),
            'ptSearchPdtAdvTable': tSearchPdtAdvTable,
            'ptTWXDocNo': tTWXDocNo,
            'ptTWXStaApv': tTWXStaApv,
            'ptTWXStaDoc': tTWXStaDoc,
            'ptTWXVATInOrEx': tTWXVATInOrEx,
            'pnTWXPageCurrent': nPageCurrent
        },
        cache: false,
        Timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['checksession'] == 'expire') {
                JCNxShowMsgSessionExpired();
            } else {
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvTWXDataPanelDetailPDT #odvTWXDataPdtTableDTTemp').html(aReturnData['tTWXPdtAdvTableHtml']);
                    if ($('#ohdTWXStaImport').val() == 1) {
                        $('.xTWXImportDT').show();
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
            JSvTWXLoadPdtDataTableHtml(pnPage)
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Add Product Into Table Document DT Temp
function JCNvTWXBrowsePdt() {
    var tTWXSplCode = $('#oetTWXFrmCstCode').val();

    if (typeof(tTWXSplCode) !== undefined && tTWXSplCode !== '') {
        var aMulti = [];

        //อนุญาต "ซื้อ" ที่หน่วย และ อนุญาต "ซื้อ" ที่บาร์โค๊ด
        var aWhereItem      = [];
        tPDTAlwSale         = ' AND (PPCZ.FTPdtStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PPCZ.FTPdtStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = ' AND (PBAR.FTBarStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PBAR.FTBarStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                Qualitysearch   : [],
                PriceType       : [ "Cost", "tCN_Cost", "Company", "1"],
                SelectTier      : ["Barcode"],
                ShowCountRecord : 10,
                BCH             : [$('#oetTWXWahBrcCode').val(),$('#oetTWXWahBrcCode').val()],
                NextFunc        : "FSvTWXNextFuncB4SelPDT",
                ReturnType      : "M",
                SPL             : ['',''],
                Where           : aWhereItem,
                aAlwPdtType     : ['T1','T3','T4','T5','T6','S2','S3','S4']
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

function JSvTWXDOCFilterPdtInTableTemp() {
    JCNxOpenLoading();
    JSvPOLoadPdtDataTableHtml();
}

// Function Chack Value LocalStorage
function JStTWXFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

function JSxTWXSetStatusClickSubmit(pnStatus) {
    $("#ohdTWXCheckSubmitByButton").val(pnStatus);
}

// Add/Edit Document
function JSxTWXAddEditDocument() { // var nStaSession = JCNxFuncChkSessionExpired();
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxTWXValidateFormDocument();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Document Single
function JSoTWXDelDocSingle(ptCurrentPage, ptTWXDocNo, tBchCode, ptTWXRefInCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptTWXDocNo) != undefined && ptTWXDocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptTWXDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvTWXModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvTWXModalDelDocSingle').modal('show');
            $('#odvTWXModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docBKOEventDelete",
                    data: {
                        'tDataDocNo': ptTWXDocNo,
                        'tBchCode': tBchCode,
                        'tTWXRefInCode': ptTWXRefInCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvTWXModalDelDocSingle').modal('hide');
                            $('#odvTWXModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                                JSvTWXCallPageDataTable(ptCurrentPage);
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
function JSoTWXDelDocMultiple() {
    var aDataDelMultiple = $('#odvTWXModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
            var tTWXRefInCode = $(this).data('refcode');
            localStorage.StaDeleteArray = '1';
            $.ajax({
                type: "POST",
                url: "docTWXEventDelete",
                data: {
                    'tDataDocNo': tDataDocNo,
                    'tBchCode': tBchCode,
                    'tTWXRefInCode': tTWXRefInCode
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvTWXModalDelDocMultiple').modal('hide');
                            $('#odvTWXModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvTWXModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                            JSvTWXCallPageList();
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
function JSxTWXShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliTWXBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliTWXBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliTWXBtnDeleteAll").addClass("disabled");
        }
    }
}

// Function: Function Chack Value LocalStorage
function JStTWXFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// Function: Cancel Document DO
function JSnTWXCancelDocument(pbIsConfirm) {
    var tTWXDocNo = $("#oetTWXDocNo").val();
    var tRefInDocNo = $('#ohdTWXRefIntDoc').val();
    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docBKOCancelDocument",
            data: {
                'ptTWXDocNo': tTWXDocNo,
                'ptRefInDocNo': tRefInDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvTWXPopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSvTWXCallPageEdit(tTWXDocNo);
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
        $('#odvTWXPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvTWXPopupCancel").modal("show");
    }
}

// Function: Function Control Object Button
function JCNxTWXControlObjAndBtn() { // Check สถานะอนุมัติ
    var nTWXStaDoc = $("#ohdTWXStaDoc").val();
    var nTWXStaApv = $("#ohdTWXStaApv").val();

    // Status Cancel
    if (nTWXStaDoc == 3) {
        $("#oliTWXTitleAdd").hide();
        $('#oliTWXTitleEdit').hide();
        $('#oliTWXTitleDetail').show();
        $('#oliTWXTitleAprove').hide();
        $('#oliTWXTitleConimg').hide();
        // Hide And Disabled
        $("#obtTWXCallPageAdd").hide();
        $("#obtTWXCancelDoc").hide(); // attr("disabled",true);
        $("#obtTWXApproveDoc").hide(); // attr("disabled",true);
        $("#obtTWXBrowseCustomer").attr("disabled", true);
        $(".xWConditionSearchPdt").attr("disabled", true);

        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetTWXFrmSearchPdtHTML").attr("disabled", false);
        $('#odvTWXBtnGrpSave').hide();
        $("#oliTWXEditShipAddress").hide();
        $("#oliTWXEditTexAddress").hide();
        $("#oliTWXTitleDetail").show();

        $("#ocbTWXFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtTWXFrmBrowseShipAdd").attr("disabled", true);
        $("#obtTWXFrmBrowseTaxAdd").attr("disabled", true);
        // อินพุต
        $('.xControlForm').attr('readonly', true);
        $('.xWTWXDisabledOnApv').attr('disabled', true);
        $('.xControlRmk').attr('readonly', true);
        $("#obtTWXFrmBrowseTaxAdd").hide();


    }

    // Status Appove Success
    if (nTWXStaDoc == 1 && nTWXStaApv == 1) { // Hide/Show Menu Title
        $("#oliTWXTitleAdd").hide();
        $('#oliTWXTitleEdit').hide();
        $('#oliTWXTitleDetail').show();
        $('#oliTWXTitleAprove').hide();
        $('#oliTWXTitleConimg').hide();
        // Hide And Disabled
        $("#obtTWXCallPageAdd").hide();
        $("#obtTWXCancelDoc").hide(); // attr("disabled",true);
        $("#obtTWXApproveDoc").hide(); // attr("disabled",true);
        $("#obtTWXBrowseCustomer").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetTWXFrmSearchPdtHTML").attr("disabled", false);
        $('#odvTWXBtnGrpSave').show();
        $("#oliTWXEditShipAddress").hide();
        $("#oliTWXEditTexAddress").hide();
        $("#oliTWXTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWTWXDisabledOnApv').attr('disabled', true);
        $("#ocbTWXFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtTWXFrmBrowseShipAdd").attr("disabled", true);
        $("#obtTWXFrmBrowseTaxAdd").attr("disabled", true);
    }
}