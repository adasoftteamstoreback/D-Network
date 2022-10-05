$("document").ready(function() {
    JSxCheckPinMenuClose(); 
    JSxDLVNavDefult('showpage_list');

    JSvDLVCallPageList();
});

// Control เมนู
function JSxDLVNavDefult(ptType) {
    if (ptType == 'showpage_list') { // แสดง
        $("#oliDLVTitle").show();
        $("#odvDLVBtnGrpInfo").show();
        $("#obtDLVCallPageAdd").show();
        $("#oliDLVTitleAdd").hide();
        $("#oliDLVTitleEdit").hide();
        $("#obtDLVCallBackPage").hide();
        $("#obtDLVPrintDoc").hide();
        $("#obtDLVCancelDoc").hide();
        $("#obtDLVApproveDoc").hide();
        $("#odvDLVBtnGrpSave").hide();
    } else if (ptType == 'showpage_add') { // แสดง
        $("#oliDLVTitle").show();
        $("#odvDLVBtnGrpSave").show();
        $("#oliDLVTitleAdd").show();
        $("#oliDLVTitleEdit").hide();
        $("#obtDLVCallBackPage").show();
        $("#obtDLVPrintDoc").hide();
        $("#obtDLVCancelDoc").hide();
        $("#obtDLVApproveDoc").hide();
        $("#odvDLVBtnGrpInfo").hide();
    } else if (ptType == 'showpage_edit') { // แสดง
        $("#oliDLVTitle").show();
        $("#odvDLVBtnGrpSave").show();
        $("#obtDLVApproveDoc").show();
        $("#obtDLVCancelDoc").show();
        $("#obtDLVCallBackPage").show();
        $("#oliDLVTitleEdit").show();
        $("#obtDLVPrintDoc").show();
        $("#oliDLVTitleAdd").hide();
        $("#odvDLVBtnGrpInfo").hide();
    }

    // ล้างค่า
    localStorage.removeItem('LocalItemData');
    localStorage.removeItem("DLV_LocalItemDataDelDtTemp");
}

