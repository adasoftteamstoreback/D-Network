<!-- Panel เงื่อนไข--> 
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'เงื่อนไขเอกสาร'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvIVBCondition" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVBCondition" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            
            <!--สาขา-->
            <?php
                $tIVBDataInputBchCode   = "";
                $tIVBDataInputBchName   = "";
                if($tIVBRoute  == "docInvoiceBillEventAdd"){
                    $tIVBDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                    $tIVBDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                    $tBrowseBchDisabled     = '';
                }else{
                    $tIVBDataInputBchCode    = @$tIVBBchCode;
                    $tIVBDataInputBchName    = @$tIVBBchName;
                    $tBrowseBchDisabled     = 'disabled';
                }
            ?>
            <script>
                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    //BCH - SHP
                    var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                    if(tBchCount < 2){
                        $('#obtIVBBrowseBranch').attr('disabled',true);
                    }
                }
            </script>
            <div class="form-group">
                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQBranch'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdIVBBchCode" name="ohdIVBBchCode" value="<?= @$tIVBDataInputBchCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetIVBBchName" name="oetIVBBchName" value="<?= @$tIVBDataInputBchName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVBBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
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
                            <input type="hidden" id="oetIVBRefIntOld" name="oetIVBRefIntOld" value="<?=@$tIVBRefInt?>">
                            <input type="text" class="input100 xCNHide" id="oetIVBRefInt" name="oetIVBRefInt" value="<?=@$tIVBRefInt?>">
                            <input class="form-control xCNInputReadOnly xWPointerEventNone" type="text" id="oetIVBRefIntName" name="oetIVBRefIntName" value="<?=@$tIVBRefInt?>" readonly placeholder="<?= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?>">
                            <span class="input-group-btn">
                                <button id="obtIVBBrowseSALRefInt" type="button" class="btn xCNBtnBrowseAddOn" >
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
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetIVBRefIntDate" name="oetIVBRefIntDate" value="<?=@$tIVBRefIntDate?>">
                            <span class="input-group-btn">
                                <button id="obtIVBRefIntDate" type="button" class="btn xCNBtnDateTime">
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
                        <input type="text" class="form-control xCNInputReadOnly xCNInputWithoutSpc" id="oetIVBRefExt" name="oetIVBRefExt" maxlength="20" placeholder="<?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?>" value="<?=@$tIVBRefEx?>">
                    </div>
                </div>
            </div>

            <!-- วันที่เอกสารภายนอก -->
            <div class="row xCNHide">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExtDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetIVBRefExtDate" name="oetIVBRefExtDate" value="<?=@$tIVBRefExDate?>">
                            <span class="input-group-btn">
                                <button id="obtIVBRefExtDate" type="button" class="btn xCNBtnDateTime">
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
<div id="odvIVBModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/invoice/invoice','อ้างอิงเอกสารใบขาย')?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvIVBFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#obtIVBBrowseSALRefInt').on('click',function(){
        JSxCallPageIVBRefIntDoc();
    });

    //Ref เอกสารใบขาย
    function JSxCallPageIVBRefIntDoc(){
        var tBCHCode = $('#ohdIVBBchCode').val()
        var tBCHName = $('#oetIVBBchName').val()
        
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
                $('#odvIVBModalRefIntDoc #odvIVBFromRefIntDoc').html(oResult);
                $('#odvIVBModalRefIntDoc').modal({backdrop : 'static' , show : true});
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
            $('#oetIVBRefInt').val(tRefIntDocNo);
            $('#oetIVBRefIntName').val(tRefIntDocNo);

            //วันที่อ้างอิงเอกสารใน
            $('#oetIVBRefIntDate').val(tRefIntDocDate).datepicker("refresh");

            JCNxOpenLoading();

            if($("#ohdIVBRoute").val() == "docInvoiceBillEventAdd"){
                var tIVBDocNo    = "DUMMY";
            }else{
                var tIVBDocNo    = $("#ohdIVBDocNo").val();
            }
            
            $.ajax({
                type    : "POST",
                url     : "docClaimRefIntDocInsertDTToTemp",
                data    : {
                    'tIVBDocNo'         : tIVBDocNo,
                    'tIVBFrmBchCode'    : $('#ohdIVBBchCode').val(),
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
                        $('#oetIVBFrmCstCode').val(aItem[0]['FTCstCode']);
                        $('#oetIVBFrmCstName').val(aItem[0]['FTXshCstName']);
                        $('#oetIVBFrmCstTel').val(aItem[0]['FTXshCstTel']);
                        $('#oetIVBFrmCstEmail').val(aItem[0]['FTXshCstEmail']);
                        $('#oetIVBFrmCstAddr').val(aItem[0]['FTAddV2Desc1']);

                        //ข้อมูลรถ
                        $('#oetIVBFrmCarCode').val(aItem[0]['FTCarCode']);
                        $('#oetIVBFrmCarName').val(aItem[0]['FTCarRegNo']);
                    }

                    //โหลดสินค้าใน Temp
                    JSxIVBStep1Point1LoadDatatable();

                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        }else{
            //อ้างอิงเอกสารภายใน
            $('#oetIVBRefInt').val('');
            $('#oetIVBRefIntName').val('');

            //วันที่อ้างอิงเอกสารใน
            $('#oetIVBRefIntDate').val('').datepicker("refresh");
        }

    });

</script>