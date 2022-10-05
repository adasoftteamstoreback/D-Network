<script type="text/javascript">

    $(document).ready(function(){
        localStorage.removeItem("LocalItemData");         
        //================================== in call function
        var oParameterSend = {
            "DocModules"                    : "",
            "FunctionName"                  : "JSxAPDSaveInline",
            "DataAttribute"                 : ['data-field', 'data-seq'],
            "TableID"                       : "otbAPDDOCPdtTable",
            "NotFoundDataRowClass"          : "xWAPDTextNotfoundDataPdtTable",
            "EditInLineButtonDeleteClass"   : "xWAPDDeleteBtnEditButtonPdt",
            "LabelShowDataClass"            : "xWShowInLine",
            "DivHiddenDataEditClass"        : "xWEditInLine"
        };

        JCNxSetNewEditInline(oParameterSend);

        $(".xWEditInlineElement").eq(nIndexInputEditInline).focus();
        $(".xWEditInlineElement").eq(nIndexInputEditInline).select();

        $(".xWEditInlineElement").removeAttr("disabled");


        let oElement = $(".xWEditInlineElement");
        for(let nI=0;nI<oElement.length;nI++){
            $(oElement.eq(nI)).val($(oElement.eq(nI)).val().trim());
        }
        //================================== end in call function
        
        JSxShowButtonChoose();

        $(".xWEditInlineElement").attr('maxlength','12');
    });

    // Function : Save Inline
    // Creator  : 09/03/2022 Wasin
    function JSxAPDSaveInline(paParams){
        var oThisEl         = paParams['Element'];
        var tThisDisChgText = $(oThisEl).parents('tr.xWPdtItem').find('td label.xWAPDDisChgDT').text();
        if(tThisDisChgText == ''){ 
            // ไม่มีลด/ชาร์จ
            var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
            var tFieldName  = paParams.DataAttribute[0]['data-field'];
            var tValue      = accounting.unformat(paParams.VeluesInline);
            var bIsDelDTDis = false;
            FSvAPDEditPdtIntoTableDT(nSeqNo, tFieldName, tValue, bIsDelDTDis); 
            
        }else{ 
            // มีลด/ชาร์จ
            $('#odvModalConfirmDeleteDTDis').modal({
                backdrop: 'static',
                show: true
            });
            $('#obtAPDConfirmDeleteDTDis').one('click', function(){
                $('#odvModalConfirmDeleteDTDis').modal('hide');
                var nSeqNo      = paParams.DataAttribute[1]['data-seq'];
                var tFieldName  = paParams.DataAttribute[0]['data-field'];
                var tValue      = accounting.unformat(paParams.VeluesInline);
                var bIsDelDTDis = true;
                FSvAPDEditPdtIntoTableDT(nSeqNo, tFieldName, tValue, bIsDelDTDis);
            });
            
            $('#obtAPDCancelDeleteDTDis').one('click', function(){
                if (JCNbAPDIsDocType('havePdt')) {
                    JSvAPDLoadPdtDataTableHtml(1, false);
                }
                if (JCNbAPDIsDocType('nonePdt')) {
                    JSvAPDLoadNonePdtDataTableHtml(1, false);
                }
            });
        }
        
    }
    
    // Function : Delete Record Before to Save.
    // Creator  : 09/03/2022 Wasin
    function JSnAPDRemoveDTRow(poEl) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal = $(poEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno = $(poEl).parents("tr.xWPdtItem").attr("data-seqno");
            $(poEl).parents("tr.xWPdtItem").remove();
            JSnAPDRemoveDTTemp(tSeqno, tVal);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Function : Event Pdt Multi Delete
    // Creator  : 09/03/2022 Wasin
    function JSoAPDPdtDelChoose(pnPage) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var aSeq            = $("#ohdConfirmSeqDelete").val();
            var tDocNo          = $("#oetAPDDocNo").val();
            // PdtCode
            var aTextSeq        = aSeq.substring(0, aSeq.length - 2);
            var aSeqSplit       = aTextSeq.split(" , ");
            var aSeqSplitlength = aSeqSplit.length;
            // Seq
            var aTextSeq        = aSeq.substring(0, aSeq.length - 2);
            var aSeqSplit       = aTextSeq.split(" , ");
            var aSeqData        = [];
            for ($i = 0; $i < aSeqSplitlength; $i++) {
                aSeqData.push(aSeqSplit[$i]);
            }
            if (aSeqSplitlength > 1) {
                localStorage.StaDeleteArray = "1";
                $.ajax({
                    type    : "POST",
                    url     : "docAPDebitnotePdtMultiDeleteEvent",
                    data    : {
                        tDocNo      : tDocNo,
                        tSeqCode    : aSeqData
                    },
                    success: function (tResult) {
                        setTimeout(function () {
                            $("#odvModalDelPdtAPD").modal("hide");
                            if(JCNbAPDIsDocType('havePdt')){
                                JSvAPDLoadPdtDataTableHtml();
                            }
                            if(JCNbAPDIsDocType('nonePdt')){
                                JSvAPDLoadNonePdtDataTableHtml();
                            }
                            $("#ospConfirmDelete").text($("#oetTextComfirmDeleteSingle").val());
                            $("#ohdConfirmSeqDelete").val("");
                            $("#ohdConfirmPdtDelete").val("");
                            $("#ohdConfirmPunDelete").val("");
                            $("#ohdConfirmDocDelete").val("");
                            localStorage.removeItem("LocalItemData");
                            $(".obtChoose").hide();
                            $(".modal-backdrop").remove();
                        }, 1000);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                localStorage.StaDeleteArray = "0";
                return false;
            }
        }else {
            JCNxShowMsgSessionExpired();
        }
    }
    
    // Function : Event Edit Pdt Table
    // Creator  : 09/03/2022 Wasin
    function JCNvAPDDisChagDT(poEl) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tDocNo          = $(poEl).parents('.xWPdtItem').data('docno');
            var tPdtCode        = $(poEl).parents('.xWPdtItem').data('pdtcode');
            var tPdtName        = $(poEl).parents('.xWPdtItem').data('pdtname');
            var tPunCode        = $(poEl).parents('.xWPdtItem').data('puncode');
            var tNet            = $(poEl).parents('.xWPdtItem').data('net');
            var tSetPrice       = $(poEl).parents('.xWPdtItem').data('set-price');
            var tQty            = $(poEl).parents('.xWPdtItem').data('qty');
            var tStaDis         = $(poEl).parents('.xWPdtItem').data('stadis');
            var tSeqNo          = $(poEl).parents('.xWPdtItem').data('seqno');
            var bHaveDisChgDT   = $(poEl).parents('.xWAPDDisChgDTForm').find('label.xWAPDDisChgDT').text() == '' ? false : true;
            window.DisChgDataRowDT = {
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
            var oDisChgParams = {
                DisChgType  : 'disChgDT'
            };
            JSxAPDOpenDisChgPanel(oDisChgParams);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

</script>