// หน้าจอลิสต์ข้อมูล
function JSvDLVCallPageList() {
    $.ajax({
        type    : "GET",
        url     : "docDLVFormSearchList",
        cache   : false,
        timeout : 0,
        success: function(tResult) {
            $("#odvDLVContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); 
            JSxDLVNavDefult('showpage_list');
            JSvDLVCallPageDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// ตารางข้อมูล
function JSvDLVCallPageDataTable(pnPage) {
    JCNxOpenLoading();
    var oAdvanceSearch = JSoDLVGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type    : "POST",
        url     : "docDLVDataTable",
        data    : {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostDLVDataTableDocument').html(aReturnData['tDLVViewDataTableList']);
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
function JSoDLVGetAdvanceSearchData() {
    var oAdvanceSearchData = {
        tSearchAll              : $("#oetSearchAll").val().trim(),
        tSearchFrmBchCode       : $("#oetDLVFrmBchCode").val(),
        tSearchToBchCode        : $("#oetDLVToBchCode").val(),
        tSearchDocDateFrm       : $("#oetDLVDocDateFrm").val(),
        tSearchDocDateTo        : $("#oetDLVDocDateTo").val(),
        tSearchStaDoc           : $("#ocmDLVStaDoc").val()
    };
    return oAdvanceSearchData;
}

// เข้ามาแบบ insert
function JSvDLVCallPageAddDoc() {
    JCNxOpenLoading();
    $.ajax({
        type    : "POST",
        url     : "docDLVPageAdd",
        cache   : false,
        timeout : 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxDLVNavDefult('showpage_add');
                $('#odvDLVContentPageDocument').html(aReturnData['tDLVViewPageAdd']);

                window.scrollTo(0, 0);
                JSvDLVLoadPdtDataTableHtml();
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

// โหลดข้อมูลในสินค้า Temp
function JSvDLVLoadPdtDataTableHtml() {
    if ($("#ohdDLVRoute").val() == "docDLVEventAdd") {
        var tDLVDocNo = "";
    } else {
        var tDLVDocNo = $("#oetDLVDocNo").val();
    }
  
    $.ajax({
        type    : "POST",
        url     : "docDLVPdtAdvanceTableLoadData",
        data    : {
            'ptDLVDocNo'        : tDLVDocNo
        },
        cache   : false,
        Timeout : 0,
        success : function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#odvDLVDataPanelDetailPDT #odvDLVDataPdtTableDTTemp').html(aReturnData['tDLVPdtAdvTableHtml']);

                //เอกสารอ้างอิง
                JSxDLVCallPageHDDocRef();
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
}

// บันทึกข้อมูล - แก้ไขข้อมูล
function JSxDLVAddEditDocument() { 
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxDLVValidateFormDocument();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// บันทึกข้อมูล - แก้ไขข้อมูล
function JSxDLVValidateFormDocument(){
    if($("#ohdDLVCheckClearValidate").val() != 0){
        $('#ofmDLVFormAdd').validate().destroy();
    }

    $('#ofmDLVFormAdd').validate({
        focusInvalid: true,
        rules: {
            oetDLVDocNo : {
                "required" : {
                    depends: function (oElement) {
                        if($("#ohdDLVRoute").val()  ==  "docDLVEventAdd"){
                            if($('#ocbDLVStaAutoGenCode').is(':checked')){
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
            oetDLVCstName       : {"required" : true},
            oetDLVBchName       : {"required" : true},
            oetDLVFrmBchName    : {"required" : true},
            oetDLVToBchName     : {"required" : true}
        },
        messages: {
            oetDLVCstName       : {"required" : $('#oetDLVCstName').attr('data-validate-required')},
            oetDLVDocNo         : {"required" : $('#oetDLVDocNo').attr('data-validate-required')},
            oetDLVBchName       : {"required" : $('#oetDLVBchName').attr('data-validate-required')},
            oetDLVFrmBchName    : {"required" : $('#oetDLVFrmBchName').attr('data-validate-required')},
            oetDLVToBchName     : {"required" : $('#oetDLVToBchName').attr('data-validate-required')}
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
            if(!$('#ocbDLVStaAutoGenCode').is(':checked')){
                JSxDLVValidateDocCodeDublicate();
            }else{
                JSxDLVSubmitEventByButton('');
            }
        },
    });
}

// Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
function JSxDLVValidateDocCodeDublicate(){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url : "CheckInputGenCode",
        data: {
            'tTableName'    : 'TARTDoHD',
            'tFieldName'    : 'FTXshDocNo',
            'tCode'         : $('#oetDLVDocNo').val()
        },
        success: function (oResult) {
            var aResultData = JSON.parse(oResult);
            $("#ohdDLVCheckDuplicateCode").val(aResultData["rtCode"]);

            if($("#ohdDLVCheckClearValidate").val() != 1) {
                $('#ofmDLVFormAdd').validate().destroy();
            }

            $.validator.addMethod('dublicateCode', function(value,element){
                if($("#ohdDLVRoute").val() == "docDLVEventAdd"){
                    if($('#ocbDLVStaAutoGenCode').is(':checked')) {
                        return true;
                    }else{
                        if($("#ohdDLVCheckDuplicateCode").val() == 1) {
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
            $('#ofmDLVFormAdd').validate({
                focusInvalid    : false,
                onclick         : false,
                onfocusout      : false,
                onkeyup         : false,
                rules: {
                    oetDLVDocNo : {"dublicateCode": {}}
                },
                messages: {
                    oetDLVDocNo : {"dublicateCode"  : $('#oetDLVDocNo').attr('data-validate-duplicate')}
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
                    JSxDLVSubmitEventByButton('');
                }
            })

            if($("#ohdDLVCheckClearValidate").val() != 1) {
                $("#ofmDLVFormAdd").submit();
                $("#ohdDLVCheckClearValidate").val(1);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// บันทึกข้อมูล - แก้ไขข้อมูล (วิ่งเข้า controller)
function JSxDLVSubmitEventByButton(ptType){
    var tDLVDocNo = '';

    if($("#ohdDLVRoute").val() !=  "docDLVEventAdd"){
        var tDLVDocNo    = $('#oetDLVDocNo').val();
    }

    // อินพุต
    $(".form-control").attr("disabled", false);

    $.ajax({
        type    : "POST",
        url     : "docDLVChkHavePdtForDocDTTemp",
        data    : {
            'ptDLVDocNo'         : tDLVDocNo,
            'tDLVSesSessionID'   : $('#ohdSesSessionID').val(),
            'tDLVUsrCode'        : $('#ohdDLVUsrCode').val(),
            'tDLVLangEdit'       : $('#ohdDLVLangEdit').val(),
            'tSesUsrLevel'       : $('#ohdSesUsrLevel').val()
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aDataReturnChkTmp   = JSON.parse(oResult);
            $('.xWDLVDisabledOnApv').attr('disabled',false);
            if (aDataReturnChkTmp['nStaReturn'] == '1'){
                $.ajax({
                    type    : "POST",
                    url     : $("#ohdDLVRoute").val(),
                    data    : $("#ofmDLVFormAdd").serialize(),
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        var aDataReturnEvent    = JSON.parse(oResult);
                        if(aDataReturnEvent['nStaReturn'] == '1'){
                            var nDLVStaCallBack      = aDataReturnEvent['nStaCallBack'];
                            var nDLVDocNoCallBack    = aDataReturnEvent['tCodeReturn'];
                          
                            let oDLVCallDataTableFile = {
                                ptElementID : 'odvDLVShowDataTable',
                                ptBchCode   : $('#oetDLVBchCode').val(),
                                ptDocNo     : nDLVDocNoCallBack,
                                ptDocKey    :'TARTDoHD',
                            }
                            JCNxUPFInsertDataFile(oDLVCallDataTableFile);

                            if( ptType == 'approve' ){
                                JSxDLVApproveDocument(false);
                            }else{
                                switch(nDLVStaCallBack){
                                    case '1' :
                                        JSvDLVCallPageEdit(nDLVDocNoCallBack);
                                    break;
                                    case '2' :
                                        JSvDLVCallPageAddDoc();
                                    break;
                                    case '3' :
                                        JSvDLVCallPageList();
                                    break;
                                    default :
                                        JSvDLVCallPageEdit(nDLVDocNoCallBack);
                                }
                            }
                        }else{
                            var tMessageError = aDataReturnEvent['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error   : function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
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
        }
    });
}

// อนุมัติเอกสาร
function JSxDLVApproveDocument(pbIsConfirm) { 
    try {   
        if (pbIsConfirm) {
            $("#odvDLVModalAppoveDoc").modal('hide');
            var tDocNo                  = $('#oetDLVDocNo').val();
            var tBchCode                = $('#ohdDLVBchCode').val();

            $.ajax({
                type    : "POST",
                url     : "docDLVApproveDocument",
                data    : {
                    tDocNo                  : tDocNo,
                    tBchCode                : tBchCode
                },
                cache   : false,
                timeout : 0,
                success : function(tResult) {
                    var aReturnData = JSON.parse(tResult);
                    var tMessageError = aReturnData['tStaMessg'];
                    if (aReturnData['nStaEvent'] == '1') {
                        JSvDLVCallPageEdit(tDocNo);
                    } else {
                        setTimeout(function(){
                            FSvCMNSetMsgErrorDialog(tMessageError);
                            JCNxCloseLoading();
                        }, 500);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            $("#odvDLVModalAppoveDoc").modal('show');
        }
    } catch (err) {
        console.log("JSxDLVApproveDocument Error: ", err);
    }
}

// เข้าหน้าแบบ แก้ไข
function JSvDLVCallPageEdit(ptDocumentNumber) { 
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docDLVPageEdit",
            data    : {
                'ptDLVDocNo': ptDocumentNumber
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult)
                if( aReturnData['nStaEvent'] == '1' ){
                    JSxDLVNavDefult('showpage_edit');
                    $('#odvDLVContentPageDocument').html(aReturnData['tViewPageEdit']);

                    window.scrollTo(0, 0);
                    JSvDLVLoadPdtDataTableHtml();
                }else{
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