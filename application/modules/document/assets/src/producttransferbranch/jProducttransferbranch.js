var nStaTBBrowseType = $("#oetTBStaBrowse").val();
var tCallTBBackOption = $("#oetTBCallBackOption").val();

$("document").ready(function() {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxTBNavDefult();
    $("#obtTBSubmit").removeAttr("disabled");


    switch (nStaTBBrowseType) {
        case '2':
            var tAgnCode = $('#oetTBJumpAgnCode').val();
            var tBchCode = $('#oetTBJumpBchCode').val();
            var tDocNo = $('#oetTBJumpDocNo').val();
            JSvCallPageTBEdit(tDocNo);
            break;
        default:
            JSvCallPageTBList();
    }

});

//Functionality: Save Pdt And Calculate Field
//Parameters: Event Proporty
//Creator: 04/04/2019 Krit(Copter)
//Return:  Cpntroll input And Call Function Edit
function JSnSaveDTEdit(paEvent) {

    var tEditSeqNo = $(paEvent.Element)
        .parents()
        .eq(2)
        .attr("data-seqno");
    var aField = [];
    var aValue = [];

    $(".xWValueEditInLine" + tEditSeqNo).each(function(index) {
        tValue = $(this).val();
        tField = $(this).attr("data-field");
        $(".xWShowValue" + tField + tEditSeqNo).text(tValue);
        aField.push(tField);
        aValue.push(tValue);
    });
    FSvTBEditPdtIntoTableDT(tEditSeqNo, aField, aValue);

}

//Functionality: Del Pdt In Row Html And Del in DB
//Parameters: Event Proporty
//Creator: 04/04/2019 Krit(Copter)
//Return:  Call function Delete
function JSnRemoveDTRow(ele) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var tVal = $(ele)
            .parent()
            .parent()
            .parent()
            .attr("data-pdtcode");
        var tSeqno = $(ele)
            .parent()
            .parent()
            .parent()
            .attr("data-seqno");
        $(ele)
            .parent()
            .parent()
            .parent()
            .remove();

        JSnTBRemoveDTTemp(tSeqno, tVal);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxTBNavDefult() {
    if (nStaTBBrowseType != 1 || nStaTBBrowseType == undefined) {
        $(".xCNTBVBrowse").hide();
        $(".xCNTBVMaster").show();
        $("#oliTBTitleAdd").hide();
        $("#oliTBTitleEdit").hide();
        $("#odvBtnAddEdit").hide();
        $(".obtChoose").hide();
        $("#odvBtnTBInfo").show();
    } else {
        $("#odvModalBody .xCNTBVMaster").hide();
        $("#odvModalBody .xCNTBVBrowse").show();
        $("#odvModalBody #odvTBMainMenu").removeClass("main-menu");
        $("#odvModalBody #oliTBNavBrowse").css("padding", "2px");
        $("#odvModalBody #odvTBBtnGroup").css("padding", "0");
        $("#odvModalBody .xCNTBBrowseLine").css("padding", "0px 0px");
        $("#odvModalBody .xCNTBBrowseLine").css(
            "border-bottom",
            "1px solid #e3e3e3"
        );
    }
}

/*
function : Function Browse Pdt
Parameters : Error Ajax Function 
Creator : 02/04/2019 Krit(Copter)
Return : Modal Status Error
Return Type : view
*/
function JCNvTBBrowsePdt() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                Qualitysearch: [
                    "NAMEPDT",
                    "CODEPDT"
                ],
                PriceType: ["Cost", "tCN_Cost", "Company", "1"],
                //'PriceType'       : ['Pricesell'],
                //'SelectTier'      : ['PDT'],
                SelectTier: ["Barcode"],
                //'Elementreturn'   : ['oetInputTestValue','oetInputTestName'],
                ShowCountRecord: 10,
                NextFunc: "FSvPINextFuncB4SelPDT",
                ReturnType: "M",
                SPL: ["", ""],
                BCH: [$("#ohdTbxBchCode").val(), $("#ohdTbxBchCode").val()],
                SHP: [$('#oetTBShopCodeFrom').val(), $('#oetTBShopCodeFrom').val()],
                'aAlwPdtType': ['T1', 'T3', 'T4', 'T5', 'T6', 'S2', 'S3', 'S4'],
                'Where': [" AND Products.FTPdtStkControl = 1"]
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                // $(".modal.fade:not(#odvTBBrowseShipAdd,#odvModalDOCPDT,#odvModalWanning,#odvModalInfoMessage,#odvShowOrderColumn,#odvTBPopupApv,#odvModalDelPdtTB)").remove();
                $("#odvModalDOCPDT").modal({ backdrop: "static", keyboard: false });
                $("#odvModalDOCPDT").modal({ show: true });

                //remove localstorage
                localStorage.removeItem("TBX_LocalItemDataDelDtTemp");
                $("#odvModalsectionBodyPDT").html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality  : Function Chack Value LocalStorage
//Functionality  : Event Select List Branch
//Creator        : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
//Return         : Duplicate/none
//Return Type    : string
function JStTBXFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

