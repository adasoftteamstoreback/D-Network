<style>
    #odvTAXRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvTAXRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvTAXRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>

<?php 
    $tDocVal = $aDataList['raItems'];
    @$tDocVal2 = $aDataList['raItems2'];
    $tLastBill = $aDataDetailList['raItemLast'][0];
    
    if ($tDocVal['FTXshStaDoc'] == 3) {
        $tClassStaDoc = 'text-danger';
        $tStaDoc = language('common/main/main', 'tStaDoc3');
    }else{
        if ($tDocVal['FTXshStaDoc'] == 1 && $tDocVal['FTXshStaApv'] == '') {
            $tClassStaDoc = 'text-warning';
            $tStaDoc = language('common/main/main', 'tStaDoc');
        }else{
            $tClassStaDoc = 'text-success';
            $tStaDoc = language('common/main/main', 'tStaDoc1');
        }
    }

    if ($tDocVal['FTAddVersion'] == 1) {
        // $tAddr = 'บ้านเลขที่ '.@$tDocVal['FTAddV1No'].' ซอย '.@$tDocVal['FTAddV1Soi'].' ถนน '.@$tDocVal['FTAddV1Road'].' หมู่บ้าน '.@$tDocVal['FTAddV1Village'].' '.@$tDocVal['FTSudName'].' '.@$tDocVal['FTDstName'].' '.@$tDocVal['FTPvnName'].' รหัสไปรษณีย์ '.@$tDocVal['FTAddV1PostCode'].'';
        $tAddr = 'บ้านเลขที่ '.@$tDocVal['FTAddV1No'].' ซอย '.@$tDocVal['FTAddV1Soi'].' ถนน '.@$tDocVal['FTAddV1Road'].' หมู่บ้าน '.@$tDocVal['FTAddV1Village'].' รหัสไปรษณีย์ '.@$tDocVal['FTAddV1PostCode'].'';
    }else{
        $tAddr = $tDocVal['FTAddV2Desc1'];
    }

    $tBchCode = $aBchCode;

