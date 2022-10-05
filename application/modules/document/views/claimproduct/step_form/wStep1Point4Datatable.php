
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbCLMStep1Point4DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','หน่วย')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','สถานะเคลมภายใน')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','หมายเหตุ')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','แจ้งเคลมผู้จำหน่าย')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','วันที่เเจ้ง')?></th>
                    <th nowrap class="xCNTextBold" style="width:20%;"><?=language('document/invoice/invoice','เปลี่ยน / เบิก [ระบุสินค้า]')?></th>
                    <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/invoice/invoice','จำนวนเปลี่ยน / เบิก')?></th>
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
                                <td style="text-align:center"><?=$nKey?></td>
                                <td nowrap><?=$aDataTableVal['FTPdtCode'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPcdPdtName'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPcdBarCode'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPunName'];?></td>
                                <td nowrap class="text-right xCNQtyClaim<?=$nKey?>"><?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?></td>

                                <?php 
                                    if($aDataTableVal['FTPcdStaClaim'] == 1){
                                        $tStatusClaim = "อนุมัติ";
                                    }else if($aDataTableVal['FTPcdStaClaim'] == 2){
                                        $tStatusClaim = "ไม่อนุมัติ";
                                    }
                                ?>
                                <td nowrap><?=$tStatusClaim?></td>
                                <td nowrap><?=($aDataTableVal['FTPcdRmk'] == '' ) ? '-' : $aDataTableVal['FTPcdRmk'];?></td>
                                <td nowrap><?=($aDataTableVal['FTSplName'] == '' ) ? '-' : $aDataTableVal['FTSplName'];?></td>
                                <td nowrap class="text-center"><?=date('d/m/Y',strtotime($aDataTableVal['FDPcdDateReq']))?></td>
                               
                                <td nowrap>
                                    <div style="width: 260px;">
                                        <div style="display: inline-block; float: left; margin-left: 15px;">

                                            <?php 
                                                if($aDataTableVal['Pick_PDTCode'] == '' || $aDataTableVal['Pick_PDTCode'] == null){
                                                    $tCheckboxChecked  = "";
                                                }else{
                                                    $tCheckboxChecked  = "checked";
                                                } 
                                            ?>

                                            <label class="fancy-checkbox">
                                                <input type="checkbox" 
                                                    data-seq="<?=$nKey?>" 
                                                    data-pdtcode="<?=$aDataTableVal['FTPdtCode']?>" 
                                                    data-pdtname="<?=$aDataTableVal['FTPcdPdtName'];?>" 
                                                    data-qtycliam="<?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],0));?>"
                                                    class="xCNCheckPickPDTClaim" <?=$tCheckboxChecked?>>
                                                <span class="">&nbsp;</span>
                                            </label>
                                        </div>
                                        <div style="display: inline-block; float: right;">
                                            <div class="input-group">
                                                <input  type="text" class="xCNSPL form-control xControlForm xCNHide" id="ohdPickPDTCode<?=$nKey?>" name="ohdPickPDTCode<?=$nKey?>" maxlength="50" value="<?=$aDataTableVal['Pick_PDTCode']?>">
                                                <input
                                                    type="text" 
                                                    class="form-control xControlForm xWPointerEventNone" 
                                                    id="oetPickPDTName<?=$nKey?>" name="oetPickPDTName<?=$nKey?>"
                                                    maxlength="100"
                                                    placeholder="กรุณาเลือกสินค้า" 
                                                    value="<?=$aDataTableVal['Pick_PDTName']?>" 
                                                    readonly
                                                >
                                                <span class="input-group-btn">
                                                    <?php 
                                                        if($aDataTableVal['Pick_PDTCode'] == '' || $aDataTableVal['Pick_PDTCode'] == null){
                                                            $tCheckboxDisabled  = "disabled";
                                                        }else{
                                                            $tCheckboxDisabled  = "";
                                                        } 
                                                    ?>
                                                    <button type="button" <?=$tCheckboxDisabled?> data-seq="<?=$nKey?>" class="btn xCNBtnBrowseAddOn xCNCLMBrowsePickPDT xCNCLMBTNPickPDT<?=$nKey?>">
                                                        <img src="<?=base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td nowrap>
                                    <div class="xWEditInLine<?=$nKey?>">
                                        <input type="text" readonly class="xCNPickQty form-control xCNInputNumeric xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?>" data-keepvalue="<?=str_replace(",","",number_format($aDataTableVal['FCPcdQtyPick'],0));?>" id="ohdPickQty<?=$nKey?>" name="ohdPickQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCPcdQtyPick'],0));?>" autocomplete="off">
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

<!--มีการกรอกจำนวนก่อน-->
<div id="odvCLMModalFail" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
            </div>
            <div class="modal-body">
                <p>ไม่อนุญาตให้ จำนวนเปลี่ยน / เบิก มากกว่าจำนวนที่ส่งเคลม</p>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNPrimery" type="button" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalConfirm')?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>

    //เลือกสินค้าที่จะยืม
    $('.xCNCLMBrowsePickPDT').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            var nSeq                  = $(this).attr('data-seq');
            window.nKeepPickPDTKey    = nSeq;

            var dTime               = new Date();
            var dTimelocalStorage   = dTime.getTime();
            $.ajax({
                type: "POST",
                url: "BrowseDataPDT",
                data: {
                    'Qualitysearch'   : [],
                    'PriceType'       : ["Cost", "tCN_Cost", "Company", "1"],
                    'SelectTier'      : ['PDT'],
                    'ShowCountRecord' : 10,
                    'NextFunc'        : 'JSxAfterChoosePickPDT',
                    'ReturnType'      : 'S',
                    'SPL'             : ['',''],
                    'BCH'             : ['',''],
                    'SHP'             : ['',''],
                    'TimeLocalstorage': dTimelocalStorage
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
            
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //อนุญาตยืม
    $(".xCNCheckPickPDTClaim").each(function() {
        var nSeq        = $(this).attr('data-seq');
        var tCheck      = $(this).prop('checked');
        var nQTYCliam   = $(this).attr('data-qtycliam');

        if(tCheck == true){
            $('#ohdPickQty'+nSeq).attr('readonly',false);
            $('#ohdPickQty'+nSeq).val(nQTYCliam);
        }else{
            $('#ohdPickQty'+nSeq).val(0);
        }
    });

    $('.xCNCheckPickPDTClaim').unbind().click(function(){
        var nSeq        = $(this).attr('data-seq');
        var tPDTCode    = $(this).attr('data-pdtcode');
        var tPDTName    = $(this).attr('data-pdtname');
        var nQTYCliam   = $(this).attr('data-qtycliam');
        
        if($(this).prop('checked') == true){
            var nValue = 1;
        }else{
            var nValue = 0;
        }

        if(nValue == 1){ //เลือก
            $('.xCNCLMBTNPickPDT'+nSeq).attr('disabled',false);
            $('#ohdPickQty'+nSeq).attr('readonly',false);

            //สินค้า
            $('#ohdPickQty'+nSeq).val(nQTYCliam);
            $('#ohdPickPDTCode'+nSeq).val(tPDTCode);
            $('#oetPickPDTName'+nSeq).val(tPDTName);

            //อัพเดท
            JSxStep1Point4UpdateDTTmp(nSeq , tPDTCode , 'PDTClaim');
            JSxStep1Point4UpdateDTTmp(nSeq , nQTYCliam , 'QTYPICKClaim');
        }else{ //ไม่เลือก
            $('#oetPickPDTName'+nSeq).val('');
            $('#oetPickPDTCode'+nSeq).val('');
            $('.xCNCLMBTNPickPDT'+nSeq).attr('disabled',true);
            $('#ohdPickQty'+nSeq).attr('readonly',true);

            //สืนค้า
            $('#ohdPickQty'+nSeq).val(0);
            $('#ohdPickPDTCode'+nSeq).val('');
            $('#oetPickPDTName'+nSeq).val('');

            //อัพเดท
            JSxStep1Point4UpdateDTTmp(nSeq , '' , 'PDTClaim');
            JSxStep1Point4UpdateDTTmp(nSeq , '' , 'QTYPICKClaim');
        }
    });

    //หลังจากเลือกสินค้า
    function JSxAfterChoosePickPDT(ptPdtData){
        var aPackData       = JSON.parse(ptPdtData);
        $('#ohdPickPDTCode'+nKeepPickPDTKey).val(aPackData[0].packData.PDTCode);
        $('#oetPickPDTName'+nKeepPickPDTKey).val(aPackData[0].packData.PDTName);

        //อัพเดท
        JSxStep1Point4UpdateDTTmp(nKeepPickPDTKey , aPackData[0].packData.PDTCode , 'PDTClaim');
    }

    $('.xCNPickQty').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){

            //จำนวน
            var nSeq        = $(this).attr('data-seq');

            //จำนวนส่งเคลม
            var nClaim      = $('.xCNQtyClaim'+nSeq).text();

            //จำนวนที่ยืม
            var nQty        = $('#ohdPickQty'+nSeq).val();

            if(nQty == 0){
                $('#ohdPickQty'+nSeq).val(1);
            }
            
            if(nQty > nClaim){
                $('#odvCLMModalFail').modal('show');
                $(this).val($(this).attr('data-keepvalue'));
                return;
            }else{
                nNextTab    = parseInt(nSeq)+1;
                $('.xWValueEditInLine'+nNextTab).focus().select();
                
                //อัพเดท
                JSxStep1Point4UpdateDTTmp(nSeq , nQty , 'QTYPICKClaim');
            }
        }
    });

    //Update ข้อมูลสินค้า
    function JSxStep1Point4UpdateDTTmp(pnSeq,ptValue,ptType){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep1Point4UpdatePickPDT",
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