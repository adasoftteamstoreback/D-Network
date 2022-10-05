
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive" style="height: 160px;">
        <table id="otbCLMStep1Point2DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','หน่วย')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ระยะทางรับประกัน')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ระยะเวลารับประกัน')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','เงื่อนไขรับประกัน')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','สถานะเคลมภายใน')?></th>
                    <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/invoice/invoice','หมายเหตุ')?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($aDataList['rtCode'] == 1 ):?>
                <?php 
                    if(FCNnHSizeOf($aDataList['raItems'])!=0){
                        foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                            <?php $nKey = $aDataTableVal['FNPcdSeqNo']; ?>
                            <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?=$aDataTableVal['FTPcdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>"
                                data-key="<?=$nKey?>"
                                data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>"
                                data-seqno="<?=$nKey?>"
                                data-qty="<?=$aDataTableVal['FCPcdQty'];?>" >
                                <td nowrap style="text-align:center"><?=$nKey?></td>
                                <td nowrap><?=$aDataTableVal['FTPdtCode'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPcdPdtName'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPcdBarCode'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPunName'];?></td>
                                <td nowrap class="text-right"><?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?></td>
                                <td nowrap class="text-center"><?=($aDataTableVal['FCPsvWaDistance'] == '' ) ? '-' : number_format($aDataTableVal['FCPsvWaDistance'],2);?></td>
                                <input type="hidden" class="xCNPsvWaQtyDay" value=<?=($aDataTableVal['FNPsvWaQtyDay'] == '' ) ? '0' : $aDataTableVal['FNPsvWaQtyDay'];?> >
                                <td nowrap class="text-center"><?=($aDataTableVal['FNPsvWaQtyDay'] == '' ) ? '-' : $aDataTableVal['FNPsvWaQtyDay'] . ' วัน' ;?></td>
                                <td nowrap class="text-left"><?=($aDataTableVal['FTPsvWaCond'] == '' ) ? '-' : $aDataTableVal['FTPsvWaCond'];?></td>
                                <td nowrap>
                                    <select class="selectpicker form-control xCNChangeStatusClaim" maxlength="1" data-seq="<?=$nKey?>" onchange="JSxStep1Point2ChangeStatusClaim(this.value,'<?=$nKey?>')">
                                        <option value="1" <?php echo $aDataTableVal['FTPcdStaClaim'] == "1" ? "selected" : ""; ?>>อนุมัติ</option>
                                        <option value="2" <?php echo $aDataTableVal['FTPcdStaClaim'] == "2" ? "selected" : ""; ?>>ไม่อนุมัติ</option>
                                    </select>
                                </td>
                                <td nowrap>
                                    <div class="xWEditInLine<?=$nKey?>">
                                        <input type="text"  style="width:150px;" class="xCNRemark form-control xCNPdtEditInLine text-left xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdRmk<?=$nKey?>" name="ohdRmk<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="50" value="<?=$aDataTableVal['FTPcdRmk']?>" autocomplete="off">
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
</div>

<script>
    $( document ).ready(function() {
        //แก้ไขจำนวน
        JSxCLMStep1Point1EditQty();

        if($('#oetCLMRefIntDate').val() == ''){
            $('.xCNChangeStatusClaim option[value="2"]').attr("selected", "selected");
        }else{
            const dDateDoc       = new Date($('#oetCLMRefIntDate').val());
            const dDateCurrent   = new Date();
            const dDiffTime      = Math.abs(dDateCurrent - dDateDoc);
            const dDiffDays      = Math.ceil(dDiffTime / (1000 * 60 * 60 * 24)); 
            if(dDiffDays <= $('.xCNPsvWaQtyDay').val() ){
                $('.xCNChangeStatusClaim option[value="1"]').attr("selected", "selected");
                JSxStep1Point2ChangeStatusClaim(1,1);
            }else{
                $('.xCNChangeStatusClaim option[value="0"]').attr("selected", "selected");
            }
        }

        $('.selectpicker').selectpicker();	
    });

    //ปรับสถานะเคลม
    function JSxStep1Point2ChangeStatusClaim(pnValue,pnKey){
        //อัพเดท
        JSxStep1Point2UpdateDTTmp(pnKey,pnValue,'StatusClaim');
    }

    //อัพเดทหมายเหตุ
    $('.xCNRemark').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var nSeq    = $(this).attr('data-seq');
            var tRmk    = $('#ohdRmk'+nSeq).val();
            nNextTab    = parseInt(nSeq)+1;
            $('.xWValueEditInLine'+nNextTab).focus().select();

            //อัพเดท
            JSxStep1Point2UpdateDTTmp(nSeq,tRmk,'RmkClaim');
        }
    });

    //Update ข้อมูล หมายเหตุ และ สถานะเคลมภายใน
    function JSxStep1Point2UpdateDTTmp(pnSeq,ptValue,ptType){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep1Point2UpdateStaAndRmk",
            data    : {
                'tCLMDocNo'         : tCLMDocNo,
                'nSeq'              : pnSeq,
                'tValueUpdate'      : ptValue,
                'tTypeUpdate'       : ptType
            },
            catch   : false,
            timeout : 0,
            success : function (oResult){ },
            error   : function (jqXHR, textStatus, errorThrown) { }
        });
    }
</script>