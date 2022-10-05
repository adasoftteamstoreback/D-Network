<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
    <table class="table xWPdtTableFont">
        <thead>
            <tr class="xCNCenter">
                <th nowrap ><?php echo language('document/expenserecord/expenserecord','ประเภทอ้างอิง')?></th>
                <th nowrap><?php echo language('document/expenserecord/expenserecord','ประเภทเอกสาร')?></th>
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

                        <?php
                            switch ($aValue['FTXthRefKey']) {
                                case "PCK":
                                    $tTypeDoc = 'ใบหยิบสินค้า / ใบจัดสินค้า';
                                    break;
                                case "QT":
                                    $tTypeDoc = 'ใบเสนอราคา';
                                    break;
                                case "PO":
                                    $tTypeDoc = 'ใบสั่งขาย';
                                    break;
                                case "ABB":
                                    $tTypeDoc = 'ใบขาย';
                                    break;
                                case "DO":
                                    $tTypeDoc = 'ใบรับของ';
                                    break;
                                default:
                            }
                        ?>

                        <td nowrap><?=$tTypeDoc?></td>
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
                <?php } ?>
            <?php }else{ ?>
                <tr><td class="text-center xCNTextDetail2" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>

    var nSOStaApv =  $('#ohdSOStaApv').val();
    var nSOStaDoc =  $('#ohdSOStaDoc').val();
    if(nSOStaApv == 2 || nSOStaApv == 1 || nSOStaDoc == 3){
        //เพิ่มข้อมูลสินค้า
        $('.xCNHideWhenCancelOrApprove').hide();
    }

    //กดลบข้อมูล
    $('.xWDelDocRef').off('click').on('click',function(){
        var tRefDocNo = $(this).parents().parents().attr('data-refdocno');
        $.ajax({
            type    : "POST",
            url     : "docSOEventDelHDDocRef",
            data:{
                'ptDocNo'         : $('#oetSODocNo').val(),
                'ptRefDocNo'      : tRefDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    JSxSOCallPageHDDocRef();
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
        $('#ocbSORefType').val(tRefType);
        $('#ocbSORefType').selectpicker('refresh');
        $('#oetSORefDocDate').datepicker({ dateFormat: 'yy-mm-dd' }).val(tRefDocDate);

        if(tRefType == 1){//ภายใน
            $('#oetSORefIntDoc').val(tRefDocNo);
        }else{ //ภายนอก
            $('#oetSORefDocNo').val(tRefDocNo);
        }

        $('#oetSORefKey').val(tRefKey);
        $('#oetSORefDocNoOld').val(tRefDocNo);
        $('#odvSOModalAddDocRef').modal('show');
    });
    
</script>
    