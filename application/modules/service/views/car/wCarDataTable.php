<?php
    $nCurrentPage = '1';
?>
<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?= $nCurrentPage; ?>">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <?php if ($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/car/car', 'tCARImage') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/car/car', 'tCARCode') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/car/car', 'tTCARRegNo') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/car/car', 'tCARColor') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/car/car', 'tCARBrand') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/car/car', 'tCARModel') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/car/car', 'tTCAROwner') ?></th>
                        <?php if ($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('service/car/car', 'tCARTBDelete') ?></th>
                        <?php endif; ?>
                        <?php if ($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('service/car/car', 'tCARTBEdit') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvCARList">
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aDataList['raItems'] as $key => $aValue) {  ?>
                            <tr class="text-center xCNTextDetail2 otrCar" id="otrCar<?= $key ?>" data-code="<?= $aValue['rtCarCode'] ?>" data-name="<?= $aValue['rtCarCode'] ?>">
                                <?php if ($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
                                    <td class="text-center">
                                        <label class="fancy-checkbox">
                                            <input id="ocbListItem<?= $key ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onchange="JSxCalendarVisibledDelAllBtn(this, event)" <?php if ($aValue['rtFTUsedCodeReqHD'] > 0 || $aValue['rtFTUsedCodeBookHD'] > 0) {
                                                                                                                                                                                                    echo "disabled";
                                                                                                                                                                                                } ?>>
                                            <span class="<?php if ($aValue['rtFTUsedCodeReqHD'] > 0 || $aValue['rtFTUsedCodeBookHD'] > 0) {
                                                                echo "xCNDisabled";
                                                            } ?>">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td class="text-center" style="padding-right: 10px !important;"><?php echo FCNtHGetImagePageList($aValue['rtImgObj'], '50px'); ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtCarCode'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtCarRegNo'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtCarColorName'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtCarBrandName'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtCarModelName'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtCstName'] ?></td>
                                <?php if ($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable xCNIconDel <?php if ($aValue['rtFTUsedCodeReqHD'] > 0 || $aValue['rtFTUsedCodeBookHD'] > 0) {
                                                                                echo "xCNDisabled";
                                                                            } ?>" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onClick="JSnCarDel('<?php echo $nCurrentPage ?>','<?= $aValue['rtCarCode'] ?>','<?= $aValue['rtCarCode'] ?>','<?= language('common/main/main', 'tModalConfirmDeleteItemsYN') ?>')" title="<?php echo language('service/car/car', 'tCARTBDelete'); ?>">
                                    </td>
                                <?php endif; ?>
                                <?php if ($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>" onClick="JSvCallPageCarEdit('<?php echo $aValue['rtCarCode']; ?>')" title="<?php echo language('service/car/car', 'tCARTBEdit'); ?>">
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='10'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>

<div class="modal fade" id="odvModalDelCar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospConfirmDelete" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnCarDelChoose('<?= $nCurrentPage ?>')">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<script type="text/Javascript">
    $('ducument').ready(function() {
        $('.ocbListItem').click(function(){
        var nCode   = $(this).parent().parent().parent().data('code');  //code
        var tName   = $(this).parent().parent().parent().data('name');  //code
        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }else{ }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"nCode": nCode, "tName": tName });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxPaseCodeDelInModal();
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxPaseCodeDelInModal();
            }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].nCode == nCode){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                JSxPaseCodeDelInModal();
            }
        }
        JSxShowButtonChoose();
        });
    });
</script>