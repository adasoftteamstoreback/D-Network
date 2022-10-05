<div class="col-lg-12">
    <div class="table-responsive">
        <table id="otbCLMStep3TableHistory" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ลำดับ')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวน')?></th>
                    <?php 
                         switch ($tTypePage) {
                            case 'historysave':  //ประวัติการบันทึก ?>
                                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','วันที่บันทึกผลเคลม')?></th>
                                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','เลขที่ใบส่งของเคลม')?></th>
                                <?php break;
                            case 'historyget':   //ประวัติการรับเข้า ?>
                                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','วันที่รับเข้าสินค้า')?></th>
                                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','อ้างอิงใบรับเข้า')?></th>
                                <?php break;
                            default:
                                break;
                        }
                    ?>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','เลขที่อ้างอิงผลเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ผลเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ส่วนลดการเคลม')?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($aDataList['rtCode'] == 1 ):?>
                <?php 
                    if(FCNnHSizeOf($aDataList['raItems'])!=0){
                        foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                            <tr>
                                <td nowrap style="text-align:center"><?=$nKey+1?></td>
                                <?php 
                                    switch ($tTypePage) {
                                        case 'historysave': //ประวัติการบันทึก ?>
                                                 <?php 
                                                    if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){ //เคลมไม่ได้
                                                        $tTextPDT = $aDataTableVal['Step3_PDTName_Wrn'] . '<br>' . '<p style="color:red; font-size: 15px !important;">เคลมไม่ได้</p>';
                                                    }else{
                                                        $tTextPDT = $aDataTableVal['Step3_PDTName_Wrn'];
                                                    } 
                                                ?>
                                                <td nowrap><?=$tTextPDT;?></td>
                                                <td class="text-right" nowrap><?=number_format($aDataTableVal['FCWrnPdtQty'],2);?></td>
                                                <td class="text-center" nowrap><?=date('d/m/Y',strtotime($aDataTableVal['FDWrnDate']));?></td>
                                                <td class="text-left" nowrap><?=($aDataTableVal['FTPcdRefTwo'] == '' ) ? '-' : $aDataTableVal['FTPcdRefTwo'];?></td>
                                            <?php break;
                                        case 'historyget':  //ประวัติการรับเข้า ?>
                                                <?php 
                                                    if($aDataTableVal['Step3_PDTName_Rcv'] == '' || $aDataTableVal['Step3_PDTName_Rcv'] == null){
                                                        if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){ //เคลมไม่ได้
                                                            $tTextName = $aDataTableVal['Step3_PDTName_Wrn'] . '<br>' . '<p style="color:red; font-size: 15px !important;">เคลมไม่ได้</p>';
                                                        }else{
                                                            $tTextName = $aDataTableVal['Step3_PDTName_Wrn'];
                                                        }
                                                    }else{
                                                        if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){ //เคลมไม่ได้
                                                            $tTextName = $aDataTableVal['Step3_PDTName_Rcv'] . '<br>' . '<p style="color:red; font-size: 15px !important;">เคลมไม่ได้</p>';
                                                        }else{
                                                            $tTextName = $aDataTableVal['Step3_PDTName_Rcv'];
                                                        }
                                                    }
                                                ?>
                                                <td nowrap><?=$tTextName;?></td>
                                                <td class="text-right" nowrap><?=number_format($aDataTableVal['FCRcvPdtQty'],2);?></td>
                                                <td class="text-center" nowrap><?=date('d/m/Y',strtotime($aDataTableVal['FDRcvDate']));?></td>
                                                <td class="text-left" nowrap><?=($aDataTableVal['FTRcvRefTwi'] == '' ) ? '-' : $aDataTableVal['FTRcvRefTwi'];?></td>
                                            <?php break;
                                        default:
                                            break;
                                    }
                                ?>
                                <td class="text-left" nowrap><?=($aDataTableVal['FTWrnRefDoc'] == '' ) ? '-' : $aDataTableVal['FTWrnRefDoc'];?></td>

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
                                    if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){ //เคลมไม่ได้
                                        $tTextDNCN = '0.00';
                                    }else{
                                        $tTextDNCN = number_format($aDataTableVal['FCWrnDNCNAmt'],2);
                                    } 
                                ?>
                                <td class="text-right" nowrap><?=$tTextDNCN;?></td>
                            </tr>
                        <?php endforeach; ?>
                <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>