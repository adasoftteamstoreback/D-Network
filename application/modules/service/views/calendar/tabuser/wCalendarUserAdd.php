<?php
//    print_r($aCldUserList);

if (isset($nStaAddOrEdit) && $nStaAddOrEdit == 1) {
    $tRoute             = "calendaruserEventEdit";
    $tUsrCode           = $aCldData['raItems']['rtUsrCode'];
    $tObjCode           = $aCldData['raItems']['rtObjCode'];
    $tUsrSeq            = $aCldData['raItems']['rtUsrSeq'];
    $tUsrRemark         = $aCldData['raItems']['FTSpuRemark'];
    $tUsrName           = $aCldData['raItems']['FTUsrName'];
    $tCldCode           = $aCldData['raItems']['rtObjCode'];
    $tUsrCldStart      = $aCldData['raItems']['rtObjDutyStart'];
	$tUsrCldStop       = $aCldData['raItems']['rtObjDutyFinish'];
} else {
    $tRoute             = "calendaruserEventAdd";
    $tUsrCode           = "";
    $tObjCode           = "";
    $tUsrSeq            = "";
    $tUsrRemark         = "";
    $tUsrName           = "";
    $tCldCode           = $tCldCode;
    $tUsrCldStart      	= date('Y-m-d');
	$tUsrCldStop       	= date('Y-m-d', strtotime('+1 year'));
}

?>

<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddUserCalendar">
    <button style="display:none" type="submit" id="obtSubmitUserCalendar" onclick="JSoAddEditUserCalendar('<?= $tRoute ?>')"></button>
    <input type="hidden" id="ohdCldUserRoute" name="ohdCldUserRoute" value="<?php echo $tRoute; ?>">
    <input type="hidden" id="ohdObjCodeUserCalendar" name="ohdObjCodeUserCalendar" value="<?php echo $tObjCode; ?>">

    <div class='row'>
        <div class="col-xs-12 col-md-5 col-lg-5">
            <!-- เปลี่ยน Col Class -->
            <div class="form-group">
                <input type="hidden" value="0" id="ohdCheckCldUserClearValidate" name="ohdCheckCldUserClearValidate">
                <?php
                if ($tRoute == "calendaruserEventAdd") {
                ?>

                <?php
                } else {
                ?>
                    <div class="form-group" id="odvCldUserCodeForm">
                        <div class="validate-input">
                            <label class="fancy-checkbox">
                                <input type="hidden" name="ohdTmpCode" id="ohdTmpCode" value="<?php echo $tUsrCode; ?>">
                            </label>
                        </div>
                    <?php
                }
                    ?>


                    </div>

                    <div class="form-group" id="odvModelUserCalendar">
                        <label class="xCNLabelFrm" id="odlUserCalendarName"><?php echo language('service/calendar/calendar', 'tCLDUserName') ?></label>
                        <div class="input-group">

                            <?php
                            if ($nStaAddOrEdit == '1') { //User
                                $tName  = $tUsrName;
                                $tValue = $tUsrCode;
                            } else {
                                $tName  = '';
                                $tValue = '';
                            } ?>
                            <input type="text" class="form-control xCNHide" id="oetAddCodeUserCalendar" name="oetAddCodeUserCalendar" value="<?php echo $tValue; ?>" data-validate="<?php echo  language('service/calendar/calendar', 'tCLDUser'); ?>">
                            <input type="text" class="form-control" id="oetAddNameUserCalendar" name="oetAddNameUserCalendar" value="<?php echo $tName; ?>" data-validate-required="กรุณาระบุผู้ใช้" readonly>
                            <span class="input-group-btn">
                                <button id="obtBrowseUser" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="validate-input">
                            <label class="xCNLabelFrm"><?php echo language('service/calendar/calendar', 'tCLDStartDate') ?></label>
                            <div class="input-group">
                                <input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetCldUserStart" name="oetCldUserStart" autocomplete="off" value="<?php if ($tUsrCldStart != "") {
                                                                                                                                                                                                    echo $tUsrCldStart;
                                                                                                                                                                                                } ?>" data-validate="<?php echo language('service/calendar/calendar', 'tCLDStartDate') ?>">
                                <span class="input-group-btn">
                                    <button id="obtCldUserStartDate" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="validate-input">
                            <label class="xCNLabelFrm"><?php echo language('service/calendar/calendar', 'tCLDFinishDate') ?></label>
                            <div class="input-group">
                                <input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetCldUserFinish" name="oetCldUserFinish" autocomplete="off" value="<?php if ($tUsrCldStop != "") {
                                                                                                                                                                                                    echo $tUsrCldStop;
                                                                                                                                                                                                } ?>" data-validate="<?php echo language('service/calendar/calendar', 'tCLDFinishDate') ?>">
                                <span class="input-group-btn">
                                    <button id="obtCldUserFinishDate" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?= language('service/calendar/calendar', 'tCLDRemark') ?></label>
                        <textarea class="form-control" rows="4" maxlength="100" id="otaCldUserRemark" name="otaCldUserRemark"><?php echo $tUsrRemark ?></textarea>
                    </div>
                    <input type="hidden" name="ohdObjCalendarCode" id="ohdObjCalendarCode" value="<?php echo $tCldCode; ?>">
                    <?php foreach ($aCldUserList as $nKey => $tValue) {
                    ?>
                        <input type="hidden" class="xSChkUser" name="ohdCldCurrentUser" id="ohdCldCurrentUser" value="<?php echo $tValue->rtUsrCode; ?>">
                    <?php
                    } ?>


            </div>
        </div>
    </div>
</form>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<?php include "application/modules/service/views/calendar/script/jCalendarUserAdd.php"; ?>