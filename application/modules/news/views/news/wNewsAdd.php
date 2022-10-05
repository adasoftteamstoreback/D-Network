<?php
    if(isset($aNewData['raItems'])){
        $tRoute             = "newsEventEdit";
        $tNewCode           = $aNewData['raItems']['rtNewCode'];
        $tNewName           = $aNewData['raItems']['rtNewName'];
        $tNewRmk            = $aNewData['raItems']['rtNewRmk'];
        $tNewRefUrl          = $aNewData['raItems']['rtNewRefUrl'];
        $rnNewToType          = $aNewData['raItems']['rnNewToType'];
        $tUsrCode           = $aNewData['raItems']['rtUsrCode'];
        $tUsrName           = $aNewData['raItems']['rtUsrName'];
        $tBchCode           = $aNewData['raItems']['rtBchCode'];
        $tBchCodeIn       = "";
        $tBchNameIn       = "";
        $tAgnCodeIn       = "";
        $tAgnNameIn       = "";
        if(isset($aNewBchIn['raItems']) && !empty($aNewBchIn['raItems'])){

            foreach ($aNewBchIn['raItems'] AS $key => $aValue){
                if($tBchCodeIn == ""){
                    $tSymbol = "";
                }else{
                    $tSymbol = ",";
                }
                if($aValue['FTNewBchTo'] != ""){
                    if(strpos($tBchCodeIn, $aValue['FTNewBchTo']) !== 0){ // เช็คค่าซ้ำ
                        $tBchCodeIn .= $tSymbol.$aValue['FTNewBchTo'];
                        $tBchNameIn .= $tSymbol.$aValue['FTBchName'];
                    }
                }


                if($tAgnCodeIn == ""){
                    $tSymbol = "";
                }else{
                    $tSymbol = ",";
                }
                if($aValue['FTNewAgnTo'] != ""){
                    if(strpos($tAgnCodeIn, $aValue['FTNewAgnTo']) !== 0){ // เช็คค่าซ้ำ
                        $tAgnCodeIn .= $tSymbol.$aValue['FTNewAgnTo'];
                        $tAgnNameIn .= $tSymbol.$aValue['FTAgnName'];
                    }
                }
          
            }
        }


        $tBchCodeEx       = "";
        $tBchNameEx       = "";
        $tAgnCodeEx       = "";
        $tAgnNameEx       = "";
        if(isset($aNewBchEx['raItems']) && !empty($aNewBchEx['raItems'])){

            foreach ($aNewBchEx['raItems'] AS $key => $aValue){
                if($tBchCodeEx == ""){
                    $tSymbol = "";
                }else{
                    $tSymbol = ",";
                }
                if($aValue['FTNewBchTo'] != ""){
                    if(strpos($tBchCodeEx, $aValue['FTNewBchTo']) !== 0){ // เช็คค่าซ้ำ
                        $tBchCodeEx .= $tSymbol.$aValue['FTNewBchTo'];
                        $tBchNameEx .= $tSymbol.$aValue['FTBchName'];
                    }
                }


                if($tAgnCodeEx == ""){
                    $tSymbol = "";
                }else{
                    $tSymbol = ",";
                }
                if($aValue['FTNewAgnTo'] != ""){
                    if(strpos($tAgnCodeEx, $aValue['FTNewAgnTo']) !== 0){ // เช็คค่าซ้ำ
                        $tAgnCodeEx .= $tSymbol.$aValue['FTNewAgnTo'];
                        $tAgnNameEx .= $tSymbol.$aValue['FTAgnName'];
                    }
                }
          
            }
        }

        $nStaUploadFile         = 2;
    }else{
        $tRoute             = "newsEventAdd";
        $tNewCode           = "";
        $tNewName           = "";
        $tNewRmk            = "";
        $tNewRefUrl          = "";
        $rnNewToType        = 1;
        $tUsrCode           = $this->session->userdata('tSesUserCode');
        $tUsrName          = $this->session->userdata('tSesUsrUsername');
        $tBchCode         = $this->session->userdata('tSesUsrBchCodeDefault');
        $tBchCodeIn       = "";
        $tBchNameIn       = "";
        $tAgnCodeIn       = "";
        $tAgnNameIn       = "";
        $tBchCodeEx       = "";
        $tBchNameEx       = "";
        $tAgnCodeEx       = "";
        $tAgnNameEx       = "";
        $nStaUploadFile         = 1;
    }
