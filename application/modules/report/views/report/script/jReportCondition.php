<script src="<?php echo base_url(); ?>application/modules/common/assets/js/jquery.mask.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/src/jFormValidate.js"></script>
<script type="text/javascript">
    var tBaseURL = '<?php echo base_url(); ?>';
    var nLangEdits = '<?php echo $this->session->userdata("tLangEdit"); ?>';
    var tMonthDefault = '<?php echo date('m', strtotime('0 month')); ?>'
    var tStaUsrLevel = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';
    var tUsrShpCode = '<?php echo $this->session->userdata("tSesUsrShpCode"); ?>';
    var tUsrShpName = '<?php echo $this->session->userdata("tSesUsrShpName"); ?>';
    var tUsrBchCode = '<?php echo str_replace("'", "", $this->session->userdata("tSesUsrBchCodeMulti")); ?>';
    var tUsrBchName = '<?php echo str_replace("'", "", $this->session->userdata("tSesUsrBchNameMulti")); ?>';

    $(document).ready(function() {

        $('.xCNCheckboxValue').off('click').on('click', function() {
            var bStaCheck = $(this).prop("checked");
            if (bStaCheck === true) {
                var oInput_1 = $(this).parents('.xCNDataCondition').find('td:eq(2)').find('.form-control');
                var oInput_2 = $(this).parents('.xCNDataCondition').find('td:eq(3)').find('.form-control');

                $(oInput_1).val('');
                $(oInput_2).val('');
            }
        });

        var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var nCountBch = "<?php echo $this->session->userdata("nSesUsrBchCount"); ?>";
        var tWhere = "";

        if (nCountBch >= 1) {
            $('#oetRptBchCodeSelect').val(tUsrBchCode);
            $('#oetRptBchNameSelect').val(tUsrBchName);
            $('#oetRptBchStaSelectAll').val('');
            $('#obtRptMultiBrowseBranch').attr("disabled", false);
        }

        // ควบคุม เปิด-ปิด เครื่องจุดขาย
        var bIsSelectedBch = $("#oetRptBchCodeSelect").val() != "";
        if (bIsSelectedBch && nCountBch == 1) {
            $("#obtRptMultiBrowsePos").attr('disabled', false);
        } else {
            $("#obtRptMultiBrowsePos").attr('disabled', true);
        }

        $(".selectpicker-crd-sta-from").selectpicker('refresh');
        $(".selectpicker-crd-sta-to").selectpicker('refresh');

        $('.selectpicker').selectpicker('refresh');

        // Event Date Picker
        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        // Event Date Picker
        $('.xCNYearPicker').datepicker({
            format: "yyyy",
            weekStart: 1,
            orientation: "bottom",
            keyboardNavigation: false,
            viewMode: "years",
            minViewMode: "years"
        });

        $('#ocmRptMonth').val(tMonthDefault);
        $('#ocmRptMonth').selectpicker('refresh');

        //ช่วงเดือน ถึงเดือน
        $('#ocmRptMonthFrom').val(tMonthDefault);
        $('#ocmRptMonthFrom').selectpicker('refresh');
        $('#ocmRptMonthTo').val(tMonthDefault);
        $('#ocmRptMonthTo').selectpicker('refresh');

        // Set Select Box 100 %
        // $('.xWInputGrpMonthFilter .dropdown').css('width','100%');
        // $('.xWInputGrpPriority .dropdown').css('width','100%');
        // $('.xWInputGrpPosType .dropdown').css('width','100%');

        // Click Button Doc Date
        $('#oetRptDocDateFrom').val('<?= date('Y-m-d') ?>').selectpicker('refresh');
        $('#obtRptBrowseDocDateFrom').unbind().click(function() {
            $('#oetRptDocDateFrom').datepicker('show');
        });

        $('#oetRptDocDateTo').val('<?= date('Y-m-d') ?>').selectpicker('refresh');
        $('#obtRptBrowseDocDateTo').unbind().click(function() {
            $('#oetRptDocDateTo').datepicker('show');
        });

        // Click Button Date Start
        $('#obtRptBrowseDateStartFrom').unbind().click(function() {
            $('#oetRptDateStartFrom').datepicker('show');
        });
        $('#obtRptBrowseDateStartTo').unbind().click(function() {
            $('#oetRptDateStartTo').datepicker('show');
        });

        // Click Button Date Expire
        $('#obtRptBrowseDateExpireFrom').unbind().click(function() {
            $('#oetRptDateExpireFrom').datepicker('show');
        });
        $('#obtRptBrowseDateExpireTo').unbind().click(function() {
            $('#oetRptDateExpireTo').datepicker('show');
        });

        // Click Button Year
        $('#obtRptBrowseYearFrom').unbind().click(function() {
            $('#oetRptYearFrom').datepicker('show');
        });
        $('#obtRptBrowseYearTo').unbind().click(function() {
            $('#oetRptYearTo').datepicker('show');
        });

        $('#obtRptBrowseOneDateFrom').unbind().click(function() {
            $('#oetRptOneDateFrom').datepicker('show');
        });

        $('.xWBranchSlt').hide();
        $('.xWShopSlt').hide();
    });

    /*===== Begin Browse Option ======================================================= */
    var oRptBranchOption = function(poReturnInputBch) {
        let tNextFuncNameBch = poReturnInputBch.tNextFuncName;
        let aArgReturnBch = poReturnInputBch.aArgReturn;
        let tInputReturnCodeBch = poReturnInputBch.tReturnInputCode;
        let tInputReturnNameBch = poReturnInputBch.tReturnInputName;
        let oOptionReturnBch = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCodeBch, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnNameBch, "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName: tNextFuncNameBch,
                ArgReturn: aArgReturnBch
            },
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        return oOptionReturnBch;
    };

    // Browse Shop Option
    var oRptShopOption = function(poReturnInputShp) {
        let tShpNextFuncName = poReturnInputShp.tNextFuncName;
        let aShpArgReturn = poReturnInputShp.aArgReturn;
        let tShpInputReturnCode = poReturnInputShp.tReturnInputCode;
        let tShpInputReturnName = poReturnInputShp.tReturnInputName;
        let tShpRptModCode = poReturnInputShp.tRptModCode;
        let tShpRptBranchForm = poReturnInputShp.tRptBranchForm;
        let tShpRptBranchTo = poReturnInputShp.tRptBranchTo;
        let tShpWhereShop = "";
        let tShpWhereShopAndBch = "";

        // Case Report Type POS,VD,LK
        switch (tShpRptModCode) {
            case '001':
                // Report Pos (รานงานการขาย)
                // tShpWhereShop       = " AND (TCNMShop.FTShpStaActive = 1) AND (TCNMShop.FTShpType = 1)";
                // tShpWhereShopAndBch = " AND ((TCNMShop.FTBchCode BETWEEN "+tShpRptBranchForm+" AND "+tShpRptBranchTo+") OR (TCNMShop.FTBchCode BETWEEN "+tShpRptBranchTo+" AND "+tShpRptBranchForm+"))";

                // Report Pos (รานงานการขาย) + Report Vending (รานงานตู้ขายสินค้า)
                tShpWhereShop = " AND (TCNMShop.FTShpStaActive = 1) AND (TCNMShop.FTShpType IN (1,4))";
                tShpWhereShopAndBch = " AND ((TCNMShop.FTBchCode BETWEEN '" + tShpRptBranchForm + "' AND '" + tShpRptBranchTo + "') OR (TCNMShop.FTBchCode BETWEEN '" + tShpRptBranchTo + "' AND '" + tShpRptBranchForm + "'))";
                break;
            case '002':
                // Report Vending (รานงานตู้ขายสินค้า)
                tShpWhereShop = " AND (TCNMShop.FTShpStaActive = 1) AND (TCNMShop.FTShpType = 4)";
                tShpWhereShopAndBch = " AND ((TCNMShop.FTBchCode BETWEEN '" + tShpRptBranchForm + "' AND '" + tShpRptBranchTo + "') OR (TCNMShop.FTBchCode BETWEEN '" + tShpRptBranchTo + "' AND '" + tShpRptBranchForm + "'))";
                break;
            case '003':
                // Report Locker (รานงานตู้ฝากของ)
                tShpWhereShop = " AND (TCNMShop.FTShpStaActive = 1) AND (TCNMShop.FTShpType = 5)";
                tShpWhereShopAndBch = " AND ((TCNMShop.FTBchCode BETWEEN '" + tShpRptBranchForm + "' AND '" + tShpRptBranchTo + "') OR (TCNMShop.FTBchCode BETWEEN '" + tShpRptBranchTo + "' AND '" + tShpRptBranchForm + "'))";
                break;
        }

        if (typeof tRptBranchForm === 'undefined' && typeof tRptBranchTo === 'undefined') {
            // แสดงข้อมูล ร้านค้าทั้งหมดตามประเภทของรายงาน
            var oShopOptionReturn = {
                Title: ['company/shop/shop', 'tSHPTitle'],
                Table: {
                    Master: 'TCNMShop',
                    PK: 'FTShpCode'
                },
                Join: {
                    Table: ['TCNMShop_L', 'TCNMBranch_L'],
                    On: [
                        'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode      AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                        'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode    AND TCNMBranch_L.FNLngID = ' + nLangEdits
                    ]
                },
                Where: {
                    Condition: [tShpWhereShop]
                },
                GrideView: {
                    ColumnPathLang: 'company/shop/shop',
                    ColumnKeyLang: ['tSHPTBBranch', 'tSHPTBCode', 'tSHPTBName'],
                    ColumnsSize: ['15%', '15%', '75%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMBranch_L.FTBchName', 'TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                    DataColumnsFormat: ['', '', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMShop.FDCreateOn DESC,TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
                },
                CallBack: {
                    ReturnType: 'S',
                    Value: [tShpInputReturnCode, "TCNMShop.FTShpCode"],
                    Text: [tShpInputReturnName, "TCNMShop_L.FTShpName"]
                },
                NextFunc: {
                    FuncName: tShpNextFuncName,
                    ArgReturn: aShpArgReturn
                },
                RouteAddNew: 'shop',
                BrowseLev: 1
            };
        } else {
            if (tRptBranchForm == "" && tRptBranchTo == "") {
                // แสดงข้อมูล ร้านค้าทั้งหมดตามประเภทของรายงาน
                var oShopOptionReturn = {
                    Title: ['company/shop/shop', 'tSHPTitle'],
                    Table: {
                        Master: 'TCNMShop',
                        PK: 'FTShpCode'
                    },
                    Join: {
                        Table: ['TCNMShop_L', 'TCNMBranch_L'],
                        On: [
                            'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode      AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                            'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode    AND TCNMBranch_L.FNLngID = ' + nLangEdits
                        ]
                    },
                    Where: {
                        Condition: [tShpWhereShop]
                    },
                    GrideView: {
                        ColumnPathLang: 'company/shop/shop',
                        ColumnKeyLang: ['tSHPTBBranch', 'tSHPTBCode', 'tSHPTBName'],
                        ColumnsSize: ['15%', '15%', '75%'],
                        WidthModal: 50,
                        DataColumns: ['TCNMBranch_L.FTBchName', 'TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                        DataColumnsFormat: ['', '', ''],
                        Perpage: 10,
                        OrderBy: ['TCNMShop.FDCreateOn DESC, TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
                    },
                    CallBack: {
                        ReturnType: 'S',
                        Value: [tShpInputReturnCode, "TCNMShop.FTShpCode"],
                        Text: [tShpInputReturnName, "TCNMShop_L.FTShpName"]
                    },
                    NextFunc: {
                        FuncName: tShpNextFuncName,
                        ArgReturn: aShpArgReturn
                    },
                    RouteAddNew: 'shop',
                    BrowseLev: 1
                };
            } else {
                // แสดงข้อมูลร้านค้า ตามสาขาที่เลือกไว้
                var oShopOptionReturn = {
                    Title: ['company/shop/shop', 'tSHPTitle'],
                    Table: {
                        Master: 'TCNMShop',
                        PK: 'FTShpCode'
                    },
                    Join: {
                        Table: ['TCNMShop_L', 'TCNMBranch_L'],
                        On: [
                            'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode      AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                            'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode    AND TCNMBranch_L.FNLngID = ' + nLangEdits
                        ]
                    },
                    Where: {
                        Condition: [tShpWhereShop + tShpWhereShopAndBch]
                    },
                    GrideView: {
                        ColumnPathLang: 'company/shop/shop',
                        ColumnKeyLang: ['tSHPTBBranch', 'tSHPTBCode', 'tSHPTBName'],
                        ColumnsSize: ['15%', '15%', '75%'],
                        WidthModal: 50,
                        DataColumns: ['TCNMBranch_L.FTBchName', 'TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                        DataColumnsFormat: ['', '', ''],
                        Perpage: 10,
                        OrderBy: ['TCNMShop.FDCreateOn DESC, TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
                    },
                    CallBack: {
                        ReturnType: 'S',
                        Value: [tShpInputReturnCode, "TCNMShop.FTShpCode"],
                        Text: [tShpInputReturnName, "TCNMShop_L.FTShpName"]
                    },
                    NextFunc: {
                        FuncName: tShpNextFuncName,
                        ArgReturn: aShpArgReturn
                    },
                    RouteAddNew: 'shop',
                    BrowseLev: 1
                }
            }
        }
        return oShopOptionReturn;
    };

    // Browse Pos Option
    var oRptPosOption = function(poReturnInputPos) {
        let tPosNextFuncName = poReturnInputPos.tNextFuncName;
        let aPosArgReturn = poReturnInputPos.aArgReturn;
        let tPosInputReturnCode = poReturnInputPos.tReturnInputCode;
        let tPosInputReturnName = poReturnInputPos.tReturnInputName;
        let tPosRptModCode = poReturnInputPos.tRptModCode;
        let tPosRptShopForm = poReturnInputPos.tRptShopForm;
        let tPosRptShopTo = poReturnInputPos.tRptShopTo;
        let oPosJoinTable = {};
        let tPosWherePos = "";
        let tPosWherePosAndShop = "";
        let tPosOrderByCase = "";
        // // Case Report Type POS,VD,LK
        // switch(tPosRptModCode){
        //     case '001':
        //         // Report Pos (รานงานการขาย)
        //         tPosWherePos    = " AND (TCNMPos.FTPosStaUse = 1) AND (TCNMPos.FTPosType NOT IN(4,5))";
        //         tPosOrderByCase = " TCNMPos.FTPosCode ASC"
        //     break;
        //     case '002':
        //         // Report Vending (รานงานตู้ขายสินค้า)
        //         oPosJoinTable   = {
        //             Table: ['TVDMPosShop'],
        //             On: [
        //                 'TCNMPos.FTPosCode = TVDMPosShop.FTPosCode',
        //             ]
        //         };
        //         tPosWherePos        = " AND (TCNMPos.FTPosStaUse = 1) AND (TCNMPos.FTPosType = 4)";
        //         tPosWherePosAndShop = " AND ((TVDMPosShop.FTShpCode BETWEEN "+tPosRptShopForm+" AND "+tPosRptShopTo+") OR (TVDMPosShop.FTShpCode BETWEEN "+tPosRptShopTo+" AND "+tPosRptShopForm+"))";
        //         tPosOrderByCase     = " TCNMPos.FTPosCode ASC,TVDMPosShop.FTPosCode ASC"
        //     break;
        //     case '003':
        //         // Report Locker (รานงานตู้ฝากของ)
        //         oPosJoinTable  = {
        //             Table: ['TRTMShopPos'],
        //             On: [
        //                 'TCNMPos.FTPosCode = TRTMShopPos.FTPosCode',
        //             ]
        //         };
        //         tPosWherePos        = " AND (TCNMPos.FTPosStaUse = 1) AND (TCNMPos.FTPosType = 5)";
        //         tPosWherePosAndShop = " AND ((TRTMShopPos.FTShpCode BETWEEN "+tPosRptShopForm+" AND "+tPosRptShopTo+") OR (TRTMShopPos.FTShpCode BETWEEN "+tPosRptShopTo+" AND "+tPosRptShopForm+"))";
        //         tPosOrderByCase     = " TCNMPos.FTPosCode ASC,TRTMShopPos.FTPosCode ASC"
        //     break;
        // }

        // if(typeof(tPosRptShopForm) == 'undefined' && typeof(tPosRptShopTo) == 'undefined'){
        // เกิดขึ้นในกรณีที่ไม่มีปุ่ม Input Shop From || Input Shop To
        var oPosOptionReturn = {
            Title: ["pos/salemachine/salemachine", "tPOSTitle"],
            Table: {
                Master: 'TCNMPos',
                PK: 'FTPosCode'
            },
            // Where   : {
            //     Condition : [tPosWherePos]
            // },
            GrideView: {
                ColumnPathLang: 'pos/salemachine/salemachine',
                ColumnKeyLang: ['tPOSCode', 'tPOSRegNo'],
                ColumnsSize: ['40%', '50%'],
                WidthModal: 50,
                DataColumns: ['TCNMPos.FTPosCode', 'TCNMPos.FTPosRegNo'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPos.FDCreateOn DESC, TCNMPos.FTPosCode ASC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPosInputReturnCode, "TCNMPos.FTPosCode"],
                Text: [tPosInputReturnName, "TCNMPos.FTPosCode"]
            },
            NextFunc: {
                FuncName: tPosNextFuncName,
                ArgReturn: aPosArgReturn
            },
            RouteAddNew: 'salemachine',
            BrowseLev: 1,
        };
        // }else{
        //     if((typeof(tPosRptShopForm) != 'undefined' && tPosRptShopForm == "") && (typeof(tPosRptShopTo) != 'undefined' && tPosRptShopTo == "")){
        //         // เกิดขึ้นในกรณีที่ไม่ได้เลือกร้านค้าต้องแสดงทุกเครื่องจุดขายตาม Type ของรายงาน
        //         var oPosOptionReturn    = {
        //             Title   : ["pos/salemachine/salemachine","tPOSTitle"],
        //             Table   : { Master:'TCNMPos', PK:'FTPosCode'},
        //             Where   : {
        //                 Condition : [tPosWherePos]
        //             },
        //             GrideView   : {
        //                 ColumnPathLang      : 'pos/salemachine/salemachine',
        //                 ColumnKeyLang       : ['tPOSCode','tPOSRegNo'],
        //                 ColumnsSize         : ['40%','50%'],
        //                 WidthModal          : 50,
        //                 DataColumns         : ['TCNMPos.FTPosCode','TCNMPos.FTPosRegNo'],
        //                 DataColumnsFormat   : ['', ''],
        //                 Perpage             : 10,
        //                 OrderBy             : ['TCNMPos.FTPosCode ASC'],
        //             },
        //             CallBack    : {
        //                 ReturnType  : 'S',
        //                 Value       : [tPosInputReturnCode,"TCNMPos.FTPosCode"],
        //                 Text        : [tPosInputReturnName,"TCNMPos.FTPosCode"]
        //             },
        //             NextFunc : {
        //                 FuncName    : tPosNextFuncName,
        //                 ArgReturn   : aPosArgReturn
        //             },
        //             RouteAddNew: 'salemachine',
        //             BrowseLev: 1,
        //         };
        //     }else{
        //         // เกิดขึ้นในกรณีที่มีการเลือกร้านค้าต้องแสดงเฉพาะ Pos ของร้าค้านั้นๆ
        //         var oPosOptionReturn    = {
        //             Title   : ["pos/salemachine/salemachine","tPOSTitle"],
        //             Table   : { Master:'TCNMPos', PK:'FTPosCode'},
        //             Join    : oPosJoinTable,
        //             Where   : {
        //                 Condition : [tPosWherePos+tPosWherePosAndShop]
        //             },
        //             GrideView   : {
        //                 ColumnPathLang      : 'pos/salemachine/salemachine',
        //                 ColumnKeyLang       : ['tPOSCode','tPOSRegNo'],
        //                 ColumnsSize         : ['40%','50%'],
        //                 WidthModal          : 50,
        //                 DataColumns         : ['TCNMPos.FTPosCode','TCNMPos.FTPosRegNo'],
        //                 DataColumnsFormat   : ['', ''],
        //                 Perpage             : 10,
        //                 OrderBy             : [tPosOrderByCase],
        //             },
        //             CallBack    : {
        //                 ReturnType  : 'S',
        //                 Value       : [tPosInputReturnCode,"TCNMPos.FTPosCode"],
        //                 Text        : [tPosInputReturnName,"TCNMPos.FTPosCode"]
        //             },
        //             NextFunc : {
        //             FuncName    : tPosNextFuncName,
        //             ArgReturn   : aPosArgReturn
        //             },
        //             RouteAddNew: 'salemachine',
        //             BrowseLev: 1,
        //         };
        //     }
        // }
        return oPosOptionReturn;
    };

    // Browse Merchant Option
    var oRptMerChantOption = function(poReturnInputMer) {
        let tMerInputReturnCode = poReturnInputMer.tReturnInputCode;
        let tMerInputReturnName = poReturnInputMer.tReturnInputName;
        let tMerNextFuncName = poReturnInputMer.tNextFuncName;
        let aMerArgReturn = poReturnInputMer.aArgReturn;
        let oMerOptionReturn = {
            Title: ['company/merchant/merchant', 'tMerchantTitle'],
            Table: {
                Master: 'TCNMMerchant',
                PK: 'FTMerCode'
            },
            Join: {
                Table: ['TCNMMerchant_L'],
                On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'company/merchant/merchant',
                ColumnKeyLang: ['tMerCode', 'tMerName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMMerchant.FTMerCode', 'TCNMMerchant_L.FTMerName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMMerchant.FDCreateOn DESC, TCNMMerchant.FTMerCode ASC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tMerInputReturnCode, "TCNMMerchant.FTMerCode"],
                Text: [tMerInputReturnName, "TCNMMerchant_L.FTMerName"],
            },
            NextFunc: {
                FuncName: tMerNextFuncName,
                ArgReturn: aMerArgReturn
            },
            RouteAddNew: 'merchant',
            BrowseLev: 1,
        };
        return oMerOptionReturn;
    }




    // เลือกบัญชีที่นำเข้า
    $("#obtDepositBrowseAccountTo").click(function() {
        // option BookBank
        window.oDepositBrowseAccountTo = {
            Title: ['BookBank/BookBank/BookBank', 'tBBKTitle'],
            Table: {
                Master: 'TFNMBookBank',
                PK: 'FTBbkCode'
            },
            Join: {
                Table: ['TFNMBookBank_L'],
                On: ['TFNMBookBank.FTBbkCode = TFNMBookBank_L.FTBbkCode AND TFNMBookBank_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [
                    function() {
                        var tSQL = " AND TFNMBookBank.FTBchCode = '" + $("#oetDepositBchCode").val() + "' AND TFNMBookBank.FTMerCode = '" + $("#oetDepositMchCode").val() + "'";
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'BookBank/BookBank/BookBank',
                ColumnKeyLang: ['tBBKTableCode', 'tBBKTableNameBookbank'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TFNMBookBank.FTBbkCode', 'TFNMBookBank_L.FTBbkName', 'TFNMBookBank.FTBbkType', 'TFNMBookBank.FTBbkAccNo'],
                DataColumnsFormat: ['', '', '', ''],
                DisabledColumns: [2, 3],
                Perpage: 10,
                OrderBy: ['TFNMBookBank.FDCreateOn DESC'],
                // SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetDepositAccountCodeTo", "TFNMBookBank.FTBbkCode"],
                Text: ["oetDepositAccountNameTo", "TFNMBookBank_L.FTBbkName"],
            },
            NextFunc: {
                FuncName: 'JSxDepositCallbackAccountTo',
                ArgReturn: ['FTBbkCode', 'FTBbkName', 'FTBbkType', 'FTBbkAccNo']
            },
            BrowseLev: 1,
            //DebugSQL : true
        }
        JCNxBrowseData('oDepositBrowseAccountTo');
    });


    // Browse Merchant Single Option
    var oRptSingleMerOption = function(poReturnInputSingleMer) {
        let tMerSingleInputReturnCode = poReturnInputSingleMer.tReturnInputCode;
        let tMerSingleInputReturnName = poReturnInputSingleMer.tReturnInputName;
        let oMerSingleOptionReturn = {
            Title: ['company/merchant/merchant', 'tMerchantTitle'],
            Table: {
                Master: 'TCNMMerchant',
                PK: 'FTMerCode'
            },
            Join: {
                Table: ['TCNMMerchant_L'],
                On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'company/merchant/merchant',
                ColumnKeyLang: ['tMerCode', 'tMerName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMMerchant.FTMerCode', 'TCNMMerchant_L.FTMerName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMMerchant.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tMerSingleInputReturnCode, "TCNMMerchant.FTMerCode"],
                Text: [tMerSingleInputReturnName, "TCNMMerchant_L.FTMerName"],
            },
            RouteAddNew: 'merchant',
            BrowseLev: 1,
        };
        return oMerSingleOptionReturn;
    }

    // Browse Employee Option
    var oRptEmpOption = function(poReturnInputEmp) {
        let tEmpInputReturnCode = poReturnInputEmp.tReturnInputCode;
        let tEmpInputReturnName = poReturnInputEmp.tReturnInputName;
        let oEmpOptionReturn = {
            Title: ['payment/card/card', 'tCRDHolderIDTiltle'],
            Table: {
                Master: 'TFNMCard',
                PK: 'FTCrdHolderID'
            },
            GrideView: {
                ColumnPathLang: 'payment/card/card',
                ColumnKeyLang: ['tCRDHolderIDCode', ],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TFNMCard.FTCrdHolderID'],
                DisabledColumns: [],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TFNMCard.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                StaSingItem: '1',
                Value: [tEmpInputReturnCode, "TFNMCard.FTCrdHolderID"],
                Text: [tEmpInputReturnName, "TFNMCard.FTCrdHolderID"]
            },
            RouteAddNew: '',
            BrowseLev: 1,
        };
        return oEmpOptionReturn;
    }

    // Browse Recive Option
    var oRptReciveOption = function(poReturnInputRcv) {
        let tAgncyCode = poReturnInputRcv.tAgncyCode;
        let tRcvInputReturnCode = poReturnInputRcv.tReturnInputCode;
        let tRcvInputReturnName = poReturnInputRcv.tReturnInputName;
        let tRcvNextFuncName = poReturnInputRcv.tNextFuncName;
        let aRcvArgReturn = poReturnInputRcv.aArgReturn;
        let tWhereCondition = '';

        if (tAgncyCode != '') {
            tWhereCondition += " AND ( TFNMRcv.FTAgnCode = '" + tAgncyCode + "' OR ISNULL(TFNMRcv.FTAgnCode,'') ='' ) ";
        }

        let oRcvOptionReturn = {
            Title: ['payment/recive/recive', 'tRCVTitle'],
            Table: {
                Master: 'TFNMRcv',
                PK: 'FTRcvCode'
            },
            Join: {
                Table: ['TFNMRcv_L'],
                On: ['TFNMRcv.FTRcvCode = TFNMRcv_L.FTRcvCode AND TFNMRcv_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tWhereCondition]
            },
            GrideView: {
                ColumnPathLang: 'payment/recive/recive',
                ColumnKeyLang: ['tRCVCode', 'tRCVName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TFNMRcv.FTRcvCode', 'TFNMRcv_L.FTRcvName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TFNMRcv.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tRcvInputReturnCode, "TFNMRcv.FTRcvCode"],
                Text: [tRcvInputReturnName, "TFNMRcv_L.FTRcvName"],
            },
            NextFunc: {
                FuncName: tRcvNextFuncName,
                ArgReturn: aRcvArgReturn
            },
            RouteAddNew: 'Payment',
            BrowseLev: 1,
        };
        return oRcvOptionReturn;
    }

    // Browse Product Option
    var oRptProductOption = function(poReturnInputPdt) {
        let tPdtInputReturnCode = poReturnInputPdt.tReturnInputCode;
        let tPdtInputReturnName = poReturnInputPdt.tReturnInputName;
        let tPdtNextFuncName = poReturnInputPdt.tNextFuncName;
        let aPdtArgReturn = poReturnInputPdt.aArgReturn;
        let tCondition = '';
        var tSesUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>'
        tCondition = " AND TCNMPdt.FTPdtForSystem = 1 AND TCNMPdt.FTPdtStaActive = 1 ";

        let tBchCodeSess = $('#oetRptBchCodeSelect').val();
        let tAgnCode = $('#oetSpcAgncyCode').val();
        let tBchcode = tBchCodeSess.replace(/,/g, "','");
        // if (tBchCodeSess != '' && tBchCodeSess != undefined) {
        //     let tAgnCode = $('#oetSpcAgncyCode').val();
        //     tBchcode    = tBchCodeSess.replace(/,/g, "','");
        //   //  tCondition  += " AND ( ( TCNMPdtSpcBch.FTAgnCode = '"+tAgnCode+"' ) OR TCNMPdtSpcBch.FTBchCode IN ('" + tBchcode + "') OR ( TCNMPdtSpcBch.FTBchCode IS NULL OR TCNMPdtSpcBch.FTBchCode ='' )  )";
        // }
        if (tSesUsrLevel != 'HQ') {

            tCondition += " AND ((TCNMPdtSpcBch.FTAgnCode = '" + tAgnCode + "')	OR TCNMPdtSpcBch.FTBchCode IN ('" + tBchcode + "') ";
            tCondition += " OR (ISNULL(TCNMPdtSpcBch.FTBchCode,'') = '' AND TCNMPdtSpcBch.FTAgnCode = '" + tAgnCode + "'	)";
            tCondition += " OR ISNULL(TCNMPdtSpcBch.FTAgnCode,'') = '' )";

        }


        let oPdtOptionReturn = {
            Title: ["product/product/product", "tPDTTitle"],
            Table: {
                Master: "TCNMPdt",
                PK: "FTPdtCode"
            },
            Join: {
                Table: ["TCNMPdt_L", 'TCNMPdtSpcBch'],
                On: [
                    'TCNMPdt.FTPdtCode = TCNMPdt_L.FTPdtCode AND TCNMPdt_L.FNLngID = ' + nLangEdits,
                    'TCNMPdtSpcBch.FTPdtCode = TCNMPdt.FTPdtCode'
                ]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'product/product/product',
                ColumnKeyLang: ['tPDTCode', 'tPDTName'],
                DataColumns: ['TCNMPdt.FTPdtCode', 'TCNMPdt_L.FTPdtName'],
                DataColumnsFormat: ['', ''],
                ColumnsSize: ['15%', '75%'],
                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TCNMPdt.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPdtInputReturnCode, "TCNMPdt.FTPdtCode"],
                Text: [tPdtInputReturnName, "TCNMPdt_L.FTPdtName"]
            },
            NextFunc: {
                FuncName: tPdtNextFuncName,
                ArgReturn: aPdtArgReturn
            },
            RouteAddNew: 'product',
            BrowseLev: 1
        };
        return oPdtOptionReturn;
    }

    // Browse Product Type Option
    var oRptPdtTypeOption = function(poReturnInputPty) {
        let tPtyInputReturnCode = poReturnInputPty.tReturnInputCode;
        let tPtyInputReturnName = poReturnInputPty.tReturnInputName;
        let tPtyNextFuncName = poReturnInputPty.tNextFuncName;
        let aPtyArgReturn = poReturnInputPty.aArgReturn;
        let tCondition = '';
        let tAgnCode = $('#oetSpcAgncyCode').val();
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMPdtType.FTAgnCode = '" + tAgnCode + "' ";
        }

        let oPtyOptionReturn = {
            Title: ['product/pdttype/pdttype', 'tPTYTitle'],
            Table: {
                Master: 'TCNMPdtType',
                PK: 'FTPtyCode'
            },
            Join: {
                Table: ['TCNMPdtType_L'],
                On: ['TCNMPdtType_L.FTPtyCode = TCNMPdtType.FTPtyCode AND TCNMPdtType_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtType.FTPtyCode', 'TCNMPdtType_L.FTPtyName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtType.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPtyInputReturnCode, "TCNMPdtType.FTPtyCode"],
                Text: [tPtyInputReturnName, "TCNMPdtType_L.FTPtyName"]
            },
            NextFunc: {
                FuncName: tPtyNextFuncName,
                ArgReturn: aPtyArgReturn
            },
            RouteAddNew: 'pdttype',
            BrowseLev: 1
        };
        return oPtyOptionReturn;
    }

    // Option Product Group Option
    var oRptPdtGrpOption = function(poReturnInputPgp) {
        let tPgpNextFuncName = poReturnInputPgp.tNextFuncName;
        let aPgpArgReturn = poReturnInputPgp.aArgReturn;
        let tPgpInputReturnCode = poReturnInputPgp.tReturnInputCode;
        let tPgpInputReturnName = poReturnInputPgp.tReturnInputName;
        let tCondition = '';
        let tAgnCode = $('#oetSpcAgncyCode').val();
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMPdtGrp.FTAgnCode = '" + tAgnCode + "' ";
        }

        let oPgpOptionReturn = {
            Title: ['product/pdtgroup/pdtgroup', 'tPGPTitle'],
            Table: {
                Master: 'TCNMPdtGrp',
                PK: 'FTPgpChain'
            },
            Join: {
                Table: ['TCNMPdtGrp_L'],
                On: ['TCNMPdtGrp_L.FTPgpChain = TCNMPdtGrp.FTPgpChain AND TCNMPdtGrp_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtGrp.FTPgpChain', 'TCNMPdtGrp_L.FTPgpName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtGrp.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPgpInputReturnCode, "TCNMPdtGrp.FTPgpChain"],
                Text: [tPgpInputReturnName, "TCNMPdtGrp_L.FTPgpName"]
            },
            NextFunc: {
                FuncName: tPgpNextFuncName,
                ArgReturn: aPgpArgReturn
            },
        };
        return oPgpOptionReturn;
    }

    // Option Warehouse Option
    var oRptWarehouseOption = function(poReturnInputWah) {
        var tWahInputReturnCode = poReturnInputWah.tReturnInputCode;
        var tWahInputReturnName = poReturnInputWah.tReturnInputName;
        var tWahNextFuncName = poReturnInputWah.tNextFuncName;
        var aWahArgReturn = poReturnInputWah.aArgReturn;
        var tWahWhereCondition = poReturnInputWah.tWhereCondition;
        var oWahOptionReturn = {
            Title: ["company/warehouse/warehouse", "tWAHTitle"],
            Table: {
                Master: "TCNMWaHouse",
                PK: "FTWahCode"
            },
            Join: {
                Table: ["TCNMWaHouse_L"],
                On: ["TCNMWaHouse.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse_L.FNLngID = '" + nLangEdits + "'"]
            },
            Where: {
                Condition: [tWahWhereCondition]
            },
            GrideView: {
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWahBrowseBchCode', 'tWahCode', 'tWahName'],
                DataColumns: ['TCNMWaHouse.FTBchCode', 'TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['', '', ''],
                ColumnsSize: ['15%', '15%', '70%'],
                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TCNMWaHouse.FTBchCode DESC, TCNMWaHouse.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tWahInputReturnCode, "TCNMWaHouse.FTWahCode"],
                Text: [tWahInputReturnName, "TCNMWaHouse_L.FTWahName"]
            },
            NextFunc: {
                FuncName: tWahNextFuncName,
                ArgReturn: aWahArgReturn
            },
            RouteAddNew: 'warehouse',
            BrowseLev: 1
        };
        return oWahOptionReturn;
    }

    // Option Courier Option
    var oRptCourierOption = function(poReturnInputCry) {
        let tCryInputReturnCode = poReturnInputCry.tReturnInputCode;
        let tCryInputReturnName = poReturnInputCry.tReturnInputName;
        let tCryNextFuncName = poReturnInputCry.tNextFuncName;
        let aCryArgReturn = poReturnInputCry.aArgReturn;
        let tCryWhereCondition = poReturnInputCry.tWhereCondition;
        let oCryOptionReturn = {
            Title: ["courier/courier/courier", "tCRYTitle"],
            Table: {
                Master: "TCNMCourier",
                PK: "FTCryCode"
            },
            Join: {
                Table: ["TCNMCourier_L"],
                On: ["TCNMCourier.FTCryCode = TCNMCourier_L.FTCryCode AND TCNMCourier_L.FNLngID = '" + nLangEdits + "'"]
            },
            Where: {
                Condition: [tCryWhereCondition]
            },
            GrideView: {
                ColumnPathLang: 'courier/courier/courier',
                ColumnKeyLang: ['tCRYCode', 'tCRYName'],
                DataColumns: ['TCNMCourier.FTCryCode', 'TCNMCourier_L.FTCryName'],
                DataColumnsFormat: ['', ''],
                ColumnsSize: ['15%', '75%'],
                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TCNMCourier.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tCryInputReturnCode, "TCNMCourier.FTCryCode"],
                Text: [tCryInputReturnName, "TCNMCourier_L.FTCryName"]
            },
            NextFunc: {
                FuncName: tCryNextFuncName,
                ArgReturn: aCryArgReturn
            },
            RouteAddNew: 'courier',
            BrowseLev: 1
        };
        return oCryOptionReturn;
    }

    // Option Rack Option
    var oRptRackOption = function(poReturnInputRak) {
        let tRakInputReturnCode = poReturnInputRak.tReturnInputCode;
        let tRakInputReturnName = poReturnInputRak.tReturnInputName;
        let tRakNextFuncName = poReturnInputRak.tNextFuncName;
        let aRakArgReturn = poReturnInputRak.aArgReturn;
        let oRakOptionReturn = {
            Title: ['company/smartlockerlayout/smartlockerlayout', 'tSMLLayoutTitleGroup'],
            Table: {
                Master: 'TRTMShopRack',
                PK: 'FTRakCode',
                PKName: 'FTRakCode'
            },
            Join: {
                Table: ['TRTMShopRack_L'],
                On: ['TRTMShopRack_L.FTRakCode = TRTMShopRack.FTRakCode AND TRTMShopRack_L.FNLngID = ' + nLangEdits, ]
            },
            GrideView: {
                ColumnPathLang: 'company/smartlockerlayout/smartlockerlayout',
                ColumnKeyLang: ['tBrowseRackCode', 'tBrowseRackName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TRTMShopRack.FTRakCode', 'TRTMShopRack_L.FTRakName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TRTMShopRack.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tRakInputReturnCode, "TRTMShopRack.FTRakCode"],
                Text: [tRakInputReturnName, "TRTMShopRack_L.FTRakName"],
            },
            NextFunc: {
                FuncName: tRakNextFuncName,
                ArgReturn: aRakArgReturn
            },
            RouteAddNew: 'rack',
            BrowseLev: 1
        }
        return oRakOptionReturn;
    }

    // Option Card Option
    var oRptBrowseCardOption = function(poCardReturnInput) {
        var tSesAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode") ?>';
        if (tSesAgnCode != '') {
            tWhereAngCode = 'AND TFNMCard.FTAgnCode = ' + tSesAgnCode;
        } else {
            tWhereAngCode = '';
        }
        let tInputReturnCardCode = poCardReturnInput.tReturnInputCardCode;
        let tInputReturnCardName = poCardReturnInput.tReturnInputCardName;
        let tNextFuncCardName = poCardReturnInput.tNextFuncCardName;
        let aArgReturnCard = poCardReturnInput.aArgCardReturn;
        let oOptionReturnCashierFrom = {
            Title: ['payment/card/card', 'tCRDTitle'],
            Table: {
                Master: 'TFNMCard',
                PK: 'FTCrdCode'
            },
            Join: {
                Table: ['TFNMCard_L'],
                On: ['TFNMCard_L.FTCrdCode = TFNMCard.FTCrdCode AND TFNMCard_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tWhereAngCode]
            },
            GrideView: {
                ColumnPathLang: 'payment/card/card',
                ColumnKeyLang: ['tCRDTBCode', 'tCRDTBName', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TFNMCard.FTCrdCode', 'TFNMCard_L.FTCrdName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TFNMCard.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCardCode, "TFNMCard.FTCrdCode"],
                Text: [tInputReturnCardName, "TFNMCard.FTCrdCode"],
            },
            NextFunc: {
                FuncName: tNextFuncCardName,
                ArgReturn: aArgReturnCard
            },
        }
        return oOptionReturnCashierFrom;
    }

    // Option Cashier
    var oRptBrowseCashierFrom = function(poCashierReturnInput) {
        let tReturnInputCashierCode = poCashierReturnInput.tReturnInputCashierCode;
        let tReturnInputCashierName = poCashierReturnInput.tReturnInputCashierName;
        let tNextFuncName = poCashierReturnInput.tNextFuncName;
        let aArgReturn = poCashierReturnInput.aArgReturn;

        let tWhereFilter = "";

        let tBchCodeFrom = poCashierReturnInput.tBchCodeFrom;
        let tBchCodeTo = poCashierReturnInput.tBchCodeTo;
        let tShpCodeFrom = poCashierReturnInput.tShpCodeFrom;
        let tShpCodeTo = poCashierReturnInput.tShpCodeTo;
        let tBchCodeSelect = poCashierReturnInput.tBchCodeSelect;

        let tAgnCode = $('#oetSpcAgncyCode').val();
        if (tAgnCode != '' && tAgnCode != undefined) {
            tWhereFilter += " AND TCNTUsrGroup.FTAgnCode = '" + tAgnCode + "' OR ISNULL(TCNTUsrGroup.FTAgnCode,'') ='' ";
        }

        let oOptionReturnCashierFrom = {
            Title: ['report/report/report', 'tUsrCashier'],
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
                Condition: [tWhereFilter]
            },
            GrideView: {
                ColumnPathLang: 'report/report/report',
                ColumnKeyLang: ['tUsrCashierCode', 'tUsrCashierName', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMUser.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tReturnInputCashierCode, "TCNMUser.FTUsrCode"],
                Text: [tReturnInputCashierName, "TCNMUser_L.FTUsrName"],
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
        }
        return oOptionReturnCashierFrom;
    }

    // Option bbk Option
    var oRptBrowseBBk = function(BbkReturnInput) {
        let tBbkNextFuncName = BbkReturnInput.tNextFuncName;
        let tBbkInputReturnBbkCode = BbkReturnInput.tReturnInputBbkCode;
        let tBbkInputReturnBbkName = BbkReturnInput.tReturnInputBbkName;
        let aArgBbkReturn = BbkReturnInput.aArgReturnBbk;

        let tBchCodeSess = $('#oetRptBchCodeSelect').val();
        let tBchcode = tBchCodeSess.replace(/,/g, "','");
        let tWhereFilter = '';
        if (tBchCodeSess != '' && tBchCodeSess != undefined) {
            tWhereFilter += " AND TFNMBookBank.FTBchCode IN ('" + tBchcode + "')";
        }

        let oOptionReturnBbk = {
            Title: ['payment/card/card', 'tCRDTitle'],
            Table: {
                Master: 'TFNMBookBank',
                PK: 'FTBbkCode'
            },
            Join: {
                Table: ['TFNMBookBank_L'],
                On: ['TFNMBookBank_L.FTBbkCode = TFNMBookBank.FTBbkCode AND TFNMBookBank_L.FTBchCode = TFNMBookBank.FTBchCode AND TFNMBookBank_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tWhereFilter]
            },
            GrideView: {
                ColumnPathLang: 'payment/card/card',
                ColumnKeyLang: ['tCRDTBCode', 'tCRDTBName', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TFNMBookBank.FTBbkCode', 'TFNMBookBank.FTBbkAccNo'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TFNMBookBank.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tBbkInputReturnBbkCode, "TFNMBookBank.FTBbkCode"],
                Text: [tBbkInputReturnBbkName, "TFNMBookBank.FTBbkCode"],
            },
            NextFunc: {
                FuncName: tBbkNextFuncName,
                ArgReturn: aArgBbkReturn
            },
        }
        return oOptionReturnBbk;
    }

    //Option Browse
    var oBrowseSpcAgncy = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tBchCodeWhere = poReturnInput.tBchCodeWhere;

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
                OrderBy: ['TCNMAgency.FTAgnCode DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            },
            RouteAddNew: 'agency',
            BrowseLev: 1,
            NextFunc: {
                FuncName: 'JSxClearBrowseConditionSpcAgn',
                ArgReturn: ['FTAgnCode']
            }
        }
        return oOptionReturn;
    }

    // เลเวลของลูกค้า
    var oRPCBrowseCstLevelOption = function(poReturnInputCard) {
        let tInputReturnCode = poReturnInputCard.tReturnInputCode;
        let tInputReturnName = poReturnInputCard.tReturnInputName;
        let tNextFuncName = poReturnInputCard.tNextFuncName;
        let aArgReturn = poReturnInputCard.aArgReturn;

        let tCondition = '';
        let tAgnCode = $('#oetSpcAgncyCode').val();
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMCstLev.FTAgnCode = '" + tAgnCode + "' OR ISNULL(TCNMCstLev.FTAgnCode,'') ='' ";
        }

        let oCstOptionReturn = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {
                Master: 'TCNMCstLev',
                PK: 'FTClvCode'
            },
            Join: {
                Table: ['TCNMCstLev_L'],
                On: ['TCNMCstLev_L.FTClvCode = TCNMCstLev.FTClvCode AND TCNMCstLev_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTLelvel', 'tCSTClv', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMCstLev.FTClvCode', 'TCNMCstLev_L.FTClvName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMCstLev.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMCstLev.FTClvCode"],
                Text: [tInputReturnName, "TCNMCstLev_L.FTClvName"],
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            }
        }
        return oCstOptionReturn;
    }

    // ทะเบียนรถ
    var oRPCBrowseCarRegNoOption = function(poReturnInputCard) {
        let tInputReturnCode = poReturnInputCard.tReturnInputCode;
        let tInputReturnName = poReturnInputCard.tReturnInputName;
        let tNextFuncName = poReturnInputCard.tNextFuncName;
        let aArgReturn = poReturnInputCard.aArgReturn;

        let oCstOptionReturn = {
            Title: ["document/bookingorder/bookingorder", "tTWXCstCar"],
            Table: {
                Master: "TSVMCar",
                PK: "FTCarCode"
            },
            Join: {

                Table: ["TSVMCarInfo_L"],
                On: ["TSVMCar.FTCarBrand = TSVMCarInfo_L.FTCaiCode AND TSVMCarInfo_L.FNLngID = '" + nLangEdits + "'"],

                Table: ["TSVMCarInfo_L", "TCNMCst_L"],
                On: ["TSVMCar.FTCarBrand = TSVMCarInfo_L.FTCaiCode AND TSVMCarInfo_L.FNLngID = '" + nLangEdits + "'",
                    "TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = " + nLangEdits
                ]

            },
            GrideView: {
                ColumnPathLang: 'document/bookingorder/bookingorder',

                ColumnKeyLang: ['tTWXCstCarCode', 'tTWXCstCarRegNo'],
                DataColumns: ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo'],
                DataColumnsFormat: ['', '', ''],
                ColumnsSize: ['15%', '35%'],

                ColumnKeyLang: ['tTWXCstCarCode', 'tTWXCstCarRegNo', 'tTWXCstCarOwner'],
                DataColumns: ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat: ['', '', ''],
                ColumnsSize: ['15%', '15%', '60%'],

                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TSVMCar.FTCarCode ASC'],
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TSVMCar.FTCarCode"],
                Text: [tInputReturnName, "TSVMCar.FTCarRegNo"]
            },
        }
        return oCstOptionReturn;
    }

    // ลูกค้า
    var oRPCBrowseCstOption = function(poReturnInputCard) {
        let tCardInputReturnCode = poReturnInputCard.tReturnInputCode;
        let tCardInputReturnName = poReturnInputCard.tReturnInputName;
        let tCardNextFuncName = poReturnInputCard.tNextFuncName;
        let aCardArgReturn = poReturnInputCard.aArgReturn;

        let tCondition = '';
        let tAgnCode = $('#oetSpcAgncyCode').val();
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMCst.FTAgnCode = '" + tAgnCode + "' OR ISNULL(TCNMCst.FTAgnCode,'') ='' ";
        }

        let oCstOptionReturn = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join: {
                Table: ['TCNMCst_L'],
                On: ['TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTCode', 'tCSTName', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMCst.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tCardInputReturnCode, "TCNMCst.FTCstCode"],
                Text: [tCardInputReturnName, "TCNMCst_L.FTCstName"],
            },
            NextFunc: {
                FuncName: tCardNextFuncName,
                ArgReturn: aCardArgReturn
            },
        }
        return oCstOptionReturn;
    }

    // ประเภทบัตร
    var oRptCardTypeOption = function(poReturnInputRpc) {
        let tRpcInputReturnCode = poReturnInputRpc.tReturnInputCode;
        let tRpcInputReturnName = poReturnInputRpc.tReturnInputName;
        let tRpcNextFuncName = poReturnInputRpc.tNextFuncName;
        let aRpcArgReturn = poReturnInputRpc.aArgReturn;

        var tSesAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode") ?>';
        if (tSesAgnCode != '') {
            tWhereAngCode = 'AND TFNMCardType.FTAgnCode = ' + tSesAgnCode;
        } else {
            tWhereAngCode = '';
        }

        let oRpcOptionReturn = {
            Title: ['report/report/report', 'tCTYTitle'],
            Table: {
                Master: 'TFNMCardType',
                PK: 'FTCtyCode'
            },
            Join: {
                Table: ['TFNMCardType_L'],
                On: ['TFNMCardType_L.FTCtyCode = TFNMCardType.FTCtyCode AND TFNMCardType_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'report/report/report',
                ColumnKeyLang: ['tCTYCode', 'tCTYName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TFNMCardType.FTCtyCode', 'TFNMCardType_L.FTCtyName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TFNMCardType.FDCreateOn DESC'],
            },
            Where: {
                Condition: [tWhereAngCode]
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tRpcInputReturnCode, "TFNMCardType.FTCtyCode"],
                Text: [tRpcInputReturnName, "TFNMCardType_L.FTCtyName"],
            },
            NextFunc: {
                FuncName: tRpcNextFuncName,
                ArgReturn: aRpcArgReturn
            },
        }
        return oRpcOptionReturn;
    }

    // พนักงาน
    var oRptBrowseEmpOption = function(poReturnInputEmp) {
        let tEmpInputReturnCode = poReturnInputEmp.tReturnInputCode;
        let tEmpInputReturnName = poReturnInputEmp.tReturnInputName;
        let tEmpNextFuncName = poReturnInputEmp.tNextFuncName;
        let aEmpArgReturn = poReturnInputEmp.aArgReturn;
        let tEmpWhereCondition = poReturnInputEmp.tWhereCondition;
        let oEmpOptionReturn = {
            Title: ['report/report/report', 'tCRDHolderIDTiltle'],
            Table: {
                Master: 'TFNMCard',
                PK: 'FTCrdHolderID'
            },
            Where: {
                Condition: [tEmpWhereCondition]
            },
            GrideView: {
                ColumnPathLang: 'report/report/report',
                ColumnKeyLang: ['tCRDHolderIDCode'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TFNMCard.FTCrdHolderID'],
                DataColumnsFormat: [''],
                Perpage: 10,
                OrderBy: ['TFNMCard.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tEmpInputReturnCode, "TFNMCard.FTCrdHolderID"],
                Text: [tEmpInputReturnName, "TFNMCard.FTCrdHolderID"],
            },
            NextFunc: {
                FuncName: tEmpNextFuncName,
                ArgReturn: aEmpArgReturn
            },
        }
        return oEmpOptionReturn;
    }

    var oRptSupplierOption = function(poReturnInputSpl) {
        let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
        let aSplArgReturn = poReturnInputSpl.aArgReturn;
        let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
        let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
        let tCondition = '';
        let tAgnCode = $('#oetSpcAgncyCode').val();
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMSpl.FTAgnCode = '" + tAgnCode + "' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
        }
        let oSplOptionReturn = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {
                Master: 'TCNMSpl',
                PK: 'FTSplCode'
            },
            Join: {
                Table: ['TCNMSpl_L'],
                On: [
                    'TCNMSpl.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tCode', 'tName'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMSpl.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tSplInputReturnCode, "TCNMSpl.FTSplCode"],
                Text: [tSplInputReturnName, "TCNMSpl_L.FTSplName"]
            },
            NextFunc: {
                FuncName: tSplNextFuncName,
                ArgReturn: aSplArgReturn
            },
        };
        return oSplOptionReturn;
    }

    var oRptSupplierMultiOption = function(poReturnInputSpl) {
        let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
        let aSplArgReturn = poReturnInputSpl.aArgReturn;
        let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
        let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
        let tCondition = '';
        let tAgnCode = $('#oetSpcAgncyCode').val();
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMSpl.FTAgnCode = '" + tAgnCode + "' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
        }
        let oSplOptionReturn = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {
                Master: 'TCNMSpl',
                PK: 'FTSplCode'
            },
            Join: {
                Table: ['TCNMSpl_L'],
                On: [
                    'TCNMSpl.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tCode', 'tName'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMSpl.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'M',
                Value: [tSplInputReturnCode, "TCNMSpl.FTSplCode"],
                Text: [tSplInputReturnName, "TCNMSpl_L.FTSplName"]
            },
            NextFunc: {
                FuncName: tSplNextFuncName,
                ArgReturn: aSplArgReturn
            },
        };
        return oSplOptionReturn;
    }

    var oRptSupplierGroupOption = function(poReturnInputSpl) {
        let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
        let aSplArgReturn = poReturnInputSpl.aArgReturn;
        let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
        let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
        let tCondition = '';
        // let tAgnCode            = $('#oetSpcAgncyCode').val();
        // if( tAgnCode != ''  && tAgnCode != undefined){
        //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
        // }
        let oSplOptionReturn = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {
                Master: 'TCNMSplGrp',
                PK: 'FTSgpCode'
            },
            Join: {
                Table: ['TCNMSplGrp_L'],
                On: [
                    'TCNMSplGrp.FTSgpCode  = TCNMSplGrp_L.FTSgpCode  AND TCNMSplGrp_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'supplier/groupsupplier/groupsupplier',
                ColumnKeyLang: ['tSGPCode', 'tSGPName'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMSplGrp.FTSgpCode', 'TCNMSplGrp_L.FTSgpName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMSplGrp.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tSplInputReturnCode, "TCNMSplGrp.FTSgpCode"],
                Text: [tSplInputReturnName, "TCNMSplGrp_L.FTSgpName"]
            },
            NextFunc: {
                FuncName: tSplNextFuncName,
                ArgReturn: aSplArgReturn
            },
        };
        return oSplOptionReturn;
    }
    var oRptSupplierStyOption = function(poReturnInputSpl) {
        let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
        let aSplArgReturn = poReturnInputSpl.aArgReturn;
        let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
        let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
        let tCondition = '';
        // let tAgnCode            = $('#oetSpcAgncyCode').val();
        // if( tAgnCode != ''  && tAgnCode != undefined){
        //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
        // }
        let oSplOptionReturn = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {
                Master: 'TCNMSplType',
                PK: 'FTStyCode'
            },
            Join: {
                Table: ['TCNMSplType_L'],
                On: [
                    'TCNMSplType.FTStyCode  = TCNMSplType_L.FTStyCode  AND TCNMSplType_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'supplier/suppliertype/suppliertype',
                ColumnKeyLang: ['tSTYCode', 'tSTYName'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMSplType.FTStyCode', 'TCNMSplType_L.FTStyName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMSplType.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tSplInputReturnCode, "TCNMSplType.FTStyCode"],
                Text: [tSplInputReturnName, "TCNMSplType_L.FTStyName"]
            },
            NextFunc: {
                FuncName: tSplNextFuncName,
                ArgReturn: aSplArgReturn
            },
        };
        return oSplOptionReturn;
    }
    //ยี่ห้อสินค้า
    var oRptBrandOption = function(poReturnInputBrand) {
        var tSesAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode") ?>';
        if (tSesAgnCode != '') {
            tWhereAngCode = 'AND TCNMPdtBrand.FTAgnCode = ' + tSesAgnCode;
        } else {
            tWhereAngCode = '';
        }

        let tPbnNextFuncName = poReturnInputBrand.tNextFuncName;
        let aPbnArgReturn = poReturnInputBrand.aArgReturn;
        let tPbnInputReturnCode = poReturnInputBrand.tReturnInputCode;
        let tPbnInputReturnName = poReturnInputBrand.tReturnInputName;
        let oPbnOptionReturn = {
            Title: ['product/pdtbrand/pdtbrand', 'tPBNTitle'],
            Table: {
                Master: 'TCNMPdtBrand',
                PK: 'FTPbnCode'
            },
            Join: {
                Table: ['TCNMPdtBrand_L'],
                On: [
                    'TCNMPdtBrand.FTPbnCode = TCNMPdtBrand_L.FTPbnCode AND TCNMPdtBrand_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tWhereAngCode]
            },
            GrideView: {
                ColumnPathLang: 'product/pdtbrand/pdtbrand',
                ColumnKeyLang: ['tPBNFrmPbnCode', 'tPBNFrmPbnName'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtBrand.FTPbnCode', 'TCNMPdtBrand_L.FTPbnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtBrand.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPbnInputReturnCode, "TCNMPdtBrand.FTPbnCode"],
                Text: [tPbnInputReturnName, "TCNMPdtBrand_L.FTPbnName"]
            },
            NextFunc: {
                FuncName: tPbnNextFuncName,
                ArgReturn: aPbnArgReturn
            },
        };
        return oPbnOptionReturn;
    }

    //รุ่นสินค้า
    var oRptModelOption = function(poReturnInputModel) {
        var tSesAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode") ?>';
        if (tSesAgnCode != '') {
            tWhereAngCode = 'AND TCNMPdtModel.FTAgnCode = ' + tSesAgnCode;
        } else {
            tWhereAngCode = '';
        }

        let tPmoNextFuncName = poReturnInputModel.tNextFuncName;
        let aPmoArgReturn = poReturnInputModel.aArgReturn;
        let tPmoInputReturnCode = poReturnInputModel.tReturnInputCode;
        let tPmoInputReturnName = poReturnInputModel.tReturnInputName;
        let oRptModelOption = {
            Title: ['product/pdtmodel/pdtmodel', 'tPMOTitle'],
            Table: {
                Master: 'TCNMPdtModel',
                PK: 'FTPmoCode'
            },
            Join: {
                Table: ['TCNMPdtModel_L'],
                On: [
                    'TCNMPdtModel.FTPmoCode = TCNMPdtModel_L.FTPmoCode AND TCNMPdtModel_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tWhereAngCode]
            },
            GrideView: {
                ColumnPathLang: 'product/pdtmodel/pdtmodel',
                ColumnKeyLang: ['tPMOFrmPmoCode', 'tPMOFrmPmoName'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtModel.FTPmoCode', 'TCNMPdtModel_L.FTPmoName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtModel.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPmoInputReturnCode, "TCNMPdtModel.FTPmoCode"],
                Text: [tPmoInputReturnName, "TCNMPdtModel_L.FTPmoName"]
            },
            NextFunc: {
                FuncName: tPmoNextFuncName,
                ArgReturn: aPmoArgReturn
            },
        };
        return oRptModelOption;
    }

    //ล็อคเกอร์
    var oRptShopSizeOption = function(poReturnInputShpSize) {
        let tShpSizeNextFuncName = poReturnInputShpSize.tNextFuncName;
        let aShpSizeArgReturn = poReturnInputShpSize.aArgReturn;
        let tShpSizeInputReturnCode = poReturnInputShpSize.tReturnInputCode;
        let tShpSizeInputReturnName = poReturnInputShpSize.tReturnInputName;
        let oShpSizeOptionReturn = {
            Title: ['company/smartlockerSize/smartlockerSize', 'tSMSSizeTitle'],
            Table: {
                Master: 'TRTMShopSize',
                PK: 'FTPzeCode'
            },
            Join: {
                Table: ['TRTMShopSize_L'],
                On: [
                    'TRTMShopSize.FTPzeCode = TRTMShopSize_L.FTSizCode AND TRTMShopSize_L.FNLngID = ' + nLangEdits
                ]
            },
            GrideView: {
                ColumnPathLang: 'company/smartlockerSize/smartlockerSize',
                ColumnKeyLang: ['tSizeCode', 'tSizeName'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TRTMShopSize.FTPzeCode', 'TRTMShopSize_L.FTSizName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TRTMShopSize.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tShpSizeInputReturnCode, "TRTMShopSize.FTPzeCode"],
                Text: [tShpSizeInputReturnName, "TRTMShopSize_L.FTSizName"]
            },
            NextFunc: {
                FuncName: tShpSizeNextFuncName,
                ArgReturn: aShpSizeArgReturn
            },
            RouteAddNew: 'SHPSmartLockerSizePageAdd',
            BrowseLev: 1
        };
        return oShpSizeOptionReturn;
    }

    // Dot ยาง
    var oRptDotOption = function(poReturnInputDot) {
        var tSesAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode") ?>';
        if (tSesAgnCode != '') {
            tWhereAngCode = 'AND TCNMLot.FTAgnCode = ' + tSesAgnCode;
        } else {
            tWhereAngCode = '';
        }

        var tWhereStaUse = 'AND TCNMLot.FTLotStaUse = 1';

        let tPmoNextFuncName = poReturnInputDot.tNextFuncName;
        let aPmoArgReturn = poReturnInputDot.aArgReturn;
        let tPmoInputReturnCode = poReturnInputDot.tReturnInputCode;
        let tPmoInputReturnName = poReturnInputDot.tReturnInputName;
        let oRptDotOption = {
            Title: ['service/pdtlot/pdtlot', 'tLOTTitle'],
            Table: {
                Master: 'TCNMLot',
                PK: 'FTLotNo'
            },
            Where: {
                Condition: [tWhereAngCode, tWhereStaUse]
            },
            GrideView: {
                ColumnPathLang: 'service/pdtlot/pdtlot',
                ColumnKeyLang: ['tLOTCode', 'tLOTLotBatchNo'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMLot.FTLotNo', 'TCNMLot.FTLotBatchNo'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMLot.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPmoInputReturnCode, "TCNMLot.FTLotNo"],
                Text: [tPmoInputReturnName, "TCNMLot.FTLotBatchNo"]
            },
            NextFunc: {
                FuncName: tPmoNextFuncName,
                ArgReturn: aPmoArgReturn
            },
        };
        return oRptDotOption;
    }

    // กลุ่มลูกค้า
    var oRptCusGrpOption = function(poReturnInputCusGrp) {
        var tSesAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode") ?>';
        if (tSesAgnCode != '') {
            tWhereAngCode = 'AND TCNMCstGrp.FTAgnCode = ' + tSesAgnCode;
        } else {
            tWhereAngCode = '';
        }

        // var tWhereStaUse = 'AND TCNMLot.FTLotStaUse = 1';

        let tPmoNextFuncName = poReturnInputCusGrp.tNextFuncName;
        let aPmoArgReturn = poReturnInputCusGrp.aArgReturn;
        let tPmoInputReturnCode = poReturnInputCusGrp.tReturnInputCode;
        let tPmoInputReturnName = poReturnInputCusGrp.tReturnInputName;
        let oRptCusGrpOption = {
            Title: ['service/pdtlot/pdtlot', 'กลุ่มลูกค้า'],
            Table: {
                Master: 'TCNMCstGrp',
                PK: 'FTCgpCode'
            },
            Join: {
                Table: ['TCNMCstGrp_L'],
                On: [
                    'TCNMCstGrp.FTCgpCode = TCNMCstGrp_L.FTCgpCode AND TCNMCstGrp_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tWhereAngCode]
            },
            GrideView: {
                ColumnPathLang: 'service/pdtlot/pdtlot',
                ColumnKeyLang: ['รหัสกลุ่มลูกค้า', 'ชื่อกลุ่มลูกค้า'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMCstGrp.FTCgpCode', 'TCNMCstGrp_L.FTCgpName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMCstGrp.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPmoInputReturnCode, "TCNMCstGrp.FTCgpCode"],
                Text: [tPmoInputReturnName, "TCNMCstGrp_L.FTCgpName"]
            },
            NextFunc: {
                FuncName: tPmoNextFuncName,
                ArgReturn: aPmoArgReturn
            },
        };
        return oRptCusGrpOption;
    }

    // ประเภทลูกค้า
    var oRptCusTypeOption = function(poReturnInputCusType) {
        var tSesAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode") ?>';
        if (tSesAgnCode != '') {
            tWhereAngCode = 'AND TCNMCstType.FTAgnCode = ' + tSesAgnCode;
        } else {
            tWhereAngCode = '';
        }

        // var tWhereStaUse = 'AND TCNMLot.FTLotStaUse = 1';

        let tPmoNextFuncName = poReturnInputCusType.tNextFuncName;
        let aPmoArgReturn = poReturnInputCusType.aArgReturn;
        let tPmoInputReturnCode = poReturnInputCusType.tReturnInputCode;
        let tPmoInputReturnName = poReturnInputCusType.tReturnInputName;
        let oRptCusTypeOption = {
            Title: ['service/pdtlot/pdtlot', 'ประเภทลูกค้า'],
            Table: {
                Master: 'TCNMCstType',
                PK: 'FTCtyCode'
            },
            Join: {
                Table: ['TCNMCstType_L'],
                On: [
                    'TCNMCstType.FTCtyCode = TCNMCstType_L.FTCtyCode AND TCNMCstType_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tWhereAngCode]
            },
            GrideView: {
                ColumnPathLang: 'service/pdtlot/pdtlot',
                ColumnKeyLang: ['รหัสประเภทลูกค้า', 'ชื่อประเภทลูกค้า'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMCstType.FTCtyCode', 'TCNMCstType_L.FTCtyName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMCstType.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tPmoInputReturnCode, "TCNMCstType.FTCtyCode"],
                Text: [tPmoInputReturnName, "TCNMCstType_L.FTCtyName"]
            },
            NextFunc: {
                FuncName: tPmoNextFuncName,
                ArgReturn: aPmoArgReturn
            },
        };
        return oRptCusTypeOption;
    }


    // หมวดหมู่ 1
    var oRptCate1Option = function(poReturnInputCate1) {
        var tConditionWhere = " AND TCNMPdtCatInfo.FNCatLevel = '1' ";
        tConditionWhere += " AND TCNMPdtCatInfo.FTCatStaUse = '1' ";

        let tCate1NextFuncName = poReturnInputCate1.tNextFuncName;
        let aCate1ArgReturn = poReturnInputCate1.aArgReturn;
        let tCate1InputReturnCode = poReturnInputCate1.tReturnInputCode;
        let tCate1InputReturnName = poReturnInputCate1.tReturnInputName;
        let oRptCate1Option = {
            Title: ['product/product/product', 'หมวดหมู่สินค้าหลัก'],
            Table: {
                Master: 'TCNMPdtCatInfo',
                PK: 'FTCatCode'
            },
            Join: {
                Table: ['TCNMPdtCatInfo_L'],
                On: [
                    'TCNMPdtCatInfo.FTCatCode = TCNMPdtCatInfo_L.FTCatCode AND TCNMPdtCatInfo.FNCatLevel = TCNMPdtCatInfo_L.FNCatLevel AND TCNMPdtCatInfo_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tConditionWhere]
            },
            GrideView: {
                ColumnPathLang: 'product/product/product',
                ColumnKeyLang: ['รหัสหมวดหมู่สินค้าหลัก', 'ชื่อหมวดหมู่สินค้าหลัก'],
                ColumnsSize: ['20%', '80%'],
                DataColumns: ['TCNMPdtCatInfo.FTCatCode', 'TCNMPdtCatInfo_L.FTCatName', 'TCNMPdtCatInfo.FNCatLevel'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMPdtCatInfo.FDCreateOn'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType: 'M',
                Value: [tCate1InputReturnCode, "TCNMPdtCatInfo.FTCatCode"],
                Text: [tCate1InputReturnName, "TCNMPdtCatInfo_L.FTCatName"],
            },
            NextFunc: {
                FuncName: tCate1NextFuncName,
                ArgReturn: aCate1ArgReturn
            },
        };
        return oRptCate1Option;
    }

    // หมวดหมู่ 2
    var oRptCate2Option = function(poReturnInputCate2) {




        let tConditionWhere = " AND TCNMPdtCatInfo.FNCatLevel = '2' ";
        tConditionWhere += " AND TCNMPdtCatInfo.FTCatStaUse = '1' ";

        let tCate1FromSess = $('#oetRptCate1CodeFrom').val();
        let tCate1From = tCate1FromSess.replace(/,/g, "','");


        if (tCate1From != '') {
            tConditionWhere += " AND TCNMPdtCatInfo.FTCatParent IN ('" + tCate1From + "')";
        }

        let tCate2NextFuncName = poReturnInputCate2.tNextFuncName;
        let aCate2ArgReturn = poReturnInputCate2.aArgReturn;
        let tCate2InputReturnCode = poReturnInputCate2.tReturnInputCode;
        let tCate2InputReturnName = poReturnInputCate2.tReturnInputName;
        let oRptCate2Option = {
            Title: ['product/product/product', 'หมวดหมู่สินค้าย่อย'],
            Table: {
                Master: 'TCNMPdtCatInfo',
                PK: 'FTCatCode'
            },
            Join: {
                Table: ['TCNMPdtCatInfo_L'],
                On: [
                    'TCNMPdtCatInfo.FTCatCode = TCNMPdtCatInfo_L.FTCatCode AND TCNMPdtCatInfo.FNCatLevel = TCNMPdtCatInfo_L.FNCatLevel AND TCNMPdtCatInfo_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tConditionWhere]
            },
            GrideView: {
                ColumnPathLang: 'product/product/product',
                ColumnKeyLang: ['รหัสหมวดหมู่สินค้าย่อย', 'ชื่อหมวดหมู่สินค้าย่อย'],
                ColumnsSize: ['20%', '80%'],
                DataColumns: ['TCNMPdtCatInfo.FTCatCode', 'TCNMPdtCatInfo_L.FTCatName', 'TCNMPdtCatInfo.FNCatLevel'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMPdtCatInfo.FDCreateOn'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType: 'M',
                Value: [tCate2InputReturnCode, "TCNMPdtCatInfo.FTCatCode"],
                Text: [tCate2InputReturnName, "TCNMPdtCatInfo_L.FTCatName"],
            },
            NextFunc: {
                FuncName: tCate2NextFuncName,
                ArgReturn: aCate2ArgReturn
            },
            // DebugSQL: true
        };
        return oRptCate2Option;
    }


    // เลขที่เอกสาร Promotion
    var oRptDocPromotionOption = function(poReturnInputDocPromotion) {
        var tConditionWhere = " AND TCNTPdtPmtHD.FTPmhStaApv = '1' ";


        let tBchCodeSess = $('#oetRptBchCodeSelect').val();
        let tBchcode = tBchCodeSess.replace(/,/g, "','");
        // let tWhereFilter = '';
        if (tBchCodeSess != '' && tBchCodeSess != undefined) {
            tConditionWhere += " AND TCNTPdtPmtHD.FTBchCode IN ('" + tBchcode + "')";
        }


        let tDocPromotionNextFuncName = poReturnInputDocPromotion.tNextFuncName;
        let aDocPromotionArgReturn = poReturnInputDocPromotion.aArgReturn;
        let tDocPromotionInputReturnCode = poReturnInputDocPromotion.tReturnInputCode;
        let tDocPromotionInputReturnName = poReturnInputDocPromotion.tReturnInputName;
        let oRptDocPromotionOption = {
            Title: ['product/product/product', 'เลขที่เอกสารโปรโมชั่น'],
            Table: {
                Master: 'TCNTPdtPmtHD',
                PK: 'FTPmhDocNo'
            },
            Join: {
                Table: ['TCNTPdtPmtHD_L'],
                On: [
                    'TCNTPdtPmtHD.FTPmhDocNo = TCNTPdtPmtHD_L.FTPmhDocNo AND TCNTPdtPmtHD.FTBchCode = TCNTPdtPmtHD_L.FTBchCode AND TCNTPdtPmtHD_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tConditionWhere]
            },
            GrideView: {
                ColumnPathLang: 'product/product/product',
                ColumnKeyLang: ['เลขที่เอกสารโปรโมชั่น', 'ชื่อโปรโมชั่น'],
                ColumnsSize: ['20%', '80%'],
                DataColumns: ['TCNTPdtPmtHD.FTPmhDocNo', 'TCNTPdtPmtHD_L.FTPmhName'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNTPdtPmtHD.FDCreateOn'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType: 'M',
                Value: [tDocPromotionInputReturnCode, "TCNTPdtPmtHD.FTPmhDocNo"],
                Text: [tDocPromotionInputReturnName, "TCNTPdtPmtHD.FTPmhDocNo"],
            },
            NextFunc: {
                FuncName: tDocPromotionNextFuncName,
                ArgReturn: aDocPromotionArgReturn
            },
        };
        return oRptDocPromotionOption;
    }

     // คูปอง
     var oRptCouponOption = function(poReturnInputCoupon) {
        var tConditionWhere = " AND TCNTPdtPmtHD.FTPmhStaApv = '1' ";


        let tBchCodeSess = $('#oetRptBchCodeSelect').val();
        let tBchcode = tBchCodeSess.replace(/,/g, "','");
        // let tWhereFilter = '';
        // if (tBchCodeSess != '' && tBchCodeSess != undefined) {
        //     tConditionWhere += " AND TCNTPdtPmtHD.FTBchCode IN ('" + tBchcode + "')";
        // }


        let tCouponNextFuncName = poReturnInputCoupon.tNextFuncName;
        let aCouponArgReturn = poReturnInputCoupon.aArgReturn;
        let tCouponInputReturnCode = poReturnInputCoupon.tReturnInputCode;
        let tCouponInputReturnName = poReturnInputCoupon.tReturnInputName;
        var tSesUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
        var tWhere = '';
        if (tSesUsrLevel != 'HQ') {
            var tUserLoginBchCode = "<?= $this->session->userdata('tSesUsrBchCodeMulti') ?>";
            var tSesUsrAgnCode = '<?= $this->session->userdata('tSesUsrAgnCode') ?>';

            // ของเดิม วัดคอมเม้นไว้ มันมีเรื่องของ ตัวแทนขาย ไม่ได้ใช้ใน Fitauto
            // tWhere = "AND (TFNTCouponHD.FTCphStaApv = 1 AND ((ISNULL(TFNTCouponHDBch.FTCphAgnTo, '') = '') OR (TFNTCouponHDBch.FTCphAgnTo IN(" + tSesUsrAgnCode + ") AND TFNTCouponHDBch.FTCphStaType = 1) OR (TFNTCouponHDBch.FTCphAgnTo NOT IN(" + tSesUsrAgnCode + ") AND TFNTCouponHDBch.FTCphStaType = 2))) AND (TFNTCouponHD.FTCphStaApv = 1 AND ((ISNULL(TFNTCouponHDBch.FTCphBchTo, '') = '') OR (TFNTCouponHDBch.FTCphBchTo IN(" + tUserLoginBchCode + ") AND TFNTCouponHDBch.FTCphStaType = 1) OR (TFNTCouponHDBch.FTCphBchTo NOT IN(" + tUserLoginBchCode + ") AND TFNTCouponHDBch.FTCphStaType = 2)))";
            tWhere = "AND (TFNTCouponHD.FTCphStaApv = 1 AND TFNTCouponHDBch.FTCphStaType = 1 OR TFNTCouponHDBch.FTCphStaType = 2 ) AND (TFNTCouponHD.FTCphStaApv = 1 AND ((ISNULL(TFNTCouponHDBch.FTCphBchTo, '') = '') OR (TFNTCouponHDBch.FTCphBchTo IN(" + tUserLoginBchCode + ") AND TFNTCouponHDBch.FTCphStaType = 1) OR (TFNTCouponHDBch.FTCphBchTo NOT IN(" + tUserLoginBchCode + ") AND TFNTCouponHDBch.FTCphStaType = 2)))";

        }


        let oRptCouponOption = {
            Title: ['coupon/coupon/coupon', 'tCPNTitle'],
            Table: {
                Master: 'TFNTCouponHD',
                PK: 'FTCphDocNo',
                PKName: 'FTCpnName'
            },
            Join: {
                Table: ['TFNTCouponHD_L'],
                On: ['TFNTCouponHD.FTCphDocNo = TFNTCouponHD_L.FTCphDocNo AND TFNTCouponHD_L.FNLngID = ' + nLangEdits + ' LEFT JOIN TFNTCouponHDBch WITH(NOLOCK) ON TFNTCouponHDBch.FTCphDocNo = TFNTCouponHD.FTCphDocNo AND  TFNTCouponHDBch.FTBchCode = TFNTCouponHD.FTBchCode']
            },
            Where: {
                Condition: [
                    function() {
                        tSQL = tWhere;
                        tSQL += " AND (TFNTCouponHD.FTCphStaApv = '1') /*AND (CONVERT(VARCHAR(10),TFNTCouponHD.FDCphDateStart, 121) <= CONVERT(VARCHAR(10),GETDATE(), 121) AND CONVERT(VARCHAR(10),TFNTCouponHD.FDCphDateStop, 121) >= CONVERT(VARCHAR(10),GETDATE(), 121))*/ /*AND (TFNTCouponHD.FTCphStaClosed = '1')*/";
                        //tSQL += " AND ((SELECT COUNT(FTCphDocNo) FROM TFNTCouponDT WITH(NOLOCK) WHERE FTCphDocNo = TFNTCouponHD.FTCphDocNo AND FNCpdAlwMaxUse = 0) > 0)"
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'coupon/coupon/coupon',
                ColumnKeyLang: ['tCPNTBCpnCode', 'tCPNTBCpnName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TFNTCouponHD.FTCphDocNo', 'TFNTCouponHD_L.FTCpnName'],
                DistinctField: [0],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TFNTCouponHD.FTCphDocNo'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'M',
                Value: [tCouponInputReturnCode, "TFNTCouponHD.FTCphDocNo"],
                Text: [tCouponInputReturnName, "TFNTCouponHD.FTCpnName"],
            },
            NextFunc: {
                FuncName: tCouponNextFuncName,
                ArgReturn: aCouponArgReturn
            },
        };
        return oRptCouponOption;
    }

    function JSxClearBrowseConditionSpcAgn(ptData) {
        if (ptData != '' || ptData != 'null') {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {

                $.ajax({
                    type: "POST",
                    url: "rptReportGetBchByAgenCode",
                    data: {
                        tAgenCode: JSON.parse(ptData)[0]
                    },
                    cache: false,
                    timeout: 5000,
                    async: false,
                    success: function(oResult) {
                        $('#oetRptPosCodeSelect').val('');
                        $('#oetRptPosNameSelect').val('');

                        var bIsHaveBch = oResult.tBchCode != "";
                        if (bIsHaveBch) {
                            $('#oetRptBchCodeSelect').val(oResult.tBchCode);
                            $('#oetRptBchStaSelectAll').val('');
                            $('#oetRptBchNameSelect').val(oResult.tBchName);

                            if (oResult.nBchCount == 1) {
                                $("#obtRptMultiBrowsePos").attr('disabled', false);
                            } else {
                                $("#obtRptMultiBrowsePos").attr('disabled', true);
                            }
                        } else {
                            $('#oetRptBchCodeSelect').val('');
                            $('#oetRptBchStaSelectAll').val('');
                            $('#oetRptBchNameSelect').val('');
                            $("#obtRptMultiBrowsePos").attr('disabled', true);
                        }
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
    }


    /*===== Begin Event Browse ======================================================== */
    $('#oimBrowseSpcAgncy').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) != 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseSpcAgencyOption = oBrowseSpcAgncy({
                'tReturnInputCode': 'oetSpcAgncyCode',
                'tReturnInputName': 'oetSpcAgncyName',
                'tBchCodeWhere': $('#oetRptBchCodeSelect').val(),
            });
            JCNxBrowseData('oBrowseSpcAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกลูกค้าเครดิต
    $('#obtMultiBrowseCstCredit').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) != 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseCstCreditMultiOption = oBrowseSpcMultiCstCredit({
                'tReturnInputCode': 'oetRptCstCreditCodeSelect',
                'tReturnInputName': 'oetRptCstCreditNameSelect',
            });
            JCNxBrowseData('oBrowseCstCreditMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptMultiBrowseBranch').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) != 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseSpcBchMultiOption = oBrowseSpcMultiBranch({
                'tReturnInputCode': 'oetRptBchCodeSelect',
                'tReturnInputName': 'oetRptBchNameSelect',
                'tAgnCodeWhere': $('#oetSpcAgncyCode').val(),
            });
            JCNxBrowseMultiSelect('oBrowseSpcBchMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event Branch
    $('#obtRptBrowseBchFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptBranchOptionFrom = undefined;
            oRptBranchOptionFrom = oRptBranchOption({
                'tReturnInputCode': 'oetRptBchCodeFrom',
                'tReturnInputName': 'oetRptBchNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseBch',
                'aArgReturn': ['FTBchCode', 'FTBchName']
            });
            JCNxBrowseData('oRptBranchOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseBchTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptBranchOptionTo = undefined;
            oRptBranchOptionTo = oRptBranchOption({
                'tReturnInputCode': 'oetRptBchCodeTo',
                'tReturnInputName': 'oetRptBchNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseBch',
                'aArgReturn': ['FTBchCode', 'FTBchName']
            });
            JCNxBrowseData('oRptBranchOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event Shop
    $('#obtRptBrowseShpFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tRptModCode = $('#ohdRptModCode').val();
            let tRptBranchForm = $('#oetRptBchCodeFrom').val();
            let tRptBranchTo = $('#oetRptBchCodeTo').val();
            window.oRptShopOptionFrom = undefined;
            oRptShopOptionFrom = oRptShopOption({
                'tReturnInputCode': 'oetRptShpCodeFrom',
                'tReturnInputName': 'oetRptShpNameFrom',
                'tRptModCode': tRptModCode,
                'tRptBranchForm': tRptBranchForm,
                'tRptBranchTo': tRptBranchTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShp',
                'aArgReturn': ['FTShpCode', 'FTShpName']
            });
            JCNxBrowseData('oRptShopOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseShpTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tRptModCode = $('#ohdRptModCode').val();
            let tRptBranchForm = $('#oetRptBchCodeFrom').val();
            let tRptBranchTo = $('#oetRptBchCodeTo').val();
            window.oRptShopOptionTo = undefined;
            oRptShopOptionTo = oRptShopOption({
                'tReturnInputCode': 'oetRptShpCodeTo',
                'tReturnInputName': 'oetRptShpNameTo',
                'tRptModCode': tRptModCode,
                'tRptBranchForm': tRptBranchForm,
                'tRptBranchTo': tRptBranchTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShp',
                'aArgReturn': ['FTShpCode', 'FTShpName']
            });
            JCNxBrowseData('oRptShopOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseShpTFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tRptModCode = $('#ohdRptModCode').val();
            let tRptBranchForm = $('#oetRptBchCodeFrom').val();
            let tRptBranchTo = $('#oetRptBchCodeTo').val();
            window.oRptShopTOptionFrom = undefined;
            oRptShopTOptionFrom = oRptShopOption({
                'tReturnInputCode': 'oetRptShpTCodeFrom',
                'tReturnInputName': 'oetRptShpTNameFrom',
                'tRptModCode': tRptModCode,
                'tRptBranchForm': tRptBranchForm,
                'tRptBranchTo': tRptBranchTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShp',
                'aArgReturn': ['FTShpCode', 'FTShpName']
            });
            JCNxBrowseData('oRptShopTOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseShpTTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tRptModCode = $('#ohdRptModCode').val();
            let tRptBranchForm = $('#oetRptBchCodeFrom').val();
            let tRptBranchTo = $('#oetRptBchCodeTo').val();
            window.oRptShopTOptionTo = undefined;
            oRptShopTOptionTo = oRptShopOption({
                'tReturnInputCode': 'oetRptShpTCodeTo',
                'tReturnInputName': 'oetRptShpTNameTo',
                'tRptModCode': tRptModCode,
                'tRptBranchForm': tRptBranchForm,
                'tRptBranchTo': tRptBranchTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShp',
                'aArgReturn': ['FTShpCode', 'FTShpName']
            });
            JCNxBrowseData('oRptShopTOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseShpRFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tRptModCode = $('#ohdRptModCode').val();
            let tRptBranchForm = $('#oetRptBchCodeFrom').val();
            let tRptBranchTo = $('#oetRptBchCodeTo').val();
            window.oRptShopROptionFrom = undefined;
            oRptShopROptionFrom = oRptShopOption({
                'tReturnInputCode': 'oetRptShpRCodeFrom',
                'tReturnInputName': 'oetRptShpRNameFrom',
                'tRptModCode': tRptModCode,
                'tRptBranchForm': tRptBranchForm,
                'tRptBranchTo': tRptBranchTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShp',
                'aArgReturn': ['FTShpCode', 'FTShpName']
            });
            JCNxBrowseData('oRptShopROptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseShpRTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tRptModCode = $('#ohdRptModCode').val();
            let tRptBranchForm = $('#oetRptBchCodeFrom').val();
            let tRptBranchTo = $('#oetRptBchCodeTo').val();
            window.oRptShopROptionTo = undefined;
            oRptShopROptionTo = oRptShopOption({
                'tReturnInputCode': 'oetRptShpRCodeTo',
                'tReturnInputName': 'oetRptShpRNameTo',
                'tRptModCode': tRptModCode,
                'tRptBranchForm': tRptBranchForm,
                'tRptBranchTo': tRptBranchTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShp',
                'aArgReturn': ['FTShpCode', 'FTShpName']
            });
            JCNxBrowseData('oRptShopROptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event Pos
    $('#obtRptBrowsePosFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptShopForm = $('#oetRptShpCodeFrom').val();
            var tRptShopTo = $('#oetRptShpCodeTo').val();
            window.oRptPosOptionFrom = undefined;
            oRptPosOptionFrom = oRptPosOption({
                'tReturnInputCode': 'oetRptPosCodeFrom',
                'tReturnInputName': 'oetRptPosNameFrom',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptShopForm,
                'tRptShopTo': tRptShopTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptPosOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowsePosTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptShopForm = $('#oetRptShpCodeFrom').val();
            var tRptShopTo = $('#oetRptShpCodeTo').val();
            window.oRptPosOptionTo = undefined;
            oRptPosOptionTo = oRptPosOption({
                'tReturnInputCode': 'oetRptPosCodeTo',
                'tReturnInputName': 'oetRptPosNameTo',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptShopForm,
                'tRptShopTo': tRptShopTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptPosOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event ตู้
    $('#obtRptBrowseLockerFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptLockerForm = $('#oetRptShpCodeFrom').val();
            var tRptLockerTo = $('#oetRptShpCodeTo').val();
            window.oRptLockerOptionFrom = undefined;
            oRptLockerOptionFrom = oRptPosOption({
                'tReturnInputCode': 'oetRptLockerCodeFrom',
                'tReturnInputName': 'oetRptLockerNameFrom',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptLockerForm,
                'tRptShopTo': tRptLockerTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptLockerOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseLockerTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptLockerForm = $('#oetRptShpCodeFrom').val();
            var tRptLockerTo = $('#oetRptShpCodeTo').val();
            window.oRptLockerOptionTo = undefined;
            oRptLockerOptionTo = oRptPosOption({
                'tReturnInputCode': 'oetRptLockerCodeTo',
                'tReturnInputName': 'oetRptLockerNameTo',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptLockerForm,
                'tRptShopTo': tRptLockerTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptLockerOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ตู้ที่โอน
    $('#obtRptBrowsePosTFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptShopTForm = $('#oetRptShpTCodeFrom').val();
            var tRptShopTTo = $('#oetRptShpTCodeTo').val();
            window.oRptPosTOptionFrom = undefined;
            oRptPosTOptionFrom = oRptPosOption({
                'tReturnInputCode': 'oetRptPosTCodeFrom',
                'tReturnInputName': 'oetRptPosTNameFrom',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptShopTForm,
                'tRptShopTo': tRptShopTTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptPosTOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowsePosTTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptShopTForm = $('#oetRptShpTCodeFrom').val();
            var tRptShopTTo = $('#oetRptShpTCodeTo').val();
            window.oRptPosTOptionTo = undefined;
            oRptPosTOptionTo = oRptPosOption({
                'tReturnInputCode': 'oetRptPosCodeTo',
                'tReturnInputName': 'oetRptPosNameTo',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptShopTForm,
                'tRptShopTo': tRptShopTTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptPosTOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ตู้ที่รับโอน
    $('#obtRptBrowsePosRFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptShopRForm = $('#oetRptShpRCodeFrom').val();
            var tRptShopRTo = $('#oetRptShpRCodeTo').val();
            window.oRptPosROptionFrom = undefined;
            oRptPosROptionFrom = oRptPosOption({
                'tReturnInputCode': 'oetRptPosRCodeFrom',
                'tReturnInputName': 'oetRptPosRNameFrom',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptShopRForm,
                'tRptShopTo': tRptShopRTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptPosROptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowsePosRTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tRptModCode = $('#ohdRptModCode').val();
            var tRptShopRForm = $('#oetRptShpRCodeFrom').val();
            var tRptShopRTo = $('#oetRptShpRCodeTo').val();
            window.oRptPosROptionTo = undefined;
            oRptPosROptionTo = oRptPosOption({
                'tReturnInputCode': 'oetRptPosRCodeTo',
                'tReturnInputName': 'oetRptPosRNameTo',
                'tRptModCode': tRptModCode,
                'tRptShopForm': tRptShopRForm,
                'tRptShopTo': tRptShopRTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosCode']
            });
            JCNxBrowseData('oRptPosROptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event MerChant
    $('#obtRptBrowseMerFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptMerChantOptionFrom = undefined;
            oRptMerChantOptionFrom = oRptMerChantOption({
                'tReturnInputCode': 'oetRptMerCodeFrom',
                'tReturnInputName': 'oetRptMerNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseMerChant',
                'aArgReturn': ['FTMerCode', 'FTMerName']
            });
            JCNxBrowseData('oRptMerChantOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseMerTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptMerChantOptionTo = undefined;
            oRptMerChantOptionTo = oRptMerChantOption({
                'tReturnInputCode': 'oetRptMerCodeTo',
                'tReturnInputName': 'oetRptMerNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseMerChant',
                'aArgReturn': ['FTMerCode', 'FTMerName']
            });
            JCNxBrowseData('oRptMerChantOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event Employee
    $('#obtRptBrowseEmpFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptEmpOptionFrom = undefined;
            oRptEmpOptionFrom = oRptEmpOption({
                'tReturnInputCode': 'oetRptEmpCodeFrom',
                'tReturnInputName': 'oetRptEmpNameFrom'
            });
            JCNxBrowseData('oRptEmpOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseEmpTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptEmpOptionTo = undefined;
            oRptEmpOptionTo = oRptEmpOption({
                'tReturnInputCode': 'oetRptEmpCodeTo',
                'tReturnInputName': 'oetRptEmpNameTo'
            });
            JCNxBrowseData('oRptEmpOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event Recive
    $('#obtRptBrowseRcvFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptReciveOptionFrom = undefined;
            oRptReciveOptionFrom = oRptReciveOption({
                'tAgncyCode': $('#oetSpcAgncyCode').val(),
                'tReturnInputCode': 'oetRptRcvCodeFrom',
                'tReturnInputName': 'oetRptRcvNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseRcv',
                'aArgReturn': ['FTRcvCode', 'FTRcvName']
            });
            JCNxBrowseData('oRptReciveOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseRcvTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptReciveOptionTo = undefined;
            oRptReciveOptionTo = oRptReciveOption({
                'tAgncyCode': $('#oetSpcAgncyCode').val(),
                'tReturnInputCode': 'oetRptRcvCodeTo',
                'tReturnInputName': 'oetRptRcvNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseRcv',
                'aArgReturn': ['FTRcvCode', 'FTRcvName']
            });
            JCNxBrowseData('oRptReciveOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event Product
    $('#obtRptBrowsePdtFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptProductFromOption = undefined;
            oRptProductFromOption = oRptProductOption({
                'tReturnInputCode': 'oetRptPdtCodeFrom',
                'tReturnInputName': 'oetRptPdtNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdt',
                'aArgReturn': ['FTPdtCode', 'FTPdtName']
            });
            JCNxBrowseData('oRptProductFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowsePdtTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptProductToOption = undefined;
            oRptProductToOption = oRptProductOption({
                'tReturnInputCode': 'oetRptPdtCodeTo',
                'tReturnInputName': 'oetRptPdtNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdt',
                'aArgReturn': ['FTPdtCode', 'FTPdtName']

            });
            JCNxBrowseData('oRptProductToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event ProductType
    $('#obtRptBrowsePdtTypeFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptPdtTypeOptionFrom = undefined;
            oRptPdtTypeOptionFrom = oRptPdtTypeOption({
                'tReturnInputCode': 'oetRptPdtTypeCodeFrom',
                'tReturnInputName': 'oetRptPdtTypeNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdtType',
                'aArgReturn': ['FTPtyCode', 'FTPtyName']

            });
            JCNxBrowseData('oRptPdtTypeOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowsePdtTypeTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptPdtTypeOptionTo = undefined;
            oRptPdtTypeOptionTo = oRptPdtTypeOption({
                'tReturnInputCode': 'oetRptPdtTypeCodeTo',
                'tReturnInputName': 'oetRptPdtTypeNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdtType',
                'aArgReturn': ['FTPtyCode', 'FTPtyName']
            });
            JCNxBrowseData('oRptPdtTypeOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event ProductGroup
    $('#obtRptBrowsePdtGrpFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptPdtGrpOptionFrom = undefined;
            oRptPdtGrpOptionFrom = oRptPdtGrpOption({
                'tReturnInputCode': 'oetRptPdtGrpCodeFrom',
                'tReturnInputName': 'oetRptPdtGrpNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdtGrp',
                'aArgReturn': ['FTPgpChain', 'FTPgpName']
            });
            JCNxBrowseData('oRptPdtGrpOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowsePdtGrpTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptPdtGrpOptionTo = undefined;
            oRptPdtGrpOptionTo = oRptPdtGrpOption({
                'tReturnInputCode': 'oetRptPdtGrpCodeTo',
                'tReturnInputName': 'oetRptPdtGrpNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdtGrp',
                'aArgReturn': ['FTPgpChain', 'FTPgpName']
            });
            JCNxBrowseData('oRptPdtGrpOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากหมายเลขบัตร
    $('#obtRPCBrowseCardFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRPTBrowseCardFrom = undefined;
            oRPTBrowseCardFrom = oRptBrowseCardOption({
                'tReturnInputCardCode': 'oetRptCardCodeFrom',
                'tReturnInputCardName': 'oetRptCardNameFrom',
                'tNextFuncCardName': 'JSxRptConsNextFuncBrowseCard',
                'aArgCardReturn': ['FTCrdCode', 'FTCrdCode'] // ['FTCrdCode','FTCrdName']
            });
            JCNxBrowseData('oRPTBrowseCardFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงหมายเลขบัตร
    $('#obtRPCBrowseCardTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRPTBrowseCardFrom = undefined;
            oRPTBrowseCardFrom = oRptBrowseCardOption({
                'tReturnInputCardCode': 'oetRptCardCodeTo',
                'tReturnInputCardName': 'oetRptCardNameTo',
                'tNextFuncCardName': 'JSxRptConsNextFuncBrowseCard',
                'aArgCardReturn': ['FTCrdCode', 'FTCrdCode'] // ['FTCrdCode','FTCrdName']
            });
            JCNxBrowseData('oRPTBrowseCardFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากหมายเลขบัตรเดิม
    $('#obtRPCBrowseCardOldFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardOldFrom = undefined;
            oRptCardOldFrom = oRptBrowseCardOption({
                'tReturnInputCardCode': 'oetRptCardCodeOldFrom',
                'tReturnInputCardName': 'oetRptCardNameOldFrom',
                'tNextFuncCardName': 'JSxRptConsNextFuncBrowseCard',
                'aArgCardReturn': ['FTCrdCode', 'FTCrdCode'] // ['FTCrdCode','FTCrdName']
            });
            JCNxBrowseData('oRptCardOldFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงหมายเลขบัตรเดิม
    $('#obtRPCBrowseCardOldTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardOldTo = undefined;
            oRptCardOldTo = oRptBrowseCardOption({
                'tReturnInputCardCode': 'oetRptCardCodeOldTo',
                'tReturnInputCardName': 'oetRptCardNameOldTo',
                'tNextFuncCardName': 'JSxRptConsNextFuncBrowseCard',
                'aArgCardReturn': ['FTCrdCode', 'FTCrdCode'] // ['FTCrdCode','FTCrdName']
            });
            JCNxBrowseData('oRptCardOldTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากหมายเลขบัตรใหม่
    $('#obtRPCBrowseCardNewFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardNewFrom = undefined;
            oRptCardNewFrom = oRptBrowseCardOption({
                'tReturnInputCardCode': 'oetRptCardCodeNewFrom',
                'tReturnInputCardName': 'oetRptCardNameNewFrom',
                'tNextFuncCardName': 'JSxRptConsNextFuncBrowseCard',
                'aArgCardReturn': ['FTCrdCode', 'FTCrdCode'] // ['FTCrdCode','FTCrdName']
            });
            JCNxBrowseData('oRptCardNewFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงหมายเลขบัตรใหม่
    $('#obtRPCBrowseCardNewTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardNewTo = undefined;
            oRptCardNewTo = oRptBrowseCardOption({
                'tReturnInputCardCode': 'oetRptCardCodeNewTo',
                'tReturnInputCardName': 'oetRptCardNameNewTo',
                'tNextFuncCardName': 'JSxRptConsNextFuncBrowseCard',
                'aArgCardReturn': ['FTCrdCode', 'FTCrdCode'] // ['FTCrdCode','FTCrdName']
            });
            JCNxBrowseData('oRptCardNewTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากลูกค้า
    $('#obtRPCBrowseCstFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCstFrom = undefined;
            oRptCstFrom = oRPCBrowseCstOption({
                'tReturnInputCode': 'oetRptCstCodeFrom',
                'tReturnInputName': 'oetRptCstNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCst',
                'aArgReturn': ['FTCstCode', 'FTCstName'] // ['FTCstCode','FTCstName']
            });
            JCNxBrowseData('oRptCstFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงลูกค้า
    $('#obtRPCBrowseCstTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCstTo = undefined;
            oRptCstTo = oRPCBrowseCstOption({
                'tReturnInputCode': 'oetRptCstCodeTo',
                'tReturnInputName': 'oetRptCstNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCst',
                'aArgReturn': ['FTCstCode', 'FTCstName'] // ['FTCstCode','FTCstName']
            });
            JCNxBrowseData('oRptCstTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Employee From
    $('#oimRPCBrowseEmp').click(function(event) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tWhereCondition = "AND FTCrdHolderID != '' ";
            window.oRptBrowseEmpOptionFrom = oRptBrowseEmpOption({
                'tReturnInputCode': 'oetRptEmpCodeFrom',
                'tReturnInputName': 'oetRptEmpNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseEmp',
                'aArgReturn': ['FTCrdHolderID', 'FTCrdHolderID'],
                'tWhereCondition': tWhereCondition,
            });
            JCNxBrowseData('oRptBrowseEmpOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Employee To
    $('#oimRPCBrowseEmpTo').click(function(event) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tWhereCondition = "AND FTCrdHolderID != '' ";
            window.oRptBrowseEmpOptionTo = oRptBrowseEmpOption({
                'tReturnInputCode': 'oetRptEmpCodeTo',
                'tReturnInputName': 'oetRptEmpNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseEmp',
                'aArgReturn': ['FTCrdHolderID', 'FTCrdHolderID'],
                'tWhereCondition': tWhereCondition,
            });
            JCNxBrowseData('oRptBrowseEmpOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // คลังสินค้า
    $('#obtRptBrowseWahFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ต้องเลือกสาขาก่อนถึงจะเลือกคลังสินค้าได้
            if ($('#oetRptBchCodeSelect').val() == '' || $('#oetRptBchCodeSelect').val() == '') {
                $('#odvModalWarningInputEmpty').modal('show');
                $('.xCNWarningInputEmpty').html('กรุณาเลือกข้อมูล สาขา ซึ่งจะสามารถกรองข้อมูลในระดับคลังสินค้าได้');

                //ล้างค่า
                $('#oetRptWahCodeFrom , #oetRptWahNameFrom').val('');
                return;
            }

            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopCodeFrom = $('#oetRptShpCodeFrom').val();
            let tRptShopCodeTo = $('#oetRptShpCodeTo').val();
            let tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
            let tRptPosCodeTo = $('#oetRptPosCodeTo').val();


            let tDataBranch = $('#oetRptBchCodeSelect').val();

            let tDataBranchReplace = tDataBranch.replaceAll(",", "','");

            // let tTextWhereInBranch      = '';
            // if(tDataBranchReplace != ''){
            //     tTextWhereInBranch = " AND (TCNMPos.FTBchCode IN ('" + tDataBranchReplace + "'))";
            // }

            // เช็คในกรณีเลือกเฉพาะคลังสาขา
            // if ((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")) {
            // tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5)) ";
            // tWhereCondition =  " AND TCNMWaHouse.FTBchCode BETWEEN '"+tRptBchCodeFrom+"' AND '"+tRptBchCodeTo+"' ";
            if (tDataBranchReplace != 'undefined' && tDataBranchReplace != '') {
                tWhereCondition = " AND (TCNMWaHouse.FTBchCode IN ('" + tDataBranchReplace + "')) ";
            }

            // }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            // if ((tRptShopCodeFrom != 'undefined' && tRptShopCodeFrom != "") && (tRptShopCodeTo != 'undefined' && tRptShopCodeTo != "")) {
            //     tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
            // }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            // if ((tRptPosCodeFrom != 'undefined' && tRptPosCodeFrom != "") && (tRptPosCodeTo != 'undefined' && tRptPosCodeTo != "")) {
            //     tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
            // }
            window.oRptWarehouseFromOption = undefined;
            oRptWarehouseFromOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahCodeFrom',
                'tReturnInputName': 'oetRptWahNameFrom',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFrom',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงคลังสินค้า
    $('#obtRptBrowseWahTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ต้องเลือกสาขาก่อนถึงจะเลือกคลังสินค้าได้
            if ($('#oetRptBchCodeSelect').val() == '' || $('#oetRptBchCodeSelect').val() == '') {
                $('#odvModalWarningInputEmpty').modal('show');
                $('.xCNWarningInputEmpty').html('กรุณาเลือกข้อมูล สาขา ซึ่งจะสามารถกรองข้อมูลในระดับคลังสินค้าได้');

                //ล้างค่า
                $('#oetRptWahCodeTo , #oetRptWahNameTo').val('');
                return;
            }

            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopCodeFrom = $('#oetRptShpCodeFrom').val();
            let tRptShopCodeTo = $('#oetRptShpCodeTo').val();
            let tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
            let tRptPosCodeTo = $('#oetRptPosCodeTo').val();

            let tDataBranch = $('#oetRptBchCodeSelect').val();
            let tDataBranchReplace = tDataBranch.replaceAll(",", "','");

            if (tDataBranchReplace != 'undefined' && tDataBranchReplace != '') {
                tWhereCondition = " AND (TCNMWaHouse.FTBchCode IN ('" + tDataBranchReplace + "')) ";
            }

            // เช็คในกรณีเลือกเฉพาะคลังสาขา
            // if ((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")) {
            //     // tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
            //     tWhereCondition = " AND TCNMWaHouse.FTBchCode BETWEEN '" + tRptBchCodeFrom + "' AND '" + tRptBchCodeTo + "' ";
            // }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            // if ((tRptShopCodeFrom != 'undefined' && tRptShopCodeFrom != "") && (tRptShopCodeTo != 'undefined' && tRptShopCodeTo != "")) {
            //     tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
            // }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            // if ((tRptPosCodeFrom != 'undefined' && tRptPosCodeFrom != "") && (tRptPosCodeTo != 'undefined' && tRptPosCodeTo != "")) {
            //     tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
            // }
            window.oRptWarehouseToOption = undefined;
            oRptWarehouseToOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahCodeTo',
                'tReturnInputName': 'oetRptWahNameTo',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFrom',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // คลังที่โอน
    $('#obtRptBrowseWahTFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ต้องเลือกสาขาก่อนถึงจะเลือกคลังสินค้าได้
            if ($('#oetRptBchCodeSelect').val() == '' || $('#oetRptBchCodeSelect').val() == '') {
                $('#odvModalWarningInputEmpty').modal('show');
                $('.xCNWarningInputEmpty').html('กรุณาเลือกข้อมูล สาขา ซึ่งจะสามารถกรองข้อมูลในระดับคลังสินค้าได้');

                //ล้างค่า
                $('#oetRptWahTCodeFrom , #oetRptWahTNameFrom').val('');
                return;
            }

            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopTCodeFrom = $('#oetRptShpTCodeFrom').val();
            let tRptShopTCodeTo = $('#oetRptShpTCodeTo').val();
            let tRptPosTCodeFrom = $('#oetRptPosTCodeFrom').val();
            let tRptPosTCodeTo = $('#oetRptPosTCodeTo').val();

            // เช็คในกรณีเลือกเฉพาะคลังสาขา
            if ((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptShopTCodeFrom != 'undefined' && tRptShopTCodeFrom != "") && (tRptShopTCodeTo != 'undefined' && tRptShopTCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptPosTCodeFrom != 'undefined' && tRptPosTCodeFrom != "") && (tRptPosTCodeTo != 'undefined' && tRptPosTCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
            }
            window.oRptWarehouseTFromOption = undefined;
            oRptWarehouseTFromOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahTCodeFrom',
                'tReturnInputName': 'oetRptWahTNameFrom',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFrom',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseTFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseWahTTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ต้องเลือกสาขาก่อนถึงจะเลือกคลังสินค้าได้
            if ($('#oetRptBchCodeSelect').val() == '' || $('#oetRptBchCodeSelect').val() == '') {
                $('#odvModalWarningInputEmpty').modal('show');
                $('.xCNWarningInputEmpty').html('กรุณาเลือกข้อมูล สาขา ซึ่งจะสามารถกรองข้อมูลในระดับคลังสินค้าได้');

                //ล้างค่า
                $('#oetRptWahTCodeTo , #oetRptWahTNameTo').val('');
                return;
            }

            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopTCodeFrom = $('#oetRptShpTCodeFrom').val();
            let tRptShopTCodeTo = $('#oetRptShpTCodeTo').val();
            let tRptPosTCodeFrom = $('#oetRptPosTCodeFrom').val();
            let tRptPosTCodeTo = $('#oetRptPosTCodeTo').val();

            // เช็คในกรณีเลือกเฉพาะคลังสาขา
            if ((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptShopTCodeFrom != 'undefined' && tRptShopTCodeFrom != "") && (tRptShopTCodeTo != 'undefined' && tRptShopTCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptPosTCodeFrom != 'undefined' && tRptPosTCodeFrom != "") && (tRptPosTCodeTo != 'undefined' && tRptPosTCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
            }
            window.oRptWarehouseTToOption = undefined;
            oRptWarehouseTToOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahTCodeTo',
                'tReturnInputName': 'oetRptWahTNameTo',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFrom',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseTToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // คลังที่รับโอน
    $('#obtRptBrowseWahRFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopRCodeFrom = $('#oetRptShpRCodeFrom').val();
            let tRptShopRCodeTo = $('#oetRptShpRCodeTo').val();
            let tRptPosRCodeFrom = $('#oetRptPosRCodeFrom').val();
            let tRptPosRCodeTo = $('#oetRptPosRCodeTo').val();

            // เช็คในกรณีเลือกเฉพาะคลังสาขา
            if ((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptShopRCodeFrom != 'undefined' && tRptShopRCodeFrom != "") && (tRptShopRCodeTo != 'undefined' && tRptShopRCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptPosRCodeFrom != 'undefined' && tRptPosRCodeFrom != "") && (tRptPosRCodeTo != 'undefined' && tRptPosRCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
            }
            window.oRptWarehouseRFromOption = undefined;
            oRptWarehouseRFromOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahRCodeFrom',
                'tReturnInputName': 'oetRptWahRNameFrom',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFrom',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseRFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseWahRTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopRCodeFrom = $('#oetRptShpRCodeFrom').val();
            let tRptShopRCodeTo = $('#oetRptShpRCodeTo').val();
            let tRptPosRCodeFrom = $('#oetRptPosRCodeFrom').val();
            let tRptPosRCodeTo = $('#oetRptPosRCodeTo').val();

            // เช็คในกรณีเลือกเฉพาะคลังสาขา
            if ((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptShopRCodeFrom != 'undefined' && tRptShopRCodeFrom != "") && (tRptShopRCodeTo != 'undefined' && tRptShopRCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
            }

            // เช็คในกรณีเลือกเฉพาะร้านค้า
            if ((tRptPosRCodeFrom != 'undefined' && tRptPosRCodeFrom != "") && (tRptPosRCodeTo != 'undefined' && tRptPosRCodeTo != "")) {
                tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
            }
            window.oRptWarehouseRToOption = undefined;
            oRptWarehouseRToOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahRCodeTo',
                'tReturnInputName': 'oetRptWahRNameTo',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFrom',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseRToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });



    // คลังโอน
    $('#obtRptBrowseWahFromOut').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ต้องเลือกสาขาก่อนถึงจะเลือกคลังสินค้าได้
            if ($('#oetRptBchCodeSelect').val() == '' || $('#oetRptBchCodeSelect').val() == '') {
                $('#odvModalWarningInputEmpty').modal('show');
                $('.xCNWarningInputEmpty').html('กรุณาเลือกข้อมูล สาขา ซึ่งจะสามารถกรองข้อมูลในระดับคลังสินค้าได้');

                //ล้างค่า
                $('#oetRptWahCodeFromOut , #oetRptWahNameFromOut').val('');
                return;
            }

            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopCodeFrom = $('#oetRptShpCodeFrom').val();
            let tRptShopCodeTo = $('#oetRptShpCodeTo').val();
            let tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
            let tRptPosCodeTo = $('#oetRptPosCodeTo').val();


            let tDataBranch = $('#oetRptBchCodeSelect').val();

            let tDataBranchReplace = tDataBranch.replaceAll(",", "','");

            if (tDataBranchReplace != 'undefined' && tDataBranchReplace != '') {
                tWhereCondition = " AND (TCNMWaHouse.FTBchCode IN ('" + tDataBranchReplace + "')) ";
            }

            window.oRptWarehouseFromOutOption = undefined;
            oRptWarehouseFromOutOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahCodeFromOut',
                'tReturnInputName': 'oetRptWahNameFromOut',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFromOut',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseFromOutOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });



    $('#obtRptBrowseWahFromIn').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ต้องเลือกสาขาก่อนถึงจะเลือกคลังสินค้าได้
            if ($('#oetRptBchCodeSelect').val() == '' || $('#oetRptBchCodeSelect').val() == '') {
                $('#odvModalWarningInputEmpty').modal('show');
                $('.xCNWarningInputEmpty').html('กรุณาเลือกข้อมูล สาขา ซึ่งจะสามารถกรองข้อมูลในระดับคลังสินค้าได้');

                //ล้างค่า
                $('#oetRptWahCodeFromIn , #oetRptWahNameFromIn').val('');
                return;
            }

            JSxCheckPinMenuClose();
            let tWhereCondition = "";
            let tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            let tRptBchCodeTo = $('#oetRptBchCodeTo').val();
            let tRptShopCodeFrom = $('#oetRptShpCodeFrom').val();
            let tRptShopCodeTo = $('#oetRptShpCodeTo').val();
            let tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
            let tRptPosCodeTo = $('#oetRptPosCodeTo').val();


            let tDataBranch = $('#oetRptBchCodeSelect').val();

            let tDataBranchReplace = tDataBranch.replaceAll(",", "','");

            if (tDataBranchReplace != 'undefined' && tDataBranchReplace != '') {
                tWhereCondition = " AND (TCNMWaHouse.FTBchCode IN ('" + tDataBranchReplace + "')) ";
            }

            window.oRptWarehouseFromInOption = undefined;
            oRptWarehouseFromInOption = oRptWarehouseOption({
                'tReturnInputCode': 'oetRptWahCodeFromIn',
                'tReturnInputName': 'oetRptWahNameFromIn',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseWahFromIn',
                'aArgReturn': ['FTWahCode', 'FTWahName']
            });
            JCNxBrowseData('oRptWarehouseFromInOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    // Clck Button Courier From-To
    $('#obtRptBrowseCourierFrom').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tWhereCondition = "AND FTCryStaActive = 1";
            window.oRptCourierFromOption = undefined;
            oRptCourierFromOption = oRptCourierOption({
                'tReturnInputCode': 'oetRptCourierCodeFrom',
                'tReturnInputName': 'oetRptCourierNameFrom',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCourier',
                'aArgReturn': ['FTCryCode', 'FTCryName']
            });
            JCNxBrowseData('oRptCourierFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRptBrowseCourierTo').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tWhereCondition = "AND FTCryStaActive = 1";
            window.oRptCourierToOption = undefined;
            oRptCourierToOption = oRptCourierOption({
                'tReturnInputCode': 'oetRptCourierCodeTo',
                'tReturnInputName': 'oetRptCourierNameTo',
                'tWhereCondition': tWhereCondition,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCourier',
                'aArgReturn': ['FTCryCode', 'FTCryName']
            });
            JCNxBrowseData('oRptCourierToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Click Button Single MerChant
    $('#obtRptBrowseMerchant').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptSingleMerChantOption = undefined;
            oRptSingleMerChantOption = oRptSingleMerOption({
                'tReturnInputCode': 'oetRptMerchantCode',
                'tReturnInputName': 'oetRptMerchantName'
            });
            JCNxBrowseData('oRptSingleMerChantOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากประเภทตู้
    $('#obtSMLBrowseGroupFrom').click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptRackOptionFrom = undefined;
            oRptRackOptionFrom = oRptRackOption({
                'tReturnInputCode': 'oetSMLBrowseGroupCodeFrom',
                'tReturnInputName': 'oetSMLBrowseGroupNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseRack',
                'aArgReturn': ['FTRakCode', 'FTRakName']
            });
            JCNxBrowseData('oRptRackOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงประเภทตู้
    $('#obtSMLBrowseGroupTo').click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptRackOptionTo = undefined;
            oRptRackOptionTo = oRptRackOption({
                'tReturnInputCode': 'oetSMLBrowseGroupCodeTo',
                'tReturnInputName': 'oetSMLBrowseGroupNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseRack',
                'aArgReturn': ['FTRakCode', 'FTRakName']
            });
            JCNxBrowseData('oRptRackOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากประเภทบัตร
    $('#obtRPCBrowseCardTypeFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardTypeOptionFrom = undefined;
            oRptCardTypeOptionFrom = oRptCardTypeOption({
                'tReturnInputCode': 'oetRptCardTypeCodeFrom',
                'tReturnInputName': 'oetRptCardTypeNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCardType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCardTypeOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงประเภทบัตร
    $('#obtRPCBrowseCardTypeTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardTypeOptionTo = undefined;
            oRptCardTypeOptionTo = oRptCardTypeOption({
                'tReturnInputCode': 'oetRptCardTypeCodeTo',
                'tReturnInputName': 'oetRptCardTypeNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCardType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCardTypeOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากประเภทบัตรเดิม
    $('#obtRPCBrowseCardTypeOldFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardTypeOldFrom = undefined;
            oRptCardTypeOldFrom = oRptCardTypeOption({
                'tReturnInputCode': 'oetRptCardTypeCodeOldFrom',
                'tReturnInputName': 'oetRptCardTypeNameOldFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCardType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCardTypeOldFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงประเภทบัตรเดิม
    $('#obtRPCBrowseCardTypeOldTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardTypeOldTo = undefined;
            oRptCardTypeOldTo = oRptCardTypeOption({
                'tReturnInputCode': 'oetRptCardTypeCodeOldTo',
                'tReturnInputName': 'oetRptCardTypeNameOldTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCardType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCardTypeOldTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากประเภทบัตรใหม่
    $('#obtRPCBrowseCardTypeNewFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardTypeNewFrom = undefined;
            oRptCardTypeNewFrom = oRptCardTypeOption({
                'tReturnInputCode': 'oetRptCardTypeCodeNewFrom',
                'tReturnInputName': 'oetRptCardTypeNameNewFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCardType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCardTypeNewFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงประเภทบัตรใหม่
    $('#obtRPCBrowseCardTypeNewTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCardTypeNewTo = undefined;
            oRptCardTypeNewTo = oRptCardTypeOption({
                'tReturnInputCode': 'oetRptCardTypeCodeNewTo',
                'tReturnInputName': 'oetRptCardTypeNameNewTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCardType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCardTypeNewTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากขนาดช่องฝาก
    $('#obtRptBrowseShpSizeFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptShopSizeOptionFrom = undefined;
            oRptShopSizeOptionFrom = oRptShopSizeOption({
                'tReturnInputCode': 'oetRptPzeCodeFrom',
                'tReturnInputName': 'oetRptPzeNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShpSize',
                'aArgReturn': ['FTPzeCode', 'FTSizName']
            });
            JCNxBrowseData('oRptShopSizeOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงขนาดช่องฝาก
    $('#obtRptBrowseShpSizeTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptShopSizeOptionTo = undefined;
            oRptShopSizeOptionTo = oRptShopSizeOption({
                'tReturnInputCode': 'oetRptPzeCodeTo',
                'tReturnInputName': 'oetRptPzeNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseShpSize',
                'aArgReturn': ['FTPzeCode', 'FTSizName']
            });
            JCNxBrowseData('oRptShopSizeOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากผู้จำหน่าย
    $('#obtRptBrowseSupplierFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptSupplierOptionFrom = undefined;
            oRptSupplierOptionFrom = oRptSupplierOption({
                'tReturnInputCode': 'oetRptSupplierCodeFrom',
                'tReturnInputName': 'oetRptSupplierNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseSpl',
                'aArgReturn': ['FTSplCode', 'FTSplName']
            });
            JCNxBrowseData('oRptSupplierOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงผู้จำหน่าย
    $('#obtRptBrowseSupplierTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptSupplierOptionTo = undefined;
            oRptSupplierOptionTo = oRptSupplierOption({
                'tReturnInputCode': 'oetRptSupplierCodeTo',
                'tReturnInputName': 'oetRptSupplierNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseSpl',
                'aArgReturn': ['FTSplCode', 'FTSplName']
            });
            JCNxBrowseData('oRptSupplierOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    // จาก กลุ่มผู้จำหน่าย
    $('#obtRptBrowseSgpFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oRptSupplierGroupOptionFrom = undefined;
            oRptSupplierGroupOptionFrom = oRptSupplierGroupOption({
                'tReturnInputCode': 'oetRptSgpCodeFrom',
                'tReturnInputName': 'oetRptSgpNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseSgp',
                'aArgReturn': ['FTSgpCode', 'FTSgpName']
            });
            JCNxBrowseData('oRptSupplierGroupOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    // ถึง กลุ่มผู้จำหน่าย
    $('#obtRptBrowseSgpTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oRptSupplierGroupOptionTo = undefined;
            oRptSupplierGroupOptionTo = oRptSupplierGroupOption({
                'tReturnInputCode': 'oetRptSgpCodeTo',
                'tReturnInputName': 'oetRptSgpNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseSgp',
                'aArgReturn': ['FTSgpCode', 'FTSgpName']
            });
            JCNxBrowseData('oRptSupplierGroupOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จาก ประเภทผู้จำหน่าย
    $('#obtRptBrowseStyFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oRptSupplierStyOptionFrom = undefined;
            oRptSupplierStyOptionFrom = oRptSupplierStyOption({
                'tReturnInputCode': 'oetRptStyCodeFrom',
                'tReturnInputName': 'oetRptStyNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseSty',
                'aArgReturn': ['FTStyCode', 'FTStyName']
            });
            JCNxBrowseData('oRptSupplierStyOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    // ถึง ประเภทผู้จำหน่าย
    $('#obtRptBrowseStyTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oRptSupplierStyOptionTo = undefined;
            oRptSupplierStyOptionTo = oRptSupplierStyOption({
                'tReturnInputCode': 'oetRptStyCodeTo',
                'tReturnInputName': 'oetRptStyNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseSty',
                'aArgReturn': ['FTStyCode', 'FTStyName']
            });
            JCNxBrowseData('oRptSupplierStyOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากผู้จำหน่าย Multi
    $('#obtRptBrowseSupplierMultiFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptSupplierMultiOptionFrom = undefined;
            oRptSupplierMultiOptionFrom = oRptSupplierMultiOption({
                'tReturnInputCode': 'oetRptSupplierCodeMultiFrom',
                'tReturnInputName': 'oetRptSupplierNameMultiFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseSplMulti',
                'aArgReturn': ['FTSplCode', 'FTSplName']
            });
            JCNxBrowseData('oRptSupplierMultiOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากยี่ห้อ
    $('#obtRptBrowseBrandFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptBrandOptionFrom = undefined;
            oRptBrandOptionFrom = oRptBrandOption({
                'tReturnInputCode': 'oetRptBrandCodeFrom',
                'tReturnInputName': 'oetRptBrandNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseBrand',
                'aArgReturn': ['FTPbnCode', 'FTPbnName']
            });
            JCNxBrowseData('oRptBrandOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงยี่ห้อ
    $('#obtRptBrowseBrandTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptBrandOptionTo = undefined;
            oRptBrandOptionTo = oRptBrandOption({
                'tReturnInputCode': 'oetRptBrandCodeTo',
                'tReturnInputName': 'oetRptBrandNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseBrand',
                'aArgReturn': ['FTPbnCode', 'FTPbnName']
            });
            JCNxBrowseData('oRptBrandOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากรุ่น
    $('#obtRptBrowseModelFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptModelOptionFrom = undefined;
            oRptModelOptionFrom = oRptModelOption({
                'tReturnInputCode': 'oetRptModelCodeFrom',
                'tReturnInputName': 'oetRptModelNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseModel',
                'aArgReturn': ['FTPmoCode', 'FTPmoName']
            });
            JCNxBrowseData('oRptModelOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงรุ่น
    $('#obtRptBrowseModelTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptModelOptionTo = undefined;
            oRptModelOptionTo = oRptModelOption({
                'tReturnInputCode': 'oetRptModelCodeTo',
                'tReturnInputName': 'oetRptModelNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseModel',
                'aArgReturn': ['FTPmoCode', 'FTPmoName']
            });
            JCNxBrowseData('oRptModelOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จากเลเวลลูกค้า
    $('#obtRPCBrowseCstLevelFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCstLevelFrom = undefined;
            oRptCstLevelFrom = oRPCBrowseCstLevelOption({
                'tReturnInputCode': 'oetRptCstLevelCodeFrom',
                'tReturnInputName': 'oetRptCstLevelNameFrom',
                'tNextFuncName': 'JSxRptNextFuncBrowseLevelCode',
                'aArgReturn': ['FTClvCode', 'FTClvName']
            });
            JCNxBrowseData('oRptCstLevelFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงเลเวลลูกค้า
    $('#obtRPCBrowseCstLevelTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCstLevelTo = undefined;
            oRptCstLevelTo = oRPCBrowseCstLevelOption({
                'tReturnInputCode': 'oetRptCstLevelCodeTo',
                'tReturnInputName': 'oetRptCstLevelNameTo',
                'tNextFuncName': 'JSxRptNextFuncBrowseLevelCode',
                'aArgReturn': ['FTClvCode', 'FTClvName']
            });
            JCNxBrowseData('oRptCstLevelTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // จากทะเบียนรถ
    $('#obtRPCBrowseCarRegNoFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCarRegNoFrom = undefined;
            oRptCarRegNoFrom = oRPCBrowseCarRegNoOption({
                'tReturnInputCode': 'oetRptCarRegNoFrom',
                'tReturnInputName': 'oetRptCarRegNoNameFrom',
                'tNextFuncName': 'JSxRptNextFuncBrowseCarRegNoCode',
                'aArgReturn': ['FTCarCode', 'FTCarRegNo']
            });
            JCNxBrowseData('oRptCarRegNoFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึงทะเบียนรถ
    $('#obtRPCBrowseCarRegNoTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCarRegNoTo = undefined;
            oRptCarRegNoTo = oRPCBrowseCarRegNoOption({
                'tReturnInputCode': 'oetRptCarRegNoTo',
                'tReturnInputName': 'oetRptCarRegNoNameTo',
                'tNextFuncName': 'JSxRptNextFuncBrowseCarRegNoCode',
                'aArgReturn': ['FTCarCode', 'FTCarRegNo']
            });
            JCNxBrowseData('oRptCarRegNoTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จาก Dot
    $('#obtRptBrowseDotFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptDotOptionFrom = undefined;
            oRptDotOptionFrom = oRptDotOption({
                'tReturnInputCode': 'oetRptDotCodeFrom',
                'tReturnInputName': 'oetRptDotNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseDot',
                'aArgReturn': ['FTLotNo', 'FTLotBatchNo']
            });
            JCNxBrowseData('oRptDotOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึง Dot
    $('#obtRptBrowseDotTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptDotOptionTo = undefined;
            oRptDotOptionTo = oRptDotOption({
                'tReturnInputCode': 'oetRptDotCodeTo',
                'tReturnInputName': 'oetRptDotNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseDot',
                'aArgReturn': ['FTLotNo', 'FTLotBatchNo']
            });
            JCNxBrowseData('oRptDotOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จาก กลุ่มลูกค้า
    $('#obtRptBrowseCusGrpFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCusGrpOptionFrom = undefined;
            oRptCusGrpOptionFrom = oRptCusGrpOption({
                'tReturnInputCode': 'oetRptCusGrpCodeFrom',
                'tReturnInputName': 'oetRptCusGrpNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCusGrp',
                'aArgReturn': ['FTCgpCode', 'FTCgpName']
            });
            JCNxBrowseData('oRptCusGrpOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึง กลุ่มลูกค้า
    $('#obtRptBrowseCusGrpTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCusGrpOptionTo = undefined;
            oRptCusGrpOptionTo = oRptCusGrpOption({
                'tReturnInputCode': 'oetRptCusGrpCodeTo',
                'tReturnInputName': 'oetRptCusGrpNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCusGrp',
                'aArgReturn': ['FTCgpCode', 'FTCgpName']
            });
            JCNxBrowseData('oRptCusGrpOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จาก ประเภทลูกค้า
    $('#obtRptBrowseCusTypeFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCusTypeOptionFrom = undefined;
            oRptCusTypeOptionFrom = oRptCusTypeOption({
                'tReturnInputCode': 'oetRptCusTypeCodeFrom',
                'tReturnInputName': 'oetRptCusTypeNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCusType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCusTypeOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึง ประเภทลูกค้า
    $('#obtRptBrowseCusTypeTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCusTypeOptionTo = undefined;
            oRptCusTypeOptionTo = oRptCusTypeOption({
                'tReturnInputCode': 'oetRptCusTypeCodeTo',
                'tReturnInputName': 'oetRptCusTypeNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCusType',
                'aArgReturn': ['FTCtyCode', 'FTCtyName']
            });
            JCNxBrowseData('oRptCusTypeOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });



    // จาก หมวดหมู่ 1
    $('#obtRptBrowseCate1From').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCate1OptionFrom = undefined;
            oRptCate1OptionFrom = oRptCate1Option({
                'tReturnInputCode': 'oetRptCate1CodeFrom',
                'tReturnInputName': 'oetRptCate1NameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCate1',
                'aArgReturn': ['FTCatCode', 'FTCatName']
            });
            JCNxBrowseData('oRptCate1OptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึง หมวดหมู่ 1
    $('#obtRptBrowseCate1To').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCate1OptionTo = undefined;
            oRptCate1OptionTo = oRptCate1Option({
                'tReturnInputCode': 'oetRptCate1CodeTo',
                'tReturnInputName': 'oetRptCate1NameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCate1',
                'aArgReturn': ['FTCatCode', 'FTCatName']
            });
            JCNxBrowseData('oRptCate1OptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // จาก หมวดหมู่ 2
    $('#obtRptBrowseCate2From').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCate2OptionFrom = undefined;
            oRptCate2OptionFrom = oRptCate2Option({
                'tReturnInputCode': 'oetRptCate2CodeFrom',
                'tReturnInputName': 'oetRptCate2NameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCate2',
                'aArgReturn': ['FTCatCode', 'FTCatName']
            });
            JCNxBrowseData('oRptCate2OptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึง หมวดหมู่ 2
    $('#obtRptBrowseCate2To').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCate2OptionTo = undefined;
            oRptCate2OptionTo = oRptCate2Option({
                'tReturnInputCode': 'oetRptCate2CodeTo',
                'tReturnInputName': 'oetRptCate2NameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCate2',
                'aArgReturn': ['FTCatCode', 'FTCatName']
            });
            JCNxBrowseData('oRptCate2OptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // จาก เลขที่เอกสาร Pronotion
    $('#obtRptBrowseDocPromotionFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptDocPromotionOptionFrom = undefined;
            oRptDocPromotionOptionFrom = oRptDocPromotionOption({
                'tReturnInputCode': 'oetRptDocPromotionCodeFrom',
                'tReturnInputName': 'oetRptDocPromotionNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseDocPromotion',
                'aArgReturn': ['FTPmhDocNo', 'FTPmhName']
            });
            JCNxBrowseData('oRptDocPromotionOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ถึง  เลขที่เอกสาร Pronotionหมวดหมู่ 2
    $('#obtRptBrowseDocPromotionTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptDocPromotionOptionTo = undefined;
            oRptDocPromotionOptionTo = oRptDocPromotionOption({
                'tReturnInputCode': 'oetRptDocPromotionCodeTo',
                'tReturnInputName': 'oetRptDocPromotionNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseDocPromotion',
                'aArgReturn': ['FTPmhDocNo', 'FTPmhName']
            });
            JCNxBrowseData('oRptDocPromotionOptionTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

     // จาก Coupon
     $('#obtRptBrowseCouponFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptCouponOptionFrom = undefined;
            oRptCouponOptionFrom = oRptCouponOption({
                'tReturnInputCode': 'oetRptCouponCodeFrom',
                'tReturnInputName': 'oetRptCouponNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCoupon',
                'aArgReturn': ['FTCphDocNo', 'FTCpnName']
            });
            JCNxBrowseData('oRptCouponOptionFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    /*=============================================================================================== */

    // Next Function : หลังจากเลือกสาขา
    function JSxRptConsNextFuncBrowseBch(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tBchCode = aDataNextFunc[0];
            tBchName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร สาขา
        var tRptBchCodeFrom, tRptBchNameFrom, tRptBchCodeTo, tRptBchNameTo
        tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
        tRptBchNameFrom = $('#oetRptBchNameFrom').val();
        tRptBchCodeTo = $('#oetRptBchCodeTo').val();
        tRptBchNameTo = $('#oetRptBchNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากสาขา ให้ default ถึงสาขา เป็นข้อมูลเดียวกัน
        if ((typeof(tRptBchCodeFrom) !== 'undefined' && tRptBchCodeFrom != "") && (typeof(tRptBchCodeTo) !== 'undefined' && tRptBchCodeTo == "")) {
            $('#oetRptBchCodeTo').val(tBchCode);
            $('#oetRptBchNameTo').val(tBchName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงสาขาให้ default จากสาขา  เป็นข้อมูลเดียวกัน
        if ((typeof(tRptBchCodeTo) !== 'undefined' && tRptBchCodeTo != "") && (typeof(tRptBchCodeFrom) !== 'undefined' && tRptBchCodeFrom == "")) {
            $('#oetRptBchCodeFrom').val(tBchCode);
            $('#oetRptBchNameFrom').val(tBchName);
        }

        var tRptShopCodeFrom, tRptShopCodeTo
        tRptShopCodeFrom = $('#oetRptShpCodeFrom').val();
        tRptShopCodeTo = $('#oetRptShpCodeTo').val();
        if ((typeof(tRptShopCodeFrom) !== 'undefined' && tRptShopCodeFrom != "") && (typeof(tRptShopCodeTo) !== 'undefined' && tRptShopCodeTo != "")) {
            $('#oetRptShpCodeFrom').val('');
            $('#oetRptShpNameFrom').val('');
            $('#oetRptShpCodeTo').val('');
            $('#oetRptShpNameTo').val('');
        }

        //เช็คเงื่อนไข แคชเชียร์
        var tRptCashierFrom = $('#oetRptCashierCodeFrom').val();
        var tRptCashierTo = $('#oetRptCashierCodeTo').val();
        if ((typeof(tRptCashierFrom) !== 'undefined' && tRptCashierFrom != "") && (typeof(tRptCashierTo) !== 'undefined' && tRptCashierTo != "")) {
            $('#oetRptCashierCodeFrom').val('');
            $('#oetRptCashierNameFrom').val('');
            $('#oetRptCashierCodeTo').val('');
            $('#oetRptCashierNameTo').val('');
        }
    }

    // Next Function หลังจากเลือกร้านค้า
    function JSxRptConsNextFuncBrowseShp(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tShpCode = aDataNextFunc[0];
            tShpName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร ร้านค้า
        var tRptShpCodeFrom, tRptShpNameFrom, tRptShpCodeTo, tRptShpNameTo
        tRptShpCodeFrom = $('#oetRptShpCodeFrom').val();
        tRptShpNameFrom = $('#oetRptShpNameFrom').val();
        tRptShpCodeTo = $('#oetRptShpCodeTo').val();
        tRptShpNameTo = $('#oetRptShpNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากร้านค้า ให้ default ถึงร้านค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpCodeFrom) !== 'undefined' && tRptShpCodeFrom != "") && (typeof(tRptShpCodeTo) !== 'undefined' && tRptShpCodeTo == "")) {
            $('#oetRptShpCodeTo').val(tShpCode);
            $('#oetRptShpNameTo').val(tShpName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงร้านค้า default  จากร้านค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpCodeTo) !== 'undefined' && tRptShpCodeTo != "") && (typeof(tRptShpCodeFrom) !== 'undefined' && tRptShpCodeFrom == "")) {
            $('#oetRptShpCodeFrom').val(tShpCode);
            $('#oetRptShpNameFrom').val(tShpName);
        }

        var tRptPosCodeFrom, tRptPosCodeTo
        tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
        tRptPosCodeTo = $('#oetRptPosCodeTo').val();
        if ((typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom != "") && (typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo != "")) {
            $('#oetRptPosCodeFrom').val('');
            $('#oetRptPosNameFrom').val('');
            $('#oetRptPosCodeTo').val('');
            $('#oetRptPosNameTo').val('');
        }


        // ประกาศตัวแปร ร้านค้าที่โอน
        var tRptShpTCodeFrom = $('#oetRptShpTCodeFrom').val();
        var tRptShpTNameFrom = $('#oetRptShpTNameFrom').val();
        var tRptShpTCodeTo = $('#oetRptShpTCodeTo').val();
        var tRptShpTNameTo = $('#oetRptShpTNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากร้านค้าที่โอน ให้ default ถึงร้านค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpTCodeFrom) !== 'undefined' && tRptShpTCodeFrom != "") && (typeof(tRptShpTCodeTo) !== 'undefined' && tRptShpTCodeTo == "")) {
            $('#oetRptShpTCodeTo').val(tShpCode);
            $('#oetRptShpTNameTo').val(tShpName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงร้านค้าที่โอน default  จากร้านค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpTCodeTo) !== 'undefined' && tRptShpTCodeTo != "") && (typeof(tRptShpTCodeFrom) !== 'undefined' && tRptShpTCodeFrom == "")) {
            $('#oetRptShpTCodeFrom').val(tShpCode);
            $('#oetRptShpTNameFrom').val(tShpName);
        }

        /*var tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
        var tRptPosCodeTo   = $('#oetRptPosCodeTo').val();
        if((typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom != "") && (typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo != "")){
            $('#oetRptPosCodeFrom').val('');
            $('#oetRptPosNameFrom').val('');
            $('#oetRptPosCodeTo').val('');
            $('#oetRptPosNameTo').val('');
        }*/


        // ประกาศตัวแปร ร้านค้าที่รับโอน
        var tRptShpRCodeFrom = $('#oetRptShpRCodeFrom').val();
        var tRptShpRNameFrom = $('#oetRptShpRNameFrom').val();
        var tRptShpRCodeTo = $('#oetRptShpRCodeTo').val();
        var tRptShpRNameTo = $('#oetRptShpRNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากร้านค้าที่รับโอน ให้ default ถึงร้านค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpRCodeFrom) !== 'undefined' && tRptShpRCodeFrom != "") && (typeof(tRptShpRCodeTo) !== 'undefined' && tRptShpRCodeTo == "")) {
            $('#oetRptShpRCodeTo').val(tShpCode);
            $('#oetRptShpRNameTo').val(tShpName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงร้านค้าที่รับโอน default  จากร้านค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpRCodeTo) !== 'undefined' && tRptShpRCodeTo != "") && (typeof(tRptShpRCodeFrom) !== 'undefined' && tRptShpRCodeFrom == "")) {
            $('#oetRptShpRCodeFrom').val(tShpCode);
            $('#oetRptShpRNameFrom').val(tShpName);
        }

        /*var tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
        var tRptPosCodeTo   = $('#oetRptPosCodeTo').val();
        if((typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom != "") && (typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo != "")){
            $('#oetRptPosCodeFrom').val('');
            $('#oetRptPosNameFrom').val('');
            $('#oetRptPosCodeTo').val('');
            $('#oetRptPosNameTo').val('');
        }*/

        //เช็คเงื่อนไข แคชเชียร์
        var tRptCashierFrom = $('#oetRptCashierCodeFrom').val();
        var tRptCashierTo = $('#oetRptCashierCodeTo').val();
        if ((typeof(tRptCashierFrom) !== 'undefined' && tRptCashierFrom != "") && (typeof(tRptCashierTo) !== 'undefined' && tRptCashierTo != "")) {
            $('#oetRptCashierCodeFrom').val('');
            $('#oetRptCashierNameFrom').val('');
            $('#oetRptCashierCodeTo').val('');
            $('#oetRptCashierNameTo').val('');
        }

    }

    // Next Function : หลังจากเลือกสินค้า
    function JSxRptConsNextFuncBrowsePdt(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tPdtCode = aDataNextFunc[0];
            tPdtName = aDataNextFunc[1];
        }
        if (poDataNextFunc == "NULL") {
            $('#oetRptPdtCodeFrom').val("");
            $('#oetRptPdtNameFrom').val("");
            $('#oetRptPdtCodeTo').val("");
            $('#oetRptPdtNameTo').val("");
        }
        // ประกาศตัวแปร สินค้า
        var tRptPdtCodeFrom, tRptPdtNameFrom, tRptPdtCodeTo, tRptPdtNameTo
        tRptPdtCodeFrom = $('#oetRptPdtCodeFrom').val();
        tRptPdtNameFrom = $('#oetRptPdtNameFrom').val();
        tRptPdtCodeTo = $('#oetRptPdtCodeTo').val();
        tRptPdtNameTo = $('#oetRptPdtNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากสินค้า ให้ default ถึงร้านค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPdtCodeFrom) !== 'undefined' && tRptPdtCodeFrom != "") && (typeof(tRptPdtCodeTo) !== 'undefined' && tRptPdtCodeTo == "")) {
            $('#oetRptPdtCodeTo').val(tPdtCode);
            $('#oetRptPdtNameTo').val(tPdtName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงร้านค้า default จากสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPdtCodeTo) !== 'undefined' && tRptPdtCodeTo != "") && (typeof(tRptPdtCodeFrom) !== 'undefined' && tRptPdtCodeFrom == "")) {
            $('#oetRptPdtCodeFrom').val(tPdtCode);
            $('#oetRptPdtNameFrom').val(tPdtName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptPdtCodeTo');

    }

    // Next Function : หลังจากเลือกกลุ่มธุรกิจ
    function JSxRptConsNextFuncBrowseMerChant(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tMerCode = aDataNextFunc[0];
            tMerName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร กลุ่มธุรกิจ
        var tRptMerCodeFrom, tRptMerNameFrom, tRptMerCodeTo, tRptPdtNameTo
        tRptMerCodeFrom = $('#oetRptMerCodeFrom').val();
        tRptMerNameFrom = $('#oetRptMerNameFrom').val();
        tRptMerCodeTo = $('#oetRptMerCodeTo').val();
        tRptMerNameTo = $('#oetRptMerNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากกลุ่มธุรกิจ ให้ default ถึงกลุ่มธุรกิจ เป็นข้อมูลเดียวกัน
        if ((typeof(tRptMerCodeFrom) !== 'undefined' && tRptMerCodeFrom != "") && (typeof(tRptMerCodeTo) !== 'undefined' && tRptMerCodeTo == "")) {
            $('#oetRptMerCodeTo').val(tMerCode);
            $('#oetRptMerNameTo').val(tMerName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงกลุ่มธุรกิจ default จากกลุ่มธุรกิจ  เป็นข้อมูลเดียวกัน
        if ((typeof(tRptMerCodeTo) !== 'undefined' && tRptMerCodeTo != "") && (typeof(tRptMerCodeFrom) !== 'undefined' && tRptMerCodeFrom == "")) {
            $('#oetRptMerCodeFrom').val(tMerCode);
            $('#oetRptMerNameFrom').val(tMerName);
        }

    }

    // Next Function : หลังจากเลือกกลุ่มสินค้า
    function JSxRptConsNextFuncBrowsePdtGrp(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tPdtGrpCode = aDataNextFunc[0];
            tPdtGrpName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร กลุ่มสินค้า
        var tRptPdtGrpCodeFrom, tRptPdtGrpNameFrom, tRptPdtGrpCodeTo, tRptPdtGrpNameTo
        tRptPdtGrpCodeFrom = $('#oetRptPdtGrpCodeFrom').val();
        tRptPdtGrpNameFrom = $('#oetRptPdtGrpNameFrom').val();
        tRptPdtGrpCodeTo = $('#oetRptPdtGrpCodeTo').val();
        tRptPdtGrpNameTo = $('#oetRptPdtGrpNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากกลุ่มสินค้า ให้ default ถึงกลุ่มสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPdtGrpCodeFrom) !== 'undefined' && tRptPdtGrpCodeFrom != "") && (typeof(tRptPdtGrpCodeTo) !== 'undefined' && tRptPdtGrpCodeTo == "")) {
            $('#oetRptPdtGrpCodeTo').val(tPdtGrpCode);
            $('#oetRptPdtGrpNameTo').val(tPdtGrpName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงกลุ่มสินค้า default จากกลุ่มสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPdtGrpCodeTo) !== 'undefined' && tRptPdtGrpCodeTo != "") && (typeof(tRptPdtGrpCodeFrom) !== 'undefined' && tRptPdtGrpCodeFrom == "")) {
            $('#oetRptPdtGrpCodeFrom').val(tPdtGrpCode);
            $('#oetRptPdtGrpNameFrom').val(tPdtGrpName);
        }

        JSxUncheckinCheckbox('oetRptPdtGrpCodeTo');
    }

    // Next Function : หลังจากเลือกประเภทสินค้า
    function JSxRptConsNextFuncBrowsePdtType(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            let aDataNextFunc = JSON.parse(poDataNextFunc);
            tPdtTypeCode = aDataNextFunc[0];
            tPdtTypeName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร ประเภทสินค้า
        var tRptPdtTypeCodeFrom, tRptPdtTypeNameFrom, tRptPdtTypeCodeTo, tRptPdtTypeNameTo
        tRptPdtTypeCodeFrom = $('#oetRptPdtTypeCodeFrom').val();
        tRptPdtTypeNameFrom = $('#oetRptPdtTypeNameFrom').val();
        tRptPdtTypeCodeTo = $('#oetRptPdtTypeCodeTo').val();
        tRptPdtTypeNameTo = $('#oetRptPdtTypeNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากประเภทสินค้า ให้ default ถึงประเภทสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPdtTypeCodeFrom) !== 'undefined' && tRptPdtTypeCodeFrom != "") && (typeof(tRptPdtTypeCodeTo) !== 'undefined' && tRptPdtTypeCodeTo == "")) {
            $('#oetRptPdtTypeCodeTo').val(tPdtTypeCode);
            $('#oetRptPdtTypeNameTo').val(tPdtTypeName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงประเภทสินค้า default จากประเภทสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPdtTypeCodeTo) !== 'undefined' && tRptPdtTypeCodeTo != "") && (typeof(tRptPdtTypeCodeFrom) !== 'undefined' && tRptPdtTypeCodeFrom == "")) {
            $('#oetRptPdtTypeCodeFrom').val(tPdtTypeCode);
            $('#oetRptPdtTypeNameFrom').val(tPdtTypeName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptPdtTypeCodeTo');

    }

    // Next Function : หลังจากเลือกประเภทการชำระเงิน
    function JSxRptConsNextFuncBrowseRcv(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tRcvCode = aDataNextFunc[0];
            tRcvName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร ประเภทการชำระเงิน
        var tRptRcvCodeFrom, tRptRcvNameFrom, tRptRcvCodeTo, tRptRcvNameTo
        tRptRcvCodeFrom = $('#oetRptRcvCodeFrom').val();
        tRptRcvNameFrom = $('#oetRptRcvNameFrom').val();
        tRptRcvCodeTo = $('#oetRptRcvCodeTo').val();
        tRptRcvNameTo = $('#oetRptRcvNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากประเภทชำระเงิน ให้ default ถึงประเภทชำระเงิน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptRcvCodeFrom) !== 'undefined' && tRptRcvCodeFrom != "") && (typeof(tRptRcvCodeTo) !== 'undefined' && tRptRcvCodeTo == "")) {
            $('#oetRptRcvCodeTo').val(tRcvCode);
            $('#oetRptRcvNameTo').val(tRcvName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงประเภทชำระเงิน default จากประเภทชำระเงิน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptRcvCodeTo) !== 'undefined' && tRptRcvCodeTo != "") && (typeof(tRptRcvCodeFrom) !== 'undefined' && tRptRcvCodeFrom == "")) {
            $('#oetRptRcvCodeFrom').val(tRcvCode);
            $('#oetRptRcvNameFrom').val(tRcvName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptRcvCodeTo');
    }

    // Next Function : หลังจากเลือกคลัง
    function JSxRptConsNextFuncBrowseWahFrom(poDataNextFunc) {
        console.log('asads');
        console.log();
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tWahCode = aDataNextFunc[0];
            tWahName = aDataNextFunc[1];

        }
        if (poDataNextFunc == "NULL") {
            $('#oetRptWahCodeFrom').val("");
            $('#oetRptWahNameFrom').val("");
            $('#oetRptWahCodeTo').val("");
            $('#oetRptWahNameTo').val("");
        }
        // ประกาศตัวแปร คลังสินค้า
        var tRptWahCodeFrom, tRptWahNameFrom, tRptWahCodeTo, tRptWahNameTo
        tRptWahCodeFrom = $('#oetRptWahCodeFrom').val();
        tRptWahNameFrom = $('#oetRptWahNameFrom').val();
        tRptWahCodeTo = $('#oetRptWahCodeTo').val();
        tRptWahNameTo = $('#oetRptWahNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้า ให้ default ถึงคลังสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom != "") && (typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo == "")) {
            $('#oetRptWahCodeTo').val(tWahCode);
            $('#oetRptWahNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้า default จากคลังสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo != "") && (typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom == "")) {
            $('#oetRptWahCodeFrom').val(tWahCode);
            $('#oetRptWahNameFrom').val(tWahName);
        }

        // ประกาศตัวแปร คลังสินค้าที่โอน
        var tRptWahTCodeFrom = $('#oetRptWahTCodeFrom').val();
        var tRptWahTNameFrom = $('#oetRptWahTNameFrom').val();
        var tRptWahTCodeTo = $('#oetRptWahTCodeTo').val();
        var tRptWahTNameTo = $('#oetRptWahTNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้าที่โอน ให้ default ถึงคลังสินค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahTCodeFrom) !== 'undefined' && tRptWahTCodeFrom != "") && (typeof(tRptWahTCodeTo) !== 'undefined' && tRptWahTCodeTo == "")) {
            $('#oetRptWahTCodeTo').val(tWahCode);
            $('#oetRptWahTNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้าที่โอน default จากคลังสินค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahTCodeTo) !== 'undefined' && tRptWahTCodeTo != "") && (typeof(tRptWahTCodeFrom) !== 'undefined' && tRptWahTCodeFrom == "")) {
            $('#oetRptWahTCodeFrom').val(tWahCode);
            $('#oetRptWahTNameFrom').val(tWahName);
        }

        // ประกาศตัวแปร คลังสินค้าที่รับโอน
        var tRptWahRCodeFrom = $('#oetRptWahRCodeFrom').val();
        var tRptWahRNameFrom = $('#oetRptWahRNameFrom').val();
        var tRptWahRCodeTo = $('#oetRptWahRCodeTo').val();
        var tRptWahRNameTo = $('#oetRptWahRNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้าที่รับโอน ให้ default ถึงคลังสินค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahRCodeFrom) !== 'undefined' && tRptWahRCodeFrom != "") && (typeof(tRptWahRCodeTo) !== 'undefined' && tRptWahRCodeTo == "")) {
            $('#oetRptWahRCodeTo').val(tWahCode);
            $('#oetRptWahRNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้าที่รับโอน default จากคลังสินค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahRCodeTo) !== 'undefined' && tRptWahRCodeTo != "") && (typeof(tRptWahRCodeFrom) !== 'undefined' && tRptWahRCodeFrom == "")) {
            $('#oetRptWahRCodeFrom').val(tWahCode);
            $('#oetRptWahRNameFrom').val(tWahName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptWahRCodeTo');

    }

    // Next Function : หลังจากเลือกประเภทตู้
    function JSxRptConsNextFuncBrowsePos(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tPosCode = aDataNextFunc[0];
            tPosCode = aDataNextFunc[1];
        }

        // ประกาศตัวแปร เครื่องจุดขาย
        var tRptPosCodeFrom, tRptPosNameFrom, tRptPosCodeTo, tRptPosNameTo
        tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
        tRptPosNameFrom = $('#oetRptPosNameFrom').val();
        tRptPosCodeTo = $('#oetRptPosCodeTo').val();
        tRptPosNameTo = $('#oetRptPosNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากเครื่องจุดขาย ให้ default ถึงเครื่องจุดขาย เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom != "") && (typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo == "")) {
            $('#oetRptPosCodeTo').val(tPosCode);
            $('#oetRptPosNameTo').val(tPosCode);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงเครื่องจุดขาย default จากเครื่องจุดขาย เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo != "") && (typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom == "")) {
            $('#oetRptPosCodeFrom').val(tPosCode);
            $('#oetRptPosNameFrom').val(tPosCode);
        }

        // ประกาศตัวแปร ตู้
        var tRptLockerCodeFrom = $('#oetRptLockerCodeFrom').val();
        var tRptLockerNameFrom = $('#oetRptLockerNameFrom').val();
        var tRptLockerCodeTo = $('#oetRptLockerCodeTo').val();
        var tRptLockerNameTo = $('#oetRptLockerNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากตู้ ให้ default ถึงตู้ เป็นข้อมูลเดียวกัน
        if ((typeof(tRptLockerCodeFrom) !== 'undefined' && tRptLockerCodeFrom != "") && (typeof(tRptLockerCodeTo) !== 'undefined' && tRptLockerCodeTo == "")) {
            $('#oetRptLockerCodeTo').val(tPosCode);
            $('#oetRptLockerNameTo').val(tPosCode);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงตู้ default จากตู้ เป็นข้อมูลเดียวกัน
        if ((typeof(tRptLockerCodeTo) !== 'undefined' && tRptLockerCodeTo != "") && (typeof(tRptLockerCodeFrom) !== 'undefined' && tRptLockerCodeFrom == "")) {
            $('#oetRptLockerCodeFrom').val(tPosCode);
            $('#oetRptLockerNameFrom').val(tPosCode);
        }

        // ประกาศตัวแปร ตู้ที่โอน
        var tRptPosTCodeFrom = $('#oetRptPosTCodeFrom').val();
        var tRptPosTNameFrom = $('#oetRptPosTNameFrom').val();
        var tRptPosTCodeTo = $('#oetRptPosTCodeTo').val();
        var tRptPosTNameTo = $('#oetRptPosTNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากตู้ที่โอน ให้ default ถึงตู้ที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPosTCodeFrom) !== 'undefined' && tRptPosTCodeFrom != "") && (typeof(tRptPosTCodeTo) !== 'undefined' && tRptPosTCodeTo == "")) {
            $('#oetRptPosTCodeTo').val(tPosCode);
            $('#oetRptPosTNameTo').val(tPosCode);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงตู้ที่โอน default จากตู้ที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPosTCodeTo) !== 'undefined' && tRptPosTCodeTo != "") && (typeof(tRptPosTCodeFrom) !== 'undefined' && tRptPosTCodeFrom == "")) {
            $('#oetRptPosTCodeFrom').val(tPosCode);
            $('#oetRptPosTNameFrom').val(tPosCode);
        }


        // ประกาศตัวแปร ตู้ที่รับโอน
        var tRptPosRCodeFrom = $('#oetRptPosRCodeFrom').val();
        var tRptPosRNameFrom = $('#oetRptPosRNameFrom').val();
        var tRptPosRCodeTo = $('#oetRptPosRCodeTo').val();
        var tRptPosRNameTo = $('#oetRptPosRNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากตู้ที่รับโอน ให้ default ถึงตู้ที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPosRCodeFrom) !== 'undefined' && tRptPosRCodeFrom != "") && (typeof(tRptPosRCodeTo) !== 'undefined' && tRptPosRCodeTo == "")) {
            $('#oetRptPosRCodeTo').val(tPosCode);
            $('#oetRptPosRNameTo').val(tPosCode);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงตู้ที่รับโอน default จากตู้ที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptPosRCodeTo) !== 'undefined' && tRptPosRCodeTo != "") && (typeof(tRptPosRCodeFrom) !== 'undefined' && tRptPosRCodeFrom == "")) {
            $('#oetRptPosRCodeFrom').val(tPosCode);
            $('#oetRptPosRNameFrom').val(tPosCode);
        }

    }

    // Next Function : หลังจากเลือกบริการขนส่ง
    function JSxRptConsNextFuncBrowseCourier(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tCryCode = aDataNextFunc[0];
            tCryName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร บริษัทขนส่ง
        var tRptCourierCodeFrom, tRptCourierNameFrom, tRptCourierCodeTo, tRptCourierNameTo
        tRptCourierCodeFrom = $('#oetRptCourierCodeFrom').val();
        tRptCourierNameFrom = $('#oetRptCourierNameFrom').val();
        tRptCourierCodeTo = $('#oetRptCourierCodeTo').val();
        tRptCourierNameTo = $('#oetRptCourierNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากบริษัทขนส่ง ให้ default ถึงบริษัทขนส่ง เป็นข้อมูลเดียวกัน
        if ((typeof(tRptCourierCodeFrom) !== 'undefined' && tRptCourierCodeFrom != "") && (typeof(tRptCourierCodeTo) !== 'undefined' && tRptCourierCodeTo == "")) {
            $('#oetRptCourierCodeTo').val(tCryCode);
            $('#oetRptCourierNameTo').val(tCryName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงบริษัทขนส่ง default จากบริษัทขนส่ง เป็นข้อมูลเดียวกัน
        if ((typeof(tRptCourierCodeTo) !== 'undefined' && tRptCourierCodeTo != "") && (typeof(tRptCourierCodeFrom) !== 'undefined' && tRptCourierCodeFrom == "")) {
            $('#oetRptCourierCodeFrom').val(tCryCode);
            $('#oetRptCourierNameFrom').val(tCryName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptCourierCodeTo');

    }

    // Next Function : หลังจากประเภทช่อง
    function JSxRptConsNextFuncBrowseRack(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tRakCode = aDataNextFunc[0];
            tRakName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร บริษัทขนส่ง
        var tRptRackCodeFrom, tRptRackNameFrom, tRptRackCodeTo, tRptRackNameTo

        tRptRackCodeFrom = $('#oetSMLBrowseGroupCodeFrom').val();
        tRptRackNameFrom = $('#oetSMLBrowseGroupNameFrom').val();
        tRptRackCodeTo = $('#oetSMLBrowseGroupCodeTo').val();
        tRptRackNameTo = $('#oetSMLBrowseGroupNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากบริษัทขนส่ง ให้ default ถึงบริษัทขนส่ง เป็นข้อมูลเดียวกัน
        if ((typeof(tRptRackCodeFrom) !== 'undefined' && tRptRackCodeFrom != "") && (typeof(tRptRackCodeTo) !== 'undefined' && tRptRackCodeTo == "")) {
            $('#oetSMLBrowseGroupCodeTo').val(tRakCode);
            $('#oetSMLBrowseGroupNameTo').val(tRakName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงบริษัทขนส่ง default จากบริษัทขนส่ง เป็นข้อมูลเดียวกัน
        if ((typeof(tRptRackCodeTo) !== 'undefined' && tRptRackCodeTo != "") && (typeof(tRptRackCodeFrom) !== 'undefined' && tRptRackCodeFrom == "")) {
            $('#oetSMLBrowseGroupCodeFrom').val(tRakCode);
            $('#oetSMLBrowseGroupNameFrom').val(tRakName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetSMLBrowseGroupCodeTo');
    }

    // Next Function : หลังจากเลือกประเภทบัตร
    function JSxRptConsNextFuncBrowseCardType(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tCtyCode = aDataNextFunc[0];
            tCtyName = aDataNextFunc[1];
        }

        /*===== ฺBegin Card Type ========================================================*/
        // ประกาศตัวแปร ประเภทบัตร
        var tRPCCardTypeCodeFrom = $('#oetRptCardTypeCodeFrom').val();
        var tRPCCardTypeName = $('#oetRptCardTypeNameFrom').val();
        var tRPCCardTypeCodeTo = $('#oetRptCardTypeCodeTo').val();
        var tRPCCardTypeNameTo = $('#oetRptCardTypeNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากประเภทบัตร ให้ default ถึงประเภทบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardTypeCodeFrom) !== 'undefined' && tRPCCardTypeCodeFrom != "") && (typeof(tRPCCardTypeCodeTo) !== 'undefined' && tRPCCardTypeCodeTo == "")) {
            $('#oetRptCardTypeCodeTo').val(tCtyCode);
            $('#oetRptCardTypeNameTo').val(tCtyName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงประเภทบัตร default จากประเภทบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardTypeCodeTo) !== 'undefined' && tRPCCardTypeCodeTo != "") && (typeof(tRPCCardTypeCodeFrom) !== 'undefined' && tRPCCardTypeCodeFrom == "")) {
            $('#oetRptCardTypeCodeFrom').val(tCtyCode);
            $('#oetRptCardTypeNameFrom').val(tCtyName);
        }
        /*===== ฺEnd Card Type ============================s==============================*/

        /*===== ฺBegin Card Type (Old) ===================================================*/
        // ประกาศตัวแปร ประเภทบัตรเดิม
        var tRPCCardTypeCodeOldFrom = $('#oetRptCardTypeCodeOldFrom').val();
        var tRPCCardTypeNameOldFrom = $('#oetRptCardTypeNameOldFrom').val();
        var tRPCCardTypeCodeOldTo = $('#oetRptCardTypeCodeOldTo').val();
        var tRPCCardTypeNameOldTo = $('#oetRptCardTypeNameOldTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากประเภทบัตรเดิม ให้ default ถึงประเภทบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardTypeCodeOldFrom) !== 'undefined' && tRPCCardTypeCodeOldFrom != "") && (typeof(tRPCCardTypeCodeOldTo) !== 'undefined' && tRPCCardTypeCodeOldTo == "")) {
            $('#oetRptCardTypeCodeOldTo').val(tCtyCode);
            $('#oetRptCardTypeNameOldTo').val(tCtyName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงประเภทบัตรเดิม default จากประเภทบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardTypeCodeOldTo) !== 'undefined' && tRPCCardTypeCodeOldTo != "") && (typeof(tRPCCardTypeCodeOldFrom) !== 'undefined' && tRPCCardTypeCodeOldFrom == "")) {
            $('#oetRptCardTypeCodeOldFrom').val(tCtyCode);
            $('#oetRptCardTypeNameOldFrom').val(tCtyName);
        }
        /*===== ฺEnd Card Type (Old) =====================================================*/

        /*===== ฺBegin Card Type (New) ===================================================*/
        // ประกาศตัวแปร ประเภทบัตรใหม่
        var tRPCCardTypeCodeNewFrom = $('#oetRptCardTypeCodeNewFrom').val();
        var tRPCCardTypeNameNewFrom = $('#oetRptCardTypeNameNewFrom').val();
        var tRPCCardTypeCodeNewTo = $('#oetRptCardTypeCodeNewTo').val();
        var tRPCCardTypeNameNewTo = $('#oetRptCardTypeNameNewTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากประเภทบัตรใหม่ ให้ default ถึงประเภทบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardTypeCodeNewFrom) !== 'undefined' && tRPCCardTypeCodeNewFrom != "") && (typeof(tRPCCardTypeCodeNewTo) !== 'undefined' && tRPCCardTypeCodeNewTo == "")) {
            $('#oetRptCardTypeCodeNewTo').val(tCtyCode);
            $('#oetRptCardTypeNameNewTo').val(tCtyName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงประเภทบัตรใหม่ default จากประเภทบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardTypeCodeNewTo) !== 'undefined' && tRPCCardTypeCodeNewTo != "") && (typeof(tRPCCardTypeCodeNewFrom) !== 'undefined' && tRPCCardTypeCodeNewFrom == "")) {
            $('#oetRptCardTypeCodeNewFrom').val(tCtyCode);
            $('#oetRptCardTypeNameNewFrom').val(tCtyName);
        }
        /*===== ฺEnd Card Type (New) =====================================================*/

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptCardTypeCodeNewTo');
    }

    // Next Function : หลังจากเลือกบัตร
    function JSxRptConsNextFuncBrowseCard(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            let aDataNextFunc = JSON.parse(poDataNextFunc);
            tCrdCode = aDataNextFunc[0];
            tCrdName = aDataNextFunc[1];
        }
        /*===== Begin Card No. =========================================================*/
        // ประกาศตัวแปร หมายเลขบัตร
        let tRPCCardCodeFrom = $('#oetRptCardCodeFrom').val();
        let tRPCCardNameFrom = $('#oetRptCardNameFrom').val();
        let tRPCCardCodeTo = $('#oetRptCardCodeTo').val();
        let tRPCCardNameTo = $('#oetRptCardNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากกหมายเลขบัตร ให้ default ถึงกหมายเลขบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardCodeFrom) !== 'undefined' && tRPCCardCodeFrom != "") && (typeof(tRPCCardCodeTo) !== 'undefined' && tRPCCardCodeTo == "")) {
            $('#oetRptCardCodeTo').val(tCrdCode);
            $('#oetRptCardNameTo').val(tCrdName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงกหมายเลขบัตร default จากกหมายเลขบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardCodeTo) !== 'undefined' && tRPCCardCodeTo != "") && (typeof(tRPCCardCodeFrom) !== 'undefined' && tRPCCardCodeFrom == "")) {
            $('#oetRptCardCodeFrom').val(tCrdCode);
            $('#oetRptCardNameFrom').val(tCrdName);
        }
        /*===== End Card No. ===========================================================*/

        /*===== Begin Card No. (Old) ===================================================*/
        // ประกาศตัวแปร หมายเลขบัตรเดิม
        let tRPCCardCodeOldFrom = $('#oetRptCardCodeOldFrom').val();
        let tRPCCardNameOldFrom = $('#oetRptCardNameOldFrom').val();
        let tRPCCardCodeOldTo = $('#oetRptCardCodeOldTo').val();
        let tRPCCardNameOldTo = $('#oetRptCardNameOldTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากกหมายเลขบัตรเดิม ให้ default ถึงกหมายเลขบัตรเดิม เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardCodeOldFrom) !== 'undefined' && tRPCCardCodeOldFrom != "") && (typeof(tRPCCardCodeOldTo) !== 'undefined' && tRPCCardCodeOldTo == "")) {
            $('#oetRptCardCodeOldTo').val(tCrdCode);
            $('#oetRptCardNameOldTo').val(tCrdName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงกหมายเลขบัตรเดิม default จากกหมายเลขบัตรเดิม เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardCodeOldTo) !== 'undefined' && tRPCCardCodeOldTo != "") && (typeof(tRPCCardCodeOldFrom) !== 'undefined' && tRPCCardCodeOldFrom == "")) {
            $('#oetRptCardCodeOldFrom').val(tCrdCode);
            $('#oetRptCardNameOldFrom').val(tCrdName);
        }
        /*===== End Card No. (Old) =====================================================*/

        /*===== Begin Card No. (New) ===================================================*/
        // ประกาศตัวแปร หมายเลขบัตรเดิม
        let tRPCCardCodeNewFrom = $('#oetRptCardCodeNewFrom').val();
        let tRPCCardNameNewFrom = $('#oetRptCardNameNewFrom').val();
        let tRPCCardCodeNewTo = $('#oetRptCardCodeNewTo').val();
        let tRPCCardNameNewTo = $('#oetRptCardNameNewTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากกหมายเลขบัตรเดิม ให้ default ถึงกหมายเลขบัตรเดิม เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardCodeNewFrom) !== 'undefined' && tRPCCardCodeNewFrom != "") && (typeof(tRPCCardCodeNewTo) !== 'undefined' && tRPCCardCodeNewTo == "")) {
            $('#oetRptCardCodeNewTo').val(tCrdCode);
            $('#oetRptCardNameNewTo').val(tCrdName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงกหมายเลขบัตรเดิม default จากกหมายเลขบัตรเดิม เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCardCodeNewTo) !== 'undefined' && tRPCCardCodeNewTo != "") && (typeof(tRPCCardCodeNewFrom) !== 'undefined' && tRPCCardCodeNewFrom == "")) {
            $('#oetRptCardCodeNewFrom').val(tCrdCode);
            $('#oetRptCardNameNewFrom').val(tCrdName);
        }
        /*===== End Card No. (New) =====================================================*/

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptCardCodeTo');
    }

    // Next Function : หลังจากเลือกลูกค้า
    function JSxRptConsNextFuncBrowseCst(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tCstCode = aDataNextFunc[0];
            tCstName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร หมายเลขลูกค้า
        var tRPCCstCodeFrom = $('#oetRptCstCodeFrom').val();
        var tRPCCstNameFrom = $('#oetRptCstNameFrom').val();
        var tRPCCstCodeTo = $('#oetRptCstCodeTo').val();
        var tRPCCstNameTo = $('#oetRptCstNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากกหมายเลขลูกค้า ให้ default ถึงกหมายเลขลูกค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCstCodeFrom) !== 'undefined' && tRPCCstCodeFrom != "") && (typeof(tRPCCstCodeTo) !== 'undefined' && tRPCCstCodeTo == "")) {
            $('#oetRptCstCodeTo').val(tCstCode);
            $('#oetRptCstNameTo').val(tCstName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงกหมายเลขลูกค้า default จากกหมายเลขลูกค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCCstCodeTo) !== 'undefined' && tRPCCstCodeTo != "") && (typeof(tRPCCstCodeFrom) !== 'undefined' && tRPCCstCodeFrom == "")) {
            $('#oetRptCstCodeFrom').val(tCstCode);
            $('#oetRptCstNameFrom').val(tCstName);
        }
        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptCstCodeTo');
    }

    // Next Function : หลังจากเลือกเลเวลลูกค้า
    function JSxRptNextFuncBrowseLevelCode(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tClvCode = aDataNextFunc[0];
            tClvName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร เลเวลลูกค้า
        var tRPCClvCodeFrom = $('#oetRptCstLevelCodeFrom').val();
        var tRPCClvNameFrom = $('#oetRptCstLevelNameFrom').val();
        var tRPCClvCodeTo = $('#oetRptCstLevelCodeTo').val();
        var tRPCClvNameTo = $('#oetRptCstLevelNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse เลเวลลูกค้า ให้ default เลเวลลูกค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCClvCodeFrom) !== 'undefined' && tRPCClvCodeFrom != "") && (typeof(tRPCClvCodeTo) !== 'undefined' && tRPCClvCodeTo == "")) {
            $('#oetRptCstLevelCodeTo').val(tClvCode);
            $('#oetRptCstLevelNameTo').val(tClvName);
        }

        // เช็คข้อมูลถ้ามีการ Browse เลเวลลูกค้า default เลเวลลูกค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCClvCodeTo) !== 'undefined' && tRPCClvCodeTo != "") && (typeof(tRPCClvCodeFrom) !== 'undefined' && tRPCClvCodeFrom == "")) {
            $('#oetRptCstLevelCodeFrom').val(tClvCode);
            $('#oetRptCstLevelNameFrom').val(tClvName);
        }

    }

    // Next Function : หลังจากเลือกทะเบียนรถ
    function JSxRptNextFuncBrowseCarRegNoCode(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tClvCode = aDataNextFunc[0];
            tClvName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร เลเวลลูกค้า
        var tRPCClvCodeFrom = $('#oetRptCarRegNoFrom').val();
        var tRPCClvNameFrom = $('#oetRptCarRegNoNameFrom').val();
        var tRPCClvCodeTo = $('#oetRptCarRegNoTo').val();
        var tRPCClvNameTo = $('#oetRptCarRegNoNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse เลเวลลูกค้า ให้ default เลเวลลูกค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCClvCodeFrom) !== 'undefined' && tRPCClvCodeFrom != "") && (typeof(tRPCClvCodeTo) !== 'undefined' && tRPCClvCodeTo == "")) {
            $('#oetRptCarRegNoTo').val(tClvCode);
            $('#oetRptCarRegNoNameTo').val(tClvName);
        }

        // เช็คข้อมูลถ้ามีการ Browse เลเวลลูกค้า default เลเวลลูกค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCClvCodeTo) !== 'undefined' && tRPCClvCodeTo != "") && (typeof(tRPCClvCodeFrom) !== 'undefined' && tRPCClvCodeFrom == "")) {
            $('#oetRptCarRegNoFrom').val(tClvCode);
            $('#oetRptCarRegNoNameFrom').val(tClvName);
        }

    }

    // Next Function : หลังจากพนักงาน
    function JSxRptConsNextFuncBrowseEmp(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tCrdHolderID = aDataNextFunc[0];
            tCrdHoldername = aDataNextFunc[1];
        }

        // ประกาศตัวแปร รหัสพนักงาน
        var tRPCEmpCode, tRPCEmpName, tRPCEmpCodeTo, tRPCEmpNameTo

        tRPCEmpCode = $('#oetRptEmpCodeFrom').val();
        tRPCEmpName = $('#oetRptEmpNameFrom').val();
        tRPCEmpCodeTo = $('#oetRptEmpCodeTo').val();
        tRPCEmpNameTo = $('#oetRptEmpNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากรหัสพนักงาน ให้ default ถึงรหัสพนักงาน เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCEmpCode) !== 'undefined' && tRPCEmpCode != "") && (typeof(tRPCEmpCodeTo) !== 'undefined' && tRPCEmpCodeTo == "")) {
            $('#oetRptEmpCodeTo').val(tCrdHolderID);
            $('#oetRptEmpNameTo').val(tCrdHoldername);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงรหัสพนักงาน default จากรหัสพนักงาน เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCEmpCodeTo) !== 'undefined' && tRPCEmpCodeTo != "") && (typeof(tRPCEmpCode) !== 'undefined' && tRPCEmpCode == "")) {
            $('#oetRptEmpCodeFrom').val(tCrdHolderID);
            $('#oetRptEmpNameFrom').val(tCrdHoldername);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptEmpCodeTo');
    }

    // Next Function : ขนาดตู้ฝาก
    function JSxRptConsNextFuncBrowseShpSize(poDataNextFuncShpSize) {
        if (typeof(poDataNextFuncShpSize) != 'undefined' && poDataNextFuncShpSize != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFuncShpSize);
            tShpSizeCode = aDataNextFunc[0];
            tShpSizeName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร ขนาดช่องฝาก
        var tRptShpSizeCodeFrom, tRptShpSizeNameFrom, tRptShpSizeCodeTo, tRptShpSizeNameTo
        tRptShpSizeCodeFrom = $('#oetRptPzeCodeFrom').val();
        tRptShpSizeNameFrom = $('#oetRptPzeNameFrom').val();
        tRptShpSizeCodeTo = $('#oetRptPzeCodeTo').val();
        tRptShpSizeNameTo = $('#oetRptPzeNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากขนาดช่องฝาก ให้ default ถึงขนาดช่องฝาก เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpSizeCodeFrom) !== 'undefined' && tRptShpSizeCodeFrom != "") && (typeof(tRptShpSizeCodeTo) !== 'undefined' && tRptShpSizeCodeTo == "")) {
            $('#oetRptPzeCodeTo').val(tShpSizeCode);
            $('#oetRptPzeNameTo').val(tShpSizeName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงขนาดช่องฝาก ให้ default จากขนาดช่องฝาก  เป็นข้อมูลเดียวกัน
        if ((typeof(tRptShpSizeCodeTo) !== 'undefined' && tRptShpSizeCodeTo != "") && (typeof(tRptShpSizeCodeFrom) !== 'undefined' && tRptShpSizeCodeFrom == "")) {
            $('#oetRptPzeCodeFrom').val(tShpSizeCode);
            $('#oetRptPzeNameFrom').val(tShpSizeName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptPzeCodeTo');
    }

    // เช็คการเปลี่ยนค่าของ DateFrom
    $("#oetRptDocDateFrom").change(function() {

        var dDateFrom, dDateTo
        dDateFrom = $('#oetRptDocDateFrom').val();
        dDateTo = $('#oetRptDocDateTo').val();

        // เช็ควันที่ถ้ามีการ Browse จากวันที่ default ถึงวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateFrom) !== 'undefined' && dDateFrom != "") && (typeof(dDateTo) !== 'undefined' && dDateTo == "")) {
            $('#oetRptDocDateTo').val(dDateFrom);
        }

    });

    // เช็คการเปลี่ยนค่าของ DateTo
    $("#oetRptDocDateTo").change(function() {

        var dDateTo, dDateFrom
        dDateTo = $('#oetRptDocDateTo').val();
        dDateFrom = $('#oetRptDocDateFrom').val();

        // เช็ควันที่ถ้ามีการ Browse ถึงวันที่ default จากวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateTo) !== 'undefined' && dDateTo != "") && (typeof(dDateFrom) !== 'undefined' && dDateFrom == "")) {
            $('#oetRptDocDateFrom').val(dDateTo);
        }

    });

    // เช็คการเปลี่ยนค่าของ DateStartFrom
    $("#oetRptDateStartFrom").change(function() {

        var dDateFrom, dDateTo
        dDateFrom = $('#oetRptDateStartFrom').val();
        dDateTo = $('#oetRptDateStartTo').val();

        // เช็ควันที่ถ้ามีการ Browse จากวันที่ default ถึงวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateFrom) !== 'undefined' && dDateFrom != "") && (typeof(dDateTo) !== 'undefined' && dDateTo == "")) {
            $('#oetRptDateStartTo').val(dDateFrom);
        }

    });

    // เช็คการเปลี่ยนค่าของ DateStartTo
    $("#oetRptDateStartTo").change(function() {

        var dDateTo, dDateFrom
        dDateTo = $('#oetRptDateStartTo').val();
        dDateFrom = $('#oetRptDateStartFrom').val();

        // เช็ควันที่ถ้ามีการ Browse ถึงวันที่ default จากวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateTo) !== 'undefined' && dDateTo != "") && (typeof(dDateFrom) !== 'undefined' && dDateFrom == "")) {
            $('#oetRptDateStartFrom').val(dDateTo);
        }

    });

    // เช็คการเปลี่ยนค่าของ DateExpireFrom
    $("#oetRptDateExpireFrom").change(function() {

        var dDateFrom, dDateTo
        dDateFrom = $('#oetRptDateExpireFrom').val();
        dDateTo = $('#oetRptDateExpireTo').val();

        // เช็ควันที่ถ้ามีการ Browse จากวันที่ default ถึงวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateFrom) !== 'undefined' && dDateFrom != "") && (typeof(dDateTo) !== 'undefined' && dDateTo == "")) {
            $('#oetRptDateExpireTo').val(dDateFrom);
        }

    });

    // เช็คการเปลี่ยนค่าของ DateExpireTo
    $("#oetRptDateExpireTo").change(function() {

        var dDateTo, dDateFrom
        dDateTo = $('#oetRptDateExpireTo').val();
        dDateFrom = $('#oetRptDateExpireFrom').val();

        // เช็ควันที่ถ้ามีการ Browse ถึงวันที่ default จากวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateTo) !== 'undefined' && dDateTo != "") && (typeof(dDateFrom) !== 'undefined' && dDateFrom == "")) {
            $('#oetRptDateExpireFrom').val(dDateTo);
        }

    });

    // เปลี่ยนการ์ด
    $('#ocmRptStaCardFrom').change(function() {
        var tStaCardFrom = $('#ocmRptStaCardFrom').val();
        switch (tStaCardFrom) {
            case '1':
                tStaCardNameFrom = '<?php echo language('report/report/report', 'tRPCCardDetailStaActive1') ?>';
                break;
            case '2':
                tStaCardNameFrom = '<?php echo language('report/report/report', 'tRPCCardDetailStaActive2') ?>';
                break;
            case '3':
                tStaCardNameFrom = '<?php echo language('report/report/report', 'tRPCCardDetailStaActive3') ?>';
                break;
            default:
                tStaCardNameFrom = '<?php echo language('report/report/report', 'tCMNBlank-NA') ?>';
        }

        //Get value Name
        if ((typeof(tStaCardNameFrom) !== 'undefined' && tStaCardNameFrom != "")) {
            $('#ohdRptStaCardNameFrom').val(tStaCardNameFrom);
        }

        // ประกาศ ตัวแปร สถานะบัตร
        var tStaCard, tStaCardTo
        tStaCardFrom = $('#ocmRptStaCardFrom').val();
        tStaCardTo = $('#ocmRptStaCardTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากสถานะบัตร ให้ default ถึงสถานะบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tStaCardFrom) !== 'undefined' && tStaCardFrom != "") && (typeof(tStaCardTo) !== 'undefined' && tStaCardTo == "")) {
            $(".selectpicker-crd-sta-to").val(tStaCardFrom).selectpicker("refresh");
            $('#ohdRptStaCardNameTo').val(tStaCardNameFrom);
            // $("#ocmRptStaCardTo option[value='" + tStaCardFrom + "']").attr('selected', true).trigger('change');
        }


    });

    // เปลี่ยนการ์ด
    $('#ocmRptStaCardTo').change(function() {
        var tStaCardTo = $('#ocmRptStaCardTo').val();
        switch (tStaCardTo) {
            case '1':
                tStaCardNameTo = '<?php echo language('report/report/report', 'tRPCCardDetailStaActive1') ?>';
                break;
            case '2':
                tStaCardNameTo = '<?php echo language('report/report/report', 'tRPCCardDetailStaActive2') ?>';
                break;
            case '3':
                tStaCardNameTo = '<?php echo language('report/report/report', 'tRPCCardDetailStaActive3') ?>';
                break;
            default:
                tStaCardNameTo = '<?php echo language('report/report/report', 'tCMNBlank-NA') ?>';
        }
        // Get value Name
        if ((typeof(tStaCardNameTo) !== 'undefined' && tStaCardNameTo != "")) {
            $('#ohdRptStaCardNameTo').val(tStaCardNameTo);
        }
        // ประกาศ ตัวแปร สถานะบัตร
        var tStaCard, tStaCardTo
        tStaCardFrom = $('#ocmRptStaCardFrom').val();
        tStaCardTo = $('#ocmRptStaCardTo').val();
        // เช็คข้อมูลถ้ามีการ Browse จากสถานะบัตร ให้ default ถึงสถานะบัตร เป็นข้อมูลเดียวกัน
        if ((typeof(tStaCardTo) !== 'undefined' && tStaCardTo != "") && (typeof(tStaCardFrom) !== 'undefined' && tStaCardFrom == "")) {
            $(".selectpicker-crd-sta-from").val(tStaCardTo).selectpicker("refresh");
            $('#ohdRptStaCardNameFrom').val(tStaCardNameTo);
            // $("#ocmRptStaCard option[value='" + tStaCardTo + "']").attr('selected', true).trigger('change');
        }
    });

    // Click Button Reset Filter
    $('#obtRptClearCondition').click(function() {
        document.forms["ofmRptConditionFilter"].reset();
        // refresh Class selectpicker
        $(".selectpicker-crd-sta-from").val('').selectpicker("refresh");
        $(".selectpicker-crd-sta-to").val('').selectpicker("refresh");
        $(".bootstrap-select").val('').selectpicker("refresh");
        $('#ohdRptStaCardNameFrom').val('');
        $('#ohdRptStaCardNameTo').val('');
        // Set Defalult Month Filter
        $('#ocmRptMonth').val(tMonthDefault);
        $('#ocmRptMonth').selectpicker('refresh');
        // Set Defalut Status Booking Filter
        $('#ocmRptStaBooking').val('').selectpicker("refresh");
        // Set Defalut Status Producer Filter
        $('#ocmRptStaProducer').val('').selectpicker("refresh");

        $(".xWReportMultiFilter").val('')
        $(".xWReportMultiFilter").selectpicker('refresh');

        $('.xCNRange').prop("checked", true);
        // $('.xCNFilterSelectMode').hide();
        // $('.xCNFilterRangeMode').show();

        //$('.xCNClickConditionSelect').click();

        $('.selectpicker').selectpicker('refresh');

        //uncheck
        $('.xCNCheckboxValue').prop("checked", true);
        $('.xCNCheckboxValue').prop("disabled", false);
        $('.xCNCheckboxBlockAll').removeClass('xCNCheckboxBlockDefault');

        //ช่วงเดือน ถึง เดือน
        $('#ocmRptMonthFrom').val(tMonthDefault);
        $('#ocmRptMonthFrom').selectpicker('refresh');
        $('#ocmRptMonthTo').val(tMonthDefault);
        $('#ocmRptMonthTo').selectpicker('refresh');
    });

    var tWarningCheckBchMessage = 'กรุณาเลือกสาขาก่อนดำเนินการ';

    // Click Button Call View Before Print
    $('#obtRptViewBeforePrint').unbind().click(function() {
        if (xCNbNotSelectBchValidate()) {
            FSvCMNSetMsgWarningDialog(tWarningCheckBchMessage);
            return;
        }

        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            $('#ohdRptTypeExport').val('html');

            // ################### Set Cockie Update Form CSRF ###################
            let tCSRFTokenName = $('#csrf_token').attr("name");
            let tCSRFTokenValue = "";
            let value = "; " + document.cookie;
            let parts = value.split("; csrf_cookie_name=");
            if (parts.length == 2) {
                tCSRFTokenValue = parts.pop().split(";").shift();
            }
            $("#ofmRptConditionFilter [name='" + tCSRFTokenName + "']").val(tCSRFTokenValue);
            // ###################################################################

            JSxReportDataExport();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Click Button Call Export Excel
    $('#obtRptDownloadPdf').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            $('#ohdRptTypeExport').val('pdf');

            // ################### Set Cockie Update Form CSRF ###################
            let tCSRFTokenName = $('#csrf_token').attr("name");
            let tCSRFTokenValue = "";
            let value = "; " + document.cookie;
            let parts = value.split("; csrf_cookie_name=");
            if (parts.length == 2) {
                tCSRFTokenValue = parts.pop().split(";").shift();
            }
            $("#ofmRptConditionFilter [name='" + tCSRFTokenName + "']").val(tCSRFTokenValue);
            // ###################################################################

            JSxReportDataExport();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Click Button Call Export PDF
    $('#obtRptExportExcel').unbind().click(function() {
        if (xCNbNotSelectBchValidate()) {
            FSvCMNSetMsgWarningDialog(tWarningCheckBchMessage);
            return;
        }
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            $('#ohdRptTypeExport').val('excel');

            // ################### Set Cockie Update Form CSRF ###################
            let tCSRFTokenName = $('#csrf_token').attr("name");
            let tCSRFTokenValue = "";
            let value = "; " + document.cookie;
            let parts = value.split("; csrf_cookie_name=");
            if (parts.length == 2) {
                tCSRFTokenValue = parts.pop().split(";").shift();
            }
            $("#ofmRptConditionFilter [name='" + tCSRFTokenName + "']").val(tCSRFTokenValue);
            // ###################################################################

            JSxReportDataExport();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Click Button Call Year From
    $("#oetRptYearFrom").change(function() {

        // ประกาศตัวแปร ปี
        var dYearFrom, dYearTo
        dYearFrom = $('#oetRptYearFrom').val();
        dYearTo = $('#oetRptYearTo').val();

        // เช็ควันที่ถ้ามีการ Browse จากปี default ถึงปี เป็น ปี เดียวกัน
        if ((typeof(dYearFrom) !== 'undefined' && dYearFrom != "") && (typeof(dYearTo) !== 'undefined' && dYearTo == "")) {
            $('#oetRptYearTo').val(dYearFrom);
        }

    });

    function xCNbNotSelectBchValidate() {
        var bNotSelectedBch = $("#oetRptBchNameSelect").val() == "";
        var bIsSelectedAgen = $("#oetSpcAgncyName").val() != "";
        var bStatus = false;
        if ((bNotSelectedBch && tStaUsrLevel != "HQ") || (tStaUsrLevel == "HQ" && bIsSelectedAgen && bNotSelectedBch)) {
            bStatus = true;
        }
        return bStatus;
    }
    // Click Button Call Year From
    $("#oetRptYearTo").change(function() {

        // ประกาศตัวแปร ปี
        var dYearFrom, dYearTo
        dYearFrom = $('#oetRptYearFrom').val();
        dYearTo = $('#oetRptYearTo').val();

        // เช็ควันที่ถ้ามีการ Browse จากปี default ถึงปี เป็น ปี เดียวกัน
        if ((typeof(dYearTo) !== 'undefined' && dYearTo != "") && (typeof(dYearFrom) !== 'undefined' && dYearFrom == "")) {
            $('#oetRptYearFrom').val(dYearTo);
        }

    });
    /*===== End Event Click Button Report ============================================= */

    /*===== Begin Display Filter Mode ==================================================*/
    $(document).ready(function() {
        $('.xCNRange').prop("checked", true);
        // $('.xCNFilterSelectMode').hide();
        // $('.xCNFilterRangeMode').show();
        $('.xCNFilterSelectMode').show();
        $('.xCNFilterRangeMode').hide();
    });

    var tDataBranch = $('#oetRptBchCodeSelect').val();
    var tDataShop = $('#oetRptShpCodeSelect').val();
    if ((tDataBranch == '' && tDataShop == '') || (tDataBranch != '' && tDataShop == '')) {
        $('#obtRptMultiBrowseLockerPos').attr('disabled',true)
    }else{
        $('#obtRptMultiBrowseLockerPos').attr('disabled',false)
    }

    $('.xCNChkTypeFillter').change(function() {

        var tFilterMode = $(this).val();

        $(".xWReportMultiFilter").val('')
        $(".xWReportMultiFilter").selectpicker('refresh');
        $('.xCNFilterBox').find('input[type=text]').val('');
        $('.xCNFilterBox').find('.xCNFilterSelectMode').hide();
        $('.xCNFilterBox').find('.xCNFilterRangeMode').hide();

        switch (tFilterMode) {
            case '1': { // แบบช่วง
                $('.xCNFilterBox').find('.xCNFilterRangeMode').show();
                break;
            }
            case '2': { // แบบเลือก
                $('.xCNFilterBox').find('.xCNFilterSelectMode').show();
                break;
            }
            default: {
                return;
            }
        }

    });
    /*===== End Display Filter Mode ====================================================*/

    var oBrowseSpcMultiCstCredit = function(poReturnInput) {
        var tUsrLevel = "<?= $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tCondition = '';
        tCondition += " AND TCNMCst.FTClvCode IN ('<?= $nAllowCusCreditCode ?>') ";

        var oOptionReturn = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join: {
                Table: ['TCNMCst_L'],
                On: ['TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTCode', 'tCSTName', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMCst.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'M',
                Value: ['oetRptCstCreditCodeSelect', "TCNMCst.FTCstCode"],
                Text: ['oetRptCstCreditNameSelect', "TCNMCst_L.FTCstName"],
            },
            NextFunc: {
                FuncName: 'JSxSetDefauleCstCredit',
                ArgReturn: ['FTCstCode', 'FTCstName']
            }
        }
        return oOptionReturn;
    }

    function JSxSetDefauleCstCredit(ptData) {

    }

    var oBrowseSpcMultiBranch = function(poReturnInput) {

        var nStaSession = JCNxFuncChkSessionExpired();
        var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tWhere = "";


        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tAgnCodeWhere = poReturnInput.tAgnCodeWhere;

        if (tUsrLevel != "HQ") {
            tWhere = " AND TCNMBranch.FTBchCode IN (" + tBchCodeMulti + ") ";
        } else {
            tWhere = "";
        }

        if (tAgnCodeWhere == '' || tAgnCodeWhere == null) {
            tWhereAgn = '';
        } else {
            tWhereAgn = " AND TCNMBranch.FTAgnCode = '" + tAgnCodeWhere + "'";
        }

        var oOptionReturn = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L', 'TCNMAgency_L'],
                On: [
                    'TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                    'TCNMAgency_L.FTAgnCode = TCNMBranch.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tWhere + tWhereAgn]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName', 'TCNMAgency_L.FTAgnName', 'TCNMBranch.FTAgnCode'],
                DataColumnsFormat: ['', '', '', ''],
                DisabledColumns: [2, 3],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack: {
                // StausAll: ['oetRptBchStaSelectAll'],
                Value: ['oetRptBchCodeSelect', 'TCNMBranch.FTBchCode'],
                Text: ['oetRptBchNameSelect', 'TCNMBranch_L.FTBchName']
            },
            NextFunc: {
                FuncName: 'JSxSetDefauleSelectAllBch',
                ArgReturn: ['FTBchCode', 'FTBchName']
            }

        }
        return oOptionReturn;
    }

    function JSxSetDefauleSelectAllBch(ptData) {
        var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti = "<?php echo str_replace("'", '', $this->session->userdata("tSesUsrBchCodeMulti")); ?>";
        var tBchNameMulti = "<?php echo str_replace("'", '', $this->session->userdata("tSesUsrBchNameMulti")); ?>";

        $('#oetRptPosCodeSelect').val('');
        $('#oetRptPosNameSelect').val('');

        if (ptData[0] == null) {
            if (tUsrLevel != 'HQ') {
                $('#oetRptBchCodeSelect').val(tBchCodeMulti);
                // $('#oetRptBchNameSelect').val(tBchNameMulti);
            }
            $("#obtRptMultiBrowsePos").attr('disabled', true);
        } else {
            var nBchCount = ptData.length;
            if (nBchCount == 1) {
                $("#obtRptMultiBrowsePos").attr('disabled', false);
            } else {
                $("#obtRptMultiBrowsePos").attr('disabled', true);
            }
        }
    }

    //Function Name : Next Function Cashier
    function JSxRptConsNextFuncBrowseSpl(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tSplCode = aDataNextFunc[0];
            var tSplName = aDataNextFunc[1];

            var tSplCodeFrom = $('#oetRptSupplierCodeFrom').val();
            var tSplCodeTo = $('#oetRptSupplierCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tSplCodeFrom == "" || tSplCodeFrom === undefined) {
                $('#oetRptSupplierCodeFrom').val(tSplCode);
                $('#oetRptSupplierNameFrom').val(tSplName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tSplCodeTo == "" || tSplCodeTo === undefined) {
                $('#oetRptSupplierCodeTo').val(tSplCode);
                $('#oetRptSupplierNameTo').val(tSplName);
            }

            JSxUncheckinCheckbox('oetRptSupplierCodeTo');

        }
    }

    //Function Name : Next Function Cashier
    function JSxRptConsNextFuncBrowseSplMulti(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tSplCode = aDataNextFunc[0];
            var tSplName = aDataNextFunc[1];

            var tSplCodeFrom = $('#oetRptSupplierCodeMultiFrom').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tSplCodeFrom == "" || tSplCodeFrom === undefined) {
                $('#oetRptSupplierCodeMultiFrom').val(tSplCode);
                $('#oetRptSupplierNameMultiFrom').val(tSplName);
            }

            JSxUncheckinCheckbox('oetRptSupplierCodeTo');

        }
    }

    //Function Name : Next Function Dot
    function JSxRptConsNextFuncBrowseDot(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tDotCode = aDataNextFunc[0];
            var tDotName = aDataNextFunc[1];

            var tDotCodeFrom = $('#oetRptDotCodeFrom').val();
            var tDotCodeTo = $('#oetRptDotCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tDotCodeFrom == "" || tDotCodeFrom === undefined) {
                $('#oetRptDotCodeFrom').val(tDotCode);
                $('#oetRptDotNameFrom').val(tDotName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tDotCodeTo == "" || tDotCodeTo === undefined) {
                $('#oetRptDotCodeTo').val(tDotCode);
                $('#oetRptDotNameTo').val(tDotName);
            }

            JSxUncheckinCheckbox('oetRptDotCodeTo');

        }
    }

    //Function Name : Next Function กลุ่มลูกค้า
    function JSxRptConsNextFuncBrowseCusGrp(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tCusGrpCode = aDataNextFunc[0];
            var tCusGrpName = aDataNextFunc[1];

            var tCusGrpCodeFrom = $('#oetRptCusGrpCodeFrom').val();
            var tCusGrpCodeTo = $('#oetRptCusGrpCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tCusGrpCodeFrom == "" || tCusGrpCodeFrom === undefined) {
                $('#oetRptCusGrpCodeFrom').val(tCusGrpCode);
                $('#oetRptCusGrpNameFrom').val(tCusGrpName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tCusGrpCodeTo == "" || tCusGrpCodeTo === undefined) {
                $('#oetRptCusGrpCodeTo').val(tCusGrpCode);
                $('#oetRptCusGrpNameTo').val(tCusGrpName);
            }

            JSxUncheckinCheckbox('oetRptCusGrpCodeTo');

        }
    }

    //Function Name : Next Function ประเภทลูกค้า
    function JSxRptConsNextFuncBrowseCusType(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tCusTypeCode = aDataNextFunc[0];
            var tCusTypeName = aDataNextFunc[1];

            var tCusTypeCodeFrom = $('#oetRptCusTypeCodeFrom').val();
            var tCusTypeCodeTo = $('#oetRptCusTypeCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tCusTypeCodeFrom == "" || tCusTypeCodeFrom === undefined) {
                $('#oetRptCusTypeCodeFrom').val(tCusTypeCode);
                $('#oetRptCusTypeNameFrom').val(tCusTypeName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tCusTypeCodeTo == "" || tCusTypeCodeTo === undefined) {
                $('#oetRptCusTypeCodeTo').val(tCusTypeCode);
                $('#oetRptCusTypeNameTo').val(tCusTypeName);
            }

            JSxUncheckinCheckbox('oetRptCusTypeCodeTo');

        }
    }

    //Function Name : Next Function หมวดหมู่สินค้าหลัก
    function JSxRptConsNextFuncBrowseCate1(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tCate1Code = aDataNextFunc[0];
            var tCate1Name = aDataNextFunc[1];

            var tCate1CodeFrom = $('#oetRptCate1CodeFrom').val();
            var tCate1CodeTo = $('#oetRptCate1CodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tCate1CodeFrom == "" || tCate1CodeFrom === undefined) {
                $('#oetRptCate1CodeFrom').val(tCate1Code);
                $('#oetRptCate1NameFrom').val(tCate1Name);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tCate1CodeTo == "" || tCate1CodeTo === undefined) {
                $('#oetRptCate1CodeTo').val(tCate1Code);
                $('#oetRptCate1NameTo').val(tCate1Name);
            }

            JSxUncheckinCheckbox('oetRptCate1CodeTo');

        }
    }


    //Function Name : Next Function หมวดหมู่สินค้าย่อย
    function JSxRptConsNextFuncBrowseCate2(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tCate2Code = aDataNextFunc[0];
            var tCate2Name = aDataNextFunc[1];

            var tCate2CodeFrom = $('#oetRptCate2CodeFrom').val();
            var tCate2CodeTo = $('#oetRptCate2CodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tCate2CodeFrom == "" || tCate2CodeFrom === undefined) {
                $('#oetRptCate2CodeFrom').val(tCate2Code);
                $('#oetRptCate2NameFrom').val(tCate2Name);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tCate2CodeTo == "" || tCate2CodeTo === undefined) {
                $('#oetRptCate2CodeTo').val(tCate2Code);
                $('#oetRptCate2NameTo').val(tCate2Name);
            }

            JSxUncheckinCheckbox('oetRptCate2CodeTo');

        }
    }

    //Function Name : Next Function เลขที่เอกสาร Promotion
    function JSxRptConsNextFuncBrowseDocPromotion(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tDocPromotionCode = aDataNextFunc[0];
            var tDocPromotionName = aDataNextFunc[1];

            var tDocPromotionCodeFrom = $('#oetRptDocPromotionCodeFrom').val();
            var tDocPromotionCodeTo = $('#oetRptDocPromotionCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tDocPromotionCodeFrom == "" || tDocPromotionCodeFrom === undefined) {
                $('#oetRptDocPromotionCodeFrom').val(tDocPromotionCode);
                $('#oetRptDocPromotionNameFrom').val(tDocPromotionName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tDocPromotionCodeTo == "" || tDocPromotionCodeTo === undefined) {
                $('#oetRptDocPromotionCodeTo').val(tDocPromotionCode);
                $('#oetRptDocPromotionNameTo').val(tDocPromotionName);
            }

            JSxUncheckinCheckbox('oetRptDocPromotionCodeTo');

        }
    }

    //Function Name : Next Function Coupon
    function JSxRptConsNextFuncBrowseCoupon(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tCouponCode = aDataNextFunc[0];
            var tCouponName = aDataNextFunc[1];

            var tCouponCodeFrom = $('#oetRptCouponCodeFrom').val();
            var tCouponCodeTo = $('#oetRptCouponCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tCouponCodeFrom == "" || tCouponCodeFrom === undefined) {
                $('#oetRptCouponCodeFrom').val(tCouponCode);
                $('#oetRptCouponNameFrom').val(tCouponName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tCouponCodeTo == "" || tCouponCodeTo === undefined) {
                $('#oetRptCouponCodeTo').val(tCouponCode);
                $('#oetRptCouponNameTo').val(tCouponName);
            }

            JSxUncheckinCheckbox('oetRptCouponCodeTo');

        }
    }

    //Function Name : Next Function Cashier
    function JSxRptConsNextFuncBrowseBrand(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tPbnCode = aDataNextFunc[0];
            var tPbnName = aDataNextFunc[1];

            var tPbnCodeFrom = $('#oetRptBrandCodeFrom').val();
            var tPbnCodeTo = $('#oetRptBrandCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tPbnCodeFrom == "" || tPbnCodeFrom === undefined) {
                $('#oetRptBrandCodeFrom').val(tPbnCode);
                $('#oetRptBrandNameFrom').val(tPbnName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tPbnCodeTo == "" || tPbnCodeTo === undefined) {
                $('#oetRptBrandCodeTo').val(tPbnCode);
                $('#oetRptBrandNameTo').val(tPbnName);
            }

            JSxUncheckinCheckbox('oetRptBrandCodeTo');

        }
    }

    //Function Name : Next Function
    function JSxRptConsNextFuncBrowseModel(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tPmoCode = aDataNextFunc[0];
            var tPmoName = aDataNextFunc[1];

            var tPmoCodeFrom = $('#oetRptModelCodeFrom').val();
            var tPmoCodeTo = $('#oetRptModelCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tPmoCodeFrom == "" || tPmoCodeFrom === undefined) {
                $('#oetRptModelCodeFrom').val(tPmoCode);
                $('#oetRptModelNameFrom').val(tPmoName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tPmoCodeTo == "" || tPmoCodeTo === undefined) {
                $('#oetRptModelCodeTo').val(tPmoCode);
                $('#oetRptModelNameTo').val(tPmoName);
            }

            JSxUncheckinCheckbox('oetRptModelCodeTo');

        }
    }

    // ========================================== Event Browse Multi Merchant ==========================================
    $('#obtRptMultiBrowseMerchant').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oMerchantBrowseMultiOption = undefined;
            oMerchantBrowseMultiOption = {
                Title: ['company/merchant/merchant', 'tMerchantTitle'],
                Table: {
                    Master: 'TCNMMerchant',
                    PK: 'FTMerCode'
                },
                Join: {
                    Table: ['TCNMMerchant_L'],
                    On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = ' + nLangEdits]
                },
                GrideView: {
                    ColumnPathLang: 'company/merchant/merchant',
                    ColumnKeyLang: ['tMerCode', 'tMerName'],
                    ColumnsSize: ['15%', '75%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMMerchant.FTMerCode', 'TCNMMerchant_L.FTMerName'],
                    DataColumnsFormat: ['', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMMerchant.FDCreateOn DESC'],
                },
                CallBack: {
                    StausAll: ['oetRptMerStaSelectAll'],
                    Value: ['oetRptMerCodeSelect', 'TCNMMerchant.FTMerCode'],
                    Text: ['oetRptMerNameSelect', 'TCNMMerchant_L.FTMerName'],
                },
            };
            JCNxBrowseMultiSelect('oMerchantBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ============================================ Event Browse Multi Shop ============================================
    $('#obtRptMultiBrowseShop').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetRptBchCodeSelect').val();
            let tDataMerchant = $('#oetRptMerCodeSelect').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch) {
                if (tDataBranch != '') {
                    var tDataBranchWhere = tDataBranch.replaceAll("','", "','");
                    tTextWhereInBranch = ' AND (TCNMShop.FTBchCode IN (' + tDataBranchWhere + '))';
                }
            }


            // ********** Check Data Branch **********
            let tTextWhereInMerchant = '';
            if (tDataMerchant) {
                if (tDataMerchant != '') {
                    tTextWhereInMerchant = ' AND (TCNMShop.FTMerCode IN (' + tDataMerchant + '))';
                }
            }

            window.oShopBrowseMultiOption = undefined;
            oShopBrowseMultiOption = {
                Title: ['company/shop/shop', 'tSHPTitle_POS'],
                Table: {
                    Master: 'TCNMShop',
                    PK: 'FTShpCode'
                },
                Join: {
                    Table: ['TCNMShop_L', 'TCNMBranch_L'],
                    On: [
                        'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode      AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                        'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode    AND TCNMBranch_L.FNLngID = ' + nLangEdits
                    ]
                },
                Where: {
                    Condition: [tTextWhereInBranch + tTextWhereInMerchant]
                },
                GrideView: {
                    ColumnPathLang: 'company/shop/shop',
                    ColumnKeyLang: ['tSHPTBBranch', 'tSHPTBCode', 'tSHPTBName'],
                    ColumnsSize: ['15%', '15%', '75%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMBranch_L.FTBchName', 'TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                    DataColumnsFormat: ['', '', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMShop.FDCreateOn DESC, TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
                },
                CallBack: {
                    StausAll: ['oetRptShpStaSelectAll'],
                    Value: ['oetRptShpCodeSelect', "TCNMShop.FTShpCode"],
                    Text: ['oetRptShpNameSelect', "TCNMShop_L.FTShpName"]
                },
                NextFunc: {
                    FuncName: 'JSxControllBrowseShopPos',
                },
            };
            JCNxBrowseMultiSelect('oShopBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxControllBrowseShopPos(){  
        var tBchName = $('#oetRptBchNameSelect').val();
        var tShpName = $('#oetRptShpCodeSelect').val();
        var aCountBch = tBchName.split(',');
        if (aCountBch.length == 1 && tBchName != '' && tShpName != '') {
            $('#obtRptMultiBrowseLockerPos').attr('disabled',false)
        } else {
            $('#obtRptMultiBrowseLockerPos').attr('disabled',true)
        }
    }

    // ============================================= Event Browse Multi Pos ============================================
    $('#obtRptMultiBrowsePos').unbind().click(function() {
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

            let tDataBranch = $('#oetRptBchCodeSelect').val();
            let tDataShop = $('#oetRptShpCodeSelect').val();

            // ********** Check Data Branch ********** 
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                var tDataBranchWhere = tDataBranch.replaceAll(",", "','");
                tTextWhereInBranch = " AND (TCNMPos.FTBchCode IN ('" + tDataBranchWhere + "'))";
            }

            // ********** Check Data Shop **********
            let tTextWhereInShop = '';
            if (tDataShop) {
                if (tDataShop != '') {
                    var tDataShopWhere = tDataShop.replaceAll(",", "','");
                    tTextWhereInShop = " AND (TVDMPosShop.FTShpCode IN ('" + tDataShopWhere + "'))";
                }
            }

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
                    Value: ['oetRptPosCodeSelect', "TCNMPos.FTPosCode"],
                    Text: ['oetRptPosNameSelect', "TCNMPos_L.FTPosName"]
                },
            };
            JCNxBrowseMultiSelect('oPosBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ========================================= Event Browse Multi Locker Pos =========================================
    $('#obtRptMultiBrowseLockerPos').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetRptBchCodeSelect').val();
            let tDataShop = $('#oetRptShpCodeSelect').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                tTextWhereInBranch = ' AND (TRTMShopPos.FTBchCode IN (' + tDataBranch + '))';
            }

            // ********** Check Data Shop **********
            let tTextWhereInShop = '';
            if (tDataShop != '') {
                tTextWhereInShop = ' AND (TRTMShopPos.FTShpCode IN (' + tDataShop + '))';
            }

            window.oLKPosBrowseMultiOption = undefined;
            oLKPosBrowseMultiOption = {
                Title: ["pos/salemachine/salemachine", "tPOSTitle"],
                Table: {
                    Master: 'TCNMPos',
                    PK: 'FTPosCode'
                },
                Join: {
                    Table: ['TRTMShopPos', 'TCNMBranch_L', 'TCNMShop_L','TCNMPos_L'],
                    On: [
                        'TCNMPos.FTPosCode = TRTMShopPos.FTPosCode AND TCNMPos.FTBchCode = TRTMShopPos.FTBchCode AND TRTMShopPos.FTPshStaUse = 1',
                        'TRTMShopPos.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                        'TRTMShopPos.FTBchCode = TCNMShop_L.FTBchCode AND TRTMShopPos.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                        'TCNMPos_L.FTBchCode = TCNMPos.FTBchCode AND TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FNLngID = ' + nLangEdits
                    ]
                },
                Where: {
                    Condition: ['AND (TCNMPos.FTPosType IN (5)) ' + tTextWhereInBranch + tTextWhereInShop]
                },
                GrideView: {
                    ColumnPathLang: 'pos/salemachine/salemachine',
                    ColumnKeyLang: ['tPOSCode','tPOSName', 'tPOSBranchRef', 'tPOSShopRef'],
                    ColumnsSize: ['20%', '20%','20%', '20%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName', 'TCNMBranch_L.FTBchName', 'TCNMShop_L.FTShpName'],
                    DataColumnsFormat: ['', '', '', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMPos.FDCreateOn DESC, TCNMPos.FTPosCode ASC'],
                },
                CallBack: {
                    StausAll: ['oetRptLockerStaSelectAll'],
                    Value: ['oetRptLockerCodeSelect', "TCNMPos.FTPosCode"],
                    Text: ['oetRptLockerNameSelect', "TCNMPos_L.FTPosName"]
                },
                // DebugSQL: true
            };
            JCNxBrowseMultiSelect('oLKPosBrowseMultiOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //ฟังก์ชั่น สำหรับเอา checkbox ออก
    function JSxUncheckinCheckbox(ptID) {
        var tValueinInput = $('#' + ptID).val();
        if (tValueinInput != '' || tValueinInput != null) {
            var oElm = $('#' + ptID).parents('.xCNDataCondition').find('td:eq(1)').find('.xCNCheckboxValue');
            $('#' + ptID).parents('.xCNDataCondition').find('td:eq(1)').find('span').addClass('xCNCheckboxBlockDefault xCNCheckboxBlockAll');
            $(oElm).prop("checked", false);
            // $(oElm).prop("disabled",true);
        }
    }


    //Browse Event Cashier ( Added By Napat(Jame) 09/03/2563 )
    $('#obtBrowseCashierFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oOptionReturnCashierFrom = undefined;
            oOptionReturnCashierFrom = oRptBrowseCashierFrom({
                'tReturnInputCashierCode': 'oetRptCashierCodeFrom',
                'tReturnInputCashierName': 'oetRptCashierNameFrom',
                'tBchCodeFrom': $('#oetRptBchCodeFrom').val(),
                'tBchCodeTo': $('#oetRptBchCodeTo').val(),
                'tShpCodeFrom': $('#oetRptShpCodeFrom').val(),
                'tShpCodeTo': $('#oetRptShpCodeTo').val(),
                'tBchCodeSelect': $('#oetRptBchCodeSelect').val(),
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCashier',
                'aArgReturn': ['FTUsrCode', 'FTUsrName']
            });
            JCNxBrowseData('oOptionReturnCashierFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtBrowseCashierTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oOptionReturnCashierFrom = undefined;
            oOptionReturnCashierFrom = oRptBrowseCashierFrom({
                'tReturnInputCashierCode': 'oetRptCashierCodeTo',
                'tReturnInputCashierName': 'oetRptCashierNameTo',
                'tBchCodeFrom': $('#oetRptBchCodeFrom').val(),
                'tBchCodeTo': $('#oetRptBchCodeTo').val(),
                'tShpCodeFrom': $('#oetRptShpCodeFrom').val(),
                'tBchCodeSelect': $('#oetRptBchCodeSelect').val(),
                'tShpCodeTo': $('#oetRptShpCodeTo').val(),
                'tNextFuncName': 'JSxRptConsNextFuncBrowseCashier',
                'aArgReturn': ['FTUsrCode', 'FTUsrName']
            });
            JCNxBrowseData('oOptionReturnCashierFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event Shop
    $('#obtDepositBrowseAccount').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tRptModCode = $('#ohdRptModCode').val();
            let tBbkAccNoFrom = $('#oetRptBbkAccNoFrom').val();
            let tRptBbkAccNoTo = $('#oetRptBbkAccNoTo').val();
            window.oOptionReturnBbk = undefined;
            oOptionReturnBbk = oRptBrowseBBk({
                'tBbkReturnInputCode': 'oetRptBbkAccNoFrom',
                'tBbkReturnInputName': 'oetRptBbkAccNameFrom',
                'tRptModCode': tRptModCode,
                'tBbkAccNoFrom': tBbkAccNoFrom,
                'tRptBbkAccNoTo': tRptBbkAccNoTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseBbkFrom',
                'aArgReturnBbk': ['FTBbkCode', 'FTBbkAccNo']
            });
            JCNxBrowseData('oOptionReturnBbk');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    $('#obtDepositBrowseAccountTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tReturnInputBbkCode = $('#ohdRptModCode').val();
            let tBbkAccNoFrom = $('#oetRptBbkAccNoFrom').val();
            let tRptBbkAccNoTo = $('#oetRptBbkAccNoTo').val();
            window.oOptionReturnBbkTo = undefined;
            oOptionReturnBbkTo = oRptBrowseBBk({
                'tReturnInputCode': 'oetRptBbkAccNoTo',
                'tReturnInputName': 'oetRptBbkAccNameTo',
                'tRptModCode': tReturnInputBbkCode,
                'tBbkAccNoFrom': tBbkAccNoFrom,
                'tRptBbkAccNoTo': tRptBbkAccNoTo,
                'tNextFuncName': 'JSxRptConsNextFuncBrowseBbkTo',
                'aArgReturnBbk': ['FTBbkCode', 'FTBbkAccNo']
            });
            JCNxBrowseData('oOptionReturnBbkTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxRptConsNextFuncBrowseBbkFrom(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            console.log(aDataNextFunc);
            var tCsrCode = aDataNextFunc[0];
            var tCardID = aDataNextFunc[1];
            $('#oetRptBbkAccNoFrom').val(tCsrCode);
            $('#oetRptBbkAccNameFrom').val(tCardID);

        }
    }

    function JSxRptConsNextFuncBrowseBbkTo(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            console.log(aDataNextFunc);
            var tCsrCode = aDataNextFunc[0];
            var tCardID = aDataNextFunc[1];
            $('#oetRptBbkAccNoTo').val(tCsrCode);
            $('#oetRptBbkAccNameTo').val(tCardID);
        }
    }

    //Function Name : Next Function Cashier
    //Create by     : Napat(Jame)
    //Date Create   : 09/03/2563
    function JSxRptConsNextFuncBrowseCashier(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            var tCashierCode = aDataNextFunc[0];
            var tCashierName = aDataNextFunc[1];

            var tCashierCodeFrom = $('#oetRptCashierCodeFrom').val();
            var tCashierCodeTo = $('#oetRptCashierCodeTo').val();

            //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
            if (tCashierCodeFrom == "" || tCashierCodeFrom === undefined) {
                $('#oetRptCashierCodeFrom').val(tCashierCode);
                $('#oetRptCashierNameFrom').val(tCashierName);
            }

            //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
            if (tCashierCodeTo == "" || tCashierCodeTo === undefined) {
                $('#oetRptCashierCodeTo').val(tCashierCode);
                $('#oetRptCashierNameTo').val(tCashierName);
            }

            JSxUncheckinCheckbox('oetRptCashierCodeTo');

        }
    }

    // จากกลุ่มราคาที่มีผล
    $('#obtRPCBrowseEffectivePriceGroupFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptEffectivePriceGroupFrom = undefined;
            oRptEffectivePriceGroupFrom = oRPCBrowseEffectivePriceGroupOption({
                'tReturnInputCode': 'oetRptEffectivePriceGroupCodeFrom',
                'tReturnInputName': 'oetRptEffectivePriceGroupNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseEffectivePriceGroup',
                'aArgReturn': ['FTPplCode', 'FTPplName'] // ['FTPplCode','FTPplName']
            });
            JCNxBrowseData('oRptEffectivePriceGroupFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    // ถึงกลุ่มร่าคาที่มีผล
    $('#obtRPCBrowseEffectivePriceGroupTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptEffectivePriceGroupTo = undefined;
            oRptEffectivePriceGroupTo = oRPCBrowseEffectivePriceGroupOption({
                'tReturnInputCode': 'oetRptEffectivePriceGroupCodeTo',
                'tReturnInputName': 'oetRptEffectivePriceGroupNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowseEffectivePriceGroup',
                'aArgReturn': ['FTPplCode', 'FTPplName'] // ['FTPplCode','FTPplName']
            });
            JCNxBrowseData('oRptEffectivePriceGroupTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // Begin Option Effective Price Group Option (กลุ่มราคาที่มีผล)
    var oRPCBrowseEffectivePriceGroupOption = function(poReturnInput) {
        let tInputReturnCode = poReturnInput.tReturnInputCode;
        let tInputReturnName = poReturnInput.tReturnInputName;
        let tNextFuncName = poReturnInput.tNextFuncName;
        let aArgReturn = poReturnInput.aArgReturn;

        let tAgnCode = $('#oetSpcAgncyCode').val();
        let tCondition = '';
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMPdtPriList.FTAgnCode = '" + tAgnCode + "' OR TCNMPdtPriList.FTAgnCode = ''";
        }

        let oCstOptionReturn = {
            Title: ['product/pdtpricelist/pdtpricelist', 'tPPLTitle'],
            Table: {
                Master: 'TCNMPdtPriList',
                PK: 'FTPplCode'
            },
            Join: {
                Table: ['TCNMPdtPriList_L'],
                On: ['TCNMPdtPriList_L.FTPplCode = TCNMPdtPriList.FTPplCode AND TCNMPdtPriList_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'product/pdtpricelist/pdtpricelist',
                ColumnKeyLang: ['tPPLTBCode', 'tPPLTBName', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtPriList.FTPplCode', 'TCNMPdtPriList_L.FTPplName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtPriList.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMPdtPriList.FTPplCode"],
                Text: [tInputReturnName, "TCNMPdtPriList_L.FTPplName"],
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
        }
        return oCstOptionReturn;
    }

    // Functionality : Next Function Effective Price Group And Check Data
    // Parameter : Event Next Func Modal
    // Create : 16/09/2020 Piya
    // Return : Clear Velues Data
    // Return Type : -
    function JSxRptConsNextFuncBrowseEffectivePriceGroup(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tEffectivePriceGroupCode = aDataNextFunc[0];
            tEffectivePriceGroupName = aDataNextFunc[1];
        }

        /*===== Begin EffectivePriceGroup No. =========================================================*/
        // ประกาศตัวแปร กลุ่มราคาที่มีผล
        var tRPCEffectivePriceGroupCodeFrom = $('#oetRptEffectivePriceGroupCodeFrom').val();
        var tRPCEffectivePriceGroupNameFrom = $('#oetRptEffectivePriceGroupNameFrom').val();
        var tRPCEffectivePriceGroupCodeTo = $('#oetRptEffectivePriceGroupCodeTo').val();
        var tRPCEffectivePriceGroupNameTo = $('#oetRptEffectivePriceGroupNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากกลุ่มราคาที่มีผล ให้ default ถึงกลุ่มราคาที่มีผล เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCEffectivePriceGroupCodeFrom) !== 'undefined' && tRPCEffectivePriceGroupCodeFrom != "") && (typeof(tRPCEffectivePriceGroupCodeTo) !== 'undefined' && tRPCEffectivePriceGroupCodeTo == "")) {
            $('#oetRptEffectivePriceGroupCodeTo').val(tEffectivePriceGroupCode);
            $('#oetRptEffectivePriceGroupNameTo').val(tEffectivePriceGroupName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงกลุ่มราคาที่มีผล default จากกลุ่มราคาที่มีผล เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCEffectivePriceGroupCodeTo) !== 'undefined' && tRPCEffectivePriceGroupCodeTo != "") && (typeof(tRPCEffectivePriceGroupCodeFrom) !== 'undefined' && tRPCEffectivePriceGroupCodeFrom == "")) {
            $('#oetRptEffectivePriceGroupCodeFrom').val(tEffectivePriceGroupCode);
            $('#oetRptEffectivePriceGroupNameFrom').val(tEffectivePriceGroupName);
        }
        /*===== End EffectivePriceGroup No. ===========================================================*/

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptEffectivePriceGroupCodeTo');
    }

    // Click Button Effective Date
    $('#obtRptBrowseEffectiveDateFrom').unbind().click(function() {
        $('#oetRptEffectiveDateFrom').datepicker('show');
    });
    $('#obtRptBrowseEffectiveDateTo').unbind().click(function() {
        $('#oetRptEffectiveDateTo').datepicker('show');
    });

    // เช็คการเปลี่ยนค่าของ Effective Date From
    $("#oetRptEffectiveDateFrom").change(function() {
        var dDateFrom, dDateTo
        dDateFrom = $('#oetRptEffectiveDateFrom').val();
        dDateTo = $('#oetRptEffectiveDateTo').val();

        // เช็ควันที่ถ้ามีการ Browse จากวันที่ default ถึงวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateFrom) !== 'undefined' && dDateFrom != "") && (typeof(dDateTo) !== 'undefined' && dDateTo == "")) {
            $('#oetRptEffectiveDateTo').val(dDateFrom);
        }
    });

    // เช็คการเปลี่ยนค่าของ Effective Date To
    $("#oetRptEffectiveDateTo").change(function() {
        var dDateTo, dDateFrom
        dDateTo = $('#oetRptEffectiveDateTo').val();
        dDateFrom = $('#oetRptEffectiveDateFrom').val();

        // เช็ควันที่ถ้ามีการ Browse ถึงวันที่ default จากวันที่ เป็นวันที่เดียวกัน
        if ((typeof(dDateTo) !== 'undefined' && dDateTo != "") && (typeof(dDateFrom) !== 'undefined' && dDateFrom == "")) {
            $('#oetRptEffectiveDateFrom').val(dDateTo);
        }
    });

    // จากหน่วยสินค้า
    $('#obtRPCBrowsePdtUnitFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptPdtUnitFrom = undefined;
            oRptPdtUnitFrom = oRPCBrowsePdtUnitOption({
                'tReturnInputCode': 'oetRptPdtUnitCodeFrom',
                'tReturnInputName': 'oetRptPdtUnitNameFrom',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdtUnit',
                'aArgReturn': ['FTPunCode', 'FTPunName'] // ['FTPunCode','FTPunName']
            });
            JCNxBrowseData('oRptPdtUnitFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    // ถึงหน่วยสินค้า
    $('#obtRPCBrowsePdtUnitTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRptPdtUnitTo = undefined;
            oRptPdtUnitTo = oRPCBrowsePdtUnitOption({
                'tReturnInputCode': 'oetRptPdtUnitCodeTo',
                'tReturnInputName': 'oetRptPdtUnitNameTo',
                'tNextFuncName': 'JSxRptConsNextFuncBrowsePdtUnit',
                'aArgReturn': ['FTPunCode', 'FTPunName'] // ['FTPunCode','FTPunName']
            });
            JCNxBrowseData('oRptPdtUnitTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Begin Option Product Unit Option (หน่วยสินค้า)
    var oRPCBrowsePdtUnitOption = function(poReturnInput) {
        let tInputReturnCode = poReturnInput.tReturnInputCode;
        let tInputReturnName = poReturnInput.tReturnInputName;
        let tNextFuncName = poReturnInput.tNextFuncName;
        let aArgReturn = poReturnInput.aArgReturn;

        let tAgnCode = $('#oetSpcAgncyCode').val();
        let tCondition = '';
        if (tAgnCode != '' && tAgnCode != undefined) {
            tCondition += " AND TCNMPdtUnit.FTAgnCode = '" + tAgnCode + "' OR TCNMPdtUnit.FTAgnCode = ''";
        }

        let oOptionReturn = {
            Title: ['product/pdtunit/pdtunit', 'tPUNTitle'],
            Table: {
                Master: 'TCNMPdtUnit',
                PK: 'FTPunCode'
            },
            Join: {
                Table: ['TCNMPdtUnit_L'],
                On: ['TCNMPdtUnit_L.FTPunCode = TCNMPdtUnit.FTPunCode AND TCNMPdtUnit_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'product/pdtunit/pdtunit',
                ColumnKeyLang: ['tPUNCode', 'tPUNName', ''],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtUnit.FTPunCode', 'TCNMPdtUnit_L.FTPunName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtUnit.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMPdtUnit.FTPunCode"],
                Text: [tInputReturnName, "TCNMPdtUnit_L.FTPunName"],
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
        }
        return oOptionReturn;
    }

    // Functionality : Next Function Product Unit And Check Data
    // Parameter : Event Next Func Modal
    // Create : 16/09/2020 Piya
    // Return : Clear Velues Data
    // Return Type : -
    function JSxRptConsNextFuncBrowsePdtUnit(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tPdtUnitCode = aDataNextFunc[0];
            tPdtUnitName = aDataNextFunc[1];
        }

        /*===== Begin PdtUnit No. =========================================================*/
        // ประกาศตัวแปร หน่วยสินค้า
        var tRPCPdtUnitCodeFrom = $('#oetRptPdtUnitCodeFrom').val();
        var tRPCPdtUnitNameFrom = $('#oetRptPdtUnitNameFrom').val();
        var tRPCPdtUnitCodeTo = $('#oetRptPdtUnitCodeTo').val();
        var tRPCPdtUnitNameTo = $('#oetRptPdtUnitNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากหน่วยสินค้า ให้ default ถึงหน่วยสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCPdtUnitCodeFrom) !== 'undefined' && tRPCPdtUnitCodeFrom != "") && (typeof(tRPCPdtUnitCodeTo) !== 'undefined' && tRPCPdtUnitCodeTo == "")) {
            $('#oetRptPdtUnitCodeTo').val(tPdtUnitCode);
            $('#oetRptPdtUnitNameTo').val(tPdtUnitName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงหน่วยสินค้า default จากหน่วยสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRPCPdtUnitCodeTo) !== 'undefined' && tRPCPdtUnitCodeTo != "") && (typeof(tRPCPdtUnitCodeFrom) !== 'undefined' && tRPCPdtUnitCodeFrom == "")) {
            $('#oetRptPdtUnitCodeFrom').val(tPdtUnitCode);
            $('#oetRptPdtUnitNameFrom').val(tPdtUnitName);
        }
        /*===== End PdtUnit No. ===========================================================*/

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptPdtUnitCodeTo');
    }




    // Next Function : หลังจากเลือกคลังโอน
    function JSxRptConsNextFuncBrowseWahFromOut(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tWahCode = aDataNextFunc[0];
            tWahName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร คลังสินค้า
        var tRptWahCodeFrom, tRptWahNameFrom, tRptWahCodeTo, tRptWahNameTo
        tRptWahCodeFrom = $('#oetRptWahCodeFromOut').val();
        tRptWahNameFrom = $('#oetRptWahNameFromOut').val();
        tRptWahCodeTo = $('#oetRptWahCodeTo').val();
        tRptWahNameTo = $('#oetRptWahNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้า ให้ default ถึงคลังสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom != "") && (typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo == "")) {
            $('#oetRptWahCodeTo').val(tWahCode);
            $('#oetRptWahNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้า default จากคลังสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo != "") && (typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom == "")) {
            $('#oetRptWahCodeFromOut').val(tWahCode);
            $('#oetRptWahNameFromOut').val(tWahName);
        }

        // ประกาศตัวแปร คลังสินค้าที่โอน
        var tRptWahTCodeFrom = $('#oetRptWahTCodeFromOut').val();
        var tRptWahTNameFrom = $('#oetRptWahTNameFromOut').val();
        var tRptWahTCodeTo = $('#oetRptWahTCodeTo').val();
        var tRptWahTNameTo = $('#oetRptWahTNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้าที่โอน ให้ default ถึงคลังสินค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahTCodeFrom) !== 'undefined' && tRptWahTCodeFrom != "") && (typeof(tRptWahTCodeTo) !== 'undefined' && tRptWahTCodeTo == "")) {
            $('#oetRptWahTCodeTo').val(tWahCode);
            $('#oetRptWahTNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้าที่โอน default จากคลังสินค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahTCodeTo) !== 'undefined' && tRptWahTCodeTo != "") && (typeof(tRptWahTCodeFrom) !== 'undefined' && tRptWahTCodeFrom == "")) {
            $('#oetRptWahTCodeFromOut').val(tWahCode);
            $('#oetRptWahTNameFromOut').val(tWahName);
        }

        // ประกาศตัวแปร คลังสินค้าที่รับโอน
        var tRptWahRCodeFrom = $('#oetRptWahRCodeFromOut').val();
        var tRptWahRNameFrom = $('#oetRptWahRNameFromOut').val();
        var tRptWahRCodeTo = $('#oetRptWahRCodeTo').val();
        var tRptWahRNameTo = $('#oetRptWahRNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้าที่รับโอน ให้ default ถึงคลังสินค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahRCodeFrom) !== 'undefined' && tRptWahRCodeFrom != "") && (typeof(tRptWahRCodeTo) !== 'undefined' && tRptWahRCodeTo == "")) {
            $('#oetRptWahRCodeTo').val(tWahCode);
            $('#oetRptWahRNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้าที่รับโอน default จากคลังสินค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahRCodeTo) !== 'undefined' && tRptWahRCodeTo != "") && (typeof(tRptWahRCodeFrom) !== 'undefined' && tRptWahRCodeFrom == "")) {
            $('#oetRptWahRCodeFromOut').val(tWahCode);
            $('#oetRptWahRNameFromOut').val(tWahName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptWahRCodeFromOut');

    }



    // Next Function : หลังจากเลือกคลังรับ
    function JSxRptConsNextFuncBrowseWahFromIn(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tWahCode = aDataNextFunc[0];
            tWahName = aDataNextFunc[1];
        }

        // ประกาศตัวแปร คลังสินค้า
        var tRptWahCodeFrom, tRptWahNameFrom, tRptWahCodeTo, tRptWahNameTo
        tRptWahCodeFrom = $('#oetRptWahCodeFromIn').val();
        tRptWahNameFrom = $('#oetRptWahNameFromIn').val();
        tRptWahCodeTo = $('#oetRptWahCodeTo').val();
        tRptWahNameTo = $('#oetRptWahNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้า ให้ default ถึงคลังสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom != "") && (typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo == "")) {
            $('#oetRptWahCodeTo').val(tWahCode);
            $('#oetRptWahNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้า default จากคลังสินค้า เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo != "") && (typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom == "")) {
            $('#oetRptWahCodeFromIn').val(tWahCode);
            $('#oetRptWahNameFromIn').val(tWahName);
        }

        // ประกาศตัวแปร คลังสินค้าที่โอน
        var tRptWahTCodeFrom = $('#oetRptWahTCodeFromIn').val();
        var tRptWahTNameFrom = $('#oetRptWahTNameFromIn').val();
        var tRptWahTCodeTo = $('#oetRptWahTCodeTo').val();
        var tRptWahTNameTo = $('#oetRptWahTNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้าที่โอน ให้ default ถึงคลังสินค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahTCodeFrom) !== 'undefined' && tRptWahTCodeFrom != "") && (typeof(tRptWahTCodeTo) !== 'undefined' && tRptWahTCodeTo == "")) {
            $('#oetRptWahTCodeTo').val(tWahCode);
            $('#oetRptWahTNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้าที่โอน default จากคลังสินค้าที่โอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahTCodeTo) !== 'undefined' && tRptWahTCodeTo != "") && (typeof(tRptWahTCodeFrom) !== 'undefined' && tRptWahTCodeFrom == "")) {
            $('#oetRptWahTCodeFromIn').val(tWahCode);
            $('#oetRptWahTNameFromIn').val(tWahName);
        }

        // ประกาศตัวแปร คลังสินค้าที่รับโอน
        var tRptWahRCodeFrom = $('#oetRptWahRCodeFromIn').val();
        var tRptWahRNameFrom = $('#oetRptWahRNameFromIn').val();
        var tRptWahRCodeTo = $('#oetRptWahRCodeTo').val();
        var tRptWahRNameTo = $('#oetRptWahRNameTo').val();

        // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้าที่รับโอน ให้ default ถึงคลังสินค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahRCodeFrom) !== 'undefined' && tRptWahRCodeFrom != "") && (typeof(tRptWahRCodeTo) !== 'undefined' && tRptWahRCodeTo == "")) {
            $('#oetRptWahRCodeTo').val(tWahCode);
            $('#oetRptWahRNameTo').val(tWahName);
        }

        // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้าที่รับโอน default จากคลังสินค้าที่รับโอน เป็นข้อมูลเดียวกัน
        if ((typeof(tRptWahRCodeTo) !== 'undefined' && tRptWahRCodeTo != "") && (typeof(tRptWahRCodeFrom) !== 'undefined' && tRptWahRCodeFrom == "")) {
            $('#oetRptWahRCodeFromIn').val(tWahCode);
            $('#oetRptWahRNameFromIn').val(tWahName);
        }

        //uncheckbox parameter[1] : id
        JSxUncheckinCheckbox('oetRptWahRCodeFromIn');

    }
</script>