
$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose();
    JSxMNTGetPageForm();
});

function JSxMNTGetPageForm(){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "mntDocStaPageForm",
        data: { 
            tMNTTypePage: $('#ohdMNTTypePage').val()
        },
        cache: false,
        timeout: 0,
        success: function(tResultHtml){
            $('#oliMNTTitleEdit').hide();
            $('#odvMNTBtnGrpInfo').hide();
            $('#obtMNTCallPageAdd').show();
            $('#oliMNTTitleAdd').hide();
            $('#odvMNTBtnGrpAddEdit').hide();
            $("#odvMNTPageFrom").html(tResultHtml);
            // JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSxMNTGetPageSumary(){
    JCNxOpenLoading();
    $.ajax({
        type    : "POST",
        url     : "mntDocStaPageSumary",
        data    : { 
            tMNTTypePage    : $('#ohdMNTTypePage').val(),
            tMNTDocDateFrom : $('#oetMNTDocDateFrom').val(),
            tMNTDocDateTo   : $('#oetMNTDocDateTo').val(),
            tMNTDocType     : $('#ocmMNTDocType').val(),
            tMNTBchCode     : $('#oetMNTBchCode').val(),
            tMNTNotiType    : $('#ocmMNTTypeNoti').val()
        },
        cache: false,
        timeout: 0,
        success: function(tResultHtml){
            $("#odvCheckdocSumary").html(tResultHtml);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSxMNTGetPageDataTable(){
    JCNxOpenLoading();
    $.ajax({
        type    : "POST",
        url     : "mntDocStaPageDataTable",
        data    : { 
            tMNTTypePage    : $('#ohdMNTTypePage').val(),
            tMNTDocDateFrom : $('#oetMNTDocDateFrom').val(),
            tMNTDocDateTo   : $('#oetMNTDocDateTo').val(),
            tMNTDocType     : $('#ocmMNTDocType').val(),
            tMNTBchCode     : $('#oetMNTBchCode').val(),
            tMNTNotiType    : $('#ocmMNTTypeNoti').val()
        },
        cache: false,
        timeout: 0,
        success: function(tResultHtml){
            $("#odvCheckdocDataTable").html(tResultHtml);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

$('#obtMNTCallPageAdd').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSvMNTCallPageAdd();
    }else{
        JCNxShowMsgSessionExpired();
    }
});

$('#obtMNTCallBackPage').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        JSxMNTGetPageForm();
    }else{
        JCNxShowMsgSessionExpired();
    }
});
    

function JSvMNTCallPageAdd(){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "mntDocStaPageAdd",
        cache: false,
        success: function(tResult){
            // var aReturnData = JSON.parse(tResult);
            // if(aReturnData['nStaEvent'] == '1'){
          
                    $('#oliMNTTitleEdit').hide();
                    $('#odvMNTBtnGrpInfo').hide();
                    $('#obtMNTCallPageAdd').hide();
                    $('#oliMNTTitleAdd').show();
                    $('#odvMNTBtnGrpAddEdit').show();
                    $('#odvMNTPageFrom').html(tResult);
                
            // }else{
            //     var tMessageError = aReturnData['tStaMessg'];
            //     FSvCMNSetMsgErrorDialog(tMessageError);
            // }
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}


function JSnAddEditMntDoc(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddMntDoc').validate().destroy();

        $('#ofmAddMntDoc').validate({
            rules: {
                oetMNTBchName: { "required": {} },
                oetMNTDesc1: { "required": {} },
                oetMNTDesc2: { "required": {} },
            },
            messages: {
                oetMNTBchName: {
                    "required": $('#oetMNTBchName').attr('data-validate-required'),
                },
                oetMNTDesc1: {
                    "required": $('#oetMNTDesc1').attr('data-validate-required'),
                },
                oetMNTDesc2: {
                    "required": $('#oetMNTDesc2').attr('data-validate-required'),
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
                $(element).closest('.form-group').addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                var nStaCheckValid = $(element).parents('.form-group').find('.help-block').length
                if (nStaCheckValid != 0) {
                    $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
                }
            },
            submitHandler: function(form) {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: 'mntStaDocEventAdd',
                    data: $('#ofmAddMntDoc').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                            $("#odvMntModalConfirm").modal("hide");
                            JSxMNTGetPageForm();
                            JCNxCloseLoading();
                           
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//ส่งข้อตวาม MQ
$('#obtMntSendNoti').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        $('#odvMntModalConfirm').modal({backdrop:'static',keyboard:false});
        $("#odvMntModalConfirm").modal("show");
    }else{
        JCNxShowMsgSessionExpired();
    }
});