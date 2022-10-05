<style>
    .table>tbody>tr>td.text-danger{
        color: #F9354C !important;
    }
</style>
<?php 
    $nCurrentPage = '1';
?>
<?php 
    $tImportStatus = "1";
    if( $aPdtPriDataList['rtCode'] == 1 ){
        foreach($aPdtPriDataList['raItems'] as $DataTableKey => $DataTableVal){
            if( $DataTableVal['FTTmpStatus'] != '1' ){
                $tImportStatus = $DataTableVal['FTTmpStatus'];
            }
        }
    }
?>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="otbSpaDataList" class="table table-striped" style="margin-bottom: 0px;">
                <thead>
                    <tr>
                    <th class="text-center otdListItem">
                        <label class="fancy-checkbox">
                            <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxSPASelectAll(this)">
                            <span class="">&nbsp;</span>
                        </label>
                    </th>
                    <th><?= language('document/purchaseorder/purchaseorder','ลำดับ')?></th>
                    <th ><?= language('document/purchaseorder/purchaseorder', 'รหัสสินค้า')?></th>
                    <th ><?= language('document/purchaseorder/purchaseorder', 'ชื่อสินค้า')?></th>
                    <th ><?= language('document/purchaseorder/purchaseorder', 'หน่วยสินค้า')?></th>
                    <th ><?= language('document/purchaseorder/purchaseorder', 'ราคาล่าสุด')?></th>
                    <?php 
                    if( $tImportStatus != "1" ){
                    ?>
                            <th><?= language('document/salepriceadj/salepriceadj','หมายเหตุ')?></th>
                    <?php } ?>
                    <th ><?= language('document/purchaseorder/purchaseorder', 'ราคาขาย')?></th>
                    <?php if(@$tXphStaApv != 1 && @$tXphStaDoc != 3){?>
                        <th class="xWDeleteBtnEditButton text-center xCNPIBeHideMQSS"><?= language('document/salepriceadj/salepriceadj','tPdtPriTBDelete')?></th>
                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aPdtPriDataList['rtCode'] == 1 ):?>
                        <?php $nIndex = 1; ?>
                        <?php foreach($aPdtPriDataList['raItems'] as $DataTableKey => $DataTableVal){  
                            $aAllpunCode = (explode(",",$DataTableVal['FTAllPunCode']));
                            $aAllpunName = (explode(",",$DataTableVal['FTAllPunName']));?>
                            <tr class="text-center xCNTextDetail2 otrSpaPdtPri xWPdtItem" 
                            id="otrSpaPdtPri<?=$DataTableVal['FNXtdSeqNo']?>" 
                            name="otrSpaPdtPri" 
                            data-doc="<?=$DataTableVal['FTXthDocNo']?>" 
                            data-code="<?=$DataTableVal['FTPdtCode']?>" 
                            data-pun="<?=$DataTableVal['FTPunCode']?>" 
                            data-seq="<?=$DataTableVal['FNXtdSeqNo']?>"
                            data-status="<?=$DataTableVal['FTTmpStatus']?>"
                            data-rmk="<?=$DataTableVal['FTTmpRemark']?>" 
                            data-page="<?=$nCurrentPage?>">
                                <td nowrap class="text-center otdListItem">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?=$DataTableVal['FNXtdSeqNo']?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                        <span class="ospListItem">&nbsp;</span>
                                    </label>
                                    <input type="hidden" id="ohdFTPunCode<?=$DataTableVal['FNXtdSeqNo']?>" name="ohdFTPunCode<?=$DataTableVal['FNXtdSeqNo']?>" value="<?=$DataTableVal['FTPunCode']?>">
                                    <input type="hidden" id="ohdFTXpdShpTo<?=$DataTableVal['FNXtdSeqNo']?>" name="ohdFTXpdShpTo<?=$DataTableVal['FNXtdSeqNo']?>" value="<?=$DataTableVal['FTXtdShpTo']?>">
                                    <input type="hidden" id="ohdFTXpdBchTo<?=$DataTableVal['FNXtdSeqNo']?>" name="ohdFTXpdBchTo<?=$DataTableVal['FNXtdSeqNo']?>" value="<?=$DataTableVal['FTXtdBchTo']?>">
                                </td>
                                <td><?=$DataTableVal['FNXtdSeqNo']?></td>
                                <td class="text-left" ><label class="text-left xCNPdtFont xWShowValueFTPdtCode<?php echo $DataTableVal['FNXtdSeqNo']?>"><?php echo $DataTableVal['FTPdtCode']?></label></td>
                                <td class="text-left" ><label class="text-left xCNPdtFont xWShowValueFTPdtName <?php echo $DataTableVal['FNXtdSeqNo']?>"><?php echo $DataTableVal['FTPdtName']?></label></td>
                                <td class="text-left">
                                <?php if(@$tXphStaApv == 1 || @$tXphStaDoc == 3){?>
                                <label class="text-right xCNPdtFont xWShowValuePuncode <?php echo $DataTableVal['FNXtdSeqNo']?>"><?php echo $DataTableVal['FTPunName']?>

                                    <?php }else{ ?>
                                    <select class="form-control xWSelectDis" id="ocmXphPuncode" name="ocmXphPuncode" maxlength="20" seq="<?=$DataTableVal['FNXtdSeqNo']?>" onchange="JSxEditPun(value,this);">
                                        <?php foreach($aAllpunCode as $nKeypun => $aValPun){ ?>
                                        <option id="optStaAdj1" value="<?php echo $aValPun ?>" <?= $aValPun == $DataTableVal['FTPunCode'] ? "selected" : ""; ?> ><?php echo $aAllpunName[$nKeypun] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                    <!-- <label class="text-left xCNPdtFont xWShowValueFTPunName<?php echo $DataTableVal['FNXtdSeqNo']?>"><?php echo $DataTableVal['FTPunName']?></label> -->
                                </td>
                                <td class="text-center"><label id='olaOriginalPrice<?php echo $DataTableVal['FNXtdSeqNo']?>' class='xWOriginalPriceClick xCNLinkClick' data-seq='<?php echo $DataTableVal['FNXtdSeqNo']?>' style='cursor:pointer'><?php echo language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceOgn')?></label></td>
                                <?php if( $tImportStatus != "1" ){ ?>
                                    <td nowrap class="xCNAdjPriceStaRmk text-left text-danger"><?php echo $DataTableVal['FTTmpRemark']; ?></td>
                                <?php } ?>
                                <td nowrap class="text-right">
                                            <input type="hidden" 
                                            name="ohdSPAFrtPdtCode" 
                                            id="ohdSPAFrtPdtCode<?=$DataTableVal['FTPdtCode']?><?=$DataTableVal['FTPunCode']?>"
                                            value="ohdFCXtdPriceRet<?=$DataTableVal['FNXtdSeqNo']?>">

                                            <div class=" xWEditInLine<?=$DataTableVal['FNXtdSeqNo']?>">
                                                <input 
                                                    style="    
                                                        background: rgb(249, 249, 249);
                                                        box-shadow: 0px 0px 0px inset;
                                                        border-top: 0px !important;
                                                        border-left: 0px !important;
                                                        border-right: 0px !important;
                                                        padding: 0px;
                                                        text-align: right;
                                                    "
                                                    type="text" 
                                                    class="form-control xStaDocEdit xWValueEditInLine<?=$DataTableVal['FNXtdSeqNo']?> xCNInputNumericWithDecimal text-right"
                                                    id="ohdFCXtdPriceRet<?=$DataTableVal['FNXtdSeqNo']?>" 
                                                    name="ohdFCXtdPriceRet<?=$DataTableVal['FNXtdSeqNo']?>" 
                                                    maxlength="11" 
                                                    value="<?= number_format($DataTableVal['FCXtdPriceRet']) ?>"
                                                    autocomplete="off"
                                                    seq="<?=$DataTableVal['FNXtdSeqNo']?>"
                                                    columname="FCXtdPriceRet"
                                                    col-validate=""
                                                    page="<?=$nPage?>"
                                                    b4value="<?= number_format($DataTableVal['FCXtdPriceRet']) ?>"
                                                    onkeypress=" if(event.keyCode==13 ){     event.preventDefault(); return JSxSpaSaveInLine(event,this); } "
                                                    onfocusout="JSxSpaSaveInLine(event,this)"
                                                    onclick="JSxSPASetValueCommaOut(this)"
                                                >
                                            </div>
                                </td>
                                
                            <?php if(@$tXphStaApv != 1 && @$tXphStaDoc != 3){?>
                            
                                <td nowrap class="text-center xWInLine xCNPIBeHideMQSS">
                                <label class="xCNTextLink xWLabelInLine">
                                    <img class="xCNIconTable xCNDeleteInLineClick" data-seq="<?=$DataTableVal['FNXtdSeqNo']?>" src="<?=base_url().'/application/modules/common/assets/images/icons/delete.png'?>" title="Remove">
                                </label>
                                </td>
                            <?php } ?>
                            </tr>
                            <?php $nIndex++ ?>
                        <?php } ?>
                 
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2 xWTextNotfoundDataSalePriceAdj' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>

        </div>
    </div>
</div>


<div class="row" style="margin-top:10px;"></div>

<!-- Modal Delete Items -->
<div class="modal fade" id="odvModalDelSpaPdtPri">
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
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoSpaPdtPriDelChoose('<?=$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Show Original Price -->
<div class="modal fade" id="odvModalOriginalPrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <label class="xCNTextModalHeard" id="exampleModalLabel"><?= language('document/salepriceadj/salepriceadj','tPdtPriTiTleOrnPri')?></label>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="odvDetailOriginalPrice">
        ...
      </div>
    </div>
  </div>
</div>

<!-- Modal Show Column -->
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

<script type="text/javascript">

//เปลี่ยนข้อความใน Table
var nXphStaAdj = $('#ocmXphStaAdj').val();
if(nXphStaAdj == 1){
    var tTextTHInTable = "ราคาขาย";
}else if(nXphStaAdj == 2){
    var tTextTHInTable = "ปรับลด %";
}else if(nXphStaAdj == 3){
    var tTextTHInTable = "ปรับลด มูลค่า";
}else if(nXphStaAdj == 4){
    var tTextTHInTable = "ปรับเพิ่ม %";
}else if(nXphStaAdj == 5){
    var tTextTHInTable = "ปรับเพิ่ม มูลค่า";
}else{
    var tTextTHInTable = "ราคาขาย";
}
$('.xCNPriceRetInAdjPrice').text(tTextTHInTable);

$('#ocbCheckAll').click(function(){
    if($(this).is(':checked')==true){
        $('.ocbListItem').prop('checked',true);
        $("#odvMngTableList #oliBtnDeleteAll").removeClass("disabled");
    }else{
        $('.ocbListItem').prop('checked',false);
        $("#odvMngTableList #oliBtnDeleteAll").addClass("disabled");
    }
});

function FSxSPASelectAll(){
    if($('.ocbListItemAll').is(":checked")){
        $('.ocbListItem').each(function (e) { 
            if(!$(this).is(":checked")){
                $(this).on( "click", FSxSPASelectMulDel(this) );
            }
        });
    }else{
        $('.ocbListItem').each(function (e) { 
            if($(this).is(":checked")){
                $(this).on( "click", FSxSPASelectMulDel(this) );
            }
        });
    }
}

$('.xCNDeleteInLineClick').off('click');
$('.xCNDeleteInLineClick').on('click',function(){
    var nSeq  = $(this).data('seq');
    var nPage = $('#otrSpaPdtPri'+nSeq).data('page');
    var tDoc  = $('#otrSpaPdtPri'+nSeq).data('doc');
    var tPdt  = $('#otrSpaPdtPri'+nSeq).data('code');
    var tPun  = $('#otrSpaPdtPri'+nSeq).data('pun');
    var tSta  = $('#otrSpaPdtPri'+nSeq).data('status');

    JSoSpaPdtPriDel(nPage,tDoc,tPdt,tPun,nSeq,tSta);
});

if($('#ohdXphStaApv').val()==1){
    $('.xStaDocEdit').prop('disabled',true);
    $('.xWSelectDis').prop('disabled',true);
}

if($('#oetStaDoc').val()==3){
    $('.xStaDocEdit').prop('disabled',true);
    $('.xWSelectDis').prop('disabled',true);
}


function JSxSPASetValueCommaOut(e){

    var tValueNext     = parseFloat($(e).val().replace(/,/g, ''));
            $(e).val(tValueNext);
            $(e).focus();
            $(e).select();
    
}

$('.xWOriginalPriceClick').click(function(){
    var elem = $(this).data('seq');
    JSxSPAShowOriginalPrice(elem);
});

$('ducument').ready(function(){
    JSxShowButtonChoose();
	var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
	var nlength = $('#odvRGPList').children('tr').length;
	for($i=0; $i < nlength; $i++){
        var tDataCode = $('#otrSpaPdtPri'+$i).data('seq');
		if(aArrayConvert == null || aArrayConvert == ''){
		}else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'tSeq',tDataCode);
			if(aReturnRepeat == 'Dupilcate'){
				$('#ocbListItem'+$i).prop('checked', true);
			}else{ }
		}
	}

	$('.ocbListItem').click(function(){
        var tSeq = $(this).parent().parent().parent().data('seq'); // Pdt
        var tPdt = $(this).parent().parent().parent().data('code'); // Pdt
        var tDoc = $(this).parent().parent().parent().data('doc'); // Doc
        var tPun = $(this).parent().parent().parent().data('pun'); // Pun
        var tSta = $(this).parent().parent().parent().data('status'); // Pun

        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }else{ }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"tSeq": tSeq, "tPdt": tPdt, "tDoc": tDoc, "tPun": tPun, "tSta" : tSta });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxSpaPdtPriTextinModal();
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'tSeq',tSeq);
            if(aReturnRepeat == 'None' ){ // ยังไม่ถูกเลือก
                obj.push({"tSeq": tSeq, "tPdt": tPdt, "tDoc": tDoc, "tPun": tPun, "tSta" : tSta });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxSpaPdtPriTextinModal();
            }else if(aReturnRepeat == 'Dupilcate'){	// เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeq == tSeq){
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
                JSxSpaPdtPriTextinModal();
            }
        }
        JSxShowButtonChoose();
    })
});

