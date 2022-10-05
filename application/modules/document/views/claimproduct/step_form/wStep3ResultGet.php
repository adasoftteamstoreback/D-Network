<div class="col-lg-12">
    <div class="table-responsive">
        <table id="otbCLMStep3TableGet" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ลำดับ')?></th>
                    <th nowrap class="xCNTextBold" style="width:200px;" ><?=language('document/invoice/invoice','สินค้าที่รับ')?></th>
                    <th nowrap class="xCNTextBold" style="width:150px;" ><?=language('document/invoice/invoice','จำนวนที่่รับ')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','เลขที่ใบส่งเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','เลขที่อ้างอิงผลเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ผลเคลม')?></th>
                    <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','ส่วนลดการเคลม')?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($aDataList['rtCode'] == 1 ):?>
                <?php 
                    if(FCNnHSizeOf($aDataList['raItems'])!=0){
                        foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                            <?php $nKey = $aDataTableVal['FNWrnSeq']; ?>
                            <tr
                                data-key="<?=$nKey?>"
                                data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>"
                                data-seqno="<?=$nKey?>" >
                                <td class="text-center" ><?=$nKey?></td>
                                <td>
                                    <div class="">
                                        <div class="input-group">
                                            <input  type="text" class="form-control xControlForm xCNHide" id="oetPdtCodeGetStep3<?=$nKey?>" name="oetPdtCodeGetStep3<?=$nKey?>" value="<?=$aDataTableVal['FTPdtCode']?>">
                                            <input
                                                type="text" 
                                                class="form-control xControlForm xWPointerEventNone" 
                                                id="oetPdtNameGetStep3<?=$nKey?>" name="oetPdtNameGetStep3<?=$nKey?>"
                                                maxlength="255"
                                                value="<?=$aDataTableVal['Step3_PDTName_Wrn']?>" 
                                                readonly
                                                style="min-width: 100px !important;"
                                            >
                                            <span class="input-group-btn">
                                                <button type="button" data-seq="<?=$nKey?>" class="btn xCNBtnBrowseAddOn xCNCLMBrowsePDTStep3">
                                                    <img src="<?=base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                        
                                        <?php 
                                            if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){ //เคลมไม่ได้
                                                echo '<p style="color:red; font-size: 15px !important;">เคลมไม่ได้</p>';
                                            }
                                        ?>
                                    </div>
                                </td>

                                <td class="text-right">
                                    <div class="xWEditInLine<?=$nKey?>">
                                        <input type="text" style="min-width: 100px !important;" class="xCNQtyGetStep3 xCNInputNumericWithDecimal form-control xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?>" id="oetQtyGetStep3<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="6" 
                                        value="<?=number_format($aDataTableVal['FCWrnPdtQty'],2);?>" autocomplete="off">
                                    </div>
                                </td>

                                <td nowrap><?=($aDataTableVal['FTPcdRefTwo'] == '' ) ? '-' : $aDataTableVal['FTPcdRefTwo'];?></td>
                                <td nowrap><?=($aDataTableVal['FTWrnRefDoc'] == '' ) ? '-' : $aDataTableVal['FTWrnRefDoc'];?></td>

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
                                    if($aDataTableVal['FCWrnDNCNAmt'] == '-100' || $aDataTableVal['FCWrnDNCNAmt'] == -100){ //เคลมไม่ได้
                                        $tTextDNCN = '0.00';
                                    }else{
                                        $tTextDNCN = number_format($aDataTableVal['FCWrnDNCNAmt'],2);
                                    } 
                                ?>
                                <td class="text-right" nowrap><?=$tTextDNCN;?></td>
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

    //เลือกสินค้า
    $('.xCNCLMBrowsePDTStep3').unbind().click(function(){ 
        var dTime               = new Date();
        var dTimelocalStorage   = dTime.getTime();

        var nSeq                  = $(this).attr('data-seq');
        window.nKeepPDTKey        = nSeq;
                
        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                'Qualitysearch'   : [],
                'PriceType'       : ["Cost", "tCN_Cost", "Company", "1"],
                'SelectTier'      : ['PDT'],
                'ShowCountRecord' : 10,
                'NextFunc'        : 'JSxAfterChoosePDTStep3',
                'ReturnType'      : 'S',
                'SPL'             : ['',''],
                'BCH'             : ['',''],
                'SHP'             : ['',''],
                'TimeLocalstorage': dTimelocalStorage,
                'tTYPEPDT'        : '1,2,3,4,5'
            },
            cache: false,
            timeout: 0,
            success: function(tResult){
                $('#odvModalDOCPDT').modal({backdrop: 'static', keyboard: false})  
                $('#odvModalDOCPDT').modal({ show: true });

                //remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $('#odvModalsectionBodyPDT').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    //หลังจากเลือกสินค้า
    function JSxAfterChoosePDTStep3(ptPdtData){
        var aPackData     = JSON.parse(ptPdtData);
        var aItem         = aPackData[0].packData;

        $('#oetPdtNameGetStep3'+nKeepPDTKey).val(aItem.PDTName);
        $('#oetPdtCodeGetStep3'+nKeepPDTKey).val(aItem.PDTCode);

        JSxStep3UpdateDTTmp(nKeepPDTKey,aItem.PDTCode,'Step3PDT');
    }

    //อัพเดทจำนวน
    $('.xCNQtyGetStep3').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var nSeq    = $(this).attr('data-seq');
            var nQty    = $('#oetQtyGetStep3'+nSeq).val();
            nNextTab    = parseInt(nSeq)+1;
            $('.xWValueEditInLine'+nNextTab).focus().select();
            
            JSxStep3UpdateDTTmp(nSeq,nQty,'Step3QTY');
        }
    });

    //Update ข้อมูลสินค้า
    function JSxStep3UpdateDTTmp(pnSeq,ptValue,ptType){
        $.ajax({
            type    : "POST",
            url     : "docClaimStep3Update",
            data    : {
                'tCLMDocNo'         : $("#ohdCLMDocNo").val(),
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