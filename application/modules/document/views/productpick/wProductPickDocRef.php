<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
    <table class="table xWPdtTableFont">
        <thead>
            <tr class="xCNCenter">
                <th nowrap ><?php echo language('document/expenserecord/expenserecord','ประเภทอ้างอิง')?></th>
                <th nowrap><?php echo language('document/expenserecord/expenserecord','เลขที่เอกสารอ้างอิง')?></th>
                <th nowrap ><?php echo language('document/expenserecord/expenserecord','วันที่เอกสารอ้างอิง')?></th>
                <th nowrap ><?php echo language('document/expenserecord/expenserecord','ค่าอ้างอิง')?></th>
                <th nowrap class="xCNTextBold xCNHideWhenCancelOrApprove" style="width:70px;"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                <th nowrap class="xCNTextBold xCNHideWhenCancelOrApprove" style="width:70px;"><?php echo language('common/main/main','tCMNActionEdit')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if( $aDataDocHDRef['tCode'] == '1' ){
                foreach($aDataDocHDRef['aItems'] as $aValue){ ?>
                    <tr data-refdocno="<?=$aValue['FTXthRefDocNo']?>" data-reftype="<?=$aValue['FTXthRefType']?>" data-refdocdate="<?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?>" data-refkey="<?=$aValue['FTXthRefKey']?>" >
                        <td nowrap><?=language('document/document/document','tDocRefType'.$aValue['FTXthRefType'])?></td>
                        <td nowrap><?=$aValue['FTXthRefDocNo']?></td>
                        <td nowrap class="text-center"><?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?></td>
                        <td nowrap class="text-left">
                            <?php if( $aValue['FTXthRefType'] != '' ){ echo $aValue['FTXthRefKey']; }else{ echo "-"; } ?>
                        </td>
                        <td nowrap class="text-center xCNHideWhenCancelOrApprove">
                            <img class="xCNIconTable xCNIconDel xWDelDocRef " src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                        </td>
                        <td nowrap class="text-center xCNHideWhenCancelOrApprove">
                            <img class="xCNIconTable xWEditDocRef " src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>">
                        </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr><td class="text-center xCNTextDetail2" colspan="100%"><?php echo language('common/main/main','กรุณาเลือกเอกสารอ้างอิงใบสั่งงานเพื่อหยิบสินค้า')?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>


    if (bIsApvOrCancel && !bIsAddPage) {
        $('.xCNHideWhenCancelOrApprove').hide();  
     } 

    //กดลบข้อมูล
    $('.xWDelDocRef').off('click').on('click',function(){
        var tRefDocNo = $(this).parents().parents().attr('data-refdocno');
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docPCKEventDelHDDocRef",
            data:{
                'ptDocNo'         : $('#oetPCKDocNo').val(),
                'ptRefDocNo'      : tRefDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    FSxPCKCallPageHDDocRef();
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

    //กดแก้ไข
    $('.xWEditDocRef').off('click').on('click',function(){
        var tRefDocNo   = $(this).parents().parents().attr('data-refdocno');
        var tRefType    = $(this).parents().parents().attr('data-reftype');
        var tRefDocDate = $(this).parents().parents().attr('data-refdocdate');
        var tRefKey     = $(this).parents().parents().attr('data-refkey');
        $('#ocbPCKRefType').val(tRefType);
        $('#ocbPCKRefType').selectpicker('refresh');
        $('#oetPCKRefDocDate').datepicker({ dateFormat: 'yy-mm-dd' }).val(tRefDocDate);

        if(tRefType == 1){//ภายใน
            $('#oetPCKDocRefIntName').val(tRefDocNo);
            $('#oetPCKDocRefInt').val(tRefDocNo);
        }else{ //ภายนอก
            $('#oetPCKRefDocNo').val(tRefDocNo);
        }

        $('#oetPCKRefKey').val(tRefKey);
        $('#oetPCKRefDocNoOld').val(tRefDocNo);
        $('#odvPCKModalAddDocRef').modal('show');
    });

    <?php //if(@$tPCKTypeDocument == 1){ //ใบขอซื้อแบบสำนักงานใหญ่ ?>
          
    <?php //}else{ //ใบขอซื้อแบบแฟรนไชส์ ?>
        //$('.xCNHideWhenPCKFN').hide();
    <?php // } ?>

</script>
    