<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
    <table class="table xWPdtTableFont">
        <thead>
            <tr class="xCNCenter">
                <th nowrap style="width:20%"><?php echo language('document/expenserecord/expenserecord','ประเภทอ้างอิง')?></th>
                <th nowrap><?php echo language('document/expenserecord/expenserecord','เลขที่เอกสารอ้างอิง')?></th>
                <th nowrap style="width:20%"><?php echo language('document/expenserecord/expenserecord','วันที่เอกสารอ้างอิง')?></th>
                <th nowrap style="width:10%"><?php echo language('document/expenserecord/expenserecord','ค่าอ้างอิง')?></th>
                <th nowrap class="xCNTextBold xWHideColumOnApv" style="width:5%;"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                <th nowrap class="xCNTextBold xWHideColumOnApv" style="width:5%;"><?php echo language('common/main/main','tCMNActionEdit')?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                if( $aDataDocHDRef['tCode'] == '1' ){
                    foreach($aDataDocHDRef['aItems'] as $aValue){
            ?>
                        <tr data-refdocno="<?=$aValue['FTXthRefDocNo']?>" data-reftype="<?=$aValue['FTXthRefType']?>" data-refdocdate="<?=$aValue['FDXthRefDocDate']?>" data-refkey="<?=$aValue['FTXthRefKey']?>" >
                            <td nowrap><?=language('document/document/document','tDocRefType'.$aValue['FTXthRefType'])?></td>
                            <td nowrap><?=$aValue['FTXthRefDocNo']?></td>
                            <td nowrap class="text-center"><?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?></td>
                            <td nowrap class="text-left">
                                <?php if( $aValue['FTXthRefType'] == '3' ){ echo $aValue['FTXthRefKey']; }else{ echo "-"; } ?>
                            </td>
                            <td nowrap class="text-center xWHideColumOnApv">
                                <img class="xCNIconTable xCNIconDel xWPXDelDocRef" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                            </td>
                            <td nowrap class="text-center xWHideColumOnApv">
                                <img class="xCNIconTable xWPXEditDocRef" src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>">
                            </td>
                        </tr>
            <?php
                    }
                }else{
            ?>
                    <tr><td class="text-center xCNTextDetail2" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>

<script>
    $('.xWPXDelDocRef').off('click').on('click',function(){
        var tRefDocNo = $(this).parents().parents().attr('data-refdocno');
        JCNxOpenLoading();
        $.ajax({
            type : "POST",
            url: "docPXEventDelHDDocRef",
            data:{
                'ptPXDocNo'         : $('#oetPXDocNo').val(),
                'ptRefDocNo'        : tRefDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    FSxPXCallPageHDDocRef();
                }else{
                    var tMessageError = aResult['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });

    $('.xWPXEditDocRef').off('click').on('click',function(){

        var tRefDocNo   = $(this).parents().parents().attr('data-refdocno');
        var tRefType    = $(this).parents().parents().attr('data-reftype');
        var tRefDocDate = $(this).parents().parents().attr('data-refdocdate');
        var tRefKey     = $(this).parents().parents().attr('data-refkey');

        $('#ocbPXRefType').val(tRefType);
        $('#ocbPXRefType').selectpicker('refresh');

        $('#oetPXRefDocDate').datepicker({ dateFormat: 'yy-mm-dd' }).val(tRefDocDate);
        $('#oetPXRefDocNo').val(tRefDocNo);
        $('#oetPXRefKey').val(tRefKey);

        $('#oetPXRefDocNoOld').val(tRefDocNo);

        $('#odvPXModalAddDocRef').modal('show');

    });
    
</script>
    