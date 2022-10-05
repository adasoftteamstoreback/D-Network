var nStaInterfaceImportBrowseType = $('#oetInterfaceImportStaBrowse').val();
var tCallInterfaceImportBackOption = $('#oetInterfaceImportCallBackOption').val();
$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); 
});

//function      : Event Click Checkbox all  
//Parameters    : 
//Creator       : 05/03/2020 nale
$('#ocmINMChkAll').click(function() {

    if ($(this).prop('checked') == true) {
        $('.progress-bar-chekbox').prop('checked', true);
    } else {
        $('.progress-bar-chekbox').prop('checked', false);
    }
});

//function      : DefualValueProgress
//Parameters    :
//Creator       : 06/03/2020 nale
function JSxINMDefualValueProgress() {
    $('.xWINMTextDisplay').css('display', 'none').removeClass('text-success').removeClass('text-danger').text('').data('status', '2');
}

//function      : UpdateProgress
//Parameters    : pnPer ptType
//Creator       : 05/03/2020 nale
function JSxINMUpdateProgress(pnPer, ptType) {

    $('#odvINM' + ptType + 'ProgressBar').attr('aria-valuenow', pnPer);
    $('#odvINM' + ptType + 'ProgressBar').css('width', pnPer + '%');
    $('#odvINM' + ptType + 'ProgressBar').text(pnPer + '%');
    let nSuccessType = 0;
    if (pnPer == 100) {
        $('#odvINM' + ptType + 'ProgressBar').attr('status', 2);
        nSuccessType = JSxINMCheckSuccessProgress();

        setTimeout(() => {
            $('#odvINM' + ptType + 'ProgressBar').parent().hide();
            $('#ospINM' + ptType + 'ProgressBar').css('color', 'green');
            $('#ospINM' + ptType + 'ProgressBar').show();
            let tstingshow = $('#ospINM' + ptType + 'ProgressBar').attr('distext');
            $('#ospINM' + ptType + 'ProgressBar').text(tstingshow);

        }, 3000);

    }
    return nSuccessType;
}

//function      : CheckSuccessProgress 
//Parameters    : 
//Creator       : 05/03/2020 nale
//Return        : 1 = success , 2 = pendding
function JSbINMCheckSuccessProgress() {
    let nCounUnSucees = 0;
    $('.progress-bar-chekbox:checked').each(function() {
        let tIdElement = $(this).attr('idpgb');
        if ($('.' + tIdElement).data('status') == 2) {
            nCounUnSucees++;
        }
    });

    if (nCounUnSucees > 0) {
        return false;
    } else {
        return true;
    }
}

//function      : Click Confrim  
//Parameters    : 
//Creator       : 05/03/2020 nale
$('#obtInterfaceImportConfirm').click(function() {
    JCNxOpenLoading();
    JSxINMDefualValueProgress();
    JSxINMCallRabbitMQ();
});

//function      : Call Rabbit MQ 
//Parameters    : 
//Creator       : 05/03/2020 nale
function JSxINMCallRabbitMQ() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        $.ajax({
            type    : "POST",
            url     : "interfaceimportAction",
            data    : $('#ofmInterfaceImport').serialize() + "&ptTypeEvent=" + 'getpassword',
            cache   : false,
            Timeout : 0,
            success : function(tResult) {
                var aResult = JSON.parse(tResult);
                if (aResult.tHost == '' || aResult.tPort == '' || aResult.tPassword == '' || aResult.tUser == '' || aResult.tVHost == "") {
                    alert('Connect ใน ตั้งค่า Config ไม่ครบ');
                    JCNxCloseLoading();
                    return;
                } else {
                    var tPassword = JCNtAES128DecryptData(aResult.tPassword, '5YpPTypXtwMML$u@', 'zNhQ$D%arP6U8waL');

                    //ส่งค่า
                    $.ajax({
                        type    : "POST",
                        url     : "interfaceimportAction",
                        data    : $('#ofmInterfaceImport').serialize() + "&ptTypeEvent=" + 'confirm' + '&tPassword=' + tPassword,
                        cache   : false,
                        Timeout : 0,
                        success : function(tResult) {
                            $('#obtInterfaceImportConfirm').attr('disabled', true);
                            JCNxCloseLoading();
                            $('#odvInterfaceImportSuccess').modal('show');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus)
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
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
