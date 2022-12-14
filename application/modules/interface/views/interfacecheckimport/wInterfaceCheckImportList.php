<div id="odvCpnMainMenu" class="main-menu clearfix">
	<div class="xCNMrgNavMenu">
		<div class="row xCNavRow" style="width:inherit;">
			<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
				<ol id="oliMenuNav" class="breadcrumb">
					<?php FCNxHADDfavorite('masChkImport/0/0');?>
					<li id="oliInterfaceImportTitle" class="xCNLinkClick" style="cursor:pointer" onclick="JSvInterfaceImportCallPage()">
						<?=language('interface/interfacechkimport/interfacechkimport','tInterfaceHead') ?>
					</li>
					<li id="oliInterfaceImportCostCenter" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadCost')?></a></li>
					<li id="oliInterfaceImportInterBa" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadInterBA')?></a></li>
					<li id="oliInterfaceImportSale" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterHeadSaleStaff')?></a></li>
					<li id="oliInterfaceImportCustomer" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadCustomer')?></a></li>
					<li id="oliInterfaceImportRole" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadRole')?></a></li>
					<li id="oliInterfaceImportSaleforStore" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadSale')?></a></li>
					<li id="oliInterfaceImportCar" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadCar')?></a></li>
					<li id="oliInterfaceImportProducts" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadProducts')?></a></li>
				</ol>
			</div>
			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-right p-r-0">
				<button onclick="JSvInterfaceImportCallPage()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
			</div>
		</div>
	</div>
</div>
<div class="main-content">
	<div class="panel panel-headline">
		<div class="panel-heading">
			<div class="row">
			 <div class="col-lg-3 col-md-3 col-xs-6 col-sm-6" style="padding-left:35px" id="typeproduct">
					<div class="form-group">
					<label class="xCNLabelFrm" ><?=language('product/product/product','tPdtSreachTypeName');?></label>
						<select class="form-control selectpicker" id="ocmchkImporproduct" name="ocmchkImporproduct" maxlength="1" >
							<option class="" value="1"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProducts')?></option>
							<option class="" value="2"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProdutGroup')?></option>
							<option class="" value="3"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProductDept')?></option>
							<option class="" value="4"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceUnitSmall')?></option>
							<option class="" value="5"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProductComponent')?></option>
							<option class="" value="6"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProductPrice2')?></option>
						</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6 col-sm-6" style="padding-left:35px" >
						<div class="form-group">
						<label class="xCNLabelFrm"><?php echo language('common/main/main','tSearch')?></label>
							<div class="input-group">
								<input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchChk" name="oetSearchChk" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
								<span class="input-group-btn">
								<button id="oimSearchChk" class="btn xCNBtnSearch" type="button">
									<img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
								</button>
								</span>
							</div>
						</div>
				</div>
			 </div>
			<div class="row">
				<div class="col-xs-12 cols-sm-12 col-md-12 col-lg-12">
					<div id="odvMainContent"> </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('application/modules/interface/assets/src/interfacecheckimport/jInterfaceCheckImport.js')?>"></script>
