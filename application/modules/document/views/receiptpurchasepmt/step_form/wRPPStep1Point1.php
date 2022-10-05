<div class="row">
    <!-- ค้นหาเอกสารใบชำระ -->
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="input-group">
                <input 
                    type="text" 
                    class="form-control xCNInputWithoutSingleQuote"
                    autocomplete="off"
                    id="oetRPPSearchStep1Point1PdtHTML"
                    name="oetRPPSearchStep1Point1PdtHTML"
                    onkeyup="JSvRPPStep1Point1SearchPdtHTML()"
                    placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>"
                >
                <span class="input-group-btn">
                    <button id="oimRPPMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvRPPStep1Point1SearchPdtHTML()">
                        <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                    </button>
                </span>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"></div>
    <!--ค้นหาจากบาร์โค๊ด-->
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right xCNHideWhenCancelOrApprove">
    </div>
</div>
<!-- ตารางเอกสารที่จะนำมาชำระ -->
<div class="row" id="odvRPPStep1Point1DataPdtTableDTTemp"></div>

<script type="text/javascript">
    $(document).ready(function() {
        JSxRPPStep1Point1LoadDatatable();
    });

    // โหลดเอกสารที่จะนำมาชำระ ( Point1 )
    function JSxRPPStep1Point1LoadDatatable() {
        var tRPPDocNo   = "";
        if ($("#ohdRPPRoute").val() == "docRPPEventAdd") {
            tRPPDocNo   = "";
        }else{
            tRPPDocNo   = $("#ohdRPPDocNo").val();
        }
        $.ajax({
            type: "POST",
            url: "docRPPStep1Point1Datatable",
            data: {
                'tAGNCode'  : $('#oetRPPAgnCode').val(),
                'tBCHCode'  : $('#oetRPPBchCode').val(),
                'tRPPDocNo' : tRPPDocNo,
            },
            async:false,
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                let aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvRPPStep1Point1DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
                    let tStatusDoc  = $('#ohdRPPStaDoc').val();
                    let tStatusApv  = $('#ohdRPPStaApv').val();
                    if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                        $('.xCNHideWhenCancelOrApprove').hide();
                    }
                }else{
                    let tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        var tChkItem    = $("#ohdConfirmRPPInsertPDT").val();
        if(tChkItem != ''){
            $('.xCNRPPNextStep').trigger('click');
        }
    }

    // โหลดเอกสารที่จะนำมาชำระ ( Point1 ) Search
    function JSxRPPStep1Point1LoadDatatableSearch() {
        var tRPPDocNo   = "";
        if ($("#ohdRPPRoute").val() == "docRPPEventAdd") {
            tRPPDocNo   = "";
        }else{
            tRPPDocNo   = $("#ohdRPPDocNo").val();
        }
        $.ajax({
            type: "POST",
            url: "docRPPStep1Point1Datatable",
            data: {
                'tAGNCode'  : $('#oetRPPAgnCode').val(),
                'tBCHCode'  : $('#oetRPPBchCode').val(),
                'tRPPDocNo' : tRPPDocNo,
            },
            async:false,
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                let aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvRPPStep1Point1DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
                    var tStatusDoc  = $('#ohdRPPStaDoc').val();
                    var tStatusApv  = $('#ohdRPPStaApv').val();
                    $("#ohdConfirmRPPInsertPDT").val('');
                    $('.ocbListItem').prop('checked', false);
                    if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                        $('.xCNHideWhenCancelOrApprove').hide();
                    }
                }else{
                    let tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // ค้นหาสินค้าใน temp
    function JSvRPPStep1Point1SearchPdtHTML() {
        let value = $("#oetRPPSearchStep1Point1PdtHTML").val().toLowerCase();
        $("#otbRPPStep1Point1DocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }
</script>
