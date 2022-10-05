<?php 
    $nCurrentPage = '1';
?>
<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?=$nCurrentPage?>">
        <div class="table-responsive">
            <table id="otbCldUserDataList" class="table table-striped"> <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <th nowarp class="text-center xCNTextBold" >สาขา</th>
                        <th nowarp class="text-center xCNTextBold" style="width : 9%">เลขที่เอกสาร</th>
                        <th nowarp class="text-center xCNTextBold" style="width : 9%">วันที่เอกสาร</th>
                        <th nowarp class="text-center xCNTextBold">รหัสสินค้า</th>
                        <th nowarp class="text-center xCNTextBold">ชื่อสินค้า</th>
                        <th nowarp class="text-center xCNTextBold">หน่วยสินค้า</th>
                        <th nowarp class="text-right xCNTextBold" style="width : 8%">จำนวน</th>
                        <th nowarp class="text-right xCNTextBold" style="width : 8%">ราคา</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aResult['rtCode'] == 1 ):?>
                        <?php foreach($aResult['raItems'] AS $nKey => $aValue):?>
                            <tr class="text-center xCNTextDetail2">
                                <td nowarp class="text-left"><?=$aValue['FTBchName']?></td>
                                <td nowarp class="text-left"><?=$aValue['FTXshDocNo']?></td>
                                <td nowarp class="text-center"><?=$aValue['FDXshDocDate']?> <?=$aValue['rtTime']?></td>
                                <td nowarp class="text-left"><?=$aValue['FTPdtCode']?></td>
                                <td nowarp class="text-left"><?=$aValue['FTXsdPdtName']?></td>
                                <td nowarp class="text-left"><?=$aValue['FTPunName']?></td>
                                <td nowarp class="text-right"><?=number_format($aValue['FCXsdQty'],2)?></td>
                                <td nowarp class="text-right"><?=number_format($aValue['FCXsdNetAfHD'],2)?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='10'><?= language('service/car/car','tCARNoData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>