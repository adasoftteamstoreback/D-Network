var nStaPNBrowseType = $("#oetPNStaBrowse").val();
var tCallPNBackOption = $("#oetPNCallBackOption").val();

$("document").ready(function () {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxPNNavDefult();

    if (nStaPNBrowseType != 1) {
        JSvCallPagePNList();
    } else {
        JSvCallPagePNAdd();
    }
    
});

/**
 * Function : Set Default Nav
 * Parameters : -
 * Creator : 22/06/2019 Piya
 * Return : -
 * Return Type : -
 */
function JSxPNNavDefult() {
    if (nStaPNBrowseType != 1 || nStaPNBrowseType == undefined) {
        $(".xCNPNVBrowse").hide();
        $(".xCNPNVMaster").show();
        $("#oliPNTitleAdd").hide();
        $("#oliPNTitleEdit").hide();
        $("#odvBtnAddEdit").hide();
        $(".obtChoose").hide();
        $("#odvBtnPNInfo").show();
    } else {
        $("#odvModalBody .xCNPNVMaster").hide();
        $("#odvModalBody .xCNPNVBrowse").show();
        $("#odvModalBody #odvPNMainMenu").removeClass("main-menu");
        $("#odvModalBody #oliPNNavBrowse").css("padding", "2px");
        $("#odvModalBody #odvPNBtnGroup").css("padding", "0");
        $("#odvModalBody .xCNPNBrowseLine").css("padding", "0px 0px");
        $("#odvModalBody .xCNPNBrowseLine").css("border-bottom", "1px solid #e3e3e3");
    }
}

/**
 * Function : Function Show Event Error
 * Parameters : Error Ajax Function
 * Creator : 22/06/2019 Piya
 * Return : Modal Status Error
 * Return Type : view
 */
