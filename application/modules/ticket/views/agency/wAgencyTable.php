<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
    $aRelation = array();
    if (count($aDataRelation)>0) {
      foreach ($aDataRelation as $nkey => $tValue) {
        $aRelation[] = $aDataRelation[$nkey]['FTAgnCode'];
      }
    }
?>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
					<tr class="xCNCenter">
					<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                        <th nowrap class="xCNTextBold text-center" style="width:5%;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
						<?php endif; ?>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?= language('ticket/agency/agency','tAggCode')?></th>
						<th nowrap class="xCNTextBold" style="width:10%;"><?= language('ticket/agency/agency','tPicture')?></th>
						<th nowrap class="xCNTextBold"><?= language('ticket/agency/agency','tAggName')?></th>
                        <th nowrap class="xCNTextBold"><?= language('ticket/agency/agency','tEmail')?></th>
                        <th nowrap class="xCNTextBold"><?= language('company/company/company','tCMPAgnFranchise')?></th>
						<th nowrap class="xCNTextBold"><?= language('pos/poschannel/poschannel','tCHNLabelTitle')?></th>
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
						<th nowrap class="xCNTextBold" style="width:10%;"><?= language('common/main/main','tCMNActionDelete')?></th>
						<?php endif; ?>
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaEdit'] == 1) : ?>
						<th nowrap class="xCNTextBold" style="width:10%;"><?= language('common/main/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
				<?php if($aDataList['rtCode'] == 1 ):?>
                    <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                        <tr class="text-center xCNTextDetail2" id="otrCoupon<?=$key?>" data-code="<?=$aValue['FTAgnCode']?>" data-name="<?=$aValue['FTAgnName']?>">
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
								<td class="text-center">
									<label class="fancy-checkbox">
                    <?php if (in_array($aValue['FTAgnCode'], $aRelation)){ ?>
                      <input id="ocbListItem<?=$key?>" type="checkbox"  class="ocbListItem" name="ocbListItem[]" disabled>
                      <span class="xCNDocDisabled">&nbsp;</span>
                    <?php }else { ?>
                      <input id="ocbListItem<?=$key?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                      <span >&nbsp;</span>
                    <?php } ?>


									</label>
								</td>
							<?php endif; ?>
                            <td class="text-left"><?=$aValue['FTAgnCode']?></td>
                            <td class="text-center"><?php echo FCNtHGetImagePageList($aValue['FTImgObj'],'38px'); ?></td>
                            <td class="text-left"><?=$aValue['FTAgnName']?></td>
                            <td class="text-left"><?=$aValue['FTAgnEmail']?></td>
                            <td class="text-left"><?php if($aValue['FTAgnType']== "1" ){ echo language('company/branch/branch', 'tBCHBchTypeSEL2'); }else{ echo language('company/branch/branch', 'tBCHBchType4');  } ?></td>
							<td class="text-left"><?=$aValue['FTChnName']?></td>
                            <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                <td>
                                    <?php if (in_array($aValue['FTAgnCode'], $aRelation)){
                                        $tBtnDelClass = "xCNIconTable xCNIconDel xCNDocDisabled"; ?>
                                        <img class="<?php echo $tBtnDelClass; ?>" src="<?= base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                                    <?php }else {
                                        $tBtnDelClass = "xCNIconTable xCNIconDel"; ?>
                                        <img class="<?php echo $tBtnDelClass; ?>" src="<?= base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSnAgencyDel('<?php echo $nCurrentPage?>','<?=$aValue['FTAgnName']?>','<?php echo $aValue['FTAgnCode']?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')">
                                    <?php } ?>
                                </td>
							<?php endif; ?>
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaEdit'] == 1) : ?>
								<td><img class="xCNIconTable" src="<?= base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageAgencyEdit('<?php echo $aValue['FTAgnCode']?>','<?php echo $aValue['FTAgnStaApv'] ?>','<?php echo $aValue['FTAgnStaActive']?>')"></td>
							<?php endif; ?>
                        </tr>
                    <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='9'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		<p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="xWCDCPaging btn-toolbar pull-right">
			<?php if($nPage == 1){ $tDisabled = 'disabled'; }else{ $tDisabled = '-';} ?>
            <button onclick="JSvCPNClickPage('previous')" class="btn btn-white btn-sm" <?=$tDisabled?>><i class="fa fa-chevron-left f-s-14 t-plus-1"></i></button>

			<?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?>
				<?php
                    if($nPage == $i){
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    }else{
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
            		<button onclick="JSvCPNClickPage('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive?>" <?=$tDisPageNumber ?>><?=$i?></button>
			<?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){ $tDisabled = 'disabled'; }else{ $tDisabled = '-'; } ?>
			<button onclick="JSvCPNClickPage('next')" class="btn btn-white btn-sm" <?=$tDisabled?>><i class="fa fa-chevron-right f-s-14 t-plus-1"></i></button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelAgency">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnAgencyDelChoose('<?=$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>
<?php include "script/jAgennyAdd.php"; ?>
