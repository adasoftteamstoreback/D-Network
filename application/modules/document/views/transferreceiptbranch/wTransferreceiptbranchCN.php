<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPITBChoose')?></th>
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPITBNo')?></th>
                        <th class="xCNTextBold"><?=language('document/transferreceiptNew/transferreceiptNew','tTBIDocNo')?></th>
                        <th class="xCNTextBold"><?=language('document/transferreceiptNew/transferreceiptNew','tTBITablePDTBch')?></th>
                        <th class="xCNTextBold"><?=language('document/transferreceiptNew/transferreceiptNew','tTBITablePDTShp')?></th>
                        <th class="xCNTextBold"><?=language('document/transferreceiptNew/transferreceiptNew','tTBITablePDTName')?></th>
                        <th class="xCNTextBold"><?=language('document/transferreceiptNew/transferreceiptNew','tTBITablePDTCode')?></th>
                        <th class="xCNTextBold"><?=language('document/transferreceiptNew/transferreceiptNew','tTBITablePDTUnit')?></th>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                    <?php if($aDataCN['rtCode'] != 800){
                        $aItem = $aDataCN['raItems']; ?>
                        <?php for($i=0; $i<FCNnHSizeOf($aItem); $i++){ ?>
                            <tr 
                                class="text-center xCNTextDetail2 nItem<?=$i?> xWPdtItem"
                                data-index="<?=$aItem[$i]['rtRowID'];?>"
                                data-docno="<?=$aItem[$i]['FTXshDocNo'];?>"
                                data-pdtcode="<?=$aItem[$i]['FTPdtCode'];?>" 
                                data-pdtname="<?=$aItem[$i]['FTXsdPdtName'];?>"
                                data-puncode="<?=$aItem[$i]['FTPunCode'];?>"
                                data-seqitem="<?=$aItem[$i]['FNXsdSeqNo'];?>"
                                data-barcode="<?=$aItem[$i]['FTBarCode'];?>">
                                <td class="text-center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?=$aItem[$i]['rtRowID']?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-left"><?=$aItem[$i]['rtRowID'];?></td>
                                <td class="text-left"><?=$aItem[$i]['FTXshDocNo'];?></td>
                                <td class="text-left"><?=$aItem[$i]['FTBchName'];?></td>
                                <td class="text-left"><?=$aItem[$i]['FTShpName'];?></td>
                                <td class="text-left"><?=$aItem[$i]['FTXsdPdtName'];?></td>
                                <td class="text-left"><?=$aItem[$i]['FTPdtCode'];?></td>
                                <td class="text-left"><?=$aItem[$i]['FTPunName'];?></td>
                            </tr>
                        <?php } ?>
                    <?php }else{ ?>
                        <tr><td class="text-center xCNTextDetail2 xWTBITextNotfoundDataPdtTable" colspan="100%"><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $("#osmConfirmPDTCN").click(function(){var t=[];$(".ocbListItem:checked").each(function(){var e=$(this).parents(".xWPdtItem").data("docno"),o=$(this).parents(".xWPdtItem").data("pdtcode"),a=$(this).parents(".xWPdtItem").data("puncode"),d=$(this).parents(".xWPdtItem").data("barcode"),n=$(this).parents(".xWPdtItem").data("seqitem");t.push({tDocNo:e,pnPdtCode:o,ptPunCode:a,ptBarCode:d,ptSeqItem:n})});var e=$("#oetTBIDocNo").val();0!=t.length?$.ajax({type:"POST",url:"docTBIEventAddPdtIntoDTDocTemp",data:{tTBIDocNo:e,tTBIPdtData:JSON.stringify(t),tType:"CN"},cache:!1,timeout:0,success:function(e){t=[],JSvTRNLoadPdtDataTableHtml()},error:function(t,e,o){JCNxResponseError(t,e,o)}}):console.log("no item")});
</script>