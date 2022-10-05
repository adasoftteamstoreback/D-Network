<script type="text/javascript">
   
    $('.xWPdtSVSetEdit').off('click');
    $('.xWPdtSVSetEdit').on('click',function(){
        var tPdtCode = $(this).parent().parent().data('pdtcode');
        $('.xWAddMargin').hide();
        JSxPdtSetSVCallPageEdit(tPdtCode);
    });

    $('.xWPdtSVSetDelete').off('click');
    $('.xWPdtSVSetDelete').on('click',function(){
        var tPdtCode = $(this).parent().parent().data('pdtcode');
        var tPdtName = $(this).parent().parent().data('pdtname');
        JSxPdtSVEventDelete(tPdtCode,tPdtName);
    });

    $('.xWPdtSVSetName').off('click');
    $('.xWPdtSVSetName').on('click',function(){
        var tPdtCode = $(this).parent().data('pdtcode');
        var tPdtName = $(this).parent().data('pdtname');
        JSxPdtSVEventProductModal(tPdtCode,tPdtName);
    });

    // Function : Function Click Condition Congfig Product Set Sub Price (ใช้ราคาย่อย)
    // Parameters : 
    // Creator : 07/02/2019 wasin(Yoshi)
    // Return : Event Select Product Price Set
    // Return Type : -
    function JSxClickEvnPdtSetSubPrice(oEvent){
        var nStaChkSubPri   = ($(oEvent).is(':checked')) ? 1 : 0;
        if(nStaChkSubPri == 1){
            $('#ocbPdtSetPrice').prop('checked',false);
            $('#ocbPdtSetPrice').prop('readonly',false);
            $('#ocbPdtSetPrice').parent().css('pointer-events','');
            $('#ocbPdtSubPrice').prop('readonly', true);
            $('#ocbPdtSubPrice').parent().css('pointer-events','none');
        }   
    }

    // Function : Function Click Condition Congfig ProductSet Peice Set (ใช้ราคาชุด)
    // Parameters : 
    // Creator : 07/02/2019 wasin(Yoshi)
    // Return : Event Select Product Set Price Set
    // Return Type : -
    function JSxClickEvnPdtSetPriceSet(oEvent){
        var nStaChkSetPri   = ($(oEvent).is(':checked')) ? 1 : 0;
        if(nStaChkSetPri == 1){
            $('#ocbPdtSubPrice').prop('checked',false);
            $('#ocbPdtSubPrice').prop('readonly', false);
            $('#ocbPdtSubPrice').parent().css('pointer-events','');
            $('#ocbPdtSetPrice').prop('readonly', true);
            $('#ocbPdtSetPrice').parent().css('pointer-events','none');
        }
    };

    // Function : Func.Pdt Event Click Edit Inline 
    // Parameters : 
    // Creator : 07/02/2019 wasin(Yoshi)
    // Return : Call Event Edit In Line
    // Return Type : -
    function JSxClickEventEditInlineData(oEvent){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tPdtSetStaEditInline    =   $('#ohdPdtSetStaEditInline').val();
            if(tPdtSetStaEditInline == 0){
                var tPdtSetPdtCode      = $(oEvent).parents('.xWPdtSetRow').data('pdtcode');
                var tLocalName          = 'LSPdtSet'+tPdtSetPdtCode;
                var oPdtSetDataLocal    = {
                    'tProductSetPdtCode' : $(oEvent).parents('.xWPdtSetRow').data('pdtcode'),
                    'tProductSetPdtName' : $(oEvent).parents('.xWPdtSetRow').data('pdtname'),
                    'tProductSetQty'     : $(oEvent).parents('.xWPdtSetRow').find('input[type=text].xCNPdtSetQty').val()
                };
                // Backup Seft Record
                localStorage.setItem(tLocalName,JSON.stringify(oPdtSetDataLocal));

                // Visibled icons
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetEditInLine').addClass('xCNHide');
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetSaveInLine').removeClass('xCNHide');
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetCancelInLine').removeClass('xCNHide');

                $(oEvent).parents('.xWPdtSetRow').find('input[type=text].xCNPdtSetQty').attr('readonly',false)
                $('#ohdPdtSetStaEditInline').val(1);
            }else{
                var tMsgEventEditInline =   '<?php echo language("product/product/product","tPDTEditInlineUse")?>';
                FSvCMNSetMsgWarningDialog(tMsgEventEditInline);
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
    
    // Function : Func.Pdt Event Click Save Inline 
    // Parameters : 
    // Creator : 08/02/2019 wasin(Yoshi)
    // Return : Call Event Save In Line
    // Return Type : -
    function JSxClickEventSaveInlineData(oEvent){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tPdtSetStaEditInline = $('#ohdPdtSetStaEditInline').val();
            if(tPdtSetStaEditInline == 1){
                var tPdtSetPdtCode  = $(oEvent).parents('.xWPdtSetRow').data('pdtcode');
                var tLocalName      = 'LSPdtSet'+tPdtSetPdtCode;

                // Remove Seft Record Backup
                localStorage.removeItem(tLocalName);
                
                // Visibled icons
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetEditInLine').removeClass('xCNHide');
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetSaveInLine').addClass('xCNHide');
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetCancelInLine').addClass('xCNHide');

                $(oEvent).parents('.xWPdtSetRow').find('input[type=text].xCNPdtSetQty').attr('readonly',true)
                $('#ohdPdtSetStaEditInline').val(0);
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Function : Func.Pdt Event Click Cancle Inline 
    // Parameters : 
    // Creator : 08/02/2019 wasin(Yoshi)
    // Return : Call Event Cancle In Line
    // Return Type : -
    function JSxClickEventCancleInlineData(oEvent){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tPdtSetStaEditInline = $('#ohdPdtSetStaEditInline').val();
            if(tPdtSetStaEditInline == 1){
                var tPdtSetPdtCode  = $(oEvent).parents('.xWPdtSetRow').data('pdtcode');
                var tLocalName      = 'LSPdtSet'+tPdtSetPdtCode;
                // Restore Seft Record
                var oBackupRecord   = JSON.parse(localStorage.getItem(tLocalName));
                $(oEvent).parents('.xWPdtSetRow').find('input[type=text].xCNPdtSetQty').val(oBackupRecord['tProductSetQty']);

                // Remove Seft Record Backup
                localStorage.removeItem(tLocalName);

                // Visibled icons
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetEditInLine').removeClass('xCNHide');
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetSaveInLine').addClass('xCNHide');
                $(oEvent).parents('.xWPdtSetRow').find('.xWPdtSetCancelInLine').addClass('xCNHide');

                $(oEvent).parents('.xWPdtSetRow').find('input[type=text].xCNPdtSetQty').attr('readonly',true)
                $('#ohdPdtSetStaEditInline').val(0);
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

</script>