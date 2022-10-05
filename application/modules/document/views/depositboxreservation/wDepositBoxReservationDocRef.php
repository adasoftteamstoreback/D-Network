<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
    <table class="table xWPdtTableFont" id="otbDBRDocRef">
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
        <?php
            $tRefKeyOld = @$aDataDocHDRef['aItems'][0]['FTXthRefKey'];
        ?>
        <input type="hidden" id="ohdRefKeyOld" value="<?=$tRefKeyOld;?>">
            <?php if( $aDataDocHDRef['tCode'] == '1' ){
                foreach($aDataDocHDRef['aItems'] as $aValue){ 
            ?>
                    <?php if( $aValue['FTXthRefKey'] == 'SO' ){ ?>
                    <input type="hidden" id="ohdDBRRefDocSO" name="ohdDBRRefDocSO" value="<?=$aValue['FTXthRefDocNo']?>">
                    <?php } ?>

                    <tr data-refdocno="<?=$aValue['FTXthRefDocNo']?>" data-reftype="<?=$aValue['FTXthRefType']?>" data-refdocdate="<?=date_format(date_create($aValue['FDXthRefDocDate']),'Y-m-d')?>" data-refkey="<?=$aValue['FTXthRefKey']?>" >
                        <td nowrap><?=language('document/document/document','tDocRefType'.$aValue['FTXthRefType'])?></td>
                        <?php switch ($aValue['FTXthRefKey']) {
                            case "SO":
                        ?>
                        <td nowrap style="cursor: pointer;color : #1866ae !important; text-decoration: underline;" onClick="JSvBOOKJumptoSOPage('<?= $aValue['FTXthRefDocNo'] ?>','<?= $aValue['FTXthDocNo'] ?>','RTBook')"><?=$aValue['FTXthRefDocNo']?></td>
                        <?php break;
                        ?>
                        <?php 
                            case "ABB":
                        ?>
                        <td nowrap style="cursor: pointer;color : #1866ae !important; text-decoration: underline;" onClick="JSvBOOKJumptoABBPage('<?= $aValue['FTXthRefDocNo'] ?>','BOOK','<?= $aValue['BookDocno'] ?>')"><?=$aValue['FTXthRefDocNo']?></td>
                        <?php break;
                            case "FULLTAX":
                        ?>
                        <td nowrap style="cursor: pointer;color : #1866ae !important; text-decoration: underline;" onClick="JSvBOOKJumptoTAXPage('<?= $aValue['FTXthRefDocNo'] ?>','<?= $aValue['FTBchCode'] ?>')"><?=$aValue['FTXthRefDocNo']?></td>
                        <?php break;
                            default:
                        ?>
                        <td nowrap><?=$aValue['FTXthRefDocNo']?></td>
                        <?php break;
                        }
                        ?>
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

    $(document).ready(function () {
        JSxDBRControlFormWhenCancelOrApprove();
    });

    // Jump ไปหน้าใบสั่งขาย
    function JSvBOOKJumptoSOPage(ptSODocNo, ptBackTo, ptType){
        
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JStCMMGetPanalLangSystemHTML("JSvSOCallPageEditDoc",ptSODocNo);
            $.ajax({
                type    : "GET",
                url     : "dcmSO/0/0",
                data    : {'ptTypeJump' : '1',
                            'tDocNo'    : ptSODocNo,
                            'tBackTo'   : ptBackTo,
                            'tType'     : ptType },
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    $(window).scrollTop(0);
                    $('.odvMainContent').html(tResult);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Jump ไปหน้าเลขที่ใบขาย
    function JSvBOOKJumptoABBPage(ptSODocNo, ptType,ptBackTo){
        
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JStCMMGetPanalLangSystemHTML("JSvABBCallPageEditDoc",ptSODocNo);
            $.ajax({
                type    : "POST",
                url     : "docABB/0/0",
                data    : {'ptTypeJump' : '1',
                            'tDocNo'    : ptSODocNo,
                            'tType'     : ptType,
                            'tBackTo'   : ptBackTo},
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    $(window).scrollTop(0);
                    $('.odvMainContent').html(tResult);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Jump ไปหน้าใยกำกับภาษีเต็มรูป
    function JSvBOOKJumptoTAXPage(ptSDocNo, ptSCstCode){
        
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JStCMMGetPanalLangSystemHTML("JSvTAXCallPageEditDoc",ptSDocNo);
            $.ajax({
                type    : "POST",
                url     : "dcmTXIN/0/0",
                data    : {'ptTypeJump' : '1',
                            'tDocNo' : ptSDocNo,
                            'tBchCode' : ptSCstCode},
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    $(window).scrollTop(0);
                    $('.odvMainContent').html(tResult);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //กดลบข้อมูล
    $('.xWDelDocRef').off('click').on('click',function(){
        var tRefDocNo = $(this).parents().parents().attr('data-refdocno');
        //JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docDBREventDelHDDocRef",
            data:{
                'ptDocNo'         : $('#oetDBRDocNo').val(),
                'ptRefDocNo'      : tRefDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    JSxDBRCallPageHDDocRef();
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
        $('#ocbDBRRefType').val(tRefType);
        $('#ocbDBRRefType').selectpicker('refresh');
        $('#oetDBRRefDocDate').datepicker({ dateFormat: 'yy-mm-dd' }).val(tRefDocDate);

        if(tRefType == 1){//ภายใน
            $('#oetDBRRefIntDoc').val(tRefDocNo);
            $('.xWShowRefInt').show();
            $('.xWShowRefExt').hide();
        }else{ //ภายนอก
            $('#oetDBRRefDocNo').val(tRefDocNo);
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }

        $('#oetDBRRefKey').val(tRefKey);
        $('#oetDBRRefDocNoOld').val(tRefDocNo);
        $('#odvDBRModalAddDocRef').modal('show');
    });
    
</script>
    