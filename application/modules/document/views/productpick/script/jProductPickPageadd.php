<script>
    $(document).ready(function() {

        localStorage.removeItem("LocalPCKPdtItemData");
        localStorage.removeItem('PCK_LocalItemDataDelDtTemp');
        $('#odvPCKContentHDRef').hide();
        JSxPCKEventCheckShowHDDocRef();

        JSxPCKGetPdtInTmp();
        FSxPCKCallPageHDDocRef();

        // if (tStaApv == "2" && !bIsCancel) {
        //     JSoPCKSubscribeMQ();
        // }

        var tStaDoc = '<?php echo $tStaDoc; ?>';
        var tStaApv = '<?php echo $tStaApv; ?>';
        var tRoute = '<?php echo $tRoute; ?>';
        var tStaPrcStk = '<?php echo $tStaPrcStk; ?>';

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $("#obtTFBO").removeAttr("disabled");

        $('#obtXthDocDate').click(function() {
            event.preventDefault();
            $('#oetXthDocDate').datepicker('show');
        });

        $('#obtXthDocTime').click(function() {
            event.preventDefault();
            $('#oetXthDocTime').datetimepicker('show');
        });

        $('#obtXthRefExtDate').click(function() {
            event.preventDefault();
            $('#oetXthRefExtDate').datepicker('show');
        });

        $('#obtXthRefIntDate').click(function() {
            event.preventDefault();
            $('#oetXthRefIntDate').datepicker('show');
        });

        $('#obtXthTnfDate').click(function() {
            event.preventDefault();
            $('#oetXthTnfDate').datepicker('show');
        });

        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss'
        });

        $('.xWTooltipsBT').tooltip({
            'placement': 'bottom'
        });

        $('[data-toggle="tooltip"]').tooltip({
            'placement': 'top'
        });

        $('#obtPCKXthRefIntDate').click(function() {
            $('#oetPCKXthRefIntDate').datepicker('show')
        });

        $('#obtPCKXthRefExtDate').click(function() {
            $('#oetPCKXthRefExtDate').datepicker('show')
        });

        $('#obtXthDocDate').click(function() {
            $('#oetPCKDocDate').datepicker('show')
        });

        $('#ocbPCKAutoGenCode').unbind().bind('change', function() {
            var bIsChecked = $('#ocbPCKAutoGenCode').is(':checked');
            var oInputDocNo = $('#oetPCKDocNo');
            if (bIsChecked) {
                $(oInputDocNo).attr('readonly', true);
                $(oInputDocNo).attr('disabled', true);
                $(oInputDocNo).val("");
                $(oInputDocNo).parents('.form-group').removeClass('has-error').find('em').hide();
            } else {
                $(oInputDocNo).removeAttr('readonly');
                $(oInputDocNo).removeAttr('disabled');
            }
        });




        if (!bIsApvOrCancel) {
            if (tUserLoginLevel == 'HQ') {
                // สาขาต้นทางต้องถูกกำหนดก่อนที่จะเลือก กลุ่มร้านค้าปลายทาง
                if ($('#oetPCKXthBchFrmCode').val() == '') { // ไม่ได้กำหนดสาขาต้นทาง
                    $('#obtPCKBrowseMerFrom').attr('disabled', true);
                } else { // กำหนดสาขาต้นทางแล้ว
                    $('#obtPCKBrowseMerFrom').attr('disabled', false);
                }

                $('#obtPCKBrowseShpFrom').attr('disabled', true);
                // $('#obtPCKBrowseWahFrom').attr('disabled', true);
            }

            if (tUserLoginLevel == 'BCH' && !bIsMultiBch) {
                $('#obtPCKBrowseBchFrom').attr('disabled', true);
                $('#obtPCKBrowseShpFrom').attr('disabled', true);
                // $('#obtPCKBrowseWahFrom').attr('disabled', true);
            }

            if (tUserLoginLevel == 'SHP') {
                $('#obtPCKBrowseBchFrom').attr('disabled', true);
                $('#obtPCKBrowseMerFrom').attr('disabled', true);
                $('#obtPCKBrowseShpFrom').attr('disabled', true);
                $('#obtPCKBrowseWahFrom').attr('disabled', true);
            }

            if ($('#oetPCKXthMerchantFrmCode').val() != '') { // กำหนดกลุ่มร้านค้าต้นทางแล้ว
                $('#obtPCKBrowseShpFrom').attr('disabled', false);
            }
            if ($('#oetPCKXthShopFrmCode').val() != '') { // กำหนดร้านค้าต้นทางแล้ว
                $('#obtPCKBrowseWahFrom').attr('disabled', false);
            }

            // คลังต้นทางต้องถูกกำหนดก่อน ถึงจะเลือกปลายทาง
            if ($('#oetPCKXthWhFrmCode').val() == '') { // ไม่ได้กำหนดคลังต้นทาง
                $('#obtPCKBrowseBchTo').attr('disabled', true);
                $('#obtPCKBrowseWahTo').attr('disabled', true);
            } else { // กำหนดคลังต้นทางแล้ว
                if (tUserLoginLevel == "HQ" || bIsMultiBch) {
                    $('#obtPCKBrowseBchTo').attr('disabled', false);
                } else {
                    // $('#obtPCKBrowseBchTo').attr('disabled', true);
                }
                $('#obtPCKBrowseWahTo').attr('disabled', true);

            }

            // สาขาปลายทางต้องถูกกำหนดก่อน ถึงเลือกคลังปลายทางได้
            if ($('#oetPCKXthBchToCode').val() == '') { // ไม่ได้กำหนดสาขาปลายทาง
                $('#obtPCKBrowseWahTo').attr('disabled', true);
            } else { // กำหนดสาขาปลายทางแล้ว
                if (tUserLoginLevel == "HQ" || bIsMultiBch) {
                    $('#obtPCKBrowseWahTo').attr('disabled', false);
                } else {
                    $('#obtPCKBrowseWahTo').attr('disabled', true);
                }
            }
        }

        if (bIsApvOrCancel && !bIsAddPage) {
            $('#obtPCKApprove').hide();
            $('#obtPCKCancel').hide();
            // $('#odvBtnAddEdit .btn-group').hide();
            $('form .xCNApvOrCanCelDisabled').attr('disabled', true);

            $('.xCNHideWhenCancelOrApprove').hide();
        } else {
            $('#odvBtnAddEdit .btn-group').show();
        }

        /*===== Begin Control สาขาที่สร้าง ================================================*/
        if (tUserLoginLevel != "HQ" && !bIsMultiBch) { //|| (!bIsAddPage) || (!bIsMultiBch)
            $("#obtPCKBrowseBch").attr('disabled', true);
        }
        /*===== End Control สาขาที่สร้าง ==================================================*/

        $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                return false;
            }
        });



        // ==================================================== Event Confirm Delete PDT IN Tabel DT ===================================================
        $('#odvPCKModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function() {

            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {
                JCNxOpenLoading();
                JSnPCKRemovePdtDTTempMultiple();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });
        // =============================================================================================================================================

    });

    /*===== Begin Event Browse =========================================================*/
    // สาขาที่สร้าง
    $("#obtPCKBrowseBch").click(function() {

        let tWhereCon = "";
        if (tUserLoginLevel != "HQ") {
            if ($('#oetPCKAgnCode').val() != '') {
                tWhereCon = " AND TCNMBranch.FTAgnCode = '" + $('#oetPCKAgnCode').val() + "' ";
            } else {
                tWhereCon = " AND TCNMBranch.FTBchCode IN(<?php echo $this->session->userdata('tSesUsrBchCodeMulti'); ?>) ";
            }
        } else {
            if ($('#oetPCKAgnCode').val() != '') {
                tWhereCon = " AND TCNMBranch.FTBchType != 4 AND TCNMBranch.FTAgnCode = '" + $('#oetPCKAgnCode').val() + "' ";
            } else {
                tWhereCon = " AND TCNMBranch.FTBchType != 4 ";
            }
        }

        // option
        window.oPCKBrowseBch = {
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
                Value: ["oetPCKBchCode", "TCNMBranch.FTBchCode"],
                Text: ["oetPCKBchName", "TCNMBranch_L.FTBchName"]
            },

            /* NextFunc: {
                FuncName: 'JSxPCKCallbackBch',
                ArgReturn: ['FTBchCode']
            }, */
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        JCNxBrowseData('oPCKBrowseBch');
    });

    // จากสาขา
    $("#obtPCKBrowseBchFrom").click(function() {

        let tWhereCon = "";
        if (tUserLoginLevel != "HQ") {
            tWhereCon = " AND TCNMBranch.FTBchCode IN(<?php echo $this->session->userdata('tSesUsrBchCodeMulti'); ?>) ";
        }

        // option
        window.oPCKBrowseBchFrom = {
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
                Condition: [
                    " AND TCNMBranch.FTBchCode NOT IN ('" + $('#oetPCKXthBchToCode').val() + "') ",
                    tWhereCon
                ]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
                // SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPCKXthBchFrmCode", "TCNMBranch.FTBchCode"],
                Text: ["oetPCKXthBchFrmName", "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName: 'JSxPCKCallbackBchFrom',
                ArgReturn: ['FTBchCode']
            },
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        JCNxBrowseData('oPCKBrowseBchFrom');
    });

    // จากกลุ่มธุรกิจ
    $("#obtPCKBrowseMerFrom").click(function() {
        // option
        window.oPCKBrowseMerFrom = {
            Title: ['company/warehouse/warehouse', 'tWAHBwsMchTitle'],
            Table: {
                Master: 'TCNMMerchant',
                PK: 'FTMerCode'
            },
            Join: {
                Table: ['TCNMMerchant_L'],
                On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [""]
            },
            GrideView: {
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWAHBwsMchCode', 'tWAHBwsMchNme'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMMerchant.FTMerCode', 'TCNMMerchant_L.FTMerName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMMerchant.FDCreateOn DESC'],
                // SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPCKXthMerchantFrmCode", "TCNMMerchant.FTMerCode"],
                Text: ["oetPCKXthMerchantFrmName", "TCNMMerchant_L.FTMerName"],
            },
            NextFunc: {
                FuncName: 'JSxPCKCallbackMerFrom',
                ArgReturn: ['FTMerCode', 'FTMerName']
            },
            BrowseLev: 1,
            //DebugSQL : true
        };
        JCNxBrowseData('oPCKBrowseMerFrom');
    });

    // จากร้านค้า
    $("#obtPCKBrowseShpFrom").click(function() {
        // Option Shop
        window.oPCKBrowseShpFrom = {
            Title: ['company/shop/shop', 'tSHPTitle'],
            Table: {
                Master: 'TCNMShop',
                PK: 'FTShpCode'
            },
            Join: {
                Table: ['TCNMShop_L', 'TCNMWaHouse_L'],
                On: [
                    'TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                    'TCNMWaHouse_L.FTWahCode = TCNMShop.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMShop.FTBchCode AND TCNMWaHouse_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [
                    function() {
                        var tSQL = " AND TCNMShop.FTShpType = '1' AND TCNMShop.FTMerCode = '" + $('#oetPCKXthMerchantFrmCode').val() + "' AND TCNMShop.FTBchCode = '" + $('#oetPCKXthBchFrmCode').val() + "'";
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['25%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName', 'TCNMShop.FTShpType', 'TCNMShop.FTBchCode', 'TCNMShop.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['', '', '', '', '', ''],
                DisabledColumns: [2, 3, 4, 5],
                Perpage: 5,
                OrderBy: ['TCNMShop_L.FTShpName'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPCKXthShopFrmCode", "TCNMShop.FTShpCode"],
                Text: ["oetPCKXthShopFrmName", "TCNMShop_L.FTShpName"],
            },
            NextFunc: {
                FuncName: 'JSxPCKCallbackShpFrom',
                ArgReturn: ['FTBchCode', 'FTShpCode', 'FTShpType', 'FTWahCode', 'FTWahName']
            },
            BrowseLev: 1,
            // DebugSQL : true
        }
        JCNxBrowseData('oPCKBrowseShpFrom');
    });

    // จากคลังสินค้า
    $("#obtPCKBrowseWahFrom").click(function() {

        //User สาขาจะมองไม่เห็นคลังของเสีย
        if ($('#ohdTWOnStaWasteWAH').val() == "") {
            var tFindWahouse = " AND TCNMWaHouse.FTWahStaType IN('1','2') ";
        } else {
            var tFindWahouse = " AND TCNMWaHouse.FTWahStaType IN('1','2','10') ";
        }

        var tShpFromCode = $('#oetPCKXthShopFrmCode').val();
        var bIsShpFromEmpty = (tShpFromCode === '') || (tShpFromCode == undefined);
        if (bIsShpFromEmpty) {
            window.oPCKBrowseWahFrom = {
                Title: ["company/warehouse/warehouse", "tWAHTitle"],
                Table: {
                    Master: 'TCNMWaHouse',
                    PK: 'FTWahCode'
                },
                Join: {
                    Table: ['TCNMWaHouse_L'],
                    On: ['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = ' + nLangEdits]
                },
                Where: {
                    Condition: [
                        function() {
                            var tSQL = " " + tFindWahouse + " AND TCNMWaHouse.FTBchCode = '" + $('#oetPCKXthBchFrmCode').val() + "'";
                            return tSQL;
                        }
                    ]
                },
                GrideView: {
                    ColumnPathLang: 'company/warehouse/warehouse',
                    ColumnKeyLang: ['tWahCode', 'tWahName'],
                    ColumnsSize: ['25%', '75%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName', 'TCNMWaHouse.FTBchCode'],
                    DataColumnsFormat: ['', '', '', ''],
                    DisabledColumns: [2, 3],
                    Perpage: 10,
                    OrderBy: ['TCNMWaHouse.FDCreateOn DESC'],
                    // SourceOrder: "ASC"
                },
                CallBack: {
                    ReturnType: 'S',
                    Value: ["oetPCKXthWhFrmCode", "TCNMWaHouse.FTWahCode"],
                    Text: ["oetPCKXthWhFrmName", "TCNMWaHouse_L.FTWahName"],
                },
                NextFunc: {
                    FuncName: 'JSxPCKCallbackWahFrom',
                    ArgReturn: ['FTBchCode', 'FTWahCode', 'FTWahName']
                },
                BrowseLev: 1,
                // DebugSQL : true
            }
        } else {
            window.oPCKBrowseWahFrom = {
                Title: ['company/shop/shop', 'tSHPWah'],
                Table: {
                    Master: 'TCNMShpWah',
                    PK: 'FTWahCode'
                },
                Join: {
                    Table: ['TCNMWaHouse_L'],
                    On: ['TCNMWaHouse_L.FTWahCode = TCNMShpWah.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMShpWah.FTBchCode AND TCNMWaHouse_L.FNLngID = ' + nLangEdits, ]
                },
                Where: {
                    Condition: [
                        function() {
                            var tSQL = " AND TCNMShpWah.FTBchCode = '" + $('#oetPCKXthBchFrmCode').val() + "' AND TCNMShpWah.FTShpCode = '" + $('#oetPCKXthShopFrmCode').val() + "'";
                            return tSQL;
                        }
                    ]
                },
                GrideView: {
                    ColumnPathLang: 'company/shop/shop',
                    ColumnKeyLang: ['tWahCode', 'tWahName'],
                    ColumnsSize: ['15%', '75%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMShpWah.FTWahCode', 'TCNMWaHouse_L.FTWahName', 'TCNMShpWah.FTBchCode'],
                    DataColumnsFormat: ['', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMShpWah.FDCreateOn DESC'],
                    // SourceOrder  : "ASC"
                },
                CallBack: {
                    ReturnType: 'S',
                    Value: ["oetPCKXthWhFrmCode", "TCNMShpWah.FTWahCode"],
                    Text: ["oetPCKXthWhFrmName", "TCNMWaHouse_L.FTWahName"],
                },
                NextFunc: {
                    FuncName: 'JSxPCKCallbackWahFrom',
                    ArgReturn: ['FTBchCode', 'FTWahCode', 'FTWahName']
                },
                BrowseLev: 1,
                // DebugSQL : true
            }
        }
        JCNxBrowseData('oPCKBrowseWahFrom');
    });

    // ถึงสาขา
    $("#obtPCKBrowseBchTo").click(function() {

        var tSesUsrAgnCode = "<?php echo $this->session->userdata("tSesUsrAgnCode"); ?>";
        let tWhereCon = "";
        // if(tUserLoginLevel != "HQ"){
        //     tWhereCon = " AND TCNMBranch.FTBchCode IN(<?php echo $this->session->userdata('tSesUsrBchCodeMulti'); ?>) ";
        // }
        if (tSesUsrAgnCode != "") {
            tWhereCon += " AND TCNMBranch.FTAgnCode ='" + tSesUsrAgnCode + "' ";
        }
        // option
        window.oPCKBrowseBchTo = {
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
                Condition: [
                    " AND TCNMBranch.FTBchCode NOT IN ('" + $('#oetPCKXthBchFrmCode').val() + "')",
                    tWhereCon
                ]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
                // SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPCKXthBchToCode", "TCNMBranch.FTBchCode"],
                Text: ["oetPCKXthBchToName", "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName: 'JSxPCKCallbackBchTo',
                ArgReturn: ['FTBchCode']
            }
        };
        JCNxBrowseData('oPCKBrowseBchTo');
    });

    // ถึงคลังสินค้า
    $("#obtPCKBrowseWahTo").click(function() {

        //User สาขาจะมองไม่เห็นคลังของเสีย
        if ($('#ohdTWOnStaWasteWAH').val() == "") {
            var tFindWahouse = " AND TCNMWaHouse.FTWahStaType IN('1','2') ";
        } else {
            var tFindWahouse = " AND TCNMWaHouse.FTWahStaType IN('1','2','10') ";
        }

        window.oPCKBrowseWahTo = {
            Title: ["company/warehouse/warehouse", "tWAHTitle"],
            Table: {
                Master: 'TCNMWaHouse',
                PK: 'FTWahCode'
            },
            Join: {
                Table: ['TCNMWaHouse_L'],
                On: ['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [
                    function() {
                        var tSQL = " " + tFindWahouse + " AND TCNMWaHouse.FTBchCode = '" + $('#oetPCKXthBchToCode').val() + "'";
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWahCode', 'tWahName'],
                ColumnsSize: ['25%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName', 'TCNMWaHouse.FTBchCode'],
                DataColumnsFormat: ['', '', '', ''],
                DisabledColumns: [2, 3],
                Perpage: 10,
                OrderBy: ['TCNMWaHouse.FDCreateOn DESC'],
                // SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPCKXthWhToCode", "TCNMWaHouse.FTWahCode"],
                Text: ["oetPCKXthWhToName", "TCNMWaHouse_L.FTWahName"],
            },
            NextFunc: {
                FuncName: 'JSxPCKCallbackWahTo',
                ArgReturn: ['FTBchCode', 'FTWahCode', 'FTWahName']
            },
            BrowseLev: 1
        }
        JCNxBrowseData('oPCKBrowseWahTo');
    });

    // เหตุผล
    $("#obtPCKBrowseReason").click(function() {
        // Option
        window.oPCKBrowseReason = {
            Title: ['other/reason/reason', 'tRSNTitle'],
            Table: {
                Master: 'TCNMRsn',
                PK: 'FTRsnCode'
            },
            Join: {
                Table: ['TCNMRsn_L'],
                On: [
                    'TCNMRsn.FTRsnCode = TCNMRsn_L.FTRsnCode AND TCNMRsn_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: ["AND TCNMRsn.FTRsgCode = '021' "] // Type โอน
            },
            GrideView: {
                ColumnPathLang: 'other/reason/reason',
                ColumnKeyLang: ['tRSNTBCode', 'tRSNTBName'],
                ColumnsSize: ['10%', '30%'],
                WidthModal: 50,
                DataColumns: ['TCNMRsn.FTRsnCode', 'TCNMRsn_L.FTRsnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMRsn.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ['oetPCKRsnCode', "TCNMRsn.FTRsnCode"],
                Text: ['oetPCKRsnName', "TCNMRsn_L.FTRsnName"]
            },
            RouteAddNew: 'reason',
            BrowseLev: 0
        }
        JCNxBrowseData('oPCKBrowseReason');
    });

    // ขนส่ง
    $("#obtPCKBrowseShipVia").click(function() {
        // Option
        window.oPCKBrowseShipVia = {
            Title: ['document/producttransferwahouse/producttransferwahouse', 'tTFWShipViaModalTitle'],
            Table: {
                Master: 'TCNMShipVia',
                PK: 'FTViaCode'
            },
            Join: {
                Table: ['TCNMShipVia_L'],
                On: [
                    "TCNMShipVia.FTViaCode = TCNMShipVia_L.FTViaCode AND TCNMShipVia_L.FNLngID = " + nLangEdits
                ]
            },
            GrideView: {
                ColumnPathLang: 'document/producttransferwahouse/producttransferwahouse',
                ColumnKeyLang: ['tTFWShipViaCode', 'tTFWShipViaName'],
                DataColumns: ['TCNMShipVia.FTViaCode', 'TCNMShipVia_L.FTViaName'],
                DataColumnsFormat: ['', ''],
                ColumnsSize: [''],
                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TCNMShipVia.FDCreateOn DESC'],
                // SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetTWOUpVendingViaCode", "TCNMShipVia.FTViaCode"],
                Text: ["oetTWOUpVendingViaName", "TCNMShipVia_L.FTViaName"],
            },
            BrowseLev: 1
        }
        JCNxBrowseData('oPCKBrowseShipVia');
    });
    /*===== End Event Browse ===========================================================*/

    /*===== Begin Callback Browse ======================================================*/
    // Browse Bch From Callback
    function JSxPCKCallbackBchFrom(params) {
        var tBchCodeFrom = $('#oetPCKXthBchFrmCode').val();

        // ต้นทาง
        $('#oetPCKXthMerchantFrmCode').val("");
        $('#oetPCKXthMerchantFrmName').val("");
        $('#oetPCKXthShopFrmCode').val("");
        $('#oetPCKXthShopFrmName').val("");
        $('#oetPCKXthWhFrmCode').val("");
        $('#oetPCKXthWhFrmName').val("");
        // ปลายทาง
        $('#oetPCKXthBchToCode').val("");
        $('#oetPCKXthBchToName').val("");
        $('#oetPCKXthWhToCode').val("");
        $('#oetPCKXthWhToName').val("");

        // ต้นทาง
        $('#obtPCKBrowseMerFrom').attr('disabled', true);
        $('#obtPCKBrowseShpFrom').attr('disabled', true);
        $('#obtPCKBrowseWahFrom').attr('disabled', true);
        // ปลายทาง
        $('#obtPCKBrowseBchTo').attr('disabled', true);
        $('#obtPCKBrowseWahTo').attr('disabled', true);

        if (tBchCodeFrom != "") {
            $('#obtPCKBrowseMerFrom').attr('disabled', false);
        }

        if (tUserLoginLevel == "HQ" || tUserLoginLevel == "BCH") {
            $('#obtPCKBrowseWahFrom').attr('disabled', false);
            if (tBchCodeFrom == "") {
                $('#obtPCKBrowseWahFrom').attr('disabled', true);
            }
        }

    }

    // Browse Mer From Callback
    function JSxPCKCallbackMerFrom(params) {
        var tMerCodeFrom = $('#oetPCKXthMerchantFrmCode').val();

        // ต้นทาง
        $('#oetPCKXthShopFrmCode').val("");
        $('#oetPCKXthShopFrmName').val("");
        $('#oetPCKXthWhFrmCode').val("");
        $('#oetPCKXthWhFrmName').val("");
        // ปลายทาง
        $('#oetPCKXthBchToCode').val("");
        $('#oetPCKXthBchToName').val("");
        $('#oetPCKXthWhToCode').val("");
        $('#oetPCKXthWhToName').val("");

        // ต้นทาง
        $('#obtPCKBrowseShpFrom').attr('disabled', true);
        $('#obtPCKBrowseWahFrom').attr('disabled', true);
        // ปลายทาง
        $('#obtPCKBrowseBchTo').attr('disabled', true);
        $('#obtPCKBrowseWahTo').attr('disabled', true);

        if (tMerCodeFrom != "") {
            $('#obtPCKBrowseShpFrom').attr('disabled', false);
        }

        if (tUserLoginLevel == "HQ" || tUserLoginLevel == "BCH") {
            $('#obtPCKBrowseWahFrom').attr('disabled', false);
        }
    }

    // Browse Shop From Callback
    function JSxPCKCallbackShpFrom(params) {
        var aParam = JSON.parse(params);
        $('#oetPCKWahInShopCode').val(aParam[3]);

        var tShpCodeFrom = $('#oetPCKXthShopFrmCode').val();

        // ต้นทาง
        $('#oetPCKXthWhFrmCode').val("");
        $('#oetPCKXthWhFrmName').val("");
        // ปลายทาง
        $('#oetPCKXthBchToCode').val("");
        $('#oetPCKXthBchToName').val("");
        $('#oetPCKXthWhToCode').val("");
        $('#oetPCKXthWhToName').val("");

        // ต้นทาง
        $('#obtPCKBrowseWahFrom').attr('disabled', true);
        // ปลายทาง
        $('#obtPCKBrowseBchTo').attr('disabled', true);
        $('#obtPCKBrowseWahTo').attr('disabled', true);

        if (tShpCodeFrom != "") {
            $('#obtPCKBrowseWahFrom').attr('disabled', false);
        }

        if (tUserLoginLevel == "HQ" || tUserLoginLevel == "BCH") {
            $('#obtPCKBrowseWahFrom').attr('disabled', false);
        }
    }

    // Browse Warehouse From Callback
    function JSxPCKCallbackWahFrom(params) {
        var tWahCodeFrom = $('#oetPCKXthWhFrmCode').val();
        var tBchCodeTo = $('#oetPCKXthBchToCode').val();
        var tWahCodeTo = $('#oetPCKXthWhToCode').val();

        // ปลายทาง
        $('#obtPCKBrowseBchTo').attr('disabled', true);
        $('#obtPCKBrowseWahTo').attr('disabled', true);

        if (tWahCodeFrom != "") {
            $('#obtPCKBrowseBchTo').attr('disabled', false);
            if (tBchCodeTo != "") {
                $('#obtPCKBrowseWahTo').attr('disabled', false);
            }
        } else {
            $('#oetPCKXthBchToCode').val("");
            $('#oetPCKXthBchToName').val("");
            $('#oetPCKXthWhToCode').val("");
            $('#oetPCKXthWhToName').val("");
        }
    }

    // Browse Bch To Callback
    function JSxPCKCallbackBchTo(params) {
        var tBchCodeTo = $('#oetPCKXthBchToCode').val();

        // ปลายทาง
        $('#oetPCKXthWhToCode').val("");
        $('#oetPCKXthWhToName').val("");

        // ปลายทาง
        $('#obtPCKBrowseWahTo').attr('disabled', true);

        if (tBchCodeTo != "") {
            $('#obtPCKBrowseWahTo').attr('disabled', false);

            //หาว่า สาขานี้คลัง default คือคลังอะไร
            $.ajax({
                type: "POST",
                url: "docPCKCheckWahouseInBCH",
                data: {
                    tBCHCode: tBchCodeTo,
                },
                cache: false,
                timeout: 5000,
                success: function(oResult) {
                    var aResult = JSON.parse(oResult)
                    $('#oetPCKXthWhToCode').val(aResult.aItems[0].FTWahCode);
                    $('#oetPCKXthWhToName').val(aResult.aItems[0].FTWahName);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    // Browse Warehouse To Callback
    function JSxPCKCallbackWahTo(params) {}

    var bUniquePCKCode;
    $.validator.addMethod(
        "uniquePCKCode",
        function(tValue, oElement, aParams) {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {

                var tPCKCode = tValue;
                $.ajax({
                    type: "POST",
                    url: "docPCKUniqueValidate",
                    data: "tPCKCode=" + tPCKCode,
                    dataType: "JSON",
                    success: function(poResponse) {
                        bUniquePCKCode = (poResponse.bStatus) ? false : true;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Custom validate uniquePCKCode: ', jqXHR, textStatus, errorThrown);
                    },
                    async: false
                });
                return bUniquePCKCode;

            } else {
                JCNxShowMsgSessionExpired();
            }

        },
        "Doc No. is Already Taken"
    );

    // Validate Form
    function JSxPCKValidateForm() {

        if ($('#oetPCKRsnCode').val() == '' || $('#oetPCKRsnCode').val() == null) {
            FSvCMNSetMsgWarningDialog('<?php echo language('document/purchasebranch/purchasebranch', 'tPRBPlsSelectReason'); ?>');
            return;
        }

        var oTopUpVendingForm = $('#ofmPCKForm').validate({
            focusInvalid: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            rules: {
                oetPCKDocNo: {
                    required: true,
                    maxlength: 20,
                    uniquePCKCode: bIsAddPage
                },
                oetPCKDocDate: {
                    required: true
                },
                oetPCKDocTime: {
                    required: true
                },
                /* oetPCKXthBchFrmName: {
                    required: true
                },
                oetPCKXthMerchantFrmName: {
                    required: true
                },
                oetPCKXthShopFrmName: {
                    required: true
                },
                oetPCKXthWhFrmName: {
                    required: true
                },
                oetPCKXthBchToName: {
                    required: true
                },
                oetPCKXthWhToName: {
                    required: true
                } */
            },
            messages: {
                oetPCKDocNo: {
                    "required": $('#oetPCKDocNo').attr('data-validate-required')
                }
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                if (element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if (tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function(form) {
                JSxPCKSave();
                // alert('Save')
            }
        });
    }

    // Save Doc
    function JSxPCKSave(ptType = '') {
        // var bIsWahFromEmpty = $('#oetPCKXthWhFrmCode').val() == "";
        // var bIsWahToEmpty = $('#oetPCKXthWhToCode').val() == "";
        // if (bIsWahFromEmpty || bIsWahToEmpty) {
        //     var tWarningMessage = 'กรุณาตรวจสอบข้อมูล เงื่อนไข ก่อนบันทึก';
        //     FSvCMNSetMsgWarningDialog(tWarningMessage);
        //     return;
        // }
        // $('#obtTFBO').attr('disabled', true);

        var bIsPdtEmpty = $('.xWPCKPdtTable').find('tr.xWPCKPdtItem').length < 1;
        if (bIsPdtEmpty) {
            var tWarningMessage = 'กรุณาเพิ่มรายการสินค้า ก่อนบันทึก';
            FSvCMNSetMsgWarningDialog(tWarningMessage);
            return;
        }

        var bIsBchEmpty = $('#oetPCKBchCode').val();
        if (bIsBchEmpty == '') {
            var tWarningMessage = 'กรุณาเพิ่มสาขา ก่อนบันทึก';
            FSvCMNSetMsgWarningDialog(tWarningMessage);
            return;
        }

        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPCKBchCode').val();
            var tMerCode = $('#oetPCKMchCode').val();
            var tShpCode = $('#oetPCKPCKShpCode').val();
            var tPosCode = $('#oetPCKPosCode').val();
            var tWahCode = $('#oetPCKWahCode').val();

            // JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "<?php echo $tRoute; ?>",
                data: $("#ofmPCKForm").serialize(),
                cache: false,
                timeout: 5000,
                dataType: "JSON",
                success: function(oResult) {

                    var oPCKCallDataTableFile = {
                        ptElementID: 'odvPCKShowDataTable',
                        ptBchCode: $('#oetPCKBchCode').val(),
                        ptDocNo: oResult.tCodeReturn,
                        ptDocKey: 'TCNTPdtPickHD',
                    }
                    JCNxUPFInsertDataFile(oPCKCallDataTableFile);


                    if (ptType == 'approve') {

                    } else {
                        switch (oResult.nStaCallBack) {
                            case "1": {
                                JSvPCKCallPageEdit(oResult.tCodeReturn);
                                break;
                            }
                            case "2": {
                                JSvPCKCallPageAdd();
                                break;
                            }
                            case "3": {
                                JSvPCKCallPageList();
                                break;
                            }
                            default: {
                                JSvPCKCallPageEdit(oResult.tCodeReturn);
                            }
                        }
                    }
                    $("#obtTFBO").removeAttr("disabled");

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    $("#obtTFBO").removeAttr("disabled");
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Approve Doc
    function JSvPCKApprove(pbIsConfirm) {
        var bIsWahFromEmpty = $('#oetPCKXthWhFrmCode').val() == "";
        var bIsWahToEmpty = $('#oetPCKXthWhToCode').val() == "";


        if (bIsWahFromEmpty || bIsWahToEmpty) {
            var tWarningMessage = 'กรุณาตรวจสอบข้อมูล เงื่อนไข ก่อนอนุมัติ';
            FSvCMNSetMsgWarningDialog(tWarningMessage);
            return;
        }

        var bIsPdtEmpty = $('.xWPCKPdtTable').find('tr.xWPCKPdtItem').length < 1;
        if (bIsPdtEmpty) {
            var tWarningMessage = 'กรุณาเพิ่มรายการสินค้า ก่อนอนุมัติ';
            FSvCMNSetMsgWarningDialog(tWarningMessage);
            return;
        }

        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            try {
                if (pbIsConfirm) {
                    JCNxOpenLoading();
                    $("#ohdPCKStaApv").val(2);
                    $("#odvPCKPopupApv").modal("hide");
                    JSxPCKSave('approve');

                    var tDocNo = $("#oetPCKDocNo").val();
                    var tStaApv = $("#ohdPCKStaApv").val();
                    var tBchCode = $('#oetPCKBchCode').val();

                    $.ajax({
                        type: "POST",
                        url: "docPCKCheckProductWahouse",
                        data: {
                            'tDocNo': tDocNo,
                            'tBchCode': $('#oetPCKXthBchFrmCode').val()
                        },
                        cache: false,
                        timeout: 0,
                        success: function(tResult) {
                            var aReturnData = JSON.parse(tResult);
                            if (aReturnData['nStaEvent'] == 1 || aReturnData['nStaEvent'] == 400 || aReturnData['tChkTsysConfig'] == '2') {
                                $.ajax({
                                    type: "POST",
                                    url: "docPCKDocApprove",
                                    data: {
                                        tDocNo: tDocNo,
                                        tStaApv: tStaApv,
                                        tBchCode: tBchCode
                                    },
                                    cache: false,
                                    timeout: 0,
                                    success: function(oResult) {
                                        try {
                                            if (oResult.nStaEvent == "900") {
                                                FSvCMNSetMsgErrorDialog(oResult.tStaMessg);
                                                JCNxCloseLoading();
                                                return;
                                            }
                                        } catch (err) {}
                                        JCNxCloseLoading();
                                        // JSoPCKSubscribeMQ();
                                        JSvPCKCallPageList();
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                                        JCNxCloseLoading();
                                    }
                                });
                            } else {
                                JSvPCKCallPageEdit(tDocNo);
                                var tMessageError = aReturnData['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                JCNxCloseLoading();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                } else {
                    // console.log("StaApvDoc Call Modal");
                    $("#odvPCKPopupApv").modal("show");
                }
            } catch (err) {
                console.log("JSvPCKApprove Error: ", err);
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Cancel Doc
    function JSvPCKCancel(pbIsConfirm) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tDocNo = $("#oetPCKDocNo").val();
            if (pbIsConfirm) {
                $.ajax({
                    type: "POST",
                    url: "docPCKDocCancel",
                    data: {
                        tDocNo: tDocNo
                    },
                    cache: false,
                    timeout: 5000,
                    success: function(tResult) {
                        $("#odvPCKPopupCancel").modal("hide");
                        var aResult = $.parseJSON(tResult);
                        if (aResult.nSta == 1) {
                            JSvPCKCallPageEdit(tDocNo);
                        } else {
                            JCNxCloseLoading();
                            var tMsgBody = aResult.tMsg;
                            FSvCMNSetMsgWarningDialog(tMsgBody);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                $("#odvPCKPopupCancel").modal("show");
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // SubscribeMQ
    function JSoPCKSubscribeMQ() {
        // Document variable
        var tLangCode = $("#ohdLangEdit").val();
        var tUsrBchCode = $("#ohdPCKBchLogin").val();
        var tUsrApv = $("#oetPCKApvCodeUsrLogin").val();
        var tDocNo = $("#oetPCKDocNo").val();
        var tPrefix = "RESTBO";
        var tStaApv = $("#ohdPCKStaApv").val();
        var tStaDelMQ = $("#ohdPCKStaDelMQ").val();
        var tQName = tPrefix + "_" + tDocNo + "_" + tUsrApv;

        // MQ Message Config
        var poDocConfig = {
            tLangCode: tLangCode,
            tUsrBchCode: tUsrBchCode,
            tUsrApv: tUsrApv,
            tDocNo: tDocNo,
            tPrefix: tPrefix,
            tStaDelMQ: tStaDelMQ,
            tStaApv: tStaApv,
            tQName: tQName
        };

        // RabbitMQ STOMP Config
        var poMqConfig = {
            host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
            username: oSTOMMQConfig.user,
            password: oSTOMMQConfig.password,
            vHost: oSTOMMQConfig.vhost
        };

        // Update Status For Delete Qname Parameter
        var poUpdateStaDelQnameParams = {
            ptDocTableName: "TCNTPdtTboHD",
            ptDocFieldDocNo: "FTXthDocNo",
            ptDocFieldStaApv: "FTXthStaPrcStk",
            ptDocFieldStaDelMQ: "FTXthStaDelMQ",
            ptDocStaDelMQ: tStaDelMQ,
            ptDocNo: tDocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit: "JSvPCKCallPageEdit",
            tCallPageList: "JSvPCKCallPageList"
        };

        // Check Show Progress %
        FSxCMNRabbitMQMessage(
            poDocConfig,
            poMqConfig,
            poUpdateStaDelQnameParams,
            poCallback
        );
        /*===========================================================================*/
        // RabbitMQ
    }

    // Get Pdt in Temp
    function JSxPCKGetPdtInTmp(pnPage, pbUseLoading) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPCKBchCode').val();
            var tMerCode = $('#oetPCKMchCode').val();
            var tShpCode = $('#oetPCKShpCode').val();
            var tPosCode = $('#oetPCKPosCode').val();
            var tWahCode = $('#oetPCKWahCode').val();

            var tSearchAll = $('#oetPCKPdtSearchAll').val();

            if (pbUseLoading) {
                JCNxOpenLoading();
            }

            (pnPage == '' || (typeof pnPage) == 'undefined') ? pnPage = 1: pnPage = pnPage;

            $.ajax({
                type: "POST",
                url: "docPCKGetPdtInTmp",
                data: {
                    tBchCode: tBchCode,
                    tMerCode: tMerCode,
                    tShpCode: tShpCode,
                    tPosCode: tPosCode,
                    tWahCode: tWahCode,
                    nPageCurrent: pnPage,
                    tIsApvOrCancel: (bIsApvOrCancel) ? "1" : "0",
                    tSearchAll: tSearchAll
                },
                cache: false,
                timeout: 5000,
                success: function(oResult) {
                    JSxPCKSetEndOfBill(oResult.aEndOfBill);
                    $('#odvPCKPdtDataTable').html(oResult.html);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Insert Pdt to Temp
    function JSvPCKInsertPdtToTemp(ptPdtData) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tPCKOptionAddPdt = $('#ocmPCKOptionAddPdt').val();
            var tPCKDocNo = $('#oetPCKDocNo').val();

            JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "docPCKInsertPdtToTmp",
                data: {
                    tPdtData: ptPdtData,
                    ptBchCode: $('#oetPCKBchCode').val(),
                    tPCKOptionAddPdt: tPCKOptionAddPdt,
                    tPCKDocNo : tPCKDocNo
                },
                cache: false,
                timeout: 5000,
                success: function(tResult) {
                    JSxPCKGetPdtInTmp(1, true);
                    $('#odvPCKPopupPdtAdd').modal('hide');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //  Clear Pdt in Temp
    function JSvPCKClearPdtInTemp() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "docPCKClearPdtInTmp",
                cache: false,
                timeout: 5000,
                success: function(tResult) {
                    JSxPCKGetPdtInTmp(1, true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Pdt Column Control
    function JSxPCKPdtColumnControl() {
        $("#odvPCKPdtColumnControlPanel").modal('show');
        $.ajax({
            type: "POST",
            url: "docPCKGetPdtColumnList",
            data: {},
            cache: false,
            Timeout: 0,
            success: function(tResult) {
                $("#odvPCKPdtColummControlDetail").html(tResult);
                // JSCNAdjustTable();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Update Pdt Column
    function JSxPCKUpdatePdtColumn() {
        var aColShowSet = [];
        $(".ocbPCKPCKPdtColStaShow:checked").each(function() {
            aColShowSet.push($(this).data("id"));
        });

        var aColShowAllList = [];
        $(".ocbPCKPdtColStaShow").each(function() {
            aColShowAllList.push($(this).data("id"));
        });

        var aColumnLabelName = [];
        $(".olbPCKColumnLabelName").each(function() {
            aColumnLabelName.push($(this).text());
        });

        var nStaSetDef;
        if ($("#ocbSetToDef").is(":checked")) {
            nStaSetDef = 1;
        } else {
            nStaSetDef = 0;
        }

        $.ajax({
            type: "POST",
            url: "docPCKPCKUpdatePdtColumn",
            data: {
                aColShowSet: aColShowSet,
                nStaSetDef: nStaSetDef,
                aColShowAllList: aColShowAllList,
                aColumnLabelName: aColumnLabelName
            },
            cache: false,
            Timeout: 0,
            success: function(tResult) {
                $("#odvPCKPdtColumnControlPanel").modal("hide");
                JSxPCKGetPdtInTmp(1, true);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Browse Pdt
    function JCNvPCKBrowsePdt() {

        var bIsWahFromEmpty = $('#oetPCKXthWhFrmCode').val() == "";
        var bIsWahToEmpty = $('#oetPCKXthWhToCode').val() == "";
        if (bIsWahFromEmpty || bIsWahToEmpty) {
            var tWarningMessage = 'กรุณาตรวจสอบข้อมูล เงื่อนไข ก่อนเพิ่มรายการสินค้า';
            FSvCMNSetMsgWarningDialog(tWarningMessage);
            return;
        }

        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var oBrowsePdtSettings = {
                Qualitysearch: [
                    "NAMEPDT",
                    "CODEPDT"
                ],
                PriceType: ["Cost", "tCN_Cost", "Company", "1"],
                //'PriceType'       : ['Pricesell'],
                //'SelectTier'      : ['PDT'],
                SelectTier: ["Barcode"],
                //'Elementreturn'   : ['oetInputTestValue','oetInputTestName'],
                ShowCountRecord: 10,
                NextFunc: "JSvPCKInsertPdtToTemp",
                ReturnType: "M",
                SPL: ["", ""],
                BCH: [$("#oetPCKXthBchFrmCode").val(), $("#oetPCKXthBchFrmCode").val()],
                MER: [$('#oetPCKXthMerchantFrmCode').val(), $('#oetPCKXthMerchantFrmCode').val()],
                SHP: [$('#oetPCKXthShopFrmCode').val(), $('#oetPCKXthShopFrmCode').val()],
                'aAlwPdtType': ['T1', 'T3', 'T4', 'T5', 'T6', 'S2', 'S3', 'S4']
            }

            var tMerFromCode = $('#oetPCKXthMerchantFrmCode').val();
            var bIsMerFromEmpty = (tMerFromCode === '') || (tMerFromCode == undefined);
            if (bIsMerFromEmpty) {
                delete oBrowsePdtSettings.MER;
            }
            var tShpFromCode = $('#oetPCKXthShopFrmCode').val();
            var bIsShpFromEmpty = (tShpFromCode === '') || (tShpFromCode == undefined);
            if (bIsShpFromEmpty) {
                delete oBrowsePdtSettings.SHP;
            }

            $.ajax({
                type: "POST",
                url: "BrowseDataPDT",
                data: oBrowsePdtSettings,
                cache: false,
                timeout: 5000,
                success: function(tResult) {
                    // $(".modal.fade:not(#odvTBBrowseShipAdd,#odvModalDOCPDT,#odvModalWanning,#odvModalInfoMessage,#odvShowOrderColumn,#odvTBPopupApv,#odvModalDelPdtTB)").remove();
                    $("#odvModalDOCPDT").modal({
                        backdrop: "static",
                        keyboard: false
                    });
                    $("#odvModalDOCPDT").modal({
                        show: true
                    });

                    //remove localstorage
                    localStorage.removeItem("LocalItemDataPDT");
                    $("#odvModalsectionBodyPDT").html(tResult);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Display End Of Bill Calc
    function JSxPCKSetEndOfBill(poParams) {}

    //ปริ้นเอกสาร ใบจ่ายโอน - สาขา
    function JSxPCKPrintDoc() {
        var aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch($tBchCode); ?>'
            },
            {
                "DocCode": $('#oetPCKDocNo').val()
            },
            {
                "DocBchCode": '<?= $tBchCode ?>'
            }
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_ALLMPdtBillPickMaterial?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    function FSxPCKCallPageHDDocRef() {
        var tDocNo = "";
        if ($("#ohdPCKRoute").val() == "docPCKEventEdit") {
            tDocNo = $('#oetPCKDocNo').val();
        }
        $.ajax({
            type: "POST",
            url: "docPCKPageHDDocRef",
            data: {
                'ptDocNo': tDocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aResult = JSON.parse(oResult);
                if (aResult['nStaEvent'] == 1) {
                    $('#odvPCKTableHDRef').html(aResult['tViewPageHDRef']);
                    JCNxCloseLoading();
                } else {
                    var tMessageError = aResult['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }


    //กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtPCKAddDocRef').off('click').on('click', function() {
        $('#ofmPCKFormAddDocRef').validate().destroy();
        JSxPCKEventClearValueInFormHDDocRef();
        $('#odvPCKModalAddDocRef').modal('show');
    });

    //เคลียร์ค่า
    function JSxPCKEventClearValueInFormHDDocRef() {
        $('#oetPCKRefDocNo').val('');
        $('#oetPCKRefDocDate').val('');
        $('#oetPCKRefIntDoc').val('');
        $('#oetPCKDocRefIntName').val('');
        $('#oetPCKRefKey').val('');
    }

    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxPCKEventCheckShowHDDocRef() {
        var tPCKRefType = $('#ocbPCKRefType').val();
        if (tPCKRefType == '1') {
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        } else {
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbPCKRefType').off('change').on('change', function() {
        $(this).selectpicker('refresh');
        JSxPCKEventCheckShowHDDocRef();
    });




    //Browse => เอกสารใบรับรถ เอาสินค้าใบรับรถลง Temp
    $('#obtConfirmRefDocPCK').click(function() {
        var tRefIntDocNo = $('.xDocuemntRefInt.active').data('docno');
        var tRefIntDocDate = $('.xDocuemntRefInt.active').data('docdate');
        var tRefIntBchCode = $('.xDocuemntRefInt.active').data('bchcode');

        var nCountCheck = $('.ocbRefIntDocDT:checked').length;
        if (nCountCheck == 0) {
            alert('กรุณาเลือกรายการสินค้า ก่อนทำใบเสนอราคา');
            return;
        } else {
            var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm) {
                return $(this).val();
            }).get();

            $('#odvPCKModalRefIntDoc').modal('hide');


            var tStaVATInOrEx = $('.xDocuemntRefInt.active').data('vatinroex');
            var tCstcode = $('.xDocuemntRefInt.active').data('cstcode');
            var tCstname = $('.xDocuemntRefInt.active').data('cstname');

            var poParams = {
                tCstcode: tCstcode,
                tCstname: tCstname,
                tCstTel: $('.xDocuemntRefInt.active').data('csttel'),
                tCstEmail: $('.xDocuemntRefInt.active').data('cstemail'),
                tAddV2Desc1: $('.xDocuemntRefInt.active').data('cstaddl'),
                tCarRegNo: $('.xDocuemntRefInt.active').data('carregno'),
                tBndName: $('.xDocuemntRefInt.active').data('bndname'),
            };
            JSxPCKSetPanelCustomerAfterJOB1Data(poParams);

            $('#oetPCKRefIntDoc').val(tRefIntDocNo);
            $('#oetPCKRefKey').val('Job2Ord');
            $('#oetPCKRefDocDate').val(tRefIntDocDate);

            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docPCKRefIntDocInsertDTToTemp",
                data: {
                    'tDocNo': $('#oetPCKDocNo').val(),
                    'tAgnCode': $('#oetPCKAgnCode').val(),
                    'tFrmBchCode': $('#oetPCKBchCode').val(),
                    'tRefIntDocNo': tRefIntDocNo,
                    'tRefIntBchCode': tRefIntBchCode,
                    'aSeqNo': aSeqNo,
                    'tOptAddPdt': $('#ocmPCKOptionAddPdt').val(),
                },
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    JCNxCloseLoading();
                    // JSxPCKGetPdtInTmp();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    });

    //Browse => หลังจากเลือกใบรับรถ เเล้วเอาอ้างอิงเอกสารมาใส่
    function JSxPCKSetPanelCustomerAfterJOB1Data(paData) {
        $('#ohdPCKCstCode').val(paData.tCstcode);
        $('#oetPCKCstName').val(paData.tCstname);
        $('#oetPCKCstTel').val((paData.tCstTel == '') ? '-' : paData.tCstTel);
        $('#oetPCKCstMail').val((paData.tCstEmail == '') ? '' : paData.tCstEmail);
        $('#oetPCKCstRegCar').val((paData.tCarRegNo == '') ? '-' : paData.tCarRegNo);
        $('#oetPCKCstCarBrand').val((paData.tBndName == '') ? '-' : paData.tBndName);
        $('#otaPCKCstAddress').val((paData.tAddV2Desc1 == '') ? '-' : paData.tAddV2Desc1);
    }




    //กดยืนยันบันทึกลง Temp
    $('#ofmPCKFormAddDocRef').off('click').on('click', function() {
        $('#ofmPCKFormAddDocRef').validate().destroy();
        $('#ofmPCKFormAddDocRef').validate({
            focusInvalid: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            rules: {
                oetPCKRefIntDoc: {
                    "required": true
                }
            },
            messages: {
                oetPCKRefIntDoc: {
                    "required": 'กรุณากรอกเลขที่เอกสารอ้างอิง'
                }
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                if (element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if (tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function(form) {
                JCNxOpenLoading();

                if ($('#ocbPCKRefType').val() == 1) { //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetPCKRefIntDoc').val();
                } else { //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetPCKRefDocNo').val();
                }

                var tDocRefDate = $('#oetPCKRefDocDate').val()

                $.ajax({
                    type: "POST",
                    url: "docPCKEventAddEditHDDocRef",
                    data: {
                        'ptRefDocNoOld': $('#oetPCKRefDocNoOld').val(),
                        'ptPCKDocNo': $('#oetPCKDocNo').val(),
                        'ptRefType': $('#ocbPCKRefType').val(),
                        'ptRefDocNo': tDocNoRef,
                        'pdRefDocDate': tDocRefDate,
                        'ptRefKey': $('#oetPCKRefKey').val()
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        JSxPCKEventClearValueInFormHDDocRef();
                        $('#odvPCKModalAddDocRef').modal('hide');
                        $('#oetPCKXthRefInt').val(tDocNoRef);
                        $('#oetPCKXthRefIntDate').val(tDocRefDate);

                        JSxPCKGetPdtInTmp();
                        FSxPCKCallPageHDDocRef();
                        JCNxCloseLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    });


    $('#oimPCKBrowseAgn').click(function(e) {
        var tCheckIteminTable = $('#otbPCKDocPdtAdvTableList .xWPdtItem').length;
        if (tCheckIteminTable > 0) {
            $('#ohdPCKTypeChange').val('Agn');
            $('#odvPCKModalChangeData #ospPCKTxtWarningAlert').text('<?php echo language('document/deliveryorder/deliveryorder', 'tDOChangeAgn') ?>');
            $('#odvPCKModalChangeData').modal('show')
        } else {
            e.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oPCKBrowseAgencyOption = oBrowseAgn({
                    'tReturnInputCode': 'oetPCKAgnCode',
                    'tReturnInputName': 'oetPCKAgnName',
                });
                JCNxBrowseData('oPCKBrowseAgencyOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
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
        $('#oetPCKBchCode').val('');
        $('#oetPCKBchName').val('');
        $('#oetPCKUsrCode').val('');
        $('#oetPCKUsrName').val('');
    }


    //ค้นหาสินค้าใน temp
    function JSvDOCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbPCKPdtTable tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    // Function: Pase Text Product Item In Modal Delete
    function JSxPCKTextInModalDelPdtDtTemp() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("PCK_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {} else {
            var tDOTextDocNo = "";
            var tDOTextSeqNo = "";
            var tDOTextPdtCode = "";
            $.each(aArrayConvert[0], function(nKey, aValue) {
                tDOTextDocNo += aValue.tDocNo;
                tDOTextDocNo += " , ";

                tDOTextSeqNo += aValue.tSeqNo;
                tDOTextSeqNo += " , ";

                tDOTextPdtCode += aValue.tPdtCode;
                tDOTextPdtCode += " , ";
            });
            // alert(tDOTextSeqNo)
            $('#odvPCKModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKDocNoDelete').val(tDOTextDocNo);
            $('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKSeqNoDelete').val(tDOTextSeqNo);
            $('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKPdtCodeDelete').val(tDOTextPdtCode);
        }
    }

    // ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxPCKShowButtonDelMutiDtTemp() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("PCK_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
            $("#odvPCKMngDelPdtInTableDT #oliPCKBtnDeleteMulti").addClass("disabled");
        } else {
            var nNumOfArr = aArrayConvert[0].length;
            if (nNumOfArr > 1) {
                $("#odvPCKMngDelPdtInTableDT #oliPCKBtnDeleteMulti").removeClass("disabled");
            } else {
                $("#odvPCKMngDelPdtInTableDT #oliPCKBtnDeleteMulti").addClass("disabled");
            }
        }
    }



    //ลบรายการสินค้าในตาราง DT Temp
    function JSnPCKDelPdtInDTTempSingle(DOEl) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            var tPdtCode = $(DOEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno = $(DOEl).parents("tr.xWPdtItem").attr("data-key");
            $(DOEl).parents("tr.xWPdtItem").remove();
            JSnPCKRemovePdtDTTempSingle(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบรายการสินค้าในตาราง DT Temp
    function JSnPCKRemovePdtDTTempSingle(ptSeqNo, ptPdtCode) {
        var tDODocNo = $("#oetPCKDocNo").val();
        var tDOBchCode = $('#oetPCKBchCode').val();
        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docPCKRemovePdtInDTTmp",
            data: {
                'tBchCode': tDOBchCode,
                'tDocNo': tDODocNo,
                'nSeqNo': ptSeqNo,
                'tPdtCode': ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JCNxLayoutControll();
                    var tCheckIteminTable = $('#otbPCKPdtTable tbody tr').length;
                    if (tCheckIteminTable == 0) {
                        $('#otbPCKPdtTable').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                    }
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                // JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JSnPCKRemovePdtDTTempSingle(ptSeqNo, ptPdtCode)
            }
        });


    } //Functionality: Remove Comma
    function JSoPCKRemoveCommaData(paData) {
        console.log(paData)
        var aTexts = paData.substring(0, paData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }


    // Function: Fucntion Call Delete Multiple Doc DT Temp
    function JSnPCKRemovePdtDTTempMultiple() {
        // JCNxOpenLoading();
        var tDODocNo = $("#oetPCKDocNo").val();
        var tDOBchCode = $('#oetPCKBchCode').val();
        var aDataPdtCode = JSoPCKRemoveCommaData($('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKPdtCodeDelete').val());
        var aDataSeqNo = JSoPCKRemoveCommaData($('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKSeqNoDelete').val());

        for (var i = 0; i < aDataSeqNo.length; i++) {
            $('.xWPdtItemList' + aDataSeqNo[i]).remove();
        }

        $('#odvPCKModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvPCKModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('PCK_LocalItemDataDelDtTemp');
        $('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKDocNoDelete').val('');
        $('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKSeqNoDelete').val('');
        $('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKPdtCodeDelete').val('');
        $('#odvPCKModalDelPdtInDTTempMultiple #ohdConfirmPCKBarCodeDelete').val('');
        setTimeout(function() {
            $('.modal-backdrop').remove();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type: "POST",
            url: "docPCKRemovePdtInDTTmpMulti",
            data: {
                'tBchCode': tDOBchCode,
                'tDocNo': tDODocNo,
                'nSeqNo': aDataSeqNo,
                'tPdtCode': aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {

                var tCheckIteminTable = $('#otbPCKPdtTable tbody tr').length;

                if (tCheckIteminTable == 0) {
                    $('#otbPCKPdtTable').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                // JSxDOCountPdtItems();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                // JCNxResDOnseError(jqXHR, textStatus, errorThrown);
                JSnPCKRemovePdtDTTempMultiple()
            }
        });
    }



    // Function: Function Chack Value LocalStorage
    function JStPCKFindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }
</script>