<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script>
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit"); ?>';
    var dCurrentDate    = new Date();
    var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
    var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;

    $('.selectpicker').selectpicker();

    var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
    if (tUsrLevel != "HQ") {
        $('#oimSatSvBrowseAgn').attr("disabled", true);
    }

    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard: true,
        autoclose: true
    });

    $('#datepicker').datepicker({
        format: "yyyy-mm-dd",
        enableOnReadonly: false,
        disableTouchKeyboard: true,
        autoclose: true
    }).datepicker("setDate", 'now');

    $('#datepicker').datepicker();
    $('#datepicker').on('changeDate', function(e) {

        $('#ohdDWODateSearch').val(
            $('#datepicker').datepicker('getFormattedDate')
        );
        var dDate = $('#ohdDWODateSearch').val();
        var dGetDate = new Date($('#ohdDWODateSearch').val());
        var dGetDate2 = dGetDate.toString();
        var aStrDate = dGetDate2.split(" ");
        var tDateLang = aStrDate[2] + ' ' + aStrDate[1].toUpperCase() + ' ' + aStrDate[3]
        $('#odvDateLanChg').html(tDateLang);

        JSvDWOCallPageDataTableFilter();
    });


    if ($('#oetSatDocTime').val() == '') {
        $('#oetSatDocTime').val(tCurrentTime);
    }

    if ($('#oetSatDocDate').val() == '') {
        $('#oetSatDocDate').datepicker("setDate", dCurrentDate);
    }

    if ($('#oetSatSvDate').val() == '') {
        $('#oetSatSvDate').datepicker("setDate", dCurrentDate);
    }

    $('#obtDWODocDate').unbind().click(function() {
        $('#oetSatDocDate').datepicker('show');
    });

    $('#obtSatSvDate').unbind().click(function() {
        $('#oetSatSvDate').datepicker('show');
    });

    $('#obtSatDocTime').unbind().click(function() {
        $('#oetSatDocTime').datetimepicker('show');
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    // browse สาขา
    $('#obtDWOBrowseBCH').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oDWOBrowseBranchOption = undefined;
            oDWOBrowseBranchOption = oBranchOption({
                'tReturnInputCode': 'ohdDWOBchCode',
                'tReturnInputName': 'oetDWOBchName'
            });
            JCNxBrowseData('oDWOBrowseBranchOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // browse แผนก
    $('#obtDWOBrowseDepart').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oDWOBrowseDepartOption = undefined;
            oDWOBrowseDepartOption = oBrowseDepart({
                'tReturnInputCode': 'ohdDWODptCode',
                'tReturnInputName': 'oetDWODptName'
            });
            JCNxBrowseData('oDWOBrowseDepartOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ตัวแปร Option Browse Modal แผนก
    var oBrowseDepart = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;

        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if (tUsrLevel != "HQ") {
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN (" + tBchMulti + ")";
        } else {
            tSQLWhereBch = "";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn = {
            Title: ['authen/user/user', 'tBrowseDPTTitle'],
            Table: {
                Master: 'TCNMUsrDepart',
                PK: 'FTDptCode'
            },
            Join: {
                Table: ['TCNMUsrDepart_L'],
                On: ['TCNMUsrDepart_L.FTDptCode = TCNMUsrDepart.FTDptCode AND TCNMUsrDepart_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tSQLWhereBch, tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseDPTCode', 'tBrowseDPTName'],
                DataColumns: ['TCNMUsrDepart.FTDptCode', 'TCNMUsrDepart_L.FTDptName'],
                ColumnsSize: ['10%', '75%'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMUsrDepart.FDCreateOn DESC'],
            },
            //DebugSQL : true,
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMUsrDepart.FTDptCode"],
                Text: [tInputReturnName, "TCNMUsrDepart_L.FTDptName"]
            },
        };
        return oOptionReturn;
    }

    // ตัวแปร Option Browse Modal สาขา
    var oBranchOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;

        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if (tUsrLevel != "HQ") {
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN (" + tBchMulti + ")";
        } else {
            tSQLWhereBch = "";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn = {
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
                Condition: [tSQLWhereBch, tSQLWhereAgn]
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
            //DebugSQL : true,
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }

    //กดเพื่อที่ไปหน้า JOB
    function JSxGotoPageJob(ptDocNo,ptBchCode,ptAgnCode,ptCstCode) {
        var tRoute = 'docJOB/0/0';
            
        $.ajax({
            type    : "GET",
            url     : tRoute,
            cache   : false,
            timeout : 0,
            success : function(tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);
                localStorage.tCheckBackStage = 'PageDailyWorkOrder';

                setTimeout(function(){
                    JSvJOBCallPageEdit(ptAgnCode,ptBchCode,ptDocNo,ptCstCode);
                }, 800);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
</script>