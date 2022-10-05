<script type="text/javascript">
    var tPRSStaDocDoc    = $('#ohdPRSStaDoc').val();
    var tPRSStaApvDoc    = $('#ohdPRSStaApv').val();
    var tPRSTypeDocument = $('#ohdPRSTypeDocument').val();

    $(document).ready(function(){
        $("#odvPRSMngDelPdtInTableDT #oliPRSBtnDeleteMulti").addClass("disabled");

        $('#odvPRSModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = 1;
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JCNxOpenLoading();
                JSnPRSRemovePdtDTTempMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        if(tPRSTypeDocument == 1){ //ใบขอซื้อแบบสำนักงานใหญ่ 
            // สถานะ Cancel
            if(tPRSStaDocDoc == 3){
                // Disable Adv Table
                $(".xCNQty").attr("disabled",true);
                $(".xCNIconTable").attr("disabled",true);
                $(".xCNIconTable").addClass("xCNDocDisabled");
                $(".xCNIconTable").attr("onclick", "").unbind("click");
                $('.xCNPdtEditInLine').attr('readonly',true);
                $('#obtPRSBrowseCustomer').attr('disabled',true);
                $('.ocbListItem').attr('disabled',true);
                $("#odvPRSMngDelPdtInTableDT").hide();
                $('#oetPRSInsertBarcode').hide();
                $('#obtPRSDocBrowsePdt').hide();
            }

            // สถานะ Approve
            if(tPRSStaDocDoc == 1 && tPRSStaApvDoc == 1 ){
                $('.xCNBTNPrimeryDisChgPlus').hide();
                $(".xCNIconTable").addClass("xCNDocDisabled");
                $(".xCNIconTable").attr("onclick", "").unbind("click");
                $('.xCNPdtEditInLine').attr('readonly',true);
                $('#obtPRSBrowseCustomer').attr('disabled',true);
                $('.ocbListItem').attr('disabled',true);
                $("#odvPRSMngDelPdtInTableDT").hide();
                $('#oetPRSInsertBarcode').hide();
                $('#obtPRSDocBrowsePdt').hide();
            }
        }else{ //ใบขอซื้อแบบแฟรนไชส์
            $('#oetPRSInsertBarcode').hide();
            $(".xCNQty").attr("disabled",true);
            $('.xCNMsgDeletePDTInTableDT').removeClass('col-lg-4').addClass('col-lg-8');
            $('.xCNMsgInsertPDTInTableDT').hide();
        }
    });

    // Function: Pase Text Product Item In Modal Delete
    function JSxPRSTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("PRS_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tPRSTextDocNo   = "";
            var tPRSTextSeqNo   = "";
            var tPRSTextPdtCode = "";
            // var tPRSTextPunCode = "";
            // var tPRSTextBarCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tPRSTextDocNo    += aValue.tDocNo;
                tPRSTextDocNo    += " , ";

                tPRSTextSeqNo    += aValue.tSeqNo;
                tPRSTextSeqNo    += " , ";

                tPRSTextPdtCode  += aValue.tPdtCode;
                tPRSTextPdtCode  += " , ";
            });
            $('#odvPRSModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSDocNoDelete').val(tPRSTextDocNo);
            $('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSSeqNoDelete').val(tPRSTextSeqNo);
            $('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSPdtCodeDelete').val(tPRSTextPdtCode);
        }
    }

    // ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxPRSShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("PRS_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvPRSMngDelPdtInTableDT #oliPRSBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvPRSMngDelPdtInTableDT #oliPRSBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvPRSMngDelPdtInTableDT #oliPRSBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnPRSDelPdtInDTTempSingle(PRSEl) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(PRSEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno   = $(PRSEl).parents("tr.xWPdtItem").attr("data-key");
            $(PRSEl).parents("tr.xWPdtItem").remove();
            JSnPRSRemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnPRSRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tPRSDocNo        = $("#oetPRSDocNo").val();
        var tPRSBchCode      = $('#oetPRSFrmBchCode').val();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docPRSRemovePdtInDTTmp",
            data: {
                'tBchCode'      : tPRSBchCode,
                'tDocNo'        : tPRSDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JCNxLayoutControll();
                    JSxPRSCountPdtItems();
                    var tCheckIteminTable = $('#otbPRSDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable==0){
                        $('#otbPRSDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                // JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResPRSnseError(jqXHR, textStatus, errorThrown);
                JSnPRSRemovePdtDTTempSingle(ptSeqNo,ptPdtCode)
            }
        });
    }

    //Functionality: Remove Comma
    function JSoPRSRemoveCommaData(paData){
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
    function JSnPRSRemovePdtDTTempMultiple(){
        // JCNxOpenLoading();
        var tPRSDocNo        = $("#oetPRSDocNo").val();
        var tPRSBchCode      = $('#oetPRSFrmBchCode').val();
        var aDataPdtCode    = JSoPRSRemoveCommaData($('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSPdtCodeDelete').val());
        var aDataSeqNo      = JSoPRSRemoveCommaData($('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvPRSModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvPRSModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('PRS_LocalItemDataDelDtTemp');
        $('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSDocNoDelete').val('');
        $('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSSeqNoDelete').val('');
        $('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSPdtCodeDelete').val('');
        $('#odvPRSModalDelPdtInDTTempMultiple #ohdConfirmPRSBarCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            // JSvPRSLoadPdtDataTableHtml();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docPRSRemovePdtInDTTmpMulti",
            data: {
                'tBchCode'      : tPRSBchCode,
                'tDocNo'        : tPRSDocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {

                var tCheckIteminTable = $('#otbPRSDocPdtAdvTableList tbody tr').length;

                if(tCheckIteminTable==0){
                    $('#otbPRSDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxPRSCountPdtItems();

            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResPRSnseError(jqXHR, textStatus, errorThrown);
                JSnPRSRemovePdtDTTempMultiple()
            }
        });
    }
    
</script>