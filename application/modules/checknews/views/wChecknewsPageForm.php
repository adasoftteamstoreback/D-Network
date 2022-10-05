<style>
	.xFontWeightBold{
		font-weight: bold;
	}

	.xCNBtnBuyLicence{
		height: 150px;
		width: 150px;
		margin-right: 10px;
		border-color: #1866ae;
		color : #1866ae;
		font-size: 20px;
		font-family: THSarabunNew-Bold;
		margin-top: 5px;
	}

	.xCNBtnBuyLicence:hover{
		background-color: #1866ae;
		color : #FFF;
	}

	img.xCNImageIconLast {
		display: none;  
	}
	.xCNBtnBuyLicence:hover img.xCNImageIconFisrt {
		display: none;  
	}

	.xCNBtnBuyLicence:hover .xCNImageIconLast {
		display: block;  
	}

	.xCNImageInformationBuy{
		width: 30px; 
		display: block; 
		margin: 0px auto; 
		margin-bottom: 10px;
	}

	@media (min-width:320px)  { .xCNDisplayButton{ padding: 15px; } }
    @media (min-width:1025px) { .xCNDisplayButton{ float: right; } }
    @media (min-width:1281px) { .xCNDisplayButton{ float: right; } }
	.xPadding30 {
    padding-left: 30px;
    padding-right: 30px;
    padding-bottom: 30px;
	}
	.xPaddingTop15 {
		padding-top: 15px;
	}
	.xPaddingTop25 {
		padding-top: 25px;
	}
</style>
<?php
	if($this->session->userdata('tSesUsrLevel')!='HQ'){
		$tDisplayNone ="none";
	}else{
		$tDisplayNone = '';
	}
?>
<div class="main-content">

	<!--ส่วนของรายละเอียดด้านบน-->
	<div class="panel panel-headline">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-body">
					
		
					
					<!--Table ข้อมูลส่วนตัว -->
					<div class="row" >
	

			
				


					<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"  >
								<!-- วันที่ในการออกเอกสาร -->
								<div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('checkdocument/checkdocument', 'tChkDocDateFrom'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetMNTDocDateFrom" name="oetMNTDocDateFrom" value="" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtMNTDocDateFrom" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"  >
								<!-- วันที่ในการออกเอกสาร -->
								<div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('checkdocument/checkdocument', 'tChkDocDateTo'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetMNTDocDateTo" name="oetMNTDocDateTo" value="" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtMNTDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
						</div>
				
						
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 xPaddingTop25" >
							<button  class="btn xCNBTNDefult xCNBTNDefult2Btn" style="width:30%" type="button" onclick="JSxMNTClearConditionAll()"> <?= language('checknews/checknews','tChkDocBtnClear')?></button>
							<button id="obtMainAdjustProductFilter" type="button"  style="width:30%" class="btn btn xCNBTNPrimery xCNBTNPrimery2Btn"> <?= language('checknews/checknews','tChkDocBtnSubmit')?></button>
						</div>
				

					</div>




					<div class="row xPaddingTop25" id="odvCheckdocSumary"></div>

					<div class="row xPaddingTop25" id="odvCheckdocDataTable"></div>

				</div>
			</div>
		</div>
	</div>



</div>

<?php include('script/jCheknewsPageFrom.php');?>