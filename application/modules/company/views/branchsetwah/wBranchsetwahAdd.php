<?php
	if (@$aItemResult['rtCode'] == '1') { // Edit Page
		$tRoute		= 'branchSettingWahouseEventEdit';
		$nSeq 		= $aItemResult['raItems']['FNBchOptSeqNo'];
		$nObjValue 	= $aItemResult['raItems']['FTObjCode'];
		$tObjName 	= $aItemResult['raItems']['FTObjName'];
		$tWahCode 	= $aItemResult['raItems']['FTWahCode'];
		$tWahName 	= $aItemResult['raItems']['FTWahName'];
	} else { // Add Page
		$tRoute	= 'branchSettingWahouseEventAdd';
	}
?>

<div class="row">
	<div class="col-xs-12 col-md-5 col-lg-4">

		<!-- รหัสตัวแทนขาย -->
		<div class="form-group">
			<label class="xCNLabelFrm"><?=language('other/reason/reason', 'tRSNAgency') ?></label>
			<input type="text" class="form-control xWPointerEventNone" maxlength="200" value="<?=$aItemResultHD[0]->FTAgnName?>" readonly placeholder="<?=language('other/reason/reason', 'tRSNAgency') ?>">
		</div>

		<!-- รหัสสาขา -->
		<div class="form-group">
			<label class="xCNLabelFrm"><?=language('company/branch/branch','tBchCode') ?></label>
			<input type="text" class="form-control xWPointerEventNone" maxlength="200" value="<?=$aItemResultHD[0]->FTBchName?>" readonly placeholder="<?=language('company/branch/branch','tBchCode') ?>">
		</div>

		<!-- รหัสอ้างอิง -->
		<div class="form-group">
			<label class="xCNLabelFrm"><span class="text-danger">*</span><?=language('company/branch/branch', 'tOptionName') ?></label>
			<div class="input-group">
				<input type="hidden" id="oetBchInWahOptionCode_old" value="<?=@$nObjValue?>">
				<input type="text" class="form-control xCNHide" id="oetBchInWahOptionCode" name="oetBchInWahOptionCode" maxlength="5" value="<?=@$nObjValue?>">
				<input type="text" class="form-control xWPointerEventNone" id="oetBchInWahOptionName" name="oetBchInWahOptionName" maxlength="200" value="<?=@$tObjName?>" readonly placeholder="<?=language('company/branch/branch', 'tOptionName') ?>">
				<span class="input-group-btn">
					<button id="oimBrowseBchInWahOption" type="button" class="btn xCNBtnBrowseAddOn">
						<img src="<?=base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
					</button>
				</span>
			</div>
		</div>

		<!-- รหัสคลัง -->
		<div class="form-group">
			<label class="xCNLabelFrm"><span class="text-danger">*</span><?=language('company/warehouse/warehouse', 'tWahCode') ?></label>
			<div class="input-group">
				<input type="hidden" id="oetBchInWahCode_old" value="<?=@$tWahCode?>">
				<input type="text" class="form-control xCNHide" id="oetBchInWahCode" name="oetBchInWahCode" maxlength="5" value="<?=@$tWahCode?>">
				<input type="text" class="form-control xWPointerEventNone" id="oetBchInWahName" name="oetBchInWahName" maxlength="100" value="<?=@$tWahName?>" readonly placeholder="<?=language('company/warehouse/warehouse', 'tWahCode') ?>">
				<span class="input-group-btn">
					<button id="oimBrowseBchInWah" type="button" class="btn xCNBtnBrowseAddOn">
						<img src="<?=base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
					</button>
				</span>
			</div>
		</div>

	</div>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script>
	//เลือกประเภท
	 $('#oimBrowseBchInWahOption').click(function(){
		JSxCheckPinMenuClose();
		JCNxBrowseData('oBrowseBchInWahOption');
	});

	//เลือกคลัง
	$('#oimBrowseBchInWah').click(function(){
		JSxCheckPinMenuClose();
		JCNxBrowseData('oBrowseBchInWah');
	});

	var nLangEdits  = '<?=$this->session->userdata("tLangEdit")?>';
	var oBrowseBchInWahOption = {
        Title : ['company/branch/branch', 'tOptionName'],
        Table:{Master:'TCNSListObj', PK:'FTObjCode'},
        Join :{
            Table	: ['TCNSListObj_L' ],
            On		: ['TCNSListObj_L.FTObjCode = TCNSListObj.FTObjCode AND TCNSListObj_L.FNLngID = '+nLangEdits]
        },
		Where :{
            Condition : [" AND TCNSListObj.FTAppode = 'SB' AND TCNSListObj.FTObjStaUse = '1'  "]
        },
        GrideView:{
            ColumnPathLang	: 'company/branch/branch',
            ColumnKeyLang	: ['tOptionCode', 'tOptionName'],
            ColumnsSize     : ['15%', '85%'],
            WidthModal      : 50,
            DataColumns		: ['TCNSListObj.FTObjCode', 'TCNSListObj_L.FTObjName'],
            DataColumnsFormat : ['', ''],
            Perpage			: 10,
            OrderBy			: ['TCNSListObj.FTObjCode ASC'],
        },
        CallBack:{
            ReturnType      : 'S',
            Value           : ["oetBchInWahOptionCode", "TCNSListObj.FTObjCode"],
            Text            : ["oetBchInWahOptionName", "TCNSListObj_L.FTObjName"]
        }
    };

	var oBrowseBchInWah = {
        Title	: ['company/warehouse/warehouse','tWAHTitle'],
        Table	: {Master:'TCNMWaHouse',PK:'FTWahCode'},
        Join	: {
            Table	: ['TCNMWaHouse_L'],
            On		: ['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMWaHouse.FTBchCode AND TCNMWaHouse_L.FNLngID = '+nLangEdits,]
        },
        Where :{
            Condition : [" AND TCNMWaHouse.FTBchCode = '"+'<?=$aItem['tBchCode']?>'+"' "]
        },
        GrideView:{
            ColumnPathLang	: 'company/warehouse/warehouse',
            ColumnKeyLang	: ['tWahCode','tWahName'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
            DataColumnsFormat : ['',''],
            Perpage			: 10,
            OrderBy			: ['TCNMWaHouse.FDCreateOn DESC'],
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetBchInWahCode","TCNMWaHouse.FTWahCode"],
            Text		: ["oetBchInWahName","TCNMWaHouse_L.FTWahName"],
        }
    };

	//เพิ่ม และแก้ไข
	function JSxCallEventSaveAndEdit(){

		if($('#oetBchInWahOptionCode').val() == ''){
			$('#oetBchInWahOptionName').focus();
			return;
		}

		if($('#oetBchInWahCode').val() == ''){
			$('#oetBchInWahName').focus();
			return;
		}

		$.ajax({
            type    : "POST",
            url     : '<?=$tRoute?>',
            data    : { 
				'tBchCode' 			: '<?=$aItem['tBchCode']?>' , 
				'tAgnCode' 			: '<?=$aItem['tAgnCode']?>' ,
				'tOptionCode'		: $('#oetBchInWahOptionCode').val() ,
				'tOptionCodeOld'	: $('#oetBchInWahOptionCode_old').val(),
				'tWahCode'			: $('#oetBchInWahCode').val(),
				'tWahCodeOld'		: $('#oetBchInWahCode_old').val(),
				'nSeq'				: '<?=@$nSeq?>'
			},
            cache   : false,
            success: function (tResult) {
				var oObj = JSON.parse(tResult);
				if(oObj.nStatus == 1){
					FSvCMNSetMsgWarningDialog('พบข้อมูลซ้ำ กรุณาลองใหม่อีกครั้ง');
				}else{
					JSvCallPageBranchSetWah();
				}
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
	}


</script>