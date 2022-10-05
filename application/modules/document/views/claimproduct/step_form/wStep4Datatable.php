<style>
    .xCNBTNSaveStep4{
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
    <table id="otbCLMStep4Datatable" class="table table-striped">
        <thead>
            <tr class="xCNCenter">
                <th nowrap class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','หน่วย')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','แจ้งเคลมผู้จำหน่าย')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','วันที่ส่ง')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวนที่รับจากผู้จำหน่ายแล้ว')?></th>
                <th nowrap class="xCNTextBold" style="width: 120px;"><?=language('document/invoice/invoice','รับสินค้า-รับผล')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวนที่ลูกค้ารับของแล้ว')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($aDataList['rtCode'] == 1 ):?>
            <?php 
                if(FCNnHSizeOf($aDataList['raItems'])!=0){
                    $nSeq = 1;
                    foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                        <?php $nKey = $aDataTableVal['FNPcdSeqNo']; ?>
                        <tr>
                            <td nowrap style="text-align:center"><?=$nSeq++?></td>
                            <td nowrap><?=$aDataTableVal['FTPdtCode'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPcdPdtName'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPcdBarCode'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPunName'];?></td>
                            <td nowrap class="text-right"><?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?></td>
                            <td nowrap><?=($aDataTableVal['FTSplName'] == '') ? '-' : $aDataTableVal['FTSplName'];?></td>
                            <td nowrap class="text-center"><?=date('d/m/Y',strtotime($aDataTableVal['FDPcdDateReq']))?></td>

                            <!--จำนวนที่รับของแล้ว-->
                            <td nowrap class="text-right"><?=str_replace(",","",number_format($aDataTableVal['SUMQTY'],2));?></td>

                            <!--ลูกค้ารับสินค้า-->
                            <?php 
                                if((float)$aDataTableVal['SUMQTY'] == (float)$aDataTableVal['SUMRET']){ //บันทึกผลครบเเล้ว
                                    $tClassDisabledInSaveStep4  = 'disabled';
                                    $tClassEventSaveInSaveStep4 = '';
                                }else{
                                    $tClassDisabledInSaveStep4  = '';
                                    $tClassEventSaveInSaveStep4 = 'xCNSaveQTYForReturnCST';
                                }
                            ?>
                            <td class="text-center">
                                <button data-hisqty="savereturn" data-seqpdt="<?=$nKey?>" class="<?=$tClassEventSaveInSaveStep4?> <?=$tClassDisabledInSaveStep4?> btn btn-outline-primary xCNBTNSaveStep4" type="button"> รับสินค้า-รับผล </button>
                            </td>
                            
                            <!--จำนวนที่ลูกค้ารับของแล้ว-->
                            <td nowrap class="text-right">
                                <div style="float: left;" data-hisqty="historysavereturn" data-seqpdt="<?=$nKey?>" class="xCNSaveQTYForReturnCST"><label style="color: #007bff;font-weight: bold; text-decoration: underline; cursor: pointer;">ดูประวัติ</label></div>
                                <div style="float: right;">
                                    <?=str_replace(",","",number_format($aDataTableVal['SUMRET'],2));?>
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

<script>

    //ดูประวัติ + บันทึกผล
    $('.xCNSaveQTYForReturnCST').unbind().click(function(){
        var tTypePage   = $(this).attr('data-hisqty');
        var nSeqPDT     = $(this).attr('data-seqpdt');

        switch(tTypePage) {
            case 'historysavereturn':
                $('#odvCLMModalStep4SaveReturn #ospTextModalStep4SaveReturn').text('ประวัติลูกค้ารับสินค้า');
                break;
            case 'savereturn':
                $('#odvCLMModalStep4SaveReturn #ospTextModalStep4SaveReturn').text('บันทึกลูกค้ารับสินค้า-รับผลเคลม');
                break;
            default:
        }

        //เปิด Modal
        $('#odvCLMModalStep4SaveReturn').modal('show');

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep4SaveReturnDatatable",
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

                //เนื้อหาในแต่ละปุ่ม
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvContentStep4SaveReturn').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();

                    //ซ่อนปุ่มบันทึกใน modal เพราะต้องการดูแค่ประวัติ
                    if(tTypePage == 'historysavereturn'){
                        $('.xCNCLMModalStep4SaveReturn').hide();
                        $('.xCNStaCreateDNCN').hide();
                    }else{
                        $('.xCNCLMModalStep4SaveReturn').show();
                        $('.xCNStaCreateDNCN').show();
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
    $('.xCNCLMModalStep4SaveReturn').unbind().click(function(){

        //สร้างเอกสาร CN/DN
        if( $('#ocbStaCreateDNCN').prop('checked') == true){
            var nValueCreateCNDN = 1;
        }else{
            var nValueCreateCNDN = null;
        }

        var nSeq = $('#odhPcdSeqNo').val();

        //บันทึกลูกค้ารับสินค้า
        $.ajax({
            type    : "POST",
            url     : "docClaimStep4Save",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'tCLMDocNo'             : $("#ohdCLMDocNo").val(),
                'nSeq'                  : nSeq,
                'nCreateCNDN'           : nValueCreateCNDN,
                'tCSTCode'              : $('#oetCLMFrmCstCode').val()
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                // console.log(oResult);
                JSxCLMStep4ResultLoadDatatable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });
</script>