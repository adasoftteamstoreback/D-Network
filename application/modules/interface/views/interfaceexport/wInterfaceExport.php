<input id="oetInterfaceExportStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetInterfaceExportCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<?php
	if($this->session->userdata("tSesUsrLevel") == "HQ"){
		$tDisabled = "";
	}else{
		$nCountBch = $this->session->userdata("nSesUsrBchCount");
		if($nCountBch == 1){
			$tDisabled = "disabled";
		}else{
			$tDisabled = "";
		}
	}

	$tUserBchCode 	= $this->session->userdata("tSesUsrBchCodeDefault");
	$tUserBchName 	= $this->session->userdata("tSesUsrBchNameDefault");
	$dStartFrm		= date('Y-m-d');
	$dStartTo		= date('Y-m-d');
?>

<div id="odvCpnMaIFXenu" class="main-menu clearfix">
	<div class="xCNMrgNavMenu">
		<div class="row xCNavRow" style="width:inherit;">
			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
				<ol id="oliMenuNav" class="breadcrumb">
					<?php FCNxHADDfavorite('interfaceexport/0/0');?> 
					<li id="oliInterfaceExportTitle" class="xCNLinkClick" style="cursor:pointer" onclick="JSvInterfaceExportCallPage('')">
						<?= language('interface/interfaceexport/interfaceexport','tITFXTitle') ?>
					</li>
				</ol>
			</div>
			
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
				<div id="odvBtnCmpEditInfo">
					<button id="obtInterfaceExportConfirm" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> 
						<?=language('interface/interfaceexport/interfaceexport','tITFXTConfirm')  ?>
					</button> 
				</div>
			</div>
		</div>
	</div>
