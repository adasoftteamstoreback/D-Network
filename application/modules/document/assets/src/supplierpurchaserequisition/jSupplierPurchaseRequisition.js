var nPRSStaBrowseType   = $("#oetPRSStaBrowse").val();
var tPRSCallBackOption  = $("#oetPRSCallBackOption").val();
var tPRSSesSessionID    = $("#ohdSesSessionID").val();
var tPRSSesSessionName  = $("#ohdSesSessionName").val();

$("document").ready(function() {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose();

    if (typeof(nPRSStaBrowseType) != 'undefined' && (nPRSStaBrowseType == 0 || nPRSStaBrowseType == 2 )) { // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliPrsTitle').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPRSCallPageList();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtPRSCallBackPage').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {

                //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
                if(localStorage.tCheckBackStage == 'PageMangeDocOrderBCH' || localStorage.tCheckBackStage == 'PageMangeDocOrderBCHHQ'){
                    JSxBackStageToMangeDocOrderBCH();
                }else{ //กลับสู่หน้า List
                    JSvPRSCallPageList();
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // หน้าจอเพิ่มข้อมูล
        $('#obtPRSCallPageAdd').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPRSCallPageAddDoc();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // ยกเลิกเอกสาร
        $('#obtPRSCancelDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSnPRSCancelDocument(false);
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // อนุมัติเอกสาร
        $('#obtPRSApproveDoc').unbind().click(function() {
            var tFrmSplName         = $('#oetPRSFrmSplName').val();
            var tPRSFrmWahName      = $('#oetPRSFrmWahName').val();
            var tPRSFrmWahName      = $('#oetPRSToBchName').val();
            var tCheckIteminTable   = $('#otbPRSDocPdtAdvTableList .xWPdtItem').length;
            var nPOStaValidate      = $('.xPOStaValidate0').length;
            if (tCheckIteminTable > 0) {
                if (nPOStaValidate == 0) {
                    if (tFrmSplName == '') {
                        $('#odvPRSModalPleseselectSPL').modal('show');
                        //เช็คค่าว่างคลังสินค้า
                    } else if (tPRSFrmWahName == '') {
                        $('#odvPRSModalWahNoFound').modal('show');
                    } else {
                        JSxPRSSetStatusClickSubmit(2);
                        JSxPRSSubmitEventByButton('approve');
                    }
                } else {
                    $('#odvPRSModalImpackImportExcel').modal('show')
                }
            } else {
                FSvCMNSetMsgWarningDialog($('#ohdPRSValidatePdt').val());
            }
        });

        // อนุมัติเอกสารขอซื้อ (สำนักงานใหญ่)
        $('#obtPRSApproveDocHQ').unbind().click(function() {
            var tCheckIteminTable   = $('#otbPRSDocPdtAdvTableList .xWPdtItem').length;
            if (tCheckIteminTable > 0) {
                JSxPRSSubmitEventByButton('approve');
            } else {
                FSvCMNSetMsgWarningDialog($('#ohdPRSValidatePdt').val());
            }
        });

        // บันทึกข้อมูล
        $('#obtPRSSubmitFromDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tFrmSplName         = $('#oetPRSFrmSplName').val();
                var tCheckIteminTable   = $('#otbPRSDocPdtAdvTableList .xWPdtItem').length;
                if (tCheckIteminTable > 0) {
                    //เช็คผู้จำหน่าย
                    if (tFrmSplName == '') {
                        $('#odvPRSModalPleseselectSPL').modal('show');
                    } else {
                        JSxPRSSetStatusClickSubmit(1);
                        $('#obtPRSSubmitDocument').click();
                    }
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdPRSValidatePdt').val());
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        switch(nPRSStaBrowseType){
            case '2':
                JSxPRSNavDefult('showpage_edit');
                var tAgnCode    = $('#oetPRSJumpAgnCode').val();
                var tBchCode    = $('#oetPRSJumpBchCode').val();
                var tDocNo      = $('#oetPRSJumpDocNo').val();
                JSvPRSCallPageEdit(tDocNo);
            break;
            default:
                JSxPRSNavDefult('showpage_list');
                JSvPRSCallPageList();
        }

    } else {
        JSxPRSNavDefult('showpage_list');
        JSvPRSCallPageAddDoc();
    }
});

//กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
function JSxBackStageToMangeDocOrderBCH(){
    if(localStorage.tCheckBackStage == 'PageMangeDocOrderBCH'){
        var tRoute = 'docMngDocPreOrdB/0/0';
    }else{
        var tRoute = 'docMngDocPreOrdB/0/2';
    }
    
    $.ajax({
        type    : "GET",
        url     : tRoute,
        cache   : false,
        timeout : 5000,
        success : function (tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);

            //เก็บเอาไว้ว่า มาจากหน้าจอจัดการใบสั่งสินค้าจากสาขา
            localStorage.tCheckBackStage = '';
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// อนุมัติเอกสาร , อนุมัติเอกสาร (สำนักงานใหญ่)
function JSxPRSApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                $("#odvPRSModalAppoveDoc").modal('hide');

                var tDocNo      = $('#oetPRSDocNo').val();
                var tBchCode    = $('#ohdPRSBchCode').val();

                $.ajax({
                    type    : "POST",
                    url     : "docPRSApproveDocument",
                    data    : {
                        'tDocNo'            : tDocNo,
                        'tBchCode'          : tBchCode,
                        'tAGNCode'          : $('#oetPRSAgnCode').val(),
                        'tPRSTypeDocument'  : $('#ohdPRSTypeDocument').val()
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        $("#odvPRSModalAppoveDoc").modal("hide");
                        $('.modal-backdrop').remove();
                        var aReturnData = JSON.parse(tResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            JSvPRSCallPageEdit(tDocNo);
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
                $("#odvPRSModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxPRSApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Control เมนู
function JSxPRSNavDefult(ptType) {
    if (ptType == 'showpage_list') { // แสดง
        $("#oliPrsTitle").show();
        $("#odvPRSBtnGrpInfo").show();
        $("#obtPRSCallPageAdd").show();

        // ซ่อน
        $("#oliPrsTitleAdd").hide();
        $("#oliPrsTitleEdit").hide();
        $("#oliPrsTitleDetail").hide();
        $("#oliPrsTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtPRSCallBackPage").hide();
        $("#obtPRSPrintDoc").hide();
        $("#obtPRSCancelDoc").hide();
        $("#obtPRSApproveDoc").hide();
        $('#obtPRSApproveDocHQ').hide();
        $("#odvPRSBtnGrpSave").hide();

    } else if (ptType == 'showpage_add') { // แสดง
        $("#oliPrsTitle").show();
        $("#odvPRSBtnGrpSave").show();
        $("#obtPRSCallBackPage").show();
        $("#oliPrsTitleAdd").show();

        // ซ่อน
        $("#oliPrsTitleEdit").hide();
        $("#oliPrsTitleDetail").hide();
        $("#oliPrsTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtPRSPrintDoc").hide();
        $("#obtPRSCancelDoc").hide();
        $("#obtPRSApproveDoc").hide();
        $('#obtPRSApproveDocHQ').hide();
        $("#odvPRSBtnGrpInfo").hide();
    } else if (ptType == 'showpage_edit') { // แสดง
        $("#oliPrsTitle").show();
        $("#odvPRSBtnGrpSave").show();
        $("#obtPRSApproveDoc").show();
        $("#obtPRSCancelDoc").show();
        $("#obtPRSCallBackPage").show();
        $("#oliPrsTitleEdit").show();
        $("#obtPRSPrintDoc").show();

        // ซ่อน
        $("#oliPrsTitleAdd").hide();
        $("#oliPrsTitleDetail").hide();
        $("#oliPrsTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#odvPRSBtnGrpInfo").hide();
    }

    // ล้างค่า
    localStorage.removeItem('IV_LocalItemDataDelDtTemp');
    localStorage.removeItem('LocalItemData');
}

// Call Page List [TAB : ใบขอซื้อผู้จำหน่าย และ ใบขอซื้อจากแฟรนไชส์]
function JSvPRSCallPageList() {
    $.ajax({
        type    : "POST",
        url     : "dcmPrsFormSearchList",
        cache   : false,
        data    : { 'tPRSTypeDocument'  : $('#ohdPRSTypeDocument').val() },
        timeout : 0,
        success : function(tResult) {
            $("#odvPRSContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSxPRSNavDefult('showpage_list');

            if($('#ohdPRSTypeDocument').val() == 1){ //ใบขอซื้อแบบสำนักงานใหญ่
                JSvPRSCallPageDataTable();
            }else{ //ใบขอซื้อแบบแฟรนไชส์
                JSvPRSCallPageDataTable_FN();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Call Page DataTable [TAB : ใบขอซื้อจากแฟรนไชส์]
function JSvPRSCallPageDataTable_FN(pnPage){
    JCNxOpenLoading();
    var oAdvanceSearch  = JSoPrsGetAdvanceSearchData();
    var nPageCurrent    = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent    = "1";
    }
    $.ajax({
        type    : "POST",
        url     : "docPrsDataTable_FN",
        data    : {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostPRSDataTableDocument').html(aReturnData['tPRSViewDataTableList']);
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

// Call Page DataTable [TAB : ใบขอซื้อผู้จำหน่าย]
function JSvPRSCallPageDataTable(pnPage) {
    JCNxOpenLoading();
    var oAdvanceSearch = JSoPrsGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docPrsDataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostPRSDataTableDocument').html(aReturnData['tPRSViewDataTableList']);
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
function JSoPrsGetAdvanceSearchData() {
    var oAdvanceSearchData = {
        tSearchAll          : $("#oetPRSSearchAllDocument").val(),
        tSearchBchCodeFrom  : $("#oetPRSAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo    : $("#oetPRSAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom  : $("#oetPRSAdvSearcDocDateFrom").val(),
        tSearchDocDateTo    : $("#oetPRSAdvSearcDocDateTo").val(),
        tSearchStaDoc       : $("#ocmPRSAdvSearchStaDoc").val(),
        tSearchStaDocAct    : $("#ocmStaDocAct").val(),
        tSearchStaPrcDoc    : $("#ocmPRSAdvSearchStaPrcDoc").val()
    };
    return oAdvanceSearchData;
}

// เข้ามาแบบ เพิ่ม
function JSvPRSCallPageAddDoc() {
    JCNxOpenLoading();

    $.ajax({
        type    : "POST",
        url     : "docPRSPageAdd",
        data    : {
            'tPRSTypeDocument'  : $('#ohdPRSTypeDocument').val()
        },
        cache   : false,
        timeout : 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxPRSNavDefult('showpage_add');
                $('#odvPRSContentPageDocument').html(aReturnData['tPRSViewPageAdd']);
                JSvPRSLoadPdtDataTableHtml();
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
function JSvPRSCallPageEdit(ptDocumentNumber) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docPRSPageEdit",
            data    : {
                'ptPRSDocNo'        : ptDocumentNumber,
                'tPRSTypeDocument'  : $('#ohdPRSTypeDocument').val()
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSxPRSNavDefult('showpage_edit');

                    $('#odvPRSContentPageDocument').html(aReturnData['tViewPageEdit']);
                    JSvPRSLoadPdtDataTableHtml();
                    JCNxCloseLoading();

                    // เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxPRSControlFormWhenCancelOrApprove();
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
function JSxPRSControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdPRSStaDoc').val();
    var tStatusApv = $('#ohdPRSStaApv').val();

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
        $('#obtPRSCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtPRSApproveDoc').hide();
        $('#obtPRSApproveDocHQ').hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').hide();

        JCNxPRSControlObjAndBtn();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // เอกสารอนุมัติแล้ว
        // ปุ่มยกเลิก
        $('#obtPRSCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtPRSApproveDoc').hide();
        $('#obtPRSApproveDocHQ').hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxPRSControlObjAndBtn();
    }
}

// โหลดสินค้าใน Temp
function JSvPRSLoadPdtDataTableHtml(pnPage) {
    if ($("#ohdPRSRoute").val() == "docPRSEventAdd") {
        var tPRSDocNo = "";
    } else {
        var tPRSDocNo = $("#oetPRSDocNo").val();
    }
    var tPRSStaApv = $("#ohdPRSStaApv").val();
    var tPRSStaDoc = $("#ohdPRSStaDoc").val();
    var tPRSVATInOrEx = $("#ocmPRSFrmSplInfoVatInOrEx").val();

    // เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
    if ($("#otbPRSDocPdtAdvTableList .xWPdtItem").length == 0) {
        if (pnPage != undefined) {
            pnPage = pnPage - 1;
        }
    }

    if (pnPage == '' || pnPage == null) {
        var pnNewPage = 1;
    } else {
        var pnNewPage = pnPage;
    }
    var nPageCurrent        = pnNewPage;
    var tSearchPdtAdvTable  = $('#oetPRSFrmFilterPdtHTML').val();

    //ถ้าอนุมัติเเล้ว
    if (tPRSStaApv == 2) {
        $('#obtPRSDocBrowsePdt').hide();
        $('#obtPRSPrintDoc').hide();
        $('#obtPRSCancelDoc').hide();
        $('#obtPRSApproveDoc').hide();
        $('#obtPRSApproveDocHQ').hide();
        $('#odvPRSBtnGrpSave').hide();
    }

    $.ajax({
        type: "POST",
        url: "docPRSPdtAdvanceTableLoadData",
        data: {
            'tSelectBCH'            : $('#oetPRSFrmBchCode').val(),
            'ptSearchPdtAdvTable'   : tSearchPdtAdvTable,
            'ptPRSDocNo'            : tPRSDocNo,
            'ptPRSStaApv'           : tPRSStaApv,
            'ptPRSStaDoc'           : tPRSStaDoc,
            'ptPRSVATInOrEx'        : tPRSVATInOrEx,
            'pnPRSPageCurrent'      : nPageCurrent,
            'tPRSTypeDocument'      : $('#ohdPRSTypeDocument').val()
        },
        cache: false,
        Timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['checksession'] == 'expire') {
                JCNxShowMsgSessionExpired();
            } else {
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvPRSDataPanelDetailPDT #odvPRSDataPdtTableDTTemp').html(aReturnData['tPRSPdtAdvTableHtml']);
                    if ($('#ohdPRSStaImport').val() == 1) {
                        $('.xPRSImportDT').show();
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
            JSvPRSLoadPdtDataTableHtml(pnPage)
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Add Product Into Table Document DT Temp
function JCNvPRSBrowsePdt() {
    var tPRSSplCode = $('#oetPRSFrmSplCode').val();

    if (typeof(tPRSSplCode) !== undefined && tPRSSplCode !== '') {
        var aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                Qualitysearch   : [],
                PriceType       : [
                    "Cost", "tCN_Cost", "Company", "1"
                ],
                SelectTier      : ["Barcode"],
                ShowCountRecord : 10,
                NextFunc        : "FSvPRSNextFuncB4SelPDT",
                ReturnType      : "M",
                SPL             : [$("#oetPRSFrmSplCode").val(),$("#oetPRSFrmSplCode").val()],
                'aAlwPdtType'   : ['T1','T3','T4','T5','T6','S2','S3','S4']
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

function JSvPrsDOCFilterPdtInTableTemp() {
    JCNxOpenLoading();
    JSvPOLoadPdtDataTableHtml();
}

// Function Chack Value LocalStorage
function JStPRSFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

function JSxPRSSetStatusClickSubmit(pnStatus) {
    $("#ohdPRSCheckSubmitByButton").val(pnStatus);
}

// Add/Edit Document
function JSxPRSAddEditDocument() { 
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxPRSValidateFormDocument();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Event Single Delete Document Single
function JSoPRSDelDocSingle(ptCurrentPage, ptPRSDocNo, tBchCode, ptPRSRefInCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptPRSDocNo) != undefined && ptPRSDocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptPRSDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvPRSModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvPRSModalDelDocSingle').modal('show');
            $('#odvPRSModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docPRSEventDelete",
                    data: {
                        'tDataDocNo': ptPRSDocNo,
                        'tBchCode': tBchCode,
                        'tPRSRefInCode': ptPRSRefInCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvPRSModalDelDocSingle').modal('hide');
                            $('#odvPRSModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                                JSvPRSCallPageDataTable(ptCurrentPage);
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

// Event Single Delete Doc Mutiple
function JSoPRSDelDocMultiple() {
    var aDataDelMultiple = $('#odvPRSModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
            var tPRSRefInCode = $(this).data('refcode');
            localStorage.StaDeleteArray = '1';
            $.ajax({
                type: "POST",
                url: "docPRSEventDelete",
                data: {
                    'tDataDocNo': tDataDocNo,
                    'tBchCode': tBchCode,
                    'tPRSRefInCode': tPRSRefInCode
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvPRSModalDelDocMultiple').modal('hide');
                            $('#odvPRSModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvPRSModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                            JSvPRSCallPageList();
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

// Function Chack And Show Button Delete All
function JSxPRSShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliPRSBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliPRSBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliPRSBtnDeleteAll").addClass("disabled");
        }
    }
}

// Function Chack Value LocalStorage
function JStPRSFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// Cancel Document 
function JSnPRSCancelDocument(pbIsConfirm) {
    var tPRSDocNo = $("#oetPRSDocNo").val();
    var tRefInDocNo = $('#oetPRSRefDocIntName').val();
    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docPRSCancelDocument",
            data: {
                'ptPRSDocNo': tPRSDocNo,
                'ptRefInDocNo': tRefInDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvPRSPopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSvPRSCallPageEdit(tPRSDocNo);
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
        $('#odvPRSPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvPRSPopupCancel").modal("show");
    }
}

// Function Control Object Button
function JCNxPRSControlObjAndBtn() { // Check สถานะอนุมัติ
    var nPRSStaDoc = $("#ohdPRSStaDoc").val();
    var nPRSStaApv = $("#ohdPRSStaApv").val();

    // Status Cancel
    if (nPRSStaDoc == 3) {
        $("#oliPrsTitleAdd").hide();
        $('#oliPrsTitleEdit').hide();
        $('#oliPrsTitleDetail').show();
        $('#oliPrsTitleAprove').hide();
        $('#oliPrsTitleConimg').hide();
        $("#obtPRSCallPageAdd").hide();
        $("#obtPRSCancelDoc").hide(); 
        $("#obtPRSApproveDoc").hide(); 
        $('#obtPRSApproveDocHQ').hide();
        $("#obtPRSBrowseSupplier").attr("disabled", true);
        $(".xWConditionSearchPdt").attr("disabled", true);

        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetPRSFrmSearchPdtHTML").attr("disabled", false);
        $('#odvPRSBtnGrpSave').hide();
        $("#oliPRSEditShipAddress").hide();
        $("#oliPRSEditTexAddress").hide();
        $("#oliPrsTitleDetail").show();

        $("#ocbPRSFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtPRSFrmBrowseShipAdd").attr("disabled", true);
        $("#obtPRSFrmBrowseTaxAdd").attr("disabled", true);
        // อินพุต
        $('.xControlForm').attr('readonly', true);
        $('.xWPRSDisabledOnApv').attr('disabled', true);
        $('.xControlRmk').attr('readonly', true);
        $("#obtPRSFrmBrowseTaxAdd").hide();
    }

    // Status Appove Success
    if (nPRSStaDoc == 1 && nPRSStaApv == 1) { // Hide/Show Menu Title
        $("#oliPrsTitleAdd").hide();
        $('#oliPrsTitleEdit').hide();
        $('#oliPrsTitleDetail').show();
        $('#oliPrsTitleAprove').hide();
        $('#oliPrsTitleConimg').hide();
        $("#obtPRSCallPageAdd").hide();
        $("#obtPRSCancelDoc").hide();
        $("#obtPRSApproveDoc").hide(); 
        $('#obtPRSApproveDocHQ').hide();
        $("#obtPRSBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetPRSFrmSearchPdtHTML").attr("disabled", false);
        $('#odvPRSBtnGrpSave').show();
        $("#oliPRSEditShipAddress").hide();
        $("#oliPRSEditTexAddress").hide();
        $("#oliPrsTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWPRSDisabledOnApv').attr('disabled', true);
        $("#ocbPRSFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtPRSFrmBrowseShipAdd").attr("disabled", true);
        $("#obtPRSFrmBrowseTaxAdd").attr("disabled", true);
    }
}