function FSxSPASelectMulDel(ptElm){
    // $('#otbDODocPdtAdvTableList #odvTBodyDOPdtAdvTableList .ocbListItem').click(function(){
    var tSeq = $(ptElm).parents('.xWPdtItem').data('seq'); // Pdt
    var tPdt = $(ptElm).parents('.xWPdtItem').data('code'); // Pdt
    var tDoc = $(ptElm).parents('.xWPdtItem').data('doc'); // Doc
    var tPun = $(ptElm).parents('.xWPdtItem').data('pun'); // Pun
    var tSta = $(ptElm).parents('.xWPdtItem').data('status'); // Pun

    $(ptElm).prop('checked', true);
    var LocalItemData = localStorage.getItem("LocalItemData");
    var obj = [];
    if(LocalItemData){
        obj = JSON.parse(LocalItemData);
    }else{ }
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if(aArrayConvert == '' || aArrayConvert == null){
        obj.push({"tSeq": tSeq, "tPdt": tPdt, "tDoc": tDoc, "tPun": tPun, "tSta" : tSta });
        localStorage.setItem("LocalItemData",JSON.stringify(obj));
        JSxSpaPdtPriTextinModal();
    }else{
        var aReturnRepeat = findObjectByKey(aArrayConvert[0],'tSeq',tSeq);
        if(aReturnRepeat == 'None' ){ // ยังไม่ถูกเลือก
            obj.push({"tSeq": tSeq, "tPdt": tPdt, "tDoc": tDoc, "tPun": tPun, "tSta" : tSta });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxSpaPdtPriTextinModal();
        }else if(aReturnRepeat == 'Dupilcate'){	// เคยเลือกไว้แล้ว
            localStorage.removeItem("LocalItemData");
            $(this).prop('checked', false);
            var nLength = aArrayConvert[0].length;
            for($i=0; $i<nLength; $i++){
                if(aArrayConvert[0][$i].tSeq == tSeq){
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
            JSxSpaPdtPriTextinModal();
        }
    }
    JSxShowButtonChoose();
}

</script>