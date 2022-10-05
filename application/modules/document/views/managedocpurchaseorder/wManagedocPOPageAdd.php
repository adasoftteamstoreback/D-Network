<?php
    if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {
        $tMNPRoute              = "docMnpDocPOEventEdit";
        $tMNPCreateBy           = $aDataDocHD['raItems']['FTUsrName'];
        $tMNPStaDoc             = $aDataDocHD['raItems']['FTXrhStaDoc'];
        $tMNPStaPrcDoc          = $aDataDocHD['raItems']['FTXrhStaPrcDoc'];
        $tMNPPathFile           = $aDataDocHD['raItems']['FTXPhRefFile'];
        $tMNPBCHCode            = $aDataDocHD['raItems']['FTBchCode'];
        $tMNPBCHName            = $aDataDocHD['raItems']['FTBchName'];
        $tMNPSPLCode            = $aDataDocHD['raItems']['FTSplCode'];
        $tMNPSPLName            = $aDataDocHD['raItems']['FTSplName'];
        $tMNPDocMNP             = $aDataDocHD['raItems']['FTXphDocNo'];
    } else {
        $tMNPRoute              = "docMnpDocPOEventAdd";
        $tMNPCreateBy           = $this->session->userdata('tSesUsrUsername');
        $tMNPStaDoc             = '';
        $tMNPStaPrcDoc          = '0';
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

    .mark-font, .panel-default > .panel-heading.mark-font{
        color           : #232C3D !important;
        font-weight     : 900;
    }
</style>

<form id="ofmMNPFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtMNPSubmitDocument" onclick="JSxMNPAddEditDocument()"></button>
    <input type="hidden" id="ohdMNPRoute" name="ohdMNPRoute" value="<?=@$tMNPRoute?>">
    <input type="hidden" id="ohdMNPStaDoc" name="ohdMNPStaDoc" value="<?=@$tMNPStaDoc?>">
    <div class="row">

        <!--Panel ด้านซ้าย-->
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQDocument'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvMNPDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvMNPDataStatusInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPTrRefPRS');?></label>
                                    <input type="text" class="form-control" id="oetMGTPODocNo" name="oetMGTPODocNo" maxlength="50" value="<?=@$tMNPDocMNP?>" placeholder="<?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPTrRefPRS');?>" readonly>
                                </div>

                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPCreateBy');?> : </label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdPOCreateBy" name="ohdPOCreateBy" value="">
                                            <label><?=@$tMNPCreateBy?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusDoc'); ?> : </label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label>
                                                <?php 
                                                    if($tMNPStaDoc == 1){
                                                        $tStaDoc = language('document\adjustmentcost\adjustmentcost_lang','tADCStaDoc1');
                                                    }else if($tMNPStaDoc == 3){
                                                        $tStaDoc = language('document\adjustmentcost\adjustmentcost_lang','tADCStaDoc3');
                                                    }else{
                                                        $tStaDoc = '-';
                                                    } 
                                                    echo $tStaDoc;
                                                ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusPrc'); ?> : </label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label>
                                                <?php 
                                                    if($tMNPStaDoc == 1 || $tMNPStaDoc == ''){
                                                        if($tMNPStaPrcDoc == '1'){
                                                            $tStaPrcDoc = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusPrc1');
                                                        }else if($tMNPStaPrcDoc == '' || $tMNPStaPrcDoc == null){
                                                            $tStaPrcDoc = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusPrc2');
                                                        }else{
                                                            $tStaPrcDoc = '-';
                                                        } 
                                                    }else{
                                                        $tStaPrcDoc = '-';
                                                    }
                                                    echo $tStaPrcDoc;
                                                ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อัพโหลดไฟล์ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPUploadFile'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvMNPUploadFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvMNPUploadFile" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPUploadFile'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="oetMNPFileNameImport" name="oetMNPFileNameImport" placeholder="<?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPPalseSelectdFile'); ?>" readonly="" value="<?=@$tMNPPathFile?>">
                                        <input type="file" class="form-control" style="visibility: hidden; position: absolute;" id="oefMNPFileImportExcel" name="oefMNPFileImportExcel" value="<?=@$tMNPPathFile?>" onchange="JSxMNPCheckFileImportFile(this, event)" 
                                        accept=".csv,application/vnd.ms-excel,.xlt,application/vnd.ms-excel,.xla,application/vnd.ms-excel,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.xltx,application/vnd.openxmlformats-officedocument.spreadsheetml.template,.xlsm,application/vnd.ms-excel.sheet.macroEnabled.12,.xltm,application/vnd.ms-excel.template.macroEnabled.12,.xlam,application/vnd.ms-excel.addin.macroEnabled.12,.xlsb,application/vnd.ms-excel.sheet.binary.macroEnabled.12">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary xCNImportFile" style="border-radius: 0px 5px 5px 0px;" onclick="$('#oefMNPFileImportExcel').click()">
                                            <?=language('common/main/main', 'tSelectedImport');?>                                                            
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPBranchForm'); ?></label>
                                    <input type="hidden" id="oetMGTBCHCodeTo" name="oetMGTBCHCodeTo" value="<?=@$tMNPBCHCode?>">
                                    <input type="text" class="form-control" id="oetMGTBCHNameTo" name="oetMGTBCHNameTo" maxlength="50" placeholder="<?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPBranchForm'); ?>" readonly value="<?=@$tMNPBCHName?>">
                                </div>

                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPSPLTo'); ?></label>
                                    <input type="hidden" id="oetMGTSPLCodeTo" name="oetMGTSPLCodeTo" value="<?=@$tMNPSPLCode?>">
                                    <input type="text" class="form-control" id="oetMGTSPLNameTo" name="oetMGTSPLNameTo" maxlength="50" placeholder="<?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPSPLTo'); ?>" readonly value="<?=@$tMNPSPLName?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Panel ด้านขวา-->
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px; position:relative; min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">

                                <div class="row p-t-10">
                                    <!--ค้นหา-->
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvMNPSearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                                                <span class="input-group-btn">
                                                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvMNPSearchPdtHTML()">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!--ตัวเลือก-->
                                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-right xCNHideWhenCancelOrApprove">
                                        <div class="btn-group xCNDropDrownGroup" style="margin-bottom:10px;">
                                            <button type="button" class="btn xCNBTNMngTable xWDropdown" data-toggle="dropdown">
                                                <?= language('common/main/main','tCMNOption')?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li id="oliMNPDTBtnDeleteMulti" class="disabled">
                                                    <a data-toggle="modal" data-target="#odvMNPDTModalDelPdtInDTTempMultiple"><?=language('common/main/main','tDelAll')?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-t-10">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        
                                        <!-- ตารางสินค้า -->
                                        <div id="odvMNPDataPdtTableDTTemp"></div>

                                        <!--ส่วนสรุปท้ายบิล-->
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                                <div id="odvRowDataEndOfBill">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <label class="pull-left mark-font"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPQtyBch'); ?></label>
                                                            <label class="pull-right mark-font"><span class="mark-font xCNShowCountBCHList">0</span> <?= language('document/bookingorder/bookingorder', 'สาขา'); ?></label>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                                <div id="odvRowDataEndOfBill">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <label class="pull-left mark-font"><?= language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPQtyPdt'); ?></label>
                                                            <label class="pull-right mark-font"><span class="mark-font xCNShowCountPDTList">0</span> <?= language('document/bookingorder/bookingorder', 'tTWXItems'); ?></label>
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
        </div>

    </div>
