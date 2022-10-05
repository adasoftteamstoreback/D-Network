
$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxMNTGetPageForm();
    // $('.progress').hide();
    // console.log(JCNtAES128EncryptData('Moe29161',tKey,tIV));
});

//Functionality : Event Call API
//Parameters : 
//Creator : 11/01/2021
//Return : -
//Return Type : -
function JSxMNTGetPageForm(){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "mntNewStaPageForm",
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



//Functionality : Event Call API
//Parameters : 
//Creator : 11/01/2021
//Return : -
//Return Type : -
function JSxMNTGetPageDataTable(){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "mntNewStaPageDataTable",
        data: { 
            tMNTTypePage: $('#ohdMNTTypePage').val(),
            tMNTDocDateFrom : $('#oetMNTDocDateFrom').val(),
            tMNTDocDateTo : $('#oetMNTDocDateTo').val(),
            tMNTDocType : $('#ocmMNTDocType').val(),
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



    // Event Click Button Add Page
    $('#obtMNTCallPageAdd').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvMNTCallPageAdd();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Button Add Page
    $('#obtMNTCallBackPage').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxMNTGetPageForm();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    






