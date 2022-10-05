<style>
.bootstrap-select>.dropdown-toggle {
    padding: 3px;
}
</style>
<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">

            <!-- START Agency -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 <?php if( !FCNbGetIsAgnEnabled()) : echo 'xCNHide';  endif;?>">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/receiptdebtor/receiptdebtor','tRCBBrowseAgnTitle');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetRCBAgnCode" name="oetRCBAgnCode">
                        <input type="text" class="form-control xWPointerEventNone" id="oetRCBAgnName" name="oetRCBAgnName" readonly placeholder="<?php echo language('document/receiptdebtor/receiptdebtor','tRCBBrowseAgnTitle');?>">
                        <span class="input-group-btn">
                            <button id="obtRCBBrowseAgency" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- END Agency -->

            <!-- START Branch -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/receiptdebtor/receiptdebtor','tRCBBrowseBchTitle');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetRCBBchCode" name="oetRCBBchCode">
                        <input type="text" class="form-control xWPointerEventNone" id="oetRCBBchName" name="oetRCBBchName" readonly placeholder="<?php echo language('document/receiptdebtor/receiptdebtor','tRCBBrowseBchTitle');?>">
                        <span class="input-group-btn">
                            <button id="obtRCBBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- END Branch -->

            <!-- START Doc Type -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?=language('document/receiptdebtor/receiptdebtor','tRCBDocType');?></label>
                    <select class="selectpicker form-control" id="oetRCBDocType" name="oetRCBDocType">
                        <option value="ALL"><?=language('common/main/main','tAll');?></option>
                        <option value="1"><?=language('common/main/main', 'tStaDocComplete');?></option>
                        <option value="2"><?=language('common/main/main', 'tStaDocinComplete');?></option>
                        <option value="3"><?=language('common/main/main', 'tStaDocCancel');?></option>
                    </select>
                    <script>
                        $('.selectpicker').selectpicker('refresh');
                    </script>
                </div>
            </div>
            <!-- END Doc Type -->

            <!-- START Search Doc No. -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?=language('document/receiptdebtor/receiptdebtor','ค้นหาเลขที่เอกสาร');?></label>
                    <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetRCBFilterDocNo" name="oetRCBFilterDocNo" autocomplete="off" placeholder="<?=language('document/receiptdebtor/receiptdebtor','ค้นหาเลขที่เอกสาร');?>">
                </div>
            </div>
            <!-- END Search Doc No. -->

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">
                <button id="obtRCBSearch" class="btn xCNBTNPrimery" type="button" style="margin-top: 25px;width: 100%;"><?=language('common/main/main','tSearch');?></button>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">
                <button id="obtRCBClearSearch" class="btn xCNBTNDefult" type="button" style="margin-top: 25px;width: 100%;"><?=language('common/main/main','tClearSearch');?></button>
            </div>

        </div>
    </div>
    <div class="panel-body">
        <section id="ostRCBContentDatatable"></section>
    </div>
</div>

<?php include('script/jReceiptDebtorList.php') ?>