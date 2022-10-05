  <div class="table-responsive">
      <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
      <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
      <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tPdtCode?>">
      <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tPunCode?>">
      
      <table class="table table-striped xWPdtTableFont" id="otbDOCPdtTable">
        <thead>
          <tr class="xCNCenter">
            <th class="xCNTWIBeHideMQSS">
              <label class="fancy-checkbox" style="padding-left:7px">
                <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxDOSelectAll(this)" >
                <span class="">&nbsp;</span>
              </label>
            </th>
            <th><?= language('document/purchaseorder/purchaseorder','tPOTBNo')?></th>
            <th ><?= language('document/purchaseorder/purchaseorder', 'รหัสสินค้า')?></th>
            <th ><?= language('document/purchaseorder/purchaseorder', 'ชื่อสินค้า')?></th>
            <th ><?= language('document/purchaseorder/purchaseorder', 'บาร์โค้ด')?></th>
            <th ><?= language('document/purchaseorder/purchaseorder', 'ชื่อหน่วยสินค้า')?></th>
            <th ><?= language('document/purchaseorder/purchaseorder', 'สถานะสต็อค')?></th>
            <th ><?= language('document/purchaseorder/purchaseorder', 'จำนวน')?></th>
            <?php if(@$tXthStaApv == '' && @$tXthStaDoc != 3){ ?>
              <th class="xCNTWIBeHideMQSS"><?= language('document/purchaseorder/purchaseorder','tPOTBDelete')?></th>
              <th class="xWDeleteBtnEditButton xCNTWIBeHideMQSS"><?= language('document/purchaseorder/purchaseorder','tPOTBEdit')?></th>
            <?php } ?>
            <?php if(!((@$tXthStaApv == '') && @$tXthStaDoc != 3)){ ?>
              <th class="xCNTWIBeHideMQSS"><?= language('document/purchaseorder/purchaseorder','tPOTBDelete')?></th>
              <th class="xWDeleteBtnEditButton xCNTWIBeHideMQSS"><?= language('document/purchaseorder/purchaseorder','tPOTBEdit')?></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody id="odvTBodyTFWPdt">
        <?php $nNumSeq = 0; ?>
        <?php if($aDataDT['rtCode'] == 1){?>
              
            <?php foreach($aDataDT['raItems'] as $DataTableKey=>$aDataTableVal){?>
      
                    <tr class="text-center xCNTextDetail2 nItem<?=$nNumSeq?> xWPdtItem"  data-index="<?=$DataTableKey?>" data-docno="<?=$aDataTableVal['FTXthDocNo']?>" data-pdtname="<?=$aDataTableVal['FTXtdPdtName']?>" data-pdtcode="<?=$aDataTableVal['FTPdtCode']?>" data-puncode="<?=$aDataTableVal['FTPunCode']?>" data-seqno="<?=$aDataTableVal['FNXtdSeqNo']?>">
                      <td class="text-center xCNTWIBeHideMQSS">
                          <label class="fancy-checkbox">
                              <input id="ocbListItem<?=$aDataTableVal['FNXtdSeqNo']?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                              <span>&nbsp;</span>
                          </label>
                      </td>
                      <td><?=$aDataTableVal['FNXtdSeqNo']?></td>
                      <!-- <?php foreach($aColumnShow as $DataKey=>$DataVal){

                              $tColumnName = $DataVal->FTShwFedShw;
                              $nColWidth = $DataVal->FNShwColWidth;

                              $tColumnDataType = substr($tColumnName,0,2);

                              if($tColumnDataType == 'FC'){
                                  $tMaxlength = '11';
                                  $tAlignFormat = 'text-right';
                                  $tDataCol =  $aDataTableVal[$tColumnName] != '' ? number_format($aDataTableVal[$tColumnName], $nOptDecimalShow, '.', ',') : number_format(0, $nOptDecimalShow,'.',',');
                                  $InputType = 'text';
                                  $tValidateType = 'xCNInputNumericWithDecimal';
                              }elseif($tColumnDataType == 'FN'){
                                  $tMaxlength = '';
                                  $tAlignFormat = 'text-right';
                                  $tDataCol = $aDataTableVal[$tColumnName] != '' ? number_format($aDataTableVal[$tColumnName], $nOptDecimalShow, '.', ',') : number_format(0, $nOptDecimalShow,'.',',');
                                  $InputType = 'number';
                                  $tValidateType = '';
                              }else{
                                  $tMaxlength = '';
                                  $tAlignFormat = 'text-left';
                                  $tDataCol = $aDataTableVal[$tColumnName];
                                  $InputType = 'text';
                                  $tValidateType = '';
                              }

                              ?>

                              <td nowrap class="<?=$tAlignFormat?>">
                                    <?php if($DataVal->FTShwStaAlwEdit == 1 && (empty($tXthStaApv) && $tXthStaDoc != 3)):?>
                                        <label class="xCNPdtFont xWShowInLine<?=$aDataTableVal['FNXtdSeqNo']?> xWShowValue<?=$tColumnName?><?=$aDataTableVal['FNXtdSeqNo']?>"><?= $tDataCol != '' ? "".$tDataCol : '-'; ?></label></label>
                                        <div class="xCNHide xWEditInLine<?=$aDataTableVal['FNXtdSeqNo']?>">
                                          <input  type="<?=$InputType?>" class="form-control xCNControllForm xCNPdtEditInLine xWValueEditInLine<?=$aDataTableVal['FNXtdSeqNo']?> <?=$tValidateType?> xCNInputWithoutSpc xWEditPdtInline" id="ohd<?=$tColumnName?><?=$aDataTableVal['FNXtdSeqNo']?>" name="ohd<?=$tColumnName?><?=$aDataTableVal['FNXtdSeqNo']?>" maxlength="<?=$tMaxlength?>" value="<?=$aDataTableVal[$tColumnName]?>" data-field="<?=$tColumnName?>"  <?= $tColumnName == 'FTXtdDisChgTxt' ? 'readonly' : '' ?> <?= $tColumnName  == 'FCXtdQty' ?>>
                                        </div>       
                                    <?php else: ?>
                                        <label class="xCNPdtFont xWShowInLine xWShowValue<?=$tColumnName?><?=$aDataTableVal['rtRowID']?>"><?=$tDataCol?></label>
                                    <?php endif;?>                  
                                </td>

                                <?php if($DataKey == '3') { ?>
                                    <td nowrap class="text-center">
                                        <?php
                                            switch ($aDataTableVal['FTXtdStaPrcStk']) {
                                                case '1':
                                                    switch ($aDataTableVal['FTXtdRmk']) {
                                                        case '1':
                                                            echo "<span class='xCNTextConfirm'>ยืนยันแล้ว</span>";
                                                            break;
                                                        case '2':
                                                            echo "<span class='xCNTextConfirm'>ไม่ตรวจสอบสต็อค</span>";
                                                            break;
                                                    }
                                                    break;
                                                default:
                                                    switch ($aDataTableVal['FTXtdRmk']) {
                                                        case '1':
                                                            echo "<span class='xCNTextConfirm'>ยืนยันแล้ว</span>";
                                                            break;
                                                        default:
                                                            echo "<span class='xCNTextWaitConfirm'>รอยืนยัน</span>";
                                                            break;
                                                    }
                                                    break;
                                            }
                                        ?>
                                    </td>   
                                <?php } ?>

                    <?php } ?> -->
                    <td class="text-left" ><label class="text-left xCNPdtFont xWShowValueFTPdtCode<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTPdtCode']?></label></td>
                    <td class="text-left" ><label class="text-left xCNPdtFont xWShowValueFTXtdPdtName<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTXtdPdtName']?></label></td>
                    <td class="text-left"><label class="text-left xCNPdtFont xWShowValueFTXtdBarCode<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTXtdBarCode']?></label></td>
                    <td nowrap class="text-center">
                        <?php
                            switch ($aDataTableVal['FTXtdStaPrcStk']) {
                                case '1':
                                    switch ($aDataTableVal['FTXtdRmk']) {
                                        case '1':
                                            echo "<span class='xCNTextConfirm'>ยืนยันแล้ว</span>";
                                            break;
                                        case '2':
                                            echo "<span class='xCNTextConfirm'>ไม่ตรวจสอบสต็อค</span>";
                                            break;
                                    }
                                    break;
                                default:
                                    switch ($aDataTableVal['FTXtdRmk']) {
                                        case '1':
                                            echo "<span class='xCNTextConfirm'>ยืนยันแล้ว</span>";
                                            break;
                                        default:
                                            echo "<span class='xCNTextWaitConfirm'>รอยืนยัน</span>";
                                            break;
                                    }
                                    break;
                            }
                        ?>
                    </td>   
                    <td class="text-left"><label class="text-left xCNPdtFont xWShowValueFTPunName<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTPunName']?></label></td>
                    <?php if((@$tXthStaApv == '') && @$tXthStaDoc != 3) { ?>
                    <td class="otdQty">
                        <div class="xWEditInLine<?=$aDataTableVal['FNXtdSeqNo']?>">
                            <input type="text" class="xCNQty form-control xWEditPdtInline xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$aDataTableVal['FNXtdSeqNo']?> xWShowInLine<?=$aDataTableVal['FNXtdSeqNo']?> " id="ohdQty<?=$aDataTableVal['FNXtdSeqNo']?>" name="ohdQty<?=$aDataTableVal['FNXtdSeqNo']?>" data-seq="<?=$aDataTableVal['FNXtdSeqNo']?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],$nOptDecimalShow));?>" autocomplete="off">
                        </div>
                    </td>
                    <?php }else{ ?>
                        <td class="text-right"><label class="text-left xCNPdtFont xWShowValueFCXtdQty<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo number_format($aDataTableVal['FCXtdQty'], $nOptDecimalShow, '.', ',')?></label></td>
                    <?php } ?>

                    
                      <td nowrap class="text-center xCNTWIBeHideMQSS">
                        <label class="xCNTextLink">
                            <img class="xCNIconTable
                            <?php if(!((@$tXthStaApv == '') && @$tXthStaDoc != 3)){?>
                              xCNDisabled
                            <?php } ?>" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" title="Remove" 
                            <?php if(((@$tXthStaApv == '') && @$tXthStaDoc != 3)){?>
                              onclick="JSnRemoveDTRow(this)"
                            <?php } ?>
                            >
                        </label>
                      </td>
                      <td nowrap class="text-center xCNTWIBeHideMQSS
                      <?php if(!((@$tXthStaApv == '') && @$tXthStaDoc != 3)){?>
                        xCNDisabled
                      <?php } ?>">
                        <label class="xCNTextLink">
                            <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" title="Edit" 
                            <?php if(((@$tXthStaApv == '') && @$tXthStaDoc != 3)){?>
                              onclick="JSnEditDTRow(this)"
                            <?php } ?>
                            >
                        </label>
                      </td>
                    
                </tr>
                <?php $nNumSeq++; ?>
                <?php }?>
            <?php }else{ ?>
                    <tr><td class='text-center xCNTextDetail2 xWTextNotfoundDataTablePdt' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php } ?>
        
        </tbody>
      </table>
  </div>

  <div class="row">
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataDT['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataDT['rnCurrentPage']?> / <?=$aDataDT['rnAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPageTFWPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvTFWPdtClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataDT['rnAllPage'],$nPage+2)); $i++){?> 
                <?php 
                    if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                
                <button onclick="JSvTFWPdtClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataDT['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvTFWPdtClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="odvShowOrderColumn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <label class="xCNTextModalHeard" id="exampleModalLabel"><?= language('common/main/main','tModalAdvTable')?></label>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="odvOderDetailShowColumn">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= language('common/main/main','tModalAdvClose')?></button>
        <button type="button" class="btn btn-primary" onclick="JSxSaveColumnShow()"><?= language('common/main/main','tModalAdvSave')?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Items -->
<div class="modal fade" id="odvModalDelPdtTFW">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
                <input type='hidden' id="ohdConfirmSeqDelete">
				<input type='hidden' id="ohdConfirmPdtDelete">
                <input type='hidden' id="ohdConfirmPunDelete">
                <input type='hidden' id="ohdConfirmDocDelete">
			</div>
			<div class="modal-footer">
				  <button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoTFWPdtDelChoose('<?=@$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
          <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<script>
  $(document).ready(function(){
  JSxEditQtyAndPrice(); 
  });

    //เเก้ไขจำนวน
    function JSxEditQtyAndPrice() {
    $('.xCNPdtEditInLine').click(function() {
        $(this).focus().select();
    });
    // $('.xCNQty').change(function(e){
    $('.xCNQty').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var nSeq    = $(this).attr('data-seq');
            var nQty        = $('#ohdQty'+nSeq).val();
            nNextTab = parseInt(nSeq)+1;
            $('.xWValueEditInLine'+nNextTab).focus().select();
            JSxGetDisChgList(nSeq);
        }
    });
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxGetDisChgList(pnSeq){
    var nQty        = $('#ohdQty'+pnSeq).val();
    FSvTFWEditPdtIntoTableDT(pnSeq, "FCXtdQty", nQty);
    }
</script>
<?php  include("script/jProducttransferwahousePdtAdvTableData.php");?>