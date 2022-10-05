var nStaAPDBrowseType   = $("#oetAPDStaBrowse").val();
var tCallAPDBackOption  = $("#oetAPDCallBackOption").val();

$("document").ready(function () {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxAPDNavDefult();

    // รองรับการเข้ามาแบบ Noti
    switch(nStaAPDBrowseType){
        case '2':
            var tAgnCode    = $('#oetAPDJumpAgnCode').val();
            var tBchCode    = $('#oetAPDJumpBchCode').val();
            var tDocNo      = $('#oetAPDJumpDocNo').val();
            JSvCallPageAPDEdit(tDocNo);
        break;
        default:
            JSvCallPageAPDList();
    }
});

// Function : Set Default Navbar 
// Creator  : 01/03/2022 Wasin
function JSxAPDNavDefult() {
    if (nStaAPDBrowseType != 1 || nStaAPDBrowseType == undefined) {
        $(".xCNAPDVBrowse").hide();
        $(".xCNAPDVMaster").show();
        $("#oliAPDTitleAdd").hide();
        $("#oliAPDTitleEdit").hide();
        $("#oliAPDTitleDetail").hide();
        $("#odvBtnAddEdit").hide();
        $(".obtChoose").hide();
        $("#odvBtnAPDInfo").show();
    }else{
        $("#odvModalBody .xCNAPDVMaster").hide();
        $("#odvModalBody .xCNAPDVBrowse").show();
        $("#odvModalBody #odvAPDMainMenu").removeClass("main-menu");
        $("#odvModalBody #oliAPDNavBrowse").css("padding", "2px");
        $("#odvModalBody #odvAPDBtnGroup").css("padding", "0");
        $("#odvModalBody .xCNAPDBrowseLine").css("padding", "0px 0px");
        $("#odvModalBody .xCNAPDBrowseLine").css("border-bottom", "1px solid #e3e3e3");
    }
}

// Function : Function Show Event Error
// Creator  : 01/03/2022 Wasin
function JCNxResponseError(jqXHR, textStatus, errorThrown) {
    JCNxCloseLoading();
    let tHtmlError = $(jqXHR.responseText);
    let tMsgError = "<h3 style='font-size:20px;color:red'>";
    tMsgError += "<i class='fa fa-exclamation-triangle'></i>";
    tMsgError += " Error<hr></h3>";
    switch (jqXHR.status) {
        case 404:
            tMsgError += tHtmlError.find("p:nth-child(2)").text();
            break;
        case 500:
            tMsgError += tHtmlError.find("p:nth-child(3)").text();
            break;

        default:
            tMsgError += "something had error. please contact admin";
            break;
    }
    $("body").append(tModal);
    $("#modal-customs").attr(
            "style",
            "width: 450px; margin: 1.75rem auto;top:20%;"
            );
    $("#myModal").modal({show: true});
    $("#odvModalBody").html(tMsgError);
}

// Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
// Creator  : 09/03/2022 Wasin
function FSvPDTAddPdtIntoTableDT(ptPdtData, ptIsRefPI, tIsByScanBarCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        ptIsRefPI           = typeof ptIsRefPI == 'undefined' ? '0' : ptIsRefPI;
        tIsByScanBarCode    = typeof tIsByScanBarCode == 'undefined' ? '0' : tIsByScanBarCode;
        var ptXthDocNoSend  = "";
        if (JCNbAPDIsUpdatePage()) {
            ptXthDocNoSend  = $("#oetAPDDocNo").val();
        }
        var tSplCode    = $('#oetAPDSplCode').val();
        $.ajax({
            type: "POST",
            url: "docAPDebitnoteAddPdtIntoTableDT",
            data: {
                tDocNo                  : ptXthDocNoSend,
                tSplCode                : tSplCode,
                tIsRefPI                : ptIsRefPI,
                tIsByScanBarCode        : tIsByScanBarCode,
                tBarCodeByScan          : $('#oetAPDScanPdtHTML').val(),
                tSplVatType             : JSxAPDIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                tPdtData                : ptPdtData,
                tAPDOptionAddPdt        : $("#ocmAPDOptionAddPdt").val(), // เพิ่มแถวใหม่ Default 1 : บวกรายการเดิมในรายการ
                tBchCode                : $("#oetAPDBchCode").val()
            },
            cache: false,
            success: function (tResult) {
                if(JCNbAPDIsDocType('havePdt')){
                    if(tResult.rtCode == '800'){
                        FSvCMNSetMsgWarningDialog('ไม่พบรายการสินค้า');
                        return;
                    }
                    JSvAPDLoadPdtDataTableHtml();
                }
                if(JCNbAPDIsDocType('nonePdt')){
                    JSvAPDLoadNonePdtDataTableHtml();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : ค้นหาสินค้า
// Creator  : 09/03/2022 Wasin
function JSvAPDDOCSearchPdtHTML() {
    if(JCNbAPDIsDocType('havePdt')){
        JSvAPDLoadPdtDataTableHtml();
    }
    if(JCNbAPDIsDocType('nonePdt')){
        JSvAPDLoadNonePdtDataTableHtml();
    }
}

// Function : อนุมัติเอกสาร
// Creator  : 09/03/2022 Wasin
function JSnAPDApprove(pbIsConfirm) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxAPDAddUpdateAction('approve');
        try {
            if (pbIsConfirm) {
                $("#ohdCardShiftTopUpCardStaPrcDoc").val(2); // Set status for processing approve
                $("#odvAPDPopupApv").modal("hide");
                var tDocNo      = $("#oetAPDDocNo").val();
                var tStaApv     = $("#ohdXthStaApv").val();
                var tDocType    = $("#ohdAPDDocType").val();
                var tBchCode    = $("#oetAPDBchCode").val();
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docAPDebitnoteApprove",
                    data: {
                        tDocNo: tDocNo,
                        tStaApv: tStaApv,
                        tDocType: tDocType,
                        tBchCode:tBchCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function (oResult) {
                        try{
                            if (oResult.nStaEvent == "900") {
                                FSvCMNSetMsgErrorDialog(oResult.tStaMessg);
                                JCNxCloseLoading();
                                return;
                            }
                        }catch(err) {}
                        
                        if(JCNbAPDIsDocType('havePdt')){
                            JSoAPDSubscribeMQ();
                        }
                        
                        if(JCNbAPDIsDocType('nonePdt')){
                            var tDocNo = $('#oetAPDDocNo').val();
                            JSvCallPageAPDEdit(tDocNo);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        JCNxCloseLoading();
                    }
                });
            } else {
                // console.log("StaApvDoc Call Modal");
                $("#odvAPDPopupApv").modal("show");
            }
        } catch (err) {
            console.log("JSnAPDApprove Error: ", err);
        }
        
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : Subscibe เอกสารใบเพิ่มหนี้
// Creator  : 09/03/2022 Wasin
function JSoAPDSubscribeMQ() {
    // RabbitMQ
    /*===========================================================================*/
    // Document variable
    var tLangCode   = $("#ohdLangEdit").val();
    var tUsrBchCode = $("#oetAPDBchCode").val();
    var tUsrApv     = $("#ohdAPDUsrCode").val();
    var tDocNo      = $("#oetAPDDocNo").val();
    var tPrefix     = "RESPD";
    var tStaApv     = $("#ohdAPDStaApv").val();
    var tStaDelMQ   = $("#ohdAPDStaDelMQ").val();
    var tQName      = tPrefix + "_" + tDocNo + "_" + tUsrApv;
    // MQ Message Config
    var poDocConfig = {
        tLangCode   : tLangCode,
        tUsrBchCode : tUsrBchCode,
        tUsrApv     : tUsrApv,
        tDocNo      : tDocNo,
        tPrefix     : tPrefix,
        tStaDelMQ   : tStaDelMQ,
        tStaApv     : tStaApv,
        tQName      : tQName
    };

    // RabbitMQ STOMP Config
    var poMqConfig = {
        host        : "ws://" + oSTOMMQConfig.host + ":15674/ws",
        username    : oSTOMMQConfig.user,
        password    : oSTOMMQConfig.password,
        vHost       : oSTOMMQConfig.vhost
    };

    // Update Status For Delete Qname Parameter
    var poUpdateStaDelQnameParams = {
        ptDocTableName      : "TAPTPdHD",
        ptDocFieldDocNo     : "FTXphDocNo",
        ptDocFieldStaApv    : "FTXphStaPrcStk",
        ptDocFieldStaDelMQ  : "FTXphStaDelMQ",
        ptDocStaDelMQ       : tStaDelMQ,
        ptDocNo             : tDocNo
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit   : "JSvCallPageAPDEdit",
        tCallPageList   : "JSvCallPageAPDList"
    };

    // Check Show Progress %
    FSxCMNRabbitMQMessage(
        poDocConfig,
        poMqConfig,
        poUpdateStaDelQnameParams,
        poCallback
    );
    /*===========================================================================*/
    // RabbitMQ
}

// Function : ยกเลิกเอกสารใบเพิ่มหนี้
// Creator  : 09/03/2022 Wasin
function JSnAPDCancel(pbIsConfirm) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        let tDocNo  = $("#oetAPDDocNo").val();
        if (pbIsConfirm) {
            $.ajax({
                type    : "POST",
                url     : "docAPDebitnoteCancel",
                data    : {tDocNo: tDocNo},
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    $("#odvAPDPopupCancel").modal("hide");
                    var aResult = $.parseJSON(tResult);
                    if (aResult.nSta == 1) {
                        JSvCallPageAPDEdit(tDocNo);
                    } else {
                        JCNxCloseLoading();
                        var tMsgBody = aResult.tMsg;
                        FSvCMNSetMsgWarningDialog(tMsgBody);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            $("#odvAPDPopupCancel").modal("show");
        }
    
    }else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : GET Scan BarCode
// Creator  : 09/03/2022 Wasin
function JSvAPDScanPdtHTML() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var tBarCode = $("#oetAPDScanPdtHTML").val();
        var tSplCode = $("#oetSplCode").val();
        if (tBarCode != "") {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docAPDebitnoteGetPdtBarCode",
                data: {
                    tBarCode: tBarCode,
                    tSplCode: tSplCode
                },
                cache: false,
                success: function (tResult) {
                    aResult = $.parseJSON(tResult);
                    if (aResult.aData != 0) {
                        tData = $.parseJSON(aResult.aData);
                        tPdtCode = tData[0].FTPdtCode;
                        tPunCode = tData[0].FTPunCode;
                        //Funtion Add Pdt To Table
                        FSvAPDAddPdtIntoTableDT(tPdtCode, tPunCode);
                        $("#oetAPDScanPdtHTML").val("");
                        $("#oetAPDScanPdtHTML").focus();
                    } else {
                        JCNxCloseLoading();
                        tMsgBody = aResult.tMsg;
                        FSvCMNSetMsgWarningDialog(tMsgBody);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            $("#oetAPDScanPdtHTML").focus();
        }
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

// Function : ลบสินค้า DT Temp
// Creator  : 09/03/2022 Wasin
function JSnAPDRemoveDTTemp(ptSeqNo, ptPdtCode) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        let tDocNo      = $("#oetAPDDocNo").val();
        let nPage       = $(".xWPageAPDPdt .active").text();
        let tBchCode    = $('#oetAPDBchCode').val();
        $.ajax({
            type: "POST",
            url: "docAPDebitnoteRemovePdtInDTTmp",
            data: {
                tDocNo      : tDocNo,
                nSeqNo      : ptSeqNo,
                tBchCode    : tBchCode,
                tSplVatType : JSxAPDIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                tPdtCode    : ptPdtCode
            },
            cache: false,
            success: function (tResult) {
                if(JCNbAPDIsDocType('havePdt')){
                    JSvAPDLoadPdtDataTableHtml(nPage);
                }
                if(JCNbAPDIsDocType('nonePdt')){
                    JSvAPDLoadNonePdtDataTableHtml();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : เพิ่มสินค้า Table DT
// Creator  : 09/03/2022 Wasin
function FSvAPDAddPdtIntoTableDT(ptPdtCode, ptPunCode, pnXthVATInOrEx) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        let ptOptDocAdd = $("#ohdOptScanSku").val();
        let ptXthDocNo  = $("#oetAPDDocNo").val();
        let ptBchCode   = $("#ohdSesUsrBchCode").val();
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnoteAddPdtIntoTableDT",
            data    : {
                ptXthDocNo      : ptXthDocNo,
                ptBchCode       : ptBchCode,
                ptPdtCode       : ptPdtCode,
                ptPunCode       : ptPunCode,
                ptOptDocAdd     : ptOptDocAdd,
                pnXthVATInOrEx  : pnXthVATInOrEx
            },
            cache   : false,
            success : function (tResult) {
                JMvDOCGetPdtImgScan(ptPdtCode);
                if(JCNbAPDIsDocType('havePdt')){
                    JSvAPDLoadPdtDataTableHtml();
                }
                if(JCNbAPDIsDocType('nonePdt')){
                    JSvAPDLoadNonePdtDataTableHtml();
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

// Function : รูปภาพของสินค้า
// Creator  : 09/03/2022 Wasin
function JMvDOCGetPdtImgScan(ptPdtCode){
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type    : "POST",
            url     : "DOCGetPdtImg",
            data    : { tPdtCode : ptPdtCode},
            cache   : false,
            success : function(tResult){
                $('#odvShowPdtImgScan').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : แก้ไขสินค้าใน Table DT
// Creator  : 09/03/2022 Wasin
function FSvAPDEditPdtIntoTableDT(pnSeqNo, ptFieldName, ptValue, pbIsDelDTDis) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var tDocNo      = $("#oetAPDDocNo").val();
        var tBchCode    = $("#ohdSesUsrBchCode").val();
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnoteEditPdtIntoTableDT",
            data    : {
                tDocNo      : tDocNo,
                tSplVatType : JSxAPDIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                tSeqNo      : pnSeqNo,
                tFieldName  : ptFieldName,
                tValue      : ptValue,
                tIsDelDTDis : (pbIsDelDTDis) ? '1' : '0' // 1: ลบ, 2: ไม่ลบ
            },
            cache   : false,
            success : function (tResult) {
                if (JCNbAPDIsDocType('havePdt')) {
                    JSvAPDLoadPdtDataTableHtml(1, false);
                }
                if (JCNbAPDIsDocType('nonePdt')) {
                    JSvAPDLoadNonePdtDataTableHtml(1, false);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : หน้าเพิ่มข้อมูล เอกสารใบเพิ่มหนี้
// Creator  : 03/03/2022 Wasin
function JSvCallPageAPDAdd(){
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        localStorage.removeItem('LocalItemData');
        $('#odvAPDSelectDocTypePopup').modal('show');
        $('#obtnAPDConfirmSelectDocType').one('click', function(){

            $('#odvAPDSelectDocTypePopup').modal('hide');
            let nDoctype    = $('#odvAPDSelectDocTypePopup input[name=orbAPDSelectDocType]:checked').val();

            $.ajax({
                type    : "POST",
                url     : "docAPDebitnotePageAdd",
                data    : {nDocType : nDoctype},
                cache   : false,
                success : function (tResult) {
                    if (nStaAPDBrowseType == 1) {
                        $(".xCNAPDVMaster").hide();
                        $(".xCNAPDVBrowse").show();
                    } else {
                        $(".xCNAPDVBrowse").hide();
                        $(".xCNAPDVMaster").show();
                        $("#oliAPDTitleEdit").hide();
                        $("#oliAPDTitleDetail").hide();
                        $("#oliAPDTitleAdd").show();
                        $("#odvBtnAPDInfo").hide();
                        $("#odvBtnAddEdit").show();
                        $("#obtAPDApprove").hide();
                        $("#obtAPDCancel").hide();
                    }
                    $("#odvContentPageAPD").html(tResult);

                    // Control Object And Button ปิด เปิด
                    JCNxAPDControlObjAndBtn();

                    // Load Pdt Table DT Temp
                    if ($("#oetBchCode").val() == "") {
                        $("#obtAPDBrowseShipAdd").attr("disabled", "disabled");
                    }

                    if(JCNbAPDIsDocType('havePdt')){
                        JSvAPDLoadPdtDataTableHtml();
                    }

                    if(JCNbAPDIsDocType('nonePdt')){
                        JSvAPDLoadNonePdtDataTableHtml();
                    }

                    $('#ocbAPDAutoGenCode').change(function () {
                        $("#oetAPDDocNo").val("");
                        if ($('#ocbAPDAutoGenCode').is(':checked')) {
                            $("#oetAPDDocNo").attr("readonly", true);
                            $("#oetAPDDocNo").attr("onfocus", "this.blur()");
                            $('#ofmAddAPD').removeClass('has-error');
                            $('#ofmAddAPD .form-group').closest('.form-group').removeClass("has-error");
                            $('#ofmAddAPD em').remove();
                        } else {
                            $("#oetAPDDocNo").attr("readonly", false);
                            $("#oetAPDDocNo").removeAttr("onfocus");
                        }

                    });
                    $("#oetAPDDocNo,#oetXthDocDate,#oetXthDocTime").blur(function () {
                        JSxValidateFormAddAPD();
                        $('#ofmAddAPD').submit();
                    });

                    $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");
                },
                error   : function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Function : หน้าเพิ่มและแก้ไข Form
// Creator  : 03/03/2022 Wasin
function JSnAddEditAPD() {
    JSxValidateFormAddAPD();
}

// Function : หน้าหลัก
// Creator  : 01/03/2022 Wasin
function JSvCallPageAPDList() {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            localStorage.removeItem('LocalItemData');
            $.ajax({
                type    : "POST",
                url     : "docAPDebitnoteFormSearchList",
                data    : {},
                cache   : false,
                success : function (tResult) {
                    $("#odvContentPageAPD").html(tResult);
                    JSxAPDNavDefult();
                    JSvCallPageAPDPdtDataTable();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } catch (err) {
            console.log('JSvCallPageAPDebitnoteList Error: ', err);
        }
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Function : ตารางรายการใบเพิ่มหนี้
// Creator  : 01/03/2022 Wasin
function JSvCallPageAPDPdtDataTable(pnPage) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch  = JSoAPDGetAdvanceSearchData();
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnoteDataTable",
            data    : {
                tAdvanceSearch  : JSON.stringify(oAdvanceSearch),
                nPageCurrent    : nPageCurrent
            },
            cache   : false,
            success : function (tResult) {
                $("#odvContentAPDList").html(tResult);
                JSxAPDNavDefult();
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Function : ดึงข้อมูลฟอร์มค้นหาใบเพิ่มหนี้
// Creator  : 01/03/2022 Wasin
function JSoAPDGetAdvanceSearchData() {
    try {
        let oAdvanceSearchData = {
            tSearchAll          : $("#oetSearchAll").val(),
            tSearchBchCodeFrom  : $("#oetBchCodeFrom").val(),
            tSearchBchCodeTo    : $("#oetBchCodeTo").val(),
            tSearchDocDateFrom  : $("#oetSearchDocDateFrom").val(),
            tSearchDocDateTo    : $("#oetSearchDocDateTo").val(),
            tSearchStaDoc       : $("#ocmStaDoc").val(),
            tSearchStaApprove   : $("#ocmStaApprove").val(),
            tSearchStaPrcStk    : $("#ocmStaPrcStk").val(),
            tSearchDocType      : $("#ocmDocType").val(),
            tSearchStaDocAct    : $("#ocmStaDocAct").val(),
            tSearchSplCodeFrom  : $("#oetSplCodeFrom").val(),
            tSearchSplCodeTo    : $("#oetSplCodeTo").val(),
        };
        return oAdvanceSearchData;
    }catch (err) {
        console.log("JSoAPDGetAdvanceSearchData Error: ", err);
    }
}

// Function : เข้าหน้าแก้ไข ใบเพิ่มหนี้
// Creator  : 01/03/2022 Wasin
function JSvCallPageAPDEdit(ptDocNo) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        localStorage.removeItem('LocalItemData');
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML("JSvCallPageAPDEdit", ptDocNo);
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnotePageEdit",
            data    : {tDocNo: ptDocNo},
            cache   : false,
            success : function (tResult) {
                if (tResult != "") {
                    $("#oliAPDTitleAdd").hide();
                    $("#odvBtnAPDInfo").hide();
                    $("#odvBtnAddEdit").show();
                    $("#odvContentPageAPD").html(tResult);
                    $("#oetAPDDocNo").addClass("xCNDisable");
                    $(".xCNDisable").attr("readonly", true);
                    $(".xCNiConGen").hide();
                    $("#obtAPDApprove").show();
                    $("#obtAPDCancel").show();
                    $("#oliAPDTitleEdit").show();
                }

                // Function Load Table Pdt ของ Debitnote
                if(JCNbAPDIsDocType('havePdt')){
                    JSvAPDLoadPdtDataTableHtml();
                }
                if(JCNbAPDIsDocType('nonePdt')){
                    JSvAPDLoadNonePdtDataTableHtml();
                }

                // Put Data
                ohdXthCshOrCrd = $("#ohdXthCshOrCrd").val();
                $("#ostXthCshOrCrd option[value='" + ohdXthCshOrCrd + "']").attr("selected", true).trigger("change");

                ohdXthStaRef = $("#ohdXthStaRef").val();
                $("#ostXthStaRef option[value='" + ohdXthStaRef + "']").attr("selected", true).trigger("change");

                // Control Object And Button ปิด เปิด
                JCNxAPDControlObjAndBtn();

                JCNxLayoutControll();
                $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");

            },
            error   : function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Function : Control Object And Button ปิด เปิด
// Creator  : 03/03/2022 Wasin
function JCNxAPDControlObjAndBtn() {
    // Check สถานะอนุมัติ
    var ohdXthStaApv    = $("#ohdAPDStaApv").val();
    var ohdXthStaDoc    = $("#ohdAPDStaDoc").val();
    var tDocType        = $("#ohdAPDDocType").val();
    $("#oliAPDTitleDetail").hide();
    if(tDocType == '7'){
        $('#odvAPDCondition').css('display','none');
        $('#obtAPDBrowseRefPI').attr('disabled',true);
        $('#oetAPDXphRefIntDate').attr('disabled',true);
        $('#obtAPDRefIntDate').attr('disabled',true);
    }
    // Set Default
    // Btn Cancel
    $("#obtAPDCancel").attr("disabled", false);
    // Btn Apv
    $("#obtAPDApprove").attr("disabled", false);
    // $(".form-control").attr("disabled", false);
    $(".ocbListItem").attr("disabled", false);
    // $(".xCNBtnBrowseAddOn").attr("disabled", false);
    $(".xCNBtnDateTime").attr("disabled", false);
    $(".xCNDocBrowsePdt").attr("disabled", false).removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").show();
    $("#oetAPDSearchPdtHTML").attr("disabled", false);
    $(".btn-group").removeClass("xCNHide", true);
    $(".btn-group").removeClass("hidden", true);
    $("#oliBtnEditShipAdd").show();
    $("#oliBtnEditTaxAdd").show();
    if (ohdXthStaApv == 1) { 
        $("#oliAPDTitleEdit").hide();
        $("#oliAPDTitleDetail").show();
        // Btn Apv
        $("#obtAPDApprove").attr("disabled", true);
        // Control input ปิด
        // $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        // $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetAPDSearchPdtHTML").attr("disabled", false);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
        $(".xCNBtnDateTime").attr("disabled", true);
    }
    // Check สถานะเอกสาร
    if (ohdXthStaDoc == 3) {
        $("#oliAPDTitleEdit").hide();
        $("#oliAPDTitleDetail").show();
        // Btn Cancel
        $("#obtAPDCancel").attr("disabled", true);
        // Btn Apv
        $("#obtAPDApprove").attr("disabled", true);
        // Control input ปิด
        // $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        // $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetAPDSearchPdtHTML").attr("disabled", false);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
        $(".xCNBtnDateTime").attr("disabled", true);
    }
}


// Function : Event Delete Document
// Creator  : 03/03/2022 Wasin
function JSnAPDDel(tCurrentPage, tDocNo) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        let aData               = $("#ohdConfirmIDDelete").val();
        let aTexts              = aData.substring(0, aData.length - 2);
        let aDataSplit          = aTexts.split(" , ");
        let aDataSplitlength    = aDataSplit.length;
        if (aDataSplitlength == "1") {
            $("#odvModalDel").modal("show");
            $("#ospConfirmDelete").html("ยืนยันการลบข้อมูล หมายเลข : " + tDocNo);
            $("#osmConfirm").on("click", function (evt) {
                $("#odvModalDel").modal("hide");
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docAPDebitnoteEventDeleteDoc",
                    data    : {tDocNo: tDocNo},
                    cache   : false,
                    success: function (tResult) {
                        JSvCallPageAPDPdtDataTable(tCurrentPage);
                        JSxAPDNavDefult();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        }
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Function : Event Delete Document Multi
// Creator  : 09/03/2022 Wasin
function JSnAPDDelChoose() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $("#odvModalDel").modal("hide");
        JCNxOpenLoading();
        var aData               = $("#ohdConfirmIDDelete").val();
        var aTexts              = aData.substring(0, aData.length - 2);
        var aDataSplit          = aTexts.split(" , ");
        var aDataSplitlength    = aDataSplit.length;
        var aDocNo              = [];
        for ($i = 0; $i < aDataSplitlength; $i++) {
            aDocNo.push(aDataSplit[$i]);
        }
        if (aDataSplitlength > 1) {
            localStorage.StaDeleteArray = "1";
            $.ajax({
                type: "POST",
                url: "docAPDebitnoteEventDeleteMultiDoc",
                data: {aDocNo: aDocNo},
                success: function (tResult) {
                    JSvCallPageAPDPdtDataTable();
                    JSxAPDNavDefult();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            localStorage.StaDeleteArray = "0";
            return false;
        }
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Function : เปลี่ยนหน้า pagenation
// Creator  : 09/03/2022 Wasin
 function JSvAPDClickPage(ptPage) {
    var nPageCurrent = "";
    switch (ptPage) {
        case "next": //กดปุ่ม Next
            $(".xWBtnNext").addClass("disabled");
            nPageOld        = $(".xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent    = nPageNew;
            break;
        case "previous": //กดปุ่ม Previous
            nPageOld        = $(".xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent    = nPageNew;
            break;
        default:
            nPageCurrent    = ptPage;
    }
    JSvCallPageAPDPdtDataTable(nPageCurrent);
}

// Function : Insert Text In Modal Delete
// Creator  : 03/03/2022 Wasin
function JSxTextinModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
    } else {
        var tTextCode = "";
        for ($i = 0; $i < aArrayConvert[0].length; $i++) {
            tTextCode += aArrayConvert[0][$i].nCode;
            tTextCode += " , ";
        }
        //Disabled ปุ่ม Delete
        if (aArrayConvert[0].length > 1) {
            $(".xCNIconDel").addClass("xCNDisabled");
        } else {
            $(".xCNIconDel").removeClass("xCNDisabled");
        }

        $("#ospConfirmDelete").text("ท่านต้องการลบข้อมูลทั้งหมดหรือไม่ ?");
        $("#ohdConfirmIDDelete").val(tTextCode);
    }
}

// Function : เปลี่ยนหน้า pagenation product table
// Creator  : 09/03/2022 Wasin
function JSvAPDPdtClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld        = $(".xWPageAPDPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent    = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld        = $(".xWPageAPDPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent    = nPageNew;
                break;
            default:
                nPageCurrent    = ptPage;
        }
        JCNxOpenLoading();
        if(JCNbAPDIsDocType('havePdt')){
            JSvAPDLoadPdtDataTableHtml(nPageCurrent);
        }
        if(JCNbAPDIsDocType('nonePdt')){
            JSvAPDLoadNonePdtDataTableHtml();
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : Function Chack And Show Button Delete All
// Creator  : 03/03/2022 Wasin
function JSxShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliAPDBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliAPDBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliAPDBtnDeleteAll").addClass("disabled");
        }
    }
}

// =============================================  Advance Table =============================================

// Function : Get Html PDT มาแปะ ในหน้า Add แบบมีสินค้า
// Creator  : 04/03/2022 Wasin
function JSvAPDLoadPdtDataTableHtml(pnPage, pbUseLoading) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        pbUseLoading    = typeof pbUseLoading === 'undefined' ? true : pbUseLoading;
        if(pbUseLoading){
            JCNxOpenLoading();
        }
        var tSearchAll = $('#oetAPDSearchPdtHTML').val();
        var tDocNo, tStaApv, tStaDoc, nPageCurrent;
        if (JCNbAPDIsCreatePage()) {
            tDocNo  = "";
        } else {
            tDocNo  = $("#oetAPDDocNo").val();
        }
        tStaApv = $("#ohdAPDStaApv").val();
        tStaDoc = $("#ohdAPDStaDoc").val();
        // เช็ค สินค้าใน table หน้านั้นๆ มีหรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#odvTBodyAPDPdt .xWPdtItem").length == 0) {
            if (typeof pnPage !== 'undefined') {
                pnPage = pnPage - 1;
            }
        }
        nPageCurrent    = ( (typeof pnPage === 'undefined') || (pnPage === "") || (pnPage <= 0) ) ? "1" : pnPage;
        $.ajax({
            type : "POST",
            url  : "docAPDebitnotePdtAdvanceTableLoadData",
            data : {
                'tSearchAll'    : tSearchAll,
                'tDocNo'        : tDocNo,
                'tSplVatType'   : JSxAPDIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก,
                'tStaApv'       : tStaApv,
                'tStaDoc'       : tStaDoc,
                'nPageCurrent'  : nPageCurrent,
                'tBchCode'      : $('#oetAPDBchCode').val()
            },
            cache: false,
            Timeout: 0,
            success: function (oResult) {
                try{
                    $("#odvAPDPdtTablePanal").html(oResult.tTalbleHtml);
                    JSxAPDSetEndOfBill(oResult.aEndOfBill);
                    // JSvAPDLoadVatTableHtml(); // Load Vat Table
                    if(pbUseLoading){
                        JCNxCloseLoading();
                    }
                }catch(err){
                    console.log('JSvAPDLoadPdtDataTableHtml Error: ', err);
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

// Function : Get Html PDT มาแปะ ในหน้า Add แบบไม่มีสินค้า
// Creator  : 04/03/2022 Wasin
function JSvAPDLoadNonePdtDataTableHtml(pnPage, pbUseLoading) {
    let nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        pbUseLoading    = typeof pbUseLoading === 'undefined' ? true : pbUseLoading;
        if(pbUseLoading){
            JCNxOpenLoading();
        }
        var tSearchAll  = $('#oetAPDSearchPdtHTML').val();
        var tDocNo, tStaApv, tStaDoc, nPageCurrent;
        if (JCNbAPDIsCreatePage()) {
            tDocNo  = "";
        } else {
            tDocNo  = $("#oetAPDDocNo").val();
        }
        tStaApv = $("#ohdAPDStaApv").val();
        tStaDoc = $("#ohdAPDStaDoc").val();
        // เช็ค สินค้าใน table หน้านั้นๆ มีหรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#odvTBodyAPDPdt .xWPdtItem").length == 0) {
            if (typeof pnPage !== 'undefined') {
                pnPage  = pnPage - 1;
            }
        }
        nPageCurrent    = ( (typeof pnPage === 'undefined') || (pnPage === "") || (pnPage <= 0) ) ? "1" : pnPage;
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnoteNonePdtAdvanceTableLoadData",
            data: {
                tSearchAll      : tSearchAll,
                tDocNo          : tDocNo,
                tSplVatType     : JSxAPDIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก,
                tStaApv         : tStaApv,
                tStaDoc         : tStaDoc,
                nPageCurrent    : nPageCurrent
            },
            cache: false,
            Timeout: 0,
            success: function (oResult) {
                try{
                    $("#odvAPDPdtTablePanal").html(oResult.tTalbleHtml);
                    if(pbUseLoading){
                        JCNxCloseLoading();
                    }
                }catch(err){
                    console.log('JSvAPDLoadPdtDataTableHtml Error: ', err);
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

// Function : Open Column From Set
// Creator  : 04/03/2022 Wasin
function JSxOpenColumnFormSet() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "docAPDebitnoteAdvanceTableShowColList",
            data: {},
            cache: false,
            Timeout: 0,
            success: function (tResult) {
                $("#odvShowOrderColumn").modal({show: true});
                $("#odvOderDetailShowColumn").html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    
    }else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : Save Column Show
// Creator  : 04/03/2022 Wasin
function JSxSaveColumnShow() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var aColShowSet = [];
        $(".ocbColStaShow:checked").each(function () {
            aColShowSet.push($(this).data("id"));
        });
        var aColShowAllList = [];
        $(".ocbColStaShow").each(function () {
            aColShowAllList.push($(this).data("id"));
        });
        var aColumnLabelName = [];
        $(".olbColumnLabelName").each(function () {
            aColumnLabelName.push($(this).text());
        });

        var nStaSetDef;
        if ($("#ocbSetToDef").is(":checked")) {
            nStaSetDef = 1;
        } else {
            nStaSetDef = 0;
        }

        $.ajax({
            type: "POST",
            url: "docAPDebitnoteAdvanceTableShowColSave",
            data: {
                aColShowSet         : aColShowSet,
                nStaSetDef          : nStaSetDef,
                aColShowAllList     : aColShowAllList,
                aColumnLabelName    : aColumnLabelName
            },
            cache: false,
            Timeout: 0,
            success: function (tResult) {
                $("#odvShowOrderColumn").modal("hide");
                $(".modal-backdrop").remove();
                // Function Gen Table Pdt ของ APD

                if(JCNbAPDIsDocType('havePdt')){
                    JSvAPDLoadPdtDataTableHtml();
                }
                if(JCNbAPDIsDocType('nonePdt')){
                    JSvAPDLoadNonePdtDataTableHtml();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}


// Function : ปรับ Value ใน Input หลัวจาก กรอก เสร็จ
// Creator  : 04/03/2022 Wasin
function JSxAPDAdjInputFormat(ptInputID) {
    cVal = $("#" + ptInputID).val();
    cVal = accounting.toFixed(cVal, nOptDecimalShow);
    $("#" + ptInputID).val(cVal);
}

// Function : Is create page.
// Creator  : 01/03/2022 Wasin
function JCNbAPDIsCreatePage(){
    try{
        var tAPDDocNo = $('#oetAPDDocNo').data('is-created');
        var bStatus = false;
        if(tAPDDocNo == ""){ // No have data
            bStatus = true;
        }
        return bStatus;
    }catch(err){
        console.log('JCNbAPDIsCreatePage Error: ', err);
    }
}

// Function : Is update page.
// Creator  : 01/03/2022 Wasin
 function JCNbAPDIsUpdatePage(){
    try{
        var tAPDDocNo = $('#oetAPDDocNo').data('is-created');
        var bStatus = false;
        if(!tAPDDocNo == ""){ // Have data
            bStatus = true;
        }
        return bStatus;
    }catch(err){
        console.log('JCNbAPDIsUpdatePage Error: ', err);
    }
}

// Function :  Check Doc Type
// Creator  : 01/03/2022 Wasin
 function JCNbAPDIsDocType(ptDocType){
    try{
        var tAPDDocType = $('#ohdAPDDocType').val();
        var bStatus = false;
        if(ptDocType == "havePdt"){ // ใบลดหนี้แบบมีสินค้า
            if(tAPDDocType == '6'){
                bStatus = true;
            }
        }
        if(ptDocType == "nonePdt"){ // ใบลดหนี้แบบไม่มีสินค้า
            if(tAPDDocType == '7'){
                bStatus = true;
            }
        }
        return bStatus;
    }catch(err){
        console.log('JCNbAPDIsDocType Error: ', err);
    }
}

function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return 'Dupilcate';
        }
    }
    return 'None';
}