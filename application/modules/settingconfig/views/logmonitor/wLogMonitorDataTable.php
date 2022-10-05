<?php
// if($aDataList['rtCode'] == '1'){
//     $nCurrentPage   = $aDataList['rnCurrentPage'];

//     // echo "<pre>";
//     // print_r ($aDataList);
//     // echo "</pre>";
//     // exit;
// }else{
//     $nCurrentPage = '1';
// }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table table-responsive">
            <table id="otbLOGTblDataDocHDList" class="table">
                <thead>

                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold text-center" style="width:5%;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
                        <!-- <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'รหัส') ?></th> -->
                        <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'ตัวแทนขาย') ?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'สาขา') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'จุดขาย') ?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'รหัสรอบ') ?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'แอปพลิเคชั่น') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'รหัสอ้างอิงเมนู') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'ชื่อเมนู') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'รหัสอ้างอิงหน้าจอ/ฟังก์ชั่น') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'ชื่อหน้าจอ/ฟังก์ชั่น/Event') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'ประเภท') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'ระดับ') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'รหัสอ้างอิง') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'รายละเอียด') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'วันที่') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'ผู้ใช้') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('settingconfig/logmonitor/logmonitor', 'ผู้อนุมัติ') ?></th>

                    </tr>
                </thead>
                <tbody>
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aDataList['raItems'] as $key => $aValue) { ?>
                            <tr class="text-center xCNTextDetail2" id="otrLogMonitor<?= $key ?>" data-code="<?= $aValue['FNLogCode'] ?>" data-name="<?= $aValue['FNLogCode'] ?>">
                                <td class="text-center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?= $key ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]"><!-- onchange="JSxCSTVisibledDelAllBtn(this, event)" -->
                                        <span>&nbsp;</span>
                                    </label>
                                </td>
                                <!-- <td nowrap class="text-left"><?php echo $aValue['FNLogCode']; ?></td> -->
                                <td nowrap class="text-left"><?php echo $aValue['FTAgnCode']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTBchCode']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTPosCode']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTShfCode']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTAppCode']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTMnuCodeRef']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTMnuName']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTPrcCodeRef']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTPrcName']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTLogType']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTLogLevel']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FNLogRefCode']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTLogDescription']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FDLogDate']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTUsrCode']; ?></td>
                                <td nowrap class="text-left"><?php echo $aValue['FTUsrApvCode']; ?></td>
                            </tr>
                        <?php } ?>
                    <?php else : ?>
                        <tr>
                            <td nowrap class='text-center xCNTextDetail2' colspan='99'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvLOGModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelSingle" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvLOGModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<?php //include('script/jLogMonitorDataTable.php') 
?>
<script type="text/javascript">
    $('.ocbListItem').click(function() {
        var nCode = $(this).parent().parent().parent().data('code'); //code
        var tName = $(this).parent().parent().parent().data('name'); //code
        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if (LocalItemData) {
            obj = JSON.parse(LocalItemData);
        } else {}
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert == '' || aArrayConvert == null) {
            obj.push({
                "nCode": nCode,
                "tName": tName
            });
            localStorage.setItem("LocalItemData", JSON.stringify(obj));
            JSxTextinModal();
        } else {
            var aReturnRepeat = findObjectByKey(aArrayConvert[0], 'nCode', nCode);
            if (aReturnRepeat == 'None') { //ยังไม่ถูกเลือก
                obj.push({
                    "nCode": nCode,
                    "tName": tName
                });
                localStorage.setItem("LocalItemData", JSON.stringify(obj));
                JSxTextinModal();
            } else if (aReturnRepeat == 'Dupilcate') { //เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i].nCode == nCode) {
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i] != undefined) {
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("LocalItemData", JSON.stringify(aNewarraydata));
                JSxTextinModal();
            }
        }
        JSxShowButtonChoose();
    })
</script>