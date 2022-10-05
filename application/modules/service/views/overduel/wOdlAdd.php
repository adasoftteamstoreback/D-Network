<?php
if (isset($nStaAddOrEdit) && $nStaAddOrEdit == 1) {
    $tRoute        = "masOdlEventEdit";
    $OdlCode       = $aOdlData['aItems']['FTOdlCode'];
    $tOdlType      = $aOdlData['aItems']['FTOdlType'];
    $tOdlMin       = $aOdlData['aItems']['FNOdlMin'];
    $tOdlMax       = ($aOdlData['aItems']['FNOdlMax'] == '') ? '0' : $aOdlData['aItems']['FNOdlMax'];

    $tOdlAgnCode   = $aOdlData['aItems']['tAgnCode'];
    $tOdlAgnName   = $aOdlData['aItems']['tAgnName'];
} else {
    $tRoute        = "masOdlEventAdd";
    $OdlCode       = "";
    $tOdlType      = "";
    $tOdlMin       = "0";
    $tOdlMax       = "0";
    $tOdlAgnCode   = $this->session->userdata('tSesUsrAgnCode');
    $tOdlAgnName   = $this->session->userdata('tSesUsrAgnName');
}
?>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddOdl">
    <!-- <input type="hidden" id="ohdPdtGroupRoute" value="<?php echo $tRoute; ?>"> -->
    <button style="display:none" type="submit" id="obtSubmitOdl" onclick="JSoAddEditOdl('<?= $tRoute ?>')"></button>
    <div class="panel panel-headline">
        <!-- เพิ่มมาใหม่ -->
        <div class="panel-body" style="padding-top:20px !important;">
            <!-- เพิ่มมาใหม่ -->
            <div class="row">
                <div class="col-xs-12 col-md-5 col-lg-5">
                    <!-- เปลี่ยน Col Class -->
                    <div class="form-group">
                        <input type="hidden" value="0" id="ohdCheckOdlClearValidate" name="ohdCheckOdlClearValidate">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/overduel/overduel', 'tOdlId') ?></label> <!-- เปลี่ยนชื่อ Class -->
                        <?php
                        if ($tRoute == "masOdlEventAdd") {
                        ?>
                            <div class="form-group" id="odvPgpAutoGenCode">
                                <div class="validate-input">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" id="ocbOdlAutoGenCode" name="ocbOdlAutoGenCode" checked="true" value="1">
                                        <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" id="odvPunCodeForm">
                                <input type="text" class="form-control xCNInputWithoutSpcNotThai" maxlength="5" id="oetOdlCode" name="oetOdlCode" data-is-created="<?php  ?>" placeholder="<?= language('service/overduel/overduel', 'รหัสวันกำหนดชำระ') ?>" value="<?php  ?>" data-validate-required="<?php echo language('product/pdtgroup/pdtgroup', 'tOdlValidCode') ?>" data-validate-dublicateCode="<?php echo language('service/overduel/overduel', 'tOdlVldCodeDuplicate') ?>" readonly onfocus="this.blur()">
                                <input type="hidden" value="2" id="ohdCheckDuplicateOdlCode" name="ohdCheckDuplicateOdlCode">
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="form-group" id="odvPunCodeForm">
                                <div class="validate-input">
                                    <label class="fancy-checkbox">
                                        <input type="text" class="form-control xCNInputWithoutSpcNotThai" maxlength="5" id="oetOdlCode" name="oetOdlCode" data-is-created="<?php  ?>" placeholder="<?= language('service/overduel/overduel', 'รหัสวันกำหนดชำระ') ?>" value="<?php echo $OdlCode; ?>" readonly onfocus="this.blur()">
                                    </label>
                                </div>
                            <?php
                        }
                            ?>
                            </div>

                            <?php
                                $tOdlAgnCode    = $tOdlAgnCode;
                                $tOdlAgnName    = $tOdlAgnName;
                                $tDisabled      = '';
                                $tNameElmIDAgn  = 'oimBrowseAgn';
                            ?>

                            <!-- เพิ่ม AD Browser -->
                            <div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                                    endif; ?>">
                                <label class="xCNLabelFrm"><?php echo language('product/pdtgroup/pdtgroup', 'tPGPAgency') ?></label>
                                <div class="input-group"><input type="text" class="form-control xCNHide" id="oetOdlAgnCode" name="oetOdlAgnCode" maxlength="5" value="<?= @$tOdlAgnCode; ?>">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetOdlAgnName" name="oetOdlAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="<?= @$tOdlAgnName; ?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="<?= @$tNameElmIDAgn; ?>" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                                <!-- ประเภท . -->
                                <div class="form-group" id="odvPunCodeForm">
                                <label class="xCNLabelFrm"><?= language('service/overduel/overduel','tOdlType') ?></label>
                                <select class="selectpicker form-control" id="ocmOdlTpye" name="ocmOdlTpye" maxlength="1" value="<?= $tOdlType; ?>">
                                  <option value="1" <?php if($tOdlType ==1 ){ echo "selected"; } ?>><?php echo language('service/overduel/overduel','tOdloption1') ?></option>
								                  <option value="2" <?php if($tOdlType ==2 ){ echo "selected"; } ?>><?php echo language('service/overduel/overduel','tOdloption2') ?></option>
                                </select>
                                </div>

                              <!-- Date Sale Start // Date Sale Stop -->
                              <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                <!-- Product Date Sale Start -->
                                                <div class="form-group" id="odvPunCodeForm">
                                                <label class="xCNLabelFrm"><?= language('service/overduel/overduel','tOdlMin') ?></label>
                                                <input type="text" class="form-control text-right xCNInputNumericWithDecimal" maxlength="18" id="oetOdlMin" name="oetOdlMin" placeholder="<?= language('service/overduel/overduel','tOdlMinAdd') ?>" value="<?php echo @$tOdlMin?>" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                <!-- Product Date Sale Stop -->
                                                <div class="form-group" id="odvPunCodeForm">
                                                 <label class="xCNLabelFrm"><?= language('service/overduel/overduel','tOdlMax') ?></label>
                                                 <input type="text" class="form-control text-right xCNInputNumericWithDecimal" maxlength="18" id="oetOdlMax" name="oetOdlMax" placeholder="<?= language('service/overduel/overduel','tOdlMaxAdd') ?>" value="<?php echo @$tOdlMax?>" autocomplete="off">
                                                </div>

                                            </div>

                </div>
            </div>
        </div>
</form>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<script>
    $(document).ready(function() {
      $('.selectpicker').selectpicker();
    })
    $('#obtGenCodeOdl').click(function() {
        JStGenerateOdlCode();
    });


    //BrowseAgn
    $('#oimBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPdtBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetOdlAgnCode',
                'tReturnInputName': 'oetOdlAgnName',
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


    if (tStaUsrLevel != "HQ" ) {
        $('#oimBrowseAgn').attr("disabled", true);

    }
  //   $('#ocmOdlTpye').change(function(){
  //   if( $(this).val() == 1  )
  //   {
  //       $("#oetOdlMax").removeAttr("disabled");
  //
  //   }
  //   else{
  //       $('#oetOdlMax').val(0) ;
  //
  //       $('#oetOdlMax').attr('disabled', true);
  //
  //      // $("#oetOdlMax").val("");
  //   }
  // });
</script>
