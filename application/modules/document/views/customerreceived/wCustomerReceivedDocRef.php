<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
    <table class="table xWPdtTableFont" id="otbCRVDocRef">
        <thead>
            <tr class="xCNCenter">
                <th nowrap ><?php echo language('document/expenserecord/expenserecord','ประเภทอ้างอิง')?></th>
                <th nowrap><?php echo language('document/expenserecord/expenserecord','เลขที่เอกสารอ้างอิง')?></th>
                <th nowrap ><?php echo language('document/expenserecord/expenserecord','วันที่เอกสารอ้างอิง')?></th>
                <th nowrap ><?php echo language('document/expenserecord/expenserecord','ค่าอ้างอิง')?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $tRefKeyOld = @$aDataDocHDRef['aItems'][0]['FTXthRefKey'];
        ?>
        <input type="hidden" id="ohdRefKeyOld" value="<?=$tRefKeyOld;?>">
            <?php if( $aDataDocHDRef['tCode'] == '1' ){
                foreach($aDataDocHDRef['aItems'] as $aValue){ ?>
                    <tr data-refdocno="<?=$aValue['FTXthRefDocNo']?>" data-reftype="<?=$aValue['FTXthRefType']?>" data-refdocdate="<?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?>" data-refkey="<?=$aValue['FTXthRefKey']?>" >
                        <td nowrap><?=language('document/document/document','tDocRefType'.$aValue['FTXthRefType'])?></td>
                        <td nowrap><?=$aValue['FTXthRefDocNo']?></td>
                        <td nowrap class="text-center"><?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?></td>
                        <td nowrap class="text-left">
                            <?php if( $aValue['FTXthRefType'] != '' ){ echo $aValue['FTXthRefKey']; }else{ echo "-"; } ?>
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

    $(document).ready(function () {
        JSxCRVControlFormWhenCancelOrApprove();
    });

    //กดลบข้อมูล
    $('.xWDelDocRef').off('click').on('click',function(){
        var tRefDocNo = $(this).parents().parents().attr('data-refdocno');
        //JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docCRVEventDelHDDocRef",
            data:{
                'ptDocNo'         : $('#oetCRVDocNo').val(),
                'ptRefDocNo'      : tRefDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    JSxCRVCallPageHDDocRef();
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
        $('#ocbCRVRefType').val(tRefType);
        $('#ocbCRVRefType').selectpicker('refresh');
        $('#oetCRVRefDocDate').datepicker({ dateFormat: 'yy-mm-dd' }).val(tRefDocDate);

        if(tRefType == 1){//ภายใน
            $('#oetCRVRefIntDoc').val(tRefDocNo);
            $('.xWShowRefInt').show();
            $('.xWShowRefExt').hide();
        }else{ //ภายนอก
            $('#oetCRVRefDocNo').val(tRefDocNo);
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }

        $('#oetCRVRefKey').val(tRefKey);
        $('#oetCRVRefDocNoOld').val(tRefDocNo);
        $('#odvCRVModalAddDocRef').modal('show');
    });
    
</script>
    