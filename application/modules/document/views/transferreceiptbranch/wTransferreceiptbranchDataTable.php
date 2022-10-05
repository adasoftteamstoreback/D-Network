<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
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
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBBchCreate')?></th>
						<th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBDocNo')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBDocDate')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBStaDoc')?></th>
                        <!-- <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBStaApv')?></th> -->
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBStaPrc')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBCreateBy')?></th>

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
                                    if($aValue['FTXthStaApv'] == 1 || $aValue['FTXthStaApv'] == 2 || $aValue['FTXthStaDoc'] == 3){
                                        $tCheckboxDisabled  = "disabled";
                                        $tClassDisabled     = "xCNDocDisabled";
                                        $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                        $tOnclick           = '';
                                    }else{
                                        $tCheckboxDisabled  = "";
                                        $tClassDisabled     = '';
                                        $tTitle             = '';
                                        $tOnclick           = "onclick=JSoTBIDelDocSingle('".$nCurrentPage."','".$aValue['FTXthDocNo']."')";
                                    }
                                    
                                    // เช็ค Text Color FTXthStaDoc
                                    if ($aValue['FTXthStaDoc'] == 3) {
                                        $tClassStaDoc = 'text-danger';
                                        $tStaDoc = language('common/main/main', 'tStaDoc3');
                                    } else if ($aValue['FTXthStaApv'] == 1) {
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1');
                                    } else {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }

                                    // เช็ค Text Color FTXthStaPrcStk
                                    if ($aValue['FTXthStaDoc'] == 3) {
                                        $tClassPrcStk   = 'text-danger';
                                        $tStaPrcDoc     = language('common/main/main', 'tStaDoc3');
                                    }else{
                                        if ($aValue['FTXthStaPrcStk'] == 1) {
                                            $tClassPrcStk = 'text-success';
                                            $tStaPrcDoc = language('common/main/main', 'tStaPrcDoc1');
                                        } else if ($aValue['FTXthStaPrcStk'] == 2) {
                                            $tClassPrcStk = 'text-warning';
                                            $tStaPrcDoc = language('common/main/main', 'tStaPrcDoc2');
                                        } else if ($aValue['FTXthStaPrcStk'] == 0 || $aValue['FTXthStaPrcStk'] == '') {
                                            $tClassPrcStk = 'text-warning';
                                            $tStaPrcDoc = language('common/main/main', 'tStaPrcDoc3');
                                        }
                                    }
                                ?>
                                <tr id="otrTIB<?php echo $nKey?>" class="text-center xCNTextDetail2 otrTIB" data-code="<?php echo $aValue['FTXthDocNo']?>" data-name="<?php echo $aValue['FTXthDocNo']?>">
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td class="text-center">
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?php echo $nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" <?php echo $tCheckboxDisabled;?>>
                                                <span class="<?php echo $tClassDisabled?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <td class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName'] : '-' ?></td>
                                    <td class="text-left"><?php echo (!empty($aValue['FTXthDocNo']))? $aValue['FTXthDocNo'] : '-' ?></td>
                                    <td class="text-center"><?php echo (!empty($aValue['FDXthDocDate']))? $aValue['FDXthDocDate'] : '-' ?></td>
                                    <td class="text-left">
                                        <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>"><?php echo $tStaDoc ?></label>
                                    </td>
                                    <td class="text-left">
                                        <label class="xCNTDTextStatus <?php echo $tClassPrcStk;?>"><?php echo $tStaPrcDoc ?></label>
                                    </td>
                                    <td class="text-left">
                                        <?php echo (!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?>
                                    </td>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td>
                                            <img
                                                class="xCNIconTable xCNIconDel <?php echo $tClassDisabled?>"
                                                src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                                <?php echo $tOnclick?>
                                                title="<?php echo $tTitle?>"
                                            >
                                        </td>
                                    <?php endif; ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td>
                                        <?php if($aValue['FTXthStaApv'] == 1 || $aValue['FTXthStaDoc'] == 3){ ?>
                                            <img class="xCNIconTable" style="width: 17px;" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/view2.png'?>" onClick="JSvTBICallPageEdit('<?=$aValue['FTXthDocNo']?>')">
                                        <?php }else{ ?>
                                            <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvTBICallPageEdit('<?=$aValue['FTXthDocNo']?>')">
                                        <?php } ?>
                                            <!-- <img class="xCNIconTable xCNIconEdit" onClick="JSvTBICallPageEdit('<?php echo $aValue['FTXthDocNo']?>')"> -->
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach;
                        } else{ ?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php } ?>
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
        <div class="xWPageTIBPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvTIBClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvTIBClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvTIBClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div> -->
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvTBIModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmTBIConfirmPdtDTTemp" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>
<!-- ======================================================================================================================================== -->

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvTBIModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTBITextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdTBIConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="obtTBIConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>
<!-- ======================================================================================================================================== -->

