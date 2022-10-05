<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbJR1TblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem"></span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/jobrequest1/jobrequest1','tJR1BchName')?></th>
						<th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/jobrequest1/jobrequest1','tJR1DocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('common/main/main','tTBTimeAndDocDoc')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/jobrequest1/jobrequest1','ชื่อลูกค้า')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/jobrequest1/jobrequest1','ทะเบียนรถ')?></th>
                        <th nowrap class="xCNTextBold" style="width:9%;">อ้างอิงใบสั่งงาน</th>
                        <th nowrap class="xCNTextBold" style="width:9%;">วันที่อ้างอิงใบสั่งงาน</th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/jobrequest1/jobrequest1','ยอดขาย')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/adjuststock/adjuststock','tASTTBStaDoc')?></th>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th nowrap class="xCNTextBold" style="width:5%;"><?=language('common/main/main','tCMNActionDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						    <th nowrap class="xCNTextBold" style="width:5%;"><?=language('common/main/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                $tJR1AgnCode    = $aValue['FTAgnCode'];
                                $tJR1BchCode    = $aValue['FTBchCode'];
                                $tJR1DocCode    = $aValue['FTXshDocNo'];
                                $tJR1DocRefCode = $aValue['FTXshRefDocNo'];
                                if(!empty($aValue['FTXshStaApv']) || $aValue['FTXshStaDoc'] == 3){
                                    $tCheckboxDisabled = "disabled";
                                    $tClassDisabled = 'xCNDocDisabled';
                                    $tTitle = language('document/document/document','tDOCMsgCanNotDel');
                                    $tOnclick = '';
                                }else{
                                    $tCheckboxDisabled = "";
                                    $tClassDisabled = '';
                                    $tTitle = '';
                                    $tOnclick = "onclick=JSoJR1DelDocSingle('".$nCurrentPage."','".$tJR1DocCode."','".$tJR1AgnCode."','".$tJR1BchCode."','".$tJR1DocRefCode."')";
                                }
                                if ($aValue['FTXshStaDoc'] == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                }else{
                                    if ($aValue['FTXshStaDoc'] == 1 && $aValue['FTXshStaApv'] == '') {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }else{
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1');
                                    }
                                }
                                $bIsApvOrCancel = ($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaApv'] == 2) || ($aValue['FTXshStaDoc'] == 3 );
                            ?>
                            <tr class="text-center xCNTextDetail2 xWJR1DocItems" id="otrPurchaseInvoice<?php echo $nKey?>" data-code="<?php echo $tJR1DocCode?>" data-name="<?php echo $tJR1DocCode?>">
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <label class="fancy-checkbox ">
                                            <input id="ocbListItem<?php echo $nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?=$tJR1DocCode?>" 
                                                    data-code="<?php echo $tJR1DocCode?>" 
                                                    data-name="<?php echo $tJR1DocCode?>" 
                                                    data-agn="<?php echo $tJR1AgnCode?>" 
                                                    data-bch="<?php echo $tJR1BchCode?>" 
                                                    data-docref="<?php echo $tJR1DocRefCode?>" 
                                                    <?php echo $tCheckboxDisabled;?>
                                            >
                                            <span class="<?php echo $tClassDisabled?>">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshDocNo']))? $aValue['FTXshDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshDocDate']))? $aValue['FDXshDocDate'] .' - ' . $aValue['FTXshDocTime'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshCstName']))? $aValue['FTXshCstName'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTCarRegNo']))? $aValue['FTCarRegNo'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshRefDocNo']))? $aValue['FTXshRefDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshRefDocDate']))? $aValue['FDXshRefDocDate'] : '-' ?></td>
                                <td nowrap class="text-right"><?= number_format(@$aValue['FCXshGrand'], 2); ?></td>
                                <td nowrap class="text-left">
                                    <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                        <?php echo $tStaDoc;?>
                                    </label>
                                </td>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap >
                                        <img
                                            class="xCNIconTable xCNIconDel <?php echo $tClassDisabled?>"
                                            src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                            <?php echo $tOnclick?>
                                            title="<?php echo $tTitle?>"
                                        >
                                    </td>
                                <?php endif; ?>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                    <td nowrap>
                                    <?php if($bIsApvOrCancel) { ?>
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvJR1CallPageEdit('<?= $aValue['FTAgnCode'] ?>','<?= $aValue['FTBchCode'] ?>','<?= $aValue['FTXshDocNo'] ?>','<?=$aValue['FTCarCode']?>')">
                                        <?php }else{ ?>
                                            <img class="xCNIconTable xCNIconEdit" onClick="JSvJR1CallPageEdit('<?= $aValue['FTAgnCode'] ?>','<?= $aValue['FTBchCode'] ?>','<?= $aValue['FTXshDocNo'] ?>','<?=$aValue['FTCarCode']?>')">
                                        <?php } ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
    <!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php //echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWJR1PageDataTable btn-toolbar pull-right">
            <?php //if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvJR1ClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php //for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?>
                <?php 
                    /*if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }*/
                ?>
                <button onclick="JSvJR1ClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php //} ?>
            <?php //if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvJR1ClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div> -->
</div>
<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvJR1ModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelSingle" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvJR1ModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jJobReqStep1DataTable.php')?>