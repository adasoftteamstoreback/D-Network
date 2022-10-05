<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">

            <!--ค้นหาสาขา-->
            <div class="col-xs-4 col-md-2 col-lg-2">
                <label class="xCNLabelFrm"><?=language('company/smartlockerlayout/smartlockerlayout','tSMLLayoutTableBch')?></label>
                <div class="input-group">
                    <input name="oetInputPSHCheckStatusBchName" id="oetInputPSHCheckStatusBchName" class="form-control" value="<?=$this->session->userdata("tSesUsrBchNameDefault");?>" type="text" readonly="" placeholder="<?=language('company/shopgpbyshp/shopgpbyshp','tSMLLayoutTableBch')?>">
                    <input name="oetInputPSHCheckStatusBchCode" id="oetInputPSHCheckStatusBchCode" class="form-control xCNHide" value="<?=$this->session->userdata("tSesUsrBchCodeDefault");?>" type="text" >
                    <span class="input-group-btn">
                        <button class="btn xCNBtnBrowseAddOn" id="obtPSHCheckStatusBrowseBranch" type="button">
                            <img src="<?=base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>

            <!--ร้านค้า-->
            <div class="col-xs-4 col-md-2 col-lg-2">
                <label class="xCNLabelFrm"><?=language('company/smartlockerlayout/smartlockerlayout','tSMLLayoutTableShp')?></label>
                <div class='input-group'>
                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetPshPSHShpCod' name='oetPshPSHShpCod'>
                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptShpName' name='oetRptShpName' placeholder="<?=language('company/smartlockerlayout/smartlockerlayout','tSMLLayoutTableShp')?>" readonly>
                    <span class='input-group-btn'>
                        <button id='obtPSHBrowseShop' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                    </span>
                </div>
            </div>

            <div class="col-xs-4 col-md-2 col-lg-2">
                <label class="xCNLabelFrm"><?= language('pos/posshop/posshop','tPshPHPOSChoose')?></label>
                <div class="input-group">
                    <input name="oetPosCode" id="oetPosCodeSN" class="form-control xCNHide" value="">
                    <input name="oetPosName" id="oetPSHPosName" class="form-control xWPointerEventNone xWRptConsCrdInput"  type="text" readonly="" value="" placeholder="<?= language('pos/posshop/posshop','tPshPHPOSChoose')?>">
                    <span class="input-group-btn">
                        <button class="btn xCNBtnBrowseAddOn" id="obtPSHBrowsePos" type="button">
                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>

            <!--กลุ่มช่อง-->
            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?=language('company/smartlockerlayout/smartlockerlayout','tSMLLayoutTitleGroup')?></label>
                    <select class="form-control" id="osmPSHCheckStatusLayoutRack">
                    </select>
                </div>
            </div>

            <!--BTN ค้นหา-->
            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group" id="odvPSHCheckStatusProcess" style="margin-top: 27px;">
                    <button type="submit" style="float: right;  width: 100%;" id="" class="btn btn-primary" onclick="JSvPSHCheckStatusTableData();"><?php echo language('company/smartlockerCheckstatus/smartlockerCheckstatus', 'tPSHCheckStatusBTNProcess')?></button>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 50px; padding-bottom: 20px">
                <div id="odvPSHCheckStatusContent"></div>
            </div>
            
        </div>
    </div>
</div>
<?php include "script/jSmartlockerCheckstatus.php"; ?>

