<!-- Panel เงื่อนไข--> 
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'เงื่อนไขเอกสาร'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvCLMCondition" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvCLMCondition" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            
            <!--สาขา-->
            <?php
                $tCLMDataInputBchCode   = "";
                $tCLMDataInputBchName   = "";
                if($tCLMRoute  == "docClaimEventAdd"){
                    $tCLMDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                    $tCLMDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                    $tBrowseBchDisabled     = '';
                }else{
                    $tCLMDataInputBchCode    = @$tCLMBchCode;
                    $tCLMDataInputBchName    = @$tCLMBchName;
                    $tBrowseBchDisabled     = '';
                }
            ?>
            <script>
                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    //BCH - SHP
                    var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                    if(tBchCount < 2){
                        $('#obtCLMBrowseBranch').attr('disabled',true);
                    }
                }
            </script>
            <div class="form-group">
                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQBranch'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdCLMBchCode" name="ohdCLMBchCode" value="<?= @$tCLMDataInputBchCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetCLMBchName" name="oetCLMBchName" value="<?= @$tCLMDataInputBchName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                    <span class="input-group-btn">
                        <button id="obtCLMBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>
        
            <!-- เลขที่เอกสารภายใน -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?></label>
                        <div class="input-group" style="width:100%;">
                            <input type="hidden" id="oetCLMRefIntOld" name="oetCLMRefIntOld" value="<?=@$tCLMRefInt?>">
                            <input type="text" class="input100 xCNHide" id="oetCLMRefInt" name="oetCLMRefInt" value="<?=@$tCLMRefInt?>">
                            <input class="form-control xCNInputReadOnly xWPointerEventNone" type="text" id="oetCLMRefIntName" name="oetCLMRefIntName" value="<?=@$tCLMRefInt?>" readonly placeholder="<?= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?>">
                            <span class="input-group-btn">
                                <button id="obtCLMBrowseSALRefInt" type="button" class="btn xCNBtnBrowseAddOn" >
                                    <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- วันที่เอกสารภายใน -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/adjustmentcost/adjustmentcost', 'tADCRefIntDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetCLMRefIntDate" name="oetCLMRefIntDate" value="<?=@$tCLMRefIntDate?>">
                            <span class="input-group-btn">
                                <button id="obtCLMRefIntDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- เลขที่อ้างอิงเอกสารภายนอก -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?></label>
                        <input type="text" class="form-control xCNInputReadOnly xCNInputWithoutSpc" id="oetCLMRefExt" name="oetCLMRefExt" maxlength="20" placeholder="<?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?>" value="<?=@$tCLMRefEx?>">
                    </div>
                </div>
            </div>

            <!-- วันที่เอกสารภายนอก -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/producttransferbranch/producttransferbranch', 'tTBRefExtDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetCLMRefExtDate" name="oetCLMRefExtDate" value="<?=@$tCLMRefExDate?>">
                            <span class="input-group-btn">
                                <button id="obtCLMRefExtDate" type="button" class="btn xCNBtnDateTime">
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
<div id="odvCLMModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/invoice/invoice','อ้างอิงเอกสารใบขาย')?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvCLMFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#obtCLMBrowseSALRefInt').on('click',function(){
        JSxCallPageCLMRefIntDoc();
    });

    $('#obtCLMRefIntDate').click(function(event){
        $('#oetCLMRefIntDate').datepicker('show');
        event.preventDefault();
    });

    $('#obtCLMRefExtDate').click(function(event){
        $('#oetCLMRefExtDate').datepicker('show');
        event.preventDefault();
    });

    //Ref เอกสารใบขาย
    function JSxCallPageCLMRefIntDoc(){
        var tBCHCode = $('#ohdCLMBchCode').val()
        var tBCHName = $('#oetCLMBchName').val()
        
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
                $('#odvCLMModalRefIntDoc #odvCLMFromRefIntDoc').html(oResult);
                $('#odvCLMModalRefIntDoc').modal({backdrop : 'static' , show : true});
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
            $('#oetCLMRefInt').val(tRefIntDocNo);
            $('#oetCLMRefIntName').val(tRefIntDocNo);

            //วันที่อ้างอิงเอกสารใน
            $('#oetCLMRefIntDate').val(tRefIntDocDate).datepicker("refresh");

            JCNxOpenLoading();

            if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
                var tCLMDocNo    = "DUMMY";
            }else{
                var tCLMDocNo    = $("#ohdCLMDocNo").val();
            }
            
            $.ajax({
                type    : "POST",
                url     : "docClaimRefIntDocInsertDTToTemp",
                data    : {
                    'tCLMDocNo'         : tCLMDocNo,
                    'tCLMFrmBchCode'    : $('#ohdCLMBchCode').val(),
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
                        $('#oetCLMFrmCstCode').val(aItem[0]['FTCstCode']);
                        $('#oetCLMFrmCstName').val(aItem[0]['FTXshCstName']);
                        $('#oetCLMFrmCstTel').val(aItem[0]['FTXshCstTel']);
                        $('#oetCLMFrmCstEmail').val(aItem[0]['FTXshCstEmail']);
                        $('#oetCLMFrmCstAddr').val(aItem[0]['FTAddV2Desc1']);

                        //ข้อมูลรถ
                        $('#oetCLMFrmCarCode').val(aItem[0]['FTCarCode']);
                        $('#oetCLMFrmCarName').val(aItem[0]['FTCarRegNo']);
                    }

                    //โหลดสินค้าใน Temp
                    $('.xCNClaimStep1Point1').click();
                    JSxCLMStep1Point1LoadDatatable();

                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        }else{
            //อ้างอิงเอกสารภายใน
            $('#oetCLMRefInt').val('');
            $('#oetCLMRefIntName').val('');

            //วันที่อ้างอิงเอกสารใน
            $('#oetCLMRefIntDate').val('').datepicker("refresh");
        }

    });

</script>