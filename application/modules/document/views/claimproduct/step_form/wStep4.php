<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <label class="xCNLabelFrm" style="vertical-align: sub;">รายการสินค้าที่ต้องส่งมอบให้ลูกค้า</label>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <button id="obtCLMPrintDocStep4" class="btn xCNBTNDefult xCNBTNDefult2Btn xCNPrintSendPDTToCST" type="button" style="display:block; float: right;"> พิมพ์ใบลูกค้ารับของ</button>
    </div>

    <!--ตารางสรุปสินค้ารับเคลม-->
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="odvContentStep4ResultDatatable" style="margin-top: 15px;"></div>
    </div>
</div>

<!-- =========================================== ดูประวัติ ส่งมอบให้ลูกค้า / บึนทึก ส่งมอบให้ลูกค้า =========================================== -->
<div id="odvCLMModalStep4SaveReturn" class="modal fade">
    <div class="modal-dialog modal-lg" role="document" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block" id="ospTextModalStep4SaveReturn"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="odvContentStep4SaveReturn"></div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- มีภาษี -->
                <label class="fancy-checkbox xCNStaCreateDNCN xCNHide" style="float: left; display:none;">
                    <input type="checkbox" id="ocbStaCreateDNCN" name="ocbStaCreateDNCN">
                    <span><?=language('product/product/product', 'สร้างเอกสาร DN / CN อัตโนมัติ') ?></span>
                </label>
                <button type="button" class="btn xCNBTNPrimery xCNCLMModalStep4SaveReturn" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalConfirm')?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>


<script>

    $( document ).ready(function() {
        //โหลดสินค้า
        JSxCLMStep4ResultLoadDatatable();
    });

    //โหลดสินค้า
    function JSxCLMStep4ResultLoadDatatable(){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep4ResultDatatable",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvContentStep4ResultDatatable').html(aReturnData['tViewDataTable']);
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