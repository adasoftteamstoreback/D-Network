<div class="row">

    <!--ค้นหา-->
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control xCNInputWithoutSingleQuote" autocomplete="off" id="oetSearchStep1Point1PdtHTML" name="oetSearchStep1Point1PdtHTML" onkeyup="JSvIVCStep1Point1SearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                <span class="input-group-btn">
                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvIVCStep1Point1SearchPdtHTML()">
                        <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"></div>

    <!--ค้นหาจากบาร์โค๊ด-->
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right xCNHideWhenCancelOrApprove">
        <!-- <div class="form-group">
            <input type="text" class="form-control" id="oetIVCInsertBarcode"  autocomplete="off" name="oetIVCInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);"  placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า" >
        </div> -->
    </div>

</div>

<!--สินค้า-->
<div class="row" id="odvIVCStep1Point1DataPdtTableDTTemp"></div>


<script>
    $(document).ready(function() {
        JSxIVCStep1Point1LoadDatatable();
    });

    //โหลดสินค้า (Point1)
    function JSxIVCStep1Point1LoadDatatable() {

        if ($("#ohdIVCRoute").val() == "docInvoiceCustomerBillEventAdd") {
            var tIVCDocNo = "";
        } else {
            var tIVCDocNo = $("#ohdIVCDocNo").val();
        }

        $.ajax({
            type: "POST",
            url: "docInvoiceCustomerBillStep1Point1Datatable",
            data: {
                'tBCHCode': $('#ohdIVCBchCode').val(),
                'ptIVCDocNo': tIVCDocNo,
            },
            async:false,
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvIVCStep1Point1DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
                    var tStatusDoc = $('#ohdIVCStaDoc').val();
                    var tStatusApv = $('#ohdIVCStaApv').val();
                    if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                        $('.xCNHideWhenCancelOrApprove').hide();
                    }
                    // JSxIVCControlFormWhenDocProcess();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        var tChkItem = $("#ohdConfirmIVCInsertPDT").val();
        if(tChkItem != ''){
            $('.xCNIVCNextStep').trigger('click');
        }
    }

        //โหลดสินค้า (Point1)
        function JSxIVCStep1Point1LoadDatatableSearch() {

            if ($("#ohdIVCRoute").val() == "docInvoiceCustomerBillEventAdd") {
                var tIVCDocNo = "";
            } else {
                var tIVCDocNo = $("#ohdIVCDocNo").val();
            }

            $.ajax({
                type: "POST",
                url: "docInvoiceCustomerBillStep1Point1Datatable",
                data: {
                    'tBCHCode': $('#ohdIVCBchCode').val(),
                    'ptIVCDocNo': tIVCDocNo,
                },
                async:false,
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        $('#odvIVCStep1Point1DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                        JCNxCloseLoading();
                        var tStatusDoc = $('#ohdIVCStaDoc').val();
                        var tStatusApv = $('#ohdIVCStaApv').val();
                        $("#ohdConfirmIVCInsertPDT").val('');
                        $('.ocbListItem').prop('checked', false);
                        if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                            $('.xCNHideWhenCancelOrApprove').hide();
                        }
                        // JSxIVCControlFormWhenDocProcess();
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }

    //ค้นหาสินค้าใน temp
    function JSvIVCStep1Point1SearchPdtHTML() {
        var value = $("#oetSearchStep1Point1PdtHTML").val().toLowerCase();
        $("#otbIVCStep1Point1DocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }
</script>