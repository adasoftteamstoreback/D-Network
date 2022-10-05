<!--รายละเอียด-->
<div class="panel panel-headline">
    <div class="panel-body">

        <div class="custom-tabs-line tabs-line-bottom left-aligned">
            <ul class="nav" role="tablist" class="oulTabBooking">
                <!--ตารางนัดหมายรายวัน-->
                <li id="oliBookingByDayTab" class="active" data-typetab="main" data-tabtitle="posinfo" style="cursor:pointer">
                    <a role="tab" data-toggle="tab" data-target="#odvBookingByDayTab" aria-expanded="true">
                        <?= language('document/bookingcalendar/bookingcalendar','ลูกค้านัดหมาย') ?>
                    </a>
                </li>
                <!--ค้นหาลูกค้าตามเงื่อนไข-->
                <li id="oliBookingByCusTab" class="" data-typetab="sub" data-tabtitle="posinfouser" style="cursor:pointer">
                    <a role="tab" data-toggle="tab" data-target="#odvBookingByCusTab" aria-expanded="true">
                        <?= language('document/bookingcalendar/bookingcalendar','tBKTitleTab1') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">

            <!--ตารางนัดหมายรายวัน-->
            <div class="tab-pane active" id="odvBookingByDayTab" role="tabpanel" aria-expanded="true">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row" style="border: 1px solid #eaeaea; padding: 10px;">
                            <!-- ตัวแทนขาย -->
                            <div class="col-lg-2">
                                <?php
                                    $tBKInputADCode    = $this->session->userdata('tSesUsrAgnCode');
                                    $tBKInputADName    = $this->session->userdata('tSesUsrAgnName');
                                    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
                                        $tBrowseADDisabled     = '';
                                    }else{
                                        $tBrowseADDisabled     = 'disabled';
                                    }
                                ?>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/invoice/invoice', 'tIVTitlePanelConditionAD'); ?></label>
                                    <div class="input-group" style="width:100%;">
                                        <input type="text" class="input100 xCNHide" id="ohdBKFindADCode" name="ohdBKFindADCode" value="<?=$tBKInputADCode?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="ohdBKFindADName" name="ohdBKFindADName" value="<?=$tBKInputADName?>" readonly placeholder="<?= language('document/invoice/invoice', 'tIVTitlePanelConditionAD'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtBKFindBrowseAgency" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseADDisabled; ?>>
                                                <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- สาขา -->
                            <div class="col-lg-2">
                                <?php
                                    $tBKInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                    $tBKInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
                                        $tBrowseBCHDisabled         = '';
                                    }else{
                                        if($this->session->userdata("nSesUsrBchCount") < 2){
                                            $tBrowseBCHDisabled     = 'disabled';
                                        }else{
                                            $tBrowseBCHDisabled     = '';
                                        }
                                    }
                                ?>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQBranch'); ?></label>
                                    <div class="input-group" style="width:100%;">
                                        <input type="text" class="input100 xCNHide" id="ohdBKFindBchCode" name="ohdBKFindBchCode" value="<?= @$tBKInputBchCode; ?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetBKFindBchName" name="oetBKFindBchName" value="<?= @$tBKInputBchName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtBKFindBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBCHDisabled; ?>>
                                                <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- วันที่ -->
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','วันที่นัดหมาย'); ?></label>
                                    <div class="input-group">
                                        <input
                                            class="form-control xCNDatePicker"
                                            type="text"
                                            id="oetDateCalendar"
                                            name="oetDateCalendar"
                                            autocomplete="off"
                                            value="<?=date('Y-m-d');?>"
                                        >
                                        <span class="input-group-btn" >
                                            <button id="obtDateCalendar" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- ปุ่มค้นหา -->
                            <div class="col-lg-1">
                                <label class="xCNLabelFrm"></label>
                                <button type="button" onclick="JSxLoadControlBay();" style="width:100%;" class="btn xCNBTNPrimery"><?=language('common/main/main', 'tSearch'); ?></button>
                            </div>

                            <!-- สถานะ -->
                            <div class="col-lg-5">
                                <div class="form-group" style="float: right;">
                                    <label class="xCNLabelFrm"></label>
                                    <div class="input-group" style="width:100%;">
                                        <div class='xCNBookingConfirm' style="width: 20px; height: 20px; display: inline-block;"></div>
                                        <label class="xCNLabelFrm" style="margin-left: 10px; margin-right: 25px;"><?= language('document/bookingcalendar/bookingcalendar','tBKTabRamark_Status_1') ?></label>

                                        <div class='xCNBookingWaitConfirm' style="width: 20px; height: 20px; display: inline-block;"></div>
                                        <label class="xCNLabelFrm" style="margin-left: 10px; margin-right: 25px;"><?= language('document/bookingcalendar/bookingcalendar','นัดหมายแล้วรอยืนยัน') ?></label>

                                        <!-- <div class='xCNBookingCancel' style="width: 20px; height: 20px; display: inline-block;"></div>
                                        <label class="xCNLabelFrm" style="margin-left: 10px;"><?= language('common/main/main','tModalAdvClose') ?></label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div id="odvHiddenBlockEmptyData"></div>
                        <div class="row">
                            <div id='odvCalendar' style="margin-top: 25px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--ค้นหาลูกค้าตามเงื่อนไข-->
            <div class="tab-pane" id="odvBookingByCusTab" role="tabpanel" aria-expanded="true">
                <?php include "FindByCustomer/wFindByCustomerList.php"; ?>
            </div>
        </div>
    </div>