//Function : Search Pdt
function JSvDOCSearchPdtHTML() {
    var value = $("#oetSearchPdtHTML")
        .val()
        .toLowerCase();
    $("#otbTbxDocPdtAdvTableList tbody tr ").filter(function() {
        tText = $(this).toggle(
            $(this)
            .text()
            .toLowerCase()
            .indexOf(value) > -1
        );
    });
}

//Get ข้อมูล Address มาใส่ modal แบบ Array
function JSvTBGetShipAddData(pTAddressInfor) {
    if (pTAddressInfor !== "NULL") {
        var aData = JSON.parse(pTAddressInfor);
        $("#ospShipAddAddV1No").text(aData[1]);
        $("#ospShipAddV1Soi").text(aData[2]);
        $("#ospShipAddV1Village").text(aData[3]);
        $("#ospShipAddV1Road").text(aData[4]);
        $("#ospShipAddV1SubDist").text(aData[5]);
        $("#ospShipAddV1DstCode").text(aData[6]);
        $("#ospShipAddV1PvnCode").text(aData[7]);
        $("#ospShipAddV1PostCode").text(aData[8]);
        $("#ospShipAddV2Desc1").text(aData[9]);
        $("#ospShipAddV2Desc2").text(aData[10]);
    } else {
        $("#ospShipAddAddV1No").text("-");
        $("#ospShipAddV1Soi").text("-");
        $("#ospShipAddV1Village").text("-");
        $("#ospShipAddV1Road").text("-");
        $("#ospShipAddV1SubDist").text("-");
        $("#ospShipAddV1DstCode").text("-");
        $("#ospShipAddV1PvnCode").text("-");
        $("#ospShipAddV1PostCode").text("-");
        $("#ospShipAddV2Desc1").text("-");
        $("#ospShipAddV2Desc2").text("-");
    }
}

