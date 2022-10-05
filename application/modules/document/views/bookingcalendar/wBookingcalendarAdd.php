<style>
    .xCNBorderLeftInforCar{
        border-left : 1px solid #eaeaea;
    }
    .xCNCalculateTotalFooter{
        text-align  : right;
        display     : block;
    }
    .xCNTabPane{
        padding     : 10px 15px;
    }
    .xCNCheckCalendar{
        float           : right;
        cursor          : pointer;
        color           : #419aff;
        text-decoration : underline;
    }
    .xCNImageCarInCalendar{
        border          : 1px solid #eeeeee;
    }
    .xCNImageCar{    
        width           : 45%;
        margin          : 0px auto;
        display         : block;
        opacity         : 0.2;
    }
    #odvBKRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvBKRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvBKRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    #odvInfoTabRemark .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvInfoTabRemark .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvInfoTabRemark .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>

<?php 
    if(!empty($aItemBookingByID)){
        $tBchCode           = $aItemBookingByID[0]['rtBchCode'];       //รหัสสาขา
        $FTXshDocNo         = $aItemBookingByID[0]['FTXshDocNo'];       //รหัสเอกสาร
        $FTXshCstRef1       = $aItemBookingByID[0]['FTXshCstRef1'];     //รหัสลูกค้า
        $FTCstName          = $aItemBookingByID[0]['FTCstName'];        //ชื่อลูกค้า
        $FTXshCstRef2       = $aItemBookingByID[0]['FTXshCstRef2'];     //รหัสรถ
        $FTCarName          = $aItemBookingByID[0]['FTCarRegNo'];       //ทะเบียนรถ
        $FTXshToPos         = $aItemBookingByID[0]['FTXshToPos'];       //รหัสช่องบริการ
        $FNXshQtyNotiPrev   = $aItemBookingByID[0]['FNXshQtyNotiPrev']; //เเจ้งเตือนก่อนวันนัด
        $FTXshStaDoc        = $aItemBookingByID[0]['FTXshStaDoc'];      //สถานะเอกสาร 1:สมบูรณ์, 3:ยกเลิก
        $FTXshStaApv        = $aItemBookingByID[0]['FTXshStaApv'];      //เอกสาร ว่าง:ยังไม่ยืนยัน(จอง), 1:ยืนยันแล้ว    
        $FTXshTel           = $aItemBookingByID[0]['FTXshTel'];         //เบอร์
        $FTXshEmail         = $aItemBookingByID[0]['FTXshEmail'];       //เมล์
        $FTXshRmk           = $aItemBookingByID[0]['FTXshRmk'];         //เหตุผล
        $FTImgObj           = $aItemBookingByID[0]['FTImgObj'];         //รูปรถ
        $FTXshVATInOrEx     = $aItemBookingByID[0]['FTXshVATInOrEx'];   //ประเภทภาษี
        $FTXshStaPrcDoc     = $aItemBookingByID[0]['FTXshStaPrcDoc'];   //สถานะประมวลผล

        //ข้อมูลรถ
        $tCarEngineNo       = $aItemBookingByID[0]['rtCarEngineNo'];
        $tCarPowerNumber    = $aItemBookingByID[0]['rtCarPowerNumber'];
        $tCarTypeName       = $aItemBookingByID[0]['rtCarTypeName'];
        $tCarBrandName      = $aItemBookingByID[0]['rtCarBrandName'];
        $tCarModelName      = $aItemBookingByID[0]['rtCarModelName'];
        $tCarColorName      = $aItemBookingByID[0]['rtCarColorName'];
        $tCarGearName       = $aItemBookingByID[0]['rtCarGearName'];
        $tCarPowerTypeName  = $aItemBookingByID[0]['rtCarPowerTypeName'];
        $tCarEngineSizeName = $aItemBookingByID[0]['rtCarEngineSizeName'];
        $tCarCategoryName   = $aItemBookingByID[0]['rtCarCategoryName'];
        $tCarDateOutCar     = $aItemBookingByID[0]['rtCarDateOutCar'];
        $tCarDate           = $aItemBookingByID[0]['rtCarDate'];
        $tRSNCodeBooking    = $aItemBookingByID[0]['rtRsnCode'];
        $tRSNNameBooking    = $aItemBookingByID[0]['rtRsnName'];
    }else{
        $FNXshQtyNotiPrev   = 0;
    }
?>

<!--Overlay-->
<div class='xCNOverlayBooking' style="height: 80px; background: #FFF;">
    <img src='<?php echo base_url()?>application/modules/common/assets/images/ada.loading.gif' class='xWImgLoading' style="top:20%;">
</div>

