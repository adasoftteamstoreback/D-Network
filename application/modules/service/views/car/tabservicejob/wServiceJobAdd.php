<?php
//    print_r($aCldUserList);

if (isset($nStaAddOrEdit) && $nStaAddOrEdit == 1) {
    $tRoute             = "calendaruserEventEdit";
    $tUsrCode           = $aCldData['raItems']['rtUsrCode'];
    $tObjCode           = $aCldData['raItems']['rtObjCode'];
    $tUsrSeq            = $aCldData['raItems']['rtUsrSeq'];
    $tUsrRemark         = $aCldData['raItems']['FTUsrRemark'];
    $tUsrName           = $aCldData['raItems']['FTUsrName'];
    $tCldCode           = $aCldData['raItems']['rtObjCode'];
} else {
    $tRoute             = "calendaruserEventAdd";
    $tUsrCode           = "";
    $tObjCode           = "";
    $tUsrSeq            = "";
    $tUsrRemark         = "";
    $tUsrName           = "";
    $tCldCode           = $tCldCode;
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
<script>
    $(document).ready(function() {
        //####### เข้ามาแบบ add#######
        var tStaPage = '<?= $nStaAddOrEdit ?>';
        if (tStaPage == 99) {
            $('#odvModelUserCalendar').show();
            $('#odvConnType').show();
            $("#obtBrowseUser").bind("click", function() {
                JSxCheckPinMenuClose();
                JCNxBrowseData('oCmpBrowseUserCalendar');
            });
            $('#odvReferConn').hide();
            $('#odvRefer').hide();
        } else {
            $('#obtBrowseUser').click(function() {
                JSxCheckPinMenuClose();
                JCNxBrowseData('oCmpBrowseUserCalendar');
            });
        }
        //########### จบ #############
    });

    //Browse User
    var nLangEdits = <?php echo $this->session->userdata("tLangEdit"); ?>;
    var tSQLWhere = "";
    var ntotal = $('.xSChkUser').length;
    if (ntotal > 0) {
        var tSQLWhere = "AND TCNMUser_L.FTUsrCode NOT IN (";
        $(".xSChkUser").each(function(index) {
            var tValue = $(this).val();
            if (index === ntotal - 1) {
                tSQLWhere = tSQLWhere + "'" + tValue + "'";
            } else {
                tSQLWhere = tSQLWhere + "'" + tValue + "',";
            }
        });
        tSQLWhere = tSQLWhere + ")";
    }

    var oCmpBrowseUserCalendar = {
        Title: ['service/calendar/calendar', 'tCLDUser'],
        Table: {
            Master: 'TCNMUser',
            PK: 'FTUsrCode'
        },
        Join: {
            Table: ['TCNMUser_L'],
            On: ['TCNMUser_L.FTUsrCode = TCNMUser.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits, ]
        },
        Where: {
            Condition: [tSQLWhere]
        },
        GrideView: {
            ColumnPathLang: 'service/calendar/calendar',
            ColumnKeyLang: ['tCLDUserCode', 'tCLDUserName', 'tCLDRemark'],
            ColumnsSize: ['30%', '40%', '30%'],
            DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName', 'TCNMUser_L.FTUsrRmk'],
            DataColumnsFormat: ['', '', ''],
            WidthModal: 50,
            Perpage: 10,
            OrderBy: ['TCNMUser.FDCreateOn DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: ["oetAddCodeUserCalendar", "TCNMUser.FTUsrCode"],
            Text: ["oetAddNameUserCalendar", "TCNMUser_L.FTUsrName"],
        }
    }
</script>