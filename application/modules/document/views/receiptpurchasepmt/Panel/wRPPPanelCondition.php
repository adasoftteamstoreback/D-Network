<!-- Panel เงื่อนไข--> 
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmConditionDoc'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvRPPCondition" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvRPPCondition" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            
            <!-- Start Condition ตัวแทนขาย -->
            <?php
                if ($tRPPRoute  == "docRPPEventAdd") {
                    $tDisabledAgn   = '';
                } else {
                    $tDisabledAgn   = 'disabled';
                }
            ?>
            <div class="form-group  <?php if( !FCNbGetIsAgnEnabled()) : echo 'xCNHide';  endif;?>">
                <label class="xCNLabelFrm"><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmPanelAgency'); ?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNHide" id="oetRPPAgnCode" name="oetRPPAgnCode" maxlength="5" value="<?php echo $tRPPAgnCode; ?>">
                    <input 
                        type="text"
                        class="form-control xWPointerEventNone"
                        id="oetRPPAgnName"
                        name="oetRPPAgnName"
                        placeholder="<?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmPanelAgency'); ?>"
                        lavudate-label="<?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmPanelAgency'); ?>"
                        value="<?php echo $tRPPAgnName; ?>"
                        readonly
                    >
                    <span class="xWConditionSearchPdt input-group-btn">
                        <button id="obtRPPBrowseAgency" type="button" class=" btn xCNBtnBrowseAddOn" <?php echo $tDisabledAgn; ?>>
                            <img class="xCNIconFind">
                        </button>
                    </span>
                </div>
            </div>
            <!-- End Condition ตัวแทนขาย -->


            <!-- Start Condition สาขา-->
            <?php
                $tRPPDataInputBchCode   = "";
                $tRPPDataInputBchName   = "";
                if($tRPPRoute  == "docRPPEventAdd"){
                    $tRPPDataInputBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
                    $tRPPDataInputBchName   = $this->session->userdata('tSesUsrBchNameDefault');
                    $tBrowseBchDisabled     = '';
                }else{
                    $tRPPDataInputBchCode   = @$tRPPBchCode;
                    $tRPPDataInputBchName   = @$tRPPBchName;
                    $tBrowseBchDisabled     = 'disabled';
                }
            ?>
            <script type="text/javascript">
                var tUsrLevel       = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    //BCH - SHP
                    var tBchCount   = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                    if(tBchCount < 2){
                        $('#obtRPPBrowseBranch').attr('disabled',true);
                    }
                }
            </script>
            <div class="form-group">
                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmBranch'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="oetRPPBchCode" name="oetRPPBchCode" value="<?= @$tRPPDataInputBchCode; ?>">
                    <input
                    type="text"
                        class="form-control xWPointerEventNone"
                        id="oetRPPBchName"
                        name="oetRPPBchName"
                        value="<?= @$tRPPDataInputBchName; ?>"
                        placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmBranch'); ?>"
                        readonly 
                    >
                    <span class="input-group-btn">
                        <button id="obtRPPBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>
            <!-- End Condition สาขา-->
            
        </div>
    </div>
</div>