<script>

//Check Status Table
JSvPSHCheckStatusTableData(1);

function JSvPSHCheckStatusTableData(pnStatus){
    var nStaComeSite = $('#ohdStaComeSite').val();
    var tBchCode = $('#oetInputPSHCheckStatusBchCode').val();
    var tShpCode = $('#oetPshPSHShpCod').val();
    var tSaleMac = $('#oetPosCodeSN').val()
    var tRack    = $('#osmPSHCheckStatusLayoutRack option:selected').val();

    if (pnStatus == null) {
        if (tBchCode == '') {
            FSvCMNSetMsgWarningDialog('กรุณาเลือกสาขา');
            return;
        }else if(tShpCode == ''){
            FSvCMNSetMsgWarningDialog('กรุณาเลือกร้านค้า');
            return;
        }else if(tSaleMac == ''){
            FSvCMNSetMsgWarningDialog('กรุณาเลือกเครื่องจุดขาย');
            return;
        }
    }

    if(tBchCode == '' || tBchCode == null ){
        $('#oetInputPSHCheckStatusBchName').focus();
    }else{
        $('#odvPSHCheckStatusContent').html('<div class="col-xs-12 col-md-4 col-lg-4"></div>'+
                        '<div class="col-xs-12 col-md-12 col-lg-6">'+
                            '<div id="odvLayoutCheckStatus"></div>'+
                        '</div>'+
                        '<div class="col-xs-12 col-md-12 col-lg-2"></div>'
        );

        var nWidth = $('#odvLayoutCheckStatus').width();
        JCNxOpenLoading();
        setTimeout(function(){ 
            $.ajax({
                type: "POST",
                url : "PSHSmartLockerCheckStatusDataTable",
                data: { 
                    tBchCode      : tBchCode,
                    tShpCode      : tShpCode,
                    tRack         : tRack,
                    tSaleMac      : tSaleMac,
                    nWidth        : nWidth
                },
                success: function(tResult) {
                    JCNxCloseLoading();
                    $('#odvPSHCheckStatusContent').html(tResult);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }, 1000);
   }
}

</script>