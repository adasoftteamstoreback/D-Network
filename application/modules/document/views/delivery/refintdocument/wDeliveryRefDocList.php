
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
    <table class="table xWPdtTableFont">
        <thead>
            <tr class="xCNCenter">
                <th nowrap ><?=language('document/document/document','ประเภทอ้างอิง')?></th>
                <th nowrap ><?=language('document/document/document','ชื่อเอกสาร')?></th>
                <th nowrap><?=language('document/document/document','เลขที่เอกสารอ้างอิง')?></th>
                <th nowrap ><?=language('document/document/document','วันที่เอกสารอ้างอิง')?></th>
                <th nowrap ><?=language('document/document/document','ค่าอ้างอิง')?></th>
                <th nowrap class="xCNTextBold xCNHideWhenCancelOrApprove" style="width:70px;"><?=language('common/main/main','tCMNActionDelete')?></th>
                <th nowrap class="xCNTextBold xCNHideWhenCancelOrApprove" style="width:70px;"><?=language('common/main/main','tCMNActionEdit')?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if( $aDataDocHDRef['tCode'] == '1' ){

                foreach($aDataDocHDRef['aItems'] as $aValue){
                    $tDisabledBtn = "";
                    $nStaAlwDelete = "1";

                    if( $aValue['FTXthRefType'] == '2' ){ // กรณีถูกอ้างอิง จะลบ/แก้ไข ไม่ได้
                        $tDisabledBtn   = "xCNDocDisabled";
                        $nStaAlwDelete  = "2";
                    } ?>
                    <tr data-refdocno="<?=$aValue['FTXthRefDocNo']?>" data-alwdel="<?=$nStaAlwDelete?>" data-reftype="<?=$aValue['FTXthRefType']?>" data-refdocdate="<?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?>" data-refkey="<?=$aValue['FTXthRefKey']?>" >
                        <td nowrap><?=language('document/document/document','tDocRefType'.$aValue['FTXthRefType'])?></td>
                        <td nowrap>
                            <?php
                                $tTitleDoc = substr($aValue['FTXthRefDocNo'],0,2);
                                if($aValue['FTXthRefType'] == 3){
                                    echo "เอกสารภายนอก";
                                }else{
                                    echo 'ใบขาย';
                                }
                            ?>
                        </td>
                        <td nowrap><?=$aValue['FTXthRefDocNo']?></td>
                        <td nowrap class="text-center"><?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?></td>
                        <td nowrap class="text-left"><?=$aValue['FTXthRefKey']?></td>
                        <td nowrap class="text-center xCNHideWhenCancelOrApprove">
                            <img class="xCNIconTable xCNIconDel xWDelDocRef <?=$tDisabledBtn?>" src="<?=base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                        </td>
                        <td nowrap class="text-center xCNHideWhenCancelOrApprove">
                            <img class="xCNIconTable xWEditDocRef <?=$tDisabledBtn?>" src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>">
                        </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr><td class="text-center xCNTextDetail2" colspan="100%"><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>

    $( document ).ready(function() {
        //control ปุ่ม [อนุมัติแล้ว หรือยกเลิก]
        if(tDLVStaApv == 1 || tDLVStaDoc == 3){
            // checkbox ทั้งหมด
            $('.xCNHideWhenCancelOrApprove').hide();
        }
    });

    //กดลบข้อมูล
    $('.xWDelDocRef').off('click').on('click',function(){
        var tRefDocNo = $(this).parents().parents().attr('data-refdocno');
        var nAlwDel   = $(this).parents().parents().attr('data-alwdel');

        if( nAlwDel != "2" ){
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docDLVEventDelHDDocRef",
                data:{
                    'ptDocNo'         : $('#oetDLVDocNo').val(),
                    'ptRefDocNo'      : tRefDocNo
                },
                cache: false,
                timeout: 0,
                success: function(oResult){
                    var aResult = JSON.parse(oResult);
                    if( aResult['nStaEvent'] == 1 ){
                        JSxDLVCallPageHDDocRef();
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
        }
    });

    //กดแก้ไข
    $('.xWEditDocRef').off('click').on('click',function(){
        var tRefDocNo   = $(this).parents().parents().attr('data-refdocno');
        var tRefType    = $(this).parents().parents().attr('data-reftype');
        var tRefDocDate = $(this).parents().parents().attr('data-refdocdate');
        var tRefKey     = $(this).parents().parents().attr('data-refkey');
        var tRefType    = $(this).parents().parents().attr('data-reftype');
        var nAlwDel     = $(this).parents().parents().attr('data-alwdel');

        if( nAlwDel != "2" ){

            var nTypeDoc = $('#ocmDLVPackType').val();
            $('#ocbDLVRefDoc option[value=1]').show();
            $('#ocbDLVRefDoc option[value=2]').show();
            $('#ocbDLVRefType').val(tRefType);
            $('#ocbDLVRefType').selectpicker('refresh');
            $('#oetDLVRefDocDate').datepicker({ dateFormat: 'yy-mm-dd' }).val(tRefDocDate);

            if(tRefType == 1){//ภายใน
                $('#oetDLVDocRefIntName').val(tRefDocNo);
                $('#oetDLVDocRefInt').val(tRefDocNo);
            }else{ //ภายนอก
                $('#oetDLVRefDocNo').val(tRefDocNo);
            }

            if( tRefType == 1){//ภายใน
                $('.xWShowRefExt').hide();
                $('.xWShowRefInt').show();
            }else{ //ภายนอก
                $('.xWShowRefInt').hide();
                $('.xWShowRefExt').show();
            }

            $('#oetDLVRefKey').val(tRefKey);
            $('#oetDLVRefDocNoOld').val(tRefDocNo);
            $('#odvDLVModalAddDocRef').modal('show');
        }
    });

</script>
