var nDPSStaSOBrowseType     = $("#oetDPSStaBrowse").val();
var tDPSCallSOBackOption    = $("#oetDPSCallBackOption").val();
var tDPSSesSessionID        = $("#ohdSesSessionID").val();

$("document").ready(function(){
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    if(typeof(nDPSStaSOBrowseType) != 'undefined' && nDPSStaSOBrowseType == 0){
        // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliDPSTitle').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDPSCallPageList();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Add Page
        $('#obtDPSCallPageAdd').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDPSCallPageAddDoc();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Call Back Page
        $('#obtSOCallBackPage').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDPSCallPageList();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Cancel Document
        $('#obtDPSCancelDoc').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSnDPSCancelDocument(false);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Appove Document
        $('#obtDPSApproveDoc').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tCheckIteminTable = $('#otbDPSDocPdtAdvTableList .xWPdtItem').length;
                if(tCheckIteminTable>0){
                JSxDPSSetStatusClickSubmit(2);
                JSxDPSApproveDocument(false);
                }else{
                    FSvCMNSetMsgWarningDialog($('#ohdSOValidatePdt').val());
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Deposit Document
        $('#obtDPSApproveDPS').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tCheckIteminTable = $('#otbDPSDocPdtAdvTableList .xWPdtItem').length;
                if(tCheckIteminTable>0){
                // JSxDPSSetStatusClickSubmit(2);
                JSxDPSDepositDocument(false);
                }else{
                    FSvCMNSetMsgWarningDialog($('#ohdSOValidatePdt').val());
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Submit From Document
        $('#obtDPSSubmitFromDoc').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
              var tHNNumber =  $('#oetSOFrmCstHNNumber').val();
              var tMerCode =  $('#oetSOFrmMerCode').val();
              var tShpCode =  $('#oetSOFrmShpCode').val();
              var tPosCode =  $('#oetSOFrmPosCode').val();
              var tWahCode =  $('#oetSOFrmWahCode').val();
              var tCheckIteminTable = $('#otbDPSDocPdtAdvTableList .xWPdtItem').length;
          
              if(tCheckIteminTable>0){
              if(tHNNumber!='' && tWahCode!=''){
                JSxDPSSetStatusClickSubmit(1);
                $('#obtDPSSubmitDocument').click();
              }else{
                    if(tHNNumber==''){
                        FSvCMNSetMsgWarningDialog($('#oetSOFrmCstHNNumber').attr('lavudate-label'));
                    }else if(tWahCode==''){
                        FSvCMNSetMsgWarningDialog($('#oetSOFrmWahName').attr('data-validate-required'));
                    }
              }
            }else{
                FSvCMNSetMsgWarningDialog($('#ohdSOValidatePdt').val());
            }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        
        JSxDPSNavDefultDocument();
        JSvDPSCallPageList();
    }else{
        // Event Modal Call Back Before List
        $('#oahSOBrowseCallBack').unbind().click(function (){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JCNxBrowseData(tDPSCallSOBackOption);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Modal Call Back Previous
        $('#oliSOBrowsePrevious').unbind().click(function (){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JCNxBrowseData(tDPSCallSOBackOption);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtSOBrowseSubmit').unbind().click(function () {
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSxDPSSetStatusClickSubmit(1);
                $('#obtDPSSubmitDocument').click();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        JSxDPSNavDefultDocument();
        JSvDPSCallPageAddDoc();
    }
});

// Function: Set Defult Nav Menu Document
// Parameters: Document Ready Or Parameter Event
// Creator: 15/07/2021 Off
// LastUpdate:
// Return: Set Default Nav Menu Document
// ReturnType: -
function JSxDPSNavDefultDocument(){
    if(typeof(nDPSStaSOBrowseType) != 'undefined' && nDPSStaSOBrowseType == 0) {
        // Title Label Hide/Show
        $('#oliDPSTitleAdd').hide();
        $('#oliDPSTitleEdit').hide();
        $('#oliDPSTitleDetail').hide();
        $('#oliDPSTitleAprove').hide();
        $('#oliDPSTitleConimg').hide();
        // Button Hide/Show
        $('#odvDPSBtnGrpAddEdit').hide();
        $('#odvDPSBtnGrpInfo').show();
        $('#obtDPSCallPageAdd').show();
    }else{
        $('#odvModalBody #odvDPSMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliDPSNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvDPSBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNDPSBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNDPSBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

// Function: Call Page List
// Parameters: Document Redy Function
// Creator: 17/06/2019 wasin (Yoshi AKA: Mr.JW)
// LastUpdate:
// Return: Call View Tranfer Out List
// ReturnType: View
function JSvDPSCallPageList(){
    $.ajax({
        type: "GET",
        url: "dcmDPSFormSearchList",
        cache: false,
        timeout: 0,
        success: function (tResult){
            $("#odvDPSContentPageDocument").html(tResult);
            JSxDPSNavDefultDocument();
            JSvDPSCallPageDataTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });  
}

//Get Data Advanced Search
function JSoDPSGetAdvanceSearchData(){
    var oAdvanceSearchData  = {
        tSearchAll          : $("#oetDPSSearchAllDocument").val(),
        tSearchBchCodeFrom  : $("#oetDPSAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo    : $("#oetDPSAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom  : $("#oetDPSAdvSearcDocDateFrom").val(),
        // tSearchDocDateTo    : $("#oetDPSAdvSearcDocDateTo").val(),
        tSearchStaDoc       : $("#ocmDPSAdvSearchStaDoc").val(),
        tSearchStaApprove   : $("#ocmDPSAdvSearchStaApprove").val(),
        tSearchStaDocAct    : $("#ocmStaDocAct").val(),
        tSearchStaPrcStk    : $("#ocmDPSAdvSearchStaPrcStk").val()
    };
    return oAdvanceSearchData;
}

//Call Page List
function JSvDPSCallPageDataTable(pnPage){
    JCNxOpenLoading();
    var oAdvanceSearch  = JSoDPSGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if(typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "dcmDPSDataTable",
        data: {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent,
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxDPSNavDefultDocument();
                $('#ostDPSDataTableDocument').html(aReturnData['tDPSViewDataTableList']);
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

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 15/07/2021 Off
//Return: Show Button Delete All
//Return Type: -
function JSxDPSShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliDPSBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliDPSBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliDPSBtnDeleteAll").addClass("disabled");
        }
    }
}

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 15/07/2021 Off
//Return: Insert Code In Text Input
//Return Type: -
function JSxDPSTextinModal() {
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
        $("#odvDPSModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
        $("#odvDPSModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
    }
}

//Functionality: Function Chack Value LocalStorage
//Parameters: Event Select List Branch
//Creator: 15/07/2021 Off
//Return: Duplicate/none
//Return Type: string
function JStDPSFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// Functionality : เปลี่ยนหน้า Pagenation Document HD List 
// Parameters : Event Click Pagenation Table HD List
// Creator : 15/07/2021 Off
// Return : View
// Return Type : View
function JSvDPSClickPage(ptPage) {
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
        JSvDPSCallPageDataTable(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Document Single
// Parameters: Function Call Page
// Creator: 09/07/2021 Off
// LastUpdate: -
// Return: object Data Sta Delete
// ReturnType: object
function JSoDPSDelDocSingle(ptCurrentPage, ptDPSDocNo, ptDPSRefInCode){
    var nStaSession = JCNxFuncChkSessionExpired();    
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        if(typeof(ptDPSDocNo) != undefined && ptDPSDocNo != ""){
            var tTextConfrimDelSingle   = $('#oetTextComfirmDeleteSingle').val()+"&nbsp"+ptDPSDocNo+"&nbsp"+$('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvDPSModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvDPSModalDelDocSingle').modal('show');
            $('#odvDPSModalDelDocSingle #osmConfirmDelSingle').unbind().click(function(){
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "dcmDPSEventDelete",
                    data: {
                    'tDataDocNo' : ptDPSDocNo,
                    'tDPSRefInCode': ptDPSRefInCode},
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1') {
                            $('#odvDPSModalDelDocSingle').modal('hide');
                            $('#odvDPSModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function () {
                                JSvDPSCallPageDataTable(ptCurrentPage);
                            }, 500);
                        }else{
                            JCNxCloseLoading();
                            FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                        if (jqXHR.status != 404){
                            var tLogFunction = 'ERROR';
                            var tDisplayEvent = 'ลบใบมัดจำ';
                            var tErrorStatus = jqXHR.status;
                            var tHtmlError = $(jqXHR.responseText);
                            var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                            var tLogDocNo   = ptDPSDocNo;
                            JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                        }else{
                            //JCNxSendMQPageNotFound(jqXHR,ptDPSDocNo);
                        }
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
// Creator: 09/07/2021 Off
// LastUpdate: -
// Return: object Data Sta Delete
// ReturnType: object
function JSoDPSDelDocMultiple(){
    var aDataDelMultiple    = $('#odvDPSModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
    var aTextsDelMultiple   = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
    var aDataSplit          = aTextsDelMultiple.split(" , ");
    var nDataSplitlength    = aDataSplit.length;
    var aNewIdDelete        = [];
    for ($i = 0; $i < nDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }
    JCNxOpenLoading();
    if (nDataSplitlength > 1) {
        $('.ocbListItem:checked').each(function() {
        var tDataDocNo = $(this).val();
        var tDPSRefInCode = $(this).data('refcode');
        localStorage.StaDeleteArray = '1';
        $.ajax({
            type: "POST",
            url: "dcmDPSEventDelete",
            data: {'tDataDocNo': tDataDocNo,
            'tDPSRefInCode': tDPSRefInCode},
            cache: false,
            timeout: 0,
            success: function (oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    setTimeout(function () {
                        $('#odvDPSModalDelDocMultiple').modal('hide');
                        $('#odvDPSModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                        $('#odvDPSModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                        $('.modal-backdrop').remove();
                        localStorage.removeItem('LocalItemData');
                        JSvDPSCallPageList();
                    }, 1000);
                } else {
                    JCNxCloseLoading();
                    FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'ลบใบมัดจำ';
                    var tErrorStatus = jqXHR.status;
                    var tHtmlError = $(jqXHR.responseText);
                    var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                    var tLogDocNo   = tDataDocNo;
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }else{
                    //JCNxSendMQPageNotFound(jqXHR,tDataDocNo);
                }
            }
        });
    });
    }
}

// Functionality : Call Page PI Add Page
// Parameters : Event Click Buttom
// Creator : 02/07/2021 Off
// Return : View
// Return Type : View
function JSvDPSCallPageAddDoc(){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "dcmDPSPageAdd",
        cache: false,
        timeout: 0,
        success: function (oResult) {
            var aReturnData = JSON.parse(oResult);
            if(aReturnData['nStaEvent'] == '1') {
                if (nDPSStaSOBrowseType == '1') {
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                    $('#odvModalBodyBrowse').html(aReturnData['tSOViewPageAdd']);
                } else {
                    // Hide Title Menu And Button
                    $('#oliDPSTitleEdit').hide();
                    $('#oliDPSTitleDetail').hide();
                    $("#obtDPSApproveDoc").hide();
                    $("#obtDPSApproveDPS").hide();
                    $("#obtDPSCancelDoc").hide();
                    $('#obtDPSPrintDoc').hide();
                    $('#odvDPSBtnGrpInfo').hide();
                    // Show Title Menu And Button
                    $('#oliDPSTitleAdd').show();
                    $('#odvDPSBtnGrpSave').show();
                    $('#odvDPSBtnGrpAddEdit').show();
                    $('#oliDPSTitleAprove').hide();
                    $('#oliDPSTitleConimg').hide();

                    // Remove Disable Button Add 
                    $(".xWBtnGrpSaveLeft").attr("disabled",false);
                    $(".xWBtnGrpSaveRight").attr("disabled",false);

                    $('#odvDPSContentPageDocument').html(aReturnData['tSOViewPageAdd']);
                }
                JSvDPSLoadPdtDataTableHtml();
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
// Creator: 02/07/2021 Off
// LastUpdate: -
// Return: View
// ReturnType : View
function JSvDPSLoadPdtDataTableHtml(pnPage){
        if($("#ohdDPSRoute").val() == "dcmDPSEventAdd"){
            var tSODocNo    = "";
        }else{
            var tSODocNo    = $("#oetDPSDocNo").val();
        }

        var tDPSStaApv       = $("#ohdDPSStaApv").val();
        var tDPSStaDoc       = $("#ohdDPSStaDoc").val();
        var tSOVATInOrEx    = $("#ocmDPSFrmSplInfoVatInOrEx").val();
        var tDPSVATInOrEx    = $("#ohdDPSVatInOrEx").val();
        
        //เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#otbDPSDocPdtAdvTableList .xWPdtItem").length == 0){
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
        var tSearchPdtAdvTable  = $('#oetDPSFrmFilterPdtHTML').val();

        if(tDPSStaApv==2){
            $('#obtDPSDocBrowsePdt').hide();
            $('#obtDPSDocSOPdt').hide();
            $('#obtDPSDocQTPdt').hide();
            $('#obtDPSPrintDoc').hide();
            $('#obtDPSCancelDoc').hide();
            $('#obtDPSApproveDoc').hide();
            $('#odvDPSBtnGrpSave').hide();
        }

        if(tDPSStaDoc==3){
            $('#oetDPSHdRemark').prop( "disabled", true );
        }

        $.ajax({
            type: "POST",
            url: "dcmDPSPdtAdvanceTableLoadData",
            data: {
                'tSelectBCH'            : $('#oetDPSFrmBchCode').val(),
                'ptSearchPdtAdvTable'   : tSearchPdtAdvTable,
                'ptSODocNo'             : tSODocNo,
                'ptDPSStaApv'            : tDPSStaApv,
                'ptDPSStaDoc'            : tDPSStaDoc,
                'ptSOVATInOrEx'         : tSOVATInOrEx,
                'pnSOPageCurrent'       : nPageCurrent,
                'ptDPSVATInOrEx'        : tDPSVATInOrEx
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['checksession'] == 'expire'){
                    JCNxShowMsgSessionExpired();
                }else{
                    if(aReturnData['nStaEvent'] == '1') {
                        $('#odvSODataPanelDetailPDT #odvSODataPdtTableDTTemp').html(aReturnData['tSOPdtAdvTableHtml']);
                        var aSOEndOfBill    = aReturnData['aSOEndOfBill'];
                        // JSxDPSSetFooterEndOfBill(aSOEndOfBill);
                        JCNxCloseLoading();
                    }else{
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JSvDPSLoadPdtDataTableHtml(pnPage)
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
}




// Functionality: Call Page Product Table In Add Document
// Parameters: Function Ajax Success
// Creator: 28/06/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: View
// ReturnType : View
function JSvDPSLoadPdtDataTableHtmlMonitor(pnPage){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1){
        if($("#ohdDPSRoute").val() == "dcmDPSEventAdd"){
            var tSODocNo    = "";
        }else{
            var tSODocNo    = $("#oetDPSDocNo").val();
        }

        var tSOStaApv       = $("#ohdDPSStaApv").val();
        var tSOStaDoc       = $("#ohdDPSStaDoc").val();
        var tSOVATInOrEx    = $("#ocmDPSFrmSplInfoVatInOrEx").val();
        
        //เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#otbDPSDocPdtAdvTableList .xWPdtItem").length == 0){
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
        var tSearchPdtAdvTable  = $('#oetDPSFrmFilterPdtHTML').val();
        $.ajax({
            type: "POST",
            url: "dcmSOPdtAdvanceTableLoadDataMonitor",
            data: {
                'tSelectBCH'            : $('#oetDPSFrmBchCode').val(),
                'ptSearchPdtAdvTable'   : tSearchPdtAdvTable,
                'ptSODocNo'             : tSODocNo,
                'ptSOStaApv'            : tSOStaApv,
                'ptSOStaDoc'            : tSOStaDoc,
                'ptSOVATInOrEx'         : tSOVATInOrEx,
                'pnSOPageCurrent'       : nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvSODataPanelDetailPDT #odvSODataPdtTableDTTempMonitor').html(aReturnData['tSOPdtAdvTableHtml']);
                    var aSOEndOfBill    = aReturnData['aSOEndOfBill'];
                    // JSxDPSSetFooterEndOfBill(aSOEndOfBill);
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
// Parameters: Function Ajax Success
// Creator: 02/07/2021 Off
// LastUpdate: -
// Return: View
// ReturnType : View
function JCNvDPSBrowsePdt(){
    // var tSOSplCode = $('#oetSOFrmSplCode').val();
    // if( $('#ohdSOPplCodeCst').val() != '' ){
    var tSOPplCode = $('#ohdSOPplCodeCst').val();
    // }

    //อนุญาต "ซื้อ" ที่หน่วย และ อนุญาต "ซื้อ" ที่บาร์โค๊ด
    var aWhereItem      = [];
    tPDTAlwSale         = ' AND (PPCZ.FTPdtStaAlwSale = 1 ';
    aWhereItem.push(tPDTAlwSale);

    tPDTAlwSale         = " OR ISNULL(PPCZ.FTPdtStaAlwSale,null) = null ) ";
    aWhereItem.push(tPDTAlwSale);

    tPDTAlwSale         = ' AND (PBAR.FTBarStaAlwSale = 1 ';
    aWhereItem.push(tPDTAlwSale);

    tPDTAlwSale         = " OR ISNULL(PBAR.FTBarStaAlwSale,null) = null ) ";
    aWhereItem.push(tPDTAlwSale);

    var aMulti = [];
    $.ajax({
        type: "POST",
        url: "BrowseDataPDT",
        data: {
            'Qualitysearch'   : [],
            'PriceType'       : ["Price4Cst", tSOPplCode], /*"Cost", "tCN_Cost", "Company", "1"*/
            'ShowCountRecord' : 10,
            'NextFunc'        : "FSvDPSNextFuncB4SelPDT", //FSvSOAddPdtIntoDocDTTemp
            'ReturnType'      : 'M',
            'SPL'             : ['',''],
            'BCH'             : [$('#ohdDPSBchCode').val(),$('#ohdDPSBchCode').val()],
            'SHP'             : ['',''],
            'Where'           : aWhereItem,
            'tTYPEPDT'        : '1,3,4,5,6', //สินค้าบริการ ไม่ต้อง , ค่าใช้จ่าย ไม่ต้อง
            'tSNPDT'          : '1,3,4' //สินค้าที่เป็นชุดจะไม่ต้องมัดจำ
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
}


// Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
// Parameters: Function Behind Edit In Line
// Creator: 02/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: View Table Product Doc DT Temp
// ReturnType : View
function FSvDPSEditPdtIntoTableDT(pnSeqNo, ptFieldName, ptValue, pbIsDelDTDis){
    // var nStaSession = JCNxFuncChkSessionExpired();
    // if (typeof nStaSession !== "undefined" && nStaSession == 1){
        var tSODocNo        = $("#oetDPSDocNo").val();
        var tSOBchCode      = $("#oetDPSFrmBchCode").val();
        var tSOVATInOrEx    = $('#ocmDPSFrmSplInfoVatInOrEx').val();
        $.ajax({
            type: "POST",
            url: "dcmSOEditPdtIntoDTDocTemp",
            data: {
                'tSOBchCode'    : tSOBchCode,
                'tSODocNo'      : tSODocNo,
                'tSOVATInOrEx'  : tSOVATInOrEx,
                'nSOSeqNo'      : pnSeqNo,
                'tSOFieldName'  : ptFieldName,
                'tSOValue'      : ptValue,
                'nSOIsDelDTDis' : (pbIsDelDTDis) ? '1' : '0' // 1: ลบ, 2: ไม่ลบ
            },
            cache: false,
            timeout: 0,
            success: function (oResult){

                if(oResult == 'expire'){
                    JCNxShowMsgSessionExpired();
                }else{
                    JSvDPSLoadPdtDataTableHtml();
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




// Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
// Parameters: Function Behind Edit In Line
// Creator: 02/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: View Table Product Doc DT Temp
// ReturnType : View
function FSvDPSEditPdtIntoTableDTMonitor(pnSeqNo, ptFieldName, ptValue, pbIsDelDTDis){
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1){
        var tSODocNo        = $("#oetDPSDocNo").val();
        var tSOBchCode      = $("#oetDPSFrmBchCode").val();
        var tSOVATInOrEx    = $('#ocmDPSFrmSplInfoVatInOrEx').val();
        $.ajax({
            type: "POST",
            url: "dcmSOEditPdtIntoDTDocTemp",
            data: {
                'tSOBchCode'    : tSOBchCode,
                'tSODocNo'      : tSODocNo,
                'tSOVATInOrEx'  : tSOVATInOrEx,
                'nSOSeqNo'      : pnSeqNo,
                'tSOFieldName'  : ptFieldName,
                'tSOValue'      : ptValue,
                'nSOIsDelDTDis' : (pbIsDelDTDis) ? '1' : '0' // 1: ลบ, 2: ไม่ลบ
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                JSvDPSLoadPdtDataTableHtmlMonitor();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}


// Functionality: Set Status On Click Submit Buttom
// Parameters: Event Click Save Document
// Creator: 09/07/2021 Off
// LastUpdate: -
// Return: Set Status Submit By Button In Input Hidden
// ReturnType: None
function JSxDPSSetStatusClickSubmit(pnStatus) {
    $("#ohdSOCheckSubmitByButton").val(pnStatus);
}

// Functionality: Add/Edit Document
// Parameters: Event Click Save Document
// Creator: 05/07/2021 Off
// LastUpdate: -
// Return: -
// ReturnType: None
function JSxDPSAddEditDocument(){
    // var nStaSession = JCNxFuncChkSessionExpired();
    var nStaSession = 1;
    if(typeof nStaSession !== "undefined" && nStaSession == 1){
        JSxDPSValidateFormDocument();
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
function JSxDPSValidateFormDocument(){
    if($("#ohdSOCheckClearValidate").val() != 0){
        $('#ofmSOFormAdd').validate().destroy();
    }
    $('#ofmSOFormAdd').validate({
        focusInvalid: false,
        onclick: false,
        onfocusout: false,
        onkeyup: false,
        rules: {
            oetDPSDocNo : {
                "required" : {
                    depends: function (oElement) {
                        if($("#ohdDPSRoute").val()  ==  "dcmDPSEventAdd"){
                            if($('#ocbDPSStaAutoGenCode').is(':checked')){
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
            oetSODocDate    : {"required" : true},
            oetSODocTime    : {"required" : true},
            oetSOFrmWahName : {"required" : true},
        },
        messages: {
            oetDPSDocNo      : {"required" : $('#oetDPSDocNo').attr('data-validate-required')},
            oetSODocDate    : {"required" : $('#oetSODocDate').attr('data-validate-required')},
            oetSODocTime    : {"required" : $('#oetSODocTime').attr('data-validate-required')},
            oetSOFrmWahName : {"required" : $('#oetSOFrmWahName').attr('data-validate-required')},
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
            if ($('#ohdDPSRoute').val() == 'dcmDPSEventAdd') {
                if(!$('#ocbDPSStaAutoGenCode').is(':checked')){
                    JSxSOValidateDocCodeDublicate();
                }else{
                    if($("#ohdSOCheckSubmitByButton").val() == 1){
                        JSxDPSSubmitEventByButton();
                    }
                }
            }else{
                JSxDPSSubmitEventByButton();
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
function JSxSOValidateDocCodeDublicate(){
    // JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "CheckInputGenCode",
        data: {
            'tTableName'    : 'TARTRcvDepositHD',
            'tFieldName'    : 'FTXshDocNo',
            'tCode'         : $('#oetDPSDocNo').val()
        },
        success: function (oResult) {
            var aResultData = JSON.parse(oResult);
            $("#ohdSOCheckDuplicateCode").val(aResultData["rtCode"]);

            if($("#ohdSOCheckClearValidate").val() != 1) {
                $('#ofmSOFormAdd').validate().destroy();
            }

            $.validator.addMethod('dublicateCode', function(value,element){
                if($("#ohdDPSRoute").val() == "dcmDPSEventAdd"){
                    if($('#ocbDPSStaAutoGenCode').is(':checked')) {
                        return true;
                    }else{
                        if($("#ohdSOCheckDuplicateCode").val() == 1) {
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
            $('#ofmSOFormAdd').validate({
                focusInvalid: false,
                onclick: false,
                onfocusout: false,
                onkeyup: false,
                rules: {
                    oetDPSDocNo : {"dublicateCode": {}}
                },
                messages: {
                    oetDPSDocNo : {"dublicateCode"  : $('#oetDPSDocNo').attr('data-validate-duplicate')}
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
                    if($("#ohdSOCheckSubmitByButton").val() == 1) {
                        JSxDPSSubmitEventByButton();
                    }
                }
            })

            if($("#ohdSOCheckClearValidate").val() != 1) {
                $("#ofmSOFormAdd").submit();
                $("#ohdSOCheckClearValidate").val(1);
            }

            if($("#ohdSOCheckDuplicateCode").val() == 1) {
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                var tLogFunction = 'ERROR';
                var tDisplayEvent = 'เพิ่ม/แก้ไข ใบมัดจำ';
                var tErrorStatus  = '900'
                var tHtmlError = 'Data Duplicate'
                var tLogDocNo   = $('#oetDPSDocNo').val();
                JCNxPackDataToMQLog(tHtmlError,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
            var tDocNo = $('#oetDPSDocNo').val();
            if (jqXHR.status != 404){
                var tLogFunction = 'ERROR';
                var tDisplayEvent = 'Check Data Duplicate';
                var tErrorStatus  = ''
                var tLogDocNo   = tDocNo;
                var tHtmlError = $(jqXHR.responseText);
                var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
            }else{
                //JCNxSendMQPageNotFound(jqXHR,tDocNo);
            }
        }
    });
}


// Functionality: Search Pdt In Temp
// Parameters: Function Parameter Behide NextFunc Validate
// Creator: 05/07/2021 Off
function JSvDPSSearchPdtHTML() {
    var value = $("#oetSearchPdtHTML").val().toLowerCase();
    $("#otbDPSDocPdtAdvTableList tbody tr ").filter(function () {
        tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        
    });
}

// Functionality: Validate Success And Send Ajax Add/Update Document
// Parameters: Function Parameter Behide NextFunc Validate
// Creator: 03/07/2019 Wasin(Yoshi)
// LastUpdate: 04/07/2019 Wasin(Yoshi)
// Return: Status Add/Update Document
// ReturnType: object
function JSxDPSSubmitEventByButton(ptType = ''){
    $("#ohdSOCheckDuplicateCode").val(0)
    if($("#ohdDPSRoute").val() !=  "dcmDPSEventAdd"){
        var tSODocNo    = $('#oetDPSDocNo').val();
    }
    $("#obtDPSSubmitFromDoc").attr('disabled','true');
    $(".selectpicker").removeAttr('disabled');
    $.ajax({
        type: "POST",
        url: "dcmDPSChkHavePdtForDocDTTemp",
        data: {
            'ptSODocNo': tSODocNo,
            'tDPSSesSessionID'   : $('#ohdSesSessionID').val(),
            'tSOUsrCode'        : $('#ohdSOUsrCode').val(),
            'tSOLangEdit'       : $('#ohdSOLangEdit').val(),
            'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            JCNxCloseLoading();
            var aDataReturnChkTmp   = JSON.parse(oResult);
            if (aDataReturnChkTmp['nStaReturn'] == '1'){
                $.ajax({
                    type    : "POST",
                    url     : $("#ohdDPSRoute").val(),
                    data    : $("#ofmSOFormAdd").serialize(),
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        JCNxCloseLoading();
                        var aDataReturnEvent    = JSON.parse(oResult);
                        if(aDataReturnEvent['nStaReturn'] == '1'){
                            var nSOStaCallBack      = aDataReturnEvent['nStaCallBack'];
                            var nSODocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                            var oDPSCallDataTableFile = {
                                ptElementID : 'odvShowDataTable',
                                ptBchCode   : $('#ohdDPSBchCode').val(),
                                ptDocNo     : nSODocNoCallBack,
                                ptDocKey    :'TARTRcvDepositHD',
                            }
                            JCNxUPFInsertDataFile(oDPSCallDataTableFile);
                            if(ptType == 'approve'){
                                var tDPSDocNo            = $("#oetDPSDocNo").val();
                                var tDPSBchCode          = $('#ohdDPSBchCode').val();
                                var tDPSStaApv           = $("#ohdDPSStaApv").val();
                                var tDPSSplPaymentType   = $("#ocmTQPaymentType").val();

                                $.ajax({
                                    type : "POST",
                                    url : "dcmDPSApproveDocument",
                                    data: {
                                        'ptDPSDocNo'             : tDPSDocNo,
                                        'ptDPSBchCode'           : tDPSBchCode,
                                        'ptDPSStaApv'            : tDPSStaApv,
                                        'ptDPSSplPaymentType'    : tDPSSplPaymentType
                                    },
                                    cache: false,
                                    timeout: 0,
                                    success: function(tResult){
                                        try {
                                            let oResult = JSON.parse(tResult);
                                            if (oResult.nStaEvent == "1") {
                                            JSvDPSCallPageEditDoc(tDPSDocNo);
                                                }else{
                                            FSvCMNSetMsgWarningDialog(oResult.tStaMessg);
                                            }
                                        } catch (err) {}
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                                        //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                                        if (jqXHR.status != 404){
                                            var tLogFunction = 'ERROR';
                                            var tDisplayEvent = 'อนุมัติใบมัดจำ';
                                            var tErrorStatus = jqXHR.status;
                                            var tHtmlError = $(jqXHR.responseText);
                                            var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                                            var tLogDocNo   = tDPSDocNo;
                                            JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                                        }else{
                                            //JCNxSendMQPageNotFound(jqXHR,tDPSDocNo);
                                        }
                                    }
                                });
                            }else{
                                switch(nSOStaCallBack){
                                    case '1' :
                                        JSvDPSCallPageEditDoc(nSODocNoCallBack);
                                    break;
                                    case '2' :
                                        JSvDPSCallPageAddDoc();
                                    break;
                                    case '3' :
                                        JSvDPSCallPageList();
                                    break;
                                    default :
                                        JSvDPSCallPageEditDoc(nSODocNoCallBack);
                                }
                            }
                        }else{
                            var tMessageError = aDataReturnEvent['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                        $("#obtDPSSubmitFromDoc").removeAttr("disabled");
                    },
                    error   : function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        $("#obtDPSSubmitFromDoc").removeAttr("disabled");

                        //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                        if (jqXHR.status != 404){
                            var tLogFunction = 'ERROR';
                            var tDisplayEvent = 'บันทึก/แก้ไข ใบมัดจำ';
                            var tErrorStatus = jqXHR.status;
                            var tHtmlError = $(jqXHR.responseText);
                            var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                            var tLogDocNo   = tSODocNo;
                            JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                        }else{
                            //JCNxSendMQPageNotFound(jqXHR,tSODocNo);
                        }
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
            //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
            if (jqXHR.status != 404){
                var tLogFunction = 'ERROR';
                var tDisplayEvent = 'บันทึก/แก้ไข ใบมัดจำ';
                var tErrorStatus = jqXHR.status;
                var tHtmlError = $(jqXHR.responseText);
                var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                var tLogDocNo   = tSODocNo;
                JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
            }else{
                //JCNxSendMQPageNotFound(jqXHR,tSODocNo);
            }
        }
    });
}

// Functionality: Call Page Edit Document
// Parameters: Event Btn Click Call Edit Document
// Creator: 07/07/2021 Off
// LastUpdate: -
// Return: Status Add/Update Document
// ReturnType: object
function JSvDPSCallPageEditDoc(ptDPSDocNo){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML("JSvDPSCallPageEditDoc",ptDPSDocNo);
        $.ajax({
            type: "POST",
            url: "dcmDPSPageEdit",
            data: {'ptDPSDocNo' : ptDPSDocNo},
            cache: false,
            timeout: 0,
            success: function(tResult){
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){

                    if(nDPSStaSOBrowseType == '1') {
                        $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                        $('#odvModalBodyBrowse').html(aReturnData['tSOViewPageEdit']);
                    }else{
                        $('#odvDPSContentPageDocument').html(aReturnData['tSOViewPageEdit']);
                        JCNxDPSControlObjAndBtn();
                        JSvDPSLoadPdtDataTableHtml();
                        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");
                        JCNxLayoutControll();
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'เรียกดูเอกสารใบมัดจำ';
                    var tErrorStatus = jqXHR.status;
                    var tHtmlError = $(jqXHR.responseText);
                    var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                    var tLogDocNo   = ptDPSDocNo;
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }else{
                    //JCNxSendMQPageNotFound(jqXHR,ptDPSDocNo);
                }
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Functionality: Function Control Object Button
// Parameters: Event Btn Click Call Edit Document
// Creator: 08/07/2021 Off
// LastUpdate: -
// Return: Status Add/Update Document
// ReturnType: object
function JCNxDPSControlObjAndBtn(){
    // Check สถานะอนุมัติ
    var nDPSStaDoc       = $("#ohdDPSStaDoc").val();
    var nSOStaApv       = $("#ohdDPSStaApv").val();
    var nDPSStaPaid       = $("#ohdDPSStaPaid").val();
    var tSOStaDelMQ     = $('#ohdSOStaDelMQ').val();
    var tSOStaPrcStk    = $('#ohdSOStaPrcStk').val();

    JSxDPSNavDefultDocument();

    // Title Menu Set De
    $("#oliDPSTitleAdd").hide();
    $('#oliDPSTitleDetail').hide();
    $('#oliDPSTitleAprove').hide();
    $('#oliDPSTitleConimg').hide();
    $('#oliDPSTitleEdit').show();
    $('#odvDPSBtnGrpInfo').hide();
    // Button Menu
    $("#obtDPSApproveDoc").show();
    $("#obtDPSApproveDPS").hide();
    $("#obtDPSCancelDoc").show();
    $('#obtDPSPrintDoc').show();
    $('#odvDPSBtnGrpSave').show();
    $('#odvDPSBtnGrpAddEdit').show();

    // Remove Disable
    $("#obtDPSCancelDoc").attr("disabled",false);
    $("#obtDPSApproveDoc").attr("disabled",false);
    $("#obtDPSPrintDoc").attr("disabled",false);
    $("#obtSOBrowseSupplier").attr("disabled",false);

    $(".xWConditionSearchPdt").attr("disabled",false);
    $(".ocbListItem").attr("disabled",false);
    $(".xCNBtnDateTime").attr("disabled",false);
    $(".xCNDocBrowsePdt").attr("disabled",false).removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocBrowseSO").attr("disabled", false).removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").show();
    $("#oetSOFrmSearchPdtHTML").attr("disabled", false);
    $(".xWBtnGrpSaveLeft").show();
    $(".xWBtnGrpSaveRight").show();
    $("#oliSOEditShipAddress").show();
    $("#oliSOEditTexAddress").show();

    if(nDPSStaDoc != 1){
        // Hide/Show Menu Title 
        $("#oliDPSTitleAdd").hide();
        $('#oliDPSTitleEdit').hide();
        $('#oliDPSTitleDetail').show();
        $('#oliDPSTitleAprove').hide();
        $('#oliDPSTitleConimg').hide();
        // Disabled Button
        $("#obtDPSCancelDoc").hide(); // attr("disabled",true);
        $("#obtDPSApproveDoc").hide(); // attr("disabled",true);
        // $("#obtDPSPrintDoc").hide(); 
        $("#obtSOBrowseSupplier").attr("disabled",true);
        $(".xWConditionSearchPdt").attr("disabled",true);
        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $("#ocbSOFrmInfoOthStaDocAct").attr("disabled", true);
        $('.xWDropdown').attr('disabled',true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocBrowseSO").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetSOFrmSearchPdtHTML").attr("disabled",true);
        // $("#ocmVatInOrEx").attr("disabled","disabled");
        // $("#ocmTQPaymentType").attr("disabled","disabled");
        $('#odvDPSBtnGrpSave').hide();
        // $(".xWBtnGrpSaveLeft").hide(); // attr("disabled", true);
        // $(".xWBtnGrpSaveRight").hide(); // attr("disabled", true);
        $("#oliSOEditShipAddress").hide();
        $("#oliSOEditTexAddress").hide();
        // Hide Button
        $("#obtDPSCallPageAdd").hide();    
    }

    // Check Status Appove Success


    if(nDPSStaDoc == 1 && nSOStaApv == 1 ){
        // Hide/Show Menu Title 
        $("#oliDPSTitleAdd").hide();
        $('#oliDPSTitleEdit').hide();
        $('#oliDPSTitleDetail').show();
        $('#oliDPSTitleAprove').hide();
        $('#oliDPSTitleConimg').hide();
        
        // Hide And Disabled
        $("#obtDPSCallPageAdd").hide();
        $("#obtDPSCancelDoc").hide(); // attr("disabled",true);
        $("#obtDPSApproveDoc").hide(); // attr("disabled",true);
        $("#obtSOBrowseSupplier").attr("disabled",true);
        $(".xWConditionSearchPdt").attr("disabled",true);
        $(".ocbListItem").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $("#ocbSOFrmInfoOthStaDocAct").attr("disabled", true);
        $('.xWDropdown').attr('disabled',true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocBrowseSO").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetSOFrmSearchPdtHTML").attr("disabled", false);
        $('#odvDPSBtnGrpSave').show();
        // $("#ocmVatInOrEx").attr("disabled","disabled");
        // $("#ocmTQPaymentType").attr("disabled","disabled");
        // $(".xWBtnGrpSaveLeft").hide(); // attr("disabled", true);
        // $(".xWBtnGrpSaveRight").hide(); // attr("disabled", true);
        $("#oliSOEditShipAddress").hide();
        $("#oliSOEditTexAddress").hide();
        // Show And Disabled
        $("#oliDPSTitleDetail").show();
        
        if(nDPSStaPaid != 4){
            $("#obtDPSApproveDPS").show();
        }
    }
}

// Functionality: Cancel Document PI
// Parameters: Event Btn Click Call Edit Document
// Creator: 08/07/2021 Off
// LastUpdate: -
// Return: Status Cancel Document
// ReturnType: object
function JSnDPSCancelDocument(pbIsConfirm){
    var tDPSDocNo    = $("#oetDPSDocNo").val();
    var tRefInDocNo  = $('#oetDPSRefInt').val();
    if(pbIsConfirm){
        $.ajax({
            type: "POST",
            url: "dcmDPSCancelDocument",
            data: {
                'ptDPSDocNo' : tDPSDocNo,
                'ptRefInDocNo': tRefInDocNo},
            cache: false,
            timeout: 0,
            success: function (tResult) {
                $("#odvPurchaseInviocePopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JSvDPSCallPageEditDoc(tDPSDocNo);
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'ยกเลิกใบมัดจำ';
                    var tErrorStatus = jqXHR.status;
                    var tHtmlError = $(jqXHR.responseText);
                    var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                    var tLogDocNo   = tDPSDocNo;
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }else{
                    //JCNxSendMQPageNotFound(jqXHR,tDPSDocNo);
                }
            }
        });
    }else{
        $('#odvPurchaseInviocePopupCancel').modal({backdrop:'static',keyboard:false});
        $("#odvPurchaseInviocePopupCancel").modal("show");
    }
}

// Functionality : Applove Document 
// Parameters : Event Click Buttom
// Creator : 09/07/2021 Off
// LastUpdate: -
// Return : Status Applove Document
// Return Type : -
function JSxDPSApproveDocument(pbIsConfirm){
    if(pbIsConfirm){
        $("#odvSOModalAppoveDoc").modal("hide");
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        var tDPSDocNo            = $("#oetDPSDocNo").val();
        var tDPSBchCode          = $('#ohdDPSBchCode').val();
        var tDPSStaApv           = $("#ohdDPSStaApv").val();
        var tDPSSplPaymentType   = $("#ocmTQPaymentType").val();

        var tHNNumber =  $('#oetSOFrmCstHNNumber').val();
              var tMerCode =  $('#oetSOFrmMerCode').val();
              var tShpCode =  $('#oetSOFrmShpCode').val();
              var tPosCode =  $('#oetSOFrmPosCode').val();
              var tWahCode =  $('#oetSOFrmWahCode').val();
              var tCheckIteminTable = $('#otbDPSDocPdtAdvTableList .xWPdtItem').length;
          
              if(tCheckIteminTable>0){
              if(tHNNumber!='' && tWahCode!=''){
                JSxDPSSubmitEventByButton('approve');
              }else{
                    if(tHNNumber==''){
                        FSvCMNSetMsgWarningDialog($('#oetSOFrmCstHNNumber').attr('lavudate-label'));
                    }else if(tWahCode==''){
                        FSvCMNSetMsgWarningDialog($('#oetSOFrmWahName').attr('data-validate-required'));
                    }
              }
            }
        // $.ajax({
        //     type : "POST",
        //     url : "dcmDPSApproveDocument",
        //     data: {
        //         'ptDPSDocNo'             : tDPSDocNo,
        //         'ptDPSBchCode'           : tDPSBchCode,
        //         'ptDPSStaApv'            : tDPSStaApv,
        //         'ptDPSSplPaymentType'    : tDPSSplPaymentType
        //     },
        //     cache: false,
        //     timeout: 0,
        //     success: function(tResult){
        //         try {
        //             let oResult = JSON.parse(tResult);
        //             if (oResult.nStaEvent == "1") {
        //              JSvDPSCallPageEditDoc(tDPSDocNo);
        //                 }else{
        //              FSvCMNSetMsgWarningDialog(oResult.tStaMessg);
        //             }
        //         } catch (err) {}
        //     },
        //     error: function (jqXHR, textStatus, errorThrown) {
        //         JCNxResponseError(jqXHR, textStatus, errorThrown);
        //     }
        // });
    }else{
        $('#odvSOModalAppoveDoc').modal({backdrop:'static',keyboard:false});
        $("#odvSOModalAppoveDoc").modal("show");
    }
}

// Functionality : Applove Paid Document 
// Parameters : Event Click Buttom
// Creator : 14/12/2021 Off
// LastUpdate: -
// Return : Status Paid Document
// Return Type : -
function JSxDPSDepositDocument(pbIsConfirm){
    if(pbIsConfirm){
        $("#odvDPSModalPaidDoc").modal("hide");
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        var tDPSDocNo            = $("#oetDPSDocNo").val();
        var tDPSBchCode          = $('#ohdDPSBchCode').val();
        var tDPSStaApv           = $("#ohdDPSStaApv").val();
        var tDPSSplPaymentType   = $("#ocmTQPaymentType").val();
        $.ajax({
            type : "POST",
            url : "dcmDPSApprovePaidDocument",
            data: {
                'ptDPSDocNo'             : tDPSDocNo,
                'ptDPSBchCode'           : tDPSBchCode,
                'ptDPSStaApv'            : tDPSStaApv,
                'ptDPSSplPaymentType'    : tDPSSplPaymentType
            },
            cache: false,
            timeout: 0,
            success: function(tResult){
                try {
                    let oResult = JSON.parse(tResult);
                    if (oResult.nStaEvent == "1") {
                     JSvDPSCallPageEditDoc(tDPSDocNo);
                        }else{
                     FSvCMNSetMsgWarningDialog(oResult.tStaMessg);
                    }
                } catch (err) {}
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                if (jqXHR.status != 404){
                    var tLogFunction = 'ERROR';
                    var tDisplayEvent = 'คืนมัดจำ';
                    var tErrorStatus = jqXHR.status;
                    var tHtmlError = $(jqXHR.responseText);
                    var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                    var tLogDocNo   = tDPSDocNo;
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }else{
                    //JCNxSendMQPageNotFound(jqXHR,tDPSDocNo);
                }
            }
        });
    }else{
        $('#odvDPSModalPaidDoc').modal({backdrop:'static',keyboard:false});
        $("#odvDPSModalPaidDoc").modal("show");
    }
}

// Functionality : Call Data Subscript Document
// Parameters : Event Click Buttom
// Creator : 09/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return : Status Applove Document
// Return Type : -
function JSoSOCallSubscribeMQ() {
    // RabbitMQ
    /*===========================================================================*/
    // Document variable
    var tLangCode   = $("#ohdSOLangEdit").val();
    var tUsrBchCode = $("#oetDPSFrmBchCode").val();
    var tUsrApv     = $("#ohdSOApvCodeUsrLogin").val();
    var tDocNo      = $("#oetDPSDocNo").val();
    var tPrefix     = "RESPPI";
    var tStaApv     = $("#ohdDPSStaApv").val();
    var tStaDelMQ   = $("#ohdSOStaDelMQ").val();
    var tQName      = tPrefix + "_" + tDocNo + "_" + tUsrApv;

    // MQ Message Config
    // var poDocConfig = {
    //     tLangCode   : tLangCode,
    //     tUsrBchCode : tUsrBchCode,
    //     tUsrApv     : tUsrApv,
    //     tDocNo      : tDocNo,
    //     tPrefix     : tPrefix,
    //     tStaDelMQ   : tStaDelMQ,
    //     tStaApv     : tStaApv,
    //     tQName      : tQName
    // };

    // RabbitMQ STOMP Config
    // var poMqConfig = {
    //     host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
    //     username: oSTOMMQConfig.user,
    //     password: oSTOMMQConfig.password,
    //     vHost: oSTOMMQConfig.vhost
    // };

    // Update Status For Delete Qname Parameter
    // var poUpdateStaDelQnameParams = {
    //     ptDocTableName      : "TARTSoHD",
    //     ptDocFieldDocNo     : "FTXshDocNo",
    //     ptDocFieldStaApv    : "FTXphStaPrcStk",
    //     ptDocFieldStaDelMQ  : "FTXphStaDelMQ",
    //     ptDocStaDelMQ       : tStaDelMQ,
    //     ptDocNo             : tDocNo
    // };

    // Callback Page Control(function)
    // var poCallback = {
    //     tCallPageEdit: "JSvDPSCallPageEditDoc",
    //     tCallPageList: "JSvDPSCallPageList"
    // };

    // Check Show Progress %
    // FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
}

// Functionality : Call Data Subscript Document
// Parameters : Event Click Buttom
// Creator : 09/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return : Status Applove Document
// Return Type :
function JSvSODOCFilterPdtInTableTemp(){
    JCNxOpenLoading();
    JSvDPSLoadPdtDataTableHtml();
}

// Functionality : Function Check Data Search And Add In Tabel DT Temp
// Parameters : Event Click Buttom
// Creator : 30/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return : 
// Return Type : Filter
function JSxSOChkConditionSearchAndAddPdt(){
    var tSODataSearchAndAdd =   $("#oetSOFrmSearchAndAddPdtHTML").val();
    if(tSODataSearchAndAdd != undefined && tSODataSearchAndAdd != ""){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var tSODataSearchAndAdd = $("#oetSOFrmSearchAndAddPdtHTML").val();
            var tSODocNo            = $('#oetDPSDocNo').val();
            var tSOBchCode          = $("#oetDPSFrmBchCode").val();
            var tSOStaReAddPdt      = $("#ocmSOFrmInfoOthReAddPdt").val();
            $.ajax({
                type: "POST",
                url: "dcmSOSerachAndAddPdtIntoTbl",
                data:{
                    'ptSOBchCode'           : tSOBchCode,
                    'ptSODocNo'             : tSODocNo,
                    'ptSODataSearchAndAdd'  : tSODataSearchAndAdd,
                    'ptSOStaReAddPdt'       : tSOStaReAddPdt,
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
function JSvDPSClickPageList(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld    = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld    = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvDPSCallPageDataTable(nPageCurrent);
}


//Next page
function JSvSOPDTDocDTTempClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWPagePIPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWPagePIPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JCNxOpenLoading();
        JSvDPSLoadPdtDataTableHtml(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}


//Next page
function JSvSOPDTDocDTTempClickPageMonitor(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWPagePIPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWPagePIPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JCNxOpenLoading();
        JSvDPSLoadPdtDataTableHtmlMonitor(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Functionality: Call Page Edit Document
// Parameters: Event Btn Click Call Edit Document
// Creator: 04/07/2019 Wasin(Yoshi)
// LastUpdate: -
// Return: Status Add/Update Document
// ReturnType: object
function JSvDPSCallPageEditDocOnMonitor(ptSODocNo){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML("JSvDPSCallPageEditDocOnMonitor",ptSODocNo);
        $.ajax({
            type: "POST",
            url: "dcmSOPageEditMonitor",
            data: {'ptSODocNo' : ptSODocNo},
            cache: false,
            timeout: 0,
            success: function(tResult){
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    if(nDPSStaSOBrowseType == '1') {
                        $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                        $('#odvModalBodyBrowse').html(aReturnData['tSOViewPageEdit']);
                    }else{
                        $('#odvDPSContentPageDocument').html(aReturnData['tSOViewPageEdit']);
                        JCNxDPSControlObjAndBtn();
                        JSvDPSLoadPdtDataTableHtmlMonitor();
                        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");
                            // Title Menu Set De
                        $("#oliDPSTitleAdd").hide();
                        $('#oliDPSTitleDetail').hide();
                        $('#oliDPSTitleAprove').show();
                        $('#oliDPSTitleConimg').hide();
                        $('#oliDPSTitleEdit').hide();
                        JCNxLayoutControll();
                    }
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