<div class="xCNContentBookingAll row" style="display:none;">

    <!--เลขที่เอกสาร-->
    <input type="hidden" name="ohdNameDocumentBooking" id="ohdNameDocumentBooking" value="<?=@$FTXshDocNo?>" >

    <!--ข้อมูลรถ-->
    <div class="col-lg-6"> 
        <div class="row">
            <div class="col-lg-12">
                <!--ชื่อลูกค้า-->
                <div class="form-group" style=" margin-bottom: 5px;">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/bookingcalendar/bookingcalendar', 'tBKCustomer') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetBKCusCode" name="oetBKCusCode" value="<?=@$FTXshCstRef1?>">
                        <input type="text" class="form-control" id="oetBKCusName" name="oetBKCusName" readonly value="<?=@$FTCstName?>">
                        <span class="input-group-btn">
                            <button id="obtBKBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>

                <!--เลือกรถที่ต้องการนัดหมาย-->
                <div class="form-group" style=" margin-bottom: 5px;">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/bookingcalendar/bookingcalendar', 'tBKChooseCar') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetBKCarCode" name="oetBKCarCode" value="<?=@$FTXshCstRef2?>">
                        <input type="text" class="form-control" id="oetBKCarName" name="oetBKCarName" readonly value="<?=@$FTCarName?>">
                        <span class="input-group-btn">
                            <button id="obtBKBrowseCar" type="button" class="btn xCNBtnBrowseAddOn" <?=(@$FTXshDocNo == '') ? 'disabled' : ''?>>
                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <!--เบอร์ติดต่อ-->
                <div class="form-group" style=" margin-bottom: 5px;">
                    <label class="xCNLabelFrm">
                        <?= language('document/bookingcalendar/bookingcalendar','tBKTelephone')?>
                    </label>
                    <input class="form-control xCNInputNumericWithoutDecimal" name="oetBKTelephone" id="oetBKTelephone" maxlength="20"  type="text" value="<?=@$FTXshTel?>" autocomplete="off" 
                    placeholder="<?= language('document/bookingcalendar/bookingcalendar','tBKTelephone')?>" >
                </div>
            </div>

            <div class="col-lg-6">
                <!--อีเมล-->
                <div class="form-group">
                    <label class="xCNLabelFrm">
                        <?= language('document/bookingcalendar/bookingcalendar','tBKEmail')?>
                    </label>
                    <input class="form-control xCNInputWithoutSpcNotThai" name="oetBKEmail" id="oetBKEmail" maxlength="40"  type="text" value="<?=@$FTXshEmail?>" autocomplete="off" 
                    placeholder="<?= language('document/bookingcalendar/bookingcalendar','tBKEmail')?>" >
                </div>
            </div>
        </div>
    </div>

    <!--รูปภาพรถ-->
    <div class="col-lg-6">
        <div class="xCNImageCarInCalendar">
            <?php 
                if(@$FTImgObj == '' || @$FTImgObj == null){
                    $tImgPathCar = base_url().'/application/modules/common/assets/images/logo/fitauto.jpg';
                    $oStyle      = "";
                    echo "<img class='xCNImageCar' src=".$tImgPathCar.">";
                }else{
                    $tImgPathCar = $FTImgObj;
                    $oStyle      = "opacity:1;";
                    if(substr($tImgPathCar,0,1) == '#'){ //ถ้าเป็นสี
                        echo "<div class='text-center xCNImageCarTypeColor'>";
                        echo "<span style='margin-top: 8px;height:170px;width:400px;background-color:".$tImgPathCar.";display:inline-block;line-height:2.3;'></span>";
                        echo "</div>";
                    }else{
                        echo "<img class='xCNImageCar' style=".$oStyle." src=".$tImgPathCar.">";
                    }
                }
            ?>
        </div>
    </div>

    <!--เส้นคั่น-->
    <div class="col-lg-12"><hr style=" margin-bottom: 5px;"></div>

    <!--ตารางข้อมูล-->
    <div class="col-lg-12">
        <div class="row">

            <!--ตารางสินค้า-->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 5px;">
                <div class="custom-tabs-line tabs-line-bottom left-aligned">
                    <ul class="nav" role="tablist">
                        <!-- รายการนัดหมาย -->
                        <li class="active" data-typetab="main" style="cursor: pointer;">
                            <a role="tab" data-toggle="tab" data-target="#odvListTabCalendar" aria-expanded="true">
                                <label class="xCNLabelFrm"><?= language('document/bookingcalendar/bookingcalendar', 'tBKListCalendar') ?></label>
                            </a>
                        </li>
                        <!-- ประวัติการเข้าใช้บริการ -->
                        <?php 
                            if(@$FTXshStaApv == 1 || @$FTXshStaDoc == 3 ){ 
                                $tDisabledTab       = "disabled";
                                $tDisabledToggle    = "false";
                                $tCSSDisabled       = "color: #cecece !important; cursor: no-drop;";
                            }else{
                                $tDisabledTab       = "";
                                $tDisabledToggle    = "tab";
                                $tCSSDisabled       = "";
                            }
                        ?>
                        <li data-typetab="sub" style="cursor: pointer;" class="<?=$tDisabledTab?>">
                            <a role="tab" data-toggle="<?=$tDisabledToggle?>" data-target="#odvListTabHistory" aria-expanded="true">
                                <label class="xCNLabelFrm" style="<?=$tCSSDisabled?>"><?= language('document/bookingcalendar/bookingcalendar', 'tBKListHistoryService'); ?></label>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="tab-content">
                                <!-- รายการนัดหมาย -->
                                <div class="tab-pane xCNTabPane active" id="odvListTabCalendar" role="tabpanel" aria-expanded="true">
                                    <div class="row">
                                        <div class="col-lg-3 xCNWahouse">
                                            <div class="form-group xCNHide">

                                                <?php 
                                                    if(@$aWahouseFrm['rtCode'] == 1){
                                                        $tWahCodeFrm = @$aWahouseFrm['raItems']['FTWahCode'];
                                                        $tWahNameFrm = @$aWahouseFrm['raItems']['FTWahName'];
                                                    }else{
                                                        $tWahCodeFrm = $this->session->userdata("tSesUsrWahCode");
                                                        $tWahNameFrm = $this->session->userdata("tSesUsrWahName");
                                                    }   
                                                    
                                                    if(@$aWahouseTo['rtCode'] == 1){
                                                        $tWahCodeTo = @$aWahouseTo['raItems']['FTWahCode'];
                                                        $tWahNameTo = @$aWahouseTo['raItems']['FTWahName'];
                                                    }
                                                ?>

                                                <label class="xCNLabelFrm"><span style="color:red">*</span><?=language('document/bookingcalendar/bookingcalendar','tBKWahouseFrm');?></label>
                                                <div class="input-group" style="width:100%;">
                                                    <input type="text" class="input100 xCNHide" id="ohdBKWahouseFrom" name="ohdBKWahouseFrom" value="<?=@$tWahCodeFrm?>">
                                                    <input class="form-control xWPointerEventNone" type="text" id="oetBKWahouseFromName" name="oetBKWahouseFromName" value="<?=@$tWahNameFrm?>" readonly placeholder="คลังขาย">
                                                    <span class="input-group-btn">
                                                        <button id="obtBKWahouseFrm" type="button" class="btn xCNBtnBrowseAddOn">
                                                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 xCNWahouse">
                                            <div class="form-group xCNHide">
                                                <label class="xCNLabelFrm"><span style="color:red">*</span></span><?=language('document/bookingcalendar/bookingcalendar','tBKWahouseTo');?></label>
                                                <div class="input-group" style="width:100%;">
                                                    <input type="text" class="input100 xCNHide" id="ohdBKWahouseTo" name="ohdBKWahouseTo" value="<?=@$tWahCodeTo?>">
                                                    <input class="form-control xWPointerEventNone" type="text" id="oetBKWahouseToName" name="oetBKWahouseToName" value="<?=@$tWahNameTo?>" readonly placeholder="คลังจอง">
                                                    <span class="input-group-btn">
                                                        <button id="obtBKWahouseTo" type="button" class="btn xCNBtnBrowseAddOn" >
                                                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <button id="obtBKEventAddPDTToTemp" class="xCNBTNPrimeryPlus" type="button" style="margin-top: 25px;">+</button>
                                        </div>

                                        <!--ตารางข้อมูล-->
                                        <div class="col-lg-12">
                                            <div id="odvContentPDTBookingCalendar"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ประวัติการเข้าใช้บริการ -->
                                <div class="tab-pane xCNTabPane" style="margin-top:10px;" id="odvListTabHistory" role="tabpanel" aria-expanded="true">
                                    <div class="row">
                                         <!--ตารางข้อมูล-->
                                        <div class="col-lg-12">

                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <!--วันที่ Dataforcate-->
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','วันที่เข้ารับบริการครั้งถัดไป'); ?></label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control xCNDatePickerforcate"
                                                                type="text"
                                                                id="oetDateforcateFrom"
                                                                name="oetDateforcateFrom"
                                                                autocomplete="off"
                                                                value="<?=date('Y-m-d');?>"
                                                            >
                                                            <span class="input-group-btn" >
                                                                <button id="obtDateCalendarforcateFrom" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <!--วันที่ Dataforcate-->
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','ถึงวันที่'); ?></label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control xCNDatePickerforcate"
                                                                type="text"
                                                                id="oetDateforcateTo"
                                                                name="oetDateforcateTo"
                                                                autocomplete="off"
                                                                value="<?=date('Y-m-d',strtotime(date('Y-m-d') . "+6 month"));?>"
                                                            >
                                                            <span class="input-group-btn" >
                                                                <button id="obtDateCalendarforcateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm" style="width: 100%;">&nbsp;</label>
                                                        <button class="btn xCNBTNPrimery" style="width:40%" onclick="JSxLoadTableHistoryService()"><?php echo language('common/main/main', 'tSearch'); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                         </div>
                                         <div class="col-lg-12">
                                            <div id="odvContentPDTTabHistory"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--ข้อมูลนัดหมาย-->
            <div class="col-lg-7 col-md-6 col-sm-7 col-xs-12" style="margin-top: 5px;">
                <div style="border: 1px solid #eeeeee;">
                    <div class="custom-tabs-line tabs-line-bottom left-aligned">
                        <ul class="nav" role="tablist">
                            <!-- ข้อมูลนัดหมาย -->
                            <li class="active" data-typetab="main" style="cursor: pointer;">
                                <a role="tab" data-toggle="tab" data-target="#odvInfoTabCalendar" aria-expanded="true">
                                    <label class="xCNLabelFrm"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTabCalendar') ?></label>
                                </a>
                            </li>
                            <!-- ข้อมูลรถ -->
                            <li class="" data-typetab="sub" style="cursor: pointer;">
                                <a role="tab" data-toggle="tab" data-target="#odvInfoTabCar" aria-expanded="true">
                                    <label class="xCNLabelFrm"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTabCar'); ?></label>
                                </a>
                            </li>
                            <!-- หมายเหตุ และข้อมูลเพิ่มเติม -->
                            <li class="" data-typetab="sub" style="cursor: pointer;">
                                <a role="tab" data-toggle="tab" data-target="#odvInfoTabRemark" aria-expanded="true">
                                    <label class="xCNLabelFrm"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTabRamark'); ?></label>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="tab-content">
                                <!-- ข้อมูลนัดหมาย -->
                                <?php 
                                    $nStartTime = explode(" ",$nStartTime);
                                    $nEndTime   = explode(" ",$nEndTime);
                                ?>
                                <div class="tab-pane xCNTabPane active" id="odvInfoTabCalendar" role="tabpanel" aria-expanded="true">

                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                                            <?php 
                                                if(!empty($aItemBookingByID)){
                                                    if(@$FTXshStaDoc == 3){ //เอกสารยกเลิก
                                                        $tTextStatus = language('document/bookingcalendar/bookingcalendar','tBKStatusCancle');
                                                        $tClassBlock = 'xCNBookingCancel';
                                                    }else{
                                                        if(@$FTXshStaPrcDoc == '' || @$FTXshStaPrcDoc == '1'){ //นัดหมาย (เหลืองสอง)
                                                            $tTextStatus = language('document/bookingcalendar/bookingcalendar','tBKStatusBookingandconfirm');
                                                            $tClassBlock = 'xCNBookingWaitConfirm';
                                                        }else if($FTXshStaPrcDoc == 2){ //นัดหมายเเละยืนยันเเล้ว (เขียว)
                                                            $tTextStatus = language('document/bookingcalendar/bookingcalendar','tBKStatusConfirm');
                                                            $tClassBlock = 'xCNBookingConfirm';
                                                        }
                                                    }
                                                }else{
                                                    $tTextStatus = language('document/bookingcalendar/bookingcalendar','tBKStatusWaitBooking');
                                                    $tClassBlock = 'xCNBookingWaitConfirm';
                                                }
                                            ?>
                                            <label class="xCNLabelFrm"><?= language('document/bookingcalendar/bookingcalendar','tBKStatus')?> : </label>
                                            <div class="<?=$tClassBlock?>" style="width: 15px; height: 15px; display: inline-block; margin: 0px 5px 0px 15px;"></div>
                                            <label class="<?=$tClassBlock?>_text"> <?=$tTextStatus?></label>
                                        </div>
                                        <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <span class="xCNCheckCalendar" onclick="JSxCheckCalendarAgain()"><?= language('document/bookingcalendar/bookingcalendar','tBKTabRamark_check')?></span>
                                        </div> -->
                                    </div>

                                    <div class="row">
                                                
                                        <?php
                                            if($this->session->userdata('tSesUsrLevel') == 'HQ'){
                                                $tBrowseADDisabled     = '';
                                                $tBrowseBCHDisabled    = '';
                                            }else{
                                                $tBrowseADDisabled     = 'disabled';
                                                $tBrowseBCHDisabled    = 'disabled';
                                            }   

                                            if($aBayService['rtCode'] != 800 ){
                                                $tBCHCodeBooking = $aBayService['raItems'][0]['FTBchCode'];
                                                $tBCHNameBooking = $aBayService['raItems'][0]['FTBchName'];
                                            }else{
                                                $tBCHCodeBooking = $aBranch['raItems'][0]['FTBchCode'];
                                                $tBCHNameBooking = $aBranch['raItems'][0]['FTBchName'];
                                            }
                                        ?>
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label class="xCNLabelFrm">
                                                <?= language('document/invoice/invoice','tIVTitlePanelConditionAD')?>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNHide" id="oetBookLastAgnCode" name="oetBookLastAgnCode" value="<?=$aBayService['raItems'][0]['FTAgnCode']?>">
                                                <input type="text" class="form-control" id="oetBookLastAgnName" name="oetBookLastAgnName" readonly value="<?=$aBayService['raItems'][0]['FTAgnName']?>" placeholder="<?= language('document/invoice/invoice', 'tIVTitlePanelConditionAD'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="oetBookLastBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn" <?=$tBrowseADDisabled?>>
                                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQBranch')?></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNHide" id="oetBookLastBchCode" name="oetBookLastBchCode" value="<?=$tBCHCodeBooking?>">
                                                <input type="text" class="form-control" id="oetBookLastBchName" name="oetBookLastBchName" readonly value="<?=$tBCHNameBooking?>" placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="oetBookLastBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?=$tBrowseBCHDisabled?>>
                                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" style="margin-top: 5px;">

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label class="xCNLabelFrm"><span style="color:red">*</span><?=language('document/bookingcalendar/bookingcalendar', 'tBKBay'); ?></label>
                                            <div class="input-group">
                                                <?php 
                                                    if($aColumn['id'] == '' || $aColumn['id'] == null){
                                                        $tBayCode = $aBayService['raItems'][0]['FTSpsCode'];
                                                        $tBayName = $aBayService['raItems'][0]['FTSpsName'];
                                                    }else{
                                                        $tBayCode = $aColumn['id'];
                                                        $tBayName = $aColumn['name'];
                                                    }
                                                ?>

                                                <input type="text" class="form-control xCNHide" id="oetBookBayCode" name="oetBookBayCode" value="<?=$tBayCode?>">
                                                <input type="text" class="form-control" id="oetBookBayName" name="oetBookBayName" readonly value="<?=$tBayName?>" placeholder="<?= language('document/bookingcalendar/bookingcalendar', 'tBKBay'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="oetBookLastBrowseBay" type="button" class="btn xCNBtnBrowseAddOn">
                                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label class="xCNLabelFrm">
                                                <?= language('document/bookingcalendar/bookingcalendar','tBKTabRamark_Date')?>
                                            </label>
                                            <div> 
                                                <input type="text" class="form-control xCNDatePicker" id="oetBookDate" name="oetBookDate" value="<?=date('d/m/Y', strtotime($nStartTime[0]));?>">
                                                <input type="hidden" class="form-control" id="ohdBookDate" name="ohdBookDate" value="<?=$nStartTime[0];?> ">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" style="margin-top: 5px;">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label class="xCNLabelFrm"><span style="color:red">*</span><?=language('document/bookingcalendar/bookingcalendar','tBKTabRamark_TimeStart')?>
                                            </label>
                                            <input class="xCNTimePicker" autocomplete="off" type="text" id="oetBookTimeStart" name="oetBookTimeStart" value="<?=$nStartTime[1];?>">
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label class="xCNLabelFrm"><span style="color:red">*</span><?=language('document/bookingcalendar/bookingcalendar','tBKTabRamark_TimeEnd')?> (ประมาณ)
                                            </label>
                                            <input class="xCNTimePicker" autocomplete="off" type="text" id="oetBookTimeEnd" name="oetBookTimeEnd" value="<?=$nEndTime[1];?>">
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 5px;">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label class="xCNLabelFrm">
                                                <?= language('document/bookingcalendar/bookingcalendar','tBKTabRamark_Waring')?>
                                            </label>
                                            <input type="text" class="xCNInputNumericWithDecimal" id="oetTabRamarkWaringDay" name="oetTabRamarkWaringDay" maxlength="3" value="<?=@$FNXshQtyNotiPrev?>" placeholder="/ <?= language('document/bookingcalendar/bookingcalendar','tBKTabRamark_Day')?>">
                                        </div>

                                        <?php if(!empty($aItemBookingByID)){ ?>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'เหตุผล')?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control xCNHide" id="oetBookReasonCode" name="oetBookReasonCode" value="<?=$tRSNCodeBooking?>">
                                                    <input type="text" class="form-control" id="oetBookReasonName" name="oetBookReasonName" readonly value="<?=$tRSNNameBooking?>" placeholder="<?= language('document/quotation/quotation', 'เหตุผล'); ?>">
                                                    <span class="input-group-btn">
                                                        <button id="oetBookBrowseReason" type="button" class="btn xCNBtnBrowseAddOn">
                                                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>

                                <!-- หมายเหตุ -->
                                <div class="tab-pane xCNTabPane" id="odvInfoTabRemark" role="tabpanel" aria-expanded="true">
                                
                                    <div class="form-group">
                                        <label class="xCNLabelFrm">
                                            <?= language('document/quotation/quotation','tTQVatInOrEx')?>
                                        </label>
                                        <select class="form-control" id="ocmBKfoVatInOrEx" name="ocmBKfoVatInOrEx" >
                                            <option value="1" <?=(@$FTXshVATInOrEx == '1') ? 'selected' : ''?>><?= language('document/quotation/quotation','tTQVatInclusive');?></option>
                                            <option value="2" <?=(@$FTXshVATInOrEx == '2') ? 'selected' : ''?>><?= language('document/quotation/quotation','tTQVatExclusive');?></option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?=language('document/bookingcalendar/bookingcalendar', 'tBKRamark'); ?></label>
                                        <textarea class="form-control" id="otaBKTabRamark" name="otaBKTabRamark" rows="10" maxlength="200" 
                                        style="resize:none;height:86px;"><?=@$FTXshRmk?></textarea>
                                    </div>

                                    <div class="panel-default" style="border: 1px solid #eeeeee;">
                                        <div class="panel-heading">
                                            <div class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBVatRate');?></div>
                                            <div class="pull-right mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBAmountVat');?></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-group" id="oulBKDataListVat">
                                            </ul>
                                        </div>
                                        <div class="panel-heading">
                                            <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBTotalValVat');?></label>
                                            <label class="pull-right mark-font" id="olbBKVatSum">0.00</label>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                </div>

                                <!-- ข้อมูลรถ -->
                                <div class="tab-pane xCNTabPane" id="odvInfoTabCar" role="tabpanel" aria-expanded="true">
                                    <div class="row">

                                        <!--ทะเบียนรถ-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCARRegNumber') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextRegNumber">: <?=(@$FTCarName == '') ? '-' : @$FTCarName;?></span>
                                        </div>

                                        <!--เลขเครื่องยนต์-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 xCNBorderLeftInforCar">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAREngineno') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextEngineno">: <?=(@$tCarEngineNo == '') ? '-' : @$tCarEngineNo;?></span>
                                        </div>

                                        <!--เลขตัวถัง-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCARPowerno') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextPowerno">: <?=(@$tCarPowerNumber == '') ? '-' : @$tCarPowerNumber;?></span>
                                        </div>

                                        <!--ประเภท/ลักษณะ-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 xCNBorderLeftInforCar">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption8') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption8">: <?=(@$tCarCategoryName == '') ? '-' : @$tCarCategoryName;?></span>
                                        </div>

                                        <!--ประเภท/เจ้าของ-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption1') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption1">: <?=(@$tCarTypeName == '') ? '-' : @$tCarTypeName;?></span>
                                        </div>

                                        <!--ยี่ห้อ-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 xCNBorderLeftInforCar">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption2') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption2">: <?=(@$tCarBrandName == '') ? '-' : @$tCarBrandName;?></span>
                                        </div>

                                        <!--รุ่น-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption3') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption3">: <?=(@$tCarModelName == '') ? '-' : @$tCarModelName;?></span>
                                        </div>

                                        <!--สี-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 xCNBorderLeftInforCar">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption4') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption4">: <?=(@$tCarColorName == '') ? '-' : @$tCarColorName;?></span>
                                        </div>

                                        <!--เกียร์-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption5') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption5">: <?=(@$tCarGearName == '') ? '-' : @$tCarGearName;?></span>
                                        </div>

                                        <!--เครื่องยนต์-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 xCNBorderLeftInforCar">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption6') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption6">: <?=(@$tCarPowerTypeName == '') ? '-' : @$tCarPowerTypeName;?></span>
                                        </div>

                                        <!--ขนาดเครื่องยนต์-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAROption7') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextOption7">: <?=(@$tCarEngineSizeName == '') ? '-' : @$tCarEngineSizeName;?></span>
                                        </div>

                                        <!--วันที่ออกรถ-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 xCNBorderLeftInforCar">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCARStartDate') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextStartDate">: <?=date("d/m/Y",strtotime(@$tCarDateOutCar));?></span>
                                        </div>

                                        <!--วันที่ครอบครอง-->
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                            <label class="xCNLabelFrm"><?=language('service/car/car', 'tCAREndDate') ?></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                                            <span class="xCNTextEndDate">: <?=date("d/m/Y",strtotime(@$tCarDate));?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--สรุปบิล-->
            <div class="col-lg-5 col-md-6 col-sm-5 col-xs-12" style="margin-top: 5px;" id="odvBKRowDataEndOfBill">

                <div class="panel panel-default">
                    <div class="panel-heading mark-font" id="odvBKDataTextBath"></div>
                </div>
                
                <div class="panel-default" style="border: 1px solid #eeeeee;">
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdNet');?></label>
                                <label class="pull-right mark-font xCNPriceSUMByPDT">0.00</label>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdVat');?></label>
                                <label class="pull-right xCNPriceSUMPDT">0.00</label>
                                <input type="hidden" name="ohdBKSumFCXtdVat" id="ohdBKSumFCXtdVat" value="0.00">
                                <div class="clearfix"></div>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-heading">
                        <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBFCXphGrand');?></label>
                        <label class="pull-right mark-font xCNPriceSUMALL">0.00</label>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <p class="xCNTextBookingFail" style="color:red; display:none; font-weight: bold; font-size: 20px !important; margin-top: 15px;"></p>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== เวลาน้อยกว่าเวลาเริ่มต้น  ============================================= -->