<script type="text/javascript">
    function JStTBIFindObjectByKey(e,t,l){for(var o=0;o<e.length;o++)if(e[o][t]===l)return"Dupilcate";return"None"}function JSxTBIShowButtonChoose(){var e=[JSON.parse(localStorage.getItem("LocalItemData"))];console.log(e),null==e[0]||""==e[0]?$("#oliBtnDeleteAll").addClass("disabled"):(nNumOfArr=e[0].length,nNumOfArr>1?$("#oliBtnDeleteAll").removeClass("disabled"):$("#oliBtnDeleteAll").addClass("disabled"))}function JSxTBITextInModal(){var e=[JSON.parse(localStorage.getItem("LocalItemData"))];if(null==e[0]||""==e[0]);else{var t="";for($i=0;$i<e[0].length;$i++)t+=e[0][$i].nCode,t+=" , ";e[0].length>1?$(".xCNIconDel").addClass("xCNDisabled"):$(".xCNIconDel").removeClass("xCNDisabled"),$("#ospTBITextConfirmDelMultiple").text("ท่านต้องการลบข้อมูลทั้งหมดหรือไม่ ?"),$("#ohdTBIConfirmIDDelMultiple").val(t)}}function JSxTBIDelDocMultiple(){var e=JCNxFuncChkSessionExpired();if(void 0!==e&&1==e){var t=$("#odvTBIModalDelDocMultiple #ohdTBIConfirmIDDelMultiple").val(),l=t.substring(0,t.length-2).split(" , "),o=l.length,a=[];for($i=0;$i<o;$i++)a.push(l[$i]);o>1&&(JCNxOpenLoading(),localStorage.StaDeleteArray="1",$.ajax({type:"POST",url:"docTBIEventDelete",data:{tTBIDocNo:a},cache:!1,timeout:0,success:function(e){var t=JSON.parse(e);"1"==t.nStaEvent?($("#odvTBIModalDelDocMultiple").modal("hide"),$("#odvTBIModalDelDocMultiple #ospTBITextConfirmDelMultiple").empty(),$("#odvTBIModalDelDocMultiple #ohdTBIConfirmIDDelMultiple").val(""),$(".modal-backdrop").remove(),localStorage.removeItem("LocalItemData"),setTimeout(function(){JSvTBICallPageTransferReceipt()},500)):(JCNxCloseLoading(),FSvCMNSetMsgErrorDialog(t.tStaMessg))},error:function(e,t,l){if(JCNxResponseError(e,t,l),404!=e.status){var o=e.status,i=$(e.responseText).find("p:nth-child(3)").text();JCNxPackDataToMQLog(i,o,"ลบใบรับโอน - สาขา","ERROR",a)}}}))}else JCNxShowMsgSessionExpired()}localStorage.removeItem("LocalItemData"),$(document).ready(function(){$(".ocbListItem").unbind().click(function(){var e=$(this).parent().parent().parent().data("code"),t=$(this).parent().parent().parent().data("name");$(this).prop("checked",!0);var l=localStorage.getItem("LocalItemData"),o=[];l&&(o=JSON.parse(l));var a=[JSON.parse(localStorage.getItem("LocalItemData"))];if(""==a||null==a)o.push({nCode:e,tName:t}),localStorage.setItem("LocalItemData",JSON.stringify(o)),JSxTBITextInModal();else{var i=JStTBIFindObjectByKey(a[0],"nCode",e);if("None"==i)o.push({nCode:e,tName:t}),localStorage.setItem("LocalItemData",JSON.stringify(o)),JSxTBITextInModal();else if("Dupilcate"==i){localStorage.removeItem("LocalItemData"),$(this).prop("checked",!1);var n=a[0].length;for($i=0;$i<n;$i++)a[0][$i].nCode==e&&delete a[0][$i];var r=[];for($i=0;$i<n;$i++)null!=a[0][$i]&&r.push(a[0][$i]);localStorage.setItem("LocalItemData",JSON.stringify(r)),JSxTBITextInModal()}}JSxTBIShowButtonChoose()}),$("#odvTBIModalDelDocMultiple #obtTBIConfirmDelMultiple").unbind().click(function(){var e=JCNxFuncChkSessionExpired();void 0!==e&&1==e?JSxTBIDelDocMultiple():JCNxShowMsgSessionExpired()})});
</script>