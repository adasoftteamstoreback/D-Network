<script type="text/javascript">
    var tTbxStaDocDoc    = $('#ohdXthStaDoc').val();
    var tTbxStaApvDoc    = $('#ohdXthStaApv').val();
    var tTbxStaPrcStkDoc = $('#ohdXthStaPrcStk').val();

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
    
    $(document).ready(function(){
    
        //Event Confirm Delete PDT IN Tabel DT 
        $('#odvTbxModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSnTBXRemovePdtDTTempMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });

    // Functionality: ฟังก์ชั่น Save Edit In Line Pdt Doc DT Temp
    // Parameters: Behind Next Func Edit Value
    // Creator: 02/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View
    // ReturnType : View
    function JSxTBXSaveEditInline(paParams){
        console.log('JSxTBXSaveEditInline: ', paParams);
        var oThisEl         = paParams['Element'];
        var tThisDisChgText = $(oThisEl).parents('tr.xWPdtItem').find('td label.xWPIDisChgDT').text().trim();
        if(tThisDisChgText == ''){
            console.log('No Have Dis/Chage DT');
            // ไม่มีลด/ชาร์จ
            var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
            var tFieldName  = paParams.DataAttribute[0]['data-field'];
            var tValue      = accounting.unformat(paParams.VeluesInline);
            var bIsDelDTDis = false;
            FSvTBXEditPdtIntoTableDT(nSeqNo,tFieldName,tValue,bIsDelDTDis); 
        }else{
            console.log('Have Dis/Chage DT');
            // มีลด/ชาร์จ
            $('#odvTbxModalConfirmDeleteDTDis').modal({
                backdrop: 'static',
                show: true
            });
            
            $('#odvTbxModalConfirmDeleteDTDis #obtPIConfirmDeleteDTDis').unbind();
            $('#odvTbxModalConfirmDeleteDTDis #obtPIConfirmDeleteDTDis').one('click',function(){
                $('#odvTbxModalConfirmDeleteDTDis').modal('hide');
                $('.modal-backdrop').remove();
                var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
                var tFieldName  = paParams.DataAttribute[0]['data-field'];
                var tValue      = accounting.unformat(paParams.VeluesInline);
                var bIsDelDTDis = true;
                FSvTBXEditPdtIntoTableDT(nSeqNo,tFieldName,tValue,bIsDelDTDis);
            });

            $('#odvTbxModalConfirmDeleteDTDis #obtPICancelDeleteDTDis').unbind();
            $('#odvTbxModalConfirmDeleteDTDis #obtPICancelDeleteDTDis').one('click',function(){
                $('.modal-backdrop').remove();
                JSvTBLoadPdtDataTableHtml();
            });

            $('#odvTbxModalConfirmDeleteDTDis').modal('show')
        }
    }

    $('.xCNPdtEditInLine').off().on('change keyup',function(e){
        if(e.type === 'change' || e.keyCode === 13){
            if(e.keyCode === 13){
            var tNextElement = $(this).closest('form').find('input[type=text]');
            var tNextElementID=   tNextElement.eq( tNextElement.index(this)+ 1 ).attr('id');
            console.log(tNextElementID);
            var tValueNext     = parseFloat($('#'+tNextElementID).val());
            $('#'+tNextElementID).val(tValueNext);
            $('#'+tNextElementID).focus();
            $('#'+tNextElementID).select();
            }
            let oParameters = {};
            oParameters.VeluesInline = $(this).val();
            oParameters.Element = $(this);
            oParameters.DataAttribute = [];
            let poParameter = {};
            poParameter.DataAttribute = ['data-field', 'data-seq'];
            for(let nI = 0;nI<poParameter.DataAttribute.length;nI++){
                let aDOCPdtTableTDChildElementAttr = $(this).attr(poParameter.DataAttribute[nI]);
                if(aDOCPdtTableTDChildElementAttr!==undefined && aDOCPdtTableTDChildElementAttr!=""){
                    oParameters.DataAttribute[nI] = {[poParameter.DataAttribute[nI]]:$(this).attr(poParameter.DataAttribute[nI])};
                }
            }
            // console.log(oParameters);
            JSxTBXSaveEditInline(oParameters);


        }
    });

    //Functionality: Call Modal Dis/Chage Doc DT
    //Parameters: object Event Click
    //Creator: 02/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View
    // ReturnType : View
    function JCNvTBXCallModalDisChagDT(poEl){
        var nStaSession = 1;
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tDocNo          = $(poEl).parents('.xWPdtItem').data('docno');
            var tPdtCode        = $(poEl).parents('.xWPdtItem').data('pdtcode');
            var tPdtName        = $(poEl).parents('.xWPdtItem').data('pdtname');
            var tPunCode        = $(poEl).parents('.xWPdtItem').data('puncode');
            var tNet            = $(poEl).parents('.xWPdtItem').data('netafhd');
            var tSetPrice       = $(poEl).parents('.xWPdtItem').attr('data-setprice'); //$(poEl).parents('.xWPdtItem').data('setprice');
            var tQty            = $(poEl).parents('.xWPdtItem').attr('data-qty'); //$(poEl).parents('.xWPdtItem').data('qty');
            var tStaDis         = $(poEl).parents('.xWPdtItem').data('stadis');
            var tSeqNo          = $(poEl).parents('.xWPdtItem').data('seqno');
            var bHaveDisChgDT   = $(poEl).parents('.xWPIDisChgDTForm').find('label.xWPIDisChgDT').text() == ''? false : true;
            window.DisChgDataRowDT  = {
                tDocNo          : tDocNo,
                tPdtCode        : tPdtCode,
                tPdtName        : tPdtName,
                tPunCode        : tPunCode,
                tNet            : tNet,
                tSetPrice       : tSetPrice,
                tQty            : tQty,
                tStadis         : tStaDis,
                tSeqNo          : tSeqNo,
                bHaveDisChgDT   : bHaveDisChgDT
            };
            var oPIDisChgParams = {
                DisChgType: 'disChgDT'
            };
            JSxTBXOpenDisChgPanel(oPIDisChgParams);
        }else{
            JCNxShowMsgSessionExpired();
        }   
    }

    // Functionality: Pase Text Product Item In Modal Delete
    // Parameters: Event Click List Table Delete Mutiple
    // Creator: 26/07/2019 Wasin(Yoshi)
    // Return: -
    // ReturnType: -
    function JSxTBXTextInModalDelPdtDtTemp(){

        var aArrayConvert   = [JSON.parse(localStorage.getItem("TBX_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tTbxTextDocNo   = "";
            var tTbxTextSeqNo   = "";
            var tTbxTextPdtCode = "";
            var tTbxTextPunCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tTbxTextDocNo    += aValue.tDocNo;
                tTbxTextDocNo    += " , ";

                tTbxTextSeqNo    += aValue.tSeqNo;
                tTbxTextSeqNo    += " , ";

                tTbxTextPdtCode  += aValue.tPdtCode;
                tTbxTextPdtCode  += " , ";

                tTbxTextPunCode  += aValue.tPunCode;
                tTbxTextPunCode  += " , ";
            });
            $('#odvTbxModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmPIDocNoDelete').val(tTbxTextDocNo);
            $('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmPISeqNoDelete').val(tTbxTextSeqNo);
            $('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmPIPdtCodeDelete').val(tTbxTextPdtCode);
            $('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmPIPunCodeDelete').val(tTbxTextPunCode);
        }
    }

    // Functionality: Show Button Delete Multiple DT Temp
    // Parameters: Event Click List Table Delete Mutiple
    // Creator: 26/07/2019 Wasin(Yoshi)
    // Return: -
    // ReturnType: -
    function JSxTBXShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("TBX_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#oliTbxBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
  
            if(nNumOfArr > 1) {
        
                $("#oliTbxBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#oliTbxBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //Functionality: Function Delete Product In Doc DT Temp
    //Parameters: object Event Click
    //Creator: 04/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View
    // ReturnType : View
    function JSnTBXDelPdtInDTTempSingle(poEl) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal    = $(poEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno  = $(poEl).parents("tr.xWPdtItem").attr("data-seqno");
            $(poEl).parents("tr.xWPdtItem").remove();
            JSnTBXRemovePdtDTTempSingle(tSeqno, tVal);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality: Function Remove Product In Doc DT Temp
    // Parameters: Event Btn Click Call Edit Document
    // Creator: 04/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: Status Add/Update Document
    // ReturnType: object
    function JSnTBXRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tTbxDocNo        = $("#oetXthDocNo").val();
        var tTbxBchCode      = $('#ohdTbxBchCode').val();
        var tTbxVatInOrEx    = 1;

        JSxRendercalculate();
        JCNxOpenLoading();

        $.ajax({
            type: "POST",
            url: "dcmPIRemovePdtInDTTmp",
            data: {
                'tBchCode'      : tTbxBchCode,
                'tDocNo'        : tTbxDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode,
                'tVatInOrEx'    : tTbxVatInOrEx,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdPIUsrCode'      : $('#ohdPIUsrCode').val(),
                'ohdPILangEdit'     : $('#ohdPILangEdit').val(),
                'ohdSesUsrLevel'    : $('#ohdSesUsrLevel').val(),
                'ohdPISesUsrBchCode'  : $('#ohdPISesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JSvTBLoadPdtDataTableHtml();
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
    }

    
    //Functionality: Remove Comma
    //Parameters: Event Button Delete All
    //Creator: 26/07/2019 Wasin
    //Return:  object Status Delete
    //Return Type: object
    function JSoTBXRemoveCommaData(paData){
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
    // Return: array Data Status Delete
    // ReturnType: Array
    function JSnTBXRemovePdtDTTempMultiple(){

        JCNxOpenLoading();
        var tTbxDocNo        = $("#oetXthDocNo").val();
        var tTbxBchCode      = $('#ohdTbxBchCode').val();
        var tTbxVatInOrEx    = 1;
        var aDataPdtCode    = JSoTBXRemoveCommaData($('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmPIPdtCodeDelete').val());
        var aDataSeqNo      = JSoTBXRemoveCommaData($('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmPISeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvTbxModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvTbxModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('TBX_LocalItemDataDelDtTemp');
        $('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmSODocNoDelete').val('');
        $('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmSOSeqNoDelete').val('');
        $('#odvTbxModalDelPdtInDTTempMultiple #ohdConfirmSOPdtCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            JCNxLayoutControll();
        }, 500);

        JSxRendercalculate();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "TBXPdtMultiDeleteEvent",
            data: {
                'tTbxBchCode'   : tTbxBchCode,
                'tDocNo'     : tTbxDocNo,
                'tTbxVatInOrEx' : tTbxVatInOrEx,
                'tPdtCode' : aDataPdtCode,
                'tSeqCode'   : aDataSeqNo,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val()
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var tCheckIteminTable = $('#otbTbxDocPdtAdvTableList tbody tr').length;
                if(tCheckIteminTable==0){
                    $('#otbTbxDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xWPITextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
                // JSnTBXRemovePdtDTTempMultiple();
            }
        });
    }

</script>