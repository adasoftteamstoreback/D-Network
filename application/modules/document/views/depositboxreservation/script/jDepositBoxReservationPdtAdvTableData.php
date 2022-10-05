<script type="text/javascript">
    var tDBRStaDocDoc    = $('#ohdDBRStaDoc').val();
    var tDBRStaApvDoc    = $('#ohdDBRStaApv').val();
    var tDBRStaPrcStkDoc = $('#ohdDBRSTaPrcStk').val();

    $(document).ready(function(){
        $("#odvDBRMngDelPdtInTableDT #oliDBRBtnDeleteMulti").addClass("disabled");
        // ==================================================== Event Confirm Delete PDT IN Tabel DT ===================================================
            $('#odvDBRModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
                
                // var nStaSession = JCNxFuncChkSessionExpired();
                var nStaSession = 1;
                if(typeof nStaSession !== "undefined" && nStaSession == 1){
                    JCNxOpenLoading();
                    JSnDBRRemovePdtDTTempMultiple();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            });
        // =============================================================================================================================================
        // สถานะ Cancel
        if(tDBRStaDocDoc == 3){
            // Disable Adv Table
            $(".xCNQty").attr("disabled",true);
            $(".xCNIconTable").attr("disabled",true);
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtDBRBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvDBRMngDelPdtInTableDT").hide();
            $('#oetDBRInsertBarcode').hide();
            $('#obtDBRDocBrowsePdt').hide();
            $("#oetRecommendLay").hide();
        }

        // สถานะ Appove
        if(tDBRStaDocDoc == 1 && tDBRStaApvDoc == 1 ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtDBRBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $("#odvDBRMngDelPdtInTableDT").hide();
            $('#oetDBRInsertBarcode').hide();
            $('#obtDBRDocBrowsePdt').hide();
            $("#oetRecommendLay").hide();
        }
    });

    // Function: Pase Text Product Item In Modal Delete
    function JSxDBRTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("DBR_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tDBRTextDocNo   = "";
            var tDBRTextSeqNo   = "";
            var tDBRTextPdtCode = "";
            // var tDBRTextPunCode = "";
            // var tDBRTextBarCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tDBRTextDocNo    += aValue.tDocNo;
                tDBRTextDocNo    += " , ";

                tDBRTextSeqNo    += aValue.tSeqNo;
                tDBRTextSeqNo    += " , ";

                tDBRTextPdtCode  += aValue.tPdtCode;
                tDBRTextPdtCode  += " , ";
            });
            $('#odvDBRModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRDocNoDelete').val(tDBRTextDocNo);
            $('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRSeqNoDelete').val(tDBRTextSeqNo);
            $('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRPdtCodeDelete').val(tDBRTextPdtCode);
        }
    }

    // ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxDBRShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("DBR_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvDBRMngDelPdtInTableDT #oliDBRBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvDBRMngDelPdtInTableDT #oliDBRBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvDBRMngDelPdtInTableDT #oliDBRBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnDBRDelPdtInDTTempSingle(DBREl) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(DBREl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno   = $(DBREl).parents("tr.xWPdtItem").attr("data-key");
            $(DBREl).parents("tr.xWPdtItem").remove();
            JSnDBRRemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnDBRRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tDBRDocNo        = $("#oetDBRDocNo").val();
        var tDBRBchCode      = $('#oetDBRFrmBchCode').val();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docDBRRemovePdtInDTTmp",
            data: {
                'tBchCode'      : tDBRBchCode,
                'tDocNo'        : tDBRDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JCNxLayoutControll();
                    JSxDBRCountPdtItems();
                    var tCheckIteminTable = $('#otbDBRDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable==0){
                        $('#otbDBRDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                // JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResDBRnseError(jqXHR, textStatus, errorThrown);
                JSnDBRRemovePdtDTTempSingle(ptSeqNo,ptPdtCode)
            }
        });
    }

    
    //Functionality: Remove Comma
    function JSoDBRRemoveCommaData(paData){
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
    function JSnDBRRemovePdtDTTempMultiple(){
        // JCNxOpenLoading();
        var tDBRDocNo        = $("#oetDBRDocNo").val();
        var tDBRBchCode      = $('#oetDBRFrmBchCode').val();
        var aDataPdtCode    = JSoDBRRemoveCommaData($('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRPdtCodeDelete').val());
        var aDataSeqNo      = JSoDBRRemoveCommaData($('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvDBRModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvDBRModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('DBR_LocalItemDataDelDtTemp');
        $('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRDocNoDelete').val('');
        $('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRSeqNoDelete').val('');
        $('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRPdtCodeDelete').val('');
        $('#odvDBRModalDelPdtInDTTempMultiple #ohdConfirmDBRBarCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            // JSvDBRLoadPdtDataTableHtml();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docDBRRemovePdtInDTTmpMulti",
            data: {
                'tBchCode'      : tDBRBchCode,
                'tDocNo'        : tDBRDocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {

                var tCheckIteminTable = $('#otbDBRDocPdtAdvTableList tbody tr').length;

                if(tCheckIteminTable==0){
                    $('#otbDBRDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxDBRCountPdtItems();

            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResDBRnseError(jqXHR, textStatus, errorThrown);
                JSnDBRRemovePdtDTTempMultiple()
            }
        });
    }

    function JSnDBRBrowseBox(ptElm,nStaBrowseType) 
    {  
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        let tDBRSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        // JSxCallDBRRefIntDoc(tDocType);
        JSxCallDBRBrowseBox(tDBRSeqNo,nStaBrowseType);
    }

    //Browse ตู้ฝาก
    function JSxCallDBRBrowseBox(ptDBRSeqNo,pnStaBrowseType){
            var tBCHCode = $('#oetDBRFrmBchCode').val();
            var tBCHName = $('#oetDBRFrmBchName').val();
            var tPosCode = $('#ohdBox'+ptDBRSeqNo).val();
            var tShpCode = $('#ohdShopBox'+ptDBRSeqNo).val();
            var tPosName = $('#oetBox'+ptDBRSeqNo).val();

            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docDBRCallBrowseBox",
                data: {
                    'nStaBrowseType'    : pnStaBrowseType,
                    'tSeqNo'            : ptDBRSeqNo,
                    'tBCHCode'          : tBCHCode,
                    'tBCHName'          : tBCHName,
                    'tPosCode'          : tPosCode,
                    'tShpCode'          : tShpCode,
                    'tPosName'          : tPosName
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JCNxCloseLoading();
                    $('#odvDBRFromBrowseBox').html(oResult);
                    $('#odvDBRModalBrowseBox').modal({
                        backdrop : 'static' , 
                        show : true
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
</script>