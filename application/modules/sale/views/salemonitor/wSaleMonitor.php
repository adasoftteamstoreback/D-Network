<input type="hidden" id="ohdSMTSALBrowseType"   value="<?php echo $nSMTSALBrowseType;?>">
<input type="hidden" id="ohdSMTSALBrowseOption" value="<?php echo $tSMTSALBrowseOption;?>">
<input type="hidden" id="ohdSMTSALSessionBchCode" value="<?php echo $this->session->userdata('tSesUsrBchCodeDefault');?>">
<input type="hidden" id="ohdSMTSALSessionBchName" value="<?php echo $this->session->userdata('tSesUsrBchNameDefault');?>">
<input type="hidden" name="odhSMTSessionUserID" id="odhSMTSessionUserID" value="<?=$this->session->userdata('tSesSessionID')?>" >
<input type="hidden" name="odhSesUsrBchCode" id="odhSesUsrBchCode" value="<?=$this->session->userdata('tSesUsrBchCodeDefault')?>" >
<input type="hidden" name="odhnSesUsrBchCount" id="odhnSesUsrBchCount" value="<?=$this->session->userdata('nSesUsrBchCount')?>" >
<input type="hidden" name="odhSMTHOST" id="odhSMTHOST" value="<?=MQ_Sale_HOST?>" >
<input type="hidden" name="odhSMTPORT" id="odhSMTPORT" value="<?=MQ_Sale_PORT?>" >
<input type="hidden" name="odhSMTUSER" id="odhSMTUSER" value="<?=MQ_Sale_USER?>" >
<input type="hidden" name="odhSMTPASS" id="odhSMTPASS" value="<?=MQ_Sale_PASS?>" >
<input type="hidden" name="odhSMTVHOST" id="odhSMTVHOST" value="<?=MQ_Sale_VHOST?>" >

<?php if(isset($nSMTSALBrowseType) && $nSMTSALBrowseType == 0):?>
    <div id="odvSMTSALMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliSMTSALMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('salemonitor/0/0');?>
                        <li id="oliSMTSALTitle" style="cursor:pointer;" datenow="<?=date('Y-m-d')?>"><?php echo @$aTextLang['tSMTSALTitleMenu'];?></li>
                    </ol>
                </div>
            
            </div>
        </div>    
    </div>
    <div class="xCNMenuCump xCNSMTSALBrowseLine" id="odvMenuCump">&nbsp;</div>

    <div class="main-content">
        <div class="panel panel-headline">
            <div class="row">
                <div class="panel-body" style="padding-top:20px !important;">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                    <ul class="nav" role="tablist" data-typetab="main" data-tabtitle="odvSMTSALContentPage">
                                        <!--ตรวจสอบยอดขายหน้าร้าน-->
                                        <li id="oliSMTInfo1" class="active" data-typetab="main" data-tabtitle="odvSMTSALContentPage" style="cursor:pointer">
                                            <a role="tab" data-toggle="tab" data-target="#odvSMTSALContentPage" aria-expanded="true" 
                                                onclick="JSvSMTSALPageDashBoardMain()" >
                                                <?= language('sale/salemonitor/salemonitor', 'tSMTSALTitleMenu') ?>
                                            </a>
                                        </li>
                                        <!--สถานะรายการส่งข้อมูลขึ้นเซิร์ฟเวอร์-->
                                        <li id="oliSMTInfo2" class="" data-typetab="main" data-tabtitle="odvSMTMQInformation" style="cursor:pointer">
                                            <a role="tab" data-toggle="tab" data-target="#odvSMTMQInformation" aria-expanded="true"
                                                onclick="JCNxSMTCallMQInformation()" >
                                                <?= language('sale/salemonitor/salemonitor', 'tMQIImformation') ?>
                                            </a>
                                        </li>
                                        <!--เครื่องมือ-->
                                        <li id="oliSMTInfo3" class="" data-typetab="main" data-tabtitle="odvSMTSaleTools" style="cursor:pointer">
                                            <a role="tab" data-toggle="tab" data-target="#odvSMTSaleTools" aria-expanded="true"
                                                onclick="JCNxSMTCallSaleTools()" >
                                                <?= language('sale/salemonitor/salemonitor', 'tMQITools') ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="tab-content">
                                    <div id="odvSMTSALContentPage" class="tab-pane active in"></div>
                                    <div id="odvSMTMQInformation" class="tab-pane"></div>
                                    <div id="odvSMTSaleTools" class="tab-pane"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url();?>application/modules/sale/assets/src/salemonitor/jSaleMonitor.js"></script>