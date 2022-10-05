<script>

    $("document").ready(function () {
        localStorage.removeItem("LocalItemData");
        JSxCheckPinMenuClose();
        JSxIVNavDefult('showpage_list');
        JSvIVCallPageList();
        $('#olbIVRefSBInt').hide();
    });

    //Control เมนู
    function JSxIVNavDefult(ptType) {
        if(ptType == 'showpage_list'){
            $("#oliIVTitleAdd").hide();
            $("#oliIVTitleEdit").hide();
            $("#odvBtnAddEdit").hide();
            $('#odvBtnIVPageAddorEdit').show();
        }else if(ptType == 'showpage_add'){
            $("#oliIVTitleAdd").show();
            $("#oliIVTitleEdit").hide();
            $("#odvBtnAddEdit").show();
            $('#odvBtnIVPageAddorEdit').hide();

            $('#obtIVApproveDoc').hide();
            $('#obtIVPrintDoc').hide();
            $('#obtIVCancelDoc').hide();
            $('.xCNBTNSaveDoc').show();
        }else if(ptType == 'showpage_edit'){
            $("#oliIVTitleAdd").hide();
            $("#oliIVTitleEdit").show();

            $("#odvBtnAddEdit").show();
            $('#odvBtnIVPageAddorEdit').hide();
            $('#obtIVApproveDoc').show();
            $('#obtIVPrintDoc').show();
            $('#obtIVCancelDoc').show();
            $('.xCNBTNSaveDoc').show();
        }

        //ล้างค่า
        localStorage.removeItem('IV_LocalItemDataDelDtTemp');
        localStorage.removeItem('LocalItemData');
    }

    //Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
    function JSxIVControlFormWhenCancelOrApprove(){
        var tStatusDoc = $('#ohdIVStaDoc').val();
        var tStatusApv = $('#ohdIVStaApv').val();

        //control ฟอร์ม
        if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){ //เอกสารยกเลิก
            //ปุ่มเลือก
            $('.xCNBtnBrowseAddOn').addClass('disabled');
            $('.xCNBtnBrowseAddOn').attr('disabled',true);

            //ปุ่มเวลา
            $('.xCNBtnDateTime').addClass('disabled');
            $('.xCNBtnDateTime').attr('disabled',true);

            //อินพุต
            $('.form-control').attr('readonly', true);

            //เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();

            //พวก selectpicker
            $('.selectpicker').prop("disabled",true)
        }

        //control ปุ่ม
        if(tStatusDoc == 3 ){ //เอกสารยกเลิก
            //ปุ่มยกเลิก
            $('#obtIVCancelDoc').hide();

            //ปุ่มอนุมัติ
            $('#obtIVApproveDoc').hide();

            //ปุ่มบันทึก
            $('.xCNBTNSaveDoc').hide();
        }else if(tStatusDoc == 1 && tStatusApv == 1){ //เอกสารอนุมัติแล้ว
            //ปุ่มยกเลิก
            $('#obtIVCancelDoc').hide();

            //ปุ่มอนุมัติ
            $('#obtIVApproveDoc').hide();

            //ปุ่มบันทึก
            $('.xCNBTNSaveDoc').show();

            //สามารถกรอกหมายเหตุได้
            $('#otaIVRemark').attr('readonly', false);
        }
    }

    //โหลด List
    function JSvIVCallPageList(){
        $.ajax({
            type    : "GET",
            url     : "docInvoiceSearchList",
            data    : {},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvContentIV").html(tResult);
                JSxIVNavDefult('showpage_list');
                JSvIVCallPageDataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //โหลดข้อมูลตาราง
    function JSvIVCallPageDataTable(pnPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var oAdvanceSearch = JSoIVGetAdvanceSearchData();
            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = "1";
            }

            $.ajax({
                type    : "POST",
                url     : "docInvoiceDataTable",
                data    : {
                    oAdvanceSearch  : oAdvanceSearch,
                    nPageCurrent    : nPageCurrent
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxIVNavDefult();
                        $('#ostContentIV').html(aReturnData['tViewDataTable']);
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                    }
                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //กด Next Page
    function JSvIVClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWPageIVPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWPageIVPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSvIVCallPageDataTable(nPageCurrent);
    }

    //ข้อมูลค้นหาขั้นสูง
    function JSoIVGetAdvanceSearchData() {
        try {
            let oAdvanceSearchData = {
                tSearchAll          : $("#oetSearchAll").val(),
                tSearchBchCodeFrom  : $("#oetBchCodeFrom").val(),
                tSearchBchCodeTo    : $("#oetBchCodeTo").val(),
                tSearchDocDateFrom  : $("#oetSearchDocDateFrom").val(),
                tSearchDocDateTo    : $("#oetSearchDocDateTo").val(),
                tSearchStaDoc       : $("#ocmStaDoc").val(),
                tSearchStaDocAct    : $("#ocmStaDocAct").val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("ค้นหาขั้นสูง Error: ", err);
        }
    }

    //เข้าหน้าแบบ เพิ่ม
    function JSvIVCallPageAdd(ptType){
        try{
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docInvoicePageAdd",
                    cache   : false,
                    timeout : 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            JSxIVNavDefult('showpage_add');
                            $('#odvContentIV').html(aReturnData['tViewPageAdd']);
                            JCNxCloseLoading();

                            //โหลดสินค้าใน Temp
                            JSvIVLoadPdtDataTableHtml();
                        }else {
                            var tMessageError = aReturnData['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }else{
                JCNxShowMsgSessionExpired();
            }
        }catch(err){
            console.log('JSvIVCallPageAdd Error: ', err);
        }
    }

    //เข้าหน้าแบบ แก้ไข
    function JSvIVCallPageEdit(ptDocumentNumber){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docInvoicePageEdit",
                data    : {'ptIVDocNo' : ptDocumentNumber},
                cache   : false,
                timeout : 0,
                success: function(tResult){
                    var aReturnData = JSON.parse(tResult);
                    if(aReturnData['nStaEvent'] == '1'){
                        JSxIVNavDefult('showpage_edit');
                        $('#odvContentIV').html(aReturnData['tViewPageAdd']);
                        JCNxCloseLoading();

                        //โหลดสินค้าใน Temp
                        JSvIVLoadPdtDataTableHtml();

                        //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                        JSxIVControlFormWhenCancelOrApprove();
                    }else{
                        var tMessageError   = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'เรียกดูเอกสารใบซื้อสินค้า';
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
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //สินค้าใน DT
    function JSvIVLoadPdtDataTableHtml(pnPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            if($("#ohdIVRoute").val() == "docInvoiceEventAdd"){
                var tIVDocNo    = "";
            }else{
                var tIVDocNo    = $("#oetIVDocNo").val();
            }
            var tIVStaApv       = $("#ohdIVStaApv").val();
            var tIVStaDoc       = $("#ohdIVStaDoc").val();
            var tIVVATInOrEx    = $("#ocmIVfoVatInOrEx").val();
            if(pnPage == '' || pnPage == null){
                var pnNewPage = 1;
            }else{
                var pnNewPage = pnPage;
            }
            var nPageCurrent  = pnNewPage;
            $.ajax({
                type    : "POST",
                url     : "docInvoiceTableDTTemp",
                data    : {
                    'tBCHCode'              : $('#ohdIVBchCode').val(),
                    'ptIVDocNo'             : tIVDocNo,
                    'ptIVStaApv'            : tIVStaApv,
                    'ptIVStaDoc'            : tIVStaDoc,
                    'ptIVVATInOrEx'         : tIVVATInOrEx,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    var aReturnData = JSON.parse(oResult);
                    if(aReturnData['nStaEvent'] == '1') {
                        $('#odvIVDataPdtTableDTTemp').html(aReturnData['tIVPdtAdvTableHtml']);
                        var aIVEndOfBill = aReturnData['aIVEndOfBill'];
                        JSxIVSetFooterEndOfBill(aIVEndOfBill);
                        JCNxCloseLoading();
                    }else{
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
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

    //เช็คก่อนกดปุ่ม บันทึก
    $('#obtIVSubmitFromDoc').unbind().click(function(){
        var tFrmSPLName         = $('#ohdIVSPLCode').val();
        var tCheckIteminTable   = $('#otbIVDocPdtAdvTableList .xWPdtItem').length;

        if(tCheckIteminTable > 0){
            if(tFrmSPLName != ''){ //ผู้จำหนายไม่ว่าง
                // if($('#ohdIVWahCode').val() == ''){
                //     $('#odvIVModalPleseSelectWah').modal('show');
                // }else{ //คลังสินค้าไม่ว่าง
                //     $('#obtIVSubmitDocument').click();
                // }
                
                var nSUMPrice = 0;
                var bCheckPDT = false;
                $("#otbIVDocPdtAdvTableList tbody td.otdPrice").each(function( index ) {
                    nSUMPrice = parseInt($(this).find('.xCNPdtEditInLine').val());
                    if(nSUMPrice == 0){
                        bCheckPDT = true;
                        return;
                    }
                });

                if(bCheckPDT == true){
                    $('#odvIVSumIsNull').modal('show');

                    //ถ้ากดยืนยันจะเปลี่ยน
                    $('#odvIVSumIsNull .xCNIVSumIsNull').unbind().click(function(){
                        $('#obtIVSubmitDocument').click();
                    });
                }else{
                    $('#obtIVSubmitDocument').click();
                }

            }else{
                $('#odvIVModalPleseSelectSPL').modal('show');
            }
        }else{
            FSvCMNSetMsgWarningDialog('<?=language('document/transferreceiptOut/transferreceiptOut','tConditionPDTEmptyDetail')?>');
        }
    });

    //บันทึก
    function JSxIVAddEditDocument(){
        if($("#ohdIVCheckClearValidate").val() != 0){
            $('#ofmIVFormAdd').validate().destroy();
        }
        $('#ofmIVFormAdd').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetIVDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdIVRoute").val()  ==  "docInvoiceEventAdd"){
                                if($('#ocbIVStaAutoGenCode').is(':checked')){
                                    return false;
                                }else{
                                    return true;
                                }
                            }else{
                                return false;
                            }
                        }
                    }
                },
                oetIVDocDate    : {"required" : true},
                oetIVDocTime    : {"required" : true},
            },
            messages: {
                oetIVDocNo      : {"required" : $('#oetIVDocNo').attr('data-validate-required')},
                oetIVDocDate    : {"required" : $('#oetIVDocDate').attr('data-validate-required')},
                oetIVDocTime    : {"required" : $('#oetIVDocTime').attr('data-validate-required')}
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                if(element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                }else{
                    var tCheck  = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form){
                if(!$('#ocbIVStaAutoGenCode').is(':checked')){
                    if($("#ohdIVRoute").val() ==  "docInvoiceEventAdd"){
                        JSxIVValidateDocCodeDublicate();
                    }else{
                        JSxIVSubmitEventByButton();
                    }
                }else{
                    JSxIVSubmitEventByButton();
                }
            },
        });
    }

    //ถ้าปล่อยให้คีย์เองต้อง ตรวจสอบรหัสเอกสารว่าซ้ำไหม
    function JSxIVValidateDocCodeDublicate(){
        $.ajax({
            type    : "POST",
            url     : "CheckInputGenCode",
            data    : {
                'tTableName'    : 'TAPTPiHD',
                'tFieldName'    : 'FTXphDocNo',
                'tCode'         : $('#oetIVDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);

                $("#ohdIVCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdIVCheckClearValidate").val() != 1) {
                    $('#ofmIVFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdIVRoute").val() == "docInvoiceEventAdd"){
                        if($('#ocbIVStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdIVCheckDuplicateCode").val() == 1) {
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
                $('#ofmIVFormAdd').validate({
                    focusInvalid    : false,
                    onclick         : false,
                    onfocusout      : false,
                    onkeyup         : false,
                    rules           : {
                        oetIVDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetIVDocNo : {"dublicateCode"  : $('#oetIVDocNo').attr('data-validate-duplicate')}
                    },
                    errorElement: "em",
                    errorPlacement: function (error, element) {
                        error.addClass("help-block");
                        if(element.prop("type") === "checkbox") {
                            error.appendTo(element.parent("label"));
                        }else{
                            var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                            if (tCheck == 0) {
                                error.appendTo(element.closest('.form-group')).trigger('change');
                            }
                        }
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).closest('.form-group').addClass("has-error");
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).closest('.form-group').removeClass("has-error");
                    },
                    submitHandler: function (form) {
                        JSxIVSubmitEventByButton();
                    }
                })

                if($("#ohdIVCheckClearValidate").val() != 1) {
                    $("#ofmIVFormAdd").submit();
                    $("#ohdIVCheckClearValidate").val(1);
                }

                if($("#ohdIVCheckDuplicateCode").val() == 1) {
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'เพิ่ม/แก้ไข ใบซื้อสินค้า';
                    var tErrorStatus  = 900
                    var tHtmlError = 'Data Duplicate'
                    var tLogDocNo   = $('#oetIVDocNo').val();
                    JCNxPackDataToMQLog(tHtmlError,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
            }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                var tDocNo = $('#oetIVDocNo').val();
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'Checking Data Duplicate';
                    var tErrorStatus  = 900
                    var tHtmlError = $(jqXHR.responseText);
                    var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                    var tLogDocNo   = tDocNo;
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }else{
                    //JCNxSendMQPageNotFound(jqXHR,tDocNo);
                }
            }
        });
    }

    //บันทึก
    function JSxIVSubmitEventByButton(ptType = ''){
        $('#ohdIVApvOrSave').val(ptType);
        if($("#ohdIVRoute").val() !=  "docInvoiceEventAdd"){
            var tIVDocNo    = $('#oetIVDocNo').val();
        }
        $("#obtIVSubmitFromDoc").attr('disabled','true');
        $.ajax({
            type    : "POST",
            url     : "docInvoiceChkHavePdtForDocDTTemp",
            data    : {'ptIVDocNo': tIVDocNo},
            async   : false,
            cache   : false,
            timeout : 0,
            success : function (oResult){
                var aDataReturnChkTmp   = JSON.parse(oResult);
                if (aDataReturnChkTmp['nStaReturn'] == '1'){

                    //พวก selectpicker
                    $('.selectpicker').prop("disabled",false)

                    $.ajax({
                        type    : "POST",
                        url     : $("#ohdIVRoute").val(),
                        data    : $("#ofmIVFormAdd").serialize(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            var aDataReturnEvent    = JSON.parse(oResult);
                            if(aDataReturnEvent['nStaReturn'] == '1'){
                                var nStaCallBack      = aDataReturnEvent['nStaCallBack'];
                                var nDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                                var oIVXCallDataTableFile = {
                                    ptElementID : 'odvShowDataTable',
                                    ptBchCode   : $('#ohdIVBchCode').val(),
                                    ptDocNo     : nDocNoCallBack,
                                    ptDocKey    :'TAPTPiHD',
                                }
                                JCNxUPFInsertDataFile(oIVXCallDataTableFile);
                                if(ptType == 'approve'){
                                    JSxIVDocumentApv(false)
                                }else{
                                    switch(nStaCallBack){
                                        case '1' :
                                            JSvIVCallPageEdit(nDocNoCallBack);
                                        break;
                                        case '2' :
                                            JSvIVCallPageAdd();
                                        break;
                                        case '3' :
                                            JSvIVCallPageList();
                                        break;
                                        default :
                                            JSvIVCallPageEdit(nDocNoCallBack);
                                    }
                                }
                            }else{
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                            }
                            $("#obtIVSubmitFromDoc").removeAttr("disabled");
                        },
                        error   : function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                            $("#obtIVSubmitFromDoc").removeAttr("disabled");
                            //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                            var tDocNo = $('#oetIVDocNo').val();
                            if (jqXHR.status != 404){
                                var tLogFunction = 'ERROR';
                                var tDisplayEvent = 'บันทึก/แก้ไข ใบซื้อสินค้า';
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
                }else if(aDataReturnChkTmp['nStaReturn'] == '800'){
                    var tMsgDataTempFound   = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgWarningDialog('<p class="text-left">'+tMsgDataTempFound+'</p>');
                }else{
                    var tMsgErrorFunction   = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">'+tMsgErrorFunction+'</p>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                var tDocNo = $('#oetIVDocNo').val();
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'บันทึก/แก้ไข ใบซื้อสินค้า';
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
    }

    // Event Click Appove Document
    $('#obtIVApproveDoc').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tFrmSplName =  $('#oetIVFrmSplName').val();
        var tCheckIteminTable = $('#otbIVDocPdtAdvTableList .xWPdtItem').length;
        var nIVStaValidate =  $('.xIVStaValidate0').length;
        if(tCheckIteminTable>0){
            if(tFrmSplName!=''){
                JSxIVSubmitEventByButton('approve');
            }else{
                $('#odvIVModalPleseselectSPL').modal('show');
            }
        }else{
            FSvCMNSetMsgWarningDialog($('#ohdIVValidatePdt').val());
        }
    });

    //ลบเอกสาร ตัวเดียว
    function JSoIVDelDocSingle(ptCurrentPage, ptDocNo){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            if(typeof(ptDocNo) != undefined && ptDocNo != ""){
                var tTextConfrimDelSingle   = $('#oetTextComfirmDeleteSingle').val()+"&nbsp"+ptDocNo+"&nbsp"+$('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvIVModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvIVModalDelDocSingle').modal('show');
                $('#odvIVModalDelDocSingle #osmIVConfirmPdtDTTemp').unbind().click(function(){
                    JCNxOpenLoading();
                    $.ajax({
                        type    : "POST",
                        url     : "docInvoiceEventDelete",
                        data    : {'tDataDocNo' : ptDocNo},
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            var aReturnData = JSON.parse(oResult);
                            if(aReturnData['nStaEvent'] == '1') {
                                $('#odvIVModalDelDocSingle').modal('hide');
                                $('#odvIVModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function () {
                                    JSvIVCallPageDataTable(ptCurrentPage);
                                }, 500);
                            }else{
                                JCNxCloseLoading();
                                FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                            //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                            if (jqXHR.status != 404){
                                var tLogFunction = 'ERROR';
                                var tDisplayEvent = 'ลบใบซื้อสินค้า';
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
            }else{
                FSvCMNSetMsgErrorDialog('Error Not Found Document Number !!');
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบเอกสาร หลายตัว
    function JSoIVDelDocMultiple(){
        var aDataDelMultiple    = $('#odvIVModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
        var aTextsDelMultiple   = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
        var aDataSplit          = aTextsDelMultiple.split(" , ");
        var nDataSplitlength    = aDataSplit.length;
        var aNewIdDelete        = [];
        for ($i = 0; $i < nDataSplitlength; $i++) {
            aNewIdDelete.push(aDataSplit[$i]);
        }
        if (nDataSplitlength > 1) {
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docInvoiceEventDelete",
                data    : {'tDataDocNo' : aNewIdDelete},
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function () {
                            $('#odvIVModalDelDocMultiple').modal('hide');
                            $('#odvIVModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvIVModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                                JSvIVCallPageDataTable();
                            }, 1000);
                    } else {
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'ลบใบซื้อสินค้า';
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
    function JStIVFindObjectByKey(array,key,value){
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    //ยกเลิกเอกสาร
    function JSxIVDocumentCancel(pbIsConfirm){
        var tDocNo    = $("#oetIVDocNo").val();
        if(pbIsConfirm){
            $.ajax({
                type    : "POST",
                url     : "docInvoiceCancel",
                data    : {
                    'tDocNo'        : tDocNo ,
                    'tRefIntDoc'    : $('#oetIVRefInt').val()
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    $("#odvIVPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if(aReturnData['rtCode'] == '1'){
                        JSvIVCallPageEdit(tDocNo);
                    }else{
                        var tMessageError   = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'ยกเลิกใบซื้อสินค้า';
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
        }else{
            $('#odvIVPopupCancel').modal({backdrop:'static',keyboard:false});
            $("#odvIVPopupCancel").modal("show");
        }
    }

    //อนุมัติเอกสาร
    function JSxIVDocumentApv(pbIsConfirm){

        if($('#oetIVRefSBInt_Old').val() == '' || $('#oetIVRefSBInt_Old').val() == null || $("#oetIVRefSBIntDate").val() == '' || $("#oetIVRefSBIntDate").val() == null){
            $('#odvIVModalBillNoteIsNull').modal('show');
            return;
        }

        if($('#oetIVEffectiveDate_Old').val() == '' || $('#oetIVEffectiveDate_Old').val() == null || $("#oetIVEffectiveDate").val() == '' || $("#oetIVEffectiveDate").val() == null){
            $('#odvIVModalDateIsNull').modal('show');
            return;
        }

        try{
            if(pbIsConfirm){
                $("#odvIVPopupApv").modal('hide');
                var tDocNo    = $('#oetIVDocNo').val();
                var tBchCode  = $('#ohdIVBchCode').val();

                $.ajax({
                    type    : "POST",
                    url     : "docInvoiceApprove",
                    data    : {
                        'tDocNo'            : tDocNo,
                        'tBchCode'          : tBchCode,
                        'tRefIntDoc'        : $('#oetIVRefInt').val(),
                        'tRefDateBill'      : $('#oetIVRefSBIntDate').val(),
                        'tRefCrTerm'        : $('#oetIVCreditTerm').val(),
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(tResult) {
                        $("#odvIVPopupApv").modal("hide");
                        $('.modal-backdrop').remove();
                        var aReturnData = JSON.parse(tResult);
                        if(aReturnData['nStaEvent'] == '1'){
                            JSoIVCallSubscribeMQ();
                        }else{
                            var tMessageError   = aReturnData['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                            var tLogFunction = 'WARNING';
                            var tDisplayEvent = 'อนุมัติใบซื้อสินค้า';
                            var tErrorStatus = '';
                            var tMsgErrorBody = tMessageError;
                            var tLogDocNo = tDocNo;
                            JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                            JCNxCloseLoading();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                        if (jqXHR.status != 404){
                            var tLogFunction = 'ERROR';
                            var tDisplayEvent = 'อนุมัติใบซื้อสินค้า';
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
            }else{
                $("#odvIVPopupApv").modal('show');
            }
        }catch(err){
            console.log("JSxIVDocumentApv Error: ", err);
        }
    }

    //Rabbit MQ
    function JSoIVCallSubscribeMQ() {

        // Document variable
        var tLangCode   = $("#ohdPILangEdit").val();
        var tUsrBchCode = $("#ohdIVBchCode").val();
        var tUsrApv     = '<?=$this->session->userdata('tSesUsername')?>';
        var tDocNo      = $("#oetIVDocNo").val();
        var tPrefix     = "RESPPI";
        var tStaApv     = $("#ohdIVStaApv").val();
        var tStaDelMQ   = 1;
        var tQName      = tPrefix + "_" + tDocNo + "_" + tUsrApv;

        // MQ Message Config
        var poDocConfig = {
            tLangCode           : tLangCode,
            tUsrBchCode         : tUsrBchCode,
            tUsrApv             : tUsrApv,
            tDocNo              : tDocNo,
            tPrefix             : tPrefix,
            tStaDelMQ           : tStaDelMQ,
            tStaApv             : tStaApv,
            tQName              : tQName
        };

        // RabbitMQ STOMP Config
        var poMqConfig = {
            host                : "ws://" + oSTOMMQConfig.host + ":15674/ws",
            username            : oSTOMMQConfig.user,
            password            : oSTOMMQConfig.password,
            vHost               : oSTOMMQConfig.vhost
        };

        // Update Status For Delete Qname Parameter
        var poUpdateStaDelQnameParams = {
            ptDocTableName      : "TAPTPiHD",
            ptDocFieldDocNo     : "FTXphDocNo",
            ptDocFieldStaApv    : "FTXphStaPrcStk",
            ptDocFieldStaDelMQ  : "FTXphStaDelMQ",
            ptDocStaDelMQ       : tStaDelMQ,
            ptDocNo             : tDocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit       : "JSvIVCallPageEdit",
            tCallPageList       : "JSvIVCallPageList"
        };

        // Check Show Progress %
        FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
    }


</script>
