<input type="text" class="form-control xCNHide" id="oetAPIStaApiTxnType" name="oetAPIStaApiTxnType" value="<?=$tStaApiTxnType?>">
<?php  $tSuccessText      = language('interface/conSettingGenaral/consettinggenaral' ,'tGeneralSuccess'); ?>
<?php if($tStaApiTxnType == '1'): ?>
<div class="row">
	<div class="col-xs-8 col-md-5 col-lg-4">
		<div class="form-group"> <!-- เปลี่ยน From Imput Class -->
			<div class="input-group">
				<input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" name="oetSearchAll" placeholder="<?php echo language('authen/department/department','กรอกคำค้นหา')?>">
				<span class="input-group-btn">
					<button id="oimSearch" class="btn xCNBtnSearch" type="button">
						<img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
					</button>
				</span>
			</div>
		</div>
	</div>
	<div class="col-xs-8 col-md-8 col-lg-8 text-right">
		<button type="button" onclick="JSxConnsetGenCancel();" id="obtConnSetGenCancel" class="btn xCNBTNDefult xCNBTNDefult2Btn" style="margin-left: 5px;">
			<?php echo language('common/main/main', 'tCancel')?>
		</button>
		<button type="submit" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" style="margin-left: 5px;" id="obtConnSetGenSave" onclick="JSxConnSetGenSaveAddEdit('consetgenEventedit','<?php echo $tSuccessText ?>')"> 
			<?php echo  language('common/main/main', 'tSave')?>
		</button>
	</div>	
</div>
<?php endif; ?>

<!--content-->
<div id="odvContentConnSetGenDataTable"></div>

<!-- ============================================ กดยกเลิก =============================================== -->
<div class="modal fade" id="odvModalConCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tCancel'); ?></h5>
            </div>
            <div class="modal-body">
                <p><?=language('interface/conSettingGenaral/consettinggenaral', 'tTextModalCancel'); ?></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxConSetGenModalCancel()" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ บันทึกสินค้า =============================================== -->
<div id="odvModalConSuccess" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalWarning') ?></label>
            </div>
            <div class="modal-body">
                <p>
					<?=language('interface/conSettingGenaral/consettinggenaral' ,'tGeneralSuccess');?><br>
					<?=language('interface/conSettingGenaral/consettinggenaral' ,'tGeneralSuccess2');?>
				</p>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn xCNBTNPrimery" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalConfirm');?>
				</button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url('application/modules/interface/assets/src/connsetgenaral/jConnsetGen.js'); ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>
	$('#oimSearch').click(function(){
		JCNxOpenLoading();
		JSvConnsetGenList();
	});

	$('#oetSearchAll').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
			JSvConnsetGenList();
		}
	});

</script>