function JCNxResponseError(jqXHR, textStatus, errorThrown) {
    JCNxCloseLoading();
    var tHtmlError = $(jqXHR.responseText);
    var tMsgError = "<h3 style='font-size:20px;color:red'>";
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

/**
 * Functionality : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
 * Parameters : ptPdtData, ptIsRefPI
 * Creator : 22/06/2019 Piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function FSvPDTAddPdtIntoTableDT(ptPdtData, ptIsRefPI, tIsByScanBarCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        ptIsRefPI           = typeof ptIsRefPI == 'undefined' ? '0' : ptIsRefPI;
        tIsByScanBarCode    = typeof tIsByScanBarCode == 'undefined' ? '0' : tIsByScanBarCode;

        var ptXthDocNoSend = "";
        if (JCNbPNIsUpdatePage()) {
            ptXthDocNoSend = $("#oetPNDocNo").val();
        }
        
        var tSplCode = $('#oetPNSplCode').val();
        
        $.ajax({
            type: "POST",
            url: "docPNAddPdtIntoTableDT",
            data: {
                tDocNo                  : ptXthDocNoSend,
                tSplCode                : tSplCode,
                tIsRefPI                : ptIsRefPI,
                tIsByScanBarCode        : tIsByScanBarCode,
                tBarCodeByScan          : $('#oetPNScanPdtHTML').val(),
                tSplVatType             : JSxPNIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                tPdtData                : ptPdtData,
                tPNOptionAddPdt : $("#ocmPNOptionAddPdt").val() // เพิ่มแถวใหม่ Default 1 : บวกรายการเดิมในรายการ
            },
            cache: false,
            success: function (tResult) {
                if(JCNbPNIsDocType('havePdt')){
                    if(tResult.rtCode == '800'){
                        FSvCMNSetMsgWarningDialog('ไม่พบรายการสินค้า');
                        return;
                    }
                    JSvPNLoadPdtDataTableHtml();
                }
                if(JCNbPNIsDocType('nonePdt')){
                    JSvPNLoadNonePdtDataTableHtml();
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

/**
 * Functionality : Action for approve
 * Parameters : pbIsConfirm
 * Creator : 22/06/2019 Piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSnPNApprove(pbIsConfirm) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        JSxPNAddUpdateAction('approve');
        try {
            if (pbIsConfirm) {
                $("#ohdCardShiftTopUpCardStaPrcDoc").val(2); // Set status for processing approve
                $("#odvPNPopupApv").modal("hide");

                var tDocNo = $("#oetPNDocNo").val();
                var tStaApv = $("#ohdXthStaApv").val();
                var tDocType = $("#ohdPNDocType").val();
                var tBchCode = $("#oetPNBchCode").val();
                var tChkTypeDocRef     = $('#oetPNRefPICodeOld').val().substr(0, 2);
                JCNxOpenLoading();
                
                $.ajax({
                    type: "POST",
                    url: "docPNApprove",
                    data: {
                        tDocNo: tDocNo,
                        tStaApv: tStaApv,
                        tDocType: tDocType,
                        tBchCode:tBchCode,
                        tChkTypeDocRef: tChkTypeDocRef
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
                        var tDocNo = $('#oetPNDocNo').val();
                        JSvCallPagePNEdit(tDocNo);

                        if (oResult.tDocRefType == "DO") {
                            if(JCNbPNIsDocType('havePdt')){
                                JSoPNSubscribeMQ();
                            }
                            
                            if(JCNbPNIsDocType('nonePdt')){
                                var tDocNo = $('#oetPNDocNo').val();
                                JSvCallPagePNEdit(tDocNo);
                            }
                        }


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        JCNxCloseLoading();
                    }
                });
            } else {
                // console.log("StaApvDoc Call Modal");
                $("#odvPNPopupApv").modal("show");
            }
        } catch (err) {
            console.log("JSnPNApprove Error: ", err);
        }
        
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : Action for approve
 * Parameters : pbIsConfirm
 * Creator : 22/06/2019 Piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSoPNSubscribeMQ() {
    // RabbitMQ
    /*===========================================================================*/
    // Document variable
    var tLangCode = $("#ohdLangEdit").val();
    var tUsrBchCode = $("#ohdPNBchCode").val();
    var tUsrApv = $("#ohdPNUsrCode").val();
    var tDocNo = $("#oetPNDocNo").val();
    var tPrefix = "RESPCN";
    var tStaApv = $("#ohdPNStaApv").val();
    var tStaDelMQ = $("#ohdPNStaDelMQ").val();
    var tQName = tPrefix + "_" + tDocNo + "_" + tUsrApv;

    console.log('test');
    console.log(tQName);

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
        ptDocTableName: "TAPTPcHD",
        ptDocFieldDocNo: "FTXphDocNo",
        ptDocFieldStaApv: "FTXphStaPrcStk",
        ptDocFieldStaDelMQ: "FTXphStaDelMQ",
        ptDocStaDelMQ: tStaDelMQ,
        ptDocNo: tDocNo
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit: "JSvCallPagePNEdit",
        tCallPageList: "JSvCallPagePNList"
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

function JSnPNCancel(pbIsConfirm) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var tDocNo = $("#oetPNDocNo").val();

        if (pbIsConfirm) {
            $.ajax({
                type: "POST",
                url: "docPNCancel",
                data: {
                    tDocNo: tDocNo
                },
                cache: false,
                timeout: 5000,
                success: function (tResult) {
                    $("#odvPNPopupCancel").modal("hide");

                    var aResult = $.parseJSON(tResult);
                    if (aResult.nSta == 1) {
                        JSvCallPagePNEdit(tDocNo);
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
            $("#odvPNPopupCancel").modal("show");
        }
    
    }else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : GET Scan BarCode
function JSvPNScanPdtHTML() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
    
        var tBarCode = $("#oetPNScanPdtHTML").val();
        var tSplCode = $("#oetSplCode").val();

        if (tBarCode != "") {
            JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "docPNGetPdtBarCode",
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
                        FSvPNAddPdtIntoTableDT(tPdtCode, tPunCode);

                        $("#oetPNScanPdtHTML").val("");
                        $("#oetPNScanPdtHTML").focus();
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
            $("#oetPNScanPdtHTML").focus();
        }
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
 * Function: Remove DT Temp
 * @param {type} ptSeqNo
 * @param {type} ptPdtCode
 * @returns {undefined}
 */
function JSnPNRemoveDTTemp(ptSeqNo, ptPdtCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var tDocNo      = $("#oetPNDocNo").val();
        var nPage       = $(".xWPagePNPdt .active").text();
        var tBchCode    = $('#oetPNBchCode').val();
        $.ajax({
            type: "POST",
            url: "docPNRemovePdtInDTTmp",
            data: {
                tDocNo      : tDocNo,
                nSeqNo      : ptSeqNo,
                tBchCode    : tBchCode,
                tSplVatType : JSxPNIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                tPdtCode    : ptPdtCode
            },
            cache: false,
            success: function (tResult) {
                if(JCNbPNIsDocType('havePdt')){
                    JSvPNLoadPdtDataTableHtml(nPage);
                }
                if(JCNbPNIsDocType('nonePdt')){
                    JSvPNLoadNonePdtDataTableHtml();
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

/**
 * Function : เพิ่มสินค้า ลง Table DT
 * @param {type} ptPdtCode
 * @param {type} ptPunCode
 * @param {type} pnXthVATInOrEx
 * @returns {undefined}
 */
function FSvPNAddPdtIntoTableDT(ptPdtCode, ptPunCode, pnXthVATInOrEx) {
    
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
    
        var ptOptDocAdd = $("#ohdOptScanSku").val();

        JCNxOpenLoading();

        var ptXthDocNo = $("#oetPNDocNo").val();
        var ptBchCode = $("#ohdSesUsrBchCode").val();

        $.ajax({
            type: "POST",
            url: "docPNAddPdtIntoTableDT",
            data: {
                ptXthDocNo: ptXthDocNo,
                ptBchCode: ptBchCode,
                ptPdtCode: ptPdtCode,
                ptPunCode: ptPunCode,
                ptOptDocAdd: ptOptDocAdd,
                pnXthVATInOrEx: pnXthVATInOrEx
            },
            cache: false,
            success: function (tResult) {
                JMvDOCGetPdtImgScan(ptPdtCode);

                if(JCNbPNIsDocType('havePdt')){
                    JSvPNLoadPdtDataTableHtml();
                }
                if(JCNbPNIsDocType('nonePdt')){
                    JSvPNLoadNonePdtDataTableHtml();
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

/**
 * Function : Get รูปภาพของสินค้า
 * @param {type} ptPdtCode
 * @returns {undefined}
 */
function JMvDOCGetPdtImgScan(ptPdtCode){
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        $.ajax({
            type: "POST",
            url: "DOCGetPdtImg",
            data: { 
                tPdtCode : ptPdtCode
            },
            cache: false,
            success: function(tResult){
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

/**
 * Function : แก้ไขสินค้าใน Table DT
 * @param {type} pnSeqNo
 * @param {type} ptFieldName
 * @param {type} ptValue
 * @param {type} pbIsDelDTDis
 * @returns {undefined}
 */
function FSvPNEditPdtIntoTableDT(pnSeqNo, ptFieldName, ptValue, pbIsDelDTDis) {
    
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var tDocNo = $("#oetPNDocNo").val();
        var tBchCode = $("#ohdSesUsrBchCode").val();

        $.ajax({
            type: "POST",
            url: "docPNEditPdtIntoTableDT",
            data: {
                tDocNo: tDocNo,
                tSplVatType: JSxPNIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                tSeqNo: pnSeqNo,
                tFieldName: ptFieldName,
                tValue: ptValue,
                tIsDelDTDis: (pbIsDelDTDis) ? '1' : '0' // 1: ลบ, 2: ไม่ลบ
            },
            cache: false,
            success: function (tResult) {
                // console.log(tResult);

                if (JCNbPNIsDocType('havePdt')) {
                    JSvPNLoadPdtDataTableHtml(1, false);
                }
                if (JCNbPNIsDocType('nonePdt')) {
                    JSvPNLoadNonePdtDataTableHtml(1, false);
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

/**
 * Functionality : Call Page Add
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvCallPagePNAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        localStorage.removeItem('LocalItemData');
        // $('#odvPNSelectDocTypePopup').modal('show');
        // $('#obtnPNConfirmSelectDocType').one('click', function(){
            // $('#odvPNSelectDocTypePopup').modal('hide');
            // var nDoctype = $('#odvPNSelectDocTypePopup input[name=orbPNSelectDocType]:checked').val();
            var nDoctype = 6;
            // console.log('nDoctype: ', nDoctype);
            $.ajax({
                type: "POST",
                url: "docPNPageAdd",
                data: {
                    nDocType: nDoctype
                },
                cache: false,
                success: function (tResult) {
                    if (nStaPNBrowseType == 1) {
                        $(".xCNPNVMaster").hide();
                        $(".xCNPNVBrowse").show();
                    } else {
                        $(".xCNPNVBrowse").hide();
                        $(".xCNPNVMaster").show();
                        $("#oliPNTitleEdit").hide();
                        $("#oliPNTitleAdd").show();
                        $("#odvBtnPNInfo").hide();
                        $("#odvBtnAddEdit").show();
                        $("#obtPNApprove").hide();
                        $("#obtPNCancel").hide();
                    }
                    $("#odvContentPagePN").html(tResult);

                    // Control Object And Button ปิด เปิด
                    JCNxPNControlObjAndBtn();
                    // Load Pdt Table

                    if ($("#oetBchCode").val() == "") {
                        $("#obtPNBrowseShipAdd").attr("disabled", "disabled");
                    }

                    if(JCNbPNIsDocType('havePdt')){
                        JSvPNLoadPdtDataTableHtml();
                    }
                    if(JCNbPNIsDocType('nonePdt')){
                        JSvPNLoadNonePdtDataTableHtml();
                    }

                    $('#ocbPNAutoGenCode').change(function () {
                        $("#oetPNDocNo").val("");
                        if ($('#ocbPNAutoGenCode').is(':checked')) {
                            $("#oetPNDocNo").attr("readonly", true);
                            $("#oetPNDocNo").attr("onfocus", "this.blur()");
                            $('#ofmAddPN').removeClass('has-error');
                            $('#ofmAddPN .form-group').closest('.form-group').removeClass("has-error");
                            $('#ofmAddPN em').remove();
                        } else {
                            $("#oetPNDocNo").attr("readonly", false);
                            $("#oetPNDocNo").removeAttr("onfocus");
                        }

                    });
                    $("#oetPNDocNo,#oetXthDocDate,#oetXthDocTime").blur(function () {
                        // JSxSetStatusClickPNSubmit(0);
                        JSxValidateFormAddPN();
                        $('#ofmAddPN').submit();
                    });

                    $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        // });
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
 * Functionality : (event) Add/Edit
 * Parameters : form
 * Creator : 22/05/2019 Piya
 * Return : Status Add
 * Return Type : number
 */
function JSnAddEditPN() {
    JSxValidateFormAddPN();
}
            
function JSvCallPagePNList() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        try {
            localStorage.removeItem('LocalItemData');
            $.ajax({
                type: "GET",
                url: "docPNFormSearchList",
                data: {},
                cache: false,
                success: function (tResult) {
                    $("#odvContentPagePN").html(tResult);
                    JSxPNNavDefult();

                    JSvCallPagePNPdtDataTable(); // แสดงข้อมูลใน List
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } catch (err) {
            console.log('JSvCallPagePNList Error: ', err);
        }
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
 * Functionality : Call Product List
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Last Modified : -
 * Return : View
 * Return Type : View
 */
function JSvCallPagePNPdtDataTable(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        JCNxOpenLoading();

        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }

        var oAdvanceSearch = JSoPNGetAdvanceSearchData();

        $.ajax({
            type: "POST",
            url: "docPNDataTable",
            data: {
                tAdvanceSearch: JSON.stringify(oAdvanceSearch),
                nPageCurrent: nPageCurrent
            },
            cache: false,
            success: function (tResult) {
                $("#odvContentPNList").html(tResult);

                JSxPNNavDefult();
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
 * Functionality : Get search data
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Last Modified : -
 * Return : Search data
 * Return Type : Object
 */
function JSoPNGetAdvanceSearchData() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            let oAdvanceSearchData = {
                tSearchAll: $("#oetSearchAll").val(),
                tSearchBchCodeFrom: $("#oetBchCodeFrom").val(),
                tSearchBchCodeTo: $("#oetBchCodeTo").val(),
                tSearchDocDateFrom: $("#oetSearchDocDateFrom").val(),
                tSearchDocDateTo: $("#oetSearchDocDateTo").val(),
                tSearchStaDoc: $("#ocmStaDoc").val(),
                tSearchStaApprove: $("#ocmStaApprove").val(),
                tSearchStaPrcStk: $("#ocmStaPrcStk").val(),
                tSearchDocType: $("#ocmDocType").val(),
                tSearchStaDocAct: $("#ocmStaDocAct").val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("JSoPNGetAdvanceSearchData Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}


/**
 * Functionality : Call Credit Page Edit
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvCallPagePNEdit(ptDocNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        localStorage.removeItem('LocalItemData');
        JCNxOpenLoading();

        JStCMMGetPanalLangSystemHTML("JSvCallPagePNEdit", ptDocNo);

        $.ajax({
            type: "POST",
            url: "docPNPageEdit",
            data: {tDocNo: ptDocNo},
            cache: false,
            success: function (tResult) {
                if (tResult != "") {
                    $("#oliPNTitleAdd").hide();
                    $("#oliPNTitleEdit").show();
                    $("#odvBtnPNInfo").hide();
                    $("#odvBtnAddEdit").show();
                    $("#odvContentPagePN").html(tResult);
                    $("#oetPNDocNo").addClass("xCNDisable");
                    $(".xCNDisable").attr("readonly", true);
                    $(".xCNiConGen").hide();
                    $("#obtPNApprove").show();
                    $("#obtPNCancel").show();

                }

                // Function Load Table Pdt ของ PN
                if(JCNbPNIsDocType('havePdt')){
                    JSvPNLoadPdtDataTableHtml();
                }
                if(JCNbPNIsDocType('nonePdt')){
                    JSvPNLoadNonePdtDataTableHtml();
                }

                // Put Data
                ohdXthCshOrCrd = $("#ohdXthCshOrCrd").val();
                $("#ostXthCshOrCrd option[value='" + ohdXthCshOrCrd + "']").attr("selected", true).trigger("change");

                ohdXthStaRef = $("#ohdXthStaRef").val();
                $("#ostXthStaRef option[value='" + ohdXthStaRef + "']").attr("selected", true).trigger("change");

                // Control Object And Button ปิด เปิด
                JCNxPNControlObjAndBtn();

                JCNxLayoutControll();
                $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

// Function : Control Object And Button ปิด เปิด
function JCNxPNControlObjAndBtn() {
    // Check สถานะอนุมัติ
    var ohdXthStaApv = $("#ohdXthStaApv").val();
    var ohdXthStaDoc = $("#ohdXthStaDoc").val();

    var tDocType     = $("#ohdPNDocType").val();

    if(tDocType == '7'){
        $('#odvPNCondition').css('display','none');
        $('#obtPNBrowseRefPI').attr('disabled',true);
        $('#oetPNXphRefIntDate').attr('disabled',true);
        $('#obtPNRefIntDate').attr('disabled',true);
    }

    // Set Default
    // Btn Cancel
    $("#obtPNCancel").attr("disabled", false);
    // Btn Apv
    $("#obtPNApprove").attr("disabled", false);
    // $(".form-control").attr("disabled", false);
    $(".ocbListItem").attr("disabled", false);
    // $(".xCNBtnBrowseAddOn").attr("disabled", false);
    $(".xCNBtnDateTime").attr("disabled", false);
    $(".xCNDocBrowsePdt").attr("disabled", false).removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").show();
    $("#oetPNSearchPdtHTML").attr("disabled", false);
    $(".xWBtnGrpSaveLeft").attr("disabled", false);
    $(".xWBtnGrpSaveRight").attr("disabled", false);
    $("#oliBtnEditShipAdd").show();
    $("#oliBtnEditTaxAdd").show();

    if (ohdXthStaApv == 1) {
        // Btn Apv
        $("#obtPNApprove").attr("disabled", true);
        // Control input ปิด
        // $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        // $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetPNSearchPdtHTML").attr("disabled", false);
        $(".xWBtnGrpSaveLeft").attr("disabled", true);
        $(".xWBtnGrpSaveRight").attr("disabled", true);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
        $(".xCNBtnDateTime").attr("disabled", true);
    }
    // Check สถานะเอกสาร
    if (ohdXthStaDoc == 3) {
        // Btn Cancel
        $("#obtPNCancel").attr("disabled", true);
        // Btn Apv
        $("#obtPNApprove").attr("disabled", true);
        // Control input ปิด
        // $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        // $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetPNSearchPdtHTML").attr("disabled", false);
        $(".xWBtnGrpSaveLeft").attr("disabled", true);
        $(".xWBtnGrpSaveRight").attr("disabled", true);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
        $(".xCNBtnDateTime").attr("disabled", true);
    }
}

/**
 * Functionality : (event) Delete
 * Parameters : tIDCode รหัส
 * Creator : 22/05/2019 Piya
 * Return : 
 * Return Type : Status Number
 */
function JSnPNDel(tCurrentPage, tDocNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var aData = $("#ohdConfirmIDDelete").val();
        var aTexts = aData.substring(0, aData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;

        if (aDataSplitlength == "1") {
            $("#odvModalDel").modal("show");
            $("#ospConfirmDelete").html("ยืนยันการลบข้อมูล หมายเลข : " + tDocNo);

            $("#osmConfirm").on("click", function (evt) {
                $("#odvModalDel").modal("hide");
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docPNEventDeleteDoc",
                    data: {tDocNo: tDocNo},
                    cache: false,
                    success: function (tResult) {
                        JSvCallPagePNPdtDataTable(tCurrentPage);
                        JSxPNNavDefult();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        }
    
    }else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : (event) Delete
 * Parameters : tIDCode รหัส
 * Creator : 22/05/2019 Piya
 * Return : 
 * Return Type : Status Number
 */
function JSnPNDelChoose() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        $("#odvModalDel").modal("hide");
        JCNxOpenLoading();

        var aData = $("#ohdConfirmIDDelete").val();
        var aTexts = aData.substring(0, aData.length - 2);
        console.log('aTexts: ', aTexts);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;

        var aDocNo = [];
        for ($i = 0; $i < aDataSplitlength; $i++) {
            aDocNo.push(aDataSplit[$i]);
        }
        console.log('aDocNo: ', aDocNo);
        if (aDataSplitlength > 1) {
            localStorage.StaDeleteArray = "1";
            $.ajax({
                type: "POST",
                url: "docPNEventDeleteMultiDoc",
                data: {aDocNo: aDocNo},
                success: function (tResult) {
                    JSvCallPagePNPdtDataTable();
                    JSxPNNavDefult();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            localStorage.StaDeleteArray = "0";
            return false;
        }
        
    }else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : เปลี่ยนหน้า pagenation
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvPNClickPage(ptPage) {
    var nPageCurrent = "";
    switch (ptPage) {
        case "next": //กดปุ่ม Next
            $(".xWBtnNext").addClass("disabled");
            nPageOld = $(".xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew;
            break;
        case "previous": //กดปุ่ม Previous
            nPageOld = $(".xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew;
            break;
        default:
            nPageCurrent = ptPage;
    }
    JSvCallPagePNPdtDataTable(nPageCurrent);
}

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 10/07/2019 Krit(Copter)
//Return: -
//Return Type: -
function JSxShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliPNBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliPNBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliPNBtnDeleteAll").addClass("disabled");
        }
    }
}

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 15/05/2018 wasin
//Return: -
//Return Type: -
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

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 25/02/2019 Napat(Jame)
//Return: -
//Return Type: -
function JSxPNPdtTextinModal() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        } else {
            var tTextSeq = "";
            var tTextPdt = "";
            var tTextDoc = "";
            var tTextPun = "";
            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tTextSeq += aArrayConvert[0][$i].tSeq;
                tTextSeq += " , ";
                tTextPdt += aArrayConvert[0][$i].tPdt;
                tTextPdt += " , ";
                tTextDoc += aArrayConvert[0][$i].tDoc;
                tTextDoc += " , ";
                tTextPun += aArrayConvert[0][$i].tPun;
                tTextPun += " , ";
            }
            $("#ospConfirmDelete").text($("#oetTextComfirmDeleteMulti").val());
            $("#ohdConfirmSeqDelete").val(tTextSeq);
            $("#ohdConfirmPdtDelete").val(tTextPdt);
            $("#ohdConfirmPunDelete").val(tTextPun);
            $("#ohdConfirmDocDelete").val(tTextDoc);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality: Function Chack Value LocalStorage
//Parameters: Event Select List Branch
//Creator: 06/06/2018 Krit
//Return: Duplicate/none
//Return Type: string
function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

/**
 * Functionality : เปลี่ยนหน้า pagenation product table
 * Parameters : Event Click Pagination
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvPNPdtClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWPagePNPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWPagePNPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JCNxOpenLoading();
        if(JCNbPNIsDocType('havePdt')){
            JSvPNLoadPdtDataTableHtml(nPageCurrent);
        }
        if(JCNbPNIsDocType('nonePdt')){
            JSvPNLoadNonePdtDataTableHtml();
        }
        
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : Generate Code Subdistrict
//Parameters : Event Icon Click
//Creator : 07/06/2018 wasin
//Return : Data
//Return Type : String
function JStGeneratePNCode() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var tTableName = "TCNTPdtTwxHD";
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "generateCode",
            data: {tTableName: tTableName},
            cache: false,
            timeout: 0,
            success: function (tResult) {
                console.log(tResult);
                var tData = $.parseJSON(tResult);
                if (tData.rtCode == "1") {
                    console.log(tData);
                    $("#oetPNDocNo").val(tData.rtXthDocNo);
                    $("#oetPNDocNo").addClass("xCNDisable");
                    $(".xCNDisable").attr("readonly", true);
                    //----------Hidden ปุ่ม Gen
                    $(".xCNBtnGenCode").attr("disabled", true);
                    $("#oetXthDocDate").focus();
                    $("#oetXthDocDate").focus();

                    JStCMNCheckDuplicateCodeMaster(
                            "oetPNDocNo",
                            "JSvCallPagePNEdit",
                            "TCNTPdtTwxHD",
                            "FTXthDocNo"
                            );
                } else {
                    $("#oetPNDocNo").val(tData.rtDesc);
                }
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

// Advance Table
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Functionality : Get Html PDT มาแปะ ในหน้า Add แบบมีสินค้า
 * Parameters : pnPage is page, pbUseLoading is use backdrop loading
 * Creator : 21/06/2019 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSvPNLoadPdtDataTableHtml(pnPage, pbUseLoading) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        pbUseLoading = typeof pbUseLoading === 'undefined' ? true : pbUseLoading;
        
        if(pbUseLoading){
            JCNxOpenLoading();
        }
        
        var tSearchAll = $('#oetPNSearchPdtHTML').val();
        var tDocNo, tStaApv, tStaDoc, nPageCurrent;
        if (JCNbPNIsCreatePage()) {
            tDocNo = "";
        } else {
            tDocNo = $("#oetPNDocNo").val();
        }
        
        tStaApv = $("#ohdPNStaApv").val();
        tStaDoc = $("#ohdPNStaDoc").val();

        // เช็ค สินค้าใน table หน้านั้นๆ มีหรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#odvTBodyPNPdt .xWPdtItem").length == 0) {
            if (typeof pnPage !== 'undefined') {
                pnPage = pnPage - 1;
            }
        }

        nPageCurrent = ( (typeof pnPage === 'undefined') || (pnPage === "") || (pnPage <= 0) ) ? "1" : pnPage;
        
        $.ajax({
            type: "POST",
            url: "docPNPdtAdvanceTableLoadData",
            data: {
                tSearchAll: tSearchAll,
                tDocNo: tDocNo,
                tSplVatType: JSxPNIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก,
                tStaApv: tStaApv,
                tStaDoc: tStaDoc,
                nPageCurrent: nPageCurrent
            },
            cache: false,
            Timeout: 0,
            success: function (oResult) {
                // console.log('JSvPNLoadPdtDataTableHtml: ', oResult);
                try{
                    $("#odvPNPdtTablePanal").html(oResult.tTalbleHtml);
                    JSxPNSetEndOfBill(oResult.aEndOfBill);
                    // JSvPNLoadVatTableHtml(); // Load Vat Table
                    
                    if(pbUseLoading){
                        JCNxCloseLoading();
                    }
                }catch(err){
                    console.log('JSvPNLoadPdtDataTableHtml Error: ', err);
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
    
// Function : Get Html PDT มาแปะ ในหน้า Add แบบไม่มีสินค้า
// Create : 04/04/2019 Krit(Copter)
function JSvPNLoadNonePdtDataTableHtml(pnPage, pbUseLoading) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        pbUseLoading = typeof pbUseLoading === 'undefined' ? true : pbUseLoading;
        
        if(pbUseLoading){
            JCNxOpenLoading();
        }
        
        var tSearchAll = $('#oetPNSearchPdtHTML').val();
        var tDocNo, tStaApv, tStaDoc, nPageCurrent;
        if (JCNbPNIsCreatePage()) {
            tDocNo = "";
        } else {
            tDocNo = $("#oetPNDocNo").val();
        }
        
        tStaApv = $("#ohdPNStaApv").val();
        tStaDoc = $("#ohdPNStaDoc").val();

        // เช็ค สินค้าใน table หน้านั้นๆ มีหรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#odvTBodyPNPdt .xWPdtItem").length == 0) {
            if (typeof pnPage !== 'undefined') {
                pnPage = pnPage - 1;
            }
        }

        nPageCurrent = ( (typeof pnPage === 'undefined') || (pnPage === "") || (pnPage <= 0) ) ? "1" : pnPage;

        $.ajax({
            type: "POST",
            url: "docPNNonePdtAdvanceTableLoadData",
            data: {
                tSearchAll: tSearchAll,
                tDocNo: tDocNo,
                tSplVatType: JSxPNIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก,
                tStaApv: tStaApv,
                tStaDoc: tStaDoc,
                nPageCurrent: nPageCurrent
            },
            cache: false,
            Timeout: 0,
            success: function (oResult) {
                try{
                    console.log(oResult);
                    $("#odvPNPdtTablePanal").html(oResult.tTalbleHtml);
                    // JSxPNSetEndOfBill(oResult.aEndOfBill);
                    
                    if(pbUseLoading){
                        JCNxCloseLoading();
                    }
                }catch(err){
                    console.log('JSvPNLoadPdtDataTableHtml Error: ', err);
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

function JSxOpenColumnFormSet() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        $.ajax({
            type: "POST",
            url: "docPNAdvanceTableShowColList",
            data: {},
            cache: false,
            Timeout: 0,
            success: function (tResult) {
                $("#odvShowOrderColumn").modal({show: true});
                $("#odvOderDetailShowColumn").html(tResult);
                //JSCNAdjustTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    
    }else {
        JCNxShowMsgSessionExpired();
    }
}

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

        // alert(aColShowAllList);

        var nStaSetDef;
        if ($("#ocbSetToDef").is(":checked")) {
            nStaSetDef = 1;
        } else {
            nStaSetDef = 0;
        }
        // alert(aColShowSet);

        $.ajax({
            type: "POST",
            url: "docPNAdvanceTableShowColSave",
            data: {
                aColShowSet: aColShowSet,
                nStaSetDef: nStaSetDef,
                aColShowAllList: aColShowAllList,
                aColumnLabelName: aColumnLabelName
            },
            cache: false,
            Timeout: 0,
            success: function (tResult) {
                $("#odvShowOrderColumn").modal("hide");
                $(".modal-backdrop").remove();
                // Function Gen Table Pdt ของ PN

                if(JCNbPNIsDocType('havePdt')){
                    JSvPNLoadPdtDataTableHtml();
                }
                if(JCNbPNIsDocType('nonePdt')){
                    JSvPNLoadNonePdtDataTableHtml();
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

// ปรับ Value ใน Input หลัวจาก กรอก เสร็จ
function JSxPNAdjInputFormat(ptInputID) {
    cVal = $("#" + ptInputID).val();
    cVal = accounting.toFixed(cVal, nOptDecimalShow);
    $("#" + ptInputID).val(cVal);
}

/**
 * Functionality : Is create page.
 * Parameters : -
 * Creator : 22/05/2019 piya
 * Last Modified : -
 * Return : Status true is create page
 * Return Type : Boolean
 */
function JCNbPNIsCreatePage(){
    try{
        var tPNDocNo = $('#oetPNDocNo').data('is-created');
        var bStatus = false;
        if(tPNDocNo == ""){ // No have data
            bStatus = true;
        }
        return bStatus;
    }catch(err){
        console.log('JCNbPNIsCreatePage Error: ', err);
    }
}

/**
 * Functionality : Is update page.
 * Parameters : -
 * Creator : 22/05/2019 piya
 * Last Modified : -
 * Return : Status true is create page
 * Return Type : Boolean
 */
function JCNbPNIsUpdatePage(){
    try{
        var tPNDocNo = $('#oetPNDocNo').data('is-created');
        var bStatus = false;
        if(!tPNDocNo == ""){ // Have data
            bStatus = true;
        }
        return bStatus;
    }catch(err){
        console.log('JCNbPNIsUpdatePage Error: ', err);
    }
}

/**
 * Functionality : Check Doc Type
 * Parameters : -
 * Creator : 24/06/2019 piya
 * Last Modified : -
 * Return : Status true is doc type match
 * Return Type : Boolean
 */
function JCNbPNIsDocType(ptDocType){
    try{
        var tPNDocType = $('#ohdPNDocType').val();
        var bStatus = false;
        if(ptDocType == "havePdt"){ // ใบลดหนี้แบบมีสินค้า
            if(tPNDocType == '6'){
                bStatus = true;
            }
        }
        if(ptDocType == "nonePdt"){ // ใบลดหนี้แบบไม่มีสินค้า
            if(tPNDocType == '7'){
                bStatus = true;
            }
        }
        return bStatus;
    }catch(err){
        console.log('JCNbPNIsDocType Error: ', err);
    }
}
































































