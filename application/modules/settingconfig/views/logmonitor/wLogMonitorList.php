<style>
    #odvInforSettingconfig,
    #odvInforAutonumber {
        padding-bottom: 0px;
    }

    #odvSettingConfig {
        margin-bottom: 0px !important;
    }
</style>

<div id="odvSettingConfig" class="panel panel-headline">
    <div class="panel-body" style="padding-top:20px !important;">
        <div id="odvLOGAdvanceSearchContainer" style="margin-bottom:20px;">
            <form id="ofmLOGFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- พิมพ์ค้นหา -->
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('settingconfig/logmonitor/logmonitor', 'tLOGInputSearch'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetLOGSearchAllDocument" name="oetLOGSearchAllDocument" placeholder="<?php echo language('settingconfig/logmonitor/logmonitor', 'tLOGInputSearch') ?>" autocomplete="off">
                                <span class="input-group-btn">
                                    <button id="obtLOGSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1">
                        <div class="form-group">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtLOGAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1">
                        <div class="form-group">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtLOGSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn" style="width:100%"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1">
                        <div class="form-group">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtLOGSearchReFresh" class="btn xCNBTNDefult xCNBTNDefult1Btn" style="width:100%"><?php echo language('common/main/main', 'รีเฟรช'); ?></button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1">
                        <div class="form-group">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtLOGSearchSync" class="btn xCNBTNDefult xCNBTNDefult1Btn" style="width:100%"><?php echo language('common/main/main', 'Sync'); ?></button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1">
                        <div class="form-group">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtLOGSearchExportExcel" onclick="JSvLOGExpExcel()" class="btn xCNBTNDefult xCNBTNDefult1Btn" style="width:100%"><?php echo language('common/main/main', 'Export Excel'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm">กลุ่ม</label>
                            <select id="ostLogMonitorFilterGroup" name="ostLogMonitorFilterGroup" class="selectpicker form-control">
                                <option value="1">User Activity Log</option>
                                <option value="2">Application Log</option>
                                <option value="3">Event Log</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm">แอปพลิเคชั่น</label>
                            <select id="ostLogMonitorFilterApplication" name="ostLogMonitorFilterApplication" class="selectpicker form-control">
                                <option value="0">ทั้งหมด</option>
                                <option value="AdaStoreBack">AdaStoreBack</option>
                                <option value="AdaStoreFront">AdaStoreFront</option>
                                <option value="MQRecive">MQRecive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm">ประเภท</label>
                            <select id="ostLogMonitorFilterType" name="ostLogMonitorFilterType" class="selectpicker form-control">
                                <option value="0">ทั้งหมด</option>
                                <option value="Error">Error</option>
                                <option value="Event">Event</option>
                                <option value="Information">Information</option>
                                <option value="Warning">Warning</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm">ระดับ</label>
                            <select id="ostLogMonitorFilterLevel" name="ostLogMonitorFilterLevel" class="selectpicker form-control">
                                <option value="0">ทั้งหมด</option>
                                <option value="Low">Low</option>
                                <option value="Miduim">Miduim</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xControlForm xCNHide" id="oetLogMonitorAgnCode" name="oetLogMonitorAgnCode" maxlength="5" value="">
                                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetLogMonitorAgnName" name="oetLogMonitorAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="" readonly>
                                <span class="input-group-btn">
                                    <button id="oimLogMonitorBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
                                        <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'สาขา'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetLogMonitorBchCode" name="oetLogMonitorBchCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetLogMonitorBchName" name="oetLogMonitorBchName" value="" readonly placeholder="สาขา">
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtLogMonitorBrowseBch" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
                                        <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'จุดขาย'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetLogMonitorPosCode" name="oetLogMonitorPosCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetLogMonitorPosName" name="oetLogMonitorPosName" value="" readonly placeholder="จุดขาย">
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtLogMonitorMultiBrowsePos" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
                                        <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'รอบการขาย'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetLogMonitorShiftCode" name="oetLogMonitorShiftCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetLogMonitorShiftName" name="oetLogMonitorShiftName" value="" readonly placeholder="รอบการขาย">
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtLogMonitorMultiBrowseShift" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
                                        <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'เมนู'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetLogMonitorMenuCode" name="oetLogMonitorMenuCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetLogMonitorMenuName" name="oetLogMonitorMenuName" value="" readonly placeholder="เมนู">
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtLogMonitorMultiBrowseMenu" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
                                        <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ผู้ใช้'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetLogMonitorUsrCode" name="oetLogMonitorUsrCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetLogMonitorUsrName" name="oetLogMonitorUsrName" value="" readonly placeholder="ผู้ใช้">
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtLogMonitorMultiBrowseUsr" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
                                        <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'วันที่'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNDatePicker" type="text" id="oetLOGDocDateFrom" name="oetLOGDocDateFrom" placeholder="<?php echo language('document/adjuststock/adjuststock', 'วันที่'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtLOGDocDateFrom" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'ถึงวันที่'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNDatePicker" type="text" id="oetLOGDocDateTo" name="oetLOGDocDateTo" placeholder="<?php echo language('document/adjuststock/adjuststock', 'ถึงวันที่'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtLOGDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <input type="text" class="input100 xCNHide" id="oetLogMonitorTab" name="oetLogMonitorTab" value="1">
            </form>
        </div>

        <div class="custom-tabs-line tabs-line-bottom left-aligned">
            <ul class="nav" role="tablist">
                <li class="nav-item  active" id="oliInforGeneralTap">
                    <a class="nav-link flat-buttons active" data-toggle="tab" href="#odvInforDataLog" onclick="JSxLOGTab(1)" role="tab" aria-expanded="true">
                        <?= language('settingconfig/settingconfig/settingconfig', 'ข้อมูล Log'); ?>
                    </a>
                </li>
                <?php if ($this->session->userdata("tSesUsrLevel") == "HQ") { ?>
                    <li class="nav-item" id="oliInforSettingConTab">
                        <a class="nav-link flat-buttons" data-toggle="tab" href="#odvInforLogWaitSync" onclick="JSxLOGTab(2)" role="tab" aria-expanded="false">
                            <?= language('settingconfig/settingconfig/settingconfig', 'Log รอ Sync'); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="tab-content">
                    <div id="odvInforDataLog" class="tab-pane in active" role="tabpanel" aria-expanded="true">

                    </div>
                    <div id="odvInforLogWaitSync" class="tab-pane" role="tabpanel" aria-expanded="true">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('script/jLogMonitorFormSearchList.php') ?>
<script>
    $("document").ready(function() {
        //Load view : config
        // JSvSettingConfigLoadViewSearch();
        //Load view : autonumber
        // JSvSettingNumberLoadViewSearch();

        //Load view : Log รอ Sync
        // JSvLogMonitorLoadView();
    });
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        // Set Select  Doc Date
        $('#obtLOGDocDateFrom').unbind().click(function() {
            event.preventDefault();
            $('#oetLOGDocDateFrom').datepicker('show');
        });

        $('#obtLOGDocDateTo').unbind().click(function() {
            event.preventDefault();
            $('#oetLODocDateTo').datepicker('show');
        });
    });

    function JSxLOGTab(nTab) {
        $('#oetLogMonitorTab').val(nTab)
    }

    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
    }).on('changeDate', function(ev) {
        var dDateFrom = $('#oetLOGDocDateFrom').val();
        var dDateTo = $('#oetLOGDocDateTo').val();

        if (dDateFrom == "") {
            $('#oetLOGDocDateFrom').val(dDateTo);
        }
        if (dDateTo == "") {
            $('#oetLOGDocDateTo').val(dDateFrom);
        }

    });

    $('#oimLogMonitorBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oLogMonitorBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetLogMonitorAgnCode',
                'tReturnInputName': 'oetLogMonitorAgnName',
            });
            JCNxBrowseData('oLogMonitorBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }

    });


    //Option Agency
    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
    var oBrowseAgn = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title: ['ticket/agency/agency', 'tAggTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'ticket/agency/agency',
                ColumnKeyLang: ['tAggCode', 'tAggName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMAgency.FDCreateOn DESC'],
            },
            NextFunc: {
                FuncName: 'JSxNextFuncPCKAgn'
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            },
            RouteAddNew: 'agency',
            BrowseLev: 1,
        }
        return oOptionReturn;
    }

    function JSxNextFuncPCKAgn() {
        $('#oetLogMonitorBchCode').val('');
        $('#oetLogMonitorBchName').val('');
        $('#oetLogMonitorPosCode').val('');
        $('#oetLogMonitorPosName').val('');
        // $('#oetPCKUsrCode').val('');
        // $('#oetPCKUsrName').val('');
    }


    /*===== Begin Event Browse =========================================================*/
    // สาขาที่สร้าง
    $("#obtLogMonitorBrowseBch").click(function() {
        let tWhereCon = "";
        let tUserLoginLevel = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';
        if (tUserLoginLevel != "HQ") {
            if ($('#oetLogMonitorAgnCode').val() != '') {
                tWhereCon = " AND TCNMBranch.FTAgnCode = '" + $('#oetLogMonitorAgnCode').val() + "' ";
            } else {
                tWhereCon = " AND TCNMBranch.FTBchCode IN(<?php echo $this->session->userdata('tSesUsrBchCodeMulti'); ?>) ";
            }
        } else {
            if ($('#oetLogMonitorAgnCode').val() != '') {
                tWhereCon = " AND TCNMBranch.FTBchType != 4 AND TCNMBranch.FTAgnCode = '" + $('#oetLogMonitorAgnCode').val() + "' ";
            } else {
                tWhereCon = " AND TCNMBranch.FTBchType != 4 ";
            }
        }

        // option
        window.oLogMonitorBrowseBch = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tWhereCon]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetLogMonitorBchCode", "TCNMBranch.FTBchCode"],
                Text: ["oetLogMonitorBchName", "TCNMBranch_L.FTBchName"]
            },
            /* NextFunc: {
                FuncName: 'JSxPCKCallbackBch',
                ArgReturn: ['FTBchCode']
            }, */
            // DebugSQL: true,
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        // JCNxBrowseData('oLogMonitorBrowseBch');
        JCNxBrowseMultiSelect('oLogMonitorBrowseBch');

    });


    // ============================================= Event Browse Multi Pos ============================================
    $('#obtLogMonitorMultiBrowsePos').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tWhere = "";

        if (tUsrLevel != "HQ") {
            tWhere = " AND TCNMPos.FTBchCode IN (" + tBchCodeMulti + ") ";
        } else {
            tWhere = "";
        }
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetLogMonitorBchCode').val();
            // let tDataShop = $('#oetRptShpCodeSelect').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                var tDataBranchWhere = tDataBranch.replaceAll(",", "','");
                tTextWhereInBranch = " AND (TCNMPos.FTBchCode IN ('" + tDataBranchWhere + "'))";
            }

            // ********** Check Data Shop **********
            // let tTextWhereInShop = '';
            // if (tDataShop) {
            //     if (tDataShop != '') {
            //         var tDataShopWhere = tDataShop.replaceAll(",", "','");
            //         tTextWhereInShop = " AND (TVDMPosShop.FTShpCode IN ('" + tDataShopWhere + "'))";
            //     }
            // }

            window.oPosBrowseMultiOption = undefined;
            oPosBrowseMultiOption = {
                Title: ["pos/salemachine/salemachine", "tPOSTitle"],
                Table: {
                    Master: 'TCNMPos',
                    PK: 'FTPosCode'
                },
                Join: {
                    Table: ['TCNMPos_L', 'TCNMBranch_L'],
                    On: [
                        'TCNMPos_L.FTBchCode = TCNMPos.FTBchCode AND TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FNLngID = ' + nLangEdits,
                        'TCNMPos.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                        // 'TCNMPos.FTPosCode = TVDMPosShop.FTPosCode AND TVDMPosShop.FTPshStaUse = 1',
                        // 'TVDMPosShop.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,
                        // 'TVDMPosShop.FTBchCode = TCNMShop_L.FTBchCode AND TVDMPosShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits
                    ]
                },
                Where: {
                    Condition: [
                        // 'AND (TCNMPos.FTPosType IN (1,2,3,4)) ' +
                        tTextWhereInBranch + tWhere
                    ] // เอา tTextWhereInShop ออก เพราะ SKC เราไม่ได้ใช้งานเรื่อง Shop
                },
                GrideView: {
                    ColumnPathLang: 'pos/salemachine/salemachine',
                    ColumnKeyLang: ['tPOSCode', 'tPOSName' /*, 'tPOSBranchRef'*/ ],
                    ColumnsSize: ['20%', '35%' /*, '35%'*/ ],
                    WidthModal: 50,
                    DataColumns: ['TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName' /*, 'TCNMBranch_L.FTBchName'*/ ],
                    DataColumnsFormat: ['', '' /*, ''*/ ],
                    Perpage: 10,
                    OrderBy: ['TCNMPos.FDCreateOn DESC, TCNMPos.FTPosCode ASC'],
                },
                CallBack: {
                    StausAll: ['oetRptPosStaSelectAll'],
                    Value: ['oetLogMonitorPosCode', "TCNMPos.FTPosCode"],
                    Text: ['oetLogMonitorPosName', "TCNMPos_L.FTPosName"]
                },
            };
            JCNxBrowseMultiSelect('oPosBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // ============================================= Event Browse Multi Shift ============================================
    $('#obtLogMonitorMultiBrowseShift').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tWhere = "";

        if (tUsrLevel != "HQ") {
            tWhere = " AND TPSTShiftHD.FTBchCode IN (" + tBchCodeMulti + ") ";
        } else {
            tWhere = "";
        }
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetLogMonitorBchCode').val();
            let tDataPos = $('#oetLogMonitorPosCode').val();

            // let tDataShop = $('#oetRptShpCodeSelect').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                var tDataBranchWhere = tDataBranch.replaceAll(",", "','");
                tTextWhereInBranch = " AND (TPSTShiftHD.FTBchCode IN ('" + tDataBranchWhere + "'))";
            }

            // ********** Check Data Pos **********
            let tTextWhereInPos = '';
            if (tDataPos != '') {
                var tDataPosWhere = tDataPos.replaceAll(",", "','");
                tTextWhereInPos = " AND (TPSTShiftHD.FTPosCode IN ('" + tDataPosWhere + "'))";
            }
            // ********** Check Data Shop **********
            // let tTextWhereInShop = '';
            // if (tDataShop) {
            //     if (tDataShop != '') {
            //         var tDataShopWhere = tDataShop.replaceAll(",", "','");
            //         tTextWhereInShop = " AND (TVDMPosShop.FTShpCode IN ('" + tDataShopWhere + "'))";
            //     }
            // }

            window.oShfBrowseMultiOption = undefined;
            oShfBrowseMultiOption = {
                Title: ["pos/salemachine/salemachine", "tPOSTitle"],
                Table: {
                    Master: 'TPSTShiftHD',
                    PK: 'FTShfCode'
                },
                Join: {
                    Table: ['TCNMPos_L', 'TCNMBranch_L'],
                    On: [
                        'TCNMPos_L.FTBchCode = TPSTShiftHD.FTBchCode AND TCNMPos_L.FTPosCode = TPSTShiftHD.FTPosCode AND TCNMPos_L.FNLngID = ' + nLangEdits,
                        'TPSTShiftHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                        // 'TCNMPos.FTPosCode = TVDMPosShop.FTPosCode AND TVDMPosShop.FTPshStaUse = 1',
                        // 'TVDMPosShop.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,
                        // 'TVDMPosShop.FTBchCode = TCNMShop_L.FTBchCode AND TVDMPosShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits
                    ]
                },
                Where: {
                    Condition: [
                        // 'AND (TCNMPos.FTPosType IN (1,2,3,4)) ' +
                        tTextWhereInBranch + tTextWhereInPos + tWhere
                    ] // เอา tTextWhereInShop ออก เพราะ SKC เราไม่ได้ใช้งานเรื่อง Shop
                },
                GrideView: {
                    ColumnPathLang: 'pos/salemachine/salemachine',
                    ColumnKeyLang: ['รอบการขาย', 'สาขา', 'จุดขาย' /*, 'tPOSBranchRef'*/ ],
                    ColumnsSize: ['35%', '25%', '25%' /*, '35%'*/ ],
                    WidthModal: 50,
                    DataColumns: ['TPSTShiftHD.FTShfCode', 'TCNMBranch_L.FTBchName', 'TCNMPos_L.FTPosName' /*, 'TCNMBranch_L.FTBchName'*/ ],
                    DataColumnsFormat: ['', '', '' /*, ''*/ ],
                    Perpage: 10,
                    OrderBy: ['TPSTShiftHD.FDCreateOn DESC, TPSTShiftHD.FTShfCode ASC'],
                },
                CallBack: {
                    StausAll: ['oetRptPosStaSelectAll'],
                    Value: ['oetLogMonitorShiftCode', "TPSTShiftHD.FTShfCode"],
                    Text: ['oetLogMonitorShiftName', "TPSTShiftHD.FTShfCode"]
                },
            };
            JCNxBrowseMultiSelect('oShfBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ============================================= Event Browse Multi Menu ============================================
    $('#obtLogMonitorMultiBrowseMenu').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tWhere = "";


        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetLogMonitorBchCode').val();
            let tDataPos = $('#oetLogMonitorPosCode').val();

            // let tDataShop = $('#oetRptShpCodeSelect').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                var tDataBranchWhere = tDataBranch.replaceAll(",", "','");
                tTextWhereInBranch = " AND (TPSTShiftHD.FTBchCode IN ('" + tDataBranchWhere + "'))";
            }

            // ********** Check Data Pos **********
            let tTextWhereInPos = '';
            if (tDataPos != '') {
                var tDataPosWhere = tDataPos.replaceAll(",", "','");
                tTextWhereInPos = " AND (TPSTShiftHD.FTPosCode IN ('" + tDataPosWhere + "'))";
            }
            // ********** Check Data Shop **********
            // let tTextWhereInShop = '';
            // if (tDataShop) {
            //     if (tDataShop != '') {
            //         var tDataShopWhere = tDataShop.replaceAll(",", "','");
            //         tTextWhereInShop = " AND (TVDMPosShop.FTShpCode IN ('" + tDataShopWhere + "'))";
            //     }
            // }

            window.oMenuBrowseMultiOption = undefined;
            oMenuBrowseMultiOption = {
                Title: ["pos/salemachine/salemachine", "tPOSTitle"],
                Table: {
                    Master: 'TCNSMenu',
                    PK: 'FTLicCode'
                },
                // Where: {
                //     Condition: [
                //         tTextWhereInBranch + tTextWhereInPos + tWhere
                //     ] // เอา tTextWhereInShop ออก เพราะ SKC เราไม่ได้ใช้งานเรื่อง Shop
                // },
                GrideView: {
                    ColumnPathLang: 'pos/salemachine/salemachine',
                    ColumnKeyLang: ['รหัสเมนู', 'ชื่อเมนู' /*, 'tPOSBranchRef'*/ ],
                    ColumnsSize: ['25%', '35%' /*, '35%'*/ ],
                    WidthModal: 50,
                    DataColumns: ['TCNSMenu.FTLicCode', 'TCNSMenu.FTLicMnuName' /*, 'TCNMBranch_L.FTBchName'*/ ],
                    DataColumnsFormat: ['', '', '' /*, ''*/ ],
                    Perpage: 10,
                    OrderBy: ['TCNSMenu.FDCreateOn DESC, TCNSMenu.FTLicCode ASC'],
                },
                CallBack: {
                    StausAll: ['oetRptPosStaSelectAll'],
                    Value: ['oetLogMonitorMenuCode', "TCNSMenu.FTLicCode"],
                    Text: ['oetLogMonitorMenuName', "TCNSMenu.FTLicMnuName"]
                },
            };
            JCNxBrowseMultiSelect('oMenuBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // ============================================= Event Browse Multi Usr ============================================
    $('#obtLogMonitorMultiBrowseUsr').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tWhere = "";


        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetLogMonitorBchCode').val();
            let tDataPos = $('#oetLogMonitorPosCode').val();

            if (tUsrLevel != "HQ") {
                if ($('#oetLogMonitorAgnCode').val() != '') {
                    tWhereFilter = " AND TCNTUsrGroup.FTAgnCode = '" + $('#oetLogMonitorAgnCode').val() + "' ";
                } else {
                    tWhereFilter = " AND TCNTUsrGroup.FTBchCode IN(<?php echo $this->session->userdata('tSesUsrBchCodeMulti'); ?>) ";
                }
            } else {
                if ($('#oetLogMonitorAgnCode').val() != '') {
                    tWhereFilter = " AND TCNTUsrGroup.FTAgnCode = '" + $('#oetLogMonitorAgnCode').val() + "' ";
                } else {
                    tWhereFilter = " ";
                }
            }

            // let tDataShop = $('#oetRptShpCodeSelect').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                var tDataBranchWhere = tDataBranch.replaceAll(",", "','");
                tTextWhereInBranch = " AND (TCNTUsrGroup.FTBchCode IN ('" + tDataBranchWhere + "'))";
            }

            // ********** Check Data Pos **********
            let tTextWhereInPos = '';
            if (tDataPos != '') {
                var tDataPosWhere = tDataPos.replaceAll(",", "','");
                tTextWhereInPos = " AND (TCNTUsrGroup.FTPosCode IN ('" + tDataPosWhere + "'))";
            }


            window.oUsrBrowseMultiOption = undefined;
            oUsrBrowseMultiOption = {
                Title: ["pos/salemachine/salemachine", "ผู้ใช้"],
                Table: {
                    Master: 'TCNMUser',
                    PK: 'FTUsrCode'
                },
                Join: {
                    Table: ['TCNMUser_L', 'TCNTUsrGroup'],
                    On: [
                        'TCNMUser.FTUsrCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits,
                        'TCNMUser.FTUsrCode = TCNTUsrGroup.FTUsrCode'
                    ]
                },
                Where: {
                    Condition: [tWhereFilter + tWhereFilter + tTextWhereInPos]
                },
                GrideView: {
                    ColumnPathLang: 'pos/salemachine/salemachine',
                    ColumnKeyLang: ['รหัสผู้ใช้', 'ชื่อผู้ใช้', ''],
                    ColumnsSize: ['15%', '85%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName'],
                    DataColumnsFormat: ['', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMUser.FDCreateOn DESC'],
                },
                CallBack: {
                    StausAll: ['oetRptPosStaSelectAll'],
                    Value: ['oetLogMonitorUsrCode', "TCNSMenu.FTUsrCode"],
                    Text: ['oetLogMonitorUsrName', "TCNMUser_L.FTUsrName"]
                },
            };
            JCNxBrowseMultiSelect('oUsrBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
</script>