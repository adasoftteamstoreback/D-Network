<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
        
        // echo "<pre>";
        // print_r ($aDataList);
        // echo "</pre>";
        // exit;
    }else{
        $nCurrentPage = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table table-responsive">
            <table id="otbBACTblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold" rowspan="2" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACAgnFC')?></th>
                        <th nowrap class="xCNTextBold" rowspan="2" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACDocName')?></th>
						<th nowrap class="xCNTextBold" rowspan="2" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgType')?></th>
                        <th nowrap class="xCNTextBold" rowspan="2" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACFTPrgGroup')?></th>
                        <th nowrap class="xCNTextBold" colspan="2" style="width:15%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgStaPrg')?></th>
                        <th nowrap class="xCNTextBold" colspan="2" style="width:15%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgStaUse')?></th>
                        <th nowrap class="xCNTextBold" rowspan="2" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgLast')?></th>
                        <th nowrap class="xCNTextBold" colspan="2" style="width:15%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgKeep')?></th>
                        <th nowrap class="xCNTextBold" rowspan="2" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgEdit')?></th>
                    </tr>
                    <tr>
                        <th style="text-align:center;  "><?= language('settingconfig/backupandcleardata/backupandcleardata','tBACPurgeStd')?></th>
                        <th style="text-align:center;  "><?= language('settingconfig/backupandcleardata/backupandcleardata','tBACPurgeConfig')?></th>
                        <th style="text-align:center;  "><?= language('settingconfig/backupandcleardata/backupandcleardata','tBACPurgeStd')?></th>
                        <th style="text-align:center;  "><?= language('settingconfig/backupandcleardata/backupandcleardata','tBACPurgeConfig')?></th>
                        <th style="text-align:center;  "><?= language('settingconfig/backupandcleardata/backupandcleardata','tBACPurgeStd')?></th>
                        <th style="text-align:center;  "><?= language('settingconfig/backupandcleardata/backupandcleardata','tBACPurgeConfig')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <tr class="text-center xCNTextDetail2" id="otrPurchaseInvoice<?php echo $nKey?>" data-prgkey="<?php echo @$aValue['FTPrgKey']?>" data-doctype="<?php echo @$aValue['FNPrgDocType']?>">
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTAgnCode']))? $aValue['FTAgnName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTPrgName']))? $aValue['FTPrgName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FNPrgType']))? $aValue['FNPrgTypeName'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTPrgGroup']))? $aValue['FTPrgGroup'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACStaPrg'.$aValue['FTPrgStaPrg1']) ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTPrgStaPrg2']))?  language('settingconfig/backupandcleardata/backupandcleardata','tBACStaPrg'.$aValue['FTPrgStaPrg2']): '-' ?></td>
                                <td nowrap class="text-left"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgUse'.$aValue['FTPrgStaUse1']) ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTPrgStaUse2']))?  language('settingconfig/backupandcleardata/backupandcleardata','tBACPrgUse'.$aValue['FTPrgStaUse2']): '-' ?></td>
                                <td nowrap class="text-center"><?php echo date_format(date_create((!empty($aValue['FDPrgLast']))? $aValue['FDPrgLast'] : '-'),'d/m/Y') ?></td>
                                <td nowrap class="text-right">
                                    <?php echo (!empty($aValue['FNPrgKeep']))? $aValue['FNPrgKeep'] : '-' ?>
                                <?php if($aValue['FNPrgType'] == 3){?>
                                    <span><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACSpanFile')?></span></td>
                                <?php }else{ ?>
                                    <span><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACSpanDay')?></span></td>
                                <?php } ?>
                                <td nowrap class="text-right">
                                    <?php echo (!empty($aValue['FNPrgKeepSpl']))? $aValue['FNPrgKeepSpl'] : '-' ?>
                                <?php if($aValue['FNPrgType'] == 3){?>
                                    <span><?php echo (!empty($aValue['FNPrgKeepSpl']))? language('settingconfig/backupandcleardata/backupandcleardata','tBACSpanFile'): ' ' ?></span></td>
                                <?php }else{ ?>
                                    <span><?php echo (!empty($aValue['FNPrgKeepSpl']))? language('settingconfig/backupandcleardata/backupandcleardata','tBACSpanDay'): ' ' ?></span></td>
                                <?php } ?>
                                <td nowrap><img class="xCNIconTable" src="<?= base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageBackupAndClearEdit('<?=$aValue['FTPrgKey']?>','<?=$aValue['FNPrgDocType']?>')"></td>
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
        <!-- <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p> -->
    </div>
</div>
<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvBACModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvBACModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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

<?php include('script/jBackupAndClearDataDataTable.php')?>