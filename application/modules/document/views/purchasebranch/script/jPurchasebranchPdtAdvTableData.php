<script type="text/javascript">
    var tPRBStaDocPRBc    = $('#ohdPRBStaDoc').val();
    var tPRBStaApvDoc    = $('#ohdPRBStaApv').val();
    var tPRBStaPrcStkDoc = $('#ohdPRBSTaPrcStk').val();

    $(document).ready(function(){
        $("#odvPRBMngDelPdtInTableDT #oliPRBBtnDeleteMulti").addClass("disabled");
        // ==================================================== Event Confirm Delete PDT IN Tabel DT ===================================================
            $('#odvPRBModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
                
                // var nStaSession = JCNxFuncChkSessionExpired();
                var nStaSession = 1;
                if(typeof nStaSession !== "undefined" && nStaSession == 1){
                    JCNxOpenLoading();
                    JSnPRBRemovePdtDTTempMultiple();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            });
        // =============================================================================================================================================
        // สถานะ Cancel
        if(tPRBStaDocPRBc == 3){
            // Disable Adv Table
            $(".xCNQty").attr("disabled",true);
            $(".xCNIconTable").attr("disabled",true);
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtPRBBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvPRBMngDelPdtInTableDT").hide();
            $('#oetPRBInsertBarcode').hide();
            $('#obtPRBDocBrowsePdt').hide();
            $('#odvPRBMngAuto').hide();
        }

        // สถานะ Appove
        if(tPRBStaDocPRBc == 1 && tPRBStaApvDoc == 1 ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtPRBBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvPRBMngDelPdtInTableDT").hide();
            $('#oetPRBInsertBarcode').hide();
            $('#obtPRBDocBrowsePdt').hide();
            $('#odvPRBMngAuto').hide();
        }
    });

    // Function: Pase Text Product Item In Modal Delete
    function JSxPRBTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("PRB_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tPRBTextDocNo   = "";
            var tPRBTextSeqNo   = "";
            var tPRBTextPdtCode = "";
            // var tPRBTextPunCode = "";
            // var tPRBTextBarCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tPRBTextDocNo    += aValue.tDocNo;
                tPRBTextDocNo    += " , ";

                tPRBTextSeqNo    += aValue.tSeqNo;
                tPRBTextSeqNo    += " , ";

                tPRBTextPdtCode  += aValue.tPdtCode;
                tPRBTextPdtCode  += " , ";
            });
            $('#odvPRBModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBDocNoDelete').val(tPRBTextDocNo);
            $('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBSeqNoDelete').val(tPRBTextSeqNo);
            $('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBPdtCodeDelete').val(tPRBTextPdtCode);
        }
    }

    // ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxPRBShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("PRB_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvPRBMngDelPdtInTableDT #oliPRBBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvPRBMngDelPdtInTableDT #oliPRBBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvPRBMngDelPdtInTableDT #oliPRBBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnPRBDelPdtInDTTempSingle(DOEl) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(DOEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno   = $(DOEl).parents("tr.xWPdtItem").attr("data-key");
            $(DOEl).parents("tr.xWPdtItem").remove();
            JSnPRBRemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnPRBRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tPRBDocNo        = $("#oetPRBDocNo").val();
        var tPRBBchCode      = $('#oetPRBFrmBchCode').val();
        var tPRBSuggesType      = $('#ocmPRBSuggesAddPdt').val();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docPRBRemovePdtInDTTmp",
            data: {
                'tBchCode'      : tPRBBchCode,
                'tDocNo'        : tPRBDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode,
                'tPRBSuggesType' : tPRBSuggesType
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    for (var i = 0; i < aReturnData.Item.raItems.length; i++) {
                        aNewData = aReturnData.Item.raItems[i];
                        tPdtCode = aReturnData.Item.raItems[i]['FTPdtCode'];
                        tSugges  = aReturnData.Item.raItems[i]['Sugges'];
                        tBarCode = aReturnData.Item.raItems[i]['FTXtdBarCode'];
                        $( "tr.otr"+tPdtCode+tBarCode ).find( "td#otdPdtQtySugges"+tPdtCode ).html( tSugges );
                        // $( "tr.otr"+tPdtCode+tBarCode ).find( "input.xCNPdtEditInLine " ).val( tSugges );
                    }
                    JCNxLayoutControll();
                    JSxPRBCountPdtItems();

                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                JSxPRBMergeTable();
                // JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResDOnseError(jqXHR, textStatus, errorThrown);
                JSnPRBRemovePdtDTTempSingle(ptSeqNo,ptPdtCode)
            }
        });
    }

    
    //Functionality: Remove Comma
    function JSoPRBRemoveCommaData(paData){
        var aTexts              = paData.substring(0, paData.length - 2);
        var aDataSplit          = aTexts.split(" , ");
        var aDataSplitlength    = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

    // Function: Fucntion Call Delete Multiple Doc DT Temp
    function JSnPRBRemovePdtDTTempMultiple(){
        // JCNxOpenLoading();
        var tPRBDocNo        = $("#oetPRBDocNo").val();
        var tPRBBchCode      = $('#oetPRBFrmBchCode').val();
        var aDataPdtCode    = JSoPRBRemoveCommaData($('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBPdtCodeDelete').val());
        var aDataSeqNo      = JSoPRBRemoveCommaData($('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvPRBModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvPRBModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('PRB_LocalItemDataDelDtTemp');
        $('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBDocNoDelete').val('');
        $('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBSeqNoDelete').val('');
        $('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBPdtCodeDelete').val('');
        $('#odvPRBModalDelPdtInDTTempMultiple #ohdConfirmPRBBarCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            // JSvDOLoadPdtDataTableHtml();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docPRBRemovePdtInDTTmpMulti",
            data: {
                'tBchCode'      : tPRBBchCode,
                'tDocNo'        : tPRBDocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {

                var tCheckIteminTable = $('#otbPRBDocPdtAdvTableList tbody tr').length;

                if(tCheckIteminTable==0){
                    $('#otbPRBDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxPRBCountPdtItems();

            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResDOnseError(jqXHR, textStatus, errorThrown);
                JSnPRBRemovePdtDTTempMultiple()
            }
        });
    }
    
</script>