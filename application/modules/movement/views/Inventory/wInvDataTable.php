<?php 
    $nCurrentPage = '1';
?>

<style type="text/css">
    .xWPointDetail {font-size: 18px !important;font-weight: bold;cursor:pointer;text-decoration:underline;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
             <table class="table table-striped" style="width:100%">
					<thead>
						<tr>
							<th nowrap class="xCNTextBold" style="width:2%;text-align:center;"><?= language('movement/movement/movement','tMMTBOrder')?></th>
							<th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement','tMMTBPdtCode')?></th>
                            <th nowrap class="xCNTextBold" style="width:15%;text-align:center;"><?= language('movement/movement/movement','tMMTBPdtName')?></th>
                            <?php if($this->session->userdata("tSesUsrLevel") == 'HQ'): ?>
                            <th nowrap class="xCNTextBold" style="width:15%;text-align:center;"><?= language('movement/movement/movement','tMMTListBanch')?></th>
                            <?php endif; ?>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement','tINVInventoryWarehouse')?></th>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement','tINVInventoryAmount')?></th>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement','tINVStockBooking')?></th>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement','tINVStockBookingReserve')?></th>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement','tINVInventoryTemporaryWarehouse')?></th>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement','tINVInventoryTotal')?></th>
                        </tr>
					</thead>
					<tbody id="odvRGPList">
                        <?php if($aDataList['rtCode'] == 1 ):?>
                            <?php foreach($aDataList['raItems'] AS $key=>$aValue){  ?>
                                <tr 
                                    class="xCNTextDetail2 otrReason" 
                                    id="otrReason<?=$key?>"
                                    data-bchcode="<?=$aValue['FTBchCode']?>"
                                    data-wahcode="<?=$aValue['FTWahCode']?>"
                                    data-code="<?=$aValue['FTPdtCode']?>"
                                    data-name="<?=$aValue['FTPdtName']?>"
                                >
                                    <td nowrap class="text-center" style="text-align: center;">
                                        <?=$key + 1?>
                                    </td>
                                    <td nowrap class="text-left"><?=$aValue['FTPdtCode']?></td>
                                    <td nowrap class="text-left">
                                    <?=$aValue['FTPdtName']?>
                                    <?php if($aValue['FTPdtForSystem']==5){ ?>
                                        <br><label class="xCNTextLink" onclick="JSvInvMmtPdtFhnDetail(<?=$key?>)" ><?= language('movement/movement/movement','tMMTCheckPdtFasion')?></label>
                                    <?php } ?>
                                    </td>
                                    <?php if($this->session->userdata("tSesUsrLevel") == 'HQ'): ?>
                                        <td nowrap class="text-left"><?= !empty($aValue['FTBchName']) ? $aValue['FTBchName']: ''; ?></td>
                                    <?php endif; ?>
                                    <td nowrap class="text-left"><?=$aValue['FTWahName']?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue['FCStkQty'], $nOptDecimalShow);?></td>
                                    <td nowrap class="text-right">
                                        <a 
                                            href="javascript:void(0)"
                                            class="xWPointDetail"
                                            onclick="JSvInvMmtDetailStockBooking(this)"
                                        >
                                        <?php echo number_format($aValue['FCXtdQtySbk'],$nOptDecimalShow);?>
                                    </a>
                                    </td>
                                    <td nowrap class="text-right"><?php echo number_format(($aValue['FCStkQty'] - $aValue['FCXtdQtySbk']), $nOptDecimalShow);?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue['FCXtdQtyInt'], $nOptDecimalShow);?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue['FCXtdQtyBal'], $nOptDecimalShow);?></td>
                                </tr>
                            <?php } ?>
                        <?php else:?>
                            <tr><td class='text-center xCNTextDetail2' colspan='12' style="text-align: center;"><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                        <?php endif;?>
					</tbody>
			</table>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:10px;">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>