<script>
    $(document).ready(function () {
        $('#obtPSHBrowseShop').attr('disabled', true)
        $('#obtPSHBrowsePos').attr('disabled', true)
        var tBchName = $('#oetInputPSHCheckStatusBchName').val();
        if (tBchName != '') {
            $('#obtPSHBrowseShop').attr('disabled', false);
            $('#osmPSHCheckStatusLayoutRack').attr('disabled', true);
        } else {
            $('#obtPSHBrowseShop').attr('disabled', true);
            $('#osmPSHCheckStatusLayoutRack').attr('disabled', true);
        }
    });
    function JSxPSHControllBrowse(){  
        var tBchName = $('#oetInputPSHCheckStatusBchName').val();
        var tShpName = $('#oetPshPSHShpCod').val();

        if (tBchName != '') {
            $('#obtPSHBrowseShop').attr('disabled', false)
        } else {
            $('#obtPSHBrowseShop').attr('disabled', true)
            $('#obtPSHBrowsePos').attr('disabled', true)
            
        }

        if(tShpName != ''){
            $('#obtPSHBrowsePos').attr('disabled', false)
            $('#osmPSHCheckStatusLayoutRack').attr('disabled', false);
            $.ajax({
                type: "POST",
                url : "PSHSmartLockerGetRack",
                data: { 
                    tBchCode      : $('#oetInputPSHCheckStatusBchCode').val() ,
                    tShpCode      : $('#oetPshPSHShpCod').val(),
                },
                success: function(tResult) {
                    var aDataReturn = JSON.parse(tResult);
                    var aDataRak = aDataReturn['aResultRack']['aList'];
                    var tSelected = '';
                    if (aDataRak != '') {
                        $.each(aDataRak, function(i, item){
                            if ([i] == 0) {
                                tSelected = "selected";
                            } else {
                                tSelected = "";
                            }
                            $('#osmPSHCheckStatusLayoutRack').append('<option value="' + aDataRak[i]['FTRakCode'] + '" '+tSelected+'>' + aDataRak[i]['FTRakName'] + '</option>');
                        });
                    } else {
                        return;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            $('#obtPSHBrowsePos').attr('disabled', true)
        }
    }
    //Browse สาขา
    var nLangEdits  = <?=$this->session->userdata("tLangEdit")?>;
    var oSMLBrowseCheckStatusBranch = {
        Title   : ['company/branch/branch','tBCHTitle'],
        Table   : {Master:'TCNMBranch',PK:'FTBchCode',PKName:'FTBchName'},
        Join    : {
            Table   : ['TCNMBranch_L'],
            On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
        },
        GrideView   : {
            ColumnPathLang	: 'company/branch/branch',
            ColumnKeyLang	: ['tBCHCode','tBCHName'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMBranch_L.FTBchCode'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetInputPSHCheckStatusBchCode","TCNMBranch.FTBchCode"],
            Text		: ["oetInputPSHCheckStatusBchName","TCNMBranch_L.FTBchName"],
        },
        NextFunc: {
            FuncName: 'JSxPSHControllBrowse',
        },
        // DebugSQL : true
    }
    $('#obtPSHCheckStatusBrowseBranch').click(function(){ 
        JCNxBrowseData('oSMLBrowseCheckStatusBranch'); 
        JCNxCloseLoading();
    });

    $('#obtPSHBrowseShop').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tDataBranch = $('#oetInputPSHCheckStatusBchCode').val();

            if (tDataBranch != '') {
                tTextWhereInBranch = ' AND TCNMShop.FTBchCode = '+tDataBranch+'';
            }

            window.oShopBrowseOption = undefined;
            oShopBrowseOption = {
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
                    Condition: [tTextWhereInBranch]
                },
                GrideView: {
                    ColumnPathLang: 'company/shop/shop',
                    ColumnKeyLang: ['tSHPTBBranch', 'tShopCode_POS', 'tShopName_POS'],
                    ColumnsSize: ['30%', '30%', '40%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMBranch_L.FTBchName', 'TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                    DataColumnsFormat: ['', '', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMShop.FDCreateOn DESC, TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
                },
                CallBack: {
                    ReturnType	: 'S',
                    Value: ['oetPshPSHShpCod', "TCNMShop.FTShpCode"],
                    Text: ['oetRptShpName', "TCNMShop_L.FTShpName"]
                },
                NextFunc: {
                    FuncName: 'JSxPSHControllBrowse',
                },
            };
            JCNxBrowseData('oShopBrowseOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtPSHBrowsePos').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetInputPSHCheckStatusBchCode').val();
            let tDataShop = $('#oetPshPSHShpCod').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                tTextWhereInBranch = ' AND TRTMShopPos.FTBchCode = ' + tDataBranch + '';
            }

            // ********** Check Data Shop **********
            let tTextWhereInShop = '';
            if (tDataShop != '') {
                tTextWhereInShop = ' AND TRTMShopPos.FTShpCode = ' + tDataShop + '';
            }

            window.oPSHPosBrowseOption = undefined;
            oPSHPosBrowseOption = {
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
                    ReturnType	: 'S',
                    Value: ['oetPosCodeSN', "TCNMPos.FTPosCode"],
                    Text: ['oetPSHPosName', "TCNMPos_L.FTPosName"]
                },
                // DebugSQL: true
            };
            JCNxBrowseData('oPSHPosBrowseOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
</script>