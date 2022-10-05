<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <label class="xCNLabelFrm" style="vertical-align: sub;">รายการสินค้าที่ต้องรับเคลมจากผู้จำหน่าย</label>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <button id="obtCLMPrintDocStep3" class="btn xCNBTNDefult xCNBTNDefult2Btn xCNPrintGetPDTFromSPL" type="button" style="display:block; float: right;"> พิมพ์ใบรับสินค้าจากผู้จำหน่าย</button>
    </div>

    <!--ตารางสรุปสินค้ารับเคลม-->
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="odvContentStep3ResultDatatable" style="margin-top: 15px;"></div>
    </div>
</div>

<!-- =========================================== ดูประวัติ บันทึกผลเคลม / ดูประวัติ จำนวนที่รับสินค้า =========================================== -->
<div id="odvCLMModalStep3SaveAndGet" class="modal fade">
    <div class="modal-dialog modal-lg" role="document" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block" id="ospTextModalStep3SaveAndGet"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <!--ชื่อ - รหัส-->
                            <div class="col-lg-3 col-md-3" ><label class="xCNLabelFrm">ชื่อสินค้า (รหัสสินค้า)</label></div>
                            <div class="col-lg-9 col-md-9" id="ospSaveAndGetPDTCodeName"></div>

                            <!--เก็บค่าเอาไว้ส่งไปที่ controller-->
                            <p id="ospSaveAndGetPDTCode" style="display:none;"></p>
                            <p id="ospSeqPDT" style="display:none;"></p>
                            <p id="ospSPLCode" style="display:none;"></p>
                            <p id="ospTypeSave" style="display:none;"></p>
                        
                            <!--บาร์โค้ด-->
                            <div class="col-lg-3 col-md-3" ><label class="xCNLabelFrm">บาร์โค้ด</label></div>
                            <div class="col-lg-9 col-md-9" id="ospSaveAndGetPDTBarcode"></div>

                            <!--รับจากผู้จำหน่าย-->
                            <div class="col-lg-3 col-md-3" ><label class="xCNLabelFrm">รับจากผู้จำหน่าย</label></div>
                            <div class="col-lg-9 col-md-9" id="ospSaveAndGetSPLName"></div>

                            <!--จำนวนส่งเคลม-->
                            <div class="col-lg-4 col-md-4" style="margin-top: 15px;">
                                <div class="row">
                                    <div class="col-lg-12" style="display: inline-block; width: 90%; margin-left: 5%; background: white; border: 1px solid #eeeeee; border-radius: 2px; padding: 5px;">
                                        <div class="col-lg-12 col-md-12" ><label class="xCNLabelFrm">จำนวนส่งเคลม</label></div>
                                        <div class="col-lg-12 col-md-12" id="ospSaveAndGetQTYSend"></div>
                                        <p id="ospQTYSend" style="display:none;"></p>
                                    </div>
                                </div>
                            </div>

                            <!--จำนวนที่บันทึก-->
                            <div class="col-lg-4 col-md-4 xCNShowForSaveClaim" style="margin-top: 15px;">
                                <div class="row">
                                    <div class="col-lg-12" style="display: inline-block; width: 90%; margin-left: 5%; background: white; border: 1px solid #eeeeee; border-radius: 2px; padding: 5px;">
                                        <div class="col-lg-12 col-md-12" ><label class="xCNLabelFrm">จำนวนที่บันทึก</label></div>
                                        <div class="col-lg-12 col-md-12" id="ospSaveClaimQTY"></div>
                                        <p id="ospQTYSaveClaim" style="display:none;"></p>
                                    </div>
                                </div>
                            </div>

                            <!--จำนวนที่รับแล้ว-->
                            <div class="col-lg-4 col-md-4 xCNShowForSaveGet" style="margin-top: 15px;">
                                <div class="row">
                                    <div class="col-lg-12" style="display: inline-block; width: 90%; margin-left: 5%; background: white; border: 1px solid #eeeeee; border-radius: 2px; padding: 5px;">
                                        <div class="col-lg-12 col-md-12" ><label class="xCNLabelFrm">จำนวนที่รับแล้ว</label></div>
                                        <div class="col-lg-12 col-md-12" id="ospSaveAndGetQTYGet"></div>
                                    </div>
                                </div>
                            </div>

                            <!--จำนวนที่ค้างรับ-->
                            <div class="col-lg-4 col-md-4 xCNShowForSaveGet" style="margin-top: 15px;">
                                <div class="row">
                                    <div class="col-lg-12" style="display: inline-block; width: 90%; margin-left: 5%; background: white; border: 1px solid #eeeeee; border-radius: 2px; padding: 5px;">
                                        <div class="col-lg-12 col-md-12" ><label class="xCNLabelFrm">จำนวนที่ค้างรับ</label></div>
                                        <div class="col-lg-12 col-md-12" id="ospSaveAndGetQTYBal"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div><hr></div>
                <div class="row">
                    <div id="odvContentStep3SaveAndGet"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNCLMModalStep3SaveAndGet">
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
        JSxCLMStep3ResultLoadDatatable();
    });

    //โหลดสินค้า
    function JSxCLMStep3ResultLoadDatatable(){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep3ResultDatatable",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvContentStep3ResultDatatable').html(aReturnData['tViewDataTable']);
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