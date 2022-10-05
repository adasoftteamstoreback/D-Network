<div class="col-lg-12" style="margin-bottom: 15px;">

    <?php 
        switch ($tTypePage) {
            case 'historysavereturn':  //ประวัติการรับสินค้าของลูกค้า ?>
                <label class="xCNLabelFrm" style="vertical-align: sub;">รายการสินค้าข้อมูลลูกค้ารับสินค้า</label>
        <?php break;
            case 'savereturn':   //บันทึกการรับสินค้าของลูกค้า ?>
                <label class="xCNLabelFrm" style="vertical-align: sub;">รายการสินค้า</label>
        <?php break;
        default:
            break;
        }
    ?>
</div>

<input type="hidden" id="odhPcdSeqNo" value="<?=$nSeqPDT?>">
<div class="col-lg-12">
    <div class="table-responsive">
        <table id="otbCLMStep4SaveAndHistoryDatatable" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                    <!-- <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th> -->
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','จำนวนที่รับจากผู้จำหน่ายแล้ว')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ผลเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ส่วนลดการเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','สถานะ')?></th>
                    <th nowrap class="xCNTextBold" style="width:180px;"><?=language('document/invoice/invoice','วันที่รับ')?></th>
                    <th nowrap class="xCNTextBold" style="width:150px;"><?=language('document/invoice/invoice','หมายเหตุ')?></th>
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

                                <?php 
                                    if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){ //เคลมไม่ได้
                                        $tTextPDT = $aDataTableVal['FTPcdPdtName'] . '<br>' . '<p style="color:red; font-size: 15px !important;">เคลมไม่ได้</p>';
                                    }else{
                                        $tTextPDT = $aDataTableVal['FTPcdPdtName'];
                                    } 
                                ?>
                                <td nowrap><?=$tTextPDT;?></td>
                                <td nowrap><?=$aDataTableVal['FTPcdBarCode'];?></td>
                                <!-- <td nowrap class="text-right"><?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?></td> -->

                                <!--จำนวนที่รับของแล้ว-->
                                <td nowrap class="text-right xCNQtyStep4<?=$nKey?>"><?=str_replace(",","",number_format($aDataTableVal['SUMQTY'],2));?></td>

                                <?php 
                                    $tTextDNResultClaim  = 'N/A';
                                    if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){
                                        $tTextDNResultClaim = 'เคลมไม่ได้';
                                    }else{
                                        if($aDataTableVal['FCWrnPercent'] == 100){
                                            $tTextDNResultClaim = 'เปลี่ยนสินค้าใหม่';
                                        }else{
                                            $tTextDNResultClaim = 'ชดเชยมูลค่า';
                                        }
                                    }
                                    //ของเดิม
                                    // number_format($aDataTableVal['FCWrnPercent'],2);
                                ?>
                                <td class="text-left" nowrap><?=$tTextDNResultClaim;?></td>

                                <?php 
                                    if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){
                                        $tTextDNCN = '0.00';
                                    }else{
                                        $tTextDNCN = number_format($aDataTableVal['FCWrnDNCNAmt'],2);
                                    } 
                                ?>
                                <td class="text-right" nowrap><?=$tTextDNCN;?></td>

                                <?php
                                    if((float)$aDataTableVal['SUMQTY']  == '' || (float)$aDataTableVal['SUMQTY']  == null){
                                        $tTextReturnCstSuccess  = 'รอรับสินค้าเข้าระบบ';
                                        $tClassStaDoc           = 'xWCSSCarrotColor';
                                    }else{
                                        if((float)$aDataTableVal['SUMQTY'] == (float)$aDataTableVal['SUMRET']){ //บันทึกผลครบเเล้ว
                                            $tTextReturnCstSuccess  = 'ลูกค้ารับแล้ว';
                                            $tClassStaDoc           = 'xWCSSGreenColor';
                                        }else{
                                            $tTextReturnCstSuccess  = 'ลูกค้ายังไม่รับ';
                                            $tClassStaDoc           = 'xWCSSCarrotColor';
                                        }
                                    }
                                ?>
                                <td class="text-center" nowrap>
                                    <label class="<?=$tClassStaDoc?>">
                                        <?=$tTextReturnCstSuccess?>
                                    </label>
                                </td>

                                <!--วันที่ลูกค้ารับของ-->
                                <?php 
                                    switch ($tTypePage) {
                                        case 'historysavereturn':  //ประวัติการรับสินค้าของลูกค้า ?>
                                            <td class="text-center"><?=date('d/m/Y',strtotime($aDataTableVal['FDRetDate']));?></td>
                                            <td><?=($aDataTableVal['FTRetRmk'] == '' ) ? '-' : $aDataTableVal['FTRetRmk']; ?></td>
                                    <?php break;
                                        case 'savereturn':   //บันทึกการรับสินค้าของลูกค้า ?>
                                            <td nowrap class="text-center">
                                                <div>
                                                    <div class="">
                                                        <div class="input-group">
                                                            <input type="text" style="min-width: 100px;" class="xCNDateGetStep4 form-control xCNDatePickerStep4 xCNInputMaskDate" id="ohdDateCst<?=$nKey?>" name="ohdDateCst<?=$nKey?>" data-seq="<?=$nKey?>" placeholder="YYYY-MM-DD" value="<?=date('Y-m-d')?>" autocomplete="off" 
                                                            onchange="JSxStep4ChangeDate(this.value,'<?=$aDataTableVal['FNPcdSeqNo']?>','<?=$aDataTableVal['FNWrnSeq']?>','<?=$aDataTableVal['FNRcvSeq']?>')">
                                                            <span class="input-group-btn">
                                                                <button type="button" class="btn xCNBtnDateTime xCNBtnDatePickerStep4">
                                                                    <img src="<?=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                                                </button>
                                                            </span>
                                                        </div>
                                                    <div>
                                                <div>
                                            </td>
                                            <td nowrap>
                                                <div class="xWEditInLine<?=$nKey?>">
                                                    <input type="text"  style="min-width: 100px;" class="xCNRemarkStep4 form-control xCNPdtEditInLine text-left" 
                                                    id="ohdRmkStep4<?=$aDataTableVal['FNPcdSeqNo']?><?=$aDataTableVal['FNWrnSeq']?><?=$aDataTableVal['FNRcvSeq']?>"
                                                    data-Pcd="<?=$aDataTableVal['FNPcdSeqNo']?>" 
                                                    data-Wrn="<?=$aDataTableVal['FNWrnSeq']?>"
                                                    data-Rcv="<?=$aDataTableVal['FNRcvSeq']?>"
                                                    maxlength="50" value="" autocomplete="off">
                                                </div>
                                            </td>
                                    <?php break;
                                    default:
                                        break;
                                    }
                                ?>
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
        nValueCNDN = 0;
        $("#otbCLMStep4SaveAndHistoryDatatable tbody tr").each(function( index ) {
            nValueCNDN = parseFloat(nValueCNDN) + parseFloat($(this).find( "td:eq(5)" ).text());
        }); 

        //ถ้ามูลค่า CNDN มากกว่า 0 ต้อง defult สร้างใบลดหนี้ + ใบเพิ่มหนี้
        if(nValueCNDN > 0){
            $('#ocbStaCreateDNCN').attr('checked',true);
            $('#ocbStaCreateDNCN').attr('disabled',false);
            $('.xCNStaCreateDNCN span').removeClass('xCNDocDisabled');
        }else{
            $('#ocbStaCreateDNCN').attr('checked',false);
            $('#ocbStaCreateDNCN').attr('disabled',true);
            $('.xCNStaCreateDNCN span').addClass('xCNDocDisabled');
        }
        
    });
    
    $('.xCNDatePickerStep4').datepicker({
        format                  : "yyyy-mm-dd",
        todayHighlight          : true,
        enableOnReadonly        : false,
        disableTouchKeyboard    : true,
        autoclose               : true
    });

    
    $('.xCNBtnDatePickerStep4').click(function(event){
        $('.xCNDateGetStep4').datepicker('show');
        event.preventDefault();
    });


    //วันที่ส่งคืนให้ลูกค้า
    function JSxStep4ChangeDate(pnValue,pnPcdSeq,pnWrnSeq,pnRcvSeq){
        //อัพเดท
        if(pnValue == '' || pnValue == null){
            
        }else{
            JSxStep4UpdateDTTmp(pnPcdSeq,pnWrnSeq,pnRcvSeq,pnValue,'DateStep4');
        }
    }

    //หมายเหตุ
    $('.xCNRemarkStep4').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var pnPcdSeq    = $(this).attr('data-Pcd');
            var pnRcvSeq    = $(this).attr('data-Rcv');
            var pnWrnSeq    = $(this).attr('data-Wrn');
            var tRmk        = $('#ohdRmkStep4'+pnPcdSeq+pnWrnSeq+pnRcvSeq).val();
         
            //อัพเดท
            JSxStep4UpdateDTTmp(pnPcdSeq,pnWrnSeq,pnRcvSeq,tRmk,'RmkStep4');
        }
    });

    //Update ข้อมูล หมายเหตุ และ วันที่ส่งคืนให้ลูกค้า
    function JSxStep4UpdateDTTmp(pnPcdSeq,pnWrnSeq,pnRcvSeq,ptValue,ptType){
        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep4Update",
            data    : {
                'tCLMDocNo'         : tCLMDocNo,
                'pnRcvSeq'          : pnRcvSeq,
                'pnWrnSeq'          : pnWrnSeq,
                'pnPcdSeq'          : pnPcdSeq,
                'tValueUpdate'      : ptValue,
                'tTypeUpdate'       : ptType
            },
            catch   : false,
            timeout : 0,
            success : function (oResult){ console.log(oResult) },
            error   : function (jqXHR, textStatus, errorThrown) { console.log(jqXHR) }
        });
    }
</script>