<div id="odvInvMmtModalPdtFhn" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1100px;">
    <input type='text' class='form-control xCNHide' id='oetInvPdtFhnPdtCode' name='oetInvPdtFhnPdtCode' value="" >
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'ตรวจสอบสินค้าคงคลัง-สินค้าแฟชั่น')?></label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-6"><?= language('movement/movement/movement','tMMTBPdtCode')?> :</div><div class="col-md-10 col-sm-10 col-xs-6"><label class="" id="olbPdtFhnPdtCode"><b><?= language('movement/movement/movement','tMMTBPdtCode')?></b></label></div>
                    <div class="col-md-2 col-sm-2 col-xs-6"><?= language('movement/movement/movement','tMMTBPdtName')?> :</div><div class="col-md-10 col-sm-10 col-xs-6"><label class="" id="olbPdtFhnPdtName"><b><?= language('movement/movement/movement','tMMTBPdtCode')?></b></label></div>
                </div>
                <hr>


                
                    <div class="row">
                            <!-- Browse สาขา -->
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <?php 
                                $tBCHCode = $this->session->userdata("tSesUsrBchCodeDefault");
                                $tBCHName = $this->session->userdata("tSesUsrBchNameDefault");
                            ?>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvPdtFhnBchStaSelectAll' name='oetInvPdtFhnBchStaSelectAll' value=<?=$tBCHCode?>>
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvPdtFhnBchCodeSelect'   name='oetInvPdtFhnBchCodeSelect' value=<?=$tBCHCode?>>
                                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvPdtFhnBchNameSelect' name='oetInvPdtFhnBchNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListBanch')?>" autocomplete="off" readonly value='<?=$tBCHName?>'>
                                    <span class="input-group-btn">
                                        
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
                                        ?>
                                        <button id="obtInvPdtFhnBrowseBranch" type="button" <?=$tDisabled?> class="btn xCNBtnDateTime">      
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                                <!-- Browse สาขา -->

                        <!-- Browse คลังสินค้า -->
                    <div class="col-md-4 col-sm-4 col-xs-6">
                                <?php 
                                    $tWahCode = $this->session->userdata("tSesUsrWahCode");
                                    $tWahName = $this->session->userdata("tSesUsrWahName");
                                ?>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvPdtFhnWahStaSelectAll' name='oetInvPdtFhnWahStaSelectAll' value="<?=$tWahCode?>">
                                        <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvPdtFhnWahCodeSelect'   name='oetInvPdtFhnWahCodeSelect' value="<?=$tWahCode?>">
                                        <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvPdtFhnWahNameSelect' name='oetInvPdtFhnWahNameSelect' value="<?=$tWahName?>" placeholder="<?= language('movement/movement/movement','tMMTListWaHouse')?>" autocomplete="off" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtInvPdtFhnBrowseWaHouse" type="button" class="btn xCNBtnDateTime">
                                                <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                        </div>
                            <!-- Browse คลังสินค้า -->

                        <!-- Browse รหัสควบคุมสต็อค -->
                            <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="form-group">
                                    <input class="form-control" name="oetInvPdtFhnRefCode" id="oetInvPdtFhnRefCode" value="" placeholder="<?= language('movement/movement/movement','รหัสควบคุมสต็อค')?>">
                            </div>
                            </div>         
                            <!-- กรองข้อมูล -->
                            <div class="col-md-2 col-sm-2 col-xs-6">
                                <button id="" onclick="JSvMmtPDtFashionDataTable();" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tAdvanceFillter')?></button>
                            </div>  
                    </div>

                    <div class="row" >
                        <!-- Browse ฤดูกาล -->
                        <div class="col-md-3 col-sm-3 col-xs-6">
                    
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvFhnPdtSeasonCode'   name='oetInvFhnPdtSeasonCode' value="">
                                        <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvFhnPdtSeasonName' name='oetInvFhnPdtSeasonName' value="" placeholder="<?= language('product/product/product','tFhnPdtDataTableSeason')?>" autocomplete="off" readonly>
                                        <span class="input-group-btn">
                                            <button id="obInvFhnPdtSeasonBrows" type="button" class="btn xCNBtnDateTime">
                                                <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                        </div>
                            <!-- Browse ฤดูกาล -->


                        <!-- Browse สี -->
                        <div class="col-md-2 col-sm-2 col-xs-6">
                    
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvFhnPdtColorCode'   name='oetInvFhnPdtColorCode' value="">
                                        <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvFhnPdtColorName' name='oetInvFhnPdtColorName' value="" placeholder="<?= language('product/product/product','tFhnPdtDataTableColor')?>" autocomplete="off" readonly>
                                        <span class="input-group-btn">
                                            <button id="obInvFhnPdtColorBrows" type="button" class="btn xCNBtnDateTime">
                                                <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                        </div>
                            <!-- Browse สี -->

                    <!-- Browse ขนาด -->
                    <div class="col-md-2 col-sm-2 col-xs-6">
                    
                        <div class="form-group">
                            <div class="input-group">
                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvFhnPdtSizeCode'   name='oetInvFhnPdtSizeCode' value="">
                                <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvFhnPdtSizeName' name='oetInvFhnPdtSizeName' value="" placeholder="<?= language('product/product/product','tFhnPdtDataTableSize')?>" autocomplete="off" readonly>
                                <span class="input-group-btn">
                                    <button id="obInvFhnPdtSizeBrows" type="button" class="btn xCNBtnDateTime">
                                        <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Browse ขนาด -->

                        <!-- Browse เนื้อผ้า -->
                        <div class="col-md-3 col-sm-3 col-xs-6">
                    
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvFhnPdtFabricCode'   name='oetInvFhnPdtFabricCode' value="">
                                        <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvFhnPdtFabricName' name='oetInvFhnPdtFabricName' value="" placeholder="<?= language('product/product/product','tFhnPdtDataTableFabric')?>" autocomplete="off" readonly>
                                        <span class="input-group-btn">
                                            <button id="obInvFhnPdtFabricBrows" type="button" class="btn xCNBtnDateTime">
                                                <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                        </div>
                        <!-- Browse เนื้อผ้า -->

                    <!-- ใช้งาน -->
                    <div class="col-md-2 col-sm-2 col-xs-6">
                            <label class="fancy-checkbox">
                                <input id="ocbPdtFhnStaUse" type="checkbox" class="ocbListItem" name="ocbPdtFhnStaUse" value="1" checked>
                                <span>&nbsp;<?php echo language('product/product/product', 'tFhnPdtDataTableUse1'); ?></span>
                            </label>
                    </div> 

                    </div>


            </div>

        
                <div  id="odvPdtFhnDataTable"></div>
        

            
        </div>
    </div>
</div>


<!-- Modal Show Detail -->
<div id="odvInvStockBookingDetail" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 7000; display: none;">
    <div class="modal-dialog modal-lg" style="width:70%">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'ตารางรายละเอียดการจองสินค้า')?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel'); ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<?php include 'script/jInvDataTable.php'; ?>