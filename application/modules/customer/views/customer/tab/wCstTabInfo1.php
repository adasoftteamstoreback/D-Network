<style>
    .xMemBorderPoint {
        border: 2px solid #E0DDDD;
        border-radius: 20px;
    }
</style>
<div id="odvTabInfo1" class="tab-pane fade active in">
    <form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCustomerInfo1">
        <button style="display:none" type="submit" class="xWSubmitCustomerInfo" onclick="//JSnCSTAddEditCustomer('<?php //echo $tRoute; 
                                                                                                                    ?>')"></button>
        <input type="hidden" id="ohdCheckDublicateCode" value="0">
        <input type="hidden" id="ohdCheckSubmitByButton" value="0">
        <input type="hidden" id="ohdFunctionAddEditCustomer" value="<?php echo $tRoute; ?>">
        <div class="row">
            <div class="col-xl-4 col-sm-4 col-md-4 col-lg-4">
                <?php echo FCNtHGetContentUploadImage(@$tImgObjAll, 'Customer'); ?>
            </div>

            <div class="col-xl-6 col-sm-6 col-md-6 col-lg-6">
                <?php if ($tRoute == "customerEventEdit") { ?>
                    <div class="col-xl-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group xMemBorderPoint" align="center">
                            <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstMemAmtActive'); ?></label>
                            <br>
                            <?= number_format($nMemAmtActive, 0) ?>
                            <br>
                            <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstMemUnitBaht'); ?></label>
                        </div>
                    </div>
                    <div class="col-xl-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group xMemBorderPoint" align="center">
                            <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstMemPntActive'); ?></label>
                            <br>
                            <?= number_format($nMemPntActive, 0) ?>
                            <br>
                            <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstMemUnitPoint'); ?></label>
                        </div>
                    </div>
                    <div class="col-xl-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group xMemBorderPoint" align="center">
                            <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstMemPntExp'); ?></label>
                            <br>
                            <?= number_format($nMemPntExp, 0) ?>
                            <br>
                            <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstMemUnitPoint'); ?></label>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('customer/customer/customer', 'tCSTCode'); ?></label>
                    <div id="odvCstAutoGenCode" class="form-group">
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbCustomerAutoGenCode" name="ocbCustomerAutoGenCode" checked="true" value="1">
                            <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                        </label>
                    </div>
                    <div id="odvCstCodeForm" class="form-group">
                        <input type="hidden" id="ohdCheckDuplicateCstCode" name="ohdCheckDuplicateCstCode" value="1">
                        <input type="text" class="form-control " maxlength="20" id="oetCstCode" name="oetCstCode" value="<?php echo $tCstCode ?>" autocomplete="off" data-is-created="<?php echo $tCstCode ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTCode'); ?>" data-validate-required="<?php echo language('customer/customer/customer', 'tCSTValidCode') ?>" data-validate-dublicateCode="<?= language('customer/customer/customer', 'tCSTValidCheckCode') ?>">
                    </div>
                </div>

                <div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                        endif; ?>">
                    <label class="xCNLabelFrm"><?= language('supplier/supplier/supplier', 'tAGNName') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetCstAgnCode" name="oetCstAgnCode" value="<?= $tAgnCode; ?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetCstAgnName" name="oetCstAgnName" placeholder="<?= language('supplier/supplier/supplier', 'tAGNName') ?>" value="<?= $tAgnName; ?>" readonly>
                        <span class="input-group-btn">
                            <script>
                                //ถ้าเป็น BCH + SHP จะไม่สามารถเลือกได้
                                var tStaUsrLevel = '<?= $this->session->userdata("tSesUsrLevel"); ?>';
                                if (tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP') {
                                    $('#obtCstBrowseAgency').attr("disabled", true);
                                }
                            </script>
                            <button id="obtCstBrowseAgency" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('customer/customer/customer', 'tCSTName'); ?><i style="color:red;"> (ชื่อ-สกุล ไม่มีคำนำหน้า เช่น สมปอง ใจดี,บริษัท สุภาพ จำกัด)</i></label>
                    <input type="text" class="form-control" maxlength="100" id="oetCstName" name="oetCstName" value="<?php echo $tCstName; ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTName'); ?>" autocomplete="off" data-validate-required="<?= language('customer/customer/customer', 'tCSTValidName') ?>">
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTIdenNum'); ?></label>
                    <input type="text" class="form-control" maxlength="20" id="oetCstIdenNum" name="oetCstIdenNum" value="<?php echo $tCstCardID; ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTIdenNum'); ?>" autocomplete="off">
                </div>

                <!-- <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTCty'); ?></label>
                    <select class="selectpicker xWDODisabledOnApv form-control xControlForm xWConditionSearchPdt" id="ocmCstBchStatus" name="ocmCstBchStatus" maxlength="1">
                        <option value="1" selected><?php echo language('customer/customer/customer', 'tCstCash'); ?></option>
                        <option value="2" ><?php echo language('customer/customer/customer', 'tCstCerdit'); ?></option>
                    </select>
                </div> -->

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTBirthday'); ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate text-center" placeholder="YYYY-MM-DD" id="oetCstBirthday" name="oetCstBirthday" value="<?php echo $tCstDob; ?>">
                        <span class="input-group-btn">
                            <button id="obtShpStart" type="button" class="btn xCNBtnDateTime">
                                <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>

                <?php
                $tSex1 = "";
                $tSex2 = "";
                if ($tCstSex == "1") {
                    $tSex1 = "checked";
                }
                if ($tCstSex == "2") {
                    $tSex1 = "";
                    $tSex2 = "checked";
                }
                ?>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="fancy-radio xCNRadioMain ">
                                    <input type="radio" id="orbCstSex" name="orbCstSex" value="1" <?php echo $tSex1; ?>>
                                    <span><i></i><?php echo language('customer/customer/customer', 'tCSTMale'); ?></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="fancy-radio xCNRadioMain ">
                                    <input type="radio" id="orbCstSex" name="orbCstSex" value="2" <?php echo $tSex2; ?>>
                                    <span><i></i><?php echo language('customer/customer/customer', 'tCSTFemale'); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div style="border-top: 1px solid #f1f1f1;padding-top: 10px;margin-top: 10px;margin-bottom: 10px;">
                        <span style="font-weight: bold;font-size: 20px !important;">ประเภท</span>
                        <div style="padding-left: 15px;">
                            <div class="row">

                                <div class="col-md-12 col-sm-12">
                                    <?php
                                    if ($tCstBus == 2) {
                                        $tBusiness1 = "checked";
                                        $tBusiness2 = "";
                                    } else {
                                        $tBusiness1 = "";
                                        $tBusiness2 = "checked";
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="fancy-radio xCNRadioMain ">
                                                <input type="radio" class="xWlBusinessPerson" id="orbCstBusiness" name="orbCstBusiness" value="2" <?php echo $tBusiness1; ?>>
                                                <span><i></i><?php echo language('customer/customer/customer', 'tCSTIndividual'); ?></span>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="fancy-radio xCNRadioMain ">
                                                <input type="radio" class="xWlBusinessPerson" id="orbCstBusiness" name="orbCstBusiness" value="1" <?php echo $tBusiness2; ?>>
                                                <span><i></i><?php echo language('customer/customer/customer', 'tCSTCorporate'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="odvBranch">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <!-- <label class="xCNLabelFrm"><?php echo language('supplier/supplier/supplier', 'tVat') ?></label> -->
                                                <select class="selectpicker form-control xCNSelectBox" id="ocbCstStaBchOrHQ" name="ocbCstStaBchOrHQ">
                                                    <option value="1" <?php if ($tCstBusiness == '1') {
                                                                            echo "selected";
                                                                        } ?>>สำนักงานใหญ่</option>
                                                    <option value="" <?php if ($tCstBusiness != '1') {
                                                                            echo "selected";
                                                                        } ?>>สาขา</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4" id="odvCstBchCode_input">
                                            <div class="form-group">
                                                <input type="hidden" id="ohdCstBchCode" name="ohdCstBchCode" value="<?php echo $tCstBchCodes; ?>">
                                                <input type="text" class="form-control" maxlength="20" id="oetCstBchCodes" name="oetCstBchCodes" placeholder="<?php echo language('supplier/supplier/supplier', 'tBranch') ?>" <?php if ($tCstBusiness == '1') {
                                                                                                                                                                                                                                    echo "readonly";
                                                                                                                                                                                                                                } ?> autocomplete="off" value="<?php echo $tCstBchCodes; ?>" data-validate="<?php echo  language('supplier/supplier/supplier', 'tBranch'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="odvCstBchCode_Browse">
                                            <div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                                                    endif; ?>">
                                                <div class="input-group">
                                                    <input type="text" class="form-control xWPointerEventNone" id="oetCstBchNames" name="oetCstBchNames" placeholder="<?= language('supplier/supplier/supplier', 'tBranch') ?>" value="<?php echo $tCstBchCodes; ?>" readonly>
                                                    <span class="input-group-btn">
                                                        <button id="obtCstBrowseBch" type="button" class="btn xCNBtnBrowseAddOn">
                                                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTTaxIdenNum'); ?></label>
                    <input type="text" class="input100" maxlength="100" id="oetCstTaxIdenNum" name="oetCstTaxIdenNum" value="<?= $tCstTaxNo ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTTaxIdenNum'); ?>" autocomplete="off">
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('customer/customer/customer', 'tCSTTel'); ?></label>
                    <input type="text" class="form-control" maxlength="15" id="oetCstTel" name="oetCstTel" value="<?= $tCstTel ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTTel'); ?>" autocomplete="off" data-validate-required="<?= language('customer/customer/customer', 'tCSTValidTel') ?>">
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTEmail'); ?></label>
                    <input type="text" class="form-control input100" maxlength="100" id="oetCstEmail" name="oetCstEmail" value="<?= $tCstEmail ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTEmail'); ?>" autocomplete="off">
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="validate-input">
                                <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTRmk'); ?></label>
                                <textarea maxlength="100" rows="4" id="otaCstRemark" name="otaCstRemark"><?= $tCstRmk ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="">
                        <label class="fancy-checkbox" style="width: 100%;">
                            <?php
                            $tCstStaAlwPosCalSoCheck;
                            !empty($tCstStaAlwPosCalSo == "1") ? $tCstStaAlwPosCalSoCheck = "checked" : $tCstStaAlwPosCalSoCheck = "";
                            ?>
                            <input type="checkbox" name="ocbCstStaAlwPosCalSo" <?php echo $tCstStaAlwPosCalSoCheck; ?> value="1">
                            <span> <?php echo language('customer/customer/customer', 'tCstStaAlwPosCalSo'); ?></span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="">
                        <label class="fancy-checkbox">
                            <?php
                            $tCstStaActiveCheck;
                            !empty($tCstStaActive == "1") ? $tCstStaActiveCheck = "checked" : $tCstStaActiveCheck = "";
                            ?>
                            <input type="checkbox" name="ocbCstStaActive" <?php echo $tCstStaActiveCheck; ?> value="1">
                            <span> <?php echo language('customer/customer/customer', 'tCSTStaActive'); ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var tRoute = $('#ohdFunctionAddEditCustomer').val();
        if (tRoute == 'customerEventAdd') {
            $("#obtCstBrowseBch").prop("disabled", true);
        } else {
            if ($("#ocbCstStaBchOrHQ").val() == '1') {
                $("#odvCstBchCode_Browse").show();
                $("#odvCstBchCode_input").hide();
            } else {
                $("#odvCstBchCode_input").show();
                $("#odvCstBchCode_Browse").hide();
                $("#oetCstBchCodes").prop("readonly", false);
            }
            $("#obtCstBrowseBch").prop("disabled", false);
        }
    });

    if ('<?= $tCstBus ?>' == 1) {
        $("#odvBranch").show();
    } else {
        $("#odvBranch").hide();
    }

    $(".xWlBusinessPerson").click(function(e) {
        $("#odvBranch").show();
        if ($(this).val() == '1') {
            $("#odvBranch").show();
            if ($("#ocbCstStaBchOrHQ").val() == '1') {
                $("#odvCstBchCode_Browse").show();
                $("#odvCstBchCode_input").hide();
            } else {
                $("#odvCstBchCode_input").show();
                $("#odvCstBchCode_Browse").hide();
                $("#oetCstBchCodes").prop("readonly", false);
            }
        } else {
            $("#odvBranch").hide();
        }
    });

    $("#ocbCstStaBchOrHQ").change(function() {
        if ($(this).val() == '1') {
            $("#odvCstBchCode_Browse").show();
            $("#odvCstBchCode_input").hide();
        } else {
            $("#odvCstBchCode_input").show();
            $("#odvCstBchCode_Browse").hide();
            $("#oetCstBchCodes").prop("readonly", false);
        }
    });

    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    $('#obtShpStart').click(function(event) {
        $('#oetCstBirthday').datepicker('show');
    });

    //เลือกตัวแทนขาย
    $('#obtCstBrowseAgency').off('click');
    $('#obtCstBrowseAgency').on('click', function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oCSTAgnOption = undefined;
            oCSTAgnOption = oCSTBrowseAgency({
                'tReturnInputCode': 'oetCstAgnCode',
                'tReturnInputName': 'oetCstAgnName',
                'tNextFuncName': 'JSxCSTNextFuncBrowseAgency',
                'aArgReturn': ['FTAgnCode', 'FTAgnName']
            });
            JCNxBrowseData('oCSTAgnOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oCSTBrowseAgency = function(poReturnInputAgency) {
        let tInputReturnAgnCode = poReturnInputAgency.tReturnInputCode;
        let tInputReturnAgnName = poReturnInputAgency.tReturnInputName;
        let tAgencyNextFunc = poReturnInputAgency.tNextFuncName;
        let aAgencyArgReturn = poReturnInputAgency.aArgReturn;

        let oAgencyOptionReturn = {
            Title: ['authen/user/user', 'tBrowseAgnTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: [' TCNMAgency.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseAgnCode', 'tBrowseAgnName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMAgency.FDCreateOn DESC'],
            },
            NextFunc: {
                FuncName: tAgencyNextFunc,
                ArgReturn: aAgencyArgReturn
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnAgnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnAgnName, "TCNMAgency_L.FTAgnName"]
            },
            //DebugSQL: true,
        };
        return oAgencyOptionReturn;
    }

    //เลือกสาขา
    $('#obtCstBrowseBch').off('click');
    $('#obtCstBrowseBch').on('click', function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oCSTBchOption = undefined;
            oCSTBchOption = oCSTBchBrowse({
                'tReturnInputCode': 'oetCstBchCodes',
                'tReturnInputName': 'oetCstBchNames',
                'tNextFuncName': 'JSxCSTNextFuncBrowseCstBch',
                'aArgReturn': ['FTCbrBchName']
            });
            JCNxBrowseData('oCSTBchOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oCSTBchBrowse = function(poReturnInputCstBch) {
        var tInputReturnCode = poReturnInputCstBch.tReturnInputCode;
        var tInputReturnName = poReturnInputCstBch.tReturnInputName;
        var tNextFunc = poReturnInputCstBch.tNextFuncName;
        var aArgReturn = poReturnInputCstBch.aArgReturn;
        var tCstCode = $('#oetCstCode').val();

        var oCstBchOptionReturn = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master: 'TCNMCstBch',
                PK: 'FTCbrBchCode'
            },
            Where: {
                Condition: ["AND TCNMCstBch.FTCstCode = '" + tCstCode + "' "]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMCstBch.FTCbrBchCode', 'TCNMCstBch.FTCbrBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMCstBch.FDCreateOn DESC'],
            },
            // NextFunc : {
            //     FuncName  : tNextFunc,
            //     ArgReturn : aArgReturn
            // },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMCstBch.FTCbrBchName"],
                Text: [tInputReturnName, "TCNMCstBch.FTCbrBchName"]
            },
            // DebugSQL: true,
        };
        return oCstBchOptionReturn;
    }

    //หลังจากเลือก AGN
    function JSxCSTNextFuncBrowseAgency() {
        //ไม่ทำอะไร
    }

    //หลังจากเลือก AGN
    // function JSxCSTNextFuncBrowseCstBch(paData){
    //     var aDataNextFunc = JSON.parse(paData);
    //     $('#oetCstBchCodes').val(aDataNextFunc[0]);
    //     $('#oetCstBchNames').val(aDataNextFunc[0]);
    // }
</script>