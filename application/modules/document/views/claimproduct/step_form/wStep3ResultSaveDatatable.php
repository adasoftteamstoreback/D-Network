
<div class="table-responsive">
    <table id="otbCLMStep3TableCNDN" class="table table-striped">
        <thead>
            <tr class="xCNCenter">
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ลำดับ')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                <th nowrap class="xCNTextBold" style="width:120px;"><?=language('document/invoice/invoice','ผลเคลม')?></th>
                <th nowrap class="xCNTextBold" style="width:120px;"><?=language('document/invoice/invoice','ส่วนลดการเคลม')?></th>
                <th nowrap class="xCNTextBold" style="width:120px;"><?=language('document/invoice/invoice','จำนวน')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','หมายเหตุ')?></th>
                <th nowrap class="xCNTextBold" style="width:60px;"><?=language('document/invoice/invoice','ลบ')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($aDataList['rtCode'] == 1 ):?>
            <?php 
                if(FCNnHSizeOf($aDataList['raItems'])!=0){
                    $nSeq = 1;
                    foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                        <?php $nKey = $aDataTableVal['FNWrnSeq']; ?>
                        <tr
                            data-key="<?=$nKey?>"
                            data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>"
                            data-seqno="<?=$nKey?>" >
                            <td nowrap style="text-align:center"><?=$nSeq++?></td>
                            <?php 
                                if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){
                                    $tTextPDT = $aDataTableVal['PDTName'] . '<br>' . '<p style="color:red; font-size: 15px !important;">เคลมไม่ได้</p>';
                                }else{
                                    $tTextPDT = $aDataTableVal['PDTName'];
                                } 
                            ?>
                            <td nowrap><?=$tTextPDT;?></td>

                            <?php 
                                $tTextDNResultClaim  = 'N/A';
                                if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){
                                    $tTextDNResultClaim = 'เคลมไม่ได้';
                                }else{
                                    if($aDataTableVal['FCWrnPercent'] == 100){
                                        $tTextDNResultClaim = 'เปลี่ยนสินค้าใหม่';
                                    }else{
                                        $tTextDNResultClaim = 'ชดเชยมูลค่า';
                                    }
                                }
                                //ของเดิม
                                // number_format($aDataTableVal['FCWrnPercent'],2);
                            ?>
                            <td class="text-left" nowrap><?=$tTextDNResultClaim;?></td>

                            <?php 
                                if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){
                                    $tTextDNCN = '0.00';
                                }else{
                                    $tTextDNCN = number_format($aDataTableVal['FCWrnDNCNAmt'],2);
                                } 
                            ?>
                            <td class="text-right" nowrap><?=$tTextDNCN;?></td>
                            <td class="text-right" nowrap><?=number_format($aDataTableVal['FCWrnPdtQty'],2);?></td>
                            <td class="text-left" nowrap><?=($aDataTableVal['FTWrnRmk'] == '' ) ? '-' : $aDataTableVal['FTWrnRmk'];?></td>
                            <td class="text-center" >
                                <img
                                    class="xCNIconTable xCNIconDel"
                                    src="<?=base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                    title="Remove" onclick="JSxRemoveDTStep3Row(this)"
                                >
                            </td>
                        </tr>
                    <?php endforeach; ?>
            <?php } ?>
            <?php else:?>
                <tr><td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>

<script>

    //ลบคอลัมน์ใน Temp
    function JSxRemoveDTStep3Row(ele) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal    = $(ele).parent().parent().attr("data-pdtcode");
            var tSeqno  = $(ele).parent().parent().attr("data-seqno");
            JSxCLMRemoveDTStep3Temp(tSeqno, tVal, ele);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบข้อมูล
    function JSxCLMRemoveDTStep3Temp(pnSeqNo,ptPDTCode,elem){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            $.ajax({
                type    : "POST",
                url     : "docClaimStep3Delete",
                data    : {
                    'tCLMDocNo'  : $("#ohdCLMDocNo").val(),
                    'nSeqNo'     : pnSeqNo,
                    'tPDTCode'   : ptPDTCode
                },
                cache   : false,
                timeout : 0,
                success: function (oResult) {
                    //โหลดหน้าจอใหม่
                    JSxLoadTableStep3Save();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }
</script>