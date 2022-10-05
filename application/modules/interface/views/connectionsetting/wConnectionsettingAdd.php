<?php
if($aResult['rtCode'] == "1"){
     $tAgnCode          =   $aResult['raItems']['FTAgnCode'];
     $tBchCode          =   $aResult['raItems']['FTBchCode'];
     $tShpCode          =   $aResult['raItems']['FTShpCode'];
     $tShpName          =   $aResult['raItems']['FTShpName'];
     $tWahCode          =   $aResult['raItems']['FTWahCode'];
     $tAgnName          =   $aResult['raItems']['FTAgnName'];
     $tBchName          =   $aResult['raItems']['FTBchName'];
     $tWahName          =   $aResult['raItems']['FTWahName'];
     $tWahRefNo         =   $aResult['raItems']['FTWahRefNo'];
     $tWahStaChannel    =   $aResult['raItems']['FTWahStaChannel'];
     $Channel1          =   ""; 
     $Channel2          =   ""; 
     $Channel3          =   ""; 

     switch ($tWahStaChannel) {
        case '1':
                $Channel1 = "selected";
          break;
        case '2':
                $Channel2 = "selected";;
          break;
        case '3':
                $Channel3 = "selected";
          break;
      }

    

    //route
    $tRoute             = "connectionsettingEventEdit";
}else{


    $tWahCode           =  "";
    $tWahName           =  "";
    $tWahRefNo          =  "";
    $tWahStaChannel     =  "";
    $Channel1           =   ""; 
    $Channel2           =   ""; 
    $Channel3           =   ""; 

    $tBchCode           =  $tBchCompCode;
    $tBchName           =  $tBchCompName;
    $tShpCode           =  $tShpCompCode;
    $tShpName           =  $tShpCompName;
    $tAgnCode           =  $tSesAgnCode;
    $tAgnName           =  $tSesAgnName;

    //route
	$tRoute             = "connectionsettingEventAdd";
}
?>