<div class="modal fade" id="odvBookWaringDateTime" style="top: 25%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/document/document', 'แจ้งเตือน') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv">เวลาเสร็จสิ้น (ประมาณ) ไม่สามารถน้อยกว่า เวลาเริ่มต้นได้</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" onclick="JSxCLooseModalWaringDate();">
                    <?=language('common/main/main', 'tCMNOK'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<?php include('script/jBookingCalculate.php') ?>
<?php include('script/jBookingOptionBrowse.php') ?>

<script>        

    function JSxCLooseModalWaringDate(){
        $('#odvBookWaringDateTime').modal('hide');
    }

    //เวลาเสร็จสิ้น (ประมาณ) ไม่สามารถน้อยกว่า เวลาเริ่มต้นได้
    $('#oetBookTimeStart').blur(function(){
        var tDateStart = new Date($('#ohdBookDate').val() + $(this).val());
        tDateStat = tDateStart.getTime();
        
        var tDateEnd = new Date($('#ohdBookDate').val() + $('#oetBookTimeEnd').val());
        tDateEnd = tDateEnd.getTime();

        if(tDateEnd < tDateStat){
            var dNewDate = new Date($('#ohdBookDate').val() + $('#oetBookTimeStart').val());
            dNewDate.setTime(dNewDate.getTime() + (30 * 60 * 1000));
            var dNewTime = ("0" + dNewDate.getHours()).slice(-2) + ":" + ("0" + dNewDate.getMinutes()).slice(-2);
            $('#oetBookTimeEnd').val(dNewTime)
            return;
        }
    });

    $('#oetBookTimeEnd').blur(function(){
        var tDateStart = new Date($('#ohdBookDate').val() + $('#oetBookTimeStart').val());
        tDateStat = tDateStart.getTime();
        
        var tDateEnd = new Date($('#ohdBookDate').val() + $(this).val());
        tDateEnd = tDateEnd.getTime();

        if(tDateEnd < tDateStat){
            $('#odvBookWaringDateTime').modal('show');
            var dNewDate = new Date($('#ohdBookDate').val() + $('#oetBookTimeStart').val());
            dNewDate.setTime(dNewDate.getTime() + (30 * 60 * 1000));
            var dNewTime = ("0" + dNewDate.getHours()).slice(-2) + ":" + ("0" + dNewDate.getMinutes()).slice(-2);
            $(this).val(dNewTime)
            return;
        }
    });

    //โหลดข้อมูล follow
    setTimeout(function(){ JSxLoadTableHistoryService(); }, 2000);

    //วันที่นัดหมายสินค้า
    $(".xCNDatePickerforcate").datepicker({
        format          		: 'yyyy-mm-dd',
        todayHighlight  		: true,
        enableOnReadonly		: false,
        disableTouchKeyboard 	: true,
        autoclose       		: true,
        orientation     		: 'bottom' 
    });

    //วันที่ค้นหา Forcast
    $('#obtDateCalendarforcateFrom').unbind().click(function(){
        $('#oetDateforcateFrom').datepicker('show');
    });

    //วันที่ค้นหาจาก Forcast
    $('#obtDateCalendarforcateTo').unbind().click(function(){
        $('#oetDateforcateTo').datepicker('show');
    });

    $('.xCNDatePicker').datepicker({
        format                  : "dd/mm/yyyy",
        todayHighlight          : true,
        enableOnReadonly        : false,
        disableTouchKeyboard    : true,
        autoclose               : true,
        startDate               : new Date(),
    });

    $('#osmSaveBooking').show();
    $('#osmConfirmBooking').show();
    $('#osmCancelBooking').show();

    //Control ปุ่ม
    if('<?=empty($aItemBookingByID)?>'){ //ขาเพิ่ม
       $('#osmCancelBooking').hide();
    }else{ //ขาเเก้ไข
        if('<?=@$FTXshStaDoc?>' == 3){ //เอกสารยกเลิก 
            $('#osmCancelBooking').hide();

            $('#osmConfirmBooking').hide();              //ซ่อนปุ่มนัดหมาย และ นัดหมายยืนยัน
            $('#obtBKEventAddPDTToTemp').hide();            //ซ่อนปุ่ม เพิ่ม
            $('.xCNWahouse').hide();                        //ซ่อนส่วนคลัง
            $('#odvModalPopupBookingCalendar .xCNBtnBrowseAddOn').attr('disabled',true); //บล็อกปุ่มทั้งหมด เลือกไม่ได้
            $('#oetBookDate , #oetBookTimeStart , #oetBookTimeEnd , #oetTabRamarkWaringDay , #oetBKTelephone , #oetBKEmail').attr('disabled',true)
            $('#osmSaveBooking').hide();                    //ปุ่มบันทึก
            $('#ocmBKfoVatInOrEx').attr('disabled',true);   //ประเภทภาษี
            $('#otaBKTabRamark').attr('disabled',true);     //หมายเหตุ
        }else{
            if('<?=@$FTXshStaPrcDoc?>' == 2){ //นัดหมายเเล้วยืนยันเเล้ว
                $('#osmConfirmBooking').hide();              //ซ่อนปุ่มนัดหมาย และ นัดหมายยืนยัน
                $('#obtBKEventAddPDTToTemp').hide();            //ซ่อนปุ่ม เพิ่ม
                $('.xCNWahouse').hide();                        //ซ่อนส่วนคลัง
                $('#odvModalPopupBookingCalendar .xCNBtnBrowseAddOn').attr('disabled',true); //บล็อกปุ่มทั้งหมด เลือกไม่ได้
                $('#oetBookBrowseReason').attr('disabled',false); //เปิดปุ่มเหตุผล
                $('#oetBookDate').attr('disabled',false)        //วันที่ booking สำหรับเอาไว้เลื่อน
                $('#ocmBKfoVatInOrEx').attr('disabled',true);   //ประเภทภาษี
                $('#otaBKTabRamark').attr('disabled',true);     //หมายเหตุ
            }
            $('#osmCancelBooking').show();
        }
    }

    //เวลา
    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm'
    });

    //load หน้าจอสินค้า
    function JSxLoadTablePDTBookingCalendar(){
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarItemDT",
            data    : { 
                "tDocumentNumber"   : $('#ohdNameDocumentBooking').val() , 
                "tBchCode"          : $('#ohdBKFindBchCode').val() , 
                "tAgnCode"          : $('#ohdBKFindADCode').val() ,
                "tStaDoc"           : '<?=@$FTXshStaDoc?>' ,
                "tStaPrcDoc"        : '<?=@$FTXshStaPrcDoc?>' },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                $("#odvContentPDTBookingCalendar").html(tResult);

                //หลังจากเลือกสินค้า
                JSxControlPopUpInPageBooking();

                //หาราคารวม
                JSnBKCalculatePrice();

                $('.xCNOverlayBooking').delay(1000).fadeOut();
                setTimeout(function(){ 
                    $("#odvModalPopupBookingCalendar .modal-body").css('height','auto');
                    $('.xCNContentBookingAll').delay(1000).fadeIn();
                }, 1000);

                var nLen = $('#otbBKPdtTemp tbody tr').length;
                if(nLen > 5 ){
                    $('#odvBKContent').css('height','250px');
                    $('#odvBKContent').css('overflow-y','scroll');
                }else{
                    $('#odvBKContent').css('height','auto');
                    $('#odvBKContent').css('overflow-y','');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //load หน้าจอประวัติการเข้าใช้บริการ
    function JSxLoadTableHistoryService(){
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarHistoryService",
            data    : { 
                "tCustomerCode"     : $('#oetBKCusCode').val() , 
                "tCarCode"          : $('#oetBKCarCode').val() ,
                "tStaDoc"           : '<?=@$FTXshStaDoc?>' ,
                "tStaPrcDoc"        : '<?=@$FTXshStaPrcDoc?>' ,
                "tDateForcateFrom"  : $('#oetDateforcateFrom').val(),
                "tDateForcateTo"    : $('#oetDateforcateTo').val()
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                $("#odvContentPDTTabHistory").html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ตรวจสอบเช็คตารางเวลาอีกครั้ง
    function JSxCheckCalendarAgain(){
        localStorage.removeItem('ItemDataForCheckAgain');

        $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

        var obj             = [];
        obj.push({
            "tCusCode"      : $('#oetBKCusCode').val(),
            "tCusName"      : $('#oetBKCusName').val(),
            "tCarCode"      : $('#oetBKCarCode').val(),
            "tCarName"      : $('#oetBKCarName').val(),
            'tTelphone'     : $('#oetBKTelephone').val(),
            'tEmail'        : $('#oetBKEmail').val(),
            'nWaringDay'    : $('#oetTabRamarkWaringDay').val(),
            'tRemark'       : $('#otaBKTabRamark').val()
        });
        localStorage.setItem("ItemDataForCheckAgain", JSON.stringify(obj));
    }

    //Modal ปิด เปิด Modal หลัก
    function JSxControlPopUpInPageBooking(){    
        //เมื่อหน้าต่าง browse ปิดลง modal แม่ต้องเปิด
        // $(document).on('hidden.bs.modal', '#myModal', function () {
        //     if(nKeepBrowseMain != 1){ //ถ้าเป็น browse option ทั่วไป
        //         $('#odvModalPopupBookingCalendar').modal('show');
        //         nKeepBrowseMain = 0;
        //     }
        // });

        // $(document).on('hidden.bs.modal', '#odvModalDOCPDT', function () {
        //     $('#odvModalPopupBookingCalendar').modal('show');
        // });
    }

    //Modal ปิด เปิด Modal หลัก
    function JSxControlPopUpOpenBooking(){
        // setTimeout(function(){ 
        //     $('#odvModalPopupBookingCalendar').modal('show');
        // }, 500);
    }

    //ถ้าเป็นการกดตรวจสอบเวลาอีกครั้ง ข้อมูลจะไม่ถูกเคลียร์ แล้วเอาข้อมูลกลับมาใส่ตารางให้เหมือนเดิม
    if('<?=empty($aItemBookingByID)?>'){
        // var aArrayConvert = [JSON.parse(localStorage.getItem("ItemDataForCheckAgain"))];
        // if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
            
        // }else{
        //     var aItemInLocal = aArrayConvert[0][0];
        //     $('#oetBKCusCode').val(aItemInLocal.tCusCode);
        //     $('#oetBKCusName').val(aItemInLocal.tCusName);
        //     $('#oetBKCarCode').val(aItemInLocal.tCarCode);
        //     $('#oetBKCarName').val(aItemInLocal.tCarName);
        //     $('#oetBKTelephone').val(aItemInLocal.tTelphone);
        //     $('#oetBKEmail').val(aItemInLocal.tEmail);
        //     $('#oetTabRamarkWaringDay').val(aItemInLocal.nWaringDay);
        //     $('#otaBKTabRamark').val(aItemInLocal.tRemark);
        // }
    }

    //กดบันทึก - กดนัดหมาย & นัดหมายและยืนยัน
    $('#osmConfirmBooking , #osmSaveBooking').off();
    $('#osmConfirmBooking , #osmSaveBooking').on('click',function(){

        var tEventClick     = $(this).attr('data-eventclick');
        var tCusCode        = $('#oetBKCusCode').val();
        var tCarCode        = $('#oetBKCarCode').val();
        var tBCHCode        = $('#oetBookLastBchCode').val();
        var tBayCode        = $('#oetBookBayCode').val();

        //ถ้าไม่มีรถ จะกดยืนยันไม่ได้
        if(tCusCode == '' || tCarCode == '' || tBCHCode == '' || tBayCode == ''){
            //ซ่อนบราว์หลัก
            // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

            if(tCusCode == ''){
                var tTextWaring = 'กรุณาระบุข้อมูลลูกค้า';
            }else if(tCarCode == ''){
                var tTextWaring = 'กรุณาระบุข้อมูลรถลูกค้า';
            }else if(tBCHCode == ''){
                var tTextWaring = 'กรุณาระบุข้อมูลสาขาที่เข้ารับบริการ';
            }else{
                var tTextWaring = 'กรุณาระบุข้อมูลช่องให้บริการ ภายในสาขา';
            }

            $('#odvBKModalPleseSelectCSTAndCAR #ospModalBKPleseSelectData').text(tTextWaring);
            setTimeout(function(){ 
                $('#odvBKModalPleseSelectCSTAndCAR').modal("toggle")
            }, 200);
        }else{
            
            // if(tEventClick == 'booking'){ //กดนัดหมาย
            //     var tIDEventClickBK = $('.xCNEventClickBooking .xWBtnSaveActive').attr('data-id');
            //     if(tIDEventClickBK == 1){ //นัดหมาย
            //         JSxBKSaveAndBooking(tEventClick);
            //     }else if(tIDEventClickBK == 2){ // นัดหมาย และยืนยัน
            //         JSxBKCheckStockMQ().then(function(res) {
            //             var oReturn = JSON.parse(res);
            //             if(oReturn.nCountItem > 0){
            //                 return;
            //             }else{
            //                 JSxBKSaveAndBooking(tEventClick);
            //             }
            //         });
            //     }
            // }else{ //กดบันทึก
            //     JSxBKSaveAndBooking(tEventClick);
            // }

            JSxBKSaveAndBooking(tEventClick);
        }
    });

    //บันทึก + นัดหมาย
    function JSxBKSaveAndBooking(ptEventClick){
        var tCusCode        = $('#oetBKCusCode').val();
        var tCarCode        = $('#oetBKCarCode').val();
        var tTelphone       = $('#oetBKTelephone').val();
        var tEmail          = $('#oetBKEmail').val();
        var nWaringDay      = $('#oetTabRamarkWaringDay').val();
        var dDateBooking    = $('#ohdBookDate').val();
        var nStartTime      = $('#oetBookTimeStart').val();
        var nEndTime        = $('#oetBookTimeEnd').val();
        var aCoulumn        = '<?=json_encode($aColumn)?>';
        var tRemark         = $('#otaBKTabRamark').val(); 


        $('#ocmBKfoVatInOrEx').attr('disabled',false);      //ประเภทภาษี
        $('#otaBKTabRamark').attr('disabled',false);        //หมายเหตุ

        //----------------------------------------------------------------//
        //      //เพิ่มข้อมูล [บันทึก , นัดหมาย]                               
        //        // บันทึก FTXshStaPrcDoc : '' ไม่ต้องวิ่ง MQ
        //        // นัดหมาย FTXshStaPrcDoc : 1 วิ่ง MQ 
        //        //         และ ใช้ JS setinterval เช็ค 3 ครั้ง ว่าสินค้าผ่านทุกตัวไหม ถ้าผ่านก็จะ Update FTXshStaPrcDoc = 2 , StaApv = 1
        //
        //
        //      //แก้ไขข้อมูล [บันทึก , นัดหมาย]
        //         // บันทึก FTXshStaPrcDoc : ไม่ต้องสนใจ ไม่ต้องวิ่ง MQ
        //         // นัดหมาย วิ่งตรวจสอบในตาราง ว่าสินค้าทุกตัวผ่านไหม 
        //            // - ถ้าผ่าน : FTXshStaPrcDoc : 2
        //            // - ถ้าไม่ผ่าน : หน้าจอจะหยุดการทำงาน และจะวิ่ง MQ ไปตัดสต็อก 
        //            //             และ ใช้ JS setinterval เช็ค 3 ครั้ง ว่าสินค้าผ่านทุกตัวไหม ถ้าผ่านก็จะ Update FTXshStaPrcDoc = 2 , StaApv = 1
        //----------------------------------------------------------------//

        //บันทึกข้อมูล กรณีต้องการเลื่อนวัน เลื่อนเวลา
        if('<?=@$FTXshStaApv?>' == 1 && '<?=@$FTXshStaPrcDoc?>' == 2){

            //ซ่อนบราว์หลัก
            $('#odvModalPopupBookingCalendar .xCNCloseModal').click();
            
            $.ajax({
                type    : "POST",
                url     : "docBookingCalendarEventPostpone",
                data    : {
                    'tDocuemntNumber' : $('#ohdNameDocumentBooking').val(),
                    'tDateBooking'    : $('#oetBookDate').val(),
                    'tTelphone'       : tTelphone,
                    'tEmail'          : tEmail,
                    'nStartTime'      : nStartTime,
                    'nEndTime'        : nEndTime,
                    'nWaringDay'      : nWaringDay
                },
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    console.log(tResult);
                    JSvBKCallPageList();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            if($('#ohdNameDocumentBooking').val() == '' || $('#ohdNameDocumentBooking').val() == null){ //เพิ่มข้อมูล
                if(ptEventClick == 'save'){ //ปุ่มบันทึก
                    var tStaPrcDoc      = '';
                    var tStaApv         = '';
                }else if(ptEventClick == 'booking'){ //ปุ่มนัดหมาย
                    var tStaPrcDoc      = 1;
                    var tStaApv         = '';
                }
            }else{ //แก้ไขข้อมูล
                if(ptEventClick == 'save'){ //ปุ่มบันทึก
                    var tStaPrcDoc      = '<?=@$FTXshStaPrcDoc?>';
                    var tStaApv         = '<?=@$FTXshStaApv?>';
                    var bDontCallMQ     = false;
                }else if(ptEventClick == 'booking'){ //ปุ่มนัดหมาย
                    // var bCheckStkClient = true;
                    // $('#otbBKPdtTemp tbody tr').each(function(){
                    //     var nPrcstk  = $(this).attr('data-prcstk');
                    //     if(nPrcstk == 0){
                    //         bCheckStkClient = false;
                    //         return false;
                    //     }
                    // });

                    // if(bCheckStkClient == false){ //มีสินค้าที่ยังไม่ประมวลผลจะนัดหมายไม่ได้
                    //     $('.xCNTextBookingFail').text('* ไม่สามารถนัดหมายได้เนื่องจาก มีสินค้าบางรายการมีสต๊อกไม่เพียงพอ');
                    //     $('.xCNTextBookingFail').css('display','block');
                    // }

                    var tStaPrcDoc      = '<?=@$FTXshStaPrcDoc?>'; //สินค้าทุกตัวประมวลผลเเล้ว
                    var tStaApv         = '<?=@$FTXshStaApv?>'; //ยืนยันเเล้ว
                }
            }

            $.ajax({
                type    : "POST",
                url     : "docBookingCalendarEventAdd",
                data    : {
                    'tEventClick'     : ptEventClick,
                    'tStaPrcDoc'      : tStaPrcDoc,
                    'tStaApv'         : tStaApv,
                    'tDocuemntNumber' : $('#ohdNameDocumentBooking').val(),
                    'tDateBooking'    : $('#oetBookDate').val(),
                    'tCusCode'        : tCusCode,
                    'tCarCode'        : tCarCode,
                    'tTelphone'       : tTelphone,
                    'tEmail'          : tEmail,
                    'nWaringDay'      : nWaringDay,
                    'dDateBooking'    : dDateBooking,
                    'nStartTime'      : nStartTime,
                    'nEndTime'        : nEndTime,
                    'aCoulumn'        : aCoulumn,
                    'tRemark'         : tRemark,
                    'tBayCode'        : $('#oetBookBayCode').val(),
                    'tDocVat'         : $('#ocmBKfoVatInOrEx').val(),
                    'nStatusBooking'   : '<?=@$FTXshStaPrcDoc;?>',
                    'tBCHCode'        : $('#oetBookLastBchCode').val(),
                    'tAGNCode'        : $('#oetBookLastAgnCode').val(),
                    'tRSNCode'        : $('#oetBookReasonCode').val(),
                },
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    // console.log(tResult);
                    localStorage.removeItem('ItemDataForCheckAgain');
                    var oReturn = JSON.parse(tResult);
                    if(ptEventClick == 'booking'){
                        setTimeout(function () {
                            $('.xCNOverlay').css('z-index','9999');
                            $('#ohdNameDocumentBooking').val(oReturn.tDocNo);
                            JSxCallCheckSTKAndBooking(oReturn.tDocNo,1);
                        }, 1000);
                    }else{
                        //ซ่อนบราว์หลัก
                        $('#odvModalPopupBookingCalendar .xCNCloseModal').click();
                        JSvBKCallPageList();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    console.log(jqXHR);
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //เช็คสต็อก (ของใหม่)
    function JSxCallCheckSTKAndBooking(ptDocument , pnTimes){
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docBookingCheckStockInTemp",
            data    : { 'tDocuemntNumber' : ptDocument , 'nTimes' : pnTimes },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                var oReturn = JSON.parse(tResult);
                var nTimes  = parseInt(oReturn.nTimes) + 1;

                if(oReturn.nStaReturn == '800'){
                    //ทุกๆ 3 วินาที Call 1 ครั้ง
                    if(parseInt(oReturn.nTimes) == 3){
                        JCNxCloseLoading();
                        $('.xCNOverlay').css('z-index','2500');
                        $('.xCNTextBookingFail').text('* ไม่สามารถนัดหมายได้เนื่องจาก มีสินค้าบางรายการมีสต๊อกไม่เพียงพอ');
                        $('.xCNTextBookingFail').css('display','block');
                        $('.xCNTextBookingFail').addClass('xCNSaveButStockIsNotNull');

                        console.log(oReturn.aResultCheck);
                        var nItemError = oReturn.aResultCheck.length;
                        if(nItemError >= 1){

                            $('.xCNCHKStockAll').find('label').text('ยืนยันแล้ว');
                            $('.xCNCHKStockAll').find('label')
                            .removeClass('xCNBookingWaitConfirm_text')
                            .removeClass('xCNBookingCancel_text')
                            .removeClass('xCNBookingConfirm_text')
                            .addClass('xCNBookingConfirm_text')

                            for(var l=0; l<oReturn.aResultCheck.length; l++){
                                var tPDTCode = oReturn.aResultCheck[l].FTPdtCode;
                                $('.xCNCHKStock'+tPDTCode).find('label').text('สต๊อกไม่พอ');
                                $('.xCNCHKStock'+tPDTCode).find('label')
                                .removeClass('xCNBookingWaitConfirm_text')
                                .removeClass('xCNBookingConfirm_text')
                                .addClass('xCNBookingCancel_text')
                            }

                            //อัพเดทว่าสินค้าตัวนี้สต๊อกไม่พอ
                            JSxCallCheckUpdateSTKFail(ptDocument,oReturn);
                        }

                        return;
                    }else{
                        setTimeout(function () {
                            //console.log('วิ่งไปตรวจสอบสต็อกครั้งที่ : ' + nTimes)
                            JSxCallCheckSTKAndBooking(ptDocument,nTimes);
                        }, 1000);
                    }
                }else{
                    //ซ่อนบราว์หลัก
                    JCNxCloseLoading();
                    $('#odvModalPopupBookingCalendar .xCNCloseModal').click();
                    JSvBKCallPageList();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //อัพเดทว่าสินคัาตัวนี้สต๊อกไม่พอ
    function JSxCallCheckUpdateSTKFail(ptDocument,poItemFail){
        $.ajax({
            type    : "POST",
            url     : "docBookingUpdateSTKFail",
            data    : { 'tDocuemntNumber' : ptDocument , 'oItemFail' : poItemFail },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                console.log(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //เช็คสต็อก (ของเก่า)
    function JSxBKCheckStockMQ(){
        return $.ajax({
            type    : "POST",
            url     : "docBookingCheckStock",
            data    : { 'tDocuemntNumber' : $('#ohdNameDocumentBooking').val() , 'tBchCode' : $('#oetBookLastBchCode').val() },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                var oReturn = JSON.parse(tResult);
                if(oReturn.tRetrunStatus == 1){
                    var oItem       = oReturn.aItemStkFail;
                    var oItemCount  = oItem.length;
                    for(var i=0; i<oItemCount; i++){
                        var tPDTCode = oItem[i];
                        var tPDTCodeMain = $('.xCNValuePDTSet'+tPDTCode).parent().attr('data-pdtcode');
                        if(tPDTCodeMain == '' || tPDTCodeMain == null){
                            tPDTCodeMain = tPDTCode;
                        }
                        $('.xCNCHKStock'+tPDTCodeMain).find('label').text('สต็อกไม่พอ');
                        $('.xCNCHKStock'+tPDTCodeMain).find('label').attr('style','color : red !important; font-weight: bold;');
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดยกเลิก
    $('#osmCancelBooking').off();
    $('#osmCancelBooking').on('click',function(){

        var tCusCode        = $('#oetBKCusCode').val();
        var tCarCode        = $('#oetBKCarCode').val();
        var tRsnCode        = $('#oetBookReasonCode').val();
        
        //ถ้าไม่มีรถ จะกดยืนยันไม่ได้
        if(tCusCode == '' || tCarCode == ''){
            //ซ่อนบราว์หลัก
            // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

            $('#odvBKModalPleseSelectCSTAndCAR #ospModalBKPleseSelectData').text('<?=language('document/bookingcalendar/bookingcalendar', 'tBKModalInpulfullfill');?>');
            setTimeout(function(){ 
                $('#odvBKModalPleseSelectCSTAndCAR').modal("toggle")
            }, 200);
        }else if(tRsnCode == "" || tRsnCode == null){
            //ซ่อนบราว์หลัก
            // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();
            
            $('#odvBKModalPleseSelectCSTAndCAR #ospModalBKPleseSelectData').text('<?=language('document/bookingcalendar/bookingcalendar', 'กรุณาเลือกเหตุผลในการยกเลิก');?>');
            setTimeout(function(){ 
                $('#odvBKModalPleseSelectCSTAndCAR').modal("toggle")
            }, 200);
        }else{
            //ซ่อนบราว์หลัก
            $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

            $.ajax({
                type    : "POST",
                url     : "docBookingCalendarCancel",
                data    : {
                    'tDocuemntNumber' : $('#ohdNameDocumentBooking').val(),
                    'tBchCode'        : '<?=@$tBchCode?>',
                    'tRSNCode'        : $('#oetBookReasonCode').val()
                },
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    console.log(tResult);
                    localStorage.removeItem('ItemDataForCheckAgain');
                    JSvBKCallPageList();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    });


</script>