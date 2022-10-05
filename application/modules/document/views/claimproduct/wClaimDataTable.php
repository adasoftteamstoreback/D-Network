<?php
    if($aDataList['rtCode'] == '1'){ $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{ $nCurrentPage = '1'; }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped">
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
                        <th class="xCNTextBold"><?=language('document/invoice/invoice','สาขา')?></th>
						<th class="xCNTextBold"><?=language('document/invoice/invoice','เลขที่เอกสาร')?></th>
                        <th class="xCNTextBold"><?=language('document/invoice/invoice','วันที่เอกสาร')?></th>
                        <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ลูกค้า')?></th>
                        <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','สถานะเอกสาร')?></th>
                        <th class="xCNTextBold"><?=language('document/invoice/invoice','หมายเหตุ')?></th>
                        <th class="xCNTextBold"><?=language('document/invoice/invoice','ผู้สร้าง')?></th>

                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th class="xCNTextBold" style="width:5%;"><?= language('common/main/main','tCMNActionDelete')?></th>
                        <?php endif; ?>
                        
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						    <th class="xCNTextBold" style="width:5%;"><?= language('common/main/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                    <?php if($aDataList['rtCode'] == 1 ):?>
                    <?php 
                        if(FCNnHSizeOf($aDataList['raItems'])!=0){
                            foreach($aDataList['raItems'] AS $nKey => $aValue):?>

                            <?php
                                $tDocNo       = $aValue['FTPchDocNo'];
                                $tBchCode     = $aValue['FTBchCode'];
                                
                                if($aValue['FTPchStaPrcDoc'] >= 2 || $aValue['FTPchStaDoc'] == 2){
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                    $tOnclick           = '';
                                }else{
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = '';
                                    $tTitle             = '';
                                    $tOnclick           = "onclick=JSoCLMDelDocSingle('".$nCurrentPage."','".$tDocNo."','".$tBchCode."')";
                                }

                                //1 : รออนุมัติ , 
                                //2 : รอส่งสินค้าไปยังผู้จำหน่าย , 
                                //3 : รอรับสินค้าจากผู้จำหน่าย , 
                                //4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 
                                //5 : รอส่งสินค้าให้ลูกค้า , 
                                //6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 
                                //7 : ปิดงานแล้ว
                                if($aValue['FTPchStaDoc'] == 2){
                                    $tTextStaPrcDoc = 'เอกสารยกเลิก';
                                    $tClassStaDoc   = 'xWCSSRedColor';
                                }else{
                                    if ($aValue['FTPchStaPrcDoc'] == 1 ) {
                                        $tTextStaPrcDoc = 'รออนุมัติการเคลม';
                                        $tClassStaDoc   = 'xWCSSCarrotColor';
                                    }else if($aValue['FTPchStaPrcDoc'] == 2 ) {
                                        $tTextStaPrcDoc = 'รอส่งสินค้าไปยังผู้จำหน่าย';
                                        $tClassStaDoc   = 'xWCSSCarrotColor';
                                    }else if($aValue['FTPchStaPrcDoc'] == 3 ) {
                                        $tTextStaPrcDoc = 'รอรับสินค้าจากผู้จำหน่าย';
                                        $tClassStaDoc   = 'xWCSSCarrotColor';
                                    }else if($aValue['FTPchStaPrcDoc'] == 4 ) {
                                        $tTextStaPrcDoc = 'รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว';
                                        $tClassStaDoc   = 'xWCSSCarrotColor';
                                    }else if($aValue['FTPchStaPrcDoc'] == 5 ) {
                                        $tTextStaPrcDoc = 'รอส่งสินค้าให้ลูกค้า';
                                        $tClassStaDoc   = 'xWCSSCarrotColor';
                                    }else if($aValue['FTPchStaPrcDoc'] == 6 ) {
                                        $tTextStaPrcDoc = 'ส่งสินค้าบางส่วนให้ลูกค้าแล้ว';
                                        $tClassStaDoc   = 'xWCSSCarrotColor';
                                    }else{
                                        $tTextStaPrcDoc = 'เอกสารสมบูรณ์';
                                        $tClassStaDoc   = 'xWCSSGreenColor';
                                    }
                                }
                                
                            ?>

                            <tr class="text-center xCNTextDetail2" id="otrCLM<?=$nKey?>" 
                                data-code="<?=$tDocNo?>" 
                                data-name="<?=$tDocNo?>">
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <label class="fancy-checkbox ">
                                            <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?=$tDocNo?>" data-bchcode="<?=$tBchCode?>" <?=$tCheckboxDisabled;?>>
                                            <span class="<?=$tClassDisabled?>">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td nowrap class="text-left"><?= (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?= (!empty($aValue['FTPchDocNo']))? $aValue['FTPchDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?= (!empty($aValue['FDXphDocDate']))? $aValue['FDXphDocDate'] : '-' ?></td>
                                <td nowrap class="text-left"><?= (!empty($aValue['FTCstName']))? $aValue['FTCstName'] : '-' ?></td>
                                <td nowrap class="text-left">
                                    <label class="<?=$tClassStaDoc;?>">
                                        <?=$tTextStaPrcDoc;?>
                                    </label>
                                </td>
                                <td nowrap class="text-left"><?= (!empty($aValue['FTPchRmk']))? $aValue['FTPchRmk'] : '-' ?></td>
                                <td nowrap class="text-left"><?= (!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?></td>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap >
                                        <img
                                            class="xCNIconTable xCNIconDel <?=$tClassDisabled?>"
                                            src="<?=base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                            <?=$tOnclick?>
                                            title="<?=$tTitle?>"
                                        >
                                    </td>
                                <?php endif; ?>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                    <td nowrap>
                                    <?php if($aValue['FTPchStaApv'] == 1) { ?>
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvCLMCallPageEdit('<?=$tDocNo?>')">
                                        <?php }else{ ?>
                                            <img class="xCNIconTable xCNIconEdit" onClick="JSvCLMCallPageEdit('<?=$tDocNo?>')">
                                        <?php } ?>
                                    </td>
                                <?php endif; ?>
                            </tr>

                            <?php endforeach; ?>
                    <?php } ?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageCLMPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvCLMClickPageList('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
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
                <button onclick="JSvCLMClickPageList('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvCLMClickPageList('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvCLMModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmCLMConfirmPdtDTTemp" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvCLMModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //ลบหลายตัว
    $('.ocbListItem').click(function(){
        var nCode = $(this).parent().parent().parent().data('code');  //code
        var tName = $(this).parent().parent().parent().data('name');  //name
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
            JSxTextinModal();
        }else{
            var aReturnRepeat = JStCLMFindObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxTextinModal();
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
                JSxTextinModal();
            }
        }
        JSxShowButtonChoose();
    })

    $('#odvCLMModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSoCLMDelDocMultiple();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
</script>