<script type="text/javascript">
    $(document).ready(function() {

        $('.selectpicker').selectpicker('refresh');

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard: true,
            autoclose: true
        });

        $('#obtQTBrowseBchRefIntDoc').click(function() {
            $('#odvQTModalRefIntDoc').modal('hide');
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                window.oQTBrowseBranchOption = undefined;
                oQTBrowseBranchOption = oBranchOptionRef({
                    'tReturnInputCode': 'oetQTRefIntBchCode',
                    'tReturnInputName': 'oetQTRefIntBchName',
                    'tNextFuncName': 'JSxQTRefIntNextFunctBrowsBranch',
                    'aArgReturn': ['FTBchCode', 'FTBchName'],
                });
                JCNxBrowseData('oQTBrowseBranchOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtQTBrowseRefExtDocDateFrm').unbind().click(function() {
            $('#oetQTRefIntDocDateFrm').datepicker('show');
        });

        $('#obtQTBrowseRefExtDocDateTo').unbind().click(function() {
            $('#oetQTRefIntDocDateTo').datepicker('show');
        });

        JSxRefIntDocHDDataTable();
    });

    //อ้างอิงสาขา ของเอกสารที่ ref
    var oBranchOptionRef = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var aArgReturn = poDataFnc.aArgReturn;

        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhere = "";
        if (tUsrLevel != "HQ") {
            tSQLWhere = " AND TCNMBranch.FTBchCode IN (" + tBchMulti + ") ";
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
                Condition: [tSQLWhere]
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
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            }
        };
        return oOptionReturn;
    }

    //หลังจากเลือกสาขาอ้างอิง
    function JSxQTRefIntNextFunctBrowsBranch(ptData) {
        JSxCheckPinMenuClose();

        setTimeout(function() {
            $('#odvQTModalRefIntDoc').modal("show");
        }, 500);
    }

    //Style
    $('#odvQTModalRefIntDoc').on('hidden.bs.modal', function() {
        $('#wrapper').css('overflow', 'auto');
        $('#odvQTModalRefIntDoc').css('overflow', 'auto');
    });

    $('#odvQTModalRefIntDoc').on('show.bs.modal', function() {
        $('#wrapper').css('overflow', 'hidden');
        $('#odvQTModalRefIntDoc').css('overflow', 'auto');
    });

    $('#obtRefIntDocFilter').on('click', function() {
        JSxRefIntDocHDDataTable();
    });

    function JSxRefIntDocHDDataTable(pnPage) {
        // if(pnPage == '' || pnPage == null){
        //     var pnNewPage = 1;
        // }else{
        //     var pnNewPage = pnPage;
        // }
        var pnNewPage = '';
        switch (pnPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld = $('.xWQTPageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน

                pnNewPage = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWQTPageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                pnNewPage = nPageNew
                break;
            default:
                pnNewPage = pnPage
        }

        var nPageCurrent = pnNewPage;
        var tQTRefIntBchCode = $('#oetQTRefIntBchCode').val();
        var tQTRefIntDocNo = $('#oetQTRefIntDocNo').val();
        var tQTRefIntDocDateFrm = $('#oetQTRefIntDocDateFrm').val();
        var tQTRefIntDocDateTo = $('#oetQTRefIntDocDateTo').val();
        var tQTRefIntStaDoc = $('#oetQTRefIntStaDoc').val();
        var tCarCode = $('#oetPreCarRegCode').val();
        let tCstCode = $('#ohdTQCustomerCode').val();
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docQuotationRefIntDocDataTable",
            data: {
                'tQTRefIntBchCode': tQTRefIntBchCode,
                'tQTRefIntDocNo': tQTRefIntDocNo,
                'tQTRefIntDocDateFrm': tQTRefIntDocDateFrm,
                'tQTRefIntDocDateTo': tQTRefIntDocDateTo,
                'tQTRefIntStaDoc': tQTRefIntStaDoc,
                'nQTRefIntPageCurrent': nPageCurrent,
                'tCarCode': tCarCode,
                'tCstCode': tCstCode,
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                $('#odvRefIntDocHDDataTable').html(oResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // function JSvPdtCatClickPage(ptPage) {
    //     var nPageCurrent = '';
    //     switch (ptPage) {
    //         case 'next': //กดปุ่ม Next
    //             $('.xWBtnNext').addClass('disabled');
    //             nPageOld = $('.xWPagePdtCat .active').text(); // Get เลขก่อนหน้า
    //             nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน

    //             nPageCurrent = nPageNew
    //             break;
    //         case 'previous': //กดปุ่ม Previous
    //             nPageOld = $('.xWPagePdtCat .active').text(); // Get เลขก่อนหน้า
    //             nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
    //             nPageCurrent = nPageNew
    //             break;
    //         default:
    //             nPageCurrent = ptPage
    //     }
    //     JCNxOpenLoading();
    //     JSvPdtCatDataTable(nPageCurrent);
    // }
</script>