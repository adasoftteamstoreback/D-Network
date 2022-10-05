<script>
var nPRBStaDOBrowseType = $("#oetPRBStaBrowse").val();
var tPRBCallDOBackOption = $("#oetPRBCallBackOption").val();
var tPRBSesSessionID = $("#ohdSesSessionID").val();
var tPRBSesSessionName = $("#ohdSesSessionName").val();

$("document").ready(function() {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    if (typeof(nPRBStaDOBrowseType) != 'undefined' && (nPRBStaDOBrowseType == 0 || nPRBStaDOBrowseType==2 )) { // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliPRBTitle').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPRBCallPageList();

            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtPRBCallBackPage').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPRBCallPageList();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Add Page
        $('#obtPRBCallPageAdd').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPRBCallPageAddDoc();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Cancel Document
        $('#obtPRBCancelDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSnPRBCancelDocument(false);
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Appove Document
        $('#obtPRBApproveDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tCheckIteminTable = $('#otbPRBDocPdtAdvTableList .xWPdtItem').length;
                if (tCheckIteminTable > 0) {
                    JSxPRBSetStatusClickSubmit(2);
                    JSxPRBApproveDocument(false);
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdPRBValidatePdt').val());
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Submit From Document
        $('#obtPRBSubmitFromDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {

                var tPRBFrmBchCode     = $('#oetPRBFrmBchCode').val();
                var tPRBAgnCode        = $('#oetPRBAgnCode').val();
                var tPRBFrmBchCodeTo     = $('#oetPRBFrmBchCodeTo').val();
                var tPRBFrmBchCodeShip = $('#oetPRBFrmBchCodeShip').val();
                var tPRBFrmWahCodeTo   = $('#oetPRBFrmWahCodeTo').val();
                var tPRBFrmWahCodeShip = $('#oetPRBFrmWahCodeShip').val();
                var tPRBReasonCode     = $('#oetPRBReasonCode').val();

                var tCheckIteminTable = $('#otbPRBDocPdtAdvTableList .xWPdtItem').length;
                var tChheckItemFlag = '0';

                $(".xCNPdtEditInLine").each(function (indexInArray, valueOfElement) { 
                    var nItemValue = $(this).val();
                     if(nItemValue <= 0){
                        tChheckItemFlag = '1';
                        return;
                     }
                });

                if (tCheckIteminTable > 0) {
                    if(tChheckItemFlag=='1'){
                        $('#odvPRBModalConfirmDel').modal('show');
                        $('#odvPRBModalConfirmDel #osmConfirmDelSingle').unbind().click(function() {
                            JCNxOpenLoading();
                            $(".xCNPdtEditInLine").each(function (indexInArray, valueOfElement) { 
                            var nItemValue = $(this).val();
                            if(nItemValue <= 0){
                                var tPdtCode = $(this).parents("tr.xWPdtItem").attr("data-pdtcode");
                                var tSeqno   = $(this).parents("tr.xWPdtItem").attr("data-key");
                                $(this).parents("tr.xWPdtItem").remove();
                                JSnPRBRemovePdtDTTempSingle(tSeqno, tPdtCode);
                            }
                            });
                            $('#odvPRBModalConfirmDel').modal('hide');
                            $('.modal-backdrop').remove();
                        });
                        return;
                    }
                    if(tPRBFrmBchCode==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectBchFrom'); ?>');
                        return;
                    }
                    if(tPRBFrmBchCodeTo==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectBchTo'); ?>');
                        return;
                    }
                    if(tPRBFrmWahCodeTo==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectWahTo'); ?>');
                        return;
                    }
                    if(tPRBFrmBchCodeShip==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectBchShip'); ?>');
                        return;
                    }
                    if(tPRBFrmWahCodeShip==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectWahShip'); ?>');
                        return;
                    }
                    if(tPRBReasonCode==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectReason'); ?>');
                        return;
                    }
                    JSxPRBSetStatusClickSubmit(1);
                    $('#obtPRBSubmitDocument').click();
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdPRBValidatePdt').val());
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        switch(nPRBStaDOBrowseType){
            case '2':
                JSxPRBNavDefult('showpage_edit');
                var tAgnCode = $('#oetPRBJumpAgnCode').val();
                var tBchCode = $('#oetPRBJumpBchCode').val();
                var tDocNo = $('#oetPRBJumpDocNo').val();
                JSvPRBCallPageEdit(tBchCode,tAgnCode,tDocNo);
            break;
            default:
                JSxPRBNavDefult('showpage_list');
                JSvPRBCallPageList();
        }



    } else {
        JSxPRBNavDefult('showpage_list');
        JSvPRBCallPageAddDoc();

    }
});

