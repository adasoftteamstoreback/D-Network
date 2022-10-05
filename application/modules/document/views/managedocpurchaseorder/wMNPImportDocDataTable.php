<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table" >
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold" style="width:5%; vertical-align: middle;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" disabled>
                                <span class="ospListItem xCNDocDisabled xCNCENCheckDeleteAll">&nbsp;</span>
                            </label>
                        </th>
                        <th nowrap class="xCNTextBold" style="width:10%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPCreateBCH');?></th>
                        <th nowrap class="xCNTextBold" style="width:10%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDocumentNumber');?><br><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDocPOPre');?></th>
                        <th nowrap class="xCNTextBold" style="width:5%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQty');?><br><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYBranchAll');?></th>
                        <th nowrap class="xCNTextBold" style="width:5%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQty');?><br><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYProductAll');?></th>
                        <th nowrap class="xCNTextBold" style="width:10%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDocRef');?><br><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDocPRB');?></th>
                        <th nowrap class="xCNTextBold" style="width:13%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDocumentNumber');?><br><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDocPRS');?></th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPSPLTo');?></th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;">ปลายทางสาขา</th>
                        <th nowrap class="xCNTextBold" style="width:5%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusDoc');?></th>
                        <th nowrap class="xCNTextBold" style="width:5%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','หมายเหตุ');?></th>
                        <th nowrap class="xCNTextBold" style="width:5%; vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','ตรวจสอบ');?></th>
                        <th nowrap class="xCNTextBold" style="vertical-align: middle;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPIconManage');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>

                            <?php 
                                //รวมคอลัมน์
                                if($aValue['PARTITIONBYDOC'] == 1){
                                    $nRowspan = '';
                                }else{
                                    $nRowspan = "rowspan=".$aValue['PARTITIONBYDOC'];
                                }

                                //สถานะเอกสาร
                                $tCssDivStaDoc  = '';
                                $tCssTextStaDoc = '';
                                $tTextStaDoc    = '';
                                if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == null && $aValue['MGTStaApv'] == null)){ //รอยินยัน
                                    $tCssDivStaDoc      = 'xWCSSYellowBG';
                                    $tCssTextStaDoc     = 'xWCSSYellowColor';
                                    $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusWait');
                                    $tTextRemark        = '-';
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == null) ){ //ยืนยันแล้วรออนุมัติ
                                    $tCssDivStaDoc      = 'xWCSSCarrotBG';
                                    $tCssTextStaDoc     = 'xWCSSCarrotColor';
                                    $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusConfrimAndWaitAprove');
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == 1) ){ //อนุมัติแล้ว
                                    $tCssDivStaDoc      = 'xWCSSGreenBG';
                                    $tCssTextStaDoc     = 'xWCSSGreenColor';
                                    $tTextStaDoc        = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusAprove');
                                }

                                //สถานะเอกสาร - control class
                                $tClassStaDoc = '';
                                if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == null && $aValue['MGTStaApv'] == null)){ //รอยินยัน
                                    $tClassStaDoc = 'xCNCheckbox_WaitConfirm'; 
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == null) ){ //ยืนยันแล้วรออนุมัติ
                                    $tClassStaDoc = 'xCNCheckbox_WaitAprove'; 
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['FTFleObj'] == null )){ //รอส่งไฟล์
                                    $tClassStaDoc = 'xCNCheckbox_WaitGenFile'; 
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['MGTStaPrcDoc'] != 2 )){ //รอส่งอีเมล์
                                    $tClassStaDoc = 'xCNCheckbox_WaitExport'; 
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['MGTStaPrcDoc'] == 2 )){ //รอส่งอีเมล์
                                    $tClassStaDoc = ''; 
                                }

                                //สถานะเอกสาร - control checkbox
                                $tCheckboxDisabled = '';
                                $tClassDisabled = '';
                                if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == null && $aValue['MGTStaApv'] == null)){ //รอยินยัน
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = "";
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == null) ){ //ยืนยันแล้วรออนุมัติ
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = "";
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['FTFleObj'] == null )){ //รอส่งไฟล์
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = "";
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['MGTStaPrcDoc'] != 2 )){ //รอส่งอีเมล์
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = "";
                                }else if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['MGTStaPrcDoc'] == 2 )){ //รอส่งอีเมล์
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = "xCNDocDisabled";
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
                                    <td <?=$nRowspan?>><?=$aValue['MGTBchName_Frm']?></td>
                                    <td <?=$nRowspan?>><?=$aValue['FTXphDocNo']?></td>
                                    <td <?=$nRowspan?> class="text-right"><?=$aValue['FNXphQtyBch']?></td>
                                    <td <?=$nRowspan?> class="text-right"><?=$aValue['FCXphQtyPdt']?></td> 
                                <?php } ?>

                                
                                <td><?=$aValue['FTXrhDocRqSpl']?></td>
                                <td><?=$aValue['FTXpdDocPo']?></td>
                                <td nowrap><?=$aValue['MGTSplName']?></td>
                                <td nowrap><?=$aValue['MGTBchName_To']?></td>
                                <td nowrap>
                                    <div class="xWCSSDotStatus <?=$tCssDivStaDoc?>"></div> 
                                    <span class="<?=$tCssTextStaDoc?>"><?=$tTextStaDoc?></span> 
                                </td>   

                                <!--หมายเหตุ-->
                                <?php
                                    if($aValue['FTXrhStaDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['MGTStaPrcDoc'] != 2){ 
                                        $tTextRemark = 'รอส่งอีเมล์';
                                    }else if($aValue['FTXrhStaDoc'] == 1 && $aValue['MGTStaApv'] == 1 && $aValue['MGTStaPrcDoc'] == 2){
                                        $tTextRemark = 'ส่งอีเมล์แล้ว';
                                    }else{
                                        $tTextRemark = '-';
                                    }
                                ?>
                                <td nowrap><?=$tTextRemark?></td>
                                    
                                <!--ดาวน์โหลด-->
                                <?php
                                    //สถานะดาวน์โหลด
                                    if($aValue['FTFleObj'] == '' || $aValue['FTFleObj'] == null){
                                        $tCssPathDowload  = '';
                                        $aHrefPathDowload = '';
                                        $tTextPathDowload = '-';
                                    }else{
                                        $tCssPathDowload  = 'xCNCssPathDowload';
                                        $aHrefPathDowload =  $aValue['FTFleObj'];
                                        $tTextPathDowload = 'ตรวจสอบ';
                                    }

                                ?>
                                <td nowrap class="<?=$tCssPathDowload?>" style="padding: 2px;">
                                    <?php 
                                        if($aHrefPathDowload == '' || $aHrefPathDowload == null){
                                            echo $tTextPathDowload;
                                        }else{
                                            echo '<a href="'.$aHrefPathDowload.'" style="font-size: 16px !important;" target="_blank" >'.$tTextPathDowload.'</a>'; 
                                        }
                                    ?>
                                </td>

                                <!--ถ้ายังไม่ได้สร้างจะแก้ไขได้-->
                                <?php if($aValue['FTXrhStaDoc'] == 1 && ( $aValue['FTXrhStaPrcDoc'] == 1 )){ ?>
                                    <td class="text-center"> 
                                        <img class="xCNIconTable" src="<?=base_url().'/application/modules/common/assets/images/icons/view2.png'?>" onClick="JSxGotoPagePurchaseorder('<?=$aValue['FTXpdDocPo']?>')">
                                    </td>
                                <?php }else{ ?>
                                    <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                        <td class="text-center" <?=$nRowspan?>> 
                                            <img class="xCNIconTable" src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>"  onClick="JSvMNPCallPageEdit('<?=$aValue['FTXphDocNo']?>')">
                                        </td>
                                    <?php } ?>
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
        <div class="xWPageMNPPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvMNPClickPageList('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
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
                <button onclick="JSvMNPClickPageList('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvMNPClickPageList('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<script>
     $('.ocbListItem').click(function(){
        var tClasscheckbox     = $(this).parent().parent().parent().data('classcheckbox'); 
        JSxMNPControlButtonEvent(tClasscheckbox);
    });

    //control เอาไว้ checkbox 
    function JSxMNPControlButtonEvent(ptClasscheckbox){
        var tCheckEventClick = '';
        if(ptClasscheckbox == 'xCNCheckbox_WaitConfirm'){ //รอยืนยัน
            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNPCreateDocRef').show();    //เปิดปุ่ม
                $('#obtMNPCallPageAdd').hide();     //ปิดปุ่มสร้าง
                $('#obtMNPApproveDoc').hide();      //ปิดปุ่ม
                $('#obtMNPGenFileAgain').hide();    //ปิดปุ่ม
                $('#obtMNPExportDoc').hide();       //ปิดปุ่ม
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').attr('disabled',true);
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNPCallPageAdd').show();
                $('#obtMNPApproveDoc').hide();      //ปิดปุ่ม
                $('#obtMNPGenFileAgain').hide();    //ปิดปุ่ม
                $('#obtMNPExportDoc').hide();       //ปิดปุ่ม
                $('#obtMNPCreateDocRef').hide();    //ปิดปุ่ม
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').attr('disabled',false);
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitExport').parent().find('span').removeClass('xCNDocDisabled');
            }
        }else if(ptClasscheckbox == 'xCNCheckbox_WaitAprove'){ //ยืนยันแล้วรออนุมัติ
            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNPApproveDoc').show();      //เปิดปุ่ม
                $('#obtMNPCallPageAdd').hide();     //ปิดปุ่มสร้าง
                $('#obtMNPGenFileAgain').hide();    //ปิดปุ่ม
                $('#obtMNPExportDoc').hide();       //ปิดปุ่ม
                $('#obtMNPCreateDocRef').hide();    //ปิดปุ่ม
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').attr('disabled',true);
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNPCallPageAdd').show();
                $('#obtMNPApproveDoc').hide();      //ปิดปุ่ม
                $('#obtMNPGenFileAgain').hide();    //ปิดปุ่ม
                $('#obtMNPExportDoc').hide();       //ปิดปุ่ม
                $('#obtMNPCreateDocRef').hide();    //ปิดปุ่ม
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').attr('disabled',false);
                $('.xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').parent().find('span').removeClass('xCNDocDisabled');
            }
        }else if(ptClasscheckbox == 'xCNCheckbox_WaitGenFile'){ //สร้างไฟล์อีกครั้ง
            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNPGenFileAgain').show();    //เปิดปุ่ม
                $('#obtMNPCallPageAdd').hide();     //ปิดปุ่มสร้าง
                $('#obtMNPApproveDoc').hide();      //ปิดปุ่ม
                $('#obtMNPExportDoc').hide();       //ปิดปุ่ม
                $('#obtMNPCreateDocRef').hide();    //ปิดปุ่ม
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').attr('disabled',true);
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNPCallPageAdd').show();
                $('#obtMNPApproveDoc').hide();      //ปิดปุ่ม
                $('#obtMNPGenFileAgain').hide();    //ปิดปุ่ม
                $('#obtMNPExportDoc').hide();       //ปิดปุ่ม
                $('#obtMNPCreateDocRef').hide();    //ปิดปุ่ม
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').attr('disabled',false);
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitExport').parent().find('span').removeClass('xCNDocDisabled');
            }
        }else if(ptClasscheckbox == 'xCNCheckbox_WaitExport'){ //ส่งอีเมล์
            $("."+ptClasscheckbox).each(function() {
                if($(this).prop("checked") == true){
                    tCheckEventClick = '1';
                    return;
                }
            });

            if(tCheckEventClick == 1){
                $('#obtMNPExportDoc').show();       //เปิดปุ่ม
                $('#obtMNPCallPageAdd').hide();     //ปิดปุ่มสร้าง
                $('#obtMNPApproveDoc').hide();      //ปิดปุ่ม
                $('#obtMNPGenFileAgain').hide();    //ปิดปุ่ม
                $('#obtMNPCreateDocRef').hide();    //ปิดปุ่ม
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').attr('disabled',true);
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').parent().find('span').addClass('xCNDocDisabled');

                //เอาไว้ control ว่า เลือกทั้งหมดของแต่ละประเภท
                JSxControlCheckBoxAll(ptClasscheckbox);
            }else{
                $('#obtMNPCallPageAdd').show();
                $('#obtMNPApproveDoc').hide();      //ปิดปุ่ม
                $('#obtMNPGenFileAgain').hide();    //ปิดปุ่ม
                $('#obtMNPExportDoc').hide();       //ปิดปุ่ม
                $('#obtMNPCreateDocRef').hide();    //ปิดปุ่ม
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').attr('disabled',false);
                $('.xCNCheckbox_WaitAprove , .xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitGenFile').parent().find('span').removeClass('xCNDocDisabled');
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
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitExport').attr('disabled',false);
                $('.xCNCheckbox_WaitConfirm , .xCNCheckbox_WaitAprove , .xCNCheckbox_WaitGenFile , .xCNCheckbox_WaitExport').parent().find('span').removeClass('xCNDocDisabled');
                $('.ocmCENCheckDeleteAll').attr('disabled',true);
                $('.xCNCENCheckDeleteAll').addClass('xCNDocDisabled');
                $('#obtMNPApproveDoc , #obtMNPCreateDocRef , #obtMNPExportDoc , #obtMNPGenFileAgain').hide(); //ปิดปุ่ม
                $('#obtMNPCallPageAdd').show();
            }else{
                $("."+ptClasscheckbox).each(function() {
                    $(this).prop('checked', true);
                });
            }
        });
    }

    //ไปหน้า PO
    function JSxGotoPagePurchaseorder(ptDocumentRef){
        $.ajax({
            type    : "GET",
            url     : 'docPO/0/0',
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);

                //เก็บเอาไว้ว่า มาจากหน้าจอจัดการใบสั่งสินค้าจากสาขา
                localStorage.tCheckBackStage = 'PageMangeDocPO';

                setTimeout(function(){                 
                    JSvPOCallPageEditDoc(ptDocumentRef);
                }, 1000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }


</script>