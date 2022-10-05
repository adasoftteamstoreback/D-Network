<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbMNGTblDataDocHDList" class="table">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold" style="width:5%; vertical-align: middle;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" disabled>
                                <span class="ospListItem xCNDocDisabled xCNCENCheckDeleteAll">&nbsp;</span>
                            </label>
                        </th>

                        <?php 
                            if(@$tMNGTypeDocument == 1){ //ใบสั่งสินค้าจากสาขา ?>
                                <th nowrap class="xCNTextBold" style="vertical-align: middle;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHBchOrder')?></th>
                            <?php }else{ //ใบสั่งสินค้าจากแฟรนไซส์ ?>
                                <th nowrap class="xCNTextBold" style="vertical-align: middle;"><?=language('document/managedocorderbranch/managedocorderbranch','สาขา / ลูกค้า')?></th>
                            <?php }   
                        ?>
						<th nowrap class="xCNTextBold" style="width:12%; vertical-align: middle;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHNumberDocBch')?></th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:6%; vertical-align: middle;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocType')?></th>

                        <?php 
                            if(@$tMNGTypeDocument == 1){ //ใบสั่งสินค้าจากสาขา 
                                $tTextPRB   = language('document/managedocorderbranch/managedocorderbranch','tMNGTHTnfAndSpl');
                                $tTextRefTo = language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocRefTo');
                            }else{ //ใบสั่งสินค้าจากแฟรนไซส์ 
                                $tTextPRB   = language('document/managedocorderbranch/managedocorderbranch','(ใบขอโอน,สั่งขาย,ใบขอซื้อ)');
                                $tTextRefTo = language('document/managedocorderbranch/managedocorderbranch','ขอโอน,สั่งขาย,ใบขอซื้อ');
                            }   
                        ?>

                        <th nowrap class="xCNTextBold">
                            <?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocRef')?><br>
                            <?=$tTextPRB?>
                        </th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;">
                            <?=$tTextRefTo?>
                        </th>
                        <th nowrap class="xCNTextBold">
                            <?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocDate')?><br>
                            <?=$tTextPRB?>
                        </th>
                        <th nowrap class="xCNTextBold">
                            <?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocApv')?><br>
                            <?=$tTextPRB?>
                        </th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocRemark')?></th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;">ดาวน์โหลดไฟล์</th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocManage')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>

                            <?php 

                                //สถานะเอกสาร
                                if($aValue['MGTDocType'] == 1 || $aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 3 || $aValue['MGTDocType'] == 4 || $aValue['MGTDocType'] == 5){ 

                                    if(($aValue['MGTDocType'] == 1 || $aValue['MGTDocType'] == 5) && ( $aValue['MGTStaExport'] == 2 || $aValue['MGTStaDoc'] != null ) ){ //ใบขอโอน
                                        $tCheckboxDisabled  = "disabled";
                                        $tClassDisabled     = "xCNDocDisabled";
                                    }else{
                                        if($aValue['MGTStaExport'] == 4){ //ถ้าส่งออกเเล้ว หรือ ส่งออกอีเมล์เเล้ว 
                                            $tCheckboxDisabled  = "disabled";
                                            $tClassDisabled     = "xCNDocDisabled";
                                        }else{
                                            $tCheckboxDisabled  = "";
                                            $tClassDisabled     = "";
                                        } 
                                    }
                                }else{
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = "xCNDocDisabled";
                                }

                                //ประเภทเอกสาร
                                if($aValue['MGTDocType'] == 1){
                                    $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPPRB');
                                }else if($aValue['MGTDocType'] == 2){
                                    $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPPRS');
                                }else if($aValue['MGTDocType'] == 3){
                                    $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPRJ');
                                }else if($aValue['MGTDocType'] == 4){
                                    $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPPRS');
                                }else if($aValue['MGTDocType'] == 5){
                                    $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','ใบสั่งขาย');
                                }else{
                                    $tTextDocRefType = '-';
                                }

                                //สถานะเอกสารใบขอโอน ขอซื้อ ใบสั่งขาย
                                $tCssDivStaDoc  = '';
                                $tCssTextStaDoc = '';
                                if($aValue['MGTDocType'] == 1 || $aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 3 || $aValue['MGTDocType'] == 4 || $aValue['MGTDocType'] == 5){ 
                                    if($aValue['MGTDocType'] == 5){ //เอกสารใบสั่งขาย
                                        $tTextRemark        = '-';
                                        if($aValue['MGTStaDoc'] == null){ //รอสร้าง
                                            $tCssDivStaDoc      = 'xWCSSYellowBG';
                                            $tCssTextStaDoc     = 'xWCSSYellowColor';
                                            $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusWait');
                                            $tTextRemark        = '-';
                                        }else{
                                            if($aValue['MGTStaExport'] == 2){ //อนุมัติเเล้ว
                                                $tCssDivStaDoc      = 'xWCSSGreenBG';
                                                $tCssTextStaDoc     = 'xWCSSGreenColor';
                                                $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusAprove'); 
                                            }else if($aValue['MGTStaExport'] == 5){ //รอสร้าง
                                                $tCssDivStaDoc      = 'xWCSSYellowBG';
                                                $tCssTextStaDoc     = 'xWCSSYellowColor';
                                                $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','รอจัดสินค้า');
                                                $tTextRemark        = '-';
                                            }else if($aValue['MGTStaExport'] == 6){ //จัดแล้วบางส่วน
                                                $tCssDivStaDoc      = 'xWCSSYellowBG';
                                                $tCssTextStaDoc     = 'xWCSSYellowColor';
                                                $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','จัดแล้วบางส่วน');
                                                $tTextRemark        = '-';
                                            }else if($aValue['MGTStaExport'] == 7){ //จัดครบแล้วรออนุมัติ
                                                $tCssDivStaDoc      = 'xWCSSYellowBG';
                                                $tCssTextStaDoc     = 'xWCSSYellowColor';
                                                $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','จัดครบแล้วรออนุมัติ');
                                                $tTextRemark        = '-';
                                            }else{
                                                $tCssDivStaDoc      = 'xWCSSCarrotBG';
                                                $tCssTextStaDoc     = 'xWCSSCarrotColor';
                                                $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusConfrimAndWaitAprove');
                                                $tTextRemark        = '-';
                                            }
                                        }
                                    }else{
                                        if($aValue['MGTStaDoc'] == null && $aValue['MGTStaExport'] == null){ //รอสร้าง
                                            $tCssDivStaDoc      = 'xWCSSYellowBG';
                                            $tCssTextStaDoc     = 'xWCSSYellowColor';
                                            $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusWait');
                                            $tTextRemark        = '-';
                                        }else if($aValue['MGTStaDoc'] == 1 && $aValue['MGTStaExport'] == 1){ //สร้างแล้วรออนุมัติ
                                            $tCssDivStaDoc      = 'xWCSSCarrotBG';
                                            $tCssTextStaDoc     = 'xWCSSCarrotColor';
                                            $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusConfrimAndWaitAprove');
                                            $tTextRemark        = '-';
                                        }else if($aValue['MGTStaDoc'] == 1 && $aValue['MGTStaExport'] == 2){ //อนุมัติแล้วรอส่งออก
                                            $tCssDivStaDoc      = 'xWCSSGreenBG';
                                            $tCssTextStaDoc     = 'xWCSSGreenColor';
                                            $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusAprove');
                                            if($aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 4){ //จะโชว์สำหรับ ขอซื้อเท่านั้น
                                                $tTextRemark    = 'รอส่งอีเมล์';
                                            }else{
                                                $tTextRemark    = '-';
                                            }
                                        }else if($aValue['MGTStaDoc'] == 1 && ( $aValue['MGTStaExport'] == 3 || $aValue['MGTStaExport'] == 4 )){ //สมบูรณ์
                                            $tCssDivStaDoc      = 'xWCSSGreenBG';
                                            $tCssTextStaDoc     = 'xWCSSGreenColor';
                                            $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusAprove');
                                            if($aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 4){ //จะโชว์สำหรับ ขอซื้อเท่านั้น
                                                if($aValue['MGTStaExport'] == 3){
                                                    $tTextRemark    = 'รอส่งอีเมล์';
                                                }else{
                                                    $tTextRemark    = 'ส่งอีเมล์แล้ว';
                                                }
                                            }else{
                                                $tTextRemark    = '-';
                                            }
                                        }
                                    }
                                }else{
                                    $tCssDivStaDoc      = 'xWCSSBlackBG';
                                    $tCssTextStaDoc     = 'xWCSSBlackColor';
                                    $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusDocProcess');
                                    $tTextRemark        = '-';
                                }

                                //ขอโอน + ขอซื้อไปยัง
                                if($aValue['MGTDocType'] == 1 || $aValue['MGTDocType'] == 5){ //ขอโอน + สั้งขาย
                                    $tTextRefTo = $aValue['MGTBchName'];
                                }else if($aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 4){ //ขอซื้อ 
                                    $tTextRefTo = $aValue['MGTSplName'];
                                }else{
                                    $tTextRefTo = '-';
                                }

                                //รวมคอลัมน์
                                if($aValue['PARTITIONBYDOC'] == 1){
                                    $nRowspan = '';
                                }else{
                                    $nRowspan = "rowspan=".$aValue['PARTITIONBYDOC'];
                                }

                                //สถานะเอกสาร
                                if($aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 1 || $aValue['MGTDocType'] == 4 || $aValue['MGTDocType'] == 5){ //ถ้ามีเอกสารลูก
                                    if($aValue['MGTStaDoc'] == null && $aValue['MGTStaExport'] == null){ //รอสร้าง
                                        $tClassStaDoc = 'xCNCheckbox_WaitConfirm';
                                    }else if($aValue['MGTStaDoc'] == 1 && $aValue['MGTStaExport'] == 1){ //สร้างแล้วรออนุมัติ
                                        $tClassStaDoc = 'xCNCheckbox_WaitAprove'; 
                                    }else if($aValue['MGTStaDoc'] == 1 && ($aValue['MGTStaExport'] == 2 && $aValue['FTFleObj'] == null) ){ //อนุมัติแล้ว แต่ไม่มีไฟล์
                                        $tClassStaDoc = 'xCNCheckbox_WaitGenFile'; 
                                    }else if($aValue['MGTStaDoc'] == 1 && ($aValue['MGTStaExport'] == 3 && $aValue['FTFleObj'] == null) ){ //อนุมัติแล้ว สร้างไฟล์แล้ว แต่ไฟล์ไม่สำเร็จ
                                        $tClassStaDoc = 'xCNCheckbox_WaitGenFile'; 
                                    }else if($aValue['MGTStaDoc'] == 1 && $aValue['MGTStaExport'] == 3 ){ //อนุมัติแล้ว มีไฟล์แล้ว แล้วรอส่งเมล์
                                        $tClassStaDoc = 'xCNCheckbox_WaitExport'; 
                                    }else if($aValue['MGTStaDoc'] == 1 && $aValue['MGTStaExport'] == 4 ){ //สมบูรณ์
                                        $tClassStaDoc = '';
                                    }else{
                                        $tClassStaDoc = '';
                                    }
                                }else{
                                    $tClassStaDoc = '';
                                }

                                //สถานะดาวน์โหลด
                                if($aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 4){
                                    if($aValue['FTFleObj'] == '' || $aValue['FTFleObj'] == null){
                                        $tCssPathDowload  = '';
                                        $aHrefPathDowload = '';
                                        $tTextPathDowload = '-';
                                    }else{
                                        $tCssPathDowload  = 'xCNCssPathDowload';
                                        $aHrefPathDowload =  $aValue['FTFleObj'];
                                        $tTextPathDowload = 'ดาวน์โหลด';
                                    }
                                }else{
                                    $tCssPathDowload  = '';
                                    $aHrefPathDowload = '';
                                    $tTextPathDowload = '-';
                                }
                            ?>

                            <tr class="text-left xCNTextDetail2" data-classcheckbox="<?=$tClassStaDoc?>" data-docnoref="<?=$aValue['FTXphDocNo']?>" >

                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <td nowrap class="text-center" <?=$nRowspan?>>
                                        <label class="fancy-checkbox">
                                            <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem <?=$tClassStaDoc?>" name="ocbListItem[]" <?=$tCheckboxDisabled;?>>
                                            <span class="<?=$tClassDisabled?>">&nbsp;</span>
                                        </label>
                                    </td>
                                    <td <?=$nRowspan?>><?=$aValue['FTBchName']?></td>
                                    <td <?=$nRowspan?>><?=$aValue['FTXphDocNo']?></td>
                                    <td <?=$nRowspan?> class="text-center"><?=($aValue['rtDocDate'] == '' ) ? '-' : $aValue['rtDocDate']?></td>   
                                <?php } ?>

                                <td><?=$tTextDocRefType?></td>
                                <td><?=($aValue['MGTDocRef']  == '' ) ? '-' : $aValue['MGTDocRef']?></td>
                                <td><?=$tTextRefTo?></td>
                                <td class="text-center"><?=($aValue['MGTDate'] == '' ) ? '-' : $aValue['MGTDate']?></td>
                                <td>
                                    <div class="xWCSSDotStatus <?=$tCssDivStaDoc?>"></div> 
                                    <span class="<?=$tCssTextStaDoc?>"><?=$tTextStaDoc?></span> 
                                </td>
                                <td><?=$tTextRemark?></td>
                                <td class="<?=$tCssPathDowload?>" style="padding: 2px;">
                                    <?php 
                                        if($aHrefPathDowload == '' || $aHrefPathDowload == null){
                                            echo $tTextPathDowload;
                                        }else{
                                            echo '<a href="'.$aHrefPathDowload.'" style="font-size: 16px !important;">'.$tTextPathDowload.'</a>'; 
                                        }
                                    ?>
                                </td>
                                
                                <?php if($aValue['MGTDocType'] == 1 || $aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 3 || $aValue['MGTDocType'] == 4 || $aValue['MGTDocType'] == 5){ ?>
                                    <?php if($aValue['MGTStaDoc'] == null && $aValue['MGTStaExport'] == null){ //่รอยืนยัน ?>
                                        <?php 
                                            if($aValue['PARTITIONBYDOC_AND_TYPE'] == 1){
                                                $nRowspan_TYPE = '';
                                            }else{
                                                $nRowspan_TYPE = "rowspan=".$aValue['PARTITIONBYDOC_AND_TYPE'];
                                            }
                                        ?> 
                                        <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                            <td class="text-center" <?=$nRowspan?>> 
                                                <img class="xCNIconTable" src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>"  onClick="JSvMNGCallPageEditDoc('<?=$aValue['FTXphDocNo']?>')">
                                            </td>
                                        <?php } ?>
                                    <?php }else{ //รออนุมัติ + อนุมัติแล้วรอส่งออก ?>
                                        <td class="text-center"> 
                                            <?php 
                                                if($aValue['MGTDocType'] == 1){ //ขอโอน ?> 
                                                    <img onClick="JSxGotoPageTranferOrBuySpl('<?=$aValue['MGTDocRef']?>','1','<?=$aValue['MGTAgnFrm']?>','<?=$aValue['BCHHQ']?>')" class="xCNIconTable" style="width: 17px;" src="<?=base_url().'/application/modules/common/assets/images/icons/view2.png'?>" >
                                                <?php }else if($aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 4){ //ขอซื้อ ?> 
                                                    <img onClick="JSxGotoPageTranferOrBuySpl('<?=$aValue['MGTDocRef']?>','2','','')" class="xCNIconTable" style="width: 17px;" src="<?=base_url().'/application/modules/common/assets/images/icons/view2.png'?>" >
                                                <?php }else if($aValue['MGTDocType'] == 3){ //ไม่สั่งซื้อ ?>
                                                    -
                                                <?php }else if($aValue['MGTDocType'] == 5){ //สั่งขาย ?>
                                                    <img onClick="JSxGotoPageTranferOrBuySpl('<?=$aValue['MGTDocRef']?>','5','','')" class="xCNIconTable" style="width: 17px;" src="<?=base_url().'/application/modules/common/assets/images/icons/view2.png'?>" >
                                                <?php } 
                                            ?>
                                        </td>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <td class="text-center"> 
                                        <img class="xCNIconTable" src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>"  onClick="JSvMNGCallPageEditDoc('<?=$aValue['FTXphDocNo']?>')">
                                    </td>
                                <?php } ?>
                            </tr>

                            <?php $tKeepDocNo = $aValue['FTXphDocNo']; ?>
                        <?php endforeach;?>
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
        <div class="xWPageMNGPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvMNGClickPageList('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
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
                <button onclick="JSvMNGClickPageList('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvMNGClickPageList('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<script>
    $('.ocbListItem').click(function(){
        var tClasscheckbox     = $(this).parent().parent().parent().data('classcheckbox'); 
        JSxMGTControlButtonEvent(tClasscheckbox);
    });

    //control เอาไว้ checkbox 
    function JSxMGTControlButtonEvent(ptClasscheckbox){
        $('#obtMNGApproveDoc').attr('disabled',false);
        
        var tCheckEventClick = '';
        if(ptClasscheckbox == 'xCNCheckbox_WaitConfirm'){ //รอยืนยัน (สร้าง) -> รออนุมัติ

            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNGCreateDocRef').show(); //เปิดปุ่ม
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').attr('disabled',true);
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNGCreateDocRef').hide(); //ปิดปุ่ม
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').attr('disabled',false);
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').parent().find('span').removeClass('xCNDocDisabled');
            }
        }else if(ptClasscheckbox == 'xCNCheckbox_WaitAprove'){ //รออนุมัติ -> อนุมัติแล้ว
            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNGApproveDoc').show();
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').attr('disabled',true);
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNGApproveDoc').hide();
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').attr('disabled',false);
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').parent().find('span').removeClass('xCNDocDisabled');
            }
        }else if(ptClasscheckbox == 'xCNCheckbox_WaitExport'){ //อนุมัติแล้ว -> ส่งออก
            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNGExportDoc').show();
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').attr('disabled',true);
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNGExportDoc').hide();
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').attr('disabled',false);
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').parent().find('span').removeClass('xCNDocDisabled');
            }
        }else if(ptClasscheckbox == 'xCNCheckbox_WaitGenFile'){ //อนุมัติแล้วแต่ไฟล์ไม่สมบูรณ์ รอสร้างไฟล์ใหม่อีกรอบ
            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNGGenFileAgain').show();
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').attr('disabled',true);
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNGGenFileAgain').hide();
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').attr('disabled',false);
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').parent().find('span').removeClass('xCNDocDisabled');
            }
        }
    }

    //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
    function JSxControlCheckBoxAll(ptClasscheckbox){
        $('.ocmCENCheckDeleteAll').attr('disabled',false);
        $('.xCNCENCheckDeleteAll').removeClass('xCNDocDisabled');

        $('.ocmCENCheckDeleteAll').unbind().click(function() {
            var bStatus = $(this).is(":checked") ? true : false;
            if(bStatus == false){
                $('.ocbListItem').prop('checked', false);
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').attr('disabled',false);
                $('.xCNCheckbox_WaitExport , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile').parent().find('span').removeClass('xCNDocDisabled');
                $('.ocmCENCheckDeleteAll').attr('disabled',true);
                $('.xCNCENCheckDeleteAll').addClass('xCNDocDisabled');
                $('#obtMNGCreateDocRef , #obtMNGApproveDoc , #obtMNGExportDoc , #obtMNGGenFileAgain').hide(); //ปิดปุ่ม
            }else{
                $("."+ptClasscheckbox).each(function() {
                    $(this).prop('checked', true);
                });
            }
        });
    }

    //กดเพื่อที่ไปหน้าไปขอซื้อ หรือ ขอโอน
    function JSxGotoPageTranferOrBuySpl(ptDocumentRef,ptType,ptAgnCode,ptBchCode){

        if(ptType == 1){ //ขอโอน
            var tRoute = 'docTRB/0/0';
        }else if(ptType == 2){ //ขอซื้อ
            var tRoute = 'docPrs/0/0';
        }else if(ptType == 5){ //สั่งขาย
            var tRoute = 'dcmSO/0/0';
        }

        $.ajax({
            type    : "GET",
            url     : tRoute,
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);

                //เก็บเอาไว้ว่า มาจากหน้าจอจัดการใบสั่งสินค้าจากสาขา
                if('<?=@$tMNGTypeDocument?>' == 1){
                    localStorage.tCheckBackStage = 'PageMangeDocOrderBCH';
                }else{
                    localStorage.tCheckBackStage = 'PageMangeDocOrderBCHHQ';
                }

                setTimeout(function(){
                    if(ptType == 1){ //ขอโอน
                        JSvTRBCallPageEdit(ptBchCode,ptAgnCode,ptDocumentRef);
                    }else if(ptType == 2){ //ขอซื้อ
                        JSvPRSCallPageEdit(ptDocumentRef);
                    }else if(ptType == 5){ //สั่งขาย
                        JSvSOCallPageEditDoc(ptDocumentRef,'');
                    }
                },1000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
</script>