<?php
if ($aDataList['rtCode'] == '1') {
    $nCurrentPage   = '1';
} else {
    $nCurrentPage = '1';
}
// print_r($aSearchAll);
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <?php if ($aSearchAll['tSearchType'] != 1) : ?>
                <table id="" class="table table-striped">
                    <thead>
                        <tr class="xCNCenter">
                            <th class="xCNHideWhenCancelOrApprove" style="width:10%">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                            <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACDocName') ?></th>
                            <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgType') ?></th>
                            <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFTPrgGroup') ?></th>
                            <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgStaPrg') ?></th>
                            <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgLast') ?></th>
                            <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgKeep') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if ($aDataList['rtCode'] == 1) : ?>
                            <?php foreach ($aDataList['raItems'] as $key => $tVal) { ?>
                                <tr class="xWSattr xWSatLng<?= $tVal['FNPrgDocType'] ?>" data-doctype='<?= $tVal['FNPrgDocType'] ?>' data-tplhd='<?= $tVal['FTPrgKey'] ?>'>
                                    <td class="xCNHideWhenCancelOrApprove" style="text-align:center">
                                        <label class="fancy-checkbox">
                                            <input id="ocbListItem<?= $key ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                            <span class="ospListItem">&nbsp;</span>
                                        </label>
                                    </td>
                                    <td nowrap data-doctype='<?= $tVal['FNPrgDocType'] ?>'>
                                        <?= $tVal['FTPrgName']; ?>
                                    </td>
                                    <td nowrap class="text-left"><?php echo (!empty($tVal['FNPrgType'])) ? $tVal['FNPrgTypeName'] : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tVal['FTPrgGroup'])) ? $tVal['FTPrgGroup'] : '-' ?></td>
                                    <td nowrap class="text-center"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACStaPrg' . $tVal['ChkStaPurge']) ?></td>
                                    <td nowrap class="text-center"><?php echo date_format(date_create((!empty($tVal['FDPrgLast'])) ? $tVal['FDPrgLast'] : '-'), 'd/m/Y') ?></td>
                                    <td nowrap class="text-right">
                                        <?php echo (!empty($tVal['FNPrgKeepSpl'])) ? $tVal['FNPrgKeepSpl'] : $tVal['FNPrgKeep'] ?>
                                        <?php if ($tVal['FNPrgType'] == 3) { ?>
                                            <span><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACSpanFile') ?></span>
                                    </td>
                                <?php } else { ?>
                                    <span><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACSpanDay') ?></span></td>
                                <?php } ?>
                                </tr>
                            <?php } ?>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class='text-center'>
                    สำรองข้อมูล <?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionServer' . $aSearchAll['tSearchGroup']).' กลุ่ม' ?> 
                    <?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionGroup' . $aSearchAll['tSearchPrgType']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Checkall ตอนเริ่มเสมอ
    $('#ocmCENCheckDeleteAll').trigger('click');
</script>