<script>
    $("document").ready(function() {
        localStorage.removeItem("LocalItemData");
        JSxCheckPinMenuClose();
        JSxQTNavDefult('showpage_list');
        JSvQTCallPageList();

        $('#obtBtnBack').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {

                //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
                if (localStorage.tCheckBackStage == 'Job2') {
                    JSxBackStageToJob2();
                } else { //กลับสู่หน้า List
                    JSvQTCallPageList();
                }

            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
        $('#obtQTSubmitFromDoc').attr('disabled', false);
    });

    function JSxBackStageToJob2() {
        var tDocNo = localStorage.tDocno;
        var tAgnCode = localStorage.tAgnCode;
        var tBchCode = localStorage.tBchCode;
        var tCstCode = localStorage.tCstCode;

        $.ajax({
            type: "GET",
            url: 'docJOB/0/0',
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);

                JSvJOBCallPageEdit(tAgnCode, tBchCode, tDocNo, tCstCode)
                localStorage.tCheckBackStage = '';
                localStorage.tDocno = '';
                localStorage.tBchCode = '';
                localStorage.tAgnCode = '';
                localStorage.tCstCode = '';

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Control เมนู
    function JSxQTNavDefult(ptType) {
        if (ptType == 'showpage_list') {
            $("#oliQTTitleAdd").hide();
            $("#oliQTTitleEdit").hide();
            $("#odvBtnAddEdit").hide();
            $('#odvBtnQTPageAddorEdit').show();
        } else if (ptType == 'showpage_add') {
            $("#oliQTTitleAdd").show();
            $("#oliQTTitleEdit").hide();
            $("#odvBtnAddEdit").show();
            $('#odvBtnQTPageAddorEdit').hide();

            $('#obtQTApproveDoc').hide();
            $('#obtQTPrintDoc').hide();
            $('#obtQTCancelDoc').hide();
            $('.xCNBTNSaveDoc').show();
        } else if (ptType == 'showpage_edit') {
            $("#oliQTTitleAdd").hide();
            $("#oliQTTitleEdit").show();

            $("#odvBtnAddEdit").show();
            $('#odvBtnQTPageAddorEdit').hide();
            $('#obtQTApproveDoc').show();
            $('#obtQTPrintDoc').show();
            $('#obtQTCancelDoc').show();
            $('.xCNBTNSaveDoc').show();
        }

        //ล้างค่า
        localStorage.removeItem('QT_LocalItemDataDelDtTemp');
        localStorage.removeItem('LocalItemData');
    }

    //Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
    function JSxQTControlFormWhenCancelOrApprove() {
        var tStatusDoc = $('#ohdTQStaDoc').val();
        var tStatusApv = $('#ohdTQStaApv').val();

        //control ฟอร์ม
        if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) { //เอกสารยกเลิก
            //ปุ่มเลือก
            $('.xCNBtnBrowseAddOn').addClass('disabled');
            $('.xCNBtnBrowseAddOn').attr('disabled', true);

            //ปุ่มเวลา
            $('.xCNBtnDateTime').addClass('disabled');
            $('.xCNBtnDateTime').attr('disabled', true);

            //อินพุต
            $('.form-control').attr('readonly', true);

            //เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();
        }

        //control ปุ่ม
        if (tStatusDoc == 3) { //เอกสารยกเลิก
            //ปุ่มยกเลิก
            $('#obtQTCancelDoc').hide();

            //ปุ่มอนุมัติ
            $('#obtQTApproveDoc').hide();

            //ปุ่มบันทึก
            $('.xCNBTNSaveDoc').hide();
        } else if (tStatusDoc == 1 && tStatusApv == 1) { //เอกสารอนุมัติแล้ว
            //ปุ่มยกเลิก
            $('#obtQTCancelDoc').hide();

            //ปุ่มอนุมัติ
            $('#obtQTApproveDoc').hide();

            //ปุ่มบันทึก
            $('.xCNBTNSaveDoc').show();

            //สามารถกรอกหมายเหตุได้
            $('#otaQTRemark').attr('readonly', false);
        }
    }

    //โหลด List
    function JSvQTCallPageList() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            $.ajax({
                type: "GET",
                url: "docQuotationSearchList",
                data: {},
                cache: false,
                timeout: 5000,
                success: function(tResult) {
                    $("#odvContentQT").html(tResult);
                    JSxQTNavDefult('showpage_list');
                    JSvQTCallPageDataTable();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //โหลดข้อมูลตาราง
    function JSvQTCallPageDataTable(pnPage) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var oAdvanceSearch = JSoQTGetAdvanceSearchData();
            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = "1";
            }

            $.ajax({
                type: "POST",
                url: "docQuotationDataTable",
                data: {
                    oAdvanceSearch: oAdvanceSearch,
                    nPageCurrent: nPageCurrent
                },
                cache: false,
                timeout: 5000,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxQTNavDefult();
                        $('#ostContentTQ').html(aReturnData['tViewDataTable']);
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                    }
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

    //กด Next Page
    function JSvQTClickPageList(ptPage) {
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld = $('.xWPageQTPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWPageQTPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSvQTCallPageDataTable(nPageCurrent);
    }

    //ข้อมูลค้นหาขั้นสูง
    function JSoQTGetAdvanceSearchData() {
        try {
            let oAdvanceSearchData = {
                tSearchAll: $("#oetSearchAll").val(),
                tSearchBchCodeFrom: $("#oetBchCodeFrom").val(),
                tSearchBchCodeTo: $("#oetBchCodeTo").val(),
                tSearchDocDateFrom: $("#oetSearchDocDateFrom").val(),
                tSearchDocDateTo: $("#oetSearchDocDateTo").val(),
                tSearchStaDoc: $("#ocmStaDoc").val(),
                tSearchStaDocAct: $("#ocmStaDocAct").val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("ค้นหาขั้นสูง Error: ", err);
        }
    }

    //เข้าหน้าแบบ เพิ่ม
    function JSvQTCallPageAdd(ptType) {
        try {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docQuotationPageAdd",
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            JSxQTNavDefult('showpage_add');
                            $('#odvContentQT').html(aReturnData['tViewPageAdd']);
                            JCNxCloseLoading();

                            //โหลดสินค้าใน Temp
                            JSvTQLoadPdtDataTableHtml();
                        } else {
                            var tMessageError = aReturnData['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                JCNxShowMsgSessionExpired();
            }
        } catch (err) {
            console.log('JSvQTCallPageAdd Error: ', err);
        }
    }

    //เข้าหน้าแบบ แก้ไข
    function JSvQTCallPageEdit(ptDocumentNumber) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docQuotationPageEdit",
                data: {
                    'ptQTDocNo': ptDocumentNumber
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxQTNavDefult('showpage_edit');
                        $('#odvContentQT').html(aReturnData['tViewPageAdd']);
                        JCNxCloseLoading();

                        //โหลดสินค้าใน Temp
                        JSvTQLoadPdtDataTableHtml();

                        //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                        JSxQTControlFormWhenCancelOrApprove();
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'เรียกดูเอกสารใบเสนอราคา';
                        var tErrorStatus  = 500
                        var tLogDocNo   = ptDocumentNumber;
                        var tHtmlError = $(jqXHR.responseText);
                        var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }else{
                        //JCNxSendMQPageNotFound(jqXHR,ptDocumentNumber);
                    }
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //สินค้าใน DT
    function JSvTQLoadPdtDataTableHtml(pnPage) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            if ($("#ohdTQRoute").val() == "docQuotationEventAdd") {
                var tTQDocNo = "";
            } else {
                var tTQDocNo = $("#oetTQDocNo").val();
            }

            var tTQStaApv = $("#ohdTQStaApv").val();
            var tTQStaDoc = $("#ohdTQStaDoc").val();
            var tTQVATInOrEx = $("#ocmTQfoVatInOrEx").val();

            if (pnPage == '' || pnPage == null) {
                var pnNewPage = 1;
            } else {
                var pnNewPage = pnPage;
            }
            var nPageCurrent = pnNewPage;

            $.ajax({
                type: "POST",
                url: "docQuotationTableDTTemp",
                data: {
                    'tBCHCode': $('#ohdTQBchCode').val(),
                    'ptQTDocNo': tTQDocNo,
                    'ptTQStaApv': tTQStaApv,
                    'ptTQStaDoc': tTQStaDoc,
                    'ptTQVATInOrEx': tTQVATInOrEx,
                },
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        $('#odvTQDataPdtTableDTTemp').html(aReturnData['tTQPdtAdvTableHtml']);
                        var aTQEndOfBill = aReturnData['aTQEndOfBill'];
                        JSxTQSetFooterEndOfBill(aTQEndOfBill);
                        JCNxCloseLoading();
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

    //เช็คก่อนกดปุ่ม บันทึก
    $('#obtQTSubmitFromDoc').unbind().click(function() {
        var tFrmCstName = $('#ohdTQCustomerCode').val();
        var tCheckIteminTable = $('#otbTQDocPdtAdvTableList .xWPdtItem').length;

        if (tCheckIteminTable > 0) {
            if (tFrmCstName != '') {
                $('#obtTQSubmitDocument').click();
            } else {
                $('#odvTQModalPleseselectCustomer').modal('show');
            }
        } else {
            FSvCMNSetMsgWarningDialog('<?= language('document/transferreceiptOut/transferreceiptOut', 'tConditionPDTEmptyDetail') ?>');
        }
    });

    //บันทึก
    function JSxTQAddEditDocument() {

        if($("#ohdTQCheckClearValidate").val() != 0){
            $('#ofmTQFormAdd').validate().destroy();
        }

        //โหลดปุ่ม picker
        $('.xCNSelectDisabledPicker').attr("disabled", false);

        $('#ofmTQFormAdd').validate({
            focusInvalid: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            rules: {
                oetTQDocNo: {
                    "required": {
                        depends: function(oElement) {
                            if ($("#ohdTQRoute").val() == "docQuotationEventAdd") {
                                if ($('#ocbTQStaAutoGenCode').is(':checked')) {
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
                oetTQDocDate: {
                    "required": true
                },
                oetTQDocTime: {
                    "required": true
                },
            },
            messages: {
                oetTQDocNo: {
                    "required": $('#oetTQDocNo').attr('data-validate-required')
                },
                oetTQDocDate: {
                    "required": $('#oetTQDocDate').attr('data-validate-required')
                },
                oetTQDocTime: {
                    "required": $('#oetTQDocTime').attr('data-validate-required')
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
            submitHandler: function(form) {
                if ($('#ohdTQRoute').val() == 'docQuotationEventAdd') {
                    if (!$('#ocbTQStaAutoGenCode').is(':checked')) {
                        JSxTQValidateDocCodeDublicate();
                    } else {
                        $("#ohdTQCheckDuplicateCode").val(0)
                        JSxTQSubmitEventByButton();
                    }
                }else{
                    JSxTQSubmitEventByButton();
                }
                
            },
        });
    }

    //ถ้าปล่อยให้คีย์เองต้อง ตรวจสอบรหัสเอกสารว่าซ้ำไหม
    function JSxTQValidateDocCodeDublicate() {
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName': 'TARTSqHD',
                'tFieldName': 'FTXshDocNo',
                'tCode': $('#oetTQDocNo').val()
            },
            success: function(oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdTQCheckDuplicateCode").val(aResultData["rtCode"]);

                if ($("#ohdTQCheckClearValidate").val() != 1) {
                    $('#ofmTQFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdTQRoute").val() == "docQuotationEventAdd"){
                        if($('#ocbTQStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdTQCheckDuplicateCode").val() == 1) {
                                return false;
                            }else{
                                return true;
                            }
                        }
                    }else{
                        return true;
                    }
                });

                // Set Form Validate From Add Document
                $('#ofmTQFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetTQDocNo: {"dublicateCode": {}}
                    },
                    messages: {
                        oetTQDocNo: {"dublicateCode": $('#oetTQDocNo').attr('data-validate-duplicate')}
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
                    submitHandler: function(form) {
                        JSxTQSubmitEventByButton();
                    }
                })
                if($("#ohdTQCheckClearValidate").val() != 1) {
                    $("#ofmTQFormAdd").submit();
                    $("#ohdTQCheckClearValidate").val(1);
                }
                
                if($("#ohdTQCheckDuplicateCode").val() == 1) {
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'Check Data Duplicate';
                    var tErrorStatus  = ''
                    var tLogDocNo   = $('#oetTQDocNo').val();
                    var tHtmlError = 'Data Duplicate'
                    JCNxPackDataToMQLog(tHtmlError,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                var tDocNo = $('#oetTQDocNo').val();
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'Check Data Duplicate';
                    var tErrorStatus  = ''
                    var tLogDocNo   = tDocNo;
                    var tHtmlError = $(jqXHR.responseText);
                    var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }else{
                    //JCNxSendMQPageNotFound(jqXHR,tDocNo);
                }
            }
        });
    }

    //บันทึก
    function JSxTQSubmitEventByButton(ptType = '') {
        $('#ohdTQApvOrSave').val(ptType);
        if ($("#ohdTQRoute").val() != "docQuotationEventAdd") {
            var tTQDocNo = $('#oetTQDocNo').val();
        }
        //console.log('asd');
        //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
        $('#obtQTSubmitFromDoc').attr('disabled', true);

        $.ajax({
            type: "POST",
            url: "docQuotationChkHavePdtForDocDTTemp",
            data: {
                'ptQTDocNo': tTQDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aDataReturnChkTmp = JSON.parse(oResult);
                if (aDataReturnChkTmp['nStaReturn'] == '1') {
                    $.ajax({
                        type: "POST",
                        url: $("#ohdTQRoute").val(),
                        data: $("#ofmTQFormAdd").serialize(),
                        cache: false,
                        timeout: 0,
                        success: function(oResult) {
                            var aDataReturnEvent = JSON.parse(oResult);
                            if (aDataReturnEvent['nStaReturn'] == '1') {
                                var nStaCallBack = aDataReturnEvent['nStaCallBack'];
                                var nDocNoCallBack = aDataReturnEvent['tCodeReturn'];
                                var oIVXCallDataTableFile = {
                                    ptElementID: 'odvShowDataTable',
                                    ptBchCode: $('#ohdTQBchCode').val(),
                                    ptDocNo: nDocNoCallBack,
                                    ptDocKey: 'TARTSqHD',
                                }
                                //console.log(oIVXCallDataTableFile);
                                JCNxUPFInsertDataFile(oIVXCallDataTableFile);
                                //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
                                $('#obtQTSubmitFromDoc').attr('disabled', false);
                                if (ptType == 'approve') {
                                    var tDocNo = $('#oetTQDocNo').val();
                                    var tBchCode = $('#ohdTQBchCode').val();
                                    $.ajax({
                                        type: "POST",
                                        url: "docQuotationApprove",
                                        data: {
                                            tDocNo: tDocNo,
                                            tBchCode: tBchCode
                                        },
                                        cache: false,
                                        timeout: 0,
                                        success: function(tResult) {
                                            $("#odvQTPopupApv").modal("hide");
                                            $('.modal-backdrop').remove();
                                            var aReturnData = JSON.parse(tResult);
                                            if (aReturnData['nStaEvent'] == '1') {
                                                JSvQTCallPageEdit(tDocNo);
                                            } else {
                                                var tMessageError = aReturnData['tStaMessg'];
                                                FSvCMNSetMsgErrorDialog(tMessageError);
                                                JCNxCloseLoading();
                                            }
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                                            //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                                            if (jqXHR.status != 404){
                                                var tLogFunction = 'ERROR';
                                                var tDisplayEvent = 'อนุมัติใบเสนอราคา';
                                                var tErrorStatus = 500;
                                                var tHtmlError = $(jqXHR.responseText);
                                                var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                                                var tLogDocNo   = tTQDocNo;
                                                JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction);
                                            }else{
                                                //JCNxSendMQPageNotFound(jqXHR,tTQDocNo);
                                            }
                                        }
                                    });
                                } else {
                                    switch (nStaCallBack) {
                                        case '1':
                                            JSvQTCallPageEdit(nDocNoCallBack);
                                            break;
                                        case '2':
                                            JSvQTCallPageAdd();
                                            break;
                                        case '3':
                                            JSvQTCallPageList();
                                            break;
                                        default:
                                            JSvQTCallPageEdit(nDocNoCallBack);
                                    }
                                }
                            } else {
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                            //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                            $('#obtQTSubmitFromDoc').attr('disabled', false);
                            if (jqXHR.status != 404){
                                var tLogFunction = 'ERROR';
                                var tDisplayEvent = 'บันทึก/แก้ไข ใบเสนอราคา';
                                var tErrorStatus = 500;
                                var tHtmlError = $(jqXHR.responseText);
                                var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                                var tLogDocNo   = tTQDocNo;
                                JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                            }else{
                                //JCNxSendMQPageNotFound(jqXHR,tTQDocNo);
                            }
                        }
                    });
                } else if (aDataReturnChkTmp['nStaReturn'] == '800') {
                    var tMsgDataTempFound = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgWarningDialog('<p class="text-left">' + tMsgDataTempFound + '</p>');
                } else {
                    var tMsgErrorFunction = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">' + tMsgErrorFunction + '</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'บันทึก/แก้ไข ใบเสนอราคา';
                    var tErrorStatus = 500;
                    var tHtmlError = $(jqXHR.responseText);
                    var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                    var tLogDocNo   = tTQDocNo;
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }else{
                    //JCNxSendMQPageNotFound(jqXHR,tTQDocNo);
                }
            }
        });
    }

    //ลบเอกสาร ตัวเดียว
    function JSoQTDelDocSingle(ptCurrentPage, ptDocNo, ptBchCode) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            if (typeof(ptDocNo) != undefined && ptDocNo != "") {
                var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvQTModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvQTModalDelDocSingle').modal('show');
                $('#odvQTModalDelDocSingle #osmQTConfirmPdtDTTemp').unbind().click(function() {
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docQuotationEventDelete",
                        data: {
                            'tBchCode': ptBchCode,
                            'tDataDocNo': ptDocNo
                        },
                        cache: false,
                        timeout: 0,
                        success: function(oResult) {
                            var aReturnData = JSON.parse(oResult);
                            if (aReturnData['nStaEvent'] == '1') {
                                $('#odvQTModalDelDocSingle').modal('hide');
                                $('#odvQTModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                    JSvQTCallPageDataTable(ptCurrentPage);
                                }, 500);
                            } else {
                                JCNxCloseLoading();
                                FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                            //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                            if (jqXHR.status != 404){
                                var tLogFunction = 'ERROR';
                                var tDisplayEvent = 'ลบใบเสนอราคา';
                                var tErrorStatus = 500;
                                var tHtmlError = $(jqXHR.responseText);
                                var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                                var tLogDocNo   = ptDocNo;
                                JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                            }else{
                                //JCNxSendMQPageNotFound(jqXHR,ptDocNo);
                            }
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

    //ลบเอกสาร หลายตัว
    function JSoQTDelDocMultiple() {
        var aDataDelMultiple = $('#odvQTModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
        var aTextsDelMultiple = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
        var aDataSplit = aTextsDelMultiple.split(" , ");
        var nDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        for ($i = 0; $i < nDataSplitlength; $i++) {
            aNewIdDelete.push(aDataSplit[$i]);
        }
        if (nDataSplitlength > 1) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docQuotationEventDelete",
                data: {
                    'tDataDocNo': aNewIdDelete
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvQTModalDelDocMultiple').modal('hide');
                            $('#odvQTModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvQTModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                            JSvQTCallPageDataTable();
                        }, 1000);
                    } else {
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'ลบใบเสนอราคา';
                        var tErrorStatus = 500;
                        var tHtmlError = $(jqXHR.responseText);
                        var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                        var tLogDocNo   = aNewIdDelete;
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }else{
                        //JCNxSendMQPageNotFound(jqXHR,aNewIdDelete);
                    }
                }
            });
        }
    }

    //เซตข้อความ กรณีลบหลายรายการ
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

            $("#ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
            $("#ohdConfirmIDDelMultiple").val(tTextCode);
        }
    }

    //เปิดปุ่มให้ลบได้ กรณีลบหลายรายการ
    function JSxShowButtonChoose() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
            $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
        } else {
            nNumOfArr = aArrayConvert[0].length;
            if (nNumOfArr > 1) {
                $('#odvMngTableList #oliBtnDeleteAll').removeClass('disabled');
            } else {
                $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
            }
        }
    }

    //ลบคอลัมน์ในฐานข้อมูล เช็คค่าใน array [หลายรายการ]
    function JStQTFindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    //ยกเลิกเอกสาร
    function JSxQTDocumentCancel(pbIsConfirm) {
        var tDocNo = $("#oetTQDocNo").val();
        var tBchCode = $("#ohdTQBchCode").val();
        if (pbIsConfirm) {
            $.ajax({
                type: "POST",
                url: "docQuotationCancel",
                data: {
                    'tBchCode' : tBchCode,
                    'tDocNo': tDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvQTPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['rtCode'] == '1') {
                        JSvQTCallPageEdit(tDocNo);
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'ยกเลิกใบเสนอราคา';
                        var tErrorStatus = 500;
                        var tHtmlError = $(jqXHR.responseText);
                        var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                        var tLogDocNo   = tDocNo;
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }else{
                        //JCNxSendMQPageNotFound(jqXHR,tDocNo);
                    }
                }
            });
        } else {
            $('#odvQTPopupCancel').modal({
                backdrop: 'static',
                keyboard: false
            });
            $("#odvQTPopupCancel").modal("show");
        }
    }

    //อนุมัติเอกสาร
    function JSxQTDocumentApv(pbIsConfirm) {
        var nStaSession = 1;
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            try {
                if (pbIsConfirm) {
                    $("#odvQTPopupApv").modal('hide');

                    var tDocNo = $('#oetTQDocNo').val();
                    var tBchCode = $('#ohdTQBchCode').val();
                    var tFrmCstName = $('#ohdTQCustomerCode').val();
                    var tCheckIteminTable = $('#otbTQDocPdtAdvTableList .xWPdtItem').length;

                    if (tCheckIteminTable > 0) {
                        if (tFrmCstName != '') {
                            JCNxCloseLoading();
                            JSxTQSubmitEventByButton('approve');
                        } else {
                            $('#odvTQModalPleseselectCustomer').modal('show');
                        }
                    } else {
                        FSvCMNSetMsgWarningDialog('<?= language('document/transferreceiptOut/transferreceiptOut', 'tConditionPDTEmptyDetail') ?>');
                    }
                } else {
                    $("#odvQTPopupApv").modal('show');
                }
            } catch (err) {
                console.log("JSxQTDocumentApv Error: ", err);
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

</script>