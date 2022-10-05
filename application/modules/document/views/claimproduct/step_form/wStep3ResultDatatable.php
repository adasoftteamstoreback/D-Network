<style>
    .xCNBTNSaveStep3{
        border-color: #3995ff;
        padding     : 3px 10px;
        border-radius: 2px !important;
        color       : #3995ff !important;
        font-weight : bold;
        font-size   : 17px;
        margin      : 2px 10px;
        width       : 100px;
    }
</style>
<div class="table-responsive">
    <table id="otbCLMStep3Datatable" class="table table-striped">
        <thead>
            <tr class="xCNCenter">
                <th nowrap class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','หน่วย')?></th>
                <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','แจ้งเคลมผู้จำหน่าย')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','วันที่ส่ง')?></th>
                <?php if($this->session->userdata("tSesUsrLevel") != 'HQ'){ ?>
                    <!-- ถ้าไม่ใช่สำนักงานใหญ่ทำไม่ได้ -->
                <?php }else { ?>
                    <th nowrap class="xCNTextBold" style="width: 120px;"><?=language('document/invoice/invoice','บันทึกผลเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวนที่บันทึกผล')?></th>
                <?php } ?>
                <th nowrap class="xCNTextBold" style="width: 120px;"><?=language('document/invoice/invoice','รับสินค้า')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวนที่รับแล้ว')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($aDataList['rtCode'] == 1 ):?>
            <?php 
                if(FCNnHSizeOf($aDataList['raItems'])!=0){
                    foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                        <?php $nKey = $aDataTableVal['FNPcdSeqNo']; ?>
                        <tr>
                            <td nowrap style="text-align:center"><?=$nKey?></td>
                            <td nowrap><?=$aDataTableVal['FTPdtCode'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPcdPdtName'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPcdBarCode'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPunName'];?></td>
                            <td nowrap class="text-right"><?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?></td>
                            <td nowrap><?=($aDataTableVal['FTSplName'] == '') ? '-' : $aDataTableVal['FTSplName'];?></td>
                            <td nowrap class="text-center"><?=date('d/m/Y',strtotime($aDataTableVal['FDPcdDateReq']))?></td>

                            <!--บันทึกผลเคลม-->
                            <?php 
                                if((float)$aDataTableVal['FCPcdQty'] == (float)$aDataTableVal['SUMQTY']){ //บันทึกผลครบเเล้ว
                                    $tClassDisabledInSaveStep3  = 'disabled';
                                    $tClassEventSaveInSaveStep3 = '';
                                }else{
                                    $tClassDisabledInSaveStep3  = '';
                                    $tClassEventSaveInSaveStep3 = 'xCNHistoryAndSaveQTY';
                                }
                            ?>

                            <?php if($this->session->userdata("tSesUsrLevel") != 'HQ'){ ?>
                                <!-- ถ้าไม่ใช่สำนักงานใหญ่ทำไม่ได้ -->
                            <?php }else { ?>
                                <td class="text-center">
                                    <button data-hisqty="saveclaim" data-seqpdt="<?=$nKey?>" class="<?=$tClassEventSaveInSaveStep3?> <?=$tClassDisabledInSaveStep3?> btn btn-outline-primary xCNBTNSaveStep3" type="button"> บันทึกผลเคลม </button>
                                </td>
                                <td nowrap class="text-right">
                                    <div style="float: left;" data-hisqty="historysave" data-seqpdt="<?=$nKey?>" class="xCNHistoryAndSaveQTY"><label style="color: #007bff;font-weight: bold; text-decoration: underline; cursor: pointer;">ดูประวัติ</label></div>
                                    <div style="float: right;">
                                        <?=str_replace(",","",number_format($aDataTableVal['SUMQTY'],2));?>
                                    </div>
                                </td>
                            <?php } ?>

                            <!--รับสินค้า-->
                            <?php 
                                if((float)$aDataTableVal['FCPcdQty'] == (float)$aDataTableVal['SUMRCVQTY']){ //บันทึกผลครบเเล้ว
                                    $tClassDisabledInGetStep3  = 'disabled';
                                    $tClassEventSaveInGetStep3 = '';
                                }else{
                                    $tClassDisabledInGetStep3  = '';
                                    $tClassEventSaveInGetStep3 = 'xCNHistoryAndSaveQTY';
                                }
                            ?>
                            <td class="text-center">
                                <button data-hisqty="saveget" data-seqpdt="<?=$nKey?>" class="<?=$tClassEventSaveInGetStep3?> <?=$tClassDisabledInGetStep3?> btn btn-outline-primary xCNBTNSaveStep3" type="button"> รับสินค้า </button>
                            </td>
                            <td nowrap class="text-right">
                                <div style="float: left;" data-hisqty="historyget" data-seqpdt="<?=$nKey?>" class="xCNHistoryAndSaveQTY"><label style="color: #007bff;font-weight: bold; text-decoration: underline; cursor: pointer;">ดูประวัติ</label></div>
                                <div style="float: right;">
                                    <?=str_replace(",","",number_format($aDataTableVal['SUMRCVQTY'],2));?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
            <?php } ?>
            <?php else:?>
                <tr><td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>

<!-- =========================================== ยืนยันการบันทึกผลเคลม =========================================== -->
<div id="odvCLMModalConfirmSaveResultClaim" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>ยืนยันการบันทึกผลเคลม ? <br> หลังจากการยืนยันการบันทึกผลเคลมแล้ว จะไม่สามารถแก้ไขข้อมูลได้</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNCLMModalConfirmSaveResultClaim" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalConfirm')?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel')?>
                </button>
            </div>
        </div>
    </div>
</div>


<script>

    //ดูประวัติ + บันทึกผล
    $('.xCNHistoryAndSaveQTY').unbind().click(function(){
        var tTypePage   = $(this).attr('data-hisqty');
        var nSeqPDT     = $(this).attr('data-seqpdt');

        switch(tTypePage) {
            case 'historysave':
                $('#odvCLMModalStep3SaveAndGet #ospTextModalStep3SaveAndGet').text('ประวัติบันทึกผลเคลม');
                break;
            case 'historyget':
                $('#odvCLMModalStep3SaveAndGet #ospTextModalStep3SaveAndGet').text('ประวัติจำนวนที่รับแล้ว');
                break;
            case 'saveclaim':
                $('#odvCLMModalStep3SaveAndGet #ospTextModalStep3SaveAndGet').text('บันทึกผลเคลม');
                break;
            case 'saveget':
                $('#odvCLMModalStep3SaveAndGet #ospTextModalStep3SaveAndGet').text('บันทึกการรับสินค้า');
                break;
            default:
        }

        //เปิด Modal
        $('#odvCLMModalStep3SaveAndGet').modal('show');

        //เก็บ Type ว่าเป็นบันทึกผลเคลม หรือ บันทึกผลรับ
        $('#ospTypeSave').text(tTypePage);

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep3SaveAndGet",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
                'nSeqPDT'               : nSeqPDT,
                'tTypePage'             : tTypePage
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);

                $('#ospSaveAndGetPDTCodeName').text(': ' + aReturnData['aList'][0].FTPcdPdtName + ' (' + aReturnData['aList'][0].FTPdtCode + ')');
                $('#ospSaveAndGetPDTCode').text(aReturnData['aList'][0].FTPdtCode);
                $('#ospSaveAndGetPDTBarcode').text(': ' + aReturnData['aList'][0].FTPcdBarCode);
                $('#ospSaveAndGetSPLName').text(': ' + aReturnData['aList'][0].FTSplName);
                $('#ospSaveAndGetQTYSend').text(parseInt(aReturnData['aList'][0].FCPcdQty).toFixed(2) + ' ' + 'รายการ');
                $('#ospQTYSend').text(parseInt(aReturnData['aList'][0].FCPcdQty).toFixed(2));
                $('#ospSeqPDT').text(aReturnData['aList'][0].FNPcdSeqNo);
                $('#ospSPLCode').text(aReturnData['aList'][0].FTSplCode);
                if(aReturnData['aList'][0].SUMRCVQTY == '' || aReturnData['aList'][0].SUMRCVQTY == null){
                    var nQTYGet = '0.00';
                    var nQTYBal = aReturnData['aList'][0].FCPcdQty;
                }else{
                    var nQTYGet = aReturnData['aList'][0].SUMRCVQTY;
                    if(nQTYGet == ".0000"){
                        nQTYGet = '0.00';
                    }else{
                        nQTYGet = nQTYGet;
                    }
                    var nQTYBal = aReturnData['aList'][0].FCPcdQty - nQTYGet;
                }   

                $('#ospSaveAndGetQTYGet').text(parseInt(nQTYGet).toFixed(2) + ' ' + 'รายการ');    //จำนวนรับเเล้ว
                $('#ospSaveAndGetQTYBal').text(parseInt(nQTYBal).toFixed(2) + ' ' + 'รายการ');    //จำนวนเหลือค้างรับ

                if(aReturnData['aList'][0].SUMQTY == '' || aReturnData['aList'][0].SUMQTY == null){
                    var nSUMQTY = 0;
                }else{
                    var nSUMQTY = aReturnData['aList'][0].SUMQTY;
                }

                $('#ospSaveClaimQTY').text(nSUMQTY + ' ' + 'รายการ');         //จำนวนที่บันทึก
                $('#ospQTYSaveClaim').text(nSUMQTY);

                //โชว์สรุป
                switch(tTypePage) {
                    case 'historysave':
                    case 'saveclaim':
                        $('.xCNShowForSaveClaim').css('display','block');
                        $('.xCNShowForSaveGet').css('display','none');
                        break;
                    case 'historyget':
                    case 'saveget':
                        $('.xCNShowForSaveClaim').css('display','none');
                        $('.xCNShowForSaveGet').css('display','block');
                        break;
                    default:
                }

                //เนื้อหาในแต่ละปุ่ม
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvContentStep3SaveAndGet').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();

                    //ซ่อนปุ่มยืนยันใน modal เพราะต้องการดูแค่ประวัติ
                    if(tTypePage == 'historysave' || tTypePage == 'historyget'){
                        $('.xCNCLMModalStep3SaveAndGet').hide();
                    }else{
                        if(tTypePage == 'saveget'){
                            var bCheckBeforeSave = $("#otbCLMStep3TableGet tbody tr td").hasClass('xCNTextNotfoundDataPdtTable');
                            if(bCheckBeforeSave == true){ //คือไม่พบข้อมูลสินค้า จะกดยืนยันไม่ได้
                                $('.xCNCLMModalStep3SaveAndGet').hide();
                            }else{
                                $('.xCNCLMModalStep3SaveAndGet').show();
                            }
                        }else{
                            $('.xCNCLMModalStep3SaveAndGet').show();
                        }
                    }
                }else{
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });

    //ยืนยันข้อมูล
    $('.xCNCLMModalStep3SaveAndGet').unbind().click(function(){
        var tTypePage = $('#ospTypeSave').text();
        var nSeqNo    = $('#ospSeqPDT').text();
        var tSPLCode  = $('#ospSPLCode').text();

        //บันทึกการรับสินค้า
        if(tTypePage == 'saveget'){

            var bCheckBeforeSave = $("#otbCLMStep3TableGet tbody tr td").hasClass('xCNTextNotfoundDataPdtTable');
            if(bCheckBeforeSave == true){   
                alert('ไม่พบสินค้า');
                return;
            }else{
                var nKeepQTYInTable = 0;
                $("#otbCLMStep3TableGet tbody tr").each(function( index ) {
                    nKeepQTYInTable = parseFloat(nKeepQTYInTable) + parseFloat($(this).find( "td:eq(2)" ).find('.xCNQtyGetStep3').val());
                });

                if(parseFloat(nKeepQTYInTable) > parseFloat($('#ospQTYSend').text())){
                    alert('จำนวนที่รับเข้า มากกว่าจำนวนที่ส่งเคลม กรุณาลองใหม่อีกครั้ง')
                    return;
                } 
            } 

            //บันทึกผลลงฐานข้อมูล
            JSxSaveResultToDB(tTypePage,nSeqNo,tSPLCode);
        }else if(tTypePage == 'saveclaim'){ //บันทึกผลเคลม	
            var bCheckBeforeSave = $("#otbCLMStep3TableCNDN tbody tr td").hasClass('xCNTextNotfoundDataPdtTable');
            if(bCheckBeforeSave == true){   
                alert('ไม่พบสินค้า');
                return;
            }else{

                //ปิด Modal
                $('#odvCLMModalStep3SaveAndGet').modal('hide');

                //เปิดโมดอลยืนยัน
                setTimeout(function(){ 
                    $('#odvCLMModalConfirmSaveResultClaim').modal('show');
                }, 800);

                //บันทึกผลลงฐานข้อมูล
                $('#odvCLMModalConfirmSaveResultClaim .xCNCLMModalConfirmSaveResultClaim').unbind().click(function(){
                    JSxSaveResultToDB(tTypePage,nSeqNo,tSPLCode);
                });
            }
        }
    });

    // บันทึกผลเคลม	+ บันทึกการรับเข้า
    function JSxSaveResultToDB(tTypePage,nSeqNo,tSPLCode){
        $('#odvCLMModalStep3SaveAndGet').modal('hide');

        // บันทึกผลเคลม	+ บันทึกการรับเข้า
        $.ajax({
            type    : "POST",
            url     : "docClaimStep3Save",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'tCLMDocNo'             : $("#ohdCLMDocNo").val(),
                'tTypePage'             : tTypePage,
                'nSeqNo'                : nSeqNo,
                'tSPLCode'              : tSPLCode
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                //โหลดหน้าจอใหม่
                setTimeout(function(){ 
                    JSxCLMStep3ResultLoadDatatable();
                }, 1000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
</script>