</div>

<!--กรุณากรอกข้อมูลให้ครบถ้วน-->
<div id="odvBKModalPleseSelectCSTAndCAR" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p id="ospModalBKPleseSelectData"><?=language('document/bookingcalendar/bookingcalendar', 'tBKModalInpulfullfill');?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal"  onclick="JSxControlPopUpOpenBooking();">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--กดยกเลิกการติดตาม-->
<div id="odvBKModalCloseFollowPDT" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'เหตุผลในการยกเลิก')?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNHide" id="oetBookReasonFlwCode" name="oetBookReasonFlwCode" value="">
                    <input type="text" class="form-control" id="oetBookReasonFlwName" name="oetBookReasonFlwName" readonly value="" placeholder="<?= language('document/quotation/quotation', 'เหตุผล'); ?>">
                    <span class="input-group-btn">
                        <button id="oetBookBrowseReasonFlw" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNClickCloseFollowPDT">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"  onclick="JSxControlPopUpOpenBooking();">
                    <?= language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--ต้องล้างค่า-->
<div id="odvBKModalChangeBCH" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p>การเปลี่ยนสาขา มีผลต่อสินค้า ยืนยันที่เปลี่ยนสาขาหรือไม่ ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNBKModalChangeBCHConfirm" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn xCNBKModalChangeBCHClose" type="button"  data-dismiss="modal" >
                    <?= language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>

    $('.xCNDatePicker').datepicker({
        format                  : 'yyyy-mm-dd',
        enableOnReadonly        : false,
        disableTouchKeyboard    : true,
        autoclose               : true
    });
    
    //วันที่นัดหมาย
    $('#obtDateCalendar').unbind().click(function(){
        $('#oetDateCalendar').datepicker('show');
    });

    //เลือกตัวแทนขาย 
    var nKeepBrowseMain = 0;
    $('#obtBKFindBrowseAgency').click(function(e) {
        nKeepBrowseMain = 1;
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBKBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'ohdBKFindADCode',
                'tReturnInputName': 'ohdBKFindADName',
            });
            JCNxBrowseData('oBKBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //ตัวแทนขาย
    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
    var oBrowseAgn = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title   : ['ticket/agency/agency', 'tAggTitle'],
            Table   : {
                Master  : 'TCNMAgency',
                PK      : 'FTAgnCode'
            },
            Join: {
                Table   : ['TCNMAgency_L'],
                On      : ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang  : 'ticket/agency/agency',
                ColumnKeyLang   : ['tAggCode', 'tAggName'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage         : 10,
                OrderBy         : ['TCNMAgency.FDCreateOn DESC'],
            },
            NextFunc:{
                FuncName        :'JSxNextFuncWhenSeletedAD'
            },
            CallBack    : {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text        : [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            }
        }
        return oOptionReturn;
    }

    //หลังจากเลือกตัวแทนขาย
    function JSxNextFuncWhenSeletedAD(aReturn){
        $('#ohdBKFindBchCode').val('');
        $('#oetBKFindBchName').val('');
    }

    //เลือกสาขา
    $('#obtBKFindBrowseBranch').unbind().click(function(){ 
        nKeepBrowseMain = 1;
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode    = $('#ohdBKFindADCode').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oBKBrowseBranchOption  = oBranchOptionForBK({
                'tReturnInputCode'  : 'ohdBKFindBchCode',
                'tReturnInputName'  : 'oetBKFindBchName',
                'tAgnCode'          : tAgnCode,
                'aArgReturn'        : ['FTBchCode','FTBchName'],
            });
            JCNxBrowseData('oBKBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //ตัวแปร Option Browse Modal สาขา
    var oBranchOptionForBK = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tAgnCode            = poDataFnc.tAgnCode;
        var aArgReturn          = poDataFnc.aArgReturn;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if(tUsrLevel != "HQ"){
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }

        if(tAgnCode != ""){
            tSQLWhereAgn = " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }
        
        var oOptionReturn       = {
            Title   : ['authen/user/user', 'tBrowseBCHTitle'],
            Table   : {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhereBch,tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }

    //โหลดข้อมูล
    JSxLoadControlBay();
    function JSxLoadControlBay(){
        var tADCode    = $('#ohdBKFindADCode').val();
        var tBCHCode   = $('#ohdBKFindBchCode').val();

        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarTable",
            data    : {'tADCode' : tADCode , 'tBCHCode' : tBCHCode},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvCalendar").html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
</script>