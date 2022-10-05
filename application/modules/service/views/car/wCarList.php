<?php
	$nType = array('8','1', '2', '3', '4', '5', '7', '6');
?>
<link rel="stylesheet" href="<?php echo base_url('application/modules/service/views/car/css/Ada.FiveColumpGrid.css') ?>">
<div class="panel-heading">
	<div class="row">
		<div class="col-xs-12 col-md-3">
			<div class="form-group">
				<div class="input-group">
					<input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" onkeypress="Javascript:if(event.keyCode==13) JSvCarDataTable()" autocomplete="off" name="oetSearchAll" placeholder="<?php echo language('common/main/main', 'tPlaceholder') ?>">
					<span class="input-group-btn">
						<button id="oimSearchCard" class="btn xCNBtnSearch" type="button">
							<img onclick="JSvCarDataTable()" class="xCNIconBrowse" src="<?= base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-3">
		<button id="obtCarAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
        <button id="oimResetSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
		</div>

		<div class="col-xs-12 col-md-6 text-right">
		<?php if ($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
				<div class="form-group">
					<div>
						<div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
							<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
								<?= language('common/main/main', 'tCMNOption') ?>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li id="oliBtnDeleteAll" class="disabled">
									<a data-toggle="modal" data-target="#odvModalDelCar"><?= language('common/main/main', 'tDelAll') ?></a>
								</li>
							</ul>
						</div>
					</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
	<div id="odvCarAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
		<?php foreach ($nType as $tValue) { ?>
			<div class="xWcol-lg-55 col-md-6 col-xs-12 col-sm-8">
				<div class="form-group">
					<label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCAROption' . $tValue) ?></label>
					<div class="input-group">
						<input class="form-control xCNHide xWAdvanceSeach" type="text" id="oetCarOptionID<?php echo @$tValue; ?>" name="oetCarOptionID<?php echo @$tValue; ?>" maxlength="5">
						<input type="text" class="form-control xWPointerEventNone xWAdvanceSeach" id="oetCarOptionName<?php echo @$tValue; ?>" name="oetCarOptionName<?php echo @$tValue; ?>" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption' . $tValue); ?>" readonly>
						<span class="input-group-btn">
							<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="<?php echo @$tValue; ?>">
								<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
							</button>
						</span>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="xWcol-lg-55 col-md-6 col-xs-12 col-sm-8" style="margin-top:25px;">
			<div class="form-group">
				<button type="button" id="oimAdvSearch" name="oimAdvSearch" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" style='width:45%'>
					<?=language('common\main\main','tSearch'); ?>	
				</button>
			</div>
		</div>
	</div>
	</div>
</div>

<div class="panel-body">
	<section id="ostDataCar"></section>
</div>

<div class="modal fade" id="odvModalDelCarChoose">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="display:inline-block"><?= language('common/main/main', 'tModalDelete') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> <?php echo language('common/main/main', 'tModalDelete') ?> </span>
				<input type='hidden' id="ospConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnCarDelChoose()">
					<i class="fa fa-check-circle" aria-hidden="true"></i> <?= language('common/main/main', 'tModalConfirm') ?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<i class="fa fa-times-circle" aria-hidden="true"></i> <?= language('common/main/main', 'tModalCancel') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<script type="text/javascript">
	$('#oimSearchCard').click(function() {
		JCNxOpenLoading();
		JSvCarDataTable();
	});

	$('#oetSearchAll').keypress(function(event) {
		if (event.keyCode == 13) {
			JCNxOpenLoading();
			JSvCarDataTable();
		}
	});
	$('#oimResetSearch').click(function() {
		$(".xWAdvanceSeach").each(function(index) {
			$(this).val('');
		});
		JCNxOpenLoading();
		JSvCarDataTable();
	});

	$('#oimAdvSearch').click(function() {
		JCNxOpenLoading();
		JSvCarDataTable();
	});

	
	// Advance search Display control
	$('#obtCarAdvanceSearch').unbind().click(function(){
		if($('#odvCarAdvanceSearchContainer').hasClass('hidden')){
			$('#odvCarAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
		}else{
			$("#odvCarAdvanceSearchContainer").slideUp(500,function() {
				$(this).addClass('hidden');
			});
		}
	});

	$('.xWBrowseCarType').click(function(e) {
		let tOptionCar	= $(this).attr("option");
		e.preventDefault();
		let nStaSession = 1;
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose();
			let nLangEdits	= <?php echo $this->session->userdata("tLangEdit") ?>;
			let oBrowseCarType	= function(poReturnInput) {
				let tInputReturnCode	= poReturnInput.tReturnInputCode;
				let tInputReturnName	= poReturnInput.tReturnInputName;
				let tSQLWhere			= "AND TSVMCarInfo.FTCaiType = " + tOptionCar;
				let oOptionReturn = {
					Title: ['service/car/car', 'tCAROption' + tOptionCar],
					Table: {
						Master: 'TSVMCarInfo',
						PK: 'FTCaiCode'
					},
					Join: {
						Table: ['TSVMCarInfo_L'],
						On: ['TSVMCarInfo_L.FTCaiCode = TSVMCarInfo.FTCaiCode AND TSVMCarInfo_L.FNLngID = ' + nLangEdits]
					},
					Where: {
						Condition: [tSQLWhere]
					},
					GrideView: {
						ColumnPathLang: 'product/carinfo/carinfo',
						ColumnKeyLang: ['tCAICode' + tOptionCar, 'tCAIName' + tOptionCar],
						ColumnsSize: ['15%', '85%'],
						WidthModal: 50,
						DataColumns: ['TSVMCarInfo.FTCaiCode', 'TSVMCarInfo_L.FTCaiName'],
						DataColumnsFormat: ['', ''],
						Perpage: 10,
						OrderBy: ['TSVMCarInfo.FDCreateOn DESC'],
					},
					CallBack: {
						ReturnType: 'S',
						Value: [tInputReturnCode, "TSVMCarInfo.FTCaiCode"],
						Text: [tInputReturnName, "TSVMCarInfo_L.FTCaiName"],
					},
					RouteAddNew: 'agency',
					BrowseLev: 1,
				}
				return oOptionReturn;
			}
			window.oPdtBrowseCarTypeOption = oBrowseCarType({
				'tReturnInputCode': 'oetCarOptionID' + tOptionCar,
				'tReturnInputName': 'oetCarOptionName' + tOptionCar,
			});
			JCNxBrowseData('oPdtBrowseCarTypeOption');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});
</script>