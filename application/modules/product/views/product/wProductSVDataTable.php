<?php
//Decimal Show ลง 2 ตำแหน่ง
$nDecShow =  FCNxHGetOptionDecimalShow();
?>
<div class="">
    <table id="otbPdtProductSetData" class="table table-striped">
        <thead>
            <tr>
                <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPDTTBImg') ?></th>
                <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTSetPdtCode') ?></th>
                <th nowrap class="text-center xCNTextBold" style="width:30%;"><?php echo language('product/product/product', 'tPDTSetPdtName') ?></th>
                <th nowrap class="text-center xCNTextBold" style="width:30%;"><?php echo language('product/product/product', 'tPDTSVType') ?></th>
                <th nowrap class="text-right xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPDTSetPstQty') ?></th>
                <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPDTTBUnit') ?></th>
                <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPDTSetPstDel') ?></th>
                <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPDTSetPstEdit') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($aItems) && is_array($aItems) && !empty($aItems)) : ?>
                <?php foreach ($aItems as $key => $aValue) : ?>
                    <tr id="otrPdtSetRow<?php echo $aValue['FTPdtCodeSub']; ?>" class="xWPdtSetRow" data-psvtype="<?php echo $aValue['FTPsvType']; ?>" data-pdtcode="<?php echo $aValue['FTPdtCodeSub']; ?>" data-pdtname="<?php echo $aValue['FTPdtName']; ?>">
                        <td nowrap class="xCNBorderRight xWPdtImgtd text-center" style="padding-right:10px !important"><?= FCNtHGetImagePageList($aValue['FTImgObj'], '40px'); ?></td>
                        <td nowrap class="text-left"><?php echo $aValue['FTPdtCodeSub'] ?></td>
                        <?php if($aValue['FTPsvType'] == '2'){?>
                        <td nowrap class="text-left xWPdtSVSetName" style='color: #0081c2 !important;cursor: pointer;text-decoration: underline'><?php echo $aValue['FTPdtName'] ?></td>
                        <?php }else{?>
                        <td nowrap class="text-left"><?php echo $aValue['FTPdtName'] ?></td>
                        <?php }?>
                        <td nowrap class="text-left"><?php echo language('product/product/product', 'tPDTSVType' . $aValue['FTPsvType']) ?></td>
                        <td nowrap class="text-right"><?php echo number_format($aValue['FCPsvQty'], 4) ?></td>
                        <td nowrap class="text-left"><?php echo $aValue['FTPunName'] ?></td>
                        <td nowrap class="text-center"><img class="xCNIconTable xWPdtSVSetDelete xCNIconDelete"></td>
                        <td nowrap class="text-center"><img class="xCNIconTable xWPdtSVSetEdit xCNIconEdit"></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="xWPdtSetNoData">
                    <td class="text-center xCNTextDetail2" colspan="99"><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ===================================================== Modal Delete Product SV Set ===================================================== -->
<div id="odvModalDeletePdtSVSet" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelPdtSVSet" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelPdtSVSet" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ====================================================== End Modal Delete Product Set ======================================================= -->

<!-- ===================================================== Modal Product SV Detail ============================================================= -->
<div id="odvModalGetPdtSVSetDetail" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">ตรวจสอบเงื่อนไข</label>
            </div>
            <div class="modal-body">
                <div class="form-group" style="border-bottom: 1px solid #ccc !important;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12 col-md-3 text-left" style="border-right: 1px solid #ccc !important;">
                            <label class="xWtextbold">รหัสสินค้าบำรุง</label>
                        </div>
                        <div class="col-xs-12 col-md-9 text-left">
                            <label class="xWSVDetail1"></label>
                        </div>
                        <div class="col-xs-12 col-md-3 text-left" style="border-right: 1px solid #ccc !important;">
                            <label class="xWtextbold">ชื่อสินค้า</label>
                        </div>
                        <div class="col-xs-12 col-md-9 text-left">
                            <label class="xWSVDetail2"></label>
                        </div>
                        <div class="col-xs-12 col-md-3 text-left" style="border-right: 1px solid #ccc !important;">
                            <label class="xWtextbold">ประเภทข้อมูล</label>
                        </div>
                        <div class="col-xs-12 col-md-9 text-left">
                            <label class="xWSVDetail3"></label>
                        </div>
                    </div>
                </div>
                <span id="ospTextConfirmDelPdtSVSet" class="xCNTextModal" style="display: inline-block; word-break:break-all">ผลหลังการตรวจสอบ</span>
                <div class="form-group">
                    <div class="row" id="odvSVAnwser">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ====================================================== End Modal Delete Product Set ======================================================= -->


<?php include "script/jProductSVDataTable.php"; ?>