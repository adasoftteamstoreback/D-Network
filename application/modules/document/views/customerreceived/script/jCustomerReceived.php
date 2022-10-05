<script type="text/javascript">
    var tCRVStaDocDoc    = $('#ohdCRVStaDoc').val();
    var tCRVStaApvDoc    = $('#ohdCRVStaApv').val();
    var tCRVStaPrcStkDoc = $('#ohdCRVSTaPrcStk').val();

    $(document).ready(function(){
        $("#odvCRVMngDelPdtInTableDT #oliCRVBtnDeleteMulti").addClass("disabled");
        // ==================================================== Event Confirm Delete PDT IN Tabel DT ===================================================
            $('#odvCRVModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
                
                // var nStaSession = JCNxFuncChkSessionExpired();
                var nStaSession = 1;
                if(typeof nStaSession !== "undefined" && nStaSession == 1){
                    JCNxOpenLoading();
                    JSnCRVRemovePdtDTTempMultiple();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            });
        // =============================================================================================================================================
        // สถานะ Cancel
        if(tCRVStaDocDoc == 3){
            // Disable Adv Table
            $(".xCNQty").attr("disabled",true);
            $(".xCNIconTable").attr("disabled",true);
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtCRVBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvCRVMngDelPdtInTableDT").hide();
            $('#oetCRVInsertBarcode').hide();
            $('#obtCRVDocBrowsePdt').hide();
        }

        // สถานะ Appove
        if(tCRVStaDocDoc == 1 && tCRVStaApvDoc == 1 ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtCRVBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvCRVMngDelPdtInTableDT").hide();
            $('#oetCRVInsertBarcode').hide();
            $('#obtCRVDocBrowsePdt').hide();
        }
    });

    // Function: Pase Text Product Item In Modal Delete
    function JSxCRVTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("CRV_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tCRVTextDocNo   = "";
            var tCRVTextSeqNo   = "";
            var tCRVTextPdtCode = "";
            // var tCRVTextPunCode = "";
            // var tCRVTextBarCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tCRVTextDocNo    += aValue.tDocNo;
                tCRVTextDocNo    += " , ";

                tCRVTextSeqNo    += aValue.tSeqNo;
                tCRVTextSeqNo    += " , ";

                tCRVTextPdtCode  += aValue.tPdtCode;
                tCRVTextPdtCode  += " , ";
            });
            $('#odvCRVModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVDocNoDelete').val(tCRVTextDocNo);
            $('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVSeqNoDelete').val(tCRVTextSeqNo);
            $('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVPdtCodeDelete').val(tCRVTextPdtCode);
        }
    }

    // ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxCRVShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("CRV_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvCRVMngDelPdtInTableDT #oliCRVBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvCRVMngDelPdtInTableDT #oliCRVBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvCRVMngDelPdtInTableDT #oliCRVBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnCRVDelPdtInDTTempSingle(CRVEl) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(CRVEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno   = $(CRVEl).parents("tr.xWPdtItem").attr("data-key");
            $(CRVEl).parents("tr.xWPdtItem").remove();
            JSnCRVRemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnCRVRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tCRVDocNo        = $("#oetCRVDocNo").val();
        var tCRVBchCode      = $('#oetCRVFrmBchCode').val();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docCRVRemovePdtInDTTmp",
            data: {
                'tBchCode'      : tCRVBchCode,
                'tDocNo'        : tCRVDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JCNxLayoutControll();
                    JSxCRVCountPdtItems();
                    var tCheckIteminTable = $('#otbCRVDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable==0){
                        $('#otbCRVDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                // JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResCRVnseError(jqXHR, textStatus, errorThrown);
                JSnCRVRemovePdtDTTempSingle(ptSeqNo,ptPdtCode)
            }
        });
    }

    
    //Functionality: Remove Comma
    function JSoCRVRemoveCommaData(paData){
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
    function JSnCRVRemovePdtDTTempMultiple(){
        // JCNxOpenLoading();
        var tCRVDocNo        = $("#oetCRVDocNo").val();
        var tCRVBchCode      = $('#oetCRVFrmBchCode').val();
        var aDataPdtCode    = JSoCRVRemoveCommaData($('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVPdtCodeDelete').val());
        var aDataSeqNo      = JSoCRVRemoveCommaData($('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvCRVModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvCRVModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('CRV_LocalItemDataDelDtTemp');
        $('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVDocNoDelete').val('');
        $('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVSeqNoDelete').val('');
        $('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVPdtCodeDelete').val('');
        $('#odvCRVModalDelPdtInDTTempMultiple #ohdConfirmCRVBarCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            // JSvCRVLoadPdtDataTableHtml();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docCRVRemovePdtInDTTmpMulti",
            data: {
                'tBchCode'      : tCRVBchCode,
                'tDocNo'        : tCRVDocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {

                var tCheckIteminTable = $('#otbCRVDocPdtAdvTableList tbody tr').length;

                if(tCheckIteminTable==0){
                    $('#otbCRVDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxCRVCountPdtItems();

            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResCRVnseError(jqXHR, textStatus, errorThrown);
                JSnCRVRemovePdtDTTempMultiple()
            }
        });
    }
    
</script>