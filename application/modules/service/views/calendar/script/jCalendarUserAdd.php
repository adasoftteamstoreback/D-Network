<script type="text/javascript">
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

        $('#obtCldUserStartDate').click(function(event) {
            $('#oetCldUserStart').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                enableOnReadonly: false,
                startDate: '1900-01-01',
                disableTouchKeyboard: true,
                autoclose: true,
            });
            $('#oetCldUserStart').datepicker('show');
            event.preventDefault();
        });

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            enableOnReadonly: false,
            startDate: $('#oetCldUserStart').val(),
            minDate: $('#oetCldUserStart').val(),
            disableTouchKeyboard: true,
            autoclose: true
        });

        $('#obtCldUserStartDate').click(function(event) {
            $('#oetCldUserStart').datepicker('show');
            event.preventDefault();
        });

        $('#obtCldUserFinishDate').click(function(event) {
            $('#oetCldUserFinish').datepicker('show');
            event.preventDefault();
        });

        $('#odvSmgSlipHeadContainer').sortable({
            items: '.xWSmgItemSelect',
            opacity: 0.7,
            axis: 'y',
            handle: '.xWSmgMoveIcon',
            update: function(event, ui) {
                var aToArray = $(this).sortable('toArray');
                var aSerialize = $(this).sortable('serialize', {
                    key: ".sort"
                });
            }
        });
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