<!-- Panel เงื่อนไข-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'tIVTitlePanelCondition'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvIVCondition" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVCondition" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- ตัวแทนขาย -->
            <?php
                $tIVDataInputADCode   = "";
                $tIVDataInputADName   = "";
                if($tIVRoute  == "docInvoiceEventAdd"){
                    $tIVDataInputADCode    = $this->session->userdata('tSesUsrAgnCode');
                    $tIVDataInputADName    = $this->session->userdata('tSesUsrAgnName');
                    $tBrowseADDisabled     = '';
                    if($this->session->userdata('tSesUsrLevel') != "HQ"){
                        $tBrowseADDisabled     = 'disabled';
                    }
                }else{
                    $tIVDataInputADCode    = @$tIVFTAgnCode;
                    $tIVDataInputADName    = @$tIVFTAgnName;
                    $tBrowseADDisabled     = 'disabled';
                }
            ?>
            <script>
                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    // $('.xCNBrowseAD').hide();
                }
            </script>
            <div class="form-group xCNBrowseAD">
                <label class="xCNLabelFrm"><?= language('document/invoice/invoice', 'tIVTitlePanelConditionAD'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdIVADCode" name="ohdIVADCode" value="<?=$tIVDataInputADCode?>">
                    <input class="form-control xWPointerEventNone" type="text" id="ohdIVADName" name="ohdIVADName" value="<?=$tIVDataInputADName?>" readonly placeholder="<?= language('document/invoice/invoice', 'tIVTitlePanelConditionAD'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVBrowseAgency" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseADDisabled; ?>>
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>

            <!--สาขา-->
            <?php
                $tIVDataInputBchCode   = "";
                $tIVDataInputBchName   = "";
                if($tIVRoute  == "docInvoiceEventAdd"){
                    $tIVDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                    $tIVDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                    $tBrowseBchDisabled     = '';
                }else{
                    $tIVDataInputBchCode    = @$tIVFTBchCode;
                    $tIVDataInputBchName    = @$tIVFTBchName;
                    $tBrowseBchDisabled     = 'disabled';
                }
            ?>
            <script>
                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    //BCH - SHP
                    var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                    if(tBchCount < 2){
                        $('#obtIVBrowseBranch').attr('disabled',true);
                    }
                }
            </script>
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQBranch'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdIVBchCode" name="ohdIVBchCode" value="<?= @$tIVDataInputBchCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetIVBchName" name="oetIVBchName" value="<?= @$tIVDataInputBchName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>

            <!-- คลังสินค้า -->
            <!-- <div class="form-group">
                <label class="xCNLabelFrm"><span style="color:red">*</span> <?///= language('document/invoice/invoice', 'tIVTitlePanelConditionWah'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdIVWahCode" name="ohdIVWahCode" value="<?//=@$tIVFTWahCode?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetIVWahName" name="oetIVWahName" value="<?//=@$tIVFTWahName?>" readonly placeholder="<?//= language('document/invoice/invoice', 'tIVTitlePanelConditionWah'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVBrowseWah" type="button" class="btn xCNBtnBrowseAddOn" >
                            <img src="<?//=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div> -->

             <!-- เลขที่เอกสารภายใน -->
             <!-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?//= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?></label>
                        <div class="input-group" style="width:100%;">
                            <input type="hidden" id="oetIVRefIntOld" name="oetIVRefIntOld" value="<?//=@$tFTXphRefInt?>">
                            <input type="text" class="input100 xCNHide" id="oetIVRefInt" name="oetIVRefInt" value="<?//=@$tFTXphRefInt?>">
                            <input class="form-control xWPointerEventNone" type="text" id="oetIVRefIntName" name="oetIVRefIntName" value="<?//=@$tFTXphRefInt?>" readonly placeholder="<?//= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?>">
                            <span class="input-group-btn">
                                <button id="obtIVBrowseIVRefInt" type="button" class="btn xCNBtnBrowseAddOn" >
                                    <img src="<?//=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- วันที่เอกสารภายใน -->
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?//=language('document/adjustmentcost/adjustmentcost', 'tADCRefIntDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetIVRefIntDate" name="oetIVRefIntDate" value="<?//=@$tFDXphRefIntDate;?>">
                            <span class="input-group-btn">
                                <button id="obtIVRefIntDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?//=base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- เลขที่อ้างอิงเอกสารภายนอก -->
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?//=language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?></label>
                        <input type="text" class="form-control xCNInputWithoutSpc" id="oetIVRefExt" name="oetIVRefExt" maxlength="20" placeholder="<?//=language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?>" value="<?//=@$tFTXphRefExt;?>">
                    </div>
                </div>
            </div> -->

            <!-- วันที่เอกสารภายนอก -->
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?//=language('document/producttransferbranch/producttransferbranch', 'tTBRefExtDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD"  id="oetIVRefExtDate" name="oetIVRefExtDate" value="<?//=@$tFDXphRefExtDate;?>">
                            <span class="input-group-btn">
                                <button id="obtIVRefExtDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?//=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div> -->

             <!-- อ้างอิงเอกสารใบวางบิล -->
             <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?=language('document/invoice/invoice', 'tIVTitlePanelRefSB'); ?></label>
                        
                        <input type="hidden" id="oetIVRefSBInt_Old" name="oetIVRefSBInt_Old" value="<?=@$tFTXphBillDoc?>" >
                        <input type="text" class="form-control xCNInputWithoutSpc" id="oetIVRefSBInt" autocomplete="off"
                        name="oetIVRefSBInt" maxlength="20" placeholder="<?=language('document/invoice/invoice', 'tIVTitlePanelRefSB'); ?>" value="<?=@$tFTXphBillDoc?>">
                        <label id="olbIVRefSBInt"><font color="red"><?= language('document/quotation/quotation', 'tTQPlsEnterDocDates'); ?></font></label>
                    </div>
                </div>
            </div>

            <!-- วันที่อ้างอิงเอกสารใบวางบิล -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?=language('document/invoice/invoice', 'tIVTitlePanelRefSBDate'); ?></label>
                        <div class="input-group">
                            <input type="hidden" class="form-control xCNDatePicker xCNInputMaskDate" autocomplete="off" placeholder="YYYY-MM-DD"  id="oetIVRefSBIntDate_Old" name="oetIVRefSBIntDate_Old" value="<?=@$tFDXphBillDue?>">
                            <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" autocomplete="off" placeholder="YYYY-MM-DD"  id="oetIVRefSBIntDate" name="oetIVRefSBIntDate" value="<?=@$tFDXphBillDue?>">
                            <span class="input-group-btn">
                                <button id="obtIVRefSBIntDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                </button>
                            </span>
                        </div>
                        <label id="olbIVRefSBDate"><font color="red"><?= language('document/quotation/quotation', 'tTQPlsEnterDocDates'); ?></font></label>
                    </div>
                </div>
            </div>

            <!-- ที่อยู่ใบกำกับภาษี -->
            <div class="row xCNIVFrmBrowseTaxAdd">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"></div>
                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

                            <input type="hidden" id="ohdIVFrmTaxAdd" name="ohdIVFrmTaxAdd" value="<?=@$nFNXphTaxAdd?>">

                            <input type="hidden" id="ohdIVTaxAddSeqNo" name="ohdIVTaxAddSeqNo" value="<?=@$tTAX_FNAddSeqNo?>">
                            <input type="hidden" id="ohdIVTaxAddTaxNo" name="ohdIVTaxAddTaxNo" value="<?=@$tTAX_FTAddTaxNo?>">
                            <input type="hidden" id="ohdIVTaxAddName" name="ohdIVTaxAddName" value="<?=@$tTAX_FTAddName?>">
                            <input type="hidden" id="ohdIVTaxTel" name="ohdIVTaxTel" value="<?=@$tTAX_FTAddTel?>">
                            <input type="hidden" id="ohdIVTaxFax" name="ohdIVTaxFax" value="<?=@$tTAX_FTAddFax?>">

                            <!-- Addr Version 1 -->
                            <input type="hidden" id="ohdIVTaxAddV1No" name="ohdIVTaxAddV1No" value="<?=@$tTAX_FTAddV1No?>">
                            <input type="hidden" id="ohdIVTaxV1Soi" name="ohdIVTaxV1Soi" value="<?=@$tTAX_FTAddV1Soi?>">
                            <input type="hidden" id="ohdIVTaxV1Village" name="ohdIVTaxV1Village" value="<?=@$tTAX_FTAddV1Village?>">
                            <input type="hidden" id="ohdIVTaxV1Road" name="ohdIVTaxV1Road" value="<?=@$tTAX_FTAddV1Road?>">
                            <input type="hidden" id="ohdIVTaxV1SubDistrict" name="ohdIVTaxV1SubDistrict" value="<?=@$tTAX_FTSudName?>">
                            <input type="hidden" id="ohdIVTaxV1District" name="ohdIVTaxV1District" value="<?=@$tTAX_FTDstName?>">
                            <input type="hidden" id="ohdIVTaxV1Province" name="ohdIVTaxV1Province" value="<?=@$tTAX_FTPvnName?>">
                            <input type="hidden" id="ohdIVTaxV1PostCode" name="ohdIVTaxV1PostCode" value="<?=@$tTAX_FTAddV1PostCode?>">

                            <!-- Addr Version 2 -->
                            <input type="hidden" id="ohdIVTaxAddV2Desc1" name="ohdIVTaxAddV2Desc1" value="<?=@$tTAX_FTAddV2Desc1?>">
                            <input type="hidden" id="ohdIVTaxAddV2Desc2" name="ohdIVTaxAddV2Desc2" value="<?=@$tTAX_FTAddV2Desc2?>">

                            <button type="button" id="obtIVFrmBrowseTaxAdd" class="btn xCNBTNSubSave" style="width:100%;" data-codebrowse="2">
                                <?=language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoTaxAddress');?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== อ้างอิงเอกสารภายใน ======================================= -->
