<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
    <table class="table xWPdtTableFont">
        <thead>
            <tr class="xCNCenter">
                <th nowrap ><?php   echo language('document/apdebitnote/apdebitnote','tAPDDocRefType')?></th>
                <th nowrap><?php    echo language('document/apdebitnote/apdebitnote','tAPDDocRefDocNo')?></th>
                <th nowrap ><?php   echo language('document/apdebitnote/apdebitnote','tAPDDocRefDocDate')?></th>
                <th nowrap ><?php   echo language('document/apdebitnote/apdebitnote','tAPDDocRefValue')?></th>
                <th nowrap class="xCNTextBold xCNHideWhenCancelOrApprove" style="width:70px;"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                <th nowrap class="xCNTextBold xCNHideWhenCancelOrApprove" style="width:70px;"><?php echo language('common/main/main','tCMNActionEdit')?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $tRefKeyOld = @$aDataDocHDRef['aItems'][0]['FTXthRefKey'];
            ?>
            <input type="hidden" id="ohdRefKeyOld" value="<?=$tRefKeyOld;?>">
            <?php if( $aDataDocHDRef['tCode'] == '1' ): ?>
                <?php foreach($aDataDocHDRef['aItems'] as $aValue): ?>
                    <tr 
                        data-refdocno="<?=$aValue['FTXthRefDocNo']?>"
                        data-reftype="<?=$aValue['FTXthRefType']?>"
                        data-refdocdate="<?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?>"
                        data-refkey="<?=$aValue['FTXthRefKey']?>"
                    >
                        <td nowrap><?=language('document/document/document','tDocRefType'.$aValue['FTXthRefType'])?></td>
                        <td nowrap><?=$aValue['FTXthRefDocNo']?></td>
                        <td nowrap class="text-center"><?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?></td>
                        <td nowrap class="text-left">
                            <?php if( $aValue['FTXthRefType'] != '' ){ echo $aValue['FTXthRefKey']; }else{ echo "-"; } ?>
                        </td>
                        <td nowrap class="text-center xCNHideWhenCancelOrApprove">
                            <img class="xCNIconTable xCNIconDel xWDelDocRef" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                        </td>
                        <td nowrap class="text-center xCNHideWhenCancelOrApprove">
                            <img class="xCNIconTable xWEditDocRef" src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>">
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php else: ?>
                <tr><td class="text-center xCNTextDetail2" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    // Function : กดลบข้อมูล อ้างอิงเอกสาร
    // Creator  : 10/03/2022 Wasin
    $('.xWDelDocRef').off('click').on('click',function(){
        var tRefDocNo = $(this).parents().parents().attr('data-refdocno');
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnoteEventDelHDDocRef",
            data:{
                'ptDocNo'    : $('#oetAPDDocNo').val(),
                'ptRefDocNo' : tRefDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    FSxAPDCallPageHDDocRef();
                }else{
                    var tMessageError   = aResult['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });
    
    // Function : กดแก้ไข อ้างอิงเอกสาร
    // Creator  : 10/03/2022 Wasin
    $('.xWEditDocRef').off('click').on('click',function(){
        var tRefDocNo   = $(this).parents().parents().attr('data-refdocno');
        var tRefType    = $(this).parents().parents().attr('data-reftype');
        var tRefDocDate = $(this).parents().parents().attr('data-refdocdate');
        var tRefKey     = $(this).parents().parents().attr('data-refkey');
        $('#ocbAPDRefType').val(tRefType);
        $('#ocbAPDRefType').selectpicker('refresh');
        $('#oetAPDRefDocDate').datepicker({ dateFormat: 'yy-mm-dd' }).val(tRefDocDate);
        if(tRefType == 1){//ภายใน
            $('#oetAPDDocRefIntName').val(tRefDocNo);
            $('#oetAPDDocRefInt').val(tRefDocNo);
        }else{ //ภายนอก
            $('#oetAPDRefDocNo').val(tRefDocNo);
        }

        $('#oetAPDRefKey').val(tRefKey);
        $('#oetAPDRefDocNoOld').val(tRefDocNo);
        $('#odvAPDModalAddDocRef').modal('show');
    });
</script>