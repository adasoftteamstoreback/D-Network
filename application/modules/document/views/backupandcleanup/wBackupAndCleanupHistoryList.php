<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = '1';
    }else{
        $nCurrentPage = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACLogCode')?></th>
						<th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACAgnFC')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACTableBch')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACTablePos')?></th>
                        <!-- <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACShfCode')?></th> -->
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACAppCode')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACMnuName')?></th>
                        <!-- <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrcCodeRef')?></th> -->
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACPrcName')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACLogType')?></th>
                        <!-- <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACLogLevel')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACLogRefCode')?></th> -->
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACLogDescription')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACLogDate')?></th>
                        <!-- <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACUsrCode')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACApvCode')?></th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                    <?php foreach ($aDataList['raItems'] as $key => $tVal) { ?>
                            <tr class="xWSattr xWSatLng"  data-tplhd='<?= $tVal['FTPrgKey'] ?>'>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FNLogCode']))? $tVal['FNLogCode'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTAgnCode']))? $tVal['FTAgnName'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTBchCode']))? $tVal['FTBchName'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTPosCode']))? $tVal['FTPosName'] : '-' ?></td>
                                <!-- <td nowrap class="text-left"><?php echo (!empty($tVal['FTShfCode']))? $tVal['FTShfCode'] : '-' ?></td> -->
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTAppCode']))? $tVal['FTAppCode'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTMnuName']))? $tVal['FTMnuName'] : '-' ?></td>
                                <!-- <td nowrap class="text-left"><?php echo (!empty($tVal['FTPrcCodeRef']))? $tVal['FTPrcCodeRef'] : '-' ?></td> -->
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTPrcName']))? $tVal['FTPrcName'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTLogType']))? $tVal['FTLogType'] : '-' ?></td>
                                <!-- <td nowrap class="text-left"><?php echo (!empty($tVal['FTLogLevel']))? $tVal['FTLogLevel'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FNLogRefCode']))? $tVal['FNLogRefCode'] : '-' ?></td> -->
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTLogDescription']))? $tVal['FTLogDescription'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($tVal['FDLogDate']))? $tVal['FDLogDate'] : '-' ?></td>
                                <!-- <td nowrap class="text-left"><?php echo (!empty($tVal['FTUsrCode']))? $tVal['FTUsrCode'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tVal['FTUsrApvCode']))? $tVal['FTUsrApvCode'] : '-' ?></td> -->
                            </tr>
                        <?php } ?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

</script>