<div id="odvIVModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/invoice/invoice','tIVTitlePanelRefDO')?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvIVFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== กำหนดที่อยู่ ============================================= -->
<div id="odvIVModalAddress" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">กำหนดที่อยู่</label>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-12">
                        <!--ที่อยู่-->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPAddrName'); ?></label>
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="input100 xCNHide" id="ohdIVAddrCode" name="ohdIVAddrCode" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrName" name="ohdIVAddrName" value="" readonly placeholder="<?= language('company/company/company', 'tCMPAddrName'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtIVBrowseAddr" type="button" class="btn xCNBtnBrowseAddOn" >
                                        <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- ที่อยู่แยก -->
                    <div class="xWIVAddress1">
                        <div class="col-lg-12">
                            <!--หมายเลขประชำตัวผู้เสียภาษีของที่อยู่-->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPTaxNo'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrTaxNo" name="ohdIVAddrTaxNo" value="" readonly placeholder="<?= language('company/company/company', 'tCMPTaxNo'); ?>">
                            </div>

                            <!--บ้านเลขที่-->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPAddressNo'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrNoHouse" name="ohdIVAddrNoHouse" value="" readonly placeholder="<?= language('company/company/company', 'tCMPAddressNo'); ?>">
                            </div>

                            <!--หมู่บ้าน / อาคาร-->
                            <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPVillage'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrVillage" name="ohdIVAddrVillage" value="" readonly placeholder="<?= language('company/company/company', 'tCMPVillage'); ?>">
                            </div>

                            <!--ถนน-->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPRoad'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrRoad" name="ohdIVAddrRoad" value="" readonly placeholder="<?= language('company/company/company', 'tCMPRoad'); ?>">
                            </div>

                        </div>

                        <!--แขวง / ตำบล-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPSubDistrict'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrSubDistrict" name="ohdIVAddrSubDistrict" value="" readonly placeholder="<?= language('company/company/company', 'tCMPSubDistrict'); ?>">
                            </div>
                        </div>

                        <!--เขต / อำเภอ-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPDistict'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrDistict" name="ohdIVAddrDistict" value="" readonly placeholder="<?= language('company/company/company', 'tCMPDistict'); ?>">
                            </div>
                        </div>

                        <!--จังหวัด-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPProvince'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrProvince" name="ohdIVAddrProvince" value="" readonly placeholder="<?= language('company/company/company', 'tCMPProvince'); ?>">
                            </div>
                        </div>

                        <!--รหัสไปรณีย์-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPZipCode'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdIVZipCode" name="ohdIVZipCode" value="" readonly placeholder="<?= language('company/company/company', 'tCMPZipCode'); ?>">
                            </div>
                        </div>


                    </div>

                    <!-- ที่อยู่รวม -->
                    <div class="xWIVAddress2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'ที่อยู่ 1'); ?></label>
                                <textarea class="form-control" id="ohdIVAddV2Desc1" name="ohdIVAddV2Desc1" maxlength="200" readonly></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'ที่อยู่ 2'); ?></label>
                                <textarea class="form-control" id="ohdIVAddV2Desc2" name="ohdIVAddV2Desc2" maxlength="200" readonly></textarea>
                            </div>
                        </div>
                    </div>

                    <!--เบอร์โทร-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPTel'); ?></label>
                            <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrTel" name="ohdIVAddrTel" value="" readonly placeholder="<?= language('company/company/company', 'tCMPTel'); ?>">
                        </div>
                    </div>

                    <!--เบอร์สาร-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPFax'); ?></label>
                            <input class="form-control xWPointerEventNone" type="text" id="ohdIVAddrFax" name="ohdIVAddrFax" value="" readonly placeholder="<?= language('company/company/company', 'tCMPFax'); ?>">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmAddress" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSxConfirmAddress();" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ถ้ามีที่อยู่เเล้ว เมื่อเปลี่ยนสาขา ================================ -->
