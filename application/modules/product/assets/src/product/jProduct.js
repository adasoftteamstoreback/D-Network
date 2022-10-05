var nStaPdtBrowseType = $('#ohdPdtStaBrowseType').val();
var tCallPdtBackOption = $('#ohdPdtCallBackOption').val();

$('ducument').ready(function() {

    localStorage.removeItem('LocalItemData');
    // Check เปิดปิด Menu ตาม Pin
    JSxCheckPinMenuClose();
    JSxPdtNavDefult();
    if (nStaPdtBrowseType != '1') {
        JSvCallPageProductList();
    } else {
        // JSvCallPageProductAdd();
    }
});


// Function : Function Clear Defult Button Product
// Parameters : Document Redy And Function Event
// Creator : 31/01/2019 wasin(Yoshi)
// Return : Reset Defult Button
// Return Type : None
function JSxPdtNavDefult() {
    if (typeof(nStaPdtBrowseType) !== 'undefined' || nStaPdtBrowseType != 1) {
        $('#oliPdtTitleAdd').hide();
        $('#oliPdtTitleEdit').hide();
        $('#odvBtnPdtAddEdit').hide();
        $('#odvBtnPdtInfo').show();
    } else {
        $('#odvModalBody #odvPdtMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliPdtNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvPdtBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNPdtBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNPdtBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

// Function : Call Page Product list
// Parameters : Document Redy And Event Button
// Creator :	31/01/2019 wasin(Yoshi)
// Return : View
// Return Type : View
function JSvCallPageProductList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        localStorage.tStaPageNow = 'JSvCallPageProductList';
        $('#oetSearchProduct').val('');
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "productMain",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvContentPageProduct').html(tResult);
                JSvCallPageProductDataTable(pnPage);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Call Product Type Data List
// Parameters:  Event Button And Ajax Function Success
// Creator:	31/01/2019 wasin(Yoshi)
// Return: View
// Return Type: View
function JSvCallPageProductDataTable(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchAll = $('#oetSearchProduct').val();
        var nSearchProductType = $('#ocmSearchProductType').val();
        var tPdtForSys = $('#ohdPdtforSystemDataTable').val();
        var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;

        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "productDataTable",
            data: {
                tSearchAll: tSearchAll,
                tPdtForSys: tPdtForSys,
                nSearchProductType: nSearchProductType,
                nPageCurrent: nPageCurrent,
                nPagePDTAll: $('#ohdProductAllRow').val()
            },
            cache: false,
            Timeout: 0,
            async: true,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSxPdtNavDefult();
                    $('#ostDataProduct').html(aReturnData['vPdtPageDataTable']);
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
        })
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxSelectPdtForSystem(tValue) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $('#ohdPdtforSystemDataTable').val(tValue);
        JSvCallPageProductDataTable();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Call Product Page Add
// Parameters:  Event Click Add Button
// Creator:	01/02/2019 wasin(Yoshi)
// Return: View
// Return Type: View
function JSvCallPageProductAdd() {
    JCNxOpenLoading();
    setTimeout(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            // JCNxOpenLoading();
            // JStCMMGetPanalLangSystemHTML('', '');
            $.ajax({
                type: "POST",
                url: "productPageAdd",
                cache: false,
                Timeout: 0,
                async: false,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        if (nStaPdtBrowseType == 1) {
                            $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                            $('#odvModalBodyBrowse').html(aReturnData['vPdtPageAdd']);
                        } else {
                            $('#oliPdtTitleEdit').hide();
                            $('#oliPdtTitleAdd').show();
                            // $('#odvPdtContentSet').hide();
                            $('#odvBtnPdtInfo').hide();
                            $('#odvBtnPdtAddEdit').show();
                            $('.xWHideSave').show();
                            $('#odvContentPageProduct').html(aReturnData['vPdtPageAdd']);
                            JsxCallNormalDataTable();
                            // JsxCallPackSizeDataTable();
                            JsxCallNormalDataTable();
                            JSxPdtFashionCallPageForm();
                        }
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
        } else {
            JCNxShowMsgSessionExpired();
        }
    }, 100);
}

// Function: Call Product Page Edit
// Parameters:  Event Click Add Button
// Creator:	01/02/2019 wasin(Yoshi)
// Return: View
// Return Type: View
function JSvCallPageProductEdit(ptPdtCode, tStatus) {
    JCNxOpenLoading();
    setTimeout(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            // JStCMMGetPanalLangSystemHTML('JSvCallPageProductEdit', ptPdtCode);
            $.ajax({
                type: "POST",
                url: "productPageEdit",
                data: { tPdtCode: ptPdtCode },
                cache: false,
                timeout: 0,
                async: false,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        $('#oliPdtTitleAdd').hide();
                        $('#odvBtnPdtInfo').hide();
    
                        $('#oliPdtTitleEdit').show();
                        $('#odvBtnPdtAddEdit').show();
                        $('.xWHideSave').show();
                        $('#odvContentPageProduct').html(aReturnData['vPdtPageAdd']);
                         JsxCallNormalDataTable(ptPdtCode);
                         $(".xWCheckNormalPdt").each(function (indexInArray, valueOfElement) { 
                             if(indexInArray == '0'){
                                $(this).addClass("actived");
                                let tBarPuncode = $(this).data('puncode');
                                $("#oliPdtPdtContentNormalBarCode a").attr('data-toggle', 'tab');
                                 JsxCallNormalBarCodeDataTable(ptPdtCode,tBarPuncode)
                             }
                         });
                         $(".xWCheckNormalBarcodePdt").each(function (indexInArray, valueOfElement) { 
                            if(indexInArray == '0'){
                               $(this).addClass("actived");
                                let tSplPuncode = $(this).data('puncode');
                                let tSplBarCode = $(this).data('barcode');
                               $("#oliPdtContentNormalVendor a").attr('data-toggle', 'tab');
                                JsxCallNormalVendorDataTable(ptPdtCode,tSplPuncode,tSplBarCode);
                            }
                        });
                         JSxPDTGetPrictPdtListTable(ptPdtCode)
                         JSxPdtFashionCallPageForm();
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                    }
                    JCNxLayoutControll();
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'เรียกดูสินค้า';
                        var tErrorStatus  = jqXHR.status
                        var tLogDocNo   = ptPdtCode;
                        var tHtmlError = $(jqXHR.responseText);
                        var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }else{
                        //JCNxSendMQPageNotFound(jqXHR,ptPdtCode);
                    }
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }, 100);
}


    //Functionality: Event Change Product System
    //Parameters: Event Change Product System
    //Creator: 28/04/2021
    //Return: -
    //ReturnType: -
    function JSxPdtTypeControlTap(){

        var nPdtForSystem = $('#ocmPdtForSystem').val();
        if(nPdtForSystem=='5'){
            setTimeout(() => {
                $('#oliPdtContentProductUnit').addClass('xCNHide');
                $('a[data-target="#odvPdtContentMore"]').click();
                // alert(111);
            }, 1000);

        }else{
          $('#oliPdtContentProductUnit').removeClass('xCNHide');
          $('a[data-target="#odvPdtContentProductUnit"]').click();
        }
  }

function JsxCallPackSizeDataTable(ptPdtCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        if (ptPdtCode == "" || ptPdtCode === undefined) { ptPdtCode = ''; }

        $.ajax({
            type: "POST",
            url: "productGetPackSizeUnit",
            data: { FTPdtCode: ptPdtCode }, //tPdtCode : ptPdtCode
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvPdtSetPackSizeTable').html(tResult);

                let oParameterSend = {
                    "FunctionName"                  : "JsxUpdatePackSizeInline",
                    "DataAttribute"                 : ["dataPDT", "dataPUN"],
                    "TableID"                       : "otbTablePackSize",
                    "NotFoundDataRowClass"          : "xWTextNotfoundDataTablePdt",
                    "EditInLineButtonDeleteClass"   : "xWDeleteBtnEditButton",
                    "LabelShowDataClass"            : "xWShowInLine",
                    "DivHiddenDataEditClass"        : "xWEditInLine"
                };
                JCNxSetNewEditInline(oParameterSend);

                // $(".xWEditInlineElement").eq(nIndexInputEditInline).focus(function() {
                //     this.select();
                // });

                // setTimeout(function() {
                //     $(".xWEditInlineElement").eq(nIndexInputEditInline).focus();
                // }, 300);

                $(".xWEditInlineElement").removeAttr("disabled");
                let oElement = $(".xWEditInlineElement");
                for (let nI = 0; nI < oElement.length; nI++) {
                    $(oElement.eq(nI)).val($(oElement.eq(nI)).val().trim());
                }

                if ($("#oetStatus").val() == 'view') {
                    $(".xWMngPackSizeUnit").addClass("xCNDocDisabled");
                    $(".xWMngPackSizeUnit").prop("onclick", null).off("click");
                    $("#otbTablePackSize  input[type='text']").prop("disabled", true);
                    $(".xWAddBarPszUnit").addClass("xCNDocDisabled");
                    $(".xWAddBarPszUnit").prop("onclick", null).off("click")
                    $(".xWPdtDelUnitPackSize").addClass("xCNDocDisabled");
                    $(".xWPdtDelUnitPackSize").prop("onclick", null).off("click")
                    $("#obtAddProductUnit").hide();

                }
                JCNxLayoutControll();
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

// Functionality : Function Check Data Search And Add In Tabel
// Parameters : Event Click Buttom
// Creator : 17/11/2021 Off
// LastUpdate: -
// Return : 
// Return Type :
function JSxPDTPCPClickPageList(ptPage) {
    var nPageCurrent = '';
    var tPdtCode = $("#oetPdtCode").val();
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSxPDTGetPrictPdtListTable(tPdtCode,nPageCurrent);
}


// //Functionality : เรียกข้อมูลตรวจสอบราคาสินค้าลงตาราง
// //Parameters : -
// //Creator : 03/09/2020 Sooksanti(Non)
// //Last Update:
// //Return : 
// //Return Type :
function JSxPDTGetPrictPdtListTable(ptPdtCode,pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            // JCNxOpenLoading();
            var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
            if (ptPdtCode == "" || ptPdtCode === undefined) { ptPdtCode = ''; }
            var tDisplayType = '1';
            localStorage.removeItem('LocalItemData');
            var oAdvanceSearch = JSoPDTPCPAdvanceSearchDataDup();


            $.ajax({
                type: "POST",
                url: "dasPCPPageDataTable",
                data: {
                    nPagePDTAll             : $('#ohdPCPProductAllRow').val(),
                    FTPdtCode               : ptPdtCode,
                    tDisplayType            : tDisplayType,
                    nPageCurrent            : nPageCurrent,
                    oAdvanceSearch          : oAdvanceSearch
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvPdtNormalTable4").html(tResult);
                    // JSxPCPAutoRowSpan($("table"),tDisplayType);
                    // JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } catch (err) {
            console.log('JSxPCPGetListPageTable Error: ', err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// //Functionality : เก็บค่าจาก input AdvanceSearch
// //Parameters : -
// //Creator : 03/09/2020 Sooksanti(Non)
// //Last Update:
// //Return : ค่า input AdvanceSearch
// //Return Type : obj
function JSoPDTPCPAdvanceSearchData() {
    
    var nStaSession = JCNxFuncChkSessionExpired();
    var tSearchDocDateFrom = $('#oetPDTPCPSearchDocDateFrom').val();
    var tSearchDocDateTo = $('#oetPDTPCPSearchDocDateTo').val();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            let oAdvanceSearchData = {
                tSearchDocDateFrom  : tSearchDocDateFrom,
                tSearchDocDateTo    : tSearchDocDateTo,
                tPplCodeFrom        : $('#oetPCPPplCodeFrom').val(),
                tPplCodeTo          : $('#oetPCPPplCodeTo').val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("JSoPDTPCPAdvanceSearchData Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// //Functionality : เก็บค่าจาก input AdvanceSearch
// //Parameters : -
// //Creator : 03/09/2020 Sooksanti(Non)
// //Last Update:
// //Return : ค่า input AdvanceSearch
// //Return Type : obj
function JSoPDTPCPAdvanceSearchDataDup() {
    
    var nStaSession = JCNxFuncChkSessionExpired();
    var tSearchAll = '';
    var tPdtCodeFrom = $('#oetPdtCode').val();
    var tPdtCodeTo = $('#oetPdtCode').val();
    var tSearchDocDateFrom = $('#oetPDTPCPSearchDocDateFrom').val();
    var tSearchDocDateTo = $('#oetPDTPCPSearchDocDateTo').val();
    var tPunCodeFrom = '';
    var tPunCodeTo = '';
    var tSearchDateStart = '';
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            let oAdvanceSearchData = {
                tSearchAll: tSearchAll,
                tPdtCodeFrom: tPdtCodeFrom,
                tPdtCodeTo: tPdtCodeTo,
                tSearchDocDateFrom: tSearchDocDateFrom,
                tSearchDocDateTo: tSearchDocDateTo,
                tPunCodeFrom: tPunCodeFrom,
                tPunCodeTo: tPunCodeTo,
                tSearchDateStart: tSearchDateStart,
                tPplCodeFrom: $('#oetPCPPplCodeFrom').val(),
                tPplCodeTo: $('#oetPCPPplCodeTo').val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("JSoPCPAdvanceSearchData Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}



function JsxCallNormalDataTable(ptPdtCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        if (ptPdtCode == "" || ptPdtCode === undefined) { ptPdtCode = ''; }

        $("#oliPdtPdtContentNormalBarCode a").attr('data-toggle', 'false');
        $("#otbTableBarCode").empty();
        $("#oliPdtContentNormalVendor a").attr('data-toggle', 'false');
        $("#otbTableSuppliere").empty();

        $.ajax({
            type: "POST",
            url: "productGetNormalUnit",
            data: { FTPdtCode: ptPdtCode }, //tPdtCode : ptPdtCode
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvPdtNormalTable').html(tResult);
                let oParameterSend = {
                    "FunctionName"                  : "JsxUpdatePackSizeInline",
                    "DataAttribute"                 : ["dataPDT", "dataPUN"],
                    "TableID"                       : "otbTablePackSize",
                    "NotFoundDataRowClass"          : "xWTextNotfoundDataTablePdt",
                    "EditInLineButtonDeleteClass"   : "xWDeleteBtnEditButton",
                    "LabelShowDataClass"            : "xWShowInLine",
                    "DivHiddenDataEditClass"        : "xWEditInLine"
                };
                // JCNxSetNewEditInline(oParameterSend);

                $(".xWEditInlineElement").removeAttr("disabled");
                let oElement = $(".xWEditInlineElement");
                for (let nI = 0; nI < oElement.length; nI++) {
                    $(oElement.eq(nI)).val($(oElement.eq(nI)).val().trim());
                }

                if ($("#oetStatus").val() == 'view') {
                    $(".xWMngPackSizeUnit").addClass("xCNDocDisabled");
                    $(".xWMngPackSizeUnit").prop("onclick", null).off("click");
                    $("#otbTablePackSize  input[type='text']").prop("disabled", true);
                    $(".xWAddBarPszUnit").addClass("xCNDocDisabled");
                    $(".xWAddBarPszUnit").prop("onclick", null).off("click")
                    $(".xWPdtDelUnitPackSize").addClass("xCNDocDisabled");
                    $(".xWPdtDelUnitPackSize").prop("onclick", null).off("click")
                    $("#obtAddProductUnit").hide();

                }
                JCNxLayoutControll();
                // JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}


function JsxCallNormalBarCodeDataTable(ptPdtCode,tBarPuncode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        if (ptPdtCode == "" || ptPdtCode === undefined) { ptPdtCode = ''; }

        $("#oliPdtContentNormalVendor a").attr('data-toggle', 'false');
        $("#otbTableSuppliere").empty();

        $.ajax({
            type: "POST",
            url: "productGetNormalBarCodeUnit",
            data: { tPuncode    : tBarPuncode,
                tPunName    : tBarPuncode,
                tPdtCode    : ptPdtCode }, //tPdtCode : ptPdtCode
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvPdtNormalTable2').html(tResult);
                JCNxLayoutControll();
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


function JsxCallNormalVendorDataTable(ptPdtCode,tPuncode,ptBarCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        if (ptPdtCode == "" || ptPdtCode === undefined) { ptPdtCode = ''; }

        $.ajax({
            type: "POST",
            url: "productGetNormalVendor",
            data: { tPuncode    : tPuncode,
                tPunName    : tPuncode,
                tPdtCode    : ptPdtCode,
                tBarCode    : ptBarCode }, //tPdtCode : ptPdtCode
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvPdtNormalTable3').html(tResult);
                JCNxLayoutControll();
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



function JsxUpdatePackSizeInline(oElm) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tPdtCode = oElm.DataAttribute[0]['dataPDT'];
        var tPunCode = oElm.DataAttribute[1]['dataPUN'];
        var tValue   = oElm.VeluesInline;
        if (tValue == '' || tValue == null | tValue == 'undefined') {
            tValue = 1;
        } else {
            tValue = tValue;
        }

        if (tPdtCode == 'NULL') { tPdtCode = ' '; } else { tPdtCode = tPdtCode; }
        $.ajax({
            type    : "POST",
            url     : "productUpdatePackSizeUnit",
            data    : {
                FTPdtCode       : tPdtCode,
                tUnitOld        : tPunCode,
                FTPunCode       : tPunCode,
                FCPdtUnitFact   : tValue,
                pnUpdateType    : 2
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(oResult) {
                JsxCallPackSizeDataTable(tPdtCode);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Function Generate Product Code
// Parameters:  Event Click Gen Code Button
// Creator:	07/02/2019 wasin(Yoshi)
// Return: View
// Return Type: View
function JSoGenerateProductCode() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#oetPdtCode').closest('.form-group').addClass("has-success").removeClass("has-error");
        $('#oetPdtCode').closest('.form-group').find(".help-block").fadeOut('slow').remove();
        var tTableName = 'TCNMPdt';
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "generateCode",
            data: { tTableName: tTableName },
            cache: false,
            timeout: 0,
            async: false,
            success: function(oResult) {
                var aDataReturn = $.parseJSON(oResult);
                if (aDataReturn.rtCode == '1') {
                    $('#oetPdtCode').val(aDataReturn['rtPdtCode']);
                    $('#oetPdtCode').addClass('xCNDisable');
                    $('#oetPdtCode').attr('readonly', true);
                    $('.xCNBtnGenCode').attr('disabled', true);
                    $('#oetPdtName').focus();
                    JCNxCloseLoading();
                } else {
                    var tMessageError = aDataReturn['rtDesc'];
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

// Function: Function Add/Edit Product
// Parameters:  Object In Next Funct Modal Browse
// Creator:	14/02/2019 wasin(Yoshi)
// Return: object View Event Not Sale Data Table
// Return Type: object
function JSoAddEditProduct(ptRoute) {

    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        var tChkDepat1  = $("#oetFhnPdtDepartName").val();
        var tChkDepat2  = $("#oetFhnPdtClassName").val();
        var tPdtGroup   = $("#oetPdtPgpChainName").val();
        var tPdtName    = $("#oetPdtName").val();
        var tChkPdtLent = $(".xWPdtPackSizeRow").length;

        if (tChkPdtLent < 1) {

                var tPdtCode        = $('#oetPdtCode').val();
                var tUnitPuncode    = '00001';
                // var tUnitPuncode    = $('#oetPDTCostSupCode').val();
                var tUnitUnitFact   = 1;
                // var tUnitUnitFact   = $('#oetPDTUnitPerCost').val();
                var tUnitGrade      = $('#oetPDTGrade').val();
                var tUnitWeight     = $('#oetPDTWeigh').val();
                var tUnitColorCode  = $('#oetPDTColorCode').val();
                var tUnitSizeCode   = $('#oetPDTSizeCode').val();
                var tUnitType       = '1';
                var FTPunCodeOld    = $('#oetPDTCostSupCodeOld').val();

                var tPdtUnitDimWidth     = $('#oetPdtUnitDimWidth').val();
                var tPdtUnitDimLength    = $('#oetPdtUnitDimLength').val();
                var tPdtUnitDimHeight    = $('#oetPdtUnitDimHeight').val();

                var tPdtPkgDimWidth      = $('#oetPdtPkgDimWidth').val();
                var tPdtPkgDimLength     = $('#oetPdtPkgDimLength').val();
                var tPdtPkgDimHeight     = $('#oetPdtPkgDimHeight').val();
                

                var tPdtPkgDim           = tPdtPkgDimWidth+";"+tPdtPkgDimLength+";"+tPdtPkgDimHeight;
                var tPdtUnitDim          = tPdtUnitDimWidth+";"+tPdtUnitDimLength+";"+tPdtUnitDimHeight;
                
                var tSaveUnitStaStaAlwPick  = $('#ocbPdtAllowManage').is(':checked') ? '1' : '2';
                var tSaveUnitStaStaAlwPoHQ  = $('#ocbPdtAllowOrderBch').is(':checked') ? '1' : '2';
                var tSaveUnitStaStaAlwBuy   = $('#ocbPdtAllowBuy').is(':checked') ? '1' : '2';
                var tSaveUnitStaStaAlwSale  = $('#ocbPdtAllowSale').is(':checked') ? '1' : '2';
                var tSaveUnitStaStaPoSPl    = $('#ocbPdtAllowOrderVendor').is(':checked') ? '1' : '2';
                $.ajax({
                    type: "POST",
                    url: "productAddPackSizeUnitTmp",
                    data: {
                        FTPdtCode           : tPdtCode,
                        FTPunCode           : tUnitPuncode,
                        FTPunCodeOld        : FTPunCodeOld,
                        FCPdtUnitFact       : tUnitUnitFact,
                        FTPdtGrade          : tUnitGrade,
                        FCPdtWeight         : tUnitWeight,
                        FTClrCode           : tUnitColorCode,
                        FTPszCode           : tUnitSizeCode,
                        FTPdtStaAlwPick     : tSaveUnitStaStaAlwPick,
                        FTPdtStaAlwPoHQ     : tSaveUnitStaStaAlwPoHQ,
                        FTPdtStaAlwBuy      : tSaveUnitStaStaAlwBuy,
                        FTPdtStaAlwSale     : tSaveUnitStaStaAlwSale,
                        FTPdtStaAlwPoSPL    : tSaveUnitStaStaPoSPl,
                        tUnitType           : tUnitType,
                        FTPdtPkgDim         : tPdtPkgDim,
                        FTPdtUnitDim        : tPdtUnitDim
                    },
                    cache: false,
                    timeout: 0,
                    async: false,
                    success: function(oResult) {
                        JsxCallNormalDataTable(tPdtCode);
                        // alert('success');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });

                var tPdtCode        = $('#oetPdtCode').val();
                var tBarCode        = 'TMP';
                var FTBarCodeOld    = $('#oetPDTUnitBarBarCodeOld').val();
                var tBarPuncode     = '00001';
                var tBarPlcCode     = $('#oetPDTUnitBarLocCode').val();
                var tUnitType       = '1';
                
                // Status Manage 
                var tSaveBarAlwSale         = $('#ocbPdtBarAlwSale').is(':checked') ? '1' : '2';
                var tSaveBarAlwStaUse       = $('#ocbPdtBarAlwUsed').is(':checked') ? '1' : '2';

                $.ajax({
                    type: "POST",
                    url: "productAddPackSizeBarCodeTmp",
                    data: {
                        FTPdtCode       : tPdtCode,
                        FTBarCode       : tBarCode,
                        FTBarCodeOld    : FTBarCodeOld,
                        FTPunCode       : tBarPuncode,
                        FTBarStaAlwSale : tSaveBarAlwSale,
                        FTBarStaUse     : tSaveBarAlwStaUse,
                        FTPlcCode       : tBarPlcCode,
                        tUnitType       : tUnitType
                    },
                    cache: false,
                    timeout: 0,
                    async: false,
                    success: function(oResult) {
                        var aReturn = JSON.parse(oResult);
                        if (aReturn['nStaQuery'] == 1) {
                        JsxCallNormalBarCodeDataTable(tPdtCode,tBarPuncode);
                        } else {
                            JCNxCloseLoading();
                            alert(aReturn['tStaMessg']);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            

            // FSvCMNSetMsgWarningDialog('กรุณาเลือก หน่วยสินค้า');
            // $("#oliPdtContentProductUnit").find("a").click();
            // window.scrollBy(0,600);
            // JCNxCloseLoading();
            // return ;
        }

        if (tPdtName == "") {
            FSvCMNSetMsgWarningDialog('กรุณากรอกชื่อสินค้า');
            $("#oliPdtContentProductUnit").find("a").click();
            JCNxCloseLoading();
            return ;
        }

        // if (tChkDepat1 == "" || tChkDepat2 == "") {
        //     FSvCMNSetMsgWarningDialog('กรุณาเลือก หมวดหมู่');
        //     $("#oliPdtDataAddFashion").find("a").click();
        //     JCNxCloseLoading();
        //     return ;
        // }

        $('#ofmAddEditProduct').validate().destroy();

        $('#ofmAddEditProduct').validate({
            rules: {
                oetPdtCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "productEventAdd") {
                                if ($('#ocbProductAutoGenCode').is(':checked')) {
                                    return false;
                                } else {
                                    return true;
                                }
                            } else {
                                return true;
                            }
                        }
                    },
                    // "dublicateCode": {}
                },
                oetPdtName: { "required": {} },
                oetModalShpName: {
                    "required": {
                        depends: function(oElement) {
                            if ($("#ocmRetPdtType").val() == "2" && $('#ocmPdtForSystem').val() == "4") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            },
            messages: {
                oetPdtCode: {
                    "required": $('#oetPdtCode').attr('data-validate-required'),
                },
                oetPdtName: {
                    "required": $('#oetPdtName').attr('data-validate-required'),
                },
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
                var aPdtImg = [];
                $('.xCNImgTumblr').each(function() {
                    if($(this).attr('hiddensrc') == '' || $(this).attr('hiddensrc') == null){
                        aPdtImg.push($(this).attr('src'));
                    }else{
                        aPdtImg.push($(this).attr('hiddensrc'));
                    }
                });

                var nHaveVat = 0;
                if ($('#ocbPdtStaHaveVat').is(':checked')) {
                    nHaveVat = 1;
                } else {
                    nHaveVat = 2;
                }
                // Product Information 1
                var aPdtDataInfo1 = {
                    'tPdtType': $('#ocmPdtType').val(),
                    'tPdtSaleType': $('#ocmPdtSaleType').val(),
                    'tPdtColor': $('#oetImgColorProduct').val(),
                    'tImgObj': $('#ohdImgObjOld').val(),
                    'tChecked': ($('input[name=orbChecked]:checked').val()) ? $('input[name=orbChecked]:checked').val() : 0,
                    'tIsAutoGenCode': ($('#ocbProductAutoGenCode').is(':checked')) ? 1 : 2,
                    'tPdtCode': $('#oetPdtCode').val(),
                    'tPdtStkCode': $('#oetPdtCode').val(),
                    'tPdtName': $('#oetPdtName').val(),
                    'tPdtNameOth': $('#oetPdtNameOth').val(),
                    'tPdtNameABB': $('#oetPdtNameABB').val(),
                    'tPdtVatCode': $('#ocmPdtVatCode').val(),
                    'nPdtStaVatBuy': nHaveVat,
                    'nPdtStaVat': nHaveVat,
                    'nPdtStaPoint': ($('#ocbPdtPoint').is(':checked')) ? 1 : 2,
                    'nPdtStaActive': ($('#ocbPdtStaActive').is(':checked')) ? 1 : 2,
                    'nPdtStkControl': ($('#ocbPdtStkControl').is(':checked')) ? 1 : 2,
                    'nPdtStaAlwReturn': ($('#ocbPdtStaAlwReturn').is(':checked')) ? 1 : 2,
                    'nPdtStaAlwDis': ($('#ocbPdtStaAlwDis').is(':checked')) ? 1 : 2,
                    'nPdtStaLot': ($('#ocbPdtStaLot').is(':checked')) ? 1 : 2,
                    'nPdtStaAlwWHTax': ($('#ocbPdtStaAlwWHTax').is(':checked')) ? 1 : 2,
                    'nPdtStaAlwBook': ($('#ocbPdtStaAlwBook').is(':checked')) ? 1 : 2,
                };

                // Product Information 2
                var aPdtDataInfo2 = {
                    'tPdtAgnCode'   : $('#oetPdtAgnCode').val(), //Nattakit(Nale) 22/05/2020
                    'tPdtBchCode'   : $('#oetPdtBchCode').val(),
                    'tPdtMerCode'   : $('#oetPdtMerCode').val(),
                    'tPdtMgpCode'   : $('#oetPdtInfoMgpCode').val(), //Napat(Jame) 30/08/2019
                    'tPdtShpCode'   : $('#oetPdtInfoShpCode').val(),
                    'tPdtPgpChain'  : $('#oetPdtPgpChain').val(),
                    'tPdtPtyCode'   : $('#oetPdtPtyCode').val(),
                    'tPdtPbnCode'   : $('#oetPdtPbnCode').val(),
                    'tPdtPmoCode'   : $('#oetPdtPmoCode').val(),
                    'tPdtTcgCode'   : $('#oetPdtTcgCode').val(),
                    'tPdtSaleStart' : $('#oetPdtSaleStart').val(),
                    'tPdtSaleStop'  : $('#oetPdtSaleStop').val(),
                    'tPdtPointTime' : $('#oetPdtPointTime').val(),
                    'tPdtQtyOrdBuy' : $('#oetPdtQtyOrdBuy').val(),
                    'tPdtMax'       : $('#oetPdtMax').val(),
                    'tPdtMin'       : $('#oetPdtMin').val(),
                    'tPdtCostDef'   : $('#oetPdtCostDef').val(),
                    'tPdtCostOth'   : $('#oetPdtCostOth').val(),
                    'tPdtCostStd'   : $('#oetPdtCostStd').val(),
                    'tPdtRmk'       : $('#otaPdtRmk').val(),
                    'tPdtForSystem' : $('#ocmPdtForSystem').val(),
                    'tPdtConditionControlCode': $('#oetConditionControlCode').val()
                };

                var aPdtDataRental = {
                    'tRetPdtType': $('#ocmRetPdtType').val(),
                    'tRetPdtSta': $('#ocmRetPdtSta').val(),
                    'tRetPdtStaPay': $('#ocmRetPdtStaPay').val(),
                    'tRetPdtDeposit': $('#oetRetPdtDeposit').val(),
                    'tRetPdtShpCode': $('#oetModalShopCode').val(),
                };

                var aPdtDataService = {
                    'tPdtSVDistance': $('#oetPdtSVDistance').val(),
                    'tPdtSVDuration': $('#oetPdtSVDuration').val(),
                    'tPdtSVEst': $('#oetPdtSVEst').val(),
                    'tPdtSVDuratKilo': $('#oetPdtSVDuratKilo').val(),
                    'tPdtSVTime': $('#oetPdtSVTime').val(),
                    'tPdtSVCondit': $('#oetPdtSVCondit').val(),
                };

                var aPdtDataDepart = {
                    'tDepart1': $('#oetFhnPdtDepartCode').val(),
                    'tDepart2': $('#oetFhnPdtClassCode').val(),
                };

                var aPackData = {
                    'ptRoute': ptRoute,
                    'aPdtImg': aPdtImg,
                    'aPdtDataInfo1': aPdtDataInfo1,
                    'aPdtDataInfo2': aPdtDataInfo2,
                    'aPdtDataRental': aPdtDataRental,
                    'aPdtDataService': aPdtDataService,
                    'aPdtDataDepart': aPdtDataDepart
                };

                if($('#ocmPdtForSystem').val()!='5'){  // ไม่เป็นสินค้าแฟชั่น

                    var tLenght = $('.xWTablePackSize tbody tr.xWPdtPackSizeRow').length;
                    var nUnitCount = $('#ohdUnitCount').val();
                    var nBarCodeIsNotValue = 0;
                    if (tLenght > 0) {
                        $('.xWTablePackSize tbody tr.xWPdtPackSizeRow').each(function(index) {
                            var nCount = parseInt($('#ohdPdtBarCodeRow' + $(this).data('puncode')).val());
                            if (nCount == 0) {
                                nBarCodeIsNotValue++;
                            }
                        });

                        if (nBarCodeIsNotValue > 0) { //มีหน่วยสินค้าแล้ว แต่ไม่มีบาร์โค้ด
                            FSvCMNSetMsgWarningDialog($('#ohdErrMsgNotHasBarCode').val());
                        } else {
                            //มีหน่วยสินค้า มีบาร์โค้ด
                            //ตรวจสอบค่า UnitFact ว่ามีหน่วยเล็กที่สุดคือ 1 หรือไม่ และ มีค่าที่ซ้ำกันหรือไม่
                            var aDataUnitFact = [];
                            var aUnique = [];
                            $('.xWPdtUnitFact').each(function() {
                                aDataUnitFact.push(parseFloat($(this).val()));
                                aUnique.push($(this).val());
                            });
                            if (aDataUnitFact.indexOf(1) != "-1") {
                                jQuery.unique(aUnique);
                                if (aDataUnitFact.length == aUnique.length) {
                                    // JSxAjaxPostDataProduct(aPackData, 1);
                                } else {
                                    //อัตราส่วน/หน่วย มีอยู่ในระบบแล้ว
                                    // FSvCMNSetMsgWarningDialog($('#ohdErrMsgDupUnitFact').val());
                                    // return false;
                                }
                                JSxAjaxPostDataProduct(aPackData, 1);
                            } else {
                                //กรุณาสร้างหน่วยเล็กที่สุด
                                FSvCMNSetMsgWarningDialog($('#ohdErrMsgNotHasUnitSmall').val());
                                return false;
                            }
                        }
                    } else { //ยังไม่มีหน่วยสินค้า
                        if (ptRoute == "productEventAdd") {
                            if (nUnitCount > 0) {
                                JSxAjaxPostDataProduct(aPackData, 2);
                            } else {
                                FSvCMNSetMsgWarningDialog($('#ohdErrMsgNotHasUnit').val());
                            }

                        } else {
                            FSvCMNSetMsgWarningDialog($('#ohdErrMsgNotHasUnit').val());
                        }
                    }

                }else{
                    // สินค้าแฟชั่น
                    JSxAjaxPostDataProduct(aPackData, 1);
                }
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxAjaxPostDataProduct(paPackData, pnTypeAdd) { //paTypeAdd 1 = เพิ่มสินค้าธรรมดา , 2 = เพิ่มสินค้า เพิ่มหน่วย เพิ่มบาร์โค้ด
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: paPackData['ptRoute'],
        data: {
            'aPdtImg': paPackData['aPdtImg'],
            'aPdtDataInfo1': paPackData['aPdtDataInfo1'],
            'aPdtDataInfo2': paPackData['aPdtDataInfo2'],
            'aPdtDataRental': paPackData['aPdtDataRental'],
            'aPdtDataService': paPackData['aPdtDataService'],
            'aPdtDataDepart': paPackData['aPdtDataDepart'],
            'pnTypeAdd': pnTypeAdd,
            'nPdtSetOrSN' : $("#ohdPdtSetOrSN").val()
                // 'aPdtDataPackSize'  : aPdtDataPackSize,
                // 'aPdtDataAllSet'    : aPdtDataAllSet,
                // 'tPdtEvnNotSale'    : tPdtEvnNotSale,
        },
        async: false,
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (nStaPdtBrowseType != 1) {
                if (aReturnData['nStaEvent'] == 1) {
                    switch (aReturnData['nStaCallBack']) {
                        case '1':
                            JSvCallPageProductEdit(aReturnData['tCodeReturn']);
                            break;
                        case '2':
                            JSvCallPageProductAdd();
                            break;
                        case '3':
                            JSvCallPageProductList();
                            break;
                        default:
                            JSvCallPageProductEdit(aReturnData['tCodeReturn']);
                    }
                    JCNxImgWarningMessage(aReturnData['aImgReturn']);
                } else {
                    var tMsgErrReturn = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMsgErrReturn);
                }
            } else {
                JCNxBrowseData(tCallPdtBackOption);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
            var tDocNo = $('#oetPdtCode').val();
            if (jqXHR.status != 404){
                var tLogFunction = 'ERROR';
                var tDisplayEvent = 'บันทึก/แก้ไข สินค้า';
                var tErrorStatus = jqXHR.status;
                var tHtmlError = $(jqXHR.responseText);
                var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                var tLogDocNo   = tDocNo;
                JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
            }else{
                //JCNxSendMQPageNotFound(jqXHR,tDocNo);
            }
        }
    });
}

// Function: Function Check BarCode In PackSize All
// Parameters:
// Creator:	14/02/2019 wasin(Yoshi)
// Return: aData BarCode All In PackSize
// Return Type: Array
function JSaChkBarCodeInPackSizeAll() {
    var aStaReturn = [];
    $('#odvPdtContentInfo1 #odvPdtSetPackSizeTable .xWPdtUnitBarCodeRow').each(function() {
        var nCountBarCodeRow = $(this).find('.xWPdtUnitDataBarCode .xWBarCodeItem').length;
        if (nCountBarCodeRow == 0) {
            var oStaChkCount = {
                'staPunCode': $(this).data('puncode'),
                'staPunName': $(this).data('punname')
            }
            aStaReturn.push(oStaChkCount);
        }
    });
    return aStaReturn;
}

// Functionality : เปลี่ยนหน้า pagenation
// Parameters : Event Click Pagenation
// Creator : 25/02/2019 wasin(Yoshi)
// Return : View
// Return Type : View
function JSvProductClickPage(ptPage, pnEndPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'Fisrt': //กดหน้าแรก
            nPageCurrent = 1;
            break;
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageProduct .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageProduct .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'Last': //กดหน้าสุดท้าย
            nPageCurrent = pnEndPage;
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvCallPageProductDataTable(nPageCurrent);
    $('#ohdPdtCurrentPageDataTable').val(nPageCurrent);
}

// Functionality: Function Chack And Show Button Delete All
// Parameters: LocalStorage Data
// Creator : 25/02/2019 wasin(Yoshi)
// Return: -
// Return Type: -
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

// Functionality: Insert Text In Modal Delete
// Parameters: LocalStorage Data
// Creator : 25/02/2019 wasin(Yoshi)
// Return: -
// Return Type: -
function JSxPaseCodeDelInModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {} else {
        var tTextCode = '';
        for ($i = 0; $i < aArrayConvert[0].length; $i++) {
            tTextCode += aArrayConvert[0][$i].nCode;
            tTextCode += ' , ';
        }
        $('#odvModalDeletePdtMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
        $('#odvModalDeletePdtMultiple #ohdConfirmIDDelMultiple').val(tTextCode);
    }
}

// Functionality: Function Chack Value LocalStorage
// Parameters: Event Select List Reason
// Creator: 25/02/2019 wasin(Yoshi)
// Return: Duplicate/none
// Return Type: string
function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return 'Dupilcate';
        }
    }
    return 'None';
}

// Functionality: Event Single Delete Product Single
// Parameters: Event Icon Delete
// Creator: 26/02/2019 wasin(Yoshi)
// Return: object Status Delete
// ReturnType: object
function JSoProductDeleteSingle(pnPageDel, pnPageCodeDel, pnPageNameDel, pnPdtForSystem) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#odvModalDeletePdtSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val() + pnPageCodeDel + ' (' + pnPageNameDel + ')');
        $('#odvModalDeletePdtSingle').modal('show');
        $('#odvModalDeletePdtSingle #osmConfirmDelSingle').unbind().click(function() {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "productEventDelete",
                data: {
                    'tIDCode': pnPageCodeDel,
                    'tPdtForSystem': pnPdtForSystem
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturn = JSON.parse(oResult);
                    if (aReturn['nStaEvent'] == 1) {
                        let tCurrentPdtAllRow = $('#ohdProductAllRow').val();
                        $('#ohdProductAllRow').val(tCurrentPdtAllRow - 1);

                        $('#odvModalDeletePdtSingle').modal('hide');
                        $('#odvModalDeletePdtSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                        $('.modal-backdrop').remove();
                        setTimeout(function() {
                            // if (aReturn["nNumRow"] != 0) {
                            //     if (aReturn["nNumRow"] > 10) {
                            //         nNumPage = Math.ceil(aReturn["nNumRow"] / 10);
                            //         if (pnPageDel <= nNumPage) {
                                        JSvCallPageProductDataTable(pnPageDel);
                            //         } else {
                            //             JSvCallPageProductDataTable(nNumPage);
                            //         }
                            //     } else {
                            //         JSvCallPageProductDataTable(1);
                            //     }
                            // } else {
                            //     JSvCallPageProductDataTable(1);
                            // }
                        }, 500);
                    } else {
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'ลบใบสินค้า';
                        var tErrorStatus = jqXHR.status;
                        var tHtmlError = $(jqXHR.responseText);
                        var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                        var tLogDocNo   = pnPageCodeDel;
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }else{
                        //JCNxSendMQPageNotFound(jqXHR,pnPageCodeDel);
                    }
                }
            });
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Functionality: Event Single Delete Product Single
// Parameters: Event Icon Delete
// Creator: 26/02/2019 wasin(Yoshi)
// Return: object Status Delete
// ReturnType: object
function JSoProductDeleteMultiple() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var nPdtForSys = $('#oetPdtForSys').val();
        var nPageDel = $('#oetPage').val();
        var aDataDelMultiple = $('#odvModalDeletePdtMultiple #ohdConfirmIDDelMultiple').val();
        var aTextsDelMultiple = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
        var aDataSplit = aTextsDelMultiple.split(" , ");
        var nDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        for ($i = 0; $i < nDataSplitlength; $i++) {
            aNewIdDelete.push(aDataSplit[$i]);
        }
        if (nDataSplitlength > 1) {
            JCNxOpenLoading();
            localStorage.StaDeleteArray = '1';
            $.ajax({
                type: "POST",
                url: "productEventDelete",
                data: {
                    'tIDCode': aNewIdDelete,
                    'tPdtForSystem': nPdtForSys
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturn = JSON.parse(oResult);
                    if (aReturn['nStaEvent'] == 1) {
                        setTimeout(function() {
                            let tCurrentPdtAllRow = $('#ohdProductAllRow').val();
                            $('#ohdProductAllRow').val(tCurrentPdtAllRow - nDataSplitlength);

                            $('#odvModalDeletePdtMultiple').modal('hide');
                            $('#odvModalDeletePdtMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvModalDeletePdtMultiple #ohdConfirmIDDelMultiple').val('');
                            localStorage.removeItem('LocalItemData');
                            // JSvCallPageProductList();
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                                // if (aReturn["nNumRow"] != 0) {
                                //     if (aReturn["nNumRow"] > 5) {
                                //         nNumPage = Math.ceil(aReturn["nNumRow"] / 5);
                                //         if (nPageDel <= nNumPage) {
                                            JSvCallPageProductDataTable(nPageDel);
                                //         } else {
                                //             JSvCallPageProductDataTable(nNumPage);
                                //         }
                                //     } else {
                                //         JSvCallPageProductDataTable(1);
                                //     }
                                // } else {
                                //     JSvCallPageProductDataTable(1);
                                // }
                            }, 500);
                        });
                    } else {
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'ลบใบสินค้า';
                        var tErrorStatus = jqXHR.status;
                        var tHtmlError = $(jqXHR.responseText);
                        var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                        var tLogDocNo   = aNewIdDelete;
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }else{
                        //JCNxSendMQPageNotFound(jqXHR,aNewIdDelete);
                    }
                }
            });
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Functionality: Function Check Is Create Page
// Parameters: Event Documet Redy
// Creator: 26/03/2019 wasin(Yoshi)
// Return: object Status Delete
// ReturnType: boolean
function JSbProductIsCreatePage() {
    try {
        const tPdtCode = $('#oetPdtCode').data('is-created');
        var bStatus = false;
        if (tPdtCode == "") { // No have data
            bStatus = true;
        }
        return bStatus;
    } catch (err) {
        console.log('JSbProductIsCreatePage Error: ', err);
    }
}

// Functionality: Function Check Is Update Page
// Parameters: Event Documet Redy
// Creator: 26/03/2019 wasin(Yoshi)
// Return: object Status Delete
// ReturnType: boolean
function JSbProductIsUpdatePage() {
    try {
        const tPdtCode = $('#oetPdtCode').data('is-created');
        var bStatus = false;
        if (!tPdtCode == "") { // Have data
            bStatus = true;
        }
        return bStatus;
    } catch (err) {
        console.log('JSbProductIsUpdatePage Error: ', err);
    }
}

// Functionality : Show or Hide Component
// Parameters : ptComponent is element on document(id or class or...),pbVisible is visible
// Creator : 26/03/2019 Wasin (Yoshi)
// Return : -
// Return Type : -
function JSxProductVisibleComponent(ptComponent, pbVisible, ptEffect) {
    try {
        if (pbVisible == false) {
            $(ptComponent).addClass('hidden');
        }
        if (pbVisible == true) {
            // $(ptComponent).removeClass('hidden');
            $(ptComponent).removeClass('hidden fadeIn animated').addClass('fadeIn animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                $(this).removeClass('hidden fadeIn animated');
            });
        }
    } catch (err) {
        console.log('JSxProductVisibleComponent Error: ', err);
    }
}

function JSxPdtGetBarCodeDataByID(ptPdtCode, ptPunCode) {
    $.ajax({
        type: "POST",
        url: "productGetDataBarCode",
        data: {
            'ptPdtCode': ptPdtCode,
            'ptPunCode': ptPunCode
        },
        async: false,
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $('.xWModalBarCodeDataTable').html(tResult);
            // $('#oetModalAebBarCode').focus();
            JCNxCloseLoading();
            JSxPdtModalBarCodeClear();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSxPdtModalBarCodeClear() {
    $('#oetEditData').val('0');
    $('#oetModalAebBarCode').val('');
    $('#oetModalAebOldBarCode').val('');
    $('#oetModalAebPlcCode').val('');
    $('#oetModalAebPlcName').val('');
    $('#oetModalAesSplCode').val('');
    $('#oetModalAesSplName').val('');
    $('#ocbModalAebBarStaUse').prop("checked", true);
    $('#ocbModalAebBarStaAlwSale').prop("checked", true);
    $('#ocbModalAesSplStaAlwPO').prop("checked", true);
    $('#oetModalAebBarCode').parents('.form-group').removeClass("has-error");
    $('#oetModalAebBarCode').parents('.form-group').removeClass("has-success");
    $('#oetModalAebBarCode').parents('.form-group').find(".help-block").fadeOut('slow').remove();
}

function JSxPdtModalUnitPackClear() {
    $('#oetEditData').val('0');
    $('#oetPDTCostSupCode').val('');
    $('#oetPDTCostSupName').val('');
    $('#oetPDTUnitPerCost').val('');
    $('#oetPDTColorCode').val('');
    $('#oetPDTColorName').val('');
    $('#oetPDTSizeCode').val('');
    $('#oetPDTSizeName').val('');
    $('#oetPDTWeigh').val('');
    $('#oetPDTGrade').val('');
    $('#ocbPdtAllowOrderBch').prop("checked", true);
    $('#ocbPdtAllowOrderVendor').prop("checked", true);
    $('#ocbPdtAllowSale').prop("checked", true);
    $('#ocbPdtAllowManage').prop("checked", true);
}

function JSxPdtModalUnitPackBarCodeClear() {
    $('#oetEditData').val('0');
    $('#oetPDTUnitBarBarCode').val('');
    $('#oetPDTUnitBarLocCode').val('');
    $('#oetPDTUnitBarLocName').val('');
    $('#ocbPdtBarAlwSale').prop("checked", true);
    $('#ocbPdtBarAlwUsed').prop("checked", true);
}


function JSxPdtModalUnitPackSupplierClear() {
    $('#oetEditData').val('0');
    $('#oetPDTUnitSupplierCode').val('');
    $('#oetPDTUnitSupplierName').val('');
    $('#oetPDTSupplierUsrCode').val('');
    $('#oetPDTSupplierUsrName').val('');
    $('#ocbSupAlwMon').prop("checked", true);
    $('#ocbSupAlwTue').prop("checked", true);
    $('#ocbSupAlwWed').prop("checked", true);
    $('#ocbSupAlwThu').prop("checked", true);
    $('#ocbSupAlwFri').prop("checked", true);
    $('#ocbSupAlwSat').prop("checked", true);
    $('#ocbSupAlwSun').prop("checked", true);
}

function JSxClearShopInfor() {
    if ($("#oetPdtMerCode").val() != "") {
        $("#obtBrowsePdtInfoMgp").attr("disabled", false);
    } else {
        $("#obtBrowsePdtInfoMgp").attr("disabled", true);
    }
    if ($("#oetPdtBchCode").val() == '' || $("#oetPdtMerCode").val() == '') {
        $("#obtBrowsePdtRetShp").attr("disabled", "disabled");
    } else {
        $("#obtBrowsePdtRetShp").removeAttr("disabled");
    }
    $("#oetPdtInfoShpCode").val("");
    $("#oetPdtInfoShpName").val("");
}



function JSxPdtSetBrowseProduct() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var dTime = new Date();
        var dTimelocalStorage = dTime.getTime();

        //รับรหัสสินค้าชุดจาก input และนำมาทำให้เป็นรูปแบบ array 2มิติ เพื่อนำไปเช็คใน BrowseProduct
        var aDataNotINItem = [];
        var aDataDup = $('#oetPdtSetPdtCodeDup').val().split(",");
        for (var i = 0; i < aDataDup.length; i++) {
            var tPdtCodeSet = [
                aDataDup[i],
                ""
            ];
            aDataNotINItem.push(tPdtCodeSet);
        }
        var tProductCode = $('#oetPdtCode').val();
        var tPdtAgnCode = $('#oetPdtAgnCode').val();


        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                'Qualitysearch': ['SUP', 'NAMEPDT', 'CODEPDT', 'FromToBCH', 'FromToSHP', 'FromToPGP', 'FromToPTY'],
                'PriceType': ['Pricesell'],
                'SelectTier': ['PDT'], //PDT, Barcode
                // 'Elementreturn': ['oetInputTestValue', 'oetInputTestName'],
                'ShowCountRecord': 10,
                'NextFunc': 'JSxPdtSetAddProductInput',
                'ReturnType': 'S', //S = Single M = Multi
                'SPL': ['', ''],
                'BCH': ['', ''],
                'SHP': ['', ''],
                'TimeLocalstorage': dTimelocalStorage,
                'NOTINITEM': '' , //aDataNotINItem,
                'ProductCode': tProductCode,
                'AgenCode': tPdtAgnCode
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvModalDOCPDT').modal({ backdrop: 'static', keyboard: false })
                $('#odvModalDOCPDT').modal({ show: true });

                //remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $('#odvModalsectionBodyPDT').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSetBrowseProductSV() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var dTime = new Date();
        var dTimelocalStorage = dTime.getTime();
        //รับรหัสสินค้าชุดจาก input และนำมาทำให้เป็นรูปแบบ array 2มิติ เพื่อนำไปเช็คใน BrowseProduct
        var aDataNotINItem = [];
        var aDataDup = $('#oetPdtSetPdtCodeDup').val().split(",");
        for (var i = 0; i < aDataDup.length; i++) {
            var tPdtCodeSet = [
                aDataDup[i],
                ""
            ];
            aDataNotINItem.push(tPdtCodeSet);
        }
        var tProductCode    = $('#oetPdtCode').val();
        var tPdtAgnCode     = $('#oetPdtAgnCode').val();

        // เช็ค ประเภทบริการ > ถ้าเป็น ตรวจสอบ - ไม่คิดราคา ให้ filter เอาเฉพาะสินค้าไม่ควบคุมสต๊อก
        var nStaPdtSvType   = $('#ocmPdtSvType').val();
        var aWhereCondition = [];
        if(nStaPdtSvType == '2'){
            var tTextStkControlType2 = " AND Products.FTPdtStkControl = 2 ";
            aWhereCondition.push(tTextStkControlType2);
        }

        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                'PriceType'          : ['Pricesell'],
                'SelectTier'         : ['Barcode'], //PDT, Barcode
                'ShowCountRecord'    : 10,
                'NextFunc'           : 'JSxPdtSVAddProductInput',
                'ReturnType'         : 'S', //S = Single M = Multi
                'TimeLocalstorage'   : dTimelocalStorage,
                'NOTINITEM'          : '', //aDataNotINItem,
                'tSNPDT'             : '1,2',
                'Where'              : aWhereCondition,
                'nStaDefaultPdtType' : 'ALL'
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvModalDOCPDT').modal({ backdrop: 'static', keyboard: false })
                $('#odvModalDOCPDT').modal({ show: true });

                //remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $('#odvModalsectionBodyPDT').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSetAddProductInput(elem) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var aData = JSON.parse(elem);
        // console.log(aData);
        $('#oetPdtSetPdtCode').val(aData[0]['packData']['PDTCode']);
        $('#oetPdtSetPdtName').val(aData[0]['packData']['PDTName']);

        $('#oetPdtSetUnitFact').val(aData[0]['packData']['UnitFact']);
        $('#oetPdtSetUnitCode').val(aData[0]['packData']['PUNCode']);
        $('#oetPdtSetUnitName').val(aData[0]['packData']['PUNName']);
        $('#oetPdtSetPrice').val(aData[0]['packData']['PriceRet']);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSVAddProductInput(elem) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var aData = JSON.parse(elem);
        // console.log(aData);
        $('#oetPdtSVPdtCode').val(aData[0]['packData']['PDTCode']);
        $('#oetPdtSVPdtName').val(aData[0]['packData']['PDTName']);
        $('#oetPdtSVUnitFact').val(aData[0]['packData']['UnitFact']);
        $('#oetPdtSVUnitCode').val(aData[0]['packData']['PUNCode']);
        $('#oetPdtSVUnitName').val(aData[0]['packData']['PUNName']);
    } else {
        JCNxShowMsgSessionExpired();
    }
}


function JSxPdtSetCallDataTable() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        // JCNxOpenLoading();
        $('#odvPdtSetSubMenuSta').show();
        $.ajax({
            type: "POST",
            url: "productSetDataTable",
            data: $('#ofmAddEditProduct').serialize(),
            async: false,
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);
                if (aReturn['nStaEvent'] == 1) {

                    //เก็บรหัสสินค้าชุดลงใน input เพื่อนำไปเช็คสินค้าซ้ำในหน้าเพิ่มใหม่
                    $('#oetPdtSetPdtCodeDup').val('');

                    //เพิ่มสินค้าตัวแม่ลงไปเป็นอันดับแรก เพื่อไม่ให้สินค้าชุด เลือกสินค้าหลักได้
                    $('#oetPdtSetPdtCodeDup').val($('#oetPdtCode').val());

                    //เอาสินค้าที่เป็นสินค้าแม่ ของสินค้าอื่นๆ มาใส่เพื่อไม่ให้เลือกได้
                    if (aReturn['aDataOthPdt']['tCode'] == '1') {
                        for (var i = 0; i < aReturn['aDataOthPdt']['aItems'].length; i++) {
                            var tPdtCodeSet = aReturn['aDataOthPdt']['aItems'][i]['FTPdtCodeSet'];
                            var tPdtCodeSetDup = $('#oetPdtSetPdtCodeDup').val();
                            if (tPdtCodeSetDup == "") {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSet);
                            } else {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSetDup + "," + tPdtCodeSet);
                            }
                        }
                    }

                    //เอาสินค้าลูกของตัวเอง มาใส่เพื่อไม่ให้เลือกได้
                    if (aReturn['aDataPdtSet']['tCode'] == '1') {
                        for (var i = 0; i < aReturn['aDataPdtSet']['aItems'].length; i++) {
                            var tPdtCodeSet = aReturn['aDataPdtSet']['aItems'][i]['FTPdtCodeSet'];
                            var tPdtCodeSetDup = $('#oetPdtSetPdtCodeDup').val();
                            if (tPdtCodeSetDup == "") {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSet);
                            } else {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSetDup + "," + tPdtCodeSet);
                            }
                        }
                    }

                    $('#odvPdtSetDataTable').html(aReturn['tHTML']);
                    $('#olbPdtSetAdd').addClass('xCNHide');
                    $('#obtPdtSetAdd').removeClass('xCNHide');
                    $('#obtPdtSetBack').addClass('xCNHide');
                    $('#obtPdtSetSave').addClass('xCNHide');
                    $('#olbPdtSetEdit').addClass('xCNHide');
                    $('#odvtTmpImgForPdtSetPage').removeClass('xCNHide');
                    // if (aReturn['nStaPdtSet'] > 0) { //หากสินค้านี้เป็นสินค้าชุดในสินค้าอื่นๆ จะไม่สามรรถให้เพิ่มสินค้าลูกได้
                    //     $('#obtPdtSetAdd').addClass('xCNHide');
                    // }

                } else {
                    alert('ไม่สามารถโหลดรายการสินค้าชุดได้');
                }
                if ($("#oetStatus").val() == 'view') {
                    $('#obtPdtSetAdd').hide();
                    $(".xCNIconDelete").addClass("xCNDocDisabled");
                    $(".xCNIconDelete").prop("onclick", null).off("click")
                    $(".xCNIconEdit").addClass("xCNDocDisabled");
                    $(".xCNIconEdit").prop("onclick", null).off("click")
                }
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

function JSxPdtSetCallSVDataTable() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        // JCNxOpenLoading();
        $('#odvPdtSetSVSubMenuSta').show();
        $('#odvSetShwDT').show();
        $('#odvBtnPdtAddEdit').show();
        $('.xWAddMargin').show();
        $.ajax({
            type: "POST",
            url: "productSVDataTable",
            data: $('#ofmAddEditProduct').serialize(),
            async: false,
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);
                if (aReturn['nStaEvent'] == 1) {

                    //เก็บรหัสสินค้าชุดลงใน input เพื่อนำไปเช็คสินค้าซ้ำในหน้าเพิ่มใหม่
                    $('#oetPdtSetPdtCodeDup').val('');

                    //เพิ่มสินค้าตัวแม่ลงไปเป็นอันดับแรก เพื่อไม่ให้สินค้าชุด เลือกสินค้าหลักได้
                    $('#oetPdtSetPdtCodeDup').val($('#oetPdtCode').val());

                    //เอาสินค้าที่เป็นสินค้าแม่ ของสินค้าอื่นๆ มาใส่เพื่อไม่ให้เลือกได้
                    if (aReturn['aDataOthPdt']['tCode'] == '1') {
                        for (var i = 0; i < aReturn['aDataOthPdt']['aItems'].length; i++) {
                            var tPdtCodeSet = aReturn['aDataOthPdt']['aItems'][i]['FTPdtCodeSet'];
                            var tPdtCodeSetDup = $('#oetPdtSetPdtCodeDup').val();
                            if (tPdtCodeSetDup == "") {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSet);
                            } else {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSetDup + "," + tPdtCodeSet);
                            }
                        }
                    }

                    //เอาสินค้าลูกของตัวเอง มาใส่เพื่อไม่ให้เลือกได้
                    if (aReturn['aDataPdtSet']['tCode'] == '1') {
                        for (var i = 0; i < aReturn['aDataPdtSet']['aItems'].length; i++) {
                            var tPdtCodeSet = aReturn['aDataPdtSet']['aItems'][i]['FTPdtCodeSet'];
                            var tPdtCodeSetDup = $('#oetPdtSetPdtCodeDup').val();
                            if (tPdtCodeSetDup == "") {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSet);
                            } else {
                                $('#oetPdtSetPdtCodeDup').val(tPdtCodeSetDup + "," + tPdtCodeSet);
                            }
                        }
                    }
                    $('#odvPdtSetSVDataTable').html(aReturn['tHTML']);
                    $('#olbPdtSVSetAdd').addClass('xCNHide');
                    $('#obtPdtSVSetAdd').removeClass('xCNHide');
                    $('#obtPdtSVSetBack').addClass('xCNHide');
                    $('#obtPdtSVSetSave').addClass('xCNHide');
                    $('#olbPdtSVSetEdit').addClass('xCNHide');
                    $('#odvtTmpImgForPdtSVSetPage').removeClass('xCNHide');
                    // if (aReturn['nStaPdtSet'] > 0) { //หากสินค้านี้เป็นสินค้าชุดในสินค้าอื่นๆ จะไม่สามรรถให้เพิ่มสินค้าลูกได้
                    //     $('#obtPdtSetAdd').addClass('xCNHide');
                    // }

                } else {
                    alert('ไม่สามารถโหลดรายการสินค้าชุดได้');
                }
                if ($("#oetStatus").val() == 'view') {
                    $('#obtPdtSVSetAdd').hide();
                    $(".xCNIconDelete").addClass("xCNDocDisabled");
                    $(".xCNIconDelete").prop("onclick", null).off("click")
                    $(".xCNIconEdit").addClass("xCNDocDisabled");
                    $(".xCNIconEdit").prop("onclick", null).off("click")
                }
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

function JSxPdtSetCallPageAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $('#odvPdtSetSubMenuSta').hide();
        $.ajax({
            type: "POST",
            url: "productSetCallPageAdd",
            data: {},
            async: false,
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);
                $('#odvPdtSetDataTable').html(aReturn['tHTML']);
                $('#olbPdtSetAdd').removeClass('xCNHide');
                $('#obtPdtSetAdd').addClass('xCNHide');
                $('#obtPdtSetBack').removeClass('xCNHide');
                $('#obtPdtSetSave').removeClass('xCNHide');
                $('#olbPdtSetEdit').addClass('xCNHide');
                $('#odvtTmpImgForPdtSetPage').addClass('xCNHide');
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

function JSxPdtSetCallPageSVAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $('#odvPdtSetSVSubMenuSta').hide();
        $('#odvSetShwDT').hide();
        $.ajax({
            type: "POST",
            url: "productSVCallPageAdd",
            data: {},
            async: false,
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);
                $('#odvPdtSetSVDataTable').html(aReturn['tHTML']);
                $('#olbPdtSVSetAdd').removeClass('xCNHide');
                $('#obtPdtSVSetAdd').addClass('xCNHide');
                $('#odvBtnPdtAddEdit').hide();
                $('#obtPdtSVSetBack').removeClass('xCNHide');
                $('#obtPdtSVSetSave').removeClass('xCNHide');
                $('#olbPdtSVSetEdit').addClass('xCNHide');
                $('#odvtTmpImgForPdtSVSetPage').addClass('xCNHide');
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

function JSxPdtSetEventAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddEditProduct').validate({
            rules: {
                oetPdtSetPdtCode: "required",
                oetPdtSetPstQty: "required",

            },
            messages: {
                oetPdtSetPdtCode: $('#oetPdtSetPdtCode').data('validate'),
                oetPdtSetPstQty: $('#oetPdtSetPstQty').data('validate'),
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
                $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
            },
            submitHandler: function(form) {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "productSetEventAdd",
                    data: $('#ofmAddEditProduct').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function() {
                        $("#ohdPdtSetOrSN").val('2');
                        JSxPdtSetCallDataTable();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSVEventAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddEditProduct').validate({
            rules: {
                oetPdtSetPdtCode: "required",
                oetPdtSetPstQty: "required",

            },
            messages: {
                oetPdtSetPdtCode: $('#oetPdtSetPdtCode').data('validate'),
                oetPdtSetPstQty: $('#oetPdtSetPstQty').data('validate'),
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
                $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
            },
            submitHandler: function(form) {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "productSVEventAdd",
                    data: $('#ofmAddEditProduct').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function() {
                        $("#ohdPdtSetOrSN").val('5');
                        JSxPdtSetCallSVDataTable();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Create by Napat(Jame) 05/11/2019
function JSxPdtSetCallPageEdit(ptPdtCodeSet) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $('#odvPdtSetSubMenuSta').hide();
        $.ajax({
            type: "POST",
            url: "productSetCallPageEdit",
            data: {
                ptPdtCodeSet: ptPdtCodeSet,
                ptPdtCode: $('#oetPdtCode').val()
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);
                $('#odvPdtSetDataTable').html(aReturn['tHTML']);
                $('#olbPdtSetAdd').addClass('xCNHide');
                $('#olbPdtSetEdit').removeClass('xCNHide');
                $('#obtPdtSetAdd').addClass('xCNHide');
                $('#obtPdtSetBack').removeClass('xCNHide');
                $('#obtPdtSetSave').removeClass('xCNHide');
                $('#odvtTmpImgForPdtSetPage').addClass('xCNHide');
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

//Create by Off 29/06/2021
function JSxPdtSetSVCallPageEdit(ptPdtCodeSet) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $('#odvPdtSetSVSubMenuSta').hide();
        $('#odvSetShwDT').hide();
        $.ajax({
            type: "POST",
            url: "productSetSVCallPageEdit",
            data: {
                ptPdtCodeSet: ptPdtCodeSet,
                ptPdtCode: $('#oetPdtCode').val()
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);
                $('#odvPdtSetSVDataTable').html(aReturn['tHTML']);
                $('#olbPdtSVSetAdd').addClass('xCNHide');
                $('#olbPdtSVSetEdit').removeClass('xCNHide');
                $('#obtPdtSVSetAdd').addClass('xCNHide');
                $('#odvBtnPdtAddEdit').hide();
                $('#obtPdtSVSetBack').removeClass('xCNHide');
                $('#obtPdtSVSetSave').removeClass('xCNHide');
                $('#odvtTmpImgForPdtSVSetPage').addClass('xCNHide');
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

function JSxPdtSetEventDelete(ptPdtCodeSet, ptPdtNameSet) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#odvModalDeletePdtSet #ospTextConfirmDelPdtSet').html($('#oetTextComfirmDeleteSingle').val() + ptPdtCodeSet + ' (' + ptPdtNameSet + ')');
        $('#odvModalDeletePdtSet').modal('show');
        $('#odvModalDeletePdtSet #osmConfirmDelPdtSet').unbind().click(function() {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "productSetEventDelete",
                data: {
                    ptPdtCodeSet: ptPdtCodeSet,
                    ptPdtCode: $('#oetPdtCode').val()
                },
                async: false,
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    // console.log(oResult);
                    JSxPdtSetCallDataTable();
                    $('.modal-backdrop').remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSVEventDelete(ptPdtCodeSet, ptPdtNameSet) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#odvModalDeletePdtSVSet #ospTextConfirmDelPdtSVSet').html($('#oetTextComfirmDeleteSingle').val() + ptPdtCodeSet + ' (' + ptPdtNameSet + ')');
        $('#odvModalDeletePdtSVSet').modal('show');
        $('#odvModalDeletePdtSVSet #osmConfirmDelPdtSVSet').unbind().click(function() {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "productSVEventDelete",
                data: {
                    ptPdtCodeSub: ptPdtCodeSet,
                    ptPdtCode: $('#oetPdtCode').val()
                },
                async: false,
                cache: false,
                timeout: 0,
                success: function() {
                    JSxPdtSetCallSVDataTable();
                    $('.modal-backdrop').remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}


function JSxPdtSVEventProductModal(ptPdtCodeSet, ptPdtNameSet) {
    // return false;
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            // JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "productSVGetDetauil",
                data: {
                    tPdtCodeSet: ptPdtCodeSet,
                    ptPdtCode: $('#oetPdtCode').val()
                },
                async: false,
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturn = JSON.parse(oResult);
                    var aPdtSvDetail = aReturn['aItems']['0'];
                    var aAnwserDetail = aReturn['aAnwser'];
                    var aAnwserType = aReturn['aAnwser']['0']['FTPdtChkType'];
                    if(aAnwserType == '1'){
                        var tType = 'แบบระบุหมายเหตุ';
                    }else if(aAnwserType == '2'){
                        var tType = 'เลือกได้มากกว่าหนึ่งรายการ';
                    }else if(aAnwserType == '3'){
                        var tType = 'เลือกได้หนึงรายการ';
                    }
                    $(".xWSVDetail1").text(aPdtSvDetail['FTPdtCodeSub']);
                    $(".xWSVDetail2").text(aPdtSvDetail['FTPdtName']);
                    $(".xWSVDetail3").text(tType);
                    $( "#odvSVAnwser" ).empty();
                    for (var i = 0; i < aAnwserDetail.length; i++) {
                        $( "#odvSVAnwser" ).append( "<div class='col-sm-2 text-center' style='margin-right: 10px;'><button type='button' class='btn' style='background-color: #1866ae;color: #FFFFFF'>"+(i+1)+'. '+aAnwserDetail[i]['FTPdtChkResult']+"</button></div>" );
                    }
                    $('#odvModalGetPdtSVSetDetail').modal('show');
                    // $('.modal-backdrop').remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSetUpdateStaSetPri(ptPdtStaSetPri) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "productSetUpdStaSetPri",
            data: {
                ptPdtStaSetPri: ptPdtStaSetPri,
                ptPdtCode: $('#oetPdtCode').val()
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function() {},
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSetUpdateStaSetShwDT(ptPdtStaSetShwDT) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "productSetUpdStaSetShwDT",
            data: {
                ptPdtStaSetShwDT: ptPdtStaSetShwDT,
                ptPdtCode: $('#oetPdtCode').val()
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function() {},
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

function JSxPdtSetUpdatePdtStaSetPrcStk(ptPdtStaSetPrcStk) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "productSetUpdPdtStaSetPrcStk",
            data: {
                ptPdtStaSetPrcStk: ptPdtStaSetPrcStk,
                ptPdtCode: $('#oetPdtCode').val()
            },
            cache: false,
            timeout: 0,
            success: function() {},
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}


function FSxPdtCallPageAdjust(){
    var nStaSession = JCNxFuncChkSessionExpired();
    JCNxOpenLoading();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "adjustProduct",
            error: function (jqXHR, textStatus, errorThrown) {

                JCNxCloseLoading();
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            },
            success: function (tView) {

                //console.log(tView);
                $(window).scrollTop(0);
                $('.odvMainContent').html(tView);

                // Chk Status Favorite
                JSxChkStaDisFavorite('adjustProduct');
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

