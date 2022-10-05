<script>

    $("document").ready(function () {
        localStorage.removeItem("LocalItemData");
        JSxCheckPinMenuClose(); 
        JSvMNGCallPageList();

        JSxMNGNavDefult('showpage_list');
    });

    //Control เมนู
    function JSxMNGNavDefult(ptType){
        if(ptType == 'showpage_list'){
            $("#oliMNGTitle_ManagePDT").hide();
            $("#oliMNGTitle_StatusApv").hide();
        }else if(ptType == 'showpage_manage'){
            $("#oliMNGTitle_ManagePDT").show();
            $("#oliMNGTitle_StatusApv").hide();
        }else if(ptType == 'showpage_edit'){

        }

        //ซ่อนปุ่ม
        $('#obtMNGBackStep').hide();
        $('#obtMNGCreateDocRef').hide();
        $('#obtMNGApproveDoc').hide();
        $('#obtMNGExportDoc').hide();
        $('#obtMNGGenFileAgain').hide();
    }

    //โหลด List
    function JSvMNGCallPageList(){
        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBSearchList",
            data    : { 'tMNGTypeDocument'  : $('#ohdMNGTypeDocument').val() },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvMNGContentPageDocument").html(tResult);
                JSxMNGNavDefult('showpage_list');
                JSvMNGCallPageDataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Move สินค้าสั่งซื้อไปยังขอซื้อ
    function JSvMNGCalculateMoveToPRS(){
        $("#otbMNGTableCustomByPDT tbody tr").each(function (i, el) {
            var tDocNoRef    = $(this).find('td:eq(4)').text();

            //ขอซื้อ
            $(this).find('.xWEditInLineReqBuy .xCNPdtEditInLine').val(tDocNoRef);

            //ขอโอน
            $(this).find('td:eq(8)').find('.xControlForm').val('');
            $(this).find('.xWEditInLineReqTnf .xCNPdtEditInLine').val(0);

            //ไม่อนุมัติ
            $(this).find('.xWEditInLineNotApv .xCNPdtEditInLine').val(0);

            var nSEQ            = $(this).attr('data-seqcode');
            var nKeepSPLKey     = ($(this).attr('data-seqcode') - 1);
            var ptPDTCode       = $('.xCNTrKey'+nKeepSPLKey).attr('data-pdtcode');
            var pnQTY           = tDocNoRef
            var ptSPLCode       = $('.xCNTrKey'+nKeepSPLKey).find('td:eq(6)').find('.xCNInputWithoutSingleQuote').val();
            JSxUpdateQTYAndSPLAndBCHAll(pnQTY , ptPDTCode , ptSPLCode , nSEQ);
        });
    }

    //อัพเดทจำนวนขอซื้อทั้งหมด
    function JSxUpdateQTYAndSPLAndBCHAll(pnQTY, ptPDTCode , ptSPLCode , nSEQ){
        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBUpdateQTYAll",
            data    : {
                pnQTY       : pnQTY,
                ptPDTCode   : ptPDTCode,
                ptSPLCode   : ptSPLCode , 
                nSEQ        : nSEQ
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {
                //console.log(oResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //โหลดข้อมูลตาราง
    function JSvMNGCallPageDataTable(pnPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var oAdvanceSearch = JSoMNGGetAdvanceSearchData();
            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = "1";
            }

            //ซ่อนปุ่ม
            $('#obtMNGBackStep').hide();
            $('#obtMNGCreateDocRef').hide();
            $('#obtMNGApproveDoc').hide();
            $('#obtMNGExportDoc').hide();
            $('#obtMNGGenFileAgain').hide();

            $.ajax({
                type    : "POST",
                url     : "docMngDocPreOrdBDataTable",
                data    : {
                    'oAdvanceSearch'    : oAdvanceSearch,
                    'nPageCurrent'      : nPageCurrent,
                    'tMNGTypeDocument'  : $('#ohdMNGTypeDocument').val()
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        $('#ostContentMNG').html(aReturnData['tViewDataTable']);
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
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ข้อมูลค้นหาขั้นสูง 
    function JSoMNGGetAdvanceSearchData() {
        try {
            let oAdvanceSearchData = {
                tSearchAll          : $("#oetSearchAll").val(),
                tSearchBchCodeFrom  : $("#oetBchCodeFrom").val(),
                tSearchBchCodeTo    : $("#oetBchCodeTo").val(),
                tSearchDocDateFrom  : $("#oetSearchDocDateFrom").val(),
                tSearchDocDateTo    : $("#oetSearchDocDateTo").val(),
                tSearchDocDateRef   : $("#oetSearchDocDateRef").val(),
                tSearchSplCodeFrom  : $('#oetSplCodeFrom').val(),
                tSearchSplCodeTo    : $('#oetSplCodeTo').val(),
                tSearchStaApv       : $("#ocmStaApv").val(),
                tSearchTypeDocument : $("#ocmStaTypeDocument").val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("ค้นหาขั้นสูง Error: ", err);
        }
    }

    //กด Next Page
    function JSvMNGClickPageList(ptPage){
        var nPageCurrent = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWPageMNGPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWPageMNGPdt .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JCNxOpenLoading();
        JSvMNGCallPageDataTable(nPageCurrent);
    }

    //กดเข้าไปจัดการสินค้า
    function JSvMNGCallPageEditDoc(ptDocumentNumber){
        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBManagePDT",
            data    : {
                ptDocumentNumber  : ptDocumentNumber
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvMNGContentPageDocument').html(aReturnData['tViewDataTable']);
                    JSxMNGNavDefult('showpage_manage');
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

    //ยืนยันสร้างเอกสาร
    function JSxMNGCreateDocRef(){
        var aItemDoc        = [];
        var tConcatDocNoRef = '';
        $(".xCNCheckbox_WaitConfirm:checked").each(function() {
           var tDocNoRef    = $(this).parent().parent().parent().attr('data-docnoref');
           tConcatDocNoRef += ',' + tDocNoRef;
           aItemDoc.push(tDocNoRef);
        });

        
        $('#odvMGTModalCreateDocNo').modal('show');
        $('#odvMGTModalCreateDocNo #ospModalCreateDocNo').text('ยืนยันทำการสร้างเอกสารหมายเลข : ' + tConcatDocNoRef.substring(1));

        //กดยืนยัน
        $('#odvMGTModalCreateDocNo .xCNConfirmCreateDocNo').unbind().click(function(){
            $.ajax({
                type    : "POST",
                url     : "docMngDocPreOrdBCreateDocRef",
                data    : {
                    aItemDoc : aItemDoc
                },
                cache   : false,
                timeout : 5000,
                success : function (oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        $('#odvMNGContentPageDocument').html(aReturnData['tViewDataTable']);
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
        });
    }

    //อนุมัติเอกสาร
    function JSxMNGAproveDocRef(){
        var aItemDoc        = [];
        var tConcatDocNoRef = '';
        $(".xCNCheckbox_WaitAprove:checked").each(function() {
           var tDocNoRef    = $(this).parent().parent().parent().attr('data-docnoref');
           tConcatDocNoRef += ',' + tDocNoRef;
           aItemDoc.push(tDocNoRef);
        });

        if(aItemDoc.length == 0){
            JSvMNGCallPageList();
        }else{
            $('#odvMGTPopupApv').modal('show');

            //กดยืนยัน
            $('#odvMGTPopupApv .xCNConfirmApprove').unbind().click(function(){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docMngDocPreOrdBAproveDocRef",
                    data    : {
                        aItemDoc : aItemDoc
                    },
                    cache   : false,
                    timeout : 5000,
                    success : function (oResult) {
                        setTimeout(function(){
                            JSvMNGCallPageList();
                        }, 4500);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        }
    }

    //ซ่อมไฟล์อีกครั้ง
    function JSxMNGGenFileAgain(){

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
            url     : "docMngDocPreOrdBExport",
            data    : {
                tTypeExport : 'genfile',  
                aItemDoc    : aItemDoc
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {
                setTimeout(function(){
                    // กลับหน้าหลัก
                    JSvMNGCallPageList();
                }, 4500);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ส่งออกไฟล์
    function JSxMNGSendEmail(){
        var aItemDoc        = [];
        var tConcatDocNoRef = '';
        $(".xCNCheckbox_WaitExport:checked").each(function() {
           var tDocNoRef    = $(this).parent().parent().parent().attr('data-docnoref');
           tConcatDocNoRef += ',' + tDocNoRef;
           aItemDoc.push(tDocNoRef);
        });

        $('#odvMGTModalExportFile').modal('show');

        //กดยืนยันส่งหาผู้จำหน่าย
        // $('#odvMGTModalExportFile .xCNConfirmExport').unbind().click(function(){
        //     JSxMNGExportFileToMQ('3',aItemDoc); //ส่งออก
        // });

        $('#odvMGTModalExportFile .xCNConfirmExportAndDowload').unbind().click(function(){
            JCNxOpenLoading();
            JSxMNGExportFileToMQ('4',aItemDoc); //ส่งออกเเละส่งเมลล์
        });
    }

    //ส่งเข้า MQ
    function JSxMNGExportFileToMQ(ptType,paItemDoc){

        //ลบ queue
        JSxDeleteSubscribe();
        
        //ส่งเข้า MQ
        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBExport",
            data    : {
                tTypeExport : ptType,  
                aItemDoc    : paItemDoc
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {

                setTimeout(function(){
                    // กลับหน้าหลัก
                    JSvMNGCallPageList();
                }, 4500);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Subscribe
    function JSoMNGSubscribeMQ() {
        var tLangCode   = '<?=$this->session->userdata("tLangEdit")?>';
        var tUsrBchCode = $("#oetTVOBCHCode").val();
        var tUsrApv     = '<?=$this->session->userdata('tSesUsername')?>';
        var tDocNo      = 'TCNTPdtReqSplHD';
        var tPrefix     = "RESEXPSPL";
        var tQName      = tPrefix + "_" + tDocNo + "_" + tUsrApv;

        JCNxOpenLoading();

        return new Promise(resolve => {
            oGetResponse = setInterval(function(){
                $.ajax({
                    url     : 'GetMassageQueueMutiDocument',
                    type    : 'post',
                    data    : { tQName : tQName },
                    async   : false,
                    success:function(res){
                        if(res.trim() == '' || res.trim() == null){
                            resolve(true);
                        }else{

                            JCNxCloseLoading();

                            //ใส่ค่า
                            setTimeout(function(){
                                $('#odvMGTModalDowloadFile').modal('show');
                                var tTextDowload = res.trim();
                                // $('#ospMGTDowloadFile').html(tTextDowload);
                                $('.xCNConfirmDowloadGoToDowload').attr("href", tTextDowload);
                            }, 1000);
                          
                            //ส่งค่ากลับไป
                            resolve(true);

                            //ลบ Interval
                            JSxRemoveSetInterval(oGetResponse);

                            //ลบ queue
                            JSxDeleteSubscribe();

                            // กลับหน้าหลัก
                            JSvMNGCallPageList();
                        }
                    }
                });
            }, 1000);
        });
    }

    //ปิดโมดอล
    function JSxCloseModalDowloadFile(){
        $('#odvMGTModalDowloadFile').modal('hide');
    }

    //สั้งให้ลบ interval
    function JSxRemoveSetInterval(poObjectFunction) {
        clearInterval(poObjectFunction);
    }  

    //ลบ queue
    function JSxDeleteSubscribe(){
        var poDelQnameParams = {
            "ptPrefixQueueName" : "RESEXPSPL",
            "ptBchCode"         : "",
            "ptDocNo"           : "TCNTPdtReqSplHD",
            "ptUsrCode"         : '<?=$this->session->userdata('tSesUsername')?>'
        };
        FSxCMNRabbitMQDeleteQname(poDelQnameParams);
    }

    function JSxMNGPageChkPdtStkBal(paPackData){

        var tPdtCode        = paPackData['tPdtCode'];
        var tBchCode        = paPackData['tBchCode'];
        var tWahCode        = paPackData['tWahCode'];
        var tBchCodeOrder   = $('#oetMNGBchCode').val();

        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBChkPdtStkBal",
            data    : {
                ptPdtCode       : tPdtCode,
                ptBchCode       : tBchCode,
                ptWahCode       : tWahCode,
                ptBchCodeOrder  : tBchCodeOrder
            },
            cache   : false,
            timeout : 0,
            success : function (tResultHtml) {
                $('#odvMNGModalChkPdtStkBal #odvMNGModalDetails').html(tResultHtml);
                $('#odvMNGModalChkPdtStkBal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    $('#obtMNGChkPdtStkBalSearchSubmit').off('click').on('click',function(){
        var tKey = $(this).attr('data-key');
        var aPackData = {
            tPdtCode : $('#oetMNGChkPdtStkBalPdtCode').val(),
            tBchCode : $('#oetMNGChkPdtStkBalBchCode').val(),
            tWahCode : $('#oetMNGChkPdtStkBalWahCode').val()
        };
        JSxMNGPageChkPdtStkBal(aPackData);
    });

    $('#obtMNGChkPdtStkBalBrowseBch').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose(); // Hidden Pin Menu
			JCNxBrowseData('oMNGChkPdtStkBalBrowseBch');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

    function JSxMNGChkPdtStkBalBrowseBchNextFunc(ptReturn){

        $('#oetMNGChkPdtStkBalWahCode').val('');
        $('#oetMNGChkPdtStkBalWahName').val('');

        if( ptReturn != 'NULL' ){
            $('#obtMNGChkPdtStkBalBrowseWah').attr('disabled',false);
        }else{
            $('#obtMNGChkPdtStkBalBrowseWah').attr('disabled',true);
        }
        
    }

    // เลือกสาขา
    $('#obtMNGChkPdtStkBalBrowseBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oMNGChkPdtStkBalBrowseBchOption   = undefined;
            oMNGChkPdtStkBalBrowseBchOption          = oChkPdtStkBalBranchOption({
                'tReturnInputCode'  : 'oetMNGChkPdtStkBalBchCode',
                'tReturnInputName'  : 'oetMNGChkPdtStkBalBchName',
                'tBchCodeOrder'     : $('#oetMNGBchCode').val(),
                'tNextFuncName'     : 'JSxMNGChkPdtStkBalBrowseBchNextFunc',
                'aArgReturn'        : ['FTBchCode', 'FTBchName'] 
            });
            JCNxBrowseData('oMNGChkPdtStkBalBrowseBchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oChkPdtStkBalBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var tBchCodeOrder       = poDataFnc.tBchCodeOrder;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = " AND TCNMBranch.FTBchCode != '"+tBchCodeOrder+"' ";

        if(tUsrLevel != "HQ"){
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        var oOptionReturn       = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhereBch]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode DESC']
            },
            CallBack:{
                ReturnType          : 'S',
                Value               : [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text                : [tInputReturnName,"TCNMBranch_L.FTBchName"]
            },
            NextFunc:{
                FuncName            : tNextFuncName,
                ArgReturn           : aArgReturn
            }
        };
        return oOptionReturn;
    }

    // เลือกสาขา
    $('#obtMNGChkPdtStkBalBrowseWah').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oMNGChkPdtStkBalBrowseWahOption   = undefined;
            oMNGChkPdtStkBalBrowseWahOption          = oChkPdtStkBalWahouseOption({
                'tReturnInputCode'  : 'oetMNGChkPdtStkBalWahCode',
                'tReturnInputName'  : 'oetMNGChkPdtStkBalWahName',
                'tBrowseBchCode'    : $('#oetMNGChkPdtStkBalBchCode').val()
            });
            JCNxBrowseData('oMNGChkPdtStkBalBrowseWahOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oChkPdtStkBalWahouseOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tBrowseBchCode      = poDataFnc.tBrowseBchCode;

        var tSQLWhereWah = "";
        if( tBrowseBchCode != "" ){
            tSQLWhereWah += " AND TCNMWaHouse.FTBchCode = '"+tBrowseBchCode+"' ";
        }

        var oOptionReturn       = {
            Title: ['authen/user/user', 'ข้อมูลคลัง'],
            Table: {
                Master  : 'TCNMWaHouse',
                PK      : 'FTWahCode'
            },
            Join: {
                Table   : ['TCNMWaHouse_L'],
                On      : ['TCNMWaHouse_L.FTBchCode = TCNMWaHouse.FTBchCode AND TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [ tSQLWhereWah ]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['รหัสคลัง', 'ชื่อคลัง'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMWaHouse.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType          : 'S',
                Value               : [tInputReturnCode,"TCNMWaHouse.FTWahCode"],
                Text                : [tInputReturnName,"TCNMWaHouse_L.FTWahName"]
            },
        };
        return oOptionReturn;
    }

</script>