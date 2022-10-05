<?php
if (isset($nStaAddOrEdit) && $nStaAddOrEdit == 1) {
    $tRoute         = "maslotBAMEventEdit";
    $tDotCode       = $aLotData['aItems']['FTLotNo'];
    $tPbnCode       = $aLotData['aItems']['FTPbnCode'];
    $tPbnName        = $aLotData['aItems']['FTPbnName'];
    $tPmoCode       = $aLotData['aItems']['FTPmoCode'];
    $tPmoName       = $aLotData['aItems']['FTPmoName'];
    $tCreateBy      = $aLotData['aItems']['FTCreateBy'];
} else {
    $tRoute         = "maslotBAMEventAdd";
    $tDotCode       = $tDotCode;
    $tPbnCode       = "";
    $tPbnNam        = "";
    $tPmoCode       = "";
    $tPmoName       = "";
    $tCreateBy      = "";
    


    $tLotAgnCode   = $this->session->userdata('tSesUsrAgnCode');
    $tLotAgnName   = $this->session->userdata('tSesUsrAgnName');
}
?>
    <input type="hidden" id="ohdPdtGroupRoute" value="<?php echo $tRoute; ?>">
    <input type="hidden" id="ohdPdtCreateBy" value="<?php echo $tCreateBy; ?>">
    <form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddPdtDotBAM">  
        <button style="display:none" type="submit" id="obtSubmitDotBAM" onclick="JSoAddEditPdtDotBAM('<?= $tRoute ?>')"></button>
        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('service/pdtlot/pdtlot', 'tLOTCode') ?></label>
                    <div class="form-group">
                        <input type="text" class="form-control xCNInputWithoutSpcNotThai" maxlength="5" id="oetLotCode" name="oetLotCode" placeholder="<?= language('service/pdtlot/pdtlot', 'tLOTCode') ?>" value="<?=$tDotCode?>" readonly>
                    </div>
                

                    <!-- Browse Brand -->
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/pdtlot/pdtlot', 'tLOTBrand') ?></label>
                    <input type="hidden" id="ohdCheckDuplicateBrandCode" name="ohdCheckDuplicateBrandCode" value="2">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control xCNHide" id="oetLotBrandCode" name="oetLotBrandCode" maxlength="5" value="<?= @$tPbnCode; ?>">
                            <input type="text" class="form-control xWPointerEventNone" id="oetLotBrandName" name="oetLotBrandName" maxlength="100" placeholder="<?php echo language('service/pdtlot/pdtlot', 'tLOTBrand') ?>" value="<?= @$tPbnName; ?>" data-validate-required="<?php echo language('service/pdtlot/pdtlot', 'tLOTValidateBrand') ?>" readonly>
                            <span class="input-group-btn">
                                <button id="obtDotBrowseBrand" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
            
                    <!-- Browse Model -->
                    <label class="xCNLabelFrm"><?php echo language('service/pdtlot/pdtlot', 'tLOTModel') ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control xCNHide" id="oetLotModelCode" name="oetLotModelCode" maxlength="5" value="<?= @$tPmoCode; ?>">
                            <input type="text" class="form-control xWPointerEventNone" id="oetLotModelName" name="oetLotModelName" maxlength="100" placeholder="<?php echo language('service/pdtlot/pdtlot', 'tLOTModel') ?>" value="<?= @$tPmoName; ?>" readonly>
                            <span class="input-group-btn">
                                <button id="obtDotBrowseModel" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<div class="modal fade" id="odvModalDupicate">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalWarning')?></label>
			</div>
			<div class="modal-body">
                <span id="ospDupicateMag"></span>
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" data-dismiss="modal"><?=language('common/main/main', 'tModalConfirm')?></button>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script>
    $('document').ready(function(){
        var tBrandCode = $('#oetLotBrandCode').val();
        if (tBrandCode == '') {
            $("#obtDotBrowseModel").prop("disabled", true);
        }
    });
    //BrowseBrand 
    $('#obtDotBrowseBrand').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPdtBrowseBrandOption = oBrowseBrand({
                'tReturnInputCode': 'oetLotBrandCode',
                'tReturnInputName': 'oetLotBrandName',
            });
            JCNxBrowseData('oPdtBrowseBrandOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
    //Option Agn
    var oBrowseBrand = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title: ['service/pdtlot/pdtlot', 'tLOTBrand'],
            Table: {
                Master: 'TCNMPdtBrand',
                PK: 'FTPbnCode'
            },
            Join: {
                Table: ['TCNMPdtBrand_L'],
                On: ['TCNMPdtBrand_L.FTPbnCode = TCNMPdtBrand.FTPbnCode AND TCNMPdtBrand_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'service/pdtlot/pdtlot',
                ColumnKeyLang: ['tLOTBrandCode', 'tLOTBrandName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtBrand.FTPbnCode', 'TCNMPdtBrand_L.FTPbnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtBrand.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMPdtBrand.FTPbnCode"],
                Text: [tInputReturnName, "TCNMPdtBrand_L.FTPbnName"],
            },
            NextFunc:{
                FuncName:'JSxNextFuncControlBrowseModel'
            },
        }
        return oOptionReturn;
    }

    //BrowseModel 
    $('#obtDotBrowseModel').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPdtBrowseModelOption = oBrowseModel({
                'tReturnInputCode': 'oetLotModelCode',
                'tReturnInputName': 'oetLotModelName',
            });
            JCNxBrowseData('oPdtBrowseModelOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
    //Option Agn
    var oBrowseModel = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title: ['service/pdtlot/pdtlot', 'tLOTModel'],
            Table: {
                Master: 'TCNMPdtModel',
                PK: 'FTPmoCode'
            },
            Join: {
                Table: ['TCNMPdtModel_L'],
                On: ['TCNMPdtModel_L.FTPmoCode = TCNMPdtModel.FTPmoCode AND TCNMPdtModel_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'service/pdtlot/pdtlot',
                ColumnKeyLang: ['tLOTModelCode', 'tLOTModelName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtModel.FTPmoCode', 'TCNMPdtModel_L.FTPmoName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMPdtModel.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMPdtModel.FTPmoCode"],
                Text: [tInputReturnName, "TCNMPdtModel_L.FTPmoName"],
            },
        }
        return oOptionReturn;
    }

    function JSxNextFuncControlBrowseModel()
    {  
        //เคลียค่าโมเดลทุกครั้งที่เปลี่ยนแบรนด์
        $('#oetLotModelCode').val('');
        $('#oetLotModelName').val('');

        var tBrandCode = $('#oetLotBrandCode').val();
        if (tBrandCode != '') {
            $("#obtDotBrowseModel").prop("disabled", false);
        }else{
            $("#obtDotBrowseModel").prop("disabled", true);
        }
    }

    function JSxDotBAMSubmit() {
        $("#ohdCheckLotClearValidate").val("1");
    }

function JSoAddEditPdtDotBAM(ptRoute) {
//   alert (ptRoute);
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddPdtDotBAM').validate().destroy();
        $.validator.addMethod('dublicateCode', function(value, element) {
            if (ptRoute == "maslotBAMEventAdd") {
                if ($("#ohdCheckDuplicateBrandCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }, '');
        $('#ofmAddPdtDotBAM').validate({
            rules: {
                oetLotBrandName: {
                    "required": {}
                },
            },
            messages: {
                oetLotBrandName: {
                    "required": $('#oetLotBrandName').attr('data-validate-required')
                },
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
                $(element).closest('.form-group').addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
            },
            submitHandler: function(form) {
                $.ajax({
                    type: "POST",
                    url: ptRoute,
                    data: $('#ofmAddPdtDotBAM').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        var aReturn = JSON.parse(tResult);
                        if (aReturn['nStaEvent'] == 1) {
                            JSvDotBAMDataTable(1, aReturn['tCodeReturn']);
                        }else{
                            $('#odvModalDupicate').modal('show');
                            $('#ospDupicateMag').text(aReturn['tStaMessg']);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });

    }
}


</script>