?>
<div class="panel-body" style="padding-top:20px !important;">

    <form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddNew">
        <button style="display:none" type="submit" id="obtSubmitNew" onclick="JSxSetStatusClickNewSubmit('<?= $tRoute?>')"></button>
        <input type="hidden" id="ohdNewRoute" value="<?php echo $tRoute; ?>">
        <input type="hidden" id="ohdNewCode"   name="ohdNewCode" value="<?=$tNewCode?>">
        <input type="hidden" id="ohdBchCode"   name="ohdBchCode" value="<?=$tBchCode?>">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-lg-8">

				<div class="form-group ">
					<label class="xCNLabelFrm"></span><?php echo language('news/news/news','tNewUserCreate');?></label>	
					<input class="form-control xCNHide" type="text" id="oetNewUsrCode" name="oetNewUsrCode" maxlength="5" value="<?=$tUsrCode?>">
						<input type="text" class="form-control xWPointerEventNone" 
							id="oetNewUsrName" 
							name="oetNewUsrName" 
							maxlength="100" 
                        placeholder ="<?php echo language('news/news/news','tNewUserCreate');?>"
							value="<?=$tUsrName?>" readonly>	
               </div>	
     
            <div class="row">
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <div class='form-group'>
                                <label class="xCNLabelFrm"><?php echo language('news/news/news','tNewUserSendTo')?></label>
                            
                                <select class="form-control" name="ocmNewToType" id="ocmNewToType">
                                <option value="1" <?php if($rnNewToType==1){ echo 'selected'; }?>><?php echo language('news/news/news','tNewSendBch')?></optoion>
                                <option value="2" <?php if($rnNewToType==2){ echo 'selected'; }?>><?php echo language('news/news/news','tNewSendAgn')?></optoion>
                                </select>
                                
                        </div>
                </div>
                <div class='col-xs-9 col-md-9 col-lg-9'>
                        <div class="form-group odvToBranch" style="margin-bottom: 0px;" >
                                <label class="xCNLabelFrm"><?php echo language('news/news/news','tNewSendBch')?></label>
                                <div class="input-group">
                                    <input  type="text" class="form-control xCNHide" id="oetBranchCodeIn" name="oetBranchCodeIn" 
                                            value="<?=$tBchCodeIn?>" 
                                            readonly
                                    >
                                    <input type="text" class="form-control" id="oetBranchNameIn" name="oetBranchNameIn" value="<?=$tBchNameIn?>" placeholder="<?php echo language('news/news/news','tNewSendAll')?>"  readonly>
                                    <span class="input-group-btn">
                                        <button id="oimBrowseBranchIn" type="button" class="btn xCNBtnBrowseAddOn" >
                                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="odvToBranch" style="white-space:nowrap;width:100%;overflow-x:auto;margin-bottom: 10px;"> 
                                <div id="odvBranchShowIn" style="margin-bottom: 10px;margin-top: 10px;">
                                        <?php if(isset($aNewBchIn['raItems']) && !empty($aNewBchIn['raItems'])){ ?>
                                                <?php 
                                                    $tBchNameIn1 = "";
                                                    foreach ($aNewBchIn['raItems'] AS $key => $aValue) { 
                                                        
                                                        if( !empty($aValue['FTBchName']) && strpos($tBchNameIn1, $aValue['FTBchName']) !== 0 ){ // เช็คค่าซ้ำ 
                                                            $tBchNameIn1 .= $aValue['FTBchName'];
                                                ?>
                                                            <span class="label label-info m-r-5"><?=$aValue['FTBchName'];?></span>
                                                <?php
                                                        } 
                                                    } 
                                                ?>
                                            <?php }else{ ?>
                                                <?php if(!empty($tBchNameIn1)){ ?>
                                                    <?php foreach(explode(",",$tBchNameIn1) AS $key => $aValue){ ?>
                                                        <span class="label label-info m-r-5"><?=$aValue;?></span>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                </div>
                            </div>

                            <div class="form-group odvToAgency" style="margin-bottom: 0px;" >
                                    <label class="xCNLabelFrm"><?php echo language('news/news/news','tNewSendAgn')?></label>
                                    <div class="input-group">
                                        <input  type="text" class="form-control xCNHide" id="oetAgencyCodeIn" name="oetAgencyCodeIn" 
                                                value="<?=$tAgnCodeIn?>" 
                                                readonly
                                        >
                                        <input type="text" class="form-control" id="oetAgencyNameIn" name="oetAgencyNameIn" value="<?=$tAgnNameIn?>" placeholder="<?php echo language('news/news/news','tNewSendAll')?>"  readonly>
                                        <span class="input-group-btn">
                                            <button id="oimBrowseAgencyIn" type="button" class="btn xCNBtnBrowseAddOn" >
                                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                            </div>

                            <div class="odvToAgency" style="white-space:nowrap;width:100%;overflow-x:auto;margin-bottom: 10px;"> 
                                <div id="odvAgencyShowIn" style="margin-bottom: 10px;margin-top: 10px;">
                                <?php if(isset($aNewBchIn['raItems']) && !empty($aNewBchIn['raItems'])){ ?>
                                                <?php 
                                                    $tAgnNameIn1 = "";
                                                    foreach ($aNewBchIn['raItems'] AS $key => $aValue) { 
                                                        
                                                        if( !empty($aValue['FTAgnName']) && strpos($tAgnNameIn1, $aValue['FTAgnName']) !== 0 ){ // เช็คค่าซ้ำ 
                                                            $tAgnNameIn1 .= $aValue['FTAgnName'];
                                                ?>
                                                            <span class="label label-info m-r-5"><?=$aValue['FTAgnName'];?></span>
                                                <?php
                                                        } 
                                                    } 
                                                ?>
                                            <?php }else{ ?>
                                                <?php if(!empty($tAgnNameIn1)){ ?>
                                                    <?php foreach(explode(",",$tAgnNameIn1) AS $key => $aValue){ ?>
                                                        <span class="label label-info m-r-5"><?=$aValue;?></span>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                </div>
                            </div>
                </div>

                
            </div>



       
            <div class="form-group odvToBranch" style="margin-bottom: 0px;">
                    <label class="xCNLabelFrm"><?php echo language('news/news/news','tNewSendExclude').language('news/news/news','tNewSendBch')?></label>
                    <div class="input-group">
                        <input  type="text" class="form-control xCNHide" id="oetBranchCodeEx" name="oetBranchCodeEx" 
                                value="<?=$tBchCodeEx?>" 
                                readonly
                        >
                        <input type="text" class="form-control" id="oetBranchNameEx" name="oetBranchNameEx" value="<?=$tBchNameEx?>" placeholder="<?php echo language('news/news/news','tNewSendNoExclude')?>"  readonly>
                        <span class="input-group-btn">
                            <button id="oimBrowseBranchEx" type="button" class="btn xCNBtnBrowseAddOn" >
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                </div>
            </div>

            <div class="odvToBranch"  style="white-space:nowrap;width:100%;overflow-x:auto;margin-bottom: 10px;"> 
                <div id="odvBranchShowEx" style="margin-bottom: 10px;margin-top: 10px;">
                                <?php if(isset($aNewBchEx['raItems']) && !empty($aNewBchEx['raItems'])){ ?>
                                                <?php 
                                                    $tBchNameEx1 = "";
                                                    foreach ($aNewBchEx['raItems'] AS $key => $aValue) { 
                                                        
                                                        if( !empty($aValue['FTBchName']) && strpos($tBchNameEx1, $aValue['FTBchName']) !== 0 ){ // เช็คค่าซ้ำ 
                                                            $tBchNameEx1 .= $aValue['FTBchName'];
                                                ?>
                                                            <span class="label label-info m-r-5"><?=$aValue['FTBchName'];?></span>
                                                <?php
                                                        } 
                                                    } 
                                                ?>
                                            <?php }else{ ?>
                                                <?php if(!empty($tBchNameEx1)){ ?>
                                                    <?php foreach(explode(",",$tBchNameEx1) AS $key => $aValue){ ?>
                                                        <span class="label label-info m-r-5"><?=$aValue;?></span>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                </div>
            </div>
               
            <div class="form-group odvToAgency" style="margin-bottom: 0px;">
                    <label class="xCNLabelFrm"><?php echo language('news/news/news','tNewSendExclude').language('news/news/news','tNewSendAgn')?></label>
                    <div class="input-group">
                        <input  type="text" class="form-control xCNHide" id="oetAgencyCodeEx" name="oetAgencyCodeEx" 
                                value="<?=$tAgnCodeEx?>" 
                                readonly
                        >
                        <input type="text" class="form-control" id="oetAgencyNameEx" name="oetAgencyNameEx" value="<?=$tAgnNameEx?>" placeholder="<?php echo language('news/news/news','tNewSendNoExclude')?>"  readonly>
                        <span class="input-group-btn">
                            <button id="oimBrowseAgencyEx" type="button" class="btn xCNBtnBrowseAddOn" >
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                </div>
            </div>

            <div class="odvToAgency"  style="white-space:nowrap;width:100%;overflow-x:auto;margin-bottom: 10px;"> 
                <div id="odvAgencyShowEx" style="margin-bottom: 10px;margin-top: 10px;">
                                <?php if(isset($aNewBchEx['raItems']) && !empty($aNewBchEx['raItems'])){ ?>
                                                <?php 
                                                    $tAgnNameEx1 = "";
                                                    foreach ($aNewBchEx['raItems'] AS $key => $aValue) { 
                                                        
                                                        if( !empty($aValue['FTAgnName']) && strpos($tAgnNameEx1, $aValue['FTAgnName']) !== 0 ){ // เช็คค่าซ้ำ 
                                                            $tAgnNameEx1 .= $aValue['FTAgnName'];
                                                ?>
                                                            <span class="label label-info m-r-5"><?=$aValue['FTAgnName'];?></span>
                                                <?php
                                                        } 
                                                    } 
                                                ?>
                                            <?php }else{ ?>
                                                <?php if(!empty($tAgnNameEx1)){ ?>
                                                    <?php foreach(explode(",",$tAgnNameEx1) AS $key => $aValue){ ?>
                                                        <span class="label label-info m-r-5"><?=$aValue;?></span>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                </div>
            </div>

               <div class="form-group">
					<div class="validate-input">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('news/news/news','tNewAddTitle')?></label>
						<input
							type="text"
							class="form-control"
							maxlength="200"
							id="oetNewDesc1"
							name="oetNewDesc1"
							placeholder="<?php echo language('news/news/news','tNewAddTitle')?>"
							value="<?=$tNewName?>"
							data-validate-required="<?php echo language('news/news/news','tNewAddTitle')?>"
						>
					</div>
				</div>


               <div class="form-group">
						<label class="xCNLabelFrm"><?= language('news/news/news','tNewDesc')?></label>
						<textarea class="form-control" rows="10" id="oetNewDesc2<?=date('YmdHis')?>" name="oetNewDesc2" data-validate-required="<?php echo language('news/news/news','tNewDesc');?>"><?=$tNewRmk?></textarea>
				</div>


				<!-- <div class="form-group">
					<div class="validate-input">
						<label class="xCNLabelFrm"><?php echo language('news/news/news','tNewRefUrl')?></label>
						<input
							type="text"
							class="form-control"
							maxlength="200"
							id="oetNewRefUrl"
							name="oetNewRefUrl"
							value="<?=$tNewRefUrl?>"
							placeholder="<?php echo language('news/news/news','tNewRefUrl')?>"
							value=""
							data-validate-required="<?php echo language('news/news/news','tNewRefUrl')?>"
						>
					</div>
				</div> -->

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="">
                    <div class="form-group row" id="odvNewShowDataTable"></div>
                </div>
                <script>
                    var oNewCallDataTableFile = {
                        ptElementID     : 'odvNewShowDataTable',
                        ptBchCode       : $('#ohdBchCode').val(),
                        ptDocNo         : $('#ohdNewCode').val(),
                        ptDocKey        : 'TCNMNews',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : ''
                    }
                    JCNxUPFCallDataTableForNew(oNewCallDataTableFile);
                </script>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include 'script/jNewsAdd.php'; ?>
<script>

$(document).ready(function () {
    JSxNEWSetElementType();
    tinymce.init({
        selector: '#oetNewDesc2<?=date('YmdHis')?>'
    });

    setTimeout(() => {
        $('.tox-statusbar__branding').hide();
    }, 650);
});

</script>
