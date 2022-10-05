<script>

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    $("document").ready(function () {
        localStorage.removeItem("LocalItemData");
        JSxCheckPinMenuClose(); 
        JSxIVCNavDefult('showpage_list');

        // รองรับการเข้ามาแบบ Noti
        var nStaIVCBrowseType = $('#oetIVCJumpBrwType').val();
        switch(nStaIVCBrowseType){
            case '2':
                var tAgnCode    = $('#oetIVCJumpAgnCode').val();
                var tBchCode    = $('#oetIVCJumpBchCode').val();
                var tDocNo      = $('#oetIVCJumpDocNo').val();
                JSvIVCCallPageEdit(tDocNo);
            break;
            default:
                JSvIVCCallPageList();
        }
    });

    //Control เมนู
    function JSxIVCNavDefult(ptType) {
        if(ptType == 'showpage_list'){
            $("#oliIVCTitleAdd").hide();
            $("#oliIVCTitleEdit").hide();
            $("#odvBtnAddEdit").hide();
            $('#odvBtnIVCPageAddorEdit').show();
            $('#obtIVCCancelDoc').hide();
            $('#obtIVCApproveDoc').hide();
        }else if(ptType == 'showpage_add'){
            $("#oliIVCTitleAdd").show();
            $("#oliIVCTitleEdit").hide();
            $("#odvBtnAddEdit").show();
            $('#obtIVCPrintDoc').hide();
            $('#odvBtnIVCPageAddorEdit').hide();
            $('.xCNBTNSaveDoc').show();
            $('#obtIVCCancelDoc').hide();
            $('#obtIVCApproveDoc').hide();
        }else if(ptType == 'showpage_edit'){
            $("#oliIVCTitleAdd").hide();
            $("#oliIVCTitleEdit").show();
            $("#odvBtnAddEdit").show();
            $('#odvBtnIVCPageAddorEdit').hide();
            $('#obtIVCPrintDoc').show();
            $('#obtIVCCancelDoc').show();
            $('#obtIVCApproveDoc').show();

        }

        //ล้างค่า
        localStorage.removeItem('IVC_LocalItemDataDelDtTemp');
        localStorage.removeItem('LocalItemData');
    }

    //Control ปุ่ม และอินพุตต่างๆ [เอกสารอยู่ระหว่างดำเนินการ StaPrc]
    function JSxIVCControlFormWhenDocProcess(){
        var tStatusPrcDoc = $('#ohdIVCStaPrc').val();
        var tStatusDoc = $('#ohdIVCStaDoc').val();
        var tStatusApv = $('#ohdIVCStaApv').val();

        if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){ 
            //ปุ่มเลือก
            $('.xCNBtnBrowseAddOn').addClass('disabled');
            $('.xCNBtnBrowseAddOn').attr('disabled',true);

            //ปุ่มเวลา
            $('.xCNBtnDateTime').addClass('disabled');
            $('.xCNBtnDateTime').attr('disabled',true);

            //อินพุต
            $('.xCNInputReadOnly').attr('readonly', true);

            //อินพุต
            $('.form-control').attr('readonly', true);

            //อินพุต
            $('.xControlRmk').attr('disabled', true);

            //เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();

            //พวก selectpicker
            $('.selectpicker').prop("disabled",true);

            //พวกปุ่มบันทึก
            // $('.xCNBTNSaveDoc').hide();

            //ปุ่มยกเลิก
            $('#obtIVCCancelDoc').hide();

            //ปุ่มอนุมัติ
            $('#obtIVCApproveDoc').hide();
        }
    }

    //โหลด List
    function JSvIVCCallPageList(){
        JSxCheckPinMenuClose();
        $.ajax({
            type    : "GET",
            url     : "docInvoiceCustomerBillPageList",
            data    : {},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvContentIVC").html(tResult);
                JSxIVCNavDefult('showpage_list');
                JSvIVCCallPageDataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //โหลดข้อมูลตาราง
    function JSvIVCCallPageDataTable(pnPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var oAdvanceSearch = JSoIVCGetAdvanceSearchData();
            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = "1";
            }

            $.ajax({
                type    : "POST",
                url     : "docInvoiceCustomerBillDataTable",
                data    : {
                    oAdvanceSearch  : oAdvanceSearch,
                    nPageCurrent    : nPageCurrent
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxIVCNavDefult('showpage_list');
                        $('#ostContentIVC').html(aReturnData['tViewDataTable']);
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
    function JSvIVCClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWPageIVCPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWPageIVCPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSvIVCCallPageDataTable(nPageCurrent);
    }

    //ข้อมูลค้นหาขั้นสูง 
    function JSoIVCGetAdvanceSearchData() {
        try {
            let oAdvanceSearchData = {
                tSearchAll          : $("#oetSearchAll").val(),
                tSearchBchCodeFrom  : $("#oetBchCodeFrom").val(),
                tSearchBchCodeTo    : $("#oetBchCodeTo").val(),
                tSearchSPLCodeFrom  : $('#oetSplCodeFrom').val(),
                tSearchSPLCodeTo    : $('#oetSplCodeTo').val(),
                tSearchDocDateFrom  : $("#oetSearchDocDateFrom").val(),
                tSearchDocDateTo    : $("#oetSearchDocDateTo").val(),
                tSearchCstCode      : $("#oetCstCode").val(),
                tSearchCarCode      : $("#oetCarCode").val(),
                tSearchStaDoc       : $("#ocmStaDoc").val(),
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("ค้นหาขั้นสูง Error: ", err);
        }
    }

    //เข้าหน้าแบบ เพิ่ม
    function JSvIVCCallPageAdd(ptType){
        try{
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docInvoiceCustomerBillPageAdd",
                    cache   : false,
                    timeout : 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            JSxIVCNavDefult('showpage_add');
                            $('#odvContentIVC').html(aReturnData['tViewPageAdd']);
                            JCNxCloseLoading();
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
            console.log('JSvIVCCallPageAdd Error: ', err);
        }
    }

    //เข้าหน้าแบบ แก้ไข
    function JSvIVCCallPageEdit(ptDocumentNumber){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docInvoiceCustomerBillPageEdit",
                data    : {'ptIVCDocNo' : ptDocumentNumber},
                cache   : false,
                timeout : 0,
                success: function(tResult){
                    var aReturnData = JSON.parse(tResult);
                    if(aReturnData['nStaEvent'] == '1'){
                        JSxIVCNavDefult('showpage_edit');
                        $('#odvContentIVC').html(aReturnData['tViewPageAdd']);

                        if (aReturnData['aChkData']['row'] == 0 || aReturnData['aChkData']['row'] == 1) {
                            $('#oimIVCBrowseCstBch').addClass('disabled');
                            $('#oimIVCBrowseCstBch').attr('disabled',true);
                        }else{
                            $('#oimIVCBrowseCstBch').removeClass('disabled');
                            $('#oimIVCBrowseCstBch').removeAttr('disabled');
                        }

                        JCNxCloseLoading();
                        //เช็คว่าเอกสารยกเลิก อยู่สถานะไหนเเล้ว
                        JSxIVCControlFormWhenDocProcess();
                    }else{
                        var tMessageError   = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //เช็คก่อนกดปุ่ม บันทึก
    $('#obtIVCSubmitFromDoc').unbind().click(function(){
        var tBchCode            = $('#ohdIVCBchCode').val();
        var tSPLCode            = $('#oetIVCCSTName').val();
        var tchkitemsteptwo     = $(".xWPdtItemStep2").length;
        var dDateDue            = $("#oetIVCPaidDate").val();
        var tCheckIteminTable   = $('#otbCLMStep1Point2DocPdtAdvTableList .xWPdtItemStep2').length;
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');

        if(tBchCode == '' || tSPLCode == '' || dDateDue == '' /*|| tMile == ''*/ || tStepNow != '2' || tchkitemsteptwo < 1){
            //กรุณากรอกข้อมูลให้ครบถ้วน
            if(tBchCode == ''){
                var tTextWarning = 'กรุณาระบุสาขา';
            }else if(tSPLCode == ''){
                var tTextWarning = 'กรุณาระบุข้อมูลลูกค้า';
            }else if(tStepNow != '2'){
                var tTextWarning = 'กรุณาตรวจสอบและยืนยันก่อน';
            }else if(tchkitemsteptwo < 1){
                var tTextWarning = 'กรุณาตรวจสอบและยืนยันก่อน';
            }else if(dDateDue == ''){
                var tTextWarning = 'กรุณาเลือกวันที่นัดรับเงิน/ชำระเงินก่อน';
            }/*else{
                var tTextWarning = 'กรุณาระบุเลขไมล์รถของลูกค้า';
            }*/

            $('#odvIVCModalPleseDataInFill #ospIVCModalPleseDataInFill').text(tTextWarning);
            $('#odvIVCModalPleseDataInFill').modal('show');
            return;
        }else if(tCheckIteminTable == 0){

            //ไม่พบสินค้าใน Temp
            FSvCMNSetMsgWarningDialog('<?=language('document/transferreceiptOut/transferreceiptOut','กรุณาเลือกเอกสารที่จะนำมาวางบิล')?>');
        }else{

            JCNxOpenLoading();

            $('#obtIVCSubmitDocument').click();
        }
    });

    //เพิ่มข้อมูล
    function JSxIVCAddEditDocument(){
        $('#ofmIVCFormAdd').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetIVCDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdIVCRoute").val()  ==  "docInvoiceCustomerBillEventAdd"){
                                if($('#ocbIVCStaAutoGenCode').is(':checked')){
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
                oetIVCDocDate    : {"required" : true},
                oetIVCDocTime    : {"required" : true},
            },
            messages: {
                oetIVCDocNo      : {"required" : $('#oetIVCDocNo').attr('data-validate-required')},
                oetIVCDocDate    : {"required" : $('#oetIVCDocDate').attr('data-validate-required')},
                oetIVCDocTime    : {"required" : $('#oetIVCDocTime').attr('data-validate-required')}
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
                if(!$('#ocbIVCStaAutoGenCode').is(':checked')){
                    if($("#ohdIVCRoute").val() ==  "docInvoiceCustomerBillEventAdd"){
                        JSxIVCValidateDocCodeDublicate();
                    }else{
                        JSxIVCSubmitEventByButton();
                    }
                }else{
                    JSxIVCSubmitEventByButton();
                }
            },
        });
    }

    //ถ้าปล่อยให้คีย์เองต้อง ตรวจสอบรหัสเอกสารว่าซ้ำไหม
    function JSxIVCValidateDocCodeDublicate(){
        $.ajax({
            type    : "POST",
            url     : "CheckInputGenCode",
            data    : {
                'tTableName'    : 'TACTSBHD',
                'tFieldName'    : 'FTXphDocNo',
                'tCode'         : $('#oetIVCDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                
                $("#ohdIVCCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdIVCCheckDuplicateCode").val() != 1) {
                    $('#ofmIVCFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdIVCRoute").val() == "docInvoiceCustomerBillEventAdd"){
                        if($('#ocbIVCStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdIVCCheckDuplicateCode").val() == 1) {
                                return true;
                            }else{
                                return true;
                            }
                        }
                    }else{
                        return true;
                    }
                });

                // Set Form Validate From Add Document
                $('#ofmIVCFormAdd').validate({
                    focusInvalid    : false,
                    onclick         : false,
                    onfocusout      : false,
                    onkeyup         : false,
                    rules           : {
                        oetIVCDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetIVCDocNo : {"dublicateCode"  : $('#oetIVCDocNo').attr('data-validate-duplicate')}
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
                        JSxIVCSubmitEventByButton();
                    }
                })
                if($("#ohdIVCCheckDuplicateCode").val() == '1'){
                    FSvCMNSetMsgErrorDialog('เลขเอกสารมีการใช้แล้ว');
                    return true;
                }else{
                    $("#ofmIVCFormAdd").submit();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //บันทึก
    function JSxIVCSubmitEventByButton(){
        $('.selectpicker').removeAttr("disabled")
        $.ajax({
            type    : "POST",
            url     : $("#ohdIVCRoute").val(),
            data    : $("#ofmIVCFormAdd").serialize(),
            cache   : false,
            timeout : 0,
            success : function(oResult){
                var aDataReturnEvent    = JSON.parse(oResult);
                if(aDataReturnEvent['nStaReturn'] == '1'){
                    var nStaCallBack      = aDataReturnEvent['nStaCallBack'];
                    var nDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                    let oIVCXCallDataTableFile = {
                        ptElementID : 'odvShowDataTable',
                        ptBchCode   : $('#ohdIVCBchCode').val(),
                        ptDocNo     : nDocNoCallBack,
                        ptDocKey    :'TACTSBHD',
                    }
                    JCNxUPFInsertDataFile(oIVCXCallDataTableFile);

                        switch(nStaCallBack){
                            case '1' :
                                JSvIVCCallPageEdit(nDocNoCallBack);
                            break;
                            case '2' :
                                JSvIVCCallPageAdd();
                            break;
                            case '3' :
                                JSvIVCCallPageList();
                            break;
                            default :
                                JSvIVCCallPageEdit(nDocNoCallBack);
                        }

                }else{
                    var tMessageError = aDataReturnEvent['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ลบเอกสาร
    function JSoIVCDelDocSingle(ptCurrentPage, ptDocNo){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            if(typeof(ptDocNo) != undefined && ptDocNo != ""){
                var tTextConfrimDelSingle   = $('#oetTextComfirmDeleteSingle').val()+"&nbsp"+ptDocNo+"&nbsp"+$('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvIVCModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvIVCModalDelDocSingle').modal('show');
                $('#odvIVCModalDelDocSingle #osmIVCConfirmPdtDTTemp').unbind().click(function(){
                    JCNxOpenLoading();
                    $.ajax({
                        type    : "POST",
                        url     : "docInvoiceCustomerBillEventDelete",
                        data    : {'tDataDocNo' : ptDocNo},
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            var aReturnData = JSON.parse(oResult);
                            if(aReturnData['nStaEvent'] == '1') {
                                $('#odvIVCModalDelDocSingle').modal('hide');
                                $('#odvIVCModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function () {
                                    JSvIVCCallPageDataTable(ptCurrentPage);
                                }, 500);
                            }else{
                                JCNxCloseLoading();
                                FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
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
    function JSoIVCDelDocMultiple(){
        var aDataDelMultiple    = $('#odvIVCModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
                url     : "docInvoiceCustomerBillEventDelete",
                data    : {'tDataDocNo' : aNewIdDelete},
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function () {
                            $('#odvIVCModalDelDocMultiple').modal('hide');
                            $('#odvIVCModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvIVCModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                                JSvIVCCallPageDataTable();
                            }, 1000);
                    } else {
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
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
    function JStIVCFindObjectByKey(array,key,value){
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    //ยกเลิกเอกสาร
    function JSxIVCDocumentCancel(pbIsConfirm){
        var tDataDocNo = $('#oetIVCDocNo').val();

        if(pbIsConfirm){
            $.ajax({
                type    : "POST",
                url     : "docInvoiceCustomerBillEventCancel",
                data    : {
                    'tDataDocNo' : tDataDocNo 
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    $("#odvIVCPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    JSvIVCCallPageList();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            $('#odvIVCPopupCancel').modal({backdrop:'static',keyboard:false});
            $("#odvIVCPopupCancel").modal("show");
        }
    }

        //อนุมัติเอกสาร
        function JSxIVCDocumentApv(pbIsConfirm){
            try{
                if(pbIsConfirm){
                    $("#odvIVCPopupApv").modal('hide');

                    var tDocNo    = $('#oetIVCDocNo').val();
                    var tBchCode  = $('#ohdIVCBchCode').val();

                    $.ajax({
                        type    : "POST",
                        url     : "docInvoiceCustomerBillApprove",
                        data    : {
                            'tDocNo'     : tDocNo,
                            'tBchCode'   : tBchCode,
                            'tRefIntDoc' : $('#oetIVRefInt').val()
                        },
                        cache   : false,
                        timeout : 0,
                        success : function(tResult) {
                            $("#odvIVCPopupApv").modal("hide");
                            $('.modal-backdrop').remove();
                            var aReturnData = JSON.parse(tResult);
                            JSvIVCCallPageList();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }else{
                    $("#odvIVCPopupApv").modal('show');
                }
            }catch(err){
                console.log("JSxIVCDocumentApv Error: ", err);
            }
            }

</script>