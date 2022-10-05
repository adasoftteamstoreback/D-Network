var nSOStaSOBrowseType      = $("#oetSOStaBrowse").val();
var tSOCallSOBackOption     = $("#oetSOCallBackOption").val();
var tSOSesSessionID         = $("#ohdSesSessionID").val();
var tJumpDocNo              = $("#oetDBRJumpDocNo").val();
var tJumpType               = $("#oetCheckJumpType").val();
var tJumpBackto             = $("#oetCheckBackTo").val();


$("document").ready(function(){ 
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    $('#obtSOSubmitFromDoc').attr('disabled',false);

    if(typeof(nSOStaSOBrowseType) != 'undefined' && nSOStaSOBrowseType == 0){
        // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliSOTitle').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                if($('#oetCheckJumpStatus').val() != '1'){
                    JSvSOCallPageList();
                }else{
                        $.ajax({
                            type    : "GET",
                            url     : "dcmSOData/0/0",
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
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Add Page
        $('#obtSOCallPageAdd').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            $('#odvSOSelectDocTypePopup').modal('show');
            $('#obtnSOConfirmSelectDocType').one('click', function(){
                $('#odvSOSelectDocTypePopup').modal('hide');
                var nDoctype = $('#odvSOSelectDocTypePopup input[name=orbSOSelectDocType]:checked').val();
                if(nDoctype == '1'){
                    JSvSOCallPageAddDoc();
                }else if(nDoctype == '2'){
                    JSvSOCallPageGenPODataTable();
                }
            });
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Call Back Page
        $('#obtSOCallBackPage').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                 //กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
                 if(localStorage.tCheckBackStage == 'PageMangeDocOrderBCH' || localStorage.tCheckBackStage == 'PageMangeDocOrderBCHHQ'){
                    JSxBackStageToMangeDocOrderBCH();
                }else{ //กลับสู่หน้า List
                    if($('#oetCheckJumpStatus').val() != '1'){
                        JSvSOCallPageList();
                    }else{
                        if(tJumpType == 'RTBook'){
                            $.ajax({
                                type    : "POST",
                                url     : "docDBR/0/0",
                                data    : {'ptTypeJump' : '1',
                                            'tDocNo' : tJumpBackto,
                                            'tBchCode' : $("#oetSOFrmBchCode").val(),
                                            'tType' : '2',
                                            'tBackTo' : tJumpDocNo
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
                        $.ajax({
                            type    : "GET",
                            url     : "dcmSOData/0/0",
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
                    }
                    }
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Cancel Document
        $('#obtSOCancelDoc').unbind().click(function(){
            let tCancelFlag = 0;
            $("#SoDocrefList tr").each( function () { 
                if($(this).attr('data-refkey') == 'CSTPICK'){
                    tCancelFlag = 1;
                    return false;
                }
            });
            if(tCancelFlag == 0){
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                    JSnSOCancelDocument(false);
                }else{
                    JCNxShowMsgSessionExpired();
                }              
            }else{
                FSvCMNSetMsgErrorDialog('ไม่สามารถยกเลิกเอกสารที่มีการอ้างอิงใบจัดสินค้าได้');
            }
        });

        // Event Click Appove Document
        $('#obtSOApproveDoc').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tCheckIteminTable = $('#otbSODocPdtAdvTableList .xWPdtItem').length;
                if(tCheckIteminTable>0){
                    JSxSOSetStatusClickSubmit(2);
                    JSxSOApproveDocument(false);
                }else{
                    FSvCMNSetMsgWarningDialog($('#ohdSOValidatePdt').val());
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // บันทึกข้อมูล
        $('#obtSOSubmitFromDoc').unbind().click(function(){
            var tHNNumber =  $('#oetSOFrmCstHNNumber').val();
            var tMerCode =  $('#oetSOFrmMerCode').val();
            var tShpCode =  $('#oetSOFrmShpCode').val();
            var tPosCode =  $('#oetSOFrmPosCode').val();
            var tWahCode =  $('#oetSOFrmWahCode').val();
            var tCheckIteminTable = $('#otbSODocPdtAdvTableList .xWPdtItem').length;
            var tCheckCarInTable  = $('#oetSOFrmCarCode').val();
            
            if(tCheckIteminTable > 0){
                if(tHNNumber!='' && tWahCode!=''){
                    JSxSOSetStatusClickSubmit(1);
                    $('#obtSOSubmitDocument').click();
                }else{
                    if(tHNNumber==''){
                        FSvCMNSetMsgWarningDialog($('#oetSOFrmCstHNNumber').attr('lavudate-label'));
                    }else if(tWahCode==''){
                        FSvCMNSetMsgWarningDialog($('#oetSOFrmWahName').attr('data-validate-required'));
                    }

                    // if (tCheckCarInTable == '') {
                    //     FSvCMNSetMsgWarningDialog($('#ohdSOValidateCarCode').val());
                    // }
                }
            }else{
                FSvCMNSetMsgWarningDialog($('#ohdSOValidatePdt').val());
            }
        });
        
        JSxSONavDefultDocument();
            if($('#oetCheckJumpStatus').val() != '1'){
                JSvSOCallPageList();
            }else{
                JSvSOCallPageEditDoc(tJumpDocNo);
            }
        
        
    }else{
        // Event Modal Call Back Before List
        $('#oahSOBrowseCallBack').unbind().click(function (){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JCNxBrowseData(tSOCallSOBackOption);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Modal Call Back Previous
        $('#oliSOBrowsePrevious').unbind().click(function (){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JCNxBrowseData(tSOCallSOBackOption);
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtSOBrowseSubmit').unbind().click(function () {
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSxSOSetStatusClickSubmit(1);
                $('#obtSOSubmitDocument').click();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        JSxSONavDefultDocument();
        JSvSOCallPageAddDoc();
    }
});

//กดปุ่มย้อนกลับ ถ้ามาจาก "หน้าจอจัดการใบสั่งสินค้าจากสาขา" เวลาย้อนกลับต้องกลับไปหน้าจอเดิม
function JSxBackStageToMangeDocOrderBCH(){
    if(localStorage.tCheckBackStage == 'PageMangeDocOrderBCH'){
        var tRoute = 'docMngDocPreOrdB/0/0';
    }else{
        var tRoute = 'docMngDocPreOrdB/0/2';
    }
    
    $.ajax({
        type    : "GET",
        url     : tRoute,
        cache   : false,
        timeout : 5000,
        success : function (tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);

            //เก็บเอาไว้ว่า มาจากหน้าจอจัดการใบสั่งสินค้าจากสาขา
            localStorage.tCheckBackStage = '';
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Set Defult Nav Menu Document
function JSxSONavDefultDocument(){
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
function JSvSOCallPageList(){
    $.ajax({
        type: "GET",
        url: "dcmSOFormSearchList",
        cache: false,
        timeout: 0,
        success: function (tResult){
            $("#odvSOContentPageDocument").html(tResult);
            JSxSONavDefultDocument();
            JSvSOCallPageDataTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });  
}

// Get Data Advanced Search
function JSoSOGetAdvanceSearchData(){
    var oAdvanceSearchData  = {
        tSearchAll          : $("#oetSOSearchAllDocument").val(),
        tSearchBchCodeFrom  : $("#oetSOAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo    : $("#oetSOAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom  : $("#oetSOAdvSearcDocDateFrom").val(),
        tSearchDocDateTo    : $("#oetSOAdvSearcDocDateTo").val(),
        tSearchStaDoc       : $("#ocmSOAdvSearchStaDoc").val(),
        tSearchStaApprove   : $("#ocmSOAdvSearchStaApprove").val(),
        tSearchStaPrcStk    : $("#ocmSOAdvSearchStaPrcStk").val(),
        tSearchStaSale      : $("#ocmSOAdvSearchStaSale").val()
    };
    return oAdvanceSearchData;
}

// Call Page List
function JSvSOCallPageDataTable(pnPage){
    JCNxOpenLoading();
    var oAdvanceSearch  = JSoSOGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if(typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "dcmSODataTable",
        data: {
            oAdvanceSearch  : oAdvanceSearch,
            nPageCurrent    : nPageCurrent,
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxSONavDefultDocument();
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
        JSvSOCallPageDataTable(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// ลบข้อมูลตัวเดียว
function JSoSODelDocSingle(ptCurrentPage, ptSODocNo){
    var nStaSession = JCNxFuncChkSessionExpired();    
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        if(typeof(ptSODocNo) != undefined && ptSODocNo != ""){
            var tTextConfrimDelSingle   = $('#oetTextComfirmDeleteSingle').val()+"&nbsp"+ptSODocNo+"&nbsp"+$('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvSOModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvSOModalDelDocSingle').modal('show');
            $('#odvSOModalDelDocSingle #osmConfirmDelSingle').unbind().click(function(){
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "dcmSOEventDelete",
                    data: {'tDataDocNo' : ptSODocNo},
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1') {
                            $('#odvSOModalDelDocSingle').modal('hide');
                            $('#odvSOModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function () {
                                JSvSOCallPageDataTable(ptCurrentPage);
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

// ลบข้อมูลหลายตัว
function JSoSODelDocMultiple(){
    var aDataDelMultiple    = $('#odvSOModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
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
            url: "dcmSOEventDelete",
            data: {'tDataDocNo' : aNewIdDelete},
            cache: false,
            timeout: 0,
            success: function (oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    setTimeout(function () {
                        $('#odvSOModalDelDocMultiple').modal('hide');
                        $('#odvSOModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                        $('#odvSOModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                        $('.modal-backdrop').remove();
                        localStorage.removeItem('LocalItemData');
                        JSvSOCallPageList();
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

// หน้าเพิ่มข้อมูล
function JSvSOCallPageAddDoc(ptCstcode = '',ptDocref = '',ptBchCodeRef = '',pdDocDate = ''){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "dcmSOPageAdd",
        cache: false,
        timeout: 0,
        success: function (oResult) {
            var aReturnData = JSON.parse(oResult);
            if(aReturnData['nStaEvent'] == '1') {
                if (nSOStaSOBrowseType == '1') {
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                    $('#odvModalBodyBrowse').html(aReturnData['tSOViewPageAdd']);
                } else {
                    // Hide Title Menu And Button
                    $('#oliSOTitleEdit').hide();
                    $('#oliSOTitleDetail').hide();
                    $("#obtSOApproveDoc").hide();
                    $("#obtSOCancelDoc").hide();
                    $('#obtSOPrintDoc').hide();
                    $('#odvSOBtnGrpInfo').hide();
                    // Show Title Menu And Button
                    $('#oliSOTitleAdd').show();
                    $('#oliSOTitleFranChise').hide();
                    $('#odvSOBtnGrpSave').show();
                    $('#odvSOBtnGrpAddEdit').show();
                    $('#oliSOTitleAprove').hide();
                    $('#oliSOTitleConimg').hide();

                    // Remove Disable Button Add 
                    $(".xWBtnGrpSaveLeft").attr("disabled",false);
                    $(".xWBtnGrpSaveRight").attr("disabled",false);

                    $('#odvSOContentPageDocument').html(aReturnData['tSOViewPageAdd']);
                }
                if(ptCstcode != ''){
                    JSxSOSetConditionFromGenSO(ptCstcode,ptBchCodeRef,ptDocref,pdDocDate);
                    // JSxSOSetConditionFromGenSOGetProduct(ptDocref,ptBchCodeRef);
                }
                JSvSOLoadPdtDataTableHtml();
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

// หน้าจอสินค้า
function JSvSOLoadPdtDataTableHtml(pnPage){
        if($("#ohdSORoute").val() == "dcmSOEventAdd"){
            var tSODocNo    = "";
        }else{
            var tSODocNo    = $("#oetSODocNo").val();
        }
        
        var tSOStaApv       = $("#ohdSOStaApv").val();
        var tSOStaDoc       = $("#ohdSOStaDoc").val();
        var tSOVATInOrEx    = $("#ocmSOFrmSplInfoVatInOrEx").val();
        
        //เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#otbSODocPdtAdvTableList .xWPdtItem").length == 0){
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
        var tSearchPdtAdvTable  = $('#oetSOFrmFilterPdtHTML').val();

        if(tSOStaApv==2){
            $('#obtSODocBrowsePdt').hide();
            $('#obtSOPrintDoc').hide();
            $('#obtSOCancelDoc').hide();
            $('#obtSOApproveDoc').hide();
            $('#odvSOBtnGrpSave').hide();
        }

        $.ajax({
            type: "POST",
            url: "dcmSOPdtAdvanceTableLoadData",
            data: {
                'tSelectBCH'        : $('#oetSOFrmBchCode').val(),
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
                if(aReturnData['checksession'] == 'expire'){
                    JCNxShowMsgSessionExpired();
                }else{
                    if(aReturnData['nStaEvent'] == '1') {
                        $('#odvSODataPanelDetailPDT #odvSODataPdtTableDTTemp').html(aReturnData['tSOPdtAdvTableHtml']);
                        var aSOEndOfBill    = aReturnData['aSOEndOfBill'];
                        JSxSOSetFooterEndOfBill(aSOEndOfBill);
                        JCNxCloseLoading();
                    }else{
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JSvSOLoadPdtDataTableHtml(pnPage)
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
}

// Call Page Product Table In Add Document
function JSvSOLoadPdtDataTableHtmlMonitor(pnPage){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1){
        if($("#ohdSORoute").val() == "dcmSOEventAdd"){
            var tSODocNo    = "";
        }else{
            var tSODocNo    = $("#oetSODocNo").val();
        }

        var tSOStaApv       = $("#ohdSOStaApv").val();
        var tSOStaDoc       = $("#ohdSOStaDoc").val();
        var tSOVATInOrEx    = $("#ocmSOFrmSplInfoVatInOrEx").val();
        
        //เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#otbSODocPdtAdvTableList .xWPdtItem").length == 0){
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
        var tSearchPdtAdvTable  = $('#oetSOFrmFilterPdtHTML').val();
        $.ajax({
            type: "POST",
            url: "dcmSOPdtAdvanceTableLoadDataMonitor",
            data: {
                'tSelectBCH'            : $('#oetSOFrmBchCode').val(),
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
                    // JSxSOSetFooterEndOfBill(aSOEndOfBill);
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

// Add Product Into Table Document DT Temp
function JCNvSOBrowsePdt(){
    var tSOSplCode = $('#oetSOFrmSplCode').val();
    if($('#ohdSOPplCodeCst').val()!=''){
        var tSOPplCode =$('#ohdSOPplCodeCst').val();
    }else{
        var tSOPplCode =$('#ohdSOPplCodeBch').val();
    }
    // if(typeof(tSOSplCode) !== undefined && tSOSplCode !== ''){
        var aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                Qualitysearch: [
                    "CODEPDT",
                    "NAMEPDT",
                    "BARCODE",
                    "SUP",
                    "FromToBCH",
                    "Merchant",
                    "PDTLOGSEQ"
                ],
                'PriceType' : ['Price4Cst',tSOPplCode],
                SelectTier: ["Barcode"],
                ShowCountRecord: 10,
                NextFunc: "FSvSONextFuncB4SelPDT", //FSvSOAddPdtIntoDocDTTemp
                ReturnType: "M",
                SPL: [$("#oetSOFrmSplCode").val(),$("#oetSOFrmSplCode").val()],
                BCH: [$("#oetSOFrmBchCode").val(),$("#oetSOFrmBchCode").val()],
                MCH: [$("#oetSOFrmMerCode").val(),$("#oetSOFrmMerCode").val()],
                SHP: [$("#oetSOFrmShpCode").val(), $("#oetSOFrmShpCode").val()],
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

// เพิ่มสินค้าจาก ลง Table ฝั่ง Client
function FSvSOEditPdtIntoTableDT(pnSeqNo, ptFieldName, ptValue, pbIsDelDTDis){
    var tSODocNo        = $("#oetSODocNo").val();
    var tSOBchCode      = $("#oetSOFrmBchCode").val();
    var tSOVATInOrEx    = $('#ocmSOFrmSplInfoVatInOrEx').val();
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
                JSvSOLoadPdtDataTableHtml();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// เพิ่มสินค้าจาก ลง Table ฝั่ง Client
function FSvSOEditPdtIntoTableDTMonitor(pnSeqNo, ptFieldName, ptValue, pbIsDelDTDis){
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1){
        var tSODocNo        = $("#oetSODocNo").val();
        var tSOBchCode      = $("#oetSOFrmBchCode").val();
        var tSOVATInOrEx    = $('#ocmSOFrmSplInfoVatInOrEx').val();
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
                JSvSOLoadPdtDataTableHtmlMonitor();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

// Set Status On Click Submit Buttom
function JSxSOSetStatusClickSubmit(pnStatus) {
    $("#ohdSOCheckSubmitByButton").val(pnStatus);
}

// Add/Edit Document
function JSxSOAddEditDocument(){
    JSxSOValidateFormDocument();
}

// Validate From Add Or Update Document
function JSxSOValidateFormDocument(){
    if($("#ohdSOCheckClearValidate").val() != 0){
        $('#ofmSOFormAdd').validate().destroy();
    }

    $('#ofmSOFormAdd').validate({
        focusInvalid    : false,
        onclick         : false,
        onfocusout      : false,
        onkeyup         : false,
        rules           : {
            oetSODocNo  : {
                "required" : {
                    depends: function (oElement) {
                        if($("#ohdSORoute").val()  ==  "dcmSOEventAdd"){
                            if($('#ocbSOStaAutoGenCode').is(':checked')){
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
            oetSODocNo      : {"required" : $('#oetSODocNo').attr('data-validate-required')},
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
            if(!$('#ocbSOStaAutoGenCode').is(':checked')){
                JSxSOValidateDocCodeDublicate();
            }else{
                if($("#ohdSOCheckSubmitByButton").val() == 1){
                    JSxSOSubmitEventByButton();
                }
            }
        },
    });
}

// เช็คว่า โค๊ด ซ้ำไหม
function JSxSOValidateDocCodeDublicate(){
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "CheckInputGenCode",
        data: {
            'tTableName'    : 'TARTSoHD',
            'tFieldName'    : 'FTXshDocNo',
            'tCode'         : $('#oetSODocNo').val()
        },
        success: function (oResult) {
            var aResultData = JSON.parse(oResult);
            $("#ohdSOCheckDuplicateCode").val(aResultData["rtCode"]);

            if($("#ohdSOCheckClearValidate").val() != 1) {
                $('#ofmSOFormAdd').validate().destroy();
            }

            $.validator.addMethod('dublicateCode', function(value,element){
                if($("#ohdSORoute").val() == "dcmSOEventAdd"){
                    if($('#ocbSOStaAutoGenCode').is(':checked')) {
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
                    oetSODocNo : {"dublicateCode": {}}
                },
                messages: {
                    oetSODocNo : {"dublicateCode"  : $('#oetSODocNo').attr('data-validate-duplicate')}
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
                        JSxSOSubmitEventByButton();
                    }
                }
            })

            if($("#ohdSOCheckClearValidate").val() != 1) {
                $("#ofmSOFormAdd").submit();
                $("#ohdSOCheckClearValidate").val(1);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// กดบันทึก
function JSxSOSubmitEventByButton(ptType = ''){
    if($("#ohdSORoute").val() !=  "dcmSOEventAdd"){
        var tSODocNo    = $('#oetSODocNo').val();
    }

    var tBchCode = $('#oetSOFrmBchCode').val();
    $(".selectpicker").removeAttr("disabled", true);
    $('#obtSOSubmitFromDoc').attr('disabled',true);

    $.ajax({
        type: "POST",
        url: "dcmSOChkHavePdtForDocDTTemp",
        data: {
            'ptSODocNo'         : tSODocNo,
            'tSOSesSessionID'   : $('#ohdSesSessionID').val(),
            'tSOUsrCode'        : $('#ohdSOUsrCode').val(),
            'tSOLangEdit'       : $('#ohdSOLangEdit').val(),
            'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
        },
        cache: false,
        timeout: 0,
        success: function (oResult){
            var aDataReturnChkTmp   = JSON.parse(oResult);
            if (aDataReturnChkTmp['nStaReturn'] == '1'){
                $.ajax({
                    type    : "POST",
                    url     : $("#ohdSORoute").val(),
                    data    : $("#ofmSOFormAdd").serialize(),
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        var aDataReturnEvent    = JSON.parse(oResult);
                        $('#obtSOSubmitFromDoc').attr('disabled',false);

                        if(aDataReturnEvent['nStaReturn'] == '1'){
                            var nSOStaCallBack      = aDataReturnEvent['nStaCallBack'];
                            var nSODocNoCallBack    = aDataReturnEvent['tCodeReturn'];
                            var tSOCstCode          = aDataReturnEvent['tCstCode'];

                            var oSOCallDataTableFile = {
                                ptElementID : 'odvSOShowDataTable',
                                ptBchCode   : tBchCode,
                                ptDocNo     : nSODocNoCallBack,
                                ptDocKey    :'TARTSoHD',
                            }
                            JCNxUPFInsertDataFile(oSOCallDataTableFile);
                            
                            if($('#ohdSOPage').val()==1){
                                if(ptType == 'approve'){
                                    var tSODocNo            = $("#oetSODocNo").val();
                                    var tSOBchCode          = $('#oetSOFrmBchCode').val();
                                    var tSOStaApv           = $("#ohdSOStaApv").val();
                                    var tSOSplPaymentType   = $("#ocmSOFrmSplInfoPaymentType").val();
                                    var ptSOCstCode         = $("#oetSOFrmCstHNNumber").val();
                                    $.ajax({
                                        type    : "POST",
                                        url     : "dcmSOApproveDocument",
                                        data    : {
                                            'ptSODocNo'             : tSODocNo,
                                            'ptSOBchCode'           : tSOBchCode,
                                            'ptSOStaApv'            : tSOStaApv,
                                            'ptSOSplPaymentType'    : tSOSplPaymentType
                                        },
                                        cache: false,
                                        timeout: 0,
                                        success: function(tResult){
                                            let oResult = JSON.parse(tResult);
                                            if (oResult.nStaEvent == "1") {
                                                JSvSOCallPageEditDoc(tSODocNo, ptSOCstCode);
                                            }else{
                                                FSvCMNSetMsgWarningDialog(oResult.tStaMessg);
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                                        }
                                    });
                                }else{
                                    switch(nSOStaCallBack){
                                        case '1' :
                                            JSvSOCallPageEditDoc(nSODocNoCallBack, tSOCstCode);
                                        break;
                                        case '2' :
                                            JSvSOCallPageAddDoc();
                                        break;
                                        case '3' :
                                            JSvSOCallPageList();
                                        break;
                                        default :
                                            JSvSOCallPageEditDoc(nSODocNoCallBack, tSOCstCode);
                                    }
                                }
                            }else{
                                JSvSOCallPageEditDocOnMonitor(nSODocNoCallBack);
                            }
                        }else{
                            var tMessageError = aDataReturnEvent['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error   : function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
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

    JSxSONavDefultDocument();

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
        $("#obtSOCreatePCK").show();

        
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
    //     tCallPageList: "JSvSOCallPageList"
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
    JSvSOCallPageDataTable(nPageCurrent);
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

// Call Page Edit Document
function JSvSOCallPageEditDocOnMonitor(ptSODocNo){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML("JSvSOCallPageEditDocOnMonitor",ptSODocNo);
        $.ajax({
            type: "POST",
            url: "dcmSOPageEditMonitor",
            data: {'ptSODocNo' : ptSODocNo},
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
                        JSvSOLoadPdtDataTableHtmlMonitor();
                        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");
                            // Title Menu Set De
                        $("#oliSOTitleAdd").hide();
                        $('#oliSOTitleFranChise').hide();
                        $('#oliSOTitleDetail').hide();
                        $('#oliSOTitleAprove').show();
                        $('#oliSOTitleConimg').hide();
                        $('#oliSOTitleEdit').hide();
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