<script>
	$('document').ready(function() {
		$('.selectpicker').selectpicker();
		$("#oliInterfaceImportCostCenter").hide();
		$("#oliInterfaceImportInterBa").hide();
		$("#oliInterfaceImportSale").hide();
		$("#oliInterfaceImportCustomer").hide();
		$("#oliInterfaceImportRole").hide();
		$("#oliInterfaceImportSaleforStore").hide();
		$("#oliInterfaceImportCar").hide();
		$("#oliInterfaceImportProducts").hide();
		$("#ocmchkImporproduct").hide();
		$("#typeproduct").hide();
		switch ("<?php echo $nCode ?>") {
			case "00020": //?????????????????? CostCenterToProfiCenter (Excel)
				JSvInterfaceImportCostCenter(1);
				$("#oliInterfaceImportCostCenter").show();
				break;
			case "00021": //?????????????????? InterBA (Excel)
				JSvInterfaceImportInterBa(1);
				$("#oliInterfaceImportInterBa").show();
				break;
			case "00022": //??????????????????????????????????????????????????????????????? (SAP Master)
				JSvInterfaceImportSale(1);
				$("#oliInterfaceImportSale").show();
				break;
			case "00023": //???????????????????????????????????? (SAP Master)?????????????????????????????????????????? (SAP)
				JSvInterfaceImportCustomer(1);
				$("#oliInterfaceImportCustomer").show();
				break;
			case "00024": //?????????????????? LocationID ????????? Role(SAP Master)
				JSvInterfaceImportRole(1);
				$("#oliInterfaceImportRole").show();
				break;
			case "00025": //?????????????????? Sale?????????????????????????????????(SAP Master)
				JSvInterfaceImportSaleforStore(1);
				$("#oliInterfaceImportSaleforStore").show();
				break;
			case "00026": //????????????????????????(SAP Master)
				JSvInterfaceImportCar(1);
				$("#oliInterfaceImportCar").show();
				break;
			case "00027": //????????????????????????????????????(Excel)
				JSvInterfaceImportProducts(1);
				$("#oliInterfaceImportProducts").show();
				break;
			default:
		}


});

	function JSvInterfaceImportCostCenter(nPageCurrent){  //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
		var tSearchAll = $('#oetSearchChk').val();
        $.ajax({
            type    : "POST",
            url     : "maspageChkImportCostCenterToProfiCenter", // ?????????????????????????????? CostCenterToProfiCenter
            data    : {
				      nPageCurrent:nPageCurrent, // ????????????????????????
					  tSearchAll: tSearchAll
			},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $('#odvMainContent').html(tResult); //??????????????????
				JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

	function JSvInterfaceImportInterBa(nPageCurrent){  //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
			var tSearchAll = $('#oetSearchChk').val();
			$.ajax({
				type    : "POST",
				url     : "maspageChkImportInterBA",  // ?????????????????????????????? InterDB
				data    : {
						nPageCurrent:nPageCurrent  , // ????????????????????????
						tSearchAll: tSearchAll
				},
				cache   : false,
				timeout : 5000,
				success : function (tResult) {
					$('#odvMainContent').html(tResult); //??????????????????
					JCNxCloseLoading();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
		}

	function JSvInterfaceImportSale(nPageCurrent){ //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
		//s	var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
			var tSearchAll = $('#oetSearchChk').val();
			$.ajax({
				type    : "POST",
				url     : "maspageChkImportSaleStaff",   // ?????????????????????????????? SaleStaff
				data    : {
							nPageCurrent:nPageCurrent, // ????????????????????????
							tSearchAll: tSearchAll
				},
				cache   : false,
				timeout : 5000,
				success : function (tResult) {
					$('#odvMainContent').html(tResult); //??????????????????
					JCNxCloseLoading();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
	}

	function JSvInterfaceImportCustomer(nPageCurrent){ //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
		//s	var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
			var tSearchAll = $('#oetSearchChk').val();
			$.ajax({
				type    : "POST",
				url     : "maspageChkImportCustomer",
				data    : {
						nPageCurrent:nPageCurrent, // ????????????????????????
						tSearchAll: tSearchAll // ????????????????????????
				},
				cache   : false,
				timeout : 5000,
				success : function (tResult) {
					$('#odvMainContent').html(tResult); //??????????????????
					JCNxCloseLoading();

				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
    }

	function JSvInterfaceImportRole(nPageCurrent){ //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
	//s	var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
			var tSearchAll = $('#oetSearchChk').val();
			$.ajax({
				type    : "POST",
				url     : "maspageChkImportRole", // ?????????????????????????????? Role
				data    : {
						nPageCurrent:nPageCurrent, // ????????????????????????
						tSearchAll: tSearchAll // ????????????????????????
				},
				cache   : false,
				timeout : 5000,
				success : function (tResult) {
					$('#odvMainContent').html(tResult); //??????????????????
					JCNxCloseLoading();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
    }

	function JSvInterfaceImportSaleforStore(nPageCurrent){ //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
	//s	var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
		var tSearchAll = $('#oetSearchChk').val();
        $.ajax({
            type    : "POST",
            url     : "maspageChkImportSaleforStore", // ?????????????????????????????? SaleforStore
            data    : {
				      nPageCurrent:nPageCurrent	,    	// ????????????????????????
					  tSearchAll: tSearchAll // ????????????????????????
			},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $('#odvMainContent').html(tResult);  //??????????????????
				JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

	function JSvInterfaceImportCar(nPageCurrent){ //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
	//s	var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
			var tSearchAll = $('#oetSearchChk').val();
			$.ajax({
				type    : "POST",
				url     : "maspageChkImportCar",   // ?????????????????????????????? InterDB
				data    : {
						nPageCurrent:nPageCurrent,  // ????????????????????????
						tSearchAll: tSearchAll // ????????????????????????
				},
				cache   : false,
				timeout : 5000,
				success : function (tResult) {
					$('#odvMainContent').html(tResult); //??????????????????
					JCNxCloseLoading();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
		}

	function JSvInterfaceImportCar(nPageCurrent){ //  ????????? paramiter ??????????????? JSvCHkClickPage ????????? nPageCurrent ????????????????????????????????????
	//s	var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
			var tSearchAll = $('#oetSearchChk').val();
			$.ajax({
				type    : "POST",
				url     : "maspageChkImportCar",   // ?????????????????????????????? InterDB
				data    : {
						nPageCurrent:nPageCurrent,  // ????????????????????????
						tSearchAll: tSearchAll // ????????????????????????
				},
				cache   : false,
				timeout : 5000,
				success : function (tResult) {
					$('#odvMainContent').html(tResult); //??????????????????
					JCNxCloseLoading();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
	}

	function JSvInterfaceImportProducts(nPageCurrent){  //  ????????? paramiter  nPageCurrent ????????????????????????????????????
		var tSearchAll = $('#oetSearchChk').val();
		var tType      = $('#ocmchkImporproduct').val();
		switch (tType) {
                    case "1": //?????????????????? CostCenterToProfiCenter (Excel)
                        var tRoute  = 'maspageChkImportProducts';
						$("#ocmchkImporproduct").show();
						$("#typeproduct").show();
                        break;
                    case "2": //?????????????????? InterBA (Excel)
                        var tRoute  = 'maspageChkImportProductGroup';
						$("#ocmchkImporproduct").show();
						$("#typeproduct").show();
                        break;
                    case "3": //??????????????????????????????????????????????????????????????? (SAP Master)
                        var tRoute  = 'maspageChkImportProductDept';
						$("#ocmchkImporproduct").show();
						$("#typeproduct").show();
                        break;
                    case "4": //???????????????????????????????????? (SAP Master)?????????????????????????????????????????? (SAP)
                        var tRoute  = 'maspageChkImportUnitSmall';
						$("#ocmchkImporproduct").show();
						$("#typeproduct").show();
                        break;
                    case "5": //?????????????????? LocationID ????????? Role(SAP Master)
                        var tRoute  = 'maspageChkImportProductComponent';
						$("#ocmchkImporproduct").show();
						$("#typeproduct").show();
                        break;
                    case "6": //?????????????????? Sale?????????????????????????????????(SAP Master)
                        var tRoute   = 'maspageChkImportProductPrice';
						$("#ocmchkImporproduct").show();
						$("#typeproduct").show();
                        break;
                    default: var tRoute  = 'maspageChkImportProducts';
						$("#ocmchkImporproduct").show();
						$("#typeproduct").show();
                    }
        $.ajax({
            type    : "POST",
            url     : tRoute,  // ?????????????????????????????? Products
            data    : {
				      nPageCurrent:nPageCurrent,  // ????????????????????????
					  tSearchAll: tSearchAll,
			},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $('#odvMainContent').html(tResult); //??????????????????
				JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

	// Even ?????????????????????????????????????????????????????? Select Box
	$('#ocmchkImporproduct').change(function(){
		$.ajax({
			type    : "POST",
			url     : "maspageChkImportSelectPage", // ?????????????????????????????? Controllers Fuction  FSvCChkImportSelectPage
			data    :  {
						tproduct : $('#ocmchkImporproduct').val()
						},
			cache   : false,
			timeout : 5000,
			success : function (tResult) {
				$('#odvMainContent').html(tResult);
			},
			error   : function (jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });
	$('#oimSearchChk').click(function(){
		JCNxOpenLoading();
		switch ("<?php echo $nCode ?>") {
			case "00020": //?????????????????? CostCenterToProfiCenter (Excel)
				JSvInterfaceImportCostCenter(1);
				$("#oliInterfaceImportCostCenter").show();
				break;
			case "00021": //?????????????????? InterBA (Excel)
				JSvInterfaceImportInterBa(1);
				$("#oliInterfaceImportInterBa").show();
				break;
			case "00022": //??????????????????????????????????????????????????????????????? (SAP Master)
				JSvInterfaceImportSale(1);
				$("#oliInterfaceImportSale").show();
				break;
			case "00023": //???????????????????????????????????? (SAP Master)?????????????????????????????????????????? (SAP)
				JSvInterfaceImportCustomer(1);
				$("#oliInterfaceImportCustomer").show();
				break;
			case "00024": //?????????????????? LocationID ????????? Role(SAP Master)
				JSvInterfaceImportRole(1);
				$("#oliInterfaceImportRole").show();
				break;
			case "00025": //?????????????????? Sale?????????????????????????????????(SAP Master)
				JSvInterfaceImportSaleforStore(1);
				$("#oliInterfaceImportSaleforStore").show();
				break;
			case "00026": //????????????????????????(SAP Master)
				JSvInterfaceImportCar(1);
				$("#oliInterfaceImportCar").show();
				break;
			case "00027": //????????????????????????????????????(Excel)
				JSvInterfaceImportProducts(1);
				$("#oliInterfaceImportProducts").show();
				break;
			default:
		}
	});
	$('#oetSearchChk').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
			switch ("<?php echo $nCode ?>") {
			case "00020": //?????????????????? CostCenterToProfiCenter (Excel)
				JSvInterfaceImportCostCenter(1);
				$("#oliInterfaceImportCostCenter").show();
				break;
			case "00021": //?????????????????? InterBA (Excel)
				JSvInterfaceImportInterBa(1);
				$("#oliInterfaceImportInterBa").show();
				break;
			case "00022": //??????????????????????????????????????????????????????????????? (SAP Master)
				JSvInterfaceImportSale(1);
				$("#oliInterfaceImportSale").show();
				break;
			case "00023": //???????????????????????????????????? (SAP Master)?????????????????????????????????????????? (SAP)
				JSvInterfaceImportCustomer(1);
				$("#oliInterfaceImportCustomer").show();
				break;
			case "00024": //?????????????????? LocationID ????????? Role(SAP Master)
				JSvInterfaceImportRole(1);
				$("#oliInterfaceImportRole").show();
				break;
			case "00025": //?????????????????? Sale?????????????????????????????????(SAP Master)
				JSvInterfaceImportSaleforStore(1);
				$("#oliInterfaceImportSaleforStore").show();
				break;
			case "00026": //????????????????????????(SAP Master)
				JSvInterfaceImportCar(1);
				$("#oliInterfaceImportCar").show();
				break;
			case "00027": //????????????????????????????????????(Excel)
				JSvInterfaceImportProducts(1);
				$("#oliInterfaceImportProducts").show();
				break;
			default:
		}
		}
	});
</script>
