<style>
    .xWBCMSALHeadPanel{
        padding-bottom:0px !important;
    }

    .xWBCMSALTextNumber{
        font-size: 25px !important;
        font-weight: bold;
    }
    
    .xWBCMSALPanelMainRight{
        padding-bottom:0px;
        overflow-x: auto;
        margin-top: -10px;
    }

    .xWBCMSALFilter{
        cursor: pointer;
    }

    .xWBCMSALRequest{
        cursor: pointer;
    }
    .xWOverlayLodingChart{
        position: absolute;
	    min-width: 100%;
	    min-height: 100%;
	    width: 100%;
	    background: #FFFFFF;
	    z-index: 2500;
	    display: none;
	    top: 0%;
        margin-left: 0px;
        left: 0%;
    }
    .xCNLabelFrm {
        font-family: THSarabunNew-Bold;
        font-size: 17px !important;
        font-weight: bold;
        color: #ffffff !important;
}
</style>

<div class="row">
    <!-- input ค่า sort กับ ฟิวช์ ที่ส่งไป query ของ Total By Branch -->
    <input type="hidden" id="oetDSHSALSort" name="oetDSHSALSort" value="">
    <input type="hidden" id="oetDSHSALFild" name="oetDSHSALFild" value="FTBchName,FTPosCode">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Panel Sale Data -->
        <div id="odvBCMSALPanelRight1" class="">
            <div >
                <div class="panel-body xWBCMSALPanelMainRight">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="">
                                <div class=" xWBCMSALHeadPanel">
                                    <form action="javascript:void(0);" class="validate-form" method="post" id="ofmBCMSALStandFormFilter">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 xCNPanelHeadColor" >
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10" style="padding: 15px;">
                                                <label class="xCNLabelFrm" style="color:#ffff">
                                                    <h3>
                                                        <?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabBch');?>:<?=$aBatchData['raItems']['FTBchName']?> > 
                                                        <?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabPos');?>:<?=$aBatchData['raItems']['FTPosRefTID']?> > 
                                                        <?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabSht');?>:<?=$aBatchData['raItems']['FTShfCode']?>
                                                        <input type="hidden" id="ohdTabSht" value="<?=$aBatchData['raItems']['FTShfCode']?>">
                                                    </h3>
                                                </label> 
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-l-0 ">
                                            <input  type="hidden" name="oetBatID" id="oetBatID" value="<?=$tBatID?>" >

                                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?= language('sale/salemonitor/salemonitor','tBCMBatTabStdType')?></label>
                                                    <select class="selectpicker form-control" name="ocmBCMBatTabStdType" id="ocmBCMBatTabStdType">
                                                        <option value=""><?= language('sale/salemonitor/salemonitor','tBCMItemAll')?></option>
                                                        <option value="1"><?= language('sale/salemonitor/salemonitor','tBCMBatTabStdType1')?></option>
                                                        <option value="2"><?= language('sale/salemonitor/salemonitor','tBCMBatTabStdType2')?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="padding-top:25px">
                                                <div class="form-group" style="margin-left: 10px;">
                                                    <button id="obtBCMBtnStdFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?=language('common\main\main','tSearch')?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            
                                <div class="col-md-12 xWBCMSALDataPanel"  id="odvPanelSaleData"></div>
                                <div class="xWOverlayLodingChart" data-keyfilter="FSD">
                                    <img src="<?php echo base_url(); ?>application/modules/common/assets/images/ada.loading.gif" class="xWImgLoading">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

 
    </div>
</div>
<div id="odvBCMSALModalFilterHTML"></div>

<?php include "script/jStandMonitor.php";?>