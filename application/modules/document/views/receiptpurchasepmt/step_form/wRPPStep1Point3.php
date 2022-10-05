<div class="row" id="odvRPPStep1Point3DataPdtTableDTTemp"></div>

<script type="text/javascript">

    // Function : โหลดหน้าจอ ขำระใบแจ้งหนี้
    // Creator  : 29/03/2022 Wasin
    function JSxRPPStep1Point3LoadDatatable(){
        if($("#ohdRPPRoute").val() == "docRPPEventAdd"){
            var tRPPDocNo   = "";
        }else{
            var tRPPDocNo   = $("#ohdRPPDocNo").val();
        }
        var tPdtCode    = JSoRPPRemoveCommaData($("#ohdConfirmRPPInsertPDT").val());
        $.ajax({
            type    : "POST",
            url     : "docRPPFindingPoint3",
            data    : {
                'tAGNCode'      : $('#oetRPPAgnCode').val(),
                'tBCHCode'      : $('#oetRPPBchCode').val(),
                'tRPPDocNo'     : tRPPDocNo,
                'tRPPStaApv'    : '<?=@$tRPPStaApv?>',
                'tPdtCode'      : tPdtCode
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvRPPStep1Point3DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
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

</script>