<script>
    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit") ?>;

    $('document').ready(function(){    
        // Control Agency    
        var tSesUsrLevel    = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';
        if( tSesUsrLevel != "HQ" ){
            $('#obtRCBBrowseAgency').attr('disabled',true);
        }
        var tSesUsrAgnCode  = '<?php echo $this->session->userdata("tSesUsrAgnCode"); ?>';
        var tSesUsrAgnName  = '<?php echo $this->session->userdata("tSesUsrAgnName"); ?>';
        if( tSesUsrAgnCode != "" && tSesUsrLevel != "HQ" ){
            $('#oetRCBAgnCode').val(tSesUsrAgnCode);
            $('#oetRCBAgnName').val(tSesUsrAgnName);
        }
        // End Control Agency

        // Control Branch
        var nSesUsrBchCount     = <?php echo $this->session->userdata("nSesUsrBchCount"); ?>;
        if( nSesUsrBchCount == 1 && tSesUsrLevel != "HQ" ){
            $('#obtRCBBrowseBranch').attr('disabled',true);
        }

        var tUsrBchCodeDefault  = '<?php echo $this->session->userdata("tSesUsrBchCodeDefault"); ?>';
        var tUsrBchNameDefault  = '<?php echo $this->session->userdata("tSesUsrBchNameDefault"); ?>';
        if( tSesUsrLevel != "HQ" ){
            $('#oetRCBBchCode').val(tUsrBchCodeDefault);
            $('#oetRCBBchName').val(tUsrBchNameDefault);
        }
        // End Control Branch
        
        $('#obtRCBSearch').off('click').on('click',function(){
            JCNxOpenLoading();
            JSxRCBPageDatatable('1','ADD');
        });

        $('#obtRCBClearSearch').click(function(){
            JCNxOpenLoading();
            $('#oetRCBAgnCode').val('');
            $('#oetRCBBchCode').val('');
            $('#ocmRCBStaPrcDoc').val('');
            $('#oetRCBStaDocAct').val('');
            $("#oetRCBDocType").val("ALL").selectpicker("refresh");
            $('#oetRCBFilterDocNo').val('');
            $('#oetRCBChannel').val('');
            $('#ohdRCBOldFilterList').val('')
            var tFilterList = $('#ohdRCBOldFilterList').val();
            console.log(tFilterList);

            if (tFilterList == '') {
                JSxRCBPageDatatable();
            }

        });

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard: true,
            autoclose: true
        });

        $('#obtRCBDocDate').unbind().click(function() {
            $('#oetRCBDocDate').datepicker('show');
        });

    });   

    $('#obtRCBBrowseAgency').click(function() {
        JSxCheckPinMenuClose();
        window.oBrowseAgencyOption = undefined;
        oBrowseAgencyOption = oBrowseAgency({
            'tReturnCode' : 'oetRCBAgnCode',
            'tReturnName' : 'oetRCBAgnName'
        });
        JCNxBrowseData('oBrowseAgencyOption');
    });

    var oBrowseAgency = function(poDataFnc) {
        var tReturnCode = poDataFnc.tReturnCode;
        var tReturnName = poDataFnc.tReturnName;
        var oOptionReturn = {
            Title: ['ticket/agency/agency', 'tAggTitle'],
            Table: {
                Master  : 'TCNMAgency',
                PK      : 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: [
                    'TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits
                ]
            },
            // Where: {
            //     Condition: [tWherePosType]
            // },
            GrideView: {
                ColumnPathLang: 'ticket/agency/agency',
                ColumnKeyLang: ['tAggCode', 'tAggName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMAgency.FDCreateOn'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tReturnCode, "TCNMAgency.FTAgnCode"],
                Text        : [tReturnName, "TCNMAgency_L.FTAgnName"],
            },
            // RouteAddNew: 'salemachine',
            // BrowseLev: nStaWahBrowseType
            // DebugSQL: true,
        }
        return oOptionReturn;
    }

    $('#obtRCBBrowseBranch').click(function() {
        JSxCheckPinMenuClose();
        window.oBrowseBranchOption = undefined;
        oBrowseBranchOption = oBrowseBranch({
            'tReturnCode' : 'oetRCBBchCode',
            'tReturnName' : 'oetRCBBchName',
            'tAgnCode'    : $('#oetRCBAgnCode').val()
        });
        JCNxBrowseData('oBrowseBranchOption');
    });
    var oBrowseBranch = function(poDataFnc) {
        var tReturnCode = poDataFnc.tReturnCode;
        var tReturnName = poDataFnc.tReturnName;
        var tAgnCode    = poDataFnc.tAgnCode;

        // var tWhereCondition = " AND TCNMBranch.FTAgnCode = '"+tAgnCode+"' ";

        var oOptionReturn = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: [
                    'TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits
                ]
            },
            // Where: {
            //     Condition: [ tWhereCondition ]
            // },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tReturnCode, "TCNMBranch.FTBchCode"],
                Text        : [tReturnName, "TCNMBranch_L.FTBchName"],
            },
            // RouteAddNew: 'salemachine',
            // BrowseLev: nStaWahBrowseType
            // DebugSQL: true,
        }
        return oOptionReturn;
    }

    // $('#obtRCBBrowseChannel').click(function() {
    //     JSxCheckPinMenuClose();
    //     window.oBrowseChannelOption = undefined;
    //     oBrowseChannelOption = oBrowseChannel({
    //         'tReturnCode' : 'oetRCBChnCode',
    //         'tReturnName' : 'oetRCBChnName'
    //     });
    //     JCNxBrowseData('oBrowseChannelOption');
    // });
    // var oBrowseChannel = function(poDataFnc) {
    //     var tReturnCode = poDataFnc.tReturnCode;
    //     var tReturnName = poDataFnc.tReturnName;
    //     var tAgnCode    = poDataFnc.tAgnCode;

    //     // var tWhereCondition = " AND TCNMChannel.FTAgnCode = '"+tAgnCode+"' ";

    //     var oOptionReturn = {
    //         Title: ['pos/poschannel/poschannel', 'tCHNTitle'],
    //         Table: {
    //             Master  : 'TCNMChannel',
    //             PK      : 'FTChnCode'
    //         },
    //         Join: {
    //             Table: ['TCNMChannel_L'],
    //             On: [
    //                 'TCNMChannel_L.FTChnCode = TCNMChannel.FTChnCode AND TCNMChannel_L.FNLngID = ' + nLangEdits
    //             ]
    //         },
    //         // Where: {
    //         //     Condition: [ tWhereCondition ]
    //         // },
    //         GrideView: {
    //             ColumnPathLang: 'pos/poschannel/poschannel',
    //             ColumnKeyLang: ['tCHNLabelChannelCode', 'tCHNLabelChannelName'],
    //             ColumnsSize: ['15%', '75%'],
    //             WidthModal: 50,
    //             DataColumns: ['TCNMChannel.FTChnCode', 'TCNMChannel_L.FTChnName'],
    //             DataColumnsFormat: ['', ''],
    //             Perpage: 5,
    //             OrderBy: ['TCNMChannel.FDCreateOn'],
    //             SourceOrder: "DESC"
    //         },
    //         CallBack: {
    //             ReturnType  : 'S',
    //             Value       : [tReturnCode, "TCNMChannel.FTChnCode"],
    //             Text        : [tReturnName, "TCNMChannel_L.FTChnName"],
    //         },
    //         // RouteAddNew: 'salemachine',
    //         // BrowseLev: nStaWahBrowseType
    //         // DebugSQL: true,
    //     }
    //     return oOptionReturn;
    // }

</script>