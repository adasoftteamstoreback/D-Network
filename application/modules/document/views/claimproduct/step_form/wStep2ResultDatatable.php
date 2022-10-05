
<div class="table-responsive">
    <table id="otbCLMStep2Datatable" class="table table-striped">
        <thead>
            <tr class="xCNCenter">
                <th nowrap class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','หน่วย')?></th>
                <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','แจ้งเคลมผู้จำหน่าย')?></th>
                <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','วันที่แจ้ง')?></th>
                <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','วันที่ผู้จำหน่ายเข้ามารับสินค้า')?></th>
                <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ผู้เข้ามารับสินค้า')?></th>
                <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','หมายเหตุ')?></th>
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
                            <td nowrap class="text-center">
                                <div >
                                    <div class="">
                                        <div class="input-group">
                                            <?php 
                                                if($aDataTableVal['DateSplGet'] == '' || $aDataTableVal['DateSplGet'] == null){
                                                    $tDateReq = date('Y-m-d');
                                                }else{
                                                    $tDateReq = $aDataTableVal['DateSplGet'];
                                                }
                                            ?>
                                            <input type="text" style="min-width: 100px !important;" class="xCNDateGetStep2 form-control xCNDatePicker xCNInputMaskDate" id="ohdSplDate<?=$nKey?>" name="ohdSplDate<?=$nKey?>" data-seq="<?=$nKey?>" placeholder="YYYY-MM-DD" value="<?=$tDateReq?>" autocomplete="off" onchange="JSxStep2ChangeDate(this.value,'<?=$nKey?>')">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn xCNBtnDateTime xCNBtnOpenPicker">
                                                    <img src="<?=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td nowrap>
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" style="min-width: 100px !important;"  class="xCNUserGetStep2 form-control xCNPdtEditInLine text-left xWValueEditInLineUser<?=$nKey?>" id="ohdUserGetStep2<?=$nKey?>" name="ohdUserGetStep2<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="50" value="<?=$aDataTableVal['FTPctSplStaff']?>" autocomplete="off">
                                </div>
                            </td>
                            <td nowrap>
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" style="min-width: 100px !important;" class="xCNRemarkStep2 form-control xCNPdtEditInLine text-left xWValueEditInLineRmk<?=$nKey?>" id="ohdRmkStep2<?=$nKey?>" name="ohdRmkStep2<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="50" value="<?=$aDataTableVal['FTPcdSplRmk']?>" autocomplete="off">
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
    $( document ).ready(function() {

        //ถ้ามีการสร้างเอกสารแล้ว
        if($('#ohdCLMStaPrc').val() >= 3){
            $('.xCNConfirmSendPDTToSPL').hide();

            //เปลี่ยนวันที่เป็น Text
            $("#otbCLMStep2Datatable tbody tr").each(function( index ) {
                var dDateValue          = $(this).find('td:eq(8)').find('.xCNDateGetStep2').val(); 
                if(dDateValue != '' || dDateValue != null){
                    var dDateValueFormat    = dDateValue.split("-");
                    var dDate               = dDateValueFormat[2]+'/'+dDateValueFormat[1]+'/'+dDateValueFormat[0];
                    $(this).find('td:eq(8)').html(dDate);
                }
                
                //เปลี่ยนคนรับเป็น Text
                var tUserGetStep2 = $(this).find('td:eq(9)').find('.xCNUserGetStep2').val();
                $(this).find('td:eq(9)').html(tUserGetStep2);

                //เปลี่ยนเหตุผลเป็น Text 
                var tRemarkStep2 = $(this).find('td:eq(10)').find('.xCNRemarkStep2').val(); 
                $(this).find('td:eq(10)').html(tRemarkStep2);
            });

            //เปิดปุ่มพิมพ์
            $('.xCNPrintSendPDTToSPL').css('display','block');
        }

        $('.xCNDatePicker').datepicker({
            format                  : "yyyy-mm-dd",
            todayHighlight          : true,
            enableOnReadonly        : false,
            disableTouchKeyboard    : true,
            autoclose               : true
        });

        $('.xCNBtnOpenPicker').click(function(event){
            $('.xCNDateGetStep2').datepicker('show');
            event.preventDefault();
        });
    });

    //วันที่เข้ามารับของ
    function JSxStep2ChangeDate(pnValue,pnKey){
        //อัพเดท
        if(pnValue == '' || pnValue == null){
            
        }else{
            JSxStep2UpdateDTTmp(pnKey,pnValue,'DateGetClaim');
        }
    }

    //หมายเหตุ
    $('.xCNRemarkStep2').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var nSeq    = $(this).attr('data-seq');
            var tRmk    = $('#ohdRmkStep2'+nSeq).val();
            nNextTab    = parseInt(nSeq)+1;
            $('.xWValueEditInLineRmk'+nNextTab).focus().select();

            //อัพเดท
            JSxStep2UpdateDTTmp(nSeq,tRmk,'RmkGet');
        }
    });

    //ชื่อคนมารับของ
    $('.xCNUserGetStep2').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var nSeq    = $(this).attr('data-seq');
            var tRmk    = $('#ohdUserGetStep2'+nSeq).val();
            nNextTab    = parseInt(nSeq)+1;
            $('.xWValueEditInLineUser'+nNextTab).focus().select();

            //อัพเดท
            JSxStep2UpdateDTTmp(nSeq,tRmk,'UserGet');
        }
    });
    
    //Update ข้อมูล หมายเหตุ และ สถานะเคลมภายใน
    function JSxStep2UpdateDTTmp(pnSeq,ptValue,ptType){
        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep2Update",
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