</div>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmInterfaceExport">
	<div class="main-content">
		<div class="panel panel-headline">
			<input type="hidden" name="tUserCode" id="tUserCode" value="<?=$this->session->userdata('tSesUserCode')?>">
			<div class="row">
				<div class="col-md-12">
					<div class="panel-body">
						<!-- <div class="col-md-12">
							<input type="checkbox" name="ocbReqpairExport" id="ocbReqpairExport"  value="1" > เฉพาะรายการส่งไม่สำเร็จ
						</div> -->
						<div class="table-responsive" style="padding:20px">
							<table id="otbInterfaceExportDataList" class="table table-striped">
								<thead>
									<tr>
										<th width="4%" nowrap class="text-center xCNTextBold"><?=language('interface/interfaceexport/interfaceexport','tITFXID'); ?></th>
										<th width="4%" nowrap class="text-center xCNTextBold">
											<input type="checkbox" id="ocmINMExportAll" value="1">
										</th>
										<th width="20%" nowrap class="text-center xCNTextBold"><?=language('interface/interfaceexport/interfaceexport','tITFXList'); ?></th>
									</tr>
								</thead>
								<tbody>
									<!--เก็บค่าเจ้าหนี้แบบ เลือกได้มากกว่าหนึ่ง-->
									<input type='text' class='form-control xCNHide' id="ohdExCreditCode" name="ohdExCreditCode">

									<?php if(!empty($aDataMasterExport)){ ?>
										<?php foreach($aDataMasterExport AS $nK => $aData){ ?>
											<tr data-apicode="<?=$aData['FTApiCode']?>">
												<td align="center"><?=($nK+1)?></td>
												<td align="center">
													<input type="checkbox" class="progress-bar-chekbox xCNCheckBoxExport xCNCheckbox<?=$aData['FTApiCode']?>" name="ocmIFXExport[]" value="<?=$aData['FTApiCode']?>" idpgb="xWIFXExpBonTextDisplay" data-type="ExpBon">
												</td>
												<td align="left"><?=$aData['FTApiName']?></td>
												<?php 
													//$aFilterSearch = '1' : สาขา ถึง สาขา
													//$aFilterSearch = '2' : วันที่ ถึง วันที่
													//$aFilterSearch = '3' : เลขที่บิล ถึง เลขที่บิล
													//$aFilterSearch = '4' : เจ้าหนี้ ถึง เจ้าหนี้
													//$aFilterSearch = '5' : เดือน
													//$aFilterSearch = '6' : ปี
													//$aFilterSearch = '7' : กลุ่มสินค้า ถึง กลุ่มสินค้า
													//$aFilterSearch = '8' : หมวดสินค้า ถึง หมวดสินค้า
													//$aFilterSearch = '9' : สินค้า ถึง สินค้า
													//$aFilterSearch = '10' : หน่วยนับ ถึง หน่วยนับ
													//$aFilterSearch = '11' : ประเภทการชำระเงิน ถึง ประเภทการชำระเงิน
													//$aFilterSearch = '12' : เลขที่คูปอง ถึง เลขที่คูปอง
													//$aFilterSearch = '13' : รหัสคูปอง ถึง รหัสคูปอง
													//$aFilterSearch = '14' : รหัสโปรโมชั่น ถึง รหัสโปรโมชั่น
													//$aFilterSearch = '15' : เลขที่ใบรับวางบิล
													//$aFilterSearch = '16' : เลขที่ใบซื้อ

													switch ($aData['FTApiCode']) {
														case "00028": //ส่งออกรายได้ขายแยกตามประเภทการชำระเงิน(SAP)
															$HideClass = 'xCNHide';
															$aFilterSearch = [1,2,3];
															break;
														case "00029": //รายการเครดิต(เจ้าหนี้)(ตั้งหนี้ 3 ตอน)(SAP)
															$HideClass = '';
															$aFilterSearch = [1,2,4,15,16];
															break;
														case "00030": //ต้นทุน FIT Auto (SAP + BIGDATA)
															$aFilterSearch = [1,5,6];
															break;
														case "00031": //ส่งออกรถกองยาน (SAP)
															$HideClass = 'xCNHide';
															$aFilterSearch = [1,2];
															break;
														case "00032": //ปรับปรุงลูกหนี้ QR Payment (SAP)
															$HideClass = 'xCNHide';
															$aFilterSearch = [1,2];
															break;
														case "00033": //ข้อมูลรายการ Master (BIGDATA)
															$aFilterSearch = [];
															break;
														case "00034": //ข้อมูลรายการขาย (BIGDATA)
															$HideClass = 'xCNHide';
															$aFilterSearch = [1,2];
															break;
															
														default:
													}
												?>
											
											</tr>
										<?php } ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<!--Modal Success-->
<div class="modal fade" id="odvInterfaceEmportSuccess">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('interface/interfaceexport/interfaceexport','tStatusProcess'); ?></h5>
            </div>
            <div class="modal-body">
                <p><?=language('interface/interfaceexport/interfaceexport','tContentProcess'); ?></p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" id="obtIFXModalMsgConfirm" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
				<button type="button" id="obtIFXModalMsgCancel" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
            </div>
        </div>
    </div>
</div>

<!--Modal กรุณาเลือกข้อมูล-->
<div class="modal fade" id="odvInterfaceExportIsNull" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tModalWarning')?></h5>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกข้อมูลในการส่งออก</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--Modal ไม่สามารถส่งออกได้-->
<div class="modal fade" id="odvInterfaceExportTextFile" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?=language('common/main/main', 'tModalWarning')?></label>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
						<button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal" >ปิด</button>
					</div>
				</div>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-sm-12">
						<p><b>ไม่สามารถส่งออกได้ เนื่องจากข้อมูลยังส่งมาจากสาขาไม่ครบ<b></p>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<!--Modal ไม่สามารถส่งออกได้-->
<div class="modal fade" id="odvInterfaceExportCostIsNull" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?=language('common/main/main', 'tModalWarning')?></label>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
						<button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal" >ปิด</button>
					</div>
				</div>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="col-lg-7 col-sm-7">
						<p><b>ไม่สามารถส่งออกได้ เนื่องจาก มีเอกสารค้างอนุมัติ สามารถตรวจสอบรายละเอียดได้ดังต่อไปนี้<b></p>
					</div>
					<div class="col-lg-5 col-sm-5">
						<button type="button" class="btn xCNBTNImportFile" style="float: right; margin-bottom: 10px;" onclick="JSxIFXExportDocumentNotApv()" >
							ส่งออก Excel
						</button>
					</div>
				</div>

				<div class="row" id="odvHistoryDocumentNotApv" style="display:none;">
					<div class="col-lg-12">
						<div class="table-responsive" style="overflow-x: auto; height: 300px;">
							<table class="table" id="otbInterfaceExportDocNotApv" style="width:100%">
								<thead>
									<tr>
										<th>ลำดับ</th>
										<th>ชื่อสาขา</th>
										<th>เอกสาร</th>
										<th>เลขที่เอกสาร</th>
										<th>วันที่เอกสาร</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>

				<br>
            </div>
        </div>
    </div>
</div>

<!--Modal ไม่พบเอกสารต้นทุน แต่สามารถไปต่อได้-->
<div class="modal fade" id="odvInterfaceExportCostIsNullButCanMoveOn" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tModalWarning')?></h5>
            </div>
            <div class="modal-body">
                <p id="ospExportCostIsNullButCanMoveOn">
					พบเอกสารที่มีผลกับสต็อกค้างอนุมัติ ระบบไม่สามารถส่งออกรายการต้นทุนได้<br> 
					ยืนยันที่จะดำเนินการในหัวข้อรายการอื่นๆ ต่อหรือไม่ ?
				</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" type="button" class="btn xCNBTNPrimery" onclick="JSxIFXCallRabbitMQToExport('remove')">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
				<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal">
					<?= language('common/main/main', 'tModalCancel')?>
				</button>
            </div>
        </div>
    </div>
</div>

<!--Modal กรุณาเลือกวันที่-->
<div class="modal fade" id="odvInterfaceExportDateIsNull" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block">แจ้งเตือน</h5>
            </div>
            <div class="modal-body">
                <p>กรุณาระบุวันที่ของเรื่องที่จะนำส่งออกให้ครบถ้วน</p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal"  id="obtIFXModalMsgConfirmIsNull" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('application/modules/interface/assets/src/interfaceexport/jInterfaceExport.js')?>"></script>
<script>
	$( document ).ready(function() {
		$('.xCNDatePicker').datepicker({
			format			: 'yyyy-mm-dd',
			autoclose		: true,
			todayHighlight	: true,
		});
	});


	//ตัวแปรสำหรับการ browse ต่างๆ
	var tTypeBrowse , tApiCode , tKeyInputCode , tKeyInputName;
	var nLangEdits  = '<?=$this->session->userdata("tLangEdit");?>';

	//////////////////////////////////////////  Browse ผู้เจ้าหน่าย //////////////////////////////////////
	$('.xCNExBrowseCreditFrm , .xCNExBrowseCreditTo').off('click').on('click',function(){
		tTypeBrowse 	= $(this).attr('data-typebrowse');
		tApiCode 		= $(this).attr('data-apicode');
		tKeyInputCode	= "oetExCreditCode";
		tKeyInputName	= "oetExCreditName";
		var tCode 		= tKeyInputCode+tTypeBrowse+tApiCode;
		var tName 		= tKeyInputName+tTypeBrowse+tApiCode;

		window.oBrowseSPL = undefined;
		oBrowseSPL        = oBrowseSPLOption({
			'tReturnInputCode'  : tCode,
			'tReturnInputName'  : tName,
			'tNextFuncName'     : 'JSxEXAfterBrowseSPL',
			'aArgReturn'        : ['FTSplCode','FTSplName']
		});
		JCNxBrowseMultiSelect('oBrowseSPL');
	});

	var oBrowseSPLOption      = function(poDataFnc){
		var tInputReturnCode    = poDataFnc.tReturnInputCode;
		var tInputReturnName    = poDataFnc.tReturnInputName;
		var tNextFuncName       = poDataFnc.tNextFuncName;
		var aArgReturn          = poDataFnc.aArgReturn;
		var oOptionReturn       = {
			Title	: ['supplier/supplier/supplier', 'tSPLTitle'],
			Table	: {Master:'TCNMSpl', PK:'FTSplCode'},
			Join	: {
				Table	: ['TCNMSpl_L', 'TCNMSplCredit'],
				On		: [
					'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
					'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
				]
			},
			Where:{
				Condition : ["AND TCNMSpl.FTSplStaActive = '1' "]
			},
			GrideView:{
				ColumnPathLang	: 'supplier/supplier/supplier',
				ColumnKeyLang	: ['tSPLTBCode', 'tSPLTBName'],
				ColumnsSize		: ['15%', '75%'],
				WidthModal		: 50,
				DataColumns		: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid'],
				DataColumnsFormat: ['',''],
				DisabledColumns	: [2, 3, 4, 5],
				Perpage			: 10,
				OrderBy			: ['TCNMSpl_L.FTSplName ASC']
			},
			CallBack:{
				StausAll    	: ['ohdExCreditCode'],
				Value   		: [tInputReturnCode,"TCNMSpl.FTSplCode"],
				Text    		: [tInputReturnName,"TCNMSpl_L.FTSplName"]
			},
		};
		return oOptionReturn;
	}

	//////////////////////////////////////////  Browse สาขา ////////////////////////////////////////// 
	$('.xCNExBrowseBchFrm , .xCNExBrowseBchTo').off('click').on('click',function(){
		tTypeBrowse 	= $(this).attr('data-typebrowse');
		tApiCode 		= $(this).attr('data-apicode');
		tKeyInputCode	= "oetExBchCode";
		tKeyInputName	= "oetExBchName";
		var tCode 		= tKeyInputCode+tTypeBrowse+tApiCode;
		var tName 		= tKeyInputName+tTypeBrowse+tApiCode;

		window.oBrowseBCH = undefined;
		oBrowseBCH        = oBrowseBCHOption({
			'tReturnInputCode'  : tCode,
			'tReturnInputName'  : tName,
			'tNextFuncName'     : 'JSxEXAfterBrowseBCH',
			'aArgReturn'        : ['FTBchCode','FTBchName']
		});
		JCNxBrowseData('oBrowseBCH');
	});

	var oBrowseBCHOption = function(poReturnInput){
        var tNextFuncName    = poReturnInput.tNextFuncName;
        var aArgReturn       = poReturnInput.aArgReturn;
        var tInputReturnCode = poReturnInput.tReturnInputCode;
		var tInputReturnName = poReturnInput.tReturnInputName;

		var tUsrLevel     = "<?= $this->session->userdata("tSesUsrLevel"); ?>";
		var tBchCodeMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
		var nCountBch     = "<?= $this->session->userdata("nSesUsrBchCount"); ?>";
		var tWhere		  = "";

		if(tUsrLevel != "HQ"){
			tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
		}else{
			tWhere = "";
		}

        var oOptionReturn    = {
            Title	: ['company/branch/branch','tBCHTitle'],
            Table	: {Master:'TCNMBranch',PK:'FTBchCode'},
			Join	: {
                Table	: ['TCNMBranch_L'],
                On		 :['TCNMBranch.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
			},
			Where: {
				Condition: [tWhere]
			},
            GrideView:{
                ColumnPathLang	: 'company/branch/branch',
                ColumnKeyLang	: ['tBCHCode','tBCHName'],
                ColumnsSize     : ['30%','70%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat : ['',''],
                Perpage			: 10,
                OrderBy			: ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"]
			},
			NextFunc:{
				FuncName    	: tNextFuncName,
				ArgReturn   	: aArgReturn
			}
        };
        return oOptionReturn;
	};

	//หลังจากเลือกสาขา
	function JSxEXAfterBrowseBCH(ptDataNextFunc){
		if(typeof(ptDataNextFunc) != undefined && ptDataNextFunc != "NULL"){
			aDataNextFunc   = JSON.parse(ptDataNextFunc);
            tParamFirst     = aDataNextFunc[0];
			tParamSecond	= aDataNextFunc[1];
			if(tTypeBrowse == 'Frm'){
				var tNewTypeBrowse = 'To';
				$('#'+tKeyInputCode+tNewTypeBrowse+tApiCode).val(tParamFirst);	
				$('#'+tKeyInputName+tNewTypeBrowse+tApiCode).val(tParamSecond);
			}
		}else{
			var tTypeBrowseFrm 	= 'Frm';
			var tTypeBrowseTo 	= 'To';
			$('#'+tKeyInputCode+tTypeBrowseFrm+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseFrm+tApiCode).val('');
			$('#'+tKeyInputCode+tTypeBrowseTo+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseTo+tApiCode).val('');
		}
	}
	
	//////////////////////////////////////////  Browse เลขที่เอกสาร /////////////////////////////////////
	$('.xCNExBrowseDocSaleFrm , .xCNExBrowseDocSaleTo').off('click').on('click',function(){
		tTypeBrowse 	= $(this).attr('data-typebrowse');
		tApiCode 		= $(this).attr('data-apicode');

		//ไปสร้างเอกสารลง Temp
		JSxCreateDataDocSaleToTemp();
	});

	function JSxCreateDataDocSaleToTemp(){
		$.ajax({
			type    : "POST",
			url     : "interfaceexportFilterBill",
			data    : {
				"dDateFrm"		:	$('#oetExDateFrm'+tApiCode).val(),
				"dDateTo"		:	$('#oetExDateTo'+tApiCode).val(),
				"tBCHCodeFrm"	:	$('#oetExBchCodeFrm'+tApiCode).val(),
				"tBCHCodeTo"	:   $('#oetExBchCodeTo'+tApiCode).val(),
				"tSPLCodeFrm"	: 	$('#oetExCreditCodeFrm'+tApiCode).val(),
				"tSPLCodeTo"	:	$('#oetExCreditCodeTo'+tApiCode).val(),
				"tType"			:   "DocSale"
			},
			cache   : false,
			Timeout : 0,
			success: function(tResult){
				tKeyInputCode	= "oetExDocSaleCode";
				tKeyInputName	= "oetExDocSaleName";
				var tCode 		= tKeyInputCode+tTypeBrowse+tApiCode;
				var tName 		= tKeyInputName+tTypeBrowse+tApiCode;

				window.oBrowseSALE = undefined;
				oBrowseSALE        = oBrowseSALEOption({
					'tReturnInputCode'  : tCode,
					'tReturnInputName'  : tName,
					'tNextFuncName'     : 'JSxEXAfterBrowseSALE',
					'aArgReturn'        : ['FTXshDocNo','FTXshDocNo']
				});
				JCNxBrowseData('oBrowseSALE');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
			}
		});
	}

	var oBrowseSALEOption = function(poReturnInput){
        var tNextFuncName    = poReturnInput.tNextFuncName;
        var aArgReturn       = poReturnInput.aArgReturn;
        var tInputReturnCode = poReturnInput.tReturnInputCode;
		var tInputReturnName = poReturnInput.tReturnInputName;
		var tWhere		     = "";
		var tSessionUserCode  = '<?=$this->session->userdata('tSesUserCode')?>'
		
		tWhere += " AND TCNTBrsBillTmp.FTUsrCode = '"+tSessionUserCode+"' ";
	
        var oOptionReturn    = {
            Title	: ['interface/interfaceexport/interfaceexport','tITFXDataSal'],
            Table	: {Master:'TCNTBrsBillTmp',PK:'FTXshDocNo'},
			Where	: {
                    Condition: [tWhere]
			},
            GrideView:{
                ColumnPathLang	: 'interface/interfaceexport/interfaceexport',
                ColumnKeyLang	: ['tITFXSalDocNo','tITFXSalDate'],
                ColumnsSize     : ['30%','50%','20%'],
                WidthModal      : 50,
                DataColumns		: ['TCNTBrsBillTmp.FTXshDocNo','TCNTBrsBillTmp.FDXshDocDate'],
                DataColumnsFormat : ['','',''],
                Perpage			: 10,
                OrderBy			: ['FTXshDocNo ASC'],
            },
            CallBack:{
				ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNTBrsBillTmp.FTXshDocNo"],
                Text		: [tInputReturnName,"TCNTBrsBillTmp.FTXshDocNo"]
			},
            NextFunc : {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        };
        return oOptionReturn;
	};

	//หลังจากเลือกเลขที่เอกสาร
	function JSxEXAfterBrowseSALE(ptDataNextFunc){
		if(typeof(ptDataNextFunc) != undefined && ptDataNextFunc != "NULL"){
			aDataNextFunc   = JSON.parse(ptDataNextFunc);
            tParamFirst     = aDataNextFunc[0];
			tParamSecond	= aDataNextFunc[1];
			if(tTypeBrowse == 'Frm'){
				var tNewTypeBrowse = 'To';
				$('#'+tKeyInputCode+tNewTypeBrowse+tApiCode).val(tParamFirst);	
				$('#'+tKeyInputName+tNewTypeBrowse+tApiCode).val(tParamSecond);
			}
		}else{
			var tTypeBrowseFrm 	= 'Frm';
			var tTypeBrowseTo 	= 'To';
			$('#'+tKeyInputCode+tTypeBrowseFrm+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseFrm+tApiCode).val('');
			$('#'+tKeyInputCode+tTypeBrowseTo+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseTo+tApiCode).val('');
		}
	}

	//////////////////////////////////////////  Browse เลขที่เอกสารใบวางบิล /////////////////////////////////////
	$('.xCNExBrowseDocBillFrm').off('click').on('click',function(){
		tTypeBrowse 	= $(this).attr('data-typebrowse');
		tApiCode 		= $(this).attr('data-apicode');

		//ไปสร้างเอกสารลง Temp
		JSxCreateDataBillToTemp();
	});

	function JSxCreateDataBillToTemp(){
		$.ajax({
			type    : "POST",
			url     : "interfaceexportFilterBill",
			data    : {
				"dDateFrm"		:	$('#oetExDateFrm'+tApiCode).val(),
				"dDateTo"		:	$('#oetExDateTo'+tApiCode).val(),
				"tBCHCodeFrm"	:	$('#oetExBchCodeFrm'+tApiCode).val(),
				"tBCHCodeTo"	:   $('#oetExBchCodeTo'+tApiCode).val(),
				"tSPLCodeFrm"	: 	$('#oetExCreditCodeFrm'+tApiCode).val(),
				"tSPLCodeTo"	:	$('#oetExCreditCodeTo'+tApiCode).val(),
				"tType"			:   "DocBill"
			},
			cache   : false,
			Timeout : 0,
			success: function(tResult){
				tKeyInputCode	= "oetExDocBillCode";
				tKeyInputName	= "oetExDocBillName";
				var tCode 		= tKeyInputCode+tTypeBrowse+tApiCode;
				var tName 		= tKeyInputName+tTypeBrowse+tApiCode;

				window.oBrowseBill = undefined;
				oBrowseBill        = oBrowseBillOption({
					'tReturnInputCode'  : tCode,
					'tReturnInputName'  : tName,
					'tNextFuncName'     : 'JSxEXAfterBrowseBill',
					'aArgReturn'        : ['FTXshDocNo','FTXshDocNo']
				});
				JCNxBrowseMultiSelect('oBrowseBill');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
			}
		});
	}

	var oBrowseBillOption = function(poReturnInput){
        var tNextFuncName    = poReturnInput.tNextFuncName;
        var aArgReturn       = poReturnInput.aArgReturn;
        var tInputReturnCode = poReturnInput.tReturnInputCode;
		var tInputReturnName = poReturnInput.tReturnInputName;
		var tWhere		     = "";
		var tSessionUserCode  = '<?=$this->session->userdata('tSesUserCode')?>'
		
		tWhere += " AND TCNTBrsBillTmp.FTUsrCode = '"+tSessionUserCode+"' ";
	
        var oOptionReturn    = {
            Title	: ['interface/interfaceexport/interfaceexport','tITFXDataSal'],
            Table	: {Master:'TCNTBrsBillTmp',PK:'FTXshDocNo'},
			Where	: {
                    Condition: [tWhere]
			},
            GrideView:{
                ColumnPathLang	: 'interface/interfaceexport/interfaceexport',
                ColumnKeyLang	: ['tITFXSalDocNo','tITFXSalDate'],
                ColumnsSize     : ['30%','50%','20%'],
                WidthModal      : 50,
                DataColumns		: ['TCNTBrsBillTmp.FTXshDocNo','TCNTBrsBillTmp.FDXshDocDate'],
                DataColumnsFormat : ['','',''],
                Perpage			: 10,
                OrderBy			: ['FTXshDocNo ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNTBrsBillTmp.FTXshDocNo"],
                Text		: [tInputReturnName,"TCNTBrsBillTmp.FTXshDocNo"]
			},
            /*NextFunc : {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }*/
        };
        return oOptionReturn;
	};

	//หลังจากเลือกเลขที่เอกสารใบวางบิล
	function JSxEXAfterBrowseBill(ptDataNextFunc){
		if(typeof(ptDataNextFunc) != undefined && ptDataNextFunc != "NULL"){
			aDataNextFunc   = JSON.parse(ptDataNextFunc);
            tParamFirst     = aDataNextFunc[0];
			tParamSecond	= aDataNextFunc[1];
			if(tTypeBrowse == 'Frm'){
				var tNewTypeBrowse = 'To';
				$('#'+tKeyInputCode+tNewTypeBrowse+tApiCode).val(tParamFirst);	
				$('#'+tKeyInputName+tNewTypeBrowse+tApiCode).val(tParamSecond);
			}
		}else{
			var tTypeBrowseFrm 	= 'Frm';
			var tTypeBrowseTo 	= 'To';
			$('#'+tKeyInputCode+tTypeBrowseFrm+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseFrm+tApiCode).val('');
			$('#'+tKeyInputCode+tTypeBrowseTo+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseTo+tApiCode).val('');
		}
	}

	//////////////////////////////////////////  Browse เลขที่เอกสารใบซื้อ /////////////////////////////////////
	$('.xCNExBrowseDocIV').off('click').on('click',function(){
		tApiCode 		= $(this).attr('data-apicode');

		//ไปสร้างเอกสารลง Temp
		JSxCreateDataIVToTemp();
	});

	function JSxCreateDataIVToTemp(){
		$.ajax({
			type    : "POST",
			url     : "interfaceexportFilterBill",
			data    : {
				"dDateFrm"		:	$('#oetExDateFrm'+tApiCode).val(),
				"dDateTo"		:	$('#oetExDateTo'+tApiCode).val(),
				"tBCHCodeFrm"	:	$('#oetExBchCodeFrm'+tApiCode).val(),
				"tBCHCodeTo"	:   $('#oetExBchCodeTo'+tApiCode).val(),
				"tSPLCodeFrm"	: 	$('#oetExCreditCodeFrm'+tApiCode).val(),
				"tType"			:   "DocIV"
			},
			cache   : false,
			Timeout : 0,
			success: function(tResult){
				tKeyInputCode	= "oetExDocIVCode";
				tKeyInputName	= "oetExDocIVName";
				var tCode 		= tKeyInputCode+tApiCode;
				var tName 		= tKeyInputName+tApiCode;

				window.oBrowseIV = undefined;
				oBrowseIV        = oBrowseIVOption({
					'tReturnInputCode'  : tCode,
					'tReturnInputName'  : tName,
					'tNextFuncName'     : 'JSxEXAfterBrowseIV',
					'aArgReturn'        : ['FTXshDocNo','FTXshDocNo']
				});
				JCNxBrowseMultiSelect('oBrowseIV');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
			}
		});
	}

	var oBrowseIVOption = function(poReturnInput){
        var tNextFuncName    	= poReturnInput.tNextFuncName;
        var aArgReturn       	= poReturnInput.aArgReturn;
        var tInputReturnCode 	= poReturnInput.tReturnInputCode;
		var tInputReturnName 	= poReturnInput.tReturnInputName;
		var tWhere		     	= "";
		var tSessionUserCode  	= '<?=$this->session->userdata('tSesUserCode')?>'
		
		tWhere += " AND TCNTBrsBillTmp.FTUsrCode = '"+tSessionUserCode+"' ";
	
        var oOptionReturn    = {
            Title	: ['interface/interfaceexport/interfaceexport','tITFXDataSal'],
            Table	: {Master:'TCNTBrsBillTmp',PK:'FTXshDocNo'},
			Where	: {
                    Condition: [tWhere]
			},
            GrideView:{
                ColumnPathLang	: 'interface/interfaceexport/interfaceexport',
                ColumnKeyLang	: ['tITFXSalDocNo','tITFXSalDate'],
                ColumnsSize     : ['30%','50%','20%'],
                WidthModal      : 50,
                DataColumns		: ['TCNTBrsBillTmp.FTXshDocNo','TCNTBrsBillTmp.FDXshDocDate'],
                DataColumnsFormat : ['','',''],
                Perpage			: 10,
                OrderBy			: ['FTXshDocNo ASC'],
            },
            CallBack:{
                Value		: [tInputReturnCode,"TCNTBrsBillTmp.FTXshDocNo"],
                Text		: [tInputReturnName,"TCNTBrsBillTmp.FTXshDocNo"]
			},
            /*NextFunc : {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }*/
        };
        return oOptionReturn;
	};

	//////////////////////////////////////////  Browse โปรโมชั่น ////////////////////////////////////////// 
	$('.xCNExBrowsePromotionFrm , .xCNExBrowsePromotionTo').off('click').on('click',function(){
		tTypeBrowse 	= $(this).attr('data-typebrowse');
		tApiCode 		= $(this).attr('data-apicode');
		tKeyInputCode	= "oetExPromotionCode";
		tKeyInputName	= "oetExPromotionName";
		var tCode 		= tKeyInputCode+tTypeBrowse+tApiCode;
		var tName 		= tKeyInputName+tTypeBrowse+tApiCode;

		window.oBrowsePromotion = undefined;
		oBrowsePromotion        = oBrowsePromotionOption({
			'tReturnInputCode'  : tCode,
			'tReturnInputName'  : tName,
			'tNextFuncName'     : 'JSxEXAfterBrowsePromotion',
			'aArgReturn'        : ['FTPmhDocNo','FTPmhName']
		});
		JCNxBrowseData('oBrowsePromotion');
	});

	var oBrowsePromotionOption = function(poReturnInput){
        var tNextFuncName    = poReturnInput.tNextFuncName;
        var aArgReturn       = poReturnInput.aArgReturn;
        var tInputReturnCode = poReturnInput.tReturnInputCode;
		var tInputReturnName = poReturnInput.tReturnInputName;

		var tUsrLevel     = "<?= $this->session->userdata("tSesUsrLevel"); ?>";
		var tBchCodeMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
		var nCountBch     = "<?= $this->session->userdata("nSesUsrBchCount"); ?>";
		var tWhere		  = "";

		if(tUsrLevel != "HQ"){
			tWhere = " AND TCNTPdtPmtHD.FTBchCode IN ("+tBchCodeMulti+") ";
		}else{
			tWhere = "";
		}

        var oOptionReturn    = {
            Title	: ['document/promotion/promotion','tTitle'],
            Table	: {Master:'TCNTPdtPmtHD',PK:'FTPmhDocNo'},
			Join	: {
                Table	: ['TCNTPdtPmtHD_L'],
                On		 :['TCNTPdtPmtHD.FTPmhDocNo = TCNTPdtPmtHD_L.FTPmhDocNo AND TCNTPdtPmtHD.FTBchCode = TCNTPdtPmtHD_L.FTBchCode AND TCNTPdtPmtHD_L.FNLngID = '+nLangEdits]
			},
			Where: {
				Condition: [tWhere]
			},
            GrideView:{
                ColumnPathLang	: 'document/promotion/promotion',
                ColumnKeyLang	: ['tBCH','tTBDocNo','tPromotionName'],
                ColumnsSize     : ['20%','30%','70%'],
                WidthModal      : 50,
                DataColumns		: ['TCNTPdtPmtHD.FTBchCode','TCNTPdtPmtHD.FTPmhDocNo','TCNTPdtPmtHD_L.FTPmhName'],
                DataColumnsFormat : ['','',''],
                Perpage			: 10,
                OrderBy			: ['TCNTPdtPmtHD.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNTPdtPmtHD.FTPmhDocNo"],
                Text		: [tInputReturnName,"TCNTPdtPmtHD_L.FTPmhName"]
			},
			NextFunc:{
				FuncName    	: tNextFuncName,
				ArgReturn   	: aArgReturn
			}
        };
        return oOptionReturn;
	};

	//หลังจากเลือกโปรโมชั่น
	function JSxEXAfterBrowsePromotion(ptDataNextFunc){
		if(typeof(ptDataNextFunc) != undefined && ptDataNextFunc != "NULL"){
			aDataNextFunc   = JSON.parse(ptDataNextFunc);
            tParamFirst     = aDataNextFunc[0];
			tParamSecond	= aDataNextFunc[1];
			if(tTypeBrowse == 'Frm'){
				var tNewTypeBrowse = 'To';
				$('#'+tKeyInputCode+tNewTypeBrowse+tApiCode).val(tParamFirst);	
				$('#'+tKeyInputName+tNewTypeBrowse+tApiCode).val(tParamSecond);
			}
		}else{
			var tTypeBrowseFrm 	= 'Frm';
			var tTypeBrowseTo 	= 'To';
			$('#'+tKeyInputCode+tTypeBrowseFrm+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseFrm+tApiCode).val('');
			$('#'+tKeyInputCode+tTypeBrowseTo+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseTo+tApiCode).val('');
		}
	}

	//////////////////////////////////////////  Browse กลุ่มสินค้า ////////////////////////////////////////// 
	$('.xCNExBrowseGrpPDTFrm , .xCNExBrowseGrpPDTTo').off('click').on('click',function(){
		tTypeBrowse 	= $(this).attr('data-typebrowse');
		tApiCode 		= $(this).attr('data-apicode');
		tKeyInputCode	= "oetExGrpPDTCode";
		tKeyInputName	= "oetExGrpPDTName";
		var tCode 		= tKeyInputCode+tTypeBrowse+tApiCode;
		var tName 		= tKeyInputName+tTypeBrowse+tApiCode;

		window.oBrowseGroupPDT = undefined;
		oBrowseGroupPDT        = oBrowseGroupPDTOption({
			'tReturnInputCode'  : tCode,
			'tReturnInputName'  : tName,
			'tNextFuncName'     : 'JSxEXAfterBrowseGroupPDT',
			'aArgReturn'        : ['FTPgpChain','FTPgpName']
		});
		JCNxBrowseData('oBrowseGroupPDT');
	});

	var oBrowseGroupPDTOption = function(poReturnInput){
        // var tNextFuncName    = poReturnInput.tNextFuncName;
        // var aArgReturn       = poReturnInput.aArgReturn;
        // var tInputReturnCode = poReturnInput.tReturnInputCode;
		// var tInputReturnName = poReturnInput.tReturnInputName;

		// var tUsrLevel     = "<?= $this->session->userdata("tSesUsrLevel"); ?>";
		// var tBchCodeMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
		// var nCountBch     = "<?= $this->session->userdata("nSesUsrBchCount"); ?>";
		// var tWhere		  = "";

		// if(tUsrLevel != "HQ"){
		// 	tWhere = " AND TCNTPdtPmtHD.FTAgnCode IN ("+tBchCodeMulti+") ";
		// }else{
		// 	tWhere = "";
		// }

        // var oOptionReturn    = {
        //     Title	: ['document/promotion/promotion','tTitle'],
        //     Table	: {Master:'TCNMPdtGrp',PK:'FTPmhDocNo'},
		// 	Join	: {
        //         Table	: ['TCNMPdtGrp_L'],
        //         On		 :['TCNMPdtGrp.FTPgpChain = TCNMPdtGrp_L.FTPgpChain AND TCNMPdtGrp_L.FNLngID = '+nLangEdits]
		// 	},
        //     GrideView:{
        //         ColumnPathLang	: 'document/promotion/promotion',
        //         ColumnKeyLang	: ['tBCH','tTBDocNo'],
        //         ColumnsSize     : ['20%','30%'],
        //         WidthModal      : 50,
        //         DataColumns		: ['TCNMPdtGrp.FTPgpChain','TCNMPdtGrp_L.FTPgpName'],
        //         DataColumnsFormat : ['','',''],
        //         Perpage			: 10,
        //         OrderBy			: ['TCNMPdtGrp.FDCreateOn DESC'],
        //     },
        //     CallBack:{
        //         ReturnType	: 'S',
        //         Value		: [tInputReturnCode,"TCNMPdtGrp.FTPmhDocNo"],
        //         Text		: [tInputReturnName,"TCNMPdtGrp_L.FTPmhName"]
		// 	},
		// 	NextFunc:{
		// 		FuncName    	: tNextFuncName,
		// 		ArgReturn   	: aArgReturn
		// 	}
        // };
        // return oOptionReturn;
	};

	//หลังจากเลือกกลุ่มสินค้า
	function JSxEXAfterBrowseGroupPDT(ptDataNextFunc){
		if(typeof(ptDataNextFunc) != undefined && ptDataNextFunc != "NULL"){
			aDataNextFunc   = JSON.parse(ptDataNextFunc);
            tParamFirst     = aDataNextFunc[0];
			tParamSecond	= aDataNextFunc[1];
			if(tTypeBrowse == 'Frm'){
				var tNewTypeBrowse = 'To';
				$('#'+tKeyInputCode+tNewTypeBrowse+tApiCode).val(tParamFirst);	
				$('#'+tKeyInputName+tNewTypeBrowse+tApiCode).val(tParamSecond);
			}
		}else{
			var tTypeBrowseFrm 	= 'Frm';
			var tTypeBrowseTo 	= 'To';
			$('#'+tKeyInputCode+tTypeBrowseFrm+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseFrm+tApiCode).val('');
			$('#'+tKeyInputCode+tTypeBrowseTo+tApiCode).val('');	
			$('#'+tKeyInputName+tTypeBrowseTo+tApiCode).val('');
		}
	}

	//////////////////////////////////////////  กดปุ่มยืนยัน /////////////////////////////////////////////
	$('#obtInterfaceExportConfirm').off('click').on('click',function(){
		var nImpportFile = $('.progress-bar-chekbox:checked').length;
        if(nImpportFile > 0){
			var nStatusCallRabbitMQ = 0;
			// $('.progress-bar-chekbox:checked').each(function(i, obj) {
			// 	var nDatePicker = $(this).parent().parent().find('.xCNDatePicker').length;
			// 	if(nDatePicker > 0){
			// 		for(i=0; i<nDatePicker; i++){
			// 			var nValue = $(this).parent().parent().find('.xCNDatePicker:eq('+i+')').val();
			// 			if(nValue == '' || nValue == null){
			// 				$('#odvInterfaceExportDateIsNull').modal('show');
			// 				var oElement = $(this).parent().parent().find('.xCNDatePicker:eq('+i+')');
			// 				$('#obtIFXModalMsgConfirmIsNull').off('click').on('click',function(){
			// 					$(oElement).focus();
			// 				});
			// 				nStatusCallRabbitMQ = 1;
			// 				return;
			// 			}
			// 		}
			// 	}
			// });

			if(nStatusCallRabbitMQ == 0){
				// JCNxOpenLoading(); 
				JSxIFXCallRabbitMQ();
			}
        }else{
            $('#odvInterfaceExportIsNull').modal('show');
        }
	});

	//เมื่อกดปุ่มยืนยันให้วิ่งไปที่หน้า ประวัตินำเข้า-ส่งออก
	$('#obtIFXModalMsgConfirm').off('click');
	$('#obtIFXModalMsgConfirm').on('click',function(){
		setTimeout(function(){
			$.ajax({
				type    : "POST",
				url     : "interfacehistory/0/0",
				data    : {},
				cache   : false,
				Timeout : 0,
				success: function(tResult){
					$('.odvMainContent').html(tResult);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
		}, 100);
	});

	//กดปุ่มปิดใน modal
	$('#obtIFXModalMsgCancel').off('click');
	$('#obtIFXModalMsgCancel').on('click',function(){
		$('#obtInterfaceExportConfirm').attr('disabled', false);
	});

	//ปุ่มเช็คทั้งหมด
	$('#ocmINMExportAll').change(function(){
		var bStatus = $(this).is(":checked") ? true : false;
		if(bStatus == false){
			$('.xCNCheckBoxExport').prop("checked",false);
		}else{
			$('.xCNCheckBoxExport').prop("checked",true)
		}
	});

	//โหลด กลับมาหน้าจอใหม่
    function JSvInterfaceExportCallPage(){
        $.ajax({
            type    : "GET",
            url     : "interfaceexport/0/0",
            data    : {},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $('.odvMainContent').html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

</script>