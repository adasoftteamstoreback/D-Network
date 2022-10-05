<!-- Panel เงื่อนไข--> 
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'เงื่อนไขเอกสาร'); ?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIVCCondition" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVCCondition" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            
            <!--สาขา-->
            <?php
                $tIVCDataInputBchCode   = "";
                $tIVCDataInputBchName   = "";
                if($tIVCRoute  == "docInvoiceCustomerBillEventAdd"){
                    $tIVCDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                    $tIVCDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                    $tBrowseBchDisabled     = '';
                }else{
                    $tIVCDataInputBchCode    = @$tIVCBchCode;
                    $tIVCDataInputBchName    = @$tIVCBchName;
                    $tBrowseBchDisabled     = 'disabled';
                }
            ?>
            <script>
                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    //BCH - SHP
                    var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                    if(tBchCount < 2){
                        $('#obtIVCBrowseBranch').attr('disabled',true);
                    }
                }
            </script>
            <div class="form-group">
                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQBranch'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdIVCBchCode" name="ohdIVCBchCode" value="<?= @$tIVCDataInputBchCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetIVCBchName" name="oetIVCBchName" value="<?= @$tIVCDataInputBchName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVCBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>
        
            <!-- เลขที่เอกสารภายใน -->
            <div class="row xCNHide">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?></label>
                        <div class="input-group" style="width:100%;">
                            <input type="hidden" id="oetIVCRefIntOld" name="oetIVCRefIntOld" value="<?=@$tIVCRefInt?>">
                            <input type="text" class="input100 xCNHide" id="oetIVCRefInt" name="oetIVCRefInt" value="<?=@$tIVCRefInt?>">
                            <input class="form-control xCNInputReadOnly xWPointerEventNone" type="text" id="oetIVCRefIntName" name="oetIVCRefIntName" value="<?=@$tIVCRefInt?>" readonly placeholder="<?= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?>">
                            <span class="input-group-btn">
                                <button id="obtIVCBrowseSALRefInt" type="button" class="btn xCNBtnBrowseAddOn" >
                                    <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- วันที่เอกสารภายใน -->
            <div class="row xCNHide">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/adjustmentcost/adjustmentcost', 'tADCRefIntDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetIVCRefIntDate" name="oetIVCRefIntDate" value="<?=@$tIVCRefIntDate?>">
                            <span class="input-group-btn">
                                <button id="obtIVCRefIntDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- เลขที่อ้างอิงเอกสารภายนอก -->
            <div class="row xCNHide">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?></label>
                        <input type="text" class="form-control xCNInputReadOnly xCNInputWithoutSpc" id="oetIVCRefExt" name="oetIVCRefExt" maxlength="20" placeholder="<?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?>" value="<?=@$tIVCRefEx?>">
                    </div>
                </div>
            </div>

            <!-- วันที่เอกสารภายนอก -->
            <div class="row xCNHide">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExtDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetIVCRefExtDate" name="oetIVCRefExtDate" value="<?=@$tIVCRefExDate?>">
                            <span class="input-group-btn">
                                <button id="obtIVCRefExtDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== อ้างอิงเอกสารภายใน ======================================= -->
<div id="odvIVCModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/invoice/invoice','อ้างอิงเอกสารใบขาย')?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvIVCFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#obtIVCBrowseSALRefInt').on('click',function(){
        JSxCallPageIVCRefIntDoc();
    });

    //Ref เอกสารใบขาย
    function JSxCallPageIVCRefIntDoc(){
        var tBCHCode = $('#ohdIVCBchCode').val()
        var tBCHName = $('#oetIVCBchName').val()
        
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docClaimRefIntDoc",
            data    : {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvIVCModalRefIntDoc #odvIVCFromRefIntDoc').html(oResult);
                $('#odvIVCModalRefIntDoc').modal({backdrop : 'static' , show : true});
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดยืนยัน Ref เอกสารใบขาย
    $('#obtConfirmRefDocInt').click(function(){
        var tRefIntDocNo    =  $('.xDocuemntRefInt.active').data('docno');
        var tRefIntDocDate  =  $('.xDocuemntRefInt.active').data('docdate');
        var tRefIntBchCode  =  $('.xDocuemntRefInt.active').data('bchcode');
        var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
            return $(this).val();
        }).get();

        //ถ้าไม่เลือกเอกสารอ้างอิงมา
        if(tRefIntDocNo != undefined){

            //อ้างอิงเอกสารภายใน
            $('#oetIVCRefInt').val(tRefIntDocNo);
            $('#oetIVCRefIntName').val(tRefIntDocNo);

            //วันที่อ้างอิงเอกสารใน
            $('#oetIVCRefIntDate').val(tRefIntDocDate).datepicker("refresh");

            JCNxOpenLoading();

            if($("#ohdIVCRoute").val() == "docInvoiceCustomerBillEventAdd"){
                var tIVCDocNo    = "DUMMY";
            }else{
                var tIVCDocNo    = $("#ohdIVCDocNo").val();
            }
            
            $.ajax({
                type    : "POST",
                url     : "docClaimRefIntDocInsertDTToTemp",
                data    : {
                    'tIVCDocNo'         : tIVCDocNo,
                    'tIVCFrmBchCode'    : $('#ohdIVCBchCode').val(),
                    'tRefIntDocNo'      : tRefIntDocNo,
                    'tRefIntBchCode'    : tRefIntBchCode,
                    'aSeqNo'            : aSeqNo
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){      
                    var aReturnData = JSON.parse(oResult);

                    if(aReturnData['aFindCustomer']['rtCode'] == 1){
                        var aItem = aReturnData['aFindCustomer']['raItems'];

                        //ข้อมูลลูกค้า
                        $('#oetIVCFrmCstCode').val(aItem[0]['FTCstCode']);
                        $('#oetIVCFrmCstName').val(aItem[0]['FTXshCstName']);
                        $('#oetIVCFrmCstTel').val(aItem[0]['FTXshCstTel']);
                        $('#oetIVCFrmCstEmail').val(aItem[0]['FTXshCstEmail']);
                        $('#oetIVCFrmCstAddr').val(aItem[0]['FTAddV2Desc1']);

                        //ข้อมูลรถ
                        $('#oetIVCFrmCarCode').val(aItem[0]['FTCarCode']);
                        $('#oetIVCFrmCarName').val(aItem[0]['FTCarRegNo']);
                    }

                    //โหลดสินค้าใน Temp
                    JSxIVCStep1Point1LoadDatatable();

                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        }else{
            //อ้างอิงเอกสารภายใน
            $('#oetIVCRefInt').val('');
            $('#oetIVCRefIntName').val('');

            //วันที่อ้างอิงเอกสารใน
            $('#oetIVCRefIntDate').val('').datepicker("refresh");
        }

    });

</script>