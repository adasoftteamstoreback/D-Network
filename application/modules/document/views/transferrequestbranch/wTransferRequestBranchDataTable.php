<?php
    $nCurrentPage   = '1';
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbTRBTblDataDocHDList" class="table table-striped">
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
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/transferrequestbranch/transferrequestbranch','สาขาที่สร้าง')?></th>
						<th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/transferrequestbranch/transferrequestbranch','tTRBDocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?php  echo language('document/transferrequestbranch/transferrequestbranch','tTRBDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/transferrequestbranch/transferrequestbranch','ขอโอนจากต้นทาง')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/transferrequestbranch/transferrequestbranch','สาขาปลายทาง')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/transferrequestbranch/transferrequestbranch','tTRBDocRefIntNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?php  echo language('document/transferrequestbranch/transferrequestbranch','tTRBDocRefIntDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?php  echo language('document/transferrequestbranch/transferrequestbranch','tTRBStaApv')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/transferrequestbranch/transferrequestbranch','tTRBCreateBy')?></th>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						    <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                $tTRBDocNo      = $aValue['FTXthDocNo'];
                                $tTRBAgnCode    = $aValue['FTAgnCode'];
                                $tTRBBchCode    = $aValue['FTBchCode'];
                                $tTRBRefInCode  = $aValue['FTXthRefInt'];
                                // Check Status Appove
                                if(!empty($aValue['FTXthStaApv']) || $aValue['FTXthStaDoc'] == 3){
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document','tTRBCMsgCanNotDel');
                                    $tOnclick           = '';
                                }else{
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = '';
                                    $tTitle             = '';
                                    $tOnclick           = "onclick=JSoTRBDelDocSingle('".$nCurrentPage."','".$tTRBDocNo."','".$tTRBAgnCode."','".$tTRBBchCode."','".$tTRBRefInCode."')";
                                }
                                // Check Status Document
                                if ($aValue['FTXthStaDoc'] == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                }else{
                                    if ($aValue['FTXthStaDoc'] == 1 && $aValue['FTXthStaApv'] == '') {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }else{
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1');
                                    }
                                }
                                $bIsApvOrCancel = ($aValue['FTXthStaApv'] == 1 || $aValue['FTXthStaApv'] == 2) || ($aValue['FTXthStaDoc'] == 3 );
                            ?>
                            <tr 
                                class="text-center xCNTextDetail2 xWPIDocItems" 
                                id="otrPurchaseInvoice<?php echo $nKey?>" 
                                data-bchcode="<?php echo $aValue['FTBchCode']?>" 
                                data-agncode="<?php echo $aValue['FTAgnCode']?>" 
                                data-code="<?php echo $aValue['FTXthDocNo']?>" 
                                data-name="<?php echo $aValue['FTXthDocNo']?>"
                            >
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <label class="fancy-checkbox ">
                                            <input id="ocbListItem<?php echo $nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?=$tTRBDocNo?>" data-agncode="<?=$tTRBAgnCode?>" data-bchcode="<?=$tTRBBchCode?>" data-refcode="<?=$tTRBRefInCode?>" <?php echo $tCheckboxDisabled;?>>
                                            <span class="<?php echo $tClassDisabled?>">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td nowrap class="text-left"><?php      echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php      echo (!empty($aValue['FTXthDocNo']))? $aValue['FTXthDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php    echo (!empty($aValue['FDXthDocDate']))? $aValue['FDXthDocDate'] : '-' ?></td>
                                <td nowrap class="text-left"><?php      echo (!empty($aValue['BchNameFrm']))? $aValue['BchNameFrm']   : '-' ?></td>
                                <td nowrap class="text-left"><?php      echo (!empty($aValue['BchNameTo']))? $aValue['BchNameTo']   : '-' ?></td>
                                <td nowrap class="text-left"><?php      echo (!empty($aValue['FTXthRefInt']))? $aValue['FTXthRefInt'] : '-' ?></td>
                                <td nowrap class="text-center"><?php    echo (!empty($aValue['FDXthRefIntDate']))? $aValue['FDXthRefIntDate'] : '-' ?></td>
                                <td nowrap class="text-left">
                                    <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                        <?php echo $tStaDoc;?>
                                    </label>
                                </td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?></td>
                                <td nowrap >
                                    <img
                                        class="xCNIconTable xCNIconDel <?php echo $tClassDisabled?>"
                                        src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                        <?php echo $tOnclick?>
                                        title="<?php echo $tTitle?>"
                                    >
                                </td>
                                <td nowrap>
                                    <?php if($bIsApvOrCancel) { ?>
                                        <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvTRBCallPageEdit('<?php echo $aValue['FTBchCode']?>','<?php echo $aValue['FTAgnCode']?>','<?= $aValue['FTXthDocNo'] ?>')">
                                    <?php }else{ ?>
                                        <img class="xCNIconTable xCNIconEdit" onClick="JSvTRBCallPageEdit('<?php echo $aValue['FTBchCode']?>','<?php echo $aValue['FTAgnCode']?>','<?php echo $aValue['FTXthDocNo']?>')">
                                    <?php } ?>
                                </td>
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
</div>
<!-- ===================================================== Modal Delete Document Single ===================================================== -->
    <div id="odvTRBModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
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
    <div id="odvTRBModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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
<?php include('script/jTransferRequestBranchDataTable.php')?>