<div id="odvIVModalAddressRemove" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalWarning')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?=language('common/main/main','tModalAddressClear')?></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmRemoveAddress" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== กรุณากรอกเลขที่ใบวางบิล ================================ -->
<div id="odvIVModalBillNoteIsNull" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalWarning')?></label>
            </div>
            <div class="modal-body">
                <span>กรุณากรอกอ้างอิงเอกสารใบวางบิลและวันที่อ้างอิงเอกสารใบวางบิล และกดปุ่มบันทึก ก่อนทำการอนุมัติ</span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalConfirm')?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== กรุณากรอกเลขที่ใบวางบิล ================================ -->
<div id="odvIVModalDateIsNull" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalWarning')?></label>
            </div>
            <div class="modal-body">
                <span>กรุณากรอกวันที่กำหนดชำระ และกดปุ่มบันทึก ก่อนทำการอนุมัติ</span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalConfirm')?></button>
            </div>
        </div>
    </div>
</div>


<script>
    $('#olbIVRefSBInt').hide();
    $('#olbIVRefSBDate').hide();
    $('#obtIVBrowseIVRefInt').on('click',function(){

        if($('#ohdIVSPLCode').val() == "" || $('#ohdIVSPLCode').val() == null){
            $('#odvIVModalPleseselectSPL').modal('show');
            return;
        }
        
        JSxCallPageIVRefIntDoc();
    });

    //Ref เอกสารใบรับของ
    function JSxCallPageIVRefIntDoc(){
        var tBCHCode = $('#ohdIVBchCode').val();
        var tBCHName = $('#oetIVBchName').val();

        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docInvoiceRefIntDoc",
            data: {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvIVModalRefIntDoc #odvIVFromRefIntDoc').html(oResult);
                $('#odvIVModalRefIntDoc').modal({backdrop : 'static' , show : true});
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดยืนยัน Ref เอกสารอ้างอิง
    $('#obtConfirmRefDocInt').click(function(){
        var tRefIntDocNo    =  $('.xDocuemntRefInt.active').data('docno');
        var tRefIntDocDate  =  $('.xDocuemntRefInt.active').data('docdate');
        var tRefIntBchCode  =  $('.xDocuemntRefInt.active').data('bchcode');
        var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
            return $(this).val();
        }).get();

        //ถ้าไม่เลือกเอกสารอ้างอิงมา
        if(tRefIntDocNo != undefined){

            var tSplStaVATInOrEx =  $('.xDocuemntRefInt.active').data('vatinroex');
            var cSplCrLimit      =  $('.xDocuemntRefInt.active').data('crtrem');
            var nSplCrTerm       =  $('.xDocuemntRefInt.active').data('crlimit');
            var tSplCode         =  $('.xDocuemntRefInt.active').data('splcode');
            var tSplName         =  $('.xDocuemntRefInt.active').data('splname');
            var tSPlPaidType     =  $('.xDocuemntRefInt.active').data('dstpain');
            var tVatcode         =  $('.xDocuemntRefInt.active').data('vatcode');
            var nVatrate         =  $('.xDocuemntRefInt.active').data('vatrate');

            $('#oetIVDocRefInt').val(tRefIntDocNo);
            $('#oetIVDocRefIntName').val(tRefIntDocNo);
            if($("#ocbIVRefDoc").val() == 1){
                $('#oetIVRefKey').val('DO');
            }else if($("#ocbIVRefDoc").val() == 2){
                $('#oetIVRefKey').val('PO');
            }else{
                $('#oetIVRefKey').val('ABB');
            }
            $('#oetIVRefDocDate').val(tRefIntDocDate).datepicker("refresh");
           let tVatInorEx = $('#ocmIVfoVatInOrEx').val(); 

            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docInvoiceRefIntDocInsertDTToTemp",
                data    : {
                    'tIVDocNo'          : $('#oetIVDocNo').val(),
                    'tIVFrmBchCode'     : $('#ohdIVBchCode').val(),
                    'tRefIntDocNo'      : tRefIntDocNo,
                    'tRefIntBchCode'    : tRefIntBchCode,
                    'tSplStaVATInOrEx'  : tVatInorEx,
                    'aSeqNo'            : aSeqNo,
                    'tIVSPLStaLocal'    : $('#ohdIVSPLStaLocal').val(),
                    'tIVTypeRefDoc'     : $('#ocbIVRefDoc').val()
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    //โหลดสินค้าใน Temp
                    JSvIVLoadPdtDataTableHtml();

                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        }else{
            //อ้างอิงเอกสารภายใน
            $('#oetIVRefInt').val('');
            $('#oetIVRefIntName').val('');

            //วันที่อ้างอิงเอกสารใน
            $('#oetIVRefIntDate').val('').datepicker("refresh");
        }

    });

    //หลังจากเลือกเอกสารอ้างอิงแล้ว ต่้องเอาผู้จำหน่ายมาใส่
    function JSxIVSetConditionAfterSelectRefIN(poDataNextFunc){
        var aData = poDataNextFunc;

        if (poDataNextFunc  != "NULL") {
            $('#oetPanel_SplName').val((aData.FTSplName == '') ? '-' : aData.FTSplName);

            // ประเภทภาษี
            if(aData.FTSplStaVATInOrEx == 1){
                // รวมใน
                $("#ocmIVfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            }else{
                // แยกนอก
                $("#ocmIVfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
            }

            // ประเภทชำระเงิน
            // if(aData.FCSplCrLimit > 0){
            //     // เงินเชื่อ
            //     $("#ocmIVPaymentType.selectpicker").val("2").selectpicker("refresh");
            //     $('.xCNPanel_CreditTerm').show();
            // }else{
            //     // เงินสด
            //     $("#ocmIVPaymentType.selectpicker").val("1").selectpicker("refresh");
            //     $('.xCNPanel_CreditTerm').hide();
            // }

            // ระยะเครดิต
            // $("#oetIVCreditTerm").val(aData.FCSplCrLimit);

            // การชำระเงิน
            if(aData.FTXphDstPaid == 1){ // ต้นทาง
                $("#ocmIVDstPaid.selectpicker").val("1").selectpicker("refresh");
            }else{ // ปลายทาง
                $("#ocmIVDstPaid.selectpicker").val("2").selectpicker("refresh");
            }

            // Vat จาก SPL
            // $('#ohdIVFrmSplVatCode').val(aData.FTVatCode);
            // $('#ohdIVFrmSplVatRate').val(aData.FCVatRate);
        }
    }


    var nStaShwAddress = <?=$nStaShwAddress?>;
    if( nStaShwAddress == 1 ){
        $('.xWIVAddress1').show();
        $('.xWIVAddress2').hide();
    }else{
        $('.xWIVAddress1').hide();
        $('.xWIVAddress2').show();
    }
    

</script>
