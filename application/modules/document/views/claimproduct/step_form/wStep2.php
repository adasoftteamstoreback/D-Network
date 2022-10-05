<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <label class="xCNLabelFrm" style="vertical-align: sub;">รายการสินค้าที่ต้องส่งเคลมไปยังผู้จำหน่าย</label>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <button id="obtCLMPrintDocStep2" class="btn xCNBTNDefult xCNBTNDefult2Btn xCNPrintSendPDTToSPL" type="button" style="display:none; float: right;"> พิมพ์ใบส่งสินค้าไปยังผู้จำหน่าย</button>
        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNConfirmSendPDTToSPL" type="button" style="float: right;"> ยืนยันการส่งสินค้าให้ผู้จำหน่าย</button>
    </div>

    <!--ตารางสรุปสินค้าที่ส่งเคลมไปยังผู้จำหน่าย-->
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="odvContentStep2ResultDatatable" style="margin-top: 15px;"></div>
    </div>
</div>

<!-- =========================================== ยืนยันการส่งสินค้าหาผู้จำหน่าย =========================================== -->
<div id="odvCLMModalConfirmSendPDTToSPL" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ospCLMModalConfirmSendPDTToSPL">ยืนยันการส่งสินค้าหาผู้จำหน่ายหรือไม่ ? <br> หลังจากการยืนยันการส่งสินค้าไปยังผู้จำหน่ายแล้ว จะไม่สามารถแก้ไขข้อมูลได้</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNCLMModalConfirmSendPDTToSPL" data-dismiss="modal">
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
        JSxCLMStep2ResultLoadDatatable();
    });

    //โหลดสินค้า
    function JSxCLMStep2ResultLoadDatatable(){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep2ResultDatatable",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvContentStep2ResultDatatable').html(aReturnData['tViewDataTable']);
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

    //ยืนยันการส่งสินค้าหาผู้จำหน่าย
    $('.xCNConfirmSendPDTToSPL').unbind().click(function(){

        //วนลูปหาข้อมูลที่เป็นค่าว่าง
        var nKeepCheckNextStep = true;
        $('#otbCLMStep2Datatable tbody tr').each(function() {
            var tTextGetUser = $(this).find("td:eq(9) .xCNUserGetStep2").val();
            if(tTextGetUser == '' || tTextGetUser == null){
                var tTextWarning = " กรุณากรอกชื่อ ผู้เข้ามารับสินค้าให้ครบถ้วน";
                $('#odvCLMModalPleseDataInFill #ospCLMModalPleseDataInFill').text(tTextWarning);
                $('#odvCLMModalPleseDataInFill').modal('show');  
                nKeepCheckNextStep = false;  
                return;
            }
        });

        if(nKeepCheckNextStep == true){
            //สร้างเอกสารใบเบิกออก
            JSxCLMStep2CreateDocNo()
        }
    });

    //สร้างเอกสารใบเบิกออก
    function JSxCLMStep2CreateDocNo(){
        $('#odvCLMModalConfirmSendPDTToSPL').modal('show');  
        $('#odvCLMModalConfirmSendPDTToSPL .xCNCLMModalConfirmSendPDTToSPL').unbind().click(function(){
            if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
                var tCLMDocNo    = "DUMMY";
            }else{
                var tCLMDocNo    = $("#ohdCLMDocNo").val();
            }

            $.ajax({
                type    : "POST",
                url     : "docClaimStep2CreateDoc",
                data    : {
                    'tBCHCode'              : $('#ohdCLMBchCode').val(),
                    'ptCLMDocNo'            : tCLMDocNo,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){

                    //ระบุไว้ว่ามันส่งหาผู้จำหน่ายแล้ว
                    $('#ohdCLMStaPrc').val(4);
                    JSxCLMStep2ResultLoadDatatable();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    }

</script>