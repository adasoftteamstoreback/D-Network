
<div class="table-responsive">
    <table id="" class="table table-striped">
        <thead>
            <tr class="xCNCenter">
                <th class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','หน่วย')?></th>
                <th class="xCNTextBold" style="width:10%;"><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','สถานะเคลมภายใน')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','หมายเหตุ')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','แจ้งเคลมผู้จำหน่าย')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','วันที่แจ้ง')?></th>
                <th class="xCNTextBold"><?=language('document/invoice/invoice','สินค้าที่เปลี่ยน / เบิก')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($aDataList['rtCode'] == 1 ):?>
            <?php 
                if(FCNnHSizeOf($aDataList['raItems'])!=0){
                    foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                        <?php $nKey = $aDataTableVal['FNPcdSeqNo']; ?>
                        <tr>
                            <td style="text-align:center"><?=$nKey?></td>
                            <td><?=$aDataTableVal['FTPdtCode'];?></td>
                            <td><?=$aDataTableVal['FTPcdPdtName'];?></td>
                            <td><?=$aDataTableVal['FTPcdBarCode'];?></td>
                            <td><?=$aDataTableVal['FTPunName'];?></td>
                            <td class="text-right"><?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?></td>
                            <td><?=($aDataTableVal['FTPcdStaClaim'] == 1) ? 'อนุมัติ' : 'ไม่อนุมัติ';?></td>
                            <td><?=($aDataTableVal['FTPcdRmk'] == '') ? '-' : $aDataTableVal['FTPcdRmk'];?></td>
                            <td><?=($aDataTableVal['FTSplName'] == '') ? '-' : $aDataTableVal['FTSplName'];?></td>
                            <td class="text-center"><?=date('d/m/Y',strtotime($aDataTableVal['FDPcdDateReq']))?></td>
                            <td><?=($aDataTableVal['Pick_PDTName'] == '') ? '-' : $aDataTableVal['Pick_PDTName'];?></td>
                        </tr>
                    <?php endforeach; ?>
            <?php } ?>
            <?php else:?>
                <tr><td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>
