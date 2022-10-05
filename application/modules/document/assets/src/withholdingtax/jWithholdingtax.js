$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSvCallPageWhTaxList();
    $('#oliWhTaxDetail').hide();
    $('#obtWhTaxCancelDoc').hide();
    $('#obtWhTaxPrintDoc').hide();
});

function JSvCallPageWhTaxList(pnPage) {
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        localStorage.tStaPageNow = 'JSvCallPageWhTaxList';
        $('#oetSearchAll').val('');
        $.ajax({
            type: "POST",
            url: "docWhTaxSearchList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvContentPageWhTax').html(tResult);
                $('#oliWhTaxDetail').hide();
                $('#obtWhTaxCancelDoc').hide();
                $('#obtWhTaxPrintDoc').hide();
                JSvWHTaxDataTable(pnPage);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSvWHTaxDataTable(pnPage){
    JCNxOpenLoading();
    var oAdvanceSearch  = JSoWhTaxGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if(typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docWhTaxDataTable",
        data: {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent,
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostWhTaxDataTable').html(aReturnData['tWhTaxViewDataTableList']);
            } else {
                var tMessageError = aReturnData['tStaMessg'];
                FSvCMNSetMsgErrorDialog(tMessageError);
            }
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSvWhTaxCallPageViewDoc(ptWHTaxBchNo, ptWHTaxDocNo){
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvWhTaxCallPageViewDoc', ptWHTaxDocNo);
    $.ajax({
        type: "POST",
        url: "docWhTaxViewDataPage",
        data: {'ptWHTaxBchNo' : ptWHTaxBchNo,
               'ptWHTaxDocNo' : ptWHTaxDocNo
              },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (tResult != '') {
                $('#odvContentPageWhTax').html(tResult);
                $('#oliWhTaxDetail').show();
                $('#obtWhTaxCancelDoc').show();
                $('#obtWhTaxPrintDoc').show();
            }
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSoWhTaxGetAdvanceSearchData(){
    var oAdvanceSearchData  = {
        tSearchAll          : $("#oetWhTaxSearchAllDocument").val(),
        tSearchBchCodeFrom  : $("#oetWhTaxAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo    : $("#oetWhTaxAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom  : $("#oetWhTaxAdvSearcDocDateFrom").val(),
        tSearchDocDateTo    : $("#oetWhTaxAdvSearcDocDateTo").val(),
        tSearchStaDoc       : $("#ocmWhTaxAdvSearchStaDoc").val(),
    };
    return oAdvanceSearchData;
} 

function JSvTAXClickPageList(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld    = $('.xWTAXPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld    = $('.xWTAXPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvWHTaxDataTable(nPageCurrent);
}