<input id="oetInterfaceImportStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetInterfaceImportCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div id="odvCpnMainMenu" class="main-menu clearfix">
	<div class="xCNMrgNavMenu">
		<div class="row xCNavRow" style="width:inherit;">
			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
				<ol id="oliMenuNav" class="breadcrumb">
					<?php FCNxHADDfavorite('interfaceimport/0/0');?> 
					<li id="oliInterfaceImportTitle" class="xCNLinkClick" style="cursor:pointer" onclick="JSvInterfaceImportCallPage('')">
						<?=language('interface/interfaceimport/interfaceimport','tIFSTitle') ?>
					</li>
				</ol>
			</div>
			
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
				<div id="odvBtnCmpEditInfo">
					<button id="obtInterfaceImportConfirm"  class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> 
						<?=language('interface/interfaceimport/interfaceimport','tIFSTConfirm')  ?>
					</button> 
				</div>
			</div>
		</div>
	</div>
</div>

<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmInterfaceImport">
	<div class="main-content">
		<div class="panel panel-headline">
			<input type="hidden" name="tUserCode" id="tUserCode" value="<?=$this->session->userdata('tSesUserCode')?>">
			<div class="row">
				<div class="col-md-12">
					<div class="panel-body">
						<div class="table-responsive" style="padding:20px">
							<table id="otbInterfaceImport" class="table table-striped"> 
								<thead>
									<tr>
										<th width="7%" nowrap class="text-center xCNTextBold"><?php echo language('interface/interfaceimport/interfaceimport','tIFSID'); ?></th>
										<th width="7%" nowrap class="text-center xCNTextBold">
											<input type="checkbox" id="ocmINMImportAll" value="1" checked >
										</th>
										<th nowrap class="text-center xCNTextBold"><?php echo language('interface/interfaceimport/interfaceimport','tIFSList'); ?></th>
										<th nowrap class="text-center xCNTextBold"><?php echo language('interface/interfaceimport/interfaceimport','tImpotLastDate'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($aDataMasterImport)){
										foreach($aDataMasterImport AS $nK => $aData){ ?>
										<tr>
											<td align="center"><?=($nK+1)?></td>
											<td align="center">
												<input type="checkbox" class="progress-bar-chekbox xCNCheckBoxImport" name="ocmINMImport[<?=$aData['FTApiCode']?>]" value="<?=$aData['FTApiCode']?>" checked idpgb="xWINM<?=$aData['FTApiCode']?>TextDisplay"  >
											</td>
											<td><?=$aData['FTApiName']?></td>
											<td class="text-center"><?=($aData['FDLogCreate'] == '') ? '-' : $aData['FDLogCreate']?></td>
										</tr>
									<?php } } ?>
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
<div class="modal fade" id="odvInterfaceImportSuccess">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('interface/interfaceimport/interfaceimport','tStatusProcess'); ?></h5>
            </div>
            <div class="modal-body">
                <p><?=language('interface/interfaceimport/interfaceimport','tContentProcess'); ?></p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" type="button"  id="obtIFIModalMsgConfirm" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
				<button type="button" id="obtIFIModalMsgCancel" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url('application/modules/interface/assets/src/interfaceimport/jInterfaceImport.js')?>"></script>
<script>
	//กดปุ่มยินยันใน modal
	$('#obtIFIModalMsgConfirm').off('click');
	$('#obtIFIModalMsgConfirm').on('click',function(){
		// ใส่ timeout ป้องกัน modal-backdrop
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
	$('#obtIFIModalMsgCancel').off('click');
	$('#obtIFIModalMsgCancel').on('click',function(){
		$('#obtInterfaceImportConfirm').attr('disabled', false);
	});

	//ปุ่มเช็คทั้งหมด
	$('#ocmINMImportAll').change(function(){
		var bStatus = $(this).is(":checked") ? true : false;
		if(bStatus == false){
			$('.xCNCheckBoxImport').attr("checked",false);
		}else{
			$('.xCNCheckBoxImport').prop("checked",true)
		}
	});

	//โหลด กลับมาหน้าจอใหม่
    function JSvInterfaceImportCallPage(){
        $.ajax({
            type    : "GET",
            url     : "interfaceimport/0/0",
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