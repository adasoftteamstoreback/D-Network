<script type="text/javascript">
    var tPXStaDocDoc    = $('#ohdPXStaDoc').val();
    var tPXStaApvDoc    = $('#ohdPXStaApv').val();
    // var tPXStaPrcStkDoc = $('#ohdPXStaPrcStk').val();

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
    
    $(document).ready(function(){
    
        //Event Confirm Delete PDT IN Tabel DT 
        $('#odvPXModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSnPXRemovePdtDTTempMultiple();
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
    function JSxPXSaveEditInline(paParams){
        console.log('JSxPXSaveEditInline: ', paParams);
        var oThisEl         = paParams['Element'];
        var tThisDisChgText = $(oThisEl).parents('tr.xWPdtItem').find('td label.xWPXDisChgDT').text().trim();
        if(tThisDisChgText == ''){
            console.log('No Have Dis/Chage DT');
            // ไม่มีลด/ชาร์จ
            var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
            var tFieldName  = paParams.DataAttribute[0]['data-field'];
            var tValue      = accounting.unformat(paParams.VeluesInline);
            var bIsDelDTDis = false;
            FSvPXEditPdtIntoTableDT(nSeqNo,tFieldName,tValue,bIsDelDTDis); 
        }else{
            console.log('Have Dis/Chage DT');
            // มีลด/ชาร์จ
            $('#odvPXModalConfirmDeleteDTDis').modal({
                backdrop: 'static',
                show: true
            });
            
            $('#odvPXModalConfirmDeleteDTDis #obtPXConfirmDeleteDTDis').unbind();
            $('#odvPXModalConfirmDeleteDTDis #obtPXConfirmDeleteDTDis').one('click',function(){
                $('#odvPXModalConfirmDeleteDTDis').modal('hide');
                $('.modal-backdrop').remove();
                var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
                var tFieldName  = paParams.DataAttribute[0]['data-field'];
                var tValue      = accounting.unformat(paParams.VeluesInline);
                var bIsDelDTDis = true;
                FSvPXEditPdtIntoTableDT(nSeqNo,tFieldName,tValue,bIsDelDTDis);
            });

            $('#odvPXModalConfirmDeleteDTDis #obtPXCancelDeleteDTDis').unbind();
            $('#odvPXModalConfirmDeleteDTDis #obtPXCancelDeleteDTDis').one('click',function(){
                $('.modal-backdrop').remove();
                JSvPXLoadPdtDataTableHtml();
            });

            $('#odvPXModalConfirmDeleteDTDis').modal('show')
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
            JSxPXSaveEditInline(oParameters);


        }
    });

    //Functionality: Call Modal Dis/Chage Doc DT
    //Parameters: object Event Click
    //Creator: 02/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View
    // ReturnType : View
    function JCNvPXCallModalDisChagDT(poEl){
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
            var bHaveDisChgDT   = $(poEl).parents('.xWPXDisChgDTForm').find('label.xWPXDisChgDT').text() == ''? false : true;
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
            var oPXDisChgParams = {
                DisChgType: 'disChgDT'
            };
            JSxPXOpenDisChgPanel(oPXDisChgParams);
        }else{
            JCNxShowMsgSessionExpired();
        }   
    }

    // Functionality: Pase Text Product Item In Modal Delete
    // Parameters: Event Click List Table Delete Mutiple
    // Creator: 26/07/2019 Wasin(Yoshi)
    // Return: -
    // ReturnType: -
    function JSxPXTextInModalDelPdtDtTemp(){

        var aArrayConvert   = [JSON.parse(localStorage.getItem("PX_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tPXTextDocNo   = "";
            var tPXTextSeqNo   = "";
            var tPXTextPdtCode = "";
            var tPXTextPunCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tPXTextDocNo    += aValue.tDocNo;
                tPXTextDocNo    += " , ";

                tPXTextSeqNo    += aValue.tSeqNo;
                tPXTextSeqNo    += " , ";

                tPXTextPdtCode  += aValue.tPdtCode;
                tPXTextPdtCode  += " , ";

                tPXTextPunCode  += aValue.tPunCode;
                tPXTextPunCode  += " , ";
            });
            $('#odvPXModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmPXDocNoDelete').val(tPXTextDocNo);
            $('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmPXSeqNoDelete').val(tPXTextSeqNo);
            $('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmPXPdtCodeDelete').val(tPXTextPdtCode);
            $('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmPXPunCodeDelete').val(tPXTextPunCode);
        }
    }

    // Functionality: Show Button Delete Multiple DT Temp
    // Parameters: Event Click List Table Delete Mutiple
    // Creator: 26/07/2019 Wasin(Yoshi)
    // Return: -
    // ReturnType: -
    function JSxPXShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("PX_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvPXMngDelPdtInTableDT #oliPXBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvPXMngDelPdtInTableDT #oliPXBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvPXMngDelPdtInTableDT #oliPXBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //Functionality: Function Delete Product In Doc DT Temp
    //Parameters: object Event Click
    //Creator: 04/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View
    // ReturnType : View
    function JSnPXDelPdtInDTTempSingle(poEl) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal    = $(poEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno  = $(poEl).parents("tr.xWPdtItem").attr("data-seqno");
            $(poEl).parents("tr.xWPdtItem").remove();
            JSnPXRemovePdtDTTempSingle(tSeqno, tVal);
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
    function JSnPXRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tPXDocNo        = $("#oetPXDocNo").val();
        var tPXBchCode      = $('#oetPXFrmBchCode').val();
        var tPXVatInOrEx    = $('#ocmPXFrmSplInfoVatInOrEx').val();

        JSxRendercalculate();
        JCNxOpenLoading();

        $.ajax({
            type: "POST",
            url: "docPXRemovePdtInDTTmp",
            data: {
                'tBchCode'      : tPXBchCode,
                'tDocNo'        : tPXDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode,
                'tVatInOrEx'    : tPXVatInOrEx,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdPXUsrCode'      : $('#ohdPXUsrCode').val(),
                'ohdPXLangEdit'     : $('#ohdPXLangEdit').val(),
                'ohdSesUsrLevel'    : $('#ohdSesUsrLevel').val(),
                'ohdPXSesUsrBchCode'  : $('#ohdPXSesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JSvPXLoadPdtDataTableHtml();
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
    function JSoPXRemoveCommaData(paData){
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
    function JSnPXRemovePdtDTTempMultiple(){

        JCNxOpenLoading();
        var tPXDocNo        = $("#oetPXDocNo").val();
        var tPXBchCode      = $('#oetPXFrmBchCode').val();
        var tPXVatInOrEx    = $('#ocmPXFrmSplInfoVatInOrEx').val();
        var aDataPdtCode    = JSoPXRemoveCommaData($('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmPXPdtCodeDelete').val());
        var aDataSeqNo      = JSoPXRemoveCommaData($('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmPXSeqNoDelete').val());
        // var aDataPunCode    = JSoPXRemoveCommaData($('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmPXPunCodeDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvPXModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvPXModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('PX_LocalItemDataDelDtTemp');
        $('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmSODocNoDelete').val('');
        $('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmSOSeqNoDelete').val('');
        $('#odvPXModalDelPdtInDTTempMultiple #ohdConfirmSOPdtCodeDelete').val('');
        setTimeout(function(){
            $('.modal-backdrop').remove();
            JCNxLayoutControll();
        }, 500);

        JSxRendercalculate();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docPXRemovePdtInDTTmpMulti",
            data: {
                'ptPXBchCode'   : tPXBchCode,
                'ptPXDocNo'     : tPXDocNo,
                'ptPXVatInOrEx' : tPXVatInOrEx,
                'paDataPdtCode' : aDataPdtCode,
                // 'paDataPunCode' : aDataPunCode,
                'paDataSeqNo'   : aDataSeqNo,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdPXUsrCode'      : $('#ohdPXUsrCode').val(),
                'ohdPXLangEdit'     : $('#ohdPXLangEdit').val(),
                'ohdSesUsrLevel'    : $('#ohdSesUsrLevel').val(),
                'ohdPXSesUsrBchCode'  : $('#ohdPXSesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var tCheckIteminTable = $('#otbPXDocPdtAdvTableList tbody tr').length;
                if(tCheckIteminTable==0){
                    $('#otbPXDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xWPXTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
            },  
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
                // JSnPXRemovePdtDTTempMultiple();
            }
        });
    }

</script>