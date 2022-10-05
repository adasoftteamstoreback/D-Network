<?php
$tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
$tUserBchName   = $this->session->userdata('tSesUsrBchNameDefault');
$tUserBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
$tUserWahName   = $this->session->userdata('tSesUsrWahName');
$tUserWahCode   = $this->session->userdata('tSesUsrWahCode');

if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1') {
    $aDataDocHD             = $aDataDocHD['raItems'];
    $tCRVBchCode            = $aDataDocHD['FTBchCode'];
    $tCRVBchName            = $aDataDocHD['FTBchName'];
    $tCRVDocNo              = $aDataDocHD['FTXshDocNo'];
    $tCRVUsrCode            = $aDataDocHD['FTUsrCode'];
    $tCRVUsrName            = $aDataDocHD['FTUsrName'];
    $dCRVDocDate            = date("d/m/Y H:i", strtotime($aDataDocHD['FDXshDocDate']));
    $tCRVCstCode            = $aDataDocHD['FTCstCode'];
    $tCRVCstName            = $aDataDocHD['FTCstName'];
    $tCRVCstTel             = $aDataDocHD['FTCstTel'];
    $tCRVCstEmail           = $aDataDocHD['FTCstEmail'];
    $tCRVStaPdtPick         = $aDataDocHD['FTStaPdtPick'];
    $tCRVPshType         = $aDataDocHD['FTPshType'];
    
} 

?>
<style>
    #odvRowDataEndOfBill .panel-heading {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    #odvRowDataEndOfBill .panel-body {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }

    #odvRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
</style>
<form id="ofmCRVFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">

    <input type="text" class="xCNHide" name="oetCRVDocNo" id="oetCRVDocNo" value="<?=$tCRVDocNo?>">
    <input type="text" class="xCNHide" name="oetCRVStaPdtPick" id="oetCRVStaPdtPick" value="<?=$tCRVStaPdtPick?>">
    <input type="text" class="xCNHide" name="oetCRVPshType" id="oetCRVPshType" value="<?=$tCRVPshType?>">
    <button style="display:none" type="submit" id="obtCRVSubmitDocument" onclick="JSxCRVAddEditDocument()"></button>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel ข้อมูลการฝากของ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvCRVReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'ข้อมูลการฝากของ'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvCRVDataReferenceDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCRVDataReferenceDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">

                                <!-- สาขาที่ฝากของ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'เลขที่เอกสาร'); ?></label>
                                    <input type="hidden" class="form-control xControlForm xWPointerEventNone" id="ohdCRVDocCode" name="ohdCRVDocCode" value="<?=$tCRVDocNo?>" readonly>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetCRVDocName" name="oetCRVDocName" value="<?=$tCRVDocNo?>" readonly>
                                </div>

                                <!-- สาขาที่ฝากของ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'สาขาที่ฝากของ'); ?></label>
                                    <input type="hidden" class="form-control xControlForm xWPointerEventNone" id="ohdCRVBchCode" name="ohdCRVBchCode" value="<?=$tCRVBchCode?>" readonly>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetCRVBchName" name="oetCRVBchName" value="<?=$tCRVBchName?>" readonly>
                                </div>

                                <!-- พนักงานฝากของเข้าตู้ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'พนักงานฝากของเข้าตู้'); ?></label>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetCRVUsrName" name="oetCRVUsrName" value="<?=$tCRVUsrName?>" readonly>
                                </div>

                                <!-- วันที่-เวลา ฝาก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'วันที่-เวลา ฝาก'); ?></label>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetCRVDocDate" name="oetCRVDocDate" value="<?=$dCRVDocDate?>" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ข้อมูลลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvCRVConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'ข้อมูลลูกค้า'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvCRVDataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCRVDataConditionDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                <!-- ชื่อ-สกุล -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'ชื่อ-สกุล'); ?></label>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetCRVCstName" name="oetCRVCstName" maxlength="100" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'ชื่อ-สกุล') ?>" value="<?=$tCRVCstName?>" readonly>
                                </div>

                                <!-- เบอร์โทร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'เบอร์โทร'); ?></label>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetCRVCstTel" name="oetCRVCstTel" maxlength="100" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'เบอร์โทร') ?>" value="<?=$tCRVCstTel?>" readonly>
                                </div>

                                <!-- อีเมล -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'อีเมล'); ?></label>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetCRVCstMail" name="oetCRVCstMail" maxlength="100" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'อีเมล') ?>" value="<?=$tCRVCstEmail?>" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

        <div class="col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvCRVDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <!-- Tab -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">

                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;" id="oliCRVContentProduct">
                                                    <a role="tab" data-toggle="tab" data-target="#odvCRVContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;" id="oliCRVContentHDRef">
                                                    <a role="tab" data-toggle="tab" data-target="#odvCRVContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Content Info -->
                                <div class="tab-content">

                                    <!-- รายการสินค้า -->
                                    <div id="odvCRVContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-10">
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvCRVCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvCRVCSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8"></div>
                                        </div>

                                        <div class="row p-t-10" id="odvCRVDataPdtTableDTTemp"></div>
                                    </div>

                                    <!-- อ้างอิง -->
                                    <div id="odvCRVContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row">
                                            <div id="odvCRVTableHDRef"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</form>

<!-- ======================================================================== View Modal Cancel Document  ======================================================================== -->
<div class="modal fade" id="odvCRVPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/depositboxreservation/depositboxreservation', 'tCRVCancelDoc') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/depositboxreservation/depositboxreservation', 'tCRVCancelDocWarnning') ?></p>
                <p><strong><?php echo language('document/depositboxreservation/depositboxreservation', 'tCRVCancelDocConfrim') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnCRVCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jCustomerReceivedAdd.php'); ?>
<?php /*include("script/wCustomerReceivedPdtAdvTableData.php");*/ ?>