<?php
$nCurrentPage   = '1';
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="" class="table">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/customerreceived/customerreceived', 'tCRVBchName') ?></th>
                        <th nowrap class="xCNTextBold" style="width:12%;"><?php echo language('document/customerreceived/customerreceived', 'tCRVDocNo') ?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?php echo language('document/customerreceived/customerreceived', 'tCRVDocDate') ?></th>
                        <th nowrap class="xCNTextBold" style="width:12%;">เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:8%;">วันที่เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/customerreceived/customerreceived', 'tCRVCstName') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/customerreceived/customerreceived', 'ชนิดตู้ฝาก') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/customerreceived/customerreceived', 'ชื่อตู้ฝาก') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/customerreceived/customerreceived', 'สถานะรับของ') ?></th>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('common/main/main', 'จัดการ') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach ($aDataList['raItems'] as $nKey => $aValue) : ?>
                            <?php
                            $tCRVDocNo  = $aValue['FTXshDocNo'];
                            $tCRVBchCode  = $aValue['FTBchCode'];
                            // $tCRVRefInCode  = $aValue['CRVCREF'];

                            if (!empty($aValue['FTXshStaApv']) || $aValue['FTXshStaDoc'] == 3) {
                                $tCheckboxDisabled = "disabled";
                                $tClassDisabled = 'xCNDocDisabled';
                                $tTitle = language('document/document/document', 'tCRVCMsgCanNotDel');
                                $tOnclick = '';
                            } else {
                                $tCheckboxDisabled = "";
                                $tClassDisabled = '';
                                $tTitle = '';
                                $tOnclick = "onclick=JSoCRVDelDocSingle('" . $nCurrentPage . "','" . $tCRVDocNo . "','" . $tCRVBchCode . "','" . @$tCRVRefInCode . "')";
                            }

                            switch ($aValue['FTStaPdtPick']) {
                                case '1':
                                    $tClassStaDoc = 'text-success';
                                    $tStaDoc = 'รับครบแล้ว';
                                    break;
                                case '2':
                                    $tClassStaDoc = 'text-warning';
                                    $tStaDoc = 'รับแล้วบางส่วน';
                                    break;
                                default:
                                    $tClassStaDoc = 'text-warning';
                                    $tStaDoc = 'ยังไม่รับ';
                            }

                            $bIsApvOrCancel = ($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaApv'] == 2) || ($aValue['FTXshStaDoc'] == 3);
                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" id="otrPurchaseInvoice<?php echo $nKey ?>" data-code="<?php echo $aValue['FTXshDocNo'] ?>" data-name="<?php echo $aValue['FTXshDocNo'] ?>">
                                <?php
                                //รวมคอลัมน์
                                // if($aValue['PARTITIONBYCRVC'] == 1 || $aValue['PARTITIONBYCRVC'] == 0){
                                $nRowspan   = '';
                                // }else{
                                //     $nRowspan   = "rowspan=".$aValue['PARTITIONBYCRVC'];
                                // } 
                                ?>
                                <?php if ($tKeepDocNo != $aValue['FTXshDocNo']) { ?>
                                    <td nowrap class="text-left" <?= $nRowspan ?>><?php echo (!empty($aValue['FTBchName'])) ? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left" <?= $nRowspan ?>><?php echo (!empty($aValue['FTXshDocNo'])) ? $aValue['FTXshDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center" <?= $nRowspan ?>><?php echo (!empty($aValue['FDXshDocDate'])) ? $aValue['FDXshDocDate'] : '-' ?></td>
                                <?php } ?>
                                <td nowrap class="text-left"><?= ($aValue['FTXshRefInt'] == '') ? '-' : $aValue['FTXshRefInt'] ?></td>
                                <td nowrap class="text-center"><?= ($aValue['FDXshRefIntDate'] == '') ? '-' : date("d/m/Y", strtotime($aValue['FDXshRefIntDate'])); ?></td>
                                <?php if ($tKeepDocNo != $aValue['FTXshDocNo']) { ?>
                                    <td nowrap class="text-left" <?= $nRowspan ?>><?php echo (!empty($aValue['FTCstName'])) ? $aValue['FTCstName'] : '-' ?></td>
                                    <td nowrap class="text-left" <?= $nRowspan ?>><?php echo (!empty($aValue['FTPshTypeName'])) ? $aValue['FTPshTypeName'] : '-' ?></td>
                                    <td nowrap class="text-left" <?= $nRowspan ?>><?php echo (!empty($aValue['FTPosName'])) ? $aValue['FTPosName'] : '-' ?></td>
                                    <td nowrap class="text-left" <?= $nRowspan ?>>
                                        <label class="xCNTDTextStatus <?php echo $tClassStaDoc; ?>">
                                            <?php echo $tStaDoc; ?>
                                        </label>
                                    </td>

                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <?php if ($aValue['FTStaPdtPick'] == '1' || $aValue['FTPshType']=='1') { ?>
                                            <td nowrap <?= $nRowspan ?>>
                                                <button id="obtCRVEdit" style='min-width:100px;' onclick="JSvCRVCallPageEdit('<?= $aValue['FTBchCode'] ?>','<?= $aValue['FTXshDocNo'] ?>')" class="btn xCNBTNDefult xCNBTNDefult1Btn"><img class="xCNIconTable" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>"></button>
                                            </td>
                                        <?php } else { ?>
                                            <td nowrap <?= $nRowspan ?>>
                                                <button id="obtCRVEdit" style='min-width:100px;' onclick="JSvCRVPageBeforeADD('<?= $aValue['FTBchCode'] ?>','<?= $aValue['FTXshDocNo'] ?>')" class="btn xCNBTNDefult xCNBTNDefult1Btn">รับของ</button>
                                            </td>
                                        <?php } ?>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXshDocNo']; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal PCK Document  ======================================================================== -->
<div id="odvCRVChkLoginModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong><h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('document/saleorder/saleorder', 'ตรวจสอบสิทธิ์การรับของ'); ?></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:25px;">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'ชื่อผู้ใช้'); ?></label>
                        <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetCRVDFillUser" name="oetCRVDFillUser" autocomplete="off">
                        <input class="form-control xCNHide" type="text" id="oetCRVDocFill" name="oetCRVDocFill" autocomplete="off">
                        <input class="form-control xCNHide" type="text" id="oetCRVBchFill" name="oetCRVBchFill" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'กรอกรหัสผ่าน'); ?></label>
                        <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" style='-webkit-text-security: disc;' id="oetCRVFillPass" name="oetCRVFillPass" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="JSxSOFillUserDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <?php $nShowRecord  = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?= $nShowRecord ?> รายการ</p>
    </div>
</div>