// อนุมัติเอกสาร
function JSxPRBApproveDocument(pbIsConfirm) {
    var nStaSession = 1;
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                $("#odvPRBModalAppoveDoc").modal('hide');

                var tPRBDocNo = $('#oetPRBDocNo').val();
                var tAgnCode = $('#oetPRBAgnCode').val();
                var tBchCode = $('#oetPRBFrmBchCode').val();
                var tRefInPRBcNo = $('#oetPRBRefDocIntName').val();

                var tPRBFrmBchCode     = $('#oetPRBFrmBchCode').val();
                var tPRBAgnCode        = $('#oetPRBAgnCode').val();
                var tPRBFrmBchCodeTo     = $('#oetPRBFrmBchCodeTo').val();
                var tPRBFrmBchCodeShip = $('#oetPRBFrmBchCodeShip').val();
                var tPRBFrmWahCodeTo   = $('#oetPRBFrmWahCodeTo').val();
                var tPRBFrmWahCodeShip = $('#oetPRBFrmWahCodeShip').val();
                var tPRBReasonCode     = $('#oetPRBReasonCode').val();

                var tCheckIteminTable = $('#otbPRBDocPdtAdvTableList .xWPdtItem').length;
                var tChheckItemFlag = '0';

                $(".xCNPdtEditInLine").each(function (indexInArray, valueOfElement) { 
                    var nItemValue = $(this).val();
                     if(nItemValue <= 0){
                        tChheckItemFlag = '1';
                        return;
                     }
                });

                if (tCheckIteminTable > 0) {
                    if(tChheckItemFlag=='1'){
                        $('#odvPRBModalConfirmDel').modal('show');
                        $('#odvPRBModalConfirmDel #osmConfirmDelSingle').unbind().click(function() {
                            JCNxOpenLoading();
                            $(".xCNPdtEditInLine").each(function (indexInArray, valueOfElement) { 
                            var nItemValue = $(this).val();
                            if(nItemValue <= 0){
                                var tPdtCode = $(this).parents("tr.xWPdtItem").attr("data-pdtcode");
                                var tSeqno   = $(this).parents("tr.xWPdtItem").attr("data-key");
                                $(this).parents("tr.xWPdtItem").remove();
                                JSnPRBRemovePdtDTTempSingle(tSeqno, tPdtCode);
                            }
                            });
                            $('#odvPRBModalConfirmDel').modal('hide');
                            $('.modal-backdrop').remove();
                        });
                        return;
                    }
                    if(tPRBFrmBchCode==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectBchFrom'); ?>');
                        return;
                    }
                    if(tPRBFrmBchCodeTo==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectBchTo'); ?>');
                        return;
                    }
                    if(tPRBFrmWahCodeTo==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectWahTo'); ?>');
                        return;
                    }
                    if(tPRBFrmBchCodeShip==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectBchShip'); ?>');
                        return;
                    }
                    if(tPRBFrmWahCodeShip==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectWahShip'); ?>');
                        return;
                    }
                    if(tPRBReasonCode==''){
                        FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectReason'); ?>');
                        return;
                    }
                    JSxPRBSubmitEventByButton('approve');
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdPRBValidatePdt').val());
                }
                // $.ajax({
                //     type: "POST",
                //     url: "docPRBApproveDocument",
                //     data: {
                //         tPRBDocNo: tPRBDocNo,
                //         tAgnCode : tAgnCode,
                //         tBchCode: tBchCode,
                //         tRefInPRBcNo: tRefInPRBcNo
                //     },
                //     cache: false,
                //     timeout: 0,
                //     success: function(tResult) {
                //         $("#odvPRBModalAppoveDoc").modal("hide");
                //         $('.modal-backdrop').remove();
                //         var aReturnData = JSON.parse(tResult);
                //         if (aReturnData.nStaEvent == "1") {
                //             //  FSvCMNSetMsgSucessDialog(aReturnData.tStaMessg);
                //             JSvPRBCallPageEdit(tBchCode,tAgnCode,tPRBDocNo);
                //                 }else{
                //             FSvCMNSetMsgWarningDialog(aReturnData.tStaMessg);
                //             }
                //     },
                //     error: function(jqXHR, textStatus, errorThrown) {
                //         JCNxResponseError(jqXHR, textStatus, errorThrown);
                //     }
                // });
            } else {
                $("#odvPRBModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxPRBApproveDocument Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Rabbit MQ
function JSoPRBCallSubscribeMQ() {
    // RabbitMQ

    // Document variable
    var tLangCode = $("#ohdPRBLangEdit").val();
    var tUsrBchCode = $("#oetPRBFrmBchCode").val();
    var tUsrApv = $("#ohdSesSessionName").val();
    var tPRBcNo = $("#oetPRBDocNo").val();
    var tPrefix = "RESDO";
    var tStaApv = $("#ohdPRBStaApv").val();
    var tStaDelMQ = 1;
    var tQName = tPrefix + "_" + tPRBcNo + "_" + tUsrApv;

    // MQ Message Config
    var poDocConfig = {
        tLangCode: tLangCode,
        tUsrBchCode: tUsrBchCode,
        tUsrApv: tUsrApv,
        tPRBcNo: tPRBcNo,
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
        ptPRBcTableName: "TCNTPdtReqHqHD",
        ptPRBcFieldDocNo: "FTXphDocNo",
        ptPRBcFieldStaApv: "FTXphStaPrcStk",
        ptPRBcFieldStaDelMQ: "FTXphStaDelMQ",
        ptPRBcStaDelMQ: tStaDelMQ,
        ptPRBcNo: tPRBcNo
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit: "JSvPRBCallPageEdit",
        tCallPageList: "JSvPRBCallPageList"
    };

    // Check Show Progress %
    FSxCMNRabbitMQMessage(poDocConfig, poMqConfig, poUpdateStaDelQnameParams, poCallback);
}

// Control เมนู
function JSxPRBNavDefult(ptType) {
    if (ptType == 'showpage_list') { // แสดง
        $("#oliPRBTitle").show();
        $("#odvPRBBtnGrpInfo").show();
        $("#obtPRBCallPageAdd").show();

        // ซ่อน
        $("#oliPRBTitleAdd").hide();
        $("#oliPRBTitleEdit").hide();
        $("#oliPRBTitleDetail").hide();
        $("#oliPRBTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtPRBCallBackPage").hide();
        $("#obtPRBPrintDoc").hide();
        $("#obtPRBCancelDoc").hide();
        $("#obtPRBApproveDoc").hide();
        $("#odvPRBBtnGrpSave").hide();

    } else if (ptType == 'showpage_add') { // แสดง
        $("#oliPRBTitle").show();
        $("#odvPRBBtnGrpSave").show();
        $("#obtPRBCallBackPage").show();
        $("#oliPRBTitleAdd").show();

        // ซ่อน
        $("#oliPRBTitleEdit").hide();
        $("#oliPRBTitleDetail").hide();
        $("#oliPRBTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtPRBPrintDoc").hide();
        $("#obtPRBCancelDoc").hide();
        $("#obtPRBApproveDoc").hide();
        $("#odvPRBBtnGrpInfo").hide();
    } else if (ptType == 'showpage_edit') { // แสดง
        $("#oliPRBTitle").show();
        $("#odvPRBBtnGrpSave").show();
        $("#obtPRBApproveDoc").show();
        $("#obtPRBCancelDoc").show();
        $("#obtPRBCallBackPage").show();
        $("#oliPRBTitleEdit").show();
        $("#obtPRBPrintDoc").show();

        // ซ่อน
        $("#oliPRBTitleAdd").hide();
        $("#oliPRBTitleDetail").hide();
        $("#oliPRBTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#odvPRBBtnGrpInfo").hide();
    }

    // ล้างค่า
    localStorage.removeItem('IV_LocalItemDataDelDtTemp');
    localStorage.removeItem('LocalItemData');
}

// Function: Call Page List
function JSvPRBCallPageList() {
    $.ajax({
        type: "GET",
        url: "docPRBFormSearchList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvPRBContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSxPRBNavDefult('showpage_list');
            JSvPRBCallPageDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function: Call Page DataTable
function JSvPRBCallPageDataTable(pnPage) {
    JCNxOpenLoading();
    var oAdvanceSearch = JSoPRBGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docPRBDataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostPRBDataTableDocument').html(aReturnData['tPRBViewDataTableList']);
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
function JSoPRBGetAdvanceSearchData() {
    var oAdvanceSearchData = {
        tSearchAll: $("#oetPRBSearchAllDocument").val(),
        tSearchBchCodeFrom: $("#oetPRBAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo: $("#oetPRBAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom: $("#oetPRBAdvSearcDocDateFrom").val(),
        tSearchDocDateTo: $("#oetPRBAdvSearcDocDateTo").val(),
        tSearchStaDoc: $("#ocmPRBAdvSearchStaDoc").val(),
        tSearchStaDocAct: $("#ocmStaDocAct").val()
    };
    return oAdvanceSearchData;
}

// เข้ามาแบบ insert
function JSvPRBCallPageAddDoc() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docPRBPageAdd",
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxPRBNavDefult('showpage_add');
                $('#odvPRBContentPageDocument').html(aReturnData['tPRBViewPageAdd']);
                JSvPRBLoadPdtDataTableHtml();
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
function JSvPRBCallPageEdit(ptBchCode,ptAgnCode,ptPRBcumentNumber) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPRBPageEdit",
            data: {
                'ptBchCode' : ptBchCode,
                'ptAgnCode' : ptAgnCode,
                'ptPRBDocNo': ptPRBcumentNumber
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                // console.log(aReturnData);
                if (aReturnData['nStaEvent'] == '1') {
                    JSxPRBNavDefult('showpage_edit');

                    $('#odvPRBContentPageDocument').html(aReturnData['tViewPageEdit']);
                    JSvPRBLoadPdtDataTableHtml();
                    JCNxCloseLoading();

                    // เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxPRBControlFormWhenCancelOrApprove();
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
function JSxPRBControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdPRBStaDoc').val();
    var tStatusApv = $('#ohdPRBStaApv').val();

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
        $('#obtPRBCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtPRBApproveDoc').hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').hide();

        JCNxPRBControlObjAndBtn();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // เอกสารอนุมัติแล้ว
        // ปุ่มยกเลิก
        $('#obtPRBCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtPRBApproveDoc').hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxPRBControlObjAndBtn();
    }
}

// Function : Call Page Product Table In Add Document
function JSvPRBLoadPdtDataTableHtml(pnPage) {
    if ($("#ohdPRBRoute").val() == "docPRBEventAdd") {
        var tPRBDocNo = "";
    } else {
        var tPRBDocNo = $("#oetPRBDocNo").val();
    }
    var tPRBStaApv = $("#ohdPRBStaApv").val();
    var tPRBStaDoc = $("#ohdPRBStaDoc").val();
    var tPRBVATInOrEx = $("#ohdPRBFrmSplInfoVatInOrEx").val();

    // เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
    if ($("#otbPRBDocPdtAdvTableList .xWPdtItem").length == 0) {
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
    var tSearchPdtAdvTable = $('#oetPRBFrmFilterPdtHTML').val();

    if (tPRBStaApv == 2) {
        $('#obtPRBDocBrowsePdt').hide();
        $('#obtPRBPrintPRBc').hide();
        $('#obtPRBCancelDoc').hide();
        $('#obtPRBApproveDoc').hide();
        $('#odvPRBBtnGrpSave').hide();
    }
    $.ajax({
        type: "POST",
        url: "docPRBPdtAdvanceTableLoadData",
        data: {
            'tSelectBCH': $('#oetPRBFrmBchCode').val(),
            'ptSearchPdtAdvTable': tSearchPdtAdvTable,
            'ptPRBDocNo': tPRBDocNo,
            'ptPRBStaApv': tPRBStaApv,
            'ptPRBStaDoc': tPRBStaDoc,
            'ptPRBVATInOrEx': tPRBVATInOrEx,
            'pnPRBPageCurrent': nPageCurrent
        },
        cache: false,
        Timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['checksession'] == 'expire') {
                JCNxShowMsgSessionExpired();
            } else {
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvPRBDataPanelDetailPDT #odvPRBDataPdtTableDTTemp').html(aReturnData['tPRBPdtAdvTableHtml']);
                    JSxPRBMergeTable();
                    if ($('#ohdPRBStaImport').val() == 1) {
                        $('.xPRBImportDT').show();
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
            JSvPRBLoadPdtDataTableHtml(pnPage)
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Add Product Into Table Document DT Temp
function JCNvPRBBrowsePdt() {
    var tPRBSplCode = $('#oetPRBFrmSplCode').val();

    aWhereCondition = [];
    // var tText1 = " AND PDTSPL.FTSplStaAlwPO = 1 ";
    // aWhereCondition.push(tText1);

    var tText2 = " AND PPCZ.FTPdtStaAlwPoHQ = 1 ";
    aWhereCondition.push(tText2);

    var tText3 = " AND Products.FTPdtStkControl = 1 ";
    aWhereCondition.push(tText3);

    if (typeof(tPRBSplCode) !== undefined && tPRBSplCode !== '') {
        var aMulti = [];
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
                Where : aWhereCondition,
                NextFunc: "FSvPRBNextFuncB4SelPDT",
                ReturnType: "M",
                'aAlwPdtType' : ['T1','T3','T4','T5','T6','S2','S3','S4']
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

function JSvPRBDOCFilterPdtInTableTemp() {
    JCNxOpenLoading();
    JSvPOLoadPdtDataTableHtml();
}

// Function Chack Value LocalStorage
function JStPRBFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

function JSxPRBSetStatusClickSubmit(pnStatus) {
    $("#ohdPRBCheckSubmitByButton").val(pnStatus);
}

// Add/Edit Document
function JSxPRBAddEditDocument() { // var nStaSession = JCNxFuncChkSessionExpired();
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxPRBValidateFormDocument();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Document Single
function JSoPRBDelDocSingle(ptCurrentPage, ptPRBDocNo, ptAgnCode , ptBchCode, ptPRBRefInCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptPRBDocNo) != undefined && ptPRBDocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptPRBDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvPRBModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvPRBModalDelDocSingle').modal('show');
            $('#odvPRBModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docPRBEventDelete",
                    data: {
                        'tDataDocNo': ptPRBDocNo,
                        'tBchCode': ptBchCode,
                        'tAgnCode': ptAgnCode,
                        'tPRBRefInCode': ptPRBRefInCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvPRBModalDelDocSingle').modal('hide');
                            $('#odvPRBModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                                JSvPRBCallPageDataTable(ptCurrentPage);
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
function JSoPRBDelDocMultiple() {
    var aDataDelMultiple = $('#odvPRBModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
            var tAgnCode = $(this).data('agncode');
            var tPRBRefInCode = $(this).data('refcode');
            localStorage.StaDeleteArray = '1';
            $.ajax({
                type: "POST",
                url: "docPRBEventDelete",
                data: {
                    'tDataDocNo': tDataDocNo,
                    'tBchCode': tBchCode,
                    'tAgnCode': tAgnCode,
                    'tPRBRefInCode': tPRBRefInCode
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvPRBModalDelDocMultiple').modal('hide');
                            $('#odvPRBModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvPRBModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                            JSvPRBCallPageList();
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
function JSxPRBShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliPRBBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliPRBBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliPRBBtnDeleteAll").addClass("disabled");
        }
    }
}

// Function: Function Chack Value LocalStorage
function JStPRBFinPRBbjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// Function: Cancel Document DO
function JSnPRBCancelDocument(pbIsConfirm) {
    var tPRBDocNo = $("#oetPRBDocNo").val();
    var tPRBAgnCode = $("#oetPRBAgnCode").val();
    var tPRBFrmBchCode = $("#oetPRBFrmBchCode").val();

    var tRefInPRBcNo = $('#oetPRBRefDocIntName').val();
    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docPRBCancelDocument",
            data: {
                'ptPRBDocNo': tPRBDocNo,
                'ptRefInPRBcNo': tRefInPRBcNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvPRBPopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSvPRBCallPageEdit(tPRBFrmBchCode,tPRBAgnCode,tPRBDocNo);
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
        $('#odvPRBPopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvPRBPopupCancel").modal("show");
    }
}

// Function: Function Control Object Button
function JCNxPRBControlObjAndBtn() { // Check สถานะอนุมัติ
    var nPRBStaDoc = $("#ohdPRBStaDoc").val();
    var nPRBStaApv = $("#ohdPRBStaApv").val();

    // Status Cancel
    if (nPRBStaDoc == 3) {
        $("#oliPRBTitleAdd").hide();
        $('#oliPRBTitleEdit').hide();
        $('#oliPRBTitleDetail').show();
        $('#oliPRBTitleAprove').hide();
        $('#oliPRBTitleConimg').hide();
        // Hide And Disabled
        $("#obtPRBCallPageAdd").hide();
        $("#obtPRBCancelDoc").hide(); // attr("disabled",true);
        $("#obtPRBApproveDoc").hide(); // attr("disabled",true);
        $("#obtPRBBrowseSupplier").attr("disabled", true);
        $(".xWConditionSearchPdt").attr("disabled", true);

        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNPRBcBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNPRBcDrpDwn").hide();
        $("#oetPRBFrmSearchPdtHTML").attr("disabled", false);
        $('#odvPRBBtnGrpSave').hide();
        $("#oliPRBEditShipAddress").hide();
        $("#oliPRBEditTexAddress").hide();
        $("#oliPRBTitleDetail").show();

        $("#ocbDOFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtPRBFrmBrowseShipAdd").attr("disabled", true);
        $("#obtPRBFrmBrowseTaxAdd").attr("disabled", true);
        // อินพุต
        $('.xControlForm').attr('readonly', true);
        $('.xWDODisabledOnApv').attr('disabled', true);
        $('.xControlRmk').attr('readonly', true);
        $("#obtPRBFrmBrowseTaxAdd").hide();


    }

    // Status Appove Success
    if (nPRBStaDoc == 1 && nPRBStaApv == 1) { // Hide/Show Menu Title
        $("#oliPRBTitleAdd").hide();
        $('#oliPRBTitleEdit').hide();
        $('#oliPRBTitleDetail').show();
        $('#oliPRBTitleAprove').hide();
        $('#oliPRBTitleConimg').hide();
        // Hide And Disabled
        $("#obtPRBCallPageAdd").hide();
        $("#obtPRBCancelDoc").hide(); // attr("disabled",true);
        $("#obtPRBApproveDoc").hide(); // attr("disabled",true);
        $("#obtPRBBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNPRBcBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNPRBcDrpDwn").hide();
        $("#oetPRBFrmSearchPdtHTML").attr("disabled", false);
        $('#odvPRBBtnGrpSave').show();
        $("#oliPRBEditShipAddress").hide();
        $("#oliPRBEditTexAddress").hide();
        $("#oliPRBTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWDODisabledOnApv').attr('disabled', true);
        $("#ocbDOFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtPRBFrmBrowseShipAdd").attr("disabled", true);
        $("#obtPRBFrmBrowseTaxAdd").attr("disabled", true);
    }
}


function JSxSoCallBackUploadFile(ptParam){
    console.log(ptParam);
}
</script>