?>
<form id="ofmWhTaxFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtWhTaxSubmitDocument" onclick="JSxWhTaxAddEditDocument()"></button>
    <div class="contrainer">
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
                <div class="panel panel-default" style="margin-bottom: 25px;">
                    <div id="odvWhTaxHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                        <label class="xCNTextDetail1"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDoc'); ?></label>
                        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvWhTaxDataStatusInfo" aria-expanded="true">
                            <i class="fa fa-plus xCNPlus"></i>
                        </a>
                    </div>
                    <div id="odvWhTaxDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <!-- เลขรหัสเอกสาร -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDocNo'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FTXshDocNo']; ?>" style="pointer-events:none" readonly>
                                    </div>
                                    <!-- วันที่เอกสาร -->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tWhTaxDocDate'); ?></label>
                                        <input type="text" class="form-control" name="oetWhTaxDocDate" value="<?php echo date('Y-m-d',strtotime($tDocVal['FDXshDocDate'])); ?>" readonly>
                                    </div>
                                    <!-- เวลาเอกสาร -->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tWhTaxDocTime'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocTime" name="oetWhTaxDocTime" value="<?php echo $tDocVal['FTXshDocTime']; ?>" readonly>
                                    </div>

                                    <!-- สถานะอนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxStaDoc'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdWhTaxStaDoc" name="ohdWhTaxStaDoc" value="<?php echo $tStaDoc;?>">
                                                <label class="<?php echo $tClassStaDoc;?>"><?php echo $tStaDoc;?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php  echo language('document/saleorder/saleorder', 'tWhTaxCreateBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdWhTaxApvBy" name="ohdWhTaxApvBy" maxlength="20" value="<?php echo $tDocVal['FTUsrName']?>">
                                                <label><?php echo $tDocVal['FTUsrName']?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- วันที่อนุมัติ -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxApvDate'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdWhTaxApvDate" name="ohdWhTaxApvBy" maxlength="20" value="<?php echo $tDocVal['FDLastUpdOn']?>">
                                                <label><?php echo date('Y-m-d',strtotime($tDocVal['FDLastUpdOn']))?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel อ้างอิงเอกสาร -->
                <div class="panel panel-default" style="margin-bottom: 25px;">
                    <div id="odvWhTaxWhTaxDocRef" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                        <label class="xCNTextDetail1"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxRefDoc'); ?></label>
                        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvWhTaxDocRef" aria-expanded="true">
                            <i class="fa fa-plus xCNPlus"></i>
                        </a>
                    </div>
                    <div id="odvWhTaxDocRef" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    
                                    <!-- อ้างอิงเอกสารใบเสนอราคา -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxRefDocNo'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxRefDocIntNo" name="oetWhTaxRefDocIntNo" maxlength="20" 
                                        value="<?php 
                                            if ($tDocVal['FTXshRefInt'] == '') {
                                                echo "-";
                                            }else {
                                                echo $tDocVal['FTXshRefInt'];
                                            }
                                        ?>" 
                                        style="pointer-events:none" readonly>
                                    </div>
                                    <!-- วันที่เอกสาร -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxRefDocDate'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxRefDocIntDate" name="oetWhTaxRefDocIntDate" maxlength="20" value="<?php 
                                            if ($tDocVal['FDXshRefIntDate'] == '') {
                                                echo "-";
                                            }else {
                                                echo date('Y-m-d',strtotime($tDocVal['FDXshRefIntDate']));
                                            }
                                        ?>" style="pointer-events:none" readonly>
                                    </div>
                                    <!-- เลขที่อ้างอิงลูกค้า -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxRefExtNo'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxRefDocExtNo" name="oetWhTaxRefDocExtNo" maxlength="20" value="<?php 
                                            if ($tDocVal['FTXshRefExt'] == '') {
                                                echo "-";
                                            }else {
                                                echo $tDocVal['FTXshRefExt'];
                                            }
                                        ?>" style="pointer-events:none" readonly>
                                    </div>

                                    <!-- วันอ้างอิงเอกสารจากลูกค้า -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxRefExtDate'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxRefDocExtDate" name="oetWhTaxRefDocExtDate" maxlength="20" value="<?php 
                                            if ($tDocVal['FDXshRefExtDate'] == '') {
                                                echo "-";
                                            }else {
                                                echo $tDocVal['FDXshRefExtDate'];
                                            }
                                        ?>" style="pointer-events:none" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel เงื่อนไขการชำระเงิน -->
                <div class="panel panel-default" style="margin-bottom: 25px;">
                    <div id="odvWhTaxHeadPay" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                        <label class="xCNTextDetail1"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxPay'); ?></label>
                        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvWhTaxDataPay" aria-expanded="true">
                            <i class="fa fa-plus xCNPlus"></i>
                        </a>
                    </div>
                    <div id="odvWhTaxDataPay" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <!-- ประเภทภาษี -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <?php if ($tDocVal['FTXshVATInOrEx'] == 1) {
                                            $tVatIn = "รวมใน";
                                        }else{
                                            $tVatIn = "แยกนอก";
                                        }
                                        ?>
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxType'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tVatIn; ?>" style="pointer-events:none" readonly>
                                    </div>

                                    <!-- ประเภทการชำระเงิน -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <?php if ($tDocVal['FTXshCshOrCrd'] == 1) {
                                            $tVatPayType = "เงินสด";
                                        }else{
                                            $tVatPayType = "Credit";
                                        }
                                        ?>
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxPayType'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tVatPayType; ?>" style="pointer-events:none" readonly>
                                    </div>

                                    <!-- ระยะเครดิต (วัน) -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <?php if ($tDocVal['FNXshCrTerm'] == 1) {
                                            $tVatPayType = "เงินสด";
                                        }else{
                                            $tVatPayType = "Credit";
                                        }
                                        ?>
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxPayCredit'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FNXshCrTerm']; ?> <?php echo language('document/withholdingtax/withholdingtax', 'วัน'); ?>" style="pointer-events:none" readonly>
                                    </div>

                                    <!-- วันครบกำหนดชำระ -->
                                    <div class="form-group" style="cursor:not-allowed">
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxPayDate'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FDXshDueDate']; ?>" style="pointer-events:none" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel ไฟลแนบ -->
                <div class="panel panel-default" style="margin-bottom: 25px;">
                    <div id="odvWhTaxReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                        <label class="xCNTextDetail1"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxRefFiles'); ?></label>
                        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvWhTaxDataFile" aria-expanded="true">
                            <i class="fa fa-plus xCNPlus"></i>
                        </a>
                    </div>
                    <div id="odvWhTaxDataFile" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvShowDataTable">
                                    <?php if (!empty($aDataRefFile['raItems'])) { ?>
                                        <?php foreach ($aDataRefFile['raItems'] as $nKey => $tRefFile) { ?>
                                            <a href="<?php echo $tRefFile['FTFleObj']?>" aria-expanded="true"><?php echo $tRefFile['FTFleName']?></a>
                                        <?php }?>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="row">
                    <!-- ข้อมูลลูกค้า -->
                    <div id="odvWhTaxDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default">
                            <div id="odvWhTaxReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                                <label class="xCNTextDetail1"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxCstInfo'); ?></label>
                            </div>
                            <div class="panel-body" style="cursor:not-allowed">

                                <!-- ชื่อลูกค้า/ชื่ออกใบกำกับภาษี -->
                                <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxCstName'); ?></label>
                                <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FTCstName']; ?>" style="pointer-events:none" readonly>
                                <!-- end -->

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                                        <!-- เลขประจำตัวผู้เสียภาษี -->
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxNo'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FTAddTaxNo']; ?>" style="pointer-events:none" readonly>
                                        <!-- END -->
                                        
                                        <!-- สถานประกอบการณ์ -->
                                        <?php if ($tDocVal['FTAddStaHQ'] == 1) {
                                                $tVatStaHQ = "สำนักงานใหญ่";
                                            }else{
                                                $tVatStaHQ = "สาขา";
                                            }
                                        ?>
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxStation'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tVatStaHQ; ?>" style="pointer-events:none" readonly>
                                        <!-- END -->
                                        
                                        <!-- เบอร์โทรศัพท์ -->
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxCstTel'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FTAddTel']; ?>" style="pointer-events:none" readonly>
                                        <!-- END -->

                                        <!-- ที่อยู่ 1 -->
                                        <?php if ($tDocVal['FTAddV2Desc1'] == '') {
                                                $tVatAddr1 = "-";
                                            }else{
                                                $tVatAddr1 = $tDocVal['FTAddV2Desc1'];
                                            }
                                        ?>
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxAddr1'); ?></label>
                                        <textarea name="otaWhTaxAddr1" id="otaWhTaxAddr1" rows="5" style="cursor:not-allowed" readonly><?php echo $tAddr; ?></textarea>
                                        <!-- END -->

                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                
                                        <!-- ประเภท -->
                                        <?php if ($tDocVal['FTAddStaBusiness'] == 1) {
                                                $tVatStaBusiness = "นิติบุคคล";
                                            }else{
                                                $tVatStaBusiness = "บุคคลธรรมดา";
                                            }
                                        ?>
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxType'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tVatStaBusiness; ?>" style="pointer-events:none" readonly>
                                        <!-- END -->
                                        
                                        <!-- รหัสสาขา -->
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxBchCode'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FTAddStaBchCode']; ?>" style="pointer-events:none" readonly>
                                        <!-- END -->
                                        
                                        <!-- เบอร์แฟกซ์ -->
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxFax'); ?></label>
                                        <input type="text" class="form-control" id="oetWhTaxDocNo" name="oetWhTaxDocNo" maxlength="20" value="<?php echo $tDocVal['FTAddFax']; ?>" style="pointer-events:none" readonly>
                                        <!-- END -->

                                        <!-- ที่อยู่ 2 -->
                                        <?php if ($tDocVal['FTAddV2Desc2'] == '') {
                                                $tVatAddr2 = "-";
                                            }else{
                                                $tVatAddr2 = $tDocVal['FTAddV2Desc2'];
                                            }
                                        ?>
                                        <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxAddr2'); ?></label>
                                        <textarea name="otaWhTaxAddr2" id="otaWhTaxAddr2" rows="5" style="cursor:not-allowed" readonly><?php echo $tVatAddr2;?></textarea>
                                        <!-- END -->
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ตารางรายการสินค้า -->
                    <div id="odvWhTaxDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" nowrap class="text-center"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDataListNo'); ?></th>
                                                        <th width="20%" nowrap class="text-center"><?php echo language('document/withholdingtax/withholdingtax', 'รหัสสินค้า'); ?></th>
                                                        <th width="40%" nowrap class="text-center"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDataList'); ?></th>
                                                        <th width="20%" nowrap class="text-center"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDataListType'); ?></th>
                                                        <th width="5%" nowrap class="text-left"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDataListTaxRate'); ?></th>
                                                        <th width="10%" nowrap class="text-left"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDataListTaxAmount'); ?></th>
                                                        <th width="10%" nowrap class="text-center"><?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxDataListRmk'); ?></th>
                                                        <th width="10%" nowrap class="text-center"><?php echo language('document/withholdingtax/withholdingtax', 'tWithholdingTax'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1 ?>
                                                    <?php foreach ($aDataDetailList['raItems'] as $nKey => $tDataList) { ?>
                                                        <?php 
                                                            $tWhType = '';
                                                            if ($tDocVal['FTAddStaBusiness'] == 2) {
                                                                switch ($tDataList['FTXsdWhType']) {
                                                                    case '1':
                                                                        $tWhType = "ให้เช่าทรัพย์สิน";
                                                                    break;

                                                                    case '2':
                                                                        $tWhType = "วิชาชีพอิสระ";
                                                                    break;

                                                                    case '3':
                                                                        $tWhType = "รับเหมา";
                                                                    break;

                                                                    case '4':
                                                                        $tWhType = "เงินรางวัลในการประกวด, แข่งขัน";
                                                                    break;

                                                                    case '5':
                                                                        $tWhType = "งานนักแสดงสาธารณะ";
                                                                    break;

                                                                    case '6':
                                                                        $tWhType = "รับโฆษณา";
                                                                    break;

                                                                    case '7':
                                                                        $tWhType = "รับจ้างทำของ";
                                                                    break;

                                                                    case '8':
                                                                        $tWhType = "บริการ";
                                                                    break;

                                                                    case '9':
                                                                        $tWhType = "เงินรางวัล, ส่วนลด";
                                                                    break;

                                                                    case '10':
                                                                        $tWhType = "ค่าขนส่ง";
                                                                    break;
                                                                    
                                                                    default:
                                                                        $tWhType = '-';
                                                                    break;
                                                                }
                                                            }elseif ($tDocVal['FTAddStaBusiness'] == 1) {
                                                                switch ($tDataList['FTXsdWhType']) {
                                                                    case '1':
                                                                        $tWhType = "ค่านายหน้า";
                                                                    break;

                                                                    case '2':
                                                                        $tWhType = "ดอกเบี้ยเงินฝาก";
                                                                    break;

                                                                    case '3':
                                                                        $tWhType = "เงินปันผล";
                                                                    break;

                                                                    case '4':
                                                                        $tWhType = "วิชาชีพอิสระ";
                                                                    break;

                                                                    case '5':
                                                                        $tWhType = "วิชาชีพอิสระ";
                                                                    break;

                                                                    case '6':
                                                                        $tWhType = "ค่าจ้างทำของ";
                                                                    break;

                                                                    case '7':
                                                                        $tWhType = "เงินรางวัลในการประกวด, แข่งขัน";
                                                                    break;

                                                                    case '8':
                                                                        $tWhType = "ค่าโฆษณา";
                                                                    break;

                                                                    case '9':
                                                                        $tWhType = "ค่าบริการ";
                                                                    break;

                                                                    case '10':
                                                                        $tWhType = "ค่าเบี้ยประกันวินาศภัย";
                                                                    break;

                                                                    case '11':
                                                                        $tWhType = "ค่าขนส่ง";
                                                                    break;
                                                                    
                                                                    default:
                                                                        $tWhType = '-';
                                                                    break;
                                                                }
                                                            }else{
                                                                $tWhType = '-';
                                                            }    
                                                        ?>
                                                        <tr>
                                                            <td nowrap class="text-right"><?php echo @$i++;?></td>
                                                            <td nowrap class="text-left"><?php echo $tDataList['FTPdtCode'];?></td>
                                                            <td nowrap class="text-left"><?php echo $tDataList['FTXsdPdtName'];?></td>
                                                            <td nowrap class="text-left"><?php echo $tWhType;?></td>
                                                            <td nowrap class="text-left"><?php echo $tDataList['FCXsdWhtRate'];?>%</td>
                                                            <td nowrap class="text-right"><?php echo number_format($tDataList['FCXsdNet'], 2);?></td>
                                                            <td nowrap class="text-left"><?php echo $tDataList['FTXsdRmk'];?></td>
                                                            <td nowrap class="text-right"><?php echo number_format($tDataList['FCXsdWhtAmt'], 2);?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!--ส่วนสรุปท้ายบิล-->
                                    <div class="row" id="odvTAXRowDataEndOfBill">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="pull-left mark-font"><?= language('document/withholdingtax/withholdingtax', 'tWhTaxDataListRmk'); ?></div>
                                                    <!-- <div class="pull-right mark-font"></div> -->
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="list-group" id="oulDataListVat">
                                                        <label class="pull-left mark-font"><?php echo $tDocVal['FTXshRmk'];?></label>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="pull-left mark-font"><?= language('document/withholdingtax/withholdingtax', 'tWhTaxVatRate'); ?></div>
                                                    <div class="pull-right mark-font"><?= language('document/withholdingtax/withholdingtax', 'tWhTaxAmountVat'); ?></div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body" style="margin: 20px 0px;">
                                                    <ul class="list-group" id="oulDataListVat" >
                                                    <label class="pull-left">7.00%</label>
                                                    <label class="pull-right" id="olbVatSum"><?php echo number_format($tDocVal['FCXshVat'], 2); ?></label>
                                                    </ul>
                                                </div>
                                                <div class="panel-heading">
                                                    <label class="pull-left mark-font"><?= language('document/withholdingtax/withholdingtax', 'tWhTaxTotalValVat'); ?></label>
                                                    <label class="pull-right mark-font" id="olbVatSum"><?php echo number_format($tDocVal['FCXshVat'], 2); ?></label>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Of Bill -->
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="pull-left mark-font"><?= language('document/withholdingtax/withholdingtax', 'tWhTaxSumFCXtdNet'); ?></div>
                                                    <div class="pull-right mark-font"><label class="pull-right mark-font" id="olbVatSum"><?php echo number_format($tDocVal['FCXshTotal'], 2); ?></label></div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body" style="margin: 20px 0px;">
                                                    <label class="pull-left"><?= language('document/withholdingtax/withholdingtax', 'tWhTaxTotalValVat'); ?></label>
                                                    <label class="pull-right" id="olbVatSum"><?php echo number_format($tDocVal['FCXshVat'], 2); ?></label>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-heading">
                                                    <label class="pull-left mark-font"><?= language('document/withholdingtax/withholdingtax', 'tWhTaxSumTotalValVat'); ?></label>
                                                    <label class="pull-right mark-font" id="olbVatSum"><?php echo number_format($tLastBill['Total'], 2); ?></label>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
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

<script>
	function JSxWhTaxPrintDoc(){
		var aInfor = [
			{ "Lang"        : '<?=FCNaHGetLangEdit(); ?>'},
			{ "ComCode"     : '<?=FCNtGetCompanyCode(); ?>'},
			{ "BranchCode"  : '<?=FCNtGetAddressBranch($tBchCode);?>' },
			{ "DocCode"     : $("#oetWhTaxDocNo").val() }, // เลขที่เอกสาร
			{ "DocBchCode"  : '<?=$tBchCode;?>'},
            { "tGrdStr"     : ''}
		];
        console.log(aInfor);
		window.open('<?= base_url(); ?>' + "formreport/Frm_PSWhTaxBill?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
	}
</script>