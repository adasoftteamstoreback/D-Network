<script type="text/javascript">
    var tSOStaDocDoc    = $('#ohdSOStaDoc').val();
    var tSOStaApvDoc    = $('#ohdSOStaApv').val();
    var tSOStaPrcStkDoc = $('#ohdSOStaPrcStk').val();

    $(document).ready(function(){
        // ==================================================== Event Confirm Delete PDT IN Tabel DT ===================================================
            $('#odvDPSModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
                // var nStaSession = JCNxFuncChkSessionExpired();
                var nStaSession = 1;
                if(typeof nStaSession !== "undefined" && nStaSession == 1){
                    JCNxOpenLoading();
                    JSnDPSRemovePdtDTTempMultiple();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            });
        // =============================================================================================================================================
    });

    // Functionality: ฟังก์ชั่น Save Edit In Line Pdt Doc DT Temp
    // Parameters: Behind Next Func Edit Value
    // Creator: 02/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View
    // ReturnType : View
    function JSxSOSaveEditInline(paParams){
        console.log('JSxSOSaveEditInline: ', paParams);
        var oThisEl         = paParams['Element'];
        var tThisDisChgText = $(oThisEl).parents('tr.xWPdtItem').find('td label.xWPIDisChgDT').text().trim();
        if(tThisDisChgText == ''){
            console.log('No Have Dis/Chage DT');
            // ไม่มีลด/ชาร์จ
            var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
            var tFieldName  = paParams.DataAttribute[0]['data-field'];
            var tValue      = accounting.unformat(paParams.VeluesInline);
            var bIsDelDTDis = false;
            FSvDPSEditPdtIntoTableDT(nSeqNo,tFieldName,tValue,bIsDelDTDis); 
        }else{
            console.log('Have Dis/Chage DT');
            // มีลด/ชาร์จ
            $('#odvSOModalConfirmDeleteDTDis').modal({
                backdrop: 'static',
                show: true
            });
            
            $('#odvSOModalConfirmDeleteDTDis #obtSOConfirmDeleteDTDis').unbind();
            $('#odvSOModalConfirmDeleteDTDis #obtSOConfirmDeleteDTDis').one('click',function(){
                $('#odvSOModalConfirmDeleteDTDis').modal('hide');
                $('.modal-backdrop').remove();
                var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
                var tFieldName  = paParams.DataAttribute[0]['data-field'];
                var tValue      = accounting.unformat(paParams.VeluesInline);
                var bIsDelDTDis = true;
                FSvDPSEditPdtIntoTableDT(nSeqNo,tFieldName,tValue,bIsDelDTDis);
            });

            $('#odvSOModalConfirmDeleteDTDis #obtSOCancelDeleteDTDis').unbind();
            $('#odvSOModalConfirmDeleteDTDis #obtSOCancelDeleteDTDis').one('click',function(){
                $('.modal-backdrop').remove();
                JSvSOLoadPdtDataTableHtml();
            });

            $('#odvSOModalConfirmDeleteDTDis').modal('show')
        }
    }

    // Functionality: Pase Text Product Item In Modal Delete
    // Parameters: Event Click List Table Delete Mutiple
    // Creator: 26/07/2019 Wasin(Yoshi)
    // Return: -
    // ReturnType: -
    function JSxDPSTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("SO_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tSOTextDocNo   = "";
            var tSOTextSeqNo   = "";
            var tSOTextPdtCode = "";
            // var tSOTextPunCode = "";
            // var tSOTextBarCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tSOTextDocNo    += aValue.tDocNo;
                tSOTextDocNo    += " , ";

                tSOTextSeqNo    += aValue.tSeqNo;
                tSOTextSeqNo    += " , ";

                tSOTextPdtCode  += aValue.tPdtCode;
                tSOTextPdtCode  += " , ";

                // tSOTextPunCode  += aValue.tPunCode;
                // tSOTextPunCode  += " , ";

                // tSOTextBarCode  += aValue.tBarCode;
                // tSOTextBarCode  += " , ";
            });
            $('#odvDPSModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSODocNoDelete').val(tSOTextDocNo);
            $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOSeqNoDelete').val(tSOTextSeqNo);
            $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOPdtCodeDelete').val(tSOTextPdtCode);
            // $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOBarCodeDelete').val(tSOTextBarCode);
            // $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOPunCodeDelete').val(tSOTextPunCode);
        }
    }

    // Functionality: Show Button Delete Multiple DT Temp
    // Parameters: Event Click List Table Delete Mutiple
    // Creator: 26/07/2019 Wasin(Yoshi)
    // Return: -
    // ReturnType: -
    function JSxSOShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("SO_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvSOMngDelPdtInTableDT #oliDPSBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvSOMngDelPdtInTableDT #oliDPSBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvSOMngDelPdtInTableDT #oliDPSBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //Functionality: Function Delete Product In Doc DT Temp
    //Parameters: object Event Click
    //Creator: 02/07/2021 Off
    // Return: View
    // ReturnType : View
    function JSnDPSDelPdtInDTTempSingle(poEl) {
        // var nStaSession = JCNxFuncChkSessionExpired();
        
        var tNamePdt = $(poEl).parents("tr.xWPdtItem").attr("data-pdtname");
        var tNameRef = $("#oetDPSRefInAllName").val();
        if(tNameRef == tNamePdt){
            $('#oetDPSRefInAllName').val('');
            $('#ohdDPSRefInAllCode').val('');
            $('#oetDPSRefIntDate').val('');
        }

        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(poEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno   = $(poEl).parents("tr.xWPdtItem").attr("data-key");
            $(poEl).parents("tr.xWPdtItem").remove();
            JSnDPSRemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality: Function Remove Product In Doc DT Temp
    // Parameters: Event Btn Click Call Edit Document
    // Creator: 02/07/2021 Off
    // Return: Status Add/Update Document
    // ReturnType: object
    function JSnDPSRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tSODocNo        = $("#oetDPSDocNo").val();
        var tSOBchCode      = $('#oetDPSFrmBchCode').val();
        var tSOVatInOrEx    = $('#ocmDPSFrmSplInfoVatInOrEx').val();

        JSxRendercalculate();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "dcmDPSRemovePdtInDTTmp",
            data: {
                'tBchCode'      : tSOBchCode,
                'tDocNo'        : tSODocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode,
                'tVatInOrEx'    : tSOVatInOrEx,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdSOUsrCode'        : $('#ohdSOUsrCode').val(),
                'ohdSOLangEdit'       : $('#ohdSOLangEdit').val(),
                'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
                'ohdSOSesUsrBchCode'  : $('#ohdSOSesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    // JSvSOLoadPdtDataTableHtml();
                    JCNxLayoutControll();
                    var tCheckIteminTable = $('#otbDPSDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable==0){
                    $('#otbDPSDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                // JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
                JSnDPSRemovePdtDTTempSingle(ptSeqNo,ptPdtCode)
            }
        });
    }

    
    //Functionality: Remove Comma
    //Parameters: Event Button Delete All
    //Creator: 26/07/2019 Wasin
    //Return:  object Status Delete
    //Return Type: object
    function JSoSORemoveCommaData(paData){
        var aTexts              = paData.substring(0, paData.length - 2);
        var aDataSplit          = aTexts.split(" , ");
        var aDataSplitlength    = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

    // Functionality: Fucntion Call Delete Multiple Doc DT Temp
    // Parameters: Event Click List Table Delete Mutiple
    // Creator: 26/07/2019 Wasin(Yoshi)
    // Last Update: 27/05/2020 Napat(Jame)
    // Return: array Data Status Delete
    // ReturnType: Array
    function JSnDPSRemovePdtDTTempMultiple(){
        // JCNxOpenLoading();
        var tDPSDocNo        = $("#oetSODocNo").val();
        var tDPSBchCode      = $('#oetSOFrmBchCode').val();
        var tDPSVatInOrEx    = $('#ocmSOFrmSplInfoVatInOrEx').val();
        var aDataPdtCode    = JSoSORemoveCommaData($('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOPdtCodeDelete').val());
        var aDataSeqNo      = JSoSORemoveCommaData($('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvDPSModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvDPSModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('SO_LocalItemDataDelDtTemp');
        $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSODocNoDelete').val('');
        $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOSeqNoDelete').val('');
        $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOPdtCodeDelete').val('');
        // $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOBarCodeDelete').val('');
        // $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOPunCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            // JSvSOLoadPdtDataTableHtml();
            JCNxLayoutControll();
        }, 500);

        JSxRendercalculate();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "dcmDPSRemovePdtInDTTmpMulti",
            data: {
                'ptDPSBchCode'   : tDPSBchCode,
                'ptDPSDocNo'     : tDPSDocNo,
                'ptDPSVatInOrEx' : tDPSVatInOrEx,
                'paDataPdtCode' : aDataPdtCode,
                // 'paDataPunCode' : aDataPunCode,
                'paDataSeqNo'   : aDataSeqNo,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdSOUsrCode'        : $('#ohdSOUsrCode').val(),
                'ohdSOLangEdit'       : $('#ohdSOLangEdit').val(),
                'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
                'ohdSOSesUsrBchCode'  : $('#ohdSOSesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {

                var tCheckIteminTable = $('#otbSODocPdtAdvTableList tbody tr').length;

                if(tCheckIteminTable==0){
                    $('#otbSODocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                // var aReturnData = JSON.parse(tResult);
                // if(aReturnData['nStaEvent'] == '1'){
                //     $('#odvDPSModalDelPdtInDTTempMultiple').modal('hide');
                //     $('#odvDPSModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
                //     localStorage.removeItem('SO_LocalItemDataDelDtTemp');
                //     $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSODocNoDelete').val('');
                //     $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOSeqNoDelete').val('');
                //     $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOPdtCodeDelete').val('');
                //     // $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOBarCodeDelete').val('');
                //     // $('#odvDPSModalDelPdtInDTTempMultiple #ohdConfirmSOPunCodeDelete').val('');
                //     setTimeout(function(){
                //         $('.modal-backdrop').remove();
                //         // JSvSOLoadPdtDataTableHtml();
                //         JCNxLayoutControll();
                //     }, 500);
                // }else{
                //     var tMessageError   = aReturnData['tStaMessg'];
                //     FSvCMNSetMsgErrorDialog(tMessageError);
                //     // JCNxCloseLoading();
                // }
            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
                JSnDPSRemovePdtDTTempMultiple()
            }
        });
    }

    







</script>