<div class="row">
    <!--ปุ่มตัวเลือก กับ ปุ่มเพิ่ม-->
    <div class="col-lg-12 col-md-12 col-xs-12   text-right">
        <button type="button" onclick="JSxCallGetContent();" id="obtGpShopCancel" class="btn" style="background-color: #D4D4D4; color: #000000;">
            <?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel')?>
        </button>
        <?php if($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
                <button type="submit" class="btn xCNBTNSubSave" onclick="$('#obtSubmitConnectionSetting').click()"> <?php echo  language('common/main/main', 'tSave')?></button>
        <?php endif; ?>
    </div>

<div class="col-lg-12 col-md-12 col-xs-12">
	<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
        <form id="ofmAddConnectionSetting" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
            <button style="display:none" type="submit" id="obtSubmitConnectionSetting" onclick="JSnAddEditConnectionSetting('<?php echo $tRoute; ?>')"></button>
                <?php
                    $tConBchCode       = "";
                    $tConBchName       = "";

                    $tConAgnCode       = "";
                    $tConAgnName       = ""; 
                    if($tRoute == "connectionsettingEventAdd"){
                        $tConBchCode   = $tBchCompCode;
                        $tConBchName   = $tBchCompName;
                        $tConShpCode   = $tShpCompCode;
                        $tConShpName   = $tShpCompName;
                        $tConAgnCode   = $tSesAgnCode;
                        $tConAgnName   = $tSesAgnName;
                        $tDisabled     = '';
                        $tNameElmIDAgn = 'oimBrowseAgn';
                        $tNameElmIDBch = 'oimBrowseBch';
                        $tNameElmIDShp = 'oimBrowseShp';
                        $tNameElmIDWah = 'oimBrowseWah';
                    }else{
                        $tConBchCode    = $tBchCode;
                        $tConBchName    = $tBchName;
                        $tConShpCode   = $tShpCode;
                        $tConShpName   = $tShpName;
                        $tConAgnCode    = $tAgnCode;
                        $tConAgnName    = $tAgnName;
                        $tDisabled      = 'disabled';
                        $tNameElmIDBch  = '';
                        $tNameElmIDShp = '';
                        $tNameElmIDAgn  = '';
                        $tNameElmIDWah  = '';
                    }
                ?>
                <div class="form-group <?php if( !FCNbGetIsAgnEnabled()) : echo 'xCNHide';  endif;?>">
                    <input type="hidden" id="oetCssAgnCode" name="oetCssAgnCode" value="">

                    <!-- <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting','tTBAgency')?></label>
                            <div class="input-group"><input type="text" class="form-control xCNHide" id="oetCssAgnCode" name="oetCssAgnCode" maxlength="5" value="<?=$tConAgnCode;?>">
                            <input  type="text" class="form-control xWPointerEventNone" id="oetCssAgnName" name="oetCssAgnName"
                                maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting','tTBAgency')?>" value="<?=$tConAgnName;?>"
                                data-validate-required = "<?php echo language('interface/connectionsetting/connectionsetting','tValiAgency')?>" 
                                readonly>
                            <span class="input-group-btn">
                            <button id="<?=@$tNameElmIDAgn;?>" type="button" class="btn xCNBtnBrowseAddOn <?=@$tDisabled?>">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div> -->
                </div>
                
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('company/branch/branch','tBCHTitle')?></label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" 
                            id="oetCssBchCode" 
                            name="oetCssBchCode" 
                            maxlength="5" 
                            value="<?=$tConBchCode;?>">
                        <input 
                            type="text" 
                            class="form-control xWPointerEventNone" 
                            id="oetCssBchName" 
                            name="oetCssBchName" 
                            maxlength="100"
                            placeholder="<?php echo language('interface/connectionsetting/connectionsetting','tTBBanch')?>" value="<?=$tConBchName;?>"
                            data-validate-required = "<?php echo language('interface/connectionsetting/connectionsetting','tValiBanch')?>" 
                            readonly
                        >
                        <span class="input-group-btn">
                            <button id="<?=@$tNameElmIDBch;?>" type="button" class="btn xCNBtnBrowseAddOn <?=@$tDisabled?>">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>

                <div class="form-group <?php if( !FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                    <input type="hidden" id="oetCssShpCode" name="oetCssShpCode" value="">

                    <!-- <label class="xCNLabelFrm"><?php echo language('company/shop/shop','tSHPTitle_POS')?></label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" 
                            id="oetCssShpCode" 
                            name="oetCssShpCode" 
                            maxlength="5" 
                            value="<?=$tConShpCode;?>">
                        <input 
                            type="text" 
                            class="form-control xWPointerEventNone" 
                            id="oetCssShpName" 
                            name="oetCssShpName" 
                            maxlength="100"
                            placeholder="<?php echo language('company/shop/shop','tSHPTitle_POS')?>" value="<?=$tConShpName;?>"
                            data-validate-required = "<?php echo language('interface/connectionsetting/connectionsetting','tValiBanch')?>" 
                            readonly
                        >
                        <span class="input-group-btn">
                            <button id="<?=@$tNameElmIDShp;?>" type="button" class="btn xCNBtnBrowseAddOn <?=@$tDisabled?>">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div> -->
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting','tValiWahouse')?></label>
                        <div class="input-group">
                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetCssWahCode'   name='oetCssWahCode' value="<?=$tWahCode;?>">
                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetCssWahName' name='oetCssWahName' value="<?=$tWahName;?>" placeholder="<?= language('movement/movement/movement','tMMTListWaHouse')?>" autocomplete="off"
                            data-validate-required = "<?php echo language('interface/connectionsetting/connectionsetting','tValiWahouse')?>" 
                             readonly>
                            <span class="input-group-btn">
                            <button id="<?=$tNameElmIDWah;?>" type="button" class="btn xCNBtnDateTime <?=@$tDisabled?>">
                                <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
         
                 <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('company/branch/branch','tTBWahRefNo')?></label>
                        <input type="text" class="form-control"
                        id="oetCssWahRefNo" name="oetCssWahRefNo" maxlength="100" value="<?=$tWahRefNo;?>"
                        data-validate-required = "<?php echo language('interface/connectionsetting/connectionsetting','tValiWahRefNo')?>"
                        >
                </div>
                
                <div class="form-group">
                    <!-- <label class="xCNLabelFrm"><?php //echo language('interface/connectionsetting/connectionsetting','tFMChannel')?></label>
                    <select class="selectpicker form-control" id="ocmStaChannel" name="ocmStaChannel" maxlength="1" readonly>
                        <option value="1" <?php //echo $Channel1;?>><?php //echo language('interface/connectionsetting/connectionsetting','tFMselec1')?></option>
                        <option value="2" <?php //echo $Channel2;?>><?php //echo language('interface/connectionsetting/connectionsetting','tFMselec2')?></option>
                        <option value="3" <?php //echo $Channel3;?>><?php //echo language('interface/connectionsetting/connectionsetting','tFMselec3')?></option>
                    </select> -->
                </div>


        </form>
    </div>
</div>

<?php include "script/jConnectionsettingAdd.php"; ?>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

