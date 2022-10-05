<script type="text/javascript">
    var tTWXStaDocDoc    = $('#ohdTWXStaDoc').val();
    var tTWXStaApvDoc    = $('#ohdTWXStaApv').val();
    var tTWXStaPrcStkDoc = $('#ohdTWXStaPrcStk').val();

    $(document).ready(function(){
        $("#odvTWXMngDelPdtInTableDT #oliTWXBtnDeleteMulti").addClass("disabled");
        // ==================================================== Event Confirm Delete PDT IN Tabel DT ===================================================
            $('#odvTWXModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
                
                // var nStaSession = JCNxFuncChkSessionExpired();
                var nStaSession = 1;
                if(typeof nStaSession !== "undefined" && nStaSession == 1){
                    JCNxOpenLoading();
                    JSnTWXRemovePdtDTTempMultiple();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            });
        // =============================================================================================================================================
        // สถานะ Cancel
        if(tTWXStaDocDoc == 3){
            // Disable Adv Table
            $(".xCNQty").attr("disabled",true);
            $(".xCNIconTable").attr("disabled",true);
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtTWXBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvTWXMngDelPdtInTableDT").hide();
            $('#oetTWXInsertBarcode').hide();
            $('#obtTWXDocBrowsePdt').hide();
        }

        // สถานะ Appove
        if(tTWXStaDocDoc == 1 && tTWXStaApvDoc == 1 ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtTWXBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvTWXMngDelPdtInTableDT").hide();
            $('#oetTWXInsertBarcode').hide();
            $('#obtTWXDocBrowsePdt').hide();
        }
    });

    // Function: Pase Text Product Item In Modal Delete
    function JSxTWXTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("TWX_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tTWXTextDocNo   = "";
            var tTWXTextSeqNo   = "";
            var tTWXTextPdtCode = "";
            // var tTWXTextPunCode = "";
            // var tTWXTextBarCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tTWXTextDocNo    += aValue.tDocNo;
                tTWXTextDocNo    += " , ";

                tTWXTextSeqNo    += aValue.tSeqNo;
                tTWXTextSeqNo    += " , ";

                tTWXTextPdtCode  += aValue.tPdtCode;
                tTWXTextPdtCode  += " , ";
            });
            $('#odvTWXModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXDocNoDelete').val(tTWXTextDocNo);
            $('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXSeqNoDelete').val(tTWXTextSeqNo);
            $('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXPdtCodeDelete').val(tTWXTextPdtCode);
        }
    }

    // ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxTWXShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("TWX_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvTWXMngDelPdtInTableDT #oliTWXBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvTWXMngDelPdtInTableDT #oliTWXBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvTWXMngDelPdtInTableDT #oliTWXBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnTWXDelPdtInDTTempSingle(TWXEl) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(TWXEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno   = $(TWXEl).parents("tr.xWPdtItem").attr("data-key");
            $(TWXEl).parents("tr.xWPdtItem").remove();
            JSnTWXRemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnTWXRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tTWXDocNo        = $("#oetTWXDocNo").val();
        var tTWXBchCode      = $('#oetTWXWahBrcCode').val();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docBKORemovePdtInDTTmp",
            data: {
                'tBchCode'      : tTWXBchCode,
                'tDocNo'        : tTWXDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JCNxLayoutControll();
                    JSxTWXCountPdtItems();
                    var tCheckIteminTable = $('#otbTWXDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable==0){
                        $('#otbTWXDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                // JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResTWXnseError(jqXHR, textStatus, errorThrown);
                JSnTWXRemovePdtDTTempSingle(ptSeqNo,ptPdtCode)
            }
        });
    }

    
    //Functionality: Remove Comma
    function JSoTWXRemoveCommaData(paData){
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
    function JSnTWXRemovePdtDTTempMultiple(){
        // JCNxOpenLoading();
        var tTWXDocNo        = $("#oetTWXDocNo").val();
        var tTWXBchCode      = $('#oetTWXWahBrcCode').val();
        var aDataPdtCode    = JSoTWXRemoveCommaData($('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXPdtCodeDelete').val());
        var aDataSeqNo      = JSoTWXRemoveCommaData($('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvTWXModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvTWXModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('TWX_LocalItemDataDelDtTemp');
        $('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXDocNoDelete').val('');
        $('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXSeqNoDelete').val('');
        $('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXPdtCodeDelete').val('');
        $('#odvTWXModalDelPdtInDTTempMultiple #ohdConfirmTWXBarCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            // JSvTWXLoadPdtDataTableHtml();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docBKORemovePdtInDTTmpMulti",
            data: {
                'tBchCode'      : tTWXBchCode,
                'tDocNo'        : tTWXDocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {

                var tCheckIteminTable = $('#otbTWXDocPdtAdvTableList tbody tr').length;

                if(tCheckIteminTable==0){
                    $('#otbTWXDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxTWXCountPdtItems();

            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResTWXnseError(jqXHR, textStatus, errorThrown);
                JSnTWXRemovePdtDTTempMultiple()
            }
        });
    }
    
</script>