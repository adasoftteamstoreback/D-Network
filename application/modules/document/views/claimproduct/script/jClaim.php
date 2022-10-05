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
        JSxCLMNavDefult('showpage_list');

        // รองรับการเข้ามาแบบ Noti
        var nStaCLMBrowseType = $('#oetCLMJumpBrwType').val();
        switch(nStaCLMBrowseType){
            case '2':
                var tAgnCode    = $('#oetCLMJumpAgnCode').val();
                var tBchCode    = $('#oetCLMJumpBchCode').val();
                var tDocNo      = $('#oetCLMJumpDocNo').val();
                JSvCLMCallPageEdit(tDocNo);
            break;
            default:
                JSvCLMCallPageList();
        }
    });

    //Control เมนู
    function JSxCLMNavDefult(ptType) {
        if(ptType == 'showpage_list'){
            $("#oliCLMTitleAdd").hide();
            $("#oliCLMTitleEdit").hide();
            $("#odvBtnAddEdit").hide();
            $('#odvBtnCLMPageAddorEdit').show();
            $('#obtCLMCancelDoc').hide();
        }else if(ptType == 'showpage_add'){
            $("#oliCLMTitleAdd").show();
            $("#oliCLMTitleEdit").hide();
            $("#odvBtnAddEdit").show();
            $('#odvBtnCLMPageAddorEdit').hide();
            $('.xCNBTNSaveDoc').show();
            $('#obtCLMCancelDoc').hide();
        }else if(ptType == 'showpage_edit'){
            $("#oliCLMTitleAdd").hide();
            $("#oliCLMTitleEdit").show();
            $("#odvBtnAddEdit").show();
            $('#odvBtnCLMPageAddorEdit').hide();
            $('#obtCLMCancelDoc').show();
        }

        //ล้างค่า
        localStorage.removeItem('CLM_LocalItemDataDelDtTemp');
        localStorage.removeItem('LocalItemData');
    }

    //Control ปุ่ม และอินพุตต่างๆ [เอกสารอยู่ระหว่างดำเนินการ StaPrc]
    function JSxCLMControlFormWhenDocProcess(){
        var tStatusPrcDoc = $('#ohdCLMStaPrc').val();
        var tStatusDoc    = $('#ohdCLMStaDoc').val();

        //1 : รออนุมัติ , 
        //2 : รอส่งสินค้าไปยังผู้จำหน่าย , 
        //3 : รอรับสินค้าจากผู้จำหน่าย , 
        //4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 
        //5 : รอส่งสินค้าให้ลูกค้า , 
        //6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 
        //7 : ปิดงานแล้ว
        if(tStatusPrcDoc >= 2 || tStatusDoc == 2){ 
            //ปุ่มเลือก
            $('.xCNBtnBrowseAddOn').addClass('disabled');
            $('.xCNBtnBrowseAddOn').attr('disabled',true);

            //ปุ่มเวลา
            $('.xCNBtnDateTime').addClass('disabled');
            $('.xCNBtnDateTime').attr('disabled',true);

            //อินพุต
            $('.xCNInputReadOnly').attr('readonly', true);

            //อินพุต
            $('.xControlRmk').attr('disabled', true);

            //เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();

            //พวก selectpicker
            $('.selectpicker').prop("disabled",true);

            //พวกปุ่มบันทึก
            $('.xCNBTNSaveDoc').hide();

            //ปุ่มยกเลิก
            $('#obtCLMCancelDoc').hide();
        }
    }

    //โหลด List
    function JSvCLMCallPageList(){
        $.ajax({
            type    : "GET",
            url     : "docClaimPageList",
            data    : {},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvContentCLM").html(tResult);
                JSxCLMNavDefult('showpage_list');
                JSvCLMCallPageDataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //โหลดข้อมูลตาราง
    function JSvCLMCallPageDataTable(pnPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var oAdvanceSearch = JSoCLMGetAdvanceSearchData();
            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = "1";
            }

            $.ajax({
                type    : "POST",
                url     : "docClaimDataTable",
                data    : {
                    oAdvanceSearch  : oAdvanceSearch,
                    nPageCurrent    : nPageCurrent
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxCLMNavDefult('showpage_list');
                        $('#ostContentCLM').html(aReturnData['tViewDataTable']);
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
    function JSvCLMClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWPageCLMPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWPageCLMPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSvCLMCallPageDataTable(nPageCurrent);
    }

    //ข้อมูลค้นหาขั้นสูง 
    function JSoCLMGetAdvanceSearchData() {
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
    function JSvCLMCallPageAdd(ptType){
        try{
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docClaimPageAdd",
                    cache   : false,
                    timeout : 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            JSxCLMNavDefult('showpage_add');
                            $('#odvContentCLM').html(aReturnData['tViewPageAdd']);
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
            console.log('JSvCLMCallPageAdd Error: ', err);
        }
    }

    //เข้าหน้าแบบ แก้ไข
    function JSvCLMCallPageEdit(ptDocumentNumber){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docClaimPageEdit",
                data    : {'ptCLMDocNo' : ptDocumentNumber},
                cache   : false,
                timeout : 0,
                success: function(tResult){
                    var aReturnData = JSON.parse(tResult);
                    if(aReturnData['nStaEvent'] == '1'){
                        JSxCLMNavDefult('showpage_edit');
                        $('#odvContentCLM').html(aReturnData['tViewPageAdd']);
                        JCNxCloseLoading();

                        //เช็คว่าเอกสารยกเลิก อยู่สถานะไหนเเล้ว
                        JSxCLMControlFormWhenDocProcess();
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
    $('#obtCLMSubmitFromDoc').unbind().click(function(){
        var tBchCode            = $('#ohdCLMBchCode').val();
        var tCstCode            = $('#oetCLMFrmCstCode').val();
        var tCarCode            = $('#oetCLMFrmCarCode').val();
        // var tMile               = $('#oetCLMCarMile').val();
        var tCheckIteminTable   = $('#otbCLMStep1Point1DocPdtAdvTableList .xWPdtItem').length;

        if(tBchCode == '' || tCstCode == '' || tCarCode == '' /*|| tMile == ''*/){

            //กรุณากรอกข้อมูลให้ครบถ้วน
            if(tBchCode == ''){
                var tTextWarning = 'กรุณาระบุสาขา';
            }else if(tCstCode == ''){
                var tTextWarning = 'กรุณาระบุข้อมูลลูกค้า';
            }else if(tCarCode == ''){
                var tTextWarning = 'กรุณาระบุข้อมูลรถของลูกค้า';
            }/*else{
                var tTextWarning = 'กรุณาระบุเลขไมล์รถของลูกค้า';
            }*/

            $('#odvCLMModalPleseDataInFill #ospCLMModalPleseDataInFill').text(tTextWarning);
            $('#odvCLMModalPleseDataInFill').modal('show');
            return;
        }else if(tCheckIteminTable == 0){

            //ไม่พบสินค้าใน Temp
            FSvCMNSetMsgWarningDialog('<?=language('document/transferreceiptOut/transferreceiptOut','tConditionPDTEmptyDetail')?>');
        }else{

            JCNxOpenLoading();

            //ผ่าน
            if($('#ohdCLMStaSaveOrSaveClaim').val() == 2){
                $('#ohdCLMStaSaveOrSaveClaim').val(2); //บันทึกและส่งเคลม
            }else{
                $('#ohdCLMStaSaveOrSaveClaim').val(1); //บันทึกเฉยๆ
            }

            $('#obtCLMSubmitDocument').click();
        }
    });

    //เพิ่มข้อมูล
    function JSxCLMAddEditDocument(){
        $('#ofmCLMFormAdd').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetCLMDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdCLMRoute").val()  ==  "docClaimEventAdd"){
                                if($('#ocbCLMStaAutoGenCode').is(':checked')){
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
                oetCLMDocDate    : {"required" : true},
                oetCLMDocTime    : {"required" : true},
            },
            messages: {
                oetCLMDocNo      : {"required" : $('#oetCLMDocNo').attr('data-validate-required')},
                oetCLMDocDate    : {"required" : $('#oetCLMDocDate').attr('data-validate-required')},
                oetCLMDocTime    : {"required" : $('#oetCLMDocTime').attr('data-validate-required')}
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
                if(!$('#ocbCLMStaAutoGenCode').is(':checked')){
                    if($("#ohdCLMRoute").val() ==  "docClaimEventAdd"){
                        JSxCLMValidateDocCodeDublicate();
                    }else{
                        JSxCLMSubmitEventByButton();
                    }
                }else{
                    JSxCLMSubmitEventByButton();
                }
            },
        });
    }

    //ถ้าปล่อยให้คีย์เองต้อง ตรวจสอบรหัสเอกสารว่าซ้ำไหม
    function JSxCLMValidateDocCodeDublicate(){
        $.ajax({
            type    : "POST",
            url     : "CheckInputGenCode",
            data    : {
                'tTableName'    : 'TCNTPdtClaimHD',
                'tFieldName'    : 'FTPchDocNo',
                'tCode'         : $('#oetCLMDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                
                $("#ohdCLMCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdCLMCheckDuplicateCode").val() != 1) {
                    $('#ofmCLMFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
                        if($('#ocbCLMStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdCLMCheckDuplicateCode").val() == 1) {
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
                $('#ofmCLMFormAdd').validate({
                    focusInvalid    : false,
                    onclick         : false,
                    onfocusout      : false,
                    onkeyup         : false,
                    rules           : {
                        oetCLMDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetCLMDocNo : {"dublicateCode"  : $('#oetCLMDocNo').attr('data-validate-duplicate')}
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
                        JSxCLMSubmitEventByButton();
                    }
                })

                $("#ofmCLMFormAdd").submit();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //บันทึก
    function JSxCLMSubmitEventByButton(){
        $.ajax({
            type    : "POST",
            url     : $("#ohdCLMRoute").val(),
            data    : $("#ofmCLMFormAdd").serialize(),
            cache   : false,
            timeout : 0,
            success : function(oResult){
                var aDataReturnEvent    = JSON.parse(oResult);
                if(aDataReturnEvent['nStaReturn'] == '1'){
                    var nStaCallBack      = aDataReturnEvent['nStaCallBack'];
                    var nDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                    var oCLMXCallDataTableFile = {
                        ptElementID : 'odvShowDataTable',
                        ptBchCode   : $('#ohdCLMBchCode').val(),
                        ptDocNo     : nDocNoCallBack,
                        ptDocKey    :'TCNTPdtClaimHD',
                    }
                    JCNxUPFInsertDataFile(oCLMXCallDataTableFile);

                    setTimeout(function(){
                        switch(nStaCallBack){
                            case '1' :
                                JSvCLMCallPageEdit(nDocNoCallBack);
                            break;
                            case '2' :
                                JSvCLMCallPageAdd();
                            break;
                            case '3' :
                                JSvCLMCallPageList();
                            break;
                            default :
                                JSvCLMCallPageEdit(nDocNoCallBack);
                        }
                    }, 3000);
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
    function JSoCLMDelDocSingle(ptCurrentPage, ptDocNo){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            if(typeof(ptDocNo) != undefined && ptDocNo != ""){
                var tTextConfrimDelSingle   = $('#oetTextComfirmDeleteSingle').val()+"&nbsp"+ptDocNo+"&nbsp"+$('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvCLMModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvCLMModalDelDocSingle').modal('show');
                $('#odvCLMModalDelDocSingle #osmCLMConfirmPdtDTTemp').unbind().click(function(){
                    JCNxOpenLoading();
                    $.ajax({
                        type    : "POST",
                        url     : "docClaimEventDelete",
                        data    : {'tDataDocNo' : ptDocNo},
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            var aReturnData = JSON.parse(oResult);
                            if(aReturnData['nStaEvent'] == '1') {
                                $('#odvCLMModalDelDocSingle').modal('hide');
                                $('#odvCLMModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function () {
                                    JSvCLMCallPageDataTable(ptCurrentPage);
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
    function JSoCLMDelDocMultiple(){
        var aDataDelMultiple    = $('#odvCLMModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
                url     : "docClaimEventDelete",
                data    : {'tDataDocNo' : aNewIdDelete},
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function () {
                            $('#odvCLMModalDelDocMultiple').modal('hide');
                            $('#odvCLMModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvCLMModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                                JSvCLMCallPageDataTable();
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
    function JStCLMFindObjectByKey(array,key,value){
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    //ยกเลิกเอกสาร
    function JSxCLMDocumentCancel(pbIsConfirm){
        var tDataDocNo = $('#oetCLMDocNo').val();

        if(pbIsConfirm){
            $.ajax({
                type    : "POST",
                url     : "docClaimEventCancel",
                data    : {
                    'tDataDocNo' : tDataDocNo 
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    $("#odvCLMPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    JSvCLMCallPageList();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            $('#odvCLMPopupCancel').modal({backdrop:'static',keyboard:false});
            $("#odvCLMPopupCancel").modal("show");
        }
    }

</script>