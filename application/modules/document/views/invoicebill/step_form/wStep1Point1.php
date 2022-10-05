<div class="row">

    <!--ค้นหา-->
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control xCNInputWithoutSingleQuote" autocomplete="off" id="oetSearchStep1Point1PdtHTML" name="oetSearchStep1Point1PdtHTML" onkeyup="JSvIVBStep1Point1SearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                <span class="input-group-btn">
                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvIVBStep1Point1SearchPdtHTML()">
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
            <input type="text" class="form-control" id="oetIVBInsertBarcode"  autocomplete="off" name="oetIVBInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);"  placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า" >
        </div> -->
    </div>

</div>

<!--สินค้า-->
<div class="row" id="odvIVBStep1Point1DataPdtTableDTTemp"></div>


<script>
    $(document).ready(function() {
        JSxIVBStep1Point1LoadDatatable();
    });

    //โหลดสินค้า (Point1)
    function JSxIVBStep1Point1LoadDatatable() {

        if ($("#ohdIVBRoute").val() == "docInvoiceBillEventAdd") {
            var tIVBDocNo = "";
        } else {
            var tIVBDocNo = $("#ohdIVBDocNo").val();
        }

        $.ajax({
            type: "POST",
            url: "docInvoiceBillStep1Point1Datatable",
            data: {
                'tBCHCode': $('#ohdIVBBchCode').val(),
                'ptIVBDocNo': tIVBDocNo,
            },
            async:false,
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvIVBStep1Point1DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
                    var tStatusDoc = $('#ohdIVBStaDoc').val();
                    var tStatusApv = $('#ohdIVBStaApv').val();
                    if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                        $('.xCNHideWhenCancelOrApprove').hide();
                    }
                    // JSxIVBControlFormWhenDocProcess();
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
        var tChkItem = $("#ohdConfirmIVBInsertPDT").val();
        if(tChkItem != ''){
            $('.xCNIVBNextStep').trigger('click');
        }
    }

        //โหลดสินค้า (Point1)
        function JSxIVBStep1Point1LoadDatatableSearch() {

            if ($("#ohdIVBRoute").val() == "docInvoiceBillEventAdd") {
                var tIVBDocNo = "";
            } else {
                var tIVBDocNo = $("#ohdIVBDocNo").val();
            }

            $.ajax({
                type: "POST",
                url: "docInvoiceBillStep1Point1Datatable",
                data: {
                    'tBCHCode': $('#ohdIVBBchCode').val(),
                    'ptIVBDocNo': tIVBDocNo,
                },
                async:false,
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        $('#odvIVBStep1Point1DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                        JCNxCloseLoading();
                        var tStatusDoc = $('#ohdIVBStaDoc').val();
                        var tStatusApv = $('#ohdIVBStaApv').val();
                        $("#ohdConfirmIVBInsertPDT").val('');
                        $('.ocbListItem').prop('checked', false);
                        if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                            $('.xCNHideWhenCancelOrApprove').hide();
                        }
                        // JSxIVBControlFormWhenDocProcess();
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
    function JSvIVBStep1Point1SearchPdtHTML() {
        var value = $("#oetSearchStep1Point1PdtHTML").val().toLowerCase();
        $("#otbIVBStep1Point1DocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }
</script>