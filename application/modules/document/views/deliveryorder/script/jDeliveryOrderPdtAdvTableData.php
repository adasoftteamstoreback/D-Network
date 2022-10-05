<script type="text/javascript">
    var tDOStaDocDoc    = $('#ohdDOStaDoc').val();
    var tDOStaApvDoc    = $('#ohdDOStaApv').val();
    var tDOStaPrcStkDoc = $('#ohdDOSTaPrcStk').val();

    $(document).ready(function(){
        $("#odvDOMngDelPdtInTableDT #oliDOBtnDeleteMulti").addClass("disabled");
        // ==================================================== Event Confirm Delete PDT IN Tabel DT ===================================================
            $('#odvDOModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
                
                // var nStaSession = JCNxFuncChkSessionExpired();
                var nStaSession = 1;
                if(typeof nStaSession !== "undefined" && nStaSession == 1){
                    JCNxOpenLoading();
                    JSnDORemovePdtDTTempMultiple();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            });
        // =============================================================================================================================================
        // สถานะ Cancel
        if(tDOStaDocDoc == 3){
            // Disable Adv Table
            $(".xCNQty").attr("disabled",true);
            $(".xCNIconTable").attr("disabled",true);
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtDOBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvDOMngDelPdtInTableDT").hide();
            $('#oetDOInsertBarcode').hide();
            $('#obtDODocBrowsePdt').hide();
        }

        // สถานะ Appove
        if(tDOStaDocDoc == 1 && tDOStaApvDoc == 1 ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtDOBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvDOMngDelPdtInTableDT").hide();
            $('#oetDOInsertBarcode').hide();
            $('#obtDODocBrowsePdt').hide();
        }
    });

    // Function: Pase Text Product Item In Modal Delete
    function JSxDOTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("DO_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tDOTextDocNo   = "";
            var tDOTextSeqNo   = "";
            var tDOTextPdtCode = "";
            // var tDOTextPunCode = "";
            // var tDOTextBarCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tDOTextDocNo    += aValue.tDocNo;
                tDOTextDocNo    += " , ";

                tDOTextSeqNo    += aValue.tSeqNo;
                tDOTextSeqNo    += " , ";

                tDOTextPdtCode  += aValue.tPdtCode;
                tDOTextPdtCode  += " , ";
            });
            $('#odvDOModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDODocNoDelete').val(tDOTextDocNo);
            $('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDOSeqNoDelete').val(tDOTextSeqNo);
            $('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDOPdtCodeDelete').val(tDOTextPdtCode);
        }
    }

    // ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxDOShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("DO_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvDOMngDelPdtInTableDT #oliDOBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvDOMngDelPdtInTableDT #oliDOBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvDOMngDelPdtInTableDT #oliDOBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnDODelPdtInDTTempSingle(DOEl) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(DOEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno   = $(DOEl).parents("tr.xWPdtItem").attr("data-key");
            $(DOEl).parents("tr.xWPdtItem").remove();
            JSnDORemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnDORemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tDODocNo        = $("#oetDODocNo").val();
        var tDOBchCode      = $('#oetDOFrmBchCode').val();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docDORemovePdtInDTTmp",
            data: {
                'tBchCode'      : tDOBchCode,
                'tDocNo'        : tDODocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JCNxLayoutControll();
                    JSxDOCountPdtItems();
                    var tCheckIteminTable = $('#otbDODocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable==0){
                        $('#otbDODocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                // JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResDOnseError(jqXHR, textStatus, errorThrown);
                JSnDORemovePdtDTTempSingle(ptSeqNo,ptPdtCode)
            }
        });
    }

    
    //Functionality: Remove Comma
    function JSoDORemoveCommaData(paData){
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
    function JSnDORemovePdtDTTempMultiple(){
        // JCNxOpenLoading();
        var tDODocNo        = $("#oetDODocNo").val();
        var tDOBchCode      = $('#oetDOFrmBchCode').val();
        var aDataPdtCode    = JSoDORemoveCommaData($('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDOPdtCodeDelete').val());
        var aDataSeqNo      = JSoDORemoveCommaData($('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDOSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvDOModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvDOModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('DO_LocalItemDataDelDtTemp');
        $('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDODocNoDelete').val('');
        $('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDOSeqNoDelete').val('');
        $('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDOPdtCodeDelete').val('');
        $('#odvDOModalDelPdtInDTTempMultiple #ohdConfirmDOBarCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            // JSvDOLoadPdtDataTableHtml();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docDORemovePdtInDTTmpMulti",
            data: {
                'tBchCode'      : tDOBchCode,
                'tDocNo'        : tDODocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {

                var tCheckIteminTable = $('#otbDODocPdtAdvTableList tbody tr').length;

                if(tCheckIteminTable==0){
                    $('#otbDODocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxDOCountPdtItems();

            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResDOnseError(jqXHR, textStatus, errorThrown);
                JSnDORemovePdtDTTempMultiple()
            }
        });
    }
    
</script>