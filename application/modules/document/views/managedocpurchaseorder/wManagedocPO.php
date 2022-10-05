<div class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol class="breadcrumb">
                    <?php FCNxHADDfavorite('docMngDocPreOrdB/0/0');?>
                    <li id="oliMNPTitle" style="cursor:pointer;" onclick="JSvMNPCallPageList()"><?=language('document\managedocpurchaseorder\managedocpurchaseorder', 'tMNPTitle'); ?></li>
                    <li id="oliMNPTitle_Manage" class="active"><a href="javascrip:;"><?=language('document\managedocpurchaseorder\managedocpurchaseorder', 'tMNPMangData');?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <div class="demo-button xCNBtngroup" style="width:100%;">
                    <div id="odvMNPBtnGrpInfo">
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                            <button id="obtMNPCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <button id="obtMNPCreateDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNPCreateDocument(false)"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPBTNConfirmDoc');?></button>   
                            <button id="obtMNPApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNPAproveDocRef()"><?=language('common/main/main','tCMNApprove');?></button>          
                            <button id="obtMNPGenFileAgain" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNPGenFileAgain()">สร้างไฟล์</button>                                                                                    
                            <button id="obtMNPExportDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style="display:none;" onclick="JSxMNPSendEmail()">ส่งอีเมล์</button>     
                        <?php endif; ?>
                    </div>
                    <div id="odvMNPBtnGrpAddEdit">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" onclick="JSvMNPCallPageList('')"><?=language('common/main/main', 'tBack'); ?></button>
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                <button id="obtMNPCreateDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSxMNPCreateDocument(false)"> <?=language('document/purchaseorder/purchaseorder','tMNPBTNConfirmDoc');?> </button>       
                                <div class="btn-group xCNBTNSaveDoc">
                                    <button id="obtMNPSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"><?=language('common/main/main', 'tSave'); ?></button>
                                    <?=$vBtnSave ?>
                                </div>
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
    <div id="odvMNPContentPageDocument"></div>
</div>

<!-- =========================================== ยืนยันการสร้างเอกสาร ============================================= -->
<div id="odvMNPModalCreateDocNo" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p id="ospModalCreateDocNo"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPWarningConfirmCreateDoc');?></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxMNPCreateDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อนุมัติเอกสาร ============================================= -->
<div class="modal fade xCNModalApprove" id="odvMGPPopupApv">
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

<!-- =========================================== ยืนยันการส่งเมล์ ============================================= -->
<div id="odvMNPModalSendMail" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p>ยืนยันการส่งออกไฟล์ไปหาผู้จำหน่าย</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNConfirmSendMail" data-dismiss="modal" >
                    ส่งอีเมล์
                </button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jManagedocPO.php') ?>
