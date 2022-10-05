
<style>
    .xCNPanelHD{
        border: 1px solid #d7d7d7;
    }

    .xCNTextStatusBold{
        font-weight: bold;
    }

</style>

<!-- 1:ใบสั่งสินค้าจากสาขา , 2:ใบสั่งสินค้าจากสาขา - ลูกค้า-->
<input id="ohdMNGTypeDocument"      type="hidden" value="<?=$tMNGTypeDocument?>">

<div class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol class="breadcrumb">
                    <?php FCNxHADDfavorite('docMngDocPreOrdB/0/0');?>
                    <li id="oliMNGTitle" style="cursor:pointer;" onclick="JSvMNGCallPageList('')">
                        <?php 
                            if(@$tMNGTypeDocument == 1){ //ใบสั่งสินค้าจากสาขา
                                echo language('document/managedocorderbranch/managedocorderbranch', 'tMNGTitle'); 
                            }else{ //ใบสั่งสินค้าจากสาขา-ลูกค้า
                                echo language('document/managedocorderbranch/managedocorderbranch', 'ใบสั่งสินค้าจากสาขา - ลูกค้า');
                            }   
                        ?>
                    </li>
                    <li id="oliMNGTitle_ManagePDT" class="active"><a href="javascrip:;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPMangData')?></a></li>
                    <li id="oliMNGTitle_StatusApv" class="active"><a href="javascrip:;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusAproveDoc')?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <div class="demo-button xCNBtngroup" style="width:100%;">
                    <div id="odvMNGBtnGrpAddEdit">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                <button id="obtMNGBackStep" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" style="display:none;" onclick="JSvMNGCallPageList()"><?=language('common/main/main','tBack');?></button>   
                                <button id="obtMNGCreateDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNGCreateDocRef()"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPBTNConfirmDoc');?></button>   
                                <button id="obtMNGApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNGAproveDocRef()"><?=language('common/main/main','tCMNApprove');?></button>               
                                <button id="obtMNGGenFileAgain" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNGGenFileAgain()">สร้างไฟล์ Excel</button>                                                                                    
                                <button id="obtMNGExportDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNGSendEmail()">ส่งอีเมล์</button>                                                                 
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvMNGContentPageDocument"></div>
</div>

<!--จำนวนสินค้าที่กรอกมา เกิน หรือขาด-->
<div id="odvMGTModalPDTLessAndMore" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPWarningPDTError');?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" >
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--ยืนยันการสร้างเอกสาร-->
<div id="odvMGTModalCreateDocNo" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p id="ospModalCreateDocNo"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPWarningConfirmCreateDoc');?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNConfirmCreateDocNo" data-dismiss="modal" >
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--อนุมัติเอกสาร-->
<div class="modal fade xCNModalApprove" id="odvMGTPopupApv">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?=language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?=language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?=language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?=language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?=language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?=language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?=language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNConfirmApprove" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--ยืนยันการสร้างเอกสาร-->
<div id="odvMGTModalExportFile" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p>ยืนยันการส่งออกไฟล์ไปหาผู้จำหน่าย</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn xCNBTNPrimery xCNConfirmExport" data-dismiss="modal" >
                    ส่งออก
                </button> -->
                <button type="button" class="btn xCNBTNPrimery xCNConfirmExportAndDowload" data-dismiss="modal" >
                    ส่งอีเมล์
                </button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--ดาวน์โหลดไฟล์-->
<div id="odvMGTModalDowloadFile" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p>ดำเนินการสำเร็จ</p>
                <strong id="ospMGTDowloadFile" style="display: none;" >รายละเอียดไฟล์แนบ</strong>
            </div>
            <div class="modal-footer">
                <a class="btn xCNBTNPrimery xCNConfirmDowloadGoToDowload" href="#" style="color: #FFF;" onclick="JSxCloseModalDowloadFile()" > ดาวน์โหลด </a>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ตรวจจำนวนสินค้าในคลัง -->
<div id="odvMNGModalChkPdtStkBal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'ตรวจสอบจำนวนคงคลัง')?></h5>
            </div>
            <div class="modal-body">
                <div id="odvMNGModalHeader">
                
                    <div class="row">
                        <input class="form-control xCNHide" id="oetMNGChkPdtStkBalPdtCode" name="oetMNGChkPdtStkBalPdtCode">
                        <div class="col-lg-12 col-md-12 col-xs-12" style="margin-bottom:10px;">
                            <span style="font-size: 22px !important;font-weight: bold;">รหัสสินค้า : </span>
                            <span style="font-size: 22px !important;" id="ospMNGModalPdtCode">-</span>
                            <span style="font-size: 22px !important;font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ชื่อสินค้า : </span>
                            <span style="font-size: 22px !important;" id="ospMNGModalPdtName">-</span>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-5 col-md-5 col-xs-12">
                            <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder','สาขา'); ?></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control xCNHide" id="oetMNGChkPdtStkBalBchCode" name="oetMNGChkPdtStkBalBchCode" maxlength="5" >
                                    <input class="form-control xWPointerEventNone" type="text" id="oetMNGChkPdtStkBalBchName" name="oetMNGChkPdtStkBalBchName" placeholder="<?=language('document/deliveryorder/deliveryorder','สาขา'); ?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtMNGChkPdtStkBalBrowseBch" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-xs-12">
                            <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder','คลัง'); ?></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control xCNHide" id="oetMNGChkPdtStkBalWahCode" name="oetMNGChkPdtStkBalWahCode" maxlength="5" >
                                    <input class="form-control xWPointerEventNone" type="text" id="oetMNGChkPdtStkBalWahName" name="oetMNGChkPdtStkBalWahName" placeholder="<?=language('document/deliveryorder/deliveryorder','คลัง'); ?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtMNGChkPdtStkBalBrowseWah" type="button" class="btn xCNBtnBrowseAddOn" disabled>
                                            <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm">&nbsp;</label>
                                <button id="obtMNGChkPdtStkBalSearchSubmit" class="btn xCNBTNPrimery" style="width:100%"><?=language('common/main/main', 'กรอง'); ?></button>
                            </div>
                        </div>
                        
                    </div>

                </div>
                <div id="odvMNGModalDetails"></div>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jManagedoc.php') ?>
