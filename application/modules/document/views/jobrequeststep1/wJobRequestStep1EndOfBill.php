<?php
   // Control Tab Menu
    $tMenuTabDisable    = " disabled xCNCloseTabNav";
    $tMenuTabToggle     = "false";

?>
<style type="text/css">
    #odvJR1RowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvJR1RowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvJR1RowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<div class="row p-t-10" id="odvJR1RowDataEndOfBill" >
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                    <div class="custom-tabs-line tabs-line-bottom left-aligned">
                        <ul class="nav" role="tablist">
                            <li id="oliJR1TabTaxAndRemark" class="xCNBCHTab active" data-typetab="main" data-tabtitle="tabtaxandremark" style="cursor: pointer;">
                                <a role="tab" data-toggle="tab" data-target="#odvJR1TabTaxAndRemark" aria-expanded="true">
                                    <?php echo language('document/jobrequest1/jobrequest1','tJR1TabTaxAndRemark')?>
                                </a>
                            </li>
                            <!-- <li id="oliJR1TabHistory" class="xCNBCHTab<?php echo $tMenuTabDisable;?>" data-typetab="sub" data-tabtitle="tabhistory" style="cursor: pointer;"> 
                                <a role="tab" data-toggle="<?php echo $tMenuTabToggle;?>" data-target="#odvJR1TabHistory" aria-expanded="true">
                                    <?php echo language('document/jobrequest1/jobrequest1','tJR1TabHistory')?>
                                </a>
                            </li> -->
                            <!-- <li id="oliJR1TabPOStk" class="xCNBCHTab<?php echo $tMenuTabDisable;?>" data-typetab="sub" data-tabtitle="tabpostk" style="cursor: pointer;"> 
                                <a role="tab" data-toggle="<?php echo $tMenuTabToggle;?>" data-target="#odvJR1TabPOStk" aria-expanded="true">
                                    <?php echo language('document/jobrequest1/jobrequest1','tJR1TabPOStk')?>
                                </a>
                            </li> -->
                            <!-- <li id="oliJR1TabRepairStk" class="xCNBCHTab<?php echo $tMenuTabDisable;?>" data-typetab="sub" data-tabtitle="tabrepairstk" style="cursor: pointer;"> 
                                <a role="tab" data-toggle="<?php echo $tMenuTabToggle;?>" data-target="#odvJR1TabRepairStk" aria-expanded="true">
                                    <?php echo language('document/jobrequest1/jobrequest1','tJR1TabRepairStk')?>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                    <div id="odvJR1TabPanelData" class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="tab-content">
                                <!-- Tab ภาษี & หมายเหตุ -->
                                <div id="odvJR1TabTaxAndRemark" class="tab-pane active" role="tabpanel" aria-expanded="true" style="margin-top:10px;padding-top:0;padding-bottom:0;" >
                                    <div class="row" style="margin-right:-30px; margin-left:-30px;">
                                        <!-- หมายเหตุเพิ่มเติม -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea
                                                    class="form-control"
                                                    id="otaJR1Remark"
                                                    name="otaJR1Remark"
                                                    rows="3"
                                                    placeholder="<?php echo language('document/jobrequest1/jobrequest1','tJR1LabelRemark')?>"
                                                ><?=@$tJR1DocRemark;?></textarea>
                                            </div>
                                        </div>
                                        <!-- ภาษีรายการสินค้า -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="pull-left mark-font"><?= language('document/jobrequest1/jobrequest1','tJR1TBVatRate');?></div>
                                                    <div class="pull-right mark-font"><?= language('document/jobrequest1/jobrequest1','tJR1TBAmountVat');?></div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="list-group" id="oulJR1DataListVat"></ul>
                                                </div>
                                                <div class="panel-heading">
                                                    <label class="pull-left mark-font"><?= language('document/jobrequest1/jobrequest1','tJR1TBTotalValVat');?></label>
                                                    <label class="pull-right mark-font" id="olbJR1VatSum">0.00</label>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="">
            <div class="panel panel-default">
                <div class="panel-heading mark-font" id="odvJR1DataTextBath"></div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <label class="pull-left mark-font"><?= language('document/jobrequest1/jobrequest1','tJR1TBSumFCXtdNet');?></label>
                            <label class="pull-right mark-font" id="olbJR1SumFCXtdNet">0.00</label>
                            <input type="hidden" id="olbJR1SumFCXtdNetAlwDis"></label>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <label class="pull-left"><?= language('document/jobrequest1/jobrequest1','tJR1TBDisChg');?>
                                <?php if(empty($tJR1StaApv) && $tJR1StaApv != 3):?>
                                    <button type="button" class="xCNBTNPrimeryDisChgPlus" onclick="JCNvJR1MngDocDisChagHD(this)" style="float: right; margin-top: 3px; margin-left: 5px;">+</button>
                                <?php endif; ?>
                            </label>
                            <label class="pull-left" style="margin-left: 5px;" id="olbJR1DisChgHD"></label>
                            <input type="hidden" id="ohdJR1HiddenDisChgHD" />
                            <label class="pull-right" id="olbJR1SumFCXtdAmt">0.00</label>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <label class="pull-left"><?= language('document/jobrequest1/jobrequest1','tJR1TBSumFCXtdNetAfHD');?></label>
                            <label class="pull-right" id="olbJR1SumFCXtdNetAfHD">0.00</label>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <label class="pull-left"><?= language('document/jobrequest1/jobrequest1','tJR1TBSumFCXtdVat');?></label>
                            <label class="pull-right" id="olbJR1SumFCXtdVat">0.00</label>
                            <input type="hidden" name="ohdJR1SumFCXtdVat" id="ohdJR1SumFCXtdVat" value="0.00">
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                </div>
                <div class="panel-heading">
                    <label class="pull-left mark-font"><?= language('document/jobrequest1/jobrequest1','tJR1TBFCXphGrand');?></label>
                    <label class="pull-right mark-font" id="olbJR1CalFCXphGrand">0.00</label>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>
    </div>
</div>