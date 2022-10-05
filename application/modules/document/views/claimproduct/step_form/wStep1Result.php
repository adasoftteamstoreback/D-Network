<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <label class="xCNLabelFrm" style="vertical-align: sub;">รายการสินค้ารับเคลม</label>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <button id="obtCLMPrintDocStep1" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" style="width:150px; float: right;"> พิมพ์ใบรับเคลม</button>
    </div>

    <!--ตารางสรุปสินค้าที่ส่งเคลม-->
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="odvContentStep1ResultDatatable" style="margin-top: 15px;"></div>
    </div>
</div>

<script>

    $( document ).ready(function() {
        //โหลดสินค้า
        JSxCLMStep1ResultLoadDatatable();
    });

    //โหลดสินค้า (Point1)
    function JSxCLMStep1ResultLoadDatatable(){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep1ResultDatatable",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvContentStep1ResultDatatable').html(aReturnData['tViewDataTable']);
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

</script>