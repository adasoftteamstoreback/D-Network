var nSOStaSOBrowseType     = $("#oetSOStaBrowse").val();
var tSOCallSOBackOption    = $("#oetSOCallBackOption").val();
var tSOSesSessionID        = $("#ohdSesSessionID").val();

$("document").ready(function(){
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    $('#obtSOSubmitFromDoc').attr('disabled',false);

    if(typeof(nSOStaSOBrowseType) != 'undefined' && nSOStaSOBrowseType == 0){
        // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliSOTitle').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvSODCallPageList();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        
        JSxSODNavDefultDocument();
        JSvSODCallPageList();
    }else{

        JSxSODNavDefultDocument();

    }
    $("#oetSODSearchSoSCAN").val('');
    $("#oetSODSearchSoSCAN").text('');

    $('.selectpicker').selectpicker();
});




// กดสแกนเพื่อรับของ
$('#obtSODSerchForCRV').unbind().click(function(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "dcmSODataCheckCRV",
            data: {
                tDocno  : $("#oetSODSearchSoSCAN").val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1'){  
                    $('.modal-backdrop').remove();
                    var aGetallitems = aReturnData['aItems']['aItems'];
                    $('#ocmCRVShow').find('option').remove();
                    if(aGetallitems.length > 1){
                        for(i=0;i<aGetallitems.length;i++){
                            $('#ocmCRVShow').append( $('<option data-bchcode="'+aGetallitems[i]['FTBchCode']+'" value = "'+aGetallitems[i]['FTXshRefDocNo']+'">'+aGetallitems[i]['FTXshRefDocNo']+'</option>') );
                        }
                        $("#odvSODToCRVDataModal").modal('show');
                    }else{
                        var tBchCode = aReturnData['aItems']['aItems'][0]['FTBchCode'];
                        var tDocNo = aReturnData['aItems']['aItems'][0]['FTXshRefDocNo'];
                        // alert(tDocNo);
                        JSvSODJumptoCRVPage(tBchCode,tDocNo,'2' )
                    }
                }else{
                    FSvCMNSetMsgErrorDialog('ไม่พบเลขที่ใบฝากของ');  
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
    $("#oetSODSearchSoSCAN").val('');
    $("#oetSODSearchSoSCAN").text('');
});

// ยินยันข้อมูล
function JSxCRVSubmit(pbIsConfirm) {
    try {
        $("#odvSODToCRVDataModal").modal('hide');
        var tDocNo = $("#ocmCRVShow").val();
        var tBchCode = $("#ocmCRVShow").find(":selected").data('bchcode');
        JSvSODJumptoCRVPage(tBchCode,tDocNo,'2' )
    } catch (err) {
        console.log("JSxDBRApproveDocument Error: ", err);
    }
}


$('#oetSODSearchSoSCAN').on('keydown',function(){
    if(event.keyCode == 13){
        $.ajax({
            type: "POST",
            url: "dcmSODataCheckCRV",
            data: {
                tDocno  : $("#oetSODSearchSoSCAN").val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1'){  
                    $('.modal-backdrop').remove();
                    var aGetallitems = aReturnData['aItems']['aItems'];
                    $('#ocmCRVShow').find('option').remove();
                    if(aGetallitems.length > 1){
                        for(i=0;i<aGetallitems.length;i++){
                            $('#ocmCRVShow').append( $('<option data-bchcode="'+aGetallitems[i]['FTBchCode']+'" value = "'+aGetallitems[i]['FTXshRefDocNo']+'">'+aGetallitems[i]['FTXshRefDocNo']+'</option>') );
                        }
                        $("#odvSODToCRVDataModal").modal('show');
                    }else{
                        var tBchCode = aReturnData['aItems']['aItems'][0]['FTBchCode'];
                        var tDocNo = aReturnData['aItems']['aItems'][0]['FTXshRefDocNo'];
                        JSvSODJumptoCRVPage(tBchCode,tDocNo,'2' )
                    }
                }else{
                    FSvCMNSetMsgErrorDialog('ไม่พบเลขที่ใบฝากของ');  
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        $("#oetSODSearchSoSCAN").val('');
        $("#oetSODSearchSoSCAN").text('');
    }
});

// Functionality : สร้างใบจัด 
    // Parameters : Event Click Buttom
    // Creator : 07/04/2022 Off
    // LastUpdate: -
    // Return : Status สร้างใบจัด
    // Return Type : -
    function JSxSOFillUserDocument(pbIsConfirm){
        var tUserName = $("#oetSODFillUser").val();
        var tUserPass = JCNtAES128EncryptData($("#oetSODFillPass").val(), tKey, tIV);
        if(pbIsConfirm){
            $.ajax({
            type: "POST",
            url: "dcmSOCheckUserLogin",
            data: {
                tUserName  : tUserName,
                tUserPass  : tUserPass,
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1'){  
                    $.ajax({
                    type: "POST",
                    url: "dcmSODataCheckCRV",
                    data: {
                        tDocno  : $("#oetSODocFill").val(),
                    },
                    cache: false,
                    timeout: 0,
                    success: function (oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){  
                            $('.modal-backdrop').remove();
                            $("#odvChkLoginModal").modal("hide");
                            var aGetallitems = aReturnData['aItems']['aItems'];
                            $('#ocmCRVShow').find('option').remove();
                            if(aGetallitems.length > 1){
                                for(i=0;i<aGetallitems.length;i++){
                                    $('#ocmCRVShow').append( $('<option data-bchcode="'+aGetallitems[i]['FTBchCode']+'" value = "'+aGetallitems[i]['FTXshRefDocNo']+'">'+aGetallitems[i]['FTXshRefDocNo']+'</option>') );
                                }
                                $("#odvSODToCRVDataModal").modal('show');
                            }else{
                                var tBchCode = aReturnData['aItems']['aItems'][0]['FTBchCode'];
                                var tDocNo = aReturnData['aItems']['aItems'][0]['FTXshRefDocNo'];
                                JSvSODJumptoCRVPage(tBchCode,tDocNo,'2' )
                            }
                        }else{
                            FSvCMNSetMsgErrorDialog('ไม่พบเลขที่ใบฝากของ');  
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
                }else{
                    FSvCMNSetMsgErrorDialog('ผู้ใช้ไม่มีสิทธิ์รับของ');  
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        }
        $("#oetSODFillUser").val('');
        $("#oetSODFillUser").text('');
        $("#oetSODFillPass").val('');
        $("#oetSODFillPass").text('');
    }


// Set Defult Nav Menu Document
function JSxSODNavDefultDocument(){
    if(typeof(nSOStaSOBrowseType) != 'undefined' && nSOStaSOBrowseType == 0) {
        // Title Label Hide/Show
        $('#oliSOTitleFranChise').hide();
        $('#oliSOTitleAdd').hide();
        $('#oliSOTitleEdit').hide();
        $('#oliSOTitleDetail').hide();
        $('#oliSOTitleAprove').hide();
        $('#oliSOTitleConimg').hide();
        // Button Hide/Show
        $('#odvSOBtnGrpAddEdit').hide();
        $('#odvSOBtnGrpInfo').show();
        $('#obtSOCallPageAdd').show();
        $("#SoSearch2").hide();
    }else{
        $('#odvModalBody #odvSOMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliSONavBrowse').css('padding', '2px');
        $('#odvModalBody #odvSOBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNSOBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNSOBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

// Call Page List
function JSvSODCallPageList(){
    $.ajax({
        type: "GET",
        url: "dcmSODataFormSearchList",
        cache: false,
        timeout: 0,
        success: function (tResult){
            $("#odvSOContentPageDocument").html(tResult);
            JSxSODNavDefultDocument();
            JSvSODCallPageDataTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });  
}

// Get Data Advanced Search
function JSoSODGetAdvanceSearchData(){
    var oAdvanceSearchData  = {
        tSearchAll          : '',
        tSearchSO           : $("#oetSODSearchSoNo").val(),
        tSearchSODate       : $("#oetSODAdvSearcSODocDateFrom").val(),
        tSearchBchCodeFrom  : '',
        tSearchBchCodeTo    : '',
        tSearchDocDateFrom  : $("#oetSODAdvSearcSODocDateFrom").val(),
        tSearchDocDateTo    : $("#oetSOAdvSearcDocDateTo").val(),
        tSearchStaDoc       : '',
        tSearchStaApprove   : '',
        tSearchStaPrcStk    : $("#ocmSOAdvSearchStaPrcStk").val(),
        tSearchStaSale      : $("#ocmSOAdvSearchStaSale").val(),
        tSearchRefDoc       : $("#oetSODSearchSoRefNo").val(),
        tSearchName         : $("#oetSODSearchName").val()
    };
    return oAdvanceSearchData;
}

// Call Page List
function JSvSODCallPageDataTable(pnPage){
    JCNxOpenLoading();

    var oAdvanceSearch  = JSoSODGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if(typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "dcmSODataDataTable",
        data: {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent,
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxSODNavDefultDocument();
                $('#ostSODataTableDocument').html(aReturnData['tSOViewDataTableList']);
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

// Call Page List
function JSvSOCallPageGenPODataTable(pnPage,ptCondit){
    JCNxOpenLoading();
    var oAdvanceSearch  = JSoSOGenGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    var nConditon = ptCondit;
    if(nConditon == '4'){
        oAdvanceSearch.tSearchStaGenSO = '4';
    }
    if(typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "dcmSODataTableGenPO",
        data: {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent,
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JCNxSOControlGenSOBtn();
                $('#ostSODataTableDocument').html(aReturnData['tSOViewDataTableList']);
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

// Get Data Advanced Search
function JSoSOGenGetAdvanceSearchData(){
    var oAdvanceSearchData  = {
        tSearchAll          : $("#oetSOGenSearchAllDocument").val(),
        tSearchBchCodeFrom  : $("#oetSOGenAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo    : $("#oetSOGenAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom  : $("#oetSOGenAdvSearcDocDateFrom").val(),
        tSearchDocDateTo    : $("#oetSOGenAdvSearcDocDateTo").val(),
        tSearchStaDoc       : $("#ocmSOGenAdvSearchStaDoc").val(),
        tSearchStaApprove   : $("#ocmSOGenAdvSearchStaApprove").val(),
        tSearchStaPrcStk    : $("#ocmSOAdvSearchStaPrcStk").val(),
        tSearchStaGenSO     : $("#ocmSOAdvSearchStaGenSO").val(),
        tSearchStaSale      : $("#ocmSOAdvSearchStaSale").val()
    };
    return oAdvanceSearchData;
}

// Function Chack And Show Button Delete All
function JSxSOShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliSOBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliSOBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliSOBtnDeleteAll").addClass("disabled");
        }
    }
}

// Insert Text In Modal Delete
function JSxSOTextinModal() {
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
        $("#odvSOModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
        $("#odvSOModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
    }
}

// Function Chack Value LocalStorage
function JStSOFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

// เปลี่ยนหน้า Pagenation Document HD List 
function JSvSOClickPage(ptPage) {
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
        JSvSODCallPageDataTable(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// หน้าจอแก้ไข
function JSvSOCallPageEditDoc(ptSODocNo, ptSOCstCode){
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JStCMMGetPanalLangSystemHTML("JSvSOCallPageEditDoc",ptSODocNo);
        $.ajax({
            type: "POST",
            url: "dcmSOPageEdit",
            data: {
                    'ptSODocNo' : ptSODocNo,
                    'ptCstCode' : ptSOCstCode
                  },
            cache: false,
            timeout: 0,
            success: function(tResult){
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    if(nSOStaSOBrowseType == '1') {
                        $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                        $('#odvModalBodyBrowse').html(aReturnData['tSOViewPageEdit']);
                    }else{
                        $('#odvSOContentPageDocument').html(aReturnData['tSOViewPageEdit']);
                        JCNxSOControlObjAndBtn();
                        JSvSOLoadPdtDataTableHtml();
                        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");
                        JCNxLayoutControll();
                        if (aReturnData['tCshOrCrd'] == 1) {
                            $('.xCNPanel_CreditTerm').hide();
                        }else if(aReturnData['tCshOrCrd'] == 2){
                            $('.xCNPanel_CreditTerm').show();
                        }else{
                            $('.xCNPanel_CreditTerm').hide();
                        }
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown){
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Jump ไปหน้าใบสั่งขาย
function JSvSODJumptoSOPage(ptSODocNo, ptSOCstCode){
    
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JStCMMGetPanalLangSystemHTML("JSvSOCallPageEditDoc",ptSODocNo);
        $.ajax({
            type    : "GET",
            url     : "dcmSO/0/0",
            data    : {'ptTypeJump' : '1',
                        'tDocNo' : ptSODocNo },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
                // JSvSOCallPageEditDoc(ptSODocNo);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Jump ไปหน้าใบจัด
function JSvSODJumptoPCKPage(ptPAMDocNo, ptType,ptBackTo){
    JCNxOpenLoading();
    clearInterval(oSetSaleOrderDataInterval);
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JStCMMGetPanalLangSystemHTML("JSvPAMCallPageEditDoc",ptPAMDocNo);
        $.ajax({
            type    : "POST",
            url     : "docPAM/0/0",
            data    : {'ptTypeJump' : '1',
                        'tDocNo' : ptPAMDocNo,
                        'tType'     : ptType,
                        'tBackTo'   : ptBackTo},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Jump ไปหน้าเลขที่ใบขาย
function JSvSODJumptoABBPage(ptSODocNo, ptType,ptBackTo){
    clearInterval(oSetSaleOrderDataInterval);
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JStCMMGetPanalLangSystemHTML("JSvABBCallPageEditDoc",ptSODocNo);
        $.ajax({
            type    : "POST",
            url     : "docABB/0/0",
            data    : {'ptTypeJump' : '1',
                        'tDocNo'    : ptSODocNo,
                        'tType'     : ptType,
                        'tBackTo'   : ptBackTo},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// กดสแกนเพื่อรับของ

function JSvSODJumptoCRVPageBefore(ptSCstCode,ptType ){
    clearInterval(oSetSaleOrderDataInterval);
    var nStaSession = JCNxFuncChkSessionExpired();
    $("#oetSODocFill").val(ptSCstCode);
    var tUsrCode = $("#ohdUsrCodeChk").val();
    if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "dcmSODataCheckCRV",
            data: {
                tDocno    : ptSCstCode,
                tUsrCode  : tUsrCode,
                ptType    : ptType,
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1'){  
                    $('.modal-backdrop').remove();
                    var aGetallitems = aReturnData['aItems']['aItems'];
                    var tBypass = aReturnData['aItems']['tByPass'];
                    $('#ocmCRVShow').find('option').remove();
                    if(aGetallitems.length > 1){
                        for(i=0;i<aGetallitems.length;i++){
                            $('#ocmCRVShow').append( $('<option data-bchcode="'+aGetallitems[i]['FTBchCode']+'" value = "'+aGetallitems[i]['FTXshRefDocNo']+'">'+aGetallitems[i]['FTXshRefDocNo']+'</option>') );
                        }
                        if(ptType == '1'){
                            $("#oetSODFillUser").val('');
                            $("#oetSODFillUser").text('');
                            $("#oetSODFillPass").val('');
                            $("#oetSODFillPass").text('');
                            if(tBypass == '1'){
                                $("#odvSODToCRVDataModal").modal('show');
                            }else{
                                $('#odvChkLoginModal').modal({backdrop:'static',keyboard:false});
                                $("#odvChkLoginModal").modal("show");
                            }
                        }else{
                            $("#odvSODToCRVDataModal").modal('show');
                        }
                    }else{
                        var tBchCode = aReturnData['aItems']['aItems'][0]['FTBchCode'];
                        var tDocNo = aReturnData['aItems']['aItems'][0]['FTXshRefDocNo'];
                        if(ptType == '1'){
                            $("#oetSODFillUser").val('');
                            $("#oetSODFillUser").text('');
                            $("#oetSODFillPass").val('');
                            $("#oetSODFillPass").text('');
                            if(tBypass == '1'){
                                JSvSODJumptoCRVPage(tBchCode,tDocNo,'2' )
                            }else{
                                $('#odvChkLoginModal').modal({backdrop:'static',keyboard:false});
                                $("#odvChkLoginModal").modal("show");
                            }
                        }else{
                            JSvSODJumptoCRVPage(tBchCode,tDocNo,'2' )
                        }
                    }
                }else{
                    FSvCMNSetMsgErrorDialog('ไม่พบเลขที่ใบฝากของ');  
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

// Jump ไปหน้าใบรับของลูกค้า
function JSvSODJumptoCRVPage(ptSCstCode,ptSDocNo,ptType ){
    clearInterval(oSetSaleOrderDataInterval);
    if(ptType == '1'){
        $('#odvChkLoginModal').modal({backdrop:'static',keyboard:false});
        $("#odvChkLoginModal").modal("show");
    }else{
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JStCMMGetPanalLangSystemHTML("JSvCRVCallPageEditDoc",ptSDocNo);
            $.ajax({
                type    : "POST",
                url     : "docCRV/0/0",
                data    : {'ptTypeJump' : '1',
                            'tDocNo' : ptSDocNo,
                            'tBchCode' : ptSCstCode},
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    $(window).scrollTop(0);
                    $('.odvMainContent').html(tResult);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
}


// Jump ไปหน้าใยกำกับภาษีเต็มรูป
function JSvSODJumptoTAXPage(ptSDocNo, ptSCstCode){
    clearInterval(oSetSaleOrderDataInterval);
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JStCMMGetPanalLangSystemHTML("JSvTAXCallPageEditDoc",ptSDocNo);
        $.ajax({
            type    : "POST",
            url     : "dcmTXIN/0/0",
            data    : {'ptTypeJump' : '1',
                        'tDocNo' : ptSDocNo,
                        'tBchCode' : ptSCstCode},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Jump ไปจองช่องฝาก
function JSvSODJumptoCreateBook(ptSODocNo, ptSOCstCode ,ptType,ptBackTo){
    clearInterval(oSetSaleOrderDataInterval);
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JStCMMGetPanalLangSystemHTML("JSvDBDCallPageEditDoc",ptSODocNo);
        $.ajax({
            type    : "POST",
            url     : "docDBR/0/0",
            data    : {'ptTypeJump' : '1',
                        'tDocNo' : ptSODocNo,
                        'tBchCode' : ptSOCstCode,
                        'tType' : ptType,
                        'tBackTo' : ptBackTo
                    },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Control ปุ่มใบสั่งขายเฟรนไชส์
function JCNxSOControlGenSOBtn(){

    $("#SoSearch1").hide();
    $("#SoSearch2").show();
    // Hide/Show Menu Title 
    $('#odvSOBtnGrpAddEdit').show();
    $('#obtSOCallBackPage').show();
    $("#oliSOTitleAdd").hide();
    $('#oliSOTitleFranChise').show();
    $("#obtSOCreatePCK").hide();
    $("#odvSOMngTableList").hide();
    $('#oliSOTitleEdit').hide();
    $('#oliSOTitleDetail').hide();
    $('#oliSOTitleAprove').hide();
    $('#oliSOTitleConimg').hide();
    $("#obtSOCancelDoc").hide(); 
    $("#obtSOApproveDoc").hide();
    $("#obtSOPrintDoc").hide(); 
    $("#obtSOBrowseSupplier").attr("disabled",true);
    $(".xWConditionSearchPdt").attr("disabled",true);
    $(".ocbListItem").attr("disabled", true);
    $(".xWControlBtnDateTime").attr("disabled", true);
    $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").hide();
    $("#oetSOFrmSearchPdtHTML").attr("disabled",true);
    $('#odvSOBtnGrpSave').hide();
    $("#oliSOEditShipAddress").hide();
    $("#oliSOEditTexAddress").hide();
    $("#obtSOCallPageAdd").hide();
    
}

// Control ปุ่ม
function JCNxSOControlObjAndBtn(){
    var nSOStaDoc       = $("#ohdSOStaDoc").val();
    var nSOStaApv       = $("#ohdSOStaApv").val();

    JSxSODNavDefultDocument();

    $("#oliSOTitleAdd").hide();
    $('#oliSOTitleFranChise').hide();
    $('#oliSOTitleDetail').hide();
    $('#oliSOTitleAprove').hide();
    $('#oliSOTitleConimg').hide();
    $('#oliSOTitleEdit').show();
    $('#odvSOBtnGrpInfo').hide();
    $("#obtSOApproveDoc").show();
    $("#obtSOCancelDoc").show();
    $('#obtSOPrintDoc').show();
    $('#odvSOBtnGrpSave').show();
    $('#odvSOBtnGrpAddEdit').show();

    // Remove Disable
    $("#obtSOCancelDoc").attr("disabled",false);
    $("#obtSOApproveDoc").attr("disabled",false);
    $("#obtSOPrintDoc").attr("disabled",false);
    $("#obtSOBrowseSupplier").attr("disabled",false);
    $(".xWConditionSearchPdt").attr("disabled",false);
    $(".ocbListItem").attr("disabled",false);
    $(".xWControlBtnDateTime").attr("disabled",false);
    $(".xCNDocBrowsePdt").attr("disabled",false).removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").show();
    $("#oetSOFrmSearchPdtHTML").attr("disabled", false);
    $(".xWBtnGrpSaveLeft").show();
    $(".xWBtnGrpSaveRight").show();
    $("#oliSOEditShipAddress").show();
    $("#oliSOEditTexAddress").show();

    if(nSOStaDoc != 1){
        // Hide/Show Menu Title 
        $("#oliSOTitleAdd").hide();
        $('#oliSOTitleFranChise').hide();
        $('#oliSOTitleEdit').hide();
        $('#oliSOTitleDetail').show();
        $('#oliSOTitleAprove').hide();
        $('#oliSOTitleConimg').hide();
        $("#obtSOCancelDoc").hide(); 
        $("#obtSOApproveDoc").hide();
        $("#obtSOPrintDoc").show(); 
        $("#obtSOBrowseSupplier").attr("disabled",true);
        $(".xWConditionSearchPdt").attr("disabled",true);
        $(".ocbListItem").attr("disabled", true);
        $(".xWControlBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetSOFrmSearchPdtHTML").attr("disabled",true);
        $('#odvSOBtnGrpSave').show();
        $("#oliSOEditShipAddress").hide();
        $("#oliSOEditTexAddress").hide();
        $("#obtSOCallPageAdd").hide();

        //controll from 
        $(".xCNControllForm").attr("readonly", true);
        $(".xCNDateTimePicker").attr("readonly", true);
        $(".selectpicker").attr("disabled", true);
        $("#odvSOMngDelPdtInTableDT").hide();
        $("#oetSOInsertBarcode").hide();
        $("#obtSODocBrowsePdt").hide();
        $("#obtSOBrowseAddr").attr("disabled", true);
    }

    if(nSOStaDoc == 1 && nSOStaApv == 1 ){
        // Hide/Show Menu Title 
        $("#oliSOTitleAdd").hide();
        $('#oliSOTitleFranChise').hide();
        $('#oliSOTitleEdit').hide();
        $('#oliSOTitleDetail').show();
        $('#oliSOTitleAprove').hide();
        $('#oliSOTitleConimg').hide();
        $("#obtSOCallPageAdd").hide();
        $("#obtSOCancelDoc").hide(); 
        $("#obtSOApproveDoc").hide();
        
        $("#obtSOBrowseSupplier").attr("disabled",true);
        $(".xWConditionSearchPdt").attr("disabled",true);
        $(".ocbListItem").attr("disabled", true);
        $(".xWControlBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetSOFrmSearchPdtHTML").attr("disabled", false);
        $('#odvSOBtnGrpSave').show();
        $("#oliSOEditShipAddress").hide();
        $("#oliSOEditTexAddress").hide();

        // Show And Disabled
        $("#oliSOTitleDetail").show();
        $("#odvSOMngDelPdtInTableDT").hide();
        $("#oetSOInsertBarcode").hide();
        $("#obtSODocBrowsePdt").hide();

        //controll from 
        $(".xCNControllForm").attr("readonly", true);
        $(".xCNDateTimePicker").attr("readonly", true);
        $(".selectpicker").attr("disabled", true);
        $("#odvSOMngDelPdtInTableDT").hide();
        $("#oetSOInsertBarcode").hide();
        $("#obtSODocBrowsePdt").hide();
        $("#obtSOBrowseAddr").attr("disabled", true);
    }
}

// ยกเลิกเอกสาร
function JSnSOCancelDocument(pbIsConfirm){
    var tSODocNo    = $("#oetSODocNo").val();
    var ptSOCstCode = $("#oetSOFrmCstHNNumber").val();
    if(pbIsConfirm){
        $.ajax({
            type    : "POST",
            url     : "dcmSOCancelDocument",
            data    : {
                'ptSODocNo'     : tSODocNo,
                'ptSOBCHCode'   : $('#oetSOFrmBchCode').val()
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                $("#odvPurchaseInviocePopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JSvSOCallPageEditDoc(tSODocNo, ptSOCstCode);
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

// อนุมัติเอกสาร
function JSxSOApproveDocument(pbIsConfirm){

    //เช็คก่อนว่าเอกสารนี้มีใบจัดค้างอยู่ไหม
    // if($('#ohdSOStaPrcDoc').val() == 7 ||  $('#ohdSOStaPrcDoc').val() == null || $('#ohdSOStaPrcDoc').val() == 1 || $('#ohdSOStaPrcDoc').val() == ''){
        if(pbIsConfirm){
            $("#odvSOModalAppoveDoc").modal("hide");
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            JSxSOSubmitEventByButton('approve');
        }else{
            $('#odvSOModalAppoveDoc').modal({backdrop:'static',keyboard:false});
            $("#odvSOModalAppoveDoc").modal("show");
        }   
    // }else{
    //     var tMSG = "ไม่สามารถอนุมัติได้ มีใบจัดสินค้า ที่สร้างจากเอกสารนี้ ค้างอนุมัติ";
    //     FSvCMNSetMsgWarningDialog(tMSG);
    //     return;
    // }
}

// Subscript
function JSoSOCallSubscribeMQ() {
    // RabbitMQ
    /*===========================================================================*/
    // Document variable
    var tLangCode   = $("#ohdSOLangEdit").val();
    var tUsrBchCode = $("#oetSOFrmBchCode").val();
    var tUsrApv     = $("#ohdSOApvCodeUsrLogin").val();
    var tDocNo      = $("#oetSODocNo").val();
    var tPrefix     = "RESPPI";
    var tStaApv     = $("#ohdSOStaApv").val();
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
    //     tCallPageEdit: "JSvSOCallPageEditDoc",
    //     tCallPageList: "JSvSODCallPageList"
    // };

    // Check Show Progress %
    // FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
}

// Call Data Subscript Document
function JSvSODOCFilterPdtInTableTemp(){
    JCNxOpenLoading();
    JSvSOLoadPdtDataTableHtml();
}

// Function Check Data Search And Add In Tabel DT Temp
function JSxSOChkConditionSearchAndAddPdt(){
    var tSODataSearchAndAdd =   $("#oetSOFrmSearchAndAddPdtHTML").val();
    if(tSODataSearchAndAdd != undefined && tSODataSearchAndAdd != ""){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var tSODataSearchAndAdd = $("#oetSOFrmSearchAndAddPdtHTML").val();
            var tSODocNo            = $('#oetSODocNo').val();
            var tSOBchCode          = $("#oetSOFrmBchCode").val();
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

// Function Check Data Search And Add In Tabel DT Temp
function JSvSOClickPageList(ptPage){
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
    JSvSODCallPageDataTable(nPageCurrent);
}

// Next page
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
        JSvSOLoadPdtDataTableHtml(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Next page
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
        JSvSOLoadPdtDataTableHtmlMonitor(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}