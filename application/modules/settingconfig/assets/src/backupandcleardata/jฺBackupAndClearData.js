var nBrowseType = $('#oetBACStaBrowse').val();
var tCallBackOption = $('#oetBACCallBackOption').val();

$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxLimNavDefult();
    if (nBrowseType != '1') {
        JSvCallPageBACList();
    }
    $('.xCNHideBtnStaAlw').hide();
    

});


//function : Function Clear Defult Button Card
//Parameters : Document Ready
//Creator : 07/10/2020 Witsarut (Bell)
//Return : Show Tab Menu
//Return Type : -
function JSxLimNavDefult(){
    if (nBrowseType != 1 || nBrowseType == undefined) {
        $('#odvBtnAddEdit').hide();
        $('#oliAdvTitleEdit').hide();
        $('#odvBtnSelectBAC').hide();
        $('.panel-heading').show();
    }else{
        $('#odvModalBody #odvLimMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliLimNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvLimBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNLimBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNLimBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

//function : Call Page SettingConditionPeriod list  
//Parameters : Document Redy And Event Button
//Creator : 07/10/2020 Witsarut (Bell)
//Return : View
//Return Type : View
function JSvCallPageBACList(pnPage){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'unundefineddefined' && nStaSession == 1){
        JCNxOpenLoading();
        $.ajax({
            type : "POST",
            url: "BACList",
            cache : false,
            data: {
                nPageCurrent: pnPage,
            },
            timeout: 0,
            success: function(tResult) {
                $('#odvBACPageDocument').html(tResult);
                JSvBACCallPageDataTable(pnPage);
            },
             error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

//function: Call SettingConperiod Data List
//Parameters: Ajax Success Event 
//Creator:	07/10/2020 Witsarut (Bell)
//Return: View
//Return Type: View
function JSvBACCallPageDataTable(pnPage){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        JCNxOpenLoading();
        var aSearchAll = JSoBACGetAdvanceSearchData();
  
        var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;

        $.ajax({
            type: "POST",
            url : "BACDataTable",
            data: {
                aSearchAll: aSearchAll,
                nPageCurrent: nPageCurrent,
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#ostBACDataTableDocument').html(tResult);
                }
                JSxLimNavDefult();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

