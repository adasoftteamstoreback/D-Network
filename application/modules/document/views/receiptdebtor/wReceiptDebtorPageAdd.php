<?php
     /* HD INFO */
    $tXshDocNo         = (empty($aDataDocHD['FTXshDocNo']) ? '-' : $aDataDocHD['FTXshDocNo']);
    $dXshDocDate       = (empty($aDataDocHD['FDXshDocDate']) ? '-' : $aDataDocHD['FDXshDocDate']);
    $tXshDocTime       = (empty($aDataDocHD['FTXshDocTime']) ? '-' : $aDataDocHD['FTXshDocTime']);
    $tCreateBy         = (empty($aDataDocHD['FTCreateBy']) ? '-' : $aDataDocHD['FTCreateBy']);
    $tBchCode          = (empty($aDataDocHD['FTBchCode']) ? '-' : $aDataDocHD['FTBchCode']);
    $tBchName          = (empty($aDataDocHD['FTBchName']) ? '-' : $aDataDocHD['FTBchName']);
    $tAgnCode          = (empty($aDataDocHD['FTAgnCode']) ? '-' : $aDataDocHD['FTAgnCode']);
    $tAgnName          = (empty($aDataDocHD['FTAgnName']) ? '-' : $aDataDocHD['FTAgnName']);
    $tUsrCreateName    = (empty($aDataDocHD['FTUsrCreateName']) ? '' : $aDataDocHD['FTUsrCreateName']);
    $tXshRmk           = $aDataDocHD['FTXshRmk'];
    $tXshCond          = $aDataDocHD['FTXshCond'];

    /* CST INFO */
    $tCstCode          = (empty($aDataCst['FTCstCode']) ? '-' : $aDataCst['FTCstCode']);
    $tCstName          = (empty($aDataCst['FTCstName']) ? '-' : $aDataCst['FTCstName']);
    $tCstTel           = (empty($aDataCst['FTCstTel']) ? '-' : $aDataCst['FTCstTel']);
    $tCstEmail         = (empty($aDataCst['FTCstEmail']) ? '-' : $aDataCst['FTCstEmail']);
    $nAddrVersion      = (empty($aDataCst['FTAddVersion']) ? '-' : $aDataCst['FTAddVersion']); 
    // Address ver.1
    $tCstAddV1No            = (empty($aDataCst['FTAddV1No']) ? '-' : $aDataCst['FTAddV1No']);
    $tCstAddV1Soi           = (empty($aDataCst['FTAddV1Soi']) ? '-' : $aDataCst['FTAddV1Soi']);
    $tCstAddV1Village       = (empty($aDataCst['FTAddV1Village']) ? '-' : $aDataCst['FTAddV1Village']);
    $tCstAddV1Road          = (empty($aDataCst['FTAddV1Road']) ? '-' : $aDataCst['FTAddV1Road']);
    $tCstAddV1SubDist       = (empty($aDataCst['FTSudName']) ? '-' : $aDataCst['FTSudName']);
    $tCstAddV1DstCode       = (empty($aDataCst['FTDstName']) ? '-' : $aDataCst['FTDstName']);
    $tCstAddV1PvnCode       = (empty($aDataCst['FTPvnName']) ? '-' : $aDataCst['FTPvnName']);
    $tCstAddV1PostCode      = (empty($aDataCst['FTAddV1PostCode']) ? '-' : $aDataCst['FTAddV1PostCode']);
    // Address ver.2
    $tCstAddV2Desc1         = (empty($aDataCst['FTAddV2Desc1']) ? '-' : $aDataCst['FTAddV2Desc1']);
    $tCstAddV2Desc2         = (empty($aDataCst['FTAddV2Desc2']) ? '-' : $aDataCst['FTAddV2Desc2']);
    $tAddTaxNo          = (empty($aDataCst['FTAddTaxNo']) ? '-' : $aDataCst['FTAddTaxNo']);
    $tAddStaBusiness    = (empty($aDataCst['FTAddStaBusiness']) ? '-' : $aDataCst['FTAddStaBusiness']);
    $tAddStaHQ          = (empty($aDataCst['FTAddStaHQ']) ? '-' : $aDataCst['FTAddStaHQ']);
    $tAddStaBchCode     = (empty($aDataCst['FTAddStaBchCode']) ? '-' : $aDataCst['FTAddStaBchCode']);

    /* ข้อมูลท้ายบิล */
    $nXshTotal          = $aDataDocHD['FCXshTotal'];
    $nXshWht            = $aDataDocHD['FCXshWht'];
    $nXshAfWht          = $aDataDocHD['FCXshAfWht'];
    $nXshInterest       = $aDataDocHD['FCXshInterest'];
    $nXshDisc           = $aDataDocHD['FCXshDisc'];
    $nXshAfDisc         = $aDataDocHD['FCXshAfDisc'];
    $nXshAmt            = $aDataDocHD['FCXshAmt'];
    $nXshPay            = $aDataDocHD['FCXshPay'];
    $nXshChgCredit      = $aDataDocHD['FCXshChgCredit'];
    $nXshGnd            = $aDataDocHD['FCXshGnd'];
    $tXshGndText        = $aDataDocHD['FTXshGndText'];

    //อื่นๆ
    $nStaRef        = $aDataDocHD['FNXshStaRef'];
    $nStaPrint        = $aDataDocHD['FNXshDocPrint'];
    $nStaUploadFile         = 2;
