<div class="row">

    <!--ค้นหา-->
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="xCNInputWithoutSingleQuote" autocomplete="off" id="oetSearchStep1Point2PdtHTML" name="oetSearchStep1Point2PdtHTML" onkeyup="JSvCLMStep1Point2SearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                <span class="input-group-btn">
                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvCLMStep1Point2SearchPdtHTML()">
                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                    </button>
                </span>
            </div>
        </div>
    </div>

</div>

<!--สินค้า-->
<div class="row" id="odvCLMStep1Point2DataPdtTableDTTemp"></div>

<script>

    $( document ).ready(function() {
        //โหลดสินค้า
        // JSxIVBStep1Point2LoadDatatable();
    });

    //โหลดสินค้า (Point2)
    function JSxIVBStep1Point2LoadDatatable(){

        if($("#ohdCLMRoute").val() == "docInvoiceBillEventAdd"){
            var tIVBDocNo    = "";
        }else{
            var tIVBDocNo    = $("#ohdIVBDocNo").val();
        }
        var tPdtCode = JSoIVBRemoveCommaData($("#ohdConfirmIVBInsertPDT").val());

        $.ajax({
            type    : "POST",
            url     : "docInvoiceBillFindingPoint2",
            data    : {
                'tBCHCode'              : $('#ohdIVBBchCode').val(),
                'ptIVBDocNo'            : tIVBDocNo,
                'ptCLMStaApv'           : '<?=@$tCLMStaApv?>',
                'tPdtCode'              : tPdtCode
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvCLMStep1Point2DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ค้นหาสินค้าใน temp
    function JSvCLMStep1Point2SearchPdtHTML() {
        var value = $("#oetSearchStep1Point2PdtHTML").val().toLowerCase();
        $("#otbCLMStep1Point2DocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    //ลบคอลัมน์ในฐานข้อมูล ลบคอม่า [หลายรายการ]
    function JSoIVBRemoveCommaData(paData){
        var aTexts              = paData.substring(0, paData.length - 2);
        var aDataSplit          = aTexts.split(" , ");
        var aDataSplitlength    = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

</script>