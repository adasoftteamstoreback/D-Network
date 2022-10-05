<div class="table-responsive" style="max-height: 260px;">
    <table class="table table-striped">
        <thead>
            <tr class="xCNCenter">
                <th class="xCNTextBold" style="width:15%;"><?=language('document/taxinvoice/taxinvoice','tTAXTitleRefType')?></th>
                <th class="xCNTextBold"><?=language('document/taxinvoice/taxinvoice','tTAXTitleRefDocNo')?></th>
                <th class="xCNTextBold" style="width:15%;"><?=language('document/taxinvoice/taxinvoice','tTAXTitleRefDocDate')?></th>
                <th class="xCNTextBold" >ค่าอ้างอิง</th>
                <th class="xCNTextBold xCNHideWhenCancelOrApprove" >ลบ</th>
            </tr>
        </thead>
        <tbody>
            <?php if( $tCode == '1' ){ ?>
                <?php foreach($aItems as $aValue){ 
                    ?>
                    <tr class="xCNTextDetail2">
                        <td class="text-left"><label><?=language('document/taxinvoice/taxinvoice','tTAXRefType'.$aValue['FTXshRefType'])?></label></td>
                        
                        <?php switch ($aValue['FTXshRefKey']) {
                            case "ABB":
                        ?>
                        <td class="text-left" onClick="JSvBOOKJumptoABBPage('<?= $aValue['FTXshRefDocNo'] ?>','TAX')"><label style="cursor: pointer;color : #1866ae !important; text-decoration: underline;"><?=$aValue['FTXshRefDocNo']?></label></td>

                        <?php break;
                            default:
                        ?>
                        <td class="text-left"><label><?=$aValue['FTXshRefDocNo']?></label></td>
                        <?php break;
                        }
                        ?>
                        
                        
                        <td class="text-center"><label><?=date_format(date_create($aValue['FDXshRefDocDate']),'Y-m-d')?></label></td>
                        <td nowrap class="text-left">
                            <?php if( $aValue['FTXshRefType'] != '' ){ echo $aValue['FTXshRefKey']; }else{ echo "-"; } ?>
                        </td>
                        <td nowrap class="text-center xCNHideWhenCancelOrApprove">
                            <img class="xCNIconTable xCNIconDel xWDelDocRef" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                        </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr class="xCNTextDetail2">
                <td class="text-center" colspan="100%"><label><?=language('common/main/main','tCMNNotFoundData')?></label></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>

// Jump ไปหน้าเลขที่ใบขาย
function JSvBOOKJumptoABBPage(ptSODocNo, ptType){
    
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
                        'tBackTo'   : $("#oetTAXDocNo").val(),
                        'tBchcode' : $("#ohdBCHDocument").val()
                    },
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

</script>