?>

<form id="ofmTransferreceiptFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
            <input type="hidden" id="ohdStaRef" value="<?=$nStaRef?>">
            <!-- Panel ข้อมูลเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?=language('document/abbsalerefund/abbsalerefund', 'tABBDocInfoTitle');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvABBDocumentInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvABBDocumentInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">

                        <div class="mb-3 row">
                            <label  class="col-md-4 col-form-label"><strong><?=language('document/abbsalerefund/abbsalerefund', 'tABBDocNo');?></strong></label>
                            <div id="odvABBDocNo" class="col-md-8"><?=$tXshDocNo?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?=language('document/abbsalerefund/abbsalerefund', 'tABBDocDate');?></strong></label>
                            <div class="col-md-8"><?=date_format(date_create($dXshDocDate),'d/m/Y')?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?=language('document/abbsalerefund/abbsalerefund', 'tABBDocTime');?></strong></label>
                            <div class="col-md-8"><?=$tXshDocTime?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?=language('document/abbsalerefund/abbsalerefund', 'tABBUsrCreate');?></strong></label>
                            <div class="col-md-8"><?=$tUsrCreateName?></div>
                        </div>

                        <hr style='margin: 5px;'>

                        <div class="mb-3 row <?php if( !FCNbGetIsAgnEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="col-md-4 col-form-label"><strong><?=language('document/abbsalerefund/abbsalerefund', 'tABBBrowseAgnTitle');?></strong></label>
                            <div class="col-md-8">
                                <?php
                                    if( $tAgnCode != "-" ){
                                        echo "(".$tAgnCode.") ".$tAgnName;
                                    }else{
                                        echo "-";
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?=language('document/abbsalerefund/abbsalerefund', 'tABBBrowseBchTitle');?></strong></label>
                            <div class="col-md-8">(<?=$tBchCode?>) <?=$tBchName?></div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Panel ข้อมูลเอกสาร -->

            <!-- Panel ข้อมูลลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?=language('document/abbsalerefund/abbsalerefund', 'tABBAnotherTitle');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvABBCustomerInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvABBCustomerInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">

                        <!-- <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/receiptdebtor/receiptdebtor','tRCBStaRefund');?></label>
                            <select class="selectpicker form-control" id="oetRCBStaRefund" name="oetRCBStaRefund" style="display: inline !important;" disabled>
                                <option value="1"><?=language('document/receiptdebtor/receiptdebtor', 'tRCBStaRefund1');?></option>
                                <option value="2"><?=language('document/receiptdebtor/receiptdebtor', 'tRCBStaRefund2');?></option>
                            </select>
                        </div> -->

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/deposit/deposit','tStaRef');?></label>
                            <select class="selectpicker form-control" id="oetRCBStaRef" name="oetRCBStaRef" style="display: inline !important;" disabled>
                                <option value="0"><?=language('document/deposit/deposit', 'tStaRef0');?></option>
                                <option value="1"><?=language('document/deposit/deposit', 'tStaRef1');?></option>
                                <option value="2"><?=language('document/deposit/deposit', 'tStaRef2');?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/receiptdebtor/receiptdebtor', 'tRCBDocPrint');?></label>
                            <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control text-right" type="text" placeholder="<?= language('common/main/main', 'tAddV1No') ?>" value="<?=$nStaPrint?>" readonly>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvRCBReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvRCBDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvRCBDataFile" class="xCNMenuPanelData panel-collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvDOShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var oSOCallDataTableFile = {
                        ptElementID     : 'odvDOShowDataTable',
                        ptBchCode       : '<?= $nStaUploadFile ?>',
                        ptDocNo         : '<?= $nStaUploadFile ?>',
                        ptDocKey        : 'TARTSpHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : ''
                        //JSxSoCallBackUploadFile -- ดูข้อมูลไฟล์แนบ
                    }
                    JCNxUPFCallDataTable(oSOCallDataTableFile);
                </script>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
            <div class="row">

                <!-- Panel ที่อยู่ลูกค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;min-height:200px;">
                        <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?=language('document/receiptdebtor/receiptdebtor', 'tRCBCstAddr');?></label>
                        </div>
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body" style="padding-left: 1px; padding-right: 1px;" >
                                <div class="col-lg-3"><!--รหัสลูกค้า-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?=language('document/abbsalerefund/abbsalerefund', 'tABBCstCode');?></label>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1No') ?>" value="<?=$tCstCode?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3"><!--ชื่อลูกค้า-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?=language('document/abbsalerefund/abbsalerefund', 'tABBCstName');?></label>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1No') ?>" value="<?=$tCstName?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3"><!--เบอร์โทร-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?=language('document/abbsalerefund/abbsalerefund', 'tABBCstTel');?></label>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1No') ?>" value="<?=$tCstTel?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3"><!--อีเมล-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?=language('document/abbsalerefund/abbsalerefund', 'tABBCstEmail');?></label>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1No') ?>" value="<?=$tCstEmail?>" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-3"><!--เลขประจำตัวผู้เสียภาษี-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/taxinvoice/taxinvoice', 'tTAXNumber'); ?></label>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" value="<?=$tAddTaxNo?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3"><!--ประเภทกิจการ-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/taxinvoice/taxinvoice', 'tTAXTypeBusiness'); ?></label>
                                        <?php
                                            $tBusinessName = "";
                                            if ($tAddStaBusiness == 1) {
                                                $tBusinessName = language('document/taxinvoice/taxinvoice', 'tTAXTypeBusiness1');
                                            }else{
                                                $tBusinessName = language('document/taxinvoice/taxinvoice', 'tTAXTypeBusiness2');
                                            }
                                        ?>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" value="<?=$tBusinessName?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3"><!--สถานประกอบการ-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/taxinvoice/taxinvoice', 'tTAXBusiness'); ?></label>
                                        <?php
                                            $tHQName = "";
                                            if ($tAddStaHQ == 1) {
                                                $tHQName = language('document/taxinvoice/taxinvoice', 'tTAXBusiness1');
                                            }else{
                                                $tHQName = language('document/taxinvoice/taxinvoice', 'tTAXBusiness2');
                                            }
                                        ?>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" value="<?=$tHQName?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3"><!--รหัสสาขา-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/taxinvoice/taxinvoice', 'tTAXBranch'); ?></label>
                                        <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" value="<?=$tAddStaBchCode?>" readonly>
                                    </div>
                                </div>

                                <?php if ($nAddrVersion == 1) { ?>
                                    <div class="col-lg-6"><!--บ้านเลขที่-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1No'); ?></label>
                                            <input name="oetFTAddV1No" id="oetFTAddV1No"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1No') ?>" value="<?=$tCstAddV1No?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6"><!--ซอย-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1Soi'); ?></label>
                                            <input name="oetFTAddV1Soi" id="oetFTAddV1Soi"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1Soi') ?>" value="<?=$tCstAddV1Soi?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6"><!--หมู่บ้าน-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1Village'); ?></label>
                                            <input name="oetFTAddV1Village" id="oetFTAddV1Village"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1Village') ?>" value="<?=$tCstAddV1Village?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6"><!--ถนน-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1Road'); ?></label>
                                            <input name="oetFTAddV1Road" id="oetFTAddV1Road"  maxlength="5" class="form-control" type="text" placeholder="<?= language('common/main/main', 'tAddV1Road') ?>" value="<?=$tCstAddV1Road?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6"><!--ตำบล/แขวง-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1SubDist1'); ?>/<?= language('common/main/main', 'tAddV1SubDist2'); ?></label>
                                            <input name="oetFTAddV1SubDistName" id="oetFTAddV1SubDistName" class="form-control" readonly type="text" value="<?=$tCstAddV1SubDist?>" placeholder="<?= language('common/main/main', 'tAddV1SubDist1'); ?>/<?= language('common/main/main', 'tAddV1SubDist2'); ?>" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6"><!--อำเภอ/เขต-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1Dst1'); ?><?= language('common/main/main', 'tAddV1Dst2'); ?></label>
                                            <input name="oetFTAddV1DstName" id="oetFTAddV1DstName" class="form-control xCNClearValue xWETaxDisabled xWETaxEnabledOniNetError" readonly type="text" value="<?=$tCstAddV1DstCode?>" placeholder="<?= language('common/main/main', 'tAddV1Dst1'); ?><?= language('common/main/main', 'tAddV1Dst2'); ?>" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6"><!--จังหวัด-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1Pvn'); ?></label>
                                            <input name="oetFTAddV1PvnName" id="oetFTAddV1PvnName" class="form-control xCNClearValue xWETaxDisabled xWETaxEnabledOniNetError" readonly type="text" value="<?=$tCstAddV1PvnCode?>" placeholder="<?= language('common/main/main', 'tAddV1Pvn') ?>" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6"><!--รหัสไปรษณีย์-->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tAddV1Post'); ?></label>
                                            <input name="oetFTAddV1PostCode" id="oetFTAddV1PostCode"  maxlength="5" class="form-control xCNClearValue xWETaxDisabled xWETaxEnabledOniNetError" type="text" value="<?=$tCstAddV1PostCode?>" placeholder="<?= language('common/main/main', 'tAddV1Post') ?>" readonly>
                                        </div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="col-lg-6">
                                        <!--ที่อยู่ 1 -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('document/taxinvoice/taxinvoice', 'tTAXAddress1'); ?></label>
                                            <textarea id="otxAddress1" class="xWETaxDisabled xWETaxEnabledOniNetError" rows="4" style="resize: none;" readonly><?=$tCstAddV2Desc1?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!--ที่อยู่ 2 -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('document/taxinvoice/taxinvoice', 'tTAXAddress2'); ?></label>
                                            <textarea id="otxAddress2" class="xWETaxDisabled xWETaxEnabledOniNetError" rows="4" style="resize: none;" readonly><?=$tCstAddV2Desc2?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ตารางสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-top:25px;margin-bottom:25px;position:relative;min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <div style="margin-top: 10px;">

                                    <!-- ค้นหา -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">

                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?=language('common/main/main','tSearchNew');?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetRCBFilterPdt" name="oetRCBFilterPdt" onkeyup="JSvRCBCSearchPdtHTML()" autocomplete="off" placeholder="<?=language('document/receiptdebtor/receiptdebtor','tDocSearchPlaceHolder');?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn xCNBtnSearch" type="button" onclick="JSvRCBCSearchPdtHTML()" >
                                                            <img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End ค้นหา -->

                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right"></div>
                                    </div>

                                    <!-- แสดงรายการสินค้า -->
                                    <div class="row p-t-10">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="table-responsive">
                                                <table id="otbRCBPdtTableList" class="table xWPdtTableFont">
                                                    <thead>
                                                        <tr class="xCNCenter">
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBSeqNo')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBRefExt')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBInvType')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBInvNo')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBInvDate')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBInvGrand')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBInvPaid')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBInvRem')?></th>
                                                            <th nowrap><?=language('document/receiptdebtor/receiptdebtor','tRCBInvPay')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        if($aDataDT['tCode'] == 1){
                                                            foreach($aDataDT['aItems'] as $nDataTableKey => $aDataVal) { ?>
                                                                <tr class="text-center xCNTextDetail2 xWPdtItem">
                                                                    <td nowrap  class="text-center"><?=$aDataVal['FNXsdSeqNo']?></td>
                                                                    <td nowrap  class="text-left"><?=$aDataVal['FTXsdRefExt']?></td>
                                                                    <td nowrap  class="text-left">
                                                                        <?php
                                                                            $tInvType = '';
                                                                            switch ($aDataVal['FNXsdInvType']) {
                                                                                case 1:
                                                                                    $tInvType = 'tRCBInvType1';
                                                                                    break;
                                                                                case 2:
                                                                                    $tInvType = 'tRCBInvType2';
                                                                                    break;
                                                                                case 3:
                                                                                    $tInvType = 'tRCBInvType3';
                                                                                    break;
                                                                                case 4:
                                                                                    $tInvType = 'tRCBInvType4';
                                                                                    break;
                                                                                default:
                                                                                    $tInvType = '-';
                                                                                    break;
                                                                            }
                                                                        ?>
                                                                        <?=language('document/receiptdebtor/receiptdebtor',$tInvType)?>
                                                                    </td>
                                                                    <td nowrap  class="text-left"><?=$aDataVal['FTXsdInvNo']?></td>
                                                                    <td nowrap  class="text-center"><?=date_format(date_create($aDataVal['FDXsdInvDate']),'d/m/Y')?></td>
                                                                    <td nowrap  class="text-right"><?=number_format($aDataVal['FCXsdInvGrand'],$nOptDecimalShow)?></td>
                                                                    <td nowrap  class="text-right"><?=number_format($aDataVal['FCXsdInvPaid'],$nOptDecimalShow)?></td>
                                                                    <td nowrap  class="text-right"><?=number_format($aDataVal['FCXsdInvRem'],$nOptDecimalShow)?></td>
                                                                    <td nowrap  class="text-right"><?=number_format($aDataVal['FCXsdInvPay'],$nOptDecimalShow)?></td>
                                                                </tr>
                                                    <?php
                                                            }
                                                        }else{
                                                    ?>
                                                            <tr><td class="text-center xCNTextDetail2 xWTWITextNotfoundDataPdtTable" colspan="100%"><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                                                    <?php
                                                        }
                                                    ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- END แสดงรายการสินค้า -->

                                    <?php include('wReceiptDebtorEndOfBill.php'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- จบตารางสินค้า -->

                <!-- Panel การชำระเงิน -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?=language('document/abbsalerefund/abbsalerefund', 'tABBRevTitle');?></label>
                        </div>
                        <div id="odvCSSReceiveInfo" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table id="otbCSSPdtTableList" class="table xWPdtTableFont">
                                        <thead>
                                            <tr class="xCNCenter xCNPanelHeadColorWhite">
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBSeqNo')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBRcvType')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBRefNo1')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBRefNo2')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBBnkName')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBBnkBch')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBRefDate')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBChgCreditPer')?><b>(%)</b></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBChgCreditAmt')?></th>
                                                <th nowrap style="color: #232C3D !important;"><?=language('document/receiptdebtor/receiptdebtor','tRCBNet')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if( $aDataDocRC['tCode'] == '1' ){
                                                    $cXrcNetSum = 0;
                                                    foreach($aDataDocRC['aItems'] as $nDataTableKey => $aDataVal){
                                                        $cXrcNetSum += $aDataVal['FCXrcNet'];
                                            ?>
                                                        <tr class="text-center xCNTextDetail2 xWPdtItem" >
                                                            <td nowrap style="color: #232C3D !important;"  class="text-center"><?=$aDataVal['FNXrcSeqNo']?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-left"><?="(".$aDataVal['FTRcvCode'].") ".$aDataVal['FTRcvName'];?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-left"><?=$aDataVal['FTXrcRefNo1']?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-left"><?=$aDataVal['FTXrcRefNo2']?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-left"><?=$aDataVal['FTBnkName']?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-left"><?=$aDataVal['FTXrcBnkBch']?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-center">
                                                            <?php
                                                                if ($aDataVal['FDXrcRefDate'] != '') {
                                                                    echo date_format(date_create($aDataVal['FDXrcRefDate']),'d/m/Y');
                                                                }else{
                                                                    echo " ";
                                                                }
                                                            ?>
                                                            </td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-right"><?=number_format($aDataVal['FCXrcChgCreditPer'],$nOptDecimalShow)?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-right"><?=number_format($aDataVal['FCXrcChgCreditAmt'],$nOptDecimalShow)?></td>
                                                            <td nowrap style="color: #232C3D !important;"  class="text-right"><?=number_format($aDataVal['FCXrcNet'],$nOptDecimalShow)?></td>
                                                        </tr>
                                            <?php
                                                    }
                                            ?>
                                                <tr class="text-center xCNTextDetail2 xWPdtItem">
                                                    <td nowrap style="color: #232C3D !important;" class="text-right" colspan="9"><strong><?=language('document/abbsalerefund/abbsalerefund','tABBTableRcvTotal')?></strong></td>
                                                    <td nowrap style="color: #232C3D !important;" class="text-right"><strong><?=number_format($cXrcNetSum,$nOptDecimalShow)?></strong></td>
                                                </tr>
                                            <?php
                                                }else{
                                            ?>
                                                <tr><td class="text-center xCNTextDetail2 xWTWITextNotfoundDataPdtTable" colspan="100%"><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Panel การชำระเงิน -->

                <!-- Panel การอ้างอิงเอกสาร -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?=language('document/abbsalerefund/abbsalerefund', 'tABBTitleHDDocRef');?></label>
                        </div>
                        <div id="odvCSSReceiveInfo" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">

                                <div class="table-responsive" style="max-height: 260px;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="xCNCenter xCNPanelHeadColorWhite">
                                                <th nowrap style="color: #232C3D !important;" class="xCNTextBold" style="width:15%;"><?=language('document/taxinvoice/taxinvoice','tABBTitleRefType')?></th>
                                                <th nowrap style="color: #232C3D !important;" class="xCNTextBold"><?=language('document/joborder/joborder','tJOBDocRefName')?></th>
                                                <th nowrap style="color: #232C3D !important;" class="xCNTextBold"><?=language('document/taxinvoice/taxinvoice','tABBTitleRefDocNo')?></th>
                                                <th nowrap style="color: #232C3D !important;" class="xCNTextBold" style="width:15%;"><?=language('document/taxinvoice/taxinvoice','tABBTitleRefDocDate')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if( $aDataDocRef['tCode'] == '1' ){ ?>
                                                <?php foreach($aDataDocRef['aItems'] as $aValue){ ?>
                                                    <tr class="xCNTextDetail2">
                                                        <td class="text-left"><label><?=language('document/taxinvoice/taxinvoice','tABBRefType'.$aValue['FTXshRefType'])?></label></td>
                                                        <td class="text-left">
                                                            <?php
                                                                $tDocName = '';
                                                                switch ($aValue['FTXshRefKey']) {
                                                                    case 'RCVDEP':
                                                                        $tDocName = language('document/depositdoc/depositdoc','tDPSTitleMenu');
                                                                        break;

                                                                    default:
                                                                        $tDocName = '-';
                                                                        break;
                                                                }
                                                            ?>
                                                            <label><?=$tDocName?></label>
                                                        </td>
                                                        <td class="text-left"><label><?=$aValue['FTXshRefDocNo']?></label></td>
                                                        <td class="text-center"><label><?=date_format(date_create($aValue['FDXshRefDocDate']),'Y-m-d')?></label></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php }else{ ?>
                                                <tr class="xCNTextDetail2">
                                                <td class="text-center" colspan="100%"><label><?=language('common/main/main','tCMNNotFoundData')?></label></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Panel การอ้างอิงเอกสาร -->

            </div>
        </div>

    </div>

</form>
<?php include('script/jReceiptDebtorPageAdd.php'); ?>
