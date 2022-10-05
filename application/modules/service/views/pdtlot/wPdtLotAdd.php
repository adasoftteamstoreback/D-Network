<?php
if (isset($nStaAddOrEdit) && $nStaAddOrEdit == 1) {
    $tRoute         = "maslotEventEdit";
    $tLotNo         = $aLotData['aItems']['FTLotNo'];
    $tLotRemark     = $aLotData['aItems']['FTLotRemark'];
    $tLotSta        = $aLotData['aItems']['FTLotStaUse'];
    $tLotBatchNo    = $aLotData['aItems']['FTLotBatchNo'];
    $tLotYear       = $aLotData['aItems']['FTLotYear'];

    $tLotAgnCode   = $aLotData['aItems']['tAgnCode'];
    $tLotAgnName   = $aLotData['aItems']['tAgnName'];
    $tMenuTabDisable = "";
    $tMenuTabToggle = "tab";
    $tMenuCursor = "cursor: pointer;";
} else {
    $tRoute         = "maslotEventAdd";
    $tLotNo         = "";
    $tLotRemark     = "";
    $tLotSta        = "";
    $tMenuTabDisable = " disabled xCNCloseTabNav";
    $tMenuTabToggle = "false";
    $tMenuCursor = "cursor: not-allowed;";
    $tLotYear = "";


    $tLotAgnCode   = $this->session->userdata('tSesUsrAgnCode');
    $tLotAgnName   = $this->session->userdata('tSesUsrAgnName');
}
?>
    <input type="hidden" id="ohdPdtGroupRoute" value="<?php echo $tRoute; ?>">
    <div class="panel panel-headline">
        <!-- เพิ่มมาใหม่ -->
        <div id="odvDotPanelBody"  class="panel-body" style="padding-top:20px !important;">
            <div class="custom-tabs-line tabs-line-bottom left-aligned">
                <ul class="nav" role="tablist">
                    <!-- Info Tab -->
                    <li id="oliDotInfoTab" class="xCNDotTab active" data-typetab="main" data-tabtitle="posinfo" style="<?=$tMenuCursor?>">
                        <a role="tab" data-toggle="tab" data-target="#odvDotInfoTab" aria-expanded="true">
                            <?php echo language('service/pdtlot/pdtlot', 'tLOTTab1') ?>
                        </a>
                    </li>
                    <!-- รุ่น ยี่ห้อ -->
                    <li id="oliBAMInfoTab" class="xCNDotTab<?php echo @$tMenuTabDisable; ?>" data-typetab="sub" data-tabtitle="BAM" style="<?=$tMenuCursor?>">
                        <a role="tab" data-toggle="<?php echo @$tMenuTabToggle; ?>" data-target="#odvBAMInfoTab" aria-expanded="true">
                            <?php echo language('service/pdtlot/pdtlot', 'tLOTTab2'); ?>
                        </a>
                    </li>
                </ul>
            </div>
            
            <form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddPdtLot">  
                <button style="display:none" type="submit" id="obtSubmitPdtLot" onclick="JSoAddEditPdtLot('<?= $tRoute ?>')"></button>
                <div class="tab-pane active" style="margin-top:10px;" id="odvDotInfoTab" role="tabpanel" aria-expanded="true">
                    <div class="row">
                        <div class="col-xs-12 col-md-5 col-lg-5">
                            <!-- เปลี่ยน Col Class -->
                            <div class="form-group">
                                <input type="hidden" value="0" id="ohdCheckLotClearValidate" name="ohdCheckLotClearValidate">
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/pdtlot/pdtlot', 'tLOTCode') ?></label> <!-- เปลี่ยนชื่อ Class -->
                                <?php
                                if ($tRoute == "maslotEventAdd") {
                                ?>
                                    <div class="form-group" id="odvPgpAutoGenCode">
                                        <div class="validate-input">
                                            <label class="fancy-checkbox">
                                                <input type="checkbox" id="ocbLotAutoGenCode" name="ocbLotAutoGenCode" checked="true" value="1">
                                                <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group" id="odvPunCodeForm">
                                        <input type="text" class="form-control xCNInputWithoutSpcNotThai" maxlength="5" id="oetLotCode" name="oetLotCode" data-is-created="<?php  ?>" placeholder="<?= language('service/pdtlot/pdtlot', 'tLOTCode') ?>" value="<?php  ?>" data-validate-required="<?php echo language('product/pdtgroup/pdtgroup', 'tLOTValidCode') ?>" data-validate-dublicateCode="<?php echo language('service/pdtlot/pdtlot', 'tLOTVldCodeDuplicate') ?>" readonly onfocus="this.blur()">
                                        <input type="hidden" value="2" id="ohdCheckDuplicateLotCode" name="ohdCheckDuplicateLotCode">
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div class="form-group" id="odvPunCodeForm">
                                        <div class="validate-input">
                                            <label class="fancy-checkbox">
                                                <input type="text" class="form-control xCNInputWithoutSpcNotThai" maxlength="5" id="oetLotCode" name="oetLotCode" data-is-created="<?php  ?>" placeholder="<?= language('service/pdtlot/pdtlot', 'tLotCode') ?>" value="<?php echo $tLotNo; ?>" readonly onfocus="this.blur()">
                                            </label>
                                        </div>
                                    <?php
                                }
                                    ?>
                                    </div>

                                    <?php
                                        $tLotAgnCode    = $tLotAgnCode;
                                        $tLotAgnName    = $tLotAgnName;
                                        $tDisabled      = '';
                                        $tNameElmIDAgn  = 'oimBrowseAgn';
                                    ?>

                                    <!-- เพิ่ม AD Browser -->
                                    <div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                                            endif; ?>">
                                        <label class="xCNLabelFrm"><?php echo language('product/pdtgroup/pdtgroup', 'tPGPAgency') ?></label>
                                        <div class="input-group"><input type="text" class="form-control xCNHide" id="oetLotAgnCode" name="oetLotAgnCode" maxlength="5" value="<?= @$tLotAgnCode; ?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetLotAgnName" name="oetLotAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="<?= @$tLotAgnName; ?>" readonly>
                                            <span class="input-group-btn">
                                                <button id="<?= @$tNameElmIDAgn; ?>" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- LOT/BATCH NO. -->
                                    <div class="form-group" id="odvPunCodeForm">
                                        <label class="xCNLabelFrm"><span style="color:red">*</span> <?= language('service/pdtlot/pdtlot', 'tLOTLotBatchNo') ?></label>
                                        <input type="text" class="form-control xCNInputWithoutSpcNotThai" maxlength="125" id="oetLotBatchNo" name="oetLotBatchNo" placeholder="<?= language('service/pdtlot/pdtlot', 'tLOTLotBatchNo') ?>" value="<?php echo @$tLotBatchNo ?>" data-validate-required="<?php echo language('product/pdtgroup/pdtgroup', 'tLOTValidDotNO') ?>" autocomplete="off">
                                    </div>
                                    
                                    <!-- ปี Dot ยาง -->
                                    <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('service/pdtlot/pdtlot', 'tLOTYear') ?></label>
                                        <div class="input-group">
                                            <input type='text' class='form-control xCNYearPicker' id='oetLOTYear' name='oetLOTYear' autocomplete="off" value="<?=$tLotYear?>">
                                            <span class='input-group-btn'>
                                                <button id='obtLotBrowseYear' type='button' class='btn xCNBtnBrowseAddOn'>
                                                    <img class='xCNIconCalendar'>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('service/pdtlot/pdtlot', 'tLOTFrmLotRmk') ?></label> <!-- เปลี่ยนชื่อ Class -->
                                        <textarea class="form-control" maxlength="100" rows="4" id="otaLotRmk" name="otaLotRmk"><?= $tLotRemark; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                        <input type="checkbox" id="ocbLotStatus" name="ocbLotStatus" value="1"
                                        
                                        <?php 
                                            if($tLotSta == '0'){
                                                echo '';
                                            }else{
                                                echo 'checked';
                                            }
                                        ?>>
                                        <span> <?php echo language('service/pdtlot/pdtlot', 'tLOTStatus'); ?></span>
                                    </label>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="tab-pane" style="margin-top:10px;" id="odvBAMInfoTab" role="tabpanel" aria-expanded="true">
                <div id="odvBAMInfoPage">
                    <?php if ($tRoute == "maslotEventEdit") { ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <ol id="oliMenuNav" class="breadcrumb">
                                    <li id="oliDotBAMTitle" class="xCNLinkClick" onclick="JSvDotBAMDataTable(1,'<?= $tLotNo ?>');" style="cursor:pointer"><?php echo language('service/pdtlot/pdtlot', 'tLOTTab2'); ?></li>
                                    <li id="oliDotBAMTitleEdit" class="active"><a>/ <?php echo language('pos/salemachine/salemachine', 'tPOSTitleEdit') ?></a></li>
                                    <li id="oliDotBAMTitleAdd" class="active"><a><?php echo language('pos/salemachine/salemachine', 'tPOSTitleAdd') ?></a></li>
                                </ol>
                            </div>
                            <div class="col-xs-12 col-md-8 text-right">
                                <div id="odvBtnDotBAMInfo">
                                    <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageDotBAMAdd()">+</button>
                                </div>
                                <div id="odvBtnDotBAMAddEdit">

                                    <button type="button" onclick="JSvDotBAMDataTable(1,'<?= $tLotNo ?>');" class="btn" style="background-color: #D4D4D4; color: #000000;">
                                        <?=language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
                                    </button>
                                    <button type="submit" class="btn xCNBTNSubSave" onclick="JSxDotBAMSubmit();$('#obtSubmitDotBAM').click()">
                                        <?=language('common/main/main', 'tSave') ?>
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group" id="odvBtnDotBAMSearch">
                                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tSearchNew') ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchBAMDevice" name="oetSearchBAMDevice" autocomplete="off" placeholder="<?php echo language('common/main/main', 'tPlaceholder'); ?>">
                                            <span class="input-group-btn">
                                                <button class="btn xCNBtnSearch" type="button" id="obtSearchBAMDevice" name="obtSearchBAMDevice">
                                                    <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-8 text-right">
                                    <div class="text-right" style="width:100%;">
                                        <div id="odvDotBAMTableList" class="btn-group xCNDropDrownGroup" style="margin-top:25px;">
                                            <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                                                <?php echo language('common/main/main', 'tCMNOption') ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li id="oliBtnDeleteAll" class="disabled">
                                                    <a data-toggle="modal" data-target="#odvModalDelPdtLot"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>

                <div id="odvBAMContentPage"></div>
                <?php } ?>
            </div>

        </div>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<script>
    $('ducument').ready(function(){
        // Event Date Picker
        $('.xCNYearPicker').datepicker({
            format: "yyyy",
            weekStart: 1,
            orientation: "bottom",
            keyboardNavigation: false,
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
    });

        // Set Select  Doc Date
    $('#obtLotBrowseYear').unbind().click(function(){
        event.preventDefault();
        $('#oetLOTYear').datepicker('show');
    });

    $('#obtGenCodePdtLot').click(function() {
        JStGeneratePdtLotCode();
    });


    //BrowseAgn 
    $('#oimBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPdtBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetLotAgnCode',
                'tReturnInputName': 'oetLotAgnName',
            });
            JCNxBrowseData('oPdtBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
    //Option Agn
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

    var tStaUsrLevel = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';


    if (tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP') {
        $('#oimBrowseAgn').attr("disabled", true);

    }

    // Event Tab
    $('#odvDotPanelBody .xCNDotTab').unbind().click(function(){
        let tRoute       = $('#ohdPdtGroupRoute').val();
        if(tRoute == 'maslotEventAdd'){
            return;
        }else{
            let tTypeTab    = $(this).data('typetab');
            if(typeof(tTypeTab) !== undefined && tTypeTab == 'main'){
                $('#odvBAMInfoTab').hide();
                $('#odvDotInfoTab').show();
                JCNxOpenLoading();
                setTimeout(function(){
                    $('#odvLotMainMenu #odvBtnAddEdit').show();
                    JCNxCloseLoading();
                    return;
                },500);
            }else if(typeof(tTypeTab) !== undefined && tTypeTab == 'sub'){
                $('#odvLotMainMenu #odvBtnAddEdit').hide();
                $('#odvDotInfoTab').hide();
                $('#odvBAMInfoTab').show();
                let tTabTitle   = $(this).data('tabtitle');
                switch(tTabTitle){
                    case 'BAM':
                        JCNxOpenLoading();
                        setTimeout(function(){
                            JCNxCloseLoading();
                            return;
                        },500);
                    break;
                }
            }   
        }
    });
</script>