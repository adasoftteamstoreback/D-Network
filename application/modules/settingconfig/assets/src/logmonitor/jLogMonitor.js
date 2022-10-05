var nBrowseType = $('#oetLOGStaBrowse').val();
var tCallBackOption = $('#oetLOGCallBackOption').val();

$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    // $('#obtLOGSearchSync').addClass('disabled');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxLimNavDefult();
    if (nBrowseType != '1') {
        JSvCallPageLOGList();
    }
    $('.xCNHideBtnStaAlw').hide();


});


//function : Function Clear Defult Button Card
//Parameters : Document Ready
//Creator : 07/10/2020 Witsarut (Bell)
//Return : Show Tab Menu
//Return Type : -
function JSxLimNavDefult() {
    if (nBrowseType != 1 || nBrowseType == undefined) {
        $('#odvBtnAddEdit').hide();
        $('#odvBtnSelectLOG').hide();
    } else {
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
function JSvCallPageLOGList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'unundefineddefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "monLogList",
            cache: false,
            data: {
                nPageCurrent: pnPage,
            },
            timeout: 0,
            success: function(tResult) {
                $('#odvLOGPageDocument').html(tResult);
                JSvLOGCallPageDataTable(pnPage);
                JSvLOGCallPageDataTableWebView(pnPage)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function: Call SettingConperiod Data List
//Parameters: Ajax Success Event 
//Creator:	07/10/2020 Witsarut (Bell)
//Return: View
//Return Type: View
function JSvLOGCallPageDataTable(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        var aSearchAll = JSoLOGGetAdvanceSearchData();
        // var aSearchAll = '';


        var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;

        $.ajax({
            type: "POST",
            url: "monLogDataTable",
            data: {
                aSearchAll: aSearchAll,
                nPageCurrent: nPageCurrent,
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#odvInforLogWaitSync').html(tResult);
                }
                JSxLimNavDefult();
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

function JSvLOGCallPageDataTableWebView(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        var aSearchAll = JSoLOGGetAdvanceSearchData();
        // var aSearchAll = $('#ofmLOGFromSerchAdv').val();
        var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;

        $.ajax({
            type: "POST",
            // url: "http://localhost:8000/AdaLog/",
            // url: "https://dev.ada-soft.com/AdaLog/",
            url: tBaseURLLog,
            data: {
                aSearchAll: aSearchAll,
                nPageCurrent: nPageCurrent,
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#odvInforDataLog').html(tResult);
                }
                JSxLimNavDefult();
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

function JSxShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
        $('#obtLOGSearchSync').addClass('disabled');
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 0) {
            $('#obtLOGSearchSync').removeClass('disabled');
        } else {
            $('#obtLOGSearchSync').addClass('disabled');
        }
    }
}


function JSxTextinModal() {

    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {

    } else {

        var tText = '';
        var tTextCode = '';
        for ($i = 0; $i < aArrayConvert[0].length; $i++) {
            tText += aArrayConvert[0][$i].tName + '(' + aArrayConvert[0][$i].nCode + ') ';
            tText += ' , ';

            tTextCode += aArrayConvert[0][$i].nCode;
            tTextCode += ' , ';
        }
        // var tTexts = tText.substring(0, tText.length - 2);
        // var tConfirm = $('#ohdDeleteChooseconfirm').val();
        // $('#ospConfirmDelete').text(tConfirm);
        // $('#ohdConfirmIDDelete').val(tTextCode);
        // var aTexts = tTextCode.substring(0, aData.length - 2);
        // var aDataSplit = aTexts.split(" , ");

        // alert(aDataSplit)

    }
}

function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return 'Dupilcate';
        }
    }
    return 'None';
}

function JSvLOGExpExcel(pnPage) {
    window.open('monLogExportExcel', '_blank');
}