var nDBRStaDBRBrowseType = $("#oetDBRStaBrowse").val();
var tDBRCallDBRBackOption = $("#oetDBRCallBackOption").val();
var tDBRSesSessionID = $("#ohdSesSessionID").val();
var tDBRSesSessionName = $("#ohdSesSessionName").val();
var tJumpDocNo  = $("#oetDBRJumpDocNo").val();
var tJumpType   = $("#oetCheckJumpType").val();
var tJumpBackto = $("#oetCheckBackTo").val();

$("document").ready(function() {
    localStorage.removeItem("LocalItemData");
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    if (typeof(nDBRStaDBRBrowseType) != 'undefined' && (nDBRStaDBRBrowseType == 0 || nDBRStaDBRBrowseType ==2)) { // Event Click Navigater Title (คลิก Title ของเอกสาร)
        $('#oliDBRTitle').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDBRCallPageList();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtDBRCallBackPage').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                if($('#oetCheckJumpStatus').val() != '1'){
                    JSvDBRCallPageList();
                }else{
                    if(tJumpType == 'SO'){
                        $.ajax({
                            type    : "GET",
                            url     : "dcmSO/0/0",
                            data    : {'ptTypeJump' : '1',
                                       'tDocNo' : tJumpBackto },
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
                    }else if(tJumpType == 'PL'){
                        $.ajax({
                            type    : "POST",
                            url     : "docPAM/0/0",
                            data    : {'ptTypeJump' : '1',
                                       'tDocNo' : tJumpBackto },
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
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Add Page
        $('#obtDBRCallPageAdd').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                JSvDBRCallPageAddDoc();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Cancel Document
        $('#obtDBRCancelDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tDocno =  $("#oetDBRDocNo").val();
                $.ajax({
                    type    : "POST",
                    url     : "docDBRCheckRtsaleDocref",
                    data: {
                        tDocno: tDocno
                    },
                    cache   : false,
                    timeout : 5000,
                    success : function (tResult) {
                        var aReturnData = JSON.parse(tResult);
                        if(aReturnData.rtCode == '1'){
                            JSnDBRCancelDocument(false);
                        }else{
                            FSvCMNSetMsgWarningDialog('ไม่สามารถยกเลิกรายการที่มีการอ้างอิงแล้วได้');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Appove Document  sdsd
        $('#obtDBRApproveDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tFrmSplName = $('#oetDBRFrmSplName').val();
                var tDBRFrmWahName = $('#oetDBRFrmWahName').val();
                var tCheckIteminTable = $('#otbDBRDocPdtAdvTableList .xWPdtItem').length;
                var nPOStaValidate = $('.xPOStaValidate0').length;
                var tChksubmit = 0;
                var aChangeValue = [];
                var tBchFrm =  $("#oetDBRFrmBchCode").val();
                var tUsrCode =  $("#ohdUserCode").val();
                var tDocCodeSO =  $("#ohdDBRDocRefSO").val();
                var tDocCode =  $("#oetDBRDocNo").val();
                

                var tCheckIteminTable = $('#otbDBRDocPdtAdvTableList .xWPdtItem').length;
                $('#otbDBRDocPdtAdvTableList tbody tr.xWPdtItem').each(function() {
                    var nSeqNo      = $(this).attr('data-seqno');
                    if ($('#ohdBox'+nSeqNo+'').val() == '' && $('#ohdSlot'+nSeqNo+'').val() == '') {
                        nStaDeposit = 'false';
                        return;
                    }else if($('#ohdBox'+nSeqNo+'').val() != '' && $('#ohdSlot'+nSeqNo+'').val() == ''){
                        nStaDeposit = 'false';
                        return;
                    }else{
                        nStaDeposit = 'true';
                    }
                });
                if (nStaDeposit == 'true') {
                    if (tCheckIteminTable > 0) {
                        $(".xWPdtItem").each(function () { 
                            var tSeqno  = $(this).data('seqno');
                            var tOldPos = $(this).data('poscode');
                            var tPosNew = $('#ohdBox'+tSeqno).val();
                            var tOldShop = $(this).data('shpcode');
                            var tNewShop = $('#ohdShp'+tSeqno).val();
                            var tOldLay  = $(this).data('layno');
                            var tNewLay = $('#ohdSlot'+tSeqno).val();
                            if(tPosNew != tOldPos || tOldShop != tNewShop || tNewLay != tOldLay){
                                aChangeValue.push({
                                    ptAgnCode: '',
                                    ptBchCode: tBchFrm,
                                    ptUsrCode: tUsrCode,
                                    ptShpFrm: tOldShop,
                                    ptPosFrm: tOldPos,
                                    pnLayNoFrm: tOldLay,
                                    ptShpTo: tNewShop,
                                    ptPosTo: tPosNew,
                                    pnLayNoTo: tNewLay,
                                    ptLayStaTo: '4',
                                    ptDocNo: tDocCodeSO,
                                    pnXsdSeqNo: tSeqno,
                                });
                                tChksubmit = 1;
                            }
                        });
                        if(tChksubmit == 0){
                            if (nPOStaValidate == 0) {
                                //เช็คค่าว่างตัวแทนขาย
                                if (tFrmSplName == '') {
                                    $('#odvDBRModalPleseselectSPL').modal('show');
                                    //เช็คค่าว่างคลังสินค้า
                                } else if (tDBRFrmWahName == '') {
                                    $('#odvDBRModalWahNoFound').modal('show');
                                } else {
                                    JSxDBRSetStatusClickSubmit(2);
                                    JSxDBRSubmitEventByButton('approve');
                                }

                            } else {
                                $('#odvDBRModalImpackImportExcel').modal('show')
                            }
                        }else{
                            $.ajax({
                                type: "POST",
                                url: "docDBRSuggestEdit",
                                data: {
                                    paData: aChangeValue
                                },
                                cache: false,
                                timeout: 0,
                                success: function(tResult) {
                                    var aReturnData = JSON.parse(tResult);
                                    if(aReturnData['nStaEvent'] == 1){
                                        localStorage.setItem("SuggestLayEdit",1);
                                        JSxDBRGetMsgSuggestEdit(tDocCodeSO,aChangeValue);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        }
                    } else {
                        FSvCMNSetMsgWarningDialog($('#ohdDBRValidatePdt').val());
                    }
            }else{
                FSvCMNSetMsgWarningDialog('มีรายการสินค้าที่ยังไม่มีตู้ฝากหรือช่องฝากกรุณาเลือกตู้ฝากหรือช่องฝากให้ครบถ้วน');
            }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        function JSxDBRGetMsgSuggestEdit(ptDocCode,aItems) {
            try {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docDBRGetMsgSuggestEdit",
                    data: {
                        tRefInDocNo: ptDocCode,
                        aItems: aItems
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        var aReturnData = JSON.parse(tResult);
                        if(aReturnData['nStaEvent'] == 1){
                        var aitem = aReturnData.aFailItem;
                        if(aitem.length > 0){
                            $("#odvTBodyDBRFailPdtAdvTableList").empty();
                            for(i=0;i<aitem.length;i++){
                                var tStatus = '';
                                var nSeqno = aitem[i]['rnXsdSeqNo'];
                                if(aitem[i]['rtStatus'] == '2'){
                                    tStatus = 'ช่องฝากที่ต้องการเปลี่ยนถูกใช้งานแล้ว';
                                }else if(aitem[i]['rtStatus'] == '3'){
                                    tStatus = 'ช่องฝากที่ต้องการเปลี่ยนปิดการใช้งานแล้ว';
                                }else if(aitem[i]['rtStatus'] == '4'){
                                    tStatus = 'ช่องฝากที่ต้องการเปลี่ยนถูกจองแล้ว';
                                }
                                var tPosNew = $("#oetBox"+nSeqno).val();
                                var tPosOld = $("#oetBoxHiddenOld"+nSeqno).val();
                                var tShpOld = $("#oetSlotHiddenOld"+nSeqno).val();
                                var tShpNew = $("#oetSlot"+nSeqno).val();
                                var tPdtName = $("#ohdPdtName"+nSeqno).val();
                                
                                var tHTML = '<tr>';
                                tHTML += '<td>'+tPdtName+'</td>';
                                tHTML += '<td>'+tPosOld+'</td>';
                                tHTML += '<td>'+tShpOld+'</td>';
                                tHTML += '<td>'+tPosNew+'</td>';
                                tHTML += '<td>'+tShpNew+'</td>';
                                tHTML += '<td>'+tStatus+'</td>';
                                tHTML += '</tr>';
                            $('#odvTBodyDBRFailPdtAdvTableList').append(tHTML);
                            }
                            $("#odvDBRModalFailItems").modal('show');
                            JCNxCloseLoading();
                        }else{
                            JSxDBRSubmit(true);
                        }
                    }else{
                        var LocalItemData = localStorage.getItem("SuggestLayEdit");
                            if (LocalItemData <= 20) {
                                localStorage.setItem("SuggestLayEdit",parseFloat(LocalItemData)+1);
                                setTimeout(function() {
                                    JSxDBRGetMsgSuggestEdit(ptDocCode,aItems);
                                }, 1000);
                            }else{
                                JCNxCloseLoading();
                                FSvCMNSetMsgErrorDialog("ไม่สำเร็จ");
                            }
                    }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } catch (err) {
                console.log("JSxDBRApproveDocument Error: ", err);
            }
        }

        function JSxDBRGetMsgSuggestEditSubmit(ptDocCode,aItems) {
            try {
                if(ptDocCode == ''){
                    ptDocCode = $("#oetDBRJumpDocNo").val();
                } 
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docDBRGetMsgSuggestEdit",
                    data: {
                        tRefInDocNo: ptDocCode,
                        aItems: aItems
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        var aReturnData = JSON.parse(tResult);
                        console.log(aReturnData);
                        if(aReturnData['nStaEvent'] == 1){
                            var aitem = aReturnData.aFailItem;
                            if(aitem.length > 0){
                                $("#odvTBodyDBRFailPdtAdvTableListSubmit").empty();
                                for(i=0;i<aitem.length;i++){
                                    var tStatus = '';
                                    var nSeqno = aitem[i]['rnXsdSeqNo'];
                                    if(aitem[i]['rtStatus'] == '2'){
                                        tStatus = 'ช่องฝากที่ต้องการเปลี่ยนถูกใช้งานแล้ว';
                                    }else if(aitem[i]['rtStatus'] == '3'){
                                        tStatus = 'ช่องฝากที่ต้องการเปลี่ยนปิดการใช้งานแล้ว';
                                    }else if(aitem[i]['rtStatus'] == '4'){
                                        tStatus = 'ช่องฝากที่ต้องการเปลี่ยนถูกจองแล้ว';
                                    }
                                    var tPosNew = $("#oetBox"+nSeqno).val();
                                    var tPosOld = $("#oetBoxHiddenOld"+nSeqno).val();
                                    var tShpOld = $("#oetSlotHiddenOld"+nSeqno).val();
                                    var tShpNew = $("#oetSlot"+nSeqno).val();
                                    var tPdtName = $("#ohdPdtName"+nSeqno).val();
                                    
                                    var tHTML = '<tr>';
                                    tHTML += '<td>'+tPdtName+'</td>';
                                    tHTML += '<td>'+tPosOld+'</td>';
                                    tHTML += '<td>'+tShpOld+'</td>';
                                    tHTML += '<td>'+tPosNew+'</td>';
                                    tHTML += '<td>'+tShpNew+'</td>';
                                    tHTML += '<td>'+tStatus+'</td>';
                                    tHTML += '</tr>';
                                $('#odvTBodyDBRFailPdtAdvTableListSubmit').append(tHTML);
                                }
                                $("#odvDBRModalFailItemsSubmit").modal('show');
                                JCNxCloseLoading();
                            }else{
                                JSxDBRSetStatusClickSubmit(1);
                                $('#obtDBRSubmitDocument').click();
                            }
                        }else{
                            var LocalItemData = localStorage.getItem("SuggestLayEdit");
                            if (LocalItemData <= 20) {
                                localStorage.setItem("SuggestLayEdit",parseFloat(LocalItemData)+1);
                                setTimeout(function() {
                                    JSxDBRGetMsgSuggestEditSubmit(ptDocCode,aItems);
                                }, 1000);
                            }else{
                                JCNxCloseLoading();
                                FSvCMNSetMsgErrorDialog("ไม่สำเร็จ");
                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } catch (err) {
                console.log("JSxDBRApproveDocument Error: ", err);
            }
        }

        // ปุ่ม สร้างใบสั่งขาย
        $('#obtDBRGenSO').unbind().click(function() {
            $.ajax({
                type    : "POST",
                url     : "docDBREventGenSO",
                data    : {
                    tDocNo      : $('#oetDBRDocNo').val(),
                    tBchCode    : $('#ohdDBRBchCode').val()
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    console.log(tResult);
                    JSvDBRCallPageList();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });

        // กดปุ่มบันทึก sdsd
        $('#obtDBRSubmitFromDoc').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                var tCheckIteminTable = $('#otbDBRDocPdtAdvTableList .xWPdtItem').length;
                var aChangeValue = [];
                var tChksubmit = 0;
                var tBchFrm =  $("#oetDBRFrmBchCode").val();
                var tUsrCode =  $("#ohdUserCode").val();
                var tDocCode =  $("#oetDBRDocNo").val();
                var tAddOrEdit =  $("#oetDBRDocNoTmp").val();
                var tDocCodeSO =  $("#ohdDBRDocRefSO").val();
                if(tDocCodeSO == ''){
                    tDocCodeSO = $("#oetDBRJumpDocNo").val();
                } 

                if (tCheckIteminTable > 0) {
                    var nStaDeposit = 'false';
                    $('#otbDBRDocPdtAdvTableList tbody tr.xWPdtItem').each(function() {
                        var nSeqNo      = $(this).attr('data-seqno');
                        if ($('#ohdBox'+nSeqNo+'').val() == '' && $('#ohdSlot'+nSeqNo+'').val() == '') {
                            nStaDeposit = 'false';
                            return;
                        }else if($('#ohdBox'+nSeqNo+'').val() != '' && $('#ohdSlot'+nSeqNo+'').val() == ''){
                            nStaDeposit = 'false';
                            return;
                        }else{
                            nStaDeposit = 'true';
                        }
                        
                    });
                    if (nStaDeposit == 'true') {
                        $(".xWPdtItem").each(function () { 
                            var tSeqno  = $(this).data('seqno');
                            var tOldPos = $(this).data('poscode');
                            var tPosNew = $('#ohdBox'+tSeqno).val();
                            var tOldShop = $(this).data('shpcode');
                            var tNewShop = $('#ohdShp'+tSeqno).val();
                            var tOldLay  = $(this).data('layno');
                            var tNewLay = $('#ohdSlot'+tSeqno).val();
                            if((tPosNew != tOldPos || tOldShop != tNewShop || tNewLay != tOldLay) || tAddOrEdit == ''){
                                aChangeValue.push({
                                    ptAgnCode: '',
                                    ptBchCode: tBchFrm,
                                    ptUsrCode: tUsrCode,
                                    ptShpFrm: tOldShop,
                                    ptPosFrm: tOldPos,
                                    pnLayNoFrm: tOldLay,
                                    ptShpTo: tNewShop,
                                    ptPosTo: tPosNew,
                                    pnLayNoTo: tNewLay,
                                    ptLayStaTo: '4',
                                    ptDocNo: tDocCodeSO,
                                    pnXsdSeqNo: tSeqno,
                                });
                                tChksubmit = 1;
                            }
                        });
                        if(tChksubmit == 0){
                            JSxDBRSetStatusClickSubmit(1);
                            $('#obtDBRSubmitDocument').click();
                        }else{
                            $.ajax({
                                type: "POST",
                                url: "docDBRSuggestEdit",
                                data: {
                                    paData: aChangeValue
                                },
                                cache: false,
                                timeout: 0,
                                success: function(tResult) {
                                    var aReturnData = JSON.parse(tResult);
                                    if(aReturnData['nStaEvent'] == 1){
                                        localStorage.setItem("SuggestLayEdit",1);
                                        JSxDBRGetMsgSuggestEditSubmit(tDocCodeSO,aChangeValue);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        }
                    }else{
                        FSvCMNSetMsgWarningDialog('มีรายการสินค้าที่ยังไม่มีตู้ฝากหรือช่องฝากกรุณาเลือกตู้ฝากหรือช่องฝากให้ครบถ้วน');
                    }
                    
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdDBRValidatePdt').val());
                }
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        JSxDBRNavDefult('showpage_list');
        switch(nDBRStaDBRBrowseType){
            case '2':
                var tAgnCode = $('#oetDBRJumpAgnCode').val();
                var tBchCode = $('#oetDBRJumpBchCode').val();
                var tDocNo = $('#oetDBRJumpDocNo').val();
                JSvDBRCallPageEdit(tDocNo);
            break;
            default:
                if($('#oetCheckJumpStatus').val() != '1' ){
                    if(tJumpType == '1'){
                        JSvDBRCallPageList();
                    }else if(tJumpType == '2' || tJumpType == 'SO'){
                        JSvDBRCallPageEdit(tDocNo);
                    }else if(tJumpDocNo != ''){
                        JSvDBRCallPageEdit(tJumpDocNo);
                    }else{
                        JSvDBRCallPageList();
                    }
                }else{
                    if(tJumpType == '2' || tJumpType == 'SO'){
                        JSvDBRCallPageEdit(tJumpDocNo);
                    }else{
                        JSvDBRCallPageAddDoc(tJumpDocNo);
                    }
                }
        }
    } else {
        JSxDBRNavDefult('showpage_list');
        JSvDBRCallPageAddDoc();
    }
});

// อนุมัติเอกสาร
function JSxDBRApproveDocument(pbIsConfirm) {
    try {
        if (pbIsConfirm) {
            $("#odvDBRModalAppoveDoc").modal('hide');
            var tAgnCode    = $('#oetDBRAgnCode').val();
            var tDocNo      = $('#oetDBRDocNo').val();
            var tBchCode    = $('#ohdDBRBchCode').val();
            var tRefInDocNo = $('#ohdDBRRefDocIntName').val();
            $.ajax({
                type: "POST",
                url: "docDBRApproveDocument",
                data: {
                    tDocNo: tDocNo,
                    tBchCode: tBchCode,
                    tAgnCode: tAgnCode,
                    tRefInDocNo: tRefInDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvDBRModalAppoveDoc").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSxDBRApproveDocCallModalMQ();
                        // JSvDBRCallPageEdit(tDocNo);
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            $("#odvDBRModalAppoveDoc").modal('show');
        }
    } catch (err) {
        console.log("JSxDBRApproveDocument Error: ", err);
    }
}

// Functionality: Check Status Document Process EQ And Call Back MQ
function JSxDBRApproveDocCallModalMQ(){

    // RESRTBOOK+_+ ptDocNo + _ + ptUser
    var nDBRLangEdits        = nLangEdits;
    var tDBRFrmBchCode       = $("#oetDBRFrmBchCode").val();
    var tDBRUsrApv           = $("#ohdDBRApvCodeUsrLogin").val();
    var tDBRDocNo            = $("#oetDBRDocNo").val();
    var tDBRPrefix           = "RESRTBOOK";
    var tDBRStaApv           = $("#ohdDBRStaApv").val();
    var tDBRStaPrcStk        = $("#ohdDBRStaPrcStk").val();
    var tDBRStaDelMQ         = $("#ohdDBRStaDelMQ").val();
    var tDBRQName            = tDBRPrefix + "_" + tDBRDocNo + "_" + tDBRUsrApv;
    var tDBRTableName        = "TARTDBRHD";
    var tDBRFieldDocNo       = "FTXphDocNo";
    var tDBRFieldStaApv      = "FTXphStaPrcStk";
    var tDBRFieldStaDelMQ    = "FTXphStaDelMQ";

    // MQ Message Config
    var poDocConfig = {
        tLangCode     : nDBRLangEdits,
        tUsrBchCode   : tDBRFrmBchCode,
        tUsrApv       : tDBRUsrApv,
        tDocNo        : tDBRDocNo,
        tPrefix       : tDBRPrefix,
        tStaDelMQ     : tDBRStaDelMQ,
        tStaApv       : tDBRStaApv,
        tQName        : tDBRQName
    };

    // RabbitMQ STOMP Config
    var poMqConfig = {
        host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
        username: oSTOMMQConfig.user,
        password: oSTOMMQConfig.password,
        vHost: oSTOMMQConfig.vhost
    };
    
    // Update Status For Delete Qname Parameter
    var poUpdateStaDelQnameParams   = {
        ptDocTableName      : tDBRTableName,
        ptDocFieldDocNo     : tDBRFieldDocNo,
        ptDocFieldStaApv    : tDBRFieldStaApv,
        ptDocFieldStaDelMQ  : tDBRFieldStaDelMQ,
        ptDocStaDelMQ       : tDBRStaDelMQ,
        ptDocNo             : tDBRDocNo
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit: "JSxDBRCallback",
        tCallPageList: "JSvDBRCallPageList"
    };
    

    
    FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
    

    // Check Delete MQ SubScrib

    var poDelQnameParams    = {
        ptPrefixQueueName   : tDBRPrefix,
        ptBchCode           : tDBRFrmBchCode,
        ptDocNo             : tDBRDocNo,
        ptUsrCode           : tDBRUsrApv
    };
    // FSxCMNRabbitMQDeleteQname(poDelQnameParams);
    // FSxCMNRabbitMQUpdateStaDeleteQname(poUpdateStaDelQnameParams);

}

function JSxDBRCallback(aitem){
    JSvDBRCallPageEdit(aitem)
}

// อนุมัติเอกสาร
function JSxDBRApproveDocument2(pbIsConfirm) {
    try {
        if (pbIsConfirm) {
            $("#odvDBRModalAppoveDoc2").modal('hide');
            var tAgnCode    = $('#oetDBRAgnCode').val();
            var tDocNo      = $('#oetDBRDocNo').val();
            var tBchCode    = $('#ohdDBRBchCode').val();
            var tRefInDocNo = $('#ohdDBRRefDocIntName').val();
            $.ajax({
                type: "POST",
                url: "docDBRApproveDocumentAfterSugges",
                data: {
                    tDocNo: tDocNo,
                    tBchCode: tBchCode,
                    tAgnCode: tAgnCode,
                    tRefInDocNo: tRefInDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvDBRModalAppoveDoc2").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSvDBRCallPageEdit(tDocNo);
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            $("#odvDBRModalAppoveDoc").modal('show');
        }
    } catch (err) {
        console.log("JSxDBRApproveDocument Error: ", err);
    }
}

// ยินยันข้อมูลที่อัพเดทไม่ได้
function JSxDBRSubmit(pbIsConfirm) {
    try {
        $("#odvDBRModalFailItems").modal('hide');
        $("#odvDBRModalAppoveDoc2").modal('show');
    } catch (err) {
        console.log("JSxDBRApproveDocument Error: ", err);
    }
}

// ยินยันข้อมูลที่อัพเดทไม่ได้
function JSxDBRSubmit2(pbIsConfirm) {
    try {
        $("#odvDBRModalFailItemsSubmit").modal('hide');
        JSxDBRSetStatusClickSubmit(1);
        $('#obtDBRSubmitDocument').click();
    } catch (err) {
        console.log("JSxDBRApproveDocument Error: ", err);
    }
}


// Control เมนู
function JSxDBRNavDefult(ptType) {
    if (ptType == 'showpage_list') { // แสดง
        $("#oliDBRTitle").show();
        $("#odvDBRBtnGrpInfo").show();
        $("#obtDBRCallPageAdd").show();

        // ซ่อน
        $("#oliDBRTitleAdd").hide();
        $("#oliDBRTitleEdit").hide();
        $("#oliDBRTitleDetail").hide();
        $("#oliDBRTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtDBRCallBackPage").hide();
        $("#obtDBRPrintDoc1").hide();
        $("#obtDBRPrintDoc2").hide();
        $("#obtDBRPrintDoc").hide();
        $("#obtDBRCancelDoc").hide();
        $("#obtDBRApproveDoc").hide();
        $("#obtDBRGenSO").hide();
        $("#odvDBRBtnGrpSave").hide();

    } else if (ptType == 'showpage_add') { // แสดง
        $("#oliDBRTitle").show();
        $("#odvDBRBtnGrpSave").show();
        $("#obtDBRCallBackPage").show();
        $("#oliDBRTitleAdd").show();

        // ซ่อน
        $("#oliDBRTitleEdit").hide();
        $("#oliDBRTitleDetail").hide();
        $("#oliDBRTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#obtDBRPrintDoc").hide();
        $("#obtDBRPrintDoc1").hide();
        $("#obtDBRPrintDoc2").hide();
        $("#obtDBRCancelDoc").hide();
        $("#obtDBRApproveDoc").hide();
        $("#obtDBRGenSO").hide();
        $("#odvDBRBtnGrpInfo").hide();
    } else if (ptType == 'showpage_edit') { // แสดง

        $("#oliDBRTitle").show();
        $("#odvDBRBtnGrpSave").show();
        $("#obtDBRApproveDoc").show();
        $("#obtDBRGenSO").hide();
        $("#obtDBRCancelDoc").show();
        $("#obtDBRCallBackPage").show();
        $("#oliDBRTitleEdit").show();
        $("#obtDBRPrintDoc").show();
        $("#obtDBRPrintDoc1").show();
        $("#obtDBRPrintDoc2").show();
        $("#obtDBRPrintDoc1").prop('disabled',true);
        $("#obtDBRPrintDoc").prop('disabled',true);
        $("#obtDBRPrintDoc2").prop('disabled',true);        


        // ซ่อน
        $("#oliDBRTitleAdd").hide();
        $("#oliDBRTitleDetail").hide();
        $("#oliDBRTitleAprove").hide();
        $("#odvBtnAddEdit").hide();
        $("#odvDBRBtnGrpInfo").hide();
    }

    // ล้างค่า
    localStorage.removeItem('IV_LocalItemDataDelDtTemp');
    localStorage.removeItem('LocalItemData');
    if($('#ohdXshRefKey').val()!=''){
        $('#obtDBRCancelDoc').hide();
    }
}

// Function: Call Page List
function JSvDBRCallPageList() {
    $.ajax({
        type: "GET",
        url: "dcmDBRFormSearchList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvDBRContentPageDocument").html(tResult);
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            JSxDBRNavDefult('showpage_list');
            JSvDBRCallPageDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function: Call Page DataTable
function JSvDBRCallPageDataTable(pnPage) {
    JCNxOpenLoading();
    var oAdvanceSearch = JSoDBRGetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docDBRDataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostDBRDataTableDocument').html(aReturnData['tDBRViewDataTableList']);
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
}

// รวม Values ต่างๆของการค้นหาขั้นสูง
function JSoDBRGetAdvanceSearchData() {
    var oAdvanceSearchData = {
        tSearchAll: $("#oetDBRSearchAllDocument").val(),
        tSearchBchCodeFrom: $("#oetDBRAdvSearchBchCodeFrom").val(),
        tSearchBchCodeTo: $("#oetDBRAdvSearchBchCodeTo").val(),
        tSearchDocDateFrom: $("#oetDBRAdvSearcDocDateFrom").val(),
        tSearchDocDateTo: $("#oetDBRAdvSearcDocDateTo").val(),
        tSearchStaDoc: $("#ocmDBRAdvSearchStaDoc").val(),
        tSearchStaDocAct: $("#ocmStaDocAct").val()
    };
    return oAdvanceSearchData;
}

// เข้ามาแบบ insert
function JSvDBRCallPageAddDoc(ptDocno = '') {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docDBRPageAdd",
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                JSxDBRNavDefult('showpage_add');
                $('#odvDBRContentPageDocument').html(aReturnData['tDBRViewPageAdd']);
                $("#ocmDBRTypePayment").val("1").selectpicker('refresh');
                $('.xCNPanel_CreditTerm').hide();
                JSvDBRLoadPdtDataTableHtml();
                JCNxLayoutControll();
                if(ptDocno != ''){
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docDBRCallRefIntDocInsertDTToTempByJump",
                        data: {
                            'tDBRDocNo'          : $('#oetDBRDocNo').val(),
                            'tDBRFrmBchCode'     : $('#oetDBRFrmBchCode').val(),
                            'tDBROptionAddPdt'   : $('#ocmDBRFrmInfoOthReAddPdt').val(),
                            'tRefIntDocNo'      : ptDocno,
                            'tRefIntBchCode'    : $('#oetDBRJumpBchCode').val(),
                            'tDoctype'          : 1
                        },
                        cache: false,
                        Timeout: 0,
                        success: function (oResult){
                            var aReturnDataCst = JSON.parse(oResult);
                            $('#oetDBRCstCode').val(aReturnDataCst['raItems'][0].FTCstCode);
                            $('#oetDBRCstName').val(aReturnDataCst['raItems'][0].FTCstName);
                            $('#oetDBRCstTel').val(aReturnDataCst['raItems'][0].FTCstTel);
                            $('#oetDBRCstMail').val(aReturnDataCst['raItems'][0].FTCstEmail);
                            JSvDBRLoadPdtDataTableHtml();
                            $.ajax({
                                type    : "POST",
                                url     : "docDBREventAddEditHDDocRef",
                                data    : {
                                    'ptRefDocNoOld'     : $('#oetDBRRefDocNoOld').val(),
                                    'ptDocNo'           : $('#oetDBRDocNo').val(),
                                    'ptRefType'         : '1',
                                    'ptRefDocNo'        : ptDocno,
                                    'pdRefDocDate'      : $('#oetDBRRefDocDate').val()+' '+$('#ohdDBRRefDocTime').val(),
                                    'ptRefKey'          : 'SO',
                                    'ptRefEx'           : 'DWN',
                                },
                                cache: false,
                                timeout: 0,
                                success: function(oResult){
                                    var aReturnData = JSON.parse(oResult);
                                    // JSxDBREventClearValueInFormHDDocRef();
                                    $('#odvDBRModalAddDocRef').modal('hide');
            
                                    if (aReturnData['nStaEvent'] == 2) {
                                        FSvCMNSetMsgErrorDialog("เลขที่เอกสารนี้ถูกอ้างอิงแล้ว");
                                    }
            
                                    JSxDBRCallPageHDDocRef();
                                    JCNxCloseLoading();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log(jqXHR);
                                    console.log(textStatus);
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }else{

                }
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

// เข้าหน้าแบบ แก้ไข
function JSvDBRCallPageEdit(ptDocumentNumber, pnDBRStaRef) {
    var nStaSession = JCNxFuncChkSessionExpired();
    // alert(ptDocumentNumber);
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docDBRPageEdit",
            data: {
                'ptDBRDocNo': ptDocumentNumber
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JCNxCloseLoading();
                var aReturnData = JSON.parse(tResult)
                if (aReturnData['nStaEvent'] == '1') {
                    JSxDBRNavDefult('showpage_edit');
                    $('#odvDBRContentPageDocument').html(aReturnData['tViewPageEdit']);

                    //สถานะอ้างอิง
                    var nDBRStaRef = $('#ohdDBRStaRef').val();
                    if (nDBRStaRef == 0 || pnDBRStaRef == 0) {
                        $("#ocmDBRFrmInfoOthRef").val("0").selectpicker('refresh');
                    } else if (nDBRStaRef == 1 || pnDBRStaRef == 1) {
                        $("#ocmDBRFrmInfoOthRef").val("1").selectpicker('refresh');
                    } else {
                        $("#ocmDBRFrmInfoOthRef").val("2").selectpicker('refresh');
                    }
                    JSvDBRLoadPdtDataTableHtml();

                    // เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    JSxDBRControlFormWhenCancelOrApprove();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
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

// Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxDBRControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdDBRStaDoc').val();
    var tStatusApv = $('#ohdDBRStaApv').val();

    // control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) {
        // เอกสารยกเลิก
        // ปุ่มเลือก
        $('.xCNBtnBrowseAddOn').addClass('disabled');
        $('.xCNBtnBrowseAddOn').attr('disabled', true);

        // ปุ่มเวลา
        $('.xCNBtnDateTime').addClass('disabled');
        $('.xCNBtnDateTime').attr('disabled', true);

        // เพิ่มข้อมูลสินค้า
        $('.xCNHideWhenCancelOrApprove').hide();
    }

    // control ปุ่ม
    if (tStatusDoc == 3) {
        // เอกสารยกเลิก
        // ปุ่มยกเลิก
        $('#obtDBRCancelDoc').hide();

        // ปุ่มอนุมัติ
        $('#obtDBRApproveDoc').hide();

        // ปุ่มสร้างใบสั่งขาย
        $("#obtDBRGenSO").hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxDBRControlObjAndBtn();

    } else if (tStatusDoc == 1 && tStatusApv == 1) {
        // เอกสารอนุมัติแล้ว
        // ปุ่มยกเลิก
        $('#obtDBRCancelDoc').hide();

        $("#obtDBRPrintDoc1").prop('disabled',false);

        // ปุ่มอนุมัติ
        $('#obtDBRApproveDoc').hide();

        // ปุ่มสร้างใบสั่งขาย
        $("#obtDBRGenSO").hide();

        // ปุ่มบันทึก
        $('.xCNBTNSaveDoc').show();

        JCNxDBRControlObjAndBtn();
    }
}

// Function : Call Page Product Table In Add Document
function JSvDBRLoadPdtDataTableHtml(pnPage) {
    if ($("#ohdDBRRoute").val() == "docDBREventAdd") {
        var tDBRDocNo = "";
    } else {
        var tDBRDocNo = $("#oetDBRDocNo").val();
    }
    var tDBRStaApv = $("#ohdDBRStaApv").val();
    var tDBRStaDoc = $("#ohdDBRStaDoc").val();

    // เช็ค สินค้าใน table หน้านั้นๆ หรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
    if ($("#otbDBRDocPdtAdvTableList .xWPdtItem").length == 0) {
        if (pnPage != undefined) {
            pnPage = pnPage - 1;
        }
    }

    if (pnPage == '' || pnPage == null) {
        var pnNewPage = 1;
    } else {
        var pnNewPage = pnPage;
    }
    var nPageCurrent = pnNewPage;
    var tSearchPdtAdvTable = $('#oetDBRFrmFilterPdtHTML').val();

    // if (tDBRStaApv == 2) {
    //     $('#obtDBRDocBrowsePdt').hide();
    //     $('#obtDBRPrintDoc').hide();
    //     $('#obtDBRCancelDoc').hide();
    //     $('#obtDBRApproveDoc').hide();
    //     $('#odvDBRBtnGrpSave').hide();
    // }

    $.ajax({
        type    : "POST",
        url     : "docDBRPdtAdvanceTableLoadData",
        data    : {
            'tSelectBCH'            : $('#oetDBRFrmBchCode').val(),
            'ptSearchPdtAdvTable'   : tSearchPdtAdvTable,
            'ptDBRDocNo'             : tDBRDocNo,
            'ptDBRStaApv'            : tDBRStaApv,
            'ptDBRStaDoc'            : tDBRStaDoc,
            'pnDBRPageCurrent'       : nPageCurrent
        },
        cache: false,
        Timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['checksession'] == 'expire') {
                JCNxShowMsgSessionExpired();
            } else {
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvDBRDataPanelDetailPDT #odvDBRDataPdtTableDTTemp').html(aReturnData['tDBRPdtAdvTableHtml']);
                    if ($('#ohdDBRStaImport').val() == 1) {
                        $('.xDBRImportDT').show();
                    }
                    JCNxCloseLoading();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JSvDBRLoadPdtDataTableHtml(pnPage)
        }
    });
}

function JSvDBRDBRCFilterPdtInTableTemp() {
    JCNxOpenLoading();
    JSvPOLoadPdtDataTableHtml();
}

// Function Chack Value LocalStorage
function JStDBRFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

function JSxDBRSetStatusClickSubmit(pnStatus) {
    if (pnStatus == 1) {
        $('#ohdDBRApvOrSave').val('');
    }else if (pnStatus == 2) {
        $('#ohdDBRApvOrSave').val('approve');
    }
    $("#ohdDBRCheckSubmitByButton").val(pnStatus);
}

// Add/Edit Document
function JSxDBRAddEditDocument() { 
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JSxDBRValidateFormDocument();
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Document Single
function JSoDBRDelDocSingle(ptCurrentPage, ptDBRDocNo, tBchCode, ptDBRRefInCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        if (typeof(ptDBRDocNo) != undefined && ptDBRDocNo != "") {
            var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptDBRDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
            $('#odvDBRModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
            $('#odvDBRModalDelDocSingle').modal('show');
            $('#odvDBRModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docDBREventDelete",
                    data: {
                        'tDataDocNo': ptDBRDocNo,
                        'tBchCode': tBchCode,
                        'tDBRRefInCode': ptDBRRefInCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var aReturnData = JSON.parse(oResult);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvDBRModalDelDocSingle').modal('hide');
                            $('#odvDBRModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                            $('.modal-backdrop').remove();
                            setTimeout(function() {
                                JSvDBRCallPageDataTable(ptCurrentPage);
                            }, 500);
                        } else {
                            JCNxCloseLoading();
                            FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        } else {
            FSvCMNSetMsgErrorDialog('Error Not Found Document Number !!');
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Event Single Delete Doc Mutiple
function JSoDBRDelDocMultiple() {
    var aDataDelMultiple = $('#odvDBRModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
    var aTextsDelMultiple = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
    var aDataSplit = aTextsDelMultiple.split(" , ");
    var nDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];
    for ($i = 0; $i < nDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }
    if (nDataSplitlength > 1) {

        JCNxOpenLoading();
        $('.ocbListItem:checked').each(function() {
            var tDataDocNo = $(this).val();
            var tBchCode = $(this).data('bchcode');
            var tDBRRefInCode = $(this).data('refcode');
            localStorage.StaDeleteArray = '1';
            $.ajax({
                type: "POST",
                url: "docDBREventDelete",
                data: {
                    'tDataDocNo': tDataDocNo,
                    'tBchCode': tBchCode,
                    'tDBRRefInCode': tDBRRefInCode
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvDBRModalDelDocMultiple').modal('hide');
                            $('#odvDBRModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                            $('#odvDBRModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                            $('.modal-backdrop').remove();
                            localStorage.removeItem('LocalItemData');
                            JSvDBRCallPageList();
                        }, 1000);
                    } else {
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        });


    }
}

// Function: Function Chack And Show Button Delete All
function JSxDBRShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliDBRBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliDBRBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliDBRBtnDeleteAll").addClass("disabled");
        }
    }
}

// Function: Function Chack Value LocalStorage
function JStDBRFindObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return "Dupilcate";
        }
    }
    return "None";
}

//ยกเลิกเอกสาร
function JSnDBRCancelDocument(pbIsConfirm) {
    var aChangeValue = [];
    var tDBRDocNo    = $("#oetDBRDocNo").val();
    var tRefInDocNo = $('#ohdDBRRefDocIntName').val();
    if (pbIsConfirm) {
        var tBchFrm =  $("#oetDBRFrmBchCode").val();
        var tUsrCode =  $("#ohdUserCode").val();
        var tDocCodeSO =  $("#ohdDBRDocRefSO").val();
        if(tDocCodeSO == ''){
            tDocCodeSO = $("#oetDBRJumpDocNo").val();
        } 

        $(".xWPdtItem").each(function () { 
            var tSeqno  = $(this).data('seqno');
            var tOldPos = $(this).data('poscode');
            var tPosNew = $('#ohdBox'+tSeqno).val();
            var tOldShop = $(this).data('shpcode');
            var tNewShop = $('#ohdShp'+tSeqno).val();
            var tOldLay  = $(this).data('layno');
            var tNewLay = $('#ohdSlot'+tSeqno).val();
            
                aChangeValue.push({
                    ptAgnCode: '',
                    ptBchCode: tBchFrm,
                    ptUsrCode: tUsrCode,
                    ptShpFrm: tOldShop,
                    ptPosFrm: tOldPos,
                    pnLayNoFrm: tOldLay,
                    ptShpTo: tNewShop,
                    ptPosTo: tPosNew,
                    pnLayNoTo: tNewLay,
                    ptLayStaTo: '1',
                    ptDocNo: tDocCodeSO,
                    pnXsdSeqNo: tSeqno,
                });
                tChksubmit = 1;

        });
        $.ajax({
            type: "POST",
            url: "docDBRSuggestEdit",
            data: {
                paData: aChangeValue
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == 1){
                    $.ajax({
                        type    : "POST",
                        url     : "docDBRCancelDocument",
                        data    : {
                            'ptDBRDocNo'     : tDBRDocNo,
                            'ptRefInDocNo'  : tRefInDocNo,
                            'ptStaApv'      : $('#ohdDBRStaApv').val(),
                            'ptBchCode'     : $('#oetDBRFrmBchCode').val()
                        },
                        cache: false,
                        timeout: 0,
                        success: function(tResult) {
            
                            $("#odvDBRPopupCancel").modal("hide");
                            $('.modal-backdrop').remove();
                            var aReturnData = JSON.parse(tResult);
            
                            if (aReturnData['nStaEvent'] == '1') {
                                JSvDBRCallPageEdit(tDBRDocNo);
                            } else {
                                var tMessageError = aReturnData['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                JCNxCloseLoading();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        

            
       
    } else {
        $.ajax({
            type    : "POST",
            url     : "docDBRCancelCheckDocref",
            data    : {
                'ptDBRDocNo'     : tDBRDocNo
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvDBRPopupCancel').modal({ backdrop: 'static', keyboard: false });
                    $("#odvDBRPopupCancel").modal("show");
                } else if(aReturnData['nStaEvent'] == '2'){
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                } 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function: Function Control Object Button
function JCNxDBRControlObjAndBtn() { // Check สถานะอนุมัติ
    var nDBRStaDoc = $("#ohdDBRStaDoc").val();
    var nDBRStaApv = $("#ohdDBRStaApv").val();
    var nDBRRefPO  = $("#ohdDBRPORef").val();
    var nDBRRefIV  = $("#ohdDBRPIRef").val();
    var nDBRRefSO  = $("#ohdDBRSORef").val();

    // Status Cancel
    if (nDBRStaDoc == 3) {
        $("#oliDBRTitleAdd").hide();
        $('#oliDBRTitleEdit').hide();
        $('#oliDBRTitleDetail').show();
        $('#oliDBRTitleAprove').hide();
        $('#oliDBRTitleConimg').hide();
        // Hide And Disabled
        $("#obtDBRCallPageAdd").hide();
        $("#obtDBRCancelDoc").hide(); 
        $("#obtDBRApproveDoc").hide(); 
        $("#obtDBRBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetDBRFrmSearchPdtHTML").attr("disabled", false);
        $('#odvDBRBtnGrpSave').show();
        $("#oliDBREditShipAddress").hide();
        $("#oliDBREditTexAddress").hide();
        $("#oliDBRTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWDBRDisabledOnApv').attr('disabled', true);
        $("#ocbDBRFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtDBRFrmBrowseShipAdd").attr("disabled", true);
        $("#obtDBRFrmBrowseTaxAdd").attr("disabled", true);


    }

    // Status Appove Success
    if (nDBRStaDoc == 1 && nDBRStaApv == 1) { // Hide/Show Menu Title
        $("#oliDBRTitleAdd").hide();
        $('#oliDBRTitleEdit').hide();
        $('#oliDBRTitleDetail').show();
        $('#oliDBRTitleAprove').hide();
        $('#oliDBRTitleConimg').hide();
        // Hide And Disabled
        $("#obtDBRCallPageAdd").hide();
        $("#obtDBRCancelDoc").show(); 
        $("#obtDBRApproveDoc").hide(); 
        $("#obtDBRBrowseSupplier").attr("disabled", true);

        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetDBRFrmSearchPdtHTML").attr("disabled", false);
        $('#odvDBRBtnGrpSave').show();
        $("#oliDBREditShipAddress").hide();
        $("#oliDBREditTexAddress").hide();
        $("#oliDBRTitleDetail").show();
        $('.xControlForm').attr('readonly', true);
        $('.xWDBRDisabledOnApv').attr('disabled', true);
        $("#ocbDBRFrmInfoOthStaDocAct").attr("readonly", true);
        $("#obtDBRFrmBrowseShipAdd").attr("disabled", true);
        $("#obtDBRFrmBrowseTaxAdd").attr("disabled", true);
        // if(nDBRRefIV != ''){
        //     $("#obtDBRCancelDoc").hide(); 
        // }
    }

    if($('#ohdXshRefKey').val()!=''){
        $('#obtDBRCancelDoc').hide();
    }
    
}