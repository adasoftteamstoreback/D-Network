<script>

    $("document").ready(function () {
        localStorage.removeItem("LocalItemData");
        JSxCheckPinMenuClose(); 
        JSvMNPCallPageList();

        JSxMNGNavDefult('showpage_list');
    });

    //Control เมนู
    function JSxMNGNavDefult(ptType){
        if(ptType == 'showpage_list'){
            $("#oliMNPTitle_Manage").hide();
            $('#odvMNPBtnGrpInfo').show();
            $('#odvMNPBtnGrpAddEdit').hide();
            $('.xCNBTNSaveDoc').hide();

            $('#obtMNPCreateDocRef').hide(); //ปิดปุ่ม
            $('#obtMNPApproveDoc').hide(); //ปิดปุ่ม
            $('#obtMNPCallPageAdd').show(); 
        }else if(ptType == 'showpage_add'){
            $("#oliMNPTitle_Manage").show();
            $('#odvMNPBtnGrpInfo').hide();
            $('#odvMNPBtnGrpAddEdit').show();
            $('#obtMNPCreateDoc').hide();
            $('.xCNBTNSaveDoc').show();
        }else if(ptType == 'showpage_edit'){
            $("#oliMNPTitle_Manage").show();
            $('#odvMNPBtnGrpInfo').hide();
            $('#odvMNPBtnGrpAddEdit').show();
            $('#obtMNPCreateDoc').show();
            $('.xCNBTNSaveDoc').show();
        }

        //ซ่อนปุ่ม
        $('#obtMNPApproveDoc').hide();
        $('#obtMNPGenFileAgain').hide();
        $('#obtMNPExportDoc').hide();
        $('#obtMNPCreateDocRef').hide();
    }

    //โหลด List 
    function JSvMNPCallPageList(){
        JCNxOpenLoading();
        $.ajax({
            type    : "GET",
            url     : "docMnpDocPOSearchList",
            data    : {},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvMNPContentPageDocument").html(tResult);
                JSxMNGNavDefult('showpage_list');

                //โหลด
                JSxCheckPinMenuClose(); 
                JSxMNPLoadTableImportDoc(1);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    ////////////////////////////////////// สร้างเอกสารจากการนำเข้าไฟล์ ///////////////////////////////////

    //ค้นหาขั้นสูง
    function JSoMNPPOGetAdvanceSearchData(){
        var oAdvanceSearchData  = {
            tSearchAll          : $("#oetSearchAll").val(),
            tSearchBchCodeFrom  : $("#oetMNPPOAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo    : $("#oetMNPPOAdvSearchBchCodeTo").val(),
            tSearchSplFrom      : $("#oetSplCodeFrom").val(),
            tSearchSplTo        : $("#oetSplCodeTo").val(),
            tSearchStaDoc       : $("#ocmMNPPOAdvSearchStaDoc").val(),
        };
        return oAdvanceSearchData;
    }

    //โหลดข้อมูลตาราง => สร้างเอกสารจากการนำเข้าไฟล์ Excel
    function JSxMNPLoadTableImportDoc(pnPage){
        var oAdvanceSearch  = JSoMNPPOGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        if(typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }

        //ซ่อนปุ่ม
        $('#obtMNPApproveDoc').hide();
        $('#obtMNPGenFileAgain').hide();
        $('#obtMNPExportDoc').hide();
        $('#obtMNPCreateDocRef').hide();
        $('#obtMNPCallPageAdd').show(); 
        
        $.ajax({
            type    : "POST",
            url     : "docMnpDocPOTableImport",
            data    : {
                oAdvanceSearch  : oAdvanceSearch,
                nPageCurrent    : nPageCurrent
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {

                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostContentMNPImport').html(aReturnData['tViewDataTable']);
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กด Next Page
    function JSvMNPClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWPageMNPPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWPageMNPPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSxMNPLoadTableImportDoc(nPageCurrent);
    }

  
    // Event Click Button Add Page
    $('#obtMNPCallPageAdd').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvMNPCallPageAddDoc();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // เข้าหน้าแบบ เพิ่มข้อมูล
    function JSvMNPCallPageAddDoc(){
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docMnpDocPOPageAdd",
            cache   : false,
            timeout : 0,
            success : function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSxMNGNavDefult('showpage_add');
                    $('#odvMNPContentPageDocument').html(aReturnData['tViewPageAdd']);
                    JSvMNPLoadPdtDataTableHtml();
                    JCNxLayoutControll();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Item DT
    function JSvMNPLoadPdtDataTableHtml(){
        JCNxCloseLoading();

        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){

            if($("#ohdMNPRoute").val() == "docMNPEventAdd"){
                var tMNPDocNo    = "";
            }else{
                var tMNPDocNo    = $('#oetMGTPODocNo').val();
            }

            $.ajax({
                type    : "POST",
                url     : "docMnpDocPOTableDTTemp",
                data    : {
                    'ptMNPDocNo' : tMNPDocNo,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    var aReturnData = JSON.parse(oResult);
                    if(aReturnData['nStaEvent'] == '1') {
                        $('#odvMNPDataPdtTableDTTemp').html(aReturnData['tMNPPdtAdvTableHtml']);
                        
                        JSxControlInputWhenPDTEmpty();
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


    function JSxControlInputWhenPDTEmpty(){
        var nItemInTable = $('#otbMNPDocPdtAdvTableList .xWPdtItem').length;
        if(nItemInTable <= 0){
            $('#oefMNPFileImportExcel').val('');
            $('#oetMNPFileNameImport').val('');
            $('#oetMGTBCHNameTo').val('');
            $('#oetMGTBCHCodeTo').val('');
            $('#oetMGTSPLCodeTo').val('');
            $('#oetMGTSPLNameTo').val('');
        }
    }

    // เช็คก่อนกดปุ่ม บันทึก
    $('#obtMNPSubmitFromDoc').unbind().click(function(){
        var tCheckIteminTable   = $('#otbMNPDocPdtAdvTableList .xWPdtItem').length;
        if(tCheckIteminTable > 0 && parseInt($('.xCNShowCountBCHList').text()) > 0 ){
            $('#obtMNPSubmitDocument').click();
        }else{
            FSvCMNSetMsgWarningDialog('<?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPPDTEmpty')?>');
        }
    });

    // อีเวนส์เพิ่มข้อมูล
    function JSxMNPAddEditDocument(){
        $.ajax({
            type    : "POST",
            url     : $("#ohdMNPRoute").val(),
            data    : $("#ofmMNPFormAdd").serialize()+"&pnQTYBch="+$('.xCNShowCountBCHList').text()+"&pnQTYPdt="+$('.xCNShowCountPDTList').text(),
            cache   : false,
            timeout : 0,
            success : function(oResult){
                console.log(oResult);

                var aDataReturnEvent    = JSON.parse(oResult);
                if(aDataReturnEvent['nStaReturn'] == '1'){
                    var nStaCallBack      = aDataReturnEvent['nStaCallBack'];
                    var nDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                    switch(nStaCallBack){
                        case '1' :
                            JSvMNPCallPageEdit(nDocNoCallBack);
                        break;
                        case '2' :
                            JSvMNPCallPageAddDoc();
                        break;
                        case '3' :
                            JSvMNPCallPageList();
                        break;
                        default :
                            JSvMNPCallPageEdit(nDocNoCallBack);
                    }
                }else{
                    var tMessageError = aDataReturnEvent['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
                
    }

    //เข้าหน้าแบบ แก้ไข
    function JSvMNPCallPageEdit(ptDocumentNumber){
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docMnpDocPOPageEdit",
            data    : { 'ptDocumentNumber' : ptDocumentNumber },
            cache   : false,
            timeout : 0,
            success : function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSxMNGNavDefult('showpage_edit');
                    $('#odvMNPContentPageDocument').html(aReturnData['tViewPageAdd']);
                    JSvMNPLoadPdtDataTableHtml();
                    JCNxLayoutControll();

                    JSxControlAproveOrCancle()
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ยกเลิกเอกสาร
    function JSxMNPDocumentCancle(pbIsConfirm){
        var tDocNo    = $("#oetMGTPODocNo").val();
        if(pbIsConfirm){
            $.ajax({
                type    : "POST",
                url     : "docMnpDocPOCancel",
                data    : {
                    'tDocNo'        : tDocNo 
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    $("#odvMNPPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if(aReturnData['rtCode'] == '1'){
                        JSvMNPCallPageEdit(tDocNo);
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
            $('#odvMNPPopupCancel').modal({backdrop:'static',keyboard:false});
            $("#odvMNPPopupCancel").modal("show");
        }
    }

    //Control ระดับเอกสาร
    function JSxControlAproveOrCancle(){
        if($('#ohdMNPStaDoc').val() == 3){ //เอกสารยกเลิก
            $('#obtMNPCreateDoc').hide();
            $('.xCNBTNSaveDoc').hide();
        }
    }

    //ยืนยันการสร้างเอกสารใบสั่งซื้อ
    function JSxMNPCreateDocument(pbIsConfirm){
        var tDocNo          = $("#oetMGTPODocNo").val();
        var aItemDoc        = [];
        var tConcatDocNoRef = '';
        if(tDocNo == '' || tDocNo == undefined){
            //ยืนยันแบบหลายตัว
            $(".xCNCheckbox_WaitConfirm:checked").each(function() {
            var tDocNoRef    = $(this).parent().parent().parent().attr('data-docnoref');
            tConcatDocNoRef += ',' + tDocNoRef;
            aItemDoc.push(tDocNoRef);
            });
            $('#odvMNPModalCreateDocNo').modal('show');
            $('#odvMNPModalCreateDocNo #ospModalCreateDocNo').html('ยืนยันการสร้างเอกสารใบสั่งซื้อผู้จำหน่าย' + '<br>' + '<strong>' + 'อ้างอิงเอกสารหมายเลขใบเตรียม : ' + tConcatDocNoRef.substring(1) + '</strong>');
        }else{
            //ยืนยันแบบตัวเดียว
            tConcatDocNoRef = ','+tDocNo;
            aItemDoc.push(tDocNo);
            $('#odvMNPModalCreateDocNo #ospModalCreateDocNo').html('ยืนยันการสร้างเอกสารใบสั่งซื้อผู้จำหน่าย' + '<br>' + '<strong>' + 'อ้างอิงเอกสารหมายเลขใบเตรียม : ' + tConcatDocNoRef.substring(1) + '</strong>');
        }

        if(pbIsConfirm){
            JCNxOpenLoading();

            $.ajax({
                type    : "POST",
                url     : "docMnpDocPOCreateDoc",
                data    : {
                    'aItemDoc' : aItemDoc 
                },
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    // console.log(oResult);
                    $("#odvMNPModalCreateDocNo").modal("hide");
                    $('.modal-backdrop').remove();

                    setTimeout(function(){ 
                        JSvMNPCallPageList();
                    }, 3000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            $('#odvMNPModalCreateDocNo').modal({backdrop:'static',keyboard:false});
            $("#odvMNPModalCreateDocNo").modal("show");
        }
    }

    //อนุมัติเอกสารใบสั่งซื้อ
    function JSxMNPAproveDocRef(){
        var aItemDoc        = [];
        var tConcatDocNoRef = '';
        $(".xCNCheckbox_WaitAprove:checked").each(function() {
           var tDocNoRef    = $(this).parent().parent().parent().attr('data-docnoref');
           tConcatDocNoRef += ',' + tDocNoRef;
           aItemDoc.push(tDocNoRef);
        });

        $('#odvMGPPopupApv').modal('show');

        //กดยืนยัน
        $('#odvMGPPopupApv .xCNConfirmApprove').unbind().click(function(){
            $.ajax({
                type    : "POST",
                url     : "docMnpDocPOAproveDoc",
                data    : {
                    aItemDoc : aItemDoc
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {
                    JCNxOpenLoading();
                    
                    // console.log(oResult);
                    setTimeout(function(){ 
                        JSvMNPCallPageList();
                    }, 5000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    }

    //สร้างไฟล์อีกครั้ง
    function JSxMNPGenFileAgain(){
        JCNxOpenLoading();
        var aItemDoc        = [];
        var tConcatDocNoRef = '';
        $(".xCNCheckbox_WaitGenFile:checked").each(function() {
           var tDocNoRef    = $(this).parent().parent().parent().attr('data-docnoref');
           tConcatDocNoRef += ',' + tDocNoRef;
           aItemDoc.push(tDocNoRef);
        });

        //ส่งเข้า MQ
        $.ajax({
            type    : "POST",
            url     : "docMnpDocPOGenFileAndSendMail",
            data    : {
                tTypeExport : 'genfile',  
                aItemDoc    : aItemDoc
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {

                setTimeout(function(){
                    // กลับหน้าหลัก
                    JSvMNPCallPageList();
                }, 5000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ส่งอีเมล์
    function JSxMNPSendEmail(){
        var aItemDoc        = [];
        var tConcatDocNoRef = '';
        $(".xCNCheckbox_WaitExport:checked").each(function() {
           var tDocNoRef    = $(this).parent().parent().parent().attr('data-docnoref');
           tConcatDocNoRef += ',' + tDocNoRef;
           aItemDoc.push(tDocNoRef);
        });

        $('#odvMNPModalSendMail').modal('show');

        $('#odvMNPModalSendMail .xCNConfirmSendMail').unbind().click(function(){
            JCNxOpenLoading();
           //ส่งเข้า MQ
            $.ajax({
                type    : "POST",
                url     : "docMnpDocPOGenFileAndSendMail",
                data    : {
                    tTypeExport : 'sendmail',  
                    aItemDoc    : aItemDoc
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {

                    setTimeout(function(){
                        // กลับหน้าหลัก
                        JSvMNPCallPageList();
                    }, 5000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
        
    }

</script>