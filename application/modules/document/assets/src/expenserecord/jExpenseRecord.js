var nPXStaPXBrowseType     = $("#oetPXStaBrowse").val();
var tPXCallPXBackOption    = $("#oetPXCallBackOption").val();

$("document").ready(function(){
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    if(typeof(nPXStaPXBrowseType) != 'undefined' && nPXStaPXBrowseType == 0){
        // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliPXTitle').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPXCallPageList();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $("#obtPXSubmitFromDoc").removeAttr("disabled");


        // Event Click Button Add Page
        $('#obtPXCallPageAdd').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPXCallPageAddDoc();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Call Back Page
        $('#obtPXCallBackPage').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvPXCallPageList();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Cancel Document
        $('#obtPXCancelDoc').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSnPXCancelDocument(false);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Appove Document
        $('#obtPXApproveDoc').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSxPXSetStatusClickSubmit(2);
                JSxPXSubmitEventByButton('approve');
                // JSxPXApproveDocument(false);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Submit From Document
        $('#obtPXSubmitFromDoc').unbind().click(function(){
            // var nStaSession = 1;
            // if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tFrmSplName         = $('#oetPXFrmSplName').val();
                var tCheckIteminTable   = $('#otbPXDocPdtAdvTableList .xWPdtItem').length;
                var nPXStaValidate      = $('.xPXStaValidate0').length;

                if( tCheckIteminTable > 0 ){
                    if( nPXStaValidate == 0 ){
                        if( tFrmSplName != '' ){
                            JSxPXSetStatusClickSubmit(1);
                            $('#obtPXSubmitDocument').click();
                        }else{
                            $('#odvPXModalPleseselectCustomer').modal('show');
                        }
                    }else{
                        FSvCMNSetMsgWarningDialog($('#ohdPXValidatePdtImp').val());
                    }
                }else{
                    FSvCMNSetMsgWarningDialog($('#ohdPXValidatePdt').val());
                }
            // }else{
            //     JCNxShowMsgSessionExpired();
            // }
        });

        JSxPXNavDefultDocument();
        JSvPXCallPageList();
    }else{
        // Event Modal Call Back Before List
        $('#oahPXBrowseCallBack').unbind().click(function (){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JCNxBrowseData(tPXCallPXBackOption);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Modal Call Back Previous
        $('#oliPXBrowsePrevious').unbind().click(function (){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JCNxBrowseData(tPXCallPXBackOption);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtPXBrowseSubmit').unbind().click(function () {
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSxPXSetStatusClickSubmit(1);
                $('#obtPXSubmitDocument').click();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        JSxPXNavDefultDocument();
        JSvPXCallPageAddDoc();
    }
});

// Function     : Set Defult Nav Menu Document
// Parameters   : Document Ready Or Parameter Event
// Creator      : 17/06/2019 wasin (Yoshi AKA: Mr.JW)
// LastUpdate   :
// Return       : Set Default Nav Menu Document
// ReturnType   : -
function JSxPXNavDefultDocument(){
    if(typeof(nPXStaPXBrowseType) != 'undefined' && nPXStaPXBrowseType == 0) {
        // Title Label Hide/Show
        $('#oliPXTitleAdd').hide();
        $('#oliPXTitleEdit').hide();
        $('#oliPXTitleDetail').hide();
        // Button Hide/Show
        $('#odvPXBtnGrpAddEdit').hide();
        $('#odvPXBtnGrpInfo').show();
        $('#obtPXCallPageAdd').show();
    }else{
        $('#odvModalBody #odvPXMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliPXNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvPXBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNPXBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNPXBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

// Function     : Call Page List
// Parameters   : Document Redy Function
// Creator      : 17/06/2019 wasin (Yoshi AKA: Mr.JW)
// LastUpdate   :
// Return       : Call View Tranfer Out List
// ReturnType   : View
function JSvPXCallPageList(){
    $.ajax({
        type: "GET",
        url: "docPXFormSearchList",
        cache: false,
        timeout: 0,
        success: function (tResult){
            $("#odvPXContentPageDocument").html(tResult);
            JSxPXNavDefultDocument();
            JSvPXCallPageDataTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function     : Get Data Advanced Search
// Parameters   : Function Call Page
// Creator      : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
// LastUpdate   : -
// Return       : object Data Advanced Search
// ReturnType   : object
function JSoPXGetAdvanceSearchData(){
    var oAdvanceSearchData  = {
        tSearchAll          : $("#oetPXSearchAllDocument").val(),
        tSearchBchCodeFrom  : $("#oetPXAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo    : $("#oetPXAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom  : $("#oetPXAdvSearcDocDateFrom").val(),
        tSearchDocDateTo    : $("#oetPXAdvSearcDocDateTo").val(),
        tSearchStaDoc       : $("#ocmPXAdvSearchStaDoc").val(),
        tSearchStaApprove   : $("#ocmPXAdvSearchStaApprove").val(),
        tSearchStaPrcStk    : $("#ocmPXAdvSearchStaPrcStk").val(),
        tSearchStaDocAct    : $("#ocmCardNewCardStaDocAct").val()
    };
    return oAdvanceSearchData;
}

// Function     : Call Page List
// Parameters   : Document Redy Function
// Creator      : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
// LastUpdate   :
// Return       : Call View Tabel Data List Document
// ReturnType   : View
function JSvPXCallPageDataTable(pnPage){
    JCNxOpenLoading();
    var oAdvanceSearch  = JSoPXGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if(typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docPXDataTable",
        data: {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent,
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxPXNavDefultDocument();
                $('#ostPXDataTableDocument').html(aReturnData['tPXViewDataTableList']);
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

//Functionality : Function Chack And Show Button Delete All
//Parameters    : LocalStorage Data
//Creator       : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
//Return        : Show Button Delete All
//Return Type   : -
function JSxPXShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliPXBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliPXBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliPXBtnDeleteAll").addClass("disabled");
        }
    }
}

//Functionality  : Insert Text In Modal Delete
//Functionality  : LocalStorage Data
//Creator        : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
//Return         : Insert Code In Text Input
//Return Type    : -
function JSxPXTextinModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") { } else {
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
        $("#odvPXModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
        $("#odvPXModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
    }
}

//Functionality  : Function Chack Value LocalStorage
//Functionality  : Event Select List Branch
//Creator        : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
//Return         : Duplicate/none
//Return Type    : string
function JStPXFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// Functionality : เปลี่ยนหน้า Pagenation Document HD List
// Parameters : Event Click Pagenation Table HD List
// Creator : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
// Return : View
// Return Type : View
function JSvPXClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld        = $(".xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent    = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld        = $(".xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent    = nPageNew;
                break;
            default:
                nPageCurrent    = ptPage;
        }
        JSvPXCallPageDataTable(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Document Single
// Parameters: Function Call Page
// Creator: 19/06/2019 wasin (Yoshi AKA: Mr.JW)
// LastUpdate: -
// Return: object Data Sta Delete
// ReturnType: object
function JSoPXDelDocSingle(ptCurrentPage, ptPXDocNo){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        if(typeof(ptPXDocNo) != undefined && ptPXDocNo != ""){
            var tTextConfrimDelSingle   = $('#oetTextComfirmDeleteSingle').val()+"&nbsp"+ptPXDocNo+"&nbsp"+$('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvPXModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvPXModalDelDocSingle').modal('show');
            $('#odvPXModalDelDocSingle #osmConfirmDelSingle').unbind().click(function(){
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docPXEventDelete",
                    data: {'tDataDocNo' : ptPXDocNo},
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1') {
                            $('#odvPXModalDelDocSingle').modal('hide');
                            $('#odvPXModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function () {
                                JSvPXCallPageDataTable(ptCurrentPage);
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

// Function: Event Single Delete Doc Mutiple
// Parameters: Function Call Page
// Creator: 19/06/2019 wasin (Yoshi AKA: Mr.JW)
// LastUpdate: -
// Return: object Data Sta Delete
// ReturnType: object
function JSoPXDelDocMultiple(){
    var aDataDelMultiple    = $('#odvPXModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
    var aTextsDelMultiple   = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
    var aDataSplit          = aTextsDelMultiple.split(" , ");
    var nDataSplitlength    = aDataSplit.length;
    var aNewIdDelete        = [];
    for ($i = 0; $i < nDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }
    if (nDataSplitlength > 1) {
        JCNxOpenLoading();
        localStorage.StaDeleteArray = '1';
        $.ajax({
            type: "POST",
            url: "docPXEventDelete",
            data: {'tDataDocNo' : aNewIdDelete},
            cache: false,
            timeout: 0,
            success: function (oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    setTimeout(function () {
                        $('#odvPXModalDelDocMultiple').modal('hide');
                        $('#odvPXModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                        $('#odvPXModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                        $('.modal-backdrop').remove();
                        localStorage.removeItem('LocalItemData');
                        JSvPXCallPageList();
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

// Functionality : Call Page PX Add Page
// Parameters : Event Click Buttom
// Creator : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
// Return : View
// Return Type : View
function JSvPXCallPageAddDoc(){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docPXPageAdd",
        cache: false,
        timeout: 0,
        success: function (oResult) {
            var aReturnData = JSON.parse(oResult);
            if(aReturnData['nStaEvent'] == '1') {
                if (nPXStaPXBrowseType == '1') {
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                    $('#odvModalBodyBrowse').html(aReturnData['tPXViewPageAdd']);
                } else {
                    // Hide Title Menu And Button
                    $('#oliPXTitleEdit').hide();
                    $('#oliPXTitleDetail').hide();
                    $("#obtPXApproveDoc").hide();
                    $("#obtPXCancelDoc").hide();
                    $('#obtPXPrintDoc').hide();
                    $('#odvPXBtnGrpInfo').hide();
                    // Show Title Menu And Button
                    $('#oliPXTitleAdd').show();
                    $('#odvPXBtnGrpSave').show();
                    $('#odvPXBtnGrpAddEdit').show();

                    // Remove Disable Button Add
                    $(".xWBtnGrpSaveLeft").attr("disabled",false);
                    $(".xWBtnGrpSaveRight").attr("disabled",false);

                    $('#odvPXContentPageDocument').html(aReturnData['tPXViewPageAdd']);
                    $('#obtPXBrowseShop').attr('disabled', true);
                }
                JSvPXLoadPdtDataTableHtml();
                JCNxLayoutControll();
            }else{
                var tMessageError = aReturnData['tStaMessg'];
                FSvCMNSetMsgErrorDialog(tMessageError);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Functionality: Call Page Product Table In Add Document
// Parameters: Function Ajax Success
// Creator: 28/06/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: View
// ReturnType : View
function JSvPXLoadPdtDataTableHtml(pnPage){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1){
        if($("#ohdPXRoute").val() == "docPXEventAdd"){
            var tPXDocNo    = "";
        }else{
            var tPXDocNo    = $("#oetPXDocNo").val();
        }

        var tPXStaApv       = $("#ohdPXStaApv").val();
        var tPXStaDoc       = $("#ohdPXStaDoc").val();
        var tPXVATInOrEx    = $("#ocmPXFrmSplInfoVatInOrEx").val();

        //เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#otbPXDocPdtAdvTableList .xWPdtItem").length == 0){
            if (pnPage != undefined) {
                pnPage = pnPage - 1;
            }
        }

        if(pnPage == '' || pnPage == null){
            var pnNewPage = 1;
        }else{
            var pnNewPage = pnPage;
        }
        var nPageCurrent = pnNewPage;
        var tSearchPdtAdvTable  = $('#oetPXFrmFilterPdtHTML').val();

        $.ajax({
            type: "POST",
            url: "docPXPdtAdvanceTableLoadData",
            data: {
                'tBCHCode'              : $('#oetPXFrmBchCode').val(),
                'ptSearchPdtAdvTable'   : tSearchPdtAdvTable,
                'ptPXDocNo'             : tPXDocNo,
                'ptPXStaApv'            : tPXStaApv,
                'ptPXStaDoc'            : tPXStaDoc,
                'ptPXVATInOrEx'         : tPXVATInOrEx,
                'pnPXPageCurrent'       : nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvPXDataPanelDetailPDT #odvPXDataPdtTableDTTemp').html(aReturnData['tPXPdtAdvTableHtml']);
                    var aPXEndOfBill    = aReturnData['aPXEndOfBill'];
                    JSxPXSetFooterEndOfBill(aPXEndOfBill);
                    if($('#ohdPXStaImport').val()==1){
                        $('.xPXImportDT').show();
                    }
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Functionality: Add Product Into Table Document DT Temp
// Parameters   : Function Ajax Success
// Creator      : 01/07/2019 Wasin(Yoshi)
// LastUpdate   : -
// Return       : View
// ReturnType   : View
function JCNvPXBrowsePdt(){
    var tPXSplCode = $('#oetPXFrmSplCode').val();
    // if(typeof(tPXSplCode) !== undefined && tPXSplCode !== ''){
    // var aMulti = [];

    var tWhereCondition = "";
    // if( tPXSplCode != "" ){
    //     tWhereCondition = " AND FTPdtSetOrSN IN('1','3') ";
    // }

    $.ajax({
        type: "POST",
        url: "BrowseDataPDT",
        data: {
            Qualitysearch   : [],
            PriceType       : ["Cost","tCN_Cost","Company","1"],
            SelectTier      : ["Barcode"],
            ShowCountRecord : 10,
            NextFunc        : "FSvPXNextFuncB4SelPDT",
            ReturnType      : "M",
            SPL             : [$("#oetPXFrmSplCode").val(),$("#oetPXFrmSplCode").val()],
            BCH             : [$("#oetPXFrmBchCode").val(),$("#oetPXFrmBchCode").val()],
            MCH             : [$("#oetPXFrmMerCode").val(),$("#oetPXFrmMerCode").val()],
            SHP             : [$("#oetPXFrmShpCode").val(), $("#oetPXFrmShpCode").val()],
            Where           : [tWhereCondition],
            // tNewPdtType     : 'T7',
            aAlwPdtType     : ['T7']
        },
        cache: false,
        timeout: 0,
        success: function(tResult){
            $("#odvModalDOCPDT").modal({backdrop: "static", keyboard: false});
            $("#odvModalDOCPDT").modal({show: true});
            //remove localstorage
            localStorage.removeItem("LocalItemDataPDT");
            $("#odvModalsectionBodyPDT").html(tResult);
            $("#odvModalDOCPDT #oliBrowsePDTSupply").css('display','none');
        },
        error: function (jqXHR,textStatus,errorThrown){
            JCNxResponseError(jqXHR,textStatus,errorThrown);
        }
    });
    // }else{
    //     var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
    //     FSvCMNSetMsgWarningDialog(tWarningMessage);
    //     return;
    // }
}

// Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
// Parameters: Function Behind Edit In Line
// Creator: 02/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: View Table Product Doc DT Temp
// ReturnType : View
function FSvPXEditPdtIntoTableDT(pnSeqNo, ptFieldName, ptValue, pbIsDelDTDis){
    // var nStaSession = JCNxFuncChkSessionExPXred();
    // var  nStaSession = 1;
    // if (typeof nStaSession !== "undefined" && nStaSession == 1){
        var tPXDocNo        = $("#oetPXDocNo").val();
        var tPXBchCode      = $("#oetPXFrmBchCode").val();
        var tPXVATInOrEx    = $('#ocmPXFrmSplInfoVatInOrEx').val();
        $.ajax({
            type: "POST",
            url: "docPXEditPdtIntoDTDocTemp",
            data: {
                'tPXBchCode'    : tPXBchCode,
                'tPXDocNo'      : tPXDocNo,
                'tPXVATInOrEx'  : tPXVATInOrEx,
                'nPXSeqNo'      : pnSeqNo,
                'tPXFieldName'  : ptFieldName,
                'tPXValue'      : ptValue,
                'nPXIsDelDTDis' : (pbIsDelDTDis) ? '1' : '0' // 1: ลบ, 2: ไม่ลบ
            },
            cache: false,
            timeout: 0,
            success: function (oResult){

                if(oResult == 'expire'){
                    JCNxShowMsgSessionExpired();
                }else{
                    JSvPXLoadPdtDataTableHtml();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    // }else{
    //     JCNxShowMsgSessionExpired();
    // }
}

// Functionality: Set Status On Click Submit Buttom
// Parameters: Event Click Save Document
// Creator: 03/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: Set Status Submit By Button In Input Hidden
// ReturnType: None
function JSxPXSetStatusClickSubmit(pnStatus) {
    $("#ohdPXCheckSubmitByButton").val(pnStatus);
}

// Functionality: Add/Edit Document
// Parameters: Event Click Save Document
// Creator: 03/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: -
// ReturnType: None
function JSxPXAddEditDocument(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1){
        JSxPXValidateFormDocument();
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Functionality : Validate From Add Or Update Document
// Parameters: Function Ajax Success
// Creator: 03/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: Status Add Or Update Document
// ReturnType : -
function JSxPXValidateFormDocument(){
    if($("#ohdPXCheckClearValidate").val() != 0){
        $('#ofmPXFormAdd').validate().destroy();
    }

    $('#ofmPXFormAdd').validate({
        // digits: true,
        rules: {
            oetPXDocNo : {
                "required" : {
                    depends: function (oElement) {
                        if($("#ohdPXRoute").val()  ==  "docPXEventAdd"){
                            if($('#ocbPXStaAutoGenCode').is(':checked')){
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
            oetPXDocDate    : {"required" : true},
            oetPXDocTime    : {"required" : true},
        },
        messages: {
            oetPXDocNo      : {"required" : $('#oetPXDocNo').attr('data-validate-required')},
            oetPXDocDate    : {"required" : $('#oetPXDocDate').attr('data-validate-required')},
            oetPXDocTime    : {"required" : $('#oetPXDocTime').attr('data-validate-required')},
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // console.log(error);
            // console.log(element);
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
            if(!$('#ocbPXStaAutoGenCode').is(':checked')){
                JSxPXValidateDocCodeDublicate();
            }else{
                if($("#ohdPXCheckSubmitByButton").val() == 1){
                    JSxPXSubmitEventByButton();
                }
            }
        },
    });
}

// Functionality: Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
// Parameters: -
// Creator: 03/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: -
// ReturnType: -
function JSxPXValidateDocCodeDublicate(){
    $.ajax({
        type: "POST",
        url: "CheckInputGenCode",
        data: {
            'tTableName'    : 'TAPTPXHD',
            'tFieldName'    : 'FTXphDocNo',
            'tCode'         : $('#oetPXDocNo').val()
        },
        success: function (oResult) {
            var aResultData = JSON.parse(oResult);
            $("#ohdPXCheckDuplicateCode").val(aResultData["rtCode"]);

            if($("#ohdPXCheckClearValidate").val() != 1) {
                $('#ofmPXFormAdd').validate().destroy();
            }

            $.validator.addMethod('dublicateCode', function(value,element){
                if($("#ohdPXRoute").val() == "docPXEventAdd"){
                    if($('#ocbPXStaAutoGenCode').is(':checked')) {
                        return true;
                    }else{
                        if($("#ohdPXCheckDuplicateCode").val() == 1) {
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
            $('#ofmPXFormAdd').validate({
                focusInvalid: false,
                onclick: false,
                onfocusout: false,
                onkeyup: false,
                rules: {
                    oetPXDocNo : {"dublicateCode": {}}
                },
                messages: {
                    oetPXDocNo : {"dublicateCode"  : $('#oetPXDocNo').attr('data-validate-duplicate')}
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
                    if($("#ohdPXCheckSubmitByButton").val() == 1) {
                        JSxPXSubmitEventByButton();
                    }
                }
            })

            if($("#ohdPXCheckClearValidate").val() != 1) {
                $("#ofmPXFormAdd").submit();
                $("#ohdPXCheckClearValidate").val(1);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Functionality: Validate Success And Send Ajax Add/Update Document
// Parameters: Function Parameter Behide NextFunc Validate
// Creator: 03/07/2019 Wasin(Yoshi)
// LastUpdate: 04/07/2019 Wasin(Yoshi)
// Return: Status Add/Update Document
// ReturnType: object
function JSxPXSubmitEventByButton(ptType = ''){

    JCNxOpenLoading();

    if( $("#ohdPXRoute").val() !=  "docPXEventAdd" ){
        var tPXDocNo = $('#oetPXDocNo').val();
    }
    $('#obtPXSubmitFromDoc').attr('disabled',true);
    $.ajax({
        type: "POST",
        url: "docPXChkHavePdtForDocDTTemp",
        data: {'ptPXDocNo': tPXDocNo},
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aDataReturnChkTmp   = JSON.parse(oResult);
            if (aDataReturnChkTmp['nStaReturn'] == '1'){
                // console.log($("#ofmPXFormAdd").serializeArray());
                $.ajax({
                    type    : "POST",
                    url     : $("#ohdPXRoute").val(),
                    data    : $("#ofmPXFormAdd").serialize(),
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        var aDataReturnEvent    = JSON.parse(oResult);
                        if(aDataReturnEvent['nStaReturn'] == '1'){

                            var nPXStaCallBack      = aDataReturnEvent['nStaCallBack'];
                            var nPXDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                            var oPXCallDataTableFile = {
                                ptElementID     : 'odvPXShowDataTable',
                                ptBchCode       : $('#oetPXFrmBchCode').val(),
                                ptDocNo         : nPXDocNoCallBack,
                                ptDocKey        :'TAPTPxHD'
                            }
                            JCNxUPFInsertDataFile(oPXCallDataTableFile);
                            if(ptType == 'approve'){
                                JSxPXApproveDocument(false);
                            }else{
                                switch(nPXStaCallBack){
                                    case '1' :
                                        JSvPXCallPageEditDoc(nPXDocNoCallBack);
                                    break;
                                    case '2' :
                                        JSvPXCallPageAddDoc();
                                    break;
                                    case '3' :
                                        JSvPXCallPageList();
                                    break;
                                    default :
                                        JSvPXCallPageEditDoc(nPXDocNoCallBack);
                                }
                            }
                            $("#obtPXSubmitFromDoc").removeAttr("disabled");
                        }else{
                            var tMessageError = aDataReturnEvent['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                            JCNxCloseLoading();
                            $("#obtPXSubmitFromDoc").removeAttr("disabled");
                        }
                        
                    },
                    error   : function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        JCNxCloseLoading();
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

// Functionality: Call Page Edit Document
// Parameters: Event Btn Click Call Edit Document
// Creator: 04/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: Status Add/Update Document
// ReturnType: object
function JSvPXCallPageEditDoc(ptPXDocNo){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        // JStCMMGetPanalLangSystemHTML("JSvPXCallPageEditDoc",ptPXDocNo);
        $.ajax({
            type: "POST",
            url: "docPXPageEdit",
            data: {'ptPXDocNo' : ptPXDocNo},
            cache: false,
            timeout: 0,
            success: function(tResult){
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    // if(nPXStaPXBrowseType == '1') {
                    //     $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                    //     $('#odvModalBodyBrowse').html(aReturnData['tPXViewPageEdit']);
                    // }else{
                        $('#odvPXContentPageDocument').html(aReturnData['tPXViewPageEdit']);
                        JCNxPXControlObjAndBtn();
                        JSvPXLoadPdtDataTableHtml();
                        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");
                        JCNxLayoutControll();
                    // }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);   
                    JCNxCloseLoading();
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown){
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                JCNxCloseLoading();
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Functionality: Function Control Object Button
// Parameters: Event Btn Click Call Edit Document
// Creator: 11/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: Status Add/Update Document
// ReturnType: object
function JCNxPXControlObjAndBtn(){
    // Check สถานะอนุมัติ
    var nPXStaDoc       = $("#ohdPXStaDoc").val();
    var nPXStaApv       = $("#ohdPXStaApv").val();
    var tPXStaDelMQ     = $('#ohdPXStaDelMQ').val();
    // var tPXStaPrcStk    = $('#ohdPXStaPrcStk').val();

    JSxPXNavDefultDocument();

    // Title Menu Set De
    $("#oliPXTitleAdd").hide();
    $('#oliPXTitleDetail').hide();
    $('#oliPXTitleEdit').show();
    $('#odvPXBtnGrpInfo').hide();
    // Button Menu
    $("#obtPXApproveDoc").show();
    $("#obtPXCancelDoc").show();
    $('#obtPXPrintDoc').show();
    $('#odvPXBtnGrpSave').show();
    $('#odvPXBtnGrpAddEdit').show();

    // Remove Disable
    $("#obtPXCancelDoc").attr("disabled",false);
    $("#obtPXApproveDoc").attr("disabled",false);
    $("#obtPXPrintDoc").attr("disabled",false);
    $("#obtPXBrowseSupplier").attr("disabled",false);

    $(".xWConditionSearchPdt").attr("disabled",false);
    $(".ocbListItem").attr("disabled",false);
    $(".xCNBtnDateTime").attr("disabled",false);
    $(".xCNDocBrowsePdt").attr("disabled",false).removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").show();
    $("#oetPXFrmSearchPdtHTML").attr("disabled", false);
    $(".xWBtnGrpSaveLeft").show();
    $(".xWBtnGrpSaveRight").show();
    $("#oliPXEditShipAddress").show();
    $("#oliPXEditTexAddress").show();

    if(nPXStaDoc != 1){
        // Hide/Show Menu Title
        $("#oliPXTitleAdd").hide();
        $('#oliPXTitleEdit').hide();
        $('#oliPXTitleDetail').show();
        // Disabled Button
        $("#obtPXCancelDoc").hide(); // attr("disabled",true);
        $("#obtPXApproveDoc").hide(); // attr("disabled",true);
        $("#obtPXPrintDoc").hide(); // attr("disabled",true);
        $("#obtPXBrowseSupplier").attr("disabled",true);
        $(".xWConditionSearchPdt").attr("disabled",true);
        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetPXFrmSearchPdtHTML").attr("disabled",true);
        $('#odvPXBtnGrpSave').hide();
        // $(".xWBtnGrpSaveLeft").hide(); // attr("disabled", true);
        // $(".xWBtnGrpSaveRight").hide(); // attr("disabled", true);
        $("#oliPXEditShipAddress").hide();
        $("#oliPXEditTexAddress").hide();
        // Hide Button
        $("#obtPXCallPageAdd").hide();

        $("#ocbPXFrmInfoOthStaDocAct").attr("disabled", true);
        $('.xWDropdown').attr('disabled',true);
    }

    // Check Status Appove Success
    if(nPXStaDoc == 1 && nPXStaApv == 1 /*&& tPXStaDelMQ == 1*/){
        // Hide/Show Menu Title
        $("#oliPXTitleAdd").hide();
        $('#oliPXTitleEdit').hide();
        $('#oliPXTitleDetail').show();

        // Hide And Disabled
        $("#obtPXCallPageAdd").hide();
        $("#obtPXCancelDoc").hide(); // attr("disabled",true);
        $("#obtPXApproveDoc").hide(); // attr("disabled",true);
        $("#obtPXBrowseSupplier").attr("disabled",true);
        $(".xWConditionSearchPdt").attr("disabled",true);

        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetPXFrmSearchPdtHTML").attr("disabled", false);
        // $('#odvPXBtnGrpSave').hide();
        // $(".xWBtnGrpSaveLeft").hide(); // attr("disabled", true);
        // $(".xWBtnGrpSaveRight").hide(); // attr("disabled", true);
        $("#oliPXEditShipAddress").hide();
        $("#oliPXEditTexAddress").hide();
        // Show And Disabled
        $("#oliPXTitleDetail").show();

        // $("#ocbPXFrmInfoOthStaDocAct").attr("disabled", true);
        $('.xWDropdown').attr('disabled',true);
    }
}

// Functionality: Cancel Document PX
// Parameters: Event Btn Click Call Edit Document
// Creator: 09/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: Status Cancel Document
// ReturnType: object
function JSnPXCancelDocument(pbIsConfirm){
    var tPXDocNo    = $("#oetPXDocNo").val();
    if(pbIsConfirm){
        $.ajax({
            type: "POST",
            url: "docPXCancelDocument",
            data: {'ptPXDocNo' : tPXDocNo},
            cache: false,
            timeout: 0,
            success: function (tResult) {
                $("#odvPurchaseInviocePopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JSvPXCallPageEditDoc(tPXDocNo);
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        $('#odvPurchaseInviocePopupCancel').modal({backdrop:'static',keyboard:false});
        $("#odvPurchaseInviocePopupCancel").modal("show");
    }
}

// Functionality : Applove Document
// Parameters : Event Click Buttom
// Creator : 09/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return : Status Applove Document
// Return Type : -
function JSxPXApproveDocument(pbIsConfirm){
    if(pbIsConfirm){
        $("#odvPXModalAppoveDoc").modal("hide");
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        var tPXDocNo            = $("#oetPXDocNo").val();
        var tPXBchCode          = $('#oetPXFrmBchCode').val();
        var tPXStaApv           = $("#ohdPXStaApv").val();
        var tPXSplPaymentType   = $("#ocmPXFrmSplInfoPaymentType").val();

        JCNxOpenLoading();
        $.ajax({
            type : "POST",
            url : "docPXApproveDocument",
            data: {
                'ptPXDocNo'             : tPXDocNo,
                'ptPXBchCode'           : tPXBchCode,
                'ptPXStaApv'            : tPXStaApv,
                'ptPXSplPaymentType'    : tPXSplPaymentType
            },
            cache: false,
            timeout: 0,
            success: function(tResult){
                console.log(tResult);
                // try {
                    let oResult = JSON.parse(tResult);
                    if (oResult.nStaEvent == "900") {
                        FSvCMNSetMsgErrorDialog(oResult.tStaMessg);
                    }else{
                        JSvPXCallPageEditDoc(tPXDocNo);
                    }
                // } catch (err) {}
                // JSoPXCallSubscribeMQ();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        $('#odvPXModalAppoveDoc').modal({backdrop:'static',keyboard:false});
        $("#odvPXModalAppoveDoc").modal("show");
    }
}

// Functionality : Call Data Subscript Document
// Parameters : Event Click Buttom
// Creator : 09/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return : Status Applove Document
// Return Type : -
function JSoPXCallSubscribeMQ() {
    // RabbitMQ
    /*===========================================================================*/
    // Document variable
    var tLangCode   = $("#ohdPXLangEdit").val();
    var tUsrBchCode = $("#oetPXFrmBchCode").val();
    var tUsrApv     = $("#ohdPXApvCodeUsrLogin").val();
    var tDocNo      = $("#oetPXDocNo").val();
    var tPrefix     = "RESPPX";
    var tStaApv     = $("#ohdPXStaApv").val();
    var tStaDelMQ   = $("#ohdPXStaDelMQ").val();
    var tQName      = tPrefix + "_" + tDocNo + "_" + tUsrApv;

    // MQ Message Config
    var poDocConfig = {
        tLangCode   : tLangCode,
        tUsrBchCode : tUsrBchCode,
        tUsrApv     : tUsrApv,
        tDocNo      : tDocNo,
        tPrefix     : tPrefix,
        tStaDelMQ   : tStaDelMQ,
        tStaApv     : tStaApv,
        tQName      : tQName
    };

    // RabbitMQ STOMP Config
    var poMqConfig = {
        host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
        username: oSTOMMQConfig.user,
        password: oSTOMMQConfig.password,
        vHost: oSTOMMQConfig.vhost
    };

    // Update Status For Delete Qname Parameter
    var poUpdateStaDelQnameParams = {
        ptDocTableName      : "TAPTPXHD",
        ptDocFieldDocNo     : "FTXphDocNo",
        ptDocFieldStaApv    : "FTXphStaPrcStk",
        ptDocFieldStaDelMQ  : "FTXphStaDelMQ",
        ptDocStaDelMQ       : tStaDelMQ,
        ptDocNo             : tDocNo
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit: "JSvPXCallPageEditDoc",
        tCallPageList: "JSvPXCallPageList"
    };

    // Check Show Progress %
    FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
}

// Functionality : Call Data Subscript Document
// Parameters : Event Click Buttom
// Creator : 09/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return : Status Applove Document
// Return Type :
function JSvPXDOCFilterPdtInTableTemp(){
    JCNxOpenLoading();
    JSvPXLoadPdtDataTableHtml();
}

// Functionality : Function Check Data Search And Add In Tabel DT Temp
// Parameters : Event Click Buttom
// Creator : 30/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return :
// Return Type : Filter
function JSxPXChkConditionSearchAndAddPdt(){
    var tPXDataSearchAndAdd =   $("#oetPXFrmSearchAndAddPdtHTML").val();
    if(tPXDataSearchAndAdd != undefined && tPXDataSearchAndAdd != ""){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var tPXDataSearchAndAdd = $("#oetPXFrmSearchAndAddPdtHTML").val();
            var tPXDocNo            = $('#oetPXDocNo').val();
            var tPXBchCode          = $("#oetPXFrmBchCode").val();
            var tPXStaReAddPdt      = $("#ocmPXFrmInfoOthReAddPdt").val();
            $.ajax({
                type: "POST",
                url: "docPXSerachAndAddPdtIntoTbl",
                data:{
                    'ptPXBchCode'           : tPXBchCode,
                    'ptPXDocNo'             : tPXDocNo,
                    'ptPXDataSearchAndAdd'  : tPXDataSearchAndAdd,
                    'ptPXStaReAddPdt'       : tPXStaReAddPdt,
                },
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aDataReturn = JSON.parse(tResult);
                    switch(aDataReturn['nStaEvent']){

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
}

// Functionality : Function Check Data Search And Add In Tabel DT Temp
// Parameters : Event Click Buttom
// Creator : 01/10/2019 Wasin(Yoshi)
// LastUpdate: -
// Return :
// Return Type : Filter
function JSvPXClickPageList(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld    = $('.xWPXPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld    = $('.xWPXPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvPXCallPageDataTable(nPageCurrent);
}

//Next page
function JSvPXPDTDocDTTempClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWPagePXPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWPagePXPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JCNxOpenLoading();
        JSvPXLoadPdtDataTableHtml(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Functionality: Call End Of Bill OnChange Vat
// Parameters: Function Ajax Success
// Creator: 22/02/2021
// LastUpdate: -
// Return: View
// ReturnType : View
function JSvPXCallEndOfBill(pnPage){
    if($("#ohdPXRoute").val() == "docPXEventAdd"){
        var tPXDocNo    = "";
    }else{
        var tPXDocNo    = $("#oetPXDocNo").val();
    }

    var tPXStaApv       = $("#ohdPXStaApv").val();
    var tPXStaDoc       = $("#ohdPXStaDoc").val();
    var tPXVATInOrEx    = $("#ocmPXFrmSplInfoVatInOrEx").val();

    $.ajax({
        type: "POST",
        url: "docPXEventCallEndOfBill",
        data :{
            'tSelectBCH'        : $('#oetPXFrmBchCode').val(),
            'ptPXDocNo'         : tPXDocNo,
            'ptPXStaApv'        : tPXStaApv,
            'ptPXStaDoc'        : tPXStaDoc,
            'ptPXVATInOrEx'     : tPXVATInOrEx
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if(aReturnData['nStaEvent'] == '1'){
                JCNxOpenLoading();
                JSvPXLoadPdtDataTableHtml();
                var aPXEndOfBill    = aReturnData['aPXEndOfBill'];
                JSxPXSetFooterEndOfBill(aPXEndOfBill);
            }else{
                var tMessageError = aReturnData['tStaMessg'];
                FSvCMNSetMsgErrorDialog(tMessageError);
                JCNxCloseLoading();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });


}
