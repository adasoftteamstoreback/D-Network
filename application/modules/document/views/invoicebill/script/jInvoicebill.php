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
        JSxIVBNavDefult('showpage_list');

        // รองรับการเข้ามาแบบ Noti
        var nStaIVBBrowseType = $('#oetIVBJumpBrwType').val();
        switch(nStaIVBBrowseType){
            case '2':
                var tAgnCode    = $('#oetIVBJumpAgnCode').val();
                var tBchCode    = $('#oetIVBJumpBchCode').val();
                var tDocNo      = $('#oetIVBJumpDocNo').val();
                JSvIVBCallPageEdit(tDocNo);
            break;
            default:
                JSvIVBCallPageList();
        }
    });

    //Control เมนู
    function JSxIVBNavDefult(ptType) {
        if(ptType == 'showpage_list'){
            $("#oliIVBTitleAdd").hide();
            $("#oliIVBTitleEdit").hide();
            $("#odvBtnAddEdit").hide();
            $('#odvBtnIVBPageAddorEdit').show();
            $('#obtIVBCancelDoc').hide();
            $('#obtIVBApproveDoc').hide();
        }else if(ptType == 'showpage_add'){
            $("#oliIVBTitleAdd").show();
            $("#oliIVBTitleEdit").hide();
            $("#odvBtnAddEdit").show();
            $('#obtIVBPrintDoc').hide();
            $('#odvBtnIVBPageAddorEdit').hide();
            $('.xCNBTNSaveDoc').show();
            $('#obtIVBCancelDoc').hide();
            $('#obtIVBApproveDoc').hide();
        }else if(ptType == 'showpage_edit'){
            $("#oliIVBTitleAdd").hide();
            $("#oliIVBTitleEdit").show();
            $("#odvBtnAddEdit").show();
            $('#odvBtnIVBPageAddorEdit').hide();
            $('#obtIVBPrintDoc').show();
            $('#obtIVBCancelDoc').show();
            $('#obtIVBApproveDoc').show();

        }

        //ล้างค่า
        localStorage.removeItem('IVB_LocalItemDataDelDtTemp');
        localStorage.removeItem('LocalItemData');
    }

    //Control ปุ่ม และอินพุตต่างๆ [เอกสารอยู่ระหว่างดำเนินการ StaPrc]
    function JSxIVBControlFormWhenDocProcess(){
        var tStatusPrcDoc = $('#ohdIVBStaPrc').val();
        var tStatusDoc = $('#ohdIVBStaDoc').val();
        var tStatusApv = $('#ohdIVBStaApv').val();

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
            $('#obtIVBCancelDoc').hide();

            //ปุ่มอนุมัติ
            $('#obtIVBApproveDoc').hide();
        }
    }

    //โหลด List
    function JSvIVBCallPageList(){
        $.ajax({
            type    : "GET",
            url     : "docInvoiceBillPageList",
            data    : {},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvContentIVB").html(tResult);
                JSxIVBNavDefult('showpage_list');
                JSvIVBCallPageDataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //โหลดข้อมูลตาราง
    function JSvIVBCallPageDataTable(pnPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var oAdvanceSearch = JSoIVBGetAdvanceSearchData();
            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = "1";
            }

            $.ajax({
                type    : "POST",
                url     : "docInvoiceBillDataTable",
                data    : {
                    oAdvanceSearch  : oAdvanceSearch,
                    nPageCurrent    : nPageCurrent
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxIVBNavDefult('showpage_list');
                        $('#ostContentIVB').html(aReturnData['tViewDataTable']);
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
    function JSvIVBClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWPageIVBPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWPageIVBPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSvIVBCallPageDataTable(nPageCurrent);
    }

    //ข้อมูลค้นหาขั้นสูง 
    function JSoIVBGetAdvanceSearchData() {
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
    function JSvIVBCallPageAdd(ptType){
        try{
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docInvoiceBillPageAdd",
                    cache   : false,
                    timeout : 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            JSxIVBNavDefult('showpage_add');
                            $('#odvContentIVB').html(aReturnData['tViewPageAdd']);
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
            console.log('JSvIVBCallPageAdd Error: ', err);
        }
    }

    //เข้าหน้าแบบ แก้ไข
    function JSvIVBCallPageEdit(ptDocumentNumber){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docInvoiceBillPageEdit",
                data    : {'ptIVBDocNo' : ptDocumentNumber},
                cache   : false,
                timeout : 0,
                success: function(tResult){
                    var aReturnData = JSON.parse(tResult);
                    if(aReturnData['nStaEvent'] == '1'){
                        JSxIVBNavDefult('showpage_edit');
                        $('#odvContentIVB').html(aReturnData['tViewPageAdd']);
                        JCNxCloseLoading();

                        //เช็คว่าเอกสารยกเลิก อยู่สถานะไหนเเล้ว
                        JSxIVBControlFormWhenDocProcess();
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
    $('#obtIVBSubmitFromDoc').unbind().click(function(){
        var tBchCode            = $('#ohdIVBBchCode').val();
        var tSPLCode            = $('#oetIVBSPLName').val();
        var tchkitemsteptwo     = $(".xWPdtItemStep2").length;
        var dDateDue            = $("#oetIVBPaidDate").val();
        var tCheckIteminTable   = $('#otbCLMStep1Point2DocPdtAdvTableList .xWPdtItemStep2').length;
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');

        if(tBchCode == '' || tSPLCode == '' || dDateDue == '' /*|| tMile == ''*/ || tStepNow != '2' || tchkitemsteptwo < 1){
            //กรุณากรอกข้อมูลให้ครบถ้วน
            if(tBchCode == ''){
                var tTextWarning = 'กรุณาระบุสาขา';
            }else if(tSPLCode == ''){
                var tTextWarning = 'กรุณาระบุข้อมูลผู้จำหน่าย';
            }else if(tStepNow != '2'){
                var tTextWarning = 'กรุณาตรวจสอบและยืนยันก่อน';
            }else if(tchkitemsteptwo < 1){
                var tTextWarning = 'กรุณาตรวจสอบและยืนยันก่อน';
            }else if(dDateDue == ''){
                var tTextWarning = 'กรุณาเลือกวันที่นัดรับเงิน/ชำระเงินก่อน';
            }/*else{
                var tTextWarning = 'กรุณาระบุเลขไมล์รถของลูกค้า';
            }*/

            $('#odvIVBModalPleseDataInFill #ospIVBModalPleseDataInFill').text(tTextWarning);
            $('#odvIVBModalPleseDataInFill').modal('show');
            return;
        }else if(tCheckIteminTable == 0){

            //ไม่พบสินค้าใน Temp
            FSvCMNSetMsgWarningDialog('<?=language('document/transferreceiptOut/transferreceiptOut','กรุณาเลือกเอกสารที่จะนำมาวางบิล')?>');
        }else{

            JCNxOpenLoading();

            $('#obtIVBSubmitDocument').click();
        }
    });

    //เพิ่มข้อมูล
    function JSxIVBAddEditDocument(){
        $('#ofmIVBFormAdd').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetIVBDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdIVBRoute").val()  ==  "docInvoiceBillEventAdd"){
                                if($('#ocbIVBStaAutoGenCode').is(':checked')){
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
                oetIVBDocDate    : {"required" : true},
                oetIVBDocTime    : {"required" : true},
            },
            messages: {
                oetIVBDocNo      : {"required" : $('#oetIVBDocNo').attr('data-validate-required')},
                oetIVBDocDate    : {"required" : $('#oetIVBDocDate').attr('data-validate-required')},
                oetIVBDocTime    : {"required" : $('#oetIVBDocTime').attr('data-validate-required')}
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
                if(!$('#ocbIVBStaAutoGenCode').is(':checked')){
                    if($("#ohdIVBRoute").val() ==  "docInvoiceBillEventAdd"){
                        JSxIVBValidateDocCodeDublicate();
                    }else{
                        JSxIVBSubmitEventByButton();
                    }
                }else{
                    JSxIVBSubmitEventByButton();
                }
            },
        });
    }

    //ถ้าปล่อยให้คีย์เองต้อง ตรวจสอบรหัสเอกสารว่าซ้ำไหม
    function JSxIVBValidateDocCodeDublicate(){
        $.ajax({
            type    : "POST",
            url     : "CheckInputGenCode",
            data    : {
                'tTableName'    : 'TCNTPdtInvoiceBillHD',
                'tFieldName'    : 'FTPchDocNo',
                'tCode'         : $('#oetIVBDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                
                $("#ohdIVBCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdIVBCheckDuplicateCode").val() != 1) {
                    $('#ofmIVBFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdIVBRoute").val() == "docInvoiceBillEventAdd"){
                        if($('#ocbIVBStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdIVBCheckDuplicateCode").val() == 1) {
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
                $('#ofmIVBFormAdd').validate({
                    focusInvalid    : false,
                    onclick         : false,
                    onfocusout      : false,
                    onkeyup         : false,
                    rules           : {
                        oetIVBDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetIVBDocNo : {"dublicateCode"  : $('#oetIVBDocNo').attr('data-validate-duplicate')}
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
                        JSxIVBSubmitEventByButton();
                    }
                })

                $("#ofmIVBFormAdd").submit();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //บันทึก
    function JSxIVBSubmitEventByButton(){
        $('.selectpicker').removeAttr("disabled")
        $.ajax({
            type    : "POST",
            url     : $("#ohdIVBRoute").val(),
            data    : $("#ofmIVBFormAdd").serialize(),
            cache   : false,
            timeout : 0,
            success : function(oResult){
                var aDataReturnEvent    = JSON.parse(oResult);
                if(aDataReturnEvent['nStaReturn'] == '1'){
                    var nStaCallBack      = aDataReturnEvent['nStaCallBack'];
                    var nDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                    let oIVBXCallDataTableFile = {
                        ptElementID : 'odvShowDataTable',
                        ptBchCode   : $('#ohdIVBBchCode').val(),
                        ptDocNo     : nDocNoCallBack,
                        ptDocKey    :'TACTPbHD',
                    }
                    JCNxUPFInsertDataFile(oIVBXCallDataTableFile);

                        switch(nStaCallBack){
                            case '1' :
                                JSvIVBCallPageEdit(nDocNoCallBack);
                            break;
                            case '2' :
                                JSvIVBCallPageAdd();
                            break;
                            case '3' :
                                JSvIVBCallPageList();
                            break;
                            default :
                                JSvIVBCallPageEdit(nDocNoCallBack);
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
    function JSoIVBDelDocSingle(ptCurrentPage, ptDocNo){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            if(typeof(ptDocNo) != undefined && ptDocNo != ""){
                var tTextConfrimDelSingle   = $('#oetTextComfirmDeleteSingle').val()+"&nbsp"+ptDocNo+"&nbsp"+$('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvIVBModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvIVBModalDelDocSingle').modal('show');
                $('#odvIVBModalDelDocSingle #osmIVBConfirmPdtDTTemp').unbind().click(function(){
                    JCNxOpenLoading();
                    $.ajax({
                        type    : "POST",
                        url     : "docInvoiceBillEventDelete",
                        data    : {'tDataDocNo' : ptDocNo},
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            var aReturnData = JSON.parse(oResult);
                            if(aReturnData['nStaEvent'] == '1') {
                                $('#odvIVBModalDelDocSingle').modal('hide');
                                $('#odvIVBModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function () {
                                    JSvIVBCallPageDataTable(ptCurrentPage);
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
    function JSoIVBDelDocMultiple(){
        var aDataDelMultiple    = $('#odvIVBModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
                url     : "docInvoiceBillEventDelete",
                data    : {'tDataDocNo' : aNewIdDelete},
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function () {
                            $('#odvIVBModalDelDocMultiple').modal('hide');
                            $('#odvIVBModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvIVBModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                                JSvIVBCallPageDataTable();
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
    function JStIVBFindObjectByKey(array,key,value){
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    //ยกเลิกเอกสาร
    function JSxIVBDocumentCancel(pbIsConfirm){
        var tDataDocNo = $('#oetIVBDocNo').val();

        if(pbIsConfirm){
            $.ajax({
                type    : "POST",
                url     : "docInvoiceBillEventCancel",
                data    : {
                    'tDataDocNo' : tDataDocNo 
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    $("#odvIVBPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    JSvIVBCallPageList();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            $('#odvIVBPopupCancel').modal({backdrop:'static',keyboard:false});
            $("#odvIVBPopupCancel").modal("show");
        }
    }

        //อนุมัติเอกสาร
        function JSxIVBDocumentApv(pbIsConfirm){
            try{
                if(pbIsConfirm){
                    $("#odvIVBPopupApv").modal('hide');

                    var tDocNo    = $('#oetIVBDocNo').val();
                    var tBchCode  = $('#ohdIVBBchCode').val();

                    $.ajax({
                        type    : "POST",
                        url     : "docInvoiceBillApprove",
                        data    : {
                            'tDocNo'     : tDocNo,
                            'tBchCode'   : tBchCode,
                            'tRefIntDoc' : $('#oetIVRefInt').val()
                        },
                        cache   : false,
                        timeout : 0,
                        success : function(tResult) {
                            $("#odvIVBPopupApv").modal("hide");
                            $('.modal-backdrop').remove();
                            var aReturnData = JSON.parse(tResult);
                            JSvIVBCallPageList();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }else{
                    $("#odvIVBPopupApv").modal('show');
                }
            }catch(err){
                console.log("JSxIVBDocumentApv Error: ", err);
            }
            }

</script>