<div class="row">
    <!--ค้นหา-->
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="input-group">
                <input 
                    type="text"
                    class="xCNInputWithoutSingleQuote"
                    autocomplete="off"
                    id="oetSearchStep1Point2PdtHTML"
                    name="oetSearchStep1Point2PdtHTML"
                    onkeyup="JSvRPPStep1Point2SearchPdtHTML()"
                    placeholder="<?=language('common/main/main','tPlaceholder');?>"
                >
                <span class="input-group-btn">
                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvRPPStep1Point2SearchPdtHTML()">
                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>
<!-- ตารางสรุปรายละเอียดเอกสารที่จะชำระ -->
<div class="row" id="odvRPPStep1Point2DataPdtTableDTTemp"></div>
<script type="text/javascript">

    // Function : โหลดรายละเอียดเอกสารที่จำชำระ
    // Creator  : 29/03/2022 Wasin
    function JSxRPPStep1Point2LoadDatatable(){
        if($("#ohdRPPRoute").val() == "docRPPEventAdd"){
            var tRPPDocNo   = "";
        }else{
            var tRPPDocNo   = $("#ohdRPPDocNo").val();
        }
        var tPdtCode    = JSoRPPRemoveCommaData($("#ohdConfirmRPPInsertPDT").val());
        $.ajax({
            type    : "POST",
            url     : "docRPPFindingPoint2",
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
                    $('#odvRPPStep1Point2DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    $('#oetRPPPriceInvPay').focus();
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

    // Function : ค้นหาสินค้า Search HTML TEMP [ STEP1POINT2 ]
    // Creator  : 29/03/2022 Wasin
    function JSvRPPStep1Point2SearchPdtHTML(){
        var value = $("#oetSearchStep1Point2PdtHTML").val().toLowerCase();
        $("#otbRPPStep1Point2DocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    // Function : ลบคอลัมน์ในฐานข้อมูล ลบคอม่า [หลายรายการ]
    // Creator  : 29/03/2022 Wasin
    function JSoRPPRemoveCommaData(paData){
        let aTexts              = paData.substring(0, paData.length - 2);
        let aDataSplit          = aTexts.split(" , ");
        let aDataSplitlength    = aDataSplit.length;
        let aNewDataDeleteComma = [];
        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

</script>