
<input type="hidden" id="ohdBCMSALSessionBchCode" value="<?php echo $this->session->userdata('tSesUsrBchCodeDefault');?>">
<input type="hidden" id="ohdBCMSALSessionBchName" value="<?php echo $this->session->userdata('tSesUsrBchNameDefault');?>">
<input type="hidden" name="odhSMTSessionUserID" id="odhSMTSessionUserID" value="<?=$this->session->userdata('tSesSessionID')?>" >
<input type="hidden" name="odhSesUsrBchCode" id="odhSesUsrBchCode" value="<?=$this->session->userdata('tSesUsrBchCodeDefault')?>" >
<input type="hidden" name="odhnSesUsrBchCount" id="odhnSesUsrBchCount" value="<?=$this->session->userdata('nSesUsrBchCount')?>" >
<input type="hidden" name="odhSMTHOST" id="odhSMTHOST" value="<?=MQ_Sale_HOST?>" >
<input type="hidden" name="odhSMTPORT" id="odhSMTPORT" value="<?=MQ_Sale_PORT?>" >
<input type="hidden" name="odhSMTUSER" id="odhSMTUSER" value="<?=MQ_Sale_USER?>" >
<input type="hidden" name="odhSMTPASS" id="odhSMTPASS" value="<?=MQ_Sale_PASS?>" >
<input type="hidden" name="odhSMTVHOST" id="odhSMTVHOST" value="<?=MQ_Sale_VHOST?>" >

<div id="odvBCMSALMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliBCMSALMenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('dasBCM');?>
                    <li id="oliBCMSALTitle" style="cursor:pointer;" datenow="<?=date('Y-m-d')?>"><?php echo language('sale/salemonitor/salemonitor', 'tBCMTitle');?></li>
                </ol>
            </div>
        
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <div class="xCNBtngroup" style="width:100%;">
                    <div>
                        <div class="xCNBtngroup" style="width:100%;">
                            <button id="obtBCMBtnBackBatPage" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('sale/salemonitor/salemonitor', 'tBCMPageBack')?></button>
                            <button id="obtBCMExport"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBCMBtnExport'); ?></button>               
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>    
</div>
<div class="xCNMenuCump xCNBCMSALBrowseLine" id="odvMenuCump">&nbsp;</div>

<div class="main-content">
    <div class="panel panel-headline">
        <div class="row">
            <div id="odvBCMSALContentPage"></div>
        </div>
    </div>
</div>

<?php include "script/jBlueCardMonitor.php";?>