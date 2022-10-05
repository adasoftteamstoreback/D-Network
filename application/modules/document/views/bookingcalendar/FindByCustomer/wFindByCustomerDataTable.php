<div class="row" style="margin-top:10px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <?php 
                if($tTypeCondition == 5){ 
                    $tClassStriped = '';
                }else{
                    $tClassStriped = 'table-striped';
                }
            ?>
            <table class="table <?=$tClassStriped?>">
                <thead>
                    <tr class="xCNCenter">

                        <?php if($tTypeCondition == 5){ // case : 5 ตรวจสอบสินค้ารอซื้อเพื่อการนัดหมาย ?>
                            <th class="xCNTextBold" style="width:5%;"><?=language('common/main/main', 'tModalAdvNo') ?></th>
                            <th class="xCNTextBold"><?=language('common/main/main', 'tCenterModalPDTBranch') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKBookingNumber') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKDateBook') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKTimeBook') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKCstCode') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKCustomer') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKTelephone') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'tCARProductNo') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'tCARProductName') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKTBPDT_Qty') ?></th>
                            <th class="xCNTextBold"><?=language('common/main/main', 'tModalPriceUnit') ?></th>
                        <?php }else if($tTypeCondition == 1){ ?>
                            <th class="xCNTextBold" style="width:6%;"><?=language('common/main/main', 'สาขาที่ใช้') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'วันที่ใช้บริการ') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'tCSTName') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'tCSTTel') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'tCARRegNo') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'tCARBrand') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'tCARModel') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'ที่อยู่') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'tCSTEmail') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'BlueCard') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'บริการที่ใช้/สินค้าที่ซื้อ') ?></th>
                            <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'จำนวนเงิน') ?></th>
                            <th class="xCNTextBold" style="width:6%;">ประเภทการชำระ</th>
                            <th class="xCNTextBold" style="width:6%;"><?=language('document/bookingcalendar/bookingcalendar', 'บริการครั้งที่แล้ว') ?></th>
                            <th class="xCNTextBold" style="width:6%;"><?=language('document/bookingcalendar/bookingcalendar', 'tQuestionnaire') ?></th>
                        <?php }else{ // case : 2 - 4?>
                            <th class="xCNTextBold" style="width:10%;"><?=language('common/main/main', 'tModalAdvNo') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'tCSTCode') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'tCSTName') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'tCSTTel') ?></th>
                            <th class="xCNTextBold"><?=language('customer/customer/customer', 'tCSTEmail') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'tCARBrand') ?></th>
                            <th class="xCNTextBold"><?=language('service/car/car', 'tCARRegNo') ?></th>
                        
                            <?php if($tTypeCondition == 2){ //ค้นหาลูกค้าเพื่อยืนยันนัดหมาย ?> 
                                <th class="xCNTextBold" style="width:13%;"><?=language('document/bookingcalendar/bookingcalendar', 'tDateIN') ?></th>
                                <th class="xCNTextBold" style="width:13%;"><?=language('document/bookingcalendar/bookingcalendar', 'วันที่ต้องเข้าใช้บริการครั้งถัดไป') ?></th>
                                <th class="xCNTextBold" style="width:10%;"><?=language('document/bookingcalendar/bookingcalendar', 'tBookingDate') ?></th>
                            <?php }else if($tTypeCondition == 3){ //ลูกค้าที่ยังไม่ได้ยืนยันการนัดหมาย ?>
                                <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKDatTimeBooking') ?></th>
                                <th class="xCNTextBold" style="width:10%;"><?=language('document/bookingcalendar/bookingcalendar', 'tPreview') ?></th>
                            <?php }else if($tTypeCondition == 4){ //ค้นหาลูกค้าที่เลยนัดหมาย ?>
                                <th class="xCNTextBold">รหัสเอกสารการนัดหมาย</th>
                                <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKDatTimeBooking') ?></th>
                                <th class="xCNTextBold">สถานะแจ้งเตือน</th>
                                <th class="xCNTextBold">สถานะเอกสาร</th>
                                <th class="xCNTextBold">สถานะสินค้า</th>
                                <th class="xCNTextBold" style="width:5%;"><?=language('document/bookingcalendar/bookingcalendar', 'tPreview') ?></th>
                            <?php }else if($tTypeCondition == 6){ //ค้นหาเอกสารแจ้งเตือนก่อนถึงวันนัด ?>
                                <th class="xCNTextBold"><?=language('document/bookingcalendar/bookingcalendar', 'tBookingDate') ?></th>
                                <th class="xCNTextBold" style="width:8%;"><?=language('document/bookingcalendar/bookingcalendar', 'แจ้งเตือนแล้ว') ?></th>
                                <th class="xCNTextBold" style="width:10%;"><?=language('document/bookingcalendar/bookingcalendar', 'tPreview') ?></th>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $nSeq = 1; ?>
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aDataList['raItems'] as $nKey => $aValue) : ?>
                            <?php 
                                $aReturn = array(
                                    'FTAgnCode'     => trim($aValue['FTAgnCode']),
                                    'FTBchCode'     => $aValue['FTBchCode'],
                                    'FTCarCode'     => $aValue['FTCarCode'], 
                                    'FTCstCode'     => $aValue['FTCstCode'],
                                    'FTCstName'     => $aValue['FTCstName'],
                                    'FTCstTel'      => $aValue['FTCstTel'],
                                    'FTCstEmail'    => $aValue['FTCstEmail'],
                                    'FTCarRegNo'    => $aValue['FTCarRegNo'],
                                    'BOOKID'        => $aValue['BOOKID']
                                );
                                $aReturnJS      = json_encode($aReturn);
                                $tEventClick    = 'JSxBackStepToBooking('.$aReturnJS.')';
                            ?>
                            <tr class="text-center xCNTextDetail2">
                                <?php if($tTypeCondition == 5){ // case : 5 ตรวจสอบสินค้ารอซื้อเพื่อการนัดหมาย ?>
                                    <?php if($aValue['PARTITIONBYDOC'] == 1){ ?>
                                        <?php
                                            if($aValue['PARTITIONBYITEM'] != 0){
                                                $tClassRowSpan = "rowspan='".$aValue['PARTITIONBYITEM']."'";
                                            }else{
                                                $tClassRowSpan = "";
                                            }
                                        ?>
                                        <td class="text-center" <?=$tClassRowSpan?> ><?=$nSeq++?></td>
                                        <td class="text-left"   <?=$tClassRowSpan?> ><?=($aValue['FTBchName'] == '') ? '-' : $aValue['FTBchName']?></td>
                                        <td class="text-left"   <?=$tClassRowSpan?> ><?=($aValue['FTXshDocNo'] == '') ? '-' : $aValue['FTXshDocNo']?></td>
                                        <td class="text-center" <?=$tClassRowSpan?> ><?=($aValue['DateBooking'] == '') ? '-' : $aValue['DateBooking']?></td>
                                        <td class="text-center" <?=$tClassRowSpan?> ><?=$aValue['TimeStart']?> - <?=$aValue['TimeEnd']?></td>
                                        <td class="text-left"   <?=$tClassRowSpan?> ><?=($aValue['FTCstCode'] == '') ? '-' : $aValue['FTCstCode']?></td>
                                        <td class="text-left"   <?=$tClassRowSpan?> ><?=($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']?></td>
                                        <td class="text-left"   <?=$tClassRowSpan?> ><?=($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']?></td>
                                    <?php } ?>
                                            
                                    <!--รหัสสินค้า-->
                                    <?php if($aValue['CODESET'] == '' || $aValue['CODESET'] == null){ ?>
                                        <td class="text-left"><?=($aValue['FTPdtCode'] == '') ? '-' : $aValue['FTPdtCode']?></td>
                                    <?php }else{ ?>
                                        <td class="text-left"><?=($aValue['CODESET'] == '') ? '-' : $aValue['CODESET']?></td>
                                    <?php } ?>

                                    <!--ชื่อสินค้า-->
                                    <?php if($aValue['NAMESET'] == '' || $aValue['NAMESET'] == null){ ?>
                                        <td class="text-left"><?=($aValue['FTXsdPdtName'] == '') ? '-' : $aValue['FTXsdPdtName']?></td>
                                    <?php }else{ ?>
                                        <td class="text-left"><?=($aValue['NAMESET'] == '') ? '-' : $aValue['NAMESET']?></td>
                                    <?php } ?>

                                    <!--จำนวน-->
                                    <?php if($aValue['QTYSET'] == '' || $aValue['QTYSET'] == null){ ?>
                                        <td class="text-right"><?=($aValue['FCXsdQty'] == '') ? '-' : number_format($aValue['FCXsdQty'],2)?></td>
                                    <?php }else{ ?>
                                        <td class="text-right"><?=($aValue['QTYSET'] == '') ? '-' : number_format($aValue['QTYSET'],2) ?></td>
                                    <?php } ?>

                                    <!--หน่วย-->
                                    <?php if($aValue['PUNSET'] == '' || $aValue['PUNSET'] == null){ ?>
                                        <td class="text-left"><?=($aValue['FTPunName'] == '') ? '-' : $aValue['FTPunName']?></td>
                                    <?php }else{ ?>
                                        <td class="text-left"><?=($aValue['PUNSET'] == '') ? '-' : $aValue['PUNSET']?></td>
                                    <?php } ?>

                                <?php }else if($tTypeCondition == 1){ ?>
                                    <td class="text-left"><?=$aValue['FTBchName']?></td>
                                    <td class="text-center"><?=$aValue['DateStart']?></td>
                                    <td class="text-left"><?=($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']?></td>
                                    <td class="text-left"><?=($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']?></td>
                                    <td class="text-left"><?=($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']?></td>
                                    <td class="text-left"><?=($aValue['CarBrand'] == '') ? '-' : $aValue['CarBrand']?></td>
                                    <td class="text-left"><?=($aValue['FTCarModel'] == '') ? '-' : $aValue['FTCarModel']?></td>
                                    <td class="text-left"><?=($aValue['FTCstAddress'] == '') ? '-' : $aValue['FTCstAddress']?></td>
                                    <td class="text-left"><?=($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail']?></td>
                                    <td class="text-left"><?=($aValue['FTTxnCrdCode'] == '') ? '-' : $aValue['FTTxnCrdCode']?></td>
                                    
                                    <?php $tDocumentJOBHD = $aValue['FTFlwDocRef']; ?>
                                    <?php 
                                        if($aValue['SCOREPoint'] == ''){
                                            $tIconPreview = base_url() . '/application/modules/common/assets/images/icons/createdoc.png';
                                        }else{
                                            $tIconPreview = base_url() . '/application/modules/common/assets/images/icons/view2.png';
                                        } 
                                    ?>
                                    <td class="text-left"><?=($aValue['FTXshRefDocNo'] == '') ? '-' : $aValue['FTXshRefDocNo']; ?></td>
                                    <?php $nOptDecimalShow = get_cookie('tOptDecimalShow');?>
                                    <td class="text-right"><?=($aValue['FCXshGrand'] == '') ? '-' : number_format($aValue['FCXshGrand'],$nOptDecimalShow); ?></td>
                                    <td class="text-left"><?=($aValue['FTRcvName'] == '') ? '-' : $aValue['FTRcvName']; ?></td>
                                    <td class="text-center"><?=($aValue['LastService'] == '') ? '-' : $aValue['LastService']; ?></td>
                                    <td class="text-center">
                                        <img class="xCNIconTable" style="width: 13px;" src="<?=$tIconPreview?>" 
                                        onClick="JSxGotoPageSatisfactionSurvey('<?=$tDocumentJOBHD?>')">
                                    </td>
                                <?php }else{ // case :2-4 ?>
                                    <td class="text-center"><?=$aValue['FNRowID']?></td>
                                    <td class="text-left"><?=($aValue['FTCstCode'] == '') ? '-' : $aValue['FTCstCode']?></td>
                                    <td class="text-left"><?=($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']?></td>
                                    <td class="text-left"><?=($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']?></td>
                                    <td class="text-left"><?=($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail']?></td>
                                    <td class="text-left"><?=($aValue['CarBrand'] == '') ? '-' : $aValue['CarBrand']?></td>
                                    <td class="text-left"><?=($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']?></td>
                                    

                                    <?php if($tTypeCondition == 2){ //เพื่อนัดหมายเข้ารับบริการ ?> 
                                        <td class="text-center"><?=$aValue['DateStart']?></td>
                                        <?php 
                                            if($aValue['DateForcate'] == '' || $aValue['DateForcate'] == null){
                                                $dDateForcate = 'ไม่ได้ระบุข้อมูล';
                                            }else{
                                                $dDateForcate = $aValue['DateForcate'];
                                            } 
                                        ?>
                                        <td class="text-center"><?=$dDateForcate?></td>
                                        <td class="text-center">
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>" 
                                            onClick='<?=$tEventClick?>'>
                                        </td>
                                    <?php }else if($tTypeCondition == 3){ //ลูกค้าที่ยังไม่ได้ยืนยันการนัดหมาย ?>
                                        <td class="text-center"><?=$aValue['DateStart']?></td>
                                        <td class="text-center">
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>" 
                                            onClick="JSxGoToPageBookingDetail('<?= $aValue['BOOKID'] ?>')">
                                        </td>
                                    <?php }else if($tTypeCondition == 4){ //ค้นหาลูกค้าที่ไม่มาตามนัด/ยังไม่ถึงกำหนด ?>
                                        <td class="text-center"><?=$aValue['BOOKID']?></td>
                                        <td class="text-center"><?=$aValue['DateStart']?></td>
                                        <td class="text-left"><?php
                                            if($aValue['FNXshQtyNotiPrev'] == 0 || $aValue['FNXshQtyNotiPrev'] == null){
                                                echo 'ไม่ต้องแจ้งเตือน';
                                            }else if($aValue['FNXshQtyNotiPrev'] < 0){
                                                echo 'แจ้งเตือนแล้ว';
                                            }else{
                                                echo 'ยังไม่แจ้งเตือน';
                                            }?>
                                        </td>
                                        <?php 
                                        if($aValue['FDXshBookDate'] == date('Y-m-d 00:00:00.000')){
                                            $tTextStaDoc = 'กำลังดำเนินการ';
                                        }else if($aValue['FDXshBookDate'] < date('Y-m-d H:i:s')){
                                            $tTextStaDoc = 'เลยกำหนด';
                                        }else{
                                            $tTextStaDoc = 'ยังไม่ถึงกำหนด';
                                        } ?>
                                        <td class="text-left"><?=$tTextStaDoc?></td>

                                        <?php if($aValue['FTXshStaPrcDoc'] == 2){
                                            $tTextStaPrcDoc = 'จองสต๊อกครบแล้ว';
                                        }else if($aValue['FTXshStaPrcDoc'] == 1){
                                            $tTextStaPrcDoc = 'จองบางส่วน';
                                        }else{
                                            $tTextStaPrcDoc = 'ไม่ต้องตรวจสอบสต๊อก';
                                        } ?>
                                        <td class="text-left"><?=$tTextStaPrcDoc?></td>
                                        <td class="text-center">
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>" 
                                            onClick="JSxGoToPageBookingDetail('<?= $aValue['BOOKID'] ?>')">
                                        </td>
                                    <?php }else if($tTypeCondition == 6){ //ค้นหาเอกสารแจ้งเตือนก่อนถึงวันนัด?>
                                        <td class="text-center"><?=$aValue['DateStart']?></td>
                                        <td class="text-center">
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?=$nKey?>" type="checkbox" onclick="JSxUpdateConfirmByTelDone('<?= $aValue['BOOKID'] ?>')">
                                                <span class="">&nbsp;</span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>" 
                                            onClick="JSxGoToPageBookingDetail('<?= $aValue['BOOKID'] ?>')">
                                        </td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= (int)$aDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= (int)$aDataList['rnCurrentPage'] ?> / <?= (int)$aDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageFindCst btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvFindCstClickPage('previous')" class="btn btn-white btn-sm" <?= $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for ($i = max((int)$nPage - 2, 1); $i <= max(0, min((int)$aDataList['rnAllPage'], (int)$nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSvFindCstClickPage('<?= $i ?>')" type="button" class="btn xCNBTNNumPagenation <?= $tActive ?>" <?= $tDisPageNumber ?>><?= $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvFindCstClickPage('next')" class="btn btn-white btn-sm" <?= $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<?php if($tTypeCondition == 5){ // case : 5 ตรวจสอบสินค้ารอซื้อเพื่อการนัดหมาย  ?>
    <!-- <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9"></div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="panel panel-default">
                <div class="panel-heading"  style="padding: 10px 10px 10px 30px !important">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mark-font"><label style="font-weight:bold"><?=language('document/bookingcalendar/bookingcalendar', 'tBKPDTWait') ?></label></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mark-font"><label style="font-weight: bold; float: right; margin-right: 15px;"><?=(@$aDataList['raItems'][0]['COUNTITEMALL'] == '') ? '0' : @$aDataList['raItems'][0]['COUNTITEMALL']?> รายการ</label></div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
<?php } ?>

<script>

    $("#oliBookingByCusTab").unbind().click(function(){
        $("#obtSubmitInvExpExcel").removeClass("xCNHide");
    });
    $("#oliBookingByDayTab").unbind().click(function(){
        $("#obtSubmitInvExpExcel").addClass("xCNHide");
    });
    //รายละเอียดของการจอง
    function JSxGoToPageBookingDetail(ptBookingID){
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarGetDetailBooking",
            data    : { 'ptBookingID' : ptBookingID },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                var oResult         = JSON.parse(tResult);

                var tDateStart      = oResult['raItems'][0]['FDXshTimeStart'];
                var tDateEnd        = oResult['raItems'][0]['FDXshTimeStop'];
                var tDocCode        = oResult['raItems'][0]['FTXshDocNo'];
                var tFKBayService   = {
                                'id' 		: oResult['raItems'][0]['FTXshToPos'] , 
                                'name' 		: oResult['raItems'][0]['FTSpsName'] , 
                                'adcode' 	: oResult['raItems'][0]['FTAgnCode'] , 
                                'bchcode'   : oResult['raItems'][0]['FTBchCode'] };

                JSxPopupBookingCalendar(tDateStart,tDateEnd,tFKBayService,ptBookingID,'List');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดเพื่อที่ไปทำแบบสอบถาม
    function JSxGotoPageSatisfactionSurvey(ptDocumentJOBHD){
        $.ajax({
            type    : "GET",
            url     : "docSatisfactionSurvey/0/0",
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
                JSxChkTypeAddOrUpdate(ptDocumentJOBHD)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function JSxChkTypeAddOrUpdate(ptDocumentJOBHD) {  
        $.ajax({
            type    : "POST",
            url     : "docSatisfactionSurveyChkTypeAddOrUpdate",
            data    : {'ptDocumentJOBHD' : ptDocumentJOBHD },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                var aDataFinal = JSON.parse(tResult);
                
                localStorage.tCheckBackStage = 'PageBookingCalendar';

                if (aDataFinal.tReturn == 1) {
                    JSvSatSvCallPageEdit(aDataFinal.tAgnCode, aDataFinal.tBchCode, aDataFinal.tDocNo);
                }else{
                    JSvSatSvCallPageAdd2(aDataFinal);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //โทรคอนเฟริมกับลูกค้าแล้ว
    function JSxUpdateConfirmByTelDone(ptBookingID){
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarConfirmByTelDone",
            data    : { 'ptBookingID' : ptBookingID },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                //console.log(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
 
</script>