</form>

<!-- =========================================== ลบสินค้าใน Temp แบบหลายตัว ============================================= -->
<div id="odvMNPDTModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmMNPDTSeqNoDelete"   name="ohdConfirmMNPDTSeqNoDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อัพโหลดไฟล์ใหม่อีกครั้ง ============================================= -->
<div class="modal fade" id="odvMNPPopupChangeExcelAgain">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/adjustmentcost/adjustmentcost', 'tASTWarning'); ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPWarningChangeFile');?></p>
                <p><strong><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPConfirmChangeFile');?></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNMNPPopupChangeExcelAgain" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult xCNMNPPopupCancelChangeExcel" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>    

    $("document").ready(function () {
        
    });

    //Import File
    function JSxMNPCheckFileImportFile(poElement, poEvent) {
        try {   
            var oFile = $(poElement)[0].files[0];
            if(oFile == undefined){
                $("#oetMNPFileNameImport").val("");
            }else{  

                if($('#otbMNPDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable') == true){ //มีข้อมูลอยู่ในตาราง
                    
                    JCNxOpenLoading();
                    $("#oetMNPFileNameImport").val(oFile.name);
                    setTimeout(function(){ 
                        JSxMNPWirteImportFile();
                    }, 3000);

                }else{ //ไม่พบข้อมูลในตาราง

                    $('#odvMNPPopupChangeExcelAgain').modal('show');
                    $('#odvMNPPopupChangeExcelAgain .xCNMNPPopupChangeExcelAgain').unbind().click(function() { //กดยืนยันที่จะเปลี่ยน
                        JCNxOpenLoading();
                        $("#oetMNPFileNameImport").val(oFile.name);
                        setTimeout(function(){ 
                            JSxMNPWirteImportFile();
                        }, 3000);
                    });

                    $('#odvMNPPopupChangeExcelAgain .xCNMNPPopupCancelChangeExcel').unbind().click(function() { //กดยืนยันที่จะเปลี่ยน
                        $("#oetMNPFileNameImport").val('');
                        $('#oefMNPFileImportExcel').val('');
                    });
                }
            }
        } catch (err) {
            JCNxCloseLoading();
            console.log("JSxPromotionStep1SetImportFile Error: ", err);
        }
    }

    //Move ลง Temp
    function JSxMNPWirteImportFile(evt) {
        var f = $('#oefMNPFileImportExcel')[0].files[0];
        if (f) {
            var r = new FileReader();
            r.onload = e => {
                var contents 	= processExcel(e.target.result);
                var aJSON 		= JSON.parse(contents);

                //ตรวจสอบชื่อชิทว่าถูกต้องไหม
                if(typeof(aJSON['Product']) == 'undefined'){
                    alert('รูปแบบเอกสารไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง');
                    JCNxCloseLoading();
                    return;
                }

                ////////////////////////////////////////////// รายละเอียดส่วนเอกสาร HD ////////////////////////////////////////////// 
                var aJSONDataHD         = aJSON["Summary"];

                //จากสาขา
                $('#oetMGTBCHCodeTo').val(aJSONDataHD[1][1]);
                $('#oetMGTBCHNameTo').val(aJSONDataHD[2][1]);

                //สั่งซื้อไปยังผู้จำหน่าย
                $('#oetMGTSPLCodeTo').val(aJSONDataHD[4][1]);
                $('#oetMGTSPLNameTo').val(aJSONDataHD[5][1]);

                ////////////////////////////////////////////// รายละเอียดส่วนรายการสินค้า DT ///////////////////////////////////////////
                var aJSONData           = aJSON["Product"];
                var nCount              = aJSONData.length;
                var aNewPackData        = [];
                var aError              = [];

                //ตรวจสอบ excel cell ที่มันเป็นค่าว่าง
                for(var k=0; k<nCount; k++){
                    if(aJSONData[k].length > 0){
                        aNewPackData.push(aJSONData[k]);
                    }
                }
                var nCount              = aNewPackData.length;
                var aJSONData           = aNewPackData;

                //ในลูปนี้จะเช็ค 2 step status 3:เช็ค MaxLen ,status 4:เช็ค DataType
                for(var j=1; j<nCount; j++){

                    //Template_Filed_สาขา
                        if(typeof(aJSONData[j][0]) != 'undefined' || null){
                            if(aJSONData[j][0] == null){
                                aJSONData[j][0] = 'N/A';
                                aError.push('7','[0]'+'$&รหัสสาขาไม่ได้ระบุข้อมูล$&'+'N/A');
                            }else{
                                if(aJSONData[j][0].toString().length > 5){
                                    var tValueOld   = aJSONData[j][0];
                                    aJSONData[j][0] = aJSONData[j][0].toString().substring(0, 5);
                                    aError.push('4','[0]'+'$&รหัสสาขายาวเกินกำหนด$&'+tValueOld);
                                }
                            }
                        }else{
                            aJSONData[j][0] = 'N/A';
                            aError.push('7','[0]'+'$&รหัสสาขาไม่ได้ระบุข้อมูล$&'+'N/A');
                        }

                    //Template_Filed_รหัสสินค้า
                        if(typeof(aJSONData[j][1]) != 'undefined' || null){
                            if(aJSONData[j][1] == null){
                                aJSONData[j][1] = 'N/A';
                                aError.push('7','[1]'+'$&รหัสสินค้าไม่ได้ระบุข้อมูล$&'+'N/A');
                            }else{
                                if(aJSONData[j][1].toString().length > 20){
                                    var tValueOld   = aJSONData[j][1];
                                    aJSONData[j][1] = aJSONData[j][1].toString().substring(0, 20);
                                    aError.push('4','[1]'+'$&รหัสสินค้ายาวเกินกำหนด$&'+tValueOld);
                                }
                            }
                        }else{
                            aJSONData[j][1] = 'N/A';
                            aError.push('7','[1]'+'$&รหัสสินค้ายาวเกินกำหนด$&'+'N/A');
                        }

                    //Template_Filed_ชื่อสินค้า
                        if(typeof(aJSONData[j][2]) != 'undefined' || null){
                            if(aJSONData[j][2] == null){
                                aJSONData[j][2] = 'N/A';
                            }else{
                                if(aJSONData[j][2].toString().length > 100){
                                    var tValueOld   = aJSONData[j][2];
                                    aJSONData[j][2] = aJSONData[j][2].toString().substring(0, 100);
                                    aError.push('4','[1]'+'$&ชื่อสินค้ายาวเกินกว่ากำหนด$&'+tValueOld);
                                }
                            }
                        }else{
                            aJSONData[j][2] = 'N/A';
                        }

                    //Template_Filed_อ้างอิงใบขอซื้อผู้จำหน่าย
                        if(typeof(aJSONData[j][3]) != 'undefined' || null){
                            if(aJSONData[j][3] == null){
                                aJSONData[j][3] = 'N/A';
                            }else{
                                if(aJSONData[j][3].toString().length > 100){
                                    var tValueOld   = aJSONData[j][3];
                                    aJSONData[j][3] = aJSONData[j][53].toString().substring(0, 100);
                                    aError.push('4','[1]'+'$&รหัสอ้างอิงเอกสารเกินกำหนด$&'+tValueOld);
                                }
                            }
                        }else{
                            aJSONData[j][3] = 'N/A';
                        }

                    //Template_Filed_บาร์โค๊ด
                        if(typeof(aJSONData[j][5]) != 'undefined' || null){
                            if(aJSONData[j][5] == null){
                                aJSONData[j][5] = 'N/A';
                            }else{
                                if(aJSONData[j][5].toString().length > 25){
                                    var tValueOld   = aJSONData[j][5];
                                    aJSONData[j][5] = aJSONData[j][5].toString().substring(0, 25);
                                    aError.push('4','[1]'+'$&รหัสบาร์โค้ดยาวเกินกำหนด$&'+tValueOld);
                                }
                            }
                        }else{
                            aJSONData[j][5] = 'N/A';
                        }

                    //Template_Filed_จำนวน
                        if(typeof(aJSONData[j][6]) != 'undefined' || null){
                            if(aJSONData[j][6] == null){
                                aJSONData[j][6] = '0';
                            }else{
                                var Letters = /^[ก-๛A-Za-z]+$/;
                                var nValue = aJSONData[j][6].toString();
                                var nValue = nValue.replace(" ", "");
                                if(nValue.match(Letters)){
                                    //เอาตัวที่ผิดออก
                                    var tValueOld  = aJSONData[j][6];
                                    aJSONData[j].pop();
                                    aJSONData[j].push(0);
                                    aError.push('3','[1]'+'$&รูปแบบจำนวนผิด$&'+tValueOld);
                                }
                            }
                        }else{
                            aJSONData[j][6] = '0';
                        }

                    //Template_Filed_จำนวนสั่งซื้อ
                        if(typeof(aJSONData[j][7]) != 'undefined' || null){
                            if(aJSONData[j][7] == null){
                                aJSONData[j][7] = '0';
                            }else{
                                var Letters = /^[ก-๛A-Za-z]+$/;
                                var nValue = aJSONData[j][7].toString();
                                var nValue = nValue.replace(" ", "");
                                if(nValue.match(Letters)){
                                    //เอาตัวที่ผิดออก
                                    var tValueOld  = aJSONData[j][7];
                                    aJSONData[j].pop();
                                    aJSONData[j].push(0);
                                    aError.push('3','[1]'+'$&รูปแบบจำนวนผิด$&'+tValueOld);
                                }
                            }
                        }else{
                            aJSONData[j][7] = '0';
                        }

                    if(aError.length > 0){
                        aJSONData[j].push(aError[0],aError[1]);
                        aError = [];
                    }else{
                        aJSONData[j].push('1','');
                    }
                }

                //MoveToTemp
                JSxMNTProcessImportExcel(aJSONData);

            }
            r.readAsBinaryString(f);

            //ล้างค่า
            $('#oefMNPFileImportExcel').val("");
        } else {
            console.log("Failed to load file");
        }
    }

    //Insert To Temp
    function JSxMNTProcessImportExcel(aJSONData){

        var tDocNo = $("#oetMGTPODocNo").val();
        if(tDocNo == '' || tDocNo == null){
            var tDocNo = 'DUMMY';
        }

        $.ajax({
            type		: "POST",
            url			: "docMnpDocPOImportFile",
            data		: {  
                'tDocNo'    : tDocNo,
                'aPackdata' : aJSONData 
            },
            async       : false,
            success	: function (aResult) {
                console.log(aResult)
                JSvMNPLoadPdtDataTableHtml();
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR)
                console.log(textStatus)
                JCNxCloseLoading();
            }
        });
        
    }

</script>