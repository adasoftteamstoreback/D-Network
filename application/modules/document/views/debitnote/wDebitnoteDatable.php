<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage   = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbDBNTblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                        <th nowrap class="xCNTextBold text-center" style="width:5%;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmDBNCheckDeleteAll" id="ocmDBNCheckDeleteAll" >
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
                        <?php endif; ?>
                        <th nowrap class="xCNTextBold" style="width:5%;"><?php  echo language('document/debitnote/debitnote','tDBNBchName')?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('document/debitnote/debitnote','tDBNDocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/debitnote/debitnote','tDBNDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/debitnote/debitnote','tDBNDocRefIntNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/debitnote/debitnote','tDBNDocRefIntDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/debitnote/debitnote','tDBNCstName')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/debitnote/debitnote','tDBNStaApv')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/debitnote/debitnote','tDBNCreateBy')?></th>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                        <!-- <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main','tCMNActionDelete')?></th> -->
                        <?php endif; ?>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                        <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('document/debitnote/debitnote','tDBNInspect')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                $tDBNAgnCode    = @$aValue['FTAgnCode'];
                                $tDBNBchCode    = @$aValue['FTBchCode'];
                                $tDBNDocCode    = @$aValue['FTXshDocNo'];
                                $tDBNDocRefCode = @$aValue['FTXshRefDocNo'];
                                // CHECK STATUS DOCUMENT APPOVE
                                if(!empty($aValue['FTXshStaApv']) || $aValue['FTXshStaDoc'] == 3){
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                    $tOnclick           = '';
                                }else{
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = '';
                                    $tTitle             = '';
                                    $tOnclick           = "onclick=JSoDBNDelDocSingle('".$nCurrentPage."','".$tDBNDocCode."','".$tDBNAgnCode."','".$tDBNBchCode."','".$tDBNDocRefCode."')";
                                }
                                // CHECK STATUS DOCUMENT DOCUMENT
                                if ($aValue['FTXshStaDoc'] == 3) {
                                    $tClassStaDoc   = 'text-danger';
                                    $tStaDoc        = language('common/main/main', 'tStaDoc3');
                                }else{
                                    if ($aValue['FTXshStaDoc'] == 1 && $aValue['FTXshStaApv'] == '') {
                                        $tClassStaDoc   = 'text-warning';
                                        $tStaDoc        = language('common/main/main', 'tStaDoc');
                                    }else{
                                        $tClassStaDoc   = 'text-success';
                                        $tStaDoc        = language('common/main/main', 'tStaDoc1');
                                    }
                                }
                                $bIsApvOrCancel = ($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaApv'] == 2) || ($aValue['FTXshStaDoc'] == 3 );
                            ?>
                            <tr class="text-center xCNTextDetail2 xWDBNDocItems" id="otrDebitNote<?php echo $nKey?>" data-code="<?php echo $tDBNDocCode?>" data-name="<?php echo $tDBNDocCode?>">
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <label class="fancy-checkbox">
                                            <input id="ocbListItem<?php echo $nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?=$tDBNDocCode?>"
                                                    data-code="<?php echo @$tDBNDocCode?>"
                                                    data-name="<?php echo @$tDBNDocCode?>"
                                                    data-agn="<?php echo @$tDBNAgnCode?>"
                                                    data-bch="<?php echo @$tDBNBchCode?>"
                                                    data-docref="<?php echo @$tDBNDocRefCode?>"
                                                    disabled
                                            >
                                            <span class="xCNDocDisabled">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshDocNo']))? $aValue['FTXshDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshDocDate']))? $aValue['FDXshDocDate'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshRefDocNo']))? $aValue['FTXshRefDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshRefDocDate']))? $aValue['FDXshRefDocDate'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTCstName']))? $aValue['FTCstName'] : '-' ?></td>
                                <td nowrap class="text-left">
                                    <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                        <?php echo $tStaDoc;?>
                                    </label>
                                </td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTCreateBy']))? $aValue['FTCreateBy'] : '-' ?></td>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                <!-- <td nowrap >
                                    <img
                                        class="xCNIconTable xCNIconDel xCNDocDisabled"
                                        src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                        title="<?php echo $tTitle?>"
                                    >
                                </td> -->
                                <?php endif; ?>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                    <td nowrap>
                                        <img 
                                            class="xCNIconTable"
                                            style="width: 17px;"
                                            src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>"
                                            onClick="JSvDBNCallPageEdit('<?= @$aValue['FTAgnCode'] ?>','<?= $aValue['FTBchCode'] ?>','<?= $aValue['FTXshDocNo'] ?>','<?= $aValue['FTCstCode'] ?>')"
                                        >
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
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWDBNPageDataTable btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvDBNClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

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
                <button onclick="JSvDBNClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvDBNClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>
<!-- ===================================================== Modal Delete Document Single ===================================================== -->
    <div id="odvDBNModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
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
<!-- ======================================================================================================================================== -->
<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
    <div id="odvDBNModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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
<!-- ======================================================================================================================================== -->
<?php include('script/jDebitNoteDataTable.php')?>