//อนุมัติเอกสาร
function JSnTBApprove(pbIsConfirm) {

    // JSxSubmitEventByButton('approve');
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                $("#ohdCardShiftTopUpCardStaPrcDoc").val(2); // Set status for processing approve
                $("#odvTBPopupApv").modal("hide");

                var tXthDocNo = $("#oetXthDocNo").val();
                var tXthStaApv = $("#ohdXthStaApv").val();
                var tXthBchCode = $("#ohdTbxBchCode").val();

                $.ajax({
                    type: "POST",
                    url: "TBXCheckProductWahouse",
                    data: {
                        'tDocNo': tXthDocNo,
                        'tBchCode': $("#oetTBBchCodeFrom").val(),
                        'tWahCode': $('#ohdWahCodeStart').val()
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        var aReturnData = JSON.parse(tResult);
                        if (aReturnData['nStaEvent'] == 1 || aReturnData['nStaEvent'] == 400 || aReturnData['tChkTsysConfig'] == '2') {
                            $.ajax({
                                type: "POST",
                                url: "TBXApprove",
                                data: {
                                    tXthDocNo: tXthDocNo,
                                    tXthStaApv: tXthStaApv,
                                    tXthBchCode: tXthBchCode
                                },
                                cache: false,
                                timeout: 0,
                                success: function(tResult) {
                                    tResult = tResult.replace("\r\n", "");
                                    let oResult = JSON.parse(tResult);
                                    if (oResult["nStaEvent"] == "900") {
                                        FSvCMNSetMsgErrorDialog(oResult.tStaMessg);
                                    } else {
                                        JSoTBSubscribeMQ('1');
                                    }

                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        } else {
                            var tMessageError = aReturnData['tStaMessg'];
                            var aItemFail = aReturnData['aItemFail'];
                            //เอาชื่อสินค้าที่ไม่พอ ไปโชว์
                            var tTextStockFail = '';
                            var tTextStockFailShow = '';
                            for (var i = 0; i < aItemFail.length; i++) {
                                tTextStockFail += '(' + aItemFail[i][0] + ')' + ' - ' + aItemFail[i][1] + ' [ร้องขอ : ' + aItemFail[i][2] + ' ชิ้น , พบในคลัง : ' + aItemFail[i][3] + ' ชิ้น] <br>';
                            }
                            tTextStockFailShow = '<p style="font-weight: bold;">' + tTextStockFail + '</p>';

                            FSvCMNSetMsgErrorDialog(tMessageError + '<br>' + tTextStockFailShow);
                            JSvCallPageTBEdit(tXthDocNo);
                            JCNxCloseLoading();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                $("#odvTBPopupApv").modal("show");
            }
        } catch (err) {
            console.log("JSnTBApprove Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//หลังอนุมัติ
function JSoTBSubscribeMQ(ptType) {

    // Document variable
    var tLangCode = $("#ohdLangEdit").val();
    var tUsrBchCode = $("#ohdBchCode").val();
    var tUsrApv = $("#oetXthApvCodeUsrLogin").val();
    var tDocNo = $("#oetXthDocNo").val();
    var tPrefix = "RESTBX";
    var tStaApv = $("#ohdXthStaApv").val();
    var tStaDelMQ = $("#ohdXthStaDelMQ").val();
    var tQName = tPrefix + "_" + tDocNo + "_" + tUsrApv;

    if ((ptType == '2' && tStaApv != "1" && tStaApv != "") || ptType == '1') {

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
            ptDocTableName: "TCNTPdtTbxHD",
            ptDocFieldDocNo: "FTXthDocNo",
            ptDocFieldStaApv: "FTXthStaPrcStk",
            ptDocFieldStaDelMQ: "FTXthStaDelMQ",
            ptDocStaDelMQ: tStaDelMQ,
            ptDocNo: tDocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit: "JSvCallPageTBEdit",
            tCallPageList: "JSvCallPageTBList"
        };

        //Check Show Progress %
        FSxCMNRabbitMQMessage(
            poDocConfig,
            poMqConfig,
            poUpdateStaDelQnameParams,
            poCallback
        );

    }

}

function JSnTBCancel(pbIsConfirm) {
    tXthDocNo = $("#oetXthDocNo").val();

    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "TBXCancel",
            data: {
                tXthDocNo: tXthDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvTBPopupCancel").modal("hide");

                aResult = $.parseJSON(tResult);
                if (aResult.nSta == 1) {
                    JSvCallPageTBEdit(tXthDocNo);
                } else {
                    JCNxCloseLoading();
                    tMsgBody = aResult.tMsg;
                    FSvCMNSetMsgWarningDialog(tMsgBody);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        //Check Status Approve for Control Msg In Modal
        nStaApv = $("#ohdXthStaApv").val();

        if (nStaApv == 1) {
            $("#obpMsgApv").show();
        } else {
            $("#obpMsgApv").hide();
        }

        $("#odvTBPopupCancel").modal("show");
    }
}

//พิมพ์เอกสาร
function JSxTFXPrintFormReport() {

}

//Function : GET Scan BarCode
function JSxTBScanPdtHTML() {
    tBarCode = $("#oetScanPdtHTML").val();

    if (tBarCode != "") {
        JCNxOpenLoading();

        $.ajax({
            type: "POST",
            url: "TBXGetPdtBarCode",
            data: {
                tBarCode: tBarCode
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aResult = $.parseJSON(oResult);
                if (aResult.aData != 0) {
                    var aDataPdt = $.parseJSON(aResult.aData);
                    var ptXthDocNoSend = "";
                    if ($("#ohdTBRoute").val() == "TBXEventEdit") {
                        ptXthDocNoSend = $("#oetXthDocNo").val();
                    }

                    $.ajax({
                        type: "POST",
                        url: "TBXAddPdtIntoTableDT",
                        data: {
                            ptXthBchCode: $("#ohdTbxBchCode").val(),
                            ptXthDocNo: ptXthDocNoSend,
                            paScanPdtData: aDataPdt,
                            pnTBOptionAddPdt: $("#ocmTBOptionAddPdt").val()
                        },
                        cache: false,
                        timeout: 0,
                        success: function(oResult) {
                            // console.log(oResult);
                            JSvTBLoadPdtDataTableHtml();
                            $("#oetScanPdtHTML").val("");
                            $("#oetScanPdtHTML").focus();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });

                } else {
                    JCNxCloseLoading();
                    FSvCMNSetMsgWarningDialog(aResult.tMsg);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

    } else {
        $("#oetScanPdtHTML").focus();
    }
}

function JSnTBRemoveDTTemp(ptSeqno, ptPdtCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        ptXthDocNo = $("#oetXthDocNo").val();
        nPage = $(".xWPageTBPdt .active").text();

        $.ajax({
            type: "POST",
            url: "TBXRemovePdtInDTTmp",
            data: {
                ptXthDocNo: ptXthDocNo,
                ptSeqno: ptSeqno,
                ptPdtCode: ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JSvTBLoadPdtDataTableHtml(nPage);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
function FSvTBEditPdtIntoTableDT(ptEditSeqNo, paField, paValue) {

    // ถ้าเป็นหน้าจอ add ใส่ส่ง docno = ''
    var ptXthDocNo = "";
    if ($('#ohdTBRoute').val() == "TBXEventEdit") {
        ptXthDocNo = $("#oetXthDocNo").val();
    }
    // var ptBchCode   = $("#ohdBchCode").val();

    $.ajax({
        type: "POST",
        url: "TBXEditPdtIntoTableDT",
        data: {
            ptXthDocNo: ptXthDocNo,
            ptEditSeqNo: ptEditSeqNo,
            paField: paField,
            paValue: paValue
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            // console.log(tResult);

            JSvTBLoadPdtDataTableHtml();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Call Purchase Page Add
function JSvCallPageTBAdd() {
    $.ajax({
        type: "POST",
        url: "TBXPageAdd",
        data: {},
        cache: false,
        timeout: 0,
        success: function(oResult) {

            var aResult = JSON.parse(oResult);

            if (nStaTBBrowseType == 1) {
                $(".xCNTBVMaster").hide();
                $(".xCNTBVBrowse").show();
            } else {
                $(".xCNTBVBrowse").hide();
                $(".xCNTBVMaster").show();
                $("#oliTBTitleEdit").hide();
                $("#oliTBTitleAdd").show();
                $("#odvBtnTBInfo").hide();
                $("#odvBtnAddEdit").show();
                $("#obtTBApprove").hide();
                $("#obtTFBchPrint").hide();
                $("#obtTBCancel").hide();
            }
            $("#odvContentPageTB").html(aResult['tHTML']);
            // Control Object And Button ปิด เปิด
            JCNxTBControlObjAndBtn();
            //Load Pdt Table


            if ($("#oetBchCode").val() == "") {
                $("#obtTBBrowseShipAdd").attr("disabled", "disabled");


            }
            JSvTBLoadPdtDataTableHtml();
            $('#ocbTBAutoGenCode').change(function() {
                $("#oetXthDocNo").val("");
                if ($('#ocbTBAutoGenCode').is(':checked')) {
                    $("#oetXthDocNo").attr("readonly", true);
                    $("#oetXthDocNo").attr("onfocus", "this.blur()");
                    $('#ofmAddTB').removeClass('has-error');
                    $('#ofmAddTB .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmAddTB em').remove();
                } else {
                    $("#oetXthDocNo").attr("readonly", false);
                    $("#oetXthDocNo").removeAttr("onfocus");
                }

            });
            $("#oetXthDocNo,#oetXthDocDate,#oetXthDocTime").blur(function() {
                JSxSetStatusClickTBSubmit(0);
                JSxValidateFormAddTB();
                $('#ofmAddTB').submit();
            });

            $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");
            // $("#obtDocBrowsePdt.disabled").attr("disabled","disabled");
            // $("#obtDocBrowsePdt").css("opacity","0.4");
            // $("#obtDocBrowsePdt").css("cursor","not-allowed");

            // ถ้าเป็น User ร้านค้า ให้ปิด
            if (aResult['tUsrLevel'] != "SHP") {
                $('#obtTBBrowseShp').attr('disabled', true);
                // $('#obtTBBrowseWahStart').attr('disabled',true);
            } else {
                $('#obtTBBrowseMerFrom').attr('disabled', true);
                $('#obtTBBrowseShp').attr('disabled', true);
            }

            $('#obtTBBrowseWahTo').attr('disabled', true);


        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : main validate form (validate ขั้นที่ 1 ตรวจสอบทั่วไป)
function JSxValidateFormAddTB() {
    if ($("#ohdCheckTBClearValidate").val() != 0) {
        $('#ofmAddTB').validate().destroy();
    }

    $('#ofmAddTB').validate({
        focusInvalid: false,
        onclick: false,
        onfocusout: false,
        onkeyup: false,
        rules: {
            oetXthDocNo: {
                "required": {
                    depends: function(oElement) {
                        if ($("#ohdTBRoute").val() == "TBXEventAdd") {
                            if ($('#ocbTBAutoGenCode').is(':checked')) {
                                return false;
                            } else {

                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            },
            oetXthDocDate: {
                "required": true
            },
            oetXthDocTime: {
                "required": true
            },
            oetWahNameStart: {
                "required": true
            },
            oetTBBchNameTo: {
                "required": true
            },
            oetTBWahNameTo: {
                "required": true
            }
        },
        messages: {
            oetXthDocNo: {
                "required": $('#oetXthDocNo').attr('data-validate-required')
            },
            oetXthDocDate: {
                "required": $('#oetXthDocDate').attr('data-validate-required')
            },
            oetXthDocTime: {
                "required": $('#oetXthDocTime').attr('data-validate-required')
            },
            oetWahNameStart: {
                "required": $('#oetWahNameStart').attr('data-validate-required')
            },
            oetTBBchNameTo: {
                "required": $('#oetTBBchNameTo').attr('data-validate-required')
            },
            oetTBWahNameTo: {
                "required": $('#oetTBWahNameTo').attr('data-validate-required')
            }
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            error.addClass("help-block");
            if (element.prop("type") === "checkbox") {
                error.appendTo(element.parent("label"));
            } else {
                var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                if (tCheck == 0) {
                    error.appendTo(element.closest('.form-group')).trigger('change');
                }
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
        },
        invalidHandler: function(event, validator) {
            if ($("#ohdCheckTBSubmitByButton").val() == 1) {
                FSvCMNSetMsgWarningDialog("<p>โปรดระบุข้อมูลให้สมบูรณ์</p>");
            }
        },
        submitHandler: function(form) {
            if (!$('#ocbTBAutoGenCode').is(':checked')) {
                JSxValidateTBCodeDublicate();
            } else {
                if ($("#ohdCheckTBSubmitByButton").val() == 1) {
                    JSxSubmitEventByButton();
                }
            }
        }
    });
    if ($("#ohdCheckTBClearValidate").val() != 0) {
        $("#ofmAddTB").submit();
        $("#ohdCheckTBClearValidate").val(0);
    }
}

//Functionality : validate TB Code (validate ขั้นที่ 2 ตรวจสอบรหัสเอกสาร)
function JSxValidateTBCodeDublicate() {
    $.ajax({
        type: "POST",
        url: "CheckInputGenCode",
        data: {
            tTableName: "TCNTPdtTbxHD",
            tFieldName: "FTXthDocNo",
            tCode: $("#oetXthDocNo").val()
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            var aResult = JSON.parse(tResult);
            $("#ohdCheckDuplicateTB").val(aResult["rtCode"]);
            if ($("#ohdCheckTBClearValidate").val() != 1) {
                $('#ofmAddTB').validate().destroy();
            }
            $.validator.addMethod('dublicateCode', function(value, element) {
                if ($("#ohdTBRoute").val() == "TBXEventAdd") {
                    if ($('#ocbTBAutoGenCode').is(':checked')) {
                        return true;
                    } else {
                        if ($("#ohdCheckDuplicateTB").val() == 1) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                } else {
                    return true;
                }
            });
            $('#ofmAddTB').validate({
                focusInvalid: false,
                onclick: false,
                onfocusout: false,
                onkeyup: false,
                rules: {
                    oetXthDocNo: {
                        "dublicateCode": {}
                    }
                },
                messages: {
                    oetXthDocNo: {
                        "dublicateCode": "ไม่สามารถใช้รหัสเอกสารนี้ได้"
                    }
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
                    error.addClass("help-block");
                    if (element.prop("type") === "checkbox") {
                        error.appendTo(element.parent("label"));
                    } else {
                        var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                        if (tCheck == 0) {
                            error.appendTo(element.closest('.form-group')).trigger('change');
                        }
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass("has-error");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').removeClass("has-error");
                },
                invalidHandler: function(event, validator) {
                    if ($("#ohdCheckTBSubmitByButton").val() == 1) {
                        FSvCMNSetMsgWarningDialog("<p>โปรดระบุข้อมูลให้สมบูรณ์</p>");
                    }
                },
                submitHandler: function(form) {
                    if ($("#ohdCheckTBSubmitByButton").val() == 1) {
                        JSxSubmitEventByButton();
                    }
                }
            });
            if ($("#ohdCheckTBClearValidate").val() != 1) {
                $("#ofmAddTB").submit();
                $("#ohdCheckTBClearValidate").val(1);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : function submit by submit button only (ส่งข้อมูลที่ผ่านการ validate ไปบันทึกฐานข้อมูล)
function JSxSubmitEventByButton(ptType = '') {
    var tDocNoSend = ""
    if ($("#ohdTBRoute").val() != "TBXEventAdd") {
        tDocNoSend = $("#oetXthDocNo").val();
    }
    $('#obtTBSubmit').attr('disabled', true);
    $.ajax({
        type: "POST",
        url: "TBXCheckPdtTmpForTransfer",
        data: { tDocNo: tDocNoSend },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            // console.log($("#ofmAddTB").serializeArray());
            var bReturn = JSON.parse(tResult);
            if (bReturn) {
                $.ajax({
                    type: "POST",
                    url: $("#ohdTBRoute").val(),
                    data: $("#ofmAddTB").serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        if (ptType == 'approve') {

                        } else {
                            if (nStaTBBrowseType != 1) {
                                var aReturn = JSON.parse(tResult);
                                if (aReturn["nStaEvent"] == 1) {
                                    if (
                                        aReturn["nStaCallBack"] == "1" ||
                                        aReturn["nStaCallBack"] == null
                                    ) {
                                        JSvCallPageTBEdit(aReturn["tCodeReturn"]);
                                    } else if (aReturn["nStaCallBack"] == "2") {
                                        JSvCallPageTBAdd();
                                    } else if (aReturn["nStaCallBack"] == "3") {
                                        JSvCallPageTBList();
                                    }
                                } else {
                                    tMsgBody = aReturn["tStaMessg"];
                                    FSvCMNSetMsgWarningDialog(tMsgBody);
                                }
                            }
                        }
                        $("#obtTBSubmit").removeAttr("disabled");
                        //else {
                        //   JCNxBrowseData(tCallTBBackOption);
                        // }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        $("#obtTBSubmit").removeAttr("disabled");
                    }
                });
            } else {
                FSvCMNSetMsgWarningDialog("<p>โปรดระบุสินค้าที่ท่านต้องการทำการโอน</p>");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : เซ็ตค่าเพื่อให้รู้ว่าตอนนี้กดปุ่มบันทึกหลักจริงๆ (เพราะมีการซัมมิทฟอร์มแต่ไม่บันทึกเพื่อให้เกิด validate ใน on blur)
function JSxSetStatusClickTBSubmit(pnStatus) {
    $("#ohdCheckTBSubmitByButton").val(pnStatus);
}

//Functionality : (event) Add/Edit
function JSnAddEditTB() {
    JSxValidateFormAddTB();
}

function JSvCallPageTBList() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $.ajax({
            type: "GET",
            url: "TBXFormSearchList",
            data: {},
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvContentPageTB").html(tResult);
                JSxTBNavDefult();

                JSvCallPageTBPdtDataTable(); //แสดงข้อมูลใน List
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSvCallPageTBPdtDataTable(pnPage) {
    JCNxOpenLoading();

    var nPageCurrent = pnPage;
    if (nPageCurrent == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }

    var oAdvanceSearch = JSoTBGetAdvanceSearchData();

    $.ajax({
        type: "POST",
        url: "TBXDataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvTBContentList").html(tResult);

            JSxTBNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Get search data
function JSoTBGetAdvanceSearchData() {
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
                tSearchStaDocAct: $("#ocmStaDocAct").val(),
                tSearchStaPrcStk: $("#ocmStaPrcStk").val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("JSoTBGetAdvanceSearchData Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : Call Credit Page Edit
function JSvCallPageTBEdit(ptXthDocNo) {
    JCNxOpenLoading();

    JStCMMGetPanalLangSystemHTML("JSvCallPageTBEdit", ptXthDocNo);

    $.ajax({
        type: "POST",
        url: "TBXPageEdit",
        data: { ptXthDocNo: ptXthDocNo },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $("#oliTBTitleAdd").hide();
                $("#oliTBTitleEdit").show();
                $("#odvBtnTBInfo").hide();
                $("#odvBtnAddEdit").show();
                $("#odvContentPageTB").html(tResult);
                $("#oetXthDocNo").addClass("xCNDisable");
                $(".xCNDisable").attr("readonly", true);
                $(".xCNiConGen").hide();
                $("#obtTBApprove").show();
                $("#obtTFBchPrint").show();
                $("#obtTBCancel").show();

            }

            //Control Event Button
            if ($("#ohdTBAutStaEdit").val() == 0) {
                $(".xCNUplodeImage").hide();
                $(".xCNIconBrowse").hide();
                $(".xCNEditRowBtn").hide();
                $("select").prop("disabled", true);
                $("input").attr("disabled", true);
            } else {
                $(".xCNUplodeImage").show();
                $(".xCNIconBrowse").show();
                $(".xCNEditRowBtn").show();
                $("select").prop("disabled", false);
                $("input").attr("disabled", false);
            }
            //Control Event Button

            //Function Load Table Pdt ของ TB
            JSvTBLoadPdtDataTableHtml();

            //Put Data
            ohdXthCshOrCrd = $("#ohdXthCshOrCrd").val();
            $("#ostXthCshOrCrd option[value='" + ohdXthCshOrCrd + "']")
                .attr("selected", true)
                .trigger("change");

            // ohdXthStaRef = $("#ohdXthStaRef").val();
            // $("#ostXthStaRef option[value='" + ohdXthStaRef + "']")
            //   .attr("selected", true)
            //   .trigger("change");

            // Control Object And Button ปิด เปิด
            JCNxTBControlObjAndBtn();

            JCNxLayoutControll();
            $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");

        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Function : Control Object And Button ปิด เปิด
function JCNxTBControlObjAndBtn() {
    //Check สถานะอนุมัติ
    ohdXthStaApv = $("#ohdXthStaApv").val();
    ohdXthStaDoc = $("#ohdXthStaDoc").val();

    //Set Default
    //Btn Cancel
    $("#obtTBCancel").attr("disabled", false);
    //Btn Apv
    $("#obtTBApprove").attr("disabled", false);
    $("#obtTFBchPrint").attr("disabled", false);
    $(".form-control").attr("disabled", false);
    $(".ocbListItem").attr("disabled", false);
    $(".xCNBtnBrowseAddOn").attr("disabled", false);
    $(".xCNBtnDateTime").attr("disabled", false);
    $(".xCNDocBrowsePdt")
        .attr("disabled", false)
        .removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").show();
    $("#oetSearchPdtHTML").attr("disabled", false);
    $(".xWBtnGrpSaveLeft").show(); // attr("disabled", false);
    $(".xWBtnGrpSaveRight").show(); // attr("disabled", false);
    $("#oliBtnEditShipAdd").show();
    $("#oliBtnEditTaxAdd").show();

    if (ohdXthStaApv == 1) {
        //Btn Apv
        $("#obtTBApprove").hide(); // attr("disabled", true);
        $("#obtTFBchPrint").show(); // attr("disabled", true);
        $("#obtTBCancel").hide(); // attr("disabled", true);
        //Control input ปิด
        $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $('#ocbXthStaDocAct').attr("disabled", true);
        $(".xCNDocBrowsePdt")
            .attr("disabled", true)
            .addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetSearchPdtHTML").attr("disabled", false);
        $(".xWBtnGrpSaveLeft").hide(); // attr("disabled", true);
        $(".xWBtnGrpSaveRight").hide(); // attr("disabled", true);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
    }
    //Check สถานะเอกสาร
    if (ohdXthStaDoc == 3) {
        //Btn Cancel
        $("#obtTBCancel").hide(); // attr("disabled", true);
        //Btn Apv
        $("#obtTBApprove").hide(); // attr("disabled", true);
        $("#obtTFBchPrint").show(); // attr("disabled", true);
        //Control input ปิด
        $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $('#ocbXthStaDocAct').attr("disabled", true);
        $(".xCNDocBrowsePdt")
            .attr("disabled", true)
            .addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetSearchPdtHTML").attr("disabled", false);
        $(".xWBtnGrpSaveLeft").hide(); // attr("disabled", true);
        $(".xWBtnGrpSaveRight").hide(); // attr("disabled", true);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
    }
}

//Functionality : (event) Delete
function JSnTBDel(tCurrentPage, tIDCode) {
    var aData = $("#ohdConfirmIDDelete").val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];
    if (aDataSplitlength == "1") {
        $("#odvModalDel").modal("show");
        $("#ospConfirmDelete").html("ยืนยันการลบข้อมูล หมายเลข : " + tIDCode);
        $("#osmConfirm").on("click", function(evt) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "TBXEventDelete",
                data: { tIDCode: tIDCode },
                cache: false,
                success: function(tResult) {
                    var aReturn = JSON.parse(tResult);

                    if (aReturn["nStaEvent"] == 1) {
                        $("#odvModalDel").modal("hide");
                        $("#ospConfirmDelete").text("ยืนยันการลบข้อมูลของ : ");
                        $("#ohdConfirmIDDelete").val("");
                        localStorage.removeItem("LocalItemData");
                        setTimeout(function() {
                            JSvTBClickPage(tCurrentPage);
                        }, 500);
                    } else {
                        alert(aReturn["tStaMessg"]);
                    }
                    JSxTBNavDefult();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    }
}

//Functionality : (event) Delete All
function JSnTBDelChoose() {
    JCNxOpenLoading();
    var aData = $("#ohdConfirmIDDelete").val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];
    for ($i = 0; $i < aDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }
    if (aDataSplitlength > 1) {
        localStorage.StaDeleteArray = "1";
        $.ajax({
            type: "POST",
            url: "TBXEventDelete",
            data: { tIDCode: aNewIdDelete },
            success: function(tResult) {
                var aReturn = JSON.parse(tResult);
                if (aReturn["nStaEvent"] == 1) {
                    setTimeout(function() {
                        $("#odvModalDel").modal("hide");
                        JSvCallPageTBPdtDataTable();
                        $("#ospConfirmDelete").text("ยืนยันการลบข้อมูลของ : ");
                        $("#ohdConfirmIDDelete").val("");
                        localStorage.removeItem("LocalItemData");
                        $(".modal-backdrop").remove();
                    }, 1000);
                } else {
                    alert(aReturn["tStaMessg"]);
                }
                JSxTBNavDefult();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        localStorage.StaDeleteArray = "0";
        return false;
    }
}

//Functionality: Event Pdt Multi Delete
function JSoTBPdtDelChoose(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var aSeq = $("#ohdConfirmSeqDelete").val();
        var tDocNo = $("#oetXthDocNo").val();

        //PdtCode
        var aTextSeq = aSeq.substring(0, aSeq.length - 2);
        var aSeqSplit = aTextSeq.split(" , ");
        var aSeqSplitlength = aSeqSplit.length;
        //Seq
        var aTextSeq = aSeq.substring(0, aSeq.length - 2);
        var aSeqSplit = aTextSeq.split(" , ");
        var aSeqData = [];

        for ($i = 0; $i < aSeqSplitlength; $i++) {
            aSeqData.push(aSeqSplit[$i]);
        }

        if (aSeqSplitlength > 1) {
            // JCNxOpenLoading();
            localStorage.StaDeleteArray = "1";
            $.ajax({
                type: "POST",
                url: "TBXPdtMultiDeleteEvent",
                data: {
                    tDocNo: tDocNo,
                    tSeqCode: aSeqData
                },
                success: function(tResult) {
                    // console.log(tResult);
                    setTimeout(function() {
                        $("#odvModalDelPdtTB").modal("hide");
                        JSvTBLoadPdtDataTableHtml();
                        $("#ospConfirmDelete").text($("#oetTextComfirmDeleteSingle").val());
                        $("#ohdConfirmSeqDelete").val("");
                        $("#ohdConfirmPdtDelete").val("");
                        $("#ohdConfirmPunDelete").val("");
                        $("#ohdConfirmDocDelete").val("");
                        localStorage.removeItem("LocalItemData");
                        $(".obtChoose").hide();
                        $(".modal-backdrop").remove();
                    }, 1000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            localStorage.StaDeleteArray = "0";
            return false;
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : เปลี่ยนหน้า pagenation
function JSvTBClickPage(ptPage) {
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
    JSvCallPageTBPdtDataTable(nPageCurrent);
}

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 10/07/2019 Krit(Copter)
//Return: -
//Return Type: -
function JSxShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliBtnDeleteAll").addClass("disabled");
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
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {} else {
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
function JSxTBPdtTextinModal() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {} else {
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


//Functionality : เปลี่ยนหน้า pagenation product table
//Parameters : Event Click Pagination
//Creator : 04/04/2019 Krit(Copter)
//Return : View
//Return Type : View
function JSvTBPdtClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWPageTBPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWPageTBPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JCNxOpenLoading();
        JSvTBLoadPdtDataTableHtml(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : Generate Code Subdistrict
//Parameters : Event Icon Click
//Creator : 07/06/2018 wasin
//Return : Data
//Return Type : String
function JStGenerateTBCode() {
    var tTableName = "TCNTPdtTwxHD";
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "generateCode",
        data: { tTableName: tTableName },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            console.log(tResult);
            var tData = $.parseJSON(tResult);
            if (tData.rtCode == "1") {
                console.log(tData);
                $("#oetXthDocNo").val(tData.rtXthDocNo);
                $("#oetXthDocNo").addClass("xCNDisable");
                $(".xCNDisable").attr("readonly", true);
                //----------Hidden ปุ่ม Gen
                $(".xCNBtnGenCode").attr("disabled", true);
                $("#oetXthDocDate").focus();
                $("#oetXthDocDate").focus();

                JStCMNCheckDuplicateCodeMaster(
                    "oetXthDocNo",
                    "JSvCallPageTBEdit",
                    "TCNTPdtTwxHD",
                    "FTXthDocNo"
                );
            } else {
                $("#oetXthDocNo").val(tData.rtDesc);
            }
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Advance Table
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Function : Get Html PDT มาแปะ ในหน้า Add
//Create : 04/04/2019 Krit(Copter)
function JSvTBLoadPdtDataTableHtml(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        //JCNxOpenLoading();

        if ($("#ohdTBRoute").val() == "TBXEventAdd") {
            tXthDocNo = "";
        } else {
            tXthDocNo = $("#oetXthDocNo").val();
        }
        tXthStaApv = $("#ohdXthStaApv").val();
        tXthStaDoc = $("#ohdXthStaDoc").val();
        ptXthVATInOrEx = $("#ostXthVATInOrEx").val();

        //เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#odvTBodyTBPdt .xWPdtItem").length == 0) {
            if (pnPage != undefined) {
                pnPage = pnPage - 1;
            }
        }

        nPageCurrent = pnPage === undefined || pnPage == "" || pnPage <= 0 ? "1" : pnPage;
        $.ajax({
            type: "POST",
            url: "TBXPdtAdvanceTableLoadData",
            data: {
                tXthDocNo: tXthDocNo,
                tXthStaApv: tXthStaApv,
                tXthStaDoc: tXthStaDoc,
                ptXthVATInOrEx: ptXthVATInOrEx,
                nPageCurrent: nPageCurrent
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);
                $("#odvPdtTablePanal").html(aReturn['tTBPdtAdvTableHtml']);
                // JSxTBSetFooterEndOfBill(aReturn['aTBEndOfBill']);

                if (!(tDocNo != '' && (tStaApv == 1 && tStaPrcStk == 1))) {
                    let oParameterSend = {
                        "FunctionName": "JSnSaveDTEdit",
                        "DataAttribute": [],
                        "TableID": "otbDOCCashTable",
                        "NotFoundDataRowClass": "xWTextNotfoundDataTablePdt",
                        "EditInLineButtonDeleteClass": "xWDeleteBtnEditButton",
                        "LabelShowDataClass": "xWShowInLine",
                        "DivHiddenDataEditClass": "xWEditInLine"
                    };
                    JCNxSetNewEditInline(oParameterSend);
                    $(".xWEditInlineElement").eq(nIndexInputEditInline).focus();
                    $($(".xWEditInlineElement").eq(nIndexInputEditInline)).select();
                    $(".xWEditInlineElement").removeAttr("disabled");
                }

                //JSvTBLoadVatTableHtml(); // Load Vat Table

                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxOpenColumnFormSet() {
    $.ajax({
        type: "POST",
        url: "TBXAdvanceTableShowColList",
        data: {},
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            $("#odvShowOrderColumn").modal({ show: true });
            $("#odvOderDetailShowColumn").html(tResult);
            //JSCNAdjustTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSxSaveColumnShow() {
    var aColShowSet = [];
    $(".ocbColStaShow:checked").each(function() {
        aColShowSet.push($(this).data("id"));
    });

    var aColShowAllList = [];
    $(".ocbColStaShow").each(function() {
        aColShowAllList.push($(this).data("id"));
    });

    var aColumnLabelName = [];
    $(".olbColumnLabelName").each(function() {
        aColumnLabelName.push($(this).text());
    });

    //alert(aColShowAllList);

    var nStaSetDef;
    if ($("#ocbSetToDef").is(":checked")) {
        nStaSetDef = 1;
    } else {
        nStaSetDef = 0;
    }
    //alert(aColShowSet);

    $.ajax({
        type: "POST",
        url: "TBXAdvanceTableShowColSave",
        data: {
            aColShowSet: aColShowSet,
            nStaSetDef: nStaSetDef,
            aColShowAllList: aColShowAllList,
            aColumnLabelName: aColumnLabelName
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            $("#odvShowOrderColumn").modal("hide");
            $(".modal-backdrop").remove();
            //Function Gen Table Pdt ของ TB
            JSvTBLoadPdtDataTableHtml();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//ปรับ Value ใน Input หลัวจาก กรอก เสร็จ
function JSxTBAdjInputFormat(ptInputID) {
    cVal = $("#" + ptInputID).val();
    cVal = accounting.toFixed(cVal, nOptDecimalShow);
    $("#" + ptInputID).val(cVal);
}

//Create By Napat(Jame) 18/03/63
function JSxTBSetConditionMerchant(poDataNextFunc) {

    if (poDataNextFunc != "NULL") {
        aData = JSON.parse(poDataNextFunc);
        tAddBch = aData[0];
        tAddSeqNo = aData[1];

        $('#obtTBBrowseShp').attr('disabled', false);
        $('#oetTBShopCodeFrom').val(aData[2]);
        $('#oetTBShopNameFrom').val(aData[3]);


        $('#obtTBBrowseWahStart').attr('disabled', false);
        $('#ohdWahCodeStart').val('');
        $('#oetWahNameStart').val('');

    }
    $('#ofmASTFormAdd').submit();

}

//Create By Napat(Jame) 19/03/63
function JSxTBSetConditionBranchTo() {
    $('#oetTBWahCodeTo').val('');
    $('#oetTBWahNameTo').val('');
    $('#obtTBBrowseWahTo').attr('disabled', false);
}

//Create By Napat(Jame) 19/03/63
function JSxTBSetConditionShopFrom() {

    $('#obtTBBrowseWahStart').attr('disabled', false);

    $('#ohdWahCodeStart').val('');
    $('#oetWahNameStart').val('');

}